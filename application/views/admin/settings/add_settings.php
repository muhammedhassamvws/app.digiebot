<?php
// if ($_SERVER['REMOTE_ADDR'] == '101.50.127.14') {
//   echo "<pre>";
//   echo $settings_arr['api_key'];
//   print_r($settings_arr);
//   exit;
// }
?>

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

.field-icon {
  float: right;
  margin-left: -25px;
  margin-top: -25px;
  position: relative;
  z-index: 2;
}

.container{
  padding-top:50px;
  margin: auto;
}
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Settings</h1>
  <div class="bg-white innerAll border-bottom">
	<ul class="menubar">
    	
      <li><a href="<?php echo SURL; ?>admin/settings/enable_google_auth">Google Authentication</a></li>
      <li><a href="<?php echo SURL; ?>admin/settings/password_change">Change Password</a></li>
      <?php if ($this->session->userdata('user_role') == 1) {
    ?>
         <li><a href="<?php echo SURL; ?>admin/settings/get_password">Global Password</a></li>
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
         <div class="widget-body">
            <h4>Add Your Binance API Credentials Here (Live)</h4>
        </div>
        <div class="widget-body">



        <div class="row" id="api_row" style="display: none;">
          <form class="form-horizontal margin-none" id="validateSubmitForm" method="post" autocomplete="off">
            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="api_key">API KEY</label>
                <input type = "hidden" value = "<?php echo $admin_id; ?>" id="user_id" name = "admin_id">
                <input class="form-control" id="api_key" name="api_key" type="password" required="required" value="" />
              <span toggle="#api_key" class="fa fa-fw fa-eye field-icon toggle-password"></span>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="api_secret">API SECRET</label>
                <input class="form-control" id="api_secret" name="api_secret" type="password" required="required" value="" />
              <span toggle="#api_secret" class="fa fa-fw fa-eye field-icon toggle-password"></span>

              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="alert alert-info"><strong>Note: </strong> If you are not able to add the API credentials, please go to binance and get the latest API credentials  </label>
              </div>
            </div>


            <!-- // Row END -->
            <hr class="separator" />

            <!-- Form actions -->
            <div class="form-actions">
              <button type="button" class="btn btn-primary save_setting"><i class="fa fa-check-circle"></i> Save</button>
              <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancel</button>
            </div>
          </form>
        </div>
          <!-- // Form actions END -->
        <div class="row" id="api_display">

                  <div class="col-md-12">
                    <div class="form-group col-md-12">
                      <label class="control-label" for="api_key">API KEY</label>
                      <input type = "hidden" value = "<?php echo $admin_id; ?>" id="user_id" name = "admin_id">
                      <br>
                      <span class="form-control" style="width: 50%;"><?php
$str = $settings_arr['api_key'];
$len = strlen($str);
echo substr($str, 0, 5) . str_repeat('*', $len - 4) . substr($str, $len - 5, 5);
$settings_arr['api_key'];?>
                    </span>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group col-md-12">
                      <label class="control-label" for="api_secret">API SECRET</label>
                      <br><span class="form-control" style="width: 50%;">
                      <?php
$str = $settings_arr['api_secret'];
$len = strlen($str);
echo substr($str, 0, 5) . str_repeat('*', $len - 4) . substr($str, $len - 5, 5);
$settings_arr['api_secret'];?></span>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group col-md-12">
                      <label class="alert alert-info"><strong>Note: </strong> If you are not able to add the API credentials, please go to binance and get the latest API credentials  </label>
                    </div>
                  </div>

                 <!-- <div>
                    <script src='https://www.google.com/recaptcha/api.js'></script>
                    <div class="g-recaptcha" data-sitekey="6Legd5EUAAAAAOaE3xs2zgAv_hymC7ASWIbhE_ZU"></div>
                 </div> -->

                  <a href="javascript:void(0);" class="btn btn-primary update_settings"><i class="fa fa-check-circle"></i>Edit Now</a>
                </div>

              </div>

         		<!-- // Form END -->


      </div>
      <!-- // Widget END -->
     <!--http://vizzweb.com/projects/crypto_trading/admin/settings/enable_google_auth/-->
      <?php
if ($this->session->userdata('check_api_settings') == 'yes') {
    ?>
    <!-- <div class="widget widget-inverse">
        <div class="widget-body bg-white">
          <h4>Sell My Last Trade On Available Balance</h4>
            <div class="checkbox">
              <div class="input-group">
                <div class="btn-group radio-group">
                   <label class="btn btn-primary <?=(($settings_arr['auto_sell_enable'] == "yes") ? "" : "not-active")?>">Yes<input type="radio" value="yes" <?=(($settings_arr['auto_sell_enable'] == 'yes') ? "checked='checked'" : "")?> name="sell" id="radio"></label>
                   <label class="btn btn-primary <?=(($settings_arr['auto_sell_enable'] == 'no') ? "" : "not-active")?>">No<input type="radio" value="no" <?=(($settings_arr['auto_sell_enable'] == 'no') ? "checked='checked'" : "")?> name="sell" id="radio"></label>
                </div>
              </div>
            </div>
            <div class="alert alert-success alert-dismissable" style="display: none;" id="successdiv"></div>
        </div>
      </div> -->
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

$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
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

$("body").on("click",".save_setting",function(){
  var api_key = $("#api_key").val();
  var api_secret = $("#api_secret").val()

  if (api_key.length == 0 || api_secret.length == 0) {
    $.alert({
      title:"Sorry!",
      content: "Sorry! API Key or API Secret Cannot be empty"
    });
    return false;
  }
  $.confirm({
    title: 'Stop! Prove Your Identity',
    content: '' +
    '<form class="formName">' +
    '<div class="form-group">' +
    '<label>Enter Your Password</label>' +
    '<input type="password" placeholder="Enter Your Password" class="password form-control" required />' +
    '</div>' +
    '</form>',
    buttons: {
        formSubmit: {
            text: 'Submit',
            btnClass: 'btn-blue',
            action: function () {
                var password = this.$content.find('.password').val();
                $.ajax({
                    url: "<?php echo SURL; ?>admin/settings/validate_password",
                    type: "POST",
                    data: {password:password},
                    success: function(resp){
                      $.alert(resp);
                      if (resp == "Sorry! Your Password is not matched! Please Enter the valid Password") {
                        return false;
                      }
                      $.ajax({
                          url: "<?php echo SURL; ?>admin/settings/validate_api",
                          type: "POST",
                          data: {api_key:api_key, api_secret:api_secret},
                          success: function(resp){
                            $.alert(resp);
                            $("#api_row").hide();
                            $("#api_display").show();
                          }
                      })
                    }
                })
            }
        },
        cancel: function () {
            //close
        },
    },
});
});


$("body").on("click",".update_settings",function(e){
    $.confirm({
    title: 'Stop! Prove Your Identity',
    content: '' +
    '<form action="<?php echo SURL; ?>admin/settings/validate_password" class="formName">' +
    '<div class="form-group">' +
    '<label>Enter Your Password</label>' +
    '<input type="password" placeholder="Enter Your Password" class="password form-control" required />' +
    '</div>' +
    '</form>',
    buttons: {
        formSubmit: {
            text: 'Submit',
            btnClass: 'btn-blue',
            action: function () {
                var password = this.$content.find('.password').val();
                $.ajax({
                    url: "<?php echo SURL; ?>admin/settings/validate_password",
                    type: "POST",
                    data: {password:password},
                    success: function(resp){
                      $.alert(resp);
                      if (resp == "Sorry! Your Password is not matched! Please Enter the valid Password") {
                        return false;
                      }
                      $.alert("Password is successfully Matched")
                      $("#api_row").show();
                      $("#api_display").hide();
                    }
                })
            }
        },
        cancel: function () {
            //close
        },
    },
});
});

$(".toggle-password").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
</script>
