<?php

namespace App\Providers;

use App\Interfaces\ContentStatusRepositoryInterface;
use App\Interfaces\ContentTypeRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\ContentStatusRepository;
use App\Repositories\ContentTypeRepository;
use App\Repositories\UserRepository;
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
        $this->app->bind(ContentStatusRepositoryInterface::class, ContentStatusRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
