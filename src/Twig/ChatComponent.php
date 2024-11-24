<?php

declare(strict_types=1);

namespace App\Twig;

use App\Chat;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('chat')]
final class ChatComponent
{
    use DefaultActionTrait;

    public function __construct(
        private readonly Chat $chat,
    ) {
    }

    /**
     * @phpstan-return MessageList
     */
    public function getMessages(): array
    {
        return array_values(
            array_filter($this->chat->loadMessages(), fn ($message) => 'system' !== $message['role'])
        );
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
