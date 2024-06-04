<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Slice Callbacks
|--------------------------------------------------------------------------
|
| By default, any data you bind or send to the views using Stencil will already
| be available in your slices, however,this may have you rewriting code over and
| over again everytime you want to use a Slice that has a variable in it.
|
| Callbacks elimate that problem.
|
| Slice Callbacks are callbacks or class methods that are called when a Slice is created.
| If you have data that you want bound to a given view each time that view is created
| throughout your application, a Slice Callback can organize that code into a single 
| location. Therefore, view Slice Callbacks may function like "view models" or "presenters".
| This will maintain an MVC approach to your Views without you having to write redundant code.
| This is inspired by Laravel's View Composers.
|
| Example:
|
|
|		public function sidebar()
|		{
|			return array('recent_posts' => array('one', 'two', 'three', 'four'));
|		}
|
|		Makes $recent_posts available in /views/slices/sidebar.php
|
| The function name must be the same as the slice name. You must return an associative array.
|
| For more information, please visit the docs: http://scotch.io/development/stencil#callbacks
*/

class Slices {

	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}
	// By shah'z created date : 28-3-2016
	//call back for header_top dynamic data
	//this will run everytime header_top slice is called
	function header_top(){
		
		
	}
	
	
	
		function header_top_front(){
			
			
		//header top dynamic data
		//load top menu data
		$this->CI->load->model('shops/mod_shops');
		//load complete top menu
		// Get all menu
		if(!empty($this->CI->session->userdata('shop_id'))){
		$data['profile_image_shop'] = $this->CI->mod_shops->get_shop_profile($this->CI->session->userdata('shop_id'));
		
		// Get Shop Menu
		$data['shopmenu_all']  = $this->CI->mod_shops->get_all_shop_menu(); 
		// get Parent Menu
		$data['shopmenu_parent'] = $this->CI->mod_shops->shopparent_menu();
		}
		
		//return true;
		return $data;
	}
	
	function header_top_front_home(){
		
		
		
		$this->CI->load->model('common/mod_common');
	     	
		//header top dynamic data
		//load top menu data
		$this->CI->load->model('home/mod_home');
		//load complete top menu
		$this->CI->load->helper('captcha');
			//Captcha Parameters
			$captcha_param = array(
				'img_path' => './assets/captcha/',
				'img_url' => base_url().'assets/captcha/',
				'font_path' => './assets/fonts/captcha/verdana.ttf',
				'img_height' => '40',
				'img_width' => '300'
				);
			$captcha_code = create_captcha($captcha_param);		
			$data['captcha_image'] = $captcha_code['image'];
			//Adding Captcha Value in Session
			$this->CI->session->set_userdata('captcha_code', $captcha_code['word']);
	
		// get Parent Menu
		$data['categories_array'] = $this->CI->mod_home->get_all_categories();
		// Get top header ads section goes here 
		$data['ads_array'] = $this->CI->mod_common->get_all_ads_header();
		
		
		// Get Sidebar ads section goes here 
		$data['ads_array_sidebar'] = $this->CI->mod_common->get_all_ads_sidebar();
		
	     ///echo "<prE>";  print_r($data['ads_array_sidebar']); exit;
		
		//return true;
		return $data;
	}
	
	
}
/* End of file Slices.php */
/* Location: ./application/libararies/Slices.php */