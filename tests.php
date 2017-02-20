<?php

use PHPUnit\Framework\TestCase;

include 'json2xml.php';

class json2xml_Test extends TestCase
{
    public function testComplexConversion()
    {
        $json = '{"depth":false,"model":"TRX-120","width":100,"test":[{"me":null},2],"height":null}';
        $xml = '<root type="object"><depth type="boolean">false</depth><model type="string">TRX-120</model><width type="number">100</width><test type="array"><item type="object"><me type="null"/></item><item type="number">2</item></test><height type="null"/></root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdnFirstExample()
    {
        $json = '{"product":"pencil","price":12}';
        $xml = '<root type="object"><product type="string">pencil</product><price type="number">12</price></root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdn42ExampleWithoutXmlDeclaration()
    {
        $json = '42';
        $xml = '<root type="number">42</root>';
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdn42ErronousExample()
    {
        $json = '42';
        $xml = '<!--comment--><?pi?><root type="number">42</root>';
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdnStringExample1()
    {
        $json = '"42"';
        $xml = '<root type="string">42</root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdnStringExample2()
    {
        $json = '"the \\"da\\/ta\\""';
        $xml = '<root type="string">the "da/ta"</root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdnStringExample3()
    {
        $json = '"\\u0041BC"';
        $xml = '<root type="string">ABC</root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
    }

    public function testMsdnStringExample4()
    {
        $json = '" A BC "';
        $xml = '<root type="string"> A BC </root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }
 
    public function testMsdnNumberExample1()
    {
        $json = '42';
        $xml = '<root type="number">42</root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdnBooleanExample1()
    {
        $json = 'false';
        $xml = '<root type="boolean">false</root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdnNullExample1()
    {
        $json = 'null';
        $xml = '<root type="null"/>';
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }
 
    public function testMsdnNullExample2()
    {
        $json = 'null';
        $xml = '<root type="null"></root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdnObjectExample1()
    {
        $json = '{"type1":"aaa","type2":"bbb"}';
        $xml = '<root type="object"><type1 type="string">aaa</type1><type2 type="string">bbb</type2></root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdnObjectExample2()
    {
        $json = '{"name":"John","__type":"Person"}';
        $xml = '<root type="object" __type="Person"><name type="string">John</name></root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdnObjectExample3()
    {
        $json = '{"name":"John","__type":"Person"}';
        $xml = '<root type="object" __type="Person"><name type="string">John</name></root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdnObjectExample4()
    {
        $json = '{"__type":"\\\\abc"}';
        $xml = '<root type="object" __type="\\abc"/>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdnObjectExample5()
    {
        $json = '{"ccc":"aaa","ddd":"bbb"}';
        $xml = '<root type="object"><ccc type="string">aaa</ccc><ddd type="string">bbb</ddd></root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdnArrayExample1()
    {
        $json = '["aaa","bbb"]';
        $xml = '<root type="array"><item type="string">aaa</item><item type="string">bbb</item></root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdnLargeExample1()
    {
        $json = '{"myLocalName":"aaa"}';
        $xml = '<root type="object"><myLocalName type="string">aaa</myLocalName></root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdnLargeExample2()
    {
        $json = '{"myLocalName1":"myValue1","myLocalName2":2,"myLocalName3":{"myNestedName1":true,"myNestedName2":null}}';
        $xml = '<root type="object"><myLocalName1 type="string">myValue1</myLocalName1><myLocalName2 type="number">2</myLocalName2><myLocalName3 type="object"><myNestedName1 type="boolean">true</myNestedName1><myNestedName2 type="null"/></myLocalName3></root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }

    public function testMsdnLargeExample3()
    {
        $json = '["myValue1",2,[true,null]]';
        $xml = '<root type="array"><item type="string">myValue1</item><item type="number">2</item><item type="array"><item type="boolean">true</item><item type="null"/></item></root>';
        $this->assertXmlStringEqualsXmlString($xml,json2xml($json));
        $this->assertJsonStringEqualsJsonString($json,xml2json($xml));
    }
}
