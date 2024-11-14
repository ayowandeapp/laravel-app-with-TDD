<?php

namespace Tests\Unit;

use App\Models\Project;
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
}
