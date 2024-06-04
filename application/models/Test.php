<?php
class test extends CI_Model {
	
	function __construct(){
		
        parent::__construct();
    }
	

	public function customQuery(){




			
		$db = $this->mongo_db->customQuery();

 $created_date = date('Y-m-d G:i:s');




$created_date = $this->mongo_db->converToMongodttime($created_date);




$str = '0.012';

         $arrInsert = array(

         	'quantity'=>'0.03',
         	'type'=>'ask',
         	'coin'=>'BNBBTC',
         	'created_date'=>$created_date,
         	'price'=>(float)$str


         );


       // $respArr = $db->testsorting->insertOne($arrInsert);
       // echo '<pre>';
       // print_r($respArr);
       // exit;

       // $respArr = $db->testsorting->drop();
       // echo '<pre>';
       // print_r($respArr);
       // exit;


		
		$priceAsk = (float)'0.0';

        

		$pipeline = array(

		 array(
		        '$project' => array(
		            "price" => 1,
		            "quantity"=>1,
		            "type"=>1,
		            "coin"=>1,
		            'created_date'=>1
		        )
		    ),

		    array(
		        '$match' => array(
		        	'type'=>'ask',
		            'price' => array('$gte'=>$priceAsk)
		        )
		       ),
		    array('$sort'=>array('created_date'=>-1,'price'=>-1)),
		    
		    array('$group' => array(
		       '_id' => array('price' => '$price'),
		       'quantity'    => array('$first' => '$quantity'),
		       'type'    => array('$first' => '$type'),
		       'coin'    => array('$first' => '$coin'),
		       'created_date'    => array('$first' => '$created_date'),
		       'price'    => array('$first' => '$price'),
		       ),
		      
		      ),
		   // array('$sort'=>array('created_date'=>-1,'price'=>-1)),
		     //array('$sort'=>array('price'=>1)),

		    array('$limit'=>7),
		    );



		$responseArr = $db->testsorting->aggregate($pipeline);

		foreach ($responseArr as $key => $value) {
			
			echo '<pre>';
			print_r($value);

				
	   // $one= $value->created_date;
	   // $datetime = $one->toDateTime();
	  // echo $time=$datetime->format(DATE_RSS);



		}





	}/***End of Function***/
}

?>