<?php

declare(strict_types=1);

use Andre\Dgpt\Services\ChatInterface;
use Andre\Dgpt\Services\Mercure;
use Andre\Dgpt\Services\MercureInterface;
use Andre\Dgpt\Services\ModelInformationInterface;
use Andre\Dgpt\Services\OllamaChat;
use Andre\Dgpt\Services\OllamaInformation;
use DI\Container;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Slim\Views\Twig;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

return function (): Container {
    return new Container([
        Twig::class => fn () => Twig::create(__DIR__ . '/../assets/templates'),
        HttpClientInterface::class => fn () => HttpClient::create(),
        MercureInterface::class => fn (Container $c) => $c->get(Mercure::class),
        ModelInformationInterface::class => fn (Container $c) => $c->get(OllamaInformation::class),
        ChatInterface::class => fn (Container $c) => $c->get(OllamaChat::class),
        FilesystemOperator::class => static function (): Filesystem {
            $adapter = new LocalFilesystemAdapter(__DIR__ . '/../..');
            return new Filesystem($adapter);
        }
    ]);
};
