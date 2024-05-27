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
    const RECURSION_LIMIT = 1000;
    private array $cache = [];
    private array $dependencies = [];
    private array $stack = [];
    private array $recursions = [];
    private array $output = [];
    private array $logs = [];
    private float $startTime;
    private Project $project;
    private string $runId = 'hello';

    public function getProject(): Project
    {
        return $this->project;
    }

    public function getRunId(): string
    {
        return $this->runId;
    }

    public function log(mixed $msg)
    {
        $this->logs[] = $msg;
    }

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

    private function logStack(MethodBlock $methodBlock, array $data = []): void
    {
        $this->stack[] = [
            'id' => $methodBlock->id,
            'block' => $methodBlock->block->name,
            'time' => microtime(true),
            'parameters' => $data['parameters'] ?? [],
            'result' => $data['result'] ?? null
        ];
    }

    /**
     * logStack method is used in runtime to log the stack of executed blocks
     * to be able to debug the execution of the method.
     * To avoid excessive performance impact, it uses as little operations as possible.
     * To create useful stack, we need to clean it up before returning it.
     * Which is done in this method that is called only when the stack is requested.
     * @return array
     */
    private function cleanStack(): array
    {
        $stack = $this->stack;
        $this->stack = [];
        $last = null;
        foreach ($stack as $key => $item) {
            if ($last) {
                $stack[$key]['duration'] = $item['time'] - $last['time'];
            }
            $last = $item;
            unset($stack[$key]['time']);
        }

        return $stack;
    }

    private function cleanCache(): array
    {
        $cache = [];

        foreach ($this->cache as $key => $value) {
            if ($connection = Connection::find($key)) {
                $cache[$connection->sourcePort->name] = [
                    'value' => $value,
                    'type' => $connection->sourcePort->type,
                    'direction' => $connection->sourcePort->direction,
                    'from' => $connection->source->block->title,
                    'to' => $connection->target->block->title,
                ];
            } else {
                $cache[$key] = $value;
            }
        }
        return $cache;
    }

    public function debug(Project $project, Block $block, array $data): JsonResponse
    {
        $this->project = $project;
        $status = 200;
        try {
            $result = $this->execute($project, $block, $data);
        } catch (Exception $e) {
            throw $e;
            $result = $e->getMessage();
            $status = 500;
        }

        return response()->json([
            'result' => $result,
            'cache' => $this->cleanCache(),
            'stack' => $this->cleanStack(),
            'logs' => $this->logs,
        ], $status);
    }

    /**
     * Execute block
     * @param Block $block
     * @param array $data
     * @return array
     */
    public function execute(Project $project, Block $block, array $data, string $run_id = null, array $recursions = [])
    {
        $this->project = $project;

        $this->startTime = microtime(true);

        $this->recursions = $recursions;

        if (!$run_id) {
            $this->runId = uniqid();
        }

        // hydrate cache with input data
        $block->connections()->whereRelation('sourcePort', 'type', '!=', 'flow')->whereRelation('sourcePort', 'direction', '0')->get()->each(
            fn(Connection $connection) => $this->setConnectionVariable($connection, $this->cast(
                $data[$connection->sourcePort->name] ?? null,
                $connection
            ))
        );

        // execute runs start block,
        // start block does nothing but connects to the first flow,
        // so we can just execute the first flow
        return $this->executeBlock($block);
    }

    private function executeBlock(Block $block): array|int
    {
//        dump($block->name);

        // get all connections in this block where the source is a flow.
        // Since flow can only be connected to flow, we can assume
        // that the target port is also a flow.
        $startBlock = $block->methodBlocks()->whereRelation('block', 'type', 'start')->first();
        if (!$startBlock) {
            dd($block->pure, $block->name);
        }
        /** @var Connection $startFlow */
        $startFlow = $block->connections()->whereRelation('sourcePort', 'type', 'flow')->where('from_method_block_id', '=', $startBlock->id)->first();

        $this->logStack($startBlock);

        return $this->executeFlow($startFlow->target);
    }

    public function executeFlow(MethodBlock $methodBlock, $doNotPropagate = false): array|int
    {
        $this->recursions[$methodBlock->id] = ($this->recursions[$methodBlock->id] ?? 0) + 1;
        if ($this->recursions[$methodBlock->id] > self::RECURSION_LIMIT) {
            throw new Exception('Infinite loop detected in block ' . $methodBlock->block->title);
        }
//        dump($methodBlock->block->name);
        if ($methodBlock->block->type === 'start') {
            throw new Exception('Missing required parameter');
        }
        if ($methodBlock->block->type === 'end') {
            $connections = $methodBlock->connectionsIn()
                ->whereRelation('targetPort', 'type', '!=', 'flow')
                ->whereRelation('targetPort', 'direction', '1')
                ->get();
//            dd($connections->load(['sourcePort.block', 'targetPort.block'])->toArray());
//            dd($methodBlock->parent->connections->load(['sourcePort.block', 'targetPort.block'])->toArray());
            $params = $this->gatherParameters($connections);
            $this->logStack($methodBlock, [
                'parameters' => $params
            ]);
            return $params;
        }
        // if this is a pure block, we can execute it right away
        if ($methodBlock->block->pure) {
//            dump('executing flow: ' . $methodBlock->block->name);
            $parameters = $this->gatherParameters($methodBlock->connectionsIn()->whereRelation('sourcePort', 'type', '!=', 'flow')->get());
            if ($methodBlock->id === '9c1dcecd-50ec-402c-9f0f-1fb1fbe709b9') {
//                dump($parameters);
//                dump($methodBlock->connectionsIn->load(['sourcePort.block', 'targetPort.block'])->toArray());
//                dd($methodBlock->block->title);
            }
            if ($methodBlock->constant) {
                $parameters['_constant'] = $methodBlock->constant;
            }
//            dump('running block: ' . $methodBlock->block->name);
            $genericBlock = GenericBlock::make(
                $this,
                $methodBlock->block->name,
                $parameters,
                $methodBlock
            );
            $result = $genericBlock->run();
//            dump($result);
            $this->logStack($methodBlock, [
                'parameters' => $parameters,
                'result' => $result
            ]);

            // save the result to cache
//            dump('saving result to cache');
//            dump($methodBlock->connectionsOut->load(['sourcePort.block', 'targetPort.block'])->toArray());
            $methodBlock->connectionsOut()->whereRelation('sourcePort', 'type', '!=', 'flow')
                ->whereRelation('sourcePort', 'direction', '1')->get()->each(
                    function (Connection $connection) use ($result) {
//                        dump($connection->sourcePort->name . ' -> ' . $connection->targetPort->name);
//                        dump($connection->sourcePort->name, $result[$connection->sourcePort->name]);
                        $this->setConnectionVariable($connection, $this->cast(
                            $result[$connection->sourcePort->name] ?? null,
                            $connection
                        ));
                    }
                );

            // if this is the end block, or we are not allowed to propagate, we can stop here
            if ($doNotPropagate || is_int($result)) {
//                dump('returning result: ', $result);
                return $result;
            }

            // if this is not the end block, we need to execute the next flow
            if ($next = $genericBlock->getNextFlow($methodBlock))
                return $this->executeFlow($next);
            return 0;
        } else {
            // if this is not a pure block, we need to execute the flow
//            dump('diving into block: ' . $methodBlock->block->name);
//            return $this->executeBlock($methodBlock->block);
            $parameters = $this->gatherParameters(
                $methodBlock
                    ->connectionsIn()
                    ->whereRelation(
                        'sourcePort',
                        'type',
                        '!=',
                        'flow'
                    )
                    ->get()
            );
            // execute external method in different context
            // this way we can manage multiple method blocks in the same context
            $result = (new MethodService())->execute(
                $this->project,
                $methodBlock->block,
                $parameters,
                $this->runId,
                $this->recursions
            );
            $this->logStack($methodBlock, [
                'parameters' => $parameters,
                'result' => $result
            ]);

            // save the result to cache
            $methodBlock->connectionsOut()->whereRelation('sourcePort', 'type', '!=', 'flow')
                ->whereRelation('sourcePort', 'direction', '1')->get()->each(
                    function (Connection $connection) use ($result) {
                        $this->setConnectionVariable($connection, $this->cast(
                            $result[$connection->sourcePort->name] ?? null,
                            $connection
                        ));
                    }
                );

            // if this is the end block, or we are not allowed to propagate, we can stop here
            if ($doNotPropagate || is_int($result)) {
                return $result;
            }

            // if this is not the end block, we need to execute the next flow
            if ($next = $methodBlock->connectionsOut()->whereRelation('sourcePort', 'type', 'flow')->first()) {
                return $this->executeFlow($next->target);
            }
            return 0;
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
            // if the connection has a default value, we can use it
            if ($connection->targetPort->default) {
                $this->setConnectionVariable($connection,
                    $this->cast(
                        $connection->targetPort->default,
                        $connection
                    )
                );
            }

            // override the default value with the value from the source connection
            $this->executeFlow($connection->source, true);
//            dump($this->cache);
        }
        return $this->cache[$connection->id];
    }

    public function cast($value, Connection $connection)
    {
        $type = $connection->targetPort->type;
        if ($type === 'any') {
            $type = $connection->sourcePort->type;
        }

        switch ($type) {
            case 'string':
                return (string)$value;
            case 'number':
                return (float)$value;
            case 'boolean':
                return (bool)$value;
            case 'array':
                if (is_array($value)) {
                    return $value;
                }
                return json_decode($value, false);
            case 'object':
                if (is_array($value)) {
                    return $value;
                }
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    private function unsetRecursiveDependencies($targetId): void
    {
        if (isset($this->dependencies[$targetId])) {
            foreach ($this->dependencies[$targetId] as $dependency) {
//                dump('unsetting dependency: ' . $dependency);
                unset($this->cache[$dependency]);
                $this->unsetRecursiveDependencies($dependency);
            }
        }
    }

    public function setConnectionVariable(Connection $connection, mixed $value): void
    {
        // check if variable already cached,
        // if so, we need to update it and all its dependencies
        if (isset($this->cache[$connection->id])) {
            $this->unsetRecursiveDependencies($connection->id);
//            foreach ($this->dependencies[$connection->id] as $dependency) {
//                dump('unsetting dependency');
//                unset($this->cache[$dependency]);
//            }
        }
        $this->cache[$connection->id] = $value;
        $this->dependencies[$connection->id] = $connection->target->connectionsOut()->whereRelation('sourcePort', 'type', '!=', 'flow')->get()->pluck('id')->toArray();
    }

    public function setVariable(string $variable, mixed $value): void
    {
        if (isset($this->cache[$variable])) {
            $this->unsetRecursiveDependencies($variable);
//            if (isset($this->dependencies[$variable])) {
//                foreach ($this->dependencies[$variable] as $dependency) {
////                    dump('unseting');
//                    unset($this->cache[$dependency]);
//                }
//            }
        }
        $this->cache[$variable] = $value;
    }

    public function getVariable($outConnections, string $variable): mixed
    {
        $deps = $this->dependencies[$variable] ?? [];
        foreach ($outConnections as $outConnection) {
            if (!in_array($outConnection->id, $deps)) {
                $deps[] = $outConnection->id;
            }

//            dd($outConnection->target->connectionsOut()->whereRelation('sourcePort', 'type', '!=', 'flow')->get()->load(['sourcePort.block', 'targetPort.block'])->toArray());
            foreach ($outConnection->target->connectionsOut()->whereRelation('sourcePort', 'type', '!=', 'flow')->get() as $connection) {
                if (!in_array($connection->id, $deps)) {
                    $deps[] = $connection->id;
                }
            }
        }
        $this->dependencies[$variable] = $deps;
        return $this->cache[$variable] ?? null;
    }

    public function output($message): void
    {
        $this->output[] = $message;
    }
}
