<?php

declare(strict_types=1);

namespace Tests\Unit;

use HypnoTox\Pack\Collection\ArrayCollection;
use HypnoTox\Pack\DTO\KeyValuePair;
use HypnoTox\Pack\Exception\ImmutableException;

class ArrayCollectionTest extends BaseTest
{
    // region Getters

    /**
     * @param non-empty-array<array-key, mixed> $data
     *
     * @dataProvider \Tests\Provider\ArrayCollectionProvider::provide()
     */
    public function testCanConstructAndGetData(ArrayCollection $collection, array $data): void
    {
        $this->assertCount(\count($data), $collection->toArray());
        $this->assertCount(\count($data), $collection->getKeys());
        $this->assertCount(\count($data), $collection->getValues());
    }

    // endregion
    // region Base methods

    /**
     * @param non-empty-array<array-key, mixed> $data
     *
     * @dataProvider \Tests\Provider\ArrayCollectionProvider::provide()
     */
    public function testCanUseExists(
        ArrayCollection $collection,
        array $data,
    ): void {
        $lastKey = array_key_last($data);

        $this->assertTrue($collection->exists($lastKey));
    }

    /**
     * @param non-empty-array<array-key, mixed> $data
     *
     * @dataProvider \Tests\Provider\ArrayCollectionProvider::provide()
     */
    public function testCanUseFirstAndLast(
        ArrayCollection $collection,
        array $data,
    ): void {
        $lastKey = array_key_last($data);

        /** @var mixed $lastValue */
        $lastValue = $data[$lastKey];

        $this->assertSame(reset($data), $collection->first()?->value);
        $this->assertSame($lastValue, $collection->last()?->value);
    }

    /**
     * @param non-empty-array<array-key, mixed> $data
     * @param non-empty-array<array-key, mixed> $extraData
     * @param KeyValuePair<array-key, mixed>    $extraDataEntryOne
     * @param KeyValuePair<array-key, mixed>    $extraDataEntryTwo
     *
     * @dataProvider \Tests\Provider\ArrayCollectionProvider::provide()
     */
    public function testCanUseSetAndGet(
        ArrayCollection $collection,
        array $data,
        array $extraData,
        KeyValuePair $extraDataEntryOne,
        KeyValuePair $extraDataEntryTwo,
    ): void {
        $lastKey = array_key_last($data);
        $unsetData = $data;
        unset($unsetData[$lastKey]);

        $this->assertSame($extraDataEntryOne->value, $collection->set($extraDataEntryOne->key, $extraDataEntryOne->value)->get($extraDataEntryOne->key));
        $this->assertSame($extraDataEntryTwo->value, $collection->set($extraDataEntryTwo->key, $extraDataEntryTwo->value)->get($extraDataEntryTwo->key));
    }

    /**
     * @param non-empty-array<array-key, mixed> $data
     *
     * @dataProvider \Tests\Provider\ArrayCollectionProvider::provide()
     */
    public function testCanUseUnset(
        ArrayCollection $collection,
        array $data,
    ): void {
        $count = \count($data);
        $lastKey = array_key_last($data);

        $unsetData = $data;
        unset($unsetData[$lastKey]);

        $this->assertSame($count - 1, $collection->unset($lastKey)->count());
        $this->assertSame($unsetData, $collection->unset($lastKey)->toArray());
    }

    /**
     * @param non-empty-array<array-key, mixed> $data
     *
     * @dataProvider \Tests\Provider\ArrayCollectionProvider::provide()
     */
    public function testCanUseFindByValue(
        ArrayCollection $collection,
        array $data,
    ): void {
        $lastKey = array_key_last($data);

        /** @var mixed $lastValue */
        $lastValue = $data[$lastKey];
        $nullValue = \is_object($lastValue) ? new \stdClass() : ((string) $lastValue).'_UNDEFINED';

        $this->assertSame($lastValue, $collection->findByValue($lastValue)?->value);
        $this->assertNull($collection->findByValue($nullValue));
    }

    /**
     * @param non-empty-array<array-key, mixed> $data
     *
     * @dataProvider \Tests\Provider\ArrayCollectionProvider::provide()
     */
    public function testCanUseFindByCallback(
        ArrayCollection $collection,
        array $data,
    ): void {
        $lastKey = array_key_last($data);

        /** @var mixed $lastValue */
        $lastValue = $data[$lastKey];
        $nullValue = \is_object($lastValue) ? new \stdClass() : ((string) $lastValue).'_UNDEFINED';

        $this->assertSame($lastValue, $collection->findByCallback(fn ($value): bool => $value === $lastValue)?->value);
        $this->assertNull($collection->findByCallback(fn ($value): bool => $value === $nullValue));
    }

    /**
     * @param non-empty-array<array-key, mixed> $data
     * @param non-empty-array<array-key, mixed> $extraData
     *
     * @dataProvider \Tests\Provider\ArrayCollectionProvider::provide()
     */
    public function testCanUseSliceAndSplice(
        ArrayCollection $collection,
        array $data,
        array $extraData,
    ): void {
        $this->assertEquals(array_merge($data, $extraData), $collection->merge($extraData)->toArray());
        $this->assertEquals(\array_slice($data, 1, null, true), $collection->slice(1, null, true)->toArray());
        $this->assertEquals(\array_slice($data, 0, 2), $collection->slice(0, 2)->toArray());

        $spliceOne = $data;
        array_splice($spliceOne, 0, 1);
        $this->assertEquals($spliceOne, $collection->splice(0, 1)->toArray());

        $spliceTwo = $data;
        array_splice($spliceTwo, 2, 1, $extraData);
        $this->assertEquals($spliceTwo, $collection->splice(2, 1, $extraData)->toArray());
    }

    /**
     * @param non-empty-array<array-key, mixed> $data
     *
     * @dataProvider \Tests\Provider\ArrayCollectionProvider::provide()
     */
    public function testCanUseMapKeys(
        ArrayCollection $collection,
        array $data,
    ): void {
        $callback = static function (mixed $_value, int|string $key): int {
            if (\is_string($key)) {
                return \strlen($key);
            }

            return $key;
        };

        $mappedArray = [];

        /** @var mixed $v */
        foreach ($data as $k => $v) {
            $key = $callback($v, $k);
            /** @var mixed */
            $mappedArray[$key] = $v;
        }

        $this->assertEquals($mappedArray, $collection->mapKeys($callback)->toArray());
    }

    /**
     * @param non-empty-array<array-key, mixed> $data
     *
     * @dataProvider \Tests\Provider\ArrayCollectionProvider::provide()
     */
    public function testCanUseMapValues(
        ArrayCollection $collection,
        array $data,
    ): void {
        $callback = static function (mixed $_value, int|string $key): int {
            if (\is_string($key)) {
                return \strlen($key);
            }

            return $key;
        };

        $mappedArray = [];

        /** @var mixed $v */
        foreach ($data as $k => $v) {
            $value = $callback($v, $k);
            /** @var mixed */
            $mappedArray[$k] = $value;
        }

        $this->assertEquals($mappedArray, $collection->mapValues($callback)->toArray());
    }

    /**
     * @param non-empty-array<array-key, mixed> $data
     *
     * @dataProvider \Tests\Provider\ArrayCollectionProvider::provide()
     */
    public function testCanUseMapKeyValuePairs(
        ArrayCollection $collection,
        array $data,
    ): void {
        $callback = static function (mixed $_value, int|string $key): KeyValuePair {
            if (\is_string($key)) {
                $key = \strlen($key);
            }

            return new KeyValuePair($key * 2, $key * 2);
        };

        $mappedArray = [];

        /** @var mixed $v */
        foreach ($data as $k => $v) {
            $result = $callback($v, $k);

            /** @var mixed */
            $mappedArray[$result->key] = $result->value;
        }

        $this->assertEquals($mappedArray, $collection->mapKeyValuePairs($callback)->toArray());
    }

    // endregion
    // region IteratorAggregate

    /**
     * @param non-empty-array<array-key, mixed> $data
     *
     * @dataProvider \Tests\Provider\ArrayCollectionProvider::provide()
     */
    public function testCanUseAsTraversable(ArrayCollection $collection, array $data): void
    {
        $newArray = [];
        $this->assertInstanceOf(\Traversable::class, $collection);

        /** @var mixed $item */
        foreach ($collection as $key => $item) {
            /** @var mixed */
            $newArray[$key] = $item;
        }

        $this->assertSame($data, $newArray);
    }

    // endregion
    // region ArrayAccess

    /**
     * @param non-empty-array<array-key, mixed> $data
     *
     * @dataProvider \Tests\Provider\ArrayCollectionProvider::provide()
     */
    public function testCanUseWithArrayAccess(ArrayCollection $collection, array $data): void
    {
        $this->assertInstanceOf(\ArrayAccess::class, $collection);
        $firstKey = array_key_first($data);

        $this->assertTrue(isset($collection[$firstKey]));
        $this->assertSame($data[$firstKey], $collection[$firstKey]);
    }

    /**
     * @dataProvider \Tests\Provider\ArrayCollectionProvider::provide()
     */
    public function testArraySetThrows(ArrayCollection $collection): void
    {
        $this->expectException(ImmutableException::class);
        $collection[$collection->getKeys()[0]] = 10;
    }

    /**
     * @dataProvider \Tests\Provider\ArrayCollectionProvider::provide()
     */
    public function testArrayUnsetThrows(ArrayCollection $collection): void
    {
        $this->expectException(ImmutableException::class);
        unset($collection[$collection->getKeys()[0]]);
    }

    // endregion
    // region Countable

    /**
     * @param non-empty-array<array-key, mixed> $data
     *
     * @dataProvider \Tests\Provider\ArrayCollectionProvider::provide()
     */
    public function testIsCountable(ArrayCollection $collection, array $data): void
    {
        $this->assertInstanceOf(\Countable::class, $collection);
        $this->assertCount(\count($data), $collection);
        $this->assertSame(\count($data), $collection->count());
    }

    // endregion
}
