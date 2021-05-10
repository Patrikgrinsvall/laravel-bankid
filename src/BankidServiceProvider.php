<?php

namespace Patrikgrinsvall\LaravelBankid;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
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
            ->hasConfigFile()
            ->hasViews(true)
            ->hasAssets(true)
            ->hasTranslations(true);
        $this->app->bind('Bankid', Bankid::class,true);
        $this->app->bind('BankidUser', BankidUser::class,true);
    }

    public function boot()
    {
        #parent::boot();
        //$this->app->setLocale("sv");
        $this->loadViewsFrom(
            $this->package->basePath('/../resources/views/vendor'),
            $this->package->shortName()
        );
        $this->loadTranslationsFrom(
            $this->package->basePath('/../resources/lang/vendor/bankid'),
            $this->package->shortName()
        );
        $this->app->config->push('view.paths', $this->package->basePath('/../resources/views/vendor/bankid'));
        $this->app->config->push('view.paths', resource_path("views/vendor/{$this->package->shortName()}"));

        if ( $this->app->runningInConsole()) {
            $this->publishes([
                $this->package->basePath("/../resources/lang/vendor/") => resource_path("lang/vendor/{$this->package->shortName()}"),
                $this->package->basePath("/../resources/views/vendor") => resource_path("views/vendor/{$this->package->shortName()}")
            ]  );

            }

            Route::macro('LaravelBankid', function (string $prefix) {
                Route::prefix($prefix)->group(function () {
                    Route::get('/complete', [BankidController::class,'complete'])->name('bankid.complete');;
                    Route::get('/cancel', [BankidController::class,'cancel']);
                    Route::view('/', 'index');
                });
            });
            Livewire::component('bankidcomponent', Http\Livewire\BankidComponent::class);
        /*$this->publishes([
            $this->package->basePath("/../assets/images") => public_path('vendor/bankid'),
        ],  $this->package->shortName());*/

    }
}
