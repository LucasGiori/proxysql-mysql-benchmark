<?php

declare(strict_types=1);

namespace App\Application\Actions\Volume;

use App\Application\Actions\Action;
use App\Domain\Volume\VolumeRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class ListVolumeAction extends Action
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
        $volumes = $this->volumeRepository->findAll();

        $this->logger->info("Volumes list was viewed.");

        return $this->respondWithData($volumes);
    }
}
