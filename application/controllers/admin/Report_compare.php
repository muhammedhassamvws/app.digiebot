<?php
/**
 *
 */
class Report_compare extends CI_Controller {

    function __construct() {

        parent::__construct();
        //load main template
        ini_set("memory_limit", -1);
        // ini_set("display_errors", E_ALL);
        // error_reporting(E_ALL);
        $this->stencil->layout('admin_layout');
        //load required slices
        $this->stencil->slice('admin_header_script');
        $this->stencil->slice('admin_header');
        $this->stencil->slice('admin_left_sidebar');
        $this->stencil->slice('admin_footer_script');
        //if($_SERVER['REMOTE_ADDR'] == '101.50.127.131' ){
        //echo "<pre>";   print_r($responseArr); exit;
        //}

        //load models
        $this->load->model('admin/mod_report');
        $this->load->model('admin/mod_dashboard');
        $this->load->model('admin/mod_coins');
        $this->load->model('admin/mod_login');
        $this->load->model('admin/mod_buy_orders');

        if ($this->session->userdata('user_role') != 1) {
            redirect(base_url() . 'forbidden');
        }
        // if ($this->session->userdata('special_role') != 1) {
        //     redirect(base_url() . 'forbidden');
        // }

    }

    public function compare_test_live_report() {

        if ($this->input->post()) {
            // ini_set("display_errors", E_ALL);
            // error_reporting(E_ALL);
            $data_arr['filter_order_data'] = $this->input->post();
            $this->session->set_userdata($data_arr);

            $start_date = $this->input->post('filter_by_start_date');
            $end_date = $this->input->post('filter_by_end_date');


            $symbol = $this->input->post('filter_by_coin');
            $trigger = $this->input->post('filter_by_trigger');

            $order_level = $this->input->post('filter_by_level');
            

            $test_count_parent = $this->get_parent_orders($symbol,$order_level,$trigger,'test_live');
           
            $live_count_parent = $this->get_parent_orders($symbol,$order_level,$trigger,'live');
       
            
            
            $to_time = strtotime($start_date);
            $from_time = strtotime($end_date);

            $total_minute = round(abs($to_time - $from_time) / 60,2);
   

            $hours = $total_minute/60;
            
            $over_all_report = array();
            $index = 1;
            while($index <= $hours) {
                $incremnt = $index+3;
                $order_count_arr = array();
                $str_date = date('Y-m-d H:00:00',strtotime("+ ".$index." hours",strtotime($start_date)));
                $end_date = date('Y-m-d H:59:59',strtotime("+ ".$incremnt." hours",strtotime($start_date)));

              

                $live_count_sold = $this->get_sold_order_count($symbol,$order_level,$trigger,'live',$str_date,$end_date);
                
                if($live_count_sold >0){
                    $order_count_arr['live_count_sold'] = $live_count_sold;
                }
                


                $test_count_sold = $this->get_sold_order_count($symbol,$order_level,$trigger,'test_live',$str_date,$end_date);

                if($test_count_sold >0){
                 $order_count_arr['test_count_sold'] = $test_count_sold;
                }

                $live_count_open = $this->get_open_order_count($symbol,$order_level,$trigger,'live',$str_date,$end_date);

                if($live_count_open >0){
                    $order_count_arr['live_count_open'] = $live_count_open;
                }
    
                $test_count_open = $this->get_open_order_count($symbol,$order_level,$trigger,'test_live',$str_date,$end_date);
                if($test_count_open >0){
                    $order_count_arr['test_count_open'] = $test_count_open;
                }
                

                if(!empty($order_count_arr)){
                    
                    $order_count_arr['live_count_sold'] = $live_count_sold;
                    $order_count_arr['test_count_sold'] = $test_count_sold;
                    $order_count_arr['live_count_open'] = $live_count_open;
                    $order_count_arr['test_count_open'] = $test_count_open;

                    $order_count_arr['str_date'] = $str_date;
                    $order_count_arr['end_date'] = $end_date;

                    
                    $order_count_arr['test_count_parent'] = $test_count_parent;
                    $order_count_arr['live_count_parent'] = $live_count_parent;

                    $order_count_arr['symbol'] = $symbol;
                    

                    $over_all_report[] = $order_count_arr;
                }
            
                $index = $index+3;
            } 
        

        }
        $data['over_all_report'] = $over_all_report;

        $coins = $this->mod_coins->get_all_coins();
        $data['coins'] = $coins;

        $this->stencil->paint('admin/reports/compare_order', $data);
    }//End of compare_test_live_report

  
    public function get_parent_orders($coin_symbol,$order_level,$trigger_type,$order_mode) {
		$where['order_mode'] = $order_mode;
		$where['pause_status'] = 'play';
		$where['trigger_type'] = $trigger_type;
		$where['symbol'] = $coin_symbol;
		$where['status'] = 'new';
        $where['parent_status'] = 'parent';
        if(!empty($order_level)){
            if(is_array($order_level)){
                $where['order_level'] = array('$in'=>$order_level);
            }else{
                $where['order_level'] = $order_level;
            }
        }
        
        $db = $this->mongo_db->customQuery(); 
        $resp =  $db->buy_orders->count($where);
        return $resp; 
    } //End of get_parent_orders


    public function get_sold_order_count($symbol,$order_level,$trigger,$type,$start_date,$end_date){
        $db = $this->mongo_db->customQuery();
        $search_arr['symbol'] = $symbol;
        $search_arr['trigger_type'] = $trigger;
        $search_arr['created_date']['$gte'] = $this->mongo_db->converToMongodttime($start_date);
        $search_arr['created_date']['$lte'] = $this->mongo_db->converToMongodttime($end_date);
        $search_arr['order_mode'] = $type;    
        if($order_level !=''){
            $search_arr['order_level'] = $order_level;
        }
       return $db->sold_buy_orders->count($search_arr);
    }//End of get_sold_order_count


    public function get_open_order_count($symbol,$order_level,$trigger,$type,$start_date,$end_date){
        $db = $this->mongo_db->customQuery();
        $search_arr['is_sell_order'] = 'yes';
        $search_arr['status'] = 'FILLED';
        $search_arr['symbol'] = $symbol;
        $search_arr['trigger_type'] = $trigger;
        $search_arr['created_date']['$gte'] = $this->mongo_db->converToMongodttime($start_date);
        $search_arr['created_date']['$lte'] = $this->mongo_db->converToMongodttime($end_date);
        $search_arr['order_mode'] = $type;    
        if($order_level !=''){
            $search_arr['order_level'] = $order_level;
        }
       return $db->buy_orders->count($search_arr);
    }//End of get_open_order_count


}// %%%%%%%%%%% -- End of controller -- %%%%%%%%%%%%% 
