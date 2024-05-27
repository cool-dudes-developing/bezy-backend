<?php

namespace App\Blocks;

class HttpDeleteBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['URL'];

    public function run(): array
    {
        return [
            'Result' => $this->post($this->URL, $this->Headers)
        ];
    }

    private function post(string $url, array|null $headers): string
    {
        $res = \Http::withHeaders($headers ?? [])->delete($url);
        return $res->body();
    }
}
