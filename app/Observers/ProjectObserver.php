<?php

namespace App\Observers;

use App\Models\Project;
use Str;

class ProjectObserver
{
    public function creating(Project $project): void
    {
        $project->slug = $project->slug ?? (str_replace(' ', '-', strtolower($project->name)) . '-' . Str::random(5));
    }

    public function created(Project $project): void
    {

    }

    public function updated(Project $project): void
    {
    }

    public function deleted(Project $project): void
    {
    }

    public function restored(Project $project): void
    {
    }

    public function forceDeleted(Project $project): void
    {
    }
}
