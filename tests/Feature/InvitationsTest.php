<?php

namespace Tests\Feature;

use App\Http\Controllers\ProjectTaskController;
use App\Models\ProjectTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    function test_the_invitee_must_be_a_user()
    {
        // $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->post("{$project->path()}/invite", [
            'email' => 'omoiya@sapa.com'
        ])->assertSessionHasErrors('email');



    }
    function test_a_project_can_invite_a_user()
    {
        // $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $invitedUser = User::factory()->create();

        $this->actingAs($project->owner)->post("{$project->path()}/invite", [
            'email' => $invitedUser->email
        ]);

        $this->assertTrue($project->members->contains($invitedUser));

    }
    function test_a_invited_user_can_update_project()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $project->invite($newUser = User::factory()->create());

        $this->authenticate($newUser);

        $this->post(action([ProjectTaskController::class, 'store'], $project), $task = ['body' => 'invited!']);

        $this->assertDatabaseHas(ProjectTask::class, $task);

    }
}
