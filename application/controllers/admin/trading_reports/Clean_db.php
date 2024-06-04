<?php
/**
 *
 */
class Clean_db extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();

        // error_reporting(E_ALL & ~E_NOTICE);
        // ini_set('display_errors', E_ALL & ~E_NOTICE);

    }

    public function stop_execution_by_time(){
        $execution_time = 1;
        $starttime = time();
        // sleep(1);
        $i = 0;
        while (1) {
            echo "$i <br>";
            $i++;
            $now = time() - $starttime;
            if ($now > $execution_time) {
                // break after $execution_time seconds
                echo "Code execution breaks after $execution_time seconds.";
                break;
            }
        }
    }

    //clean_order_logs
    public function clean_order_logs()
    {
        // "order_ids___orders_history_log_13_01_20_backup",
        // "order_ids___orders_history_log_2019_backup",
        $log_collection = [
            // "order_ids___orders_history_log",
            // "order_ids___orders_history_log_2019_backup",
            "order_ids___orders_history_log_bam",
            "order_ids___orders_history_log_bam_live_2019_10",
            "order_ids___orders_history_log_bam_live_2019_11",
            "order_ids___orders_history_log_bam_live_2020_0",
            "order_ids___orders_history_log_bam_live_2020_1",
            "order_ids___orders_history_log_bam_live_2020_2",
            "order_ids___orders_history_log_bam_live_2020_3",
            "order_ids___orders_history_log_bam_live_2020_4",
            "order_ids___orders_history_log_bam_live_2020_5",
            "order_ids___orders_history_log_bam_live_2020_6",
            "order_ids___orders_history_log_bam_live_2020_7",
            "order_ids___orders_history_log_bam_live_2020_8",
            "order_ids___orders_history_log_bam_test_2019_11",
            "order_ids___orders_history_log_bam_test_2020_0",
            "order_ids___orders_history_log_bam_test_2020_1",
            "order_ids___orders_history_log_bam_test_2020_2",
            "order_ids___orders_history_log_bam_test_2020_3",
            "order_ids___orders_history_log_bam_test_2020_4",
            "order_ids___orders_history_log_bam_test_2020_5",
            "order_ids___orders_history_log_bam_test_2020_6",
            "order_ids___orders_history_log_bam_test_2020_7",
            "order_ids___orders_history_log_bam_test_2020_8",
            "order_ids___orders_history_log_coinbasepro",
            "order_ids___orders_history_log_kraken",
            "order_ids___orders_history_log_kraken_2020_8",
            "order_ids___orders_history_log_kraken_live_2020_2",
            // "order_ids___orders_history_log_kraken_live_2020_3",
            // "order_ids___orders_history_log_kraken_live_2020_4",
            // "order_ids___orders_history_log_kraken_live_2020_5",
            // "order_ids___orders_history_log_kraken_live_2020_6",
            "order_ids___orders_history_log_kraken_live_2020_7",
            "order_ids___orders_history_log_kraken_live_2020_8",
            "order_ids___orders_history_log_kraken_test_2020_3",
            "order_ids___orders_history_log_kraken_test_2020_4",
            "order_ids___orders_history_log_kraken_test_2020_5",
            "order_ids___orders_history_log_kraken_test_2020_6",
            "order_ids___orders_history_log_kraken_test_2020_7",
            "order_ids___orders_history_log_kraken_test_2020_8",
            "order_ids___orders_history_log_live_2019_0",
            "order_ids___orders_history_log_live_2019_1",
            "order_ids___orders_history_log_live_2019_10",
            "order_ids___orders_history_log_live_2019_11",
            "order_ids___orders_history_log_live_2019_2",
            "order_ids___orders_history_log_live_2019_3",
            "order_ids___orders_history_log_live_2019_4",
            "order_ids___orders_history_log_live_2019_5",
            "order_ids___orders_history_log_live_2019_6",
            "order_ids___orders_history_log_live_2019_8",
            "order_ids___orders_history_log_live_2020_0",
            "order_ids___orders_history_log_live_2020_1",
            "order_ids___orders_history_log_live_2020_2",
            "order_ids___orders_history_log_live_2020_3",
            "order_ids___orders_history_log_live_2020_4",
            "order_ids___orders_history_log_live_2020_5",
            "order_ids___orders_history_log_live_2020_6",
            "order_ids___orders_history_log_live_2020_7",
            "order_ids___orders_history_log_live_2020_8",
            "order_ids___orders_history_log_test_2019_11",
            "order_ids___orders_history_log_test_2020_0",
            "order_ids___orders_history_log_test_2020_1",
            "order_ids___orders_history_log_test_2020_2",
            "order_ids___orders_history_log_test_2020_3",
            "order_ids___orders_history_log_test_2020_4",
            "order_ids___orders_history_log_test_2020_5",
            "order_ids___orders_history_log_test_2020_6",
            "order_ids___orders_history_log_test_2020_7",
            "order_ids___orders_history_log_test_2020_8",
            "order_ids___orders_history_log_undefined_2020_8",
            "order_ids___orders_history_log_undefined_live_2020_8",
            "order_ids___orders_history_log_undefined_test_2020_8",
        ];

        $total_order_ids_collection = count($log_collection);

        $db = $this->mongo_db->customQuery();

        $query_count_stop = 1000;
        $query_count = 0;


        echo "<pre>";
        for($i=0; $i<$total_order_ids_collection; $i++){

            echo $log_collection[$i]."<br>";

            $order_ids_col = $log_collection[$i];
            $log_col = str_replace('order_ids___', '', $log_collection[$i]);


            //if bam order
            if(strpos($log_col, 'bam') !== false){
                
                //query to_check
                //get 100 order ids
                $resp = $db->$order_ids_col->find(['_id' => ['$ne'=>null]], ['limit'=>100]);
                $resp = iterator_to_array($resp);
                if(count($resp) == 0){
                    continue;
                }

                $query_count++;
                if($query_count > $query_count_stop){
                    break;
                }

                foreach($resp as $val){
                    $order = $db->buy_orders_bam->find(['_id'=> $this->mongo_db->mongoId((string)$val['_id'])]);
                    $order = iterator_to_array($order);

                    // remove order ids
                    $db->$order_ids_col->deleteMany(['_id' => ['$in' => [$this->mongo_db->mongoId((string)$val['_id']), (string)$val['_id']]]]);

                    if(count($order) > 0){
                        // echo "<br> bam buy order found";
                        continue;
                    }
                    
                    $order = $db->sold_buy_orders_bam->find(['_id'=> $this->mongo_db->mongoId((string)$val['_id'])]);
                    $order = iterator_to_array($order);
                    if(count($order) > 0){
                        // echo "<br> bam sold order found";
                        continue;
                    }
                    
                    //remove logs
                    $db->$log_col->deleteMany(['_id'=> ['$in' => [$this->mongo_db->mongoId((string)$val['_id']), (string)$val['_id']]]]);
                    
                    $query_count++;
                    if($query_count > $query_count_stop){
                        break;
                    }
                }

            //if kraken order
            }else if(strpos($log_col, 'kraken') !== false){
                
                //query to_check
                //get 100 order ids
                $resp = $db->$order_ids_col->find(['_id' => ['$ne' => null]], ['limit' => 100]);
                $resp = iterator_to_array($resp);
                if(count($resp) == 0){
                    continue;
                }

                $query_count++;
                if($query_count > $query_count_stop){
                    break;
                }

                if(count($resp) > 0){

                    foreach($resp as $val){
                        
                        // print_r($val);
                        // print_r($val['_id']);

                        $order = $db->buy_orders_kraken->find(['_id'=> $this->mongo_db->mongoId((string)$val['_id'])]);
                        $order = iterator_to_array($order);

                        //remove order ids
                        // $db->$order_ids_col->deleteMany(['_id'=> ['$in' => [$this->mongo_db->mongoId((string) $val['_id']), (string)$val['_id']]]]);

                        if(count($order) > 0){
                            // echo "<br> kraken buy order found  ----------  $order_ids_col";
                            continue;
                        }
                        
                        $order = $db->sold_buy_orders_kraken->find(['_id'=> $this->mongo_db->mongoId((string)$val['_id'])]);
                        $order = iterator_to_array($order);
                        if(count($order) > 0){
                            // echo "<br> kraken sold order found ----------  $order_ids_col";
                            continue;
                        }
                        
                        //remove logs
                        $db->$log_col->deleteMany(['_id'=> ['$in' => [$this->mongo_db->mongoId((string) $val['_id']), (string)$val['_id']]]]);
                        
                        $query_count++;
                        if($query_count > $query_count_stop){
                            break;
                        }
                    }
                }


            //if binance order
            }else{

                //query to_check
                //get 100 order ids
                $resp = $db->$order_ids_col->find(['_id' => ['$ne' => null]], ['limit' => 100]);
                $resp = iterator_to_array($resp);
                if(count($resp) == 0){
                    continue;
                }

                $query_count++;
                if($query_count > $query_count_stop){
                    break;
                }

                foreach($resp as $val){
                    $order = $db->buy_orders->find(['_id'=> $this->mongo_db->mongoId((string)$val['_id'])]);
                    $order = iterator_to_array($order);

                    // remove order ids
                    $db->$order_ids_col->deleteMany(['_id' => ['$in' => [$this->mongo_db->mongoId((string)$val['_id']), (string)$val['_id']]]]);

                    if(count($order) > 0){
                        // echo "<br> binance buy order found";
                        continue;
                    }
                    
                    $order = $db->sold_buy_orders->find(['_id'=> $this->mongo_db->mongoId((string)$val['_id'])]);
                    $order = iterator_to_array($order);
                    if(count($order) > 0){
                        // echo "<br> binance sold order found";
                        continue;
                    }

                    // echo "<br> aaaaaaaaaa <br>";
                    
                    // remove logs
                    $db->$log_col->deleteMany(['_id'=> ['$in' => [$this->mongo_db->mongoId((string)$val['_id']), (string)$val['_id']]]]);
                    
                    $query_count++;
                    if($query_count > $query_count_stop){
                        break;
                    }
                }

            }

            $query_count++;
            if($query_count > $query_count_stop){
                break;
            }

        }
        echo '<br><br> ********************** script end ***************** <br><br>';
    } //End clean_order_logs

    public function last_cron_execution_time($name, $duration, $summary)
    {
        //Hit CURL to update last cron execution time
        $params = [
            'name' => $name,
            'cron_duration' => $duration,
            'cron_summary' => $summary,
        ];
        $req_arr = [
            'req_type' => 'POST',
            'req_params' => $params,
            'req_endpoint' => '',
            'req_url' => 'http://35.171.172.15:3000/api/save_cronjob_execution',
        ];
        $resp = hitCurlRequest($req_arr);

    } //End last_cron_execution_time

}
