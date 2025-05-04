<?php

declare(strict_types=1);

use App\Application\Actions\Volume\VolumeAction;
use App\Application\Actions\Volume\ListVolumeAction;
use App\Application\Settings\SettingsInterface;
use App\Domain\Volume\VolumeRepository;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ServerRequestFactory;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        ListVolumeAction::class => function (ContainerInterface $container) {
            return new ListVolumeAction(
                logger: $container->get(LoggerInterface::class),
                volumeRepository: $container->get(VolumeRepository::class),
            );
        },
        
        VolumeAction::class => function (ContainerInterface $container) {
            return new VolumeAction(
                logger: $container->get(LoggerInterface::class),
                volumeRepository: $container->get(VolumeRepository::class),
            );
        },
        ServerRequestInterface::class => function () {
            return (new ServerRequestFactory())->createFromGlobals();
        },
    ]);
};
