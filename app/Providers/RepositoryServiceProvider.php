<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\TestRepository;
use App\Interfaces\TestRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TestRepositoryInterface::class, TestRepository::class);
    }
}
