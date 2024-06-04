<?php
/**
 *
 */
class Api_documentation extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("admin/mod_login");
	}

	public function index() {
		$this->mod_login->verify_is_admin_login();
		$this->load->view('admin/api_doc/index');
	}

}