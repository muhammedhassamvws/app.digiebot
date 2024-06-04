<div id="content">
  <h1 class="content-heading bg-white border-bottom">Add User</h1>
  <div class="bg-white innerAll border-bottom">
	<ul class="menubar">
    	<li><a href="<?php echo SURL;?>admin/users">Users</a></li>
		<li class="active"><a href="<?php echo SURL;?>admin/users/add-user">Add User</a></li>
	</ul>
  </div>
  <div class="innerAll spacing-x2">


      <!-- Widget -->
      <div class="widget widget-inverse">
      <?php
	  if($this->session->flashdata('err_message')){
	  ?>
	  <div class="alert alert-danger"><?php echo $this->session->flashdata('err_message'); ?></div>
	  <?php
	  }
	  if($this->session->flashdata('ok_message')){
	  ?>
	  <div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('ok_message'); ?></div>
	  <?php
	  }
	  ?>

         <!-- Form -->
    	<form action="<?php echo SURL;?>admin/users/add_user_process" class="form-horizontal margin-none" id="validateSubmitForm" method="post" autocomplete="off">
        <div class="widget-body">

          <!-- Row -->
          <div class="row">

            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="first_name">First Name</label>
                <input class="form-control" id="first_name" name="first_name" type="text" required="required" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="last_name">Last Name</label>
                <input class="form-control" id="last_name" name="last_name" type="text" required="required" />
              </div>
            </div>


            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="username">User Name</label>
                <input class="form-control" id="username" name="username" type="text" required="required" />
              </div>
            </div>


            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="email_address">Email Address</label>
                <input class="form-control" id="email_address" name="email_address" type="text" required="required" />
              </div>
            </div>


            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="phone_number">Phone Number</label>
                <input class="form-control" id="phone_number" name="phone_number" type="text" required="required" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="application_mode">Application Mode</label>
                <select name="application_mode" class="form-control">
                  <option value="">Select Mode</option>
                  <option value="live" <?php if($user_arr['application_mode'] == "live") echo "selected" ?>>Live</option>
                  <option value="test" <?php if($user_arr['application_mode'] == "test") echo "selected" ?>>Test</option>
                  <option value="both" <?php if($user_arr['application_mode'] == "both") echo "selected" ?>>Both</option>
                </select>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="trading_ip">Trading IP</label>
                <select name="trading_ip" class="form-control">
                  <option value="">Select IP for Trading</option>
                  <?php 
                    foreach($allowed_ips as $key=>$value)
                    { ?>
                       <option value="<?php echo $key ?>"><?php echo $key ?></option> 
                    <?php }
                  ?>
             
                </select>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password" required="required" />
              </div>
            </div>


            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="confirm_password">Confirm Password</label>
                <input class="form-control" id="confirm_password" name="confirm_password" type="password" required="required" />
              </div>
            </div>


          </div>
          <!-- // Row END -->


          <hr class="separator" />

          <!-- Form actions -->
          <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
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
