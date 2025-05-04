<?php

declare(strict_types=1);

namespace App\Domain\Volume;

interface VolumeRepository
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    public function push(Volume $volume): Volume;
}
