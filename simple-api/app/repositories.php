<?php

declare(strict_types=1);

use App\Domain\Volume\VolumeRepository;
use App\Infrastructure\Persistence\Volume\MysqlVolumeRepository;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        VolumeRepository::class => function (ContainerInterface $c): MysqlVolumeRepository {
            $request = $c->get(Request::class);
            $useProxySQL = $request->getHeaderLine('X-Use-ProxySQL') === 'true';

            if ($useProxySQL) {
                $dsn = "mysql:host=proxysql;port=6033;dbname=poc;charset=utf8mb4";
            } else {
                $dsn = "mysql:host=mysql1;port=3306;dbname=poc;charset=utf8mb4";
            }

            $pdo = new PDO($dsn, 'poc', 'poc', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            return new MysqlVolumeRepository($pdo);
        },
    ]);
};
