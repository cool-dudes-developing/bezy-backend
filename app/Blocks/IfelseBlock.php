<?php

namespace App\Blocks;

use App\Models\MethodBlock;

class IfelseBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Condition'];

    public function getNextFlow(MethodBlock $methodBlock): ?MethodBlock
    {
        return $methodBlock
            ->connectionsOut()
            ->whereRelation('targetPort', 'type', 'flow')
            ->whereRelation('sourcePort', 'name', $this->Condition ? 'True' : 'False')
            ->first()?->target;
    }


    public function run(): array
    {
//        dump($this->Condition);
        return [
            'Result' => $this->Condition ? 'True' : 'False'
        ];
    }
}
