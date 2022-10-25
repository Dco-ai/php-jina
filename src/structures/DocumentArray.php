<?php

namespace DcoAi\PhpJina\structures;

class DocumentArray
{
    public $documentArray;
    function __construct() {
        $this->documentArray = new \stdClass();
        $this->documentArray->data = [];
        $this->documentArray->parameters = new \stdClass();
    }
}