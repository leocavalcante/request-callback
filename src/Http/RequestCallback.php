<?php declare(strict_types=1);

namespace Swoole\Http;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RequestCallback
{
    private RequestHandlerInterface $handler;
    private RequestCallbackOptions $options;

    /**
     * @param callable(ServerRequestInterface):ResponseInterface $callable
     * @param RequestCallbackOptions|null $options
     * @return static
     */
    public static function fromCallable(callable $callable, ?RequestCallbackOptions $options = null): self
    {
        return new self(new CallableRequestHandler($callable) ,$options);
    }

    public function __construct(RequestHandlerInterface $handler, ?RequestCallbackOptions $options = null)
    {
        $this->handler = $handler;
        $this->options = $options ?? new RequestCallbackOptions();
    }

    public function __invoke(Request $request, Response $response): void
    {
        $this->emit($this->handler->handle($this->createServerRequest($request)), $response);
    }

    private function createServerRequest(Request $swooleRequest): ServerRequestInterface
    {
        /** @var array<string, string> $server */
        $server = $swooleRequest->server;

        /** @var array<array> | array<empty> $files */
        $files = $swooleRequest->files ?? [];

        /** @var array<string, string> | array<empty> $headers */
        $headers = $swooleRequest->header ?? [];

        /** @var array<string, string> | array<empty> $cookies */
        $cookies = $swooleRequest->cookie ?? [];

        /** @var array<string, string> | array<empty> $query_params */
        $query_params = $swooleRequest->get ?? [];

        return new ServerRequest(
            $server,
            $files,
            $server['request_uri'] ?? '/',
            $server['request_method'] ?? 'GET',
            $this->options->getStreamFactory()->createStream($swooleRequest->getContent()),
            $headers,
            $cookies,
            $query_params,
        );
    }

    private function emit(ResponseInterface $psrResponse, Response $swooleResponse): void
    {
        $swooleResponse->setStatusCode($psrResponse->getStatusCode(), $psrResponse->getReasonPhrase());

        foreach ($psrResponse->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                $swooleResponse->setHeader($name, $value);
            }
        }

        $body = $psrResponse->getBody();
        $body->rewind();

        if ($body->isReadable()) {
            if ($body->getSize() <= $this->options->getResponseChunkSize()) {
                if ($data = $body->getContents()) {
                    $swooleResponse->write($data);
                }
            } else {
                while (!$body->eof() && ($data = $body->read($this->options->getResponseChunkSize()))) {
                    $swooleResponse->write($data);
                }
            }

            $swooleResponse->end();
        } else {
            $swooleResponse->end((string) $body);
        }

        $body->close();
    }
}
