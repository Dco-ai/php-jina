<h1 align="center">A PHP Client for Jina</h1>


A tool to connect to Jina with PHP. This client will not work without a running Jina installation. 

To see how that is set up go here: [Jina Installation](https://docs.jina.ai/get-started/install/)

## Jina Documentation
For more information about Jina go here: [Jina](https://docs.jina.ai)

## Install with composer command
```bash
    composer require dco-ai/php-jina
```

## Install using composer.json

### from GitHub directly:
Add this to your `composer.json` file or create the file and put this in it.
```json
{
  "name":  "dco-ai/php-jina",
  "repositories": [
    {
      "type": "svn",
      "url": "https://github.com/Dco-ai/php-jina.git"
    }
  ],
  "require": {
    "dco-ai/php-jina": "main"
  }
}
```

### or from Packagist:
```json
{
  "require": {
    "dco-ai/php-jina": "v1.*"
  }
}
```

now run composer with `composer update`

## Configuration
This client needs to know a few things about your Jina project to make the connection.

The configuration is an associative array with the following fields:
<table>
<thead>
<tr>
<th><p>Attribute</p></th>
<th><p>Type</p></th>
<th><p>Description</p></th>
</tr>
</thead>

<tbody>
<tr>
<td><p>url (required)</p></td>
<td><p>string</p></td>
<td><p>The endpoint of your Jina application. 
This can be a public URL or a private one if this client is used on the same network.</p></td>
</tr>
<tr>
<td><p>port (required)</p></td>
<td><p>string</p></td>
<td><p>The port used in your Jina application</p></td>
</tr>
<tr>
<td><p>endpoints (required)</p></td>
<td><p>associative array</p></td>
<td><p>This is how this client knows what endpoint uses which method when making the curl request. 
Since Jina allows you to make custom endpoints we need to know how to handle them. 
The default is <code>GET</code> so if your endpoint is not set here then it will attempt the call using <code>GET</code>.</p></td>
</tr>
<tr>
<td><p>dataStore (optional)</p></td>
<td><p>associative array</p></td>
<td><p>This is an optional configuration used to identify the Data Store being used. Interaction between Data Stores 
inside of DocArray differs so this client needs to know in order to handle certain functionality accordingly. 
If no dataStore is identified then the default functions will be used which may cause unintended results.</p></td>
</tr>
</tbody>
</table>

## Usage

A small example is `src/example.php`. This shows you how to load the class and then create/update Jina's Document
and DocumentArray structures.

First include the package in the header:

```php
<?php
use DcoAi\PhpJina\JinaClient;
```

Then Instantiate the JinaClient class with your configuration:
```php
$config = [
    "url" => "localhost", // The URL or endpoint of your Jina installation
    "port" => "1234", // The port used for your Jina Installation
    "endpoints" => [ // These are the active endpoints in your Jina application with the corresponding method
        "/status" => "GET",
        "/post" => "POST",
        "/index" => "POST",
        "/search" => "POST",
        "/delete" => "DELETE",
        "/update" => "PUT",
        "/" => "GET"
    ]
];
$jina = new JinaClient($config);
```

Now you can use these functions:
```php
// this creates a Document that you can add data to the structure
$d = $jina->document();

// This creates a DocumentArray that Documents can be added to
$da = $jina->documentArray();

// This adds Documents to a DocumentArray
$jina->addDocument($da, $d);

// This sends the DocumentArray to your JinaClient application and returns the result.
$jina->submit("/index",$da);
```

## Structures

### Document

<table>
<thead>
<tr>
<th><p>Attribute</p></th>
<th><p>Type</p></th>
<th><p>Description</p></th>
</tr>
</thead>

<tbody>
<tr>
<td><p>id</p></td>
<td><p>string</p></td>
<td><p>A hexdigest that represents a unique document ID. It is recommended to let Jina set this value.</p></td>
</tr>

<tr>
<td><p>blob</p></td>
<td><p>bytes</p></td>
<td><p>the raw binary content of this document, which often represents the original document</p></td>
</tr>

<tr>
<td><p>tensor</p></td>
<td><p><code>ndarray</code>-like</p></td>
<td><p>the ndarray of the image/audio/video document</p></td>
</tr>

<tr>
<td><p>text</p></td>
<td><p>string</p></td>
<td><p>a text document</p></td>
</tr>

<tr>
<td><p>granularity</p></td>
<td><p>int</p></td>
<td><p>the depth of the recursive chunk structure</p></td>
</tr>

<tr>
<td><p>adjacency</p></td>
<td><p>int</p></td>
<td><p>the width of the recursive match structure</p></td>
</tr>

<tr>
<td><p>parent_id</p></td>
<td><p>string</p></td>
<td><p>the parent id from the previous granularity</p></td>
</tr>

<tr>
<td><p>weight</p></td>
<td><p>float</p></td>
<td><p>The weight of this document</p></td>
</tr>

<tr>
<td><p>uri</p></td>
<td><p>string</p></td>
<td><p>a uri of the document could be: a local file path, a remote url starts with http or https or data URI scheme</p></td>
</tr>

<tr>
<td><p>modality</p></td>
<td><p>string</p></td>
<td><p>modality, an identifier to the modality this document belongs to. In the scope of multi/cross modal search</p></td>
</tr>

<tr>
<td><p>mime_type</p></td>
<td><p>string</p></td>
<td><p>mime type of this document, for blob content, this is required; for other contents, this can be guessed</p></td>
</tr>

<tr>
<td><p>offset</p></td>
<td><p>float</p></td>
<td><p>the offset of the doc</p></td>
</tr>

<tr>
<td><p>location</p></td>
<td><p>float</p></td>
<td><p>the position of the doc, could be start and end index of a string; could be x,y (top, left) coordinate of an image crop; could be the timestamp of an audio clip</p></td>
</tr>

<tr>
<td><p>chunks</p></td>
<td><p>array</p></td>
<td><p>array of the sub-documents of this document (recursive structure)</p></td>
</tr>

<tr>
<td><p>matches</p></td>
<td><p>array</p></td>
<td><p>array of matched documents on the same level (recursive structure)</p></td>
</tr>

<tr>
<td><p>embedding</p></td>
<td><p><code>ndarray</code>-like</p></td>
<td><p>the embedding of this document</p></td>
</tr>

<tr>
<td><p>tags</p></td>
<td><p><code>/stdClass</code></p></td>
<td><p>a structured data value, consisting of field which map to dynamically typed values.</p></td>
</tr>

<tr>
<td><p>scores</p></td>
<td><code>/stdClass</code></td>
<td><p>Scores performed on the document, each element corresponds to a metric</p></td>
</tr>

<tr>
<td><p>evaluations</p></td>
<td><p><code>/stdClass</code></p></td>
<td><p>Evaluations performed on the document, each element corresponds to a metric</p></td>
</tr>

</tbody>
</table>

### DocumentArray

<table>
<thead>
<tr>
<th><p>Attribute</p></th>
<th><p>Type</p></th>
<th><p>Description</p></th>
</tr>
</thead>

<tbody>
<tr><td><p>data</p></td>
<td><p>array</p></td>
<td><p>an array of Documents</p></td>
</tr>

<tr>
<td><p>parameters</p></td>
<td><p><code>/stdClass</code></p></td>
<td><p>a key/value set of custom instructions to be passed along with the request to Jina</p></td>
</tr>

<tr>
<td><p>targetExecutor</p></td>
<td><p>string</p></td>
<td><p>A string indicating an Executor to target. Default targets all Executors</p></td>
</tr>

</tbody>
</table>

## Filters

Filters are unique to each Data Store in DocArray. the structure and how they are passed is dependent on how you 
have your Executors set up. For every example I am providing I am assuming your Executors accept a `filter` key in the
`parameters` section of the request. If your Executors are set up to accept filters in a different way you will need to
modify the request accordingly.

In this client you can build a filter by chaining together filter functions. First you have to create an instance of the
`Filter` class with the `useFilterFormatter()` function.
```php
use DcoAi\PhpJina\JinaClient;
// set the config and create a new instance of the JinaClient
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
    ]
];
$jina = new JinaClient($config);

// create a new instance of the filter class
$filterBuilder = $jina->useFilterFormatter();
```
Now that you have the filter this is how you would chain together a basic filter:
```php
$filterBuilder->
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
```
Some Data Stores will have grouping operators like `and` and `or` that will allow you to group filters together.
If the Data Store has these operators there will be a closing function which corresponds to the opening function.

Once you have your filter built you will need to retrieve it from the `Filter` class and add it to the request.
This is not done automatically.
```php
// Lets make an empty DocumentArray
$da = $jina->documentArray();
// And add the filter to the parameters 
$da->parameters->filter = $filterBuilder->createFilter();
// print ths document and see what we got
print_r(json_encode($da, JSON_PRETTY_PRINT));
```

This filter will produce a string like this:
```json
{
    "data": [],
    "parameters": {
        "filter": [
            {
                "$and": [
                    {
                        "env": {
                            "$eq": "dev"
                        }
                    },
                    {
                        "userId": {
                            "$eq": "2"
                        }
                    }
                ]
            },
            {
                "$or": [
                    {
                        "env": {
                            "$ne": "2"
                        }
                    },
                    {
                        "id": {
                            "$gt": 5
                        }
                    }
                ]
            },
            {
                "env": {
                    "$eq": "dev"
                }
            },
            {
                "env": {
                    "$ne": "prod"
                }
            }
        ]
    }
}
```
This example is a bit complicated and probably not useful, but it shows what can be done.

### Default Filter

DocArray has a default filter structure that can be used by this client without any configuration changes. Documentation
can be found here: [Documentation](https://docs.docarray.org/fundamentals/documentarray/find/#filter-with-query-operators)

This is a list of the operators that are supported by the Default filter. The `$column` is the field you are filtering on
and the `$value` is the value you are filtering on.
<table>
<thead>
<tr>
<th><p>Query Operator</p></th>
<th><p>Chainable Function</p></th>
<th><p>Description</p></th>
</tr>
</thead>
<tbody>

<tr><td><p>$eq</p></td>
<td><code>equal($column, $value)</code></td>
<td><p>Equal to (number, string)</p></td>
</tr>

<tr><td><p>$ne</p></td>
<td><code>notEqual($column, $value)</code></td>
<td><p>Not equal to (number, string)</p></td>
</tr>

<tr><td><p>$gt</p></td>
<td><code>greaterThan($column, $value)</code></td>
<td><p>Greater than (number)</p></td>
</tr>

<tr><td><p>$gte</p></td>
<td><code>greaterThanEqual($column, $value)</code></td>
<td><p>Greater than or equal to (number)</p></td>
</tr>

<tr><td><p>$lt</p></td>
<td><code>lessThan($column, $value)</code></td>
<td><p>Less than (number)</p></td>
</tr>

<tr><td><p>$lte</p></td>
<td><code>lessThanEqual($column, $value)</code></td>
<td><p>Less than or equal to (number)</p></td>
</tr>

<tr><td><p>$in</p></td>
<td><code>in($column, $value)</code></td>
<td><p>Is in an array</p></td>
</tr>

<tr><td><p>$nin</p></td>
<td><code>notIn($column, $value)</code></td>
<td><p>Not in an array</p></td>
</tr>

<tr><td><p>$regex</p></td>
<td><code>regex($column, $value)</code></td>
<td><p>Match the specified regular expression</p></td>
</tr>

<tr><td><p>$size</p></td>
<td><code>size($column, $value)</code></td>
<td><p>Match array/dict field that have the specified size. <code>$size</code> does not accept ranges of values.</p></td>
</tr>

<tr><td><p>$exists</p></td>
<td><code>exists($column, $value)</code></td>
<td><p>Matches documents that have the specified field; predefined fields having a default value 
(for example empty string, or 0) are considered as not existing; if the expression specifies a field <code>x</code> 
in <code>tags</code> (<code>tags__x</code>), then the operator tests that x is not None.</p></td>
</tr>

</tbody>
</table>

The list of combining functions for the Default filter is here:
<table>
<thead>
<tr>
<th><p>Operator</p></th>
<th><p>Chainable Function</p></th>
<th><p>Closing Function</p></th>
<th><p>Description</p></th>
</tr>
</thead>

<tbody>

<tr><td><p>$and</p></td>
<td><code>and()</code></td>
<td><code>endAnd()</code></td>
<td><p>Join query clauses with a logical AND by chaining operator function between these two functions.</p></td>
</tr>

<tr><td><p>$or</p></td>
<td><code>or()</code></td>
<td><code>endOr()</code></td>
<td><p>Join query clauses with a logical OR by chaining operator function between these two functions.</p></td>
</tr>

<tr><td><p>$not</p></td>
<td><code>not()</code></td>
<td><code>endNot()</code></td>
<td><p>Inverts the effect of a query expression that is chained between these two functions.</p></td>
</tr>

</tbody>
</table>

### AnnLite Filter

This filter uses the AnnLite Data Store and is very similar to the Default filter with some minor differences.
Documentation can be found here: [Documentation](https://github.com/jina-ai/annlite)

To use this filter you must add the `"type" => "annlite"` key to the `dataStore` array in the configuration.
```php
use DcoAi\PhpJina\JinaClient;
// set the config and create a new instance of the JinaClient
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
    "dataStore" => [
        "type" => "annlite",
    ]
];
$jina = new JinaClient($config);
```

Like all other filters you can build it using the chaining method. Here are the specific fields using this Data Store:

<table>
<thead>
<tr>
<th><p>Query Operator</p></th>
<th><p>Chainable Function</p></th>
<th><p>Description</p></th>
</tr>
</thead>
<tbody>

<tr><td><p>$eq</p></td>
<td><code>equal($column, $value)</code></td>
<td><p>Equal to (number, string)</p></td>
</tr>

<tr><td><p>$ne</p></td>
<td><code>notEqual($column, $value)</code></td>
<td><p>Not equal to (number, string)</p></td>
</tr>

<tr><td><p>$gt</p></td>
<td><code>greaterThan($column, $value)</code></td>
<td><p>Greater than (number)</p></td>
</tr>

<tr><td><p>$gte</p></td>
<td><code>greaterThanEqual($column, $value)</code></td>
<td><p>Greater than or equal to (number)</p></td>
</tr>

<tr><td><p>$lt</p></td>
<td><code>lessThan($column, $value)</code></td>
<td><p>Less than (number)</p></td>
</tr>

<tr><td><p>$lte</p></td>
<td><code>lessThanEqual($column, $value)</code></td>
<td><p>Less than or equal to (number)</p></td>
</tr>

<tr><td><p>$in</p></td>
<td><code>in($column, $value)</code></td>
<td><p>Is in an array</p></td>
</tr>

<tr><td><p>$nin</p></td>
<td><code>notIn($column, $value)</code></td>
<td><p>Not in an array</p></td>
</tr>

</tbody>
</table>

The list of combining functions for the Default filter is here:
<table>
<thead>
<tr>
<th><p>Operator</p></th>
<th><p>Chainable Function</p></th>
<th><p>Closing Function</p></th>
<th><p>Description</p></th>
</tr>
</thead>

<tbody>

<tr><td><p>$and</p></td>
<td><code>and()</code></td>
<td><code>endAnd()</code></td>
<td><p>Join query clauses with a logical AND by chaining operator function between these two functions.</p></td>
</tr>

<tr><td><p>$or</p></td>
<td><code>or()</code></td>
<td><code>endOr()</code></td>
<td><p>Join query clauses with a logical OR by chaining operator function between these two functions.</p></td>
</tr>

</tbody>
</table>

### Weaviate Filter

This filter uses the Weaviate Data Store and uses GraphQL as the query language. Since this language is dependent
on the schema in the DB we need to connect to your Weaviate instance and retrieve the schema to build the query.
This is done automatically, but you will need to add the `url` and `port` parameters when creating the JinaClient instance.
Documentation can be found here: [Documentation](https://weaviate.io/developers/weaviate/api/graphql/filters)

To use this filter you must add the `"type" => "weaviate"` key to the `dataStore` array in the configuration.
```php
use DcoAi\PhpJina\JinaClient;
// set the config and create a new instance of the JinaClient
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
    "dataStore" => [
        "type" => "weaviate",
        "url" => "localhost",
        "port" => "8080",
    ]
];
$jina = new JinaClient($config);
```

Here are the specific fields using this Data Store:

<table>
<thead>
<tr>
<th><p>Query Operator</p></th>
<th><p>Chainable Function</p></th>
<th><p>Description</p></th>
</tr>
</thead>
<tbody>

<tr><td><p>Not</p></td>
<td><code>not($column, $value)</code></td>
<td><p>Exclude the value from the query</p></td>
</tr>

<tr><td><p>Equal</p></td>
<td><code>equal($column, $value)</code></td>
<td><p>Equal to the value</p></td>
</tr>

<tr><td><p>NotEqual</p></td>
<td><code>notEqual($column, $value)</code></td>
<td><p>Not equal to the value</p></td>
</tr>

<tr><td><p>GreaterThan</p></td>
<td><code>greaterThan($column, $value)</code></td>
<td><p>Greater than the value</p></td>
</tr>

<tr><td><p>GreaterThanEqual</p></td>
<td><code>greaterThanEqual($column, $value)</code></td>
<td><p>Greater than or equal to the value</p></td>
</tr>

<tr><td><p>LessThan</p></td>
<td><code>lessThan($column, $value)</code></td>
<td><p>Less than the value</p></td>
</tr>

<tr><td><p>LessThanEqual</p></td>
<td><code>lessThanEqual($column, $value)</code></td>
<td><p>Less than or equal to the value</p></td>
</tr>

<tr><td><p>Like</p></td>
<td><code>like($column, $value)</code></td>
<td><p>Allows you to do string searches based on partial match</p></td>
</tr>

<tr><td><p>WithinGeoRange</p></td>
<td><code>withinGeoRange($column, $value)</code></td>
<td><p>A special case of the Where filter is with geoCoordinates.
If you've set the geoCoordinates property type, you can search in an area based on distance.</p></td>
</tr>

<tr><td><p>IsNull</p></td>
<td><code>isNull($column, $value=true or false)</code></td>
<td><p>Allows you to do filter for objects where given properties are null or not null. 
Note that zero-length arrays and empty strings are equivalent to a null value.</p></td>
</tr>

</tbody>
</table>

The list of combining functions for the Default filter is here:
<table>
<thead>
<tr>
<th><p>Operator</p></th>
<th><p>Chainable Function</p></th>
<th><p>Closing Function</p></th>
<th><p>Description</p></th>
</tr>
</thead>

<tbody>

<tr><td><p>$and</p></td>
<td><code>and()</code></td>
<td><code>endAnd()</code></td>
<td><p>Join query clauses with a logical AND by chaining operator function between these two functions.</p></td>
</tr>

<tr><td><p>$or</p></td>
<td><code>or()</code></td>
<td><code>endOr()</code></td>
<td><p>Join query clauses with a logical OR by chaining operator function between these two functions.</p></td>
</tr>

</tbody>
</table>

### All Other Data Store Filters

Currently, these are not supported but are planned for future releases. If you would like to contribute to this project
please feel free to submit a pull request and reach out to me for any questions.

## Response

To save on data transfer and memory when making calls to your Jina application this client will clean up the 
request and response automatically by removing any key where the value is not set. 
Keep this in mind when performing evaluations on the response by checking if the key exists first.

If you want all the values returned you can set a flag when using the `submit()` function
```php
// setting the third parameter to false will not remove any empty values from the response
$jina->submit("/index", $da, false);
```