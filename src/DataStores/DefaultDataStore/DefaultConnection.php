<?php

namespace DcoAi\PhpJina\DataStores\DefaultDataStore;

class DefaultConnection
{

    public function filter(): Filter
    {
        return new Filter();
    }
}
