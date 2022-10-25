<?php
namespace DcoAi\PhpJina;

use DcoAi\PhpJina\structures\{
    Document,
    DocumentArray
};
use DcoAi\PhpJina\Connection\ConnectJina;
use stdClass;

/**
 * This is the main class used to create Jina Documents and send them to your Jina service.
 *
 * Example usage:
 * $config = [
 *  "url" => "localhost",
 *  "port" => 1234,
 *  "endpoints" => [
 *      "/status":"GET",
 *      "/post":"POST",
 *      "/index":"POST",
 *      "/search":"POST",
 *      "/match":"POST",
 *      "/delete":"DELETE",
 *      "/update":"PUT",
 *      "/":"GET"
 *  ]
 * ];
 * $jinaConn = new Jina($config);
 *
 * @package     dco-ai/php-jina
 * @author      Jonathan Rowley
 * @access      public
 * @param       array $conf The configuration settings for your Jina Project
 */
final class Jina
{
    private $conf;
    public function __construct($conf) {
        $this->conf = $conf;
    }

    /**
     * @return stdClass
     */
    public function documentArray(): stdClass
    {
        $docArr =  new DocumentArray;
        return $docArr->documentArray;
    }

    /**
     * @return stdClass
     */
    public function document(): stdClass
    {
        $doc =  new Document;
        return $doc->document;
    }

    /**
     * Adds a Document to a DocumentArray
     *
     * @param stdClass $da
     * @param stdClass $d
     * @return stdClass
     */
    public function addDocument(stdClass $da, stdClass $d): stdClass
    {
        $da->data[] = $d;
        return $da;
    }

    /**
     * Submits the DocumentArray to your Jina Project
     *
     * @param string $endpoint
     * @param stdClass $da
     * @return mixed|void
     */
    public function submit(string $endpoint, stdClass $da) {
        $conn = new ConnectJina($this->conf);
        return $conn->callAPI($endpoint, $da);
    }
}