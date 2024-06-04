<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function __construct() {

        parent::__construct();

        //load main template
        $this->stencil->layout('admin_layout');

        //load required slices
        $this->stencil->slice('admin_header_script');
        $this->stencil->slice('admin_header');
        $this->stencil->slice('admin_left_sidebar');
        $this->stencil->slice('admin_footer_script');

        // Load Library Goes here
        // $this->load->library('binance_api');

        // Load Modal
        $this->load->model('admin/mod_login');
        $this->load->model('admin/mod_coins');
        $this->load->model('admin/mod_settings');
        $this->load->model('admin/mod_users');

    }

    // public function index() {
    //     //Login Check
    //     $this->mod_login->verify_is_admin_login();
    //     $id = $this->session->userdata('admin_id');
    //     //Fetching users Record
    //     $settings_arr = $this->mod_settings->get_settings_by_id($id);
    //     $data['settings_arr'] = $settings_arr;
    //     $data['admin_id'] = $id;

    //     //stencil is our templating library. Simply call view via it
    //     $this->stencil->paint('admin/settings/add_settings', $data);

    // } //End index

    // public function add_settings() {
    //     //Login Check
    //     $this->mod_login->verify_is_admin_login();
    //     $id = $this->session->userdata('admin_id');
    //     $data['admin_id'] = $id;
    //     //stencil is our templating library. Simply call view via it
    //     $this->stencil->paint('admin/settings/add_settings', $data);

    // } //End add_settings

    // public function function_timelog() {
    //     //Login Check
    //     $this->mod_login->verify_is_admin_login();
    //     $id = $this->session->userdata('admin_id');
    //     $data['admin_id'] = $id;

    //     $fucntionTimeLog = $this->mod_settings->fucntionTimeLog();
    //     $data['timelogarr'] = $fucntionTimeLog;
    //     //stencil is our templating library. Simply call view via it
    //     $this->stencil->paint('admin/settings/track_execution_time', $data);

    // } //End function_timelog

    // public function key_settings() {

    //     //Login Check
    //     $this->mod_login->verify_is_admin_login();
    //     $getMasterApikeyCredentials = $this->mod_settings->getMasterApikeyCredentials();
    //     $data['apiKeyArray'] = $getMasterApikeyCredentials;

    //     //stencil is our templating library. Simply call view via it
    //     $this->stencil->paint('admin/settings/key_settings', $data);

    // } //End key_settings

    // public function updateKeySettingsProcess() {

    //     //Login Check
    //     $this->mod_login->verify_is_admin_login();

    //     $api_key_tr = $this->input->post('api_key_tr');
    //     $api_secret_tr = $this->input->post('api_secret_tr');
    //     /*
    //     $api_key_tr     =  '0012323rmrpH8YDuTAujVZkSrUEr2NmtOkQTVMXRRg86d4InQBIQxiIlOKWTJ2uSPeT6TQb00';
    //     $api_secret_tr  =  '00123233Jb7YjdfpOqZqMaKc9QbpOs6tjYrXWMekvlcWvs9QNu32n3jbOgVAGkM8ulY5LkgQ00';

    //     $checkBinanceApi = $this->binance_api->check_master_api($api_key_tr,$api_secret_tr);
    //     $checkBinanceApi1 = $this->binance_api->checkExchangeInfo($api_key_tr,$api_secret_tr);
    //     //$Info = $checkBinanceApi->accountStatus();
    //     echo "<pre>";  print_r($checkBinanceApi1);   exit;
    //      */
    //     $json_array = array();
    //     if ($api_key_tr == '') {
    //         $json_array['success'] = true;
    //         $json_array['message'] = 'Master Api key field cannot be empty .';
    //         echo json_encode($json_array);
    //         exit;
    //     }
    //     if ($api_secret_tr == '') {
    //         $json_array['success'] = false;
    //         $json_array['message'] = 'Master Api Secret field cannot be empty .';
    //         echo json_encode($json_array);
    //         exit;
    //     }
    //     $updateKeySettings = $this->mod_settings->updateKeySettingsProcess($this->input->post());

    //     if ($updateKeySettings) {
    //         $json_array['success'] = true;
    //         $json_array['message'] = 'Api key credentials successfully updated.';
    //         echo json_encode($json_array);
    //         exit;
    //     } //$updateKeySettings
    // } //end updateKeySettingsProcess

    // public function add_settings_process() {

    //     //Login Check
    //     $this->mod_login->verify_is_admin_login();

    //     //Adding add_user
    //     $user_id = $this->mod_settings->add_settings($this->input->post());

    //     if ($user_id) {

    //         $this->session->set_flashdata('ok_message', 'Settings added successfully.');
    //         redirect(base_url() . 'admin/settings/');

    //     } else {

    //         $this->session->set_flashdata('err_message', 'Settings cannot added. Something went wrong, please try again.');
    //         redirect(base_url() . 'admin/settings/');

    //     } //end if

    // } //end add_settings_process

    // public function edit_settings($setting_id) {
    //     //Login Check
    //     $this->mod_login->verify_is_admin_login();

    //     //Fetching user Record
    //     $settings_arr = $this->mod_settings->get_setings($setting_id);
    //     $data['settings_arr'] = $settings_arr;
    //     $data['setting_id'] = $setting_id;

    //     $this->stencil->paint('admin/settings/edit_settings', $data);

    // } //End edit_settings

    // public function edit_settings_process() {

    //     //Login Check
    //     $this->mod_login->verify_is_admin_login();

    //     //edit_user
    //     $sett_id = $this->mod_settings->edit_settings($this->input->post());

    //     if ($sett_id) {

    //         redirect(base_url() . 'admin/settings');

    //     } else {

    //         redirect(base_url() . 'admin/settings/');

    //     } //end if

    // } //end edit_settings_process

    // public function delete_settings($setting_id) {

    //     //Login Check
    //     $this->mod_login->verify_is_admin_login();

    //     //Delete Settings
    //     $delete_id = $this->mod_settings->delete_settings($setting_id);

    //     if ($delete_id) {

    //         $this->session->set_flashdata('ok_message', 'User deleted successfully.');
    //         redirect(base_url() . 'admin/settings');

    //     } else {

    //         $this->session->set_flashdata('err_message', 'User can not deleted. Something went wrong, please try again.');
    //         redirect(base_url() . 'admin/settings');

    //     } //end if

    // } //end delete_settings

    // public function trigger_setting() {
    //     $this->mod_login->verify_is_admin_login();
    //     $order_mode = $this->session->userdata('global_mode');
    //     $data['admin_id'] = $this->session->userdata('admin_id');
    //     $coins = $this->mod_coins->get_all_coins();
    //     $data['coins'] = $coins;

    //     $triggers_arr = array();
    //     if (count($coins) > 0) {
    //         foreach ($coins as $row) {
    //             $this->mongo_db->where(array('coins' => $row['symbol'], 'order_mode' => $order_mode));
    //             $res = $this->mongo_db->get('setting_triggers_collections');
    //             $result = iterator_to_array($res);

    //             foreach ($result as $row_1) {
    //                 $triggers_arr[$row_1['triggers_type']][$row_1['coins']] = (array) $row_1;
    //             }
    //         }
    //     }

    //     $data['triggers_arr'] = $triggers_arr;
    //     $data['order_mode'] = $order_mode;

    //     $this->stencil->paint('admin/settings/trigger_setting', $data);
    // }

    // public function get_coin_trigger_setting() {
    //     $trigger_type = $this->input->post('trigger_type');
    //     //$trigger_type = 'trigger_2';
    //     $res_arr = $this->mod_settings->get_coin_trigger_setting($trigger_type);
    //     $html = '';
    //     if (count($res_arr) > 0) {
    //         $res_arr_1 = $res_arr;
    //         foreach ($res_arr_1 as $coin_1 => $row_1) {
    //             $arr_1 = $row_1['live'];
    //             if ($arr_1 == null) {
    //                 $arr_1 = $row_1['test'];
    //             }

    //             $triggers_type_2 = '';
    //             if ($arr_1) {
    //                 $triggers_type_2 = $arr_1['triggers_type'];
    //             }

    //         }

    //         if ($triggers_type_2 != 'trigger_1' && $triggers_type_2 != 'barrier_trigger' && $triggers_type_2 != '') {
    //             $html .= '<table class="table table-bordered tbl_cls">
    //             <thead>
    //               <tr>
    //                 <th>Coin</th>
    //                 <th>Live Buy</th>
    //                 <th>Live Sell</th>
    //                 <th>Live Stop Loss</th>
    //                 <th>Test Buy</th>
    //                 <th>Test Sell</th>
    //                 <th>Test Stop Loss</th>
    //                 <th>Action</th>
    //               </tr>
    //             </thead>
    //             <tbody>';

    //             foreach ($res_arr as $coin => $row) {
    //                 $html .= '<tr>';
    //                 $live_arr = $row['live'];
    //                 $html .= '<th>' . $coin . '</th>';
    //                 $test_id = '';
    //                 $live_id = '';

    //                 if ($live_arr == null) {
    //                     $html .= ' <td><input type="text" value="" class="form-control " name="" id="l_buy' . $coin . '"> </td>
	// 				<td><input value="" type="" class="form-control " name="" id="l_sell' . $coin . '"></td>
	// 				<td><input value="" type="" class="form-control " name="" id="l_s_l' . $coin . '"></td>';
    //                 } else {
    //                     $live_id = $live_arr['_id'];
    //                     $html .= ' <td><input type="text" value="' . $live_arr['buy_price'] . '" class="form-control " name="" id="l_buy' . $coin . '"> </td>
	// 				<td><input value="' . $live_arr['sell_price'] . '" type="" class="form-control " name="" id="l_sell' . $coin . '"></td>
	// 				<td><input value="' . $live_arr['stop_loss'] . '" type="" class="form-control " name="" id="l_s_l' . $coin . '"></td>';
    //                 }

    //                 $test_arr = $row['test'];
    //                 if ($test_arr == null) {
    //                     $html .= ' <td><input type="text" value="" class="form-control " name="" id="t_buy' . $coin . '"> </td>
	// 				<td><input value="" type="" class="form-control " name="" id="t_sell' . $coin . '"></td>
	// 				<td><input value="" type="" class="form-control " name="" id="t_s_l' . $coin . '"></td>';
    //                 } else {
    //                     $test_id = $test_arr['_id'];
    //                     $html .= ' <td><input type="text" value="' . $test_arr['buy_price'] . '" class="form-control " name=""  id="t_buy' . $coin . '"> </td>
	// 				<td><input value="' . $test_arr['sell_price'] . '" type="" class="form-control " name="" id="t_sell' . $coin . '"></td>
	// 				<td><input value="' . $test_arr['stop_loss'] . '" type="" class="form-control " name="" id="t_s_l' . $coin . '"></td>';
    //                 }
    //                 $html .= '<td><button type="button" test_att="' . $test_id . '" class="btn btn-success upd_cls" value="" live_att="' . $live_id . '" coin_att="' . $coin . '"> Update</button></td>';
    //                 $html .= '</tr>';
    //             }

    //         } else if ($triggers_type_2 == 'barrier_trigger') {
    //             //if not barrier_trigger ||  trigger_1

    //             $html .= '<table class="table table-bordered tbl_cls">
    //             <thead>
    //               <tr>
    //                 <th>Coin</th>
    //                 <th>Live Sell</th>
    //                 <th>Live Stop Loss</th>
    //                 <th>Live Quantity</th>
    //                 <th>Test Sell</th>
    //                 <th>Test Stop Loss</th>
    //                 <th>Test Quantity</th>
    //                 <th>Action</th>
    //               </tr>
    //             </thead>
    //             <tbody>';

    //             foreach ($res_arr as $coin => $row) {
    //                 $html .= '<tr>';
    //                 $live_arr = $row['live'];
    //                 $html .= '<th>' . $coin . '</th>';
    //                 $test_id = '';
    //                 $live_id = '';
    //                 if ($live_arr == null) {
    //                     $html .= ' <td><input type="number" value="" class="form-control " name="b_sell" id="b_sell"> </td>
	// 				<td><input value="" type="number" class="form-control " name="b_s_l" id="b_s_l"></td>
	// 				<td><input value="" type="number" class="form-control " name="b_quantity" id="b_quantity" type="number"></td>';
    //                 } else {
    //                     $live_id = $live_arr['_id'];
    //                     $html .= ' <td><input type="number" value="' . $live_arr['buy_price_percent_barrier_trigger'] . '" class="form-control " name="" id="b_sell' . $live_id . '"> </td>
	// 				<td><input value="' . $live_arr['stop_loss_price_percent_barrier_trigger'] . '" type="" class="form-control " name="" id="b_s_l' . $live_id . '"></td>
	// 				<td><input value="' . $live_arr['barrier_trigger_quantity'] . '" type="" class="form-control " name="" id="b_quantity' . $live_id . '"></td>';
    //                 }

    //                 $test_arr = $row['test'];
    //                 if ($test_arr == null) {
    //                     $html .= ' <td><input type="number" value="" class="form-control " name="b_sell" id="b_sell_t"> </td>
	// 				<td><input value="" type="number" class="form-control " name="b_s_t" id="b_s_t"></td>
	// 				<td><input value="" type="number" class="form-control " name="b_quantity_t" id="b_quantity_t" type="number"></td>';
    //                 } else {
    //                     $test_id = $test_arr['_id'];
    //                     $html .= ' <td><input type="number" value="' . $live_arr['buy_price_percent_barrier_trigger'] . '" class="form-control " name="" id="b_sell_t' . $live_id . '"> </td>
	// 				<td><input value="' . $live_arr['stop_loss_price_percent_barrier_trigger'] . '" type="" class="form-control " name="" id="b_s_t' . $live_id . '"></td>
	// 				<td><input value="' . $live_arr['barrier_trigger_quantity'] . '" type="" class="form-control " name="" id="b_quantity_t' . $live_id . '"></td>';
    //                 }
    //                 $html .= '<td><button type="button" test_att="' . $test_id . '" class="btn btn-success upd_cls" value="" live_att="' . $live_id . '"> Update</button></td>';
    //                 $html .= '</tr>';
    //             }
    //         }

    //     }
    //     echo $html;
    //     exit();

    // } //End of get_coin_trigger_setting

    // public function update_coin_trigger_setting_barrier_trigger() {
    //     $data = $this->input->post();
    //     $live_id = $this->input->post('live_id');
    //     $this->mongo_db->where(array('_id' => $live_id));
    //     $this->mongo_db->set($data);
    //     echo $this->mongo_db->update('setting_triggers_collections', $data);

    //     exit();

    // }

    // public function update_coin_trigger_setting() {

    //     $live_id = $this->input->post('live_id');
    //     $test_id = $this->input->post('test_id');
    //     $trigger_type = $this->input->post('trigger_type');
    //     $coin = $this->input->post('coin');

    //     $order_mode_live = 'live';
    //     $buy_price_l = $this->input->post('l_buy');
    //     $sell_price_l = $this->input->post('l_sell');
    //     $stop_loss_l = $this->input->post('l_s_l');

    //     $insert_arr_live = array('coins' => $coin, 'buy_price' => $buy_price_l, 'sell_price' => $sell_price_l, 'stop_loss' => $stop_loss_l, 'admin_id' => '1', 'triggers_type' => $trigger_type, 'buy_part_1_price_percent' => '', 'buy_part_2_price_percent' => '', 'buy_part_3_price_percent' => '', 'sell_part_1_price_percent' => '', 'sell_part_2_price_percent' => '', 'sell_part_3_price_percent' => '', 'Initail_trail_stop_trigger_1' => '', 'order_mode' => $order_mode_live);

    //     if ($live_id == '') {
    //         $this->mongo_db->insert('setting_triggers_collections', $insert_arr_live);
    //     } else {

    //         $this->mongo_db->where(array('_id' => $live_id));
    //         $this->mongo_db->set($insert_arr_live);
    //         echo $this->mongo_db->update('setting_triggers_collections');
    //     }

    //     $order_mode_test = 'test';
    //     $buy_price_t = $this->input->post('t_buy');
    //     $sell_price_t = $this->input->post('t_sell');
    //     $stop_loss_t = $this->input->post('t_s_l');

    //     $insert_arr_test = array('coins' => $coin, 'buy_price' => $buy_price_t, 'sell_price' => $sell_price_t, 'stop_loss' => $stop_loss_t, 'admin_id' => '1', 'triggers_type' => $trigger_type, 'buy_part_1_price_percent' => '', 'buy_part_2_price_percent' => '', 'buy_part_3_price_percent' => '', 'sell_part_1_price_percent' => '', 'sell_part_2_price_percent' => '', 'sell_part_3_price_percent' => '', 'Initail_trail_stop_trigger_1' => '', 'order_mode' => $order_mode_test);

    //     if ($test_id == '') {
    //         $this->mongo_db->insert('setting_triggers_collections', $insert_arr_test);
    //     } else {

    //         $this->mongo_db->where(array('_id' => $test_id));
    //         $this->mongo_db->set($insert_arr_test);
    //         echo $this->mongo_db->update('setting_triggers_collections');
    //     }

    // } //End of

    // public function delete_orders() {

    //     //$this->mod_login->verify_is_admin_login();
    //     $coins = $this->mod_coins->get_all_coins();
    //     $users = $this->mod_users->get_all_users();

    //     $data['coins'] = $coins;
    //     $data['users'] = $users;
    //     $this->stencil->paint('admin/settings/delete_orders', $data);
    // } //End of delete_orders

    // public function delete_orders_ajax() {
    //     $coin = $this->input->post('coin');
    //     $user_id = $this->input->post('user');
    //     $user_id = "$user_id";
    //     $triggers_type = $this->input->post('triggers_type');
    //     $order_mode = $this->input->post('order_mode');

    //     $res = $con->buy_orders->deleteMany(array('trigger_type' => $triggers_type, 'order_mode' => $order_mode, 'admin_id' => $user_id, 'symbol' => $coin));
    //     echo '<pre>';
    //     echo print_r($res);
    //     exit();
    // } //End of delete_orders_ajax

    // public function get_coin_setting_ajax() {

    //     $coin = $this->input->post('coin');
    //     $triggers_type = $this->input->post('triggers_type');
    //     $order_mode = $this->input->post('order_mode');
    //     $this->mongo_db->where(array('coins' => $coin, 'triggers_type' => $triggers_type, 'order_mode' => $order_mode));
    //     $res = $this->mongo_db->get('setting_triggers_collections');
    //     $result = iterator_to_array($res);

    //     $data_array = array();

    //     if (count($result) > 0) {

    //         foreach ($result as $data) {

    //             if ($data['triggers_type'] == 'barrier_trigger') {
    //                 $data_array['_id'] = (string) $data['_id'];
    //                 $data_array['coins'] = $data['coins'];
    //                 $data_array['buy_price_percent_barrier_trigger'] = $data['buy_price_percent_barrier_trigger'];
    //                 $data_array['stop_loss_price_percent_barrier_trigger'] = $data['stop_loss_price_percent_barrier_trigger'];

    //                 $data_array['barrier_trigger_quantity'] = $data['barrier_trigger_quantity'];
    //                 $data_array['admin_id'] = $data['admin_id'];
    //                 $data_array['triggers_type'] = $data['triggers_type'];
    //             } else {
    //                 $data_array['_id'] = (string) $data['_id'];
    //                 $data_array['coins'] = $data['coins'];
    //                 $data_array['buy_price'] = $data['buy_price'];
    //                 $data_array['sell_price'] = $data['sell_price'];

    //                 $data_array['stop_loss'] = $data['stop_loss'];
    //                 $data_array['admin_id'] = $data['admin_id'];
    //                 $data_array['triggers_type'] = $data['triggers_type'];

    //                 $data_array['buy_part_1_price_percent'] = $data['buy_part_1_price_percent'];
    //                 $data_array['buy_part_2_price_percent'] = $data['buy_part_2_price_percent'];
    //                 $data_array['buy_part_3_price_percent'] = $data['buy_part_3_price_percent'];

    //                 $data_array['sell_part_1_price_percent'] = $data['sell_part_1_price_percent'];
    //                 $data_array['sell_part_2_price_percent'] = $data['sell_part_2_price_percent'];
    //                 $data_array['sell_part_3_price_percent'] = $data['sell_part_3_price_percent'];
    //                 $data_array['Initail_trail_stop_trigger_1'] = $data['Initail_trail_stop_trigger_1'];
    //             }

    //         }

    //     }

    //     echo json_encode($data_array);
    //     exit();
    // } //End of get_coin_setting_ajax

    // public function add_trigger_settings_process() {

    //     $this->mod_login->verify_is_admin_login();

    //     $admin_id = $this->input->post('admin_id');
    //     $coins = $this->input->post('coins');

    //     $triggers_type = $this->input->post('triggers_type');
    //     $trigger_setting_id = $this->input->post('trigger_setting_id');
    //     $order_mode = $this->input->post('order_mode');

    //     if (($triggers_type == 'trigger_2') || ($triggers_type == 'box_trigger_3') || ($triggers_type == 'rg_15')) {

    //         $buy_price = $this->input->post('buy_price');
    //         $sell_price = $this->input->post('sell_price');
    //         $stop_loss = $this->input->post('stop_loss');

    //         $insert_arr = array('coins' => $coins, 'buy_price' => $buy_price, 'sell_price' => $sell_price, 'stop_loss' => $stop_loss, 'admin_id' => $admin_id, 'triggers_type' => $triggers_type, 'buy_part_1_price_percent' => '', 'buy_part_2_price_percent' => '', 'buy_part_3_price_percent' => '', 'sell_part_1_price_percent' => '', 'sell_part_2_price_percent' => '', 'sell_part_3_price_percent' => '', 'Initail_trail_stop_trigger_1' => '', 'order_mode' => $order_mode);

    //     }

    //     if ($triggers_type == 'trigger_1') {

    //         $buy_part_1_price_percent = $this->input->post('buy_part_1_price_percent');
    //         $buy_part_2_price_percent = $this->input->post('buy_part_2_price_percent');
    //         $buy_part_3_price_percent = $this->input->post('buy_part_3_price_percent');

    //         $sell_part_1_price_percent = $this->input->post('sell_part_1_price_percent');
    //         $sell_part_2_price_percent = $this->input->post('sell_part_2_price_percent');
    //         $sell_part_3_price_percent = $this->input->post('sell_part_3_price_percent');

    //         $Initail_trail_stop_trigger_1 = $this->input->post('Initail_trail_stop_trigger_1');

    //         $insert_arr = array('coins' => $coins, 'buy_price' => '', 'sell_price' => '', 'stop_loss' => '', 'admin_id' => $admin_id, 'triggers_type' => $triggers_type, 'buy_part_1_price_percent' => $buy_part_1_price_percent, 'buy_part_2_price_percent' => $buy_part_2_price_percent, 'buy_part_3_price_percent' => $buy_part_3_price_percent, 'sell_part_1_price_percent' => $sell_part_1_price_percent, 'sell_part_2_price_percent' => $sell_part_2_price_percent, 'sell_part_3_price_percent' => $sell_part_3_price_percent, 'Initail_trail_stop_trigger_1' => $Initail_trail_stop_trigger_1, 'order_mode' => $order_mode);

    //     }

    //     if ($triggers_type == 'barrier_trigger') {

    //         $barrier_trigger_quantity = $this->input->post('barrier_trigger_quantity');
    //         $stop_loss_price_percent_barrier_trigger = $this->input->post('stop_loss_price_percent_barrier_trigger');
    //         $buy_price_percent_barrier_trigger = $this->input->post('buy_price_percent_barrier_trigger');

    //         $insert_arr = array('coins' => $coins, 'admin_id' => $admin_id, 'triggers_type' => $triggers_type, 'order_mode' => $order_mode, 'buy_price_percent_barrier_trigger' => $buy_price_percent_barrier_trigger, 'barrier_trigger_quantity' => $barrier_trigger_quantity, 'stop_loss_price_percent_barrier_trigger' => $stop_loss_price_percent_barrier_trigger);

    //     }

    //     if ($triggers_type == '') {
    //         $this->session->set_flashdata('err_message', ' please select trigger type.');
    //         redirect(base_url() . 'admin/settings/trigger_setting');
    //     } else {

    //         if ($trigger_setting_id == '') {
    //             $res = $this->mongo_db->insert('setting_triggers_collections', $insert_arr);
    //         } else {
    //             $this->mongo_db->where(array('_id' => $trigger_setting_id));
    //             $this->mongo_db->set($insert_arr);
    //             $res = $this->mongo_db->update('setting_triggers_collections');

    //         }

    //         if ($res) {

    //             $this->session->set_flashdata('ok_message', 'Trigger setting Add successfully.');
    //             redirect(base_url() . 'admin/settings/trigger_setting');

    //         } else {

    //             $this->session->set_flashdata('err_message', ' Something went wrong, please try again.');
    //             redirect(base_url() . 'admin/settings/trigger_setting');

    //         } //end if
    //     }

    // } //End of add_trigger_settings_process

    // public function trigger_listining() {
    //     $this->mod_login->verify_is_admin_login();
    //     $res = $this->mongo_db->get('setting_triggers_collections');

    //     $data['trigger_list_arr'] = iterator_to_array($res);
    //     $this->stencil->paint('admin/settings/trigger_setting_listining', $data);

    // } //End of trigger_listining

    // public function delete_trigger_setting($id) {

    //     // $obj_id = $this->mongo_db->mongoId($id);

    //     $this->mongo_db->where(array('_id' => $id));

    //     $res = $this->mongo_db->delete('setting_triggers_collections');

    //     if ($res) {
    //         $this->session->set_flashdata('ok_message', 'Trigger setting Delete successfully.');
    //         redirect(base_url() . 'admin/settings/trigger_listining');
    //     } else {
    //         $this->session->set_flashdata('err_message', ' Something went wrong, please try again.');
    //         redirect(base_url() . 'admin/settings/trigger_listining');
    //     }
    // } //End of     delete_trigger_setting

    public function enable_google_auth() {
        $this->mod_login->verify_is_admin_login();
        $data['request'] = 1;
        $data['admin_id'] = $this->session->userdata('admin_id');
        $this->stencil->paint('admin/settings/google_auth_enable', $data);
    }

    public function get_the_secret_code() {
        require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
        $ga = new GoogleAuthenticator();
        $secret = $ga->createSecret();

        $respone = '<div class="row">
						<div class="col-md-12">
							<div class="image reddd"  style="float:right;">
			                    <img src="' . IMG . 'g_a/n16.png" width="100%">
			                    <span class="text reddtext" style="font-weight: bold;">' . $secret . '</span>
			                </div>
						</div>
					</div>
                  <div class="control-group col-md-12">
                    <label class="control control-checkbox">
                       <span> I have Written The Code in safe Place Continue now </span>
                        <input type="checkbox" name = "secret" id="check_secret" value="' . $secret . '" />
                        <div class="control_indicator"></div>
                    </label>
                  </div>';
        echo $respone;
        exit;
    }
    public function add_google_auth() {
        $this->mod_login->verify_is_admin_login();

        $is_enable = $this->input->post('auth');
        $admin_id = $this->input->post('admin_id');
        $secret = $this->input->post('secret');
        /*require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
        $ga = new GoogleAuthenticator();*/
        //$secret = $ga->createSecret();

        $this->mod_settings->update_user_auth($admin_id, $is_enable, $secret);
        if ($is_enable == 'yes') {
            redirect(base_url() . 'admin/settings/enable_google_auth2');
        } else {
            redirect(base_url() . 'admin/settings/enable_google_auth');
        }

    }

    public function enable_google_auth2() {
        $this->mod_login->verify_is_admin_login();
        $secret = $this->session->userdata('google_auth_code');
        $email = $this->session->userdata('email_address');
        require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
        $ga = new GoogleAuthenticator();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($email, $secret, 'app.digiebot.com');
        $data['qrCodeUrl'] = $qrCodeUrl;
        $data['request'] = 2;
        $data['admin_id'] = $this->session->userdata('admin_id');
        $this->stencil->paint('admin/settings/google_auth_enable', $data);
    }

    public function verify_code() {
        $this->mod_login->verify_is_admin_login();
        $code = $this->input->post('code');
        $secret = $this->session->userdata('google_auth_code');
        require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
        $ga = new GoogleAuthenticator();
        $checkResult = $ga->verifyCode($secret, $code, 2); // 2 = 2*30sec clock tolerance
        if ($checkResult) {
            $_SESSION['googleCode'] = $code;
            redirect(base_url() . 'admin/settings/enable_google_auth');
        } else {
            echo "Failed";
            exit;
        }
    }

    public function password_change() {
        $data['admin_id'] = $this->session->userdata('admin_id');
        $this->stencil->paint('admin/settings/change_password', $data);

    }

    public function change_password() {
        $this->mod_login->verify_is_admin_login();

        //echo "<pre>";   print_r($this->input->post()); exit;

        if ($_POST['code']) {
            $code = $this->input->post('code');
            $secret = $this->session->userdata('google_auth_code');
            require_once 'GoogleAuthenticator/GoogleAuthenticator.php';
            $ga = new GoogleAuthenticator();
            $checkResult = $ga->verifyCode($secret, $code, 2); // 2 = 2*30sec clock tolerance
            if ($checkResult) {

                $data1 = $this->mod_settings->change_password($this->input->post());
            } else {
                $this->session->set_flashdata('err_message', 'Some Issue Occured in auth.');
                redirect(base_url() . 'admin/settings/password_change');
            }
        } else {
            $data1 = $this->mod_settings->change_password($this->input->post());
        }

        if ($data1) {
            $this->session->set_flashdata('ok_message', 'Password Changed Successfully.');
            redirect(base_url() . 'admin/settings/password_change');
        } else {
            $this->session->set_flashdata('err_message', 'Some Issue Occured.');
            redirect(base_url() . 'admin/settings/password_change');
        }
    }

    // public function auto_sell_enable() {

    //     $this->mod_settings->auto_sell_enable($this->input->post());
    //     echo "Good Luck";
    //     exit;

    // }

    public function user_leftmenu_setting() {
        $this->mod_login->verify_is_admin_login();
        $leftmenu = $this->input->post('leftmenu');
        $setting = $this->mod_settings->user_leftmenu_setting($leftmenu);
        return true;
    }

    // public function update_candle() {
    //     $this->mod_login->verify_is_admin_login();
    //     if ($this->session->userdata('user_role') != 1) {
    //         redirect(base_url() . 'forbidden');
    //     }

    //     $coins = $this->mod_coins->get_all_coins();
    //     $data['coins'] = $coins;
    //     $this->stencil->paint('admin/settings/update_candle', $data);
    // }

    // public function get_candle_info() {
    //     $data_post = $this->input->post();
    //     $coin = $data_post['coin'];
    //     $time = $data_post['time'];
    //     date_default_timezone_set("Asia/Karachi");
    //     $new_time = date("Y-m-d G", strtotime($time));
    //     $start_date = $new_time . ":00:00";
    //     $end_date = $new_time . ":59:59";
    //     $data_array = $this->mod_settings->get_candle_info($coin, $start_date, $end_date);
    //     $rejection = $this->calculate_rejection_candle($coin, $time);
    //     $response = '<form method="post" action="' . SURL . 'admin/settings/update_candle_process">';
    //     foreach ($data_array as $key => $data_arr) {
    //         if (!empty($data_arr)) {
    //             $response .= ' <div class="row">
	// 							 <div class="col-md-12">
	// 		                      <div class="form-group col-md-12">
	// 		                        <label class="control-label" for="hour">Coin</label>
	// 		                        <input type="hidden" name="candle_id" value="' . $data_arr['_id'] . '">
	// 		                        <input class="form-control" name="coin" value="' . $data_arr['coin'] . '" />
	// 		                      </div>
	// 		                    </div>
	// 		                    <div class="col-md-12">
	// 		                      <div class="form-group col-md-12">
	// 		                        <label class="control-label" for="hour">High Value</label>
	// 		                        <input class="form-control" name="high" value="' . num($data_arr['high']) . '" />
	// 		                      </div>
	// 		                    </div>
	// 		                    <div class="col-md-12">
	// 		                      <div class="form-group col-md-12">
	// 		                        <label class="control-label" for="hour">Low Value</label>
	// 		                        <input class="form-control" name="low" value="' . num($data_arr['low']) . '" />
	// 		                      </div>
	// 		                    </div>
	// 		                    <div class="col-md-12">
	// 		                      <div class="form-group col-md-12">
	// 		                        <label class="control-label" for="hour">Open Value</label>
	// 		                        <input class="form-control" name="open" value="' . num($data_arr['open']) . '" />
	// 		                      </div>
	// 		                    </div>
	// 		                    <div class="col-md-12">
	// 		                      <div class="form-group col-md-12">
	// 		                        <label class="control-label" for="hour">Close Value</label>
	// 		                        <input class="form-control" name="close" value="' . num($data_arr['close']) . '" />
	// 		                      </div>
	// 		                    </div>
	// 		                    <div class="col-md-12">
	// 		                      <div class="form-group col-md-12">
	// 		                        <label class="control-label" for="hour">Volume</label>
	// 		                        <input class="form-control" name="volume" value="' . $data_arr['volume'] . '" />
	// 		                      </div>
	// 		                    </div>
	// 		                     <div class="col-md-12">
	// 		                      <div class="form-group col-md-12">
	// 		                        <label class="control-label" for="hour">Rejection</label>
	// 		                        <input class="form-control" name="rejection" value="' . $rejection . '" />
	// 		                      </div>
	// 		                    </div>
	// 		                    <div class="col-md-12">
	// 		                      <div class="form-group col-md-12">
	// 		                        <label class="control-label" for="hour">Candle Type</label>
	// 		                       <select class = "form-control" name="candle_type">';
    //             if ($data_arr['candle_type'] == 'demand') {
    //                 $response .= '<option value = "">Select Candle Type</option>
	// 										<option value = "demand" selected>Demand</option>
	// 			                        	<option value = "supply">Supply</option>
	// 			                        	<option value = "diverse_demand">Diverse Demand</option>
	// 			                        	<option value = "diverse_supply">Diverse Supply</option>';
    //             } elseif ($data_arr['candle_type'] == 'supply') {
    //                 $response .= '<option value = "">Select Candle Type</option>
	// 									<option value = "demand">Demand</option>
	// 			                        <option value = "supply" selected>Supply</option>
	// 			                        <option value = "diverse_demand">Diverse Demand</option>
	// 			                        <option value = "diverse_supply">Diverse Supply</option>';
    //             } elseif ($data_arr['candle_type'] == 'diverse_demand') {
    //                 $response .= '<option value = "">Select Candle Type</option>
	// 									<option value = "demand">Demand</option>
	// 			                        <option value = "supply">Supply</option>
	// 			                        <option value = "diverse_demand" selected>Diverse Demand</option>
	// 			                        <option value = "diverse_supply">Diverse Supply</option>';
    //             } elseif ($data_arr['candle_type'] == 'diverse_supply') {
    //                 $response .= '<option value = "">Select Candle Type</option>
	// 									<option value = "demand">Demand</option>
	// 			                        <option value = "supply">Supply</option>
	// 			                        <option value = "diverse_demand">Diverse Demand</option>
	// 			                        <option value = "diverse_supply" selected>Diverse Supply</option>';
    //             } else {
    //                 $response .= '<option value = "">Select Candle Type</option>
	// 		                        	<option value = "demand">Demand</option>
	// 			                        <option value = "supply">Supply</option>
	// 			                        <option value = "diverse_demand">Diverse Demand</option>
	// 			                        <option value = "diverse_supply">Diverse Supply</option>';
    //             }
    //             $response .= '</select>
	// 		                      </div>
	// 		                    </div>
	// 		                    <div class="col-md-12">
	// 		                      <div class="form-group col-md-12">
	// 		                        <label class="control-label" for="hour">Candle Status</label>
	// 		                        <select class = "form-control" name="candel_status">';
    //             if ($data_arr['candel_status'] == '') {
    //                 $response .= '<option value = "">Select Candle Status</option>
	// 		                        	<option value = "Continuation_up">Continuation up</option>
	// 			                        <option value = "Current_up">Current up</option>
	// 			                        <option value = "Continuation_Down">Continuation Down</option>
	// 			                        <option value = "Current_Down">Current Down</option>';
    //                 # code...
    //             } elseif ($data_arr['candel_status'] == 'Continuation_up') {
    //                 $response .= '<option value = "">Select Candle Status</option>
	// 									<option value = "Continuation_up" selected>Continuation up</option>
	// 		                        <option value = "Current_up">Current up</option>
	// 		                        <option value = "Continuation_Down">Continuation Down</option>
	// 		                        <option value = "Current_Down">Current Down</option>';
    //             } elseif ($data_arr['candel_status'] == 'Current_up') {
    //                 $response .= '<option value = "">Select Candle Status</option>
	// 									<option value = "Continuation_up">Continuation up</option>
	// 		                        <option value = "Current_up" selected>Current up</option>
	// 		                        <option value = "Continuation_Down">Continuation Down</option>
	// 		                        <option value = "Current_Down">Current Down</option>';
    //             } elseif ($data_arr['candel_status'] == 'Continuation_Down') {
    //                 $response .= '<option value = "">Select Candle Status</option>
	// 									<option value = "Continuation_up">Continuation up</option>
	// 		                        <option value = "Current_up">Current up</option>
	// 		                        <option value = "Continuation_Down" selected>Continuation Down</option>
	// 		                        <option value = "Current_Down">Current Down</option>';
    //             } elseif ($data_arr['candel_status'] == 'Current_Down') {
    //                 $response .= '<option value = "">Select Candle Status</option>
	// 									<option value = "Continuation_up">Continuation up</option>
	// 		                        <option value = "Current_up">Current up</option>
	// 		                        <option value = "Continuation_Down">Continuation Down</option>
	// 		                        <option value = "Current_Down" selected>Current Down</option>';
    //             }
    //             $response .= '</select>
	// 		                      </div>
	// 		                    </div>
	// 		                     <div class="col-md-12">
	// 		                      <div class="form-group col-md-12">
	// 		                        <label class="control-label" for="hour">Ask Volume</label>
	// 		                        <input class="form-control" name="ask_volume" value="' . $data_arr['ask_volume'] . '" />
	// 		                      </div>
	// 		                    </div>
	// 		                     <div class="col-md-12">
	// 		                      <div class="form-group col-md-12">
	// 		                        <label class="control-label" for="hour">Bid Volume</label>
	// 		                        <input class="form-control" name="bid_volume" value="' . $data_arr['bid_volume'] . '" />
	// 		                      </div>
	// 		                    </div>
	// 		                     <div class="col-md-12">
	// 		                      <div class="form-group col-md-12">
	// 		                        <label class="control-label" for="hour">Total Volume</label>
	// 		                        <input class="form-control" name="total_volume" value="' . $data_arr['total_volume'] . '" />
	// 		                      </div>
	// 		                    </div>
	// 			          </div>
	// 			          <hr class="separator" />

	// 			          <div class="form-actions">
	// 			            <button type="submit" id="clint_info_btn" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
	// 			            <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancel</button>
	// 			          </div>';
    //         }
    //     }
    //     $response .= '</form>';
    //     echo $response;
    //     exit;
    // }
    // public function candle_test() {
    //     $coins = $this->mod_coins->get_all_coins();
    //     $data['coins'] = $coins;
    //     $this->stencil->paint('admin/settings/candle_test', $data);
    // }

    // public function testing() {
    //     $coin = 'NCASHBTC';
    //     $time_second = '2018-04-05 00:00:00';

    //     for ($i = 0; $i <= 100; $i++) {
    //         $time = date("Y-m-d H:00:00", strtotime('+' . $i . 'hour', strtotime($time_second)));
    //         echo "{";
    //         echo "<br>";
    //         echo "Timestamp" . $time;
    //         echo "<br>";
    //         $this->test_rejection($coin, $time);
    //         echo "}";
    //         echo "<br>";
    //     }
    // }

    // public function test_rejection($coin, $time) {
    //     $new_time = date("Y-m-d H", strtotime($time));
    //     $start_date = $new_time . ":00:00";
    //     $end_date = $new_time . ":59:59";
    //     $data_array = $this->mod_settings->get_candle_info($coin, $start_date, $end_date);

    //     $open = $data_array[0]['open'];
    //     $close = $data_array[0]['close'];
    //     $high = $data_array[0]['high'];
    //     $low = $data_array[0]['low'];
    //     $bid_volume = $data_array[0]['bid_volume'];
    //     $ask_volume = $data_array[0]['ask_volume'];
    //     echo "Total Volume: ";
    //     echo $total_volume = $ask_volume + $bid_volume;
    //     echo "<br>";
    //     $rejected = 0;
    //     $rejection = '';

    //     $last_25_per_volume = $this->calculate_base_candel($coin, $start_date, $end_date);
    //     echo "Previous Candles Calculated Volume: ";
    //     echo $last_25_per_volume;
    //     echo "<br>";
    //     if ($total_volume > $last_25_per_volume) {
    //         if ($open < $close) {
    //             $candle_type = 'Demand';

    //             //Top Demand Rejection
    //             $top_percentage = ((($high - $close) / ($close - $open)) * 100);
    //             if ($top_percentage >= 40) {
    //                 $rejected = 1;
    //             } else {
    //                 $rejected = 0;
    //             }
    //             //Bottom Demand Rejection
    //             $bottom_percentage = ((($open - $low) / ($close - $open)) * 100);
    //             if ($bottom_percentage >= 40) {
    //                 $rejected = 1;
    //             } else {
    //                 if ($rejected == 0) {
    //                     $rejected = 0;
    //                 }
    //             }

    //             if (($top_percentage > $bottom_percentage) && $rejected == 1) {
    //                 $rejection = 'top_demand_rejection';
    //             } elseif (($bottom_percentage > $top_percentage) && $rejected == 1) {
    //                 $rejection = "bottom_demand_rejection";
    //             }

    //         }
    //         if ($open > $close) {
    //             //Top Supply Rejection
    //             $top_percentage = ((($high - $open) / ($open - $close)) * 100);
    //             if ($top_percentage >= 40) {
    //                 $rejected = 1;
    //             } else {
    //                 $rejected = 0;
    //             }
    //             //Bottom Supply Rejection
    //             $bottom_percentage = ((($close - $low) / ($open - $close)) * 100);
    //             if ($bottom_percentage >= 40) {
    //                 $rejected = 1;
    //             } else {
    //                 if ($rejected == 0) {
    //                     $rejected = 0;
    //                 }
    //             }

    //             if (($top_percentage > $bottom_percentage) && $rejected == 1) {
    //                 $rejection = 'top_supply_rejection';
    //             } elseif (($bottom_percentage > $top_percentage) && $rejected == 1) {
    //                 $rejection = "bottom_supply_rejection";
    //             }
    //         }
    //         if ($open == $close) {
    //             $candle_type = 'Normal';
    //         }
    //     }

    //     echo '<b>' . $rejection . '</b>';
    //     echo "<br>";
    // }
    // public function calculate_rejection_candle($coin, $time) {
    //     date_default_timezone_set("Asia/Karachi");
    //     $new_time = date("Y-m-d G", strtotime($time));
    //     $start_date = $new_time . ":00:00";
    //     $end_date = $new_time . ":59:59";

    //     $data_array = $this->mod_settings->get_candle_info($coin, $start_date, $end_date);

    //     $open = $data_array[0]['open'];
    //     $close = $data_array[0]['close'];
    //     $high = $data_array[0]['high'];
    //     $low = $data_array[0]['low'];
    //     $bid_volume = $data_array[0]['bid_volume'];
    //     $ask_volume = $data_array[0]['ask_volume'];
    //     $total_volume = $ask_volume + $bid_volume;
    //     $rejected = 0;
    //     $rejection = '';

    //     $last_25_per_volume = $this->calculate_base_candel($coin, $start_date, $end_date);

    //     /*echo $last_25_per_volume;
    //     echo "<br>";
    //     echo $total_volume;
    //      */

    //     if ($total_volume > $last_25_per_volume) {
    //         if ($open < $close) {
    //             $candle_type = 'Demand';

    //             //Top Demand Rejection
    //             $top_percentage = ((($high - $close) / ($close - $open)) * 100);
    //             if ($top_percentage >= 40) {
    //                 $rejected = 1;
    //             } else {
    //                 $rejected = 0;
    //             }
    //             //Bottom Demand Rejection
    //             $bottom_percentage = ((($open - $low) / ($close - $open)) * 100);
    //             if ($bottom_percentage >= 40) {
    //                 $rejected = 1;
    //             } else {
    //                 if ($rejected == 0) {
    //                     $rejected = 0;
    //                 }
    //             }

    //             if (($top_percentage > $bottom_percentage) && $rejected == 1) {
    //                 $rejection = 'top_demand_rejection';
    //             } elseif (($bottom_percentage > $top_percentage) && $rejected == 1) {
    //                 $rejection = "bottom_demand_rejection";
    //             }

    //         }
    //         if ($open > $close) {
    //             //Top Supply Rejection
    //             $top_percentage = ((($high - $open) / ($open - $close)) * 100);
    //             if ($top_percentage >= 40) {
    //                 $rejected = 1;
    //             } else {
    //                 $rejected = 0;
    //             }
    //             //Bottom Supply Rejection
    //             $bottom_percentage = ((($close - $low) / ($open - $close)) * 100);
    //             if ($bottom_percentage >= 40) {
    //                 $rejected = 1;
    //             } else {
    //                 if ($rejected == 0) {
    //                     $rejected = 0;
    //                 }
    //             }

    //             if (($top_percentage > $bottom_percentage) && $rejected == 1) {
    //                 $rejection = 'top_supply_rejection';
    //             } elseif (($bottom_percentage > $top_percentage) && $rejected == 1) {
    //                 $rejection = "bottom_supply_rejection";
    //             }
    //         }
    //         if ($open == $close) {
    //             $candle_type = 'Normal';
    //         }
    //     }

    //     return $rejection;
    // }

    // public function update_candle_process() {
    //     $data_post = $this->input->post();
    //     $datapost = $this->mod_settings->update_candle_process($data_post);
    //     if ($datapost) {
    //         $this->session->set_flashdata('ok_message', 'Candle updated Successfully.');
    //         redirect(base_url() . 'admin/settings/update_candle');
    //     } else {
    //         $this->session->set_flashdata('err_message', 'Some Issue Occured.');
    //         redirect(base_url() . 'admin/settings/update_candle');
    //     }
    // } //End of update_candle_process

    // public function triggers_global_setting() {
        // $this->mod_login->verify_is_admin_login();
        // if ($this->session->userdata('user_role') != 1) {
        //     redirect(base_url() . 'forbidden');
        // }

        // $coins = $this->mod_coins->get_all_coins();
        // $data['coins'] = $coins;

        // if ($this->input->post()) {

        //     $data = $this->input->post();
        //     $trigger_level = $data['trigger_level'];
        //     $numeric_level = preg_replace('/[^0-9]/', '', $trigger_level);
            
        //     $data['numeric_level'] = (float)$numeric_level;

        //     $cancel_trade = $this->input->post('cancel_trade');
        //     if ($cancel_trade) {

        //     } else {
        //         $data['cancel_trade'] = 'not';
        //     }

        //     //%%%%%%%%%%%%%%%%%%%% Barrier Percentiel trigger buy part %%%%%%%%%%

        //     if (!$this->input->post('enable_buy_barrier_percentile')) {
        //         $data['enable_buy_barrier_percentile'] = 'not';
        //     }

        //     if (!$this->input->post('enable_test_buy_barrier_percentile')) {
        //         $data['enable_test_buy_barrier_percentile'] = 'not';
        //     }

            // if (!$this->input->post('enable_sell_barrier_percentile')) {
            //     $data['enable_sell_barrier_percentile'] = 'not';
            // }

            // if (!$this->input->post('enable_percentile_trigger_stop_loss')) {
            //     $data['enable_percentile_trigger_stop_loss'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_buy_black_wall_apply')) {
            //     $data['barrier_percentile_trigger_buy_black_wall_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_is_previous_blue_candel')) {
            //     $data['barrier_percentile_is_previous_blue_candel'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_bottom_demond_rejection')) {
            //     $data['barrier_percentile_bottom_demond_rejection'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_bottom_supply_rejection')) {
            //     $data['barrier_percentile_bottom_supply_rejection'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_buy_virtual_barrier_apply')) {
            //     $data['barrier_percentile_trigger_buy_virtual_barrier_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_sell_virtual_barrier_for_buy_apply')) {
            //     $data['barrier_percentile_trigger_sell_virtual_barrier_for_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_stop_loss_virtual_barrier_bid_apply')) {
            //     $data['barrier_percentile_trigger_stop_loss_virtual_barrier_bid_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_buy_seven_level_pressure_apply')) {
            //     $data['barrier_percentile_trigger_buy_seven_level_pressure_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_buy_last_200_contracts_buy_vs_sell_apply')) {
            //     $data['barrier_percentile_trigger_buy_last_200_contracts_buy_vs_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_buy_last_200_contracts_time_apply')) {
            //     $data['barrier_percentile_trigger_buy_last_200_contracts_time_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_buy_last_qty_contracts_buyer_vs_seller_apply')) {
            //     $data['barrier_percentile_trigger_buy_last_qty_contracts_buyer_vs_seller_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_buy_last_qty_contracts_time_apply')) {
            //     $data['barrier_percentile_trigger_buy_last_qty_contracts_time_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_5_minute_rolling_candel_apply')) {
            //     $data['barrier_percentile_trigger_5_minute_rolling_candel_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_15_minute_rolling_candel_apply')) {
            //     $data['barrier_percentile_trigger_15_minute_rolling_candel_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_buyers_buy_apply')) {
            //     $data['barrier_percentile_trigger_buyers_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_sellers_buy_apply')) {
            //     $data['barrier_percentile_trigger_sellers_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_15_minute_last_time_ago_apply')) {
            //     $data['barrier_percentile_trigger_15_minute_last_time_ago_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_15_minute_last_time_ago_sell_apply')) {
            //     $data['barrier_percentile_trigger_15_minute_last_time_ago_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_sell_apply')) {
            //     $data['barrier_percentile_trigger_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_sell_rule_sell_apply')) {
            //     $data['barrier_percentile_trigger_sell_rule_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_bid_apply')) {
            //     $data['barrier_percentile_trigger_bid_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_bid_sell_apply')) {
            //     $data['barrier_percentile_trigger_bid_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_ask_apply')) {
            //     $data['barrier_percentile_trigger_ask_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_ask_contracts_apply')) {
            //     $data['barrier_percentile_trigger_ask_contracts_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_ask_contracts_sell_apply')) {
            //     $data['barrier_percentile_trigger_ask_contracts_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_bid_contracts_apply')) {
            //     $data['barrier_percentile_trigger_bid_contracts_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_bid_contracts_sell_apply')) {
            //     $data['barrier_percentile_trigger_bid_contracts_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_ask_contracts')) {
            //     $data['barrier_percentile_trigger_ask_contracts'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_buy_apply')) {
            //     $data['barrier_percentile_trigger_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_buy_sell_apply')) {
            //     $data['barrier_percentile_trigger_buy_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_ask_sell_apply')) {
            //     $data['barrier_percentile_trigger_ask_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_bid_contracts_sell_apply')) {
            //     $data['barrier_percentile_trigger_bid_contracts_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('box_trigger_15_minute_rolling_candel_apply')) {
            //     $data['box_trigger_15_minute_rolling_candel_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_riskpershare_buy_apply')) {
            //     $data['percentile_trigger_riskpershare_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_RL_buy_apply')) {
            //     $data['percentile_trigger_RL_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_meta_trading_buy_apply')) {
            //     $data['percentile_trigger_meta_trading_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_supply_buy_apply')) {
            //     $data['percentile_trigger_supply_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_market_trend_buy_apply')) {
            //     $data['percentile_trigger_market_trend_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_demand_buy_apply')) {
            //     $data['percentile_trigger_demand_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_sell_buy_apply')) {
            //     $data['percentile_trigger_sell_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_buy_trend_buy_apply')) {
            //     $data['percentile_trigger_buy_trend_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_caption_score_buy_apply')) {
            //     $data['percentile_trigger_caption_score_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_caption_option_buy_apply')) {
            //     $data['percentile_trigger_caption_option_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_caption_option_sell_apply')) {
            //     $data['percentile_trigger_caption_option_sell_apply'] = 'not';
            // }


            // if (!$this->input->post('percentile_trigger_long_term_intension_buy_apply')) {
            //     $data['percentile_trigger_long_term_intension_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('percentile_trigger_previous_state_buy_apply')) {
            //     $data['percentile_trigger_previous_state_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('percentile_trigger_range_buy_apply')) {
            //     $data['percentile_trigger_range_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('percentile_trigger_deep_value_1_buy_apply')) {
            //     $data['percentile_trigger_deep_value_1_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('candle_24_buy_apply')) {
            //     $data['candle_24_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('candle_move_color_buy_apply')) {
            //     $data['candle_move_color_buy_apply'] = 'not';
            // }


            // //********************** */
            // if (!$this->input->post('volume_increasing_buy_apply')) {
            //     $data['volume_increasing_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('lh_tw_contracts_buy_apply')) {
            //     $data['lh_tw_contracts_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('lh_lw_contracts_buy_apply')) {
            //     $data['lh_lw_contracts_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('lh_lwb_contracts_buy_apply')) {
            //     $data['lh_lwb_contracts_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('five_min_btc_change_buy_apply')) {
            //     $data['five_min_btc_change_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('lh_lw_contracts_percentile_buy_apply')) {
            //     $data['lh_lw_contracts_percentile_buy_apply'] = 'not';
            // }



            // if (!$this->input->post('top_wick_aggregate_buy_apply')) {
            //     $data['top_wick_aggregate_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('deep_price_percentage_buy_apply')) {
            //     $data['deep_price_percentage_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('cancel_order_hours_range_buy_apply')) {
            //     $data['cancel_order_hours_range_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('external_indicator_buy_apply')) {
            //     $data['external_indicator_buy_apply'] = 'not';
            // }

            


            // if (!$this->input->post('total_volume_percentile_buy_apply')) {
            //     $data['total_volume_percentile_buy_apply'] = 'not';
            // }

            
            


            // if (!$this->input->post('lh_tw_contracts_percentile_buy_apply')) {
            //     $data['lh_tw_contracts_percentile_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('lh_lwb_contracts_percentile_buy_apply')) {
            //     $data['lh_lwb_contracts_percentile_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('lh_bc_qty_buy_apply')) {
            //     $data['lh_bc_qty_buy_apply'] = 'not';
            // }


        

            // if (!$this->input->post('lh_tv_percentile_buy_apply')) {
            //     $data['lh_tv_percentile_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('curr_bc_percentile_buy_apply')) {
            //     $data['curr_bc_percentile_buy_apply'] = 'not';
            // }

         

            // //************************ */


            

            // if (!$this->input->post('candle_1_buy_apply')) {
            //     $data['candle_1_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('big_contractor_top_percentage_buy_apply')) {
            //     $data['big_contractor_top_percentage_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('last_1h_candle_compare_volume_value_buy_apply')) {
            //     $data['last_1h_candle_compare_volume_value_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('last_1h_candle_big_contractor_buyers_value_buy_apply')) {
            //     $data['last_1h_candle_big_contractor_buyers_value_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('last_1h_candle_big_contractor_sellers_value_buy_apply')) {
            //     $data['last_1h_candle_big_contractor_sellers_value_buy_apply'] = 'not';
            // }


            // if (!$this->input->post('percentile_trigger_caption_score_sell_apply')) {
            //     $data['percentile_trigger_caption_score_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_buy_operator_sell_apply')) {
            //     $data['percentile_trigger_buy_operator_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_sell_trend_sell_apply')) {
            //     $data['percentile_trigger_sell_trend_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_demand_sell_apply')) {
            //     $data['percentile_trigger_demand_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_supply_sell_apply')) {
            //     $data['percentile_trigger_supply_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_market_trend_operator_sell_apply')) {
            //     $data['percentile_trigger_market_trend_operator_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_meta_trading_sell_apply')) {
            //     $data['percentile_trigger_meta_trading_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_riskpershare_sell_apply')) {
            //     $data['percentile_trigger_riskpershare_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('percentile_trigger_RL_sell_apply')) {
            //     $data['percentile_trigger_RL_sell_apply'] = 'not';
            // }



            // if (!$this->input->post('percentile_trigger_long_term_intension_sell_apply')) {
            //     $data['percentile_trigger_long_term_intension_sell_apply'] = 'not';
            // }

            

            // //%%%%%%%%%%%%%%%%  --Percentile stop loss part -- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

            // if (!$this->input->post('barrier_percentile_stop_loss_black_wall_apply')) {
            //     $data['barrier_percentile_stop_loss_black_wall_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_sell_black_wall_apply')) {
            //     $data['barrier_percentile_trigger_sell_black_wall_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_stop_loss_virtual_barrier_apply')) {
            //     $data['barrier_percentile_trigger_stop_loss_virtual_barrier_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_stop_loss_seven_level_pressure_apply')) {
            //     $data['barrier_percentile_trigger_stop_loss_seven_level_pressure_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_stop_loss_last_200_contracts_buy_vs_sell_apply')) {
            //     $data['barrier_percentile_trigger_stop_loss_last_200_contracts_buy_vs_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_stop_loss_last_200_contracts_time_apply')) {
            //     $data['barrier_percentile_trigger_stop_loss_last_200_contracts_time_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_stop_loss_last_qty_contracts_buyer_vs_seller_apply')) {
            //     $data['barrier_percentile_trigger_stop_loss_last_qty_contracts_buyer_vs_seller_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_stop_loss_last_qty_contracts_time_apply')) {
            //     $data['barrier_percentile_trigger_stop_loss_last_qty_contracts_time_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_stop_loss_5_minute_rolling_candel_sell_apply')) {
            //     $data['barrier_percentile_stop_loss_5_minute_rolling_candel_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_stop_loss_15_minute_rolling_candel_sell_apply')) {
            //     $data['barrier_percentile_stop_loss_15_minute_rolling_candel_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_buyers_stop_loss_apply')) {
            //     $data['barrier_percentile_trigger_buyers_stop_loss_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_sellers_stop_loss_apply')) {
            //     $data['barrier_percentile_trigger_sellers_stop_loss_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_buyers1_minute_stop_loss_apply')) {
            //     $data['barrier_percentile_trigger_buyers1_minute_stop_loss_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_sellers_1_minute_stop_loss_apply')) {
            //     $data['barrier_percentile_trigger_sellers_1_minute_stop_loss_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_caption_option_buy_apply')) {
            //     $data['market_trend_caption_option_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('enable_buy_market_trends_trigger')) {
            //     $data['enable_buy_market_trends_trigger'] = 'not';
            // }

            // if (!$this->input->post('enable_sell_market_trends_trigger')) {
            //     $data['enable_sell_market_trends_trigger'] = 'not';
            // }

            // if (!$this->input->post('market_trend_caption_option_sell_apply')) {
            //     $data['market_trend_caption_option_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_caption_score_buy_apply')) {
            //     $data['market_trend_caption_score_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_caption_score_sell_apply')) {
            //     $data['market_trend_caption_score_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_buy_trend_buy_apply')) {
            //     $data['market_trend_buy_trend_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_buy_operator_sell_apply')) {
            //     $data['market_trend_buy_operator_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_sell_buy_apply')) {
            //     $data['market_trend_sell_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_sell_trend_sell_apply')) {
            //     $data['market_trend_sell_trend_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_demand_buy_apply')) {
            //     $data['market_trend_demand_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_demand_sell_apply')) {
            //     $data['market_trend_demand_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_supply_buy_apply')) {
            //     $data['market_trend_supply_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_supply_sell_apply')) {
            //     $data['market_trend_supply_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_meta_trading_buy_apply')) {
            //     $data['market_trend_meta_trading_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_market_trend_operator_sell_apply')) {
            //     $data['market_trend_market_trend_operator_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_meta_trading_sell_apply')) {
            //     $data['market_trend_meta_trading_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_riskpershare_buy_apply')) {
            //     $data['market_trend_riskpershare_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_riskpershare_sell_apply')) {
            //     $data['market_trend_riskpershare_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_black_wall_buy_apply')) {
            //     $data['market_trend_black_wall_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_black_wall_sell_apply')) {
            //     $data['market_trend_black_wall_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_seven_level_pressure_buy_apply')) {
            //     $data['market_trend_seven_level_pressure_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_seven_level_pressure_sell_apply')) {
            //     $data['market_trend_seven_level_pressure_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_RL_sell_apply')) {
            //     $data['market_trend_RL_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_market_trend_buy_apply')) {
            //     $data['market_trend_market_trend_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('market_trend_market_trend_buy_apply')) {
            //     $data['market_trend_market_trend_buy_apply'] = 'not';
            // }

            // //%%%%%%%%%%%%%% Barrier percentile trigger Sell Part %%%%%%%%%%%%%

            // if (!$this->input->post('barrier_percentile_trigger_sell_virtual_barrier_apply')) {
            //     $data['barrier_percentile_trigger_sell_virtual_barrier_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_buy_virtual_barrier_for_sell_apply')) {
            //     $data['barrier_percentile_trigger_buy_virtual_barrier_for_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_sell_seven_level_pressure_apply')) {
            //     $data['barrier_percentile_trigger_sell_seven_level_pressure_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_buy_last_200_contracts_sell_vs_sell_apply')) {
            //     $data['barrier_percentile_trigger_buy_last_200_contracts_sell_vs_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_sell_last_200_contracts_time_apply')) {
            //     $data['barrier_percentile_trigger_sell_last_200_contracts_time_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_sell_last_200_contracts_buy_vs_sell_apply')) {
            //     $data['barrier_percentile_trigger_sell_last_200_contracts_buy_vs_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_sell_last_qty_contracts_buyer_vs_seller_apply')) {
            //     $data['barrier_percentile_trigger_sell_last_qty_contracts_buyer_vs_seller_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_sell_last_qty_contracts_time_apply')) {
            //     $data['barrier_percentile_trigger_sell_last_qty_contracts_time_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_5_minute_rolling_candel_sell_apply')) {
            //     $data['barrier_percentile_trigger_5_minute_rolling_candel_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_15_minute_rolling_candel_sell_apply')) {
            //     $data['barrier_percentile_trigger_15_minute_rolling_candel_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_buyers_sell_apply')) {
            //     $data['barrier_percentile_trigger_buyers_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('barrier_percentile_trigger_sellers_sell_apply')) {
            //     $data['barrier_percentile_trigger_sellers_sell_apply'] = 'not';
            // }

            // //%%%%%%%%%%%%%%%%%%%%%5 Box Trigger Part %%%%%%%%%%%%%%%%%%%%

            // /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
            // if (!$this->input->post('box_trigger_black_wall_apply')) {
            //     $data['box_trigger_black_wall_apply'] = 'not';
            // }

            // if (!$this->input->post('box_trigger_virtual_barrier_apply')) {
            //     $data['box_trigger_virtual_barrier_apply'] = 'not';
            // }

            // if (!$this->input->post('box_trigger_seven_level_pressure_apply')) {
            //     $data['box_trigger_seven_level_pressure_apply'] = 'not';
            // }

            // if (!$this->input->post('box_trigger_buyer_vs_seller_rolling_candel_apply')) {
            //     $data['box_trigger_buyer_vs_seller_rolling_candel_apply'] = 'not';
            // }

            // if (!$this->input->post('last_200_contracts_buy_vs_sell_box_trigger_apply')) {
            //     $data['last_200_contracts_buy_vs_sell_box_trigger_apply'] = 'not';
            // }

            // if (!$this->input->post('last_200_contracts_time_box_trigger_apply')) {
            //     $data['last_200_contracts_time_box_trigger_apply'] = 'not';
            // }

            // if (!$this->input->post('last_qty_contracts_time_box_trigger_apply')) {
            //     $data['last_qty_contracts_time_box_trigger_apply'] = 'not';
            // }

            // if (!$this->input->post('last_qty_contracts_buyer_vs_seller_box_trigger_apply')) {
            //     $data['last_qty_contracts_buyer_vs_seller_box_trigger_apply'] = 'not';
            // }

            // if (!$this->input->post('box_trigger_15_minute_rolling_candel_apply')) {
            //     $data['box_trigger_15_minute_rolling_candel_apply'] = 'not';
            // }

            // if (!$this->input->post('box_trigger_buyers_buy_apply')) {
            //     $data['box_trigger_buyers_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('box_trigger_sellers_buy_apply')) {
            //     $data['box_trigger_sellers_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('box_trigger_15_minute_last_time_ago_apply')) {
            //     $data['box_trigger_15_minute_last_time_ago_apply'] = 'not';
            // }

            // if (!$this->input->post('box_trigger_ask_apply')) {
            //     $data['box_trigger_ask_apply'] = 'not';
            // }

            // if (!$this->input->post('box_trigger_bid_apply')) {
            //     $data['box_trigger_bid_apply'] = 'not';
            // }

            // if (!$this->input->post('box_trigger_buy_apply')) {
            //     $data['box_trigger_buy_apply'] = 'not';
            // }

            // if (!$this->input->post('box_trigger_sell_apply')) {
            //     $data['box_trigger_sell_apply'] = 'not';
            // }

            // if (!$this->input->post('box_trigger_ask_contracts_apply')) {
            //     $data['box_trigger_ask_contracts_apply'] = 'not';
            // }

            // if (!$this->input->post('box_trigger_bid_contracts_apply')) {
            //     $data['box_trigger_bid_contracts_apply'] = 'not';
            // }

            // if (!$this->input->post('box_trigger_5_minute_rolling_candel_apply')) {
            //     $data['box_trigger_5_minute_rolling_candel_apply'] = 'not';
            // }

            // /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

            // //%%%%%%%%%%%%%%%%% End of Box trigger Part %%%%%%%%%%%%%%%%%

            // $bottom_demand_rejection = $this->input->post('bottom_demand_rejection');
            // if ($bottom_demand_rejection) {
            // } else {
            //     $data['bottom_demand_rejection'] = 'not';
            // }

            // $bottom_supply_rejection = $this->input->post('bottom_supply_rejection');
            // if ($bottom_supply_rejection) {
            // } else {
            //     $data['bottom_supply_rejection'] = 'not';
            // }

            // $check_high_open = $this->input->post('check_high_open');
            // if ($check_high_open) {
            // } else {
            //     $data['check_high_open'] = 'not';
            // }

            // $is_previous_blue_candle = $this->input->post('is_previous_blue_candle');
            // if ($is_previous_blue_candle) {
            // } else {
            //     $data['is_previous_blue_candle'] = 'not';
            // }

            // $is_closest_black_bottom_wall = $this->input->post('is_closest_black_bottom_wall');
            // if ($is_closest_black_bottom_wall) {
            // } else {
            //     $data['is_closest_black_bottom_wall'] = 'not';
            // }

            // $is_closest_yellow_bottom_wall = $this->input->post('is_closest_yellow_bottom_wall');
            // if ($is_closest_yellow_bottom_wall) {
            // } else {
            //     $data['is_closest_yellow_bottom_wall'] = 'not';
            // }

            // $is_big_ask_percent = $this->input->post('is_big_ask_percent');
            // if ($is_big_ask_percent) {
            // } else {
            //     $data['is_big_ask_percent'] = 'not';
            // }

            // $is_big_bid_percent = $this->input->post('is_big_bid_percent');
            // if ($is_big_bid_percent) {
            // } else {
            //     $data['is_big_bid_percent'] = 'not';
            // }

            // $is_big_buyers = $this->input->post('is_big_buyers');
            // if ($is_big_buyers) {
            // } else {
            //     $data['is_big_buyers'] = 'not';
            // }

            // $is_big_trade = $this->input->post('is_big_trade');
            // if ($is_big_trade) {
            // } else {
            //     $data['is_big_trade'] = 'not';
            // }

            // $is_up_pressure = $this->input->post('is_up_pressure');
            // if ($is_up_pressure) {
            // } else {
            //     $data['is_up_pressure'] = 'not';
            // }

            // $is_endble_trigger_for_sell = $this->input->post('is_endble_trigger_for_sell');
            // if ($is_endble_trigger_for_sell) {
            // } else {
            //     $data['is_endble_trigger_for_sell'] = 'not';
            // }

            // $is_endble_trigger_for_buy = $this->input->post('is_endble_trigger_for_buy');
            // if ($is_endble_trigger_for_buy) {
            // } else {
            //     $data['is_endble_trigger_for_buy'] = 'not';
            // }

            // $is_big_pressure_up = $this->input->post('is_big_pressure_up');
            // if ($is_big_pressure_up) {
            // } else {
            //     $data['is_big_pressure_up'] = 'not';
            // }

            // $is_down_pressure_for_sell = $this->input->post('is_down_pressure_for_sell');
            // if ($is_down_pressure_for_sell) {
            // } else {
            //     $data['is_down_pressure_for_sell'] = 'not';
            // }

            // $is_black_closest_wall_for_sell = $this->input->post('is_black_closest_wall_for_sell');
            // if ($is_black_closest_wall_for_sell) {
            // } else {
            //     $data['is_black_closest_wall_for_sell'] = 'not';
            // }

            // $is_yellow_closest_wall_for_sell = $this->input->post('is_yellow_closest_wall_for_sell');
            // if ($is_yellow_closest_wall_for_sell) {
            // } else {
            //     $data['is_yellow_closest_wall_for_sell'] = 'not';
            // }

            // $seven_level_up_down_rule_for_sell = $this->input->post('seven_level_up_down_rule_for_sell');
            // if ($seven_level_up_down_rule_for_sell) {
            // } else {
            //     $data['seven_level_up_down_rule_for_sell'] = 'not';
            // }

            // $seven_level_up_down_rule_for_buy = $this->input->post('seven_level_up_down_rule_for_buy');
            // if ($seven_level_up_down_rule_for_buy) {
            // } else {
            //     $data['seven_level_up_down_rule_for_buy'] = 'not';
            // }

            /************** Buy Rule Setting************************/

            // for ($rule_num = 1; $rule_num <= 10; $rule_num++) {

                /****************Buy Setting Enable part***/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('buy_last_candle_type' . $rule_num . '_enable')) {
                //     $data['buy_last_candle_type' . $rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('order_status' . $rule_num . '_enable')) {
                //     $data['order_status' . $rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('buyers_vs_sellers_buy' . $rule_num . '_enable')) {
                //     $data['buyers_vs_sellers_buy' . $rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('ask_percentile_' . $rule_num . '_apply_buy')) {
                //     $data['ask_percentile_' . $rule_num . '_apply_buy'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('sell_percentile_' . $rule_num . '_apply_buy')) {
                //     $data['sell_percentile_' . $rule_num . '_apply_buy'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('buy_percentile_' . $rule_num . '_apply_buy')) {
                //     $data['buy_percentile_' . $rule_num . '_apply_buy'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('bid_percentile_' . $rule_num . '_apply_buy')) {
                //     $data['bid_percentile_' . $rule_num . '_apply_buy'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('buyers_vs_sellers_sell' . $rule_num . '_enable')) {
                //     $data['buyers_vs_sellers_sell' . $rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('buy_percentile_' . $rule_num . '_apply_sell')) {
                //     $data['buy_percentile_' . $rule_num . '_apply_sell'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('ask_percentile_' . $rule_num . '_apply_sell')) {
                //     $data['ask_percentile_' . $rule_num . '_apply_sell'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('sell_percentile_' . $rule_num . '_apply_sell')) {
                //     $data['sell_percentile_' . $rule_num . '_apply_sell'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('bid_percentile_' . $rule_num . '_apply_sell')) {
                //     $data['bid_percentile_' . $rule_num . '_apply_sell'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('buy_rejection_candle_type' . $rule_num . '_enable')) {
                //     $data['buy_rejection_candle_type' . $rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('buy_last_200_contracts_buy_vs_sell' . $rule_num . '_enable')) {
                //     $data['buy_last_200_contracts_buy_vs_sell' . $rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('buy_last_200_contracts_time' . $rule_num . '_enable')) {
                //     $data['buy_last_200_contracts_time' . $rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('buy_last_qty_buyers_vs_seller' . $rule_num . '_enable')) {
                //     $data['buy_last_qty_buyers_vs_seller' . $rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('buy_last_qty_time' . $rule_num . '_enable')) {
                //     $data['buy_last_qty_time' . $rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('buy_last_5_minute_candle_buys_vs_seller' . $rule_num . '_enable')) {
                //     $data['buy_last_5_minute_candle_buys_vs_seller' . $rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$this->input->post('buy_score' . $rule_num . '_enable')) {
                //     $data['buy_score' . $rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*******************************************/
                // if (!$this->input->post('buy_status_rule_' . $rule_num . '_enable')) {
                //     $data['buy_status_rule_' . $rule_num . '_enable'] = 'not';
                // }
                /*******************************************/

                /******************************************/

                // if (!$this->input->post('buy_trigger_type_rule_' . $rule_num . '_enable')) {
                //     $data['buy_trigger_type_rule_' . $rule_num . '_enable'] = 'not';
                // }

                /******************************************/

                /***************************************/
                // if (!$this->input->post('buy_check_volume_rule_' . $rule_num)) {
                //     $data['buy_check_volume_rule_' . $rule_num] = 'not';
                // }
                /***************************************/

                /***************************************/
                // if (!$this->input->post('buy_virtual_barrier_rule_' . $rule_num . '_enable')) {
                //     $data['buy_virtual_barrier_rule_' . $rule_num . '_enable'] = 'not';
                // }
                /***************************************/

                /***************************************/
                // if (!$this->input->post('sell_virtural_for_buy_rule_' . $rule_num . '_enable')) {
                //     $data['sell_virtural_for_buy_rule_' . $rule_num . '_enable'] = 'not';
                // }
                /***************************************/

                /*************************************/

                // if (!$this->input->post('done_pressure_rule_' . $rule_num . '_buy_enable')) {
                //     $data['done_pressure_rule_' . $rule_num . '_buy_enable'] = 'not';
                // }
                /**************************************/

                /*************************************/

                // if (!$this->input->post('big_seller_percent_compare_rule_' . $rule_num . '_buy_enable')) {
                //     $data['big_seller_percent_compare_rule_' . $rule_num . '_buy_enable'] = 'not';
                // }
                /*************************************/

                /**********************************/

                // if (!$this->input->post('closest_black_wall_rule_' . $rule_num . '_buy_enable')) {
                //     $data['closest_black_wall_rule_' . $rule_num . '_buy_enable'] = 'not';
                // }
                /**********************************/

                /**********************************/

                // if (!$this->input->post('closest_yellow_wall_rule_' . $rule_num . '_buy_enable')) {
                //     $data['closest_yellow_wall_rule_' . $rule_num . '_buy_enable'] = 'not';
                // }
                /*********************************/

                /*********************************/
                // if (!$this->input->post('seven_level_pressure_rule_' . $rule_num . '_buy_enable')) {
                //     $data['seven_level_pressure_rule_' . $rule_num . '_buy_enable'] = 'not';
                // }
                /********************************/

                /*********************************/

                // if (!$this->input->post('buy_order_level_' . $rule_num . '_enable')) {
                //     $data['buy_order_level_' . $rule_num . '_enable'] = 'not';
                // }
                /********************************/

                // if (!$this->input->post('buy_last_candle_status' . $rule_num . '_enable')) {
                //     $data['buy_last_candle_status' . $rule_num . '_enable'] = 'not';
                // }

                /***************************/

                // if (!$this->input->post('buyer_vs_seller_rule_' . $rule_num . '_buy_enable')) {
                //     $data['buyer_vs_seller_rule_' . $rule_num . '_buy_enable'] = 'not';
                // }
                /***************************/

                /*****************************/
                // if (!$this->input->post('enable_buy_rule_no_' . $rule_num)) {
                //     $data['enable_buy_rule_no_' . $rule_num] = 'not';
                // }
                /*******************************/
                /****************Buy Setting Enable part***/

            // } //End of for loop

            /***************End of Buy Rule Setting*****************/

            /*****************  Sell Rule Setting****************/
            // for ($sell_rule_num = 1; $sell_rule_num <= 10; $sell_rule_num++) {
                /******************************************************/
                // if (!$data['sell_status_rule_' . $sell_rule_num . '_enable']) {
                //     $data['sell_status_rule_' . $sell_rule_num . '_enable'] = 'not';
                // }
                /********************************************************/
                /********************************************************/
                // if (!$data['sell_trigger_type_rule_' . $sell_rule_num . '_enable']) {
                //     $data['sell_trigger_type_rule_' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*********************************************************/

                /********************************************************/
                // if (!$data['sell_order_level_' . $sell_rule_num . '_enable']) {
                //     $data['sell_order_level_' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*********************************************************/

                /*********************************************/
                // if (!$data['sell_check_volume_rule_' . $sell_rule_num]) {
                //     !$data['sell_check_volume_rule_' . $sell_rule_num] = 'not';
                // }
                /*******************************************/

                /*********************************************/
                // if (!$data['buy_virtural_rule_for_sell_' . $sell_rule_num]) {
                //     !$data['buy_virtural_rule_for_sell_' . $sell_rule_num] = 'not';
                // }
                /*******************************************/

                /*********************************************/
                // if (!$data['sell_virtual_barrier_rule_' . $sell_rule_num . '_enable']) {
                //     !$data['sell_virtual_barrier_rule_' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*******************************************/

                /*********************************************/
                // if (!$data['done_pressure_rule_' . $sell_rule_num . '_enable']) {
                //     !$data['done_pressure_rule_' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*******************************************/

                /*********************************************/
                // if (!$data['big_seller_percent_compare_rule_' . $sell_rule_num . '_enable']) {
                //     !$data['big_seller_percent_compare_rule_' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*******************************************/
                /*********************************************/
                // if (!$data['closest_black_wall_rule_' . $sell_rule_num . '_enable']) {
                //     !$data['closest_black_wall_rule_' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*******************************************/

                /*********************************************/
                // if (!$data['closest_yellow_wall_rule_' . $sell_rule_num . '_enable']) {
                //     !$data['closest_yellow_wall_rule_' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*******************************************/
                /*********************************************/
                // if (!$data['seven_level_pressure_rule_' . $sell_rule_num . '_enable']) {
                //     !$data['seven_level_pressure_rule_' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*******************************************/
                /*********************************************/
                // if (!$data['sell_percent_rule_' . $sell_rule_num . '_enable']) {
                //     !$data['sell_percent_rule_' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*******************************************/

                /*********************************************/
                // if (!$data['seller_vs_buyer_rule_' . $sell_rule_num . '_sell_enable']) {
                //     !$data['seller_vs_buyer_rule_' . $sell_rule_num . '_sell_enable'] = 'not';
                // }
                /*******************************************/

                // if (!$data['enable_sell_rule_no_' . $sell_rule_num]) {
                //     !$data['enable_sell_rule_no_' . $sell_rule_num] = 'not';
                // }

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$data['sell_last_candle_type' . $sell_rule_num . '_enable']) {
                //     !$data['sell_last_candle_type' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$data['sell_rejection_candle_type' . $sell_rule_num . '_enable']) {
                //     !$data['sell_rejection_candle_type' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$data['sell_last_200_contracts_buy_vs_sell' . $sell_rule_num . '_enable']) {
                //     !$data['sell_last_200_contracts_buy_vs_sell' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$data['sell_last_200_contracts_time' . $sell_rule_num . '_enable']) {
                //     !$data['sell_last_200_contracts_time' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$data['sell_last_qty_buyers_vs_seller' . $sell_rule_num . '_enable']) {
                //     !$data['sell_last_qty_buyers_vs_seller' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$data['sell_last_candle_status' . $sell_rule_num . '_enable']) {
                //     !$data['sell_last_candle_status' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$data['rule_sorting' . $sell_rule_num . '_enable']) {
                //     !$data['rule_sorting' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$data['sell_last_qty_time' . $sell_rule_num . '_enable']) {
                //     !$data['sell_last_qty_time' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                // if (!$data['sell_last_5_minute_candle_buys_vs_seller' . $sell_rule_num . '_enable']) {
                //     !$data['sell_last_5_minute_candle_buys_vs_seller' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
                // if (!$data['sell_score' . $sell_rule_num . '_enable']) {
                //     !$data['sell_score' . $sell_rule_num . '_enable'] = 'not';
                // }
                /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

            // }
            /****************  End of Sell Rule Setting*********/

            /////////////////////////////////////////////////////////
            /////////////////////////////////////////////////////////

        //     $datapost = $this->mod_settings->save_triggers_global_setting($data);
        //     if ($datapost) {
        //         $this->session->set_flashdata('ok_message', 'Trigger Setting Save successfully.');
        //         redirect(base_url() . 'admin/settings/triggers_global_setting');
        //     } else {

        //         $this->session->set_flashdata('err_message', 'Some Issue Occured.');
        //         redirect(base_url() . 'admin/settings/triggers_global_setting');
        //     }
        // }
        // $this->stencil->paint('admin/settings/trigger_3_setting', $data);
        //echo json_encode($data_array);
    // } //End of trigger_3_setting

    // public function get_global_trigger_setting_ajax() {
    //     $triggers_type = $this->input->post('triggers_type');
    //     $order_mode = $this->input->post('order_mode');
    //     $coin = $this->input->post('coin');
    //     $trigger_level = $this->input->post('trigger_level');  
    //     $where['triggers_type'] = $triggers_type;
    //     $where['order_mode'] = $order_mode;
    //     $where['coin'] = $coin;

    //     if (($triggers_type == 'barrier_percentile_trigger' || $triggers_type == 'box_trigger_3' || $triggers_type == 'market_trend_trigger') && ($trigger_level != '')) {
    //         $where['trigger_level'] = $trigger_level;
    //     }

    //     $this->mongo_db->where($where);
    //     $response_obj = $this->mongo_db->get('trigger_global_setting');
    //     $response_arr = iterator_to_array($response_obj);
    //     $row_data = array();
    //     if (!empty($response_arr)) {
    //         $row_data = (array) $response_arr[0];
    //     }
    //     echo json_encode($row_data);
    //     exit();

    // } //End  get_global_trigger_setting_ajax

    // public function get_market_trend() {
    //     $coin = $this->input->post("coin");
    //     $where['coin'] = $coin;
    //     $this->mongo_db->where($where);
    //     $response_obj = $this->mongo_db->get('market_trending');
    //     $response_arr = iterator_to_array($response_obj);

    //     // echo '<pre>';
    //     // print_r($response_arr);
    //     // exit;

    //     if (!empty($response_arr)) {
    //         $row_data = (array) $response_arr[0];
    //     }
    //     echo json_encode($row_data);
    //     exit();
    // } //End of get_market_trend

    // public function calculate_percentile_for_trading() {
    //     $coin = $this->input->post("coin");
    //     $where['coin'] = $coin;
    //     $this->mongo_db->where($where);
    //     $response_obj = $this->mongo_db->get('coin_meta_hourly_percentile');
    //     $response_arr = iterator_to_array($response_obj);

    //     // echo '<pre>';
    //     // print_r($response_arr);
    //     // exit;

    //     if (!empty($response_arr)) {
    //         $row_data = (array) $response_arr[0];
    //     }
    //     echo json_encode($row_data);
    //     exit();
    // }

    // public function calculate_base_candel($coin_symbol, $start_date, $end_date) {
    //     $total_volume = 0;
    //     $volume_arr = array();
    //     for ($index_date = 1; $index_date <= 168; $index_date++) {
    //         $from_date_for_candel = date("Y-m-d H:00:00", strtotime('-' . $index_date . ' hour', strtotime($start_date)));
    //         $end_date_for_candel = date("Y-m-d H:59:59", strtotime('-' . $index_date . ' hour', strtotime($start_date)));

    //         $ask_volume = $this->get_and_calculate_volume_for_candel($from_date_for_candel, $end_date_for_candel, $coin_symbol);
    //         $volume_arr[] = $ask_volume;
    //     }

    //     sort($volume_arr);
    //     $greater_ask_volume = 0;
    //     echo "Rejection Percentage: ";
    //     echo $rejected_per = $this->get_coin_rejection_value($coin_symbol);
    //     echo "<br>";
    //     echo "Array Index: ";
    //     echo $demand_percentage_index = round((count($volume_arr) / 100) * $rejected_per);
    //     echo "<br>";
    //     $demond_greater_ask_volume = $volume_arr[$demand_percentage_index];

    //     return $demond_greater_ask_volume;
    // } //End of calculate_base_candel

    // public function get_and_calculate_volume_for_candel($from_date, $end_date, $coin_type) {
    //     $connect = $this->mongo_db->customQuery();
    //     $res = $connect->market_trade_hourly_history->find(array(
    //         'coin' => $coin_type,
    //         'hour' => array('$gte' => $from_date, '$lte' => $end_date),
    //     ));
    //     $volume = 0;
    //     $res = iterator_to_array($res);
    //     foreach ($res as $key) {
    //         $volume += (float) $key['volume'];
    //     }
    //     return $volume;
    // } //End of  get_and_calculate_volume_for_candel

    // public function get_coin_rejection_value($symbol) {
    //     $this->db->dbprefix('coins');
    //     $this->db->select('rejection');
    //     $this->db->where('symbol', $symbol);
    //     $get = $this->db->get('coins');
    //     $get_arr = $get->row_array();
    //     return $get_arr['rejection'];
    // } //end get_coin_rejection_value()

    // public function on_off_trading() {
    //     $this->mod_login->verify_is_admin_login();
    //     if ($this->session->userdata('user_role') != 1) {
    //         redirect(base_url() . 'forbidden');
    //     }

    //     if ($this->input->post()) {

    //         $data = $this->input->post();
    //         $custom_on_of_trading = $this->input->post('custom_on_of_trading');
    //         if ($custom_on_of_trading) {
    //         } else {
    //             $data['custom_on_of_trading'] = 'off';
    //         }

    //         $automatic_on_of_trading = $this->input->post('automatic_on_of_trading');
    //         if ($automatic_on_of_trading) {
    //         } else {
    //             $data['automatic_on_of_trading'] = 'off';
    //         }


    //         $buy_on_of_trading = $this->input->post('buy_on_of_trading');
    //         if ($buy_on_of_trading) {
    //         } else {
    //             $data['buy_on_of_trading'] = 'off';
    //         }


    //         $sell_on_of_trading = $this->input->post('sell_on_of_trading');
    //         if ($sell_on_of_trading) {
    //         } else {
    //             $data['sell_on_of_trading'] = 'off';
    //         }

    //         //%%%%%%%%%%%% -- Manual Trading -- %%%%%%%%%%%%%%%%
    //         $buy_on_of_manual_trading = $this->input->post('buy_on_of_manual_trading');
    //         if ($buy_on_of_manual_trading) {
    //         } else {
    //             $data['buy_on_of_manual_trading'] = 'off';
    //         }


    //         $sell_on_of_manual_trading = $this->input->post('sell_on_of_manual_trading');
    //         if ($sell_on_of_manual_trading) {
    //         } else {
    //             $data['sell_on_of_manual_trading'] = 'off';
    //         }
            

    //         //%%%%%%%%%%%%%%% -- Trading  buy test, live -- %%%%%%%%%%%%%%%
    //         $on_of_live_trading = $this->input->post('on_of_live_trading');
    //         if ($on_of_live_trading) {
    //         } else {
    //             $data['on_of_live_trading'] = 'off';
    //         }


    //         $on_of_test_trading = $this->input->post('on_of_test_trading');
    //         if ($on_of_test_trading) {
    //         } else {
    //             $data['on_of_test_trading'] = 'off';
    //         }


    //         $resp = $this->mod_settings->save_on_off_trading($data);

    //         if ($resp) {
    //             $this->session->set_flashdata('ok_message', 'Trading Status Saved Successfully.');
    //             redirect(base_url() . 'admin/settings/on_off_trading');
    //         } else {

    //             $this->session->set_flashdata('err_message', 'Some Issue Occured.');
    //             redirect(base_url() . 'admin/settings/on_off_trading');
    //         }
    //     }

    //     $trading = $this->mod_settings->get_saved_on_off_trading();


    //     $data['trading'] = $trading;
    //     $this->stencil->paint('admin/settings/on_off_trading', $data);
    // } //End of on_off_trading

    // public function get_tradding_status() {

    //     $trading = $this->mod_settings->get_saved_on_off_trading();
    //     $status = 'on';
    //     $message = '';
    //     $off_arr = array();
    //     foreach ($trading as $row) {
    //         if($row['status'] == 'off'){
    //            $type = $row['type'];
    //            $status = 'off';
    //            if($type == 'custom_on_of_trading'){
    //             $message .= 'Trading Off by Admin <br>';
    //            }else if($type == 'automatic_on_of_trading'){
    //             $message .= 'Trading Off by System <br>';
    //            }else if($type == 'buy_on_of_trading'){
    //                 $message .= 'Buy Trading Off by Admin <br>'; 
    //            }else if($type == 'sell_on_of_trading'){
    //                 $message .= 'Sell Trading Off by Admin <br>'; 
    //            }else if($type == 'buy_on_of_manual_trading'){
    //             $message .= 'Manual buy Trading Off by Admin <br>'; 
    //            }else if($type == 'sell_on_of_manual_trading'){
    //             $message .= 'Manual Sell Trading Off by Admin <br>'; 
    //            }else if($type == 'on_of_live_trading'){
    //             $message .= 'Live Trading Off by Admin <br>'; 
    //            }else if($type == 'on_of_test_trading'){
    //             $message .= 'Test Trading Off by Admin <br>'; 
    //            }

    //            //$message .= "Binance will perform a system upgrade at 2019/05/15 3:00 AM (UTC), taking approximately 6-8 hours";
               
    //         }
    //     }//%%%%%% --  End of foreach -- %%%%%%%%%%%

    //     $data['status'] = $status;
    //     $data['message'] = $message;
    //     echo json_encode($data);
    //     exit;
    // } //End of get_tradding_status

    // public function get_barrier_trigger_setting_log() {
    //     error_reporting(E_ALL);
    //     ini_set('display_errors', 1);
    //     $this->mod_login->verify_is_admin_login();

    //     $trigger_changed_log = $this->mod_settings->get_barrier_trigger_setting_changed_log();
    //     $new_arr = array();
    //     if (!empty($trigger_changed_log)) {
    //         foreach ($trigger_changed_log as $log) {
    //             $log = (array) $log;
    //             $user_id = $log['changed_by'];
    //             $user_id_obj = $this->mongo_db->mongoId($user_id);
    //             $response = $this->mod_users->get_user($user_id_obj);
    //             $username = $response['username'];
    //             $log['username'] = $username;
    //             $new_arr[] = $log;
    //         }
    //     }

    //     $data['trigger_changed_log'] = $new_arr;
    //     //stencil is our templating library. Simply call view via it
    //     $this->stencil->paint('admin/settings/barrier_trigger_setting_changed_loged', $data);
    // } //%%%%%%%%% --End of get_barrier_trigger_setting_log -- %%%%%%%%

    // public function show_barrier_setting_log_values() {
    //     $this->stencil->paint('admin/settings/show_barrier_setting_log_values');
    // }

    public function get_trigger_setting_by_id() {
        ;
        $setting_id = $this->input->post('setting_id');
        $this->mongo_db->where(array('_id' => $setting_id));
        $response_obj = $this->mongo_db->get('barrier_trigger_setting_changed_log');
        $response_arr = iterator_to_array($response_obj);
        $row_data = array();
        if (!empty($response_arr)) {
            $row_data = (array) $response_arr[0];
        }
        echo json_encode($row_data);
        exit();

    } //End  get_trigger_setting_by_id

    public function validate_password() {
        $pass = $this->input->post('password');

        $this->mongo_db->where(array('_id' => $this->session->userdata('admin_id'), 'password' => md5($pass)));
        $get_arr = $this->mongo_db->get('users');
        $user = iterator_to_array($get_arr);

        if (count($user) > 0) {
            echo "Password Accepted! Validated";
        } else {
            echo "Sorry! Your Password is not matched! Please Enter the valid Password";
        }
        exit;
    }

    // public function validate_api() {
    //     $api_key = $this->input->post('api_key');
    //     $api_secret = $this->input->post('api_secret');

    //     $testing = $this->binance_api->accountStatusNew($api_key, $api_secret);

    //     if (count($testing) > 0) {
    //         $user_id = $this->mod_settings->add_settings($this->input->post());
    //         echo "Api Credentials Validated";
    //     } else {
    //         echo "Sorry! Api Credentials are not Valid! Please Enter the valid Credentials";
    //     }
    //     exit;
    // }

    public function trading_conversion_on_reserved_ips() {
        $this->mod_login->verify_is_admin_login();
        //Fetching users Record
        $ips_arr = $this->mod_settings->get_convert_trading_on_reserved_ips();
        $data['ips_arr'] = $ips_arr;
        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/settings/reserved_ips_conversion', $data);

    } //End of trading_conversion_on_reserved_ips

    public function save_convert_trading_on_reserved_ips() {
        $this->mod_login->verify_is_admin_login();
        $data = $this->input->post();
        echo $this->mod_settings->save_convert_trading_on_reserved_ips($data);
        exit;
    } // %%%%%%%%%%% -- End of save_convert_trading_on_reserved_ips -- %%%

    public function unassign_convert_trading_on_reserved_ips() {
        $this->mod_login->verify_is_admin_login();
        $Id = $this->input->post('Id');
        echo $this->mod_settings->unassign_convert_trading_on_reserved_ips($Id);
        exit;
    } // %%%%%%%%%%% End of unassign_convert_trading_on_reserved_ips



    // public function on_off_trading_by_trigger_coin() {
    //     $this->mod_login->verify_is_admin_login();

    //     if ($this->session->userdata('user_role') != 1) {
    //         redirect(base_url() . 'forbidden');
    //     }


    //     if($this->input->post()){

    //         $type = $this->input->post('type');
    //         $status = $this->input->post('status');
    //         $coin = $this->input->post('coin');
    //         $trigger = $this->input->post('trigger');

    //         $db = $this->mongo_db->customQuery();   
    //         $filter['trigger'] = $trigger;
    //         $filter['coin'] = $coin;
    //         $filter['type'] = $type;

    //         $insArr['trigger'] = $trigger;
    //         $insArr['coin'] = $coin;
    //         $insArr['type'] = $type;
    //         $insArr['status'] = $status;

    //         $upd['$set'] = $insArr;
    //         $upsert['upsert'] = true;
    //         echo  $db->trading_on_off_collection->updateOne($filter,$upd,$upsert);
    //         exit;
    //     }
    //     $all_coins_arr = $this->triggers_trades->coins_list();

    //     $data['trading'] = $trading;
    //     $data['all_coins_arr'] = $all_coins_arr;
    //     $this->stencil->paint('admin/settings/on_off_coinbase_trading', $data);
    // } //End of on_off_trading_by_trigger_coin


    // public function get_on_off_trading_by_trigger_coin(){
    //     $trigger = $this->input->post('trigger');
    //     $coin = $this->input->post('coin');

    //     $filter['trigger'] = $trigger;
    //     $filter['coin'] = $coin;
    //     $this->mongo_db->where($filter);
    //     $data = $this->mongo_db->get('trading_on_off_collection');
    //     $data = iterator_to_array($data);
    //     echo json_encode($data);
    //     exit;

    // }//End of on_off_trading_by_trigger_coin


    public function make_password(){
        $chars = 6;
        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $password = substr(str_shuffle($data), 0, $chars);

        $ins_arr = array("type" => "system","updated_system_password" => $password, "subtype" => 'superadmin_password');
        $filter_arr['subtype'] = "superadmin_password";
        $db = $this->mongo_db->customQuery();

        $upd['$set'] = $ins_arr;
        $upsert['upsert'] = true;
        $ypd = $db->superadmin_settings->updateOne($filter_arr,$upd,$upsert);
        $name = "make_global_password";
        $duration = "1h";
        $summary = "Cronjob to make Global Password";
        save_cronjob_description($name, $duration, $summary);
        echo "<pre>";
        print_r($ypd);
        exit;
    }

    public function get_password(){
        $this->mod_login->verify_is_admin_login();
        $filter_arr['subtype'] = "superadmin_password";
        $this->mongo_db->where($filter_arr);
        $get = $this->mongo_db->get("superadmin_settings");
        $arr = iterator_to_array($get);
        $passs = $arr[0]['updated_system_password'];
        $data['password'] = $passs;
        $this->stencil->paint('admin/settings/device',$data);
    }

} //%%%%%%%%%%%%%%%%%%%% End of Controller %%%%%%%%%%%%%%%%%%%
