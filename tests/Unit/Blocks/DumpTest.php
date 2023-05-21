<?php

namespace Tests\Unit\Blocks;

use App\Models\Method;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DumpTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group blocks
     */
    public function test_that_dump_block_works(): void
    {
        $method = Method::factory()->create();

        $this->postJson(route('methods.blocks.store', $method), [
            'blockable_type' => 'block',
            'blockable_id' => 'test',
            'x' => 0,
            'y' => 0,
        ])->assertCreated();
    }
}
