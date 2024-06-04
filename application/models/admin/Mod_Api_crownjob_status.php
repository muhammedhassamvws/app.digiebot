<?php
class Mod_Api_crownjob_status extends CI_Model {

    function __construct() {

        parent::__construct();
        
    }
    public function get_cron_listing(){

        $data = $this->mongo_db->get('cronjob_listing_update');
        return iterator_to_array($data);
    }
    public function getallusers($coin='TRXBTC' ,$id){
        $coin =(string) $coin; 
        if($id){
            $where['_id'] = $id;
            $this->mongo_db->where($where);
            $data = $this->mongo_db->get('users');
            $data = iterator_to_array($data);
            
        }else{

            $data = $this->mongo_db->get('users');
            $data = iterator_to_array($data);
        }

        $reps = array();

           foreach($data as $row){
               $arr = array();
              
                $userId = (string) $row['_id'];
                $username = (string) $row['username'];

               $totalQty = $this->calulateQty($userId,$coin);
               $digidbaladf = $this->get_digi_bal($userId,$coin);
               $binancebaladf = $this->get_binance_bal($userId,$coin);
                
               if($totalQty !=0){
                  $arr['_id']=$userId ;
                  $arr['username'] = $username;
                  $arr['totalQty'] = $totalQty;
                  $arr['coin'] = $coin;
                  $arr['digidbaladf'] = $digidbaladf;
                  $arr['binancebal'] = $binancebaladf;
                  $reps[] = $arr;
               }
           if($id){break;}
           }
         
           $data['userFinalData'] = $reps;
           
       return $data['userFinalData'];
       
    } //END OF   getallusers

    public function calulateQty($userId,$symbol){

       $where['order_mode'] = 'live';
       $where['status']['$in'] = array('FILLED','LTH');
       $where['symbol'] = $symbol;
       $where['is_sell_order'] = 'yes';
       $where['admin_id'] = $userId;
       $this->mongo_db->limit(10);
       $this->mongo_db->where($where);
       $get_obj = $this->mongo_db->get('buy_orders');
       $buy_orders = iterator_to_array($get_obj);
       $totalQty = 0;

       foreach($buy_orders as $row){

           $totalQty +=$row['quantity'];

       }

       return $totalQty;
    }//End of calulateQty

    public function get_binance_bal($user_id , $symbol ){

       $balance_arr = $this->binance_api->get_account_balance($symbol, $user_id);
       $symbol =  str_replace("BTC","",$symbol);
       return  $balance_arr[$symbol]['available'];  

    }//End of get_binance_bal

    public function get_digi_bal($userId ,$symbol){

       $_id = (string) $userId;
       $where['user_id'] = $_id;
       $where['coin_symbol'] = $symbol;
       $this->mongo_db->limit(10);
       $this->mongo_db->where($where);
       $data1 = $this->mongo_db->get('user_wallet');
       $data = iterator_to_array($data1);
       $reps1 = array();

       foreach($data as $row){

          $digi_bal= $row['coin_balance'];
       }
       return $digi_bal;
   }//End of get_digi_bal
    public function getusername($userId){

        $where['_id'] = $userId;
        $this->mongo_db->where($where);
        $data1 = $this->mongo_db->get('users');
        $data = iterator_to_array($data1);

        foreach($data as $row){

        $username = $row['username'];
        }
        return $username;
    }//End of getusername

    public function is_server_is_closed(){
        $date = date('Y-m-d H:i:s',strtotime('-15 minutes'));
        $created_date = $this->mongo_db->converToMongodttime($date);
        $db = $this->mongo_db->customQuery();
        $search['created_date'] =array('$gte'=>$created_date);
        $response_obj = $db->is_server_running->find($search);
        $response = iterator_to_array($response_obj);

        $resp = true;
        if(count($response )>0){
            $resp =false;
        }
        return $resp;
    }//End of is_server_is_closed

    public function get_user_trade_info($user_id = '') {


        //$this->mod_login->verify_is_admin_login();
        $this->mongo_db->where(array("admin_id" => $user_id, 'status' => 1));
        $get_response = $this->mongo_db->get("user_account_history");
        $totalBTCspent = 0;
        $totalBTCgain = 0;
        $fullArr = array();
        foreach ($get_response as $key => $value) {
            $retArr = array();

            $retArr['buy_id'] =(string) $value['buy_id'];
            $retArr['created_date'] = $this->getdate($value['buy_id']);
            $retArr['coin'] = $value['coin'];
            $retArr['buy_fee_deducted'] = $value['buy_fee_deducted'];
            $retArr['buy_fee_symbol'] = $value['buy_fee_symbol'];
            $retArr['buy_price'] = $value['buy_price'];
            $retArr['buy_qty'] = $value['buy_qty'];
            $retArr['totalBuyBTC'] = ($value['buy_price'] * $value['buy_qty']);
            $retArr['buy_time_btc_usd'] = $value['buy_time_btc_usd'];
            $retArr['buy_time_wallet'] = $value['buy_time_wallet'];
            $retArr['fee_in_btc'] = $value['fee_in_btc'];
            $retArr['sell_fee_deducted'] = $value['sell_fee_deducted'];
            $retArr['sell_fee_in_btc'] = $value['sell_fee_in_btc'];
            $retArr['sell_fee_symbol'] = $value['sell_fee_symbol'];
            $retArr['sell_price'] = $value['sell_price'];
            $retArr['totalSoldBTC'] = ($value['sell_price'] * $value['buy_qty']);
            $retArr['sell_time_btc_usd'] = $value['sell_time_btc_usd'];
            $retArr['sell_time_wallet'] = $value['sell_time_wallet'];
            $retArr['ProfitLossBTC'] = ((($retArr['totalSoldBTC'] - ($retArr['totalBuyBTC'] + $retArr['sell_fee_in_btc'] + $retArr['fee_in_btc'])) / $retArr['totalSoldBTC']) * 100);
            if (!empty($value['buy_qty'])) {
                $totalBTCspent += $retArr['totalBuyBTC'];
                $totalBTCgain += $retArr['totalSoldBTC'];
                array_push($fullArr, $retArr);
            }
           
            
        }
        return $fullArr;

    } // get_user_trade_info
    public function getdate($id){
        //$id = (string) $id;
        $this->mongo_db->where(array("_id" => $this->mongo_db->mongoId($id)));
        $get_response = $this->mongo_db->get("sold_buy_orders");
        $list = iterator_to_array($get_response);

        foreach($list as $key => $value){

            $date = $value['created_date'];
            $new_Date = $date->toDateTime()->format("Y-m-d H:i:s");
            
        }

        return $new_Date;
    }

    public function check_duplication($start_date , $end_date ,$status){
        
       
        //$end = (string) $end;
        $data['userFinalData'] = $this->get_order_logs($start_date , $end_date , $status );
        return $data['userFinalData'];
       
    }//check_duplication

    public function get_order_logs($start , $end , $status) {

        $pipeline = array(
            array(
                '$project' => array(
                    "type" => 1,
                    "count" => 1,
                    "log_msg" => 1,
                    'created_date' => 1,
                    'order_id' => 1,
                ),
            ),
           array(
                '$match' => array(
                    'type' => $status,
                  
                ),
            ), 
            array('$group' => array(
                '_id' => array('order_id' => '$order_id'),
                'count' => array('$sum' => 1),
                'type' => array('$first' => '$type'),
                'log_msg' => array('$first' => '$log_msg'),
                'order_id' => array('$first' => '$order_id'),
                'created_date' => array('$first' => '$created_date'),
            ),
            ), 
            array('$sort' => array('created_date' => -1)),    
        );
        $allow = array('allowDiskUse' => true);
        $db = $this->mongo_db->customQuery();

        $response = $db->orders_history_log->aggregate($pipeline,$allow);

        $row = iterator_to_array($response);
        $resp=array();
        foreach($row as $value){

            
            $res = array();
            $counts = $value['count'];
            $arr =  $value['order_id'];
            $created_at = $value['created_date']->toDateTime()->format("d-M, Y H:i:s");
            $created_date = $value['created_date'];


            if($counts >= 2 ){
                if($created_date >= $start && $created_date <= $end){

                    $oid = (string) $arr;
                    $uid = $this->get_buyorder($oid);
                    //$uid = (string) $uid;
                    $username = $this->get_users($uid);
                    $coin = $this->coin($oid);
                    if($uid){
    
                        $res['oid'] = $oid;
                        $res['user'] = $username;
                        $res['coin'] = $coin;
                        $res['counts'] = $counts;
                        $res['created_date'] = $created_at;
                        $resp[] = $res;
                    }
                }

            }
            else{
                continue;
            }

        }

        $data['userFinalData'] = $resp;
        // echo "<pre>";
        // print_r($data);
        // exit;
        return $created_date;

    }//get_order_logs

    public function user_trade_profit_report() {


    }//get_order_logs
    public function add_records($upd_arr) {


        $ins = $this->mongo_db->insert('income_detail_collection', $upd_arr);
        if ($ins) {

            return true;

        } else {

            return false;

        } //end if
        
    }

    public function fetch_record() {


        $ins = $this->mongo_db->get('income_detail_collection');
        $ins = iterator_to_array($ins);
        $res = array();
        foreach($ins as $arr){
            $resp = array();

            $resp['id'] = $arr['id'];
            $resp['coin'] = $arr['coin'];
            $resp['price'] = $arr['price'];
            $resp['qty'] = $arr['qty'] ;
            $date = $arr['date'];
            $resp['rate'] = $arr['rate'];
            $resp['date'] = $date->toDateTime()->format("Y-m-d H:i:s");;

            $res[] = $resp;
        }
            
        

        return $res;
        
    }

    
}
