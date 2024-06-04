<div id="content" style="background:#232323;">
  
  
<div class="innerAll spacing-x2">
<div class="row">
     
<section class="tradding_chart_1_sec">
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

                    <li class="price_s_r_li with_BS" d_price="<?php echo $price;?>" index="1">
                    	  <div class="wbs_buyer_prog_main">
                            <div class="wbs_blu_prog">
                                <div class="wbs_blue_prog_p">1867</div>
                                <div class="wbs_blue_prog_bar" WBS_d_prog_percent="10"></div>
                            </div>
                        </div>
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
                   
                   <li class="price_s_r_li with_BS" d_price="<?php echo $market_value; ?>" index="1">
                   		<div class="wbs_buyer_prog_main">
                            <div class="wbs_blu_prog">
                                <div class="wbs_blue_prog_p">1867</div>
                                <div class="wbs_blue_prog_bar" WBS_d_prog_percent="10"></div>
                            </div>
                        </div>
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
                        <div class="wbs_seller_prog_main">
                            <div class="wbs_red_prog">
                                <div class="wbs_red_prog_p">169</div>
                                <div class="wbs_red_prog_bar" wbs_d_prog_percent="10"></div>
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
                    
                    <li class="price_s_r_li with_BS" d_price="<?php echo $price22; ?>" index="1">
                    	<div class="wbs_buyer_prog_main">
                            <div class="wbs_blu_prog">
                                <div class="wbs_blue_prog_p">1867</div>
                                <div class="wbs_blue_prog_bar" WBS_d_prog_percent="10"></div>
                            </div>
                        </div>
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
                    
                </ul>
                
            </div>


        <div class="price_sec_center_main" id="response_market_value">
        </div>
        


        </div>


        <div class="price_sec_candle_main">
          <div class="meater_candle">
              <div class="goal_meater_main">
                    <div class="goal_meater_img">
                        <div class="degits">75</div>
                        <div pin-value="75" class="goal_pin"></div>
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
                        <div class="inzoon_candle_hd">In Zone</div>
                        <div class="inzoon_candle_gr">
                            <div class="verti_bar_prog" id="response_target_zone_date">
                            </div>
                        </div>
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
  function autoload_trading_chart_data222(){

      var market_value = $("#market_value").val();
      var previous_market_value = $("#previous_market_value").val();
      
    
      $.ajax({
        type:'POST',
        url:'<?php echo SURL?>admin/dashboard/autoload_trading_chart_data222',
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
            SelectedOrderPrice(zin,itemData._id.$oid,itemData.sell_price,"sell",itemData.quantity);
              
          });


          var Buy_orders_arr = jQuery.parseJSON(split_response[7]);
          $.each(Buy_orders_arr, function(index, itemData) {
            
            zin2++;
            SelectedOrderPrice(zin2,itemData._id.$oid,itemData.price,"buy",itemData.quantity);
              
          });
         
          setTimeout(function() {
              autoload_trading_chart_data222();
          }, 2000);
         
        }
      });

  }//end autoload_trading_chart_data222() 

  autoload_trading_chart_data222();

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
	
	var Sval = start_val; //jQuery(".Sval").val();
	var Eval = end_val; //jQuery(".Eval").val();
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
        url:'<?php echo SURL?>admin/dashboard/get_order_details/'+order_id+'/'+type,
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
        url:'<?php echo SURL?>admin/dashboard/get_edit_order_details/'+order_id+'/'+type,
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
        url:'<?php echo SURL?>admin/dashboard/update_order_details',
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

