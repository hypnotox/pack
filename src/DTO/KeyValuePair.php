<?php

declare(strict_types=1);

namespace HypnoTox\Pack\DTO;

/**
 * @template TKey
 * @template TValue
 * @psalm-immutable
 */
class KeyValuePair
{
    /**
     * @param TKey   $key
     * @param TValue $value
     * @psalm-pure
     */
    public function __construct(
        public readonly mixed $key,
        public readonly mixed $value,
    ) {
    }
}
