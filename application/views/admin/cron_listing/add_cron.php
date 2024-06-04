<div id="content">
  <h1 class="content-heading bg-white border-bottom">Add Cron Job</h1>
  <div class="bg-white innerAll border-bottom">
	<ul class="menubar">
    	<li><a href="<?php echo SURL;?>admin/cron-listing">Cron Listing</a></li>
		<li class="active"><a href="<?php echo SURL;?>admin/cron-listing/add-cronjob">Add Cronjob</a></li>
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
    	<form action="<?php echo SURL;?>admin/cron_listing/add_cronjob_process" class="form-horizontal margin-none" method="post" autocomplete="off">
        <div class="widget-body">

          <!-- Row -->
          <div class="row">

            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="first_name">Add Cronjob</label>
                <input class="form-control" id="cron_name" name="cron_name" type="text" required="required" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="last_name">Duration</label>
                <input class="form-control" id="cron_duration" name="cron_duration" type="text" required="required" />
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
