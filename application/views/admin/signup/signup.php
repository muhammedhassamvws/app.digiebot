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
                    	<h1 class="border border-light border-top-0 border-bottom-0 border-right-0 pl-3 mt-5 mb-0">Sign Up</h1>
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
                                <form id="signup_frm" method="post" role="form" action="<?php echo SURL?>admin/signup/signup_process">
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label><span class="dg-icon mr-2"><i data-feather="user"></i></span>First Name</label>
                                                <input type="text" class="form-control border border-top-0 border-left-0 border-right-0 btn-outline-light mb-2 pl-0 pr-0" id="first_name" name="first_name" required>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label><span class="dg-icon mr-2"><i data-feather="user"></i></span>Last Name</label>
                                                <input type="text" class="form-control border border-top-0 border-left-0 border-right-0 btn-outline-light mb-2 pl-0 pr-0" id="last_name" name="last_name" required>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label><span class="dg-icon mr-2"><i data-feather="user"></i></span>Username</label>
                                                <input type="text" class="form-control border border-top-0 border-left-0 border-right-0 btn-outline-light mb-2 pl-0 pr-0" id="username" name="username" required>
                                            </div>
                                            <div class='error-message-username'></div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label><span class="dg-icon mr-2"><i data-feather="mail"></i></span>Email Address</label>
                                                <input type="email" class="form-control border border-top-0 border-left-0 border-right-0 btn-outline-light mb-2 pl-0 pr-0" id="email_address" name="email_address" required>
                                            </div>
                                            <div class='error-message-email'></div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label><span class="dg-icon mr-2"><i data-feather="phone"></i></span>Phone Number</label>
                                                <input type="tel" class="form-control border border-top-0 border-left-0 border-right-0 btn-outline-light mb-2 pl-0 pr-0" id="phone_number" name="phone_number" required>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label><span class="dg-icon mr-2"><i data-feather="alert-circle"></i></span>Security Code</label>
                                                <input type="text" class="form-control border border-top-0 border-left-0 border-right-0 btn-outline-light mb-2 pl-0 pr-0" id="security_code" name="security_code" required>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label><span class="dg-icon mr-2"><i data-feather="unlock"></i></span>Password</label>
                                                <input type="password" id="password" name="password" class="form-control border border-top-0 border-left-0 border-right-0 btn-outline-light mb-2 pl-0 pr-0" required>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label><span class="dg-icon mr-2"><i data-feather="lock"></i></span>Confirm Password</label>
                                                <input type="password" class="form-control border border-top-0 border-left-0 border-right-0 btn-outline-light mb-2 pl-0 pr-0" id="confirm_password" name="confirm_password" required>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-12">
                                            <div class="form-group">
                                                <label><span class="dg-icon mr-2"><i data-feather="clock"></i></span>Select Time Zone</label>
                                                <select class="form-control border border-top-0 border-left-0 border-right-0 btn-outline-light mb-5 pl-0 pr-0" name="timezone" required>
                                                  <option value="" >Select TimeZone</option>
                                                    <?php
                                                    foreach ($time_zone_arr as $key => $value) {
                                                     ?>
                                                       <option value="<?php echo $value['zone_key'] ?>"><?php echo $value['zone_name']; ?></option>
                                                    <?php
                                                      }
                                                    ?>
                                                  </select>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 text-center">
                                            <div class="form-group">
                                               	<button id="submit-btn" type="submit" class="btn btn-primary btn-xl btn-outline-primary">Sign Up</button>
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
<script type="text/javascript">
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
