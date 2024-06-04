<?php
class mod_api_services extends CI_Model {

    function __construct() {

        parent::__construct();
        $this->load->model('admin/Mod_notifications');
    }

    //Validation of Login
    public function validate_credentials($username, $password) {

        // $search_Arr['username'] = $username;
        $search_Arr['username_lowercase'] = strtolower($username);
        $search_Arr['password'] = md5($password);
        //$search_Arr['mobile_app_password'] = md5($password);
        $search_Arr['status'] = (string) 0;
        $search_Arr['user_soft_delete'] = '0';
        //$search_Arr['app_enable'] = 'yes';

        $this->mongo_db->where($search_Arr);
        $get = $this->mongo_db->get('users');
        $row = iterator_to_array($get);

        if (count($row) > 0) {

            $this->update_login_time($row['_id']);

            return $row[0];
        }

    } //end function validate
    
    //Validation of Login
    public function send_confirm_email($user_id, $email_address) {

        $email = $email_address;

        $confirmation_code = $this->confirm_code_maker();
        $this->mongo_db->where(array('_id' => $user_id));
        $this->mongo_db->set(array('verification_code' => $confirmation_code));
        $this->mongo_db->update('users');

        $email_body = '<table width="100%" height="100%" cellspacing="0" cellpadding="0" border-collapse="0" border="0" bgcolor="#efefee" align="center">
        <tr>
            <td valign="top" align="center">
                <div style="min-width:320px !important; max-width:480px !important; margin:30px auto;" align="center">

                    <body style="margin: 0;padding: 0;background-color: #fff;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;height: 100% !important;width: 100% !important;">
                        <div id="transition_content">
                            <table width="100%" height="100%" cellspacing="0" cellpadding="0" border-collapse="0" border="0" bgcolor="#efefee" align="center">
                                <tr>
                                    <td valign="top" align="center">
                                        <div style="min-width:320px !important; max-width:460px !important; margin:60px auto;" align="center">

                                            <table bgcolor="#FFFFFF" border="0" width="100%" cellspacing="0" cellpadding="0" border-collapse="0" border-spacing="0" align="center">
                                                <tr>
                                                    <td colspan="2" valign="middle" align="center" style="font-size:27px;color:#fff; mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family:Helvetica Neue, Helvetica, Arial, sans-serif;padding:30px 0px;line-height:1.2;font-weight:bold;letter-spacing:1.6px;background:#2d68d5 url(http://app.digiebot.com/assets/images/digiebot.jpg) repeat-x center top;padding-top:120px;padding-bottom:40px;">
                                                    </td>
                                                    <tr>
                                                        <td colspan="2" bgcolor="#556edd" valign="top" align="center" style="padding:20px 5px; color:#FFFFFF; mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family:Helvetica Neue, Helvetica, Arial, sans-serif;font-size:15px;">Login Verification Code
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" valign="top" align="center" style="padding:20px; color:#181c60; mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family:Helvetica Neue, Helvetica, Arial, sans-serif;line-height:30px; background-color: rgba(85, 110, 221,0.1);">
                                                            <div style="width: 100%">Use The Following Code To Login in Digiebot
                                                            </div>
                                                            <div style="width: 100%">Your Login Verification Code is
                                                                <br> ' . $confirmation_code . '
                                                                <br>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" bgcolor="#FFFFFF" valign="top" align="center" style="padding:0px 30px 20px 30px; color:#181c60; mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; ">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" bgcolor="#FFFFFF" valign="top" align="left" style="padding:0px 30px 30px 30px; color:#000000;font-size:16px; mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; ">
                                                            Regards,
                                                            <br>
                                                            <strong>Digiebot Team</strong>
                                                            <br>
                                                            <a href="#">info@digiebot.com</a>
                                                            <table>
                                                                 <br><br><br>
                                                                <tr>
                                                                    <td>
                                                                    </td>
                                                                    <td>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" bgcolor="#FFFFFF" valign="top" align="center" style="padding:0px; color:#181c60; mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;font-family:Helvetica Neue, Helvetica, Arial, sans-serif;line-height:0px; ">
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2" bgcolor="#efefee" align="left" valign="top" style="background-color:#efefee;padding:25px;color:#1d1d1d;font-size:16px;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; line-height:28px; mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                        </td>
                                                    </tr>

                                            </table>

                                        </div>

                                    </td>
                                    </tr>
                                </tr>
                            </table>
                        </div>';
        $noreply_email = "noreply@digiebot.com";
        $email_from_txt = "Digiebot";
        $email_subject = "Confirmation Code";

        // $config['charset'] = 'utf-8';
        // $config['mailtype'] = 'html';
        // $config['wordwrap'] = TRUE;
        // $config['protocol'] = 'mail';

        $this->config->load('email', TRUE);
        $config = $this->config->item('email');

        $this->load->library('email', $config);

        $this->email->from($noreply_email, $email_from_txt);
        $this->email->to($email);
        $this->email->subject($email_subject);
        $this->email->message($email_body);


        //Send Email used amazon ses
        // $this->load->library('Amazon_ses_bulk_email');
        // $this->amazon_ses_bulk_email->send_bulk_email($html_message, $subject, $from, $to, $cc = '', $bcc = '', $title = '');
        // $email_sent = $this->amazon_ses_bulk_email->send_bulk_email($email_body, $email_subject, 'support@digiebot.com', $email, $cc = '', $bcc = '', $title = '');

        $new_body = 'Use The Following Code To Login in Digiebot <br>
                                Your Login Verification Code is <strong> ' . $confirmation_code . '</strong>';
		$email_sent = send_mail($user_id, $email_subject, $new_body);

        if ($email_sent) {
            return true;
        }

    }

    public function update_login_time($id) {
        $login_time = date("Y-m-d G:i:s");
        $upd_arr = array('last_login_datetime' => $this->mongo_db->converToMongodttime($login_time));

        $this->mongo_db->where(array("_id" => $id));
        $this->mongo_db->set($upd_arr);
        $this->mongo_db->update("users");

        return true;
    } //update_login_time

    public function confirm_code_maker() {
        $randstr = '';
        $length = 6;
        srand((double) microtime(TRUE) * 1000000);
        //our array add all letters and numbers if you wish
        $chars = array(
            '1', '2', '3', '4', '5',
            '6', '7', '8', '9', '0');

        for ($rand = 0; $rand < $length; $rand++) {
            $random = rand(0, count($chars) - 1);
            $randstr .= $chars[$random];
        }
        return $randstr;
    }

    public function get_google_code($user_id) {
        $this->mongo_db->where(array('_id' => $user_id));
        $user_response = $this->mongo_db->get('users');
        $user_array = iterator_to_array($user_response);

        return $user_array[0]['google_auth_code'];

    }

    public function get_verification_code($user_id) {
        $this->mongo_db->where(array('_id' => $user_id));
        $user_response = $this->mongo_db->get('users');
        $user_array = iterator_to_array($user_response);

        return $user_array[0]['verification_code'];
    }

    public function get_all_coins($id, $exchange) {

        // if($exchange == 'binance'){
        //     $this->mongo_db->sort(array('_id' => -1));
        //     $this->mongo_db->where(array('user_id' => ($id), 'symbol' => array('$nin' => array('', null, 'BTC', 'BNBBTC', 'NCASHBTC', 'POEBTC')), 'exchange_type' => 'binance'));
        //     $get_coins = $this->mongo_db->get('coins');
        //     $coins_arr = iterator_to_array($get_coins);
        //     return $coins_arr;
        // }else{
        //     $collection = 'coins_'.$exchange;
        //     $this->mongo_db->sort(array('_id' => -1));
        //     $this->mongo_db->where(array('user_id' => ($id), 'symbol' => array('$nin' => array('', null, 'BTC', 'BNBBTC'))));
        //     $get_coins = $this->mongo_db->get($collection);
        //     $coins_arr = iterator_to_array($get_coins);
        //     return $coins_arr;
        // }

        $collection_name = $exchange == "binance" ? "coins" : "coins_$exchange";

        $db = $this->mongo_db->customQuery();
        $pipeline = [ 
            [ 
                '$match' => [
                    "user_id"=>"$id",
                    "symbol"=>['$nin'=>["",null,"BTC","BNBBTC","NCASHBTC","POEBTC"]],
                ]
            ], 
            ['$lookup'=>[
                    "from"=>"$collection_name",
                    "let"=>["symbol1"=>'$symbol'],
                    "pipeline"=>[
                        [
                            '$match'=>[
                                '$expr'=>['$eq'=>['$symbol','$$symbol1']], 
                                "user_id"=>"global",
                            ]
                        ],
                        [
                            '$project'=> [
                                "_id"=> 0,
                                "coin_categories"=> [ '$ifNull'=> [ '$coin_categories', ["manual"] ] ]
                            ]
                        ]
                    ],
                    "as"=>"temp_categories"
                ]
            ],
            [
                '$addFields'=> [
                    "coin_categories"=> ['$arrayElemAt'=> ['$temp_categories', 0] ]
                ]
            ],
            [
                '$addFields'=> [
                    "coin_categories"=> '$coin_categories.coin_categories'
                ]
            ],
            [
                '$project'=> [
                    "temp_categories"=> 0
                ]
            ]
        ];

        if($exchange == 'binance'){
            $pipeline[0]['$match']['exchange_type'] = 'binance';
            $pipeline[1]['$lookup']['pipeline'][0]['$match']['exchange_type'] = 'binance';
        }
        $coins = $db->$collection_name->aggregate($pipeline);
        $coins = iterator_to_array($coins);

        return $coins;

    }

    public function get_coin_balance($coin, $user_id, $exchange) {

        if($exchange == 'binance'){
            $collection = 'user_wallet';
            $this->mongo_db->where(array('coin_symbol' => $coin, 'user_id' => (string) $user_id));
            $get_coin = $this->mongo_db->get($collection);
            $coin_arr = iterator_to_array($get_coin);
            $coin_arr = $coin_arr[0];
            return $coin_arr['coin_balance'];
        }else{
            $collection = 'user_wallet_'.$exchange;
            $this->mongo_db->where(array('coin_symbol' => $coin, 'user_id' => (string) $user_id));
            $get_coin = $this->mongo_db->get('user_wallet');
            $coin_arr = iterator_to_array($get_coin);
            $coin_arr = $coin_arr[0];
            return $coin_arr['coin_balance'];
        }
    }

    public function get_last_price($symbol) {
        $this->mongo_db->where(array('coin' => $symbol));
        $this->mongo_db->limit(1);
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('market_prices');

        foreach ($responseArr as $valueArr) {
            if (!empty($valueArr)) {
                $market_value = $valueArr['price'];
            }
        }
        return number_format($market_value, 8);
    }

    public function get_market_trades($symbol, $admin_id) {
        $db = $this->mongo_db->customQuery();

        $params = array(
            'symbol' => $symbol,
            'status' => 'FILLED',
            'is_sell_order' => 'yes',
            'admin_id' => $admin_id,
        );

        $resp = $db->buy_orders->count($params);
        return $resp;
    }

    public function get_24_hour_price_change($symbol) {
        //$date = date('Y-m-d H:i:s', strtotime('-24 hours'));
        //$this->mongo_db->where(array('coin' => $symbol,'time' => array('$gte' => $this->mongo_db->converToMongodttime($date))));
        $this->mongo_db->where(array('symbol' => $symbol));
        $res = $this->mongo_db->get('coin_price_change');
        $result_arr = iterator_to_array($res);

        return array('change' => num($result_arr[0]['priceChange']), 'percentage' => number_format($result_arr[0]['priceChangePercent'], 2));
    }

    public function calculate_score($news) {

        foreach ($news as $key => $value) {
            if ($value['score'] >= 0) {
                $psum = $psum + $value['score'];
            } else {
                $nsum = $nsum + $value['score'];
            }
            $count++;
        }
        $sum = $psum + (-1 * ($nsum));
        $x = $psum / $sum;
        $score_avg = round($x * 100);
        return $score_avg;
    }

    public function count_orders($status, $application_mode, $admin_id, $filter_array) {
        //Check Filter Data
        $session_post_data = $filter_array;

        $search_array = array('admin_id' => $admin_id, 'application_mode' => $application_mode);
        //$search_array = array('admin_id'=> $admin_id);

        if (!empty($filter_array)) {
            if ($session_post_data['filter_coin'] != "") {

                $symbol = $session_post_data['filter_coin'];
                $search_array['symbol']['$in'] = $symbol;
            }
            if ($session_post_data['filter_type'] != "") {

                $order_type = $session_post_data['filter_type'];
                $search_array['order_type'] = $order_type;
            }
            if ($session_post_data['filter_level'] != "") {

                $order_level = $session_post_data['filter_level'];
                $search_array['order_level'] = $order_level;
            }
            if ($session_post_data['filter_trigger'] != "") {

                $filter_trigger = $session_post_data['filter_trigger'];
                $search_array['trigger_type'] = $filter_trigger;
            }
            if ($session_post_data['start_date'] != "" && $session_post_data['end_date'] != "") {

                $created_datetime = date('Y-m-d G:i:s', strtotime($session_post_data['start_date']));
                $orig_date = new DateTime($created_datetime);
                $orig_date = $orig_date->getTimestamp();
                $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

                $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_post_data['end_date']));
                $orig_date22 = new DateTime($created_datetime22);
                $orig_date22 = $orig_date22->getTimestamp();
                $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);

                $order_type = $session_post_data['filter_type'];
                $search_array['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
            }
        }

        $connetct = $this->mongo_db->customQuery();

        if ($status == 'open' || $status == 'sold') {
            if ($status == 'open') {

                $search_array['status'] = 'FILLED';
                $search_array['is_sell_order'] = 'yes';
                $cursor = $connetct->buy_orders->count($search_array);

            } elseif ($status == 'sold') {

                $search_array['status'] = 'FILLED';
                $search_array['is_sell_order'] = 'sold';
                $cursor = $connetct->sold_buy_orders->count($search_array);

            }
        } elseif ($status == 'parent') {
            $search_array['parent_status'] = 'parent';
            $search_array['status'] = 'new';
            $cursor = $connetct->buy_orders->count($search_array);

        } elseif ($status == 'lth') {
            $search_array['status'] = 'LTH';
            $cursor = $connetct->buy_orders->count($search_array);

        } elseif ($status == 'new') {
            $search_array['status'] = 'new';
            $search_array['parent_status'] = array('$ne' => 'parent');
            $cursor = $connetct->buy_orders->count($search_array);

        } elseif ($status == 'all') {
            $search_array['status'] = array('$in' => array('error', 'canceled', 'submitted'));
            $search_array['price'] = array('$ne' => '');
            $connetct = $this->mongo_db->customQuery();
            $cursor = $connetct->buy_orders->count($search_array);
            $cursor2 = $connetct->sold_buy_orders->count($search_array);

            // if($cursor2 >$cursor){
            //     $cursor = $cursor2;
            // }
            $cursor = $cursor + $cursor2;
        } else {
            $search_array['status'] = $status;
            $cursor = $connetct->buy_orders->count($search_array);

        }

        return $cursor;

    }

    public function count_orders_new($status, $application_mode, $admin_id, $filter_array) {
        //Check Filter Data
        $session_post_data = $filter_array;

        $search_array = array('admin_id' => $admin_id, 'application_mode' => $application_mode);
        //$search_array = array('admin_id'=> $admin_id);

        if (!empty($filter_array)) {
            if ($session_post_data['filter_coin'] != "") {

                $symbol = $session_post_data['filter_coin'];
                $search_array['symbol'] = $symbol;
            }
            if ($session_post_data['filter_type'] != "") {

                $order_type = $session_post_data['filter_type'];
                $search_array['order_type'] = $order_type;
            }
            if ($session_post_data['filter_level'] != "") {

                $order_level = $session_post_data['filter_level'];
                $search_array['order_level'] = $order_level;
            }
            if ($session_post_data['filter_trigger'] != "") {

                $filter_trigger = $session_post_data['filter_trigger'];
                $search_array['trigger_type'] = $filter_trigger;
            }
            if ($session_post_data['start_date'] != "" && $session_post_data['end_date'] != "") {

                $created_datetime = date('Y-m-d G:i:s', strtotime($session_post_data['start_date']));
                $orig_date = new DateTime($created_datetime);
                $orig_date = $orig_date->getTimestamp();
                $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

                $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_post_data['end_date']));
                $orig_date22 = new DateTime($created_datetime22);
                $orig_date22 = $orig_date22->getTimestamp();
                $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);

                $order_type = $session_post_data['filter_type'];
                $search_array['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
            }
        }

        $connetct = $this->mongo_db->customQuery();

        if ($status == 'open' || $status == 'sold') {
            if ($status == 'open') {

                $search_array['status'] = 'FILLED';
                $search_array['is_sell_order'] = 'yes';
                $cursor = $connetct->buy_orders->count($search_array);

            } elseif ($status == 'sold') {

                $search_array['status'] = 'FILLED';
                $search_array['is_sell_order'] = 'sold';
                $cursor = $connetct->sold_buy_orders->count($search_array);

            }
        } elseif ($status == 'parent') {
            $search_array['parent_status'] = 'parent';
            $search_array['status'] = 'new';
            $cursor = $connetct->buy_orders->count($search_array);

        } elseif ($status == 'lth') {
            $search_array['status'] = 'LTH';
            $cursor = $connetct->buy_orders->count($search_array);

        } elseif ($status == 'new') {
            $search_array['status'] = 'new';
            $search_array['parent_status'] = array('$ne' => 'parent');
            $cursor = $connetct->buy_orders->count($search_array);

        } elseif ($status == 'all') {
            $search_array['status'] = array('$in' => array('error', 'canceled', 'submitted'));
            $search_array['price'] = array('$ne' => '');
            $connetct = $this->mongo_db->customQuery();
            $cursor = $connetct->buy_orders->count($search_array);
            $cursor2 = $connetct->sold_buy_orders->count($search_array);

            // if($cursor2 >$cursor){
            //     $cursor = $cursor2;
            // }
            $cursor = $cursor + $cursor2;
        } else {
            $search_array['status'] = $status;
            $cursor = $connetct->buy_orders->count($search_array);

        }

        return $cursor;

    }

    public function get_orders($status, $application_mode, $admin_id, $filter_array, $skip, $limit) {
        //Check Filter Data
        $this->load->model('admin/mod_coins');
        $this->load->model('admin/mod_dashboard');
        $timezone = get_user_timezone($admin_id);
        $session_post_data = $filter_array;
        $search_array = array('admin_id' => $admin_id, 'application_mode' => $application_mode);
        //$search_array = array('admin_id'=> $admin_id);
        if (!empty($filter_array)) {

            if ($session_post_data['filter_coin'] != "") {

                $symbol = $session_post_data['filter_coin'];
                $search_array['symbol']['$in'] = $symbol;
            }
            if ($session_post_data['filter_type'] != "") {

                $order_type = $session_post_data['filter_type'];
                $search_array['order_type'] = $order_type;
            }
            if ($session_post_data['filter_level'] != "") {

                $order_level = $session_post_data['filter_level'];
                $search_array['order_level'] = $order_level;
            }
            if ($session_post_data['filter_trigger'] != "") {

                $filter_trigger = $session_post_data['filter_trigger'];
                $search_array['trigger_type'] = $filter_trigger;
            }
            if ($session_post_data['start_date'] != "" && $session_post_data['end_date'] != "") {

                $created_datetime = date('Y-m-d G:i:s', strtotime($session_post_data['start_date']));
                $orig_date = new DateTime($created_datetime);
                $orig_date = $orig_date->getTimestamp();
                $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

                $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_post_data['end_date']));
                $orig_date22 = new DateTime($created_datetime22);
                $orig_date22 = $orig_date22->getTimestamp();
                $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);

                $order_type = $session_post_data['filter_type'];
                $search_array['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
            }
        }
        $connetct = $this->mongo_db->customQuery();

        if ($status == 'open' || $status == 'sold') {
            if ($status == 'open') {

                $search_array['status'] = array('$in' => array('submitted', 'FILLED'));
                $search_array['is_sell_order'] = 'yes';
                if ($skip != 0) {
                    $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
                } else {
                    $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
                }

                $cursor = $connetct->buy_orders->find($search_array, $qr);
            } elseif ($status == 'sold') {

                $search_array['status'] = 'FILLED';
                $search_array['is_sell_order'] = 'sold';
                if ($skip != 0) {
                    $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
                } else {
                    $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
                }
                $cursor = $connetct->sold_buy_orders->find($search_array, $qr);
            }
        } elseif ($status == 'parent') {

            $search_array['parent_status'] = 'parent';
            $search_array['status'] = 'new';
            if ($skip != 0) {
                $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
            } else {
                $qr = array('sort' => array('modified_date' => -1));
            }
            $cursor = $connetct->buy_orders->find($search_array, $qr);
        } elseif ($status == 'lth') {
            $search_array['status'] = 'LTH';
            $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
            $cursor = $connetct->buy_orders->find($search_array, $qr);

            //$responseArr = iterator_to_array($cursor);
        } elseif ($status == 'new') {
            $search_array['status'] = 'new';
            $search_array['price'] = array('$ne' => '');
            if ($skip != 0) {
                $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
            } else {
                $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
            }
            $cursor = $connetct->buy_orders->find($search_array, $qr);
        } elseif ($status == 'all') {
            $search_array['status'] = array('$in' => array('error', 'canceled', 'submitted'));
            $search_array['price'] = array('$ne' => '');
            if ($skip != 0) {
                $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
            } else {
                $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
            }
            //$cursor = $connetct->buy_orders->find($search_array, $qr);
            $pending_curser = $connetct->buy_orders->find($search_array, $qr);
            $sold_curser = $connetct->sold_buy_orders->find($search_array, $qr);
            
            // $pending_curser = $connetct->buy_orders->find($search_array, $pending_options);
            // $sold_curser = $connetct->sold_buy_orders->find($search_array, $sold_options);

            $pending_arr = iterator_to_array($pending_curser);
            $sold_arr = iterator_to_array($sold_curser);

            $originalArray = array_merge_recursive($pending_arr, $sold_arr);

            foreach ($originalArray as $key => $part) {
                $sort[$key] = (string) $part['modified_date'];
            }

            array_multisort($sort, SORT_DESC, $originalArray);
            $cursor = $originalArray;
        }

        if(empty($cursor)){
            return array();
        }

        $responseArr = iterator_to_array($cursor);

        $fullarray = array();
        foreach ($responseArr as $valueArr) {

            $returArr = array();

            if (!empty($valueArr)) {

                //$timezone = get_user_timezone($valueArr[]);
                $image = $this->mod_coins->get_coin_logo($valueArr['symbol']);
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone($timezone);
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                if (empty($valueArr['modified_date'])) {
                    $valueArr['modified_date'] = $valueArr['created_date'];
                }
                $datetime111 = $valueArr['modified_date']->toDateTime();
                $created_date111 = $datetime111->format(DATE_RSS);

                $datetime111 = new DateTime($created_date111);
                $datetime111->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone($timezone);
                $datetime111->setTimezone($new_timezone);
                $formated_date_time1 = $datetime111->format('Y-m-d g:i:s A');

                $time_elapsed_string = time_elapsed_string($formated_date_time1, $timezone, false);

                $score_avg = $this->mod_dashboard->get_score_avg($valueArr['symbol']);
                $returArr['_id'] = (string) $valueArr['_id'];
                $returArr['symbol'] = $valueArr['symbol'];
                $returArr['score'] = $score_avg;
                $returArr['image'] = $image;
                $returArr['binance_order_id'] = $valueArr['binance_order_id'];
                $returArr['price'] = num($valueArr['price']);
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['order_type'] = $valueArr['order_type'];
                $returArr['market_value'] = num($valueArr['market_value']);
                $returArr['trail_check'] = $valueArr['trail_check'];
                $returArr['trail_interval'] = $valueArr['trail_interval'];
                $returArr['buy_trail_price'] = num($valueArr['buy_trail_price']);
                $returArr['status'] = $valueArr['status'];
                $returArr['is_sell_order'] = $valueArr['is_sell_order'];
                $returArr['market_sold_price'] = num($valueArr['market_sold_price']);
                $returArr['sell_order_id'] = (string) $valueArr['sell_order_id'];
                $returArr['pause_status'] = $valueArr['pause_status'];
                $returArr['inactive_status'] = $valueArr['inactive_status'];
                $returArr['admin_id'] = $valueArr['admin_id'];
                $returArr['auto_sell'] = $valueArr['auto_sell'];
                $returArr['trigger_type'] = $valueArr['trigger_type'];
                $returArr['order_level'] = $valueArr['order_level'];
                $returArr['trigger_name'] = strtoupper(str_replace("_", " ", $valueArr['trigger_type']));
                $returArr['application_mode'] = $valueArr['application_mode'];
                $returArr['created_date'] = $formated_date_time;
                $returArr['modified_date'] = $formated_date_time1;
                $returArr['time_ago'] = $time_elapsed_string;
                $returArr['time_zone'] = $timezone;

                $this->load->model('admin/mod_dashboard');

                $market_value = $this->mod_dashboard->get_market_value($valueArr['symbol']);
                if ($valueArr['status'] != 'new' && $valueArr['status'] != 'error') {
                    $market_value333 = num($valueArr['market_value']);
                } else {
                    $market_value333 = num($market_value);
                }
                if ($valueArr['status'] == 'new') {
                    $current_order_price = num($valueArr['price']);
                } else {
                    $current_order_price = num($valueArr['market_value']);
                }
                if ($valueArr['is_sell_order'] != 'sold' && $valueArr['is_sell_order'] != 'yes' && $valueArr['status'] != 'error') {
                    $current_data = $market_value333 - $current_order_price;
                    $market_data = ($current_data * 100 / $market_value333);
                    $market_data = number_format((float) $market_data, 2, '.', '');
                }

                if ($valueArr['status'] == 'FILLED') {

                    if ($valueArr['is_sell_order'] == 'yes') {

                        $current_data = num($market_value) - num($valueArr['market_value']);
                        $market_data = ($current_data * 100 / $market_value);
                        $market_data = number_format((float) $market_data, 2, '.', '');
                    }
                    if ($valueArr['is_sell_order'] == 'sold') {
                        $current_data = num($valueArr['market_sold_price']) - num($valueArr['market_value']);
                        $market_data = ($current_data * 100 / $valueArr['market_sold_price']);
                        $market_data = number_format((float) $market_data, 2, '.', '');
                    }
                }
                $returArr['profit_data'] = $market_data;

            }

            $fullarray[] = $returArr;
        }
        return $fullarray;

    }

    //get_orders_test //Umer Abbas [12-11-19]
    public function get_orders_test($status, $application_mode, $admin_id, $filter_array, $skip, $limit) {
        //Check Filter Data
        $this->load->model('admin/mod_coins');
        $this->load->model('admin/mod_dashboard');
        $timezone = get_user_timezone($admin_id);
        $session_post_data = $filter_array;
        $search_array = array('admin_id' => $admin_id, 'application_mode' => $application_mode);
        //$search_array = array('admin_id'=> $admin_id);
        if (!empty($filter_array)) {

            if ($session_post_data['filter_coin'] != "") {

                $symbol = $session_post_data['filter_coin'];
                $search_array['symbol']['$in'] = $symbol;
            }
            if ($session_post_data['filter_type'] != "") {

                $order_type = $session_post_data['filter_type'];
                $search_array['order_type'] = $order_type;
            }
            if ($session_post_data['filter_level'] != "") {

                $order_level = $session_post_data['filter_level'];
                $search_array['order_level'] = $order_level;
            }
            if ($session_post_data['filter_trigger'] != "") {

                $filter_trigger = $session_post_data['filter_trigger'];
                $search_array['trigger_type'] = $filter_trigger;
            }
            if ($session_post_data['start_date'] != "" && $session_post_data['end_date'] != "") {

                $created_datetime = date('Y-m-d G:i:s', strtotime($session_post_data['start_date']));
                $orig_date = new DateTime($created_datetime);
                $orig_date = $orig_date->getTimestamp();
                $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

                $created_datetime22 = date('Y-m-d G:i:s', strtotime($session_post_data['end_date']));
                $orig_date22 = new DateTime($created_datetime22);
                $orig_date22 = $orig_date22->getTimestamp();
                $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);

                $order_type = $session_post_data['filter_type'];
                $search_array['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
            }
        }
        $connetct = $this->mongo_db->customQuery();

        if ($status == 'open' || $status == 'sold') {
            if ($status == 'open') {

                $search_array['status'] = array('$in' => array('submitted', 'FILLED'));
                $search_array['is_sell_order'] = 'yes';
                if ($skip != 0) {
                    $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
                } else {
                    $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
                }

                $cursor = $connetct->buy_orders->find($search_array, $qr);
            } elseif ($status == 'sold') {

                $search_array['status'] = 'FILLED';
                $search_array['is_sell_order'] = 'sold';
                if ($skip != 0) {
                    $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
                } else {
                    $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
                }
                $cursor = $connetct->sold_buy_orders->find($search_array, $qr);
            }
        } elseif ($status == 'parent') {

            $search_array['parent_status'] = 'parent';
            $search_array['status'] = 'new';
            if ($skip != 0) {
                $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
            } else {
                $qr = array('sort' => array('modified_date' => -1));
            }
            $cursor = $connetct->buy_orders->find($search_array, $qr);
        } elseif ($status == 'lth') {
            $search_array['status'] = 'LTH';
            $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
            $cursor = $connetct->buy_orders->find($search_array, $qr);

            //$responseArr = iterator_to_array($cursor);
        } elseif ($status == 'new') {
            $search_array['status'] = 'new';
            $search_array['price'] = array('$ne' => '');
            if ($skip != 0) {
                $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
            } else {
                $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
            }
            $cursor = $connetct->buy_orders->find($search_array, $qr);
        } elseif ($status == 'all') {
            $search_array['status'] = array('$in' => array('error', 'canceled', 'submitted'));
            $search_array['price'] = array('$ne' => '');
            if ($skip != 0) {
                $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
            } else {
                $qr = array('skip' => $skip, 'sort' => array('modified_date' => -1), 'limit' => $limit);
            }
            //$cursor = $connetct->buy_orders->find($search_array, $qr);
            $pending_curser = $connetct->buy_orders->find($search_array, $qr);
            $sold_curser = $connetct->sold_buy_orders->find($search_array, $qr);
            
            // $pending_curser = $connetct->buy_orders->find($search_array, $pending_options);
            // $sold_curser = $connetct->sold_buy_orders->find($search_array, $sold_options);

            $pending_arr = iterator_to_array($pending_curser);
            $sold_arr = iterator_to_array($sold_curser);

            $originalArray = array_merge_recursive($pending_arr, $sold_arr);

            foreach ($originalArray as $key => $part) {
                $sort[$key] = (string) $part['modified_date'];
            }

            array_multisort($sort, SORT_DESC, $originalArray);
            $cursor = $originalArray;
        }

        if(empty($cursor)){
            return array();
        }

        $responseArr = iterator_to_array($cursor);

        $fullarray = array();
        foreach ($responseArr as $valueArr) {

            $returArr = array();

            if (!empty($valueArr)) {

                //$timezone = get_user_timezone($valueArr[]);
                $image = $this->mod_coins->get_coin_logo($valueArr['symbol']);
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone($timezone);
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                if (empty($valueArr['modified_date'])) {
                    $valueArr['modified_date'] = $valueArr['created_date'];
                }
                $datetime111 = $valueArr['modified_date']->toDateTime();
                $created_date111 = $datetime111->format(DATE_RSS);

                $datetime111 = new DateTime($created_date111);
                $datetime111->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone($timezone);
                $datetime111->setTimezone($new_timezone);
                $formated_date_time1 = $datetime111->format('Y-m-d g:i:s A');

                $time_elapsed_string = time_elapsed_string($formated_date_time1, $timezone, false);

                $score_avg = $this->mod_dashboard->get_score_avg($valueArr['symbol']);
                $returArr['_id'] = (string) $valueArr['_id'];
                $returArr['symbol'] = $valueArr['symbol'];
                $returArr['score'] = $score_avg;
                $returArr['image'] = $image;
                $returArr['binance_order_id'] = $valueArr['binance_order_id'];
                $returArr['price'] = num($valueArr['price']);
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['order_type'] = $valueArr['order_type'];
                $returArr['market_value'] = num($valueArr['market_value']);
                $returArr['trail_check'] = $valueArr['trail_check'];
                $returArr['trail_interval'] = $valueArr['trail_interval'];
                $returArr['buy_trail_price'] = num($valueArr['buy_trail_price']);
                $returArr['status'] = $valueArr['status'];
                $returArr['is_sell_order'] = $valueArr['is_sell_order'];
                $returArr['market_sold_price'] = num($valueArr['market_sold_price']);
                $returArr['sell_order_id'] = (string) $valueArr['sell_order_id'];
                $returArr['pause_status'] = $valueArr['pause_status'];
                $returArr['inactive_status'] = $valueArr['inactive_status'];
                $returArr['admin_id'] = $valueArr['admin_id'];
                $returArr['auto_sell'] = $valueArr['auto_sell'];
                $returArr['trigger_type'] = $valueArr['trigger_type'];
                $returArr['order_level'] = $valueArr['order_level'];
                $returArr['trigger_name'] = strtoupper(str_replace("_", " ", $valueArr['trigger_type']));
                $returArr['application_mode'] = $valueArr['application_mode'];
                $returArr['created_date'] = $formated_date_time;
                $returArr['modified_date'] = $formated_date_time1;
                $returArr['time_ago'] = $time_elapsed_string;
                $returArr['time_zone'] = $timezone;

                $this->load->model('admin/mod_dashboard');

                $market_value = $this->mod_dashboard->get_market_value($valueArr['symbol']);
                if ($valueArr['status'] != 'new' && $valueArr['status'] != 'error') {
                    $market_value333 = num($valueArr['market_value']);
                } else {
                    $market_value333 = num($market_value);
                }
                if ($valueArr['status'] == 'new') {
                    $current_order_price = num($valueArr['price']);
                } else {
                    $current_order_price = num($valueArr['market_value']);
                }
                if ($valueArr['is_sell_order'] != 'sold' && $valueArr['is_sell_order'] != 'yes' && $valueArr['status'] != 'error') {
                    $current_data = $market_value333 - $current_order_price;
                    $market_data = ($current_data * 100 / $market_value333);
                    $market_data = number_format((float) $market_data, 2, '.', '');
                }

                if ($valueArr['status'] == 'FILLED') {

                    if ($valueArr['is_sell_order'] == 'yes') {

                        $current_data = num($market_value) - num($valueArr['market_value']);
                        $market_data = ($current_data * 100 / $market_value);
                        $market_data = number_format((float) $market_data, 2, '.', '');
                    }
                    if ($valueArr['is_sell_order'] == 'sold') {
                        $current_data = num($valueArr['market_sold_price']) - num($valueArr['market_value']);
                        $market_data = ($current_data * 100 / $valueArr['market_sold_price']);
                        $market_data = number_format((float) $market_data, 2, '.', '');
                    }
                }
                $returArr['profit_data'] = $market_data;

            }

            $fullarray[] = $returArr;
        }
        return $fullarray;

    }//end get_orders_test

    //get_buy_order
    public function get_buy_order($id) {

        $this->mongo_db->where(array('_id' => $this->mongo_db->mongoId($id)));
        $responseArr = $this->mongo_db->get('buy_orders');
        // echo "<pre>";
        // print_r(iterator_to_array($responseArr));
        // exit;
        $this->load->model('admin/mod_dashboard');
        foreach ($responseArr as $valueArr) {
            $returArr = array();
            if (!empty($valueArr)) {
                $timezone = get_user_timezone($valueArr['admin_id']);
                //$image = $this->mod_coins->get_coin_logo($valueArr['symbol']);
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone($timezone);
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                if (empty($valueArr['modified_date'])) {
                    $valueArr['modified_date'] = $valueArr['created_date'];
                }
                $datetime111 = $valueArr['modified_date']->toDateTime();
                $created_date111 = $datetime111->format(DATE_RSS);

                $datetime111 = new DateTime($created_date111);
                $datetime111->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');
                $datetime111->setTimezone($new_timezone);
                $formated_date_time1 = $datetime111->format('Y-m-d g:i:s A');

                $time_elapsed_string = time_elapsed_string($formated_date_time1, $timezone);
                if ($valueArr['parent_status'] == 'parent') {

                    $returArr['ID'] = (string) $valueArr['_id'];
                    $returArr['Symbol'] = $valueArr['symbol'];
                    $returArr['Quantity'] = $valueArr['quantity'];
                    $returArr['Order Type'] = $valueArr['order_type'];
                    $returArr['Status'] = $valueArr['status'];
                    $returArr['Admin ID'] = $valueArr['admin_id'];
                    $returArr['Application Mode'] = $valueArr['application_mode'];
                    $returArr['Created Date'] = $formated_date_time;
                    $returArr['Modified Date'] = $formated_date_time1;
                    $returArr['Last Updated'] = $time_elapsed_string;
                    $returArr['Order Level'] = $valueArr['order_level'];

                }

                if (($valueArr['status'] == 'new' || $valueArr['status'] == 'canceled' || $valueArr['status'] == 'error') && $valueArr['parent_status'] != 'parent') {
                    $returArr['ID'] = (string) $valueArr['_id'];
                    $returArr['Symbol'] = $valueArr['symbol'];
                    $returArr['Price'] = $valueArr['price'];
                    $returArr['Quantity'] = $valueArr['quantity'];
                    $returArr['Order Type'] = $valueArr['order_type'];
                    $returArr['Status'] = $valueArr['status'];
                    $returArr['Admin ID'] = $valueArr['admin_id'];
                    $returArr['Order Level'] = $valueArr['order_level'];
                    $returArr['Application Mode'] = $valueArr['application_mode'];
                    $returArr['Created Date'] = $formated_date_time;
                    $returArr['Modified Date'] = $formated_date_time1;
                    $returArr['Last Updated'] = $time_elapsed_string;
                    $returArr['Auto Sell'] = (isset($valueArr['auto_sell']) ? $valueArr['auto_sell'] : "no");
                    if ($valueArr['auto_sell'] == 'yes') {
                        //Get Sell Temp Data
                        $sell_data_arr = $this->mod_dashboard->get_temp_sell_data($valueArr['_id']);
                        $returArr['Profit Type'] = $sell_data_arr['profit_type'];
                        $returArr['Profit Percentage'] = $sell_data_arr['profit_percent'];
                        $returArr['Profit Price'] = $sell_data_arr['profit_price'];
                        $returArr['Sell Order Type'] = $sell_data_arr['order_type'];
                        $returArr['Trail Check'] = (isset($sell_data_arr['trail_check']) ? $sell_data_arr['trail_check'] : "no");
                        $returArr['Trail Interval'] = $sell_data_arr['trail_interval'];
                        $returArr['Stop Loss'] = (isset($sell_data_arr['stop_loss']) ? $sell_data_arr['stop_loss'] : "no");
                        $returArr['Loss Percentage'] = $sell_data_arr['loss_percentage'];
                    }
                }
                if (($valueArr['status'] == 'FILLED' || $valueArr['status'] == 'submitted') && $valueArr['parent_status'] != 'parent') {
                    if ($valueArr['is_sell_order'] == 'sold') {
                        $returArr['ID'] = (string) $valueArr['_id'];
                        $returArr['Symbol'] = $valueArr['symbol'];
                        $returArr['Price'] = $valueArr['price'];
                        $returArr['Entry Price'] = $valueArr['market_value'];
                        $returArr['Exit Price'] = $valueArr['market_sold_price'];
                        $returArr['Binance ID'] = (isset($valueArr['binance_order_id']) ? $valueArr['binance_order_id'] : "N/A");
                        $returArr['Quantity'] = $valueArr['quantity'];
                        $returArr['Order Type'] = $valueArr['order_type'];
                        $returArr['Status'] = $valueArr['status'];
                        $returArr['Admin ID'] = $valueArr['admin_id'];
                        $returArr['Order Level'] = $valueArr['order_level'];
                        $returArr['Application Mode'] = $valueArr['application_mode'];
                        $returArr['Created Date'] = $formated_date_time;
                        $returArr['Modified Date'] = $formated_date_time1;
                        $returArr['Last Updated'] = $time_elapsed_string;
                        $returArr['Auto Sell'] = (isset($valueArr['auto_sell']) ? $valueArr['auto_sell'] : "no");
                        if ($valueArr['auto_sell'] == 'yes') {
                            //Get Sell Temp Data
                            $sell_data_arr = $this->mod_dashboard->get_temp_sell_data($valueArr['_id']);
                            $returArr['Profit Type'] = $sell_data_arr['profit_type'];
                            $returArr['Profit Percentage'] = $sell_data_arr['profit_percent'];
                            $returArr['Profit Price'] = $sell_data_arr['profit_price'];
                            $returArr['Sell Order Type'] = $sell_data_arr['order_type'];
                            $returArr['Trail Check'] = (isset($sell_data_arr['trail_check']) ? $sell_data_arr['trail_check'] : "no");
                            $returArr['Trail Interval'] = $sell_data_arr['trail_interval'];
                            $returArr['Stop Loss'] = (isset($sell_data_arr['stop_loss']) ? $sell_data_arr['stop_loss'] : "no");
                            $returArr['Loss Percentage'] = $sell_data_arr['loss_percentage'];
                        }

                        $current_data = num($valueArr['market_sold_price']) - num($valueArr['market_value']);
                        $market_data = ($current_data * 100 / $valueArr['market_sold_price']);
                        $market_data = number_format((float) $market_data, 2, '.', '');

                        $returArr['profit_data'] = $market_data;

                    } else {
                        $returArr['ID'] = (string) $valueArr['_id'];
                        $returArr['Symbol'] = $valueArr['symbol'];
                        $returArr['Price'] = $valueArr['price'];
                        $returArr['Order Level'] = $valueArr['order_level'];
                        $returArr['Market Buy Price'] = $valueArr['market_value'];
                        $returArr['Binance ID'] = (isset($valueArr['binance_order_id']) ? $valueArr['binance_order_id'] : "N/A");
                        $returArr['Quantity'] = $valueArr['quantity'];
                        $returArr['Order Type'] = $valueArr['order_type'];
                        $returArr['Status'] = $valueArr['status'];
                        $returArr['Admin ID'] = $valueArr['admin_id'];
                        $returArr['Application Mode'] = $valueArr['application_mode'];
                        $returArr['Created Date'] = $formated_date_time;
                        $returArr['Modified Date'] = $formated_date_time1;
                        $returArr['Last Updated'] = $time_elapsed_string;
                        $returArr['Auto Sell'] = (isset($valueArr['auto_sell']) ? $valueArr['auto_sell'] : "no");
                        if ($valueArr['auto_sell'] == 'yes') {
                            //Get Sell Temp Data
                            $sell_data_arr = $this->mod_dashboard->get_temp_sell_data($valueArr['_id']);
                            $returArr['Profit Type'] = $sell_data_arr['profit_type'];
                            $returArr['Profit Percentage'] = $sell_data_arr['profit_percent'];
                            $returArr['Profit Price'] = $sell_data_arr['profit_price'];
                            $returArr['Sell Order Type'] = $sell_data_arr['order_type'];
                            $returArr['Trail Check'] = (isset($sell_data_arr['trail_check']) ? $sell_data_arr['trail_check'] : "no");
                            $returArr['Trail Interval'] = $sell_data_arr['trail_interval'];
                            $returArr['Stop Loss'] = (isset($sell_data_arr['stop_loss']) ? $sell_data_arr['stop_loss'] : "no");
                            $returArr['Loss Percentage'] = $sell_data_arr['loss_percentage'];
                        }

                        if ($valueArr['is_sell_order'] == 'yes') {
                            $market_value = $this->mod_dashboard->get_market_value($valueArr['symbol']);
                            $current_data = num($market_value) - num($valueArr['market_value']);
                            $market_data = ($current_data * 100 / $market_value);
                            $market_data = number_format((float) $market_data, 2, '.', '');
                            $returArr['profit_data'] = $market_data;
                        } else {
                            $returArr['profit_data'] = "-";
                        }
                    }
                }
                // $datetime = $valueArr['created_date']->toDateTime();
                // $created_date = $datetime->format(DATE_RSS);

                // $datetime = new DateTime($created_date);
                // $datetime->format('Y-m-d g:i:s A');

                // $new_timezone = new DateTimeZone('Asia/Karachi');
                // $datetime->setTimezone($new_timezone);
                // $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                // $returArr['_id'] = $valueArr['_id'];
                // $returArr['symbol'] = $valueArr['symbol'];
                // $returArr['binance_order_id'] = $valueArr['binance_order_id'];
                // $returArr['price'] = $valueArr['price'];
                // $returArr['quantity'] = $valueArr['quantity'];
                // $returArr['market_value'] = $valueArr['market_value'];
                // $returArr['order_type'] = $valueArr['order_type'];
                // $returArr['status'] = $valueArr['status'];
                // $returArr['admin_id'] = $valueArr['admin_id'];
                // $returArr['trail_check'] = $valueArr['trail_check'];
                // $returArr['trail_interval'] = $valueArr['trail_interval'];
                // $returArr['is_sell_order'] = $valueArr['is_sell_order'];
                // $returArr['sell_order_id'] = $valueArr['sell_order_id'];
                // $returArr['auto_sell'] = $valueArr['auto_sell'];
                // $returArr['application_mode'] = $valueArr['application_mode'];
                // $returArr['created_date'] = $formated_date_time;
            }
        }
        return $returArr;

    } //end get_buy_order

    //add_buy_order_triggers

    public function add_buy_order_triggers($data) {

        extract($data);

        $created_date = date('Y-m-d G:i:s');
        $order_mode_arr = array();
        if ($order_mode != '') {
            $order_mode_arr = explode("_", $order_mode);
        }

        $application_mode = '';
        if (count($order_mode_arr) > 0) {
            $application_mode = $order_mode_arr[0];
        }

        $inactive_time_new = date("Y-m-d G:00:00", strtotime($inactive_time));
        $this->load->model('admin/mod_dashboard');
        $market_value = $this->mod_dashboard->get_market_value($coin);
        // $ins_data = array(
        //     'price' => '',
        //     'quantity' => $quantity,
        //     'symbol' => $coin,
        //     'order_type' => '',
        //     'admin_id' => $admin_id,
        //     'created_date' => $this->mongo_db->converToMongodttime($created_date),
        //     'trail_check' => '',
        //     'trail_interval' => '',
        //     'order_level' => 'level_1',
        //     'buy_trail_price' => '',
        //     'status' => 'new',
        //     'auto_sell' => '',
        //     'market_value' => '',
        //     'binance_order_id' => '',
        //     'is_sell_order' => '',
        //     'inactive_time' => $this->mongo_db->converToMongodttime($inactive_time_new),
        //     'sell_order_id' => '',
        //     'trigger_type' => $trigger_type,
        //     'application_mode' => $application_mode,
        //     'order_mode' => $order_mode,
        //     'parent_status' => 'parent',
        //     'modified_date' => $this->mongo_db->converToMongodttime($created_date),
        // );
        $ins_data = array(
            'price' => '',
            'quantity' => $quantity,
            'symbol' => $coin,
            'order_type' => $order_type,
            'admin_id' => $admin_id,
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
            'trail_check' => '',
            'trail_interval' => '',
            'buy_trail_price' => '',
            'status' => 'new',
            'auto_sell' => '',
            'market_value' => '',
            'binance_order_id' => '',
            'is_sell_order' => '',
            'sell_order_id' => '',
            'trigger_type' => $trigger_type,
            'application_mode' => $application_mode,
            'order_mode' => $order_mode,
            'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            'parent_status' => 'parent',
            'defined_sell_percentage' => $defined_sell_percentage,
            'buy_one_tip_above' => $buy_one_tip_above,
            'sell_one_tip_below' => $sell_one_tip_below,
            'order_level' => $order_level,
            'current_market_price' => (float) $market_value,
            'custom_stop_loss_percentage' => $custom_stop_loss,
            'stop_loss_rule' => $stop_loss_rule,
            'activate_stop_loss_profit_percentage' => $activate_stop_loss_profit_percentage,
            'lth_functionality' => $lth_functionality,
            'lth_profit' => $lth_profit,
        );

        if (!empty($inactive_time) && $inactive_time != '') {
            $ins_data['inactive_time'] = $this->mongo_db->converToMongodttime($inactive_time_new);
        }
        $buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data);

        $log_msg = "Buy Order was Created at Price " . $price;
        if ($auto_sell == 'yes' && $sell_profit_percent != '') {
            $log_msg .= ' with auto sell ' . $sell_profit_percent . '%';
        }
        $log_msg .= " From Mobile App";
        $this->load->model('admin/mod_dashboard');
        $this->mod_dashboard->insert_order_history_log($buy_order_id, $log_msg, 'buy_created', $admin_id);
        return true;

    } //End add_buy_order_triggers

    //add_buy_order
    public function add_buy_order($data) {

        extract($data);

        $created_date = date('Y-m-d G:i:s');

        $ins_data = array(
            'price' => $price,
            'quantity' => $quantity,
            'symbol' => $symbol,
            'order_type' => $order_type,
            'admin_id' => $admin_id,
            'trigger_type' => 'no',
            'application_mode' => $application_mode,
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
            'modified_date' => $this->mongo_db->converToMongodttime($created_date),
        );

        $is_submitted = 'no';
        if ($trail_check != '') {
            $ins_data['trail_check'] = 'yes';
            $ins_data['trail_interval'] = $trail_interval;
            $ins_data['buy_trail_price'] = $price;
            $ins_data['status'] = 'new';

        } else {

            $ins_data['trail_check'] = 'no';
            $ins_data['trail_interval'] = '0';
            $ins_data['buy_trail_price'] = '0';
            $ins_data['status'] = 'new';
        }

        if ($auto_sell == 'yes') {
            $ins_data['auto_sell'] = 'yes';
        } else {
            $ins_data['auto_sell'] = 'no';
        }

        //Insert data in mongoTable
        $buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data);

        ////////////////////////////// Auto Sell////////////////////////////
        if ($auto_sell == 'yes') {

            $ins_temp_data = array(
                'buy_order_id' => $this->mongo_db->mongoId($buy_order_id),
                'profit_type' => $profit_type,
                'profit_percent' => $sell_profit_percent,
                'profit_price' => $sell_profit_price,
                'order_type' => $sell_order_type,
                'trail_check' => $sell_trail_check,
                'trail_interval' => $sell_trail_interval,
                'stop_loss' => $stop_loss,
                'loss_percentage' => $loss_percentage,
                'admin_id' => $admin_id,
                'application_mode' => $application_mode,
                'created_date' => $this->mongo_db->converToMongodttime($created_date),
            );

            //Insert data in mongoTable
            $this->mongo_db->insert('temp_sell_orders', $ins_temp_data);

        }
        //////////////////////////////// End Auto Sell/////////////////////////

        //////////////////////////////////////////////////////////////////////////////
        ////////////////////////////// Order History Log /////////////////////////////
        $log_msg = "Buy Order was Created at Price " . $price;
        if ($auto_sell == 'yes' && $sell_profit_percent != '') {
            $log_msg .= ' with auto sell ' . $sell_profit_percent . '%';
        }
        $log_msg .= " From Mobile App";
        $this->load->model('admin/mod_dashboard');
        $this->mod_dashboard->insert_order_history_log($buy_order_id, $log_msg, 'buy_created', $admin_id);
        ////////////////////////////// End Order History Log /////////////////////////
        //////////////////////////////////////////////////////////////////////////////

        return true;

    } //end add_buy_order
    
    //add_buy_order_test //Umer Abbas [20-12-19]
    public function add_buy_order_test($data, $received_Token_send) {

        //testing
        $temp_test_data = $data;
        $temp_test_data['created_date'] = $this->mongo_db->converToMongodttime(date('Y-m-d G:i:s'));
        $this->mongo_db->insert('test_mobile_api', $temp_test_data);

        extract($data);

        $created_date = date('Y-m-d G:i:s');

        $ins_data = array(
            'exchange' => $exchange,
            'price' => $price,
            'quantity' => (float) $quantity,
            'usd_worth' => (float) $usd_worth,
            'profit_type' => $profit_type,
            'symbol' => $symbol,
            'order_type' => $order_type,
            'admin_id' => $admin_id,
            'trigger_type' => 'no',
            'application_mode' => $application_mode,
            "lth_functionality" => $lth_functionality,
            "lth_profit" => (float) $lth_profit,
        );

        $is_submitted = 'no';
        if ($trail_check != '') {
            $ins_data['trail_check'] = 'yes';
            $ins_data['trail_interval'] = (float) $trail_interval;
            $ins_data['buy_trail_price'] = (float) $price;
            $ins_data['status'] = 'new';

        } else {

            $ins_data['trail_check'] = 'no';
            $ins_data['trail_interval'] = (float) 0.0;
            $ins_data['buy_trail_price'] = (float) 0.0;
            $ins_data['status'] = 'new';
        }

        
        if ($auto_sell == 'yes') {
            $ins_data['auto_sell'] = 'yes';
        } else {
            $ins_data['auto_sell'] = 'no';
        }

        if (!empty($buyRightAway) && $buyRightAway == 'yes') {
        
            $ins_data['buyRightAway'] = 'yes';
        
        } else if((empty($buyRightAway) || $buyRightAway == '' || $buyRightAway == 'no') && !empty($deep_price_on_off) && $deep_price_on_off == 'yes') {

            $ins_data['deep_price_on_off'] = 'yes';
            $ins_data['expecteddeepPrice'] = (float) $price;
            $ins_data['cancel_hour'] = $cancel_hour ?? '';
        }
        
        //Insert data in mongoTable
        // $buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data);
        
        ////////////////////////////// Auto Sell////////////////////////////
        if ($auto_sell == 'yes') {
            
            $ins_temp_data = array(
                'profit_type' => $profit_type,
                'profit_percent' => (float) $sell_profit_percent,
                'profit_price' => (float) $sell_profit_price,
                'sell_price' => (float) $sell_profit_price,
                'order_type' => $sell_order_type,
                'trail_check' => $sell_trail_check,
                'trail_interval' => (float) $sell_trail_interval,
                'stop_loss' => $stop_loss,
                'loss_percentage' => (float) $loss_percentage,
                'iniatial_trail_stop' => (float) $iniatial_trail_stop,
                "lth_functionality" => $lth_functionality,
                "lth_profit" => (float) $lth_profit,
                'admin_id' => $admin_id,
                'application_mode' => $application_mode,
            );
            
            //Insert data in mongoTable
            // $this->mongo_db->insert('temp_sell_orders', $ins_temp_data);
            
        }
        //////////////////////////////// End Auto Sell/////////////////////////
        
        $params = [
            'orderArr' => $ins_data,
            'tempOrderArr' => (!empty($ins_temp_data) ? $ins_temp_data :  [] ),
            'interface' => 'mobile device',
        ];
        $req_arr = [
            'req_type' => 'POST',
            'req_endpoint' => 'createManualOrder',
            'req_params' => $params,
            'header'     => $received_Token_send
        ];
        $resp = hitCurlRequest($req_arr);
        // echo '<pre>';
        // print_r($resp);
        return $resp;

    } //end add_buy_order_test

    //add_buy_order_triggers_test //Umer Abbas [20-12-19]
    public function add_buy_order_triggers_test($data,  $token) {

        extract($data);

        $created_date = date('Y-m-d G:i:s');
        $order_mode_arr = array();
        if ($order_mode != '') {
            $order_mode_arr = explode("_", $order_mode);
        }


        $inactive_time_new = date("Y-m-d G:00:00", strtotime($inactive_time));
        $this->load->model('admin/mod_dashboard');
        $market_value = $this->mod_dashboard->get_market_value($coin);
        
        $ins_data = array(
            'exchange' => $exchange,
            'price' => '',
            'quantity' => (float) $quantity,
            'usd_worth' => (float) $usd_worth,
            'symbol' => $coin,
            'order_type' => $order_type,
            'admin_id' => $admin_id,
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
            'trail_check' => '',
            'trail_interval' => '',
            'buy_trail_price' => '',
            'status' => 'new',
            'auto_sell' => '',
            'market_value' => '',
            'binance_order_id' => '',
            'is_sell_order' => '',
            'sell_order_id' => '',
            'trigger_type' => $trigger_type,
            'application_mode' => $application_mode,
            'order_mode' => $order_mode,
            'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            'parent_status' => 'parent',
            "pause_status" =>  "play",
            'defined_sell_percentage' => $defined_sell_percentage,
            'sell_profit_percent' => $defined_sell_percentage,
            'buy_one_tip_above' => $buy_one_tip_above,
            'sell_one_tip_below' => $sell_one_tip_below,
            'order_level' => $order_level,
            'current_market_price' => (float) $market_value,
            'custom_stop_loss_percentage' => $custom_stop_loss_percentage,
            'stop_loss_rule' => $stop_loss_rule,
            'activate_stop_loss_profit_percentage' => $activate_stop_loss_profit_percentage,
            'lth_functionality' => $lth_functionality,
            'lth_profit' => $lth_profit,
            // "un_limit_child_orders": "no",
        );

        if(!empty($cost_avg) && $cost_avg == 'yes'){
            $ins_data['cost_avg'] = 'yes';
        }

        if (!empty($inactive_time) && $inactive_time != '') {
            $ins_data['inactive_time'] = $this->mongo_db->converToMongodttime($inactive_time_new);
        }

        $params = [
            'orderArr' => $ins_data,
            'interface' => 'mobile device'
        ]; 
        $req_arr = [
            'req_type' => 'POST',
            'req_endpoint' => 'createAutoOrder',
            'req_params' => $params,
            'header'  => $token
        ];
        $resp = hitCurlRequest($req_arr);
        
        return $resp;

        // $buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data);

        // $log_msg = "Buy Order was Created at Price " . $price;
        // if ($auto_sell == 'yes' && $sell_profit_percent != '') {
        //     $log_msg .= ' with auto sell ' . $sell_profit_percent . '%';
        // }
        // $log_msg .= " From Mobile App";
        // $this->load->model('admin/mod_dashboard');
        // $this->mod_dashboard->insert_order_history_log($buy_order_id, $log_msg, 'buy_created', $admin_id);
        return true;

    } //End add_buy_order_triggers_test

    public function change_inactive_status($id) {
        $upd_arr = array(
            'inactive_status' => 'inactive',
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_arr);
        $this->mongo_db->update('buy_orders', $upd_arr);

        return true;
    } //End of  change_inactive_status

    public function play_pause_status_change($id, $type) {
        $this->mongo_db->where("_id", $this->mongo_db->mongoId($id));
        $this->mongo_db->set(array('pause_status' => $type));
        $this->mongo_db->update("buy_orders");

        return true;
    }

    public function get_user_info() {
        $ip = getenv('HTTP_CLIENT_IP') ?:
        getenv('HTTP_X_FORWARDED_FOR') ?:
        getenv('HTTP_X_FORWARDED') ?:
        getenv('HTTP_FORWARDED_FOR') ?:
        getenv('HTTP_FORWARDED') ?:
        getenv('REMOTE_ADDR');

        $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
        $detail = (array) $details;

        $userAgent = $_SERVER["HTTP_USER_AGENT"];
        $devicesTypes = array(
            "computer" => array("msie 10", "msie 9", "msie 8", "windows.*firefox", "windows.*chrome", "x11.*chrome", "x11.*firefox", "macintosh.*chrome", "macintosh.*firefox", "opera"),
            "tablet" => array("tablet", "android", "ipad", "tablet.*firefox"),
            "mobile" => array("mobile ", "android.*mobile", "iphone", "ipod", "opera mobi", "opera mini"),
            "bot" => array("googlebot", "mediapartners-google", "adsbot-google", "duckduckbot", "msnbot", "bingbot", "ask", "facebook", "yahoo", "addthis"),
            "DigieBot"=>array("iOS","android"),
        );
        foreach ($devicesTypes as $deviceType => $devices) {
            foreach ($devices as $device) {
                if (preg_match("/" . $device . "/i", $userAgent)) {
                    $deviceName = $deviceType;
                }
            }
        }
        $returnArr = $this->getBrowser();

        $array = array(
            'IP' => $ip,
            'location' => $detail['city'] . ',' . $detail['region'] . ', ' . $detail['country'],
            'Geometry' => $detail['loc'],
            'Postal Code' => $detail['postal'],
            'Device' => $deviceName,
            'Date Time' => date('l jS \of F Y h:i:s A'),
            'Direct_device' => $userAgent,
        );

        if($deviceName == 'DigieBot'){
            $info_arr = explode('/',(string)$userAgent);

            $version_mobile_app = explode(' ',$info_arr[1]);


            $explode_ios = explode('iOS',$userAgent);
            $version_ios = explode(')',$explode_ios[1]);

            $array['Browser'] = $version_mobile_app[0] ." ". $returnArr['name'] . " Version " . $version_ios[0] ;
            $array['Operating System'] = $returnArr['platform'];
        }else{
            $array['Browser'] = $returnArr['name'] . " Version " . $returnArr['version'];
            $array['Operating System'] = $returnArr['platform'];
        }

        return $array;
    }

    public function getBrowser() {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        } elseif (preg_match('/Digiebot/i', $u_agent)) {
            $platform = 'ios Device';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        } elseif(preg_match('/ios/i', $u_agent)) {
            $bname = 'ios';
            $ub = "ios";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {$version = "?";}

        $print_arr = array(
            'userAgent' => $u_agent,
            'name' => $bname,
            'version' => $version,
            'platform' => $platform,
            'pattern' => $pattern,
        );

        return $print_arr;
    }

    public function send_logged_in_email($data) {
        $email = $data['email_address'];
        $admin_id = $data['admin_id'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $u_info = $this->get_user_info();
        $noreply_email = "no_reply@digiebot.com";
        $email_from_txt = "Digiebot";
        $email_subject = "Digiebot Login Update";
        $email_body = '<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:14px;font-family:Microsoft Yahei,Arial,Helvetica,sans-serif;padding:0;margin:0;color:#333;background-image:url(https://cryptoconsultant.com/wp-content/uploads/2017/02/bg2.jpg);background-color:#f7f7f7;background-repeat:repeat-x;background-position:bottom left">
		<tbody><tr>
			<td>
					<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
							<tbody><tr>
								<td align="center" valign="middle" style="padding:33px 0">
									<img src="https://app.digiebot.com/assets/images/digiebot_logo.png">
								</td>
							</tr>
							<tr>
								<td>
										<div style="padding:0 30px;background:#fff">
												<table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tbody><tr>
														<td style="border-bottom:1px solid #e6e6e6;font-size:18px;padding:20px 0">
																<table border="0" cellspacing="0" cellpadding="0" width="100%">
																	 <tbody><tr>
																 <td>Login Update</td>
																		<td>

																		</td>
																</tr>
																	</tbody></table>
															 </td></tr>
													<tr>
														<td style="font-size:14px;line-height:30px;padding:20px 0;color:#666">Hello, ' . $first_name . " " . $last_name . '<br>You have just initiated a request to Login in Digiebot account.<strong style="margin:0 5px"><a href="mailto:' . $email . '" target="_blank"></a></strong>Below are the Login Information:</td>
													</tr>
													<tr>
														<td style="padding:5px 0">
															<table width="100%" style="font-size: 12px; text-align: left;">';
        foreach ($u_info as $key => $value) {
            $email_body .= '<tr>
														<th>' . strtoupper($key) . '</th>
														<td>' . strtoupper($value) . '</td>
													</tr>';
        }
        $email_body .= '</table>
														</td>
													</tr>

													<tr>
														<td style="padding:20px 0 10px 0;line-height:26px;color:#666">If this activity is not your own operation, please contact us immediately. </td>
													</tr>
							<tr>
							</tr>
							<tr>
														<td style="padding:30px 0 15px 0;font-size:12px;color:#999;line-height:20px">Digiebot Team<br>Automated message.please do not reply</td>
													</tr>
												</tbody></table>
										</div>
								</td>
							</tr>

							<tr>
								<td align="center" style="font-size:12px;color:#999;padding:20px 0">© ' . date('Y') . ' digiebot.com All Rights Reserved<br>URL：<a style="color:#999;text-decoration:none" href="https://app.digiebot.com/admin" target="_blank">Digiebot Application</a>&nbsp;
		&nbsp;
		E-mail：<a href="mailto:support@digiebot.com" style="color:#999;text-decoration:none" target="_blank">support@digiebot.com</a></td>
									</tr>
								</tbody></table>
						</td>
				</tr>
		</tbody></table>';

        // $config['charset'] = 'utf-8';
        // $config['mailtype'] = 'html';
        // $config['wordwrap'] = TRUE;
        // $config['protocol'] = 'mail';

        $this->config->load('email', TRUE);
        $config = $this->config->item('email');
        

        $this->load->library('email', $config);

        $this->email->from($noreply_email, $email_from_txt);
        $this->email->to($email);
        $this->email->subject($email_subject);
        $this->email->message($email_body);



        //Send Email used amazon ses
        // $this->load->library('Amazon_ses_bulk_email');
        // $this->amazon_ses_bulk_email->send_bulk_email($html_message, $subject, $from, $to, $cc = '', $bcc = '', $title = '');
        // $email_sent = $this->amazon_ses_bulk_email->send_bulk_email($email_body, $email_subject, 'support@digiebot.com', $email, $cc = '', $bcc = '', $title = '');

        $new_body = 'You have just initiated a request to Login in Digiebot account. Below are the Login Information: <br>';
		$new_body .= '<table width="100%" style="font-size: 12px; text-align: left;">';
		foreach ($u_info as $key => $value) {
			$email_body .= '<tr>
										<th>' . strtoupper($key) . '</th>
										<td>' . strtoupper($value) . '</td>
										</tr>';
		}
		$new_body .= '</table>';
		$email_sent = send_mail($admin_id, $email_subject, $new_body);

        if ( 1==1 ) {
            $data_ins['user_id'] = $admin_id;
            $data_ins['login_ip'] = $u_info['IP'];
            $data_ins['login_date_time'] = $this->mongo_db->converToMongodttime(date("Y-m-d H:i:s"));
            $data_ins['login_location'] = $u_info['location'];
            $data_ins['login_device_browser'] = $u_info['Device'] . " " . $u_info['Browser'];
            $data_ins['checking_direct_device'] = $u_info['Direct_device'];
            $this->load->model('admin/mod_login');
            $this->update_login_record($data_ins);
            $data_arr_not['admin_id'] = $admin_id;
            $data_arr_not['type'] = 'security_alerts';
            $data_arr_not['priority'] = 'high';
            $data_arr_not['interface'] = 'Mobile App';
            $data_arr_not['message'] = 'Login Attempted From IP address ' . $u_info['IP'] .' Location '.$u_info['location'] .' '. $data_ins['login_device_browser'];
            $result_not = $this->Mod_notifications->add_notification($data_arr_not);
            return $u_info;
        }
    }

    public function update_login_record($data) {
        $this->mongo_db->insert('user_login_log', $data);
    }

    public function save_settings($data) {
        extract($data);
       
        $update_arr = [];
        if (isset($buy_alerts)) {
            $update_arr['buy_alerts'] = $buy_alerts;
        }
        if (isset($sell_alerts)) {
            $update_arr['sell_alerts'] = $sell_alerts;
        }
        if (isset($trading_alerts)) {
            $update_arr['trading_alerts'] = $trading_alerts;
        }
        if (isset($news_alerts)) {
            $update_arr['news_alerts'] = $news_alerts;
        }
        if (isset($withdraw_alerts)) {
            $update_arr['withdraw_alerts'] = $withdraw_alerts;
        }
        if (isset($security_alerts)) {
            $update_arr['security_alerts'] = $security_alerts;
        }
         //echo json_encode($admin_id);exit;
        $db = $this->mongo_db->customQuery();
        $pdate = $db->users->updateOne(['_id'=>$this->mongo_db->mongoId($admin_id)],['$set'=>$update_arr]);

        
        return true;
    }

    public function record_app_device_token($data) {
        extract($data);
        $ins_arr = array(
            'admin_id' => $admin_id,
            'device_token' => $device_token,
            'device_type' => $device_type,
        );
        $db = $this->mongo_db->customQuery();
        $findArr = array('device_token' => $device_token);
        $ins_data = $db->users_device_tokens->updateOne($findArr, array('$set' => $ins_arr), array('upsert' => true));

        return true;
    }

    //get_notifications
    public function get_notifications($admin_id, $time , $filter) {
        $search_array['admin_id'] = $admin_id;
        $timezone = get_user_timezone($admin_id);

        if($filter == 'important'){

            if ($time == 'today') {
                $date_start = date("Y-m-d 00:00:00", strtotime("today"));
                $date_end = date("Y-m-d 23:59:59", strtotime("today"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "high" ;
            }
            if ($time == 'yesterday') {
                $date_start = date("Y-m-d 00:00:00", strtotime("yesterday"));
                $date_end = date("Y-m-d 23:59:59", strtotime("yesterday"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "high" ;
            }
            if ($time == 'last_week') {
                $date_start = date("Y-m-d 00:00:00", strtotime("-7 days"));
                $date_end = date("Y-m-d 23:59:59", strtotime("-3 days"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "high" ;
            }

        }
        if($filter == 'medium'){
            if ($time == 'today') {
                $date_start = date("Y-m-d 00:00:00", strtotime("today"));
                $date_end = date("Y-m-d 23:59:59", strtotime("today"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "medium" ;
            }
            if ($time == 'yesterday') {
                $date_start = date("Y-m-d 00:00:00", strtotime("yesterday"));
                $date_end = date("Y-m-d 23:59:59", strtotime("yesterday"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "medium" ;
            }
            if ($time == 'last_week') {
                $date_start = date("Y-m-d 00:00:00", strtotime("-7 days"));
                $date_end = date("Y-m-d 23:59:59", strtotime("-3 days"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "medium" ;
            }
            
        }
        if($filter == 'normal'){
            if ($time == 'today') {
                $date_start = date("Y-m-d 00:00:00", strtotime("today"));
                $date_end = date("Y-m-d 23:59:59", strtotime("today"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "low" ;
                
            }
            if ($time == 'yesterday') {
                $date_start = date("Y-m-d 00:00:00", strtotime("yesterday"));
                $date_end = date("Y-m-d 23:59:59", strtotime("yesterday"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "low" ;
                
            }
            if ($time == 'last_week') {
                $date_start = date("Y-m-d 00:00:00", strtotime("-7 days"));
                $date_end = date("Y-m-d 23:59:59", strtotime("-3 days"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
                $search_array['priority'] = "low" ;
                
            }
            
        }else{
            if ($time == 'today') {
                $date_start = date("Y-m-d 00:00:00", strtotime("today"));
                $date_end = date("Y-m-d 23:59:59", strtotime("today"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
            }
            if ($time == 'yesterday') {
                $date_start = date("Y-m-d 00:00:00", strtotime("yesterday"));
                $date_end = date("Y-m-d 23:59:59", strtotime("yesterday"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
            }
            if ($time == 'last_week') {
                $date_start = date("Y-m-d 00:00:00", strtotime("-7 days"));
                $date_end = date("Y-m-d 23:59:59", strtotime("-3 days"));
                $search_array['created_date']['$gte'] = $this->mongo_db->converToMongodttime($date_start);
                $search_array['created_date']['$lte'] = $this->mongo_db->converToMongodttime($date_end);
            }
            
        }

        $this->mongo_db->order_by(array('_id' => -1));
        $this->mongo_db->where($search_array);
        $resp = $this->mongo_db->get('app_notification');

        $notification_arr = array(); //iterator_to_array($resp);
        foreach ($resp as $key => $value) {

            $datetime = $value['created_date']->toDateTime();
            $created_date = $datetime->format(DATE_RSS);
            $datetime = new DateTime($created_date);
            $datetime->format('Y-m-d g:i:s A');

            $new_timezone = new DateTimeZone($timezone);
            $datetime->setTimezone($new_timezone);

            $formated_date_time = $datetime->format('Y-m-d g:i:s A');

            $retArr['_id'] = (string) $value['_id'];
            $retArr['admin_id'] = $value['admin_id'];
            $retArr['order_id'] = $value['order_id'];
            $retArr['type'] = $value['type'];
            $retArr['priority'] = $value['priority'];
            $retArr['message'] = $value['message'];
            $retArr['symbol'] = $value['symbol'];
            $retArr['coin_logo'] = $value['coin_logo'];
            $retArr['created_date_human_readable'] = $value['created_date_human_readable'];
            $retArr['created_date'] = $formated_date_time;
            $time_elapsed_string = time_elapsed_string($formated_date_time, $timezone);
            $retArr['time_ago'] = $time_elapsed_string;
            array_push($notification_arr, $retArr);
        }
        return $notification_arr;
    } //End get_notifications

    public function fetch_settings($admin_id) {
        $search_array['_id'] = $this->mongo_db->mongoId($admin_id);
        $this->mongo_db->where($search_array);
        $resp = $this->mongo_db->get('users');
        $user_arr = iterator_to_array($resp);
        return $user_arr[0];

    }

    public function logout($admin_id, $token) {
        $search_array['admin_id'] = $admin_id;
        $search_array['device_token'] = $token;
        $this->mongo_db->where($search_array);
        $resp = $this->mongo_db->delete('users_device_tokens');
        return true;

    }

    public function app_dashboard($coin_symbol, $admin_id) {
        //fetching market_prices
        $this->load->model('admin/mod_dashboard');
        $date_to_traverse = date("Y-m-d H:i:s", strtotime("-30 minutes"));
        $time = $this->mongo_db->converToMongodttime($date_to_traverse);

        $search_array['coin'] = $coin_symbol;
        $search_array['created_date']['$gte'] = $time;
        // $this->mongo_db->where($search_array);
        // $response = $this->mongo_db->get('market_price_history');
        // echo "<pre>";
        // print_r(iterator_to_array($response));
        // exit;
        $queryHours =
            [
            ['$match' => $search_array],
            ['$group' => ['_id' => [
                'hour' => ['$hour' => '$created_date'],
                'minute' => ['$minute' => '$created_date'],
            ], 'market_value' => ['$last' => '$price'], 'time' => ['$last' => '$created_date']]],
            ['$sort' => ['time' => 1]],
        ];

        $db = $this->mongo_db->customQuery();
        $response = $db->market_prices->aggregate($queryHours);
        $timezone = get_user_timezone($admin_id);
        $fullarray = array();
        $fullarraytest = array();

        
        foreach ($response as $key => $value) {
            
            if (!empty($value)) {

                $datetime = $value['time']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $formated_time22 = $datetime->format('Y-m-d g:i:s A');
                $new_timezone = new DateTimeZone($timezone);
                $datetime->setTimezone($new_timezone);
                $formated_time = $datetime->format('Y-m-d g:i:s A');

                $retArr['id'] = $value["_id"];
                $retArr['price'] = num($value['market_value']);
                $retArr['time'] = $formated_time;

                //getting the buy orders on prices
                $timearr['$gte'] = $this->mongo_db->converToMongodttime(date("Y-m-d H:i:00", strtotime($formated_time22)));
                $timearr['$lte'] = $this->mongo_db->converToMongodttime(date("Y-m-d H:i:59", strtotime($formated_time22)));
                /*$buy_search['market_value'] = (float) $value['market_value'];*/
                $buy_search['created_date'] = $timearr;
                $buy_search['symbol'] = $coin_symbol;
                $buy_search['admin_id'] = $admin_id;
                $buy_search['status'] = 'FILLED';
                $buy_search['is_sell_order'] = 'yes';

                $this->mongo_db->where($buy_search);
                $buy_obj = $this->mongo_db->get("buy_orders");
                $fullarray2 = array();
                foreach ($buy_obj as $valueArr) {

                    $returArr = array();

                    if (!empty($valueArr)) {

                        //$image = $this->mod_coins->get_coin_logo($valueArr['symbol']);

                        $datetime = $valueArr['created_date']->toDateTime();
                        $created_date = $datetime->format(DATE_RSS);

                        $datetime = new DateTime($created_date);
                        $datetime->format('Y-m-d g:i:s A');

                        $new_timezone = new DateTimeZone($timezone);
                        $datetime->setTimezone($new_timezone);
                        $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                        if (empty($valueArr['modified_date'])) {
                            $valueArr['modified_date'] = $valueArr['created_date'];
                        }
                        $datetime111 = $valueArr['modified_date']->toDateTime();
                        $created_date111 = $datetime111->format(DATE_RSS);

                        $datetime111 = new DateTime($created_date111);
                        $datetime111->format('Y-m-d g:i:s A');

                        $new_timezone = new DateTimeZone($timezone);
                        $datetime111->setTimezone($new_timezone);
                        $formated_date_time1 = $datetime111->format('Y-m-d g:i:s A');

                        $time_elapsed_string = time_elapsed_string($formated_date_time1, $timezone, false);

                        //$//score_avg = $this->mod_dashboard->get_score_avg($valueArr['symbol']);
                        $returArr['_id'] = (string) $valueArr['_id'];

                        $returArr['price'] = num($valueArr['price']);
                        $returArr['quantity'] = $valueArr['quantity'];

                        $returArr['market_value'] = num($valueArr['market_value']);

                        $returArr['time_ago'] = $time_elapsed_string;
                        $returArr['time_zone'] = $timezone;

                        $this->load->model('admin/mod_dashboard');

                        $market_value = $this->mod_dashboard->get_market_value($valueArr['symbol']);
                        if ($valueArr['status'] != 'new' && $valueArr['status'] != 'error') {
                            $market_value333 = num($valueArr['market_value']);
                        } else {
                            $market_value333 = num($market_value);
                        }
                        if ($valueArr['status'] == 'new') {
                            $current_order_price = num($valueArr['price']);
                        } else {
                            $current_order_price = num($valueArr['market_value']);
                        }
                        if ($valueArr['is_sell_order'] != 'sold' && $valueArr['is_sell_order'] != 'yes' && $valueArr['status'] != 'error') {
                            $current_data = $market_value333 - $current_order_price;
                            $market_data = ($current_data * 100 / $market_value333);
                            $market_data = number_format((float) $market_data, 2, '.', '');
                        }

                        if ($valueArr['status'] == 'FILLED') {

                            if ($valueArr['is_sell_order'] == 'yes') {

                                $current_data = num($market_value) - num($valueArr['market_value']);
                                $market_data = ($current_data * 100 / $market_value);
                                $market_data = number_format((float) $market_data, 2, '.', '');
                            }
                            if ($valueArr['is_sell_order'] == 'sold') {
                                $current_data = num($valueArr['market_sold_price']) - num($valueArr['market_value']);
                                $market_data = ($current_data * 100 / $valueArr['market_sold_price']);
                                $market_data = number_format((float) $market_data, 2, '.', '');
                            }
                        }
                        $returArr['profit_data'] = $market_data;
                    }

                    $fullarray2[] = $returArr;
                }
                $retArr['trades'] = $fullarray2;
                array_push($fullarraytest, $retArr);
            }
        }
        
        $current_market_price = $this->get_last_price($coin_symbol);
        $filter_array['filter_coin'] = $coin_symbol;
        $total_open_trades = $this->count_orders_new("open", "live", $admin_id, $filter_array);
        $total_sold_trades = $this->count_orders_new("sold", "live", $admin_id, $filter_array);
        $balance = $this->get_coin_balance($coin_symbol, $admin_id);
        $last_price = $this->get_24_hour_price_change($coin_symbol);
        $score_avg = $this->mod_dashboard->get_score_avg($coin_symbol);

        $fullarray['chart_data'] = $fullarraytest;
        $fullarray['current_market_price'] = $current_market_price;
        $fullarray['total_open_trades'] = $total_open_trades;
        $fullarray['total_sold_trades'] = $total_sold_trades;
        $fullarray['balance'] = $balance;
        $fullarray['last_price'] = $last_price;
        $fullarray['score'] = $score_avg;
        $fullarray['avg_profit'] = $this->calculate_avg_profit($coin_symbol, $admin_id);
        $this->mongo_db->where(array('coin' => $coin_symbol));
        $coin_meta_get = $this->mongo_db->get("coin_meta");
        $coin_meta_arr = iterator_to_array($coin_meta_get);
        $coin_meta = $coin_meta_arr[0];

        $fullarray['pressure_diff'] = $coin_meta['pressure_diff'];
        $fullarray['black_wall_pressure'] = $coin_meta['black_wall_pressure'];
        $fullarray['yellow_wall_pressure'] = $coin_meta['yellow_wall_pressure'];
        $fullarray['last_candle_type'] = $coin_meta['last_candle_type'];

        return $fullarray;
    }

    //app_dashboard_test //Umer Abbas [12-11-19]
    public function app_dashboard_test($coin_symbol, $admin_id) {
        //fetching market_prices
        $this->load->model('admin/mod_dashboard');
        $date_to_traverse = date("Y-m-d H:i:s", strtotime("-30 minutes"));
        $time = $this->mongo_db->converToMongodttime($date_to_traverse);

        $search_array['coin'] = $coin_symbol;
        $search_array['time']['$gte'] = $time;
        // $this->mongo_db->where($search_array);
        // $response = $this->mongo_db->get('market_price_history');
        // echo "<pre>";
        // print_r(iterator_to_array($response));
        // exit;
        $queryHours =
            [
            ['$match' => $search_array],
            ['$group' => ['_id' => [
                'hour' => ['$hour' => '$time'],
                'minute' => ['$minute' => '$time'],
            ], 'market_value' => ['$last' => '$market_value'], 'time' => ['$last' => '$time']]],
            ['$sort' => ['time' => 1]],
        ];

        $db = $this->mongo_db->customQuery();
        $response = $db->market_price_history->aggregate($queryHours);
        $timezone = get_user_timezone($admin_id);
        $fullarray = array();
        $fullarraytest = array();
        foreach ($response as $key => $value) {
            if (!empty($value)) {

                $datetime = $value['time']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $formated_time22 = $datetime->format('Y-m-d g:i:s A');
                $new_timezone = new DateTimeZone($timezone);
                $datetime->setTimezone($new_timezone);
                $formated_time = $datetime->format('Y-m-d g:i:s A');

                $retArr['id'] = $value["_id"];
                $retArr['price'] = num($value['market_value']);
                $retArr['time'] = $formated_time;

                //getting the buy orders on prices
                $timearr['$gte'] = $this->mongo_db->converToMongodttime(date("Y-m-d H:i:00", strtotime($formated_time22)));
                $timearr['$lte'] = $this->mongo_db->converToMongodttime(date("Y-m-d H:i:59", strtotime($formated_time22)));
                /*$buy_search['market_value'] = (float) $value['market_value'];*/
                $buy_search['created_date'] = $timearr;
                $buy_search['symbol'] = $coin_symbol;
                $buy_search['admin_id'] = $admin_id;
                $buy_search['status'] = 'FILLED';
                $buy_search['is_sell_order'] = 'yes';

                $this->mongo_db->where($buy_search);
                $buy_obj = $this->mongo_db->get("buy_orders");
                $fullarray2 = array();
                foreach ($buy_obj as $valueArr) {

                    $returArr = array();

                    if (!empty($valueArr)) {

                        //$image = $this->mod_coins->get_coin_logo($valueArr['symbol']);

                        $datetime = $valueArr['created_date']->toDateTime();
                        $created_date = $datetime->format(DATE_RSS);

                        $datetime = new DateTime($created_date);
                        $datetime->format('Y-m-d g:i:s A');

                        $new_timezone = new DateTimeZone($timezone);
                        $datetime->setTimezone($new_timezone);
                        $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                        if (empty($valueArr['modified_date'])) {
                            $valueArr['modified_date'] = $valueArr['created_date'];
                        }
                        $datetime111 = $valueArr['modified_date']->toDateTime();
                        $created_date111 = $datetime111->format(DATE_RSS);

                        $datetime111 = new DateTime($created_date111);
                        $datetime111->format('Y-m-d g:i:s A');

                        $new_timezone = new DateTimeZone($timezone);
                        $datetime111->setTimezone($new_timezone);
                        $formated_date_time1 = $datetime111->format('Y-m-d g:i:s A');

                        $time_elapsed_string = time_elapsed_string($formated_date_time1, $timezone, false);

                        //$//score_avg = $this->mod_dashboard->get_score_avg($valueArr['symbol']);
                        $returArr['_id'] = (string) $valueArr['_id'];

                        $returArr['price'] = num($valueArr['price']);
                        $returArr['quantity'] = $valueArr['quantity'];

                        $returArr['market_value'] = num($valueArr['market_value']);

                        $returArr['time_ago'] = $time_elapsed_string;
                        $returArr['time_zone'] = $timezone;

                        $this->load->model('admin/mod_dashboard');

                        $market_value = $this->mod_dashboard->get_market_value($valueArr['symbol']);
                        if ($valueArr['status'] != 'new' && $valueArr['status'] != 'error') {
                            $market_value333 = num($valueArr['market_value']);
                        } else {
                            $market_value333 = num($market_value);
                        }
                        if ($valueArr['status'] == 'new') {
                            $current_order_price = num($valueArr['price']);
                        } else {
                            $current_order_price = num($valueArr['market_value']);
                        }
                        if ($valueArr['is_sell_order'] != 'sold' && $valueArr['is_sell_order'] != 'yes' && $valueArr['status'] != 'error') {
                            $current_data = $market_value333 - $current_order_price;
                            $market_data = ($current_data * 100 / $market_value333);
                            $market_data = number_format((float) $market_data, 2, '.', '');
                        }

                        if ($valueArr['status'] == 'FILLED') {

                            if ($valueArr['is_sell_order'] == 'yes') {

                                $current_data = num($market_value) - num($valueArr['market_value']);
                                $market_data = ($current_data * 100 / $market_value);
                                $market_data = number_format((float) $market_data, 2, '.', '');
                            }
                            if ($valueArr['is_sell_order'] == 'sold') {
                                $current_data = num($valueArr['market_sold_price']) - num($valueArr['market_value']);
                                $market_data = ($current_data * 100 / $valueArr['market_sold_price']);
                                $market_data = number_format((float) $market_data, 2, '.', '');
                            }
                        }
                        $returArr['profit_data'] = $market_data;
                    }

                    $fullarray2[] = $returArr;
                }
                $retArr['trades'] = $fullarray2;
                array_push($fullarraytest, $retArr);
            }
        }

        $current_market_price = $this->get_last_price($coin_symbol);
        $filter_array['filter_coin'] = $coin_symbol;
        $total_open_trades = $this->count_orders_new("open", "live", $admin_id, $filter_array);
        $total_sold_trades = $this->count_orders_new("sold", "live", $admin_id, $filter_array);
        $balance = $this->get_coin_balance($coin_symbol, $admin_id);
        $last_price = $this->get_24_hour_price_change($coin_symbol);
        $score_avg = $this->mod_dashboard->get_score_avg($coin_symbol);

        $fullarray['chart_data'] = $fullarraytest;
        $fullarray['current_market_price'] = $current_market_price;
        $fullarray['total_open_trades'] = $total_open_trades;
        $fullarray['total_sold_trades'] = $total_sold_trades;
        $fullarray['balance'] = $balance;
        $fullarray['last_price'] = $last_price;
        $fullarray['score'] = $score_avg;
        $fullarray['avg_profit'] = $this->calculate_avg_profit($coin_symbol, $admin_id);
        $this->mongo_db->where(array('coin' => $coin_symbol));
        $coin_meta_get = $this->mongo_db->get("coin_meta");
        $coin_meta_arr = iterator_to_array($coin_meta_get);
        $coin_meta = $coin_meta_arr[0];

        $fullarray['pressure_diff'] = $coin_meta['pressure_diff'];
        $fullarray['black_wall_pressure'] = $coin_meta['black_wall_pressure'];
        $fullarray['yellow_wall_pressure'] = $coin_meta['yellow_wall_pressure'];
        $fullarray['last_candle_type'] = $coin_meta['last_candle_type'];

        return $fullarray;
    }//app_dashboard_test

    public function calculate_avg_profit($coin_symbol = '', $admin_id = '') {

        $search['symbol'] = $coin_symbol;
        $search['admin_id'] = $admin_id;
        $search['application_mode'] = 'live';
        $connetct = $this->mongo_db->customQuery();
        $sold_curser = $connetct->sold_buy_orders->find($search);
        $orders = iterator_to_array($sold_curser);

        //$orders = array_merge_recursive($pending_arr, $sold_arr);

        // foreach ($orders as $key => $part) {
        //     $sort[$key] = (string) $part['modified_date'];
        // }

        // array_multisort($sort, SORT_DESC, $orders);

        foreach ($orders as $key => $value) {

            $total_sold_orders++;
            $market_sold_price = $value['market_sold_price'];
            $current_order_price = $value['market_value'];
            $quantity = $value['quantity'];

            $current_data2222 = $market_sold_price - $current_order_price;
            $profit_data = ($current_data2222 * 100 / $market_sold_price);

            $profit_data = number_format((float) $profit_data, 2, '.', '');
            $total_btc = $quantity * (float) $current_order_price;
            $total_profit += $total_btc * $profit_data;
            $total_quantity += $total_btc;

        }
        if ($total_quantity == 0) {
            $total_quantity = 1;
        }
        $avg_profit = $total_profit / $total_quantity;

        return $avg_profit;
    }

    public function edit_profile($data,$user_id) {

        if(empty($data)){
            return true;
        }
        extract($data);

        if (isset($first_name)) {
            $update_arr['first_name'] = $first_name;
        }
        if (isset($last_name)) {
            $update_arr['last_name'] = $last_name;
        }
        // if (isset($username)) {
        //     $update_arr['username'] = $username;
        // }
        // if (isset($email_address)) {
        //     $update_arr['email_address'] = $email_address;
        // }
        if (isset($phone_number)) {
            $update_arr['phone_number'] = $phone_number;
        }
        // if (isset($password)) {
        //     $update_arr['password'] = $password;
        // }
        if (isset($timezone)) {
            $update_arr['timezone'] = $timezone;
        }
        if (isset($profile_image)) {
            $update_arr['profile_image'] = $profile_image;
        }
        if (isset($default_exchange)) {
            $update_arr['default_exchange'] = $default_exchange;
        }

        // $this->mongo_db->where(array('_id' => $admin_id));
        // $this->mongo_db->set($update_arr);
        // $this->mongo_db->update('users');

        $db = $this->mongo_db->customQuery();
        $user_id = $this->mongo_db->mongoId((string) $user_id);
        $db->users->updateMany(['_id' => $user_id], ['$set' => $update_arr]);

        return true;
    }

    //get_market_price //Umer Abbas [24-12-19]
    public function get_market_price($coin, $exchange){

        $collection = ($exchange == 'binance') ? 'market_prices' : 'market_prices_'.$exchange;

        $this->mongo_db->where(array('coin' => $coin));
        $this->mongo_db->limit(1);
        $this->mongo_db->sort(array('created_date' => 'desc'));
        $responseArr = $this->mongo_db->get($collection);
        $price = iterator_to_array($responseArr);
        if (!empty($price)) {
            return num($price[0]['price']);
        } else {
            return 0;
        }
    }//end get_market_price

    public function get_coin_min_notation($symbol, $exchange){

        $collection_name = $exchange == 'binance' ? 'market_min_notation' : 'market_min_notation_'.$exchange;

        $search_array['symbol'] = $symbol;
        $this->mongo_db->where($search_array);
        $res = $this->mongo_db->get($collection_name);

        $min_notation_arr = iterator_to_array($res);
        if(!empty($min_notation_arr)){
            return $min_notation_arr[0];
        }
        return [];
    }

}
?>
