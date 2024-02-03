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

    public function publish(string $message, string $topic): void
    {
        $this->httpClient->request(
            'POST',
            'https://localhost/.well-known/mercure',
            [
                'body' => [
                    'data' => $message,
                    'topic' => $topic
                ],
                'headers' => [
                    'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOlsiKiJdLCJzdWJzY3JpYmUiOlsiKiJdfX0.bVXdlWXwfw9ySx7-iV5OpUSHo34RkjUdVzDLBcc6l_g'
                ]
            ]
        );
    }
}
