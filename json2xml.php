<?php

$xml = "<?xml version=\"1.0\"?>\n".'<root type="object"><depth type="false"/><model type="string">TRX-120</model><width type="number">100</width><test type="array"><value type="object"><me type="null"/></value><value type="number">2</value></test><height type="null"/></root>';
$json = '{"depth":false,"model":"TRX-120","width":100,"test":[{"me":null},2],"height":null}';

function json2xml($json) {
    $t = function($v) {
        switch(gettype($v)) {
            case 'boolean': return $v?'true':'false';
            case 'integer': return 'number';
            case 'double':  return 'number';
            case 'string':  return 'string';
            case 'array':   return 'array';
            case 'object':  return 'object';
            case 'NULL':    return 'null';
        }
    };
    $a = json_decode($json);
    $c = new SimpleXMLElement('<?xml version="1.0"?><root type="'.$t($a).'"></root>');
    $f = function($f,$c,$a,$s=false) use ($t) {
            foreach($a as $k=>$v) {
                if(is_object($v)) {
                    $ch=$c->addChild($s?'value':$k);
                    $ch->addAttribute('type', $t($v));
                    $f($f,$ch,$v);
                } else if(is_array($v)) {
                    $ch=$c->addChild($s?'value':$k);
                    $ch->addAttribute('type', $t($v));
                    $f($f,$ch,$v,true);
                } else if(is_bool($v)) {
                    $ch=$c->addChild($s?'value':$k,'');
                    $ch->addAttribute('type', $t($v));
                } else {
                    $ch=$c->addChild($s?'value':$k,$v);
                    $ch->addAttribute('type', $t($v));
                }
            }
    };
    $f($f,$c,$a);
    return trim($c->asXML());
}

function xml2json($xml) {
    $p = function ($p,$n) {
        foreach($n->childNodes as $node) {
            if($node->hasChildNodes()) {
                $p($p,$node);
            } else {
                if($n->hasAttributes() && strlen($n->nodeValue)){
                    $n->setAttribute("value", $node->textContent);
                    $node->nodeValue = "";
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
                    if ($v->type=='null') {
                        $a = null; 
                        return;
                    }
                    if ($v->type=='true') {
                        $a = true; 
                        return;
                    }
                    if ($v->type=='false') {
                        $a = false; 
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

var_dump(json2xml($json));
var_dump($xml);
var_dump(json2xml($json)==$xml);
var_dump(xml2json(json2xml($json)));
var_dump($json);
var_dump(xml2json(json2xml($json))==$json);
