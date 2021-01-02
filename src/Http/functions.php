<?php declare(strict_types=1);

namespace Swoole\Http;

use Psr\Http\Server\RequestHandlerInterface;

/**
 * @param RequestHandlerInterface|callable(\Psr\Http\Message\ServerRequestInterface):\Psr\Http\Message\ResponseInterface $handler
 * @param RequestCallbackOptions|null $options
 * @return RequestCallback
 */
function request_callback($handler, ?RequestCallbackOptions $options = null): RequestCallback
{
    if (is_callable($handler)) {
        return RequestCallback::fromCallable($handler, $options);
    }

    return new RequestCallback($handler, $options);
}