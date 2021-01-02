<?php declare(strict_types=1);

namespace Swoole\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CallableRequestHandler implements RequestHandlerInterface
{
    /**
     * @var callable(ServerRequestInterface):ResponseInterface
     */
    private $callable;

    /**
     * @param callable(ServerRequestInterface):ResponseInterface $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return ($this->callable)($request);
    }
}