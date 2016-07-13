Opis Config
===========
[![Latest Stable Version](https://poser.pugx.org/opis/config/version.png)](https://packagist.org/packages/opis/config)
[![Latest Unstable Version](https://poser.pugx.org/opis/config/v/unstable.png)](//packagist.org/packages/opis/config)
[![License](https://poser.pugx.org/opis/config/license.png)](https://packagist.org/packages/opis/config)

Configuration manager
---------------------
**Opis Config** is a configuration management library, with support for multiple backend storages,
that provides developers an API which allows them to work with configurations in a standardised way,
no matter where the configurations are stored.

The supported backend storages are: File, JSON, Mongo, MongoArray, PHPFile, Memory, DualStorage.

##### Important!

You can install an additional storage adapter, for SQL databases, by using the optional [Opis Storages](https://github.com/opis/storages) package.

### License

**Opis Config** is licensed under the [Apache License, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0). 

### Requirements

* PHP 5.3.* or higher

### Installation

This library is available on [Packagist](https://packagist.org/packages/opis/config) and can be installed using [Composer](http://getcomposer.org).

```json
{
    "require": {
        "opis/config": "^2.1.2"
    }
}
```

If you are unable to use [Composer](http://getcomposer.org) you can download the
[tar.gz](https://github.com/opis/config/archive/2.1.2.tar.gz) or the [zip](https://github.com/opis/config/archive/2.1.2.zip)
archive file, extract the content of the archive and include de `autoload.php` file into your project. 

```php

require_once 'path/to/config-2.1.2/autoload.php';

```

### Documentation

Examples and documentation can be found [here](http://opis.io/config).