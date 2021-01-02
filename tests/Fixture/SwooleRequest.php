<?php declare(strict_types=1);

namespace Test\Fixture;

use Swoole\Http\Request;

final class SwooleRequest extends Request
{
    private string $content;

    public function __construct(string $content = '')
    {
        $this->content = $content;
        $this->server = [
            'request_uri' => '/',
            'request_method' => 'GET',
        ];
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
