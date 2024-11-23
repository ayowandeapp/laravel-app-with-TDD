<?php

namespace Tests\Unit;

use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsetTest extends TestCase
{
    use RefreshDatabase;
    public function test_a_user_has_projects()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->projects);

    }

    public function test_user_has_accessible_projects()
    {
        $user = $this->authenticate();

        ProjectFactory::ownedBy($user)->create();

        $this->assertCount(1, $user->accessibleProjects());

        $user2 = User::factory()->create();

        $project = tap(ProjectFactory::ownedBy($user2)->create())->invite($user);


        $this->assertCount(2, $user->accessibleProjects());

    }
}
