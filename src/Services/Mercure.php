<?php

declare(strict_types=1);

namespace Andre\Dgpt\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class Mercure implements MercureInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {
    }

    // TODO: improve this function to handler errors
    public function publish(string $message, string $topic): void
    {
        $mercureJwt = $_ENV['MERCURE_JWT'];
        $this->httpClient->request(
            'POST',
            'https://localhost/.well-known/mercure',
            [
                'body' => [
                    'data' => $message,
                    'topic' => $topic
                ],
                'headers' => [
                    'Authorization' => sprintf('Bearer %s', $mercureJwt)
                ]
            ]
        );
    }
}
