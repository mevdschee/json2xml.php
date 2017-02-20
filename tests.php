<?php

// source: https://msdn.microsoft.com/en-us/library/bb924435(v=vs.110).aspx

use PHPUnit\Framework\TestCase;

include 'json2xml.php';

class json2xml_Test extends TestCase
{
    public function testComplexConversion()
    {
        $json = '{"depth":false,"model":"TRX-120","width":100,"test":[{"me":null},2],"height":null}';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="object"><depth type="boolean">false</depth><model type="string">TRX-120</model><width type="number">100</width><test type="array"><item type="object"><me type="null"/></item><item type="number">2</item></test><height type="null"/></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnFirstExample()
    {
        $json = '{"product":"pencil","price":12}';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="object"><product type="string">pencil</product><price type="number">12</price></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdn42ExampleWithoutXmlDeclaration()
    {
        $json = '42';
        $xml = '<root type="number">42</root>';
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdn42ErronousExample()
    {
        $json = '42';
        $xml = "<?xml version=\"1.0\"?>\n".'<!--comment--><?pi?><root type="number">42</root>';
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnStringExample1()
    {
        $json = '"42"';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="string">42</root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnStringExample2()
    {
        $json = '"the \\"da\\/ta\\""';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="string">the "da/ta"</root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnStringExample3()
    {
        $json = '"\\u0041BC"';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="string">ABC</root>';
        $this->assertEquals($xml,json2xml($json));
    }

    public function testMsdnStringExample4()
    {
        $json = '" A BC "';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="string"> A BC </root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }
 
    public function testMsdnNumberExample1()
    {
        $json = '42';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="number">42</root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnBooleanExample1()
    {
        $json = 'false';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="boolean">false</root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnNullExample1()
    {
        $json = 'null';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="null"/>';
        $this->assertEquals($json,xml2json($xml));
    }
 
    public function testMsdnNullExample2()
    {
        $json = 'null';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="null"></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnObjectExample1()
    {
        $json = '{"type1":"aaa","type2":"bbb"}';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="object"><type1 type="string">aaa</type1><type2 type="string">bbb</type2></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnObjectExample2()
    {
        $json = '{"name":"John","__type":"Person"}';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="object" __type="Person"><name type="string">John</name></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnObjectExample3()
    {
        $json = '{"name":"John","__type":"Person"}';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="object" __type="Person"><name type="string">John</name></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnObjectExample4()
    {
        $json = '{"__type":"\\\\abc"}';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="object" __type="\\abc"/>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnObjectExample5()
    {
        $json = '{"ccc":"aaa","ddd":"bbb"}';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="object"><ccc type="string">aaa</ccc><ddd type="string">bbb</ddd></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnArrayExample1()
    {
        $json = '["aaa","bbb"]';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="array"><item type="string">aaa</item><item type="string">bbb</item></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnLargeExample1()
    {
        $json = '{"myLocalName":"aaa"}';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="object"><myLocalName type="string">aaa</myLocalName></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnLargeExample2()
    {
        $json = '{"myLocalName1":"myValue1","myLocalName2":2,"myLocalName3":{"myNestedName1":true,"myNestedName2":null}}';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="object"><myLocalName1 type="string">myValue1</myLocalName1><myLocalName2 type="number">2</myLocalName2><myLocalName3 type="object"><myNestedName1 type="boolean">true</myNestedName1><myNestedName2 type="null"/></myLocalName3></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnLargeExample3()
    {
        $json = '["myValue1",2,[true,null]]';
        $xml = "<?xml version=\"1.0\"?>\n".'<root type="array"><item type="string">myValue1</item><item type="number">2</item><item type="array"><item type="boolean">true</item><item type="null"/></item></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }
}
