<?php

namespace App\Blocks;

use App\Models\MethodBlock;

class ForiBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Start', 'Times'];

    public function getNextFlow(MethodBlock $methodBlock): ?MethodBlock
    {
        $indexConnections = $methodBlock->connectionsOut()
            ->whereRelation('sourcePort', 'name', 'Index')
            ->get();

        for ($i = $this->Start; $i < $this->Times; $i++) {
            $indexConnections->each(function ($indexConnection) use ($i) {
//                dump('setting index');
                $this->service->setConnectionVariable($indexConnection, $i);
            });

            if ($this->service->executeFlow(
                    $methodBlock->connectionsOut()
                        ->whereRelation('targetPort', 'type', 'flow')
                        ->whereRelation('sourcePort', 'name', 'Loop')
                        ->first()?->target,
                ) === -1) {
                break;
            };
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
