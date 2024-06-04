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
</head>
<body class=" loginWrapper">
<div id="content">
<h4 class="innerAll margin-none border-bottom text-center"><i class="fa fa-lock"></i> Login to your Account</h4>
<div class="login spacing-x2">
  <div class="placeholder text-center"><i class="fa fa-lock"></i></div>
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
      
        <form method="post" role="form" action="<?php echo SURL?>admin/login/update_password_process">
          <div class="form-group">
            <label for="exampleInputEmail1">New Password</label>
            <input class="form-control" placeholder="Password" id="new_password" name="new_password" type="password" autofocus>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Confirm Password</label>
            <input class="form-control" placeholder="Password" id="confirm_password" name="confirm_password" type="password" value="">
            <input class="form-control" placeholder="Password" name="email" type="hidden" value="<?php echo $email ?>">
          </div>
          <button type="submit" id="submit_btn" class="btn btn-primary btn-block">Change Password</button>
          <div id="checkbox">
            
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- <div class="col-sm-6 col-sm-offset-3 text-center">
    <a href="<?php echo SURL?>admin/signup" class="btn btn-info">Create a new account? <i class="fa fa-pencil"></i> </a>
  </div> -->
  
</div>

<script type="text/javascript">
  $("#confirm_password").keyup(function(){
    var pass = $("#new_password").val();
    var conf = $(this).val();
    $('#submit_btn').prop("disabled","true");
    if (pass == conf ) {
      var ht = '<div class="alert alert-success"><strong>Password Matched!</strong></div>';
      $('#submit_btn').removeAttr("disabled");
    }
    else
    {
      var ht = '<div class="alert alert-danger"><strong>Password Not Matched!</strong></div>';
      $('#submit_btn').prop("disabled","true");
    }
    $("#checkbox").html(ht);
  });
</script>
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
</body>
</html>