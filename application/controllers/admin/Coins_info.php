<?php 
/**
* 
*/
class Coins_info extends CI_Controller
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
		// Load Modal
		$this->load->model('admin/mod_coins_info');
        $this->load->model('admin/mod_coins');
	}

	public function index()
	{
    	if($this->input->server('REQUEST_METHOD') == 'POST'){
            $keyword = $this->input->post('keyword');
            $coin = $this->input->post('coin');
            $News = $this->input->post('News');
            $Score = $this->input->post('Score');
            $source = $this->input->post('source');
            $factor = $this->input->post('factor');

            if($News == ''){
                $message = 'News Required';
                $type  ='400';
             $this->response($message,$type);
            }elseif($coin ==''){
                $message = 'Coin Required';
                $type  ='400';
                $this->response($message,$type);
            }elseif($source == ''){
                $message = 'Source Required';
                $type  ='400';
                $this->response($message,$type);
            }else{
              $resp = $this->mod_coins_info->save_coins_info($keyword,$coin,$News,$Score,$source,$factor);
              if($resp){
                $message = 'Coins info saved Successfully';
                $type  ='200';
                $this->response($message,$type);
              }else{
                $message = 'Some Thing went wrong please try again';
                $type  ='400';
                $this->response($message,$type);
              }
            }
        }else{

             $message = 'Only Post Data Allowed';
             $type  ='400';
             $this->response($message,$type);
        
    	 //echo $orders_arr_arr = $this->mod_coins_info->save_coins_info();
        }
	}/**End of index function */

    public function fetch_coins()
    {
        if($this->input->server('REQUEST_METHOD') == 'POST'){
                $coins_arr = $this->mod_coins_info->fetch_coins();
                echo json_encode($coins_arr);
                exit;
        }else{
             $message = 'Only Post Data Allowed';
             $type  ='400';
             $this->response($message,$type);
        }
    }/**End of function fetch_coins */

    public function get_coins_info(){
        echo $orders_arr_arr = $this->mod_coins_info->get_coins_info();
    }/*** End of get_coins_info**/

	// public function get_coins_news_listing(){
    //      $news_arr = $this->mod_coins_info->get_coin_listing();
    //      $data['news'] = $news_arr;
    //     $this->stencil->paint('admin/news/news',$data);
    // }/*** End of get_coins_info**/

    public function response($message,$type){

        $response = array('HTTP Response' =>$type , 'Message' => $message);

        echo json_encode($response);
        exit;

    }/**End of response ***/

	
}
?>

