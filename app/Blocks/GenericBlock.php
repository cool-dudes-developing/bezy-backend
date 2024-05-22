<?php

namespace App\Blocks;

use App\Models\MethodBlock;
use App\Services\MethodService;
use Exception;

/**
 * @method array|int run()
 */
abstract class GenericBlock
{
    protected array $requiredParameters = [];

    /**
     * @throws Exception
     */
    public function __construct(protected readonly MethodService $service, protected array $parameters = [])
    {
        if (count(array_diff($this->requiredParameters, array_keys($parameters)))) {
            throw new Exception('Missing required parameters. Required: ' . implode(', ', $this->requiredParameters) . '. Given: ' . implode(', ', array_keys($parameters)));
        }
    }

    public function getNextFlow(MethodBlock $methodBlock): ?MethodBlock
    {
        return $methodBlock->connectionsOut()->whereRelation('targetPort', 'type', 'flow')->first()?->target;
    }


    /**
     * @throws Exception
     */
    public function __get(string $name)
    {
        return $this->parameters[$name] ?? null;
    }

    /**
     * @throws Exception
     */
    public static function make(MethodService $service, string $name, array $parameters = []): static
    {
        $class = 'App\\Blocks\\' . str_replace('_', '', ucwords($name, '_')) . 'Block';

        // check if class exists
        if (!class_exists($class)) {
            throw new Exception('Block ' . $class . ' not found');
        }

        return new $class($service, $parameters);
    }
}
