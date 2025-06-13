<?php

namespace App\Providers;

use App\Interfaces\ContentTypeRepositoryInterface;
use App\Repositories\ContentTypeRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->bind(ContentTypeRepositoryInterface::class, ContentTypeRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
