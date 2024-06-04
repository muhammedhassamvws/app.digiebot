<?php
class mod_sockets extends CI_Model {

    function __construct() {

        parent::__construct();

    }

    //get_all_coins
    public function get_all_coins() {
        $this->mongo_db->sort(array('_id' => -1));
        $this->mongo_db->where(array('user_id' => 'global', 'exchange_type' => 'binance'));
        $get_coins = $this->mongo_db->get('coins');
        $coins_arr = iterator_to_array($get_coins);
        return $coins_arr;
    } //end get_all_coins

    public function check_socket_track($type, $symbol) {
        $now_date = date('Y-m-d G:i:s');
        $end_date = $this->mongo_db->converToMongodttime($now_date);
        $last_five_minute = date('Y-m-d G:i:s', strtotime("-5 minutes"));
        $strt_date = $this->mongo_db->converToMongodttime($last_five_minute);

        $this->mongo_db->where(array('symbol' => $symbol, 'type' => $type));
        $this->mongo_db->where_between('run_time', $strt_date, $end_date);
        $get_data = $this->mongo_db->get('sockets_track');
        $data_arr = iterator_to_array($get_data);

        if (count($data_arr) > 0) {
            return "yes";
        } else {
            return "no";
        }
    } //end check_socket_track

    public function update_socket_track($type, $symbol) {
        $created_date = date('Y-m-d G:i:s');
        $final_created = $this->mongo_db->converToMongodttime($created_date);
        $this->mongo_db->where(array('symbol' => $symbol, 'type' => $type));
        $get_data = $this->mongo_db->get('sockets_track');
        $data_arr = iterator_to_array($get_data);
        if (count($data_arr) > 0) {
            $upd_data = array(
                'run_time' => $final_created,
            );
            $id = $data_arr[0]['_id'];
            //Update the record into the database.
            $this->mongo_db->where(array('_id' => $id));
            $this->mongo_db->set($upd_data);
            $this->mongo_db->update('sockets_track', $upd_data);

        } else {
            $ins_data = array(
                'type' => $type,
                'symbol' => $symbol,
                'run_time' => $final_created,
            );
            //Insert the record into the database.
            $this->mongo_db->insert('sockets_track', $ins_data);

        }
        return true;
    } //end update_socket_track

    //update_socket_counter_track
    public function update_socket_counter_track($type, $symbol) {
        $created_date = date('Y-m-d G:i:s');
        $final_created = $this->mongo_db->converToMongodttime($created_date);
        $this->mongo_db->where(array('symbol' => $symbol, 'type' => $type));
        $get_data = $this->mongo_db->get('sockets_track');

        $data_arr = iterator_to_array($get_data);
        if (count($data_arr) == 0) {
            $new_counter = 1;
            $upd_data = array(
                'counter' => (trim($new_counter)),
                'type' => $type,
                'symbol' => $symbol,
                'run_time' => $final_created,
                'last_update_datetime' => $final_created,
            );
            $this->mongo_db->insert('sockets_track', $upd_data);
        } else {
            $counter = $data_arr[0]['counter'];
            $new_counter = $counter + 1;
            $upd_data = array(
                'counter' => (trim($new_counter)),
                'last_update_datetime' => $final_created,
            );
            //Update the record into the database.
            $id = $data_arr[0]['_id'];
            $this->mongo_db->where('_id', $id);
            $this->mongo_db->set($upd_data);
            $this->mongo_db->update('sockets_track', $upd_data);
        }
        return $new_counter;
    } //end update_socket_counter_track

    public function get_current_socket_counter($type, $symbol) {
        $created_date = date('Y-m-d G:i:s');
        $final_created = $this->mongo_db->converToMongodttime($created_date);
        $this->mongo_db->where(array('symbol' => $symbol, 'type' => $type));
        $get_data = $this->mongo_db->get('sockets_track');

        $data_arr = iterator_to_array($get_data);
        $counter = $data_arr[0]['counter'];
        return $counter;
    } //end get_current_socket_counter

    public function count_market_depth() {
        $run = $this->mongo_db->customQuery();
        $count = $run->market_depth->count();
        return $count;
    } //End of count_market_depth

    public function count_market_trade() {
        $run = $this->mongo_db->customQuery();
        $count = $run->market_trades->count();
        return $count;
    } //End of count_market_trade

    public function count_candle_stick_records() {
        $run = $this->mongo_db->customQuery();
        $count = $run->market_chart->count();
        return $count;
    } //End of count_candle_stick_records

    public function delete_market_depth_socket() {
        $get_data = $this->mongo_db->drop_collection('market_depth');
        return $get_data;
    } //End of delete_market_depth_socket

    public function delete_market_trade_socket() {
        $get_data = $this->mongo_db->drop_collection('market_trades');
        return $get_data;
    } //End of delete_market_trade_socket

    public function delete_candle_socket() {
        $custom = $this->mongo_db->customQuery();
        $get_data = $custom->market_chart->deleteMany(array());
        return $get_data;
    } //End of delete_candle_socket

    public function check_candle_stick_data_if_exist($coin_symbol, $period, $openTime) {
        $this->mongo_db->where(array('coin' => $coin_symbol, 'periods' => $period, 'openTime' => $openTime));
        $responseArr = $this->mongo_db->get('market_chart');

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

    public function candle_update($coin_symbol, $period, $openTime, $insert22) {
        $this->mongo_db->where(array('coin' => $coin_symbol, 'periods' => $period, 'openTime' => $openTime));
        $this->mongo_db->set($insert22);
        $this->mongo_db->update('market_chart');

    }/** candle_update***/

    public function update_count_for_duplicating_candle() {
    }

    //
    public function count_candle_stick_repeating() {
    }/** count_candle_stick_repeating*/

    public function delete_candle_repeat() {

    }/** delete_candle_repeat*/

    public function update_count_for_ignore_candle() {
    }/** count_candle_stick_repeating*/

    /************ get_market_history *********/

    public function market_trade_hourly_history() {

        $start_second = strtotime(date("Y-m-d H:00:00", strtotime('-1 hour')));
        $end_second = strtotime(date("Y-m-d H:59:59", strtotime('-1 hour')));
        $current_date = date("Y-m-d H:00:00", strtotime('-1 hour'));

        $start_milli_second = $start_second * 1000;
        $end_milli_second = $end_second * 1000;

        $start_milli_second_obj = new MongoDB\BSON\UTCDateTime($start_milli_second);
        $end_milli_second_obj = new MongoDB\BSON\UTCDateTime($end_milli_second);

        $current_date_milli_second = $current_date * 1000;
        $current_date_milli_second_obj = new MongoDB\BSON\UTCDateTime($current_date_milli_second);

        $pipeline = array(
            '$group' => array('_id' => '$price', 'quantity' => array('$sum' => '$quantity'),
                'maker' => array('$first' => '$maker'),
                'coin' => array('$first' => '$coin'),
                'created_date' => array('$first' => '$created_date'),
                'price' => array('$first' => '$price'),
            ),
        );

        $project = array(
            '$project' => array(
                "_id" => 1,
                "price" => 1,
                "quantity" => 1,
                "maker" => 1,
                "coin" => 1,
                'created_date' => 1,
            ),
        );

        $all_coins_arr = $this->mod_sockets->get_all_coins();

        /*** For ask insertion**/

        foreach ($all_coins_arr as $key => $coins_arr) {

            $coin_symbol = $coins_arr['symbol'];
            $match = array(
                '$match' => array(
                    'coin' => $coin_symbol,
                    'maker' => 'false',
                    'created_date' => array('$gte' => $start_milli_second_obj,
                        '$lte' => $end_milli_second_obj),
                ),
            );

            $connect = $this->mongo_db->customQuery();
            $market_history_Arr = $connect->market_trades->aggregate(array($project, $match, $pipeline));
            $market_history_Arr = iterator_to_array($market_history_Arr);

            foreach ($market_history_Arr as $key => $value) {
                $type = 'ask';
                if ($value['maker'] == 'true') {
                    $type = 'bid';
                }

                $insert_array = array(
                    'coin' => $value['coin'],
                    'hour' => $current_date,
                    'hour_timestamp' => $current_date_milli_second,
                    'price' => (float) $value['price'],
                    'volume' => (float) $value['quantity'],
                    'timestamp' => $value['created_date'],
                    'type' => $type,
                    'maker' => $value['maker'],
                );

                $this->mongo_db->where(array('hour' => $current_date, 'coin' => $value['coin'], 'price' => (float) $value['price'], 'type' => $type));
                $result = $this->mongo_db->get('market_trade_hourly_history');
                $result = iterator_to_array($result);

                if (count($result) > 0) {

                    $this->mongo_db->where(array('hour' => $current_date, 'coin' => $value['coin'], 'price' => (float) $value['price'], 'type' => $type));
                    $this->mongo_db->set($insert_array);
                    //Update data in mongoTable
                    $this->mongo_db->update('market_trade_hourly_history');

                    // echo 'coin updated at ' . $current_date . '--- con' . $value['coin'] . '<br>';
                    // echo '... AAAAAAASSSSSSSSkk.' . '<br>';

                } else {
                    $this->mongo_db->insert('market_trade_hourly_history', $insert_array);
                    // echo 'coin inserted at ' . $current_date . '--- con' . $value['coin'] . '<br>';
                    // echo '... AAAAAAASSSSSSSSkk.' . '<br>';
                }

            }

        }/** End of  for each coin symbol**/

        /***** End of ask insetion****/

        /*** For bid insertion**/

        foreach ($all_coins_arr as $key => $coins_arr) {

            $coin_symbol = $coins_arr['symbol'];

            $match = array(
                '$match' => array(
                    'coin' => $coin_symbol,
                    'maker' => 'true',
                    'created_date' => array('$gte' => $start_milli_second_obj,
                        '$lte' => $end_milli_second_obj),
                ),
            );

            $connect = $this->mongo_db->customQuery();
            $market_history_Arr = $connect->market_trades->aggregate(array($project, $match, $pipeline));
            $market_history_Arr = iterator_to_array($market_history_Arr);
            foreach ($market_history_Arr as $key => $value) {
                $type = 'ask';
                if ($value['maker'] == 'true') {
                    $type = 'bid';
                }

                $insert_array = array(
                    'coin' => $value['coin'],
                    'hour' => $current_date,
                    'hour_timestamp' => $current_date_milli_second,
                    'price' => (float) $value['price'],
                    'volume' => (float) $value['quantity'],
                    'timestamp' => $value['created_date'],
                    'type' => $type,
                    'maker' => $value['maker'],
                );

                $this->mongo_db->where(array('hour' => $current_date, 'coin' => $value['coin'], 'price' => (float) $value['price'], 'type' => $type));
                $result = $this->mongo_db->get('market_trade_hourly_history');
                $result = iterator_to_array($result);

                if (count($result) > 0) {
                    $this->mongo_db->where(array('hour' => $current_date, 'coin' => $value['coin'], 'price' => (float) $value['price'], 'type' => $type));
                    $this->mongo_db->set($insert_array);
                    //Update data in mongoTable
                    $this->mongo_db->update('market_trade_hourly_history');
                    // echo 'coin updated at ' . $current_date . '--- con' . $value['coin'] . '<br>';
                    // echo '... bBBBBBBBBBBBBBBIIIIIIIIIIID.' . '<br>';
                    //
                } else {
                    $this->mongo_db->insert('market_trade_hourly_history', $insert_array);
                    // echo 'coin inserted at ' . $current_date . '--- con' . $value['coin'] . '<br>';
                    // echo '... bBBBBBBBBBBBBBBIIIIIIIIIIID.' . '<br>';
                }

            }

        }/** End of  for each coin symbol**/

        $removeTime = date('Y-m-d G:i:s', strtotime('-3 hour', strtotime(date("Y-m-d G:i:s"))));
        $removeTime1 = date('Y-m-d G:i:s', strtotime('-1 hour', strtotime(date("Y-m-d G:i:s"))));

        $orig_date = new DateTime($removeTime);
        $orig_date = $orig_date->getTimestamp();
        $created_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);

        $orig_date1 = new DateTime($removeTime1);
        $orig_date1 = $orig_date1->getTimestamp();
        $created_date1 = new MongoDB\BSON\UTCDateTime($orig_date1 * 1000);

        $db = $this->mongo_db->customQuery();
        ///////////////////////////////////////////////////////////////
        $delectmarket_prices = $db->market_prices->deleteMany(array('created_date' => array('$lte' => $created_date1)));

        /////////////////////////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////

        $delectmarket_depth = $db->market_depth->deleteMany(array('created_date' => array('$lte' => $created_date)));

        ///////////////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////

        $delectmarket_trades = $db->market_trades->deleteMany(array('created_date' => array('$lte' => $created_date)));
    } /*** End of get_market_history***/

    public function market_trade_hourly_history_bvs() {

        $start_second = strtotime(date("Y-m-d H:00:00", strtotime('-1 hour')));
        $end_second = strtotime(date("Y-m-d H:59:59", strtotime('-1 hour')));
        $current_date = date("Y-m-d H:00:00", strtotime('-1 hour'));

        $start_milli_second = $start_second * 1000;
        $end_milli_second = $end_second * 1000;

        $start_milli_second_obj = new MongoDB\BSON\UTCDateTime($start_milli_second);
        $end_milli_second_obj = new MongoDB\BSON\UTCDateTime($end_milli_second);

        $current_date_milli_second = $current_date * 1000;
        $current_date_milli_second_obj = new MongoDB\BSON\UTCDateTime($current_date_milli_second);

        $pipeline = array(
            '$group' => array('_id' => '$price', 'quantity' => array('$sum' => '$quantity'),
                'maker' => array('$first' => '$maker'),
                'coin' => array('$first' => '$coin'),
                'created_date' => array('$first' => '$created_date'),
                'price' => array('$first' => '$price'),
                'type' => array('$first' => '$type'),
            ),
        );

        $project = array(
            '$project' => array(
                "_id" => 1,
                "price" => 1,
                "quantity" => 1,
                "maker" => 1,
                "coin" => 1,
                "type" => 1,
                'created_date' => 1,
            ),
        );

        $all_coins_arr = $this->mod_sockets->get_all_coins();

        /*** For ask insertion**/

        foreach ($all_coins_arr as $key => $coins_arr) {

            $coin_symbol = $coins_arr['symbol'];
            $match = array(
                '$match' => array(
                    'coin' => $coin_symbol,
                    'type' => 'buy',
                    'created_date' => array('$gte' => $start_milli_second_obj,
                        '$lte' => $end_milli_second_obj),
                ),
            );

            $connect = $this->mongo_db->customQuery();
            $market_history_Arr = $connect->market_trades->aggregate(array($project, $match, $pipeline));
            $market_history_Arr = iterator_to_array($market_history_Arr);

            foreach ($market_history_Arr as $key => $value) {
                $type = 'buy';
                if ($value['type'] == 'buy') {
                    $type = 'buy';
                }

                $insert_array = array(
                    'coin' => $value['coin'],
                    'hour' => $current_date,
                    'hour_timestamp' => $current_date_milli_second,
                    'price' => (float) $value['price'],
                    'volume' => (float) $value['quantity'],
                    'timestamp' => $value['created_date'],
                    'type' => $type,
                    'maker' => $value['maker'],
                );

                $this->mongo_db->where(array('hour' => $current_date, 'coin' => $value['coin'], 'price' => (float) $value['price'], 'type' => $type));
                $result = $this->mongo_db->get('market_trade_hourly_history_bvs');
                $result = iterator_to_array($result);

                if (count($result) > 0) {

                    $this->mongo_db->where(array('hour' => $current_date, 'coin' => $value['coin'], 'price' => (float) $value['price'], 'type' => $type));
                    $this->mongo_db->set($insert_array);
                    //Update data in mongoTable
                    $this->mongo_db->update('market_trade_hourly_history_bvs');

                    // echo 'coin updated at ' . $current_date . '--- con' . $value['coin'] . '<br>';
                    // echo '... AAAAAAASSSSSSSSkk.' . '<br>';

                } else {
                    $this->mongo_db->insert('market_trade_hourly_history_bvs', $insert_array);
                    // echo 'coin inserted at ' . $current_date . '--- con' . $value['coin'] . '<br>';
                    // echo '... AAAAAAASSSSSSSSkk.' . '<br>';
                }

            }

        }/** End of  for each coin symbol**/

        /***** End of ask insetion****/

        /*** For bid insertion**/

        foreach ($all_coins_arr as $key => $coins_arr) {

            $coin_symbol = $coins_arr['symbol'];

            $match = array(
                '$match' => array(
                    'coin' => $coin_symbol,
                    'type' => 'sell',
                    'created_date' => array('$gte' => $start_milli_second_obj,
                        '$lte' => $end_milli_second_obj),
                ),
            );

            $connect = $this->mongo_db->customQuery();
            $market_history_Arr = $connect->market_trades->aggregate(array($project, $match, $pipeline));
            $market_history_Arr = iterator_to_array($market_history_Arr);
            foreach ($market_history_Arr as $key => $value) {
                $type = 'sell';
                if ($value['type'] == 'sell') {
                    $type = 'sell';
                }

                $insert_array = array(
                    'coin' => $value['coin'],
                    'hour' => $current_date,
                    'hour_timestamp' => $current_date_milli_second,
                    'price' => (float) $value['price'],
                    'volume' => (float) $value['quantity'],
                    'timestamp' => $value['created_date'],
                    'type' => $type,
                    'maker' => $value['maker'],
                );

                $this->mongo_db->where(array('hour' => $current_date, 'coin' => $value['coin'], 'price' => (float) $value['price'], 'type' => $type));
                $result = $this->mongo_db->get('market_trade_hourly_history_bvs');
                $result = iterator_to_array($result);

                if (count($result) > 0) {
                    $this->mongo_db->where(array('hour' => $current_date, 'coin' => $value['coin'], 'price' => (float) $value['price'], 'type' => $type));
                    $this->mongo_db->set($insert_array);
                    //Update data in mongoTable
                    $this->mongo_db->update('market_trade_hourly_history_bvs');
                    // echo 'coin updated at ' . $current_date . '--- con' . $value['coin'] . '<br>';
                    // echo '... bBBBBBBBBBBBBBBIIIIIIIIIIID.' . '<br>';

                } else {
                    $this->mongo_db->insert('market_trade_hourly_history_bvs', $insert_array);
                    // echo 'coin inserted at ' . $current_date . '--- con' . $value['coin'] . '<br>';
                    // echo '... bBBBBBBBBBBBBBBIIIIIIIIIIID.' . '<br>';
                }

            }

        }/** End of  for each coin symbol**/

        // $removeTime = date('Y-m-d G:i:s', strtotime('-3 hour', strtotime(date("Y-m-d G:i:s"))));

        // $orig_date = new DateTime($removeTime);
        // $orig_date = $orig_date->getTimestamp();
        // $created_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
        // $db = $this->mongo_db->customQuery();
        // ///////////////////////////////////////////////////////////////
        // $delectmarket_prices = $db->market_prices->deleteMany(array('created_date' => array('$lte' => $created_date)));

        // /////////////////////////////////////////////////////////////////////////////

        // ///////////////////////////////////////////////////////////////

        // $delectmarket_depth = $db->market_depth->deleteMany(array('created_date' => array('$lte' => $created_date)));

        // ///////////////////////////////////////////////////////////////////

        // ///////////////////////////////////////////////////////////////

        // $delectmarket_trades = $db->market_trades->deleteMany(array('created_date' => array('$lte' => $created_date)));
    } /*** End of get_market_history***/

    ///////////////////////////////////////////////
    //////////////////////////////////////////////
    /////////////////////////////////////////////
    //////////                                 //////////
    /////////  market_trade_quarterly_history ///////////
    /////////                                ///////////
    ///////////////////////////////////////////
    //////////////////////////////////////////
    /////////////////////////////////////////

    public function make_date_quarter() {
        $date_quart = array('H:00:00' => 'H:14:59', 'H:15:00' => 'H:29:59', 'H:30:00' => 'H:44:59', 'H:45:00' => 'H:59:59');
        foreach ($date_quart as $start_date => $end_date) {
            $this->market_trade_quarterly_history($start_date, $end_date);
        }
    } //End of make_date_quarter

    public function market_trade_quarterly_history($start_date, $end_date) {

        $start_second = strtotime(date("Y-m-d " . $start_date, strtotime('-1 hour')));
        $end_second = strtotime(date("Y-m-d " . $end_date, strtotime('-1 hour')));
        $current_date = date("Y-m-d " . $start_date, strtotime('-1 hour'));
        $start_milli_second = $start_second * 1000;
        $end_milli_second = $end_second * 1000;
        $start_milli_second_obj = new MongoDB\BSON\UTCDateTime($start_milli_second);
        $end_milli_second_obj = new MongoDB\BSON\UTCDateTime($end_milli_second);

        $current_date_milli_second = $current_date * 1000;
        $current_date_milli_second_obj = new MongoDB\BSON\UTCDateTime($current_date_milli_second);

        $pipeline = array(
            '$group' => array('_id' => '$price', 'quantity' => array('$sum' => '$quantity'),
                'maker' => array('$first' => '$maker'),
                'coin' => array('$first' => '$coin'),
                'created_date' => array('$first' => '$created_date'),
                'price' => array('$first' => '$price'),
            ),
        );

        $project = array(
            '$project' => array(
                "_id" => 1,
                "price" => 1,
                "quantity" => 1,
                "maker" => 1,
                "coin" => 1,
                'created_date' => 1,
            ),
        );

        $all_coins_arr = $this->mod_sockets->get_all_coins();

        /*** For ask insertion**/
        foreach ($all_coins_arr as $key => $coins_arr) {
            $coin_symbol = $coins_arr['symbol'];
            $match = array(
                '$match' => array(
                    'coin' => $coin_symbol,
                    'maker' => 'false',
                    'created_date' => array('$gte' => $start_milli_second_obj,
                        '$lte' => $end_milli_second_obj),
                ),
            );
            $connect = $this->mongo_db->customQuery();
            $market_history_Arr = $connect->market_trades->aggregate(array($project, $match, $pipeline));
            $market_history_Arr = iterator_to_array($market_history_Arr);

            foreach ($market_history_Arr as $key => $value) {
                $type = 'ask';

                if ($value['maker'] == 'true') {
                    $type = 'bid';
                }

                $insert_array = array(
                    'coin' => $value['coin'],
                    'hour' => $current_date,
                    'hour_timestamp' => $current_date_milli_second,
                    'price' => (float) $value['price'],
                    'volume' => (float) $value['quantity'],
                    'timestamp' => $value['created_date'],
                    'type' => $type,
                    'maker' => $value['maker'],
                );

                $this->mongo_db->where(array('hour' => $current_date, 'coin' => $value['coin'], 'price' => (float) $value['price'], 'type' => $type));
                $result = $this->mongo_db->get('market_trade_quarterly_history');
                $result = iterator_to_array($result);
                if (count($result) > 0) {
                    $this->mongo_db->where(array('hour' => $current_date, 'coin' => $value['coin'], 'price' => (float) $value['price'], 'type' => $type));
                    $this->mongo_db->set($insert_array);
                    //Update data in mongoTable
                    $this->mongo_db->update('market_trade_quarterly_history');
                    echo 'coin updated at ' . $current_date . '--- con' . $value['coin'] . '<br>';
                    echo '... AAAAAAASSSSSSSSkk.' . '<br>';
                } else {
                    $this->mongo_db->insert('market_trade_quarterly_history', $insert_array);
                    echo 'coin inserted at ' . $current_date . '--- con' . $value['coin'] . '<br>';
                    echo '... AAAAAAASSSSSSSSkk.' . '<br>';
                }
            }
        }/** End of  for each coin symbol**/

        /***** End of ask insetion****/

        /*** For bid insertion**/

        foreach ($all_coins_arr as $key => $coins_arr) {

            $coin_symbol = $coins_arr['symbol'];

            $match = array(
                '$match' => array(
                    'coin' => $coin_symbol,
                    'maker' => 'true',
                    'created_date' => array('$gte' => $start_milli_second_obj,
                        '$lte' => $end_milli_second_obj),
                ),
            );

            $connect = $this->mongo_db->customQuery();

            $market_history_Arr = $connect->market_trades->aggregate(array($project, $match, $pipeline));

            $market_history_Arr = iterator_to_array($market_history_Arr);

            foreach ($market_history_Arr as $key => $value) {

                $type = 'ask';

                if ($value['maker'] == 'true') {
                    $type = 'bid';
                }

                $insert_array = array(
                    'coin' => $value['coin'],
                    'hour' => $current_date,
                    'hour_timestamp' => $current_date_milli_second,
                    'price' => (float) $value['price'],
                    'volume' => (float) $value['quantity'],
                    'timestamp' => $value['created_date'],
                    'type' => $type,
                    'maker' => $value['maker'],
                );

                $this->mongo_db->where(array('hour' => $current_date, 'coin' => $value['coin'], 'price' => (float) $value['price'], 'type' => $type));

                $result = $this->mongo_db->get('market_trade_quarterly_history');

                $result = iterator_to_array($result);

                if (count($result) > 0) {

                    $this->mongo_db->where(array('hour' => $current_date, 'coin' => $value['coin'], 'price' => (float) $value['price'], 'type' => $type));
                    $this->mongo_db->set($insert_array);
                    //Update data in mongoTable
                    $this->mongo_db->update('market_trade_quarterly_history');

                    echo 'coin updated at ' . $current_date . '--- con' . $value['coin'] . '<br>';
                    echo '... bBBBBBBBBBBBBBBIIIIIIIIIIID.' . '<br>';

                } else {
                    $this->mongo_db->insert('market_trade_quarterly_history', $insert_array);
                    echo 'coin inserted at ' . $current_date . '--- con' . $value['coin'] . '<br>';
                    echo '... bBBBBBBBBBBBBBBIIIIIIIIIIID.' . '<br>';
                }

            }

        }/** End of  for each coin symbol**/

    } /*** End of get_market_history***/

    public function insert_market_trades($data) {
        $this->db->insert('market_trades_test', $data);
    }

}
