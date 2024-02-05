<?php

declare(strict_types=1);

namespace Andre\Dgpt\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OllamaInformation implements ModelInformationInterface
{
    private readonly string $ollamaLLMPath;
    private readonly string $ollamaUrl;

    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {
        $this->ollamaLLMPath = __DIR__ . '/../..' . $_ENV['OLLAMA_LLM_PATH'];
        $this->ollamaUrl = sprintf('http://%s:%s', $_ENV['OLLAMA_HOST'], $_ENV['OLLAMA_PORT']);
    }

    public function loadedModels(): array
    {
        $response = $this->httpClient->request(
            'GET',
            $this->ollamaUrl . '/api/tags'
        );

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Failed to get models ' . $response->getContent());
        }

        $models = json_decode($response->getContent(), true);

        return array_map(
            static fn (array $model) => substr($model['name'], 0, strpos($model['name'], ':')),
            $models['models']
        );
    }

    public function loadModel(string $model): bool
    {
        // $content = file_get_contents($this->ollamaLLMPath . '/' . $model);
        try {
            $request = $this->httpClient->request(
                'POST',
                sprintf('%s/api/create', $this->ollamaUrl),
                [
                    'json' => [
                        'name' => $model,
                        'stream' => false,
                        // 'modelfile' => $content
                        'path' => $this->ollamaLLMPath . '/' . $model
                    ]
                ]
            );

            if ($request->getStatusCode() !== 200) {
                throw new \RuntimeException('Failed to load model ' . $request->getContent());
            }

            $response = json_decode($request->getContent(), true);

            return $response['status'] === 'success';
        } catch (\Throwable $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function availableModels(): array
    {
        $files = scandir($this->ollamaLLMPath);
        return array_filter(
            $files,
            fn (string $file) => !(($file === '.' || $file === '..') || str_contains($file, '.gguf') || $file === ".DS_Store")
        );
    }

    public function reloadListOfModels(): bool
    {
        $modelFiles = $this->availableModels();

        foreach ($modelFiles as $modelFile) {
            $added = $this->loadModel($modelFile);
            if (!$added) {
                return false;
            }
        }

        return true;
    }
}
