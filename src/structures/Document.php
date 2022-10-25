<?php

namespace DcoAi\PhpJina\structures;


use stdClass;

class Document
{
    public $document;
    function __construct() {
        $this->document = new stdClass();
        $this->document->id = null;
        $this->document->parent_id = null;
        $this->document->granularity = null;
        $this->document->adjacency = null;
        $this->document->blob = null;
        $this->document->tensor = null;
        $this->document->mime_type = null;
        $this->document->text = null;
        $this->document->weight = null;
        $this->document->uri = null;
        $this->document->tags = new stdClass();
        $this->document->offset = null;
        $this->document->location = null;
        $this->document->embedding = [];
        $this->document->modality = null;
        $this->document->evaluations = null;
        $this->document->scores = [];
        $this->document->chunks = new stdClass();
        $this->document->matches = new stdClass();
    }
}
