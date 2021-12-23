<?php

declare(strict_types=1);

namespace Tests;

use HypnoTox\Pack\ArrayCollection;
use RuntimeException;

class ArrayCollectionTest extends BaseTest
{
    public function testCanConstructAndGetValues(): void
    {
        $collection1 = new ArrayCollection();
        $collection2 = new ArrayCollection([]);
        $collection3 = new ArrayCollection([1, 2, 3]);
        $collection4 = new ArrayCollection(['1', '2', '3']);

        $this->assertSame(\count($collection1->getValues()), 0);
        $this->assertSame(\count($collection2->getValues()), 0);
        $this->assertSame(\count($collection3->getValues()), 3);
        $this->assertSame(\count($collection4->getValues()), 3);
    }

    public function testCanUseAsTraversable(): void
    {
        $array = [1, 2, 3];
        $newArray = [];
        $collection = new ArrayCollection($array);

        $this->assertInstanceOf(\Traversable::class, $collection);

        foreach ($collection as $key => $item) {
            $newArray[$key] = $item;
        }

        $this->assertSame($array, $newArray);
    }

    public function testCanUseWithArrayAccess(): void
    {
        $array = [1, 2, 3];
        $collection = new ArrayCollection($array);

        /** @var ArrayCollection<array-key, int> $newCollection */
        $this->assertInstanceOf(\ArrayAccess::class, $collection);

        $this->assertTrue(isset($collection[0]));
        $this->assertTrue(isset($collection[1]));
        $this->assertTrue(isset($collection[2]));

        $this->assertSame(1, $collection[0]);
        $this->assertSame(2, $collection[1]);
        $this->assertSame(3, $collection[2]);
    }

    public function testArraySetThrows(): void
    {
        $array = [1, 2, 3];
        $collection = new ArrayCollection($array);

        $this->expectException(RuntimeException::class);
        $collection[0] = 10;
    }

    public function testArrayUnsetThrows(): void
    {
        $array = [1, 2, 3];
        $collection = new ArrayCollection($array);

        $this->expectException(RuntimeException::class);
        unset($collection[0]);
    }

    public function testCanCount(): void
    {
        $array = [1, 2, 3];
        $collection = new ArrayCollection($array);

        /** @var ArrayCollection<array-key, int> $newCollection */
        $this->assertInstanceOf(\Countable::class, $collection);
        $this->assertCount(3, $collection);
    }
}
