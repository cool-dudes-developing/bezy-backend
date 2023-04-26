<?php

namespace Tests\Feature\Api;

use App\Models\Method;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MethodTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group method
     */
    public function test_can_get_methods()
    {
        $project = Project::factory()->create();
        Method::factory()->count(5)->create(['project_id' => $project->id]);
        Method::factory()->count(5)->create();
        $response = $this->actingAs($project->user, 'api')
            ->getJson(route('projects.methods.index', $project));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                    'project_id',
                ],
            ],
        ]);
        $this->assertCount(5, $response->json('data'));
        $this->assertDatabaseCount('methods', 10);
    }

    /**
     * @group method
     */
    public function test_can_get_method()
    {
        $project = Project::factory()->create();
        $method = Method::factory()->create(['project_id' => $project->id]);
        $response = $this->actingAs($project->user, 'api')
            ->getJson(route('projects.methods.show', [$project, $method]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'name',
                'created_at',
                'updated_at',
                'project_id',
            ],
        ]);
        $this->assertDatabaseHas('methods', [
            'id' => $response->json('data.id'),
            'name' => $method->name,
            'project_id' => $project->id,
        ]);
    }

    /**
     * @group method
     */
    public function test_can_create_method()
    {
        $project = Project::factory()->create();
        $method = Method::factory()->make(['project_id' => $project->id]);
        $response = $this->actingAs($project->user, 'api')
            ->postJson(route('projects.methods.store', $project), $method->toArray());
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'name',
                'created_at',
                'updated_at',
                'project_id',
            ],
        ]);
        $this->assertDatabaseHas('methods', [
            'id' => $response->json('data.id'),
            'name' => $method->name,
            'project_id' => $project->id,
        ]);
    }

    /**
     * @group method
     */
    public function test_can_update_method()
    {
        $project = Project::factory()->create();
        $method = Method::factory()->create(['project_id' => $project->id]);
        $method->name = 'Updated';
        $response = $this->actingAs($project->user, 'api')
            ->putJson(route('projects.methods.update', [$project, $method]), $method->toArray());
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'name',
                'created_at',
                'updated_at',
                'project_id',
            ],
        ]);
        $this->assertDatabaseHas('methods', [
            'id' => $method->id,
            'name' => 'Updated',
        ]);
    }

    /**
     * @group method
     */
    public function test_can_delete_method()
    {
        $project = Project::factory()->create();
        $method = Method::factory()->create(['project_id' => $project->id]);
        $response = $this->actingAs($project->user, 'api')
            ->deleteJson(route('projects.methods.destroy', [$project, $method]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
        ]);
        $this->assertDatabaseMissing('methods', $method->toArray());
    }
}
