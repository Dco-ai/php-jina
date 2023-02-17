<?php

namespace DcoAi\PhpJina\DataStores\Redis;

class RedisConnection
{
    private $conf;
    private string $url;
    public function __construct($conf) {
        $this->conf = $conf;
        $this->url = $this->conf["dataStore"]["url"].":".$this->conf["dataStore"]["port"];
    }

    public function filter(): Filter
    {
        return new Filter;
    }
}