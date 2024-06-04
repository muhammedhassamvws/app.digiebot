<!DOCTYPE html>
<html>
<head>
    <title>2-Step Verification using Google Authenticator</title>

    <style type="text/css">
        .app
        {
            width: 150px;
            padding-bottom: 10px;
        }
    </style>
   <link rel="stylesheet" href="<?php echo CSS; ?>admin/module.admin.page.login.min.css" />

<script src="<?php echo ASSETS; ?>components/library/jquery/jquery.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS; ?>components/library/jquery/jquery-migrate.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS; ?>components/library/modernizr/modernizr.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS; ?>components/plugins/less-js/less.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS; ?>components/modules/admin/charts/flot/assets/lib/excanvas.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS; ?>components/plugins/browser/ie/ie.prototype.polyfill.js?v=v1.2.3"></script>
</head>
<body class=" loginWrapper">
<div id="content">
    <h4 class="innerAll margin-none border-bottom text-center"><i class="fa fa-lock"></i> 2-Step Google Verfication</h4>
    <div class="login spacing-x2">
      	<div class="placeholder text-center"><i class="fa fa-lock"></i></div>
    </div>
</div>


<div id="content">
    <div class="login spacing-x2">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body innerAll">
                	<div class="row">
                    	<div class="col-xs-12">
                            <div class='google-images col-xs-12 col-sm-4 col-md-3'>
                                 <img class="img-responsive" src="/assets/Google/1.png">
                            </div>
                            <div class='google-images-text col-xs-12 col-sm-8 col-md-9'>
                                 <h3>STEP 1</h3>
                                 <p>If you lost Your google authenticator from the phone or you have new phone just install google authenticator from app store open the google authenticator page. Click on the add new (+) button from the bottom.</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body innerAll">
                	<div class="row">
                        <div class="col-xs-12">
                            <div class='google-images col-xs-12 col-sm-4 col-md-3'>
                                 <img class="img-responsive" src="/assets/Google/2.png">
                            </div>
                            <div class='google-images-text col-xs-12 col-sm-8 col-md-9'>
                                 <h3>STEP 2</h3>
                                 <p>After Clicking on the button you will get a menu.
                                    <br>
                                    <ol>
                                        <li>Scan a Barcode</li>
                                        <li>Enter Provided Key</li>
                                    </ol>
                                Click on the "Enter Provided Key" from the menu.
                                 </p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body innerAll">
                	<div class="row">
                        <div class="col-xs-12">
                            <div class='google-images col-xs-12 col-sm-4 col-md-3'>
                                 <img class="img-responsive" src="/assets/Google/3.png">
                            </div>
                            <div class='google-images-text col-xs-12 col-sm-8 col-md-9'>
                                 <h3>STEP 3</h3>
                                 <p>When you will enter in the menu, You will see the window which is prompting the account name and key. Enter the account name and the key provided to you on the time when you enabled the google authenticator</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body innerAll">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class='google-images col-xs-12 col-sm-4 col-md-3'>
                            </div>
                            <div class='google-images-text col-xs-12 col-sm-8 col-md-9'>
                                 <h3>Note</h3>
                                 <p>Other option provide the private key here in below field if your key is accurate you will get login, then re-enable your google authenticator. Otherwise <br>
                                    <a href="mailto:support@digiebot.com">Contact Support </a> <br>
                                    Contact Support at support@digiebot.com
                                 </p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<div id="content">
    <div class="login spacing-x2">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body innerAll">
					<?php
if ($this->session->flashdata('err_message')) {
    ?>
                        <div class="alert alert-danger"><?php echo $this->session->flashdata('err_message'); ?></div>
                        <?php
} //end if($this->session->flashdata('err_message'))
if ($this->session->flashdata('ok_message')) {
    ?>
                        <div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('ok_message'); ?></div>
                        <?php
} //if($this->session->flashdata('ok_message'))
?>

                    <form method="post" action="<?php echo SURL ?>admin/login/lost_phone_process">
                        <div class="form-group">
                        	<label>Enter Secret Code</label>
                        	<input type="text" name="code" class="form-control" />
                        </div>
                    	<button type="submit" class="btn btn-primary btn-block">Submit</button>
                    </form>
                    <!-- <div class="col-md-12 text-right">
                    <a href="<?php echo SURL; ?>admin/login/lost_phone"><small>Phone lost ? Dont Worry Click Here</small></a>
                    </div> -->
                </div>
                <!-- <div style="text-align:center">
                <h5>Get Google Authenticator on your phone</h5>
                <a href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank"><img class='app' src="<?php echo ASSETS; ?>images/appLinks/iphone.png" /></a>

                <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank"><img class="app" src="<?php echo ASSETS; ?>images/appLinks/android.png" /></a>
                </div> -->
            </div>
        </div>
    </div>
</div>

<!-- Global -->
<script>
    var basePath = '',
        commonPath = '<?php echo ASSETS; ?>',
        rootPath = '',
        DEV = false,
        componentsPath = '<?php echo ASSETS; ?>components/';

    var primaryColor = '#cb4040',
        dangerColor = '#b55151',
        infoColor = '#466baf',
        successColor = '#8baf46',
        warningColor = '#ab7a4b',
        inverseColor = '#45484d';

    var themerPrimaryColor = infoColor;
</script>
<script src="<?php echo ASSETS; ?>components/library/bootstrap/js/bootstrap.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS; ?>components/plugins/nicescroll/jquery.nicescroll.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS; ?>components/plugins/breakpoints/breakpoints.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS; ?>components/core/js/animations.init.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS; ?>components/helpers/themer/assets/plugins/cookie/jquery.cookie.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS; ?>components/core/js/core.init.js?v=v1.2.3"></script>
</body>
</html>