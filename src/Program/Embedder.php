<?php

declare(strict_types=1);

namespace App\Program;

use App\OpenAI\EmbeddingClient;
use Codewithkyrian\ChromaDB\Client;
use Symfony\Component\Uid\Uuid;

final class Embedder
{
    public function __construct(
        private EmbeddingClient $embeddingClient,
        private Client $chromaDBClient,
        private Loader $loader,
    ) {
    }

    public function embedProgram(): void
    {
        $program = $this->loader->loadProgram();
        $collection = $this->chromaDBClient->getOrCreateCollection('symfonycon-program');

        $ids = [];
        $embeddings = [];
        $metadatas = [];

        foreach ($program->getSessions() as $session) {
            $ids[] = Uuid::v4();
            $metadatas[] = $session->toArray();
            $embeddings[] = $this->embeddingClient->create($session->toString());
        }

        $collection->add($ids, $embeddings, $metadatas);
    }
}
