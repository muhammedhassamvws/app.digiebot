<?php

class mod_dashboard extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function barrierListing($skip, $limit)
    {
        $search_array = array();
        $search_array['coin'] = $this->session->userdata('global_symbol');

        if (!empty($this->session->userdata('filter_data'))) {
            $filter_data = $this->session->userdata('filter_data');
        }

        /*$this->mongo_db->where($search_arr);
        $this->mongo_db->limit(20);

        $this->mongo_db->order_by(array('created_date' => -1));
        $depth_responseArr = $this->mongo_db->get('barrier_values_collection');

        $arr = iterator_to_array($depth_responseArr);
        $data['barrier_arr'] = $arr;

        return     $data['barrier_arr'];
         */
        //$admin_id = $this->session->userdata('cust_id');
        //$search_array = array('admin_id' => $admin_id);
        //Check Filter Data

        if ($filter_data['status'] != '') {
            $status = $filter_data['status'];
            $search_array['barrier_status'] = $status;
        }
        if ($filter_data['filter_coin'] != '') {
            $filter_coin = $filter_data['filter_coin'];
            $search_array['coin'] = $filter_coin;
        }
        if ($filter_data['breakable'] != '') {
            $breakable = $filter_data['breakable'];
            $search_array['breakable'] = $breakable;
        }
        if ($filter_data['global_swing_parent_status'] != '') {
            $global_swing_parent_status = $filter_data['global_swing_parent_status'];
            $search_array['global_swing_parent_status'] = $global_swing_parent_status;
        }

        if ($filter_data['barrier_type'] != '') {
            $barrier_type = $filter_data['barrier_type'];
            $search_array['barrier_type'] = $barrier_type;
        }

        if ($filter_data['start_date'] != '' && $filter_data['end_date'] != '') {
            $timezone = $this->session->userdata('timezone');
            $created_datetime = date('Y-m-d G:i:s', strtotime($filter_data['start_date']));

            $orig_date = new DateTime($created_datetime, new DateTimeZone($timezone));

            $orig_date->setTimezone(new DateTimeZone('UTC'));

            /*$orig_date = new DateTime($created_datetime);
            $new_timezone = new DateTimeZone('UTC');
             */

            //$orig_date = $orig_date->format("Y-m-d H:i:s");
            $orig_date = $orig_date->getTimestamp();
            $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
            $created_datetime22 = date('Y-m-d G:i:s', strtotime($filter_data['end_date']));
            /*$orig_date22 = new DateTime($created_datetime22);
            $new_timezone = new DateTimeZone('UTC');
             */

            $orig_date22 = new DateTime($created_datetime22, new DateTimeZone($timezone));

            $orig_date22->setTimezone(new DateTimeZone('UTC'));

            $orig_date22 = $orig_date22->getTimestamp();
            $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
            $search_array['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
        }

        $connetct = $this->mongo_db->customQuery();
        $qr = array('skip' => $skip, 'sort' => array('created_date' => -1), 'limit' => $limit);
        $cursor = $connetct->barrier_values_collection->find($search_array, $qr);

        //echo "<pre>";

        $responseArr = iterator_to_array($cursor);
        $return_data['finalArray'] = $responseArr;

        return $return_data;
    }

    // End barrierListing

    public function countBarrierListing()
    {
        $filter_data = $this->session->userdata('filter_data');
        if (!empty($filter_data)) {
            //echo "<pre>";
        }

        /*
        $this->mongo_db->where($search_arr);
        $this->mongo_db->limit(20);

        $this->mongo_db->order_by(array('created_date' => -1));
        $depth_responseArr = $this->mongo_db->get('barrier_values_collection');

        $arr = iterator_to_array($depth_responseArr);
        $data['barrier_arr'] = $arr;

        return     $data['barrier_arr'];

         */
        $search_array['coin'] = $this->session->userdata('global_symbol');

        if ($filter_data['filter_coin'] != '') {
            $filter_coin = $filter_data['filter_coin'];
            $search_array['coin'] = $filter_coin;
        }

        if ($filter_data['barrier_type'] != '') {
            $barrier_type = $filter_data['barrier_type'];
            $search_array['barrier_type'] = $barrier_type;
        }
        if ($filter_data['breakable'] != '') {
            $breakable = $filter_data['breakable'];
            $search_array['breakable'] = $breakable;
        }
        /*echo "<pre>";
        print_r($search_array);
        exit;
         */

        if ($filter_data['start_date'] != '' && $filter_data['end_date'] != '') {
            $timezone = $this->session->userdata('timezone');
            $created_datetime = date('Y-m-d G:i:s', strtotime($filter_data['start_date']));

            $orig_date = new DateTime($created_datetime, new DateTimeZone($timezone));

            $orig_date->setTimezone(new DateTimeZone('UTC'));

            /*$orig_date = new DateTime($created_datetime);
            $new_timezone = new DateTimeZone('UTC');
             */

            //$orig_date = $orig_date->format("Y-m-d H:i:s");
            $orig_date = $orig_date->getTimestamp();
            $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
            $created_datetime22 = date('Y-m-d G:i:s', strtotime($filter_data['end_date']));
            /*$orig_date22 = new DateTime($created_datetime22);
            $new_timezone = new DateTimeZone('UTC');
             */

            $orig_date22 = new DateTime($created_datetime22, new DateTimeZone($timezone));

            $orig_date22->setTimezone(new DateTimeZone('UTC'));

            $orig_date22 = $orig_date22->getTimestamp();
            $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
            $search_array['created_date'] = array('$gte' => $start_date, '$lte' => $end_date);
        }

        $connetct = $this->mongo_db->customQuery();
        $qr = array('skip' => $skip, 'sort' => array('created_date' => -1), 'limit' => $limit);
        $cursor = $connetct->barrier_values_collection->find($search_array, $qr);
        $responseArr = iterator_to_array($cursor);

        $count = count($responseArr);

        return $count;

        //$list_indexes  =  $this->mongo_db->list_indexes('orders_history_log');

        if ($_SERVER['REMOTE_ADDR'] == '101.50.127.131') {
        }

        /*        $admin_id = $this->session->userdata('cust_id');
        $search_array = array('admin_id' => $admin_id);*/
        //Check Filter Data
        $session_post_data = $this->session->userdata('filter_data');
        if ($session_post_data['coin_filter'] != '') {
            $symbol = $session_post_data['coin_filter'];
            $search_array['symbol'] = $symbol;
        }
        if ($session_post_data['type_filter'] != '') {
            $order_type = $session_post_data['type_filter'];
            $search_array['order_type'] = $order_type;
        }

        if ($session_post_data['start_date'] != '' && $session_post_data['end_date'] != '') {
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
        $connetct = $this->mongo_db->customQuery();
        $qr = array('sort' => array('modified_date' => -1));
        $cursor = $connetct->buy_orders->find($search_array, $qr);
        $responseArr = iterator_to_array($cursor);

        foreach ($responseArr as $valueArr) {
            $returArr = array();
            if (!empty($valueArr)) {
                // To get of sell order record
                if ($valueArr['is_sell_order'] == 'sold') {
                    $market_sold_price = num($valueArr['market_sold_price']);
                    $current_order_price = num($valueArr['market_value']);
                    $quantity = num($valueArr['quantity']);
                    $current_data2222 = $market_sold_price - $current_order_price;
                    $profit_data = ($current_data2222 * 100 / $market_sold_price);
                    $profit_data = number_format((float) $profit_data, 2, '.', '');

                    $total_profit += $quantity * $profit_data;
                    $total_quantity += $quantity;
                }
                // to get order of error
                $id = $valueArr['_id'];
                $this->mongo_db->where(array('order_id' => $id));
                $this->mongo_db->order_by(array('_id' => -1));
                $get = $this->mongo_db->get('orders_history_log');
                $record = iterator_to_array($get);

                foreach ($record as $key => $value222) {
                    if (!empty($value222)) {
                        if ($value222['type'] == 'buy_error' || $value222['type'] == 'sell_error') {
                            $error[] = $value222;
                        }
                    }
                }
            }
        }
        // To get data from
        $avg_profit = $total_profit / $total_quantity;
        $cursor = $connetct->buy_orders->count($search_array);
        $output = array(
            'count' => $cursor,
            'error' => $error,
            'quantity_avg' => $quantity_avg,
            'avg_profit' => $avg_profit,
            'market_sold_price_avg' => $market_sold_price_avg,
            'current_order_price_avg' => $current_order_price_avg,
        );

        return $output;
    }

    // End countBarrierListing

    public function get_market_data()
    {
        //$now_date = date('Y-m-d G:i:s', strtotime('-5 minute'));
        //    $this->mongo_db->where(array('type'=> 'ask', 'coin'=> 'BNBBTC', 'created_date' => $now_date));

        $price = (float) $_GET['price'];
        $this->mongo_db->where(array('type' => 'ask', 'coin' => 'BNBBTC', 'price' => $price));
        $this->mongo_db->limit(500);
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('market_depth');

        $fullarray = array();
        foreach ($responseArr as $valueArr) {
            $returArr = array();

            if (!empty($valueArr)) {
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $returArr['_id'] = $valueArr['_id'];
                $returArr['price'] = $valueArr['price'];
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['type'] = $valueArr['type'];
                $returArr['coin'] = $valueArr['coin'];
                $returArr['created_date'] = $formated_date_time;
            }

            $fullarray[] = $returArr;
        }

        // $sort = array();
        // foreach($fullarray as $k=>$v) {
        //     $sort['price'][$k] = $v['price'];
        // }
        // array_multisort($sort['price'], SORT_ASC, $fullarray);
    }

    //get_market_buy_depth
    public function get_market_buy_depth($market_value = '')
    {
        $global_symbol = $this->session->userdata('global_symbol');

        if (isset($_GET['market_value']) && $_GET['market_value'] != '' && $market_value == '') {
            $market_value = $_GET['market_value'];
        } elseif ($market_value != '') {
            $market_value = $market_value;
        } else {
            //Get Market Prices
            $market_value = $this->get_market_value($global_symbol);
        }

        ///////////////////////////////////////
        $db = $this->mongo_db->customQuery();
        $priceAsk = (float) $market_value;

        $pipeline = array(
            array(
                '$project' => array(
                    'price' => 1,
                    'quantity' => 1,
                    'type' => 1,
                    'coin' => 1,
                    'created_date' => 1,
                ),
            ),

            array(
                '$match' => array(
                    'coin' => $global_symbol,
                    'type' => 'ask',
                    'price' => array('$gte' => $priceAsk),
                ),
            ),

            array('$sort' => array('created_date' => -1)),
            // array('$sort'=>array('price'=>1)),
            array('$group' => array(
                '_id' => array('price' => '$price'),
                'quantity' => array('$first' => '$quantity'),
                'type' => array('$first' => '$type'),
                'coin' => array('$first' => '$coin'),
                'created_date' => array('$first' => '$created_date'),
                'price' => array('$first' => '$price'),
            ),
            ),
            array('$sort' => array('price' => 1)),
            array('$limit' => 20),
        );

        $allow = array('allowDiskUse' => true);
        $responseArr = $db->market_depth->aggregate($pipeline, $allow);
        // if ($_SERVER['REMOTE_ADDR'] == '203.175.73.182') {
        //     echo "<pre>";
        //     print_r(iterator_to_array($responseArr));
        //     exit;
        // }
        $fullarray = array();
        foreach ($responseArr as $valueArr) {
            $returArr = array();

            if (!empty($valueArr)) {
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $returArr['_id'] = $valueArr['_id'];
                $returArr['price'] = $valueArr['price'];
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['type'] = $valueArr['type'];
                $returArr['coin'] = $valueArr['coin'];
                $returArr['created_date'] = $formated_date_time;
            }

            $fullarray[] = $returArr;
        }

        $sort = array();
        foreach ($fullarray as $k => $v) {
            $sort['price'][$k] = $v['price'];
        }
        if(isset($sort['price'])){
         array_multisort($sort['price'], SORT_ASC, $fullarray);   
        }
        

        $data['market_value'] = $market_value;
        $data['fullarray'] = $fullarray;

        return $data;
    }

    //end get_market_buy_depth

    //get_market_sell_depth
    public function get_market_sell_depth($market_value = '')
    {
        $global_symbol = $this->session->userdata('global_symbol');

        if (isset($_GET['market_value']) && $_GET['market_value'] != '' && $market_value == '') {
            $market_value = $_GET['market_value'];
        } elseif ($market_value != '') {
            $market_value = $market_value;
        } else {
            //Get Market Prices
            $market_value = $this->get_market_value($global_symbol);
        }

        ///////////////////////////////////////
        $db = $this->mongo_db->customQuery();
        $priceAsk = (float) $market_value;

        $pipeline = array(
            array(
                '$project' => array(
                    'price' => 1,
                    'quantity' => 1,
                    'type' => 1,
                    'coin' => 1,
                    'created_date' => 1,
                ),
            ),

            array(
                '$match' => array(
                    'coin' => $global_symbol,
                    'type' => 'bid',
                    'price' => array('$lte' => $priceAsk),
                ),
            ),

            array('$sort' => array('created_date' => -1)),
            // array('$sort'=>array('price'=>1)),
            array('$group' => array(
                '_id' => array('price' => '$price'),
                'quantity' => array('$first' => '$quantity'),
                'type' => array('$first' => '$type'),
                'coin' => array('$first' => '$coin'),
                'created_date' => array('$first' => '$created_date'),
                'price' => array('$first' => '$price'),
            ),
            ),
            array('$sort' => array('price' => -1)),
            array('$limit' => 20),
        );

        $allow = array('allowDiskUse' => true);
        $responseArr = $db->market_depth->aggregate($pipeline, $allow);

        $fullarray = array();
        foreach ($responseArr as $valueArr) {
            $returArr = array();

            if (!empty($valueArr)) {
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $returArr['_id'] = $valueArr['_id'];
                $returArr['price'] = $valueArr['price'];
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['type'] = $valueArr['type'];
                $returArr['coin'] = $valueArr['coin'];
                $returArr['created_date'] = $formated_date_time;

                $priceee = $valueArr['price'];
            }

            $fullarray[] = $returArr;
        }

        $sort = array();
        foreach ($fullarray as $k => $v) {
            $sort['price'][$k] = $v['price'];
        }
        if(isset($sort['price'])){
         array_multisort($sort['price'], SORT_ASC, $fullarray);   
        }
       
        $data['market_value'] = $market_value;
        $data['fullarray'] = $fullarray;

        return $data;
    }

    //end get_market_sell_depth

    //get_market_buy_depth_chart
    public function get_market_buy_depth_chart($market_value = '')
    {
        $global_symbol = $this->session->userdata('global_symbol');

        if (isset($_GET['market_value']) && $_GET['market_value'] != '' && $market_value == '') {
            $market_value = $_GET['market_value'];
        } elseif ($market_value != '') {
            $market_value = $market_value;
        } else {
            //Get Market Prices
            $market_value = $this->get_market_value($global_symbol);
        }

        ///////////////////////////////////////
        $db = $this->mongo_db->customQuery();
        $priceAsk = (float) $market_value;

        $pipeline = array(
            array(
                '$project' => array(
                    'price' => 1,
                    'quantity' => 1,
                    'type' => 1,
                    'coin' => 1,
                    'created_date' => 1,
                ),
            ),
            array(
                '$match' => array(
                    'coin' => $global_symbol,
                    'type' => 'ask',
                    'price' => array('$gte' => $priceAsk),
                ),
            ),
            array('$sort' => array('created_date' => -1)),
            array('$group' => array(
                '_id' => array('price' => '$price'),
                'quantity' => array('$first' => '$quantity'),
                'type' => array('$first' => '$type'),
                'coin' => array('$first' => '$coin'),
                'created_date' => array('$first' => '$created_date'),
                'price' => array('$first' => '$price'),
            ),
            ),
            array('$sort' => array('price' => 1)),
            array('$limit' => 50),
        );

        $allow = array('allowDiskUse' => true);
        $responseArr = $db->market_depth->aggregate($pipeline, $allow);

        $fullarray = array();
        $big_quantity = 0;
        $depth_big_quantity = 0;
        foreach ($responseArr as $valueArr) {
            $returArr = array();

            if (!empty($valueArr)) {
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $returArr['_id'] = $valueArr['_id'];
                $returArr['price'] = $valueArr['price'];
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['type'] = $valueArr['type'];
                $returArr['coin'] = $valueArr['coin'];
                $returArr['created_date'] = $formated_date_time;

                ///////////////////////////////////////////////////////
                $depth_price = $valueArr['price'];
                $this->mongo_db->where(array('type' => 'ask', 'coin' => $global_symbol, 'price' => $depth_price));
                $this->mongo_db->limit(1);
                $this->mongo_db->order_by(array('created_date' => -1));
                $depth_responseArr = $this->mongo_db->get('market_depth');

                $depth_buy_quantity = 0;
                foreach ($depth_responseArr as $depth_valueArr) {
                    if (!empty($depth_valueArr)) {
                        $depth_buy_quantity += $depth_valueArr['quantity'];
                    }
                }
                if ($depth_buy_quantity > $depth_big_quantity) {
                    $depth_big_quantity = $depth_buy_quantity;
                }

                $returArr['depth_buy_quantity'] = $depth_buy_quantity;
                ///////////////////////////////////////////////////////

                ///////////////////////////////////////////////////////
                $depth_price = $valueArr['price'];
                $this->mongo_db->where(array('type' => 'bid', 'coin' => $global_symbol, 'price' => $depth_price));
                $depth_responseArr = $this->mongo_db->get('market_depth');

                $depth_sell_quantity = 0;
                foreach ($depth_responseArr as $depth_valueArr) {
                    if (!empty($depth_valueArr)) {
                        $depth_sell_quantity += $depth_valueArr['quantity'];
                    }
                }
                if ($depth_sell_quantity > $depth_big_quantity) {
                    $depth_big_quantity = $depth_sell_quantity;
                }

                $returArr['depth_sell_quantity'] = $depth_sell_quantity;
                ///////////////////////////////////////////////////////

                $priceee = $valueArr['price'];
                $this->mongo_db->where(array('maker' => 'true', 'coin' => $global_symbol, 'price' => $priceee));
                $responseArr2222 = $this->mongo_db->get('market_trades');

                //////////////
                $buy_quantity = 0;
                foreach ($responseArr2222 as $valueArr222) {
                    if (!empty($valueArr222)) {
                        $buy_quantity += $valueArr222['quantity'];
                    }
                }

                if ($buy_quantity > $big_quantity) {
                    $big_quantity = $buy_quantity;
                }

                $returArr['buy_quantity'] = $buy_quantity;

                $priceee = $valueArr['price'];
                $this->mongo_db->where(array('maker' => 'false', 'coin' => $global_symbol, 'price' => $priceee));
                $responseArr2222 = $this->mongo_db->get('market_trades');

                //////////////
                $sell_quantity = 0;
                foreach ($responseArr2222 as $valueArr222) {
                    if (!empty($valueArr222)) {
                        $sell_quantity += $valueArr222['quantity'];
                    }
                }

                if ($sell_quantity > $big_quantity) {
                    $big_quantity = $sell_quantity;
                }

                $returArr['sell_quantity'] = $sell_quantity;
                ////////////
            }

            $fullarray[] = $returArr;
        }

        $sort = array();
        foreach ($fullarray as $k => $v) {
            $sort['price'][$k] = $v['price'];
        }
        array_multisort($sort['price'], SORT_DESC, $fullarray);

        $data['market_value'] = $market_value;
        $data['fullarray'] = $fullarray;
        $data['buy_big_quantity'] = $big_quantity;
        $data['depth_buy_big_quantity'] = $depth_big_quantity;

        return $data;
    }

    //end get_market_buy_depth_chart

    //get_market_sell_depth_chart
    public function get_market_sell_depth_chart($market_value = '')
    {
        $global_symbol = $this->session->userdata('global_symbol');

        if (isset($_GET['market_value']) && $_GET['market_value'] != '' && $market_value == '') {
            $market_value = $_GET['market_value'];
        } elseif ($market_value != '') {
            $market_value = $market_value;
        } else {
            //Get Market Prices
            $market_value = $this->get_market_value($global_symbol);
        }

        ///////////////////////////////////////
        $db = $this->mongo_db->customQuery();
        $priceAsk = (float) $market_value;

        $pipeline = array(
            array(
                '$project' => array(
                    'price' => 1,
                    'quantity' => 1,
                    'type' => 1,
                    'coin' => 1,
                    'created_date' => 1,
                ),
            ),
            array(
                '$match' => array(
                    'coin' => $global_symbol,
                    'type' => 'bid',
                    'price' => array('$lte' => $priceAsk),
                ),
            ),
            array('$sort' => array('created_date' => -1)),
            array('$group' => array(
                '_id' => array('price' => '$price'),
                'quantity' => array('$first' => '$quantity'),
                'type' => array('$first' => '$type'),
                'coin' => array('$first' => '$coin'),
                'created_date' => array('$first' => '$created_date'),
                'price' => array('$first' => '$price'),
            ),
            ),
            array('$sort' => array('price' => -1)),
            array('$limit' => 50),
        );

        $allow = array('allowDiskUse' => true);
        $responseArr = $db->market_depth->aggregate($pipeline, $allow);

        $fullarray = array();
        $big_quantity = 0;
        $depth_big_quantity = 0;
        foreach ($responseArr as $valueArr) {
            $returArr = array();

            if (!empty($valueArr)) {
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $returArr['_id'] = $valueArr['_id'];
                $returArr['price'] = $valueArr['price'];
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['type'] = $valueArr['type'];
                $returArr['coin'] = $valueArr['coin'];
                $returArr['created_date'] = $formated_date_time;

                ///////////////////////////////////////////////////////
                $depth_price = $valueArr['price'];
                $this->mongo_db->where(array('type' => 'ask', 'coin' => $global_symbol, 'price' => $depth_price));
                $depth_responseArr = $this->mongo_db->get('market_depth');

                $depth_buy_quantity = 0;
                foreach ($depth_responseArr as $depth_valueArr) {
                    if (!empty($depth_valueArr)) {
                        $depth_buy_quantity += $depth_valueArr['quantity'];
                    }
                }
                if ($depth_buy_quantity > $depth_big_quantity) {
                    $depth_big_quantity = $depth_buy_quantity;
                }

                $returArr['depth_buy_quantity'] = $depth_buy_quantity;
                ///////////////////////////////////////////////////////

                ///////////////////////////////////////////////////////
                $depth_price = $valueArr['price'];
                $this->mongo_db->where(array('type' => 'bid', 'coin' => $global_symbol, 'price' => $depth_price));
                $depth_responseArr = $this->mongo_db->get('market_depth');

                $depth_sell_quantity = 0;
                foreach ($depth_responseArr as $depth_valueArr) {
                    if (!empty($depth_valueArr)) {
                        $depth_sell_quantity += $depth_valueArr['quantity'];
                    }
                }
                if ($depth_sell_quantity > $depth_big_quantity) {
                    $depth_big_quantity = $depth_sell_quantity;
                }

                $returArr['depth_sell_quantity'] = $depth_sell_quantity;
                ///////////////////////////////////////////////////////

                $priceee = $valueArr['price'];
                $this->mongo_db->where(array('maker' => 'true', 'coin' => $global_symbol, 'price' => $priceee));
                $responseArr2222 = $this->mongo_db->get('market_trades');

                //////////////
                $buy_quantity = 0;
                foreach ($responseArr2222 as $valueArr222) {
                    if (!empty($valueArr222)) {
                        $buy_quantity += $valueArr222['quantity'];
                    }
                }

                if ($buy_quantity > $big_quantity) {
                    $big_quantity = $buy_quantity;
                }

                $returArr['buy_quantity'] = $buy_quantity;

                $priceee = $valueArr['price'];
                $this->mongo_db->where(array('maker' => 'false', 'coin' => $global_symbol, 'price' => $priceee));
                $responseArr2222 = $this->mongo_db->get('market_trades');

                //////////////
                $sell_quantity = 0;
                foreach ($responseArr2222 as $valueArr222) {
                    if (!empty($valueArr222)) {
                        $sell_quantity += $valueArr222['quantity'];
                    }
                }

                if ($sell_quantity > $big_quantity) {
                    $big_quantity = $sell_quantity;
                }

                $returArr['sell_quantity'] = $sell_quantity;
                ////////////
            }

            $fullarray[] = $returArr;
        }

        $sort = array();
        foreach ($fullarray as $k => $v) {
            $sort['price'][$k] = $v['price'];
        }
        array_multisort($sort['price'], SORT_DESC, $fullarray);

        $data['market_value'] = $market_value;
        $data['fullarray'] = $fullarray;
        $data['sell_big_quantity'] = $big_quantity;
        $data['depth_sell_big_quantity'] = $depth_big_quantity;

        return $data;
    }

    //end get_market_sell_depth_chart

    //get_market_history
    public function get_market_history()
    {
        $global_symbol = $this->session->userdata('global_symbol');

        $this->mongo_db->where(array('coin' => $global_symbol));

        $this->mongo_db->limit(20);
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('market_trades');

        $fullarray = array();
        foreach ($responseArr as $valueArr) {
            $returArr = array();

            if (!empty($valueArr)) {
                //$created_date = date('Y-m-d G:i:s', $valueArr['created_date']);
                $datetime = $valueArr['created_date']->toDateTime();
                //$datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $returArr['_id'] = $valueArr['_id'];
                $returArr['price'] = $valueArr['price'];
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['maker'] = $valueArr['maker'];
                $returArr['coin'] = $valueArr['coin'];
                $returArr['counter'] = $valueArr['counter'];
                $returArr['created_date'] = $formated_date_time;
            }

            $fullarray[] = $returArr;
        }

        return $fullarray;
    }

    //end get_market_history

    //add_zone
    public function add_zone($data)
    {
        extract($data);

        $created_date = date('Y-m-d G:i:s');
        $admin_id = $this->session->userdata('admin_id');

        $start_date_arr = (explode('-', $start_date));
        $full_start_date = '';
        if (count($start_date_arr) > 0) {
            $full_start_date = $start_date_arr[0].' '.$start_date_arr[1];
        }
        $str_date = $this->make_universak_date_time($full_start_date);

        $end_date_arr = (explode('-', $end_date));
        $full_end_date = '';
        if (count($end_date_arr) > 0) {
            $full_end_date = $end_date_arr[0].' '.$end_date_arr[1];
        }
        $end_date = $this->make_universak_date_time($full_end_date);

        $ins_data = array(
            'start_value' => num((float) $start_value),
            'end_value' => num((float) $end_value),
            'type' => $type,
            'admin_id' => $admin_id,
            'start_date' => $str_date,
            'end_date' => $end_date,
            'coin' => $coin,
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
        );

        //Insert data in mongoTable
        $this->mongo_db->insert('chart_target_zones', $ins_data);

        return true;
    }

    //end add_zone

    public function make_universak_date_time($date)
    {
        $orig_date = new DateTime($date);
        $orig_date->format('Y-m-d H:i:s');

        $orig_date->sub(new DateInterval('PT5H00M'));
        $orig_date->format('Y-m-d H:i:s');
        $orig_date = $orig_date->getTimestamp();

        return $str_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
    }

    //edit_zone
    public function edit_zone($data)
    {
        extract($data);

        $created_date = date('Y-m-d G:i:s');
        $admin_id = $this->session->userdata('admin_id');

        $start_date_arr = (explode('-', $start_date));
        $full_start_date = '';
        if (count($start_date_arr) > 0) {
            $full_start_date = $start_date_arr[0].' '.$start_date_arr[1];
        }
        $str_date = $this->make_universak_date_time($full_start_date);

        $end_date_arr = (explode('-', $end_date));
        $full_end_date = '';
        if (count($end_date_arr) > 0) {
            $full_end_date = $end_date_arr[0].' '.$end_date_arr[1];
        }
        $end_date = $this->make_universak_date_time($full_end_date);

        $upd_data = array(
            'start_value' => num((float) $start_value),
            'end_value' => num((float) $end_value),
            'type' => $type,
            'admin_id' => $admin_id,
            'start_date' => $str_date,
            'end_date' => $end_date,
            'coin' => $coin,
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('chart_target_zones');

        return true;
    }

    //end edit_zone

    //delete_zone
    public function delete_zone($id)
    {
        $this->mongo_db->where(array('_id' => $id));

        //Delete data in mongoTable
        $this->mongo_db->delete('chart_target_zones');

        return true;
    }

    //end delete_zone

    //get_chart_target_zones
    public function get_chart_target_zones()
    {
        $admin_id = $this->session->userdata('admin_id');

        $this->mongo_db->where(array('admin_id' => $admin_id));
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('chart_target_zones');

        $fullarray = array();
        foreach ($responseArr as $valueArr) {
            if (!empty($valueArr)) {
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $returArr['_id'] = $valueArr['_id'];
                $returArr['start_value'] = num($valueArr['start_value']);
                $returArr['end_value'] = num($valueArr['end_value']);
                $returArr['type'] = $valueArr['type'];
                $returArr['coin'] = $valueArr['coin'];
                $returArr['created_date'] = $formated_date_time;

                if ($valueArr['start_date'] != '') {
                    $returArr['start_date'] = $this->change_time_stamp_to_human_readible($valueArr['start_date']);
                } else {
                    $returArr['start_date'] = '';
                }

                if ($valueArr['end_date'] != '') {
                    $returArr['end_date'] = $this->change_time_stamp_to_human_readible($valueArr['end_date']);
                } else {
                    $returArr['end_date'] = '';
                }
            }

            $fullarray[] = $returArr;
        }

        return $fullarray;
    }

    //end get_chart_target_zones

    public function change_time_stamp_to_human_readible($send_date)
    {
        if ($send_date != 0) {
            $datetime = $send_date->toDateTime();
            $created_date = $datetime->format(DATE_RSS);

            $datetime = new DateTime($created_date);
            $datetime->format('Y-m-d g:i:s A');
            $new_timezone = new DateTimeZone('Asia/Karachi');
            $datetime->setTimezone($new_timezone);

            return $formated_date_time = $datetime->format('Y-m-d g:i:s A');
        } else {
            return '';
        }
    }

    //get_zone
    public function get_zone($id)
    {
        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('chart_target_zones');

        foreach ($responseArr as $valueArr) {
            $returArr = array();
            if (!empty($valueArr)) {
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $start_value = num($valueArr['start_value']);
                $end_value = num($valueArr['end_value']);

                $returArr['_id'] = $valueArr['_id'];
                $returArr['start_value'] = num($start_value);
                $returArr['end_value'] = num($end_value);
                $returArr['type'] = $valueArr['type'];
                $returArr['coin'] = $valueArr['coin'];
                $returArr['start_date'] = $this->change_time_stamp_to_specific_format($valueArr['start_date']);

                $returArr['end_date'] = $this->change_time_stamp_to_specific_format($valueArr['end_date']);
                $returArr['created_date'] = $formated_date_time;
            }
        }

        return $returArr;
    }

    //end get_zone

    public function change_time_stamp_to_specific_format($send_date)
    {
        if ($send_date != 0) {
            $datetime = $send_date->toDateTime();
            $created_date = $datetime->format(DATE_RSS);

            $datetime = new DateTime($created_date);
            $datetime->format('Y-m-d g:i:s A');
            $new_timezone = new DateTimeZone('Asia/Karachi');
            $datetime->setTimezone($new_timezone);

            return $formated_date_time = $datetime->format('d F Y - g:i A');
        } else {
            return '';
        }
    }

    //get_zone_values
    public function get_zone_values($market_value)
    {
        $global_symbol = $this->session->userdata('global_symbol');

        $priceAsk = num((float) $market_value);
        $db = $this->mongo_db->customQuery();

        $params = array(
            'start_value' => array('$gte' => $priceAsk),
            'end_value' => array('$lte' => $priceAsk),
            'coin' => $global_symbol,
        );

        $res = $db->chart_target_zones->find($params);

        foreach ($res as $valueArr) {
            if (!empty($valueArr)) {
                $start_value = num($valueArr['start_value']);
                $end_value = num($valueArr['end_value']);

                $zone_id = $valueArr['_id'];
                $zone_start_value = (float) $start_value;
                $zone_end_value = (float) $end_value;
                $zone_type = $valueArr['type'];
            }
        }

        if ($zone_type == 'sell') {
            $zone_type2 = 'bid';
        } else {
            $zone_type2 = 'ask';
        }

        $pipeline = array(
            array(
                '$project' => array(
                    'price' => 1,
                    'quantity' => 1,
                    'type' => 1,
                    'coin' => 1,
                    'created_date' => 1,
                ),
            ),
            array(
                '$match' => array(
                    'coin' => $global_symbol,
                    'type' => $zone_type2,
                    'price' => array('$lte' => $zone_start_value, '$gte' => $zone_end_value),
                ),
            ),
            array('$sort' => array('created_date' => -1)),
            array('$group' => array(
                '_id' => array('price' => '$price'),
                'quantity' => array('$first' => '$quantity'),
                'type' => array('$first' => '$type'),
                'coin' => array('$first' => '$coin'),
                'created_date' => array('$first' => '$created_date'),
                'price' => array('$first' => '$price'),
            ),
            ),
            array('$sort' => array('price' => 1)),
        );

        $allow = array('allowDiskUse' => true);
        $responseArr = $db->market_depth->aggregate($pipeline, $allow);

        $fullarray = array();
        $buy_quantity = 0;
        $sell_quantity = 0;
        foreach ($responseArr as $valueArr) {
            if (!empty($valueArr)) {
                $priceee = num($valueArr['price']);

                $created_datetime = date('Y-m-d G:i:s');
                $orig_date = new DateTime($created_datetime);
                $orig_date = $orig_date->getTimestamp();
                $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

                $created_datetime22 = date('Y-m-d G:i:s', strtotime('-1 hour'));
                $orig_date22 = new DateTime($created_datetime22);
                $orig_date22 = $orig_date22->getTimestamp();
                $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);

                $db = $this->mongo_db->customQuery();
                $params = array(
                    'created_date' => array('$lte' => $start_date, '$gte' => $end_date),
                    'maker' => 'true',
                    'coin' => $global_symbol,
                    'price' => $priceee,
                );

                $responseArr2222 = $db->market_trades->find($params);

                //////////////
                foreach ($responseArr2222 as $valueArr222) {
                    if (!empty($valueArr222)) {
                        $buy_quantity += $valueArr222['quantity'];
                    }
                }

                $priceee22 = num($valueArr['price']);

                $created_datetime = date('Y-m-d G:i:s');
                $orig_date = new DateTime($created_datetime);
                $orig_date = $orig_date->getTimestamp();
                $start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

                $created_datetime22 = date('Y-m-d G:i:s', strtotime('-1 hour'));
                $orig_date22 = new DateTime($created_datetime22);
                $orig_date22 = $orig_date22->getTimestamp();
                $end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);

                $db = $this->mongo_db->customQuery();
                $params = array(
                    'created_date' => array('$lte' => $start_date, '$gte' => $end_date),
                    'maker' => 'false',
                    'coin' => $global_symbol,
                    'price' => $priceee22,
                );

                $responseArr3333 = $db->market_trades->find($params);

                /////////////
                foreach ($responseArr3333 as $valueArr3333) {
                    if (!empty($valueArr3333)) {
                        $sell_quantity += $valueArr3333['quantity'];
                    }
                }
            }
        }
        ////////////////////////////

        $total_quantity = $buy_quantity + $sell_quantity;

        $buy_percentage = round($buy_quantity * 100 / $total_quantity);
        $sell_percentage = round($sell_quantity * 100 / $total_quantity);

        $restttt['buy_quantity'] = $buy_quantity;
        $restttt['sell_quantity'] = $sell_quantity;
        $restttt['buy_percentage'] = $buy_percentage;
        $restttt['sell_percentage'] = $sell_percentage;
        $restttt['zone_id'] = $zone_id;

        return $restttt;
    }

    //end get_zone_values

    //add_order
    public function add_order($data)
    {
        extract($data);

        $created_date = date('Y-m-d G:i:s');
        $admin_id = $this->session->userdata('admin_id');
        $global_symbol = $this->session->userdata('global_symbol');

        $buy_order_arr = $this->get_buy_order($buy_order_id);
        $application_mode = $buy_order_arr['application_mode'];

        $is_submitted = 'no';
        $ins_data = array(
            'symbol' => $coin,
            'purchased_price' => $purchased_price,
            'quantity' => $quantity,
            'profit_type' => $profit_type,
            'order_type' => $order_type,
            'admin_id' => $admin_id,
            'buy_order_check' => $buy_order_check,
            'buy_order_id' => $buy_order_id,
            'buy_order_binance_id' => $buy_order_binance_id,
            'stop_loss' => $stop_loss,
            'loss_percentage' => $loss_percentage,
            'application_mode' => $application_mode,
            'trigger_type' => 'no',
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
            'modified_date' => $this->mongo_db->converToMongodttime($created_date),
        );

        if ($profit_type == 'percentage') {
            $sell_price = $purchased_price * $sell_profit_percent;
            $sell_price = $sell_price / 100;
            $sell_price = $sell_price + $purchased_price;
            $sell_price = number_format($sell_price, 8, '.', '');

            $ins_data['sell_profit_percent'] = $sell_profit_percent;
            $ins_data['sell_price'] = $sell_price;
        } else {
            $sell_price = $sell_profit_price;

            $ins_data['sell_profit_price'] = $sell_profit_price;
            $ins_data['sell_price'] = $sell_price;
        }

        if ($trail_check != '') {
            $ins_data['trail_check'] = 'yes';
            $ins_data['trail_interval'] = $trail_interval;
            $ins_data['sell_trail_price'] = $sell_price;
            $ins_data['status'] = 'new';
        } else {
            $ins_data['trail_check'] = 'no';
            $ins_data['trail_interval'] = '0';
            $ins_data['sell_trail_price'] = '0';
            $ins_data['status'] = 'new';
        }

        //Insert data in mongoTable
        $order_id = $this->mongo_db->insert('orders', $ins_data);

        if ($buy_order_check == 'yes') {
            //Update Buy Order
            $upd_data = array(
                'is_sell_order' => 'yes',
                'sell_order_id' => $order_id,
            );

            $this->mongo_db->where(array('_id' => $buy_order_id));
            $this->mongo_db->set($upd_data);

            //Update data in mongoTable
            $this->mongo_db->update('buy_orders');
        }

        //////////////////////////////////////////////////////////////////////////////
        ////////////////////////////// Order History Log /////////////////////////////
        $log_msg = 'Sell Order was Created';
        $this->insert_order_history_log($buy_order_id, $log_msg, 'sell_created', $admin_id);
        ////////////////////////////// End Order History Log /////////////////////////
        //////////////////////////////////////////////////////////////////////////////

        return $order_id;
    }

    //end add_order

    //edit_order
    public function edit_order($data)
    {
        extract($data);

        $order_arr = $this->get_order($id);

        if ($order_arr['status'] == 'new' || $order_arr['status'] == 'error') {
            if ($lth_status == 'LTH') {
                $this->mongo_db->where(array('_id' => $buy_order_id));
                $this->mongo_db->set(array('lth_profit' => $sell_profit_percent));
                $this->mongo_db->update('buy_orders');
            }

            $created_date = date('Y-m-d G:i:s');
            $admin_id = $this->session->userdata('admin_id');

            $is_submitted = 'no';
            $upd_data = array(
                'symbol' => $coin,
                'purchased_price' => $purchased_price,
                'quantity' => $quantity,
                'profit_type' => $profit_type,
                'order_type' => $order_type,
                'admin_id' => $admin_id,
                'stop_loss' => $stop_loss,
                'loss_percentage' => $loss_percentage,
                'trigger_type' => 'no',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            );

            if ($profit_type == 'percentage') {
                if ($status == 'LTH') {
                    $upd_data['lth_profit'] = $sell_profit_percent;
                }
                $sell_price = $purchased_price * $sell_profit_percent;
                $sell_price = $sell_price / 100;
                $sell_price = $sell_price + $purchased_price;
                $sell_price = number_format($sell_price, 8, '.', '');

                $upd_data['sell_profit_percent'] = $sell_profit_percent;
                $upd_data['sell_price'] = $sell_price;
            } else {
                $sell_price = $sell_profit_price;

                $upd_data['sell_profit_percent'] = $sell_profit_percent;
                $upd_data['sell_price'] = $sell_price;
            }

            if ($trail_check != '') {
                $upd_data['trail_check'] = 'yes';
                $upd_data['trail_interval'] = $trail_interval;
                $upd_data['sell_trail_price'] = $sell_price;
                $upd_data['status'] = 'new';
            } else {
                $upd_data['trail_check'] = 'no';
                $upd_data['trail_interval'] = '0';
                $upd_data['sell_trail_price'] = '0';
                $upd_data['status'] = 'new';
            }

            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_data);

            //Update data in mongoTable
            $this->mongo_db->update('orders');

            ////////////////////////////// Update Auto Sell////////////////////////////
            if ($buy_order_id != '') {
                //Get Temp Sell Order Record
                $temp_sell_arr = $this->get_temp_sell_data($buy_order_id);

                if (count($temp_sell_arr) > 0) {
                    $temp_sell_id = $temp_sell_arr['_id'];

                    $upd_temp_data = array(
                        'profit_type' => $profit_type,
                        'profit_percent' => $sell_profit_percent,
                        'profit_price' => $sell_price,
                        'order_type' => $order_type,
                        'trail_check' => $trail_check,
                        'trail_interval' => $trail_interval,
                        'stop_loss' => $stop_loss,
                        'loss_percentage' => $loss_percentage,
                    );

                    $this->mongo_db->where(array('_id' => $temp_sell_id));
                    $this->mongo_db->set($upd_temp_data);

                    //Update data in mongoTable
                    $this->mongo_db->update('temp_sell_orders');
                } //End if temp data found
            }
            //////////////////////////////// End Update Auto Sell//////////////////////

            //////////////////////////////////////////////////////////////////////////////
            ////////////////////////////// Order History Log /////////////////////////////
            $htm = '<span style="color:orange;    font-size: 14px;"><b>Manually</b></span>';
            $log_msg = 'Sell Order was Updated '.$htm;
            $this->insert_order_history_log($buy_order_id, $log_msg, 'sell_updated', $admin_id);
            ////////////////////////////// End Order History Log /////////////////////////
            //////////////////////////////////////////////////////////////////////////////

            return true;
        } else {
            return false;
        } //End if Order in New
    }

    //end edit_order

    //update_trail_price
    public function update_trail_price($id, $sell_trail_price, $new_trial_price, $buy_order_id, $admin_id)
    {
        $upd_data = array(
            'sell_trail_price' => $new_trial_price,
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('orders');

        //////////////////////////////////////////////////////////////////////////////
        ////////////////////////////// Order History Log /////////////////////////////
        $log_msg = 'Sell Order Trail was Updated from <b>('.$sell_trail_price.')</b> to <b>('.$new_trial_price.')</b>';
        $this->insert_order_history_log($buy_order_id, $log_msg, 'sell_trail_updated', $admin_id);
        ////////////////////////////// End Order History Log /////////////////////////
        //////////////////////////////////////////////////////////////////////////////

        return true;
    }

    //end update_trail_price

    public function update_trail_price_bam($id, $sell_trail_price, $new_trial_price, $buy_order_id, $admin_id)
    {
        $upd_data = array(
            'sell_trail_price' => $new_trial_price,
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('orders_bam');

        //////////////////////////////////////////////////////////////////////////////
        ////////////////////////////// Order History Log /////////////////////////////
        $log_msg = 'Sell Order Trail was Updated from <b>('.$sell_trail_price.')</b> to <b>('.$new_trial_price.')</b>';
        $this->insert_order_history_log_bam($buy_order_id, $log_msg, 'sell_trail_updated', $admin_id);
        ////////////////////////////// End Order History Log /////////////////////////
        //////////////////////////////////////////////////////////////////////////////

        return true;
    }

    //end update_trail_price_bam

    //update_trail_buy_price
    public function update_trail_buy_price($id, $buy_trail_price, $new_trial_price, $admin_id)
    {
        $upd_data = array(
            'buy_trail_price' => $new_trial_price,
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('buy_orders');

        //////////////////////////////////////////////////////////////////////////////
        ////////////////////////////// Order History Log /////////////////////////////
        $log_msg = 'Buy Order Trail was Updated from <b>'.num($buy_trail_price).'</b> to <b>('.num($new_trial_price).')</b>';
        $this->insert_order_history_log($id, $log_msg, 'buy_trail_updated', $admin_id);
        ////////////////////////////// End Order History Log /////////////////////////
        //////////////////////////////////////////////////////////////////////////////

        return true;
    }

    //end update_trail_buy_price

    public function update_trail_buy_price_bam($id, $buy_trail_price, $new_trial_price, $admin_id)
    {
        $upd_data = array(
            'buy_trail_price' => $new_trial_price,
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('buy_orders_bam');

        //////////////////////////////////////////////////////////////////////////////
        ////////////////////////////// Order History Log /////////////////////////////
        $log_msg = 'Buy Order Trail was Updated from <b>'.num($buy_trail_price).'</b> to <b>('.num($new_trial_price).')</b>';
        $this->insert_order_history_log_bam($id, $log_msg, 'buy_trail_updated', $admin_id);
        ////////////////////////////// End Order History Log /////////////////////////
        //////////////////////////////////////////////////////////////////////////////

        return true;
    }

    //end update_trail_buy_price

    //update_hightest_price
    public function update_hightest_price($id, $hightest_price)
    {
        $upd_data = array(
            'hightest_price' => $hightest_price,
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('orders');

        return true;
    }

    //end update_hightest_price

    //delete_order_backup
    public function delete_order_backup($id, $order_id)
    {
        // $this->mongo_db->where(array('_id'=> $id));

        // //Delete data in mongoTable
        // $this->mongo_db->delete('orders');

        $admin_id = $this->session->userdata('admin_id');
        $global_symbol = $this->session->userdata('global_symbol');

        if ($order_id != '') {
            //Binance Cancel Order
            $order = $this->binance_api->cancel_order($global_symbol, $order_id);
        }

        $upd_data = array(
            'status' => 'canceled',
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('orders');

        //////////////////////////////////////////////////////////////////////////////
        ////////////////////////////// Order History Log /////////////////////////////
        $log_msg = 'Sell Order was Canceled';
        $this->insert_order_history_log($id, $log_msg, 'sell_canceled', $admin_id);
        ////////////////////////////// End Order History Log /////////////////////////
        //////////////////////////////////////////////////////////////////////////////

        return true;
    }

    //end delete_order_backup

    //delete_order     **** Test Order ****
    public function delete_order($id, $order_id)
    {
        $admin_id = $this->session->userdata('admin_id');
        $global_symbol = $this->session->userdata('global_symbol');

        $upd_data = array(
            'status' => 'canceled',
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('orders');

        //////////////////////////////////////////////////////////////////////////////
        ////////////////////////////// Order History Log /////////////////////////////
        $log_msg = 'Sell Order was Canceled';
        $this->insert_order_history_log($id, $log_msg, 'sell_canceled', $admin_id);
        ////////////////////////////// End Order History Log /////////////////////////
        //////////////////////////////////////////////////////////////////////////////

        return true;
    }

    //end delete_order

    //get_order
    public function get_order($id)
    {
        $timezone = $this->session->userdata('timezone');
        if (empty($timezone)) {
            $timezone = 'ASIA/KARACHI';
        }
        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('orders');
        foreach ($responseArr as $valueArr) {
            $returArr = array();
            if (!empty($valueArr)) {
                $returArr['_id'] = $valueArr['_id'];
                $returArr['symbol'] = $valueArr['symbol'];
                $returArr['binance_order_id'] = $valueArr['binance_order_id'];
                $returArr['purchased_price'] = $valueArr['purchased_price'];
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['profit_type'] = $valueArr['profit_type'];
                $returArr['sell_profit_percent'] = $valueArr['sell_profit_percent'];
                $returArr['sell_profit_price'] = $valueArr['sell_profit_price'];
                $returArr['sell_price'] = $valueArr['sell_price'];
                $returArr['market_value'] = $valueArr['market_value'];
                $returArr['order_type'] = $valueArr['order_type'];
                $returArr['status'] = $valueArr['status'];
                $returArr['admin_id'] = $valueArr['admin_id'];
                $returArr['created_date'] = $formated_date_time;
                $returArr['trail_check'] = $valueArr['trail_check'];
                $returArr['trail_interval'] = $valueArr['trail_interval'];
                $returArr['sell_trail_price'] = $valueArr['sell_trail_price'];
                $returArr['buy_order_check'] = $valueArr['buy_order_check'];
                $returArr['buy_order_id'] = $valueArr['buy_order_id'];
                $returArr['buy_order_binance_id'] = $valueArr['buy_order_binance_id'];
                $returArr['stop_loss'] = $valueArr['stop_loss'];
                $returArr['modified_date'] = $valueArr['modified_date'];
                $returArr['application_mode'] = $valueArr['application_mode'];
                $returArr['loss_percentage'] = $valueArr['loss_percentage'];
                $returArr['trigger_type'] = $valueArr['trigger_type'];
            }
        }

        return $returArr;
    }

    public function get_order_bam($id)
    {
        $timezone = $this->session->userdata('timezone');
        if (empty($timezone)) {
            $timezone = 'ASIA/KARACHI';
        }
        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('orders_bam');

        foreach ($responseArr as $valueArr) {
            $returArr = array();
            if (!empty($valueArr)) {
                $returArr['_id'] = $valueArr['_id'];
                $returArr['symbol'] = $valueArr['symbol'];
                $returArr['binance_order_id'] = $valueArr['binance_order_id'];
                $returArr['purchased_price'] = $valueArr['purchased_price'];
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['profit_type'] = $valueArr['profit_type'];
                $returArr['sell_profit_percent'] = $valueArr['sell_profit_percent'];
                $returArr['sell_profit_price'] = $valueArr['sell_profit_price'];
                $returArr['sell_price'] = $valueArr['sell_price'];
                $returArr['market_value'] = $valueArr['market_value'];
                $returArr['order_type'] = $valueArr['order_type'];
                $returArr['status'] = $valueArr['status'];
                $returArr['admin_id'] = $valueArr['admin_id'];
                $returArr['created_date'] = date('Y-m-d H:i:s');
                $returArr['trail_check'] = $valueArr['trail_check'];
                $returArr['trail_interval'] = $valueArr['trail_interval'];
                $returArr['sell_trail_price'] = $valueArr['sell_trail_price'];
                $returArr['buy_order_check'] = $valueArr['buy_order_check'];
                $returArr['buy_order_id'] = $valueArr['buy_order_id'];
                $returArr['buy_order_binance_id'] = $valueArr['buy_order_binance_id'];
                $returArr['stop_loss'] = $valueArr['stop_loss'];
                $returArr['modified_date'] = $valueArr['modified_date'];
                $returArr['application_mode'] = $valueArr['application_mode'];
                $returArr['loss_percentage'] = $valueArr['loss_percentage'];
                $returArr['trigger_type'] = $valueArr['trigger_type'];
            }
        }

        return $returArr;
    }

    //end get_order

    //get_orders
    public function get_orders()
    {
        $admin_id = $this->session->userdata('admin_id');

        //Check Filter Data
        $session_post_data = $this->session->userdata('filter-data');

        $search_array = array('admin_id' => $admin_id);
        if ($session_post_data['filter_coin'] != '') {
            $symbol = $session_post_data['filter_coin'];
            $search_array['symbol'] = $symbol;
        }
        if ($session_post_data['filter_type'] != '') {
            $order_type = $session_post_data['filter_type'];
            $search_array['order_type'] = $order_type;
        }

        $this->mongo_db->where($search_array);
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('orders');

        $fullarray = array();
        foreach ($responseArr as $valueArr) {
            $returArr = array();

            if (!empty($valueArr)) {
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $returArr['_id'] = $valueArr['_id'];
                $returArr['symbol'] = $valueArr['symbol'];
                $returArr['binance_order_id'] = $valueArr['binance_order_id'];
                $returArr['purchased_price'] = $valueArr['purchased_price'];
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['profit_type'] = $valueArr['profit_type'];
                $returArr['sell_profit_percent'] = $valueArr['sell_profit_percent'];
                $returArr['sell_profit_price'] = $valueArr['sell_profit_price'];
                $returArr['sell_price'] = $valueArr['sell_price'];
                $returArr['market_value'] = $valueArr['market_value'];
                $returArr['trail_check'] = $valueArr['trail_check'];
                $returArr['trail_interval'] = $valueArr['trail_interval'];
                $returArr['sell_trail_price'] = $valueArr['sell_trail_price'];
                $returArr['order_type'] = $valueArr['order_type'];
                $returArr['stop_loss'] = $valueArr['stop_loss'];
                $returArr['loss_percentage'] = $valueArr['loss_percentage'];
                $returArr['status'] = $valueArr['status'];
                $returArr['admin_id'] = $valueArr['admin_id'];
                $returArr['buy_order_id'] = $valueArr['buy_order_id'];
                $returArr['buy_order_binance_id'] = $valueArr['buy_order_binance_id'];
                $returArr['application_mode'] = $valueArr['application_mode'];
                $returArr['created_date'] = $formated_date_time;
            }

            $fullarray[] = $returArr;
        }

        return $fullarray;
    }

    //end get_orders

    //get_sell_active_orders
    public function get_sell_active_orders()
    {
        $admin_id = $this->session->userdata('admin_id');

        //Check Filter Data
        $session_post_data = $this->session->userdata('filter-data');

        $search_array = array('admin_id' => $admin_id, 'status' => 'new');

        if ($session_post_data['filter_coin'] != '') {
            $symbol = $session_post_data['filter_coin'];
            $search_array['symbol'] = $symbol;
        }
        if ($session_post_data['filter_type'] != '') {
            $order_type = $session_post_data['filter_type'];
            $search_array['order_type'] = $order_type;
        }
        if ($session_post_data['start_date'] != '' && $session_post_data['end_date'] != '') {
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

        $this->mongo_db->where($search_array);
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('orders');

        $fullarray = array();
        foreach ($responseArr as $valueArr) {
            $returArr = array();

            if (!empty($valueArr)) {
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $returArr['_id'] = $valueArr['_id'];
                $returArr['symbol'] = $valueArr['symbol'];
                $returArr['binance_order_id'] = $valueArr['binance_order_id'];
                $returArr['purchased_price'] = $valueArr['purchased_price'];
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['profit_type'] = $valueArr['profit_type'];
                $returArr['sell_profit_percent'] = $valueArr['sell_profit_percent'];
                $returArr['sell_profit_price'] = $valueArr['sell_profit_price'];
                $returArr['sell_price'] = number_format($valueArr['sell_price'], 8, '.', '');
                $returArr['market_value'] = $valueArr['market_value'];
                $returArr['trail_check'] = $valueArr['trail_check'];
                $returArr['trail_interval'] = $valueArr['trail_interval'];
                $returArr['sell_trail_price'] = $valueArr['sell_trail_price'];
                $returArr['stop_loss'] = $valueArr['stop_loss'];
                $returArr['loss_percentage'] = $valueArr['loss_percentage'];
                $returArr['status'] = $valueArr['status'];
                $returArr['admin_id'] = $valueArr['admin_id'];
                $returArr['buy_order_id'] = $valueArr['buy_order_id'];
                $returArr['buy_order_binance_id'] = $valueArr['buy_order_binance_id'];
                $returArr['application_mode'] = $valueArr['application_mode'];
                $returArr['created_date'] = $formated_date_time;
            }

            $fullarray[] = $returArr;
        }

        return $fullarray;
    }

    //end get_sell_active_orders

    //binance_buy_auto_limit_order_live
    public function binance_buy_auto_limit_order_live($id, $quantity, $price, $symbol, $user_id)
    {
        // --- %%% call function %%%% ----
        $this->update_user_coin_balance($user_id, $symbol);
        $quantity = $this->remove_min_step_size_notational_error($symbol, $quantity);

        $balance = $this->binance_api->get_account_balance($symbol, $user_id);

        $log_msg = 'Avaliable Balance of <bold>'.$symbol.'</bold>  is :'.$balance;
        $this->insert_order_history_log($id, $log_msg, 'buy_error', $user_id);

        $min_notational_quantity = $this->is_min_quantity_notational_error($symbol, 'buy');
        // %%%%%%%%%%%%%%%% get buy order detail %%%%%%%%%%
        $buy_order_arr = $this->get_buy_order($id);

        if (($quantity < $min_notational_quantity) && ($balance < $min_notational_quantity)) {
            $log_msg = "Buy Order got Custom  Min notational <span style='color:red;    font-size: 14px;'><b>ERROR</b></span>";
            $this->insert_order_history_log($id, $log_msg, 'buy_error', $user_id);

            $log_msg = 'Order Quantity '.$quantity;
            $this->insert_order_history_log($id, $log_msg, 'buy_error', $user_id);

            $log_msg = 'Min Required Order Quantity '.$min_notational_quantity;
            $this->insert_order_history_log($id, $log_msg, 'buy_error', $user_id);

            $created_date = date('Y-m-d G:i:s');
            $upd_data22 = array(
                'status' => 'error',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            );
            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_data22);
            //Update data in mongoTable
            $this->mongo_db->update('buy_orders');

            $buy_parent_id = $buy_order_arr['buy_parent_id'];
            $admin_id = $buy_order_arr['admin_id'];
            if ($buy_parent_id) {
                $this->pause_parent_order($buy_parent_id, $admin_id);
            }

            return false;
        }

        $created_date = date('Y-m-d G:i:s');

        if ($buy_order_arr['status'] == 'new') {
            ///////////////////////Update Status Before Submitted to Binance///////////////
            $upd_status = array(
                'status' => 'submitted',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                'buy_date' => $this->mongo_db->converToMongodttime($created_date),
            );
            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_status);
            $this->mongo_db->update('buy_orders');
            ///////////////////////Update Status Before Submitted to Binance///////////////

            //Submit Limit Order to Binance
            $order = $this->binance_api->place_buy_limit_order($symbol, $quantity, $price, $user_id);
            $balance = $this->get_balance($symbol, $user_id);

            // --- %%% call function %%%% ----
            $this->update_user_coin_balance($user_id, $symbol);

            if ($order['orderId'] == '') {
                $order_arr = json_encode($order);
                $order_arr2 = json_decode($order_arr);

                $error_msg = $order_arr2->msg;

                //////////////////////////////////////////////////////////////////////////////
                ////////////////////////////// Order History Log /////////////////////////////
                $log_msg = 'Buy Order was got Error ('.$error_msg.')';
                $this->insert_order_history_log($id, $log_msg, 'buy_error', $user_id);
                ////////////////////////////// End Order History Log /////////////////////////
                //////////////////////////////////////////////////////////////////////////////

                /////////////Insert Binance Error Record/////////////////////////////////////
                $this->insert_binance_errors($id, $error_msg, 'buy', $user_id, $symbol);
                /////////////End Insert Binance Error Record/////////////////////////////////

                return array('error' => $error_msg);
            } else {
                $upd_data = array(
                    'market_value' => $price,
                    'status' => 'submitted',
                    'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                    'binance_order_id' => $order['orderId'],
                );

                $this->mongo_db->where(array('_id' => $id));
                $this->mongo_db->set($upd_data);

                //Update data in mongoTable
                $this->mongo_db->update('buy_orders');

                ////////////////////// Set Notification //////////////////
                $message = 'Buy Limit Order is <b>SUBMITTED</b>';
                $message2 = '<strong>'.$symbol."</strong> Limit Trade is <b style='color:#2ca634'>SUBMITTED</b> for Buy";
                $this->add_notification($id, 'buy', $message, $user_id);
                $this->add_notification_for_app($id, 'trading_alerts', 'medium', $message2, $user_id, $symbol);
                //////////////////////////////////////////////////////////

                //////////////////////////////////////////////////////////////////////////////
                ////////////////////////////// Order History Log /////////////////////////////
                $log_msg = 'Buy Limit Order was <b>SUBMITTED</b>';
                $this->insert_order_history_log($id, $log_msg, 'buy_submitted', $user_id);
                ////////////////////////////// End Order History Log /////////////////////////
                //////////////////////////////////////////////////////////////////////////////
            }
        } //End if Order is New

        return true;
    }

    //end binance_buy_auto_limit_order_live

    //binance_buy_auto_limit_order_test       ****** TEST ORDERS ********
    public function binance_buy_auto_limit_order_test($id, $quantity, $price, $symbol, $user_id)
    {
        $buy_order_arr = $this->get_buy_order($id);
        $created_date = date('Y-m-d G:i:s');
        if ($buy_order_arr['status'] == 'new') {
            //Submit Limit Order to Binance
            //$order = $this->binance_api->place_buy_limit_order($symbol,$quantity,$price,$user_id);
            //$balance = $this->get_balance($symbol,$user_id);

            $upd_data = array(
                'market_value' => $price,
                'status' => 'submitted',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                'buy_date' => $this->mongo_db->converToMongodttime($created_date),
                'binance_order_id' => '111000',
            );

            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_data);

            //Update data in mongoTable
            $this->mongo_db->update('buy_orders');

            ////////////////////// Set Notification //////////////////
            $message = 'Buy Limit Order is <b>SUBMITTED</b>';
            $this->add_notification($id, 'buy', $message, $user_id);
            $message2 = '<strong>'.$symbol."</strong> Limit Trade is <b style='color:#2ca634'>SUBMITTED</b> for Buy";
            $this->add_notification_for_app($id, 'trading_alerts', 'medium', $message2, $user_id, $symbol);
            //////////////////////////////////////////////////////////

            //////////////////////////////////////////////////////////////////////////////
            ////////////////////////////// Order History Log /////////////////////////////
            $log_msg = 'Buy Limit Order was <b>SUBMITTED</b>';
            $this->insert_order_history_log($id, $log_msg, 'buy_submitted', $user_id);
            ////////////////////////////// End Order History Log /////////////////////////
            //////////////////////////////////////////////////////////////////////////////
        } //End if Order is New

        return true;
    }

    //end binance_buy_auto_limit_order_test

    //binance_buy_auto_market_order_live
    public function binance_buy_auto_market_order_live($id, $quantity, $market_value, $symbol, $user_id)
    {
        // --- %%% call function %%%% ----
        $this->update_user_coin_balance($user_id, $symbol);
        $quantity = $this->remove_min_step_size_notational_error($symbol, $quantity);

        $balance = $this->binance_api->get_account_balance($symbol, $user_id);

        $log_msg = 'Avaliable Balance of <bold>'.$symbol.'</bold>  is :'.$balance;
        $this->insert_order_history_log($id, $log_msg, 'buy_error', $user_id);

        $min_notational_quantity = $this->is_min_quantity_notational_error($symbol, 'buy');
        $buy_order_arr = $this->get_buy_order($id);
        $buy_parent_id = $buy_order_arr['buy_parent_id'];

        if (($quantity < $min_notational_quantity) && ($balance < $min_notational_quantity)) {
            $log_msg = "Buy Order got Custom  Min notational <span style='color:red;    font-size: 14px;'><b>ERROR</b></span>";
            $this->insert_order_history_log($id, $log_msg, 'buy_error', $user_id);

            $log_msg = 'Order Quantity '.$quantity;
            $this->insert_order_history_log($id, $log_msg, 'buy_error', $user_id);

            $log_msg = 'Min Required Order Quantity '.$min_notational_quantity;
            $this->insert_order_history_log($id, $log_msg, 'buy_error', $user_id);

            $created_date = date('Y-m-d G:i:s');
            $upd_data22 = array(
                'status' => 'error',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            );
            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_data22);
            //Update data in mongoTable
            $this->mongo_db->update('buy_orders');

            $buy_parent_id = $buy_order_arr['buy_parent_id'];
            $admin_id = $buy_order_arr['admin_id'];
            if ($buy_parent_id) {
                $this->pause_parent_order($buy_parent_id, $admin_id);
            }

            return false;
        }

        $created_date = date('Y-m-d G:i:s');
        $api_key_check = $this->check_user_api_key_set($user_id);
        if ($buy_order_arr['status'] == 'new' && $buy_order_arr['status'] != 'canceled' && $api_key_check) {
            ///////////////////////Update Status Before Submitted to Binance///////////////
            $upd_status = array(
                'status' => 'submitted',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                'buy_date' => $this->mongo_db->converToMongodttime($created_date),
            );
            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_status);
            $this->mongo_db->update('buy_orders');
            ///////////////////////Update Status Before Submitted to Binance///////////////

            //Submit Market Order to Binance
            $order = $this->binance_api->place_buy_market_order($symbol, $quantity, $user_id);
            $balance = $this->get_balance($symbol, $user_id);

            // --- %%% call function %%%% ----
            $this->update_user_coin_balance($user_id, $symbol);

            if ($order['orderId'] == '') {
                $order_arr = json_encode($order);
                $order_arr2 = json_decode($order_arr);

                $error_msg = $order_arr2->msg;

                //////////////////////////////////////////////////////////////////////////////
                ////////////////////////////// Order History Log /////////////////////////////
                $log_msg = 'Buy Order was got Error ('.$error_msg.')';
                $this->insert_order_history_log($id, $log_msg, 'buy_error', $user_id);
                ////////////////////////////// End Order History Log /////////////////////////
                //////////////////////////////////////////////////////////////////////////////

                /////////////Insert Binance Error Record//////////////////////////////////////
                $this->insert_binance_errors($id, $error_msg, 'buy', $user_id, $symbol);
                /////////////End Insert Binance Error Record//////////////////////////////////
                $this->pause_parent_order($buy_parent_id, $user_id);

                return array('error' => $error_msg);
            } else {
                $upd_data = array(
                    'market_value' => (float) $market_value,
                    'status' => 'submitted',
                    'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                    'buy_date' => $this->mongo_db->converToMongodttime($created_date),
                    'binance_order_id' => $order['orderId'],
                );

                $this->mongo_db->where(array('_id' => $id));
                $this->mongo_db->set($upd_data);

                //Update data in mongoTable
                $this->mongo_db->update('buy_orders');

                ////////////////////// Set Notification //////////////////
                $message = 'Buy Market Order is <b>SUBMITTED</b>';
                $message2 = '<strong>'.$symbol."</strong> Market Trade is <b style='color:#2ca634'>SUBMITTED</b> for Buy";
                $this->add_notification($id, 'buy', $message, $user_id);
                $this->add_notification_for_app($id, 'trading_alerts', 'medium', $message2, $user_id, $symbol);
                //////////////////////////////////////////////////////////

                //////////////////////////////////////////////////////////////////////////////
                ////////////////////////////// Order History Log /////////////////////////////
                $log_msg = 'Buy Market Order was <b>SUBMITTED</b>';
                $this->insert_order_history_log($id, $log_msg, 'buy_submitted', $user_id);
                ////////////////////////////// End Order History Log /////////////////////////
                //////////////////////////////////////////////////////////////////////////////
            }
        } //End if Order is New

        return true;
    }

    //end binance_buy_auto_market_order_live

    public function check_user_api_key_set($user_id)
    {
        $this->mongo_db->where(array('_id' => $user_id));
        $get_arr = $this->mongo_db->get('users');
        $settings_arr = iterator_to_array($get_arr);
        $settings_arr = $settings_arr[0];

        if ($settings_arr['api_key'] != '' && $settings_arr['api_key'] != '') {
            $check_api_settings = true;
        } else {
            $check_api_settings = false;
        }

        return $check_api_settings;
    }

    //End of check_user_api_key_set

    //binance_buy_auto_market_order_test     ****** TEST ORDERS ********
    public function binance_buy_auto_market_order_test($id, $quantity, $market_value, $symbol, $user_id)
    {
        $buy_order_arr = $this->get_buy_order($id);
        $created_date = date('Y-m-d G:i:s');
        if ($buy_order_arr['status'] == 'new') {
            //Submit Market Order to Binance
            //$order = $this->binance_api->place_buy_market_order($symbol,$quantity,$user_id);
            //$balance = $this->get_balance($symbol,$user_id);

            $upd_data = array(
                'market_value' => $market_value,
                'status' => 'submitted',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                'buy_date' => $this->mongo_db->converToMongodttime($created_date),
                'binance_order_id' => '111000',
            );

            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_data);

            //Update data in mongoTable
            $this->mongo_db->update('buy_orders');

            ////////////////////// Set Notification //////////////////
            $message = 'Buy Market Order is <b>SUBMITTED</b>';
            $message2 = '<strong>'.$symbol."</strong> Market Trade is <b style='color:#2ca634'>SUBMITTED</b> for Buy";
            $this->add_notification($id, 'buy', $message, $user_id);
            $this->add_notification_for_app($id, 'trading_alerts', 'medium', $message2, $user_id, $symbol);
            //////////////////////////////////////////////////////////

            //////////////////////////////////////////////////////////////////////////////
            ////////////////////////////// Order History Log /////////////////////////////
            $log_msg = 'Buy Market Order was <b>SUBMITTED</b>';
            $this->insert_order_history_log($id, $log_msg, 'buy_submitted', $user_id);
            ////////////////////////////// End Order History Log /////////////////////////
            //////////////////////////////////////////////////////////////////////////////
        } //End if Order is New

        return true;
    }

    //end binance_buy_auto_market_order_test
    // this method was commented by Muhammad Sheraz on (31-august-2021) on behalf of Shehzad.
    // public function binance_buy_auto_market_order_test_bam($id, $quantity, $market_value, $symbol, $user_id)
    // {
    //     $buy_order_arr = $this->get_buy_order_bam($id);
    //     $created_date = date('Y-m-d G:i:s');
    //     if ($buy_order_arr['status'] == 'new') {
    //         //Submit Market Order to Binance
    //         //$order = $this->binance_api->place_buy_market_order($symbol,$quantity,$user_id);
    //         //$balance = $this->get_balance($symbol,$user_id);

    //         $upd_data = array(
    //             'market_value' => $market_value,
    //             'status' => 'submitted',
    //             'modified_date' => $this->mongo_db->converToMongodttime($created_date),
    //             'buy_date' => $this->mongo_db->converToMongodttime($created_date),
    //             'binance_order_id' => '111000',
    //         );

    //         $this->mongo_db->where(array('_id' => $id));
    //         $this->mongo_db->set($upd_data);

    //         //Update data in mongoTable
    //         $this->mongo_db->update('buy_orders_bam');

    //         ////////////////////// Set Notification //////////////////
    //         $message = 'Buy Market Order is <b>SUBMITTED</b>';
    //         $message2 = '<strong>'.$symbol."</strong> Market Trade is <b style='color:#2ca634'>SUBMITTED</b> for Buy";
    //         $this->add_notification($id, 'buy', $message, $user_id);
    //         $this->add_notification_for_app($id, 'trading_alerts', 'medium', $message2, $user_id, $symbol);
    //         //////////////////////////////////////////////////////////

    //         //////////////////////////////////////////////////////////////////////////////
    //         ////////////////////////////// Order History Log /////////////////////////////
    //         $log_msg = 'Buy Market Order was <b>SUBMITTED</b>';
    //         $this->insert_order_history_log_bam($id, $log_msg, 'buy_submitted', $user_id);
    //         ////////////////////////////// End Order History Log /////////////////////////
    //         //////////////////////////////////////////////////////////////////////////////
    //     } //End if Order is New

    //     return true;
    // }

    //end binance_buy_auto_market_order_test

    //binance_sell_auto_limit_order_live
    public function binance_sell_auto_limit_order_live($id, $quantity, $price, $symbol, $user_id, $buy_order_id)
    {
        // --- %%% call function %%%% ----
        $this->update_user_coin_balance($user_id, $symbol);
        //Check Order Quantity
        $balance = $this->binance_api->get_account_balance($symbol, $user_id);
        // if($quantity > $balance){
        //     $quantity = $balance;
        // }

        $log_msg = 'Avaliable Balance of <bold>'.$symbol.'</bold>  is :'.$balance;
        $this->insert_order_history_log($buy_order_id, $log_msg, 'buy_error', $user_id);

        $quantity = $this->remove_min_step_size_notational_error($symbol, $quantity);

        $min_notational_quantity = $this->is_min_quantity_notational_error($symbol, 'sell');

        $buy_order_arr = $this->get_buy_order($buy_order_id);

        if (($quantity < $min_notational_quantity) || ($quantity > $balance)) {
            $log_msg = "Buy Order got Custom Min notational <span style='color:red;    font-size: 14px;'><b>ERROR</b></span>";

            $this->insert_order_history_log($buy_order_id, $log_msg, 'buy_error', $user_id);

            $log_msg = 'Order Quantity '.$quantity;
            $this->insert_order_history_log($buy_order_id, $log_msg, 'buy_error', $user_id);

            $log_msg = 'Min Required Order Quantity '.$min_notational_quantity;
            $this->insert_order_history_log($buy_order_id, $log_msg, 'buy_error', $user_id);
            $created_date = date('Y-m-d G:i:s');
            $upd_data22 = array(
                'status' => 'error',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            );
            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_data22);
            //Update data in mongoTable
            $this->mongo_db->update('orders');

            $created_date = date('Y-m-d G:i:s');
            $upd_data22 = array(
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            );
            $this->mongo_db->where(array('_id' => $buy_order_id));
            $this->mongo_db->set($upd_data22);
            //Update data in mongoTable
            $this->mongo_db->update('buy_orders');

            $buy_parent_id = $buy_order_arr['buy_parent_id'];
            $admin_id = $buy_order_arr['admin_id'];
            if ($buy_parent_id) {
                $this->pause_parent_order($buy_parent_id, $admin_id);
            }

            return false;
        }

        $sell_order_arr = $this->get_order($id);
        $created_date = date('Y-m-d G:i:s');
        if ($sell_order_arr['status'] == 'new') {
            ///////////////////////Update Status Before Submitted to Binance///////////////
            $upd_status = array(
                'status' => 'submitted',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                'sell_date' => $this->mongo_db->converToMongodttime($created_date),
            );
            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_status);
            $this->mongo_db->update('orders');
            ///////////////////////Update Status Before Submitted to Binance///////////////

            //Submit Limit Order to Binance
            $order = $this->binance_api->place_sell_limit_order($symbol, $quantity, $price, $user_id);
            // --- %%% call function %%%% ----
            $this->update_user_coin_balance($user_id, $symbol);

            if ($order['orderId'] == '') {
                $order_arr = json_encode($order);
                $order_arr2 = json_decode($order_arr);

                $error_msg = $order_arr2->msg;

                //////////////////////////////////////////////////////////////////////////////
                ////////////////////////////// Order History Log /////////////////////////////
                $log_msg = 'Sell Order was got Error ('.$error_msg.')';
                $this->insert_order_history_log($buy_order_id, $log_msg, 'sell_error', $user_id);
                ////////////////////////////// End Order History Log /////////////////////////
                //////////////////////////////////////////////////////////////////////////////

                /////////////Insert Binance Error Record//////////////////////////////////////
                $this->insert_binance_errors($id, $error_msg, 'sell', $user_id, $symbol);
                /////////////End Insert Binance Error Record//////////////////////////////////

                return array('error' => $error_msg);
            } else {
                $upd_data = array(
                    'market_value' => $price,
                    'status' => 'submitted',
                    'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                    'sell_date' => $this->mongo_db->converToMongodttime($created_date),
                    'binance_order_id' => $order['orderId'],
                );

                $this->mongo_db->where(array('_id' => $id));
                $this->mongo_db->set($upd_data);

                //Update data in mongoTable
                $this->mongo_db->update('orders');

                $update_arr['modified_date'] = $this->mongo_db->converToMongodttime($created_date);
                $this->mongo_db->where(array('sell_order_id' => $id));
                $this->mongo_db->set($update_arr);
                $this->mongo_db->update('buy_orders');
                ////////////////////// Set Notification //////////////////
                $message = 'Sell Limit Order is <b>SUBMITTED</b>';
                $message2 = '<strong>'.$symbol."</strong> Limit Trade is <b style='color:#2ca634'>SUBMITTED</b> for Sell";
                $this->add_notification($id, 'sell', $message, $user_id);
                $this->add_notification_for_app($id, 'trading_alerts', 'medium', $message2, $user_id, $symbol);
                //////////////////////////////////////////////////////////

                //////////////////////////////////////////////////////////////////////////////
                ////////////////////////////// Order History Log /////////////////////////////
                $log_msg = 'Sell Limit Order was <b>SUBMITTED</b>';
                $this->insert_order_history_log($buy_order_id, $log_msg, 'sell_submitted', $user_id);
                ////////////////////////////// End Order History Log /////////////////////////
                //////////////////////////////////////////////////////////////////////////////
            }
        } //End if Order is New

        return true;
    }

    //end binance_sell_auto_limit_order_live

    //binance_sell_auto_stop_loss_limit_order_live
    public function binance_sell_auto_stop_loss_limit_order_live($id, $quantity, $price, $symbol, $user_id, $buy_order_id, $stoploss_price)
    {
        // --- %%% call function %%%% ----
        $this->update_user_coin_balance($user_id, $symbol);

        $balance = $this->binance_api->get_account_balance($symbol, $user_id);
        // if($quantity > $balance){
        //     $quantity = $balance;
        // }

        $log_msg = 'Avaliable Balance of <bold>'.$symbol.'</bold>  is :'.$balance;
        $this->insert_order_history_log($buy_order_id, $log_msg, 'buy_error', $user_id);

        $quantity = $this->remove_min_step_size_notational_error($symbol, $quantity);

        $min_notational_quantity = $this->is_min_quantity_notational_error($symbol, 'sell');
        $buy_order_arr = $this->get_buy_order($buy_order_id);

        if (($quantity < $min_notational_quantity) || ($quantity > $balance)) {
            $log_msg = "Buy Order got Custom  Min notational <span style='color:red;    font-size: 14px;'><b>ERROR</b></span>";

            $this->insert_order_history_log($buy_order_id, $log_msg, 'buy_error', $user_id);

            $log_msg = 'Order Quantity '.$quantity;
            $this->insert_order_history_log($buy_order_id, $log_msg, 'buy_error', $user_id);

            $log_msg = 'Min Required Order Quantity '.$min_notational_quantity;
            $this->insert_order_history_log($buy_order_id, $log_msg, 'buy_error', $user_id);
            $created_date = date('Y-m-d G:i:s');
            $upd_data22 = array(
                'status' => 'error',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            );
            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_data22);
            //Update data in mongoTable
            $this->mongo_db->update('orders');

            $created_date = date('Y-m-d G:i:s');
            $upd_data22 = array(
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            );
            $this->mongo_db->where(array('_id' => $buy_order_id));
            $this->mongo_db->set($upd_data22);
            //Update data in mongoTable
            $this->mongo_db->update('buy_orders');

            $buy_parent_id = $buy_order_arr['buy_parent_id'];
            $admin_id = $buy_order_arr['admin_id'];
            if ($buy_parent_id) {
                $this->pause_parent_order($buy_parent_id, $admin_id);
            }

            return false;
        }

        $sell_order_arr = $this->get_order($id);
        $created_date = date('Y-m-d G:i:s');
        if ($sell_order_arr['status'] == 'new') {
            ///////////////////////Update Status Before Submitted to Binance///////////////
            $upd_status = array(
                'status' => 'submitted',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                'sell_date' => $this->mongo_db->converToMongodttime($created_date),
            );
            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_status);
            $this->mongo_db->update('orders');
            ///////////////////////Update Status Before Submitted to Binance///////////////

            //Submit Limit Order to Binance
            $order = $this->binance_api->fire_stop_loss_limit_order($symbol, $quantity, $price, $user_id, $stoploss_price);

            // --- %%% call function %%%% ----
            $this->update_user_coin_balance($user_id, $symbol);

            if ($order['orderId'] == '') {
                $order_arr = json_encode($order);
                $order_arr2 = json_decode($order_arr);

                $error_msg = $order_arr2->msg;

                //////////////////////////////////////////////////////////////////////////////
                ////////////////////////////// Order History Log /////////////////////////////
                $log_msg = 'Sell Order was got Error ('.$error_msg.')';
                $this->insert_order_history_log($buy_order_id, $log_msg, 'sell_error', $user_id);
                ////////////////////////////// End Order History Log /////////////////////////
                //////////////////////////////////////////////////////////////////////////////

                /////////////Insert Binance Error Record//////////////////////////////////////
                $this->insert_binance_errors($id, $error_msg, 'sell', $user_id, $symbol);
                /////////////End Insert Binance Error Record//////////////////////////////////

                return array('error' => $error_msg);
            } else {
                $upd_data = array(
                    'market_value' => $price,
                    'status' => 'submitted',
                    'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                    'sell_date' => $this->mongo_db->converToMongodttime($created_date),
                    'binance_order_id' => $order['orderId'],
                );

                $this->mongo_db->where(array('_id' => $id));
                $this->mongo_db->set($upd_data);

                //Update data in mongoTable
                $this->mongo_db->update('orders');

                $update_arr['modified_date'] = $this->mongo_db->converToMongodttime($created_date);
                $this->mongo_db->where(array('sell_order_id' => $id));
                $this->mongo_db->set($update_arr);
                $this->mongo_db->update('buy_orders');
                ////////////////////// Set Notification //////////////////
                $message = 'Sell STOP_LOSS_LIMIT Order is <b>SUBMITTED</b>';
                $message2 = '<strong>'.$symbol."</strong> STOP_LOSS_LIMIT Trade is <b style='color:#2ca634'>SUBMITTED</b> for Sell";
                $this->add_notification($id, 'sell', $message, $user_id);
                $this->add_notification_for_app($id, 'trading_alerts', 'medium', $message2, $user_id, $symbol);
                //////////////////////////////////////////////////////////

                //////////////////////////////////////////////////////////////////////////////
                ////////////////////////////// Order History Log /////////////////////////////
                $log_msg = 'Sell STOP_LOSS_LIMIT Order was <b>SUBMITTED</b>';
                $this->insert_order_history_log($buy_order_id, $log_msg, 'sell_submitted', $user_id);
                ////////////////////////////// End Order History Log /////////////////////////
                //////////////////////////////////////////////////////////////////////////////
            }
        } //End if Order is New

        return true;
    }

    //end binance_sell_auto_stop_loss_limit_order_live

    //binance_sell_auto_limit_order_test     ****** TEST ORDERS ********
    public function binance_sell_auto_limit_order_test($id, $quantity, $price, $symbol, $user_id, $buy_order_id)
    {
        $sell_order_arr = $this->get_order($id);
        $created_date = date('Y-m-d G:i:s');
        if ($sell_order_arr['status'] == 'new') {
            $upd_data = array(
                'market_value' => $price,
                'status' => 'submitted',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                'sell_date' => $this->mongo_db->converToMongodttime($created_date),
                'binance_order_id' => '111000',
            );

            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_data);

            //Update data in mongoTable
            $this->mongo_db->update('orders');

            $update_arr['modified_date'] = $this->mongo_db->converToMongodttime($created_date);
            $this->mongo_db->where(array('sell_order_id' => $id));
            $this->mongo_db->set($update_arr);
            $this->mongo_db->update('buy_orders');
            ////////////////////// Set Notification //////////////////
            $message = 'Sell Limit Order is <b>SUBMITTED</b>';
            $message2 = '<strong>'.$symbol."</strong> Limit Trade is <b style='color:#2ca634'>SUBMITTED</b> for Sell";
            $this->add_notification($id, 'sell', $message, $user_id);
            $this->add_notification_for_app($id, 'trading_alerts', 'medium', $message2, $user_id, $symbol);
            //////////////////////////////////////////////////////////

            //////////////////////////////////////////////////////////////////////////////
            ////////////////////////////// Order History Log /////////////////////////////
            $log_msg = 'Sell Limit Order was <b>SUBMITTED</b>';
            $this->insert_order_history_log($buy_order_id, $log_msg, 'sell_submitted', $user_id);
            ////////////////////////////// End Order History Log /////////////////////////
            //////////////////////////////////////////////////////////////////////////////
        } //End if Order is New

        return true;
    }

    //end binance_sell_auto_limit_order_test

    //binance_sell_auto_market_order_live
    public function binance_sell_auto_market_order_live($id, $quantity, $market_value, $symbol, $user_id, $buy_order_id)
    {
        // --- %%% call function %%%% ----
        $this->update_user_coin_balance($user_id, $symbol);
        //Check Order Quantity

        $balance = $this->binance_api->get_account_balance($symbol, $user_id);
        // if($quantity > $balance){
        //     $quantity = $balance;
        // }

        $log_msg = 'Send Qty before  <bold>'.$symbol.'</bold>  is :'.$quantity;
        $this->insert_order_history_log($buy_order_id, $log_msg, 'buy_error', $user_id);

        $log_msg = 'Avaliable Balance of <bold>'.$symbol.'</bold>  is :'.$balance;
        $this->insert_order_history_log($buy_order_id, $log_msg, 'buy_error', $user_id);

        $quantity = $this->remove_min_step_size_notational_error($symbol, $quantity);

        $min_notational_quantity = $this->is_min_quantity_notational_error($symbol, 'sell');

        $buy_order_arr = $this->get_buy_order($buy_order_id);

        $log_msg = 'Send Qty After <bold>'.$symbol.'</bold>  is :'.$quantity;
        $this->insert_order_history_log($buy_order_id, $log_msg, 'buy_error', $user_id);

        if (($quantity < $min_notational_quantity) || ($quantity > $balance)) {
            $log_msg = "Buy Order got  Custom  Min Notational <span style='color:red;    font-size: 14px;'><b>ERROR</b></span>";

            $this->insert_order_history_log($buy_order_id, $log_msg, 'buy_error', $user_id);

            $log_msg = 'Order Quantity '.$quantity;
            $this->insert_order_history_log($buy_order_id, $log_msg, 'buy_error', $user_id);

            $log_msg = 'Min Required Order Quantity '.$min_notational_quantity;
            $this->insert_order_history_log($buy_order_id, $log_msg, 'buy_error', $user_id);
            $created_date = date('Y-m-d G:i:s');
            $upd_data22 = array(
                'status' => 'error',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            );
            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_data22);
            //Update data in mongoTable
            $this->mongo_db->update('orders');

            $created_date = date('Y-m-d G:i:s');
            $upd_data22 = array(
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            );
            $this->mongo_db->where(array('_id' => $buy_order_id));
            $this->mongo_db->set($upd_data22);
            //Update data in mongoTable
            $this->mongo_db->update('buy_orders');

            $buy_parent_id = $buy_order_arr['buy_parent_id'];
            $admin_id = $buy_order_arr['admin_id'];
            if ($buy_parent_id) {
                $this->pause_parent_order($buy_parent_id, $admin_id);
            }

            return false;
        }

        $sell_order_arr = $this->get_order($id);

        if ($this->is_stepSize($symbol) == 'true') {
            $quantity = floor($quantity);
        }
        $created_date = date('Y-m-d G:i:s');

        $api_key_check = $this->check_user_api_key_set($user_id);

        if ($sell_order_arr['status'] == 'new' && $api_key_check) {
            ///////////////////////Update Status Before Submitted to Binance///////////////
            $upd_status = array(
                'status' => 'submitted',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                'sell_date' => $this->mongo_db->converToMongodttime($created_date),
            );
            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_status);
            $this->mongo_db->update('orders');

            $update_arr['modified_date'] = $this->mongo_db->converToMongodttime($created_date);
            $this->mongo_db->where(array('sell_order_id' => $id));
            $this->mongo_db->set($update_arr);

            //Update data in mongoTable
            $this->mongo_db->update('buy_orders');
            ///////////////////////Update Status Before Submitted to Binance///////////////

            //Submit Limit Order to Binance
            $order = $this->binance_api->place_sell_market_order($symbol, $quantity, $user_id);
            // --- %%% call function %%%% ----
            $this->update_user_coin_balance($user_id, $symbol);

            if ($order['orderId'] == '') {
                $order_arr = json_encode($order);
                $order_arr2 = json_decode($order_arr);

                $error_msg = $order_arr2->msg;

                //////////////////////////////////////////////////////////////////////////////
                ////////////////////////////// Order History Log /////////////////////////////
                $log_msg = 'Sell Order was got Error ('.$error_msg.')';
                $this->insert_order_history_log($buy_order_id, $log_msg, 'sell_error', $user_id);
                ////////////////////////////// End Order History Log /////////////////////////
                //////////////////////////////////////////////////////////////////////////////

                /////////////Insert Binance Error Record/////////////////////////////////////
                $this->insert_binance_errors($id, $error_msg, 'sell', $user_id, $symbol);
                /////////////End Insert Binance Error Record/////////////////////////////////

                return array('error' => $error_msg);
            } else {
                $upd_data = array(
                    'market_value' => $market_value,
                    'status' => 'submitted',
                    'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                    'sell_date' => $this->mongo_db->converToMongodttime($created_date),
                    'binance_order_id' => $order['orderId'],
                );

                $this->mongo_db->where(array('_id' => $id));
                $this->mongo_db->set($upd_data);

                //Update data in mongoTable
                $this->mongo_db->update('orders');

                ////////////////////// Set Notification //////////////////
                $message = 'Sell Market Order is <b>SUBMITTED</b>';
                $message2 = '<strong>'.$symbol."</strong> Market Trade is <b style='color:#2ca634'>SUBMITTED</b> for Sell";
                $this->add_notification($id, 'sell', $message, $user_id);
                $this->add_notification_for_app($id, 'trading_alerts', 'medium', $message2, $user_id, $symbol);
                //////////////////////////////////////////////////////////

                //////////////////////////////////////////////////////////////////////////////
                ////////////////////////////// Order History Log /////////////////////////////
                $log_msg = 'Sell Market Order was <b>SUBMITTED</b>';
                $this->insert_order_history_log($buy_order_id, $log_msg, 'sell_submitted', $user_id);
                ////////////////////////////// End Order History Log /////////////////////////
                //////////////////////////////////////////////////////////////////////////////
            }
        } //End if Order is New

        return true;
    }

    //end binance_sell_auto_market_order_live

    //binance_sell_auto_market_order_test     ****** TEST ORDERS ********
    public function binance_sell_auto_market_order_test($id, $quantity, $market_value, $symbol, $user_id, $buy_order_id)
    {
        $sell_order_arr = $this->get_order($id);
        $created_date = date('Y-m-d G:i:s');
        if ($sell_order_arr['status'] == 'new' || $sell_order_arr['status'] == 'LTH') {
            $upd_data = array(
                'market_value' => $market_value,
                'status' => 'submitted',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                'sell_date' => $this->mongo_db->converToMongodttime($created_date),
                'binance_order_id' => '111000',
            );

            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_data);

            //Update data in mongoTable
            $this->mongo_db->update('orders');

            $update_arr['modified_date'] = $this->mongo_db->converToMongodttime($created_date);
            $this->mongo_db->where(array('sell_order_id' => $this->mongo_db->mongoId($id)));
            $this->mongo_db->set($update_arr);
            $this->mongo_db->update('buy_orders');
            ////////////////////// Set Notification //////////////////
            $message = 'Sell Market Order is <b>SUBMITTED</b>';
            $message2 = '<strong>'.$symbol."</strong> Market Trade is <b style='color:#2ca634'>SUBMITTED</b> for Sell";
            $this->add_notification($id, 'sell', $message, $user_id);
            $this->add_notification_for_app($id, 'trading_alerts', 'medium', $message2, $user_id, $symbol);
            //////////////////////////////////////////////////////////

            //////////////////////////////////////////////////////////////////////////////
            ////////////////////////////// Order History Log /////////////////////////////
            $log_msg = 'Sell Market Order was <b>SUBMITTED</b>';
            $this->insert_order_history_log($buy_order_id, $log_msg, 'sell_submitted', $user_id);
            ////////////////////////////// End Order History Log /////////////////////////
            //////////////////////////////////////////////////////////////////////////////
        } //End if Order is New

        return true;
    }

    //end binance_sell_auto_market_order_test

    public function binance_sell_auto_market_order_test_bam($id, $quantity, $market_value, $symbol, $user_id, $buy_order_id)
    {
        $sell_order_arr = $this->get_order_bam($id);
        $created_date = date('Y-m-d G:i:s');
        if ($sell_order_arr['status'] == 'new' || $sell_order_arr['status'] == 'LTH') {
            $upd_data = array(
                'market_value' => $market_value,
                'status' => 'submitted',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                'sell_date' => $this->mongo_db->converToMongodttime($created_date),
                'binance_order_id' => '111000',
            );

            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_data);

            //Update data in mongoTable
            $this->mongo_db->update('orders_bam');

            $update_arr['modified_date'] = $this->mongo_db->converToMongodttime($created_date);
            $this->mongo_db->where(array('sell_order_id' => $this->mongo_db->mongoId($id)));
            $this->mongo_db->set($update_arr);
            $this->mongo_db->update('buy_orders_bam');
            ////////////////////// Set Notification //////////////////
            $message = 'Sell Market Order is <b>SUBMITTED</b>';
            $message2 = '<strong>'.$symbol."</strong> Market Trade is <b style='color:#2ca634'>SUBMITTED</b> for Sell";
            $this->add_notification($id, 'sell', $message, $user_id);
            $this->add_notification_for_app($id, 'trading_alerts', 'medium', $message2, $user_id, $symbol);
            //////////////////////////////////////////////////////////

            //////////////////////////////////////////////////////////////////////////////
            ////////////////////////////// Order History Log /////////////////////////////
            $log_msg = 'Sell Market Order was <b>SUBMITTED</b>';
            $this->insert_order_history_log_bam($buy_order_id, $log_msg, 'sell_submitted', $user_id);
            ////////////////////////////// End Order History Log /////////////////////////
            //////////////////////////////////////////////////////////////////////////////
        } //End if Order is New
    }

    //end binance_sell_auto_market_order_test_bam

    //check_order_quantity
    public function check_order_quantity($id, $buy_order_id, $admin_id, $symbol, $quantity)
    {
        $created_date = date('Y-m-d G:i:s');
        //Get user Details

        $this->mongo_db->where(array('_id' => $admin_id));
        $get_arr = $this->mongo_db->get('users');
        $settings_arr = iterator_to_array($get_arr);
        $setting_arr = $settings_arr[0];

        if ($setting_arr['api_key'] != '' && $setting_arr['api_secret'] != '' && $setting_arr['auto_sell_enable'] == 'yes') {
            $this->mongo_db->where(array('user_id' => $admin_id));
            $get_coins = $this->mongo_db->get('coins');
            $coins_arr = iterator_to_array($get_coins);
            $coins_arr = $coins_arr[0];
            $coin_balance = $coins_arr['coin_balance'];

            if ($quantity > $coin_balance) {
                //Update Order Record
                $upd_data = array(
                    'quantity' => $coin_balance,
                    'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                    'last_quantity_updated' => 'yes',
                );

                $this->mongo_db->where(array('_id' => $id));
                $this->mongo_db->set($upd_data);
                $this->mongo_db->update('orders');

                //%%%%%%%%%%%%%%%%%%%%%%%update buy orders %%%%%%%%%%%%%
                $this->mongo_db->where(array('_id' => $buy_order_id));
                $this->mongo_db->set($upd_data);
                $this->mongo_db->update('buy_orders');

                //////////////////////////////////////////////////////////////////////////////
                ////////////////////////////// Order History Log /////////////////////////////
                $log_msg = 'Sell Order Quantity Updated from <b>'.$quantity.'</b> to <b>'.$coin_balance.'</b> as per your Last Order Settings';
                $this->insert_order_history_log($buy_order_id, $log_msg, 'auto_update_quantity', $admin_id);
                ////////////////////////////// End Order History Log /////////////////////////
                //////////////////////////////////////////////////////////////////////////////

                return $coin_balance;
            } else {
                return 'no';
            }
        } else {
            return 'no';
        }
    }

    //end check_order_quantity

    public function is_stepSize($symbol)
    {
        $this->mongo_db->where(array('symbol' => $symbol));
        $resp = $this->mongo_db->get('market_min_notation');
        $resp = iterator_to_array($resp);
        $resp = $resp[0];
        $stepSize = $resp['stepSize'];
        $resp2 = 'true';
        if ($stepSize < 1) {
            $resp2 = 'false';
        }

        return $resp2;
    }

    //%%%%%%% is_stepSize %%%%%%%%%%%%%

    //insert_binance_errors
    public function insert_binance_errors($id, $error_msg, $type, $user_id, $symbol)
    {
        $created_date = date('Y-m-d G:i:s');

        //Update Order Record
        $upd_data = array(
            'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            'status' => 'error',
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        if ($type == 'sell') {
            $this->mongo_db->update('orders');
        } else {
            $this->mongo_db->update('buy_orders');
        }

        ////////////////////// Set Notification //////////////////
        $message = ucfirst($type).' Order got <b>ERROR</b>';
        $message2 = '<strong>'.$symbol.'</strong> '.ucfirst($type)." Trade got <b style='color:#fe7481'>ERROR</b> Because ".$error_msg;
        $this->add_notification($id, $type, $message, $user_id);
        $this->add_notification_for_app($id, 'trading_alerts', 'high', $message2, $user_id, $symbol);
        //////////////////////////////////////////////////////////

        return true;
    }

    //end insert_binance_errors

    //insert_order_history_log
    public function insert_order_history_log($id, $log_msg, $type, $user_id)
    {
        $created_date = date('Y-m-d G:i:s');

        $ins_error = array(
            'order_id' => $this->mongo_db->mongoId($id),
            'log_msg' => $log_msg,
            'type' => $type,
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
        );

        $this->mongo_db->insert('orders_history_log', $ins_error);

        return true;
    }

    //end insert_order_history_log

    public function insert_order_history_log_bam($id, $log_msg, $type, $user_id)
    {
        $created_date = date('Y-m-d G:i:s');

        $ins_error = array(
            'order_id' => $this->mongo_db->mongoId($id),
            'log_msg' => $log_msg,
            'type' => $type,
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
        );

        $this->mongo_db->insert('orders_history_log_bam', $ins_error);

        return true;
    }

    //end insert_order_history_log

    //get_binance_errors
    public function get_binance_errors($id, $type)
    {
        $this->mongo_db->where(array('order_id' => $id, 'type' => $type));
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('binance_errors');

        $fullarray = array();
        foreach ($responseArr as $valueArr) {
            $returArr = array();
            if (!empty($valueArr)) {
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $returArr['order_id'] = $valueArr['order_id'];
                $returArr['type'] = $valueArr['type'];
                $returArr['error_msg'] = $valueArr['error_msg'];
                $returArr['created_date'] = $formated_date_time;
            }

            $fullarray[] = $returArr;
        }

        return $fullarray;
    }

    //End get_binance_errors

    //get_order_history_log
    public function get_order_history_log($id)
    {
        $this->mongo_db->where(array('order_id' => $id));

        if ($this->session->userdata('special_role') != 1) {
            $this->mongo_db->where_ne('show_error_log', 'no');
        }

        // if (!isset($_GET['testing'])) {
        //     $this->mongo_db->where_ne('show_error_log', 'yes');
        // }

        $this->mongo_db->sort(array('_id' => 'desc'));
        $this->mongo_db->limit(1000);

        $responseArr = $this->mongo_db->get('orders_history_log');

        $timezone = $this->session->userdata('timezone');
        $fullarray = array();
        foreach ($responseArr as $valueArr) {
            $returArr = array();
            if (!empty($valueArr)) {
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone($timezone);
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A T');

                $returArr['order_id'] = $valueArr['order_id'];
                $returArr['type'] = $valueArr['type'];
                $returArr['log_msg'] = $valueArr['log_msg'];
                $returArr['created_date'] = $formated_date_time;
            }

            $fullarray[] = $returArr;
        }

        return $fullarray;
    }

    //End get_order_history_log

    //add_buy_order_triggers

    public function add_buy_order_triggers($data)
    {
        extract($data);
        $un_limit_child_orders = (isset($data['un_limit_child_orders'])) ? $data['un_limit_child_orders'] : 'no';

        $created_date = date('Y-m-d G:i:s');
        $admin_id = $this->session->userdata('admin_id');
        $order_mode_arr = array();
        if ($order_mode != '') {
            $order_mode_arr = explode('_', $order_mode);
        }

        $application_mode = '';
        if (count($order_mode_arr) > 0) {
            $application_mode = $order_mode_arr[0];
        }

        $created_date = date('Y-m-d G:i:s');
        $admin_id = $this->session->userdata('admin_id');
        $order_mode_arr = array();
        if ($order_mode != '') {
            $order_mode_arr = explode('_', $order_mode);
        }

        $application_mode = '';
        if (count($order_mode_arr) > 0) {
            $application_mode = $order_mode_arr[0];
        }

        $inactive_time_new = date('Y-m-d G:00:00', strtotime($inactive_time));

        if (!$buy_one_tip_above) {
            $buy_one_tip_above = 'not';
        }

        if (!$buy_one_tip_above) {
            $sell_one_tip_below = 'not';
        }

        $market_value = $this->mod_dashboard->get_market_value($coin);

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
            'pause_status' => 'play',
            'parent_status' => 'parent',
            'defined_sell_percentage' => $defined_sell_percentage,
            'buy_one_tip_above' => $buy_one_tip_above,
            'sell_one_tip_below' => $sell_one_tip_below,
            'order_level' => $order_level,
            'current_market_price' => (float) $market_value,
            'stop_loss_rule' => $stop_loss_rule,
            'custom_stop_loss_percentage' => $custom_stop_loss_percentage_frst,
            'activate_stop_loss_profit_percentage' => $activate_stop_loss_profit_percentage,
            'lth_functionality' => $lth_functionality,
            'lth_profit' => $lth_profit,
            'un_limit_child_orders' => $un_limit_child_orders,
            'secondary_stop_loss_rule' => $secondary_stop_loss_rule,
            'secondary_stop_loss_status' => 'in-active',
        );

        if (!empty($inactive_time) && $inactive_time != '') {
            $ins_data['inactive_time'] = $this->mongo_db->converToMongodttime($inactive_time_new);
        }

        $buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data);

        return true;
    }

    //End add_buy_order_triggers

    //add_buy_order
    public function add_buy_order($data)
    {
        extract($data);

        $created_date = date('Y-m-d G:i:s');
        $admin_id = $this->session->userdata('admin_id');
        $global_symbol = $this->session->userdata('global_symbol');
        $application_mode = $this->session->userdata('global_mode');

        $ins_data = array(
            'price' => $price, //(float) $price,
            'quantity' => $quantity,
            'symbol' => $coin,
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
            $ins_data['buy_trail_percentage'] = $buy_trail_interval_per;
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
        //if ($_SERVER['REMOTE_ADDR'] == '115.186.191.18') {

        if ($_SERVER['REMOTE_ADDR'] == '203.99.181.69') {
            echo '<pre>';
            print_r($ins_data);
        }
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
                'sell_trail_percentage' => $sell_trail_interval_per,
                'stop_loss' => $stop_loss,
                'loss_percentage' => $loss_percentage,
                'admin_id' => $admin_id,
                'lth_functionality' => $autoLTH,
                'application_mode' => $application_mode,
                'created_date' => $this->mongo_db->converToMongodttime($created_date),
            );

            if ($_SERVER['REMOTE_ADDR'] == '203.99.181.69') {
                echo '<pre>';
                print_r($ins_temp_data);
            }

            //Insert data in mongoTable
            $this->mongo_db->insert('temp_sell_orders', $ins_temp_data);
        }
        //////////////////////////////// End Auto Sell/////////////////////////
        if ($_SERVER['REMOTE_ADDR'] == '203.99.181.69') {
            exit;
        }
        //////////////////////////////////////////////////////////////////////////////
        ////////////////////////////// Order History Log /////////////////////////////
        $log_msg = 'Buy Order was Created at Price '.$price;
        if ($auto_sell == 'yes' && $sell_profit_percent != '') {
            $log_msg .= ' with auto sell '.$sell_profit_percent.'%';
        }
        $this->insert_order_history_log($buy_order_id, $log_msg, 'buy_created', $admin_id);
        ////////////////////////////// End Order History Log /////////////////////////
        //////////////////////////////////////////////////////////////////////////////

        return true;
    }

    //end add_buy_order

    //auto_sell_now
    public function auto_sell_now($buy_order_id)
    {
        $order_arr = $this->get_buy_order($buy_order_id);

        $auto_sell = $order_arr['auto_sell'];
        $admin_id = $order_arr['admin_id'];
        $symbol = $order_arr['symbol'];

        $is_submitted = 'no';
        if ($auto_sell == 'yes') {
            $created_date = date('Y-m-d G:i:s');

            $purchased_price = $order_arr['market_value'];

            if (!empty($order_arr['updated_quantity'])) {
                $quantity = $order_arr['updated_quantity'];
            } else {
                $quantity = $order_arr['quantity'];
            }

            $binance_order_id = $order_arr['binance_order_id'];
            $buy_order_check = 'yes';

            //Get Sell Temp Data
            $sell_data_arr = $this->get_temp_sell_data($buy_order_id);
            $profit_type = $sell_data_arr['profit_type'];
            $sell_profit_percent = $sell_data_arr['profit_percent'];
            $sell_profit_price = $sell_data_arr['profit_price'];
            $order_type = $sell_data_arr['order_type'];
            $trail_check = $sell_data_arr['trail_check'];
            $trail_interval = $sell_data_arr['trail_interval'];
            $stop_loss = $sell_data_arr['stop_loss'];
            $loss_percentage = $sell_data_arr['loss_percentage'];
            $application_mode = $sell_data_arr['application_mode'];
            $lth_functionality = $sell_data_arr['lth_functionality'];

            $ins_data = array(
                'symbol' => $symbol,
                'purchased_price' => $purchased_price,
                'quantity' => $quantity,
                'profit_type' => $profit_type,
                'order_type' => $order_type,
                'admin_id' => $admin_id,
                'buy_order_check' => $buy_order_check,
                'buy_order_id' => $buy_order_id,
                'buy_order_binance_id' => $binance_order_id,
                'stop_loss' => $stop_loss,
                'lth_functionality' => $lth_functionality,
                'loss_percentage' => $loss_percentage,
                'application_mode' => $application_mode,
                'trigger_type' => 'no',
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                'created_date' => $this->mongo_db->converToMongodttime($created_date),
            );

            if (($profit_type == 'percentage') || ($sell_profit_percent > 0)) {
                $sell_price = $purchased_price * $sell_profit_percent;
                $sell_price = $sell_price / 100;
                $sell_price = $sell_price + $purchased_price;
                $sell_price = number_format($sell_price, 8, '.', '');

                $ins_data['sell_profit_percent'] = $sell_profit_percent;
                $ins_data['sell_price'] = $sell_price;
            } else {
                $sell_price = $sell_profit_price;

                $ins_data['sell_profit_price'] = $sell_profit_price;
                $ins_data['sell_price'] = $sell_price;
            }

            if ($trail_check != '') {
                $ins_data['trail_check'] = 'yes';
                $ins_data['trail_interval'] = $trail_interval;
                $ins_data['sell_trail_price'] = $sell_price;
                $ins_data['status'] = 'new';
            } else {
                $ins_data['trail_check'] = 'no';
                $ins_data['trail_interval'] = '0';
                $ins_data['sell_trail_price'] = '0';

                if ($order_type == 'limit_order') {
                    //Submit Sell Limit Order to Binance
                    $order = $this->binance_api->place_sell_limit_order($symbol, $quantity, $sell_price);

                    if ($order['orderId'] == '') {
                        $order_arr = json_encode($order);
                        $order_arr2 = json_decode($order_arr);

                        $error_msg = $order_arr2->msg;

                        return array('error' => $error_msg);
                    } else {
                        $ins_data['market_value'] = $sell_price;
                        $ins_data['status'] = 'submitted';
                        $ins_data['binance_order_id'] = $order['orderId'];
                        $is_submitted = 'yes';
                    }
                } else {
                    $ins_data['status'] = 'new';
                }
            }

            //Insert data in mongoTable
            $order_id = $this->mongo_db->insert('orders', $ins_data);

            if ($buy_order_check == 'yes') {
                //Update Buy Order
                $upd_data = array(
                    'is_sell_order' => 'yes',
                    'lth_functionality' => $lth_functionality,
                    'sell_order_id' => $order_id,
                );

                $this->mongo_db->where(array('_id' => $buy_order_id));
                $this->mongo_db->set($upd_data);

                //Update data in mongoTable
                $this->mongo_db->update('buy_orders');
            }

            //////////////////////////////////////////////////////////////////////////////
            ////////////////////////////// Order History Log /////////////////////////////
            $log_msg = 'Sell Order was Created from Auto Sell';
            $this->insert_order_history_log($buy_order_id, $log_msg, 'sell_created', $admin_id);
            ////////////////////////////// End Order History Log /////////////////////////
            //////////////////////////////////////////////////////////////////////////////

            //////////////////////////////////////////////////////////////////////////////
            ////////////////////////////// Order History Log /////////////////////////////
            if ($is_submitted == 'yes') {
                $log_msg = 'Sell Order was Submitted to Binance';
                $this->insert_order_history_log($buy_order_id, $log_msg, 'sell_submitted', $admin_id);
            }
            ////////////////////////////// End Order History Log /////////////////////////
            //////////////////////////////////////////////////////////////////////////////
        } // if($auto_sell =='yes')

        return true;
    }

    //End auto_sell_now
    // this method was commented by MUhammad Sheraz on (31-august-2021) on behalf of Shehzad.
    // public function auto_sell_now_bam($buy_order_id)
    // {
    //     $order_arr = $this->get_buy_order_bam($buy_order_id);

    //     $auto_sell = $order_arr['auto_sell'];
    //     $admin_id = $order_arr['admin_id'];
    //     $symbol = $order_arr['symbol'];

    //     $is_submitted = 'no';
    //     if ($auto_sell == 'yes') {
    //         $created_date = date('Y-m-d G:i:s');

    //         $purchased_price = $order_arr['market_value'];

    //         if (!empty($order_arr['updated_quantity'])) {
    //             $quantity = $order_arr['updated_quantity'];
    //         } else {
    //             $quantity = $order_arr['quantity'];
    //         }

    //         $binance_order_id = $order_arr['binance_order_id'];
    //         $buy_order_check = 'yes';

    //         //Get Sell Temp Data
    //         $sell_data_arr = $this->get_temp_sell_data_bam($buy_order_id);
    //         $profit_type = $sell_data_arr['profit_type'];
    //         $sell_profit_percent = $sell_data_arr['profit_percent'];
    //         $sell_profit_price = $sell_data_arr['profit_price'];
    //         $order_type = $sell_data_arr['order_type'];
    //         $trail_check = $sell_data_arr['trail_check'];
    //         $trail_interval = $sell_data_arr['trail_interval'];
    //         $stop_loss = $sell_data_arr['stop_loss'];
    //         $loss_percentage = $sell_data_arr['loss_percentage'];
    //         $application_mode = $sell_data_arr['application_mode'];
    //         $lth_functionality = $sell_data_arr['lth_functionality'];

    //         $ins_data = array(
    //             'symbol' => $symbol,
    //             'purchased_price' => $purchased_price,
    //             'quantity' => $quantity,
    //             'profit_type' => $profit_type,
    //             'order_type' => $order_type,
    //             'admin_id' => $admin_id,
    //             'buy_order_check' => $buy_order_check,
    //             'buy_order_id' => $buy_order_id,
    //             'buy_order_binance_id' => $binance_order_id,
    //             'stop_loss' => $stop_loss,
    //             'lth_functionality' => $lth_functionality,
    //             'loss_percentage' => $loss_percentage,
    //             'application_mode' => $application_mode,
    //             'trigger_type' => 'no',
    //             'modified_date' => $this->mongo_db->converToMongodttime($created_date),
    //             'created_date' => $this->mongo_db->converToMongodttime($created_date),
    //         );

    //         if ($profit_type == 'percentage' || $sell_profit_percent > 0) {
    //             $sell_price = $purchased_price * $sell_profit_percent;
    //             $sell_price = $sell_price / 100;
    //             $sell_price = $sell_price + $purchased_price;
    //             $sell_price = number_format($sell_price, 8, '.', '');

    //             $ins_data['sell_profit_percent'] = $sell_profit_percent;
    //             $ins_data['sell_price'] = $sell_price;
    //         } else {
    //             $sell_price = $sell_profit_price;

    //             $ins_data['sell_profit_price'] = $sell_profit_price;
    //             $ins_data['sell_price'] = $sell_price;
    //         }

    //         if ($trail_check != '') {
    //             $ins_data['trail_check'] = 'yes';
    //             $ins_data['trail_interval'] = $trail_interval;
    //             $ins_data['sell_trail_price'] = $sell_price;
    //             $ins_data['status'] = 'new';
    //         } else {
    //             $ins_data['trail_check'] = 'no';
    //             $ins_data['trail_interval'] = '0';
    //             $ins_data['sell_trail_price'] = '0';

    //             if ($order_type == 'limit_order') {
    //                 //Submit Sell Limit Order to Binance
    //                 $order = $this->binance_api->place_sell_limit_order($symbol, $quantity, $sell_price);

    //                 if ($order['orderId'] == '') {
    //                     $order_arr = json_encode($order);
    //                     $order_arr2 = json_decode($order_arr);

    //                     $error_msg = $order_arr2->msg;

    //                     return array('error' => $error_msg);
    //                 } else {
    //                     $ins_data['market_value'] = $sell_price;
    //                     $ins_data['status'] = 'submitted';
    //                     $ins_data['binance_order_id'] = $order['orderId'];
    //                     $is_submitted = 'yes';
    //                 }
    //             } else {
    //                 $ins_data['status'] = 'new';
    //             }
    //         }

    //         //Insert data in mongoTable
    //         $order_id = $this->mongo_db->insert('orders_bam', $ins_data);

    //         if ($buy_order_check == 'yes') {
    //             //Update Buy Order
    //             $upd_data = array(
    //                 'is_sell_order' => 'yes',
    //                 'lth_functionality' => $lth_functionality,
    //                 'sell_order_id' => $order_id,
    //             );

    //             $this->mongo_db->where(array('_id' => $buy_order_id));
    //             $this->mongo_db->set($upd_data);

    //             //Update data in mongoTable
    //             $this->mongo_db->update('buy_orders_bam');
    //         }

    //         //////////////////////////////////////////////////////////////////////////////
    //         ////////////////////////////// Order History Log /////////////////////////////
    //         $log_msg = 'Sell Order was Created from Auto Sell';
    //         $this->insert_order_history_log_bam($buy_order_id, $log_msg, 'sell_created', $admin_id);
    //         ////////////////////////////// End Order History Log /////////////////////////
    //         //////////////////////////////////////////////////////////////////////////////

    //         //////////////////////////////////////////////////////////////////////////////
    //         ////////////////////////////// Order History Log /////////////////////////////
    //         if ($is_submitted == 'yes') {
    //             $log_msg = 'Sell Order was Submitted to Binance';
    //             $this->insert_order_history_log_bam($buy_order_id, $log_msg, 'sell_submitted', $admin_id);
    //         }
    //         ////////////////////////////// End Order History Log /////////////////////////
    //         //////////////////////////////////////////////////////////////////////////////
    //     } // if($auto_sell =='yes')

    //     return true;
    // }

    //End auto_sell_now_bam

    //get_buy_order
    // this method was commented by Muhammad sheraz on (31-august-2021) on behalf of shehzad.
    // public function get_buy_order_bam($id)
    // {
    //     $timezone = $this->session->userdata('timezone');
    //     if (empty($timezone)) {
    //         $timezone = 'ASIA/KARACHI';
    //     }
    //     $this->mongo_db->where(array('_id' => $id));
    //     $this->mongo_db->sort(array('_id' => 'desc'));
    //     $responseArr = $this->mongo_db->get('buy_orders_bam');
    //     $final_resp_arr = iterator_to_array($responseArr);
    //     if (count($final_resp_arr) == 0) {
    //         $this->mongo_db->where(array('_id' => $id));
    //         $this->mongo_db->sort(array('_id' => 'desc'));
    //         $responseArr = $this->mongo_db->get('sold_buy_orders_bam');
    //         $final_resp_arr = iterator_to_array($responseArr);
    //     }
    //     foreach ($final_resp_arr as $valueArr) {
    //         $returArr = array();
    //         if (!empty($valueArr)) {
    //             $datetime = $valueArr['created_date']->toDateTime();
    //             $created_date = $datetime->format(DATE_RSS);

    //             $datetime = new DateTime($created_date);
    //             $datetime->format('Y-m-d g:i:s A');

    //             $new_timezone = new DateTimeZone($timezone);
    //             $datetime->setTimezone($new_timezone);
    //             $formated_date_time = $datetime->format('Y-m-d g:i:s A');
    //             if (empty($valueArr['modified_date'])) {
    //                 $valueArr['modified_date'] = $valueArr['created_date'];
    //             }
    //             $datetime2 = $valueArr['modified_date']->toDateTime();
    //             $created_date2 = $datetime2->format(DATE_RSS);

    //             $datetime2 = new DateTime($created_date2);
    //             $datetime2->format('Y-m-d g:i:s A');
    //             $datetime2->setTimezone($new_timezone);
    //             $formated_date_time2 = $datetime2->format('Y-m-d g:i:s A');

    //             $returArr['_id'] = $valueArr['_id'];
    //             $returArr['symbol'] = $valueArr['symbol'];
    //             $returArr['binance_order_id'] = $valueArr['binance_order_id'];
    //             $returArr['price'] = $valueArr['price'];
    //             $returArr['quantity'] = $valueArr['quantity'];
    //             $returArr['market_value'] = num($valueArr['market_value']);
    //             $returArr['order_type'] = $valueArr['order_type'];
    //             $returArr['status'] = $valueArr['status'];
    //             $returArr['admin_id'] = $valueArr['admin_id'];
    //             $returArr['trail_check'] = $valueArr['trail_check'];
    //             $returArr['buy_trail_price'] = $valueArr['buy_trail_price'];
    //             $returArr['trail_interval'] = $valueArr['trail_interval'];
    //             $returArr['is_sell_order'] = $valueArr['is_sell_order'];
    //             $returArr['market_sold_price'] = num($valueArr['market_sold_price']);
    //             $returArr['sell_order_id'] = $valueArr['sell_order_id'];
    //             $returArr['auto_sell'] = $valueArr['auto_sell'];
    //             $returArr['trigger_type'] = $valueArr['trigger_type'];
    //             $returArr['modified_date'] = $formated_date_time2;
    //             $returArr['application_mode'] = $valueArr['application_mode'];
    //             $returArr['inactive_status'] = $valueArr['inactive_status'];
    //             $returArr['inactive_time'] = $valueArr['inactive_time'];
    //             $returArr['parent_status'] = $valueArr['parent_status'];

    //             $returArr['buy_rule_number'] = $valueArr['buy_rule_number'];
    //             $returArr['sell_rule_number'] = $valueArr['sell_rule_number'];
    //             $returArr['is_manual_sold'] = $valueArr['is_manual_sold'];

    //             $returArr['buy_parent_id'] = $valueArr['buy_parent_id'];
    //             $returArr['order_mode'] = $valueArr['order_mode'];
    //             $returArr['updated_quantity'] = $valueArr['update_quantity_after_commision_deducted'];
    //             $returArr['created_date'] = $formated_date_time;
    //         }
    //     }

    //     return $returArr;
    // }

    //end get_buy_order_bam

    //get_temp_sell_data
    public function get_temp_sell_data($buy_order_id)
    {
        $this->mongo_db->where(array('buy_order_id' => $buy_order_id));
        $responseArr = $this->mongo_db->get('temp_sell_orders');

        foreach ($responseArr as $valueArr) {
            $returArr = array();
            if (!empty($valueArr)) {
                $returArr['_id'] = $valueArr['_id'];
                $returArr['buy_order_id'] = $valueArr['buy_order_id'];
                $returArr['profit_type'] = $valueArr['profit_type'];
                $returArr['profit_percent'] = $valueArr['profit_percent'];
                $returArr['profit_price'] = $valueArr['profit_price'];
                $returArr['order_type'] = $valueArr['order_type'];
                $returArr['trail_check'] = $valueArr['trail_check'];
                $returArr['trail_interval'] = $valueArr['trail_interval'];
                $returArr['stop_loss'] = $valueArr['stop_loss'];
                $returArr['lth_functionality'] = $valueArr['lth_functionality'];
                $returArr['loss_percentage'] = $valueArr['loss_percentage'];
                $returArr['application_mode'] = $valueArr['application_mode'];
            }
        }

        return $returArr;
    }
    
    
    //get_temp_sell_data_exchnage
    public function get_temp_sell_data_exchnage($buy_order_id, $exchange=''){

        if(!empty($exchange)){
            if($exchange != 'binance'){
                $exchange = "_$exchange";
            }{
                $exchange = "";
            }
        }

        $this->mongo_db->where(array('buy_order_id' => $buy_order_id));
        $responseArr = $this->mongo_db->get("temp_sell_orders$exchange");

        foreach ($responseArr as $valueArr) {
            $returArr = array();
            if (!empty($valueArr)) {
                $returArr['_id'] = $valueArr['_id'];
                $returArr['buy_order_id'] = $valueArr['buy_order_id'];
                $returArr['profit_type'] = $valueArr['profit_type'];
                $returArr['profit_percent'] = $valueArr['profit_percent'];
                $returArr['profit_price'] = $valueArr['profit_price'];
                $returArr['order_type'] = $valueArr['order_type'];
                $returArr['trail_check'] = $valueArr['trail_check'];
                $returArr['trail_interval'] = $valueArr['trail_interval'];
                $returArr['stop_loss'] = $valueArr['stop_loss'];
                $returArr['lth_functionality'] = $valueArr['lth_functionality'];
                $returArr['loss_percentage'] = $valueArr['loss_percentage'];
                $returArr['application_mode'] = $valueArr['application_mode'];
            }
        }

        return $returArr;
    }//end get_temp_sell_data_exchnage

    //End get_temp_sell_data

    public function get_temp_sell_data_bam($buy_order_id){

        $this->mongo_db->where(array('buy_order_id' => $buy_order_id));
        $responseArr = $this->mongo_db->get('temp_sell_orders_bam');

        foreach ($responseArr as $valueArr) {
            $returArr = array();
            if (!empty($valueArr)) {
                $returArr['_id'] = $valueArr['_id'];
                $returArr['buy_order_id'] = $valueArr['buy_order_id'];
                $returArr['profit_type'] = $valueArr['profit_type'];
                $returArr['profit_percent'] = $valueArr['profit_percent'];
                $returArr['profit_price'] = $valueArr['profit_price'];
                $returArr['order_type'] = $valueArr['order_type'];
                $returArr['trail_check'] = $valueArr['trail_check'];
                $returArr['trail_interval'] = $valueArr['trail_interval'];
                $returArr['stop_loss'] = $valueArr['stop_loss'];
                $returArr['lth_functionality'] = $valueArr['lth_functionality'];
                $returArr['loss_percentage'] = $valueArr['loss_percentage'];
                $returArr['application_mode'] = $valueArr['application_mode'];
            }
        }

        return $returArr;
    }

    //End get_temp_sell_data_bam

    //edit_buy_order
    public function edit_buy_order($data)
    {
        extract($data);

        $order_arr = $this->get_buy_order($id);

        if ($order_arr['status'] == 'new' || $order_arr['status'] == 'error') {
            $created_date = date('Y-m-d G:i:s');
            $admin_id = $this->session->userdata('admin_id');
            //$application_mode = $this->session->userdata('global_mode');

            $is_submitted = 'no';
            $upd_data = array(
                'symbol' => $coin,
                'price' => $price,
                'quantity' => $quantity,
                'order_type' => $order_type,
                'admin_id' => $admin_id,
                'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                //'application_mode' => $application_mode,
                'trigger_type' => 'no',
            );

            if ($trail_check != '') {
                $upd_data['trail_check'] = 'yes';
                $upd_data['trail_interval'] = $trail_interval;
                $upd_data['buy_trail_price'] = $price;
                $upd_data['status'] = 'new';
            } else {
                $upd_data['trail_check'] = 'no';
                $upd_data['trail_interval'] = '0';
                $upd_data['buy_trail_price'] = '0';
                $upd_data['status'] = 'new';
            }

            if ($auto_sell == 'yes') {
                $upd_data['auto_sell'] = 'yes';
            } else {
                $upd_data['auto_sell'] = 'no';
            }

            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_data);

            //Update data in mongoTable
            $this->mongo_db->update('buy_orders');

            ////////////////////////////// Auto Sell////////////////////////////
            if ($auto_sell == 'yes') {
                if ($temp_sell_id != '') {
                    $upd_temp_data = array(
                        'profit_type' => $profit_type,
                        'profit_percent' => $sell_profit_percent,
                        'profit_price' => $sell_profit_price,
                        'order_type' => $sell_order_type,
                        'trail_check' => $sell_trail_check,
                        'trail_interval' => $sell_trail_interval,
                        'stop_loss' => $stop_loss,
                        'loss_percentage' => $loss_percentage,
                    );

                    $this->mongo_db->where(array('_id' => $temp_sell_id));
                    $this->mongo_db->set($upd_temp_data);

                    //Update data in mongoTable
                    $this->mongo_db->update('temp_sell_orders');
                } else {
                    $ins_temp_data = array(
                        'buy_order_id' => $this->mongo_db->mongoId($id),
                        'profit_type' => $profit_type,
                        'profit_percent' => $sell_profit_percent,
                        'profit_price' => $sell_profit_price,
                        'order_type' => $sell_order_type,
                        'trail_check' => $sell_trail_check,
                        'trail_interval' => $sell_trail_interval,
                        'stop_loss' => $stop_loss,
                        'loss_percentage' => $loss_percentage,
                        'admin_id' => $admin_id,
                        'created_date' => $this->mongo_db->converToMongodttime($created_date),
                        'modified_date' => $this->mongo_db->converToMongodttime($created_date),
                    );

                    //Insert data in mongoTable
                    $this->mongo_db->insert('temp_sell_orders', $ins_temp_data);
                }
            }
            //////////////////////////////// End Auto Sell//////////////////////

            //////////////////////////////////////////////////////////////////////////////
            ////////////////////////////// Order History Log /////////////////////////////
            $log_msg = 'Buy Order was Updated';
            $this->insert_order_history_log($id, $log_msg, 'buy_updated', $admin_id);
            ////////////////////////////// End Order History Log /////////////////////////
            //////////////////////////////////////////////////////////////////////////////

            return true;
        } else {
            return false;
        } //End if Order is New
    }

    //end edit_buy_order

    //delete_buy_order_backup
    public function delete_buy_order_backup($id, $order_id)
    {
        $admin_id = $this->session->userdata('admin_id');
        $global_symbol = $this->session->userdata('global_symbol');

        // $this->mongo_db->where(array('_id'=> $id));

        // //Delete data in mongoTable
        //  $this->mongo_db->delete('buy_orders');

        if ($order_id != '') {
            //Binance Cancel Order
            $order = $this->binance_api->cancel_order($global_symbol, $order_id);
        }

        $upd_data = array(
            'status' => 'canceled',
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('buy_orders');

        //////////////////////////////////////////////////////////////////////////////
        ////////////////////////////// Order History Log /////////////////////////////
        $log_msg = 'Buy Order was Canceled';
        $this->insert_order_history_log($id, $log_msg, 'buy_canceled', $admin_id);
        ////////////////////////////// End Order History Log /////////////////////////
        //////////////////////////////////////////////////////////////////////////////

        return true;
    }

    //end delete_buy_order_backup

    //delete_buy_order         **** Test Order *****
    public function delete_buy_order($id, $order_id)
    {
        $admin_id = $this->session->userdata('admin_id');
        $global_symbol = $this->session->userdata('global_symbol');
        $created_date = date('Y-m-d H:i:s');
        $upd_data = array(
            'status' => 'canceled',
            'modified_date' => $this->mongo_db->converToMongodttime($created_date),
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('buy_orders');

        //////////////////////////////////////////////////////////////////////////////
        ////////////////////////////// Order History Log /////////////////////////////
        $log_msg = 'Buy Order was Canceled';
        $this->insert_order_history_log($id, $log_msg, 'buy_canceled', $admin_id);
        ////////////////////////////// End Order History Log /////////////////////////
        //////////////////////////////////////////////////////////////////////////////

        return true;
    }

    //end delete_buy_order

    public function count_child_buy_order($id){

        $mongoID = $this->mongo_db->mongoId($id);
        $this->mongo_db->where(array('buy_parent_id' => $mongoID));
        $get = $this->mongo_db->get('buy_orders');
        $get_arr = iterator_to_array($get);
        $count = count($get_arr);

        $this->mongo_db->where(array('buy_parent_id' => $mongoID));
        $get1 = $this->mongo_db->get('sold_buy_orders');
        $get_arr1 = iterator_to_array($get1);

        $count += count($get_arr1);

        return $count;
    }
    
    //count_child_buy_order_exchange //Umer Abbas [7-11-19]
    public function count_child_buy_order_exchange($id, $exchange=''){

        if(!empty($exchange)){
            if($exchange != 'binance'){
                $exchange = "_$exchange";
            }else{
                $exchange = "";
            }
        }

        $mongoID = $this->mongo_db->mongoId($id);
        $this->mongo_db->where(array('buy_parent_id' => $mongoID));
        $get = $this->mongo_db->get("buy_orders$exchange");
        $get_arr = iterator_to_array($get);
        $count = count($get_arr);

        $this->mongo_db->where(array('buy_parent_id' => $mongoID));
        $get1 = $this->mongo_db->get("sold_buy_orders$exchange");
        $get_arr1 = iterator_to_array($get1);

        $count += count($get_arr1);

        return $count;
    }//end count_child_buy_order_exchange

    //get_buy_order_exchange //Umer Abbas [7-11-19]
    public function get_buy_order_exchange($id, $exchange=''){

        if(!empty($exchange)){
            if($exchange != 'binance'){
                $exchange = "_$exchange";
            }else{
                $exchange = "";
            }
        }

        $timezone = $this->session->userdata('timezone');
        if (empty($timezone)) {
            $timezone = 'ASIA/KARACHI';
        }
        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get("buy_orders$exchange");
        $final_resp_arr = iterator_to_array($responseArr);
        if (count($final_resp_arr) == 0) {
            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->sort(array('_id' => 'desc'));
            $responseArr = $this->mongo_db->get("sold_buy_orders$exchange");
            $final_resp_arr = iterator_to_array($responseArr);
        }
        foreach ($final_resp_arr as $valueArr) {
            $returArr = array();
            if (!empty($valueArr)) {
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
                $datetime2 = $valueArr['modified_date']->toDateTime();
                $created_date2 = $datetime2->format(DATE_RSS);

                $datetime2 = new DateTime($created_date2);
                $datetime2->format('Y-m-d g:i:s A');
                $datetime2->setTimezone($new_timezone);
                $formated_date_time2 = $datetime2->format('Y-m-d g:i:s A');

                $returArr['_id'] = $valueArr['_id'];
                $returArr['symbol'] = $valueArr['symbol'];
                if($exchange == 'binance'){
                    $returArr['binance_order_id'] = $valueArr['binance_order_id'];
                }else{
                    $returArr['kraken_order_id'] = $valueArr['kraken_order_id'];
                }
                
                $returArr['price'] = $valueArr['price'];
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['market_value'] = num($valueArr['market_value']);
                $returArr['order_type'] = $valueArr['order_type'];
                $returArr['status'] = $valueArr['status'];
                $returArr['admin_id'] = $valueArr['admin_id'];
                $returArr['trail_check'] = $valueArr['trail_check'];
                $returArr['buy_trail_price'] = $valueArr['buy_trail_price'];
                $returArr['trail_interval'] = $valueArr['trail_interval'];
                $returArr['is_sell_order'] = $valueArr['is_sell_order'];
                $returArr['market_sold_price'] = num($valueArr['market_sold_price']);
                $returArr['sell_order_id'] = $valueArr['sell_order_id'];
                $returArr['auto_sell'] = $valueArr['auto_sell'];
                $returArr['trigger_type'] = $valueArr['trigger_type'];
                $returArr['modified_date'] = $formated_date_time2;
                $returArr['application_mode'] = $valueArr['application_mode'];
                $returArr['inactive_status'] = $valueArr['inactive_status'];
                $returArr['inactive_time'] = $valueArr['inactive_time'];
                $returArr['parent_status'] = $valueArr['parent_status'];
                $returArr['order_type'] = $valueArr['order_type'];

                $returArr['buy_rule_number'] = $valueArr['buy_rule_number'];
                $returArr['sell_rule_number'] = $valueArr['sell_rule_number'];
                $returArr['is_manual_sold'] = $valueArr['is_manual_sold'];
                $returArr['opportunityId'] = (!empty($valueArr['opportunityId'])? $valueArr['opportunityId'] : '');

                $returArr['buy_parent_id'] = $valueArr['buy_parent_id'];
                $returArr['custom_stop_loss_percentage'] = $valueArr['custom_stop_loss_percentage'];
                $returArr['stop_loss_rule'] = $valueArr['stop_loss_rule'];
                $returArr['sell_profit_percent'] = $valueArr['sell_profit_percent'];
                $returArr['sell_price'] = $valueArr['sell_price'];
                $returArr['order_mode'] = $valueArr['order_mode'];
                $returArr['updated_quantity'] = $valueArr['update_quantity_after_commision_deducted'];
                $returArr['created_date'] = $formated_date_time;
            }
        }

        return $returArr;
    }//end get_buy_order_exchange

    //get_buy_order
    public function get_buy_order($id)
    {
        $timezone = $this->session->userdata('timezone');
        if (empty($timezone)) {
            $timezone = 'ASIA/KARACHI';
        }
        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('buy_orders');
        $final_resp_arr = iterator_to_array($responseArr);
        if (count($final_resp_arr) == 0) {
            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->sort(array('_id' => 'desc'));
            $responseArr = $this->mongo_db->get('sold_buy_orders');
            $final_resp_arr = iterator_to_array($responseArr);
        }
        foreach ($final_resp_arr as $valueArr) {
            $returArr = array();
            if (!empty($valueArr)) {
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
                $datetime2 = $valueArr['modified_date']->toDateTime();
                $created_date2 = $datetime2->format(DATE_RSS);

                $datetime2 = new DateTime($created_date2);
                $datetime2->format('Y-m-d g:i:s A');
                $datetime2->setTimezone($new_timezone);
                $formated_date_time2 = $datetime2->format('Y-m-d g:i:s A');

                $returArr['_id'] = $valueArr['_id'];
                $returArr['symbol'] = $valueArr['symbol'];
                $returArr['binance_order_id'] = $valueArr['binance_order_id'];
                $returArr['price'] = $valueArr['price'];
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['market_value'] = num($valueArr['market_value']);
                $returArr['order_type'] = $valueArr['order_type'];
                $returArr['status'] = $valueArr['status'];
                $returArr['admin_id'] = $valueArr['admin_id'];
                $returArr['trail_check'] = $valueArr['trail_check'];
                $returArr['buy_trail_price'] = $valueArr['buy_trail_price'];
                $returArr['trail_interval'] = $valueArr['trail_interval'];
                $returArr['is_sell_order'] = $valueArr['is_sell_order'];
                $returArr['market_sold_price'] = num($valueArr['market_sold_price']);
                $returArr['sell_order_id'] = $valueArr['sell_order_id'];
                $returArr['auto_sell'] = $valueArr['auto_sell'];
                $returArr['trigger_type'] = $valueArr['trigger_type'];
                $returArr['modified_date'] = $formated_date_time2;
                $returArr['application_mode'] = $valueArr['application_mode'];
                $returArr['inactive_status'] = $valueArr['inactive_status'];
                $returArr['inactive_time'] = $valueArr['inactive_time'];
                $returArr['parent_status'] = $valueArr['parent_status'];

                $returArr['buy_rule_number'] = $valueArr['buy_rule_number'];
                $returArr['sell_rule_number'] = $valueArr['sell_rule_number'];
                $returArr['is_manual_sold'] = $valueArr['is_manual_sold'];
                $returArr['opportunityId'] = (!empty($valueArr['opportunityId']) ? $valueArr['opportunityId'] : '');

                $returArr['buy_parent_id'] = $valueArr['buy_parent_id'];
                $returArr['order_mode'] = $valueArr['order_mode'];
                $returArr['updated_quantity'] = $valueArr['update_quantity_after_commision_deducted'];
                $returArr['created_date'] = $formated_date_time;
            }
        }

        return $returArr;
    }//end get_buy_order

    //end get_buy_order

    //get_buy_orders
    public function get_buy_orders()
    {
        $admin_id = $this->session->userdata('admin_id');

        //Check Filter Data
        $session_post_data = $this->session->userdata('filter-data-buy');

        $search_array = array('admin_id' => $admin_id);
        if ($session_post_data['filter_coin'] != '') {
            $symbol = $session_post_data['filter_coin'];
            $search_array['symbol'] = $symbol;
        }
        if ($session_post_data['filter_type'] != '') {
            $order_type = $session_post_data['filter_type'];
            $search_array['order_type'] = $order_type;
        }
        if ($session_post_data['start_date'] != '' && $session_post_data['end_date'] != '') {
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

        $this->mongo_db->where($search_array);
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('buy_orders');

        $fullarray = array();
        $total_sold_orders = 0;
        $total_buy_amount = 0;
        $total_sell_amount = 0;
        $total_profit = 0;
        $total_quantity = 0;
        foreach ($responseArr as $valueArr) {
            $returArr = array();

            if (!empty($valueArr)) {
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $returArr['_id'] = $valueArr['_id'];
                $returArr['symbol'] = $valueArr['symbol'];
                $returArr['binance_order_id'] = $valueArr['binance_order_id'];
                $returArr['price'] = $valueArr['price'];
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['order_type'] = $valueArr['order_type'];
                $returArr['market_value'] = num($valueArr['market_value']);
                $returArr['trail_check'] = $valueArr['trail_check'];
                $returArr['trail_interval'] = $valueArr['trail_interval'];
                $returArr['buy_trail_price'] = $valueArr['buy_trail_price'];
                $returArr['status'] = $valueArr['status'];
                $returArr['is_sell_order'] = $valueArr['is_sell_order'];
                $returArr['market_sold_price'] = num($valueArr['market_sold_price']);
                $returArr['sell_order_id'] = $valueArr['sell_order_id'];
                $returArr['admin_id'] = $valueArr['admin_id'];
                $returArr['application_mode'] = $valueArr['application_mode'];
                $returArr['created_date'] = $formated_date_time;

                if ($valueArr['status'] == 'FILLED') {
                    $total_buy_amount += num($valueArr['market_value']);
                }

                if ($valueArr['is_sell_order'] == 'sold') {
                    ++$total_sold_orders;
                    $total_sell_amount += num($valueArr['market_sold_price']);
                }

                if ($valueArr['is_sell_order'] == 'sold') {
                    $market_sold_price = $valueArr['market_sold_price'];
                    $current_order_price = $valueArr['market_value'];
                    $quantity = $valueArr['quantity'];

                    $current_data2222 = $market_sold_price - $current_order_price;
                    $profit_data = ($current_data2222 * 100 / $market_sold_price);

                    $profit_data = number_format((float) $profit_data, 2, '.', '');

                    $total_profit += $quantity * $profit_data;
                    $total_quantity += $quantity;
                }
            }

            $fullarray[] = $returArr;
        }

        $avg_profit = $total_profit / $total_quantity;

        $return_data['fullarray'] = $fullarray;
        $return_data['total_buy_amount'] = num($total_buy_amount);
        $return_data['total_sell_amount'] = num($total_sell_amount);
        $return_data['total_sold_orders'] = $total_sold_orders;
        $return_data['avg_profit'] = number_format($avg_profit, 2, '.', '');

        return $return_data;
    }

    //end get_buy_orders

    //get_buy_active_orders
    public function get_buy_active_orders()
    {
        $admin_id = $this->session->userdata('admin_id');

        //Check Filter Data
        $session_post_data = $this->session->userdata('filter-data-buy');

        $search_array = array('admin_id' => $admin_id, 'status' => 'new');

        if ($session_post_data['filter_coin'] != '') {
            $symbol = $session_post_data['filter_coin'];
            $search_array['symbol'] = $symbol;
        }
        if ($session_post_data['filter_type'] != '') {
            $order_type = $session_post_data['filter_type'];
            $search_array['order_type'] = $order_type;
        }
        if ($session_post_data['start_date'] != '' && $session_post_data['end_date'] != '') {
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

        $this->mongo_db->where($search_array);
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('buy_orders');

        $fullarray = array();
        foreach ($responseArr as $valueArr) {
            $returArr = array();

            if (!empty($valueArr)) {
                $datetime = $valueArr['created_date']->toDateTime();
                $created_date = $datetime->format(DATE_RSS);

                $datetime = new DateTime($created_date);
                $datetime->format('Y-m-d g:i:s A');

                $new_timezone = new DateTimeZone('Asia/Karachi');
                $datetime->setTimezone($new_timezone);
                $formated_date_time = $datetime->format('Y-m-d g:i:s A');

                $returArr['_id'] = $valueArr['_id'];
                $returArr['symbol'] = $valueArr['symbol'];
                $returArr['price'] = $valueArr['price'];
                $returArr['quantity'] = $valueArr['quantity'];
                $returArr['market_value'] = $valueArr['market_value'];
                $returArr['trail_check'] = $valueArr['trail_check'];
                $returArr['trail_interval'] = $valueArr['trail_interval'];
                $returArr['buy_trail_price'] = $valueArr['buy_trail_price'];
                $returArr['status'] = $valueArr['status'];
                $returArr['admin_id'] = $valueArr['admin_id'];
                $returArr['application_mode'] = $valueArr['application_mode'];
                $returArr['created_date'] = $formated_date_time;
            }

            $fullarray[] = $returArr;
        }

        return $fullarray;
    }

    //end get_buy_active_orders

    //sell_order
    public function sell_order($id, $market_value)
    {
        $upd_data = array(
            'status' => 'sell',
            'market_value' => $market_value,
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('orders');

        return true;
    }

    //end sell_order

    //sell_all_orders
    public function sell_all_orders()
    {
        //Get all Sell Orders
        $orders_arr = $this->get_sell_active_orders();

        if (count($orders_arr) > 0) {
            foreach ($orders_arr as $key => $value) {
                $id = $value['_id'];
                $quantity = $value['quantity'];
                $symbol = $value['symbol'];
                $user_id = $value['admin_id'];
                $buy_order_id = $value['buy_order_id'];
                $application_mode = $value['application_mode'];

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($symbol);

                //////////////////Auto Sell Binance Market Order////////////////////
                if ($application_mode == 'live') {
                    $this->binance_sell_auto_market_order_live($id, $quantity, $market_value, $symbol, $user_id, $buy_order_id);
                } else {
                    $this->binance_sell_auto_market_order_test($id, $quantity, $market_value, $symbol, $user_id, $buy_order_id);
                }
                ///////////////////////////////////////////////////////////////////
            }
        }

        return true;
    }

    //end sell_all_orders

    //buy_order
    public function buy_order($id, $market_value)
    {
        $upd_data = array(
            'status' => 'buy',
            'market_value' => $market_value,
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('buy_orders');

        return true;
    }

    //end buy_order

    //buy_all_orders
    public function buy_all_orders()
    {
        //Get All Buy Orders
        $orders_arr = $this->get_buy_active_orders();

        if (count($orders_arr) > 0) {
            foreach ($orders_arr as $key => $value) {
                $id = $value['_id'];
                $quantity = $value['quantity'];
                $symbol = $value['symbol'];
                $user_id = $value['admin_id'];
                $application_mode = $value['application_mode'];

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($symbol);

                //////////////////Auto Buy Binance Market Order///////////////////
                if ($application_mode == 'live') {
                    $this->binance_buy_auto_market_order_live($id, $quantity, $market_value, $symbol, $user_id);
                } else {
                    $this->binance_buy_auto_market_order_test($id, $quantity, $market_value, $symbol, $user_id);
                }
                //////////////////////////////////////////////////////////////////
            }
        }

        return true;
    }

    //end buy_all_orders

    public function get_candelstick_data()
    {
        $this->mongo_db->limit(25);
        $this->mongo_db->sort(array('created_date' => 'desc'));
        $responseArr = $this->mongo_db->get('market_chart');

        foreach ($responseArr as $val_arr) {
            $final_arr[] = array(
                'close' => $val_arr['close'],
                'open' => $val_arr['open'],
                'high' => $val_arr['high'],
                'low' => $val_arr['low'],
                'volume' => $val_arr['volume'],
                'openTime' => $val_arr['openTime'],
                'closeTime' => $val_arr['closeTime'],
            );
        }

        return $final_arr;
        exit;

        $global_symbol = $this->session->userdata('global_symbol');

        $this->mongo_db->where(array('coin' => $global_symbol, 'periods' => '1h'));
        $this->mongo_db->limit(90);
        $this->mongo_db->sort(array('created_date' => 'desc'));
        $responseArr = $this->mongo_db->get('market_chart');
        $final_arr = array();
        foreach ($responseArr as $val_arr) {
            $final_arr[] = array(
                'close' => $val_arr['close'],
                'open' => $val_arr['open'],
                'high' => $val_arr['high'],
                'low' => $val_arr['low'],
                'volume' => $val_arr['volume'],
                'openTime' => $val_arr['openTime'],
                'closeTime' => $val_arr['closeTime'],
            );
        }
        // echo "<pre>";
        // print_r($final_arr);
        // exit;
        return $final_arr;
    }

    //end get_candelstick_data

    public function get_all_orders()
    {
        $global_symbol = $this->session->userdata('global_symbol');

        $all_orders = $this->binance_api->get_all_orders($global_symbol);

        echo '<pre>';
        print_r($all_orders);
        exit;

        for ($i = 0; $i < count($all_orders); ++$i) {
            $binance_order_id = $all_orders[$i]['orderId'];
            $price = $all_orders[$i]['price'];
            $status = $all_orders[$i]['status'];

            $upd_data = array(
                'market_value' => $price,
                'status' => $status,
            );

            $this->mongo_db->where(array('binance_order_id' => $binance_order_id));
            $this->mongo_db->set($upd_data);

            //Update data in mongoTable
            $this->mongo_db->update('buy_orders');
        }

        return true;
    }

    //end get_all_orders

    public function get_sell_order_status($id, $order_id)
    {
        //Check Orders
        $order_arr = $this->get_order($id);
        $symbol = $order_arr['symbol'];

        $order_data = $this->binance_api->order_status($symbol, $order_id);

        if ($order_data['status'] == 'NEW') {
            $status = 'submitted';
        } else {
            $status = $order_data['status'];
        }

        $upd_data = array(
            'status' => $status,
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('orders');

        if ($order_data['status'] == 'FILLED') {
            $buy_order_check = $order_arr['buy_order_check'];
            $buy_order_id = $order_arr['buy_order_id'];
            $market_sold_price = $order_arr['market_value'];

            if ($buy_order_id != '') {
                //Update Buy Order
                $upd_data22 = array(
                    'is_sell_order' => 'sold',
                    'market_sold_price' => $market_sold_price,
                );

                $this->mongo_db->where(array('_id' => $buy_order_id));
                $this->mongo_db->set($upd_data22);

                //Update data in mongoTable
                $this->mongo_db->update('buy_orders');
            }
        }

        return true;
    }

    //end get_sell_order_status

    public function get_buy_order_status($id, $order_id)
    {
        //Check Orders
        $order_arr = $this->get_buy_order($id);
        $symbol = $order_arr['symbol'];

        $order_data = $this->binance_api->order_status($symbol, $order_id);

        if ($order_data['status'] == 'NEW') {
            $status = 'submitted';
        } else {
            $status = $order_data['status'];
        }

        $upd_data = array(
            'status' => $status,
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('buy_orders');

        return true;
    }

    //end get_buy_order_status

    //get_market_value

    public function get_market_value_bam($symbol = '')
    {
        if ($symbol != '') {
            $global_symbol = $symbol;
        } else {
            $global_symbol = $this->session->userdata('global_symbol');
        }
        //Get Market Prices
        $this->mongo_db->where(array('coin' => $global_symbol));
        $this->mongo_db->limit(1);
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('market_prices_bam');
        $price = iterator_to_array($responseArr);

        if (!empty($price)) {
            return num($price[0]['price']);
        } else {
            return 0;
        }
    }

    //End get_market_value_bam

    public function get_market_value($symbol = '')
    {
        if ($symbol != '') {
            $global_symbol = $symbol;
        } else {
            $global_symbol = $this->session->userdata('global_symbol');
        }

        //Get Market Prices
        $this->mongo_db->where(array('coin' => $global_symbol));
        $this->mongo_db->limit(1);
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('market_prices');
        $price = iterator_to_array($responseArr);

        if (!empty($price)) {
            return num($price[0]['price']);
        } else {
            return 0;
        }
    }

    //End get_market_value

    public function get_market_value2($symbol = '', $exchange){

        if($exchange == 'bam'){
            $collectionName = 'market_prices_bam';
        }else{
            $collectionName = $exchange == "binance" ? "market_prices" : "market_prices_$exchange";
        }
        //Get Market Prices
        $this->mongo_db->where(array('coin' => $symbol));
        $this->mongo_db->limit(1);
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get($collectionName);
        $price = iterator_to_array($responseArr);

        if (!empty($price)) {
            return num($price[0]['price']);
        } else {
            return 0;
        }
    }

    //check_buy_zones
    public function check_buy_zones($market_value, $symbol = '')
    {
        if ($symbol != '') {
            $global_symbol = $symbol;
        } else {
            $global_symbol = $this->session->userdata('global_symbol');
        }

        $priceAsk = num((float) $market_value);

        $db = $this->mongo_db->customQuery();

        $params = array(
            'start_value' => array(
                '$gte' => $priceAsk,
            ),
            'end_value' => array(
                '$lte' => $priceAsk,
            ),
        );

        $res = $db->chart_target_zones->find($params);

        $in_zone = 'no';
        foreach ($res as $valueArr) {
            if (!empty($valueArr)) {
                $in_zone = 'yes';
                $type = $valueArr['type'];
                $start_value = $valueArr['start_value'];
                $end_value = $valueArr['end_value'];
            } else {
                $in_zone = 'no';
                $type = '';
                $start_value = '';
                $end_value = '';
            }
        }

        $data['in_zone'] = $in_zone;
        $data['type'] = $type;
        $data['start_value'] = $start_value;
        $data['end_value'] = $end_value;

        return $data;
    }

    //End check_buy_zones

    //get_coin_balance
    public function get_coin_balance($symbol = '')
    {
        if ($symbol != '') {
            $global_symbol = $symbol;
        } else {
            $global_symbol = $this->session->userdata('global_symbol');
        }
        $admin_id = $this->session->userdata('admin_id');

        $this->mongo_db->where(array('user_id' => $admin_id, 'symbol' => $symbol));
        $get_coins = $this->mongo_db->get('coins');
        $coins_arr = iterator_to_array($get_coins);
        $coins_arr = $coins_arr[0];

        return $coins_arr['coin_balance'];
    }

    //End get_coin_balance

    //get_balance
    public function get_balance($global_symbol, $admin_id)
    {
        $this->mongo_db->where(array('user_id' => $admin_id, 'symbol' => $global_symbol));
        $get_coins = $this->mongo_db->get('coins');
        $coins_arr = iterator_to_array($get_coins);
        $coins_arr = $coins_arr[0];

        return $coins_arr['coin_balance'];
    }

    //End get_balance

    //get_notifications
    public function get_notifications($id)
    {
        if ($id != 0) {
            $upd_data = array('status' => 1);
            //Update the record into the database.

            $this->mongo_db->where('id', $id);
            $this->mongo_db->update('notification', $upd_data);
        }

        $admin_id = $this->session->userdata('admin_id');
        $start_date = date('Y-m-d H:i:s', strtotime('-5 minutes'));
        $end_date = date('Y-m-d H:i:s');

        $this->mongo_db->order_by(array('_id' => -1));
        $this->mongo_db->where_lte('created_date', $end_date);
        $this->mongo_db->where_gte('created_date', $start_date);
        $this->mongo_db->where(array('admin_id' => $admin_id, 'status' => '0'));
        $resp = $this->mongo_db->get('notification');

        $notification_arr = iterator_to_array($resp);

        return $notification_arr;
    }

    //End get_notifications

    //add_notification
    public function add_notification($order_id, $type, $message, $admin_id)
    {
        extract($data);
        $created_date = date('Y-m-d G:i:s');
        $ins_data = array(
            'admin_id' => (trim($admin_id)),
            'order_id' => (trim($order_id)),
            'type' => (trim($type)),
            'message' => (trim($message)),
            'status' => (trim('0')),
            'created_date' => (trim($created_date)),
        );

        //Insert the record into the database.
        $this->mongo_db->insert('notification', $ins_data);

        return true;
    }

    //end add_notification()
    //add_notification_for_app
    public function add_notification_for_app($order_id = '', $type = '', $priority = '', $message = '', $admin_id = '', $symbol = '')
    {
        extract($data);
        if ($symbol != '') {
            $this->load->model('admin/mod_coins');
            $coin_logo = $this->mod_coins->get_coin_logo($symbol);
        } elseif ($type == 'security_alerts' || $type == 'security_alert') {
            $coin_logo = 'security_alert.png';
        } else {
            $coin_logo = '';
        }
        $created_date = date('Y-m-d G:i:s');
        $ins_data = array(
            'admin_id' => (trim($admin_id)),
            'order_id' => (trim($order_id)),
            'type' => (trim($type)),
            'priority' => (trim($priority)),
            'message' => (trim($message)),
            'symbol' => (trim($symbol)),
            'coin_logo' => (trim($coin_logo)),
            'created_date_human_readable' => (trim($created_date)),
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
        );

        //Insert the record into the database.
        $this->mongo_db->insert('app_notification', $ins_data);
        $this->send_push_notification($admin_id, $type, $message);

        return true;
    }

    //end add_notification_for_app()

    public function send_push_notification($admin_id, $type, $message)
    {
        $this->load->library('push_notifications');
        $tokens = $this->get_device_token($admin_id);
        //$user_info = $this->get_user_settings($admin_id);
        foreach ($tokens as $value) {
            if (!empty($value)) {
                if ($value['device_type'] == 'android') {
                    $device_token = $value['device_token'];
                    $data['title'] = ucfirst(str_replace('_', ' ', $type)).' Notification';
                    $data['msg_desc'] = strip_tags($message);
                    $push = $this->push_notifications->android_notification($data, $device_token);
                } elseif ($value['device_type'] == 'ios') {
                    $device_token = $value['device_token'];
                    $data['title'] = ucfirst(str_replace('_', ' ', $type)).' Notification';
                    $data['msg_desc'] = strip_tags($message);
                    $push = $this->push_notifications->iOS($data, $device_token);
                }
            }
        }
    }

    public function get_device_token($admin_id)
    {
        $this->mongo_db->where(array('admin_id' => $admin_id));
        $get = $this->mongo_db->get('users_device_tokens');

        return iterator_to_array($get);
    }

    public function get_user_settings($admin_id)
    {
        $this->mongo_db->where(array('_id' => $admin_id));
        $get = $this->mongo_db->get('users');
        /*
        $sett_arr['buy_alerts']
        $sett_arr['news_alerts']
        $sett_arr['security_alerts']
        $sett_arr['sell_alerts']
        $sett_arr['trading_alerts']
        $sett_arr['withdraw_alerts']
         */
        $sett_arr = iterator_to_array($get);
        $ret_arr = array(
            'buy_alerts' => $sett_arr['buy_alerts'],
            'news_alerts' => $sett_arr['news_alerts'],
            'security_alerts' => $sett_arr['security_alerts'],
            'sell_alerts' => $sett_arr['sell_alerts'],
            'trading_alerts' => $sett_arr['trading_alerts'],
            'withdraw_alerts' => $sett_arr['withdraw_alerts'],
        );

        return iterator_to_array($ret_arr);
    }

    public function buy_trigger_1_process()
    {
        $this->load->model('mod_candel', 'mod_candel');
        $this->mongo_db->where('status', 'new');
        $this->mongo_db->where('trigger_type', 'trigger_1');
        $this->mongo_db->where_ne('buy_order_status_new_filled', 'wait_for_buyed');

        $buy_orders_result = $this->mongo_db->get('buy_orders');
        $buy_orders_arr = iterator_to_array($buy_orders_result);

        $market_value = $this->mod_dashboard->get_market_value();

        $count_log = 0;

        $response_arr = [];

        $response_arr['message_create_new_buy'] = '';
        $response_arr['iniatial_trail_stop'] = '';

        if (count($buy_orders_arr) > 0) {
            foreach ($buy_orders_arr as $buy_orders_row) {
                $coin_symbol = $buy_orders_row['symbol'];

                $date = date('Y-m-d H:00:00', strtotime('-0 hour'));

                $res = $this->mongo_db->get('buy_trigger_1_process_log');
                $result_log = iterator_to_array($res);

                $count_log = 0;

                if (count($result_log)) {
                    $start_time = $result_log[0]['date'];

                    $date = date('Y-m-d H:00:00', strtotime('+0 hour', strtotime($start_time)));
                }

                echo $date.'<br>';

                //$date ='2018-06-10 16:00:00';

                /////////////////
                /////////////////

                $candle_type = '';

                $res = $this->mongo_db->get('buy_trigger_1_process_log');
                $result_log = iterator_to_array($res);

                if (count($result_log)) {
                    $count_log = $result_log[0]['count'];

                    $count_log = $count_log + 1;
                }

                //////////////////////
                //////////////////////

                $buy_trigger_result = $this->mod_candel->buy_trigger_1($coin_symbol, $date);

                $quantity = $buy_orders_row['quantity'];

                $coin = $buy_orders_row['symbol'];
                $parent_order_id = $buy_orders_row['_id'];
                $trigger_type = $buy_orders_row['trigger_type'];
                $admin_id = $buy_orders_row['admin_id'];

                if (count($buy_trigger_result) > 0) {
                    if ($buy_trigger_result['reponse_result']) {
                        echo 'previous is demand candel<br>';

                        $candle_type = 'Demand Candel';

                        $buy_part_one = $buy_trigger_result['value_22'];
                        $buy_part_two = $buy_trigger_result['value_56'];
                        $buy_part_three = $buy_trigger_result['value_82'];

                        $current_low_value = $buy_trigger_result['current_low_value'];
                        $current_open_value = $buy_trigger_result['current_open_value'];

                        $iniatial_trail_stop = $buy_trigger_result['iniatial_trail_stop'];

                        $response_arr['iniatial_trail_stop'] = $iniatial_trail_stop;

                        $market_value = $current_low_value;

                        // echo '$buy_part_one -->'.$buy_part_one .'--->$buy_part_two-->'.$buy_part_two.'--->$buy_part_three-->'.$buy_part_three.'-->'.$market_value;
                        // exit();

                        $buy_success = false;

                        if ($current_low_value != 0 && ($current_low_value <= $buy_part_one || $current_open_value <= $buy_part_one)) {
                            $buy_part = 'one';

                            $buy_order_status_new_filled = 'buyed_filled';

                            $this->mod_dashboard->add_child_order($quantity, $coin, $admin_id, $trigger_type, $parent_order_id, $buy_part_one, $market_value, $iniatial_trail_stop, $buy_part, $date, $buy_order_status_new_filled);

                            $response_arr['message'] = 'part one buyed successfully';

                            $buy_success = true;
                        } else {
                            $buy_part = 'one';
                            $buy_order_status_new_filled = 'wait_for_buyed';
                            $response_arr['message_create_new_buy'] = 'Set new Buy Order Wait for buy buy_part_one';

                            $this->mod_dashboard->add_child_order($quantity, $coin, $admin_id, $trigger_type, $parent_order_id, $buy_part_one, $market_value, $iniatial_trail_stop, $buy_part, $date, $buy_order_status_new_filled);

                            $buy_success = true;
                        }

                        if ($current_low_value != 0 && ($current_low_value <= $buy_part_two || $current_open_value <= $buy_part_two)) {
                            $buy_part = 'two';
                            $buy_order_status_new_filled = 'buyed_filled';
                            $this->mod_dashboard->add_child_order($quantity, $coin, $admin_id, $trigger_type, $parent_order_id, $buy_part_two, $market_value, $iniatial_trail_stop, $buy_part, $date, $buy_order_status_new_filled);

                            $response_arr['message'] = 'part two buyed successfully';
                            $buy_success = true;
                        } else {
                            $buy_order_status_new_filled = 'wait_for_buyed';
                            $response_arr['message_create_new_buy'] = 'Set new Buy Order Wait for buy buy_part_two';

                            $buy_part = 'two';
                            $this->mod_dashboard->add_child_order($quantity, $coin, $admin_id, $trigger_type, $parent_order_id, $buy_part_two, $market_value, $iniatial_trail_stop, $buy_part, $date, $buy_order_status_new_filled);
                            $buy_success = true;
                        }

                        /////////////// ***************************** /////////////

                        if ($current_low_value != 0 && ($current_low_value <= $buy_part_three || $current_open_value <= $buy_part_three)) {
                            $buy_part = 'three';
                            $buy_order_status_new_filled = 'buyed_filled';
                            $this->mod_dashboard->add_child_order($quantity, $coin, $admin_id, $trigger_type, $parent_order_id, $buy_part_three, $market_value, $iniatial_trail_stop, $buy_part, $date, $buy_order_status_new_filled);

                            $response_arr['message'] = 'part three buyed successfully';
                            $buy_success = true;
                        } else {
                            $buy_order_status_new_filled = 'wait_for_buyed';
                            $response_arr['message_create_new_buy'] = 'Set new Buy Order Wait for buy buy_part_three';

                            $buy_part = 'three';

                            $this->mod_dashboard->add_child_order($quantity, $coin, $admin_id, $trigger_type, $parent_order_id, $buy_part_three, $market_value, $iniatial_trail_stop, $buy_part, $date, $buy_order_status_new_filled);

                            $buy_success = true;
                        }

                        ////////////////********************************************* ///////////////

                        if (!$buy_success) {
                            $response_arr['message'] = 'Market value is Greater Then Buy Price';
                        }
                    } else {
                        $response_arr['message'] = 'Trigger is not match the requirement';
                    }
                } else {
                    $response_arr['message'] = 'No trigger Set';
                }

                $date = date('Y-m-d H:00:00', strtotime('+1 hour', strtotime($date)));
                $upd_array = array('date' => $date, 'count' => $count_log);
                $this->mongo_db->set($upd_array);
                $this->mongo_db->update('buy_trigger_1_process_log');
            }
        } else {
            $response_arr['message'] = 'No Order Found';
        }

        // echo '<pre>';
        // print_r($response_arr);

        $upd_array = array('message' => $response_arr['message'], 'candle_type' => $candle_type, 'message_create_new_buy' => $response_arr['message_create_new_buy'], 'iniatial_trail_stop' => $response_arr['iniatial_trail_stop']);
        $this->mongo_db->set($upd_array);
        $this->mongo_db->update('buy_trigger_1_process_log');

        $res = $this->mongo_db->get('buy_trigger_1_process_log');
        $result_log = iterator_to_array($res);
        $look_forward = 0;
        if (count($result_log)) {
            $look_forward = $result_log[0]['look_forward'];
        }

        if ($count_log != 0) {
            if ($count_log < $look_forward) {
                $this->buy_trigger_1_process();
            }
        }

        return $response_arr;
    }

    //End of buy_trigger_1_process

    public function get_buy_orders_trigger_by_1()
    {
        $this->mongo_db->where_or(array('status' => 'FILLED', 'buy_order_status_new_filled' => 'wait_for_buyed'));
        $this->mongo_db->where('trigger_type', 'trigger_1');
        $buy_orders_result = $this->mongo_db->get('buy_orders');

        return iterator_to_array($buy_orders_result);
    }

    //End of get_buy_orders_trigger_by_1

    public function add_sell_order_by_triggers($coin_symbol, $buy_price, $quantity, $sell_price, $market_value, $iniatial_trail_stop, $status, $admin_id, $order_id, $trigger_type, $sell_order_rule)
    {
        $created_date = date('Y-m-d G:i:s');
        $insert_arr = array(
            'symbol' => $coin_symbol,
            'binance_order_id' => '',
            'purchased_price' => $buy_price,
            'quantity' => $quantity,
            'profit_type' => '',
            'sell_profit_percent' => '',
            'sell_profit_price' => '',

            'sell_price' => $sell_price,
            'market_value' => $market_value,
            'trail_check' => '',
            'trail_interval' => '',
            'sell_trail_price' => '',
            'order_type' => '',
            'stop_loss' => $iniatial_trail_stop,
            'loss_percentage' => '',
            'status' => $status,
            'admin_id' => $admin_id,
            'buy_order_id' => $order_id,
            'buy_order_binance_id' => '',
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
            'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            'trigger_type' => $trigger_type,
            'sell_order_rule' => $sell_order_rule,
        );

        $this->mongo_db->insert('orders', $insert_arr);
    }

    //End of add_sell_order_by_triggers

    public function add_child_order($quantity, $coin, $admin_id, $trigger_type, $parent_order_id, $price, $market_value, $iniatial_trail_stop, $buy_part, $date, $buy_order_status_new_filled)
    {
        if ($buy_order_status_new_filled == 'buyed_filled') {
            $status = 'FILLED';
        } else {
            $status = 'new';
        }

        // $created_date = date('Y-m-d G:i:s');
        $created_date = $date;

        $ins_data = array(
            'price' => $price,
            'quantity' => $quantity,
            'symbol' => $coin,
            'order_type' => '',
            'admin_id' => $admin_id,
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
            'trail_check' => 'NO',
            'trail_interval' => 0,
            'buy_trail_price' => '',
            'status' => $status,
            'auto_sell' => 'yes',
            'market_value' => $market_value,
            'binance_order_id' => '',
            'is_sell_order' => '',
            'sell_order_id' => '',
            'buy_part' => $buy_part,
            'iniatial_trail_stop' => $iniatial_trail_stop,
            'trigger_type' => $trigger_type,
            'parent_order_id' => $parent_order_id,
            'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            'buy_order_status_new_filled' => $buy_order_status_new_filled,
        );

        $buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data);

        ////////////////////////////// Auto Sell////////////////////////////

        $ins_temp_data = array(
            'buy_order_id' => $this->mongo_db->mongoId($buy_order_id),
            'profit_type' => 'Percentage',
            'profit_percent' => '',
            'profit_price' => '',
            'order_type' => '',
            'trail_check' => '',
            'trail_interval' => '',
            'stop_loss' => 'yes',
            'loss_percentage' => '',
            'admin_id' => $admin_id,
            'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
        );

        //Insert data in mongoTable
        $this->mongo_db->insert('temp_sell_orders', $ins_temp_data);

        return true;
    }

    public function get_coin_min_notation($symbol)
    {
        $search_array['symbol'] = $symbol;
        $this->mongo_db->where($search_array);
        $res = $this->mongo_db->get('market_min_notation');

        $min_notation_arr = iterator_to_array($res);

        /*echo "<pre>";
        print_r($min_notation_arr);
         */
        $min_notation = $min_notation_arr[0]->min_notation;

        return $min_notation;
    }

    public function get_time_zone()
    {
        $get_arr = $this->mongo_db->get('timezones');
        $timezones = iterator_to_array($get_arr);

        return $timezones;
    }

    //End of get_time_zone

    public function count_user_parent_orders($user_id)
    {
        $search_array['application_mode'] = 'live';
        $search_array['parent_status'] = 'parent';
        $search_array['admin_id'] = $user_id;
        $search_array['status'] = 'new';
        $search_array['inactive_status'] = array('$ne' => 'inactive');

        $this->mongo_db->where($search_array);
        $get_obj = $this->mongo_db->get('buy_orders');

        $get_arr = iterator_to_array($get_obj);

        return count($get_arr);
    }

    public function get_score_avg($symbol)
    {
        $this->mongo_db->where('coin', $symbol);
        $res = $this->mongo_db->get('coin_meta');
        $get_arr = iterator_to_array($res);

        return $get_arr[0]['score'];
    }

    public function is_min_quantity_notational_error($symbol, $type)
    {
        $search_array['symbol'] = $symbol;
        $this->mongo_db->where($search_array);
        $res = $this->mongo_db->get('market_min_notation');
        $min_notation_arr = iterator_to_array($res);
        $min_notation = $min_notation_arr[0]->min_notation;

        $market_value = $this->mod_dashboard->get_market_value($symbol);
        $min_quantity = $min_notation / $market_value;

        $percentage = 1.1;
        if ($type == 'sell') {
            $percentage = 1.02;
        }

        return $min_required_quantity = $min_quantity * $percentage;
    }

    //End of is_min_notational_error

    public function remove_min_step_size_notational_error($symbol, $quantity)
    {
        $search_array['symbol'] = $symbol;
        $this->mongo_db->where($search_array);
        $res = $this->mongo_db->get('market_min_notation');
        $min_notation_arr = iterator_to_array($res);

        $stepSize = $min_notation_arr[0]->stepSize;
        $flaot_point = $this->numberOfDecimals($stepSize + 0);

        if ($stepSize >= 1) {
            $quantity = floor($quantity);
        } else {
            $quantity = floor($quantity);
            //$quantity  = round($quantity,$flaot_point,PHP_ROUND_HALF_DOWN);
        }

        return $quantity;
    }

    //End of is_min_step_size_notational_error

    public function numberOfDecimals($value)
    {
        if ((int) $value == $value) {
            return 0;
        } elseif (!is_numeric($value)) {
            return false;
        }

        return strlen($value) - strrpos($value, '.') - 1;
    }

    //End of numberOfDecimals

    public function update_user_coin_balance($user_id, $symbol)
    {
        $this->load->model('admin/mod_balance');
        $balance = $this->binance_api->get_account_balance($symbol, $user_id);
        $upd = $this->mod_balance->update_coin_balance($symbol, $balance, $user_id);
        $symbol = 'BTC';
        $balance = $this->binance_api->get_bitcoin_balance($symbol, $user_id);
        $upd = $this->mod_balance->update_coin_balance($symbol, $balance, $user_id);

        $symbol = 'BNBBTC';
        $balance = $this->binance_api->get_account_balance($symbol, $user_id);
        $upd = $this->mod_balance->update_coin_balance($symbol, $balance, $user_id);
    }

    // %%% -- End of update_user_coin_balance -- %%%

    public function pause_parent_order($buy_parent_id, $admin_id)
    {
        $created_date = date('Y-m-d G:i:s');
        $upd_data22 = array(
            'pause_status' => 'pause',
            'modified_date' => $this->mongo_db->converToMongodttime($created_date),
        );
        $this->mongo_db->where(array('_id' => $buy_parent_id));
        $this->mongo_db->set($upd_data22);
        //Update data in mongoTable
        $this->mongo_db->update('buy_orders');

        $log_msg = 'Order <span style="color:orange;    font-size: 14px;"><b>PAUSED</b></span> Due to Low Quantity By System';
        $this->insert_order_history_log($buy_parent_id, $log_msg, 'buy_error', $admin_id);

        $message2 = '<strong>'.$symbol."</strong> Order <span style='color:orange;    font-size: 14px;'><b>PAUSED</b></span> Due to Low Quantity By System";
        $this->add_notification_for_app($buy_parent_id, 'buy_alert', 'low', $message2, $admin_id, $symbol);
    }

    //%%%%%%%% End of pause_parent_order
} //%%% -- End of Model --- %%%%%
