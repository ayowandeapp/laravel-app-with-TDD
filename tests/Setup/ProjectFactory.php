<?php

namespace Tests\Setup;

use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\User;

class ProjectFactory
{
    protected $taskCount = 0;
    protected User $user;

    public function withTasks($count)
    {
        $this->taskCount = $count;
        return $this;
    }

    public function ownedBy(User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function create()
    {
        $project = Project::factory()->create([
            'owner_id' => $this->user ?? User::factory()->create()
        ]);

        ProjectTask::factory($this->taskCount)->create([
            'project_id' => $project->id
        ]);

        return $project;

    }

}
