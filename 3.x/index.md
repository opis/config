---
layout: project
version: 3.x
title: About
description: About Opis Config library
lib: 
    name: opis/config
    version: 3.0.0
---
# Config manager

**Opis Config** is a configuration management library, with support for multiple backend storage, 
that provides developers an API which allows them to work with configurations in a standardised way, 
no matter where the configurations are stored. 

## License

**Opis Config** is licensed under [Apache License, Version 2.0][apache_license].

## Requirements

* PHP 7.0 or higher

## Installation

**Opis Config** is available on [Packagist] and it can be installed from a 
command line interface by using [Composer]. 

```bash
composer require {{page.lib.name}}
```

Or you could directly reference it into your `composer.json` file as a dependency

```json
{
    "require": {
        "{{page.lib.name}}": "^{{page.lib.version}}"
    }
}
```


[apache_license]: http://www.apache.org/licenses/LICENSE-2.0 "Project license" 
{:rel="nofollow" target="_blank"}
[Packagist]: https://packagist.org/packages/{{page.lib.name}} "Packagist" 
{:rel="nofollow" target="_blank"}
[Composer]: http://getcomposer.org "Composer" 
{:ref="nofollow" target="_blank"}
