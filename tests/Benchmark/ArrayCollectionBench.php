<?php

declare(strict_types=1);

namespace Tests\Benchmark;

use HypnoTox\Pack\Collection\ArrayCollection;
use HypnoTox\Pack\DTO\KeyValuePair;
use Tests\Provider\ArrayCollectionProvider;

class ArrayCollectionBench
{
    public function benchConstruct(): void
    {
        $provider = new ArrayCollectionProvider();
        $provider->provide();
    }

    public function benchGetter(): void
    {
        $provider = new ArrayCollectionProvider();
        $inputs = $provider->provide();

        foreach ($inputs as $input) {
            /**
             * @var ArrayCollection $collection
             * @psalm-suppress UnnecessaryVarAnnotation
             */
            [$collection] = $input;

            $_keys = $collection->getKeys();
            $_values = $collection->getValues();
            $_array = $collection->toArray();
        }
    }

    public function benchBase(): void
    {
        $provider = new ArrayCollectionProvider();
        $inputs = $provider->provide();

        foreach ($inputs as $input) {
            /**
             * @var ArrayCollection $collection
             * @psalm-suppress UnnecessaryVarAnnotation
             */
            [$collection] = $input;

            /** @var KeyValuePair<array-key, mixed> $first */
            $first = $collection->first();

            /** @var KeyValuePair<array-key, mixed> $last */
            $last = $collection->last();

            $_exists = $collection->exists($first->key);
            $_get = $collection->get($first->key);
            $_set = $collection->set($first->key, $last->value);
            $_unset = $collection->unset($first->key);
            $_first = $collection->first();
            $_last = $collection->last();
            $_findByValue = $collection->findByValue($last->value);
            $_findByCallback = $collection->findByCallback(fn (mixed $value) => $value === $last->value);
        }
    }
}
