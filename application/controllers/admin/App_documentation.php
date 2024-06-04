<?php
/**
 *
 */
class App_documentation extends CI_Controller {

	function __construct() {
		parent::__construct();

		//load main template
		$this->stencil->layout('admin_layout');

		//load required slices
		$this->stencil->slice('admin_header_script');
		$this->stencil->slice('admin_header');
		$this->stencil->slice('admin_left_sidebar');
		$this->stencil->slice('admin_footer_script');
		$this->load->model('admin/mod_login');
	}

	public function index() {
		$this->mod_login->verify_is_admin_login();
		$this->stencil->paint('admin/app_doc/index');
	}

	// public function test() {
	// 	//$output = shell_exec('crontab -l');
	// 	//file_put_contents('crontab.txt', $output . '* * * * * NEW_CRON' . PHP_EOL);
	// 	//echo exec('crontab /tmp/crontab.txt');
	// 	exit;
	// }

}