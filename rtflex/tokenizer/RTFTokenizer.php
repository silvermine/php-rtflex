<?php

namespace RTFLex\tokenizer;
use RTFLex\io\IByteReader;


class RTFTokenizer implements ITokenGenerator {
    const CONTROL_CHARS = "/[\\\\|\{\}]/";
    const CONTROL_WORD = "/[a-z\*]/";
    const NUMERIC = "/[0-9]/";

    private $reader;

    public function __construct(IByteReader $reader) {
        $this->reader = $reader;
    }


    private function readControlWord() {
        $word = "";
        while (preg_match(self::CONTROL_WORD, $this->reader->lookAhead())) {
            $byte = $this->reader->readByte();
            $word .= $byte;
            if ($byte == ' ') {
                break;
            }
        }

        $param = "";
        while (preg_match(self::NUMERIC, $this->reader->lookAhead())) {
            $param .= $this->reader->readByte();
        }

        // Swallow the control word delim
        if (empty($param) && !preg_match(self::CONTROL_CHARS, $this->reader->lookAhead())) {
            $this->reader->readByte();
        }

        $param = strlen($param) == 0 ? null : $param;
        $param = is_numeric($param) ? (int)$param : null;
        return array($word, $param);
    }


    private function readText($start) {
        $buffer = $start;
        $last = $start;

        while (true) {
            $n0 = $this->reader->lookAhead();
            if ($n0 === false) {
                break;
            }

            if (preg_match(self::CONTROL_CHARS, $n0) && $last != "\\") {
                break;
            }

            $buffer .= $this->reader->readByte();
        }

        return $buffer;
    }


    public function readToken() {
        $byte = $this->reader->readByte();
        if ($byte === false) {
            return false;
        }

        switch ($byte) {
            case "{":
                return new RTFToken(RTFToken::T_START_GROUP);

            case "}":
                return new RTFToken(RTFToken::T_END_GROUP);

            case "\\":
                if (preg_match(self::CONTROL_WORD, $this->reader->lookAhead())) {
                    list($word, $param) = $this->readControlWord();
                    return new RTFToken(RTFToken::T_CONTROL_WORD, $word, $param);
                }
                $symbol = $this->reader->readByte();
                return new RTFToken(RTFToken::T_CONTROL_SYMBOL, null, $symbol);

            default:
                $str = $this->readText($byte);
                if (strlen((trim($str))) === 0) {
                    return $this->readToken();
                }
                return new RTFToken(RTFToken::T_TEXT, null, $str);
        }
    }
}
