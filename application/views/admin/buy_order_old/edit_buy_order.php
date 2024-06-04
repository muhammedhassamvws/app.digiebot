<?php
$score_avg = 0;
$psum = 0;
$nsum = 0;
$sum = 0;
$x = 0;
$count = 0;
//echo "<pre>";  print_r($order_arr); exit;
if ($_SERVER['REMOTE_ADDR'] == '101.50.127.131') {
    //echo "<pre>";  print_r($order_arr); exit;
}

foreach ($news as $key => $value) {
    if ($value['score'] >= 0) {
        $psum = $psum + $value['score'];
    } else {
        $nsum = $nsum + $value['score'];
    }
    $count++;
}
$sum = $psum + (-1 * ($nsum));
$x = $psum / $sum;
$score_avg = round($x * 100);

?>
<style type="text/css">
  label.error {
  /* remove the next line when you have trouble in IE6 with labels in list */
  color: red;
  font-style: italic
}
  .btnbtn{
    padding-left:0px;
  }
  .btn-group
  {
    padding-bottom: 5px;
  }

  .alert-ignore{
    font-size:12px;
    border-color: #b38d41;
  }
  small{
    color:orange;
    font-weight: bold;
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
  .btnval2 {
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
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Edit Buy Order</h1>

  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
        <li><a href="<?php echo SURL; ?>admin/buy_orders">Buy Order Listing</a></li>
        <li class="active"><a href="javascript:void(0)">Edit Buy Order</a></li>
    </ul>
  </div>

  <div class="innerAll spacing-x2">

        <?php
if ($this->session->flashdata('err_message')) {
    ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('err_message'); ?></div>
        <?php
}
if ($this->session->flashdata('ok_message')) {
    ?>
        <div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('ok_message'); ?></div>
        <?php
}
?>

        <div class="row">
          <div class="col-md-6">
            <div class="widget widget-inverse">
                <form id="buy_order_form" class="form-horizontal margin-none" method="post" action="<?php echo SURL ?>admin/buy_orders/edit-buy-order-process" novalidate="novalidate">
                <div class="widget-body">

                  <!-- Row -->
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label">Coin</label>
                        <select name="coin" id="coin" class="form-control">
                          <?php
if (count($coins_arr) > 0) {
    for ($i = 0; $i < count($coins_arr); $i++) {
        ?>
                          <option value="<?php echo $coins_arr[$i]['symbol']; ?>" <?php if ($order_arr['symbol'] == $coins_arr[$i]['symbol']) {?> selected <?php }?>><?php echo $coins_arr[$i]['symbol']; ?></option>
                          <?php }
}?>
                        </select>
                      </div>
                    </div>
                  </div>



                  <div class="row">
                   <?php if ($order_arr['parent_status'] != 'parent') {?>

                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label">Price</label>
                        <input type="text" name="price" style="letter-spacing:0.25px;" id="purchased_price" value="<?php echo num($order_arr['price']); ?>" required="required" class="form-control">

                        <input type="hidden" name="price11" value="<?php echo $market_value; ?>" id="purchased_price_hidden" required="required" class="form-control">
												<div class="price_error"></div>
											</div>
                    </div>
                    <?php }?>

                    <div class="col-md-12" id="pricealert">

                    </div>
                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label">Quantity</label>
                        <input type="number" id="quantity" name="quantity" required="required" class="form-control" onchange="setTwoNumberDecimal()" step="any" value="<?php echo $order_arr['quantity'] ?>" />
                        <script type="text/javascript">
                          function setTwoNumberDecimal(event) {
                              $('#quantity').val(parseFloat($('#quantity').val()).toFixed(4));
                          }
                        </script>
												<input type="hidden" id="quantity_check_min" name="quantity_check_min">
                        <input type="hidden" id="quantity_check_max" name="quantity_check_max">
                      </div>
                    </div>
                  </div>


                  <div class="col-md-12" id="quantitydv"></div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label">Order Type</label>
                        <select name="order_type" class="form-control">
                          <option value="market_order" <?php if ($order_arr['order_type'] == 'market_order') {?> selected <?php }?>>Market Order</option>
                          <option value="limit_order" <?php if ($order_arr['order_type'] == 'limit_order') {?> selected <?php }?> >Limit Order</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-check">
                        <?php if ($order_arr['trail_check'] == 'yes') {$selected = 'checked';}
?>
                        <input type="checkbox" class="form-check-input" value="trail_buy" id="trail_check" name = "trail_check" <?php echo $selected ?>>
                        <label class="form-check-label" for="trail_check">Trail Buy</label>
                      </div>
                    </div>
                  </div>

                  <div class="row" id="trail_buy_data" style="display:none;">

                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label">Trail Interval</label>
                        <input type="hidden" name="trail_interval"  value="<?php echo $order_arr['trail_interval'] ?>" required="required" class="form-control">
                      <input type="number" min="0.0" max="2" id="buy_trail_per" name="buy_trail_interval_per" required="required" class="form-control" value="<?php echo $order_arr['buy_trail_percentage'] ?>">
                    </div>
                    <style type="text/css">
                        .btnval3 {
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
                    </style>
                    <div class="col-md-12">
                         <span class="btn-group btn-group-xs">
                            <span class ="btnbtn"><a class="btn btnval3" id="btn1" data-id = "0.1">.1%</a></span>
                            <span class ="btnbtn"><a class="btn btnval3" id="btn2" data-id = "0.2">.2%</a></span>
                            <span class ="btnbtn"><a class="btn btnval3" id="btn3" data-id = "0.3">.3%</a></span>
                            <span class ="btnbtn"><a class="btn btnval3" id="btn5" data-id = "0.4">.4%</a></span>
                            <span class ="btnbtn"><a class="btn btnval3" id="btn10" data-id = "0.5">.5%</a></span>
                            <span class ="btnbtn"><a class="btn btnval3" id="btn15" data-id = "0.6">.6%</a></span>
                            <span class ="btnbtn"><a class="btn btnval3" id="btn20" data-id = "0.7">.7%</a></span>
                            <span class ="btnbtn"><a class="btn btnval3" id="btn26" data-id = "0.8">.8%</a></span>
                            <span class ="btnbtn"><a class="btn btnval3" id="btn27" data-id = "0.9">.9%</a></span>
                            <span class ="btnbtn"><a class="btn btnval3" id="btn28" data-id = "1.0">1%</a></span>
                            <span class ="btnbtn"><a class="btn btnval3" id="btn29" data-id = "2.0">2%</a></span>
                        </span>
                        <script type="text/javascript">
                           $('body').on('click','.btnval3',function(e){
                              //$('.pl').html(parseFloat($(this).val()).toFixed(8));
                              var per = parseFloat($(this).data('id'));
                              var price = parseFloat($("#purchased_price").val());

                              var calculate = (price * per)/100;
                             // var calculate = parseFloat(margin_total).toFixed(8);
                              $('#buy_trail').val(parseFloat(calculate).toFixed(8));
                              $('#buy_trail_per').val(per);
                            });
                        </script>
                      </div>
                    </div>

                  </div>


                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="yes" id="auto_sell" name = "auto_sell" <?php if ($order_arr['auto_sell'] == 'yes') {?> checked <?php }?>>
                        <label class="form-check-label" for="auto_sell">Auto Sell</label>
                      </div>
                    </div>
                  </div>

                  <?php
if ($temp_sell_arr['profit_type'] == '') {
    $style1 = 'style="display:block;"';
    $style2 = 'style="display:none;"';
} elseif ($temp_sell_arr['profit_type'] == 'percentage') {
    $style1 = 'style="display:block;"';
    $style2 = 'style="display:none;"';
} else {
    $style1 = 'style="display:none;"';
    $style2 = 'style="display:block;"';
}
?>

                  <div id="auto_sell_data" style="display:none;">

                      <div class="col-md-12">
                        <div class="form-group col-md-12">
                          <label class="control-label">Profit Type</label>
                          <select class="form-control" name="profit_type" id="profit_type">
                            <option value="percentage" <?php if ($temp_sell_arr['profit_type'] == 'percentage') {?> selected <?php }?>>Percentage</option>
                            <option value="fixed_price" <?php if ($temp_sell_arr['profit_type'] == 'fixed_price') {?> selected <?php }?>>Fixed Price</option>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-12" id="sell_profit_percent_div" <?php echo $style1; ?>>
                        <div class="form-group col-md-12">
                          <label class="control-label">Sell Profit (%)</label>
                          <input type="text" name="sell_profit_percent" id="sell_profit_percent" required="required" class="form-control" value="<?php echo $temp_sell_arr['profit_percent']; ?>">
                        </div>
                        <div class="col-md-12" id="sellpercent">

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

                      <div class="col-md-12" id="sell_profit_price_div" <?php echo $style2; ?>>
                        <div class="form-group col-md-12">
                          <label class="control-label">Sell Price</label>
                          <input type="text" name="sell_profit_price" id="sell_profit_price" required="required" class="form-control" value="<?php echo $temp_sell_arr['profit_price']; ?>">
                        </div>
                      </div>
             			<div class="col-md-12" id="sell_price">

                    	</div>
                      <div class="col-md-12">
                        <div class="form-group col-md-12">
                          <label class="control-label">Order Type</label>
                          <select name="sell_order_type" class="form-control">
                            <option value="market_order" <?php if ($temp_sell_arr['order_type'] == 'market_order') {?> selected <?php }?>>Market Order</option>
                            <option value="limit_order" <?php if ($temp_sell_arr['order_type'] == 'limit_order') {?> selected <?php }?>>Limit Order</option>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-check">
                          <input type="checkbox" class="form-check-input" value="trail_sell" id="trail_check22" name="sell_trail_check" <?php if ($temp_sell_arr['trail_check'] == 'trail_sell') {?> checked <?php }?>>
                          <label class="form-check-label" for="trail_check22">Trail Sell</label>
                        </div>
                         <br>
                      </div>

                      <div id="trail_sell_data" style="display:none;">

                        <div class="col-md-12">
                          <div class="form-group col-md-12">
                            <label class="control-label">Trail Interval</label>
                            <input type="hidden" id="sell_trail_interval" name="sell_trail_interval" required="required" class="form-control" value="<?php echo $temp_sell_arr['trail_interval']; ?>">
                            <input type="number" min="0.0" max="2" id="buy_trail_per" name="buy_trail_interval_per" required="required" class="form-control" value="<?php echo $order_arr['sell_trail_percentage'] ?>">
                          </div>
                        </div>
                        <div class="col-md-12">
                         <span class="btn-group btn-group-xs">
                            <span class ="btnbtn"><a class="btn btnval2" id="btn1" data-id = "0.1">.1%</a></span>
                            <span class ="btnbtn"><a class="btn btnval2" id="btn2" data-id = "0.2">.2%</a></span>
                            <span class ="btnbtn"><a class="btn btnval2" id="btn3" data-id = "0.3">.3%</a></span>
                            <span class ="btnbtn"><a class="btn btnval2" id="btn5" data-id = "0.4">.4%</a></span>
                            <span class ="btnbtn"><a class="btn btnval2" id="btn10" data-id = "0.5">.5%</a></span>
                            <span class ="btnbtn"><a class="btn btnval2" id="btn15" data-id = "0.6">.6%</a></span>
                            <span class ="btnbtn"><a class="btn btnval2" id="btn20" data-id = "0.7">.7%</a></span>
                            <span class ="btnbtn"><a class="btn btnval2" id="btn26" data-id = "0.8">.8%</a></span>
                            <span class ="btnbtn"><a class="btn btnval2" id="btn27" data-id = "0.9">.9%</a></span>
                        </span>
                      </div>
                      </div>


                      <div class="col-md-12">
                        <div class="form-check">
                          <input type="checkbox" class="form-check-input" value="yes" id="stop_loss" name="stop_loss" <?php if ($temp_sell_arr['stop_loss'] == 'yes') {?> checked <?php }?>>
                          <label class="form-check-label" for="stop_loss">Stop Loss</label>
                        </div>
                      </div>


                      <div class="col-md-12" id="stop_loss_data" style="display:none;">
                        <div class="form-group col-md-12">
                          <label class="control-label">Loss Percentage (%)</label>
                          <input type="text" name="loss_percentage" id="loss_percentage" required="required" class="form-control" value="<?php echo $temp_sell_arr['loss_percentage']; ?>">
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
                    <input type="hidden" name="id" value="<?php echo $order_arr['_id']; ?>">
                    <input type="hidden" name="temp_sell_id" value="<?php echo $temp_sell_arr['_id']; ?>">
                    <button id="add_order_p" class="btn btn-success" type="submit"><i class="fa fa-check-circle"></i> Update Order </button>
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
                  <?php if ($type == 'sell') {?>
                  <li>
                    <span><b>Closest Sell Zone</b></span>
                    <span class="count"><?php echo $start_value . ' - ' . $end_value; ?></span>
                  </li>
                  <?php } else {?>
                  <li>
                    <span><b>Closest Buy Zone</b></span>
                    <span class="count"><?php echo $start_value . ' - ' . $end_value; ?></span>
                  </li>
                  <?php }?>
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
             <div class="widget">
              <div class="widget-body list">
                <ul>
                  <li>
                    <span><b>Order Type</b></span>
                    <span class="count"><?php
if ($order_arr['trigger_type'] == 'no' || $order_arr['trigger_type'] == '') {
    echo "Manual Order";
} else {
    echo strtoupper(str_replace('_', ' ', $order_arr['trigger_type']));
}

?></span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <!-- <div class="col-md-6 text-center">
            <div class="widget widget-inverse" style="/*transform: scale(0.7)*/;">
              <div class="" style="text-align: center;background: #aaaaaa;">
                <div class="row" style="padding:10px;">
                <div class="meater_candle">
                      <div class="goal_meater_main">
                            <div class="goal_meater_img">
                                <div class="degits"><?php //echo $score_avg; ?></div>
                                <div pin-value="<?php //echo $score_avg; ?>" class="goal_pin"></div>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
          </div> -->

          <?php if (count($order_history_arr) > 0) {
    ?>
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
$i = 0;
    foreach ($order_history_arr as $value) {
        $i++;
        ?>
                                      <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><!-- <?php echo $value['log_msg']; ?> -->
                                        	 <?php
//str_ireplace($word, "<span class='hilight'>{$word}</span>", $text)
        if (strpos($value['log_msg'], '<b>SUBMITTED</b>') !== false) {

            echo str_replace('<b>SUBMITTED</b>', "<span style='color:orange;    font-size: 14px;'><b>SUBMITTED</b></span>", $value['log_msg']);
        } elseif (strpos($value['log_msg'], '<b>FILLED</b>') !== false) {

            echo str_replace('<b>FILLED</b>', "<span style='color:green;    font-size: 14px;'><b>FILLED</b></span>", $value['log_msg']);
        } elseif (stripos($value['log_msg'], 'Error') !== false) {

            echo str_replace('Error', "<span style='color:red;font-size: 14px;'><b>ERROR</b></span>", $value['log_msg']);
        } else {
            echo $value['log_msg'];
        }

        //echo $value['log_msg']; ?>
                                        </td>
                                        <td><?php echo $value['created_date']; ?></td>
                                      </tr>
                                      <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
          </div>
          <?php }?>

        </div>


  </div>
</div>

<script type="text/javascript" src="<?php echo ASSETS; ?>js/jquery.validate.js"></script>
<script src="<?php echo ASSETS; ?>cdn_links/jquery.inputmask-2.x/dist/jquery.inputmask.bundle.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#purchased_price").inputmask({
		mask: "9[.99999999]",
		greedy: true,
		definitions: {
			'*': {
				validator: "[0-9]"
			}
		},
		rightAlign: false
		});

		$("#purchased_price").blur(function(e){
			var purchased_price = $(this).val();
			var market_value = parseFloat($('#purchased_price_hidden').val());
			var float_price = parseFloat(purchased_price);
			var purchase_up = float_price * 2;
			var purchase_down = float_price / 2;

			if ((market_value > purchase_down) && (market_value < purchase_up)) {
				$('.price_error').html('');
			}else{
				$('.price_error').html('<br><div class="alert alert-warning alert-dismissible" role="alert">\
  <strong>Non-Relaiable Price!</strong> You should check in the entered price. The price seems to be non-relaiable!<br>Are you sure you want to continue <br> click on (x) button to ignore\
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">\
    <span aria-hidden="true">&times;</span>\
  </button>\
</div>');
			}


		});
	});
	</script>
<script>

/*$("body").on('keyup','#purchased_price',function() {
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
      $('#sellpercent').html('<div class="alert alert-warning alert-dismissible alert-ignore" role="alert">You are setting The profit %age greater then 100 <small>Click Close Button to Ignore</small>.<button type="button" class="close"><span aria-hidden="true">&times;</span></button></div>');
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
});*/

function autoload_market_buy_price()
{

  var coin = $('#coin').val();
  $.ajax({
      type:'POST',
      url:'<?php echo SURL ?>admin/dashboard/set_buy_price',
      data: {coin:coin},
      success:function(response){

         var resp = response.split('|');
          $('#purchased_price_hidden').val(resp[0]);
          if (resp[1] == 'NAN') { resp[1] = 0; }
          $('.goal_pin').attr('pin-value',resp[1]);
          $('.degits').html(resp[1]);
          meaterfunction();

          setTimeout(function() {
                autoload_market_buy_price();
          }, 3000);

      }
    });
}

function autoload_market_statistics(){
    var coin = $('#coin').val();
    $.ajax({
      type:'POST',
      url:'<?php echo SURL ?>admin/dashboard/autoload_market_statistics',
      data: {coin:coin},
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
calculate_min_notation();
calculate_max_notation();
$("body").on("change","#coin",function(e){
   var coin = $('#coin').val();
  $.ajax({
      type:'POST',
      url:'<?php echo SURL ?>admin/dashboard/set_buy_price',
      data: {coin:coin},
      success:function(response){
       	  var resp = response.split('|');
          $('#purchased_price_hidden').val(resp[0]);
          if($('#buy_now').is(':checked'))
          {
            $('#purchased_price').val(resp[0]);
          }
          if (resp[1] == 'NAN') { resp[1] = 0}
          $('.goal_pin').attr('pin-value',resp[1]);
          $('.degits').html(resp[1]);
          meaterfunction();
         calculate_min_notation();
         calculate_max_notation();


      }
    });
});

function calculate_min_notation()
{
	var symbol = $('#coin').val();
	var sss = "1";
	$.ajax({
		type:'POST',
		async: false,
		url:'<?php echo SURL ?>admin/buy_orders/get_min_notation',
		data: {symbol:symbol},
		success:function(response){
		 $('#quantity_check_min').val(response);
		}
	});
}


function calculate_max_notation()
{
	var symbol = $('#coin').val();
	var sss = "1";
	$.ajax({
		type:'POST',
		async: false,
		url:'<?php echo SURL ?>admin/buy_orders/get_max_notation',
		data: {symbol:symbol},
		success:function(response){
		 $('#quantity_check_max').val(response);
		}
	});
}

$(document).ready(function(){

    if (document.getElementById('trail_check').checked)
    {
        $('#trail_buy_data').show();
    }
    else
    {
        $('#trail_buy_data').hide();
    }

    if (document.getElementById('trail_check22').checked)
    {
        $('#trail_sell_data').show();
    }
    else
    {
        $('#trail_sell_data').hide();
    }

    if (document.getElementById('auto_sell').checked)
    {
        $('#auto_sell_data').show();
    }
    else
    {
        $('#auto_sell_data').hide();
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
    $('#loss_percentage').val(data);
  });
</script>
<script type="text/javascript">
  $("#buy_order_form").validate();
</script>
<script type="text/javascript">

  $("body").on("click",".close",function(){
    /*$(this).parent().hide()
     if($('.alert').is(':visible')){
      $('#add_order_p').attr('disabled', 'disabled');
    }
    else{
      $('#add_order_p').removeAttr("disabled");
    }*/

  });
  $(document).ready(function(){
    /*if($('.alert').is(':visible')){
      $('#add_order_p').attr('disabled', 'disabled');
    }
    else{
      $('#add_order_p').removeAttr("disabled");
    }*/
  });
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
  $(document).ready(function(){

    $('body').on('click','.btnval2',function(e){
      //$('.pl').html(parseFloat($(this).val()).toFixed(8));
      var per = parseFloat($(this).data('id'));
      var price = parseFloat($("#purchased_price").val());

      var calculate = (price * per)/100;
     // var calculate = parseFloat(margin_total).toFixed(8);
      $('#sell_trail_interval').val(parseFloat(calculate).toFixed(8));
    });
  });



	$("body").on('keyup','#quantity',function() {
		var checked_min = parseFloat($('#quantity_check_min').val());
		var checked_max = parseFloat($('#quantity_check_max').val());
		var quantity = parseFloat($('#quantity').val());

		if (quantity < checked_min) {
			$('#quantitydv').html('<div class="alert alert-danger">Minimum Quantity Should Be '+checked_min+' Please Enter Valid Quantity');
			$("#add_order_p").prop("disabled","true");
			 // var quantity = $('#quantity').val(checked);
		}else if(quantity > checked_max){
			$('#quantitydv').html('<div class="alert alert-danger">Maximum Quantity Should Be '+checked_max+' Please Enter Valid Quantity');
			$("#add_order_p").prop("disabled","true");
		}else{
			 $('#quantitydv').html('');
			 $("#add_order_p").removeAttr("disabled");
		}
	});
</script>
