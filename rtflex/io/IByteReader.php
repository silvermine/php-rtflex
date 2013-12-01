<?php

namespace RTFLex\io;


interface IByteReader {
    public function close();
    public function lookAhead($offset = 0);
    public function readByte();
}
