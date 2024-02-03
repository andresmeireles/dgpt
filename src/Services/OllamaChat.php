<?php

declare(strict_types=1);

namespace Andre\Dgpt\Services;

use Symfony\Component\HttpClient\Response\StreamableInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class OllamaChat implements ChatInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly MercureInterface $mercure
    ) {
    }

    public function ask(string $question): string
    {
        try {
            /** @var ResponseInterface&StreamableInterface */
            // $response = $this->httpClient->request(
            //     'POST',
            //     'http://ollama:11434/api/generate',
            //     [
            //         'json' => [
            //             'prompt' => $question,
            //             'model' => 'phi2'
            //         ]
            //     ]
            // );

            // if ($response->getStatusCode() !== 200) {
            //     throw new \RuntimeException('Failed to ask question ' . $response->getContent());
            // }

            // $stream = $response->toStream();
            $stream = fopen(__DIR__ . '/../../test.txt', 'r');
            while (feof($stream) === false) {
                $line = fgets($stream);
                if ($line === false) {
                    continue;
                }
                // while (($line = fgets($stream)) === false) {
                $response = $this->getResponseFromLine($line);
                $this->mercure->publish($response, topic: self::CHAT_TOPIC);
            }

            fclose($stream);

            return 'ok';
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    private function getResponseFromLine(string $line): string
    {
        $textToArray = json_decode($line, true);

        return $textToArray['response'];
    }
}
