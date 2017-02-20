<?php

use PHPUnit\Framework\TestCase;

include 'json2xml.php';

class json2xml_Test extends TestCase
{
	public static function setUpBeforeClass()
	{

	}

	public function testComplexConversion()
	{
        $json = '{"depth":false,"model":"TRX-120","width":100,"test":[{"me":null},2],"height":null}';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="object"><depth type="false"/><model type="string">TRX-120</model><width type="number">100</width><test type="array"><value type="object"><me type="null"/></value><value type="number">2</value></test><height type="null"/></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }
}
