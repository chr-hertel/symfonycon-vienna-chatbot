<?php

declare(strict_types=1);

namespace App\OpenAI;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\Filesystem\Filesystem;

#[AsDecorator(GptClientInterface::class, priority: 10)]
final class ProgramAwareClient implements GptClientInterface
{
    public function __construct(
        private GptClientInterface $client,
        private string $programFile,
    ) {
    }

    public function generateResponse(array $messages): string
    {
        $programData = (new Filesystem())->readFile($this->programFile);
        $prompt = <<<PROMPT
            You are a helpful assistant that helps people navigating the SymfonyCon Vienna 2024.
            You can answer questions around the schedule, the speakers, the venue, and more.
            If you can't find the answer to the user's question in the context provided, say so.
            
            {$programData}
            PROMPT;

        array_unshift($messages, ['role' => 'system', 'content' => $prompt]);

        return $this->client->generateResponse($messages);
    }
}
