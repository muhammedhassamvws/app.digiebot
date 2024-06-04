<?php $page_post_data   = $this->session->userdata('page_post_data'); 
//echo "<prE>";  print_r($page_post_data); exit;
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">

<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>


<div id="content" style="margin-top: 111px">
<link rel="stylesheet" type="text/css" href="http://app.digiebot.com/assets/cube_chart/style.css">
<form action="<?php echo SURL ?>admin/candle-report/chart" name="" method="post" >
  <span class="dddd" style="display:none;"></span>
  <div id="toptip"></div>
  <div class="formbox"  style=""> 
    <div class="col-md-12 ">
            <div class="form-group col-md-2">
      <label class="control-label">Start Date</label>
      <input type='text' id="datetime_picker" class="form-control  datetime_picker" name="previous_date" placeholder="Search By Start Date" value="<?php echo $page_post_data['previous_date']; ?>"  autocomplete="off"/>
     </div>
            <div class="form-group col-md-2">
      <label class="control-label">End Date </label>
      <input type='text' id="datetime_picker" class="form-control datetime_picker" name="forward_date" placeholder="Search By End Date" value="<?php echo $page_post_data['forward_date']; ?>" autocomplete="off" />
   </div>
            <div class="form-group col-md-2">
      <label>Coin </label>
      <select class="form-control coin" name="coin" id="coin">
        <?php  

if ($page_post_data['coin'] == '') {
    $coinval = $global_symbol;   
} else {
    $coinval = $page_post_data['coin'];
}
?>
        <?php
foreach ($coins_arr as $coin) {
    ?>
        <option value="<?php
echo $coin['symbol'];
    ?>" <?php
echo ($global_symbol == $coin['symbol']) ? 'selected="selected"' : '';
    ?>>
        <?php
echo $coin['symbol'];
    ?>
        </option>
        <?php
}
?>
      </select>
    </div>
            <div class="form-group col-md-1">
      <label>Box %</label>
      <input type="text" name="percent" id="percent" class="form-control percent" value="<?php echo $page_post_data['percent'];?>" autocomplete="off" />
     </div>
            <div class="form-group col-md-1">
      <label>Operational %</label>
      <input type="text" name="op_percent" id="op_percent" class="form-control op_percent"  value="<?php echo $page_post_data['op_percent'];?>" autocomplete="off" />
   </div>
            <div class="form-group col-md-1">
      <label>Directional %</label>
      <input type="text" name="direct_percent" id="direct_percent" class="form-control direct_percent" value="<?php echo $page_post_data['direct_percent'];?>" autocomplete="off" />
    </div>
            <!--<div class="form-group col-md-2">
      <label>Hours</label>
      <input type="hours" name="hours" id="hours" class="form-control" value="<?php echo $page_post_data['hours'];;?>" autocomplete="off" />
    </div>-->
            <div class="form-group col-md-2" style="padding-top: 25px;">
      <!--<label>Submit</label>-->
      <input type="submit"   class="btn btn-info " name="CSV" id="CSV" value="CSV">
      <button class="s_submit btn btn-success" name="submit" id="submit">Submit</button>
    </div>
  </div>
</form>
<div class="formbox" >
  <div class="formitem">
    <label>Pline<span class="pl">100</span></label>
    <input class="Pline" type="range" min="10" max="500" value="100">
  </div>
  <div class="formitem">
    <label>Tline<span class="tl">72</span></label>
    <input class="Tline" type="range" min="20" max="1000" value="400">
  </div>
</div>
<div id="canvasbox_outer">
  <div id="tooltipbox">
    <div class="toletip"></div>
  </div>
</div>
<canvas id="myCanvas" style="border:1px solid #000;"></canvas>
<script>
 $(function () {
      $('.datetime_picker').datetimepicker({});
 });
</script> 
<script>
  
TempArray_tow = <?php echo json_encode($final_arr);?>


var top_cubval = TempArray_tow[0].top;
var bottom_cubval = TempArray_tow[0].bottom;

var cube_percent = top_cubval - bottom_cubval; 
// JavaScript Document
var WindowOfsetX = 100;
var WindowOfsetY = 100;

var canvas = document.getElementById("myCanvas");
var c = canvas.getContext("2d");
var cHeight = window.innerHeight*1.2;
var cWidth = (window.innerWidth - WindowOfsetX)*2;
var cOffse_right = 1;
var cOffse_left = 100;
var cOffse_top = 100;
var cOffse_bottom = 100;
canvas.height = cHeight;
canvas.width = cWidth;

var cFull_height = canvas.height;

var canvasContainerHeight = cHeight - cOffse_top - cOffse_bottom;
var canvasContainerWidth  = cWidth - cOffse_left - cOffse_right;

// Rect
/*c.fillStyle = "rgba(255,0,0,0.5)";
c.fillRect(100,100,100,100);
c.fillStyle = "rgba(0,255,0,0.5)";
c.fillRect(200,100,100,100);
c.fillStyle = "rgba(0,0,255,0.5)";
c.fillRect(300,100,100,100);*/


// Time line 



// Price line




var scroll = 0;
var scrollLeft = 0;
jQuery(document).ready(function(){
	jQuery(window).scroll(function() {
        scroll = window.pageYOffset || document.documentElement.scrollTop;

    });
    jQuery(window).scroll(function() {
        scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
        //ocnsole.log(scrollLeft);

    });

    jQuery("body").on("mousemove","#myCanvas",function(e){
			cnvs_getCoordinates(e);
	});

	jQuery("body").on("input change",".Pline",function(e){
		// c.clearRect(0,0,cWidth,cHeight);

		var Tlength = jQuery(".Tline").val();
		var Plength = jQuery(".Pline").val();

		jQuery(".pl").text(Plength);
		jQuery(".tl").text(Tlength);

		// drwPriceLine(Plength);
		// drwTimeLine(Tlength);
		requestAnimationFrame(randerChart);
		cnvs_getCoordinates(e);
	});


	jQuery("body").on("input change",".Tline",function(e){
		// c.clearRect(0,0,cWidth,cHeight);
		var Tlength = jQuery(".Tline").val();
		var Plength = jQuery(".Pline").val();

		jQuery(".pl").text(Plength);
		jQuery(".tl").text(Tlength);


		// jQuery(".s_time").html('');
		// jQuery(".s_high").html('');
		// jQuery(".s_low").html('');
		// jQuery(".s_open").html('');
		// jQuery(".s_close").html('');

		// drwPriceLine(Plength);
		// drwTimeLine(Tlength);
		requestAnimationFrame(randerChart);
		cnvs_getCoordinates(e);
	});




});




////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////



function drwTimeLine(Tlength){
	var T_LfromX = cOffse_left;
	var T_LtoX = cOffse_left;
	var T_LfromY = cOffse_top-10;
	var T_LtoY = cHeight - cOffse_bottom + (10);
	
	
		var startY = cHeight ;
	// Base Time line
	c.beginPath();
	c.moveTo(T_LfromX,T_LfromY);
	c.lineTo(T_LtoX,startY);
	c.strokeStyle="rgba(0,255,0,0.9)";
	c.stroke();


	var lineSpace = cWidth - cOffse_left - cOffse_right;
	lineSpace = lineSpace/Tlength;

	for(var i = 0; i <= Tlength; i++){
		c.beginPath();
		c.moveTo(T_LfromX+(lineSpace),T_LfromY);
		c.lineTo(T_LtoX+(lineSpace),T_LtoY);
		c.strokeStyle="rgba(200,200,200,0.2)";
		c.stroke();

		if(i != 0){
			T_LfromX +=lineSpace ;
			T_LtoX +=lineSpace;
			c.font="9px Arial";
			c.fillStyle = "#444";
			c.fillText(i+"H",T_LfromX-10,T_LtoY+30);
			//drawCandle(T_LfromX,Tlength);

		}

		jQuery(".s_time").append('<option value="'+i+'">'+i+'Hr</option>');

		

	}
	
}

function drwPriceLine(Plength){
	var P_LfromX = cOffse_left-10;
	var P_LtoX = cWidth - cOffse_right+10;
	var P_LfromY = cHeight - cOffse_bottom ;
	var P_LtoY = cHeight - cOffse_bottom ;

		
		
	// Base Price Line
		c.beginPath();
		c.moveTo(P_LfromX,P_LfromY);
		c.lineTo(P_LtoX,P_LfromY);
		c.strokeStyle="rgba(255,0,0,0.9)";
		c.stroke(); 

	var lineSpace = cHeight - cOffse_top - cOffse_bottom;
	lineSpace = lineSpace/Plength;

	for(var i = 0; i <= Plength; i++){
		c.beginPath();
		c.moveTo(P_LfromX,P_LfromY-(lineSpace*i));
		c.lineTo(P_LtoX,P_LtoY-(lineSpace*i));
		c.strokeStyle="rgba(200,200,200,0.2";
		c.stroke(); 

		//P_LfromY +=lineSpace;
		//P_LtoY +=lineSpace;
		
		c.font="5px Arial";
		c.fillStyle = "#fff";
		c.fillText("$"+i,P_LfromX-20,P_LfromY-(lineSpace*i)+2);

		jQuery(".s_high").append('<option value="'+i+'">'+i+'</option>');
		jQuery(".s_low").append('<option value="'+i+'">'+i+'</option>');
		jQuery(".s_open").append('<option value="'+i+'">'+i+'</option>');
		jQuery(".s_close").append('<option value="'+i+'">'+i+'</option>');
		
	}


	var P_divided = 100 / cube_percent;
	var P_div_form=jQuery(".Pline").val();
	var P_cl_per = P_div_form/P_divided;

	
	var linespace_dvide = cHeight - cOffse_top - cOffse_bottom;
	linespace_dvide = linespace_dvide/P_divided;
	for(var i_dv = 0; i_dv <= P_divided; i_dv++){
		var mns = i_dv*P_cl_per;
		c.beginPath();
		c.moveTo(P_LfromX,P_LfromY-(linespace_dvide*i_dv));
		c.lineTo(P_LtoX,P_LtoY-(linespace_dvide*i_dv));
		c.strokeStyle="rgba(100,100,100,0.3)";
		c.stroke(); 

		//P_LfromY +=lineSpace;
		//P_LtoY +=lineSpace;


var myTrunc = Math.trunc( mns );

		c.font="14px Arial";
		c.fillStyle = "#f00";
		c.fillText(myTrunc,P_LfromX-60,P_LfromY-(linespace_dvide*i_dv)+2);
		
		
		console.log(P_cl_per);
	}
	
}



ToolArray = [];
function drawCandle(cd_x,cd_l,cd_top,cd_bottom,cd_color,cd_close,cd_open,cd_high,cd_low,cd_openTime_human_readible,cd_global_swing_status,cd_top_spick,cd_bottom_spick){
	var ToolArray_obj = {};

	var cd_time                  =    cd_x;
	var cd_l                     =    cd_l;
	var cd_top                   =    cd_top;
	var cd_bottom                =    cd_bottom ;
	var cd_color                 =    cd_color;

	var close_vlu                =    cd_close;
	var open_vlu                 =    cd_open;
	var high_vlu                 =    cd_high;
	var low_vlu                  =    cd_low;
	var time_vlu                 =    cd_openTime_human_readible;
	var global_swing_status_vlu  =    cd_global_swing_status;
	var top_spick_vlu			 =    cd_top_spick;
	var bottom_spick_vlu		 =    cd_bottom_spick;

	

	//alert("cd_time"+cd_time+"cd_l"+cd_l+"cd_high"+cd_high+"cd_low"+cd_low+"cd_open"+cd_open+"cd_close"+cd_close);
	//////////////////////////// Formula for calculate Ratio For Y

	var InerHeight = canvasContainerHeight;
	var total_P_length = jQuery(".Pline").val();

	var PerPriceHeight = InerHeight / total_P_length;

	var TopOffSet = cOffse_top;
	/////////////////////////////
	//////////////////////////// Formula for calculate Ratio For X

	var InerWidth = canvasContainerWidth;
	var total_T_length = jQuery(".Tline").val();

	var PerTimeWidth = InerWidth / total_T_length;

	var LeftOffSet = cOffse_left;
	/////////////////////////////






	var cd_xx = (PerTimeWidth*cd_x*cube_percent) + LeftOffSet;

	var cd_l_y = InerHeight - (PerPriceHeight*cd_top_spick) + TopOffSet - (total_P_length/2);

	var cd_l_y_b = InerHeight - (PerPriceHeight*cd_bottom_spick) + TopOffSet - (total_P_length/2);

	var cd_l_h  = cd_l_y_b - cd_l_y ; 

	var cd_l_w = 1;

	var cd_l_x = cd_xx - (cd_l_w / 2);

	c.fillStyle = "rgba(0,0,0,0.5)"; c.fillRect(cd_l_x,cd_l_y,cd_l_w,cd_l_h);









	

			
	var cd_y = InerHeight - (PerPriceHeight*cd_top) + TopOffSet - (total_P_length/2);

	var cd_y_b = InerHeight - (PerPriceHeight*cd_bottom ) + TopOffSet - (total_P_length/2);

	var cd_h  = cd_y_b - cd_y ; 

	var cdwr = candleSize(cd_l)*cube_percent;

		cd_w = cdwr

	var cd_x = cd_xx - (cd_w/2);

	if(cd_color == 'red'){
		cd_color = '#ff0000';
	}else{
		cd_color = '#0000ff';
	}
	
	c.fillStyle = cd_color;
	c.fillRect(cd_x,cd_y,cd_w,cd_h);
	

	var start_x = cd_x;
	var start_y = cd_y;
	
	var end_x   = parseFloat(cd_x) + parseFloat(cd_w);
	var end_y = parseFloat(cd_y) + parseFloat(cd_h);
	
	ToolArray_obj.start_x = start_x;
	ToolArray_obj.start_y = start_y;
	ToolArray_obj.end_x = end_x;
	ToolArray_obj.end_y = end_y;

	ToolArray_obj.close_vlu                =    close_vlu;
	ToolArray_obj.open_vlu                 =    open_vlu;
	ToolArray_obj.high_vlu                 =    high_vlu;
	ToolArray_obj.low_vlu                  =    low_vlu;
	ToolArray_obj.time_vlu                 =    time_vlu;
	ToolArray_obj.global_swing_status_vlu  =    global_swing_status_vlu;
	ToolArray_obj.top_spick_vlu            =    top_spick_vlu;
	ToolArray_obj.bottom_spick_vlu         =    bottom_spick_vlu;




	
	
	ToolArray.push(ToolArray_obj);



			/*-----------------------------------------Trading Price Line---*/
		

			if(cd_time == 0){ //alert(cd_time);
				H_trading_price_line_X = cd_l_x ;
				H_trading_price_line_Y = cd_l_y ;
				L_trading_price_line_X = cd_l_x ;
				L_trading_price_line_Y = cd_l_y+cd_l_h;
			}
		

			//Price Line High to High
			c.beginPath();
			c.moveTo(H_trading_price_line_X,H_trading_price_line_Y);
			c.lineTo(cd_l_x,cd_l_y);
			c.strokeStyle = 'rgba(0,255,0,1)';
			c.stroke(); 
			H_trading_price_line_X = cd_l_x;
			H_trading_price_line_Y = cd_l_y;


			//Price Line Low to Low
			c.beginPath();
			c.moveTo(L_trading_price_line_X,L_trading_price_line_Y);
			c.lineTo(cd_l_x,cd_l_y+cd_l_h);
			c.strokeStyle = 'rgba(255,0,0,1)';
			c.stroke(); 
			L_trading_price_line_X = cd_l_x;
			L_trading_price_line_Y = cd_l_y+cd_l_h;

		/*-----------------------------------------*/	


}


function candleSize(cd_l){
	var cd_l = cd_l;
	var cdSiz = canvasContainerWidth / cd_l;
	// 80% // cdSiz =  (80/100)*cdSiz
	cdSiz =  (100/100)*cdSiz
	jQuery(".dddd").text(cdSiz);

	return cdSiz;
}

function randerChart(){
	

	var LengthOfItems = TempArray_tow.length;
	


		jQuery(".s_time").html('');
		jQuery(".s_high").html('');
		jQuery(".s_low").html('');
		jQuery(".s_open").html('');
		jQuery(".s_close").html('');

		var Tlength = jQuery(".Tline").val();
		var Plength = jQuery(".Pline").val();
		jQuery(".pl").text(Plength);

		c.clearRect(0,0,cWidth,cFull_height);
		drwPriceLine(Plength);
		
		
		drwTimeLine(Tlength);




				var time = Tlength;
				var Price = Plength;

				ToolArray = [];
				for(indexi in TempArray_tow){


	


							var cd_top=TempArray_tow[indexi].top;
							var cd_bottom=TempArray_tow[indexi].bottom;
							var cd_color=TempArray_tow[indexi].color;
							var cd_close=TempArray_tow[indexi].close;
							var cd_open=TempArray_tow[indexi].open;
							var cd_high=TempArray_tow[indexi].high;
							var cd_low=TempArray_tow[indexi].low;
							var cd_openTime_human_readible=TempArray_tow[indexi].openTime_human_readible;
							var cd_global_swing_status=TempArray_tow[indexi].global_swing_status;


						    var cd_top_spick = TempArray_tow[indexi].topSlick;
							var cd_bottom_spick = TempArray_tow[indexi].botSlick;


							// var cd_high  =TempArray_tow[indexi].top;
							// var cd_low    = TempArray_tow[indexi].bottom;
							// var cd_color = TempArray_tow[indexi].color;
							// var cd_time = TempArray_tow[indexi].openTime_human_readible;
							// var cd_open = TempArray_tow[indexi].open;
							// var cd_close = TempArray_tow[indexi].close;




					 	var cd_x = indexi;
					 	var cd_l = time;
					 drawCandle(cd_x,cd_l,cd_top,cd_bottom,cd_color,cd_close,cd_open,cd_high,cd_low,cd_openTime_human_readible,cd_global_swing_status,cd_top_spick,cd_bottom_spick);
				}

}
function get_index_Value(arr){

	if(typeof arr !='undefined' && arr.length>0){
	 var newArr = {};
	 var fullArr = [];
	 for(index in arr){
	 
	 newArr[arr[index].top] = arr[index].top;
	 newArr[arr[index].bottom] = arr[index].bottom; 
	 
	 	
	 }
	 
	 for(index in newArr){
	 
	 	fullArr.push(newArr[index]);
	  
	 }
	 
	 
	 var sorArr= fullArr.sort(function(a, b){return a - b});

	 return sorArr;
	 
	}

}
requestAnimationFrame(randerChart);






function SetWidthOfCanvsBox(){
	var get_cansWidth = jQuery("canvas").width();
	jQuery("#canvasbox_X").width(get_cansWidth);
	jQuery("#canvasbox_Y").width(get_cansWidth);
	jQuery("#tooltipbox").width(get_cansWidth);
}
SetWidthOfCanvsBox();


function SetHeightOfCanvsBox(){
	
	var get_cansHeight = jQuery("canvas").height();
	//console.log(get_cansHeight+'aaa');
	jQuery("#main_time_line").css("height",get_cansHeight+"px");
	
}
SetHeightOfCanvsBox();

function cnvs_getCoordinates(e){
var indexing = 0;
	// x=e.clientX - canvas.offsetLeft -5 ;
	// y=e.clientY - canvas.offsetTop-5;

	x=e.clientX - canvas.offsetLeft;
	y=e.clientY - canvas.offsetTop;
	
	
	var ys = scroll+y;
	var xs = scrollLeft+x;
	document.getElementById("toptip").innerHTML="Coordinates: (" + xs + "," + ys + ")";

	jQuery("#main_price_line").css("top",ys+"px");
	jQuery("#main_time_line").css("left",xs+"px");



		var tol_top = ys - 210;
		var tol_left = xs + 15;


	jQuery(".toletip").css("left",tol_left+"px");
	jQuery(".toletip").css("top",tol_top+"px");

	//jQuery("#pdgn").text(ys);


	var dtimevar = findTimeCordinate(xs, ToolArray);
	if(dtimevar === false){
		var datime = '';
	}else{
		var datime = dateConvert(dtimevar);
	}
	jQuery("#tdgn").text(datime);

	// var  vstart_x = 65.62;
	// var  vstart_y = 121.81481481481481;
	// var  end_x = 134.38;
	// var  end_y = 128.320987654321;


	if(findArryVal(xs, ys, ToolArray)){
		var get_toltiphtml = findArryVal(xs, ys, ToolArray);
		//console.log("true");
		jQuery("#toptip").show();
		jQuery(".toletip").show();
		jQuery(".toletip").html(get_toltiphtml);
	}else{
		//console.log("false");
		jQuery("#toptip").hide();
		jQuery(".toletip").hide();
	}	


	var per_cel_height = canvasContainerHeight / 100;
    var Start_ys = ys - 100;
    var per_pxl_price_percent = 100-(Start_ys / per_cel_height);
    per_pxl_price_percent = Math.round(per_pxl_price_percent);

    var sortedArr= get_index_Value(TempArray);
    var max_val = sortedArr[sortedArr.length-1];
    var min_val = sortedArr[0];
    
    var unit_val = (max_val-min_val)/100;
    console.log('max_val -->'+max_val+'<---min_val'+min_val+'--->'+unit_val);
    var att_50 = (unit_val*per_pxl_price_percent)+parseFloat(min_val);
    //console.log(att_50+'<------------att_50'+'actulal--> '+per_pxl_price_percent);

   jQuery("#pdgn").text(att_50.toFixed(8)+'-->'+per_pxl_price_percent);
	// jQuery("#pdgn").text(per_pxl_price_percent);
	

	// findPriceCordinate();
}



jQuery("body").on("click","canvas",function(){
		var yx_line_d_val = jQuery("#main_price_line .c_pdgn").text();
		var yx_line_style = jQuery("#main_price_line").attr("style");
		jQuery("#canvasbox_X").append('<div class="c_main_price_line cmpl_Red" style="'+yx_line_style+'"><div id="pdgn" class="c_pdgn">'+yx_line_d_val+'</div><span class="c_removePL">x</span></div>');
});
jQuery("body").on("click",".c_removePL",function(){
	jQuery(this).closest(".c_main_price_line").remove();		
});
function dateConvert(milliseconds){

        var dateobj = new Date(+milliseconds);
        var year = dateobj.getFullYear();
        var month= ("0" + (dateobj.getMonth()+1)).slice(-2);
        var date = ("0" + dateobj.getDate()).slice(-2);
        var hours = ("0" + dateobj.getHours()).slice(-2);
        var minutes = ("0" + dateobj.getMinutes()).slice(-2);
        var seconds = ("0" + dateobj.getSeconds()).slice(-2);
        var day = dateobj.getDay();
        var months = ["JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];
        var dates = ["SUN","MON","TUE","WED","THU","FRI","SAT"];
        var converted_date = "";
        converted_date = date + "-" + months[parseInt(month)-1]  + "-" +year +', ' +hours +':'+minutes+':'+seconds;

        return converted_date;
}
function findArryVal(x, ys, array){
	var respose = false;

	if(typeof array !='undefined' && array.length>0){
		for(index in array){
			if(x >= array[index].start_x && x <= array[index].end_x && ys >= array[index].start_y && ys <= array[index].end_y){

			var tooltip_html = '\
				<ul>\
					<li><strong>Close:</strong> '+array[index].close_vlu+'</li>\
					<li><strong>Open:</strong> '+array[index].open_vlu+'</li>\
					<li><strong>High:</strong> '+array[index].high_vlu+'</li>\
					<li><strong>Low:</strong> '+array[index].low_vlu+'</li>\
					<li><strong>Time:</strong> '+array[index].time_vlu+'</li>\
					<li><strong>Swing Status:</strong> '+array[index].global_swing_status_vlu+'</li>\
					<li><strong>Top spick:</strong> '+array[index].top_spick_vlu+'</li>\
					<li><strong>Bottom spick:</strong> '+array[index].bottom_spick_vlu+'</li>\
				</ul>\
			';	

			respose = tooltip_html;
			break;
			}else{
			respose = false;
			}
		}
	}
	return respose;
}
function findTimeCordinate(x, array){
	var respose = false;

	if(typeof array !='undefined' && array.length>0){
		for(index in array){
			if(x >= array[index].start_x && x <= array[index].end_x){

			var TimeCordinate_html = array[index].A_cd_time;	

			respose = TimeCordinate_html;
			break;
			}else{
			respose = false;
			}
		}
	}
	return respose;
}  
</script> 

<!-- // Content END --> 
