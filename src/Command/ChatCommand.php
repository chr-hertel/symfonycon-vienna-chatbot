<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand('app:chat', description: 'Chat with GPT')]
final class ChatCommand extends Command
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $openAiApiKey,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Chat with GPT');

        $messages = [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
        ];

        $userMessage = $io->ask('You');

        do {
            $messages[] = ['role' => 'user', 'content' => $userMessage];
            $response = $this->httpClient->request('POST', 'https://api.openai.com/v1/chat/completions', [
                'auth_bearer' => $this->openAiApiKey,
                'json' => [
                    'model' => 'gpt-4o',
                    'messages' => $messages,
                ],
            ]);
            $data = $response->toArray();

            $assistant = $data['choices'][0]['message']['content'];
            $messages[] = ['role' => 'assistant', 'content' => $assistant];

            $io->writeln(' <comment>Bot</comment>:');
            $io->writeln(' > '.$assistant);
        } while ($userMessage = $io->ask('You'));

        return 0;
    }
}