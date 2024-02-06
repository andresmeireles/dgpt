<?php

declare(strict_types=1);

namespace Andre\Dgpt\Controller;

use Andre\Dgpt\Services\ModelInformationInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ModelController
{
    public function __construct(
        private ModelInformationInterface $modelInformation,
        private Twig $twig
    ) {
    }

    public function reload(Response $response): Response
    {
        return $response;
    }

    public function models(Response $response): Response
    {
        $models = $this->modelInformation->loadedModels();

        return $this->twig->render($response, 'loaded_models.twig', ['models' => $models]);
    }

    public function available(Response $response): Response
    {
        $available = $this->modelInformation->availableModels();
        $loaded = $this->modelInformation->loadedModels();

        return $this->twig->render($response, 'available_models.twig', ['available' => $available, 'models' => $loaded]);
    }

    public function add(Request $request, Response $response): Response
    {
        $params = $request->getParsedBody();
        $model = $params['model'] ?? '';

        if (trim($model) === '') {
            $response->getBody()->write('No model provided');

            return $response->withStatus(500);
        }

        $wasAdded = $this->modelInformation->loadModel($model);

        if (!$wasAdded) {
            $response->getBody()->write('Could not add model');

            return $response->withStatus(500);
        }

        return $response->withHeader('Location', '/models')->withStatus(302);
    }
}
