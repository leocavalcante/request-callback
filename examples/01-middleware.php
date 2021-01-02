<?php declare(strict_types=1);

namespace App;

require_once __DIR__ . '/../vendor/autoload.php';

use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Stratigility\MiddlewarePipe;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Swoole\Http\Server;
use function Laminas\Stratigility\middleware;
use function Swoole\Http\request_callback;

$app = new MiddlewarePipe();

$app->pipe(middleware(static function (ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface {
    $user = $request->getQueryParams()['user'] ?? null;

    if ($user === null) {
        return new JsonResponse(['error' => true, 'message' => 'Unauthorized'], 401);
    }

    return $next
        ->handle($request->withAttribute('user', $user))
        ->withAddedHeader('X-Foo', 'Bar');
}));

$app->pipe(middleware(static function (ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface {
    return new JsonResponse(['message' => sprintf('Hello, %s!', $request->getAttribute('user'))]);
}));

$server = new Server('0.0.0.0', 9501);
$server->on('request', request_callback($app));
$server->start();