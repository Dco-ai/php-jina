<?php

namespace DcoAi\PhpJina\DataStores\ElasticSearch;

class ElasticSearchConnection
{
    public function filter(): Filter
    {
        return new Filter;
    }
}
