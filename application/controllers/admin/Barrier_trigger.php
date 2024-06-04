<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Barrier_trigger extends CI_Controller
{
    private $originalString = "vizzwebsolutions";
    private $keyCode = "vizzwebsolutions";

    public function __construct()
    {
        parent::__construct();
        //load main template
        $this->stencil->layout('admin_layout');

        //load required slices
        $this->stencil->slice('admin_header_script');
        $this->stencil->slice('admin_header');
        $this->stencil->slice('admin_left_sidebar');
        $this->stencil->slice('admin_footer_script');

        // Load Modal
        $this->load->model('admin/mod_login');
        $this->load->model('admin/mod_users');
        $this->load->model('admin/mod_dashboard');
        $this->load->model('admin/mod_market');
        $this->load->model('admin/mod_coins');
        $this->load->model('admin/mod_candel');
        $this->load->model('admin/mod_realtime_candle_socket');
        $this->load->model('admin/mod_box_trigger_3');
        $this->load->model('admin/mod_barrier_trigger');
        $this->load->model('admin/mod_chart3');
        $this->load->model('admin/mod_custom_script');
        $this->load->model('admin/mod_limit_order');
        $this->load->model('admin/mod_balance');
        $this->load->model('admin/mod_jwt');
    }
    public function listOrdersZeroQty($exchange='binance') {
        $remote_ip = get_client_ip();
        $allowed_exchanges = ['bam','binance','kraken'];
        if($remote_ip!='203.99.181.17')
        {
            exit("No Route Found");
        }
        if (!in_array($exchange, $allowed_exchanges))
        {
            exit("Invalid Exchange");
            
        }
       
        $db = $this->mongo_db->customQuery();
        $collection = $exchange == 'binance'?"buy_orders":"buy_orders_".$exchange;
        
        
        $match = array(trigger_type=>"barrier_percentile_trigger","application_mode"=>"live","status"=>array('$in'=>array('submitted_for_sell','FILLED_ERROR','LTH','FILLED')),"quantity"=>array('$lte'=>0));
        $pipeline = array(
          array('$match'=>$match),
          array('$project'=>array('quantity'=>1,"orderCreatedDate"=>array('$toString'=>'$created_date'),"orderLastModifiedDate"=>array('$toString'=>'$modified_date'),"userObjId"=>array('$toObjectId'=>'$admin_id'),"orderID"=>array('$toString'=>'$_id'),"symbol"=>1,"admin_id"=>1,"status"=>1,"order_level"=>1)),
          array('$lookup'=>array("from"=>"users",
       "localField"=>"userObjId",
       "foreignField"=>"_id",
       "as"=>"userObjectData")),
       array('$unwind'=>'$userObjectData'),
       array('$project'=>array("orderID"=>1,'quantity'=>1,"orderCreatedDate"=>1,"orderLastModifiedDate"=>1,"_id"=>0,"symbol"=>1,"admin_id"=>1,"status"=>1,"order_level"=>1,"username"=>'$userObjectData.username',"email_address"=>'$userObjectData.email_address'))
        
               
        
        );
        //echo json_encode($pipeline);
        $qResult = $db->$collection->aggregate($pipeline);
        $results = iterator_to_array($qResult);
        if(count($results) > 0)
        {
             echo '<b> Listing Orders Having Quantity Equals to Zero or Less For '.$exchange . ' Exchange </b>';
            foreach($results as $key=>$value)
            {
                $toPrint = (array) $value;
                ksort($toPrint);
                echo "<pre>";
                print_r($toPrint);
                echo "</pre>";
            }
            echo '<b> Ends Listing Orders Having Quantity Equals to Zero or Less For '.$exchange . ' Exchange </b>';
        }
        else
        {
            echo '<b> No Orders Found That has quantity 0 in '.$exchange . ' Exchange </b>';
        }
       
        
        
        exit;

    }
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////                           /////////////////
    ////////////////////                           ////////////////////////////////
    ////////////////////  Function Call Part        //////////////////////////////
    ////////////////////                           ////////////////////////////////
    ////////////////////                           /////////////////
    ////////////////////                           /////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    // public function update_user_selected_stop_loss()
    // {
    //     $summary = "This cronjob Run for Barrier Trigger Update User Stop Loss";
    //     $duration = "1 second";
    //     track_execution_of_cronjob($duration, $summary);

    //     echo '%%%%%%%%%%' . date('Y-m-d H:i:s') . '<br>';
    //     //Call function at the start of function
    //     $function_name = 'update_user_selected_stop_loss';
    //     $is_function_process_complete = is_function_process_complete($function_name);
    //     function_start($function_name);

    //     $is_script_take_more_time = is_script_take_more_time($function_name);

    //     if ($is_script_take_more_time) {
    //         track_execution_of_function_time($function_name);
    //         function_stop($function_name);
    //     }

    //     if (!$is_function_process_complete) {
    //         echo 'previous process is still running';
    //         return false;
    //     }

    //     //function to see if trade is on
    //     $is_trades_on_of = $this->mod_barrier_trigger->is_trades_on_of();
    //     if (!$is_trades_on_of) {
    //         echo 'Trade Is  being Off';
    //         return false;
    //     }

    //     $trigger_type = 'barrier_trigger';
    //     $type = 'live';

    //     $all_coins_arr = $this->mod_sockets->get_all_coins();

    //     if (count($all_coins_arr) > 0) {
    //         foreach ($all_coins_arr as $data) {
    //             $coin_symbol = $data['symbol'];

    //             $this->mongo_db->where(array('triggers_type' => $trigger_type, 'order_mode' => $type, 'coin' => $coin_symbol));
    //             $response_obj = $this->mongo_db->get('trigger_global_setting');
    //             $response_arr = iterator_to_array($response_obj);
    //             $response = array();
    //             if (count($response_arr) > 0) {
    //                 $aggressive_stop_rule = $response_arr[0]['aggressive_stop_rule'];
    //                 $sell_profit_percet = $response_arr[0]['sell_profit_percet'];

    //                 //%%%%%%%%%%%%%%
    //                 $this->mod_barrier_trigger->aggrisive_define_percentage_followup_stop_loss_user_selected($date, $type, $trigger_type);

    //                 $current_market_price = $this->mod_dashboard->get_market_value($coin_symbol);
    //                 $current_market_price = (float) $current_market_price;

    //                 //Update Market Deep Price
    //                 $this->mod_barrier_trigger->is_market_deep_price_order($current_market_price, $coin_symbol, $type = "user_select");

    //                 //check if order is ready to update stop loss
    //                 $this->mod_barrier_trigger->is_market_deep_ready_stop_loss_update($coin_symbol, $sell_profit_percet, $current_market_price, $type = "user_select");

    //                 //update stop loss big barrier
    //                 $this->mod_barrier_trigger->stop_loss_big_wall_barrier_trigger($coin_symbol, $sell_profit_percet, $current_market_price, $type = "user_select");
    //             } //End of if setting array not empty
    //         } //End of coin array for each
    //     } //Check if coin exist

    //     //%%%%%%%%%%%% if function process complete %%%%%%%%%%%%%
    //     function_stop($function_name);
    //     echo 'End Date ******' . date('Y-m-d H:i:s') . '<br>';
    // } //End of update_user_selected_stop_loss

    // public function update_custom_user_stop_loss()
    // {
    //     $summary = "This cronjob Run to update user custom stop loss";
    //     $duration = "1 second";
    //     track_execution_of_cronjob($duration, $summary);

    //     echo '%%%%%%%%%%' . date('Y-m-d H:i:s') . '<br>';
    //     //Call function at the start of function
    //     $function_name = 'update_custom_user_stop_loss';
    //     $is_function_process_complete = is_function_process_complete($function_name);
    //     function_start($function_name);

    //     $is_script_take_more_time = is_script_take_more_time($function_name);

    //     if ($is_script_take_more_time) {
    //         track_execution_of_function_time($function_name);
    //         function_stop($function_name);
    //     }

    //     if (!$is_function_process_complete) {
    //         echo 'previous process is still running';
    //         return false;
    //     }

    //     //function to see if trade is on
    //     $is_trades_on_of = $this->mod_barrier_trigger->is_trades_on_of();
    //     if (!$is_trades_on_of) {
    //         echo 'Trade Is  being Off';
    //         return false;
    //     }

    //     $trigger_type = 'barrier_trigger';
    //     $type = 'live';
    //     $all_coins_arr = $this->mod_sockets->get_all_coins();

    //     if (count($all_coins_arr) > 0) {
    //         foreach ($all_coins_arr as $data) {
    //             $coin_symbol = $data['symbol'];
    //             $orders_arr = $this->mod_barrier_trigger->ready_custom_stop_loss_order_for_update($coin_symbol);
    //             $current_market_price = $this->mod_dashboard->get_market_value($coin_symbol);
    //             $current_market_price = (float) $current_market_price;

    //             //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //             $created_date = date('Y-m-d G:i:s');
    //             if (!empty($orders_arr)) {
    //                 foreach ($orders_arr as $data) {
    //                     $id = (string) $data['_id'];
    //                     $admin_id = $data['admin_id'];
    //                     $iniatial_trail_stop = (float) $data['iniatial_trail_stop'];
    //                     $market_value = (float) $data['market_value'];

    //                     $activate_stop_loss_profit_percentage = $data['activate_stop_loss_profit_percentage'];

    //                     $diff_price = $current_market_price - ($current_market_price / 100) * $activate_stop_loss_profit_percentage;

    //                     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
    //                     $upd_data22 = array(
    //                         'is_market_deep_range' => 'yes',
    //                     );

    //                     if ($market_value <= $diff_price) {
    //                         $this->mongo_db->where(array('_id' => $id));
    //                         $this->mongo_db->set($upd_data22);
    //                         $this->mongo_db->update('buy_orders');

    //                         $log_msg = "Order is <span style='color:green'>Ready</span> to update stop Loss";
    //                         $this->insert_order_history_log($id, $log_msg, 'buy_created', $admin_id, $created_date);
    //                         /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //                         if (isset($data['market_deep_price']) && $data['market_deep_price'] != '') {
    //                             $market_deep_price = $data['market_deep_price'];
    //                             $this->update_stop_loss_close_to_deep_price($market_deep_price, $iniatial_trail_stop, $id, $admin_id);
    //                         } //end of if market deep price not empty
    //                     } //if market price is greater form defined
    //                 } //End of for Each
    //             } //End of if condition
    //             //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

    //             echo '<pre>';
    //             print_r($orders_arr);
    //             exit;
    //         } //End of coin array for each
    //     } //Check if coin exist

    //     //%%%%%%%%%%%% if function process complete %%%%%%%%%%%%%
    //     function_stop($function_name);
    //     echo 'End Date ******' . date('Y-m-d H:i:s') . '<br>';
    // } //End of update_custom_user_stop_loss

    // public function run_barrier_trigger_auto_update_stop_loss()
    // {

    //     //$this->update_custom_user_stop_loss();

    //     $summary = "This cronjob Run for Barrier Trigger auto update stop Loss";
    //     $duration = "1 second";
    //     track_execution_of_cronjob($duration, $summary);

    //     echo '%%%%%%%%%%' . date('Y-m-d H:i:s') . '<br>';
    //     //Call function at the start of function
    //     $function_name = 'run_barrier_trigger_auto_update_stop_loss';
    //     $is_function_process_complete = is_function_process_complete($function_name);
    //     function_start($function_name);

    //     $is_script_take_more_time = is_script_take_more_time($function_name);

    //     if ($is_script_take_more_time) {
    //         track_execution_of_function_time($function_name);
    //         function_stop($function_name);
    //     }

    //     if (!$is_function_process_complete) {
    //         echo 'previous process is still running';
    //         return false;
    //     }

    //     //function to see if trade is on
    //     $is_trades_on_of = $this->mod_barrier_trigger->is_trades_on_of();
    //     if (!$is_trades_on_of) {
    //         echo 'Trade Is  being Off';
    //         return false;
    //     }

    //     $trigger_type = 'barrier_trigger';
    //     $type = 'live';

    //     $all_coins_arr = $this->mod_sockets->get_all_coins();

    //     if (count($all_coins_arr) > 0) {
    //         foreach ($all_coins_arr as $data) {
    //             $coin_symbol = $data['symbol'];

    //             $this->mongo_db->where(array('triggers_type' => $trigger_type, 'order_mode' => $type, 'coin' => $coin_symbol));
    //             $response_obj = $this->mongo_db->get('trigger_global_setting');
    //             $response_arr = iterator_to_array($response_obj);
    //             $response = array();
    //             if (count($response_arr) > 0) {
    //                 $aggressive_stop_rule = $response_arr[0]['aggressive_stop_rule'];

    //                 if ($aggressive_stop_rule == 'stop_loss_rule_2') {
    //                     $this->mod_box_trigger_3->aggrisive_define_percentage_followup($date, $type, $trigger_type);
    //                 } elseif ($aggressive_stop_rule == 'stop_loss_rule_big_wall') {
    //                     $sell_profit_percet = $response_arr[0]['sell_profit_percet'];

    //                     $current_market_price = $this->mod_dashboard->get_market_value($coin_symbol);
    //                     $current_market_price = (float) $current_market_price;

    //                     //Update Market Deep Price
    //                     // $this->mod_barrier_trigger->is_market_deep_price_order($current_market_price, $coin_symbol);

    //                     //check if order is ready to update stop loss
    //                     $this->mod_barrier_trigger->is_order_ready_to_update_stop_loss($coin_symbol, $sell_profit_percet, $current_market_price);

    //                     //update stop loss big barrier
    //                     $this->mod_barrier_trigger->stop_loss_big_wall_barrier_trigger($coin_symbol, $sell_profit_percet, $current_market_price);
    //                 } //End of stop_loss_rule_big_wall
    //             } //If array not Empty
    //         } //End of coin array for each
    //     } //Check if coin exist

    //     //%%%%%%%%%%%% if function process complete %%%%%%%%%%%%%
    //     function_stop($function_name);
    //     echo 'End Date ******' . date('Y-m-d H:i:s') . '<br>';
    // } //End run_barrier_trigger_auto_update_stop_loss

    // public function run_barrier_trigger_auto_sell_stop_loss_cron_job()
    // {
    //     exit;
    //     $summary = "This cronjob Run for run_barrier_trigger_auto_sell_stop_loss_cron_job";
    //     $duration = "2 second";
    //     track_execution_of_cronjob($duration, $summary);

    //     $function_name = 'run_barrier_trigger_auto_sell_stop_loss_cron_job';
    //     $is_function_process_complete = is_function_process_complete($function_name);
    //     function_start($function_name);

    //     $is_script_take_more_time = is_script_take_more_time($function_name);

    //     if ($is_script_take_more_time) {
    //         track_execution_of_function_time($function_name);
    //         function_stop($function_name);
    //     }

    //     if (!$is_function_process_complete) {
    //         echo 'previous process is still running';
    //         return false;
    //     }

    //     //function to see if trade is on
    //     $is_trades_on_of = $this->mod_barrier_trigger->is_trades_on_of();
    //     if (!$is_trades_on_of) {
    //         echo 'Trade Is  being Off';
    //         return false;
    //     }

    //     $trigger_type = 'barrier_trigger';
    //     $date = date('Y-m-d H:i:s');
    //     $type = 'live';

    //     $all_coins_arr = $this->mod_sockets->get_all_coins();

    //     if (count($all_coins_arr) > 0) {
    //         foreach ($all_coins_arr as $data) {
    //             $coin_symbol = $data['symbol'];
    //             /******************************/
    //             $this->sell_orders_by_stop_loss($date, $coin_symbol);
    //             /******************************/
    //         } //End of Foreach
    //     } //End of if count is greater then 0

    //     function_stop($function_name);
    // } //End of run_barrier_trigger_auto_sell_stop_loss_cron_job

    // public function run_barrier_trigger_auto_buy_cron_job()
    // {
    //     exit;
    //     $summary = "This cronjob Run for run_barrier_trigger_auto_buy_cron_job";
    //     $duration = "2 second";
    //     track_execution_of_cronjob($duration, $summary);

    //     $function_name = 'run_barrier_trigger_auto_buy_cron_job';
    //     $is_function_process_complete = is_function_process_complete($function_name);
    //     function_start($function_name);

    //     $is_script_take_more_time = is_script_take_more_time($function_name);

    //     if ($is_script_take_more_time) {
    //         track_execution_of_function_time($function_name);
    //         function_stop($function_name);
    //     }

    //     if (!$is_function_process_complete) {
    //         echo 'previous process is still running';
    //         return false;
    //     }

    //     //function to see if trade is on
    //     $is_trades_on_of = $this->mod_barrier_trigger->is_trades_on_of();
    //     if (!$is_trades_on_of) {
    //         return 'Trade Is  being Off';
    //     }

    //     $trigger_type = 'barrier_trigger';
    //     $date = date('Y-m-d H:00:00');
    //     $type = 'live';

    //     $all_coins_arr = $this->mod_sockets->get_all_coins();

    //     if (count($all_coins_arr) > 0) {
    //         foreach ($all_coins_arr as $data) {
    //             $coin_symbol = $data['symbol'];
    //             /******************************/
    //             $this->go_buy_rules($coin_symbol);
    //             /******************************/
    //         } //End of Foreach
    //     } //End of if count is greater then 0

    //     function_stop($function_name);
    // } //End of run_barrier_trigger_auto_buy_cron_job

    // public function run_barrier_trigger_auto_sell_cron_job()
    // {
    //     exit;
    //     $summary = "This cronjob Run for run_barrier_trigger_auto_sell_cron_job";
    //     $duration = "2 second";
    //     track_execution_of_cronjob($duration, $summary);

    //     // $txt = "run_barrier_trigger_auto_sell_cron_job update%%%%%%%%%%%%%%%%%%%%%%%% ".date('y-m-d g:i:s');
    //     // $myfile = file_put_contents('/home/digiebot/public_html/app.digiebot.com/custom_cornjobs/check_box_logs.txt', $txt . PHP_EOL, FILE_APPEND | LOCK_EX);

    //     $function_name = 'run_barrier_trigger_auto_sell_cron_job';
    //     $is_function_process_complete = is_function_process_complete($function_name);
    //     function_start($function_name);

    //     $is_script_take_more_time = is_script_take_more_time($function_name);

    //     if ($is_script_take_more_time) {
    //         track_execution_of_function_time($function_name);
    //         function_stop($function_name);
    //     }

    //     if (!$is_function_process_complete) {
    //         echo 'previous process is still running';
    //         return false;
    //     }

    //     //function to see if trade is on
    //     $is_trades_on_of = $this->mod_barrier_trigger->is_trades_on_of();
    //     if (!$is_trades_on_of) {
    //         return 'Trade Is  being Off';
    //     }

    //     $trigger_type = 'barrier_trigger';
    //     $date = date('Y-m-d H:i:s');
    //     $type = 'live';

    //     $all_coins_arr = $this->mod_sockets->get_all_coins();

    //     if (count($all_coins_arr) > 0) {
    //         foreach ($all_coins_arr as $data) {
    //             $coin_symbol = $data['symbol'];
    //             /******************************/
    //             $this->go_sell_rules($coin_symbol);
    //             /******************************/
    //         } //End of Foreach
    //     } //End of if count is greater then 0

    //     function_stop($function_name);
    // } //end if run_barrier_trigger_auto_sell_cron_job

    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////                           /////////////////
    ////////////////////                           ////////////////////////////////
    ////////////////////  Create and Buy Orders    /////////////////////////////////
    ////////////////////                           ////////////////////////////////
    ////////////////////                           /////////////////
    ////////////////////                           /////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    // public function go_buy_barrier_trigger_order($date, $current_market_price, $coin_symbol, $sell_per, $stop_loss_percent, $log_arr, $show_error_log, $aggressive_stop_rule, $recommended_order_level, $recommended_order_level_on_off, $buy_rule_number)
    // {

    //     // %%%%%%%%%%%%%% -- Get Parent Orders -- %%%%%%%%%%%%%%%%%%%
    //     $parent_orders_arr = $this->mod_barrier_trigger->get_parent_orders($coin_symbol);

    //     $log_msg_success = '';
    //     foreach ($log_arr as $key => $value) {
    //         $log_msg_success .= $key . '  :  ' . $value . '<br>';
    //     }

    //     //%%%%%%%%%%%%%%%%%%%%%%% -- Get Coin Unit Value -- %%%%%%%%%%%%%%%%%%%
    //     $coin_unit_value = $this->mod_coins->get_coin_unit_value($coin_symbol);

    //     //%%%%%%%%%%%%%%%%%%%%%%% -- Check Previous Order is Created   -- %%%%%%%%%%%%%%%%%%%
    //     $is_order_created = $this->mod_barrier_trigger->is_order_is_created_just_now($coin_symbol, 'buy');

    //     if (count($parent_orders_arr) > 0) {
    //         foreach ($parent_orders_arr as $buy_orders) {
    //             $buy_parent_id = $buy_orders['_id'];
    //             $coin_symbol = $buy_orders['symbol'];
    //             $buy_quantity = $buy_orders['quantity'];
    //             $buy_trigger_type = $buy_orders['trigger_type'];
    //             $admin_id = $buy_orders['admin_id'];
    //             $application_mode = $buy_orders['application_mode'];
    //             $order_mode = $buy_orders['order_mode'];
    //             $defined_sell_percentage = $buy_orders['defined_sell_percentage'];
    //             $order_type = $buy_orders['order_type'];
    //             $buy_one_tip_above = $buy_orders['buy_one_tip_above'];
    //             $sell_one_tip_below = $buy_orders['sell_one_tip_below'];
    //             $order_level = $buy_orders['order_level'];
    //             $stop_loss_rule_apply = $buy_orders['stop_loss_rule'];
    //             $custom_stop_loss_percentage = $buy_orders['custom_stop_loss_percentage'];
    //             $activate_stop_loss_profit_percentage = $buy_orders['activate_stop_loss_profit_percentage'];
    //             $lth_functionality = $buy_orders['lth_functionality'];
    //             $lth_profit = $buy_orders['lth_profit'];

    //             $sell_price = $current_market_price + ($current_market_price * $sell_per) / 100;
    //             $stop_loss_rule = '';

    //             if ($stop_loss_rule_apply == 'custom_stop_loss') {
    //                 $iniatial_trail_stop = $current_market_price - ($current_market_price / 100) * $custom_stop_loss_percentage;
    //                 $stop_loss_rule = 'custom_stop_loss value :<b>' . $custom_stop_loss_percentage . ' %</b>  ';
    //             } else {
    //                 $iniatial_trail_stop = $current_market_price - ($current_market_price / 100) * $stop_loss_percent;
    //                 $stop_loss_rule = '<b>' . $stop_loss_percent . ' % </b>  Setting Defined stop Loss';
    //             }

    //             $created_date = date('Y-m-d G:i:s');
    //             $ins_data_buy_order = array(
    //                 'price' => (float) $current_market_price,
    //                 'quantity' => $buy_quantity,
    //                 'symbol' => $coin_symbol,
    //                 'order_type' => $order_type,
    //                 'admin_id' => $admin_id,
    //                 'trigger_type' => 'barrier_trigger',
    //                 'sell_price' => (float) $sell_price,
    //                 'created_date' => $this->mongo_db->converToMongodttime($date),
    //                 'modified_date' => $this->mongo_db->converToMongodttime($created_date),
    //                 'buy_date' => $this->mongo_db->converToMongodttime($date),
    //                 'trail_check' => 'no',
    //                 'trail_interval' => '0',
    //                 'buy_trail_price' => '0',
    //                 'auto_sell' => 'no',
    //                 'buy_parent_id' => $buy_parent_id,
    //                 'iniatial_trail_stop' => (float) $iniatial_trail_stop,
    //                 'iniatial_trail_stop_copy' => (float) $iniatial_trail_stop,
    //                 'buy_order_status_new_filled' => 'wait_for_buyed',
    //                 'application_mode' => $application_mode,
    //                 'order_mode' => $order_mode,
    //                 'defined_sell_percentage' => $defined_sell_percentage,
    //                 'buy_one_tip_above' => $buy_one_tip_above,
    //                 'sell_one_tip_below' => $sell_one_tip_below,
    //                 'order_level' => $order_level,
    //                 'stop_loss_rule' => $stop_loss_rule_apply,
    //                 'custom_stop_loss_percentage' => $custom_stop_loss_percentage,
    //                 'activate_stop_loss_profit_percentage' => (float) $activate_stop_loss_profit_percentage,
    //                 'sell_profit_percent' => $defined_sell_percentage,
    //                 'buy_rule_number' => $buy_rule_number,
    //                 'lth_functionality' => $lth_functionality,
    //                 'lth_profit' => (float) $lth_profit,
    //             );

    //             $check_exist = $this->mod_barrier_trigger->check_of_previous_buy_order_exist_for_current_user($admin_id, $buy_parent_id);

    //             //%%%%%%%%%%%%%% Check of Level Meet %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //             $is_level_meet = true;
    //             if (($recommended_order_level_on_off == 'ON') && $order_level != '') {
    //                 if (in_array($order_level, $recommended_order_level)) {
    //                     $is_level_meet = true;
    //                 } else {
    //                     $is_level_meet = false;
    //                 }
    //             } // -- %%%%%%%%%%  End of Check order Level -- %%%%%%%%%%%%%%%%%%%%%%%%%

    //             if ($check_exist && $is_order_created && $is_level_meet) {
    //                 // exit('&*********************************');
    //                 //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //                 $ins_orders_data = array(
    //                     'symbol' => $coin_symbol,
    //                     'purchased_price' => (float) ($current_market_price),
    //                     'quantity' => $buy_quantity,
    //                     'profit_type' => 'percentage',
    //                     'order_type' => $order_type,
    //                     'admin_id' => $admin_id,
    //                     'buy_order_check' => 'yes',
    //                     'buy_order_binance_id' => '',
    //                     'stop_loss' => 'no',
    //                     'loss_percentage' => '',
    //                     'created_date' => $this->mongo_db->converToMongodttime($date),
    //                     'modified_date' => $this->mongo_db->converToMongodttime($created_date),
    //                     'market_value' => (float) $current_market_price,
    //                     'application_mode' => $application_mode,
    //                     'order_mode' => $order_mode,
    //                     'trigger_type' => $buy_trigger_type,
    //                     'buy_one_tip_above' => $buy_one_tip_above,
    //                     'sell_one_tip_below' => $sell_one_tip_below,
    //                     'order_level' => $order_level,
    //                 );

    //                 $ins_orders_data['sell_profit_percent'] = $sell_per;
    //                 $ins_orders_data['sell_price'] = (float) $sell_price;
    //                 $ins_orders_data['trail_check'] = 'no';
    //                 $ins_orders_data['trail_interval'] = '0';
    //                 $ins_orders_data['sell_trail_price'] = '0';
    //                 $ins_orders_data['status'] = 'new';

    //                 //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //                 $this->mod_barrier_trigger->save_order_time_track($coin_symbol, 'buy');
    //                 $make_new_order_in_orders_collection = true;
    //                 $is_live_order = false;
    //                 if ($application_mode == 'live') {
    //                     $trading_ip = $this->mod_barrier_trigger->get_user_trading_ip($admin_id);
    //                     $is_live_order = true;
    //                     $ins_data_buy_order['status'] = 'new';
    //                     $ins_data_buy_order['trading_ip'] = $trading_ip;
    //                     $buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data_buy_order);
    //                     // %%%%%%%%%%%% store order id in array %%%%%%%%%%%%%%%%%%%%%%%%
    //                     $ins_orders_data['buy_order_id'] = $buy_order_id;
    //                     //%%%%%%%%%%%%%%%%%%%%%% Save Temp orders %%%%%%%%%%%%%%
    //                     $this->mongo_db->insert('temp_ip_orders', $ins_orders_data);
    //                     $this->mod_barrier_trigger->save_rules_for_orders($buy_order_id, $coin_symbol, 'buy', $rule, $mode = 'live');

    //                     if ($order_type == 'limit_order') {
    //                         //Add unit value to current market Price
    //                         //%%%%%%%%%%%%%%%%%%%%%%% Order send
    //                         $log_msg = 'Current Market Price : <b>' . num($current_market_price) . '</b> ';
    //                         $this->mod_box_trigger_3->insert_order_history_log($buy_order_id, $log_msg, 'buy_price', $admin_id, $created_date);

    //                         $send_value_for_buy = $current_market_price + $coin_unit_value;

    //                         if ($buy_one_tip_above == 'yes') {
    //                             $log_msg = 'Limit Order was send for buy With one tick above : <b>' . num($send_value_for_buy) . '</b> ';
    //                             $this->mod_box_trigger_3->insert_order_history_log($buy_order_id, $log_msg, 'buy_price', $admin_id, $created_date);
    //                         } else {
    //                             $send_value_for_buy = $current_market_price;
    //                             $log_msg = 'Limit Order was send for buy With current market price : <b>' . num($send_value_for_buy) . '</b> ';
    //                             $this->mod_box_trigger_3->insert_order_history_log($buy_order_id, $log_msg, 'buy_price', $admin_id, $created_date);
    //                         }

    //                         $log_msg = 'Send order for buy by Ip:<blod>' . $trading_ip . '</bold>';
    //                         $this->mod_barrier_trigger->insert_developer_log($buy_order_id, $log_msg, 'Message', $created_date, $show_error_log);

    //                         $trigger_type = 'barrier_trigger';
    //                         $this->mod_barrier_trigger->order_ready_for_buy_by_ip($buy_order_id, $buy_quantity, $send_value_for_buy, $coin_symbol, $admin_id, $trading_ip, $trigger_type, 'buy_limit_order');
    //                         //%%%%%%%%% Need to delete %%%%

    //                         // $res_limit_order = $this->mod_dashboard->binance_buy_auto_limit_order_live($buy_order_id, $buy_quantity, $send_value_for_buy, $coin_symbol, $admin_id);

    //                         // if(isset($res_limit_order['error'])){
    //                         //     $make_new_order_in_orders_collection = false;
    //                         // }

    //                         $this->mod_limit_order->save_follow_up_of_limit_order($buy_order_id, $type = 'buy');
    //                     } else {

    //                         //%%%%%%%%%%%%%%%%%%%%%%% Order send
    //                         $log_msg = 'Order was send for buy on : <b>' . num($current_market_price) . '</b> ';
    //                         $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'buy_price', $admin_id, $created_date);

    //                         $log_msg = 'Send order for buy by Ip:<blod>' . $trading_ip . '</bold>';

    //                         $this->mod_barrier_trigger->insert_developer_log($buy_order_id, $log_msg, 'Message', $created_date, $show_error_log);

    //                         $trigger_type = 'barrier_trigger';
    //                         $this->mod_barrier_trigger->order_ready_for_buy_by_ip($buy_order_id, $buy_quantity, $current_market_price, $coin_symbol, $admin_id, $trading_ip, $trigger_type, 'buy_market_order');

    //                         // $res_market_order = $this->mod_dashboard->binance_buy_auto_market_order_live($buy_order_id, $buy_quantity, $current_market_price, $coin_symbol, $admin_id);

    //                         // if(isset($res_market_order['error'])){
    //                         //     $make_new_order_in_orders_collection = false;
    //                         // }
    //                     }

    //                     //%%%%%%%%%%%%%%%%55 Live Order Log %%%%%%%%%%%%%%%%%%%%%%%

    //                     $this->mod_barrier_trigger->insert_developer_log($buy_order_id, $log_msg_success, 'Message', $created_date, $show_error_log);

    //                     $log_m = 'Initial Trail Stop is : ' . num($iniatial_trail_stop) . ' Set  By  ' . $stop_loss_rule;

    //                     $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_m, 'buy_commision', $admin_id, $created_date);

    //                     ////////////////////// Set Notification //////////////////
    //                     $message = "Buy Market Order is <b>buyed</b> as status Filled market_price=" . number_format($current_market_price, 8) . "  buy_price  " . number_format($buy_price, 8);
    //                     $this->mod_barrier_trigger->add_notification($buy_order_id, 'buy', $message, $admin_id);
    //                     //////////////////////////////////////////////////////////
    //                     //Check Market History

    //                     //%%%%%%%%%%%%%%%%%% End of Live Order Log %%%%%%%%%%%%%%%%%
    //                 } else {
    //                     // %%%%%%%%%%% Test Order Part Start %%%%%%%%%%%%%%%%%%
    //                     //****************************************************** */
    //                     $ins_data_buy_order['is_sell_order'] = 'yes';
    //                     $ins_data_buy_order['status'] = 'FILLED';
    //                     $ins_data_buy_order['market_value'] = $current_market_price;

    //                     $buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data_buy_order);

    //                     // %%%%%%%%%%%% store order id in array %%%%%%%%%%%%%%%%%%%%%%%%
    //                     $ins_orders_data['buy_order_id'] = $buy_order_id;

    //                     $this->mod_barrier_trigger->save_rules_for_orders($buy_order_id, $coin_symbol, $order_type = 'buy', $rule, $mode = 'test_live');

    //                     //%%%%%%%%%%% Messages Part %%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //                     //Message Lof For Test
    //                     $log_msg = " Order was Buyed at Price " . number_format($current_market_price, 8);
    //                     $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'buy_created', $admin_id, $created_date);

    //                     $this->mod_barrier_trigger->insert_developer_log($buy_order_id, $log_msg_success, 'Message', $created_date, $show_error_log);

    //                     $m_log = 'Initial Trail Stop is : ' . num($iniatial_trail_stop) . '  By Rule ' . $stop_loss_rule;

    //                     $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $m_log, 'buy_commision', $admin_id, $created_date);

    //                     ////////////////////// Set Notification //////////////////
    //                     $message = "Buy Market Order is <b>buyed</b> as status Filled market_price=" . number_format($current_market_price, 8) . "  buy_price  " . number_format($buy_price, 8);
    //                     $this->mod_barrier_trigger->add_notification($buy_order_id, 'buy', $message, $admin_id);

    //                     //Check Market History
    //                     $commission = $buy_quantity * (0.001);
    //                     $commissionAsset = str_replace('BTC', '', $symbol);

    //                     ////////////////////////////// Order History Log /////////////////////////////////////////////
    //                     $log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
    //                     $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'buy_commision', $admin_id, $created_date);
    //                     ////////////////////////////// End Order History Log

    //                     //%%%%%%%%%%%%%%%%% End of Messages Part %%%%%%%%%%%%%%%%%
    //                     $order_id = $this->mongo_db->insert('orders', $ins_orders_data);
    //                     $upd_data22 = array(
    //                         'sell_order_id' => $order_id,
    //                         'is_sell_order' => 'yes',
    //                     );
    //                     $this->mongo_db->where(array('_id' => $buy_order_id));
    //                     $this->mongo_db->set($upd_data22);
    //                     //Update data in mongoTable
    //                     $this->mongo_db->update('buy_orders');
    //                 } //End of test part
    //             } //if open trade Exist
    //         } //End of parent order array
    //     } //End of parent ordr count
    //     echo 'end of function' . date('y-m-d H:i:s');
    // } //End of buy_barrier_trigger_order

    ///////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////                           /////////////////
    ////////////////////                           ////////////////////////////////
    ////////////////////  Sell Order by Profit     /////////////////////////////////
    ////////////////////                           ////////////////////////////////
    ////////////////////                           /////////////////
    ////////////////////                           /////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    // public function go_sell_orders_on_defined_sell_price($market_price, $coin_symbol, $log_arr, $show_error_log, $admin_sell_percentage, $rule, $recommended_order_level_on_off, $recommended_order_level)
    // {
    //     $created_date = date('Y-m-d G:i:s');
    //     $target_sell_price = '';
    //     $buy_orders_arr = $this->mod_barrier_trigger->get_profit_sell_orders($target_sell_price, $coin_symbol);

    //     $coin_unit_value = $this->mod_coins->get_coin_unit_value($coin_symbol);
    //     $is_order_is_send_just_now = $this->mod_barrier_trigger->is_order_is_created_just_now($coin_symbol, 'sell');

    //     if (count($buy_orders_arr) > 0) {
    //         foreach ($buy_orders_arr as $buy_orders) {
    //             $buy_orders_id = $buy_orders['_id'];
    //             $coin_symbol = $buy_orders['symbol'];
    //             $sell_price = $buy_orders['sell_price'];
    //             $admin_id = $buy_orders['admin_id'];
    //             $purchased_price = $buy_orders['price'];
    //             $buy_purchased_price = $buy_orders['market_value'];
    //             $iniatial_trail_stop = $buy_orders['iniatial_trail_stop'];
    //             $application_mode = $buy_orders['application_mode'];
    //             $quantity = $buy_orders['quantity'];
    //             $order_type = $buy_orders['order_type'];
    //             $order_mode = $buy_orders['order_mode'];
    //             $binance_order_id = $buy_orders['binance_order_id'];
    //             $trigger_type = $buy_orders['trigger_type'];
    //             $order_id = $buy_orders['sell_order_id'];
    //             $defined_sell_percentage = $buy_orders['defined_sell_percentage'];

    //             $market_value = $buy_orders['market_value'];
    //             $order_level = $buy_orders['order_level'];

    //             $is_level_meet = true;
    //             if (($recommended_order_level_on_off == 'ON') && $order_level != '') {
    //                 if (in_array($order_level, $recommended_order_level)) {
    //                     $is_level_meet = true;
    //                 } else {
    //                     $is_level_meet = false;
    //                 }
    //             }

    //             //%%%%%%%%%%%%%% Check if rule percentage is empty then sell on defined percentge
    //             if ($admin_sell_percentage == '') {
    //                 $sell_percentage = $defined_sell_percentage;
    //             } else {
    //                 $sell_percentage = $admin_sell_percentage;
    //             }

    //             echo 'sell_percentage ' . $sell_percentage . '************';

    //             $sell_one_tip_below = $buy_orders['sell_one_tip_below'];

    //             $log_message = '';
    //             foreach ($log_arr as $key => $value) {
    //                 $log_message .= $key . ' =>' . $value . '<br>';
    //             }

    //             //If no rule Apply
    //             if ($rule == 0) {
    //                 if ($defined_sell_percentage != '' && $defined_sell_percentage > 0) {
    //                 } else {
    //                     echo 'return false';
    //                     $log_msg = 'Error Sell Percentage <span style="color:red>Empty</span> ';
    //                     $this->mod_box_trigger_3->insert_order_history_log($buy_order_id, $log_msg, 'buy_price', $admin_id, $created_date);
    //                     return false;
    //                 }
    //             }

    //             $target_sell_price = $market_value + ($market_value / 100) * $sell_percentage;

    //             //Check of sell percentag is greatert then zero
    //             $is_sell_percentage_greater_then_zero = false;
    //             if ($sell_percentage > 0) {
    //                 $is_sell_percentage_greater_then_zero = true;
    //             }

    //             //Is sell or status is new
    //             $is_sell_order_status_new = $this->mod_barrier_trigger->is_sell_order_status_new($order_id);

    //             if (($market_price >= $target_sell_price) && $is_sell_percentage_greater_then_zero && $is_sell_order_status_new && $is_level_meet && $is_order_is_send_just_now) {
    //                 $this->mod_barrier_trigger->save_order_time_track($coin_symbol, 'sell');

    //                 if ($defined_sell_percentage) {
    //                     if ($defined_sell_percentage < $admin_sell_percentage) {
    //                         $sell_percentage = $defined_sell_percentage;
    //                         $log_message = 'Message_type =>**********SELL MESSAGE*******<br> Sell Type => Order has been Sold by User Defined percentage :' . $defined_sell_percentage;
    //                     }
    //                 }

    //                 $trading_ip = $this->mod_barrier_trigger->get_user_trading_ip($admin_id);

    //                 $this->mod_barrier_trigger->insert_developer_log($buy_orders_id, $log_message, 'Message Sell', $created_date, $show_error_log);

    //                 /////////////////////////////////////
    //                 ///////////////////////////////////
    //                 $upd_data = array(
    //                     'buy_order_binance_id' => $binance_order_id,
    //                     'market_value' => (float) $market_price,
    //                     'sell_price' => (float) $market_price,
    //                     'modified_date' => $this->mongo_db->converToMongodttime($created_date),
    //                 );

    //                 $this->mongo_db->where(array('_id' => $order_id));
    //                 $this->mongo_db->set($upd_data);
    //                 $this->mongo_db->update('orders');

    //                 //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

    //                 $upd_rules = array(
    //                     'sell_rule_number' => $rule,
    //                 );
    //                 $this->mongo_db->where(array('_id' => $buy_orders_id));
    //                 $this->mongo_db->set($upd_rules);
    //                 //Update data in mongoTable
    //                 $this->mongo_db->update('buy_orders');

    //                 //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

    //                 //Insert data in mongoTable
    //                 if ($application_mode == 'live') {
    //                     if ($this->is_order_already_not_send($buy_orders_id)) {
    //                         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //                         $created_date = date('Y-m-d G:i:s');
    //                         $log_msg = 'Order Send for Sell ON :<b>' . num($market_price) . '</b> Price';
    //                         $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'Sell_Price', $admin_id, $created_date);

    //                         //Target price %%%%%%%%%%%

    //                         $log_msg = 'Order Target Sell Price : <b>' . num($target_sell_price) . '</b> Price';
    //                         $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'target_Price', $admin_id, $created_date);

    //                         //Profit Percentage
    //                         $log_msg = 'Order Profit percentage : <b>' . num($sell_percentage) . '</b> ';
    //                         $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'profit_percantage', $admin_id, $created_date);

    //                         if ($order_type == 'limit_order') {
    //                             if ($sell_one_tip_below == 'yes') {
    //                                 $one_unit_below_value = $market_price - $coin_unit_value;
    //                                 $market_price = $one_unit_below_value;
    //                                 //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //                                 $log_msg = 'Send Limit Order On One tick Below: <b>' . num($market_price) . '</b> ';
    //                                 $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'send_limit_order', $admin_id, $created_date);
    //                             } else {
    //                                 //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //                                 $log_msg = 'Send Limit Order On Current Market Price: <b>' . num($market_price) . '</b> ';
    //                                 $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'send_limit_order', $admin_id, $created_date);
    //                             }

    //                             //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

    //                             // $res_limit_order = $this->mod_dashboard->binance_sell_auto_limit_order_live($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id);

    //                             $log_msg = 'Send Limit Orde for sell by Ip: <b>' . $trading_ip . '</b> ';

    //                             $this->mod_barrier_trigger->insert_developer_log($buy_orders_id, $log_msg, 'Message', $created_date, $show_error_log);

    //                             $trigger_type = 'barrier_trigger';
    //                             $this->mod_barrier_trigger->order_ready_for_sell_by_ip($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id, $trading_ip, $trigger_type, 'sell_limit_order');

    //                             // //if No Error Occure
    //                             // if(!isset($res_limit_order['error'])){
    //                             //     $this->mod_limit_order->save_follow_up_of_limit_sell_order($order_id,$buy_orders_id,$type='sell');
    //                             // }
    //                         } elseif ($order_type == 'stop_loss_limit_order') {
    //                             $log_msg = 'Send stop loss limit order by Profit On Current Market Price: <b>' . num($market_price) . '</b> ';
    //                             $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'send_limit_order', $admin_id, $created_date);

    //                             $log_msg = 'Send stop loss limit order by stop_loss On trail stop price: <b>' . num($iniatial_trail_stop) . '</b> ';
    //                             $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'send_limit_order', $admin_id, $created_date);

    //                             $res_limit_order = $this->mod_dashboard->binance_sell_auto_stop_loss_limit_order_live($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id, $iniatial_trail_stop);
    //                         } else {
    //                             $log_msg = 'Send Market Orde for sell by Ip: <b>' . $trading_ip . '</b> ';
    //                             $this->mod_barrier_trigger->insert_developer_log($buy_orders_id, $log_msg, 'Message', $created_date, $show_error_log);

    //                             $trigger_type = 'barrier_trigger';
    //                             $this->mod_barrier_trigger->order_ready_for_sell_by_ip($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id, $trading_ip, $trigger_type, 'sell_market_order');

    //                             // $this->mod_dashboard->binance_sell_auto_market_order_live($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id);
    //                         }

    //                         $this->mod_barrier_trigger->save_rules_for_orders($buy_orders_id, $coin_symbol, $order_type = 'sell', $rule, $mode = 'live');
    //                     } //End of if order already not send
    //                 } else {
    //                     //Sell With Normal Value
    //                     $upd_data_1 = array(
    //                         'status' => 'FILLED',
    //                     );
    //                     $this->mongo_db->where(array('_id' => $order_id));
    //                     $this->mongo_db->set($upd_data_1);
    //                     //Update data in mongoTable
    //                     $this->mongo_db->update('orders');
    //                     $upd_data = array(
    //                         'sell_order_id' => $order_id,
    //                         'is_sell_order' => 'sold',
    //                         'market_sold_price' => (float) $market_price,
    //                         'modified_date' => $this->mongo_db->converToMongodttime($created_date),
    //                     );
    //                     $this->mongo_db->where(array('_id' => $buy_orders_id));
    //                     $this->mongo_db->set($upd_data);
    //                     //Update data in mongoTable
    //                     $this->mongo_db->update('buy_orders');

    //                     $this->mod_barrier_trigger->save_rules_for_orders($buy_orders_id, $coin_symbol, $order_type = 'sell', $rule, $mode = 'test_live');

    //                     $message = 'Sell Order was Sold With profit';

    //                     //////////////////////////////////////////////////////////////////////////////
    //                     ////////////////////////////// Order History Log /////////////////////////////
    //                     $log_msg = $message . " " . number_format($market_price, 8);
    //                     $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'sell_created', $admin_id, $created_date);
    //                     ////////////////////////////// End Order History Log /////////////////////////
    //                     //////////////////////////////////////////////////////////////////////////////
    //                     ////////////////////// Set Notification //////////////////
    //                     $message = $message . " <b>Sold</b>";
    //                     $this->mod_box_trigger_3->add_notification($buy_orders_id, 'buy', $message, $admin_id);
    //                     //////////////////////////////////////////////////////////
    //                     //Check Market History
    //                     $commission_value = $quantity * (0.001);
    //                     $commission = $commission_value * $with;
    //                     $commissionAsset = 'BTC';
    //                     //////////////////////////////////////////////////////////////////////////////////////////////
    //                     ////////////////////////////// Order History Log /////////////////////////////////////////////
    //                     $log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
    //                     $created_date = date('Y-m-d G:i:s');
    //                     $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'sell_commision', $admin_id, $created_date);
    //                     ////////////////////////////// End Order History Log
    //                 } //if test live order
    //             } //if markt price is greater then sell order
    //         } //End  of forEach buy orders
    //     } //Check of orders found
    // } //End of sell_orders_on_defined_sell_price

    ///////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////                           /////////////////
    ////////////////////                           ////////////////////////////////
    ////////////////////  test                     /////////////////////////////////
    ////////////////////                           ////////////////////////////////
    ////////////////////                           /////////////////
    ////////////////////                           /////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    // public function go_buy_rules($coin_symbol)
    // {
    //     $current_market_price = $this->mod_dashboard->get_market_value($coin_symbol);
    //     $current_market_price = (float) $current_market_price;

    //     //Check if market price is empty
    //     $this->mod_barrier_trigger->is_market_price_empty($current_market_price);

    //     $rule_meet_arr = array();
    //     $coin_meta_arr = $this->mod_barrier_trigger->get_coin_meta_data($coin_symbol);
    //     $coin_meta_arr = (array) $coin_meta_arr[0];

    //     for ($rule_number = 1; $rule_number <= 10; $rule_number++) {
    //         $buy_arr = $this->go_buy($coin_symbol, $rule_number, $coin_meta_arr);

    //         echo '<pre>';
    //         print_r($buy_arr);

    //         $buy_arr_1 = $buy_arr;
    //         unset($buy_arr_1['Rule_' . $rule_number]);
    //         $rule_arr_message = $buy_arr['Rule_' . $rule_number];
    //         $new = array();
    //         if (!empty($rule_arr_message)) {
    //             foreach ($rule_arr_message as $key => $value) {
    //                 if ($value == '<span style="color:green">YES</span>') {
    //                     $new_key = '<span style="background-color:yellow">' . $key . '</span>';
    //                     $new[$new_key] = '<span style="background-color:yellow">' . $value . '</span>';
    //                 } elseif ($value == '<span style="color:red">NO</span>') {
    //                     $new_key = '<span style="background-color:#ede1e1">' . $key . '</span>';
    //                     $new[$new_key] = '<span style="background-color:#ede1e1">' . $value . '</span>';
    //                 } else {
    //                     $new[$key] = $value;
    //                 }
    //             }
    //         }

    //         $buy_arr_1['Rule_' . $rule_number] = $new;

    //         $buy_arr['Rule_' . $rule_number] = $new;
    //         $log_arr = $buy_arr['Rule_' . $rule_number];

    //         $sell_per = $buy_arr['sell_per'];
    //         $stop_loss_percent = $buy_arr['stop_loss_percent'];
    //         $aggressive_stop_rule = $buy_arr['aggressive_stop_rule'];

    //         $recommended_order_level = $buy_arr['recommended_order_level'];
    //         $recommended_order_level_on_off = $buy_arr['recommended_order_level_on_off'];

    //         if ($buy_arr['response_message'] == 'YES') {

    //             //Funcion For Locking true rules
    //             $this->mod_barrier_trigger->lock_barrier_trigger_true_rules($coin_symbol, $rule_number, $type = 'buy', $current_market_price, $buy_arr);
    //             $recommended_order_level_arr = array();
    //             if (empty($rule_meet_arr)) {
    //                 $rule_meet_arr['recommended_order_level'] = $recommended_order_level;
    //                 $rule_meet_arr['recommended_order_level_on_off'] = $recommended_order_level_on_off;
    //                 $rule_meet_arr['rule_number'] = $rule_number;
    //                 $rule_meet_arr['sell_per'] = $sell_per;
    //                 $rule_meet_arr['log_arr'] = $log_arr;
    //                 $rule_meet_arr['stop_loss_percent'] = $stop_loss_percent;
    //                 $rule_meet_arr['aggressive_stop_rule'] = $aggressive_stop_rule;
    //             }
    //         }
    //     } //End of rules loop

    //     if (!empty($rule_meet_arr)) {
    //         $recommended_order_level = $rule_meet_arr['recommended_order_level'];
    //         $recommended_order_level_on_off = $rule_meet_arr['recommended_order_level_on_off'];

    //         $log_arr = $rule_meet_arr['log_arr'];
    //         $sell_per = $rule_meet_arr['sell_per'];
    //         $stop_loss_percent = $rule_meet_arr['stop_loss_percent'];
    //         $aggressive_stop_rule = $rule_meet_arr['aggressive_stop_rule'];
    //         $show_error_log = 'yes';
    //         $date = date('Y-m-d H:i:s');
    //         $buy_rule_number = $rule_meet_arr['rule_number'];

    //         $this->go_buy_barrier_trigger_order($date, $current_market_price, $coin_symbol, $sell_per, $stop_loss_percent, $log_arr, $show_error_log, $aggressive_stop_rule, $recommended_order_level, $recommended_order_level_on_off, $buy_rule_number);
    //         echo 'Condition Meet for order creation';
    //     } //End of rule_meet_arr
    // } //End of go_buy_rules

    // public function go_sell_rules($coin_symbol)
    // {
    //     $current_market_price = $this->mod_dashboard->get_market_value($coin_symbol);
    //     $current_market_price = (float) $current_market_price;

    //     //Check if market price is empty
    //     $this->mod_barrier_trigger->is_market_price_empty($current_market_price);

    //     $coin_meta_arr = $this->mod_barrier_trigger->get_coin_meta_data($coin_symbol);
    //     $coin_meta_arr = (array) $coin_meta_arr[0];

    //     $rule_meet_arr = array();
    //     for ($rule_number = 1; $rule_number <= 10; $rule_number++) {
    //         $sell_arr = $this->go_sell($coin_symbol, $rule_number, $coin_meta_arr);

    //         $sell_percentage = $sell_arr['sell_percent_rule_' . $rule_number];
    //         $recommended_order_level_on_off = $sell_arr['recommended_order_level_on_off'];
    //         $recommended_order_level = $sell_arr['recommended_order_level'];

    //         $log_arr = $sell_arr['Rule_' . $rule_number];

    //         /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */
    //         $sell_arr_1 = $sell_arr;
    //         unset($sell_arr_1['Rule_' . $rule_number]);
    //         $rule_arr_message = $sell_arr['Rule_' . $rule_number];
    //         $new = array();
    //         if (!empty($rule_arr_message)) {
    //             foreach ($rule_arr_message as $key => $value) {
    //                 if ($value == '<span style="color:green">YES</span>') {
    //                     $new_key = '<span style="background-color:yellow">' . $key . '</span>';
    //                     $new[$new_key] = '<span style="background-color:yellow">' . $value . '</span>';
    //                 } elseif ($value == '<span style="color:red">NO</span>') {
    //                     $new_key = '<span style="background-color:#ede1e1">' . $key . '</span>';
    //                     $new[$new_key] = '<span style="background-color:#ede1e1">' . $value . '</span>';
    //                 } else {
    //                     $new[$key] = $value;
    //                 }
    //             }
    //         }

    //         $sell_arr_1['Rule_' . $rule_number] = $new;

    //         $sell_arr['Rule_' . $rule_number] = $new;
    //         $log_arr = $sell_arr['Rule_' . $rule_number];
    //         /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */

    //         echo '<pre>';
    //         print_r($sell_arr);

    //         if ($sell_arr['response_message'] == 'YES') {
    //             //CALL function to lock barrier trigger success rule
    //             $this->mod_barrier_trigger->lock_barrier_trigger_true_rules($coin_symbol, $rule_number, $type = 'sell', $current_market_price, $sell_arr);

    //             if (empty($rule_meet_arr)) {
    //                 $rule_meet_arr['recommended_order_level_on_off'] = $recommended_order_level_on_off;
    //                 $rule_meet_arr['recommended_order_level'] = $recommended_order_level;

    //                 $rule_meet_arr['rule_number'] = $rule_number;
    //                 $rule_meet_arr['sell_percentage'] = $sell_percentage;
    //                 $rule_meet_arr['log_arr'] = $log_arr;
    //             } else {
    //                 $previous_percentage = $rule_meet_arr['sell_percentage'];
    //                 if ($sell_percentage < $previous_percentage) {
    //                     $rule_meet_arr['recommended_order_level_on_off'] = $recommended_order_level_on_off;
    //                     $rule_meet_arr['recommended_order_level'] = $recommended_order_level;

    //                     $rule_meet_arr['rule_number'] = $rule_number;
    //                     $rule_meet_arr['sell_percentage'] = $sell_percentage;
    //                     $rule_meet_arr['log_arr'] = $log_arr;
    //                 } //End of
    //             } //End of
    //         }
    //     }

    //     /************************* */

    //     $show_error_log = 'yes';
    //     $date = date('Y-m-d H:i:s');
    //     if (!empty($rule_meet_arr)) {
    //         $log_arr = $rule_meet_arr['log_arr'];
    //         $sell_percentage = $rule_meet_arr['sell_percentage'];
    //         $rule_number = $rule_meet_arr['rule_number'];
    //         $recommended_order_level_on_off = $rule_meet_arr['recommended_order_level_on_off'];
    //         $recommended_order_level = $rule_meet_arr['recommended_order_level'];

    //         $this->go_sell_orders_on_defined_sell_price($current_market_price, $coin_symbol, $log_arr, $show_error_log, $sell_percentage, $rule_number, $recommended_order_level_on_off, $recommended_order_level);
    //     } else {
    //         $log_arr = array('Message_type' => '**********SELL MESSAGE*******', 'Sell Type' => 'Order has been Sold by User Defined Value');
    //         $rule_number = 0;
    //         $sell_percentage = '';
    //         $this->go_sell_orders_on_defined_sell_price($current_market_price, $coin_symbol, $log_arr, $show_error_log, $sell_percentage, $rule_number, $recommended_order_level_on_off, $recommended_order_level);
    //     } //If Not Empty
    // } //End of go_sell_rules

    // public function go_sell($coin_symbol, $rule_number, $coin_meta_arr)
    // {
    //     extract($coin_meta_arr);
    //     $date = date('Y-m-d H:i:s');
    //     $triggers_type = 'barrier_trigger';
    //     $order_mode = 'live';
    //     $rule = 'Rule_' . $rule_number;

    //     $global_setting_arr = $this->mod_barrier_trigger->get_trigger_global_setting($triggers_type, $order_mode, $coin_symbol);
    //     $global_setting_arr = $global_setting_arr[0];

    //     $log_arr = array('Message_type' => '*******SEll Message**');

    //     $rule_on_off_setting = $global_setting_arr['enable_sell_rule_no_' . $rule_number];
    //     if ($rule_on_off_setting == 'not' || $rule_on_off_setting == '') {
    //         $log_arr['Rule_NO_' . $rule_number . '_Off'] = '<span style="color:red">OFF</span>';
    //         return $log_arr;
    //     }

    //     $log_arr['rule_sort'] = $global_setting_arr['rule_sort' . $rule_number . '_sell'];

    //     //%%%%%%%%%%%%%%%%% Check if Level For Buy  %%%%%%%%%%%%%%%%%%

    //     $sell_order_level_on_off = $global_setting_arr['sell_order_level_' . $rule_number . '_enable'];
    //     $recommended_order_level = $global_setting_arr['sell_order_level_' . $rule_number];
    //     if ($sell_order_level_on_off == 'not' || $sell_order_level_on_off == '') {
    //         $log_arr['sell_order_level' . $rule_number] = '<span style="color:red">OFF</span>';
    //         $data['recommended_order_level_on_off'] = 'OFF';
    //     } else {
    //         //%%%%%%%%%%%%%%%%%%%%%%% On %%%%%%%%%%%%%%%%%%%%%%%%%
    //         $log_arr['sell_order_level' . $rule_number] = '<span style="color:green">ON</span>';
    //         $log_arr['recommended_order_level_' . $rule_number] = implode(',', (array) $recommended_order_level);

    //         $data['recommended_order_level_on_off'] = 'ON';
    //     }
    //     //%%%%%%%%%%%%%%%%% Recommended  buy levels%%%%%%%%%%%%%%%%%%%%%%%%%
    //     $data['recommended_order_level'] = (array) $recommended_order_level;

    //     $data['sell_percent_rule_' . $rule_number] = $global_setting_arr['sell_percent_rule_' . $rule_number];

    //     //%%%%%%%%%%%%%%%% status Rule %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //     $rule_on_off = 'sell_status_rule_' . $rule_number . '_enable';
    //     $rule_name = 'sell_status_rule_' . $rule_number;

    //     $status_rule_1 = $this->sell_rule_status($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $status_rule_1['log_arr']));

    //     $status_rule_1_result = false;
    //     if ($status_rule_1['success_message'] == 'YES' || $status_rule_1['success_message'] == 'OFF') {
    //         $status_rule_1_result = true;
    //     }

    //     //%%%%%%%%%%%%%%%%%%%%% Rule End %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

    //     //%%%%%%%%%%%%%%%%% check candle procedding status %%%%%%%%%%%%%%%%%%%
    //     $rule_on_off = 'sell_last_candle_status' . $rule_number . '_enable';
    //     $rule_name = 'last_candle_status' . $rule_number . '_sell';

    //     $candle_procedding_status = $this->sell_candle_lalst_procedding_status($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $candle_procedding_status['log_arr']));

    //     $candle_procedding_status_result = false;
    //     if ($candle_procedding_status['success_message'] == 'YES' || $candle_procedding_status['success_message'] == 'OFF') {
    //         $candle_procedding_status_result = true;
    //     }

    //     //%%%%%%%%%%%%%%%% End of candle Procedding status %%%%%%%%%%%%%%%%%%%

    //     $rule_on_off = 'sell_check_volume_rule_' . $rule_number;
    //     $rule_name = 'sell_volume_rule_' . $rule_number;

    //     $barrier_volume_rule_1 = $this->sell_rule_barrier_volume($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $rule_number);

    //     $log_arr = (array_merge($log_arr, $barrier_volume_rule_1['log_arr']));

    //     $barrier_volume_rule_1_result = false;
    //     if ($barrier_volume_rule_1['success_message'] == 'YES' || $barrier_volume_rule_1['success_message'] == 'OFF') {
    //         $barrier_volume_rule_1_result = true;
    //     }

    //     $rule_on_off = 'done_pressure_rule_' . $rule_number . '_enable';
    //     $rule_name = 'done_pressure_rule_' . $rule_number;

    //     $down_pressure_rule_1 = $this->sell_rule_down_pressure($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $down_pressure_rule_1['log_arr']));

    //     $down_pressure_rule_1_result = false;
    //     if ($down_pressure_rule_1['success_message'] == 'YES' || $down_pressure_rule_1['success_message'] == 'OFF') {
    //         $down_pressure_rule_1_result = true;
    //     }

    //     $rule_on_off = 'big_seller_percent_compare_rule_' . $rule_number . '_enable';
    //     $rule_name = 'big_seller_percent_compare_rule_' . $rule_number;

    //     $big_seller_rule_1 = $this->sell_rule_big_seller($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $big_seller_rule_1['log_arr']));

    //     $big_seller_rule_1_rule_1_result = false;
    //     if ($big_seller_rule_1['success_message'] == 'YES' || $big_seller_rule_1['success_message'] == 'OFF') {
    //         $big_seller_rule_1_rule_1_result = true;
    //     }

    //     $rule_on_off = 'closest_black_wall_rule_' . $rule_number . '_enable';
    //     $rule_name = 'closest_black_wall_rule_' . $rule_number;

    //     $black_wall_rule_1 = $this->sell_rule_black_wall($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $black_wall_rule_1['log_arr']));

    //     $black_wall_rule_1_result = false;
    //     if ($black_wall_rule_1['success_message'] == 'YES' || $black_wall_rule_1['success_message'] == 'OFF') {
    //         $black_wall_rule_1_result = true;
    //     }

    //     $rule_on_off = 'closest_yellow_wall_rule_' . $rule_number . '_enable';
    //     $rule_name = 'closest_yellow_wall_rule_' . $rule_number;

    //     $yellow_wall_rule_1 = $this->sell_rule_yellow_wall($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $yellow_wall_rule_1['log_arr']));

    //     $yellow_wall_rule_1_result = false;
    //     if ($yellow_wall_rule_1['success_message'] == 'YES' || $yellow_wall_rule_1['success_message'] == 'OFF') {
    //         $yellow_wall_rule_1_result = true;
    //     }

    //     $rule_on_off = 'seven_level_pressure_rule_' . $rule_number . '_enable';
    //     $rule_name = 'seven_level_pressure_rule_' . $rule_number;

    //     $seven_level_rule_1 = $this->sell_rule_seven_level($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $seven_level_rule_1['log_arr']));

    //     $seven_level_rule_1_result = false;
    //     if ($seven_level_rule_1['success_message'] == 'YES' || $seven_level_rule_1['success_message'] == 'OFF') {
    //         $seven_level_rule_1_result = true;
    //     }

    //     /****************seller_vs_buyer_rule_1_sell **************/
    //     $rule_on_off = 'seller_vs_buyer_rule_' . $rule_number . '_sell_enable';
    //     $rule_name = 'seller_vs_buyer_rule_' . $rule_number . '_sell';

    //     $seller_vs_buyer_rule = $this->seller_vs_buyer($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $seller_vs_buyer_rule['log_arr']));

    //     $seller_vs_buyer_rule_result = false;
    //     if ($seller_vs_buyer_rule['success_message'] == 'YES' || $seller_vs_buyer_rule['success_message'] == 'OFF') {
    //         $seller_vs_buyer_rule_result = true;
    //     }

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     $rule_on_off = 'sell_last_candle_type' . $rule_number . '_enable';
    //     $rule_name = 'last_candle_type' . $rule_number . '_sell';

    //     $is_candle_type = $this->is_candle_type_sell($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_candle_type);

    //     $log_arr = (array_merge($log_arr, $is_candle_type['log_arr']));

    //     $is_last_candle_type_result = false;
    //     if ($is_candle_type['success_message'] == 'YES' || $is_candle_type['success_message'] == 'OFF') {
    //         $is_last_candle_type_result = true;
    //     }
    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     $rule_on_off = 'sell_rejection_candle_type' . $rule_number . '_enable';
    //     $rule_name = 'rejection_candle_type' . $rule_number . '_sell';

    //     $is_rejection_candle = $this->is_rejection_candle_sell($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_candle_rejection_status);

    //     $log_arr = (array_merge($log_arr, $is_rejection_candle['log_arr']));

    //     $is_rejection_candle_result = false;
    //     if ($is_rejection_candle['success_message'] == 'YES' || $is_rejection_candle['success_message'] == 'OFF') {
    //         $is_rejection_candle_result = true;
    //     }

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     $rule_on_off = 'sell_last_200_contracts_buy_vs_sell' . $rule_number . '_enable';
    //     $rule_name = 'last_200_contracts_buy_vs_sell' . $rule_number . '_sell';
    //     $is_last_200_contracts_buy_vs_sell = $this->is_last_200_contracts_buy_vs_sell_rule($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_200_buy_vs_sell);

    //     $log_arr = (array_merge($log_arr, $is_last_200_contracts_buy_vs_sell['log_arr']));

    //     $is_last_200_contracts_buy_vs_sell_result = false;
    //     if ($is_last_200_contracts_buy_vs_sell['success_message'] == 'YES' || $is_last_200_contracts_buy_vs_sell['success_message'] == 'OFF') {
    //         $is_last_200_contracts_buy_vs_sell_result = true;
    //     }

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     $rule_on_off = 'sell_last_200_contracts_time' . $rule_number . '_enable';
    //     $rule_name = 'last_200_contracts_time' . $rule_number . '_sell';

    //     $is_last_200_contracts_time = $this->is_last_200_contracts_time_sell($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_200_time_ago);
    //     $log_arr = (array_merge($log_arr, $is_last_200_contracts_time['log_arr']));

    //     $is_last_200_contracts_time_result = false;
    //     if ($is_last_200_contracts_time['success_message'] == 'YES' || $is_last_200_contracts_time['success_message'] == 'OFF') {
    //         $is_last_200_contracts_time_result = true;
    //     }

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     $rule_on_off = 'sell_last_qty_buyers_vs_seller' . $rule_number . '_enable';
    //     $rule_name = 'last_qty_buyers_vs_seller' . $rule_number . '_sell';

    //     $is_last_qty_buyers_vs_seller = $this->is_last_qty_buyers_vs_seller_sell($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_qty_buy_vs_sell);

    //     $log_arr = (array_merge($log_arr, $is_last_qty_buyers_vs_seller['log_arr']));

    //     $is_last_qty_buyers_vs_seller_result = false;
    //     if ($is_last_qty_buyers_vs_seller['success_message'] == 'YES' || $is_last_qty_buyers_vs_seller['success_message'] == 'OFF') {
    //         $is_last_qty_buyers_vs_seller_result = true;
    //     }

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     $rule_on_off = 'sell_last_qty_time' . $rule_number . '_enable';
    //     $rule_name = 'last_qty_time' . $rule_number . '_sell';

    //     $is_last_qty_time = $this->is_last_qty_time_sell($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_qty_time_ago);

    //     $log_arr = (array_merge($log_arr, $is_last_qty_time['log_arr']));

    //     $is_last_qty_time_result = false;
    //     if ($is_last_qty_time['success_message'] == 'YES' || $is_last_qty_time['success_message'] == 'OFF') {
    //         $is_last_qty_time_result = true;
    //     }

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
    //     $rule_on_off = 'sell_score' . $rule_number . '_enable';
    //     $rule_name = 'score' . $rule_number . '_sell';

    //     $is_score = $this->is_score_sell($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $score);

    //     $log_arr = (array_merge($log_arr, $is_score['log_arr']));

    //     $is_score_result = false;
    //     if ($is_score['success_message'] == 'YES' || $is_score['success_message'] == 'OFF') {
    //         $is_score_result = true;
    //     }

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     //%%%%%%%%%%%%%%%%%%%%%%%%%%%%% seller VS Buyser fifteen minute %%%%%%%%%%%%%%%

    //     $rule_on_off = 'buyers_vs_sellers_sell' . $rule_number . '_enable';
    //     $rule_name = 'buyers_vs_sellers' . $rule_number . '_sell';

    //     $buyers_fifteen_response = $this->is_buyers_vs_sellers_fifteen_for_sell($rule_on_off, $rule_name, $coin_meta_arr, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $buyers_fifteen_response['log_arr']));
    //     $buyers_fifteen_result = false;
    //     if ($buyers_fifteen_response['success_message'] == 'YES' || $buyers_fifteen_response['success_message'] == 'OFF') {
    //         $buyers_fifteen_result = true;
    //     }
    //     //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  End of buyeer fifteen minute %%%%%%%%%

    //     /****************End seller_vs_buyer_rule_1_sell **************/
    //     $meet_all_condition_for_rule_1 = false;
    //     if ($status_rule_1_result && $barrier_volume_rule_1_result && $down_pressure_rule_1_result && $big_seller_rule_1_rule_1_result && $black_wall_rule_1_result && $yellow_wall_rule_1_result && $seven_level_rule_1_result && $seller_vs_buyer_rule_result & $is_last_candle_type_result && $is_rejection_candle_result && $is_last_200_contracts_buy_vs_sell_result && $is_last_200_contracts_time_result && $is_last_qty_buyers_vs_seller_result && $is_last_qty_time_result && $is_score_result && $candle_procedding_status_result && $buyers_fifteen_result) {
    //         $meet_all_condition_for_rule_1 = true;
    //     }

    //     $response_message = 'NO';
    //     if ($meet_all_condition_for_rule_1) {
    //         $response_message = 'YES';
    //     }

    //     $data['Rule_' . $rule_number] = $log_arr;
    //     $data['response_message'] = $response_message;
    //     return $data;
    // } //End of Function

    // public function go_buy($coin_symbol, $rule_number, $coin_meta_arr)
    // {
    //     extract($coin_meta_arr);

    //     $date = date('Y-m-d H:i:s');
    //     $triggers_type = 'barrier_trigger';
    //     $order_mode = 'live';
    //     $rule = 'Rule_' . $rule_number;

    //     $global_setting_arr = $this->mod_barrier_trigger->get_trigger_global_setting($triggers_type, $order_mode, $coin_symbol);

    //     $global_setting_arr = $global_setting_arr[0];

    //     $log_arr = array('Message_type' => '********Buy Message *************');

    //     $rule_on_off_setting = $global_setting_arr['enable_buy_rule_no_' . $rule_number];

    //     if ($rule_on_off_setting == 'not' || $rule_on_off_setting == '') {
    //         $log_arr['Rule_NO_' . $rule_number . '_Off'] = '<span style="color:red">OFF</span>';
    //         return $log_arr;
    //     }

    //     $log_arr['rule_sort'] = $global_setting_arr['order_status' . $rule_number . '_buy'];

    //     //%%%%%%%%%%%%%%%%% Check if Level For Buy  %%%%%%%%%%%%%%%%%%

    //     $buy_order_level_on_off = $global_setting_arr['buy_order_level_' . $rule_number . '_enable'];
    //     $recommended_order_level = $global_setting_arr['buy_order_level_' . $rule_number];

    //     if ($buy_order_level_on_off == 'not' || $buy_order_level_on_off == '') {
    //         $log_arr['buy_order_level'] = '<span style="color:red">OFF</span>';
    //         $data['recommended_order_level_on_off'] = 'OFF';
    //     } else {
    //         //%%%%%%%%%%%%%%%%%%%%%%% On %%%%%%%%%%%%%%%%%%%%%%%%%
    //         $log_arr['buy_order_level'] = '<span style="color:green">ON</span>';
    //         $log_arr['recommended_order_level'] = implode(',', (array) $recommended_order_level);

    //         $data['recommended_order_level_on_off'] = 'ON';
    //     }
    //     //%%%%%%%%%%%%%%%%% Recommended  buy levels%%%%%%%%%%%%%%%%%%%%%%%%%
    //     $data['recommended_order_level'] = (array) $recommended_order_level;

    //     $sell_per = $global_setting_arr['sell_profit_percet'];
    //     $stop_loss_percent = $global_setting_arr['stop_loss_percet'];

    //     $data['sell_per'] = $sell_per;
    //     $data['stop_loss_percent'] = $stop_loss_percent;
    //     $data['aggressive_stop_rule'] = $global_setting_arr['aggressive_stop_rule'];

    //     $rule_on_off = 'buy_status_rule_' . $rule_number . '_enable';
    //     $rule_name = 'buy_status_rule_' . $rule_number;

    //     $status_rule_1 = $this->buy_rule_status($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $status_rule_1['log_arr']));

    //     $test_arr = array();

    //     $status_rule_1_result = false;
    //     if ($status_rule_1['success_message'] == 'YES' || $status_rule_1['success_message'] == 'OFF') {
    //         $status_rule_1_result = true;
    //     }

    //     $test_arr['status_rule_1'] = $status_rule_1['success_message'];

    //     $rule_on_off = 'buy_check_volume_rule_' . $rule_number;
    //     $rule_name = 'buy_volume_rule_' . $rule_number;

    //     $barrier_volume_rule_1 = $this->buy_rule_barrier_volume($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $rule_number);
    //     $log_arr = (array_merge($log_arr, $barrier_volume_rule_1['log_arr']));

    //     $barrier_volume_rule_1_result = false;
    //     if ($barrier_volume_rule_1['success_message'] == 'YES' || $barrier_volume_rule_1['success_message'] == 'OFF') {
    //         $barrier_volume_rule_1_result = true;
    //     }

    //     $test_arr['barrier_volume_rule_1'] = $barrier_volume_rule_1['success_message'];

    //     $rule_on_off = 'done_pressure_rule_' . $rule_number . '_buy_enable';
    //     $rule_name = 'done_pressure_rule_' . $rule_number . '_buy';

    //     $down_pressure_rule_1 = $this->buy_rule_down_pressure($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $down_pressure_rule_1['log_arr']));

    //     $down_pressure_rule_1_result = false;
    //     if ($down_pressure_rule_1['success_message'] == 'YES' || $down_pressure_rule_1['success_message'] == 'OFF') {
    //         $down_pressure_rule_1_result = true;
    //     }

    //     $test_arr['down_pressure_rule_1'] = $barrier_volume_rule_1['success_message'];

    //     //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //     $rule_on_off = 'big_seller_percent_compare_rule_' . $rule_number . '_buy_enable';
    //     $rule_name = 'big_seller_percent_compare_rule_' . $rule_number . '_buy';

    //     $big_seller_rule_1 = $this->buy_rule_big_seller($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $big_seller_rule_1['log_arr']));

    //     $big_seller_rule_1_rule_1_result = false;
    //     if ($big_seller_rule_1['success_message'] == 'YES' || $big_seller_rule_1['success_message'] == 'OFF') {
    //         $big_seller_rule_1_rule_1_result = true;
    //     }

    //     $test_arr['big_seller_rule_1'] = $big_seller_rule_1['success_message'];
    //     //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

    //     //%%%%%%%%%%%%%%%%% check candle procedding status %%%%%%%%%%%%%%%%%%%

    //     $rule_on_off = 'buy_last_candle_status' . $rule_number . '_enable';
    //     $rule_name = 'last_candle_status' . $rule_number . '_buy';

    //     $candle_procedding_status = $this->buy_candle_lalst_procedding_status($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);
    //     $log_arr = (array_merge($log_arr, $candle_procedding_status['log_arr']));

    //     $candle_procedding_status_result = false;
    //     if ($candle_procedding_status['success_message'] == 'YES' || $candle_procedding_status['success_message'] == 'OFF') {
    //         $candle_procedding_status_result = true;
    //     }

    //     //%%%%%%%%%%%%%%%% End of candle Procedding status %%%%%%%%%%%%%%%%%%%

    //     $rule_on_off = 'closest_black_wall_rule_' . $rule_number . '_buy_enable';
    //     $rule_name = 'closest_black_wall_rule_' . $rule_number . '_buy';

    //     $black_wall_rule_1 = $this->buy_rule_black_wall($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $black_wall_rule_1['log_arr']));

    //     $black_wall_rule_1_result = false;
    //     if ($black_wall_rule_1['success_message'] == 'YES' || $black_wall_rule_1['success_message'] == 'OFF') {
    //         $black_wall_rule_1_result = true;
    //     }

    //     $test_arr['black_wall_rule_1'] = $black_wall_rule_1['success_message'];

    //     $rule_on_off = 'closest_yellow_wall_rule_' . $rule_number . '_buy_enable';
    //     $rule_name = 'closest_yellow_wall_rule_' . $rule_number . '_buy';

    //     $yellow_wall_rule_1 = $this->buy_rule_yellow_wall($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $yellow_wall_rule_1['log_arr']));

    //     $yellow_wall_rule_1_result = false;
    //     if ($yellow_wall_rule_1['success_message'] == 'YES' || $yellow_wall_rule_1['success_message'] == 'OFF') {
    //         $yellow_wall_rule_1_result = true;
    //     }

    //     $test_arr['yellow_wall_rule_1'] = $yellow_wall_rule_1['success_message'];

    //     $rule_on_off = 'seven_level_pressure_rule_' . $rule_number . '_buy_enable';
    //     $rule_name = 'seven_level_pressure_rule_' . $rule_number . '_buy';

    //     $seven_level_rule_1 = $this->buy_rule_seven_level($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $seven_level_rule_1['log_arr']));

    //     $seven_level_rule_1_result = false;
    //     if ($seven_level_rule_1['success_message'] == 'YES' || $seven_level_rule_1['success_message'] == 'OFF') {
    //         $seven_level_rule_1_result = true;
    //     }

    //     /************Buy buyer_vs_seller_rule_1_buy **************/

    //     $rule_on_off = 'buyer_vs_seller_rule_' . $rule_number . '_buy_enable';
    //     $rule_name = 'buyer_vs_seller_rule_' . $rule_number . '_buy';

    //     $buyer_vs_seller_rule = $this->buyer_vs_seller($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $buyer_vs_seller_rule['log_arr']));

    //     $buyer_vs_seller_rule_result = false;
    //     if ($buyer_vs_seller_rule['success_message'] == 'YES' || $buyer_vs_seller_rule['success_message'] == 'OFF') {
    //         $buyer_vs_seller_rule_result = true;
    //     }

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
    //     $rule_on_off = 'buy_last_candle_type' . $rule_number . '_enable';
    //     $rule_name = 'last_candle_type' . $rule_number . '_buy';

    //     $is_candle_type = $this->is_candle_type($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_candle_type);

    //     $log_arr = (array_merge($log_arr, $is_candle_type['log_arr']));

    //     $is_last_candle_type_result = false;
    //     if ($is_candle_type['success_message'] == 'YES' || $is_candle_type['success_message'] == 'OFF') {
    //         $is_last_candle_type_result = true;
    //     }
    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     $rule_on_off = 'buy_rejection_candle_type' . $rule_number . '_enable';
    //     $rule_name = 'rejection_candle_type' . $rule_number . '_buy';

    //     $is_rejection_candle = $this->is_rejection_candle($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_candle_rejection_status);

    //     $log_arr = (array_merge($log_arr, $is_rejection_candle['log_arr']));

    //     $is_rejection_candle_result = false;
    //     if ($is_rejection_candle['success_message'] == 'YES' || $is_rejection_candle['success_message'] == 'OFF') {
    //         $is_rejection_candle_result = true;
    //     }

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     $rule_on_off = 'buy_last_200_contracts_buy_vs_sell' . $rule_number . '_enable';
    //     $rule_name = 'last_200_contracts_buy_vs_sell' . $rule_number . '_buy';

    //     $is_last_200_contracts_buy_vs_sell = $this->is_last_200_contracts_buy_vs_sell($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_200_buy_vs_sell);

    //     $log_arr = (array_merge($log_arr, $is_last_200_contracts_buy_vs_sell['log_arr']));

    //     $is_last_200_contracts_buy_vs_sell_result = false;
    //     if ($is_last_200_contracts_buy_vs_sell['success_message'] == 'YES' || $is_last_200_contracts_buy_vs_sell['success_message'] == 'OFF') {
    //         $is_last_200_contracts_buy_vs_sell_result = true;
    //     }

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     $rule_on_off = 'buy_last_200_contracts_time' . $rule_number . '_enable';
    //     $rule_name = 'last_200_contracts_time' . $rule_number . '_buy';

    //     $is_last_200_contracts_time = $this->is_last_200_contracts_time($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_200_time_ago);

    //     $log_arr = (array_merge($log_arr, $is_last_200_contracts_time['log_arr']));

    //     $is_last_200_contracts_time_result = false;
    //     if ($is_last_200_contracts_time['success_message'] == 'YES' || $is_last_200_contracts_time['success_message'] == 'OFF') {
    //         $is_last_200_contracts_time_result = true;
    //     }

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     $rule_on_off = 'buy_last_qty_buyers_vs_seller' . $rule_number . '_enable';
    //     $rule_name = 'last_qty_buyers_vs_seller' . $rule_number . '_buy';

    //     $is_last_qty_buyers_vs_seller = $this->is_last_qty_buyers_vs_seller($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_qty_buy_vs_sell);

    //     $log_arr = (array_merge($log_arr, $is_last_qty_buyers_vs_seller['log_arr']));

    //     $is_last_qty_buyers_vs_seller_result = false;
    //     if ($is_last_qty_buyers_vs_seller['success_message'] == 'YES' || $is_last_qty_buyers_vs_seller['success_message'] == 'OFF') {
    //         $is_last_qty_buyers_vs_seller_result = true;
    //     }

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     $rule_on_off = 'buy_last_qty_time' . $rule_number . '_enable';
    //     $rule_name = 'last_qty_time' . $rule_number . '_buy';

    //     $is_last_qty_time = $this->is_last_qty_time($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_qty_time_ago);

    //     $log_arr = (array_merge($log_arr, $is_last_qty_time['log_arr']));

    //     $is_last_qty_time_result = false;
    //     if ($is_last_qty_time['success_message'] == 'YES' || $is_last_qty_time['success_message'] == 'OFF') {
    //         $is_last_qty_time_result = true;
    //     }

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
    //     $rule_on_off = 'buy_score' . $rule_number . '_enable';
    //     $rule_name = 'score' . $rule_number . '_buy';

    //     $is_score = $this->is_score($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $score);

    //     $log_arr = (array_merge($log_arr, $is_score['log_arr']));

    //     $is_score_result = false;
    //     if ($is_score['success_message'] == 'YES' || $is_score['success_message'] == 'OFF') {
    //         $is_score_result = true;
    //     }

    //     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    //     //%%%%%%%%%%%%%%%%%%%%%%%%%%%%% seller fifteen minute %%%%%%%%%%%%%%%

    //     $rule_on_off = 'buyers_vs_sellers_buy' . $rule_number . '_enable';
    //     $rule_name = 'buyers_vs_sellers' . $rule_number . '_buy';

    //     $sellers_fifteen_response = $this->is_buyers_vs_sellers_buy_fifteen($rule_on_off, $rule_name, $coin_meta_arr, $global_setting_arr);

    //     $log_arr = (array_merge($log_arr, $sellers_fifteen_response['log_arr']));
    //     $sellers_fifteen_result = false;
    //     if ($sellers_fifteen_response['success_message'] == 'YES' || $sellers_fifteen_response['success_message'] == 'OFF') {
    //         $sellers_fifteen_result = true;
    //     }
    //     //%%%%%%%%%%%%%%%%%%%%%%%%%%%%  End of buyeer fifteen minute %%%%%%%%%

    //     /************ End Buy buyer_vs_seller_rule_1_buy **************/

    //     $meet_all_condition_for_rule_1 = false;

    //     if ($status_rule_1_result && $barrier_volume_rule_1_result & $down_pressure_rule_1_result && $big_seller_rule_1_rule_1_result & $black_wall_rule_1_result & $yellow_wall_rule_1_result & $seven_level_rule_1_result && $buyer_vs_seller_rule_result & $is_last_candle_type_result && $is_rejection_candle_result && $is_last_200_contracts_buy_vs_sell_result && $is_last_200_contracts_time_result && $is_last_qty_buyers_vs_seller_result && $is_last_qty_time_result && $is_score_result && $candle_procedding_status_result && $sellers_fifteen_result) {
    //         $meet_all_condition_for_rule_1 = true;
    //     }

    //     var_dump($meet_all_condition_for_rule_1);

    //     $response_message = 'NO';
    //     if ($meet_all_condition_for_rule_1) {
    //         $response_message = 'YES';
    //     }

    //     $data['Rule_' . $rule_number] = $log_arr;
    //     $data['response_message'] = $response_message;

    //     return $data;
    // } //End of Function

    ///////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////                           /////////////////
    ////////////////////                           ////////////////////////////////
    ////////////////////  Buy Part                 /////////////////////////////////
    ////////////////////                           ////////////////////////////////
    ////////////////////                           /////////////////
    ////////////////////                           /////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    // public function is_buyers_vs_sellers_buy_fifteen($rule_on_off, $rule_name, $coin_meta_arr, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';

    //     $sellers_buyers_per_fifteen = $coin_meta_arr['sellers_buyers_per_fifteen'];
    //     $recommended_sellers_fifteen = $global_setting_arr[$rule_name];

    //     if ($_enable_disable_rule == 'yes') {
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($sellers_buyers_per_fifteen >= $recommended_sellers_fifteen) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['buyers_vs_sellers_fifteen_rule_status'] = $_status_rule_color;
    //     $log_arr['actual_buyers_vs_sellers_fifteen'] = $sellers_buyers_per_fifteen;
    //     $log_arr['recommended_buyers_vs_sellers_fifteen'] = $recommended_sellers_fifteen;

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;

    //     return $data;
    // } //End of is_buyers_vs_sellers_buy_fifteen

    // public function is_buyers_fifteen($rule_on_off, $rule_name, $coin_meta_arr, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';

    //     $buyers_fifteen = $coin_meta_arr['buyers_fifteen'];
    //     $recommended_buyer_fifteen = $global_setting_arr[$rule_name];

    //     if ($_enable_disable_rule == 'yes') {
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($buyers_fifteen >= $recommended_buyer_fifteen) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['buyers_fifteen_rule_status'] = $_status_rule_color;
    //     $log_arr['actual_buyers_fifteen'] = $buyers_fifteen;
    //     $log_arr['recommended_buyer_fifteen'] = $recommended_buyer_fifteen;

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_buyers_fifteen

    // public function is_score($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $score)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_score = $global_setting_arr[$rule_name];
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($score >= $recommended_score) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_score' . $rule . '_buy_enable'] = $_status_rule_color;
    //     $log_arr['score'] = $score;
    //     $log_arr['recommended_score'] = $recommended_score;

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_score

    // public function is_last_5_minute_candle_buys_vs_seller($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $sellers_buyers_per)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_last_5_minute_candle_buys_vs_seller = $global_setting_arr[$rule_name];
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($sellers_buyers_per >= $recommended_last_5_minute_candle_buys_vs_seller) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_last_5_minute_candle_buys_vs_seller' . $rule . '_buy_enable'] = $_status_rule_color;
    //     $log_arr['last_5_minute_candle_buys_vs_seller'] = $sellers_buyers_per;
    //     $log_arr['recommended_last_5_minute_candle_buys_vs_seller'] = $recommended_last_5_minute_candle_buys_vs_seller;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_last_5_minute_candle_buys_vs_seller

    // public function is_last_qty_time($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_qty_time_ago)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_last_qty_time = $global_setting_arr[$rule_name];
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($last_qty_time_ago <= $recommended_last_qty_time) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_last_qty_time' . $rule . '_buy_enable'] = $_status_rule_color;
    //     $log_arr['is_last_qty_time'] = $last_qty_time_ago;
    //     $log_arr['recommended_last_qty_time'] = $recommended_last_qty_time;

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_last_qty_time

    // public function is_last_qty_buyers_vs_seller($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_qty_buy_vs_sell)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_is_last_qty_buyers_vs_seller = $global_setting_arr[$rule_name];
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($last_qty_buy_vs_sell >= $recommended_is_last_qty_buyers_vs_seller) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_last_qty_buyers_vs_seller' . $rule . '_buy_enable'] = $_status_rule_color;
    //     $log_arr['last_qty_buyers_vs_seller'] = $last_qty_buy_vs_sell;
    //     $log_arr['recommended_is_last_qty_buyers_vs_seller'] = $recommended_is_last_qty_buyers_vs_seller;

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_last_qty_buyers_vs_seller

    // public function is_last_200_contracts_time($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_200_time_ago)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_is_last_200_contracts_time = $global_setting_arr[$rule_name];
    //         $last_200_time_ago_1 = (float) $last_200_time_ago;
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($last_200_time_ago_1 <= $recommended_is_last_200_contracts_time) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_last_200_contracts_time' . $rule . '_buy_enable'] = $_status_rule_color;
    //     $log_arr['last_200_contracts_time'] = $last_200_time_ago;
    //     $log_arr['recommended_last_200_contracts_time'] = $recommended_is_last_200_contracts_time;

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_last_200_contracts_time

    // public function is_last_200_contracts_buy_vs_sell($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_200_buy_vs_sell)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_last_200_contracts_buy_vs_sell = $global_setting_arr[$rule_name];
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($last_200_buy_vs_sell >= $recommended_last_200_contracts_buy_vs_sell) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_last_200_contracts_buy_vs_sell_' . $rule . '_buy_enable'] = $_status_rule_color;
    //     $log_arr['last_200_buy_vs_sell'] = $last_200_buy_vs_sell;
    //     $log_arr['recommended_last_200_contracts_buy_vs_sell'] = $recommended_last_200_contracts_buy_vs_sell;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_last_200_contracts_buy_vs_sell

    // public function is_rejection_candle($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_candle_rejection_status)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_last_rejection_candle = $global_setting_arr[$rule_name];
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($last_candle_rejection_status == $recommended_last_rejection_candle) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_last_rejection_candle_' . $rule . '_buy_enable'] = $_status_rule_color;
    //     $log_arr['last_rejection_candle_type'] = $last_candle_rejection_status;
    //     $log_arr['recommended_rejection_candle_type'] = $recommended_last_rejection_candle;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_rejection_candle

    // public function is_candle_type($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_candle_type)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_candle_type = $global_setting_arr[$rule_name];
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($last_candle_type == $recommended_candle_type) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['Candle_type_' . $rule . '_buy_enable'] = $_status_rule_color;
    //     $log_arr['last_candle_type'] = $last_candle_type;
    //     $log_arr['recommended_candle_type'] = $recommended_candle_type;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_candle_type

    // public function buyer_vs_seller($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $current_buyer_vs_seller = $this->mod_barrier_trigger->get_buyer_vs_seller_rule($coin_symbol);

    //         $recommended_buyer_vs_seller = $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($current_buyer_vs_seller >= $recommended_buyer_vs_seller) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['buyer_vs_seller_rule_' . $rule . '_buy_enable'] = $_status_rule_color;
    //     $log_arr['current_buyer_vs_seller'] = $current_buyer_vs_seller;
    //     $log_arr['recommended_buyer_vs_seller'] = $recommended_buyer_vs_seller;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of buyer_vs_seller

    // public function buy_rule_seven_level($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $seven_levele_pressure_value = $seven_levele_pressure = $this->mod_barrier_trigger->seven_level_pressure_sell($coin_symbol);
    //         $recommended_seven_levele_pressure_value = $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($seven_levele_pressure >= $recommended_seven_levele_pressure_value) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_seven_levele_pressure_buy_' . $rule . '_yes'] = $_status_rule_color;
    //     $log_arr['seven_levele_pressure_value'] = $seven_levele_pressure_value;
    //     $log_arr['recommended_seven_levele_pressure_value'] = $recommended_seven_levele_pressure_value;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of buy_rule_seven_level

    // public function buy_rule_yellow_wall($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $closest_yelllow_bottom_wall_value = $this->mod_barrier_trigger->get_yellow_closet_wall($coin_symbol);
    //         $recommended_closest_yellow_wall = $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($closest_yelllow_bottom_wall_value >= $recommended_closest_yellow_wall) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }
    //     $log_arr['is_big_yellow_closest_wall_buy_' . $rule . '_yes'] = $_status_rule_color;
    //     $log_arr['closest_yellow_bottom_wall_value'] = $closest_yelllow_bottom_wall_value;
    //     $log_arr['recommended_closest_yellow_bottom_wall_value'] = $recommended_closest_yellow_wall;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of buy_rule_black_wall

    // public function buy_rule_black_wall($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $closest_black_bottom_wall_value = $this->mod_barrier_trigger->get_black_closet_wall($coin_symbol);
    //         $recommended_closest_black_wall = $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($closest_black_bottom_wall_value >= $recommended_closest_black_wall) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_big_black_closest_wall_buy_' . $rule . '_yes'] = $_status_rule_color;
    //     $log_arr['closest_black_bottom_wall_value'] = $closest_black_bottom_wall_value;
    //     $log_arr['recommended_closest_black_wall'] = $recommended_closest_black_wall;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of buy_rule_black_wall

    // public function buy_rule_big_seller($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $ask_percent = $this->mod_barrier_trigger->buy_contract_percentage($coin_symbol);
    //         $recommended_percentage = $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($ask_percent >= $recommended_percentage) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_big_buyers_' . $rule] = $_status_rule_color;
    //     $log_arr['big_buyers_percentage'] = $ask_percent;
    //     $log_arr['recommended_percentage'] = $recommended_percentage;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of buy_rule_big_seller

    // public function buy_candle_lalst_procedding_status($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $current_procedding_status = $this->mod_barrier_trigger->last_procedding_candle_status($coin_symbol);

    //         $recommended_procedding_status = $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($current_procedding_status == $recommended_procedding_status) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_candle_procedding_status_' . $rule . '_yes'] = $_status_rule_color;
    //     if ($_status_rule != 'OFF') {
    //         $log_arr['current_procedding_status'] = $current_procedding_status;
    //         $log_arr['recommended_procedding_status'] = $recommended_procedding_status;
    //     }
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of buy_candle_lalst_procedding_status

    // public function buy_rule_down_pressure($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $current_down_pressure = $this->mod_barrier_trigger->pressure_calculate_from_coin_meta($coin_symbol);
    //         $recommended_pressure = $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($current_down_pressure >= $recommended_pressure) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_down_pressure_buy_' . $rule . '_yes'] = $_status_rule_color;
    //     $log_arr['current_down_pressure'] = $current_down_pressure;
    //     $log_arr['recommended_down_pressure'] = $recommended_pressure;

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of buy_rule_down_pressure

    // public function buy_rule_barrier_volume($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $rule_number)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';

    //     $current_market_price = $this->mod_dashboard->get_market_value($coin_symbol);
    //     $current_market_price = (float) $current_market_price;
    //     $m_price = (float) $current_market_price;

    //     if ($_enable_disable_rule == 'yes') {
    //         $range_percentage = $global_setting_arr['buy_range_percet'];

    //         /////////////////Barrier Type ///////////////////////////

    //         $rule_on_off = 'buy_trigger_type_rule_' . $rule_number . '_enable';
    //         $rule_name = 'buy_trigger_type_rule_' . $rule_number;

    //         $trigger_type = $this->buy_rule_trigger($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //         $trigger_type_log_arr = $trigger_type['log_arr'];

    //         $log_arr['is_trigger_status_buy_Rule_1_yes'] = $trigger_type_log_arr['is_trigger_status_buy_Rule_1_yes'];
    //         $log_arr['Recommended_trigger_status'] = $trigger_type_log_arr['Recommended_trigger_status'];
    //         $log_arr['last_barrier_value'] = $trigger_type_log_arr['last_barrier_value'];

    //         $last_barrrier_value = $trigger_type_log_arr['last_barrier_value'];

    //         // %%%%%%%%%%%%%%%%%%% Barrier Range %%%%%%%%%%%%%%%%%%
    //         $barrier_value_range_upside = $last_barrrier_value + ($last_barrrier_value / 100) * $range_percentage;
    //         $barrier_value_range_down_side = $last_barrrier_value - ($last_barrrier_value / 100) * $range_percentage;

    //         $meet_condition_for_buy = false;
    //         if ((num($current_market_price) >= num($barrier_value_range_down_side)) && (num($current_market_price) <= num($barrier_value_range_upside))) {
    //             $meet_condition_for_buy = true;
    //         }

    //         if ($meet_condition_for_buy) {
    //             $coin_offset_value = $this->mod_coins->get_coin_offset_value($coin_symbol);
    //             $coin_unit_value = $this->mod_coins->get_coin_unit_value($coin_symbol);

    //             $total_bid_quantity = 0;
    //             for ($i = 0; $i < $coin_offset_value; $i++) {
    //                 $new_last_barrrier_value = '';
    //                 $new_last_barrrier_value = $last_barrrier_value - ($coin_unit_value * $i);
    //                 $bid = $this->mod_barrier_trigger->get_market_volume($new_last_barrrier_value, $coin_symbol, $type = 'bid');

    //                 $total_bid_quantity += $bid;
    //             } //End of Coin off Set

    //             $bid_quantity = $total_bid_quantity;

    //             $bid_volume = $global_setting_arr['buy_volume_rule_' . $rule_number];

    //             if ($bid_quantity >= $bid_volume) {
    //                 $_status_rule = 'YES';
    //             } else {
    //                 $_status_rule = '<span style="color:red">NO</span>';
    //             }

    //             $log_arr['total_bid_quantity_for_barrier_range'] = $total_bid_quantity;

    //             $log_arr['Recommended_bid_quantity'] = $bid_volume;
    //         } else {
    //             $_status_rule = '<span style="color:red">NO</span>';
    //         } //End of Meet barrier

    //         $log_arr['current_market_price'] = num($current_market_price);
    //         $log_arr['last_barrrier_value'] = num($last_barrrier_value);
    //         $log_arr['barrier_value_range'] = num($barrier_value_range);
    //         $log_arr['bid_quantity'] = $bid_quantity;

    //         if ($_status_rule == 'YES') {
    //             $_status_rule_color = '<span style="color:green">YES</span>';
    //             $log_arr['is_bid_quantity_buy_' . $rule . '_yes'] = $_status_rule_color;
    //         } else {
    //             $log_arr['is_bid_quantity_buy_' . $rule . '_yes'] = $_status_rule;
    //         }
    //     } else {
    //         //End of enable disable rule
    //         $log_arr['is_bid_quantity_buy_' . $rule . '_yes'] = $_status_rule;
    //         /*#################################*/
    //         //IF barrier_not_Meet Then use virturl barrier
    //         $virtual_barrier_response = $this->is_virtual_barrier($coin_symbol, $global_setting_arr, $rule_number, $m_price);

    //         $log_arr = (array_merge($log_arr, $virtual_barrier_response['log_arr']));

    //         $is_virtual_barrier = $virtual_barrier_response['success_message'];
    //         if ($is_virtual_barrier == 'YES') {
    //             $_status_rule = $is_virtual_barrier;
    //             $_status_rule_color = '<span style="color:green">YES</span>';
    //         } else {
    //             $_status_rule_color = $_status_rule;
    //             $_status_rule = $is_virtual_barrier;
    //         }
    //         /*#################################*/
    //     } //End of rule is disable

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of barrier_volume_buy_rule

    // public function buy_rule_status($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $current_swing_point = $this->mod_barrier_trigger->get_current_swing_point($coin_symbol);
    //         $swing_status = (array) $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if (in_array($current_swing_point, $swing_status)) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_swing_status_buy_' . $rule . '_yes'] = $_status_rule_color;
    //     $log_arr['current_swing_point'] = $current_swing_point;
    //     $log_arr['Recommended_swing_status'] = implode('--', $swing_status);
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of buy_status

    // public function buy_rule_trigger($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];

    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $c_price = $this->mod_dashboard->get_market_value($coin_symbol);
    //         $c_price = (float) $c_price;

    //         $current_barrier_status = $this->mod_barrier_trigger->get_current_barrier_status($coin_symbol, $c_price);

    //         $barrier_status = (array) $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         $last_barrier_value = '';
    //         if (count($current_barrier_status) > 0) {
    //             foreach ($current_barrier_status as $row) {
    //                 if (in_array($row['barrier_status'], $barrier_status)) {
    //                     $_status_rule = 'YES';
    //                     $last_barrier_value = $row['barier_value'];
    //                     break;
    //                 }
    //                 break; // %%%%%%%%%% Check only First Barrier %%%%%%%%%
    //             } //End Of
    //         } //End Of if Barrier is Greater
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_trigger_status_buy_' . $rule . '_yes'] = $_status_rule_color;
    //     $log_arr['current_trigger_status'] = $current_barrier_status;
    //     $log_arr['Recommended_trigger_status'] = implode('--', $barrier_status);
    //     $log_arr['last_barrier_value'] = num($last_barrier_value);

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;

    //     return $data;
    // } //End of buy_rule_trigger

    // public function is_virtual_barrier($coin_symbol, $global_setting_arr, $rule_number, $current_market_price)
    // {
    //     $rule_on_off = 'buy_virtual_barrier_rule_' . $rule_number . '_enable';
    //     $rule_name = 'buy_virtural_rule_' . $rule_number;

    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_quantity = (float) $global_setting_arr[$rule_name];
    //         $_status_rule = '<span style="color:red">NO</span>';

    //         /* %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */

    //         $coin_offset_value = $this->mod_coins->get_coin_offset_value($coin_symbol);
    //         $coin_unit_value = $this->mod_coins->get_coin_unit_value($coin_symbol);
    //         $total_bid_quantity = 0;
    //         for ($i = 0; $i < $coin_offset_value; $i++) {
    //             $new_last_barrrier_value = $current_market_price - ($coin_unit_value * $i);

    //             $bid = $this->mod_barrier_trigger->get_market_volume($new_last_barrrier_value, $coin_symbol, $type = 'bid');
    //             $total_bid_quantity += $bid;
    //         } //End of Coin off Set

    //         /* %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */

    //         if ($total_bid_quantity >= $recommended_quantity) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }
    //     $log_arr['is_order_book_barrier_enable_' . $rule_number] = $_status_rule_color;

    //     if ($_status_rule != 'OFF') {
    //         $log_arr['calculated_quantity'] = $total_bid_quantity;
    //         $log_arr['recommended_quantity'] = $recommended_quantity;
    //     }

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_score

    ///////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////                           /////////////////
    ////////////////////                           ////////////////////////
    ////////////////////  Sell Part                 //////////////////////
    ////////////////////                           //////////////////////
    ////////////////////                           /////////////////
    ////////////////////                           /////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    // public function is_sellers_fifteen_for_sell($rule_on_off, $rule_name, $coin_meta_arr, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';

    //     $sellers_fifteen = $coin_meta_arr['sellers_fifteen'];
    //     $recommended_sellers_fifteen = $global_setting_arr[$rule_name];

    //     if ($_enable_disable_rule == 'yes') {
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($sellers_fifteen >= $recommended_sellers_fifteen) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['sellers_fifteen_rule_status'] = $_status_rule_color;
    //     $log_arr['actual_sellers_fifteen'] = $sellers_fifteen;
    //     $log_arr['recommended_sellers_fifteen'] = $recommended_sellers_fifteen;

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_sellers_fifteen_for_sell

    // public function is_buyers_vs_sellers_fifteen_for_sell($rule_on_off, $rule_name, $coin_meta_arr, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';

    //     $buyers_vs_sellers_fifteen = $coin_meta_arr['sellers_buyers_per_fifteen'];
    //     $recommended_buyer_fifteen = $global_setting_arr[$rule_name];

    //     if ($_enable_disable_rule == 'yes') {
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($buyers_vs_sellers_fifteen <= $recommended_buyer_fifteen) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['buyers_vs_sellers_fifteen_rule_status'] = $_status_rule_color;
    //     $log_arr['actual_buyers_vs_sellers_fifteen_value'] = $buyers_vs_sellers_fifteen;
    //     $log_arr['recommended_buyers_vs_sellers_fifteen'] = $recommended_buyer_fifteen;

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_buyers_vs_sellers_fifteen_for_sell

    // public function sell_candle_lalst_procedding_status($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';

    //     //check if current candel procedding status is LL
    //     $candel_status = $this->mod_barrier_trigger->get_precedding_candel_swing_status($coin_symbol);

    //     if (!$candel_status) {
    //         $log_arr['candel_status'] = 'Candel processing status is not LL We return true';
    //         $data['log_arr'] = $log_arr;
    //         $data['success_message'] = $_status_rule;
    //         return $data;
    //     }

    //     if ($_enable_disable_rule == 'yes') {
    //         $current_procedding_status = $this->mod_barrier_trigger->last_procedding_candle_status($coin_symbol);

    //         $recommended_procedding_status = $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($current_procedding_status == $recommended_procedding_status) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_candle_procedding_status_' . $rule . '_yes'] = $_status_rule_color;
    //     if ($_status_rule != 'OFF') {
    //         $log_arr['current_procedding_status'] = $current_procedding_status;
    //         $log_arr['recommended_procedding_status'] = $recommended_procedding_status;
    //     }
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of sell_candle_lalst_procedding_status

    // public function is_virtual_barrier_sell($coin_symbol, $global_setting_arr, $rule_number, $current_market_price)
    // {
    //     $rule_on_off = 'sell_virtual_barrier_rule_' . $rule_number . '_enable';
    //     $rule_name = 'sell_virtural_rule_' . $rule_number;

    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_quantity = $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';

    //         /* %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */

    //         $coin_offset_value = $this->mod_coins->get_coin_offset_value($coin_symbol);
    //         $coin_unit_value = $this->mod_coins->get_coin_unit_value($coin_symbol);
    //         $total_bid_quantity = 0;
    //         for ($i = 0; $i < $coin_offset_value; $i++) {
    //             $new_last_barrrier_value = $current_market_price + ($coin_unit_value * $i);
    //             $bid = $this->mod_barrier_trigger->get_market_volume($new_last_barrrier_value, $coin_symbol, $type = 'ask');
    //             $total_bid_quantity += $bid;
    //         } //End of Coin off Set

    //         /* %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */

    //         if ($total_bid_quantity >= $recommended_quantity) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }
    //     $log_arr['is_order_book_barrier_enable_' . $rule_number] = $_status_rule_color;

    //     if ($_status_rule != 'OFF') {
    //         $log_arr['calculated_quantity'] = $total_bid_quantity;
    //         $log_arr['recommended_quantity'] = $recommended_quantity;
    //     }

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_score

    // public function seller_vs_buyer($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $current_seller_vs_buyer = $this->mod_barrier_trigger->get_buyer_vs_seller_rule($coin_symbol);

    //         $recommended_seller_vs_buyer = $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($current_seller_vs_buyer <= $recommended_seller_vs_buyer) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['seller_vs_buyer_rule_' . $rule . '_buy_enable'] = $_status_rule_color;
    //     $log_arr['current_seller_vs_buyer'] = $current_seller_vs_buyer;
    //     $log_arr['recommended_seller_vs_buyer'] = $recommended_seller_vs_buyer;

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of seller_vs_buyer
    // public function sell_rule_trigger($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];

    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $current_market_price = $this->mod_dashboard->get_market_value($coin_symbol);
    //         $current_market_price = (float) $current_market_price;

    //         $current_barrier_status = $this->mod_barrier_trigger->get_current_barrier_status_up($coin_symbol, $current_market_price);

    //         $barrier_status = (array) $global_setting_arr[$rule_name];

    //         // echo '<pre>';
    //         // print_r($current_barrier_status);
    //         // print_r($barrier_status);

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         $last_barrier_value = '';
    //         if (count($current_barrier_status) > 0) {
    //             foreach ($current_barrier_status as $row) {
    //                 if (in_array($row['barrier_status'], $barrier_status)) {
    //                     $_status_rule = 'YES';
    //                     $last_barrier_value = $row['barier_value'];
    //                     $log_arr['last_barrier_value'] = num($last_barrier_value);
    //                     break;
    //                 }
    //             } //End Of
    //         } //End Of if Barrier is Greater
    //     } //End of buy_status_rule_1_enable

    //     //echo num($last_barrier_value);
    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_trigger_status_sell_' . $rule . '_yes'] = $_status_rule_color;
    //     $log_arr['current_trigger_status'] = $current_barrier_status;
    //     $log_arr['Recommended_trigger_status'] = implode('--', $barrier_status);

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of buy_rule_trigger

    // public function sell_rule_seven_level($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $seven_levele_pressure_value = $seven_levele_pressure = $this->mod_barrier_trigger->seven_level_pressure_sell($coin_symbol);
    //         $recommended_seven_levele_pressure_value = $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($seven_levele_pressure <= $recommended_seven_levele_pressure_value) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_seven_levele_pressure_sell_' . $rule] = $_status_rule_color;
    //     $log_arr['seven_levele_pressure_value'] = $seven_levele_pressure_value;
    //     $log_arr['recommended_seven_levele_pressure_value'] = $recommended_seven_levele_pressure_value;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of sell_rule_seven_level

    // public function sell_rule_yellow_wall($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $closest_yelllow_bottom_wall_value = $this->mod_barrier_trigger->get_yellow_closet_wall($coin_symbol);
    //         $recommended_closest_yellow_wall = $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($closest_yelllow_bottom_wall_value <= $recommended_closest_yellow_wall) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_big_yellow_closest_wall_sell_' . $rule] = $_status_rule_color;
    //     $log_arr['closest_yellow_bottom_wall_value'] = $closest_yelllow_bottom_wall_value;
    //     $log_arr['recommended_closest_yellow_wall_value'] = $recommended_closest_yellow_wall;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of sell_rule_yellow_wall

    // public function sell_rule_black_wall($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $closest_black_bottom_wall_value = $this->mod_barrier_trigger->get_black_closet_wall($coin_symbol);
    //         $recommended_closest_black_wall = $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($closest_black_bottom_wall_value <= $recommended_closest_black_wall) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_big_black_closest_wall_sell_' . $rule] = $_status_rule_color;
    //     $log_arr['closest_black_wall_value'] = $closest_black_bottom_wall_value;
    //     $log_arr['recommended_closest_black_wall'] = $recommended_closest_black_wall;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of sell_rule_black_wall
    // public function sell_rule_big_seller($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $ask_percent = $this->mod_barrier_trigger->sell_contract_percentage($coin_symbol);
    //         $recommended_percentage = $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($ask_percent >= $recommended_percentage) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_big_seller_sell_' . $rule] = $_status_rule_color;
    //     $log_arr['big_seller_percentage'] = $ask_percent;
    //     $log_arr['recommended_percentage'] = $recommended_percentage;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of sell_rule_big_seller

    // public function sell_rule_down_pressure($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $current_down_pressure = $this->mod_barrier_trigger->pressure_calculate_from_coin_meta($coin_symbol);
    //         $recommended_pressure = $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($current_down_pressure <= $recommended_pressure) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     $log_arr['is_down_pressure_sell_' . $rule] = $_status_rule;
    //     $log_arr['current_down_pressure'] = $current_down_pressure;
    //     $log_arr['recommended_down_pressure'] = $recommended_pressure;

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of sell_rule_down_pressure

    // public function sell_rule_barrier_volume($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $rule_number)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     $current_market_price = $this->mod_dashboard->get_market_value($coin_symbol);
    //     $current_market_price = (float) $current_market_price;
    //     if ($_enable_disable_rule == 'yes') {
    //         $range_percentage = $global_setting_arr['range_previous_barrier_values'];

    //         ///////////////////////////
    //         //////////////////////////////
    //         ///////////////////////

    //         /////////////////Barrier Type ///////////////////////////

    //         $rule_on_off = 'sell_trigger_type_rule_' . $rule_number . '_enable';
    //         $rule_name = 'sell_trigger_type_rule_' . $rule_number;

    //         $trigger_type = $this->sell_rule_trigger($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr);

    //         $trigger_type_log_arr = $trigger_type['log_arr'];

    //         $log_arr['Recommended_trigger_status'] = $trigger_type_log_arr['Recommended_trigger_status'];

    //         $log_arr['last_barrier_value'] = $trigger_type_log_arr['last_barrier_value'];

    //         $last_barrrier_value = $trigger_type_log_arr['last_barrier_value'];

    //         //////////////////////////
    //         //////////////////////
    //         ///////////////

    //         $barrier_value_range_upside = $last_barrrier_value + ($last_barrrier_value / 100) * $range_percentage;

    //         $barrier_value_range_down_side = $last_barrrier_value - ($last_barrrier_value / 100) * $range_percentage;

    //         $log_arr['barrier_value_range'] = num($barrier_value_range);

    //         $log_arr['current_market_price'] = num($current_market_price);

    //         $meet_condition_for_sell = false;

    //         if ((num($current_market_price) <= num($barrier_value_range_upside)) && (num($current_market_price) >= num($barrier_value_range_down_side))) {
    //             $meet_condition_for_sell = true;
    //         }

    //         echo '$current_market_price' . $current_market_price;
    //         echo '<br>';
    //         var_dump($meet_condition_for_sell);
    //         //exit();

    //         if ($meet_condition_for_sell) {
    //             $coin_offset_value = $this->mod_coins->get_coin_offset_value($coin_symbol);
    //             $coin_unit_value = $this->mod_coins->get_coin_unit_value($coin_symbol);

    //             $total_bid_quantity = 0;
    //             for ($i = 0; $i < $coin_offset_value; $i++) {
    //                 $new_last_barrrier_value = '';
    //                 $new_last_barrrier_value = $last_barrrier_value + ($coin_unit_value * $i);
    //                 $bid = $this->mod_barrier_trigger->get_market_volume($new_last_barrrier_value, $coin_symbol, $type = 'ask');
    //                 $total_bid_quantity += $bid;
    //             } //End of Coin off Set

    //             $bid_quantity = $total_bid_quantity;
    //             $log_arr['ask_quantity'] = $bid_quantity;

    //             $rl_ = 'sell_volume_rule_' . $rule_number;

    //             $bid_volume = $global_setting_arr[$rl_];

    //             if ($bid_quantity >= $bid_volume) {
    //                 $_status_rule = 'YES';
    //             } else {
    //                 $_status_rule = '<span style="color:red">NO</span>';
    //             }

    //             $log_arr['Recommended_ask_quantity'] = $bid_volume;
    //         } else {
    //             $_status_rule = '<span style="color:red">NO</span>';
    //         } //End of Meet barrier
    //     } //End of enable disable rule

    //     $log_arr['is_ask_quantity_sell_' . $rule] = $_status_rule;

    //     if ($_status_rule == 'OFF') {
    //         /*#################################*/
    //         //IF barrier_not_Meet Then use virturl barrier
    //         $virtual_barrier_response = $this->is_virtual_barrier_sell($coin_symbol, $global_setting_arr, $rule_number, $current_market_price);
    //         $log_arr = (array_merge($log_arr, $virtual_barrier_response['log_arr']));

    //         $is_virtual_barrier = $virtual_barrier_response['success_message'];

    //         $_status_rule = $is_virtual_barrier;
    //     } //End Of Status Yes

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of sell_rule_barrier_volume

    // public function sell_rule_status($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $current_swing_point = $this->mod_barrier_trigger->get_current_swing_point($coin_symbol);
    //         $swing_status = (array) $global_setting_arr[$rule_name];

    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if (in_array($current_swing_point, $swing_status)) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     $log_arr['is_swing_status_sell_' . $rule] = $_status_rule;
    //     $log_arr['current_swing_point'] = $current_swing_point;
    //     $log_arr['Recommended_swing_status'] = implode('--', $swing_status);
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of buy_status

    // public function is_candle_type_sell($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_candle_type)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_candle_type = $global_setting_arr[$rule_name];
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($last_candle_type == $recommended_candle_type) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['Candle_type_' . $rule . '_sell_enable'] = $_status_rule_color;
    //     $log_arr['last_candle_type'] = $last_candle_type;
    //     $log_arr['recommended_candle_type'] = $recommended_candle_type;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }
    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_candle_type

    // public function is_rejection_candle_sell($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_candle_rejection_status)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_last_rejection_candle = $global_setting_arr[$rule_name];
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($last_candle_rejection_status == $recommended_last_rejection_candle) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_last_rejection_candle_' . $rule . '_sell_enable'] = $_status_rule_color;
    //     $log_arr['last_rejection_candle_type'] = $last_candle_rejection_status;
    //     $log_arr['recommended_rejection_candle_type'] = $recommended_last_rejection_candle;

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_rejection_candle

    // public function is_last_200_contracts_buy_vs_sell_rule($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_200_buy_vs_sell)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_last_200_contracts_buy_vs_sell = $global_setting_arr[$rule_name];
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($last_200_buy_vs_sell <= $recommended_last_200_contracts_buy_vs_sell) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_last_200_contracts_buy_vs_sell_' . $rule . '_sell_enable'] = $_status_rule_color;
    //     $log_arr['last_200_buy_vs_sell'] = $last_200_buy_vs_sell;
    //     $log_arr['recommended_last_200_contracts_buy_vs_sell'] = $recommended_last_200_contracts_buy_vs_sell;

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_last_200_contracts_buy_vs_sell

    // public function is_last_200_contracts_time_sell($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_200_time_ago)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_is_last_200_contracts_time = $global_setting_arr[$rule_name];
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         $last_200_time_ago_1 = (float) $last_200_time_ago;
    //         if ($last_200_time_ago_1 <= $recommended_is_last_200_contracts_time) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_last_200_contracts_time' . $rule . '_sell_enable'] = $_status_rule_color;
    //     $log_arr['last_200_contracts_time'] = $last_200_time_ago;
    //     $log_arr['recommended_last_200_contracts_time'] = $recommended_is_last_200_contracts_time;

    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_last_200_contracts_time

    // public function is_last_qty_buyers_vs_seller_sell($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_qty_buy_vs_sell)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_is_last_qty_buyers_vs_seller = $global_setting_arr[$rule_name];
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($last_qty_buy_vs_sell <= $recommended_is_last_qty_buyers_vs_seller) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_last_qty_buyers_vs_seller' . $rule . '_sell_enable'] = $_status_rule_color;
    //     $log_arr['last_qty_buyers_vs_seller'] = $last_qty_buy_vs_sell;
    //     $log_arr['recommended_is_last_qty_buyers_vs_seller'] = $recommended_is_last_qty_buyers_vs_seller;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_last_qty_buyers_vs_seller

    // public function is_last_qty_time_sell($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $last_qty_time_ago)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_last_qty_time = $global_setting_arr[$rule_name];
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         $last_qty_time_ago_1 = (float) $last_qty_time_ago;
    //         if ($last_qty_time_ago_1 <= $recommended_last_qty_time) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_last_qty_time' . $rule . '_sell_enable'] = $_status_rule_color;
    //     $log_arr['is_last_qty_time'] = $last_qty_time_ago;
    //     $log_arr['recommended_last_qty_time'] = $recommended_last_qty_time;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_last_qty_time

    // public function is_score_sell($coin_symbol, $rule, $rule_name, $rule_on_off, $global_setting_arr, $score)
    // {
    //     $_enable_disable_rule = $global_setting_arr[$rule_on_off];
    //     $_status_rule = 'OFF';
    //     if ($_enable_disable_rule == 'yes') {
    //         $recommended_score = $global_setting_arr[$rule_name];
    //         $_status_rule = '<span style="color:red">NO</span>';
    //         if ($score <= $recommended_score) {
    //             $_status_rule = 'YES';
    //         }
    //     } //End of buy_status_rule_1_enable

    //     if ($_status_rule == 'YES') {
    //         $_status_rule_color = '<span style="color:green">YES</span>';
    //     } else {
    //         $_status_rule_color = $_status_rule;
    //     }

    //     $log_arr['is_score' . $rule . '_sell_enable'] = $_status_rule_color;
    //     $log_arr['score'] = $score;
    //     $log_arr['recommended_score'] = $recommended_score;
    //     if (isset($_GET['setting'])) {
    //         if ($_GET['setting'] == 'onlyon') {
    //             if ($_status_rule == 'OFF') {
    //                 $log_arr = array();
    //             }
    //         }
    //     }

    //     $data['log_arr'] = $log_arr;
    //     $data['success_message'] = $_status_rule;
    //     return $data;
    // } //End of is_score

    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////                           /////////////////
    ////////////////////                           ////////////////////////////////
    ////////////////////  Sell Order by stop loss  /////////////////////////////////
    ////////////////////                           ////////////////////////////////
    ////////////////////                           /////////////////
    ////////////////////                           /////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    // public function sell_orders_by_stop_loss($date = '', $coin_symbol)
    // {
    //     $function_name = 'sell_orders_by_stop_loss';
    //     $is_function_process_complete = is_function_process_complete($function_name);
    //     function_start($function_name);

    //     $is_script_take_more_time = is_script_take_more_time($function_name);

    //     if ($is_script_take_more_time) {
    //         track_execution_of_function_time($function_name);
    //         function_stop($function_name);
    //     }

    //     if (!$is_function_process_complete) {
    //         echo 'previous process is still running';
    //         return false;
    //     }

    //     $created_date = date('Y-m-d G:i:s');
    //     $market_price = $this->mod_dashboard->get_market_value($coin_symbol);
    //     $market_price = (float) $market_price;

    //     //Check if market price is empty
    //     $this->mod_barrier_trigger->is_market_price_empty($market_price);

    //     $buy_orders_arr = $this->mod_barrier_trigger->get_stop_loss_orders($market_price, $coin_symbol);

    //     $is_order_created = $this->mod_barrier_trigger->is_order_is_created_just_now($coin_symbol, 'sell_stop_loss');

    //     $arr_response = array();
    //     if (count($buy_orders_arr) > 0) {
    //         foreach ($buy_orders_arr as $buy_orders) {
    //             $buy_orders_id = $buy_orders['_id'];
    //             $coin_symbol = $buy_orders['symbol'];
    //             $sell_price = $buy_orders['sell_price'];
    //             $admin_id = $buy_orders['admin_id'];
    //             $purchased_price = $buy_orders['price'];
    //             $buy_purchased_price = $buy_orders['market_value'];
    //             $iniatial_trail_stop = $buy_orders['iniatial_trail_stop'];
    //             $application_mode = $buy_orders['application_mode'];
    //             $quantity = $buy_orders['quantity'];
    //             $order_type = $buy_orders['order_type'];
    //             $order_mode = $buy_orders['order_mode'];
    //             $binance_order_id = $buy_orders['binance_order_id'];
    //             $trigger_type = $buy_orders['trigger_type'];
    //             $order_id = $buy_orders['sell_order_id'];
    //             $lth_functionality = $buy_orders['lth_functionality'];
    //             $purchase_price = $buy_orders['market_value'];

    //             // -- %%%%%%%%%%% If Long Time Hold is yes --%%%%%%%%%%
    //             $is_long_time_hold = true;
    //             if ($lth_functionality == 'yes') {
    //                 if ($iniatial_trail_stop <= $purchase_price) {
    //                     $is_long_time_hold = false;
    //                     $this->make_order_long_time_hold($order_id, $buy_orders_id, $admin_id);
    //                 }
    //             } // %%%%%%%%%%%% -- End of LTH Status check -- %%%%%%%%

    //             //Is sell or status is new
    //             $is_sell_order_status_new = $this->mod_barrier_trigger->is_sell_order_status_new($order_id);

    //             if ($market_price <= $iniatial_trail_stop && $iniatial_trail_stop != '' && $is_sell_order_status_new && $is_order_created && $is_long_time_hold) {

    //                 //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //                 $upd_rules = array(
    //                     'sell_rule_number' => 0,
    //                 );
    //                 $this->mongo_db->where(array('_id' => $buy_orders_id));
    //                 $this->mongo_db->set($upd_rules);
    //                 //Update data in mongoTable
    //                 $this->mongo_db->update('buy_orders');
    //                 //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

    //                 $this->mod_barrier_trigger->save_order_time_track($coin_symbol, 'sell_stop_loss');
    //                 //////////////////////////////
    //                 //////////////////////////////
    //                 $upd_data = array(
    //                     'buy_order_binance_id' => $binance_order_id,
    //                     'market_value' => (float) $market_price,
    //                     'sell_price' => (float) $sell_price,
    //                     'modified_date' => $this->mongo_db->converToMongodttime($date),
    //                 );

    //                 $this->mongo_db->where(array('_id' => $order_id));
    //                 $this->mongo_db->set($upd_data);
    //                 $this->mongo_db->update('orders');

    //                 if ($application_mode == 'live') {
    //                     if ($this->is_order_already_not_send($buy_orders_id)) {
    //                         $trading_ip = $this->mod_barrier_trigger->get_user_trading_ip($admin_id);
    //                         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //                         $created_date = date('Y-m-d G:i:s');
    //                         $htm = '<span style="color:red;    font-size: 14px;"><b>Stop Loss</b></span>';

    //                         $log_msg = 'Order Send for Sell by ' . $htm . ' ON :<b>' . num($market_price) . '</b> Price';
    //                         $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'Sell_Price', $admin_id, $created_date);

    //                         //%%%%%%%%%%%%%%% Target price %%%%%%%%%%%
    //                         $log_msg = 'Expected Stop Loss value : <b>' . num($iniatial_trail_stop) . '</b> ';
    //                         $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'Expected Stop Loss', $admin_id, $created_date);

    //                         $log_msg = 'Send Market Orde for sell by Ip: <b>' . $trading_ip . '</b> ';
    //                         $this->mod_barrier_trigger->insert_developer_log($buy_orders_id, $log_msg, 'Message', $created_date, $show_error_log = 'yes');

    //                         $trigger_type = 'barrier_trigger';
    //                         $this->mod_barrier_trigger->order_ready_for_sell_by_ip($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id, $trading_ip, $trigger_type, 'sell_stop_loss_order');
    //                     } //End of is already not send

    //                     // $res = $this->mod_dashboard->binance_sell_auto_market_order_live($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id);
    //                 } else {
    //                     $upd_data_1 = array(
    //                         'status' => 'FILLED',
    //                         'market_value' => (float) $market_price,
    //                     );
    //                     $this->mongo_db->where(array('_id' => $order_id));
    //                     $this->mongo_db->set($upd_data_1);
    //                     //Update data in mongoTable
    //                     $this->mongo_db->update('orders');
    //                     $sell_price = $iniatial_trail_stop;
    //                     $upd_data22 = array(
    //                         'is_sell_order' => 'sold',
    //                         'market_sold_price' => (float) $market_price,
    //                         'modified_date' => $this->mongo_db->converToMongodttime($date),

    //                     );

    //                     $this->mongo_db->where(array('_id' => $buy_orders_id));
    //                     $this->mongo_db->set($upd_data22);
    //                     //Update data in mongoTable
    //                     $this->mongo_db->update('buy_orders');
    //                     //////////////////////////////////////////////////////////////////////////////
    //                     ////////////////////////////// Order History Log /////////////////////////////
    //                     $message = 'Sell Order was Sold by stop_loss';
    //                     $log_msg = $message . " " . number_format($sell_price, 8);
    //                     $created_date = date('Y-m-d G:i:s');
    //                     $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'sell_created', $admin_id, $created_date);
    //                     ////////////////////////////// End Order History Log /////////////////////////
    //                     //////////////////////////////////////////////////////////////////////////////
    //                     ////////////////////// Set Notification //////////////////
    //                     $message = $message . " <b>Sold</b>";
    //                     $this->mod_box_trigger_3->add_notification($buy_orders_id, 'buy', $message, $admin_id);
    //                     //////////////////////////////////////////////////////////
    //                     //Check Market History
    //                     $commission_value = $quantity * (0.001);
    //                     $commission = $commission_value * $market_value;
    //                     $commissionAsset = 'BTC';
    //                     //Check Market History
    //                     //////////////////////////////////////////////////////////////////////////////////////////////
    //                     ////////////////////////////// Order History Log /////////////////////////////////////////////
    //                     $log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
    //                     $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'sell_commision', $admin_id, $created_date);
    //                     ////////////////////////////// End Order History Log /////////////////////////////////////////
    //                     //////////////////////////////////////////////////////////////////////////////////////////////
    //                     $response['message'] = '$log_msg  ' . $log_msg . '  $message ' . $message;
    //                 }
    //             } //End of check Of market price is less Then Initial trail stop
    //         } //End of for Each buy orders
    //     } //End of buy orders Exist
    //     //%%%%%%%%%%%% if function process complete %%%%%%%%%%%%%
    //     function_stop($function_name);
    // } //End of sell_orders_by_stop_loss

    // //%%%%%%%%%%%%%%%%%%%%%%% Hold Order for longtime %%%%%%%%%%%%

    // public function make_order_long_time_hold($order_id, $buy_orders_id, $admin_id)
    // {
    //     //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //     $upd_lth = array(
    //         'status' => 'LTH',
    //     );
    //     //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

    //     //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //     $this->mongo_db->where(array('_id' => $buy_orders_id));
    //     $this->mongo_db->set($upd_lth);
    //     //Update data in mongoTable
    //     $this->mongo_db->update('buy_orders');
    //     //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

    //     $log_msg = 'Order Goes to <span style="color:orange;font-size: 14px;"><b>Long Term Hold</b></span> By System';
    //     $created_date = date('Y-m-d G:i:s');
    //     $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'sell_created', $admin_id, $created_date);
    // } //End of make_order_long_time_hold

    // public function is_order_already_not_send($buy_orders_id)
    // {
    //     $buy_orders_id_obj = $this->mongo_db->mongoId($buy_orders_id);
    //     $this->mongo_db->where(array('buy_orders_id' => $buy_orders_id_obj));
    //     $response = $this->mongo_db->get('ready_orders_for_sell_ip_based');
    //     $result = iterator_to_array($response);
    //     $resp = true;
    //     if (!empty($result)) {
    //         $resp = false;
    //     }
    //     return $resp;
    // } //End of is_order_already_not_send

    ///////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////                           /////////////////
    ////////////////////                           ////////////////////////////////
    ////////////////////  Calculate Barriers       /////////////////////////////////
    ////////////////////                           ////////////////////////////////
    ////////////////////                           /////////////////
    ////////////////////                           /////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    // public function lowest_barrier($coin)
    // {
    //     $start_date = date('Y-m-d H:00:00');
    //     $this->mongo_db->limit('5');
    //     $this->mongo_db->where(array('coin' => $coin));
    //     $start_date1 = $this->mongo_db->converToMongodttime($start_date);
    //     $this->mongo_db->where_lt('timestampDate', $start_date1);
    //     $this->mongo_db->order_by(array('timestampDate' => -1));
    //     $data_obj = $this->mongo_db->get('market_chart');
    //     $data_row = iterator_to_array($data_obj);

    //     $low_value_arr = array();
    //     $body_value_arr = array();
    //     if (count($data_row) > 0) {
    //         foreach ($data_row as $row) {
    //             array_push($low_value_arr, num($row['low']));
    //             if ($row['open'] < $row['close']) {
    //                 array_push($body_value_arr, num($row['open']));
    //             } else {
    //                 array_push($body_value_arr, num($row['close']));
    //             }
    //         } //End of for each
    //     } //if coutn is greater then 0

    //     $lowest_value_in_low_values = min($low_value_arr);
    //     $lowest_value_in_body_values = min($body_value_arr);

    //     $is_body_less = true;

    //     if (count($low_value_arr) > 0) {
    //         foreach ($low_value_arr as $low_value) {
    //             if ($lowest_value_in_body_values < $low_value) {
    //                 $is_body_less = false;
    //                 break;
    //             }
    //         }
    //     }

    //     $type = 'no_barrier';
    //     if ($is_body_less) {
    //         $boy_low_values = array();
    //         if (count($body_value_arr) > 0) {
    //             $index = 0;
    //             foreach ($body_value_arr as $body_values) {
    //                 $low_value = $low_value_arr[$index];
    //                 if ($low_value == $body_values) {
    //                     array_push($boy_low_values, $low_value);
    //                 } elseif ($low_value < $body_values) {
    //                     array_push($boy_low_values, $low_value);
    //                 } elseif ($body_values < $low_value) {
    //                     array_push($boy_low_values, $body_values);
    //                 }
    //                 $index++;
    //             } //End of foreach
    //         } //End

    //         $type = 'no_barrier';
    //         $number_of_values = array_count_values($boy_low_values);

    //         if (count($number_of_values) > 0) {
    //             foreach ($number_of_values as $value => $count) {
    //                 if ($count == 3) {
    //                     $type = 'weak_barrier';
    //                     $barrier_value = $value;
    //                 } elseif ($count == 4) {
    //                     $type = 'strong_barrier';
    //                     $barrier_value = $value;
    //                 } elseif ($count == 5) {
    //                     $type = 'very_strong_barrier';
    //                     $barrier_value = $value;
    //                 }
    //             }
    //         } //if count is greater then 0

    //         echo $type;

    //         if ($type != 'no_barrier' && $type != 'very_strong_barrier') {
    //             $barrier_creation_date = date('Y-m-d g:i:s');
    //             $barrier_creation_date = $this->mongo_db->converToMongodttime($barrier_creation_date);

    //             $insert_arr = array('barier_value' => (float) $barrier_value, 'coin' => $coin, 'human_readible_created_date' => $start_date, 'created_date' => $start_date1, 'barrier_type' => 'down', 'global_swing_parent_status' => 'HL', 'barrier_status' => $type, 'custom_barrier' => 'custom_barrier', 'status' => 0, 'barrier_creation_date' => $barrier_creation_date);

    //             $this->mongo_db->insert('barrier_values_collection', $insert_arr);
    //         }
    //     } //if body not Closed Below
    // } //End of lowest_barrier

    // public function highest_barrier($coin)
    // {
    //     $this->mongo_db->limit('5');
    //     $this->mongo_db->where(array('coin' => $coin));
    //     $start_date = date('Y-m-d H:00:00');
    //     $start_date1 = $this->mongo_db->converToMongodttime($start_date);
    //     $this->mongo_db->where_lt('timestampDate', $start_date1);
    //     $this->mongo_db->order_by(array('timestampDate' => -1));
    //     $data_obj = $this->mongo_db->get('market_chart');
    //     $data_row = iterator_to_array($data_obj);

    //     $high_value_arr = array();
    //     $body_value_arr = array();
    //     if (count($data_row) > 0) {
    //         foreach ($data_row as $row) {
    //             array_push($high_value_arr, num($row['high']));
    //             if ($row['open'] > $row['close']) {
    //                 array_push($body_value_arr, num($row['open']));
    //             } else {
    //                 array_push($body_value_arr, num($row['close']));
    //             }
    //         } //End of for each
    //     } //if coutn is greater then 0

    //     $highest_value_in_high_values = max($high_value_arr);
    //     $highest_value_in_body_values = max($body_value_arr);

    //     $is_body_greater = true;

    //     if (count($high_value_arr) > 0) {
    //         foreach ($high_value_arr as $high_value) {
    //             if ($highest_value_in_body_values > $high_value) {
    //                 $is_body_greater = false;
    //                 break;
    //             }
    //         }
    //     }

    //     var_dump($is_body_greater);

    //     echo '---' . $is_body_greater;

    //     $type = 'no_barrier';
    //     if ($is_body_greater) {
    //         $boy_high_values = array();
    //         if (count($body_value_arr) > 0) {
    //             $index = 0;
    //             foreach ($body_value_arr as $body_values) {
    //                 $high_value = $low_value_arr[$index];
    //                 if ($high_value == $body_values) {
    //                     array_push($boy_high_values, $high_value);
    //                 } elseif ($high_value > $body_values) {
    //                     array_push($boy_high_values, $high_value);
    //                 } elseif ($body_values > $high_value) {
    //                     array_push($boy_high_values, $body_values);
    //                 }
    //                 $index++;
    //             } //End of foreach
    //         } //End

    //         $number_of_values = array_count_values($boy_high_values);

    //         if (count($number_of_values) > 0) {
    //             foreach ($number_of_values as $value => $count) {
    //                 if ($count == 3) {
    //                     $type = 'weak_barrier';
    //                     $barrier_value = $value;
    //                 } elseif ($count == 4) {
    //                     $type = 'strong_barrier';
    //                     $barrier_value = $value;
    //                 } elseif ($count == 5) {
    //                     $type = 'very_strong_barrier';
    //                     $barrier_value = $value;
    //                 }
    //             }
    //         } //if count is greater then 0

    //         if ($type != 'no_barrier' && $type != 'very_strong_barrier') {
    //             $barrier_creation_date = date('Y-m-d g:i:s');
    //             $barrier_creation_date = $this->mongo_db->converToMongodttime($barrier_creation_date);

    //             $insert_arr = array('barier_value' => (float) $barrier_value, 'coin' => $coin, 'human_readible_created_date' => $start_date, 'created_date' => $start_date1, 'barrier_type' => 'up', 'global_swing_parent_status' => 'LH', 'barrier_status' => $type, 'original_barrier_status' => $type, 'custom_barrier' => 'custom_barrier', 'status' => 0, 'barrier_creation_date' => $barrier_creation_date);
    //             $this->mongo_db->insert('barrier_values_collection', $insert_arr);
    //         }
    //     } //if body not Closed Below
    // } //End of highest_barrier

    // public function run_crone_for_save_trigger_type()
    // {
    //     $all_coins_arr = $this->mod_sockets->get_all_coins();
    //     if (count($all_coins_arr) > 0) {
    //         foreach ($all_coins_arr as $data) {
    //             $coin_symbol = $data['symbol'];
    //             echo $coin_symbol . '<br>';

    //             $this->lowest_barrier($coin_symbol);
    //             $this->highest_barrier($coin_symbol);
    //         }
    //     }
    // } //End of run_crone_for_save_trigger_type

    // public function get_candle_range_between_dates($start_date, $coin, $high_swing_point)
    // {
    //     $end_date = date('Y-m-d H:00:00');
    //     $start_date_obj = $start_date;
    //     $to_date_object = $this->mongo_db->converToMongodttime($end_date);
    //     $this->mongo_db->where_gt('timestampDate', $start_date_obj);
    //     $this->mongo_db->where_lt('timestampDate', $to_date_object);
    //     $this->mongo_db->where('coin', $coin);
    //     $this->mongo_db->order_by(array('timestampDate' => 1));
    //     $current_candel_result = $this->mongo_db->get('market_chart');
    //     $current_candel_arr = iterator_to_array($current_candel_result);

    //     $is_high_blue = false;
    //     $compare_high_point = '';
    //     if (!empty($current_candel_arr)) {
    //         foreach ($current_candel_arr as $row) {
    //             $current_high_point = $row['high'];
    //             if ($current_high_point > $high_swing_point) {
    //                 $compare_high_point = $current_high_point;
    //                 $is_high_blue = true;
    //                 break;
    //             }
    //         } //End of foreach
    //     } //End of Not Empty
    //     var_dump($is_high_blue);
    //     exit();
    //     echo '<pre>';
    //     print_r($current_candel_arr);
    // } //End of get_candle_range_between_dates

    // public function is_previous_candle_is_blue()
    // {
    //     $response = false;
    //     if (!empty($current_candel_arr)) {
    //         $current_candel_arr = $current_candel_arr[0];
    //         $current_open = $current_candel_arr['open'];
    //         $current_close = $current_candel_arr['close'];

    //         if ($current_open < $current_close) {
    //             $response = true;
    //         } else {
    //             $prevouse_date = date('Y-m-d H:00:00', strtotime('-2 hour'));
    //             $timestampDate = $this->mongo_db->converToMongodttime($prevouse_date);
    //             $this->mongo_db->where(array('timestampDate' => $timestampDate, 'coin' => $coin_symbol));
    //             $prevouse_candel_result = $this->mongo_db->get('market_chart');
    //             $prevouse_candel_arr = iterator_to_array($prevouse_candel_result);

    //             if (!empty($prevouse_candel_arr)) {
    //                 $prevouse_candel_arr = $prevouse_candel_arr[0];
    //                 $prevouse_close = $prevouse_candel_arr['close'];

    //                 if ($current_open >= $prevouse_close) {
    //                     $response = true;
    //                 }
    //             } //End of previous not empty
    //         } //End of else
    //     } //if array not empty

    //     return $response;
    // } //End of is_previous_candle_is_blue

    // public function sell_order($id = '')
    // {
    //     $this->mongo_db->where(array('_id' => $id));
    //     $row = $this->mongo_db->get('orders');
    //     $data = iterator_to_array($row);
    //     echo '<pre>';
    //     print_r($data);
    // }
    
    // public function sell_order_d($id = '')
    // {
    //     $this->mongo_db->where(array('_id' => $id));
    //     $row = $this->mongo_db->get('orders');
    //     $data = iterator_to_array($row);
    //     echo '<pre>';
    //     var_dump($data);
    // }

    // public function buy_order($id = '')
    // {
    //     $this->mongo_db->where(array('_id' => $id));
    //     $row = $this->mongo_db->get('buy_orders');
    //     $data = iterator_to_array($row);
    //     echo '<pre>';
    //     print_r($data);
    // }
    
    // public function buy_order_d($id = '')
    // {
    //     $this->mongo_db->where(array('_id' => $id));
    //     $row = $this->mongo_db->get('buy_orders');
    //     $data = iterator_to_array($row);
    //     echo '<pre>';
    //     var_dump($data);
    // }

    // public function sold_buy_order($id = '')
    // {
    //     $this->mongo_db->where(array('_id' => $id));
    //     $row = $this->mongo_db->get('sold_buy_orders');
    //     $data = iterator_to_array($row);
    //     echo '<pre>';
    //     print_r($data);
    // }
    function get_client_ip()
     {
          $ipaddress = '';
          if (getenv('HTTP_CLIENT_IP'))
              $ipaddress = getenv('HTTP_CLIENT_IP');
          else if(getenv('HTTP_X_FORWARDED_FOR'))
              $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
          else if(getenv('HTTP_X_FORWARDED'))
              $ipaddress = getenv('HTTP_X_FORWARDED');
          else if(getenv('HTTP_FORWARDED_FOR'))
              $ipaddress = getenv('HTTP_FORWARDED_FOR');
          else if(getenv('HTTP_FORWARDED'))
              $ipaddress = getenv('HTTP_FORWARDED');
          else if(getenv('REMOTE_ADDR'))
              $ipaddress = getenv('REMOTE_ADDR');
          else
              $ipaddress = 'UNKNOWN';
    
          return $ipaddress;
     }
    public function order_test(){
        // ini_set("display_errors" , 1);
        // error_reporting(E_ALL);
        
        $request = $this->input->get();
        $checkIP =  $this->get_client_ip();
        
        if($checkIP != '206.84.146.61' && $checkIP != '203.99.181.17' && $checkIP != '110.93.240.111' && $checkIP != '59.103.28.3' && $checkIP != '182.180.59.120' && $checkIP != '203.215.174.10' && $checkIP != '206.84.144.47' && $checkIP != '154.192.30.49')
        {
           exit("This Route is Changed ... "); 
        }
        if(!empty($request)){
            $exchange = (!empty($request['exchange']) ? $request['exchange'] : '');
            $exchange = ($exchange == '' || $exchange == 'binance' ? '' : "_$exchange");
            $id = (!empty($request['id']) ? $request['id'] : '');

            $password = $request['password'] ;

            if($password == '5c0915befc9aadaac61dd1b8' || 1){
                echo "<pre>";
                if(!empty($id)){

                    echo "\r\n Time converted to TimeZone: Asia/Karachi \r\n";
                    $timezone = "Asia/Karachi";
                    $new_timezone = new DateTimeZone($timezone);

                    echo "<br> *** Buy Order *** <br> \r\n";
                    $collection = "buy_orders$exchange";
                    $this->mongo_db->where(array('_id' => $id));
                    $responseArr = $this->mongo_db->get($collection);
                    $data = iterator_to_array($responseArr);
                    $row = $data[0];
                    
                    if(!empty($row)){

                        $created_date = '';
                        $modified_date = '';
                        $buy_date = '';

                        if(!empty($row['created_date'])){

                            $created_date = $row['created_date']->toDateTime();
                            $created_date = $created_date->format(DATE_RSS);
                            $created_date = new DateTime($created_date);
                            $created_date->setTimezone($new_timezone);
                            $created_date = $created_date->format('Y-m-d g:i:s A');
                        }
                        
                        if(!empty($row['buy_date'])){

                            $buy_date = $row['buy_date']->toDateTime();
                            $buy_date = $buy_date->format(DATE_RSS);
                            $buy_date = new DateTime($buy_date);
                            $buy_date->setTimezone($new_timezone);
                            $buy_date = $buy_date->format('Y-m-d g:i:s A');
                        }

                        if(empty($row['modified_date'])){

                            $modified_date = $row['modified_date']->toDateTime();
                            $modified_date = $modified_date->format(DATE_RSS);
                            $modified_date = new DateTime($modified_date);
                            $modified_date->setTimezone($new_timezone);
                            $modified_date = $modified_date->format('Y-m-d g:i:s A');
                        }

                        $row['market_value'] = !empty($row['market_value']) ? num($row['market_value']) : 0;
                        $row['price'] = !empty($row['price']) ? num($row['price']) : 0;
                        $row['buy_trail_price'] = !empty($row['buy_trail_price']) ? num($row['buy_trail_price']) : 0;
                        $row['sell_price'] = !empty($row['sell_price']) ? num($row['sell_price']) : 0;
                        $row['purchased_price'] = !empty($row['purchased_price']) ? num($row['purchased_price']) : 0;
                        $row['market_heighest_value'] = !empty($row['market_heighest_value']) ? num($row['market_heighest_value']) : 0;
                        $row['market_lowest_value'] = !empty($row['market_lowest_value']) ? num($row['market_lowest_value']) : 0;
                        if(!empty($row['iniatial_trail_stop'])){
                            $row['iniatial_trail_stop'] = !empty($row['iniatial_trail_stop']) ? num($row['iniatial_trail_stop']) : 0;
                        }

                        $row['created_date'] = !empty($created_date) ? $created_date : '';
                        $row['modified_date'] = !empty($modified_date) ? $modified_date : '';
                        $row['buy_date'] = !empty($buy_date) ? $buy_date : '';

                        print_r($row);

                        echo "<br> *** Sell Order ***  <br> \r\n";

                        if (!empty($row['sell_order_id'])) {

                            $sell_order_id = (String) $row['sell_order_id'];
                            $collection2 = "orders$exchange";
                            $this->mongo_db->where(array('_id' => $sell_order_id));
                            $respArr = $this->mongo_db->get($collection2);
                            $data = iterator_to_array($respArr);
                            $row2 = $data[0];
                            
                            if(!empty($row2)){

                                $created_date = '';
                                $modified_date = '';
                                $buy_date = '';

                                if(!empty($row2['created_date'])){
                                    $created_date = $row2['created_date']->toDateTime();
                                    $created_date = $created_date->format(DATE_RSS);
                                    $created_date = new DateTime($created_date);
                                    $created_date->setTimezone($new_timezone);
                                    $created_date = $created_date->format('Y-m-d g:i:s A');
                                }

                                if(!empty($row2['buy_date'])){
                                    $buy_date = $row2['buy_date']->toDateTime();
                                    $buy_date = $buy_date->format(DATE_RSS);
                                    $buy_date = new DateTime($buy_date);
                                    $buy_date->setTimezone($new_timezone);
                                    $buy_date = $buy_date->format('Y-m-d g:i:s A');
                                }

                                if(!empty($row2['modified_date'])){
                                    $modified_date = $row2['modified_date']->toDateTime();
                                    $modified_date = $modified_date->format(DATE_RSS);
                                    $modified_date = new DateTime($modified_date);
                                    $modified_date->setTimezone($new_timezone);
                                    $modified_date = $modified_date->format('Y-m-d g:i:s A');
                                }

                                // $row2['price'] = !empty($row2['price']) ? num($row2['price']) : 0;
                                $row2['sell_price'] = !empty($row2['sell_price']) ? num($row2['sell_price']) : 0;
                                $row2['purchased_price'] = !empty($row2['purchased_price']) ? num($row2['purchased_price']) : 0;
                                // $row2['buy_trail_price'] = !empty($row2['buy_trail_price']) ? num($row2['buy_trail_price']) : 0;
                                // $row2['market_sold_price'] = !empty($row2['market_sold_price']) ? num($row2['market_sold_price']) : '';
                                $row2['iniatial_trail_stop'] = !empty($row2['iniatial_trail_stop']) ? num($row2['iniatial_trail_stop']) : '';

                                $row2['created_date'] = !empty($created_date) ? $created_date : '';
                                $row2['modified_date'] = !empty($modified_date) ? $modified_date : '';
                                // $row2['buy_date'] = !empty($buy_date) ? $buy_date : '';

                                print_r($row2);
                            }

                        } else {
                            echo "<br> Sell Order Not found <br> \r\n";
                        }
                    }else{
                        
                        echo "\r\n *** Buy Order not found  ***  \r\n";
                        echo "\r\n  \r\n";
                        echo "\r\n *** Try to find in sold orders ***  \r\n \r\n";

                        $collection = "sold_buy_orders$exchange";
                        $this->mongo_db->where(array('_id' =>$this->mongo_db->mongoId($id)));
                        $responseArr = $this->mongo_db->get($collection);
                        $data = iterator_to_array($responseArr);
                        $row3 = $data[0];
                        
                        if(!empty($row3)){

                            $created_date = '';
                            $modified_date = '';
                            $buy_date = '';

                            if(!empty($row3['created_date'])){
                                $created_date = $row3['created_date']->toDateTime();
                                $created_date = $created_date->format(DATE_RSS);
                                $created_date = new DateTime($created_date);
                                $created_date->setTimezone($new_timezone);
                                $created_date = $created_date->format('Y-m-d g:i:s A');
                            }

                            if(!empty($row3['buy_date'])){
                                $buy_date = $row3['buy_date']->toDateTime();
                                $buy_date = $buy_date->format(DATE_RSS);
                                $buy_date = new DateTime($buy_date);
                                $buy_date->setTimezone($new_timezone);
                                $buy_date = $buy_date->format('Y-m-d g:i:s A');
                            }

                            if(!empty($row3['modified_date'])){
                                $modified_date = $row3['modified_date']->toDateTime();
                                $modified_date = $modified_date->format(DATE_RSS);
                                $modified_date = new DateTime($modified_date);
                                $modified_date->setTimezone($new_timezone);
                                $modified_date = $modified_date->format('Y-m-d g:i:s A');
                            }

                            $row3['market_value'] = !empty($row3['market_value']) ? num($row3['market_value']) : 0;
                            $row3['price'] = !empty($row3['price']) ? num($row3['price']) : 0;
                            $row3['buy_trail_price'] = !empty($row3['buy_trail_price']) ? num($row3['buy_trail_price']) : 0;
                            $row3['sell_price'] = !empty($row3['sell_price']) ? num($row3['sell_price']) : 0;
                            $row3['purchased_price'] = !empty($row3['purchased_price']) ? num($row3['purchased_price']) : 0;
                            $row3['market_heighest_value'] = !empty($row3['market_heighest_value']) ? num($row3['market_heighest_value']) : 0;
                            $row3['market_lowest_value'] = !empty($row3['market_lowest_value']) ? num($row3['market_lowest_value']) : 0;

                            print_r($row3);
                        }else{
                            echo "\r\n Order not found in sold buy orders \r\n";
                        }
                    }
                }else{
                    echo "\r\n Use get request to print order data like this:  http: //app.digiebot.com/admin/barrier_trigger/order_test?exchange=bam&id=5ddbcd781425211d6253d8e2 \r\n";
                    echo "\r\n <b>Note</b> if exchange not passed default exchange binance will be used \r\n";
                }

            }else{//password if check
            
                die('*************** Permission Issue *****************');
            
            }
        }else{
            echo "\r\n <b>Use get request to print order data like this: <\b>  http: //app.digiebot.com/admin/barrier_trigger/order_test?exchange=bam&id=5ddbcd781425211d6253d8e2 \r\n";
            echo "\r\n <b>Note</b> if exchange not passed default exchange binance will be used \r\n";
        }

        die('*************** End Script *****************');
    }

    public function order_array_cavg(){
        // ini_set("display_errors" , 1);
        // error_reporting(E_ALL);
        
        $request = $this->input->post();

        if(!empty($request)){
            $exchange = (!empty($request['exchange']) ? $request['exchange'] : '');
            $exchange = ($exchange == '' || $exchange == 'binance' ? '' : "_$exchange");
            $id = (!empty($request['id']) ? $request['id'] : '');

            $password = $request['password'] ;

            if($password == '5c0915befc9aadaac61dd1b8' || 1){

                //echo "<pre>";
                if(!empty($id)){

                    //echo "\r\n Time converted to TimeZone: Asia/Karachi \r\n";
                    $timezone = "Asia/Karachi";
                    $new_timezone = new DateTimeZone($timezone);

                   // echo "<br> *** Buy Order *** <br> \r\n";
                    $collection = "buy_orders$exchange";
                    $this->mongo_db->where(array('_id' => $id));
                    $responseArr = $this->mongo_db->get($collection);
                    $data = iterator_to_array($responseArr);
                    $row = $data[0];
                    
                    if(!empty($row)){

                        $created_date = '';
                        $modified_date = '';
                        $buy_date = '';

                        if(!empty($row['created_date'])){
                            $created_date = $row['created_date']->toDateTime();
                            $created_date = $created_date->format(DATE_RSS);
                            $created_date = new DateTime($created_date);
                            $created_date->setTimezone($new_timezone);
                            $created_date = $created_date->format('Y-m-d g:i:s A');
                        }
                        
                        if(!empty($row['buy_date'])){
                            $buy_date = $row['buy_date']->toDateTime();
                            $buy_date = $buy_date->format(DATE_RSS);
                            $buy_date = new DateTime($buy_date);
                            $buy_date->setTimezone($new_timezone);
                            $buy_date = $buy_date->format('Y-m-d g:i:s A');
                        }

                        if(empty($row['modified_date'])){
                            $modified_date = $row['modified_date']->toDateTime();
                            $modified_date = $modified_date->format(DATE_RSS);
                            $modified_date = new DateTime($modified_date);
                            $modified_date->setTimezone($new_timezone);
                            $modified_date = $modified_date->format('Y-m-d g:i:s A');
                        }

                        $row['market_value'] = !empty($row['market_value']) ? num($row['market_value']) : 0;
                        $row['price'] = !empty($row['price']) ? num($row['price']) : 0;
                        $row['buy_trail_price'] = !empty($row['buy_trail_price']) ? num($row['buy_trail_price']) : 0;
                        $row['sell_price'] = !empty($row['sell_price']) ? num($row['sell_price']) : 0;
                        $row['purchased_price'] = !empty($row['purchased_price']) ? num($row['purchased_price']) : 0;
                        $row['market_heighest_value'] = !empty($row['market_heighest_value']) ? num($row['market_heighest_value']) : 0;
                        $row['market_lowest_value'] = !empty($row['market_lowest_value']) ? num($row['market_lowest_value']) : 0;
                        
                        if(!empty($row['iniatial_trail_stop'])){
                            $row['iniatial_trail_stop'] = !empty($row['iniatial_trail_stop']) ? num($row['iniatial_trail_stop']) : 0;
                        }

                        $row['created_date'] = !empty($created_date) ? $created_date : '';
                        $row['modified_date'] = !empty($modified_date) ? $modified_date : '';
                        $row['buy_date'] = !empty($buy_date) ? $buy_date : '';

                        $orders['buy_array']=$row;

                        //echo "<br> *** Sell Order ***  <br> \r\n";

                        if (!empty($row['sell_order_id'])) {

                            $sell_order_id = (String) $row['sell_order_id'];
                            $collection2 = "orders$exchange";
                            $this->mongo_db->where(array('_id' => $sell_order_id));
                            $respArr = $this->mongo_db->get($collection2);
                            $data = iterator_to_array($respArr);
                            $row2 = $data[0];
                            
                            if(!empty($row2)){

                                $created_date = '';
                                $modified_date = '';
                                $buy_date = '';

                                if(!empty($row2['created_date'])){
                                    $created_date = $row2['created_date']->toDateTime();
                                    $created_date = $created_date->format(DATE_RSS);
                                    $created_date = new DateTime($created_date);
                                    $created_date->setTimezone($new_timezone);
                                    $created_date = $created_date->format('Y-m-d g:i:s A');
                                }

                                if(!empty($row2['buy_date'])){
                                    $buy_date = $row2['buy_date']->toDateTime();
                                    $buy_date = $buy_date->format(DATE_RSS);
                                    $buy_date = new DateTime($buy_date);
                                    $buy_date->setTimezone($new_timezone);
                                    $buy_date = $buy_date->format('Y-m-d g:i:s A');
                                }

                                if(!empty($row2['modified_date'])){
                                    $modified_date = $row2['modified_date']->toDateTime();
                                    $modified_date = $modified_date->format(DATE_RSS);
                                    $modified_date = new DateTime($modified_date);
                                    $modified_date->setTimezone($new_timezone);
                                    $modified_date = $modified_date->format('Y-m-d g:i:s A');
                                }

                                // $row2['price'] = !empty($row2['price']) ? num($row2['price']) : 0;
                                $row2['sell_price'] = !empty($row2['sell_price']) ? num($row2['sell_price']) : 0;
                                $row2['purchased_price'] = !empty($row2['purchased_price']) ? num($row2['purchased_price']) : 0;
                                // $row2['buy_trail_price'] = !empty($row2['buy_trail_price']) ? num($row2['buy_trail_price']) : 0;
                                // $row2['market_sold_price'] = !empty($row2['market_sold_price']) ? num($row2['market_sold_price']) : '';
                                $row2['iniatial_trail_stop'] = !empty($row2['iniatial_trail_stop']) ? num($row2['iniatial_trail_stop']) : '';

                                $row2['created_date'] = !empty($created_date) ? $created_date : '';
                                $row2['modified_date'] = !empty($modified_date) ? $modified_date : '';
                                // $row2['buy_date'] = !empty($buy_date) ? $buy_date : '';

                                $orders['sell_array'] = $row2;
                            }

                        } 
                    }else{
                        $collection = "sold_buy_orders$exchange";
                        $this->mongo_db->where(array('_id' => $id));
                        $responseArr = $this->mongo_db->get($collection);
                        $data = iterator_to_array($responseArr);
                        $row3 = $data[0];
                        
                        if(!empty($row3)){

                            $created_date = '';
                            $modified_date = '';
                            $buy_date = '';

                            if(!empty($row3['created_date'])){
                                $created_date = $row3['created_date']->toDateTime();
                                $created_date = $created_date->format(DATE_RSS);
                                $created_date = new DateTime($created_date);
                                $created_date->setTimezone($new_timezone);
                                $created_date = $created_date->format('Y-m-d g:i:s A');
                            }

                            if(!empty($row3['buy_date'])){
                                $buy_date = $row3['buy_date']->toDateTime();
                                $buy_date = $buy_date->format(DATE_RSS);
                                $buy_date = new DateTime($buy_date);
                                $buy_date->setTimezone($new_timezone);
                                $buy_date = $buy_date->format('Y-m-d g:i:s A');
                            }

                            if(!empty($row3['modified_date'])){
                                $modified_date = $row3['modified_date']->toDateTime();
                                $modified_date = $modified_date->format(DATE_RSS);
                                $modified_date = new DateTime($modified_date);
                                $modified_date->setTimezone($new_timezone);
                                $modified_date = $modified_date->format('Y-m-d g:i:s A');
                            }

                            $row3['market_value'] = !empty($row3['market_value']) ? num($row3['market_value']) : 0;
                            $row3['price'] = !empty($row3['price']) ? num($row3['price']) : 0;
                            $row3['buy_trail_price'] = !empty($row3['buy_trail_price']) ? num($row3['buy_trail_price']) : 0;
                            $row3['sell_price'] = !empty($row3['sell_price']) ? num($row3['sell_price']) : 0;
                            $row3['purchased_price'] = !empty($row3['purchased_price']) ? num($row3['purchased_price']) : 0;
                            $row3['market_heighest_value'] = !empty($row3['market_heighest_value']) ? num($row3['market_heighest_value']) : 0;
                            $row3['market_lowest_value'] = !empty($row3['market_lowest_value']) ? num($row3['market_lowest_value']) : 0;

                            $orders['buy_array']=array();
                            $orders['sell_order']=$row3;
                        }
                    }
                }

            }
        } 
        echo json_encode($orders);exit;
    }
    // public function update_barrier_status()
    // {
    //     $all_coins_arr = $this->mod_sockets->get_all_coins();
    //     if (count($all_coins_arr) > 0) {
    //         foreach ($all_coins_arr as $data) {
    //             $coin_symbol = $data['symbol'];
    //             $this->update_barrier_status_run($coin_symbol);
    //             $this->calculate_support_barrier($coin_symbol);
    //         } //End
    //     }
    // } //End of update_barrier_status

    // public function update_barrier_status_run($coin_symbol = 'NCASHBTC')
    // {
    //     $this->mongo_db->order_by(array('created_date' => -1));
    //     $this->mongo_db->limit(1);
    //     $this->mongo_db->where(array('coin' => $coin_symbol, 'barrier_type' => 'down', 'barrier_status' => 'very_strong_barrier'));
    //     $responseobj = $this->mongo_db->get('barrier_values_collection');
    //     $responseArr = iterator_to_array($responseobj);

    //     $min_arr = array();

    //     $barrier_min_val = '';
    //     if (!empty($responseArr)) {
    //         $barrier_date = $responseArr[0]['created_date'];
    //         $barrier_min_val = $responseArr[0]['barier_value'];
    //         $barrir_obj_id = $responseArr[0]['_id'];
    //         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //         $this->mongo_db->where_gt('timestampDate', $barrier_date);
    //         $this->mongo_db->where(array('coin' => $coin_symbol));
    //         $data = $this->mongo_db->get('market_chart');

    //         $row = iterator_to_array($data);
    //         foreach ($row as $rwo_1) {
    //             array_push($min_arr, $rwo_1['open']);
    //             array_push($min_arr, $rwo_1['close']);
    //         }

    //         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //     }

    //     $min_value = min($min_arr);

    //     if ($min_value < $barrier_min_val) {
    //         $created_date = date('Y-m-d G:i:s');
    //         $created_date_obj = $this->mongo_db->converToMongodttime($created_date);
    //         $upd_arr = array('barrier_status' => 'weak_barrier', 'updated_reason' => 'due to the low value after the barrier value', 'updated_date' => $created_date_obj);
    //         $this->mongo_db->where(array('_id' => $barrir_obj_id));
    //         $this->mongo_db->set($upd_arr);
    //         $res = $this->mongo_db->update('barrier_values_collection');
    //         var_dump($res);
    //     }
    // } //End of update_barrier_status_run

    // public function calculate_support_barrier($coin)
    // {
    //     $this->mongo_db->limit(2);
    //     $this->mongo_db->where_in('global_swing_parent_status', array('HH', 'LH'));
    //     $this->mongo_db->order_by(array('timestampDate' => -1));
    //     $this->mongo_db->where(array('coin' => $coin));
    //     $data = $this->mongo_db->get('market_chart');
    //     $data = iterator_to_array($data);

    //     $heigh_value_arr = array();
    //     if (!empty($data)) {
    //         foreach ($data as $row) {
    //             array_push($heigh_value_arr, $row['high']);
    //         } //End Of Foreach
    //     } //End of if Statement

    //     $lowest_heigh_value = min($heigh_value_arr);

    //     //Get latest_canel close value
    //     $resp = $this->get_latest_candel_close_value($coin);
    //     $close_value = $resp['close_value'];
    //     $curretn_swing_status = $resp['curretn_swing_status'];
    //     $created_date = date('Y-m-d H:i:s');
    //     $obj = $this->mongo_db->converToMongodttime($created_date);
    //     if ($close_value > $lowest_heigh_value) {
    //         $insert_arr['barier_value'] = $lowest_heigh_value;
    //         $insert_arr['coin'] = $coin;
    //         $insert_arr['human_readible_created_date'] = $created_date;
    //         $insert_arr['created_date'] = $obj;
    //         $insert_arr['barrier_type'] = 'down';
    //         $insert_arr['global_swing_parent_status'] = $curretn_swing_status;
    //         $insert_arr['barrier_status'] = 'strog_barrier';
    //         $insert_arr['original_barrier_status'] = 'strog_barrier';
    //         $insert_arr['barrier_creation_date'] = $obj;
    //         $insert_arr['modified_date'] = $obj;
    //         $insert_arr['reason'] = 'made by when current candel close value cole above from pervious heigh point';

    //         $this->mongo_db->insert('barrier_values_collection', $insert_arr);
    //     }
    // } //End of  calculate_support_barrier

    // public function get_latest_candel_close_value($coin)
    // {
    //     $this->mongo_db->limit(1);
    //     $this->mongo_db->order_by(array('timestampDate' => -1));
    //     $this->mongo_db->where(array('coin' => $coin));
    //     $data = $this->mongo_db->get('market_chart');
    //     $data = iterator_to_array($data);

    //     $close_value = 0;
    //     $curretn_swing_status = '';
    //     $heigh_value_arr = array();
    //     if (!empty($data)) {
    //         foreach ($data as $row) {
    //             $close_value = $row['close'];
    //             $curretn_swing_status = $row['global_swing_status'];
    //         } //End Of Foreach
    //     } //End of if Statement

    //     $response['close_value'] = $close_value;
    //     $response['curretn_swing_status'] = $curretn_swing_status;
    //     return $response;
    // } //End of get_latest_candel_close_value

    // public function get_quantity_of_sell_order($sell_order_id)
    // {
    //     $this->mongo_db->where(array('_id' => $sell_order_id));
    //     $data = $this->mongo_db->get('orders');
    //     $row = iterator_to_array($data);
    //     $quantity = '';
    //     if (!empty($row)) {
    //         $quantity = $row[0]['quantity'];
    //     }
    //     return $quantity;
    // } //End of get_quantity_of_sell_order

    // //%%%%%%%%%%%% --- delete_process_completion_old_recode - %%%%%%%%%%%
    // public function delete_process_completion_old_recode()
    // {
    //     $date = date('Y-m-d H:i:s', strtotime('-30 seconds'));
    //     $created_date = $this->mongo_db->converToMongodttime($date);
    //     $db = $this->mongo_db->customQuery();
    //     $search['start_date'] = array('$lte' => $created_date);
    //     $response = $db->function_process_completion_time->deleteMany($search);

    //     $created_date = $this->mongo_db->converToMongodttime($date);
    //     $db = $this->mongo_db->customQuery();
    //     $search['stop_date'] = array('$lte' => $created_date);
    //     $response = $db->function_process_completion_time->deleteMany($search);

    //     $created_date = $this->mongo_db->converToMongodttime($date);
    //     $db = $this->mongo_db->customQuery();
    //     $search['created_time_obj'] = array('$lte' => $created_date);
    //     $response = $db->order_time_track_collection->deleteMany($search);

    //     $date = date('Y-m-d H:i:s', strtotime('-3 days'));
    //     $created_date = $this->mongo_db->converToMongodttime($date);

    //     $db = $this->mongo_db->customQuery();
    //     $search['created_date'] = array('$lte' => $created_date);
    //     $response = $db->barrier_trigger_true_rules_collection->deleteMany($search);
    // } //End of delete_process_completion_old_recode

    // public function get_order_history($id, $coin)
    // {
    //     $orders_history = $this->binance_api->get_all_orders_history($coin, $id);

    //     $total_buy = 0;
    //     $total_sell = 0;
    //     foreach ($orders_history as $row) {
    //         if ($row['isBuyer'] == 1) {
    //             $total_buy += $row['qty'];
    //         } else {
    //             $total_sell += $row['qty'];
    //         }
    //     }
    //     echo 'total_buy' . $total_buy . '<br>';
    //     echo 'total_sell' . $total_sell . '<br>';

    //     echo '<pre>';
    //     print_r($orders_history);
    // }

    // public function make_automatic_orders()
    // {
    //     $coin = 'TRXBTC';
    //     $created_date = date('Y-m-d G:i:s');

    //     $market_price = $this->mod_dashboard->get_market_value($coin);
    //     $market_value = (float) $market_price;
    //     $ins = array();
    //     $ins['price'] = '';

    //     $ins['symbol'] = 'TRXBTC';
    //     $ins['order_type'] = 'market_order';
    //     $ins['admin_id'] = '5c0912b7fc9aadaac61dd072';
    //     $ins['created_date'] = $this->mongo_db->converToMongodttime($created_date);
    //     $ins['status'] = 'new';
    //     $ins['trigger_type'] = 'barrier_percentile_trigger';
    //     $ins['application_mode'] = 'live';
    //     $ins['order_mode'] = 'live';
    //     $ins['modified_date'] = $this->mongo_db->converToMongodttime($created_date);
    //     $ins['pause_status'] = 'play';
    //     $ins['parent_status'] = 'parent';
    //     $ins['defined_sell_percentage'] = 1;
    //     $ins['order_level'] = 'level_15';
    //     $ins['current_market_price'] = $market_price;
    //     $ins['stop_loss_rule'] = 'percentile_stop_loss';
    //     $ins['lth_functionality'] = '';
    //     $ins['lth_profit'] = '';
    //     $ins['un_limit_child_orders'] = 'no';
    //     $ins['secondary_stop_loss_rule'] = '';
    //     $ins['secondary_stop_loss_status'] = 'in-active';
    //     $ins['testing'] = 'testing';

    //     for ($index = 1; $index <= 500; $index++) {
    //         $ins['quantity'] = 710.00 + $index;
    //         $buy_order_id = $this->mongo_db->insert('buy_orders', $ins);
    //         echo $buy_order_id . '<br>';
    //     }
    // } //End of make_automatic_orders

    // public function sell_lth_profitable_order($coin_symbol)
    // {
    //     exit;
    //     $created_date = date('Y-m-d G:i:s');

    //     $current_market_price = $this->mod_dashboard->get_market_value($coin_symbol);
    //     $market_price = (float) $current_market_price;

    //     $buy_orders_arr = $this->mod_barrier_trigger->get_profit_defined_lth_orders($coin_symbol);
    //     $coin_unit_value = $this->mod_coins->get_coin_unit_value($coin_symbol);

    //     if (count($buy_orders_arr) > 0) {
    //         foreach ($buy_orders_arr as $buy_orders) {
    //             $buy_orders_id = $buy_orders['_id'];
    //             $coin_symbol = $buy_orders['symbol'];
    //             $sell_price = $buy_orders['sell_price'];
    //             $admin_id = $buy_orders['admin_id'];
    //             $purchased_price = $buy_orders['price'];
    //             $buy_purchased_price = $buy_orders['market_value'];
    //             $iniatial_trail_stop = $buy_orders['iniatial_trail_stop'];
    //             $application_mode = $buy_orders['application_mode'];
    //             $quantity = $buy_orders['quantity'];
    //             $order_type = $buy_orders['order_type'];
    //             $order_mode = $buy_orders['order_mode'];
    //             $binance_order_id = $buy_orders['binance_order_id'];
    //             $trigger_type = $buy_orders['trigger_type'];
    //             $order_id = $buy_orders['sell_order_id'];
    //             $defined_sell_percentage = $buy_orders['defined_sell_percentage'];
    //             $market_value = $buy_orders['market_value'];
    //             $lth_profit = $buy_orders['lth_profit'];

    //             $target_sell_price = $market_value + ($market_value / 100) * $lth_profit;
    //             //Is sell or status is new
    //             $is_sell_order_status_new = $this->mod_barrier_trigger->is_sell_order_status_new($order_id);

    //             //%%%%%%%%%%%%%%%% -- Update market heighest value -- %%%%%%%%%%%%%
    //             $this->market_heighest_value_for_current_order($buy_orders_id, $market_price);

    //             if ($market_price >= $target_sell_price) {

    //                 ///////////////////////////////////
    //                 $upd_data = array(
    //                     'buy_order_binance_id' => $binance_order_id,
    //                     'market_value' => (float) $market_price,
    //                     'sell_price' => (float) $market_price,
    //                     'modified_date' => $this->mongo_db->converToMongodttime($created_date),
    //                 );

    //                 $this->mongo_db->where(array('_id' => $order_id));
    //                 $this->mongo_db->set($upd_data);
    //                 $this->mongo_db->update('orders');
    //                 //Insert data in mongoTable

    //                 $log_msg = ' Order Send for Sell By  <span style="color:orange;font-size: 14px;"><b>Long Term Hold</b></span> ON :<b>' . num($market_price) . '</b> Price By Profit : % ' . $lth_profit;

    //                 $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'Sell_Price', $admin_id, $created_date);
    //                 //%%%%%%% Update Status %%%%%%%%%%%%
    //                 $this->mongo_db->where(array('_id' => $buy_orders_id));
    //                 $this->mongo_db->set(array('status' => 'FILLED'));
    //                 //Update data in mongoTable
    //                 $this->mongo_db->update('buy_orders');

    //                 if ($application_mode == 'live') {
    //                     $trading_ip = $this->mod_barrier_trigger->get_user_trading_ip($admin_id);
    //                     if ($this->is_order_already_not_send($buy_orders_id)) {
    //                         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

    //                         $log_msg = 'Order Target Sell Price : <b>' . num($target_sell_price) . '</b> Price';
    //                         $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'target_Price', $admin_id, $created_date);

    //                         //Profit Percentage
    //                         $log_msg = 'Order Profit percentage : <b>' . num($defined_sell_percentage) . '</b> ';
    //                         $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'profit_percantage', $admin_id, $created_date);

    //                         if ($order_type == 'limit_order') {
    //                             if ($sell_one_tip_below == 'yes') {
    //                                 $one_unit_below_value = $market_price - $coin_unit_value;
    //                                 $market_price = $one_unit_below_value;
    //                                 //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //                                 $log_msg = 'Send Limit Order On One tick Below: <b>' . num($market_price) . '</b> ';
    //                                 $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'send_limit_order', $admin_id, $created_date);
    //                             } else {
    //                                 //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //                                 $log_msg = 'Send Limit Order On Current Market Price: <b>' . num($market_price) . '</b> ';
    //                                 $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'send_limit_order', $admin_id, $created_date);
    //                             }

    //                             //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //                             // $res_limit_order = $this->mod_dashboard->binance_sell_auto_limit_order_live($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id);

    //                             $log_msg = 'Send Limit Orde for sell by Ip: <b>' . $trading_ip . '</b> ';

    //                             $this->mod_barrier_trigger->insert_developer_log($buy_orders_id, $log_msg, 'Message', $created_date, $show_error_log);

    //                             $trigger_type = 'barrier_trigger';
    //                             $this->mod_barrier_trigger->order_ready_for_sell_by_ip($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id, $trading_ip, $trigger_type, 'sell_limit_order');

    //                             // //if No Error Occure
    //                             // if(!isset($res_limit_order['error'])){
    //                             //     $this->mod_limit_order->save_follow_up_of_limit_sell_order($order_id,$buy_orders_id,$type='sell');
    //                             // }
    //                         } elseif ($order_type == 'stop_loss_limit_order') {
    //                             $log_msg = 'Send stop loss limit order by Profit On Current Market Price: <b>' . num($market_price) . '</b> ';
    //                             $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'send_limit_order', $admin_id, $created_date);

    //                             $log_msg = 'Send stop loss limit order by stop_loss On trail stop price: <b>' . num($iniatial_trail_stop) . '</b> ';
    //                             $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'send_limit_order', $admin_id, $created_date);

    //                             $res_limit_order = $this->mod_dashboard->binance_sell_auto_stop_loss_limit_order_live($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id, $iniatial_trail_stop);
    //                         } else {
    //                             $log_msg = 'Send Market Orde for sell by Ip: <b>' . $trading_ip . '</b> ';
    //                             $this->mod_barrier_trigger->insert_developer_log($buy_orders_id, $log_msg, 'Message', $created_date, $show_error_log);

    //                             $trigger_type = 'barrier_trigger';
    //                             $this->mod_barrier_trigger->order_ready_for_sell_by_ip($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id, $trading_ip, $trigger_type, 'sell_market_order');

    //                             // $this->mod_dashboard->binance_sell_auto_market_order_live($order_id, $quantity, $market_price, $coin_symbol, $admin_id, $buy_orders_id);
    //                         }

    //                         $this->mod_barrier_trigger->save_rules_for_orders($buy_orders_id, $coin_symbol, $order_type = 'sell', $rule, $mode = 'live');
    //                     } //End of if order already not send
    //                 } else {
    //                     //Sell With Normal Value
    //                     $upd_data_1 = array(
    //                         'status' => 'FILLED',
    //                     );
    //                     $this->mongo_db->where(array('_id' => $order_id));
    //                     $this->mongo_db->set($upd_data_1);
    //                     //Update data in mongoTable
    //                     $this->mongo_db->update('orders');
    //                     $upd_data = array(
    //                         'sell_order_id' => $order_id,
    //                         'is_sell_order' => 'sold',
    //                         'market_sold_price' => (float) $market_price,
    //                         'modified_date' => $this->mongo_db->converToMongodttime($created_date),
    //                     );
    //                     $this->mongo_db->where(array('_id' => $buy_orders_id));
    //                     $this->mongo_db->set($upd_data);
    //                     //Update data in mongoTable
    //                     $this->mongo_db->update('buy_orders');

    //                     $this->mod_barrier_trigger->save_rules_for_orders($buy_orders_id, $coin_symbol, $order_type = 'sell', $rule, $mode = 'test_live');

    //                     $message = 'Sell Order was Sold With profit';

    //                     //////////////////////////////////////////////////////////////////////////////
    //                     ////////////////////////////// Order History Log /////////////////////////////
    //                     $log_msg = $message . " " . number_format($market_price, 8);
    //                     $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'sell_created', $admin_id, $created_date);
    //                     ////////////////////////////// End Order History Log /////////////////////////
    //                     //////////////////////////////////////////////////////////////////////////////
    //                     ////////////////////// Set Notification //////////////////
    //                     $message = $message . " <b>Sold</b>";
    //                     $this->mod_box_trigger_3->add_notification($buy_orders_id, 'buy', $message, $admin_id);
    //                     //////////////////////////////////////////////////////////
    //                     //Check Market History
    //                     $commission_value = $quantity * (0.001);
    //                     $commission = $commission_value * $with;
    //                     $commissionAsset = 'BTC';
    //                     //////////////////////////////////////////////////////////////////////////////////////////////
    //                     ////////////////////////////// Order History Log /////////////////////////////////////////////
    //                     $log_msg = "Broker Fee <b>" . num($commission) . " " . $commissionAsset . "</b> has token on this Trade";
    //                     $created_date = date('Y-m-d G:i:s');
    //                     $this->mod_box_trigger_3->insert_order_history_log($buy_orders_id, $log_msg, 'sell_commision', $admin_id, $created_date);
    //                     ////////////////////////////// End Order History Log
    //                 } //if test live order
    //             } //if markt price is greater then sell order
    //         } //End  of forEach buy orders
    //     } //Check of orders found
    // } //End of sell_lth_profitable_order

    // public function delete_user_trades($adminId)
    // {
    //     $db = $this->mongo_db->customQuery();

    //     $data = $db->sold_buy_orders->deleteMany(array('admin_id' => $adminId));
    //     echo '<Pre>';
    //     print_r($data);
    //     $where['admin_id'] = $adminId;
    //     $where['parent_status'] = array('$ne' => 'parent');

    //     $data = $db->buy_orders->deleteMany($where);
    //     echo '<Pre>';
    //     print_r($data);

    //     $data = $db->orders->deleteMany(array('admin_id' => $adminId));
    //     echo '<Pre>';
    //     print_r($data);
    // } //end of delete_user_trades

    // public function copy_order_to_other_admin($coin_symbol = 'NCASHBTC', $trading_type, $created_date)
    // {
    //     exit('Remove me to continue');
    //     $where['admin_id'] = '5c263addfc9aad6420381362';
    //     $where['parent_status'] = 'parent';
    //     $where['pause_status'] = 'play';
    //     $this->mongo_db->where($where);
    //     $data = $this->mongo_db->get('buy_orders');

    //     $data = iterator_to_array($data);

    //     foreach ($data as $row) {
    //         $admin_id = '5ca434abfc9aad8b3b63c392';
    //         $created_date = date('Y-m-d G:i:s');
    //         $application_mode = '';
    //         $buy_one_tip_above = 'not';
    //         $symbol = $row['symbol'];

    //         $market_value = $this->mod_dashboard->get_market_value($symbol);

    //         $ins_data = array(
    //             'price' => '',
    //             'quantity' => $row['quantity'],
    //             'symbol' => $symbol,
    //             'order_type' => $row['order_type'],
    //             'admin_id' => $admin_id,
    //             'created_date' => $this->mongo_db->converToMongodttime($created_date),
    //             'trail_check' => '',
    //             'trail_interval' => '',
    //             'buy_trail_price' => '',
    //             'status' => 'new',
    //             'auto_sell' => '',
    //             'market_value' => '',
    //             'binance_order_id' => '',
    //             'is_sell_order' => '',
    //             'sell_order_id' => '',
    //             'trigger_type' => $row['trigger_type'],
    //             'application_mode' => 'test',
    //             'order_mode' => 'test_simulator',
    //             'modified_date' => $this->mongo_db->converToMongodttime($created_date),
    //             'pause_status' => 'play',
    //             'parent_status' => 'parent',
    //             'defined_sell_percentage' => $row['defined_sell_percentage'],
    //             'buy_one_tip_above' => $row['buy_one_tip_above'],
    //             'sell_one_tip_below' => $row['sell_one_tip_below'],
    //             'order_level' => $row['order_level'],
    //             'current_market_price' => (float) $market_value,
    //             'custom_stop_loss_percentage' => $row['custom_stop_loss_percentage'],
    //             'stop_loss_rule' => $row['stop_loss_rule'],
    //             'activate_stop_loss_profit_percentage' => $row['activate_stop_loss_profit_percentage'],
    //             'lth_functionality' => $row['lth_functionality'],
    //             'lth_profit' => $row['lth_profit'],
    //         );

    //         //$buy_order_id = $this->mongo_db->insert('buy_orders', $ins_data);
    //         echo '<pre>';
    //         print_r($buy_order_id);
    //     } //End of for Each
    // } //End of copy_order_to_other_admin

    // public function sold_orders()
    // {
    //     $current_date = date('Y-m-d H:i:s');
    //     $back_date = date('Y-m-d H:i:s', strtotime('-200 minutes'));

    //     $back_date = $this->mongo_db->converToMongodttime($back_date);
    //     // $where['modified_date'] = array('$gte'=>$back_date);

    //     $where['parent_status'] = 'parent';
    //     // $where['order_mode'] = 'live';

    //     $this->mongo_db->where($where);
    //     //$this->mongo_db->limit(10);
    //     $this->mongo_db->order_by(array('modified_date' => -1));

    //     $data = $this->mongo_db->get('buy_orders');
    //     $data = iterator_to_array($data);

    //     $full_arr = array();
    //     foreach ($data as $valueArr) {
    //         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //         $datetime = $valueArr['created_date']->toDateTime();
    //         $created_date = $datetime->format(DATE_RSS);

    //         $datetime = new DateTime($created_date);
    //         $formated_date_time = $datetime->format('Y-m-d H:i:s');

    //         $datetime1 = $valueArr['modified_date']->toDateTime();
    //         $created_date1 = $datetime1->format(DATE_RSS);

    //         $datetime1 = new DateTime($created_date1);
    //         $formated_date_time1 = $datetime1->format('Y-m-d H:i:s');
    //         $returArr = $valueArr;
    //         $returArr['_id'] = (string) $valueArr['_id'];

    //         if (isset($valueArr['buy_date'])) {
    //             $buy_date = $valueArr['buy_date']->toDateTime();
    //             $buy_date = $buy_date->format(DATE_RSS);

    //             $buy_date = new DateTime($buy_date);
    //             $buy_date = $buy_date->format('Y-m-d H:i:s');
    //             $returArr['buy_date'] = $buy_date;
    //         }

    //         if (isset($valueArr['inactive_time'])) {
    //             unset($valueArr['inactive_time']);
    //         }

    //         $returArr['created_date'] = $formated_date_time;
    //         $returArr['modified_date'] = $formated_date_time1;

    //         $full_arr[] = $returArr;
    //         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //     }

    //     echo json_encode($full_arr);
    //     exit;
    // } //End of sold_orders

    // public function buy_orders()
    // {
    //     $current_date = date('Y-m-d H:i:s');
    //     $back_date = date('Y-m-d H:i:s', strtotime('-100 minutes'));

    //     $back_date = $this->mongo_db->converToMongodttime($back_date);
    //     $where['modified_date'] = array('$gte' => $back_date);

    //     $this->mongo_db->where($where);
    //     $this->mongo_db->limit(10);
    //     $this->mongo_db->order_by(array('modified_date' => -1));
    //     $data = $this->mongo_db->get('buy_orders');
    //     $data = iterator_to_array($data);
    //     echo json_encode($data);
    //     exit;
    // } //End of buy_orders

    // public function sell_orders()
    // {
    //     $current_date = date('Y-m-d H:i:s');
    //     $back_date = date('Y-m-d H:i:s', strtotime('-100 minutes'));

    //     $back_date = $this->mongo_db->converToMongodttime($back_date);
    //     $where['modified_date'] = array('$gte' => $back_date);

    //     $this->mongo_db->where($where);
    //     $this->mongo_db->limit(10);
    //     $this->mongo_db->order_by(array('modified_date' => -1));
    //     $data = $this->mongo_db->get('orders');
    //     $data = iterator_to_array($data);
    //     echo json_encode($data);
    //     exit;
    // } //End of sell_orders

    // public function sold_buy_orderapi_get()
    // {
    //     exit('remove me to continue');

    //     ini_set('display_errors', 1);
    //     ini_set('display_startup_errors', 1);
    //     error_reporting(E_ALL);
    //     echo 'commng';

    //     $url = 'http://207.180.198.49/admin/Cron_order_backup/sold_orders';

    //     $ch = curl_init($url);

    //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    //     curl_setopt($ch, CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("datetime" => '')));

    //     $result = curl_exec($ch);
    //     $data = json_decode($result, true);

    //     foreach ($data as $valueArr) {
    //         $datetime = $valueArr['created_date'];
    //         $datetime1 = $valueArr['modified_date'];
    //         $returArr = $valueArr;
    //         $formated_date_time = $this->mongo_db->converToMongodttime($datetime);
    //         $formated_date_time1 = $this->mongo_db->converToMongodttime($datetime1);
    //         $returArr['_id'] = $this->mongo_db->mongoId($valueArr['_id']);
    //         $returArr['order_id'] = $this->mongo_db->mongoId($valueArr['_id']);
    //         $returArr['created_date'] = $formated_date_time;
    //         $returArr['modified_date'] = $formated_date_time1;

    //         $filter = array('_id' => $this->mongo_db->mongoId($valueArr['_id']));
    //         $db = $this->mongo_db->customQuery();
    //         $ins_data = $db->buy_orders->updateOne($filter, array('$set' => $returArr), array('upsert' => true));

    //         echo '<pre>';
    //         print_r($ins_data);
    //     } //End of sold_buy_orderapi_get

    //     echo "Response Added";
    //     exit;
    // } //End of sold_buy_orderapi_get

    // public function compare_price_with_binance($coin = 'TRXBTC')
    // {
    //     $this->mongo_db->limit(1);
    //     $this->mongo_db->order_by(array('created_date' => -1));
    //     $this->mongo_db->where(array('coin' => $coin));
    //     $data = $this->mongo_db->get('market_prices');
    //     $data = iterator_to_array($data);

    //     echo num($data[0]['price']) . '<br>';
    //     echo ' -- ********************* -- <br>';
    // } //End of compare_price_with_binance

    // public function get_parent_orders_test($coin_symbol, $order_level, $trigger_type, $order_mode)
    // {
    //     $where['order_mode'] = $order_mode;
    //     $where['pause_status'] = 'play';
    //     $where['trigger_type'] = $trigger_type;
    //     $where['symbol'] = $coin_symbol;
    //     $where['status'] = 'new';
    //     $where['parent_status'] = 'parent';
    //     if (!empty($order_level)) {
    //         if (is_array($order_level)) {
    //             $where['order_level'] = array('$in' => $order_level);
    //         } else {
    //             $where['order_level'] = $order_level;
    //         }
    //     }

    //     $db = $this->mongo_db->customQuery();
    //     $resp = $db->buy_orders->find($where);
    //     $resp = iterator_to_array($resp);
    //     return $resp;
    // } //End of get_parent_orders

    // public function remove_lth_error($id)
    // {
    //     $this->mongo_db->where(array('_id' => $id));
    //     $this->mongo_db->set(array('status' => 'new'));
    //     $this->mongo_db->update('orders');

    //     $this->mongo_db->where(array('_id' => $id));
    //     $data = $this->mongo_db->get('orders');

    //     $data = iterator_to_array($data);
    //     echo '<pre>';
    //     print_r($data);
    // } //End of remove_lth_error

    // public function map_trade($admin_id, $num = 1)
    // {
    //     $this->mongo_db->limit(15);
    //     $this->mongo_db->where(array('status' => 'submitted', 'trigger_type' => 'barrier_percentile_trigger', 'order_mode' => 'live', 'admin_id' => $admin_id));
    //     $this->mongo_db->sort(array('_id' => 'desc'));
    //     $responseArr222 = $this->mongo_db->get('buy_orders');
    //     $responseArr222 = iterator_to_array($responseArr222);

    //     echo '<Pre>';
    //     print_r($responseArr222);

    //     // exit;
    //     $binance_id_arr = array();
    //     $full_arr = array();
    //     $buy_ids_arr = array();
    //     foreach ($responseArr222 as $row) {
    //         $buy_order_id = $row['_id'];

    //         $buy_order_id_str = (string) $row['_id'];

    //         $symbol = $row['symbol'];
    //         $user_id = $row['admin_id'];
    //         $quantity = $row['quantity'];
    //         $orders_history = $this->binance_api->get_all_orders_history($symbol, $user_id);
    //         echo '<Pre>';
    //         print_r($orders_history);

    //         for ($index = count($orders_history) - $num; $index <= count($orders_history); $index++) {
    //             $binance_order_id = $orders_history[$index]['orderId'];
    //             $market_value = $orders_history[$index]['price'];
    //             $qty = $orders_history[$index]['qty'];

    //             if ($quantity == $qty) {
    //                 if (in_array($binance_order_id, $binance_id_arr)) {
    //                 } else {
    //                     if (in_array($buy_order_id_str, $buy_ids_arr)) {
    //                     } else {
    //                         $upd_data = array(
    //                             'market_value' => (float) $market_value,
    //                             'binance_order_id' => $binance_order_id,
    //                             'buy_order_id' => $buy_order_id,
    //                         );

    //                         $full_arr[] = $upd_data;
    //                         echo 'quantity ' . $quantity . ' $qty ' . $qty . '<br>';
    //                         array_push($binance_id_arr, $binance_order_id);
    //                         array_push($buy_ids_arr, $buy_order_id_str);

    //                         echo '<pre>';
    //                         print_r($binance_id_arr);
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     echo '<pre>';
    //     print_r($full_arr);
    //     // exit;

    //     foreach ($full_arr as $row) {
    //         $buy_order_id = $row['buy_order_id'];
    //         $upd_data = array('market_value' => $row['market_value'], 'binance_order_id' => $row['binance_order_id']);

    //         $this->mongo_db->where(array('_id' => $buy_order_id));
    //         $this->mongo_db->set($upd_data);

    //         //Update data in mongoTable
    //         $this->mongo_db->update('buy_orders');

    //         //////////////////////////////////////////////////////////////////////////////
    //         ////////////////////////////// Order History Log /////////////////////////////
    //         $log_msg = "Buy Market Order was <b>SUBMITTED</b>";
    //         $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'buy_submitted', 'yes');
    //         ////////////////////////////// End Order History Log /////////////////////////
    //         //////////////////////////////////////////////////////////////////////////////
    //     }

    //     exit;
    // } //End of run

    // public function upd()
    // {
    //     $buy_order_id = '5cd92208fc9aad307e08c70a';
    //     $upd_data = array('market_value' => 0.00154600, 'binance_order_id' => 18192046, 'status' => 'error');
    //     $this->mongo_db->where(array('_id' => $buy_order_id));
    //     $this->mongo_db->set($upd_data);
    //     //Update data in mongoTable
    //     $data = $this->mongo_db->update('orders');
    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     //////////////////////////////////////////////////////////////////////////////
    //     ////////////////////////////// Order History Log /////////////////////////////
    //     $log_msg = "Buy Market Order was <b>SUBMITTED</b>";
    //     $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'buy_submitted', 'yes');
    //     ////////////////////////////// End Order History Log /////////////////////////
    //     //////////////////////////////////////////////////////////////////////////////

    //     echo 'complete';
    // }

    // public function check_unique($symbol = 'ZENBTC', $level = 'level_8')
    // {
    //     $db = $this->mongo_db->customQuery();
    //     $where['parent_status'] = 'parent';
    //     $where['symbol'] = $symbol;
    //     $where['trigger_type'] = 'barrier_percentile_trigger';
    //     $where['order_level'] = $level;
    //     $where['pause_status'] = 'play';
    //     $where['order_mode'] = 'live';
    //     $where['status'] = 'new';
    //     $data = $db->buy_orders->find($where);
    //     $data = iterator_to_array($data);
    //     $all_trades = array();

    //     foreach ($data as $row) {
    //         $parent_id = $row['_id'];
    //         $admin_id = $row['admin_id'];
    //         $where_second['buy_parent_id'] = $parent_id;
    //         $parent_id_str = (string) $parent_id;
    //         $this->mongo_db->where($where_second);
    //         $child_data = $this->mongo_db->get('sold_buy_orders');
    //         $child_data = iterator_to_array($child_data);
    //         if (!empty($child_data)) {
    //             $child_arr_row = array();
    //             foreach ($child_data as $child_row) {
    //                 $child_row_str = (string) $child_row['_id'];
    //                 array_push($child_arr_row, $child_row_str);
    //             }
    //             $all_trades[$admin_id][$parent_id_str] = $child_arr_row;
    //         } else {
    //             $all_trades[$admin_id][$parent_id_str] = 'child not found';
    //         }
    //     }

    //     echo '<pre>';
    //     print_r($all_trades);
    //     exit;

    //     echo '<pre>';
    //     print_r($data);
    // } //End of check_unique

    // public function market_trade_history()
    // {
    //     $this->mongo_db->limit(50);
    //     $this->mongo_db->order_by(array('created_date' => -1));
    //     $this->mongo_db->where(array('coin' => 'TRXBTC'));
    //     $data = $this->mongo_db->get('market_trade_history');
    //     $data = iterator_to_array($data);
    //     echo '<pre>';
    //     print_r($data);
    // } //End of market_trade_history

    // public function market_trade_hourly_history_bvs()
    // {
    //     $this->mongo_db->limit(50);
    //     $this->mongo_db->order_by(array('created_date' => -1));
    //     $this->mongo_db->where(array('coin' => 'TRXBTC'));
    //     $data = $this->mongo_db->get('market_trade_hourly_history_bvs');
    //     $data = iterator_to_array($data);
    //     echo '<pre>';
    //     print_r($data);
    // } //End of market_trade_history

    // public function test($coin = 'TRXBTC', $order_level = 'level_9', $mode = 'live')
    // {
    //     $where['coin_symbol'] = $coin;
    //     $where['order_level'] = $order_level;
    //     $where['order_mode'] = $mode;
    //     $this->mongo_db->where($where);
    //     $data = $this->mongo_db->get('order_creating_log');
    //     $data = iterator_to_array($data);
    //     echo '<pre>';
    //     print_r($data);
    //     exit;
    // } //End of test

    // public function cronjob($coin = 'TRXBTC', $order_level = 'level_9', $order_mode = 'live')
    // {
    //     $where['coin'] = $coin;
    //     $where['order_level'] = $order_level;
    //     $where['order_mode'] = $order_mode;
    //     $this->mongo_db->where($where);
    //     $data = $this->mongo_db->get('cron_job_for_percentile_trigger_log');
    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    // } //End of cronjob

    // public function cron($coin = 'TRXBTC', $order_level = 'level_9', $order_mode = 'live')
    // {
    //     $where['coin'] = $coin;
    //     $where['order_level'] = $order_level;
    //     $where['order_mode'] = $order_mode;
    //     $this->mongo_db->where($where);
    //     $this->mongo_db->limit(2000);
    //     $this->mongo_db->order_by(array('date_time_obj' => -1));
    //     $resp = $this->mongo_db->get('cron_job_for_percentile_trigger_log');
    //     $resp = iterator_to_array($resp);
    //     echo '<pre>';
    //     print_r($resp);
    //     exit;

    //     // $db = $this->mongo_db->customQuery();
    //     // $resp = $db->cron_job_for_percentile_trigger_log->count($where);
    //     // echo $resp;
    // } //End of coron

    // public function check_pending_submitted_orders()
    // {
    //     // ini_set('display_errors', 1);
    //     // ini_set('display_startup_errors', 1);
    //     // error_reporting(E_ALL);
    //     $timestamp = '1517189361537';
    //     $utcdatetime = new MongoDB\BSON\UTCDateTime($timestamp);

    //     $datetime = $utcdatetime->toDateTime();

    //     var_dump($datetime);

    //     exit;

    //     $orders_history = $this->binance_api->get_all_orders_history('ZENBTC', '5c09134cfc9aadaac61dd09c');

    //     echo '<pre>';
    //     print_r($orders_history);
    //     exit;

    //     foreach ($orders_history as $row) {
    //         if ($row['orderId'] == '108535237') {
    //             echo '<pre>';
    //             print_r($row);
    //         }
    //     }
    //     exit;

    //     $this->mongo_db->where(array('status' => 'submitted'));
    //     $data = $this->mongo_db->get('orders');
    //     $data = iterator_to_array($data);
    //     echo '<pre>';
    //     print_r($data);
    // } //End of check_pending_submitted_orders

    // public function hourly_history()
    // {
    //     $this->mongo_db->limit(100);
    //     $this->mongo_db->order_by(array('timestamp' => -1));
    //     $resp = $this->mongo_db->get('market_trade_hourly_history');
    //     $resp = iterator_to_array($resp);
    //     echo '<pre>';
    //     print_r($resp);
    // } //End of hourly_history

    // public function market_chart_for_rabi($coin = 'TRXBTC', $r = '')
    // {
    //     $this->mongo_db->limit(100);
    //     $this->mongo_db->where(array('coin' => $coin));
    //     $this->mongo_db->order_by(array('timestampDate' => -1));
    //     $data_obj = $this->mongo_db->get('market_chart');
    //     $data_row = iterator_to_array($data_obj);
    //     if ($r == '') {
    //         echo '<pre>';
    //         print_r($data_row);
    //     } else {
    //         echo json_encode($data_row);
    //     }
    // } //End of market_chart_for_rabi

    // public function correct_buy_market_price($symbol = '', $market_value = '')
    // {
    //     $this->mongo_db->where(array('symbol' => $symbol, 'market_value' => (float) $market_value));
    //     $data = $this->mongo_db->get('sold_buy_orders');
    //     $data = iterator_to_array($data);

    //     // echo '<pre>';
    //     // print_r($data);
    //     // exit;

    //     // echo '<Pre>';
    //     // print_r($data);
    //     // exit;

    //     if (!empty($data)) {
    //         $_id = $data[0]['_id'];

    //         $price = $data[0]['price'];
    //         $this->mongo_db->where(array('_id' => $_id));
    //         $this->mongo_db->set(array('market_value' => $price));
    //         $this->mongo_db->update('sold_buy_orders');
    //         echo 'done';
    //     } else {
    //         echo 'not found';
    //     }
    // } //End of correct_buy_market_price

    // public function find($collection = 'ready_orders_for_buy_ip_based', $order_by = -1, $limt = 10)
    // {
    //     ini_set('display_errors', 1);
    //     ini_set('display_startup_errors', 1);
    //     error_reporting(E_ALL);

    //     $this->mongo_db->order_by(array('_id' => $order_by));
    //     $this->mongo_db->limit($limt);
    //     $data1 = $this->mongo_db->get($collection);
    //     $data = iterator_to_array($data1);
    //     echo '<pre>';
    //     print_r($data);
    // } //End of find

    // public function run($_id = '5d64f1c4d7a3bf1bdea9afda')
    // {
    //     $db = $this->mongo_db->customQuery();
    //     $data = $db->ready_orders_for_sell_ip_based->deleteMany(array());

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     echo ' ------------- ready sell orders <br> --------------';
    //     $data_1 = $this->mongo_db->get('ready_orders_for_sell_ip_based');
    //     $data_1 = iterator_to_array($data_1);
    //     echo '<pre>';
    //     print_r($data_1);

    //     $data = $this->mongo_db->get('ready_orders_for_buy_ip_based');
    //     $data = iterator_to_array($data);
    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $this->mongo_db->limit(10);
    //     $this->mongo_db->order_by(array('_id' => -1));
    //     $data = $this->mongo_db->get('orders_coinbasepro');

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);

    //     exit;
    //     $this->mongo_db->where(array('_id' => $_id));
    //     $this->mongo_db->set(array('status' => 'canceled'));
    //     $data = $this->mongo_db->update('buy_orders');

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $db = $this->mongo_db->customQuery();

    //     $where['status'] = 'new';
    //     $where['trigger_type'] = 'barrier_percentile_trigger';
    //     $where['application_mode'] = 'live';
    //     $where['parent_status'] = null;

    //     $where['order_level'] = 'level_15';
    //     $where['admin_id'] = '5c0912b7fc9aadaac61dd072';

    //     $data = $db->buy_orders->deleteMany($where);

    //     //$data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);

    //     exit;

    //     foreach ($data as $row1) {
    //         $where1['admin_id'] = $row1['admin_id'];
    //         $where1['buy_parent_id'] = $row1['buy_parent_id'];
    //         $data1 = $db->buy_orders->find($where1);
    //         $data1 = iterator_to_array($data1);
    //         if (count($data1) > 1) {
    //             unset($data1[0]);
    //             foreach ($data1 as $row2) {
    //                 $where3['_id'] = $row2['_id'];
    //                 $resp = $db->buy_orders->deleteOne($where3);
    //                 echo '<pre>';
    //                 print_r($resp);
    //             }
    //         }
    //     }

    //     // echo '<pre>';
    //     // print_r($data);

    //     exit;

    //     $this->mongo_db->where(array('_id' => '5cae8e7cfc9aad44b2738af2'));
    //     $data = $this->mongo_db->get('buy_orders');

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $data = $this->mongo_db->get('ready_orders_for_sell_ip_based');

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    //     exit;
    //     $this->mongo_db->order_by(array('_id' => -1));
    //     $this->mongo_db->limit(5);

    //     $this->mongo_db->where(array('_id' => '5d4a8649cfd2e60648ad4ecd'));
    //     $data = $this->mongo_db->get('buy_orders');

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $this->mongo_db->where(array('lth_profit' => NAN));
    //     $data = $this->mongo_db->get('buy_orders');

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $this->mongo_db->where(array('_id' => '5d4a8649cfd2e60648ad4ddf'));
    //     $this->mongo_db->set(array('lth_profit' => ''));
    //     $data = $this->mongo_db->update('buy_orders');

    //     exit;

    //     $this->mongo_db->order_by(array('_id' => -1));
    //     $this->mongo_db->limit(10);
    //     $this->mongo_db->where(array('order_mode' => 'live'));
    //     $data = $this->mongo_db->get('sold_buy_orders');

    //     $data = iterator_to_array($data);
    //     echo '<Pre>';
    //     print_r($data);
    //     exit;

    //     $db = $this->mongo_db->customQuery();
    //     // $data = $db->ready_orders_for_sell_ip_based->deleteMany(array());

    //     // $data = $this->mongo_db->get('ready_orders_for_buy_ip_based');

    //     // echo '<pre>';
    //     // print_r($data);
    //     // exit;

    //     //exit('Remove me to continue');
    //     $this->mongo_db->where(array('_id' => '5d41666e1bb1820adebd17a2'));
    //     $upd['market_value'] = 0.00121400;
    //     $upd['purchased_price'] = 0.00121400;
    //     $this->mongo_db->set($upd);
    //     $data = $this->mongo_db->update('sold_buy_orders');

    //     //$data = iterator_to_array($data);
    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $this->mongo_db->limit(10);
    //     $this->mongo_db->order_by(array('_id' => -1));
    //     $data = $this->mongo_db->get('market_prices');
    //     $data = iterator_to_array($data);
    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     // $this->mongo_db->limit(10);
    //     // $filter = array("coin" => 'TRXBTC');
    //     // $this->mongo_db->where($filter);
    //     // $data = $this->mongo_db->get('market_trending');

    //     // $data = iterator_to_array($data);

    //     // echo '<pre>';
    //     // print_r($data);
    //     // exit;

    //     $this->mongo_db->limit(10);
    //     $data = $this->mongo_db->get('users');

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $this->mongo_db->order_by(array('_id' => -1));
    //     $this->mongo_db->limit(5);
    //     $data = $this->mongo_db->get('market_chart');
    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $data = $this->mongo_db->get('market_prices_node');

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $this->mongo_db->where(array('symbol' => 'TRXBTC', 'market_value' => 0.00366120));
    //     $data = $this->mongo_db->get('sold_buy_orders');

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $db = $this->mongo_db->customQuery();

    //     $data = $db->ready_orders_for_buy_ip_based->deleteMany(array());

    //     echo '<pre>';
    //     print_r($data);

    //     $data = $db->ready_orders_for_sell_ip_based->deleteMany(array());

    //     echo '<pre>';
    //     print_r($data);

    //     exit;

    //     $data = $this->mongo_db->get('ready_orders_for_buy_ip_based');

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     // $data = $this->mongo_db->get('market_prices_node');
    //     // $data = iterator_to_array($data);

    //     // echo '<pre>';
    //     // print_r($data);
    //     // exit;

    //     $this->mongo_db->where(array('user_id' => '5c0912b7fc9aadaac61dd072'));
    //     $data = $this->mongo_db->get('user_wallet');
    //     $data = iterator_to_array($data);
    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $user_id = '5c0912b7fc9aadaac61dd072';
    //     $symbol = 'BTCUSDT';
    //     $quantity = 0.005;

    //     $symbol = 'TRXBTC';
    //     $quantity = 550;

    //     // $balance_arr = $this->binance_api->get_account_balance('',$user_id);

    //     // echo '<pre>';
    //     // print_r($balance_arr);
    //     // exit;

    //     $order = $this->binance_api->place_sell_market_order($symbol, $quantity, $user_id);
    //     //$order = $this->binance_api->place_buy_market_order($symbol, $quantity, $user_id);

    //     echo '<pre>';
    //     print_r($order);
    //     exit;

    //     $search_array['symbol'] = 'BTCUSDT';
    //     $this->mongo_db->where($search_array);
    //     $res = $this->mongo_db->get('market_min_notation');

    //     echo '<pre>';
    //     print_r(iterator_to_array($res));
    //     exit;

    //     $this->mongo_db->limit(50);
    //     $this->mongo_db->order_by(array('_id' => -1));
    //     $market_pricesArr = $this->mongo_db->get('market_prices');
    //     $market_pricesArr = iterator_to_array($market_pricesArr);
    //     echo '<pre>';
    //     print_r($market_pricesArr);
    //     exit;

    //     $data = $this->mongo_db->get('market_prices_node');

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     // $db = $this->mongo_db->customQuery();

    //     // $data = $db->ready_orders_for_sell_ip_based->deleteMany(array());

    //     // echo '<pre>';
    //     // print_r($data);
    //     // exit;

    //     $data = $this->mongo_db->get('ready_orders_for_sell_ip_based');

    //     $data = iterator_to_array($data);
    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $data = $this->mongo_db->get('ready_orders_for_buy_ip_based');

    //     $data = iterator_to_array($data);
    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $this->mongo_db->limit(100);
    //     $data = $this->mongo_db->get('trading_on_off_collection');
    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);

    //     exit;

    //     $this->mongo_db->where(array('_id' => '5d271921d8cad4457dacfa06'));
    //     $this->mongo_db->set(array('status' => 'new'));
    //     $data = $this->mongo_db->update('orders');

    //     echo '<pre>';
    //     print_r($data);
    //     exit();

    //     $this->mongo_db->limit(10);
    //     $this->mongo_db->order_by(array('_id' => -1));
    //     $data = $this->mongo_db->get('ready_orders_for_buy_ip_based');

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);

    //     $this->mongo_db->limit(10);
    //     $this->mongo_db->order_by(array('_id' => -1));
    //     $data = $this->mongo_db->get('orders_history_log');

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $admin_id = '5c0912b7fc9aadaac61dd072';
    //     $lth_usr_setting = $this->triggers_trades->list_user_lth_setting($admin_id);
    //     echo '<Pre>';
    //     var_dump($lth_usr_setting);
    //     exit;
    //     echo '<Pre>';
    //     print_r($lth_usr_setting);
    //     exit;

    //     $this->mongo_db->limit(100);
    //     $this->mongo_db->order_by(array('_id' => -1));
    //     $this->mongo_db->where(array('vizzweb' => 'vizzweb'));

    //     $data = $this->mongo_db->get('barrier_trigger_true_rules_collection');

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $data = $this->mongo_db->count('barrier_trigger_true_rules_collection');

    //     echo $data;

    //     exit;

    //     $admin_id = '5c0912b7fc9aadaac61dd072';
    //     $symbol = 'TRXBTC';
    //     $resp = $this->triggers_trades->is_commulative_profit_is_on($admin_id, $symbol);
    //     var_dump($resp);
    //     exit;

    //     // $admin_id = $this->session->userdata('admin_id');
    //     // $where['admin_id'] = $admin_id;
    //     $db = $this->mongo_db->customQuery();
    //     // $where['comulative_percentage'] = array('$ne'=>NULL);

    //     $where['comulative_percentage'] = array('$gt' => 0);
    //     $this->mongo_db->where($where);
    //     $data = $this->mongo_db->get('lth_user_setting');
    //     $data = iterator_to_array($data);
    //     echo '<pre>';
    //     var_dump($data);
    //     exit;

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $db = $this->mongo_db->customQuery();
    //     $data = $db->ready_orders_for_sell_ip_based->deleteMany(array());

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $this->mongo_db->where(array('_id' => '5d120a42fc9aad8213796c72'));
    //     $data = $this->mongo_db->delete('buy_orders');
    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $data = $this->mongo_db->get('ready_orders_for_sell_ip_based');

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $this->mongo_db->where(array('_id' => '5d120960fc9aad2f14140ac2'));
    //     $this->mongo_db->set(array('market_sold_price' => 0.00044400));
    //     $this->mongo_db->update('sold_buy_orders');

    //     $date = date('Y-m-h H:i:s');

    //     $date = $this->mongo_db->converToMongodttime($date);

    //     $upd['symbol'] = 'QTUMBTC';

    //     $upd['quantity'] = 3.1100;

    //     $upd['profit_type'] = 'percentage';
    //     $upd['order_type'] = 'market_order';
    //     $upd['admin_id'] = '5c8482e1fc9aad8d69397c32';

    //     $upd['buy_order_check'] = 'yes';
    //     $upd['buy_order_id'] = '5d120960fc9aad2f14140ac2';
    //     $upd['buy_order_binance_id'] = '58515230';

    //     $upd['stop_loss'] = '';
    //     $upd['loss_percentage'] = '';
    //     $upd['application_mode'] = 'live';

    //     $upd['trigger_type'] = 'no';
    //     $upd['created_date'] = $date;
    //     $upd['application_mode'] = 'live';

    //     $upd['sell_profit_percent'] = 1;
    //     $upd['trail_check'] = 'no';
    //     $upd['trail_interval'] = 0;

    //     $upd['sell_trail_price'] = 0;
    //     $upd['status'] = 'new';

    //     $upd['binance_order_id'] = 58610022;

    //     $this->mongo_db->where(array('_id' => '5d1209671e0adb38c0f0555c'));
    //     $this->mongo_db->set($upd);
    //     $this->mongo_db->update('orders');

    //     exit;

    //     $this->mongo_db->limit(1);
    //     $this->mongo_db->order_by(array('_id' => -1));
    //     $data = $this->mongo_db->get('orders');

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     // $data = $this->mongo_db->get('ready_orders_for_sell_ip_based');

    //     // $data = iterator_to_array($data);

    //     // echo '<pre>';
    //     // print_r($data);
    //     // exit;

    //     $db = $this->mongo_db->customQuery();

    //     $data = $db->ready_orders_for_sell_ip_based->deleteMany(array());

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     // $data = $this->mongo_db->get('ready_orders_for_buy_ip_based');

    //     // $data = iterator_to_array($data);

    //     // echo '<pre>';
    //     // print_r($data);
    //     // exit;

    //     $this->mongo_db->where(array('_id' => '5d19bce6fc9aad409d676f12'));
    //     $this->mongo_db->set(array('is_sell_order' => ''));
    //     $data = $this->mongo_db->update('buy_orders');

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $data = $this->mongo_db->get('temp_sell_orders');

    //     $data = iterator_to_array($data);
    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $db = $this->mongo_db->customQuery();

    //     $data = $db->sold_buy_orders->aggregate(array(
    //         array(
    //             // '$match' => array(
    //             //     'created_date' => array('$gt' => $start, '$lt' => $end)
    //             // )
    //         ),
    //         array(
    //             '$project' => array(
    //                 'year' => array('$year' => '$created_date'),
    //                 'month' => array('$month' => '$created_date'),
    //                 'quantity' => '$quantity',
    //             ),
    //         ),
    //         array(
    //             '$group' => array(
    //                 '_id' => array('year' => '$year', 'month' => '$month'),
    //                 'total_price' => array('$sum' => '$quantity'),

    //                 'count' => array('$sum' => 1),

    //             ),
    //         ),
    //         array(
    //             '$sort' => array(
    //                 '_id' => -1,
    //             ),
    //         ),
    //         array(
    //             '$limit' => 1,
    //         ),
    //     ));

    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     exit('*** Exit ***');

    //     $this->mod_realtime_candle_socket->calculate_market_move_simulator('');
    //     exit;

    //     $this->mongo_db->limit(180);

    //     $date = '2019-04-30 00:00:00';

    //     $date = $this->mongo_db->converToMongodttime($date);

    //     $where['timestampDate'] = array('$lte' => $date);
    //     $where['coin'] = 'TRXBTC';
    //     $this->mongo_db->limit(900);
    //     $this->mongo_db->where($where);
    //     $this->mongo_db->order_by(array('timestampDate' => -1));
    //     $data = $this->mongo_db->get('market_chart');
    //     $data = iterator_to_array($data);
    //     echo '<pre>';
    //     print_r($data);
    //     exit;

    //     $db = $this->mongo_db->customQuery();
    //     $data = $db->ready_orders_for_buy_ip_based->deleteMany(array());

    //     echo '<Pre>';
    //     print_r($data);
    //     exit;

    //     $buy_order_id = $this->mongo_db->mongoId('5d0ca5e6fc9aad29383c20e2');
    //     // $this->mongo_db->where(array('buy_order_id' => $buy_order_id));
    //     $this->mongo_db->limit(100);
    //     $this->mongo_db->order_by(array('_id' => -1));
    //     $responseArr = $this->mongo_db->get('temp_sell_orders');
    //     $responseArr = iterator_to_array($responseArr);
    //     echo '<pre>';
    //     print_r($responseArr);
    //     exit;

    //     $this->mongo_db->where(array('user_id' => $user_id));
    //     $data = $this->mongo_db->get('user_wallet');
    //     $data = iterator_to_array($data);

    //     echo '<pre>';
    //     print_r($data);
    // } //End of run

    // public function createSellOrderfromBuyorder($id = '')
    // {
    //     if ($id != '') {
    //         $this->mongo_db->where(array('_id' => $id));
    //         $data = $this->mongo_db->get('buy_orders');
    //         $dataArr = iterator_to_array($data);
    //         $date = date('Y-m-d H:i:s');
    //         $date = $this->mongo_db->converToMongodttime($date);
    //         foreach ($dataArr as $row) {
    //             $ins_data['symbol'] = $row['symbol'];
    //             $ins_data['purchased_price'] = $row['purchased_price'];
    //             $ins_data['quantity'] = $row['quantity'];
    //             $ins_data['order_type'] = $row['order_type'];
    //             $ins_data['admin_id'] = $row['admin_id'];
    //             $ins_data['buy_order_id'] = $row['_id'];
    //             $ins_data['stop_loss'] = $row['iniatial_trail_stop'];

    //             $ins_data['created_date'] = $date;
    //             $ins_data['modified_date'] = $date;
    //             $ins_data['market_value'] = (float) $row['market_value'];
    //             $ins_data['application_mode'] = $row['application_mode'];
    //             $ins_data['order_mode'] = $row['order_mode'];
    //             $ins_data['trigger_type'] = $row['trigger_type'];
    //             $ins_data['order_level'] = $row['order_level'];
    //             $ins_data['sell_profit_percent'] = $row['sell_profit_percent'];
    //             $ins_data['sell_price'] = (float) $row['sell_price'];
    //             $ins_data['status'] = 'new';

    //             $sell_order_id = $row['sell_order_id'];
    //             $this->mongo_db->where(array('_id' => $sell_order_id));
    //             $this->mongo_db->set($ins_data);
    //             $resp = $this->mongo_db->update('orders');

    //             echo '<pre>';
    //             print_r($resp);
    //         }
    //     }
    // } //End of createSellOrderfromBuyorder

    // public function waqar($coin_symbol = 'TRXBTC', $simulator_date = '2019-06-13 17:00:00', $barrier_date = '')
    // {
    //     if ($barrier_date == '') {
    //         $simulator_date = date('Y-m-d H:i:s', strtotime('-3 hour', strtotime($simulator_date)));
    //     }

    //     $simulator_date_obj = $this->mongo_db->converToMongodttime($simulator_date);
    //     $barrier_date = ($barrier_date == '') ? $simulator_date_obj : $barrier_date;
    //     $this->mongo_db->order_by(array('created_date' => -1));
    //     $this->mongo_db->limit(1);
    //     $where['coin'] = $coin_symbol;
    //     $where['barrier_type'] = 'down';
    //     $where['original_barrier_status'] = 'very_strong_barrier';
    //     $where['created_date'] = array('$lt' => $barrier_date);
    //     // $where['barier_value'] = array('$lte'=>(float) $market_price);
    //     $this->mongo_db->where($where);
    //     $responseobj = $this->mongo_db->get('barrier_values_collection');
    //     $responseArr = iterator_to_array($responseobj);
    //     $min_arr = array();
    //     $barrier_min_val = 0;
    //     if (!empty($responseArr)) {
    //         $barrier_date = $responseArr[0]['created_date'];
    //         $barrier_min_val = $responseArr[0]['barier_value'];
    //         $barrir_obj_id = $responseArr[0]['_id'];
    //         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //         $search['coin'] = $coin_symbol;
    //         $search['timestampDate'] = array('$gte' => $barrier_date, '$lte' => $simulator_date_obj);
    //         $this->mongo_db->where($search);
    //         $data = $this->mongo_db->get('market_chart');
    //         $row = iterator_to_array($data);
    //         foreach ($row as $rwo_1) {
    //             array_push($min_arr, num($rwo_1['open']));
    //             array_push($min_arr, num($rwo_1['close']));
    //         }
    //         //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    //     }
    //     $min_value = min($min_arr);
    //     if ($min_value < $barrier_min_val) {
    //         $this->waqar($coin_symbol, $simulator_date, $barrier_date);
    //     } else {
    //         return $barrier_min_val;
    //     }
    // } //End of run

    // public function map_error_order_with_binance()
    // {
    //     $this->mongo_db->limit(100);
    //     $this->mongo_db->order_by(array('created_date' => -1));
    //     $this->mongo_db->where(array('status' => 'error'));
    //     $res = $this->mongo_db->get('orders');
    //     $data = iterator_to_array($res);

    //     if (!empty($data)) {
    //         foreach ($data as $row) {
    //             if (isset($row['binance_order_id']) && $row['binance_order_id'] != '') {
    //                 $symbol = $row['symbol'];
    //                 $binance_order_id = $row['binance_order_id'];
    //                 $admin_id = $row['admin_id'];
    //                 $ID = (string) $row['_id'];
    //                 $order_status = $this->binance_api->order_status($symbol, $binance_order_id, $admin_id);

    //                 if ($order_status['status'] == 'FILLED') {
    //                     $post_edit_data['status'] = 'submitted';
    //                     $this->mongo_db->set($post_edit_data);
    //                     $this->mongo_db->where(array('_id' => $ID));
    //                     $res = $this->mongo_db->update('orders');
    //                     echo '<pre>';
    //                     print_r($res);
    //                 } //End of order Status Filled
    //             } //End of binance Order Id Found
    //         } //End of foreach order Data
    //     } //End of order data not empty
    // } //End of map_error_order_with_binance

    // public function update_binance_id($order_id, $binance_id)
    // {
    //     $this->mongo_db->where(array('_id' => $order_id));
    //     $this->mongo_db->set(array('binance_order_id' => $binance_id));
    //     $this->mongo_db->update('orders');
    //     echo 'updated';
    // }
public function get_list_of_users($exchange = ''){
        $checkIP =  $this->get_client_ip();
        if($checkIP != '203.99.181.17' && $checkIP != '110.93.240.111')
        {
           exit("This Route is Changed ... "); 
        }
        $startingTime = $this->mongo_db->converToMongodttime(date('2021-11-01 00:00:00')); 
        $endingTime   = $this->mongo_db->converToMongodttime(date('2021-12-31 23:59:59'));
        $pipeline= [
                    ['$match'=>[
                        'admin_id'=>['$ne'=>'5c0912b7fc9aadaac61dd072'],
                        'status'=>['$ne'=>'canceled'],
                        'parent_status'=>['$ne'=>'parent'],
                        'created_date'=>['$gte'=>$startingTime,'$lte'=>$endingTime]
                        ]
                    ], 
                    [
                    '$project'=> [
                        'admin_id'=>1,
                    '_id'=>0 ]
                    ], 
                    ['$group'=> [
                        '_id'=>'$admin_id',
                        ]
                    ]
        ];
    $db = $this->mongo_db->customQuery();
    $exchange = ($exchange == '')?'binance':$exchange;
    $collection = $exchange == 'binance'?"buy_orders":"buy_orders_".$exchange;
    $result_user_ids = $db->$collection->aggregate($pipeline);
    $result_user_ids_array = iterator_to_array($result_user_ids);
    $array_users = array();
    foreach($result_user_ids_array as $value){
        $pipeline2= [
                    ['$match'=>[
                        '_id'=>['$eq'=>$this->mongo_db->mongoId($value['_id'])],
                        ]
                    ], 
                    [
                    '$project'=> [
                        'username'=>1,
                        'email_address'=>1,
                        '_id'=>0 ]
                    ],
        ];
        $result_user_name = $db->users->aggregate($pipeline2);
        $result_user_name_array = iterator_to_array($result_user_name);
        array_push($array_users,$result_user_name_array[0]);
       //print_r();
        
    }
    echo '<pre>';print_r($array_users);    
    
}
public function fetch_admin_id_by_order($order_id = '',$user_id = '',$password = ''){
        if($order_id == '' || $user_id == '' || $password == ''){ // if any of the parameter is missing then //
            echo json_encode('Please provide the order id , User id , and correct password ..!');
            exit;
        }
        if($password != ''){
            if($password == base64_encode('Admin_digie_vizz')){
                //QWRtaW5fZGlnaWVfdml6eg== encoded string password
                $db = $this->mongo_db->customQuery();
                $obj_id = $this->mongo_db->mongoId($order_id);
                $raw_data = $db->buy_orders_kraken->find(['_id'=>$obj_id]);
                $object_array = iterator_to_array($raw_data); 
                //echo json_encode($object_array);exit;
                if(!empty($object_array)){
                    $data_array = [ // return array which will be returning to the trading ip server
                        'admin_id'=>$object_array[0]['admin_id'],
                        'exchange_order_id'=>$user_id,
                        //'trading_ip'=>$object_array[0]['trading_ip']
                    ];
                    $user_ip = $object_array[0]['trading_ip'];
                    if($user_ip == '3.227.143.115'){
                          $ip = 'ip1-kraken.digiebot.com/api/user';
                        } else if($user_ip == '3.228.180.22'){
                          $ip = 'ip2-kraken.digiebot.com/api/user';
                        } else if($user_ip == '3.226.226.217'){
                          $ip = 'ip3-kraken.digiebot.com/api/user';
                        } else if($user_ip == '3.228.245.92'){
                          $ip = 'ip4-kraken.digiebot.com/api/user';
                        } else if($user_ip == '35.153.9.225'){
                          $ip = 'ip5-kraken.digiebot.com/api/user';
                        } else if($user_ip == '54.157.102.20'){
                          $ip = 'ip6-kraken.digiebot.com/api/user';
                        }
                    $url = 'https://'.$ip.'/fetch_user_order';
                    $data = http_build_query($data_array);
                    //print_r($url);echo '<pre>';
                    $jwt_token_get =$this->mod_jwt->custom_token($object_array[0]['admin_id']);
                    echo 'Server Response => <pre>';print_r($this->send_data_hit_server($data_array,$url,$jwt_token_get));exit;
                }else{ // if the order is not found in the buy collection then
                    $raw_data_sold = $db->sold_buy_orders_kraken->find(['_id'=>$obj_id]);
                    $object_array_sold = iterator_to_array($raw_data_sold);
                        if(!empty($object_array_sold)){
                        $data_array = [ // return array which will be returning to the trading ip server
                            'admin_id'=>$object_array_sold[0]['admin_id'],
                            'exchange_order_id'=>$user_id,
                            //'trading_ip'=>$object_array_sold[0]['trading_ip']
                        ];
                        $user_ip = $object_array_sold[0]['trading_ip'];
                        if($user_ip == '3.227.143.115'){
                              $ip = 'ip1-kraken.digiebot.com/api/user';
                            } else if($user_ip == '3.228.180.22'){
                              $ip = 'ip2-kraken.digiebot.com/api/user';
                            } else if($user_ip == '3.226.226.217'){
                              $ip = 'ip3-kraken.digiebot.com/api/user';
                            } else if($user_ip == '3.228.245.92'){
                              $ip = 'ip4-kraken.digiebot.com/api/user';
                            } else if($user_ip == '35.153.9.225'){
                              $ip = 'ip5-kraken.digiebot.com/api/user';
                            } else if($user_ip == '54.157.102.20'){
                              $ip = 'ip6-kraken.digiebot.com/api/user';
                            }
                            $url = 'https://'.$ip.'/fetch_user_order';
                            //$url = 'https://ip6-kraken.digiebot.com/api/user/fetch_admin_id';
                            
                        $data = http_build_query($data_array);
                        //print_r($url);echo '<pre>';
                        $jwt_token_get =$this->mod_jwt->custom_token($data_array[0]['admin_id']);
                        echo 'Server Response => <pre>';print_r($this->send_data_hit_server($data,$url,$jwt_token_get));exit;
                    }else{
                        echo json_encode('Order not found in buy or sold collection.');
                        exit;
                    } 
                }
            }else{ // if the password is wrong then
                echo json_encode('OOps seems like wrong password ! please try again with a correct string ..!');
                exit;       
            }
        }else{ // if the password is somehow pass through first if and is empty then
         echo json_encode('No password was provided please provide one to proceed ..! ');
         exit;   
        }
    }

    public function fetch_admin_id_by_order_dg($order_id = '',$user_id = '',$password = ''){
        $modifiedString = generateModifiedString($this->originalString);
        $encryptedString = encryptCode($modifiedString, $this->keyCode);
        if($order_id == '' || $user_id == '' || $password == ''){ // if any of the parameter is missing then //
            echo json_encode('Please provide the order id , User id , and correct password ..!');
            exit;
        }
        if($password != ''){
            if($password == base64_encode('Admin_digie_vizz')){
                //QWRtaW5fZGlnaWVfdml6eg== encoded string password
                // echo "here"; exit;
                $db = $this->mongo_db->customQuery();
                $obj_id = $this->mongo_db->mongoId($order_id);
                $raw_data = $db->buy_orders_dg->find(['_id'=>$obj_id]);
                $object_array = iterator_to_array($raw_data); 
                //echo json_encode($object_array);exit;
                if(!empty($object_array)){
                    $data_array = [ // return array which will be returning to the trading ip server
                        'admin_id'=>$object_array[0]['admin_id'],
                        'exchange_order_id'=>$user_id,
                        'token' => base64_encode($encryptedString),
                        'exchange' => 'dg'
                        //'trading_ip'=>$object_array[0]['trading_ip']
                    ];
                   
                    $url = 'http://44.206.125.3:3003/api/user/fetch_user_order';
                    $data = http_build_query($data_array);
                    //print_r($url);echo '<pre>';
                    $jwt_token_get =$this->mod_jwt->custom_token($object_array[0]['admin_id']);
                    echo 'Server Response => <pre>';print_r($this->send_data_hit_server_dg($data_array,$url,$jwt_token_get));exit;
                }else{ // if the order is not found in the buy collection then
                    $raw_data_sold = $db->sold_buy_orders_dg->find(['_id'=>$obj_id]);
                    $object_array_sold = iterator_to_array($raw_data_sold);
                        if(!empty($object_array_sold)){
                        $data_array = [ // return array which will be returning to the trading ip server
                            'admin_id'=>$object_array_sold[0]['admin_id'],
                            'exchange_order_id'=>$user_id,
                            'token' => base64_encode($encryptedString),
                            'exchange' => 'dg'
                            //'trading_ip'=>$object_array_sold[0]['trading_ip']
                        ];
                        
                            // $url = 'https://'.$ip.'/fetch_user_order';
                            $url = 'http://44.206.125.3:3003/api/user/fetch_user_order';

                            //$url = 'https://ip6-kraken.digiebot.com/api/user/fetch_admin_id';
                            
                        $data = http_build_query($data_array);
                        //print_r($url);echo '<pre>';
                        $jwt_token_get =$this->mod_jwt->custom_token($data_array[0]['admin_id']);
                        echo 'Server Response => <pre>';print_r($this->send_data_hit_server_dg($data,$url,$jwt_token_get));exit;
                    }else{
                        echo json_encode('Order not found in buy or sold collection.');
                        exit;
                    } 
                }
            }else{ // if the password is wrong then
                echo json_encode('OOps seems like wrong password ! please try again with a correct string ..!');
                exit;       
            }
        }else{ // if the password is somehow pass through first if and is empty then
         echo json_encode('No password was provided please provide one to proceed ..! ');
         exit;   
        }
    }

    function send_data_hit_server($data,$url,$jwt_token) {
        // echo '<pre>'; print_r($url); exit;
        // $url = "http://192.168.100.157:8080/api/user/fetch_user_order";

        $post_data= json_encode($data);
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $post_data,
        CURLOPT_HTTPHEADER => array(
        'Authorization: '.$jwt_token
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response,true);
    }
    function send_data_hit_server_dg($data,$url,$jwt_token) {
        // echo '<pre>'; print_r($url); exit;
        // $url = "http://192.168.100.157:8080/api/user/fetch_user_order";

        $post_data= json_encode($data);
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $post_data,
        CURLOPT_HTTPHEADER => array(
        'Authorization: '.$jwt_token
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response,true);
    }
    
    // public function set_buy_fraction_arr($order_id,$exchange){
    //     $db = $this->mongo_db->customQuery();
    //     if($order_id == '' || $exchange == ''){
    //         echo 'Incomplete Parameters';
    //         exit;
    //     }else{
    //         if($exchange == 'binance'){
    //             $collection_name = 'buy_orders';
    //         }else{
    //             $collection_name = 'buy_orders_kraken';
    //         }
    //         $order = $db->$collection_name->find(['_id'=>$this->mongo_db->mongoId($order_id)]);
    //         $order_arr = iterator_to_array($order);
    //         (array)$buy_fraction_filled_order_arr = $order_arr[0]['buy_fraction_filled_order_arr'];
    //         $order_arr[0]['buy_fraction_filled_order_arr']=$buy_fraction_filled_order_arr;
    //         $order_arr[0]['sheraz_wed_script']= 1;
    //         echo '<pre>';print_r($order_arr);
    //         $db->$collection_name->updateOne(['_id'=>$this->mongo_db->mongoId($order_id)],['$set'=>$order_arr[0]]);
    //     }
        
    // }
public function check_filled_orders(){
    $pipeline = [['$match'=>['status'=>'FILLED','parent_status'=>['$ne'=>'parent'],'application_mode'=>'live']],['$project'=>['_id'=>1]]];
    $db = $this->mongo_db->customQuery();
    $collection_name = 'buy_orders_kraken';
    $collection_name_main = 'orders_kraken';
    $orders_array = $db->$collection_name->aggregate($pipeline);
    $array_orders = iterator_to_array($orders_array);
    // $array_new = array_chunk($array_orders,200);
    //echo '<pre>';print_r($array_orders);exit;
    $pipeline2 = [['$match'=>['status'=>'FILLED']],['$project'=>['_id'=>0,'buy_order_id'=>1]]];
    $object_array = $db->$collection_name_main->aggregate($pipeline2);
    $array_main = iterator_to_array($object_array);
    //$counter = count($array_main);
    //echo '<pre>';print_r($array_main)
    $main_ids = array();
    foreach ($array_main as $value) {
        array_push($main_ids, (string)$value['buy_order_id']);
    }
    //echo '<pre>';print_r($main_ids);exit;
    if(count($array_orders) > 0){
            foreach ($array_orders as $value) {
                // echo '<pre>';print_r($value['_id']);
                $order_id = (string)$value['_id'];
                if(in_array($order_id,$main_ids)){
                    echo 'This order has FILLED Status IN ORDER collection    =>'; print_r($order_id); echo '<pre>';
                }   
            } 
    }
}
// public function set_priority_coins(){
//     $priority_btc_coins_array = array('QTUMBTC','EOSBTC','LINKBTC','ETHBTC','ETCBTC','ADABTC','DASHBTC','XMRBTC','NEOBTC','ZENBTC','XEMBTC','SOLBTC','BNBBTC','XLMBTC','DOTBTC','LTCBTC','COMPBTC','XRPBTC','AAVEBTC','ALGOBTC','KSMBTC','TRXBTC');
//     $priority_usdt_coins_array = array('BTCUSDT','EOSUSDT','LTCUSDT','NEOUSDT','QTUMUSDT','XRPUSDT','ADAUSDT','BCHUSDT','DOTUSDT','LINKUSDT','XMRUSDT','COMPUSDT');
//     $counter_btc = 1;
//     $counter_usdt = 1;
//     $db = $this->mongo_db->customQuery();
//     foreach ($priority_btc_coins_array as $value) {
//         echo  '<pre>Coin name => '.$value;
//         echo  '<pre>Priority => '.$counter_btc;
//         echo '---------------------------';
//         $db->coins->updateOne(['symbol'=>$value,'user_id'=>'global'],['$set'=>['coin_priority'=>$counter_btc]]);
//         $db->coins_kraken->updateOne(['symbol'=>$value,'user_id'=>'global'],['$set'=>['coin_priority'=>$counter_btc]]);
//         $counter_btc = $counter_btc + 1;
        
//     }
//     foreach ($priority_usdt_coins_array as $value_usdt) {
//         echo  '<pre>Coin name => '.$value_usdt;
//         echo  '<pre>Priority => '.$counter_usdt;
//         echo '---------------------------';
//         $db->coins->updateOne(['symbol'=>$value_usdt,'user_id'=>'global'],['$set'=>['coin_priority'=>$counter_usdt]]);
//         $db->coins_kraken->updateOne(['symbol'=>$value_usdt,'user_id'=>'global'],['$set'=>['coin_priority'=>$counter_usdt]]);
//         $counter_usdt = $counter_usdt + 1;
        
//     }
// }
} //En of controller
