<?php
namespace Patrikgrinsvall\LaravelBankid;

use Closure;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Session;

class BankidMiddleware {
    public function handle(Request $request, Closure $next)
    {
        if(!$request->session->isStarted()) {
            $request->session->start();
        }
        if($request->session->has('user') && $user = $request->session->get('user')) {
            if(isset($user['personal_number'])) {
                return $next($request);
            }
        }

    }
}
