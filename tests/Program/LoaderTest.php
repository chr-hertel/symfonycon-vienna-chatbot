<?php

declare(strict_types=1);

namespace App\Tests\Program;

use App\Program\Loader;
use App\Program\Parser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Loader::class)]
final class LoaderTest extends TestCase
{
    public function testLoadProgram(): void
    {
        $loader = new Loader(new Parser(), dirname(__DIR__, 2).'/symfonycon-program.txt');
        $program = $loader->loadProgram();

        self::assertCount(10, $program->workshops);
        self::assertCount(43, $program->talks);
        self::assertCount(53, $program->getSessions());
    }
}
