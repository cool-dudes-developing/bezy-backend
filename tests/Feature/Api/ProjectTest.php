<?php

namespace Tests\Feature\Api;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group project
     */
    public function test_can_get_projects()
    {
        $user = User::factory()->create();
        Project::factory()->count(5)->create(['user_id' => $user->id]);
        Project::factory()->count(5)->create();
        $response = $this->actingAs($user, 'api')
            ->getJson(route('projects.index'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'slug',
                    'name',
                    'description',
                    'created_at',
                    'updated_at',
                    'user_id',
                ],
            ],
        ]);
        $this->assertCount(5, $response->json('data'));
        $this->assertDatabaseCount('projects', 10);
    }

    /**
     * @group project
     */
    public function test_can_get_project()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user, 'api')
            ->getJson(route('projects.show', $project));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'slug',
                'name',
                'description',
                'created_at',
                'updated_at',
                'user_id',
            ],
        ]);
        $this->assertDatabaseHas('projects', [
            'id' => $response->json('data.id'),
            'name' => $response->json('data.name'),
            'description' => $response->json('data.description'),
        ]);
    }

    /**
     * @group project
     */
    public function test_can_get_project_by_slug()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user, 'api')
            ->getJson(route('projects.show', $project->slug));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'slug',
                'name',
                'description',
                'created_at',
                'updated_at',
                'user_id',
            ],
        ]);
        $this->assertDatabaseHas('projects', [
            'id' => $response->json('data.id'),
            'name' => $response->json('data.name'),
            'description' => $response->json('data.description'),
        ]);
    }

    /**
     * @group project
     */
    public function test_can_create_project()
    {
        $response = $this->actingAs(User::factory()->create(), 'api')
            ->postJson(route('projects.index'), Project::factory()->make()->toArray());
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'slug',
                'name',
                'description',
                'created_at',
                'updated_at',
                'user_id',
            ],
        ]);
        $this->assertDatabaseHas('projects', [
            'id' => $response->json('data.id'),
            'name' => $response->json('data.name'),
            'description' => $response->json('data.description'),
        ]);
    }

    /**
     * @group project
     */
    public function test_can_create_project_without_slug()
    {
        $response = $this->actingAs(User::factory()->create(), 'api')
            ->postJson(route('projects.index'), Project::factory()->make(['slug' => null])->toArray());
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'slug',
                'name',
                'description',
                'created_at',
                'updated_at',
                'user_id',
            ],
        ]);
        $this->assertDatabaseHas('projects', [
            'id' => $response->json('data.id'),
            'name' => $response->json('data.name'),
            'description' => $response->json('data.description'),
        ]);
    }

    /**
     * @group project
     */
    public function test_can_update_project()
    {
        $project = Project::factory()->create();
        $response = $this->actingAs($project->user, 'api')
            ->putJson(route('projects.update', $project), Project::factory()->make()->toArray());
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'slug',
                'name',
                'description',
                'created_at',
                'updated_at',
                'user_id',
            ],
        ]);
        $this->assertDatabaseHas('projects', [
            'id' => $response->json('data.id'),
            'name' => $response->json('data.name'),
            'description' => $response->json('data.description'),
        ]);
    }

    /**
     * @group project
     */
    public function test_can_update_project_with_same_slug()
    {
        $project = Project::factory()->create();
        $response = $this->actingAs($project->user, 'api')
            ->putJson(route('projects.update', $project), [
                'name' => $project->name,
                'description' => $project->description,
                'slug' => $project->slug,
            ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'slug',
                'name',
                'description',
                'created_at',
                'updated_at',
                'user_id',
            ],
        ]);
        $this->assertDatabaseHas('projects', [
            'id' => $response->json('data.id'),
            'name' => $response->json('data.name'),
            'description' => $response->json('data.description'),
        ]);
    }

    /**
     * @group project
     */
    public function test_can_delete_project()
    {
        $project = Project::factory()->create();
        $response = $this->actingAs($project->user, 'api')
            ->deleteJson(route('projects.destroy', $project));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
        ]);
        $this->assertDatabaseMissing('projects', [
            'id' => $response->json('data.id'),
            'name' => $response->json('data.name'),
            'description' => $response->json('data.description'),
        ]);
    }
}
