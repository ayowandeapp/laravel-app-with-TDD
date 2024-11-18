<?php

namespace App\Observers;

use App\Models\ProjectTask;

class ProjectTaskObserver
{
    /**
     * Handle the ProjectTask "created" event.
     */
    public function created(ProjectTask $projectTask): void
    {
        // $projectTask->saveActivity('created_task');
    }

    // public function updating(ProjectTask $ProjectTask): void
    // {
    //     $ProjectTask->old = $ProjectTask->getOriginal();
    // }
    /**
     * Handle the ProjectTask "updated" event.
     */
    public function updated(ProjectTask $projectTask): void
    {
        // dd($projectTask);
        // if (!$projectTask->completed)
        //     return;
        // $projectTask->saveActivity('completed_task');
    }

    /**
     * Handle the ProjectTask "deleted" event.
     */
    public function deleted(ProjectTask $projectTask): void
    {
        // $projectTask->saveActivity('deleted_task');
    }

    /**
     * Handle the ProjectTask "restored" event.
     */
    public function restored(ProjectTask $projectTask): void
    {
        //
    }

    /**
     * Handle the ProjectTask "force deleted" event.
     */
    public function forceDeleted(ProjectTask $projectTask): void
    {
        //
    }
}
