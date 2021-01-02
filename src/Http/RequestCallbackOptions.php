<?php declare(strict_types=1);

namespace Swoole\Http;

use Laminas\Diactoros\StreamFactory;
use Psr\Http\Message\StreamFactoryInterface;

final class RequestCallbackOptions
{
    private int $responseChunkSize = 2097152; // 2 MB
    private StreamFactoryInterface $streamFactory;

    public static function create(): self
    {
        return new self();
    }

    public function __construct()
    {
        $this->streamFactory = new StreamFactory();
    }

    public function getResponseChunkSize(): int
    {
        return $this->responseChunkSize;
    }

    public function setResponseChunkSize(int $responseChunkSize): self
    {
        $this->responseChunkSize = $responseChunkSize;
        return $this;
    }

    public function getStreamFactory(): StreamFactoryInterface
    {
        return $this->streamFactory;
    }

    public function setStreamFactory(StreamFactoryInterface $streamFactory): self
    {
        $this->streamFactory = $streamFactory;
        return $this;
    }
}
