<?php

declare(strict_types=1);

namespace Tests;

use HypnoTox\Pack\ArrayCollection;
use JetBrains\PhpStorm\Pure;

/**
 * @extends BaseCollectionTest<array-key, int>
 */
class ArrayCollectionTest extends BaseCollectionTest
{
    #[Pure]
    protected function getCollectionClassString(): string
    {
        return ArrayCollection::class;
    }

    #[Pure]
     protected function getTestValues(): array
     {
         return [1, 2, 3];
     }
}
