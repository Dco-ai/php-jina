<?php

namespace DcoAi\PhpJina\DataStores;

use DcoAi\PhpJina\DataStores\Weaviate\WeaviateConnection;
use DcoAi\PhpJina\DataStores\Qdrant\QdrantConnection;
use DcoAi\PhpJina\DataStores\AnnLite\AnnLiteConnection;
use DcoAi\PhpJina\DataStores\ElasticSearch\ElasticSearchConnection;
use DcoAi\PhpJina\DataStores\Milvus\MilvusConnection;
use DcoAi\PhpJina\DataStores\Redis\RedisConnection;
use DcoAi\PhpJina\DataStores\SQLite\SQLiteConnection;
use DcoAi\PhpJina\DataStores\DefaultDataStore\DefaultConnection;

class DataStoreFactory
{
    private $connection;
    private array $conf;

    public function __construct($conf)
    {
        $this->conf = $conf;
        $this->create();
    }
    public function create()
    {
        if (!array_key_exists("dataStore", $this->conf)) {
            $this->connection = new DefaultConnection();
            return;
        }
        $dataStoreType = $this->conf["dataStore"]["type"];

        switch ($dataStoreType) {
            case 'weaviate':
                $this->connection = new WeaviateConnection($this->conf);
                break;
            case 'qdrant':
                $this->connection = new QdrantConnection();
                break;
            case 'redis':
                $this->connection = new RedisConnection();
                break;
            case 'milvus':
                $this->connection = new MilvusConnection();
                break;
            case 'elasticsearch':
                $this->connection = new ElasticSearchConnection();
                break;
            case 'annlite':
                $this->connection = new AnnLiteConnection();
                break;
            case 'sqlite':
                $this->connection = new SQLiteConnection();
                break;
            default:
                $this->connection = new DefaultConnection();
                break;
        }
    }

    public function filter()
    {
        return $this->connection->filter();
    }
}