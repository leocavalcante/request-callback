# Request Callback

![CI](https://github.com/leocavalcante/request-callback/workflows/CI/badge.svg)
[![codecov](https://codecov.io/gh/leocavalcante/request-callback/branch/main/graph/badge.svg?token=TE1YQYNKHJ)](https://codecov.io/gh/leocavalcante/request-callback)
[![psalm](https://shepherd.dev/github/leocavalcante/request-callback/coverage.svg)](https://shepherd.dev/github/leocavalcante/request-callback)

➰ Swoole request callback for PSR complaint handlers.

## Install

```bash
composer require leocavalcante/request-callback
```

## Usage

Simply create a callback that decorates a `RequestHandlerInterface` and pass as a listener to the `onRequest` event of the `Swoole\Http\Server`.

```php
use Swoole\Http\RequestCallback;

$callback = new RequestCallback(\Psr\Http\Server\RequestHandlerInterface);
```

### Example

#### Hello, World!

```php
use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Swoole\Http\{RequestCallback, Server};

$server = new Server('localhost', 9501);

$server->on('request', new RequestCallback(new class implements RequestHandlerInterface {
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new TextResponse(sprintf('Hello, %s!', $request->getQueryParams()['name'] ?? 'World'));
    }
}));

$server->start();
```

To help you with the boilerplate, you can use the `RequestCallback::fromCallable(callable)` factory method:

```php
$server->on('request', RequestCallback::fromCallable(static function (ServerRequestInterface $request): ResponseInterface {
    return new TextResponse(sprintf('Hello, %s!', $request->getQueryParams()['name'] ?? 'World'));
}));
```

If you are like me and want even more sugar, use the `request_callback(RequestHandlerInterface|callable)` function:

```php
$server->on('request', request_callback(
    static fn(ServerRequestInterface $request): ResponseInterface =>
        new TextResponse(sprintf('Hello, %s!', $request->getQueryParams()['name'] ?? 'World')))
);

```

You can pass either a `RequestHandlerInterface` or a `callable`, it will figure out.

#### Middleware

```php
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Stratigility\MiddlewarePipe;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Swoole\Http\{RequestCallback, Server};
use function Laminas\Stratigility\middleware;

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
$server->on('request', new RequestCallback($app));
$server->start();
```
