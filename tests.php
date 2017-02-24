<?php

use PHPUnit\Framework\TestCase;

include 'json2xml.php';

class json2xml_Test extends TestCase
{
    public function testComplexConversion()
    {
        $json = '{"depth":false,"model":"TRX-120","width":100,"test":[{"me":null},2.5],"height":null}';
        $xml = '<root type="object"><depth type="boolean">false</depth><model type="string">TRX-120</model><width type="number">100</width><test type="array"><item type="object"><me type="null"></me></item><item type="number">2.5</item></test><height type="null"></height></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnFirstExample()
    {
        $json = '{"product":"pencil","price":12}';
        $xml = '<root type="object"><product type="string">pencil</product><price type="number">12</price></root>';
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
        $xml = '<!--comment--><?pi?><root type="number">42</root>';
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnStringExample1()
    {
        $json = '"42"';
        $xml = '<root type="string">42</root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnStringExample2()
    {
        $json = '"the \\"da\\/ta\\""';
        $xml = '<root type="string">the "da/ta"</root>';
        $this->assertEquals($xml,json2xml($json));
    }

    public function testMsdnStringExample3()
    {
        $json = '"\\u0041BC"';
        $xml = '<root type="string">ABC</root>';
        $this->assertEquals($xml,json2xml($json));
    }

    public function testMsdnStringExample4()
    {
        $json = '" A BC "';
        $xml = '<root type="string"> A BC </root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }
 
    public function testMsdnNumberExample1()
    {
        $json = '42';
        $xml = '<root type="number">42</root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnBooleanExample1()
    {
        $json = 'false';
        $xml = '<root type="boolean">false</root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnNullExample1()
    {
        $json = 'null';
        $xml = '<root type="null"/>';
        $this->assertEquals($json,xml2json($xml));
    }
 
    public function testMsdnNullExample2()
    {
        $json = 'null';
        $xml = '<root type="null"></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnObjectExample1()
    {
        $json = '{"type1":"aaa","type2":"bbb"}';
        $xml = '<root type="object"><type1 type="string">aaa</type1><type2 type="string">bbb</type2></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnObjectExample2()
    {
        $json = '{"__type":"Person","name":"John"}';
        $xml = '<root type="object" __type="Person"><name type="string">John</name></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnObjectExample3()
    {
        $json = '{"__type":"Person","name":"John"}';
        $xml = '<root type="object" __type="Person"><name type="string">John</name></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnObjectExample4()
    {
        $json = '{"__type":"\\\\abc"}';
        $xml = '<root type="object" __type="\\abc"/>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnObjectExample5()
    {
        $json = '{"ccc":"aaa","ddd":"bbb"}';
        $xml = '<root type="object"><ccc type="string">aaa</ccc><ddd type="string">bbb</ddd></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnArrayExample1()
    {
        $json = '["aaa","bbb"]';
        $xml = '<root type="array"><item type="string">aaa</item><item type="string">bbb</item></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnLargeExample1()
    {
        $json = '{"myLocalName":"aaa"}';
        $xml = '<root type="object"><myLocalName type="string">aaa</myLocalName></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnLargeExample2()
    {
        $json = '{"myLocalName1":"myValue1","myLocalName2":2,"myLocalName3":{"myNestedName1":true,"myNestedName2":null}}';
        $xml = '<root type="object"><myLocalName1 type="string">myValue1</myLocalName1><myLocalName2 type="number">2</myLocalName2><myLocalName3 type="object"><myNestedName1 type="boolean">true</myNestedName1><myNestedName2 type="null"></myNestedName2></myLocalName3></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMsdnLargeExample3()
    {
        $json = '["myValue1",2,[true,null]]';
        $xml = '<root type="array"><item type="string">myValue1</item><item type="number">2</item><item type="array"><item type="boolean">true</item><item type="null"></item></item></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

     /**
      * @expectedException        DOMException
      * @expectedExceptionMessage Namespace Error
      */
    public function testColonInKey()
    {
        $json = '{"a:b":true}';
        $xml = '<root type="object"><a:b type="boolean">true</a:b></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

     /**
      * @expectedException        DOMException
      * @expectedExceptionMessage Invalid Character Error
      */
    public function testAmpersandInKey()
    {
        $json = '{"a&b":true}';
        $xml = '<root type="object"><a&b type="boolean">true</a&b></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

     /**
      * @expectedException        DOMException
      * @expectedExceptionMessage Invalid Character Error
      */
    public function testSlashInKey()
    {
        $json = '{"a/b":true}';
        $xml = '<root type="object"><a/b type="boolean">true</a/b></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testUnderscoreInKey()
    {
        $json = '{"a_b":true}';
        $xml = '<root type="object"><a_b type="boolean">true</a_b></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testHyphenInKey()
    {
        $json = '{"a-b":true}';
        $xml = '<root type="object"><a-b type="boolean">true</a-b></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testDotInKey()
    {
        $json = '{"a.b":true}';
        $xml = '<root type="object"><a.b type="boolean">true</a.b></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testMiddleDotInKey()
    {
        $json = '{"a\u00b7b":true}';
        $xml = '<root type="object"><a·b type="boolean">true</a·b></root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }

    public function testAllAscii7bitInValue()
    {
        $str = '';
        for ($i=32;$i<127;$i++) {
            $str.=chr($i);
        }
        $json = json_encode($str,64);//64=JSON_UNESCAPED_SLASHES
        $xml = '<root type="string"> !"#$%&amp;\'()*+,-./0123456789:;&lt;=&gt;?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~</root>';
        $this->assertEquals($xml,json2xml($json));
        $this->assertEquals($json,xml2json($xml));
    }
}
