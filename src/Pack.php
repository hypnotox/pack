<?php

namespace HypnoTox\Pack;

use JetBrains\PhpStorm\Pure;

/**
 * @template T
 *
 * @property T[] $values
 */
class Pack implements PackInterface
{
    /**
     * @param T[] $values
     */
    public function __construct(
        private readonly array $values = [],
    ) {
    }

    #[Pure]
    public function getValues(): array
    {
        return $this->values;
    }
}
