<?php

declare(strict_types=1);

namespace App\Program;

use App\OpenAI\EmbeddingClient;
use Codewithkyrian\ChromaDB\Client;
use Symfony\Component\Uid\Uuid;

final class Embedder
{
    public function __construct(
        private readonly Loader $loader,
        private readonly EmbeddingClient $embeddingClient,
        private readonly Client $chromaClient,
    ) {
    }

    public function embedProgram(): void
    {
        $collection = $this->chromaClient->getOrCreateCollection('symfonycon-program');

        $ids = [];
        $embeddings = [];
        $metadatas = [];
        foreach ($this->loader->loadProgram()->getSessions() as $session) {
            $ids[] = Uuid::v4();
            $embeddings[] = $this->embeddingClient->create($session->toString());
            $metadatas[] = $session->toArray();
        }

        $collection->add($ids, $embeddings, $metadatas);
    }
}
