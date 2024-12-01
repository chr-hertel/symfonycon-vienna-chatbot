<?php

declare(strict_types=1);

namespace App\Twig;

use App\Wikipedia;
use PhpLlm\LlmChain\Model\Message\MessageBag;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('wikipedia')]
final class WikipediaComponent
{
    use DefaultActionTrait;

    public function __construct(
        private readonly Wikipedia $chat,
    ) {
    }

    public function getMessages(): MessageBag
    {
        return $this->chat->loadMessages()->withoutSystemMessage();
    }

    #[LiveAction]
    public function submit(#[LiveArg] string $message): void
    {
        $this->chat->submitMessage($message);
    }

    #[LiveAction]
    public function reset(): void
    {
        $this->chat->reset();
    }
}
