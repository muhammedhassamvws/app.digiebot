<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coin_balance extends CI_Controller {

	public function __construct(){

		parent::__construct();

		// Load Modal
		$this->load->model('admin/mod_balance');
    $this->load->model('admin/mod_coins');
		$this->load->library('binance_api');

	}
  // public function index()
  // {
  //   exit;
  //     $id = $this->session->userdata('admin_id');
  //     $coins = $this->mod_coins->get_all_user_coins($id);
  //     $x = 1;
  //     foreach ($coins as $coin) {
  //     $symbol = $coin['symbol'];
  //     $user_id = $coin['user_id'];
  //     $balance = $this->binance_api->get_account_balance($symbol);
  //     $upd = $this->mod_balance->update_coin_balance($symbol,$balance,$user_id);
  //     if ($x == 1) {
  //     $symbol = "BTC";
  //     $balance = $this->binance_api->get_bitcoin_balance($symbol);
  //     $upd = $this->mod_balance->update_coin_balance($symbol,$balance,$user_id);

	// 		$balance1 = $this->binance_api->get_account_balance("BNBBTC");
  //     $upd = $this->mod_balance->update_coin_balance("BNBBTC",$balance1,$user_id);
  //     }
  //     $x++;
  //   }
  //   echo "true";
  //   exit;
  // }//End of Index
}//End of Controller 
