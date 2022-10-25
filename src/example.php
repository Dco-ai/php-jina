<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "autoload.php";

use DcoAi\PhpJina\Jina;

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
// initiate a DocumentArray
$da = $jina->documentArray();

// add  a request parameter to the DocumentArray
$da->parameters->asset_id = "ABCDE";

// create a new Document and add text to it
$d1 = $jina->document();
$d1->text = "test";
// add the Document to the DocumentArray
$jina->addDocument($da, $d1);

// add another Document to the DocumentArray
$d2 = $jina->document();
$d2->text = "me!";
$jina->addDocument($da, $d2);

// Let's see the results
print_r($da);
