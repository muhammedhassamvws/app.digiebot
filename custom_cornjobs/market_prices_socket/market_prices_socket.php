<?php

$urls = array(
    'https://app.digiebot.com/admin/market_prices_socket/run_cron',

);

run_curl($urls);

function run_curl($urls) {
    if (!empty($urls)) {
        foreach ($urls as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            echo curl_exec($ch);
            curl_close($ch);
        } //end curl
    } //End of check empty
} //End of run_curl
