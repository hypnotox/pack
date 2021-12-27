<?php

declare(strict_types=1);

namespace HypnoTox\Pack;

use ArrayAccess;
use IteratorAggregate;

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
    /*
     * Getters
     */

    /**
     * @return iterable<TKey, TValue>
     */
    public function getValues(): iterable;

    /*
     * Base methods
     */

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

    /*
     * Collection "modification" methods
     */

    public function splice(int $offset, ?int $length = null): static;

    public function slice(int $offset, ?int $length, bool $preserveKeys = false): static;
}
