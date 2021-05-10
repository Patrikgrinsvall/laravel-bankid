<?php

namespace Patrikgrinsvall\LaravelBankid;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use BankidUser;
#use \Composer\InstalledVersions;
class Bankid
{
    public function check_configuration()
    {
        $this->debugbar = false;
        if(\Composer\InstalledVersions::isInstalled('barryvdh/laravel-debugbar')) {
            $this->debugbar = true;
            AliasLoader::getInstance(['Debugbar'=> \Barryvdh\Debugbar\Facade::class]);
        }

        if(empty(config("bankid.ENDPOINT"))) {
            throw new \Exception('Environment variable ENDPOINT missing. Bankid endpoint not specified', 2);
        }
        if (empty(config("bankid.SSL_CERT"))) {
            throw new \Exception('Environment variable SSL_CERT missing. Bankid local certificate not configured', 2);
        }
        if (empty(config("bankid.SSL_KEY"))) {
            throw new \Exception('Environment variable SSL_KEY missing. Bankid key file not configured', 2);
        }
        if (empty(config("bankid.SSL_KEY_PASSWORD"))) {
            throw new \Exception('Environment variable SSL_KEY_PASSWORD missing. Bankid key file password not configured', 2);
        }
        if (empty(config("bankid.CA_CERT"))) {
            throw new \Exception('Environment variable CA_CERT missing. CA cert not specified', 2);
        }
    }

    private $debugbar = null;

    /**
     * Log function
     *
     * @param [type] $message
     * @param string $level
     * @return void
     */
    function log($message, $level = "error") {
        if(config("app.debug") != true) return;
        if($this->debugbar == true ) {
            if(is_string($message) === false) {
                $message = print_r($message,1);
            }
            \Debugbar::addMessage($message, "info");
            Log::log($level, $message);
        } else {
            Log::log($level, $message);
        }
    }

    /**
     * Collects a bankid response
     */
    public function Collect(array $request)
    {
        if ($request['orderRef'] === null) {
            return ['status' => 'error', 'message' => __('bankid::message.RFA22')];
        }

        $response = $this->bankIdRequest("/collect", [
            "orderRef" => $request['orderRef'],
        ]);

        if($response['status'] === 'complete') {
            $response['loggedin'] = 'true';
            $user = new BankidUser($response['user']);
            Auth::login($user, true);
            return $response;
        }

        return $response;
    }

    /**
     * initiates an authentication
     */
    public function Authenticate($personalNumber, $endUserIp = null)
    {
        if (empty($endUserIp) && array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER))
            $endUserIp = $_SERVER["HTTP_X_FORWARDED_FOR"];
         else $endUserIp = $_SERVER['REMOTE_ADDR'];

        $validation = Validator::make([
            'pnr' => $personalNumber,
            'ip' => $endUserIp,
        ], [
            'pnr' => 'required|digits:12',
            'ip' => 'required|ip',
        ], [
            'need 12 digit',
            'need ip',
        ]);

        if (empty($endUserIp)) throw new \Exception('Could not get end user ip which is required');


        $postdata = [
            'personalNumber' => $personalNumber,
            'endUserIp' => $endUserIp,
        ];

        $response = $this->bankIdRequest("auth", $postdata);
        return $response;
    }

    /**
     * Do a bankid request and returns response
     *
     * function = authenticate / collect
     * data     = array with data,
     */
    public function bankIdRequest($function, $data)
    {
        $this->log("requesting");
        $options['verify']                      = false;//base_path(config("bankid.CA_CERT"));
        $options['cert']                        = base_path(config("bankid.SSL_CERT"));
        $options['ssl_key'][]                   = base_path(config("bankid.SSL_KEY"));
        $options['ssl_key'][]                   = config("bankid.SSL_KEY_PASSWORD");
        $this->log("BANKID body: ".json_encode($data), "error");
        $response = Http::withOptions($options)
                        ->withBody(json_encode($data),'application/json')
                        ->post(config("bankid.ENDPOINT").$function);
        $this->log("Bankid said: ");
        $response = $response->json();
        $this->log(print_r($response,1));
        if(isset($response['status']) && $response['status'] == "complete") {
            $newResponse                = array_merge($response['completionData']['user'],
                                            $response['completionData']['device']);
            $newResponse['signature']   = $response['completionData']['signature'];
            $newResponse['orderRef']    = $response['orderRef'];
            $newResponse['status']      = $response['status'];
        return $newResponse;
        }
        if(isset($response['orderRef']) && !isset($response['status'])) {
            $response['status'] = 'collect';
        }
        if(isset($response['errorCode'])) {
            $response['message'] = __("bankid::messages." . $response['details']);
            $response['status'] = $response['errorCode'];
        }

        if(isset($response['hintCode'])) {
            $response['message'] = __("bankid::messages." . $response['hintCode']);
        }
        return $response;
    }

  /**
     * initiates an authentication
     */
    public function Sign(Request $request)
    {
        $bankid = config('bankid');
        $data = $request->all();
        Log::debug("incoming" . print_r($data, 1));
        $validation = Validator::make($request->all(), [
            'personalNumber' => 'required|digits:12',
        ]);

        if ($validation->fails()) {
            return ['status' => 'invalid pnr or ip'];
        }

        if (isset($request['endUserIp'])) {
            $endUserIp = $request['endUserIp'];
        } elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $endUserIp = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $endUserIp = $_SERVER['REMOTE_ADDR'];
        }

        if ($endUserIp == "127.0.0.1") {
            $endUserIp = "158.30.1.55";
        }

        //$userNonVisibleData = base64_encode($request['userNonVisibleData']);
        $userVisibleData = base64_encode($request['userVisibleData']);
        $userNonVisibleData = $userVisibleData;

        $postdata = [
            'personalNumber' => (string)$request['personalNumber'],
            'endUserIp' => $endUserIp,
            'userVisibleData' => $userVisibleData,
            'userNonVisibleData' => $userNonVisibleData,
        ];
        $response = $this->bankIdRequest("/sign", $postdata); // 'Bankid returned an error. Maybe you tried to start twice or something else weird happend. Please try again.'

        if (isset($response['errorCode']) && $response['errorCode'] == 'alreadyInProgress') {
            return response()->json(['status' => 'error ','message' => 'Login already started on with the same personal number. Try again..'], self::ERROR_RESPONSE_CODES[0]);
        }
        if (isset($response['status']) && $response['status'] == 'error') {
            return response()->json(['status' => 'error ','message' => 'Bankid returned an unknown error. Maybe a connection error. Please try again.'], self::ERROR_RESPONSE_CODES[0]);
        }
        if (! isset($response['orderRef'])) {
            return response()->json(['status' => 'error','message' => 'Did not get orderref, check server logs'], self::ERROR_RESPONSE_CODES[0]);
        }

        Log::debug("response" . $response['orderRef']);

        return [
            'orderRef' => $response['orderRef'],
        ];
    }
}
