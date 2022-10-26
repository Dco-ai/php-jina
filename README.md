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

The configuration is an associative array with 3 requirements:
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
<td><p>url</p></td>
<td><p>string</p></td>
<td><p>The endpoint of your Jina application. 
This can be a public URL or a private one if this client is used on the same network.</p></td>
</tr>
<tr>
<td><p>port</p></td>
<td><p>string</p></td>
<td><p>The port used in your Jina application</p></td>
</tr>
<tr>
<td><p>endpoints</p></td>
<td><p>associative array</p></td>
<td><p>This is how this client knows what endpoint uses which method when making the curl request. 
Since Jina allows you to make custom endpoints we need to know how to handle them. 
The default is <code>GET</code> so if your endpoint is not set here then it will attempt the call using <code>GET</code>.</p></td>
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

</tbody>
</table>

## Response

To save on data transfer and memory when making calls to your Jina application this client will clean up the 
request and response automatically by removing any key where the value is not set. 
Keep this in mind when performing evaluations on the response by checking if the key exists first.

If you want all the values returned you can set a flag when using the `submit()` function
```php
// setting the third parameter to false will not remove any empty values from the response
$jina->submit("/index", $da, false);
```