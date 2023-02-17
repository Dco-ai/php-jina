<?php

namespace DcoAi\PhpJina\DataStores\Default;

class DefaultConnection
{

    public function filter(): Filter
    {
        return new Filter();
    }
}
