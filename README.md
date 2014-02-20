Opis Config
===========
[![Latest Stable Version](https://poser.pugx.org/opis/config/version.png)](https://packagist.org/packages/opis/config)
[![Latest Unstable Version](https://poser.pugx.org/opis/config/v/unstable.png)](//packagist.org/packages/opis/config)
[![License](https://poser.pugx.org/opis/config/license.png)](https://packagist.org/packages/opis/config)

Simple config library
---------------------

###Installation

This library is available on [Packagist](https://packagist.org/packages/opis/config) and can be installed using [Composer](http://getcomposer.org)

```json
{
    "require": {
        "opis/config": "1.0.*"
    }
}
```

###Documentation

###Examples

```php
use \Opis\Config\StorageCollection;
use \Opis\Config\Storage\StandardFile;

$config = new StorageCollection();

$config->add('connections', function(){
    return new StandardFile('/path/to/config.file');
});

$config->get('connections')->write('mysql', array(
    'database' => 'MyDatabase',
    'user' => 'doe',
    'password' => 'secret',
));

print $config->get('connections')->read('mysql.password'); //> secret

//Shorter syntax
print $config('connections')->read('mysql.user'); //> doe
print $config('connections')->read('mysql.database'); //> MyDatabase

//Alter
$config('connections')->write('mysql.database', 'OtherDatabase');

//Add new
$config('connections')->write('mysql.host', 'localhost');

//Save config
$config('connections')->save();

//Serialization
$config = unserialize(serialize($config));


print $config('connections')->read('mysql.database'); //> OtherDatabase
print $config('connections')->read('mysql.host'); //> localhost
```