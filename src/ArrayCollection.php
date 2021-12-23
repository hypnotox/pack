<?php

declare(strict_types=1);

namespace HypnoTox\Pack;

use ArrayIterator;
use JetBrains\PhpStorm\Pure;
use RuntimeException;
use Traversable;

/**
 * @template TKey as array-key
 * @template TValue
 *
 * @property array<TKey, TValue> $values
 * @psalm-immutable
 */
class ArrayCollection implements CollectionInterface
{
    /**
     * @param array<TKey, TValue> $values
     */
    public function __construct(
        private readonly array $values = [],
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-mutation-free
     */
    #[Pure]
    public function getValues(): array
    {
        return $this->values;
    }

    /*
     * IteratorAggregate
     */

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     * @psalm-return ArrayIterator<array-key, TValue>
     * @psalm-mutation-free
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    /*
     * Array Access
     */

    /**
     * {@inheritDoc}
     *
     * @param array-key $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return \array_key_exists($offset, $this->values);
    }

    /**
     * {@inheritDoc}
     *
     * @param array-key $offset
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->values[$offset];
    }

    /**
     * {@inheritDoc}
     *
     * @param array-key $offset
     * @param TValue as mixed $value
     */
    public function offsetSet(mixed $offset, mixed $value): never
    {
        throw new RuntimeException(
            sprintf(
                'Attempt to mutate immutable %s object.',
                __CLASS__,
            ),
        );
    }

    /**
     * {@inheritDoc}
     *
     * @param array-key $offset
     */
    public function offsetUnset(mixed $offset): never
    {
        throw new RuntimeException(
            sprintf(
                'Attempt to mutate immutable %s object.',
                __CLASS__,
            ),
        );
    }

    /*
    * Countable
    */

    public function count(): int
    {
        return \count($this->values);
    }
}
