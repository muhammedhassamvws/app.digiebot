<?php
/**
 *
 */
class Cron_listing extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    //load main template
		$this->stencil->layout('admin_layout');

		//load required slices
		$this->stencil->slice('admin_header_script');
		$this->stencil->slice('admin_header');
		$this->stencil->slice('admin_left_sidebar');
		$this->stencil->slice('admin_footer_script');

    $this->load->model('admin/mod_cronjob_listing');
    $this->load->model('admin/mod_login');

    //Login Check
    $this->mod_login->verify_is_admin_login();
   
    
  }

  public function index(){

    if ($_SERVER['REMOTE_ADDR'] == '203.99.181.17') {
       error_reporting(E_ALL);
       ini_set('display_errors', E_ALL);   
    }
    $data_arr = $this->mod_cronjob_listing->get_cron_listing();
    $data['cron_list'] = $data_arr;
    $this->stencil->paint('admin/cron_listing/index',$data);
  }

  public function cron_list(){
    $output = shell_exec('crontab -l');
    echo "<pre>";
    echo $output;
  }
  // public function add_cronjob()
  // {
  //   $this->stencil->paint('admin/cron_listing/add_cron');
  // }

  // public function add_cronjob_process(){
  //   //Adding add_user
	// 	$cron_id = $this->mod_cronjob_listing->add_cronjob($this->input->post());

	// 	if ($cron_id) {

	// 		$this->session->set_flashdata('ok_message', 'Cron added successfully with id.'. $cron_id);
	// 		redirect(base_url() . 'admin/cron-listing/add-cronjob');

	// 	} else {

	// 		$this->session->set_flashdata('err_message', 'Cron cannot added. Something went wrong, please try again.');
	// 		redirect(base_url() . 'admin/cron-listing/add-cronjob');

	// 	} //end if
  // }

  // public function test(){
  //   $url = 'http://app.digiebot.com/index.php/admin/candle_chart/get_market_trade_quarterly_history';
  //   $test = $this->mod_cronjob_listing->check_when_last_cron_ran($url);

  // }
  // public function update_cronjob_priority() {
  //   $url_id = $this->input->post('url_id');
  //   $priority = $this->input->post('priority');

  //   $user_arr = $this->mod_cronjob_listing->update_cronjob_priority($url_id, $priority);
  //   redirect(base_url() . 'admin/cron_listing/index');
  // }
  
  // public function delete_cronjob_listing() {
  //   $url_id = $this->input->post('url_id');
  //   //$priority = $this->input->post('priority');

  //   $delete_cronjob = $this->mod_cronjob_listing->delete_cronjob_listing($url_id);

  //       if ($delete_cronjob) {

  //           $this->session->set_flashdata('ok_message', 'cronjob deleted successfully.');
  //           redirect(base_url() . 'admin/cron_listing/index');

  //       } else {

  //           $this->session->set_flashdata('err_message', 'job can not deleted. Something went wrong, please try again.');
  //           redirect(base_url() . 'admin/cron_listing/index');

  //       } //end if
  // }

}
