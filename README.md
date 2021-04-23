# WIP! This is not in a usable state yet! Wait a week or two more.

## Laravel Swedish BankID 
 This package enabless seamless integration with Swedish Bankid using laravel 8.
 - Unit testing 👌
 - Test certificates 👌
 - Auto discovery 👌
 - Custom views 👌
 - Own production certificates 👌
 
 The only flow that is implemented for now is to authenticate on a mobile phone (other device) using a Swedish personal number.
 

To do list:
- [x]  Make a working laravel package
- [ ]  Migrate code from old package
- [ ]  Write authentication guard
- [ ]  Support QR
- [ ]  Rewrite tests
- [ ]  Publish to packagist

## Testing
For windows run `./vendor/bin/testbench package:test`


## Installation

~~You can install the package via composer:

```bash
composer require patrikgrinsvall/laravel_bankid
```

Publish the config file with:
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
- Composer install
- All dependencies should be auto discovered since larvel 8 so no need to register service provider. 
- If above doesnt work, register service provider `Patrikgrinsvall\LaravelBankid\BankidServiceProvider` in `config/app.php`
- In your routes, define what route prefix to use for bankid authentication, example: 
  `Route::LaravelBankid('/bankid');` 
- To test it works, browse to your local environment and to the prefix choosen above.
- Start a bankid authentication you can either use facade: ```
use Patrikgrinsvall\LaravelBankid\BankidFacade;
...

Bankid::login(); // <-- will redirect user to login flow and ask for pnr etc.
```
- After bankid authentication is completed you can get the bankid details like `Bankid::user()`
- 

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
