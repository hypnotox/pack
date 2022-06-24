<?php

declare(strict_types=1);

namespace Tests\Provider;

use Faker\Factory;
use Faker\Generator;
use HypnoTox\Pack\Collection\ArrayCollection;
use HypnoTox\Pack\DTO\KeyValuePair;

class ArrayCollectionProvider
{
    protected readonly Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @return list<array{ArrayCollection, array<array-key, mixed>, array<array-key, mixed>, KeyValuePair<array-key, mixed>, KeyValuePair<array-key, mixed>}>
     */
    public function provide(): array
    {
        $faker = $this->faker;

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
}
