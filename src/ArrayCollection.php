<?php

declare(strict_types=1);

namespace HypnoTox\Pack;

use ArrayIterator;
use Traversable;

/**
 * @template TKey as array-key
 * @template TValue
 *
 * @psalm-immutable
 * @psalm-consistent-constructor
 * @psalm-consistent-templates
 */
final class ArrayCollection implements CollectionInterface
{
    // region Properties & Constructor

    /**
     * @param array<TKey, TValue> $values
     * @psalm-pure
     */
    public function __construct(
        private readonly array $values = [],
    ) {
    }

    // endregion
    // region Getters

    /**
     * {@inheritDoc}
     */
    public function getValues(): array
    {
        return $this->values;
    }

    // endregion
    // region Base methods

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
    public function set(mixed $key, mixed $value): self
    {
        $values = $this->values;
        $values[$key] = $value;

        return new self($values);
    }

    /**
     * {@inheritDoc}
     *
     * @param array-key $key
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function unset(mixed $key): self
    {
        $values = $this->values;
        unset($values[$key]);

        return new self($values);
    }

    // endregion
    // region Collection "modification" methods

    public function splice(int $offset, ?int $length = null, array $replacement = null): self
    {
        $values = $this->values;

        if ($replacement) {
            array_splice($values, $offset, $length ?? \count($values), $replacement);
        } else {
            array_splice($values, $offset, $length ?? \count($values));
        }

        return new self($values);
    }

    public function slice(int $offset, ?int $length, bool $preserveKeys = false): self
    {
        $values = $this->values;
        $values = \array_slice($values, $offset, $length ?? \count($values), $preserveKeys);

        return new self($values);
    }

    // endregion
    // region IteratorAggregate

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

    // endregion
    // region ArrayAccess

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
     *
     * @throws ImmutableException
     */
    public function offsetSet(mixed $offset, mixed $value): never
    {
        throw new ImmutableException(
            $this,
            __FUNCTION__
        );
    }

    /**
     * {@inheritDoc}
     *
     * @param array-key $offset
     *
     * @throws ImmutableException
     */
    public function offsetUnset(mixed $offset): never
    {
        throw new ImmutableException(
            $this,
            __FUNCTION__
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
