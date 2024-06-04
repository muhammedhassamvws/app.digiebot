<div id="content">
	<style>
.notification_popup {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.7);
    z-index: 99999;
    display:none;
}

.notification_popup_iner {
    padding: 25px;
    background: #fff;
    margin: auto;
    height: 250px;
    width: 100%;
    max-width: 500px;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    position: absolute;
    box-shadow: 0 0 59px 24px rgba(0,0,43,0.2);
    border-radius: 15px;
}

.conternta-pop h2 {
    font-size: 19px;
    text-align: center;
    font-weight: bold;
    color: #000;
    margin-top: 33px !important;
    float: left;
    width: 100%;
}

.conternta-pop h2 codet {
    color: #c3a221;
}

.npclss {
    position: absolute;
    right: 15px;
    top: 15px;
    height: 30px;
    width: 30px;
    text-align: center;
    border: 1px solid #ccc;
    background: #fff;
    border-radius: 22px;
    padding-top: 5px;
    cursor: pointer;
}    
    </style>
<!--     <div class="notification_popup">
        <div class="notification_popup_iner">
        	<div class="npclss">X</div>
            <div id="popup_text" class="conternta-pop">
              <h2>
              WARNING: You have less than <codet>$10 USD in BNB </codet> balance. To reduce your Binance trading fees, please Maintain a Minimum of <codet>$10 USD in BNB </codet> coin balance in your Binance account.Your digiebot trading account will still trade if you do not have any BNB, but your Binance trading fees will be slightly higher. Binance Fee Schedule https://www.binance.com/en/fee/schedule
              </h2>
            </div>
        </div>
    </div> -->

  <div class="innerAll spacing-x2">
    <div class="row">
      <div class="col-md-12">
        <div class="widget">
          <div class="widget-body padding-none">
            <div class="row row-merge">
              <div class="col-sm-4">
                <div class="back">
                  <div class="widget widget-inverse widget-scroll" data-scroll-height="700px">
                    <div class="widget-head">

                      <?php if(isset($_GET['market_value']) && $_GET['market_value'] !=""){ ?>
                      <input type="hidden" value="<?php echo $market_value; ?>" id="market_value">
                      <?php } ?>
                      
                      <h4 class="heading" id="response_market_value_buy" style="color: #ff5d5d;font-weight: bold;">Buy (<?php echo number_format($market_value, 7, '.', ''); ?>)</h4>
                    </div>
                    <div class="widget-body padding-none">
                      <div id="response_buy_trade">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                  <td><strong>Price(BTC)</strong></td>
                                  <td><strong>Amount(<?php echo $currncy;?>)</strong></td>
                                  <td><strong>Total(BTC)</strong></td>
                                </tr>
                            </thead>
                          	<tbody>
                            <?php 
                            if(count($market_buy_depth_arr)>0){
                              foreach ($market_buy_depth_arr as $key => $value) {

                              $lenth22 =  strlen(substr(strrchr($value['price'], "."), 1));
                              if($lenth22==6){
                                $price = $value['price'].'0';
                              }else{
                                $price = $value['price'];
                              } 

                            ?>

                              <tr>
                                <td><?php echo number_format($price,8,".","");?></td>
                                <td><?php echo number_format($value['quantity'], 2, '.', '');?></td>
                                <td>
                                <?php 
                                  $total_price = $value['price'] * $value['quantity'];
                                  number_format($total_price, 7, '.', '');
                                ?>
                                </td>
                              </tr>
                               
                            <?php }
                            } 
                            ?> 
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-sm-4">
                <div class="back">
                  <div class="widget widget-inverse widget-scroll" data-scroll-height="700px">
                    <div class="widget-head">
                      <h4 class="heading" id="response_market_value_sell" style="color: #72ff85;
    font-weight: bold;">Sell (<?php echo number_format($market_value, 7, '.', ''); ?>)</h4>
                    </div>
                    <div class="widget-body padding-none">
                      <div id="response_sell_trade">
                       <table class="table table-condensed">
                            <thead>
                                <tr>
                                  <td><strong>Price(BTC)</strong></td>
                                  <td><strong>Amount(<?php echo $currncy;?>)</strong></td>
                                  <td><strong>Total(BTC)</strong></td>
                                </tr>
                            </thead>
                          	<tbody>
                              <?php 
                              if(count($market_sell_depth_arr)>0){
                                foreach ($market_sell_depth_arr as $key => $value2) { 

                                $lenth33 =  strlen(substr(strrchr($value2['price'], "."), 1));
                                if($lenth33==6){
                                  $price22 = $value2['price'].'0';
                                }else{

                                  $price22 = $value2['price'];
                                } 


                              ?>

                                <tr>
                                  <td><?php echo number_format($price22,8,".","");?></td>
                                  <td><?php echo number_format($value2['quantity'], 2, '.', ''); ?></td>
                                  <td>
                                  <?php 
                                    $total_price2 = $value2['price'] * $value2['quantity'];
                                    number_format($total_price2, 7, '.', '');
                                  ?>  
                                  </td>
                                </tr>
                                 
                              <?php }
                              } 
                              ?> 
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-sm-4">
                <div class="back">
                  <div class="widget widget-inverse widget-scroll" data-scroll-height="700px">
                    <div class="widget-head">
                      <h4 class="heading">Trade History</h4>
                    </div>
                    <div class="widget-body padding-none">
                      <div id="response_market_history">
                       <table class="table table-condensed">
                            <thead>
                                <tr>
                                  <td><strong>Price(BTC)</strong></td>
                                  <td><strong>Amount(<?php echo $currncy;?>)</strong></td>
                                  <td><strong>Total(BTC)</strong></td>
                                </tr>
                            </thead>
                          	<tbody>
                              <?php 
                              if(count($market_history_arr)>0){
                                foreach ($market_history_arr as $key => $value3) { 

                                $maker = $value3['maker'];
                                if($maker=='true'){
                                  $color="red";
                                }else{
                                  $color="green";
                                }
                                ?>
                                <tr style="color:<?php echo $color; ?>">
                                  <td><?php echo number_format($value3['price'], 8, '.', ''); ?></td>
                                  <td><?php echo number_format($value3['quantity'], 2, '.', '');?></td>
                                  <td>
                                  <?php 
                                  $total_price3 = $value3['price'] * $value3['quantity'];
                                  number_format($total_price3, 7, '.', '');
                                  ?>
                                  </td>
                                </tr>                                
                              <?php }
                              } 
                              ?> 
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
        </div>
        
      </div>
      
    </div>
  </div>
</div>
<!-- // Content END -->

<script type="text/javascript">

$(document).ready(function(e) {
  $("body").on("click",".npclss",function(){
    $(".notification_popup").hide();
  });

  var is_bnb_balance = "<?php echo $is_bnb_balance; ?>";
  if(is_bnb_balance =='NO'){
    $(".notification_popup").show();
  }
  
});


  var call_statusinterval;
  var auto_refresh;
 
  //auto_refresh = setInterval(autoload_trading_data, 2000);

  function autoload_trading_data(){

    var market_value = $("#market_value").val();
  
    $.ajax({
      type:'POST',
      url:'<?php echo SURL?>admin/dashboard/autoload_trading_data',
      data: {market_value:market_value},
      success:function(response){

        var split_response = response.split('|');

        if(split_response[0] !=""){
          $('#response_sell_trade').html(split_response[0]);
          $('#response_market_value_sell').html('Sell ('+split_response[3]+')');
        }
        if(split_response[1] !=""){
          $('#response_buy_trade').html(split_response[1]);
          $('#response_market_value_buy').html('Buy ('+split_response[3]+')');
        }
        if(split_response[2] !=""){
          $('#response_market_history').html(split_response[2]);
        }
        
        setTimeout(function() {
            autoload_trading_data();
        }, 1000);
        //autoload_trading_data();
      }
    });

  }//end autoload_trading_data() 

  autoload_trading_data();
  
</script>