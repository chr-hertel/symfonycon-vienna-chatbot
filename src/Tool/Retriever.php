<?php

declare(strict_types=1);

namespace App\Tool;

use App\OpenAI\EmbeddingClient;
use App\Program\Data\Talk;
use App\Program\Data\Workshop;
use Codewithkyrian\ChromaDB\Client;
use PhpLlm\LlmChain\Chain\ToolBox\Attribute\AsTool;

#[AsTool('program_search', 'Searches in program of SymfonyCon Vienna')]
final class Retriever
{
    public function __construct(
        private readonly EmbeddingClient $embeddingClient,
        private readonly Client $chromaClient,
    ) {
    }

    /**
     * @param string $search Text used for similarity search in the program
     */
    public function __invoke(string $search): string
    {
        $vector = $this->embeddingClient->create($search);
        $collection = $this->chromaClient->getOrCreateCollection('symfonycon-program');
        $queryResponse = $collection->query(
            queryEmbeddings: [$vector],
            nResults: 4,
        );

        if (1 === count($queryResponse->ids, COUNT_RECURSIVE)) {
            return 'No results found';
        }

        $result = 'Found following sessions in the program of SymfonyCon Vienna:'.PHP_EOL;
        /* @phpstan-ignore-next-line */
        foreach ($queryResponse->metadatas[0] as $metadata) {
            if (array_key_exists('trainer', $metadata)) {
                /* @phpstan-ignore-next-line */
                $result .= Workshop::fromArray($metadata)->toString().PHP_EOL;
                continue;
            }
            /* @phpstan-ignore-next-line */
            $result .= Talk::fromArray($metadata)->toString().PHP_EOL;
        }

        return $result;
    }
}
