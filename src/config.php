<?php
require "autoload.php";

use PhpJina\Jina;

$config = [
    "url" => "localhost",
    "port" => "1234",
    "endpoints" => [
        "/status" => "GET",
        "/post" => "POST",
        "/index" => "POST",
        "/search" => "POST",
        "/delete" => "DELETE",
        "/update" => "PUT",
        "/" => "GET"
    ]
];
$jina = new Jina($config);
