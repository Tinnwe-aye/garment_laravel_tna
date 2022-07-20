<?php

namespace App\Providers;

use App\Repositories\TestRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\TestRepositoryInterface;
use App\Repositories\Tailor\TailorRepository;
use App\Interfaces\Tailor\TailorRepositoryInterface;

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
        $this->app->bind(TailorRepositoryInterface::class, TailorRepository::class);
    }
}
