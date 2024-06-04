<?php
class mod_candlereport extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
	//======================================================================
    // Get Candle stick Chart Report
    //======================================================================
	 public function get_candelstick_chart_report($data)
    {
	    extract($data);
		
		
        $previous_date = date("Y-m-d H:00:00", strtotime($previous_date));
        $start_date_mongo = $this->mongo_db->converToMongodttime($previous_date);
		
		$forward_date = date("Y-m-d H:00:00", strtotime($forward_date));
        $end_date_mongo = $this->mongo_db->converToMongodttime($forward_date);
		
		$record = ($record=='') ? 5000 : $record;
        $this->mongo_db->where(array(
            'coin'    => $coin,
        ));
        if ($start_date_mongo && $end_date_mongo) {
            $this->mongo_db->where_gte('timestampDate', $start_date_mongo);
            $this->mongo_db->where_lte('timestampDate', $end_date_mongo);
        }
       
        $this->mongo_db->sort(array(
                'timestampDate' => 'DESC'
        )); //ASC/DESC*/
        
        $this->mongo_db->limit($record);
        $responseArr          = $this->mongo_db->get('market_chart');
		$obj_arr              = iterator_to_array($responseArr);
		
		return $obj_arr; 
	}//get_candelstick_chart_report
	
	
    //======================================================================
    // Get Coin Offest Value
    //======================================================================
    public function get_coin_offset_value($symbol)
    {
        $this->mongo_db->where(array(
            'symbol' => $symbol,
            'user_id' => 'global',
            'exchange_type' => 'binance'
        ));
        $get_coin = $this->mongo_db->get('coins');
        $coin_arr = iterator_to_array($get_coin);
        $data     = $coin_arr[0];
        return $data;
    } //end get_coin_offset_value()
	
	
	//======================================================================
    // Get get_candle_box_data
    //======================================================================
    public function get_candle_box_data($candel_stick_arr,$boxWidthPerc,$valueToMul,$initailVal)
    {
       foreach ($candel_stick_arr as $key => $row) {
		    
			$boxWidthPerc     = $boxWidthPerc;
			$valueToMul       = $valueToMul;
			$compareVal       = num($row['close']) ;
			$compareValMult   = num($row['close']) * $valueToMul;
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
            if ($compareVal > $newPriceToMakeBoxTop) {
		     
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
				$perceantage             = num(($percent / 100) *  num($row['close']));
				$newPriceToMakeBoxTop    = num($row['close']) + $perceantage;
				$newPriceToMakeBoxDown   = num($row['close']) - $perceantage;
				$runingPosition          = $color;
				
            } else  if ($compareVal < $newPriceToMakeBoxDown) {
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
				$newPriceToMakeBoxDown  = num($row['close']);
				$perceantage            = num(($percent / 100) *  num($row['close']));
				$newPriceToMakeBoxTop   = num($row['close']) + $perceantage;
				$newPriceToMakeBoxDown  = num($row['close']) - $perceantage;
				$runingPosition         = $color;
            } 
		}
		return $myArray;
    } //end get_candle_box_data()
	
	
	//======================================================================
    // Get get_candle_box_type
    //======================================================================
    public function get_candle_box_type($candleBoxArray,$percent,$op_percent,$direct_percent, $boxWidthPerc,$valueToMul,$initailVal,$look_back)
    {
        foreach($candleBoxArray  as $key => $row){
		
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
					 if ($compareVal > $newPriceToMakeBoxTop_op) {
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
			          if  ($compareVal < $newPriceToMakeBoxDown_direct) {
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
				if ($compareVal > $newPriceToMakeBoxTop_direct) {
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
	            if  ($compareVal < $newPriceToMakeBoxDown_op) {
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
    } //end get_coin_lists()
	
    //======================================================================
    // Get Candle stick Data from database 
    //======================================================================
	
    public function get_candelstick_data_from_database($global_symbol, $periods, $from_date_object,$record,$previous_date, $forward_date)
    {
	
		$record = ($record=='') ? 5000 : $record;
        $this->mongo_db->where(array(
            'coin'    => $global_symbol,
            'periods' => $periods
        ));
        if ($from_date_object && $to_date_object) {
            $this->mongo_db->where_gte('timestampDate', $from_date_object);
            $this->mongo_db->where_lte('timestampDate', $to_date_object);
        }
        if ($previous_date != '') {
            $previous_date_date_mongo = $this->mongo_db->converToMongodttime($forward_date);
            $this->mongo_db->where_lte('timestampDate', $previous_date_date_mongo);
        }
        if ($forward_date != '') {
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
                'black_ask_diff'  => $val_arr['black_ask_diff'],
                'black_bid_diff'  => $val_arr['black_bid_diff'],
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
		
		$record = ($record=='') ? 5000 : $record;
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
    }
	
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
	
	
	 public function array2csvR($array){	
	    
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys((array) reset($array)));
        foreach ($array as $key => $row) {
            //$rowNew  =  htmlspecialchars(trim(strip_tags($row))); 
            fputcsv($df, (array) $row);
        }
        fclose($df);
        return ob_get_clean();
    } //array2csv
	
	
	 public function create_candle_csv($array){	
	    
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys((array) reset($array)));
        foreach ($array as $key => $row) {
            //$rowNew  =  htmlspecialchars(trim(strip_tags($row))); 
            fputcsv($df, (array) $row);
        }
        fclose($df);
        return ob_get_clean();
    } //create_candle_csv
    
	
	public function getCoinSettingsCsv($posted_data){
		
		
		    //$this->mongo_db->where(array('coin' => $global_symbol, 'openTime_human_readible' => $openTime_human_readible));
			//$get_candlereport_settings = $this->mongo_db->get('candlereport_settings');
			//$candlereport_settings_arr = iterator_to_array($get_candlereport_settings);	
		
			//echo "<pre>";  print_r($candlereport_settings_arr); exit; 
			
			
				$previous_date  = $this->input->post('previous_date'); 
				$forward_date   = $this->input->post('forward_date'); 
				$coin           = $this->input->post('coin'); 
				$previous_date  = date('Y-m-d H:00:00', strtotime($previous_date));
				$forward_date   = date('Y-m-d H:00:00', strtotime($forward_date));
			
			
			    $finalArr = array();
				$symbol   = $coin;
				$this->mongo_db->where(array(
					'coin' => $symbol ,
				));
				if ($forward_date != '') {
					//$this->mongo_db->where_lte('openTime_human_readible', $forward_date);
				}
				if ($previous_date != '') {
					//$this->mongo_db->where_gt('openTime_human_readible', $previous_date);
					$this->mongo_db->sort(array(
						'openTime_human_readible' => 'ASC'
					)); //ASC/DESC
				}
				$this->mongo_db->limit(5000);
				$responseArr          = $this->mongo_db->get('candlereport_settings');
				$resArray             = iterator_to_array($responseArr);	
				
				$finalArr = array();
				$i=1;
				foreach($resArray  as $row){
				
		             	$final['S.No']  = $i;
						$final['Coin']  = $row['coin'];
						$final['Color'] = $row['color'];
						$final['Close'] = $row['close'];
						$final['Open']  = $row['open'];
						$final['High']  = $row['high'];
						$final['Low']   = $row['low'];
						$final['Time Stay Coin'] = $row['timeDifference'];
						$final['Date Time']      = $row['openTime_human_readible'];
				        $finalArr[] = $final;
						$i++;
				}
			return $finalArr;
		  
	}//getCoinSettingsCsv
	
	
	public function insertSettings($recordArray,$previous_date,$global_symbol){
		
		
		
		
		foreach($recordArray as $key => $row){
			
			
			$openTime_human_readible   = date('Y-m-d H:00:00', strtotime($row['openTime_human_readible']));
			$this->mongo_db->where(array('coin' => $global_symbol, 'openTime_human_readible' => $openTime_human_readible));
			$get_candlereport_settings = $this->mongo_db->get('candlereport_settings');
			$candlereport_settings_arr = iterator_to_array($get_candlereport_settings);		 
			
			if($candlereport_settings_arr!=''){
				// First delete the record 
				$id  = $this->mongo_db->mongoId($candlereport_settings_arr[0]['_id']);
				$this->mongo_db->where(array('_id' =>  $id));
				$this->mongo_db->delete('candlereport_settings');
			}
				
			if($key==0){
			   $previous_dateFor  = date('Y-m-d H:00:00', strtotime($previous_date));
			}else{
			   $previous_dateFor  = date('Y-m-d H:00:00', strtotime($recordArray[$key-1]['openTime_human_readible']));
			}
			$date1 = strtotime($previous_dateFor);  
			$date2 = strtotime($row['openTime_human_readible']);  
			$diff  = $date2 - $date1;
			$hours = $diff / ( 60 * 60 );
			
			$created_date = date('Y-m-d G:i:s');
			
			$orig_date = new DateTime($row['openTime_human_readible']);
			$orig_date = $orig_date->getTimestamp();
			$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
	
			$ins_data = array(
				'coin'           => ($global_symbol),
				'color'          => (($row['color'])),
				'top'            => (($row['top'])),
				'bottom'         => (($row['bottom'])),
				'close'          => (($row['close'])),
				'open'           => (($row['open'])),
				'high'           => (($row['high'])),
				'low'            => (($row['low'])),
				'timeDifference' => (($hours.' Hrs')),
				'openTime_human_readible'   => ($row['openTime_human_readible']),
				'mongo_time'     => ($start_date),
			);
			$previous_datenew = date('Y-m-d H:00:00', strtotime($row['openTime_human_readible']));
			//echo "<pre>";  print_r($ins_data); 
			//Insert the record into the database.
			$this->mongo_db->insert('candlereport_settings', $ins_data);
			
		}
	} //end add_user()
	
}
?>
