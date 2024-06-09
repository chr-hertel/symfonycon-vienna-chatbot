<?php

declare(strict_types=1);

namespace App\Program;

use App\Program\Data\Program;
use Symfony\Component\Filesystem\Filesystem;

use function Symfony\Component\String\u;

final readonly class Loader
{
    public function __construct(
        private Parser $parser,
        private string $programFile,
    ) {
    }

    public function loadProgram(): Program
    {
        $programData = (new Filesystem())->readFile($this->programFile);

        $workshops = array_map(
            fn (\Stringable $text) => $this->parser->parseWorkshop((string) $text),
            u($programData)->after('Workshops'.PHP_EOL.'=========')->before('Talks')->trim()->split('---'),
        );

        $talks = array_map(
            fn (\Stringable $text) => $this->parser->parseTalk((string) $text),
            u($programData)->after('Talks'.PHP_EOL.'====')->before('Hackday')->trim()->split('---'),
        );

        return new Program($workshops, $talks);
    }
}
