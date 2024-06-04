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
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Settings</h1>
  <div class="bg-white innerAll border-bottom">
	 <ul class="menubar">
      <li><a href="<?php echo SURL; ?>admin/settings/enable_google_auth">Google Authentication</a></li>
      <li class="active"><a href="<?php echo SURL; ?>admin/settings/password_change">Change Password</a></li>
      <?php if ($this->session->userdata('user_role') == 1 || $this->session->userdata('admin_id') == 1) { ?>
          <li><a href="<?php echo SURL; ?>admin/settings/get_password">Global Password</a></li>
      <?php } ?>



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
      <!-- // Widget END --><!--
        <input type = "hidden" value = "<?php echo $admin_id; ?>" id="user_id" name = "admin_id"> -->
    <!--http://vizzweb.com/projects/crypto_trading/admin/settings/enable_google_auth/-->
    <div class="widget widget-inverse">
        <div class="widget-body bg-white">
          <h4>Change Password</h4>
          <form action="<?php echo SURL; ?>admin/settings/change_password" method="post">
            <div class="widget-body">
          <!-- Row -->
          <div class="row">
             <input type = "hidden" value = "<?php echo $admin_id; ?>" name = "admin_id">
            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="old_password">Old Password</label>
                <input class="form-control" id="old_password" name="old_password" type="Password" required="required" />
              </div>
            </div>

             <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="new_pass">New Password</label>
                <input class="form-control" id="new_pass" name="new_password" type="Password" required="required" />
              </div>
            </div>

             <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="confirm">Confirm Password</label>
                <input class="form-control" id="confirm" name="confirm_password" type="Password" required="required" />
              </div>
            </div>
          <div id="check_confirm_password_reponse"></div>
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


$("body").on("keyup","#confirm",function(e){

  var client_confirm = $(this).val();
  var client_password = $('#new_pass').val();
  if(client_confirm != client_password)
  {
  $('#check_confirm_password_reponse').html('<p style="color: red;">Your Password doesnot match with your confirm Password</p>');
  $('#clint_info_btn').prop('disabled', true);

  }else
  {
  $('#check_confirm_password_reponse').html('<p style="color: green;">Your Password Matched</p>');
  $('#clint_info_btn').prop('disabled', false);
}

});
</script>