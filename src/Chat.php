<?php

declare(strict_types=1);

namespace App;

use PhpLlm\LlmChain\ChainInterface;
use PhpLlm\LlmChain\Model\Message\Message;
use PhpLlm\LlmChain\Model\Message\MessageBag;
use PhpLlm\LlmChain\Model\Response\TextResponse;
use Symfony\Component\HttpFoundation\RequestStack;

final class Chat
{
    private const SESSION_KEY = 'chat-messages';

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ChainInterface $chain,
    ) {
    }

    public function loadMessages(): MessageBag
    {
        return $this->requestStack->getSession()->get(self::SESSION_KEY, new MessageBag());
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
}
