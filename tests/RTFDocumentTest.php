<?php

use RTFLex\io\StreamReader;
use RTFLex\tokenizer\RTFTokenizer;
use RTFLex\tree\RTFDocument;


class RTFDocumentTest extends BaseTest {

    private function getDocument() {
        $reader = new StreamReader('tests/sample/hello-world.rtf');
        $tokenizer = new RTFTokenizer($reader);
        $doc = new RTFDocument($tokenizer);
        $reader->close();
        return $doc;
    }


    public function testExtractMetadata() {
        $doc = $this->getDocument();
        $this->assertEquals('Sample Title', $doc->getMetadata('title'));
        $this->assertEquals('Sample Subject', $doc->getMetadata('subject'));
        $this->assertEquals('Craig Weber', $doc->getMetadata('author'));
        $this->assertEquals('silvermine', $doc->getMetadata('company'));
        $this->assertEquals('2013 silvermine.', $doc->getMetadata('copyright'));
    }


    public function testExtractText() {
        $expected = file_get_contents('tests/sample/hello-world.txt');
        $doc = $this->getDocument();
        $this->assertEquals($expected, $doc->extractText());
    }
}
