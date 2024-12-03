<?php

declare(strict_types=1);

namespace App\Program;

use App\Program\Data\Talk;
use App\Program\Data\Workshop;

use function Symfony\Component\String\u;

final readonly class Parser
{
    public function parseWorkshop(string $text): Workshop
    {
        $title = u($text)->after('Workshop:')->before('Trainer:')->trim()->toString();
        $trainer = u($text)->after('Trainer:')->before('Description:')->trim()->toString();
        $description = u($text)->after('Description:')->before('Details:')->trim()->toString();
        $details = u($text)->after('Details:')->trim()->toString();

        return new Workshop($title, $trainer, $description, $details);
    }

    public function parseTalk(string $text): Talk
    {
        $title = u($text)->after('Talk:')->before('Speaker:')->trim()->toString();
        $speaker = u($text)->after('Speaker:')->before('Description:')->trim()->toString();
        $description = u($text)->after('Description:')->before('Details:')->trim()->toString();
        $language = u($text)->after('Details:')->before('Room:')->replace('Delivered in ', '')->trim()->toString();
        $room = u($text)->after('Room:')->before(PHP_EOL)->trim()->toString();
        $slot = u($text)->after($room.PHP_EOL)->before(PHP_EOL)->trim()->toString();
        $level = u($text)->after($slot.PHP_EOL)->before(PHP_EOL)->trim()->toString();

        return new Talk($title, $speaker, $description, $room, $language, $slot, $level);
    }
}
