<style type="text/css">
.chart_header {
    font-size: 15px;
    float: right;
    max-height: 750px;
    text-align: center;
    width: 70%;
    position: sticky;
    top: 0px;
    text-transform: uppercase;
    vertical-align: middle;
    z-index: 5;
    font-weight: bolder;
    background: #2B2B2B;
}
.coin_symbol {
    font-size: 15px;
    float: right;
    max-height: 750px;
    text-align: center;
    width: 70%;
    position: sticky;
    top: 0px;
    text-transform: uppercase;
    vertical-align: middle;
    z-index: 5;
    font-weight: bolder;
    background: #2B2B2B;
}
.widget_swing {
    background-color: white;
    text-align: center;
    vertical-align: middle;
    padding-top: 10%;
    font-size: 16px;
    font-weight: bolder;
    height: 62px !important;
}
.widthdepth{
  width: 30% !important;
}

.with_BS .price_cent_main {
    width: 34% !important;
}

.bottom_prog_box {
    position: absolute;
    bottom: 0px;
    left: 0;
    width: 100%;
    height: 120px;
    background: #252525;
    padding: 10px 20px;
}
.bottom_prog_left {
    float: left;
    width: 30%;
}
.bottom_prog_one {
    float: left;
    width: 100%;
    margin-bottom: 5px;
    padding: 5px;
    background: #333;
    border-radius: 50px;
}
.bottom_prog_two {
    float: left;
    width: 100%;
    margin-bottom: 5px;
    padding: 5px;
    background: #333;
    border-radius: 50px;
}
.bottom_prog_three {
    float: left;
    width: 100%;
    border-radius: 50px;
    background: #333;
    padding: 5px;
}
.bottom_prog_title {
    float: left;
    width: 30%;
}
.bottom_prog_title h2 {
    float: left;
    width: 100%;
    margin: 0;
    color: #fff;
    padding-top: 5px;
    font-weight: normal;
    font-size: 12px;
    padding-left: 11px;
}
.bottom_progress {
    float: left;
    height: 20px;
    width: 70%;
    border-radius: 15px;
    position:relative;
    overflow:hidden;
}
.prog_box {
    float: left;
    text-align: center;
    color: #fff;
    font-weight: bold;
    font-size: 12px;
    height: 20px;
}
.tradding_chart_1_main {
    padding: 15px 15px 125px;
    position: relative;
}
</style>
<div id="content" style="background:#232323;">
<!--   <div class="innerAll border-bottom">
	<span class="fa fa-info-circle" style="float: right;font-size: 20px;margin-top: -25px;color: #cb4040;" data-toggle="popover" data-placement="left" data-trigger="hover" data-container="body" data-original-title="Buy Order Listing" data-content="Here in Buy order listing page every order is filtered by their current status, If you want to see the specific order look that in related tab, Moreover you can filter the orders by date, type and coin"></span>
  </div> -->

<div class="innerAll spacing-x2">
<div class="row">

<section class="tradding_chart_1_sec">
  <div class="tradding_chart_1_main">
      <div class="towinone">
            <div class="coin_symbol">
                <select id="coin_val" class="form-control">
                    <option value="">Select a Symbol</option>
                   <?php foreach ($coins as $key => $value) {
	echo '<option value="' . $value['symbol'] . '">' . $value['symbol'] . '</option>';
}?>
                </select>
            </div>
            <div class="chart_header">
                <div class="wbs_buyer_prog_main"  style="background: #2B2B2B; width: 30%; height: 50px">
                    <div class="wbs_blu_prog" style="height: 50px;">
                        <div class="wbs_blue_prog_p">Orders Book</div>
                    </div>
                </div>
                <!-- <div class="buyer_prog_main" style="background: #2B2B2B; width: 20%; height: 50px">
                    <div class="blu_prog" style="height: 50px;">
                      <div class="blue_prog_p">Orders Book</div>
                    </div>
                </div> -->
                <div class="price_cent_main" style="background: #2B2B2B; width: 32%; height: 50px">
                    <span class="simple_p gray_v_p" style="height: 50px;">Price</span>
                </div>
                <div class="seller_prog_main" style="background: #2B2B2B; width: 38%; height: 50px">
                    <div class="red_prog" style="height: 50px;">
                      <div class="red_prog_p" style="float:none; text-align: center;">Volume</div>
                    </div>
                </div>
                <!-- <div class="wbs_seller_prog_main" style="background: #2B2B2B; width: 20%; height: 50px">
                    <div class="wbs_red_prog" style="height: 50px;">
                        <div class="wbs_red_prog_p">Volume</div>
                    </div>
                </div> -->
            </div>
         <div class="price_s_r_main" id="response_trading_data">
                <ul class="price_s_r_ul">
                    <?php
if (count($market_buy_depth_arr) > 0) {
	$i = 0;
	foreach ($market_buy_depth_arr as $key => $value) {

		$price = num($value['price']);
		?>

                    <li class="price_s_r_li with_BS" d_price="<?php echo $price; ?>" index="<?php echo $i; ?>">
                    	  <!-- <div class="wbs_buyer_prog_main">
                            <div class="wbs_blu_prog">
                                <div class="wbs_blue_prog_p">1867</div>
                                <div class="wbs_blue_prog_bar" WBS_d_prog_percent="10"></div>
                            </div>
                        </div> -->
                         <div class="wbs_seller_prog_main widthdepth">
                            <div class="wbs_red_prog">
                                <div class="wbs_red_prog_p">169</div>
                                <div class="wbs_red_prog_bar" wbs_d_prog_percent="10"></div>
                            </div>
                        </div>
                        <div class="price_cent_main">
                            <span class="simple_p gray_v_p"><?php echo $price; ?></span>
                        </div>
                        <div class="seller_prog_main">
                            <div class="red_prog">
                              <div class="red_prog_p"><?php echo $value['buy_quantity']; ?></div>
                                                              <?php
$buy_percentage = round($value['buy_quantity'] * 100 / $biggest_value);
		if ($buy_percentage == 100) {
			$big_price = $price;
		}
		?>
                              <div class="red_prog_bar" d_prog_percent="<?php echo $buy_percentage; ?>"></div>
                            </div>
                        </div>
                        <div class="buyer_prog_main">
                            <div class="blu_prog">
                              <div class="blue_prog_p"><?php echo $value['sell_quantity']; ?></div>
                              <?php
$sell_percentage = round($value['sell_quantity'] * 100 / $biggest_value);
		if ($sell_percentage == 100) {
			$big_price = $price;
		}
		?>
                              <div class="blue_prog_bar" d_prog_percent="<?php echo $sell_percentage; ?>"></div>
                            </div>
                        </div>

                    </li>

                    <?php }
}
?>

                   <li class="price_s_r_li with_BS" d_price="<?php echo num($market_value); ?>" index="<?php echo $i++; ?>">
                   		<div class="wbs_buyer_prog_main" style="width:30% !important;">
                            <div class="wbs_blu_prog">
                                <div class="wbs_blue_prog_p">1867</div>
                                <div class="wbs_blue_prog_bar" WBS_d_prog_percent="10"></div>
                            </div>
                        </div>
                        <div class="price_cent_main">
                            <span class="simple_p white_v_p" id="response2222">
                             <span class="GCV_color_default"><?php echo num($market_value); ?></span>
                            </span>
                        </div>
                        <div class="seller_prog_main">
                            <div class="red_prog">
                            </div>
                        </div>
                        <div class="buyer_prog_main">
                        </div>
                   </li>


                    <?php
if (count($market_sell_depth_arr) > 0) {
	foreach ($market_sell_depth_arr as $key => $value2) {

		$lenth33 = strlen(substr(strrchr($value2['price'], "."), 1));
		if ($lenth33 == 6) {
			$price22 = $value2['price'] . '0';
		} else {

			$price22 = $value2['price'];
		}
		?>

                    <li class="price_s_r_li with_BS" d_price="<?php echo $price22; ?>" index="<?php echo $i; ?>">
                    	<div class="wbs_buyer_prog_main widthdepth">
                            <div class="wbs_blu_prog">
                                <div class="wbs_blue_prog_p">1867</div>
                                <div class="wbs_blue_prog_bar" WBS_d_prog_percent="10"></div>
                            </div>
                        </div>
                       <!-- <div class="wbs_seller_prog_main">
                            <div class="wbs_red_prog">
                                <div class="wbs_red_prog_p">169</div>
                                <div class="wbs_red_prog_bar" wbs_d_prog_percent="10"></div>
                            </div>
                        </div> -->
                        <div class="price_cent_main">
                            <span class="simple_p light_gray_v_p"><?php echo $price22; ?></span>
                        </div>
                        <div class="seller_prog_main">
                            <div class="red_prog">
                              <div class="red_prog_p"><?php echo $value2['buy_quantity']; ?></div>
                              <?php
$buy_percentage2 = round($value2['buy_quantity'] * 100 / $biggest_value);
		if ($buy_percentage2 == 100) {
			$big_price = $price22;
		}
		?>
                              <div class="red_prog_bar" d_prog_percent="<?php echo $buy_percentage2; ?>"></div>
                            </div>
                        </div>
                         <div class="buyer_prog_main">
                            <div class="blu_prog">
                              <div class="blue_prog_p"><?php echo $value2['sell_quantity']; ?></div>
                              <?php
$sell_percentage2 = round($value2['sell_quantity'] * 100 / $biggest_value);
		if ($sell_percentage2 == 100) {
			$big_price = $price22;
		}
		?>
                              <div class="blue_prog_bar" d_prog_percent="<?php echo $sell_percentage2; ?>"></div>
                            </div>
                        </div>

                    </li>

                    <?php }
}
?>

                </ul>

            </div>


        <div class="price_sec_center_main" id="response_market_value">
        </div>



        </div>


        <div class="price_sec_candle_main">
       <div class="barcalnd">
                <div class="bar_candle" style="width:25%">
                    <div class="current_candle">
                        <div class="current_candle_hd">
                            Last Demand Candle
                        </div>
                        <div class="current_candle_gr">
                            <div class="verti_bar_prog" style="height: 486px;">
                                <div class="verti_bar_prog_top last_bid" d_vbppercent="70" style="height: 70%;">
                                    <span id="last_candle_bid">70</span>
                                </div>
                                <div class="verti_bar_prog_bottom last_ask" d_vbppercent="30" style="height: 30%;">
                                    <span id="last_candle_ask">30</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<div class="bar_candle" style="width:25%">
                    <div class="current_candle">
                        <div class="current_candle_hd">
                            Current Candle
                        </div>
                        <div class="current_candle_gr">
                            <div class="verti_bar_prog" style="height: 486px;">
                                <div class="verti_bar_prog_top curr_bid" d_vbppercent="70" style="height: 70%;">
                                    <span id="current_candle_bid">70</span>
                                </div>
                                <div class="verti_bar_prog_bottom curr_ask" d_vbppercent="30" style="height: 30%;">
                                    <span id="current_candle_ask">30</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bar_inzoon_candle " style="width:25%">
                    <div class="inzoon_candle">
                        <div class="inzoon_candle_hd">Rolling Candle</div>

                        <div class="inzoon_candle_gr">
                            <div class="verti_bar_prog" style="height: 486px;">
                                <div class="verti_bar_prog_top roll_bid" d_vbppercent="70" style="height: 70%;">
                                    <span id="roll_candle_bid">70</span>
                                </div>
                                <div class="verti_bar_prog_bottom roll_ask" d_vbppercent="30" style="height: 30%;">
                                    <span id="roll_candle_ask">30</span>
                                </div>
                        </div>
                    </div>
                </div>
              </div>
                 <div class="bar_inzoon_candle " style="width:25%">
                    <div class="inzoon_candle">
                        <div class="inzoon_candle_hd">Swing Candle</div>
                        <div class="inzoon_candle_gr">
                            <div class="verti_bar_prog widget_swing" id="response_swing_candle" style=" height: 100px;"></div>
                        </div>
                    </div>
                </div>

                <div class="bar_inzoon_candle " style="width:25%">
                    <div class="inzoon_candle">
                        <div class="inzoon_candle_hd">Contracts Average</div>
                        <div class="inzoon_candle_gr">
                            <div class="verti_bar_prog widget_swing" style="background-color: #778ca3; color:white;" id="response_contract_info" style=" height: 100px;"></div>
                        </div>
                    </div>
                </div>
                <div class="bar_inzoon_candle " style="width:25%">
                    <div class="inzoon_candle">
                        <div class="inzoon_candle_hd">Bid Contracts Average</div>
                        <div class="inzoon_candle_gr">
                            <div class="verti_bar_prog widget_swing" style="background-color: #fc5c65; color:white;" id="response_bid_contract_info" style=" height: 100px;"></div>
                        </div>
                    </div>
                </div>
                <div class="bar_inzoon_candle " style="width:25%">
                    <div class="inzoon_candle">
                        <div class="inzoon_candle_hd">Ask Contracts Average</div>
                        <div class="inzoon_candle_gr">
                            <div class="verti_bar_prog widget_swing" style="background-color: #2bcbba; color:white;" id="response_ask_contract_info" style=" height: 100px;"></div>
                        </div>
                    </div>
                </div>
                <div class="bar_inzoon_candle " style="width:25%">
                    <div class="inzoon_candle">
                        <div class="inzoon_candle_hd">Big Wall</div>
                        <div class="inzoon_candle_gr">
                            <div class="verti_bar_prog widget_swing widget1122" style="background-color: #2bcbba; color:white;" id="response_big_wall" style=" height: 90px;"></div>
                        </div>
                    </div>
                </div>
                <div class="bar_inzoon_candle " style="width:25%">
                    <div class="inzoon_candle">
                        <div class="inzoon_candle_hd">Up Down Pressure</div>
                        <div class="inzoon_candle_gr">
                            <div class="verti_bar_prog widget_swing" style="background-color: rgb(92, 188, 50); color:white;" id="up" style=" height: 100px;"></div>
                            <div class="verti_bar_prog widget_swing" style="background-color:rgb(241, 25, 25); color:white;" id="down" style=" height: 100px;"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="bottom_prog_box" id="response_pressure">
                        <div class="bottom_prog_left">
                            <div class="bottom_prog_one">
                                <div class="bottom_prog_title">
                                    <h2>Pressure One</h2>
                                </div>
                                <div class="bottom_progress">
                                    <div class="prog_box" style="width:70%; background:#f11919;">70%</div>
                                    <div class="prog_box" style="width:30%; background:#5cbc32;">30%</div>
                                </div>
                            </div>
                            <div class="bottom_prog_two">
                                <div class="bottom_prog_title">
                                    <h2>Pressure Two</h2>
                                </div>
                                <div class="bottom_progress">
                                    <div class="prog_box" style="width:40%; background:#2196F3;">40%</div>
                                    <div class="prog_box" style="width:60%; background:#2bcbba;">60%</div>
                                </div>
                            </div>
                            <div class="bottom_prog_three">
                                <div class="bottom_prog_title">
                                    <h2>Pressure Three</h2>
                                </div>
                                <div class="bottom_progress">
                                    <div class="prog_box" style="width:35%; background:#9C27B0;">35%</div>
                                    <div class="prog_box" style="width:65%; background:#FF9800;">65%</div>
                                </div>
                            </div>
                        </div>
                    </div>
    </div>
</section>

<div class="boat_points" id="response_order_details">
</div>


<input type="hidden" value="<?php echo $market_value; ?>" id="previous_market_value">
<input type="hidden" value="<?php echo $biggest_value; ?>" >
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Chart3</h4>
      </div>
      <div class="modal-body">
        <p>In Chart 3 there is complete picture of Market Depth and Trade History at every price, the percentages, total depth and trades etc. If you have open Orders, either Buy or Sell, it will be shown on respective Price you can view and edit that orders, If you do Right Click on the price, it will allow you to add a new order</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
   // $('#myModal').modal('show');
  });
</script>
<script type="text/javascript">
  var zi=0;
  var zin = 0;
  var zin2 = 0;
  function autoload_trading_chart_data(){

      var market_value = $("#market_value").val();
      var previous_market_value = $("#previous_market_value").val();
      var coin = $('#coin_val').val();

      $.ajax({
        type:'POST',
        url:'<?php echo SURL ?>admin/chart3/autoload_trading_chart_data',
        data: {market_value:market_value,previous_market_value:previous_market_value,symbol:coin},
        success:function(response){

          var split_response = response.split('|');

          if(split_response[0] !=""){
            $('#response_trading_data').html(split_response[0]);
          }

          if(split_response[1] !=""){
            $('#response_market_value').html(split_response[1]);
          }

          $('#previous_market_value').val(split_response[2]);

          var type = split_response[3];
          var target_zones_arr = jQuery.parseJSON(split_response[4]);



          $(".G_graph_box").remove();

          //kamran area
          $.each(target_zones_arr, function(index, itemData) {

            zi++;
            MakeOrangeGreen_box(zi,itemData.start_value, itemData.end_value, itemData.type);

          });


          $('#response_target_zone_date').html(split_response[5]);

          Get_CurrentCandel_and_InZoon_GraphVal();

          Get_BlueProgBar_GraphVal();
          Get_RedProgBar_GraphVal();

          WbsGet_BlueProgBar_GraphVal();
          WbsGet_RedProgBar_GraphVal();

          var Sell_orders_arr = jQuery.parseJSON(split_response[6]);
          $.each(Sell_orders_arr, function(index, itemData) {

            zin++;

            if(itemData.trail_check =='yes'){
              var new_sell_price = itemData.sell_trail_price;
            }else{
              var new_sell_price = itemData.sell_price;
            }

            SelectedOrderPrice(zin,itemData._id.$oid,new_sell_price,"sell",itemData.quantity);

          });


          var Buy_orders_arr = jQuery.parseJSON(split_response[7]);
          $.each(Buy_orders_arr, function(index, itemData) {

            zin2++;

            if(itemData.trail_check =='yes'){
              var new_buy_price = itemData.buy_trail_price;
            }else{
              var new_buy_price = itemData.price;
            }

            SelectedOrderPrice(zin2,itemData._id.$oid,new_buy_price,"buy",itemData.quantity);

          });

          var last_candle_ask = split_response[9];
          var last_candle_bid = split_response[8];
          var current_candle_ask = split_response[11];
          var current_candle_bid = split_response[10];
          var curr_bid = split_response[12];
          var curr_ask = split_response[13];
          var last_bid = split_response[14];
          var last_ask = split_response[15];

          var roll_bid_per = split_response[20];
          var roll_ask_per = split_response[21];
          var roll_bid = split_response[22];
          var roll_ask = split_response[23];

           $("#up").html("Up " + split_response[24])
           $("#down").html("Down " + split_response[25])
           var str = split_response[26]
           $("#response_big_wall").html(str.toUpperCase())
           if (str == 'up') {
            $('.widget1122').css({'background-color':'#D7484E'})
           }else if(str == 'down'){
            $('.widget1122').css({'background-color':'#3BA8D1'})
           }

           $('#response_pressure').html(split_response[27])
           $("#response_swing_candle").html(split_response[16])
           $("#response_contract_info").html(split_response[17])
           $("#response_bid_contract_info").html(split_response[18])
           $("#response_ask_contract_info").html(split_response[19])
          //alert(split_response[15])
          var last_ask_width = last_candle_ask;
          var last_bid_width = last_candle_bid;

          var curr_ask_width = current_candle_ask;
          var curr_bid_width = current_candle_bid;
          if(isNaN(last_candle_ask))
          {
            last_candle_ask = 0;
            last_ask = 0;
            last_ask_width = 50;
          }

          if(isNaN(last_candle_bid))
          {
            last_candle_bid = 0;
            last_bid_width = 50;
            last_bid = 0;
          }

          if(isNaN(current_candle_ask))
          {
            current_candle_ask = 0;
            curr_ask_width = 50;
            curr_ask = 0;
          }

          if(isNaN(current_candle_bid))
          {
            current_candle_bid = 0;
            curr_bid_width = 50;
            curr_bid = 0;
          }

          /*if(isNaN(roll_bid))
          {
            roll_bid = 0;
            roll_bid_per = 50;
            roll_bid = 0;
          }

           if(isNaN(roll_ask))
          {
            roll_ask = 0;
            roll_ask_per = 50;
            roll_ask = 0;
          }*/

          $("#last_candle_ask").html(last_ask);
          $(".last_ask").attr("d_vbppercent",last_candle_ask+'%')
          $(".last_ask").height( last_ask_width+'%')

          $("#last_candle_bid").html(last_bid);
          $(".last_bid").attr("d_vbppercent",last_ask_width+'%')
          $(".last_bid").height( last_bid_width+'%')

          $("#current_candle_ask").html(curr_ask);
          $(".curr_ask").attr("d_vbppercent",curr_ask_width+'%')
          $(".curr_ask").height( curr_ask_width+'%')

          $("#current_candle_bid").html(curr_bid);
          $(".curr_bid").attr("d_vbppercent",curr_bid_width+'%')
          $(".curr_bid").height( curr_bid_width+'%')

          $("#roll_candle_ask").html(roll_ask);
          $(".roll_ask").attr("d_vbppercent",roll_ask_per+'%')
          $(".roll_ask").height( roll_ask_per+'%')

          $("#roll_candle_bid").html(roll_bid);
          $(".roll_bid").attr("d_vbppercent",roll_bid_per+'%')
          $(".roll_bid").height( roll_bid_per+'%')

          setTimeout(function() {
              autoload_trading_chart_data();
          }, 2000);

        },
        error:function(e){
             autoload_trading_chart_data();
        }
      });

  }//end autoload_trading_chart_data222()

  autoload_trading_chart_data();
$('body').on('change','#coin_val',function(){
    autoload_trading_chart_data();
});
</script>

<script>
function price_sec_center_main_height_func(){
  var price_s_r_li_length = jQuery(".price_s_r_li").length;
  var price_sec_center_main_height = parseInt(price_s_r_li_length)*25;
  jQuery(".price_sec_center_main").height(price_sec_center_main_height);
}

function Get_CurrentCandel_and_InZoon_GraphVal(){
  jQuery(".verti_bar_prog_top").each(function(index, element) {
    var this_get_height_t = jQuery(this).attr("d_vbppercent");
    jQuery(this).css("height",this_get_height_t+"%");
  });

  jQuery(".verti_bar_prog_bottom").each(function(index, element) {
    var this_get_height_b = jQuery(this).attr("d_vbppercent");
    jQuery(this).css("height",this_get_height_b+"%");
  });
}

function Get_BlueProgBar_GraphVal(){
  jQuery(".blue_prog_bar").each(function(index, element) {
        var this_blue_prog_bar = jQuery(this).attr("d_prog_percent");
    jQuery(this).css("width",this_blue_prog_bar+"%");
    });
}

function Get_RedProgBar_GraphVal(){
  jQuery(".red_prog_bar").each(function(index, element) {
        var this_red_prog_bar = jQuery(this).attr("d_prog_percent");
    jQuery(this).css("width",this_red_prog_bar+"%");
    });
}

function WbsGet_BlueProgBar_GraphVal(){
	jQuery(".wbs_blue_prog_bar").each(function(index, element) {
    var this_blue_prog_bar = jQuery(this).attr("wbs_d_prog_percent");
		jQuery(this).css("width",this_blue_prog_bar+"%");
  });
}

function WbsGet_RedProgBar_GraphVal(){
	jQuery(".wbs_red_prog_bar").each(function(index, element) {
    var this_red_prog_bar = jQuery(this).attr("wbs_d_prog_percent");
		jQuery(this).css("width",this_red_prog_bar+"%");
  });
}

function set_verti_graph_height(){
  var windowHeight = jQuery(window).height();
  var setHv = parseInt(windowHeight)-250;
  jQuery(".verti_bar_prog").height(setHv);
}

function set_indexing_price_s_r_li(){
  jQuery(".price_s_r_li").each(function(index, element) {
    var this_price_s_r_li_index = jQuery(this).index();
    jQuery(this).attr("index",this_price_s_r_li_index);
  });
}


function MakeOrangeGreen_box(zi,start_val,end_val,type){

  var zindexing = jQuery(".zindexing").text();
  var Zind = parseInt(zindexing);

  jQuery(".zindexing").text(zi);

	var vary_first_price = jQuery(".price_s_r_li:first").attr("d_price");
	var vary_last_price = jQuery(".price_s_r_li:last").attr("d_price");

	var Eval = start_val; //jQuery(".").val();
	var Sval= end_val; //jQuery(".Eval").val();
	var BSval = type; //jQuery(".BSval").val();

  var thisplength_S = jQuery("[d_price='"+Sval+"']").length;
	var thisplength_E = jQuery("[d_price='"+Eval+"']").length;

	var ValueArray = [];
	jQuery(".shoval").text('');
	if(thisplength_S > 0 || thisplength_E > 0 ){
	jQuery("[d_price]").each(function(index, element) {

    if(jQuery(this).attr('d_price') <= Sval){

  			if(jQuery(this).attr('d_price') >= Eval){
  				var thisSEval = jQuery(this).attr('d_price');
  				jQuery(".shoval").append(thisSEval+',');
  				ValueArray.push(thisSEval);
  			}
		}

  });
}else{
  return false;
}

	var ValueArrayLength = ValueArray.length;
	var ValueArrayLast = ValueArrayLength-1;

	var makSval = ValueArray[0];
	var makEval = ValueArray[ValueArrayLast];

	Sval = makSval
	Eval = makEval

	if(BSval == 'buy'){
		var GB_cls = 'gg_color_orange';
	}else{
		var GB_cls = 'gg_color_green';
	}

	//alert(Sval+Eval+GB_cls);

	var Sval_index = jQuery("li[d_price='"+Sval+"']").attr("index");
	var Eval_index = jQuery("li[d_price='"+Eval+"']").attr("index");

	var setIndxS= parseInt(Sval_index)+parseInt(1);
	var setIndxE= parseInt(Eval_index)+parseInt(1);
	//alert('setIndxS'+setIndxS+'----setIndxE'+setIndxE);


	if(setIndxE == 0){
		setIndxE = 1;
	}

	var setTop = (setIndxS*25) - 25;

	var setHeight = (parseInt(setIndxE)-parseInt(setIndxS)+1)*25;

	//alert(setTop+"---"+setHeight);
	var OG_HTML = '<div class="G_graph_box '+GB_cls+'" style="top:'+setTop+'px; height:'+setHeight+'px; z-index:2;"><div class="TP_line TP_line_start"><div class="TP_line_text">TP Buyer Zoon 1</div></div><div class="TP_line TP_line_end"><div class="TP_line_text">TP Buyer Zoon 2</div></div></div>';
	jQuery(".price_sec_center_main").prepend(OG_HTML);

}


var zin = 0;
function SelectedOrderPrice(zin,order_id,val,type,Qty){

	var vary_first_price = jQuery(".price_s_r_li:first").attr("d_price");
	var vary_last_price = jQuery(".price_s_r_li:last").attr("d_price");

	var BoSval = val ;   //jQuery(".BoSval").val();
	var SbOs =  type ;   //jQuery(".SbOs").val();

  var thisplength_S = jQuery("[d_price='"+BoSval+"']").length;


	if(thisplength_S ==0){

   return false;

  }

	if(SbOs == 'buy'){
		var GB_cls = 'drawPrice_b';
	}else{
		var GB_cls = 'drawPrice_s';
	}


	var Sval_index = jQuery("li[d_price='"+BoSval+"']").attr("index");
	var Eval_index = jQuery("li[d_price='"+BoSval+"']").attr("index");

	var setIndxS= parseInt(Sval_index)+parseInt(1);
	var setIndxE= parseInt(Eval_index)+parseInt(1);


	if(setIndxE == 0){
		setIndxE = 1;
	}

	var setTop = (setIndxS*25) - 25;

	//var setHeight = (parseInt(setIndxE)-parseInt(setIndxS)+1)*25;
	var zindex = 9999 + parseInt(zin);

	var OG_HTML = '<div style="top:'+setTop+'px; z-index:4;" class="drawPrice '+GB_cls+'"><div class="drawPrice_v" order_id="'+order_id+'" type="'+type+'">'+Qty+'</div><div class="drawPrice_line"></div></div>';
	jQuery(".price_sec_center_main").prepend(OG_HTML);

}


function draw_rectangle(type){

  if(type =='buy'){

    jQuery(".blu_prog div[d_prog_percent]").each(function(){
    var vargetval = jQuery(this).attr("d_prog_percent");

    if(vargetval == 100){
      var thisvl = jQuery(this).closest(".price_s_r_li").attr("d_price");
      var thisvl_before = jQuery(this).closest(".price_s_r_li").prev().attr("d_price");
      var thisvl_after = jQuery(this).closest(".price_s_r_li").next().attr("d_price");
      //var type = 'buy';
      zi++;
      MakeOrangeGreen_box(zi,thisvl_before,thisvl_after,type);
    }
  });


  }else{

    jQuery(".red_prog div[d_prog_percent]").each(function(){
    var vargetval = jQuery(this).attr("d_prog_percent");

      if(vargetval == 100){
        var thisvl = jQuery(this).closest(".price_s_r_li").attr("d_price");
        var thisvl_before = jQuery(this).closest(".price_s_r_li").prev().attr("d_price");
        var thisvl_after = jQuery(this).closest(".price_s_r_li").next().attr("d_price");
        //var type = 'buy';
        zi++;
        MakeOrangeGreen_box(zi,thisvl_before,thisvl_after,type);
      }
    });
  }

}

jQuery(document).ready(function(e) {

  Get_CurrentCandel_and_InZoon_GraphVal();
  /*------------------------*/
  price_sec_center_main_height_func();


  set_verti_graph_height();

  set_indexing_price_s_r_li();

  var getoffset_price_sec_candle_main = jQuery(".price_sec_candle_main").offset().top;
  var getwidth_price_sec_candle_main = jQuery(".price_sec_candle_main").width();
  jQuery(window).scroll(function() {

    var scroll = window.pageYOffset || document.documentElement.scrollTop;

    if(scroll > getoffset_price_sec_candle_main){
      //jQuery(".price_sec_candle_main").addClass("positionFixed").width(getwidth_price_sec_candle_main);
    }else{
      //jQuery(".price_sec_candle_main").removeClass("positionFixed").removeAttr("style");
    }

  });
});
jQuery(window).on("load",function(){

  Get_BlueProgBar_GraphVal();
  Get_RedProgBar_GraphVal();

});

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
  jQuery("body").on("keyup",".Meater",function(){
    var inmet = jQuery(this).val();
    jQuery(".goal_pin").attr("pin-value",inmet);
    jQuery(".degits").text(inmet);
    meaterfunction();
  });

    jQuery("body").on("click",".Meater",function(){
    var inmet = jQuery(this).val();
    jQuery(".goal_pin").attr("pin-value",inmet);
    jQuery(".degits").text(inmet);
    meaterfunction();
  });

});

jQuery(window).on("load",function(){
	jQuery(".towinone").scrollTop('890');
});

jQuery("body").on("click",".drawPrice_v",function(){

    var order_id = $(this).attr("order_id");
    var type = $(this).attr("type");
    $.ajax({
        type:'POST',
        url:'<?php echo SURL ?>admin/chart3_group/get_order_details/'+order_id+'/'+type,
        data: {order_id:order_id,type:type},
        success:function(response){
          $('#response_order_details').html(response);
        }
    });

    jQuery(".boat_points").addClass("active");

});


jQuery("body").on("click","#edit_order_btn",function(){

    var order_id = $(this).attr("order_id");
    var type = $(this).attr("data-type");
    $.ajax({
        type:'POST',
        url:'<?php echo SURL ?>admin/chart3_group/get_edit_order_details/'+order_id+'/'+type,
        data: {order_id:order_id,type:type},
        success:function(response){
          $('#response_order_details').html(response);
        }
    });

    jQuery(".boat_points").addClass("active");

});


jQuery("body").on("click","#update_order_btn",function(){

   var type = $(this).attr("data-type");

   var postData = $('#edit_order_form').serializeArray();
   postData.push({name: 'type', value: type});

    $.ajax({
        type:'POST',
        url:'<?php echo SURL ?>admin/chart3_group/update_order_details',
        data: postData,
        success:function(response){
          $('#response_order_details').html(response);
        }
    });

    jQuery(".boat_points").addClass("active");

});



jQuery("body").on("click","#add_order_btn",function(){

   var postData = $('#add_order_form').serializeArray();

    $.ajax({
        type:'POST',
        url:'<?php echo SURL ?>admin/chart3_group/add_order_details',
        data: postData,
        success:function(response){
          $('#response_order_details').html(response);
        }
    });

    jQuery(".boat_points").addClass("active");

});
$(document).on('mousedown','.simple_p',function(e){
  if(e.which == 3)
  {
    var price = jQuery(this).html();
    $.ajax({
        type:'POST',
        url:'<?php echo SURL ?>admin/chart3_group/add_order_btn/',
        data: {price:price},
        success:function(response){
          $('#response_order_details').html(response);
        }
    });
    jQuery(".boat_points").addClass("active");
  }
});


jQuery("body").on("click",".boat_points_close",function(){
    jQuery(".boat_points").removeClass("active");
});

</script>


    </div>
  </div>
</div>
<!-- // Content END -->

