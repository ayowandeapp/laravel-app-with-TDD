<?php

namespace Tests\Feature;

use App\Models\ProjectTask;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActivityFeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_a_project_generate_activity()
    {
        // $this->withoutExceptionHandling();
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);

        $this->assertEquals('created', $project->activity()->first()->description);
    }
    public function test_update_a_project_generate_activity()
    {
        // $this->withoutExceptionHandling();
        $project = ProjectFactory::create();
        $oldTitle = $project->title;

        $project->update(['title' => 'changed']);

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function ($activity) use ($oldTitle) {

            $this->assertEquals('updated', $activity->description);
            $expected = [
                'before' => [
                    'title' => $oldTitle,
                ],
                'after' => [
                    'title' => 'changed',
                ]
            ];
            // dd($expected, $activity->changes);
            $this->assertEquals($expected, $activity->changes);
        });

    }


    public function test_create_a_task_generate_activity()
    {
        // $this->withoutExceptionHandling();
        // $project = ProjectFactory::withTasks(1)->create();

        $project = ProjectFactory::withTasks(1)->create();

        // $project->tasks()->create(ProjectTask::factory()->raw());

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('created_task', $activity->description);
            $this->assertInstanceOf(ProjectTask::class, $activity->subject);
        });
    }

    public function test_update_a_task_generate_activity()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $project->tasks->first()->update(['completed' => 1]);

        $this->assertCount(3, $project->activity);

        $this->assertEquals('completed_task', $project->activity->last()->description);
    }

    public function test_delete_a_task_generate_activity()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $project->tasks[0]->delete();

        $this->assertCount(3, $project->activity);

        $this->assertEquals('deleted_task', $project->activity->last()->description);
    }
}
