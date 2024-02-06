<?php

declare(strict_types=1);

use Andre\Dgpt\Controller\ChatController;
use Andre\Dgpt\Controller\ModelController;
use Andre\Dgpt\Middleware\AddContentType;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;

/**
 * All routes of this file will be prefixed with /api
 */
return function (App $app): void {
    $app->add(new AddContentType());
    $app->group('/api', function (RouteCollectorProxyInterface $router) {
        $router->post('/chat', [ChatController::class, 'chat']);
        $router->get('/reload', [ModelController::class, 'reload']);
    });
};
