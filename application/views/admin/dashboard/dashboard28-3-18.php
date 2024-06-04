<div id="content">
  <h1 class="bg-white content-heading border-bottom">Dashboard</h1>
  <div class="bg-white innerAll border-bottom"> </div>
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
                      
                      <h4 class="heading" id="response_market_value_buy" style="color: #ff5d5d;
    font-weight: bold;">Buy (<?php echo $market_value; ?>)</h4>
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
                                <td><?php echo $price;?></td>
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
    font-weight: bold;">Sell (<?php echo $market_value; ?>)</h4>
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
                                  <td><?php echo $price22; ?></td>
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
                                  <td><?php echo $value3['price'];?></td>
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

  var call_statusinterval;
  var auto_refresh;
 
 // auto_refresh = setInterval(autoload_trading_data, 2000);

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