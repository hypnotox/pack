<?php

declare(strict_types=1);

namespace HypnoTox\Pack;

use ArrayIterator;
use RuntimeException;
use Traversable;

/**
 * @template TKey as array-key
 * @template TValue
 *
 * @property array<TKey, TValue> $values
 * @psalm-immutable
 * @psalm-consistent-constructor
 * @psalm-consistent-templates
 */
class ArrayCollection implements CollectionInterface
{
    /**
     * @param array<TKey, TValue> $values
     * @psalm-pure
     */
    public function __construct(
        private readonly array $values = [],
    ) {
    }

    /*
     * Getters
     */

    /**
     * {@inheritDoc}
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /*
     * Base methods
     */

    /**
     * {@inheritDoc}
     *
     * @param array-key $key
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function exists(mixed $key): bool
    {
        return \array_key_exists($key, $this->values);
    }

    /**
     * {@inheritDoc}
     *
     * @param array-key $key
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function get(mixed $key): mixed
    {
        return $this->values[$key];
    }

    /**
     * {@inheritDoc}
     *
     * @param array-key $key
     * @param TValue    $value
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function set(mixed $key, mixed $value): static
    {
        $values = $this->values;
        $values[$key] = $value;

        return new static($values);
    }

    /**
     * {@inheritDoc}
     *
     * @param array-key $key
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function unset(mixed $key): static
    {
        $values = $this->values;
        unset($values[$key]);

        return new static($values);
    }

    /*
     * Collection "modification" methods
     */

    public function splice(int $offset, ?int $length = null): static
    {
        $values = $this->values;
        array_splice($values, $offset, $length ?? \count($values));

        return new static($values);
    }

    public function slice(int $offset, ?int $length, bool $preserveKeys = false): static
    {
        $values = $this->values;
        $values = \array_slice($values, $offset, $length ?? \count($values), $preserveKeys);

        return new static($values);
    }

    /*
     * IteratorAggregate
     */

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     * @psalm-return ArrayIterator<array-key, TValue>
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
        return $this->exists($offset);
    }

    /**
     * {@inheritDoc}
     *
     * @param array-key $offset
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
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
                'Cannot mutate immutable object of type %s.',
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
                'Cannot mutate immutable object of type %s.',
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
