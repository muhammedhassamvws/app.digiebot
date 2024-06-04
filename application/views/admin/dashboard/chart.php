<div id="content" style="background:#232323;">
  
  
<div class="innerAll spacing-x2">
<div class="row">
     
<section class="tradding_chart_1_sec">
  <div class="form_sec">
      <div class="forgrou">
          <div class="zindexing">1</div>
        </div>
        <div class="forgrou">
          <label>MeaterVal</label>
          <input type="number" class="Meater" value="73">
        </div>
        <div class="forgrou">
          <label>StartValue</label>
            <select class="Sval"></select>
        </div>
        <div class="forgrou">
          <label>EndValue</label>
            <select class="Eval"></select>
        </div>
        <div class="forgrou">
          <label>Select Buy Or Sell</label>
            <select class="BSval">
              <option value="buy">Buy</option>
                <option value="sell">Sell</option>
            </select>
        </div>
        <div class="forgrou">
          <label>Action</label>
            <button class="submit">Submit</button>
        </div>
    </div>
  <div class="tradding_chart_1_main">
      <div class="towinone">
            <div class="price_s_r_main" id="response_trading_data">
                <ul class="price_s_r_ul">
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

                    <li class="price_s_r_li" d_price="<?php echo $price;?>" index="1">
                        <div class="buyer_prog_main">
                            <div class="blu_prog">
                              <div class="blue_prog_p"><?php echo $value['sell_quantity'];?></div>
                              <?php 
                              $sell_percentage = round($value['sell_quantity'] * 100 / $biggest_value);
                              if($sell_percentage==100){
                                $big_price = $price;
                              }
                              ?>
                              <div class="blue_prog_bar" d_prog_percent="<?php echo $sell_percentage; ?>"></div>
                            </div>
                        </div>
                        <div class="price_cent_main">
                            <span class="simple_p gray_v_p"><?php echo $price;?></span>
                        </div>
                        <div class="seller_prog_main">
                            <div class="red_prog">
                              <div class="red_prog_p"><?php echo $value['buy_quantity'];?></div>
                              <?php 
                              $buy_percentage = round($value['buy_quantity'] * 100 / $biggest_value);
                              if($buy_percentage==100){
                                $big_price = $price;
                              }
                              ?>
                              <div class="red_prog_bar" d_prog_percent="<?php echo $buy_percentage; ?>"></div>
                            </div>
                        </div>  
                    </li>

                    <?php }
                    } 
                    ?> 
                   
                   <li class="price_s_r_li" d_price="2030.75" index="1">
                        <div class="buyer_prog_main">
                        </div>
                        <div class="price_cent_main">
                            <span class="simple_p white_v_p" id="response2222">
                             <span class="GCV_color_default"><?php echo $market_value; ?></span> 
                            </span>
                        </div>
                        <div class="seller_prog_main">
                            <div class="red_prog">
                            </div>
                        </div>  
                   </li>
                  
                   
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
                    
                    <li class="price_s_r_li" d_price="<?php echo $price22; ?>" index="1">
                        <div class="buyer_prog_main">
                            <div class="blu_prog">
                              <div class="blue_prog_p"><?php echo $value2['sell_quantity'];?></div>
                              <?php 
                              $sell_percentage2 = round($value2['sell_quantity'] * 100 / $biggest_value);
                              if($sell_percentage2==100){
                                $big_price = $price22;
                              }
                              ?>
                              <div class="blue_prog_bar" d_prog_percent="<?php echo $sell_percentage2; ?>"></div>
                            </div>
                        </div>
                        <div class="price_cent_main">
                            <span class="simple_p light_gray_v_p"><?php echo $price22;?></span>
                        </div>
                        <div class="seller_prog_main">
                            <div class="red_prog">
                              <div class="red_prog_p"><?php echo $value2['buy_quantity']; ?></div>
                              <?php 
                              $buy_percentage2 = round($value2['buy_quantity'] * 100 / $biggest_value);
                              if($buy_percentage2==100){
                                $big_price = $price22;
                              }
                              ?>
                              <div class="red_prog_bar" d_prog_percent="<?php echo $buy_percentage2; ?>"></div>
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
          <div class="meater_candle">
              <div class="goal_meater_main">
                    <div class="goal_meater_img">
                        <div class="degits">73</div>
                        <div pin-value="73" class="goal_pin"></div>
                    </div>
                </div>
            </div>
            <div class="barcalnd">
                <div class="bar_candle">
                    <div class="current_candle">
                        <div class="current_candle_hd">
                            Current Candle
                        </div>
                        <div class="current_candle_gr">
                            <div class="verti_bar_prog">
                                <div class="verti_bar_prog_top" d_vbpPercent="70">
                                    <span>70</span>
                                </div>
                                <div class="verti_bar_prog_bottom" d_vbpPercent="30">
                                    <span>30</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bar_inzoon_candle">
                    <div class="inzoon_candle">
                        <div class="inzoon_candle_hd">
                            In Zone
                        </div>
                        <div class="inzoon_candle_gr">
                            <div class="verti_bar_prog">
                                <div class="verti_bar_prog_top" d_vbpPercent="25">
                                    <span>25</span>
                                </div>
                                <div class="verti_bar_prog_bottom" d_vbpPercent="75">
                                    <span>75</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<input type="hidden" value="<?php echo $market_value; ?>" id="previous_market_value">
<input type="hidden" value="<?php echo $biggest_value; ?>" >

<script type="text/javascript">
  var zi=0;
  function autoload_trading_chart_data(){

      var market_value = $("#market_value").val();
      var previous_market_value = $("#previous_market_value").val();
      
    
      $.ajax({
        type:'POST',
        url:'<?php echo SURL?>admin/dashboard/autoload_trading_chart_data',
        data: {market_value:market_value,previous_market_value:previous_market_value},
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


          Get_BlueProgBar_GraphVal();
          Get_RedProgBar_GraphVal();

          $(".G_graph_box").remove();

          draw_rectangle(type);
         
          setTimeout(function() {
              autoload_trading_chart_data();
          }, 1000);
          //autoload_trading_chart_data();

        }
      });

  }//end autoload_trading_chart_data() 

  autoload_trading_chart_data();

  
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
  
  var Sval = start_val; //jQuery(".Sval").val();
  var Eval = end_val; //jQuery(".Eval").val();
  var BSval = type; //jQuery(".BSval").val();
  
  if(BSval == 'buy'){
    var GB_cls = 'gg_color_green';
  }else{
    var GB_cls = 'gg_color_orange';
  }
  
  //alert(Sval+Eval+GB_cls);
  
  var Sval_index = jQuery("li[d_price='"+Sval+"']").attr("index");
  var Eval_index = jQuery("li[d_price='"+Eval+"']").attr("index");
  
  
  var setTop = Sval_index*25;
  var remvitm= parseInt(Sval_index)+parseInt(1);
  var setHeight = (parseInt(Eval_index)+parseInt(2)-parseInt(remvitm))*25;
  
  //alert(setTop+"---"+setHeight);
  var OG_HTML = '<div class="G_graph_box '+GB_cls+'" style="top:'+setTop+'px; height:'+setHeight+'px; z-index:'+zi+';"><div class="TP_line TP_line_start"><div class="TP_line_text">TP Buyer Zoon 1</div></div><div class="TP_line TP_line_end"><div class="TP_line_text">TP Buyer Zoon 2</div></div></div>';
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
var zi=0;
jQuery("body").on("click",".submit",function(){
  
 // zi++;
  //MakeOrangeGreen_box(zi);
});

/*--*/    
  
  
// var i = 1
// jQuery(".price_s_r_li").each(function(){
// var b = i*0.25
//  var a = jQuery(this).attr("d_price");
// var ba = parseFloat(b) + parseFloat(a);
// var xy = ba.toFixed(2)  
// jQuery(this).attr("d_price",xy);
// jQuery(this).find(".simple_p").text("");
// jQuery(this).find(".simple_p").text(xy);
// jQuery(".Sval").append('<option value="'+xy+'">'+xy+'</option>');
// jQuery(".Eval").append('<option value="'+xy+'">'+xy+'</option>');
// i++;
// }); 
  
  
  
/*--*/  
// jQuery(".red_prog_bar").each(function(index, element) {
//     jQuery(this).attr("d_prog_percent",Math.floor((Math.random() * 100) + 1));  
// }); 
// jQuery(".blue_prog_bar").each(function(index, element) {
//     jQuery(this).attr("d_prog_percent",Math.floor((Math.random() * 100) + 1));  
// }); 


  
  
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
      jQuery(".price_sec_candle_main").addClass("positionFixed").width(getwidth_price_sec_candle_main);
    }else{
      jQuery(".price_sec_candle_main").removeClass("positionFixed").removeAttr("style");
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

</script>       

      
    </div>
  </div>
</div>
<!-- // Content END -->

