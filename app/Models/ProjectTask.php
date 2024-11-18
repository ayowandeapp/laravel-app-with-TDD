<?php

namespace App\Models;

use App\Observers\ProjectTaskObserver;
use App\RecordsActivity;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;


#[ObservedBy([ProjectTaskObserver::class])]
class ProjectTask extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectTaskFactory> */
    use HasFactory, RecordsActivity;

    protected $guarded = [];

    protected $touches = ['project'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function path(): string
    {
        return "{$this->project->path()}/tasks/$this->id";
    }

    public function activity(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    // public function saveActivity($description)
    // {
    //     $this->activity()->create([
    //         'user_id' => ($this->project ?? $this)->owner->id,
    //         'description' => $description,
    //         'project_id' => $this->project->id
    //     ]);
    // }

    // private function loadActivityChanges()
    // {
    //     if ($this->wasChanged()) {
    //         return [
    //             'before' => array_diff($this->old, $this->getAttributes()),
    //             'after' => $this->getChanges()
    //         ];
    //     }
    // }
}
