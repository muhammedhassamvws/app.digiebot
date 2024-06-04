<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '/home/digiebot/public_html/app.digiebot.com/application/libraries/mongodb_class/autoload.php'; 
$urls = array();
$connect = new MongoDB\Client("mongodb://localhost:27017");
$database = 'binance';
$db = $connect->$database;


$coins_arr = $db->coins->find(array('user_id'=>'global'));
$coins_arr = iterator_to_array($coins_arr);

foreach($coins_arr as $row){
	$url = 'http://app.digiebot.com/admin/coin_meta/index/'.$row['symbol'];
	array_push($urls,$url);
}





run_curl($urls);

 function run_curl($urls){
 	if(!empty($urls)){
		foreach ($urls as $url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			echo curl_exec($ch);
			curl_close($ch);
		}//end curl
 	}//End of check empty
 }//End of run_curl
