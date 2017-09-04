<?php

namespace RTFLex\io;


class StringReader implements IByteReader {

    private $buffer = 0;
    private $index = 0;
    private $size;

    public function __construct($string) {
        $this->buffer = trim($string);
        $this->size = strlen($this->buffer);
    }

    public function close() {
        $this->buffer = '';
    }

    public function lookAhead($offset = 0) {
        if( ($this->index + $offset) >= $this->size) {
            return false;
        }
        return substr($this->buffer, ($this->index + $offset), 1);
    }

    public function readByte() {
        $byte = $this->lookAhead();
        $this->index++;
        return $byte;
    }
}
