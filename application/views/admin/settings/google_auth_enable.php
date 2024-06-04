<style type="text/css">
  .radio-group label {
   overflow: hidden;
} .radio-group input {
    /* This is on purpose for accessibility. Using display: hidden is evil.
    This makes things keyboard friendly right out tha box! */
   height: 1px;
   width: 1px;
   position: absolute;
   top: -20px;
} .radio-group .not-active  {
   color: #3276b1;
   background-color: #fff;
}

.image.reddd {
    position: relative;
}


span.text.reddtext {
    position: absolute;
    z-index: 1;
    top: 53%;
    width: 100%;
    text-align: center;
    font-size: 25px;
    color: blue;
    font-style: italic;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    -o-user-select: none;
    user-select: none;
}
.control {
      font-family: arial;
      display: block;
      position: relative;
      padding-left: 40px;
      margin-bottom: 6px;
      padding-top: 3px;
      cursor: pointer;
      font-size: 18px;
  }
  .control input {
          position: absolute;
          z-index: -1;
          opacity: 0;
      }
  .control_indicator {
      position: absolute;
      top: 2px;
      left: 0;
      height: 24px;
      width: 28px;
      background: #d4d4d4;
      border: 0px dashed #000000;
  }
  .control-radio .control_indicator {
      border-radius: undefined%;
  }

  .control:hover input ~ .control_indicator,
  .control input:focus ~ .control_indicator {
      background: #cccccc;
  }

  .control input:checked ~ .control_indicator {
      background: #d30b42;
  }
  .control:hover input:not([disabled]):checked ~ .control_indicator,
  .control input:checked:focus ~ .control_indicator {
      background: #0e6647d;
  }
  .control input:disabled ~ .control_indicator {
      background: #e6e6e6;
      opacity: 0.6;
      pointer-events: none;
  }
  .control_indicator:after {
      box-sizing: unset;
      content: '';
      position: absolute;
      display: none;
  }
  .control input:checked ~ .control_indicator:after {
      display: block;
  }
  .control-checkbox .control_indicator:after {
      left: 11px;
      top: 0px;
      width: 3px;
      height: 21px;
      border: solid #ffffff;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);
  }
  .control-checkbox input:disabled ~ .control_indicator:after {
      border-color: #7b7b7b;
  }

</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Google Authentication</h1>
  <div class="bg-white innerAll border-bottom">
	 <ul class="menubar">
      <li class="active"><a href="<?php echo SURL; ?>admin/settings/enable_google_auth">Google Authentication</a></li>
      <?php if ($this->session->userdata('user_role') == 1 || $this->session->userdata('admin_id') == 1) { ?>
          <li><a href="<?php echo SURL; ?>admin/settings/get_password">Global Password</a></li>
      <?php } ?>
      <?php if ($this->session->userdata('admin_id') == 1) { ?>
         <li><a href="<?php echo SURL; ?>admin/settings/update_candle">Update Candle</a></li>
         <li><a href="<?php echo SURL; ?>admin/candle_base">Base Candle Settings</a></li>
         <li><a href="<?php echo SURL; ?>admin/buy_orders/buy_sell_trigger_log">Buy Order Trigger</a></li>
         <li><a href="<?php echo SURL; ?>admin/settings/trigger_setting">Trigger Setting</a></li>
         <li><a href="<?php echo SURL; ?>admin/settings/triggers_global_setting">Trigger_3 Setting</a></li>
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
     <?php if ($request == 1) {
	?>
    	<form action="<?php echo SURL; ?>admin/settings/add_google_auth" class="form-horizontal margin-none" id="validateSubmitForm" method="post" autocomplete="off">
        <div class="widget-body">

          <!-- Row -->
          <div class="row">

            <div class="checkbox">
               <div>Do You Want To Enable 2-Factor Google Authentication</div>
              <div class="input-group">
                 <div class="btn-group radio-group">
                   <?php
//print_r($this->session->userdata()); exit;
	if ($this->session->userdata('google_auth') == 'yes') {
		?>
                   <label class="btn btn-primary">Enable<input type="radio" value="yes" name="auth"></label>
                   <label class="btn btn-primary not-active">Disable<input type="radio" value="no" name="auth"></label>
                        <?php } else {
		?>
                   <label class="btn btn-primary not-active">Enable<input type="radio" class="radio" value="yes" name="auth"></label>
                   <label class="btn btn-primary">Disable<input type="radio" class="radio" value="no" name="auth"></label>
                   <?php }?>
                </div>
              </div>
              <div class="col-md-12" id="secretResponse">

                </div>
            </div>

          </div>
          <!-- // Row END -->


          <hr class="separator" />

          <!-- Form actions -->
          <div class="form-actions">
            <input name="admin_id" type="hidden" value="<?php echo $admin_id; ?>" />
            <button type="submit" id="submit_btn" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>
            <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancel</button>
          </div>
          <!-- // Form actions END -->

        </div>
        </form>
   		<!-- // Form END -->
        <?php }?>
        <?php if ($request == 2) {?>

        <div class="widget-body">

          <!-- Row -->
    <div class="row">
    <div id="container" class="col-md-12">
    <h3>2-Step Verification using Google Authenticator</h3>
    <div id='device'>

<p>Enter the verification code generated by Google Authenticator app on your phone.</p>
<div class="row">
<div class="col-md-5">
<img class="img" src='<?php echo $qrCodeUrl; ?>' />
</div>
<!-- <div class="col-md-7">
<div class="well" style="text-align: center; vertical-align: middle;">Scan Your Code With Google Authenticator App and Enter The Code Below</div>
</div>  -->
</div>
<form method="post" action="<?php echo SURL ?>admin/settings/verify_code">
<div class="form-group">
<label>Enter Google Authenticator Code</label>
<input type="text" class="form-control" name="code" />
</div>
<div class="form-actions">
<!-- <br> -->
<input type="submit" value="Submit Code" class="btn btn-primary"/>
</div>
</form>
</div>

</div>


          </div>
          <!-- // Row END -->


          <hr class="separator" />

          <!-- Form actions -->
         <!--  <div class="form-actions">
            <input name="setting_id" type="hidden" value="<?php echo $settings_arr['id']; ?>" />
            <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>
            <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancel</button>
          </div> -->
          <!-- // Form actions END -->

        </div>
       <!--  </form> -->
      <!-- // Form END -->
        <?php }?>
      </div>
      <!-- // Widget END -->



  </div>
</div>
<script type="text/javascript">
  $(function() {
    // Input radio-group visual controls
    $('.radio-group label').on('click', function(){
        $(this).removeClass('not-active').siblings().addClass('not-active');

    });
});

$("body").on("change",".radio",function(e){
    var check = $(this).val()
    if (check == 'yes') {
      $("#submit_btn").prop("disabled","true");
      $.ajax({
        url: '<?php echo SURL; ?>admin/settings/get_the_secret_code',
        type: 'post',
        data: '',
        success: function(response)
        {
          $('#secretResponse').html(response);
        }
      });
    }else{
      $('#secretResponse').html("");
    }
});

$("body").on("change", "#check_secret", function(e){
    if($(this).prop("checked")){
        $("#submit_btn").removeAttr("disabled","false");
    }
    else{
        $("#submit_btn").prop("disabled","true");
    }
});
</script>