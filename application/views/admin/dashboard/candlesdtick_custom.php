
<script src="https://code.jquery.com/jquery-3.3.1.js" type="text/javascript"></script>

<?php

$candlesdtickArr = $candlesdtickArr;

  $compare_val = $compare_val;
  $candle_period = $candle_period;

 ?>
<script>





var dataArr = <?php echo json_encode($candlesdtickArr); ?>;
var draw_zone_arr = <?php echo json_encode($compare_val); ?>;

console.log(draw_zone_arr);



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





function GetVal(maxQ,maxV,finQ){
    var max_Q = maxQ;
    var max_V = maxV;   
    var singl_Q = max_V / max_Q;
    var fin_Q = singl_Q * finQ;
    return fin_Q;
}



var dsid = 0;





function drawCandle(
dsid,
lineXxs,
candleXxs,
s_time,
s_high,
s_low,
s_open,
s_close ,
actual_HeighValue,actual_LowValue,actual_OpenValue,actual_CloseValue,actual_volume,open_time
){
    
       
        var open_time = dateConvert(open_time);
        /*--------------Time line------*/
            var line_width = 4;
            line_width = line_width/2;
            
            var candle_width = 20;
            candle_width = candle_width/2;
            
            var if_time_val   =  1;
            var time_xs_val   =  100;
            
            
            //////////////////////////// Formula for calculate Ratio
            
            var BasePrice1 = 125;
            var BasePriceY1 = 0;
            
            var ifPrice1 = 100;
            var ifPriceY1 = 100;
            
            var ifPrice2 = 75;
            var ifPriceY2 = 200;
            
            var PriceInterval = ifPrice1 - ifPrice2;
            
            var PriceYxInterval = ifPriceY2 - ifPriceY1;
            
            var PriceRatio = PriceYxInterval / PriceInterval;
            
            /////////////////////////////

        
    dsid++;
    
        
        
if(s_time == null){
    s_time   =    jQuery(".s_time").val();
}
if(s_high == null){
    s_high   =    jQuery(".s_high").val();
}
if(s_low == null){
    s_low    =    jQuery(".s_low").val();
}
if(s_open == null){
    s_open   =    jQuery(".s_open").val();
}
if(s_close == null){
    s_close  =    jQuery(".s_close").val();
}
        
        
        if(lineXxs == null){
        ///////////
        var line_time_Xs = GetVal(if_time_val,time_xs_val,s_time)-line_width;
        ///////////
        }else{
        var line_time_Xs = lineXxs-line_width;
        }
        
        
        ///////////
        var line_topVal = (BasePrice1-s_high)*PriceRatio;
        //////////
        
        
        ///////////
        var line_bottomVal = (BasePrice1-s_low)*PriceRatio;
        //////////
        
        ///////////
        var line_height = line_bottomVal - line_topVal;
        ///////////
        
        
        /*--------------------------------------*/
        
        
        if(candleXxs == null){
        ///////////
        var candle_time_Xs = GetVal(if_time_val,time_xs_val,s_time)-candle_width;
        ///////////
        }else{
        var candle_time_Xs = candleXxs-candle_width;    
        }
        
        
        ///////////
        var candle_topVal = (BasePrice1-s_open)*PriceRatio;
        //////////
        
        
        ///////////
        var candle_bottomVal = (BasePrice1-s_close)*PriceRatio;
        //////////
        
        ///////////
        var candle_height = candle_bottomVal - candle_topVal;
        ///////////
        
        
    
        var baselineX = parseInt(candle_time_Xs)+9.5;
        var tolltipX = baselineX - 165;
        var tolltipY = candle_topVal - 50;
        
        var tooltipTextX = parseInt(tolltipX)+15;  
        
        var tooltipTextY1 = parseInt(tolltipY)+20; 
        var tooltipTextY2 = parseInt(tooltipTextY1)+17; 
        var tooltipTextY3 = parseInt(tooltipTextY2)+17; 
        var tooltipTextY4 = parseInt(tooltipTextY3)+17; 
        var tooltipTextY5 = parseInt(tooltipTextY4)+17; 
        
        var polyX1 = candle_time_Xs-10;
        var polyY1 = tolltipY -15+52.5;
        
        var polyX2 = candle_time_Xs;
        var polyY2 = tolltipY - 7.5+57;
        
        var polyX3 = candle_time_Xs-10;
        var polyY3 = tolltipY-1+60;
        
    
        var timePolyX1 = parseInt(line_time_Xs)+2;
        var timePolyY1 = 500;
        
        var timePolyX2 = timePolyX1-1+6;
        var timePolyY2 = parseInt(timePolyY1)+10;
        
        var timePolyX3 = parseInt(timePolyX2)+70;
        var timePolyY3 = timePolyY2;
        
        var timePolyX4 = timePolyX3
        var timePolyY4 = parseInt(timePolyY3) + 25;
        
        var timePolyX5 = timePolyX4 - 150;
        var timePolyY5 = timePolyY4;
        
        var timePolyX6 = timePolyX5;
        var timePolyY6 = timePolyY5-25;
        
        var timePolyX7 = parseInt(timePolyX6)+70;
        var timePolyY7 = timePolyY6;
        
        var timePolyX_text = timePolyX1 - 70;
        var timePolyY_text = 527;
        
        var teebar = '<rect fill="#333" height="2" width="20" x="'+candle_time_Xs+'" y="'+candle_topVal+'"/>'

        
        var object = document.getElementById("svgid");
        var candleHtml = '\
        <g class="dataSet" id="DS_'+dsid+'">\
            <g id="toll">\
                <rect class="back_base_line" fill="#f00" height="420" width="0.5" x="'+baselineX+'" y="90"/>\
                <rect class="tolltip" rx="10" ry="10" stroke="#333" stroke-width="2" fill="#fff" fill-opacity="0.8" height="100" width="150" x="'+tolltipX+'" y="'+tolltipY+'"/>\
                <polygon points="'+polyX1+','+polyY1+' '+polyX2+','+polyY2+' '+polyX3+','+polyY3+'" style="fill:#73A409;" />\
                <text class="tooltip_text" y="'+tooltipTextY1+'" x="'+tooltipTextX+'">High Price : '+actual_HeighValue+'</text>\
                <text class="tooltip_text" y="'+tooltipTextY2+'" x="'+tooltipTextX+'">Low Price : '+actual_LowValue+'</text>\
                <text class="tooltip_text" y="'+tooltipTextY3+'" x="'+tooltipTextX+'">Open Price : '+actual_OpenValue+'</text>\
                <text class="tooltip_text" y="'+tooltipTextY4+'" x="'+tooltipTextX+'">Close Price : '+actual_CloseValue+'</text>\
                <text class="tooltip_text" y="'+tooltipTextY5+'" x="'+tooltipTextX+'">Volume : '+actual_volume+'</text>\
                <polygon class="timePoly" points="'+timePolyX1+','+timePolyY1+' '+timePolyX2+','+timePolyY2+' '+timePolyX3+','+timePolyY3+' '+timePolyX4+','+timePolyY4+' '+timePolyX5+','+timePolyY5+' '+timePolyX6+','+timePolyY6+' '+timePolyX7+','+timePolyY7+' " />\
                <text class="timePoly_text" y="'+timePolyY_text+'" x="'+timePolyX_text+'">Date: '+open_time+'</text>\
            </g>\
            <rect fill="#333" height="'+line_height+'" width="4" x="'+line_time_Xs+'" y="'+line_topVal+'"/>'+teebar+'\
            <rect fill="#3366CC" height="'+candle_height+'" width="20" x="'+candle_time_Xs+'" y="'+candle_topVal+'"/>\
        </g>';
        jQuery(object).append(candleHtml);
        
        jQuery("#svgid").html( jQuery("#svgid").html());
        
        //alert(candleHtml);
        
}



function drawZone(id,ft_c,tt_c,TimeFrom,TimeTo,PriceFrom,PriceTo,type){
   
    var if_time_val   =  1;
    var time_xs_val   =  100;
    
    
    
    if(TimeFrom == null){
        var f_time   =    jQuery(".f_time").val();
    }else{
        var f_time   =  TimeFrom;
    }
            
    if(TimeTo == null){
        var t_time   =    jQuery(".t_time").val();
    }else{
        var t_time   =  TimeTo;
    }       
            
    if(PriceFrom == null){
        var f_price   =    jQuery(".f_price").val();
    }else{
        var f_price   =     PriceFrom;
    }       
            
    if(PriceTo == null){
        var t_price   =    jQuery(".t_price").val();
    }else{
        var t_price   =     PriceTo;
    }       
            
            
             
            
            //////////////////////////// Formula for calculate Ratio
            
            var BasePrice1 = 125;
            var BasePriceY1 = 0;
            
            var ifPrice1 = 100;
            var ifPriceY1 = 100;
            
            var ifPrice2 = 75;
            var ifPriceY2 = 200;
            
            var PriceInterval = ifPrice1 - ifPrice2;
            
            var PriceYxInterval = ifPriceY2 - ifPriceY1;
            
            var PriceRatio = PriceYxInterval / PriceInterval;
            
            /////////////////////////////
    
    
            ///////////
            var zone_topVal = (BasePrice1-t_price)*PriceRatio; 
            //////////
            
            
            ///////////
            var zone_bottomVal = (BasePrice1-f_price)*PriceRatio;
            //////////
            
            ///////////
            var zone_height = zone_bottomVal - zone_topVal;
            ///////////
    
    
            if(ft_c==null){
            var zone_time_Xs_start = GetVal(if_time_val,time_xs_val,f_time)+100;
            }else{
            var zone_time_Xs_start = ft_c;  
            }
            
            
            if(tt_c==null){
            var zone_time_Xs_end = GetVal(if_time_val,time_xs_val,t_time)+100;
                
            }else{
            var zone_time_Xs_end = tt_c;
            }
            
            
            
            var zoon_width =parseInt(zone_time_Xs_end) - parseInt(zone_time_Xs_start);


    
        if(type =='buy'){
           var color = 'rgba(0,255,0,0.5)';

        }else if(type =='sell'){
            var color = 'rgba(255,0,0,0.5)';
        }else{
            var color = 'rgba(0,0,255,0.5)';
        }
    
    var object = document.getElementById("svgid");
        var ZoneHtml = '<g id="ZoneSet"><rect fill="'+color+'" height="'+zone_height+'" width="'+zoon_width+'" x="'+zone_time_Xs_start+'" y="'+zone_topVal+'"/></g>';

        jQuery(object).prepend(ZoneHtml);
        
        jQuery("#svgid").html( jQuery("#svgid").html());
    
    
    
}


function draw_price_lines(sort_order_array){

    // alert(JSON.stringify(sort_order_array));




        var max_val = sort_order_array[sort_order_array.length-1];
        var min_val = sort_order_array[0];

        var unit_val = (max_val-min_val);

    
        // var price_difference = maximum_val-minimum_val;
        // var unit_price = (price_difference/100);
        


       // price = unit_price*i;


        var object = document.getElementById("svgid");
        
        for(i=1; i <= 100; i++){
            var priHeight = i*4;
            var priHY = 500 - priHeight;
            
            var price_linesPolyX1 = 5;
            var price_linesPolyY1 = priHY - 7;
            
            var price_linesPolyX2 = 85;
            var price_linesPolyY2 = price_linesPolyY1;
            
            var price_linesPolyX3 = 90;
            var price_linesPolyY3 = parseInt(price_linesPolyY2)+7;
            
            var price_linesPolyX4 = 85;
            var price_linesPolyY4 = parseInt(price_linesPolyY3)+7;
            
            var price_linesPolyX5 = 5;
            var price_linesPolyY5 = price_linesPolyY4;
            
            var price_linesPolyX6 = 5;
            var price_linesPolyY6 = parseInt(price_linesPolyY5)-14;
            
            
            var price_linesPolyYText = parseInt(priHY) + 4;
            
            
            
            
            
            
            
            var price_linesPolyX11 = 1315;
            var price_linesPolyY11 = priHY - 7;
            
            var price_linesPolyX22 = 1395;
            var price_linesPolyY22 = price_linesPolyY1;
            
            var price_linesPolyX33 = 1395;
            var price_linesPolyY33 = parseInt(price_linesPolyY22)+14;
            
            var price_linesPolyX44 = 1315;
            var price_linesPolyY44 = parseInt(price_linesPolyY33);
            
            var price_linesPolyX55 = 1310;
            var price_linesPolyY55 = price_linesPolyY4-7;
            
            var price_linesPolyX66 = 1315;
            var price_linesPolyY66 = parseInt(price_linesPolyY55)-7;
            
            
            var price_linesPolyYTextR = parseInt(priHY) + 4;
            
             

          
      

          var price = ((unit_val*i)/100)+parseFloat(min_val);
          
          
           price = (price).toFixed(8);

            var price_linesHtml = '\
            <g id="price_linesSet">\
                <rect class="price_lines" fill="rgba(0,0,255,0)" height="4" width="1210" x="90" y="'+priHY+'"/>\
                <rect class="price_lines_hover" fill="#73A409" height="0.5" width="1220" x="90" y="'+priHY+'"/>\
                <polygon fill="rgba(0,0,255,0)" class="price_linesPoly" points="'+price_linesPolyX1+','+price_linesPolyY1+' '+price_linesPolyX2+','+price_linesPolyY2+' '+price_linesPolyX3+','+price_linesPolyY3+' '+price_linesPolyX4+','+price_linesPolyY4+' '+price_linesPolyX5+','+price_linesPolyY5+' '+price_linesPolyX6+','+price_linesPolyY6+'" />\
                <text class="price_linesPoly_text" y="'+price_linesPolyYText+'" x="10">Price : $'+price+'</text>\
                <polygon fill="rgba(0,0,255,0)" class="price_linesPoly" points="'+price_linesPolyX11+','+price_linesPolyY11+' '+price_linesPolyX22+','+price_linesPolyY22+' '+price_linesPolyX33+','+price_linesPolyY33+' '+price_linesPolyX44+','+price_linesPolyY44+' '+price_linesPolyX55+','+price_linesPolyY55+' '+price_linesPolyX66+','+price_linesPolyY66+'" />\
                <text class="price_linesPoly_text" y="'+price_linesPolyYTextR+'" x="1320">Price : $'+price+'</text>\
            </g>\
            ';
            jQuery(object).prepend(price_linesHtml);
        }
        
        
        jQuery("#svgid").html( jQuery("#svgid").html());
    
}


//var valueone = GetVal('10','30','4');
jQuery(document).ready(function(e) {
    
//draw_price_lines();   
    
    
    jQuery("body").on("click",".submit",function(){
        
        var mt_a = jQuery(".s_time").val();
        var mt_b = jQuery(".intervalhoursVal").text();
        
        var mt_c = parseInt(mt_b)*parseInt(mt_a);
        mt_c = parseInt(mt_c) + 100;
        
        drawCandle('',mt_c,mt_c);
    });
    
    jQuery("body").on("click",".submit_zone",function(){
        
        var ft_a = jQuery(".f_time").val();
        var ft_b = jQuery(".intervalhoursVal").text();
        
        var ft_c = parseInt(ft_b)*parseInt(ft_a);
        ft_c = parseInt(ft_c) + 100;
        
        
        var tt_a = jQuery(".t_time").val();
        var tt_b = jQuery(".intervalhoursVal").text();
        
        var tt_c = parseInt(tt_b)*parseInt(tt_a);
        tt_c = parseInt(tt_c) + 100;
        
        drawZone('',ft_c,tt_c);
    });
    
    
    
    jQuery("body").on("click","#svgid",function(){
        //alert();
    });
    
    
    jQuery("body").on("click",".resetp",function(){
        //alert();
            jQuery("g[id=price_linesSet]").removeClass("active");
    });
});
</script>


<style>
svg{
    background-color:#fff;  
    margin:30px auto 30px;
    display:block;
}
.graph .glins{
    stroke:#ccc;
    stroke-width:1;
    stroke-dasharray: 0;
}
.graph .redlins{
    stroke:#ccc;
    stroke-width:1;
    stroke-dasharray: 0;
}
.graph .grid{
    stroke:#ccc;
    stroke-width:1;
    stroke-dasharray: 0;
}
.text{
    fill:#333333;
    font-family:Verdana, Geneva, sans-serif;
    font-size:12px;
}
.textYTitle{
    fill:#3366CC;
    font-family:Verdana, Geneva, sans-serif;
    font-size:18px;
    font-weight:bold;
}

.textXTitle{
    fill:#3366CC;
    font-family:Verdana, Geneva, sans-serif;
    font-size:18px;
    font-weight:bold;
}

.formbox {
    text-align: center;
}
.lab {
    display: inline-block;
}
.lab label {
    display: inline-block;
    width: 100%;
}
.text.hrs_title{
    font-size:10px;
}
#toll{
    display:none;
}
.dataSet:hover #toll , .dataSet.active #toll {
    display:block;
}
.tolltip {
    fill: #73a409;
    fill-opacity: 1;
    filter: drop-shadow(-2px 3px 4px rgba(0, 29, 40, 0.4));
    stroke: #fff;
    stroke-width: 2px;
}
.tooltip_text {
    fill: #fff;
    font-family: arial;
    font-size: 11px;
}
.timePoly {
    fill: #fff;
    stroke: red;
    stroke-width: 0.5;
}
.price_linesPoly {
    stroke: #73A409;
    stroke-width: 0.5;
    display:none;
}
.price_linesPoly_text{
    fill: #73A409;
    font-family: arial;
    font-size: 9px;
    display:none;
}
.timePoly_text {
    fill: #f00;
    font-family: arial;
    font-size: 10px;
}
.price_lines_hover{
    display:none;
}
#price_linesSet:hover .price_lines_hover , #price_linesSet.active .price_lines_hover{
    display:block;
}
#price_linesSet:hover .price_linesPoly , #price_linesSet.active .price_linesPoly{
    display:block;
}
#price_linesSet:hover .price_linesPoly_text , #price_linesSet.active .price_linesPoly_text{
    display:block;
}
/*---------------------------------*/







.top-headcont {
    background: #fff none repeat scroll 0 0;
    float: left;
    margin-bottom: 25px;
    padding: 19px 50px;
    position: relative;
    width: 100%;
}
.PrePC {
    cursor: pointer;
    left: 8px;
    position: absolute;
    top: 5px;
}
.nextPC {
    cursor: pointer;
    position: absolute;
    right: 8px;
    top: 5px;
}
.navigt_chart {
    color: #cbcbcb;
    font-size: 50px;
}
.chxbx {
    float: left;
    padding-top: 12px !important;
    text-align: center;
}
.chxbx label {
    float: left;
    margin-right: 15px;
    min-width: unset;
}
.chxbx input {
    float: left;
}
.Drawshowzon h2 {
    color: #ccc;
    display: none;
    float: left;
    font-size: 10px;
    margin-top: -15px !important;
    padding-top: 2px;
    text-align: center;
    width: 100%;
}
.dzacxbx label {
    float: left;
    font-size: 12px;
    height: 15px;
    margin-top: -15px;
    padding-top: 2px;
    width: 100%;
}
.overflowy{
	overflow-x:auto;
	max-width:100%;
	float:left;
	width:100%;
}



</style>


<div id="content">
	<div class="overflowy">
    <div class="top-headcont">
        <div class="navigt_chart">
        	<div class="PrePC"><i class="fa fa-step-backward pre_hide_show" aria-hidden="true"></i>
                <i class="fa fa-cog fa-spin pre_wait" style="font-size:24px;display: none;"></i>
            </div>
        	<div class="nextPC"><i class="fa fa-step-forward next_hide_show" aria-hidden="true"></i>
                <i class="fa fa-cog fa-spin next_wait" style="font-size:24px;display:none ;"></i>
            </div>
        </div>
        <div class="chxbx col-xs-12 col-md-2">
        	<label>Show Zone</label>
            <input type="checkbox">
        </div>
        <div class="chxbx col-xs-12 col-md-2">
        	<label>Show Volumn</label>
            <input type="checkbox">
        </div>
        <div class="Drawshowzon col-xs-12 col-md-8">
        	<h2>Draw Zone Area</h2>
            <div class="dzacxbx col-xs-12 col-md-2">
        	<label>From Time</label>
            <select class="form-control f_time"></select>
            </div>
            <div class="dzacxbx col-xs-12 col-md-2">
        	<label>To Time</label>
            <select class="form-control t_time"></select>
            </div>
            <div class="dzacxbx col-xs-12 col-md-3">
        	<label>From Price</label>
            <select class="form-control f_price"></select>
            </div>
            <div class="dzacxbx col-xs-12 col-md-3">
        	<label>To Price</label>
            <select class="form-control t_price"></select>
            </div>
            <div class="dzacxbx col-xs-12 col-md-2">
        	<label></label>
           	<button class="btn btn-primary submit_zone">Drow Zone</button>
            </div>
        </div>
         <div class="chxbx col-xs-12 col-md-2">
            <label>1 Minute</label>
            <input type="radio" id="minute_dur" name="duration_type" value="minute" <?php if($candle_period=='1m'){echo 'checked';} ?>>
            <i class="fa fa-cog fa-spin minute_wait" style="font-size:24px;display: none;"></i>
         </div>
         <div class="chxbx col-xs-12 col-md-2">
          <label>1 Hour</label>
          <input type="radio" name="duration_type" value="hour" <?php if($candle_period=='1h'){echo 'checked';} ?>>
          <i class="fa fa-cog fa-spin hour_wait" style="font-size:24px;display: none;"></i>
         </div>
         <div class="chxbx col-xs-12 col-md-2">
          <label>1 Day</label>
          <input type="radio" name="duration_type" value="day" <?php if($candle_period=='1d'){echo 'checked';} ?>>
          <i class="fa fa-cog fa-spin day_wait" style="font-size:24px;display: none;"></i>
         </div>
    </div>
	<svg id="svgid" width="1400" height="600" class="graph" xmlns="http://www.w3.org/2000/svg">
    
    
    
        <g id="liney" class="liney glins">
            <line x1="100" y1="510" x2="100" y2="90"/>
        </g>
        <g id="linex" class="linex glins">
            <line x1="90" y1="500" x2="1310" y2="500"/>
        </g>
        
        <g id="lineXgrid" class="gridX grid">
            <line x1="90" y1="100" x2="1310" y2="100"/>
            <line x1="90" y1="200" x2="1310" y2="200"/>
            <line x1="90" y1="300" x2="1310" y2="300"/>
            <line x1="90" y1="400" x2="1310" y2="400"/>
        </g>
        <g id="textYTitle" class="textYTitle">
            <text y="50" x="50">Price</text>
        </g>
        
    
    
    
        <g id="lineYtext" class="textY text">
            <text x="50" y="105" id="att_100">$100</text>
            <text x="50" y="205" id="att_75">$75</text>
            <text x="50" y="305" id="att_50">$50</text>
            <text x="50" y="405" id="att_25">$25</text>
            <text x="50" y="505" id="att_0">$0</text>
        </g>

        <g id="lineXtext" class="textX text">
            <text x="90" y="550">Jan</text>
            <text x="190" y="550">Feb</text>
            <text x="290" y="550">Mar</text>
            <text x="390" y="550">Apr</text>
            <text x="490" y="550">May</text>
            <text x="590" y="550">Jun</text>
            <text x="690" y="550">Jul</text>
            <text x="790" y="550">Aug</text>
            <text x="890" y="550">Sep</text>
            <text x="990" y="550">Oct</text>
            <text x="1090" y="550">Nov</text>
            <text x="1190" y="550">Dec</text>
        </g>
        
        <g id="textXTitle" class="textXTitle">
            <text x="583" y="580">Time</text>
        </g>
        
        
        
        
        
    </svg>
    <div class="width_range" style="display: none;">
        <span class="intervalhoursVal">100</span>
        <h2>Set Hours</h2>
        <input type="range" class="myRange_hours" value="40" min="1" max="48">
        <span class="hoursVal">48</span>
    </div>
    </div>
</div>
<style>

.width_range {
    float: left;
    margin-bottom: 25px;
    text-align: center;
    width: 100%;
}
.width_range h2 {
    float: left;
    font-family: arial;
    font-size: 15px;
    margin: 25px 0 15px;
    width: 100%;
}
.width_range input {
    max-width: 500px;
    width: 100%;
}
span.hoursVal {
    float: left;
    text-align: center;
    width: 100%;
}
span.intervalhoursVal {
    float: left;
    text-align: center;
    width: 100%;
}
</style>




<script>






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

}/**End of Ok*/


function get_open_time(arr){

    if(typeof arr !='undefined' && arr.length>0){
     var fullArr = [];
     for(index in arr){
     fullArr.push(arr[index].openTime);
     }
     return fullArr;
     
    }

}/**End of Ok*/






$(document).ready(function(){
    run_and_draw_Chart(dataArr);

    var arr_length =  dataArr.length;


    draw_zone_dynamic(arr_length,get_open_time(dataArr),get_index_Value(dataArr),draw_zone_arr);



});




function  draw_zone_dynamic(arr_length,time_arr,value_arr,draw_zone_arr){

  

        if(typeof draw_zone_arr !='undefined' && draw_zone_arr.length>0){

            
                    var max_val = value_arr[value_arr.length-1];
                    var min_val = value_arr[0];
                
                
                for(index in draw_zone_arr){
                    var start_date = -1;
                    var end_date = -1;
                    var start_value = -1;
                    var end_value = -1;
                    /*** ***/
                    start_date1 = draw_zone_arr[index].start_date;
                   
                    var closest1 = time_arr.reduce(function(prev, curr) {
                    return (Math.abs(curr - start_date1) < Math.abs(prev - start_date1) ? curr : prev);
                    });
                     
                    /*** Get Value***/
                    start_date = time_arr.indexOf(parseInt(closest1));
                    
                    /*** ***/
                    end_date2 =draw_zone_arr[index].end_date;
                 ;
                    var closest2 = time_arr.reduce(function(prev, curr) {
                    return (Math.abs(curr - end_date2) < Math.abs(prev - end_date2) ? curr : prev);
                    });
                    /*** Get Value***/
               

                     end_date = time_arr.indexOf(parseInt(closest2));




                     /*** ***/
                    start_value3 =draw_zone_arr[index].start_value;
                    
                    var closest3 = value_arr.reduce(function(prev, curr) {
                    return (Math.abs(curr - start_value3) < Math.abs(prev - start_value3) ? curr : prev);
                    });
                    /*** Get Value***/
                   

                     start_value = value_arr.indexOf((closest3).toString());

                    /*** ***/
                    end_value4 =draw_zone_arr[index].end_value;
                 
                    var closest4 = value_arr.reduce(function(prev, curr) {
                    return (Math.abs(curr - end_value4) < Math.abs(prev - end_value4) ? curr : prev);
                    });
                    /*** Get Value***/
                    


                     end_value = value_arr.indexOf((closest4).toString());
                     
                     if(start_date!=-1 && end_date!=-1 && start_value!=-1 && end_date!=-1){


                           
                            var intervalhoursVal = 1200 / arr_length;  
                            var ft_c = parseInt(intervalhoursVal)*parseInt(start_date);
                            ft_c = parseInt(ft_c) + 100;

                            var tt_c = parseInt(intervalhoursVal)*parseInt(end_date);
                            tt_c = parseInt(tt_c) + 100;

                                
                         start_value =get_round_index(start_value3,max_val,min_val);

                         end_value =get_round_index(end_value4,max_val,min_val);
                         var type = draw_zone_arr[index].type;

                       
                         
                        drawZone('',ft_c,tt_c,start_date,end_date,start_value,end_value,type)
                     }/** if condition**/
                }/**End of  Loop**/

        }/**End of if condition**/


        
       
        
}
 

 function get_round_index(draw_val,max_val,min_val){

    var LowValue = ((draw_val-min_val)/(max_val-min_val))*100;
    return  Math.round(LowValue);
 }

 function run_and_draw_Chart(respArr){
       
        

        var get_width_svg = 1200;
        var get_width_intvl = 100;
        var get_width_nod = 12;
            
        var hrval_c = respArr.length;         
        var intervalhoursVal = get_width_svg / hrval_c;  
        jQuery(".intervalhoursVal").text(intervalhoursVal);       
            
            
            
        
            jQuery(".drawline").remove();
            jQuery(".hrs_title").remove();
            jQuery("#lineXtext").remove();
            jQuery(".s_time").html("");
            
            
            jQuery(".f_time").html("");
                
            jQuery(".t_time").html("");
            
            
            jQuery(".dataSet").remove();
            jQuery(".lineYgrid").remove();
            
            jQuery("#ZoneSet").remove();
            
            
        
                

            if(typeof  respArr != 'undefined' && respArr.length>0){
                var sortedArr= get_index_Value(respArr);

                var max_val = sortedArr[sortedArr.length-1];
                var min_val = sortedArr[0];

                var unit_val = (max_val-min_val);


                for(indexsort in sortedArr){

                    jQuery(".f_price").append('<option value="'+indexsort+'">'+sortedArr[indexsort]+'</option>');
                    jQuery(".t_price").append('<option value="'+indexsort+'">'+sortedArr[indexsort]+'</option>');
                }


                var minm_val = 0;
                if(typeof sortedArr[0] !='undefined'){
                    $('#att_0').text('$'+sortedArr[0]);
                    minm_val = sortedArr[0];
                }else{
                    $('#att_0').text('$'+0);
                }

                var maxval = 100;
                if(typeof sortedArr[sortedArr.length-1] !='undefined'){
                $('#att_100').text('$'+sortedArr[sortedArr.length-1]);
                 maxval =sortedArr[sortedArr.length-1];
                }else{
                $('#att_100').text('$'+100);
                }


               var att_50 = ((unit_val*50)/100)+parseFloat(min_val)
               var att_25 = ((unit_val*25)/100)+parseFloat(min_val)
               var att_75 = ((unit_val*75)/100)+parseFloat(min_val)
                
                $('#att_50').text('$'+att_50.toFixed(8));
                $('#att_25').text('$'+att_25.toFixed(8));
                $('#att_75').text('$'+att_75.toFixed(8));                        



                draw_price_lines(sortedArr);
                console.log(sortedArr);

            

            for(index in respArr){
            
                var i = index;
                
                    var Xx = intervalhoursVal*i;
                    Xx  = parseInt(Xx)+100;
                
                    var textXx = Xx - 10;
                    var object_id = document.getElementById("svgid");
                    var htmlnewline = '<g di="'+i+'" id="liney" class="liney redlins drawline text"><line y2="90" x2="'+Xx+'" y1="510" x1="'+Xx+'"/></g><g di="'+i+'" id="" class="text hrs_title"><text x="'+textXx+'" y="550">'+i+'H</text><line y2="90" x2="'+Xx+'" y1="510" x1="'+Xx+'"/></g>';

                    jQuery(object_id).append(htmlnewline);

                    jQuery(".s_time").append('<option value="'+i+'">'+i+'Hr</option>');

                    jQuery(".f_time").append('<option value="'+i+'">'+i+'Hr</option>');

                    jQuery(".t_time").append('<option value="'+i+'">'+i+'Hr</option>');

                    

                    var HeighValue = 0;
                    var LowValue   = 0;
                    var OpenValue  = 0;
                    var CloseValue = 0;

                    var heign_val = sortedArr.length;
                    var min_val = sortedArr[0];
                    var max_val = sortedArr[sortedArr.length-1];

                    if(sortedArr.indexOf(respArr[index].high)){
                       
                        var draw_val = respArr[index].high;
                        var HeighValue = ((draw_val-min_val)/(max_val-min_val))*100;

                        var HeighValue = Math.round(HeighValue);

                    }

                    if(sortedArr.indexOf(respArr[index].low)){
                   

                     var draw_val = respArr[index].low;
                        var LowValue = ((draw_val-min_val)/(max_val-min_val))*100;

                        var LowValue = Math.round(LowValue);
                     
                    }

                    if(sortedArr.indexOf(respArr[index].open)){
                    
                        var draw_val = respArr[index].open;
                        var OpenValue = ((draw_val-min_val)/(max_val-min_val))*100;

                        var OpenValue = Math.round(OpenValue);

                      
                    }

                    if(sortedArr.indexOf(respArr[index].close)){
                    
                       var draw_val = respArr[index].close;
                        var CloseValue = ((draw_val-min_val)/(max_val-min_val))*100;

                        var CloseValue = Math.round(CloseValue);


                    }


                    var actual_HeighValue = respArr[index].high;
                    var actual_LowValue  = respArr[index].low;
                    var actual_OpenValue  = respArr[index].open;
                    var actual_CloseValue = respArr[index].close;
                    var actual_volume    = respArr[index].volume;
                    var openTime     = respArr[index].openTime;
                    

                    if(OpenValue < CloseValue){
                      CloseValue1 =  OpenValue;
                      OpenValue  =  CloseValue;
                      CloseValue  =CloseValue1;
                    }
                


                     drawCandle(index,Xx,Xx,index,HeighValue,LowValue,OpenValue,CloseValue,actual_HeighValue,actual_LowValue,actual_OpenValue,actual_CloseValue,actual_volume,openTime);

                }/**End of For Loop***/

            }/** End of If Statement **/
                
                

                
                
                    
    
      jQuery("#svgid").html( jQuery("#svgid").html());

            

 } /***End of Function Run***/
         jQuery("body").on("input change",".myRange_hours",function(){
        var Rangval = jQuery(this).val();
        var arr_changed= split_arr(dataArr,Rangval);
        run_and_draw_Chart(arr_changed);
        });
        
        jQuery("body").on("click",".dataSet",function(){
            jQuery(this).toggleClass("active");
        });
        jQuery("body").on("click","#price_linesSet",function(){
            jQuery(this).toggleClass("active");
        });
        



    function split_arr(dataArr,size){

        size = parseInt(size);
        if(size>dataArr.length){
         size = dataArr.length;
        }
        var full_arr = [];
        if(typeof dataArr != 'undifined' && dataArr.length >0 && size<=dataArr.length){
        for(index in dataArr){

            if(index<size){
               full_arr.push(dataArr[index]);
            }
        }


    }
        return full_arr;

    }




   function autoload_candle_stick_data(){
    
        var period = '1h';
          $.ajax({
        type:'POST',
        url:'<?php echo SURL?>admin/Candel/autoload_candle_stick_data_ajax',
        data: {period:period},
         dataType: "json",
        success:function(response){
            clear_all();
            var res_Arr = response.candlesdtickArr;
            run_and_draw_Chart(res_Arr);
            var arr_length =  res_Arr.length;
            var draw_zone_arr = response.compare_val;
            draw_zone_dynamic(arr_length,get_open_time(res_Arr),get_index_Value(res_Arr),draw_zone_arr);

              

             // setTimeout(function(){
             //        autoload_candle_stick_data();
                  
             //  }, 59000);
                     
        }
      });
   }

$(document).ready(function() {
     autoload_candle_stick_data();
    
})
  
   function clear_all(){
    jQuery("g#ZoneSet").remove();  
   }

   $(document).on('click','.PrePC',function(){

    if(typeof dataArr !='undifined' && dataArr.length>0){

           var pre_time_Stamp_obj= dataArr[0].timestampDate;
           var pre_time_Stamp =  pre_time_Stamp_obj['$date']['$numberLong'];

           $('.pre_wait').show();
           $('.pre_hide_show').hide();
           

            $.ajax({
                type:'POST',
                url:'<?php echo SURL?>admin/Candel/autoload_candle_stick_data_pre',
                data: {'pre_time':pre_time_Stamp},
                dataType: "json",
                success:function(response){
                    var res_Arr = response.candlesdtickArr;
                   
                    run_and_draw_Chart(res_Arr);

                    $('.pre_wait').hide();
                    $('.pre_hide_show').show();
                }
            });
            }
    
   });

    $(document).on('click','.nextPC',function(){

    if(typeof dataArr !='undifined' && dataArr.length>0){

           var next_time_Stamp_obj= dataArr[dataArr.length-1].timestampDate;

          var next_time_Stamp =  next_time_Stamp_obj['$date']['$numberLong'];

            $('.next_wait').show();
            $('.next_hide_show').hide();

                      $.ajax({
                type:'POST',
                url:'<?php echo SURL?>admin/Candel/autoload_candle_stick_data_next',
                data: {'next_time':next_time_Stamp},
                dataType: "json",
                success:function(response){
                    var res_Arr = response.candlesdtickArr;
                    
                    run_and_draw_Chart(res_Arr);
                     $('.next_wait').hide();
                     $('.next_hide_show').show();
                }
            });
           
    }
    
   });


$(document).ready(function() {

    $('input[type=radio][name=duration_type]').change(function() {

         var class_sh = '';
         var period   = '';
        if (this.value == 'minute') {
             class_sh ='minute_wait';
             period = '1m';
        }else if(this.value == 'hour'){
           class_sh = 'hour_wait';
           period = '1h';
        }else if(this.value == 'day'){
          class_sh = 'day_wait';
           period = '1d';
        }
         
     $('.'+class_sh).show();
       $.ajax({
        type:'POST',
        url:'<?php echo SURL?>admin/Candel/autoload_candle_stick_data_ajax',
        data: {period:period},
        dataType: "json",
        success:function(response){
          var res_Arr = response.candlesdtickArr;
          run_and_draw_Chart(res_Arr);
            var arr_length =  res_Arr.length;
            var draw_zone_arr = response.compare_val;
           draw_zone_dynamic(arr_length,get_open_time(res_Arr),get_index_Value(res_Arr),draw_zone_arr);
          $('.'+class_sh).hide();
        }
    });   /*** End of Ajax**/
        
    });/*** End of click button**/

}); 


</script>


