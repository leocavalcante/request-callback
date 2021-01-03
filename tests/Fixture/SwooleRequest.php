<?php declare(strict_types=1);

namespace Test\Fixture;

use Swoole\Http\Request;

final class SwooleRequest extends Request
{
    private string $data;

    public function __construct(string $data = '')
    {
        $this->data = $data;
        $this->server = [
            'request_uri' => '/',
            'request_method' => 'GET',
        ];
    }

    public function getData(): string
    {
        return $this->data;
    }
}
