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
        "opis/config": "1.1.*"
    }
}
```

###Documentation

###Examples

```php
use \Opis\Config\StorageCollection;
use \Opis\Config\Storage\File as FileStorage;

$config = new StorageCollection();

$config->add('connections', function(){
    return new FileStorage('/path/to/writeable/config/folder');
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

//Serialization
$config = unserialize(serialize($config));


print $config('connections')->read('mysql.database'); //> OtherDatabase
print $config('connections')->read('mysql.host'); //> localhost
```