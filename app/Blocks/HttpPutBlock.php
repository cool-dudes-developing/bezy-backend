<?php

namespace App\Blocks;

class HttpPutBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['URL'];

    public function run(): array
    {
        return [
            'Result' => $this->post($this->URL, $this->Headers, $this->Data)
        ];
    }

    private function post(string $url, array|null $headers, array|null $data): string
    {
        $res = \Http::withHeaders($headers ?? [])->put($url, $data ?? []);
        return $res->body();
    }
}
