# pedroac\url4php

[![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](CONTRIBUTING.md)
[![Build Status](https://travis-ci.org/pedroac/url4php.svg?branch=master)](https://travis-ci.org/pedroac/url4php)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/8687a9318a3043e990a4dae4234a3a00)](https://www.codacy.com/app/pedroac/url4php?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=pedroac/url4php&amp;utm_campaign=Badge_Grade)
[![GitHub tag](https://img.shields.io/github/tag/pedroac/url4php.svg)]()
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![Support via PayPal](https://img.shields.io/badge/Donate-PayPal-green.svg)](http://paypal.me/pedroac)

URL value object, parser, normalization rules and URI components library tools, following the RFC 3986 and RFC 1738 standards.

## Table of contents

* [Getting started](#getting-started)
    * [Prerequisites](#prerequisites)
    * [Installing](#installation)
* [Code examples](#code-examples)
    * [Parsing an URL](#parsing-an-url)
    * [Changing an URL (immutable)](#changing-an-url-immutable)
    * [Parsing and changing URL components](#parsing-and-changing-url-components)
    * [Converting URL to absolute URL](#converting-url-to-absolute-url)
    * [Getting the current URL](#getting-the-current-url)
    * [Checking if an URL is absolute](#checking-if-an-url-is-absolute)
    * [Getting path extensions](#getting-path-extensions)
    * [Applying non-destructive normalization rules](#applying-non-destructive-normalization-rules)
    * [Applying common normalization rules](#applying-common-normalization-rules)
    * [Applying selected or customized normalization rules](#applying-selected-or-customized-normalization-rules)
    * [Applying common normalization rules and other selected rules](#applying-common-normalization-rules-and-other-selected-rules)
* [Authors](#authors)

## Getting started

### Prerequisites

* PHP 7.1 or later: http://php.net/downloads.php
* composer: https://getcomposer.org

### Installing

Add to your `composer.json` file:

```json
"repositories": [
    {
        "url": "https://github.com/pedroac/url4php.git",
        "type": "vcs"
    }
],
"require": {
    "pedroac/url4php": "@stable"
}
```

And execute:
```bash
composer.phar update
```

## Code examples

If you need Unicode support, make sure that the multibyte internal code is set
accordingly, for instance:
```
mb_internal_encoding("UTF-8");
```

### Parsing an URL
```php
<?php
use pedroac\url\Parsed;

$parsed = Parsed::fromString('http://test.com:80/my/path?a=1&b=2#label');

echo $parsed->scheme;
// http
echo $parsed->host;
// test.com
echo $parsed->port;
// 80
echo $parsed->path;
// /my/path
echo $parsed->query;
// a=1&b=2
echo $parsed->fragment;
// label
```

### Changing an URL (immutable)
```php
<?php
use pedroac\url\URL;
use pedroac\url\component\Scheme;
use pedroac\url\component\Query;
use pedroac\url\component\Fragment;

$url = new URL('http://test.com/my/path');
$newUrl = $url->with(new Scheme('https'),
                     null,
                     null,
                     Query::fromArray(['a' => 1, 'b' => 2]),
                     new Fragment('label'));
echo $newUrl;
// https://test.com/my/path?a=1&b=2#label
echo $newUrl->changeParameters(['c' => 3]);
// https://test.com/my/path?a=1&b=2&c=3#label
```

### Parsing and changing URL components

```php
<?php
use pedroac\url\URL;
use pedroac\url\component\Scheme;
use pedroac\url\component\Query;
use pedroac\url\component\Path;

$url        = new URL('http://test.com/my/path');
$components = $url->parse()->toComponents();
$components->scheme = new Scheme('https');
$components->query  = Query::fromArray(['a' => '1']);
$components->path   = $components->getPath()
                      ->merge(new Path('new/path'));
$newUrl = new URL($components);
echo $newUrl;
// https://test.com/my/new/path?a=1
```
`pedroac\url\URL::parse` method returns an immutable object (`pedroac\url\Parsed`).

`pedro\url\Parsed::toComponents` method returns a mutable object.

Safe normalizations are applied.

### Converting URL to absolute URL
```php
<?php
use pedroac\url\Base;
use pedroac\url\URL;

$base   = Base::fromString('http://test.com/this/is');
$newUrl = $base->toAbsolute(new URL('my/path'));
echo $newUrl;
// http://test.com/this/my/path
```

### Getting the current URL
```php
<?php
use pedroac\url\URL;

echo URL::getCurrent();
```

### Checking if an URL is absolute
```php
<?php
use pedroac\url\URL;

$url = new URL('http://test.com/my/path');
var_dump($url->isAbsolute());
// bool(true)
```

### Getting path extensions

```php
<?php
use pedroac\url\Parsed;

$parsed = Parsed::fromString('http://test.com/my/path/file.tar.gz');

echo $parsed->path->getExtension();
// gz
echo print_r($parsed->path->getAllExtensions());
/*
Array
(
    [0] => tar
    [1] => gz
)
*/
```

### Applying non-destructive normalization rules

```php
<?php
use pedroac\url\URL;
use pedroac\url\normalization\Rules;

$rules = Rules::safe();
echo $rules->apply(new URL('HttP://TesT.com/my/path?a=1&b=2&f=3'));
// http://test.com/my/path?a=1&b=2&f=3
```

### Applying common normalization rules

```php
<?php
use pedroac\url\URL;
use pedroac\url\normalization\Rules;

$rules = Rules::basic();
echo $rules->apply(new URL('HttP://TesT.com/my/.//path?b=1&c=2&a=3')), "\n";
// http://test.com/my/path/?a=3&b=1&c=2
```

### Applying selected or customized normalization rules

```php
<?php
use pedroac\url\URL;
use pedroac\url\normalization\Rules;
use pedroac\url\normalization\rule\StripWWWRule;
use pedroac\url\normalization\rule\StripDuplicatedSlashesRule;
use pedroac\url\normalization\rule\StripDotSegmentsRule;
use pedroac\url\normalization\rule\StripDirectoryIndexRule;
use pedroac\url\normalization\rule\StripDefaultPortRule;
use pedroac\url\normalization\rule\StripUnusedParametersRule;
use pedroac\url\normalization\rule\StripDefaultParametersRule;

$rules = new Rules(new StripWWWRule,
                   new StripDuplicatedSlashes,
                   new StripDotSegmentsRule,
                   new StripDirectoryIndexRule,
                   new StripDefaultPortRule,
                   new StripUnusedParametersRule(['a','b','c']),
                   new StripDefaultParametersRule(['b'=>2]));
$newUrl = $rules->apply(new URL('http://test.com/my/path?a=1&b=2&f=3'));
echo $newUrl;
// http://test.com/my/path?a=1
```

### Applying common normalization rules and other selected rules

```php
<?php
use pedroac\url\URL;
use pedroac\url\normalization\Rules;
use pedroac\url\normalization\rule\StripUnusedParametersRule;
use pedroac\url\normalization\rule\StripDefaultParametersRule;

$rules = Rules::basicAnd(
    new StripUnusedParametersRule(['a','b','c']),
    new StripDefaultParametersRule(['b'=>2])
);
echo $rules->apply(new URL('HttP://TesT.com/my/.//path?b=2&c=2&a=3')), "\n";
```

## Authors
* Pedro Amaral Couto: https://github.com/pedroac
