<?php

declare(strict_types=1);

namespace App\OpenAI;

use App\Program\Data\Talk;
use App\Program\Data\Workshop;
use Codewithkyrian\ChromaDB\Client;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator(decorates: GptClientInterface::class, priority: 10)]
final class RetrievalClient implements GptClientInterface
{
    public function __construct(
        private EmbeddingClient $embeddingClient,
        private readonly Client $chromaClient,
        private readonly GptClientInterface $gptClient,
    ) {
    }

    public function generateResponse(array $messages): string
    {
        $prompt = <<<PROMPT
            You are an helpful assistant answering questions in a chat based on the information provided by the assistant
            messages in the conversation. If you can't find the answer, just say so.
            
            You can also answer questions about the program of the SymfonyCon Vienna 2024, which is an in-depth
            conference about the Symfony framework for PHP developers.
            It is the 10th edition of the SymfonyCon and taking place in Vienna, Austria:
            * December 03-04, 2024: 2 days of pre-conference workshops 
            * December 05-06, 2024: 2 days of conference with 3 parallels tracks
            PROMPT;

        array_unshift($messages, ['role' => 'system', 'content' => $prompt]);

        $userMessage = array_pop($messages);

        try {
            $retrievalString = $this->getRetrievalInformation($userMessage['content']);
            $messages[] = ['role' => 'assistant', 'content' => $retrievalString];
        } catch (\Exception) {
        }

        $messages[] = $userMessage;

        return $this->gptClient->generateResponse($messages);
    }

    private function getRetrievalInformation(string $message): string
    {
        $vector = $this->embeddingClient->create($message);
        $collection = $this->chromaClient->getOrCreateCollection('symfonycon-program');
        $queryResponse = $collection->query(
            queryEmbeddings: [$vector],
            nResults: 4,
        );

        if (1 === count($queryResponse->ids, COUNT_RECURSIVE)) {
            throw new \Exception('No results found');
        }

        $retrievalString = 'Additional Information:'.PHP_EOL;
        /* @phpstan-ignore-next-line */
        foreach ($queryResponse->metadatas[0] as $metadata) {
            if (array_key_exists('trainer', $metadata)) {
                /* @phpstan-ignore-next-line */
                $retrievalString .= Workshop::fromArray($metadata)->toString().PHP_EOL;
                continue;
            }
            /* @phpstan-ignore-next-line */
            $retrievalString .= Talk::fromArray($metadata)->toString().PHP_EOL;
        }

        return $retrievalString;
    }
}
