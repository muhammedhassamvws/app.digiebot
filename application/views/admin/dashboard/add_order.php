<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
<style type="text/css">
  label.error {
  /* remove the next line when you have trouble in IE6 with labels in list */
  color: red;
  font-style: italic
}
  .btnbtn{
    padding-left:0px;
  }

  .btnval {
    width: 9%;
    font-size: 11px;
    height: auto;
    padding-left: 3px;
    margin-left: 5px;
    background: darkgrey;
    border-color: azure;
    border-radius: 6px;
    color:white;
}

.btnval1 {
    width: 9%;
    font-size: 11px;
    height: auto;
    padding-left: 3px;
    margin-left: 5px;
    background: darkgrey;
    border-color: azure;
    border-radius: 6px;
    color:white;
}
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Add Order</h1>

  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
        <li><a href="<?php echo SURL; ?>admin/dashboard/orders-listing">Sell Order Listing</a></li>
        <li class="active"><a href="#">Add Order</a></li>
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
                <form id="sell_order_form" class="form-horizontal margin-none" method="post" action="<?php echo SURL?>admin/dashboard/add-order-process" novalidate="novalidate">
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
                        <input type="text" name="purchased_price" id="purchased_price" value="<?php echo $order_arr['market_value'];?>" required="required" class="form-control" <?php if($buy_order_check=='yes'){?> readonly <?php } ?> >
                      </div>
                    </div> 
                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Quantity</label>
                        <input type="text" name="quantity" value="<?php echo $order_arr['quantity'];?>" required="required" class="form-control" <?php if($buy_order_check=='yes'){?> readonly <?php } ?>>
                      </div>
                    </div>
                  </div>  

                  <div class="row">
                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Profit Type</label>
                        <select class="form-control" name="profit_type" id="profit_type">
                          <option value="percentage">Percentage</option>
                          <option value="fixed_price">Fixed Price</option>
                        </select>
                      </div>
                    </div> 
                  </div>

                  <div class="row">
                    <div class="col-md-12" id="sell_profit_percent_div"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Sell Profit (%)</label>
                        <input type="text" name="sell_profit_percent" id="sell_profit_percent" required="required" class="form-control">
                      </div>
                      <span class="btn-group btn-group-xs">
                            <span class ="btnbtn"><a class="btn btnval" id="btn1" data-id = "1">1%</a></span>
                            <span class ="btnbtn"><a class="btn btnval" id="btn2" data-id = "2">2%</a></span>
                            <span class ="btnbtn"><a class="btn btnval" id="btn3" data-id = "3">3%</a></span>
                            <span class ="btnbtn"><a class="btn btnval" id="btn5" data-id = "5">5%</a></span>
                            <span class ="btnbtn"><a class="btn btnval" id="btn10" data-id = "10">10%</a></span>
                            <span class ="btnbtn"><a class="btn btnval" id="btn15" data-id = "15">15%</a></span>
                            <span class ="btnbtn"><a class="btn btnval" id="btn20" data-id = "20">20%</a></span>
                            <span class ="btnbtn"><a class="btn btnval" id="btn25" data-id = "25">25%</a></span>
                        </span>
                    </div> 
                    <div class="col-md-12" id="sell_profit_price_div"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Sell Price</label>
                        <input type="text" name="sell_profit_price" id="sell_profit_price" required="required" class="form-control">
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
                        <input type="checkbox" class="form-check-input" value="trail_sell" id="trail_check" name="trail_check">
                        <label class="form-check-label" for="trail_check">Trail Sell</label>
                      </div>
                    </div>
                  </div>  

                  <div class="row" id="trail_sell_data" style="display:none;">

                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Trail Interval</label>
                        <input type="text" name="trail_interval" required="required" class="form-control">
                      </div>
                    </div> 
                    
                  </div> 




                  <div class="row"> 
                    <div class="col-md-12"> 
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="yes" id="stop_loss" name="stop_loss">
                        <label class="form-check-label" for="stop_loss">Stop Loss</label>
                      </div>
                    </div>
                  </div>  

                  <div class="row" id="stop_loss_data" style="display:none;">

                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Loss Percentage (%)</label>
                        <input type="text" name="loss_percentage" id="loss_percentage" required="required" class="form-control">
                      </div>
                      <span class="btn-group btn-group-xs">
                            <span class ="btnbtn"><a class="btn btnval1" id="btn1" data-id = "1">1%</a></span>
                            <span class ="btnbtn"><a class="btn btnval1" id="btn2" data-id = "2">2%</a></span>
                            <span class ="btnbtn"><a class="btn btnval1" id="btn3" data-id = "3">3%</a></span>
                            <span class ="btnbtn"><a class="btn btnval1" id="btn5" data-id = "5">5%</a></span>
                            <span class ="btnbtn"><a class="btn btnval1" id="btn10" data-id = "10">10%</a></span>
                            <span class ="btnbtn"><a class="btn btnval1" id="btn15" data-id = "15">15%</a></span>
                            <span class ="btnbtn"><a class="btn btnval1" id="btn20" data-id = "20">20%</a></span>
                            <span class ="btnbtn"><a class="btn btnval1" id="btn25" data-id = "25">25%</a></span>
                        </span>
                    </div> 
                    
                  </div> 
                 
                  <!-- // Row END -->
                  <hr class="separator">
                  
                  <!-- Form actions -->
                  <div class="form-actions">
                    <input type="hidden" name="buy_order_check" value="<?php echo $buy_order_check;?>">
                    <input type="hidden" name="buy_order_id" value="<?php echo $order_arr['_id'];?>">
                    <input type="hidden" name="buy_order_binance_id" value="<?php echo $order_arr['binance_order_id'];?>">
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
$("body").on('keyup','#sell_profit_percent',function() {
  var price = $('#sell_profit_percent').val();
  if (price > 100) {   
      $.alert({
    title: '<span><i style="font-size:56px;" class="fa fa-warning text-danger"></i></span><span style="padding-left:10px">Attention!</span>',
    content: 'you are setting the profit percentage more then 100 Are you sure You want to continue!!!',
    });
  }
});

$("body").on('focusout','#sell_profit_price',function() {
  var price = $('#purchased_price').val();
  var price_market = $('#sell_profit_price').val();
  if (price > price_market) {   
      $.alert({
    title: '<span><i style="font-size:56px;" class="fa fa-warning text-danger"></i></span><span style="padding-left:10px">Attention!</span>',
    content: 'you are setting the sell price less then the buy price of the order Are you sure You want to continue!!!',
    });
  }
});
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
<script type="text/javascript">
  $('body').on('click','.btnval', function(){
      var data = $(this).attr('data-id');
//      alert(data);
    $('#sell_profit_percent').val(data);

    var sell_profit_percent = data;
    var purchased_price = $("#purchased_price").val();

    $.ajax({
    'url': '<?php echo SURL ?>admin/dashboard/convert_price',
    'type': 'POST', //the way you want to send data to your URL
    'data': {purchased_price:purchased_price,sell_profit_percent: sell_profit_percent},
    'success': function (response) { //probably this request will return anything, it'll be put in var "data"
      
      $("#sell_profit_price").val(response);
      $('#sell_profit_price_div').show();
    }
  });
  });
</script>


<script type="text/javascript">
  $('body').on('click','.btnval1', function(){
      var data = $(this).attr('data-id');
//      alert(data);
    $('#loss_percentage').val(data);
  });
</script>
<script type="text/javascript">
  $("#sell_order_form").validate();
</script>