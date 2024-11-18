<?php

namespace App\Models;

use App\Observers\ProjectObserver;
use App\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ProjectObserver::class])]
class Project extends Model
{
    use RecordsActivity;
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $guarded = [];


    public function path(): string
    {
        return "/api/projects/{$this->id}";
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class, 'project_id');
    }

    public function activity(): HasMany
    {
        return $this->hasMany(Activity::class, 'project_id')->latest();
    }

    // public function saveActivity($description)
    // {
    //     // $this->activity()->create([
    //     //     'user_id' => ($this->project ?? $this)->owner->id,
    //     //     'description' => $description,
    //     //     'changes' => $this->loadActivityChanges()
    //     // ]);
    //     $this->activity()->create([
    //         'user_id' => ($this->project ?? $this)->owner->id,
    //         'description' => $description,
    //         'changes' => $this->loadActivityChanges(),
    //         'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id
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
