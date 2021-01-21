<?php declare(strict_types=1);

namespace Test\Fixture;

use Swoole\Http\Response;

final class SwooleResponse extends Response
{
    private int $statusCode = 200;
    private string $reasonPhrase = 'OK';
    private array $headers = [];
    private string $body = '';

    public function setStatusCode($http_code, $reason = null)
    {
        $this->statusCode = $http_code;
        $this->reasonPhrase = $reason;
    }

    public function setHeader($key, $value, $ucwords = null)
    {
        $this->headers[$key] = $value;
    }

    public function write($content)
    {
        $this->body .= $content;
    }

    public function end($content = null)
    {
        if ($content !== null) {
            $this->body = $content;
        }
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
