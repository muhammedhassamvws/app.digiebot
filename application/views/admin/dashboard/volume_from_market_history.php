<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/candle_css/style.css">
<script src="https://code.jquery.com/jquery-3.3.1.js" type="text/javascript"></script>


    <?php

        $candlesdtickArr = $candlesdtickArr;
        $compare_val = $compare_val;
        $candle_period = $candle_period;

        $get_market_history_for_candel = $get_market_history_for_candel;

        $total_volume_arr  =$total_volume_arr;
        $max_volume = $max_volume;

    ?>





<div id="content">
    <span class="dddd"></span>
<div id="toptip">sss</div>
<div class="formbox" style="margin-top: 6%;">

	<div class="run">Run</div>
    <div class="row">
        <div class="form-group">
            <label for="comment" class="col-md-1">Warning limit:</label>
            <div class="col-md-3">
              <input type="number"  id="limit_id" class=" form-control"  step="0.1" value="0.1">
            </div>

        </div>  

         <div class="form-group">
	         <label for="comment" class="col-md-1">candle seconds:</label>
	         <div class="col-md-3">
	              <input type="number" id="candlePeriod_id" class=" form-control"   value="3600">
	         </div>
        </div>

		<div class="form-group">
			<label for="comment" class="col-md-1">vol MA size:</label>
			<div class="col-md-3">
				<input type="number" id="sizeMAVol_id" class=" form-control" step="10"   value="10">
			</div>
		</div>

    </div>

	<div class="row">
		<div class="form-group">
			<label for="comment" class="col-md-1">Lookback:</label>
			<div class="col-md-1">
				<input type="number" id="Lookback" class=" form-control"    value="7">
			</div>
		</div>
		<div class="form-group">
			<label for="comment" class="col-md-1">enableBarColors:</label>
			<div class="col-md-1">
				<input type="checkbox" value="" id="enableBarColors">
			</div>
		</div>

		<div class="form-group">
			<label for="comment" class="col-md-1">use2Bars:</label>
			<div class="col-md-1">
				<input type="checkbox" value="" id="use2Bars" checked="checked">
			</div>
		</div>

		<div class="form-group">
			<label for="comment" class="col-md-1">lowVol:</label>
			<div class="col-md-1">
				<input type="checkbox" value="" checked="checked" id="lowVol">
			</div>
		</div>

		<div class="form-group">
			<label for="comment" class="col-md-1">climaxUp:</label>
			<div class="col-md-1">
				<input type="checkbox" value="" checked="checked" id="climaxUp">
			</div>
		</div>

		
	</div>

	<div class="row">
		<div class="form-group">
			<label for="comment" class="col-md-1">climaxDown:</label>
			<div class="col-md-1">
				<input type="checkbox" value="" id="climaxDown" checked="checked">
			</div>
		</div>

		<div class="form-group">
			<label for="comment" class="col-md-1">churn:</label>
			<div class="col-md-1">
				<input type="checkbox" value="" checked="checked" id="churn">
			</div>
		</div>
		<div class="form-group">
			<label for="comment" class="col-md-1">climaxChurn:</label>
			<div class="col-md-1">
				<input type="checkbox" value="" checked="checked" id="climaxChurn">
			</div>
		</div>
	</div>



    <div class="row">
        <div class="form-group">
            <label for="comment" class="col-md-1">PercentileTrigger:</label>
            <div class="col-md-3">
                <input type="number" value="90" id="PercentileTrigger" class=" form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="comment" class="col-md-1">DemandTrigger:</label>
            <div class="col-md-3">
                <input type="number" value="90000"  id="DemandTrigger" class=" form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="comment" class="col-md-1">SupplyTrigger:</label>
            <div class="col-md-3">
                <input type="number" value="90000"  id="SupplyTrigger" class=" form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="comment" class="col-md-1">BarsBack:</label>
            <div class="col-md-3">
                <input type="number" value="8"  id="BarsBack" class=" form-control">
            </div>
        </div>
    </div>

  
</div>
<div class="formbox">
    <div class="formitem">
        <label>Pline<span class="pl">100</span></label>
        <input class="Pline" type="range" min="10" max="500" value="100">
    </div>
    <div class="formitem">
        <label>Tline<span class="tl">7</span></label>
        <input class="Tline" type="range" min="20" max="1000" value="50">
    </div>
</div>
<div id="canvasbox_outer">
    <div id="tooltipbox">
        <div class="toletip"></div>
    </div>
    <div id="canvasbox_X">
        <div id="main_price_line" class="c_main_price_line">
            <div id="pdgn" class="c_pdgn">0.000525</div>
        </div>
    </div>
    <div id="canvasbox_Y">
        <div id="main_time_line">
            <div id="tdgn">0.000525</div>
        </div>
    </div>
</div>

    <canvas id="myCanvas" style="border:1px solid #000;"></canvas>
</div>



<script>


 TempArray = <?php echo json_encode($candlesdtickArr); ?>;

 get_market_history_for_candel = <?php echo json_encode($get_market_history_for_candel); ?>;




 total_volume_arr = <?php echo json_encode($total_volume_arr); ?>;
 var max_volume_candle = <?php echo json_encode($max_volume); ?>;







 // alert(JSON.stringify(get_market_history_for_candel));





    console.log(TempArray);


var BGHPercent_one = (window.innerHeight/100)*50;
var BGHPercent_two = (window.innerHeight/100)*50;

var BarCanvas_one_height = BGHPercent_one; 
var BarCanvas_two_height = BGHPercent_two; 



// JavaScript Document
var WindowOfsetX = 100;
var WindowOfsetY = 140;

var canvas = document.getElementById("myCanvas");
var c = canvas.getContext("2d");
var cHeight = window.innerHeight  - BarCanvas_one_height - BarCanvas_two_height;
var cWidth = window.innerWidth - WindowOfsetX;
var cOffse_right = 1;
var cOffse_left = 100;
var cOffse_top = 0;
var cOffse_bottom = 70;
canvas.height = cHeight + BarCanvas_one_height + BarCanvas_two_height;
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




var scroll=0;

jQuery(document).ready(function(){
    jQuery(window).scroll(function() {
        scroll = window.pageYOffset || document.documentElement.scrollTop;

    });
    jQuery("body").on("mousemove","#myCanvas",function(e){
            cnvs_getCoordinates(e);
    });

    jQuery("body").on("input change",".Pline",function(){
        // c.clearRect(0,0,cWidth,cHeight);

        var Tlength = jQuery(".Tline").val();
        var Plength = jQuery(".Pline").val();

        jQuery(".pl").text(Plength);
        jQuery(".tl").text(Tlength);

        // drwPriceLine(Plength);
        // drwTimeLine(Tlength);
        requestAnimationFrame(randerChart);
    });


    jQuery("body").on("input change",".Tline",function(){
        // c.clearRect(0,0,cWidth,cHeight);
        var Tlength = jQuery(".Tline").val();
        var Plength = jQuery(".Pline").val();
    

        if(Tlength>TempArray.length){
            Tlength = TempArray.length;
       
        }

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
    });


    jQuery("body").on("click",".s_submit",function(){
        var s_time   =   jQuery(".s_time").val();
        var s_high   =   jQuery(".s_high").val();
        var s_low    =   jQuery(".s_low").val();
        var s_open   =   jQuery(".s_open").val();
        var s_close  =   jQuery(".s_close").val();
        var lngthHr  =   jQuery(".tl").text();
        lngthHr      =   parseInt(lngthHr);

        drawCandle(s_time,lngthHr,s_high,s_low,s_open,s_close);
        
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
    
    
        var startY = cHeight + BarCanvas_one_height + BarCanvas_two_height ;
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
        c.strokeStyle="rgba(200,200,200,0.2)";
        c.stroke(); 

        //P_LfromY +=lineSpace;
        //P_LtoY +=lineSpace;
        
        c.font="5px Arial";
        c.fillStyle = "#666";
        c.fillText("$"+i,P_LfromX-20,P_LfromY-(lineSpace*i)+2);

        jQuery(".s_high").append('<option value="'+i+'">'+i+'</option>');
        jQuery(".s_low").append('<option value="'+i+'">'+i+'</option>');
        jQuery(".s_open").append('<option value="'+i+'">'+i+'</option>');
        jQuery(".s_close").append('<option value="'+i+'">'+i+'</option>');
        
    }



        var P_divided = 4;
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
            
            c.font="14px Arial";
            c.fillStyle = "#f00";
            c.fillText("$"+mns,P_LfromX-60,P_LfromY-(linespace_dvide*i_dv)+2);
            
            
            console.log(P_cl_per);
        }




    
}

function drwBarGraph_one_top_Line(){
    var B_G_1_LfromX = 0;
    var B_G_1_LtoX = cWidth;
    var B_G_1_LfromY = cHeight;
    var B_G_1_LtoY = cHeight;
    // Base Price Line
        c.beginPath();
        c.moveTo(B_G_1_LfromX,B_G_1_LfromY);
        c.lineTo(B_G_1_LtoX,B_G_1_LfromY);
        c.strokeStyle="rgba(0,0,0,0.9)";
        c.stroke(); 
}
function drwBarGraph_two_top_Line(){
    var B_G_2_LfromX = 0;
    var B_G_2_LtoX = cWidth;
    var B_G_2_LfromY = cHeight + BarCanvas_one_height;
    var B_G_2_LtoY = cHeight + BarCanvas_one_height;
    // Base Price Line
        c.beginPath();
        c.moveTo(B_G_2_LfromX,B_G_2_LfromY);
        c.lineTo(B_G_2_LtoX,B_G_2_LfromY);
        c.strokeStyle="rgba(0,0,0,0.9)";
        c.stroke(); 
}
function drwBarGraph_OneTwoCombine_line(){
    var B_G_1_LfromX = 0;
    var B_G_1_LtoX = cWidth;
    var B_G_1_LfromY = cHeight;
    var B_G_1_LtoY = cHeight;
    // Base Price Line
        c.beginPath();
        c.moveTo(B_G_1_LfromX,B_G_1_LfromY);
        c.lineTo(B_G_1_LtoX,B_G_1_LfromY);
        c.strokeStyle="rgba(0,0,0,0.9)";
        c.stroke(); 
}
ToolArray = [];
function drawCandle(cd_x,cd_l,cd_high,cd_low,cd_open,cd_close,A_cd_high,A_cd_low,A_cd_open,A_cd_close,A_cd_volume,A_cd_time){
    var ToolArray_obj = {};


    var cd_time     =    cd_x;
    var cd_l        =    cd_l;
    var cd_high     =    cd_high;
    var cd_low      =    cd_low;
    var cd_open     =    cd_open;
    var cd_close    =    cd_close;

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

    var cd_xx = (PerTimeWidth*cd_x) + LeftOffSet;

    var cd_l_y = InerHeight - (PerPriceHeight*cd_high) + TopOffSet;

    var cd_l_y_b = InerHeight - (PerPriceHeight*cd_low) + TopOffSet;

    var cd_l_h  = cd_l_y_b - cd_l_y ; 

    var cd_l_w = 1;

    var cd_l_x = cd_xx - (cd_l_w / 2);

    c.fillStyle = "rgba(0,0,0,0.5)"; c.fillRect(cd_l_x,cd_l_y,cd_l_w,cd_l_h);

    //alert("cd_l_y"+cd_l_y+"cd_l_h"+cd_l_h);
            
    var cd_y = InerHeight - (PerPriceHeight*cd_open) + TopOffSet;

    var cd_y_b = InerHeight - (PerPriceHeight*cd_close) + TopOffSet;

    var cd_h  = cd_y_b - cd_y ; 

    var cdwr = candleSize(cd_l);

        cd_w = cdwr

    var cd_x = cd_xx - (cd_w / 2);

    var cd_color = "#E83646";

    if(cd_close > cd_open){
        cd_color = "#3EB078";
    }
    
    
    /////////////////////////////
    //////////////////////////////
    ///////////ssssssss//////////////////
    //////////////////////////////
    /////////////////////////////
    //////////////////////////////
    
    c.fillStyle = cd_color;
    c.fillRect(cd_x,cd_y,cd_w,cd_h);
    
    var start_x = cd_x;
    var start_y = cd_l_y;
    
    var end_x   = parseFloat(cd_x) + parseFloat(cd_w);
    var end_y = parseFloat(cd_l_y) + parseFloat(cd_l_h);
    
    ToolArray_obj.start_x = start_x;
    ToolArray_obj.start_y = start_y;
    ToolArray_obj.end_x = end_x;
    ToolArray_obj.end_y = end_y;
    ToolArray_obj.A_cd_high = A_cd_high;
    ToolArray_obj.A_cd_low = A_cd_low;
    ToolArray_obj.A_cd_open = A_cd_open;
    ToolArray_obj.A_cd_close = A_cd_close;
    ToolArray_obj.A_cd_volume = A_cd_volume;
    ToolArray_obj.A_cd_time = A_cd_time;
    
    
    ToolArray.push(ToolArray_obj);
    
    
    //alert(JSON.stringify(ToolArray));
    
    
    
}

function find_volume(actual_openTime,actual_closeTime,A_cd_open,A_cd_close,A_cd_volume,TempArray,index){

        var limit = $('#limit_id').val();
        var candlePeriod = $('#candlePeriod_id').val(); 
        var sizeMAVol = $('#sizeMAVol_id').val();
        var timenow = Date.now();
        var open_time = actual_openTime;
        var clsoe_time = actual_closeTime;
        var red  ='#FF0000';
        var gray = '#D3D3D3';
        var green = '#548DD3';
        var black = '#000000';


        var volume = A_cd_volume;


        //calculate time until candle closes. candle close= 100% of time
        currentCandle = (timenow-open_time)< candlePeriod*1000 ? true : false;

        deltaT = currentCandle ? (timenow - open_time) : (open_time - clsoe_time)
        deltaT = deltaT / (candlePeriod * 1000)
        //weekends have weird periods, need normalization hack
        deltaT = deltaT > 1 ? 1 : deltaT

        //volume calculation
        // MV = sma(volume,sizeMAVol)

        MV =  find_sample_moving_average(TempArray,sizeMAVol,index);

           
        deltaV = (volume / MV);
        // direction = (close-open)>0 ? 1 : -1
         direction = (A_cd_close-A_cd_open)>0 ? 1 : -1
        volTarget = deltaV / deltaT * direction;



        volColor = (volTarget>limit) ? red : volTarget<(0-limit) ? green: gray;

        volColor = Math.abs(volTarget)>5 ? black : volColor;
        volTarget = Math.abs(volTarget)>5 ? 0.5 : volTarget;

      
        volTarget = Math.abs(volTarget);


        var resposne = {};
            resposne.volColor = volColor;
            resposne.volTarget = volTarget;   
           
            return resposne;

}

 function find_sample_moving_average(arr,sizeMAVol,index){


        var total_volume = 0;
        if(arr.length>sizeMAVol){

            for(var i=sizeMAVol;i>=1;i--){
                
                var new_index = Math.abs(index-i);
                // alert(new_index);
                if(typeof arr[new_index] !='undefined'){
                total_volume +=parseFloat(arr[new_index].volume);
            }

            }
        }
        return total_volume;
   }

function drawCandle_bar_one(cd_x,cd_l,actual_openTime,actual_closeTime,A_cd_open,A_cd_close,A_cd_volume,TempArray,indexi,max_volume_value){
    

    var cd_time     =    cd_x;
    var cd_l        =    cd_l;
    
   

   var response_obj  = find_volume(actual_openTime,actual_closeTime,A_cd_open,A_cd_close,A_cd_volume,TempArray,indexi);

   var  volume_taget = response_obj.volTarget;



   var volume =  (volume_taget/max_volume_value)*100;
 



   var cd_color = response_obj.volColor;

    
    
    
    var TopOffset = cHeight +5;
    
    var BottomOffset = BarCanvas_two_height +5;
     
    var cnvHeight = cFull_height - BottomOffset - TopOffset  ;
    
    //alert(cnvHeight);
    
    //return false;
    
    
    
    var barHeight = cnvHeight*volume/100;
    
    var barY = cFull_height - BarCanvas_two_height - barHeight-5;
    
    /*alert(barY);
    
    return false;*/
    
//////////////////////////// Formula for calculate Ratio For X

    var InerWidth = canvasContainerWidth;
    var total_T_length = jQuery(".Tline").val();

    var PerTimeWidth = InerWidth / total_T_length;

    var LeftOffSet = cOffse_left;
/////////////////////////////

    var cd_xx = (PerTimeWidth*cd_x) + LeftOffSet;
            
    var cd_y = barY;

    var cd_h  = barHeight ; 
    
    
    
    
    var cd_l = cd_l;
    var cdSiz = canvasContainerWidth / cd_l;
    // 100%
    cdSiz =  (100/100)*cdSiz;
    
    
    
    
    

    var cdwr = cdSiz;

        cd_w = cdwr

    var cd_x = cd_xx - (cd_w / 2);


    c.fillStyle = cd_color;
    c.fillRect(cd_x,cd_y,cd_w,cd_h);
    
    
}  

function get_volume_arr(arr){

    if(typeof arr !='undefined' && arr.length>0){
     var newArr = {};
     var fullArr = [];
     for(index in arr){
     
     newArr[arr[index].volume] = arr[index].volume;
   
     }
     
     for(index in newArr){
     
        fullArr.push(newArr[index]);
      
     }
     
     
     var sorArr= fullArr.sort(function(a, b){return a - b});

     return sorArr;
     
    }

}
function drawCandle_bar_two(cd_x,cd_l,better_volume_color,volume_taget,TempArray){
    

    var cd_time     =    cd_x;
    var cd_l        =    cd_l;
    


    var volume_arr = get_volume_arr(TempArray);

   

    var max_volume_value = volume_arr[volume_arr.length-1];

    var volume =  (volume_taget/max_volume_value)*100;


   console.log('*(***&*&*&*&*******************');
   console.log(volume_taget);


 
    
    var TopOffset = cHeight + BarCanvas_one_height +5;
    
    var BottomOffset = 5;
     
    var cnvHeight = cFull_height - BottomOffset - TopOffset  ;
    
    //alert(cnvHeight);
    
    //return false;
    
    
    
    var barHeight = cnvHeight*volume/100;
    
    var barY = cFull_height - barHeight-5;
    
    /*alert(barY);
    
    return false;*/
    
//////////////////////////// Formula for calculate Ratio For X

    var InerWidth = canvasContainerWidth;
    var total_T_length = jQuery(".Tline").val();

    var PerTimeWidth = InerWidth / total_T_length;

    var LeftOffSet = cOffse_left;
/////////////////////////////

    var cd_xx = (PerTimeWidth*cd_x) + LeftOffSet;
            
    var cd_y = barY;

    var cd_h  = barHeight ; 
    
    
    
    
    var cd_l = cd_l;
    var cdSiz = canvasContainerWidth / cd_l;
    // 100%
    cdSiz =  (100/100)*cdSiz;
    
    
    
    
    

    var cdwr = cdSiz;

        cd_w = cdwr

    var cd_x = cd_xx - (cd_w / 2);

   
    c.fillStyle = better_volume_color;
    c.fillRect(cd_x,cd_y,cd_w,cd_h);
    
    
}

$(function(){

//alert(JSON.stringify(total_volume_arr));

 
 var cdx_global =0;
 for(index in total_volume_arr){
     var cd_x = cdx_global;
     var cd_l = 50;
     var A_cd_volume = total_volume_arr[index];
     
     var max_volume = max_volume_candle;
     drawCandle_bar_OneTwo(cd_x,cd_l,A_cd_volume,max_volume);

     cdx_global++;

 }



});


function drawCandle_bar_OneTwo(cd_x,cd_l,A_cd_volume,max_volume){

   
  

   // alert('------'+cd_x+'-- cd_l --'+cd_l+'------'+better_volume_color);


   

    // var cd_color_two = resp_obj_bar_one_val_color.cd_color;
        var cd_color_two = 'white';

    

    var cd_time     =    cd_x;
    var cd_l        =    cd_l;
    

    var max_volume_value = max_volume;

    var volume2 =  (A_cd_volume/max_volume_value)*100;
   


    var val_one =  volume2 ;

   
    
    // var val_one = 50;
    // var val_two = 50;
    
    var TopOffset = cHeight +5;
    
    var BottomOffset = 5;
     
    var cnvHeight = cFull_height - BottomOffset - TopOffset  ;
    
    //alert(cnvHeight);
    
    //return false;
    
    
    
    var barHeight = cnvHeight*val_one/100;
    
    var barY = cFull_height - barHeight-5;
    
  
    

    /*alert(barY);
    
    return false;*/
    
    //////////////////////////// Formula for calculate Ratio For X

    var InerWidth = canvasContainerWidth;
    var total_T_length = jQuery(".Tline").val();

    var PerTimeWidth = InerWidth / total_T_length;

    var LeftOffSet = cOffse_left;
    /////////////////////////////

    var cd_xx = (PerTimeWidth*cd_x) + LeftOffSet;
            
    var cd_y = barY;

    var cd_h  = barHeight; 
    

  



    
    
    
    var cd_l = cd_l;
    var cdSiz = canvasContainerWidth / cd_l;
    // 100%
    cdSiz =  (100/100)*cdSiz; 
    
    
    
    
    

    var cdwr = cdSiz;

        cd_w = cdwr

    var cd_x = cd_xx - (cd_w / 2);

    var cd_color_one = '';
    if(val_one<=25){
        
           cd_color_one = '#32CD32';
      }else if(val_one<=50){
        
         cd_color_one = '#0000ff';
      }else if(val_one<=75){
          
         cd_color_one = '#FFFF00';
      }else if(val_one<=100){
     
         cd_color_one = '#FFA500';
      }

  




    




    c.fillStyle = cd_color_one;
    c.fillRect(cd_x,cd_y,cd_w,cd_h);





}



function candleSize(cd_l){
    var cd_l = cd_l;
    var cdSiz = canvasContainerWidth / cd_l;
    // 80%
    cdSiz =  (80/100)*cdSiz
    jQuery(".dddd").text(cdSiz);

    return cdSiz;
}

function randerChart(){
    


    var LengthOfItems = TempArray.length;
    


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
        //drwBarGraph_one_top_Line(); 
        //drwBarGraph_two_top_Line();
        drwBarGraph_OneTwoCombine_line();
        drwTimeLine(Tlength);

        // if(LengthOfItems >= Tlength){
        //  var Tlength = LengthOfItems;
        //  jQuery(".tl").text(Tlength);

        // }else{
            
        //  jQuery(".tl").text(Tlength);
        // }


                var time = Tlength;
                var Price = Plength;

                // var cd_high = 10;
                // var cd_low = 2;
                // var cd_open = 7;
                // var cd_close = 3;
                var total_volume_arr = [];
                var drawCandle_bar_one_array = []; 
                ToolArray = [];






                    if(typeof TempArray !='undefined' && TempArray.length>0){

                    var total_volume_arr  = [];
                    for(barindex in TempArray){



                    var response_obj  = find_volume(TempArray[barindex].openTime,TempArray[barindex].closeTime,TempArray[barindex].open,TempArray[barindex].close,TempArray[barindex].volume,TempArray,barindex);
                    total_volume_arr.push(response_obj.volTarget);
                    }


                    var volume_ordering_array = total_volume_arr.sort(function(a, b){return a - b});
                    var max_volume_value = volume_ordering_array[volume_ordering_array.length-1];
                    }/** End of**/













                for(indexi in TempArray) {
                    

                        total_volume_arr.push(TempArray[indexi].volume);

                        var sortedArr= get_index_Value(TempArray);



                        var HeighValue = 0;
                        var LowValue   = 0;
                        var OpenValue  = 0;
                        var CloseValue = 0;

                        var heign_val = sortedArr.length;



                        if(sortedArr.indexOf(TempArray[indexi].high)){

                        A_cd_high = TempArray[indexi].high;

                        HeighValue  = (sortedArr.indexOf(TempArray[indexi].high) / heign_val) * 100;
                        }
                        var A_cd_low = '';
                        if(sortedArr.indexOf(TempArray[indexi].low)){
                        A_cd_low = TempArray[indexi].low;
                        LowValue  = (sortedArr.indexOf(TempArray[indexi].low) / heign_val) * 100;
                        }

                        if(sortedArr.indexOf(TempArray[indexi].open)){
                        A_cd_open = TempArray[indexi].open;
                        OpenValue  = (sortedArr.indexOf(TempArray[indexi].open) / heign_val) * 100;
                        }

                        if(sortedArr.indexOf(TempArray[indexi].close)){
                        A_cd_close = TempArray[indexi].close;
                        CloseValue  = (sortedArr.indexOf(TempArray[indexi].close) / heign_val) * 100;
                        }

                        if(sortedArr.indexOf(TempArray[indexi].volume)){
                        A_cd_volume = TempArray[indexi].volume;
                        }
                        if(sortedArr.indexOf(TempArray[indexi].openTime)){
                        A_cd_time = TempArray[indexi].openTime;
                        }









                        var actual_closeTime = TempArray[indexi].closeTime;
                        var actual_openTime = TempArray[indexi].openTime;


                        var cd_high  = HeighValue;
                        var cd_low    = LowValue;
                        var cd_open   = OpenValue;
                        var cd_close  = CloseValue;


                        var cd_x = indexi;
                        var cd_l = time;
                        // cd_high =cd_high + 1;
                        // cd_low = cd_low + 1;
                        // cd_open = cd_open + 1;
                        // cd_close = cd_close + 1;




                        //(cd_x,cd_l,cd_high,cd_low,cd_open,cd_close,A_cd_high,A_cd_low,A_cd_open,A_cd_close,A_cd_volume,A_cd_time)
                        drawCandle(cd_x,cd_l,cd_high,cd_low,cd_open,cd_close,A_cd_high,A_cd_low,A_cd_open,A_cd_close,A_cd_volume,A_cd_time);




                        // drawCandle_bar_one(cd_x,cd_l,actual_openTime,actual_closeTime,A_cd_open,A_cd_close,A_cd_volume,TempArray,indexi);

                        var better_volume_color = better_volume(TempArray,A_cd_open,A_cd_close,A_cd_high,A_cd_low,A_cd_volume,TempArray[indexi].openTime,TempArray[indexi].closeTime,indexi);

                        var resp_obj_bar_one_val_color = bar_one_volume_calculate(TempArray[indexi].openTime,TempArray[indexi].closeTime,A_cd_open,A_cd_close,A_cd_volume,TempArray,indexi,max_volume_value);



                        
                       //drawCandle_bar_OneTwo(cd_x,cd_l,better_volume_color,A_cd_volume,TempArray,resp_obj_bar_one_val_color); 

                        //var A_cd_volume = Math.random()*1000;

                       // drawCandle_bar_OneTwo(cd_x,cd_l,A_cd_volume);  

                       


                    
               } 



            
}


function bar_one_volume_calculate(actual_openTime,actual_closeTime,A_cd_open,A_cd_close,A_cd_volume,TempArray,indexi,max_volume_value){


        var response_obj = {};
        var response_obj  = find_volume(actual_openTime,actual_closeTime,A_cd_open,A_cd_close,A_cd_volume,TempArray,indexi);
        var  volume_taget = response_obj.volTarget;
        var volume =  (volume_taget/max_volume_value)*100;
        var cd_color = response_obj.volColor;
        response_obj.volume = volume;
        response_obj.cd_color = cd_color;

        return response_obj;

}


function get_index_Value(arr){

    if(typeof arr !='undefined' && arr.length>0){
     var newArr = {};
     var fullArr = [];
     for(index in arr){
     
     newArr[arr[index].close] = arr[index].close;
     newArr[arr[index].open ]= arr[index].open;
     newArr[arr[index].high] = arr[index].high;
     newArr[arr[index].low] = arr[index].low; 
        
     }
     
     for(index in newArr){
     
        fullArr.push(newArr[index]);
      
     }
     
     
     var sorArr= fullArr.sort(function(a, b){return a - b});

     return sorArr;
     
    }

}
requestAnimationFrame(randerChart);




 function tootip(){
  var w = canvas.width ;
  var h = canvas.height;
  

  jQuery("body").on('click','#myCanvas', function(e) {
    //randerChart();
    var x = e.pageX - canvas.offsetLeft;
    var y = e.pageY - canvas.offsetTop;
    var str = 'X : ' + x + ', ' + 'Y : ' + y;

    
    c.fillStyle = '#ddd';
    c.fillRect(x + 10, y + 10, 80, 25);
    c.fillStyle = '#000';
    c.font = '20px Arial';
    c.fillText(str, x + 20, y + 30, 60);

  });
}

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

    x=e.clientX - canvas.offsetLeft -15 ;
    y=e.clientY - canvas.offsetTop-15;
    
    
    var ys = scroll+y;
    document.getElementById("toptip").innerHTML="Coordinates: (" + x + "," + ys + ")";

    jQuery("#main_price_line").css("top",ys+"px");
    jQuery("#main_time_line").css("left",x+"px");



        var tol_top = ys - 175;
        var tol_left = x + 15;


    jQuery(".toletip").css("left",tol_left+"px");
    jQuery(".toletip").css("top",tol_top+"px");

    //jQuery("#pdgn").text('0.00'+ys);


    var dtimevar = findTimeCordinate(x, ToolArray);
    if(dtimevar === false){
        var datime = datime;
    }else{
        var datime = dateConvert(dtimevar);
    }
    jQuery("#tdgn").text(datime);


    //jQuery("#tdgn").text(x+'Hr');

    // var  vstart_x = 65.62;
    // var  vstart_y = 121.81481481481481;
    // var  end_x = 134.38;
    // var  end_y = 128.320987654321;


    if(findArryVal(x, ys, ToolArray)){
        var get_toltiphtml = findArryVal(x, ys, ToolArray);
        console.log("true");
        jQuery("#toptip").show();
        jQuery(".toletip").show();
        jQuery(".toletip").html(get_toltiphtml);
    }else{
        console.log("false");
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

    console.log('________________________________');
    console.log('max_val ='+max_val+'min_val ='+min_val);
    $('.dummmy').html('max_val ='+max_val+'min_val ='+min_val);
  
      console.log('________________________________');
    var unit_val = (max_val-min_val);
    var att_50 = ((unit_val*per_pxl_price_percent)/100)+parseFloat(min_val);
    jQuery("#pdgn").text(att_50.toFixed(8));
 // jQuery("#pdgn").text(per_pxl_price_percent)




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
                    <li><strong>High:</strong> '+array[index].A_cd_high+'</li>\
                    <li><strong>Low:</strong> '+array[index].A_cd_low+'</li>\
                    <li><strong>Open:</strong> '+array[index].A_cd_open+'</li>\
                    <li><strong>Close:</strong> '+array[index].A_cd_close+'</li>\
                    <li><strong>Volume:</strong> '+array[index].A_cd_volume+'</li>\
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

function cnvs_clearCoordinates(){
    document.getElementById("toptip").innerHTML="";
}


   function autoload_candle_stick_data(){

        var period = '1h';
          $.ajax({
        type:'POST',
        url:'<?php echo SURL?>admin/Candel/autoload_candle_stick_data_from_database_ajax',
        data: {period:period},
         dataType: "json",
        success:function(response){
           
            var res_Arr = response.candlesdtickArr;
            console.log(res_Arr);
            TempArray = res_Arr;

           requestAnimationFrame(randerChart);
              
        
             setTimeout(function(){
          
                    autoload_candle_stick_data();
                  
              }, 5000);
                     
        }
      });
   }

// $(document).ready(function() {
//      autoload_candle_stick_data();
    	
// })

 previous_v3 = 0;
 previous_v1 = 0;
 previous_v2 = 0;
 previous_close = 0;
 previous_open  = 0;



 /*** Function  For   ***/

 function better_volume(candle_stick_arr,open_price,close_price,heigh_price,low_price,trade_volume,open_time,close_time,index){

 	/** Usr Value **/
 	var Lookback = $('#Lookback').val();
 	var enableBarColors = $('#enableBarColors').prop('checked');
 	var use2Bars = $('#use2Bars').prop('checked');
 	var lowVol = $('#lowVol').prop('checked');
 	var climaxUp = $('#climaxUp').prop('checked');
 	var climaxDown  = $('#climaxDown').prop('checked');
 	var churn = $('#churn').prop('checked');
 	var climaxChurn = $('#climaxChurn').prop('checked');
 	/** End of usr value **/


	/*******  Tweak the colors *****/
	var lowVolColor = '	#F5F445';
    var climaxUpColor = '#548DD3';
	var climaxDownColor = '#FE0000';
	var churnColor = '#000000';
	var climaxChurnColor = '#FF57FF';
	var defColor = '#B2E489';

	/*** Function variable ***/
    open_price = parseFloat(open_price);
    close_price = parseFloat(close_price);
    heigh_price = parseFloat(heigh_price);
    low_price = parseFloat(low_price);
    trade_volume = parseFloat(trade_volume);


    var heighst_lowest_look_back = 10;

	var range = (heigh_price-low_price);
    var v = trade_volume;

    /********* Volume part**********/
    v1 = close_price >= open_price ? (v * ((range) / ((2+(range*range)/10) * range + (open_price - close_price)))) : (v * (((range + close_price - open_price)) / (2+(range*range)/10) * range + (close_price - open_price)));
    //alert('original V1 ='+v1);
	v2 = v - v1;
	v3 = v1 + v2;
	v4 = v1 * range;
	v5 = (v1 - v2) * range;
	v6 = v2 * range;
	v7 = (v2 - v1) * range;
	v8 = (range != 0 ?  v1 / range : 1);
	v9 = (range != 0 ? (v1 - v2) / range : 1);
	v10 = (range != 0 ?  v2 / range : 1);
	v11 = (range != 0 ?  (v2 - v1) / range :  1);
	v12 = (range != 0 ?  v3 / range : 1);


	v13 = use2Bars ? v3 + previous_v3 : 1;
	v14 = (use2Bars ? (v1 + previous_v1)*(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);
	
	v15 = (use2Bars ? (v1 + previous_v1-v2-previous_v2)*(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);
	v16 = (use2Bars ? (v2 + previous_v2)*(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);


	v17 = (use2Bars ? (v2 + previous_v2-v1-previous_v1)*(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);


	v18 =  ((use2Bars && (highest_heigh(candle_stick_arr,2,index)!=lowest_low(candle_stick_arr,2,index))) ? (v1+previous_v1)/(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);



	v19 = ((use2Bars && (highest_heigh(candle_stick_arr,2,index)!=lowest_low(candle_stick_arr,2,index))) ? (v1+previous_v1-v2-previous_v2)/(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);


	v20 = ((use2Bars && (highest_heigh(candle_stick_arr,2,index)!=lowest_low(candle_stick_arr,2,index))) ? (v2+previous_v2)/(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);



	v21 = ((use2Bars && (highest_heigh(candle_stick_arr,2,index)!=lowest_low(candle_stick_arr,2,index))) ? (v2+previous_v2-v1-previous_v1)/(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);


	v22 = ((use2Bars && (highest_heigh(candle_stick_arr,2,index)!=lowest_low(candle_stick_arr,2,index))) ? v13/(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);

	 /********* End of  Volume part**********/


	 c1 = (v3 == dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'lowest_volume_3',heighst_lowest_look_back) ?  1 : 0);

	     c2_dynamic = ((v4 == dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'heigst_volume_4',heighst_lowest_look_back) && close_price > open_price) ? 1 : 0);

           //c2 = ((v4 == highest_volume_4(candle_stick_arr,Lookback,index) && close_price > open_price) ? 1 : 0);

           c2 = ((v4 == dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'heigst_volume_4',heighst_lowest_look_back) && close_price > open_price) ? 1 : 0);

         // alert('c2_dynamic ='+ dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'heigst_volume_4',heighst_lowest_look_back) +'simplel ='+highest_volume_4(candle_stick_arr,Lookback,index)+'   index   ='+index);

	  c3 = ((v5 == dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'heigst_volume_5',heighst_lowest_look_back) && close_price > open_price) ? 1 : 0);

	 c4 = ((v6 == dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'heigst_volume_6',heighst_lowest_look_back) && close_price < open_price) ? 1 : 0);


	 c5 = ((v7 == dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'heigst_volume_7',heighst_lowest_look_back) && close_price < open_price) ? 1 : 0);

	 c6 = ((v8 == dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'lowest_volume_8',heighst_lowest_look_back) && close_price < open_price) ? 1 : 0);


	 c7 = ((v9 == dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'lowest_volume_9',heighst_lowest_look_back) && close_price < open_price) ? 1 : 0);

	 c8 = ((v10 == dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'lowest_volume_10',heighst_lowest_look_back) && close_price > open_price) ? 1 : 0);


	 c9 = ((v11 == dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'lowest_volume_11',heighst_lowest_look_back) && close_price > open_price) ? 1 :  0);


	 c10 = (v12 == dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'heigst_volume_12',heighst_lowest_look_back) ? 1 : 0);


	 c11 = (use2Bars && (v13==dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'lowest_volume_13',heighst_lowest_look_back) && close_price > open_price && previous_close > previous_open) ? 1 : 0);

	 c12 = (use2Bars && (v14==dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'heigst_volume_14',heighst_lowest_look_back) && close_price > open_price && previous_close > previous_open) ? 1 : 0);


	 c13 = (use2Bars && (v15==dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'heigst_volume_15',heighst_lowest_look_back) && close_price > open_price && previous_close < previous_open) ? 1 : 0);


     c14 = (use2Bars && (v16==dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'lowest_volume_16',heighst_lowest_look_back) && close_price < open_price && previous_close < previous_open) ? 1 : 0);


     c15 = (use2Bars && (v17==dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'lowest_volume_17',heighst_lowest_look_back) && close_price < open_price && previous_close < previous_open) ? 1 : 0);


     c16 = (use2Bars && (v18==dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'lowest_volume_18',heighst_lowest_look_back) && close_price < open_price && previous_close < previous_open) ? 1 : 0);


     c17 = (use2Bars && (v19==dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'lowest_volume_19',heighst_lowest_look_back) && close_price > open_price && previous_close < previous_open) ? 1 : 0);

     c18 = (use2Bars && (v20==dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'lowest_volume_20',heighst_lowest_look_back) && close_price > open_price && previous_close > previous_open) ? 1 : 0);



     c19 = (use2Bars && (v21==dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'lowest_volume_21',heighst_lowest_look_back) && close_price > open_price && previous_close > previous_open) ? 1 : 0);


     c20 = (use2Bars && (v22==dynamic_volume(candle_stick_arr,Lookback,previous_v1,previous_v2,previous_v3,use2Bars,index,'lowest_volume_22',heighst_lowest_look_back)) ? 1 : 0)

  


      c0=(climaxUp && (c2 || c3 || c8 || c9 || c12 || c13 || c18 || c19)) ? climaxUpColor : ((climaxDown && (c4 || c5 || c6 || c7 || c14 || c15 || c16 || c17)) ? climaxDownColor : ((churn && c10 || c20) ? churnColor : defColor));




     v_color=(climaxChurn && (c10 || c20)) && (c2 || c3 || c4 || c5 || c6 || c7 || c8 || c9) ? climaxChurnColor : ((lowVol && (c1 || c11)) ? lowVolColor : c0)

	previous_close = close_price;
	previous_open = open_price;
	previous_v1 = v1;
	previous_v2 = v2;
	previous_v3 = v3;


   return v_color;
 }
 /*** Function for better volume ****/




 	function dynamic_volume(candle_stick_arr,number_of_look_back,previous_v1,previous_v2,previous_v3,use2Bars,index,formula_name,heighst_lowest_look_back){

                //alert('look back '+number_of_look_back);
                var  lowest_volume_22 = 0;
                var  lowest_volume_21 = 0;
                var  lowest_volume_20 = 0;
                var  lowest_volume_19 = 0;
                var  lowest_volume_18 = 0;
                var  lowest_volume_17 = 0;
                var  lowest_volume_16 = 0;

                var  heigst_volume_15 = 0;
                var  heigst_volume_14 = 0;


                var  lowest_volume_13 = 0;
                var  heigst_volume_12 = 0;

                var  lowest_volume_11 = 0;
                var  lowest_volume_10 = 0;
                var  lowest_volume_9 = 0;
                var  lowest_volume_8 = 0;
                var  lowest_volume_7 = 0;


                var  heigst_volume_6 = 0;
                var  heigst_volume_5 = 0;
                var  heigst_volume_4 = 0;

                var  lowest_volume_3 = 0;


                var lowest_volume_arr_22 = [];
                var lowest_volume_arr_21 = [];
                var lowest_volume_arr_20 = [];
                var lowest_volume_arr_19 = [];
                var lowest_volume_arr_18 = [];
                var lowest_volume_arr_17 = [];
                var lowest_volume_arr_16 = [];

                var  highest_volume_arr_15 = [];
                var  highest_volume_arr_14 = [];


                var  lowest_volume_arr_13 = [];

                var  highest_volume_arr_12= [];

                var lowest_volume_arr_11 = [];
                var lowest_volume_arr_10 = [];
                var lowest_volume_arr_9 = [];
                var lowest_volume_arr_8 = [];

                var  highest_volume_arr_7 = [];
                var  highest_volume_arr_6 = [];
                var  highest_volume_arr_5 = [];
                var  highest_volume_arr_4 = [];

                var lowest_volume_arr_3 = [];




			if(typeof candle_stick_arr !='undifined' && candle_stick_arr.length>0){

			for(var i=number_of_look_back;i>=0;i--){

					var previous_index = index-i;
					previous_index = Math.abs(previous_index);

					/*** ***/
					var open_price = parseFloat(candle_stick_arr[previous_index].open);
					var close_price = parseFloat(candle_stick_arr[previous_index].close);
					var heigh_price = parseFloat(candle_stick_arr[previous_index].high);
					var low_price = parseFloat(candle_stick_arr[previous_index].low);
					var trade_volume = parseFloat(candle_stick_arr[previous_index].volume);
					var range = (heigh_price-low_price);
					var v = trade_volume;

					v1 = close_price >= open_price ? (v * ((range) / ((2+(range*range)/10) * range + (open_price - close_price)))) : (v * (((range + close_price - open_price)) / (2+(range*range)/10) * range + (close_price - open_price)));
                   // alert('calculated  volume'+v1);

					v2 = v - v1;
					v3 = v1 + v2;
                       lowest_volume_arr_3.push(v3);
					v4 = v1 * range;
                         highest_volume_arr_4.push(v4);


					v5 = (v1 - v2) * range;
                         highest_volume_arr_5.push(v5);
					v6 = v2 * range;
                        highest_volume_arr_6.push(v6);


					v7 = (v2 - v1) * range;
                        highest_volume_arr_7.push(v7);

					v8 = (range != 0 ?  v1 / range : 1);
                      lowest_volume_arr_8.push(v8);

					v9 = (range != 0 ? (v1 - v2) / range : 1);
                        lowest_volume_arr_9.push(v9);

					v10 = (range != 0 ?  v2 / range : 1);
                        lowest_volume_arr_10.push(v10);

					v11 = (range != 0 ?  (v2 - v1) / range :  1);
                    lowest_volume_arr_11.push(v11);

					v12 = (range != 0 ?  v3 / range : 1);
                    highest_volume_arr_12.push(v12);
                    

                    v13 = use2Bars ? v3 + previous_v3 : 1;
                    lowest_volume_arr_13.push(v13);




					v14 = (use2Bars ? (v1 + previous_v1)*(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);

                     highest_volume_arr_14.push(v14);

					v15 = (use2Bars ? (v1 + previous_v1-v2-previous_v2)*(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);
                    highest_volume_arr_15.push(v15);


					v16 = (use2Bars ? (v2 + previous_v2)*(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);

                    lowest_volume_arr_16.push(v16);

					v17 = (use2Bars ? (v2 + previous_v2-v1-previous_v1)*(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);

                    lowest_volume_arr_17.push(v17);

					v18 =  ((use2Bars && (highest_heigh(candle_stick_arr,2,index)!=lowest_low(candle_stick_arr,2,index))) ? (v1+previous_v1)/(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);
                    lowest_volume_arr_18.push(v18);


					v19 = ((use2Bars && (highest_heigh(candle_stick_arr,2,index)!=lowest_low(candle_stick_arr,2,index))) ? (v1+previous_v1-v2-previous_v2)/(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);
                        lowest_volume_arr_19.push(v19);

					v20 = ((use2Bars && (highest_heigh(candle_stick_arr,2,index)!=lowest_low(candle_stick_arr,2,index))) ? (v2+previous_v2)/(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);

                    lowest_volume_arr_20.push(v20);

					v21 = ((use2Bars && (highest_heigh(candle_stick_arr,2,index)!=lowest_low(candle_stick_arr,2,index))) ? (v2+previous_v2-v1-previous_v1)/(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);

                    lowest_volume_arr_21.push(v21);



					v22 = ((use2Bars && (highest_heigh(candle_stick_arr,2,index)!=lowest_low(candle_stick_arr,2,index))) ? v13/(highest_heigh(candle_stick_arr,2,index)-lowest_low(candle_stick_arr,2,index)) : 1);

					lowest_volume_arr_22.push(v22);

					/*****/
				}
			}

            /**************  lowest_volume__22   *************/
                if(formula_name == 'lowest_volume_22'){

                        if(lowest_volume_arr_22.length>0){
                         var lowest_volume_22 =Math.min.apply(null, lowest_volume_arr_22);
                        }
                        return lowest_volume_22;
                }

            /*********  End of lowest_volume__22  **********/


            /**************  lowest_volume__21   *************/
                if(formula_name == 'lowest_volume_21'){

                        if(lowest_volume_arr_21.length>0){
                         var lowest_volume_21 =Math.min.apply(null, lowest_volume_arr_21);
                        }
                        return lowest_volume_21;
                }

            /*********  End of lowest_volume__21  **********/


            /**************  lowest_volume__20   *************/
                if(formula_name == 'lowest_volume_20'){

                        if(lowest_volume_arr_20.length>0){
                         var lowest_volume_20 =Math.min.apply(null, lowest_volume_arr_20);
                        }
                        return lowest_volume_20;
                }

            /*********  End of lowest_volume__20 **********/


            /**************  lowest_volume__19   *************/
                if(formula_name == 'lowest_volume_19'){

                        if(lowest_volume_arr_19.length>0){
                         var lowest_volume_19 =Math.min.apply(null, lowest_volume_arr_19);
                        }
                        return lowest_volume_19;
                }

            /*********  End of lowest_volume__19 **********/

            /**************  lowest_volume__18   *************/
                if(formula_name == 'lowest_volume_18'){

                        if(lowest_volume_arr_18.length>0){
                         var lowest_volume_18 =Math.min.apply(null, lowest_volume_arr_18);
                        }
                        return lowest_volume_18;
                }

            /*********  End of lowest_volume__18 **********/


            /**************  lowest_volume__17   *************/
                if(formula_name == 'lowest_volume_17'){

                        if(lowest_volume_arr_17.length>0){
                         var lowest_volume_17 =Math.min.apply(null, lowest_volume_arr_17);
                        }
                        return lowest_volume_17;
                }

            /*********  End of lowest_volume__17 **********/

            /**************  lowest_volume__16   *************/
                if(formula_name == 'lowest_volume_16'){

                        if(lowest_volume_arr_16.length>0){
                         var lowest_volume_16 =Math.min.apply(null, lowest_volume_arr_16);
                        }
                        return lowest_volume_16;
                }

            /*********  End of lowest_volume__16 **********/


            /**************  heigst_volume_15   *************/
                if(formula_name == 'heigst_volume_15'){

                        if(highest_volume_arr_15.length>0){
                         var heigst_volume_15 =Math.max.apply(null, highest_volume_arr_15);
                        }
                        return heigst_volume_15;
                }

            /*********  End of heigst_volume_15 **********/


            /**************  heigst_volume_14   *************/
                if(formula_name == 'heigst_volume_14'){

                        if(highest_volume_arr_14.length>0){
                         var heigst_volume_14 =Math.max.apply(null, highest_volume_arr_14);
                        }
                        return heigst_volume_14;
                }

            /*********  End of heigst_volume_14 **********/

             /**************  lowest_volume__13   *************/
                if(formula_name == 'lowest_volume_13'){

                        if(lowest_volume_arr_13.length>0){
                         var lowest_volume_13 =Math.min.apply(null, lowest_volume_arr_13);
                        }
                        return lowest_volume_13;
                }

            /*********  End of lowest_volume__13 **********/


            /**************  heigst_volume_12   *************/
                if(formula_name == 'heigst_volume_12'){

                        if(highest_volume_arr_12.length>0){
                         var heigst_volume_12 =Math.max.apply(null, highest_volume_arr_12);
                        }
                        return heigst_volume_12;
                }
            /**************  heigst_volume_12   *************/


            /**************  lowest_volume__11   *************/
                if(formula_name == 'lowest_volume_11'){

                        if(lowest_volume_arr_11.length>0){
                         var lowest_volume_11 =Math.min.apply(null, lowest_volume_arr_11);
                        }
                        return lowest_volume_11;
                }

            /*********  End of lowest_volume__11 **********/


            /**************  lowest_volume__10   *************/
                if(formula_name == 'lowest_volume_10'){

                        if(lowest_volume_arr_10.length>0){
                         var lowest_volume_10 =Math.min.apply(null, lowest_volume_arr_10);
                        }
                        return lowest_volume_10;
                }

            /*********  End of lowest_volume__10 **********/


            /**************  lowest_volume__19  *************/
                if(formula_name == 'lowest_volume_9'){

                        if(lowest_volume_arr_9.length>0){
                         var lowest_volume_9 =Math.min.apply(null, lowest_volume_arr_9);
                        }
                        return lowest_volume_9;
                }

            /*********  End of lowest_volume__9 **********/

            /**************  lowest_volume__8  *************/
                if(formula_name == 'lowest_volume_8'){

                        if(lowest_volume_arr_8.length>0){
                         var lowest_volume_8 =Math.min.apply(null, lowest_volume_arr_8);
                        }
                        return lowest_volume_8;
                }

            /*********  End of lowest_volume__8 **********/

            /**************  heigst_volume_7   *************/
                if(formula_name == 'heigst_volume_7'){

                        if(highest_volume_arr_7.length>0){
                         var heigst_volume_7 =Math.max.apply(null, highest_volume_arr_7);
                        }
                        return heigst_volume_7;
                }

            /*********  End of heigst_volume_7 **********/



             /**************  heigst_volume_6   *************/
                if(formula_name == 'heigst_volume_6'){

                        if(highest_volume_arr_6.length>0){
                         var heigst_volume_6 =Math.max.apply(null, highest_volume_arr_6);
                        }
                        return heigst_volume_6;
                }

            /*********  End of heigst_volume_6 **********/


             /**************  heigst_volume_5   *************/
                if(formula_name == 'heigst_volume_5'){

                        if(highest_volume_arr_5.length>0){
                         var heigst_volume_5 =Math.max.apply(null, highest_volume_arr_5);
                        }
                        return heigst_volume_5;
                }

            /*********  End of heigst_volume_5 **********/


            /**************  heigst_volume_4   *************/
                if(formula_name == 'heigst_volume_4'){

                        if(highest_volume_arr_4.length>0){
                         var heigst_volume_4 =Math.max.apply(null, highest_volume_arr_4);
                        }
                        return heigst_volume_4;
                }

            /*********  End of heigst_volume_4 **********/


            /**************  lowest_volume__3  *************/
                if(formula_name == 'lowest_volume_9'){

                        if(lowest_volume_arr_3.length>0){
                         var lowest_volume_3 =Math.min.apply(null, lowest_volume_arr_3);
                        }
                        return lowest_volume_3;
                }

            /*********  End of lowest_volume__3 **********/




	}




		function highest_heigh(candle_stick_arr,number_of_look_back,index){
			
    		var heigh_val  = 0;
          
    		if(typeof candle_stick_arr !='undifined' && candle_stick_arr.length>0){
    		for(var i=number_of_look_back;i>=0;i--){

    			var previous_index = index-i;
    			previous_index = Math.abs(previous_index);
    			if(candle_stick_arr[previous_index].high > heigh_val) {
    			heigh_val = candle_stick_arr[previous_index].high;

    		  }
    		}
    		}
    		return parseFloat(heigh_val); 
		}/** End of highest_heigh ***/


		function lowest_low(candle_stick_arr,number_of_look_back,index){
			
    		var low_val  = 0;

    		if(typeof candle_stick_arr !='undifined' && candle_stick_arr.length>0){
    		for(var i=number_of_look_back;i>=0;i--){

    			var previous_index = index-i;
    			previous_index = Math.abs(previous_index);

    			if(candle_stick_arr[previous_index].low < low_val) {
    			low_val = candle_stick_arr[previous_index].low;

    		  }
    		}
    		}
    		return parseFloat(low_val); 
		}/** End of highest_heigh ***/





        /*** Function for calculing volume pressure**/


        function calculate_volume_pressure(get_market_history_for_candel){

                    

                var total_volume_arr = get_market_history_for_candel.total_volume_arr;
                var bid_volume_arr = get_market_history_for_candel.bid_volume_arr;
                var ask_volume_arr = get_market_history_for_candel.ask_volume_arr;

                var dates_arr = Object.keys(total_volume_arr);
                
                var PercentileTrigger = $('#PercentileTrigger').val();
                var DemandTrigger = $('#DemandTrigger').val();
                var SupplyTrigger =  $('#SupplyTrigger').val();
                var BarsBack = $('#BarsBack').val();


                if(dates_arr.length>0){

                    for(var index =0;index<dates_arr.length;index++){

                       
                        var TotalVolume = total_volume_arr[dates_arr[index]];

                         var BuyPressure = ask_volume_arr[dates_arr[index]];

                         var SellPressure = bid_volume_arr[dates_arr[index]];

                         var DeltaPressure = BuyPressure - SellPressure;


                      //  var DemandCandle = BuyPressure > SellPresure && BuyPressure >= DemandTrigger
                        //var SupplyCandle = SellPressure > BuyPressure && SellPressure >= SupplyTrigger

                        
                    }
                }


             

             
        }

        calculate_volume_pressure(get_market_history_for_candel);



</script>


