<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Crons extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('admin/mod_login');

        $this->stencil->layout('admin_layout');
        //load required slices
        $this->stencil->slice('admin_header_script');
        $this->stencil->slice('admin_header');
        $this->stencil->slice('admin_left_sidebar');
        $this->stencil->slice('admin_footer_script');
    }
        //////////////////////////////////////////////////////////////////
        /////////////////           BINANCE     /////////////////////////
        /////////////////////////////////////////////////////////////////
    public function reset_buy_order_status(){
        $this->mod_login->verify_is_admin_login();
        $current_hour =  date('Y-m-d H:i:s', strtotime('-20 minutes'));
		$current_time_tenmint = $this->mongo_db->converToMongodttime($current_hour);
        $where['application_mode'] = 'live';
        $where['order_mode']       =  'live';
        $where['status']           = array('$in'=>array('error','FILLED_submitted_for_sell', 'submitted_for_sell', 'FILLED_ERROR', 'SELL_ID_ERROR', 'submitted_ERROR', 'fraction_ERROR'));
        $where['is_sell_order']    = ['$nin'=> ['sold', 'resume_pause']];
        $where['script_fix']       = array('$exists'=>false); 
        $where['modified_date']    = array('$lte'=>$current_time_tenmint);
        $where['cost_avg']         = ['$exists' => false]; 
        $where['resume_status']    = ['$exists' => false];

        // $where['admin_id'] = '5c0912b7fc9aadaac61dd072';
        $condition = array('limit'=> 5); 

        $db = $this->mongo_db->customQuery();
        $data = $db->buy_orders->find($where, $condition);
        $res = iterator_to_array($data);
        echo "<br> count = ".count($res);
        $set = array(
            'status' => 'FILLED',
            'script_fix' => 1
        );
        $set1 = array(
            'status' => 'new',
            'script_fix' => 1
        );
        $set2 = array(
            'script_fix' => 0
        );
        foreach($res as $value){
            echo "<br> order id= ".$value['_id'];
            $price_search['coin'] = $value['symbol'];
            $this->mongo_db->where($price_search);
            $priceses = $this->mongo_db->get('market_prices');
            $final_prices = iterator_to_array($priceses);	
            echo "<br> Purchase price = ".$value['purchased_price'];
            echo "<br> Current Market = ".$final_prices[0]['price'];		
            $open_lth_puchase_price = (float) ($final_prices[0]['price'] - $value['purchased_price'])/ $value['purchased_price'];
            $open_lth_avg_per_trade = (float) $open_lth_puchase_price * 100;
            echo "<br> P/L profit = ".$open_lth_avg_per_trade;

            $update_where['_id'] = $this->mongo_db->mongoId($value['_id']);
            $update_where_sell['buy_order_id'] = $this->mongo_db->mongoId($value['_id']);
            if($value['iniatial_trail_stop'] < $value['purchased_price']){   //check mean SP is negitive and curret p/l > 0.5
                $db->buy_orders->updateOne($update_where, ['$set'=> $set]);
                $db->orders->updateOne($update_where_sell, ['$set'=> $set1]);
                echo "<br>done";
            }elseif($value['iniatial_trail_stop'] > $value['purchased_price'] && $open_lth_avg_per_trade > $value['custom_stop_loss_percentage']){   //check mean SP is positive and curret p/l > CSL percentage
                $db->buy_orders->updateOne($update_where, ['$set'=> $set]);
                $db->orders->updateOne($update_where_sell, ['$set'=> $set1]);
                echo "<br>else done";
            }elseif($value['stop_loss_rule'] == '' && $value['lth_functionality'] == 'no' ){
                $db->buy_orders->updateOne($update_where, ['$set'=> $set]);
                $db->orders->updateOne($update_where_sell, ['$set'=> $set1]);
                echo "<br>else done";
            }else{
                $db->buy_orders->updateOne($update_where, ['$set'=> $set2]);
                echo "<br>not updated ";
            }
        }//end loop
    }//end function

    //////////////////////////////////////////////////////////////////
    /////////////////           KRAKEN       /////////////////////////
    /////////////////////////////////////////////////////////////////

    public function reset_buy_order_status_kraken(){
        $this->mod_login->verify_is_admin_login();
        $current_hour =  date('Y-m-d H:i:s', strtotime('-20 minutes'));
		$current_time_tenmint = $this->mongo_db->converToMongodttime($current_hour);
        $where['application_mode'] = 'live';
        $where['status']           = array('$in'=>array('error','FILLED_submitted_for_sell', 'submitted_for_sell', 'FILLED_ERROR', 'SELL_ID_ERROR', 'submitted_ERROR', 'fraction_ERROR'));
        $where['is_sell_order']    = ['$nin'=> ['sold', 'resume_pause']];
        $where['script_fix']       = array('$exists' => false); 
        $where['modified_date']    = array('$lte'=>$current_time_tenmint);
        $where['cost_avg']         = ['$exists' => false]; 
        $where['resume_status']    = ['$exists' => false];


        // $where['admin_id'] = '5c0915befc9aadaac61dd1b8';
        $condition = array('limit'=> 5); 

        $db = $this->mongo_db->customQuery();
        $data = $db->buy_orders_kraken->find($where, $condition);
        $res = iterator_to_array($data);
        echo "<br> count = ".count($res);
        $set = array(
            'status' => 'FILLED',
            'script_fix' => 1
        );
        $set1 = array(
            'status' => 'new',
            'script_fix' => 1
        );
        $set2 = array(
            'script_fix' => 0
        );
        foreach($res as $value){
            echo "<br> order id= ".$value['_id'];
            $price_search['coin'] = $value['symbol'];
            $this->mongo_db->where($price_search);
            $priceses = $this->mongo_db->get('market_prices_kraken');
            $final_prices = iterator_to_array($priceses);	
            echo "<br> Purchase price = ".$value['purchased_price'];
            echo "<br> Current Market = ".$final_prices[0]['price'];		
            $open_lth_puchase_price = (float) ($final_prices[0]['price'] - $value['purchased_price'])/ $value['purchased_price'];
            $open_lth_avg_per_trade = (float) $open_lth_puchase_price * 100;
            echo "<br> P/L profit = ".$open_lth_avg_per_trade;

            $update_where['_id'] = $this->mongo_db->mongoId($value['_id']);
            $update_where_sell['buy_order_id'] = $this->mongo_db->mongoId($value['_id']);
            // if($open_lth_avg_per_trade > 0.5 && $value['iniatial_trail_stop'] < $value['purchased_price']){    //check mean SP is negitive and curret p/l > 0.5
            if($value['iniatial_trail_stop'] < $value['purchased_price']){    
                $db->buy_orders_kraken->updateOne($update_where, ['$set'=> $set]);
                $db->orders_kraken->updateOne($update_where_sell, ['$set'=> $set1]);
                echo "<br>done";
            }elseif($value['iniatial_trail_stop'] > $value['purchased_price'] && $open_lth_avg_per_trade > $value['custom_stop_loss_percentage']){ //check mean SP is positive and curret p/l > CSL percentage
                $db->buy_orders_kraken->updateOne($update_where, ['$set'=> $set]);
                $db->orders_kraken->updateOne($update_where_sell, ['$set'=> $set1]);
                echo "<br>else done";
            }elseif($value['stop_loss_rule'] == '' && $value['lth_functionality'] == 'no' ){
                    $db->buy_orders_kraken->updateOne($update_where, ['$set'=> $set]);
                    $db->orders_kraken->updateOne($update_where_sell, ['$set'=> $set1]);
                    echo "<br>else done";
            }else{
                $db->buy_orders_kraken->updateOne($update_where, ['$set'=> $set2]);
                echo "<br>not updated";
            }
        }//end loop
    }//end function

    //////////////////////////////////////////////////////////////////
    /////////////////           BAM         /////////////////////////
    /////////////////////////////////////////////////////////////////

    public function reset_buy_order_status_bam(){
        $this->mod_login->verify_is_admin_login();
        $current_hour =  date('Y-m-d H:i:s', strtotime('-20 minutes'));
        $current_time_tenmint = $this->mongo_db->converToMongodttime($current_hour);
        $where['application_mode'] = 'live';
        $where['status']           = array('$in'=>array('error','FILLED_submitted_for_sell', 'submitted_for_sell', 'FILLED_ERROR', 'SELL_ID_ERROR', 'submitted_ERROR', 'fraction_ERROR'));
        $where['is_sell_order']    = ['$nin'=> ['sold', 'resume_pause']];
        $where['script_fix']       = ['$exists' => false]; 
        $where['cost_avg']         = ['$exists' => false]; 
        $where['modified_date']    = ['$lte'=>$current_time_tenmint];
        $where['resume_status']    = ['$exists' => false];


        // $where['admin_id'] = '5c0915befc9aadaac61dd1b8';
        $condition = array('limit'=> 5); 

        $db = $this->mongo_db->customQuery();
        $data = $db->buy_orders_bam->find($where, $condition);
        $res = iterator_to_array($data);
        echo "<br> count = ".count($res);
        $set = array(
            'status' => 'FILLED',
            'script_fix' => 1
        );
        $set1 = array(
            'status' => 'new',
            'script_fix' => 1
        );
        $set2 = array(
            'script_fix' => 0
        );
        foreach($res as $value){
            echo "<br> order id= ".$value['_id'];
            $price_search['coin'] = $value['symbol'];
            $this->mongo_db->where($price_search);
            $priceses = $this->mongo_db->get('market_prices_bam');
            $final_prices = iterator_to_array($priceses);	
            echo "<br> Purchase price = ".$value['purchased_price'];
            echo "<br> Current Market = ".$final_prices[0]['price'];		
            $open_lth_puchase_price = (float) ($final_prices[0]['price'] - $value['purchased_price'])/ $value['purchased_price'];
            $open_lth_avg_per_trade = (float) $open_lth_puchase_price * 100;
            echo "<br> P/L profit = ".$open_lth_avg_per_trade;

            $update_where['_id'] = $this->mongo_db->mongoId($value['_id']);
            $update_where_sell['buy_order_id'] = $this->mongo_db->mongoId($value['_id']);
            if($value['iniatial_trail_stop'] < $value['purchased_price']){    //check mean SP is negitive
                $db->buy_orders_bam->updateOne($update_where, ['$set'=> $set]);
                $db->orders_bam->updateOne($update_where_sell, ['$set'=> $set1]);
                echo "<br>done";
            }elseif($value['iniatial_trail_stop'] > $value['purchased_price'] && $open_lth_avg_per_trade > $value['custom_stop_loss_percentage']){ //check mean SP is positive and curret p/l > CSL percentage
                $db->buy_orders_bam->updateOne($update_where, ['$set'=> $set]);
                $db->orders_bam->updateOne($update_where_sell, ['$set'=> $set1]);
                echo "<br>else done";
            }elseif($value['stop_loss_rule'] == '' && $value['lth_functionality'] == 'no' ){
                $db->buy_orders_bam->updateOne($update_where, ['$set'=> $set]);
                $db->orders_bam->updateOne($update_where_sell, ['$set'=> $set1]);
                echo "<br>else done";
            }else{
                $db->buy_orders_bam->updateOne($update_where, ['$set'=> $set2]);
                echo "<br>not updated";
            }
        }//end loop
    }//end function
         
    public function cornsHistoryDisplay(){
        $this->mod_login->verify_is_admin_login();
        $db = $this->mongo_db->customQuery();
        //delete deta older than 1 month
        $current_to_previous = date('Y-m-d H:i:s', strtotime('-15 days'));
        $converted_time      = $this->mongo_db->converToMongodttime($current_to_previous);
        $where['enteryTime'] = array('$lte' => $converted_time);
        $db->corn_stops_history->deleteMany($where);
        //end 
 
        $cornHistoryReturn = $db->corn_stops_history->count([]);
        $history['total'] = $cornHistoryReturn;
        $config['base_url'] = base_url() .'admin/crons/cornsHistoryDisplay';
        $config['total_rows'] = $cornHistoryReturn;
    
        $config['per_page'] = 50;
        $config['num_links'] = 3;
        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 4;
        $config['reuse_query_string'] = TRUE;
    
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
    
        $config['prev_link'] = '&laquo;';
    
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
    
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
    
        $config['cur_tag_open'] = '<li class="active"><a href="#"><b>';
        $config['cur_tag_close'] = '</b></a></li>';
    
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        if($page !=0){
            $page = ($page-1) * $config['per_page'];
        }
        $history["links"] = $this->pagination->create_links();
        $query =
            [
                ['$sort'  => ['enteryTime' => -1]],
                ['$skip'  => $page],
                ['$limit' => 50],
            ];
        $response_1 = $db->corn_stops_history->aggregate($query);   
        $cornHistory = iterator_to_array($response_1);
        $history['final_array'] = $cornHistory;
        $this->stencil->paint('admin/cron_listing/cornHistoryDisplay',$history);
    }
    
    public function allowedIP(){
        $ip =   getenv('HTTP_CLIENT_IP') ?:
            getenv('HTTP_X_FORWARDED_FOR') ?:
            getenv('HTTP_X_FORWARDED') ?:
            getenv('HTTP_FORWARDED_FOR') ?:
            getenv('HTTP_FORWARDED') ?:
            getenv('REMOTE_ADDR');
        // check ip and user name are allowed for login or not 
        $parameter = array(
            'ip_address'             => '182.180.59.120',
        );
       
        $curl = curl_init();
        $jsondata = json_encode($parameter);
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://rules.digiebot.com/apiEndPoint/is_ipaddress_whitelisted",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>$jsondata,
        CURLOPT_HTTPHEADER => array(
            "Authorization: ipwhitelisted#Um4dRaZ3evBhGDZVICd3",
            "Content-Type: application/json"
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $responce_Data = json_decode($response);
        print_r( $responce_Data); exit;
       return $responce_Data;
    }

}//end controller