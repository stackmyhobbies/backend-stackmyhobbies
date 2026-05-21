<?php

use App\Providers\AppServiceProvider;
use App\Providers\HorizonServiceProvider;
use App\Providers\RepositoryServiceProvider;
use App\Providers\TelescopeServiceProvider;

$providers = [
    AppServiceProvider::class,
    HorizonServiceProvider::class,
    RepositoryServiceProvider::class,
];

if (class_exists(Laravel\Telescope\TelescopeServiceProvider::class)) {
    $providers[] = TelescopeServiceProvider::class;
}

return $providers;
