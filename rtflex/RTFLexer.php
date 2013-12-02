<?php

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));


require_once "io/IByteReader.php";
require_once "io/StreamReader.php";

require_once "tokenizer/ITokenGenerator.php";
require_once "tokenizer/RTFToken.php";
require_once "tokenizer/RTFTokenizer.php";

require_once "tree/RTFGroup.php";
require_once "tree/RTFDocument.php";

use RTFLex\io\StreamReader;
use RTFLex\tokenizer\RTFTokenizer;
use RTFLex\tree\RTFDocument;


class RTFLexer {

    public static function file($filename) {
        $reader = new StreamReader($filename);
        $tokenizer = new RTFTokenizer($reader);
        $doc = new RTFDocument($tokenizer);
        return $doc;
    }
}
