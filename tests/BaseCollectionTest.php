<?php

declare(strict_types=1);

namespace Tests;

use HypnoTox\Pack\ArrayCollection;
use HypnoTox\Pack\CollectionInterface;

/**
 * @template TKey
 * @template TValue
 *
 * TODO: Refactor tests and think about a better structure for code reuse that makes sense for these tests.
 */
abstract class BaseCollectionTest extends BaseTest
{
    /**
     * @return class-string<CollectionInterface>
     */
    abstract protected function getCollectionClassString(): string;

    abstract protected function getTestValues(): mixed;

    public function testCanConstructAndGetValues(): void
    {
        $class = $this->getCollectionClassString();

        $collection1 = new $class();
        $collection2 = new $class($this->getTestValues());

        $this->assertIsIterable($collection1->getValues());
        $this->assertIsIterable($collection2->getValues());
    }

    public function testCanUseAsTraversable(): void
    {
        $class = $this->getCollectionClassString();
        $values = $this->getTestValues();

        /**
         * @var CollectionInterface $collection
         */
        $collection = new $class($values);

        $this->assertInstanceOf(\Traversable::class, $collection);

        foreach ($collection as $key => $item) {
            $this->assertSame($collection->get($key), $item);
        }
    }

    public function testCanUseWithArrayAccess(): void
    {
        $class = $this->getCollectionClassString();
        $values = $this->getTestValues();
        $collection = new $class($values);

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
        $class = $this->getCollectionClassString();
        $values = $this->getTestValues();
        /** @noinspection PhpObjectFieldsAreOnlyWrittenInspection */
        $collection = new $class($values);

        $this->expectException(\RuntimeException::class);
        $collection[0] = 10;
    }

    public function testArrayUnsetThrows(): void
    {
        $class = $this->getCollectionClassString();
        $values = $this->getTestValues();
        $collection = new $class($values);

        $this->expectException(\RuntimeException::class);
        unset($collection[0]);
    }

    public function testCanCount(): void
    {
        $class = $this->getCollectionClassString();
        $values = $this->getTestValues();
        $collection = new $class($values);

        /** @var ArrayCollection<array-key, int> $newCollection */
        $this->assertInstanceOf(\Countable::class, $collection);
        $this->assertCount(3, $collection);
    }
}
