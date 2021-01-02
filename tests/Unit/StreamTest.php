<?php declare(strict_types=1);

namespace Test\Unit;

use Swoole\Http\Stream;
use Test\Fixture\SwooleRequest;

it('streams swoole request contents', function (): void {
    $request = new SwooleRequest('Content');
    $stream = new Stream($request, 1);

    expect((string) $stream)->toBe('Content');
    expect($stream->detach())->toBe($request);
    expect($stream->isSeekable())->toBeTrue();
    expect($stream->read(2))->toBe('on');
    expect($stream->seek(1, SEEK_CUR))->toBeTrue();
    expect($stream->tell())->toBe(3);
    expect($stream->eof())->toBeFalse();
    expect($stream->seek(0, SEEK_END))->toBeTrue();
    expect($stream->eof())->toBeTrue();
    expect($stream->seek(0, -1))->toBeFalse();
    expect($stream->isWritable())->toBeFalse();
    expect($stream->write('foo'))->toBeFalse();
    expect($stream->getMetadata())->toBeEmpty();
    expect($stream->getMetadata('foo'))->toBeNull();
});
