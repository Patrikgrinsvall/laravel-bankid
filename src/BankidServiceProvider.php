<?php

namespace Patrikgrinsvall\LaravelBankid;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BankidServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-bankid')
            ->hasConfigFile()
            ->hasCommands([])
            ->hasTranslations(true);
    }

    public function boot()
    {


    }

}
