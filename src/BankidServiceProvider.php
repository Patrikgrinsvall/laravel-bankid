<?php

namespace Patrikgrinsvall\LaravelBankid;

use Illuminate\Support\Facades\Route;
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
        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'LaravelBankid');

        $this->publishes([
            __DIR__ . "/../assets/images" => base_path('storage/vendor/images/laravel-bankid'),
        ], ['bankid-assets']);

        Route::macro('LaravelBankid', function (string $prefix) {
            Route::prefix($prefix)->group(function () {
                Route::get('/', [BankidController::class, 'index']);
            });
        });
    }
}
