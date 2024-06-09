<?php

declare(strict_types=1);

namespace App\Program\Data;

final readonly class Talk implements Session
{
    public function __construct(
        public string $title,
        public string $speaker,
        public string $description,
        public string $room,
        public string $language,
        public string $slot,
        public string $level,
    ) {
    }

    public static function fromArray(array $data): Session
    {
        return new self(
            $data['title'],
            $data['speaker'],
            $data['description'],
            $data['room'],
            $data['language'],
            $data['slot'],
            $data['level'],
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'speaker' => $this->speaker,
            'description' => $this->description,
            'room' => $this->room,
            'language' => $this->language,
            'slot' => $this->slot,
            'level' => $this->level,
        ];
    }

    public function toString(): string
    {
        return <<<TALK
            Talk: {$this->title}
            Speaker: {$this->speaker}
            Description:
            {$this->description}
            Details:
            Delivered in {$this->language}
            Room: {$this->room}
            {$this->slot}
            {$this->level}
            TALK;
    }
}
