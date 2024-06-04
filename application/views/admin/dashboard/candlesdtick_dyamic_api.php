<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/candle_css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

    <?php
        $candlesdtickArr = $candlesdtickArr;
        $compare_val = $compare_val;
        $candle_period = $candle_period;
        $get_market_history_for_candel_api = $get_market_history_for_candel_api;
        $draw_target_zone_arr   = $draw_target_zone_arr;
        $bid_volume_arr = $bid_volume_arr;
        $ask_volume_arr =  $ask_volume_arr;
        $max_volumer   = $max_volumer;
        $unit_value = $unit_value;
        if(isset($_GET['DemandTrigger'])){
            $DemandTrigger = $_GET['DemandTrigger'];
        }else{
            $DemandTrigger =90000 ;
        }


        if(isset($_GET['SupplyTrigger'])){
            $SupplyTrigger = $_GET['SupplyTrigger'];
        }else{
            $SupplyTrigger =90000 ;
        }
    ?>

    <style>

.right_filter_box.active {
    background: #c43939 none repeat scroll 0 0;
}

.right_filter_box.active{
    transform: translateX(0);
    background: #c43939 none repeat scroll 0 0;
}

.right_filter_box {
    background: #373737;
    border-radius: 30px 0 0 30px;
    box-shadow: -4px 4px 13px 2px rgba(0, 0, 0, 0.3);
    height: 86%;
    padding: 45px 0 25px 65px;
    position: fixed;
    right: 0;
    top: 7%;
    width: 400px;
    z-index: 99999;
    transform: translateX(338px);
    transition:0.3s;
}

.rfb_openclose {
    bottom: 0;
    color: #fff;
    height: 400px;
    left: 15px;
    margin: auto;
    position: absolute;
    top: 0;
    width: 15px;
}

.rfb_openclose > p {
    float: left;
    font-size: 18px;
    font-weight: bold;
    margin: 0;
    text-align: center;
    transform: rotate(-90deg) translate(-190px, -75px);
    width: 190px;
}

.rfb_openclose > span {
    background: #fff none repeat scroll 0 0;
    border-radius: 50%;
    bottom: 0;
    box-shadow: 0 4px 2px -1px rgba(0, 0, 0, 0.4);
    color: #cb4040;
    cursor: pointer;
    font-size: 18px;
    height: 35px;
    left: -30px;
    margin: auto;
    padding-top: 4px;
    position: absolute;
    text-align: center;
    top: 0;
    width: 35px;
    transform:rotate(180deg);
    transition:0.3s;
}

.right_filter_box.active .rfb_openclose > span{
    transform:rotate(0deg);
}

.right_filter_iner {
    float: left;
    height: 100%;
    overflow-y: auto;
    padding-right: 25px;
    width: 100%;
}

.rfi_fieldset {
    background: #dd5252 none repeat scroll 0 0;
    border-radius: 10px;
    float: left;
    margin-bottom: 15px;
    padding: 15px 20px 10px;
    width: 100%;
}

.rfi_row {
    float: left;
    margin-bottom: 10px;
    position: relative;
    width: 100%;
}

.rfi_row label {
    color: #ffffff;
    float: left;
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    font-size: 14px;
    margin: 0;
    width: 100%;
}

.rfi_row .form-control, .rfi_row .form-control:focus {
    background: #fff none repeat scroll 0 0;
    border: 2px solid #fff;
    border-radius: 6px;
    color: #000;
    float: left;
    letter-spacing: 1px;
    width: 100%;
}

.rfb_checkbox {
    float: left;
    padding-left: 40px;
    position: relative;
    width: 100%;
}

.rfb_checkbox > label {
    color: #fff;
    font-size: 13px;
    margin: 0;
    padding-top: 2px;
    width: auto;
}

.rfb_checkbox > input {
    height: 25px;
    left: 0;
    margin: 0;
    opacity: 0;
    position: absolute;
    top: 0;
    width: 25px;
    z-index: 2;
}

.rfb_checkbox > span {
    color: #fff;
    font-size: 21px;
    height: 25px;
    left: 0;
    line-height: 0;
    position: absolute;
    text-align: center;
    top: 0;
    width: 25px;
    z-index: 1;
}

.rfb_checkbox > span .fa.fa-square-o {
    display: block;
}

.rfb_checkbox > input:checked + span .fa.fa-square-o {
    display: none;
}

.rfb_checkbox > input:checked + span .fa.fa-check-square-o {
    display: block;
    margin-left: 3px;
}

.rfb_checkbox > span .fa.fa-check-square-o {
    display: none;
}

/*** ______________________________________________ */

.slidecontainer {
    width: 100%;
}

.slider {
    -webkit-appearance: none;
    width: 100%;
    height: 25px;
    background: #d3d3d3;
    outline: none;
    opacity: 0.7;
    -webkit-transition: .2s;
    transition: opacity .2s;
}

.slider:hover {
    opacity: 1;
}



.slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 25px;
    height: 25px;
    background: #4CAF50;
    cursor: pointer;
}



.slider::-moz-range-thumb {
    width: 25px;
    height: 25px;
    background: #4CAF50;
    cursor: pointer;
}

/*****_________________________________________*****/

.rfi_row.supply_cls {
    width: 50%;
    padding: 0 7px;
}

</style>





<div id="content" style="margin-top: 111px">
    <span class="dddd"></span>
    <div id="toptip">sss</div>

<div class="right_filter_box">

    <div class="rfb_openclose">

        <p>TASK MANAGER</p>

        <span><i class="fa fa-chevron-right" aria-hidden="true"></i></span>

    </div>

    <div class="right_filter_iner">

        <div class="rfi_fieldset">

            <div class="rfi_row">

                <label>Pline<span class="pl">100</span></label>

                <input class="Pline" type="range" min="10" max="200" value="100" style="width:100%">

            </div>

        </div>

        <div class="rfi_fieldset">

            <div class="rfi_row">



                    <div class="slidecontainer">

                        <label>Tline<span class="tl" id="demo">7</span></label>

                        <input type="range" min="1" max="500" value="50" class="slider Tline" id="myRange">

                         

                    </div>



            </div>

        </div>

        

        <div class="rfi_fieldset">

            <div class="rfi_row">

                <label for="comment">Warning limit:</label>

                <input type="number"  id="limit_id" class=" form-control"  step="0.1" value="0.1">



            </div>

            <div class="rfi_row">

                <label for="comment">candle seconds:</label>                

                <input type="number" id="candlePeriod_id" class=" form-control"   value="3600">

            </div>

            <div class="rfi_row">

                <label for="comment">vol MA size:</label>

                <input type="number" id="sizeMAVol_id" class=" form-control" step="10"   value="10">

            </div>

            <div class="rfi_row">

                <label for="comment">Lookback:</label>

                <input type="number" id="Lookback" class=" form-control"    value="7">

            </div>

        </div>



          

        

        

        <div class="rfi_fieldset">

            <div class="rfi_row">

                <div class="rfb_checkbox">

                    <label>Enable Color Bars</label>

                    <input type="checkbox" value="" id="enableBarColors">

                    <span>

                        <i class="fa fa-square-o" aria-hidden="true"></i>

                        <i class="fa fa-check-square-o" aria-hidden="true"></i>

                    </span>

                </div>

            </div>

            <div class="rfi_row">

                <div class="rfb_checkbox">

                    <label>Use 2 Bars:</label>

                    <input type="checkbox" value="" id="use2Bars" checked="checked">

                    <span>

                        <i class="fa fa-square-o" aria-hidden="true"></i>

                        <i class="fa fa-check-square-o" aria-hidden="true"></i>

                    </span>

                </div>

            </div>

            <div class="rfi_row">

                <div class="rfb_checkbox">

                    <label>Low Vol:</label>

                    <input type="checkbox" value="" checked="checked" id="lowVol">

                    <span>

                        <i class="fa fa-square-o" aria-hidden="true"></i>

                        <i class="fa fa-check-square-o" aria-hidden="true"></i>

                    </span>

                </div>

            </div>

            <div class="rfi_row">

                <div class="rfb_checkbox">

                    <label>Climax Up:</label>

                    <input type="checkbox" value="" checked="checked" id="climaxUp">

                    <span>

                        <i class="fa fa-square-o" aria-hidden="true"></i>

                        <i class="fa fa-check-square-o" aria-hidden="true"></i>

                    </span>

                </div>

            </div>

            <div class="rfi_row">

                <div class="rfb_checkbox">

                    <label>Climax Down:</label>

                    <input type="checkbox" value="" id="climaxDown" checked="checked" >

                    <span>

                        <i class="fa fa-square-o" aria-hidden="true"></i>

                        <i class="fa fa-check-square-o" aria-hidden="true"></i>

                    </span>

                </div>

            </div>

            <div class="rfi_row">

                <div class="rfb_checkbox">

                    <label>Churn:</label>

                    <input type="checkbox" value="" checked="checked" id="churn">

                    <span>

                        <i class="fa fa-square-o" aria-hidden="true"></i>

                        <i class="fa fa-check-square-o" aria-hidden="true"></i>

                    </span>

                </div>

            </div>

            <div class="rfi_row">

                <div class="rfb_checkbox">

                    <label>Cimax Churn:</label>

                    <input type="checkbox" value="" checked="checked" id="climaxChurn">

                    <span>

                        <i class="fa fa-square-o" aria-hidden="true"></i>

                        <i class="fa fa-check-square-o" aria-hidden="true"></i>

                    </span>

                </div>

            </div>

            <div class="rfi_row">

                <div class="rfb_checkbox">

                    <label>First chart color white:</label>

                    <input type="checkbox" value="" id="chck_white">

                    <span>

                        <i class="fa fa-square-o" aria-hidden="true"></i>

                        <i class="fa fa-check-square-o" aria-hidden="true"></i>

                    </span>

                </div>

            </div>

        </div>

        

        

        <div class="rfi_fieldset">

            <div class="rfi_row">

                <label>Climax Down:</label>

                <input type="checkbox" value="" id="climaxDown" checked="checked">

                

            </div>

            <div class="rfi_row">

                <label>churn:</label>

                <input type="checkbox" value="" checked="checked" id="churn">

            </div>

            <div class="rfi_row">

                <label>Climax Churn:</label>

                <input type="checkbox" value="" checked="checked" id="climaxChurn">

            </div>

            

        </div>





        <!-- Swing point  -->



          <div class="rfi_fieldset">



            <div class="rfi_row">

                <label for="comment">Pivot Length Left Hand Side:</label>

                <input type="number" id="pvtLenL" class=" form-control" step="1"   value="5">

            </div>



           <div class="rfi_row">

                <label for="comment">Pivot Length Right Hand Side:</label>

                <input type="number" id="pvtLenR" class=" form-control" step="1"   value="3">

            </div>



            <div class="rfi_row">

                <label for="comment">Maximum Extension Length:</label>

                <input type="number" id="maxLvlLen" class=" form-control" step="1"   value="0">

            </div>

           

        

            <div class="rfi_row">

                <div class="rfb_checkbox">

                    <label>Show HH,LL,LH,HL Markers On Pivots Points:</label>

                    <input type="checkbox" value=""  id="ShowHHLL">

                    <span>

                        <i class="fa fa-square-o" aria-hidden="true"></i>

                        <i class="fa fa-check-square-o" aria-hidden="true"></i>

                    </span>

                </div>

            </div>



            <div class="rfi_row">

                <div class="rfb_checkbox">

                    <label>Wait For Candle Close Before Printing Pivot:</label>

                    <input type="checkbox" value="" checked="checked" id="WaitForClose">

                    <span>

                        <i class="fa fa-square-o" aria-hidden="true"></i>

                        <i class="fa fa-check-square-o" aria-hidden="true"></i>

                    </span>

                </div>

            </div>

            

        </div>

        <!--  End of swing point-->









         <div class="rfi_fieldset">

            <div class="rfi_row">

                <label for="comment">PercentileTrigger:</label>

                <input type="number" value="90" id="PercentileTrigger" class=" form-control">

            </div>


            <div class="rfi_row">

                <label for="comment">BarsBack:</label>

                <input type="number" value="8"  id="BarsBack" class=" form-control">

            </div>


           <div class="rfi_row supply_cls">

                <label for="comment">Current Down Percentile D:</label>

                <input type="number" value="25" id="Current_Down_Percentile" class=" form-control">

            </div>



             <div class="rfi_row supply_cls">

                <label for="comment">Current Down Percentile S:</label>

                <input type="number" value="25" id="Current_Down_Percentile_supply" class=" form-control">

            </div>



            <div class="rfi_row supply_cls">

                <label for="comment">Continuationi Down Percentile D:</label>

                <input type="number" value="20" id="Continuation_Down_Percentile" class=" form-control">

            </div>



              <div class="rfi_row supply_cls">

                <label for="comment">Continuationi Down Percentile S:</label>

                <input type="number" value="20" id="Continuation_Down_Percentile_supply" class=" form-control">

            </div>



             <div class="rfi_row supply_cls">

                <label for="comment">Current Up Percentile D:</label>

                <input type="number" value="25" id="Current_up_Percentile" class=" form-control">

            </div>



             <div class="rfi_row supply_cls">

                <label for="comment">Current Up Percentile S:</label>

                <input type="number" value="25" id="Current_up_Percentile_supply" class=" form-control">

            </div>



             <div class="rfi_row supply_cls">

                <label for="comment">Continuation Up Percentile D:</label>

                <input type="number" value="30" id="Continuation_up_Percentile" class=" form-control">

            </div>





            <div class="rfi_row supply_cls">

                <label for="comment">Continuation Up Percentile S:</label>

                <input type="number" value="30" id="Continuation_up_Percentile_supply" class=" form-control">

            </div>



             <div class="rfi_row supply_cls">

                <label for="comment">LH Percentile D:</label>

                <input type="number" value="10" id="LH_Percentile" class=" form-control">

            </div>



             <div class="rfi_row supply_cls">

                <label for="comment">LH Percentile S:</label>

                <input type="number" value="10" id="LH_Percentile_supply" class=" form-control">

            </div>



            <div class="rfi_row supply_cls">

                <label for="comment">HL Percentile D:</label>

                <input type="number" value="10" id="HL_Percentile" class=" form-control">

            </div>



            <div class="rfi_row supply_cls">

                <label for="comment">HL Percentile S:</label>

                <input type="number" value="10" id="HL_Percentile_supply" class=" form-control">

            </div>



            <!-- _______________________________  -->



             <div class="rfi_row">

                <div class="rfb_checkbox">

                    <label>Custom Base Candel:</label>

                    <input type="checkbox" value=""  id="custom_base_trigers">

                    <span>

                        <i class="fa fa-square-o" aria-hidden="true"></i>

                        <i class="fa fa-check-square-o" aria-hidden="true"></i>

                    </span>

                </div>

            </div>



            <!-- _______________________________  -->



            <div class="rfi_row ">

                <label for="comment">DemandTrigger:</label>                

                

                 <input type="number" value="90000"  id="DemandTrigger" class=" form-control">

            </div>

            <div class="rfi_row">

                <label for="comment">SupplyTrigger:</label>

                 <input type="number" value="90000"  id="SupplyTrigger" class=" form-control">

            </div>

            

        </div>



        <div class="rfi_fieldset">
            <div class="rfi_row">
                <div class="form-group">
                    <label for="comment" class="col-md-1">candel By Date Range:</label>
                    <div class="col-md-12 loadinput">
                        <input type='text' class="form-control datetime_picker " name="start_date" placeholder="Search By Start Date" value="" />
                    </div>
                     <div class="col-md-12 loadinput">
                        <button type="button" class="btn btn-success btn-block btn-lg search_candel_by_date" >Search</button>
                        <button type="button" class="btn btn-success btn-block btn-lg wait_search_candel_by_date" style="display: none;"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></button>
                    </div>
                      
                </div>
            </div>
        </div>


         <div class="rfi_fieldset">

            <div class="rfi_row">

                <div class="col-md-6 pull-left">

                    <button href="#" class="btn btn-info btn-lg Backward">

                     <span class="glyphicon glyphicon-backward back_word_sh_hide"> </span> Backward

                     <i class="fa fa-spinner fa-spin span_hd_show" style="font-size:24px;display: none;"></i>

                    </button>

                </div>





                <div class="col-md-6 pull-righ">

                <button class="btn btn-info btn-lg Forward">

                    <span class="glyphicon glyphicon-forward forward_sh_hide"></span> Forward

                    <i class="fa fa-spinner fa-spin f_span_hd_show " style="font-size:24px;display: none;"></i>

                </button>

                </div>

                

            </div>

            
        </div>



        <div class="rfi_fieldset">

             <div class="rfi_row">

                

                <div class="row">

                    <div class="col-xs-4">

                        <button type="button" class="btn btn-success btn-block btn-lg run_ajax">Run Ajax</button>

                        <button type="button" class="btn btn-success btn-block btn-lg wait_run_ajax" style="display: none;"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></button>

                    </div>

                    <div class="col-xs-8">

                        <button type="button" class="btn btn-success btn-block btn-lg save_candle_stick_detail">Save Canle Stock</button>

                        <button type="button" class="btn btn-success btn-block btn-lg wait_candle_stick_detail" style="display: none;"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></button>

                    </div>

                </div>



            </div>

        </div>

        

        

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




<div class="append_table"></div> 





<script>

        TempArray = <?php echo json_encode($candlesdtickArr); ?>;
        get_market_history_for_candel_api = <?php echo json_encode($get_market_history_for_candel_api); ?>;
        draw_target_zone_arr = <?php echo json_encode($draw_target_zone_arr); ?>;
        bid_volume_arr = <?php echo json_encode($bid_volume_arr); ?>;
        ask_volume_arr = <?php echo json_encode($ask_volume_arr); ?>;
        max_volumer = <?php echo json_encode($max_volumer); ?>;
        order_data = <?php echo json_encode($order_data); ?>;
        unit_value = <?php echo json_encode($unit_value); ?>;
        DemandTrigger_global = <?php echo json_encode($DemandTrigger); ?>;
        SupplyTrigger_global = <?php echo json_encode($SupplyTrigger); ?>;
        all_Hour_candle_volume_detail = <?php echo json_encode($all_Hour_candle_volume_detail); ?>;

        //Defined demand  percentage as global 
              demand_percentage = 90000;
              supply_percentage = 90000;

        var BGHPercent_one = (window.innerHeight/100)*28;
        var BGHPercent_two = (window.innerHeight/100)*12;
        var BarCanvas_one_height = BGHPercent_one; 
        var BarCanvas_two_height = BGHPercent_two; 



        // JavaScript Document
        var WindowOfsetX = 100;
        var WindowOfsetY = 140;
        var canvas = document.getElementById("myCanvas");
        var c = canvas.getContext("2d");
        var cHeight = (window.innerHeight  - BarCanvas_one_height - BarCanvas_two_height)*1.5;
        var cWidth = (window.innerWidth - WindowOfsetX)*3;
        var cOffse_right = 1;
        var cOffse_left = 100;
        var cOffse_top = 100;
        var cOffse_bottom = 70;
        canvas.height = cHeight + BarCanvas_one_height + BarCanvas_two_height;
        canvas.width = cWidth;

        var cFull_height = canvas.height;
        var canvasContainerHeight = cHeight - cOffse_top - cOffse_bottom;
        var canvasContainerWidth  = cWidth - cOffse_left - cOffse_right;

        var scroll=0;
        var scrollLeft = 0;

        jQuery(document).ready(function(){

            jQuery(window).scroll(function() {
                scroll = window.pageYOffset || document.documentElement.scrollTop;
            });

            jQuery(window).scroll(function() {
                scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
            });

            jQuery("body").on("mousemove","#myCanvas",function(e){
                    cnvs_getCoordinates(e);
            });

            jQuery("body").on("input change",".Pline",function(){

                var Tlength = jQuery(".Tline").val();
                var Plength = jQuery(".Pline").val();
                jQuery(".pl").text(Plength);
                jQuery(".tl").text(Tlength);
                requestAnimationFrame(randerChart);
            });

            jQuery("body").on(" change",".Tline",function(){
                var Tlength = jQuery(".Tline").val();
                var Plength = jQuery(".Pline").val();
                if(Tlength>TempArray.length){
                    Tlength = TempArray.length;
                }

                jQuery(".pl").text(Plength);
                jQuery(".tl").text(Tlength);
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
                }
                jQuery(".s_time").append('<option value="'+i+'">'+i+'Hr</option>');
            }
        }/** End of  drwTimeLine **/

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

                jQuery(".s_high").append('<option value="'+i+'">'+i+'</option>');
                jQuery(".s_low").append('<option value="'+i+'">'+i+'</option>');
                jQuery(".s_open").append('<option value="'+i+'">'+i+'</option>');
                jQuery(".s_close").append('<option value="'+i+'">'+i+'</option>');
            }

            var sortedArr= get_index_Value(TempArray);
            var max_val = sortedArr[sortedArr.length-1];
            var min_val = sortedArr[0];
            var unit_val = (max_val-min_val)/100;
            var P_divided = 4;
            var P_div_form=jQuery(".Pline").val();
            var P_cl_per = P_div_form/P_divided;
            var linespace_dvide = cHeight - cOffse_top - cOffse_bottom;
            linespace_dvide = linespace_dvide/P_divided;
            for(var i_dv = 0; i_dv <= P_divided; i_dv++){
                var mns = i_dv*P_cl_per;
                var percentage_price = mns;// you percentage here
                var at_100 = ((unit_val*percentage_price)+parseFloat(min_val)).toFixed(8);
                c.beginPath();
                c.moveTo(P_LfromX,P_LfromY-(linespace_dvide*i_dv));
                c.lineTo(P_LtoX,P_LtoY-(linespace_dvide*i_dv));
                c.strokeStyle="rgba(100,100,100,0.3)";
                c.stroke(); 
                c.font="14px Arial";
                c.fillStyle = "#f00";
                c.fillText(at_100,P_LfromX-90,P_LfromY-(linespace_dvide*i_dv)+2);
            }   

        }/*** End of drwPriceLine***/

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
            var Ask_x = B_G_1_LfromX + 15;
            var Dif_x = B_G_1_LfromX + 15;
            var Bid_x = B_G_1_LfromX + 15;
            var Ask_y = B_G_1_LtoY + 20;
            var Dif_y = Ask_y + 15;
            var Bid_y = Dif_y + 15;
            /////////////////////// Ask
            c.font="10px Arial";
            c.fillStyle = "#00f";
            c.fillText('Ask Value',Ask_x,Ask_y); 
            /////////////////////// DIF
            c.font="10px Arial";
            c.fillStyle = "#0f0";
            c.fillText('Dif Value',Dif_x,Dif_y);
            /////////////////////// Bid
            c.font="10px Arial";
            c.fillStyle ="#f00";
            c.fillText('Bid Value',Bid_x,Bid_y);  
   
        }/** End of drwBarGraph_one_top_Line ***/

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
        } /** End of drwBarGraph_two_top_Line ***/

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
        }/** End of drwBarGraph_OneTwoCombine_line ***/

        ToolArray = [];
        cd_color=0;
        cd_close_temp_val = 0;

        function drawCandle(cd_x,cd_l,cd_high,cd_low,cd_open,cd_close,A_cd_high,A_cd_low,A_cd_open,A_cd_close,A_cd_volume,A_cd_time,ask_volume,bid_volume,actual_openTime,swing_heigh_arr,swing_low_arr,heighst_heigh_swing_point_arr,array_lowest_low,calculate_hh,calculate_LL_HL,max_volume_value,single_candel_api_calculation_arr,openTime_human_readible,total_volume){

            var ToolArray_obj = {};
            var cd_time     =    cd_x;
            var cd_l        =    cd_l;
            var cd_high     =    cd_high;
            var cd_low      =    cd_low;
            var cd_open     =    cd_open;
            var cd_close    =    cd_close;

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
            c.fillStyle = "rgba(0,0,0,0.5)";
            c.fillRect(cd_l_x,cd_l_y,cd_l_w,cd_l_h);
            var cd_y = InerHeight - (PerPriceHeight*cd_open) + TopOffSet;
            var cd_y_b = InerHeight - (PerPriceHeight*cd_close) + TopOffSet;
            var cd_h  = 0 ; 
            if(cd_y == cd_y_b){
                cd_h  = 1; 
            }else{
             cd_h  = cd_y_b - cd_y ;
            }

            var cdwr = candleSize(cd_l);
            cd_w = cdwr
            var cd_x = cd_xx - (cd_w / 2);
            if(cd_open == cd_close){
                if(cd_close_temp_val > cd_open){
                    //alert("No last close is grater then new open red");
                    cd_color = "#E83646"; // red    
                }else{
                    //alert("Yes last close is less then new open green");
                    cd_color = "#0000FF"; // blue
                }

            }else{

                if(cd_close > cd_open){
                    cd_color = "#0000FF"; // blue
                }
                if(cd_close < cd_open){
                    cd_color = "#E83646"; // red    
                }

            }
            ///////////////////////////////////////
            cd_close_temp_val = cd_close;
            //////////////////////////////////////
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
            ToolArray_obj.ask_volume = ask_volume;
            ToolArray_obj.bid_volume = bid_volume;
            ToolArray.push(ToolArray_obj);

            ////////////////////////////////////////
            ///////////////////////////////////////
            /*-----------------------------------------Swing_lines---*/
            var swing_low_high = '';
            var Swing_color = 'red';
            var candel_api_status = 'normal';
            if(heighst_heigh_swing_point_arr.indexOf(actual_openTime) !=-1 || array_lowest_low.indexOf(actual_openTime) !=-1){
                 Swing_color = 'blue';
            }
            if(typeof calculate_hh !='undifined' && calculate_hh.length>0){
                for(hh_index in calculate_hh){
                if(calculate_hh[hh_index].time == actual_openTime){
                    if(calculate_hh[hh_index].type == 'LH'){
                        candel_api_status = 'LH';
                        Swing_color = 'blue';
                        demand_percentage =$('#LH_Percentile').val();
                        supply_percentage = $('#LH_Percentile_supply').val();
                    }

                    if(calculate_hh[hh_index].type == 'HH'){
                        candel_api_status = 'HH';
                        Swing_color = 'black';
                        var prevouse_indx = hh_index-1;
                        var prevouse_more = hh_index-2;
                        if(prevouse_more && prevouse_more >=0){
                                if(calculate_hh[prevouse_indx].type == calculate_hh[hh_index].type && calculate_hh[hh_index].type == calculate_hh[prevouse_more].type ){
                                demand_percentage = $('#Continuation_up_Percentile').val();
                                supply_percentage = $('#Continuation_up_Percentile_supply').val();
                                candel_api_status = 'Continuation_up';
                        }else if(calculate_hh[prevouse_indx].type == calculate_hh[hh_index].type){
                            candel_api_status = 'Current_up';
                            demand_percentage = $('#Current_up_Percentile').val();
                            supply_percentage = $('#Current_up_Percentile_supply').val();
                        }
                        }else if(prevouse_indx >=0){
                            if(calculate_hh[prevouse_indx].type == calculate_hh[hh_index].type){
                                candel_api_status = 'Current_up';
                                demand_percentage = $('#Current_up_Percentile').val();
                                supply_percentage = $('#Current_up_Percentile_supply').val();
                            }

                        }
                    }//End of  HH
                }

                }

            }//End of check of Data Exist

            if(typeof calculate_LL_HL !='undifined' && calculate_LL_HL.length>0){
                for(ll_hl_index in calculate_LL_HL){
                    if(calculate_LL_HL[ll_hl_index].time == actual_openTime){
                        if(calculate_LL_HL[ll_hl_index].type == 'HL'){
                            candel_api_status = 'HL';
                            Swing_color = 'orange';
                            demand_percentage = $('#HL_Percentile').val();
                            supply_percentage = $('#HL_Percentile_supply').val();
                        }
                        if(calculate_LL_HL[ll_hl_index].type == 'LL'){
                            candel_api_status = 'LL';
                            Swing_color = 'green';
                            var prevouse_indx = ll_hl_index-1;
                            var prevouse_more = ll_hl_index-2;
                            if(prevouse_more && prevouse_more>=0){
                            if(calculate_LL_HL[prevouse_indx].type == calculate_LL_HL[ll_hl_index].type && calculate_LL_HL[ll_hl_index].type == calculate_LL_HL[prevouse_more].type ){
                                demand_percentage = $('#Continuation_Down_Percentile').val();
                                supply_percentage = $('#Continuation_Down_Percentile_supply').val();
                                candel_api_status = 'Continuation_Down';
                            }else if(calculate_LL_HL[prevouse_indx].type == calculate_LL_HL[ll_hl_index].type){
                                demand_percentage = $('#Current_Down_Percentile').val(); 
                                supply_percentage = $('#Current_Down_Percentile_supply').val();
                                candel_api_status = 'Current_Down'; 
                            }
                            }else if(prevouse_indx && prevouse_indx>=0){
                                    if(calculate_LL_HL[prevouse_indx].type == calculate_LL_HL[ll_hl_index].type){
                                    demand_percentage = $('#Current_Down_Percentile').val();
                                    supply_percentage = $('#Current_Down_Percentile_supply').val();
                                    candel_api_status = 'Current_Down'; 
                                }
                            } 
                        }
                    }//End of if data eist
                }//End Of For Loop
            }//End of check of Data Exist
            var cande_status_and_type_object ={};
            cande_status_and_type_object['candel_api_status'] = candel_api_status;
            if(demand_percentage){
                if($('#custom_base_trigers').prop('checked')){
                    var max_volume_value_1 =  $('#DemandTrigger').val();
                    DemandTrigger_global = (max_volume_value_1/100)*demand_percentage;
                }else{
                    DemandTrigger_global = (max_volume_value/100)*demand_percentage;
                    $('#DemandTrigger').val(max_volume_value);
                }
            }

            if(supply_percentage){
                if($('#custom_base_trigers').prop('checked')){
                    var max_volume_value1 =  $('#DemandTrigger').val();
                    SupplyTrigger_global = (max_volume_value1/100)*supply_percentage;
                }else{
                    SupplyTrigger_global = (max_volume_value/100)*supply_percentage;
                    $('#SupplyTrigger').val(max_volume_value);
                }
            }

            if(swing_heigh_arr.indexOf(actual_openTime) !=-1){
                 swing_low_high = 'High'; 
            }

            if(swing_low_arr.indexOf(actual_openTime) !=-1){
                swing_low_high ='Low'
            }

            if(swing_low_high){
                var Swing_high_low  = swing_low_high;
                // var Swing_line_Scale = 10;
                var Swing_line_Scale = 3;
                var Single_line_size = canvasContainerWidth / cd_l;
                var Swing_line_Size = Swing_line_Scale*Single_line_size;
                if(Swing_high_low == 'High'){
                    var PVT_High_X = cd_l_x;                                                                                                                                   
                    var PVT_High_Y = cd_l_y;
                    var PVT_High_Xx = cd_l_x+Swing_line_Size;
                    var PVT_High_Yy = cd_l_y ;
                    c.beginPath();
                    c.moveTo(PVT_High_X,PVT_High_Y);
                    c.lineTo(PVT_High_Xx,PVT_High_Yy);
                    c.strokeStyle=Swing_color;
                    c.stroke(); 
                }   

                if(Swing_high_low == 'Low'){
                    var PVT_Low_X = cd_l_x;                                                                                                                                    
                    var PVT_Low_Y = cd_l_y+cd_l_h;
                    var PVT_Low_Xx = cd_l_x+Swing_line_Size;
                    var PVT_Low_Yy = cd_l_y+cd_l_h;
                    c.beginPath();
                    c.moveTo(PVT_Low_X,PVT_Low_Y);
                    c.lineTo(PVT_Low_Xx,PVT_Low_Yy);
                    c.strokeStyle=Swing_color;
                    c.stroke(); 
                }
            }

            /////////////////////////////////////////
            /////////////////////////////////////////
            /*-----------------------------------------DOT ON CANDLE---*/
            var DemandCandle  = 0;
            var SupplyCandle = 0;
            if(typeof single_candel_api_calculation_arr != "undefined"){
                SupplyCandle = single_candel_api_calculation_arr['SupplyCandle']; 
                DemandCandle = single_candel_api_calculation_arr['DemandCandle'];
            }
            var candle_type = 'normal';
            if(SupplyCandle ==1){
                Dot_color = '#E83646';//red
                candle_type = 'supply';
            }
            if(DemandCandle == 1){
                Dot_color = '#0000FF'; //Blue
                candle_type = 'demand';
            }
            if(cd_color == "#0000FF" && bid_volume>ask_volume){//blue
                //diverse supply
                Dot_color ='#8B0000';//darkred
                candle_type = 'diverse_supply';
            }

            if(cd_color == "#E83646" && ask_volume>bid_volume){//red
                //diverse Demand
                Dot_color= '#00008B';//dark blue
                candle_type = 'diverse_demand';
            }

            cande_status_and_type_object['candle_type'] = candle_type;
            cande_status_and_type_object['openTime_human_readible'] = openTime_human_readible;
            cande_status_and_type_object['ask_volume'] = ask_volume;
            cande_status_and_type_object['bid_volume'] = bid_volume;
            cande_status_and_type_object['total_volume'] = total_volume;
            cande_status_and_type_arr.push(cande_status_and_type_object);
            if(DemandCandle == 1 || SupplyCandle ==1){
                var Dot_Size = cd_w/4;              
                var DOT__X = cd_l_x;                                                                                                                                       
                var DOT__Y = cd_l_y;
                var centerX = DOT__X;
                var centerY = DOT__Y-(Dot_Size*2);
                var radius  = Dot_Size;
                c.beginPath();
                c.arc(centerX, centerY, radius, 0, 2 * Math.PI, true);
                c.fillStyle = Dot_color;
                c.fill();
            }
            /*-----------------------------------------*/
        } /** End of drawCandle **/

        function drawTradingPriceLine(trad_time_from,trad_time_to,trad_price_from,trad_price_to,trad_line_color){
            var trad_time_from   =  trad_time_from ;
            var trad_time_to     =  trad_time_to ;
            var trad_price_from  =  trad_price_from ;
            var trad_price_to    =  trad_price_to ;
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
            var TIMEfrom =  (PerTimeWidth*trad_time_from) + LeftOffSet;
            var PRICEfrom = InerHeight - (PerPriceHeight*trad_price_from) + TopOffSet;
            var TIMEto =  (PerTimeWidth*trad_time_to) + LeftOffSet;
            var PRICEto = InerHeight - (PerPriceHeight*trad_price_to) + TopOffSet;  
            /*-----------------------------------------Trading Price Line---*/
            //Price Line High to High
            c.beginPath();
            c.moveTo(TIMEfrom,PRICEfrom);
            c.lineTo(TIMEto,PRICEto);
            c.strokeStyle = trad_line_color;
            c.stroke(); 
            /*-----------------------------------------*/
        }//END OF drawTradingPriceLine

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
            currentCandle = (timenow-open_time) < candlePeriod*1000 ? true : false;
            deltaT = currentCandle ? (timenow - open_time) : (open_time - clsoe_time)
            deltaT = deltaT / (candlePeriod * 1000)
            //weekends have weird periods, need normalization hack
            deltaT = deltaT > 1 ? 1 : deltaT
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
        } /*** End of  find_volume***/

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
        }/** End of  find_sample_moving_average**/

        function drawCandle_bar_one(cd_x,cd_l,ask_volume,bid_volume,max_volume,total_volume,time_index,single_candel_api_calculation_arr,candle_type){
            var ReversePressure = '';
            var candel_api_color  = 'black';
            var SellPressure  = 0;
            var BuyPressure = 0;
            if(typeof single_candel_api_calculation_arr != "undefined"){
                candel_api_color = single_candel_api_calculation_arr['candel_api_color']; 
                ReversePressure = single_candel_api_calculation_arr['ReversePressure'];
                SellPressure = single_candel_api_calculation_arr['SellPressure'];
                BuyPressure  = single_candel_api_calculation_arr['BuyPressure'];
            }

            var cd_time     =    cd_x;
            var cd_l        =    cd_l;
            var val_one = (75/max_volume)*ask_volume;  //alert(val_one); 
            var val_two = (75/max_volume)*bid_volume;  //alert(val_two); 
            var value_boxies_height = 100; 
            var TopOffset = cHeight + 5 + value_boxies_height;
            var BottomOffset = BarCanvas_two_height +5;
            var cnvHeight = cFull_height - BottomOffset - TopOffset;
            //alert(cnvHeight);
            //return false;
            var cnvHeight_half = cnvHeight/2;
            var barHeight = cnvHeight*val_one/100;
            var barY = cFull_height - BarCanvas_two_height - barHeight - 5 - cnvHeight_half;
            var barHeight_two = cnvHeight*val_two/100;
            var barY_two = cFull_height - BottomOffset - cnvHeight_half ;
            //////////////////////////// Formula for calculate Ratio For X
            var InerWidth = canvasContainerWidth;
            var total_T_length = jQuery(".Tline").val();
            var PerTimeWidth = InerWidth / total_T_length;
            var LeftOffSet = cOffse_left;
            /////////////////////////////
            var cd_xx = (PerTimeWidth*cd_x) + LeftOffSet;
            var cd_y = barY;
            var cd_h  = barHeight; 
            var cd_y_two = barY_two;
            var cd_h_two  = barHeight_two ; 
            var cd_l = cd_l;
            var cdSiz = canvasContainerWidth / cd_l;
            cdSiz =  (100/100)*cdSiz; 
            var cdwr = cdSiz;
            cd_w = cdwr;
            var cd_x = cd_xx - (cd_w / 2);
            var cd_color_one = "#00f";
            var cd_color_two = "#f00";
            var sumvals = val_one+val_two;
            c.fillStyle = cd_color_two;
            c.fillRect(cd_x,cd_y_two,cd_w,cd_h_two);
            c.fillStyle = cd_color_one;
            c.fillRect(cd_x,cd_y,cd_w,cd_h);
            ///////////////////////////////////////////////
            var volume_difference = ask_volume-bid_volume;
            Convert_to_million(ask_volume);
            var a__val_Ask = ask_volume;
            var val_Ask  =  Convert_to_million(ask_volume);
            var val_diff =  Convert_to_million(volume_difference);
            var val_bid  =  Convert_to_million(bid_volume);
            var val_scl  = '';
            var Ask_x = cd_xx-15;
            var Dif_x = cd_xx-15;
            var Bid_x = cd_xx-15;
            var Vlu1_x = cd_xx-15;
            var Vlu2_x = cd_xx;
            var Vlu3_x = cd_xx;
            var Ask_y = TopOffset - value_boxies_height + 15;
            var Dif_y = Ask_y + 15;
            var Bid_y = Dif_y + 15;
            var Vlu1_y = Bid_y + 15;
            var Vlu2_y = Vlu1_y + 15;
            var Vlu3_y = Vlu2_y + 15;
            /////////////////////// BID
            c.font="10px Arial";
            c.fillStyle = "#f00";
            c.fillText(val_bid+val_scl,Bid_x,Bid_y);
            /////////////////////// DIF
            c.font="10px Arial";
            c.fillStyle = candel_api_color;
            c.fillText(val_diff+val_scl,Dif_x,Dif_y);
            /////////////////////// ASK
            c.font="10px Arial";
            c.fillStyle = "#00f";
            c.fillText(val_Ask+val_scl,Ask_x,Ask_y); 
            var sell_buy_pressure = 0;
            var  sell_buy_color = 'white';
            if(SellPressure>BuyPressure){
                if(BuyPressure){
                    sell_buy_pressure = (SellPressure/BuyPressure);
                    sell_buy_color = 'red';
                }
            }else if(BuyPressure>SellPressure){
                if(SellPressure){
                    sell_buy_pressure = (BuyPressure/SellPressure);
                    sell_buy_color = 'blue';
                }
            }
            var sell_buy_pressurmilliom  =  (parseFloat(sell_buy_pressure)).toFixed(1); 
            /////////////////////// VOLU1
            c.font="10px Arial";
            c.fillStyle = sell_buy_color;
            c.fillText(sell_buy_pressurmilliom+val_scl,Vlu1_x,Vlu1_y); 
            /////////////////////// VOLU2
            c.font="10px Arial";
            var candle_type_color = '';
            var candle_type_value = 11111;
            if(candle_type == 'supply'){
                candle_type_color = '#ff0000';//red
                candle_type_value = 11111;
            }else if(candle_type == 'demand'){
                candle_type_color = '#0000FF';//blue
                candle_type_value = 11111;
            }else{
                candle_type_value = '';
            }


            ///***
            if(candle_type_value !=''){

            var candleLength = jQuery(".Tline").val();
            var candlewr = candleSize(candleLength);
            var candle_w = candlewr;
            var zVlu2_x = Vlu2_x - (candle_w/2);

            c.fillStyle = candle_type_color;
            c.fillRect(zVlu2_x,Vlu2_y-5,candle_w,10);    
            }
            /////////////////////// VOLU3
            var vlu_3_colors = '#fff';
            if(a__val_Ask != ''){//alert(a__val_Ask);
                if(a__val_Ask > 1000){
                 vlu_3_colors = '#FFFF00';
                }
                if(a__val_Ask > 500000){
                 vlu_3_colors = '#00FFFF';
                }
                if(a__val_Ask > 1000000){
                 vlu_3_colors = '#00FF00';
                }
                if(a__val_Ask > 2000000){
                    vlu_3_colors = '#0000FF';
                }
                if(a__val_Ask > 5000000){
                    vlu_3_colors = '#FF00FF';
                }
                if(a__val_Ask > 10000000){
                    vlu_3_colors = '#FF0000';
                }
                if(a__val_Ask > 20000000){
                 vlu_3_colors = '#000000';
                }
                var candleLength__v3 = jQuery(".Tline").val();
                var candlewr__v3 = candleSize(candleLength__v3);
                var candle_w__v3 = candlewr__v3;
                var zVlu3_x = Vlu3_x - (candle_w__v3/2);
            }
            ///////////////////////////////////////////////
        } /** End of drawCandle_bar_one**/  

       $(document).ready(function(){
            draw_table_for_data();
        });

        function draw_table_for_data(){
            var  html = '';
            if(typeof total_arr !='undifined' && total_arr.length>0){
                 html += '<table class="table">';
                html += '<tbody>';
                html +='<tr>';
                html +='<td> ReversePressure <br> BuyPressure <br> SellPressure <br>DeltaPressure<br>DemandCandle <br>SupplyCandle <br>\
                CumBuyPressure <br>CumSellPressure <br>TotalVolume_color  <br>DemandTrigger</td>';
                for(idx in total_arr){
                    html +='<td>'+total_arr[idx].ReversePressure+'<br>'+total_arr[idx].BuyPressure+'<br>'+total_arr[idx].SellPressure+'<br>\
                    '+total_arr[idx].DeltaPressure+'<br>'+total_arr[idx].DemandCandle+'<br>\
                    '+total_arr[idx].SupplyCandle+'<br>'+total_arr[idx].CumBuyPressure+'<br>\
                    '+total_arr[idx].CumSellPressure+'<br>'+total_arr[idx].TotalVolume_color+'\
                    <br><br><br>'+total_arr[idx].DemandTrigger+'</td>';
                }
                html +='<tr>';  
                html += '</tbody>';
                html += '</table>';
            }
            $('.append_table').empty().append(html);
        }//End of draw_table_for_data

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
        } /** End of get_volume_arr**/

        function drawCandle_bar_two(cd_x,cd_l,better_volume_color,volume_taget,TempArray){
            var cd_time     =    cd_x;
            var cd_l        =    cd_l;
            var volume_arr = get_volume_arr(TempArray);
            var max_volume_value = volume_arr[volume_arr.length-1];
            var volume =  (volume_taget/max_volume_value)*100;
            var TopOffset = cHeight + BarCanvas_one_height +5;
            var BottomOffset = 5;
            var cnvHeight = cFull_height - BottomOffset - TopOffset  ;
            var barHeight = cnvHeight*volume/100;
            var barY = cFull_height - barHeight-5;

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
            cdSiz =  (100/100)*cdSiz;
            var cdwr = cdSiz;
            cd_w = cdwr
            var cd_x = cd_xx - (cd_w / 2);
            c.fillStyle = better_volume_color;
            c.fillRect(cd_x,cd_y,cd_w,cd_h);  
        } /** End of   drawCandle_bar_OneTwo **/

        function drawCandle_bar_OneTwo(cd_x,cd_l,better_volume_color,A_cd_volume,TempArray,resp_obj_bar_one_val_color){
            var val_two = resp_obj_bar_one_val_color.volume;
            val_two = parseFloat(val_two);
            val_two = val_two/2;
            var cd_color_two = resp_obj_bar_one_val_color.cd_color;
            //var cd_color_two = 'white';
            var cd_time     =    cd_x;
            var cd_l        =    cd_l;
            var volume_arr = get_volume_arr(TempArray);
            var max_volume_value = volume_arr[volume_arr.length-1];
            var volume2 =  (A_cd_volume/max_volume_value)*100;
            var volume2 = volume2 /2;
            var val_one =  volume2 ;
            var TopOffset = cHeight +5;
            var BottomOffset = 5;
            var cnvHeight = cFull_height - BottomOffset - TopOffset - BGHPercent_one  ;
            var barHeight = cnvHeight*val_one/100;
            var barY = cFull_height - barHeight-5;
            var barHeight_two = cnvHeight*val_two/100;
            var barY_two = cFull_height - barHeight_two-5;
            //////////////////////////// Formula for calculate Ratio For X
            var InerWidth = canvasContainerWidth;
            var total_T_length = jQuery(".Tline").val();
            var PerTimeWidth = InerWidth / total_T_length;
            var LeftOffSet = cOffse_left;
            /////////////////////////////
            var cd_xx = (PerTimeWidth*cd_x) + LeftOffSet;
            var cd_y = barY;
            var cd_h  = barHeight; 
            var cd_y_two = barY_two-cd_h;
            var cd_h_two  = barHeight_two ; 
            var cd_l = cd_l;
            var cdSiz = canvasContainerWidth / cd_l;
            // 100%
            cdSiz =  (100/100)*cdSiz;
            var cdwr = cdSiz;
            cd_w = cdwr
            var cd_x = cd_xx - (cd_w / 2);
            var cd_color_one = better_volume_color;
            var sumvals = val_one+val_two;
            c.fillStyle = cd_color_one;
            c.fillRect(cd_x,cd_y,cd_w,cd_h);
            c.fillStyle = cd_color_two;
            c.fillRect(cd_x,cd_y_two,cd_w,cd_h_two);
        }/** End of drawCandle_bar_OneTwo ***/

        function candleSize(cd_l){
         var cd_l = cd_l;
            var cdSiz = canvasContainerWidth / cd_l;
            cdSiz =  (80/100)*cdSiz
            jQuery(".dddd").text(cdSiz);
            return cdSiz;
        }/** End of candleSize**/

        function get_sell_and_buy_date_index(dte){
            var resp = false;
            if(typeof TempArray !='undefined' && TempArray.length>0){   
                for(index_dt in TempArray) {
                    var openTime_human_readible = TempArray[index_dt].openTime_human_readible;
                    if(dte == openTime_human_readible){
                        resp =  index_dt;
                    }
                }
            }
            return resp;
        }// end of get_sell_and_buy_date_index

        function get_price_line_indexis(){
            var response = false;
            var all_prices_arr = get_index_Value(TempArray);
            var Maximim_value = all_prices_arr[all_prices_arr.length-1];
            var Minimum_value=  all_prices_arr[0];
            /*-------------temp val-*/
            trad_time_from_temp =0;
            trad_time_to_temp =0;
            trad_price_from_temp =0;
            trad_price_to_temp =0;
            trad_line_color_temp =0;
            /*---------------*/                     
            if(typeof order_data !='undefined' && order_data.length>0){
                for(index_date in order_data){
                    var current_order_sell_date  = order_data[index_date].sell_date;
                    var index_for_sell_date  = get_sell_and_buy_date_index(current_order_sell_date);
                    if(index_for_sell_date){
                        trad_time_to_temp = index_for_sell_date;
                        var sell_price  = order_data[index_date].sell_price;
                        var sell_price_index  = ((sell_price-Minimum_value)/(Maximim_value-Minimum_value))*100;
                        trad_price_to_temp = sell_price_index;
                    }else{
                        var index_for_sell_date = 0;
                        var sell_price_index = 0;
                    }
                    var current_order_buy_date  = order_data[index_date].buy_date;
                    var index_for_buy_date  = get_sell_and_buy_date_index(current_order_buy_date);

                    if(index_for_buy_date){
                        trad_time_from_temp = index_for_buy_date;
                        var buy_price  = order_data[index_date].buy_price;
                        var buy_price_index  = ((buy_price-Minimum_value)/(Maximim_value-Minimum_value))*100;
                        trad_price_from_temp = buy_price_index;
                    }else{
                        var index_for_buy_date = 0;  
                        var buy_price_index = 0; 
                    }
                }
                ///////////
                ////////////
                if(trad_price_from_temp > trad_price_to_temp){
                 trad_line_color_temp ='#ff0000';  
                }else{
                    trad_line_color_temp ='#00ff00'; 
                }
                if(trad_price_from_temp == trad_price_to_temp){
                    trad_line_color_temp ='#000000';  
                }
                console.log('trad_time_from_temp  '+trad_time_from_temp+' trad_time_to_temp '+trad_time_to_temp+ ' trad_price_to_temp'+trad_price_to_temp+' trad_price_from_temp'+trad_price_from_temp);
                drawTradingPriceLine(trad_time_from_temp,trad_time_to_temp,trad_price_from_temp,trad_price_to_temp,trad_line_color_temp);
                //////////////
                ///////////
            }
        }//End of $candel_stick_arr

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
        drwBarGraph_one_top_Line(); 
        drwBarGraph_two_top_Line();
        drwBarGraph_OneTwoCombine_line();
        drwTimeLine(Tlength);
        var time = Tlength;
        var Price = Plength;
        var total_volume_arr = [];

        var drawCandle_bar_one_array = []; 

        ToolArray = [];



        cande_status_and_type_arr = [];



               total_arr = [];



            if(typeof TempArray !='undefined' && TempArray.length>0){



            var total_volume_arr  = [];

            for(barindex in TempArray){

                var response_obj  = find_volume(TempArray[barindex].openTime,TempArray[barindex].closeTime,TempArray[barindex].open,TempArray[barindex].close,TempArray[barindex].volume,TempArray,barindex);

                total_volume_arr.push(response_obj.volTarget);

            }





            var volume_ordering_array = total_volume_arr.sort(function(a, b){return a - b});

            var max_volume_value = volume_ordering_array[volume_ordering_array.length-1];

            }/** End of**/



            var sortedArryyy= get_index_Value(TempArray);



            var aA_max  = sortedArryyy[0];

            var aA_min  =  sortedArryyy[sortedArryyy.length-1];



             var all_prices_arr = get_index_Value(TempArray);

             var Maximim_value = all_prices_arr[sortedArryyy.length-1];

             var Minimum_value=  all_prices_arr[0];



            //console.log(aA_max+'<--maxvalue '+aA_min+'<---minvalue');





            var array_for_drawaone_on_color_base =[];



            var sortedArr= get_index_Value(TempArray);



                var swing_response_arr = find_swing_heighst_points(TempArray);



                var calculate_hh = swing_response_arr.calculate_hh;



                var swing_heigh_arr = swing_response_arr.array_swing_value;

              



                var heighst_heigh_swing_point_arr = swing_response_arr.heighst_heigh_swing_point;



                var swing_low_arr_response = get_swing_lowest_point(TempArray); 



                var calculate_LL_HL = swing_low_arr_response.calculate_LL_HL;









                var swing_low_arr = swing_low_arr_response.array_swing_low_value;

                var array_lowest_low = swing_low_arr_response.array_lowest_low;








        for(indexi in TempArray) {

            



                total_volume_arr.push(TempArray[indexi].volume);



                







                var HeighValue = 0;

                var LowValue   = 0;

                var OpenValue  = 0;

                var CloseValue = 0;





              









                



                  A_cd_high = TempArray[indexi].high;

                    HeighValue  = ((A_cd_high-Minimum_value)/(Maximim_value-Minimum_value))*100;

                













               

                



                    A_cd_low = TempArray[indexi].low;

                    LowValue  = ((A_cd_low-Minimum_value)/(Maximim_value-Minimum_value))*100;

                   

               



               

                    A_cd_open = TempArray[indexi].open;

                    OpenValue  = ((A_cd_open-Minimum_value)/(Maximim_value-Minimum_value))*100;

                



              

                    A_cd_close = TempArray[indexi].close;



                    CloseValue  = ( (A_cd_close-Minimum_value) /(Maximim_value-Minimum_value))*100;



                

                



              



                

                A_cd_volume = TempArray[indexi].volume;

                A_cd_time = TempArray[indexi].openTime;

                



                var actual_closeTime = TempArray[indexi].closeTime;

                var actual_openTime = TempArray[indexi].openTime;









                var cd_high  = HeighValue;

                var cd_low    = LowValue;

                var cd_open   = OpenValue;

                var cd_close  = CloseValue;







                var ask_volume  = TempArray[indexi].ask_volume;

                var bid_volume  = TempArray[indexi].bid_volume;

                var total_volume  = TempArray[indexi].total_volume;

                var openTime_human_readible = TempArray[indexi].openTime_human_readible;

                 





                var cd_x = indexi;

                var cd_l = time;



                   var single_candel_api_calculation_arr = calculate_volume_pressure(all_Hour_candle_volume_detail,TempArray[indexi].time_index);



               

              



                 total_arr.push(single_candel_api_calculation_arr);



                drawCandle(cd_x,cd_l,cd_high,cd_low,cd_open,cd_close,A_cd_high,A_cd_low,A_cd_open,A_cd_close,A_cd_volume,A_cd_time,ask_volume,bid_volume,actual_openTime,swing_heigh_arr,swing_low_arr,heighst_heigh_swing_point_arr,array_lowest_low,calculate_hh,calculate_LL_HL,TempArray[indexi].max_volume,single_candel_api_calculation_arr,openTime_human_readible,total_volume);





                /*------------------------------START // drawTradingPriceLine(trad_time_from,trad_time_to,trad_price_from,trad_price_to,trad_line_color)-------*/





            



                

            
              
             

             









                

                



                







                /*------------------------------END // drawTradingPriceLine(trad_time_from,trad_time_to,trad_price_from,trad_price_to,trad_line_color)-------*/







                var better_volume_color = better_volume(TempArray,A_cd_open,A_cd_close,A_cd_high,A_cd_low,A_cd_volume,TempArray[indexi].openTime,TempArray[indexi].closeTime,indexi);



                var resp_obj_bar_one_val_color = bar_one_volume_calculate(TempArray[indexi].openTime,TempArray[indexi].closeTime,A_cd_open,A_cd_close,A_cd_volume,TempArray,indexi,max_volume_value);



                var volume_color_two = resp_obj_bar_one_val_color.cd_color;





                var object_for_drawone_on_color_base = {};







                 

                

                if(volume_color_two == '#FF0000' && better_volume_color == '#FF0000'){



                    

                    object_for_drawone_on_color_base[actual_openTime] = volume_color_two;

                    //object_for_drawone_on_color_base['A_cd_high'] = A_cd_high;

                     array_for_drawaone_on_color_base.push(object_for_drawone_on_color_base);



                    

                }





                if(volume_color_two == '#548DD3' && better_volume_color == '#548DD3'){



                    object_for_drawone_on_color_base[actual_openTime] = volume_color_two;

                    //object_for_drawone_on_color_base['A_cd_high'] = A_cd_high;

                    

                     array_for_drawaone_on_color_base.push(object_for_drawone_on_color_base);



                    

                }



            



              



                drawCandle_bar_one(cd_x,cd_l,TempArray[indexi].ask_volume,TempArray[indexi].bid_volume,TempArray[indexi].max_volume,TempArray[indexi].total_volume,TempArray[indexi].time_index,single_candel_api_calculation_arr,TempArray[indexi].candle_type);

            

                drawCandle_bar_OneTwo(cd_x,cd_l,better_volume_color,A_cd_volume,TempArray,resp_obj_bar_one_val_color); 



        }  /***End of Temp For loop**/



        //alert(JSON.stringify(array_for_drawaone_on_color_base));



        //draw_volume_by_color(get_open_time(TempArray),array_for_drawaone_on_color_base);



        var arr_length =  TempArray.length;

        draw_zone_dynamic(arr_length,get_open_time(TempArray),get_index_Value(TempArray),draw_target_zone_arr,array_for_drawaone_on_color_base);

        //Draw price lines
        get_price_line_indexis();

        }/** End of randerChart ***/







  function draw_volume_by_color(time_arr,array_for_drawaone_on_color_base){





                    

                    for(var index_one in array_for_drawaone_on_color_base){



                        var new_obj = array_for_drawaone_on_color_base[index_one];

                        var index = parseInt(index_one)+1;



                        var new_c= array_for_drawaone_on_color_base[index];



                        third_index = '';



                        for(var c in new_c){

                             third_index = c;

                        }



                        for(b in new_obj){



                            if(new_obj[b] !=new_c[third_index]){





                                        start_date1 = b;

                                        var closest1 = time_arr.reduce(function(prev, curr) {

                                        return (Math.abs(curr - start_date1) < Math.abs(prev - start_date1) ? curr : prev);

                                        });



                                        /*** Get Value***/

                                        start_date = time_arr.indexOf(parseInt(closest1));





                                          /*** ***/

                                            end_date2 =third_index;

                                            ;

                                            var closest2 = time_arr.reduce(function(prev, curr) {

                                            return (Math.abs(curr - end_date2) < Math.abs(prev - end_date2) ? curr : prev);

                                            });

                                            /*** Get Value***/





                                            end_date = time_arr.indexOf(parseInt(closest2));









                                            /*************Draw volume  **********/



                                            var Tlength = jQuery(".Tline").val();

                                            var Plength = jQuery(".Pline").val();



                                            var per_price_Height = (canvasContainerHeight)/(Plength);

                                            var per_Time_Width = (canvasContainerWidth)/(Tlength);



                                            var LeftOffSet = cOffse_left;

                                            var TopOffSet = cOffse_top;

                                            var bottomOffSet = cOffse_bottom;







                                            var fromtop = canvasContainerHeight + cOffse_top;

                                            var fx = (per_Time_Width*start_date)+LeftOffSet;

                                            var fy = fromtop - (per_price_Height*1);



                                            var tx = (per_Time_Width*end_date)+LeftOffSet;

                                            var ty = fromtop - (per_price_Height*100);



                                            var x = fx;

                                            var y = ty;

                                            var w = tx - fx;

                                            var h = fy - ty;



                                        c.fillStyle = 'rgba(255,0,0,0.07)';

                                        c.fillRect(100,180,237,365);

                                            // c.fillStyle = 'rgba(255,0,0,0.07)';

                                            // c.fillRect(x,y,w,h);

                                            /************* End of Draw Volume ********/



                          



                            }



                        }



                    }





                 



                    

                  

  } /** End of  draw_volume_by_color**/





    function bar_one_volume_calculate(actual_openTime,actual_closeTime,A_cd_open,A_cd_close,A_cd_volume,TempArray,indexi,max_volume_value){

            var response_obj = {};

            var response_obj  = find_volume(actual_openTime,actual_closeTime,A_cd_open,A_cd_close,A_cd_volume,TempArray,indexi);

            var  volume_taget = response_obj.volTarget;

            var volume =  (volume_taget/max_volume_value)*100;

            var cd_color = response_obj.volColor;

            response_obj.volume = volume;

            response_obj.cd_color = cd_color;

            return response_obj;

    }/** End of bar_one_volume_calculate**/





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

    }/** End of get_index_Value **/



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

} /***End of  tootip**/



function SetWidthOfCanvsBox(){

    var get_cansWidth = jQuery("canvas").width();

    jQuery("#canvasbox_X").width(get_cansWidth);

    jQuery("#canvasbox_Y").width(get_cansWidth);

    jQuery("#tooltipbox").width(get_cansWidth);

} /** End of  SetWidthOfCanvsBox **/ 



SetWidthOfCanvsBox();





function SetHeightOfCanvsBox(){

    

    var get_cansHeight = jQuery("canvas").height();

    //console.log(get_cansHeight+'aaa');

    jQuery("#main_time_line").css("height",get_cansHeight+"px");

} /** End of SetHeightOfCanvsBox **/



SetHeightOfCanvsBox();











function cnvs_getCoordinates(e){



    x=e.clientX - canvas.offsetLeft -15 ;

    y=e.clientY - canvas.offsetTop-15;

    

    

    var ys = scroll+y;

    var xs = scrollLeft+x;

    document.getElementById("toptip").innerHTML="Coordinates: (" + xs + "," + ys + ")";



    jQuery("#main_price_line").css("top",ys+"px");

    jQuery("#main_time_line").css("left",xs+"px");







        var tol_top = ys - 110 -130;

        var tol_left = xs + 15;





    jQuery(".toletip").css("left",tol_left+"px");

    jQuery(".toletip").css("top",tol_top+"px");



    //jQuery("#pdgn").text('0.00'+ys);





    var dtimevar = findTimeCordinate(xs, ToolArray);

    if(dtimevar === false){

        var datime = datime;

    }else{

        var datime = dateConvert(dtimevar);

    }

    jQuery("#tdgn").text(datime);





    if(findArryVal(xs, ys, ToolArray)){

        var get_toltiphtml = findArryVal(xs, ys, ToolArray);

        ///console.log("true");

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



    //alert(JSON.stringify(sortedArr))

    var max_val = sortedArr[sortedArr.length-1];

    var min_val = sortedArr[0];

    

    var unit_val = (max_val-min_val)/100;



    var att_50 = (unit_val*per_pxl_price_percent)+parseFloat(min_val);



    jQuery("#pdgn").text(att_50.toFixed(8)+'-->'+per_pxl_price_percent);



  //jQuery("#pdgn").text(att_50.toFixed(8));

    //jQuery("#pdgn").text(per_pxl_price_percent)

}/** End of cnvs_getCoordinates**/



jQuery("body").on("click","canvas",function(){

        var yx_line_d_val = jQuery("#main_price_line .c_pdgn").text();

        var yx_line_style = jQuery("#main_price_line").attr("style");

        jQuery("#canvasbox_X").append('<div class="c_main_price_line cmpl_Red" style="'+yx_line_style+'"><div id="pdgn" class="c_pdgn">'+yx_line_d_val+'</div><span class="c_removePL c_removePL_left">x</span><span class="c_removePL">x</span></div>');

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

}/** End of dateConvert **/



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
                     <li><strong>Bid:</strong> '+array[index].bid_volume+'</li>\
                      <li><strong>Difference:</strong> '+(array[index].bid_volume-array[index].ask_volume)+'</li>\
                       <li><strong>Ask:</strong> '+array[index].ask_volume+'</li>\
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

}/** End of  findArryVal **/





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

}/** End of findTimeCordinate **/



function cnvs_clearCoordinates(){

    document.getElementById("toptip").innerHTML="";

}/*** End of cnvs_clearCoordinates**/





    function autoload_candle_stick_data(){



            var period = '1h';



            $('.wait_run_ajax').show();

            $('.run_ajax').hide();



            var previous_date = (TempArray[TempArray.length-1].openTime);
   


              $.ajax({

            type:'POST',

            url:'<?php echo SURL?>admin/candel_api/autoload_candle_stick_data_from_database_ajax',

            data: {period:period,previous_date:previous_date},

             dataType: "json",

            success:function(response){

               

               

                var res_Arr = response.candlesdtickArr;

                 //total_arr = [];

                //console.log(res_Arr);

                TempArray = res_Arr;

                get_market_history_for_candel_api = response.get_market_history_for_candel_api;

                draw_target_zone_arr = response.draw_target_zone_arr;

                bid_volume_arr = response.bid_volume_arr;

                ask_volume_arr = response.ask_volume_arr;

                max_volumer   =  response.max_volumer;

                unit_value = response.unit_value;

                all_Hour_candle_volume_detail = response.all_Hour_candle_volume_detail;

                order_data = order_data;



               requestAnimationFrame(randerChart);

                draw_table_for_data();

            

                $('.wait_run_ajax').hide();

                $('.run_ajax').show();





            



                 // setTimeout(function(){

              

                 //        autoload_candle_stick_data();

                      

                 //  }, 5000);

                         

            }

          });

    }/** End of autoload_candle_stick_data**/



$(document).on('click','.run_ajax',function(){

    autoload_candle_stick_data();

})//







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

        var climaxDownColor = '#FF0000';

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
} /** End of better_volume**/

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

		}/** End of if condition**/



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

	} /** End of dynamic_volume **/











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



          function getSum(total, num) {

                return total + num;

            }





           



        /*** Function for calculing volume pressure**/

        volume_pressure_detial_arr = [];

        function calculate_volume_pressure(all_Hour_candle_volume_detail,time_index){



            



                var total_volume_arr = all_Hour_candle_volume_detail.total_hour_volume_arr;

                var bid_volume_arr = all_Hour_candle_volume_detail.bid_hour_arr_volume;

                var ask_volume_arr = all_Hour_candle_volume_detail.ask_hour_arr_volume;

                var max_volume_hourly = all_Hour_candle_volume_detail.max_volume_hourly;





                var PercentileTrigger = $('#PercentileTrigger').val();

               



                 var DemandTrigger = DemandTrigger_global; //  $('#DemandTrigger').val();

   

                 var SupplyTrigger = SupplyTrigger_global; // $('#SupplyTrigger').val();

             

                var BarsBack = $('#BarsBack').val();



                   



                 var check_trigger = {};





                    var demand_sum_buy = 0;

                    var supply_sum_buy = 0;

                    var demand_sum_sell = 0;

                    var supply_sum_sell = 0;

                    var CumBuyPressure = 0;

                    var CumSellPressure = 0;

                    var demand_total_volume= 0;

                    var supply_total_volume = 0;

                    var first_candel_api_type = '';

                    previous_candel_api_type_global = '';

                    var previous_candel_api_type = '';

                    var total_demand_volume = 0;

                    var ReversePressure  = '';

                    var response_arr = {};

                    



                    var data_arr = {};





                    var TotalVolume = 0;

                        if(typeof ask_volume_arr[time_index] !='undifined'){

                            var TotalVolume = total_volume_arr[time_index];

                        }

                    

                    var BuyPressure = 0;

                        if(typeof ask_volume_arr[time_index] !='undifined'){



                            BuyPressure  = ask_volume_arr[time_index];

                        }

                    

                    var SellPressure = 0;

                    if(typeof bid_volume_arr[time_index] !='undifined'){

                        SellPressure = bid_volume_arr[time_index];

                    } 

                    

                    var DeltaPressure = BuyPressure - SellPressure;



                       

                    var DemandCandle = (BuyPressure > SellPressure && BuyPressure >= DemandTrigger)?1:0;

                    var SupplyCandle = (SellPressure > BuyPressure && SellPressure >= SupplyTrigger)?1:0;



                       /************************************************/   



                    var trigger_type = 'normal';

                    if(DemandCandle == 1){

                     first_candel_api_type = 'DemandCandle';

                        demand_total_volume += TotalVolume;

                    }else if(SupplyCandle){

                     supply_total_volume += TotalVolume;

                     first_candel_api_type = 'SupplyCandle';

                    }



                    /**********************/

                    if(first_candel_api_type == previous_candel_api_type_global && previous_candel_api_type_global !=''){

                            if(first_candel_api_type == 'DemandCandle'){

                            demand_total_volume += TotalVolume;

                        }

                            if(first_candel_api_type == 'SupplyCandle'){

                            supply_total_volume += TotalVolume;

                        }



                    }else

                    {



                        if(first_candel_api_type == 'DemandCandle'){

                            if(supply_total_volume>=demand_total_volume){

                                trigger_type = 'reverse';

                                demand_total_volume = 0;

                                supply_total_volume = 0;

                            }

                        }

                        if(first_candel_api_type == 'SupplyCandle'){

                            if(demand_total_volume>=supply_total_volume){

                                trigger_type = 'reverse';

                                demand_total_volume = 0;

                                supply_total_volume = 0;

                            }

                        }

                    }//End of else







                    if(DemandCandle == 1){

                        previous_candel_api_type_global = 'DemandCandle';

                    }else if(SupplyCandle){

                        previous_candel_api_type_global = 'SupplyCandle';

                    }



                       /********************************************************************/



                    if(DemandCandle==1 || ((DemandCandle == 0 && SupplyCandle == 0) && (demand_sum_buy !=0 && demand_sum_sell!=0) ) ){

                        // if(previous_candel_api_type == 'DemandCandle'){

                        //     CumBuyPressure = BuyPressure;

                        //     CumSellPressure = SellPressure;

                        // }else

                        // {

                        //     demand_sum_buy  = demand_sum_buy + BuyPressure;

                        //     CumBuyPressure = demand_sum_buy;

                        //     demand_sum_sell  = demand_sum_sell + SellPressure;

                        //     CumSellPressure = demand_sum_sell;

                        // }



                        demand_sum_buy  = demand_sum_buy + BuyPressure;

                        CumBuyPressure = demand_sum_buy;

                        demand_sum_sell  = demand_sum_sell + SellPressure;

                        CumSellPressure = demand_sum_sell;



                        supply_sum_buy = 0;

                        supply_sum_sell = 0;

                        previous_candel_api_type = 'DemandCandle';

                    }//End of check candel_api







                            

                    if(SupplyCandle==1 || ((DemandCandle == 0 && SupplyCandle == 0) && (supply_sum_buy !=0 && supply_sum_sell!=0) ) ){

                        supply_sum_buy  = supply_sum_buy + BuyPressure;

                        CumBuyPressure = supply_sum_buy;

                        supply_sum_sell  = supply_sum_sell + SellPressure;

                        CumSellPressure = supply_sum_sell;



                        // if(previous_candel_api_type == 'SupplyCandle'){

                        //     CumBuyPressure = BuyPressure;

                        //     CumSellPressure = SellPressure;

                        // }else{

                        //      supply_sum_buy  = supply_sum_buy + BuyPressure;

                        //      CumBuyPressure = supply_sum_buy;

                        //      supply_sum_sell  = supply_sum_sell + SellPressure;

                        //      CumSellPressure = supply_sum_sell;

                        // }

                        demand_sum_buy = 0;

                        demand_sum_sell = 0;

                        previous_candel_api_type = 'SupplyCandle';



                    }//Check of supply candel_api





                    var TotalVolume_color = (TotalVolume<PercentileTrigger && DeltaPressure ==1)?'blue':'red';

                    var HighVolume_color = (TotalVolume>=PercentileTrigger && DeltaPressure >=1)?'dark green':(TotalVolume >= PercentileTrigger && DeltaPressure <=0)?'purple' :'';



                    var candel_api_color = 'black';

                    if(trigger_type == 'reverse'){

                        candel_api_color = '#FFA500';//orange

                    } else if(DemandCandle == 1){ //

                        candel_api_color = '#0000FF';//blue

                    }else if(SupplyCandle == 1){

                        candel_api_color = '#FF0000';//red

                    }







                            data_arr['ReversePressure'] = trigger_type;

                            data_arr['BuyPressure'] = BuyPressure;

                            data_arr['SellPressure'] = SellPressure;

                            data_arr['DeltaPressure'] = DeltaPressure;

                            data_arr['DemandCandle'] = DemandCandle;

                            data_arr['SupplyCandle'] = SupplyCandle;



                            data_arr['CumBuyPressure'] = CumBuyPressure;

                            data_arr['CumSellPressure'] = CumSellPressure;

                            data_arr['TotalVolume_color'] = TotalVolume_color;



                            data_arr['HighVolume_color'] = HighVolume_color;

                            data_arr['candel_api_color'] = candel_api_color;

                            data_arr['DemandTrigger'] = DemandTrigger;



                           // alert(DemandTrigger);





                            volume_pressure_detial_arr['time_index'] = data_arr;



                           //alert('trigger_type-->'+trigger_type+'----BuyPressure-->'+BuyPressure+'---SellPressure-->'+SellPressure+'--DeltaPressure-->'+DeltaPressure'--DemandCandle-->'+DemandCandle+'--SupplyCandle-->'+SupplyCandle+'--CumBuyPressure-->'+CumBuyPressure+'--TotalVolume_color--->'+TotalVolume_color+'---HighVolume_color-->'+HighVolume_color+'---candel_api_color-->'+candel_api_color+'--DemandTrigger-->'+DemandTrigger);



                           







                          // volume_pressure_detial_arr.push(data_arr);



                       /********************************************************************/

               return data_arr;

                                

        } /** End of calculate_volume_pressure **/





      





        

function  draw_zone_dynamic(arr_length,time_arr,value_arr,draw_zone_arr,array_for_drawaone_on_color_base){







        if(typeof draw_zone_arr !='undefined' && draw_zone_arr.length>0){



            

                    var max_val = value_arr[value_arr.length-1];

                    var min_val = value_arr[0];

                

                

                for(index in draw_zone_arr){

                    var start_date = -1;

                    var end_date = -1;

                    var start_value = -1;

                    var end_value = -1;



                    var actual_start_value = draw_zone_arr[index].start_value;

                    var actual_end_value = draw_zone_arr[index].end_value;

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

                    start_value3 = draw_zone_arr[index].start_value;

                    

                    

                    var closest3 = value_arr.reduce(function(prev, curr) {

                    return (Math.abs(curr - start_value3) < Math.abs(prev - start_value3) ? curr : prev);

                    });

                    /*** Get Value***/

                   



                     start_value = value_arr.indexOf((closest3).toString());



                     var start_value_index = start_value;







                    /*** ***/

                    end_value4 =draw_zone_arr[index].end_value;

                 

                    var closest4 = value_arr.reduce(function(prev, curr) {

                    return (Math.abs(curr - end_value4) < Math.abs(prev - end_value4) ? curr : prev);

                    });

                    /*** Get Value***/

                    





                     end_value = value_arr.indexOf((closest4).toString());



                     var end_value_index = end_value;

                     

                     if(start_date!=-1 && end_date!=-1 && start_value!=-1 && end_date!=-1){





                           

                            var intervalhoursVal = 1200 / arr_length;  

                            var ft_c = parseInt(intervalhoursVal)*parseInt(start_date);

                            ft_c = parseInt(ft_c) + 100;



                            var tt_c = parseInt(intervalhoursVal)*parseInt(end_date);

                            tt_c = parseInt(tt_c) + 100;



                                //alert('start_value3'+start_value3+'end_value4'+end_value4);

                         start_value =get_round_index(start_value3,max_val,min_val);



                         end_value =get_round_index(end_value4,max_val,min_val);



                         var type = draw_zone_arr[index].type;





                         var draw_zone_volume_arr = draw_zone_arr[index].draw_target_zone;



                        

                       // alert('start_date'+start_date+'end_date'+end_date+'start_value'+start_value+'end_value'+end_value);

                      

                        drawZone(start_date,end_date,start_value,end_value,start_value_index,end_value_index,draw_zone_volume_arr,actual_start_value,actual_end_value,array_for_drawaone_on_color_base,time_arr,max_val,min_val);

                     }/** if condition**/

                }/**End of  Loop**/



        }/**End of if condition**/

       

}







function drawZone(From_time,To_time,From_price,To_price,start_value_index,end_value_index,draw_zone_volume_arr,actual_start_value,actual_end_value,array_for_drawaone_on_color_base,time_arr,maximum_price,minimum_price){





    var Tlength = jQuery(".Tline").val();

    var Plength = jQuery(".Pline").val();













    var per_price_Height = (canvasContainerHeight)/(Plength);

    var per_Time_Width = (canvasContainerWidth)/(Tlength);







    /////////////////









    var LeftOffSet = cOffse_left;

    var TopOffSet = cOffse_top;

    var bottomOffSet = cOffse_bottom;









    var fromtop = canvasContainerHeight + cOffse_top;



    var fx = (per_Time_Width*From_time)+LeftOffSet;

    var fy = fromtop - (per_price_Height*From_price);



    var tx = (per_Time_Width*To_time)+LeftOffSet;

    var ty = fromtop - (per_price_Height*To_price);









    var x = fx;

    var y = ty;







    var w = tx - fx;

    var h = fy - ty;









    c.fillStyle = 'rgba(255,0,0,0.07)';

    c.fillRect(x,y,w,h);



    var coin_unit_value = 1;

    if(unit_value !=0 && unit_value!=''){

         coin_unit_value = unit_value; 

    }

    

        



    var Height_scale = (100*coin_unit_value)/(maximum_price -minimum_price);

    var cnt = Height_scale;

    var From_price = parseInt(From_price);

    var To_price = parseInt(To_price);



    var response_volume_arr = get_volue_arr_for_zone(draw_zone_volume_arr,actual_start_value,actual_end_value);

    var bid_volume_arr_draw = response_volume_arr['bid_volume_arr_draw'];

    var ask_volume_arr_draw = response_volume_arr['ask_volume_arr_draw'];

     var maximum_volume  = draw_zone_volume_arr['max_volume'];



    

    var From_price_dv = parseInt(From_price)/Height_scale;

    var To_price_dv = parseInt(To_price)/Height_scale;

        

        var index = 0;

    for(var i=From_price;i<To_price;){



        //alert(i);

        i=i+Height_scale;

        var t_pp = parseInt(From_price)+parseInt(cnt);

        var f_pp = t_pp-Height_scale;

        cnt= cnt+Height_scale;



        // var vlm1 = Math.random()*100;

        // var vlm2 = Math.random()*50;

        var vlm_1 = 0;

        var vlm_2 = 0;



        if(typeof bid_volume_arr_draw[index] != 'undifined'){

             vlm_1 = (bid_volume_arr_draw[index]/maximum_volume)*100;

        }



        if(typeof ask_volume_arr_draw[index] !='undifined'){

            vlm_2 =(ask_volume_arr_draw[index]/maximum_volume)*100;

        }



        var vlm =  vlm_1+vlm_2;

        var color_1="#32CD32"; //green

        var color_2="#FF4500";//red

        drwa_zone_VolumBar(Tlength,Plength,From_time,To_time,f_pp,t_pp,vlm_1,vlm_2,color_1,color_2,Height_scale);

        index++;



    }//End Of For Loop





}









function drawZone_bk(From_time,To_time,From_price,To_price,start_value_index,end_value_index,draw_zone_volume_arr,actual_start_value,actual_end_value,array_for_drawaone_on_color_base,time_arr){



    var Tlength = jQuery(".Tline").val();

    var Plength = jQuery(".Pline").val();





    

    var per_price_Height = (canvasContainerHeight)/(Plength);

    var per_Time_Width = (canvasContainerWidth)/(Tlength);



    var LeftOffSet = cOffse_left;

    var TopOffSet = cOffse_top;

    var bottomOffSet = cOffse_bottom;







    var fromtop = canvasContainerHeight + cOffse_top;

    var fx = (per_Time_Width*From_time)+LeftOffSet;

    var fy = fromtop - (per_price_Height*From_price);



    var tx = (per_Time_Width*To_time)+LeftOffSet;

    var ty = fromtop - (per_price_Height*To_price);



    var x = fx;

    var y = ty;

    var w = tx - fx;

    var h = fy - ty;





         /************** Draw Zone By History****************/



                    //alert(JSON.stringify(array_for_drawaone_on_color_base));

                    for(var index_one in array_for_drawaone_on_color_base){



                        var new_obj = array_for_drawaone_on_color_base[index_one];

                        var index = parseInt(index_one)+1;



                        var new_c = '';



                        if(array_for_drawaone_on_color_base[index] !='undefined'){



                            var new_c= array_for_drawaone_on_color_base[index];

                        }

                        



                        third_index = '';



                        if(new_c!=''){

                            for(var mmm in new_c){

                                 third_index = mmm;

                            }

                    }



                        for(b in new_obj){ 



                            if(third_index !=''){

                            

                             if(new_obj[b] != new_c[third_index]){





                                        start_date1 = b;

                                        var closest1 = time_arr.reduce(function(prev, curr) {

                                        return (Math.abs(curr - start_date1) < Math.abs(prev - start_date1) ? curr : prev);

                                        });



                                        /*** Get Value***/

                                        start_date = time_arr.indexOf(parseInt(closest1));





                                          /*** ***/

                                            end_date2 =third_index;

                                            ;

                                            var closest2 = time_arr.reduce(function(prev, curr) {

                                            return (Math.abs(curr - end_date2) < Math.abs(prev - end_date2) ? curr : prev);

                                            });

                                            /*** Get Value***/





                                            end_date = time_arr.indexOf(parseInt(closest2));









                                            // ************Draw volume  *********



                                            var Tlength = jQuery(".Tline").val();

                                            var Plength = jQuery(".Pline").val();



                                            var per_price_Height = (canvasContainerHeight)/(Plength);

                                            var per_Time_Width = (canvasContainerWidth)/(Tlength);



                                            var LeftOffSet = cOffse_left;

                                            var TopOffSet = cOffse_top;

                                            var bottomOffSet = cOffse_bottom;







                                            var fromtop = canvasContainerHeight + cOffse_top;

                                            var fx = (per_Time_Width*start_date)+LeftOffSet;

                                            var fy = fromtop - (per_price_Height*1);



                                            var tx = (per_Time_Width*end_date)+LeftOffSet;

                                            var ty = fromtop - (per_price_Height*100);



                                            var x1 = fx;

                                            var y1 = ty;

                                            var w1 = tx - fx;

                                            var h1 = fy - ty;



                                     

                                       

                                           // c.fillStyle = 'rgba(255,0,0,0.07)';

                                           // c.fillRect(x1,y1,w1,h1);

                                            /************* End of Draw Volume ********/



                          

                                



                            } 

                        }

                         



                        }



                   }

    /**************Draw zone by history**************/





     













    c.fillStyle = 'rgba(255,0,0,0.07)';

    c.fillRect(x,y,w,h);







   





    //var total_num_z_bar = h / per_price_Height



    var cnt =0;

 

    var From_price = parseInt(From_price);

    var To_price = parseInt(To_price);







     var response_volume_arr = get_volue_arr_for_zone(draw_zone_volume_arr,actual_start_value,actual_end_value);



     var bid_volume_arr_draw = response_volume_arr['bid_volume_arr_draw'];

     var ask_volume_arr_draw = response_volume_arr['ask_volume_arr_draw'];

     var maximum_volume  = draw_zone_volume_arr['max_volume'];

   

    







        for(var i=0;i<=bid_volume_arr_draw.length;i++){



        //alert(i);

        var t_pp = parseInt(From_price)+parseInt(cnt);

        var f_pp = t_pp-1;

        cnt++;



        var vlm_1 = (bid_volume_arr_draw[i]/maximum_volume)*100;

        var vlm_2 =(ask_volume_arr_draw[i]/maximum_volume)*100;

        var vlm =  vlm_1+vlm_2; 



        var color_1="#32CD32";

        var color_2="#FF4500";







        drwa_zone_VolumBar(Tlength,Plength,From_time,To_time,f_pp,t_pp,vlm_1,vlm_2,color_1,color_2);

           

        

    }





}







    function get_volue_arr_for_zone(obj,start_price,end_price){







        var bid_arr_volume =obj.bid_arr_volume;

        var ask_arr_volume =obj.ask_arr_volume;



        var user = [];

        var data = {};

        var bid_volume_arr_draw = [];

        var ask_volume_arr_draw = [];

        for (var price in bid_arr_volume) {







            if(price>=start_price && price <=end_price){



                bid_volume_arr_draw.push(bid_arr_volume[price]);



                var ask_price = ask_arr_volume[price];



                    //=== "undefined" 

                if(ask_price !='undefined'){

                    if(ask_price==null){

                     ask_price = 0;

                    }

                    ask_volume_arr_draw.push(ask_price);

                }

            }





        }

            data['bid_volume_arr_draw'] = bid_volume_arr_draw;

            data['ask_volume_arr_draw'] = ask_volume_arr_draw;

            return data;

    }/***End of  get_volue_arr_for_zone**/







function drwa_zone_VolumBar(Tlength_Vlm,Plength_Vlm,From_time_Vlm,To_time_Vlm,From_price_Vlm,To_price_Vlm,vlm_1,vlm_2,color_1,color_2){









//console.log("Tlength_Vlm"+Tlength_Vlm+" ,Plength_Vlm"+Plength_Vlm+",From_time_Vlm,"+From_time_Vlm+",To_time_Vlm,"+To_time_Vlm+",From_price_Vlm,"+From_price_Vlm+",To_price_Vlm,"+To_price_Vlm+",vlm_1"+vlm_1+",,vlm_2"+vlm_2+",,color_1"+color_1+",,color_2"+color_2);



    var Tlength_Vlm         = Tlength_Vlm;

    var Plength_Vlm         = Plength_Vlm;

    var From_time_Vlm       = From_time_Vlm;

    var To_time_Vlm         = To_time_Vlm;

    var From_price_Vlm      = From_price_Vlm;

    var To_price_Vlm        = To_price_Vlm;

    var vlm                 = vlm;

    var color               = color;





    // console.log(Tlength_Vlm+'Tlength_Vlm');

    // console.log(Plength_Vlm+'Plength_Vlm');

    // console.log(From_time_Vlm+'From_time_Vlm');

    // console.log(To_time_Vlm+'To_time_Vlm');

    // console.log(From_price_Vlm+'From_price_Vlm');







    // console.log(To_price_Vlm+'To_price_Vlm');



    var per_price_Height_Vlm = (canvasContainerHeight)/(Plength_Vlm);

    var per_Time_Width_Vlm = (canvasContainerWidth)/(Tlength_Vlm);





    var LeftOffSet_Vlm = cOffse_left;

    var TopOffSet_Vlm = cOffse_top;

    var bottomOffSet_Vlm = cOffse_bottom;





    var fromtop_Vlm = canvasContainerHeight + TopOffSet_Vlm;



    var fx_Vlm = (per_Time_Width_Vlm*From_time_Vlm)+LeftOffSet_Vlm;

    var fy_Vlm = fromtop_Vlm - (per_price_Height_Vlm*From_price_Vlm);



    var tx_Vlm = (per_Time_Width_Vlm*To_time_Vlm)+LeftOffSet_Vlm;



    var ty_Vlm = fromtop_Vlm - (per_price_Height_Vlm*To_price_Vlm);







    console.log('************************');

    console.log('fromtop_Vlm --> '+fromtop_Vlm+' per_price_Height_Vlm-->'+per_price_Height_Vlm+'To_price_Vlm -->'+To_price_Vlm+'Plength_Vlm--->'+Plength_Vlm);

    console.log('************************');









    var x_Vlm = fx_Vlm;

    var y_Vlm = ty_Vlm;



    var w_Vlm = tx_Vlm - fx_Vlm;

    var h_Vlm = fy_Vlm - ty_Vlm;



    var width_v_1 = (w_Vlm/100)*vlm_1;

    var width_v_2 = (w_Vlm/100)*vlm_2;



    //console.log("x_Vlm="+x_Vlm+" y_Vlm="+y_Vlm+" x_Vlm="+x_Vlm+" width_v_1="+width_v_1+" h_Vlm="+h_Vlm); 



    c.fillStyle = color_1;

    c.fillRect(x_Vlm,y_Vlm,width_v_1,h_Vlm);



    var x_Vlm_2 = parseInt(x_Vlm) + parseInt(width_v_1);



    c.fillStyle = color_2;

    c.fillRect(x_Vlm_2,y_Vlm,width_v_2,h_Vlm);





}





 function get_round_index(draw_val,max_val,min_val){

    var LowValue = ((draw_val-min_val)/(max_val-min_val))*100;

    return  Math.round(LowValue);

 }/*** End of get_round_index **/





 function get_open_time(arr){



        if(typeof arr !='undefined' && arr.length>0){

         var fullArr = [];

         for(index in arr){

         fullArr.push(arr[index].openTime);

         }

         return fullArr;

         

        }



}/**End of Ok*/








$(document).on('click','.Backward',function(e){

    e.preventDefault();



    var previous_date = (TempArray[0].openTime);




    // if(previous_id !=''){

    //     gloabal_forward = previous_id;

    //  }else{

    //     previous_id = gloabal_forward;

    //  }





    var period = '1h';

    $('.back_word_sh_hide').hide();

    $('.span_hd_show').show();



     $.ajax({

                type:'POST',

                url:'<?php echo SURL ?>admin/candel_api/autoload_candle_stick_data_from_database_ajax',

                data: {period:period,previous_date:previous_date},

                dataType: "json",

                success:function(response){

                

                var res_Arr = response.candlesdtickArr;

                 //total_arr = [];

                //console.log(res_Arr);

                TempArray = res_Arr;

                get_market_history_for_candel_api = response.get_market_history_for_candel_api;

                draw_target_zone_arr = response.draw_target_zone_arr;

                bid_volume_arr = response.bid_volume_arr;

                ask_volume_arr = response.ask_volume_arr;

                max_volumer   =  response.max_volumer;

                unit_value = response.unit_value;

                all_Hour_candle_volume_detail = response.all_Hour_candle_volume_detail;


                order_data = order_data;
                

               requestAnimationFrame(randerChart);

                draw_table_for_data();

          

                    $('.back_word_sh_hide').show();

                     $('.span_hd_show').hide();

                }

            });





})





$(document).on('click','.search_candel_by_date',function(e){
    e.preventDefault();
    $('.search_candel_by_date').hide();
    $('.wait_search_candel_by_date').show();
    var forward_dt = $('.datetime_picker').val();
    var date = new Date(forward_dt); // some mock date
    var forward_date = date.getTime(); 

     var period = '1h';
     $.ajax({
                type:'POST',
                url:'<?php echo SURL ?>admin/candel_api/autoload_candle_stick_data_from_database_ajax',
                data: {period:period,forward_date:forward_date},
                dataType: "json",
                success:function(response){
                    var res_Arr = response.candlesdtickArr;
                    TempArray = res_Arr;
                    get_market_history_for_candel_api = response.get_market_history_for_candel_api;
                    draw_target_zone_arr = response.draw_target_zone_arr;
                    bid_volume_arr = response.bid_volume_arr;
                    ask_volume_arr = response.ask_volume_arr;
                    max_volumer   =  response.max_volumer;
                    unit_value = response.unit_value;
                    all_Hour_candle_volume_detail = response.all_Hour_candle_volume_detail;
                    order_data = order_data;
                    requestAnimationFrame(randerChart);
                    draw_table_for_data();
                    $('.search_candel_by_date').show();
                    $('.wait_search_candel_by_date').hide();
                }
            });
})/*** End **/

$(document).on('click','.Forward',function(e){

    e.preventDefault();

    $('.forward_sh_hide').hide();
    $('.f_span_hd_show').show();


     var forward_date = (TempArray[TempArray.length-1].openTime);

     
     var period = '1h';

     $.ajax({

                type:'POST',

                url:'<?php echo SURL ?>admin/candel_api/autoload_candle_stick_data_from_database_ajax',

                data: {period:period,forward_date:forward_date},

                dataType: "json",

                success:function(response){

                    

                    var res_Arr = response.candlesdtickArr;

                    //total_arr = [];

                    //console.log(res_Arr);

                    TempArray = res_Arr;

                    get_market_history_for_candel_api = response.get_market_history_for_candel_api;

                    draw_target_zone_arr = response.draw_target_zone_arr;

                    bid_volume_arr = response.bid_volume_arr;

                    ask_volume_arr = response.ask_volume_arr;

                    max_volumer   =  response.max_volumer;

                    unit_value = response.unit_value;

                    all_Hour_candle_volume_detail = response.all_Hour_candle_volume_detail;


                    order_data = order_data;
                  

                    requestAnimationFrame(randerChart);

                    draw_table_for_data();



                    $('.forward_sh_hide').show();

                    $('.f_span_hd_show').hide();

                }

            });
})/*** End **/



        







    function find_swing_heighst_points(arr){



        var pvtLenL = $('#pvtLenL').val();

        var pvtLenR = $('#pvtLenR').val();

        var maxLvlLen = $('#maxLvlLen').val();

        var ShowHHLL = $('#ShowHHLL').val();

        var WaitForClose = $('#WaitForClose').val();





        var number_of_look_back = pvtLenL;

        var number_of_look_forward = pvtLenR;



        if(typeof arr !='undifined' && arr.length>0){

                

         var look_back_heigh = 0;

         var look_forward_heigh = 0;

         

         var look_back_heighst = arr[0].high;



         var array_swing_value  =[];

         var array_height_heigh =[];

       

            for(index in arr){

        

        var heigh_value = arr[index].high; 

        var current_value = arr[index].high; 

        

        

        for(var i=number_of_look_back;i>0;i--){

        

            var previous_index = index-i;

          if(previous_index>=0){  

            if(arr[previous_index].high  > heigh_value){

                look_back_heigh = arr[previous_index].high;

                

            }

            

          }

          

        }/************/

        

        

    

         for(var i=1; i< number_of_look_forward; i++){

        

            var forward_index = parseInt(index)+parseInt(i);



          if(forward_index< arr.length){

          

            if(arr[forward_index].high  > heigh_value){



                look_forward_heigh = arr[forward_index].high;

            }

            

          }

          

        }/************/

        

        

        

        var heighst_point = 0;

        

        if(look_back_heigh>look_forward_heigh){

                heighst_point = look_back_heigh;

          

        }else{

            heighst_point = look_forward_heigh;

        }

        

 

       

        var height_heigh_swing = {};



        if(current_value>heighst_point){

              

             array_swing_value.push(arr[index].openTime);



             height_heigh_swing['time']   =  arr[index].openTime;

             height_heigh_swing['values'] =  current_value;



             array_height_heigh.push(height_heigh_swing);

        }

        



                    



         look_back_heigh = 0;

         look_forward_heigh = 0;

      }

     }







    var response_object = {}; 

        response_object['array_swing_value'] = array_swing_value;



    var heighst_heigh_swing_point = get_heighst_heigh_swing_point(array_height_heigh);



        response_object['heighst_heigh_swing_point'] = heighst_heigh_swing_point;



        response_object['calculate_hh'] = calculate_HH(array_height_heigh);



     return response_object;





    }//End of find_swing_points





    function calculate_HH(arr){



            var response_arr = [];



            for(index in arr){

                var response_object = {};

                if(index==0){

                    response_object['time'] = arr[0].time;

                    response_object['type'] = 'LH';

                }else{



                    var current_value = arr[index].values;

                    var prevous_value = arr[index-1].values;



                    if(current_value <= prevous_value){



                        response_object['time'] = arr[index].time;

                        response_object['type'] = 'LH';



                    }else{



                        response_object['time'] = arr[index].time;

                        response_object['type'] = 'HH';

                    }

                }



                response_arr.push(response_object);

            }



            return response_arr;

        }//End of calculate_HH







    function get_heighst_heigh_swing_point(arr){



             var heighst_heigh_swing_point_arr = [];



        if(typeof arr !='undifined' && arr.length>0){



            var response_arr = Math.max.apply(Math,arr.map(function(o){

                 return o.values;

            }));





           



            for(index in arr){



                if(arr[index].values == response_arr){



                     heighst_heigh_swing_point_arr.push(arr[index].time);



                }



            }//End of for loop

        }//End of if condition

        

        return    heighst_heigh_swing_point_arr;



    }//End of get_heighst_heigh_swing_point







   function get_swing_lowest_point(arr){









        var pvtLenL = $('#pvtLenL').val();

        var pvtLenR = $('#pvtLenR').val();

        var maxLvlLen = $('#maxLvlLen').val();

        var ShowHHLL = $('#ShowHHLL').val();

        var WaitForClose = $('#WaitForClose').val();





        var number_of_look_back = pvtLenL;

        var number_of_look_forward = pvtLenR;



        var array_swing_value = [];

        var array_lowest_low = [];

         if(typeof arr !='undifined' && arr.length>0){

                

        



          for(index in arr){

          

            var current_value = arr[index].low;

                

            

            var compare_value =arr[index].low;

            

            if(index-1 >=0){

                   compare_value = arr[index-1].low;

            }

            

            var look_back_low = compare_value;















            for(var i=number_of_look_back;i>0;i--){

            

              var previous_index = index-i;

              if(previous_index>=0){



                if(arr[previous_index].low  < compare_value){

                  look_back_low = arr[previous_index].low;

                }



              }



            }/************/







            var  look_forward_low = arr[index].low;



            var arr_lenght = parseInt(arr.length);



                 if(parseInt(index)+1 < arr_lenght){

                    var new_index = parseInt(index)+1;

                    compare_value = arr[new_index].low;

                     }



            var look_forward_low = compare_value;

 

         for(var i=1; i< number_of_look_forward; i++){

        

            var forward_index = parseInt(index)+parseInt(i);

          

          if(forward_index< arr.length){



            if(arr[forward_index].low  < compare_value){



                look_forward_low = arr[forward_index].low;

            

            }

            

          }

          

        }/************/

        

        var lowest_swing = '';

        

            if(look_forward_low <look_back_low){



              lowest_swing = look_forward_low;

            }else{

                lowest_swing = look_back_low;

            }

        

        var lowest_low_swing = {};



        if(current_value<lowest_swing){



              array_swing_value.push(arr[index].openTime);



                lowest_low_swing['time']   =  arr[index].openTime;

                lowest_low_swing['values'] =  current_value;



             array_lowest_low.push(lowest_low_swing);

        }

        



             

      }

     }//end of loop

     //////////////////

     /////////////////





    var response_object = {}; 

        response_object['array_swing_low_value'] = array_swing_value;



    var lowest_low_swing_point = get_lowest_low_swing_point(array_lowest_low);

        response_object['array_lowest_low'] =lowest_low_swing_point ;





        response_object['calculate_LL_HL'] = calculate_LL_HL(array_lowest_low);



        



     return response_object;





 }//get_swing_lowest_point





    function calculate_LL_HL(arr){



            var response_arr = [];



            for(index in arr){

                var response_object = {};

                if(index==0){

                    response_object['time'] = arr[0].time;

                    response_object['type'] = 'HL';

                }else{



                    var current_value = arr[index].values;

                    var prevous_value = arr[index-1].values;



                    if(current_value >= prevous_value){



                        response_object['time'] = arr[index].time;

                        response_object['type'] = 'HL';



                    }else{



                        response_object['time'] = arr[index].time;

                        response_object['type'] = 'LL';

                    }

                }



                response_arr.push(response_object);

            }



            return response_arr;

        }//End of calculate_LL_HL







   function get_lowest_low_swing_point(arr){



             var heighst_heigh_swing_point_arr = [];



        if(typeof arr !='undifined' && arr.length>0){



            var response_arr = Math.min.apply(Math,arr.map(function(o){

                 return o.values;

            }));





           



            for(index in arr){



                if(arr[index].values == response_arr){



                     heighst_heigh_swing_point_arr.push(arr[index].time);



                }



            }//End of for loop

        }//End of if condition

        

        return    heighst_heigh_swing_point_arr;



    }//End of get_heighst_heigh_swing_point

 

  





function Convert_to_million(labelValue) 

{   







    // Nine Zeroes for Billions

    var res = Math.abs(Number(labelValue)) >= 1.0e+9



    ? (Math.abs(Number(labelValue)) / 1.0e+9).toFixed(1) + "B"

    // Six Zeroes for Millions 

    : Math.abs(Number(labelValue)) >= 1.0e+6



    ? (Math.abs(Number(labelValue)) / 1.0e+6).toFixed(1) + "M"

    // Three Zeroes for Thousands

    : Math.abs(Number(labelValue)) >= 1.0e+3



    ? (Math.abs(Number(labelValue)) / 1.0e+3).toFixed(1)+ "K"



    : Math.abs(Number(labelValue)).toFixed(1);



    if(labelValue<0){

        res =  '-'+res;

    }



    return res;

}/** End of  Convert_to_million **/













jQuery(document).ready(function(e) {

    jQuery("body").addClass("sidebar-mini");

    jQuery("body").on("click",".rfb_openclose > span",function(){

        jQuery(".right_filter_box").toggleClass("active");

    });

});







var slider = document.getElementById("myRange");

var output = document.getElementById("demo");

output.innerHTML = slider.value;



slider.oninput = function() {

  output.innerHTML = this.value;

}



 



$(document).on('click','.save_candle_stick_detail',function(){



    $('.wait_candle_stick_detail').show();

    $('.save_candle_stick_detail').hide();



     var  cande_status_and_type_stringify = JSON.stringify(cande_status_and_type_arr);

     // alert(cande_status_and_type_arr);



        $.ajax({

            type:'POST',

            url:'<?php echo SURL?>admin/candel_api/save_candel_api_ajax',

            data: {cande_status_and_type_stringify:cande_status_and_type_stringify},

             //dataType: "json",

            success:function(response){

        

                $('.wait_candle_stick_detail').hide();

                $('.save_candle_stick_detail').show();



         

            }

          });



})

    




</script>



    <script type="text/javascript">
      
            $(function () {
                $('.datetime_picker').datetimepicker();
            });
      

    </script>

<?php 







    if(isset($_GET['print'])){

       echo '<pre>';

        print_r($draw_target_zone_arr);

        

       // print_r($total_volume_arr);

        exit; 

    }

        

?>





