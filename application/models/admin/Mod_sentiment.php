<?php
class mod_sentiment extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	//getSentimentReport
	public function getSentimentReport($type, $source, $coin, $start ,$end ,$formula,$time) {
		
		 if($source=='twitter'){
			$Table = 'sentiments_tweet'; 
		 }else if($source=='reddit'){
			$Table = 'sentiments_reddit'; 
		}
	     if($time=='day') {
				 // New data
				 $fromNew  = '';
				 $from     = strtotime($start);
				 $from     = $from +36000;
				 $fromOrg  = strtotime($end);
				 $fromOrg  = $fromOrg +36000;
				 $to       = strtotime($end);
				 $to       = $to +36000;
				 $datediff = $to - $from;
				 $timestampOnDay = ($from) + 60*60*24;
				 // Set Up Output for the Timestamp if over 24 hours
				 $totalDays  = round($datediff / (60 * 60 * 24));
				 $totalHours = round(($to) - ($from))/(60*60);
				 $totalDaysOrHours = $totalDays ;
		 }
		 if($time=='hour') {
				 //  New data
				 $fromNew  = '';
				 $from     = strtotime($start);
				 $from     = $from +36000;
				 $fromOrg  = strtotime($start);
				 $fromOrg  = $fromOrg +36000;
				 $timestampOnhour = ($from) + 60*60;
				 $to              = strtotime($end);
				 $to              = $to+ 36000;
				 $totalHours      = round((($to) - ($from))/(60*60));
				 $totalDaysOrHours = $totalHours;  
		 }
		 
	        for ($k = 1; $k < $totalDaysOrHours; $k++) {
				
				 $negative_sentiment ='';
				 $positive_sentiment ='';
				 
					if($time=='day'){
						if($fromNew==''){   
							   $from            =  $from;
							   $timestampOnDay  =  ($from) + 60*60*24;
							   $fromNew         =  $timestampOnDay;
						}else{			  
							   $from2           = ($from) + $k*60*60*24;
							   $from3           = ($from2) - (60*60*24);
							   $form            =  $from3 ;
							   $timestampOnDay  = ($fromOrg) + $k*60*60*24;
						}
					}else if($time=='hour'){
						
						if($fromNew==''){
						   $from            = $from +36000;
						   $timestampOnhour = ($from) + 60*60;
						   $fromNew         =  $timestampOnhour;
						}else{ 
						   $from    = ($from) + 60*60;
						   $timestampOnhour = ($fromOrg) + $k*60*60;   
						 }
					}
		$from  =  $from ;		
		$timestampOnhour =  $timestampOnhour;
		// Custome QueryGoes Here 
		ini_set("memory_limit", -1);
		$start_date = date("Y-m-d H:i:s", $from);
		$start_date_mongo = $this->mongo_db->converToMongodttime($start_date);
		
		$end_date = date("Y-m-d H:i:s", $timestampOnhour);
		$end_date_mongo = $this->mongo_db->converToMongodttime($end_date);
		//$this->mongo_db->where_gte('created_date', $start_date*'000');
		//$this->mongo_db->where_lte('created_date', $end_date*'000');
		$this->mongo_db->limit(50);
		$this->mongo_db->where('keyword', $coin);
		$res = $this->mongo_db->get($Table);
		$result = iterator_to_array($res);
		
		
		$negative_sentiment = 0;
		$positive_sentiment = 0;
		$countnegative = '';
		$countpositive = '';
		$potivrat = 0;
		$negative = 0;
		foreach($result as $key=>$value)
		{
		   if($value['negative_sentiment']!=''){
		     $negative_sentiment+= $value['negative_sentiment'];
			 $countnegative++;
		   }
		   if($value['positive_sentiment']!=''){
		     $positive_sentiment+= $value['positive_sentiment'];
			 $countpositive++;
		   }
		}
		$countTotalRecord  =  count($result);
		//echo "<pre>";  print_r($result); exit;            
				$totalrecord         = $countTotalRecord;
				$negative_sentiment  = $negative_sentiment;
				$positive_sentiment  = $positive_sentiment;
				$count_negative_sentiment  = $countnegative;
				$count_positive_sentiment  = $countpositive;
				
				 if($positive_sentiment!=0){
					$potivrat        =  $positive_sentiment / $totalrecord;
					$potivrat_count  =  $positive_sentiment / $count_negative_sentiment;
				 }
				 if($negative_sentiment!=0){
					$negative        =  $negative_sentiment / $totalrecord;
					$negative_count  =  $negative_sentiment / $count_positive_sentiment;
				 }
				$hourrecord = ($totalDays-1) ;
				//$temp = array();
				$temp['totalrecord']         = $totalrecord;
				$temp['sum_nageative']       = $negative_sentiment;
				$temp['sum_positive']        = $positive_sentiment;
				$temp['t_neg_divide_t']      = $negative;
				$temp['t_pos_divide_t']      = $potivrat;
				$temp['t_neg_divide_t_neg']  = $negative_count;
				$temp['t_pos_divide_t_pos']  = $potivrat_count;
				$final_array[]=$temp;
                //}//foreach ($data as $item){
			}//   for ($k = 1; $k < $totalDays; $k++) {	
		  echo "<pre>";   print_r($final_array); exit;	
	} //end getSentimentReport

	

} //End of Model

?>