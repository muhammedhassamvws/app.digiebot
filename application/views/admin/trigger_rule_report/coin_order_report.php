<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>-->
<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
.Input_text_s {
    float: left;
    width: 100%;
	position:relative;
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

.coin_symbol {}

.coin_symbol {
    color: #fff;
    font-weight: bold;
    font-size: 16px;
    float: left;
    width: 100%;
    padding: 12px 20px;
    background: #2d4c5a;
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
</style>

<style type="text/css">
          /* Important part */
          .modal-dialog{
              overflow-y: initial !important
          }
          .Opp{
              height: 550px;
              padding-left: 10px;
              overflow-y: auto;
              overflow-x: hidden;
            }
          .totalAvg {
            padding-top: 44px;
          }
    </style>

<style>
                  .Input_text_btn {padding: 25px 0 0;}
               </style>

<?php //echo "<pre>";  print_r($full_arr); exit; ?>

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
            <form method="POST" action="<?php echo SURL; ?>admin/trigger_rule_reports/coin_report">
              <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-12 ax_1">
                <div class="alert alert-info"><?php echo "Server Time: " . date("d-m-y H:i:s a"); ?></label>
                </div>
               <div class="col-xs-12 col-sm-12 col-md-3 ax_2">
                  <div class="Input_text_s">
                     <label>Filter Coin: </label>
                     <select id="filter_by_coin" name="filter_by_coin" type="text" class=" filter_by_name_margin_bottom_sm form-control">
 <?php
if (count($coins) > 0) {
    for ($i = 0; $i < count($coins); $i++) {
        ?>
                  <?php if (in_array($coins[$i]['symbol'], $this->input->post('filter_by_coin'))) {?>
                      <option value="<?php echo $coins[$i]['symbol'] ?>" selected><?php echo $coins[$i]['symbol']; ?></option>
                  <?php } else {?>
                          <option value="<?php echo $coins[$i]['symbol'] ?>" ><?php echo $coins[$i]['symbol']; ?></option>
                    <?php }
    }
}
?>
                     </select>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-3 ax_3">
                  <div class="Input_text_s">
                     <label>Filter Mode: </label>
                     <select id="filter_by_mode" name="filter_by_mode" type="text" class="form-control filter_by_name_margin_bottom_sm">
                        <option value="">Search By Mode</option>
                        <option value="live"<?=(($filter_user_data['filter_by_mode'] == "live") ? "selected" : "")?>>Live</option>
                        <option value="test_live"<?=(($filter_user_data['filter_by_mode'] == "test_live") ? "selected" : "")?>>Test</option>
                        <option value="test_simulator"<?=(($filter_user_data['filter_by_mode'] == "test_simulator") ? "selected" : "")?>>Simulator</option>
                     </select>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-3 ax_4">
                  <div class="Input_text_s">
                     <label>From Date Range: </label>
                     <input id="filter_by_start_date" name="filter_by_start_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_start_date']) ? $filter_user_data['filter_by_start_date'] : "")?>" autocomplete="off">
                     <i class="glyphicon glyphicon-calendar"></i>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-3 ax_5">
                  <div class="Input_text_s">
                     <label>To Date Range: </label>
                     <input id="filter_by_end_date" name="filter_by_end_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_end_date']) ? $filter_user_data['filter_by_end_date'] : "")?>" autocomplete="off">
                     <i class="glyphicon glyphicon-calendar"></i>
                  </div>
               </div>
               <script type="text/javascript">
                   $(function () {
                       $('.datetime_picker').datetimepicker();
                   });
               </script>
               
               <div class="col-xs-12 col-sm-12 col-md-3 ax_6">
                  <div class="Input_text_s">


                  		<div class="col-radio">
                                <label>
                                    <input id="trigger_group" name="group_filter" value="trigger_group" type="radio" <?php if ($filter_user_data['group_filter'] == 'trigger_group') {?>checked="checked" <?php }?> />
                                    <span>
                                        <i class="fa fa-dot-circle-o"></i>
                                        <i class="fa fa-circle-o"></i>
                                    </span>
                                    Select Trigger
                                </label>
                            </div>


                  </div>
                  
                  
               </div>
               
               <div class="col-xs-12 col-sm-12 col-md-3  ax_8" style=" min-height: 60px;">
                  <div class="Input_text_s" id="triggerFirst" <?php if ($filter_user_data['group_filter'] == 'rule_group') {?>style="display:block;" <?php } else {?><?php }?>>
                     <label>Filter Trigger: </label>
                     <select id="filter_by_trigger" name="filter_by_trigger" type="text" class="form-control filter_by_name_margin_bottom_sm">
                       <option value="">Search By Trigger</option>
                       <option value="trigger_1" <?php if ($filter_user_data['filter_by_trigger'] == 'trigger_1') {?> selected <?php }?>>Trigger 1</option>
                       <option value="trigger_2" <?php if ($filter_user_data['filter_by_trigger'] == 'trigger_2') {?> selected <?php }?>>Trigger 2</option>
                       <option value="box_trigger_3" <?php if ($filter_user_data['filter_by_trigger'] == 'box_trigger_3') {?> selected <?php }?>>Box Trigger 3</option>
                       <option value="barrier_trigger" <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_trigger') {?> selected <?php }?>>BARRIER TRIGGER</option>
                       <option value="rg_15" <?php if ($filter_user_data['filter_by_trigger'] == 'rg_15') {?> selected <?php }?>>RG 15</option>
                        <option value="barrier_percentile_trigger" <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_percentile_trigger') {?> selected <?php }?>>Barrier Percentile Trigger</option>
                       <option value="no" <?php if ($filter_user_data['filter_by_trigger'] == 'no') {?> selected <?php }?>>Manual Order</option>
                     </select>
                  </div>
               </div>
               <!-- Hidden Searches -->
               <div class="col-xs-12 col-sm-12 col-md-3 ax_7">
                  <div class="Input_text_s">



                        <div class="col-radio">
                                <label>
                                    <input id="rule_group" name="group_filter" value="rule_group" type="radio" <?php if ($filter_user_data['group_filter'] == 'rule_group') {?>checked="checked" <?php }?>/>
                                    <span>
                                        <i class="fa fa-dot-circle-o"></i>
                                        <i class="fa fa-circle-o"></i>
                                    </span>
                                    Group By Rule
                                </label>
                            </div>


                  </div>
               </div>
               <!-- <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                        <div class="inputGroup">
                          <input id="level_group" name="level_group" type="checkbox"/>
                          <label for="level_group">Group By Level</label>
                        </div>
                  </div>
               </div> -->

               <!-- End Hidden Searches -->
               <div class="col-xs-12 col-sm-12 col-md-3  ax_8" style=" min-height: 60px;">
                  <div class="Input_text_s" id="trigger1" <?php if ($filter_user_data['group_filter'] == 'rule_group') {?>style="display:block;" <?php } else {?> style="display:none;" <?php }?>>
                     <label>Filter Trigger: </label>
                     <select id="filter_by_trigger" name="filter_by_trigger" type="text" class="form-control filter_by_name_margin_bottom_sm">
                       <option value="">Search By Trigger</option>
                       <option value="trigger_1" <?php if ($filter_user_data['filter_by_trigger'] == 'trigger_1') {?> selected <?php }?>>Trigger 1</option>
                       <option value="trigger_2" <?php if ($filter_user_data['filter_by_trigger'] == 'trigger_2') {?> selected <?php }?>>Trigger 2</option>
                       <option value="box_trigger_3" <?php if ($filter_user_data['filter_by_trigger'] == 'box_trigger_3') {?> selected <?php }?>>Box Trigger 3</option>
                       <option value="barrier_trigger" <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_trigger') {?> selected <?php }?>>BARRIER TRIGGER</option>
                       <option value="rg_15" <?php if ($filter_user_data['filter_by_trigger'] == 'rg_15') {?> selected <?php }?>>RG 15</option>
                        <option value="barrier_percentile_trigger" <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_percentile_trigger') {?> selected <?php }?>>Barrier Percentile Trigger</option>
                       <option value="no" <?php if ($filter_user_data['filter_by_trigger'] == 'no') {?> selected <?php }?>>Manual Order</option>
                     </select>
                  </div>
               </div>
               <!--<div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter Username: </label>
                     <div class="inputGroup">
                          <input id="option1" name="option1" type="checkbox"/>
                          <label for="option1">Option One</label>
                        </div>
                  </div>
               </div> -->
               <div class="col-xs-12 col-sm-12 col-md-12 ax_9">
                  <div class="Input_text_btn">
                     <label></label>
                     <button id="submit-form" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
                     <a href="<?php echo SURL; ?>admin/reports/reset_filters_report/coin" class="btn btn-danger"><i class="fa fa-times-circle"></i>Reset</a>

                     <span class="ax_10"><button class="btn btn-info" onclick="exportTableToCSV('report.csv')">Export To CSV File</button></span>
                     <!-- <span style="float:right;"><a href="<?php //echo SURL; ?>admin/reports/csv_export_trades" class="btn btn-info">Export To CSV File</a></span> -->
                  </div>
               </div>
            </div>
            </form>
          </div>
      </div>
    <!-- Widget -->
    
    <div class="widget widget-inverse">

      <div class="widget-body padding-bottom-none">
      	<div class="table-responsive">
            <!-- <pre>
              <?php //print_r($full_arr);?>
            </pre> -->
            <?php
foreach ($full_arr as $coin_key => $coin_array) {
    ?>
            <table class="table">
              <tr class="coin_symbol">
                <td>Coin</td>
                <td><?=$coin_key;?></td>
                <?php $trigger_meta = $coin_array['coin_meta'];?>
                      <td><span style="font-weight: bolder;">Total Opertunity :</span> <?= $trigger_value['opp']['op'];?></td>
                      <td><span style="font-weight: bolder;">Highest Price:</span> <?=num($trigger_meta['high'])?></td>
                      <td><span style="font-weight: bolder;">Lowest Price: </span><?=num($trigger_meta['low'])?></td>
                      <td><span style="font-weight: bolder;">Coin Average Move: </span><?=$trigger_meta['coin_avg_move']?></td>
              </tr>
            </table>
          
            <table class="table table-stripped">
              <tr class="theadd">
              
                <!--<td>S.No</td>-->
                <td>Time</td>
                <td>Average Profit</td>
                <td>Total Count</td>
                <td>Five Hour High</td>
                <td>Five Hour Low</td>
                <td>Max High</td>
                <td>Max Low</td>
               
                
              
              </tr>
              <?php foreach ($coin_array as $trigger_key => $trigger_value) {
				    //echo "<pre>";  print_r($trigger_value); exit;
					if ($trigger_key != 'coin_meta') {
			
					$variable  = $trigger_value['opp']['avg'];
					$total_avg = 0;
					$total     = 0;
					
					//$k=1; 
					foreach ($variable as $key => $value) {
						$total++;
						$total_avg += $value['avg'];?>
                        
                        <tr>
                          <!--<th><?php echo $k; ?></th>-->
                          <th><?php echo $key . ":00:00"; ?></th>
                          <td><?php echo number_format($value['avg'], 2); ?></td>
                          <td><?php echo $value['count']; ?></td>
                          <td><?php echo number_format($value['max_profit_5h'], 2); ?></td>
                          <td><?php echo number_format($value['min_profit_5h'], 2); ?></td>
                          <td><?php echo number_format($value['max_profit_high'], 2); ?></td>
                          <td><?php echo number_format($value['min_profit_low'], 2); ?></td>

                        </tr>
                        <?php $k++; 
					}?>

              </tr>
            <?php } elseif ($trigger_key == 'coin_meta') { ?>
              <!--<tr>
                <td colspan="17" style="background: #ccc;">
                  <table class="table table-stripped-column table-hover">
                    <tr>
                      <td><span style="font-weight: bolder;">Total Coin Counts:</span> <?=$trigger_value['total']?></td>
                      <td><span style="font-weight: bolder;">Highest Price:</span> <?=num($trigger_value['high'])?></td>
                      <td><span style="font-weight: bolder;">Lowest Price: </span><?=num($trigger_value['low'])?></td>
                      <td><span style="font-weight: bolder;">Coin Average Move: </span><?=$trigger_value['coin_avg_move']?></td>
                    </tr>
                  </table>
                </td>
              </tr>-->
            <?php }
			}
    }?>
            </table>
          
        </div>
        <!-- // Table END -->
      </div>
    </div>
    <!-- // Widget END -->

  </div>
</div>

<!-- The modal -->

<!-- Start Model -->

<!-- End Model -->

<!-- Start Model -->

<!-- End Model -->
<script type="text/javascript">

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
            //close
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
<script type="text/javascript">

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
   var query = $("#filter_by_trigger").val();
    if (query == 'barrier_trigger') {
        $("#barrier_t").show();
        $("#barrier_p_t").hide();
    }else if(query == 'barrier_percentile_trigger'){
        $("#barrier_t").hide();
        $("#barrier_p_t").show();
    }else{
      $("#barrier_t").hide();
        $("#barrier_p_t").hide();
    }
});
$("body").on("click",".glassflter",function(e){
    var query = $("#filter_by_name").val();
    window.location.href = "<?php echo SURL; ?>/admin/users/?query="+query;
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

$("body").on("click",".viewadmininfo",function(e){
    var user_id = $(this).attr('id');
    $.ajax({
      url: "<?php echo SURL; ?>admin/reports/get_user_info",
      data: {user_id:user_id},
      type: "POST",
      success: function(response){
          $("#mymodalresp").html(response);
      }
    });
});

$("body").on("click",".view_order_details",function(e){

      var order_id = $(this).attr("data-id");

       $.ajax({
          'url': '<?php echo SURL ?>admin/dashboard/get_buy_order_details_ajax',
          'type': 'POST',
          'data': {order_id:order_id},
          'success': function (response) {

              $('#response_order_details').html(response);
              $("#modal-order_details").modal('show');
          }
      });

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


  //Custom switcher by Afzal
  jQuery("body").on("change","#af-swith-asc",function(){
	if(jQuery(".af-switcher-default").hasClass("active")){
		jQuery(".af-switcher-default").removeClass("active");
	}
	else{
		jQuery(".af-switcher-default").addClass("active");
	}
	});
	jQuery("body").on("click",".af-switcher-default",function(){
		if(jQuery(".af-switcher-default").hasClass("active")){
			jQuery(".af-switcher-default").removeClass("active");
		}
		else{
			jQuery(".af-switcher-default").addClass("active");
		}
	});
  jQuery("body").on("change",".af-cust-radio",function(){
		jQuery(".af-form-group-created").addClass("active");
	});
  //----End--------
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