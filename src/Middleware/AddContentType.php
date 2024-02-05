<?php

declare(strict_types=1);

namespace Andre\Dgpt\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AddContentType
{
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $newRequest = $request->withoutHeader('Content-Type')->withHeader('Content-Type', 'application/json');

        return $handler->handle($newRequest);
    }
}
