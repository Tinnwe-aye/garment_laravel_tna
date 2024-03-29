<?php

namespace App\Providers;

use App\Repositories\TestRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\TestRepositoryInterface;
use App\Repositories\Tailor\TailorRepository;
use App\Repositories\Supplier\SupplierRepository;
use App\Interfaces\Tailor\TailorRepositoryInterface;
use App\Interfaces\Supplier\SupplierRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Interfaces\Product\ProductRepositoryInterface;
use App\Repositories\Customer\CustomerRepository;
use App\Interfaces\Customer\CustomerRepositoryInterface;
use App\Interfaces\SupplierTransaction\SupplierTransactionRepositoryInterface;
use App\Repositories\SupplierTransaction\SupplierTransactionRepository;
use App\Repositories\CustomerTransaction\CustomerTransactionRepository;
use App\Interfaces\CustomerTransaction\CustomerTransactionRepositoryInterface;

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
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(SupplierTransactionRepositoryInterface::class, SupplierTransactionRepository::class);
        $this->app->bind(CustomerTransactionRepositoryInterface::class, CustomerTransactionRepository::class);
    }
}
