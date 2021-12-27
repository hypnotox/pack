# hypnotox/pack

![CI Status](https://img.shields.io/github/workflow/status/hypnotox/pack/CI)
![Code Coverage](https://raw.githubusercontent.com/hypnotox/pack/main/coverage/badge.svg)
![GitHub issues](https://img.shields.io/github/issues/hypnotox/pack)

A collection library offering immutable collections.

Collections implement [`\HypnoTox\Pack\CollectionInterface`](/src/CollectionInterface.php) which extends `\IteratorAggregate`, `ArrayAccess` and `\Countable` and adds other chainable methods.

This is just a personal project. It will follow semantic versioning and will be stable, but don't expect constant development, especially when it is declared feature complete.

PRs are welcome.

## Installation

Simply require it using composer: `composer require hypnotox/pack`

## Features

Currently, the only implemented collection is [`HypnoTox\Pack\ArrayCollection`](/src/ArrayCollection.php).

## Usage

### ArrayCollection

`$collection = new \HypnoTox\Pack\ArrayCollection([1, 2, 3]);`

## Collection Methods

// TODO
