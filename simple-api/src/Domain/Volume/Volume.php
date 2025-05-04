<?php

declare(strict_types=1);

namespace App\Domain\Volume;

use DateTimeImmutable;
use JsonSerializable;

class Volume implements JsonSerializable
{
    private int|null $id = null;

    private string $random_data;

    private DateTimeImmutable|null $createdAt;

    public function __construct(int|null $id = null, string $random_data, DateTimeImmutable|null $createdAt = null)
    {
        $this->id = $id;
        $this->random_data = $random_data;
        $this->createdAt = $createdAt;
    }

    public function getId(): int|null
    {
        return $this->id;
    }
    public function getRandomData(): string
    {
        return $this->random_data;
    }

    public function getCreatedAt(): DateTimeImmutable|null
    {
        return $this->createdAt;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'random_data' => $this->random_data,
            'created_at' => $this->createdAt->format(format: 'd M Y H:i:s')
        ];
    }
}
