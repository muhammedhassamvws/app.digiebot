<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Digiebot</title>
<link rel="stylesheet" href="<?php echo NEW_ASSETS; ?>bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo NEW_ASSETS; ?>css/style.css">
<link rel="stylesheet" href="<?php echo NEW_ASSETS; ?>icons/font/flaticon.css">
</head>
<body>
<div class="mainloginsignup">
    <div class="sidebar-overlay"></div>
    <div class="container-fluid h-100">
      <div class="row justify-content-center h-100">
            <div class="col-12 col-sm-12 col-lg-3 text-white">
              <div class="row align-items-end h-50">
                  <div class="col-12">
                      <h1 class="border border-light border-top-0 border-bottom-0 border-right-0 pl-3 mt-5 mb-0">Reset Password Update</h1>
                    </div>
                </div>
                <div class="row align-items-end h-50">
                    <div class="col-12">
                        <p>Already have an account? <strong><a href="<?php echo SURL; ?>admin/login" class="text-light">Login</a></strong></p>
                    </div>
          </div>
            </div>
            <div class="col-lg-3">
            	<div class="row align-items-center h-100">
            		<img class="digibot_img" src="<?php echo NEW_ASSETS; ?>/images/new_dashboard_digibot.png">
                </div>
            </div>
            <div class="col-12 col-sm-12 col-lg-5">
              <div class="row align-items-center h-100">
                  <div class="col-12">
                        <div class="col-12 bg-white card">


                            <div class="login-log text-center pt-5 pb-5">
                                <img class="img-fluid" src="<?php echo NEW_ASSETS; ?>images/login-logo.png">
                            </div>
                            <div class="digi-form card-body">
                                <form method="post" action="<?php echo SURL ?>admin/login/forget_password_process">
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

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <fieldset>Due to some security issues we need all of you to kindly update your passwords and make it some how secure.</fieldset>
                                                <label><span class="dg-icon mr-2"><i data-feather="mail"></i></span>Email</label>
                                                <input name="email" type="email" value="<?php echo  $email_address ?>" class="form-control border border-top-0 border-left-0 border-right-0 btn-outline-light mb-5 pl-0 pr-0">
                                            </div>
                                        </div>

                                        <div class="col-12 text-center">
                                            <div class="form-group">
                                                <button class="btn btn-primary btn-xl btn-outline-primary mb-3">Send</button><br>
                                                <small class="text-muted">This will send an email to reset password</small>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo NEW_ASSETS; ?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo NEW_ASSETS; ?>js/popper.min.js"></script>
<script type="text/javascript" src="<?php echo NEW_ASSETS; ?>bootstrap/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script src="<?php echo NEW_ASSETS; ?>js/custom.js"></script>
<script>
      feather.replace();
</script>
</body>
</html>
