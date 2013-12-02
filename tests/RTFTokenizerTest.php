<?php

use RTFLex\io\StreamReader;
use RTFLex\tokenizer\RTFTokenizer;
use RTFLex\tokenizer\RTFToken;


class RTFTokenizerTest extends BaseTest {

    public function assertToken($token, $type, $name = null, $data = null) {
        $this->assertEquals($type, $token->getType(), "Token type is correct");
        $this->assertEquals($name, $token->getName(), "Token name is correct");
        $this->assertEquals($data, $token->getData(), "Token data is correct");
    }


    public function testReadToken() {
        $r = new StreamReader('tests/sample/hello-world.rtf');
        $tokenizer = new RTFTokenizer($r);

        $this->assertToken($tokenizer->readToken(), RTFToken::T_START_GROUP);
        $this->assertToken($tokenizer->readToken(), RTFToken::T_CONTROL_WORD, 'rtf', 1);
        $this->assertToken($tokenizer->readToken(), RTFToken::T_CONTROL_WORD, 'ansi');
        $this->assertToken($tokenizer->readToken(), RTFToken::T_CONTROL_WORD, 'ansicpg', 1252);
        $this->assertToken($tokenizer->readToken(), RTFToken::T_CONTROL_WORD, 'cocoartf', 1187);
        $this->assertToken($tokenizer->readToken(), RTFToken::T_CONTROL_WORD, 'cocoasubrtf', 370);

        $r->close();
    }
}
