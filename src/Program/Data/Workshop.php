<?php

declare(strict_types=1);

namespace App\Program\Data;

final readonly class Workshop implements Session
{
    public function __construct(
        public string $title,
        public string $trainer,
        public string $description,
        public string $details,
    ) {
    }

    public static function fromArray(array $data): Session
    {
        return new self(
            $data['title'],
            $data['trainer'],
            $data['description'],
            $data['details'],
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'trainer' => $this->trainer,
            'description' => $this->description,
            'details' => $this->details,
        ];
    }

    public function toString(): string
    {
        return <<<WORKSHOP
            Workshop: {$this->title}
            Trainer: {$this->trainer}
            Description:
            {$this->description}
            Details:
            {$this->details}
            WORKSHOP;
    }
}
