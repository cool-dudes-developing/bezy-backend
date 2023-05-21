<?php

namespace App\Blocks;

use Exception;
use Nette\NotImplementedException;

/**
 * @method array run()
 */
abstract class GenericBlock
{
    protected array $requiredParameters = [];

    /**
     * @throws Exception
     */
    public function __construct(protected array $parameters = [])
    {
        if (count(array_diff($this->requiredParameters, array_keys($parameters)))) {
            throw new Exception('Missing required parameters. Required: ' . implode(', ', $this->requiredParameters) . '. Given: ' . implode(', ', array_keys($parameters)));
        }
    }

    /**
     * @throws Exception
     */
    public function __get(string $name)
    {
        return $this->parameters[$name] ?? throw new Exception('Parameter ' . $name . ' not found');
    }

    /**
     * @throws Exception
     */
    public static function make(string $name, array $parameters = []): static
    {
        $class = 'App\\Blocks\\' . ucfirst($name) . 'Block';

        // check if class exists
        if (!class_exists($class)) {
            throw new Exception('Block ' . $name . ' not found');
        }

        return new $class($parameters);
    }
}
