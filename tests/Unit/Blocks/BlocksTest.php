<?php

namespace Tests\Unit\Blocks;

use App\Models\Block;
use Exception;
use Tests\TestCase;

class BlocksTest extends TestCase
{
    public function test_all_blocks_present()
    {
        foreach (config('blocks') as $superGroup => $groups) {
            foreach ($groups as $group => $blocks) {
                foreach ($blocks as $name => $block) {
                    if ($name === 'start' || $name === 'end') {
                        continue;
                    }

                    $class = 'App\\Blocks\\' . str_replace('_', '', ucwords($name, '_')) . 'Block';

                    $this->assertTrue(class_exists($class), 'Block ' . $class . ' not found');
                }
            }
        }
    }
}
