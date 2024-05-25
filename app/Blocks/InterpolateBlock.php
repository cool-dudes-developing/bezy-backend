<?php

namespace App\Blocks;

class InterpolateBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String', 'Variables'];

    public function run(): array
    {
        $string = $this->String;
        $variables = is_string($this->Variables) ? json_decode($this->Variables, true) : $this->Variables;

        foreach ($variables as $key => $value) {
            $string = str_replace('{' . $key . '}', $value, $string);
        }

        return ['Result' => $string];
    }
}
