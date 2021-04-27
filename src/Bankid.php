<?php

namespace Patrikgrinsvall\LaravelBankid;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        if (empty(config("bankid.ENDPOINT"))) {
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

    public function complete()
    {
        return redirect('/bankid/complete');
    }

    public function cancel()
    {
        return redirect('/bankid/cancel');
    }

    public function error()
    {
        return redirect('/bankid/complete');
    }

    /**
     * Collects a bankid response
     */
    public function Collect(array $request)
    {
        $function = "/collect";
        $type = isset($request['type']) ? $request['type'] : "auth";
        $autoAllow = (isset($request['autoallow']) && $request['autoallow'] == true) ? true : false;


//         if($autoAllow == true) {
//             Log::error("autoallow");
//             $user = array(
//                 'name' => 'Anders Andersson',
//                 'status' => "complete",
//                 'personalNumber' => "198211121234",
//                 'givenName' => "Anders",
//                 'surname' => "Andersson",
//                 'notAfter' => "2025-01-01T00:00:00.000",
//                 'orderRef'=> $request['orderRef'],
//                 'type' => $type,
//                 'signature' => "<xml><testsignature/></xml>"
//             );
        // /*
//             $transaction = new Transaction([
//                 'user_personal_number' => $user['personalNumber'],
//                 'deal_id' => null,
//                 'type' => 'bankid_' . $user['type'],
//                 'statement' => $request['orderRef']]
//             );
//             $transaction->save();
//             */
//             session($user);
//             return $user;
//         } else {
        // real collect.
        if ($request['orderRef'] === null) {
            return ['status' => 'error', 'message' => 'missing order'];
        }
        $postdata = [
                "orderRef" => $request['orderRef'],
              ];
        $response = $this->bankIdRequest("/collect", $postdata);
        $response = [
                'name' => isset($response['completionData']) ? $response['completionData']['user']['name'] : null,
                'status' => $response['status'],
                'personalNumber' => isset($response['completionData']) ? $response['completionData']['user']['personalNumber'] : null,
                'givenName' => isset($response['completionData']) ? $response['completionData']['user']['givenName'] : null,
                'surname' => isset($response['completionData']) ? $response['completionData']['user']['surname'] : null,
                'orderRef' => $request['orderRef'],
                'type' => $type,
                'signature' => isset($response['completionData'])?$response['completionData']['signature']:null,
            ];

        Log::error("Bankid response before collapse: ".print_r($response, 1));
        //$response = Arr::collapse($response);
        //$response = $this->flatten([$response]);
        Log::error("Bankid response: ".print_r($response, 1));

        session($response);






        return $response;

        //}
    }

    public $flattened = [];

    public function flatten(array $array)
    {
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $member) {
                if (! is_array($member)) {
                    $this->flattened[] = $member;
                } else {
                    $this->flatten($member);
                }
            }
        }

        return $this->flattened;
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

        $response = $this->bankIdRequest("/auth", $postdata);
        Log::debug("bankid response:" . print_r($response, 1));
        $this->orderRef = $response['orderRef'] ?? null;
        if (isset($response[ 'orderRef' ])) {
            return [
                'status' => 'collect',
                'orderRef' => $response[ 'orderRef' ],
                'message' => 'Complete authentication on your other device',
            ];
        }

        if (isset($response[ 'status' ]) && $response[ 'status' ] == 'error') {
            return [
                'status' => 'error',
                'message' => 'Bankid returned an error. Maybe you tried to start twice or something else weird happend. Please try again.',
            ];
        }

        if (isset($response[ 'errorCode' ]) &&
            $response[ 'errorCode' ] == 'alreadyInProgress') {
            return [
                    'status' => 'error',
                    'message' => 'You had already started a login. Now its canceled. Please try again.',
                ];
        }

        Log::debug("Something we did not account for happened: " . print_r($response, 1));

        return [
            'status' => 'error',
            'message' => 'Temporary error, please refresh page and try again.',
        ];
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
        $postdata = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, config("bankid.ENDPOINT") . $function);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_SSLCERT, base_path(config("bankid.SSL_CERT")));
        curl_setopt($ch, CURLOPT_SSLKEY,  base_path(config("bankid.SSL_KEY")));
        curl_setopt($ch, CURLOPT_KEYPASSWD, config("bankid.SSL_KEY_PASSWORD"));
        curl_setopt($ch, CURLOPT_CAINFO, base_path(config("bankid.CA_CERT")));
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error_msg = curl_error($ch);
        Log::error("->>". base_path(config("bankid.SSL_CERT")).", -- ". config("bankid.SSL_KEY_PASSWORD")." --".base_path(config("bankid.SSL_KEY")));
        Log::error("Request (".config("bankid.ENDPOINT")."{$function}): ".$postdata. "bankid response:" . print_r($response, 1) . print_r($info, 1) . print_r($error_msg, 1));

        if (strlen($error_msg) > 1) {
            throw new \Exception("Error when backend talking with bankid.." . print_r($error_msg, 1));
        }

        $response = json_decode($response, true);
        if (! empty($response['errorCode'])) {
            return [
                'status' => 'error',
                'errorCode' => $response['errorCode'],
            ];
        }

        if (in_array($info['http_code'], self::SUCCESS_RESPONSE_CODES) === false) {
            throw new \Exception("Serious error! Bankid didnt return http 200: " . print_r($info, 1));
        }


        return $response;
    }
}
