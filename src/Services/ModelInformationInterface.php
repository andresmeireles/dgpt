<?php

namespace Andre\Dgpt\Services;

interface ModelInformationInterface
{
    /**
     * List of available models names
     *
     * @return string[]
     */
    public function availableModels(): array;

    /**
     * List of loaded models
     *
     * @return string[]
     */
    public function loadedModels(): array;

    public function reloadListOfModels(): bool;

    public function loadModel(string $model): bool;
}
