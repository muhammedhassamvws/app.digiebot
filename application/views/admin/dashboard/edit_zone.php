<link href="<?php echo base_url(); ?>assetest_one/daterangepicker.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assetest_one/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assetest_one/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assetest_one/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assetest_one/clockface.css" rel="stylesheet" type="text/css" />

<div id="content">
  <h1 class="content-heading bg-white border-bottom">Edit Target Zone</h1>
  <div class="bg-white innerAll border-bottom">
	<ul class="menubar">
    	<li><a href="<?php echo SURL; ?>admin/dashboard/zone-listing">Target Zones Listing</a></li>
      <li class="active"><a href="#">Edit Target Zone</a></li>
	</ul>
  </div>
  <div class="innerAll spacing-x2">

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


    <div class="widget widget-inverse"> 
    
        <div class="widget widget-inverse"> 
            <form class="form-horizontal margin-none" method="post" action="<?php echo SURL?>admin/dashboard/edit-zone-process" novalidate="novalidate">
            <div class="widget-body"> 
              
              <!-- Row -->

              <div class="row">

                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Coin</label>
                    <select name="coin" class="form-control">
                      <option value="">Select Coin</option>
                      <?php 
                       if(count($coins_arr)>0){
                       for($i=0; $i<count($coins_arr); $i++){
                       ?> 
                      <option value="<?php echo $coins_arr[$i]['symbol'];?>" <?php if($zone_arr['coin'] == $coins_arr[$i]['symbol']){?> selected <?php } ?>><?php echo $coins_arr[$i]['symbol'];?></option>
                      <?php }
                      } ?>
                    </select>
                  </div>
                </div>

              </div>

              <div class="row">
                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Enter Start Value</label>
                    <input type="text" name="start_value" value="<?php echo $zone_arr['start_value']; ?>" required="required" class="form-control">
                  </div>
                </div> 
                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Enter End value</label>
                    <input type="text" name="end_value" value="<?php echo $zone_arr['end_value']; ?>" required="required" class="form-control">
                  </div>
                </div>

                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Enter Start Date</label>
                   <!--  <input type="text" name="start_date" required="required" class="form-control"> -->

                    <div class="input-group date form_meridian_datetime" data-date="">
                      <input type="text" size="16" class="form-control" value="<?php echo $zone_arr['start_date']; ?>" name="start_date">
                      <span class="input-group-btn">
                      <button class="btn default date-reset" type="button">
                      <i class="fa fa-times"></i>
                      </button>
                      <button class="btn default date-set" type="button">
                          <i class="fa fa-calendar"></i>
                      </button>
                      </span>
                    </div>

                  </div>
                </div> 

                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Enter End Date</label>
                    <div class="input-group date form_meridian_datetime" data-date="">
                      <input type="text" size="16" class="form-control" value="<?php echo $zone_arr['end_date']; ?>" name="end_date">
                      <span class="input-group-btn">
                      <button class="btn default date-reset" type="button">
                      <i class="fa fa-times"></i>
                      </button>
                      <button class="btn default date-set" type="button">
                          <i class="fa fa-calendar"></i>
                      </button>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Type</label>
                    <select name="type" class="form-control">
                      <option value="sell" <?php if($zone_arr['type'] =='sell'){ ?> selected <?php } ?>>Sell</option>
                      <option value="buy" <?php if($zone_arr['type'] =='buy'){ ?> selected <?php } ?>>Buy</option>
                    </select>
                  </div>
                </div>  

                 <!-- <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                   
                    <label class="control-label">Unit Value</label>
                    <input type="text" name="unit_value" value="<?php echo $zone_arr['unit_value']; ?>" required="required" class="form-control">
                 
                  </div>
                </div>  -->
               
                
              </div>
              <!-- // Row END -->
              <hr class="separator">
              <input type="hidden" name="id" value="<?php echo $zone_arr['_id']; ?>">
              
              <!-- Form actions -->
              <div class="form-actions">
                <button class="btn btn-success" type="submit"><i class="fa fa-check-circle"></i> Update </button>
              </div>
              <!-- // Form actions END --> 
              
            </div>
            </form>
        </div>    
     
    
  </div>
</div>


<script src="<?php echo base_url(); ?>assetest_one/jquery.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>assetest_one/bootstrap.min.js" type="text/javascript"></script>

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo base_url(); ?>assetest_one//moment.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assetest_one/daterangepicker.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>assetest_one/bootstrap-datepicker.min.js" type="text/javascript">

</script>
<script src="<?php echo base_url(); ?>assetest_one/bootstrap-timepicker.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>assetest_one/bootstrap-datetimepicker.min.js" type="text/javascript">

</script>
<script src="<?php echo base_url(); ?>assetest_one/clockface.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script src="<?php echo base_url(); ?>assetest_one/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assetest_one/components-date-time-pickers.min.js" type="text/javascript"></script>

