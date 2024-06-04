<style>
span.time-ago-stamp {
    background: #e9e9e9;
    color: black;
    padding: 5px;
    border: 1px solid #efefef;
    border-radius: 50px;
}
</style>
<div id="content">

  <h1 class="content-heading bg-white border-bottom">Add Cron Job</h1>

  <div class="bg-white innerAll border-bottom">

	<ul class="menubar">

    	<li><a href="<?php echo SURL; ?>admin/cron-listing">Cron Listing</a></li>

		<li class="active"><a href="<?php echo SURL; ?>admin/cron-listing/add-cronjob">Add Cronjob</a></li>

	</ul>

  </div>

  <div class="innerAll spacing-x2">



  

      <!-- Widget -->

      <div class="widget widget-inverse">
      <div class="alert alert-success alert-dismissable successMessage" style="display:none;"><strong>Success !</strong> updated Successfully</div>
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

    <!-- Widget -->

    <div class="widget widget-inverse">

      <div class="widget-body padding-bottom-none">

        <!-- Table -->
        <table class="table table-bordered " style="width: 100%;">

          <!-- Table heading -->
          <thead>
            <tr>
              <th width="3%">Sr</th>
              <th width="20%">Cronjob Link</th>
              <th width="25%">Summary</th>
              <th>Priority</th>
              <th>Last Update</th>
              <th>Execute</th>
              <th>Status</th>
              <th>Type</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>

           <?php

if (count($cron_list) > 0) {

    for ($i = 0; $i < count($cron_list); $i++) {

        $test = check_active_cron($cron_list[$i]['name']);

        $stopped_crons_arr = [
          'COIN META BINANCE ZENBTC',
          'COIN META BINANCE POEBTC',
          'FIVE SECOND TRENDING INDICATORS BINANCE',
        ];
        $ignore_crons_arr = [
          "MARKET PRICES SOCKET BINANCE",
          "IS SERVER RUNNING",
          "AUTO BUY BNB BAM",
          "UPDATE USER VALLET BINANCE",
          "CALCULATE 5 HOUR MIN MAX BAM",
          "CALCULATE TRADE MIN MAX BAM",
          "BAM LIVE OPPORTUNITY",
          "BAM TEST OPPORTUNITY",
          "UPDATE DAILY BUY LIMIT BAM",
          "UPDATE USER VALLET BAM",
          "CALCULATE CURRENT TRADING POINTS BAM",
          "UPDATE AUTO TRADE USD WORTH AND TRADEABLE BALANCE BAM",
          "RANDOMIZE SORT NUMBER BAM",
          "COIN META HOURLY PERCENTILE BAM",
          "TRENDING SCRIPTS RECYCLE BAM",
          "24 HOUR PRICE UPDATE BAM",
          "MARKET CHART HOURLY BAM",
          "TRENDING SCRIPTS BAM",
          "MARKET TRADE DAILY PERCENTILE BAM",
          "UPDATE AUTO TRADE ACTUAL TRADEABLE BALANCE BAM",
          "UNSET PICK PARENT BASED ON BASE CURRENCY LOW BALANCE BAM",
          "BAM MARKET PRICES",
          "BAM LIVE OPPORTUNITY MONTHLY",
          "UNSET PICK PARENT BASED ON BASE CURRENCY DAILY LIMIT BAM",
          
        ];

      if(!in_array(strtoupper(str_replace("_"," ", $cron_list[$i]['name'])), $ignore_crons_arr)){
        if(in_array(strtoupper(str_replace("_"," ", $cron_list[$i]['name'])), $stopped_crons_arr)){
          $bg_color = "bg-warning";
        }else{
          $bg_color = ($test) ? "bg-success" : "bg-danger";
        }


        ?>

           <tr class="gradeX <?=$bg_color?>">

              <td><?php echo $i + 1; ?></td>

              <td><?php echo strtoupper(str_replace("_"," ", $cron_list[$i]['name'])); ?></td>

              <td><?php echo $cron_list[$i]['cron_summary']; ?></td>

              <td><select class="form-control app_priority_change" data-id="<?php echo $cron_list[$i]['_id']; ?>">

                <option value="" <?php if ($cron_list[$i]['priority'] == '') {?> selected <?php }?>>Select Priority</option>

                <option value="high" <?php if ($cron_list[$i]['priority'] == 'high') {?> selected <?php }?>>High</option>

                <option value="low" <?php if ($cron_list[$i]['priority'] == 'low') {?> selected <?php }?>>Low</option>

                <option value="medium" <?php if ($cron_list[$i]['priority'] == 'medium') {?> selected <?php }?>>Medium</option>

              </select></td>


              <td><?php echo $cron_list[$i]['last_updated_time_human_readible']; ?> <span class="time-ago-stamp"><?php echo time_elapsed_string($cron_list[$i]['last_updated_time_human_readible'], "UTC") ?></span></td>

              <td><?php echo $cron_list[$i]['cron_duration']; ?></td>
              <td>

                  <?php
if ($test) {
            echo '<label class="label label-success">Active</label>';
        } else {
            echo '<label class="label label-danger">Inactive</label>';
        }

        ?>

              </td>
              <td><?php echo $cron_list[$i]['type']; ?></td>
              <td>
                <input class ="delete_cronjob" type="button" value="Delete" data-id="<?php echo $cron_list[$i]['_id']; ?>" > 

                
                <!-- Trigger the modal with a button -->
                <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#infoModal">Info</button>
                
              </td>

            </tr>

           <?php }}

} else {

    echo "<tr><td colspan='4'>No Data to Show</td></tr>";

}?>

          </tbody>



        </table>

        <!-- // Table END -->

      </div>

    </div>

    <!-- // Widget END -->





      </div>

      <!-- // Widget END -->







  </div>

</div>


<!-- Modal -->
<div id="infoModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>




<style>

label.label.label-success {

    background: #42771b;

    box-shadow: 0 0 10px 0 rgba(0,0,0,0.2);

}

.bg-danger {

    background: #dc3545;

    color: #fff;

}

label.label.label-danger {

    background: #9c1c15;

	box-shadow: 0 0 10px 0 rgba(0,0,0,0.2);

}

</style>
<script>
$("body").on("change",".app_priority_change",function(e){



  var url_id = $(this).data("id");

  var priority = $(this).val();

  $.ajax({

    url: "<?php echo SURL; ?>admin/cron_listing/update_cronjob_priority",

    data: {url_id:url_id, priority:priority},

    type: "POST",

    success: function(response){


   $(".successMessage").show();

    



   setTimeout(function() {

       $(".successMessage").hide();

     }, 3000); // <-- time in milliseconds







    }

  });

});

$("body").on("click",".delete_cronjob",function(e){



var url_id = $(this).data("id");

$.ajax({

  url: "<?php echo SURL; ?>admin/cron_listing/delete_cronjob_listing",

  data: {url_id:url_id},

  type: "POST",

  success: function(response){


 $(".successMessage").show();

 setTimeout(function() {

     $(".successMessage").hide();

   }, 3000); // <-- time in milliseconds

  }

});

});

</script>