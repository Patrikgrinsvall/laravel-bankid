<?php

namespace Patrikgrinsvall\LaravelBankid;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use \Barryvdh\Debugbar\Facade;
use \Composer\InstalledVersions;

class Bankid
{
    const BANKID_CODES = [
        "INVALID_PARAMETERS" => "INVALID_PARAMETERS",
        "ALREADY_IN_PROGRESS" => "ALREADY_IN_PROGRESS",
        "INTERNAL_ERROR" => "INTERNAL_ERROR",
        "OUTSTANDING_TRANSACTION" => "OUTSTANDING_TRANSACTION",
        "NO_CLIENT" => "NO_CLIENT",
        "STARTED" => "STARTED",
        "USER_SIGN" => "USER_SIGN",
        "COMPLETE" => "COMPLETE",
        "USER_CANCEL" => "USER_CANCEL",
        "CANCEL" => "CANCELLED",
        "EXPIRED_TRANSACTION" => "EXPIRED_TRANSACTION",
    ];

    const ERROR_RESPONSE_CODES = [500, 501, 400, 401, 403];
    const SUCCESS_RESPONSE_CODES = [200, 201];

    public function check_configuration()
    {
        $this->debugbar = false;
        if(\Composer\InstalledVersions::isInstalled('barryvdh/laravel-debugbar')) {
            $this->debugbar = true;
            AliasLoader::getInstance(['Debugbar'=> \Barryvdh\Debugbar\Facade::class]);
            $this->log("Debugbar found");
        } else {
            $this->log("debugbar not found");
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

    private $orderRef = null;

    private $debugbar = null;

    function log($message, $level = "error") {
        if($this->debugbar == true){
            if(is_string($message) === false) {
                \Debugbar::addMessage("message is not string". print_r($message,1), "info");
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
        $function = "/collect";
        $type = isset($request['type']) ? $request['type'] : "auth";

        if ($request['orderRef'] === null) {
            return ['status' => 'error', 'message' => __('bankid.RFA22')];
        }
        $postdata = [
                "orderRef" => $request['orderRef'],
              ];
        $response = $this->bankIdRequest("/collect", $postdata);

        if($response['status'] === 'complete') {
            Auth::login($response);
            session($response);
        }

        return $response;

    }

    public function saveTransaction()
    {
        /*
                        $transaction = new Transaction([
                            'user_personal_number' => $response['completionData']['user']['personalNumber'],
                            'deal_id' => null,
                            'type' => 'bankid_' . $type,
                            'statement' => $response['orderRef']]
                        );
                        $transaction->save();
                        */
    }

    /**
     * initiates an authentication
     */
    public function Authenticate($personalNumber, $endUserIp = null)
    {
        if (empty($endUserIp) && array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $endUserIp = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $endUserIp = $_SERVER['REMOTE_ADDR'];
        }

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

        if (empty($endUserIp)) {
            throw new \Exception('Could not get end user ip which is required');
        }

        $postdata = [
            'personalNumber' => $personalNumber,
            'endUserIp' => $endUserIp,
        ];

        $response = $this->bankIdRequest("auth", $postdata);

        $this->log("after auth");
        $this->log($response);
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
        Log::debug("reposne" . $response['orderRef']);

        return [
            'orderRef' => $response['orderRef'],
        ];
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
        $bankIdResponse = Http::withOptions($options)
                        ->withBody(json_encode($data),'application/json')
                        ->post(config("bankid.ENDPOINT").$function);
        $this->log("before response");
        $this->log($bankIdResponse);
        $response = $bankIdResponse->collect()->toArray();

        if(Arr::has($response, 'status') && $response['status'] == "complete") {
            $response = $response->collect()->mapWithKeys(function($a) {
                return $a;
            });
        }
        $this->log("after response");
        $this->log($response);

        if(isset($response['orderRef']) && !isset($response['status'])) {
            $response['status'] = 'collect';
        }
        if(isset($response['errorCode'])) {
            $response['message'] = __($response['details']);
            $response['status'] = __('bankid.'.$response['errorCode']);
        }
        if(isset($response['hintCode'])) {
            $response['message'] = __('bankid.'.$response['hintCode']);
        }
        $this->log("after response");
        $this->log($response);

        return $response;
    }
}
