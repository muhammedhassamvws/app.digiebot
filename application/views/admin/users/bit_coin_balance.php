<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
<style>
.Input_text_s {
    /* display: inline; */
    position: relative;
}

.Input_text_s i {
    position: absolute;
    top: 33px;
    right: 10px;
}
/*** custom checkboxes ***/
@import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);
input[type=checkbox] { display:none; } /* to hide the checkbox itself */
input[type=checkbox] + label:before {
  font-family: FontAwesome;
  display: inline-block;
}
.custom_label {
    font-size: 25px;
    width: 100%;
    text-align: center;
}
input[type=checkbox] + label:before { content: "\f096"; } /* unchecked icon */
input[type=checkbox] + label:before { letter-spacing: 10px; } /* space between checkbox and label */

input[type=checkbox]:checked + label:before { content: "\f046"; } /* checked icon */
input[type=checkbox]:checked + label:before { letter-spacing: 5px; } /* allow space for check mark */

</style>

<style>
.loaderImage {
	float: left;
	width: 100%;
	position: relative;
}
.loaderimagbox {
	background: rgba(255, 255, 255, 0.78);
	position: absolute;
	z-index: 9;
	width: 100%;
	height: 100%;
	left: 0;
	top: 0;
	bottom: 0;
}
.loaderimagbox img {
	position: absolute;
	margin: auto;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	border-radius: 50%;
}
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Users</h1>
  <div class="innerAll bg-white border-bottom">
  <ul class="menubar">
    <li class="active"><a href="<?php echo SURL; ?>admin/users">Users</a></li>
	</ul>
  </div>
  <div class="innerAll spacing-x2">

      <div class="alert alert-success alert-dismissable successMessage" style="display:none;"><strong>Success !</strong> User Application mode updated Successfully</div>
      <div class="alert alert-danger error errormessage" style="display:none;"><strong>Oops !</strong> Something went wrong .</div>

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
      <?php $filter_user_data = $this->session->userdata('filter_user_data');?>
      <div class="widget widget-inverse">
         <div class="widget-body">
            <form method="POST" action="<?php echo SURL; ?>admin/users/index">
              <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter Query: </label>
                     <input id="filter_by_name" name="filter_by_name" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Search By Name, Email, Phone" value="<?=(!empty($filter_user_data['filter_by_name']) ? $filter_user_data['filter_by_name'] : "")?>">
                     <i class="fa fa-search glassflter" aria-hidden="true"></i>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter By User Id: </label>
                     <input id="filter_by_id" name="filter_by_id" type="text" class="form-control filter_by_id_margin_bottom_sm" placeholder="Search By User Id" value="<?=(!empty($filter_user_data['filter_by_id']) ? $filter_user_data['filter_by_id'] : "")?>">
                     <i class="fa fa-search glassflter1" aria-hidden="true"></i>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter IP: </label>
                     <select id="filter_by_ip" name="filter_by_ip" type="text" class="form-control filter_by_name_margin_bottom_sm">
                        <option value ="" <?=(($filter_user_data['filter_by_ip'] == "") ? "selected" : "")?>>Search By IP</option>
                        <option value ="3.227.143.115" <?=(($filter_user_data['filter_by_ip'] == "3.227.143.115") ? "selected" : "")?>>3.227.143.115</option>
                        <option value ="3.228.180.22" <?=(($filter_user_data['filter_by_ip'] == "3.228.180.22") ? "selected" : "")?>>3.228.180.22</option>
                        <option value ="3.226.226.217" <?=(($filter_user_data['filter_by_ip'] == "3.226.226.217") ? "selected" : "")?>>3.226.226.217</option>
                        <option value ="3.228.245.92" <?=(($filter_user_data['filter_by_ip'] == "3.228.245.92") ? "selected" : "")?>>3.228.245.92</option>
                        <option value ="50.28.36.48" <?=(($filter_user_data['filter_by_ip'] == "50.28.36.48") ? "selected" : "")?>>50.28.36.48</option>
                        <option value ="50.28.36.49" <?=(($filter_user_data['filter_by_ip'] == "50.28.36.49") ? "selected" : "")?>>50.28.36.49</option>
                     </select>
                  </div>
               </div>
               <!-- <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter Mode: </label>
                     <select id="filter_by_mode" name="filter_by_mode" type="text" class="form-control filter_by_name_margin_bottom_sm">
                        <option value="">Search By Mode</option>
                        <option value="live"<?=(($filter_user_data['filter_by_mode'] == "live") ? "selected" : "")?>>Live</option>
                        <option value="test"<?=(($filter_user_data['filter_by_mode'] == "test") ? "selected" : "")?>>Test</option>
                        <option value="both"<?=(($filter_user_data['filter_by_mode'] == "both") ? "selected" : "")?>>Both</option>
                     </select>
                  </div>
               </div> -->
               <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>From Date Range: </label>
                     <input id="filter_by_start_date" name="filter_by_start_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_start_date']) ? $filter_user_data['filter_by_start_date'] : "")?>">
                     <i class="glyphicon glyphicon-calendar"></i>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>To Date Range: </label>
                     <input id="filter_by_end_date" name="filter_by_end_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_end_date']) ? $filter_user_data['filter_by_end_date'] : "")?>">
                     <i class="glyphicon glyphicon-calendar"></i>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-1" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Special Role:</label>
                     <input id="box1" name="filter_special" value="yes" type="checkbox" <?=(($filter_user_data['filter_special'] == "yes") ? "checked" : "")?> />
                     <label class="custom_label" for="box1"></label>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-1" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Active:</label>
                     <input id="box3" name="filter_active" value="yes" type="checkbox" <?=(($filter_user_data['filter_active'] == "yes") ? "checked" : "")?> />
                     <label class="custom_label" for="box3"></label>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-1" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                      <label>InActive:</label>
                      <input id="box2" name="filter_inactive" value="yes" type="checkbox" <?=(($filter_user_data['filter_inactive'] == "yes") ? "checked" : "")?> />
                     <label class="custom_label" for="box2"></label>
                  </div>
               </div>
               <script type="text/javascript">
                   $(function () {
                       $('.datetime_picker').datetimepicker();
                   });
               </script>
               <style>
                  .Input_text_btn {padding: 25px 0 0;}
               </style>
               <div class="col-xs-12 col-sm-12 col-md-1" style="padding-bottom: 6px;">
                  <div class="Input_text_btn">
                     <label></label>
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-12" style="padding-bottom: 6px;">
                  <div class="Input_text_btn col-xs-4 col-sm-4 col-md-4">
                     <label></label>
                     <button class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
                     <a href="<?php echo SURL; ?>admin/bitcoin_report/reset_filters/all" class="btn btn-danger"><i class="fa fa-times-circle"></i>Reset</a>
                  </div>

                  <div class="Input_text_btn col-xs-6 col-sm-6 col-md-6">
                    &nbsp;&nbsp;
                  </div>

                

               </div>
            </div>
            </form>
          </div>
      </div>
    <!-- Widget -->
    <div class="widget widget-inverse">

      <div class="widget-body padding-bottom-none">
        <!-- Table -->
         <div class="loaderImage">
        <div class="loaderimagbox" style="display:none;"><img src="<?php echo SURL ?>assets/images/loader.gif" /></div>
        <table class="table table-bordered ">

          <!-- Table heading -->
          <thead>
            <tr>
              <th>Sr</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>UserName</th>
              <th>Email</th>
              <th>Mode</th>
              <th>Balance</th>     
            </tr>
          </thead>

          <tbody>
           <?php
if (count($users_arr) > 0) {
    for ($i = 0; $i < count($users_arr); $i++) {
        ?>
           <tr class="gradeX">
              <td><?php echo $i + 1; ?></td>
              <td><?php echo $users_arr[$i]['first_name']; ?></td>
              <td><?php echo $users_arr[$i]['last_name']; ?></td>
              <td><?php echo $users_arr[$i]['username']; ?></td>
              <td><?php echo $users_arr[$i]['email_address']; ?></td>
              <td><?php echo $users_arr[$i]['application_mode']; ?></td>
              <td><?php echo $users_arr[$i]['coin_balance']; ?></td>
            </tr>
           <?php }
}?>
          </tbody>

        </table>
        </div>
        <!-- // Table END -->


        <?php echo $pagination; ?>

      </div>
    </div>
    <!-- // Widget END -->

  </div>
</div>
<script>
$("body").on("click",".glassflter",function(e){
    var query = $("#filter_by_name").val();
    window.location.href = "<?php echo SURL; ?>/admin/users/?query="+query;
});

$("body").on("change",".app_mode_change",function(e){

	$(".loaderimagbox").show();
    var user_id = $(this).data("id");
    var mode = $(this).val();
    $.ajax({
      url: "<?php echo SURL; ?>admin/users/update_application_mode",
      data: {user_id:user_id, mode:mode},
      type: "POST",
      success: function(response){

	   $(".successMessage").show();
       $(".loaderimagbox").hide();

	   setTimeout(function() {
         $(".successMessage").hide();
       }, 3000); // <-- time in milliseconds



      }
    });
});


$("body").on("change",".update_trigger",function(){
  var user_id = $(this).data('id');
  var checked = $(this).is(':checked');
  if(checked) {
        var mode = "yes";
    }else{
      var mode = "no";
    }

    $(".loaderimagbox").show();

    $.ajax({
      url: "<?php echo SURL; ?>admin/users/update_trigger_mode",
      data: {user_id:user_id, mode:mode},
      type: "POST",
      success: function(response){

     $(".successMessage").show();
       $(".loaderimagbox").hide();

     setTimeout(function() {
         $(".successMessage").hide();
       }, 3000); // <-- time in milliseconds



      }
    });
});


$("body").on("change",".update_app",function(){
  var user_id = $(this).data('id');
  var checked = $(this).is(':checked');
  if(checked) {
        var mode = "yes";
    }else{
      var mode = "no";
    }

    $(".loaderimagbox").show();

    $.ajax({
      url: "<?php echo SURL; ?>admin/users/update_app_mode",
      data: {user_id:user_id, mode:mode},
      type: "POST",
      success: function(response){

     $(".successMessage").show();
       $(".loaderimagbox").hide();

     setTimeout(function() {
         $(".successMessage").hide();
       }, 3000); // <-- time in milliseconds
      }
    });
});
</script>
