<?php declare(strict_types=1);

namespace Test\Fixture;

use Psr\Http\Message\StreamInterface;

final class NonReadableStream implements StreamInterface
{
    private string $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function __toString(): string
    {
        return $this->data;
    }

    public function close(): void
    {
    }

    public function detach()
    {
    }

    public function getSize(): int
    {
    }

    public function tell(): int
    {
    }

    public function eof(): bool
    {
    }

    public function isSeekable(): bool
    {
        return false;
    }

    public function seek($offset, $whence = SEEK_SET): bool
    {
        return false;
    }

    public function rewind(): void
    {
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function write($string): void
    {
    }

    public function isReadable(): bool
    {
        return false;
    }

    public function read($length): string
    {
        return '';
    }

    public function getContents(): string
    {
        return '';
    }

    public function getMetadata($key = null)
    {
        return $key ? null : [];
    }
}
