<?php

declare(strict_types=1);

namespace App\Tool;

use App\Wikipedia\Client;
use PhpLlm\LlmChain\Chain\ToolBox\Attribute\AsTool;

#[AsTool('wikipedia_search', 'Search on Wikipedia for relevant articles', 'search')]
#[AsTool('wikipedia_article', 'Load a Wikipedia article', 'article')]
final class Wikipedia
{
    public function __construct(
        private Client $client,
    ) {
    }

    /**
     * @param string $query Query string for the search
     */
    public function search(string $query): string
    {
        $results = $this->client->search($query);

        $titles = array_map(fn ($result) => $result['title'], $results['query']['search']);

        return 'Found the following articles: '.implode(', ', $titles);
    }

    /**
     * @param string $title Title of the article to load
     */
    public function article(string $title): string
    {
        $content = $this->client->getArticle($title);

        return current($content['query']['pages'])['extract'] ?? '';
    }
}
