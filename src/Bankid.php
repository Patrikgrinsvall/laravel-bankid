<?php

namespace Patrikgrinsvall\LaravelBankid;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Bankid
{
    const BANKID_CODES = [
        "INVALID_PARAMETERS"        => "INVALID_PARAMETERS",
        "ALREADY_IN_PROGRESS"       => "ALREADY_IN_PROGRESS",
        "INTERNAL_ERROR"            => "INTERNAL_ERROR",
        "OUTSTANDING_TRANSACTION"   => "OUTSTANDING_TRANSACTION",
        "NO_CLIENT"                 => "NO_CLIENT",
        "STARTED"                   => "STARTED",
        "USER_SIGN"                 => "USER_SIGN",
        "COMPLETE"                  => "COMPLETE",
        "USER_CANCEL"               => "USER_CANCEL",
        "CANCEL"                    => "CANCELLED",
        "EXPIRED_TRANSACTION"       => "EXPIRED_TRANSACTION",
    ];



    const ERROR_RESPONSE_CODES      = [500, 501, 400, 401, 403];
    const SUCCESS_RESPONSE_CODES    = [200, 201];

    public function check_configuration()
    {
        if(empty(config("bankid.ENDPOINT")))
            throw new \Exception('Environment variable ENDPOINT missing. Bankid endpoint not specified', 2);
        if(empty(config("bankid.SSL_CERT")))
            throw new \Exception('Environment variable SSL_CERT missing. Bankid local certificate not configured', 2);
        if(empty(config("bankid.SSL_KEY")))
            throw new \Exception('Environment variable SSL_KEY missing. Bankid key file not configured', 2);
        if(empty(config("bankid.SSL_KEY_PASSWORD")))
            throw new \Exception('Environment variable SSL_KEY_PASSWORD missing. Bankid key file password not configured', 2);
        if(empty(config("bankid.CA_CERT")))
            throw new \Exception('Environment variable CA_CERT missing. CA cert not specified', 2);

    }

    /**
     * Collects a bankid response
     */
    public function Collect(Request $request)
    {
        $function = "/collect";
        $orderRef = $request['orderRef'];
        $type = $request['type'];

        // @todo start laravel cookie session.

        if($request['autoallow'] == true) {
            $user = array(
                'name' => 'Anders Andersson',
                'status' => "complete",
                'personalNumber' => "198211121234",
                'givenName' => "Anders",
                'surname' => "Andersson",
                'notAfter' => "2025-01-01T00:00:00.000",
                'orderRef'=> $request['orderRef'],
                'type' => $request['type'],
                'signature' => "<xml><testsignature/></xml>"
            );
/*
            $transaction = new Transaction([
                'user_personal_number' => $user['personalNumber'],
                'deal_id' => null,
                'type' => 'bankid_' . $user['type'],
                'statement' => $request['orderRef']]
            );
            $transaction->save();
            */
            session($user);
            return $user;
        } else {
            // real collect.
            $postdata = [
                "orderRef" => $orderRef
              ];
            $response = $this->bankIdRequest("/collect", $postdata);
            Log::debug("Bankid response: ".print_r($response,1));
            $message = isset($response['hintCode']) ? $response['hintCode'] : "";
            if($response['status'] == "complete") {

                $niceResponse = [
                    'name' => $response['completionData']['user']['name'],
                    'status' => $response['status'],
                    'personalNumber' => $response['completionData']['user']['personalNumber'],
                    'givenName' => $response['completionData']['user']['givenName'],
                    'surname' => $response['completionData']['user']['surname'],
                    'orderRef'=> $response['orderRef'],
                    'type'    => $type,
                    'signature' => isset($response['completionData']['user']['signature'])?$response['completionData']['user']['signature']:$response['completionData']['signature'],
                ];
                $niceResponse['loggedin'] = true;
                session($niceResponse);
/*
                $transaction = new Transaction([
                    'user_personal_number' => $response['completionData']['user']['personalNumber'],
                    'deal_id' => null,
                    'type' => 'bankid_' . $type,
                    'statement' => $response['orderRef']]
                );
                $transaction->save();
                */
                return $niceResponse;
            }
            elseif($response['status'] == "pending")  return ['status' => "pending"];
            else return response()->json(['status' => 'error', 'type' => $type, 'message' => $message], 500);

        }

    }

    /**
     * initiates an authentication
     */
    public function Authenticate($personalNumber, $endUserIp = null)
    {
        $validation = Validator::make($personalNumber, [
            'personalNumber'    =>  'required|digits:12'
        ]);

        if($validation->fails()) {
            return ['status' => 'invalid pnr or ip'];
        }

        if (empty($endUserIp) && array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER))
            $endUserIp = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else
            $endUserIp = $_SERVER['REMOTE_ADDR'];


        if(empty($endUserIp))
            throw new \Exception('Could not get end user ip which is required');

        $postdata = [
            'personalNumber'    => $personalNumber,
            'endUserIp'         => $endUserIp
        ];

        $response = $this->bankIdRequest("/auth", $postdata);
        Log::debug("bankid response:" . print_r($response, 1));
        if(isset($response['status']) && $response['status'] =='error')
            return response()->json([
                'status'    => 'error',
                'message'   => 'Bankid returned an error. Maybe you tried to start twice or something else weird happend. Please try again.'],self::ERROR_RESPONSE_CODE);

        if( isset($response['errorCode']) &&
            $response['errorCode'] == 'alreadyInProgress')
                return response()->json([
                    'status'    => 'error ',
                    'message'   => 'You had already started a login. Now its canceled. Please try again.'
                ], self::ERROR_RESPONSE_CODES[0]);

        if(!isset($response['orderRef']))
            return response()->json([
                'status'    => 'error',
                'message'   => 'Did not get orderref, check server logs'
            ], self::ERROR_RESPONSE_CODES[0]);

        return response()->json([
            'orderRef' => $response['orderRef']
        ], 200);
    }

    /**
     * initiates an authentication
     */
    public function Sign(Request $request)
    {

        $bankid = config('bankid');
        $data = $request->all();
        Log::debug("incoming" . print_r($data,1));
        $validation = Validator::make($request->all() ,[
            'personalNumber'    =>  'required|digits:12'
        ]);

        if($validation->fails()) {
            return ['status' => 'invalid pnr or ip'];
        }

        if (isset($request['endUserIp'])){
            $endUserIp = $request['endUserIp'];
        } elseif(array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
            $endUserIp = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else {
            $endUserIp = $_SERVER['REMOTE_ADDR'];
        }

        if($endUserIp == "127.0.0.1") {
            $endUserIp = "158.30.1.55";
        }

        //$userNonVisibleData = base64_encode($request['userNonVisibleData']);
        $userVisibleData = base64_encode($request['userVisibleData']);
        $userNonVisibleData = $userVisibleData;

        $postdata = [
            'personalNumber' => (string)$request['personalNumber'],
            'endUserIp' => $endUserIp,
            'userVisibleData' => $userVisibleData,
            'userNonVisibleData' => $userNonVisibleData
        ];
        $response = $this->bankIdRequest("/sign", $postdata); // 'Bankid returned an error. Maybe you tried to start twice or something else weird happend. Please try again.'

        if(isset($response['errorCode']) && $response['errorCode'] =='alreadyInProgress') return response()->json(['status' => 'error ','message' => 'Login already started on with the same personal number. Try again..'], self::ERROR_RESPONSE_CODE);
        if(isset($response['status']) && $response['status'] =='error') return response()->json(['status' => 'error ','message' => 'Bankid returned an unknown error. Maybe a connection error. Please try again.'], self::ERROR_RESPONSE_CODE);
        if(!isset($response['orderRef'])) return response()->json(['status' => 'error','message' => 'Did not get orderref, check server logs'], self::ERROR_RESPONSE_CODE);
        Log::debug("reposne" . $response['orderRef']);
        return [
            'orderRef' => $response['orderRef']
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
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $this->endpoint . $function);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS , $postdata);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_SSLCERT, base_path($this->ssl_cert));
        curl_setopt($ch, CURLOPT_SSLKEY,  base_path($this->ssl_key));
        curl_setopt($ch, CURLOPT_KEYPASSWD, $this->ssl_key_password);
        curl_setopt($ch, CURLOPT_CAINFO, base_path($this->ca_cert));
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error_msg = curl_error($ch);
        Log::debug("->>". base_path($this->ssl_key).", -- ". $this->ssl_key_password." --".base_path($this->ssl_cert));
        Log::debug("Request ({$this->endpoint}{$function}): ".$postdata. "bankid response:" . print_r($response,1) . print_r($info,1) . print_r($error_msg,1));

        if(strlen($error_msg) > 1) {
            throw new \Exception("Error when backend talking with bankid.." . print_r($error_msg,1));
        }

        $response = json_decode($response, true);
        if(!empty($response['errorCode'])) {
            return [
                'status' => 'error',
                'errorCode' => $response['errorCode']
            ];
        }

        if(in_array($info['http_code'], self::SUCCESS_RESPONSE_CODES) === false)
            throw new \Exception("Serious error! Bankid didnt return http 200: " . print_r($info,1));


        return $response;
    }
}
