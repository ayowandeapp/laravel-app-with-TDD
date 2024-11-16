<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_path()
    {
        $project = Project::factory()->create();

        $this->assertEquals("/api/projects/{$project->id}", $project->path());
    }

    public function test_project_belong_to_owner()
    {
        $project = Project::factory()->create();

        $this->assertInstanceOf(User::class, $project->owner);

    }

    public function test_can_add_a_task()
    {
        $project = Project::factory()->create();
        $task = $project->tasks()->create(['body' => 'new task']);
        $this->assertDatabaseHas(ProjectTask::class, ['project_id' => $project->id, 'body' => 'new task']);
        // $this->assertDatabaseHas(ProjectTask::class, $task->toArray());
        $this->assertTrue($project->tasks->contains($task));
        $this->assertCount(1, $project->tasks);
    }
}
