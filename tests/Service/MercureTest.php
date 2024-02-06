<?php

declare(strict_types=1);

namespace Tests\Service;

use Andre\Dgpt\Services\Mercure;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @coversDefaultClass \Andre\Dgpt\Services\Mercure
 */
class MercureTest extends TestCase
{
    private HttpClientInterface&MockObject $clientMock;

    private Mercure $service;

    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(HttpClientInterface::class);

        $this->service = new Mercure($this->clientMock);
    }

    /**
     * @covers ::publish
     *
     * @return void
     */
    public function testPublish(): void
    {
        // arrange
        $this->clientMock
            ->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'https://localhost/.well-known/mercure',
                [
                    'body' => [
                        'data' => 'hello',
                        'topic' => 'topic'
                    ],
                    'headers' => [
                        'Authorization' => 'Bearer abc'
                    ]
                ]
            );

        // act
        $this->service->publish('hello', 'topic');
    }
}
