<?php

declare(strict_types=1);

namespace App\OpenAI;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final class EmbeddingClient
{
    public function __construct(
        private HttpClientInterface $client,
        private string $openAiApiKey,
    ) {
    }

    /**
     * @return float[]
     */
    public function create(string $input): array
    {
        $response = $this->client->request('POST', 'https://api.openai.com/v1/embeddings', [
            'headers' => ['Content-Type' => 'application/json'],
            'auth_bearer' => $this->openAiApiKey,
            'json' => [
                'input' => $input,
                'model' => 'text-embedding-ada-002',
                'encoding_format' => 'float',
            ],
        ]);

        $data = $response->toArray();

        return $data['data'][0]['embedding'];
    }
}
