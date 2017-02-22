<?php
// Copyright: Maurits van der Schee <maurits@vdschee.nl>
// Description: Convert from JSON to XML and back.
// License: MIT

function json2xml($json) {
    $a = json_decode($json);
    $d = new DOMDocument();
    $c = $d->createElement("root");
    $d->appendChild($c);
    $t = function($v) {
        $type = gettype($v);
        switch($type) {
            case 'integer': return 'number';
            case 'double':  return 'number';
            default: return strtolower($type);
        }
    };
    $f = function($f,$c,$a,$s=false) use ($t,$d) {
        $c->setAttribute('type', $t($a));
        if ($t($a) != 'array' && $t($a) != 'object') {
            if ($t($a) == 'boolean') {
                $c->appendChild($d->createTextNode($a?'true':'false'));
            } else {
                $c->appendChild($d->createTextNode($a));
            }
        } else {
            foreach($a as $k=>$v) {
                if ($k == '__type' && $t($a) == 'object') {
                    $c->setAttribute('__type', $v);
                } else {
                    if ($t($v) == 'object') {
                        $ch = $c->appendChild($d->createElementNS(null, $s ? 'item' : $k));
                        $f($f, $ch, $v);
                    } else if ($t($v) == 'array') {
                        $ch = $c->appendChild($d->createElementNS(null, $s ? 'item' : $k));
                        $f($f, $ch, $v, true);
                    } else {
                        $va = $d->createElementNS(null, $s ? 'item' : $k);
                        if ($t($v) == 'boolean') {
                            $va->appendChild($d->createTextNode($v?'true':'false'));
                        } else {
                            $va->appendChild($d->createTextNode($v));
                        }
                        $ch = $c->appendChild($va);
                        $ch->setAttribute('type', $t($v));
                    }
                }
            }
        }
    };
    $f($f,$c,$a,$t($a)=='array');
    return $d->saveXML($d->documentElement);
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
    return json_encode($c,64);//64=JSON_UNESCAPED_SLASHES
}