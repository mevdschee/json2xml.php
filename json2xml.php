<?php

function json2xml($json) {
    $a = json_decode($json);
    $c = new SimpleXMLElement('<root/>');
    $t = function($v) {
        $type = gettype($v);
        switch($type) {
            case 'integer': return 'number';
            case 'double':  return 'number';
            default: return strtolower($type);
        }
    };
    $f = function($f,$c,$a,$s=false) use ($t) {
        $c->addAttribute('type', $t($a));
        if (is_scalar($a)||is_null($a)) {
            if(is_bool($a)){ 
                $c[0] = $a?'true':'false';
            } else {
                $c[0] = $a;
            }
        } else {
            foreach($a as $k=>$v) {
                if ($k=='__type' && is_object($a)) {
                    $c->addAttribute('__type', $v);
                } else {
                    if(is_object($v)) {
                        $ch=$c->addChild($s?'item':$k);
                        $f($f,$ch,$v);
                    } else if(is_array($v)) {
                        $ch=$c->addChild($s?'item':$k);
                        $f($f,$ch,$v,true);
                    } else if(is_bool($v)) {
                        $ch=$c->addChild($s?'item':$k,$v?'true':'false');
                        $ch->addAttribute('type', $t($v));
                    } else {
                        $ch=$c->addChild($s?'item':$k,$v);
                        $ch->addAttribute('type', $t($v));
                    }
                }
            }
        }
    };
    $f($f,$c,$a,$t($a)=='array');
    return trim($c->asXML());
}

function xml2json($xml) {
    $a = dom_import_simplexml(simplexml_load_string($xml));
    $t = function($v) {
        return $v->getAttribute('type');
    };
    $f = function($f,$a) use ($t) {
        $c = null;
        if ($t($a)=='null') {
            $c = null; 
        } else if ($t($a)=='boolean') {
            $b = substr(strtolower($a->textContent),0,1);
            $c = in_array($b,array('1','t'));
        } else if ($t($a)=='number') {
            $c = $a->textContent+0; 
        } else if ($t($a)=='string') {
            $c = $a->textContent;
        } else if ($t($a)=='object') {
            $c = array();
            if ($a->getAttribute('__type')) {
                $c['__type'] = $a->getAttribute('__type');
            }
            for ($i=0;$i<$a->childNodes->length;$i++) {
                $v = $a->childNodes[$i];
                $c[$v->nodeName] = $f($f,$v);
            }
            $c = (object)$c;
        } else if ($t($a)=='array') {
            $c = array();
            for ($i=0;$i<$a->childNodes->length;$i++) {
                $v = $a->childNodes[$i];
                $c[$i] = $f($f,$v);
            }
        }
        return $c;
    };
    $c = $f($f,$a);
    return json_encode($c);
}