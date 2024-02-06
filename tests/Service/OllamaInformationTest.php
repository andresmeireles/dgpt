<?php

declare(strict_types=1);

namespace Tests\Service;

use Andre\Dgpt\Services\OllamaInformation;
use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * @coversDefaultClass \Andre\Dgpt\Services\OllamaInformation
 */
class OllamaInformationTest extends TestCase
{
    private OllamaInformation $service;
    private FilesystemOperator&MockObject $filesystemMock;
    private MockHttpClient $clientMock;

    public function setUp(): void
    {
        $this->clientMock = new MockHttpClient();
        $this->filesystemMock = $this->createMock(FilesystemOperator::class);
        $this->service = new OllamaInformation($this->clientMock, $this->filesystemMock);
    }

    /**
     * @covers ::loadedModels
     *
     * @return void
     */
    public function testLoadedModels(): void
    {
        // arrange
        $responseMock = new MockResponse('{"models": [{"name": "test:latest"}]}', ['http_code' => 200]);

        $this->clientMock->setResponseFactory([$responseMock]);

        // act
        $response = $this->service->loadedModels();

        // assert
        $this->assertIsArray($response);
    }

    /**
     * @covers ::loadModel
     *
     * @return void
     */
    public function testLoadModel(): void
    {
        // arrange
        $this->filesystemMock->expects($this->once())->method('fileExists')->willReturn(true);
        $responseMock = new MockResponse('{"status": "success"}', ['http_code' => 200]);
        $loadModelResponseMock = new MockResponse('{"models": []}', ['http_code' => 200]);

        $this->clientMock->setResponseFactory([$loadModelResponseMock, $responseMock]);

        // act
        $response = $this->service->loadModel('davinci');

        // assert
        $this->isTrue($response);
    }
}
