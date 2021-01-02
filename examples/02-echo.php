<?php declare(strict_types=1);

namespace App;

use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoole\Http\RequestCallback;
use Swoole\Http\Server;

require_once __DIR__ . '/../vendor/autoload.php';

$server = new Server('0.0.0.0', 9501);

$server->on('request', RequestCallback::fromCallable(static function (ServerRequestInterface $request): ResponseInterface {
    return new TextResponse($request->getBody());
}));

$server->start();