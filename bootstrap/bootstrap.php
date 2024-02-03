<?php

declare(strict_types=1);

set_time_limit(120);
ignore_user_abort(true);

use Andre\Dgpt\Controller\ChatController;
use DI\Bridge\Slim\Bridge;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$diContainer = require __DIR__ . '/di_container.php';

$app = Bridge::create($diContainer());

$app->get('/', [ChatController::class, 'index']);
$app->get('/c', [ChatController::class, 'chat']);

$handler = fn () => $app->run();

// To return to dev when is on worker mode, reload the container.
$mode = $_ENV['MODE'];
if ($mode === "dev") {
    $handler();
    return;
}

// This will only work in worker mode on container and not in dev
$maxRequests = 1;
for ($nbRequests = 0, $running = true; ($nbRequests < $maxRequests) && $running; ++$nbRequests) {
    $running = \frankenphp_handle_request($handler);
    // Call the garbage collector to reduce the chances of it being triggered in the middle of a page generation
    gc_collect_cycles();
}
