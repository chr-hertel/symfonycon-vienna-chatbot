<?php

declare(strict_types=1);

namespace App\OpenAI;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GptClient implements GptClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $openAiApiKey,
    ) {
    }

    public function generateResponse(array $messages): string
    {
        $body = [
            'model' => 'gpt-4o',
            'temperature' => 1.0,
            'messages' => $messages,
        ];

        $response = $this->httpClient->request('POST', 'https://api.openai.com/v1/chat/completions', [
            'headers' => ['Content-Type' => 'application/json'],
            'auth_bearer' => $this->openAiApiKey,
            'json' => $body,
        ]);

        $data = $response->toArray();

        return $data['choices'][0]['message']['content'];
    }
}
