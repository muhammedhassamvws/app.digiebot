<div id="content">
  <h1 class="content-heading bg-white border-bottom">Update Profile</h1>

  <div class="innerAll spacing-x2">


      <!-- Widget -->
      <div class="widget widget-inverse">
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



         <!-- Form -->
    	<form action="<?php echo SURL; ?>admin/dashboard/edit_profile_process" class="form-horizontal margin-none" id="validateSubmitForm" method="post" autocomplete="off" enctype="multipart/form-data">
        <div class="widget-body">



          <!-- Row -->
          <div class="row">

          <?php  if($user_arr['report_id']=='' || $user_arr['report_id']==0){ ?>
<!-- <div class="alert alert-warning alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>If you have digie Report acccount . Please insert the Digiebot report Id here . if you don't have any account create the digie Report account here
			 <b><a href="http://users.digiebot.com/signup" target="_blank">Click Here</a></b></div> -->

<?php }?>

          <!--<div class="col-md-12">

              <div class="form-group col-md-3">
                <label class="control-label" for="">Digiebot ID</label>
                <input class="form-control" type="text" required="required"  readonly="readonly" value="<?php echo $user_arr['id']; ?>" />
              </div>

              <?php  if($user_arr['kula_member_id']!='' && $user_arr['kula_member_id']!=0){ ?>
              <div class="form-group col-md-1"></div>
               <div class="form-group col-md-3">
                <label class="control-label" for="">Kula Member ID</label>
                <input class="form-control" type="text" required="required"  readonly="readonly" value="<?php echo $user_arr['kula_member_id']; ?>" />
              </div>

              <?php }?>


               <div class="form-group col-md-1"></div>
               <div class="form-group col-md-3">
                <label class="control-label" for="">Report ID</label>
    <input class="form-control" type="text" required="required" <?php echo ($user_arr['report_id']) ? 'readonly="readonly"' : ''; ?>  name="report_id" id="report_id" value="<?php echo $user_arr['report_id']; ?>" />
              </div>
            </div>-->




            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="first_name">First Name</label>
                <input class="form-control" id="first_name" name="first_name" type="text" required="required" value="<?php echo $user_arr['first_name']; ?>" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="last_name">Last Name</label>
                <input class="form-control" id="last_name" name="last_name" type="text" required="required" value="<?php echo $user_arr['last_name']; ?>" />
              </div>
            </div>


            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="username">User Name</label>
                <input class="form-control" id="username" name="username" type="text" required="required" value="<?php echo $user_arr['username']; ?>" />
              </div>
            </div>


            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="email_address">Email Address</label>
                <input class="form-control" id="email_address" name="email_address" type="text" readonly="readonly" value="<?php echo $user_arr['email_address']; ?>" />
              </div>
            </div>


            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="phone_number">Phone Number</label>
                <input class="form-control" id="phone_number" name="phone_number" type="text" required="required" value="<?php echo $user_arr['phone_number']; ?>" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group col-md-12">
              <?php if ($user_arr['profile_image'] != '') {?>
              <img src="<?php echo SURL . 'assets/profile_images/' . $user_arr['profile_image']; ?>" width="80" height="80"><br><br><br>
              <?php }?>
              <label for="upload">Upload Image</label>
              <input type="file" id="profile_image" name="profile_image">
              </div>
            </div>


             <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="password">Select Time Zone</label>
                <select class="form-control" name="timezone" required>
                  <option value="" >Select TimeZone</option>
                  <?php
foreach ($time_zone_arr as $key => $value) {
	?>
                            <?php $selected = (($user_arr['timezone'] == $value['zone_key']) ? 'selected' : '')?>
                           <option value="<?php echo $value['zone_key'] ?>" <?php echo $selected; ?> ><?php echo $value['zone_name']; ?></option>
                        <?php
}

?>
                </select>

              </div>
            </div>


          </div>
          <!-- // Row END -->


          <hr class="separator" />

          <!-- Form actions -->
          <div class="form-actions">
            <input name="user_id" type="hidden" value="<?php echo $user_arr['_id']; ?>" />
            <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>
            <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancel</button>
          </div>
          <!-- // Form actions END -->

        </div>
        </form>
   		<!-- // Form END -->


      </div>
      <!-- // Widget END -->



  </div>
</div>
