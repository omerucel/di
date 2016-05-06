[![Build Status](https://secure.travis-ci.org/omerucel/di.png)](http://travis-ci.org/omerucel/di)

# About

A simple DI class.

# Composer

```yaml
{
    "require": {
        "omerucel/di": "dev-master"
    }
}
```

# Usage

```php
<?php


$environment = getenv('APPLICATION_ENV');

$di = new OU\DI();
$di->setShared('config', function ($di) use ($environment) {
    return new Config(realpath(__DIR__ . '/' . $environment . '.php');
});
$di->setShared('logger', function ($di) {
    return new Logger($di->get('config')->file_path);
});

/**
 * @var Logger $logger
 */
$logger = $di->get('logger');
$logger->info('Hello world!');

// Anonymous function works again for key when second argument is true.
$di->get('logger', true)->info('Hello world!');
```

With Service implementation:
```php
<?php

namespace Project\Service;

class ConfigService implements \OU\DI\Service
{
    public function getService(\OU\DI $di)
    {
        $environment = $di->get('environment');
        return new Config(realpath(__DIR__ . '/' . $environment . '.php');
    }
}
```

```php
<?php
$di = new OU\DI();
$di->setShared('environment', 'development');
$di->setSharedService('config', 'Project\Service\ConfigService');
$config = $di->getShared('config');
```
