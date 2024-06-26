<?php
class mod_candle_new extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    //get_chart_target_zones
    public function get_chart_target_zones($global_symbol)
    {
        $admin_id = $this->session->userdata('admin_id');
        $this->mongo_db->where(array(
            'coin' => $global_symbol
        ));
        $this->mongo_db->limit(5);
        $this->mongo_db->sort(array(
            '_id' => 'desc'
        ));
        $responseArr = $this->mongo_db->get('chart_target_zones');
        $fullarray   = array();
        foreach ($responseArr as $valueArr) {
            $start_date = $valueArr['start_date'];
            $end_date   = $valueArr['end_date'];
            if (!empty($valueArr)) {
                $returArr['start_value'] = (string) ($valueArr['start_value']);
                $returArr['end_value']   = (string) ($valueArr['end_value']);
                $returArr['unit_value']  = (string) ($valueArr['unit_value']);
                $returArr['start_date']  = (string) json_decode($start_date);
                $returArr['end_date']    = (string) json_decode($end_date);
                $returArr['type']        = $valueArr['type'];
            }
            $fullarray[] = $returArr;
        }
        return $fullarray;
    } //end get_chart_target_zones
    public function get_candelstick_data_from_database($global_symbol, $periods, $from_date_object, $to_date_object, $previous_date, $forward_date)
    {
        $this->mongo_db->where(array(
            'coin' => $global_symbol,
            'periods' => $periods
        ));
        if ($from_date_object && $to_date_object) {
            $this->mongo_db->where_gte('timestampDate', $from_date_object);
            $this->mongo_db->where_lte('timestampDate', $to_date_object);
        }
        if ($previous_date != '') {
            $previous_date            = $previous_date / 1000;
            $previous_date            = date("Y-m-d H:i:s", $previous_date);
            $previous_date_date_mongo = $this->mongo_db->converToMongodttime($previous_date);
            $this->mongo_db->where_lte('timestampDate', $previous_date_date_mongo);
        }
        if ($forward_date != '') {
            $forward_date            = $forward_date / 1000;
            $forward_date            = date("Y-m-d H:i:s", $forward_date);
            $forward_date_date_mongo = $this->mongo_db->converToMongodttime($forward_date);
            $this->mongo_db->where_gt('timestampDate', $forward_date_date_mongo);
            $this->mongo_db->sort(array(
                'timestampDate' => 'ASC'
            )); //ASC/DESC
        } else {
            $this->mongo_db->sort(array(
                'timestampDate' => 'DESC'
            )); //ASC/DESC
        }
        $this->mongo_db->limit(168);
        $responseArr      = $this->mongo_db->get('market_chart');
        $final_arr        = array();
        $total_volume_arr = array();
        foreach ($responseArr as $val_arr) {
            array_push($total_volume_arr, $val_arr['total_volume']);
            $final_arr[] = array(
                '_id' => $myText = (string) $val_arr['_id'],
                'timestampDate' => $val_arr['timestampDate'],
                'close' => num($val_arr['close']),
                'open' => num($val_arr['open']),
                'high' => num($val_arr['high']),
                'low' => num($val_arr['low']),
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
                'ask_volume' => $val_arr['ask_volume'],
                'bid_volume' => $val_arr['bid_volume'],
                'total_volume' => $val_arr['total_volume'],
                'black_ask_diff' => $val_arr['black_ask_diff'],
                'black_bid_diff' => $val_arr['black_bid_diff'],
                'yellow_ask_diff' => $val_arr['yellow_ask_diff'],
                'yellow_bid_diff' => $val_arr['yellow_bid_diff'],
                'black_wall_meta' => $val_arr['black_wall_meta'],
                'last_200_qty' => $val_arr['last_200_qty'],
                'last_qty_buy_vs_sell' => $val_arr['last_qty_buy_vs_sell'],
                'last_qty_timeago' => $val_arr['last_qty_timeago'],
                'score' => $val_arr['score'],
                'seven_level_depth' => $val_arr['seven_level_depth'],
                'market_depth_quantity' => $val_arr['market_depth_quantity']
            );
        }
        if ($forward_date == '') {
            $final_arr = array_reverse($final_arr);
        }
        $data['candle_arr'] = $final_arr;
        $max_volume         = max($total_volume_arr);
        $data['max_volume'] = $max_volume;
        return $data;
    }
    /** End of get_candelstick_data_from_database **/
    public function get_market_history_for_candel($symbol)
    {
        $pipeline               = array(
            '$group' => array(
                '_id' => '$hour',
                'volume' => array(
                    '$sum' => '$volume'
                ),
                'type' => array(
                    '$first' => '$type'
                ),
                'coin' => array(
                    '$first' => '$coin'
                ),
                'timestamp' => array(
                    '$first' => '$timestamp'
                ),
                'price' => array(
                    '$first' => '$price'
                ),
                'hour' => array(
                    '$first' => '$hour'
                ),
                'hour_timestamp' => array(
                    '$first' => '$hour_timestamp'
                )
            )
        );
        $project                = array(
            '$project' => array(
                "_id" => 1,
                "price" => 1,
                "volume" => 1,
                "type" => 1,
                "coin" => 1,
                'timestamp' => 1,
                'hour' => 1,
                'hour_timestamp' => 1
            )
        );
        $match                  = array(
            '$match' => array(
                'type' => 'ask',
                'coin' => $symbol
            )
        );
        $sort                   = array(
            '$sort' => array(
                'hour' => -1
            )
        );
        $limit                  = array(
            '$limit' => 1000
        );
        $connect                = $this->mongo_db->customQuery();
        $market_history_Arr_ask = $connect->market_trade_hourly_history->aggregate(array(
            $project,
            $match,
            $pipeline,
            $sort,
            $limit
        ));
        $market_history_Arr_ask = iterator_to_array($market_history_Arr_ask);
        $ask_price_volume_arr   = array();
        if (count($market_history_Arr_ask) > 0) {
            foreach ($market_history_Arr_ask as $key => $value) {
                $ask_price_volume_arr[$value['price']] = $value['volume'];
            }
        }
        $match                  = array(
            '$match' => array(
                'type' => 'bid',
                'coin' => $symbol
            )
        );
        $market_history_Arr_bit = $connect->market_trade_hourly_history->aggregate(array(
            $project,
            $match,

            $pipeline,
            $sort,
            $limit
        ));
        $market_history_Arr_bit = iterator_to_array($market_history_Arr_bit);
        $bid_volume_arr         = array();
        $total_volume           = array();
        if (count($market_history_Arr_bit) > 0) {
            foreach ($market_history_Arr_bit as $key => $value) {
                $total_volume[$value['hour']]   = $ask_volume_arr[$value['hour']] + $value['volume'];
                $bid_volume_arr[$value['hour']] = $value['volume'];
            }
        }
        $response_arr['bid_volume_arr']   = array_reverse($bid_volume_arr);
        $response_arr['ask_volume_arr']   = array_reverse($ask_volume_arr);
        $response_arr['total_volume_arr'] = array_reverse($total_volume);
        $max_volumer                      = max($total_volume);
        return $response_arr;
    } //End of get_market_history_for_candel
    public function get_candle_price_volume_detail($symbol, $start_date, $end_date, $unit_value)
    {
        $bid_arr_volume   = $this->get_bid_price_volume($symbol, $start_date, $end_date);
        $ask_arr_volume   = $this->get_ask_price_volume($symbol, $start_date, $end_date);
        $total_volume_arr = array();
        $total_volume     = 0;
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
        $max_volume                     = max($total_volume_arr);
        $retur_data['bid_arr_volume']   = $bid_arr_volume;
        $retur_data['ask_arr_volume']   = $ask_arr_volume;
        $retur_data['total_volume_arr'] = $total_volume_arr;
        $retur_data['max_volume']       = $max_volume;
        $retur_data['unit_value']       = $unit_value;
        return $retur_data;
    }
    /** End of get_candle_price_volume_detail **/
    public function get_hour_volume_array_detail($symbol, $start_date, $end_date)
    {
        $ask_arr_volume_hourly = $this->get_ask_price_volume_for_hour($symbol, $start_date, $end_date, 'ask');
        $bid_arr_volume_hourly = $this->get_ask_price_volume_for_hour($symbol, $start_date, $end_date, 'bid');
        $total_volume_arr      = array();
        $total_volume          = 0;
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
        $max_volume                          = max($total_volume_arr);
        $retur_data['bid_hour_arr_volume']   = $bid_arr_volume_hourly;
        $retur_data['ask_hour_arr_volume']   = $ask_arr_volume_hourly;
        $retur_data['total_hour_volume_arr'] = $total_volume_arr;
        $retur_data['max_volume_hourly']     = $max_volume;
        return $retur_data;
    }
    /*** End of get_hour_volume_array_detail ***/
    public function get_price_volume_for_hour($symbol, $start_date, $end_date, $type)
    {
        $match = array(
            'type' => $type,
            'coin' => $symbol,
            'timestamp' => array(
                '$gte' => $this->mongo_db->converToMongodttime($start_date),
                '$lte' => $this->mongo_db->converToMongodttime($end_date)
            )
        );
        $this->mongo_db->where($match);
        $res_volume_obj = $this->mongo_db->get('market_trade_hourly_history');
        $total_volume   = 0;
        $res_volume_arr = iterator_to_array($res_volume_obj);
        if (count($res_volume_arr) > 0) {
            foreach ($res_volume_arr as $row) {
                $total_volume += $row['volume'];
            }
        }
        return $total_volume;
    } //End of get_ask_price_volume_for_hour
    public function get_ask_price_volume_for_hour($symbol, $start_date, $end_date, $type)
    {
        $connect                = $this->mongo_db->customQuery();
        $pipeline               = array(
            '$group' => array(
                '_id' => '$hour',
                'volume' => array(
                    '$sum' => '$volume'
                ),
                'type' => array(
                    '$first' => '$type'
                ),
                'coin' => array(
                    '$first' => '$coin'
                ),
                'timestamp' => array(
                    '$first' => '$timestamp'
                ),
                'price' => array(
                    '$first' => '$price'
                ),
                'hour' => array(
                    '$first' => '$hour'
                ),
                'hour_timestamp' => array(
                    '$first' => '$hour_timestamp'
                )
            )
        );
        $project                = array(
            '$project' => array(
                "_id" => 1,
                "price" => 1,
                "volume" => 1,
                "type" => 1,
                "coin" => 1,
                'timestamp' => 1,
                'hour' => 1,
                'hour_timestamp' => 1
            )
        );
        $match                  = array(
            '$match' => array(
                'type' => $type,
                'coin' => $symbol,
                'hour' => array(
                    '$gte' => $start_date,
                    '$lte' => $end_date
                )
            )
        );
        $sort                   = array(
            '$sort' => array(
                'hour' => 1
            )
        );
        $limit                  = array(
            '$limit' => 1000
        );
        $connect                = $this->mongo_db->customQuery();
        $market_history_Arr_ask = $connect->market_trade_hourly_history->aggregate(array(
            $project,
            $match,
            $pipeline,
            $sort,
            $limit
        ));
        $market_history_Arr_ask = iterator_to_array($market_history_Arr_ask);
        $ask_volume_arr         = array();
        if (count($market_history_Arr_ask) > 0) {
            foreach ($market_history_Arr_ask as $key => $value) {
                $ask_volume_arr[$value['hour']] = $value['volume'];
            }
        }
        return $ask_volume_arr;
    }
    /** End of get_ask_price_volume_for_hour **/
    public function get_ask_price_volume($symbol, $start_date, $end_date)
    {
        $connect = $this->mongo_db->customQuery();
        $this->mongo_db->where('type', 'ask');
        $this->mongo_db->where('coin', $symbol);
        $this->mongo_db->where_gte('hour', $start_date);
        $this->mongo_db->where_lte('hour', $end_date);
        $this->mongo_db->sort(array(
            'hour' => 'desc'
        ));
        $responseArr          = $this->mongo_db->get('market_trade_hourly_history');
        $responseArr          = iterator_to_array($responseArr);
        $ask_price_volume_arr = array();
        $full_arr             = array();
        if (count($responseArr) > 0) {
            foreach ($responseArr as $value) {
                $ask_price_volume_arr[number_format($value['price'], 8)] = $value['volume'];
            }
        }
        ksort($ask_price_volume_arr);
        return $ask_price_volume_arr;
    }
    /** End of get_bid_price_volume**/
    public function get_bid_price_volume($symbol, $start_date, $end_date)
    {
        $connect = $this->mongo_db->customQuery();
        $this->mongo_db->where_gte('hour', $start_date);
        $this->mongo_db->where_lte('hour', $end_date);
        $this->mongo_db->where('type', 'bid');
        $this->mongo_db->where('coin', $symbol);
        $this->mongo_db->sort(array(
            'hour' => 'desc'
        ));
        $responseArr          = $this->mongo_db->get('market_trade_hourly_history');
        $responseArr          = iterator_to_array($responseArr);
        $bid_price_volume_arr = array();
        if (count($responseArr) > 0) {
            foreach ($responseArr as $value) {
                $bid_price_volume_arr[number_format($value['price'], 8)] = $value['volume'];
            }
        }
        ksort($bid_price_volume_arr);
        return $bid_price_volume_arr;
    }
    /** End of get_bid_price_volume**/
    public function get_date_range_for_history($symbol)
    {
        $connect                = $this->mongo_db->customQuery();
        $pipeline               = array(
            '$group' => array(
                '_id' => '$hour',
                'volume' => array(
                    '$sum' => '$volume'
                ),
                'type' => array(
                    '$first' => '$type'
                ),
                'coin' => array(
                    '$first' => '$coin'
                ),
                'timestamp' => array(
                    '$first' => '$timestamp'
                ),
                'price' => array(
                    '$first' => '$price'
                ),
                'hour' => array(
                    '$first' => '$hour'
                ),
                'hour_timestamp' => array(
                    '$first' => '$hour_timestamp'
                )
            )
        );
        $project                = array(
            '$project' => array(
                "_id" => 1,
                "price" => 1,
                "volume" => 1,
                "type" => 1,
                "coin" => 1,
                'timestamp' => 1,
                'hour' => 1,
                'hour_timestamp' => 1
            )
        );
        $match                  = array(
            '$match' => array(
                'type' => 'ask',
                'coin' => $symbol
            )
        );
        $sort                   = array(
            '$sort' => array(
                'hour' => -1
            )
        );
        $limit                  = array(
            '$limit' => 8
        );
        $connect                = $this->mongo_db->customQuery();
        $market_history_Arr_ask = $connect->market_trade_hourly_history->aggregate(array(
            $project,
            $match,
            $pipeline,
            $sort,
            $limit
        ));
        $market_history_Arr_ask = iterator_to_array($market_history_Arr_ask);
        $var_total_hour_array   = array();
        $ask_volume_arr         = array();
        if (count($market_history_Arr_ask) > 0) {
            foreach ($market_history_Arr_ask as $key => $value) {
                $ask_volume_arr[$value['hour']] = $value['volume'];
                array_push($var_total_hour_array, $value['hour']);
            }
        }
        return $var_total_hour_array;
    }
    /** End of get_date_range_for_history**/
    public function delete_data_from_data_base()
    {
        $removeTime          = date('Y-m-d G:i:s', strtotime('-1 hour', strtotime(date("Y-m-d G:i:s"))));
        $orig_date           = new DateTime($removeTime);
        $orig_date           = $orig_date->getTimestamp();
        $created_date        = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
        $db                  = $this->mongo_db->customQuery();
        ///////////////////////////////////////////////////////////////
        $delectmarket_prices = $db->market_prices->deleteMany(array(
            'created_date' => array(
                '$lte' => $created_date
            )
        ));
        $delectmarket_trades = $db->market_trades->deleteMany(array(
            'created_date' => array(
                '$lte' => $created_date
            )
        ));
    } //delete_data_from_data_base
    public function update_candel_for_formul_values($data_arr, $global_symbol)
    {
        $response = 'no data found';
        $db       = $this->mongo_db->customQuery();
        if (count($data_arr) > 0) {
            foreach ($data_arr as $arr) {
                $where    = array(
                    'openTime_human_readible' => $arr->openTime_human_readible,
                    'coin' => $global_symbol
                );
                $set      = array(
                    '$set' => array(
                        'candel_status' => $arr->candel_status,
                        'candle_type' => $arr->candle_type,
                        'ask_volume' => (float) $arr->ask_volume,
                        'bid_volume' => (float) $arr->bid_volume,
                        'total_volume' => (float) $arr->total_volume
                    )
                );
                $response = $db->market_chart->updateMany($where, $set);
                $response = 'ok';
            }
        }
        return $response;
    } //End o fupdate_candel_for_formul_values
    //get_order_array
    public function get_order_array($symbol, $admin_id, $global_mode)
    {
        $search_Array = array(
            'symbol' => $symbol,
            'is_sell_order' => 'sold',
            'admin_id' => $admin_id,
            'application_mode' => $global_mode
        );
        $this->mongo_db->where($search_Array);
        $res            = $this->mongo_db->get('sold_buy_orders');
        $final_arr      = array();
        $buy_orders_arr = iterator_to_array($res);
        foreach ($buy_orders_arr as $key => $arr) {
            $buy_order_id = $arr['_id'];
            $buy_date     = $arr['created_date'];
            if ($arr['buy_date']) {
                $buy_date = $arr['buy_date'];
            }
            $datetime          = $buy_date->toDateTime();
            $created_date      = $datetime->format(DATE_RSS);
            $market_sold_price = $arr['market_sold_price'];
            $datetime          = new DateTime($created_date);
            $datetime->format('Y-m-d H:00:00');
            $formated_date_time     = $datetime->format('Y-m-d H:00:00');
            $buy_order_date         = $formated_date_time;
            $search['buy_order_id'] = $buy_order_id;
            $this->mongo_db->where($search);
            $res_sold       = $this->mongo_db->get('orders');
            $sold_order_arr = iterator_to_array($res_sold);
            foreach ($sold_order_arr as $key => $value) {
                $buy_order_price  = $value['purchased_price'];
                $sell_order_price = $value['sell_price'];
                $sell_date        = $value['created_date'];
                if ($value['sell_date']) {
                    $sell_date = $value['sell_date'];
                }
                $datetime     = $sell_date->toDateTime();
                $created_date = $datetime->format(DATE_RSS);
                $datetime     = new DateTime($created_date);
                $datetime->format('Y-m-d H:00:00');
                $formated_date_time = $datetime->format('Y-m-d H:00:00');
                $sell_date          = $formated_date_time;
                $final_arr[]        = array(
                    'buy_date' => $buy_order_date,
                    'sell_date' => $sell_date,
                    'buy_price' => $buy_order_price,
                    'sell_price' => $market_sold_price
                );
            }
        }
        return $final_arr;
    } //end get_order_array
    public function save_task_manager_setting($data, $global_symbol)
    {
        $this->mongo_db->where(array(
            'coin' => $global_symbol
        ));
        $result_object = $this->mongo_db->get('task_manager_setting');
        $result_arr    = iterator_to_array($result_object);
        if (count($result_arr) > 0) {
            $this->mongo_db->set($data);
            //Update data in mongoTable
            $this->mongo_db->where(array(
                'coin' => $global_symbol
            ));
            $this->mongo_db->update('task_manager_setting');
        } else {
            $data['coin'] = $global_symbol;
            $this->mongo_db->insert('task_manager_setting', $data);
        }
        return true;
    } //End of save_task_manager_setting
    public function get_task_manager_setting($global_symbol)
    {
        $this->mongo_db->where(array(
            'coin' => $global_symbol
        ));
        $res = $this->mongo_db->get('task_manager_setting');
        return iterator_to_array($res);
    } //End of get_task_manager_setting
    public function calculate_pressure_up_and_down($start_date, $end_date, $coin, $pressure_type)
    {
        $this->mongo_db->where_gte('created_date', $this->mongo_db->converToMongodttime($start_date));
        $this->mongo_db->where_lt('created_date', $this->mongo_db->converToMongodttime($end_date));
        $this->mongo_db->where(array(
            'coin' => $coin,
            'pressure' => $pressure_type
        ));
        $res     = $this->mongo_db->get('order_book_pressure');
        $res_arr = iterator_to_array($res);
        return $total_pressure = count($res_arr);
    } //End of calculate_pressure_up_and_down
    public function get_coin_unit_value($symbol)
    {
        $this->db->dbprefix('coins');
        $this->db->select('unit_value');
        $this->db->where('symbol', $symbol);
        $get     = $this->db->get('coins');
        $get_arr = $get->row_array();
        return $get_arr['unit_value'];
    } //end get_coin_unit_value()
    public function calculate_up_down_wall($coin_symbol, $datetime)
    {
        $start_date               = date("Y-m-d H:00:00", strtotime($datetime));
        $end_date                 = date("Y-m-d H:59:59", strtotime($datetime));
        $where['coin']            = $coin_symbol;
        $where['created_date']    = array(
            '$gte' => $this->mongo_db->converToMongodttime($start_date),
            '$lte' => $this->mongo_db->converToMongodttime($end_date)
        );
        $order_by['created_date'] = -1;
        $this->mongo_db->where($where);
        $this->mongo_db->order_by($order_by);
        $get   = $this->mongo_db->get('depth_wall_history');
        $array = iterator_to_array($get);
        $x     = 1;
        for ($i = 0; $i < count($array); $i++) {
            if (!empty($array[$i]['current_market_value'])) {
                $current_val = $array[$i]['current_market_value'];
                $ask_wall    = $array[$i]['ask_black_wall'];
                $bid_wall    = $array[$i]['bid_black_wall'];
                $unit_val    = $this->get_coin_unit_value($coin_symbol);
                $bid_diff[]  = ($current_val - $bid_wall) / $unit_val;
                $ask_diff[]  = ($ask_wall - $current_val) / $unit_val;
                $x++;
            }
        }
        $bid_diff_sum = array_sum($bid_diff);
        $bid          = $bid_diff_sum / $x;
        $ask_diff_sum = array_sum($ask_diff);
        $ask          = $ask_diff_sum / $x;
        return array(
            "ask_diff" => $ask,
            'bid_diff' => $bid
        );
    }
    public function calculate_up_down_yellow_wall($coin_symbol, $datetime)
    {
        $start_date               = date("Y-m-d H:00:00", strtotime($datetime));
        $end_date                 = date("Y-m-d H:59:59", strtotime($datetime));
        $where['coin']            = $coin_symbol;
        $where['created_date']    = array(
            '$gte' => $this->mongo_db->converToMongodttime($start_date),
            '$lte' => $this->mongo_db->converToMongodttime($end_date)
        );
        $order_by['created_date'] = -1;
        $this->mongo_db->where($where);
        $this->mongo_db->order_by($order_by);
        $get   = $this->mongo_db->get('depth_wall_history');
        $array = iterator_to_array($get);
        $x     = 1;
        for ($i = 0; $i < count($array); $i++) {
            if (!empty($array[$i]['current_market_value'])) {
                $current_val = $array[$i]['current_market_value'];

                $ask_wall    = $array[$i]['ask_yellow_wall'];
                $bid_wall    = $array[$i]['bid_yellow_wall'];
                $unit_val    = $this->get_coin_unit_value($coin_symbol);
                $bid_diff[]  = ($current_val - $bid_wall) / $unit_val;
                $ask_diff[]  = ($ask_wall - $current_val) / $unit_val;
                $x++;
            }
        }
        $bid_diff_sum = array_sum($bid_diff);
        $bid          = $bid_diff_sum / $x;
        $ask_diff_sum = array_sum($ask_diff);
        $ask          = $ask_diff_sum / $x;
        return array(
            "ask_diff" => $ask,
            'bid_diff' => $bid
        );
    }
    public function sma_of_pressure($start_date, $end_date, $coin, $sma_offset)
    {
        //$sma_offset = 10;
        for ($i = 0; $i < $sma_offset; $i++) {
            $start_date = date('Y-m-d H:00:00', strtotime('-' . $i . ' hours', strtotime($start_date)));
            $end_date   = date('Y-m-d H:59:59', strtotime('-' . $i . ' hours', strtotime($end_date)));
            $up[]       = $this->calculate_pressure_up_and_down($start_date, $end_date, $coin, 'up');
            $down[]     = $this->calculate_pressure_up_and_down($start_date, $end_date, $coin, 'down');
        }
        $denominator = 0;
        $sum         = 0;
        for ($j = 0; $j < count($up); $j++) {
            $denominator++;
            $sum += $up[$j];
        }
        $up_sma      = ($sum / $denominator);
        $denominator = 0;
        $sum         = 0;
        for ($j = 0; $j < count($down); $j++) {
            $denominator++;
            $sum += $down[$j];
        }
        $down_sma = ($sum / $denominator);
        return array(
            "up_sma" => round($up_sma),
            "down_sma" => round($down_sma)
        );
    }
    public function getSentimentReportHour($type, $source, $coin, $startDateSocail, $end, $formula, $time)
    {
        if ($time == '1d') {
            if ($source == 'twitter') {
                $Table = 'sentiments_tweet_new_5';
            }
            // Custome QueryGoes Here
            ini_set("memory_limit", -1);
            $startNew = $this->mongo_db->converToMongodttime($startDateSocail);
            $endNew   = $this->mongo_db->converToMongodttime($end);
            $this->mongo_db->where_gte('created_date', $startNew);
            $this->mongo_db->where_lt('created_date', $endNew);
            //$this->mongo_db->limit(100);
            $this->mongo_db->where('keyword', ($coin));
            //$this->mongo_db->limit(1);
            $res              = $this->mongo_db->get($Table);
            $result           = iterator_to_array($res);
            $countTotalRecord = count($result);
            if ($countTotalRecord != '') {
            }
            $negative_sentiment = 0;
            $positive_sentiment = 0;
            $countnegative      = '';
            $countpositive      = '';
            $potivrat           = 0;
            $negative           = 0;
            $negative_count     = 0;
            $potivrat_count     = 0;
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
            $totalrecord              = $countTotalRecord;
            $negative_sentiment       = $negative_sentiment;
            $positive_sentiment       = $positive_sentiment;
            $count_negative_sentiment = $countnegative;
            $count_positive_sentiment = $countpositive;
            if ($positive_sentiment != 0) {
                $potivrat       = $positive_sentiment / $totalrecord;
                $potivrat_count = $positive_sentiment / $count_negative_sentiment;
            }
            if ($negative_sentiment != 0) {
                $negative       = $negative_sentiment / $totalrecord;
                $negative_count = $negative_sentiment / $count_positive_sentiment;
            }
            //$temp = array();
            $temp['totalrecord']        = round($totalrecord);
            $temp['sum_nageative']      = round($negative_sentiment);
            $temp['sum_positive']       = round($positive_sentiment);
            $temp['t_neg_divide_t']     = round($negative);
            $temp['t_pos_divide_t']     = round($potivrat);
            $temp['t_neg_divide_t_neg'] = round($negative_count);
            $temp['t_pos_divide_t_pos'] = round($potivrat_count);
            $final_array[]              = $temp;
        } //   for ($k = 1; $k < $totalDays; $k++) {
        return $final_array;
    } //end getSentimentReport
    public function get_buy_sell_rules_log($symbol, $start_date, $end_date)
    {
        $type      = 'buy';
        $buy_arr   = $this->get_rules_log($symbol, $start_date, $end_date, $type); 
        $type      = 'sell';
        $sell_arr  = $this->get_rules_log($symbol, $start_date, $end_date, $type);
        $buy_rule  = array();
        $sell_rule = array();
        if ($_GET['trigger_type'] == 'percentile' || $_GET['trigger_type'] == 'box_trigger') {
            foreach ($buy_arr as $key => $value) {
                $arr                             = array();
                $arr['rule_number']              = $value['order_level'];
                $arr['buy_price']                = $value['market_price'];
                $arr['sell_price']               = 0;
                $buy_rule[$value['order_level']] = $arr;
            }
            foreach ($sell_arr as $key => $value) {
                $arr                              = array();
                $arr['rule_number']               = $value['order_level'];
                $arr['buy_price']                 = 0;
                $arr['sell_price']                = $value['market_price'];
                $sell_rule[$value['order_level']] = $arr;
            }
            $rule_array = array(
                "level_1",
                "level_2",
                "level_3",
                "level_4",
                "level_5",
                "level_6",
                "level_7",
                "level_8",
                "level_9",
                "level_10",
                "level_11",
                "level_12",
                "level_13",
                "level_14",
                "level_15"
            );
        } else {
            foreach ($buy_arr as $key => $value) {
                $arr                             = array();
                $arr['rule_number']              = $value['rule_number'];
                $arr['buy_price']                = $value['market_price'];
                $arr['sell_price']               = 0;
                $buy_rule[$value['rule_number']] = $arr;
            }
            foreach ($sell_arr as $key => $value) {
                $arr                              = array();
                $arr['rule_number']               = $value['rule_number'];
                $arr['buy_price']                 = 0;
                $arr['sell_price']                = $value['market_price'];
                $sell_rule[$value['rule_number']] = $arr;
            }
            $rule_array = array(
                "rule_no_1",
                "rule_no_2",
                "rule_no_3",
                "rule_no_4",
                "rule_no_5",
                "rule_no_6",
                "rule_no_7",
                "rule_no_8",
                "rule_no_9",
                "rule_no_10"
            );
        }
        $color = array();
        foreach ($rule_array as $key => $value) {
            if (array_key_exists($value, $buy_rule)) {
                $color[$value]['color']      = 'blue';
                $color[$value]['buy_price']  = num($buy_rule[$value]['buy_price']);
                $color[$value]['sell_price'] = num($buy_rule[$value]['sell_price']);
            }
            if (array_key_exists($value, $sell_rule)) {
                if (array_key_exists($value, $color)) {
                    $color[$value]['color']      = 'plum';
                    $color[$value]['buy_price']  = num($buy_rule[$value]['buy_price']);
                    $color[$value]['sell_price'] = num($sell_rule[$value]['sell_price']);
                } else {
                    $color[$value]['color']      = 'red';
                    $color[$value]['buy_price']  = num($sell_rule[$value]['buy_price']);
                    $color[$value]['sell_price'] = num($sell_rule[$value]['sell_price']);
                }
            }
            if (!array_key_exists($value, $color)) {
                $color[$value]['color']      = 'white';
                $color[$value]['buy_price']  = num(0);
                $color[$value]['sell_price'] = num(0);
            }
        }
        return $color;
    }
    public function get_rules_log($symbol, $start_date, $end_date, $type)
    {
        $start_date      = date("Y-m-d H:05:00", strtotime($start_date));
        $end_date        = date("Y-m-d H:59:59", strtotime($end_date));
        $db              = $this->mongo_db->customQuery();
        $collection_name = 'barrier_trigger_true_rules_collection';
        if ($_GET['trigger_type'] == 'percentile') {
            $where_array['trigger_type'] = 'barrier_percentile_trigger';
            $where_array['order_level']  = array(
                '$in' => array(
                    "level_1",
                    "level_2",
                    "level_3",
                    "level_4",
                    "level_5",
                    "level_6",
                    "level_7",
                    "level_8",
                    "level_9",
                    "level_10",
                    "level_11",
                    "level_12",
                    "level_13",
                    "level_14",
                    "level_15"
                )
            );
            $where_array['coin_symbol']  = $symbol;
            $where_array['type']         = $type;
            $where_array['created_date'] = array(
                '$gte' => $this->mongo_db->converToMongodttime($start_date),
                '$lt' => $this->mongo_db->converToMongodttime($end_date)
            );
            $pipeline                    = array(
                array(
                    '$project' => array(
                        "coin_symbol" => 1,
                        "log" => 1,
                        "type" => 1,
                        "_id" => 1,
                        "rule_number" => 1,
                        'created_date' => 1,
                        'market_price' => 1,
                        'trigger_type' => 1,
                        'order_level' => 1
                    )
                ),
                array(
                    '$match' => $where_array
                ),
                array(
                    '$sort' => array(
                        'created_date' => 1
                    )
                ),
                // array('$sort'=>array('price'=>1)),
                array(
                    '$group' => array(
                        '_id' => array(
                            'order_level' => '$order_level'
                        ),
                        'log' => array(
                            '$first' => '$log'
                        ),
                        'type' => array(
                            '$first' => '$type'
                        ),
                        'rule_number' => array(
                            '$first' => '$rule_number'
                        ),
                        'coin_symbol' => array(
                            '$first' => '$coin_symbol'
                        ),
                        'created_date' => array(
                            '$first' => '$created_date'
                        ),
                        'market_price' => array(
                            '$first' => '$market_price'
                        ),
                        'trigger_type' => array(
                            '$first' => '$trigger_type'
                        ),
                        'order_level' => array(
                            '$first' => '$order_level'
                        )
                    )
                ),
                array(
                    '$sort' => array(
                        'created_date' => 1
                    )
                )
            );
        } elseif ($_GET['trigger_type'] == 'simulator') {
            $where_array['trigger_type'] = 'barrier_trigger_simulator';
            $where_array['rule_number']  = array(
                '$in' => array(
                    "rule_no_1",
                    "rule_no_2",
                    "rule_no_3",
                    "rule_no_4",
                    "rule_no_5",
                    "rule_no_6",
                    "rule_no_7",
                    "rule_no_8",
                    "rule_no_9",
                    "rule_no_10"
                )
            );
            $where_array['coin_symbol']  = $symbol;
            $where_array['type']         = $type;
            $where_array['created_date'] = array(
                '$gte' => $this->mongo_db->converToMongodttime($start_date),
                '$lt' => $this->mongo_db->converToMongodttime($end_date)
            );
            $pipeline                    = array(
                array(
                    '$project' => array(
                        "coin_symbol" => 1,
                        "log" => 1,
                        "type" => 1,
                        "_id" => 1,
                        "rule_number" => 1,
                        'created_date' => 1,
                        'market_price' => 1,
                        'trigger_type' => 1,
                        'order_level' => 1
                    )
                ),
                array(
                    '$match' => $where_array
                ),
                array(
                    '$sort' => array(
                        'created_date' => 1
                    )
                ),
                // array('$sort'=>array('price'=>1)),
                array(
                    '$group' => array(
                        '_id' => array(
                            'rule_number' => '$rule_number'
                        ),
                        'log' => array(
                            '$first' => '$log'
                        ),
                        'type' => array(
                            '$first' => '$type'
                        ),
                        'rule_number' => array(
                            '$first' => '$rule_number'
                        ),
                        'coin_symbol' => array(
                            '$first' => '$coin_symbol'
                        ),
                        'created_date' => array(
                            '$first' => '$created_date'
                        ),
                        'market_price' => array(
                            '$first' => '$market_price'
                        ),
                        'trigger_type' => array(
                            '$first' => '$trigger_type'
                        ),
                        'order_level' => array(
                            '$first' => '$order_level'
                        )
                    )
                ),
                array(
                    '$sort' => array(
                        'created_date' => 1
                    )
                )
            );
        } elseif ($_GET['trigger_type'] == 'box_trigger') {
            $where_array['trigger_type'] = 'box_trigger_3';
            $where_array['order_level']  = array(
                '$in' => array(
                    "level_1",
                    "level_2",
                    "level_3",
                    "level_4",
                    "level_5",
                    "level_6",
                    "level_7",
                    "level_8",
                    "level_9",
                    "level_10"
                )
            );
            $where_array['coin_symbol']  = $symbol;
            $where_array['type']         = $type;
            $where_array['created_date'] = array(
                '$gte' => $this->mongo_db->converToMongodttime($start_date),
                '$lt' => $this->mongo_db->converToMongodttime($end_date)
            );
            $pipeline                    = array(
                array(
                    '$project' => array(
                        "coin_symbol" => 1,
                        "log" => 1,
                        "type" => 1,
                        "_id" => 1,
                        "rule_number" => 1,
                        'created_date' => 1,
                        'market_price' => 1,
                        'trigger_type' => 1,
                        'order_level' => 1
                    )
                ),
                array(
                    '$match' => $where_array
                ),
                array(
                    '$sort' => array(
                        'created_date' => 1
                    )
                ),
                // array('$sort'=>array('price'=>1)),
                array(
                    '$group' => array(
                        '_id' => array(
                            'order_level' => '$order_level'
                        ),
                        'log' => array(
                            '$first' => '$log'
                        ),
                        'type' => array(
                            '$first' => '$type'
                        ),
                        'rule_number' => array(
                            '$first' => '$rule_number'
                        ),
                        'coin_symbol' => array(
                            '$first' => '$coin_symbol'
                        ),
                        'created_date' => array(
                            '$first' => '$created_date'
                        ),
                        'market_price' => array(
                            '$first' => '$market_price'
                        ),
                        'trigger_type' => array(
                            '$first' => '$trigger_type'
                        ),
                        'order_level' => array(
                            '$first' => '$order_level'
                        )
                    )
                ),
                array(
                    '$sort' => array(
                        'created_date' => 1
                    )
                )
            );
        } else {
            $where_array['trigger_type'] = 'barrier_trigger';
            $where_array['rule_number']  = array(
                '$in' => array(
                    "rule_no_1",
                    "rule_no_2",
                    "rule_no_3",
                    "rule_no_4",
                    "rule_no_5",
                    "rule_no_6",
                    "rule_no_7",
                    "rule_no_8",
                    "rule_no_9",
                    "rule_no_10"
                )
            );
            $where_array['coin_symbol']  = $symbol;
            $where_array['type']         = $type;
            $where_array['created_date'] = array(
                '$gte' => $this->mongo_db->converToMongodttime($start_date),
                '$lt' => $this->mongo_db->converToMongodttime($end_date)
            );
            $pipeline                    = array(
                array(
                    '$project' => array(
                        "coin_symbol" => 1,
                        "log" => 1,
                        "type" => 1,
                        "_id" => 1,
                        "rule_number" => 1,
                        'created_date' => 1,
                        'market_price' => 1,
                        'trigger_type' => 1,
                        'order_level' => 1
                    )
                ),
                array(
                    '$match' => $where_array
                ),
                array(
                    '$sort' => array(
                        'created_date' => 1
                    )
                ),
                // array('$sort'=>array('price'=>1)),
                array(
                    '$group' => array(
                        '_id' => array(
                            'rule_number' => '$rule_number'
                        ),
                        'log' => array(
                            '$first' => '$log'
                        ),
                        'type' => array(
                            '$first' => '$type'
                        ),
                        'rule_number' => array(
                            '$first' => '$rule_number'
                        ),
                        'coin_symbol' => array(
                            '$first' => '$coin_symbol'
                        ),
                        'created_date' => array(
                            '$first' => '$created_date'
                        ),
                        'market_price' => array(
                            '$first' => '$market_price'
                        ),
                        'trigger_type' => array(
                            '$first' => '$trigger_type'
                        ),
                        'order_level' => array(
                            '$first' => '$order_level'
                        )
                    )
                ),
                array(
                    '$sort' => array(
                        'created_date' => 1
                    )
                )
            );
        }
        $allow       = array(
            'allowDiskUse' => true
        );
        $responseArr = $db->$collection_name->aggregate($pipeline, $allow);
        $retArr      = iterator_to_array($responseArr);
        // echo "<pre>";
        // print_r($retArr);
        return $retArr;
    }
} //End of Model
?>