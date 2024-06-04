<?php 
$score_avg = 0;
$psum = 0;
$nsum = 0;
$sum = 0;
$x = 0;
$count = 0;

foreach ($news as $key => $value) {
  if ($value['score'] >= 0) {
    $psum = $psum + $value['score'];
  }
  else
  {
    $nsum = $nsum+ $value['score'];
  }
  $count++;
}
$sum = $psum+(-1*($nsum));
$x = $psum/$sum; 
$score_avg = round($x*100);

?>

<style type="text/css">
  label.error {
  /* remove the next line when you have trouble in IE6 with labels in list */
  color: red;
  font-style: italic
}

  .alert-ignore{
    font-size:12px;
    border-color: #b38d41;
  }
  small{
    color:orange;
    font-weight: bold;
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
    background: #ffffff;
    border-color: #373737;
    border-radius: 6px;
    color: #373737;
    font-weight: bold;
}

.btnval1 {
    width: 9%;
    font-size: 11px;
    height: auto;
    padding-left: 3px;
    margin-left: 5px;
    background: #ffffff;
    border-color: #373737;
    border-radius: 6px;
    color: #373737;
    font-weight: bold;
}

.btn-group, .btn-group-vertical {
    position: relative;
    display: inline-block;
    vertical-align: middle;
    padding-bottom: 10px;
}
.close{
  margin-top: -2%;
}
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Add Buy Order</h1>

  <div class="bg-white innerAll border-bottom">
    <ul class="menubar"> 
        <li><a href="<?php echo SURL; ?>admin/buy_orders/">Buy Order Listing</a></li>
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
                <form id="buy_order_form" class="form-horizontal margin-none" method="post" action="<?php echo SURL?>admin/buy_orders/add-buy-order-process" novalidate="novalidate">
                <div class="widget-body"> 
                  
                  <!-- Row -->
                  <div class="row">
                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Coin</label>
                        <select name="coin" id="coin" class="form-control" required>
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
                        <input type="text" name="price" value="<?php echo $market_value; ?>" id="purchased_price" required="required" class="form-control">
                        <input type="hidden" name="price11" value="<?php echo $market_value; ?>" id="purchased_price_hidden" required="required" class="form-control">
                      </div>
                    </div> 
                    <div class="col-md-12" id="pricealert">
                      
                    </div>
                    <div class="col-md-12"> 
                      <div class="form-group col-md-12">
                        <label class="control-label">Quantity</label>
                        <input type="text" id="quantity" name="quantity" required="required" class="form-control">
                      </div>
                    </div>
                  </div> 
                  <div class="col-md-12" id="quantitydv">
                      
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
                        <div class="col-md-12" id="sellpercent">
                      
                        </div>
                        <div class="col-md-12">
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
                      </div>
                      
                      <div class="col-md-12" id="sell_profit_price_div"> 
                        <div class="form-group col-md-12">
                          <label class="control-label">Sell Price</label>
                          <input type="text" name="sell_profit_price" id="sell_profit_price" required="required" class="form-control">
                        </div>
                        <div class="col-md-12" id="sell_price">
                      
                        </div>
                      </div> 
             
                      <div class="col-md-12"> 
                        <div class="form-group col-md-12">
                          <label class="control-label">Order Type</label>
                          <select name="sell_order_type" class="form-control">
                            <option value="market_order">Market Order</option>
                            <option value="limit_order">Limit Order</option>
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
                          <input type="text" id= "loss_percentage" name="loss_percentage" required="required" class="form-control">
                        </div>
                        <div class="col-md-12">
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

                  </div> 

                  <!-- // Row END -->
                  <hr class="separator">
                  
                  <!-- Form actions -->
                  <div class="form-actions">
                    <button class="btn btn-success" id="add_order_p" type="submit"><i class="fa fa-check-circle"></i> Add Order </button>
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
          <div class="col-md-6 text-center">
            <div class="widget widget-inverse" style="/*transform: scale(0.7)*/;">
              <div class="" style="text-align: center;background: #aaaaaa;">
                <div class="row" style="padding:10px;">
                <div class="meater_candle">
                      <div class="goal_meater_main">
                            <div class="goal_meater_img">
                                <div class="degits"><?php echo $score_avg; ?></div>
                                <div pin-value="<?php echo $score_avg; ?>" class="goal_pin"></div>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
         
          <div class="col-md-6">
            <div class="widget">
              <div class="widget-body list">
                <ul>
                  <li>
                    <span><b>Available Bitcoin Quantity</b></span>
                    <span class="count" id="bitcoin">0</span>  
                  </li>
                </ul>
              </div>
            </div>
          </div>    
        </div> 
  </div>
</div>

<script>
//
$("body").on('keyup','#purchased_price',function() {
  var price = $('#purchased_price').val();
  var price_market = $('#purchased_price_hidden').val();
  if (price > price_market) {   
    $('#pricealert').html('<div class="alert alert-warning alert-dismissible alert-ignore" role="alert">Price you are setting is greater then the Current Market <small>Click Close Button to Ignore</small>.<button type="button" class="close"><span aria-hidden="true">&times;</span></button></div>');
  }
  else
  {
     $('#pricealert').find(".alert").hide();
  }

  if($('.alert').is(':visible')){
      $('#add_order_p').attr('disabled', 'disabled');
    }
    else{
      $('#add_order_p').removeAttr("disabled");
    }

});
//sell_profit_percent
$("body").on('keyup','#sell_profit_percent',function() {
  var price = $('#sell_profit_percent').val();
  if (price > 100) {   
      $('#sellpercent').html('<div class="alert alert-warning alert-dismissible alert-ignore" role="alert">You are setting The profit %age greater then 100<small>Click Close Button to Ignore</small>.<button type="button" class="close"><span aria-hidden="true">&times;</span></button></div>');
  }
  else
  {
     $('#sellpercent').find(".alert").hide();
  }

  if($('.alert').is(':visible')){
      $('#add_order_p').attr('disabled', 'disabled');
    }
    else{
      $('#add_order_p').removeAttr("disabled");
    }

});

$("body").on('keyup','#quantity',function() {
  var price = $('#quantity').val();
  if (price < 250) {   
       $('#quantitydv').html('<div class="alert alert-warning alert-dismissible alert-ignore" role="alert">Your Quantity is less then the Expected Quantity <small>Click Close Button to Ignore</small>.<button type="button" class="close"><span aria-hidden="true">&times;</span></button></div>');
  }
  else
  {
     $('#quantitydv').find(".alert").hide();
  }

  if($('.alert').is(':visible')){
      $('#add_order_p').attr('disabled', 'disabled');
    }
    else{
      $('#add_order_p').removeAttr("disabled");
    }
});

$("body").on('keyup','#sell_profit_price',function() {
  var price = $('#purchased_price').val();
  var price_market = $('#sell_profit_price').val();
  if (price > price_market) {   
        $('#sell_price').html('<div class="alert alert-warning alert-dismissible alert-ignore" role="alert">You are setting the Sell Price Less then The Buy Price <small>Click Close Button to Ignore</small>.<button type="button" class="close"><span aria-hidden="true">&times;</span></button></div>');
  }
  else
  {
     $('#sell_price').find(".alert").hide();
  }

  if($('.alert').is(':visible')){
      $('#add_order_p').attr('disabled', 'disabled');
    }
    else{
      $('#add_order_p').removeAttr("disabled");
    }
});

function autoload_market_buy_price()
{

  var coin = $('#coin').val();
  $.ajax({
      type:'POST',
      url:'<?php echo SURL?>admin/dashboard/set_buy_price',
      data: {coin:coin},
      success:function(response){
       
          $('#purchased_price_hidden').val(response);
       
          setTimeout(function() {
                autoload_market_buy_price();
          }, 3000);
       
      }
    });
}

function autoload_bitcoin_price()
{
  $.ajax({
      type:'POST',
      url:'<?php echo SURL?>admin/bitcoin_balance',
      data: "",
      success:function(response){
       
          $('#bitcoin').html(response);
       
          setTimeout(function() {
                autoload_bitcoin_price();
          }, 3000);
       
      }
    });
}
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
autoload_market_buy_price();
autoload_bitcoin_price();

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

$("body").on("change","#coin",function(e){
   var coin = $('#coin').val();
  $.ajax({
      type:'POST',
      url:'<?php echo SURL?>admin/dashboard/set_buy_price',
      data: {coin:coin},
      success:function(response){
       
          $('#purchased_price_hidden').val(response);
          $('#purchased_price').val(response);
          
      }
    });
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
<script type="text/javascript" src="<?php echo ASSETS;?>js/jquery.validate.js"></script>
<script type="text/javascript">
  $("#buy_order_form").validate();
</script>
<script type="text/javascript">
  
  function meaterfunction(){

var pin_val = jQuery(".goal_pin").attr("pin-value");
  
  var actual_pin = 2.075 * pin_val;
  
  var deg_rat = 166 + actual_pin;
  //alert(deg_rat);
  
  jQuery(".goal_pin").css({
  '-webkit-transform' : 'rotate('+deg_rat+'deg)',
     '-moz-transform' : 'rotate('+deg_rat+'deg)',  
      '-ms-transform' : 'rotate('+deg_rat+'deg)',  
       '-o-transform' : 'rotate('+deg_rat+'deg)',  
          'transform' : 'rotate('+deg_rat+'deg)',  
               'zoom' : 1

    });
  
}

jQuery(document).ready(function(e) {
  meaterfunction();
  /*jQuery("body").on("keyup",".Meater",function(){
    var inmet = jQuery(this).val();
    jQuery(".goal_pin").attr("pin-value",inmet);
    jQuery(".degits").text(inmet);
    meaterfunction();
  });*/
  
   /* jQuery("body").on("click",".Meater",function(){
    var inmet = jQuery(this).val();
    jQuery(".goal_pin").attr("pin-value",inmet);
    jQuery(".degits").text(inmet);
    meaterfunction();
  });*/
  
});
</script>
<script type="text/javascript">

  $("body").on("click",".close",function(){
    $(this).parent().hide()
     if($('.alert').is(':visible')){
      $('#add_order_p').attr('disabled', 'disabled');
    }
    else{
      $('#add_order_p').removeAttr("disabled");
    }

  });
  $(document).ready(function(){
    if($('.alert').is(':visible')){
      $('#add_order_p').attr('disabled', 'disabled');
    }
    else{
      $('#add_order_p').removeAttr("disabled");
    }
  });
</script>