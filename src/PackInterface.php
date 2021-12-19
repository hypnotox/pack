<?php

namespace HypnoTox\Pack;

use JetBrains\PhpStorm\Pure;

/**
 * @template T
 */
interface PackInterface
{
    /**
     * @return T[]
     */
    #[Pure]
    public function getValues(): array;
}
