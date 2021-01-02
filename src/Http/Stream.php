<?php declare(strict_types=1);

namespace Swoole\Http;

use Psr\Http\Message\StreamInterface;

final class Stream implements StreamInterface
{
    private Request $request;
    private string $contents;
    private int $size;
    private int $offset;

    public function __construct(Request $request, int $offset = 0)
    {
        $this->request = $request;
        $this->contents = (string) ($request->rawContent() ?? '');
        $this->size = strlen($this->contents);
        $this->offset = $offset;
    }

    public function __toString(): string
    {
        return $this->contents;
    }

    public function close(): bool
    {
        return true;
    }

    public function detach(): Request
    {
        return $this->request;
    }

    public function getSize(): int
    {
        return strlen($this->contents);
    }

    public function tell(): int
    {
        return $this->offset;
    }

    public function eof(): bool
    {
        return $this->offset >= $this->size;
    }

    public function isSeekable(): bool
    {
        return true;
    }

    public function seek($offset, $whence = SEEK_SET): bool
    {
        switch ($whence) {
            case SEEK_SET:
                $this->offset = $offset;
                return true;

            case SEEK_CUR:
                $this->offset += $offset;
                return true;

            case SEEK_END:
                $this->offset = $this->size + $offset;
                return true;
        }

        return false;
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function write($string): bool
    {
        return false;
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function read($length): string
    {
        $chunk = substr($this->contents, $this->offset, $length);
        $this->seek($length);
        return $chunk;
    }

    public function getContents(): string
    {
        return substr($this->contents, $this->offset);
    }

    public function getMetadata($key = null)
    {
        return $key ? null : [];
    }
}