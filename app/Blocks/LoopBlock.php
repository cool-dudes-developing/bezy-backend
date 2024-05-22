<?php

namespace App\Blocks;

use App\Models\MethodBlock;

class LoopBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Items'];

    public function getNextFlow(MethodBlock $methodBlock): ?MethodBlock
    {
        $indexConnections = $methodBlock->connectionsOut()
            ->whereRelation('sourcePort', 'name', 'Index')
            ->get();
        $itemConnections = $methodBlock->connectionsOut()
            ->whereRelation('sourcePort', 'name', 'Item')
            ->get();
        $i = 0;
        foreach ($this->Items as $item) {
            $indexConnections->each(function ($indexConnection) use ($i) {
                $this->service->setConnectionVariable($indexConnection, $i);
            });
            $itemConnections->each(function ($itemConnection) use ($item) {
                $this->service->setConnectionVariable($itemConnection, $item);
            });

            if ($this->service->executeFlow(
                    $methodBlock->connectionsOut()
                        ->whereRelation('targetPort', 'type', 'flow')
                        ->whereRelation('sourcePort', 'name', 'Loop')
                        ->first()?->target,
                ) === -1) {
                break;
            };
            $i++;
        }
//        dump('End Loop');
        return $methodBlock
            ->connectionsOut()
            ->whereRelation('targetPort', 'type', 'flow')
            ->whereRelation('sourcePort', 'name', 'Out')
            ->first()?->target;
    }


    public function run(): array
    {
        return [
        ];
    }
}
