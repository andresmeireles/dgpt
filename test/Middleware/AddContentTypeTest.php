<?php

declare(strict_types=1);

namespace Tests\Middleware;

use Andre\Dgpt\Middleware\AddContentType;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @coversDefaultClass \Andre\Dgpt\Middleware\AddContentType
 */
class AddContentTypeTest extends TestCase
{
    /**
     * @covers ::__invoke
     *
     * @return void
     */
    public function testInvoke()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);

        $request->expects($this->once())
            ->method('withoutHeader')
            ->with('Content-Type')
            ->willReturnSelf();

        $request->expects($this->once())
            ->method('withHeader')
            ->with('Content-Type', 'application/json')
            ->willReturnSelf();

        $handler->expects($this->once())
            ->method('handle')
            ->with($request);

        $middleware = new AddContentType(); // replace with the actual name of your middleware class
        $middleware($request, $handler);
    }
}
