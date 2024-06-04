<?php
/**
 *
 */
class Atg extends CI_Controller {

  function __construct() {

    parent::__construct();

    // ini_set("display_errors", E_ALL);
    // error_reporting(E_ALL);

    //helper
    $this->load->helper('common_helper');
    $this->load->model('admin/Mod_jwt');

  }

  public function index(){
      echo "<pre>";
      echo "ATG is running";
  }
  
  public function test(){
      echo "test ATG is running";
  }

  public function checkAvailableBalance($user_id='', $exchange='') {

      if(!empty($user_id) && !empty($exchange)){

          $this->find_available_btc_usdt($user_id, $exchange);
  
          //set old ATG fields
          $btcInvestPercentage = 0;
          $tradeableBTC = 0;
          $availableBTC = 0;
          $actualTradeableBTC = 0;
          $dailyTradeableBTC = 0;
  
          $usdtInvestPercentage = 0;
          $tradeableUSDT = 0;
          $availableUSDT = 0;
          $actualTradeableUSDT = 0;
          $dailyTradeableUSDT = 0;
  
  
          //set floating points
          $btcInvestPercentage = (float) number_format($btcInvestPercentage,6, '.', '');
          $tradeableBTC = (float) number_format($tradeableBTC ,6, '.', '');
          $availableBTC = (float) number_format($availableBTC ,6, '.', '');
          $actualTradeableBTC = (float) number_format($actualTradeableBTC ,6, '.', '');
          $dailyTradeableBTC = (float) number_format($dailyTradeableBTC ,6, '.', '');
  
          $usdtInvestPercentage = (float) number_format($usdtInvestPercentage ,2, '.', '');
          $tradeableUSDT = (float) number_format($tradeableUSDT ,2, '.', '');
          $availableUSDT = (float) number_format($availableUSDT ,2, '.', '');
          $actualTradeableUSDT = (float) number_format($actualTradeableUSDT ,2, '.', '');
          $dailyTradeableUSDT = (float) number_format($dailyTradeableUSDT ,2, '.', '');
  
          $resultArr = [];
          $resultArr['step_4'] = [];
          $resultArr['step_4']['availableBTC'] = $availableBTC;
          $resultArr['step_4']['availableUSDT'] = $availableUSDT;
  
          $resultArr['step_4']['btcInvestPercentage'] = $btcInvestPercentage;
          $resultArr['step_4']['usdtInvestPercentage'] = $usdtInvestPercentage;
  
          $resultArr['step_4']['tradeableBTC'] = $tradeableBTC;
          $resultArr['step_4']['tradeableUSDT'] = $tradeableUSDT;
  
          $resultArr['step_4']['actualTradeableBTC'] = $actualTradeableBTC;
          $resultArr['step_4']['actualTradeableUSDT'] = $actualTradeableUSDT;
  
          $resultArr['step_4']['dailyTradeableBTC'] = $dailyTradeableBTC;
          $resultArr['step_4']['dailyTradeableUSDT'] = $dailyTradeableUSDT;
      
      }else{
          echo "user_id and exchane is required";
      }

  }

  public function find_available_btc_usdt($user_id='', $exchange=''){

    //$request = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
    $json = file_get_contents('php://input');
    $request = json_decode($json,true);
    // echo "<pre>";
    // print_r($request);exit;

    $user_id = $request['user_id'];
    $exchange = $request['exchange'];

    $baseCurrencyArr = $request['baseCurrencyArr'];
    $customBtcPackage = $request['customBtcPackage'];
    $customUsdtPackage = $request['customUsdtPackage'];
    $dailTradeAbleBalancePercentage = $request['dailTradeAbleBalancePercentage'];

    $received_Token = $this->input->get_request_header('Authorization');
   
    // $received_Token = str_replace("Bearer ", "", $received_Token);
    // $received_Token = str_replace("Token ", "", $received_Token);
    // $tokenData = $this->Mod_jwt->GetTokenData($received_Token);
    // $tokenData = json_decode($tokenData);

    // if($tokenData != false ){
    
    //   $isUserValid = $this->Mod_jwt->isValidUser($tokenData->id);
      
    //   if($isUserValid == true || $isUserValid == 1 || $isUserValid == '1'){

        if(!empty($user_id) && !empty($exchange)){
            
            //get user dashboard wallet data
            $marketPricesArr = get_current_market_prices($exchange, ['BTCUSDT']);
            // echo "hassan <pre>";
            

            $userBalancesInfo = $this->get_dashboard_wallet($user_id, $exchange, $received_Token);
            // print_r($userBalancesInfo);
            // exit;

            if($userBalancesInfo === false){
                return false;
            }

            $balanceArr = $userBalancesInfo['data'];
            
            $btcBalanceObj = [];
            $usdtBalanceObj = [];
            $bnbBalanceObj = [];

            $btcLimitExceeded = false;
            $usdtLimitExceeded = false;

            foreach($userBalancesInfo['data']['avaiableBalance'] as $val){

              if($val['coin_symbol'] == 'BTC' ){
                $btcBalanceObj = $val;
              }
              
              if($val['coin_symbol'] == 'USDT' ){
                $usdtBalanceObj = $val;
              }
              
              if($val['coin_symbol'] == 'BNB' ){
                $bnbBalanceObj = $val;
              }
                
            }

            $btcWallet = !empty($btcBalanceObj['coin_balance']) ? $btcBalanceObj['coin_balance'] : 0;
            $usdtWallet = !empty($usdtBalanceObj['coin_balance']) ? $usdtBalanceObj['coin_balance'] : 0;
            $bnbWallet = !empty($bnbBalanceObj['coin_balance']) ? $bnbBalanceObj['coin_balance'] : 0;
            
            // echo('btcWallet: ' .$btcWallet. ' ----- usdtWallet: '. $usdtWallet. ' ----- bnbWallet: '. $bnbWallet. '<br><br>');

            $btcPackage = $customBtcPackage;
            $usdtPackage = $customUsdtPackage;
            $dailyTradePercentage = $dailTradeAbleBalancePercentage; //default 10 %
            $btcAvailable = 0;
            $usdtAvailable = 0;

            $totalBtcForPackageSelection = ($btcWallet + $balanceArr['openBalance']['onlyBtc'] + $balanceArr['lthBalance']['onlyBtc'] + $balanceArr['costAvgBalance']['onlyBtc'] - ($balanceArr['openLthBTCUSDTBalance']['onlyUsdt'] / $marketPricesArr['BTCUSDT']));
            
            $totalUsdtForPackageSelection = ($usdtWallet + $balanceArr['openBalance']['onlyUsdt'] + $balanceArr['lthBalance']['onlyUsdt'] + $balanceArr['costAvgBalance']['onlyUsdt']);

            
            $btcUsed = ($balanceArr['openBalance']['onlyBtc'] + $balanceArr['lthBalance']['onlyBtc'] + $balanceArr['costAvgBalance']['onlyBtc']);
            
            $usdtUsed = ($balanceArr['openBalance']['onlyUsdt'] + $balanceArr['lthBalance']['onlyUsdt'] + $balanceArr['costAvgBalance']['onlyUsdt']);

            // echo $usdtUsed;
            // echo $balanceArr['openLthBTCUSDTBalance']['onlyUsdt'];

            // $usdtUsed = ($balanceArr['openBalance']['onlyUsdt'] + $balanceArr['lthBalance']['onlyUsdt'] + $balanceArr['costAvgBalance']['onlyUsdt']) - $balanceArr['openLthBTCUSDTBalance']['onlyUsdt'];

            //BTC package limit
            // echo('BTC package limit '. $btcPackage. ' <= '. $totalBtcForPackageSelection.'<br><br>');

            if ($btcPackage <= $totalBtcForPackageSelection) {

              // echo('BTC Package is less than total balance  ------------------------------------ '.'<br><br>');

              $_70percentOfTotal = (70 * $btcPackage) / 100;
              $_30percentOfTotal = (30 * $btcPackage) / 100;
              //echo('_70percentOfTotal   '. $_70percentOfTotal. ' -------------    btcUsed '. $btcUsed.'<br><br>');
              // echo '<pre>BTCuSED : ';print_r($btcUsed);
              // echo '<pre>BTCPCKG : ';print_r($btcPackage);
              // echo '<pre>30prcnt : ';print_r($_30percentOfTotal);
              // echo '<pre>btcwallet : ';print_r($btcWallet);
              // echo '<pre>totalBtcForPackageSelection : ';print_r($totalBtcForPackageSelection);
              if ($_30percentOfTotal > $btcUsed) {
                $remainingTradddeeAble = $btcPackage - $_30percentOfTotal > 0 ? $btcPackage - $_30percentOfTotal : 0;
              } else {
                $remainingTradddeeAble = $btcPackage - $btcUsed > 0 ? $btcPackage - $btcUsed : 0; // sir asked to do this on 24 june 2022 @haseeb n sheraz
              }
              // echo('BTC after 70% check remainingTradddeeAble  '. $remainingTradddeeAble.'<br><br>');

              $btcAvailable = $remainingTradddeeAble > $btcWallet ? $btcWallet : $remainingTradddeeAble;

              // echo('availableBTC ------------------------22   '. $btcAvailable.'<br><br>');

              if ($remainingTradddeeAble <= 0) {
                // echo('Your BTC trade limit has been exceeded please upgrade to a bigger package    ----  ERROR'.'<br><br>');
                $btcLimitExceeded = true;
              }

            }else{

              // echo('BTC Package is GREATER than total balance  ------------------------------------ '.'<br><br>');

              // $_70percentOfTotal = (70 * $totalBtcForPackageSelection) / 100;
              $_30percentOfTotal = (30 * $totalBtcForPackageSelection) / 100;
              // echo('_70percentOfTotal   '. $_70percentOfTotal. ' -------------    btcUsed '. $btcUsed.'<br><br>');

              if ($_30percentOfTotal > $btcUsed) {
                $remainingTradddeeAble = $totalBtcForPackageSelection - $_30percentOfTotal > 0 ? $totalBtcForPackageSelection - $_30percentOfTotal : 0;
              } else {
                $remainingTradddeeAble = $totalBtcForPackageSelection - $btcUsed > 0 ? $totalBtcForPackageSelection - $btcUsed : 0;
              }
              // echo 'else<pre>BTCuSED : ';print_r($btcUsed);
              // echo '<pre>BTCPCKG : ';print_r($btcPackage);
              // echo '<pre>30prcnt : ';print_r($_30percentOfTotal);
              // echo '<pre>totalBtcForPackageSelection : ';print_r($totalBtcForPackageSelection);
              // echo('BTC after 70% check remainingTradddeeAble      '. $remainingTradddeeAble.'<br><br>');

              $btcAvailable = $remainingTradddeeAble > $btcWallet ? $btcWallet : $remainingTradddeeAble;

              // echo('availableBTC ------------------------22     '. $btcAvailable.'<br><br>');
              
            }
            
            //USDT package limit
            // echo('USDT package limit '. $usdtPackage. ' <= '. $totalUsdtForPackageSelection.'<br><br>');

            if ($usdtPackage <= $totalUsdtForPackageSelection) {

              // echo('USDT Package is less than total balance  ------------------------------------ '.'<br><br>');

              // $_70percentOfTotal = (70 * $usdtPackage) / 100;
              $_30percentOfTotal = (30 * $usdtPackage) / 100;
              // echo('_70percentOfTotal   '. $_70percentOfTotal. ' -------------    usdtUsed '. $usdtUsed.'<br><br>');

              if ($_30percentOfTotal > $usdtUsed) {
                $remainingTradddeeAble = $usdtPackage - $_30percentOfTotal > 0 ? $usdtPackage - $_30percentOfTotal : 0;
              } else {
                $remainingTradddeeAble = $usdtPackage - $usdtUsed > 0 ? $usdtPackage - $usdtUsed : 0;
              }
              // echo('USDT after 70% check remainingTradddeeAble   '. $remainingTradddeeAble.' <br><br>');
              // echo 'if<pre>usdtUsed : ';print_r($usdtUsed);
              // echo '<pre>usdtPackage : ';print_r($usdtPackage);
              // echo '<pre>30prcnt_usdt : ';print_r($_30percentOfTotal);
              // echo '<pre>totalUsdtForPackageSelection : ';print_r($totalUsdtForPackageSelection);
              $usdtAvailable = $remainingTradddeeAble > $usdtWallet ? $usdtWallet : $remainingTradddeeAble;

              // echo('availableUSDT ------------------------22    '. $usdtAvailable.'<br><br>');

              if ($remainingTradddeeAble <= 0) {
                // echo('Your USDT trade limit has been exceeded please upgrade to a bigger package ---  ERROR'.'<br><br>');
                $usdtLimitExceeded = true;
              }

            } else {

              // echo('USDT Package is GREATER than total balance  ------------------------------------ '.'<br><br>');

              $_70percentOfTotal = (70 * $totalUsdtForPackageSelection) / 100;
              $_30percentOfTotal = (30 * $totalUsdtForPackageSelection) / 100;
              // echo('_70percentOfTotal   '. $_70percentOfTotal. ' -------------    usdtUsed '. $usdtUsed.'<br><br>');

              if ($_30percentOfTotal > $usdtUsed) {
                $remainingTradddeeAble = $totalUsdtForPackageSelection - $_30percentOfTotal > 0 ? $totalUsdtForPackageSelection - $_30percentOfTotal : 0;
              } else {
                $remainingTradddeeAble = $totalUsdtForPackageSelection - $usdtUsed > 0 ? $totalUsdtForPackageSelection - $usdtUsed : 0;
              }

              // echo('USDT after 70% check remainingTradddeeAble     '. $remainingTradddeeAble.'<br><br>');

              $usdtAvailable = $remainingTradddeeAble > $usdtWallet ? $usdtWallet : $remainingTradddeeAble;
              // echo 'else<pre>usdtUsed : ';print_r($usdtUsed);
              // echo '<pre>usdtPackage : ';print_r($usdtPackage);
              // echo '<pre>30prcnt_usdt : ';print_r($_30percentOfTotal);
              // echo '<pre>totalUsdtForPackageSelection : ';print_r($totalUsdtForPackageSelection);
              // echo '<pre>usdtWallet : ';print_r($usdtWallet);
              // echo '<pre>remainingTradddeeAble : ';print_r($remainingTradddeeAble);
              // echo('availableUSDT ------------------------22      '. $usdtAvailable.'<br><br>');

            }

            // $saveCurrSettings('step_4', 'availableBNB', $availableBNB)
            // $saveCurrSettings('step_4', 'openOnlyBtc', $balanceArr['openBalance']['onlyBtc'])
            // $saveCurrSettings('step_4', 'openOnlyUsdt', $balanceArr['openBalance']['onlyUsdt'])
            // $saveCurrSettings('step_4', 'lthOnlyBtc', $balanceArr['lthBalance']['onlyBtc'])
            // $saveCurrSettings('step_4', 'lthOnlyUsdt', $balanceArr['lthBalance']['onlyUsdt'])

            //package exceed check for BTC
            if ($btcAvailable > $btcPackage) {
              $btcAvailable = $btcPackage;
            }
            
            //package exceed check for USDT
            if ($usdtAvailable > $usdtPackage) {
              $usdtAvailable = $usdtPackage;
            }

            //check currency selection
            if (!in_array('BTC', $baseCurrencyArr) && !in_array('USDT', $baseCurrencyArr)) {
              // echo('Select curency to trade  ---  Error'.'<br><br>');
              // return false;

              //by default set both currencies

            } else if (!in_array('BTC', $baseCurrencyArr) || !in_array('USDT', $baseCurrencyArr)) {
              if (!in_array('BTC', $baseCurrencyArr)) {
                $btcAvailable = 0;
              }
              if (!in_array('USDT', $baseCurrencyArr)) {
                $usdtAvailable = 0;
              }
            }

            

            $availableBTC = $btcAvailable;
            $availableUSDT = $usdtAvailable;

            //set btc/usdt wrt daily percentage
            $dailyBtc = ($dailyTradePercentage * $btcAvailable) / 100;
            $dailyUsdt = ($dailyTradePercentage * $usdtAvailable) / 100;

            $dailyBtcUsdWorth = $dailyBtc * $marketPricesArr['BTCUSDT'];
            $dailyUsdtUsdWorth = $dailyUsdt;

            $dailyBtcUsdWorth = $dailyBtcUsdWorth;
            $dailyUsdtUsdWorth = $dailyUsdtUsdWorth;
            // echo('available BTC ::  '. $btcAvailable. '   -------  available USDT ::  '. $usdtAvailable.'<br><br>');
            // echo('dailyTradePercentage ::  '. $dailyTradePercentage. '   -------  dailyUsdtUsdWorth ::  '. $dailyUsdtUsdWorth.'<br><br>');
            // echo('dailTradeAbleBalancePercentage  '. $dailTradeAbleBalancePercentage.'<br><br>');
            // echo('dailyBtc  '. $dailyBtc. ' ------- dailyUsdt '. $dailyUsdt.'<br><br>');
            // echo('dailyBtcUsdWorth  '. $dailyBtcUsdWorth. '  ------- dailyUsdtUsdWorth  '. $dailyUsdtUsdWorth.'<br><br>');


            $dataArr = [
              'user_id' => $user_id,
              'exchange' => $exchange,

              'baseCurrencyArr'=> $baseCurrencyArr,

              'availableBTC'=> (float) number_format($availableBTC, 6, '.', ''),
              'availableUSDT'=> (float) number_format($availableUSDT, 2, '.', ''),
              'tradeableBTC'=> (float) number_format($availableBTC, 6, '.', ''),
              'tradeableUSDT'=> (float) number_format($availableUSDT, 2, '.', ''),
              'actualTradeableBTC'=> (float) number_format($availableBTC, 6, '.', ''),
              'actualTradeableUSDT'=> (float) number_format($availableUSDT, 2, '.', ''),

              //new1 fields
              'btcInvestPercentage'=> 100,
              'usdtInvestPercentage'=> 100,

              // 'dailyBtc' => $dailyBtc, 
              // 'dailyUsdt' => $dailyUsdt, 
              
              // 'dailyBtc'=> (float) number_format($dailyBtc, 6, '.', ''),
              // 'dailyUsdt'=> (float) number_format($dailyUsdt, 2, '.', ''),
              'dailyTradeableBTC'=> (float) number_format($dailyBtc, 6, '.', ''),
              'dailyTradeableUSDT'=> (float) number_format($dailyUsdt, 2, '.', ''),
              'dailyBtcUsdWorth' => (float) number_format($dailyBtcUsdWorth, 2, '.', ''),
              'dailyUsdtUsdWorth' => (float) number_format($dailyUsdtUsdWorth, 2, '.', ''),

              'btcLimitExceeded' => $btcLimitExceeded,
              'usdtLimitExceeded' => $usdtLimitExceeded,

              'totalBtcForPackageSelection' => $totalBtcForPackageSelection,
              'totalUsdtForPackageSelection' => $totalUsdtForPackageSelection,
              // // new fields,
              // 'openOnlyBtc': balanceArr['openBalance']['onlyBtc'],
              // 'openOnlyUsdt': balanceArr['openBalance']['onlyUsdt'],

              // 'lthOnlyBtc': balanceArr['lthBalance']['onlyBtc'],
              // 'lthOnlyUsdt': balanceArr['lthBalance']['onlyUsdt'],

            ];

        }

        // die('**************** testing *********************');

        if(!empty($dataArr)){
          $response = [
            'status' => true,
            'data' => $dataArr,
            'message' => 'Data found',
          ];
        }else{
          $response = [
            'status' => false,
            'data' => [],
            'message' => 'something went wrong',
          ];
        }

        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($response);
        exit;

    //   }else{

    //     $message = array(
    //       'status' => 401,
    //       'message' => 'User Not Valid!!!',
    //     );

    //     http_response_code('401');
    //     echo json_encode($message);
    //   }

    // }else{

    //   $message = array(
    //     'status' => 401,
    //     'message' => 'Token not Valid!!!',
    //   );

    //   http_response_code('401');
    //   echo json_encode($message);
    // }

  }

  public function find_available_btc_usdt_test($user_id='', $exchange=''){

    $request = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
  
    // header('Content-Type: application/json;charset=utf-8');
    // echo json_encode(['status'=>true, 'data'=>$request, 'message'=>'API works']);
    // exit;

    $user_id = $request['user_id'];
    $exchange = $request['exchange'];

    $baseCurrencyArr = $request['baseCurrencyArr'];
    $customBtcPackage = $request['customBtcPackage'];
    $customUsdtPackage = $request['customUsdtPackage'];
    $dailTradeAbleBalancePercentage = $request['dailTradeAbleBalancePercentage'];

    // // Defaut values
    // $baseCurrencyArr = ['BTC', 'USDT'];
    // $customBtcPackage = 0.05;
    // $customUsdtPackage = 1000;
    // $dailTradeAbleBalancePercentage = 10;

    // echo "<pre>";
    if(!empty($user_id) && !empty($exchange)){
        
        //get user dashboard wallet data
        $marketPricesArr = get_current_market_prices($exchange, ['BTCUSDT']);

        $userBalancesInfo = $this->get_dashboard_wallet($user_id, $exchange);
        // print_r($userBalancesInfo);
        

        if($userBalancesInfo === false){
            return false;
        }

        $balanceArr = $userBalancesInfo['data'];
        
        $btcBalanceObj = [];
        $usdtBalanceObj = [];
        $bnbBalanceObj = [];

        $btcLimitExceeded = false;
        $usdtLimitExceeded = false;

        foreach($userBalancesInfo['data']['avaiableBalance'] as $val){

          if($val['coin_symbol'] == 'BTC' ){
            $btcBalanceObj = $val;
          }
          
          if($val['coin_symbol'] == 'USDT' ){
            $usdtBalanceObj = $val;
          }
          
          if($val['coin_symbol'] == 'BNB' ){
            $bnbBalanceObj = $val;
          }
            
        }

        $btcWallet = !empty($btcBalanceObj['coin_balance']) ? $btcBalanceObj['coin_balance'] : 0;
        $usdtWallet = !empty($usdtBalanceObj['coin_balance']) ? $usdtBalanceObj['coin_balance'] : 0;
        $bnbWallet = !empty($bnbBalanceObj['coin_balance']) ? $bnbBalanceObj['coin_balance'] : 0;
        
        echo('btcWallet: ' .$btcWallet. ' ----- usdtWallet: '. $usdtWallet. ' ----- bnbWallet: '. $bnbWallet. '<br><br>');

        $btcPackage = $customBtcPackage;
        $usdtPackage = $customUsdtPackage;
        $dailyTradePercentage = $dailTradeAbleBalancePercentage; //default 10 %
        $btcAvailable = 0;
        $usdtAvailable = 0;

        $totalBtcForPackageSelection = ($btcWallet + $balanceArr['openBalance']['onlyBtc'] + $balanceArr['lthBalance']['onlyBtc'] + $balanceArr['costAvgBalance']['onlyBtc'] - ($balanceArr['openLthBTCUSDTBalance']['onlyUsdt'] / $marketPricesArr['BTCUSDT']));
        
        $totalUsdtForPackageSelection = ($usdtWallet + $balanceArr['openBalance']['onlyUsdt'] + $balanceArr['lthBalance']['onlyUsdt'] + $balanceArr['costAvgBalance']['onlyUsdt']);

        
        $btcUsed = ($balanceArr['openBalance']['onlyBtc'] + $balanceArr['lthBalance']['onlyBtc'] + $balanceArr['costAvgBalance']['onlyBtc']);
        
        $usdtUsed = ($balanceArr['openBalance']['onlyUsdt'] + $balanceArr['lthBalance']['onlyUsdt'] + $balanceArr['costAvgBalance']['onlyUsdt']);

        echo $usdtUsed;
        echo $balanceArr['openLthBTCUSDTBalance']['onlyUsdt'];

        // $usdtUsed = ($balanceArr['openBalance']['onlyUsdt'] + $balanceArr['lthBalance']['onlyUsdt'] + $balanceArr['costAvgBalance']['onlyUsdt']) - $balanceArr['openLthBTCUSDTBalance']['onlyUsdt'];

        //BTC package limit
        echo('BTC package limit '. $btcPackage. ' <= '. $totalBtcForPackageSelection.'<br><br>');

        if ($btcPackage <= $totalBtcForPackageSelection) {

          echo('BTC Package is less than total balance  ------------------------------------ '.'<br><br>');

          $_70percentOfTotal = (70 * $btcPackage) / 100;
          $_30percentOfTotal = (30 * $btcPackage) / 100;
          echo('_70percentOfTotal   '. $_70percentOfTotal. ' -------------    btcUsed '. $btcUsed.'<br><br>');

          if ($_30percentOfTotal > $btcUsed) {
            $remainingTradddeeAble = $btcPackage - $_30percentOfTotal > 0 ? $btcPackage - $_30percentOfTotal : 0;
          } else {
            $remainingTradddeeAble = $btcPackage - $btcUsed > 0 ? $btcPackage - $btcUsed : 0;
          }
          echo('BTC after 70% check remainingTradddeeAble  '. $remainingTradddeeAble.'<br><br>');

          $btcAvailable = $remainingTradddeeAble > $btcWallet ? $btcWallet : $remainingTradddeeAble;

          echo('availableBTC ------------------------22   '. $btcAvailable.'<br><br>');

          if ($remainingTradddeeAble <= 0) {
            echo('Your BTC trade limit has been exceeded please upgrade to a bigger package    ----  ERROR'.'<br><br>');
            $btcLimitExceeded = true;
          }

        }else{

          echo('BTC Package is GREATER than total balance  ------------------------------------ '.'<br><br>');

          $_70percentOfTotal = (70 * $totalBtcForPackageSelection) / 100;
          $_30percentOfTotal = (30 * $totalBtcForPackageSelection) / 100;
          echo('_70percentOfTotal   '. $_70percentOfTotal. ' -------------    btcUsed '. $btcUsed.'<br><br>');

          if ($_30percentOfTotal > $btcUsed) {
            $remainingTradddeeAble = $totalBtcForPackageSelection - $_30percentOfTotal > 0 ? $totalBtcForPackageSelection - $_30percentOfTotal : 0;
          } else {
            $remainingTradddeeAble = $totalBtcForPackageSelection - $btcUsed > 0 ? $totalBtcForPackageSelection - $btcUsed : 0;
          }
          
          echo('BTC after 70% check remainingTradddeeAble      '. $remainingTradddeeAble.'<br><br>');

          $btcAvailable = $remainingTradddeeAble > $btcWallet ? $btcWallet : $remainingTradddeeAble;

          echo('availableBTC ------------------------22     '. $btcAvailable.'<br><br>');
          
        }
        
        //USDT package limit
        echo('USDT package limit '. $usdtPackage. ' <= '. $totalUsdtForPackageSelection.'<br><br>');

        if ($usdtPackage <= $totalUsdtForPackageSelection) {

          // echo('USDT Package is less than total balance  ------------------------------------ '.'<br><br>');

          $_70percentOfTotal = (70 * $usdtPackage) / 100;
          $_30percentOfTotal = (30 * $usdtPackage) / 100;
          // echo('_70percentOfTotal   '. $_70percentOfTotal. ' -------------    usdtUsed '. $usdtUsed.'<br><br>');

          if ($_70percentOfTotal > $usdtUsed) {
            $remainingTradddeeAble = $usdtPackage - $_30percentOfTotal > 0 ? $usdtPackage - $_30percentOfTotal : 0;
          } else {
            $remainingTradddeeAble = $usdtPackage - $usdtUsed > 0 ? $usdtPackage - $usdtUsed : 0;
          }
          // echo('USDT after 70% check remainingTradddeeAble   '. $remainingTradddeeAble.' <br><br>');

          $usdtAvailable = $remainingTradddeeAble > $usdtWallet ? $usdtWallet : $remainingTradddeeAble;

          // echo('availableUSDT ------------------------22    '. $usdtAvailable.'<br><br>');

          if ($remainingTradddeeAble <= 0) {
            // echo('Your USDT trade limit has been exceeded please upgrade to a bigger package ---  ERROR'.'<br><br>');
            $usdtLimitExceeded = true;
          }

        } else {

          echo('USDT Package is GREATER than total balance  ------------------------------------ '.'<br><br>');

          $_70percentOfTotal = (70 * $totalUsdtForPackageSelection) / 100;
          $_30percentOfTotal = (30 * $totalUsdtForPackageSelection) / 100;
          echo('_70percentOfTotal   '. $_70percentOfTotal. ' -------------    usdtUsed '. $usdtUsed.'<br><br>');

          if ($_30percentOfTotal > $usdtUsed) {
            $remainingTradddeeAble = $totalUsdtForPackageSelection - $_30percentOfTotal > 0 ? $totalUsdtForPackageSelection - $_30percentOfTotal : 0;
          } else {
            $remainingTradddeeAble = $totalUsdtForPackageSelection - $usdtUsed > 0 ? $totalUsdtForPackageSelection - $usdtUsed : 0;
          }

          echo('USDT after 70% check remainingTradddeeAble     '. $remainingTradddeeAble.'<br><br>');

          $usdtAvailable = $remainingTradddeeAble > $usdtWallet ? $usdtWallet : $remainingTradddeeAble;

          echo('availableUSDT ------------------------22      '. $usdtAvailable.'<br><br>');

        }

        // $saveCurrSettings('step_4', 'availableBNB', $availableBNB)
        // $saveCurrSettings('step_4', 'openOnlyBtc', $balanceArr['openBalance']['onlyBtc'])
        // $saveCurrSettings('step_4', 'openOnlyUsdt', $balanceArr['openBalance']['onlyUsdt'])
        // $saveCurrSettings('step_4', 'lthOnlyBtc', $balanceArr['lthBalance']['onlyBtc'])
        // $saveCurrSettings('step_4', 'lthOnlyUsdt', $balanceArr['lthBalance']['onlyUsdt'])

        //package exceed check for BTC
        // Comment By Huzaifa
        // if ($btcAvailable > $btcPackage) {
        //   $btcAvailable = $btcPackage;
        // }
        
        //package exceed check for USDT
        // Comment By Huzaifa
        // if ($usdtAvailable > $usdtPackage) {
        //   $usdtAvailable = $usdtPackage;
        // }

        //check currency selection
        if (!in_array('BTC', $baseCurrencyArr) && !in_array('USDT', $baseCurrencyArr)) {
          echo('Select curency to trade  ---  Error'.'<br><br>');
          // return false;

          //by default set both currencies

        } else if (!in_array('BTC', $baseCurrencyArr) || !in_array('USDT', $baseCurrencyArr)) {
          if (!in_array('BTC', $baseCurrencyArr)) {
            $btcAvailable = 0;
          }
          if (!in_array('USDT', $baseCurrencyArr)) {
            $usdtAvailable = 0;
          }
        }

        echo('available BTC ::  '. $btcAvailable. '   -------  available USDT ::  '. $usdtAvailable.'<br><br>');

        $availableBTC = $btcAvailable;
        $availableUSDT = $usdtAvailable;

        //set btc/usdt wrt daily percentage
        $dailyBtc = ($dailyTradePercentage * $btcAvailable) / 100;
        $dailyUsdt = ($dailyTradePercentage * $usdtAvailable) / 100;

        $dailyBtcUsdWorth = $dailyBtc * $marketPricesArr['BTCUSDT'];
        $dailyUsdtUsdWorth = $dailyUsdt;

        $dailyBtcUsdWorth = $dailyBtcUsdWorth;
        $dailyUsdtUsdWorth = $dailyUsdtUsdWorth;

        echo('dailTradeAbleBalancePercentage  '. $dailTradeAbleBalancePercentage.'<br><br>');
        echo('dailyBtc  '. $dailyBtc. ' ------- dailyUsdt '. $dailyUsdt.'<br><br>');
        echo('dailyBtcUsdWorth  '. $dailyBtcUsdWorth. '  ------- dailyUsdtUsdWorth  '. $dailyUsdtUsdWorth.'<br><br>');


        $dataArr = [
          'user_id' => $user_id,
          'exchange' => $exchange,

          'baseCurrencyArr'=> $baseCurrencyArr,

          'availableBTC'=> (float) number_format($availableBTC, 6, '.', ''),
          'availableUSDT'=> (float) number_format($availableUSDT, 2, '.', ''),
          'tradeableBTC'=> (float) number_format($availableBTC, 6, '.', ''),
          'tradeableUSDT'=> (float) number_format($availableUSDT, 2, '.', ''),
          'actualTradeableBTC'=> (float) number_format($availableBTC, 6, '.', ''),
          'actualTradeableUSDT'=> (float) number_format($availableUSDT, 2, '.', ''),

          //new1 fields
          'btcInvestPercentage'=> 100,
          'usdtInvestPercentage'=> 100,

          // 'dailyBtc' => $dailyBtc, 
          // 'dailyUsdt' => $dailyUsdt, 
          
          // 'dailyBtc'=> (float) number_format($dailyBtc, 6, '.', ''),
          // 'dailyUsdt'=> (float) number_format($dailyUsdt, 2, '.', ''),
          'dailyTradeableBTC'=> (float) number_format($dailyBtc, 6, '.', ''),
          'dailyTradeableUSDT'=> (float) number_format($dailyUsdt, 2, '.', ''),
          'dailyBtcUsdWorth' => (float) number_format($dailyBtcUsdWorth, 2, '.', ''),
          'dailyUsdtUsdWorth' => (float) number_format($dailyUsdtUsdWorth, 2, '.', ''),

          'btcLimitExceeded' => $btcLimitExceeded,
          'usdtLimitExceeded' => $usdtLimitExceeded,

          'totalBtcForPackageSelection' => $totalBtcForPackageSelection,
          'totalUsdtForPackageSelection' => $totalUsdtForPackageSelection,
          // // new fields,
          // 'openOnlyBtc': balanceArr['openBalance']['onlyBtc'],
          // 'openOnlyUsdt': balanceArr['openBalance']['onlyUsdt'],

          // 'lthOnlyBtc': balanceArr['lthBalance']['onlyBtc'],
          // 'lthOnlyUsdt': balanceArr['lthBalance']['onlyUsdt'],

        ];

    }

    // die('**************** testing *********************');

    if(!empty($dataArr)){
      $response = [
        'status' => true,
        'data' => $dataArr,
        'message' => 'Data found',
      ];
    }else{
      $response = [
        'status' => false,
        'data' => [],
        'message' => 'something went wrong',
      ];
    }

    header('Content-Type: application/json;charset=utf-8');
    echo json_encode($response);
    exit;

  }
  
  public function get_dashboard_wallet($user_id='', $exchange='', $received_Token = ''){
      $params = [
          'user_id' => $user_id,
          'exchange' => $exchange,
      ];
      $req_arr = [
          'req_type' => 'POST',
          'req_params' => $params,
          'req_endpoint' => 'get_dashboard_wallet',
          'header'    => $received_Token
      ];
      //print_r($req_arr);exit;
      $resp = hitCurlRequest($req_arr);
      //return $resp;
      // $resp = array('http_code' => $http_code, 'response' => $response, 'error' => $err);
      if($resp['http_code'] == 200 && $resp['response']['status'] == true){
          return $resp['response'];
      }
      return false;
  }
  
  public function get_subscription($user_id='', $exchange=''){
      
  }
  
  public function get_ATG_settings($user_id='', $exchange=''){
      
  }
  
  public function get_trading_points($user_id='', $exchange=''){

  }
  // script to pause parents by checking their total balance and allowing them to trade the coins according to their balance
  // Author @sheraz 2022 june
  public function parent_pause_by_balance_binance(){
    $this->pause_parent_order_by_balance('binance');
  }
  public function parent_pause_by_balance_kraken(){
    $this->pause_parent_order_by_balance('kraken');
  }
  public function pause_parent_order_by_balance($exchange = '',$user_id = ''){
    if($exchange == '' || $exchange == 'binance'){
      $atg_collection = 'auto_trade_settings';
      $balance_collection = 'user_investment_binance';
      $trading_collection = 'buy_orders';
    }elseif($exchange == 'kraken'){
      $atg_collection = 'auto_trade_settings_kraken';
      $balance_collection = 'user_investment_kraken';
      $trading_collection = 'buy_orders_kraken';
    }
    //$user_id = '5eb5a5a628914a45246bacc6';
    if($user_id != ''){
      $user_id_val =['$eq'=>$user_id]; 
    }else{
      $user_id_val =['$ne'=>''];
    }
    $pipeline1 = 
    [
      ['$match'=>['user_id'=>$user_id_val,'application_mode'=>'live','is_parent_paused_by_script'=>['$ne'=>1]]
      ],
      [
        '$limit'=>100
      ],
      [
        '$sort'=>['last_script_scanned'=>1]
      ]
    ];

    $db = $this->mongo_db->customQuery();
    $data_arr = $db->$atg_collection->aggregate($pipeline1);
    $atg_user = iterator_to_array($data_arr);
    //echo '<pre>';print_r($atg_user);
    if(count($atg_user) > 0){
        foreach ($atg_user as $value) {
          $user_id_db = (string)$value['user_id'];
          $coins_array=array();
          $coins_array = (array)$value['step_2']['coins'];
          $priority_btc_coins_array = array('QTUMBTC','EOSBTC','LINKBTC','ETHBTC','ETCBTC','ADABTC','DASHBTC','XMRBTC','NEOBTC','ZENBTC','XEMBTC','SOLBTC','BNBBTC','XLMBTC','DOTBTC','LTCBTC','COMPBTC','XRPBTC','AAVEBTC','ALGOBTC','KSMBTC','TRXBTC');
          $priority_usdt_coins_array = array('BTCUSDT','EOSUSDT','LTCUSDT','NEOUSDT','QTUMUSDT','XRPUSDT','ADAUSDT','BCHUSDT','DOTUSDT','LINKUSDT','XMRUSDT','COMPUSDT');
          $sorted_user_coins = array();
          $sorted_user_coins_usdt = array();
          foreach ($priority_btc_coins_array as $coin_symbol) { // sorting algo for btc coins
              if(in_array($coin_symbol,$coins_array)){
                array_push($sorted_user_coins,$coin_symbol);
              }
          } // end algo
          foreach ($priority_usdt_coins_array as $coin_symbol_usdt) { // sorting algo for usdt coins
              if(in_array($coin_symbol_usdt,$coins_array)){
                array_push($sorted_user_coins_usdt,$coin_symbol_usdt);
              }
          } // end algo
          $trading_coins_btc = array();
          $non_trading_coins_btc = array();
          foreach ($sorted_user_coins as $coin_symbol) { // separating trading and non trading coins btc
              $pipeline3 = [
                [
                  '$match'=>['admin_id'=>$user_id_db,'symbol'=>$coin_symbol,'parent_status'=>['$ne'=>'parent'],'status'=>['$nin'=>['new','canceled']]]
                ]
              ];
              $get_orders = $db->$trading_collection->aggregate($pipeline3);
              $orders_array = iterator_to_array($get_orders);
              if(count($orders_array) > 0 ){
                array_push($trading_coins_btc,$coin_symbol);
              }else{
                array_push($non_trading_coins_btc,$coin_symbol);
              }
          } // end foreach
          // $trading_coins_btc = array_merge($trading_coins_btc,$non_trading_coins_btc);
          $trading_coins_btc = array_merge($trading_coins_btc,$non_trading_coins_btc);
          $trading_coins_usdt = array();
          $non_trading_coins_usdt = array();
          foreach ($sorted_user_coins_usdt as $coin_symbol) { // separating trading and non trading coins usdt
              $pipeline3 = [
                [
                  '$match'=>['admin_id'=>$user_id_db,'symbol'=>$coin_symbol,'parent_status'=>['$ne'=>'parent'],'status'=>['$nin'=>['new','canceled']]]
                ]
              ];
              $get_orders = $db->$trading_collection->aggregate($pipeline3); // checking for the trades in buy collection.
              $orders_array = iterator_to_array($get_orders);
              if(count($orders_array) > 0 ){
                array_push($trading_coins_usdt,$coin_symbol);
              }else{
                array_push($non_trading_coins_usdt,$coin_symbol);
              }
          } // end foreach
          $trading_coins_usdt = array_merge($trading_coins_usdt,$non_trading_coins_usdt);
          $pipeline2 = 
          [
            ['$match'=>['admin_id'=>$user_id_db]
            ],
          ];
          $cavg_symbol_btc = 'BTC';
          $cavg_symbol_sdt = 'SDT';
          $users_cost_avg_parent_btc = $db->$trading_collection->count(['trigger_type'=>'barrier_percentile_trigger','admin_id'=>$user_id_db,'$and'=>[['symbol'=>new MongoDB\BSON\Regex(".*{$cavg_symbol_btc}.*", 'i')],['symbol'=>['$ne'=>'BTCUSDT']]],'cavg_parent'=>'yes','cost_avg_array'=>['$exists'=>true],'parent_status'=>['$ne'=>'parent'],'status'=>['$nin'=>['new','canceled']]]);
          $users_cost_avg_parent_usdt = $db->$trading_collection->count(['trigger_type'=>'barrier_percentile_trigger','admin_id'=>$user_id_db,'symbol'=>new MongoDB\BSON\Regex(".*{$cavg_symbol_sdt}.*", 'i'),'cavg_parent'=>'yes','cost_avg_array'=>['$exists'=>true],'parent_status'=>['$ne'=>'parent'],'status'=>['$nin'=>['new','canceled']]]);
          $data_blnc_arr = $db->$balance_collection->aggregate($pipeline2); // getting the balance from user investment collection updated by me.
          $user_balances =  iterator_to_array($data_blnc_arr);
          // echo '<pre> BTC cost avg => ';print_r($users_cost_avg_parent_btc);
          // echo '<pre> USDT cost avv => ';print_r($users_cost_avg_parent_usdt);
          // exit;
          //$total_btc_atg = $user_balances[0]['avaliableBtcBalance'] + $user_balances[0]['used_manul_btc'] + $user_balances[0]['used_auto_btc'];
          //$total_usdt_atg = $user_balances[0]['avaliableUsdtBalance'] + $user_balances[0]['used_manual_usdt'] + $user_balances[0]['used_auto_usdt'];
          $total_btc_atg = $value['allocatedBTC'];
          $total_usdt_atg = $value['allocatedUSDT'];
          $current_market_value = get_current_market_prices($exchange,['BTCUSDT']); // helper function to convert the btc value to the current market usdt price
          $btc_converted = (float)$total_btc_atg*$current_market_value['BTCUSDT'];
          $tradebale_parent_coins_btc = array();
          $tradebale_parent_coins_usdt = array();
          // getting the btc coins to set play
          if((int)$btc_converted > 10 && (int)$btc_converted < 500){// 3 coins
            $tradebale_parent_coins_btc = array_slice($trading_coins_btc, 0, 3, true); 
          }elseif((int)$btc_converted >= 500 && (int)$btc_converted < 1000){ // 5 coins
            $tradebale_parent_coins_btc = array_slice($trading_coins_btc, 0, 5, true);
          }elseif((int)$btc_converted >= 1000 && (int)$btc_converted <= 2000){ // 7 coins
            $tradebale_parent_coins_btc = array_slice($trading_coins_btc, 0, 6, true);
          }elseif((int)$btc_converted > 2000 && (int)$btc_converted <= 5000){ // 9 coins
            $tradebale_parent_coins_btc =  array_slice($trading_coins_btc, 0, 7, true);
          }elseif((int)$btc_converted > 5000 && (int)$btc_converted <= 10000){ // 11 coins
            $tradebale_parent_coins_btc = array_slice($trading_coins_btc, 0, 10, true);
          }elseif((int)$btc_converted > 10000){ // 13 coins
            $tradebale_parent_coins_btc = array_slice($trading_coins_btc, 0, 14, true);
          }
          // end of btc getting coins
          // getting the usdt coins to set play
          if((int)$total_usdt_atg > 10 && (int)$total_usdt_atg < 500){ // 3 coins
            $tradebale_parent_coins_usdt = array_slice($trading_coins_usdt, 0, 3, true);
          }elseif((int)$total_usdt_atg >= 500 && (int)$total_usdt_atg < 1000){ // 5 coins
            $tradebale_parent_coins_usdt = array_slice($trading_coins_usdt, 0, 5, true);
          }elseif((int)$total_usdt_atg >= 1000 && (int)$total_usdt_atg <= 2000){ // 7 coins
            $tradebale_parent_coins_usdt = array_slice($trading_coins_usdt, 0, 6, true);
          }elseif((int)$total_usdt_atg > 2000 && (int)$total_usdt_atg <= 5000){ // 9 coins
            $tradebale_parent_coins_usdt =  array_slice($trading_coins_usdt, 0, 7, true);
          }elseif((int)$total_usdt_atg > 5000 && (int)$total_usdt_atg <= 10000){ // 11 coins
            $tradebale_parent_coins_usdt = array_slice($trading_coins_usdt, 0, 10, true);
          }elseif((int)$total_usdt_atg > 10000){ // 13 coins
            $tradebale_parent_coins_usdt = array_slice($trading_coins_usdt, 0, 14, true);
          }
          // end of usdt getting coins
          $tradeable_coins = array_merge($tradebale_parent_coins_btc,$tradebale_parent_coins_usdt);
          echo 'Tradeable coins of '.$user_id_db.'<pre>';print_r($tradeable_coins);exit;
          // updating parents base on AI.
          if(count($tradeable_coins) > 0){
            //echo 'hello i am in';exit;
            $last_scanned = $this->mongo_db->converToMongodttime(date('Y-m-d h:i:s'));
            $db->$trading_collection->updateMany(['symbol'=>['$in'=>$tradeable_coins],'admin_id'=>$user_id_db,'parent_status'=>'parent','status'=>['$ne'=>'canceled']],['$set'=>['pause_status'=>'play','pick_parent'=>'yes','sheraz_play_modified_date'=>$last_scanned]]);
            $db->$trading_collection->updateMany(['symbol'=>['$nin'=>$tradeable_coins],'admin_id'=>$user_id_db,'parent_status'=>'parent','status'=>['$ne'=>'canceled']],['$set'=>['pause_status'=>'pause','pick_parent'=>'no','sheraz_pause_modified_date'=>$last_scanned]]);
            $db->$atg_collection->updateOne(['user_id'=>$user_id_db,'application_mode'=>'live'],['$set'=>['is_parent_paused_by_script'=>1,'last_script_scanned'=>$last_scanned]]);
          }else{
            $db->$atg_collection->updateOne(['user_id'=>$user_id_db,'application_mode'=>'live'],['$set'=>['is_parent_paused_by_script'=>1,'last_script_scanned'=>$last_scanned]]);
          } // end if condition
      }// end foreach main
    }else{
        $db->$atg_collection->updateMany(['user_id'=>['$ne'=>'']],['$set'=>['is_parent_paused_by_script'=>0]]); // making the field 0 again for all the users just to restart the scanning.
    }
  }

  public function pause_parent_order_by_balance_backup($exchange = '',$user_id = ''){
    if($exchange == '' || $exchange == 'binance'){
      $atg_collection = 'auto_trade_settings';
      $balance_collection = 'user_investment_binance';
      $trading_collection = 'buy_orders';
    }elseif($exchange == 'kraken'){
      $atg_collection = 'auto_trade_settings_kraken';
      $balance_collection = 'user_investment_kraken';
      $trading_collection = 'buy_orders_kraken';
    }elseif ($exchange == 'dg') {
      $atg_collection = 'auto_trade_settings_dg';
      // $balance_collection = 'user_investment_dg';
      $trading_collection = 'buy_orders_dg';
    }
    //$user_id = '5eb5a5a628914a45246bacc6';
    if($user_id != ''){
      $user_id_val =['$eq'=>$user_id]; 
    }else{
      $user_id_val =['$ne'=>''];
    }
    $pipeline1 = 
    [
      ['$match'=>['user_id'=>$user_id_val,'application_mode'=>'live','is_parent_paused_by_script'=>['$ne'=>1]]
      ],
      [
        '$limit'=>100
      ],
      [
        '$sort'=>['last_script_scanned'=>1]
      ]
    ];

    $db = $this->mongo_db->customQuery();
    $data_arr = $db->$atg_collection->aggregate($pipeline1);
    $atg_user = iterator_to_array($data_arr);
    // echo '<pre>';print_r($atg_user);
    if(count($atg_user) > 0){
        foreach ($atg_user as $value) {
          $user_id_db = (string)$value['user_id'];
          $coins_array=array();
          $coins_array = (array)$value['step_2']['coins'];

          $user_coins_priority = $this->rankingCoins((string) $value['user_id'], $exchange);

          if (is_array($user_coins_priority)) {
            $selected_coins = array_filter($user_coins_priority, function ($coin) use ($coins_array) {
                return in_array($coin['symbol'], $coins_array);
            });

            $selected_coins_priority_map = [];
            foreach ($selected_coins as $coin) {
                $selected_coins_priority_map[$coin['symbol']] = $coin['category'];
            }
            usort($selected_coins, function ($a, $b) use ($selected_coins_priority_map) {
                $priorityA = $selected_coins_priority_map[$a['symbol']] ?? PHP_INT_MAX;
                $priorityB = $selected_coins_priority_map[$b['symbol']] ?? PHP_INT_MAX;

                return $priorityA - $priorityB;
            });
            // echo '<br><pre>';
            // print_r($selected_coins);
          } else {
            echo "Error: The array is not properly defined.";
          }
         
          $btc_pairs = [];
          $usdt_pairs = [];

          foreach ($selected_coins as $coin) {
              $symbol = $coin['symbol'];
              $category = $coin['category'];
              if (substr($symbol, -3) === 'BTC') {
                  $btc_pairs[] = $coin;
              } elseif (substr($symbol, -4) === 'USDT') {
                  $usdt_pairs[] = $coin;
              }
          }
          usort($btc_pairs, function ($a, $b) {
              return $a['category'] - $b['category'];
          });

          usort($usdt_pairs, function ($a, $b) {
              return $a['category'] - $b['category'];
          });

          $btc_sorted_coins = sort_coins_category_wise($btc_pairs);
          $usdt_sorted_coins = sort_coins_category_wise($usdt_pairs);
          // $priority_btc_coins_array = array('QTUMBTC','EOSBTC','LINKBTC','ETHBTC','ETCBTC','ADABTC','DASHBTC','XMRBTC','NEOBTC','ZENBTC','XEMBTC','SOLBTC','BNBBTC','XLMBTC','DOTBTC','LTCBTC','COMPBTC','XRPBTC','AAVEBTC','ALGOBTC','KSMBTC','TRXBTC');
          // $priority_usdt_coins_array = array('BTCUSDT','EOSUSDT','LTCUSDT','NEOUSDT','QTUMUSDT','XRPUSDT','ADAUSDT','BCHUSDT','DOTUSDT','LINKUSDT','XMRUSDT','COMPUSDT');

          // echo '<pre>'; print_r($btc_sorted_coins);
          // echo '<pre>'; print_r($coins_array); exit;
          // exit;
          $sorted_user_coins = array();
          $sorted_user_coins_usdt = array();
          foreach ($btc_sorted_coins as $coin_document) { // sorting algo for btc coins
            $coin_symbol_btc = $coin_document['symbol'];
              if(in_array($coin_symbol_btc,$coins_array)){
                array_push($sorted_user_coins,$coin_symbol_btc);
              }
          } // end algo
          foreach ($usdt_sorted_coins as $coin_document) { // sorting algo for usdt coins
            $coin_symbol_usdt = $coin_document['symbol'];
              if(in_array($coin_symbol_usdt,$coins_array)){
                array_push($sorted_user_coins_usdt,$coin_symbol_usdt);
              }
          } // end algo
          // echo '<pre>'; print_r($sorted_user_coins); exit;
          $trading_coins_btc = array();
          $non_trading_coins_btc = array();
          // echo '<pre>'; print_r($sorted_user_coins); exit;
          foreach ($sorted_user_coins as $coin_symbol) { // separating trading and non trading coins btc
              $pipeline3 = [
                [
                  '$match'=>['admin_id'=>$user_id_db,'symbol'=>$coin_symbol,'parent_status'=>['$ne'=>'parent'],'status'=>['$nin'=>['new','canceled']]]
                ]
              ];
              $get_orders = $db->$trading_collection->aggregate($pipeline3);
              $orders_array = iterator_to_array($get_orders);
              // echo '<pre>'; print_r($orders_array); exit;
              if(count($orders_array) > 0 ){
                array_push($trading_coins_btc,$coin_symbol);
              }else{
                array_push($non_trading_coins_btc,$coin_symbol);
              }
          } // end foreach
          // echo '<pre>non_trading_coins_btc :: '; print_r($non_trading_coins_btc);
          // echo '<pre>trading_coins_btc :: '; print_r($trading_coins_btc); exit;
          // exit;
          // $trading_coins_btc = array_merge($trading_coins_btc,$non_trading_coins_btc);
          $trading_coins_btc = array_merge($trading_coins_btc,$non_trading_coins_btc);
          
          $trading_coins_usdt = array();
          $non_trading_coins_usdt = array();
          foreach ($sorted_user_coins_usdt as $coin_symbol) { // separating trading and non trading coins usdt
              $pipeline3 = [
                [
                  '$match'=>['admin_id'=>$user_id_db,'symbol'=>$coin_symbol,'parent_status'=>['$ne'=>'parent'],'status'=>['$nin'=>['new','canceled']]]
                ]
              ];
              $get_orders = $db->$trading_collection->aggregate($pipeline3); // checking for the trades in buy collection.
              $orders_array = iterator_to_array($get_orders);
              if(count($orders_array) > 0 ){
                array_push($trading_coins_usdt,$coin_symbol);
              }else{
                array_push($non_trading_coins_usdt,$coin_symbol);
              }
          } // end foreach
          $trading_coins_usdt = array_merge($trading_coins_usdt,$non_trading_coins_usdt);
          // echo '<pre>trading_coins_btc :: '; print_r($trading_coins_btc); exit;
          // $pipeline2 = 
          // [
          //   ['$match'=>['admin_id'=>$user_id_db]
          //   ],
          // ];
          // $cavg_symbol_btc = 'BTC';
          // $cavg_symbol_sdt = 'SDT';
          // $users_cost_avg_parent_btc = $db->$trading_collection->count(['trigger_type'=>'barrier_percentile_trigger','admin_id'=>$user_id_db,'$and'=>[['symbol'=>new MongoDB\BSON\Regex(".*{$cavg_symbol_btc}.*", 'i')],['symbol'=>['$ne'=>'BTCUSDT']]],'cavg_parent'=>'yes','cost_avg_array'=>['$exists'=>true],'parent_status'=>['$ne'=>'parent'],'status'=>['$nin'=>['new','canceled']]]);
          // $users_cost_avg_parent_usdt = $db->$trading_collection->count(['trigger_type'=>'barrier_percentile_trigger','admin_id'=>$user_id_db,'symbol'=>new MongoDB\BSON\Regex(".*{$cavg_symbol_sdt}.*", 'i'),'cavg_parent'=>'yes','cost_avg_array'=>['$exists'=>true],'parent_status'=>['$ne'=>'parent'],'status'=>['$nin'=>['new','canceled']]]);
      
          $total_btc_atg = $value['step_4']['allocatedBTC'];
          // echo '<pre>$total_btc_atg '; print_r($total_btc_atg); exit;
          $total_usdt_atg = $value['step_4']['allocatedUSDT'];
          $current_market_value = get_current_market_prices($exchange,['BTCUSDT']); // helper function to convert the btc value to the current market usdt price
          $btc_converted = (float)$total_btc_atg*$current_market_value['BTCUSDT'];
          // echo '<pre>$btc_converted '; print_r($btc_converted); exit;
          $tradebale_parent_coins_btc = array();
          $tradebale_parent_coins_usdt = array();
          // getting the btc coins to set play
          // echo '<pre>trading_coins_btc :: '; print_r($trading_coins_btc); exit;
          if((int)$btc_converted > 10 && (int)$btc_converted < 500){// 3 coins
            $tradebale_parent_coins_btc = array_slice($trading_coins_btc, 0, 3, true); 
          }elseif((int)$btc_converted >= 500 && (int)$btc_converted < 1000){ // 5 coins
            $tradebale_parent_coins_btc = array_slice($trading_coins_btc, 0, 5, true);
          }elseif((int)$btc_converted >= 1000 && (int)$btc_converted <= 2000){ // 7 coins
            $tradebale_parent_coins_btc = array_slice($trading_coins_btc, 0, 6, true);
          }elseif((int)$btc_converted > 2000 && (int)$btc_converted <= 5000){ // 9 coins
            $tradebale_parent_coins_btc =  array_slice($trading_coins_btc, 0, 7, true);
          }elseif((int)$btc_converted > 5000 && (int)$btc_converted <= 10000){ // 11 coins
            $tradebale_parent_coins_btc = array_slice($trading_coins_btc, 0, 10, true);
          }elseif((int)$btc_converted > 10000){ // 13 coins
            $tradebale_parent_coins_btc = array_slice($trading_coins_btc, 0, 14, true);
          }
          // end of btc getting coins
          // getting the usdt coins to set play
          if((int)$total_usdt_atg > 10 && (int)$total_usdt_atg < 500){ // 3 coins
            $tradebale_parent_coins_usdt = array_slice($trading_coins_usdt, 0, 3, true);
          }elseif((int)$total_usdt_atg >= 500 && (int)$total_usdt_atg < 1000){ // 5 coins
            $tradebale_parent_coins_usdt = array_slice($trading_coins_usdt, 0, 5, true);
          }elseif((int)$total_usdt_atg >= 1000 && (int)$total_usdt_atg <= 2000){ // 7 coins
            $tradebale_parent_coins_usdt = array_slice($trading_coins_usdt, 0, 6, true);
          }elseif((int)$total_usdt_atg > 2000 && (int)$total_usdt_atg <= 5000){ // 9 coins
            $tradebale_parent_coins_usdt =  array_slice($trading_coins_usdt, 0, 7, true);
          }elseif((int)$total_usdt_atg > 5000 && (int)$total_usdt_atg <= 10000){ // 11 coins
            $tradebale_parent_coins_usdt = array_slice($trading_coins_usdt, 0, 10, true);
          }elseif((int)$total_usdt_atg > 10000){ // 13 coins
            $tradebale_parent_coins_usdt = array_slice($trading_coins_usdt, 0, 14, true);
          }
          // end of usdt getting coins
          $tradeable_coins = array_merge($tradebale_parent_coins_btc,$tradebale_parent_coins_usdt);
          // echo 'Tradeable coins of '.$user_id_db.'<pre>';print_r($tradeable_coins);exit;
          // updating parents base on AI.
          if(count($tradeable_coins) > 0){
            //echo 'hello i am in';exit;
            $last_scanned = $this->mongo_db->converToMongodttime(date('Y-m-d h:i:s'));
            $db->$trading_collection->updateMany(['symbol'=>['$in'=>$tradeable_coins],'admin_id'=>$user_id_db,'parent_status'=>'parent','status'=>['$ne'=>'canceled']],['$set'=>['pause_status'=>'play','pick_parent'=>'yes','sheraz_play_modified_date'=>$last_scanned]]);
            $db->$trading_collection->updateMany(['symbol'=>['$nin'=>$tradeable_coins],'admin_id'=>$user_id_db,'parent_status'=>'parent','status'=>['$ne'=>'canceled']],['$set'=>['pick_parent'=>'no','sheraz_pause_modified_date'=>$last_scanned]]);
            $db->$atg_collection->updateOne(['user_id'=>$user_id_db,'application_mode'=>'live'],['$set'=>['is_parent_paused_by_script'=>1,'last_script_scanned'=>$last_scanned]]);
          }else{
            $db->$atg_collection->updateOne(['user_id'=>$user_id_db,'application_mode'=>'live'],['$set'=>['is_parent_paused_by_script'=>1,'last_script_scanned'=>$last_scanned]]);
          } // end if condition
      }// end foreach main
    }else{
        $db->$atg_collection->updateMany(['user_id'=>['$ne'=>'']],['$set'=>['is_parent_paused_by_script'=>0]]); // making the field 0 again for all the users just to restart the scanning.
    }
  }

  public function rankingCoins($user_id, $exchange) {

    if ($exchange == 'binance') {
      $atg_collection = 'auto_trade_settings';
      $coins_collection = 'coins';
    }elseif ($exchange == 'dg') {
      $atg_collection = 'auto_trade_settings_dg';
      $coins_collection = 'coins_dg';
    }else{
      $atg_collection = 'auto_trade_settings_kraken';
      $coins_collection = 'coins_kraken';
    }
    $db = $this->mongo_db->customQuery();
    $atg_object = $db->$atg_collection->find(['user_id'=>(string)$user_id,'application_mode'=>'live']);
    $atg_array = iterator_to_array($atg_object); 
    $user_coins = $atg_array[0]['step_2']['coins'];
    $category_coins = $db->$coins_collection->find(
        ['category' => ['$exists' => true]],
        ['projection' => ['category' => 1, 'symbol' => 1]]
    );

    $coins_array = iterator_to_array($category_coins);
    usort($coins_array, function($a, $b) {
        return $a['category'] - $b['category'];
    });

    $user_coins_priority = $coins_array;

    return $user_coins_priority;
  }

}



 