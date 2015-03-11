<?php

namespace RTFLex\tree;
use RTFLex\tokenizer\ITokenGenerator;
use RTFLex\tokenizer\RTFToken;


class RTFDocument {
    private $groupStack = array();
    private $tokenizer;
    private $rootGroup;
    private $metadataGroup;


    public function __construct(ITokenGenerator $tokenizer) {
        $this->buildTree($tokenizer);
    }


    protected function buildTree($tokenizer) {
        // Wipe the stack
        $this->groupStack = array();
        $this->rootGroup = null;

        while ($t = $tokenizer->readToken()) {
            $this->parseToken($t);
        }
    }


    public function extractText() {
        return $this->rootGroup->extractText();
    }


    private function findGroup($root, $control) {
        if (!$root) {
            return null;
        }

        if ($root->hasControlWord($control)) {
            return $root;
        }

        foreach ($root->listChildren() as $child) {
            if ($group = $this->findGroup($child, $control)) {
                return $group;
            }
        }

        return null;
    }


    private function getInfoGroup() {
        if (is_null($this->metadataGroup)) {
            $this->metadataGroup = $this->findGroup($this->rootGroup, 'info');
        }
        return $this->metadataGroup;
    }


    public function getMetadata($name) {
        $info = $this->getInfoGroup();
        $block = $this->findGroup($info, $name);
        return $block instanceof RTFGroup
           ? trim($block->extractText($allowInvisible = true))
           : null;
    }


    protected function parseToken($token) {
        switch ($token->getType()) {
            // Start a new Group
            case RTFToken::T_START_GROUP:
                $group = new RTFGroup();
                $parent = end($this->groupStack);
                if ($parent) {
                    $parent->pushGroup($group);
                } else {
                    $this->rootGroup = $group;
                }
                array_push($this->groupStack, $group);
                break;

            // End the active group
            case RTFToken::T_END_GROUP:
                if (count($this->groupStack) == 0) {
                    throw new Exception("Can not close group when open group doesn't exist");
                }
                array_pop($this->groupStack);
                break;

            // Attach a control word to the active group
            case RTFToken::T_CONTROL_WORD:
                if (count($this->groupStack) == 0) {
                    throw new Exception("Can not use control word when open group doesn't exist");
                }
                $group = end($this->groupStack);
                $group->pushControlWord($token);
                break;

            // Add content into the active group
            case RTFToken::T_CONTROL_SYMBOL:
            case RTFToken::T_TEXT:
                if (count($this->groupStack) == 0) {
                    throw new Exception("Can not use content when open group doesn't exist");
                }
                $group = end($this->groupStack);
                $group->pushContent($token);
                break;
        }
    }
}
