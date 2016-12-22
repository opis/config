CHANGELOG
---------
### v3.0.x-dev

* Removed support for PHP 5.x. Now it supports only PHP 7.0.*
* Removed `Opis\Config\Config` class
* Removed `Opis\Config\ArrayHelper` class
* Removed `Opis\Config\Storage\Mongo` class
* Removed `Opis\Config\Storage\MongoArray` class
* Removed `Opis\Config\StorageInterface` interface
* Added `Opis\Config\ConfigInterface` interface
* Added `Opis\Config\ConfigHelper` class
* All classes under the `Opis\Config\Storage` namespace were moved under
the `Opis\Config\Drivers` namespace
* Source files were moved from `lib` to `src`

### v2.1.2, 2016.07.13

* Fixed a potential bug in `Opis\Config\Storage\File::fileWrite` method
* Added a `.gitignore` file

### v2.1.1, 2015.10.15

* The `@` operator is now used with `mkdir` function in `Opis\Config\Storage\File::__construct` method

### v2.1.0, 2015.10.08

* Changed the way `File`, `JSON` and `PHPFile` storages behaves when the specified directory doesn't exits and now
they try to create it first, before throwing an exception.
* Added `fileWrite` method to `Opis\Config\Storage\File` class.
* Modified the `writeConfig` method for `File`, `JSON` and `PHPFile` storages. It uses now the newly added
`fileWrite` method to save configurations.

### v2.0.0, 2015.08.31

* Removed `Opis\Config\StorageCollection` class
* `Opis\Config\Storage\Database` was moved into the `opis/storages` package
* Removed `opis\database` and `opis\closure` dependencies

### v1.6.0, 2015.07.31

* Updated `opis/closure` library dependency to version `^2.0.0`
* Updated `opis/database` library dependency to version `^2.1.1`
* Removed `branch-alias` property from the `composer.json` file

### v1.5.0, 2015.07.31

* Updated `opis/closure` library dependency to version `~2.0.*`
* Updated `opis/database` library dependency to version `~2.0.*`

### v1.4.0, 2014.11.23

* Added autoload file

### v1.4.0, 2014.10.23

* Updated `opis/database` library dependency to version `2.0.*`
* Changed `Opis\Config\Storage\Database` class to reflect changes
* Updated `opis/closure` library dependency to version `1.3.*`

### v1.3.0, 2014.06.26

* Updated `opis/database` library dependency to version `1.3.*`

### v1.2.0, 2014.06.01

* Started changelog
* Added support for database storage
* Added support for MongoDB storage
* Added `opis/database: 1.2.*` dependency
