<?php
class mod_market extends CI_Model {

    public function get_coins_sql() {
        $id = $this->session->userdata('admin_id');

        $this->db->select('*');
        $this->db->where('user_id', $id);
        $this->db->from('coins');
        $this->db->join('user_coins', 'coins.id = user_coins.coin_id');
        $query = $this->db->get();
        $coins_arr = $query->result_array();
        return $coins_arr;
    }

    public function get_coins() {
        $user_id = $this->session->userdata('admin_id');
        $this->mongo_db->sort(array('_id' => -1));
        $this->mongo_db->where(array('user_id' => ($user_id), 'symbol' => array('$nin' => array('', null, 'BTC', 'BNBBTC'))));
        $get_coins = $this->mongo_db->get('coins');
        $coins_arr = iterator_to_array($get_coins);

        return $coins_arr;
    } //end get_all_coins

    public function get_coin_info($coin) {
        $this->mongo_db->where(array('symbol' => $coin));
        $get_coin = $this->mongo_db->get('coins');
        $coin_arr = iterator_to_array($get_coin);
        return $coin_arr[0];
    }

    public function get_user_balance($coin, $user_id) {

        $this->mongo_db->where(array('symbol' => $coin, 'user_id' => $user_id));
        $get_coin = $this->mongo_db->get('coins');
        $coin_arr = iterator_to_array($get_coin);
        $coin_arr = $coin_arr[0];
        return $coin_arr['coin_balance'];
    }

    public function get_coin_balance($coin, $user_id) {

        $this->mongo_db->where(array('coin_symbol' => $coin, 'user_id' => (string) $user_id));
        $get_coin = $this->mongo_db->get('user_wallet');
        $coin_arr = iterator_to_array($get_coin);
        $coin_arr = $coin_arr[0];
        return $coin_arr['coin_balance'];
    }

    public function get_coin_keywords($coin) {

        $this->mongo_db->where(array('symbol' => $coin));
        $get_coin = $this->mongo_db->get('coins');
        $coin_arr = iterator_to_array($get_coin);
        $coin_arr = $coin_arr[0];
        return $coin_arr['coin_keywords'];
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
        return number_format($market_value, 10);
    }

    public function get_market_trades($symbol) {
        $admin_id = $this->session->userdata('admin_id');
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
        // $date = date('Y-m-d H:i:s', strtotime('-24 hours'));
        // $this->mongo_db->where(array('coin' => $symbol));
        // $this->mongo_db->where(array('time' => array('$gte' => $this->mongo_db->converToMongodttime($date))));
        // $this->mongo_db->order_by(array('time' => -1));
        // $res = $this->mongo_db->get('market_price_history');
        // $result_arr = iterator_to_array($res);
        //
        // $count = count($result_arr);
        // $new_number = (float) $result_arr[0]->market_value;
        // $old_number = (float) $result_arr[$count - 1]->market_value;
        //
        // $number = (float) $new_number - $old_number;
        //
        // $per_number = (float) ($number / $new_number) * 100;

        $this->mongo_db->where(array('symbol' => $symbol));
        $res = $this->mongo_db->get('coin_price_change');
        $result_arr = iterator_to_array($res);

        return array('change' => num($result_arr[0]['priceChange']), 'percentage' => number_format($result_arr[0]['priceChangePercent'], 2));
    }

    public function get_coin_news($keywords) {

        $db = $this->mongo_db->customQuery();

        $search_array['Score'] = array('$ne' => NULL);
        $search_array['keyword'] = array('$in' => $keywords);
        $qr = array('sort' => array('_id' => -1), 'limit' => 20);

        $resp = $db->coins_news->find($search_array, $qr);

        foreach ($resp as $valueArr) {
            $returArr = array();

            if (!empty($valueArr)) {
                $datetime = $valueArr['Date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $keywrdslist = iterator_to_array($valueArr['keyword']);

                $returArr['_id'] = $valueArr['_id'];
                $returArr['keyword'] = $keywrdslist;
                $returArr['coin'] = $valueArr['coin'];
                $returArr['news'] = $valueArr['News'];
                $returArr['date'] = $formated_date_time;
                $returArr['score'] = $valueArr['Score'];
                $returArr['source'] = $valueArr['source'];
                $returArr['factor'] = $valueArr['factor'];
            }

            $fullarray[] = $returArr;
        }

        /*echo "<pre>";
        print_r($fullarray);
         */
        return $fullarray;
    }

    public function test($keywords) {
        $returArr = array();
        for ($i = 0; $i < 5; $i++) {
            $created_datetime = date('Y-m-d', strtotime("-" . $i . " day"));
            $start_date = $created_datetime . " 00:00:00";
            $end_date = $created_datetime . " 23:59:59";

            $orig_date = new DateTime($start_date);
            $orig_date = $orig_date->getTimestamp();
            $start_date11 = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

            $orig_date222 = new DateTime($end_date);
            $orig_date222 = $orig_date222->getTimestamp();
            $end_date222 = new MongoDB\BSON\UTCDateTime($orig_date222 * 1000);

            $db = $this->mongo_db->customQuery();

            $search_array['Score'] = array('$ne' => NULL);
            $search_array['Date'] = array('$gte' => $start_date11, '$lte' => $end_date222);
            $search_array['keyword'] = array('$in' => $keywords);
            $qr = array('sort' => array('_id' => -1));
            $resp = $db->coins_news->find($search_array, $qr);

            foreach ($resp as $valueArr) {

                if (!empty($valueArr)) {
                    $datetime = $valueArr['Date']->toDateTime();
                    $created_date = $datetime->format(DATE_RSS);

                    $datetime = new DateTime($created_date);
                    $datetime->format('d-m-Y');

                    $new_timezone = new DateTimeZone('Asia/Karachi');
                    $datetime->setTimezone($new_timezone);
                    $formated_date_time = $datetime->format('d-m-Y');
                    $returArr[$i][] = array(
                        'score' => $valueArr['Score'],
                        'created_date' => $formated_date_time,
                    );
                }
            }
        }
        return $returArr;
        exit;
    }
}
?>
