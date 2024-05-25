<?php

namespace App\Blocks;

use Exception;

class ObjectGetBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Object', 'Property'];

    /**
     * @throws Exception
     */
    public function run(): array
    {
        $object = is_string($this->Object) ? json_decode($this->Object, true) : $this->Object;
        return [
            'Result' => $this->get($object, $this->Property)
        ];
    }

    /**
     * @throws Exception
     */
    private function get(array $object, $property)
    {
        if (str_contains($property, '.')) {
            $parts = explode('.', $property);
            $current = $object;
            foreach ($parts as $part) {
                if (!isset($current[$part])) {
                    return null;
                }
                $current = $current[$part];
            }
            return $current;
        } else {
            return $object[$property] ?? throw new Exception('Property not found ' . $property);
        }
    }
}
