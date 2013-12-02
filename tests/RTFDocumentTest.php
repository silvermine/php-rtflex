<?php

use RTFLex\io\StreamReader;
use RTFLex\tokenizer\RTFTokenizer;
use RTFLex\tree\RTFDocument;


class RTFDocumentTest extends BaseTest {

    public function testExtractText() {
        $reader = new StreamReader('tests/sample/hello-world.rtf');
        $expected = file_get_contents('tests/sample/hello-world.txt');

        $tokenizer = new RTFTokenizer($reader);

        $doc = new RTFDocument($tokenizer);
        $this->assertEquals($expected, $doc->extractText());

        $reader->close();
    }
}
