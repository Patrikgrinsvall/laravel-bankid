
While this package is free to use, you can hire help of integrating it, contact info@silentridge.io


## Swedish BankID for Laravel 8 with Livewire 
You will need laravel 8 to use route facade but it might work with earlier versions when using middleware. Livewire is a required dependency so it will be installed. I think however its possible to quite easy call the service from another controller action and it will work quite well.

Anyway, there are a plenthora of improvements to be made to this project but it is used in production for atleast one company. PRs are very welcome.


## Installation

Composer:
```bash
composer require patrikgrinsvall/laravel-bankid
```
- Publish the config, views and transaltions with
```
php artisan vendor:publish --provider="Patrikgrinsvall\LaravelBankid\LaravelBankidServiceProvider"
```
- Then check in `app/config/bankid.php` for info on how to add your own certificates.
- Views will be editable in your views directory
- Translations will be editable in defauit translations directory. Swedish and English are provided with package.
- 
## Usage
- Service provider should be auto discovered since larvel 8 so no need to register service provider. 
- However i have noticed in many cases this does not work so usually:
- Register service provider `Patrikgrinsvall\LaravelBankid\BankidServiceProvider` in `config/app.php`

**There are a few different ways to use this package**
1. Use the supplied Route macro:
```
// You can change the route prefix '/bankid' to whatever you want, for example /login.
// This is then the entrypoint for where users should be sent to start authentication.
// This line of code should be placed in routes.
Route::LaravelBankid('/bankid'); 
```

2. As a facade (this might work, dont remember.)
```
use Patrikgrinsvall\LaravelBankid\BankidFacade;
...
Bankid::login(); // will redirect user and start a login. After login user is returned to url in config/bankid.php
Bankid::user(); // returns the user previously logged in or false.
```

3. As a middleware, first register in App\Http\Kernel class: (WIP! Dont use yet!) 
```
protected $routeMiddleware = [
    'bankid' => \Patrikgrinsvall\LaravelBankid\BankidMiddleware::class,
```
 Then use in one of the following ways:
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

## Retrieve authenticated user.

```
// Get the user from the session
use Illuminate\Support\Facades\Session;
...
Session::get('bankid.user');            // returns user details as an array
Session::get('bankid.personalNumber');  // returns personal number as a string
Session::get('bankid.name');            // returns full name as a string
Session::get('bankid.givenName');       // returns first name as a string
Session::get('bankid.surname');         // returns lastn name as string
Session::get('bankid.signature');       // returns signature as string.
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

## Final notes. 
-  Basic authentication works for mobile devices.
-  But alot in this package is not finished i will continue to iterate over it when need arises.
-  PRs are welcome!
-  Sign is not working.
-  QR is not working.
-  Same device is not working (bankid on fil)





The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
