<?php

namespace App\Blocks;

use App\Models\MethodBlock;

class TrycatchBlock extends GenericBlock implements BlockInterface
{
    public function getNextFlow(MethodBlock $methodBlock): ?MethodBlock
    {
        try {
            if ($try = $methodBlock
                ->connectionsOut()
                ->whereRelation('targetPort', 'type', 'flow')
                ->whereRelation('sourcePort', 'name', 'Try')
                ->first()?->target)
                $this->service->executeFlow($try);
        } catch (\Exception $e) {
            $methodBlock->connectionsOut()
                ->whereRelation('sourcePort', 'name', 'Error')
                ->get()
                ->each(function ($connection) use ($e) {
                    $this->service->setConnectionVariable(
                        $connection,
                        $e->getMessage()
                    );
                });

            if ($catch = $methodBlock
                ->connectionsOut()
                ->whereRelation('targetPort', 'type', 'flow')
                ->whereRelation('sourcePort', 'name', 'Catch')
                ->first()?->target)
                $this->service->executeFlow($catch);
        }
        return $methodBlock
            ->connectionsOut()
            ->whereRelation('targetPort', 'type', 'flow')
            ->whereRelation('sourcePort', 'name', 'Finally')
            ->first()?->target;
    }


    public function run(): array
    {
        return [
        ];
    }
}
