<?php
class Mod_trigger extends CI_Model {
	
	function __construct(){
        parent::__construct();
    }

	//Get All Categories
	public function get_all_trigger(){
		
		$this->db->dbprefix('trigger');
		$this->db->order_by('display_order DESC');
		//$this->db->where('parent_id','0');
		$get_all_trigger = $this->db->get('trigger');

		//echo $this->db->last_query();
		$row_trigger['trigger_list_arr'] = $get_all_trigger->result_array();
		$row_trigger['trigger_list_count'] = $get_all_trigger->num_rows;
	
		
		/*for($i=0;$i<$row_trigger['trigger_list_count'];$i++){
			
			$trigger_cat_id = $row_trigger['trigger_list_arr'][$i]['cat_id'];
			$create_trigger_chain = $this->mod_trigger->create_trigger_chain($trigger_cat_id);
			$row_trigger['trigger_list_arr'][$i]['trigger_chain'] = $create_trigger_chain;
			
		}//end for*/
	
		return $row_trigger;
		
	}//end get_all_trigger


//Get All Categories
	public function get_variables(){
		
		$this->db->dbprefix('variable');
		$this->db->order_by('id DESC');
		//$this->db->where('parent_id','0');
		$get_all_trigger = $this->db->get('variable');
		//echo $this->db->last_query();
		$row_trigger = $get_all_trigger->result_array();
	    return $row_trigger;
		
	}//end get_all_trigger


	//Get All Categories Count
	public function get_all_trigger_count(){
		
		$this->db->dbprefix('trigger');
		return $this->db->count_all("trigger");
		
	}//end get_all_trigger_count

	//Get Categofy Record by ID
	public function get_trigger($cat_id){
		
		$this->db->dbprefix('trigger');
		$this->db->where('cat_id',$cat_id);
		$get_trigger = $this->db->get('trigger');

		//$this->db->last_query(); exit;
		
		$row_trigger['trigger_arr'] = $get_trigger->row_array();
		$row_trigger['trigger_arr_count'] = $get_trigger->num_rows;
		
		return $row_trigger;
		
	}//end get_trigger
	
	//Check if Category exist against the selected Parent Id. If Nowt.. proceed
	public function check_if_trigger_exist($trigger_name,$parent_id,$exclude_self){
		
		$this->db->dbprefix('trigger');
		$this->db->select('cat_id');
		$this->db->from('trigger');
		if($exclude_self != 0) $this->db->where('cat_id !=', strip_quotes($exclude_self));
		$this->db->where('trigger_name', strip_quotes($trigger_name));
		$this->db->where('parent_id', strip_quotes($parent_id));
		$count_result = $this->db->count_all_results();
		//echo $this->db->last_query(); 		exit;
		return $count_result;

	}//end check_if_trigger_exist

	//Get Category Root Parent
	public function get_trigger_root_parent($cat_id){
		
		$this->db->dbprefix('trigger');
		$this->db->where('cat_id',$cat_id);
		$get_trigger_arr = $this->db->get('trigger');

		//echo $this->db->last_query(); exit;
		$row_trigger = $get_trigger_arr->row_array();
		
		if($row_trigger['parent_id'] == 0)
			return $row_trigger;
		else
			return $this->mod_trigger->get_trigger_root_parent($row_trigger['parent_id']);
			
	}//end get_trigger_root_parent
	

	//Create Category Herachy Chain 
	public function create_trigger_chain($cat_id){
		
		global $chain_str;

		$this->db->dbprefix('trigger');
		$this->db->select('cat_id,parent_id, trigger_name');
	
		$this->db->where('cat_id',$cat_id);

		$get_trigger_arr = $this->db->get('trigger');
		$row_trigger = $get_trigger_arr->row_array();
		
		//echo $this->db->last_query();

		$chain_str[] =  $row_trigger['trigger_name'];
		
		if($row_trigger['parent_id'] == 0){
			$reverse_chain = array_reverse($chain_str);
			$chain_str = array(); //clear the global variable;
			$creating_chain = implode(' > ',$reverse_chain);
			
			return $creating_chain;
			
		}else
			return $this->mod_trigger->create_trigger_chain($row_trigger['parent_id']);
		
		//end if($row_trigger['parent_id'] == 0)

	}//end create_trigger_chain
	
	
	//Add New Category
	public function add_new_trigger($data){
		
		extract($data);
		
		$created_date = date('Y-m-d G:i:s');
		$ip_address = $this->input->ip_address();
		$created_by = $this->session->userdata('admin_id');
		
		$ins_data = array(
				'trigger_name' => (trim($trigger_name)),
				'parent_id'     => (trim($parent_id)),
				'status'        => (trim($status)),
				'created_by'    => (trim($created_by)),
				'created_by_ip' => (trim($ip_address)),
				'created_date'  => (trim($created_date))
		);

		//Insert the record into the database.
		$this->db->dbprefix('trigger');
		$ins_into_db = $this->db->insert('trigger', $ins_data);
			
		if($ins_into_db) return true;

	}//end add_new_trigger()

	//Edit Category
	public function edit_trigger($data){
		
		extract($data);
	
		$last_modified_date = date('Y-m-d G:i:s');
		$last_modified_ip = $this->input->ip_address();
		$last_modified_by = $this->session->userdata('admin_id');

		$upd_data = array(
			'trigger_name' => (trim($trigger_name)),
			'parent_id' => (trim($parent_id)),
			'status' => (trim($status)),
			'last_modified_by' => (trim($last_modified_by)),
			'last_modified_date' => (trim($last_modified_date)),
			'last_modified_ip' => (trim($last_modified_ip))

		);

		//Insert the record into the database.
		$this->db->dbprefix('trigger');
		$this->db->where('cat_id',$cat_id);
		$upd_into_db = $this->db->update('trigger', $upd_data);
		//echo $this->db->last_query(); exit;
		
		if($upd_into_db){
		    return true;
		}//end if($upd_into_db)
		
	}//end edit_trigger()
	
	
	//Edit Category type
	public function update_cattype($data){
		
		extract($data);
		
		
		$upd_data = array(
				'meta_value' => (trim($value)),
				'cat_id' => (trim($cat_id)),
				'seo_url_name' => (trim($verified_seo_url))
		);

		//Insert the record into the database.
		$this->db->dbprefix('catmeta');
		$this->db->where('meta_id',$meta_id);
		$upd_into_db = $this->db->update('catmeta', $upd_data);
		//echo $this->db->last_query(); exit;			
			if($upd_into_db) return true;
		
	}//end update_cattype()
	
	

	
	public function get_trigger_fields($cat_id){
		
		$this->db->dbprefix('cat_fields');
		$this->db->where('cat_id',$cat_id);
		$this->db->order_by('field_id ASC');
		$get_trigger_type = $this->db->get('cat_fields');
		$trigger_array['cat_fields_array'] = $get_trigger_type->result_array();
		
		foreach($trigger_array['cat_fields_array'] as $key=>$row){
			
         /*   $query_2 = "SELECT * FROM `tr_cat_fields_value` WHERE (`field_id` = '".$row['depend_id']."') orderby ";   
		    $result = $this->db->query($query_2);
		    $finale_array = $result->result_array();
			//$array_type_value =  implode(', ', array_map(function ($entry) { return $entry['cat_fields_array']; }, $finale_array));
			$trigger_array['cat_fields_array'][$key]['fileds_value'] = $finale_array;*/
			
			
			$query_2 = "SELECT * FROM `tr_cat_fields_value` WHERE `field_id` = '".$row['field_id']."'";
		    $result = $this->db->query($query_2);
		    $finale_array = $result->result_array();
			//$array_type_value =  implode(', ', array_map(function ($entry) { return $entry['cat_fields_array']; }, $finale_array));
			$trigger_array['cat_fields_array'][$key]['edit_fields_value'] = $finale_array;
		}
		
		//echo "<pre>";   print_r($trigger_array); exit;
		
		return $trigger_array;
	}
	
	
	
	
		
	public function get_all_trigger_type(){
		
		$this->db->dbprefix('catmeta');
		$get_trigger_type = $this->db->get('catmeta');
		$trigger_array = $get_trigger_type->result_array();
		return $trigger_array;
		
	}
		
	
	//add Category type
	public function add_cat_type($data){
		
		///echo "<pre>";  print_r($data); exit;
		
		extract($data);
		
		$last_modified_date = date('Y-m-d G:i:s');
		$last_modified_ip = $this->input->ip_address();
		$last_modified_by = $this->session->userdata('admin_id');
          
		$ins_data = array(
		
			'variable' => (trim($variable)),
			'field_name' => (trim($field_name)),
			'field_value' => (trim($field_value)),
			'cat_id' => (trim($trigger_id)),
			'html_structure' => (trim($html_structure)),
			'seo_url_name' => (trim($verified_seo_url))
		);
		//Insert the record into the database.
		$this->db->dbprefix('cat_fields');
		$ins_into_db = $this->db->insert('cat_fields', $ins_data);
		$last_id =  $this->db->insert_id();
		
		
		/*if($field_id!=''){
			
			$this->db->dbprefix('cat_fields');
			$this->db->where('cat_type_id',$field_id);
		    $get_type_value = $this->db->get('cat_type_value');
			$type_value_array = $get_type_value->result_array();
			
			foreach($type_value_array as $row){	
				$ins_data = array(
					
					'cat_type_id' => (trim($last_id)),
					'cat_type_value' => (trim($row['cat_type_value']))
				);
				//Insert the record into the database.
				$this->db->dbprefix('cat_type_value');
				$ins_into_db = $this->db->insert('cat_type_value', $ins_data);
			}
		}*/
		
		// Get all trigger type goes here
		$trigger_array = $this->get_trigger_fields($cat_id);
		//echo $this->db->last_query(); exit;
		if($ins_into_db){
          return $trigger_array;
		}//end if($ins_into_db)
		
	}//end add_cat_type()
	
		//add Category type
	public function add_field_value($data){
		
		
		extract($data);  
		
		$dependent_ids = implode(",",(array_values($dep_value)));
		//echo "<pre>";  print_r($dependent_values); exit;
	
		$ins_data = array(
		
			'field_id' => (trim($field_id)),
			'field_value' => (trim($field_value)),
			'dep_value' => (trim($dependent_ids))
		);
		
		//Insert the record into the database.
		$this->db->dbprefix('cat_fields_value');
		$ins_into_db = $this->db->insert('cat_fields_value', $ins_data);
		
		if($ins_into_db){
          return true;;
		}//end if($ins_into_db)
		
	}//end add_cat_type()
	
	
	//add Category type
	public function ajax_add_trigger_type_value($data){
		
		extract($data);
		
		$ins_data = array(
			'cat_type_id' => (trim($cat_type_id)),
			'cat_type_value' => (trim($cat_type_value))
		);
		//Insert the record into the database.
		$this->db->dbprefix('cat_type_value');
		$ins_into_db = $this->db->insert('cat_type_value', $ins_data);
		//echo $this->db->last_query(); exit;
		if($ins_into_db){
          return true;
		}//end if($ins_into_db)
		
	}//end add_cat_type()
	
	
	//add Category type
	public function change_cattype($cat_type_id,$html_structure){
	
		$update_data = array(
			'html_structure' => (trim($html_structure))
		);
		//Insert the record into the database.
		$this->db->dbprefix('cat_type');
		$this->db->where('meta_id',$cat_type_id);
		$update_into_db = $this->db->update('cat_type', $update_data);
		//echo $this->db->last_query(); exit;
		if($update_into_db){
          return true;
		}//end if($update_into_db)
		
	}//end change_cattype()
	
	
	//add Category type
	public function ajax_delete_trigger_type_value($data){
		
		extract($data);
		
	    //Delete the record from the database.
		$this->db->dbprefix('cat_type_value');
		$this->db->where('cat_type_value',$cat_type_value);
		$del_into_db = $this->db->delete('cat_type_value');
		//$this->db->last_query();
		if($del_into_db) return true;
		
	}//end add_cat_type()
	

	//Delete Category
	public function delete_trigger($cat_id){
		//Delete the record from the database.
		$this->db->dbprefix('trigger');
		$this->db->where('cat_id',$cat_id);
		$del_into_db = $this->db->delete('trigger');
		//$this->db->last_query();
		if($del_into_db) return true;
	}//end delete_page()
	
	
	//Delete Category type
	public function delete_cattype($field_id){
		
		
		
		//Delete the record from the database.
		$this->db->dbprefix('cat_fields');
		$this->db->where('field_id',$field_id);
		$del_into_db_cattype = $this->db->delete('cat_fields');
		
		//$this->db->last_query();
		if($del_into_db_cattype){
			return true;
			/*$this->db->dbprefix('cat_fields_value');
			$this->db->where('field_id',$field_id);
			$del_into_db_cattype_value = $this->db->delete('cat_fields_value');*/
		} /*if($del_into_db_cattype_value) return true;*/
	}//end delete_cattype()
	
	
	public function getCategories($cat_id){
		
		$this->db->dbprefix('cat_fields');
		$this->db->where('cat_id',$cat_id);
		$this->db->order_by('field_id ASC');
		$get_trigger_type = $this->db->get('cat_fields');
		$trigger_array['cat_fields_array'] = $get_trigger_type->result_array();
		
		foreach($trigger_array['cat_fields_array'] as $key=>$row){
			
            $query_2 = "SELECT * FROM `tr_cat_fields_value` WHERE (`field_id` = '".$row['depend_id']."')";   
		    $result = $this->db->query($query_2);
		    $finale_array = $result->result_array();
			//$array_type_value =  implode(', ', array_map(function ($entry) { return $entry['cat_fields_array']; }, $finale_array));
			$trigger_array['cat_fields_array'][$key]['fileds_value'] = $finale_array;
			
			
			$query_2 = "SELECT * FROM `tr_cat_fields_value` WHERE `field_id` = '".$row['field_id']."'";
		    $result = $this->db->query($query_2);
		    $finale_array = $result->result_array();
			//$array_type_value =  implode(', ', array_map(function ($entry) { return $entry['cat_fields_array']; }, $finale_array));
			$trigger_array['cat_fields_array'][$key]['edit_fields_value'] = $finale_array;
		}
		//echo "<pre>";   print_r($trigger_array); exit;
		return $trigger_array;
	}
	
	
}
?>