<?php

namespace App\Providers;

use App\Http\Controllers\Client\ClientOrderController;
use App\Interfaces\Client\CrudRepoInterface;
use App\Repositories\Client\ClientOrderRepo;

use Illuminate\Support\ServiceProvider;

class CrudRepoProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // $this->app->bind(CrudRepoInterface::class, ClientOrderRepo::class);

        $this->app->when(ClientOrderController::class)
            ->needs(CrudRepoInterface::class)
            ->give(function () {
                return new ClientOrderRepo();
            });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
