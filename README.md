Skeetr Silex Provider [![Build Status](https://travis-ci.org/skeetr/silex.png?branch=master)](https://travis-ci.org/skeetr/silex)
==============================

This is a Skeetr implementation for Silex-based apps. 

Requirements
------------

* PHP 5.3.23
* Unix system
* silex/silex
* skeetr/skeetr

Installation
------------

The recommended way to install Skeetr/Silex is [through composer](http://getcomposer.org).
You can see [package information on Packagist.](https://packagist.org/packages/skeetr/silex)

```JSON
{
    "require": {
        "skeetr/silex": "dev-master"
    }
}
```

Parameters
------------

* skeetr.host (string) Gearman server hostname
* skeetr.port (integer) Gearman server port
* skeetr.worker (object) Skeetr\Gearman\Worker instance
* skeetr.client (object) Skeetr\Client instance


Usage
------------

At your Silex project create the file "web/worker.php"

```PHP
$app = new Silex\Application();
$app->register(new Skeetr\Silex\SkeetrServiceProvider())

//Gearman Server hostname
$app['skeetr.host'] = '127.0.0.1';

//Gearman Server port
$app['skeetr.port'] = 4730;

$app['skeetr.client']->work();
```


Tests
-----

Tests are in the `tests` folder.
To run them, you need PHPUnit.
Example:

    $ phpunit --configuration phpunit.xml.dist


License
-------

MIT, see [LICENSE](LICENSE)