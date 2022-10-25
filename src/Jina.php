<?php
namespace DcoAi\PhpJina;

use DcoAi\PhpJina\structures\{
    Document,
    DocumentArray
};
use DcoAi\PhpJina\Connection\ConnectJina;

final class Jina
{
    private $conf;
    public function __construct($conf) {
        $this->conf = $conf;
    }

    public function documentArray() {
        return new DocumentArray;
    }
    public function document() {
        return new Document;
    }
    public function addDocument(DocumentArray $da, Document $d) {
        $da->documentArray->data[] = $d;
        return $da;
    }
    public function submit($endpoint,DocumentArray $da) {
        $conn = new ConnectJina($this->conf);
        return $conn->callAPI($endpoint, $da);
    }
}