<?php

declare(strict_types=1);

namespace HypnoTox\Pack\Collection;

use HypnoTox\Pack\DTO\KeyValuePair;

/**
 * @template TKey
 * @template TValue
 * @psalm-immutable
 *
 * @extends \IteratorAggregate<TKey, TValue>
 * @extends \ArrayAccess<TKey, TValue>
 */
interface CollectionInterface extends \ArrayAccess, \Countable, \IteratorAggregate
{
    // region Getters

    /**
     * @return list<TKey>
     */
    public function getKeys(): array;

    /**
     * @return list<TValue>
     */
    public function getValues(): array;

    /**
     * @return array<array-key, TValue>
     * @psalm-return (TKey is array-key ? array<TKey, TValue> : array<array-key, TValue>)
     */
    public function toArray(): array;

    // endregion
    // region Base methods

    /**
     * @param TKey $key
     */
    public function exists(mixed $key): bool;

    /**
     * @param TKey $key
     *
     * @return TValue
     */
    public function get(mixed $key): mixed;

    /**
     * @param TKey   $key
     * @param TValue $value
     */
    public function set(mixed $key, mixed $value): self;

    /**
     * @param TKey $key
     */
    public function unset(mixed $key): self;

    /**
     * @return KeyValuePair<TKey, TValue>|null
     */
    public function first(): KeyValuePair|null;

    /**
     * @return KeyValuePair<TKey, TValue>|null
     */
    public function last(): KeyValuePair|null;

    /**
     * @param TValue $search
     *
     * @return KeyValuePair<TKey, TValue>|null
     */
    public function findByValue(mixed $search): KeyValuePair|null;

    /**
     * @param callable(TValue, TKey):bool $callback
     * @psalm-param pure-callable(TValue, TKey):bool $callback
     *
     * @return KeyValuePair<TKey, TValue>|null
     *
     * @noinspection PhpUndefinedClassInspection
     */
    public function findByCallback(callable $callback): KeyValuePair|null;

    /**
     * @return self<TKey, TValue>
     */
    public function splice(int $offset, ?int $length = null, array $replacement = null): self;

    /**
     * @return self<TKey, TValue>
     */
    public function slice(int $offset, ?int $length, bool $preserveKeys = false): self;

    /**
     * @param array<TKey, TValue>|CollectionInterface<TKey, TValue> $collection
     * @psalm-param (TKey is array-key ? array<array-key, TValue>|CollectionInterface<TKey, TValue> : CollectionInterface<TKey, TValue>) $collection
     *
     * @return self<TKey, TValue>
     */
    public function merge(array|self $collection): self;

    /**
     * @template TMapKey
     *
     * @param callable(TValue, TKey):TMapKey $callback
     * @psalm-param pure-callable(TValue, TKey):TMapKey $callback
     *
     * @return CollectionInterface<TMapKey, TValue>
     *
     * @noinspection PhpUndefinedClassInspection
     */
    public function mapKeys(callable $callback): self;

    /**
     * @template TMapValue
     *
     * @param callable(TValue, TKey):TMapValue $callback
     * @psalm-param pure-callable(TValue, TKey):TMapValue $callback
     *
     * @return CollectionInterface<TKey, TMapValue>
     *
     * @noinspection PhpUndefinedClassInspection
     */
    public function mapValues(callable $callback): self;

    /**
     * @template TMapKey
     * @template TMapValue
     *
     * @param callable(TValue, TKey):KeyValuePair<TMapKey, TMapValue> $callback
     * @psalm-param pure-callable(TValue, TKey):KeyValuePair<TMapKey, TMapValue> $callback
     *
     * @return CollectionInterface<TMapKey, TMapValue>
     *
     * @noinspection PhpUndefinedClassInspection
     */
    public function mapKeyValuePairs(callable $callback): self;

    // endregion
}
