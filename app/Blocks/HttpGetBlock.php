<?php

namespace App\Blocks;

class HttpGetBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['URL'];

    public function run(): array
    {
        return [
            'Result' => $this->get($this->URL, $this->Headers)
        ];
    }

    private function get(string $url, array|null $headers): string
    {
        $res = \Http::withHeaders($headers ?? [])->get($url);
        return $res->body();
    }
}
