<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
/*---------------------------*/

/*input[type="radio"] {
  display: none;
}

input[type="radio"] + label {
  position: relative;
  cursor: pointer;
  margin: 30px;
  padding-left: 28px;
}
input[type="radio"] + label:before, input[type="radio"] + label:after {
  content: "";
  position: absolute;
  border-radius: 50%;
  -moz-transition: all 0.3s ease;
  -o-transition: all 0.3s ease;
  -webkit-transition: all 0.3s ease;
  transition: all 0.3s ease;
}
input[type="radio"] + label:before {
  top: 0;
  left: 0;
  width: 18px;
  height: 18px;
  background: #1565c0;
  -moz-box-shadow: inset 0 0 0 18px #e0e0e0;
  -webkit-box-shadow: inset 0 0 0 18px #e0e0e0;
  box-shadow: inset 0 0 0 18px #e0e0e0;
}
input[type="radio"] + label:after {
  top: 49%;
  left: 9px;
  width: 54px;
  height: 54px;
  opacity: 0;
  background: rgba(255, 255, 255, 0.3);
  -moz-transform: translate(-50%, -50%) scale(0);
  -ms-transform: translate(-50%, -50%) scale(0);
  -webkit-transform: translate(-50%, -50%) scale(0);
  transform: translate(-50%, -50%) scale(0);
}

input[type="radio"]:checked + label:before {
  -moz-box-shadow: inset 0 0 0 4px #e0e0e0;
  -webkit-box-shadow: inset 0 0 0 4px #e0e0e0;
  box-shadow: inset 0 0 0 4px #e0e0e0;
}
input[type="radio"]:checked + label:after {
  -moz-transform: translate(-50%, -50%) scale(1);
  -ms-transform: translate(-50%, -50%) scale(1);
  -webkit-transform: translate(-50%, -50%) scale(1);
  transform: translate(-50%, -50%) scale(1);
  -moz-animation: ripple 1s none;
  -webkit-animation: ripple 1s none;
  animation: ripple 1s none;
}*/
/*---------------------------*/
.Input_text_s {
    float: left;
    width: 100%;
  position:relative;
}
.Input_text_s > i {
    position: absolute;
    right: 8px;
    bottom: 4px !important;
    height: 20px;
    top: auto;
}
.Input_text_btn > a > i, .Input_text_btn > button > i {
    margin-right: 10px;
}

.af-ledger-titles {
  width: 100%;
  float: left;
  display: inline-block;
}

.af-ledger-titles h2 {
  display: inline-block;
  float: left;
  width: 50%;
  text-align: center;
  border-bottom: 2px solid #eee;
  margin: 0;
  padding-bottom: 10px;
}
.af-ledger-table-debit {
  width: 50%;
  float: left;
  display: inline-table;
  text-align: center;
  border-right: 2px solid #eee;
}
.af-ledger-table-credit {
  width: 50%;
  float: left;
  display: inline-table;
  text-align: center;
}
.af-ledger-table-debit th {
  text-align: center;
  border-bottom: 2px solid #eee;
}
.af-ledger-table-credit th {
  text-align: center;
  border-bottom: 2px solid #eee;
}
.af-ledger-table-debit tbody {
  border-bottom: 2px solid #eee;
  background: #f7f7f7;
}
.af-ledger-table-credit tbody {
  border-bottom: 2px solid #eee;
  background: #f7f7f7;
}
.af-ledger-table-debit tr {
  height: 40px;
}
.af-ledger-table-credit tr {
  height: 40px;
}
.af-ledger-table-debit tfoot {
  border-bottom: 2px solid #eee;
}
.af-ledger-table-credit tfoot {
  border-bottom: 2px solid #eee;
}
</style>

<div id="content">
  <h1 class="content-heading bg-white border-bottom">Reports</h1>
  <div class="innerAll bg-white border-bottom">
  <ul class="menubar">
    <li class=""><a href="<?php echo SURL; ?>admin/reports">Reports</a></li>
    <li class="active"><a href="#">Custom Report</a></li>
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
      <?php $filter_user_data = $this->session->userdata('filter_order_data');?>
      <div class="widget widget-inverse">
         <div class="widget-body">
            <form method="POST" action="<?php echo SURL; ?>admin/reports/meta_coin_report">
              <div class="row">
                <div class="col-md-12">
                  <div class="col-xs-12 col-sm-12 col-md-6" style="padding-bottom: 6px;">
                  <input type="radio" class="radiobtn" id="radio1" name="radio-category" value="new" />
                  <label for="radio1">New Search</label>

                  <input type="radio" class="radiobtn" id="radio2" name="radio-category" value="history" />
                  <label for="radio2">Search History</label>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-6 new" style="padding-bottom: 6px; display: none;">
                  <div class="Input_text_s">
                     <label>Title: <br><small>Title to Filter</small> </label>
                     <input type="text" class="form-control" name="title_to_filter" value="<?=$filter_user_data['title_to_filter']?>">
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-6 old" style="padding-bottom: 6px; display: none;">
                  <div class="Input_text_s">
                     <label>Filter Search:<br> <small>select filter</small></label>
                     <select id="filter_search" name="filter_search" type="text" class="form-control filter_by_name_margin_bottom_sm">
                        <option value ="" <?=(($filter_user_data['filter_search'] == "") ? "selected" : "")?>>Search Filter</option>
                        <?php
for ($i = 0; $i < count($settings); $i++) {
    if (!empty($settings[$i]['title_to_filter'])) {
        $selected = ($settings[$i]['title_to_filter'] == $filter_user_data['title_to_filter']) ? "selected" : "";
        echo "<option value='" . $settings[$i]['_id'] . "' $selected>" . $settings[$i]['title_to_filter'] . "</option>";
    }
}
?>
                     </select>
                  </div>
               </div>
                </div>

               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter Coin:<br> <small>select coin</small></label>
                     <select id="filter_by_coin" name="filter_by_coin" type="text" class="form-control filter_by_name_margin_bottom_sm" required>
                        <option value ="" <?=(($filter_user_data['filter_by_coin'] == "") ? "selected" : "")?>>Search By Coin Symbol</option>
                        <?php
for ($i = 0; $i < count($coins); $i++) {
    $selected = ($coins[$i]['symbol'] == $filter_user_data['filter_by_coin']) ? "selected" : "";
    echo "<option value='" . $coins[$i]['symbol'] . "' $selected>" . $coins[$i]['symbol'] . "</option>";
}
?>
                     </select>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Black Wall: <br><small>greater</small> </label>
                     <input type="text" class="form-control" name="black_wall_pressure" id="black_wall_pressure" value="<?=$filter_user_data['black_wall_pressure']?>">
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Yellow Wall:<br> <small>greater</small></label>
                     <input type="text" class="form-control" name="yellow_wall_pressure" id="yellow_wall_pressure" value="<?=$filter_user_data['yellow_wall_pressure']?>">
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Big Buyers (%age):<br> <small>greater</small></label>
                     <input type="text" class="form-control" name="ask_percentage" id="ask_percentage" value="<?=$filter_user_data['ask_percentage']?>">
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Seven Level: <br><small>greater</small></label>
                     <input type="text" class="form-control" name="seven_level_depth" id="seven_level_depth" value="<?=$filter_user_data['seven_level_depth']?>">
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Resistance Barrier:<br> <small>less</small></label>
                     <input type="text" class="form-control" name="market_depth_ask" id="market_depth_ask" value="<?=$filter_user_data['market_depth_ask']?>">
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Support Barrier: <br><small>greater</small></label>
                     <input type="text" class="form-control" name="market_depth_quantity" id="market_depth_quantity"  value="<?=$filter_user_data['market_depth_quantity']?>">
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>T1COT (B/S): <br><small>greater</small></label>
                     <input type="text" class="form-control" name="sellers_buyers_per" id="sellers_buyers_per" value="<?=$filter_user_data['sellers_buyers_per']?>">
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>T4COT (B/S): <br><small>greater</small></label>
                     <input type="text" class="form-control" name="sellers_buyers_per_fifteen" id="sellers_buyers_per_fifteen" value="<?=$filter_user_data['sellers_buyers_per_fifteen']?>">
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>T1LTC (B/S): <br><small>greater</small></label>
                     <input type="text" class="form-control" name="last_qty_buy_vs_sell" id="last_qty_buy_vs_sell" value="<?=$filter_user_data['last_qty_buy_vs_sell']?>">
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>T1LTC (Time):<br> <small>less</small></label>
                     <input type="text" class="form-control" name="last_qty_time_ago" id="last_qty_time_ago" value="<?=$filter_user_data['last_qty_time_ago']?>">
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>T3LTC (Time):<br> <small>less</small></label>
                     <input type="text" class="form-control" name="last_qty_time_ago_15" id="last_qty_time_ago_15" value="<?=$filter_user_data['last_qty_time_ago_15']?>">
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Binance Sell: <br><small>less</small></label>
                     <input type="text" class="form-control" name="bid" id="bid" value="<?=$filter_user_data['bid']?>">
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Binance Buy:<br> <small>greater</small></label>
                     <input type="text" class="form-control" name="ask" id="ask" value="<?=$filter_user_data['ask']?>">
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Target Profit:<br> <small>greater</small></label>
                     <input type="text" class="form-control" name="target_profit" id="target_profit" value="<?=$filter_user_data['target_profit']?>" required>
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Target StopLoss:<br> <small>less</small></label>
                     <input type="text" class="form-control" name="target_stoploss" id="target_stoploss" value="<?=$filter_user_data['target_stoploss']?>" required>
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Lookup Period: <br><small>lookup period</small></label>
                     <input type="text" class="form-control" name="lookup_period" id="lookup_period" value="<?=$filter_user_data['lookup_period']?>" required>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-1" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Watch Later: <br><small>wait for calculation</small></label>
                     <input type="checkbox" class="form-check-input" id="watch_later" name="watch_later" value="yes" <?php if ($filter_user_data['watch_later'] == 'yes') {echo "checked";}?>>
                     <label class="form-check-label"  for="barrier_check">Watch Later</label>
                  </div>
               </div>
               <div class="col-sm-12 col-md-12">
                 <div class="col-xs-12 col-sm-12 col-md-6 ax_4">
                  <div class="Input_text_s">
                     <label>From Date Range: <br></label>
                     <input id="filter_by_start_date" name="filter_by_start_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_start_date']) ? $filter_user_data['filter_by_start_date'] : "")?>" autocomplete="off" required>
                     <i class="glyphicon glyphicon-calendar"></i>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-6 ax_5">
                  <div class="Input_text_s">
                     <label>To Date Range: <br></label>
                     <input id="filter_by_end_date" name="filter_by_end_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_end_date']) ? $filter_user_data['filter_by_end_date'] : "")?>" autocomplete="off" required>
                     <i class="glyphicon glyphicon-calendar"></i>
                  </div>
               </div>
               </div>
               <script type="text/javascript">
                   $(function () {
                       $('.datetime_picker').datetimepicker({format: 'YYYY-MM-DD 12:00 A'});
                   });
               </script>
                <style>
                  .Input_text_btn {padding: 25px 0 0;}
               </style>
               <div class="col-xs-12 col-sm-12 col-md-12" style="padding-bottom: 6px;">
                  <div class="Input_text_btn">
                     <label></label>
                     <button id="submit-form" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
                     <a href="<?php echo SURL; ?>admin/reports/reset_filters_report/meta" class="btn btn-danger"><i class="fa fa-times-circle"></i>Reset</a>
                  </div>
               </div>
            </div>
            </form>
          </div>
      </div>
    <!-- Widget -->
    <div class="widget widget-inverse">
      <div class="widget-head">
       <span> Meta Report </span>
        <span style="float:right;"><button class="btn btn-info" onclick="exportTableToCSV('report.csv')">Export To CSV File</button></span>
      </div>
        <div class="widget-body">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Total Oppurtunities</th>
                    <th>Winning Opportunities</th>
                    <th>Winning Percentage</th>
                    <th>Losing Opportunities</th>
                    <th>Losing Percentage</th>
                    <th>Total Winning Percentage</th>
                    <th>Total Losing Percentage</th>
                    <th>Total Percentage</th>
                    <th>Per Trade Percentage Profit</th>
                    <th>Per Day Percentage Profit</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><?php echo $count_msg; ?></td>
                    <td><?php echo $positive_msg; ?></td>
                    <td><?php echo $positive_percentage; ?></td>
                    <td><?php echo $negitive_msg; ?></td>
                    <td><?php echo $negitive_percentage; ?></td>
                    <td><?php echo $winners; ?></td>
                    <td><?php echo $losers; ?></td>
                    <td><?php echo $total_profit; ?></td>
                    <td><?php echo $per_trade; ?></td>
                    <td><?php echo $per_day; ?></td>
                  </tr>
                </tbody>

            </table>
            </div>
        </div>
        <div class="widget-body">
          <div class="table-responsive">
            <table class="table table-stripped">
              <thead>
                <tr>
                  <th>Opportunity Time</th>
                  <th>Market Price</th>
                  <th>Market Time</th>
                  <th>Message</th>
                  <th>Profit Percentage</th>
                  <th>Profit Time</th>
                  <th>Profit Time Ago</th>
                  <th>Loss Percentage</th>
                  <th>Loss Time</th>
                  <th>Loss Time Ago</th>
                </tr>
              </thead>
              <tbody>
                <?php
if (count($final) > 0) {
    foreach ($final as $key => $value) {
        if (!empty($value)) {
            ?>
                        <tr>
                          <td><?=$key;?></td>
                          <td><?=$value['market_value'];?></td>
                          <td><?=$value['market_time'];?></td>
                          <td><?=$value['message'];?></td>
                          <td><?=$value['profit_percentage'];?></td>
                          <td><?=$value['profit_date'];?></td>
                          <td><?=$value['profit_time'];?></td>
                          <td><?=$value['loss_percentage'];?></td>
                          <td><?=$value['loss_date'];?></td>
                          <td><?=$value['loss_time'];?></td>
                        </tr>
                      <?php
}
    }
}
?>
              </tbody>
            </table>
          </div>
        </div>
    </div>
    <!-- // Widget END -->

  </div>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
$("body").on("click",".radiobtn",function(e){
  var value = $( ".radiobtn:checked" ).val()

  if (value == 'history') {
    $(".old").show();
    $(".new").hide();
  }else if (value == 'new') {
    $(".new").show();
    $(".old").hide();
  }
});
$("body").on("click","#watch_later",function(e){
   if($(this).is(":checked")) {
      $('#code_backup').html($('.coin_filter').html());
      $('#filter_by_coin').prop("name" , "filter_by_coin[]");
      $('#filter_by_coin').prop("multiple",true);
      $('#filter_by_coin').multiselect({
      includeSelectAllOption: true,
      buttonWidth: 250,
      enableFiltering: true
    });
    }else{
      $('.btn-group').remove();
      $('.coin_filter').html($("#code_backup").html());
      $('#filter_by_coin').prop("name" , "filter_by_coin");
      $('#filter_by_coin').removeAttr("multiple");
    }
});

$("body").on("change","#filter_search",function(e){
  var value = $(this).val()

  $.ajax({
    url: "<?=SURL;?>admin/reports/rest_filters_meta",
    type: "POST",
    data: {value:value, trigger:"barrier"},
    success: function(rep){
      console.log(rep);
      var rep = JSON.parse(rep);
      $.each(rep, function( key, value ) {
        if (key != 'filter_search') {
          $('#'+key).val(value);
        }
      });
    }
  });
});


function passwordCheck(){
  $.confirm({
    title: 'Prompt!',
    content: '' +
    '<form action="" class="formName">' +
    '<div class="form-group">' +
    '<label>Enter something here</label>' +
    '<input type="text" placeholder="Please Enter Pin" class="name form-control" required />' +
    '</div>' +
    '</form>',
    buttons: {
        formSubmit: {
            text: 'Submit',
            btnClass: 'btn-blue',
            action: function () {
                var name = this.$content.find('.name').val();
                if(!name){
                    $.alert('provide a valid name');
                    return false;
                }else{
                  if (name == '7869') {
                    $.alert('You are authorized to view this page');
                  }else{
                    window.location.href = "<?php echo SURL; ?>forbidden/"
                  }
                }

            }
        },
        cancel: function () {
            window.location.href = "<?php echo SURL; ?>forbidden/"
        },
    },
    onContentReady: function () {
        // bind to events
        var jc = this;
        this.$content.find('form').on('submit', function (e) {
            // if the user submits the form by pressing enter in the field.
            e.preventDefault();
            jc.$$formSubmit.trigger('click'); // reference the button and click it
        });
    }
});
}
$(document).ready(function(e){
  //passwordCheck()
});
</script>
<script>
  $( function() {
    availableTags = [];
    $.ajax({
       'url': '<?php echo SURL ?>admin/reports/get_all_usernames_ajax',
       'type': 'POST',
       'data': "",
       'success': function (response) {
          availableTags = JSON.parse(response);

          $( "#username" ).autocomplete({
            source: availableTags
          });
       }
   });

  });
  </script>

  <script type="text/javascript">
    function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
}

function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("table tr");

    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");

        for (var j = 0; j < cols.length; j++)
            row.push(cols[j].innerText);

        csv.push(row.join(","));
    }

    // Download CSV file
    downloadCSV(csv.join("\n"), filename);
}
  </script>