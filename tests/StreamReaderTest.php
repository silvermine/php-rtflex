<?php

use RTFLex\io\StreamReader;


class StreamReaderTest extends BaseTest {

    public function testReadByte() {
        $reader = new StreamReader('tests/sample/hello-world.txt');

        $this->assertEquals('H', $reader->readByte());
        $this->assertEquals('e', $reader->readByte());
        $this->assertEquals('l', $reader->readByte());
        $this->assertEquals('l', $reader->readByte());
        $this->assertEquals('o', $reader->readByte());
        $this->assertEquals('.', $reader->readByte());
        $this->assertEquals("\n", $reader->readByte());
        $this->assertFalse($reader->readByte());

        $reader->close();
    }


    public function testLookAhead() {
        $reader = new StreamReader('tests/sample/hello-world.txt');

        $this->assertEquals('H', $reader->lookAhead());
        $this->assertEquals('e', $reader->lookAhead(1));
        $this->assertEquals('H', $reader->readByte());

        $this->assertEquals('e', $reader->lookAhead());
        $this->assertEquals('e', $reader->lookAhead());
        $this->assertEquals('e', $reader->readByte());

        $this->assertEquals('l', $reader->lookAhead());
        $this->assertEquals('o', $reader->lookAhead(2));
        $this->assertEquals('l', $reader->readByte());

        $reader->close();
    }
}
