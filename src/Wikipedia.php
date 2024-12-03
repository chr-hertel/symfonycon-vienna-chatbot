<?php

declare(strict_types=1);

namespace App;

use PhpLlm\LlmChain\ChainInterface;
use PhpLlm\LlmChain\Model\Message\Message;
use PhpLlm\LlmChain\Model\Message\MessageBag;
use PhpLlm\LlmChain\Model\Response\TextResponse;
use Symfony\Component\HttpFoundation\RequestStack;

final class Wikipedia
{
    private const SESSION_KEY = 'wikipedia-bot';

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ChainInterface $chain,
    ) {
    }

    public function loadMessages(): MessageBag
    {
        return $this->requestStack->getSession()->get(self::SESSION_KEY, $this->initMessageBag());
    }

    public function submitMessage(string $message): void
    {
        $messages = $this->loadMessages();
        $messages[] = Message::ofUser($message);

        $response = $this->chain->call($messages);

        assert($response instanceof TextResponse);

        $messages[] = Message::ofAssistant($response->getContent());

        $this->saveMessages($messages);
    }

    public function reset(): void
    {
        $this->requestStack->getSession()->remove(self::SESSION_KEY);
    }

    private function saveMessages(MessageBag $messages): void
    {
        $this->requestStack->getSession()->set(self::SESSION_KEY, $messages);
    }

    private function initMessageBag(): MessageBag
    {
        $systemPrompt = <<<PROMPT
            You are a chat bot that does research on Wikipedia by using the tools provided.
            You can search for relevant articles by using the "wikipedia_search" and
            afterwards use the "wikipedia_article" to load the entire article by its title.
            
            Please provide the link to the page as addition to your response.
            PROMPT;


        return new MessageBag(
            Message::forSystem($systemPrompt),
        );
    }
}
