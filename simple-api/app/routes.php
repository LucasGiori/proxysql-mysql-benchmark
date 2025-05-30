<?php

declare(strict_types=1);

use App\Application\Actions\Volume\ListVolumeAction;
use App\Application\Actions\Volume\VolumeAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/volumes', function (Group $group) {
        $group->get('/list', ListVolumeAction::class);
        $group->post('/register', VolumeAction::class);
    });
};
