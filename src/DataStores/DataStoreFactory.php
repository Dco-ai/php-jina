<?php

namespace DcoAi\PhpJina\DataStores;

use DcoAi\PhpJina\DataStores\Weaviate\WeaviateConnection;
use DcoAi\PhpJina\DataStores\Qdrant\QdrantConnection;
use DcoAi\PhpJina\DataStores\AnnLite\AnnLiteConnection;
use DcoAi\PhpJina\DataStores\ElasticSearch\ElasticSearchConnection;
use DcoAi\PhpJina\DataStores\Milvus\MilvusConnection;
use DcoAi\PhpJina\DataStores\Redis\RedisConnection;
use DcoAi\PhpJina\DataStores\SQLite\SQLiteConnection;
use DcoAi\PhpJina\DataStores\Default\DefaultConnection;

class DataStoreFactory
{
    private $connection;
    private array $conf;

    public function __construct($conf)
    {
        $this->conf = $conf;
        $this->create($conf);
    }
    public function create($config)
    {
        $this->conf = $config;
        if (!array_key_exists("dataStore", $this->conf)) {
            $this->connection = new DefaultConnection();
            return;
        }
        $dataStoreType = $this->conf["dataStore"]["type"];

        $this->connection = match ($dataStoreType) {
            'weaviate' => new WeaviateConnection($this->conf),
            'qdrant' => new QdrantConnection(),
            'redis' => new RedisConnection(),
            'milvus' => new MilvusConnection(),
            'elasticsearch' => new ElasticSearchConnection(),
            'annlite' => new AnnLiteConnection(),
            'sqlite' => new SQLiteConnection(),
            default => new DefaultConnection(),
        };
    }

    public function filter()
    {
        return $this->connection->filter();
    }
}