<?php

declare(strict_types=1);

namespace App\OpenAI;

use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator(GptClientInterface::class)]
final class DateTimeAwareClient implements GptClientInterface
{
    public function __construct(
        private GptClientInterface $client,
        private ClockInterface $clock,
    ) {
    }

    public function generateResponse(array $messages): string
    {
        $time = sprintf(
            'Current date is %s (YYYY-MM-DD) and the time is %s (HH:MM:SS).',
            $this->clock->now()->format('Y-m-d'),
            $this->clock->now()->format('H:i:s')
        );

        array_unshift($messages, ['role' => 'assistant', 'content' => $time]);

        return $this->client->generateResponse($messages);
    }
}
