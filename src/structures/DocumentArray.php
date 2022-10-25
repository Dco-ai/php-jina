<?php

namespace DcoAi\PhpJina\structures;

use stdClass;

class DocumentArray
{
    public $documentArray;
    function __construct() {
        $this->documentArray = new stdClass();
        $this->documentArray->data = [];
        $this->documentArray->parameters = new stdClass();
    }
}