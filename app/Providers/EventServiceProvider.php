<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\ProjectBlock;
use App\Models\ProjectTable;
use App\Models\TableColumn;
use App\Models\TableRelation;
use App\Observers\ProjectBlockObserver;
use App\Observers\ProjectObserver;
use App\Observers\ProjectTableObserver;
use App\Observers\TableColumnObserver;
use App\Observers\TableRelationObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Project::observe(ProjectObserver::class);
        ProjectBlock::observe(ProjectBlockObserver::class);
        ProjectTable::observe(ProjectTableObserver::class);
        TableColumn::observe(TableColumnObserver::class);
        TableRelation::observe(TableRelationObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
