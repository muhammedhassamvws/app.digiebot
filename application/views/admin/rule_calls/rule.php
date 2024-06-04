
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php  echo SURL ?>assets/dist/jquery-asPieProgress.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
<?php //echo "<pre>";  print_r($full_arr); exit; ?>
<div id="content">
<h1 class="content-heading bg-white border-bottom">Reports</h1>
<div class="innerAll bg-white border-bottom">
</div>
<?php $filterData = $this->session->userdata($search_data);


?>

<div class="widget widget-inverse">
  <div class="widget-body">
    <form method="POST" action="<?php echo SURL; ?>admin/rule_calls/update_rule">
      <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 ax_1">

        <div class="col-xs-12 col-sm-12 col-md-3 ax_3">
          <div class="Input_text_s">
            <label>Coin: </label>
            <select id="coin" name="coin" type="text" class="form-control filter_by_name_margin_bottom_sm">
            <?php foreach($coins as $coinRow){ ?> 
              <option value="<?php echo $coinRow['symbol'];?>"<?=(($filterData['search_data']['coin'] == $coinRow['symbol']) ? "selected" : "")?>><?php echo $coinRow['symbol'] ?></option>
              <?php } ?>
            </select>
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 ax_3">
          <div class="Input_text_s">
            <label>Mode: </label>
            <select id="mode" name="mode" type="text" class="form-control filter_by_name_margin_bottom_sm">
              <option value="both"<?=(($filterData['search_data']['mode'] == "both") ? "selected" : "")?>>Both</option>
              <option value="live"<?=(($filterData['search_data']['mode'] == "live") ? "selected" : "")?>>Live</option>
              <option value="test"<?=(($filterData['search_data']['mode'] == "test") ? "selected" : "")?>>Test</option>
            </select>
          </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 ax_3">
          <div class="Input_text_s">
            <label>Status: </label>
            <select id="status" name="status" type="text" class="form-control filter_by_name_margin_bottom_sm">
              <option value="on"<?=(($filterData['search_data']['status'] == "on") ? "selected" : "")?>>On</option>
              <option value="off"<?=(($filterData['search_data']['status'] == "off") ? "selected" : "")?>>Off</option>
            </select>
          </div>
        </div>

       
        <div class="col-xs-12 col-sm-12 col-md-3 ax_10">
          <div class="Input_text_btn">
            <label></label>
            <button id="submit-form" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Submit</button>
            </span>
          </div>
        </div>
      </div>
      </div>
    </form>
  
       <br>
          <table class="table table-striped">
            <thead class="thead-dark">
              <tr>
                <th scope="col">Coin</th>
                <th scope="col">Level</th>
                <th scope="col">On Status</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($hit_data->data as $value){?>
              <tr>
                <td><?php echo $value->coin; ?></td>
                <td>level_15</td>
                <td>
                    <?php
                      if($value->mode == "both" || $value->mode == "live"){ ?>
                      <span style="color:green; font-weight:bold"><?php echo $value->mode;?></span> 
                      <?php } else{
                        echo $value->mode;
                      }
                    ?>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
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
input[type="checkbox"][readonly] {
  pointer-events: none;
}
</style>
<script>

$(".setsize").each(function() {
    $(this).height($(this).width());
});
// });
$(window).on('resize', function(){
$(".setsize").each(function() {
    $(this).height($(this).width());
});
});
</script> 
</div>
