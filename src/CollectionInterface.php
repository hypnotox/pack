<?php

declare(strict_types=1);

namespace HypnoTox\Pack;

use JetBrains\PhpStorm\Pure;

/**
 * @template TKey
 * @template TValue
 */
interface CollectionInterface extends \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * @return iterable<TKey, TValue>
     * @psalm-mutation-free
     */
    #[Pure]
    public function getValues(): iterable;
}
