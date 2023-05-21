<?php

namespace Tests\Feature\Api;

use App\Models\Block;
use App\Models\Method;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MethodBlockTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group method-block
     */
    public function test_can_method_blocks()
    {
        $method = Method::factory()->hasMethodBlocks(5)->create();
        $response = $this->actingAs($method->project->user, 'api')
            ->getJson(route('methods.blocks.index', $method));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'blockable_id',
                    'blockable_type',
                    'x',
                    'y',
                    'created_at',
                    'updated_at',
                    'method_id',
                ],
            ],
        ]);
        $this->assertCount(5, $response->json('data'));
        $this->assertDatabaseCount('method_blocks', 5);
    }

    /**
     * @group method-block
     */
    public function test_can_get_method_block()
    {
        $method = Method::factory()->hasMethodBlocks(1)->create();
        $response = $this->actingAs($method->project->user, 'api')
            ->getJson(route('methods.blocks.show', [$method, $method->methodBlocks->first()]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'blockable_id',
                'blockable_type',
                'x',
                'y',
                'created_at',
                'updated_at',
                'method_id',
            ],
        ]);
    }

    /**
     * @group method-block
     */
    public function test_can_create_method_block_with_block()
    {
        $method = Method::factory()->create();
        $block = Block::factory()->create();
        $response = $this->actingAs($method->project->user, 'api')
            ->postJson(route('methods.blocks.store', $method), [
                'blockable_id' => $block->id,
                'blockable_type' => 'block',
                'x' => 1,
                'y' => 1,
            ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'blockable_id',
                'blockable_type',
                'x',
                'y',
                'created_at',
                'updated_at',
                'method_id',
            ],
        ]);
        $this->assertDatabaseCount('method_blocks', 1);
    }
}
