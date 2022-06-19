<?php

declare(strict_types=1);

namespace Tests;

use HypnoTox\Pack\ArrayCollection;
use HypnoTox\Pack\ImmutableException;

class ArrayCollectionCollectionTest extends BaseCollectionTest
{
    // region Base

    /**
     * @return ArrayCollection<int, int>
     */
    protected function getTestCollection(): ArrayCollection
    {
        return new ArrayCollection([1, 2, 3]);
    }

    // endregion
    // region Getters

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

    // endregion
    // region Base methods

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

    // endregion
    // region Collection "modification" methods

    public function testCanUseModificationMethods(): void
    {
        $collection = $this->getTestCollection();

        $this->assertEquals([1 => 2, 2 => 3], $collection->slice(1, null, true)->getValues());
        $this->assertEquals([1, 2], $collection->slice(0, 2)->getValues());
        $this->assertEquals([2, 3], $collection->splice(0, 1)->getValues());
        $this->assertEquals([1, 2, 2], $collection->splice(2, 1, [2])->getValues());
        $this->assertEquals([2, 4, 6], $collection->map(fn (int $value): int => $value * 2)->getValues());
    }

    // endregion
    // region IteratorAggregate

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

    // endregion
    // region ArrayAccess

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

        $this->expectException(ImmutableException::class);
        $collection[0] = 10;
    }

    public function testArrayUnsetThrows(): void
    {
        $collection = $this->getTestCollection();

        $this->expectException(ImmutableException::class);
        unset($collection[0]);
    }

    // endregion
    // region Countable

    public function testIsCountable(): void
    {
        $collection = $this->getTestCollection();

        /** @var ArrayCollection<array-key, int> $newCollection */
        $this->assertInstanceOf(\Countable::class, $collection);
        $this->assertCount(3, $collection);
        $this->assertSame(3, $collection->count());
    }

    // endregion
}
