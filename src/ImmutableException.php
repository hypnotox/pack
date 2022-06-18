<?php

declare(strict_types=1);

namespace HypnoTox\Pack;

use Throwable;

class ImmutableException extends \Exception
{
    public function __construct(
        CollectionInterface $collection,
        string $action,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = sprintf(
            'Cannot mutate immutable object of type %s. (Action: %s)',
            $collection::class,
            $action,
        );

        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
