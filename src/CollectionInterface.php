<?php

declare(strict_types=1);

namespace HypnoTox\Pack;

use ArrayAccess;
use IteratorAggregate;

/**
 * @template TKey
 * @template TValue
 * @psalm-immutable
 *
 * @extends IteratorAggregate<TKey, TValue>
 * @extends ArrayAccess<TKey, TValue>
 */
interface CollectionInterface extends IteratorAggregate, ArrayAccess, \Countable
{
    // region Getters

    /**
     * @return iterable<TKey, TValue>
     */
    public function getValues(): iterable;

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
     * @return array<array-key, TValue>
     */
    public function toArray(): array;

    /**
     * @return TValue|null
     */
    public function first(): mixed;

    /**
     * @return TValue|null
     */
    public function last(): mixed;

    /**
     * @param TValue $search
     *
     * @return KeyValuePair<TKey, TValue>|null
     */
    public function findByValue(mixed $search): KeyValuePair|null;

    /**
     * @param pure-callable(TValue, TKey):bool $callback
     *
     * @return KeyValuePair<TKey, TValue>|null
     *
     * @noinspection PhpUndefinedClassInspection
     * @noinspection PhpDocSignatureInspection
     */
    public function findByCallback(callable $callback): KeyValuePair|null;

    // endregion
    // region Collection "modification" methods

    public function splice(int $offset, ?int $length = null, array $replacement = null): self;

    public function slice(int $offset, ?int $length, bool $preserveKeys = false): self;

    public function merge(array|self $collection): self;

    /**
     * @param pure-callable(TValue):TValue $callback
     *
     * @noinspection PhpUndefinedClassInspection
     * @noinspection PhpDocSignatureInspection
     */
    public function map(callable $callback): self;

    /**
     * @param pure-callable(TValue, TKey):KeyValuePair<TKey, TValue> $callback
     *
     * @noinspection PhpUndefinedClassInspection
     * @noinspection PhpDocSignatureInspection
     */
    public function mapWithKeys(callable $callback): self;

    // endregion
}
