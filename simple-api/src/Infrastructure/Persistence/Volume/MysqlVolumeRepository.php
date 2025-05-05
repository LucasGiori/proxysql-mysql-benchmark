<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Volume;

use App\Domain\Volume\Volume;
use App\Domain\Volume\VolumeRepository;
use DateTimeImmutable;
use PDO;

class MysqlVolumeRepository implements VolumeRepository
{
    public function __construct(public readonly PDO $pdo)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT id, random_data, created_at FROM volume_test ORDER BY id DESC LIMIT 5");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * {@inheritdoc}
     */
    public function push(Volume $volume): Volume
    {
        $createdAt = $volume->getCreatedAt() ?? new DateTimeImmutable();

        $stmt = $this->pdo->prepare("INSERT INTO volume_test (random_data, created_at) VALUES (:random_data, :created_at);");
        $stmt->bindValue("random_data", $volume->getRandomData(), PDO::PARAM_STR);
        $stmt->bindValue("created_at", $createdAt->format('Y-m-d H:i:s'));
        $stmt->execute();

        $lastId = $this->pdo->lastInsertId();

        return new Volume((int)$lastId, $volume->getRandomData(), $createdAt);
    }
}
