<?php

declare(strict_types=1);

namespace Tests\Service;

use Andre\Dgpt\Services\MercureInterface;
use Andre\Dgpt\Services\OllamaChat;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpClient\Response\StreamableInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface mockInterface extends StreamableInterface, ResponseInterface
{
}

final class OllamaChatTest extends TestCase
{
    private MockHttpClient $httpClientMock;
    private MercureInterface&MockObject $mercureMock;

    private OllamaChat $service;

    protected function setUp(): void
    {
        $this->httpClientMock = new MockHttpClient();
        $this->mercureMock = $this->createMock(MercureInterface::class);

        $this->service = new OllamaChat($this->httpClientMock, $this->mercureMock);
    }

    /**
     * TODO: add tests with multiline responses
     * 
     * @covers \Andre\Dgpt\Services\OllamaChat::ask
     * @return void
     */
    public function testAsk(): void
    {
        // arrange
        $responseMock = new MockResponse('{"response": "ok"}', ['http_code' => 200]);

        $this->httpClientMock->setResponseFactory([$responseMock]);

        $this->mercureMock->expects($this->once())->method('publish');

        // act
        $response = $this->service->ask('What is the meaning of life?', 'davinci');

        // assert
        $this->assertSame('ok', $response);
    }
}
