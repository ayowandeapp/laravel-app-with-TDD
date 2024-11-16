<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTask extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectTaskFactory> */
    use HasFactory;

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
}
