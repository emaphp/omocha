omocha
==============

Simple annotation parser for PHP

[![Build Status](https://travis-ci.org/emaphp/omocha.svg?branch=master)](https://travis-ci.org/emaphp/omocha)

####Dependencies

 - PHP 5.4

####Installation

```json
{
    "require": {
        "omocha/omocha": "1.1.*"
    }
}
```

####Examples

>Getting annotations

```php
require 'vendor/autoload.php';

use Omocha\Omocha;

/**
 * @Instrument
 * @Type stringed
 * @Price 500.75
 */
class Violin {
    /**
     * @Amount 4
     */
    private $strings;
    
    /**
     * @Techniques ["Sordino", "Martelé", "Pizzicato"]
     */
    private $bow;
    
    /**
     * @Detail {"name": "Antonio Stradivari", "occupation": "luthier"}
     */
    private $owner;
}

//get annotations as an instance of AnnotationBag
$reflectionClass = new ReflectionClass('Violin');
$annotationBag = Omocha::getAnnotations($reflectionClass);

//check for annotation
$annotationBag->has('Instrument'); // returns true

//get Annotation instance
$annotation = $annotationBag->get('Instrument');

//default value
$annotation->getValue(); // returns true

//strings
$annotationBag->get('Type')->getValue(); // returns 'stringed'

//floats
$annotationBag->get('Price')->getValue(); // returns 500.75

//integers
$annotationBag = Omocha::getAnnotations($reflectionClass->getProperty('strings'));
$annotationBag->get('Amount')->getValue(); //returns 4

//arrays
$annotationBag = Omocha::getAnnotations($reflectionClass->getProperty('bow'));
$annotationBag->get('Techniques')->getValue(); //returns ["Sordino", "Martelé", "Pizzicato"]

//objects
$annotationBag = Omocha::getAnnotations($reflectionClass->getProperty('owner'));
$value = $annotationBag->get('Detail')->getValue();
$value instanceof stdClass; // returns true
$value->name; //returns "Antonio Stradivari"
$value->occupation; //returns "luthier"
```
>Arguments

```php
require 'vendor/autoload.php';

use Omocha\Omocha;
use Omocha\Filter;

/**
 * @RestService
 * @Option(output) XML
 * @Option(template) service.tpl
 * @Option(on_error) 404
 * @Option null
 */
class Webservice {
    /**
     * @Config(MySQL) ['user', 'password', 'database']
     * @Config(PostgreSQL) user=user,password=password,dbname=database
     * @Config(SQLite) database.db
     */
    private $connection;
}

$reflectionClass = new ReflectionClass('Webservice');
$annotationBag = Omocha::getAnnotations($reflectionClass);

//AnnotationBag implements Countable
count($annotationBag); // returns 5

//and IteratorAggregate
foreach ($annotationBag as $annotation) {
    //...
}

//and JsonSerializable
$json = json_encode($annotationBag);

//get first option
$annotation = $annotationBag->get('Option');
//annotation argument is stored as a string
$annotation->getArgument(); //returns 'output'

//the 'find' method gets all annotations with the given name
$options = $annotationBag->find('Option');
count($options); //returns 4

//an additional filter argument could be provided
$argOptions = $annotationBag->find('Option', Filter::HAS_ARGUMENT);
count($argOptions); //returns 3

//annotations could also be filtered by its value type
$annotationBag = Omocha::getAnnotations($reflectionClass->getProperty('connection'));
$connOptions = $annotationBag->find('Config', Filter::HAS_ARGUMENT | Filter::TYPE_ARRAY);
$connOptions[0]->getArgument(); //returns 'MySQL'

//using a custom filter
$conn = $annotationBag->filter(function ($annotation) {
    return $annotation->getArgument() == 'SQLite';
});

$conn[0]->getValue(); // returns 'database.db'
```

####License

Licensed under the Apache License, Version 2.0.