<?php

namespace RTFLex\tokenizer;


class RTFToken {
    const T_START_GROUP = 1;
    const T_END_GROUP = 2;
    const T_CONTROL_WORD = 3;
    const T_CONTROL_SYMBOL = 4;
    const T_TEXT = 5;

    private $type;
    private $name;
    private $data;

    public function __construct($type, $name = null, $data = null) {
        $this->type = $type;
        $this->name = $name;
        $this->data = $data;
    }


    public function getData() {
        return $this->data;
    }


    public function getName() {
        return $this->name;
    }


    public function getType() {
        return $this->type;
    }
}
