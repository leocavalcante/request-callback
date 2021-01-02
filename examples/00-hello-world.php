<?php declare(strict_types=1);

namespace App;

use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Swoole\Http\{RequestCallback, RequestCallbackOptions, Server};
use function Swoole\Http\request_callback;

require_once __DIR__ . '/../vendor/autoload.php';

$server = new Server('localhost', 9501);

//$server->on('request', new RequestCallback(new class implements RequestHandlerInterface {
//    public function handle(ServerRequestInterface $request): ResponseInterface
//    {
//        return new TextResponse(sprintf('Hello, %s!', $request->getQueryParams()['name'] ?? 'World'));
//    }
//}));

//$server->on('request', RequestCallback::fromCallable(static function (ServerRequestInterface $request): ResponseInterface {
//    return new TextResponse(sprintf('Hello, %s!', $request->getQueryParams()['name'] ?? 'World'));
//}));

$server->on('request', request_callback(static function (ServerRequestInterface $request): ResponseInterface {
    return new TextResponse(sprintf('Hello, %s!', $request->getQueryParams()['name'] ?? 'World'));
}));

$server->start();
