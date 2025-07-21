<?php

namespace App\Providers;

use App\Interfaces\Auth\RegisterRepositoryInterface;
use App\Interfaces\Auth\SessionRepositoryInterface;
use App\Interfaces\ContentItemRepositoryInterface;
use App\Interfaces\ContentStatusRepositoryInterface;
use App\Interfaces\ContentTypeRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\Auth\RegisterRepository;
use App\Repositories\Auth\SessionRepository;
use App\Repositories\ContentItemRepository;
use App\Repositories\ContentStatusRepository;
use App\Repositories\ContentTypeRepository;
use App\Repositories\TagRepository;
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
        $this->app->bind(ContentItemRepositoryInterface::class, ContentItemRepository::class);
        $this->app->bind(ContentStatusRepositoryInterface::class, ContentStatusRepository::class);
        $this->app->bind(ContentTypeRepositoryInterface::class, ContentTypeRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
        $this->app->bind(SessionRepositoryInterface::class, SessionRepository::class);
        $this->app->bind(RegisterRepositoryInterface::class, RegisterRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
