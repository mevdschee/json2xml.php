<?php

function json2xml($json) {
    $a = json_decode($json);
    $t = function($v) {
        switch(gettype($v)) {
            case 'boolean': return 'boolean';
            case 'integer': return 'number';
            case 'double':  return 'number';
            case 'string':  return 'string';
            case 'array':   return 'array';
            case 'object':  return 'object';
            case 'NULL':    return 'null';
        }
    };
    $c = new SimpleXMLElement('<root/>');
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
    $p = function ($p,$n) {
        foreach($n->childNodes as $node) {
            if($node->hasChildNodes()) {
                $p($p,$node);
            } else {
                if($n->hasAttributes() && strlen($n->nodeValue)){
                    $n->setAttribute('value', $node->textContent);
                    $node->nodeValue = '';
                }
            }
        }
    };
    $dom = new DOMDocument();
    $dom->loadXML($xml);
    $p($p,$dom);
    $xml = simplexml_load_string($dom->saveXML());
    $a = json_decode(json_encode($xml));
    $f = function($f,&$a) {
            foreach($a as $k=>&$v) {
                if($k==='@attributes') {
                    if (isset($v->__type) && is_object($a)) {
                        $a->__type = $v->__type;
                    }
                    if ($v->type=='null') {
                        $a = null; 
                        return;
                    }
                    if ($v->type=='boolean') {
                        $b = substr(strtolower($v->value[0]),0,1);
                        $a = in_array($b,array('1','t'));
                        return;
                    }
                    if ($v->type=='number') {
                        $a = $v->value+0; 
                        return;
                    }
                    if ($v->type=='string') {
                        $a = $v->value;
                        return;
                    }
                    unset($a->$k);
                } else {
                    if (is_object($v)) {
                        $f($f,$v);
                    } else if (is_array($v)) {
                        $f($f,$v);
                        $a = $v;
                        return;
                    }
                }
            }
    };
    $f($f,$a);
    return json_encode($a);
}
