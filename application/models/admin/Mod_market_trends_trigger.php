<?php
class mod_market_trends_trigger extends CI_Model {

	function __construct() {
		# code...
	}


	public function is_triggers_qualify_to_buy_orders($coin_symbol,$order_level,$settings,$coinMeta,$trends) {
        $trends = (array)$trends;
        unset($trends['_id']);
        unset($trends['coin']);
        
        $trends['black_wall'] = $coinMeta['black_wall_pressure'];
        $trends['seven_level_pressure'] = $coinMeta['seven_level_depth'];

        $is_buy_rule_enable = $settings['enable_buy_market_trends_trigger'];
        if ($is_buy_rule_enable == 'not' || $is_buy_rule_enable == '') {
            $log['Market_trends_Level_'.$order_level.'_status'] = '<span style="color:red">OFF</span>';
           return $log;
        }//End of 

        $log['Rule Type'] = '<span style="color: green;font-size: 27px;">Buy Rules</span><br>';
        $log['Order_Is_Buyed_By_Level'] = $order_level . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Buyers  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

       $rules =  $this->triggers_trades->grep_rules( $pattern="/(market_trend)/",(array)$settings,$flags=0,$type='buy');
       $log = $this->compare($rules,$trends,$log,'buy');
       return  $log;
    } //End of is_triggers_qualify_to_buy_orders

    

    public function is_triggers_qualify_to_sell_orders($coin_symbol,$order_level,$settings,$coinMeta,$trends) {
        $trends = (array)$trends;
        unset($trends['_id']);
        unset($trends['coin']);
        
        $trends['black_wall'] = $coinMeta['black_wall_pressure'];
        $trends['seven_level_pressure'] = $coinMeta['seven_level_depth'];

        $is_buy_rule_enable = $settings['enable_sell_market_trends_trigger'];
        if ($is_buy_rule_enable == 'not' || $is_buy_rule_enable == '') {
            $log['Market_trends_Level_'.$order_level.'_status'] = '<span style="color:red">OFF</span>';
           return $log;
        }//End of 

        $log['Rule Type'] = '<span style="color: green;font-size: 27px;">Sell Rules</span><br>';
        $log['Order_Is_sell_By_Level'] = $order_level . '<br>';
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%% Buyers  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

       $rules =  $this->triggers_trades->grep_rules( $pattern="/(market_trend)/",(array)$settings,$flags=0,$type='sell');
       $log = $this->compare($rules,$trends,$log,$type="sell");
       return  $log;
    } //End of is_triggers_qualify_to_sell_orders


    public function compare($rules,$trends,$log,$type){

      
        foreach($trends as $trend_key => $current_value){
            
            if($trend_key == 'buy'){
                if($type == 'buy'){
                    $pattern = 'buy_trend';
                }else{
                    $pattern = $trend_key;
                }
               
            }else if($trend_key == 'sell'){;
                if($type == 'sell'){
                    $pattern = 'sell_trend';
                }else{
                    $pattern = $trend_key;
                }
            }else{
                $pattern = $trend_key;
            }

            $pattern ="/(".$pattern.")/"; 
            $return_rule = $this->triggers_trades->get_rule($pattern,$rules,$flags=0);

            if(!empty($return_rule)){
                $on_off_rules = '';
                    $operator = '';
                    $compare_value = '';
                foreach ($return_rule as $key => $value) {
                    
                    if($value == 'yes' || $value == 'no'){
                        $on_off_rules = $value;
                    }else if($value == '==' || $value == '<=' || $value == '>='){
                        $operator = $value;
                    }else{
                        $compare_value = $value;
                    }
                }//End of foreach
            }//End of if rules not empty
          $reponse[]= $this->response_message($on_off_rules,$current_value,$operator,$compare_value,$trend_key);

          $is_meet = true;
          $log_message = '';

         foreach ($log as $key => $value) {
            $log_message .= $key.'  <b>'.$value.'</b><br>';
         }
          $log_message .= '<br>';
            
          
          foreach ($reponse as $row) {
             if($row['message'] == 'NO'){
                $is_meet = false;
             }

             foreach ($row['log'] as $key => $value) {
                $log_message .= $key.'  <b>'.$value.'</b><br>';
             }
             $log_message .= '<br>';
          }// %%%%%%%%%%% -- End of foreach -- %%%%%%%%%%555 

          $reponse_message['log_message'] = $log_message;
          $reponse_message['success_message'] =  ($is_meet)?'YES':'NO';
     

        }// %%%%%% -- End of foreach -- %%%%%%%%%%

        return  $reponse_message;

    }//End of compare value


    public function response_message($on_off_rules,$current_value,$operator,$compare_value,$trend_key){
        $log = array();
        $message = '';
        if($on_off_rules == '' || $on_off_rules == 'not'){
            $log[$trend_key.'_status'] = '<span style="background-color:yellow">OFF</span>';
            $log[$trend_key.'_operator'] = $operator;
            $log[$trend_key.'_compare_value'] = $compare_value;
            $log[$trend_key.'_current_value'] = $current_value;
            $message = 'YES';
        }else if($on_off_rules == 'yes'){

            switch ($operator) {
                case '==':
                    $log[$trend_key.'_status'] = '<span style="color:red">NO</span>';
                    $message = 'NO';
                    if($current_value == $compare_value){
                        $log[$trend_key.'_status'] = '<span style="color:green">YES</span>';
                        $message = 'YES';
                    }
                    $log[$trend_key.'_operator'] = $operator;
                    $log[$trend_key.'_compare_value'] = $compare_value;
                    $log[$trend_key.'_current_value'] = $current_value;
                    break;

                    case '<=':
                    $log[$trend_key.'_status'] = '<span style="color:red">NO</span>';
                    $message = 'NO';
                    if($current_value <= $compare_value){
                        $message = 'YES';
                        $log[$trend_key.'_status'] = '<span style="color:green">YES</span>';
                    }
                    $log[$trend_key.'_operator'] = $operator;
                    $log[$trend_key.'_compare_value'] = $compare_value;
                    $log[$trend_key.'_current_value'] = $current_value;
                    break;


                    case '>=':
                    $message = 'NO';
                    $log[$trend_key.'_status'] = '<span style="color:red">NO</span>';
                    if($current_value >= $compare_value){
                        $message = 'YES';
                        $log[$trend_key.'_status'] = '<span style="color:green">YES</span>';
                    }
                    $log[$trend_key.'_operator'] = $operator;
                    $log[$trend_key.'_compare_value'] = $compare_value;
                    $log[$trend_key.'_current_value'] = $current_value;
                    break;
            }// %%%%%%%%% -- End of Switch -- %%%%%%%%55
        }//End of rule check on off

        $resp['message'] = $message;
        $resp['log'] = $log;
        return $resp;
    }//End of response_message


} //End of mod_TEST_Barrier_trigger
?>