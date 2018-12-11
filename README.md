# Simple slugger for PHP 7.2+ based on ICU

[![Gitter](https://badges.gitter.im/sunrise-php/support.png)](https://gitter.im/sunrise-php/support)
[![Build Status](https://api.travis-ci.com/sunrise-php/slugger.svg?branch=master)](https://travis-ci.com/sunrise-php/slugger)
[![CodeFactor](https://www.codefactor.io/repository/github/sunrise-php/slugger/badge)](https://www.codefactor.io/repository/github/sunrise-php/slugger)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sunrise-php/slugger/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sunrise-php/slugger/?branch=master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/sunrise-php/slugger/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![Latest Stable Version](https://poser.pugx.org/sunrise/slugger/v/stable?format=flat)](https://packagist.org/packages/sunrise/slugger)
[![Total Downloads](https://poser.pugx.org/sunrise/slugger/downloads?format=flat)](https://packagist.org/packages/sunrise/slugger)
[![License](https://poser.pugx.org/sunrise/slugger/license?format=flat)](https://packagist.org/packages/sunrise/slugger)

## Awards

[![SymfonyInsight](https://insight.symfony.com/projects/64e80815-60f3-47eb-8163-8bd2538376e9/big.svg)](https://insight.symfony.com/projects/64e80815-60f3-47eb-8163-8bd2538376e9)

## Installation

```
composer require sunrise/slugger
```

## How to use

#### Russian to Latin (default)

```php
$slugger = new \Sunrise\Slugger\Slugger();

// "syesh-yeshche-etikh-myagkikh-frantsuzskikh-bulok-da-vypey-chayu"
$slugger->slugify('Съешь ещё этих мягких французских булок, да выпей чаю');
```

#### Deutsch to Latin

```php
$slugger = new \Sunrise\Slugger\Slugger();

$slugger->setTransliteratorId('de-ASCII');

// "falsches-ueben-von-xylophonmusik-quaelt-jeden-groesseren-zwerg"
$slugger->slugify('Falsches Üben von Xylophonmusik quält jeden größeren Zwerg');
```

#### Only transliteration

```php
$slugger = new \Sunrise\Slugger\Slugger();

$slugger->setTransliteratorId('Hiragana-Latin');

// "irohanihoheto chirinuruwo wakayotareso tsunenaramu uwinookuyama kefukoete asakiyumemishi wehimosesu"
$slugger->transliterate('いろはにほへと ちりぬるを わかよたれそ つねならむ うゐのおくやま けふこえて あさきゆめみし ゑひもせす', '');
```

#### Customization

```php
$slugger = new \Sunrise\Slugger\Slugger();

$slugger->setTransliteratorId('Greek-Latin/BGN');

// "takhisti alopix vafis psimeni yi dhraskelizi iper nothrou kinos"
$slugger->transliterate('Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός', 'Any-Latin; Latin-ASCII; Lower(); [^\x20\x30-\x39\x41-\x5A\x61-\x7A] Remove');
```

#### Using DI Container

```php
$di['slugger'] = function() : \Sunrise\Slugger\SluggerInterface
{
	$slugger = new \Sunrise\Slugger\Slugger();

	$slugger->setTransliteratorId('de-ASCII');

	return $slugger;
};

$di['slugger']->slugify('Zwölf große Boxkämpfer jagen Viktor quer über den Sylter Deich.');
```

## Api documentation

https://phpdoc.fenric.ru/

## Useful links

http://site.icu-project.org/<br>
http://userguide.icu-project.org/transforms/general<br>
http://demo.icu-project.org/icu-bin/translit
