<?php

namespace HypnoTox\Pack;

use JetBrains\PhpStorm\Pure;

/**
 * @template T
 */
class Pack implements PackInterface
{
    /**
     * @param T[] $values
     */
    public function __construct(
        private array $values = [],
    ) {
    }

    /**
     * @return T[]
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
