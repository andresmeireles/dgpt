<?php

use Andre\Dgpt\Controller\ChatController;
use Slim\App;

return function (App $app): void {
    $app->get('/', [ChatController::class, 'index']);
};
