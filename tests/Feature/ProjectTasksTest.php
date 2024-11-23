<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_project_can_have_tasks()
    {
        $project = ProjectFactory::ownedBy($this->authenticate())->create();

        $this->post("{$project->path()}/tasks", ['body' => 'New Tasks'])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertSee('New Tasks');

        // $this->get($project->path())->assertSee('New Tasks');
    }

    public function test_a_tasks_can_be_updated()
    {
        $project = ProjectFactory::withTasks(1)->create();


        // $project = app(ProjectFactory::class)
        // // ->ownedBy($this->authenticate())
        // ->withTasks(1)
        // ->create();
        // $this->authenticate();

        // $project = Project::factory()->create(['owner_id' => auth()->user()->id]);

        // $task = ProjectTask::factory()->create(['project_id' => $project->id]);

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
                'body' => 'changed body'
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertSee('changed body');
    }

    public function test_a_tasks_can_be_completed()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
                'body' => 'changed body',
                'completed' => 1
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertSee('changed body');
    }
    public function test_a_tasks_can_be_incomplete()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
                'body' => 'changed body',
                'completed' => 0
            ]);

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
                'completed' => 1
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertSee('changed body')
        ;
        $this->assertEquals(1, $project->refresh()->tasks->first()->completed);
    }
    public function test_only_project_owner_can_update_a_task()
    {
        $this->authenticate();

        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks->first()->path(), [
            'body' => 'changed body',
            'completed' => 1
        ])
            ->assertStatus(Response::HTTP_FORBIDDEN)
        ;

        $this->assertDatabaseMissing(ProjectTask::class, $project->tasks->first()->toArray());

        // $this->get($project->path())->assertSee('New Tasks');
    }
    public function test_a_task_requires_a_body()
    {
        $project = ProjectFactory::ownedBy($this->authenticate())->create();

        $res = $this->post("{$project->path()}/tasks", ['body' => null]);
        $res->assertSessionHasErrors(['body']);
    }

    public function test_guest_cannot_add_tasks_to_project()
    {
        $project = Project::factory()->create();

        $payload = ProjectTask::factory()->raw(['project_id' => $project->id]);

        $res = $this->post("{$project->path()}/tasks", $payload);

        $res->assertStatus(Response::HTTP_FOUND);

    }

    public function test_only_project_owner_may_add_tasks()
    {
        $this->authenticate();

        $project = Project::factory()->create();

        $payload = ProjectTask::factory()->raw(['project_id' => $project->id]);

        $res = $this->post("{$project->path()}/tasks", $payload)
            ->assertStatus(Response::HTTP_FORBIDDEN)
        ;
        $this->assertDatabaseMissing('project_tasks', $payload);

    }


    public function test_task_can_be_included_when_creating_project()
    {
        $this->withoutExceptionHandling();
        $this->authenticate();

        $attributes = Project::factory()->raw();

        $attributes['tasks'] = [
            ['body' => 'Task 1'],
            ['body' => 'Task 2']
        ];

        $project = $this->post('/api/projects', $attributes)->assertStatus(201);

        $project = Project::find($project['id']);

        $this->assertCount(2, $project->tasks);

    }
}
