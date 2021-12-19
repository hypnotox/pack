<?php

namespace Tests;

use HypnoTox\Pack\Pack;
use PHPUnit\Framework\TestCase;

class PackTest extends TestCase
{
    public function testConstruct(): void
    {
        $pack1 = new Pack();
        $pack2 = new Pack([]);
        $pack3 = new Pack([1, 2, 3]);
        $pack4 = new Pack(['1', '2', '3']);

        $this->assertSame(\count($pack1->getValues()), 0);
        $this->assertSame(\count($pack2->getValues()), 0);
        $this->assertSame(\count($pack3->getValues()), 3);
        $this->assertSame($pack3[1], 2);
        $this->assertSame(\count($pack4->getValues()), 3);
        $this->assertSame($pack4[1], '2');
    }

    public function testGetValues(): void
    {
    }
}
