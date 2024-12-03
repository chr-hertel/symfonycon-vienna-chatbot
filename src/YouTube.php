<?php

declare(strict_types=1);

namespace App;

use App\YouTube\TranscriptFetcher;
use PhpLlm\LlmChain\ChainInterface;
use PhpLlm\LlmChain\Model\Message\Message;
use PhpLlm\LlmChain\Model\Message\MessageBag;
use PhpLlm\LlmChain\Model\Response\TextResponse;
use Symfony\Component\HttpFoundation\RequestStack;

final class YouTube
{
    private const SESSION_KEY = 'youtube-chat';

    public function __construct(
        private RequestStack $requestStack,
        private TranscriptFetcher $transcriptFetcher,
        private ChainInterface $chain,
    ) {
    }

    public function loadMessages(): MessageBag
    {
        return $this->requestStack->getSession()->get(self::SESSION_KEY, new MessageBag());
    }

    public function start(string $videoId): void
    {
        $transcript = $this->transcriptFetcher->fetchTranscript($videoId);
        $systemPrompt = <<<PROMPT
            You are a helpful assistant that answers questions about a YouTube video based on the transcript of it.
            If you can't find the answer, you simply say "I don't know".
            
            This is the transcript of the video:
            {$transcript}
            PROMPT;

        $messages = new MessageBag(
            Message::forSystem($systemPrompt),
            Message::ofAssistant('Hello, what do you want to know about the video?'),
        );

        $this->saveMessages($messages);
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
