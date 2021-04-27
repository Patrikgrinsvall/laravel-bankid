<?php
return [
    'SSL_CERT' => env('BANKID_SSL_CERT', base_path("storage/certs/bankidtest/bankidtest.crt.pem")),
    'CA_CERT' => env('BANKID_CA_CERT', base_path("storage/certs/cacert-2020-01-01.pem")),
    'ENDPOINT' => env('BANKID_ENDPOINT', "https://appapi2.test.bankid.com/rp/v5.1"),
    'SSL_KEY' => env("BANKID_SSL_KEY", base_path("storage/certs/bankidtest/bankidtest.key.pem")),
    'SSL_KEY_PASSWORD' => env("BANKID_SSL_KEY_PASSWORD", "qwerty123"),
    'complete' => [BankidController::class, 'complete'],
    'cancel' => [BankidController::class, 'cancel'],
];
