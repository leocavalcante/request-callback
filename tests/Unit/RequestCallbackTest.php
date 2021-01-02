<?php declare(strict_types=1);

namespace Test\Unit;

use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoole\Http\RequestCallbackOptions;
use Test\Fixture\NonReadableStream;
use Test\Fixture\SwooleRequest;
use Test\Fixture\TextCallback;
use Test\Fixture\SwooleResponse;
use function Swoole\Http\request_callback;

it('emits regular requests', function(): void {
    $cb = request_callback(new TextCallback('OK'), RequestCallbackOptions::create()->setResponseChunkSize(1));
    $request = new SwooleRequest();
    $response = new SwooleResponse();

    $cb($request, $response);

    expect($response->getBody())->toBe('OK');
});

it('emits non-readable requests', function(): void {
    $cb = request_callback(static function (ServerRequestInterface $request): ResponseInterface {
        return new TextResponse(new NonReadableStream('Foo'));
    });

    $request = new SwooleRequest();
    $response = new SwooleResponse();

    $cb($request, $response);

    expect($response->getBody())->toBe('Foo');
});

it('echos incoming requests', function(): void {
   $cb = request_callback(static function (ServerRequestInterface $request): ResponseInterface {
       return new TextResponse($request->getBody());
   });

    $request = new SwooleRequest('Test');
    $response = new SwooleResponse();

    $cb($request, $response);

    expect($response->getBody())->toBe('Test');
});
