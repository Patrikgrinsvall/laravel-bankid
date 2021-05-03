<?php

namespace Patrikgrinsvall\LaravelBankid;

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Patrikgrinsvall\LaravelBankid\Commands\BankidCommand;
use Patrikgrinsvall\LaravelBankid\Http\Controllers\BankidController;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BankidServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-bankid')
            ->hasConfigFile("bankid")
            ->hasViews(true)
            ->hasMigration('create_laravel_bankid_table')
            ->hasAssets(true)
            ->hasCommand(BankidCommand::class);
    }

    public function boot()
    {
        /*
        $this->publishes([
            $this->package->basePath('/../Components') => base_path("app/View/Components/vendor/{$this->package->shortName()}"),
        ], "{$this->package->name}-components");
        */

        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'LaravelBankid');
        if (! $this->app->runningInConsole()) {
            Livewire::component('bankidcomponent', Http\Livewire\BankidComponent::class);
        }
        $this->publishes([
            __DIR__ . "/../assets/images" => public_path('vendor/laravel-bankid'),
        ], 'bankid-assets');
        //$this->app->register(new BankidController());
#        $this->app->regu
        Route::macro('LaravelBankid', function (string $prefix) {
            Route::prefix($prefix)->group(function () {
                Route::get('/complete', [BankidController::class,'complete']);


                Route::get('/cancel', [BankidController::class,'complete']);
                Route::get('/', [BankidController::class, 'index']);
            });
        });
    }

    /**
     * Register the guard.
     *
     * @param \Illuminate\Contracts\Auth\Factory  $auth
     * @param array $config
     * @return RequestGuard
     */
    protected function createGuard($auth, $config)
    {
        return new RequestGuard(
            new Guard($auth, config('sanctum.expiration'), $config['provider']),
            $this->app['request'],
            $auth->createUserProvider($config['provider'] ?? null)
        );
    }
}
