<?php

use RTFLex\io\StreamReader;


class StreamReaderTest extends BaseTest {

    public function testReadByte() {
        $reader = new StreamReader('tests/sample/hello-world.txt');

        $this->assertEquals('S', $reader->readByte());
        $this->assertEquals('y', $reader->readByte());
        $this->assertEquals('n', $reader->readByte());
        $this->assertEquals('?', $reader->readByte());
        $this->assertEquals(' ', $reader->readByte());
        $this->assertEquals('A', $reader->readByte());

        $reader->close();
    }


    public function testLookAhead() {
        $reader = new StreamReader('tests/sample/hello-world.txt');

        $this->assertEquals('S', $reader->lookAhead());
        $this->assertEquals('y', $reader->lookAhead(1));
        $this->assertEquals('S', $reader->readByte());

        $this->assertEquals('y', $reader->lookAhead());
        $this->assertEquals('y', $reader->lookAhead());
        $this->assertEquals('y', $reader->readByte());

        $this->assertEquals('n', $reader->lookAhead());
        $this->assertEquals(' ', $reader->lookAhead(2));
        $this->assertEquals('n', $reader->readByte());

        $reader->close();
    }
}
