<?php

declare(strict_types=1);

namespace Andre\Dgpt\Controller;

use Andre\Dgpt\Services\ModelInformationInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ModelController
{
    public function reload(Response $response): Response
    {
        return $response;
    }

    public function add(Request $request, Response $response, ModelInformationInterface $modelInformation): Response
    {
        $params = $request->getParsedBody();
        $model = $params['model'] ?? '';

        if (trim($model) === '') {
            $response->getBody()->write('No model provided');

            return $response->withStatus(500);
        }

        $wasAdded = $modelInformation->loadModel($model);

        if (!$wasAdded) {
            $response->getBody()->write('Could not add model');

            return $response->withStatus(500);
        }

        return $response;
    }
}
