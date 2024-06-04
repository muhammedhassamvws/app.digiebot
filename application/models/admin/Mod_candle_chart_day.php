<?php

/** Mod_candle_chart **/

class mod_candle_chart_day extends CI_Model {

    function __construct() {

        parent::__construct();
        $this->load->model("admin/mod_sockets");
        $this->load->model("admin/mod_custom_script");
    }

    public function get_candelstick_data_from_database($global_symbol, $periods, $from_date_object, $to_date_object, $previous_date, $forward_date) {

        $timezone = $this->session->userdata('timezone');

        $this->mongo_db->where(array('coin' => $global_symbol, 'periods' => $periods));

        if ($from_date_object && $to_date_object) {

            $this->mongo_db->where_gte('timestampDate', $from_date_object);

            $this->mongo_db->where_lte('timestampDate', $to_date_object);

        }

        if ($previous_date != '') {

            $previous_date = $previous_date / 1000;

            $previous_date = date("Y-m-d H:i:s", $previous_date);

            $previous_date_date_mongo = $this->mongo_db->converToMongodttime($previous_date);

            $this->mongo_db->where_lte('timestampDate', $previous_date_date_mongo);

        }

        if ($forward_date != '') {

            $forward_date = $forward_date / 1000;

            $forward_date = date("Y-m-d H:i:s", $forward_date);

            $forward_date_date_mongo = $this->mongo_db->converToMongodttime($forward_date);

            $this->mongo_db->where_gt('timestampDate', $forward_date_date_mongo);

            $this->mongo_db->sort(array('timestampDate' => 1)); //ASC/DESC

        } else {

            $this->mongo_db->sort(array('timestampDate' => -1)); //ASC/DESC

        }

        $this->mongo_db->limit(30);

        $responseArr = $this->mongo_db->get('market_chart_dailybase');

        $final_arr = array();

        foreach ($responseArr as $val_arr) {

            $final_arr[] = array(

                '_id' => $myText = (string) $val_arr['_id'],

                'timestampDate' => $val_arr['timestampDate'],

                'close' => $val_arr['close'],

                'open' => $val_arr['open'],

                'high' => $val_arr['high'],

                'low' => $val_arr['low'],

                'volume' => $val_arr['volume'],

                'openTime' => $val_arr['openTime'],

                'closeTime' => $val_arr['closeTime'],

                'coin' => $val_arr['coin'],

                'candel_status' => $val_arr['candel_status'],

                'candle_type' => $val_arr['candle_type'],

                'openTime_human_readible' => $val_arr['openTime_human_readible'],

                'closeTime_human_readible' => $val_arr['closeTime_human_readible'],

                'demand_base_candel' => $val_arr['demand_base_candel'],

                'supply_base_candel' => $val_arr['supply_base_candel'],

                'global_swing_status' => $val_arr['global_swing_status'],

                'global_swing_parent_status' => $val_arr['global_swing_parent_status'],

                'rejected_candle' => $val_arr['rejected_candle'],

            );

        }

        if ($forward_date == '') {

            $final_arr = array_reverse($final_arr);

        }

        return $final_arr;

    }/** End of get_candelstick_data_from_database **/

    public function get_market_history_for_candel($symbol) {

        $pipeline = array(

            '$group' => array('_id' => '$hour', 'volume' => array('$sum' => '$volume'),

                'type' => array('$first' => '$type'),

                'coin' => array('$first' => '$coin'),

                'timestamp' => array('$first' => '$timestamp'),

                'price' => array('$first' => '$price'),

                'hour' => array('$first' => '$hour'),

                'hour_timestamp' => array('$first' => '$hour_timestamp'),

            ),

        );

        $project = array(

            '$project' => array(

                "_id" => 1,

                "price" => 1,

                "volume" => 1,

                "type" => 1,

                "coin" => 1,

                'timestamp' => 1,

                'hour' => 1,

                'hour_timestamp' => 1,

            ),

        );

        $match = array(

            '$match' => array(

                'type' => 'ask',

                'coin' => $symbol,

            ),

        );

        $sort = array('$sort' => array('hour' => -1));

        $limit = array('$limit' => 1000);

        $connect = $this->mongo_db->customQuery();

        $market_history_Arr_ask = $connect->market_trade_hourly_history->aggregate(array($project, $match, $pipeline, $sort, $limit));

        $market_history_Arr_ask = iterator_to_array($market_history_Arr_ask);

        $ask_price_volume_arr = array();

        if (count($market_history_Arr_ask) > 0) {

            foreach ($market_history_Arr_ask as $key => $value) {

                $ask_price_volume_arr[$value['price']] = $value['volume'];

            }

        }

        $match = array(

            '$match' => array(

                'type' => 'bid',

                'coin' => $symbol,

            ),

        );

        $market_history_Arr_bit = $connect->market_trade_hourly_history->aggregate(array($project, $match, $pipeline, $sort, $limit));

        $market_history_Arr_bit = iterator_to_array($market_history_Arr_bit);

        $bid_volume_arr = array();

        $total_volume = array();

        if (count($market_history_Arr_bit) > 0) {

            foreach ($market_history_Arr_bit as $key => $value) {

                $total_volume[$value['hour']] = $ask_volume_arr[$value['hour']] + $value['volume'];

                $bid_volume_arr[$value['hour']] = $value['volume'];

            }

        }

        $response_arr['bid_volume_arr'] = array_reverse($bid_volume_arr);

        $response_arr['ask_volume_arr'] = array_reverse($ask_volume_arr);

        $response_arr['total_volume_arr'] = array_reverse($total_volume);

        $max_volumer = max($total_volume);

        return $response_arr;

    } //End of get_market_history_for_candel

    public function get_candle_price_volume_detail($symbol, $start_date, $end_date, $unit_value) {

        $bid_arr_volume = $this->get_bid_price_volume($symbol, $start_date, $end_date);

        $ask_arr_volume = $this->get_ask_price_volume($symbol, $start_date, $end_date);

        $total_volume_arr = array();

        $total_volume = 0;

        if (count($ask_arr_volume) > 0) {

            foreach ($ask_arr_volume as $price => $valume) {

                $total_volume_arr[$price] = $bid_arr_volume[$price] + $valume;

            }

        }

        foreach ($bid_arr_volume as $bid_price => $bid_volume) {

            if (!array_key_exists($bid_price, $total_volume_arr)) {

                $total_volume_arr[$bid_price] = $bid_volume;

            }

        }

        $max_volume = max($total_volume_arr);

        $retur_data['bid_arr_volume'] = $bid_arr_volume;

        $retur_data['ask_arr_volume'] = $ask_arr_volume;

        $retur_data['total_volume_arr'] = $total_volume_arr;

        $retur_data['max_volume'] = $max_volume;

        $retur_data['unit_value'] = $unit_value;

        return $retur_data;

    }/** End of get_candle_price_volume_detail **/

    public function get_hour_volume_array_detail($symbol, $start_date, $end_date) {

        $ask_arr_volume_hourly = $this->get_ask_price_volume_for_hour($symbol, $start_date, $end_date, 'ask');

        $bid_arr_volume_hourly = $this->get_ask_price_volume_for_hour($symbol, $start_date, $end_date, 'bid');

        $total_volume_arr = array();

        $total_volume = 0;

        if (count($ask_arr_volume_hourly) > 0) {

            foreach ($ask_arr_volume_hourly as $date => $valume) {

                $total_volume_arr[$date] = $bid_arr_volume_hourly[$date] + $valume;

            }

        }

        foreach ($bid_arr_volume_hourly as $bid_date => $bid_volume) {

            if (!array_key_exists($bid_date, $total_volume_arr)) {

                $total_volume_arr[$bid_date] = $bid_volume;

            }

        }

        $max_volume = max($total_volume_arr);

        $retur_data['bid_hour_arr_volume'] = $bid_arr_volume_hourly;

        $retur_data['ask_hour_arr_volume'] = $ask_arr_volume_hourly;

        $retur_data['total_hour_volume_arr'] = $total_volume_arr;

        $retur_data['max_volume_hourly'] = $max_volume;

        return $retur_data;

    } /*** End of get_hour_volume_array_detail ***/

    public function get_ask_price_volume_for_hour($symbol, $start_date, $end_date, $type) {

        $connect = $this->mongo_db->customQuery();

        $pipeline = array(

            '$group' => array('_id' => '$time',

                'bid_quantity' => array('$sum' => '$bid_quantity'),

                'ask_quantity' => array('$sum' => '$ask_quantity'),

                'coin' => array('$first' => '$coin'),

                'time' => array('$first' => '$time'),

            ),

        );

        $project = array(

            '$project' => array(

                "_id" => 1,

                "bid_quantity" => 1,

                "ask_quantity" => 1,

                "coin" => 1,

                'time' => 1,

            ),

        );

        $match = array(

            '$match' => array(

                'coin' => $symbol,

                'time' => array('$gte' => strtotime($start_date), '$lte' => strtotime($end_date)),

            ),

        );

        $sort = array('$sort' => array('time' => 1));

        $limit = array('$limit' => 1000);

        $connect = $this->mongo_db->customQuery();

        $market_history_Arr_ask = $connect->fifteen_min_market_history->aggregate(array($project, $match, $pipeline, $sort, $limit));

        $market_history_Arr_ask = iterator_to_array($market_history_Arr_ask);

        $ask_volume_arr = array();

        $bid_volume_arr = array();

        if (count($market_history_Arr_ask) > 0) {

            foreach ($market_history_Arr_ask as $key => $value) {

                $bid_volume_arr[$value['time']] = $value['bid_quantity'];

                $ask_volume_arr[$value['time']] = $value['ask_quantity'];

            }

        }

        if ($type == 'bid') {

            return $bid_volume_arr;

        } else if ($type == 'ask') {

            return $ask_volume_arr;

        }

    }/** End of get_ask_price_volume_for_hour **/

    public function get_ask_price_volume($symbol, $start_date, $end_date) {

        $connect = $this->mongo_db->customQuery();

        //$this->mongo_db->where('type', 'ask');

        $this->mongo_db->where('coin', $symbol);

        $this->mongo_db->where_gte('time', strtotime($start_date));

        $this->mongo_db->where_lt('time', strtotime($end_date));

        $this->mongo_db->sort(array('time' => 'desc'));

        $responseArr = $this->mongo_db->get('market_chart_dailybase');

        $responseArr = iterator_to_array($responseArr);

        $ask_price_volume_arr = array();

        $full_arr = array();

        if (count($responseArr) > 0) {

            foreach ($responseArr as $value) {

                $ask_price_volume_arr[$value['time']] = $value['ask_quantity'];

            }

        }

        ksort($ask_price_volume_arr);

        return $ask_price_volume_arr;

    }/** End of get_bid_price_volume**/

    public function get_bid_price_volume($symbol, $start_date, $end_date) {

        $connect = $this->mongo_db->customQuery();

        //$this->mongo_db->where('type', 'ask');

        $this->mongo_db->where('coin', $symbol);

        $this->mongo_db->where_gte('time', strtotime($start_date));

        $this->mongo_db->where_lt('time', strtotime($end_date));

        $this->mongo_db->sort(array('time' => 'desc'));

        $responseArr = $this->mongo_db->get('fifteen_min_market_history');

        $responseArr = iterator_to_array($responseArr);

        $ask_price_volume_arr = array();

        $full_arr = array();

        if (count($responseArr) > 0) {

            foreach ($responseArr as $value) {

                $ask_price_volume_arr[$value['time']] = $value['bid_quantity'];

            }

        }

        ksort($ask_price_volume_arr);

        return $ask_price_volume_arr;

    }/** End of get_bid_price_volume**/

    public function get_date_range_for_history($symbol) {

        $connect = $this->mongo_db->customQuery();

        $pipeline = array(

            '$group' => array('_id' => '$time',

                'bid_quantity' => array('$sum' => '$bid_quantity'),

                'ask_quantity' => array('$sum' => '$ask_quantity'),

                'coin' => array('$first' => '$coin'),

                'time' => array('$first' => '$time'),

            ),

        );

        $project = array(

            '$project' => array(

                "_id" => 1,

                "bid_quantity" => 1,

                "ask_quantity" => 1,

                "coin" => 1,

                'time' => 1,

            ),

        );

        $match = array(

            '$match' => array(

                'coin' => $symbol,

            ),

        );

        $sort = array('$sort' => array('time' => -1));

        $limit = array('$limit' => 8);

        $connect = $this->mongo_db->customQuery();

        $market_history_Arr_ask = $connect->fifteen_min_market_history->aggregate(array($project, $match, $pipeline, $sort, $limit));

        $market_history_Arr_ask = iterator_to_array($market_history_Arr_ask);

        $var_total_hour_array = array();

        $ask_volume_arr = array();

        if (count($market_history_Arr_ask) > 0) {

            foreach ($market_history_Arr_ask as $key => $value) {

                $ask_volume_arr[$value['time']] = $value['quantity'];

                array_push($var_total_hour_array, $value['time']);

            }

        }

        return $var_total_hour_array;

    }/** End of get_date_range_for_history**/

    public function delete_data_from_data_base() {

        $removeTime = date('Y-m-d G:i:s', strtotime('-1 hour', strtotime(date("Y-m-d G:i:s"))));

        $orig_date = new DateTime($removeTime);

        $orig_date = $orig_date->getTimestamp();

        $created_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

        $db = $this->mongo_db->customQuery();

        ///////////////////////////////////////////////////////////////

        $delectmarket_prices = $db->market_prices->deleteMany(array('created_date' => array('$lte' => $created_date)));

        $delectmarket_trades = $db->market_trades->deleteMany(array('created_date' => array('$lte' => $created_date)));

    } //delete_data_from_data_base

    public function update_candel_for_formul_values($data_arr, $global_symbol) {

        $response = 'no data found';

        $db = $this->mongo_db->customQuery();

        if (count($data_arr) > 0) {

            foreach ($data_arr as $arr) {

                $where = array('openTime_human_readible' => $arr->openTime_human_readible, 'coin' => $global_symbol);

                $set = array('$set' => array('candel_status' => $arr->candel_status, 'candle_type' => $arr->candle_type, 'ask_volume' => (float) $arr->ask_volume, 'bid_volume' => (float) $arr->bid_volume, 'total_volume' => (float) $arr->total_volume));

                $response = $db->market_chart_fifteen_minutes->updateMany($where, $set);

                $response = 'ok';

            }

        }

        return $response;

    } //End o fupdate_candel_for_formul_values

    //get_order_array

    public function get_order_array($symbol, $admin_id, $global_mode) {

        $search_Array = array(

            'symbol' => $symbol,

            'is_sell_order' => 'sold',

            'admin_id' => $admin_id,

            'application_mode' => $global_mode,

        );

        $this->mongo_db->where($search_Array);

        $res = $this->mongo_db->get('buy_orders');

        $final_arr = array();

        $buy_orders_arr = iterator_to_array($res);

        foreach ($buy_orders_arr as $key => $arr) {

            $buy_order_id = $arr['_id'];

            $buy_date = $arr['created_date'];

            if ($arr['buy_date']) {

                $buy_date = $arr['buy_date'];

            }

            $datetime = $buy_date->toDateTime();

            $created_date = $datetime->format(DATE_RSS);

            $market_sold_price = $arr['market_sold_price'];

            $datetime = new DateTime($created_date);

            $datetime->format('Y-m-d H:00:00');

            $formated_date_time = $datetime->format('Y-m-d H:00:00');

            $buy_order_date = $formated_date_time;

            $search['buy_order_id'] = $buy_order_id;

            $this->mongo_db->where($search);

            $res_sold = $this->mongo_db->get('orders');

            $sold_order_arr = iterator_to_array($res_sold);

            foreach ($sold_order_arr as $key => $value) {

                $buy_order_price = $value['purchased_price'];

                $sell_order_price = $value['sell_price'];

                $sell_date = $value['created_date'];

                if ($value['sell_date']) {

                    $sell_date = $value['sell_date'];

                }

                $datetime = $sell_date->toDateTime();

                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);

                $datetime->format('Y-m-d H:00:00');

                $formated_date_time = $datetime->format('Y-m-d H:00:00');

                $sell_date = $formated_date_time;

                $final_arr[] = array(

                    'buy_date' => $buy_order_date,

                    'sell_date' => $sell_date,

                    'buy_price' => $buy_order_price,

                    'sell_price' => $market_sold_price,

                );

            }

        }

        return $final_arr;

    } //end get_order_array

    public function save_task_manager_setting($data, $global_symbol) {

        $this->mongo_db->where(array('coin' => $global_symbol));

        $result_object = $this->mongo_db->get('task_manager_setting');

        $result_arr = iterator_to_array($result_object);

        if (count($result_arr) > 0) {

            $this->mongo_db->set($data);

            //Update data in mongoTable

            $this->mongo_db->where(array('coin' => $global_symbol));

            $this->mongo_db->update('task_manager_setting');

        } else {

            $data['coin'] = $global_symbol;

            $this->mongo_db->insert('task_manager_setting', $data);

        }

        return true;

    } //End of save_task_manager_setting

    public function get_task_manager_setting($global_symbol) {

        $this->mongo_db->where(array('coin' => $global_symbol));

        $res = $this->mongo_db->get('task_manager_setting');

        return iterator_to_array($res);

    } //End of get_task_manager_setting

    public function get_chart_target_zones($global_symbol) {

        $admin_id = $this->session->userdata('admin_id');

        $this->mongo_db->where(array('coin' => $global_symbol));

        $this->mongo_db->limit(5);

        $this->mongo_db->sort(array('_id' => 'desc'));

        $responseArr = $this->mongo_db->get('chart_target_zones');

        $fullarray = array();

        foreach ($responseArr as $valueArr) {

            $start_date = $valueArr['start_date'];

            $end_date = $valueArr['end_date'];

            if (!empty($valueArr)) {
                $returArr['start_value'] = (string) ($valueArr['start_value']);
                $returArr['end_value'] = (string) ($valueArr['end_value']);
                $returArr['unit_value'] = (string) ($valueArr['unit_value']);
                $returArr['start_date'] = (string) json_decode($start_date);
                $returArr['end_date'] = (string) json_decode($end_date);
                $returArr['type'] = $valueArr['type'];
            }
            $fullarray[] = $returArr;
        }

        return $fullarray;

    } //end get_chart_target_zones

    public function calculate_pressure_up_and_down($start_date, $end_date, $coin, $pressure_type) {

        $this->mongo_db->where_gte('created_date', $this->mongo_db->converToMongodttime($start_date));

        $this->mongo_db->where_lt('created_date', $this->mongo_db->converToMongodttime($end_date));

        $this->mongo_db->where(array('coin' => $coin, 'pressure' => $pressure_type));

        $res = $this->mongo_db->get('order_book_pressure');

        $res_arr = iterator_to_array($res);

        return $total_pressure = count($res_arr);

    } //End of calculate_pressure_up_and_down

    public function get_coin_unit_value($symbol) {

        $this->db->dbprefix('coins');

        $this->db->select('unit_value');

        $this->db->where('symbol', $symbol);

        $get = $this->db->get('coins');

        $get_arr = $get->row_array();

        return $get_arr['unit_value'];

    } //end get_coin_unit_value()

    public function calculate_up_down_wall($coin_symbol, $datetime) {

        $start_date = date("Y-m-d H:i:00", strtotime($datetime));

        $end_date = date("Y-m-d H:i:59", strtotime("+15 minutes", strtotime($datetime)));

        $where['coin'] = $coin_symbol;

        $where['created_date'] = array('$gte' => $this->mongo_db->converToMongodttime($start_date), '$lte' => $this->mongo_db->converToMongodttime($end_date));

        $order_by['created_date'] = -1;

        $this->mongo_db->where($where);

        $this->mongo_db->order_by($order_by);

        $get = $this->mongo_db->get('depth_wall_history');

        $array = iterator_to_array($get);

        $x = 1;

        for ($i = 0; $i < count($array); $i++) {

            if (!empty($array[$i]['current_market_value'])) {

                $current_val = $array[$i]['current_market_value'];

                $ask_wall = $array[$i]['ask_black_wall'];

                $bid_wall = $array[$i]['bid_black_wall'];

                $unit_val = $this->get_coin_unit_value($coin_symbol);

                $bid_diff[] = ($current_val - $bid_wall) / $unit_val;

                $ask_diff[] = ($ask_wall - $current_val) / $unit_val;

                $x++;
            }
        }

        $bid_diff_sum = array_sum($bid_diff);

        $bid = $bid_diff_sum / $x;

        $ask_diff_sum = array_sum($ask_diff);

        $ask = $ask_diff_sum / $x;

        return array("ask_diff" => $ask, 'bid_diff' => $bid);

    }

    public function getSentimentReportDay($type, $source, $coin, $startDateSocail, $end, $formula, $time) {

        if ($time == '1d') {

            if ($source == 'twitter') {$Table = 'tweets_sent_report';}
            // Custome QueryGoes Here
            ini_set("memory_limit", -1);

            $startNew = $this->mongo_db->converToMongodttime($startDateSocail);
            $endNew = $this->mongo_db->converToMongodttime($end);

            $this->mongo_db->where_gte('created_date', $startNew);
            $this->mongo_db->where_lt('created_date', $endNew);

            //$this->mongo_db->limit(100);
            $this->mongo_db->where('keyword', ($coin));
            //$this->mongo_db->limit(1);
            $res = $this->mongo_db->get($Table);

            $result = iterator_to_array($res);

            $countTotalRecord = count($result);
            if ($countTotalRecord != '') {}

            $negative_sentiment = '';
            $positive_sentiment = '';
            $countnegative = '';
            $countpositive = '';
            $potivrat = '';
            $negative = '';
            $negative_count = '';
            $potivrat_count = '';
            foreach ($result as $key => $value) {
                if ($value['negative_sentiment'] != '') {
                    $negative_sentiment += $value['negative_sentiment'];
                    $countnegative++;
                }
                if ($value['positive_sentiment'] != '') {
                    $positive_sentiment += $value['positive_sentiment'];
                    $countpositive++;
                }
            }
            $totalrecord = $countTotalRecord;
            $negative_sentiment = number_format($negative_sentiment, 2, '.', '');
            $positive_sentiment = number_format($positive_sentiment, 2, '.', '');
            $count_negative_sentiment = $countnegative;
            $count_positive_sentiment = $countpositive;

            if ($positive_sentiment != 0) {
                $potivrat = $positive_sentiment / $totalrecord;
                $potivrat_count = $positive_sentiment / $count_negative_sentiment;
                $potivrat_count = $potivrat_count;
                $potivrat = number_format($potivrat, 2, '.', '');
            }
            if ($negative_sentiment != 0) {
                $negative = $negative_sentiment / $totalrecord;
                $negative_count = $negative_sentiment / $count_positive_sentiment;
                $negative = number_format($negative, 2, '.', '');

            }
            //$temp = array();
            $temp['totalrecord'] = number_format($totalrecord, 2, '.', '');
            $temp['sum_nageative'] = number_format($negative_sentiment, 2, '.', '');
            $temp['sum_positive'] = number_format($positive_sentiment, 2, '.', '');
            $temp['t_neg_divide_t'] = number_format($negative, 2, '.', '');
            $temp['t_pos_divide_t'] = number_format($potivrat, 2, '.', '');
            $temp['t_neg_divide_t_neg'] = number_format($negative_count, 2, '.', '');
            $temp['t_pos_divide_t_pos'] = number_format($potivrat_count, 2, '.', '');
            $final_array = $temp;

        } //   for ($k = 1; $k < $totalDays; $k++) {

        return $final_array;
    } //end getSentimentReport

    public function getSentimentReportSocialDay($type, $source, $coin, $startDateSocail, $end, $formula, $time) {

        $coin = $coin;
        if ($time == '1d') {

            if ($source == 'reddit') {$Table = 'reddi_comments';}
            // Custome QueryGoes Here
            ini_set("memory_limit", -1);
            $startNew = $this->mongo_db->converToMongodttime($startDateSocail);
            $endNew = $this->mongo_db->converToMongodttime($end);

            $this->mongo_db->where_gte('created_date', $startNew);
            $this->mongo_db->where_lt('created_date', $endNew);

            //$this->mongo_db->limit(100);
            $this->mongo_db->where('keyword', ($coin));
            //$this->mongo_db->limit(1);
            $res = $this->mongo_db->get($Table);
            $result = iterator_to_array($res);

            $countTotalRecord = count($result);
            if ($countTotalRecord != '') {}

            $negative_sentiment = 0;
            $positive_sentiment = 0;
            $countnegative = '';
            $countpositive = '';
            $potivrat = 0;
            $negative = 0;
            $negative_count = 0;
            $potivrat_count = 0;
            foreach ($result as $key => $value) {
                if ($value['negative_sentiment'] != '') {
                    $negative_sentiment += $value['negative_sentiment'];
                    $countnegative++;
                }
                if ($value['positive_sentiment'] != '') {
                    $positive_sentiment += $value['positive_sentiment'];
                    $countpositive++;
                }
                if ($value['neutral_sentiment'] != '') {
                    $neutral_sentiment += $value['neutral_sentiment'];
                    $countneutral++;
                }
            }
            $totalrecord = $countTotalRecord;
            $negative_sentiment = $negative_sentiment;
            $positive_sentiment = $positive_sentiment;
            $neutral_sentiment = $neutral_sentiment;

            $count_negative_sentiment = $countnegative;
            $count_positive_sentiment = $countpositive;
            $count_neutral_sentiment = $countneutral;

            if ($positive_sentiment) {
                $potivrat = $positive_sentiment / $totalrecord;
                $potivrat_count = $positive_sentiment / $count_negative_sentiment;
            }
            if ($negative_sentiment) {
                $negative = $negative_sentiment / $totalrecord;
                $negative_count = $negative_sentiment / $count_positive_sentiment;
            }
            if ($neutral_sentiment) {
                $neutrat = $neutral_sentiment / $totalrecord;
                $neutral_count = $neutral_sentiment / $count_neutral_sentiment;
            }
            //$temp = array();
            $temp['totalrecord'] = ($totalrecord);
            $temp['sum_nageative'] = ($negative_sentiment);
            $temp['sum_positive'] = ($positive_sentiment);
            $temp['sum_neutral'] = ($neutral_sentiment);
            $temp['t_neg_divide_t'] = ($negative);
            $temp['t_pos_divide_t'] = ($potivrat);
            $temp['t_neu_divide_t'] = ($neutrat);
            $temp['t_neg_divide_t_neg'] = ($negative_count);
            $temp['t_pos_divide_t_pos'] = ($potivrat_count);
            $temp['t_neu_divide_t_neu'] = ($neutral_count);
            $final_array = $temp;

        } //   for ($k = 1; $k < $totalDays; $k++) {

        return $final_array;
    } //end getSentimentReportSocial

    // ************************************ saeed Ullaha Work Goes here  ****************************************//

    public function save_candle_stick_by_cron_job() {

        //Get All Coins
        $all_coins_arr = $this->mod_sockets->get_all_coins();

        $period_array = array('1d');
        for ($i = 0; $i < count($all_coins_arr); $i++) {
            $coin_symbol = $all_coins_arr[$i]['symbol'];

            $periods = '1d';
            $chart = $this->binance_api->get_candelstick($coin_symbol, $periods);

            if (count($chart) > 0) {

                foreach ($chart as $key => $value) {

                    $created_datetime = date('Y-m-d H:i:s', strtotime('-1 day'));

                    $orig_date = new DateTime($created_datetime);
                    $orig_date = $orig_date->getTimestamp();
                    $created_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
                    $seconds = $value['openTime'] / 1000;
                    $datetime = date("Y-m-d H:i:s", $seconds);
                    $openTime_human_readible = $datetime;
                    $seconds_close = $value['closeTime'] / 1000;
                    $datetime_close = date("Y-m-d H:i:s", $seconds_close);
                    $closeTime_human_readible = $datetime_close;
                    $orig_date22 = new DateTime($datetime);
                    $orig_date22 = $orig_date22->getTimestamp();
                    $timestampDate = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);

                    //Check if candel is previos then Execuite it
                    $date_for_candel = date('Y-m-d H:00:00', strtotime('-1 day'));
                    $end_date_for_candel = date('Y-m-d H:59:59', strtotime('-1 day'));

                    $ask_volume = $this->get_and_calculate_volume_for_candel($date_for_candel, $end_date_for_candel, 'ask', $coin_symbol);

                    $bid_volume = $this->get_and_calculate_volume_for_candel($date_for_candel, $end_date_for_candel, 'bid', $coin_symbol);
                    $totla_volume = $ask_volume + $bid_volume;

                    $response_detail = $this->calculate_swing_poinst($coin_symbol, $value['high'], $value['low'], $date_for_candel);

                    //if ($openTime_human_readible == $date_for_candel) {
                    $update = $this->calculate_candel_status_demand_supply($date_for_candel, $end_date_for_candel, $coin_symbol);
                    //}

                    extract($response_detail);

                    $base_candel_arr = $this->calculate_base_candel($coin_symbol, $demand_percentage, $supply_percentage);
                    $DemandTrigger = $base_candel_arr['demond_max_volume'];
                    $SupplyTrigger = $base_candel_arr['supply_max_volume'];
                    $demand_base_candel = $DemandTrigger;
                    $supply_base_candel = $SupplyTrigger;

                    // $DemandTrigger = ($base_candel/100)*$demand_percentage;
                    // $SupplyTrigger = ($base_candel/100)*$supply_percentage;

                    $DemandCandle = ($ask_volume > $bid_volume && $ask_volume >= $DemandTrigger) ? 1 : 0;
                    $SupplyCandle = ($bid_volume > $ask_volume && $bid_volume >= $SupplyTrigger) ? 1 : 0;
                    $candle_type = 'normal';
                    if ($DemandCandle == 1) {
                        $candle_type = 'demand';
                    }
                    if ($SupplyCandle == 1) {
                        $candle_type = 'supply';
                    }
                    $rejection_status = $this->calculate_rejection_candle($coin_symbol, $date_for_candel);

                    $depth_wall_array = $this->get_hourly_depthwall($date_for_candel, $end_date_for_candel, $coin_symbol);
                    $black_ask_diff = $depth_wall_array['black_ask_diff'];
                    $black_bid_diff = $depth_wall_array['black_bid_diff'];
                    $yellow_ask_diff = $depth_wall_array['yellow_ask_diff'];
                    $yellow_bid_diff = $depth_wall_array['yellow_bid_diff'];

                    $insert22 = array(
                        'open' => (float) $value['open'],
                        'high' => (float) $value['high'],
                        'low' => (float) $value['low'],
                        'close' => (float) $value['close'],
                        'volume' => (float) $value['volume'],
                        'openTime' => $value['openTime'],
                        'closeTime' => $value['closeTime'],
                        'coin' => $coin_symbol,
                        'created_date' => $created_date,
                        'timestampDate' => $timestampDate,
                        'periods' => $periods,
                        'openTime_human_readible' => $openTime_human_readible,
                        'closeTime_human_readible' => $closeTime_human_readible,
                        'human_readible_dateTime' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                        'candel_status' => $candel_status,
                        'candle_type' => $candle_type,
                        'ask_volume' => $ask_volume,
                        'bid_volume' => $bid_volume,
                        'total_volume' => $totla_volume,
                        'demand_base_candel' => $demand_base_candel,
                        'supply_base_candel' => $supply_base_candel,
                        'trigger_status' => 0,
                        'triggert_type' => '',
                        'highest_swing_point' => $highest_swing_point,
                        'lowest_swing_point' => $lowest_swing_point,
                        'candel_highest_swing_status' => $candel_highest_swing_status,
                        'candel_lowest_swing_status' => $candel_lowest_swing_status,
                        'global_swing_status' => $global_swing_status,
                        'global_swing_parent_status' => '',
                        'black_ask_diff' => $black_ask_diff,
                        'black_bid_diff' => $black_bid_diff,
                        'yellow_ask_diff' => $yellow_ask_diff,
                        'yellow_bid_diff' => $yellow_bid_diff,
                        'rejected_candle' => $rejection_status,
                    );

                    //if ($openTime_human_readible == $date_for_candel) {
                    $check_candle = $this->mod_sockets->check_candle_stick_data_if_exist($coin_symbol, $periods, $value['openTime']);
                    if ($check_candle) {
                        $candle_id = $this->mongo_db->insert('market_chart_dailybase', $insert22);
                        echo 'insert' . '<br>';
                        $rejection_status = $this->calculate_rejection_candle($coin_symbol, $date_for_candel);
                        $upd['rejected_candle'] = $rejection_status;

                        $this->mongo_db->where(array('_id' => $candle_id));
                        $this->mongo_db->set($upd);
                        $this->mongo_db->update('market_chart_dailybase');
                    } else {
                        $if_current_cand = $this->check_if_current_candle($periods, $value['openTime']);
                        if ($if_current_cand) {

                            $this->mod_sockets->candle_update($coin_symbol, $periods, $value['openTime'], $insert22);
                            echo 'update' . '<br>';
                        } else {
                            $update_count_for_canlde_missing = $this->mod_sockets->update_count_for_ignore_candle();
                            echo 'nothing' . '<br>';

                        } /*** End of update***/
                    } /*** End of insert***/
                    //} // End of if candel is pervious
                } //End of for each
            }/** End of chart count*/
            //}/** End of period candel_stick_arr**/
        } //end for
    } //End of save_candle_stick_by_cron_job

    public function get_hourly_depthwall($start_date, $end_date, $coin_symbol) {

        //$coin_symbol = $value['symbol'];

        $where['coin'] = $coin_symbol;
        $where['created_date'] = array('$gte' => $this->mongo_db->converToMongodttime($start_date), '$lte' => $this->mongo_db->converToMongodttime($end_date));

        $order_by['created_date'] = -1;
        $this->mongo_db->where($where);
        $this->mongo_db->order_by($order_by);
        $get = $this->mongo_db->get('depth_wall_history');
        $array = iterator_to_array($get);

        $x = 1;
        $bid_diff = array();
        $ask_diff = array();
        $y_bid_diff = array();
        $y_ask_diff = array();
        for ($i = 0; $i < count($array); $i++) {

            $ask_wall = $array[$i]['black_ask_diff'];
            $bid_wall = $array[$i]['black_bid_diff'];

            $y_ask_wall = $array[$i]['yellow_ask_diff'];
            $y_bid_wall = $array[$i]['yellow_bid_diff'];

            $bid_diff[] = $bid_wall;
            $ask_diff[] = $ask_wall;

            $y_bid_diff[] = $y_bid_wall;
            $y_ask_diff[] = $y_ask_wall;

            $x++;

        }

        $bid_diff_sum = array_sum($bid_diff);

        $bid = round($bid_diff_sum / $x);

        $ask_diff_sum = array_sum($ask_diff);
        $ask = round($ask_diff_sum / $x);

        $y_bid_diff_sum = array_sum($y_bid_diff);
        $y_bid = round($y_bid_diff_sum / $x);

        $y_ask_diff_sum = array_sum($y_ask_diff);
        $y_ask = round($y_ask_diff_sum / $x);

        /*$ins_arr = array('coin' => $coin_symbol, "ask_diff" => $ask, 'bid_diff' => $bid, 'datetime' => $this->mongo_db->converToMongodttime($start_date), 'date_time_human_readable' => $start_date);

        $this->mongo_db->insert('market_depth_hourly_wall', $ins_arr);

        $ins_arr22 = array('coin' => $coin_symbol, "ask_diff" => $y_ask, 'bid_diff' => $y_bid, 'datetime' => $this->mongo_db->converToMongodttime($start_date), 'date_time_human_readable' => $start_date);
        $this->mongo_db->insert('market_depth_yellow_hourly_wall', $ins_arr22);
         */

        $ret_arr = array(
            'black_ask_diff' => $ask,
            'black_bid_diff' => $bid,
            'yellow_ask_diff' => $y_ask,
            'yellow_bid_diff' => $y_bid,
        );

        return $ret_arr;
    }

    public function check_candle_stick_data_if_exist($coin_symbol, $period, $openTime) {

        $this->mongo_db->where(array('coin' => $coin_symbol, 'periods' => $period, 'openTime' => $openTime));

        $responseArr = $this->mongo_db->get('market_chart_dailybase');

        $exist = 0;

        foreach ($responseArr as $key) {

            $exist = 1;

            break;

        }

        if ($exist == 1) {

            return false;

        } else {

            return true;

        }

    }/** End of check_candle_stick_data_if_exist***/

    public function get_and_calculate_volume_for_candel($from_date, $end_date, $volume_type, $coin_type) {
        $connect = $this->mongo_db->customQuery();
        $res = $connect->market_trade_hourly_history->find(array(
            'type' => $volume_type,
            'coin' => $coin_type,
            'hour' => array('$gte' => $from_date, '$lte' => $end_date),
        ));
        $volume = 0;
        $res = iterator_to_array($res);
        foreach ($res as $key) {
            $volume += (float) $key['volume'];
        }
        return $volume;
    } //End of  get_and_calculate_volume_for_candel

    public function check_if_current_candle($periods, $openTime) {
        list($alpha, $numeric) = sscanf($periods, "%[A-Z]%d");
        switch ($alpha) {
        case "h":
            $response_period = ($numeric * 3600) * 2;
            break;
        case "m":
            $response_period = ($numeric * 60) * 2;
            break;
        default:
            $response_period = 1 * 2;
        }
        $seconds = $openTime / 1000;
        $datetime = date("Y-m-d H:i:s", $seconds);
        $seconds_2 = $seconds - $response_period;
        $datetime_2 = date("Y-m-d H:i:s", $seconds_2);
        if ($datetime_2 < $datetime) {
            return true;
        } else {
            return false;
        }
    } /*** End of check_if_current_candle**/

    ////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    ////////////////////                       /////////////////
    ////////////////////                       ////////////////////////////////
    //////////////////// Swing Point Live      ////////////////////////////////
    ////////////////////                       ////////////////////////////////
    ////////////////////                       /////////////////
    ////////////////////                       /////////////////
    ////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    public function calculate_swing_poinst($coin_symbol, $current_highest_point, $current_lowest_point, $date_for_candel) {

        ///////////////////////////////////////////////////////////////////////
        ///////////////Get Task Manager Setting Details //////////////////////
        $task_manager_setting_obj = $this->mongo_db->get('task_manager_setting');
        $task_manager_setting_arr = iterator_to_array($task_manager_setting_obj);

        $number_of_look_back = 5;
        $number_of_look_forward = 3;
        $maxLvlLen = 0;
        $ShowHHLL = 0;
        $WaitForClose = 1;
        $LH_Percentile = 10;
        $LH_Percentile_supply = 10;
        $Continuation_up_Percentile = 30;
        $Continuation_up_Percentile_supply = 30;
        $Current_up_Percentile = 25;
        $Current_up_Percentile_supply = 25;
        $HL_Percentile = 10;
        $HL_Percentile_supply = 10;
        $Continuation_Down_Percentile = 20;
        $Continuation_Down_Percentile_supply = 20;
        $Current_Down_Percentile = 25;
        $Current_Down_Percentile_supply = 25;

        if (count($task_manager_setting_arr) > 0) {
            $number_of_look_back = $task_manager_setting_arr[0]['pvtLenL'];
            $number_of_look_forward = $task_manager_setting_arr[0]['pvtLenR'];

            $maxLvlLen = $task_manager_setting_arr[0]['maxLvlLen'];
            $ShowHHLL = $task_manager_setting_arr[0]['ShowHHLL'];
            $WaitForClose = $task_manager_setting_arr[0]['WaitForClose'];

            $LH_Percentile = $task_manager_setting_arr[0]['LH_Percentile'];
            $LH_Percentile_supply = $task_manager_setting_arr[0]['LH_Percentile_supply'];
            $Continuation_up_Percentile = $task_manager_setting_arr[0]['Continuation_up_Percentile'];
            $Continuation_up_Percentile_supply = $task_manager_setting_arr[0]['Continuation_up_Percentile_supply'];
            $Current_up_Percentile = $task_manager_setting_arr[0]['Current_up_Percentile'];
            $Current_up_Percentile_supply = $task_manager_setting_arr[0]['Current_up_Percentile_supply'];
            $HL_Percentile = $task_manager_setting_arr[0]['HL_Percentile'];
            $HL_Percentile_supply = $task_manager_setting_arr[0]['HL_Percentile_supply'];
            $Continuation_Down_Percentile = $task_manager_setting_arr[0]['Continuation_Down_Percentile'];
            $Continuation_Down_Percentile_supply = $task_manager_setting_arr[0]['Continuation_Down_Percentile_supply'];
            $Current_Down_Percentile = $task_manager_setting_arr[0]['Current_Down_Percentile'];
            $Current_Down_Percentile_supply = $task_manager_setting_arr[0]['Current_Down_Percentile_supply'];
        }
        ///////////////End Task Manager Setting Details //////////////////////
        /////////////////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////////////
        //////////////////Get Look Back Detail //////////////////////////
        $number_of_look_forward = 0;

        $current_swing_heighst_point = $this->find_swing_heighst_points($number_of_look_back, $number_of_look_forward, $date_for_candel, $coin_symbol);

        $current_swing_lowest_point = $this->find_swing_lowest_points($number_of_look_back, $number_of_look_forward, $date_for_candel, $coin_symbol);

        $prevouse_date = date('Y-m-d H:00:00', strtotime('-1 day', strtotime($date_for_candel)));

        $this->mongo_db->limit(2);
        $this->mongo_db->order_by(array('timestampDate' => -1));
        $this->mongo_db->where_lte('timestampDate', $this->mongo_db->converToMongodttime($prevouse_date));

        $this->mongo_db->where(array('coin' => $coin_symbol));
        $response_detail = $this->mongo_db->get('market_chart_dailybase');
        $market_chart_arr = iterator_to_array($response_detail);

        $previous_highest_point = 0;
        $previous_lowest_point = 0;

        $previous_swing_high_status = '';
        $second_last_high_swing_status = '';

        $second_last_lowest_swing_status = '';
        $previous_swing_lowest_status = '';

        if (count($market_chart_arr) > 0) {

            $previous_highest_point = $market_chart_arr[0]['highest_swing_point'];
            $previous_lowest_point = $market_chart_arr[0]['lowest_swing_point'];
            $candel_status = $market_chart_arr[0]['candel_status'];
            $global_swing_status = $market_chart_arr[0]['global_swing_status'];

            $second_last_high_swing_status = $market_chart_arr[1]['candel_highest_swing_status'];
            $previous_swing_high_status = $market_chart_arr[0]['candel_highest_swing_status'];

            $second_last_lowest_swing_status = $market_chart_arr[1]['candel_lowest_swing_status'];
            $previous_swing_lowest_status = $market_chart_arr[0]['candel_lowest_swing_status'];

        } //End of if

        $demand_percentage = 90;

        $supply_percentage = 90;

        $candel_highest_swing_status = '';
        $global_swing_parent_status = '';

        if ($current_swing_heighst_point != '') {

            if ($current_swing_heighst_point <= $previous_highest_point) {
                $candel_status = 'normal';
                $candel_highest_swing_status = 'LH';
                $demand_percentage = $LH_Percentile;
                $supply_percentage = $LH_Percentile_supply;
                $global_swing_status = 'LH';

                $global_swing_parent_status = 'LH';

            } else {

                $candel_highest_swing_status = 'HH';
                $global_swing_status = 'HH';
                $global_swing_parent_status = 'HH';
                if ($previous_swing_high_status == 'HH' && $second_last_high_swing_status == 'HH') {

                    $demand_percentage = $Continuation_up_Percentile;
                    $supply_percentage = $Continuation_up_Percentile_supply;
                    $candel_status = 'Continuation_up';

                } else {
                    $candel_status = 'Current_up';
                    $demand_percentage = $Current_up_Percentile;
                    $supply_percentage = $Current_up_Percentile_supply;
                }
            }

        } else {

            if ($candel_status == 'normal') {
                $demand_percentage = $LH_Percentile;
                $supply_percentage = $LH_Percentile_supply;
            } else if ($candel_status == 'Continuation_up') {
                $demand_percentage = $Continuation_up_Percentile;
                $supply_percentage = $Continuation_up_Percentile_supply;
            } else if ($candel_status == 'Current_up') {
                $demand_percentage = $Current_up_Percentile;
                $supply_percentage = $Current_up_Percentile_supply;
            }

            $current_highest_point = $previous_highest_point;
            $candel_highest_swing_status = $candel_highest_swing_status;

        }

        if ($current_swing_lowest_point != '') {

            if ($current_swing_lowest_point >= $previous_lowest_point) {

                $candel_lowest_swing_status = 'HL';
                $global_swing_parent_status = 'HL';
                $global_swing_status = 'HL';
                $demand_percentage = $HL_Percentile;
                $supply_percentage = $HL_Percentile_supply;

            } else {

                $candel_lowest_swing_status = 'LL';
                $global_swing_parent_status = 'LL';
                $global_swing_status = 'LL';
                if (($previous_swing_lowest_status == 'LL') && ($second_last_lowest_swing_status == 'LL')) {

                    $demand_percentage = $Continuation_Down_Percentile;
                    $supply_percentage = $Continuation_Down_Percentile_supply;
                    $candel_status = 'Continuation_Down';
                } else {

                    $demand_percentage = $Current_Down_Percentile;
                    $supply_percentage = $Current_Down_Percentile_supply;
                    $candel_status = 'Current_Down';
                }
            }

        } else {

            if ($candel_status == 'normal') {
                $demand_percentage = $HL_Percentile;
                $supply_percentage = $HL_Percentile_supply;
            } else if ($candel_status == 'Continuation_Down') {
                $demand_percentage = $Continuation_Down_Percentile;
                $supply_percentage = $Continuation_Down_Percentile_supply;
            } else if ($candel_status == 'Current_Down') {
                $demand_percentage = $Current_Down_Percentile;
                $supply_percentage = $Current_Down_Percentile_supply;
            }

            $current_lowest_point = $previous_lowest_point;
            $candel_lowest_swing_status = $previous_swing_lowest_status;

        }

        $return_arr = array();
        $return_arr['candel_status'] = $candel_status;
        $return_arr['highest_swing_point'] = $current_highest_point;
        $return_arr['lowest_swing_point'] = $current_lowest_point;
        $return_arr['candel_highest_swing_status'] = $candel_highest_swing_status;
        $return_arr['candel_lowest_swing_status'] = $candel_lowest_swing_status;
        $return_arr['demand_percentage'] = $demand_percentage;
        $return_arr['supply_percentage'] = $supply_percentage;
        $return_arr['global_swing_status'] = $global_swing_status;
        $return_arr['global_swing_parent_status'] = $global_swing_parent_status;

        return $return_arr;
    } //End of calculate_swing_poinst

    public function calculate_base_candel($coin_symbol, $demand_percentage, $supply_percentage) {
        $total_volume = 0;
        $volume_arr = array();
        for ($index_date = 1; $index_date <= 168; $index_date++) {
            $from_date_for_candel = date("Y-m-d H:00:00", strtotime('-' . $index_date . ' day'));
            $end_date_for_candel = date("Y-m-d H:59:59", strtotime('-' . $index_date . ' day'));
            $ask_volume = $this->get_and_calculate_volume_for_candel($from_date_for_candel, $end_date_for_candel, 'ask', $coin_symbol);
            $bid_volume = $this->get_and_calculate_volume_for_candel($from_date_for_candel, $end_date_for_candel, 'bid', $coin_symbol);
            $bid_volume_arr[] = $bid_volume;

            //$total_volume = $ask_volume+$bid_volume;
            $volume_arr[] = $ask_volume;
        }

        sort($volume_arr);
        sort($bid_volume_arr);
        $greater_ask_volume = 0;
        $demand_percentage_index = round((count($volume_arr) / 100) * $demand_percentage);
        $demond_greater_ask_volume = $volume_arr[$demand_percentage_index];

        $supply_percentage_index = round((count($bid_volume_arr) / 100) * $supply_percentage);
        $supply_greater_ask_volume = $bid_volume_arr[$supply_percentage_index];

        $response_arr['demond_max_volume'] = $demond_greater_ask_volume;
        $response_arr['supply_max_volume'] = $supply_greater_ask_volume;
        return $response_arr;
    } //End of calculate_base_candel

    public function find_swing_heighst_points($number_of_look_back, $number_of_look_forward, $date_for_candel, $coin_symbol) {

        $limit_no = $number_of_look_back + $number_of_look_forward + 1;
        $prevouse_date = date('Y-m-d H:00:00', strtotime('-' . $number_of_look_back . ' day', strtotime($date_for_candel)));

        $this->mongo_db->limit($limit_no);
        $this->mongo_db->order_by(array('timestampDate' => 1));
        $this->mongo_db->where_gte('timestampDate', $this->mongo_db->converToMongodttime($prevouse_date));
        $this->mongo_db->where(array('coin' => $coin_symbol));
        $this->mongo_db->limit(10);
        $market_chart_object = $this->mongo_db->get('market_chart_dailybase');
        $market_chart_arr = iterator_to_array($market_chart_object);

        $current_heigh_value = '';
        $look_forward_index = -1;
        $heighs_pont_arr = array();
        $look_forward_heigh_array = array();

        if (count($market_chart_arr) > 0) {
            $index = 0;
            foreach ($market_chart_arr as $chart_data) {
                $current = 'no';
                if ($chart_data['openTime_human_readible'] == $date_for_candel) {
                    $current_heigh_value = $chart_data['high'];
                    unset($market_chart_arr[$index]);
                    $look_forward_index = $index;
                    $current = 'yes';
                } else {
                    array_push($heighs_pont_arr, $chart_data['high']);
                }

                if (($look_forward_index != -1) && ($current == 'no')) {
                    array_push($look_forward_heigh_array, $chart_data['high']);
                }

                $index++;
            }
        }

        $heighst_swing_point = max($heighs_pont_arr);
        $look_forward_heigh = max($look_forward_heigh_array);

        $response_value = '';
        if ($current_heigh_value >= $heighst_swing_point) {
            $response_value = $current_heigh_value;
        }

        return $response_value;
    } //End of find_swing_heighst_points

    public function find_swing_lowest_points($number_of_look_back, $number_of_look_forward, $date_for_candel, $coin_symbol) {

        $limit_no = $number_of_look_back + $number_of_look_forward + 1;
        $prevouse_date = date('Y-m-d H:00:00', strtotime('-' . $number_of_look_back . ' hour', strtotime($date_for_candel)));

        $this->mongo_db->limit($limit_no);
        $this->mongo_db->order_by(array('timestampDate' => 1));
        $this->mongo_db->where_gte('timestampDate', $this->mongo_db->converToMongodttime($prevouse_date));
        $this->mongo_db->where(array('coin' => $coin_symbol));
        $market_chart_object = $this->mongo_db->get('market_chart_dailybase');
        $market_chart_arr = iterator_to_array($market_chart_object);

        $current_low_value = '';
        $look_forward_index = -1;
        $heighs_pont_arr = array();
        $look_forward_heigh_array = array();

        if (count($market_chart_arr) > 0) {
            $index = 0;
            foreach ($market_chart_arr as $chart_data) {
                $current = 'no';
                if ($chart_data['openTime_human_readible'] == $date_for_candel) {
                    $current_low_value = $chart_data['low'];
                    unset($market_chart_arr[$index]);
                    $look_forward_index = $index;
                    $current = 'yes';
                } else {
                    array_push($heighs_pont_arr, $chart_data['low']);
                }

                if (($look_forward_index != -1) && ($current == 'no')) {
                    array_push($look_forward_heigh_array, $chart_data['low']);
                }

                $index++;
            }
        }

        $lowest_swing_point = min($heighs_pont_arr);
        $look_forward_heigh = min($look_forward_heigh_array);
        $response_value = '';

        if ($current_low_value <= $lowest_swing_point) {

            $response_value = $current_low_value;

        }
        return $response_value;
    } //End of Find_swing_lowest_points

    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////                       /////////////////
    ////////////////////                       ////////////////////////////////
    //////////////////// Swing Point simulator ////////////////////////////////
    ////////////////////                       ////////////////////////////////
    ////////////////////                       /////////////////
    ////////////////////                       /////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    public function calculate_swing_poinst_simulator($coin_symbol, $current_highest_point, $current_lowest_point, $date_for_candel) {

        ///////////////////////////////////////////////////////////////////////
        ///////////////Get Task Manager Setting Details //////////////////////
        $task_manager_setting_obj = $this->mongo_db->get('task_manager_setting');
        $task_manager_setting_arr = iterator_to_array($task_manager_setting_obj);

        $number_of_look_back = 5;
        $number_of_look_forward = 3;
        $maxLvlLen = 0;
        $ShowHHLL = 0;
        $WaitForClose = 1;
        $LH_Percentile = 10;
        $LH_Percentile_supply = 10;
        $Continuation_up_Percentile = 30;
        $Continuation_up_Percentile_supply = 30;
        $Current_up_Percentile = 25;
        $Current_up_Percentile_supply = 25;
        $HL_Percentile = 10;
        $HL_Percentile_supply = 10;
        $Continuation_Down_Percentile = 20;
        $Continuation_Down_Percentile_supply = 20;
        $Current_Down_Percentile = 25;
        $Current_Down_Percentile_supply = 25;

        if (count($task_manager_setting_arr) > 0) {
            $number_of_look_back = $task_manager_setting_arr[0]['pvtLenL'];
            $number_of_look_forward = $task_manager_setting_arr[0]['pvtLenR'];

            $maxLvlLen = $task_manager_setting_arr[0]['maxLvlLen'];
            $ShowHHLL = $task_manager_setting_arr[0]['ShowHHLL'];
            $WaitForClose = $task_manager_setting_arr[0]['WaitForClose'];

            $LH_Percentile = $task_manager_setting_arr[0]['LH_Percentile'];
            $LH_Percentile_supply = $task_manager_setting_arr[0]['LH_Percentile_supply'];
            $Continuation_up_Percentile = $task_manager_setting_arr[0]['Continuation_up_Percentile'];
            $Continuation_up_Percentile_supply = $task_manager_setting_arr[0]['Continuation_up_Percentile_supply'];
            $Current_up_Percentile = $task_manager_setting_arr[0]['Current_up_Percentile'];
            $Current_up_Percentile_supply = $task_manager_setting_arr[0]['Current_up_Percentile_supply'];
            $HL_Percentile = $task_manager_setting_arr[0]['HL_Percentile'];
            $HL_Percentile_supply = $task_manager_setting_arr[0]['HL_Percentile_supply'];
            $Continuation_Down_Percentile = $task_manager_setting_arr[0]['Continuation_Down_Percentile'];
            $Continuation_Down_Percentile_supply = $task_manager_setting_arr[0]['Continuation_Down_Percentile_supply'];
            $Current_Down_Percentile = $task_manager_setting_arr[0]['Current_Down_Percentile'];
            $Current_Down_Percentile_supply = $task_manager_setting_arr[0]['Current_Down_Percentile_supply'];
        }
        ///////////////End Task Manager Setting Details //////////////////////
        /////////////////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////////////
        //////////////////Get Look Back Detail //////////////////////////

        $current_swing_heighst_point = $this->find_swing_heighst_points_simulator($number_of_look_back, $number_of_look_forward, $date_for_candel, $coin_symbol);

        $current_swing_lowest_point = $this->find_swing_lowest_points_simulator($number_of_look_back, $number_of_look_forward, $date_for_candel, $coin_symbol);

        $prevouse_date = date('Y-m-d H:00:00', strtotime('-1 day', strtotime($date_for_candel)));

        $this->mongo_db->limit(2);
        $this->mongo_db->order_by(array('timestampDate' => -1));
        $this->mongo_db->where_lte('timestampDate', $this->mongo_db->converToMongodttime($prevouse_date));

        $this->mongo_db->where(array('coin' => $coin_symbol));
        $response_detail = $this->mongo_db->get('market_chart_dailybase');
        $market_chart_arr = iterator_to_array($response_detail);

        $previous_highest_point = 0;
        $previous_lowest_point = 0;

        $previous_swing_high_status = '';
        $second_last_high_swing_status = '';

        $second_last_lowest_swing_status = '';
        $previous_swing_lowest_status = '';

        if (count($market_chart_arr) > 0) {

            $previous_highest_point = $market_chart_arr[0]['highest_swing_point'];
            $previous_lowest_point = $market_chart_arr[0]['lowest_swing_point'];
            $candel_status = $market_chart_arr[0]['candel_status'];
            $global_swing_status = $market_chart_arr[0]['global_swing_status'];

            $second_last_high_swing_status = $market_chart_arr[1]['candel_highest_swing_status'];
            $previous_swing_high_status = $market_chart_arr[0]['candel_highest_swing_status'];

            $second_last_lowest_swing_status = $market_chart_arr[1]['candel_lowest_swing_status'];
            $previous_swing_lowest_status = $market_chart_arr[0]['candel_lowest_swing_status'];

        } //End of if

        $demand_percentage = 90;
        $supply_percentage = 90;

        $candel_highest_swing_status = '';

        $global_swing_parent_status = '';
        if ($current_swing_heighst_point != '') {

            if ($current_swing_heighst_point <= $previous_highest_point) {
                $candel_status = 'normal';
                $candel_highest_swing_status = 'LH';
                $demand_percentage = $LH_Percentile;
                $supply_percentage = $LH_Percentile_supply;
                $global_swing_status = 'LH';
                $global_swing_parent_status = 'LH';

            } else {

                $candel_highest_swing_status = 'HH';
                $global_swing_parent_status = 'HH';
                $global_swing_status = 'HH';
                if ($previous_swing_high_status == 'HH' && $second_last_high_swing_status == 'HH') {

                    $demand_percentage = $Continuation_up_Percentile;
                    $supply_percentage = $Continuation_up_Percentile_supply;
                    $candel_status = 'Continuation_up';

                } else {
                    $candel_status = 'Current_up';
                    $demand_percentage = $Current_up_Percentile;
                    $supply_percentage = $Current_up_Percentile_supply;
                }
            }

        } else {

            if ($candel_status == 'normal') {
                $demand_percentage = $LH_Percentile;
                $supply_percentage = $LH_Percentile_supply;
            } else if ($candel_status == 'Continuation_up') {
                $demand_percentage = $Continuation_up_Percentile;
                $supply_percentage = $Continuation_up_Percentile_supply;
            } else if ($candel_status == 'Current_up') {
                $demand_percentage = $Current_up_Percentile;
                $supply_percentage = $Current_up_Percentile_supply;
            }

            $current_highest_point = $previous_highest_point;
            $candel_highest_swing_status = $candel_highest_swing_status;

        }

        if ($current_swing_lowest_point != '') {

            if ($current_swing_lowest_point >= $previous_lowest_point) {

                $candel_lowest_swing_status = 'HL';
                $global_swing_parent_status = 'HL';
                $global_swing_status = 'HL';
                $demand_percentage = $HL_Percentile;
                $supply_percentage = $HL_Percentile_supply;

            } else {

                $candel_lowest_swing_status = 'LL';
                $global_swing_parent_status = 'LL';
                $global_swing_status = 'LL';
                if (($previous_swing_lowest_status == 'LL') && ($second_last_lowest_swing_status == 'LL')) {

                    $demand_percentage = $Continuation_Down_Percentile;
                    $supply_percentage = $Continuation_Down_Percentile_supply;
                    $candel_status = 'Continuation_Down';
                } else {

                    $demand_percentage = $Current_Down_Percentile;
                    $supply_percentage = $Current_Down_Percentile_supply;
                    $candel_status = 'Current_Down';
                }
            }

        } else {

            if ($candel_status == 'normal') {
                $demand_percentage = $HL_Percentile;
                $supply_percentage = $HL_Percentile_supply;
            } else if ($candel_status == 'Continuation_Down') {
                $demand_percentage = $Continuation_Down_Percentile;
                $supply_percentage = $Continuation_Down_Percentile_supply;
            } else if ($candel_status == 'Current_Down') {
                $demand_percentage = $Current_Down_Percentile;
                $supply_percentage = $Current_Down_Percentile_supply;
            }

            $current_lowest_point = $previous_lowest_point;
            $candel_lowest_swing_status = $previous_swing_lowest_status;

        }

        $return_arr = array();
        $return_arr['candel_status'] = $candel_status;
        $return_arr['highest_swing_point'] = $current_highest_point;
        $return_arr['lowest_swing_point'] = $current_lowest_point;
        $return_arr['candel_highest_swing_status'] = $candel_highest_swing_status;
        $return_arr['candel_lowest_swing_status'] = $candel_lowest_swing_status;
        $return_arr['demand_percentage'] = $demand_percentage;
        $return_arr['supply_percentage'] = $supply_percentage;
        $return_arr['global_swing_status'] = $global_swing_status;
        $return_arr['global_swing_parent_status'] = $global_swing_parent_status;
        return $return_arr;
    } //End of calculate_swing_poinst

    public function calculate_candel_status_demand_supply($date_for_candel, $end_date_for_candel, $coin) {

        $rejection_status = $this->calculate_rejection_candle($coin, $date);

        //Check if candel is previos then Execuite it
        $date_for_candel = $date_for_candel;
        //echo "<br />";
        $end_date_for_candel = $end_date_for_candel;

        $this->mongo_db->where(array('openTime_human_readible' => $date_for_candel, 'coin' => $coin));
        $response_detail = $this->mongo_db->get('market_chart_dailybase');
        $candel_detail_arr = iterator_to_array($response_detail);

        $message_candel = '';
        if (count($candel_detail_arr) > 0) {
            foreach ($candel_detail_arr as $candel_data) {
                $candel_id = $candel_data['_id'];
                $high_value = $candel_data['high'];
                $low_value = $candel_data['low'];
                $coin_symbol = $candel_data['coin'];

                $ask_volume = $this->get_and_calculate_volume_for_candel($date_for_candel, $end_date_for_candel, 'ask', $coin_symbol);
                $bid_volume = $this->get_and_calculate_volume_for_candel($date_for_candel, $end_date_for_candel, 'bid', $coin_symbol);
                $totla_volume = $ask_volume + $bid_volume;

                $response_detail = $this->calculate_swing_poinst_simulator($coin_symbol, $high_value, $low_value, $date_for_candel);
                extract($response_detail);

                $base_candel_arr = $this->calculate_base_candel_samulater($coin_symbol, $date_for_candel, $end_date_for_candel, $demand_percentage, $supply_percentage);

                $DemandTrigger = $base_candel_arr['demond_max_volume'];
                $SupplyTrigger = $base_candel_arr['supply_max_volume'];

                $demand_base_candel = $DemandTrigger;
                $supply_base_candel = $SupplyTrigger;

                // $DemandTrigger = ($base_candel/100)*$demand_percentage;
                // $SupplyTrigger = ($base_candel/100)*$supply_percentage;

                $DemandCandle = ($ask_volume > $bid_volume && $ask_volume >= $DemandTrigger) ? 1 : 0;
                $SupplyCandle = ($bid_volume > $ask_volume && $bid_volume >= $SupplyTrigger) ? 1 : 0;
                $candle_type = 'normal';

                if ($DemandCandle == 1) {
                    $candle_type = 'demand';
                }
                if ($SupplyCandle == 1) {
                    $candle_type = 'supply';
                }

                //////////////////////////////////////
                /////////////////////////////////////
                $update_arr = array(
                    'candel_status' => $candel_status,
                    'candle_type' => $candle_type,
                    'ask_volume' => $ask_volume,
                    'bid_volume' => $bid_volume,
                    'total_volume' => $totla_volume,
                    'demand_base_candel' => $demand_base_candel,
                    'supply_base_candel' => $supply_base_candel,
                    //'trigger_status' => 0,
                    'triggert_type' => '',
                    'highest_swing_point' => $highest_swing_point,
                    'lowest_swing_point' => $lowest_swing_point,
                    'candel_highest_swing_status' => $candel_highest_swing_status,
                    'candel_lowest_swing_status' => $candel_lowest_swing_status,
                    'global_swing_status' => $global_swing_status,
                    //'order_mode_lock_for_update' => 0,
                    'global_swing_parent_status' => $global_swing_parent_status,
                    'rejected_candle' => $rejection_status,
                );

                $this->mongo_db->where(array('_id' => $candel_id));
                $this->mongo_db->set($update_arr);
                //Update data in mongoTable
                $this->mongo_db->update('market_chart_dailybase');

                if ($global_swing_parent_status != '') {

                    $barrier_type = 'up';
                    $last_barrrier_value = (float) $high_value;

                    if ($global_swing_parent_status == 'HL' || $global_swing_parent_status == 'LL') {
                        $barrier_type = 'down';
                        $last_barrrier_value = (float) $low_value;
                    }
                    $this->save_last_barrier_value($last_barrrier_value, $coin_symbol, $global_swing_parent_status, $barrier_type, $date_for_candel);

                }

                //Update Global status for trigger_3_setting
                $this->update_trigger_3_setting_for_global_status($date, $coin, $global_swing_parent_status);

                ///////////////////////////////////////
                //////////////////////////////////////
            }
        }
        return $message_candel;
    } //calculate_candel_status_demand_supply

    public function update_trigger_3_setting_for_global_status($date, $coin, $global_swing_parent_status) {
        $box_lock_status = 0;
        $order_lock_status = 0;
        if (($global_swing_parent_status == 'LL') || ($global_swing_parent_status == 'HL')) {
            $box_lock_status = 1;
            $order_lock_status = 1;

            $this->lock_order_status();
        }
        $this->mongo_db->where(array('open_time_object' => $this->mongo_db->converToMongodttime($date), 'coin' => $coin));
        $upd_arr = array('global_swing_parent_status' => $global_swing_parent_status, 'box_lock_status' => $box_lock_status);
        $this->mongo_db->set($upd_arr);
        $this->mongo_db->update('box_trigger_3_setting');

        return true;
    } //End of update_trigger_3_setting_for_global_status

    public function lock_order_status() {

        $this->mongo_db->where_in('order_mode', array('test_live', 'live'));
        $this->mongo_db->where_ne('is_sell_order', 'sold');
        $this->mongo_db->where(array('symbol' => $coin, 'buy_order_status_new_filled' => 'wait_for_buyed', 'status' => 'new', 'trigger_type' => 'box_trigger_3'));
        $responese_obj = $this->mongo_db->get('buy_orders');
        $responese_arr = iterator_to_array($responese_obj);
        if (count($responese_arr) > 0) {
            foreach ($responese_arr as $data) {
                $order_id = $data['_id'];
                $this->mongo_db->where(array('_id' => $order_id));
                $update_arr = array('order_lock_status' => 1);
                $this->mongo_db->set($update_arr);
                $this->mongo_db->update('buy_orders');
            }
        }
    } //End of lock_order_status

    public function calculate_base_candel_samulater($coin_symbol, $date, $end_date, $demand_percentage, $supply_percentage, $totla_volume) {

        $total_volume = 0;
        $volume_arr = array();
        for ($index_date = 1; $index_date <= 168; $index_date++) {
            $from_date_for_candel = date("Y-m-d H:00:00", strtotime($date . ' -' . $index_date . ' day'));
            $end_date_for_candel = date("Y-m-d H:59:59", strtotime($end_date . ' -' . $index_date . ' day'));

            $ask_volume = $this->get_and_calculate_volume_for_candel($from_date_for_candel, $end_date_for_candel, 'ask', $coin_symbol);

            if ($ask_volume != 0) {
                $volume_arr[] = $ask_volume;
            }

            $bid_volume = $this->get_and_calculate_volume_for_candel($from_date_for_candel, $end_date_for_candel, 'bid', $coin_symbol);
            if ($bid_volume != 0) {
                $bid_volume_arr[] = $bid_volume;
            }
        }
        sort($volume_arr);
        sort($bid_volume_arr);

        // $top_percent =

        $greater_ask_volume = 0;
        $demand_percentage_index = round((count($volume_arr) / 100) * $demand_percentage);

        $demond_greater_ask_volume = $volume_arr[$demand_percentage_index];

        $supply_percentage_index = round((count($bid_volume_arr) / 100) * $supply_percentage);
        $supply_greater_ask_volume = $bid_volume_arr[$supply_percentage_index];

        $response_arr['demond_max_volume'] = $demond_greater_ask_volume;
        $response_arr['supply_max_volume'] = $supply_greater_ask_volume;

        return $response_arr;
    } //End of calculate_base_candel

    public function find_swing_heighst_points_simulator($number_of_look_back, $number_of_look_forward, $date_for_candel, $coin_symbol) {

        $limit_no = $number_of_look_back + $number_of_look_forward + 1;
        $prevouse_date = date('Y-m-d H:00:00', strtotime('-' . $number_of_look_back . ' hour', strtotime($date_for_candel)));

        $this->mongo_db->limit($limit_no);
        $this->mongo_db->order_by(array('timestampDate' => 1));
        $this->mongo_db->where_gte('timestampDate', $this->mongo_db->converToMongodttime($prevouse_date));
        $this->mongo_db->where(array('coin' => $coin_symbol));
        $market_chart_object = $this->mongo_db->get('market_chart_dailybase');
        $market_chart_arr = iterator_to_array($market_chart_object);

        $current_heigh_value = '';
        $look_forward_index = -1;
        $heighs_pont_arr = array();
        $look_forward_heigh_array = array();

        if (count($market_chart_arr) > 0) {
            $index = 0;
            foreach ($market_chart_arr as $chart_data) {
                $current = 'no';
                if ($chart_data['openTime_human_readible'] == $date_for_candel) {
                    $current_heigh_value = $chart_data['high'];
                    unset($market_chart_arr[$index]);
                    $look_forward_index = $index;
                    $current = 'yes';
                } else {
                    array_push($heighs_pont_arr, $chart_data['high']);
                }

                if (($look_forward_index != -1) && ($current == 'no')) {
                    array_push($look_forward_heigh_array, $chart_data['high']);
                }

                $index++;
            }
        }

        $heighst_swing_point = max($heighs_pont_arr);
        $look_forward_heigh = max($look_forward_heigh_array);

        $response_value = '';
        if ($current_heigh_value >= $heighst_swing_point) {
            if ($look_forward_heigh < $current_heigh_value) {
                $response_value = $current_heigh_value;
            }
        }

        return $response_value;
    } //End of find_swing_heighst_points

    public function find_swing_lowest_points_simulator($number_of_look_back, $number_of_look_forward, $date_for_candel, $coin_symbol) {

        $limit_no = $number_of_look_back + $number_of_look_forward + 1;
        $prevouse_date = date('Y-m-d H:00:00', strtotime('-' . $number_of_look_back . ' day', strtotime($date_for_candel)));

        $this->mongo_db->limit($limit_no);
        $this->mongo_db->order_by(array('timestampDate' => 1));
        $this->mongo_db->where_gte('timestampDate', $this->mongo_db->converToMongodttime($prevouse_date));
        $this->mongo_db->where(array('coin' => $coin_symbol));
        $market_chart_object = $this->mongo_db->get('market_chart_dailybase');
        $market_chart_arr = iterator_to_array($market_chart_object);

        $current_low_value = '';
        $look_forward_index = -1;
        $heighs_pont_arr = array();
        $look_forward_heigh_array = array();

        if (count($market_chart_arr) > 0) {
            $index = 0;
            foreach ($market_chart_arr as $chart_data) {
                $current = 'no';
                if ($chart_data['openTime_human_readible'] == $date_for_candel) {
                    $current_low_value = $chart_data['low'];
                    unset($market_chart_arr[$index]);
                    $look_forward_index = $index;
                    $current = 'yes';
                } else {
                    array_push($heighs_pont_arr, $chart_data['low']);
                }

                if (($look_forward_index != -1) && ($current == 'no')) {
                    array_push($look_forward_heigh_array, $chart_data['low']);
                }

                $index++;
            }
        }

        $lowest_swing_point = min($heighs_pont_arr);
        $look_forward_heigh = min($look_forward_heigh_array);
        $response_value = '';

        if ($current_low_value <= $lowest_swing_point) {
            if ($look_forward_heigh >= $current_low_value) {
                $response_value = $current_low_value;
            }
        }
        return $response_value;
    } //End of Find_swing_lowest_points

    public function calculate_rejection_candle($coin, $time) {
        //date_default_timezone_set("Asia/Karachi");
        $new_time = date("Y-m-d H", strtotime($time));

        $start_date = $new_time . ":00:00";
        $end_date = $new_time . ":59:59";

        $data_array = $this->get_current_candel($start_date, $coin);

        $open = $data_array[0]['open'];
        $close = $data_array[0]['close'];
        $high = $data_array[0]['high'];
        $low = $data_array[0]['low'];
        $bid_volume = $data_array[0]['bid_volume'];
        $ask_volume = $data_array[0]['ask_volume'];
        $total_volume = $ask_volume + $bid_volume;
        $rejected = 0;
        $rejection = '';
        $last_25_per_volume = $this->calculate_base_candel_for_rejection($coin, $start_date, $end_date);

        if ($total_volume > $last_25_per_volume) {
            if ($open < $close) {
                $candle_type = 'Demand';

                //Top Demand Rejection
                $top_percentage = ((($high - $close) / ($close - $open)) * 100);
                if ($top_percentage >= 40) {
                    $rejected = 1;
                } else {
                    $rejected = 0;
                }
                //Bottom Demand Rejection
                $bottom_percentage = ((($open - $low) / ($close - $open)) * 100);
                if ($bottom_percentage >= 40) {
                    $rejected = 1;
                } else {
                    if ($rejected == 0) {
                        $rejected = 0;
                    }
                }

                if (($top_percentage > $bottom_percentage) && $rejected == 1) {
                    $rejection = 'top_demand_rejection';
                } elseif (($bottom_percentage > $top_percentage) && $rejected == 1) {
                    $rejection = "bottom_demand_rejection";
                }

            }
            if ($open > $close) {
                //Top Supply Rejection
                $top_percentage = ((($high - $open) / ($open - $close)) * 100);
                if ($top_percentage >= 40) {
                    $rejected = 1;
                } else {
                    $rejected = 0;
                }
                //Bottom Supply Rejection
                $bottom_percentage = ((($close - $low) / ($open - $close)) * 100);
                if ($bottom_percentage >= 40) {
                    $rejected = 1;
                } else {
                    if ($rejected == 0) {
                        $rejected = 0;
                    }
                }

                if (($top_percentage > $bottom_percentage) && $rejected == 1) {
                    $rejection = 'top_supply_rejection';
                } elseif (($bottom_percentage > $top_percentage) && $rejected == 1) {
                    $rejection = "bottom_supply_rejection";
                }
            }
            if ($open == $close) {
                $candle_type = 'Normal';
            }
        }

        return $rejection;
    }

    public function get_current_candel($curretn_date, $coin_symbol) {
        $this->mongo_db->where(array('openTime_human_readible' => $curretn_date, 'coin' => $coin_symbol));
        $previouse_candel_result = $this->mongo_db->get('market_chart_dailybase');
        return $previouse_candel_arr = iterator_to_array($previouse_candel_result);
    } //End of get_current_candel

    public function calculate_base_candel_for_rejection($coin_symbol, $start_date, $end_date) {
        $total_volume = 0;
        $volume_arr = array();
        for ($index_date = 1; $index_date <= 50; $index_date++) {
            $from_date_for_candel = date("Y-m-d H:00:00", strtotime('-' . $index_date . ' day', strtotime($start_date)));
            $end_date_for_candel = date("Y-m-d H:59:59", strtotime('-' . $index_date . ' dat', strtotime($start_date)));
            $reject_per = $this->get_coin_rejection_value($coin_symbol);
            $ask_volume = $this->get_and_calculate_volume_for_candel_for_rejection($from_date_for_candel, $end_date_for_candel, $coin_symbol);
            $volume_arr[] = $ask_volume;
        }

        sort($volume_arr);
        $greater_ask_volume = 0;
        $demand_percentage_index = round((count($volume_arr) / 100) * $reject_per);

        $demond_greater_ask_volume = $volume_arr[$demand_percentage_index];
        return $demond_greater_ask_volume;
    } //End of calculate_base_candel

    public function get_and_calculate_volume_for_candel_for_rejection($from_date, $end_date, $coin_type) {
        $connect = $this->mongo_db->customQuery();
        $res = $connect->market_trade_daily_history->find(array(
            'coin' => $coin_type,
            'hour' => array('$gte' => $from_date, '$lte' => $end_date),
        ));
        $volume = 0;
        $res = iterator_to_array($res);
        foreach ($res as $key) {
            $volume += (float) $key['volume'];
        }
        return $volume;
    } //End of  get_and_calculate_volume_for_candel

    public function get_coin_rejection_value($symbol) {
        $this->db->dbprefix('coins');
        $this->db->select('rejection');
        $this->db->where('symbol', $symbol);
        $get = $this->db->get('coins');
        $get_arr = $get->row_array();
        return $get_arr['rejection'];
    } //end get_coin_rejection_value()

    public function save_last_barrier_value($last_barrrier_value, $coin_symbol, $global_swing_parent_status, $barrier_type, $date_for_candel) {

        $created_date = date('Y-m-d H:00:00', strtotime($date_for_candel));

        $barrier_creation_date = date('Y-m-d g:i:s');
        $barrier_creation_date = $this->mongo_db->converToMongodttime($barrier_creation_date);

        $insert_arr = array('barier_value' => (float) $last_barrrier_value, 'coin' => $coin_symbol, 'human_readible_created_date' => $created_date, 'created_date' => $this->mongo_db->converToMongodttime($created_date), 'barrier_type' => $barrier_type, 'global_swing_parent_status' => $global_swing_parent_status, 'status' => 0, 'barrier_status' => 'very_strong_barrier', 'barrier_creation_date' => $barrier_creation_date);

        $this->mongo_db->insert('barrier_values_collection_day', $insert_arr);

    } //End of

}

?>