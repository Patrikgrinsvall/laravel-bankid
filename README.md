# WIP! This is not in a usable state yet! Wait a week or two more.

## Laravel Swedish BankID 

## Testing
For windows run `./vendor/bin/testbench package:test`


## Installation

~~You can install the package via composer:

```bash
composer require patrikgrinsvall/laravel_bankid
```

Publish the config, translations and views with:
```bash
php artisan vendor:publish --provider="Patrikgrinsvall\LaravelBankid\LaravelBankidServiceProvider" --tag="laravel_bankid-config"
```

This is the contents of the published config file:

```php
return [
    "BANKID_ENDPOINT"           => "https://appapi2.bankid.com/rp/v5.1",
    "BANKID_SSL_CERT"           => "storage/certs/bankidprod/customername.crt.pem"
    "BANKID_CA_CERT"            => storage/certs/bankidprod/cacert.pem
    "BANKID_SSL_KEY"            => storage/certs/bankidprod/private.key.pem
    "BANKID_SSL_KEY_PASSWORD"   => xxxx
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
