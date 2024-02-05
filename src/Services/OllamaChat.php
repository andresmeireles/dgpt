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

    public function ask(string $question, string $model): string
    {
        $phrase = $this->addPunctuation($question);
        $ollamaUrl = sprintf('http://%s:%s', $_ENV['OLLAMA_HOST'], $_ENV['OLLAMA_PORT']);

        try {
            /** @var ResponseInterface&StreamableInterface */
            $response = $this->httpClient->request(
                'POST',
                $ollamaUrl . '/api/generate',
                [
                    'json' => [
                        'prompt' => $phrase,
                        'model' => $model
                    ]
                ]
            );

            if ($response->getStatusCode() !== 200) {
                throw new \RuntimeException('Failed to ask question ' . $response->getContent());
            }

            $stream = $response->toStream();
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

    private function addPunctuation(string $phrase): string
    {
        $lastChar = $phrase[strlen($phrase) - 1];

        return match ($lastChar) {
            '.' => $phrase,
            ',' => $phrase . '.',
            '!' => $phrase,
            '?' => $phrase,
            default => $phrase . '.',
        };
    }

    private function getResponseFromLine(string $line): string
    {
        $textToArray = json_decode($line, true);

        return $textToArray['response'];
    }
}
