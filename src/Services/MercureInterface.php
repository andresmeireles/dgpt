<?php

namespace Andre\Dgpt\Services;

interface MercureInterface
{
    public function publish(string $message, string $topic): void;
}
