<div id="content">
  <h1 class="content-heading bg-white border-bottom">Add Buy Order</h1>

  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
        <li><a href="<?php echo SURL; ?>admin/dashboard/buy-orders-listing">Buy Order Listing</a></li>
        <li class="active"><a href="#">Add Buy Order</a></li>
    </ul>
  </div>
  
  <div class="innerAll spacing-x2">

        <?php
        if($this->session->flashdata('err_message')){
        ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('err_message'); ?></div>
        <?php
        }
        if($this->session->flashdata('ok_message')){
        ?>
        <div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('ok_message'); ?></div>
        <?php 
        }
        ?>   
  	
        <div class="row">
          <div class="col-md-6"> 
            <div class="widget widget-inverse"> 
                <form class="form-horizontal margin-none" method="post" action="<?php echo SURL?>admin/dashboard/add-buy-order-process" novalidate="novalidate">
                <div class="widget-body"> 
                  
                  <!-- Row -->
                  <div class="row">
                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Coin</label>
                        <select name="coin" class="form-control" required>
                          <?php 
                           if(count($coins_arr)>0){
                           for($i=0; $i<count($coins_arr); $i++){
                           ?> 
                          <option value="<?php echo $coins_arr[$i]['symbol'];?>" <?php if($this->session->userdata('global_symbol') == $coins_arr[$i]['symbol']){ ?> selected <?php } ?>><?php echo $coins_arr[$i]['symbol'];?></option>
                          <?php }
                          } ?>
                        </select>
                      </div>
                    </div>
                  </div>


                  <div class="row">
                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Price</label>
                        <input type="text" name="price" id="purchased_price" required="required" class="form-control">
                      </div>
                    </div> 
                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Quantity</label>
                        <input type="text" name="quantity" required="required" class="form-control">
                      </div>
                    </div>
                  </div> 

                  <div class="row"> 
                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Order Type</label>
                        <select name="order_type" class="form-control">
                          <option value="market_order">Market Order</option>
                          <option value="limit_order">Limit Order</option>
                        </select>
                      </div>
                    </div>
                  </div> 


                  <div class="row"> 
                    <div class="col-md-12"> 
                      <div class="form-check">
                          <input type="checkbox" class="form-check-input" value="trail_buy" id="trail_check" name = "trail_check">
                          <label class="form-check-label" for="trail_check">Trail Buy</label>
                      </div>
                    </div>
                  </div> 

               
                  <div class="col-md-12" id="trail_buy_data" style="display:none;"> 
                    <div class="form-group col-md-12">
                      <label class="control-label">Trail Interval</label>
                      <input type="text" name="trail_interval" required="required" class="form-control">
                    </div>
                  </div> 
                  


                  <div class="row"> 
                    <div class="col-md-12"> 
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="yes" id="auto_sell" name = "auto_sell">
                        <label class="form-check-label" for="auto_sell">Auto Sell</label>
                      </div>
                    </div>
                  </div>

                  <div id="auto_sell_data" style="display:none;">
             
                      <div class="col-md-12"> 
                        <div class="form-group col-md-12">
                          <label class="control-label">Profit Type</label>
                          <select class="form-control" name="profit_type" id="profit_type">
                            <option value="percentage">Percentage</option>
                            <option value="fixed_price">Fixed Price</option>
                          </select>
                        </div>
                      </div> 

                      <div class="col-md-12" id="sell_profit_percent_div"> 
                        <div class="form-group col-md-12">
                          <label class="control-label">Sell Profit (%)</label>
                          <input type="text" name="sell_profit_percent" id="sell_profit_percent" required="required" class="form-control">
                        </div>
                      </div>

                      <div class="col-md-12" id="sell_profit_price_div"> 
                        <div class="form-group col-md-12">
                          <label class="control-label">Sell Price</label>
                          <input type="text" name="sell_profit_price" id="sell_profit_price" required="required" class="form-control">
                        </div>
                      </div> 
             
                      <div class="col-md-12"> 
                        <div class="form-group col-md-12">
                          <label class="control-label">Order Type</label>
                          <select name="sell_order_type" class="form-control">
                            <option value="limit_order">Limit Order</option>
                            <option value="market_order">Market Order</option>
                          </select>
                        </div>
                      </div>
             
                      <div class="col-md-12"> 
                        <div class="form-check">
                          <input type="checkbox" class="form-check-input" value="trail_sell" id="trail_check22" name="sell_trail_check">
                          <label class="form-check-label" for="trail_check22">Trail Sell</label>
                        </div>
                         <br>
                      </div>
                    
                      <div class="col-md-12" id="trail_sell_data" style="display:none;"> 
                        <div class="form-group col-md-12">
                          <label class="control-label">Trail Interval</label>
                          <input type="text" name="sell_trail_interval" required="required" class="form-control">
                        </div>
                      </div> 
                     

                     
                      <div class="col-md-12"> 
                        <div class="form-check">
                          <input type="checkbox" class="form-check-input" value="yes" id="stop_loss" name="stop_loss">
                          <label class="form-check-label" for="stop_loss">Stop Loss</label>
                        </div>
                      </div>
                     

                      <div class="col-md-12" id="stop_loss_data" style="display:none;"> 
                        <div class="form-group col-md-12">
                          <label class="control-label">Loss Percentage (%)</label>
                          <input type="text" name="loss_percentage" required="required" class="form-control">
                        </div>
                      </div> 

                  </div> 

                  <!-- // Row END -->
                  <hr class="separator">
                  
                  <!-- Form actions -->
                  <div class="form-actions">
                    <button class="btn btn-success" type="submit"><i class="fa fa-check-circle"></i> Add Order </button>
                  </div>
                  <!-- // Form actions END --> 
                  
                </div>
                </form>
            </div>  
          </div>

          <div class="col-md-6"> 
            <div class="widget">
              <div class="widget-body list" id="response_market_statistics">
                <ul>
                  <li>
                    <span><b>Current Market</b></span>
                    <span class="count"><?php echo $market_value; ?></span>
                  </li>
                  <li>
                    <span><b>In Zone <?php echo ucfirst($type); ?></b></span>
                    <span class="count"><?php echo ucfirst($in_zone); ?></span>
                  </li>
                  <?php if($type =='sell'){ ?>
                  <li>
                    <span><b>Closest Sell Zone</b></span>
                    <span class="count"><?php echo $start_value.' - '.$end_value; ?></span>
                  </li>
                  <?php }else{ ?>
                  <li>
                    <span><b>Closest Buy Zone</b></span>
                    <span class="count"><?php echo $start_value.' - '.$end_value; ?></span>
                  </li>
                  <?php } ?>
                  <li>
                    <span><b>Pressure</b></span>
                    <span class="count">Up</span>
                  </li>
                  <li>
                    <span><b>Available Quantity</b></span>
                    <span class="count">0</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>    
        </div> 

     
     
    
  </div>
</div>

<script>

function autoload_market_statistics(){
    
    $.ajax({
      type:'POST',
      url:'<?php echo SURL?>admin/dashboard/autoload_market_statistics',
      data: "",
      success:function(response){
       
          $('#response_market_statistics').html(response);
       
          setTimeout(function() {
                autoload_market_statistics();
          }, 3000);
       
      }
    });

}//end autoload_market_statistics() 

autoload_market_statistics();


$("body").on("change","#trail_check",function(e){
    if($(this).is(':checked'))
    {
      $('#trail_buy_data').show();
    }
    else
    {
      $('#trail_buy_data').hide();
    }
});


$("body").on("change","#trail_check22",function(e){
    if($(this).is(':checked'))
    {
      $('#trail_sell_data').show();
    }
    else
    {
      $('#trail_sell_data').hide();
    }
});


$("body").on("change","#auto_sell",function(e){
    if($(this).is(':checked'))
    {
      $('#auto_sell_data').show();
    }
    else
    {
      $('#auto_sell_data').hide();
    }
});


$("body").on("change","#stop_loss",function(e){
    if($(this).is(':checked'))
    {
      $('#stop_loss_data').show();
    }
    else
    {
      $('#stop_loss_data').hide();
    }
});

</script>

