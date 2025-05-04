<?php

declare(strict_types=1);

namespace App\Application\Actions\Volume;

use App\Application\Actions\Action;
use App\Domain\Volume\Volume;
use App\Domain\Volume\VolumeRepository;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class VolumeAction extends Action
{
    protected VolumeRepository $volumeRepository;

    public function __construct(LoggerInterface $logger, VolumeRepository $volumeRepository)
    {
        parent::__construct($logger);
        $this->volumeRepository = $volumeRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): ResponseInterface
    {
        $data = $this->request->getParsedBody() ?? [];

        $volume = new Volume(id: null,random_data: $data['random_data'], createdAt: new DateTimeImmutable());

        $volume = $this->volumeRepository->push($volume);

        $this->logger->info("Volumes was pushed.");

        return $this->respondWithData($volume->jsonSerialize() ?? []);
    }
}
