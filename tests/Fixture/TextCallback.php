<?php declare(strict_types=1);

namespace Test\Fixture;

use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TextCallback implements RequestHandlerInterface
{
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new TextResponse($this->text);
    }
}