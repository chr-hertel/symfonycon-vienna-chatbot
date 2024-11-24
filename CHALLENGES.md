# SymfonyCon Vienna 2024 - Workshop

The workshop is divided into multiple theory and practice parts.
For every practical part is set up as a separate challenge, and there
are corresponding branches in this repository that you can easily
merge them into your current working branch.

If you get stuck, you can always check the `solution` branch.

## Challenge No. 0: Setup & Chat Command

**Task**: Set up the Symfony application and run it.

* Follow the setup instructions from [README.md](README.md).
* Check if the application is running by opening it in your browser.
* Run `bin/check` to see if everything is green:
  ```
            _ _      _____ _               _            _____                       _
      /\   | | |    / ____| |             | |          |  __ \                     | |
     /  \  | | |   | |    | |__   ___  ___| | _____    | |__) |_ _ ___ ___  ___  __| |
    / /\ \ | | |   | |    | '_ \ / _ \/ __| |/ / __|   |  ___/ _\ / __/ __|/ _ \/ _\ |
   / ____ \| | |   | |____| | | |  __/ (__|   <\__ \   | |  | (_| \__ \__ \  __/ (_| |
  /_/    \_\_|_|    \_____|_| |_|\___|\___|_|\_\___/   |_|   \__,_|___/___/\___|\__,_|
  ```
* Implement a new console command `app:chat` that uses `HttpClient` to interact with the OpenAI's API.
  * You can run the command with:
  ```shell
  docker compose exec app bin/console app:chat
  ```
* The command should ask the user for input and then send it to the GPT model.
  * Use [OpenAI's GPT API](https://platform.openai.com/docs/api-reference/chat) and [Symfony's HttpClient](https://symfony.com/doc/current/http_client.html).
  * Example request:
  ```shell
  curl https://api.openai.com/v1/chat/completions \
    -H "Content-Type: application/json" \
    -H "Authorization: Bearer $OPENAI_API_KEY" \
    -d '{
      "model": "gpt-4o-mini",
      "temperature": 1.0,
      "messages": [
        {"role": "user", "content": "Hello!"}
      ]
    }'
  ```
  * You can find an example API response in `tests/fixtures/gpt-response.json`.
* Make sure the conversation runs in a loop and the messages are kept.

## Challenge No. 1: GPT Client with Chat UI

**Task**: Integrate basic GPT model with your chatbot.

* Merge branch `1-gpt` into your working branch - quality checks will now fail.
  ```shell
  git fetch origin
  git merge origin/1-gpt
  bin/check # will fail
  ```
* Implement the `App\OpenAI\GptClient` implementing the `App\OpenAI\GptClientInterface`
  * Verify the implementation by running the corresponding unit test:
    ```shell
    docker compose exec app vendor/bin/phpunit tests/OpenAI/GptClientTest.php
    ```
* Use the `App\OpenAI\GptClientInterface` in the `App\Chat::submitMessage` implementation.
  * Verify the implementation by running the corresponding functional test:
    ```shell
    docker compose exec app vendor/bin/phpunit tests/Twig/ChatComponentTest.php
    ```
* Run `bin/check` to check all tests and quality tools:
  ```
            _ _      _____ _               _            _____                       _
      /\   | | |    / ____| |             | |          |  __ \                     | |
     /  \  | | |   | |    | |__   ___  ___| | _____    | |__) |_ _ ___ ___  ___  __| |
    / /\ \ | | |   | |    | '_ \ / _ \/ __| |/ / __|   |  ___/ _\ / __/ __|/ _ \/ _\ |
   / ____ \| | |   | |____| | | |  __/ (__|   <\__ \   | |  | (_| \__ \__ \  __/ (_| |
  /_/    \_\_|_|    \_____|_| |_|\___|\___|_|\_\___/   |_|   \__,_|___/___/\___|\__,_|
  ```

## Challenge No. 2: Context

**Task**: Extend the chat's context to bring in more knowledge.

* Merge branch `2-context` into your working branch - quality checks will now fail.
  ```shell
  git fetch origin
  git merge origin/2-context
  bin/check # will fail
  ```
* For extending the chat's context, implement two decorators for `App\OpenAI\GptClientInterface`:
  * `App\OpenAI\ProgramAwareClient`
  * `App\OpenAI\DateTimeAwareClient` 
* For `ProgramAwareClient` use the program of SymfonyCon Vienna stored in `symfonycon-program.txt`
  * Inject the program as a **system prompt**, that tells GPT to use it for answering the user's questions.
  * Additionally think about general information that could be useful for the chatbot, e.g. location or date.
* For `DateTimeAwareClient` inject the current date & time into the context, but make sure it's always up-to-date.
  * Use Symfony's `ClockInterface` to get the current date & time.
  * Format the output so the message reads like:
    ```
    Current date is 2024-07-05 (YYYY-MM-DD) and the time is 14:00:00 (HH:MM:SS).
    ```
* Run `bin/check` to check all tests and quality tools:
  ```
            _ _      _____ _               _            _____                       _
      /\   | | |    / ____| |             | |          |  __ \                     | |
     /  \  | | |   | |    | |__   ___  ___| | _____    | |__) |_ _ ___ ___  ___  __| |
    / /\ \ | | |   | |    | '_ \ / _ \/ __| |/ / __|   |  ___/ _\ / __/ __|/ _ \/ _\ |
   / ____ \| | |   | |____| | | |  __/ (__|   <\__ \   | |  | (_| \__ \__ \  __/ (_| |
  /_/    \_\_|_|    \_____|_| |_|\___|\___|_|\_\___/   |_|   \__,_|___/___/\___|\__,_|
  ```

## Challenge No. 3: Vectors

**Task**: Convert SymfonyCon program into embedding vectors in ChromaDB.

* Merge branch `3-vectors` into your working branch.
  ```shell
  git fetch origin
  git merge origin/3-vectors
  bin/check # will fail
  ```
* Start the ChromaDB container and run the test command.
  ```shell
  docker compose exec app bin/console app:test:chroma
  ```
  Should output ChromaDB version, collection details and end with an error.
* Implement `App\OpenAI\EmbeddingClient::create` method
  * Example request:
    ```shell
    curl https://api.openai.com/v1/embeddings \
      -H "Authorization: Bearer $OPENAI_API_KEY" \
      -H "Content-Type: application/json" \
      -d '{
        "input": "The food was delicious and the waiter...",
        "model": "text-embedding-ada-002",
        "encoding_format": "float"
      }'
    ``` 
  * See [OpenAI's Embeddings API](https://platform.openai.com/docs/api-reference/embeddings) for more information.
  * Afterward, run the test command again.
* Implement `App\Program\Embedder` to complete `app:program:embed` command.
  * The `App\Program\Loader` can take care of loading the program from the website for you.
  * You will receive a `Program` with a list of `Workshop` and a list of `Talk` objects.
  * Both implement the `Session` interface with `toArray()`, `fromArray()` and `toString()` methods, that are helpful for creating the vector and metadata.
  * For interaction with ChromaDB, use the `Codewithkyrian\ChromaDB\Client` class, which is already prepared to be injected.
  * See [documentation](https://github.com/CodeWithKyrian/chromadb-php) for information on how to use the ChromaDB client.
* After successfully running the command `app:program:embed`, run the test command again.
  ```shell
  docker compose exec app bin/console app:program:embed -vv
  docker compose exec app bin/console app:test:chroma
  ```
  After ChromaDB details, you should see a list of similar content:
  ```shell
  // Searching for Symfony content ...
  
   -------------------------------------- -------------------------------------------------- 
    ID                                     Title                                             
   -------------------------------------- -------------------------------------------------- 
    a0d337cd-a86d-493f-852b-f05ead0f2941   Running Symfony in a Multi-Process Container      
    48178fad-8704-4e91-bd18-4213489e84b2   Cutting-Edge Symfony Pipelines with Dagger        
    8f924e48-4e0f-4c87-8b03-e9be85220418   Resurrecting the Dead                             
    0704ac98-ca95-45af-a4cb-8a85e2ad6290   Platforms & Frameworks Eat Culture for Breakfast
   -------------------------------------- -------------------------------------------------- 
  ```
* And of course, also make sure `bin/check` passes all tests and tools:
  ```
            _ _      _____ _               _            _____                       _
      /\   | | |    / ____| |             | |          |  __ \                     | |
     /  \  | | |   | |    | |__   ___  ___| | _____    | |__) |_ _ ___ ___  ___  __| |
    / /\ \ | | |   | |    | '_ \ / _ \/ __| |/ / __|   |  ___/ _\ / __/ __|/ _ \/ _\ |
   / ____ \| | |   | |____| | | |  __/ (__|   <\__ \   | |  | (_| \__ \__ \  __/ (_| |
  /_/    \_\_|_|    \_____|_| |_|\___|\___|_|\_\___/   |_|   \__,_|___/___/\___|\__,_|
  ```

## Challenge No. 4: Retrieval

**Task**: Switch to retrieval augmented generation (RAG) instead of static context.

* Merge branch `4-retrieval` into your working branch.
  ```shell
  git fetch origin
  git merge origin/4-retrieval
  bin/check # will fail
  ```
* Implement `App\OpenAI\RetrievalClient` decorator to replace `ProgramAwareClient`
  * Define system prompt with instructions and general information.
  * Use the `App\OpenAI\EmbeddingClient::create` to get the embedding vectors for the user's message.
  * Use the `Codewithkyrian\ChromaDB\Client::query` to search for similar content in ChromaDB.
    * See `App\Command\ChromaTestCommand` for an example query.
  * Convert the search results into an assistant message
    * See `App\Program\Data\Session::fromArray($metadata)->toString()` for easy conversion.
* Run `bin/check` to check all tests and quality tools:
  ```
            _ _      _____ _               _            _____                       _
      /\   | | |    / ____| |             | |          |  __ \                     | |
     /  \  | | |   | |    | |__   ___  ___| | _____    | |__) |_ _ ___ ___  ___  __| |
    / /\ \ | | |   | |    | '_ \ / _ \/ __| |/ / __|   |  ___/ _\ / __/ __|/ _ \/ _\ |
   / ____ \| | |   | |____| | | |  __/ (__|   <\__ \   | |  | (_| \__ \__ \  __/ (_| |
  /_/    \_\_|_|    \_____|_| |_|\___|\___|_|\_\___/   |_|   \__,_|___/___/\___|\__,_|
  ```

## Challenge No. 5: Tools

**Task**: Shift time and program search to tools.

* Merge branch `5-tools` into your working branch.
  ```shell
  git fetch origin
  git merge origin/5-tools
  bin/check # will fail
  ```
* Use `PhpLlm\LlmChain\ChainInterface` instead of `GptClientInterface` in `App\Chat`
  * Adopt `PhpLlm\LlmChain\Model\Message\Message` and `PhpLlm\LlmChain\Model\Message\MessageBag` instead of `array` for `$messages`
  ```php
  $messages = new MessageBag();
  $messages[] = Message::ofUser('What time is it?');
  $messages[] = Message::ofAssistant($response);
  ```
* Implement `App\Tool\Clock` to provide current date & time
  * Use Symfony's `ClockInterface` to get the current date & time.
  * Use `PhpLlm\LlmChain\Chain\ToolBox\Attribute\AsTool` to expose service as tool.
  ```php
  #[AsTool('clock', 'Provides the current date and time')]
  ```
  * Check the registration via `debug:container` command.
  ```shell
  docker compose exec app bin/console debug:container --tag llm_chain.tool
  ```
* Implement `App\Tool\Retriever` to provide a search for program sessions.
  * You can basically reuse a lot of the code of the `App\OpenAI\RetrievalClient` here.
* Run `bin/check` to check all tests and quality tools:
  ```
            _ _      _____ _               _            _____                       _
      /\   | | |    / ____| |             | |          |  __ \                     | |
     /  \  | | |   | |    | |__   ___  ___| | _____    | |__) |_ _ ___ ___  ___  __| |
    / /\ \ | | |   | |    | '_ \ / _ \/ __| |/ / __|   |  ___/ _\ / __/ __|/ _ \/ _\ |
   / ____ \| | |   | |____| | | |  __/ (__|   <\__ \   | |  | (_| \__ \__ \  __/ (_| |
  /_/    \_\_|_|    \_____|_| |_|\___|\___|_|\_\___/   |_|   \__,_|___/___/\___|\__,_|
  ```

## Challenge No. 6: YouTube

**Task**: Implement a chat on top of a YouTube video transcript.

* Merge branch `6-youtube` into your working branch.
  ```shell
  git fetch origin
  git merge origin/6-youtube
  ```
* You now have a new route, that renders a new chat interface at `/youtube`.
* A Twig component `App\Twig\YouTubeComponent` and `App\YouTube\TranscriptFetcher` are already implemented.
* Finish the implementation by creating `App\YouTube`
  * Implement a `start` method to initialize the chat with a system prompt.
  * Embed the transcript of the YouTube video into the context.
  * Use `App\Chat` implementation as inspiration for session handling.
* In the end `bin/check` should run successful:
  ```
            _ _      _____ _               _            _____                       _
      /\   | | |    / ____| |             | |          |  __ \                     | |
     /  \  | | |   | |    | |__   ___  ___| | _____    | |__) |_ _ ___ ___  ___  __| |
    / /\ \ | | |   | |    | '_ \ / _ \/ __| |/ / __|   |  ___/ _\ / __/ __|/ _ \/ _\ |
   / ____ \| | |   | |____| | | |  __/ (__|   <\__ \   | |  | (_| \__ \__ \  __/ (_| |
  /_/    \_\_|_|    \_____|_| |_|\___|\___|_|\_\___/   |_|   \__,_|___/___/\___|\__,_|
  ```

## Challenge No. 7: Wikipedia

**Task**: Implement a tool chain on top of Wikipedia.

* Merge branch `7-wikipedia` into your working branch.
  ```shell
  git fetch origin
  git merge origin/7-wikipedia
  ```
* You now have a new route, that renders a new chat interface at `/wikipedia`.
* Also `App\Twig\WikipediaComponent` and `App\Wikipedia\Client` are already implemented.
* Implement two tools to equip your bot with the capability to search and read Wikipedia articles.
  * `App\Tool\Wikipedia::search` to search for Wikipedia articles.
  * `App\Tool\Wikipedia::read` to read the content of a Wikipedia article.
* Implement `App\Wikipedia` to bring the bot to life.
  * Integrate it with `App\Twig\WikipediaComponent`, `App\Wikipedia\Client` and `PhpLlm\LlmChain\ChainInterface`. 
* In the end `bin/check` should run successful:
  ```
            _ _      _____ _               _            _____                       _
      /\   | | |    / ____| |             | |          |  __ \                     | |
     /  \  | | |   | |    | |__   ___  ___| | _____    | |__) |_ _ ___ ___  ___  __| |
    / /\ \ | | |   | |    | '_ \ / _ \/ __| |/ / __|   |  ___/ _\ / __/ __|/ _ \/ _\ |
   / ____ \| | |   | |____| | | |  __/ (__|   <\__ \   | |  | (_| \__ \__ \  __/ (_| |
  /_/    \_\_|_|    \_____|_| |_|\___|\___|_|\_\___/   |_|   \__,_|___/___/\___|\__,_|
  ```

## Challenge No. 8: Response Streaming

**Task**: Bring response streaming to the chat command.

* With the option `"stream" => true` as part of the request, you can stream the response from OpenAI.
* This API feature enables Server-sent events with deltas of the response, e.g. single words.
* Use Symfony's `EventSourceHttpClient` in your `ChatCommand` to stream the response.
  * See [Symfony's HttpClient documentation](https://symfony.com/doc/current/http_client.html#consuming-server-sent-events) for more information.

## Challenge No. 9: Streaming Animation

**Task**: Implement a streaming animation for the chat UI.

* Symfony UX supports `Typed.js` which can help to animate the assistant messages.
  * See [example on ux.symfony.com](https://ux.symfony.com/typed)
* Extend the chat UI templates to animate the latest message of the bot.
