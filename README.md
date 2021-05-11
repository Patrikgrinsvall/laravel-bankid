# WIP! Still a few bugs.

## Laravel 8, with livewire Swedish BankID 
You will need laravel 8 to use route facade but it might work with earlier versions when using middleware. Livewire is a required dependency so it will be installed.


## Installation

Composer:
```bash
composer require patrikgrinsvall/laravel_bankid
```

Publish the config, translations and views with:
```
php artisan vendor:publish --provider="Patrikgrinsvall\LaravelBankid\LaravelBankidServiceProvider"
```
Or to select from vendor list.
```
php artisan vendor:publish
```
After publishing you will be able to add your production certificates, edit views and translations.

This is the contents of the published config file, defaults to test certificates:

```php
return [
    'SSL_CERT' => env('BANKID_SSL_CERT', base_path("storage/certs/bankidtest/bankidtest.crt.pem")),
    'CA_CERT' => env('BANKID_CA_CERT', base_path("storage/certs/cacert-2020-01-01.pem")),
    'ENDPOINT' => env('BANKID_ENDPOINT', "https://appapi2.test.bankid.com/rp/v5.1"),
    'SSL_KEY' => env("BANKID_SSL_KEY", base_path("storage/certs/bankidtest/bankidtest.key.pem")),
    'SSL_KEY_PASSWORD' => env("BANKID_SSL_KEY_PASSWORD", "qwerty123"),
    'completeUrl' =>  env('BANKID_COMPLETE_URL','/member/index'), // change to the url to redirect user to after completed login
    'cancelUrl' => env('BANKID_CANCEL_URL','/'), // change to the url to redirect user to if he press cancel.
    'SETUP_COMPLETE' => false // shows install instructions
];
```

## Usage
- Service provider should be auto discovered since larvel 8 so no need to register service provider. 
- If above doesnt work, register service provider `Patrikgrinsvall\LaravelBankid\BankidServiceProvider` in `config/app.php`
- There are a few different ways to use this package. As middleware, by calling the supplied route with prefix or as a facade:
1. As a middleware, first register in App\Http\Kernel class:
```
protected $routeMiddleware = [
    'bankid' => \Patrikgrinsvall\LaravelBankid\BankidMiddleware::class,
```
1.1  Then use in one of the following ways:
```
// On a single route
Route::get('/profile', function () {
// your stuff
})->middleware('bankid');

// On a group of routes
Route::middleware([BankidMiddleware::class])->group(function () {
    Route::get('/your-route', View::render('your-route'))
});

// On a group of routes without adding to kernel
Route::middleware([\Patrikgrinsvall\LaravelBankid\BankidMiddleware::class])->group(function () {
    Route::get('/your-route', View::render('your-route'))
});
```
2. As a facade
```
use Patrikgrinsvall\LaravelBankid\BankidFacade;
...
Bankid::login(); // will redirect user and start a login. After login user is returned to url in config/bankid.php
Bankid::user(); // returns the user previously logged in
```
3. Use the supplied Route facade:
```
Route::LaravelBankid('/bankid'); // you can change the route prefix '/bankid' to whatever you want.
```
Then you can get the logged in user in one of the following ways;
```
// Get the user from the session
use Illuminate\Support\Facades\Session;
...
Session::get('user'); // another way to get the logged in user.
```
From facade:
```
use Patrikgrinsvall\LaravelBankid\BankidFacade;
...
Bankid::user();
``` 

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Patrik Grinsvall](https://github.com/patrikgrinsvall)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
