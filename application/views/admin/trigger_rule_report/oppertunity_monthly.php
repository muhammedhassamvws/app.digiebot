<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php  echo SURL ?>assets/dist/jquery-asPieProgress.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<style>
.Input_text_s {
float: left;
width: 100%;
position: relative;
}
.Input_text_s > label {
float: left;
width: 100%;
color: #000;
font-size: 14px;
}
.multiselect-native-select .btn-group {
float: left;
width: 100% !important;
}
.multiselect-native-select .btn-group button {
background: #fff;
border: 1px solid #ccc;
border-radius: 5px !important;
}
.Input_text_s > i {
position: absolute;
right: 8px;
bottom: 4px !important;
height: 20px;
top: auto;
}
.ax_2, .ax_3, .ax_4, .ax_5 {
padding-bottom: 35px !important;
}
.col-radio {
float: left;
width: 100%;
position: relative;
padding-left: 30px;
height: 30px;
}
.col-radio span {
position: absolute;
left: 0;
width: 30px;
height: 30px;
top: 0;
font-size: 23px;
line-height: 0;
}
.col-radio input[type="radio"] {
position: absolute;
left: 0;
opacity: 0;
}
.col-radio input[type="radio"]:checked + span i.fa.fa-dot-circle-o {
display: block;
color: #72af46;
}
.col-radio input[type="radio"]:checked + span i.fa.fa-circle-o {
display: none;
}
.col-radio span i.fa.fa-dot-circle-o {
display: none;
}
.col-radio label {
color: #000;
font-size: 15px;
padding-top: 1px;
}
.Input_text_btn > a > i, .Input_text_btn > button > i {
margin-right: 10px;
}
.coin_symbol {
}
.coin_symbol {
color: #fff;
font-weight: bold;
font-size: 14px;
float: left;
width: 100%;
padding: 12px 20px;
background: #31708f;
border-radius: 7px 7px 0 0;
margin-top: 25px;
}
.coin_symbol:first-child {
margin-top: 0;
}
table.table.table-stripped {
border: 1px solid #2d4c5a;
}
table.table.table-stripped tr.theadd {
background: #ccc;
color: #000;
}
table.table.table-stripped tr.theadd td {
border: 1px solid #2d4c5a;
font-weight: bold;
font-size: 13px;
}
table.table.table-stripped tr td {
border: 1px solid #2d4c5a;
vertical-align: middle;
}
table.table.table-stripped tr td.heading {
background: #ccc;
color: #000;
font-size: 13px;
font-weight: bold;
}
table.table.table-stripped tr:hover {
background: rgba(0,0,0,0.04);
}
table.table.table-stripped tr.theadd:hover {
background: rgba(204,204,204,1);
}
tr.coin_symbol td {
border: none !important;
}
table.table.table-stripped tr td .table-stripped-column tr td {
border: none;
padding-bottom: 0;
padding-top: 15px;
background: #ccc;
color: black;
}
.modal-dialog {
overflow-y: initial !important
}
.Opp {
height: 550px;
padding-left: 10px;
overflow-y: auto;
overflow-x: hidden;
}
.totalAvg {
padding-top: 44px;
}
.Input_text_btn {
padding: 25px 0 0;
}
</style>

<style>
/* New CSS Classes for Labels and Boxes */
.label-box-pending{
display:inline-block;
width: 22px;
float:left;
height: 22px;
border-radius: 0.7rem;
background: #f0ad4e;
}
.label-box-approved{
display:inline-block;
width: 22px;
float:left;
height: 22px;
border-radius: 0.7rem;
background: #c3e6cb;
}
.label-box-rejected{
display:inline-block;
width: 22px;
float:left;
height: 22px;
border-radius: 0.7rem;
background: #d9534f;
}
.label-box-requested{
display:inline-block;
width: 22px;
float:left;
height: 22px;
border-radius: 0.7rem;
background: #5bc0de;
}
.status-box-pending{
color: white !important;
text-align:center;
background-color:#f0ad4e;
}
.status-box-approved{
color: white !important;
text-align:center;
background-color:#72af46;
}
.status-box-rejected{
color: white !important;
text-align:center;
background-color:#d9534f;
}
.status-box-requested{
color: white !important;
text-align:center;
background-color:#5bc0de;
}
.table_align_head{
text-align: left !important;
}
.badge {
background-color: black!important;
}
</style>
<div id="content">
<h1 class="content-heading bg-white border-bottom">Reports</h1>
<div class="innerAll bg-white border-bottom">
<div class="pull-right" style="padding-right: 12px; padding-top: 8px;">
  <div class=" pull-right alert alert-warning" style=" margin-top: -10px; background: #5c678a;color: white;"> <?php echo date("F j, Y, g:i a").'&nbsp;&nbsp;  <b>'.date_default_timezone_get().' (GMT + 0)'.'<b />' ?></div>     

</div>
<ul class="menubar">
  <li class=""><a href="<?php echo SURL; ?>/admin/trigger_rule_reports/oppertunity_month">Reports</a></li>
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
<?php $filter_data = $this->session->userdata('filter_order_data');?>
<div class="widget widget-inverse">
  <div class="widget-body">
    <form method="POST" action="<?php echo SURL; ?>admin/trigger_rule_reports/oppertunity_month">
      <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 ax_1">
        <div class="col-xs-12 col-sm-12 col-md-3 ax_2">
          <div class="Input_text_s">
            <label>Filter Coin: </label>
            <select id="filter_by_coin" multiple="multiple" name="filter_by_coin[]" type="text" class=" filter_by_name_margin_bottom_sm">
              <?php foreach($coins as $coinRow){  ?>      
              <option value="<?php echo $coinRow['symbol'] ?>" <?php if (in_array($coinRow['coin'], $filter_session['filter_by_coin'])) {?> selected <?php }?>><?php echo $coinRow['symbol'] ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 ax_3">
          <div class="Input_text_s">
            <label>Filter Mode: </label>
            <select id="filter_by_mode" name="filter_by_mode" type="text" class="form-control filter_by_name_margin_bottom_sm">
              <option value="live"<?=(($filter_session['filter_by_mode'] == "live") ? "selected" : "")?>>Live</option>
              <option value="test_live"<?=(($filter_session['filter_by_mode'] == "test_live") ? "selected" : "")?>>Test</option>
            </select>
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 ax_3">
          <div class="Input_text_s">
            <label>Exchange: </label>
            <select id="exchange" name="exchange" type="text" class="form-control filter_by_name_margin_bottom_sm">
              <option value="binance"<?=(($filter_session['exchange'] == "binance") ? "selected" : "")?>>Binance</option>
              <option value="bam"<?=(($filter_session['exchange'] == "bam") ? "selected" : "")?>>Bam</option>
              <option value="kraken"<?=(($filter_session['exchange'] == "kraken") ? "selected" : "")?>>kraken</option>
            </select>
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3  ax_8 filter_by_month"  style=" min-height: 60px;">
            <label>Filter Month: </label>
            <select name="filter_by_month" class="form-control">
              <option>Select</option>
              <option value="01">January</option>
              <option value="02">February</option>
              <option value="03">March</option>
              <option value="04">April</option>
              <option value="05">May</option>
              <option value="06">June</option>
              <option value="07">July</option>
              <option value="08">August</option>
              <option value="09">September</option>
              <option value="10">October</option>
              <option value="11">November</option>
              <option value="12">December</option>
            </select>
        </div> 


       
        <div class="col-xs-12 col-sm-12 col-md-3 ax_10">
          <div class="Input_text_btn">
            <label></label>
            <button id="submit-form" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
            <a href="<?php echo SURL; ?>admin/trigger_rule_reports/csv_export_oppertunity_month"  class="btn btn-info">Export To CSV File</a>
            </span>
           
            <!-- <span style="float:right;"><a href="<?php //echo SURL; ?>admin/reports/csv_export_trades" class="btn btn-info">Export To CSV File</a></span> --> 
          </div>
        </div>
      </div>
      </div>
    </form>
  </div>
</div>
<!-- Widget -->
<style>
/* STYLES FOR PROGRESSBARS */

.progress-radial, .progress-radial * {
-webkit-box-sizing: content-box;
-moz-box-sizing: content-box;
box-sizing: content-box;
}

/* -------------------------------------
* Bar container
* ------------------------------------- */
.progress-radial {
float: left;
margin-right: 4%;
position: relative;
width: 55px;
border-radius: 50%;
}
.progress-radial:first-child {
margin-left: 4%;
}
/* -------------------------------------
* Optional centered circle w/text
* ------------------------------------- */
.progress-radial .overlay {
position: absolute;
width: 80%;
background-color: #f0f0f0;
border-radius: 50%;
font-size: 14px;
top:50%;
left:50%;
-webkit-transform: translate(-50%, -50%);
-ms-transform: translate(-50%, -50%);
transform: translate(-50%, -50%);
}

.progress-radial .overlay p{
position: absolute;
line-height: 40px;
text-align: center;
width: 100%;
top:50%;
margin-top: -20px;
}

/* -------------------------------------
* Mixin for progress-% class
* ------------------------------------- */
.mypai_prog {
display: inline-block;
padding: 2px;
}
.tdmypi{
padding:2px;
text-align:center;
}

div.pie_progress__label {
position: absolute;
top: 20px;
left: 8px;
}

.pie_progress {
position: relative;
width: 60px;
text-align: center;
margin-left: 39px;
}


.circle{
            position: relative;
        }
        .circle strong {
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: 2222;
            transform: translate(-50%, -50%);
            font-size: 15px;
            color: black;
        }
</style>
<script>

$(".setsize").each(function() {
    $(this).height($(this).width());
});
$(window).on('resize', function(){
$(".setsize").each(function() {
    $(this).height($(this).width());
});
});

</script> 
<div class="widget widget-inverse">
  <div class="widget-body padding-bottom-none">
    <table class=" table table-bordered">
      <tr class="theadd">
        <th>Coin</th>
        <th>Total Oppertunity</th>
        <th>Compeletion %</th> 
        <th>Sold</th>
        <th>Open/LTH</th>
        <th>Other Status</th>
        <th>Average Sold</th>
        <th>Average Open/lth</th>
        <th>BTC Invest</th>
        <th>Buy Comission BNB</th>
        <th>Sell Comission BNB</th>
        <th>Buy Comission QTY</th>
        <th>Sell Comission QTy</th>
        <th>BTC gain</th>
        <th>BTC Profit</th>
        <th>Last Modified</th>
        <th>Last update time</th>
      </tr>
    <?php
  foreach ($final_array as $key => $value) { 
    if($value['coin'] =='NCASHBTC'){
      $coinImage  =  'ncashhhhhhh.png';
  }elseif($value['coin'] =='ETHBTC'){
    $coinImage = 'ethereum-black-symbol-chrystal-vector-20393411.jpg';
  }else if($value['coin'] =='TRXBTC'){
      $coinImage  =  'aaaaaw.jpg'; 
  }else if($value['coin'] =='EOSBTC'){
    $coinImage  =  'EOS.jpg';
  }else if($value['coin'] =='POEBTC'){
    $coinImage  =  'original.jpg';
  }else if($value['coin'] =='NEOBTC'){
    $coinImage  =  'NEO.jpg';
  }else if($value['coin'] =='ETCBTC'){
    $coinImage  =  'etc.jpg';
  }else if($value['coin'] =='XRPBTC'){
    $coinImage  =  'ripple.png';
  }else if($value['coin'] =='XEMBTC'){
    $coinImage  =  'nem.png';
  }else if($value['coin'] =='XLMBTC'){
    $coinImage  =  'xlm.png';
  }else if($value['coin'] =='QTUMBTC'){
    $coinImage  =  'QTUMBTC.jpg';
  }else if($value['coin'] =='ZENBTC'){
    $coinImage  =  'ZENBTC.png';
  }elseif($value['coin'] == 'NEOUSDT'){
    $coinImage = 'neousdt.png';
  }elseif($value['coin'] == 'BTCUSDT'){
    $coinImage = 'btc.png';
  }elseif($value['coin'] == 'XRPUSDT'){
    $coinImage  =  'ripple.png';
  }elseif($value['coin'] == 'QTUMUSDT'){      
    $coinImage  =  'QTUMBTC.jpg';
  }elseif($value['coin'] == 'ADABTC'){              
    $coinImage = 'adabtc.png';
  }elseif($value['coin'] == 'LINKBTC'){              
    $coinImage = 'linkbtc.png';
  }elseif($value['coin'] == 'XMRBTC'){              
    $coinImage = 'xmrbtc.png';
  }elseif($value['coin'] == 'DASHBTC'){              
    $coinImage = 'dashbtc.jpg';
  }elseif($value['coin'] == 'LTCUSDT'){              
    $coinImage = 'ltcusdt.png';
  }
  
        $last_modified_time = $value['last_modified_time']->toDateTime()->format("Y-m-d H:i:s");
        $time_zone = date_default_timezone_get();
        $completion_total = $value['open_lth']+ $value['other_status'] + $value['sold'];
        if($value['sold'] == 0){
            $compeletion = 0;
          }elseif( ($value['open_lth'] + $value['other_status']) == 0)
          {
            $compeletion = 100;
          }
          else{
            $compeletion = ($value['sold'] / $completion_total )*100;
          }
        ?>
    <tr style="text-align:center;">
    <td><img class="img img-circle" src="https://admin.digiebot.com/assets/coin_logo/thumbs/<?php echo $coinImage ; ?>" data-toggle="tooltip" data-placement="top" alt="<?php echo $value['coin']; ?>" title="<?php echo $value['coin'];?>"></td>
    <td><?php echo $value['total_oppertunities']; ?></td>
   
    <?php if(number_format($compeletion, 2) == 0): ?>
      <td> <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width:100%;border-radius:3px;color:black">
              <?php echo number_format($compeletion, 2)."%";?>
                </div></td>
        <?php endif; ?>
        <?php if(number_format($compeletion, 2) != 0): ?>  
            <td><div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="10" style="width: <?php echo $compeletion;?>%;border-radius:3px;color:black">
           <?php echo number_format($compeletion, 2)."%";?>
           </div></td>
       <?php endif; ?>

    <td><?php echo $value['sold'];?></td>
    <td><?php echo $value['open_lth']; ?></td> 
    <td><?php echo $value['other_status'];?></td> 
    <td>
      <?php    $avg_sold   = number_format($value['avg_sold'], 2); ?>
    <?php if($avg_sold >=0){ ?>
      <div class="circle" id="circle-a" data-value="<?php echo $avg_sold/5;?>" data-fill="{
                    &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">
                    <strong><span title="<?php echo $value['5_min_value']; ?>"><?php echo number_format($avg_sold, 2);?>%</span></strong>
      </div> <?php } elseif($avg_sold < 0){ ?>
        <div class="circle" id="circle-a" data-value="<?php echo $avg_sold/-5;?>" data-fill="{
                    &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                    <strong><span title="<?php echo $value['5_min_value']; ?>"><?php echo number_format($avg_sold, 2);?>%</span></strong>
        </div><?php } ?>
    </td>

    <td>
      <?php    $avg_open_lth   = number_format($value['avg_open_lth'], 2); ?>
    <?php if($avg_open_lth >=0){ ?>
      <div class="circle" id="circle-a" data-value="<?php echo $avg_open_lth/4;?>" data-fill="{
                    &quot;color&quot;: &quot;rgba(31, 183, 79) &quot;}">
                    <strong><span title="<?php echo $value['5_min_value']; ?>"><?php echo number_format($avg_open_lth, 2);?>%</span></strong>
      </div> <?php } elseif($avg_open_lth < 0){ ?>
        <div class="circle" id="circle-a" data-value="<?php echo $avg_open_lth/-4;?>" data-fill="{
                    &quot;color&quot;: &quot;rgba(237, 7, 49) &quot;}">
                    <strong><span title="<?php echo $value['5_min_value']; ?>"><?php echo number_format($avg_open_lth, 2);?>%</span></strong>
        </div><?php } ?>
    </td>

    <td><?php if(isset($value['total_investment'])){echo number_format($value['total_investment'],9);}?></td>
    <td><?php if(isset($value['buy_comission'])){echo number_format($value['buy_comission'],9);}?></td>
    <td><?php if(isset($value['sell_comission'])){echo number_format($value['sell_comission'],9);}?></td>
    <td><?php if(isset($value['buy_comission_BNB'])){echo number_format($value['buy_comission_BNB'],9);} ?></td>
    <td><?php if(isset($value['sell_comission_BNB'])){echo number_format($value['sell_comission_BNB'],9);}?></td>
    <td><?php if(isset($value['total_gain'])){echo number_format($value['total_gain'],9);}?></td>
    <td><?php if(isset($value['total_profit'])){echo number_format($value['total_profit'],9);}?></td>
    <td><?php
        $this->load->helper('common_helper');
        $last_time_ago = time_elapsed_string($last_modified_time , $time_zone);?>
        <span class="label label-info" title="<?php echo $last_modified_time; ?>"> <?php echo $last_time_ago; ?></span>
      </td>  

    </tr>
  <?php  }   ?>
    </table>
  </div>
</div>
</div>
</div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.1.3/circle-progress.min.js"></script> 
    <script>
        var progressBarOptions = {
            startAngle: -1,
            size: 60,
        };
        $('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function (event, progress, value) {
           
        });
    </script> 


<script type="text/javascript">
$('.pie_progress').asPieProgress({
size: 100,
ringWidth: 100,
strokeWidth: 100,
ringEndsRounded: true,
valueSelector: "span.value",
color: "navy"
});
</script> 
<script type="text/javascript">
$(document).ready(function() {
    $('#filter_by_coin').multiselect({
      includeSelectAllOption: true,
      buttonWidth: 435.7,
      enableFiltering: true
    });
});
</script> 
<script>
$(document).ready(function(e){
var filter_by_trigger = $("#filter_by_trigger").val();



if (filter_by_trigger == 'barrier_trigger') {
    $(".filter_by_level").hide();
    $(".filter_by_rule").show();
}else if(filter_by_trigger == 'barrier_percentile_trigger'){
    $(".filter_by_level").show();
    $(".filter_by_rule").hide();
}else{
    $(".filter_by_level").hide();
    $(".filter_by_rule").hide();
}
});


$("body").on("change","#filter_by_trigger",function(e){
var filter_by_trigger = $("#filter_by_trigger").val();

$(".filter_by_level").hide();
$(".filter_by_rule").hide();

if(filter_by_trigger =='barrier_percentile_trigger'){
  $(".filter_by_level").show();
$(".filter_by_rule").hide();
}

if(filter_by_trigger =='barrier_trigger'){
  $(".filter_by_rule").show();
$(".filter_by_level").hide();
}
if(filter_by_trigger =='no'){
  $(".filter_by_level").hide();
    $(".filter_by_rule").hide();
}
});

$("body").on("click","input[name=group_filter]",function(e){
var query = $(this).attr('id');
if (query == 'trigger_group') {
    $("#trigger1").hide();

$("#triggerFirst").show();
}else if(query == 'rule_group'){
    $("#trigger1").show();
$("#triggerFirst").hide();
}else{
  $("#trigger1").hide();
}
});
$(function () {
$('[data-toggle="tooltip"]').tooltip()
})
</script> 