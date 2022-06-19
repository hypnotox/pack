<?php

declare(strict_types=1);

namespace HypnoTox\Pack\Collection;

use ArrayIterator;
use HypnoTox\Pack\DTO\KeyValuePair;
use HypnoTox\Pack\Exception\ImmutableException;
use Traversable;

/**
 * @template TKey as array-key
 * @template TValue as mixed
 *
 * @psalm-immutable
 * @template-implements CollectionInterface<TKey, TValue>
 */
final class ArrayCollection implements CollectionInterface
{
    // region Properties & Constructor

    /**
     * @param array<TKey, TValue> $data
     */
    public function __construct(
        private readonly array $data = [],
    ) {
    }

    // endregion
    // region Getters

    public function getKeys(): array
    {
        return array_keys($this->data);
    }

    public function getValues(): array
    {
        return array_values($this->data);
    }

    public function toArray(): array
    {
        return $this->data;
    }

    // endregion
    // region Base methods

    /**
     * {@inheritDoc}
     *
     * @param TKey $key
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function exists(mixed $key): bool
    {
        return \array_key_exists($key, $this->data);
    }

    /**
     * {@inheritDoc}
     *
     * @param TKey $key
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function get(mixed $key): mixed
    {
        return $this->data[$key];
    }

    /**
     * {@inheritDoc}
     *
     * @param TKey   $key
     * @param TValue $value
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function set(mixed $key, mixed $value): self
    {
        $values = $this->data;
        $values[$key] = $value;

        return new self($values);
    }

    /**
     * {@inheritDoc}
     *
     * @param TKey $key
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function unset(mixed $key): self
    {
        $values = $this->data;
        unset($values[$key]);

        return new self($values);
    }

    public function first(): mixed
    {
        $key = array_key_first($this->data);

        return $key ? $this->data[$key] : null;
    }

    public function last(): mixed
    {
        $key = array_key_last($this->data);

        return $key ? $this->data[$key] : null;
    }

    public function findByValue(mixed $search): KeyValuePair|null
    {
        foreach ($this->data as $key => $value) {
            if ($value === $search) {
                return new KeyValuePair($key, $value);
            }
        }

        return null;
    }

    public function findByCallback(callable $callback): KeyValuePair|null
    {
        foreach ($this->data as $key => $value) {
            if ($callback($value, $key)) {
                return new KeyValuePair($key, $value);
            }
        }

        return null;
    }

    // endregion
    // region Collection "modification" methods

    public function splice(int $offset, ?int $length = null, array $replacement = null): self
    {
        $values = $this->data;

        if ($replacement) {
            array_splice($values, $offset, $length ?? \count($values), $replacement);
        } else {
            array_splice($values, $offset, $length ?? \count($values));
        }

        return new self($values);
    }

    public function slice(int $offset, ?int $length, bool $preserveKeys = false): self
    {
        $values = $this->data;
        $values = \array_slice($values, $offset, $length ?? \count($values), $preserveKeys);

        return new self($values);
    }

    public function merge(CollectionInterface|array $collection): CollectionInterface
    {
        $array = $collection instanceof CollectionInterface ? $collection->toArray() : $collection;

        return new self(
            array_merge(
                $this->data,
                $array,
            ),
        );
    }

    public function mapKeys(callable $callback): CollectionInterface
    {
        /** @var array<TKey, TValue> $values */
        $values = [];

        foreach ($this->data as $k => $v) {
            $key = $callback($v, $k);
            $values[$key] = $v;
        }

        return new self($values);
    }

    public function mapValues(callable $callback): CollectionInterface
    {
        /** @var array<TKey, TValue> $values */
        $values = [];

        foreach ($this->data as $k => $v) {
            $value = $callback($v, $k);
            $values[$k] = $value;
        }

        return new self($values);
    }

    public function mapKeyValuePairs(callable $callback): CollectionInterface
    {
        /** @var array<TKey, TValue> $values */
        $values = [];

        foreach ($this->data as $k => $v) {
            /** @var KeyValuePair<TKey, TValue>|null $result */
            $result = $callback($v, $k);

            if (null === $result) {
                continue;
            }

            $values[$result->key] = $result->value;
        }

        return new self($values);
    }

    // endregion
    // region IteratorAggregate

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     * @psalm-return ArrayIterator<TKey, TValue>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    // endregion
    // region ArrayAccess

    /**
     * {@inheritDoc}
     *
     * @param TKey $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->exists($offset);
    }

    /**
     * {@inheritDoc}
     *
     * @param TKey $offset
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * {@inheritDoc}
     *
     * @param TKey $offset
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
     * @param TKey $offset
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

    // endregion
    // region Countable

    public function count(): int
    {
        return \count($this->data);
    }

    // endregion
}
