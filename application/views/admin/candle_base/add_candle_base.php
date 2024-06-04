<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Settings</h1>
  <div class="bg-white innerAll border-bottom">
	<ul class="menubar">
      <li class=""><a href="<?php echo SURL;?>admin/candle_base">Candle Base</a></li>
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
      <!-- // Widget END --><!-- 
        <input type = "hidden" value = "<?php echo $admin_id; ?>" id="user_id" name = "admin_id"> -->
    <!--http://vizzweb.com/projects/crypto_trading/admin/settings/enable_google_auth/-->
    <div class="widget widget-inverse">
        <div class="widget-body bg-white">
          <h4>Add Base Candle</h4>
          <form action="<?php echo SURL; ?>admin/candle_base/save_candle_base" method="post">
            <div class="widget-body"> 
          <!-- Row -->
          <div class="row"> 
             <div class="col-md-12"> 
              <div class="form-group col-md-12">
                <label class="control-label" for="old_password">Select Coin</label>
                <select class="form-control" name="coins" id="coin" >
                  <?php 
                  foreach ($coins as $coin) {
                    ?>
                    <option value="<?php echo $coin['symbol'] ?>"><?php echo $coin['symbol']; ?></option>
                  <?php }
                  ?>
                </select>
              </div>
            </div>

            <div class="col-md-12"> 
              <div class="form-group col-md-12">
                <label class="control-label" for="old_password">Start Date</label>
                <input class="form-control datetime_picker" id="old_password" name="start_date" type="text" required="required" />
              </div>
            </div>

             <div class="col-md-12"> 
              <div class="form-group col-md-12">
                <label class="control-label" for="new_pass">End Date</label>
                <input class="form-control datetime_picker" id="new_pass" name="end_date" type="text" required="required" />
              </div>
            </div>
            <script type="text/javascript">
                $(function () {
                    $('.datetime_picker').datetimepicker();
                });
            </script>
             <div class="col-md-12"> 
              <div class="form-group col-md-12">
                <label class="control-label" for="confirm">Base Candle Value</label>
                <input class="form-control" id="confirm" name="base_candle" type="text" required="required" />
              </div>
            </div>

            <div class="col-md-12"> 
              <div class="form-group col-md-12">
                <label class="control-label" for="confirm">Interval</label>
                <input class="form-control" id="confirm" name="interval" type="text" value="1h" required="required" readonly="true" />
              </div>
            </div>
          <!-- // Row END -->
          
       
          <hr class="separator" />
          
          <!-- Form actions -->
          <div class="form-actions">
            <button type="submit" id="clint_info_btn" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
            <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancel</button>
          </div>
          <!-- // Form actions END --> 
          
        </div>
          </form>
        </div>
      </div>
  </div>
</div>
