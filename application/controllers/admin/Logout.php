<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');
}

class Logout extends CI_Controller {

	public function index() {

		//Distroy All Sessions
		//$this->session->sess_destroy();
		$this->session->unset_userdata('admin_id');
		$this->session->unset_userdata('logged_in');
		$this->session->unset_userdata('no_of_login');

		$this->session->sess_destroy();
		$this->session->set_flashdata('err_message', 'You have successfully logged out.');
		redirect(base_url().'admin/login');

	}//end logout
}

/* End of file */
