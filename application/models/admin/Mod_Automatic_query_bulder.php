<?php
class mod_Automatic_query_bulder extends CI_Model {

	function __construct(){
        parent::__construct();
        
    }
	
	public function get_collection_fields($collection_name){
		$this->mongo_db->limit(1);
		$this->mongo_db->order_by(array('_id'=>-1));
		$data  = $this->mongo_db->get($collection_name);
		return iterator_to_array($data);
	}//End of get_collection_fields
	
	
	
}//End of mod_Automatic_query_bulder



?>