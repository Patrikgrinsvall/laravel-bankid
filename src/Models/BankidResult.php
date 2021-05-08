<?php

namespace Patrikgrinsvall\LaravelBankid\Models;

use ArrayAccess;

#use Illuminate\Http\Client\Response;
#use \GuzzleHttp\Psr7\Response as HttpResponse;
class BankidResult implements ArrayAccess
{
    public $response = [];
    public function __construct($response = null)
    {
        $this->response = empty($response) ? ['status' => 'waiting', 'errorCode' => '', 'details' => ''] : $response;
        /*
        if(!is_a($response, "\GuzzleHttp\Psr7\Response")) {
            $response = new HttpResponse(200, [], $response);
        }
        parent::__construct($response);
        */
    }
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->response[] = $value;
        } else {
            $this->response[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->response[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->response[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->response[$offset]) ? $this->response[$offset] : null;
    }
}
