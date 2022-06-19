<?php

declare(strict_types=1);

namespace Tests;

use HypnoTox\Pack\Collection\ArrayCollection;
use HypnoTox\Pack\DTO\KeyValuePair;
use HypnoTox\Pack\Exception\ImmutableException;

class ArrayCollectionTest extends BaseTest
{
    // region Getters

    /**
     * @param non-empty-array<array-key, mixed> $data
     *
     * @dataProvider collectionProvider
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
     * @param non-empty-array<array-key, mixed> $extraData
     * @param KeyValuePair<array-key, mixed>    $extraDataEntryOne
     * @param KeyValuePair<array-key, mixed>    $extraDataEntryTwo
     *
     * @dataProvider collectionProvider
     */
    public function testCanUseBaseMethods(
        ArrayCollection $collection,
        array $data,
        array $extraData,
        KeyValuePair $extraDataEntryOne,
        KeyValuePair $extraDataEntryTwo
    ): void {
        $count = \count($data);
        $lastKey = array_key_last($data);

        /** @var mixed $lastValue */
        $lastValue = $data[$lastKey];
        $nullValue = \is_object($lastValue) ? new \stdClass() : ((string) $lastValue).'_UNDEFINED';

        $unsetData = $data;
        unset($unsetData[$lastKey]);

        $this->assertTrue($collection->exists($lastKey));
        $this->assertSame(reset($data), $collection->first());
        $this->assertSame($lastValue, $collection->last());
        $this->assertSame($extraDataEntryOne->value, $collection->set($extraDataEntryOne->key, $extraDataEntryOne->value)->get($extraDataEntryOne->key));
        $this->assertSame($extraDataEntryTwo->value, $collection->set($extraDataEntryTwo->key, $extraDataEntryTwo->value)->get($extraDataEntryTwo->key));
        $this->assertSame($count, $collection->count());
        $this->assertSame($count - 1, $collection->unset($lastKey)->count());
        $this->assertSame($unsetData, $collection->unset($lastKey)->toArray());
        $this->assertSame($lastValue, $collection->findByValue($lastValue)?->value);
        $this->assertNull($collection->findByValue($nullValue));
        $this->assertSame($lastValue, $collection->findByCallback(fn ($value): bool => $value === $lastValue)?->value);
        $this->assertNull($collection->findByCallback(fn ($value): bool => $value === $nullValue));
    }

    // endregion
    // region Collection "modification" methods

    /**
     * @param non-empty-array<array-key, mixed> $data
     * @param non-empty-array<array-key, mixed> $extraData
     *
     * @dataProvider collectionProvider
     */
    public function testCanUseSliceAndSpliceMethods(
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
     * @dataProvider collectionProvider
     */
    public function testCanUseMapKeysMethod(
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
     * @dataProvider collectionProvider
     */
    public function testCanUseMapValuesMethod(
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
     * @dataProvider collectionProvider
     */
    public function testCanUseMapKeyValuePairsMethod(
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
     * @dataProvider collectionProvider
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
     * @dataProvider collectionProvider
     */
    public function testCanUseWithArrayAccess(ArrayCollection $collection, array $data): void
    {
        $this->assertInstanceOf(\ArrayAccess::class, $collection);
        $firstKey = array_key_first($data);

        $this->assertTrue(isset($collection[$firstKey]));
        $this->assertSame($data[$firstKey], $collection[$firstKey]);
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testArraySetThrows(ArrayCollection $collection): void
    {
        $this->expectException(ImmutableException::class);
        $collection[$collection->getKeys()[0]] = 10;
    }

    /**
     * @dataProvider collectionProvider
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
     * @dataProvider collectionProvider
     */
    public function testIsCountable(ArrayCollection $collection, array $data): void
    {
        $this->assertInstanceOf(\Countable::class, $collection);
        $this->assertCount(\count($data), $collection);
        $this->assertSame(\count($data), $collection->count());
    }

    // endregion
    // region DataProvider

    /**
     * @return list<array{ArrayCollection, array<array-key, mixed>, array<array-key, mixed>, KeyValuePair<array-key, mixed>, KeyValuePair<array-key, mixed>}>
     */
    private function collectionProvider(): array
    {
        $faker = $this->getFaker();

        $intListData = [$faker->randomDigit(), $faker->randomDigit(), $faker->randomDigit()];
        $intList = new ArrayCollection($intListData);
        $intListExtraEntryOne = new KeyValuePair(3, $faker->randomDigit());
        $intListExtraEntryTwo = new KeyValuePair(4, $faker->randomDigit());
        $intListExtraData = [
            $intListExtraEntryOne->key => $intListExtraEntryOne->value,
            $intListExtraEntryTwo->key => $intListExtraEntryTwo->value,
        ];

        $intListEntry = [
            $intList,
            $intListData,
            $intListExtraData,
            $intListExtraEntryOne,
            $intListExtraEntryTwo,
        ];

        $stringListData = [$faker->word(), $faker->word(), $faker->word()];
        $stringList = new ArrayCollection($stringListData);
        $stringListExtraEntryOne = new KeyValuePair(3, $faker->word());
        $stringListExtraEntryTwo = new KeyValuePair(4, $faker->word());
        $stringListExtraData = [
            $stringListExtraEntryOne->key => $stringListExtraEntryOne->value,
            $stringListExtraEntryTwo->key => $stringListExtraEntryTwo->value,
        ];

        $stringListEntry = [
            $stringList,
            $stringListData,
            $stringListExtraData,
            $stringListExtraEntryOne,
            $stringListExtraEntryTwo,
        ];

        $objectListData = [(object) ['value' => $faker->randomDigit()], (object) ['value' => $faker->randomDigit()], (object) ['value' => $faker->randomDigit()]];
        $objectList = new ArrayCollection($objectListData);
        $objectListExtraEntryOne = new KeyValuePair(3, (object) ['value' => $faker->randomDigit()]);
        $objectListExtraEntryTwo = new KeyValuePair(4, (object) ['value' => $faker->randomDigit()]);
        $objectListExtraData = [
            $objectListExtraEntryOne->key => $objectListExtraEntryOne->value,
            $objectListExtraEntryTwo->key => $objectListExtraEntryTwo->value,
        ];

        $objectListEntry = [
            $objectList,
            $objectListData,
            $objectListExtraData,
            $objectListExtraEntryOne,
            $objectListExtraEntryTwo,
        ];

        $intAssociativeData = [$faker->word() => $faker->randomDigit(), $faker->word() => $faker->randomDigit(), $faker->word() => $faker->randomDigit()];
        $intAssociative = new ArrayCollection($intAssociativeData);
        $intAssociativeExtraEntryOne = new KeyValuePair($faker->word(), $faker->randomDigit());
        $intAssociativeExtraEntryTwo = new KeyValuePair($faker->word(), $faker->randomDigit());
        $intAssociativeExtraData = [
            $intAssociativeExtraEntryOne->key => $intAssociativeExtraEntryOne->value,
            $intAssociativeExtraEntryTwo->key => $intAssociativeExtraEntryTwo->value,
        ];

        $intAssociativeEntry = [
            $intAssociative,
            $intAssociativeData,
            $intAssociativeExtraData,
            $intAssociativeExtraEntryOne,
            $intAssociativeExtraEntryTwo,
        ];

        $stringAssociativeData = [$faker->word() => $faker->word(), $faker->word() => $faker->word(), $faker->word() => $faker->word()];
        $stringAssociative = new ArrayCollection($stringAssociativeData);
        $stringAssociativeExtraEntryOne = new KeyValuePair($faker->word(), $faker->word());
        $stringAssociativeExtraEntryTwo = new KeyValuePair($faker->word(), $faker->word());
        $stringAssociativeExtraData = [
            $stringAssociativeExtraEntryOne->key => $stringAssociativeExtraEntryOne->value,
            $stringAssociativeExtraEntryTwo->key => $stringAssociativeExtraEntryTwo->value,
        ];

        $stringAssociativeEntry = [
            $stringAssociative,
            $stringAssociativeData,
            $stringAssociativeExtraData,
            $stringAssociativeExtraEntryOne,
            $stringAssociativeExtraEntryTwo,
        ];

        $objectAssociativeData = [
            $faker->word() => (object) ['value' => $faker->randomDigit()],
            $faker->word() => (object) ['value' => $faker->randomDigit()],
            $faker->word() => (object) ['value' => $faker->randomDigit()],
        ];
        $objectAssociative = new ArrayCollection($objectAssociativeData);
        $objectAssociativeExtraEntryOne = new KeyValuePair($faker->word(), (object) ['value' => $faker->randomDigit()]);
        $objectAssociativeExtraEntryTwo = new KeyValuePair($faker->word(), (object) ['value' => $faker->randomDigit()]);
        $objectAssociativeExtraData = [
            $objectAssociativeExtraEntryOne->key => $objectAssociativeExtraEntryOne->value,
            $objectAssociativeExtraEntryTwo->key => $objectAssociativeExtraEntryTwo->value,
        ];

        $objectAssociativeEntry = [
            $objectAssociative,
            $objectAssociativeData,
            $objectAssociativeExtraData,
            $objectAssociativeExtraEntryOne,
            $objectAssociativeExtraEntryTwo,
        ];

        return [
            $intListEntry,
            $stringListEntry,
            $objectListEntry,
            $intAssociativeEntry,
            $stringAssociativeEntry,
            $objectAssociativeEntry,
        ];
    }

    // endregion
}
