<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coins extends CI_Controller {

	public function __construct() {

		parent::__construct();

		//load main template
		$this->stencil->layout('admin_layout');

		//load required slices
		$this->stencil->slice('admin_header_script');
		$this->stencil->slice('admin_header');
		$this->stencil->slice('admin_left_sidebar');
		$this->stencil->slice('admin_footer_script');

		// Load Modal
		$this->load->model('admin/mod_login');
		$this->load->model('admin/mod_coins');

	}

	public function index() {
		// Login Check
		$this->mod_login->verify_is_admin_login();
		if ($this->session->userdata('user_role') != 1) {
			redirect(base_url() . 'forbidden');
		}
	
		if ($this->input->post('exchange')) {
			$exchange_type = $this->input->post('exchange');
			$this->session->set_userdata('selected_exchange', $exchange_type);
		}
	
		$exchange = $this->session->userdata('selected_exchange');
		if (empty($exchange)) {
			$exchange = 'binance';
		}

		$coins_arr = $this->mod_coins->get_all_coins($exchange);
		$data['exchange'] = $exchange;
		$data['coins_arr'] = $coins_arr;
	// echo "<pre>"; print_r($coins_arr); exit;
		$this->stencil->paint('admin/coins/coins', $data);
	}

	public function update_coin_category()
	{
		try{
			$request = $this->input->post();

			$exchange = $request['exchange'];

			if($exchange == 'binance'){

				$collection = 'coins';

			}else{

				$collection = 'coins_'.$exchange;
			}

			$db = $this->mongo_db->customQuery();

			// echo "<pre>"; print_r($collection); exit;
			$update = $db->$collection->updateOne(['_id' => new MongoDB\BSON\ObjectID($request['coin_id'])],
			[
				'$set' => ['category' => (int)$request['category']]
			],
			['upsert' => true]
		);

			if ($update->getModifiedCount() > 0) {
				echo json_encode(['success' => true, 'message' => 'Category Updated Successfully.']);
			} else {
				echo json_encode(['success' => false, 'message' => 'Something went wrong!']);
			}

		} catch (\Exception $e) {
			// Print or log the error message
			echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
		}
		
	}

	public function add_coin() {
		//Login Check
		$this->mod_login->verify_is_admin_login();
		if ($this->session->userdata('user_role') != 1) {
			redirect(base_url() . 'forbidden');
		}
		//stencil is our templating library. Simply call view via it
		$this->stencil->paint('admin/coins/add_coin');

	} //End add_coin

	public function add_coin_process() {

		//Login Check
		$this->mod_login->verify_is_admin_login();
		if ($this->session->userdata('user_role') != 1) {
			redirect(base_url() . 'forbidden');
		}
		//Adding add_coin
		$coin_id = $this->mod_coins->add_coin($this->input->post());

		if ($coin_id) {

			$this->session->set_flashdata('ok_message', 'Coin added successfully.');
			redirect(base_url() . 'admin/coins/add-coin');

		} else {

			$this->session->set_flashdata('err_message', 'Coin cannot added. Something went wrong, please try again.');
			redirect(base_url() . 'admin/coins/add-coin');

		} //end if

	} //end add_coin_process

	public function edit_coin($coin_id, $exchange) {
		//Login Check
		$this->mod_login->verify_is_admin_login();

		if ($this->session->userdata('user_role') != 1) {
			redirect(base_url() . 'forbidden');
		}
		//Fetching coin Record
		$coin_arr = $this->mod_coins->get_coin($coin_id, $exchange);
		$data['coin_arr'] = $coin_arr;
		$data['coin_id'] = $coin_id;
		$data['exchange'] = $exchange;

		$this->stencil->paint('admin/coins/edit_coin', $data);

	} //End edit_coin

	public function edit_coin_process() {

		//Login Check
		$this->mod_login->verify_is_admin_login();
		//edit_coin
		$coin_id = $this->mod_coins->edit_coin($this->input->post());

		if ($coin_id) {

			redirect(base_url() . 'admin/coins/edit-coin/' . $coin_id);

		} else {

			redirect(base_url() . 'admin/coins/edit-coin/' . $coin_id);

		} //end if

	} //end edit_coin_process

	public function delete_coin($coin_id) {

		//Login Check
		$this->mod_login->verify_is_admin_login();
		if ($this->session->userdata('user_role') != 1) {
			redirect(base_url() . 'forbidden');
		}
		//Delete coin
		$delete_coin = $this->mod_coins->delete_coin($coin_id);

		if ($delete_coin) {

			$this->session->set_flashdata('ok_message', 'coin deleted successfully.');
			redirect(base_url() . 'admin/coins');

		} else {

			$this->session->set_flashdata('err_message', 'coin can not deleted. Something went wrong, please try again.');
			redirect(base_url() . 'admin/coins');

		} //end if

	} //end delete_coin

	public function create_thumbnail() {

		//print_r($_FILES);
		if($_FILES['image']['name'] != ''){
			//Create User Directory if not exist
			$coins_material_folder_path ='assets/coin_logo/';

		if(!is_dir($coins_material_folder_path))
			 mkdir($coins_material_folder_path,0777);
			$orignal_file_name = $_FILES['image']['name'];
			$file_ext          = ltrim(strtolower(strrchr($_FILES['image']['name'],'.')),'.');
			$file_name         = 'coin-'.".".$file_ext ;
			$config['upload_path'] = $coins_material_folder_path;
			$config['allowed_types'] = 'jpg|jpeg|gif|tiff|tif|png';
			$config['max_size']	= '6000';
			$config['overwrite'] = true;
			$config['file_name'] = $orignal_file_name;
			$this->load->library('upload', $config);
            $this->upload->initialize($config);
			//print_r($config);exit;
			if(!$this->upload->do_upload('image')){
				$error_file_arr = array('error' => $this->upload->display_errors());
				return $error_file_arr;
			}else{
				//print_r("do upload runned");
				$data_image_upload = array('upload_image_data' => $this->upload->data());
				$source_info = $coins_material_folder_path.'/'.$orignal_file_name;
				//Resize the Uploaded Image 800 * 600
				$config_profile['image_library'] = 'gd2';
				$config_profile['source_image'] = $coins_material_folder_path.'/'.$orignal_file_name;
				$config_profile['create_thumb'] = TRUE;
				$config_profile['thumb_marker'] = '';
				$config_profile['maintain_ratio'] = TRUE;
				$config_profile['width'] = 800;
				$config_profile['height'] = 600;
				$this->load->library('image_lib');
				$this->image_lib->initialize($config_profile);
				$this->image_lib->resize();
				$this->image_lib->clear();
				//Creating Thumbmail 28 * 28
				//Uploading is successful now resizing the uploaded image
			 	$config_profile['image_library'] = 'gd2';
				$config_profile['source_image'] = $coins_material_folder_path.'/'.$orignal_file_name;
				$config_profile['new_image']    = $coins_material_folder_path.'/thumbs/'.$orignal_file_name;
				$config_profile['create_thumb'] = TRUE;
				$config_profile['thumb_marker'] = '';
				$config_profile['maintain_ratio'] = TRUE;
				$config_profile['width']  = 30;
				$config_profile['height'] = 30;
				$this->load->library('image_lib');
				$this->image_lib->initialize($config_profile);
				$this->image_lib->resize();
				$this->image_lib->clear();
				echo $orignal_file_name;
		        exit;
			}//end if(!$this->upload->do_upload('prof_image'))

		}//end if($_FILES['image']['name'] != '')
		$error = 0;
		$userfile = $_FILES['image']['tmp_name'];
		$userfile_name = $_FILES['image']['name'];
		$userfile_size = $_FILES['image']['size'];
		$userfile_type = $_FILES['image']['type'];
		/////////////////////////
		//GET-DECLARE DIMENSIONS //
		$dimension = getimagesize($userfile);
		$large_width = $dimension[0]; // GET PHOTO WIDTH
		$large_height = $dimension[1]; //GET PHOTO HEIGHT
		$small_width = 30; // DECLARE THUMB WIDTH
		$small_height = 30; // DECLARE THUMB HEIGHT
		/////////////////////////
		//CHECK SIZE  //
		if ($userfile_size > 102400) {
			$error = 1;
			$msg = "The photo is over 100kb. Please try again.";
		}
		////////////////////////////////
		// CHECK TYPE (IE AND OTHERS) //
		if ($userfile_type = "image/jpeg") {
			if ($userfile_type != "image/jpeg") {
				$error = 1;
				$msg = "The photo must be JPG";
			}
		}
		//////////////////////////////
		//CHECK WIDTH/HEIGHT //
		/*if ($large_width!=600 or$large_height!=400){
		$error=1;
		$msg = "The photo must be 600x400 pixels";
		} */
		///////////////////////////////////////////
		//CREATE THUMB / UPLOAD THUMB AND PHOTO ///
		if ($error != 1) {
			$image = $userfile_name; //if you want to insert it to the database
			$pic = imagecreatefromjpeg($userfile);
			$small = imagecreatetruecolor($small_width, $small_height);
			imagecopyresampled($small, $pic, 0, 0, 0, 0, $small_width, $small_height, $large_width, $large_height);
			if (imagejpeg($small, $_SERVER["DOCUMENT_ROOT"] . '/assets/coin_logo/thumbs/' . $userfile_name, 100)) {
				$large = imagecreatetruecolor($large_width, $large_height);
				imagecopyresampled($large, $pic, 0, 0, 0, 0, $large_width, $large_height, $large_width, $large_height);
				if (imagejpeg($large, $_SERVER["DOCUMENT_ROOT"] . '/assets/coin_logo/' . $userfile_name, 100)) {
					$msg = $userfile_name;

					//$msg .= "<img src ='".SURL."assets/coin_logo/thumbs/".$userfile_name."'>";
				} else {
					$msg = "A problem1 has occured. Please try again.";
					$error = 1;}
			} else {
				$msg = "A problem3 has occured. Please try again.";
				$error = 1;
			}
		}
		echo $msg;
		exit;
		//////////////////////////////////////////////

		/// If everything went right a photo (600x400) and
		/// a thumb(120x90) were uploaded to the given folders
	}
	// public function update_all(){
	
		
	// 	$response = $this->mongo_db->get('coins');
	// 	$arr = iterator_to_array($response);

	// 	// $db = $this->mongo_db->customQuery();
	// 	// $set['$set'] = array('exchange_type'=>'binance');
	// 	// $db->coins->updateMany(array(),$set);
					
	
	// 	echo "<pre>";
	// 	print_r($arr);
	// 	exit;
	
	// }
}
