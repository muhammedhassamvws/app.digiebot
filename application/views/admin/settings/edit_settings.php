<div id="content">
  <h1 class="content-heading bg-white border-bottom">Edit coin</h1>
  <div class="bg-white innerAll border-bottom">
	<ul class="menubar">
    	<li><a href="<?php echo SURL;?>admin/settings">Settings</a></li>
		<li class="active"><a href="<?php echo SURL;?>admin/settings/edit-settings/<?php echo $setting_id;?>">Edit Settings</a></li>
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
    	<form action="<?php echo SURL;?>admin/settings/edit_settings_process" class="form-horizontal margin-none" id="validateSubmitForm" method="post" autocomplete="off">
        <div class="widget-body"> 
          
          <!-- Row -->
          <div class="row"> 
            
            <div class="col-md-12"> 
              <div class="form-group col-md-12">
                <label class="control-label" for="api_key">API KEY</label>
                <input class="form-control" id="api_key" name="api_key" type="text" required="required" value="<?php echo $settings_arr['api_key']; ?>" />
              </div>
            </div>
            
            <div class="col-md-12"> 
              <div class="form-group col-md-12">
                <label class="control-label" for="api_secret">API SECRET</label>
                <input class="form-control" id="api_secret" name="api_secret" type="password" required="required" value="<?php echo $settings_arr['api_secret']; ?>" />
                
              </div>
            </div>
          
          </div>
          <!-- // Row END -->
          
       
          <hr class="separator" />
          
          <!-- Form actions -->
          <div class="form-actions">
            <input name="setting_id" type="hidden" value="<?php echo $settings_arr['id']; ?>" />
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
