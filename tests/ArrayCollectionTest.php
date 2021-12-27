<?php

declare(strict_types=1);

namespace Tests;

use HypnoTox\Pack\ArrayCollection;
use JetBrains\PhpStorm\Pure;
use RuntimeException;

class ArrayCollectionTest extends BaseTest
{
    /**
     * @return ArrayCollection<int, int>
     */
    #[Pure]
    private function getTestCollection(): ArrayCollection
    {
        return new ArrayCollection([1, 2, 3]);
    }

    /*
     * Getters
     */

    public function testCanConstructAndGetValues(): void
    {
        $collection1 = new ArrayCollection();
        $collection2 = new ArrayCollection([]);
        $collection3 = new ArrayCollection([1, 2, 3]);
        $collection4 = new ArrayCollection(['1', '2', '3']);
        $collection5 = new ArrayCollection(['one' => 1, 'two' => 2, 'three' => 3]);

        $this->assertSame(\count($collection1->getValues()), 0);
        $this->assertSame(\count($collection2->getValues()), 0);
        $this->assertSame(\count($collection3->getValues()), 3);
        $this->assertSame(\count($collection4->getValues()), 3);
        $this->assertSame(\count($collection5->getValues()), 3);
    }

    /*
     * Base methods
     */

    public function testCanUseBaseMethods(): void
    {
        $collection = $this->getTestCollection();

        $this->assertTrue($collection->exists(2));

        $this->assertSame(1, $collection->get(0));
        $this->assertSame(2, $collection->get(1));
        $this->assertSame(3, $collection->get(2));

        $this->assertSame(4, $collection->set(3, 4)->get(3));
        $this->assertSame(3, $collection->count());
        $this->assertSame(2, $collection->unset(2)->count());
    }

    /*
     * Collection "modification" methods
     */

    /*
     * IteratorAggregate
     */

    public function testCanUseAsTraversable(): void
    {
        $collection = $this->getTestCollection();
        $newArray = [];

        $this->assertInstanceOf(\Traversable::class, $collection);

        foreach ($collection as $key => $item) {
            $newArray[$key] = $item;
        }

        $this->assertSame([1, 2, 3], $newArray);
    }

    /*
     * Array Access
     */

    public function testCanUseWithArrayAccess(): void
    {
        $collection = $this->getTestCollection();

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
        $collection = $this->getTestCollection();

        $this->expectException(RuntimeException::class);
        $collection[0] = 10;
    }

    public function testArrayUnsetThrows(): void
    {
        $collection = $this->getTestCollection();

        $this->expectException(RuntimeException::class);
        unset($collection[0]);
    }

    /*
    * Countable
    */

    public function testIsCountable(): void
    {
        $collection = $this->getTestCollection();

        /** @var ArrayCollection<array-key, int> $newCollection */
        $this->assertInstanceOf(\Countable::class, $collection);
        $this->assertCount(3, $collection);
    }
}
