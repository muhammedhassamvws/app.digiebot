<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
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
        $this->load->model('admin/mod_coins');
        $this->load->model('admin/mod_candel');
        $this->load->model('admin/mod_market');
        $this->load->model('admin/mod_barrier_trigger');
        $this->load->model('admin/mod_balance');
        // $this->load->model('admin/Mod_jwt');

    }

    public function metaniance() {
        $this->load->view('maintanence');
    }

    // public function get_session_api() {

    //     header('Content-type: application/json');
    //     header("Access-Control-Allow-Origin: *");
    //     header("Access-Control-Allow-Methods: GET");
    //     header("Access-Control-Allow-Methods: GET, OPTIONS");
    //     header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
    //     $this->mod_login->verify_is_admin_login();
    //     $session_data = $this->session->userdata();

    //     $json_arr = array(
    //         'id' => $session_data['admin_id'],
    //         'user_name' => $session_data['username'],
    //         'email_address' => $session_data['email_address'],
    //     );

    //     $final_arr['status'] = '200';
    //     $final_arr['message'] = $json_arr;

    //     echo json_encode($final_arr);
    //     exit;
    // }

    public function index() {

        //Login Check

        $this->mod_login->verify_is_admin_login();

        //Fetching Market Buy Depth
        $market_buy_depth_data = $this->mod_dashboard->get_market_buy_depth();
        $data['market_buy_depth_arr'] = $market_buy_depth_data['fullarray'];

        $market_value = $market_buy_depth_data['market_value'];
        $data['market_value'] = num($market_value);

        //Fetching Market Sell Depth
        $market_sell_depth_data = $this->mod_dashboard->get_market_sell_depth();

        $data['market_sell_depth_arr'] = $market_sell_depth_data['fullarray'];

        //Fetching Market History
        $market_history_arr = $this->mod_dashboard->get_market_history();
        $data['market_history_arr'] = $market_history_arr;

        $global_symbol = $this->session->userdata('global_symbol');
        $currncy = str_replace('BTC', '', $global_symbol);
        $data['currncy'] = $currncy;

        $data['is_bnb_balance'] = $this->check_user_bnb_balance();

        // if ($_SERVER['REMOTE_ADDR'] == '124.109.61.3' ){
        //     $this->stencil->paint('admin/dashboard/dashboard', $data);
        //     //$this->load->view('admin/dashboard/maintanence');
        // }else{
        //     $this->load->view('admin/dashboard/maintanence');
        // }
        //stencil is our templating library. Simply call view via it

        //Umer Abbas [30-10-19]
        $this->load->model('admin/mod_settings');
        $trading_on_Off = $this->mod_settings->get_saved_on_off_trading();

        foreach ($trading_on_Off as $row) {
            if($row['type'] == 'automatic_on_of_trading'){
                $data['trading_status']['auto_trading_status'] = $row['status'];
            }
            if($row['type'] == 'custom_on_of_trading'){
                $data['trading_status']['custom_trading_status'] = $row['status'];;
            }
        }//End of foreach trading
        
        $this->stencil->paint('admin/dashboard/dashboard', $data);

    } //End of index

    public function danish() {
        //Fetching Market Buy Depth
        $market_buy_depth_data = $this->mod_dashboard->get_market_buy_depth();
        $data['market_buy_depth_arr'] = $market_buy_depth_data['fullarray'];

        $market_value = $market_buy_depth_data['market_value'];
        $data['market_value'] = num($market_value);

        //Fetching Market Sell Depth
        $market_sell_depth_data = $this->mod_dashboard->get_market_sell_depth();

        $data['market_sell_depth_arr'] = $market_sell_depth_data['fullarray'];

        //Fetching Market History
        $market_history_arr = $this->mod_dashboard->get_market_history();
        $data['market_history_arr'] = $market_history_arr;

        echo '<pre>';
        print_r($data);
    } //End

    public function check_user_bnb_balance() {
        $search_criteria['coin_symbol'] = 'BNBBTC';
        $admin_id = $this->session->userdata('admin_id');
        $search_criteria['user_id'] = $admin_id;
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

    public function login_history_search($user_id) {
        //$user_id           = $this->session->userdata('admin_id');
        $data_arr = $this->mod_users->get_user_login_info($user_id);
        $data['user_info'] = $data_arr;
        $this->stencil->paint('admin/dashboard/login_history', $data);
    } //end login_history();

    public function test_market() {

        $global_symbol = $this->session->userdata('global_symbol');

        //Get Market Prices
        $this->mongo_db->where(array('coin' => 'NCASHBTC'));
        $this->mongo_db->limit(100);
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('market_prices');

        $final_arr = array();
        foreach ($responseArr as $valueArr) {
            if (!empty($valueArr)) {
                $market_value = $valueArr['price'];

                $fullarray[] = num($market_value);
            }
        }

        echo "<pre>";
        print_r($fullarray);
        exit;

    }
    public function test_session() {
        echo "<pre>";
        print_r($this->session->userdata());
        exit;
    }
    public function new_test() {

        $global_symbol = $this->session->userdata('global_symbol');
        $market_value = $this->mod_dashboard->get_market_value($global_symbol);

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

                echo "<pre>";
                print_r($valueArr);
                exit;

            }
        }

        exit;

    }

    public function get_order_custom($id) {

        $order_arr = $this->mod_dashboard->get_order($id);

        echo "<pre>";
        print_r($order_arr);
        exit;

    }

    public function get_buy_order_custom($id) {

        $order_arr = $this->mod_dashboard->get_buy_order($id);

        echo "<pre>";
        print_r($order_arr);
        exit;

    }

    public function manual_update($id) {

        $global_symbol = $this->session->userdata('global_symbol');

        //limit_order market_order
        $upd_data = array(
            'trigger_type' => 'no',
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('orders');

    }

    public function manual_update22($id) {

        $global_symbol = $this->session->userdata('global_symbol');

        //limit_order market_order
        $upd_data = array(
            'application_mode' => 'live',
        );

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('buy_orders');

    }

    public function manual_delete($id) {
        $this->mongo_db->where(array('_id' => $id));

        //Delete data in mongoTable
        $this->mongo_db->delete('buy_orders');
    }

    public function user_testing() {
        $user_testing = $this->binance_api->user_testing();
    }

    public function get_account_balance() {

        $this->binance_api->get_account_balance();
    }

    public function manual_buy() {

        $created_date = date('Y-m-d G:i:s');
        $admin_id = $this->session->userdata('admin_id');
        $global_symbol = $this->session->userdata('global_symbol');

        $ins_data = array(
            'price' => '0.00000247',
            'quantity' => '420',
            'symbol' => 'NCASHBTC',
            'order_type' => 'limit_order',
            'admin_id' => $admin_id,
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
        );

        $ins_data['trail_check'] = 'no';
        $ins_data['trail_interval'] = '0';
        $ins_data['buy_trail_price'] = '0';
        $ins_data['market_value'] = '0.00000247';
        $ins_data['status'] = 'submitted';
        $ins_data['binance_order_id'] = '15325247';

        //Insert data in mongoTable
        $this->mongo_db->insert('buy_orders', $ins_data);
    }

    public function manual_sell() {

        $created_date = date('Y-m-d G:i:s');
        $admin_id = $this->session->userdata('admin_id');
        $global_symbol = $this->session->userdata('global_symbol');

        $ins_data = array(
            'purchased_price' => '0.00000390',
            'quantity' => '260',
            'profit_type' => 'percentage',
            'order_type' => 'limit_order',
            'admin_id' => $admin_id,
            'buy_order_check' => 'yes',
            'buy_order_id' => '5ac638e91c0b7623e117d792',
            'buy_order_binance_id' => '6069005',
            'created_date' => $this->mongo_db->converToMongodttime($created_date),
        );

        $ins_data['sell_profit_percent'] = '1';
        $ins_data['sell_price'] = '0.00000413';

        $ins_data['trail_check'] = 'no';
        $ins_data['trail_interval'] = '0';
        $ins_data['sell_trail_price'] = '0';

        $ins_data['market_value'] = '0.00000413';
        $ins_data['status'] = 'FILLED';
        $ins_data['binance_order_id'] = '5879337';

        //Insert data in mongoTable
        $order_id = $this->mongo_db->insert('orders', $ins_data);

        //Update Buy Order
        $upd_data = array(
            'is_sell_order' => 'yes',
            'sell_order_id' => $order_id,
        );

        $this->mongo_db->where(array('_id' => '5ac638e91c0b7623e117d792'));
        $this->mongo_db->set($upd_data);

        //Update data in mongoTable
        $this->mongo_db->update('buy_orders');

    }

    public function place_buy_order() {

        $pirces = $this->binance_api->place_buy_order();
    }

    public function order_status() {

        $pirces = $this->binance_api->order_status();
    }

    public function get_all_orders() {

        $this->mod_dashboard->get_all_orders();

    }

    public function get_market_data() {

        //get_market_data
        $market_buy_depth_arr = $this->mod_dashboard->get_market_data();
    }

    public function chart() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Fetching Market Buy Depth
        $market_buy_depth_data = $this->mod_dashboard->get_market_buy_depth_chart();
        $data['market_buy_depth_arr'] = $market_buy_depth_data['fullarray'];
        $buy_big_quantity = $market_buy_depth_data['buy_big_quantity'];

        $market_value = $market_buy_depth_data['market_value'];
        $data['market_value'] = num($market_value);

        //Fetching Market Sell Depth
        $market_sell_depth_data = $this->mod_dashboard->get_market_sell_depth_chart();
        $data['market_sell_depth_arr'] = $market_sell_depth_data['fullarray'];
        $sell_big_quantity = $market_sell_depth_data['sell_big_quantity'];

        if ($buy_big_quantity > $sell_big_quantity) {
            $biggest_value = $buy_big_quantity;
        } else {
            $biggest_value = $sell_big_quantity;
        }

        $data['biggest_value'] = $biggest_value;

        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/dashboard/chart', $data);
    }

    public function chart2() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Fetching Market Buy Depth
        $market_buy_depth_data = $this->mod_dashboard->get_market_buy_depth_chart();
        $data['market_buy_depth_arr'] = $market_buy_depth_data['fullarray'];
        $buy_big_quantity = $market_buy_depth_data['buy_big_quantity'];

        $market_value = $market_buy_depth_data['market_value'];
        $data['market_value'] = num($market_value);

        //Fetching Market Sell Depth
        $market_sell_depth_data = $this->mod_dashboard->get_market_sell_depth_chart();
        $data['market_sell_depth_arr'] = $market_sell_depth_data['fullarray'];
        $sell_big_quantity = $market_sell_depth_data['sell_big_quantity'];

        if ($buy_big_quantity > $sell_big_quantity) {
            $biggest_value = $buy_big_quantity;
        } else {
            $biggest_value = $sell_big_quantity;
        }

        $data['biggest_value'] = $biggest_value;

        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/dashboard/chart2', $data);
    }

    public function chart3() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Fetching Market Buy Depth
        $market_buy_depth_data = $this->mod_dashboard->get_market_buy_depth_chart();
        $data['market_buy_depth_arr'] = $market_buy_depth_data['fullarray'];
        $buy_big_quantity = $market_buy_depth_data['buy_big_quantity'];

        $market_value = $market_buy_depth_data['market_value'];
        $data['market_value'] = num($market_value);

        //Fetching Market Sell Depth
        $market_sell_depth_data = $this->mod_dashboard->get_market_sell_depth_chart();
        $data['market_sell_depth_arr'] = $market_sell_depth_data['fullarray'];
        $sell_big_quantity = $market_sell_depth_data['sell_big_quantity'];

        if ($buy_big_quantity > $sell_big_quantity) {
            $biggest_value = $buy_big_quantity;
        } else {
            $biggest_value = $sell_big_quantity;
        }

        $data['biggest_value'] = $biggest_value;

        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/dashboard/chart3', $data);
    }

    public function autoload_trading_data() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        $global_symbol = $this->session->userdata('global_symbol');
        $currncy = str_replace('BTC', '', $global_symbol);

        $market_value = $this->input->post('market_value');

        //Fetching Market Sell Depth
        $market_sell_depth_data = $this->mod_dashboard->get_market_sell_depth($market_value);
        $market_value = $market_sell_depth_data['market_value'];
        $market_sell_depth_arr = $market_sell_depth_data['fullarray'];

        $market_value = num($market_value);

        $response = '';
        if (count($market_sell_depth_arr) > 0) {
            $response = '<table class="table table-condensed">
                            <thead>
                                <tr>
                                  <td><strong>Price(BTC)</strong></td>
                                  <td><strong>Amount(' . $currncy . ')</strong></td>
                                  <td><strong>Total(BTC)</strong></td>
                                </tr>
                            </thead>
                          	<tbody>';
            foreach ($market_sell_depth_arr as $key => $value) {

                $price = num($value['price']);

                $response .= '<tr>
                                <td>' . number_format($price, 8, '.', '') . '</td>
                                <td>' . number_format($value['quantity'], 2, '.', '') . '</td>
                                <td>';
                $total_price = $value['price'] * $value['quantity'];
                $response .= number_format($total_price, 8, '.', '');
                $response .= '</td>
                             </tr>';
            }
            $response .= '</tbody>
                    </table>';
        }

        //Fetching Market Buy Depth
        $market_buy_depth_data = $this->mod_dashboard->get_market_buy_depth($market_value);
        $market_value = $market_buy_depth_data['market_value'];
        $market_buy_depth_arr = $market_buy_depth_data['fullarray'];

        $response2 = '';
        if (count($market_buy_depth_arr) > 0) {
            $response2 = '<table class="table table-condensed">
                            <thead>
                                <tr>
                                  <td><strong>Price(BTC)</strong></td>
                                  <td><strong>Amount(' . $currncy . ')</strong></td>
                                  <td><strong>Total(BTC)</strong></td>
                                </tr>
                            </thead>
                          	<tbody>';
            foreach ($market_buy_depth_arr as $key => $value2) {

                $price22 = num($value2['price']);

                $response2 .= '<tr>
                                <td>' . number_format($price22, 8, '.', '') . '</td>
                                <td>' . number_format($value2['quantity'], 2, '.', '') . '</td>
                                <td>';
                $total_price2 = $value2['price'] * $value2['quantity'];
                $response2 .= number_format($total_price2, 8, '.', '');
                $response2 .= '</td>
                             </tr>';
            }
            $response2 .= '</tbody>
                    </table>';

        }

        //Fetching Market History
        $market_history_arr = $this->mod_dashboard->get_market_history();

        $response3 = '';
        if (count($market_history_arr) > 0) {
            $response3 = '<table class="table table-condensed">
                            <thead>
                                <tr>
                                  <td><strong>Price(BTC)</strong></td>
                                  <td><strong>Amount(' . $currncy . ')</strong></td>
                                  <td><strong>Total(BTC)</strong></td>
                                </tr>
                            </thead>
                          	<tbody>';
            foreach ($market_history_arr as $key => $value3) {

                $maker = $value3['maker'];
                if ($maker == 'true') {
                    $color = "red";
                } else {
                    $color = "green";
                }
                if ($_SERVER['REMOTE_ADDR'] == '58.65.164.72') {
                    $response3 .= '<tr style="color:' . $color . ';">
                                    <td>' . number_format($value3['price'], 8, '.', '') . '(' . $value3['counter'] . ')</td>
                                    <td>' . number_format($value3['quantity'], 2, '.', '') . '</td>
                                    <td>';
                    $total_price3 = $value3['price'] * $value3['quantity'];
                    $response3 .= number_format($total_price3, 8, '.', '');
                    $response3 .= '</td>
                                             </tr>';
                } else {
                    $response3 .= '<tr style="color:' . $color . ';">
                                                    <td>' . number_format($value3['price'], 8, '.', '') . '</td>
                                                    <td>' . number_format($value3['quantity'], 2, '.', '') . '</td>
                                                    <td>';
                    $total_price3 = $value3['price'] * $value3['quantity'];
                    $response3 .= number_format($total_price3, 8, '.', '');
                    $response3 .= '</td>
                                                 </tr>';
                }
            }
            $response3 .= '</tbody>
                    </table>';
        }

        echo $response . "|" . $response2 . "|" . $response3 . "|" . number_format($market_value, 8, '.', '');
        exit;

    } //end autoload_trading_data

    public function autoload_trading_chart_data() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        $market_value = $this->input->post('market_value');
        $previous_market_value = $this->input->post('previous_market_value');

        //Fetching Market Buy Depth
        $market_buy_depth_data = $this->mod_dashboard->get_market_buy_depth_chart($market_value);
        $market_value = $market_buy_depth_data['market_value'];
        $market_buy_depth_arr = $market_buy_depth_data['fullarray'];
        $buy_big_quantity = $market_buy_depth_data['buy_big_quantity'];

        //Fetching Market Sell Depth
        $market_sell_depth_data = $this->mod_dashboard->get_market_sell_depth_chart($market_value);
        $market_value = $market_sell_depth_data['market_value'];
        $market_sell_depth_arr = $market_sell_depth_data['fullarray'];
        $sell_big_quantity = $market_sell_depth_data['sell_big_quantity'];

        if ($buy_big_quantity > $sell_big_quantity) {
            $biggest_value = $buy_big_quantity;
        } else {
            $biggest_value = $sell_big_quantity;
        }

        $market_value = num($market_value);

        $response = '<ul class="price_s_r_ul">';

        if (count($market_buy_depth_arr) > 0) {
            $i = 0;
            foreach ($market_buy_depth_arr as $key => $value) {

                $price = num($value['price']);

                $response .= '<li class="price_s_r_li" d_price="' . $price . '" index="' . $i . '">
			                        <div class="buyer_prog_main">
			                            <div class="blu_prog">
			                                <div class="blue_prog_p">' . $value['sell_quantity'] . '</div>';
                $sell_percentage = round($value['sell_quantity'] * 100 / $biggest_value);

                if ($sell_percentage == 100) {
                    $type = 'buy';
                }

                $response .= '<div class="blue_prog_bar" d_prog_percent="' . $sell_percentage . '"></div>
			                            </div>
			                        </div>
			                        <div class="price_cent_main">
			                            <span class="simple_p gray_v_p">' . $price . '</span>
			                        </div>
			                        <div class="seller_prog_main">
			                            <div class="red_prog">
			                                <div class="red_prog_p">' . $value['buy_quantity'] . '</div>';
                $buy_percentage = round($value['buy_quantity'] * 100 / $biggest_value);

                if ($buy_percentage == 100) {
                    $type = 'sell';
                }

                $response .= '<div class="red_prog_bar" d_prog_percent="' . $buy_percentage . '"></div>
			                            </div>
			                        </div>
			                    </li>';

                $i++;

            } //end foreach
        } //end if

        if ($market_value > $previous_market_value) {
            $class = 'GCV_color_green';
            $icon = 'fa fa-arrow-up';
        } elseif ($market_value < $previous_market_value) {
            $class = 'GCV_color_red';
            $icon = 'fa fa-arrow-down';
        } else {
            $class = 'GCV_color_default';
            $icon = '';
        }

        $response .= '<li class="price_s_r_li" d_price="' . $market_value . '" index="' . $i++ . '">
				            <div class="buyer_prog_main">
				            </div>
				            <div class="price_cent_main">
				                <span class="simple_p white_v_p" id="response2222">
				                 <span class="' . $class . '">' . $market_value . '</span>
				                </span>
				            </div>
				            <div class="seller_prog_main">
				                <div class="red_prog">
				                </div>
				            </div>
				        </li>';

        if (count($market_sell_depth_arr) > 0) {

            foreach ($market_sell_depth_arr as $key => $value2) {

                $price22 = num($value2['price']);

                $response .= '<li class="price_s_r_li" d_price="' . $price22 . '" index="' . $i . '">
			                        <div class="buyer_prog_main">
			                            <div class="blu_prog">
			                                <div class="blue_prog_p">' . $value2['sell_quantity'] . '</div>';
                $sell_percentage2 = round($value2['sell_quantity'] * 100 / $biggest_value);

                if ($sell_percentage2 == 100) {
                    $type = 'buy';
                }

                $response .= '<div class="blue_prog_bar" d_prog_percent="' . $sell_percentage2 . '"></div>
			                            </div>
			                        </div>
			                        <div class="price_cent_main">
			                            <span class="simple_p light_gray_v_p">' . $price22 . '</span>
			                        </div>
			                        <div class="seller_prog_main">
			                            <div class="red_prog">
			                                <div class="red_prog_p">' . $value2['buy_quantity'] . '</div>';
                $buy_percentage2 = round($value2['buy_quantity'] * 100 / $biggest_value);

                if ($buy_percentage2 == 100) {
                    $type = 'sell';
                }

                $response .= '<div class="red_prog_bar" d_prog_percent="' . $buy_percentage2 . '"></div>
			                            </div>
			                        </div>
			                    </li>';

                $i++;

            } //end foreach
        } //end if

        $response .= '</ul>';

        //GEt Zone values
        $zone_values_arr = $this->mod_dashboard->get_zone_values($market_value);

        $buy_quantity = $zone_values_arr['buy_quantity'];
        $sell_quantity = $zone_values_arr['sell_quantity'];
        $buy_percentage = $zone_values_arr['buy_percentage'];
        $sell_percentage = $zone_values_arr['sell_percentage'];
        $zone_id = $zone_values_arr['zone_id'];

        $response2 = '<div class="G_current_val ' . $class . '">
			                <div class="GCV_text"><b>' . $market_value . '</b></div>
			                <div class="GCV_icon">
			                    <i class="' . $icon . '" aria-hidden="true"></i>
			                </div>
			            </div>';

        $response3 = $market_value;

        if ($zone_id != "" && $buy_percentage != 'NAN' && $sell_quantity != 'NAN') {

            $response4 = '<div class="verti_bar_prog_top" d_vbpPercent="' . $buy_percentage . '">
		                    <span>' . $buy_quantity . '</span>
		                 </div>
		                 <div class="verti_bar_prog_bottom" d_vbpPercent="' . $sell_percentage . '">
		                    <span>' . $sell_quantity . '</span>
		                 </div>';

        } else {
            $response4 = '';
        }

        //Get zones Record
        $chart_target_zones_arr = $this->mod_dashboard->get_chart_target_zones();

        echo $response . "|" . $response2 . "|" . $response3 . "|" . $type . "|" . json_encode($chart_target_zones_arr) . "|" . $response4;
        exit;

    } //end autoload_trading_chart_data

    public function autoload_trading_chart_data222() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        $market_value = $this->input->post('market_value');
        $previous_market_value = $this->input->post('previous_market_value');

        //Fetching Market Buy Depth
        $market_buy_depth_data = $this->mod_dashboard->get_market_buy_depth_chart($market_value);
        $market_value = num($market_buy_depth_data['market_value']);
        $market_buy_depth_arr = $market_buy_depth_data['fullarray'];
        $buy_big_quantity = $market_buy_depth_data['buy_big_quantity'];
        $depth_buy_big_quantity = $market_buy_depth_data['depth_buy_big_quantity'];

        //Fetching Market Sell Depth
        $market_sell_depth_data = $this->mod_dashboard->get_market_sell_depth_chart($market_value);
        $market_value = num($market_sell_depth_data['market_value']);
        $market_sell_depth_arr = $market_sell_depth_data['fullarray'];
        $sell_big_quantity = $market_sell_depth_data['sell_big_quantity'];
        $depth_sell_big_quantity = $market_buy_depth_data['depth_sell_big_quantity'];

        if ($buy_big_quantity > $sell_big_quantity) {
            $biggest_value = $buy_big_quantity;
        } else {
            $biggest_value = $sell_big_quantity;
        }

        if ($depth_buy_big_quantity > $depth_sell_big_quantity) {
            $biggest_value2 = $depth_buy_big_quantity;
        } else {
            $biggest_value2 = $depth_sell_big_quantity;
        }

        $response = '<ul class="price_s_r_ul">';

        if (count($market_buy_depth_arr) > 0) {
            $i = 0;
            foreach ($market_buy_depth_arr as $key => $value) {

                $price = num($value['price']);

                $response .= '<li class="price_s_r_li with_BS" d_price="' . num($price) . '" index="' . $i . '">
			                        <div class="wbs_buyer_prog_main">
			                            <div class="wbs_blu_prog">
			                                <div class="wbs_blue_prog_p">' . number_format($value['depth_sell_quantity'], 2, '.', '') . '</div>';
                $sell_percentage22 = round($value['depth_sell_quantity'] * 100 / $biggest_value2);

                $response .= '<div class="wbs_blue_prog_bar" WBS_d_prog_percent="' . $sell_percentage22 . '"></div>
			                            </div>
			                        </div>
			                        <div class="buyer_prog_main">
			                            <div class="blu_prog">
			                                <div class="blue_prog_p">' . number_format($value['sell_quantity'], 2, '.', '') . '</div>';
                $sell_percentage = round($value['sell_quantity'] * 100 / $biggest_value);

                if ($sell_percentage == 100) {
                    $type = 'buy';
                }

                $response .= '<div class="blue_prog_bar" d_prog_percent="' . $sell_percentage . '"></div>
			                            </div>
			                        </div>
			                        <div class="price_cent_main">
			                            <span class="simple_p gray_v_p">' . num($price) . '</span>
			                        </div>
			                        <div class="seller_prog_main">
			                            <div class="red_prog">
			                                <div class="red_prog_p">' . number_format($value['buy_quantity'], 2, '.', '') . '</div>';
                $buy_percentage = round($value['buy_quantity'] * 100 / $biggest_value);

                if ($buy_percentage == 100) {
                    $type = 'sell';
                }

                $response .= '<div class="red_prog_bar" d_prog_percent="' . $buy_percentage . '"></div>
			                            </div>
			                        </div>
			                        <div class="wbs_seller_prog_main">
			                            <div class="wbs_red_prog">
			                                <div class="wbs_red_prog_p">' . number_format($value['depth_buy_quantity'], 2, '.', '') . '</div>';
                $buy_percentage22 = round($value['depth_buy_quantity'] * 100 / $biggest_value2);

                $response .= '<div class="wbs_red_prog_bar" wbs_d_prog_percent="' . $buy_percentage22 . '"></div>
			                            </div>
			                        </div>
			                    </li>';

                $i++;

            } //end foreach
        } //end if

        if ($market_value > $previous_market_value) {
            $class = 'GCV_color_green';
            $icon = 'fa fa-arrow-up';
        } elseif ($market_value < $previous_market_value) {
            $class = 'GCV_color_red';
            $icon = 'fa fa-arrow-down';
        } else {
            $class = 'GCV_color_default';
            $icon = '';
        }

        $response .= '<li class="price_s_r_li with_BS" d_price="' . num($market_value) . '" index="' . $i++ . '">
				            <div class="wbs_buyer_prog_main">
	                        </div>
				            <div class="buyer_prog_main">
				            </div>
				            <div class="price_cent_main">
				                <span class="simple_p white_v_p" id="response2222">
				                 <span class="' . $class . '">' . num($market_value) . '</span>
				                </span>
				            </div>
				            <div class="seller_prog_main">
				                <div class="red_prog">
				                </div>
				            </div>
				            <div class="wbs_seller_prog_main">
	                        </div>
				        </li>';

        if (count($market_sell_depth_arr) > 0) {

            foreach ($market_sell_depth_arr as $key => $value2) {

                $price22 = num($value2['price']);

                $response .= '<li class="price_s_r_li with_BS" d_price="' . num($price22) . '" index="' . $i . '">
			                        <div class="wbs_buyer_prog_main">
			                            <div class="wbs_blu_prog">
			                                <div class="wbs_blue_prog_p">' . number_format($value2['depth_sell_quantity'], 2, '.', '') . '</div>';
                $sell_percentage222 = round($value2['depth_sell_quantity'] * 100 / $biggest_value2);

                $response .= '<div class="wbs_blue_prog_bar" WBS_d_prog_percent="' . $sell_percentage222 . '"></div>
			                            </div>
			                        </div>
			                        <div class="buyer_prog_main">
			                            <div class="blu_prog">
			                                <div class="blue_prog_p">' . number_format($value2['sell_quantity'], 2, '.', '') . '</div>';
                $sell_percentage2 = round($value2['sell_quantity'] * 100 / $biggest_value);

                if ($sell_percentage2 == 100) {
                    $type = 'buy';
                }

                $response .= '<div class="blue_prog_bar" d_prog_percent="' . $sell_percentage2 . '"></div>
			                            </div>
			                        </div>
			                        <div class="price_cent_main">
			                            <span class="simple_p light_gray_v_p">' . num($price22) . '</span>
			                        </div>
			                        <div class="seller_prog_main">
			                            <div class="red_prog">
			                                <div class="red_prog_p">' . number_format($value2['buy_quantity'], 2, '.', '') . '</div>';
                $buy_percentage2 = round($value2['buy_quantity'] * 100 / $biggest_value);

                if ($buy_percentage2 == 100) {
                    $type = 'sell';
                }

                $response .= '<div class="red_prog_bar" d_prog_percent="' . $buy_percentage2 . '"></div>
			                            </div>
			                        </div>
			                        <div class="wbs_seller_prog_main">
			                            <div class="wbs_red_prog">
			                                <div class="wbs_red_prog_p">' . number_format($value2['depth_buy_quantity'], 2, '.', '') . '</div>';
                $buy_percentage222 = round($value2['depth_buy_quantity'] * 100 / $biggest_value2);

                $response .= '<div class="wbs_red_prog_bar" wbs_d_prog_percent="' . $buy_percentage222 . '"></div>
			                            </div>
			                        </div>
			                    </li>';

                $i++;

            } //end foreach
        } //end if

        $response .= '</ul>';

        //GEt Zone values
        $zone_values_arr = $this->mod_dashboard->get_zone_values($market_value);

        $buy_quantity = $zone_values_arr['buy_quantity'];
        $sell_quantity = $zone_values_arr['sell_quantity'];
        $buy_percentage = $zone_values_arr['buy_percentage'];
        $sell_percentage = $zone_values_arr['sell_percentage'];
        $zone_id = $zone_values_arr['zone_id'];

        $response2 = '<div class="G_current_val ' . $class . '">
			                <div class="GCV_text"><b>' . num($market_value) . '</b></div>
			                <div class="GCV_icon">
			                    <i class="' . $icon . '" aria-hidden="true"></i>
			                </div>
			            </div>';

        $response3 = $market_value;

        if ($zone_id != "" && $buy_percentage != 'NAN' && $sell_quantity != 'NAN') {

            $response4 = '<div class="verti_bar_prog_top" d_vbpPercent="' . $buy_percentage . '">
		                    <span>' . $buy_quantity . '</span>
		                 </div>
		                 <div class="verti_bar_prog_bottom" d_vbpPercent="' . $sell_percentage . '">
		                    <span>' . $sell_quantity . '</span>
		                 </div>';

        } else {
            $response4 = '';
        }

        //Get zones Record
        $chart_target_zones_arr = $this->mod_dashboard->get_chart_target_zones();

        //Get Sell Orders
        $orders_arr = $this->mod_dashboard->get_sell_active_orders();

        //Get Buy Orders
        $buy_orders_arr = $this->mod_dashboard->get_buy_active_orders();

        echo $response . "|" . $response2 . "|" . $response3 . "|" . $type . "|" . json_encode($chart_target_zones_arr) . "|" . $response4 . "|" . json_encode($orders_arr) . "|" . json_encode($buy_orders_arr);
        exit;

    } //end autoload_trading_chart_data222

    public function edit_profile() {
        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Fetching user Record
        $user_arr = $this->mod_users->get_user($this->session->userdata('admin_id'));

        $data['user_arr'] = $user_arr;
        $time_zone_arr = $this->mod_dashboard->get_time_zone();
        $data['time_zone_arr'] = $time_zone_arr;
        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/dashboard/edit_profile', $data);
    }

    public function testCurl() {

        $time_stamp = '1527860017';
        $header = array(
            //Free Token : 6e80f4879da5a0c2f7f2eb2b641033b12541538e
            //Paid Token first  : cf0fc855ba948e64af7ab897631ef67d1a02c007

            //second paid token : 625ecb409c9efe54b7c15ed6952d48544b9ddaa1

            'Accept: application/json',
            'Authorization: Token 625ecb409c9efe54b7c15ed6952d48544b9ddaa1',
        );

        $set_url = "https://coinograph.io/trades/?symbol=binance:BNBBTC&limit=300&start=$time_stamp";

        //echo $set_url; exit;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $set_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);
        echo "<pre>";
        print_r($response);exit;

    }

    public function edit_profile_process() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        $report_id = $this->input->post('report_id');
        $email_address = $this->input->post('email_address');

        if ($report_id != '' && $report_id != 0) {
            // ******** Alikhan Work Goes here *********//

            $header = array(
                'Accept: application/json',
            );
            $set_url = "https://users.digiebot.com/cronjob/getReportUserExists/?report_id=$report_id&email_address=$email_address";
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $set_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => $header,
            ));

            $response = curl_exec($curl);
            $userArr = json_decode($response);

            if ($userArr->userData == 'Error') {
                $this->session->set_flashdata('err_message', ' Your profile can not updated with Report Id <b>' . $report_id . '</b> user does not exist in Digie report . First create the digie Report account here
				 <b><a href="http://users.digiebot.com/signup" target="_blank">Click Here</a></b>');
                redirect(base_url() . 'admin/dashboard/edit-profile');
            }
        }
        //edit_user
        $user_id = $this->mod_users->edit_user($this->input->post());

        if ($user_id) {
            redirect(base_url() . 'admin/dashboard/edit-profile');
        } else {
            redirect(base_url() . 'admin/dashboard/edit-profile');
        } //end if

    } //end edit_profile_process

    public function login_history() {
        $user_id = $this->session->userdata('admin_id');
        $data_arr = $this->mod_users->get_user_login_info($user_id);
        $data['user_info'] = $data_arr;
        $this->stencil->paint('admin/dashboard/login_history', $data);
    } //end login_history();

    public function transaction_history() {
        $user_id = $this->session->userdata('admin_id');
        $data_arr = $this->mod_users->get_user_transaction_info($user_id);

        $data['user_info'] = $data_arr;
        $this->stencil->paint('admin/dashboard/transaction_history', $data);
    } //end login_history();
    public function transaction_history_process() {
        $data_arr = $this->input->post();

        $id = $data_arr['id'];
        $price = $data_arr['price'];

        $this->mongo_db->where(array("_id" => $this->mongo_db->mongoId($id)));
        $this->mongo_db->set(array("price_in_usd" => $price));
        $this->mongo_db->update("user_transaction_history");

        echo "Price " . $price . " against " . $id . " Updated successfully";
        exit;
    } //end login_history();

    public function test($price) {

        $this->mongo_db->where(array('type' => 'ask', 'coin' => 'BNBBTC', 'price' => (float) $price));
        $responseArr2222 = $this->mongo_db->get('market_depth');

        //////////////
        $fullarray = array();
        foreach ($responseArr2222 as $valueArr) {
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
                $returArr['maker'] = $valueArr['maker'];
                $returArr['coin'] = $valueArr['coin'];
                $returArr['created_date'] = $formated_date_time;

            }

            $fullarray[] = $returArr;
        }

        echo "<pre>";
        print_r($fullarray);
        exit;
    }

    public function add_zone() {
        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Fetching coins Record
        $coins_arr = $this->mod_coins->get_all_coins();
        $data['coins_arr'] = $coins_arr;

        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/dashboard/add_zone', $data);
    }

    public function add_zone_process() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //add_zone
        $add_zone = $this->mod_dashboard->add_zone($this->input->post());

        if ($add_zone) {

            $this->session->set_flashdata('ok_message', 'Record added successfully.');
            redirect(base_url() . 'admin/dashboard/add-zone');

        } else {

            $this->session->set_flashdata('err_message', 'Something went wrong, please try again.');
            redirect(base_url() . 'admin/dashboard/add-zone');

        } //end if

    } //end add_zone_process

    public function edit_zone($id) {
        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Get zone Record
        $zone_arr = $this->mod_dashboard->get_zone($id);
        $data['zone_arr'] = $zone_arr;

        //Fetching coins Record
        $coins_arr = $this->mod_coins->get_all_coins();
        $data['coins_arr'] = $coins_arr;

        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/dashboard/edit_zone', $data);
    }

    public function edit_zone_process() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //edit_zone
        $edit_zone = $this->mod_dashboard->edit_zone($this->input->post());

        $id = $this->input->post('id');

        if ($edit_zone) {

            $this->session->set_flashdata('ok_message', 'Record updated successfully.');
            redirect(base_url() . 'admin/dashboard/zone-listing');

        } else {

            $this->session->set_flashdata('err_message', 'Something went wrong, please try again.');
            redirect(base_url() . 'admin/dashboard/edit-zone/' . $id);

        } //end if

    } //end edit_zone_process

    public function delete_zone($id) {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //delete_zone
        $delete_zone = $this->mod_dashboard->delete_zone($id);

        if ($delete_zone) {

            $this->session->set_flashdata('ok_message', 'Record deleted successfully.');
            redirect(base_url() . 'admin/dashboard/zone-listing');

        } else {

            $this->session->set_flashdata('err_message', 'Something went wrong, please try again.');
            redirect(base_url() . 'admin/dashboard/edit-zone');

        } //end if

    } //end delete_zone

    public function zone_listing() {
        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Get zones Record
        $chart_target_zones_arr = $this->mod_dashboard->get_chart_target_zones();
        $data['chart_target_zones_arr'] = $chart_target_zones_arr;

        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/dashboard/zone_listing', $data);
    }

    public function add_order($buy_id = '') {
        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Fetching coins Record
        $coins_arr = $this->mod_coins->get_all_coins();
        $data['coins_arr'] = $coins_arr;

        if ($buy_id != "") {

            //Get Order Record
            $order_arr = $this->mod_dashboard->get_buy_order($buy_id);
            $data['order_arr'] = $order_arr;
            $data['buy_order_check'] = 'yes';

        } else {

            $data['buy_order_check'] = 'no';
        }

        //Get Order History
        $order_history_arr = $this->mod_dashboard->get_order_history_log($buy_id);
        $data['order_history_arr'] = $order_history_arr;

        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/dashboard/add_order', $data);

    } //end add_order

    public function add_order_process() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //add_order
        $add_order = $this->mod_dashboard->add_order($this->input->post());

        $buy_order_id = $this->input->post('buy_order_id');
        $buy_order_check = $this->input->post('buy_order_check');

        if ($add_order['error'] != "") {

            if ($buy_order_check == 'yes') {

                $this->session->set_flashdata('err_message', $add_order['error']);
                redirect(base_url() . 'admin/dashboard/add-order/' . $buy_order_id);

            } else {

                $this->session->set_flashdata('err_message', $add_order['error']);
                redirect(base_url() . 'admin/dashboard/add-order');
            }

        }

        if ($add_order) {

            $this->session->set_flashdata('ok_message', 'Record added successfully.');
            redirect(base_url() . 'admin/dashboard/edit-order/' . $buy_order_id);

        } else {

            $this->session->set_flashdata('err_message', 'Something went wrong, please try again.');
            redirect(base_url() . 'admin/dashboard/add-order');

        } //end if

    } //end add_order_process

    public function edit_order($id) {
        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Fetching coins Record
        $coins_arr = $this->mod_coins->get_all_coins();
        $data['coins_arr'] = $coins_arr;

        //Get Order Record
        $order_arr = $this->mod_dashboard->get_order($id);
        $data['order_arr'] = $order_arr;

        //Get Order History
        $order_history_arr = $this->mod_dashboard->get_order_history_log($order_arr['buy_order_id']);
        $data['order_history_arr'] = $order_history_arr;

        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/dashboard/edit_order', $data);

    } //end edit_order

    public function edit_order_process() {

        //Login Check
        $this->mod_login->verify_is_admin_login();
        //edit_order
        $edit_order = $this->mod_dashboard->edit_order($this->input->post());

        $id = $this->input->post('id');

        if ($edit_order['error'] != "") {

            $this->session->set_flashdata('err_message', $edit_order['error']);
            redirect(base_url() . 'admin/dashboard/edit-order/' . $id);
        }

        if ($edit_order) {

            $this->session->set_flashdata('ok_message', 'Record updated successfully.');
            redirect(base_url() . 'admin/dashboard/edit-order/' . $id);

        } else {

            $this->session->set_flashdata('err_message', 'Something went wrong, please try again.');
            redirect(base_url() . 'admin/dashboard/edit-order/' . $id);

        } //end if

    } //end edit_order_process

    public function delete_order($id, $order_id) {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //delete_order
        $delete_order = $this->mod_dashboard->delete_order($id, $order_id);

        if ($delete_order) {

            $this->session->set_flashdata('ok_message', 'Record deleted successfully.');
            redirect(base_url() . 'admin/dashboard/orders-listing');

        } else {

            $this->session->set_flashdata('err_message', 'Something went wrong, please try again.');
            redirect(base_url() . 'admin/dashboard/edit-order');

        } //end if

    } //end delete_order

    public function orders_listing() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        if ($this->input->post()) {

            $data_arr['filter-data'] = $this->input->post();
            $this->session->set_userdata($data_arr);
            redirect(base_url() . 'admin/dashboard/orders-listing');
        }

        //Fetching coins Record
        $coins_arr = $this->mod_coins->get_all_coins();
        $data['coins_arr'] = $coins_arr;

        $global_symbol = $this->session->userdata('global_symbol');

        $filled_orders = array();
        $new_orders = array();
        $error_orders = array();
        $cancelled = array();
        $orders_arr = $this->mod_dashboard->get_orders();
        foreach ($orders_arr as $key => $value) {
            if ($value['status'] == 'new') {
                $new_orders[] = $value;
            } elseif ($value['status'] == 'FILLED') {
                $filled_orders[] = $value;
            } elseif ($value['status'] == 'cancelled') {
                $cancelled[] = $value;
            } elseif ($value['status'] == 'error') {
                $error_orders[] = $value;
            }
        }

        $data['orders_arr'] = $orders_arr;
        $data['filled_arr'] = $filled_orders;
        $data['new_arr'] = $new_orders;
        $data['cancelled_arr'] = $cancelled;
        $data['error_arr'] = $error_orders;
        //Get Market Price
        $this->mongo_db->where(array('coin' => $global_symbol));
        $this->mongo_db->limit(1);
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('market_prices');

        foreach ($responseArr as $valueArr) {
            if (!empty($valueArr)) {
                $market_value = $valueArr['price'];
            }
        }

        $data['market_value'] = $market_value;

        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/dashboard/orders_listing', $data);
    }

    public function add_buy_order() {
        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Fetching coins Record
        $coins_arr = $this->mod_coins->get_all_coins();
        $data['coins_arr'] = $coins_arr;

        //Get Market Value
        $market_value = $this->mod_dashboard->get_market_value();
        $data['market_value'] = $market_value;

        //Check Buy Zones
        $check_buy_zones = $this->mod_dashboard->check_buy_zones($market_value);
        $data['in_zone'] = $check_buy_zones['in_zone'];
        $data['type'] = $check_buy_zones['type'];
        $data['start_value'] = $check_buy_zones['start_value'];
        $data['end_value'] = $check_buy_zones['end_value'];

        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/dashboard/add_buy_order', $data);

    } //end add_buy_order

    public function add_buy_order_process() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //add_buy_order
        $add_buy_order = $this->mod_dashboard->add_buy_order($this->input->post());

        if ($add_buy_order['error'] != "") {

            $this->session->set_flashdata('err_message', $add_buy_order['error']);
            redirect(base_url() . 'admin/dashboard/add-buy-order');
        }

        if ($add_buy_order) {

            $this->session->set_flashdata('ok_message', 'Buy Order added successfully.');
            redirect(base_url() . 'admin/dashboard/add-buy-order');

        } else {

            $this->session->set_flashdata('err_message', 'Something went wrong, please try again.');
            redirect(base_url() . 'admin/dashboard/add-buy-order');

        } //end if

    } //end add_buy_order_process

    public function edit_buy_order($id) {
        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Fetching coins Record
        $coins_arr = $this->mod_coins->get_all_coins();
        $data['coins_arr'] = $coins_arr;

        //Get Market Value
        $market_value = $this->mod_dashboard->get_market_value();
        $data['market_value'] = $market_value;

        //Check Buy Zones
        $check_buy_zones = $this->mod_dashboard->check_buy_zones($market_value);
        $data['in_zone'] = $check_buy_zones['in_zone'];
        $data['type'] = $check_buy_zones['type'];
        $data['start_value'] = $check_buy_zones['start_value'];
        $data['end_value'] = $check_buy_zones['end_value'];

        //Get Order Record
        $order_arr = $this->mod_dashboard->get_buy_order($id);
        $data['order_arr'] = $order_arr;

        //Get Temp Sell Order Record
        $temp_sell_arr = $this->mod_dashboard->get_temp_sell_data($id);
        $data['temp_sell_arr'] = $temp_sell_arr;

        //Get Order History
        $order_history_arr = $this->mod_dashboard->get_order_history_log($id);
        $data['order_history_arr'] = $order_history_arr;

        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/dashboard/edit_buy_order', $data);

    } //end edit_buy_order

    public function edit_buy_order_process() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //edit_buy_order
        $edit_buy_order = $this->mod_dashboard->edit_buy_order($this->input->post());

        $id = $this->input->post('id');

        if ($edit_buy_order['error'] != "") {

            $this->session->set_flashdata('err_message', $add_buy_order['error']);
            redirect(base_url() . 'admin/dashboard/edit-buy-order/' . $id);
        }

        if ($edit_buy_order) {

            $this->session->set_flashdata('ok_message', 'Edit Order updated successfully.');
            redirect(base_url() . 'admin/dashboard/edit-buy-order/' . $id);

        } else {

            $this->session->set_flashdata('err_message', 'Something went wrong, please try again.');
            redirect(base_url() . 'admin/dashboard/edit-buy-order/' . $id);

        } //end if

    } //end edit_buy_order_process

    public function delete_buy_order($id, $order_id) {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //delete_buy_order
        $delete_buy_order = $this->mod_dashboard->delete_buy_order($id, $order_id);

        if ($delete_buy_order) {

            $this->session->set_flashdata('ok_message', 'Record deleted successfully.');
            redirect(base_url() . 'admin/dashboard/buy-orders-listing');

        } else {

            $this->session->set_flashdata('err_message', 'Something went wrong, please try again.');
            redirect(base_url() . 'admin/dashboard/edit-buy-order');

        } //end if

    } //end delete_buy_order

    public function buy_orders_listing22222() {
        //Login Check
        $this->mod_login->verify_is_admin_login();

        if ($this->input->post()) {

            $data_arr['filter-data-buy'] = $this->input->post();
            $this->session->set_userdata($data_arr);
            redirect(base_url() . 'admin/dashboard/buy-orders-listing');
        }

        $global_symbol = $this->session->userdata('global_symbol');

        //Fetching coins Record
        $coins_arr = $this->mod_coins->get_all_coins();
        $data['coins_arr'] = $coins_arr;

        //Get Orders
        $return_data = $this->mod_dashboard->get_buy_orders();

        $data['orders_arr'] = $return_data['fullarray'];
        $data['total_buy_amount'] = $return_data['total_buy_amount'];
        $data['total_sell_amount'] = $return_data['total_sell_amount'];
        $data['total_sold_orders'] = $return_data['total_sold_orders'];
        $data['avg_profit'] = $return_data['avg_profit'];

        //Get Market Price
        $this->mongo_db->where(array('coin' => $global_symbol));
        $this->mongo_db->limit(1);
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('market_prices');

        foreach ($responseArr as $valueArr) {
            if (!empty($valueArr)) {
                $market_value = $valueArr['price'];
            }
        }

        $data['market_value'] = $market_value;

        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/dashboard/buy_orders_listing', $data);

    } //end buy_orders_listing

    public function buy_orders_listing() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        if ($this->input->post()) {

            $data_arr['filter-data-buy'] = $this->input->post();
            $this->session->set_userdata($data_arr);
            redirect(base_url() . 'admin/dashboard/buy-orders-listing');
        }
        $filled_orders = array();
        $new_orders = array();
        $error_orders = array();
        $cancelled = array();
        $submitted = array();
        $open_trades = array();
        $sold_trades = array();
        $return_data = $this->mod_dashboard->get_buy_orders();

        $orders_arr = $return_data['fullarray'];
        // echo "<pre>";
        // print_r($orders_arr);
        // exit;
        $data['total_buy_amount'] = $return_data['total_buy_amount'];
        $data['total_sell_amount'] = $return_data['total_sell_amount'];
        $data['total_sold_orders'] = $return_data['total_sold_orders'];
        $data['avg_profit'] = $return_data['avg_profit'];

        foreach ($orders_arr as $key => $value) {
            if ($value['status'] == 'new') {
                $new_orders[] = $value;
            } elseif ($value['status'] == 'FILLED') {
                if ($value['is_sell_order'] == 'yes') {
                    $open_trades[] = $value;
                }
                if ($value['is_sell_order'] == 'sold') {
                    $sold_trades[] = $value;
                }

                $filled_orders[] = $value;

            } elseif ($value['status'] == 'canceled') {
                $cancelled[] = $value;
            } elseif ($value['status'] == 'error') {
                $error_orders[] = $value;
            } elseif ($value['status'] == 'submitted') {
                $submitted[] = $value;
                $open_trades[] = $value;
            }
        }

        // echo "<pre>";
        // print_r($open_trades);
        // exit;
        $data['orders_arr'] = $orders_arr;
        $data['filled_arr'] = $filled_orders;
        $data['new_arr'] = $new_orders;
        $data['cancelled_arr'] = $cancelled;
        $data['error_arr'] = $error_orders;
        $data['submitted'] = $submitted;
        $data['open_trades'] = $open_trades;
        $data['sold_trades'] = $sold_trades;

        $global_symbol = $this->session->userdata('global_symbol');

        //Fetching coins Record
        $coins_arr = $this->mod_coins->get_all_coins();
        $data['coins_arr'] = $coins_arr;

        //Get Market Price
        $this->mongo_db->where(array('coin' => $global_symbol));
        $this->mongo_db->limit(1);
        $this->mongo_db->sort(array('_id' => 'desc'));
        $responseArr = $this->mongo_db->get('market_prices');

        foreach ($responseArr as $valueArr) {
            if (!empty($valueArr)) {
                $market_value = $valueArr['price'];
            }
        }

        $data['market_value'] = $market_value;

        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/dashboard/buy_orders_listing', $data);
    }

    public function drawCandlestick() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        $resp = $this->mod_dashboard->get_candelstick_data();
        $data["candlesdtickArr"] = $resp;

        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/dashboard/candlesdtick', $data);
    }

    public function drawCandlestick_custom() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        $resp = $this->mod_candel->get_candelstick_data();
        $data["candlesdtickArr"] = $resp;

        //stencil is our templating library. Simply call view via it
        $this->stencil->paint('admin/dashboard/candlesdtick_custom', $data);
        //$this->load->view('admin/dashboard/candlesdtick_custom',$data);
    }

    public function candle_stick_data() {
        $abc = $this->mod_dashboard->get_candelstick_data();
        echo "<pre>";
        print_r($abc);
        exit;
    }

    public function autoload_market_data() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Get Orders
        $orders_arr = $this->mod_dashboard->get_orders();

        $response = '<table class="table table-condensed">
	                    <thead>
	                        <tr>
	                            <th></th>
	                            <th><strong>Coin</strong></th>
	                            <th><strong>Entry Price</strong></th>
	                            <th><strong>Exit Price</strong></th>
	                            <th><strong>Quantity</strong></th>
	                            <th><strong>Profit Target</strong></th>
	                            <th><strong>Sell Price</strong></th>
	                            <th><strong>Trail Price</strong></th>
	                            <th class="text-center"><strong>P/L</strong></th>
	                            <th class="text-center"><strong>Status</strong></th>
	                            <th class="text-center"><strong>Actions</strong></th>
	                        </tr>
	                    </thead>
                        <tbody>';
        if (count($orders_arr) > 0) {
            foreach ($orders_arr as $key => $value) {

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                $response .= '<tr>
                            <td class="center">
                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="' . $value['_id'] . '"><i class="fa fa-eye"></i></button>
                            </td>
                            <td>' . $value['symbol'] . '</td>
                            <td>' . num($value['purchased_price']) . '</td>
                            <td>';
                if ($value['market_value'] != "") {
                    $response .= num($value['market_value']);
                }
                $response .= '</td>
                            <td>' . $value['quantity'] . '</td>
                            <td>';
                if ($value['profit_type'] == 'percentage') {
                    $response .= $value['sell_profit_percent'] . "%";
                } else {
                    $response .= num($value['sell_profit_price']);
                }
                $response .= '</td>
                            <td>' . num($value['sell_price']) . '</td>
                            <td>';
                if ($value['trail_check'] == 'yes') {
                    $response .= num($value['sell_trail_price']);
                } else {
                    $response .= "-";
                }
                $response .= '</td>
                            <td class="center">';

                if ($value['status'] != 'new' && $value['status'] != 'error') {

                    $market_value111 = num($value['market_value']);

                } else {

                    $market_value111 = num($market_value);
                }

                $current_data = $market_value111 - num($value['purchased_price']);
                $market_data = ($current_data * 100 / $market_value111);

                $market_data = number_format((float) $market_data, 2, '.', '');

                if ($market_value111 > $value['purchased_price']) {
                    $class = 'success';
                } else {
                    $class = 'danger';
                }

                if ($value['profit_type'] == 'percentage') {

                    $response .= '<span class="text-' . $class . '"><b>' . $market_data . '%</b></span>';
                } else {

                    $response .= '<span class="text-' . $class . '"><b>' . $market_value111 . '</b></span>';
                }

                $response .= '</td>';

                if ($value['status'] == 'error') {
                    $status_cls = "danger";
                } else {
                    $status_cls = "success";
                }

                $response .= '<td class="center">
                            	<span class="label label-' . $status_cls . '">' . strtoupper($value['status']) . '</span>
                            	<span class="custom_refresh" data-id="' . $value['_id'] . '" order_id="' . $value['binance_order_id'] . '">
                            		<i class="fa fa-refresh" aria-hidden="true"></i>
                            	</span>
                            </td>

                            <td class="center">
                                <div class="btn-group btn-group-xs ">';
                if ($value['status'] == 'new' || $value['status'] == 'error') {
                    $response .= '<a href="' . SURL . 'admin/dashboard/edit-order/' . $value['_id'] . '" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>';
                }
                if ($value['status'] != 'FILLED') {
                    $response .= '<a href="' . SURL . 'admin/dashboard/delete-order/' . $value['_id'] . '/' . $value['binance_order_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>';
                }
                $response .= '</div>
                            </td>
                            <td class="text-center">';
                if ($value['status'] == 'new') {
                    $response .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';
                }
                $response .= '</td>
                            </tr>';
            }
        }
        $response .= '</tbody>
                    </table>';

        echo $response;
        exit;

    } //end autoload_market_data

    public function autoload_market_data2() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Get Orders
        $orders_arr = $this->mod_dashboard->get_orders();
        $filled_orders = array();
        $new_orders = array();
        $error_orders = array();
        $cancelled = array();

        foreach ($orders_arr as $key => $value) {
            if ($value['status'] == 'new') {
                $new_orders[] = $value;
            } elseif ($value['status'] == 'FILLED') {
                $filled_orders[] = $value;
            } elseif ($value['status'] == 'cancelled') {
                $cancelled[] = $value;
            } elseif ($value['status'] == 'error') {
                $error_orders[] = $value;
            }
        }
        $response = '<table class="table table-condensed">
	                    <thead>
	                        <tr>
	                            <th></th>
	                            <th><strong>Coin</strong></th>
	                            <th><strong>Entry Price</strong></th>
	                            <th><strong>Exit Price</strong></th>
	                            <th><strong>Quantity</strong></th>
	                            <th><strong>Profit Target</strong></th>
	                            <th><strong>Sell Price</strong></th>
	                            <th><strong>Trail Price</strong></th>
	                            <th class="text-center"><strong>P/L</strong></th>
	                            <th class="text-center"><strong>Status</strong></th>
	                            <th class="text-center"><strong>Actions</strong></th>
	                        </tr>
	                    </thead>
                        <tbody>';
        if (count($orders_arr) > 0) {
            foreach ($orders_arr as $key => $value) {

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                $response .= '<tr>
                            <td class="center">
                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="' . $value['_id'] . '"><i class="fa fa-eye"></i></button>
                            </td>
                            <td>' . $value['symbol'] . '</td>
                            <td>' . num($value['purchased_price']) . '</td>
                            <td>';
                if ($value['market_value'] != "") {
                    $response .= num($value['market_value']);
                }
                $response .= '</td>
                            <td>' . $value['quantity'] . '</td>
                            <td>';
                if ($value['profit_type'] == 'percentage') {
                    $response .= $value['sell_profit_percent'] . "%";
                } else {
                    $response .= num($value['sell_profit_price']);
                }
                $response .= '</td>
                            <td>' . num($value['sell_price']) . '</td>
                            <td>';
                if ($value['trail_check'] == 'yes') {
                    $response .= num($value['sell_trail_price']);
                } else {
                    $response .= "-";
                }
                $response .= '</td>
                            <td class="center">';

                if ($value['status'] != 'new' && $value['status'] != 'error') {

                    $market_value111 = num($value['market_value']);

                } else {

                    $market_value111 = num($market_value);
                }

                $current_data = $market_value111 - num($value['purchased_price']);
                $market_data = ($current_data * 100 / $market_value111);

                $market_data = number_format((float) $market_data, 2, '.', '');

                if ($market_value111 > $value['purchased_price']) {
                    $class = 'success';
                } else {
                    $class = 'danger';
                }

                if ($value['status'] == 'submitted') {

                    $response .= '<span class="text-' . $class . '"><b>-</b></span>';

                } elseif ($value['profit_type'] == 'percentage') {

                    $response .= '<span class="text-' . $class . '"><b>' . $market_data . '%</b></span>';
                } else {

                    $response .= '<span class="text-' . $class . '"><b>' . $market_value111 . '</b></span>';
                }

                $response .= '</td>';

                if ($value['status'] == 'error') {
                    $status_cls = "danger";
                } else {
                    $status_cls = "success";
                }

                $response .= '<td class="center">
                            	<span class="label label-' . $status_cls . '">' . strtoupper($value['status']) . '</span>
                            	<span class="custom_refresh" data-id="' . $value['_id'] . '" order_id="' . $value['binance_order_id'] . '">
                            		<i class="fa fa-refresh" aria-hidden="true"></i>
                            	</span>
                            </td>

                            <td class="center">
                                <div class="btn-group btn-group-xs ">';
                if ($value['status'] == 'new' || $value['status'] == 'error') {
                    $response .= '<a href="' . SURL . 'admin/dashboard/edit-order/' . $value['_id'] . '" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>';
                }
                if ($value['status'] != 'FILLED') {
                    $response .= '<a href="' . SURL . 'admin/dashboard/delete-order/' . $value['_id'] . '/' . $value['binance_order_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>';
                }
                $response .= '</div>
                            </td>
                            <td class="text-center">';
                if ($value['status'] == 'new') {
                    $response .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';
                }
                $response .= '</td>
                            </tr>';
            }
        }
        $response .= '</tbody>
                    </table>';

        $response1 = '<table class="table table-condensed">
	                    <thead>
	                        <tr>
	                            <th></th>
	                            <th><strong>Coin</strong></th>
	                            <th><strong>Entry Price</strong></th>
	                            <th><strong>Exit Price</strong></th>
	                            <th><strong>Quantity</strong></th>
	                            <th><strong>Profit Target</strong></th>
	                            <th><strong>Sell Price</strong></th>
	                            <th><strong>Trail Price</strong></th>
	                            <th class="text-center"><strong>P/L</strong></th>
	                            <th class="text-center"><strong>Status</strong></th>
	                            <th class="text-center"><strong>Actions</strong></th>
	                        </tr>
	                    </thead>
                        <tbody>';
        if (count($new_orders) > 0) {
            foreach ($new_orders as $key => $value) {

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                $response1 .= '<tr>
                            <td class="center">
                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="' . $value['_id'] . '"><i class="fa fa-eye"></i></button>
                            </td>
                            <td>' . $value['symbol'] . '</td>
                            <td>' . num($value['purchased_price']) . '</td>
                            <td>';
                if ($value['market_value'] != "") {
                    $response1 .= num($value['market_value']);
                }
                $response1 .= '</td>
                            <td>' . $value['quantity'] . '</td>
                            <td>';
                if ($value['profit_type'] == 'percentage') {
                    $response1 .= $value['sell_profit_percent'] . "%";
                } else {
                    $response1 .= num($value['sell_profit_price']);
                }
                $response1 .= '</td>
                            <td>' . num($value['sell_price']) . '</td>
                            <td>';
                if ($value['trail_check'] == 'yes') {
                    $response1 .= num($value['sell_trail_price']);
                } else {
                    $response1 .= "-";
                }
                $response1 .= '</td>
                            <td class="center">';

                if ($value['status'] != 'new' && $value['status'] != 'error') {

                    $market_value111 = num($value['market_value']);

                } else {

                    $market_value111 = num($market_value);
                }

                $current_data = $market_value111 - num($value['purchased_price']);
                $market_data = ($current_data * 100 / $market_value111);

                $market_data = number_format((float) $market_data, 2, '.', '');

                if ($market_value111 > $value['purchased_price']) {
                    $class = 'success';
                } else {
                    $class = 'danger';
                }

                if ($value['status'] == 'submitted') {

                    $response1 .= '<span class="text-' . $class . '"><b>-</b></span>';

                } elseif ($value['profit_type'] == 'percentage') {

                    $response1 .= '<span class="text-' . $class . '"><b>' . $market_data . '%</b></span>';
                } else {

                    $response1 .= '<span class="text-' . $class . '"><b>' . $market_value111 . '</b></span>';
                }

                $response1 .= '</td>';

                if ($value['status'] == 'error') {
                    $status_cls = "danger";
                } else {
                    $status_cls = "success";
                }

                $response1 .= '<td class="center">
                            	<span class="label label-' . $status_cls . '">' . strtoupper($value['status']) . '</span>
                            	<span class="custom_refresh" data-id="' . $value['_id'] . '" order_id="' . $value['binance_order_id'] . '">
                            		<i class="fa fa-refresh" aria-hidden="true"></i>
                            	</span>
                            </td>

                            <td class="center">
                                <div class="btn-group btn-group-xs ">';
                if ($value['status'] == 'new' || $value['status'] == 'error') {
                    $response1 .= '<a href="' . SURL . 'admin/dashboard/edit-order/' . $value['_id'] . '" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>';
                }
                if ($value['status'] != 'FILLED') {
                    $response1 .= '<a href="' . SURL . 'admin/dashboard/delete-order/' . $value['_id'] . '/' . $value['binance_order_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>';
                }
                $response1 .= '</div>
                            </td>
                            <td class="text-center">';
                if ($value['status'] == 'new') {
                    $response1 .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';
                }
                $response1 .= '</td>
                            </tr>';
            }
        }
        $response1 .= '</tbody>
                    </table>';

        $response2 = '<table class="table table-condensed">
	                    <thead>
	                        <tr>
	                            <th></th>
	                            <th><strong>Coin</strong></th>
	                            <th><strong>Entry Price</strong></th>
	                            <th><strong>Exit Price</strong></th>
	                            <th><strong>Quantity</strong></th>
	                            <th><strong>Profit Target</strong></th>
	                            <th><strong>Sell Price</strong></th>
	                            <th><strong>Trail Price</strong></th>
	                            <th class="text-center"><strong>P/L</strong></th>
	                            <th class="text-center"><strong>Status</strong></th>
	                            <th class="text-center"><strong>Actions</strong></th>
	                        </tr>
	                    </thead>
                        <tbody>';
        if (count($filled_orders) > 0) {
            foreach ($filled_orders as $key => $value) {

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                $response2 .= '<tr>
                            <td class="center">
                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="' . $value['_id'] . '"><i class="fa fa-eye"></i></button>
                            </td>
                            <td>' . $value['symbol'] . '</td>
                            <td>' . num($value['purchased_price']) . '</td>
                            <td>';
                if ($value['market_value'] != "") {
                    $response2 .= num($value['market_value']);
                }
                $response2 .= '</td>
                            <td>' . $value['quantity'] . '</td>
                            <td>';
                if ($value['profit_type'] == 'percentage') {
                    $response2 .= $value['sell_profit_percent'] . "%";
                } else {
                    $response2 .= num($value['sell_profit_price']);
                }
                $response2 .= '</td>
                            <td>' . num($value['sell_price']) . '</td>
                            <td>';
                if ($value['trail_check'] == 'yes') {
                    $response2 .= num($value['sell_trail_price']);
                } else {
                    $response2 .= "-";
                }
                $response2 .= '</td>
                            <td class="center">';

                if ($value['status'] != 'new' && $value['status'] != 'error') {

                    $market_value111 = num($value['market_value']);

                } else {

                    $market_value111 = num($market_value);
                }

                $current_data = $market_value111 - num($value['purchased_price']);
                $market_data = ($current_data * 100 / $market_value111);

                $market_data = number_format((float) $market_data, 2, '.', '');

                if ($market_value111 > $value['purchased_price']) {
                    $class = 'success';
                } else {
                    $class = 'danger';
                }

                if ($value['status'] == 'submitted') {

                    $response2 .= '<span class="text-' . $class . '"><b>-</b></span>';

                } elseif ($value['profit_type'] == 'percentage') {

                    $response2 .= '<span class="text-' . $class . '"><b>' . $market_data . '%</b></span>';
                } else {

                    $response2 .= '<span class="text-' . $class . '"><b>' . $market_value111 . '</b></span>';
                }

                $response2 .= '</td>';

                if ($value['status'] == 'error') {
                    $status_cls = "danger";
                } else {
                    $status_cls = "success";
                }

                $response2 .= '<td class="center">
                            	<span class="label label-' . $status_cls . '">' . strtoupper($value['status']) . '</span>
                            	<span class="custom_refresh" data-id="' . $value['_id'] . '" order_id="' . $value['binance_order_id'] . '">
                            		<i class="fa fa-refresh" aria-hidden="true"></i>
                            	</span>
                            </td>

                            <td class="center">
                                <div class="btn-group btn-group-xs ">';
                if ($value['status'] == 'new' || $value['status'] == 'error') {
                    $response2 .= '<a href="' . SURL . 'admin/dashboard/edit-order/' . $value['_id'] . '" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>';
                }
                if ($value['status'] != 'FILLED') {
                    $response2 .= '<a href="' . SURL . 'admin/dashboard/delete-order/' . $value['_id'] . '/' . $value['binance_order_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>';
                }
                $response2 .= '</div>
                            </td>
                            <td class="text-center">';
                if ($value['status'] == 'new') {
                    $response2 .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';
                }
                $response2 .= '</td>
                            </tr>';
            }
        }
        $response2 .= '</tbody>
                    </table>';

        $response3 = '<table class="table table-condensed">
	                    <thead>
	                        <tr>
	                            <th></th>
	                            <th><strong>Coin</strong></th>
	                            <th><strong>Entry Price</strong></th>
	                            <th><strong>Exit Price</strong></th>
	                            <th><strong>Quantity</strong></th>
	                            <th><strong>Profit Target</strong></th>
	                            <th><strong>Sell Price</strong></th>
	                            <th><strong>Trail Price</strong></th>
	                            <th class="text-center"><strong>P/L</strong></th>
	                            <th class="text-center"><strong>Status</strong></th>
	                            <th class="text-center"><strong>Actions</strong></th>
	                        </tr>
	                    </thead>
                        <tbody>';
        if (count($cancelled) > 0) {
            foreach ($cancelled as $key => $value) {

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                $response3 .= '<tr>
                            <td class="center">
                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="' . $value['_id'] . '"><i class="fa fa-eye"></i></button>
                            </td>
                            <td>' . $value['symbol'] . '</td>
                            <td>' . num($value['purchased_price']) . '</td>
                            <td>';
                if ($value['market_value'] != "") {
                    $response3 .= num($value['market_value']);
                }
                $response3 .= '</td>
                            <td>' . $value['quantity'] . '</td>
                            <td>';
                if ($value['profit_type'] == 'percentage') {
                    $response3 .= $value['sell_profit_percent'] . "%";
                } else {
                    $response3 .= num($value['sell_profit_price']);
                }
                $response3 .= '</td>
                            <td>' . num($value['sell_price']) . '</td>
                            <td>';
                if ($value['trail_check'] == 'yes') {
                    $response3 .= num($value['sell_trail_price']);
                } else {
                    $response3 .= "-";
                }
                $response3 .= '</td>
                            <td class="center">';

                if ($value['status'] != 'new' && $value['status'] != 'error') {

                    $market_value111 = num($value['market_value']);

                } else {

                    $market_value111 = num($market_value);
                }

                $current_data = $market_value111 - num($value['purchased_price']);
                $market_data = ($current_data * 100 / $market_value111);

                $market_data = number_format((float) $market_data, 2, '.', '');

                if ($market_value111 > $value['purchased_price']) {
                    $class = 'success';
                } else {
                    $class = 'danger';
                }

                if ($value['status'] == 'submitted') {

                    $response3 .= '<span class="text-' . $class . '"><b>-</b></span>';

                } elseif ($value['profit_type'] == 'percentage') {

                    $response3 .= '<span class="text-' . $class . '"><b>' . $market_data . '%</b></span>';
                } else {

                    $response3 .= '<span class="text-' . $class . '"><b>' . $market_value111 . '</b></span>';
                }

                $response3 .= '</td>';

                if ($value['status'] == 'error') {
                    $status_cls = "danger";
                } else {
                    $status_cls = "success";
                }

                $response3 .= '<td class="center">
                            	<span class="label label-' . $status_cls . '">' . strtoupper($value['status']) . '</span>
                            	<span class="custom_refresh" data-id="' . $value['_id'] . '" order_id="' . $value['binance_order_id'] . '">
                            		<i class="fa fa-refresh" aria-hidden="true"></i>
                            	</span>
                            </td>

                            <td class="center">
                                <div class="btn-group btn-group-xs ">';
                if ($value['status'] == 'new' || $value['status'] == 'error') {
                    $response3 .= '<a href="' . SURL . 'admin/dashboard/edit-order/' . $value['_id'] . '" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>';
                }
                if ($value['status'] != 'FILLED') {
                    $response3 .= '<a href="' . SURL . 'admin/dashboard/delete-order/' . $value['_id'] . '/' . $value['binance_order_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>';
                }
                $response3 .= '</div>
                            </td>
                            <td class="text-center">';
                if ($value['status'] == 'new') {
                    $response3 .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';
                }
                $response3 .= '</td>
                            </tr>';
            }
        }
        $response3 .= '</tbody>
                    </table>';

        $response4 = '<table class="table table-condensed">
	                    <thead>
	                        <tr>
	                            <th></th>
	                            <th><strong>Coin</strong></th>
	                            <th><strong>Entry Price</strong></th>
	                            <th><strong>Exit Price</strong></th>
	                            <th><strong>Quantity</strong></th>
	                            <th><strong>Profit Target</strong></th>
	                            <th><strong>Sell Price</strong></th>
	                            <th><strong>Trail Price</strong></th>
	                            <th class="text-center"><strong>P/L</strong></th>
	                            <th class="text-center"><strong>Status</strong></th>
	                            <th class="text-center"><strong>Actions</strong></th>
	                        </tr>
	                    </thead>
                        <tbody>';
        if (count($error_orders) > 0) {
            foreach ($error_orders as $key => $value) {

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                $response4 .= '<tr>
                            <td class="center">
                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="' . $value['_id'] . '"><i class="fa fa-eye"></i></button>
                            </td>
                            <td>' . $value['symbol'] . '</td>
                            <td>' . num($value['purchased_price']) . '</td>
                            <td>';
                if ($value['market_value'] != "") {
                    $response4 .= num($value['market_value']);
                }
                $response4 .= '</td>
                            <td>' . $value['quantity'] . '</td>
                            <td>';
                if ($value['profit_type'] == 'percentage') {
                    $response4 .= $value['sell_profit_percent'] . "%";
                } else {
                    $response4 .= num($value['sell_profit_price']);
                }
                $response4 .= '</td>
                            <td>' . num($value['sell_price']) . '</td>
                            <td>';
                if ($value['trail_check'] == 'yes') {
                    $response4 .= num($value['sell_trail_price']);
                } else {
                    $response4 .= "-";
                }
                $response4 .= '</td>
                            <td class="center">';

                if ($value['status'] != 'new' && $value['status'] != 'error') {

                    $market_value111 = num($value['market_value']);

                } else {

                    $market_value111 = num($market_value);
                }

                $current_data = $market_value111 - num($value['purchased_price']);
                $market_data = ($current_data * 100 / $market_value111);

                $market_data = number_format((float) $market_data, 2, '.', '');

                if ($market_value111 > $value['purchased_price']) {
                    $class = 'success';
                } else {
                    $class = 'danger';
                }

                if ($value['status'] == 'submitted') {

                    $response4 .= '<span class="text-' . $class . '"><b>-</b></span>';

                } elseif ($value['profit_type'] == 'percentage') {

                    $response4 .= '<span class="text-' . $class . '"><b>' . $market_data . '%</b></span>';
                } else {

                    $response4 .= '<span class="text-' . $class . '"><b>' . $market_value111 . '</b></span>';
                }

                $response4 .= '</td>';

                if ($value['status'] == 'error') {
                    $status_cls = "danger";
                } else {
                    $status_cls = "success";
                }

                $response4 .= '<td class="center">
                            	<span class="label label-' . $status_cls . '">' . strtoupper($value['status']) . '</span>
                            	<span class="custom_refresh" data-id="' . $value['_id'] . '" order_id="' . $value['binance_order_id'] . '">
                            		<i class="fa fa-refresh" aria-hidden="true"></i>
                            	</span>
                            </td>

                            <td class="center">
                                <div class="btn-group btn-group-xs ">';
                if ($value['status'] == 'new' || $value['status'] == 'error') {
                    $response4 .= '<a href="' . SURL . 'admin/dashboard/edit-order/' . $value['_id'] . '" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>';
                }
                if ($value['status'] != 'FILLED') {
                    $response4 .= '<a href="' . SURL . 'admin/dashboard/delete-order/' . $value['_id'] . '/' . $value['binance_order_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>';
                }
                $response4 .= '</div>
                            </td>
                            <td class="text-center">';
                if ($value['status'] == 'new') {
                    $response4 .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';
                }
                $response4 .= '</td>
                            </tr>';
            }
        }
        $response4 .= '</tbody>
                    </table>';

        $count_new_arr = count($new_orders);
        $count_filled_arr = count($filled_orders);
        $count_cancelled_arr = count($cancelled);
        $count_error_arr = count($error_orders);
        $count_orders_arr = count($orders_arr);

        echo $response . '|' . $response1 . '|' . $response2 . '|' . $response3 . '|' . $response4 . "|" . $count_new_arr . "|" . $count_filled_arr . "|" . $count_cancelled_arr . "|" . $count_error_arr . "|" . $count_orders_arr;
        exit;

    } //end autoload_market_data

    public function autoload_market_buy_data() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Get Buy Orders
        $return_data = $this->mod_dashboard->get_buy_orders();

        $orders_arr = $return_data['fullarray'];
        $total_buy_amount = $return_data['total_buy_amount'];

        $response = '<table class="table table-condensed">
	                    <thead>
	                        <tr>
	                            <th></th>
	                            <th><strong>Coin</strong></th>
	                            <th><strong>Price</strong></th>
	                            <th><strong>Trail Price</strong></th>
	                            <th><strong>Quantity</strong></th>
	                            <th class="text-center"><strong>P/L</strong></th>
	                            <th class="text-center"><strong>Market(%)</strong></th>
	                            <th class="text-center"><strong>Status</strong></th>
	                            <th class="text-center"><strong>Profit(%)</strong></th>
	                            <th class="text-center"><strong>Actions</strong></th>
	                        </tr>
	                    </thead>
                        <tbody>';
        if (count($orders_arr) > 0) {
            foreach ($orders_arr as $key => $value) {

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                if ($value['status'] != 'new') {
                    $market_value333 = num($value['market_value']);
                } else {
                    $market_value333 = num($market_value);
                }

                if ($value['status'] == 'new') {
                    $current_order_price = num($value['price']);
                } else {
                    $current_order_price = num($value['market_value']);
                }

                $current_data = $market_value333 - $current_order_price;
                $market_data = ($current_data * 100 / $market_value333);

                $market_data = number_format((float) $market_data, 2, '.', '');

                if ($market_value333 > $current_order_price) {
                    $class = 'success';
                } else {
                    $class = 'danger';
                }

                $response .= '<tr>
                            <td class="center">
                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="' . $value['_id'] . '"><i class="fa fa-eye"></i></button>
                            </td>
                            <td>' . $value['symbol'] . '</td>
                            <td>' . num($value['price']) . '</td>
                            <td>';
                if ($value['trail_check'] == 'yes') {
                    $response .= num($value['buy_trail_price']);
                } else {
                    $response .= "-";
                }
                $response .= '</td>
                            <td>' . $value['quantity'] . '</td>
                            <td class="center"><b>' . num($market_value333) . '</b></td>';

                if ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes') {

                    $response .= '<td class="center"><span class="text-' . $class . '"><b>' . $market_data . '%</b></span></td>';

                } else {

                    $response .= '<td class="center"><span class="text-default"><b>-</b></span></td>';
                }

                $response .= '<td class="center">';

                $response .= '<span class="label label-success">' . ucfirst($value['status']) . '</span>
                            			  <span class="custom_refresh" data-id="' . $value['_id'] . '" order_id="' . $value['binance_order_id'] . '">
		                            		<i class="fa fa-refresh" aria-hidden="true"></i>
		                            	  </span>';

                $response .= '</td>

                            <td class="center">';

                if ($value['market_sold_price'] != "") {

                    $market_sold_price = num($value['market_sold_price']);

                    $current_data2222 = $market_sold_price - $current_order_price;
                    $profit_data = ($current_data2222 * 100 / $market_sold_price);

                    $profit_data = number_format((float) $profit_data, 2, '.', '');

                    if ($market_sold_price > $current_order_price) {
                        $class222 = 'success';
                    } else {
                        $class222 = 'danger';
                    }

                    $response .= '<span class="text-' . $class222 . '">
		                        				<b>' . $profit_data . '%</b>
		                        			  </span>';
                } else {

                    $response .= '<span class="text-default">
		                        					<b>-</b>
		                        			   </span>';
                }

                $response .= '</td>

                            <td class="center">
                                <div class="btn-group btn-group-xs ">';
                if ($value['status'] == 'new') {
                    $response .= '<a href="' . SURL . 'admin/dashboard/edit-buy-order/' . $value['_id'] . '" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>';
                }
                if ($value['status'] != 'FILLED') {
                    $response .= '<a href="' . SURL . 'admin/dashboard/delete-buy-order/' . $value['_id'] . '/' . $value['binance_order_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>';
                }

                if ($value['status'] == 'FILLED') {

                    if ($value['is_sell_order'] == 'yes') {
                        $response .= '<button class="btn btn-info">Submited For Sell</button>';
                    } elseif ($value['is_sell_order'] == 'sold') {
                        $response .= '<button class="btn btn-success">Sold</button>';
                    } else {
                        $response .= '<a href="' . SURL . 'admin/dashboard/add-order/' . $value['_id'] . '" class="btn btn-warning" target="_blank">Sell Now</a>';
                    }

                }

                $response .= '</div>
                            </td>



                            <td class="text-center">';
                if ($value['status'] == 'new') {

                    $response .= '<button class="btn btn-danger buy_now_btn" id="' . $value['_id'] . '" data-id="' . $value['_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Buy Now</button>';
                }
                $response .= '</td>
                            </tr>';
            }
        }
        $response .= '</tbody>
                    </table>';

        echo $response;
        exit;

    } //end autoload_market_buy_data

    public function autoload_market_buy_data2() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Get Buy Orders
        $return_data = $this->mod_dashboard->get_buy_orders();

        $orders_arr = $return_data['fullarray'];
        $total_buy_amount = $return_data['total_buy_amount'];
        $filled_orders = array();
        $new_orders = array();
        $error_orders = array();
        $cancelled = array();
        $submitted = array();
        $open_trades = array();
        $sold_trades = array();
        foreach ($orders_arr as $key => $value) {
            if ($value['status'] == 'new') {
                $new_orders[] = $value;
            } elseif ($value['status'] == 'FILLED') {
                $filled_orders[] = $value;
                if ($value['is_sell_order'] == 'yes') {
                    $open_trades[] = $value;
                }
                if ($value['is_sell_order'] == 'sold') {
                    $sold_trades[] = $value;
                }
            } elseif ($value['status'] == 'canceled') {
                $cancelled[] = $value;
            } elseif ($value['status'] == 'error') {
                $error_orders[] = $value;
            } elseif ($value['status'] == 'submitted') {
                $submitted[] = $value;
                $open_trades[] = $value;
            }
        }

        $response = '<table class="table table-condensed">
	                    <thead>
	                        <tr>
	                            <th></th>
	                            <th><strong>Coin</strong></th>
	                            <th><strong>Price</strong></th>
	                            <th><strong>Trail Price</strong></th>
	                            <th><strong>Quantity</strong></th>
	                            <th class="text-center"><strong>P/L</strong></th>
	                            <th class="text-center"><strong>Market(%)</strong></th>
	                            <th class="text-center"><strong>Status</strong></th>
	                            <th class="text-center"><strong>Profit(%)</strong></th>
	                            <th class="text-center"><strong>Actions</strong></th>
	                        </tr>
	                    </thead>
                        <tbody>';
        if (count($orders_arr) > 0) {
            foreach ($orders_arr as $key => $value) {

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                if ($value['status'] != 'new' && $value['status'] != 'error') {
                    $market_value333 = num($value['market_value']);
                } else {
                    $market_value333 = num($market_value);
                }

                if ($value['status'] == 'new') {
                    $current_order_price = num($value['price']);
                } else {
                    $current_order_price = num($value['market_value']);
                }

                $current_data = $market_value333 - $current_order_price;
                $market_data = ($current_data * 100 / $market_value333);

                $market_data = number_format((float) $market_data, 2, '.', '');

                if ($market_value333 > $current_order_price) {
                    $class = 'success';
                } else {
                    $class = 'danger';
                }

                $response .= '<tr>
                            <td class="center">
                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="' . $value['_id'] . '"><i class="fa fa-eye"></i></button>
                            </td>
                            <td>' . $value['symbol'] . '</td>
                            <td>' . num($value['price']) . '</td>
                            <td>';
                if ($value['trail_check'] == 'yes') {
                    $response .= num($value['buy_trail_price']);
                } else {
                    $response .= "-";
                }
                $response .= '</td>
                            <td>' . $value['quantity'] . '</td>
                            <td class="center"><b>' . num($market_value333) . '</b></td>';

                if ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes') {

                    $response .= '<td class="center"><span class="text-' . $class . '"><b>' . $market_data . '%</b></span></td>';

                } else {

                    $response .= '<td class="center"><span class="text-default"><b>-</b></span></td>';
                }

                $response .= '<td class="center">';

                if ($value['status'] == 'FILLED' && $value['is_sell_order'] == 'yes') {

                    $response .= '<span class="label label-info">SUBMITTED FOR SELL</span>';

                } else {

                    if ($value['status'] == 'error') {
                        $status_cls = "danger";
                    } else {
                        $status_cls = "success";
                    }

                    $response .= '<span class="label label-' . $status_cls . '">' . strtoupper($value['status']) . '</span>';
                }

                $response .= '<span class="custom_refresh" data-id="' . $value['_id'] . '" order_id="' . $value['binance_order_id'] . '">
		                            		<i class="fa fa-refresh" aria-hidden="true"></i>
		                            	  </span>';

                $response .= '</td>

                            <td class="center">';

                if ($value['market_sold_price'] != "") {

                    $market_sold_price = num($value['market_sold_price']);

                    $current_data2222 = $market_sold_price - $current_order_price;
                    $profit_data = ($current_data2222 * 100 / $market_sold_price);

                    $profit_data = number_format((float) $profit_data, 2, '.', '');

                    if ($market_sold_price > $current_order_price) {
                        $class222 = 'success';
                    } else {
                        $class222 = 'danger';
                    }

                    $response .= '<span class="text-' . $class222 . '">
		                        				<b>' . $profit_data . '%</b>
		                        			  </span>';
                } else {

                    if ($value['status'] == 'FILLED') {

                        if ($value['is_sell_order'] == 'yes') {

                            $current_data = num($market_value) - num($value['market_value']);
                            $market_data = ($current_data * 100 / $market_value);

                            $market_data = number_format((float) $market_data, 2, '.', '');

                            if ($market_value > $value['market_value']) {
                                $class = 'success';
                            } else {
                                $class = 'danger';
                            }

                            $response .= '<span class="text-' . $class . '"><b>' . $market_data . '%</b></span>';

                        } else {

                            $response .= '<span class="text-default"><b>-</b></span>';
                        }

                    } else {

                        $response .= '<span class="text-default"><b>-</b></span>';

                    }

                }

                $response .= '</td>

                            <td class="center">
                                <div class="btn-group btn-group-xs ">';
                if ($value['status'] == 'new' || $value['status'] == 'error') {
                    $response .= '<a href="' . SURL . 'admin/dashboard/edit-buy-order/' . $value['_id'] . '" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>';
                }
                if ($value['status'] != 'FILLED') {
                    $response .= '<a href="' . SURL . 'admin/dashboard/delete-buy-order/' . $value['_id'] . '/' . $value['binance_order_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>';
                }

                if ($value['status'] == 'FILLED') {

                    if ($value['is_sell_order'] == 'yes') {

                        $response .= '<a href="' . SURL . 'admin/dashboard/edit-order/' . $value['sell_order_id'] . '" class="btn btn-inverse" target="_blank"><i class="fa fa-pencil"></i></a>';
                        $response .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';

                    } elseif ($value['is_sell_order'] == 'sold') {
                        $response .= '<button class="btn btn-success">Sold</button>';
                    } else {
                        $response .= '<a href="' . SURL . 'admin/dashboard/add-order/' . $value['_id'] . '" class="btn btn-warning" target="_blank">Set For Sell</a>';
                        $response .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';
                    }

                }

                $response .= '</div>
                            </td>



                            <td class="text-center">';
                if ($value['status'] == 'new') {

                    $response .= '<button class="btn btn-danger buy_now_btn" id="' . $value['_id'] . '" data-id="' . $value['_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Buy Now</button>';
                }
                $response .= '</td>
                            </tr>';
            }
        }
        $response .= '</tbody>
                    </table>';

        $response1 = '<table class="table table-condensed">
                        <thead>
                            <tr>
                                <th></th>
                                <th><strong>Coin</strong></th>
                                <th><strong>Price</strong></th>
                                <th><strong>Trail Price</strong></th>
                                <th><strong>Quantity</strong></th>
                                <th class="text-center"><strong>P/L</strong></th>
                                <th class="text-center"><strong>Market(%)</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Profit(%)</strong></th>
                                <th class="text-center"><strong>Actions</strong></th>
                            </tr>
                        </thead>
                        <tbody>';
        if (count($new_orders) > 0) {
            foreach ($new_orders as $key => $value) {

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                if ($value['status'] != 'new') {
                    $market_value333 = num($value['market_value']);
                } else {
                    $market_value333 = num($market_value);
                }

                if ($value['status'] == 'new') {
                    $current_order_price = num($value['price']);
                } else {
                    $current_order_price = num($value['market_value']);
                }

                $current_data = $market_value333 - $current_order_price;
                $market_data = ($current_data * 100 / $market_value333);

                $market_data = number_format((float) $market_data, 2, '.', '');

                if ($market_value333 > $current_order_price) {
                    $class = 'success';
                } else {
                    $class = 'danger';
                }

                $response1 .= '<tr>
                            <td class="center">
                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="' . $value['_id'] . '"><i class="fa fa-eye"></i></button>
                            </td>
                            <td>' . $value['symbol'] . '</td>
                            <td>' . num($value['price']) . '</td>
                            <td>';
                if ($value['trail_check'] == 'yes') {
                    $response1 .= num($value['buy_trail_price']);
                } else {
                    $response1 .= "-";
                }
                $response1 .= '</td>
                            <td>' . $value['quantity'] . '</td>
                            <td class="center"><b>' . num($market_value333) . '</b></td>';

                if ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes') {

                    $response1 .= '<td class="center"><span class="text-' . $class . '"><b>' . $market_data . '%</b></span></td>';

                } else {

                    $response1 .= '<td class="center"><span class="text-default"><b>-</b></span></td>';
                }

                $response1 .= '<td class="center">';

                if ($value['status'] == 'FILLED' && $value['is_sell_order'] == 'yes') {

                    $response1 .= '<span class="label label-info">SUBMITTED FOR SELL</span>';

                } else {

                    $response1 .= '<span class="label label-success">' . strtoupper($value['status']) . '</span>';
                }

                $response1 .= '<span class="custom_refresh" data-id="' . $value['_id'] . '" order_id="' . $value['binance_order_id'] . '">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>
                                          </span>';

                $response1 .= '</td>

                            <td class="center">';

                if ($value['market_sold_price'] != "") {

                    $market_sold_price = num($value['market_sold_price']);

                    $current_data2222 = $market_sold_price - $current_order_price;
                    $profit_data = ($current_data2222 * 100 / $market_sold_price);

                    $profit_data = number_format((float) $profit_data, 2, '.', '');

                    if ($market_sold_price > $current_order_price) {
                        $class222 = 'success';
                    } else {
                        $class222 = 'danger';
                    }

                    $response1 .= '<span class="text-' . $class222 . '">
                                                <b>' . $profit_data . '%</b>
                                              </span>';
                } else {

                    $response1 .= '<span class="text-default">
                                                    <b>-</b>
                                               </span>';
                }

                $response1 .= '</td>

                            <td class="center">
                                <div class="btn-group btn-group-xs ">';
                if ($value['status'] == 'new') {
                    $response1 .= '<a href="' . SURL . 'admin/dashboard/edit-buy-order/' . $value['_id'] . '" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>';
                }
                if ($value['status'] != 'FILLED') {
                    $response1 .= '<a href="' . SURL . 'admin/dashboard/delete-buy-order/' . $value['_id'] . '/' . $value['binance_order_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>';
                }

                if ($value['status'] == 'FILLED') {

                    if ($value['is_sell_order'] == 'yes') {

                        $response1 .= '<a href="' . SURL . 'admin/dashboard/edit-order/' . $value['sell_order_id'] . '" class="btn btn-inverse" target="_blank"><i class="fa fa-pencil"></i></a>';
                        $response1 .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';

                    } elseif ($value['is_sell_order'] == 'sold') {
                        $response1 .= '<button class="btn btn-success">Sold</button>';
                    } else {
                        $response1 .= '<a href="' . SURL . 'admin/dashboard/add-order/' . $value['_id'] . '" class="btn btn-warning" target="_blank">Set For Sell</a>';
                        $response1 .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';
                    }

                }

                $response1 .= '</div>
                            </td>



                            <td class="text-center">';
                if ($value['status'] == 'new') {

                    $response1 .= '<button class="btn btn-danger buy_now_btn" id="' . $value['_id'] . '" data-id="' . $value['_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Buy Now</button>';
                }
                $response1 .= '</td>
                            </tr>';
            }
        }
        $response1 .= '</tbody>
                    </table>';

        $response2 = '<table class="table table-condensed">
                        <thead>
                            <tr>
                                <th></th>
                                <th><strong>Coin</strong></th>
                                <th><strong>Price</strong></th>
                                <th><strong>Trail Price</strong></th>
                                <th><strong>Quantity</strong></th>
                                <th class="text-center"><strong>P/L</strong></th>
                                <th class="text-center"><strong>Market(%)</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Profit(%)</strong></th>
                                <th class="text-center"><strong>Actions</strong></th>
                            </tr>
                        </thead>
                        <tbody>';
        if (count($filled_orders) > 0) {
            foreach ($filled_orders as $key => $value) {

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                if ($value['status'] != 'new') {
                    $market_value333 = num($value['market_value']);
                } else {
                    $market_value333 = num($market_value);
                }

                if ($value['status'] == 'new') {
                    $current_order_price = num($value['price']);
                } else {
                    $current_order_price = num($value['market_value']);
                }

                $current_data = $market_value333 - $current_order_price;
                $market_data = ($current_data * 100 / $market_value333);

                $market_data = number_format((float) $market_data, 2, '.', '');

                if ($market_value333 > $current_order_price) {
                    $class = 'success';
                } else {
                    $class = 'danger';
                }

                $response2 .= '<tr>
                            <td class="center">
                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="' . $value['_id'] . '"><i class="fa fa-eye"></i></button>
                            </td>
                            <td>' . $value['symbol'] . '</td>
                            <td>' . num($value['price']) . '</td>
                            <td>';
                if ($value['trail_check'] == 'yes') {
                    $response2 .= num($value['buy_trail_price']);
                } else {
                    $response2 .= "-";
                }
                $response2 .= '</td>
                            <td>' . $value['quantity'] . '</td>
                            <td class="center"><b>' . num($market_value333) . '</b></td>';

                if ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes') {

                    $response2 .= '<td class="center"><span class="text-' . $class . '"><b>' . $market_data . '%</b></span></td>';

                } else {

                    $response2 .= '<td class="center"><span class="text-default"><b>-</b></span></td>';
                }

                $response2 .= '<td class="center">';

                if ($value['status'] == 'FILLED' && $value['is_sell_order'] == 'yes') {

                    $response2 .= '<span class="label label-info">SUBMITTED FOR SELL</span>';

                } else {

                    $response2 .= '<span class="label label-success">' . strtoupper($value['status']) . '</span>';
                }

                $response2 .= '<span class="custom_refresh" data-id="' . $value['_id'] . '" order_id="' . $value['binance_order_id'] . '">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>
                                          </span>';

                $response2 .= '</td>

                            <td class="center">';

                if ($value['market_sold_price'] != "") {

                    $market_sold_price = num($value['market_sold_price']);

                    $current_data2222 = $market_sold_price - $current_order_price;
                    $profit_data = ($current_data2222 * 100 / $market_sold_price);

                    $profit_data = number_format((float) $profit_data, 2, '.', '');

                    if ($market_sold_price > $current_order_price) {
                        $class222 = 'success';
                    } else {
                        $class222 = 'danger';
                    }

                    $response2 .= '<span class="text-' . $class222 . '">
                                                <b>' . $profit_data . '%</b>
                                              </span>';
                } else {

                    if ($value['status'] == 'FILLED') {

                        if ($value['is_sell_order'] == 'yes') {

                            $current_data = num($market_value) - num($value['market_value']);
                            $market_data = ($current_data * 100 / $market_value);

                            $market_data = number_format((float) $market_data, 2, '.', '');

                            if ($market_value > $value['market_value']) {
                                $class = 'success';
                            } else {
                                $class = 'danger';
                            }

                            $response2 .= '<span class="text-' . $class . '"><b>' . $market_data . '%</b></span>';

                        } else {

                            $response2 .= '<span class="text-default"><b>-</b></span>';
                        }

                    } else {

                        $response2 .= '<span class="text-default"><b>-</b></span>';

                    }
                }

                $response2 .= '</td>

                            <td class="center">
                                <div class="btn-group btn-group-xs ">';
                if ($value['status'] == 'new') {
                    $response2 .= '<a href="' . SURL . 'admin/dashboard/edit-buy-order/' . $value['_id'] . '" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>';
                }
                if ($value['status'] != 'FILLED') {
                    $response2 .= '<a href="' . SURL . 'admin/dashboard/delete-buy-order/' . $value['_id'] . '/' . $value['binance_order_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>';
                }

                if ($value['status'] == 'FILLED') {

                    if ($value['is_sell_order'] == 'yes') {

                        $response2 .= '<a href="' . SURL . 'admin/dashboard/edit-order/' . $value['sell_order_id'] . '" class="btn btn-inverse" target="_blank"><i class="fa fa-pencil"></i></a>';
                        $response2 .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';

                    } elseif ($value['is_sell_order'] == 'sold') {
                        $response2 .= '<button class="btn btn-success">Sold</button>';
                    } else {
                        $response2 .= '<a href="' . SURL . 'admin/dashboard/add-order/' . $value['_id'] . '" class="btn btn-warning" target="_blank">Set For Sell</a>';
                        $response2 .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';
                    }

                }

                $response2 .= '</div>
                            </td>



                            <td class="text-center">';
                if ($value['status'] == 'new') {

                    $response2 .= '<button class="btn btn-danger buy_now_btn" id="' . $value['_id'] . '" data-id="' . $value['_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Buy Now</button>';
                }
                $response2 .= '</td>
                            </tr>';
            }
        }
        $response2 .= '</tbody>
                    </table>';

        $response3 = '<table class="table table-condensed">
                        <thead>
                            <tr>
                                <th></th>
                                <th><strong>Coin</strong></th>
                                <th><strong>Price</strong></th>
                                <th><strong>Trail Price</strong></th>
                                <th><strong>Quantity</strong></th>
                                <th class="text-center"><strong>P/L</strong></th>
                                <th class="text-center"><strong>Market(%)</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Profit(%)</strong></th>
                                <th class="text-center"><strong>Actions</strong></th>
                            </tr>
                        </thead>
                        <tbody>';
        if (count($cancelled) > 0) {
            foreach ($cancelled as $key => $value) {

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                if ($value['status'] != 'new') {
                    $market_value333 = num($value['market_value']);
                } else {
                    $market_value333 = num($market_value);
                }

                if ($value['status'] == 'new') {
                    $current_order_price = num($value['price']);
                } else {
                    $current_order_price = num($value['market_value']);
                }

                $current_data = $market_value333 - $current_order_price;
                $market_data = ($current_data * 100 / $market_value333);

                $market_data = number_format((float) $market_data, 2, '.', '');

                if ($market_value333 > $current_order_price) {
                    $class = 'success';
                } else {
                    $class = 'danger';
                }

                $response3 .= '<tr>
                            <td class="center">
                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="' . $value['_id'] . '"><i class="fa fa-eye"></i></button>
                            </td>
                            <td>' . $value['symbol'] . '</td>
                            <td>' . num($value['price']) . '</td>
                            <td>';
                if ($value['trail_check'] == 'yes') {
                    $response3 .= num($value['buy_trail_price']);
                } else {
                    $response3 .= "-";
                }
                $response3 .= '</td>
                            <td>' . $value['quantity'] . '</td>
                            <td class="center"><b>' . num($market_value333) . '</b></td>';

                if ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes') {

                    $response3 .= '<td class="center"><span class="text-' . $class . '"><b>' . $market_data . '%</b></span></td>';

                } else {

                    $response3 .= '<td class="center"><span class="text-default"><b>-</b></span></td>';
                }

                $response3 .= '<td class="center">';

                if ($value['status'] == 'FILLED' && $value['is_sell_order'] == 'yes') {

                    $response3 .= '<span class="label label-info">SUBMITTED FOR SELL</span>';

                } else {

                    $response3 .= '<span class="label label-success">' . strtoupper($value['status']) . '</span>';
                }

                $response3 .= '<span class="custom_refresh" data-id="' . $value['_id'] . '" order_id="' . $value['binance_order_id'] . '">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>
                                          </span>';

                $response3 .= '</td>

                            <td class="center">';

                if ($value['market_sold_price'] != "") {

                    $market_sold_price = num($value['market_sold_price']);

                    $current_data2222 = $market_sold_price - $current_order_price;
                    $profit_data = ($current_data2222 * 100 / $market_sold_price);

                    $profit_data = number_format((float) $profit_data, 2, '.', '');

                    if ($market_sold_price > $current_order_price) {
                        $class222 = 'success';
                    } else {
                        $class222 = 'danger';
                    }

                    $response3 .= '<span class="text-' . $class222 . '">
                                                <b>' . $profit_data . '%</b>
                                              </span>';
                } else {

                    $response3 .= '<span class="text-default">
                                                    <b>-</b>
                                               </span>';
                }

                $response3 .= '</td>

                            <td class="center">
                                <div class="btn-group btn-group-xs ">';
                if ($value['status'] == 'new') {
                    $response3 .= '<a href="' . SURL . 'admin/dashboard/edit-buy-order/' . $value['_id'] . '" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>';
                }
                if ($value['status'] != 'FILLED') {
                    $response3 .= '<a href="' . SURL . 'admin/dashboard/delete-buy-order/' . $value['_id'] . '/' . $value['binance_order_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>';
                }

                if ($value['status'] == 'FILLED') {

                    if ($value['is_sell_order'] == 'yes') {

                        $response3 .= '<a href="' . SURL . 'admin/dashboard/edit-order/' . $value['sell_order_id'] . '" class="btn btn-inverse" target="_blank"><i class="fa fa-pencil"></i></a>';

                    } elseif ($value['is_sell_order'] == 'sold') {
                        $response3 .= '<button class="btn btn-success">Sold</button>';
                    } else {
                        $response3 .= '<a href="' . SURL . 'admin/dashboard/add-order/' . $value['_id'] . '" class="btn btn-warning" target="_blank">Set For Sell</a>';
                        $response2 .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';
                    }

                }

                $response3 .= '</div>
                            </td>



                            <td class="text-center">';
                if ($value['status'] == 'new') {

                    $response3 .= '<button class="btn btn-danger buy_now_btn" id="' . $value['_id'] . '" data-id="' . $value['_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Buy Now</button>';
                }
                $response3 .= '</td>
                            </tr>';
            }
        }
        $response3 .= '</tbody>
                    </table>';

        $response4 = '<table class="table table-condensed">
                        <thead>
                            <tr>
                                <th></th>
                                <th><strong>Coin</strong></th>
                                <th><strong>Price</strong></th>
                                <th><strong>Trail Price</strong></th>
                                <th><strong>Quantity</strong></th>
                                <th class="text-center"><strong>P/L</strong></th>
                                <th class="text-center"><strong>Market(%)</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Profit(%)</strong></th>
                                <th class="text-center"><strong>Actions</strong></th>
                            </tr>
                        </thead>
                        <tbody>';
        if (count($error_orders) > 0) {
            foreach ($error_orders as $key => $value) {

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                if ($value['status'] != 'new' && $value['status'] != 'error') {
                    $market_value333 = num($value['market_value']);
                } else {
                    $market_value333 = num($market_value);
                }

                if ($value['status'] == 'new') {
                    $current_order_price = num($value['price']);
                } else {
                    $current_order_price = num($value['market_value']);
                }

                $current_data = $market_value333 - $current_order_price;
                $market_data = ($current_data * 100 / $market_value333);

                $market_data = number_format((float) $market_data, 2, '.', '');

                if ($market_value333 > $current_order_price) {
                    $class = 'success';
                } else {
                    $class = 'danger';
                }

                $response4 .= '<tr>
                            <td class="center">
                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="' . $value['_id'] . '"><i class="fa fa-eye"></i></button>
                            </td>
                            <td>' . $value['symbol'] . '</td>
                            <td>' . num($value['price']) . '</td>
                            <td>';
                if ($value['trail_check'] == 'yes') {
                    $response4 .= num($value['buy_trail_price']);
                } else {
                    $response4 .= "-";
                }
                $response4 .= '</td>
                            <td>' . $value['quantity'] . '</td>
                            <td class="center"><b>' . num($market_value333) . '</b></td>';

                if ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes') {

                    $response4 .= '<td class="center"><span class="text-' . $class . '"><b>' . $market_data . '%</b></span></td>';

                } else {

                    $response4 .= '<td class="center"><span class="text-default"><b>-</b></span></td>';
                }

                $response4 .= '<td class="center">';

                if ($value['status'] == 'FILLED' && $value['is_sell_order'] == 'yes') {

                    $response4 .= '<span class="label label-info">SUBMITTED FOR SELL</span>';

                } else {

                    if ($value['status'] == 'error') {
                        $status_cls = "danger";
                    } else {
                        $status_cls = "success";
                    }

                    $response4 .= '<span class="label label-' . $status_cls . '">' . strtoupper($value['status']) . '</span>';
                }

                $response4 .= '<span class="custom_refresh" data-id="' . $value['_id'] . '" order_id="' . $value['binance_order_id'] . '">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>
                                          </span>';

                $response4 .= '</td>

                            <td class="center">';

                if ($value['market_sold_price'] != "") {

                    $market_sold_price = num($value['market_sold_price']);

                    $current_data2222 = $market_sold_price - $current_order_price;
                    $profit_data = ($current_data2222 * 100 / $market_sold_price);

                    $profit_data = number_format((float) $profit_data, 2, '.', '');

                    if ($market_sold_price > $current_order_price) {
                        $class222 = 'success';
                    } else {
                        $class222 = 'danger';
                    }

                    $response4 .= '<span class="text-' . $class222 . '">
                                                <b>' . $profit_data . '%</b>
                                              </span>';
                } else {

                    $response4 .= '<span class="text-default">
                                                    <b>-</b>
                                               </span>';
                }

                $response4 .= '</td>

                            <td class="center">
                                <div class="btn-group btn-group-xs ">';
                if ($value['status'] == 'new' || $value['status'] == 'error') {
                    $response4 .= '<a href="' . SURL . 'admin/dashboard/edit-buy-order/' . $value['_id'] . '" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>';
                }
                if ($value['status'] != 'FILLED') {
                    $response4 .= '<a href="' . SURL . 'admin/dashboard/delete-buy-order/' . $value['_id'] . '/' . $value['binance_order_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>';
                }

                if ($value['status'] == 'FILLED') {

                    if ($value['is_sell_order'] == 'yes') {

                        $response4 .= '<a href="' . SURL . 'admin/dashboard/edit-order/' . $value['sell_order_id'] . '" class="btn btn-inverse" target="_blank"><i class="fa fa-pencil"></i></a>';
                        $response4 .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';

                    } elseif ($value['is_sell_order'] == 'sold') {
                        $response4 .= '<button class="btn btn-success">Sold</button>';
                    } else {
                        $response4 .= '<a href="' . SURL . 'admin/dashboard/add-order/' . $value['_id'] . '" class="btn btn-warning" target="_blank">Set For Sell</a>';
                        $response4 .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';
                    }

                }

                $response4 .= '</div>
                            </td>



                            <td class="text-center">';
                if ($value['status'] == 'new') {

                    $response4 .= '<button class="btn btn-danger buy_now_btn" id="' . $value['_id'] . '" data-id="' . $value['_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Buy Now</button>';
                }
                $response4 .= '</td>
                            </tr>';
            }
        }
        $response4 .= '</tbody>
                    </table>';

        $response5 = '<table class="table table-condensed">
                        <thead>
                            <tr>
                                <th></th>
                                <th><strong>Coin</strong></th>
                                <th><strong>Price</strong></th>
                                <th><strong>Trail Price</strong></th>
                                <th><strong>Quantity</strong></th>
                                <th class="text-center"><strong>P/L</strong></th>
                                <th class="text-center"><strong>Market(%)</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>Profit(%)</strong></th>
                                <th class="text-center"><strong>Actions</strong></th>
                            </tr>
                        </thead>
                        <tbody>';
        if (count($submitted) > 0) {
            foreach ($submitted as $key => $value) {

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                if ($value['status'] != 'new') {
                    $market_value333 = num($value['market_value']);
                } else {
                    $market_value333 = num($market_value);
                }

                if ($value['status'] == 'new') {
                    $current_order_price = num($value['price']);
                } else {
                    $current_order_price = num($value['market_value']);
                }

                $current_data = $market_value333 - $current_order_price;
                $market_data = ($current_data * 100 / $market_value333);

                $market_data = number_format((float) $market_data, 2, '.', '');

                if ($market_value333 > $current_order_price) {
                    $class = 'success';
                } else {
                    $class = 'danger';
                }

                $response5 .= '<tr>
                            <td class="center">
                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="' . $value['_id'] . '"><i class="fa fa-eye"></i></button>
                            </td>
                            <td>' . $value['symbol'] . '</td>
                            <td>' . num($value['price']) . '</td>
                            <td>';
                if ($value['trail_check'] == 'yes') {
                    $response5 .= num($value['buy_trail_price']);
                } else {
                    $response5 .= "-";
                }
                $response5 .= '</td>
                            <td>' . $value['quantity'] . '</td>
                            <td class="center"><b>' . num($market_value333) . '</b></td>';

                if ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes') {

                    $response5 .= '<td class="center"><span class="text-' . $class . '"><b>' . $market_data . '%</b></span></td>';

                } else {

                    $response5 .= '<td class="center"><span class="text-default"><b>-</b></span></td>';
                }

                $response5 .= '<td class="center">';

                if ($value['status'] == 'FILLED' && $value['is_sell_order'] == 'yes') {

                    $response5 .= '<span class="label label-info">SUBMITTED FOR SELL</span>';

                } else {

                    $response5 .= '<span class="label label-success">' . strtoupper($value['status']) . '</span>';
                }

                $response5 .= '<span class="custom_refresh" data-id="' . $value['_id'] . '" order_id="' . $value['binance_order_id'] . '">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>
                                          </span>';

                $response5 .= '</td>

                            <td class="center">';

                if ($value['market_sold_price'] != "") {

                    $market_sold_price = num($value['market_sold_price']);

                    $current_data2222 = $market_sold_price - $current_order_price;
                    $profit_data = ($current_data2222 * 100 / $market_sold_price);

                    $profit_data = number_format((float) $profit_data, 2, '.', '');

                    if ($market_sold_price > $current_order_price) {
                        $class222 = 'success';
                    } else {
                        $class222 = 'danger';
                    }

                    $response5 .= '<span class="text-' . $class222 . '">
                                                <b>' . $profit_data . '%</b>
                                              </span>';
                } else {

                    $response5 .= '<span class="text-default">
                                                    <b>-</b>
                                               </span>';
                }

                $response5 .= '</td>

                            <td class="center">
                                <div class="btn-group btn-group-xs ">';
                if ($value['status'] == 'new') {
                    $response5 .= '<a href="' . SURL . 'admin/dashboard/edit-buy-order/' . $value['_id'] . '" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>';
                }
                if ($value['status'] != 'FILLED') {
                    $response5 .= '<a href="' . SURL . 'admin/dashboard/delete-buy-order/' . $value['_id'] . '/' . $value['binance_order_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>';
                }

                if ($value['status'] == 'FILLED') {

                    if ($value['is_sell_order'] == 'yes') {

                        $response5 .= '<a href="' . SURL . 'admin/dashboard/edit-order/' . $value['sell_order_id'] . '" class="btn btn-inverse" target="_blank"><i class="fa fa-pencil"></i></a>';
                        $response5 .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';

                    } elseif ($value['is_sell_order'] == 'sold') {
                        $response5 .= '<button class="btn btn-success">Sold</button>';
                    } else {
                        $response5 .= '<a href="' . SURL . 'admin/dashboard/add-order/' . $value['_id'] . '" class="btn btn-warning" target="_blank">Set For Sell</a>';
                        $response5 .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';
                    }

                }

                $response5 .= '</div>
                            </td>



                            <td class="text-center">';
                if ($value['status'] == 'new') {

                    $response5 .= '<button class="btn btn-danger buy_now_btn" id="' . $value['_id'] . '" data-id="' . $value['_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Buy Now</button>';
                }
                $response5 .= '</td>
                            </tr>';
            }
        }
        $response5 .= '</tbody>
                    </table>';

        $response6 = '<table class="table table-condensed">
	                    <thead>
	                        <tr>
	                            <th></th>
	                            <th><strong>Coin</strong></th>
	                            <th><strong>Price</strong></th>
	                            <th><strong>Trail Price</strong></th>
	                            <th><strong>Quantity</strong></th>
	                            <th class="text-center"><strong>P/L</strong></th>
	                            <th class="text-center"><strong>Market(%)</strong></th>
	                            <th class="text-center"><strong>Status</strong></th>
	                            <th class="text-center"><strong>Profit(%)</strong></th>
	                            <th class="text-center"><strong>Actions</strong></th>
	                        </tr>
	                    </thead>
                        <tbody>';
        if (count($open_trades) > 0) {
            foreach ($open_trades as $key => $value) {

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                if ($value['status'] != 'new') {
                    $market_value333 = num($value['market_value']);
                } else {
                    $market_value333 = num($market_value);
                }

                if ($value['status'] == 'new') {
                    $current_order_price = num($value['price']);
                } else {
                    $current_order_price = num($value['market_value']);
                }

                $current_data = $market_value333 - $current_order_price;
                $market_data = ($current_data * 100 / $market_value333);

                $market_data = number_format((float) $market_data, 2, '.', '');

                if ($market_value333 > $current_order_price) {
                    $class = 'success';
                } else {
                    $class = 'danger';
                }

                $response6 .= '<tr>
                            <td class="center">
                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="' . $value['_id'] . '"><i class="fa fa-eye"></i></button>
                            </td>
                            <td>' . $value['symbol'] . '</td>
                            <td>' . num($value['price']) . '</td>
                            <td>';
                if ($value['trail_check'] == 'yes') {
                    $response6 .= num($value['buy_trail_price']);
                } else {
                    $response6 .= "-";
                }
                $response6 .= '</td>
                            <td>' . $value['quantity'] . '</td>
                            <td class="center"><b>' . num($market_value333) . '</b></td>';

                if ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes') {

                    $response6 .= '<td class="center"><span class="text-' . $class . '"><b>' . $market_data . '%</b></span></td>';

                } else {

                    $response6 .= '<td class="center"><span class="text-default"><b>-</b></span></td>';
                }

                $response6 .= '<td class="center">';

                if ($value['status'] == 'FILLED' && $value['is_sell_order'] == 'yes') {

                    $response6 .= '<span class="label label-info">SUBMITTED FOR SELL</span>';

                } else {

                    $response6 .= '<span class="label label-success">' . strtoupper($value['status']) . '</span>';
                }

                $response6 .= '<span class="custom_refresh" data-id="' . $value['_id'] . '" order_id="' . $value['binance_order_id'] . '">
		                            		<i class="fa fa-refresh" aria-hidden="true"></i>
		                            	  </span>';

                $response6 .= '</td>

                            <td class="center">';

                if ($value['market_sold_price'] != "") {

                    $market_sold_price = num($value['market_sold_price']);

                    $current_data2222 = $market_sold_price - $current_order_price;
                    $profit_data = ($current_data2222 * 100 / $market_sold_price);

                    $profit_data = number_format((float) $profit_data, 2, '.', '');

                    if ($market_sold_price > $current_order_price) {
                        $class222 = 'success';
                    } else {
                        $class222 = 'danger';
                    }

                    $response6 .= '<span class="text-' . $class222 . '">
		                        				<b>' . $profit_data . '%</b>
		                        			  </span>';
                } else {

                    if ($value['status'] == 'FILLED') {

                        if ($value['is_sell_order'] == 'yes') {

                            $current_data = num($market_value) - num($value['market_value']);
                            $market_data = ($current_data * 100 / $market_value);

                            $market_data = number_format((float) $market_data, 2, '.', '');

                            if ($market_value > $value['market_value']) {
                                $class = 'success';
                            } else {
                                $class = 'danger';
                            }

                            $response6 .= '<span class="text-' . $class . '"><b>' . $market_data . '%</b></span>';

                        } else {

                            $response6 .= '<span class="text-default"><b>-</b></span>';
                        }

                    } else {

                        $response6 .= '<span class="text-default"><b>-</b></span>';

                    }
                }

                $response6 .= '</td>

                            <td class="center">
                                <div class="btn-group btn-group-xs ">';
                if ($value['status'] == 'new') {
                    $response6 .= '<a href="' . SURL . 'admin/dashboard/edit-buy-order/' . $value['_id'] . '" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>';
                }
                if ($value['status'] != 'FILLED') {
                    $response6 .= '<a href="' . SURL . 'admin/dashboard/delete-buy-order/' . $value['_id'] . '/' . $value['binance_order_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>';
                }

                if ($value['status'] == 'FILLED') {

                    if ($value['is_sell_order'] == 'yes') {

                        $response6 .= '<a href="' . SURL . 'admin/dashboard/edit-order/' . $value['sell_order_id'] . '" class="btn btn-inverse" target="_blank"><i class="fa fa-pencil"></i></a>';
                        $response6 .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';

                    } elseif ($value['is_sell_order'] == 'sold') {
                        $response6 .= '<button class="btn btn-success">Sold</button>';
                    } else {
                        $response6 .= '<a href="' . SURL . 'admin/dashboard/add-order/' . $value['_id'] . '" class="btn btn-warning" target="_blank">Set For Sell</a>';
                        $response6 .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';
                    }

                }

                $response6 .= '</div>
                            </td>



                            <td class="text-center">';
                if ($value['status'] == 'new') {

                    $response6 .= '<button class="btn btn-danger buy_now_btn" id="' . $value['_id'] . '" data-id="' . $value['_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Buy Now</button>';
                }
                $response6 .= '</td>
                            </tr>';
            }
        }
        $response6 .= '</tbody>
                    </table>';

        $response7 = '<table class="table table-condensed">
	                    <thead>
	                        <tr>
	                            <th></th>
	                            <th><strong>Coin</strong></th>
	                            <th><strong>Price</strong></th>
	                            <th><strong>Trail Price</strong></th>
	                            <th><strong>Quantity</strong></th>
	                            <th class="text-center"><strong>P/L</strong></th>
	                            <th class="text-center"><strong>Market(%)</strong></th>
	                            <th class="text-center"><strong>Status</strong></th>
	                            <th class="text-center"><strong>Profit(%)</strong></th>
	                            <th class="text-center"><strong>Actions</strong></th>
	                        </tr>
	                    </thead>
                        <tbody>';
        if (count($sold_trades) > 0) {
            foreach ($sold_trades as $key => $value) {

                //Get Market Price
                $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                if ($value['status'] != 'new') {
                    $market_value333 = num($value['market_value']);
                } else {
                    $market_value333 = num($market_value);
                }

                if ($value['status'] == 'new') {
                    $current_order_price = num($value['price']);
                } else {
                    $current_order_price = num($value['market_value']);
                }

                $current_data = $market_value333 - $current_order_price;
                $market_data = ($current_data * 100 / $market_value333);

                $market_data = number_format((float) $market_data, 2, '.', '');

                if ($market_value333 > $current_order_price) {
                    $class = 'success';
                } else {
                    $class = 'danger';
                }

                $response7 .= '<tr>
                            <td class="center">
                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="' . $value['_id'] . '"><i class="fa fa-eye"></i></button>
                            </td>
                            <td>' . $value['symbol'] . '</td>
                            <td>' . num($value['price']) . '</td>
                            <td>';
                if ($value['trail_check'] == 'yes') {
                    $response7 .= num($value['buy_trail_price']);
                } else {
                    $response7 .= "-";
                }
                $response7 .= '</td>
                            <td>' . $value['quantity'] . '</td>
                            <td class="center"><b>' . num($market_value333) . '</b></td>';

                if ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes') {

                    $response7 .= '<td class="center"><span class="text-' . $class . '"><b>' . $market_data . '%</b></span></td>';

                } else {

                    $response7 .= '<td class="center"><span class="text-default"><b>-</b></span></td>';
                }

                $response7 .= '<td class="center">';

                if ($value['status'] == 'FILLED' && $value['is_sell_order'] == 'yes') {

                    $response7 .= '<span class="label label-info">SUBMITTED FOR SELL</span>';

                } else {

                    $response7 .= '<span class="label label-success">' . strtoupper($value['status']) . '</span>';
                }

                $response7 .= '<span class="custom_refresh" data-id="' . $value['_id'] . '" order_id="' . $value['binance_order_id'] . '">
		                            		<i class="fa fa-refresh" aria-hidden="true"></i>
		                            	  </span>';

                $response7 .= '</td>

                            <td class="center">';

                if ($value['market_sold_price'] != "") {

                    $market_sold_price = num($value['market_sold_price']);

                    $current_data2222 = $market_sold_price - $current_order_price;
                    $profit_data = ($current_data2222 * 100 / $market_sold_price);

                    $profit_data = number_format((float) $profit_data, 2, '.', '');

                    if ($market_sold_price > $current_order_price) {
                        $class222 = 'success';
                    } else {
                        $class222 = 'danger';
                    }

                    $response7 .= '<span class="text-' . $class222 . '">
		                        				<b>' . $profit_data . '%</b>
		                        			  </span>';
                } else {

                    $response7 .= '<span class="text-default">
		                        					<b>-</b>
		                        			   </span>';
                }

                $response7 .= '</td>

                            <td class="center">
                                <div class="btn-group btn-group-xs ">';
                if ($value['status'] == 'new') {
                    $response7 .= '<a href="' . SURL . 'admin/dashboard/edit-buy-order/' . $value['_id'] . '" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>';
                }
                if ($value['status'] != 'FILLED') {
                    $response7 .= '<a href="' . SURL . 'admin/dashboard/delete-buy-order/' . $value['_id'] . '/' . $value['binance_order_id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>';
                }

                if ($value['status'] == 'FILLED') {
                    if ($value['is_sell_order'] == 'yes') {

                    } elseif ($value['is_sell_order'] == 'sold') {
                        $response7 .= '<button class="btn btn-success">Sold</button>';
                    } else {
                        $response7 .= '<a href="' . SURL . 'admin/dashboard/add-order/' . $value['_id'] . '" class="btn btn-warning" target="_blank">Set For Sell</a>';
                        $response7 .= '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Sell Now</button>';
                    }
                }

                $response7 .= '</div>
                            </td>



                            <td class="text-center">';
                if ($value['status'] == 'new') {

                    $response7 .= '<button class="btn btn-danger buy_now_btn" id="' . $value['_id'] . '" data-id="' . $value['_id'] . '" market_value="' . num($market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '">Buy Now</button>';
                }
                $response7 .= '</td>
                            </tr>';
            }
        }
        $response7 .= '</tbody>
                    </table>';

        $count_new_orders = count($new_orders);
        $count_filled_orders = count($filled_orders);
        $count_submitted = count($submitted);
        $count_cancelled = count($cancelled);
        $count_error_orders = count($error_orders);
        $count_open_trades = count($open_trades);
        $count_sold_trades = count($sold_trades);
        $count_orders_arr = count($orders_arr);

        echo $response . '@@@@@@' . $response1 . '@@@@@@' . $response2 . '@@@@@@' . $response3 . '@@@@@@' . $response4 . '@@@@@@' . $response5 . '@@@@@@' . $response6 . '@@@@@@' . $response7 . '@@@@@@' . $count_new_orders . '@@@@@@' . $count_filled_orders . '@@@@@@' . $count_submitted . '@@@@@@' . $count_cancelled . '@@@@@@' . $count_error_orders . '@@@@@@' . $count_open_trades . '@@@@@@' . $count_sold_trades . '@@@@@@' . $count_orders_arr;
        exit;

    } //end autoload_market_buy_data2

    public function check_status_of_limit_order() {

        $this->mod_login->verify_is_admin_login();
        $sell_id = $this->input->post('sell_id');
        $this->mongo_db->where(array('_id' => $sell_id));
        $data = $this->mongo_db->get('orders');
        $row = iterator_to_array($data);
        $status = '';

        $symbol = $this->input->post('symbol');
        $market_price = $this->mod_dashboard->get_market_value($symbol);

        $resp_data = array();

        if (count($row) > 0) {
            $status = $row[0]['status'];
        }
        $resp_data['status'] = $status;
        $resp_data['market_price'] = $market_price;
        echo json_encode($resp_data);
        exit();
    } //End of check_status_of_limit_order

    public function sell_market_order_by_user() {

        $this->mod_login->verify_is_admin_login();
        $sell_id = $this->input->post('sell_id');
        $buy_order_id = $this->input->post('buy_order_id');
        $market_value = $this->input->post('market_value');
        $quantity = $this->input->post('quantity');
        $symbol = $this->input->post('symbol');
        $admin_id = $this->session->userdata('admin_id');

        $trading_ip = $this->mod_barrier_trigger->get_user_trading_ip($admin_id);

        $sell_price = $this->input->post('sell_price');

        $order_type = $this->input->post('order_type');
        $order_arr = $this->mod_dashboard->get_order($sell_id);
        $created_date = date('Y-m-d G:i:s');

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $created_date = date('Y-m-d G:i:s');
        $upd_data22 = array(
            'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            'is_manual_sold' => 'yes',
        );
        $this->mongo_db->where(array('_id' => $sell_id));
        $this->mongo_db->set($upd_data22);
        //Update data in mongoTable
        $this->mongo_db->update('orders');

        $this->mongo_db->where(array('_id' => $buy_order_id));
        $this->mongo_db->set($upd_data22);
        //Update data in mongoTable

        $upd_data22['status'] = 'FILLED';
        $this->mongo_db->update('buy_orders');
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $coin_unit_value = $this->mod_coins->get_coin_unit_value($symbol);
        $resp_message = '';

        if (!empty($order_arr)) {
            $binance_order_id = $order_arr['binance_order_id'];
            $quantity = $order_arr['quantity'];

            if ($order_type != 'm_current') {
                $log_msg = " Order Type Changed From <b>Market order</b> to <b>Limit Order</b>";
                $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
            }

            $market_price = $this->mod_dashboard->get_market_value($symbol);
            $market_price = (float) $market_price;

            $application_mode = $order_arr['application_mode'];

            if ($application_mode == 'live') {

                if ($order_type == 'm_current') {
                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                    $log_msg = "Market Order Send For Sell On:  " . num($market_price);
                    $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'yes');

                    $log_msg = 'Send Market Orde for sell by Ip: <b>' . $trading_ip . '</b> ';
                    $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'no');

                    $trigger_type = 'barrier_trigger';
                    $this->mod_barrier_trigger->order_ready_for_sell_by_ip($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id, $trading_ip, $trigger_type, 'sell_market_order');

                    //$this->mod_dashboard->binance_sell_auto_market_order_live($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id);

                } else {

                    if ($order_type == 'l_below') {

                        //%%%%%%%%%%%%%%%%%%%% Below Price %%%%%%%%%%%
                        $below_price = $market_price - $coin_unit_value;
                        if ($sell_price != '') {
                            $below_price = $sell_price - $coin_unit_value;
                        }

                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                        $log_msg = "Current Market Price:  " . num($market_price);
                        $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'yes');

                        if ($sell_price != '') {
                            $log_msg = "User Defined Sell Price:  " . num($sell_price);
                            $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'yes');

                        }

                        $log_msg = "Limit Order Send For Sell On:  " . num($below_price);
                        $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'yes');

                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        //Auto Sell Binance Limit Order Live
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                        $log_msg = 'Send Limit Orde for sell by Ip: <b>' . $trading_ip . '</b> ';
                        $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'yes');

                        $trigger_type = 'barrier_trigger';
                        $this->mod_barrier_trigger->order_ready_for_sell_by_ip($sell_id, $quantity, $below_price, $symbol, $admin_id, $buy_order_id, $trading_ip, $trigger_type, 'sell_limit_order');

                        // $res_limit_order = $this->mod_dashboard->binance_sell_auto_limit_order_live($sell_id, $quantity, $below_price, $symbol, $admin_id, $buy_order_id);

                    } else {

                        $log_msg = "Current Market Price:  " . num($market_price);
                        $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'yes');

                        if ($sell_price != '') {
                            $log_msg = "User Defined Sell Price:  " . num($sell_price);
                            $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'yes');

                            $market_price = $sell_price;
                        }

                        $log_msg = "Limit Order Send For Sell On:  " . num($market_price);
                        $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'yes');
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        //Auto Sell Binance Limit Order Live
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                        $log_msg = 'Send Limit Orde for sell by Ip: <b>' . $trading_ip . '</b> ';
                        $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'no');

                        $trigger_type = 'barrier_trigger';
                        $this->mod_barrier_trigger->order_ready_for_sell_by_ip($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id, $trading_ip, $trigger_type, 'sell_limit_order');

                        //$res_limit_order = $this->mod_dashboard->binance_sell_auto_limit_order_live($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id);
                    }
                } //End of limit order

            } else {
                //End of  live order check

                //%%%%%%%%%%%%%%%%% Test Order %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                if ($order_type == 'm_current') {
                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                    $log_msg = "Market Order Send For Sell On:  " . num($market_price);
                    $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'yes');

                    $this->mod_dashboard->binance_sell_auto_market_order_test($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id);

                } else {

                    if ($order_type == 'l_below') {

                        //%%%%%%%%%%%%%%%%%%%% Below Price %%%%%%%%%%%
                        $below_price = $market_price - $coin_unit_value;
                        if ($sell_price != '') {
                            $below_price = $sell_price - $coin_unit_value;
                        }

                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                        $log_msg = "Current Market Price:  " . num($market_price);
                        $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'yes');

                        if ($sell_price != '') {
                            $log_msg = "User Defined Sell Price:  " . num($sell_price);
                            $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'yes');
                        }
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                        $log_msg = "Limit Order Send For Sell On:  " . num($below_price);
                        $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'yes');
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        //Auto Sell Binance Limit Order Live
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        $res_limit_order = $this->mod_dashboard->binance_sell_auto_limit_order_test($sell_id, $quantity, $below_price, $symbol, $admin_id, $buy_order_id);

                    } else {

                        $log_msg = "Current Market Price:  " . num($market_price);
                        $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'yes');

                        if ($sell_price != '') {
                            $log_msg = "User Defined Sell Price:  " . num($sell_price);
                            $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);

                            $market_price = $sell_price;
                        }
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                        $log_msg = "Limit Order Send For Sell On:  " . num($market_price);
                        $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'yes');
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        //Auto Sell Binance Limit Order Live
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        $res_limit_order = $this->mod_dashboard->binance_sell_auto_limit_order_test($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id);
                    }
                } //End of limit order
                //%%%%%%%%%%%%%%%%% End of Test order %%%%%%%%%%%%%%%%

            }

            $log_msg = " Order Has been sent for sold Manually by Sell Now";
            $this->triggers_trades->record_order_log($buy_order_id, $log_msg, 'change status', 'yes');
        } //if sell order exist

        echo $resp_message;
        exit();
    } //End of sell_market_order_by_user

    public function sell_lmit_order_by_user() {

        $this->mod_login->verify_is_admin_login();
        $sell_id = $this->input->post('sell_id');
        $buy_order_id = $this->input->post('buy_order_id');
        $market_value = $this->input->post('market_value');
        $quantity = $this->input->post('quantity');
        $symbol = $this->input->post('symbol');
        $admin_id = $this->session->userdata('admin_id');

        $trading_ip = $this->mod_barrier_trigger->get_user_trading_ip($admin_id);

        $sell_price = $this->input->post('sell_price');

        $sold_by = $this->input->post('admin');

        $order_type = $this->input->post('order_type');
        $order_arr = $this->mod_dashboard->get_order($sell_id);
        $created_date = date('Y-m-d G:i:s');

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $created_date = date('Y-m-d G:i:s');
        $upd_data22 = array(
            'modified_date' => $this->mongo_db->converToMongodttime($created_date),
            'is_manual_sold' => 'yes',
        );
        $this->mongo_db->where(array('_id' => $sell_id));
        $this->mongo_db->set($upd_data22);
        //Update data in mongoTable
        $this->mongo_db->update('orders');

        $this->mongo_db->where(array('_id' => $buy_order_id));
        $this->mongo_db->set($upd_data22);
        //Update data in mongoTable
        $this->mongo_db->update('buy_orders');
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        $coin_unit_value = $this->mod_coins->get_coin_unit_value($symbol);
        $resp_message = '';

        if (!empty($order_arr)) {
            $binance_order_id = $order_arr['binance_order_id'];
            $quantity = $order_arr['quantity'];

            if ($order_type == 'm_current') {
                $log_msg = " Order Type Changed From <b>Limit order</b> to <b>Market Order</b>";
                $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
            }
            $market_price = $this->mod_dashboard->get_market_value($symbol);
            $market_price = (float) $market_price;

            $application_mode = $order_arr['application_mode'];
            if ($application_mode == 'live') {

                if ($order_type == 'm_current') {
                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                        $log_msg = "Market Order Send For Sell On:  " . num($market_price);
                        $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                    

                    //***************************************************************/
                    $log_msg = 'Send Market Orde for sell by Ip: <b>' . $trading_ip . '</b> ';
                    $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'send_limit_order', $admin_id, $created_date);

                    $trigger_type = 'barrier_trigger';
                    $this->mod_barrier_trigger->order_ready_for_sell_by_ip($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id, $trading_ip, $trigger_type, 'sell_market_order');
                    //*************************************************************/
                    //$this->mod_dashboard->binance_sell_auto_market_order_live($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id);

                } else {

                    if ($order_type == 'l_below') {

                        //%%%%%%%%%%%%%%%%%%%% Below Price %%%%%%%%%%%
                        $below_price = $market_price - $coin_unit_value;
                        if ($sell_price != '') {
                            $below_price = $sell_price - $coin_unit_value;
                        }

                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        $log_msg = "Current Market Price:  " . num($market_price);
                        $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);

                        if ($sell_price != '') {
                            $log_msg = "User Defined Sell Price:  " . num($sell_price);
                            $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                        }
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                        $log_msg = "Current Market Price:  " . num($market_price);
                        $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);

                        $log_msg = "Limit Order Send For Sell On:  " . num($below_price);
                        $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        //Auto Sell Binance Limit Order Live
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                        $log_msg = 'Send Limit Orde for sell by Ip: <b>' . $trading_ip . '</b> ';
                        $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'send_limit_order', $admin_id, $created_date);

                        $trigger_type = 'barrier_trigger';
                        $this->mod_barrier_trigger->order_ready_for_sell_by_ip($sell_id, $quantity, $below_price, $symbol, $admin_id, $buy_order_id, $trading_ip, $trigger_type, 'sell_limit_order');

                        // $res_limit_order = $this->mod_dashboard->binance_sell_auto_limit_order_live($sell_id, $quantity, $below_price, $symbol, $admin_id, $buy_order_id);

                    } else {

                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        $log_msg = "Current Market Price:  " . num($market_price);
                        $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);

                        if ($sell_price != '') {
                            $log_msg = "User Defined Sell Price:  " . num($sell_price);
                            $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                            $market_price = $sell_price;
                        }

                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                        $log_msg = "Limit Order Send For Sell On:  " . num($market_price);
                        $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        //Auto Sell Binance Limit Order Live
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        $log_msg = 'Send Limit Orde for sell by Ip: <b>' . $trading_ip . '</b> ';
                        $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'send_limit_order', $admin_id, $created_date);

                        $trigger_type = 'barrier_trigger';
                        $this->mod_barrier_trigger->order_ready_for_sell_by_ip($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id, $trading_ip, $trigger_type, 'sell_limit_order');

                        // $res_limit_order = $this->mod_dashboard->binance_sell_auto_limit_order_live($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id);
                    }
                } //End of limit order
            } else {
                //End of  live order check
                //%%%%%%%%%%%%%%%%% Test Order %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                if ($order_type == 'm_current') {
                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                    $log_msg = "Market Order Send For Sell On:  " . num($market_price);
                    $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);

                    $this->mod_dashboard->binance_sell_auto_market_order_test($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id);

                } else {

                    if ($order_type == 'l_below') {

                        //%%%%%%%%%%%%%%%%%%%% Below Price %%%%%%%%%%%
                        $below_price = $market_price - $coin_unit_value;
                        if ($sell_price != '') {
                            $below_price = $sell_price - $coin_unit_value;
                        }

                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                        $log_msg = "Current Market Price:  " . num($market_price);
                        $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);

                        if ($sell_price != '') {
                            $log_msg = "User Defined Sell Price:  " . num($sell_price);
                            $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                        };
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                        $log_msg = "Current Market Price:  " . num($market_price);
                        $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);

                        $log_msg = "Limit Order Send For Sell On:  " . num($below_price);
                        $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        //Auto Sell Binance Limit Order Live
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        $res_limit_order = $this->mod_dashboard->binance_sell_auto_limit_order_test($sell_id, $quantity, $below_price, $symbol, $admin_id, $buy_order_id);

                    } else {

                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        $log_msg = "Current Market Price:  " . num($market_price);
                        $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);

                        if ($sell_price != '') {
                            $log_msg = "User Defined Sell Price:  " . num($sell_price);
                            $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                            $market_price = $sell_price;
                        }

                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                        $log_msg = "Limit Order Send For Sell On:  " . num($market_price);
                        $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        //Auto Sell Binance Limit Order Live
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                        $res_limit_order = $this->mod_dashboard->binance_sell_auto_limit_order_test($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id);
                    }
                } //End of limit order
                //%%%%%%%%%%%%%%%%% End of Test order %%%%%%%%%%%%%%%%
            } //End of test

            $log_msg = " Order Has been sent for Sell Manually by Sell Now";
            $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'sell_order', $admin_id, $created_date);
        } //if sell order exist

        echo $resp_message;
        exit();
    } //End of sell_lmit_order_by_user

    public function cancel_and_place_new_limit_order_for_sell() {
        $this->mod_login->verify_is_admin_login();
        $sell_id = $this->input->post('sell_id');
        $buy_order_id = $this->input->post('buy_order_id');
        $market_value = $this->input->post('market_value');
        $quantity = $this->input->post('quantity');
        $symbol = $this->input->post('symbol');
        $admin_id = $this->session->userdata('admin_id');

        $trading_ip = $this->mod_barrier_trigger->get_user_trading_ip($admin_id);

        $order_type = $this->input->post('order_type');
        $order_arr = $this->mod_dashboard->get_order($sell_id);
        $created_date = date('Y-m-d G:i:s');
        $sell_price = $this->input->post('sell_price');

        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $created_date = date('Y-m-d G:i:s');
        $upd_data22 = array(
            'modified_date' => $this->mongo_db->converToMongodttime($created_date),
        );
        $this->mongo_db->where(array('_id' => $sell_id));
        $this->mongo_db->set($upd_data22);
        //Update data in mongoTable
        $this->mongo_db->update('orders');

        $this->mongo_db->where(array('_id' => $buy_order_id));
        $this->mongo_db->set($upd_data22);
        //Update data in mongoTable
        $this->mongo_db->update('buy_orders');

        $coin_unit_value = $this->mod_coins->get_coin_unit_value($symbol);
        $resp_message = '';

        if (!empty($order_arr)) {
            $binance_order_id = $order_arr['binance_order_id'];
            $quantity = $order_arr['quantity'];
            $application_mode = $order_arr['application_mode'];

            $this->save_traking_cancel_order_against_user($sell_id, $buy_order_id, $symbol, $admin_id, $created_date);

            $is_cancel_order_exceed_limit = $this->is_cancel_order_exceed_limit($sell_id, $buy_order_id, $symbol, $admin_id);

            if ($is_cancel_order_exceed_limit) {
                echo $resp_message = 'Your cancel order limit exceed';
                exit;
            }

            //%%%%%%%%%%%%%% Cancel limit sell order %%%%%%%%%%%%%
            if ($application_mode == 'live') {
                $cancel_order = $this->binance_api->cancel_order($symbol, $binance_order_id, $admin_id);
            } else {
                $cancel_order['orderId'] == '0000000';
            }

            if ($cancel_order['orderId'] == "") {

                $order_arr = json_encode($order);
                $order_arr2 = json_decode($order_arr);
                $error_msg = $order_arr2->msg;

                $log_msg = "Error Occure When  Cancelling The Trade  " . $error_msg;

                $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                echo $resp_message = $log_msg;
                exit;

            } else {

                //%%%%%%%%%%%%%%%%% Change sell order status to new %%%%%%%%

                $upd_status = array('status' => 'new');
                $this->mongo_db->where(array('_id' => $sell_id));
                $this->mongo_db->set($upd_status);
                //Update data in mongoTable
                $this->mongo_db->update('orders');

                //%%%%%%%%%%%%%%%% Message Log %%%%%%%%%%%%%%%%%%
                $created_date = date('Y-m-d G:i:s');
                $frm_msg = '<span style="color:orange;    font-size: 14px;"><b>SUBMITTED</b></span>';
                $to_msg = '<span style="color:green;    font-size: 14px;"><b>NEW</b></span>';
                $log_msg = " Order status change from  " . $frm_msg . ' To ' . $to_msg;
                $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);

                if ($order_type == 'm_current') {
                    $log_msg = " Order Type Changed From <b>Limit order</b> to <b>Market Order</b>";
                    $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                }

                $application_mode = $order_arr['application_mode'];
                $market_price = $this->mod_dashboard->get_market_value($symbol);
                $market_price = (float) $market_price;

                if ($application_mode == 'live') {

                    if ($order_type == 'm_current') {
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                        $log_msg = "Market Order Send For Sell On:  " . num($market_price);
                        $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);

                        $log_msg = 'Send Market Orde for sell by Ip: <b>' . $trading_ip . '</b> ';
                        $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'send_limit_order', $admin_id, $created_date);

                        $trigger_type = 'barrier_trigger';
                        $this->mod_barrier_trigger->order_ready_for_sell_by_ip($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id, $trading_ip, $trigger_type, 'sell_market_order');

                        //$this->mod_dashboard->binance_sell_auto_market_order_live($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id);

                    } else {

                        if ($order_type == 'l_below') {
                            //%%%%%%%%%%%%%%%%%%%% Below Price %%%%%%%%%%%
                            $below_price = $market_price - $coin_unit_value;
                            if ($sell_price != '') {
                                $below_price = $sell_price - $coin_unit_value;
                            }

                            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                            $log_msg = "Current Market Price:  " . num($market_price);
                            $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);

                            if ($sell_price != '') {
                                $log_msg = "User Defined Sell Price:  " . num($sell_price);
                                $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                            };
                            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                            $log_msg = "Limit Order Send For Sell On:  " . num($below_price);
                            $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                            //Auto Sell Binance Limit Order Live
                            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                            $log_msg = 'Send Limit Orde for sell by Ip: <b>' . $trading_ip . '</b> ';
                            $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'send_limit_order', $admin_id, $created_date);

                            $trigger_type = 'barrier_trigger';
                            $this->mod_barrier_trigger->order_ready_for_sell_by_ip($sell_id, $quantity, $below_price, $symbol, $admin_id, $buy_order_id, $trading_ip, $trigger_type, 'sell_limit_order');

                            //$res_limit_order = $this->mod_dashboard->binance_sell_auto_limit_order_live($sell_id, $quantity, $below_price, $symbol, $admin_id, $buy_order_id);

                        } else {

                            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                            $log_msg = "Current Market Price:  " . num($market_price);
                            $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);

                            if ($sell_price != '') {
                                $log_msg = "User Defined Sell Price:  " . num($sell_price);
                                $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);

                                $market_price = $sell_price;
                            }

                            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                            $log_msg = "Limit Order Send For Sell On:  " . num($market_price);
                            $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                            //Auto Sell Binance Limit Order Live
                            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                            $log_msg = 'Send Limit Orde for sell by Ip: <b>' . $trading_ip . '</b> ';
                            $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'send_limit_order', $admin_id, $created_date);

                            $trigger_type = 'barrier_trigger';
                            $this->mod_barrier_trigger->order_ready_for_sell_by_ip($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id, $trading_ip, $trigger_type, 'sell_limit_order');

                            // $res_limit_order = $this->mod_dashboard->binance_sell_auto_limit_order_live($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id);
                        }
                    } //End of limit order
                } else {
                    //End of  live order check

                    //%%%%%%%%%%%%%%%%% Test Order %%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                    if ($order_type == 'm_current') {
                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                        $log_msg = "Market Order Send For Sell On:  " . num($market_price);
                        $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);

                        $this->mod_dashboard->binance_sell_auto_market_order_test($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id);

                    } else {

                        if ($order_type == 'l_below') {

                            //%%%%%%%%%%%%%%%%%%%% Below Price %%%%%%%%%%%
                            $below_price = $market_price - $coin_unit_value;
                            if ($sell_price != '') {
                                $below_price = $sell_price - $coin_unit_value;
                            }

                            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                            $log_msg = "Current Market Price:  " . num($market_price);
                            $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);

                            if ($sell_price != '') {
                                $log_msg = "User Defined Sell Price:  " . num($sell_price);
                                $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                            }

                            $log_msg = "Limit Order Send For Sell On:  " . num($below_price);
                            $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                            //Auto Sell Binance Limit Order Live
                            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                            $res_limit_order = $this->mod_dashboard->binance_sell_auto_limit_order_test($sell_id, $quantity, $below_price, $symbol, $admin_id, $buy_order_id);

                        } else {

                            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                            $log_msg = "Current Market Price:  " . num($market_price);
                            $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);

                            if ($sell_price != '') {
                                $log_msg = "User Defined Sell Price:  " . num($sell_price);
                                $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                                $market_price = $sell_price;
                            }

                            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                            $log_msg = "Limit Order Send For Sell On:  " . num($market_price);
                            $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'change status', $admin_id, $created_date);
                            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

                            //Auto Sell Binance Limit Order Live
                            //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                            $res_limit_order = $this->mod_dashboard->binance_sell_auto_limit_order_test($sell_id, $quantity, $market_price, $symbol, $admin_id, $buy_order_id);
                        }
                    } //End of limit order
                    //%%%%%%%%%%%%%%%%% End of Test order %%%%%%%%%%%%%%%%

                } //End of test
            } //End of if no erro

        } //if sell order exist

        echo $resp_message;
        exit();

    } //End of cancel_and_place_new_limit_order_for_sell

    public function is_cancel_order_exceed_limit($sell_id, $buy_order_id, $symbol, $admin_id) {
        $from_date = date('Y-m-d 00:00:00');
        $to_date = date('Y-m-d 23:59:59');

        $from_date_obj = $this->mongo_db->converToMongodttime($from_date);
        $to_date_obj = $this->mongo_db->converToMongodttime($to_date);

        $this->mongo_db->where(array('admin_id' => $admin_id));

        $this->mongo_db->where_gte('created_date', $from_date);
        $this->mongo_db->where_lte('created_date', $to_date);

        $resp = $this->mongo_db->get('traking_cancel_order_against_user');
        $resp = iterator_to_array($resp);
        $response = false;
        if (count($resp) >= 3) {
            $response = false;
        }
        return $response;

    } //End OF is_cancel_order_exceed_limit

    public function save_traking_cancel_order_against_user($sell_id, $buy_order_id, $symbol, $admin_id, $created_date) {
        $created_date_obj = $this->mongo_db->converToMongodttime($created_date);

        $insert_arr = array('sell_id' => $sell_id, 'buy_order_id' => $buy_order_id, 'symbol' => $symbol, 'admin_id' => (int) $admin_id, 'created_date_human_readible' => $created_date, 'created_date' => $created_date_obj);
        $this->mongo_db->insert('traking_cancel_order_against_user', $insert_arr);
    } //End  of save_traking_cancel_order_against_user

    public function sell_order() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        $id = $this->input->post('id');
        $market_value = $this->input->post('market_value');
        $quantity = $this->input->post('quantity');
        $symbol = $this->input->post('symbol');
        $user_id = $this->session->userdata('admin_id');
        $trading_ip = $this->mod_barrier_trigger->get_user_trading_ip($user_id);

        $sold_by = $this->input->post('admin');

        $order_arr = $this->mod_dashboard->get_order($id);

        if ($order_arr['status'] == 'new') {

            $application_mode = $order_arr['application_mode'];
            $buy_order_id = $order_arr['buy_order_id'];

            if ($application_mode == 'live') {

                //Auto Sell Binance Market Order Live
                // $this->mod_dashboard->binance_sell_auto_market_order_live($id, $quantity, $market_value, $symbol, $user_id);

                if($sold_by == 'admin'){
                     $log_msg = 'Sell order is being processed by admin';
                     $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'sell_market_order', $user_id, $created_date);

                }

                $log_msg = 'Send Market Orde for sell by Ip: <b>' . $trading_ip . '</b> ';
                $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'sell_market_order', $user_id, $created_date);

                $trigger_type = 'barrier_trigger';
                $this->mod_barrier_trigger->order_ready_for_sell_by_ip($id, $quantity, $market_value, $symbol, $user_id, $buy_order_id, $trading_ip, $trigger_type, 'sell_market_order');

            } else {
                if($sold_by == 'admin'){
                    $log_msg = 'Sell order is being processed by admin';
                    $this->mod_barrier_trigger->insert_order_history_log($buy_order_id, $log_msg, 'sell_market_order', $user_id, $created_date);

               }

                //Auto Sell Binance Market Order Test
                $this->mod_dashboard->binance_sell_auto_market_order_test($id, $quantity, $market_value, $symbol, $user_id);
            }

            echo 1;

        } else {

            echo "Order is already in <b>" . strtoupper($order_arr['status']) . "</b> status";

        }

        exit;

    } //end sell_order

    public function sell_all_orders() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        $sell_all_orders = $this->mod_dashboard->sell_all_orders();

        if ($sell_all_orders) {
            $this->autoload_market_data2();
        }

    } //end sell_all_orders

    public function buy_order() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        $id = $this->input->post('id');
        $market_value = $this->input->post('market_value');
        $quantity = $this->input->post('quantity');
        $symbol = $this->input->post('symbol');
        $user_id = $this->session->userdata('admin_id');
        $trading_ip = $this->mod_barrier_trigger->get_user_trading_ip($user_id);

        $order_arr = $this->mod_dashboard->get_buy_order($id);
        $application_mode = $order_arr['application_mode'];

        $this->mongo_db->where(array('_id' => $id));
        $this->mongo_db->set(array('is_manual_buy' => 'yes'));
        $this->mongo_db->update('buy_orders');

        if ($application_mode == 'live') {

            //Auto Buy Binance Market Order Live
            // $this->mod_dashboard->binance_buy_auto_market_order_live($id, $quantity, $market_value, $symbol, $user_id);

            $log_msg = 'Send order for buy by Ip:<blod>' . $trading_ip . '</bold> Manually';
            $this->mod_barrier_trigger->insert_order_history_log($id, $log_msg, 'buy_price', $user_id, $created_date);

            $trigger_type = 'barrier_trigger';
            $this->mod_barrier_trigger->order_ready_for_buy_by_ip($id, $quantity, $market_value, $symbol, $user_id, $trading_ip, $trigger_type, 'buy_market_order');

        } else {

            $this->mod_dashboard->binance_buy_auto_market_order_test($id, $quantity, $market_value, $symbol, $user_id);
        }

        echo 1;
        exit;

    } //end buy_order

    public function buy_all_orders() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        $buy_all_orders = $this->mod_dashboard->buy_all_orders();

        if ($buy_all_orders) {
            $this->autoload_market_buy_data2();
        }

    } //end buy_all_orders

    public function get_order_details($order_id, $type) {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        if ($type == 'sell') {

            $order_arr = $this->mod_dashboard->get_order($order_id);
            $response = '<div class="boat_points_iner">
					        <div class="boat_points_close">
					            <i class="fa fa-chevron-right" aria-hidden="true"></i>
					            <i class="fa fa-chevron-left" aria-hidden="true"></i>
					        </div>
					        <div class="boat_points_header" style="background: #f11919 none repeat scroll 0 0;">Order Details</div>
					        <div class="boat_points_body">
					            <ul>
					                <li><strong>Entry Price</strong> <span class="color-blue">' . $order_arr['purchased_price'] . '</span></li>
					                <li><strong>Exit Price</strong> <span class="color-blue">' . $order_arr['market_value'] . '</span></li>
					                <li><strong>Quantity</strong> <span class="color-blue">' . $order_arr['quantity'] . '</span></li>
					                <li><strong>Profit Target</strong> <span class="color-blue">';
            if ($order_arr['profit_type'] == 'percentage') {
                $response .= $order_arr['sell_profit_percent'] . "%";
            } else {
                $response .= $order_arr['sell_profit_price'];
            }
            $response .= '</span></li>
					               <li><strong>Status</strong> <span class="color-blue">';
            if ($order_arr['status'] == 'sell') {
                $response .= '<span class="label label-danger">Sell</span>';
            } else {
                $response .= '<span class="label label-success">New</span>';
            }
            $response .= '</span></li>

					               <li>
					               <button type="button" class="btn btn-info pull-right" id="edit_order_btn" order_id="' . $order_arr['_id'] . '" data-type="sell">Edit</button>
					               </li>
					            </ul>
					        </div>
					    </div>';

        } else {

            $order_arr = $this->mod_dashboard->get_buy_order($order_id);
            $response = '<div class="boat_points_iner">
					        <div class="boat_points_close">
					            <i class="fa fa-chevron-right" aria-hidden="true"></i>
					            <i class="fa fa-chevron-left" aria-hidden="true"></i>
					        </div>
					        <div class="boat_points_header">Order Details</div>
					        <div class="boat_points_body">
					            <ul>
					                <li><strong>Entry Price</strong> <span class="color-blue">' . $order_arr['price'] . '</span></li>
					                <li><strong>Quantity</strong> <span class="color-blue">' . $order_arr['quantity'] . '</span></li>
					               <li><strong>Status</strong> <span class="color-blue">';
            if ($order_arr['status'] == 'buy') {
                $response .= '<span class="label label-success">Buy</span>';
            } else {
                $response .= '<span class="label label-success">New</span>';
            }
            $response .= '</span></li>
					               <li>
					               <button type="button" class="btn btn-info pull-right" id="edit_order_btn" order_id="' . $order_arr['_id'] . '" data-type="buy">Edit</button>
					               </li>
					            </ul>
					        </div>
					    </div>';

        }

        echo $response;
        exit;

    } //end get_order_details

    public function get_edit_order_details($order_id, $type) {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        if ($type == 'sell') {

            $order_arr = $this->mod_dashboard->get_order($order_id);
            $response = '<form id="edit_order_form" method="post">
						<div class="boat_points_iner">
					        <div class="boat_points_close">
					            <i class="fa fa-chevron-right" aria-hidden="true"></i>
					            <i class="fa fa-chevron-left" aria-hidden="true"></i>
					        </div>
					        <div class="boat_points_header" style="background: #f11919 none repeat scroll 0 0;">Order Details</div>
					        <div class="boat_points_body">
					            <ul>
					                <li><strong>Entry Price</strong>
					                <span class="color-blue">
					                <input type="text" class="form-control" name="purchased_price" value="' . $order_arr['purchased_price'] . '" >
					                </span>
					                </li>
					                <li><strong>Quantity</strong>
					                <span class="color-blue">
					                <input type="text" class="form-control" name="quantity" value="' . $order_arr['quantity'] . '" >
					                </span>
					                </li>
					                <li><strong>Profit Type</strong>
					                <span class="color-blue">
					                <select class="form-control" name="profit_type" id="profit_type">
				                      <option value="percentage"';if ($order_arr['profit_type'] == 'percentage') {$response .= 'selected';}$response .= '>Percentage</option>
				                      <option value="fixed_price"';if ($order_arr['profit_type'] == 'fixed_price') {$response .= 'selected';}$response .= '>Fixed Price</option>
				                    </select>
				                    </span>
				                    </li>';
            if ($order_arr['profit_type'] == 'percentage') {
                $style1 = 'style="display:block;"';
                $style2 = 'style="display:none;"';
            } else {
                $style1 = 'style="display:none;"';
                $style2 = 'style="display:block;"';
            }

            $response .= '<li id="sell_profit_percent_div" ' . $style1 . '><strong>Sell Profit (%)</strong>
					               	<span class="color-blue">
					               	<input type="text" name="sell_profit_percent" value="' . $order_arr['sell_profit_percent'] . '" class="form-control">
					               	</span>
					               	</li>

					               <li id="sell_profit_price_div" ' . $style2 . '><strong>Sell Price</strong>
					               <span class="color-blue">
					               <input type="text" name="sell_profit_price" value="' . $order_arr['sell_profit_price'] . '" class="form-control">
					               </span>
					               </li>

					               <li>
					               <input type="hidden" name="id" value="' . $order_arr['_id'] . '">
					               <button type="button" class="btn btn-info pull-right" id="update_order_btn" data-type="sell">Update</button>
					               </li>
					            </ul>
					        </div>
					    </div>
					    </form>';

        } else {

            $order_arr = $this->mod_dashboard->get_buy_order($order_id);
            $response = '<form id="edit_order_form" method="post">
						<div class="boat_points_iner">
					        <div class="boat_points_close">
					            <i class="fa fa-chevron-right" aria-hidden="true"></i>
					            <i class="fa fa-chevron-left" aria-hidden="true"></i>
					        </div>
					        <div class="boat_points_header">Order Details</div>
					        <div class="boat_points_body">
					            <ul>
					                <li>
					                <strong>Entry Price</strong>
					                <span class="color-blue">
					                <input type="text" class="form-control" name="price" value="' . $order_arr['price'] . '" >
					                </span>
					                </li>
					                <li>
					                <strong>Quantity</strong>
					                <span class="color-blue">
					                <input type="text" class="form-control" name="quantity" value="' . $order_arr['quantity'] . '" >
					                </span>
					                </li>
					                <li>
					               <input type="hidden" name="id" value="' . $order_arr['_id'] . '">
					               <button type="button" class="btn btn-info pull-right" id="update_order_btn" data-type="buy">Update</button>
					               </li>
					            </ul>
					        </div>
					    </div>
					    </form>';

        }

        echo $response;
        exit;

    } //end get_edit_order_details

    public function update_order_details() {

        //Login Check
        $this->mod_login->verify_is_admin_login();

        $type = $this->input->post('type');
        $order_id = $this->input->post('id');

        if ($type == 'sell') {

            //edit_order
            $edit_order = $this->mod_dashboard->edit_order($this->input->post());
            $this->get_order_details($order_id, $type);

        } else {

            //edit_buy_order
            $edit_buy_order = $this->mod_dashboard->edit_buy_order($this->input->post());
            $this->get_order_details($order_id, $type);

        }

    } //End update_order_details

    public function set_currency() {

        $symbol = $this->input->post('symbol');

        $sess_symbol = array(
            'global_symbol' => $symbol,
        );

        $this->session->set_userdata($sess_symbol);

    } //End set_currency

    public function set_application_mode() {

        $mode = $this->input->post('mode');

        $user_id = $this->session->userdata('admin_id');

        /*        $upd_data = array(
        'application_mode' => $this->db->escape_str(trim($mode))
        );

        $this->db->dbprefix('users');
        $this->db->where('id', $user_id);
         */

        $sess_symbol = array(
            'global_mode' => $mode,
        );

        $this->session->set_userdata($sess_symbol);

    } //End set_application_mode

    public function convert_price() {

        $sell_profit_percent = $this->input->post('sell_profit_percent');
        $purchased_price = $this->input->post('purchased_price');

        $sell_price = $purchased_price * $sell_profit_percent;
        $sell_price = $sell_price / 100;
        $sell_price = $sell_price + $purchased_price;

        echo number_format($sell_price, 8, '.', '');
        exit;

    } //End convert_price

    public function get_sell_order_status() {

        $order_id = $this->input->post('order_id');
        $id = $this->input->post('id');

        $order_status = $this->mod_dashboard->get_sell_order_status($id, $order_id);

        if ($order_status) {
            $this->autoload_market_data();
        }

    } //End get_sell_order_status

    public function get_buy_order_status() {

        $order_id = $this->input->post('order_id');
        $id = $this->input->post('id');

        $order_status = $this->mod_dashboard->get_buy_order_status($id, $order_id);

        if ($order_status) {
            $this->autoload_market_buy_data();
        }

    } //End get_buy_order_status

    public function get_buy_order_details_ajax() {

        $order_id = $this->input->post('order_id');
        $order_arr = $this->mod_dashboard->get_buy_order($order_id);
        $parent_id = $order_arr['buy_parent_id'];

        if (isset($order_arr['buy_parent_id']) && $order_arr['buy_parent_id'] != '') {
            $level = $this->order_level($order_arr['buy_parent_id']);
        } else {
            $level = '--';
        }
        if ($order_arr['parent_status'] == 'parent') {
            $order_count = $this->mod_dashboard->count_child_buy_order($order_id);
        }
        if ($order_arr['trigger_type'] != 'no') {
            $buy = "auto";
        }

        if ($order_arr['is_sell_order'] == 'sold') {
            if ($order_arr['is_manual_sold'] == 'yes') {
                $sell = "manual";
            } else {
                $sell = "auto";
            }
        } else {
            $sell = "";
        }
        $response = '<div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">ID :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . $order_arr['_id'] . '</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Price :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . num($order_arr['price']) . '</p>
                        </div>
                     </div>';
        if ($order_arr['parent_status'] == 'parent') {
            $response .= ' <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Child Orders :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . $order_count . '</p>
                        </div>
                     </div>';
        }
        $response .= ' <div class="row">
											<div class="col-md-6">
													<label for="inputTitle">Parent Level Order:</label>
											</div>
											<div class="col-md-6">
													<p>' . strtoupper(str_replace("_", " ", $level)) . '</p>
											</div>
                                     </div>';
        $response .= ' <div class="row">
                                            <div class="col-md-6">
                                                    <label for="inputTitle">Parent ID:</label>
                                            </div>
                                            <div class="col-md-6">
                                                    <p>' . str_replace("_", " ", $parent_id) . '</p>
                                            </div>
                                        </div>';
        $response .= ' <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Market Buy Price :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . num($order_arr['market_value']) . '</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Trigger Type:</label>
                        </div>
                        <div class="col-md-6">';
        if ($order_arr['trigger_type'] == 'no' || $order_arr['trigger_type'] == '') {
            $response .= '<td> Manual Order</td>';
        } else {
            $response .= '<td>' . strtoupper(str_replace('_', ' ', $order_arr['trigger_type'])) . '</td>';
        }
        $response .= '</div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Quantity :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . $order_arr['quantity'] . '</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Trail :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . ucfirst($order_arr['trail_check']) . '</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Trail Interval :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . $order_arr['trail_interval'] . '</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Order Type :</label>
                        </div>
                        <div class="col-md-6">
                            <label for="inputTitle">' . strtoupper($order_arr['order_type']) . '</label>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Created Date :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . $order_arr['created_date'] . '</p>
                        </div>
                     </div>
                      <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Last Action Date :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . $order_arr['modified_date'] . '</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Binance Order ID :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="label label-success">' . $order_arr['binance_order_id'] . '</span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Status :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="label label-success">' . ucfirst($order_arr['status']) . '</span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Buy :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="">' . ucfirst($buy) . '</span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Sell :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="">' . ucfirst($sell) . '</span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Buy Rule :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="">' . ucfirst($order_arr['buy_rule_number']) . '</span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Sell Rule :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="">' . (($order_arr['sell_rule_number'] == "0") ? "STOP-LOSS Order" : ucfirst($order_arr['sell_rule_number'])) . '</span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Order Mode :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="label label-success">' . strtoupper($order_arr['order_mode']) . '</span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Auto Sell :</label>
                        </div>
                        <div class="col-md-6">';
        if ($order_arr['auto_sell'] == 'yes') {
            $auto_sell = 'yes';
            $auto_sell_class = 'success';
        } else {
            $auto_sell = 'no';
            $auto_sell_class = 'danger';
        }
        $response .= '<span class="label label-' . $auto_sell_class . '">' . ucfirst($auto_sell) . '</span>
                        </div>
                     </div>';

        if ($order_arr['auto_sell'] == 'yes') {

            //Get Sell Temp Data
            $sell_data_arr = $this->mod_dashboard->get_temp_sell_data($order_id);
            $profit_type = $sell_data_arr['profit_type'];
            $sell_profit_percent = $sell_data_arr['profit_percent'];
            $sell_profit_price = $sell_data_arr['profit_price'];
            $order_type = $sell_data_arr['order_type'];
            $trail_check = $sell_data_arr['trail_check'];
            $trail_interval = $sell_data_arr['trail_interval'];
            $stop_loss = $sell_data_arr['stop_loss'];
            $loss_percentage = $sell_data_arr['loss_percentage'];

            $response .= '<br>
							 <div class="row">
		                        <div class="col-md-6">
		                            <label for="inputTitle">Profit Type :</label>
		                        </div>
		                        <div class="col-md-6">
		                            <p>' . ucfirst($profit_type) . '</p>
		                        </div>
		                     </div>
		                     <div class="row">
		                        <div class="col-md-6">
		                            <label for="inputTitle">Profit Percentage :</label>
		                        </div>
		                        <div class="col-md-6">
		                            <p>' . $sell_profit_percent . '</p>
		                        </div>
		                     </div>
		                     <div class="row">
		                        <div class="col-md-6">
		                            <label for="inputTitle">Profit Price :</label>
		                        </div>
		                        <div class="col-md-6">
		                            <p>' . $sell_profit_price . '</p>
		                        </div>
		                     </div>
		                     <div class="row">
		                        <div class="col-md-6">
		                            <label for="inputTitle">Order Type :</label>
		                        </div>
		                        <div class="col-md-6">
		                            <p>' . strtoupper($order_type) . '</p>
		                        </div>
		                     </div>
		                     <div class="row">
		                        <div class="col-md-6">
		                            <label for="inputTitle">Trail Check:</label>
		                        </div>
		                        <div class="col-md-6">
		                            <p>' . $trail_check . '</p>
		                        </div>
		                     </div>
		                     <div class="row">
		                        <div class="col-md-6">
		                            <label for="inputTitle">Trail Interval:</label>
		                        </div>
		                        <div class="col-md-6">
		                            <p>' . $trail_interval . '</p>
		                        </div>
		                     </div>
		                     <div class="row">
		                        <div class="col-md-6">
		                            <label for="inputTitle">Stop Loss:</label>
		                        </div>
		                        <div class="col-md-6">
		                            <p>' . $stop_loss . '</p>
		                        </div>
		                     </div>
		                     <div class="row">
		                        <div class="col-md-6">
		                            <label for="inputTitle">Loss Percentage:</label>
		                        </div>
		                        <div class="col-md-6">
		                            <p>' . $loss_percentage . '</p>
		                        </div>
		                     </div>';
        }

        if(!empty($order_arr['opportunityId'])){
            $response .='<div class="row">
		                        <div class="col-md-6">
		                            <label>Opportunity Id:</label>
		                        </div>
		                        <div class="col-md-6">
		                            <p>' . $order_arr['opportunityId'] . '</p>
		                        </div>
		                     </div>';
        }

        echo $response;
        exit;

    } //End get_buy_order_details_ajax

    // //Umer Abbas [7-11-19]
    public function get_buy_order_details_exchange_ajax() {

        $order_id = $this->input->post('order_id');
        $exchange = (!empty($this->input->post('exchange')) ? $this->input->post('exchange') : '');

        $order_arr = $this->mod_dashboard->get_buy_order_exchange($order_id, $exchange);
        $parent_id = $order_arr['buy_parent_id'];

        if (isset($order_arr['buy_parent_id']) && $order_arr['buy_parent_id'] != '') {
            $level = $this->order_level_exchange($order_arr['buy_parent_id'], $exchange);
        } else {
            $level = '--';
        }
        if ($order_arr['parent_status'] == 'parent') {
            $order_count = $this->mod_dashboard->count_child_buy_order_exchange($order_id, $exchange);
        }
        if ($order_arr['trigger_type'] != 'no') {
            $buy = "auto";
        }

        if ($order_arr['is_sell_order'] == 'sold') {
            if ($order_arr['is_manual_sold'] == 'yes') {
                $sell = "manual";
            } else {
                $sell = "auto";
            }
        } else {
            $sell = "Not sold yet";
        }
        $response = '<div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">ID :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . $order_arr['_id'] . '</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Price :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . num($order_arr['price']) . '</p>
                        </div>
                     </div>';
        if ($order_arr['parent_status'] == 'parent') {
            $response .= ' <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Child Orders :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . $order_count . '</p>
                        </div>
                     </div>';
        }
        $response .= ' <div class="row">
											<div class="col-md-6">
													<label for="inputTitle">Parent Level Order:</label>
											</div>
											<div class="col-md-6">
													<p>' . strtoupper(str_replace("_", " ", $level)) . '</p>
											</div>
                                     </div>';
        $response .= ' <div class="row">
                                            <div class="col-md-6">
                                                    <label for="inputTitle">Parent ID:</label>
                                            </div>
                                            <div class="col-md-6">
                                                    <p>' . str_replace("_", " ", $parent_id) . '</p>
                                            </div>
                                        </div>';
        $response .= ' <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Market Buy Price :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . num($order_arr['market_value']) . '</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Trigger Type:</label>
                        </div>
                        <div class="col-md-6">';
        if ($order_arr['trigger_type'] == 'no' || $order_arr['trigger_type'] == '') {
            $response .= '<td> Manual Order</td>';
        } else {
            $response .= '<td>' . strtoupper(str_replace('_', ' ', $order_arr['trigger_type'])) . '</td>';
        }
        $response .= '</div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Quantity :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . $order_arr['quantity'] . '</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Order Type :</label>
                        </div>
                        <div class="col-md-6">
                            <label for="inputTitle">' . strtoupper($order_arr['order_type']) . '</label>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Created Date :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . $order_arr['created_date'] . '</p>
                        </div>
                     </div>
                      <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Last Action Date :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . $order_arr['modified_date'] . '</p>
                        </div>
                     </div>';
                    if($exchange == 'binance'){
                        $response .= '<div class="row">
                                    <div class="col-md-6">
                                        <label for="inputTitle">Binance Order ID :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="label label-success">' . $order_arr['binance_order_id'] . '</span>
                                    </div>
                                 </div>';
                    }else{
                        $response .= '<div class="row">
                                        <div class="col-md-6">
                                            <label for="inputTitle">Kraken Order ID :</label>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="label label-success">' . $order_arr['kraken_order_id'] . '</span>
                                        </div>
                                     </div>';
                    }
        

        $response .='<div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Status :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="label label-success">' . ucfirst($order_arr['status']) . '</span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Buy :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="">' . ucfirst($buy) . '</span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Sell :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="">' . ucfirst($sell) . '</span>
                        </div>
                     </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Stop loss rule :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="">' . ucfirst($order_arr['stop_loss_rule']) . '</span>
                        </div>
                    </div>
                    <div class="row">
                                <div class="col-md-6">
                                    <label for="inputTitle">Stop Loss Percentage:</label>
                                </div>
                                <div class="col-md-6">
                                    <p>' . $order_arr['custom_stop_loss_percentage'] . '(-)</p>
                                </div>
                    </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Order Mode :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="label label-success">' . strtoupper($order_arr['order_mode']) . '</span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Auto Sell :</label>
                        </div>
                        <div class="col-md-6">';
        if ($order_arr['auto_sell'] == 'yes') {
            $auto_sell = 'yes';
            $auto_sell_class = 'success';
        } else {
            $auto_sell = 'no';
            $auto_sell_class = 'danger';
        }
        $response .= '<span class="label label-' . $auto_sell_class . '">' . ucfirst($auto_sell) . '</span>
                        </div>
                     </div>';

        if ($order_arr['auto_sell'] == 'yes') {

            //Get Sell Temp Data
            $sell_data_arr = $this->mod_dashboard->get_temp_sell_data_exchnage($order_id, $exchange);
            $profit_type = $sell_data_arr['profit_type'];
            $sell_profit_percent = $order_arr['sell_profit_percent'];
            $sell_profit_price = $order_arr['sell_price'];
            $order_type = $order_arr['order_type'];
            $trail_check = $sell_data_arr['trail_check'];
            $trail_interval = $sell_data_arr['trail_interval'];
            $stop_loss = $sell_data_arr['stop_loss'];
            $loss_percentage = $order_arr['custom_stop_loss_percentage'];

            $response .= '<br>
		                     <div class="row">
		                        <div class="col-md-6">
		                            <label for="inputTitle">Sell Profit Percentage :</label>
		                        </div>
		                        <div class="col-md-6">
		                            <p>' . $sell_profit_percent . '</p>
		                        </div>
		                     </div>
		                     <div class="row">
		                        <div class="col-md-6">
		                            <label for="inputTitle">Profit Price :</label>
		                        </div>
		                        <div class="col-md-6">
		                            <p>' . $sell_profit_price . '</p>
		                        </div>
		                     </div>
		                     <div class="row">
		                        <div class="col-md-6">
		                            <label for="inputTitle">Order Type :</label>
		                        </div>
		                        <div class="col-md-6">
		                            <p>' . strtoupper($order_type) . '</p>
		                        </div>
		                     </div>
		                     <div class="row">
		                        <div class="col-md-6">
		                            <label for="inputTitle">Loss Percentage:</label>
		                        </div>
		                        <div class="col-md-6">
		                            <p>' . $loss_percentage . '(-)</p>
		                        </div>
		                     </div>';
        }

        if (!empty($order_arr['opportunityId'])) {
            $response .= '<div class="row">
                                        <div class="col-md-6">
                                            <label>Opportunity Id:</label>
                                        </div>
                                        <div class="col-md-6">
                                            <p>' . $order_arr['opportunityId'] . '</p>
                                        </div>
                                    </div>';
        }

        echo $response;
        exit;

    } //End get_buy_order_details_exchange_ajax

    public function order_level($buy_parent_id) {
        $search_array['_id'] = $buy_parent_id;
        $this->mongo_db->where($search_array);
        $res = $this->mongo_db->get('buy_orders');
        $order_level_arr = iterator_to_array($res);
        $order_level = $order_level_arr[0]['order_level'];

        return $order_level;
    }
    
    // //order_level_exchange //Umer Abbas [7-11-19]
    public function order_level_exchange($buy_parent_id, $exchange='') {

        if(!empty($exchange)){
            if($exchange != 'binance'){
                $exchange = "_$exchange";
            }else{
                $exchange = "";
            }
        }

        $search_array['_id'] = $buy_parent_id;
        $this->mongo_db->where($search_array);
        $res = $this->mongo_db->get("buy_orders$exchange");
        $order_level_arr = iterator_to_array($res);
        $order_level = $order_level_arr[0]['order_level'];

        return $order_level;
    }//end order_level_exchange

    public function get_sell_order_details_ajax() {

        $order_id = $this->input->post('order_id');
        $order_arr = $this->mod_dashboard->get_order($order_id);

        $response = '<div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">ID :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . $order_arr['_id'] . '</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Purchased Price :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . $order_arr['purchased_price'] . '</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Market Sell Price :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . num($order_arr['market_value']) . '</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Quantity :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . $order_arr['quantity'] . '</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Trail :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . ucfirst($order_arr['trail_check']) . '</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Trail Interval :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . $order_arr['trail_interval'] . '</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Order Type :</label>
                        </div>
                        <div class="col-md-6">
                            <label for="inputTitle">' . strtoupper($order_arr['order_type']) . '</label>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Created Date :</label>
                        </div>
                        <div class="col-md-6">
                            <p>' . $order_arr['created_date'] . '</p>
                        </div>
                     </div>';

        if ($order_arr['stop_loss'] == 'yes') {
            $response .= '<div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Stop Loss :</label>
                        </div>
                        <div class="col-md-6">
                            <label for="inputTitle">' . ucfirst($order_arr['stop_loss']) . '</label>
                        </div>
	                    </div>
	                   	<div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Stop Loss Percentage :</label>
                        </div>
                        <div class="col-md-6">
                            <label for="inputTitle">' . $order_arr['loss_percentage'] . '%</label>
                        </div>
	                    </div>';
        }

        $response .= '<div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Binance Order ID :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="label label-success">' . $order_arr['binance_order_id'] . '</span>
                        </div>
                     </div>
                      <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Status :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="label label-success">' . ucfirst($order_arr['status']) . '</span>
                        </div>
                     </div>


                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Buy :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="label label-success">' . ucfirst($buy) . '</span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Sell :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="label label-success">' . ucfirst($sell) . '</span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Buy Rule :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="label label-success">' . ucfirst($order_arr['buy_rule_number']) . '</span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <label for="inputTitle">Sell Rule :</label>
                        </div>
                        <div class="col-md-6">
                            <span class="label label-success">' . (($order_arr['sell_rule_number'] == 0) ? "STOP-LOSS Order" : ucfirst($order_arr['sell_rule_number'])) . '</span>
                        </div>
                     </div>';

        echo $response;
        exit;

    } //End get_sell_order_details_ajax

    public function autoload_market_statistics() {
        //Login Check
        $this->mod_login->verify_is_admin_login();

        //Get Market Value
        $symbol = $this->input->post('coin');
        if (empty($symbol)) {
            $symbol = $this->session->userdata('global_symbol');
        }
        $market_value = $this->mod_dashboard->get_market_value($symbol);

        //Check Buy Zones
        $check_buy_zones = $this->mod_dashboard->check_buy_zones($market_value);
        $in_zone = $check_buy_zones['in_zone'];
        $type = $check_buy_zones['type'];
        $start_value = $check_buy_zones['start_value'];
        $end_value = $check_buy_zones['end_value'];

        //Get Coin Balance
        $coin_balance = $this->mod_dashboard->get_coin_balance($symbol);

        $response = '<ul>
	                 <li>
	                    <span><b>Current Market</b></span>
	                    <span class="count">' . $market_value . '</span>
	                 </li>
	                 <li>
	                    <span><b>In Zone ' . ucfirst($type) . '</b></span>
	                    <span class="count">' . ucfirst($in_zone) . '</span>
	                 </li>';
        if ($type == 'sell') {
            $response .= '<li>
	                    <span><b>Closest Sell Zone</b></span>
	                    <span class="count">' . $start_value . ' - ' . $end_value . '</span>
	                 </li>';
        } else {
            $response .= '<li>
	                    <span><b>Closest Buy Zone</b></span>
	                    <span class="count">' . $start_value . ' - ' . $end_value . '</span>
	                 </li>';
        }
        $response .= '<li>
	                    <span><b>Pressure</b></span>
	                    <span class="count">Up</span>
	                 </li>
	                 <li>
	                    <span><b>Available Quantity</b></span>
	                    <span class="count">' . $coin_balance . '</span>
	                 </li>
	                </ul>';

        echo $response;
        exit;

    } //end autoload_market_statistics

    public function reset_filters($type) {

        $this->session->unset_userdata('filter-data');
        redirect(base_url() . 'admin/dashboard/orders-listing');

    } //End reset_filters

    public function reset_buy_filters($type) {

        $this->session->unset_userdata('filter-data-buy');
        redirect(base_url() . 'admin/buy_orders');

    } //End reset_buy_filters

    public function autoload_notifications($id = '') {

        $notifications = $this->mod_dashboard->get_notifications($id);

        echo $message = $notifications['message'] . "|" . $notifications['_id'];
        exit;

    } //End autoload_notifications

    public function set_buy_price() {

        //Get Market Value
        $coin = $this->input->post('coin');
        $market_value = $this->mod_dashboard->get_market_value($coin);

        $score_avg = $this->mod_dashboard->get_score_avg($coin);

        $score_avg = ($score_avg - 30) * 2.5;
        echo $market_value . '|' . round($score_avg);
        exit;

    } //End set_buy_price

    public function get_coin_balance() {
        $balance = $this->mod_dashboard->get_coin_balance();
        echo $balance;
        exit;
    }

    public function waqar_testing($id) {
        $this->mongo_db->where(array('user_id' => $id));
        $get = $this->mongo_db->get('user_login_log');
        echo "<pre>";
        print_r(iterator_to_array($get));
        exit;
    }

    public function bitcoin_reference() {

        $start_date = "2017-01-01";
        $end_date = date("Y-m-d");

        $set_url = "https://api.coindesk.com/v1/bpi/historical/close.json?start=" . $start_date . "&end=" . $end_date . "";

        //echo $set_url; exit;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $set_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);

        $data_arr = json_decode($response);

        $my_arr = (array) $data_arr;
        $bpi = (array) $my_arr['bpi'];

        $data['bitcoin_prices'] = $bpi;

        $this->stencil->paint("admin/dashboard/bit_coin_price", $data);

    }

}
