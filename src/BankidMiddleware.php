<?php
namespace Patrikgrinsvall\LaravelBankid;

use Closure;
use Illuminate\Http\Request;
use Patrikgrinsvall\LaravelBankid\BankidFacade;
class BankidMiddleware {
    public function handle(Request $request, Closure $next)
    {

        if($user = Bankid::user()) {
            if(isset($user['personal_number'])) {
                return $next($request);
            }
        } else {
            return redirect(config('bankid.prefix') . "/login");
        }
    }
}
