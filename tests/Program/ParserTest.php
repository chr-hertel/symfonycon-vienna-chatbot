<?php

declare(strict_types=1);

namespace App\Tests\Program;

use App\Program\Data\Talk;
use App\Program\Data\Workshop;
use App\Program\Parser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Parser::class)]
final class ParserTest extends TestCase
{
    #[DataProvider('provideWorkshopTexts')]
    public function testParseSchedule(string $text, Workshop $expected): void
    {
        $parser = new Parser();
        $actual = $parser->parseWorkshop($text);

        self::assertEquals($expected, $actual);
    }

    public static function provideWorkshopTexts(): \Generator
    {
        $workshop = <<<WORKSHOP
            Workshop: Getting the Most Out of PHPStan
            Trainer: Ondřej Mirtes
            Description:
            PHP is nothing like compiled languages. If you make a mistake, the program will crash when the line of code with the mistake is executed. When testing a PHP application, whether manually or automatically, developers spend a lot of their time discovering mistakes that wouldn’t even compile in other languages, leaving less time for testing actual business logic.
            PHPStan is a popular static analyser focused on finding bugs in your code. By leveraging the strength of PHP type system further enhanced in PHPStan itself, developers can create robust applications and discover bugs early in the development process. The aid the tool provides during refactoring is also indispensable.
            In this workshop, the attendees will learn how to install, configure and run PHPStan, and also receive tips how to write code so that PHPStan can be as powerful as possible. They will also be able to write their own PHPStan rules and extensions.
            Details:
            1-day Workshop (7 hours)
            Delivered in English
            Wednesday, December 4, 2024
            Starts at 9:00AM
            WORKSHOP;

        yield 'phpstan' => [
            $workshop,
            new Workshop(
                'Getting the Most Out of PHPStan',
                'Ondřej Mirtes',
                'PHP is nothing like compiled languages. If you make a mistake, the program will crash when the line of code with the mistake is executed. When testing a PHP application, whether manually or automatically, developers spend a lot of their time discovering mistakes that wouldn’t even compile in other languages, leaving less time for testing actual business logic.'.PHP_EOL.
                'PHPStan is a popular static analyser focused on finding bugs in your code. By leveraging the strength of PHP type system further enhanced in PHPStan itself, developers can create robust applications and discover bugs early in the development process. The aid the tool provides during refactoring is also indispensable.'.PHP_EOL.
                'In this workshop, the attendees will learn how to install, configure and run PHPStan, and also receive tips how to write code so that PHPStan can be as powerful as possible. They will also be able to write their own PHPStan rules and extensions.',
                '1-day Workshop (7 hours)'.PHP_EOL.
                'Delivered in English'.PHP_EOL.
                'Wednesday, December 4, 2024'.PHP_EOL.
                'Starts at 9:00AM',
            ),
        ];

        $workshop = <<<WORKSHOP
            Workshop: Mastering OOP & Design Patterns
            Trainer: Alexandre Salomé
            Description:
            Last seats There are no seats available for this workshop on December 3, 2024.
            Object Oriented Programming (OOP) goes beyond the design of classes and interfaces. It includes a wide variety of concepts such as objects, entities, value objects, services, design models, SOLID principles, calisthenics, coupling, etc. Mastering OOP often requires several years of experience.
            This workshop will help you to better understand all these concepts in order to write more maintainable, robust and testable object-oriented code. You will also discover techniques to reduce the complexity of your code and make your classes more specific and therefore simpler. You will also learn how to recognize and exploit the power of design models (factory, adapter, composite, decorator, mediator, strategy, etc.).
            Details:
            1-day Workshop (7 hours)
            Delivered in English
            Edition #1:
            Tuesday, December 3, 2024
            Starts at 9:00AM
            Edition #2:
            Wednesday, December 4, 2024
            Starts at 9:00AM
            WORKSHOP;

        yield 'oop' => [
            $workshop,
            new Workshop(
                'Mastering OOP & Design Patterns',
                'Alexandre Salomé',
                'Last seats There are no seats available for this workshop on December 3, 2024.'.PHP_EOL.
                'Object Oriented Programming (OOP) goes beyond the design of classes and interfaces. It includes a wide variety of concepts such as objects, entities, value objects, services, design models, SOLID principles, calisthenics, coupling, etc. Mastering OOP often requires several years of experience.'.PHP_EOL.
                'This workshop will help you to better understand all these concepts in order to write more maintainable, robust and testable object-oriented code. You will also discover techniques to reduce the complexity of your code and make your classes more specific and therefore simpler. You will also learn how to recognize and exploit the power of design models (factory, adapter, composite, decorator, mediator, strategy, etc.).',
                '1-day Workshop (7 hours)'.PHP_EOL.
                'Delivered in English'.PHP_EOL.
                'Edition #1:'.PHP_EOL.
                'Tuesday, December 3, 2024'.PHP_EOL.
                'Starts at 9:00AM'.PHP_EOL.
                'Edition #2:'.PHP_EOL.
                'Wednesday, December 4, 2024'.PHP_EOL.
                'Starts at 9:00AM',
            ),
        ];
    }

    #[DataProvider('provideTalkTexts')]
    public function testParseTalk(string $text, Talk $expected): void
    {
        $parser = new Parser();
        $actual = $parser->parseTalk($text);

        self::assertEquals($expected, $actual);
    }

    public static function provideTalkTexts(): \Generator
    {
        $talk = <<<TALK
            Talk: Strict PHP
            Speaker: Alexander M. Turek
            Description:
            Its loose type system and implicit type casts make PHP a perfect programming language for beginners. However, if we rely on those features heavily, we will eventually end up with a codebase that is hard to control and maintain. In this session, Alexander will open up his toolbox and show how a stricter way of programming PHP applications helps him to not lose pace when applying changes to PHP applications.
            Details:
            Delivered in English
            Room: Track Symfony
            Friday, December 6, 2024 at 10:05 AM – 10:40 AM
            Introductory talk, no prior knowledge needed.
            TALK;

        yield 'strict-php' => [
            $talk,
            new Talk(
                'Strict PHP',
                'Alexander M. Turek',
                'Its loose type system and implicit type casts make PHP a perfect programming language for beginners. However, if we rely on those features heavily, we will eventually end up with a codebase that is hard to control and maintain. In this session, Alexander will open up his toolbox and show how a stricter way of programming PHP applications helps him to not lose pace when applying changes to PHP applications.',
                'Track Symfony',
                'English',
                'Friday, December 6, 2024 at 10:05 AM – 10:40 AM',
                'Introductory talk, no prior knowledge needed.',
            ),
        ];

        $talk = <<<TALK
            Talk: Green IT, Accessibility, GDPR: 360 Vision of Sustainability
            Speaker: Vincent Maucorps & Céline DEIS
            Description:
            Join us for an insightful session on more sustainable and ethical tech practices. This conference will cover the essentials of Green IT, showing you how to minimize environmental impact through optimized development processes. You’ll also learn about the importance of accessibility, ensuring that your web applications are inclusive and meet accessibility standards. Additionally, the session will delve into data privacy, providing practical advice on managing data protection in your projects. Gain valuable knowledge and tools to integrate sustainability into your Symfony applications and contribute to a more responsible tech ecosystem.
            Details:
            Delivered in English
            Room: Track Upsun & Smile
            Friday, December 6, 2024 at 14:30 PM – 15:05 PM
            Introductory talk, no prior knowledge needed.
            TALK;

        yield 'green-it' => [
            $talk,
            new Talk(
                'Green IT, Accessibility, GDPR: 360 Vision of Sustainability',
                'Vincent Maucorps & Céline DEIS',
                'Join us for an insightful session on more sustainable and ethical tech practices. This conference will cover the essentials of Green IT, showing you how to minimize environmental impact through optimized development processes. You’ll also learn about the importance of accessibility, ensuring that your web applications are inclusive and meet accessibility standards. Additionally, the session will delve into data privacy, providing practical advice on managing data protection in your projects. Gain valuable knowledge and tools to integrate sustainability into your Symfony applications and contribute to a more responsible tech ecosystem.',
                'Track Upsun & Smile',
                'English',
                'Friday, December 6, 2024 at 14:30 PM – 15:05 PM',
                'Introductory talk, no prior knowledge needed.',
            ),
        ];
    }
}
