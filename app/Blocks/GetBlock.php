<?php

namespace App\Blocks;

class GetBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Variable'];

    public function run(): array|int
    {
//        dump("I'm getting it");
        return [
            'Value' => $this->service->getVariable(
                $this->methodBlock->connectionsOut()
                    ->whereRelation('sourcePort', 'name', 'Value')
                    ->get(),
                $this->Variable
            )
        ];
    }
}
