<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

abstract class BaseCollectionTest extends TestCase
{
    protected $backupStaticAttributes = [];
    protected $runTestInSeparateProcess = false;
}
