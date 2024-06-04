<div id="content">
  <h1 class="content-heading bg-white border-bottom">Edit Order</h1>

  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
        <li><a href="<?php echo SURL; ?>admin/dashboard/orders-listing">Sell Order Listing</a></li>
        <li class="active"><a href="#">Edit Order</a></li>
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

        <?php if($order_arr['status'] !='new' && $order_arr['status'] !='error'){?>
        <div class="alert alert-danger">Order is Already <b><?php echo strtoupper($order_arr['status']); ?></b> you can not update this Order</div>
        <?php } ?>  
  	
        <div class="row">
          <div class="col-md-6"> 
            <div class="widget widget-inverse"> 
                <form class="form-horizontal margin-none" method="post" action="<?php echo SURL?>admin/dashboard/edit-order-process" novalidate="novalidate">
                <div class="widget-body"> 
                  
                  <!-- Row -->
                  <div class="row">
                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Coin</label>
                        <select name="coin" class="form-control">
                          <?php 
                           if(count($coins_arr)>0){
                           for($i=0; $i<count($coins_arr); $i++){

                           if($order_arr['symbol']==$coins_arr[$i]['symbol']){
                           ?> 
                           <option value="<?php echo $coins_arr[$i]['symbol'];?>" selected><?php echo $coins_arr[$i]['symbol'];?></option>
                          <?php }
                           }
                          } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Purchased Price</label>
                        <input type="text" name="purchased_price" id="purchased_price" value="<?php echo $order_arr['purchased_price']; ?>" required="required" class="form-control" readonly>
                      </div>
                    </div> 
                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Quantity</label>
                        <input type="text" name="quantity" value="<?php echo $order_arr['quantity']; ?>" required="required" class="form-control" readonly>
                      </div>
                    </div>
                  </div>  

                  <div class="row">
                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Profit Type</label>
                        <select class="form-control" name="profit_type" id="profit_type">
                          <option value="percentage" <?php if($order_arr['profit_type'] =='percentage'){ ?>selected<?php } ?> >Percentage</option>
                          <option value="fixed_price" <?php if($order_arr['profit_type'] =='fixed_price'){ ?>selected<?php } ?>>Fixed Price</option>
                        </select>
                      </div>
                    </div> 
                  </div>

                  <?php 
                  if($temp_sell_arr['profit_type'] ==''){ 
                    $style1 = 'style="display:block;"';
                    $style2 = 'style="display:none;"';
                  }elseif($order_arr['profit_type'] =='percentage'){ 
                    $style1 = 'style="display:block;"';
                    $style2 = 'style="display:none;"';
                  }else{
                    $style1 = 'style="display:none;"';
                    $style2 = 'style="display:block;"';
                  }
                  ?>

                  <div class="row">
                    <div class="col-md-12" id="sell_profit_percent_div" <?php echo $style1;?>> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Sell Profit (%)</label>
                        <input type="text" name="sell_profit_percent" id="sell_profit_percent" value="<?php echo $order_arr['sell_profit_percent']; ?>" required="required" required="required" class="form-control">
                      </div>
                    </div> 
                    <div class="col-md-12" id="sell_profit_price_div" <?php echo $style2;?>> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Sell Price</label>
                        <input type="text" name="sell_profit_price" id="sell_profit_price" value="<?php echo $order_arr['sell_profit_price']; ?>" required="required" required="required" class="form-control">
                      </div>
                    </div> 
                  </div>


                  <div class="row"> 
                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Order Type</label>
                        <select name="order_type" class="form-control">
                          <option value="market_order" <?php if($order_arr['order_type'] == 'market_order')
                        { ?> selected <?php } ?>>Market Order</option>
                          <option value="limit_order" <?php if($order_arr['order_type'] =='limit_order')
                        { ?> selected <?php } ?> >Limit Order</option>
                        </select>
                      </div>
                    </div>
                  </div> 

                  
                  <div class="row"> 
                    <div class="col-md-12"> 
                      <div class="form-check">
                        <?php if($order_arr['trail_check'] == 'yes')
                        { $selected = 'checked';}
                        ?>
                        <input type="checkbox" class="form-check-input" value="trail_sell" id="trail_check" name="trail_check" <?php echo $selected; ?>>
                        <label class="form-check-label" for="trail_check">Trail Sell</label>
                      </div>
                    </div>
                  </div>
                    
                  <div class="row" id="trail_sell_data" style="display:none;"> 

                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Trail Interval</label>
                        <input type="text" name="trail_interval" value = "<?php echo $order_arr['trail_interval'] ?>" required="required" class="form-control">
                      </div>
                    </div> 
                     
                  </div> 



                  <div class="row"> 
                    <div class="col-md-12"> 
                      <div class="form-check">
                        <?php if($order_arr['stop_loss'] == 'yes')
                        { $selected22 = 'checked';}
                        ?>
                        <input type="checkbox" class="form-check-input" value="yes" id="stop_loss" name="stop_loss" <?php echo $selected22; ?>>
                        <label class="form-check-label" for="stop_loss">Stop Loss</label>
                      </div>
                    </div>
                  </div>  

                  <div class="row" id="stop_loss_data" style="display:none;">

                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Loss Percentage (%)</label>
                        <input type="text" name="loss_percentage" value="<?php echo $order_arr['loss_percentage'] ?>" required="required" class="form-control">
                      </div>
                    </div> 
                    
                  </div> 


                 
                  <!-- // Row END -->
                  <hr class="separator">
                  
                  <!-- Form actions -->
                  <?php if($order_arr['status'] =='new' || $order_arr['status'] =='error'){?>
                  <div class="form-actions">
                    <input type="hidden" name="id" value="<?php echo $order_arr['_id']; ?>">
                    <input type="hidden" name="buy_order_id" value="<?php echo $order_arr['buy_order_id']; ?>">
                    <button class="btn btn-success" type="submit"><i class="fa fa-check-circle"></i> Update Order </button>
                  </div>
                  <?php } ?>
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

          <?php if(count($order_history_arr)>0){ ?>
          <div class="col-md-6"> 
            <div class="widget">
              <div class="widget widget-inverse widget-scroll">
                        <div class="widget-head" style="height:46px;">               
                          <h4 class="heading" style=" padding-top: 3px;">Order History Log</h4>
                        </div>
                        <div class="widget-body padding-none">
                            <div id="response_market_trading" style="overflow: hidden; outline: none;" tabindex="5000">
                              <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><strong>Message</strong></th>
                                            <th><strong>Created Date</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      $i=0;
                                      foreach ($order_history_arr as $value) {
                                      $i++; 
                                      ?>
                                      <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $value['log_msg']; ?></td>
                                        <td><?php echo $value['created_date']; ?></td>
                                      </tr>
                                      <?php } ?>  
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
          </div>
          <?php } ?>
           
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


$(document).ready(function(){
    if (document.getElementById('trail_check').checked) 
    {
        $('#trail_sell_data').show();
    }
    else
    {
        $('#trail_sell_data').hide();
    }

    if (document.getElementById('stop_loss').checked) 
    {
        $('#stop_loss_data').show();
    }
    else
    {
        $('#stop_loss_data').hide();
    }
});

$("body").on("change","#trail_check",function(e){
    if($(this).is(':checked'))
    {
      $('#trail_sell_data').show();
    }
    else
    {
      $('#trail_sell_data').hide();
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