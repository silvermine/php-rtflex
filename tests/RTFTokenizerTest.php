<?php

use RTFLex\io\StreamReader;
use RTFLex\tokenizer\RTFTokenizer;
use RTFLex\tokenizer\RTFToken;


class RTFTokenizerTest extends BaseTest {

    public function testReadToken() {
        $r = new StreamReader('tests/sample/hello-world.rtf');
        $tokenizer = new RTFTokenizer($r);

        // {\rtf1\ansi\ansicpg1252\cocoartf1265
        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_START_GROUP, $t->getType());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_CONTROL_WORD, $t->getType());
        $this->assertEquals('rtf', $t->getName());
        $this->assertEquals(1, $t->getData());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_CONTROL_WORD, $t->getType());
        $this->assertEquals('ansi', $t->getName());
        $this->assertEquals(null, $t->getData());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_CONTROL_WORD, $t->getType());
        $this->assertEquals('ansicpg', $t->getName());
        $this->assertEquals(1252, $t->getData());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_CONTROL_WORD, $t->getType());
        $this->assertEquals('cocoartf', $t->getName());
        $this->assertEquals(1265, $t->getData());

        // {\fonttbl\f0\fswiss\fcharset0 Helvetica;}
        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_START_GROUP, $t->getType());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_CONTROL_WORD, $t->getType());
        $this->assertEquals('fonttbl', $t->getName());
        $this->assertEquals(null, $t->getData());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_CONTROL_WORD, $t->getType());
        $this->assertEquals('f', $t->getName());
        $this->assertEquals(0, $t->getData());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_CONTROL_WORD, $t->getType());
        $this->assertEquals('fswiss', $t->getName());
        $this->assertEquals(null, $t->getData());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_CONTROL_WORD, $t->getType());
        $this->assertEquals('fcharset', $t->getName());
        $this->assertEquals(0, $t->getData());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_TEXT, $t->getType());
        $this->assertEquals(null, $t->getName());
        $this->assertEquals("Helvetica;", $t->getData());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_END_GROUP, $t->getType());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_TEXT, $t->getType());
        $this->assertEquals(null, $t->getName());
        $this->assertEquals("\n", $t->getData());

        // {\colortbl;\red255\green255\blue255;}
        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_START_GROUP, $t->getType());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_CONTROL_WORD, $t->getType());
        $this->assertEquals('colortbl', $t->getName());
        $this->assertEquals('', $t->getData());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_CONTROL_WORD, $t->getType());
        $this->assertEquals('red', $t->getName());
        $this->assertEquals(255, $t->getData());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_CONTROL_WORD, $t->getType());
        $this->assertEquals('green', $t->getName());
        $this->assertEquals(255, $t->getData());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_CONTROL_WORD, $t->getType());
        $this->assertEquals('blue', $t->getName());
        $this->assertEquals(255, $t->getData());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_END_GROUP, $t->getType());

        $t = $tokenizer->readToken();
        $this->assertEquals(RTFToken::T_TEXT, $t->getType());
        $this->assertEquals(null, $t->getName());
        $this->assertEquals("\n", $t->getData());

        // \margl1440\margr1440\vieww10800\viewh8400\viewkind0
        // \pard\tx720\tx1440\tx2160\tx2880\tx3600\tx4320\tx5040\tx5760\tx6480\tx7200\tx7920\tx8640\pardirnatural

        // \f0\fs96 \cf0 Syn? Ack.\

        // \fs24 \
        // Hello World. This is a sample RTF file.\
        // \
        // This is another paragraph.}

        $r->close();
    }
}
