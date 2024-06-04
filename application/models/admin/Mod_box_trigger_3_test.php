<?php
class mod_box_trigger_3_test extends CI_Model {

	function __construct() {
		# code...
	}

	public function is_triggers_qualify_to_buy_orders($coin_symbol, $order_level,$global_setting_arr,$current_market_price) {

        $response['success_message'] = $is_rules_true;
        $response['log_message'] = $log_msg;
        return $response;
	}//End of is_triggers_qualify_to_buy_orders
	
}//-- End Of Box Model --
?>