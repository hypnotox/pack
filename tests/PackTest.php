<?php

namespace Tests;

use HypnoTox\Pack\Pack;

class PackTest extends BaseTest
{
    public function testCanConstructAndGetValues(): void
    {
        $pack1 = new Pack();
        $pack2 = new Pack([]);
        $pack3 = new Pack([1, 2, 3]);
        $pack4 = new Pack(['1', '2', '3']);

        $this->assertSame(\count($pack1->getValues()), 0);
        $this->assertSame(\count($pack2->getValues()), 0);
        $this->assertSame(\count($pack3->getValues()), 3);
        $this->assertSame(\count($pack4->getValues()), 3);
    }
}
