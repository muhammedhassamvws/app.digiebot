<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
 <script type="text/javascript">
  $(function () {
      $('#datepicker').flatpickr({enableTime: true,
      dateFormat: "Y-m-d H:i",
    });
  });
</script>
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
  .wbs_buyer_prog_main
  {
    background: #707070;
  height:24px;
    /*color: #232323;*/
  }
  .buyer_prog_main
  {
     background: #707070;
     /*color: #232323;*/
  }
  .seller_prog_main
  {
     background: #707070;
     /*color: #232323;*/
  }
  .wbs_seller_prog_main
  {
     background: #707070;
     /*color: #232323;*/
  }
.bottom_prog_box {
    position: absolute;
    bottom: 0px;
    left: 0;
    width: 100%;
    height: 150px;
    background: #252525;
    padding: 10px 20px;
}
.bottom_prog_left {
    float: left;
    width: 30%;
    padding-left: 15px;
}
.bottom_prog_one {
    float: left;
    width: 100%;
    margin-bottom: 5px;
    height: 40px;
    padding: 5px;
    background: #333;
    border-radius: 0px;
}
.bottom_prog_two {
    float: left;
    width: 100%;
    height: 40px;
    margin-bottom: 5px;
    padding: 5px;
    background: #333;
    border-radius: 0px;
}
.bottom_prog_three {
    float: left;
    width: 100%;
    height: 40px;
    border-radius: 0px;
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
  border-radius: 0px;
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
    padding: 60px 15px 125px;
    position: relative;
}
  .simple_p.light_gray_v_p
  {
    background: #2B2B2B;
  }
  .wbs_blu_prog {
    border-bottom: 0px;
  }
  .blu_prog {
    border-bottom: 0px;
  }
  .wbs_red_prog {
    border-bottom: 0px;
  }
  .red_prog {
    border-bottom: 0px;
  }
  .widthdepth{
  width: 30% !important;
}

.with_BS .price_cent_main {
    width: 34% !important;
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
.mrow{
    position: absolute;
    top: 0;
    width: 100%;
    left: 0;
    z-index: 1;
    background: #000;
    margin: 0;
    padding: 10px 15px 0;

}
.tradding_chart_1_sec{
    position: relative;
}

.verti_bar_prog_bottom {
    bottom: 0;
    background: rgb(0,91,3);
  background: -moz-linear-gradient(left, rgba(0,91,3,1) 0%, rgba(22,226,29,1) 35%, rgba(22,226,29,1) 70%, rgba(0,91,3,1) 100%);
  background: -webkit-linear-gradient(left, rgba(0,91,3,1) 0%,rgba(22,226,29,1) 35%,rgba(22,226,29,1) 70%,rgba(0,91,3,1) 100%);
  background: linear-gradient(to right, rgba(0,91,3,1) 0%,rgba(22,226,29,1) 35%,rgba(22,226,29,1) 70%,rgba(0,91,3,1) 100%); /
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#005b03', endColorstr='#005b03',GradientType=1 );
    color: #fff;
    height: 30%;
    position: absolute;
    text-align: center;
    width: 100%;
}
.verti_bar_prog_top {
  background: rgb(204,0,0);
background: -moz-linear-gradient(left, rgba(204,0,0,1) 0%, rgba(204,89,89,1) 35%, rgba(204,89,89,1) 70%, rgba(204,0,0,1) 100%, rgba(204,0,0,1) 100%);
background: -webkit-linear-gradient(left, rgba(204,0,0,1) 0%,rgba(204,89,89,1) 35%,rgba(204,89,89,1) 70%,rgba(204,0,0,1) 100%,rgba(204,0,0,1) 100%);
background: linear-gradient(to right, rgba(204,0,0,1) 0%,rgba(204,89,89,1) 35%,rgba(204,89,89,1) 70%,rgba(204,0,0,1) 100%,rgba(204,0,0,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#cc0000', endColorstr='#cc0000',GradientType=1 );
    color: #fff;
    height: 70%;
    position: absolute;
    text-align: center;
    top: 0;
    width: 100%;
}

.verti_bar_prog {
    float: left;
    height: 100%;
    position: relative;
    width: 100%;
    background: #45484d;
    background: -moz-radial-gradient(center, ellipse cover, #45484d 0%, #000000 100%);
    background: -webkit-radial-gradient(center, ellipse cover, #45484d 0%,#000000 100%);
    background: radial-gradient(ellipse at center, #45484d 0%,#000000 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#45484d', endColorstr='#000000',GradientType=1 );
}

/*----------------------------------------------------------*/




</style>
<style>
.prog_gre {
background-image: linear-gradient(90deg, rgb(218, 26, 9) 0%, rgb(206, 132, 11) 10%, rgb(194, 189, 14) 20%, rgb(52, 253, 29) 30%, rgb(30, 210, 99) 40%, rgb(29, 144, 50) 50%, rgb(23, 105, 175) 60%, rgb(15, 38, 143) 70%, rgb(77, 36, 173) 80%, rgb(93, 10, 170) 90%, rgb(33, 9, 55) 100%);
    float: left;
    width: 70%;
    height: 20px;

    margin-top: 10px;
    position: relative;
    border-radius: 0px;
}

.progx_line {
    position: absolute;
    left: 0;
    top: -15px;
    font-size: 11px;
  color:#fff;
}
.prog_gre_iner {
    float: left;
    width: 100%;
}

.prog_gre_val {
    width: 15px;
    background: #fff;
    height: 120%;
    border-radius: 50px;
    position: absolute;
    margin-left: -2px;
    left: 25%;
    box-shadow: 0 0 0px 1px rgba(0,0,0,0.3);
    top: -10%;
}
/*----------------------------------------------------------*/
.current_candle_hd , .current_candle_ft {
    background: #111 none repeat scroll 0 0;
    color: #fff;
    float: left;
    font-size: 12px;
    font-weight: bold;
    padding: 7px 10px;
    text-align: center;
    width: 100%;
  height:45px;
}
.towinone {
    float: right;
    max-height: 750px;
    overflow-y: auto;
    width: 70%;
}
@media(max-width:1400px){
  .verti_bar_prog_top > span {
    font-size: 10px;
  }
  .verti_bar_prog_bottom > span {
    font-size: 10px;
  }
  .current_candle_hd, .current_candle_ft {
    font-size: 10px;
  }
  .mrow .btn {
    font-size: 12px;
    padding: 7px 8px;
  }
}
</style>

<style>
.percentail_indicaters {
    float: left;
    width: 100%;
    height: 85px;
    background: #333;
}
.col-pind {
    float: left;
    width: 10%;
    padding: 2px;
    height: 85px;
}
.col-pind-box {
    float: left;
    width: 100%;
    height: 14.5px;
    background: #f44336;
    margin-bottom: 2px;
    border-radius: 2px;
}

.daba-black .col-pind-box {
    background: #000;
}
.daba-yellow .col-pind-box {
    background: #ff9800;
}
.daba-blue .col-pind-box {
    background: #2196f3;
}
.daba-red .col-pind-box {
    background: #f11919;
}
.daba-green .col-pind-box {
    background: #5cbc32;
}
.daba-orange .col-pind-box {
    background: #FF5722;
}

.black-tooltip + .tooltip > .tooltip-inner {background-color: #000;}
</style>
<div id="content" style="background:#232323; padding-bottom: 0;">
<!--h1 class="bg-white content-heading border-bottom">Chart 3</h1>
  <div class="bg-white innerAll border-bottom">
	<ul class="menubar">
    	<li class="active"><a href="<?php echo SURL; ?>/admin/dashboard/chart3">Chart 3</a></li>
	</ul>
  </div-->
<style>
  .btn-down-open {
    position: fixed;
    display: none;
    top: 50px;
    right: 25px;
    background: #FF5722;
    color: #fff;
    height: 50px;
    text-align: center;
    cursor: pointer;
    width: 50px;
    border-radius: 50%;
    z-index: 99999;
    padding-top: 4px;
    font-size: 25px;
    transition: 0.3s;
    box-shadow: 0 0 62px 4px rgba(0,0,0,0.54);
}

.btn-down-open:hover {
    padding-top: 8px;
}

.bticon-popup {
    position: fixed;
    width: 80%;
    left: 10%;
    height: 80%;
    top: 10%;
    background: #241146;
    color:#fff;
    z-index: 99999;
    /* overflow-y: auto; */
    border-radius: 15px;
    box-shadow: 0 0 57px 8px rgba(0,0,0,0.5);
    border: 7px solid #03A9F4;
    padding: 20px 0px 20px 20px;
    display: none;
}

.btn-down-close {
    position: absolute;
    right: 0px;
    top: 0px;
    height: 30px;
    cursor: pointer;
    width: 30px;
    background: #FF5722;
    border-radius: 0 0 0 15px;
    text-align: center;
    font-size: 22px;
    line-height: 0;
    padding-top: 3px;
    color: #fff;
}

pre {
  color: #fff;
  background-color: #241146;
  border: none;
}
</style>
<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery("body").on("click",".btn-down-open",function(){
  	var symbol = $('#coin_val').val();
  	var datetime = $('#datepicker').val();
      jQuery.ajax({
        url:"<?php echo SURL; ?>admin/barrier_trigger_simulator/create_and_buy_order/"+symbol+"/",
        type:"POST",
        data:{datetime_simulator:datetime},
        success: function(resp) {
          jQuery('.do-some-thing').html(resp)
        }
      });
      jQuery(".bticon-popup").fadeIn(700);
    });
    jQuery("body").on("click",".btn-down-close",function(){
      jQuery(".bticon-popup").fadeOut(700);
    });
  });
</script>
<div class="btn-down-open">
  <i class="fa fa-angle-down"></i>
</div>
<div class="bticon-popup">
  <div class="btn-down-close">
    <i class="fa fa-angle-up"></i>
  </div>
  <div class="bticon-popup-iner" style="height:100%; overflow-y: auto;">
    <div class="do-some-thing"></div>
  </div>
</div>

    <div class="innerAll" style="padding-top: 0;">
        <div class="row">
            <section class="tradding_chart_1_sec">
            <div class="mrow">
                <div class="col-md-2">
                    <select id="coin_val" class="form-control" style="background-color: #2B2B2B; color:white; border: none;">
                    <option value="">Select a Symbol</option>
                   <?php foreach ($coins as $key => $value) {
    echo '<option value="' . $value['symbol'] . '">' . $value['symbol'] . '</option>';
}?>
                </select>
                </div>

                <div class="col-md-2" id="response_prices">

                </div>

               <div class="col-md-2">
                    <div class="form-group">
                    <input type="text" style="background-color: #2B2B2B; color:white; border: none;" name="date" id="datepicker" placeholder="Select Date" class="form-control datetime_picker">
                </div>
               </div>
               <div class="col-md-6">
                    <div class="form-action">
                    <button class="btn btn-warning submtbtn" id="onehourback" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Loading...">-1 Hour</button>
<!--                     <button style="background-color: #eb4d4b; color:white;" class="btn  submtbtn" id="halfhourback">-30 Min</button>
 -->                    <button class="btn btn-info submtbtn" id="fifminback" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Loading...">-15 Min</button>
                    <button style="background-color: #a55eea; color:white;" class="btn submtbtn" id="fiveminback">-5 Min</button>
                    <button class="btn btn-danger submtbtn" id="oneminback" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Loading...">-1 Min</button>
                    <button class="btn btn-success submtbtn" id="submtbtn" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Loading...">Go</button>
                    <button class="btn btn-danger submtbtn" id="oneminforward" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Loading...">+1 Min</button>
                    <button style="background-color: #a55eea; color:white;" class="btn submtbtn" id="fiveminforward" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Loading...">+5 Min</button>
                    <button class="btn btn-info submtbtn" id="fifminforward" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Loading...">+15 Min</button>
                   <!--  <button style="background-color: #eb4d4b; color:white;" class="btn submtbtn" id="halfhourforward">+30 Min</button> -->
                    <button class="btn btn-warning submtbtn" id="onehourforward" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Loading...">+1 Hour</button>
                </div>
               </div>
                </div>
                <div class="tradding_chart_1_main">
                    <div class="towinone">
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

                            <li class="price_s_r_li with_BS" d_price="<?php echo num($price); ?>" index="<?php echo $i; ?>">
                                  <!-- <div class="wbs_buyer_prog_main">
                                    <div class="wbs_blu_prog">
                                        <div class="wbs_blue_prog_p">1867</div>
                                        <div class="wbs_blue_prog_bar" WBS_d_prog_percent="10"></div>
                                    </div>
                                </div> -->
                                <div class="buyer_prog_main widthdepth">
                                    <div class="blu_prog">
                                      <div class="blue_prog_p"><?php echo $value['sell_quantity']; ?></div>
                                      <?php
$sell_percentage = round($value['sell_quantity'] * 100 / $biggest_value);
        if ($sell_percentage == 100) {
            $big_price = num($price);
        }
        ?>
                                      <div class="blue_prog_bar" d_prog_percent="<?php echo $sell_percentage; ?>"></div>
                                    </div>
                                </div>
                                <div class="price_cent_main">
                                    <span class="simple_p gray_v_p"><?php echo num($price); ?></span>
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
                                <div class="wbs_seller_prog_main">
                                    <div class="wbs_red_prog">
                                        <div class="wbs_red_prog_p">169</div>
                                        <div class="wbs_red_prog_bar" wbs_d_prog_percent="10"></div>
                                    </div>
                                </div>
                            </li>

                            <?php }
}
?>

                           <li class="price_s_r_li with_BS" d_price="<?php echo num($market_value); ?>" index="<?php echo $i++; ?>">
                                <div class="wbs_buyer_prog_main widthdepth">
                                    <div class="wbs_blu_prog">
                                        <div class="wbs_blue_prog_p">1867</div>
                                        <div class="wbs_blue_prog_bar" WBS_d_prog_percent="10"></div>
                                    </div>
                                </div>
                                <!-- <div class="buyer_prog_main">
                                </div> -->
                                <div class="price_cent_main">
                                    <span class="simple_p white_v_p" id="response2222">
                                     <span class="GCV_color_default"><?php echo num($market_value); ?></span>
                                    </span>
                                </div>
                                <div class="seller_prog_main">
                                    <div class="red_prog">
                                    </div>
                                </div>
                                <div class="wbs_seller_prog_main">
                                    <div class="wbs_red_prog">
                                        <div class="wbs_red_prog_p">169</div>
                                        <div class="wbs_red_prog_bar" wbs_d_prog_percent="10"></div>
                                    </div>
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
                                    <span class="simple_p light_gray_v_p"><?php echo num($price22); ?></span>
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
  <style>
  .bar_candle.bc_15{
    width:16.6%;
  }
  </style>
   <div class="barcalnd">
      <div class="bar_candle bc_15">
         <div class="current_candle">
            <div class="current_candle_hd">
               T<sub>3</sub>&nbsp;COT
            </div>
            <div class="current_candle_gr">
               <div class="verti_bar_prog verti_bar_prog_2">
                  <div class="verti_bar_prog_top last_bid" d_vbppercent="70" style="height: 70%;">
                     <span id="last_candle_bid">70</span>
                  </div>
                  <div class="verti_bar_prog_bottom last_ask" d_vbppercent="30" style="height: 30%;">
                     <span id="last_candle_ask">30</span>
                  </div>
               </div>
            </div>
            <div class="current_candle_ft">

            </div>
         </div>
      </div>
      <div class="bar_candle bc_15">
         <div class="current_candle">
            <div class="current_candle_hd">
               T<sub>2</sub>&nbsp;COT
            </div>
            <div class="current_candle_gr">
               <div class="verti_bar_prog verti_bar_prog_2">
                  <div class="verti_bar_prog_top curr_bid" d_vbppercent="70" style="height: 70%;">
                     <span id="current_candle_bid">70</span>
                  </div>
                  <div class="verti_bar_prog_bottom curr_ask" d_vbppercent="30" style="height: 30%;">
                     <span id="current_candle_ask">30</span>
                  </div>
               </div>
            </div>
            <div class="current_candle_ft">

            </div>
         </div>
      </div>
      <div class="bar_candle bc_15">
         <div class="current_candle">
            <div class="current_candle_hd">
               T<sub>1</sub>&nbsp;COT
            </div>
            <div class="current_candle_gr">
               <div class="verti_bar_prog verti_bar_prog_2">
                 <div class="verti_bar_prog_top roll_bid" d_vbppercent="70" style="height: 70%;">
                  <span id="roll_candle_bid">70</span>
                </div>
                <div class="verti_bar_prog_bottom roll_ask" d_vbppercent="30" style="height: 30%;">
                  <span id="roll_candle_ask">30</span>
                </div>
               </div>
            </div>
            <div class="current_candle_ft">

            </div>
         </div>
      </div>
      <div class="bar_candle bc_15">
         <div class="current_candle">
            <div class="current_candle_hd">
               T<sub>1</sub>&nbsp;LTC
               <span id="contract_size"></span>
            </div>
            <div class="current_candle_gr">
               <div class="verti_bar_prog verti_bar_prog_2">
                  <div class="verti_bar_prog_top bid_m" d_vbppercent="70" style="height: 70%;">
                     <span id="last_candle_bid_m">70</span>
                  </div>
                  <div class="verti_bar_prog_bottom ask_m" d_vbppercent="30" style="height: 30%;">
                     <span id="last_candle_ask_m">30</span>
                  </div>
               </div>
            </div>
            <div class="current_candle_ft two_m_candle">
               Last Candle
            </div>
         </div>
      </div>
      <div class="bar_candle bc_15">
         <div class="current_candle">
            <div class="current_candle_hd">
               T<sub>2</sub>&nbsp;LTC
               <span id="contract_time"></span>
            </div>
            <div class="current_candle_gr">
               <div class="verti_bar_prog verti_bar_prog_2">
                  <div class="verti_bar_prog_top bid_h" d_vbppercent="70" style="height: 70%;">
                     <span id="last_candle_bid_h">70</span>
                  </div>
                  <div class="verti_bar_prog_bottom ask_h" d_vbppercent="30" style="height: 30%;">
                     <span id="last_candle_ask_h">30</span>
                  </div>
               </div>
            </div>
            <div class="current_candle_ft two_h_candle">

            </div>
         </div>
      </div>





      <div class="bar_candle bc_15">
         <div class="current_candle">
            <div class="current_candle_hd">
              T<sub>4</sub>COT
            </div>
            <div class="current_candle_gr">
               <div class="verti_bar_prog verti_bar_prog_2">
                 <div class="verti_bar_prog_top roll_bid1" d_vbppercent="70" style="height: 70%;">
                  <span id="roll_candle_bid1">70</span>
                </div>
                <div class="verti_bar_prog_bottom roll_ask1" d_vbppercent="30" style="height: 30%;">
                  <span id="roll_candle_ask1">30</span>
                </div>
               </div>
            </div>
            <div class="current_candle_ft">

            </div>
         </div>
      </div>
      <!-- NEW LINE CANDLES -->
      <div class="bar_candle bc_15">
         <div class="current_candle">
            <div class="current_candle_hd">
               TAF
            </div>
            <div class="current_candle_gr">
               <div class="verti_bar_prog verti_bar_prog_2">
                  <!-- <div class="verti_bar_prog_top bid_h" d_vbppercent="70" style="height: 70%;">
                     <span id="last_candle_bid_h">70</span>
                  </div> -->
                  <div class="verti_bar_prog_bottom ask_h_p" d_vbppercent="30" style="height: 30%;">
                     <span id="last_candle_ask_h_p">30</span>
                  </div>
               </div>
            </div>
            <div class="current_candle_ft">

            </div>
         </div>
      </div>
      <div class="bar_candle bc_15">
         <div class="current_candle">
            <div class="current_candle_hd">
               TBF
            </div>
            <div class="current_candle_gr">
               <div class="verti_bar_prog verti_bar_prog_2">
                  <!-- <div class="verti_bar_prog_top bid_h" d_vbppercent="70" style="height: 70%;">
                     <span id="last_candle_bid_h">70</span>
                  </div> -->
                 <div class="verti_bar_prog_bottom bid_h_p" d_vbppercent="30" style="height: 30%; background: linear-gradient(to right, rgba(204,0,0,1) 0%,rgba(204,89,89,1) 35%,rgba(204,89,89,1) 70%,rgba(204,0,0,1) 100%,rgba(204,0,0,1) 100%);">
                     <span id="last_candle_bid_h_p">30</span>
                  </div>
               </div>
            </div>
            <div class="current_candle_ft">

            </div>
         </div>
      </div>
      <div class="bar_candle bc_15">
         <div class="current_candle">
            <div class="current_candle_hd">
                T<sub>3</sub>LTC
                <span id="contract_size2"></span>
            </div>
            <div class="current_candle_gr">
               <div class="verti_bar_prog verti_bar_prog_2">
                  <div class="verti_bar_prog_top bid_h2" d_vbppercent="70" style="height: 70%;">
                     <span id="last_candle_bid_h2">70</span>
                  </div>
                  <div class="verti_bar_prog_bottom ask_h2" d_vbppercent="30" style="height: 30%;">
                     <span id="last_candle_ask_h2">30</span>
                  </div>
               </div>
            </div>
            <div class="current_candle_ft two_h_candle2">

            </div>
         </div>
      </div>
      <div class="bar_candle bc_15">
         <div class="current_candle">
            <div class="current_candle_hd">
                T<sub>4</sub>LTC
                <span id="contract_size3"></span>
            </div>
            <div class="current_candle_gr">
               <div class="verti_bar_prog verti_bar_prog_2">
                  <div class="verti_bar_prog_top bid_h3" d_vbppercent="70" style="height: 70%;">
                     <span id="last_candle_bid_h3">70</span>
                  </div>
                  <div class="verti_bar_prog_bottom ask_h3" d_vbppercent="30" style="height: 30%;">
                     <span id="last_candle_ask_h3">30</span>
                  </div>
               </div>
            </div>
            <div class="current_candle_ft two_h_candle3">

            </div>
         </div>
      </div>
      <div class="bar_candle bc_15">
         <div class="current_candle">
            <div class="current_candle_hd">

            </div>
            <div class="current_candle_gr">
               <div class="verti_bar_prog verti_bar_prog_2">
                  <!-- <div class="verti_bar_prog_top bid_h" d_vbppercent="70" style="height: 70%;">
                     <span id="last_candle_bid_h">70</span>
                  </div>
                  <div class="verti_bar_prog_bottom ask_h" d_vbppercent="30" style="height: 30%;">
                     <span id="last_candle_ask_h">30</span>
                  </div> -->
               </div>
            </div>
            <div class="current_candle_ft">

            </div>
         </div>
      </div>
      <div class="bar_candle bc_15">
         <div class="current_candle">
            <div class="current_candle_hd">

            </div>
            <div class="current_candle_gr">
               <div class="verti_bar_prog verti_bar_prog_2">
                  <!-- <div class="verti_bar_prog_top bid_h" d_vbppercent="70" style="height: 70%;">
                     <span id="last_candle_bid_h">70</span>
                  </div>
                  <div class="verti_bar_prog_bottom ask_h" d_vbppercent="30" style="height: 30%;">
                     <span id="last_candle_ask_h">30</span>
                  </div> -->
               </div>
            </div>
            <div class="current_candle_ft">

            </div>
         </div>
      </div>
   </div>
</div>

<style>
	.main-colors-box {
	float: right;
	width: 70%;
	height: 173px;
	margin-top: 5px;
	padding-top: 21px;
	}

	.col-color-box {
	float: left;
	width: 14%;
	box-shadow: 0 0 32px 3px rgba(0,0,0,0.5);
	overflow: hidden;
	border-radius: 5px;
	margin-left: 1%;
	margin-right: 1%;
	}

	.color-box-hd {
	float: left;
	width: 100%;
	background: #000;
	border-radius: 5px 5px 0 0;
	}

	.color-box-hd h2 {
	float: left;
	width: 100%;
	margin: 5px 0 0 0 !important;
	font-size: 15px;
	font-weight: bold;
	padding: 10px;
	color: #fff;
	text-align: center;
	border-radius: 5px 5px 0 0;
	min-height: 53px;
	}

	.color-box-bd {
	float: left;
	width: 100%;
	min-height: 85px;
	text-align: center;
	}

	.color-box-bd h3 {
	float: left;
	width: 100%;
	color: #fff;
	margin: 0;
	font-size: 20px;
	padding: 10px 0 5px;
	}

	.color-box-bd h4 {
	float: left;
	width: 100%;
	color: #fff;
	font-size: 14px;
	margin: 0;
	padding: 5px 5px 10px;
	}

	.col-c-b-white .color-box-bd {
	background: #fff;
	}

	.col-c-b-white .color-box-bd h3 {
	color: #2196F3;
	}

	.col-c-b-white .color-box-bd h4 {
	color: #3F51B5;
	}

	.col-c-b-blue .color-box-bd {
	background: #3F51B5;
	}

	.col-c-b-red .color-box-bd {
	background: #F44336;
	}

	.col-c-b-skyblue .color-box-bd {
	background: #00BCD4;
	}

	.col-c-b-mehroon .color-box-bd {
	background: #7b0817;
	}

	.color-box-up-down h3 {
	background: #6ec30c;
	padding: 12px;
	float: left;
	width: 100%;
	}

	.color-box-up-down h4 {
	padding: 10px;
	font-size: 18px;
	padding: 11px;
	background: #ef2617;
	float: left;
	width: 100%;
	}
</style>
<div class="main-colors-box">
  <div class="col-color-box col-c-b-white">
      <div class="color-box-hd">
          <h2>SC</h2>
        </div>
        <div class="color-box-bd">
          <h3 id="response_swing_candle">LL</h3>
            <h4></h4>
        </div>
    </div>
    <div class="col-color-box col-c-b-blue">
      <div class="color-box-hd">
          <h2>MCP</h2>
        </div>
        <div class="color-box-bd">
          <h3 id="response_contract_info">32.63K+</h3>
        </div>
    </div>
    <div class="col-color-box col-c-b-red">
      <div class="color-box-hd">
          <h2>SPL</h2>
        </div>
        <div class="color-box-bd">
          <h3 id="response_bid_contract_info">31.99K+</h3>
        </div>

    </div>
    <div class="col-color-box col-c-b-skyblue">
      <div class="color-box-hd">
          <h2>BPL</h2>
        </div>
        <div class="color-box-bd">
          <h3 id="response_ask_contract_info">LL</h3>
        </div>
    </div>
    <div class="col-color-box col-c-b-mehroon">
      <div class="color-box-hd">
          <h2>OBW</h2>
        </div>
        <div class="color-box-bd">
          <h3 id="response_big_wall">Up</h3>
            <h4></h4>
        </div>
    </div>
    <div class="col-color-box">
      <div class="color-box-hd">
          <h2>OBD</h2>
        </div>
        <div class="color-box-bd color-box-up-down">
          <h4 id="down">Down 1</h4>
          <h3 id="up">Up 4</h3>
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

                            <div class="percentail_indicaters">
                            	<div class="col-pind black-tooltip" data-toggle="tooltip" title="Black wall">
                                	<div class="col-pind-box"  style="visibility:hidden;"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                </div>
                                <div class="col-pind black-tooltip" data-toggle="tooltip" title="Black wall">
                                	<div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                </div>
                                <div class="col-pind black-tooltip" data-toggle="tooltip" title="Black wall">
                                	<div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                </div>
                                <div class="col-pind black-tooltip" data-toggle="tooltip" title="Black wall">
                                	<div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                </div>
                                <div class="col-pind black-tooltip" data-toggle="tooltip" title="Black wall">
                                	<div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                </div>
                                <div class="col-pind black-tooltip" data-toggle="tooltip" title="Black wall">
                                	<div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                </div>
                                <div class="col-pind black-tooltip" data-toggle="tooltip" title="Black wall">
                                	<div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                </div>
                                <div class="col-pind black-tooltip" data-toggle="tooltip" title="Black wall">
                                	<div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                </div>
                                <div class="col-pind black-tooltip" data-toggle="tooltip" title="Black wall">
                                	<div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                </div>
                                <div class="col-pind black-tooltip" data-toggle="tooltip" title="Black wall">
                                	<div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
                                    <div class="col-pind-box"></div>
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
            <script type="text/javascript">
              var zi=0;
              var zin = 0;
              var zin2 = 0;
              function autoload_trading_chart_data(type){

                  var market_value = $("#market_value").val();
                  var previous_market_value = $("#previous_market_value").val();
                  var datetime = $('#datepicker').val();
                  var symbol = $('#coin_val').val();
                  $.ajax({
                    type:'POST',
                    url:'<?php echo SURL ?>admin/chart3_group_trigger/autoload_trading_chart_data',
                    data: {datetime:datetime, type:type,symbol:symbol},
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
                    //   if(typeof target_zones_arr !== "undefined" && target_zones_arr.length!=0){
                    //   $.each(target_zones_arr, function(index, itemData) {
                    //     zi++;
                    //     MakeOrangeGreen_box(zi,itemData.start_value, itemData.end_value, itemData.type);

                    //   });
                    // }


                      $('#response_target_zone_date').html(split_response[5]);

                      Get_CurrentCandel_and_InZoon_GraphVal();

                      Get_BlueProgBar_GraphVal();
                      Get_RedProgBar_GraphVal();

                      WbsGet_BlueProgBar_GraphVal();
                      WbsGet_RedProgBar_GraphVal();

                      /*var Sell_orders_arr = jQuery.parseJSON(split_response[6]);
                      $.each(Sell_orders_arr, function(index, itemData) {
                        zin++;

                        if(itemData.trail_check =='yes'){
                          var new_sell_price = itemData.sell_trail_price;
                        }else{
                          var new_sell_price = itemData.sell_price;
                        }
                        SelectedOrderPrice(zin,itemData._id.$oid,new_sell_price,"sell",itemData.quantity);

                      });*/


                      /*var Buy_orders_arr = jQuery.parseJSON(split_response[7]);
                      $.each(Buy_orders_arr, function(index, itemData) {

                        zin2++;

                        if(itemData.trail_check =='yes'){
                          var new_sell_price = itemData.buy_trail_price;
                        }else{
                          var new_sell_price = itemData.price;
                        }

                        SelectedOrderPrice(zin2,itemData._id.$oid,new_sell_price,"buy",itemData.quantity);

                      });
*/

                      var last_candle_ask = split_response[9];
                      var last_candle_bid = split_response[8];
                      var current_candle_ask = split_response[11];
                      var current_candle_bid = split_response[10];
                      var curr_bid = split_response[12];
                      var curr_ask = split_response[13];
                      var last_bid = split_response[14];
                      var last_ask = split_response[15];

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

                      $("#datepicker").val(split_response[16])
                      $("#response_swing_candle").html(split_response[17])
                      $("#response_contract_info").html(split_response[18])
                      $("#response_bid_contract_info").html(split_response[19])
                      $("#response_ask_contract_info").html(split_response[20])
                      $("#up").html("Up " + split_response[21])
                      $("#down").html("Down " + split_response[22])

                      var str = split_response[23]
                       $("#response_big_wall").html(str.toUpperCase())
                       if (str == 'up') {
                        $('.widget1122').css({'background-color':'#D7484E'})
                       }else if(str == 'down'){
                        $('.widget1122').css({'background-color':'#3BA8D1'})
                       }

                       $('#response_pressure').html(split_response[24]);
                       $('#response_prices').html(split_response[25]);

                        var roll_bid_per = split_response[26];
                        var roll_ask_per = split_response[27];
                        var roll_bid = split_response[28];
                        var roll_ask = split_response[29];

                      $("#roll_candle_bid").html(roll_bid);
                      $(".roll_bid").attr("d_vbppercent",roll_bid_per+'%')
                      $(".roll_bid").height( roll_bid_per+'%')

                      $("#roll_candle_ask").html(roll_ask);
                      $(".roll_ask").attr("d_vbppercent",roll_ask_per+'%')
                      $(".roll_ask").height( roll_ask_per+'%')
                      /*setTimeout(function() {
                          autoload_trading_chart_data();
                      }, 2000);*/

                      var candle_m_n = split_response[30];
                        var candle_m = JSON.parse(candle_m_n);

                        var candle_h_n = split_response[31];
                        var candle_h = JSON.parse(candle_h_n)

                        $('#coin_val').val(split_response[32]);

                        var bid_p = split_response[33]
      			            var ask_p = split_response[34]

      			            $("#last_candle_bid_h_p").html(bid_p+"%");
      			            $(".bid_h_p").attr("d_vbppercent",bid_p+'%')
      			            $(".bid_h_p").height( bid_p+'%')

      			            $("#last_candle_ask_h_p").html(ask_p+"%");
      			            $(".ask_h_p").attr("d_vbppercent",ask_p+'%')
      			            $(".ask_h_p").height( ask_p+'%')
                        /*===============================================*/
                        $("#last_candle_ask_m").html(candle_m.ask_quantity);
                        $(".ask_m").attr("d_vbppercent",candle_m.asks+'%')
                        $(".ask_m").height( candle_m.asks+'%')
                        $(".two_m_candle").html("("+candle_m.time_string+")")
                        $("#contract_size").html("("+candle_m.period+")")
                        $("#last_candle_bid_m").html(candle_m.bid_quantity);
                        $(".bid_m").attr("d_vbppercent",candle_m.bids+'%')
                        $(".bid_m").height( candle_m.bids+'%')
                        /*===============================================*/
                        $("#last_candle_ask_h").html(candle_h.ask_quantity);
                        $(".ask_h").attr("d_vbppercent",candle_h.asks+'%')
                        $(".ask_h").height( candle_h.asks+'%')
                        $(".two_h_candle").html("("+candle_h.time_string+")")
                        $("#contract_time").html("("+candle_h.period+")")
                        $("#last_candle_bid_h").html(candle_h.bid_quantity);
                        $(".bid_h").attr("d_vbppercent",candle_h.bids+'%')
                        $(".bid_h").height( candle_h.bids+'%')
                        /*===============================================*/


                        var candle_m_n2 = split_response[35];
                        var candle_m2 = JSON.parse(candle_m_n2);

                        var candle_h_n2 = split_response[36];
                        var candle_h2 = JSON.parse(candle_h_n2)

                        /*===============================================*/
                        $("#last_candle_ask_h2").html(candle_m2.ask_quantity);
                        $(".ask_h2").attr("d_vbppercent",candle_m2.asks+'%')
                        $(".ask_h2").height( candle_m2.asks+'%')
                        $(".two_h_candle2").html("("+candle_m2.time_string+")")
                        $("#contract_size2").html("("+candle_m2.period+")")
                        $("#last_candle_bid_h2").html(candle_m2.bid_quantity);
                        $(".bid_h2").attr("d_vbppercent",candle_m2.bids+'%')
                        $(".bid_h2").height( candle_m2.bids+'%')
                        /*===============================================*/
                        $("#last_candle_ask_h3").html(candle_h2.ask_quantity);
                        $(".ask_h3").attr("d_vbppercent",candle_h2.asks+'%')
                        $(".ask_h3").height( candle_h2.asks+'%')
                        $(".two_h_candle3").html("("+candle_h2.time_string+")")
                        $("#contract_size3").html("("+candle_h2.period+")")
                        $("#last_candle_bid_h3").html(candle_h2.bid_quantity);
                        $(".bid_h3").attr("d_vbppercent",candle_h2.bids+'%')
                        $(".bid_h3").height( candle_h2.bids+'%')
                        /*===============================================*/

                        var roll_bid_per1 = split_response[37];
                        var roll_ask_per1 = split_response[38];
                        var roll_bid1 = split_response[39];
                        var roll_ask1 = split_response[40];

                        $("#roll_candle_bid1").html(roll_bid1);
                        $(".roll_bid1").attr("d_vbppercent",roll_bid_per1+'%')
                        $(".roll_bid1").height( roll_bid_per1+'%')

                        $("#roll_candle_ask1").html(roll_ask1);
                        $(".roll_ask1").attr("d_vbppercent",roll_ask_per1+'%')
                        $(".roll_ask1").height( roll_ask_per1+'%')
                        $('#'+type).button('reset');
                        $(".btn-down-open").show();

                    }
                  });

              }//end autoload_trading_chart_data222()

              //autoload_trading_chart_data();
            $('body').on('click','.submtbtn',function(e){
                var $this = $(this);
                $this.button('loading');
                var type = $(this).attr('id');
                autoload_trading_chart_data(type);
            });

             $('body').on('change','#coin_val',function(e){
                var type = 'submtbtn';
                var $this = $('#submtbtn');
                $this.button('loading');
                autoload_trading_chart_data(type);
            });

             $('body').on('change','#price_value',function(e){
                var type = 'submtbtn';
                var $this = $('#submtbtn');
                $this.button('loading');
                var datetime = $('#price_value').val();
                $('#datepicker').val(datetime);

                autoload_trading_chart_data(type);
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
              // var windowHeight = jQuery(window).height();
              // var setHv = parseInt(windowHeight)-300;
              // jQuery(".verti_bar_prog").height(setHv);
              // jQuery(".towinone").height(setHv+50);
              var windowHeight = jQuery(window).height();
              var navbar_navbar_fixed_top = jQuery(".navbar.navbar-fixed-top").height();
              var mrow = jQuery(".mrow").height();
              var bottom_prog_box = jQuery(".bottom_prog_box").height();

              var actual_h = windowHeight - navbar_navbar_fixed_top - mrow - bottom_prog_box - 15;
              var actual_h_calc = actual_h/2;

              jQuery(".bar_candle .current_candle").height(actual_h_calc);

              var hdft_height = 45;
              //jQuery(".current_candle_hd , .current_candle_ft").height(hdft_height);

              var current_candle_gr_height = actual_h_calc - (hdft_height*2);
              jQuery(".current_candle_gr").height(current_candle_gr_height);


              var main_colors_box_height = jQuery(".main-colors-box").height();
              var towinone = actual_h - main_colors_box_height;
              jQuery(".towinone").height(towinone);
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

                var Sval = end_val; //jQuery(".Sval").val();
                var Eval = start_val; //jQuery(".Eval").val();
                var BSval = type; //jQuery(".BSval").val();

              var thisplength_S = jQuery("[d_price='"+Sval+"']").length;
                var thisplength_E = jQuery("[d_price='"+Eval+"']").length;

                /*var ValueArray = [];
                jQuery(".shoval").text('');
                if(thisplength_S > 0 || thisplength_E > 0 ){
                jQuery("[d_price]").each(function(index, element) {

                if(jQuery(this).attr('d_price') <= Sval){
                         console.log(Sval+"====>"+Eval);
                        if(jQuery(this).attr('d_price') >= Eval){
                            var thisSEval = jQuery(this).attr('d_price');
                            jQuery(".shoval").append(thisSEval+',');
                            ValueArray.push(thisSEval);
                        }
                    }

              });
            }else{
              return false;
            }*/
              var temp1 = Sval;
              var temp2 = Eval;
              if(thisplength_S == 0 || thisplength_E == 0){
                  var closest = null;
                  var closest1 = null;
                  $(('.price_s_r_ul > li')).each(function(index){
                       var pos = $(this).attr('d_price');
                       var second_li = $(this).next('.price_s_r_ul > li').attr('d_price');
                      /*if (closest == null || Math.abs(pos - temp) < Math.abs(closest - temp)) {
                          closest = pos;
                      }
                      BoSval = closest;*/

                      if (temp1 < pos && temp1 >= second_li) {
                          closest = second_li;
                      }
                      if (temp2 < pos && temp2 >= second_li) {
                          closest1 = second_li;
                      }


                      Sval = closest;
                      Eval = closest1;

                  });

              }

            /*	var ValueArrayLength = ValueArray.length;
                var ValueArrayLast = ValueArrayLength-1;

                var makSval = ValueArray[0];
                var makEval = ValueArray[ValueArrayLast];*/

            /*	Sval = makSval
                Eval = makEval*/

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
                var OG_HTML = '<div class="G_graph_box '+GB_cls+'" style="top:'+setTop+'px; height:'+setHeight+'px; z-index:'+zi+';"><div class="TP_line TP_line_start"><div class="TP_line_text">TP Buyer Zoon 1</div></div><div class="TP_line TP_line_end"><div class="TP_line_text">TP Buyer Zoon 2</div></div></div>';
                jQuery(".price_sec_center_main").prepend(OG_HTML);

            }


            var zin = 0;
            function SelectedOrderPrice(zin,order_id,val,type,Qty){

                var vary_first_price = jQuery(".price_s_r_li:first").attr("d_price");
                var vary_last_price = jQuery(".price_s_r_li:last").attr("d_price");

                var BoSval = val ; //jQuery(".BoSval").val();
                var SbOs =  type ;   //jQuery(".SbOs").val();

              var thisplength_S = jQuery("[d_price='"+BoSval+"']").length;
                var temp = BoSval;
                /*alert(jQuery("[d_price='"+BoSval+"']").val())*/
              if (temp == '') { return false; }
                if(thisplength_S ==0){
                  var closest = null;
                  $(('.price_s_r_ul > li')).each(function(index){
                       var pos = $(this).attr('d_price');
                       var second_li = $(this).next('.price_s_r_ul > li').attr('d_price');
                      /*if (closest == null || Math.abs(pos - temp) < Math.abs(closest - temp)) {
                          closest = pos;
                      }
                      BoSval = closest;*/

                      if (temp < pos && temp >= second_li) {
                          closest = second_li;
                      }
                      BoSval = closest;

                  });

              }

                if(SbOs == 'buy'){
                    var GB_cls = 'drawPrice_b';
                }else{
                    var GB_cls = 'drawPrice_s';
                }

                var Sval_index = jQuery("li[d_price='"+BoSval+"']").attr("index");
                var Eval_index = jQuery("li[d_price='"+BoSval+"']").attr("index");
              var items = $('ul.price_s_r_ul>li');

                var setIndxS= parseInt(Sval_index)+parseInt(1);
                var setIndxE= parseInt(Eval_index)+parseInt(1);


                if(setIndxE == 0){
                    setIndxE = 1;
                }

                var setTop = (setIndxS*25) - 25;

                //var setHeight = (parseInt(setIndxE)-parseInt(setIndxS)+1)*25;
                var zindex = 9999 + parseInt(zin);

                var OG_HTML = '<div style="top:'+setTop+'px; z-index:'+zindex+';" class="drawPrice '+GB_cls+'"><div class="drawPrice_v" order_id="'+order_id+'" type="'+type+'">'+Qty+'</div><div class="drawPrice_line"></div></div>';
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
                    url:'<?php echo SURL ?>admin/dashboard/get_order_details/'+order_id+'/'+type,
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
                    url:'<?php echo SURL ?>admin/dashboard/get_edit_order_details/'+order_id+'/'+type,
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
                    url:'<?php echo SURL ?>admin/dashboard/update_order_details',
                    data: postData,
                    success:function(response){
                      $('#response_order_details').html(response);
                    }
                });

                jQuery(".boat_points").addClass("active");

            });






            jQuery("body").on("click",".boat_points_close",function(){
                jQuery(".boat_points").removeClass("active");
            });

            </script>
       	</div>
    </div>

</div>
<!-- // Content END -->
