<?php

namespace DcoAi\PhpJina\DataStores\Weaviate;

use DcoAi\PhpJina\connection\Http;

class WeaviateConnection
{
    protected $conf;
    public function __construct($conf) {
        $this->conf = $conf;
    }

    /**
     * Retrieves the Weaviate schema from the API and returns it as a JSON object.
     *
     * @return mixed The schema from the Weaviate API, decoded as a JSON object.
     */
    public function retrieveWeaviateSchema(): mixed
    {
        $path = "/v1/schema";
        return Http::makeCurlRequest($this->conf["dataStore"]["url"].":".$this->conf["dataStore"]["port"].$path);
    }

    public function filter(): Filter
    {
        return new Filter($this->conf);
    }
}
