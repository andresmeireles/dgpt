<?php

declare(strict_types=1);

namespace Andre\Dgpt\Controller;

use Andre\Dgpt\Services\ChatInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class ChatController
{
    public function index(Response $response, Twig $twig): Response
    {
        return $twig->render($response, 'chat/index.twig');
    }

    public function chat(Response $response, ChatInterface $chat): Response
    {
        try {
            $chat->ask('why sky is blue?');
            return $response;
        } catch (\Exception $e) {
            $response->getBody()->write($e->getMessage());
            return $response->withStatus(500);
        }
    }
}
