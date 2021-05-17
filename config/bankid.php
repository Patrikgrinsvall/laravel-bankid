<?php
return [
    // Path to client certificate
    'SSL_CERT'          =>  env('BANKID_SSL_CERT',          base_path("storage/certs/bankidtest/bankidtest.crt.pem")),

    // Path to bank CA cert. This is currently disabled
    // since it needs to be bundled with all
    // other certificates
    'CA_CERT'           =>  env('BANKID_CA_CERT',           base_path("storage/certs/cacert-2020-01-01.pem")),

    // Path to bankid endpoint
    // prod = https://appapi2.bankid.com/rp/v5.1
    'ENDPOINT'          =>  env('BANKID_ENDPOINT',          "https://appapi2.test.bankid.com/rp/v5.1"),

    // Path to client certificate
    'SSL_KEY'           =>  env("BANKID_SSL_KEY",           base_path("storage/certs/bankidtest/bankidtest.key.pem")),

    // Password to private key
    'SSL_KEY_PASSWORD'  =>  env("BANKID_SSL_KEY_PASSWORD",  "qwerty123"),

    // Login completed
    'completeUrl'       =>  env('BANKID_COMPLETE_URL',      '/member/index'),

    // User press Cancel button
    'cancelUrl'         =>  env('BANKID_CANCEL_URL',        '/'),

    // Show setup instructions
    'SETUP_COMPLETE'    =>  true,

    // If you dont want to go through the process of logging in each time when developing,
    // Adding personal number here will make this always logged in.
    "personalNumber"    => "197410092527"
];
