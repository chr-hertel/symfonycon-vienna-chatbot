<?php

declare(strict_types=1);

namespace App\Command;

use App\Issue;
use PhpLlm\LlmChain\ChainInterface;
use PhpLlm\LlmChain\Model\Message\Message;
use PhpLlm\LlmChain\Model\Message\MessageBag;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:structured-output', description: 'Structured output command')]
final class StructuredOutputCommand extends Command
{

    public function __construct(
        private ChainInterface $chain,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $systemPrompt = <<<PROMPT
            You are a helpful assistant that is tasked to convert a user message into an structured issue.
            PROMPT;

        $userMessage = <<<PROMPT
            I am having trouble with my computer. I need you to reset the firewall or the router. I don't know.
            Everytime i'm googling nothing happens. I need help.
            PROMPT;

        $messages = new MessageBag(
            Message::forSystem($systemPrompt),
            Message::ofUser($userMessage),
        );

        $response = $this->chain->call($messages, ['output_structure' => Issue::class]);

        dump($response->getContent());

        return Command::SUCCESS;
    }
}
