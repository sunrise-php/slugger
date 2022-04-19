# Simple slugger for PHP 7.1+ based on ICU

[![Gitter](https://badges.gitter.im/sunrise-php/support.png)](https://gitter.im/sunrise-php/support)
[![Build Status](https://circleci.com/gh/sunrise-php/slugger.svg?style=shield)](https://circleci.com/gh/sunrise-php/slugger)
[![Code Coverage](https://scrutinizer-ci.com/g/sunrise-php/slugger/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sunrise-php/slugger/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sunrise-php/slugger/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sunrise-php/slugger/?branch=master)
[![Total Downloads](https://poser.pugx.org/sunrise/slugger/downloads?format=flat)](https://packagist.org/packages/sunrise/slugger)
[![Latest Stable Version](https://poser.pugx.org/sunrise/slugger/v/stable?format=flat)](https://packagist.org/packages/sunrise/slugger)
[![License](https://poser.pugx.org/sunrise/slugger/license?format=flat)](https://packagist.org/packages/sunrise/slugger)

## Installation

```bash
composer require sunrise/slugger
```

## How to use

#### Russian to Latin (default)

```php
use Sunrise\Slugger\Slugger;

$slugger = new Slugger();

// syesh-yeshche-etikh-myagkikh-frantsuzskikh-bulok-da-vypey-chayu
$slugger->slugify('Съешь ещё этих мягких французских булок, да выпей чаю');
```

#### Deutsch to Latin

```php
use Sunrise\Slugger\Slugger;

$slugger = new Slugger('de-ASCII');

// falsches-ueben-von-xylophonmusik-quaelt-jeden-groesseren-zwerg
$slugger->slugify('Falsches Üben von Xylophonmusik quält jeden größeren Zwerg');
```

#### Custom replacements

```php
$slugger = new Slugger(null, [
    '.' => ' dot ',
    '@' => ' at ',
]);

// admin@acme.com
$slugger->slugify('admin-at-acme-dot-com');
```

## Useful links

* http://site.icu-project.org/
* http://userguide.icu-project.org/transforms/general
* http://demo.icu-project.org/icu-bin/translit
