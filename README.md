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
        "opis/config": "1.3.*"
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

###Dual storage example

Dual storage allows you to simultaneously write into two storages.
Reading is made from the first storage but in case of fail the second storage will be checked.

```php
use \Opis\Config\StorageCollection;
use \Opis\Config\Storage\Database as DatabaseStorage;
use \Opis\Config\Storage\PHPFile as PHPFileStorage;
use \Opis\Config\Storage\DualStorage;
use \Opis\Database\Connection;

$config = new StorageCollection();

$connection = Connection::mysql('user', 'password')
                        ->database('mydatabase')
                        ->charset('utf8');

$config->add('roles', function() use ($connection) {
    return new DualStorage(
      new DatabaseStorage($connection, 'configuration'),
      new PHPFileStorage('/path/to/writeable/config/folder'),
      true // enable auto-sync on read failure
    );
});

$roles_conf = $config->get('roles');

// The config will be written in database and file.
$roles_conf->write('admin', array('permissions' => 'all'));

// First read is on database, if no results tries to read from file.
print $roles_conf->read('admin.permissions'); //> all

// Delete the config record from database and comment the write part ($roles_conf->write(...)) 
// from this code.
// Re-running code, you should still get the result from file and also the record 
// will be in database because auto-sync on read is enabled.

```




