<?php
class mod_candlereport_test extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    //======================================================================
    // Get Coin Offest Value
    //======================================================================
    public function get_coin_offset_value($symbol)
    {
        $this->mongo_db->where(array(
            'symbol' => $symbol,
            'user_id' => 'global',
            'exchange_type' => 'binance',
        ));
        $get_coin = $this->mongo_db->get('coins');
        $coin_arr = iterator_to_array($get_coin);
        $data     = $coin_arr[0];
        return $data;
    } //end get_coin_offset_value()
	
	//======================================================================
    // Get get_loopback_forward
    //======================================================================
	public function get_loopback_forward($arr,$look_forward_back,$current_index){
		
            $total_back = 0;
            $total_far  = 0;
			
            for ($index = 1 ; $index <= $look_forward_back  ; $index++) { 
            	
                $back = $current_index -$index;
                $back = ($back <0)?0:$back;

                $far  = $current_index+$index;
                $far  = ($far >count($arr))?count($arr):$far;

                $total_back += $arr[$back]['close'];
                $total_far  += $arr[$far]['close'];
            }
            $fianlArr = array();
			$fianlArr['total_back'] = $total_back / $look_forward_back;
			$fianlArr['total_far']  = $total_far / $look_forward_back;
			return $fianlArr;
	}//get_loopback_forward
	
	
	
	//======================================================================
    // Get Coin Offest Value
    //======================================================================
    public function get_candle_box_data($look_forward_back,$candel_stick_arr,$boxWidthPerc,$valueToMul,$initailVal)
    {
		
	   $look_forward_back = $look_forward_back;
       $current_index     = 0; 
	   
	  
       foreach ($candel_stick_arr as $key => $row) {
		   
		   
		    if($look_forward_back==1){
				
				$valueToMul       = $valueToMul;
				$compareValBack   = num($row['close']) ;
				$compareValForw   = num($row['close']) ;
				$compareValMult   = num($row['close']) * $valueToMul;
			}else{
			    $getArrForwarBack = $this->mod_candlereport_test->get_loopback_forward($candel_stick_arr,$look_forward_back,$current_index);
			    $compareValBack   = num($getArrForwarBack['total_back']) ;
			    $compareValForw   = num($getArrForwarBack['total_far']) ;
			}
			$current_index++;
			$boxWidthPerc        = $boxWidthPerc;
			$valueToMul          = $valueToMul;
			
			if ($key == 0) {
				$perceantage            =  num(($percent / 100) *  num($row['close']));
				$newPriceToMakeBoxTop   =  num($row['close']) + $perceantage;
				$newPriceToMakeBoxDown  =  num($row['close']) - $perceantage;
			    $initailVal             =  $initailVal; 
				//======================================================================
				// Operational Percentage 
				//======================================================================
				$perceantage_op            =  num(($op_percent / 100) *  num($row['close']));
				$newPriceToMakeBoxTop_op   =  num($row['close']) + $perceantage_op;
				$newPriceToMakeBoxDown_op  =  num($row['close']) - $perceantage_op;
			    $initailVal_op             =  $initailVal; 
				
				//======================================================================
				// Direct Percentage 
				//======================================================================
				$perceantage_direct            =  num(($direct_percent / 100) *  num($row['close']));
				$newPriceToMakeBoxTop_direct   =  num($row['close']) + $perceantage_direct;
				$newPriceToMakeBoxDown_direct  =  num($row['close']) - $perceantage_direct;
			    $initailVal_direct             =  $initailVal; 
				
            }
            if ($compareValForw > $newPriceToMakeBoxTop) {
		     
				// Second step code here
                $color                 = 'blue';
                $fianlArr['top']       = $initailVal + $boxWidthPerc; /// For general use 
                $fianlArr['bottom']    = $initailVal  ;
                $fianlArr['color']     = $color;
				//======================================================================
				$fianlArr['close']     = $row['close'];
				$fianlArr['open']      = $row['open'];
				$fianlArr['high']      = $row['high'];
				$fianlArr['low']       = $row['low'];
				$fianlArr['openTime_human_readible']     = $row['openTime_human_readible'];
				$fianlArr['global_swing_status']         = $row['global_swing_status'];
				//======================================================================
                $myArray[]             = $fianlArr;
				$initailVal            = $initailVal + $boxWidthPerc;
				$initailValForBott     = $initailVal;
				//======================================================================
				// Defined The Top and Bottom Value 
				//======================================================================
				$perceantage           = num(($percent / 100) *  num($row['close']));
				$newPriceToMakeBoxTop  = num($row['close']) + $perceantage;
				$newPriceToMakeBoxDown = num($row['close']) - $perceantage;
				$runingPosition        = $color;
				
            } else  if ($compareValBack < $newPriceToMakeBoxDown) {
                // Second step code here
                $color                 = 'red';
                $fianlArr['top']       = $initailVal - $boxWidthPerc; /// For general use 
                $fianlArr['bottom']    = $initailVal - ($boxWidthPerc + $boxWidthPerc) ;
                $fianlArr['color']     = $color;
				//======================================================================
				$fianlArr['close']     = $row['close'];
				$fianlArr['open']      = $row['open'];
				$fianlArr['high']      = $row['high'];
				$fianlArr['low']       = $row['low'];
				$fianlArr['openTime_human_readible']     = $row['openTime_human_readible'];
				$fianlArr['global_swing_status']         = $row['global_swing_status'];
				//======================================================================
                $myArray[]             = $fianlArr;
				$initailVal            = $initailVal - $boxWidthPerc;
				//======================================================================
				// Defined The Top and Bottom Value
				//======================================================================
				$newPriceToMakeBoxDown = num($row['close']);
				$perceantage           = num(($percent / 100) *  num($row['close']));
				$newPriceToMakeBoxTop  = num($row['close']) + $perceantage;
				$newPriceToMakeBoxDown = num($row['close']) - $perceantage;
				$runingPosition        = $color;
            } 
		}
		
	
		return $myArray;
    } //end get_candle_box_data()
	
	
	//======================================================================
    // Get Coin Offest Value
    //======================================================================
    public function get_candle_box_type($candleBoxArray,$percent,$op_percent,$direct_percent, $boxWidthPerc,$valueToMul,$initailVal,$look_back)
    {
	
	   $look_forward_back = $look_back;
       $current_index     = 0; 
	   
        foreach($candleBoxArray  as $key => $row){
			
			 if($look_forward_back==1){
				
				$valueToMul       = $valueToMul;
				$compareValBack   = num($row['close']) ;
				$compareValForw   = num($row['close']) ;
				$compareValMult   = num($row['close']) * $valueToMul;
			}else{
			    $getArrForwarBack = $this->mod_candlereport_test->get_loopback_forward($candleBoxArray,$look_forward_back,$current_index);
			    $compareValBack   = num($getArrForwarBack['total_back']) ;
			    $compareValForw   = num($getArrForwarBack['total_far']) ;
			}
			$current_index++;
		
		    $percent          = $percent;
			$op_percent       = $op_percent;
			$direct_percent   = $direct_percent;
		    $boxWidthPerc     = $boxWidthPerc;
			$valueToMul       = $valueToMul;
			$compareVal       = num($row['close']) ;
			$compareValMult   = num($row['close']) * $valueToMul;
			
			if ($key == 0) {
			    $perceantage            =  num(($percent / 100) *  num($row['close']));
				$newPriceToMakeBoxTop   =  num($row['close']) + $perceantage;
				$newPriceToMakeBoxDown  =  num($row['close']) - $perceantage;
			    $initailVal             =  $initailVal; 
				$color                  =  $row['color'] ;
				//======================================================================
				// Operational Percentage 
				//======================================================================
				$perceantage_op            =  num(($op_percent / 100) *  num($row['close']));
				$newPriceToMakeBoxTop_op   =  num($row['close']) + $perceantage_op;
				$newPriceToMakeBoxDown_op  =  num($row['close']) - $perceantage_op;
			    $initailVal_op             =  $initailVal; 
				
				//======================================================================
				// Direct Percentage 
				//======================================================================
				$perceantage_direct            =  num(($direct_percent / 100) *  num($row['close']));
				$newPriceToMakeBoxTop_direct   =  num($row['close']) + $perceantage_direct;
				$newPriceToMakeBoxDown_direct  =  num($row['close']) - $perceantage_direct;
			    $initailVal_direct             =  $initailVal; 
				continue;
            }
			if($color == 'blue'  && $row['color']=='blue'){
					 if ($compareValForw > $newPriceToMakeBoxTop_op) {
							// Second step code here
							$color                 = $row['color'];
							$fianlArr['top']       = $initailVal + $boxWidthPerc; /// For general use 
							$fianlArr['bottom']    = $initailVal  ;
							$fianlArr['color']     = $color;
							$fianlArr['key']       = $key;
							//===================== Calcalte the slick ===============================
							$slickTop  = $compareVal - $newPriceToMakeBoxTop_op;
							$calcultePerceOfGivenT = ($slickTop / $row['close']) * 100;  
							
							$finalSlickTop         = num(($calcultePerceOfGivenT / 100) *  $fianlArr['top']);
							$fianlArr['topSlick']  = $finalSlickTop + $fianlArr['top'];
							
							$slickBot  =  $compareVal -  $newPriceToMakeBoxTop_op;
							$calcultePerceOfGivenB = ($slickBot / $row['close']) * 100;  
							$finalSlickBot         = num(($calcultePerceOfGivenB / 100) *  $fianlArr['bottom']);
							$fianlArr['botSlick']  =  $fianlArr['bottom'] - $finalSlickBot ;
							
							//======================================================================
							$fianlArr['close']     = $row['close'];
							$fianlArr['open']      = $row['open'];
							$fianlArr['high']      = $row['high'];
							$fianlArr['low']       = $row['low'];
							
							$fianlArr['openTime_human_readible']     = $row['openTime_human_readible'];
							$fianlArr['global_swing_status']         = $row['global_swing_status'];
							//======================================================================
							$myArray[]             = $fianlArr;
							$initailVal            = $initailVal + $boxWidthPerc;
							$initailValForBott     = $initailVal;
							//======================================================================
							// Defined The Top and Bottom Value 
							//======================================================================
							$perceantage             = num(($percent / 100) *  num($row['close']));
							$newPriceToMakeBoxTop    = num($row['close']) + $perceantage;
							$newPriceToMakeBoxDown   = num($row['close']) - $perceantage;
							$runingPosition          = $color;
							
							//======================================================================
							// Defined The Top and Bottom Value Operational change
							//======================================================================
							$perceantage_op            =  num(($op_percent / 100) *  num($row['close']));
				            $newPriceToMakeBoxTop_op   =  num($row['close']) + $perceantage_op;
				            $newPriceToMakeBoxDown_op  =  num($row['close']) - $perceantage_op;
							
							//======================================================================
							// Defined The Top and Bottom Value Directional Change
							//======================================================================
							$perceantage_direct            =  num(($direct_percent / 100) *  num($row['close']));
				            $newPriceToMakeBoxTop_direct   =  num($row['close']) + $perceantage_direct;
				            $newPriceToMakeBoxDown_direct  =  num($row['close']) - $perceantage_direct;
					 }
			}
			if($color == 'blue'  && $row['color']=='red'){
			          if  ($compareValBack < $newPriceToMakeBoxDown_direct) {
							// Second step code here
							$color                 = $row['color'];
							$fianlArr['top']       = $initailVal - $boxWidthPerc; /// For general use 
							$fianlArr['bottom']    = $initailVal - ($boxWidthPerc + $boxWidthPerc) ;
							$fianlArr['color']     = $color;
							$fianlArr['key']       = $key;
							
							//===================== Calcalte the slick ===============================
							
							$slickTop  =  $newPriceToMakeBoxDown_direct - $compareVal;
							$calcultePerceOfGivenT = ($slickTop / $row['close']) * 100;  
							$finalSlickTop         = num(($calcultePerceOfGivenT / 100) *  $fianlArr['top']);
							$fianlArr['topSlick']  = $fianlArr['top'] + $finalSlickTop;
							
							$slickBot  = $newPriceToMakeBoxDown_direct - $compareVal ;
							$calcultePerceOfGivenB = ($slickBot / $row['open']) * 100;  
							$finalSlickBot         = num(($calcultePerceOfGivenB / 100) *  $fianlArr['bottom']);
							$fianlArr['botSlick']  = $fianlArr['bottom'] - $finalSlickBot;
						
							//======================================================================
							$fianlArr['close']     = $row['close'];
							$fianlArr['open']      = $row['open'];
							$fianlArr['high']      = $row['high'];
							$fianlArr['low']       = $row['low'];
							$fianlArr['openTime_human_readible']     = $row['openTime_human_readible'];
							$fianlArr['global_swing_status']         = $row['global_swing_status'];
							//======================================================================
							$myArray[]             = $fianlArr;
							$initailVal            = $initailVal - $boxWidthPerc;
							//======================================================================
							// Defined The Top and Bottom Value
							//======================================================================
							$newPriceToMakeBoxDown  = num($row['close']);
							$perceantage            = num(($percent / 100) *  num($row['close']));
							$newPriceToMakeBoxTop   = num($row['close']) + $perceantage;
							$newPriceToMakeBoxDown  = num($row['close']) - $perceantage;
							$runingPosition         = $color;
							//======================================================================
							// Defined The Top and Bottom Value Operational change
							//======================================================================
							$perceantage_op            =  num(($op_percent / 100) *  num($row['close']));
				            $newPriceToMakeBoxTop_op   =  num($row['close']) + $perceantage_op;
				            $newPriceToMakeBoxDown_op  =  num($row['close']) - $perceantage_op;
							
							//======================================================================
							// Defined The Top and Bottom Value Directional Change
							//======================================================================
							$perceantage_direct            =  num(($direct_percent / 100) *  num($row['close']));
				            $newPriceToMakeBoxTop_direct   =  num($row['close']) + $perceantage_direct;
				            $newPriceToMakeBoxDown_direct  =  num($row['close']) - $perceantage_direct;
					  }
			}
			if($color == 'red'  && $row['color']=='blue'){
				if ($compareValForw > $newPriceToMakeBoxTop_direct) {
				   // Second step code here
							$color                 = $row['color'];
							$fianlArr['top']       = $initailVal + $boxWidthPerc; /// For general use 
							$fianlArr['bottom']    = $initailVal  ;
							$fianlArr['color']     = $color;
							$fianlArr['key']       = $key;
							//===================== Calcalte the slick ===============================
							$slickTop  = $compareVal - $newPriceToMakeBoxTop_direct;
							$calcultePerceOfGivenT = ($slickTop / $row['close']) * 100;  
							$finalSlickTop         = num(($calcultePerceOfGivenT / 100) *  $fianlArr['top']);
							$fianlArr['topSlick']  = $finalSlickTop + $fianlArr['top'];
							
							$slickBot  = $compareVal - $newPriceToMakeBoxTop_direct;
							$calcultePerceOfGivenB = ($slickBot / $row['open']) * 100;  
							$finalSlickBot         = num(($calcultePerceOfGivenB / 100) *  $fianlArr['bottom']);
							$fianlArr['botSlick']  =  $fianlArr['bottom'] - $finalSlickBot;
							//======================================================================
							$fianlArr['close']     = $row['close'];
							$fianlArr['open']      = $row['open'];
							$fianlArr['high']      = $row['high'];
							$fianlArr['low']       = $row['low'];
							$fianlArr['openTime_human_readible']     = $row['openTime_human_readible'];
							$fianlArr['global_swing_status']         = $row['global_swing_status'];
							//======================================================================
							$myArray[]             = $fianlArr;
							$initailVal            = $initailVal + $boxWidthPerc;
							$initailValForBott     = $initailVal;
							//======================================================================
							// Defined The Top and Bottom Value 
							//======================================================================
							$perceantage             = num(($percent / 100) *  num($row['close']));
							$newPriceToMakeBoxTop    = num($row['close']) + $perceantage;
							$newPriceToMakeBoxDown   = num($row['close']) - $perceantage;
							$runingPosition          = $color;
							//======================================================================
							// Defined The Top and Bottom Value Operational change
							//======================================================================
							$perceantage_op            =  num(($op_percent / 100) *  num($row['close']));
				            $newPriceToMakeBoxTop_op   =  num($row['close']) + $perceantage_op;
				            $newPriceToMakeBoxDown_op  =  num($row['close']) - $perceantage_op;
							
							//======================================================================
							// Defined The Top and Bottom Value Directional Change
							//======================================================================
							$perceantage_direct            =  num(($direct_percent / 100) *  num($row['close']));
				            $newPriceToMakeBoxTop_direct   =  num($row['close']) + $perceantage_direct;
				            $newPriceToMakeBoxDown_direct  =  num($row['close']) - $perceantage_direct;
				}
			}
			if($color == 'red'  && $row['color']=='red'){
	            if  ($compareValBack < $newPriceToMakeBoxDown_op) {
							// Second step code here
							$color                 = $row['color'];
							$fianlArr['top']       = $initailVal - $boxWidthPerc; /// For general use 
							$fianlArr['bottom']    = $initailVal - ($boxWidthPerc + $boxWidthPerc) ;
							$fianlArr['color']     = $color;
							$fianlArr['key']       = $key;
							//===================== Calcalte the slick ===============================
							$slickTop  =  $newPriceToMakeBoxDown_op - $compareVal;
							$calcultePerceOfGivenT = ($slickTop / $row['close']) * 100;  
							$finalSlickTop         = num(($calcultePerceOfGivenT / 100) *  $fianlArr['top']);
							$fianlArr['topSlick']  = $finalSlickTop + $fianlArr['top'];
							
							$slickBot  = $newPriceToMakeBoxDown_op - $compareVal ;
							$calcultePerceOfGivenB = ($slickBot / $row['open']) * 100;  
							$finalSlickBot         = num(($calcultePerceOfGivenB / 100) *  $fianlArr['bottom']);
							$fianlArr['botSlick']  = $fianlArr['bottom'] -  $finalSlickBot;
							//======================================================================
							$fianlArr['close']     = $row['close'];
							$fianlArr['open']      = $row['open'];
							$fianlArr['high']      = $row['high'];
							$fianlArr['low']       = $row['low'];
							$fianlArr['openTime_human_readible']     = $row['openTime_human_readible'];
							$fianlArr['global_swing_status']         = $row['global_swing_status'];
							//======================================================================
							$myArray[]             = $fianlArr;
							$initailVal            = $initailVal - $boxWidthPerc;
							//======================================================================
							// Defined The Top and Bottom Value
							//======================================================================
							$newPriceToMakeBoxDown  = num($row['close']);
							$perceantage            = num(($percent / 100) *  num($row['close']));
							$newPriceToMakeBoxTop   = num($row['close']) + $perceantage;
							$newPriceToMakeBoxDown  = num($row['close']) - $perceantage;
							$runingPosition         = $color;
							//======================================================================
							// Defined The Top and Bottom Value Operational change
							//======================================================================
							$perceantage_op            =  num(($op_percent / 100) *  num($row['close']));
				            $newPriceToMakeBoxTop_op   =  num($row['close']) + $perceantage_op;
				            $newPriceToMakeBoxDown_op  =  num($row['close']) - $perceantage_op;
							
							//======================================================================
							// Defined The Top and Bottom Value Directional Change
							//======================================================================
							$perceantage_direct            =  num(($direct_percent / 100) *  num($row['close']));
				            $newPriceToMakeBoxTop_direct   =  num($row['close']) + $perceantage_direct;
				            $newPriceToMakeBoxDown_direct  =  num($row['close']) - $perceantage_direct;
					  }
			}
		}
		return $myArray;
    } //end get_candle_box_type()
	
	//======================================================================
    // Get Coin Data
    //======================================================================
    public function get_coin_data()
    {
        $where_arr = array(
            'user_id' => 'global',
            'exchange_type' => 'binance',
        );

        $this->mongo_db->sort(array('_id' => 1));
		// $this->mongo_db->where(array('user_id' => 'global'));
		$this->mongo_db->where($where_arr);
		$get_coins = $this->mongo_db->get('coins');
		$coins_arr = iterator_to_array($get_coins);
		return $coins_arr;
    } //end get_coin_data()
	
	//======================================================================
    // Get Coin Lists
    //======================================================================
    public function get_coin_lists()
    {
        $where_arr = array(
            'user_id' => 'global',
            'exchange_type' => 'binance',
        );

        $this->mongo_db->sort(array('_id' => 1));
		// $this->mongo_db->where(array('user_id' => 'global'));
		$this->mongo_db->where($where_arr);
		$get_coins = $this->mongo_db->get('coins');
		$coins_arr = iterator_to_array($get_coins);
		return $coins_arr;
    } //end get_coin_offset_value()
	
	 //======================================================================
     // Update Settings in Coin table 
     //======================================================================
	 public function updateCoinSettings($data){
		 
		extract($data);
		$upd_data = array(
			'percent' => $this->db->escape_str(trim($percent)),
			'op_percent' => $this->db->escape_str(trim($op_percent)),
			'direct_percent' => $this->db->escape_str(trim($direct_percent)),
			'look_back' => $this->db->escape_str(trim($look_back)),
		);
		//Update data in mongoTable
		$this->mongo_db->set($upd_data);
		$this->mongo_db->sort(array('_id' => 1));
		$this->mongo_db->where(array('user_id' => 'global'));
		$this->mongo_db->where(array('exchange_type' => 'binance'));
		$this->mongo_db->where(array('symbol' => $coin));
		$result_object = $this->mongo_db->update('coins');
        return true;

    } //End of save_task_manager_setting
	
    //======================================================================
    // Get Candle stick Data from database 
    //======================================================================
    public function get_candelstick_data_from_database($global_symbol, $periods, $from_date_object,$record,$previous_date, $forward_date)
    {	
		$record = ($record=='') ? 5000 : $record;	
        $this->mongo_db->where(array(
            'coin' => $global_symbol,
            'periods' => $periods
        ));
        if ($from_date_object && $to_date_object) {
            $this->mongo_db->where_gte('timestampDate', $from_date_object);
            $this->mongo_db->where_lte('timestampDate', $to_date_object);
        }
        if ($forward_date != '') {
            $previous_date_date_mongo = $this->mongo_db->converToMongodttime($forward_date);
            $this->mongo_db->where_lte('timestampDate', $previous_date_date_mongo);
        }
        if ($previous_date != '') {
            $forward_date_date_mongo = $this->mongo_db->converToMongodttime($previous_date);
            $this->mongo_db->where_gt('timestampDate', $forward_date_date_mongo);
            $this->mongo_db->sort(array(
                'timestampDate' => 'ASC'
            )); //ASC/DESC
        } else {
            $this->mongo_db->sort(array(
                'timestampDate' => 'DESC'
            )); //ASC/DESC
        }
        $this->mongo_db->limit($record);
        $responseArr          = $this->mongo_db->get('market_chart');
        $final_arr            = array();
        $total_volume_arr     = array();
        $total_volume_arr_bvs = array();
        foreach ($responseArr as $val_arr) {
            array_push($total_volume_arr, $val_arr['total_volume']);
            array_push($total_volume_arr_bvs, $val_arr['total_volume_bvs']);
            $final_arr[] = array(
                '_id' => $myText = (string) $val_arr['_id'],
                'timestampDate' => $val_arr['timestampDate'],
                'close' => num($val_arr['close']),
                'open' => num($val_arr['open']),
                'high' => num($val_arr['high']),
                'low' => num($val_arr['low']),
                'volume' => $val_arr['volume'],
                'openTime' => $val_arr['openTime'],
                'closeTime' => $val_arr['closeTime'],
                'coin' => $val_arr['coin'],
                'candel_status' => $val_arr['candel_status'],
                'candle_type' => $val_arr['candle_type'],
                'openTime_human_readible' => $val_arr['openTime_human_readible'],
                'closeTime_human_readible' => $val_arr['closeTime_human_readible'],
                'demand_base_candel' => $val_arr['demand_base_candel'],
                'supply_base_candel' => $val_arr['supply_base_candel'],
                'global_swing_status' => $val_arr['global_swing_status'],
                'global_swing_parent_status' => $val_arr['global_swing_parent_status'],
                'rejected_candle' => $val_arr['rejected_candle'],
                'ask_volume' => $val_arr['ask_volume'],
                'bid_volume' => $val_arr['bid_volume'],
                'total_volume' => $val_arr['total_volume'],
                'buy_volume' => $val_arr['buy_volume'],
                'sell_volume' => $val_arr['sell_volume'],
                'move' => $val_arr['move'],
                'black_ask_diff' => $val_arr['black_ask_diff'],
                'black_bid_diff' => $val_arr['black_bid_diff'],
                'yellow_ask_diff' => $val_arr['yellow_ask_diff'],
                'yellow_bid_diff' => $val_arr['yellow_bid_diff']
            );
        }
        if ($forward_date == '') {
            $final_arr = array_reverse($final_arr);
        }
        $data['candle_arr']     = $final_arr;
        $max_volume             = max($total_volume_arr);
        $data['max_volume']     = $max_volume;
        $max_volume_bvs         = max($total_volume_arr_bvs);
        $data['max_volume_bvs'] = $max_volume_bvs;
        return $data;
    }
	
	
	
    //======================================================================
    // Get Candle stick Data from database 
    //======================================================================
    public function get_candelstick_data_from_database_cron($global_symbol, $periods, $from_date_object,$record,$previous_date, $forward_date)
    {
		
		$record = ($record=='') ? 500 : $record;
		$record = 5000;
        $this->mongo_db->where(array(
            'coin' => $global_symbol,
            'periods' => $periods
        ));
        if ($from_date_object && $to_date_object) {
            $this->mongo_db->where_gte('timestampDate', $from_date_object);
            $this->mongo_db->where_lte('timestampDate', $to_date_object);
        }
        if ($previous_date != '') {
            $previous_date_date_mongo = $this->mongo_db->converToMongodttime($previous_date);
            $this->mongo_db->where_lte('timestampDate', $previous_date_date_mongo);
        }
        if ($forward_date != '') {
            $forward_date_date_mongo = $this->mongo_db->converToMongodttime($forward_date);
            $this->mongo_db->where_gt('timestampDate', $forward_date_date_mongo);
            $this->mongo_db->sort(array(
                'timestampDate' => 'ASC'
            )); //ASC/DESC
        } else {
            $this->mongo_db->sort(array(
                'timestampDate' => 'DESC'
            )); //ASC/DESC
        }
        $this->mongo_db->limit($record);
        $responseArr          = $this->mongo_db->get('market_chart');
	
        $final_arr            = array();
        $total_volume_arr     = array();
        $total_volume_arr_bvs = array();
        foreach ($responseArr as $val_arr) {
            array_push($total_volume_arr, $val_arr['total_volume']);
            array_push($total_volume_arr_bvs, $val_arr['total_volume_bvs']);
            $final_arr[] = array(
                '_id' => $myText = (string) $val_arr['_id'],
                'timestampDate' => $val_arr['timestampDate'],
                'close' => num($val_arr['close']),
                'open' => num($val_arr['open']),
                'high' => num($val_arr['high']),
                'low' => num($val_arr['low']),
                'volume' => $val_arr['volume'],
                'openTime' => $val_arr['openTime'],
                'closeTime' => $val_arr['closeTime'],
                'coin' => $val_arr['coin'],
                'candel_status' => $val_arr['candel_status'],
                'candle_type' => $val_arr['candle_type'],
                'openTime_human_readible' => $val_arr['openTime_human_readible'],
                'closeTime_human_readible' => $val_arr['closeTime_human_readible'],
                'demand_base_candel' => $val_arr['demand_base_candel'],
                'supply_base_candel' => $val_arr['supply_base_candel'],
                'global_swing_status' => $val_arr['global_swing_status'],
                'global_swing_parent_status' => $val_arr['global_swing_parent_status'],
                'rejected_candle' => $val_arr['rejected_candle'],
                'ask_volume' => $val_arr['ask_volume'],
                'bid_volume' => $val_arr['bid_volume'],
                'total_volume' => $val_arr['total_volume'],
                'buy_volume' => $val_arr['buy_volume'],
                'sell_volume' => $val_arr['sell_volume'],
                'move' => $val_arr['move'],
                'black_ask_diff' => $val_arr['black_ask_diff'],
                'black_bid_diff' => $val_arr['black_bid_diff'],
                'yellow_ask_diff' => $val_arr['yellow_ask_diff'],
                'yellow_bid_diff' => $val_arr['yellow_bid_diff']
            );
        }
        if ($forward_date == '') {
            $final_arr = array_reverse($final_arr);
        }
        $data['candle_arr']     = $final_arr;
        $max_volume             = max($total_volume_arr);
        $data['max_volume']     = $max_volume;
        $max_volume_bvs         = max($total_volume_arr_bvs);
        $data['max_volume_bvs'] = $max_volume_bvs;
        return $data;
    }
	
	
	  public function get_candelstick_data_from_database_test($global_symbol, $periods, $from_date_object,$record,$previous_date,$forward_date)
    {
		
		$record = ($record=='') ? 500 : $record;
        $this->mongo_db->where(array(
            'coin' => $global_symbol,
            'periods' => $periods
        ));
        if ($previous_date && $forward_date) {
            $this->mongo_db->where_gte('timestampDate', $previous_date);
            $this->mongo_db->where_lte('timestampDate', $forward_date);
        }
        if ($previous_date != '') {
            $previous_date            = $previous_date / 1000;
            $previous_date            = date("Y-m-d H:i:s", $previous_date);
            $previous_date_date_mongo = $this->mongo_db->converToMongodttime($previous_date);
            $this->mongo_db->where_lte('timestampDate', $previous_date_date_mongo);
        }
     
        $this->mongo_db->limit($record);
        $responseArr          = $this->mongo_db->get('market_chart');
        $final_arr            = array();
        $total_volume_arr     = array();
        $total_volume_arr_bvs = array();
        foreach ($responseArr as $val_arr) {
            array_push($total_volume_arr, $val_arr['total_volume']);
            array_push($total_volume_arr_bvs, $val_arr['total_volume_bvs']);
            $final_arr[] = array(
                '_id' => $myText = (string) $val_arr['_id'],
                'timestampDate' => $val_arr['timestampDate'],
                'close' => num($val_arr['close']),
                'open' => num($val_arr['open']),
                'high' => num($val_arr['high']),
                'low' => num($val_arr['low']),
                'volume' => $val_arr['volume'],
                'openTime' => $val_arr['openTime'],
                'closeTime' => $val_arr['closeTime'],
                'coin' => $val_arr['coin'],
                'candel_status' => $val_arr['candel_status'],
                'candle_type' => $val_arr['candle_type'],
                'openTime_human_readible' => $val_arr['openTime_human_readible'],
                'closeTime_human_readible' => $val_arr['closeTime_human_readible'],
                'demand_base_candel' => $val_arr['demand_base_candel'],
                'supply_base_candel' => $val_arr['supply_base_candel'],
                'global_swing_status' => $val_arr['global_swing_status'],
                'global_swing_parent_status' => $val_arr['global_swing_parent_status'],
                'rejected_candle' => $val_arr['rejected_candle'],
                'ask_volume' => $val_arr['ask_volume'],
                'bid_volume' => $val_arr['bid_volume'],
                'total_volume' => $val_arr['total_volume'],
                'buy_volume' => $val_arr['buy_volume'],
                'sell_volume' => $val_arr['sell_volume'],
                'move' => $val_arr['move'],
                'black_ask_diff' => $val_arr['black_ask_diff'],
                'black_bid_diff' => $val_arr['black_bid_diff'],
                'yellow_ask_diff' => $val_arr['yellow_ask_diff'],
                'yellow_bid_diff' => $val_arr['yellow_bid_diff']
            );
        }
        if ($forward_date == '') {
            $final_arr = array_reverse($final_arr);
        }
        $data['candle_arr']     = $final_arr;
        $max_volume             = max($total_volume_arr);
        $data['max_volume']     = $max_volume;
        $max_volume_bvs         = max($total_volume_arr_bvs);
        $data['max_volume_bvs'] = $max_volume_bvs;
        return $data;
    }//get_candelstick_data_from_database_test
	
    /** End of get_candelstick_data_from_database **/
    //======================================================================
    // Get Orderss Array
    //======================================================================
    public function get_order_array($symbol, $admin_id, $global_mode, $start_date_for_time_zone_time, $end_date_for_time_zone_time)
    {
        $search_Array = array(
            'symbol' => $symbol,
            'is_sell_order' => 'sold',
            'admin_id' => $admin_id,
            'application_mode' => $global_mode,
            'created_date' => array(
                '$gte' => $start_date_for_time_zone_time,
                '$lte' => $end_date_for_time_zone_time
            )
        );
        $this->mongo_db->where($search_Array);
        $res            = $this->mongo_db->get('buy_orders');
        $final_arr      = array();
        $buy_orders_arr = iterator_to_array($res);
        foreach ($buy_orders_arr as $key => $arr) {
            $buy_order_id = $arr['_id'];
            $buy_date     = $arr['created_date'];
            if ($arr['buy_date']) {
                $buy_date = $arr['buy_date'];
            }
            $datetime          = $buy_date->toDateTime();
            $created_date      = $datetime->format(DATE_RSS);
            $market_sold_price = $arr['market_sold_price'];
            $datetime          = new DateTime($created_date);
            $datetime->format('Y-m-d H:00:00');
            $formated_date_time     = $datetime->format('Y-m-d H:00:00');
            $buy_order_date         = $formated_date_time;
            $search['buy_order_id'] = $buy_order_id;
            $this->mongo_db->where($search);
            $res_sold       = $this->mongo_db->get('orders');
            $sold_order_arr = iterator_to_array($res_sold);
            foreach ($sold_order_arr as $key => $value) {
                $buy_order_price  = $value['purchased_price'];
                $sell_order_price = $value['sell_price'];
                $sell_date        = $value['created_date'];
                if ($value['sell_date']) {
                    $sell_date = $value['sell_date'];
                }
                $datetime     = $sell_date->toDateTime();
                $created_date = $datetime->format(DATE_RSS);
                $datetime     = new DateTime($created_date);
                $datetime->format('Y-m-d H:00:00');
                $formated_date_time = $datetime->format('Y-m-d H:00:00');
                $sell_date          = $formated_date_time;
                $final_arr[]        = array(
                    'buy_date' => $buy_order_date,
                    'sell_date' => $sell_date,
                    'buy_price' => $buy_order_price,
                    'sell_price' => $market_sold_price
                );
            }
        }
        return $final_arr;
    } //end get_order_array
    //======================================================================
    // Get Task Manger Settings
    //======================================================================
    public function get_task_manager_setting($global_symbol)
    {
        $this->mongo_db->where(array(
            'coin' => $global_symbol
        ));
        $res = $this->mongo_db->get('task_manager_setting');
        return iterator_to_array($res);
    } //End of get_task_manager_setting
    //======================================================================
    // Get Coin unit Value
    //======================================================================
    public function get_coin_unit_value($symbol)
    {
        $this->mongo_db->where(array(
            'symbol' => $symbol
        ));
        $get_coin = $this->mongo_db->get('coins');
        $coin_arr = iterator_to_array($get_coin);
        $coin_arr = $coin_arr[0];
        return $coin_arr['unit_value'];
    } //end get_coin_unit_value()
    //======================================================================
    // Get Coin unit Value
    //======================================================================
    public function get_chart_target_zones($global_symbol)
    {
        $admin_id = $this->session->userdata('admin_id');
        $this->mongo_db->where(array(
            'coin' => $global_symbol
        ));
        $this->mongo_db->limit(5);
        $this->mongo_db->sort(array(
            '_id' => 'desc'
        ));
        $responseArr = $this->mongo_db->get('chart_target_zones');
        $fullarray   = array();
        foreach ($responseArr as $valueArr) {
            $start_date = $valueArr['start_date'];
            $end_date   = $valueArr['end_date'];
            if (!empty($valueArr)) {
                $returArr['start_value'] = (string) ($valueArr['start_value']);
                $returArr['end_value']   = (string) ($valueArr['end_value']);
                $returArr['unit_value']  = (string) ($valueArr['unit_value']);
                $returArr['start_date']  = (string) json_decode($start_date);
                $returArr['end_date']    = (string) json_decode($end_date);
                $returArr['type']        = $valueArr['type'];
            }
            $fullarray[] = $returArr;
        }
        return $fullarray;
    } //end get_chart_target_zones
    //======================================================================
    // Get  get_candle_price_volume_detail
    //======================================================================
    public function get_candle_price_volume_detail($symbol, $start_date, $end_date, $unit_value)
    {
        $bid_arr_volume   = $this->get_bid_price_volume($symbol, $start_date, $end_date);
        $ask_arr_volume   = $this->get_ask_price_volume($symbol, $start_date, $end_date);
        $total_volume_arr = array();
        $total_volume     = 0;
        if (count($ask_arr_volume) > 0) {
            foreach ($ask_arr_volume as $price => $valume) {
                $total_volume_arr[$price] = $bid_arr_volume[$price] + $valume;
            }
        }
        foreach ($bid_arr_volume as $bid_price => $bid_volume) {
            if (!array_key_exists($bid_price, $total_volume_arr)) {
                $total_volume_arr[$bid_price] = $bid_volume;
            }
        }
        $max_volume                     = max($total_volume_arr);
        $retur_data['bid_arr_volume']   = $bid_arr_volume;
        $retur_data['ask_arr_volume']   = $ask_arr_volume;
        $retur_data['total_volume_arr'] = $total_volume_arr;
        $retur_data['max_volume']       = $max_volume;
        $retur_data['unit_value']       = $unit_value;
        return $retur_data;
    }
    /** End of get_candle_price_volume_detail **/
    //======================================================================
    // Get  get_candle_price_volume_detail
    //======================================================================
    public function get_ask_price_volume($symbol, $start_date, $end_date)
    {
        $connect = $this->mongo_db->customQuery();
        $this->mongo_db->where('type', 'ask');
        $this->mongo_db->where('coin', $symbol);
        $this->mongo_db->where_gte('hour', $start_date);
        $this->mongo_db->where_lte('hour', $end_date);
        $this->mongo_db->sort(array(
            'hour' => 'desc'
        ));
        $responseArr          = $this->mongo_db->get('market_trade_hourly_history');
        $responseArr          = iterator_to_array($responseArr);
        $ask_price_volume_arr = array();
        $full_arr             = array();
        if (count($responseArr) > 0) {
            foreach ($responseArr as $value) {
                $ask_price_volume_arr[number_format($value['price'], 8)] = $value['volume'];
            }
        }
        ksort($ask_price_volume_arr);
        return $ask_price_volume_arr;
    }
    /** End of get_bid_price_volume**/
    //======================================================================
    // Get  get_candle_price_volume_detail
    //======================================================================
    public function get_bid_price_volume($symbol, $start_date, $end_date)
    {
        $connect = $this->mongo_db->customQuery();
        $this->mongo_db->where_gte('hour', $start_date);
        $this->mongo_db->where_lte('hour', $end_date);
        $this->mongo_db->where('type', 'bid');
        $this->mongo_db->where('coin', $symbol);
        $this->mongo_db->sort(array(
            'hour' => 'desc'
        ));
        $responseArr          = $this->mongo_db->get('market_trade_hourly_history');
        $responseArr          = iterator_to_array($responseArr);
        $bid_price_volume_arr = array();
        if (count($responseArr) > 0) {
            foreach ($responseArr as $value) {
                $bid_price_volume_arr[number_format($value['price'], 8)] = $value['volume'];
            }
        }
        ksort($bid_price_volume_arr);
        return $bid_price_volume_arr;
    }
    /** End of get_bid_price_volume**/
    //======================================================================
    // Get  get_candle_price_volume_detail
    //======================================================================
    public function is_box_trigger_trade_buyed($start_date, $end_date, $symbol)
    {
        $start_date            = $this->mongo_db->converToMongodttime($start_date);
        $end_date              = $this->mongo_db->converToMongodttime($end_date);
        $where['trigger_type'] = 'box_trigger_3';
        $where['buy_date']     = array(
            '$gte' => $start_date,
            '$lte' => $end_date
        );
        $where['symbol']       = $symbol;
        $this->mongo_db->where($where);
        $this->mongo_db->limit(1);
        $data             = $this->mongo_db->get('buy_orders');
        $data             = iterator_to_array($data);
        $resp['is_exist'] = 'no';
        $resp['price']    = '';
        if (!empty($data)) {
            $resp['is_exist'] = 'yes';
            $resp['price']    = num($data[0]['price']);
        } else {
            $this->mongo_db->where($where);
            $this->mongo_db->limit(1);
            $data = $this->mongo_db->get('sold_buy_orders');
            $data = iterator_to_array($data);
            if (!empty($data)) {
                $resp['is_exist'] = 'yes';
                $resp['price']    = num($data[0]['price']);
            }
        }
        return $resp;
    } //End of is_box_trigger_trade_buyed
    //======================================================================
    // Get  get_candle_price_volume_detail
    //======================================================================
    public function convert_time_zone($mil)
    {
        $seconds  = $mil / 1000;
        $datetime = date("Y-m-d g:i:s A", $seconds);
        $timezone = $this->session->userdata('timezone');
        $date     = date_create($datetime);
        date_timezone_set($date, timezone_open($timezone));
        return date_format($date, 'Y-m-d g:i:s A');
    } //End of convert_time_zone
    //======================================================================
    // Get  get_candle_price_volume_detail
    //======================================================================
    public function calculate_pressure_up_and_down($start_date, $end_date, $coin, $pressure_type)
    {
        $this->mongo_db->where_gte('created_date', $this->mongo_db->converToMongodttime($start_date));
        $this->mongo_db->where_lt('created_date', $this->mongo_db->converToMongodttime($end_date));
        $this->mongo_db->where(array(
            'coin' => $coin,
            'pressure' => $pressure_type
        ));
        $res     = $this->mongo_db->get('order_book_pressure');
        $res_arr = iterator_to_array($res);
        return $total_pressure = count($res_arr);
    } //End of calculate_pressure_up_and_down
    //======================================================================
    // Get  get_candle_price_volume_detail
    //======================================================================
    public function calculate_average_score($symbol, $start_d, $end_d)
    {
        $start_date = $this->mongo_db->converToMongodttime($start_d);
        $end_date   = $this->mongo_db->converToMongodttime($end_d);
        $this->mongo_db->where(array(
            'coin' => $symbol,
            'modified_date' => array(
                '$lte' => $end_date,
                '$gte' => $start_date
            )
        ));
        $this->mongo_db->order_by(array(
            'modified_date' => -1
        ));
        $get_arr222 = $this->mongo_db->get('coin_meta_history');
        $score_Arr  = iterator_to_array($get_arr222);
        foreach ($score_Arr as $key => $value) {
            $score[] = $value['score'];
        }
        if (count($score) > 0) {
            $avg_score = array_sum($score) / count($score);
        } else {
            $avg_score = 0;
        }
        return $avg_score;
    }//calculate_average_score
}
?>
