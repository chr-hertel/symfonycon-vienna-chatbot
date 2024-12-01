<?php

declare(strict_types=1);

namespace App\Program\Data;

final readonly class Program
{
    /**
     * @param Workshop[] $workshops
     * @param Talk[]     $talks
     */
    public function __construct(
        public array $workshops,
        public array $talks,
    ) {
    }

    /**
     * @return Session[]
     */
    public function getSessions(): array
    {
        return array_merge($this->workshops, $this->talks);
    }
}
