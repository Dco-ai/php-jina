A tool to connect to Jina with PHP.

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
  "name":  "Dco-ai/php-jina",
  "repositories": [
    {
      "type": "svn",
      "url": "https://github.com/Dco-ai/php-jina.git"
    }
  ],
  "require": {
    "Dco-ai/php-jina": "main"
  }
}
```

### or from Packagist:
```json
{
  "require": {
    "Dco-ai/php-jina": "1.0.*"
  }
}
```

now run composer with `composer update`

## Usage

A small example is `src/example.php`. This shows you how to load the class and then create/update Jinas Document
and DocumentArray structures.

First include the package in the header:
```php
<?php
use DcoAi\PhpJina\Jina;
```

Now Instantiate the Jina class:
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
$jina = new Jina($config);
```

Now you can use these functions:
```php
// this creates a Document that you can add data to the structure
$d = $jina->document();

// This creates a DocumentArray that Documents can be added to
$da = $jina->documentArray();

// This adds Documents to a DocumentArray
$jina->addDocument($da, $d);

// This sends the DocumentArray to your Jina application and returns the result.
$jina->submit("/index",$da);
```