<?php declare(strict_types=1);

namespace Swoole\Http;

final class RequestCallbackOptions
{
    private int $responseChunkSize = 2097152; // 2 MB

    public static function create(): self
    {
        return new self();
    }

    public function getResponseChunkSize(): int
    {
        return $this->responseChunkSize;
    }

    public function setResponseChunkSize(int $responseChunkSize): RequestCallbackOptions
    {
        $this->responseChunkSize = $responseChunkSize;
        return $this;
    }
}