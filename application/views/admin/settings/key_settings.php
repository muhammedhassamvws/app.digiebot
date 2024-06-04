<?php

   //echo "<pre>";
   //print_r($apiKeyArray);
   //exit;

?>
<style type="text/css">
.radio-group label {
	overflow: hidden;
}
.radio-group input {
	/* This is on purpose for accessibility. Using display: hidden is evil.
    This makes things keyboard friendly right out tha box! */
   height: 1px;
	width: 1px;
	position: absolute;
	top: -20px;
}
.radio-group .not-active {
	color: #3276b1;
	background-color: #fff;
}

.p_erro {
    color: #f00;
        margin: 5px 5px 5px;
   
}

.p_success {
    color: #0C3;
        margin: 5px 5px 5px;
    
}
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Settings</h1>
  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
      <li class=""><a href="<?php echo SURL; ?>/admin/settings">Settings</a></li>
      <li><a href="<?php echo SURL; ?>admin/settings/enable_google_auth">Google Authentication</a></li>
      <li><a href="<?php echo SURL; ?>admin/settings/password_change">Change Password</a></li>
      <?php if ($this->session->userdata('user_role') == 1 || $this->session->userdata('admin_id') == 1) {?>
      <li><a href="<?php echo SURL; ?>admin/settings/update_candle">Update Candle</a></li>
      <li><a href="<?php echo SURL; ?>admin/candle_base">Base Candle Settings</a></li>
      <li><a href="<?php echo SURL; ?>admin/buy_orders/buy_sell_trigger_log">Buy Order Trigger</a></li>
      <li><a href="<?php echo SURL; ?>admin/settings/trigger_setting">Trigger Setting</a></li>
      <li><a href="<?php echo SURL; ?>admin/settings/triggers_global_setting">Trigger_3 Setting</a></li>
      <li class="active"><a href="<?php echo SURL; ?>/admin/key-settings">Api Key settings</a></li>
      <li><a href="<?php echo SURL; ?>admin/settings/on_off_trading">On-Off Trading</a></li>
      <?php
}
?>
    </ul>
  </div>
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
      
     <!-- <form action="<?php echo SURL; ?>admin/settings/updateKeySettingsProcess" class="form-horizontal margin-none" id="validateSubmitForm" method="post" autocomplete="off">-->
        <div class="widget-body"> 
        
      
        
        <div class="widget-body">
        <h4>Update Key and secret of your Binance API ( Admins Only ) to grab data from Binance exchnage </h4>
      </div>
      
        <p class="p_erro js_err" style="display:none;"></p>
      <p class="p_success " style="display:none;"></p>
          
          <!-- Row -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="api_key_tr">API KEY</label>
                <input type = "hidden" value = "<?php echo $admin_id; ?>" id="user_id" name = "admin_id">
                <input class="form-control" id="api_key_tr" name="api_key_tr" type="text" required="required" value="<?php echo $apiKeyArray[0]->api_key_tr; ?>" /><span style="color: #F00;" id="api_key_tr" ></span>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="api_secret_tr">API SECRET</label>
                <input class="form-control" id="api_secret_tr" name="api_secret_tr" type="text" required="required" value="<?php echo $apiKeyArray[0]->api_secret_tr; ?>" /><span style="color: #F00;" id="api_secret_tr" ></span>
              </div>
            </div>
          </div>
          <!-- // Row END -->
          
          <hr class="separator" />
          
          <!-- Form actions -->
          <div class="form-actions">
            <a type="submit" class="btn btn-primary keysettings"  ><i class="fa fa-check-circle"></i> Save</a>
            <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancel</button>
          </div>
          <!-- // Form actions END --> 
          
        </div>
     <!-- </form>-->
      <!-- // Form END --> 
      
    </div>
    <!-- // Widget END --> 
    <!--http://vizzweb.com/projects/crypto_trading/admin/settings/enable_google_auth/-->
    <?php
if ($this->session->userdata('check_api_settings') == 'yes') {
	?>
    <div class="widget widget-inverse">
      <div class="widget-body bg-white">
        <h4>Sell My Last Trade On Available Balance</h4>
        <div class="checkbox">
          <div class="input-group">
            <div class="btn-group radio-group">
              <label class="btn btn-primary <?= (($settings_arr['auto_sell_enable'] == "yes") ? "":"not-active") ?>">Yes
                <input type="radio" value="yes" <?= (($settings_arr['auto_sell_enable'] == 'yes') ? "checked='checked'":"") ?> name="sell" id="radio">
              </label>
              <label class="btn btn-primary <?= (($settings_arr['auto_sell_enable'] == 'no') ? "":"not-active") ?>">No
                <input type="radio" value="no" <?= (($settings_arr['auto_sell_enable'] == 'no') ? "checked='checked'":"") ?> name="sell" id="radio">
              </label>
            </div>
          </div>
        </div>
        <div class="alert alert-success alert-dismissable" style="display: none;" id="successdiv"></div>
      </div>
    </div>
    <?php }?>
  </div>
</div>
<script type="text/javascript">
  $(function() {
    // Input radio-group visual controls
    $('.radio-group label').on('click', function(){
        $(this).removeClass('not-active').siblings().addClass('not-active');
    });
});

$(function(){
  $('input[type="radio"]').click(function(){
    if ($(this).is(':checked'))
    {
      var checked = $(this).val();
      var admin = $('#user_id').val();
      $.ajax({
        url: "<?php echo SURL ?>admin/settings/auto_sell_enable",
        type: "POST",
        data:{enable: checked, admin:admin},
        success: function()
        {
          $('#successdiv').html('Auto Sell Setting Enabled: '+ checked);
          $('#successdiv').show();
        }
      });
    }
  });
});


jQuery("body").on("click",".keysettings",function(e){ 
        
		e.preventDefault();
        var api_key_tr     = jQuery("#api_key_tr").val();
		var api_secret_tr  = jQuery("#api_secret_tr").val();
		
		if(api_key_tr == ''){
			jQuery("#api_key_tr").css("border-bottom-color","#f00");
			jQuery(".js_err").show().text("Plese Enter Api Key");
			return false;
		}else{
			jQuery("#api_key_tr").css("border-bottom-color","#0f0");
			jQuery(".js_err").hide().text("");
		}
		if( api_secret_tr == '' ){ 
			jQuery("#api_secret_tr").css("border-bottom-color","#f00");
			jQuery(".js_err").show().text("Plese Enter Password");
			return false;
		}else{
			jQuery("#api_secret_tr").css("border-bottom-color","#0f0");
			jQuery(".js_err").hide().text("");
		}
		
		  $.ajax({
                url         : '<?php echo SURL; ?>admin/settings/updateKeySettingsProcess',     // point to server-side PHP script 
                type        : 'POST',
                data        : {api_key_tr:api_key_tr,api_secret_tr:api_secret_tr},                         
                success     : function(output){
				   var obj = $.parseJSON( output);
				   //jQuery(".gearload").hide();
				   if(obj.success==true){	
				            jQuery(".p_success").show();
							jQuery(".p_erro").hide();
							jQuery(".p_success").html('');
							jQuery(".p_success").html(obj.message);
				   }
				   if(obj.success==false){	
					        jQuery(".p_success").hide();
							jQuery(".p_erro").show();
							jQuery(".p_erro").html('');
						    jQuery(".p_erro").html(obj.message);
				   }
                }
        });	
});

</script> 
