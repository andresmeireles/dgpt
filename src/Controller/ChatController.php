<?php

declare(strict_types=1);

namespace Andre\Dgpt\Controller;

use Andre\Dgpt\Services\ChatInterface;
use Andre\Dgpt\Services\ModelInformationInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ChatController
{
    public function index(Response $response, Twig $twig): Response
    {
        return $twig->render($response, 'chat/index.twig');
    }

    public function responseTemplate(Request $request, Response $response, Twig $twig): Response
    {
        $question = $request->getQueryParams()['question'] ?? '';
        if ($question === '') {
            $response->getBody()->write('Please enter a question.');

            return $response->withStatus(500);
        }

        return $twig->render($response, 'chat/template.twig', ['question' => $question]);
    }

    public function chat(Request $request, Response $response, ChatInterface $chat): Response
    {
        $params = $request->getParsedBody();
        $question = $params['question'] ?? '';
        $model = $params['model'] ?? '';

        if ($model === '') {
            $response->getBody()->write('set a model');
            return $response;
        }

        if ($question === '') {
            $response->getBody()->write('Please enter a question.');
            return $response->withStatus(400);
        }

        try {
            $chat->ask($question, $model);

            return $response;
        } catch (\Exception $e) {
            $response->getBody()->write($e->getMessage());

            return $response->withStatus(500);
        }
    }
}
