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
- In your routes, define what route prefix to use for bankid authentication, example: 
  `Route::LaravelBankid('/bankid');` 
- Or you can use supplied middleware:
```// Within App\Http\Kernel class...
protected $routeMiddleware = [
    '`bankid' => \Patrikgrinsvall\LaravelBankid\BankidMiddleware::class,

// On a single route
Route::get('/profile', function () {
})->middleware('bankid');

// On a group of routes without adding to kernel
Route::middleware([\Patrikgrinsvall\LaravelBankid\BankidMiddleware::class])->group(function () {
    Route::get('/your-route', View::render('your-route'))
});

// Or as a facade
use Patrikgrinsvall\LaravelBankid\BankidFacade;
...
Bankid::login(); // will redirect user and start a login. After login user is returned to url in config/bankid.php
Bankid::user(); // returns the user previously logged in

// Get the user from the session
use Illuminate\Support\Facades\Session;
...
Session::get('user'); // another way to get the logged in user.
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
