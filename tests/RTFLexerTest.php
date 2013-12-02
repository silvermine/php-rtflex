<?php

class RTFLexerTest extends BaseTest {

    public function testDocumentFromFile() {
        $doc = RTFLexer::file('tests/sample/hello-world.rtf');
        $this->assertInstanceOf('RTFLex\tree\RTFDocument', $doc);
    }
}
