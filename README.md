# hypnotox/pack

![CI Status](https://github.com/hypnotox/pack/actions/workflows/ci.yml/badge.svg)
[![Code Coverage](https://codecov.io/gh/hypnotox/pack/branch/main/graph/badge.svg)](https://codecov.io/gh/hypnotox/pack)
[![Packagist Version](https://badgen.net/packagist/v/hypnotox/pack)](https://packagist.org/packages/hypnotox/pack)
[![Packagist PHP Version Support](https://badgen.net/packagist/php/hypnotox/pack)](https://packagist.org/packages/hypnotox/pack)
[![GitHub](https://badgen.net/packagist/license/hypnotox/pack)](/LICENSE.md)

A collection library offering immutable collections.

Collections implement [`\HypnoTox\Pack\CollectionInterface`](/src/Collection/CollectionInterface.php) which extends `\IteratorAggregate`, `\ArrayAccess` and `\Countable` and adds other chainable methods.

This is just a personal project. It will follow semantic versioning and will be stable, but don't expect constant development, especially when it is declared feature complete.

PRs are welcome.

## Installation

Simply require it using composer: `composer require hypnotox/pack`

## Features

Currently, the only implemented collection is [`\HypnoTox\Pack\ArrayCollection`](/src/Collection/ArrayCollection.php).

Everything is type-hinted with generic template expressions to allow for full typing using static analysis.

## Usage

### ArrayCollection

```php
$collection = new \HypnoTox\Pack\ArrayCollection([1, 2, 3]);

$collection->set(0, 100)->first(); // 100
```

## Collection Methods

// TODO
