<?php

declare(strict_types=1);

namespace App\Program\Data;

interface Session
{
    /**
     * @param array<string, string> $data
     */
    public static function fromArray(array $data): self;

    /**
     * @return array<string, string>
     */
    public function toArray(): array;

    public function toString(): string;
}
