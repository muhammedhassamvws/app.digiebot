<?php
class mod_highchart extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}  

	public function getCoinaverage($global_symbol, $time, $start_date, $end_date, $fianalArrReturn, $x)
	{
		if ($time == 'minut') {
			$created_datetime = date('Y-m-d G:i:s', strtotime($start_date));
			$orig_date = new DateTime($created_datetime);
			$orig_date = $orig_date->getTimestamp();
			$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
			$created_datetime22 = date('Y-m-d G:i:s', strtotime($end_date));
			$orig_date22 = new DateTime($created_datetime22);
			$orig_date22 = $orig_date22->getTimestamp();
			$orig_date22 = $orig_date22 + 60;
			$end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
		}
		else
		if ($time == 'hour') {
			$created_datetime = date('Y-m-d G:i:s', strtotime($start_date));
			$orig_date = new DateTime($created_datetime);
			$orig_date = $orig_date->getTimestamp();
			$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
			$created_datetime22 = date('Y-m-d G:i:s', strtotime($end_date));
			$orig_date22 = new DateTime($created_datetime22);
			$orig_date22 = $orig_date22->getTimestamp();
			$orig_date22 = $orig_date22 + 3600;
			$end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
		}

		$all_coins_arr = $this->mod_sockets->get_all_coins();
		$finalArr[] = array(
			'coin_name' => 'Bitcoin',
			'symbol' => 'BTC'
		);
		$all_coins_arrList = array_merge($all_coins_arr, $finalArr);
		$finalArr = array();
		foreach($all_coins_arrList as $coin) {
			$symbol = $coin['symbol'];
			$search_array = array();
			$i = '';
			$condVar = '';
			$search_array['coin'] = $symbol;
			$search_array['time'] = array(
				'$gte' => $start_date,
				'$lte' => $end_date
			);
			$connetct = $this->mongo_db->customQuery();
			$limit = 1;
			$qr = array(
				'skip' => $skip,
				'sort' => array(
					'time' => - 1
				) ,
				'limit' => $limit
			);
			$cursor = $connetct->market_price_history->find($search_array, $qr);
			$resArray = iterator_to_array($cursor);
			
			$count = count($resArray);
			$i = 1;
			$condVar = $count;
			$oldVal['market_value'] = ($resArray[0]->market_value) ? $resArray[0]->market_value : 0;
			$market_value = ($resArray[0]->market_value != '') ? $resArray[0]->market_value : 0;
			if (array_key_exists($symbol, $fianalArrReturn)) {
				$lastValue = $fianalArrReturn[$symbol];
			}
			$finalArr[$coin['symbol']] = (num($market_value) == '') ? $lastValue : num($market_value);
		} // End of coin foreach
		return $finalArr;
	} //getCoinaverage
	
	
	
	public function saveOneMinutCurrentMarketValue($global_symbol, $time, $start_date, $end_date)
	{
			
			$created_datetime = date('Y-m-d G:i:s', strtotime($start_date));
			$orig_date = new DateTime($created_datetime);
			$orig_date = $orig_date->getTimestamp();
			$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
			$created_datetime22 = date('Y-m-d G:i:s', strtotime($end_date));
			$orig_date22 = new DateTime($created_datetime22);
			$orig_date22 = $orig_date22->getTimestamp();
			$end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
		
		$all_coins_arr = $this->mod_sockets->get_all_coins();
		//$finalArr[] = array(
			//'coin_name' => 'Bitcoin',
			//'symbol' => 'BTC'
		//);
		//$all_coins_arrList = array_merge($all_coins_arr, $finalArr);
		$finalArr = array();
		foreach($all_coins_arr as $coin) {
			$symbol = $coin['symbol'];
			if($_SERVER['REMOTE_ADDR'] == '182.191.92.16'){
			  //echo "<pre>";   print_r(); exit;
		      //$symbol = 'XRPBTC';	
			}
			$search_array = array();
			$i = '';
			$condVar = '';
			$search_array['coin'] = $symbol;
			$search_array['time'] = array(
				'$gte' => $start_date,
				'$lte' => $end_date
			);
			$connetct = $this->mongo_db->customQuery();
			$limit = 1;
			$qr = array(
				'skip' => $skip,
				'sort' => array(
					'time' => - 1
				) ,
				'limit' => $limit
			);
			$cursor   = $connetct->market_price_history->find($search_array, $qr);
			$resArray = iterator_to_array($cursor);
			
			if($_SERVER['REMOTE_ADDR'] == '182.191.92.16'){
				
			  //echo $symbol;
			  //echo "<pre>";   print_r($resArray); exit;
			}
			$record   = $resArray[0];	
			$ins_data = array(
				//'coin' => $this->db->escape_str(trim($global_symbol)) ,
				'periods' => $this->db->escape_str(trim($time)) ,
				'market_value' => $this->db->escape_str(trim($record->market_value)) ,
				'coin' => $this->db->escape_str(trim($record->coin)),
				'time' => (($record->time)),
			);
			
		$insert = $this->mongo_db->insert('market_price_minut', $ins_data);
		} // End of coin foreach
		return $finalArr;
	} //saveOneMinutCurrentMarketValue
	
	
	
	public function getCoinaverageChart($global_symbol, $time, $start_date, $end_date, $fianalArrReturn, $x)
	{
		
		
		
		
		if ($time == 'minut') {
			$created_datetime = date('Y-m-d G:i:s', strtotime($start_date));
			$orig_date = new DateTime($created_datetime);
			$orig_date = $orig_date->getTimestamp();
			$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
			$created_datetime22 = date('Y-m-d G:i:s', strtotime($end_date));
			$orig_date22 = new DateTime($created_datetime22);
			$orig_date22 = $orig_date22->getTimestamp();
			$orig_date22 = $orig_date22 + 60;
			$end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
		}
		else
		if ($time == 'hour') {
			$created_datetime = date('Y-m-d G:i:s', strtotime($start_date));
			$orig_date = new DateTime($created_datetime);
			$orig_date = $orig_date->getTimestamp();
			$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
			$created_datetime22 = date('Y-m-d G:i:s', strtotime($end_date));
			$orig_date22 = new DateTime($created_datetime22);
			$orig_date22 = $orig_date22->getTimestamp();
			$orig_date22 = $orig_date22 + 3600;
			$end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
		}

		$all_coins_arr = $this->mod_sockets->get_all_coins();
		$finalArr[] = array(
			'coin_name' => 'Bitcoin',
			'symbol' => 'BTC'
		);
		$all_coins_arrList = array_merge($all_coins_arr, $finalArr);
		$finalArr = array();
		foreach($all_coins_arrList as $coin) {
			$symbol = $coin['symbol'];
			$search_array = array();
			$i = '';
			$condVar = '';
			$search_array['coin'] = $symbol;
			$search_array['time'] = array(
				'$gte' => $start_date,
				'$lte' => $end_date
			);
			$connetct = $this->mongo_db->customQuery();
			$limit = 1;
			$qr = array(
				'skip' => $skip,
				'sort' => array(
					'time' => - 1
				) ,
				'limit' => $limit
			);
			$cursor = $connetct->market_price_history->find($search_array, $qr);
			$resArray = iterator_to_array($cursor);
			
			$count = count($resArray);
			$i = 1;
			$condVar = $count;
			$oldVal['market_value'] = ($resArray[0]->market_value) ? $resArray[0]->market_value : 0;
			$market_value = ($resArray[0]->market_value != '') ? $resArray[0]->market_value : 0;
			if (array_key_exists($symbol, $fianalArrReturn)) {
				$lastValue = $fianalArrReturn[$symbol];
			}
			$finalArr[$coin['symbol']] = (num($market_value) == '') ? $lastValue : num($market_value);
		} // End of coin foreach
		return $finalArr;
	} //getCoinaverageChart
	
	
	public function getCoinaverageChartHour($global_symbol, $start_date, $end_date){
	
		    
		    $timezone          = $this->session->userdata('timezone');
		    $created_datetime = date('Y-m-d G:i:s', strtotime($start_date));
			$orig_date = new DateTime($created_datetime);
			$orig_date = $orig_date->getTimestamp();
			$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
		    
			$created_datetime22 = date('Y-m-d G:i:s', strtotime($end_date));
			$orig_date22 = new DateTime($created_datetime22);
			$orig_date22 = $orig_date22->getTimestamp();
			$end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
			
			
		$all_coins_arr = $this->mod_sockets->get_all_coins();
		//$finalArr[] = array(
			//'coin_name' => 'Bitcoin',
			//'symbol' => 'BTC'
		//);
		//$all_coins_arrList = array_merge($all_coins_arr, $finalArr);
		$finalArr = array();
		foreach($all_coins_arr as $coin) {
			
			$symbol = $coin['symbol'];
			$search_array['coin'] = $symbol;
			$periods              = '1h';
			$this->mongo_db->where(array(
				'coin' => $symbol ,
				'periods' => $periods
			));
		   
			if ($end_date != '') {
				$this->mongo_db->where_lte('timestampDate', $end_date);
			}
			if ($start_date != '') {
				$this->mongo_db->where_gt('timestampDate', $start_date);
				
				$this->mongo_db->sort(array(
					'timestampDate' => 'ASC'
				)); //ASC/DESC
			}
			$this->mongo_db->limit(5000);
			$responseArr          = $this->mongo_db->get('market_chart');
			$resArray             = iterator_to_array($responseArr);
			
		
			$prefix = $fruitList = '';
				foreach($resArray as $key=> $row){
					if($key==0){
						$closevalue  = num($row->close) ;
						continue;
					 }
					 
					$closeCoinnw   = $closevalue - num($row->close);
					$finalValBTC   = $closeCoinnw / $closevalue;
					$finalValBTC   = round($finalValBTC * 100, 2);
					 
					$fianlArr[$resArray[0]->coin] .= $prefix . '' . $finalValBTC . '';
					$prefix = ', ';
				}
		}
		return $fianlArr;
	} //getCoinaverageChart
	
	    
	
	public function getCoinaverageChartMinut($global_symbol, $start_date, $end_date,$post_coin){
		
		    $timezone          = $this->session->userdata('timezone');
		    $created_datetime = date('Y-m-d G:i:s', strtotime($start_date));
			$orig_date = new DateTime($created_datetime);
			$orig_date = $orig_date->getTimestamp();
			$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
			$created_datetime22 = date('Y-m-d G:i:s', strtotime($end_date));
			$orig_date22 = new DateTime($created_datetime22);
			$orig_date22 = $orig_date22->getTimestamp();
			$end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
			//$all_coins_arr = $this->mod_sockets->get_all_coins();
			$finalArr   = array();
			$post_coin  = array_reverse($post_coin);
			foreach($post_coin as $coin) {
				
				$periods  = 'minut';
				$this->mongo_db->where(array(
					'coin' => $coin ,
				));
				if ($end_date != '') {
					$this->mongo_db->where_lte('time', $end_date);
				}
				if ($start_date != '') {
					$this->mongo_db->where_gt('time', $start_date);
					$this->mongo_db->sort(array(
						'timestampDate' => 'ASC'
					)); //ASC/DESC
				}
				$this->mongo_db->limit(5000);
				$responseArr          = $this->mongo_db->get('market_price_minut');
				$resArray             = iterator_to_array($responseArr);	
				$prefix               = $fruitList = '';
				foreach($resArray as $key=> $row){
					if($key==0){
						$closevalue  = num($row->market_value) ;
						continue;
					}	 
					$closeCoinnw   = num($row->market_value) - $closevalue ;
					$finalValBTC   = $closeCoinnw / $closevalue;
					$finalValBTC   = round($finalValBTC * 100, 2);
					$fianlArr[$resArray[0]->coin] .= $prefix . '' . $finalValBTC . '';
					$prefix = ', ';
				}
		}
		// Query Code goes END here 
		return $fianlArr;
	} //getCoinaverageChartMinut
	
	
	public function ChartDataOneMinutCron($global_symbol, $start_date, $end_date){
	
		$created_datetime = date('Y-m-d G:i:s', strtotime($start_date));
		$orig_date = new DateTime($created_datetime);
		$orig_date = $orig_date->getTimestamp();
		$start_date1 = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
		$created_dateEnd = date('Y-m-d G:i:s', strtotime($end_date));
		$orig_date22 = new DateTime($created_dateEnd);
		$orig_date22 = $orig_date22->getTimestamp();
		$end_date2 = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
		$search_array = array();
		$search_array['coin'] = $global_symbol;
		$search_array['modified_date'] = array(
			'$gte' => $start_date1,
			'$lte' => $end_date2
		);
		$connetct = $this->mongo_db->customQuery();
		$limit = 100;
		$qr = array(
			'skip' => $skip,
			'sort' => array(
				'modified_date' => - 1
			) ,
			'limit' => $limit
		);
		
			
		echo $global_symbol;
		echo "<br />";
		echo $start_date;
		echo "<br />";
		echo $end_date;
		echo "<br />";
		echo $start_date1;
		echo "<br />";
		echo $end_date2;
		echo "<br />";
		
		
		$cursor   = $connetct->coin_meta_history->find($search_array, $qr);
		$resArray = iterator_to_array($cursor);
		
		echo "<pre>";  print_r($resArray);
		
		
		
		$count    = count($resArray);
		
		$highestMarketValue  = num(max(array_column($resArray, 'current_market_value')));
		$lowesttMarketValue  = num(min(array_column($resArray, 'current_market_value')));
		
		$i = 1;
		$condVar = $count;
		foreach($resArray as $key => $valueArr) {
			$returArr = array();
			if (!empty($valueArr)) {
				$last_qty_time_agoa     = ($valueArr->last_qty_time_ago) ? $valueArr->last_qty_time_ago : 0;
				$last_200_time_agoa     = ($valueArr->last_200_time_ago) ? $valueArr->last_200_time_ago : 0;
				$last_qty_time_ago_15a  = ($valueArr->last_qty_time_ago_15) ? $valueArr->last_qty_time_ago_15 : 0;
				$last_qty_time_agoNewa  = str_replace(" min ago", "", $last_qty_time_agoa);
				$last_200_time_agoNewa  = str_replace(" min ago", "", $last_200_time_agoa);
				$last_qty_time_ago_15Na = str_replace(" min ago", "", $last_qty_time_ago_15a);
				$black_wall_pressurea   = ($valueArr->black_wall_pressure) ? $valueArr->black_wall_pressure : 0;;
				$yellow_wall_pressurea  = ($valueArr->yellow_wall_pressure) ? $valueArr->yellow_wall_pressure : 0;;
				$pressure_diffa         = ($valueArr->pressure_diff) ? $valueArr->pressure_diff : 0;;
				$seven_level_deptha     = ($valueArr->seven_level_depth) ? $valueArr->seven_level_depth : 0;;
				$scorea                 = ($valueArr->score) ? $valueArr->score : 0;;
				$great_wall_priceArra   = ($valueArr->great_wall_price) ? $valueArr->great_wall_price : 0;;
				$current_market_valuea  = ($valueArr->current_market_value) ? $valueArr->current_market_value : 0;;
				$datetimea              = ($valueArr->modified_date) ? $valueArr->modified_date : 0;;
				$market_depth_quantity  = ($valueArr->market_depth_quantity) ? $valueArr->market_depth_quantity : 0;;
				$market_depth_ask       = ($valueArr->market_depth_ask) ? $valueArr->market_depth_ask : 0;;
				$last_qty_buy_vs_sell   = ($valueArr->last_qty_buy_vs_sell) ? $valueArr->last_qty_buy_vs_sell : 0;;
				$last_200_buy_vs_sell   = ($valueArr->last_200_buy_vs_sell) ? $valueArr->last_200_buy_vs_sell : 0;;
				$buyers                 = ($valueArr->buyers) ? $valueArr->buyers : 0;;
				$sellers                = ($valueArr->sellers) ? $valueArr->sellers : 0;;
				$last_qty_buyVsell_15a  = ($valueArr->last_qty_buy_vs_sell_15) ? $valueArr->last_qty_buy_vs_sell_15 : 0;;
				$buyers_fifteenA        = ($valueArr->buyers_fifteen) ? $valueArr->buyers_fifteen : 0;;
				$sellers_fifteenA       = ($valueArr->sellers_fifteen) ? $valueArr->sellers_fifteen : 0;;
				$sellers_buyers_per_15  = ($valueArr->sellers_buyers_per_fifteen) ? $valueArr->sellers_buyers_per_fifteen : 0;;
				$bid_contracts          = ($valueArr->bid_contracts) ? $valueArr->bid_contracts : 0;;
				$ask_contract           = ($valueArr->ask_contract) ? $valueArr->ask_contract : 0;;
				$ask                    = ($valueArr->ask) ? $valueArr->ask : 0;;
				$bid                    = ($valueArr->bid) ? $valueArr->bid : 0;;
				$buy                    = ($valueArr->buy) ? $valueArr->buy : 0;;
				$sell                   = ($valueArr->sell) ? $valueArr->sell : 0;;
				$sellers_buyers_per_t4cot = ($valueArr->sellers_buyers_per_t4cot) ? $valueArr->sellers_buyers_per_t4cot : 0;
				$sellers_buyers_per       = ($valueArr->sellers_buyers_per) ? $valueArr->sellers_buyers_per : 0;
				
				if ($last_qty_buy_vs_sell > 0) {
					$last_qty_buy_vs_sell = $last_qty_buy_vs_sell - 1;
				}
				else
				if ($last_qty_buy_vs_sell < 0) {
					$last_qty_buy_vs_sell = $last_qty_buy_vs_sell + 1;
				}

				if ($last_200_buy_vs_sell > 0) {
					$last_200_buy_vs_sell = $last_200_buy_vs_sell - 1;
				}
				else
				if ($last_200_buy_vs_sell < 0) {
					$last_200_buy_vs_sell = $last_200_buy_vs_sell + 1;
				}

				if ($last_qty_buyVsell_15a > 0) {
					$last_qty_buyVsell_15a = $last_qty_buyVsell_15a - 1;
				}
				else
				if ($last_qty_buyVsell_15a < 0) {
					$last_qty_buyVsell_15a = $last_qty_buyVsell_15a + 1;
				}
                // ***** New Code Goes here ***** //
                // ***** Percentile Array Goes here ***** //
				$black_wall_percentileArr[]         = $valueArr->black_wall_percentile;
				$sevenlevel_percentileArr[]         = $valueArr->sevenlevel_percentile;
				$rolling_five_bid_percentileArr[]   = $valueArr->rolling_five_bid_percentile;
				$rolling_five_ask_percentileArr[]   = $valueArr->rolling_five_ask_percentile;
				$five_buy_sell_percentileArr[]      = $valueArr->five_buy_sell_percentile;
				$fifteen_buy_sell_percentileArr[]   = $valueArr->fifteen_buy_sell_percentile;
				$last_qty_buy_sell_percentileArr[]  = $valueArr->last_qty_buy_sell_percentile;
				$last_qty_time_percentileArr[]      = $valueArr->last_qty_time_percentile;
				$virtual_barrier_percentileArr[]    = $valueArr->virtual_barrier_percentile;
				$virtual_barrier_percentile_askArr[]= $valueArr->virtual_barrier_percentile_ask;
				$last_qty_time_fif_percentileArr[]  = $valueArr->last_qty_time_fif_percentile;
				$big_buyers_percentileArr[]         = $valueArr->big_buyers_percentile;
				$big_sellers_percentileArr[]        = $valueArr->big_sellers_percentile;
				$buy_percentileArr[]                = $valueArr->buy_percentile;
				$sell_percentileArr[]               = $valueArr->sell_percentile;
				$bid_percentileArr[]                = $valueArr->bid_percentile;
				$ask_percentileArr[]                = $valueArr->ask_percentile;
				// ***** Percentile Array Goes END here ***** //
				$askNW+= $ask;
				$bidNW+= $bid;
				$buyNW+= $buy;
				$sellNW+= $sell;
				$sellers_buyers_per_t4cotNw+= $sellers_buyers_per_t4cot;
				$sellers_buyers_perNW+= $sellers_buyers_per;
				$last_qty_timeAgo_15NW+= $last_qty_time_ago_15Na;
				$last_qty_buyVsell_15NW+= $last_qty_buyVsell_15a;
				$market_depth_quantityNw+= $market_depth_quantity;
				$market_depth_askNw+= $market_depth_ask;
				$last_qty_time_agoNew+= $last_qty_time_agoNewa;
				$last_200_time_agoNew+= $last_200_time_agoNewa;
				$black_wall_pressure+= $black_wall_pressurea;
				$yellow_wall_pressure+= $yellow_wall_pressurea;
				$pressure_diff+= $pressure_diffa;
				$seven_level_depth+= $seven_level_deptha;
				$score+= $scorea;
				$great_wall_priceArr+= num($great_wall_priceArra);
				$current_market_value+= num($current_market_valuea);
				$last_qty_buy_vs_sellNw+= num($last_qty_buy_vs_sell);
				$last_200_buy_vs_sellNw+= num($last_200_buy_vs_sell);
				$buyersNw+= num($buyers);
				$sellersNw+= num($sellers);
				$buyers_fifteenANW+= num($buyers_fifteenA);
				$sellers_fifteenANW+= num($sellers_fifteenA);
				$sellers_buyers_per_15NW+= num($sellers_buyers_per_15);
				$bid_contractsNW+= num($bid_contracts);
				$ask_contractNW+= num($ask_contract);
				$datetime = $datetimea;
				$condVar = $condVar;
				if ($i % $condVar == 0) {
					$last_qty_time_agoNewCon = ($last_qty_time_agoNew != 0) ? $last_qty_time_agoNew / $condVar : 0;
					$last_200_time_agoNewCon = ($last_200_time_agoNew != 0) ? $last_200_time_agoNew / $condVar : 0;;
					$black_wall_pressureCon = ($black_wall_pressure != 0) ? $black_wall_pressure / $condVar : 0;
					$yellow_wall_pressureCon = ($yellow_wall_pressure != 0) ? $yellow_wall_pressure / $condVar : 0;
					$pressure_diffCon = ($pressure_diff != 0) ? $pressure_diff / $condVar : 0;
					$seven_level_depthCon = ($seven_level_depth != 0) ? $seven_level_depth / $condVar : 0;
					$scoreCon = ($score != 0) ? $score / $condVar : 0;
					$great_wall_priceArrCon = ($great_wall_priceArr != 0) ? $great_wall_priceArr / $condVar : 0;
					$current_market_valueCon = ($current_market_value != 0) ? $current_market_value / $condVar : 0;
					$last_qty_buy_vs_sellCon = ($last_qty_buy_vs_sellNw != 0) ? $last_qty_buy_vs_sellNw / $condVar : 0;
					$last_200_buy_vs_sellCon = ($last_200_buy_vs_sellNw != 0) ? $last_200_buy_vs_sellNw / $condVar : 0;
					$buyersNwCon = ($buyersNw != 0) ? $buyersNw / $condVar : 0;
					$sellersNwCon = ($sellersNw != 0) ? $sellersNw / $condVar : 0;
					$great_wall_priceArrCon = ($great_wall_priceArrCon != 0) ? num($great_wall_priceArrCon) : 0;
					$current_market_valueCon = ($current_market_valueCon != 0) ? num($current_market_valueCon) : 0;
					$datetimeCon = ($datetime != 0) ? $datetime : 0;
					$market_depth_quantityNC = ($market_depth_quantityNw != 0) ? $market_depth_quantityNw / $condVar : 0;
					$market_depth_askNwNC = ($market_depth_askNw != 0) ? $market_depth_askNw / $condVar : 0;
					$last_qty_timeAgo_15NWA_AVG = ($last_qty_timeAgo_15NW != 0) ? $last_qty_timeAgo_15NW / $condVar : 0;
					$last_qty_buyVsell_15NWA_AVG = ($last_qty_buyVsell_15NW != 0) ? $last_qty_buyVsell_15NW / $condVar : 0;
					$buyers_fifteenOrg = ($buyers_fifteenANW != 0) ? $buyers_fifteenANW / $condVar : 0;
					$sellers_fifteenOrg = ($sellers_fifteenANW != 0) ? $sellers_fifteenANW / $condVar : 0;
					$sellers_buyers_per_15NW_AVG = ($sellers_buyers_per_15NW != 0) ? $sellers_buyers_per_15NW / $condVar : 0;
					$bid_contractsNW_AVG = ($bid_contractsNW != 0) ? $bid_contractsNW / $condVar : 0;
					$ask_contractNW_AVG = ($ask_contractNW != 0) ? $ask_contractNW / $condVar : 0;
					$askNW_AVG = ($askNW != 0) ? $askNW / $condVar : 0;
					$bidNW_AVG = ($bidNW != 0) ? $bidNW / $condVar : 0;
					$buyNW_AVG = ($buyNW != 0) ? $buyNW / $condVar : 0;
					$sellNW_AVG = ($sellNW != 0) ? $sellNW / $condVar : 0;
					$sellers_buyers_per_t4cotNwAVG = ($sellers_buyers_per_t4cotNw != 0) ? $sellers_buyers_per_t4cotNw / $condVar : 0;
					$sellers_buyers_perNWAVG = ($sellers_buyers_perNW != 0) ? $sellers_buyers_perNW / $condVar : 0;
					$bigBuyDivideSell = ($ask_contractNW_AVGA != 0) ? $ask_contractNW_AVGA / $bid_contractsNW_AVGA : 0;
					$valueLast_qty_buy_vs_sell = ($last_qty_buy_vs_sellCon && $last_qty_buy_vs_sellCon > 10) ? 10 : $last_qty_buy_vs_sellCon;
					$valueLast_200_buy_vs_sell = ($last_200_buy_vs_sellCon && $last_200_buy_vs_sellCon > 10) ? 10 : $last_200_buy_vs_sellCon;
					$valueLast_qty_buy_vs_sellNewOne = ($valueLast_qty_buy_vs_sell && $valueLast_qty_buy_vs_sell < - 10) ? -10 : $valueLast_qty_buy_vs_sell;
					$valueLast_200_buy_vs_sellNewone = ($valueLast_200_buy_vs_sell && $valueLast_200_buy_vs_sell < - 10) ? -10 : $valueLast_200_buy_vs_sell;
					$valueLast_qty_buy_vs_sell = ($last_qty_buy_vs_sellCon && $last_qty_buy_vs_sellCon > 10) ? 10 : $last_qty_buy_vs_sellCon;
					$valueLast_200_buy_vs_sellNewone = ($valueLast_200_buy_vs_sell && $valueLast_200_buy_vs_sell < - 10) ? -10 : $valueLast_200_buy_vs_sell;
					$sellers_buyers_per_15NW_AVG_F = ($sellers_buyers_per_15NW_AVG && $sellers_buyers_per_15NW_AVG > 50) ? 50 : $sellers_buyers_per_15NW_AVG;
					$sellers_buyers_per_15NW_AVG_F = ($sellers_buyers_per_15NW_AVG && $sellers_buyers_per_15NW_AVG < -50) ? -50 : $sellers_buyers_per_15NW_AVG;
					
					
					if ($sellers_buyers_perNWAVG > 50) {
						$sellers_buyers_perNWAVG = 50;
					}
					else
					if ($sellers_buyers_perNWAVG < - 50) {
						$sellers_buyers_perNWAVG = - 50;
					}
					else {
						$sellers_buyers_perNWAVG = $sellers_buyers_perNWAVG;
					}
					if (in_array('1', $black_wall_percentileArr)) {
						$black_wall_percentile = 10;
					}
					else
					if (in_array('-1', $black_wall_percentileArr)) {
						$black_wall_percentile = -10;
					}
					else
					if (in_array('2', $black_wall_percentileArr)) {
						$black_wall_percentile = 9;
					}
					else
					if (in_array('-2', $black_wall_percentileArr)) {
						$black_wall_percentile = -9;
					}
					else
					if (in_array('3', $black_wall_percentileArr)) {
						$black_wall_percentile = 8;
					}
					else
					if (in_array('-3', $black_wall_percentileArr)) {
						$black_wall_percentile = -8;
					}
					else
					if (in_array('4', $black_wall_percentileArr)) {
						$black_wall_percentile = 7;
					}
					else
					if (in_array('-4', $black_wall_percentileArr)) {
						$black_wall_percentile = -7;
					}
					else
					if (in_array('5', $black_wall_percentileArr)) {
						$black_wall_percentile = 5;
					}
					else
					if (in_array('-5', $black_wall_percentileArr)) {
						$black_wall_percentile = -5;
					}
					else
					if (in_array('10', $black_wall_percentileArr)) {
						$black_wall_percentile = 4;
					}
					else
					if (in_array('-10', $black_wall_percentileArr)) {
						$black_wall_percentile = -4;
					}
					else
					if (in_array('15', $black_wall_percentileArr)) {
						$black_wall_percentile = 3;
					}
					else
					if (in_array('-15', $black_wall_percentileArr)) {
						$black_wall_percentile = -3;
					}
					else
					if (in_array('20', $black_wall_percentileArr)) {
						$black_wall_percentile = 2;
					}
					else
					if (in_array('-20', $black_wall_percentileArr)) {
						$black_wall_percentile = -2;
					}
					else
					if (in_array('25', $black_wall_percentileArr)) {
						$black_wall_percentile = 1;
					}
					
					//9-14 End 
					else
					if (in_array('-25', $black_wall_percentileArr)) {
						$black_wall_percentile = -1;
					}
					else {
						$black_wall_percentile = 0;
					}

					// ////////  ********************** //////////
					
					if (in_array('1', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = 10;
					}
					else
					if (in_array('-1', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = -10;
					}
					
					else
					if (in_array('2', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = 9;
					}
					else
					if (in_array('-2', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = -9;
					}
					else
					if (in_array('3', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = 8;
					}
					else
					if (in_array('-3', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = -8;
					}
					else
					if (in_array('4', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = 7;
					}
					else
					if (in_array('-4', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = -7;
					}
					else
					if (in_array('5', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = 5;
					}
					else
					if (in_array('-5', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = -5;
					}
					else
					if (in_array('10', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = 4;
					}
					else
					if (in_array('-10', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = -4;
					}
					else
					if (in_array('15', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = 3;
					}
					else
					if (in_array('-15', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = -3;
					}
					else
					if (in_array('20', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = 2;
					}
					else
					if (in_array('-20', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = -2;
					}
					// 3-14
					
					//9-14 End 
					else
					if (in_array('25', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = 1;
					}
					else
					if (in_array('-25', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = -1;
					}
					else {
						$sevenlevel_percentile = 0;
					}

					// ////////  ********************** //////////
					
					if (in_array('1', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = 10;
					}
					else
					if (in_array('-1', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = -10;
					}
					else
					if (in_array('2', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = 9;
					}
					else
					if (in_array('-2', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = -9;
					}
					else
					if (in_array('3', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = 8;
					}
					else
					if (in_array('-3', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = -8;
					}
					else
					if (in_array('4', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = 7;
					}
					else
					if (in_array('-4', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = -7;
					}
					else
					if (in_array('5', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = 5;
					}
					else
					if (in_array('-5', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = -5;
					}
					else
					if (in_array('10', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = 4;
					}
					else
					if (in_array('-10', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = -4;
					}
					else
					if (in_array('15', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = 3;
					}
					else
					if (in_array('-15', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = -3;
					}
					else
					if (in_array('20', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = 2;
					}
					else
					if (in_array('-20', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = -2;
					}
					// 3-14
					
					//9-14 End 
					else
					if (in_array('25', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = 1;
					}
					else
					if (in_array('-25', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = -1;
					}
					else {
						$rolling_five_bid_percentile = 0;
					}

					// ////////  ********************** //////////
					
					if (in_array('1', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = 10;
					}
					else
					if (in_array('-1', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = -10;
					}
					// **** here 3-20
					else
					if (in_array('2', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = 9;
					}
					else
					if (in_array('-2', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = -9;
					}
					else
					if (in_array('3', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = 8;
					}
					else
					if (in_array('-3', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = -8;
					}
					else
					if (in_array('4', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = 7;
					}
					else
					if (in_array('-4', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = -7;
					}
					else
					if (in_array('5', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = 5;
					}
					else
					if (in_array('-5', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = -5;
					}
					else
					if (in_array('10', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = 4;
					}
					else
					if (in_array('-10', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = - 4;
					}
					else
					if (in_array('15', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = 3;
					}
					else
					if (in_array('-15', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = -3;
					}
					else
					if (in_array('20', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = 2;
					}
					else
					if (in_array('-20', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = -2;
					}
					else
					if (in_array('25', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = 1;
					}
					// 3-14
					//9-14 End 
					
					else
					if (in_array('-25', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = -1;
					}
					else {
						$rolling_five_ask_percentile = 0;
					}

					// ////////  ********************** //////////
						
					if (in_array('1', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = 10;
					}
					else
					if (in_array('-1', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = -10;
					}
					// **************
					else
					if (in_array('2', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = 9;
					}
					else
					if (in_array('-2', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = -9;
					}
					else
					if (in_array('3', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = 8;
					}
					else
					if (in_array('-3', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = -8;
					}
					else
					if (in_array('4', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = 7;
					}
					else
					if (in_array('-4', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = -7;
					}
					else
					if (in_array('5', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = 5;
					}
					else
					if (in_array('-5', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = -5;
					}
					else
					if (in_array('10', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = 4;
					}
					else
					if (in_array('-10', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = -4;
					}
					else
					if (in_array('15', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = 3;
					}
					else
					if (in_array('-15', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = -3;
					}
					else
					if (in_array('20', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = 2;
					}
					else
					if (in_array('-20', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = -2;
					}
					else
					if (in_array('25', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = 1;
					}
					
					// 3-14
					//9-14 End 
					else
					if (in_array('-25', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = -1;
					}
					else {
						$five_buy_sell_percentile = 0;
					}

					// ////////  ********************** //////////
					
					if (in_array('1', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = 10;
					}
					else
					if (in_array('-1', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = -10;
					}
					// ******************
					else
					if (in_array('2', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = 9;
					}
					else
					if (in_array('-2', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = -9;
					}
					else
					if (in_array('3', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = 8;
					}
					else
					if (in_array('-3', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = -8;
					}
					else
					if (in_array('4', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = 7;
					}
					else
					if (in_array('-4', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = -7;
					}
					else
					if (in_array('5', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = 5;
					}
					else
					if (in_array('-5', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = -5;
					}
					else
					if (in_array('10', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = 4;
					}
					else
					if (in_array('-10', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = -4;
					}
					else
					if (in_array('15', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = 3;
					}
					else
					if (in_array('-15', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = -3;
					}
					else
					if (in_array('20', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = 2;
					}
					else
					if (in_array('-20', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = -2;
					}
					else
					if (in_array('25', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = 1;
					}
					// 3-14
					
					//9-14 End 
					else
					if (in_array('-25', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = -1;
					}
					else {
						$fifteen_buy_sell_percentile = 0;
					}

					// ////////  ********************** //////////
					
					if (in_array('1', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = 10;
					}
					else
					if (in_array('-1', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = -10;
					}
					else
					if (in_array('2', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = 9;
					}
					else
					if (in_array('-2', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = -9;
					}
					else
					if (in_array('3', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = 8;
					}
					else
					if (in_array('-3', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = -8;
					}
					else
					if (in_array('4', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = 7;
					}
					else
					if (in_array('-4', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = -7;
					}
					else
					if (in_array('5', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = 5;
					}
					else
					if (in_array('-5', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = -5;
					}
					else
					if (in_array('10', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = 4;
					}
					else
					if (in_array('-10', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = -4;
					}
					else
					if (in_array('15', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = 3;
					}
					else
					if (in_array('-15', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = -3;
					}
					else
					if (in_array('20', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = 2;
					}
					else
					if (in_array('-20', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = -2;
					}
					else
					if (in_array('25', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = 1;
					}
					// 3-14
					//9-14 End 
					else
					if (in_array('-25', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = - 1;
					}
					else {
						$last_qty_buy_sell_percentile = 0;
					}

					// ////////  ********************** //////////
					
					if (in_array('1', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = 10;
					}
					else
					if (in_array('-1', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = -10;
					}
					else
					if (in_array('2', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = 9;
					}
					else
					if (in_array('-2', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = -9;
					}
					else
					if (in_array('3', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = 8;
					}
					else
					if (in_array('-3', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = -8;
					}
					else
					if (in_array('4', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = 7;
					}
					else
					if (in_array('-4', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = -7;
					}
					else
					if (in_array('5', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = 5;
					}
					else
					if (in_array('-5', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = -5;
					}
					else
					if (in_array('10', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = 4;
					}
					else
					if (in_array('-10', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = -4;
					}
					else
					if (in_array('15', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = 3;
					}
					else
					if (in_array('-15', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = -3;
					}
					else
					if (in_array('20', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = 2;
					}
					else
					if (in_array('-20', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = -2;
					}
					else
					if (in_array('25', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = 1;
					}
					// 3-14
					
					//9-14 End 
					else
					if (in_array('-25', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = -1;
					}
					else {
						$last_qty_time_percentile = 0;
					}
					
					// ////////  **********virtual_barrier_percentile************ //////////
					
					if (in_array('1', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = 10;
					}
					else
					if (in_array('-1', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = -10;
					}
					else
					if (in_array('2', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = 9;
					}
					else
					if (in_array('-2', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = -9;
					}
					else
					if (in_array('3', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = 8;
					}
					else
					if (in_array('-3', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = -8;
					}
					else
					if (in_array('4', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = 7;
					}
					else
					if (in_array('-4', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = -7;
					}
					else
					if (in_array('5', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = 5;
					}
					else
					if (in_array('-5', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = -5;
					}
					else
					if (in_array('10', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = 4;
					}
					else
					if (in_array('-10', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = - 4;
					}
					else
					if (in_array('15', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = 3;
					}
					else
					if (in_array('-15', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = -3;
					}
					else
					if (in_array('20', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = 2;
					}
					else
					if (in_array('-20', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = -2;
					}
					else
					if (in_array('25', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = 1;
					}
					// 3-14
					
					
					//9-14 End 
					else
					if (in_array('-25', $virtual_barrier_percentileArr)) {
						$virtual_barrier_percentile = - 1;
					}
					else {
						$virtual_barrier_percentile = 0;
					}
					
					// ////////  **********virtual_barrier_percentile_ask************ //////////
					
					if (in_array('1', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = 10;
					}
					else
					if (in_array('-1', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = -10;
					}
					
					else
					if (in_array('2', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = 9;
					}
					else
					if (in_array('-2', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = -9;
					}
					else
					if (in_array('3', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = 8;
					}
					else
					if (in_array('-3', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = -8;
					}
					else
					if (in_array('4', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = 7;
					}
					else
					if (in_array('-4', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = -7;
					}
					else
					if (in_array('5', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = 5;
					}
					else
					if (in_array('-5', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = -5;
					}
					else
					if (in_array('10', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = 4;
					}
					else
					if (in_array('-10', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = -4;
					}
					else
					if (in_array('15', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = 3;
					}
					else
					if (in_array('-15', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = -3;
					}
					else
					if (in_array('20', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = 2;
					}
					else
					if (in_array('-20', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = -2;
					}
					else
					if (in_array('25', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = 1;
					}
					// 3-14
					
					//9-14 End 
					else
					if (in_array('-25', $virtual_barrier_percentile_askArr)) {
						$virtual_barrier_percentile_ask = -1;
					}
					else {
						$virtual_barrier_percentile_ask = 0;
					}
					
					// ////////  **********last_qty_time_fif_percentile************ //////////
					
					if (in_array('1', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = 10;
					}
					else
					if (in_array('-1', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = -10;
					}
					else
					if (in_array('2', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = 9;
					}
					else
					if (in_array('-2', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = -9;
					}
					else
					if (in_array('3', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = 8;
					}
					else
					if (in_array('-3', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = -8;
					}
					else
					if (in_array('4', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = 7;
					}
					else
					if (in_array('-4', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = -7;
					}
					else
					if (in_array('5', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = 5;
					}
					else
					if (in_array('-5', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = -5;
					}
					else
					if (in_array('10', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = 4;
					}
					else
					if (in_array('-10', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = -4;
					}
					else
					if (in_array('15', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = 3;
					}
					else
					if (in_array('-15', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = -3;
					}
					else
					if (in_array('20', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = 2;
					}
					else
					if (in_array('-20', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = -2;
					}
					else
					if (in_array('25', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = 1;
					}
					// 3-14
					
					//9-14 End 
					else
					if (in_array('-25', $last_qty_time_fif_percentileArr)) {
						$last_qty_time_fif_percentile = - 1;
					}
					else {
						$last_qty_time_fif_percentile = 0;
					}
					
					// ////////  **********big_buyers_percentile************ //////////
					
					if (in_array('1', $big_buyers_percentileArr)) {
						$big_buyers_percentile = 10;
					}
					else
					if (in_array('-1', $big_buyers_percentileArr)) {
						$big_buyers_percentile = -10;
					}
					else
					if (in_array('2', $big_buyers_percentileArr)) {
						$big_buyers_percentile = 9;
					}
					else
					if (in_array('-2', $big_buyers_percentileArr)) {
						$big_buyers_percentile = -9;
					}
					else
					if (in_array('3', $big_buyers_percentileArr)) {
						$big_buyers_percentile = 8;
					}
					else
					if (in_array('-3', $big_buyers_percentileArr)) {
						$big_buyers_percentile = -8;
					}
					else
					if (in_array('4', $big_buyers_percentileArr)) {
						$big_buyers_percentile = 7;
					}
					else
					if (in_array('-4', $big_buyers_percentileArr)) {
						$big_buyers_percentile = -7;
					}
					else
					if (in_array('5', $big_buyers_percentileArr)) {
						$big_buyers_percentile = 5;
					}
					else
					if (in_array('-5', $big_buyers_percentileArr)) {
						$big_buyers_percentile = -5;
					}
					else
					if (in_array('10', $big_buyers_percentileArr)) {
						$big_buyers_percentile = 4;
					}
					else
					if (in_array('-10', $big_buyers_percentileArr)) {
						$big_buyers_percentile = -4;
					}
					else
					if (in_array('15', $big_buyers_percentileArr)) {
						$big_buyers_percentile = 3;
					}
					else
					if (in_array('-15', $big_buyers_percentileArr)) {
						$big_buyers_percentile = -3;
					}
					else
					if (in_array('20', $big_buyers_percentileArr)) {
						$big_buyers_percentile = 2;
					}
					else
					if (in_array('-20', $big_buyers_percentileArr)) {
						$big_buyers_percentile = -2;
					}
					else
					if (in_array('25', $big_buyers_percentileArr)) {
						$big_buyers_percentile = 1;
					}
					// 3-14
					
					//9-14 End 
					else
					if (in_array('-25', $big_buyers_percentileArr)) {
						$big_buyers_percentile = -1;
					}
					else {
						$big_buyers_percentile = 0;
					}
					// ////////  **********big_sellers_percentile************ //////////
					
					if (in_array('1', $big_sellers_percentileArr)) {
						$big_sellers_percentile = 10;
					}
					else
					if (in_array('-1', $big_sellers_percentileArr)) {
						$big_sellers_percentile = -10;
					}
					else
					if (in_array('2', $big_sellers_percentileArr)) {
						$big_sellers_percentile = 9;
					}
					else
					if (in_array('-2', $big_sellers_percentileArr)) {
						$big_sellers_percentile = -9;
					}
					else
					if (in_array('3', $big_sellers_percentileArr)) {
						$big_sellers_percentile = 8;
					}
					else
					if (in_array('-3', $big_sellers_percentileArr)) {
						$big_sellers_percentile = -8;
					}
					else
					if (in_array('4', $big_sellers_percentileArr)) {
						$big_sellers_percentile = 7;
					}
					else
					if (in_array('-4', $big_sellers_percentileArr)) {
						$big_sellers_percentile = -7;
					}
					else
					if (in_array('5', $big_sellers_percentileArr)) {
						$big_sellers_percentile = 5;
					}
					else
					if (in_array('-5', $big_sellers_percentileArr)) {
						$big_sellers_percentile = -5;
					}
					else
					if (in_array('10', $big_sellers_percentileArr)) {
						$big_sellers_percentile = 4;
					}
					else
					if (in_array('-10', $big_sellers_percentileArr)) {
						$big_sellers_percentile = -4;
					}
					else
					if (in_array('15', $big_sellers_percentileArr)) {
						$big_sellers_percentile = 3;
					}
					else
					if (in_array('-15', $big_sellers_percentileArr)) {
						$big_sellers_percentile = -3;
					}
					else
					if (in_array('20', $big_sellers_percentileArr)) {
						$big_sellers_percentile = 2;
					}
					else
					if (in_array('-20', $big_sellers_percentileArr)) {
						$big_sellers_percentile = -2;
					}
					else
					if (in_array('25', $big_sellers_percentileArr)) {
						$big_sellers_percentile = 1;
					}
					// 3-14
					
					//9-14 End 
					else
					if (in_array('-25', $big_sellers_percentileArr)) {
						$big_sellers_percentile = -1;
					}
					else {
						$big_sellers_percentile = 0;
					}
					
					// ////////  **********buy_percentile************ //////////
					
					if (in_array('1', $buy_percentileArr)) {
						$buy_percentile = 10;
					}
					else
					if (in_array('-1', $buy_percentileArr)) {
						$buy_percentile = -10;
					}
					else
					if (in_array('2', $buy_percentileArr)) {
						$buy_percentile = 9;
					}
					else
					if (in_array('-2', $buy_percentileArr)) {
						$buy_percentile = -9;
					}
					else
					if (in_array('3', $buy_percentileArr)) {
						$buy_percentile = 8;
					}
					else
					if (in_array('-3', $buy_percentileArr)) {
						$buy_percentile = -8;
					}
					else
					if (in_array('4', $buy_percentileArr)) {
						$buy_percentile = 7;
					}
					else
					if (in_array('-4', $buy_percentileArr)) {
						$buy_percentile = -7;
					}
					else
					if (in_array('5', $buy_percentileArr)) {
						$buy_percentile = 5;
					}
					else
					if (in_array('-5', $buy_percentileArr)) {
						$buy_percentile = -5;
					}
					else
					if (in_array('10', $buy_percentileArr)) {
						$buy_percentile = 4;
					}
					else
					if (in_array('-10', $buy_percentileArr)) {
						$buy_percentile = -4;
					}
					else
					if (in_array('15', $buy_percentileArr)) {
						$buy_percentile = 3;
					}
					else
					if (in_array('-15', $buy_percentileArr)) {
						$buy_percentile = -3;
					}
					else
					if (in_array('20', $buy_percentileArr)) {
						$buy_percentile = 2;
					}
					else
					if (in_array('-20', $buy_percentileArr)) {
						$buy_percentile = -2;
					}
					else
					if (in_array('25', $buy_percentileArr)) {
						$buy_percentile = 1;
					}
					// 3-14
					
					//9-14 End 
					else
					if (in_array('-25', $buy_percentileArr)) {
						$buy_percentile = -1;
					}
					else {
						$buy_percentile = 0;
					}
					
					
					// ////////  **********big_sellers_percentile************ //////////
					
					if (in_array('1', $sell_percentileArr)) {
						$sell_percentile = 10;
					}
					else
					if (in_array('-1', $sell_percentileArr)) {
						$sell_percentile = -10;
					}
					else
					if (in_array('2', $sell_percentileArr)) {
						$sell_percentile = 9;
					}
					else
					if (in_array('-2', $sell_percentileArr)) {
						$sell_percentile = -9;
					}
					else
					if (in_array('3', $sell_percentileArr)) {
						$sell_percentile = 8;
					}
					else
					if (in_array('-3', $sell_percentileArr)) {
						$sell_percentile = -8;
					}
					else
					if (in_array('4', $sell_percentileArr)) {
						$sell_percentile = 7;
					}
					else
					if (in_array('-4', $sell_percentileArr)) {
						$sell_percentile = -7;
					}
					else
					if (in_array('5', $sell_percentileArr)) {
						$sell_percentile = 5;
					}
					else
					if (in_array('-5', $sell_percentileArr)) {
						$sell_percentile = -5;
					}
					else
					if (in_array('10', $sell_percentileArr)) {
						$sell_percentile = 4;
					}
					else
					if (in_array('-10', $sell_percentileArr)) {
						$sell_percentile = -4;
					}
					else
					if (in_array('15', $sell_percentileArr)) {
						$sell_percentile = 3;
					}
					else
					if (in_array('-15', $sell_percentileArr)) {
						$sell_percentile = -3;
					}
					else
					if (in_array('20', $sell_percentileArr)) {
						$sell_percentile = 2;
					}
					else
					if (in_array('-20', $sell_percentileArr)) {
						$sell_percentile = -2;
					}
					else
					if (in_array('25', $sell_percentileArr)) {
						$sell_percentile = 1;
					}
					// 3-14
					
					//9-14 End 
					else
					if (in_array('-25', $sell_percentileArr)) {
						$sell_percentile = -1;
					}
					else {
						$sell_percentile = 0;
					}
					
					
					// ////////  **********bid_percentile************ //////////
					
					if (in_array('1', $bid_percentileArr)) {
						$bid_percentile = 10;
					}
					else
					if (in_array('-1', $bid_percentileArr)) {
						$bid_percentile = -10;
					}
					else
					if (in_array('2', $bid_percentileArr)) {
						$bid_percentile = 9;
					}
					else
					if (in_array('-2', $bid_percentileArr)) {
						$bid_percentile = -9;
					}
					else
					if (in_array('3', $bid_percentileArr)) {
						$bid_percentile = 8;
					}
					else
					if (in_array('-3', $bid_percentileArr)) {
						$bid_percentile = -8;
					}
					else
					if (in_array('4', $bid_percentileArr)) {
						$bid_percentile = 7;
					}
					else
					if (in_array('-4', $bid_percentileArr)) {
						$bid_percentile = -7;
					}
					else
					if (in_array('5', $bid_percentileArr)) {
						$bid_percentile = 5;
					}
					else
					if (in_array('-5', $bid_percentileArr)) {
						$bid_percentile = -5;
					}
					else
					if (in_array('10', $bid_percentileArr)) {
						$bid_percentile = 4;
					}
					else
					if (in_array('-10', $bid_percentileArr)) {
						$bid_percentile = -4;
					}
					else
					if (in_array('15', $bid_percentileArr)) {
						$bid_percentile = 3;
					}
					else
					if (in_array('-15', $bid_percentileArr)) {
						$bid_percentile = -3;
					}
					else
					if (in_array('20', $bid_percentileArr)) {
						$bid_percentile = 2;
					}
					else
					if (in_array('-20', $bid_percentileArr)) {
						$bid_percentile = -2;
					}
					else
					if (in_array('25', $bid_percentileArr)) {
						$bid_percentile = 1;
					}
					// 3-14
					
					//9-14 End 
					else
					if (in_array('-25', $bid_percentileArr)) {
						$bid_percentile = -1;
					}
					else {
						$bid_percentile = 0;
					}
					
					
					// ////////  **********big_sellers_percentile************ //////////
					
					if (in_array('1', $ask_percentileArr)) {
						$ask_percentile = 10;
					}
					else
					if (in_array('-1', $ask_percentileArr)) {
						$ask_percentile = -10;
					}
					else
					if (in_array('2', $ask_percentileArr)) {
						$ask_percentile = 9;
					}
					else
					if (in_array('-2', $ask_percentileArr)) {
						$ask_percentile = -9;
					}
					else
					if (in_array('3', $ask_percentileArr)) {
						$ask_percentile = 8;
					}
					else
					if (in_array('-3', $ask_percentileArr)) {
						$ask_percentile = -8;
					}
					else
					if (in_array('4', $ask_percentileArr)) {
						$ask_percentile = 7;
					}
					else
					if (in_array('-4', $ask_percentileArr)) {
						$ask_percentile = -7;
					}
					else
					if (in_array('5', $ask_percentileArr)) {
						$ask_percentile = 5;
					}
					else
					if (in_array('-5', $ask_percentileArr)) {
						$ask_percentile = -5;
					}
					else
					if (in_array('10', $ask_percentileArr)) {
						$ask_percentile = 4;
					}
					else
					if (in_array('-10', $ask_percentileArr)) {
						$ask_percentile = -4;
					}
					else
					if (in_array('15', $ask_percentileArr)) {
						$ask_percentile = 3;
					}
					else
					if (in_array('-15', $ask_percentileArr)) {
						$ask_percentile = -3;
					}
					else
					if (in_array('20', $ask_percentileArr)) {
						$ask_percentile = 2;
					}
					else
					if (in_array('-20', $ask_percentileArr)) {
						$ask_percentile = -2;
					}
					else
					if (in_array('25', $ask_percentileArr)) {
						$ask_percentile = 1;
					}
					// 3-14
					//9-14 End 
					else
					if (in_array('-25', $ask_percentileArr)) {
						$ask_percentile = -1;
					}
					else {
						$ask_percentile = 0;
					}
					
					$fullarray = $returArr;
				}
			}

			$i++;
		} // Foreach
		$search_array = array();
		$search_array['coin_symbol'] = $global_symbol;
		$search_array['created_date'] = array(
			'$gte' => $start_date1,
			'$lte' => $end_date2
		);
		$connetct = $this->mongo_db->customQuery();
		$limit = 100;
		$qr = array(
			'skip' => $skip,
			'sort' => array(
				'created_date' => 1
			) ,
			'limit' => $limit
		);
		$cursor = $connetct->barrier_trigger_true_rules_collection->find($search_array, $qr);
		$resArrayColle = iterator_to_array($cursor);
		
		$ruleArra = array();
		$buySum = 0;
		$sellSum = 0;
		foreach($resArrayColle as $ruleName) {
			if ($ruleName['trigger_type'] == 'barrier_percentile_trigger') {
				if ($ruleName['order_level'] == 'level_1') {
					$ruleArra['p_rule'] = '1';
				}
				else
				if ($ruleName['order_level'] == 'level_2') {
					$ruleArra['p_rule'] = '2';
				}
				else
				if ($ruleName['order_level'] == 'level_3') {
					$ruleArra['p_rule'] = '3';
				}
				else
				if ($ruleName['order_level'] == 'level_4') {
					$ruleArra['p_rule'] = '4';
				}
				else
				if ($ruleName['order_level'] == 'level_5') {
					$ruleArra['p_rule'] = '5';
				}
				else
				if ($ruleName['order_level'] == 'level_6') {
					$ruleArra['p_rule'] = '6';
				}
				else
				if ($ruleName['order_level'] == 'level_7') {
					$ruleArra['p_rule'] = '7';
				}
				else
				if ($ruleName['order_level'] == 'level_8') {
					$ruleArra['p_rule'] = '8';
				}
				else
				if ($ruleName['order_level'] == 'level_9') {
					$ruleArra['p_rule'] = '9';
				}
				else
				if ($ruleName['order_level'] == 'level_10') {
					$ruleArra['p_rule'] = '10';
				}

				if ($ruleName['type'] == 'buy') {
					$buySum_p+= 1;
					$buySum+= 1;
					$finalArrRuleBuy_p[] = $ruleArra['p_rule'];
				}

				if ($ruleName['type'] == 'sell') {
					$sellSum_p+= 1;
					$sellSum+= 1;
					$finalArrRuleSell_p[] = $ruleArra['p_rule'];
				}
			}

			if ($ruleName['trigger_type'] == 'barrier_trigger') {
				if ($ruleName['rule_number'] == 'rule_no_1') {
					$ruleArra['b_rule'] = '1';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_2') {
					$ruleArra['b_rule'] = '2';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_3') {
					$ruleArra['b_rule'] = '3';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_4') {
					$ruleArra['b_rule'] = '4';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_5') {
					$ruleArra['b_rule'] = '5';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_6') {
					$ruleArra['b_rule'] = '6';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_7') {
					$ruleArra['b_rule'] = '7';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_8') {
					$ruleArra['b_rule'] = '8';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_9') {
					$ruleArra['b_rule'] = '9';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_10') {
					$ruleArra['b_rule'] = '10';
				}

				if ($ruleName['type'] == 'buy') {
					$buySum_b+= 1;
					$buySum+= 1;
					$finalArrRuleBuy_b[] = $ruleArra['b_rule'];
				}

				if ($ruleName['type'] == 'sell') {
					$sellSum_b+= 1;
					$sellSum+= 1;
					$finalArrRuleSell_b[] = $ruleArra['b_rule'];
				}
			}

			if ($ruleName['trigger_type'] == 'box_trigger_3') {
				if ($ruleName['order_level'] == 'level_1') {
					$ruleArra['bt3_rule'] = ' 1';
				}
				else
				if ($ruleName['order_level'] == 'level_2') {
					$ruleArra['bt3_rule'] = ' 2';
				}
				else
				if ($ruleName['order_level'] == 'level_3') {
					$ruleArra['bt3_rule'] = ' 3';
				}
				else
				if ($ruleName['order_level'] == 'level_4') {
					$ruleArra['bt3_rule'] = ' 4';
				}
				else
				if ($ruleName['order_level'] == 'level_5') {
					$ruleArra['bt3_rule'] = ' 5';
				}
				else
				if ($ruleName['order_level'] == 'level_6') {
					$ruleArra['bt3_rule'] = ' 6';
				}
				else
				if ($ruleName['order_level'] == 'level_7') {
					$ruleArra['bt3_rule'] = ' 7';
				}
				else
				if ($ruleName['order_level'] == 'level_8') {
					$ruleArra['bt3_rule'] = ' 8';
				}
				else
				if ($ruleName['order_level'] == 'level_9') {
					$ruleArra['bt3_rule'] = ' 9';
				}
				else
				if ($ruleName['order_level'] == 'level_10') {
					$ruleArra['bt3_rule'] = ' 10';
				}

				if ($ruleName['type'] == 'buy') {
					$buySumbt3+= 1;
					$buySum+= 1;
					$finalArrRuleBuybt3[] = $ruleArra['bt3_rule'];
				}

				if ($ruleName['type'] == 'sell') {
					$sellSumbt3+= 1;
					$sellSum+= 1;
					$finalArrRuleSellbt3[] = $ruleArra['bt3_rule'];
				}
			}

			if ($ruleName['trigger_type'] == 'barrier_trigger_simulator') {
				if ($ruleName['rule_number'] == 'level_1') {
					$ruleArra['bts_rule'] = ' 1';
				}
				else
				if ($ruleName['rule_number'] == 'level_2') {
					$ruleArra['bts_rule'] = ' 2';
				}
				else
				if ($ruleName['rule_number'] == 'level_3') {
					$ruleArra['bts_rule'] = ' 3';
				}
				else
				if ($ruleName['rule_number'] == 'level_4') {
					$ruleArra['bts_rule'] = ' 4';
				}
				else
				if ($ruleName['rule_number'] == 'level_5') {
					$ruleArra['bts_rule'] = ' 5';
				}
				else
				if ($ruleName['rule_number'] == 'level_6') {
					$ruleArra['bts_rule'] = ' 6';
				}
				else
				if ($ruleName['rule_number'] == 'level_7') {
					$ruleArra['bts_rule'] = ' 7';
				}
				else
				if ($ruleName['rule_number'] == 'level_8') {
					$ruleArra['bts_rule'] = ' 8';
				}
				else
				if ($ruleName['rule_number'] == 'level_9') {
					$ruleArra['bts_rule'] = ' 9';
				}
				else
				if ($ruleName['rule_number'] == 'level_10') {
					$ruleArra['bts_rule'] = ' 10';
				}

				if ($ruleName['type'] == 'buy') {
					$buySum_bts+= 1;
					$buySum+= 1;
					$finalArrRuleBuy_bts[] = $ruleArra['bts_rule'];
				}

				if ($ruleName['type'] == 'sell') {
					$sellSum_bts+= 1;
					$sellSum+= 1;
					$finalArrRuleSell_bts[] = $ruleArra['bts_rule'];
				}
			}
		}
	

		$explodArrBuy = implode(',', $finalArrRuleBuy);
		$explodArrSell = implode(',', $finalArrRuleSell);
		$fullarray['buySum'] = ($buySum != '') ? ($buySum) : 0;
		$fullarray['sellSum'] = ($sellSum != '') ? ($sellSum) : 0;
		$fullarray['rulesBuy'] = $explodArrBuy;
		$fullarray['rulesSell'] = $explodArrSell;

		// Percentile Trigger
		$finalArrRuleBuy_pArr = implode(',', $finalArrRuleBuy_p);
		$finalArrRuleSell_pArr = implode(',', $finalArrRuleSell_p);
		$buySum_p = ($buySum_p != '') ? ($buySum_p) : 0;
		$sellSum_p = ($sellSum_p != '') ? ($sellSum_p) : 0;
		$rulesBuy_pArr = $finalArrRuleBuy_pArr;
		$rulesSell_pArr = $finalArrRuleSell_pArr;

		// Barrier  Trigger
		$finalArrRuleBuy_bArr = implode(',', $finalArrRuleBuy_b);
		$finalArrRuleSell_bArr = implode(',', $finalArrRuleSell_b);
		$buySum_b = ($buySum_b != '') ? ($buySum_b) : 0;
		$sellSum_b = ($sellSum_b != '') ? ($sellSum_b) : 0;
		$rulesBuy_bArr = $finalArrRuleBuy_bArr;
		$rulesSell_bArr = $finalArrRuleSell_bArr;

		// BT3 Trigger
		$finalArrRuleBuybt3Arr = implode(',', $finalArrRuleBuybt3);
		$finalArrRuleSellbt3Arr = implode(',', $finalArrRuleSellbt3);
		$buySum_bt3 = ($buySum_bt3 != '') ? ($buySum_bt3) : 0;
		$sellSum_bt3 = ($sellSum_bt3 != '') ? ($sellSum_bt3) : 0;
		$rulesBuy_bt3Arr = $finalArrRuleBuybt3Arr;
		$rulesSell_bt3Arr = $finalArrRuleSellbt3Arr;

		// Percentile Trigger
		$finalArrRuleBuy_btsArr = implode(',', $finalArrRuleBuy_bts);
		$finalArrRuleSell_btsArr = implode(',', $finalArrRuleSell_bts);
		$buySum_bts = ($buySum_bts != '') ? ($buySum_bts) : 0;
		$sellSum_bts = ($sellSum_bts != '') ? ($sellSum_bts) : 0;
		$rulesBuy_btsArr = $finalArrRuleBuy_btsArr;
		$rulesSell_btsArr = $finalArrRuleSell_btsArr;
     	// ****  Insert The data IntoDb **** //
	
		$ins_data = array(
			'coin' => $this->db->escape_str(trim($global_symbol)) ,
			'market_depth_quantity' => $this->db->escape_str(trim($market_depth_quantityNC)) ,
			'market_depth_ask' => $this->db->escape_str(trim($market_depth_askNwNC)) ,
			'last_qty_time_ago' => $this->db->escape_str(trim($last_qty_time_agoNewCon)) ,
			'last_200_time_ago' => $this->db->escape_str(trim($last_200_time_agoNewCon)) ,
			'black_wall_pressure' => $this->db->escape_str(trim($black_wall_pressureCon)) ,
			'yellow_wall_pressure' => $this->db->escape_str(trim($yellow_wall_pressureCon)) ,
			'pressure_diff' => $this->db->escape_str(trim($pressure_diffCon)) ,
			'seven_level_depth' => $this->db->escape_str(trim($seven_level_depthCon)) ,
			'last_qty_buy_vs_sell' => $this->db->escape_str(trim($valueLast_qty_buy_vs_sellNewOne)) ,
			'last_200_buy_vs_sell' => $this->db->escape_str(trim($valueLast_200_buy_vs_sellNewone)) ,
			'last_qty_time_ago_15' => $this->db->escape_str(trim($last_qty_timeAgo_15NWA_AVG)) ,
			'last_qty_buy_vs_sell_15' => $this->db->escape_str(trim($last_qty_buyVsell_15NWA_AVG)) ,
			'last_qty_buy_vs_sell_15_org' => $this->db->escape_str(trim($last_qty_buyVsell_15NWA_AVG)) ,
			'buyers_fifteen' => $this->db->escape_str(trim($buyers_fifteenOrg)) ,
			'sellers_fifteen' => $this->db->escape_str(trim(($sellers_fifteenOrg))) ,
			'sellers_buyers_per_fifteen' => $this->db->escape_str(trim($sellers_buyers_per_15NW_AVG_F)) ,
			'bid_contracts' => $this->db->escape_str(trim($bid_contractsNW_AVG)) ,
			'ask_contract' => $this->db->escape_str(trim($ask_contractNW_AVG)) ,
			'ask' => $this->db->escape_str(trim($askNW_AVG)) ,
			'bid' => $this->db->escape_str(trim($bidNW_AVG)) ,
			'buy' => $this->db->escape_str(trim($buyNW_AVG)) ,
			'sell' => $this->db->escape_str(trim($sellNW_AVG)) ,
			'buyers' => $this->db->escape_str(trim($buyersNwCon)) ,
			'sellers' => $this->db->escape_str(trim($sellersNwCon)) ,
			'great_wall_price' => $this->db->escape_str(trim($great_wall_priceArrCon)) ,
			'current_market_value' => $this->db->escape_str(trim($current_market_valueCon)) ,
			'highest_market_value' => $this->db->escape_str(trim($highestMarketValue)) ,
			'lowest_market_value'  => $this->db->escape_str(trim($lowesttMarketValue)) ,
			'score' => $this->db->escape_str(trim($scoreCon)) ,
			'datetime_user_friend' => $this->db->escape_str(trim($start_date)) ,
			'datetime_mongo' => $this->mongo_db->converToMongodttime($start_date) ,
			'modified_date' => $this->db->escape_str(trim($start_date1)) ,
			'straightline' => $this->db->escape_str(trim($straightline)) ,
			'buyrule' => $this->db->escape_str(trim($buySum)) ,
			'sellrule' => $this->db->escape_str(trim($sellSum)) ,
			'ruleslevel' => $this->db->escape_str(trim($rulesBuy)) ,
			// New work Goes here
			'buySum_p' => $this->db->escape_str(trim($buySum_p)) ,
			'sellSum_p' => $this->db->escape_str(trim($sellSum_p)) ,
			'rulesBuy_pArr' => $this->db->escape_str(trim($rulesBuy_pArr)) ,
			'rulesSell_pArr' => $this->db->escape_str(trim($rulesSell_pArr)) ,
			'buySum_b' => $this->db->escape_str(trim($buySum_b)) ,
			'sellSum_b' => $this->db->escape_str(trim($sellSum_b)) ,
			'rulesBuy_bArr' => $this->db->escape_str(trim($rulesBuy_bArr)) ,
			'rulesSell_bArr' => $this->db->escape_str(trim($rulesSell_bArr)) ,
			'buySum_bt3' => $this->db->escape_str(trim($buySum_bt3)) ,
			'sellSum_bt3' => $this->db->escape_str(trim($sellSum_bt3)) ,
			'rulesBuy_bt3Arr' => $this->db->escape_str(trim($rulesBuy_bt3Arr)) ,
			'rulesSell_bt3Arr' => $this->db->escape_str(trim($rulesSell_bt3Arr)) ,
			'buySum_bts' => $this->db->escape_str(trim($buySum_bts)) ,
			'sellSum_bts' => $this->db->escape_str(trim($sellSum_bts)) ,
			'rulesBuy_btsArr' => $this->db->escape_str(trim($rulesBuy_btsArr)) ,
			'rulesSell_btsArr' => $this->db->escape_str(trim($rulesSell_btsArr)) ,
			'sellers_buyers_per_t4cot' => $this->db->escape_str(trim($sellers_buyers_per_t4cotNwAVG)) ,
			'sellers_buyers_per' => $this->db->escape_str(trim($sellers_buyers_perNWAVG)) ,
			'black_wall_percentile' => $this->db->escape_str(trim($black_wall_percentile)) ,
			'sevenlevel_percentile' => $this->db->escape_str(trim($sevenlevel_percentile)) ,
			'rolling_five_bid_percentile' => $this->db->escape_str(trim($rolling_five_bid_percentile)) ,
			'rolling_five_ask_percentile' => $this->db->escape_str(trim($rolling_five_ask_percentile)) ,
			'five_buy_sell_percentile' => $this->db->escape_str(trim($five_buy_sell_percentile)) ,
			'fifteen_buy_sell_percentile' => $this->db->escape_str(trim($fifteen_buy_sell_percentile)) ,
			'last_qty_buy_sell_percentile' => $this->db->escape_str(trim($last_qty_buy_sell_percentile)) ,
			'last_qty_time_percentile' => $this->db->escape_str(trim($last_qty_time_percentile)) ,
			'virtual_barrier_percentile' => $this->db->escape_str(trim($virtual_barrier_percentile)) ,
			'virtual_barrier_percentile_ask' => $this->db->escape_str(trim($virtual_barrier_percentile_ask)) ,
			'last_qty_time_fif_percentile' => $this->db->escape_str(trim($last_qty_time_fif_percentile)) ,
			'big_buyers_percentile' => $this->db->escape_str(trim($big_buyers_percentile)) ,
			'big_sellers_percentile' => $this->db->escape_str(trim($big_sellers_percentile)) ,
			'buy_percentile' => $this->db->escape_str(trim($buy_percentile)) ,
			'sell_percentile' => $this->db->escape_str(trim($sell_percentile)) ,
			'bid_percentile' => $this->db->escape_str(trim($bid_percentile)) ,
			'ask_percentile' => $this->db->escape_str(trim($ask_percentile)) ,
		);
		
		 if ($_SERVER['REMOTE_ADDR'] == '58.65.164.72') {
		   //echo "<prE>";
	       //print_r($ins_data);     
		 }
		$insert = $this->mongo_db->insert('highchart_report', $ins_data);
		return true;
	} //ChartDataOneMinutCron
	public function testChartDataOneMinutCron($global_symbol, $start_date, $end_date)
	{
		$created_datetime = date('Y-m-d G:i:s', strtotime($start_date));
		$orig_date = new DateTime($created_datetime);
		$orig_date = $orig_date->getTimestamp();
		$start_date1 = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
		$created_dateEnd = date('Y-m-d G:i:s', strtotime($end_date));
		$orig_date22 = new DateTime($created_dateEnd);
		$orig_date22 = $orig_date22->getTimestamp();
		$end_date2 = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
		$search_array = array();
		$search_array['coin'] = $global_symbol;
		$search_array['modified_date'] = array(
			'$gte' => $start_date1,
			'$lte' => $end_date2
		);
		$connetct = $this->mongo_db->customQuery();
		$limit = 100;
		$qr = array(
			'skip' => $skip,
			'sort' => array(
				'modified_date' => - 1
			) ,
			'limit' => $limit
		);
		$cursor = $connetct->coin_meta_history->find($search_array, $qr);
		$resArray = iterator_to_array($cursor);
		echo "<prE>";
		print_r($resArray);
		//echo "********************** coin_meta_history *******************************";
		echo "<br />";
		
		echo num(max(array_column($resArray, 'current_market_value')));
		echo "<br />";
		
		echo num(min(array_column($resArray, 'current_market_value')));
		echo "<br />";
        exit;
		// echo "<prE>";  print_r($resArray);
		$count = count($resArray);
		$i = 1;
		$condVar = $count;
		foreach($resArray as $key => $valueArr) {
			$returArr = array();
			if (!empty($valueArr)) {
				$last_qty_time_agoa = ($valueArr->last_qty_time_ago) ? $valueArr->last_qty_time_ago : 0;
				$last_200_time_agoa = ($valueArr->last_200_time_ago) ? $valueArr->last_200_time_ago : 0;
				$last_qty_time_ago_15a = ($valueArr->last_qty_time_ago_15) ? $valueArr->last_qty_time_ago_15 : 0;
				$last_qty_time_agoNewa = str_replace(" min ago", "", $last_qty_time_agoa);
				$last_200_time_agoNewa = str_replace(" min ago", "", $last_200_time_agoa);
				$last_qty_time_ago_15Na = str_replace(" min ago", "", $last_qty_time_ago_15a);
				$black_wall_pressurea = ($valueArr->black_wall_pressure) ? $valueArr->black_wall_pressure : 0;;
				$yellow_wall_pressurea = ($valueArr->yellow_wall_pressure) ? $valueArr->yellow_wall_pressure : 0;;
				$pressure_diffa = ($valueArr->pressure_diff) ? $valueArr->pressure_diff : 0;;
				$seven_level_deptha = ($valueArr->seven_level_depth) ? $valueArr->seven_level_depth : 0;;
				$scorea = ($valueArr->score) ? $valueArr->score : 0;;
				$great_wall_priceArra = ($valueArr->great_wall_price) ? $valueArr->great_wall_price : 0;;
				$current_market_valuea = ($valueArr->current_market_value) ? $valueArr->current_market_value : 0;;
				$datetimea = ($valueArr->modified_date) ? $valueArr->modified_date : 0;;
				$market_depth_quantity = ($valueArr->market_depth_quantity) ? $valueArr->market_depth_quantity : 0;;
				$market_depth_ask = ($valueArr->market_depth_ask) ? $valueArr->market_depth_ask : 0;;
				$last_qty_buy_vs_sell = ($valueArr->last_qty_buy_vs_sell) ? $valueArr->last_qty_buy_vs_sell : 0;;
				$last_200_buy_vs_sell = ($valueArr->last_200_buy_vs_sell) ? $valueArr->last_200_buy_vs_sell : 0;;
				$buyers = ($valueArr->buyers) ? $valueArr->buyers : 0;;
				$sellers = ($valueArr->sellers) ? $valueArr->sellers : 0;;
				$last_qty_buyVsell_15a = ($valueArr->last_qty_buy_vs_sell_15) ? $valueArr->last_qty_buy_vs_sell_15 : 0;;
				$buyers_fifteenA = ($valueArr->buyers_fifteen) ? $valueArr->buyers_fifteen : 0;;
				$sellers_fifteenA = ($valueArr->sellers_fifteen) ? $valueArr->sellers_fifteen : 0;;
				$sellers_buyers_per_15 = ($valueArr->sellers_buyers_per_fifteen) ? $valueArr->sellers_buyers_per_fifteen : 0;;
				$bid_contracts = ($valueArr->bid_contracts) ? $valueArr->bid_contracts : 0;;
				$ask_contract = ($valueArr->ask_contract) ? $valueArr->ask_contract : 0;;
				$sellers_buyers_per_t4cot = ($valueArr->sellers_buyers_per_t4cot) ? $valueArr->sellers_buyers_per_t4cot : 0;;
				$ask = ($valueArr->ask) ? $valueArr->ask : 0;;
				$bid = ($valueArr->bid) ? $valueArr->bid : 0;;
				$buy = ($valueArr->buy) ? $valueArr->buy : 0;;
				$sell = ($valueArr->sell) ? $valueArr->sell : 0;;
				if ($last_qty_buy_vs_sell > 0) {
					$last_qty_buy_vs_sell = $last_qty_buy_vs_sell - 1;
				}
				else
				if ($last_qty_buy_vs_sell < 0) {
					$last_qty_buy_vs_sell = $last_qty_buy_vs_sell + 1;
				}

				if ($last_200_buy_vs_sell > 0) {
					$last_200_buy_vs_sell = $last_200_buy_vs_sell - 1;
				}
				else
				if ($last_200_buy_vs_sell < 0) {
					$last_200_buy_vs_sell = $last_200_buy_vs_sell + 1;
				}

				if ($last_qty_buyVsell_15a > 0) {
					$last_qty_buyVsell_15a = $last_qty_buyVsell_15a - 1;
				}
				else
				if ($last_qty_buyVsell_15a < 0) {
					$last_qty_buyVsell_15a = $last_qty_buyVsell_15a + 1;
				}

				if ($valueArr->black_wall_percentile == 25) {
					$black_wall_percentile = 5;
				}
				else
				if ($valueArr->black_wall_percentile == 20) {
					$black_wall_percentile = 4;
				}
				else
				if ($valueArr->black_wall_percentile == 15) {
					$black_wall_percentile = 3;
				}
				else
				if ($valueArr->black_wall_percentile == 10) {
					$black_wall_percentile = 2;
				}
				else
				if ($valueArr->black_wall_percentile == 5) {
					$black_wall_percentile = 1;
				}
				else
				if ($valueArr->black_wall_percentile == - 5) {
					$black_wall_percentile = - 1;
				}
				else
				if ($valueArr->black_wall_percentile == - 10) {
					$black_wall_percentile = - 2;
				}
				else
				if ($valueArr->black_wall_percentile == - 15) {
					$black_wall_percentile = - 3;
				}
				else
				if ($valueArr->black_wall_percentile == - 20) {
					$black_wall_percentile = - 4;
				}
				else
				if ($valueArr->black_wall_percentile == - 25) {
					$black_wall_percentile = - 5;
				}

				// //////////////////////////////////////
				if ($valueArr->sevenlevel_percentile == 25) {
					$sevenlevel_percentile = 5;
				}
				else
				if ($valueArr->sevenlevel_percentile == 20) {
					$sevenlevel_percentile = 4;
				}
				else
				if ($valueArr->sevenlevel_percentile == 15) {
					$sevenlevel_percentile = 3;
				}
				else
				if ($valueArr->sevenlevel_percentile == 10) {
					$sevenlevel_percentile = 2;
				}
				else
				if ($valueArr->sevenlevel_percentile == 5) {
					$sevenlevel_percentile = 1;
				}
				else
				if ($valueArr->sevenlevel_percentile == - 5) {
					$sevenlevel_percentile = - 1;
				}
				else
				if ($valueArr->sevenlevel_percentile == - 10) {
					$sevenlevel_percentile = - 2;
				}
				else
				if ($valueArr->sevenlevel_percentile == - 15) {
					$sevenlevel_percentile = - 3;
				}
				else
				if ($valueArr->sevenlevel_percentile == - 20) {
					$sevenlevel_percentile = - 4;
				}
				else
				if ($valueArr->sevenlevel_percentile == - 25) {
					$sevenlevel_percentile = - 5;
				}

				// //////////////////////////////////////
				if ($valueArr->rolling_five_bid_percentile == 25) {
					$rolling_five_bid_percentile = 5;
				}
				else
				if ($valueArr->rolling_five_bid_percentile == 20) {
					$rolling_five_bid_percentile = 4;
				}
				else
				if ($valueArr->rolling_five_bid_percentile == 15) {
					$rolling_five_bid_percentile = 3;
				}
				else
				if ($valueArr->rolling_five_bid_percentile == 10) {
					$rolling_five_bid_percentile = 2;
				}
				else
				if ($valueArr->rolling_five_bid_percentile == 5) {
					$rolling_five_bid_percentile = 1;
				}
				else
				if ($valueArr->rolling_five_bid_percentile == - 5) {
					$rolling_five_bid_percentile = - 1;
				}
				else
				if ($valueArr->rolling_five_bid_percentile == - 10) {
					$rolling_five_bid_percentile = - 2;
				}
				else
				if ($valueArr->rolling_five_bid_percentile == - 15) {
					$rolling_five_bid_percentile = - 3;
				}
				else
				if ($valueArr->rolling_five_bid_percentile == - 20) {
					$rolling_five_bid_percentile = - 4;
				}
				else
				if ($valueArr->rolling_five_bid_percentile == - 25) {
					$rolling_five_bid_percentile = - 5;
				}

				// //////////////////////////////////////
				if ($valueArr->rolling_five_ask_percentile == 25) {
					$rolling_five_ask_percentile = 5;
				}
				else
				if ($valueArr->rolling_five_ask_percentile == 20) {
					$rolling_five_ask_percentile = 4;
				}
				else
				if ($valueArr->rolling_five_ask_percentile == 15) {
					$rolling_five_ask_percentile = 3;
				}
				else
				if ($valueArr->rolling_five_ask_percentile == 10) {
					$rolling_five_ask_percentile = 2;
				}
				else
				if ($valueArr->rolling_five_ask_percentile == 5) {
					$rolling_five_ask_percentile = 1;
				}
				else
				if ($valueArr->rolling_five_ask_percentile == - 5) {
					$rolling_five_ask_percentile = - 1;
				}
				else
				if ($valueArr->rolling_five_ask_percentile == - 10) {
					$rolling_five_ask_percentile = - 2;
				}
				else
				if ($valueArr->rolling_five_ask_percentile == - 15) {
					$rolling_five_ask_percentile = - 3;
				}
				else
				if ($valueArr->rolling_five_ask_percentile == - 20) {
					$rolling_five_ask_percentile = - 4;
				}
				else
				if ($valueArr->rolling_five_ask_percentile == - 25) {
					$rolling_five_ask_percentile = - 5;
				}

				// //////////////////////////////////////
				if ($valueArr->five_buy_sell_percentile == 25) {
					$five_buy_sell_percentile = 5;
				}
				else
				if ($valueArr->five_buy_sell_percentile == 20) {
					$five_buy_sell_percentile = 4;
				}
				else
				if ($valueArr->five_buy_sell_percentile == 15) {
					$five_buy_sell_percentile = 3;
				}
				else
				if ($valueArr->five_buy_sell_percentile == 10) {
					$five_buy_sell_percentile = 2;
				}
				else
				if ($valueArr->five_buy_sell_percentile == 5) {
					$five_buy_sell_percentile = 1;
				}
				else
				if ($valueArr->five_buy_sell_percentile == - 5) {
					$five_buy_sell_percentile = - 1;
				}
				else
				if ($valueArr->five_buy_sell_percentile == - 10) {
					$five_buy_sell_percentile = - 2;
				}
				else
				if ($valueArr->five_buy_sell_percentile == - 15) {
					$five_buy_sell_percentile = - 3;
				}
				else
				if ($valueArr->five_buy_sell_percentile == - 20) {
					$five_buy_sell_percentile = - 4;
				}
				else
				if ($valueArr->five_buy_sell_percentile == - 25) {
					$five_buy_sell_percentile = - 5;
				}

				// //////////////////////////////////////
				if ($valueArr->fifteen_buy_sell_percentile == 25) {
					$fifteen_buy_sell_percentile = 5;
				}
				else
				if ($valueArr->fifteen_buy_sell_percentile == 20) {
					$fifteen_buy_sell_percentile = 4;
				}
				else
				if ($valueArr->fifteen_buy_sell_percentile == 15) {
					$fifteen_buy_sell_percentile = 3;
				}
				else
				if ($valueArr->fifteen_buy_sell_percentile == 10) {
					$fifteen_buy_sell_percentile = 2;
				}
				else
				if ($valueArr->fifteen_buy_sell_percentile == 5) {
					$fifteen_buy_sell_percentile = 1;
				}
				else
				if ($valueArr->fifteen_buy_sell_percentile == - 5) {
					$fifteen_buy_sell_percentile = - 1;
				}
				else
				if ($valueArr->fifteen_buy_sell_percentile == - 10) {
					$fifteen_buy_sell_percentile = - 2;
				}
				else
				if ($valueArr->fifteen_buy_sell_percentile == - 15) {
					$fifteen_buy_sell_percentile = - 3;
				}
				else
				if ($valueArr->fifteen_buy_sell_percentile == - 20) {
					$fifteen_buy_sell_percentile = - 4;
				}
				else
				if ($valueArr->fifteen_buy_sell_percentile == - 25) {
					$fifteen_buy_sell_percentile = - 5;
				}

				// //////////////////////////////////////
				if ($valueArr->last_qty_buy_sell_percentile == 25) {
					$last_qty_buy_sell_percentile = 5;
				}
				else
				if ($valueArr->last_qty_buy_sell_percentile == 20) {
					$last_qty_buy_sell_percentile = 4;
				}
				else
				if ($valueArr->last_qty_buy_sell_percentile == 15) {
					$last_qty_buy_sell_percentile = 3;
				}
				else
				if ($valueArr->last_qty_buy_sell_percentile == 10) {
					$last_qty_buy_sell_percentile = 2;
				}
				else
				if ($valueArr->last_qty_buy_sell_percentile == 5) {
					$last_qty_buy_sell_percentile = 1;
				}
				else
				if ($valueArr->last_qty_buy_sell_percentile == - 5) {
					$last_qty_buy_sell_percentile = - 1;
				}
				else
				if ($valueArr->last_qty_buy_sell_percentile == - 10) {
					$last_qty_buy_sell_percentile = - 2;
				}
				else
				if ($valueArr->last_qty_buy_sell_percentile == - 15) {
					$last_qty_buy_sell_percentile = - 3;
				}
				else
				if ($valueArr->last_qty_buy_sell_percentile == - 20) {
					$last_qty_buy_sell_percentile = - 4;
				}
				else
				if ($valueArr->last_qty_buy_sell_percentile == - 25) {
					$last_qty_buy_sell_percentile = - 5;
				}

				// //////////////////////////////////////
				if ($valueArr->last_qty_time_percentile == 25) {
					$last_qty_time_percentile = 5;
				}
				else
				if ($valueArr->last_qty_time_percentile == 20) {
					$last_qty_time_percentile = 4;
				}
				else
				if ($valueArr->last_qty_time_percentile == 15) {
					$last_qty_time_percentile = 3;
				}
				else
				if ($valueArr->last_qty_time_percentile == 10) {
					$last_qty_time_percentile = 2;
				}
				else
				if ($valueArr->last_qty_time_percentile == 5) {
					$last_qty_time_percentile = 1;
				}
				else
				if ($valueArr->last_qty_time_percentile == - 5) {
					$last_qty_time_percentile = - 1;
				}
				else
				if ($valueArr->last_qty_time_percentile == - 10) {
					$last_qty_time_percentile = - 2;
				}
				else
				if ($valueArr->last_qty_time_percentile == - 15) {
					$last_qty_time_percentile = - 3;
				}
				else
				if ($valueArr->last_qty_time_percentile == - 20) {
					$last_qty_time_percentile = - 4;
				}
				else
				if ($valueArr->last_qty_time_percentile == - 25) {
					$last_qty_time_percentile = - 5;
				}

				$black_wall_percentileArr[] = $valueArr->black_wall_percentile;
				
				
				
				
				
				$sevenlevel_percentileArr[] = $valueArr->sevenlevel_percentile;
				$rolling_five_bid_percentileArr[] = $valueArr->rolling_five_bid_percentile;
				$rolling_five_ask_percentileArr[] = $valueArr->rolling_five_ask_percentile;
				$five_buy_sell_percentileArr[] = $valueArr->five_buy_sell_percentile;
				$fifteen_buy_sell_percentileArr[] = $valueArr->fifteen_buy_sell_percentile;
				$last_qty_buy_sell_percentileArr[] = $valueArr->last_qty_buy_sell_percentile;
				$last_qty_time_percentileArr[] = $valueArr->last_qty_time_percentile;
				$askNW+= $ask;
				$bidNW+= $bid;
				$buyNW+= $buy;
				$sellNW+= $sell;
				$last_qty_timeAgo_15NW+= $last_qty_time_ago_15Na;
				$last_qty_buyVsell_15NW+= $last_qty_buyVsell_15a;
				$market_depth_quantityNw+= $market_depth_quantity;
				$market_depth_askNw+= $market_depth_ask;
				$last_qty_time_agoNew+= $last_qty_time_agoNewa;
				$last_200_time_agoNew+= $last_200_time_agoNewa;
				$black_wall_pressure+= $black_wall_pressurea;
				$yellow_wall_pressure+= $yellow_wall_pressurea;
				$pressure_diff+= $pressure_diffa;
				$seven_level_depth+= $seven_level_deptha;
				$score+= $scorea;
				$great_wall_priceArr+= num($great_wall_priceArra);
				$current_market_value+= num($current_market_valuea);
				$last_qty_buy_vs_sellNw+= num($last_qty_buy_vs_sell);
				$last_200_buy_vs_sellNw+= num($last_200_buy_vs_sell);
				$buyersNw+= num($buyers);
				$sellersNw+= num($sellers);
				$buyers_fifteenANW+= num($buyers_fifteenA);
				$sellers_fifteenANW+= num($sellers_fifteenA);
				$sellers_buyers_per_15NW+= num($sellers_buyers_per_15);
				$bid_contractsNW+= num($bid_contracts);
				$ask_contractNW+= num($ask_contract);
				$sellers_buyers_per_t4cotNw+= num($sellers_buyers_per_t4cot);
				$datetime = $datetimea;
				$condVar = $condVar;
				if ($i % $condVar == 0) {

					$last_qty_time_agoNewCon = ($last_qty_time_agoNew != 0) ? $last_qty_time_agoNew / $condVar : 0;
					$last_200_time_agoNewCon = ($last_200_time_agoNew != 0) ? $last_200_time_agoNew / $condVar : 0;;
					$black_wall_pressureCon = ($black_wall_pressure != 0) ? $black_wall_pressure / $condVar : 0;
					$yellow_wall_pressureCon = ($yellow_wall_pressure != 0) ? $yellow_wall_pressure / $condVar : 0;
					$pressure_diffCon = ($pressure_diff != 0) ? $pressure_diff / $condVar : 0;
					$seven_level_depthCon = ($seven_level_depth != 0) ? $seven_level_depth / $condVar : 0;
					$scoreCon = ($score != 0) ? $score / $condVar : 0;
					$great_wall_priceArrCon = ($great_wall_priceArr != 0) ? $great_wall_priceArr / $condVar : 0;
					$current_market_valueCon = ($current_market_value != 0) ? $current_market_value / $condVar : 0;
					$last_qty_buy_vs_sellCon = ($last_qty_buy_vs_sellNw != 0) ? $last_qty_buy_vs_sellNw / $condVar : 0;
					$last_200_buy_vs_sellCon = ($last_200_buy_vs_sellNw != 0) ? $last_200_buy_vs_sellNw / $condVar : 0;
					$buyersNwCon = ($buyersNw != 0) ? $buyersNw / $condVar : 0;
					$sellersNwCon = ($sellersNw != 0) ? $sellersNw / $condVar : 0;
					$great_wall_priceArrCon = ($great_wall_priceArrCon != 0) ? num($great_wall_priceArrCon) : 0;
					$current_market_valueCon = ($current_market_valueCon != 0) ? num($current_market_valueCon) : 0;
					$datetimeCon = ($datetime != 0) ? $datetime : 0;
					$market_depth_quantityNC = ($market_depth_quantityNw != 0) ? $market_depth_quantityNw / $condVar : 0;
					$market_depth_askNwNC = ($market_depth_askNw != 0) ? $market_depth_askNw / $condVar : 0;
					$last_qty_timeAgo_15NWA_AVG = ($last_qty_timeAgo_15NW != 0) ? $last_qty_timeAgo_15NW / $condVar : 0;
					$last_qty_buyVsell_15NWA_AVG = ($last_qty_buyVsell_15NW != 0) ? $last_qty_buyVsell_15NW / $condVar : 0;
					$buyers_fifteenOrg = ($buyers_fifteenANW != 0) ? $buyers_fifteenANW / $condVar : 0;
					$sellers_fifteenOrg = ($sellers_fifteenANW != 0) ? $sellers_fifteenANW / $condVar : 0;
					$sellers_buyers_per_15NW_AVG = ($sellers_buyers_per_15NW != 0) ? $sellers_buyers_per_15NW / $condVar : 0;
					$bid_contractsNW_AVG = ($bid_contractsNW != 0) ? $bid_contractsNW / $condVar : 0;
					$ask_contractNW_AVG = ($ask_contractNW != 0) ? $ask_contractNW / $condVar : 0;
					$askNW_AVG = ($askNW != 0) ? ($askNW / $condVar) : 0;
					$bidNW_AVG = ($bidNW != 0) ? ($bidNW / $condVar) : 0;
					$buyNW_AVG = ($buyNW != 0) ? $buyNW / $condVar : 0;
					$sellNW_AVG = ($sellNW != 0) ? $sellNW / $condVar : 0;
					$sellers_buyers_per_t4cotNwAVG = ($sellers_buyers_per_t4cotNw != 0) ? ($sellers_buyers_per_t4cotNw / $condVar) : 0;
					$bigBuyDivideSell = ($ask_contractNW_AVGA != 0) ? $ask_contractNW_AVGA / $bid_contractsNW_AVGA : 0;
					$valueLast_qty_buy_vs_sell = ($last_qty_buy_vs_sellCon && $last_qty_buy_vs_sellCon > 10) ? 10 : $last_qty_buy_vs_sellCon;
					$valueLast_200_buy_vs_sell = ($last_200_buy_vs_sellCon && $last_200_buy_vs_sellCon > 10) ? 10 : $last_200_buy_vs_sellCon;
					$valueLast_qty_buy_vs_sellNewOne = ($valueLast_qty_buy_vs_sell && $valueLast_qty_buy_vs_sell < - 10) ? -10 : $valueLast_qty_buy_vs_sell;
					$valueLast_200_buy_vs_sellNewone = ($valueLast_200_buy_vs_sell && $valueLast_200_buy_vs_sell < - 10) ? -10 : $valueLast_200_buy_vs_sell;
					if (in_array('5', $black_wall_percentileArray)) {
						$black_wall_percentile = 5;
					}
					else
					if (in_array('-5', $black_wall_percentileArray)) {
						$black_wall_percentile = -5;
					}
					else
					if (in_array('10', $black_wall_percentileArray)) {
						$black_wall_percentile = 4;
					}
					else
					if (in_array('-10', $black_wall_percentileArray)) {
						$black_wall_percentile = -4;
					}
					else
					if (in_array('15', $black_wall_percentileArray)) {
						$black_wall_percentile = 3;
					}
					else
					if (in_array('-15', $black_wall_percentileArray)) {
						$black_wall_percentile = -3;
					}
					else
					if (in_array('20', $black_wall_percentileArray)) {
						$black_wall_percentile = 2;
					}
					else
					if (in_array('-20', $black_wall_percentileArray)) {
						$black_wall_percentile = -2;
					}
					else
					if (in_array('25', $black_wall_percentileArray)) {
						$black_wall_percentile = 1;
					}
					else
					if (in_array('-25', $black_wall_percentileArray)) {
						$black_wall_percentile = -1;
					}
					else {
						$black_wall_percentile = 0;
					}

					// ////////  ********************** //////////
					if (in_array('5', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = 5;
					}
					else
					if (in_array('-5', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = - 5;
					}
					else
					if (in_array('10', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = 4;
					}
					else
					if (in_array('-10', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = - 4;
					}
					else
					if (in_array('15', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = 3;
					}
					else
					if (in_array('-15', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = - 3;
					}
					else
					if (in_array('20', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = 2;
					}
					else
					if (in_array('-20', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = - 2;
					}
					else
					if (in_array('25', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = 1;
					}
					else
					if (in_array('-25', $sevenlevel_percentileArr)) {
						$sevenlevel_percentile = - 1;
					}
					else {
						$sevenlevel_percentile = 0;
					}

					// ////////  ********************** //////////
					if (in_array('5', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = 5;
					}
					else
					if (in_array('-5', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = - 5;
					}
					else
					if (in_array('10', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = 4;
					}
					else
					if (in_array('-10', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = - 4;
					}
					else
					if (in_array('15', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = 3;
					}
					else
					if (in_array('-15', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = - 3;
					}
					else
					if (in_array('20', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = 2;
					}
					else
					if (in_array('-20', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = - 2;

					}
					else
					if (in_array('25', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = 1;
					}
					else
					if (in_array('-25', $rolling_five_bid_percentileArr)) {
						$rolling_five_bid_percentile = - 1;
					}
					else {
						$rolling_five_bid_percentile = 0;
					}

					// ////////  ********************** //////////
					if (in_array('5', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = 5;
					}
					else
					if (in_array('-5', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = - 5;
					}
					else
					if (in_array('10', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = 4;
					}
					else
					if (in_array('-10', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = - 4;
					}
					else
					if (in_array('15', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = 3;
					}
					else
					if (in_array('-15', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = - 3;
					}
					else
					if (in_array('20', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = 2;
					}
					else
					if (in_array('-20', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = - 2;
					}
					else
					if (in_array('25', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = 1;
					}
					else
					if (in_array('-25', $rolling_five_ask_percentileArr)) {
						$rolling_five_ask_percentile = - 1;
					}
					else {
						$rolling_five_ask_percentile = 0;
					}

					// ////////  ********************** //////////
					if (in_array('5', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = 5;
					}
					else
					if (in_array('-5', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = - 5;
					}
					else
					if (in_array('10', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = 4;
					}
					else
					if (in_array('-10', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = - 4;
					}
					else
					if (in_array('15', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = 3;
					}
					else
					if (in_array('-15', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = - 3;
					}
					else
					if (in_array('20', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = 2;
					}
					else
					if (in_array('-20', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = - 2;
					}
					else
					if (in_array('25', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = 1;
					}
					else
					if (in_array('-25', $five_buy_sell_percentileArr)) {
						$five_buy_sell_percentile = - 1;
					}
					else {
						$five_buy_sell_percentile = 0;
					}

					// ////////  ********************** //////////
					if (in_array('5', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = 5;
					}
					else
					if (in_array('-5', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = - 5;
					}
					else
					if (in_array('10', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = 4;
					}
					else
					if (in_array('-10', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = - 4;
					}
					else
					if (in_array('15', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = 3;
					}
					else
					if (in_array('-15', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = - 3;
					}
					else
					if (in_array('20', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = 2;
					}
					else
					if (in_array('-20', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = - 2;
					}
					else
					if (in_array('25', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = 1;
					}
					else
					if (in_array('-25', $fifteen_buy_sell_percentileArr)) {
						$fifteen_buy_sell_percentile = - 1;
					}
					else {
						$fifteen_buy_sell_percentile = 0;
					}

					// ////////  ********************** //////////
					if (in_array('5', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = 5;
					}
					else
					if (in_array('-5', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = - 5;
					}
					else
					if (in_array('10', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = 4;
					}
					else
					if (in_array('-10', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = - 4;
					}
					else
					if (in_array('15', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = 3;
					}
					else
					if (in_array('-15', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = - 3;
					}
					else
					if (in_array('20', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = 2;
					}
					else
					if (in_array('-20', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = - 2;
					}
					else
					if (in_array('25', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = 1;
					}
					else
					if (in_array('-25', $last_qty_buy_sell_percentileArr)) {
						$last_qty_buy_sell_percentile = - 1;
					}
					else {
						$last_qty_buy_sell_percentile = 0;
					}

					// ////////  ********************** //////////
					if (in_array('5', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = 5;
					}
					else
					if (in_array('-5', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = - 5;
					}
					else
					if (in_array('10', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = 4;
					}
					else
					if (in_array('-10', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = - 4;
					}
					else
					if (in_array('15', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = 3;
					}
					else
					if (in_array('-15', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = - 3;
					}
					else
					if (in_array('20', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = 2;
					}
					else
					if (in_array('-20', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = - 2;
					}
					else
					if (in_array('25', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = 1;
					}
					else
					if (in_array('-25', $last_qty_time_percentileArr)) {
						$last_qty_time_percentile = - 1;
					}
					else {
						$last_qty_time_percentile = 0;
					}
					$fullarray = $returArr;
				}
			}

			$i++;
		} // Foreach
		$search_array = array();
		$search_array['coin_symbol'] = $global_symbol;
		$search_array['created_date'] = array(
			'$gte' => $start_date1,
			'$lte' => $end_date2
		);
		$connetct = $this->mongo_db->customQuery();
		$limit = 1000;
		$qr = array(
			'skip' => $skip,
			'sort' => array(
				'created_date' => - 1
			) ,
			'limit' => $limit
		);

		$cursor = $connetct->barrier_trigger_true_rules_collection->find($search_array, $qr);
		$resArrayColle = iterator_to_array($cursor);
		$ruleArra = array();
		$buySum = 0;
		$sellSum = 0;
		foreach($resArrayColle as $ruleName) {
			if ($ruleName['trigger_type'] == 'barrier_percentile_trigger') {
				if ($ruleName['order_level'] == 'level_1') {
					$ruleArra['p_rule'] = '1';
				}
				else
				if ($ruleName['order_level'] == 'level_2') {
					$ruleArra['p_rule'] = '2';
				}
				else
				if ($ruleName['order_level'] == 'level_3') {
					$ruleArra['p_rule'] = '3';
				}
				else
				if ($ruleName['order_level'] == 'level_4') {
					$ruleArra['p_rule'] = '4';
				}
				else
				if ($ruleName['order_level'] == 'level_5') {
					$ruleArra['p_rule'] = '5';
				}
				else
				if ($ruleName['order_level'] == 'level_6') {
					$ruleArra['p_rule'] = '6';
				}
				else
				if ($ruleName['order_level'] == 'level_7') {
					$ruleArra['p_rule'] = '7';
				}
				else
				if ($ruleName['order_level'] == 'level_8') {
					$ruleArra['p_rule'] = '8';
				}
				else
				if ($ruleName['order_level'] == 'level_9') {
					$ruleArra['p_rule'] = '9';
				}
				else
				if ($ruleName['order_level'] == 'level_10') {
					$ruleArra['p_rule'] = '10';
				}

				if ($ruleName['type'] == 'buy') {
					$buySum_p+= 1;
					$finalArrRuleBuy_p[] = $ruleArra['p_rule'];
				}

				if ($ruleName['type'] == 'sell') {
					$sellSum_p+= 1;
					$finalArrRuleSell_p[] = $ruleArra['p_rule'];
				}
			}

			if ($ruleName['trigger_type'] == 'barrier_trigger') {
				if ($ruleName['rule_number'] == 'rule_no_1') {
					$ruleArra['b_rule'] = '1';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_2') {
					$ruleArra['b_rule'] = '2';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_3') {
					$ruleArra['b_rule'] = '3';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_4') {
					$ruleArra['b_rule'] = '4';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_5') {
					$ruleArra['b_rule'] = '5';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_6') {
					$ruleArra['b_rule'] = '6';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_7') {
					$ruleArra['b_rule'] = '7';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_8') {
					$ruleArra['b_rule'] = '8';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_9') {
					$ruleArra['b_rule'] = '9';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_10') {
					$ruleArra['b_rule'] = '10';
				}

				if ($ruleName['type'] == 'buy') {
					$buySum_b+= 1;
					$finalArrRuleBuy_b[] = $ruleArra['b_rule'];
				}

				if ($ruleName['type'] == 'sell') {
					$sellSum_b+= 1;
					$finalArrRuleSell_b[] = $ruleArra['b_rule'];
				}
			}

			if ($ruleName['trigger_type'] == 'box_trigger_3') {
				if ($ruleName['order_level'] == 'level_1') {
					$ruleArra['bt3_rule'] = ' 1';
				}
				else
				if ($ruleName['order_level'] == 'level_2') {
					$ruleArra['bt3_rule'] = ' 2';
				}
				else
				if ($ruleName['order_level'] == 'level_3') {
					$ruleArra['bt3_rule'] = ' 3';
				}
				else
				if ($ruleName['order_level'] == 'level_4') {
					$ruleArra['bt3_rule'] = ' 4';
				}
				else
				if ($ruleName['order_level'] == 'level_5') {
					$ruleArra['bt3_rule'] = ' 5';
				}
				else
				if ($ruleName['order_level'] == 'level_6') {
					$ruleArra['bt3_rule'] = ' 6';
				}
				else
				if ($ruleName['order_level'] == 'level_7') {
					$ruleArra['bt3_rule'] = ' 7';
				}
				else
				if ($ruleName['order_level'] == 'level_8') {
					$ruleArra['bt3_rule'] = ' 8';
				}
				else
				if ($ruleName['order_level'] == 'level_9') {
					$ruleArra['bt3_rule'] = ' 9';
				}
				else
				if ($ruleName['order_level'] == 'level_10') {
					$ruleArra['bt3_rule'] = ' 10';
				}

				if ($ruleName['type'] == 'buy') {
					$buySumbt3+= 1;
					$finalArrRuleBuybt3[] = $ruleArra['bt3_rule'];
				}

				if ($ruleName['type'] == 'sell') {
					$sellSumbt3+= 1;
					$finalArrRuleSellbt3[] = $ruleArra['bt3_rule'];
				}
			}

			if ($ruleName['trigger_type'] == 'barrier_trigger_simulator') {
				if ($ruleName['rule_number'] == 'level_1') {
					$ruleArra['bts_rule'] = ' 1';
				}
				else
				if ($ruleName['rule_number'] == 'level_2') {
					$ruleArra['bts_rule'] = ' 2';
				}
				else
				if ($ruleName['rule_number'] == 'level_3') {
					$ruleArra['bts_rule'] = ' 3';
				}
				else
				if ($ruleName['rule_number'] == 'level_4') {
					$ruleArra['bts_rule'] = ' 4';
				}
				else
				if ($ruleName['rule_number'] == 'level_5') {
					$ruleArra['bts_rule'] = ' 5';
				}
				else
				if ($ruleName['rule_number'] == 'level_6') {
					$ruleArra['bts_rule'] = ' 6';
				}
				else
				if ($ruleName['rule_number'] == 'level_7') {
					$ruleArra['bts_rule'] = ' 7';
				}
				else
				if ($ruleName['rule_number'] == 'level_8') {
					$ruleArra['bts_rule'] = ' 8';
				}
				else
				if ($ruleName['rule_number'] == 'level_9') {
					$ruleArra['bts_rule'] = ' 9';
				}
				else
				if ($ruleName['rule_number'] == 'level_10') {
					$ruleArra['bts_rule'] = ' 10';
				}

				if ($ruleName['type'] == 'buy') {
					$buySum_bts+= 1;
					$finalArrRuleBuy_bts[] = $ruleArra['bts_rule'];
				}

				if ($ruleName['type'] == 'sell') {
					$sellSum_bts+= 1;
					$finalArrRuleSell_bts[] = $ruleArra['bts_rule'];
				}
			}
		}

		// Percentile Trigger
		$finalArrRuleBuy_pArr = implode(',', $finalArrRuleBuy_p);
		$finalArrRuleSell_pArr = implode(',', $finalArrRuleSell_p);
		$buySum_p = ($buySum_p != '') ? ($buySum_p) : 0;
		$sellSum_p = ($sellSum_p != '') ? ($sellSum_p) : 0;
		$rulesBuy_pArr = $finalArrRuleBuy_pArr;
		$rulesSell_pArr = $finalArrRuleSell_pArr;

		// Barrier  Trigger
		$finalArrRuleBuy_bArr = implode(',', $finalArrRuleBuy_b);
		$finalArrRuleSell_bArr = implode(',', $finalArrRuleSell_b);
		$buySum_b = ($buySum_b != '') ? ($buySum_b) : 0;
		$sellSum_b = ($sellSum_b != '') ? ($sellSum_b) : 0;
		$rulesBuy_bArr = $finalArrRuleBuy_bArr;
		$rulesSell_bArr = $finalArrRuleSell_bArr;

		// Percentile Trigger
		$finalArrRuleBuybt3Arr = implode(',', $finalArrRuleBuybt3);
		$finalArrRuleSellbt3Arr = implode(',', $finalArrRuleSellbt3);
		$buySum_bt3 = ($buySum_bt3 != '') ? ($buySum_bt3) : 0;
		$sellSum_bt3 = ($sellSum_bt3 != '') ? ($sellSum_bt3) : 0;
		$rulesBuy_bt3Arr = $finalArrRuleBuybt3Arr;
		$rulesSell_bt3Arr = $finalArrRuleSellbt3Arr;

		// Percentile Trigger
		$finalArrRuleBuy_btsArr = implode(',', $finalArrRuleBuy_bts);
		$finalArrRuleSell_btsArr = implode(',', $finalArrRuleSell_bts);
		$buySum_bts = ($buySum_bts != '') ? ($buySum_bts) : 0;
		$sellSum_bts = ($sellSum_bts != '') ? ($sellSum_bts) : 0;
		$rulesBuy_btsArr = $finalArrRuleBuy_btsArr;
		$rulesSell_btsArr = $finalArrRuleSell_btsArr;

		// ****  Insert The data IntoDb **** //
		$ins_data = array(
			'coin' => $this->db->escape_str(trim($global_symbol)) ,
			'market_depth_quantity' => $this->db->escape_str(trim($market_depth_quantityNC)) ,
			'market_depth_ask' => $this->db->escape_str(trim($market_depth_askNwNC)) ,
			'last_qty_time_ago' => $this->db->escape_str(trim($last_qty_time_agoNewCon)) ,
			'last_200_time_ago' => $this->db->escape_str(trim($last_200_time_agoNewCon)) ,
			'black_wall_pressure' => $this->db->escape_str(trim($black_wall_pressureCon)) ,
			'yellow_wall_pressure' => $this->db->escape_str(trim($yellow_wall_pressureCon)) ,
			'pressure_diff' => $this->db->escape_str(trim($pressure_diffCon)) ,
			'seven_level_depth' => $this->db->escape_str(trim($seven_level_depthCon)) ,
			'last_qty_buy_vs_sell' => $this->db->escape_str(trim($valueLast_qty_buy_vs_sellNewOne)) ,
			'last_200_buy_vs_sell' => $this->db->escape_str(trim($valueLast_200_buy_vs_sellNewone)) ,
			'last_qty_time_ago_15' => $this->db->escape_str(trim($last_qty_timeAgo_15NWA_AVG)) ,
			'last_qty_buy_vs_sell_15' => $this->db->escape_str(trim($last_qty_buyVsell_15NWA_AVG)) ,
			'buyers_fifteen' => $this->db->escape_str(trim($buyers_fifteenOrg)) ,
			'sellers_fifteen' => $this->db->escape_str(trim(($sellers_fifteenOrg))) ,
			'sellers_buyers_per_fifteen' => $this->db->escape_str(trim($sellers_buyers_per_15NW_AVG)) ,
			'bid_contracts' => $this->db->escape_str(trim($bid_contractsNW_AVG)) ,
			'ask_contract' => $this->db->escape_str(trim($ask_contractNW_AVG)) ,
			'ask' => $this->db->escape_str(trim($askNW_AVG)) ,
			'bid' => $this->db->escape_str(trim($bidNW_AVG)) ,
			'buy' => $this->db->escape_str(trim($buyNW_AVG)) ,
			'sell' => $this->db->escape_str(trim($sellNW_AVG)) ,
			'buyers' => $this->db->escape_str(trim($buyersNwCon)) ,
			'sellers' => $this->db->escape_str(trim($sellersNwCon)) ,
			'great_wall_price' => $this->db->escape_str(trim($great_wall_priceArrCon)) ,
			'current_market_value' => $this->db->escape_str(trim($current_market_valueCon)) ,
			'score' => $this->db->escape_str(trim($scoreCon)) ,
			'datetime_user_friend' => $this->db->escape_str(trim($start_date)) ,
			'datetime_mongo' => $this->mongo_db->converToMongodttime($start_date) ,
			'modified_date' => $this->db->escape_str(trim($start_date1)) ,
			'straightline' => $this->db->escape_str(trim($straightline)) ,
			'buySum_p' => $this->db->escape_str(trim($buySum_p)) ,
			'sellSum_p' => $this->db->escape_str(trim($sellSum_p)) ,
			'rulesBuy_pArr' => $this->db->escape_str(trim($rulesBuy_pArr)) ,
			'rulesSell_pArr' => $this->db->escape_str(trim($rulesSell_pArr)) ,
			'buySum_b' => $this->db->escape_str(trim($buySum_b)) ,
			'sellSum_b' => $this->db->escape_str(trim($sellSum_b)) ,
			'rulesBuy_bArr' => $this->db->escape_str(trim($rulesBuy_bArr)) ,
			'rulesSell_bArr' => $this->db->escape_str(trim($rulesSell_bArr)) ,
			'buySum_bt3' => $this->db->escape_str(trim($buySum_bt3)) ,
			'sellSum_bt3' => $this->db->escape_str(trim($sellSum_bt3)) ,
			'rulesBuy_bt3Arr' => $this->db->escape_str(trim($rulesBuy_bt3Arr)) ,
			'rulesSell_bt3Arr' => $this->db->escape_str(trim($rulesSell_bt3Arr)) ,
			'buySum_bts' => $this->db->escape_str(trim($buySum_bts)) ,
			'sellSum_bts' => $this->db->escape_str(trim($sellSum_bts)) ,
			'rulesBuy_btsArr' => $this->db->escape_str(trim($rulesBuy_btsArr)) ,
			'rulesSell_btsArr' => $this->db->escape_str(trim($rulesSell_btsArr)) ,
			'black_wall_percentile' => $this->db->escape_str(trim($black_wall_percentile)) ,
			'sevenlevel_percentile' => $this->db->escape_str(trim($sevenlevel_percentile)) ,
			'rolling_five_bid_percentile' => $this->db->escape_str(trim($rolling_five_bid_percentile)) ,
			'rolling_five_ask_percentile' => $this->db->escape_str(trim($rolling_five_ask_percentile)) ,
			'five_buy_sell_percentile' => $this->db->escape_str(trim($five_buy_sell_percentile)) ,
			'fifteen_buy_sell_percentile' => $this->db->escape_str(trim($fifteen_buy_sell_percentile)) ,
			'last_qty_buy_sell_percentile' => $this->db->escape_str(trim($last_qty_buy_sell_percentile)) ,
			'last_qty_time_percentile' => $this->db->escape_str(trim($last_qty_time_percentile)) ,
			'sellers_buyers_per_t4cot' => $this->db->escape_str(trim($sellers_buyers_per_t4cotNwAVG)) ,
		);
		echo "**************************Insert Record ***********************************";
		echo "<prE>";
		print_r($ins_data);
		exit;
		// $insert =  $this->mongo_db->insert('highchart_report', $ins_data);
		return true;
	} //testChartDataOneMinutCron
	public function getChartDataReportCandle($global_symbol, $start_date, $end_date)
	{
		$created_datetime = date('Y-m-d G:i:s', strtotime($start_date));
		$orig_date = new DateTime($created_datetime);
		$orig_date = $orig_date->getTimestamp();

		$start_date1 = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
		$created_dateEnd = date('Y-m-d G:i:s', strtotime($end_date));
		$orig_date22 = new DateTime($created_dateEnd);
		$orig_date22 = $orig_date22->getTimestamp();
		$end_date2 = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
		$search_array = array();
		$search_array['coin'] = $global_symbol;
		$search_array['datetime_mongo'] = array(
			'$gte' => $start_date1,
			'$lte' => $end_date2
		);
		$connetct = $this->mongo_db->customQuery();
		$limit = 300000;
		$qr = array(
			'sort' => array(
				'modified_date' => - 1
			) ,
			'limit' => $limit
		);
		$cursor = $connetct->highchart_report->find($search_array, $qr);
		$resArray = iterator_to_array($cursor);
		//echo '<Pre>';  print_r($search_array); exit;	
		
		return $resArray;
	} //getChartDataReportCandle
	public function getChartDataHours($global_symbol, $time, $start_date, $end_date, $mutiply_no_score, $mutiply_no_market, $totalHours, $minus_no_score, $price_format, $time_cot, $m_buyers_sellers, $s_b_15, $l_quantity_15, $rule_buy_sell, $ask_buy_for, $bid_sell_for, $trigger)
	{
		if ($time == 'minut') {
			$created_datetime = date('Y-m-d G:i:s', strtotime($start_date));
			$orig_date = new DateTime($created_datetime);
			$orig_date = $orig_date->getTimestamp();
			$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
			$created_datetime22 = date('Y-m-d G:i:s', strtotime($end_date));
			$orig_date22 = new DateTime($created_datetime22);
			$orig_date22 = $orig_date22->getTimestamp();
			$orig_date22 = $orig_date22 + 60;
			$end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
		}
		else
		if ($time == 'hour') {
			$created_datetime = date('Y-m-d G:i:s', strtotime($start_date));
			$orig_date = new DateTime($created_datetime);
			$orig_date = $orig_date->getTimestamp();
			$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
			$created_datetime22 = date('Y-m-d G:i:s', strtotime($end_date));
			$orig_date22 = new DateTime($created_datetime22);
			$orig_date22 = $orig_date22->getTimestamp();
			$orig_date22 = $orig_date22 + 3600;
			$end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
		}

		$search_array = array();
		$search_array['coin'] = $global_symbol;
		$order_type = $session_post_data['filter_type'];
		$search_array['modified_date'] = array(
			'$gte' => $start_date,
			'$lte' => $end_date
		);
		$connetct = $this->mongo_db->customQuery();
		$limit = 100000;
		$qr = array(
			'skip' => $skip,
			'sort' => array(
				'modified_date' => 1
			) ,
			'limit' => $limit
		);
		$cursor = $connetct->coin_meta_history->find($search_array, $qr);
		$resArray = iterator_to_array($cursor);
		$count = count($resArray);
		$i = 1;
		$condVar = $count;
		foreach($resArray as $key => $valueArr) {
			$returArr = array();
			if (!empty($valueArr)) {
				$last_qty_time_agoa = ($valueArr->last_qty_time_ago) ? $valueArr->last_qty_time_ago : 0;
				$last_200_time_agoa = ($valueArr->last_200_time_ago) ? $valueArr->last_200_time_ago : 0;
				$last_qty_time_agoNewa = str_replace(" min ago", "", $last_qty_time_agoa);
				$last_200_time_agoNewa = str_replace(" min ago", "", $last_200_time_agoa);
				$last_qty_timeAgo_15 = str_replace(" min ago", "", $last_qty_time_ago_15a);
				$black_wall_pressurea = $valueArr->black_wall_pressure;
				$yellow_wall_pressurea = $valueArr->yellow_wall_pressure;
				$pressure_diffa = $valueArr->pressure_diff;
				$seven_level_deptha = $valueArr->seven_level_depth;
				$scorea = $valueArr->score;
				$great_wall_priceArra = $valueArr->great_wall_price;
				$current_market_valuea = $valueArr->current_market_value;
				$datetimea = $valueArr->modified_date;
				$market_depth_quantity = $valueArr->market_depth_quantity;
				$market_depth_ask = $valueArr->market_depth_ask;
				$last_qty_buy_vs_sell = $valueArr->last_qty_buy_vs_sell;
				$last_200_buy_vs_sell = $valueArr->last_200_buy_vs_sell;
				$buyers = $valueArr->buyers;
				$sellers = $valueArr->sellers;
				$last_qty_time_ago_15a = ($valueArr->last_qty_time_ago_15) ? $valueArr->last_qty_time_ago_15 : 0;
				$last_qty_buyVsell_15a = $valueArr->last_qty_buy_vs_sell_15;
				$buyers_fifteenA = $valueArr->buyers_fifteen;
				$sellers_fifteenA = $valueArr->sellers_fifteen;
				$sellers_buyers_per_15 = $valueArr->sellers_buyers_per_fifteen;
				$bid_contracts = $valueArr->bid_contracts;
				$ask_contract = $valueArr->ask_contract;
				$ask = $valueArr->ask;
				$bid = $valueArr->bid;
				$buy = $valueArr->buy;
				$sell = $valueArr->sell;
				$askNW+= $ask;
				$bidNW+= $bid;
				$buyNW+= $buy;
				$sellNW+= $sell;
				$last_qty_timeAgo_15NW+= $last_qty_timeAgo_15;
				$last_qty_buyVsell_15NW+= $last_qty_buyVsell_15a;
				$market_depth_quantityNw+= $market_depth_quantity;
				$market_depth_askNw+= $market_depth_ask;
				$last_qty_time_agoNew+= $last_qty_time_agoNewa;
				$last_200_time_agoNew+= $last_200_time_agoNewa;
				$black_wall_pressure+= $black_wall_pressurea;
				$yellow_wall_pressure+= $yellow_wall_pressurea;
				$pressure_diff+= $pressure_diffa;
				$seven_level_depth+= $seven_level_deptha;
				$score+= $scorea;
				$great_wall_priceArr+= num($great_wall_priceArra);
				$current_market_value+= num($current_market_valuea);
				$last_qty_buy_vs_sellNw+= num($last_qty_buy_vs_sell);
				$last_200_buy_vs_sellNw+= num($last_200_buy_vs_sell);
				$buyersNw+= num($buyers);
				$sellersNw+= num($sellers);
				$buyers_fifteenANW+= num($buyers_fifteenA);
				$sellers_fifteenANW+= num($sellers_fifteenA);
				$sellers_buyers_per_15NW+= num($sellers_buyers_per_15);
				$bid_contractsNW+= num($bid_contracts);
				$ask_contractNW+= num($ask_contract);
				$datetime = $datetimea;
				$condVar = $condVar;
				if ($i % $condVar == 0) {
					$last_qty_time_agoNewCon = $last_qty_time_agoNew / $condVar;
					$last_200_time_agoNewCon = $last_200_time_agoNew / $condVar;
					$black_wall_pressureCon = $black_wall_pressure / $condVar;
					$yellow_wall_pressureCon = $yellow_wall_pressure / $condVar;
					$pressure_diffCon = $pressure_diff / $condVar;
					$seven_level_depthCon = $seven_level_depth / $condVar;
					$scoreCon = $score / $condVar;
					$great_wall_priceArrCon = $great_wall_priceArr / $condVar;
					$current_market_valueCon = $current_market_value / $condVar;
					$last_qty_buy_vs_sellCon = $last_qty_buy_vs_sellNw / $condVar;
					$last_200_buy_vs_sellCon = $last_200_buy_vs_sellNw / $condVar;
					$buyersNwConOrg = $buyersNw / $condVar;
					$sellersNwConOrg = $sellersNw / $condVar;
					$buyersNwCon = $buyersNwConOrg / $m_buyers_sellers;
					$sellersNwCon = $sellersNwConOrg / $m_buyers_sellers;
					$great_wall_priceArrCon = num($great_wall_priceArrCon);
					$current_market_valueCon = num($current_market_valueCon);
					$datetimeCon = $datetime;
					$market_depth_quantityNC = $market_depth_quantityNw / $condVar;
					$market_depth_quantityNCA = $market_depth_quantityNC / $price_format;
					$market_depth_askNwNC = $market_depth_askNw / $condVar;
					$market_depth_askNwNCA = $market_depth_askNwNC / $price_format;
					$market_depth_askNwNCA = $market_depth_askNwNCA;
					$mutiply_no_score = num($mutiply_no_score);
					$mutiply_no_market = num($mutiply_no_market);
					$last_qty_timeAgo_15NWA_AVG = $last_qty_timeAgo_15NW / $condVar;
					$last_qty_buyVsell_15NWA_AVG = $last_qty_buyVsell_15NW / $condVar;
					$last_qty_timeAgo_15NWA = $last_qty_timeAgo_15NWA_AVG / $l_quantity_15;
					$last_qty_buyVsell_15NWA = $last_qty_buyVsell_15NWA_AVG / $l_quantity_15;
					$buyers_fifteenOrg = $buyers_fifteenANW / $condVar;
					$sellers_fifteenOrg = $sellers_fifteenANW / $condVar;
					$buyers_fifteenANW_AVG = $buyers_fifteenOrg / $s_b_15;
					$sellers_fifteenANW_AVG = $sellers_fifteenOrg / $s_b_15;
					$sellers_buyers_per_15NW_AVG = $sellers_buyers_per_15NW / $condVar;
					$bid_contractsNW_AVG = $bid_contractsNW / $condVar;
					$ask_contractNW_AVG = $ask_contractNW / $condVar;
					$bid_contractsNW_AVGA = $bid_contractsNW_AVG / $m_buyers_sellers;
					$ask_contractNW_AVGA = $ask_contractNW_AVG / $m_buyers_sellers;
					$askNW_AVG_T = $askNW / $condVar;
					$bidNW_AVG_T = $bidNW / $condVar;
					$buyNW_AVG_T = $buyNW / $condVar;
					$sellNW_AVG_T = $sellNW / $condVar;
					$askNW_AVG = $askNW_AVG_T / $ask_buy_for;
					$bidNW_AVG = $bidNW_AVG_T / $bid_sell_for;
					$buyNW_AVG = $buyNW_AVG_T / $ask_buy_for;
					$sellNW_AVG = $sellNW_AVG_T / $bid_sell_for;
					$bigBuyDivideSell = $ask_contractNW_AVGA / $bid_contractsNW_AVGA;
					$returArr['market_depth_quantity'] = !empty($market_depth_quantityNCA) ? round($market_depth_quantityNCA, 2) : 0;
					$returArr['market_depth_ask'] = !empty($market_depth_askNwNCA) ? round($market_depth_askNwNCA, 2) : 0;
					$returArr['last_qty_time_agoNew'] = !empty($last_qty_time_agoNewCon) ? round($last_qty_time_agoNewCon, 2) : 0;
					$returArr['last_200_time_agoNew'] = !empty($last_200_time_agoNewCon) ? round($last_200_time_agoNewCon, 2) : 0;
					$returArr['black_wall_pressure'] = !empty($black_wall_pressureCon) ? round($black_wall_pressureCon, 2) : 0;
					$returArr['yellow_wall_pressure'] = !empty($yellow_wall_pressureCon) ? round($yellow_wall_pressureCon, 2) : 0;
					$returArr['pressure_diff'] = !empty($pressure_diffCon) ? round($pressure_diffCon, 2) : 0;
					$returArr['seven_level_depth'] = !empty($seven_level_depthCon) ? round($seven_level_depthCon, 2) : 0;
					$valueLast_qty_buy_vs_sell = ($last_qty_buy_vs_sellCon && $last_qty_buy_vs_sellCon > 10) ? 10 : $last_qty_buy_vs_sellCon;
					$valueLast_qty_buy_vs_sellNewOne = ($valueLast_qty_buy_vs_sell && $valueLast_qty_buy_vs_sell < - 10) ? -10 : $valueLast_qty_buy_vs_sell;
					$valueLast_200_buy_vs_sell = ($last_200_buy_vs_sellCon && $last_200_buy_vs_sellCon > 10) ? 10 : $last_200_buy_vs_sellCon;
					$valueLast_200_buy_vs_sellNewone = ($valueLast_200_buy_vs_sell && $valueLast_200_buy_vs_sell < - 10) ? -10 : $valueLast_200_buy_vs_sell;
					$returArr['last_qty_buy_vs_sell'] = !empty($valueLast_qty_buy_vs_sellNewOne) ? round($valueLast_qty_buy_vs_sellNewOne, 2) : 0;
					$returArr['last_200_buy_vs_sell'] = !empty($valueLast_200_buy_vs_sellNewone) ? round($valueLast_200_buy_vs_sellNewone, 2) : 0;
					$returArr['last_qty_time_ago_15'] = !empty($last_qty_timeAgo_15NWA) ? round($last_qty_timeAgo_15NWA, 2) : 0;
					$returArr['last_qty_buy_vs_sell_15'] = !empty($last_qty_buyVsell_15NWA) ? round($last_qty_buyVsell_15NWA, 2) : 0;
					$returArr['buyers_fifteen'] = !empty($buyers_fifteenANW_AVG) ? round($buyers_fifteenANW_AVG, 2) : 0;
					$returArr['sellers_fifteen'] = !empty($sellers_fifteenANW_AVG) ? round($sellers_fifteenANW_AVG, 2) : 0;
					$returArr['sellers_buyers_per_fifteen'] = !empty($sellers_buyers_per_15NW_AVG) ? round($sellers_buyers_per_15NW_AVG, 2) : 0;
					$returArr['bid_contracts'] = !empty($bid_contractsNW_AVGA) ? round($bid_contractsNW_AVGA, 2) : 0;
					$returArr['ask_contract'] = !empty($ask_contractNW_AVGA) ? round($ask_contractNW_AVGA, 2) : 0;
					$returArr['ask'] = !empty($askNW_AVG) ? round($askNW_AVG, 2) : 0;
					$returArr['bid'] = !empty($bidNW_AVG) ? round($bidNW_AVG, 2) : 0;
					$returArr['buy'] = !empty($buyNW_AVG) ? round($buyNW_AVG, 2) : 0;
					$returArr['sell'] = !empty($sellNW_AVG) ? round($sellNW_AVG, 2) : 0;
					$time_cotbuyers = $buyersNwCon / $time_cot;
					$time_cotbuyers = num($time_cotbuyers);
					$time_cotsellere = $sellersNwCon / $time_cot;
					$time_cotsellere = num($time_cotsellere);
					$returArr['buyers'] = !empty($time_cotbuyers) ? round($time_cotbuyers, 2) : 0;
					$returArr['sellers'] = !empty($time_cotsellere) ? round($time_cotsellere, 2) : 0;
					$great_wall_priceArrConAsd = $mutiply_no_score * $great_wall_priceArrCon;
					$great_wall_priceArrConAsd = num($great_wall_priceArrConAsd);
					$returArr['great_wall_priceArr'] = !empty($great_wall_priceArrConAsd) ? round($great_wall_priceArrConAsd, 2) : 0;
					$current_market_valueAsd = $mutiply_no_market * $current_market_valueCon;
					$current_market_valueAsd = num($current_market_valueAsd);
					$returArr['current_market_value'] = ($minus_no_score != '') ? num($current_market_valueAsd) - num($minus_no_score) : num($current_market_valueAsd);
					$returArr['score'] = !empty($scoreCon) ? round($scoreCon, 2) : 0;
					$returArr['datetime'] = !empty($datetimeCon) ? round($datetimeCon, 2) : 0;
					$returArr['straightline'] = 0;
					$fullarray = $returArr;
				}
			}

			$i++;
		} // Foreach
		if ($trigger != '') {
			$search_array = array();
			$search_array['coin_symbol'] = $global_symbol;
			if ($trigger != 'all') {
				$search_array['trigger_type'] = $trigger;
			}

			$search_array['created_date'] = array(
				'$gte' => $start_date,
				'$lte' => $end_date
			);
			$connetct = $this->mongo_db->customQuery();
			$limit = 100;
			$qr = array(
				'skip' => $skip,
				'sort' => array(
					'created_date' => 1
				) ,
				'limit' => $limit
			);
			$cursor = $connetct->barrier_trigger_true_rules_collection->find($search_array, $qr);
			$resArrayColle = iterator_to_array($cursor);
			$ruleArra = array();
			$buySum = 0;
			$sellSum = 0;
			foreach($resArrayColle as $ruleName) {
				if ($ruleName['rule_number'] == 'rule_no_1') {
					$ruleArra['rule'] = 'Rule 1';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_2') {
					$ruleArra['rule'] = 'Rule 2';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_3') {
					$ruleArra['rule'] = 'Rule 3';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_4') {
					$ruleArra['rule'] = 'Rule 4';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_5') {
					$ruleArra['rule'] = 'Rule 5';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_6') {
					$ruleArra['rule'] = 'Rule 6';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_7') {
					$ruleArra['rule'] = 'Rule 7';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_8') {
					$ruleArra['rule'] = 'Rule 8';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_9') {
					$ruleArra['rule'] = 'Rule 9';
				}
				else
				if ($ruleName['rule_number'] == 'rule_no_10') {
					$ruleArra['rule'] = 'Rule 10';
				}

				if ($ruleName['type'] == 'buy') {
					$buySum+= 1;
					$finalArrRuleBuy[] = $ruleArra['rule'];
				}
				else
				if ($ruleName['type'] == 'sell') {
					$sellSum+= 1;
					$finalArrRuleSell[] = $ruleArra['rule'];
				}

				$ruleArra['type'] = $ruleName['type'];
			}

			$explodArrBuy = implode('-', $finalArrRuleBuy);
			$explodArrSell = implode('-', $finalArrRuleSell);
			$fullarray['buySum'] = ($buySum != '') ? ($buySum * $rule_buy_sell) : 0;
			$fullarray['sellSum'] = ($sellSum != '') ? ($sellSum * $rule_buy_sell) : 0;
			$fullarray['rulesBuy'] = $explodArrBuy;
			$fullarray['rulesSell'] = $explodArrSell;
		}

		return $fullarray;
	} //getChartDataHours
	public function next_session($global_symbol, $time, $start_date, $end_date, $mutiply_no_score, $mutiply_no_market, $totalHours, $minus_no_score, $price_format, $time_cot, $m_buyers_sellers, $s_b_15, $l_quantity_15, $rule_buy_sell, $ask_buy_for, $bid_sell_for)
	{
		if ($time == 'minut') {
			$created_datetime = date('Y-m-d G:i:s', strtotime($start_date));
			$orig_date = new DateTime($created_datetime);
			$orig_date = $orig_date->getTimestamp();
			$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
			$created_datetime22 = date('Y-m-d G:i:s', strtotime($end_date));
			$orig_date22 = new DateTime($created_datetime22);
			$orig_date22 = $orig_date22->getTimestamp();
			$orig_date22 = $orig_date22 + 60;
			$end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
		}
		else
		if ($time == 'hour') {
			$created_datetime = date('Y-m-d G:i:s', strtotime($start_date));
			$orig_date = new DateTime($created_datetime);
			$orig_date = $orig_date->getTimestamp();
			$start_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
			$created_datetime22 = date('Y-m-d G:i:s', strtotime($end_date));
			$orig_date22 = new DateTime($created_datetime22);
			$orig_date22 = $orig_date22->getTimestamp();
			$orig_date22 = $orig_date22 + 3600;
			$end_date = new MongoDB\BSON\UTCDateTime($orig_date22 * 1000);
		}

		$search_array = array();
		$search_array['coin'] = $global_symbol;
		$order_type = $session_post_data['filter_type'];
		$search_array['modified_date'] = array(
			'$gte' => $start_date,
			'$lte' => $end_date
		);
		$connetct = $this->mongo_db->customQuery();
		$limit = 100000;
		$qr = array(
			'skip' => $skip,
			'sort' => array(
				'modified_date' => 1
			) ,
			'limit' => $limit
		);
		$cursor = $connetct->coin_meta_history->find($search_array, $qr);
		$resArray = iterator_to_array($cursor);
		$count = count($resArray);
		$i = 1;
		$condVar = $count;
		foreach($resArray as $key => $valueArr) {
			$returArr = array();
			if (!empty($valueArr)) {
				$last_qty_time_agoa = ($valueArr->last_qty_time_ago) ? $valueArr->last_qty_time_ago : 0;
				$last_200_time_agoa = ($valueArr->last_200_time_ago) ? $valueArr->last_200_time_ago : 0;
				$last_qty_time_agoNewa = str_replace(" min ago", "", $last_qty_time_agoa);
				$last_200_time_agoNewa = str_replace(" min ago", "", $last_200_time_agoa);
				$last_qty_timeAgo_15 = str_replace(" min ago", "", $last_qty_time_ago_15a);
				$black_wall_pressurea = $valueArr->black_wall_pressure;
				$yellow_wall_pressurea = $valueArr->yellow_wall_pressure;
				$pressure_diffa = $valueArr->pressure_diff;
				$seven_level_deptha = $valueArr->seven_level_depth;
				$scorea = $valueArr->score;
				$great_wall_priceArra = $valueArr->great_wall_price;
				$current_market_valuea = $valueArr->current_market_value;
				$datetimea = $valueArr->modified_date;
				$market_depth_quantity = $valueArr->market_depth_quantity;
				$market_depth_ask = $valueArr->market_depth_ask;
				$last_qty_buy_vs_sell = $valueArr->last_qty_buy_vs_sell;
				$last_200_buy_vs_sell = $valueArr->last_200_buy_vs_sell;
				$buyers = $valueArr->buyers;
				$sellers = $valueArr->sellers;
				$last_qty_time_ago_15a = ($valueArr->last_qty_time_ago_15) ? $valueArr->last_qty_time_ago_15 : 0;
				$last_qty_buyVsell_15a = $valueArr->last_qty_buy_vs_sell_15;
				$buyers_fifteenA = $valueArr->buyers_fifteen;
				$sellers_fifteenA = $valueArr->sellers_fifteen;
				$sellers_buyers_per_15 = $valueArr->sellers_buyers_per_fifteen;
				$bid_contracts = $valueArr->bid_contracts;
				$ask_contract = $valueArr->ask_contract;
				$ask = $valueArr->ask;
				$bid = $valueArr->bid;
				$buy = $valueArr->buy;
				$sell = $valueArr->sell;
				$askNW+= $ask;
				$bidNW+= $bid;
				$buyNW+= $buy;
				$sellNW+= $sell;
				$last_qty_timeAgo_15NW+= $last_qty_timeAgo_15;
				$last_qty_buyVsell_15NW+= $last_qty_buyVsell_15a;
				$market_depth_quantityNw+= $market_depth_quantity;
				$market_depth_askNw+= $market_depth_ask;
				$last_qty_time_agoNew+= $last_qty_time_agoNewa;
				$last_200_time_agoNew+= $last_200_time_agoNewa;
				$black_wall_pressure+= $black_wall_pressurea;
				$yellow_wall_pressure+= $yellow_wall_pressurea;
				$pressure_diff+= $pressure_diffa;
				$seven_level_depth+= $seven_level_deptha;
				$score+= $scorea;
				$great_wall_priceArr+= num($great_wall_priceArra);
				$current_market_value+= num($current_market_valuea);
				$last_qty_buy_vs_sellNw+= num($last_qty_buy_vs_sell);
				$last_200_buy_vs_sellNw+= num($last_200_buy_vs_sell);
				$buyersNw+= num($buyers);
				$sellersNw+= num($sellers);
				$buyers_fifteenANW+= num($buyers_fifteenA);
				$sellers_fifteenANW+= num($sellers_fifteenA);
				$sellers_buyers_per_15NW+= num($sellers_buyers_per_15);
				$bid_contractsNW+= num($bid_contracts);
				$ask_contractNW+= num($ask_contract);
				$datetime = $datetimea;
				$condVar = $condVar;
				if ($i % $condVar == 0) {
					$last_qty_time_agoNewCon = $last_qty_time_agoNew / $condVar;
					$last_200_time_agoNewCon = $last_200_time_agoNew / $condVar;
					$black_wall_pressureCon = $black_wall_pressure / $condVar;
					$yellow_wall_pressureCon = $yellow_wall_pressure / $condVar;
					$pressure_diffCon = $pressure_diff / $condVar;
					$seven_level_depthCon = $seven_level_depth / $condVar;
					$scoreCon = $score / $condVar;
					$great_wall_priceArrCon = $great_wall_priceArr / $condVar;
					$current_market_valueCon = $current_market_value / $condVar;
					$last_qty_buy_vs_sellCon = $last_qty_buy_vs_sellNw / $condVar;
					$last_200_buy_vs_sellCon = $last_200_buy_vs_sellNw / $condVar;
					$buyersNwConOrg = $buyersNw / $condVar;
					$sellersNwConOrg = $sellersNw / $condVar;
					$buyersNwCon = $buyersNwConOrg / $m_buyers_sellers;
					$sellersNwCon = $sellersNwConOrg / $m_buyers_sellers;
					$great_wall_priceArrCon = num($great_wall_priceArrCon);
					$current_market_valueCon = num($current_market_valueCon);
					$datetimeCon = $datetime;
					$market_depth_quantityNC = $market_depth_quantityNw / $condVar;
					$market_depth_quantityNCA = $market_depth_quantityNC;
					$market_depth_askNwNC = $market_depth_askNw / $condVar;
					$market_depth_askNwNCA = $market_depth_askNwNC;
					$mutiply_no_score = num($mutiply_no_score);
					$mutiply_no_market = num($mutiply_no_market);
					$last_qty_timeAgo_15NWA = $last_qty_timeAgo_15NW / $condVar;
					$last_qty_buyVsell_15NWA = $last_qty_buyVsell_15NW / $condVar;
					$buyers_fifteenOrg = $buyers_fifteenANW / $condVar;
					$sellers_fifteenOrg = $sellers_fifteenANW / $condVar;
					$buyers_fifteenANW_AVG = $buyers_fifteenOrg / $s_b_15;
					$sellers_fifteenANW_AVG = $sellers_fifteenOrg / $s_b_15;
					$sellers_buyers_per_15NW_AVG = $sellers_buyers_per_15NW / $condVar;
					$bid_contractsNW_AVG = $bid_contractsNW / $condVar;
					$ask_contractNW_AVG = $ask_contractNW / $condVar;
					$bid_contractsNW_AVGA = $bid_contractsNW_AVG / $m_buyers_sellers;
					$ask_contractNW_AVGA = $ask_contractNW_AVG / $m_buyers_sellers;
					$askNW_AVG_T = $askNW / $condVar;
					$bidNW_AVG_T = $bidNW / $condVar;
					$buyNW_AVG_T = $buyNW / $condVar;
					$sellNW_AVG_T = $sellNW / $condVar;
					$askNW_AVG = $askNW_AVG_T / $ask_buy_for;
					$bidNW_AVG = $bidNW_AVG_T / $bid_sell_for;
					$buyNW_AVG = $buyNW_AVG_T / $ask_buy_for;
					$sellNW_AVG = $sellNW_AVG_T / $bid_sell_for;
					$returArr['market_depth_quantity'] = !empty($market_depth_quantityNCA) ? round($market_depth_quantityNCA, 2) : 0;
					$returArr['market_depth_ask'] = !empty($market_depth_askNwNCA) ? round($market_depth_askNwNCA, 2) : 0;
					$returArr['last_qty_time_agoNew'] = !empty($last_qty_time_agoNewCon) ? round($last_qty_time_agoNewCon, 2) : 0;
					$returArr['last_200_time_agoNew'] = !empty($last_200_time_agoNewCon) ? round($last_200_time_agoNewCon, 2) : 0;
					$returArr['black_wall_pressure'] = !empty($black_wall_pressureCon) ? round($black_wall_pressureCon, 2) : 0;
					$returArr['yellow_wall_pressure'] = !empty($yellow_wall_pressureCon) ? round($yellow_wall_pressureCon, 2) : 0;
					$returArr['pressure_diff'] = !empty($pressure_diffCon) ? round($pressure_diffCon, 2) : 0;
					$returArr['seven_level_depth'] = !empty($seven_level_depthCon) ? round($seven_level_depthCon, 2) : 0;
					$valueLast_qty_buy_vs_sell = ($last_qty_buy_vs_sellCon && $last_qty_buy_vs_sellCon > 10) ? 10 : $last_qty_buy_vs_sellCon;
					$valueLast_qty_buy_vs_sellNewOne = ($valueLast_qty_buy_vs_sell && $valueLast_qty_buy_vs_sell < - 10) ? -10 : $valueLast_qty_buy_vs_sell;
					$valueLast_200_buy_vs_sell = ($last_200_buy_vs_sellCon && $last_200_buy_vs_sellCon > 10) ? 10 : $last_200_buy_vs_sellCon;
					$valueLast_200_buy_vs_sellNewone = ($valueLast_200_buy_vs_sell && $valueLast_200_buy_vs_sell < - 10) ? -10 : $valueLast_200_buy_vs_sell;
					$returArr['last_qty_buy_vs_sell'] = !empty($valueLast_qty_buy_vs_sellNewOne) ? round($valueLast_qty_buy_vs_sellNewOne, 2) : 0;
					$returArr['last_200_buy_vs_sell'] = !empty($valueLast_200_buy_vs_sellNewone) ? round($valueLast_200_buy_vs_sellNewone, 2) : 0;
					$returArr['last_qty_time_ago_15'] = !empty($last_qty_timeAgo_15NWA) ? round($last_qty_timeAgo_15NWA, 2) : 0;
					$returArr['last_qty_buy_vs_sell_15'] = !empty($last_qty_buyVsell_15NWA) ? round($last_qty_buyVsell_15NWA, 2) : 0;
					$returArr['buyers_fifteen'] = !empty($buyers_fifteenANW_AVG) ? round($buyers_fifteenANW_AVG, 2) : 0;
					$returArr['sellers_fifteen'] = !empty($sellers_fifteenANW_AVG) ? round($sellers_fifteenANW_AVG, 2) : 0;
					$returArr['sellers_buyers_per_fifteen'] = !empty($sellers_buyers_per_15NW_AVG) ? round($sellers_buyers_per_15NW_AVG, 2) : 0;
					$returArr['bid_contracts'] = !empty($bid_contractsNW_AVGA) ? round($bid_contractsNW_AVGA, 2) : 0;
					$returArr['ask_contract'] = !empty($ask_contractNW_AVGA) ? round($ask_contractNW_AVGA, 2) : 0;
					$returArr['ask'] = !empty($askNW_AVG) ? round($askNW_AVG, 2) : 0;
					$returArr['bid'] = !empty($bidNW_AVG) ? round($bidNW_AVG, 2) : 0;
					$returArr['buy'] = !empty($buyNW_AVG) ? round($buyNW_AVG, 2) : 0;
					$returArr['sell'] = !empty($sellNW_AVG) ? round($sellNW_AVG, 2) : 0;
					$time_cotbuyers = $buyersNwCon / $time_cot;
					$time_cotbuyers = num($time_cotbuyers);
					$time_cotsellere = $sellersNwCon / $time_cot;
					$time_cotsellere = num($time_cotsellere);
					$returArr['buyers'] = !empty($time_cotbuyers) ? round($time_cotbuyers, 2) : 0;
					$returArr['sellers'] = !empty($time_cotsellere) ? round($time_cotsellere, 2) : 0;
					$great_wall_priceArrConAsd = $mutiply_no_score * $great_wall_priceArrCon;
					$great_wall_priceArrConAsd = num($great_wall_priceArrConAsd);
					$returArr['great_wall_priceArr'] = !empty($great_wall_priceArrConAsd) ? round($great_wall_priceArrConAsd, 2) : 0;
					$current_market_valueAsd = $mutiply_no_market * $current_market_valueCon;
					$current_market_valueAsd = num($current_market_valueAsd);
					$returArr['current_market_value'] = ($minus_no_score != '') ? num($current_market_valueAsd) - num($minus_no_score) : num($current_market_valueAsd);
					$returArr['score'] = !empty($scoreCon) ? round($scoreCon, 2) : 0;
					$returArr['datetime'] = !empty($datetimeCon) ? round($datetimeCon, 2) : 0;
					$returArr['straightline'] = 0;
					$last_qty_time_ago = '';
					$last_200_time_ago = '';
					$last_qty_time_agoNew = '';
					$last_200_time_agoNew = '';
					$black_wall_pressure = '';
					$yellow_wall_pressure = '';
					$pressure_diff = '';
					$seven_level_depth = '';
					$score = '';
					$great_wall_priceArr = '';
					$current_market_value = '';
					$market_depth_quantity = '';
					$market_depth_ask = '';
					$last_qty_buy_vs_sell = '';
					$last_200_buy_vs_sell = '';
					$buyers = '';
					$sellers = '';
					$last_qty_buy_vs_sell_15 = '';
					$last_qty_time_ago_15 = '';
					$buyers_fifteen = '';
					$sellers_fifteen = '';
					$sellers_buyers_per_fifteen = '';
					$bid_contracts = '';
					$ask_contract = '';
					$ask = '';
					$bid = '';
					$buy = '';
					$sell = '';
					$fullarray = $returArr;
				}
			}

			$i++;
		} // Foreach
		$search_array = array();
		$search_array['coin_symbol'] = $global_symbol;
		$search_array['created_date'] = array(
			'$gte' => $start_date,
			'$lte' => $end_date
		);
		$connetct = $this->mongo_db->customQuery();
		$limit = 10;
		$qr = array(
			'skip' => $skip,
			'sort' => array(
				'created_date' => 1
			) ,
			'limit' => $limit
		);
		$cursor = $connetct->barrier_trigger_true_rules_collection->find($search_array, $qr);
		$resArrayColle = iterator_to_array($cursor);
		$ruleArra = array();
		$buySum = 0;
		$sellSum = 0;
		foreach($resArrayColle as $ruleName) {
			if ($ruleName['rule_number'] == 'rule_no_1') {
				$ruleArra['rule'] = 'Rule 1';
			}
			else
			if ($ruleName['rule_number'] == 'rule_no_2') {
				$ruleArra['rule'] = 'Rule 2';
			}
			else
			if ($ruleName['rule_number'] == 'rule_no_3') {
				$ruleArra['rule'] = 'Rule 3';
			}
			else
			if ($ruleName['rule_number'] == 'rule_no_4') {
				$ruleArra['rule'] = 'Rule 4';
			}
			else
			if ($ruleName['rule_number'] == 'rule_no_5') {
				$ruleArra['rule'] = 'Rule 5';
			}
			else
			if ($ruleName['rule_number'] == 'rule_no_6') {
				$ruleArra['rule'] = 'Rule 6';
			}
			else
			if ($ruleName['rule_number'] == 'rule_no_7') {
				$ruleArra['rule'] = 'Rule 7';
			}
			else
			if ($ruleName['rule_number'] == 'rule_no_8') {
				$ruleArra['rule'] = 'Rule 8';
			}
			else
			if ($ruleName['rule_number'] == 'rule_no_9') {
				$ruleArra['rule'] = 'Rule 9';
			}
			else
			if ($ruleName['rule_number'] == 'rule_no_10') {
				$ruleArra['rule'] = 'Rule 10';
			}

			if ($ruleName['type'] == 'buy') {
				$buySum+= 1;
				$finalArrRuleBuy[] = $ruleArra['rule'];
			}
			else
			if ($ruleName['type'] == 'sell') {
				$sellSum+= 1;
				$finalArrRuleSell[] = $ruleArra['rule'];
			}

			$ruleArra['type'] = $ruleName['type'];
		}

		$explodArrBuy = implode('-', $finalArrRuleBuy);
		$explodArrSell = implode('-', $finalArrRuleSell);
		$fullarray['buySum'] = ($buySum != '') ? ($buySum * $rule_buy_sell) : 0;
		$fullarray['sellSum'] = ($sellSum != '') ? ($sellSum * $rule_buy_sell) : 0;
		$fullarray['rulesBuy'] = $explodArrBuy;
		$fullarray['rulesSell'] = $explodArrSell;
		return $fullarray;
	} //next_session
	public function save_sesion_data($save_sesion_data, $coin)
	{
		$page_post_data = $this->session->userdata('page_post_data');
		$admin_id = $this->session->userdata('admin_id');
		$ins_data = array(
			'user_id' => $this->db->escape_str(trim($admin_id)) ,
			'coin' => $this->db->escape_str(trim($page_post_data['coin'])) ,
			'start_date' => $this->db->escape_str(trim($page_post_data['start_date'])) ,
			'trigger' => $this->db->escape_str(trim($page_post_data['trigger'])) ,
			'end_date' => $this->db->escape_str(trim($page_post_data['end_date'])) ,
			'time' => $this->db->escape_str(trim($page_post_data['time'])) ,
			'mutiply_no_market' => $this->db->escape_str(trim($page_post_data['mutiply_no_market'])) ,
			'minus_no_score' => $this->db->escape_str(trim($page_post_data['minus_no_score'])) ,
			'price_format' => $this->db->escape_str(trim($page_post_data['price_format'])) ,
			'time_cot' => $this->db->escape_str(trim($page_post_data['time_cot'])) ,
			'm_buyers_sellers' => $this->db->escape_str(trim($page_post_data['m_buyers_sellers'])) ,
			's_b_15' => $this->db->escape_str(trim($page_post_data['s_b_15'])) ,
			'l_quantity_15' => $this->db->escape_str(trim($page_post_data['l_quantity_15'])) ,
			'rule_buy_sell' => $this->db->escape_str(trim($page_post_data['rule_buy_sell'])) ,
			'ask_buy_for' => $this->db->escape_str(trim($page_post_data['ask_buy_for'])) ,
			'bid_sell_for' => $this->db->escape_str(trim($page_post_data['bid_sell_for'])) ,
			'session_name' => $this->db->escape_str(trim($save_sesion_data)) ,
			'tab_no' => $this->db->escape_str(trim($page_post_data['tab_no'])) ,
			'check0' => $this->db->escape_str(trim($page_post_data['check0'])) ,
			'check1' => $this->db->escape_str(trim($page_post_data['check1'])) ,
			'check2' => $this->db->escape_str(trim($page_post_data['check2'])) ,
			'check3' => $this->db->escape_str(trim($page_post_data['check3'])) ,
			'check4' => $this->db->escape_str(trim($page_post_data['check4'])) ,
			'check5' => $this->db->escape_str(trim($page_post_data['check5'])) ,
			'check6' => $this->db->escape_str(trim($page_post_data['check6'])) ,
			'check7' => $this->db->escape_str(trim($page_post_data['check7'])) ,
			'check8' => $this->db->escape_str(trim($page_post_data['check8'])) ,
			'check9' => $this->db->escape_str(trim($page_post_data['check9'])) ,
			'check10' => $this->db->escape_str(trim($page_post_data['check10'])) ,
			'check11' => $this->db->escape_str(trim($page_post_data['check11'])) ,
			'check12' => $this->db->escape_str(trim($page_post_data['check12'])) ,
			'check13' => $this->db->escape_str(trim($page_post_data['check13'])) ,
			'check14' => $this->db->escape_str(trim($page_post_data['check14'])) ,
			'check15' => $this->db->escape_str(trim($page_post_data['check15'])) ,
			'check16' => $this->db->escape_str(trim($page_post_data['check16'])) ,
			'check17' => $this->db->escape_str(trim($page_post_data['check17'])) ,
			'check18' => $this->db->escape_str(trim($page_post_data['check18'])) ,
			'check19' => $this->db->escape_str(trim($page_post_data['check19'])) ,
			'check20' => $this->db->escape_str(trim($page_post_data['check20'])) ,
			'check21' => $this->db->escape_str(trim($page_post_data['check21'])) ,
			'check22' => $this->db->escape_str(trim($page_post_data['check22'])) ,
			'check23' => $this->db->escape_str(trim($page_post_data['check23'])) ,
			'check24' => $this->db->escape_str(trim($page_post_data['check24'])) ,
			'check25' => $this->db->escape_str(trim($page_post_data['check25'])) ,
			'check26' => $this->db->escape_str(trim($page_post_data['check26'])) ,
			'check27' => $this->db->escape_str(trim($page_post_data['check27'])) ,
			'check28' => $this->db->escape_str(trim($page_post_data['check28'])) ,
			'check29' => $this->db->escape_str(trim($page_post_data['check29'])) ,
			'check30' => $this->db->escape_str(trim($page_post_data['check30'])) ,
			'check31' => $this->db->escape_str(trim($page_post_data['check31'])) ,
			'check32' => $this->db->escape_str(trim($page_post_data['check32'])) ,
			'check33' => $this->db->escape_str(trim($page_post_data['check33'])) ,
			'check34' => $this->db->escape_str(trim($page_post_data['check34'])) ,
			'check35' => $this->db->escape_str(trim($page_post_data['check35'])) ,
			'check36' => $this->db->escape_str(trim($page_post_data['check36'])) ,
			'check37' => $this->db->escape_str(trim($page_post_data['check37'])) ,
			'check38' => $this->db->escape_str(trim($page_post_data['check38'])) ,
			'check39' => $this->db->escape_str(trim($page_post_data['check39'])) ,
			'check40' => $this->db->escape_str(trim($page_post_data['check40'])) ,
			'check41' => $this->db->escape_str(trim($page_post_data['check41'])) ,
			'check42' => $this->db->escape_str(trim($page_post_data['check42'])) ,
			'check43' => $this->db->escape_str(trim($page_post_data['check43'])) ,
			'check44' => $this->db->escape_str(trim($page_post_data['check44'])) ,
			'check45' => $this->db->escape_str(trim($page_post_data['check45'])) ,
			'check46' => $this->db->escape_str(trim($page_post_data['check46'])) ,
			'check47' => $this->db->escape_str(trim($page_post_data['check47'])) ,
			'check48' => $this->db->escape_str(trim($page_post_data['check48'])) ,
			'check49' => $this->db->escape_str(trim($page_post_data['check49'])) ,
			
		);
		$this->mongo_db->insert('highchart_session_data', $ins_data);
		return true;
	} //End of save_sesion_data
	public function update_sesion_data($formdata, $session_id)
	{
		$page_post_data = $this->session->userdata('page_post_data');
		$admin_id = $this->session->userdata('admin_id');
		extract($formdata);
		$upda_data = array(
			'user_id' => $this->db->escape_str(trim($admin_id)) ,
			'coin' => $this->db->escape_str(trim($coin)) ,
			'start_date' => $this->db->escape_str(trim($start_date)) ,
			'trigger' => $this->db->escape_str(trim($trigger)) ,
			'end_date' => $this->db->escape_str(trim($end_date)) ,
			'time' => $this->db->escape_str(trim($time)) ,
			'mutiply_no_market' => $this->db->escape_str(trim($mutiply_no_market)) ,
			'minus_no_score' => $this->db->escape_str(trim($minus_no_score)) ,
			'price_format' => $this->db->escape_str(trim($price_format)) ,
			'time_cot' => $this->db->escape_str(trim($time_cot)) ,
			'm_buyers_sellers' => $this->db->escape_str(trim($m_buyers_sellers)) ,
			's_b_15' => $this->db->escape_str(trim($s_b_15)) ,
			'l_quantity_15' => $this->db->escape_str(trim($l_quantity_15)) ,
			'rule_buy_sell' => $this->db->escape_str(trim($rule_buy_sell)) ,
			'ask_buy_for' => $this->db->escape_str(trim($ask_buy_for)) ,
			'bid_sell_for' => $this->db->escape_str(trim($bid_sell_for)) ,
			'tab_no' => $this->db->escape_str(trim($tab_no)) ,
			'check0' => $this->db->escape_str(trim($check0)) ,
			'check1' => $this->db->escape_str(trim($check1)) ,
			'check2' => $this->db->escape_str(trim($check2)) ,
			'check3' => $this->db->escape_str(trim($check3)) ,
			'check4' => $this->db->escape_str(trim($check4)) ,
			'check5' => $this->db->escape_str(trim($check5)) ,
			'check6' => $this->db->escape_str(trim($check6)) ,
			'check7' => $this->db->escape_str(trim($check7)) ,
			'check8' => $this->db->escape_str(trim($check8)) ,
			'check9' => $this->db->escape_str(trim($check9)) ,
			'check10' => $this->db->escape_str(trim($check10)) ,
			'check11' => $this->db->escape_str(trim($check11)) ,
			'check12' => $this->db->escape_str(trim($check12)) ,
			'check13' => $this->db->escape_str(trim($check13)) ,
			'check14' => $this->db->escape_str(trim($check14)) ,
			'check15' => $this->db->escape_str(trim($check15)) ,
			'check16' => $this->db->escape_str(trim($check16)) ,
			'check17' => $this->db->escape_str(trim($check17)) ,
			'check18' => $this->db->escape_str(trim($check18)) ,
			'check19' => $this->db->escape_str(trim($check19)) ,
			'check20' => $this->db->escape_str(trim($check20)) ,
			'check21' => $this->db->escape_str(trim($check21)) ,
			'check22' => $this->db->escape_str(trim($check22)) ,
			'check23' => $this->db->escape_str(trim($check23)) ,
			'check24' => $this->db->escape_str(trim($check24)) ,
			'check25' => $this->db->escape_str(trim($check25)) ,
			'check26' => $this->db->escape_str(trim($check26)) ,
			'check27' => $this->db->escape_str(trim($check27)) ,
			'check28' => $this->db->escape_str(trim($check28)) ,
			'check29' => $this->db->escape_str(trim($check29)) ,
			'check30' => $this->db->escape_str(trim($check30)) ,
			'check31' => $this->db->escape_str(trim($check31)) ,
			'check32' => $this->db->escape_str(trim($check32)) ,
			'check33' => $this->db->escape_str(trim($check33)) ,
			'check34' => $this->db->escape_str(trim($check34)) ,
			'check35' => $this->db->escape_str(trim($check35)) ,
			'check36' => $this->db->escape_str(trim($check36)) ,
			'check37' => $this->db->escape_str(trim($check37)) ,
			
			'check38' => $this->db->escape_str(trim($check38)) ,
			'check39' => $this->db->escape_str(trim($check39)) ,
			'check40' => $this->db->escape_str(trim($check40)) ,
			'check41' => $this->db->escape_str(trim($check41)) ,
			'check42' => $this->db->escape_str(trim($check42)) ,
			'check43' => $this->db->escape_str(trim($check43)) ,
			'check44' => $this->db->escape_str(trim($check44)) ,
			'check45' => $this->db->escape_str(trim($check45)),
			'check46' => $this->db->escape_str(trim($check46)),  
			'check47' => $this->db->escape_str(trim($check47)),  
			'check48' => $this->db->escape_str(trim($check48)),  
			'check49' => $this->db->escape_str(trim($check49)),  
		);

		$this->mongo_db->where(array(
			'_id' => $session_id
		));
		$this->mongo_db->set($upda_data);
		$update = $this->mongo_db->update('highchart_session_data');
		if ($update) {
			return true;
		}
		else {
			return false;
		}
	} //End of update_sesion_data
	public function get_sesion_data($global_symbol)
	{

		$admin_id = $this->session->userdata('admin_id');
		$connetct = $this->mongo_db->customQuery();
		$this->mongo_db->where(array(
			'user_id' => $admin_id
		));
		$res = $this->mongo_db->get('highchart_session_data');
		$resArray = iterator_to_array($res);
		return $resArray;
	}//get_sesion_data

	public function get_sesion_by_id($id)
	{
		$admin_id = $this->session->userdata('admin_id');
		$connetct = $this->mongo_db->customQuery();
		$this->mongo_db->where(array(

			'_id' => $this->mongo_db->mongoId($id)
		));
		$res = $this->mongo_db->get('highchart_session_data');
		$resArray = iterator_to_array($res);
		
		$this->session->set_userdata(array(
                'page_post_data' => (array)$resArray[0]
        ));
		return $resArray[0];
	}
	
	public function getCurrentMarketAvg($currentMarket_normal,$currentMarket_normalFirst,$average_c_m_v){   
	
			$currentMarket_Diff       = $currentMarket_normal - $currentMarket_normalFirst;
		
			if($currentMarket_Diff ==0 || is_nan($currentMarket_Diff) || is_infinite($currentMarket_Diff)|| $currentMarket_Diff ==1 ||  $currentMarket_Diff ==-1 ){
				  
				 if($oldValToStore==''){
				   $finalValToShow  = $average_c_m_v;		 
				 }else{
				   $finalValToShow =  $oldValToStore;	 
				   $finalValToShow  = $average_c_m_v;		
				 }
			}else if($currentMarket_normal ==0){	 
			
				 if($oldValToStore==''){
				   $finalValToShow  = $average_c_m_v;		 
				 }else{
				   $finalValToShow  =  $oldValToStore[0];	 
				 }			    		 
			}else{
				  $finalValToShow  = ($currentMarket_Diff / $currentMarket_normalFirst)*100;
				  $finalValToShow  = ($finalValToShow * 8 ) + $average_c_m_v;
				  $oldValToStore   = '';
				  $oldValToStore[] = $finalValToShow;
			}
			return $finalValToShow;
	}//getCurrentMarketAvg
	
	public function getTriggerCalForHighchartBuy($rulesBuy_pArr){   
	               
                    if (in_array(1, $rulesBuy_pArr)) {
                        $ruleBuy1 = 1;
                    } else {
                        $ruleBuy1 = 0;
                    }
                    if (in_array(2, $rulesBuy_pArr)) {
                        $ruleBuy2 = 1;
                    } else {
                        $ruleBuy2 = 0;
                    }
                    if (in_array(3, $rulesBuy_pArr)) {
                        $ruleBuy3 = 1;
                    } else {
                        $ruleBuy3 = 0;
                    }
                    if (in_array(4, $rulesBuy_pArr)) {
                        $ruleBuy4 = 1;
                    } else {
                        $ruleBuy4 = 0;
                    }
                    if (in_array(5, $rulesBuy_pArr)) {
                        $ruleBuy5 = 1;
                    } else {
                        $ruleBuy5 = 0;
                    }
                    $ruleBuy1 = ($ruleBuy1 != 0) ? 1 * $rule_buy_sell : 0;
                    $ruleBuy2 = ($ruleBuy2 != 0) ? 1 * $rule_buy_sell : 0;
                    $ruleBuy3 = ($ruleBuy3 != 0) ? 1 * $rule_buy_sell : 0;
                    $ruleBuy4 = ($ruleBuy4 != 0) ? 1 * $rule_buy_sell : 0;
                    $ruleBuy5 = ($ruleBuy5 != 0) ? 1 * $rule_buy_sell : 0;
                    if ($ruleBuy1 != 0) {
                        $ruleValue = "{  y: 1, color: '#3CB371'}";
                        $buySum .= $prefix . '' . $ruleValue . '';
                    } else if ($ruleBuy2 != 0) {
                        $ruleValue = "{  y: 2, color: '#008000'}";
                        $buySum .= $prefix . '' . $ruleValue . '';
                    } else if ($ruleBuy3 != 0) {
                        $ruleValue = "{  y: 3, color: '#3CB371'}";
                        $buySum .= $prefix . '' . $ruleValue . '';
                    } else if ($ruleBuy4 != 0) {
                        $ruleValue = "{  y: 4, color: '#6B8E23'}";
                        $buySum .= $prefix . '' . $ruleValue . '';
                    } else if ($ruleBuy5 != 0) {
                        $ruleValue = "{  y: 5, color: '#9ACD32'}";
                        $buySum .= $prefix . '' . $ruleValue . '';
                    } else {
                        $buySum .= $prefix . '0';
                    }
					
	}//getTriggerCalForHighchart
	public function getTriggerCalForHighchartSell($rulesSell_pArr){   
	// ======= Sell Work Goess here ======== //
                    if (in_array(1, $rulesSell_pArr)) {
                        $ruleSell1 = 1;
                    } else {
                        $ruleSell1 = 0;
                    }
                    if (in_array(2, $rulesSell_pArr)) {
                        $ruleSell2 = 1;
                    } else {
                        $ruleSell2 = 0;
                    }
                    if (in_array(3, $rulesSell_pArr)) {
                        $ruleSell3 = 1;
                    } else {
                        $ruleSell3 = 0;
                    }
                    if (in_array(4, $rulesSell_pArr)) {
                        $ruleSell4 = 1;
                    } else {
                        $ruleSell4 = 0;

                    }
                    if (in_array(5, $rulesSell_pArr)) {
                        $ruleSell5 = 1;
                    } else {
                        $ruleSell5 = 0;
                    }
                    $ruleSell1 = ($ruleSell1 != 0) ? 1 * $rule_buy_sell : 0;
                    $ruleSell2 = ($ruleSell2 != 0) ? 1 * $rule_buy_sell : 0;
                    $ruleSell3 = ($ruleSell3 != 0) ? 1 * $rule_buy_sell : 0;
                    $ruleSell4 = ($ruleSell4 != 0) ? 1 * $rule_buy_sell : 0;
                    $ruleSell5 = ($ruleSell5 != 0) ? 1 * $rule_buy_sell : 0;
                    if ($ruleSell1 != 0) {
                        //$categroy   .= $prefix . "'level 1'"; 
                        $ruleValues = "{  y: 1, color: '#FFA07A'}";
                        $sellSum .= $prefix . '' . $ruleValues . '';
                    } else if ($ruleSell2 != 0) {
                        $ruleValues = "{  y: 2, color: '#8B0000'}";
                        $sellSum .= $prefix . '' . $ruleValues . '';
                    } else if ($ruleSell3 != 0) {
                        $ruleValues = "{  y: 3, color: '#FF6347'}";
                        $sellSum .= $prefix . '' . $ruleValues . '';
                    } else if ($ruleSell4 != 0) {
                        $ruleValues = "{  y: 4, color: '#DB7093'}";
                        $sellSum .= $prefix . '' . $ruleValues . '';
                    } else if ($ruleSell5 != 0) {
                        $ruleValues = "{  y: 5, color: '#B22222'}";
                        $sellSum .= $prefix . '' . $ruleValues . '';
                    } else {
                        $sellSum .= $prefix . '0';
                    }
	}//getTriggerCalForHighchartSell
	
	public function highchart_empty_variable($id)
	{
		$black_wall_pressure          = '';
        $yellow_wall_pressure         = '';
        $pressure_diff                = '';
        $great_wall_price             = '';
        $seven_level_depth            = '';
        $score                        = '';
        $straightline                 = '';
        $current_market_value         = '';
        $last_qty_time_ago            = '';
        $last_200_time_ago            = '';
        $last_200_time_ago            = '';
        $market_depth_quantity        = '';
        $market_depth_ask             = '';
        $last_qty_buy_vs_sell         = '';
        $last_200_buy_vs_sell         = '';
        $last_qty_buy_vs_sell_15      = '';
        $last_qty_time_ago_15         = '';
        $buyers                       = '';
        $sellers                      = '';
        $buyers_fifteen               = '';
        $sellers_fifteen              = '';
        $sellers_buyers_per_fifteen   = '';
        $bid_contracts                = '';
        $ask_contract                 = '';
        $buySum                       = '';
        $sellSum                      = '';
        $ask                          = '';
        $bid                          = '';
        $buy                          = '';
        $sell                         = '';
        $black_wall_percentile        = '';
        $sevenlevel_percentile        = '';
        $rolling_five_bid_percentile  = '';
        $rolling_five_ask_percentile  = '';
        $five_buy_sell_percentile     = '';
        $fifteen_buy_sell_percentile  = '';
        $last_qty_buy_sell_percentile = '';
        $last_qty_time_percentile     = '';
		
		$virtual_barrier_percentile      = '';
		$virtual_barrier_percentile_ask  = '';
		$last_qty_time_fif_percentile    = '';
		$big_buyers_percentile           = '';
		$big_sellers_percentile          = '';
		$buy_percentile                  = '';
		$sell_percentile                 = '';
		$ask_percentile                  = '';
		$bid_percentile                  = '';
		return true;
	}//highchart_empty_variable
	
	
	public function checkPercentile($percentile)
	{
		
		            if ($percentile==1) {
						$percentileVal = 'Top 25 %';
						return $percentileVal;
					}
					else
					if ($percentile==2) {
						$percentileVal = 'Top 20 %';
						return $percentileVal;
					}
					else
					if ($percentile==3) {
						$percentileVal = 'Top 15 %';
						return $percentileVal;
					}
					else
					if ($percentile==4) {
						$percentileVal = 'Top 10 %';
						return $percentileVal;
					}
					else
					if ($percentile==5) {
						$percentileVal = 'Top 5 %';;
						return $percentileVal;
					}
					else
					if ($percentile==7) {
						$percentileVal = 'Top 4 %';
						return $percentileVal;
					}
					else
					if ($percentile==8) {
						$percentileVal = 'Top 3 %';
						return $percentileVal;
					}
					else
					if ($percentile==9 || $percentile=='9') {
						$percentileVal = 'Top 2 %';
						return $percentileVal;
					}
					else
					if ($percentile==10) {
						$percentileVal = 'Top 1 %';;
						return $percentileVal;
					}
					if ($percentile==-1) {
						$percentileVal = 'Bottom 25 %';
						return $percentileVal;
					}
					else
					if ($percentile==-2) {
						$percentileVal = 'Bottom 20 %';
						return $percentileVal;
					}
					else
					if ($percentile==-3) {
						$percentileVal = 'Bottom 15 %';
						return $percentileVal;
					}
					else
					if ($percentile==-4) {
						$percentileVal = 'Bottom 10 %';
						return $percentileVal;
					}
					else
					if ($percentile==-5) {
						$percentileVal = 'Bottom 5 %';;
						return $percentileVal;
					}
					else
					if ($percentile==-7) {
						$percentileVal = 'Bottom 4 %';
						return $percentileVal;
					}
					else
					if ($percentile==-8) {
						$percentileVal = 'Bottom 3 %';
						return $percentileVal;
					}
					else
					if ($percentile==-9) {
						$percentileVal = 'Bottom 2 %';
						return $percentileVal;
					}
					else
					if ($percentile==-10) {
						$percentileVal = 'Bottom 1 %';;
						return $percentileVal;
					}
					else {
						$percentileVal = 0;
						return $percentileVal;
					}
	}
	
}

?>