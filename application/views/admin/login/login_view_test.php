<!doctype html>

<html lang="en">

    <head>

        <meta charset="utf-8">

        <title>Digiebot</title>

        <link rel="stylesheet" href="<?php echo NEW_ASSETS; ?>bootstrap/css/bootstrap.min.css">

        <link rel="stylesheet" href="<?php echo NEW_ASSETS; ?>css/style.css">

        <link rel="stylesheet" href="<?php echo NEW_ASSETS; ?>icons/font/flaticon.css">

            <meta name="google-site-verification" content="gFhfhx6jvmZRZQYRm_Ot1ledmN8bs5ReigRKk4x6JzE" />

    </head>

    <body>

        <div class="mainloginsignup">

            <div class="sidebar-overlay"></div>

                <div class="container-fluid h-100">

                    <div class="row justify-content-center h-100">

                        <div class="col-12 col-sm-12 col-lg-3 text-white">

                            <div class="row align-items-end h-50">

                                <div class="col-12">

                                    <h1 class="border border-light border-top-0 border-bottom-0 border-right-0 pl-3 mt-5 mb-0">Login</h1>

                                </div>

                            </div>

                            <div class="row align-items-end h-50">

                                <div class="col-12">

                                    <p>Don't have an account? <strong><a href="http://users.digiebot.com/signup" class="text-light" >Sign Up</a></strong></p>

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
                                        
                                            <form action="<?php echo SURL; ?>admin/login_test_ctrl/login_process_test" method="post">
                                             <div id="mess"> </div>
                                            
                                             
                                                <?php if($message!=''){?>
                                                
                                                <div class="alert alert-danger" ><?php echo $message;  ?> </div>

                                                <?php }?>

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

                                                            <label><span class="dg-icon mr-2"><i data-feather="user"></i></span>Username</label>

                                                            <input type="text" name="username" id="txt_username" class="form-control border border-top-0 border-left-0 border-right-0 btn-outline-light mb-5 pl-0 pr-0">

                                                        </div>

                                                    </div>

                                                    <div class="col-12">

                                                        <div class="form-group">

                                                            <label><span class="dg-icon mr-2"><i data-feather="lock"></i></span>Password</label>

                                                            <input type="password" name="password" id="txt_password" class="form-control border border-top-0 border-left-0 border-right-0 btn-outline-light mb-5 pl-0 pr-0">

                                                        </div>

                                                    </div>

                                                    <div class="col-12 text-right">

                                                        <div class="form-group forgetpass">

                                                            <p><a href="<?php echo SURL; ?>admin/login/forget_password" class="text-muted">Forgot Password?</a></p>

                                                        </div>

                                                    </div>

                                                    <div class="col-12">

                                                        <div class="form-group rememberme">

                                                            <label><input type="checkbox" class="mr-2"> Remember me</label>

                                                        </div>

                                                    </div>

                                                    <div class="col-12 text-center">

                                                        <div class="form-group">

                                                            <button class="btn btn-primary btn-xl btn-outline-primary" id="btn_submit">Login</button>

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
            $(document).ready(function() {
                $("#btn_submit").click(function (){
                    var username = $("#txt_username").val();
                    var password = $("#txt_password").val();

                   
                        if(username == ''){
                            $("#mess").addClass("alert alert-danger");
                            $("#mess").show().text("Plese Enter Username");
                            return false;
                        }else{
                            $("#mess").hide().text("");
                        }
                        
                        
                        if(password == ''){ 
                            $("#mess").addClass("alert alert-danger");
                            $("#mess").show().text("Enter password");
                            return false;
                        }else{
                        
                            $("#mess").hide().text("");
                        }
                         
                });
            });
        
       </script>                                        
        <script>

            feather.replace();

            </script>

       

    </body>

</html>

