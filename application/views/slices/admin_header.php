<?php
$coins_arr = get_coins();
$global_symbol = $this->session->userdata('global_symbol');
$global_mode = $this->session->userdata('app_mode');
$global_mode1 = $this->session->userdata('global_mode');


if($_SERVER['REMOTE_ADDR'] == '58.65.164.72'){
	
	 //echo 'global_mode '.$global_mode;
     //exit;	
}


?>
<link href="<?php echo ASSETS;?>cdn_links/bootstrap-toggle-master/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="<?php echo ASSETS;?>cdn_links/bootstrap-toggle-master/js/bootstrap-toggle.min.js"></script>
<style type="text/css">
  .toggle{
        top: 6px;
  }

  span.toggle-handle
  {
    background: darkgrey;
  }

.btn-switch {
  font-size: 1em;
  position: relative;
  display: inline-block;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
.btn-switch__radio {
  display: none;
}
.btn-switch__label {
  display: inline-block;
  padding: .75em .5em .75em .75em;
  vertical-align: top;
  font-size: 1em;
  font-weight: 700;
  line-height: 0.5;
  color: #666;
  cursor: pointer;
  transition: color .2s ease-in-out;
}
.btn-switch__label + .btn-switch__label {
  padding-right: .75em;
  padding-left: 0;
}
.btn-switch__txt {
  position: relative;
  z-index: 2;
  display: inline-block;
  min-width: 1.5em;
  opacity: 1;
  pointer-events: none;
  transition: opacity .2s ease-in-out;
}
.btn-switch__radio_no:checked ~ .btn-switch__label_yes .btn-switch__txt,
.btn-switch__radio_yes:checked ~ .btn-switch__label_no .btn-switch__txt {
  opacity: 0;
}
.btn-switch__label:before {
  content: "";
  position: absolute;
  z-index: -1;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background: #f0f0f0;
  border-radius: 1.5em;
  box-shadow: inset 0 .0715em .3572em rgba(43,43,43,.05);
  transition: background .2s ease-in-out;
}
.btn-switch__radio_yes:checked ~ .btn-switch__label:before {
  background: #6ad500;
}
.btn-switch__label_no:after {
  content: "";
  position: absolute;
  z-index: 2;
  top: .5em;
  bottom: .5em;
  left: .5em;
  width: 2em;
  background: #fff;
  border-radius: 1em;
  pointer-events: none;
  box-shadow: 0 .1429em .2143em rgba(43,43,43,.2), 0 .3572em .3572em rgba(43,43,43,.1);
  transition: left .2s ease-in-out, background .2s ease-in-out;
}
.btn-switch__radio_yes:checked ~ .btn-switch__label_no:after {
  left: calc(100% - 2.5em);
  background: #fff;
}
.btn-switch__radio_no:checked ~ .btn-switch__label_yes:before,
.btn-switch__radio_yes:checked ~ .btn-switch__label_no:before {
  z-index: 1;
}
.btn-switch__radio_yes:checked ~ .btn-switch__label_yes {
  color: #fff;
}
</style>
<div class="navbar navbar-fixed-top navbar-primary main" role="navigation">
  <div class="navbar-header pull-left">
    <div class="navbar-brand">
      <div class="pull-left">
      <a href="javascript:void(0);" class="toggle-button toggle-sidebar btn-navbar" id="user_leftmenu_setting"><i class="fa fa-bars"></i></a> </div>
      <a href="<?php echo SURL ?>admin/dashboard" class="appbrand innerL"><img src="<?php echo ASSETS; ?>images/digiebot_logo.png" style="width: 46%; margin-left: 4%; margin-bottom: 2%;"></a>
    </div>
  </div>
  <ul class="nav navbar-nav navbar-left">
    <li class="dropdown">
        <select class="form-control mycls" id="main_symbol" data-toggle="tooltip" data-placement="bottom" title="This is the global coin Once you Select this it will use in overall application">
          <?php
if (count($coins_arr) > 0) {
	for ($i = 0; $i < count($coins_arr); $i++) {?>
             <option value="<?php echo $coins_arr[$i]['symbol']; ?>" <?php if ($global_symbol == $coins_arr[$i]['symbol']) {?> selected <?php }?>><b><?php echo $coins_arr[$i]['symbol']; ?></b></option>
          <?php }
}
?>
        </select>
    </li>
   <!--  <li>
       <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom"></i>
    </li> -->


  </ul>


  <?php if ($this->session->userdata('check_api_settings') == 'no') {?>
  <ul class="nav navbar-nav hidden-xs">
    <li>
        <div class="alert alert-warning" style="padding: 9px;border-radius: 4px;margin-left: 110px;margin-top: 5px;">
          <strong>Attention!</strong> Your Binance Api is not set, please add from <a href="<?php echo SURL ?>admin/settings">here</a>
        </div>
    </li>
  </ul>
  <?php }?>


  <ul class="nav navbar-nav navbar-right hidden-xs">
    <?php
if ($global_mode == 'both') {	?>

        <li class="dropdown">
            <!-- <select class="form-control mycls" id="application_mode">
              <option value="live" <?php if ($global_mode1 == 'live') {?> selected <?php }?>><b>Live</b></option>
              <option value="test" <?php if ($global_mode1 == 'test') {?> selected <?php }?>><b>Test</b></option>
            </select> -->

            <!-- <input type="button" id="application_mode" data-toggle="toggle" data-onstyle="info" data-on="live"  data-off="test"> -->
            <?php if ($global_mode1 == 'live') {
		$btn_text = "Live";
		$btn_data = "test";
		$btn_class = "btn btn-success";
	} else {
		$btn_text = "Test";
		$btn_data = "live";
		$btn_class = "btn btn-default";
	}
	?>
            <div class="toggle" data-toggle="tooltip" data-placement="bottom" title="This is the global applicaton Mode, Switch Between the Live and Test Mode">

            <p class="btn-switch">
              <input type="radio" <?php if ($global_mode1 == 'live') {?> checked <?php }?> id ="yes" value="live" name="switch" class="btn-switch__radio btn-switch__radio_yes application_mode" />
              <input type="radio" <?php if ($global_mode1 == 'test') {?> checked <?php }?> id ="no" value="test" name="switch" class="btn-switch__radio btn-switch__radio_no application_mode" />
              <label for="yes" class="btn-switch__label btn-switch__label_yes"><span class="btn-switch__txt">Live</span></label>
              <label for="no" class="btn-switch__label btn-switch__label_no"><span class="btn-switch__txt">Test</span></label>
            </p>

        </li>
       <?php } elseif ($global_mode == 'test') {?>
          <li class="dropdown">
            <!-- <select class="form-control mycls" id="application_mode">
              <option value="test" <?php if ($global_mode1 == 'test') {?> selected <?php }?>><b>Test</b></option>
            </select> -->

            <div class="toggle">
              <!-- <input type="button" id="application_mode" data-value = "test" value="Test" class="btn btn-default"> -->
              <p class="btn-switch">
              <input type="radio" <?php if ($global_mode1 == 'live') {?> checked <?php }?> value="test" name="switch" class="btn-switch__radio btn-switch__radio_yes application_mode" />
              <input type="radio" <?php if ($global_mode1 == 'test') {?> checked <?php }?> value="test" name="switch" class="btn-switch__radio btn-switch__radio_no application_mode" />
              <label for="yes" class="btn-switch__label btn-switch__label_yes"><span class="btn-switch__txt">Live</span></label>
              <label for="no" class="btn-switch__label btn-switch__label_no"><span class="btn-switch__txt">Test</span></label>
            </p>
            </div>
        </li>
        <?php } elseif ($global_mode == 'live') {?>
          <li class="dropdown">
<!--             <select class="form-control mycls" id="application_mode">
              <option value="live" <?php if ($global_mode1 == 'live') {?> selected <?php }?>><b>Live</b></option>
            </select> -->

            <div class="toggle">
              <!-- <input type="button" id="application_mode" data-value = "live" value="Live" class="btn btn-success"> -->
               <p class="btn-switch">
              <input type="radio" <?php if ($global_mode1 == 'live') {?> checked <?php }?> value="live" name="switch" class="btn-switch__radio btn-switch__radio_yes application_mode" />
              <input type="radio" <?php if ($global_mode1 == 'test') {?> checked <?php }?> value="live" name="switch" class="btn-switch__radio btn-switch__radio_no application_mode" />
              <label for="yes" class="btn-switch__label btn-switch__label_yes"><span class="btn-switch__txt">Live</span></label>
              <label for="no" class="btn-switch__label btn-switch__label_no"><span class="btn-switch__txt">Test</span></label>
            </p>
            </div>
        </li>
        <?php }?>
    <li class="dropdown"> <a href="" class="dropdown-toggle user pull-right" data-toggle="dropdown">
    <?php if ($this->session->userdata('profile_image') != "") {?>
    <img src="<?php echo ASSETS; ?>profile_images/<?php echo $this->session->userdata('profile_image'); ?>" alt="" class="img-circle" width="39" height="39">
    <?php } else {?>
    <img src="<?php echo ASSETS; ?>images/empty_user.png" alt="" class="img-circle" width="39" height="39"/>
    <?php }?>
    <span class="hidden-xs hidden-sm"> &nbsp; <?php echo ucfirst($this->session->userdata('first_name') . " " . $this->session->userdata('last_name')); ?></span> <span class="caret"></span></a>
      <ul class="dropdown-menu list pull-right ">
        <li><a href="<?php echo SURL; ?>admin/dashboard/edit-profile">Edit Profile <i class="fa fa-pencil pull-right"></i></a></li>
         <li><a href="<?php echo SURL; ?>admin/dashboard/login_history">Login History <i class="fa fa-history"></i></a></li>
        <li><a href="<?php echo SURL; ?>admin/logout">Log out <i class="fa fa-sign-out pull-right"></i></a></li>
      </ul>
    </li>

   	<li>
   	<a href="<?php echo SURL; ?>admin/logout" class="menu-icon" title="Logout"><i class="fa fa-sign-out"></i></a>
   	</li>
  </ul>
</div>