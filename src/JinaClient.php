<?php
namespace DcoAi\PhpJina;

use DcoAi\PhpJina\DataStores\DataStoreFactory;
use DcoAi\PhpJina\structures\{
    Document,
    DocumentArray
};
use DcoAi\PhpJina\connection\ConnectJina;
use stdClass;

/**
 * This is the main class used to create JinaClient Documents and send them to your JinaClient service.
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
 * $jinaConn = new JinaClient($config);
 *
 * @package     dco-ai/php-jina
 * @author      Jonathan Rowley
 * @access      public
 * @param       array $conf The configuration settings for your JinaClient Project
 */
final class JinaClient
{
    private array $conf;
    private DataStoreFactory $dataStore;
    public function __construct($conf) {
        $this->conf = $conf;
        $this->dataStore = new DataStoreFactory($conf);
    }

    /**
     * @return stdClass
     */
    public function documentArray()
    {
        $docArr =  new DocumentArray;
        return $docArr->documentArray;
    }

    /**
     * @return stdClass
     */
    public function document()
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
    public function addDocument(stdClass $da, stdClass $d)
    {
        $da->data[] = $d;
        return $da;
    }

    /**
     * Submits the DocumentArray to your JinaClient Project
     *
     * @param string $endpoint The endpoint to your Jina Application
     * @param stdClass $da The DocumentArray you are sending to your Jina Application
     * @param bool $clean (optional) Set to False if you want all values returned
     * @return mixed|void
     */
    public function submit(string $endpoint, stdClass $da, bool $clean=true) {
        // if a Document was passed put it in a DocumentArray
        if(!property_exists($da, "data") && property_exists($da, "id")){
            $da = $this->addDocument($this->documentArray(), $da);
        }
        $conn = new ConnectJina($this->conf);
        return $conn->callAPI($endpoint, $da, $clean);
    }

    /**
     * Returns the Filter class based on the configuration used
     *
     * @return mixed class
     */
    public function useFilterFormatter() {
        return $this->dataStore->filter();
    }
}
