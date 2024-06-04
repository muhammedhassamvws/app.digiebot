<?php
class mod_campaign extends CI_Model {
	function __construct(){
		
        parent::__construct();
    }
	
	//get_campaign_variation
	public function get_campaign_variation($variation_id){
		
		$this->db->dbprefix('campaign_variations');
		$this->db->where('id',$variation_id);
		
		$get_campaign_variations = $this->db->get('campaign_variations');
		$campaign_variations_arr = $get_campaign_variations->row_array();
		
		
		//Get Variation Images
		$this->db->dbprefix('variation_images');
		$this->db->where('variation_id',$campaign_variations_arr['id']);
		
		$get_variation_images = $this->db->get('variation_images');
		$variation_images_arr = $get_variation_images->result_array();
		$campaign_variations_arr['variation_images'] = $variation_images_arr;
		
		
		//Get Variation CSS
		$this->db->dbprefix('variation_css');
		$this->db->where('variation_id',$campaign_variations_arr['id']);
		
		$get_variation_css = $this->db->get('variation_css');
		$variation_css_arr = $get_variation_css->result_array();
		$campaign_variations_arr['variation_css'] = $variation_css_arr;
		
		
		//Get Variation JS
		$this->db->dbprefix('variation_js');
		$this->db->where('variation_id',$campaign_variations_arr['id']);
		
		$get_variation_js = $this->db->get('variation_js');
		$variation_js_arr = $get_variation_js->result_array();
		$campaign_variations_arr['variation_js'] = $variation_js_arr;
		
		
		return $campaign_variations_arr;
		
	}//end get_campaign_variation
	
	
	//get_form_short_code
	public function get_form_short_code($campaign_id,$variation_id){
		
		$this->db->dbprefix('campaigns');
		$this->db->where('id',$campaign_id);
		
		$get_campaign = $this->db->get('campaigns');
		$campaign_arr = $get_campaign->row_array();
		
		$response = '<div class="container">
					  <form action="'.SURL.'campaign/submit-variation-form" method="post">';
					    if($campaign_arr['first_name']==1){
						$response .='<div class="form-group">
									  <label for="text">First Name:</label>
									  <input type="text" class="form-control" placeholder="Enter first name" id="first_name" name="first_name">
									 </div>';
						}
						if($campaign_arr['last_name']==1){
						$response .='<div class="form-group">
						  <label for="last_name">Last Name:</label>
						  <input type="text" class="form-control" placeholder="Enter last name" id="last_name" name="last_name">
						</div>';
						}
						if($campaign_arr['email']==1){
						$response .='<div class="form-group">
						  <label for="email">Email:</label>
						  <input type="text" class="form-control" id="email" placeholder="Enter email address" name="email">
						</div>';
						}
						if($campaign_arr['phone_number']==1){
						$response .='<div class="form-group">
						  <label for="phone_number">Phone Number:</label>
						  <input type="text" class="form-control" placeholder="Enter phone number" id="phone_number" name="phone_number">
						</div>';
						}
						$response .='<div class="form-group">
						<input type="hidden" value="'.$campaign_id.'" name="campaign_id" >
						<input type="hidden" value="'.$variation_id.'" name="variation_id" >
						<button type="submit" class="btn btn-default">Submit</button>
						</div>
					  </form>
					</div>';
					
		return $response;			
		
	}//end get_form_short_code
	
	
	//submit_variation_form
	public function submit_variation_form($data){
		
		extract($data);
		
		$created_date = date('Y-m-d G:i:s');
		
		$ins_data = array(
		   'campaign_id' => $this->db->escape_str(trim($campaign_id)),
		   'variation_id' => $this->db->escape_str(trim($variation_id)),
		   'first_name' => $this->db->escape_str(trim($first_name)),
		   'last_name' => $this->db->escape_str(trim($last_name)),
		   'email' => $this->db->escape_str(trim($email)),
		   'phone_number' => $this->db->escape_str(trim($phone_number)),
		   'created_date' => $this->db->escape_str(trim($created_date)),
		);

		//Insert the record into the database.
		$this->db->dbprefix('variation_form_users');
		$ins_into_db = $this->db->insert('variation_form_users', $ins_data);
		//echo $this->db->last_query();exit;
		
		return true;

	}//end submit_variation_form()
	
	
	//submit_heatmap_clicks
	public function submit_heatmap_clicks($data){
		
		extract($data);
		
		$created_date = date('Y-m-d G:i:s');
		
		$ins_data = array(
		   'campaign_id' => $this->db->escape_str(trim($campaign_id)),
		   'variation_id' => $this->db->escape_str(trim($variation_id)),
		   'width' => $this->db->escape_str(trim($width)),
		   'height' => $this->db->escape_str(trim($height)),
		   'type' => $this->db->escape_str(trim('click'))
		);

		//Insert the record into the database.
		$this->db->dbprefix('heatmap_data');
		$ins_into_db = $this->db->insert('heatmap_data', $ins_data);
		//echo $this->db->last_query();exit;
		
		return true;

	}//end submit_heatmap_clicks()
	
	
	//submit_heatmap_mousemove
	public function submit_heatmap_mousemove($data){
		
		extract($data);
		
		$created_date = date('Y-m-d G:i:s');
		
		$ins_data = array(
		   'campaign_id' => $this->db->escape_str(trim($campaign_id)),
		   'variation_id' => $this->db->escape_str(trim($variation_id)),
		   'width' => $this->db->escape_str(trim($width)),
		   'height' => $this->db->escape_str(trim($height)),
		   'type' => $this->db->escape_str(trim('mousemove'))
		);

		//Insert the record into the database.
		$this->db->dbprefix('heatmap_data');
		$ins_into_db = $this->db->insert('heatmap_data', $ins_data);
		//echo $this->db->last_query();exit;
		
		return true;

	}//end submit_heatmap_mousemove()
	
	
	//get_variation_heatmap
	public function get_variation_heatmap($variation_id,$type){
		
		$this->db->dbprefix('heatmap_data');
		$this->db->where('variation_id',$variation_id);
		if($type =='mousemove'){
		$this->db->where('type','mousemove');	
		}
		if($type =='clicks'){
		$this->db->where('type','click');	
		}
		
		$get_heatmap_data = $this->db->get('heatmap_data');
		$heatmap_data_arr = $get_heatmap_data->result_array();
		
		return $heatmap_data_arr;
		
	}//end get_variation_heatmap
	
	
}

?>