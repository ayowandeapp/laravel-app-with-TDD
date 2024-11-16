<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\ProjectTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Feature\ProjectsTest;

class ProjectTaskTest extends TestCase
{
    use RefreshDatabase;
    public function test_as_a_path()
    {

        $task = ProjectTask::factory()->createOne();

        $this->assertEquals("/api/projects/{$task->project_id}/tasks/$task->id", $task->path());
    }
    public function test_belong_to_project()
    {
        $task = ProjectTask::factory()->createOne();

        $this->assertInstanceOf(Project::class, $task->project);
    }
}
