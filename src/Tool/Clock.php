<?php

declare(strict_types=1);

namespace App\Tool;

use PhpLlm\LlmChain\Chain\ToolBox\Attribute\AsTool;
use Symfony\Component\Clock\ClockInterface;

#[AsTool('clock', 'Provides the current date and time')]
final class Clock
{
    public function __construct(
        private readonly ClockInterface $clock,
    ) {
    }

    public function __invoke(): string
    {
        return $this->clock->now()->format('Y-m-d H:i:s');
    }
}
