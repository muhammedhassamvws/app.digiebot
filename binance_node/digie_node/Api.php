<?php
/**
 *
 */
class Api extends CI_Controller {
    function __construct() {
        parent::__construct();

        // ini_set("display_errors", E_ALL);
        // error_reporting(E_ALL);
        // Load Modal
        $this->load->model('admin/mod_login');
        $this->load->model('admin/mod_users');
        $this->load->model('admin/mod_dashboard');
        $this->load->model('admin/mod_coins');
        $this->load->model('admin/mod_candel');
        $this->load->model('admin/mod_market');
        $this->load->model('admin/mod_barrier_trigger');
        $this->load->model('admin/mod_balance');
        $this->load->model('admin/mod_api');
        $this->load->model('admin/mod_api_services');
    }
    public function get_coin_meta() {
        list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTH'], 6)));
        $username = $this->input->server('PHP_AUTH_USER');
        $password = $this->input->server('PHP_AUTH_PW');

        if ($username == 'digiebot' && $password == 'digiebot') {
            $coin = $this->input->post('symbol');
            $ip = getenv('HTTP_CLIENT_IP') ?:
            getenv('HTTP_X_FORWARDED_FOR') ?:
            getenv('HTTP_X_FORWARDED') ?:
            getenv('HTTP_FORWARDED_FOR') ?:
            getenv('HTTP_FORWARDED') ?:
            getenv('REMOTE_ADDR');

            if ($ip == '58.65.164.72' || true) {
                $this->load->model('admin/mod_api');
                $coin_meta = $this->mod_api->get_all_coin_meta($coin);
                $message = $coin_meta;
                $type = '200';
                $this->response($message, $type);
            } else {
                $message = 'You are not allowed To Access this';
                $type = '403';
                $this->response($message, $type);
            }

        } else {

            $message = 'Sorry You are not Authorized';
            $type = '401';
            $this->response($message, $type);

            //echo $orders_arr_arr = $this->mod_coins_info->save_coins_info();
        }

    } //end Coin meta Function

    public function dashboard_api() {

        list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTH'], 6)));

        $username = $this->input->server('PHP_AUTH_USER');
        $password = $this->input->server('PHP_AUTH_PW');

        if ($username == 'digiebot' && $password == 'digiebot' || true) {
            $coin = $this->input->post('symbol');
            $user_id = $this->input->post('user_id');
            $ip = getenv('HTTP_CLIENT_IP') ?:
            getenv('HTTP_X_FORWARDED_FOR') ?:
            getenv('HTTP_X_FORWARDED') ?:
            getenv('HTTP_FORWARDED_FOR') ?:
            getenv('HTTP_FORWARDED') ?:
            getenv('REMOTE_ADDR');

            if ($ip == '58.65.164.72' || true) {
                //Fetching Market Buy Depth
                $market_buy_depth_data = $this->mod_api->get_market_buy_depth($coin);
                $data['market_buy_depth_arr'] = $market_buy_depth_data['fullarray'];

                $market_value = $market_buy_depth_data['market_value'];
                $data['market_value'] = num($market_value);

                //Fetching Market Sell Depth
                $market_sell_depth_data = $this->mod_api->get_market_sell_depth($coin);

                $data['market_sell_depth_arr'] = $market_sell_depth_data['fullarray'];

                //Fetching Market History
                $market_history_arr = $this->mod_api->get_market_history($coin);
                $data['market_history_arr'] = $market_history_arr;

                // $global_symbol = $this->session->userdata('global_symbol');
                $currncy = str_replace('BTC', '', $coin);
                $data['currncy'] = $currncy;

                $data['is_bnb_balance'] = $this->check_user_bnb_balance();
                $message = $data;
                $type = '200';
                $this->response($message, $type);
            } else {
                $message = 'You are not allowed To Access this';
                $type = '403';
                $this->response($message, $type);
            }

        } else {

            $message = 'Sorry You are not Authorized';
            $type = '401';
            $this->response($message, $type);

            //echo $orders_arr_arr = $this->mod_coins_info->save_coins_info();
        }

    } //end Coin meta Function
    public function check_user_bnb_balance($user_id) {
        $search_criteria['coin_symbol'] = 'BNBBTC';
        $search_criteria['user_id'] = $user_id;
        $this->mongo_db->where($search_criteria);
        $data = $this->mongo_db->get('user_wallet');
        $row = iterator_to_array($data);
        $resp = 'NO';
        if (!empty($row)) {
            $coin_balance = $row[0]['coin_balance'];
            if ($coin_balance >= 0.5) {
                $resp = 'YES';
            }
        } else {
            $resp = 'YES';
        }
        return $resp;
    } //End of check_user_bnb_balance
    public function get_coin_meta_percentile() {
        list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTH'], 6)));
        $username = $this->input->server('PHP_AUTH_USER');
        $password = $this->input->server('PHP_AUTH_PW');

        if ($username == 'digiebot' && $password == 'digiebot') {
            $coin = $this->input->post('symbol');
            $ip = getenv('HTTP_CLIENT_IP') ?:
            getenv('HTTP_X_FORWARDED_FOR') ?:
            getenv('HTTP_X_FORWARDED') ?:
            getenv('HTTP_FORWARDED_FOR') ?:
            getenv('HTTP_FORWARDED') ?:
            getenv('REMOTE_ADDR');

            if ($ip == '58.65.164.72' || true) {
                $this->load->model('admin/mod_api');
                $coin_meta = $this->mod_api->get_all_coin_meta_percentile($coin);
                ksort($coin_meta);
                $message = $coin_meta;
                $type = '200';
                $this->response($message, $type);
            } else {
                $message = 'You are not allowed To Access this';
                $type = '403';
                $this->response($message, $type);
            }

        } else {

            $message = 'Sorry You are not Authorized';
            $type = '401';
            $this->response($message, $type);

            //echo $orders_arr_arr = $this->mod_coins_info->save_coins_info();
        }

    } //end Coin meta Function

    public function get_user_orders() {
        //Basic ZGlnaWVib3Q6ZGlnaWVib3Q=
        $username = $this->input->server('PHP_AUTH_USER');
        $password = $this->input->server('PHP_AUTH_PW');

        if (($username == 'digiebot' && $password == 'digiebot') || ture) {
            $ip = getenv('HTTP_CLIENT_IP') ?:
            getenv('HTTP_X_FORWARDED_FOR') ?:
            getenv('HTTP_X_FORWARDED') ?:
            getenv('HTTP_FORWARDED_FOR') ?:
            getenv('HTTP_FORWARDED') ?:
            getenv('REMOTE_ADDR');

            if ($ip == '58.65.164.72' || true) {
                $admin_id = $this->input->post('user_id');
                $start_date = $this->input->post('start_date');
                $end_date = $this->input->post('end_date');
                $status = $this->input->post('status');

                $this->load->model('admin/mod_api');
                $user_orders = $this->mod_api->get_all_user_orders($admin_id, $start_date, $end_date, $status);
                $message = $user_orders;
                $type = '200';
                $this->response($message, $type);
            } else {
                $message = 'You are not allowed To Access this';
                $type = '403';
                $this->response($message, $type);
            }

        } else {

            $message = 'Sorry You are not Authorized';
            $type = '401';
            $this->response($message, $type);

            //echo $orders_arr_arr = $this->mod_coins_info->save_coins_info();
        }

    } //end get_user_orders

    public function get_candles() {
        //$global_coins = $this->triggers_trades->list_system_global_coin();
        $coin_symbol = strtoupper($this->input->post('coin'));

        // if($coin_symbol == ''){
        //     $message = 'coin symbol required';
        //     $type    = '403';
        //     $this->response($message, $type);
        // }else if(!in_array($coin_symbol,$global_coins)){
        //     $message = 'Coin not registered with Digieboat';
        //     $type    = '403';
        //     $this->response($message, $type);
        // }

        $required_fld_arr['coin'] = $coin_symbol;
        $candle_date = $this->input->post('candle_date');
        $where['coin'] = $coin_symbol;

        if ($candle_date != '') {
            $start_date = date('Y-m-d 00:00:00', strtotime($candle_date));
            $end_date = date('Y-m-d 23:59:59', strtotime($candle_date));
            $start_date = $this->mongo_db->converToMongodttime($start_date);
            $end_date = $this->mongo_db->converToMongodttime($end_date);
            $where['timestampDate'] = array('$gte' => $start_date, '$lte' => $end_date);
        }

        $this->mongo_db->where($where);
        $data = $this->mongo_db->get('market_chart');
        $data = (array) iterator_to_array($data);
        //$data = $data[0];

        // unset($data['_id']);
        // unset($data['timestampDate']);
        // unset($data['openTime_human_readible']);
        // unset($data['closeTime_human_readible']);
        // unset($data['human_readible_dateTime']);
        // unset($data['global_swing_status']);
        // unset($data['global_swing_parent_status']);
        // unset($data['triggert_type']);
        // unset($data['trigger_status']);

        $data = (array) $data;
        $message = $data;
        $type = '200';
        $this->response($message, $type);
    } //End of get_candles

    public function get_all_coins() {

        $this->load->model('admin/mod_api_services');
        $this->load->model('admin/mod_market');
        $this->load->model('admin/mod_dashboard');
        $user_id = $this->input->post('admin_id');
        list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTH'], 6)));
        $username = $this->input->server('PHP_AUTH_USER');
        $password = $this->input->server('PHP_AUTH_PW');


        $header = apache_request_headers(); 
        $exploded = explode(':', base64_decode(substr($header["Auth"], 6)), 2);
        if (2 == \count($exploded)) {
            list($un, $pw) = $exploded;
        }


        if ( ($username != 'digiebot' && $password != 'digiebot') || ($un !='digiebot' || $pw !='digiebot')) {
            $message = 'Sorry You are not Authorized';
            $type = '401';
            $this->response($message, $type);

            //echo $orders_arr_arr = $this->mod_coins_info->save_coins_info();
        }

        $coin_arr = $this->mod_api_services->get_all_coins($user_id);
        if (count($coin_arr) > 0) {

            $message = array(
                'status' => TRUE,
                'data' => $coin_arr,
                'message' => 'Coins Fetched successfully.',
            );
            $type = '200';
            $this->response($message, $type);
        } else {
            $message = array(
                'status' => FALSE,
                'message' => 'No Data Found',
            );
            $type = '404';
            $this->response($message, $type);
        }

    } //end get_all_coins_post

    public function get_coin_current_market_value() {
        list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTH'], 6)));
        $username = $this->input->server('PHP_AUTH_USER');
        $password = $this->input->server('PHP_AUTH_PW');

        if ($username != 'digiebot' || $password != 'digiebot') {
            $message = 'Sorry You are not Authorized';
            $type = '401';
            $this->response($message, $type);

            //echo $orders_arr_arr = $this->mod_coins_info->save_coins_info();
        }
        $symbol = $this->input->post('symbol');
        $this->load->model('admin/mod_dashboard');

        $market_value = $this->mod_dashboard->get_market_value($symbol);
        $message = array(
            'status' => TRUE,
            'data' => array('market_value' => $market_value),
            'message' => 'Market Value Fetched successfully.',
        );
        $type = '200';
        $this->response($message, $type);
    }

    public function get_notations() {
        list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTH'], 6)));
        $username = $this->input->server('PHP_AUTH_USER');
        $password = $this->input->server('PHP_AUTH_PW');

        if ($username != 'digiebot' || $password != 'digiebot') {
            $message = 'Sorry You are not Authorized';
            $type = '401';
            $this->response($message, $type);

            //echo $orders_arr_arr = $this->mod_coins_info->save_coins_info();
        }
        $this->load->model("admin/mod_dashboard");
        $global = $this->input->post('symbol');
        $min_not = get_min_notation($global);
        $market_value = $this->mod_dashboard->get_market_value($global);

        $per = $min_not / $market_value;
        $new_width = ($per) * 1.20;
        $new_market = (0.015 / (float) $market_value);

        $currency = 'bitcoin';
        $url = 'https://api.coinmarketcap.com/v1/ticker/' . $currency . '/?convert=USD';
        //Use file_get_contents to GET the URL in question.
        $contents = file_get_contents($url);
        $price = 1;
        //If $contents is not a boolean FALSE value.
        if ($contents !== false) {

            $result = json_decode($contents);
            $price_usd = $result[0]->price_usd;

            $convertamount = $price_usd * $price;
            $convertamount = round($convertamount, 5);
        }

        $message = array(
            'status' => TRUE,
            'data' => array(
                'min_notation' => $new_width,
                'max_notation' => $new_market,
                'usd_amount' => $convertamount,
                'market_value' => $market_value),
            'message' => 'Fetched Successfully ',
        );
        $type = '200';
        $this->response($message, $type);
    }

    public function response($message, $type) {

        $response = array('HTTP Response' => $type, 'Message' => $message);

        echo json_encode($response);
        exit;

    } /**End of response ***/
}