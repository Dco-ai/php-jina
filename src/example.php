<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "autoload.php";

use DcoAi\PhpJina\JinaClient;

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
        "/" => "GET",
    ],
    //"dataStore" => [
    //    "type" => "weaviate",
    //    "url" => "localhost",
    //    "port" => "8080",
    //]
];
$jina = new JinaClient($config);
// initiate a DocumentArray
$da = $jina->documentArray();

// add  a request parameter to the DocumentArray
$da->parameters->asset_id = "asset_id";

// lets add some filters as the request parameters
// You can chain together as many filters as you want
$filterFormatter = $jina->useFilterFormatter();
$filterFormatter->
    and()->
        equal("env","dev")->
        equal("userId","2")->
    endAnd()->
    or()->
        notEqual("env","2")->
        greaterThan("id","5")->
    endOr()->
    equal("env","dev")->
    notEqual("env","prod");
// Now set the filter on the DocumentArray as a parameter
$da->parameters->filter = $filterFormatter->createFilter();

// create a new Document and add text to it
$d1 = $jina->document();
$d1->text = "test";
// add the Document to the DocumentArray
$jina->addDocument($da, $d1);

// add another Document to the DocumentArray
$d2 = $jina->document();
$d2->text = "me!";
$jina->addDocument($da, $d2);

// Let's see what the request looks like
print_r(json_encode($da, JSON_PRETTY_PRINT));

// Uncomment to submit the DocumentArray to Jina
// $results = $jina->submit("/search",$da);
// print_r($results);
