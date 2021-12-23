<?php

declare(strict_types=1);

namespace HypnoTox\Pack;

use ArrayAccess;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;

/**
 * @template TKey
 * @template TValue
 * @psalm-immutable
 * @psalm-consistent-constructor
 * @psalm-consistent-templates
 *
 * @extends IteratorAggregate<TKey, TValue>
 * @extends ArrayAccess<TKey, TValue>
 */
interface CollectionInterface extends IteratorAggregate, ArrayAccess, \Countable
{
    /**
     * @return iterable<TKey, TValue>
     */
    #[Pure]
    public function getValues(): iterable;

    /**
     * @param TKey $key
     *
     * @return TValue
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
    public function set(mixed $key, mixed $value): static;

    /**
     * @param TKey $key
     */
    public function unset(mixed $key): static;
}
