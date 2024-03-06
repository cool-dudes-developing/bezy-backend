<?php

namespace App\Services;

use App\Blocks\GenericBlock;
use App\Models\Block;
use App\Models\Connection;
use App\Models\MethodBlock;
use App\Models\Project;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class MethodService
{

    private array $cache = [];
    private array $stack = [];

    private Collection $flows;

    private function buildFlowTree(MethodBlock $methodBlock, Collection $flows, Collection $params): array
    {
        if ($methodBlock->block->pure) {
            return $this->buildMethodBlockFlowTree($methodBlock, $flows, $params);
        }
        return $this->buildBlockFlowTree($methodBlock->block);
    }

    /**
     * @throws Exception
     */
    private function buildBlockFlowTree(Block $block): array
    {
        // get all connections in this block where the source is a flow.
        // Since flow can only be connected to flow, we can assume
        // that the target port is also a flow.
        $flows = $block->connections()->whereRelation('sourcePort', 'type', 'flow')->get();

        // get all connections in this block where the source is not a flow.
        $params = $block->connections()->whereRelation('sourcePort', 'type', '!=', 'flow')->get();

        // if there are no flows, we can stop here.
        if ($flows->isEmpty()) {
            throw new Exception('No flows found in block ' . $block->name);
        }
        /** @var Connection $startFlow */
        $startFlow = $flows->firstWhere('from_port_id', '=', $block->ports()->where('direction', 0)->firstWhere('type', '=', 'flow')->id);
        return [
            'block_id' => $block->id,
            'block' => $block,
            'pure' => false,
            'next' => $this->buildFlowTree($startFlow->target, $flows, $params)
        ];
    }

    private function buildMethodBlockFlowTree(MethodBlock $methodBlock, Collection $flows, Collection $params): array
    {
        $paramsOut = $params->where('from_method_block_id', '=', $methodBlock->id);
        $paramsIn = $params->where('to_method_block_id', '=', $methodBlock->id);
        return [
            'block_id' => $methodBlock->block_id,
            'method_block' => $methodBlock,
            'pure' => $methodBlock->block->pure,
            'params_in' => $paramsIn,
            'params_out' => $paramsOut,
            'children' => $flows->where('from_method_block_id', '=', $methodBlock->id)
                ->keyBy('targetPort.name')
                ->map(
                    fn(Connection $flow) => $this->buildFlowTree($flow->target, $flows, $params)
                )->toArray()
        ];
    }

    public function debug(Block $block, array $data): JsonResponse
    {
        $result = $this->execute($block, $data);

        return response()->json([
            'result' => $result,
            'cache' => $this->cache,
            'stack' => array_values($this->stack)
        ]);
    }

    public function execute(Block $block, array $data)
    {
        // setup cache
        $block->connections()->whereRelation('sourcePort', 'type', '!=', 'flow')->whereRelation('sourcePort', 'direction', '0')->get()->each(
            fn(Connection $connection) => $this->cache[$connection->id] = $data[$connection->sourcePort->name] ?? null
        );

//        dump($this->cache);

        return $this->executeBlock($block);
    }

    private function executeBlock(Block $block)
    {
        // get all connections in this block where the source is a flow.
        // Since flow can only be connected to flow, we can assume
        // that the target port is also a flow.
        $startBlock = $block->methodBlocks()->whereRelation('block', 'type', 'start')->first();

        /** @var Connection $startFlow */
        $startFlow = $block->connections()->whereRelation('sourcePort', 'type', 'flow')->where('from_method_block_id', '=', $startBlock->id)->first();

        return $this->executeFlow($startFlow->target);
    }

    public function executeFlow(MethodBlock $methodBlock, $doNotPropagate = false): array
    {
        if ($methodBlock->block->type === 'start') {
            throw new Exception('Missing required parameter');
        }
        if ($methodBlock->block->type === 'end') {
//            dump('end block reached');
            return $this->gatherParameters($methodBlock->parent->connections()->whereRelation('sourcePort', 'type', '!=', 'flow')->whereRelation('sourcePort', 'direction', '1')->get());
        }
        // if this is a pure block, we can execute it right away
        if ($methodBlock->block->pure) {
//            dump('executing flow: ' . $methodBlock->block->name);
            $parameters = $this->gatherParameters($methodBlock->connectionsIn()->whereRelation('sourcePort', 'type', '!=', 'flow')->get());
            $startingTime = microtime(true);
//            dump('running block: ' . $methodBlock->block->name);
            $result = GenericBlock::make($methodBlock->block->name, $parameters)->run();
//            dump('result: ', $result);
            $endingTime = microtime(true);
            $this->stack[$methodBlock->id]['time'] = ($endingTime - $startingTime);

            // save the result to cache
//            dump('saving result to cache');
//            dump($methodBlock->connectionsOut->toArray());
            $methodBlock->connectionsOut()->whereRelation('targetPort', 'type', '!=', 'flow')
                ->whereRelation('targetPort', 'direction', '1')->get()->each(
                    function (Connection $connection) use ($result) {
//                        dump($connection->sourcePort->name . ' -> ' . $connection->targetPort->name);
//                        dump($connection->sourcePort->name, $result[$connection->sourcePort->name]);
                        $this->cache[$connection->id] = $result[$connection->sourcePort->name] ?? null;
                    }
                );

            // if this is the end block, or we are not allowed to propagate, we can stop here
            if ($doNotPropagate) {
                return $result;
            }

            // if this is not the end block, we need to execute the next flow
            $nextFlow = $methodBlock->connectionsOut()->whereRelation('targetPort', 'type', 'flow')->first();
            return $this->executeFlow($nextFlow->target);
        } else {
            // if this is not a pure block, we need to execute the flow
//            dump('diving into block: ' . $methodBlock->block->name);
            return $this->executeBlock($methodBlock->block);
        }
    }

    /**
     * @param Collection $connections
     * @return array
     */
    public function gatherParameters(Collection $connections, $targetPortName = true)
    {
        $parameters = [];
//        dump('gathering ' . $connections->count() . ' parameters');
        foreach ($connections as $connection) {
            // get all input ports that are not flow and gather their values
//            dump('gathering parameter: ' . $connection->sourcePort->name);
            $parameters[$targetPortName ? $connection->targetPort->name : $connection->sourcePort->name] = $this->getParameter($connection);
        }
//        dump('gathered parameters: ', $parameters);
        return $parameters;
    }

    public function getParameter(Connection $connection)
    {
//        dump('getting parameter: ' . $connection->id . '@' . $connection->source->block->name . '#' . $connection->sourcePort->name);

        // this way we can manage multiple connections going from same block
        if (!isset($this->cache[$connection->id])) {
            $this->executeFlow($connection->source, true);
        }
        return $this->cache[$connection->id];
    }
}
