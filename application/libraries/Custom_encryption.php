<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class Custom_encryption {

    function __construct() {
    }

    function encrypt_decrypt($action, $string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = 'crypto_trading_digiebot_2019_world_top_bot';
        $secret_iv = 'digiebotistheworldbestcryptocurrencybot';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    public function encrypt_string($string) {
        $encrypt_method = "AES-256-CBC";

        $secret_key = 'crypto_trading_digiebot_2019_world_top_bot';
        $secret_iv = 'digiebotistheworldbestcryptocurrencybot';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);

        return $output;
    }

    public function decrypt_string($string) {
        $encrypt_method = "AES-256-CBC";

        $secret_key = 'crypto_trading_digiebot_2019_world_top_bot';
        $secret_iv = 'digiebotistheworldbestcryptocurrencybot';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

        return $output;
    }

}
?>