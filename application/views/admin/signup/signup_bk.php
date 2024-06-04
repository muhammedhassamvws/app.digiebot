<?php $form_data = $this->session->userdata('form-data'); ?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>

<!-- Meta -->
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />

<link rel="stylesheet" href="<?php echo CSS;?>admin/module.admin.page.login.min.css" />

<script src="<?php echo ASSETS;?>components/library/jquery/jquery.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/library/jquery/jquery-migrate.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/library/modernizr/modernizr.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/plugins/less-js/less.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/modules/admin/charts/flot/assets/lib/excanvas.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/plugins/browser/ie/ie.prototype.polyfill.js?v=v1.2.3"></script>


<!-- Form Validation -->
<script src="<?php echo ASSETS;?>components/modules/admin/forms/validator/assets/lib/jquery-validation/dist/jquery.validate.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/modules/admin/forms/validator/assets/custom/form-validator.init.js?v=v1.2.3"></script>
<script type="text/javascript" src="<?php echo ASSETS;?>js/jquery.validate.js"></script>
<!-- End Form Validation -->

<style type="text/css">
label.error{
  color: red;
}
</style>

</head>
<body class=" loginWrapper">

<div id="content">
  <h4 class="innerAll margin-none border-bottom text-center"><i class="fa fa-pencil"></i> Create a new Account</h4>

  <div class="login spacing-x2">
    <div class="placeholder text-center"><i class="fa fa-pencil"></i></div>
    <div class="col-sm-6 col-sm-offset-3">
      <div class="panel panel-default">
        <div class="panel-body innerAll">

            <?php
            if($this->session->flashdata('err_message')){
            ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('err_message'); ?></div>
            <?php
            }//end if($this->session->flashdata('err_message'))
            if($this->session->flashdata('ok_message')){
            ?>
            <div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('ok_message'); ?></div>
            <?php
            }//if($this->session->flashdata('ok_message'))
            ?>

            <form id="signup_frm" method="post" role="form" action="<?php echo SURL?>admin/signup/signup_process">
              <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Your first name" required="required" value="<?php echo $form_data['first_name']; ?>">
              </div>

              <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Your last name" required="required" value="<?php echo $form_data['last_name']; ?>">
              </div>

              <div class="form-group">
                <label for="last_name">User Name</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Your user name" value="<?php echo $form_data['username']; ?>">
              </div>
              <div class='error-message-username'></div>
              <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="email_address" name="email_address" placeholder="Enter email address" value="<?php echo $form_data['email_address']; ?>">
              </div>
              <div class='error-message-email'></div>
              <div class="form-group">
                <label for="exampleInputEmail1">Phone Number</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Enter phone number" value="<?php echo $form_data['phone_number']; ?>">
              </div>


              <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required="required">
              </div>

              <div class="form-group">
                <label for="exampleInputPassword1">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Retype Password" required="required">
              </div>


               <div class="form-group">
                <label for="exampleInputPassword1">Security Code</label>
                <input type="text" class="form-control" id="security_code" name="security_code" placeholder="Enter security code" required="required">
              </div>

               <div class="form-group">
                 <label class="control-label" for="password">Select Time Zone</label>
                 <select class="form-control" name="timezone" required>
                   <option value="" >Select TimeZone</option>
                   <?php
 foreach ($time_zone_arr as $key => $value) {
 	?>
                            <option value="<?php echo $value['zone_key'] ?>"><?php echo $value['zone_name']; ?></option>
                         <?php
 }

 ?>
                 </select>

               </div>


              <button type="submit" id="submit-btn" class="btn btn-primary btn-block">Create Account</button>
          </form>
          </div>
      </div>
    </div>
  </div>

<!-- Global -->
<script>
	var basePath = '',
		commonPath = '<?php echo ASSETS;?>',
		rootPath = '',
		DEV = false,
		componentsPath = '<?php echo ASSETS;?>components/';

	var primaryColor = '#cb4040',
		dangerColor = '#b55151',
		infoColor = '#466baf',
		successColor = '#8baf46',
		warningColor = '#ab7a4b',
		inverseColor = '#45484d';

	var themerPrimaryColor = primaryColor;
</script>
<script src="<?php echo ASSETS;?>components/library/bootstrap/js/bootstrap.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/plugins/nicescroll/jquery.nicescroll.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/plugins/breakpoints/breakpoints.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/core/js/animations.init.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/helpers/themer/assets/plugins/cookie/jquery.cookie.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/core/js/core.init.js?v=v1.2.3"></script>


<script type="text/javascript">
jQuery(document).ready(function() {

    // validate signup form on keyup and submit
  $("#signup_frm").validate({
      rules: {
        first_name : 'required',
        last_name : 'required',
        username: {
          required: true,
          minlength: 5,
          maxlength: 20
        },
        phone_number: {
          required: false,
          number: true
        },
        password: {
          required: true,
          minlength: 6
        },
        confirm_password: {
          required: true,
          equalTo: "#password"
        },
        email_address: {
          required: true,
          email: true
        }
            },
          messages: {
                first_name: "This field is required.",
        last_name : "This field is required.",
        username: {
          required: "This field is required.",
          minlength: "Username must consist of at least 5 characters",
          maxlength: "Username cannot me more than 20 characters"
        },
        password: {
          required: "Password cannot be empty.",
          minlength: "Password must be at least 6 characters long"
        },
        confirm_password: {
          required: "Confirm Password cannot be empty.",
          equalTo: "New Password must match with confirm password"
        },
        email_address: "Enter your valid email address",
            }
  });

});

$('body').on("blur","#username",function(e){
  var name = $(this).val();
  $.ajax({
  type: 'post',
  url: '<?php echo SURL; ?>admin/signup/check_user_info',
  data: {
   user_name:name,
  },
  success: function (response) {
      var resp = response.split('@@');
      $('.error-message-username').html(resp[0]);
      if (resp[1] == '0') {
        $('#submit-btn').prop("disabled","true");
      }else{
        $('#submit-btn').removeAttr("disabled");
      }
    }
  });
});

$('body').on("blur","#email_address",function(e){
var email = $(this).val();
$.ajax({
  type: 'post',
  url: '<?php echo SURL; ?>admin/signup/check_user_info',
  data: {
   user_email:email,
  },
  success: function (response) {
      var resp = response.split('@@');
      $('.error-message-email').html(resp[0]);
      if (resp[1] == '0') {
        $('#submit-btn').prop("disabled","true");
      }else{
        $('#submit-btn').removeAttr("disabled");
      }
    }
  });
});
</script>

</body>
</html>
