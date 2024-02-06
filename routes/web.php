<?php

use Andre\Dgpt\Controller\ChatController;
use Andre\Dgpt\Controller\ModelController;
use Slim\App;

return function (App $app): void {
    $app->get('/', [ChatController::class, 'index']);
    $app->get('/conversation', [ChatController::class, 'responseTemplate']);

    $app->get('/models', [ModelController::class, 'models']);
    $app->get('/available', [ModelController::class, 'available']);
    $app->post('/add', [ModelController::class, 'add']);
};
