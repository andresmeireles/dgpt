<?php

declare(strict_types=1);

namespace Andre\Dgpt\Services;

interface ChatInterface
{
    public const CHAT_TOPIC = 'chat';

    public function ask(string $question, string $model): string;
}
