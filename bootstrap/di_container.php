<?php

declare(strict_types=1);

use Andre\Dgpt\Services\ChatInterface;
use Andre\Dgpt\Services\Mercure;
use Andre\Dgpt\Services\MercureInterface;
use Andre\Dgpt\Services\OllamaChat;
use DI\Container;
use Slim\Views\Twig;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

return function (): Container {
    return new Container([
        Twig::class => fn () => Twig::create(__DIR__ . '/../assets/templates'),
        HttpClientInterface::class => fn () => HttpClient::create(),
        MercureInterface::class => fn (Container $c) => $c->get(Mercure::class),
        ChatInterface::class => fn (Container $c) => $c->get(OllamaChat::class),
    ]);
};
