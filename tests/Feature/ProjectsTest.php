<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_a_user_can_create_project(): void
    {
        $this->authenticate();

        $payload = Project::factory()->raw(['owner_id' => auth()->user()->id]);

        $response = $this->post('/api/projects', $payload);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('projects', $payload);

        $this->get('/api/projects')->assertSee($payload['title']);

    }

    public function test_a_user_can_update_a_project(): void
    {
        $project = ProjectFactory::create();

        $payload = Project::factory()->raw(['owner_id' => $project->owner->id]);

        $response = $this->actingAs($project->owner)->patch($project->path(), $payload);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('projects', $payload);

        $this->get('/api/projects')->assertSee($payload['title']);

    }

    public function test_guests_cannot_view_projects(): void
    {

        $response = $this->get('/api/projects');
        // dd($response);
        // $response->assertSessionHasErrors(['unauthorized']);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_a_user_can_view_a_project(): void
    {
        $project = ProjectFactory::create();

        $response = $this->actingAs($project->owner)->get($project->path());

        // $response->assertStatus(Response::HTTP_OK);

        $response->assertSee($project['title'])->assertSee($project['description']);
    }


    public function test_a_title_is_required()
    {
        $this->authenticate();

        $payload = Project::factory()->raw(['title' => '']);

        $response = $this->post('/api/projects', $payload);

        $response->assertSessionHasErrors(['title']);

        $response->assertStatus(Response::HTTP_FOUND);

    }
    public function test_a_description_is_required()
    {
        $this->authenticate();

        $payload = Project::factory()->raw(['description' => '']);

        $response = $this->post('/api/projects', $payload);

        $response->assertSessionHasErrors(['description']);

        $response->assertStatus(Response::HTTP_FOUND);
    }
    public function test_guests_cannot_create_project()
    {
        $payload = Project::factory()->raw();

        $response = $this->post('/api/projects', $payload);

        $response->assertStatus(Response::HTTP_FOUND);

    }

    public function test_auth_user_cannot_view_others_project()
    {
        $this->authenticate();

        $project = Project::factory()->create();

        $response = $this->get($project->path());

        $response->assertStatus(Response::HTTP_FORBIDDEN);


    }

    public function test_auth_user_cannot_update_others_project()
    {
        $this->authenticate();

        $project = Project::factory()->create();

        $response = $this->patch($project->path(), ['body' => $this->faker->sentence]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);


    }
}
