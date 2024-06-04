<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<link rel="stylesheet" type="text/css" href="<?php echo ASSETS; ?>buy_orders/assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ASSETS; ?>buy_orders/assets/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ASSETS; ?>buy_orders/assets/pictoicons/css/picto.css">
<link rel="stylesheet" type="text/css" href="<?php echo ASSETS; ?>buy_orders/assets/css/style.css">
<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<link rel="stylesheet" href="https://app.digiebot.com/assets/jquery_confirm/jquery-confirm.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo ASSETS; ?>introduction/introjs.css">
<script type="text/javascript" src="<?php echo ASSETS; ?>introduction/intro.js"></script>
<!---------------- CUSTOM STYLE ------------------------->
<style type="text/css">

.button-wrap {
  position: relative;
  text-align: center;
  top: 50%;
  margin-top: 0;
}
@media (max-width: 40em) {
  .button-wrap {
    margin-top: -1.5em;
  }
}

.button-label {
  display: inline-block;
  padding: 1em 2em;
  margin: 0.5em;
  cursor: pointer;
  color: #292929;
  border-radius: 0.25em;
  background: #efefef;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2), inset 0 -3px 0 rgba(0, 0, 0, 0.22);
  transition: 0.3s;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
}
.button-label h1 {
  font-size: 1em;
  font-family: "Lato", sans-serif;
}
.button-label:hover {
  background: #d6d6d6;
  color: #101010;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2), inset 0 -3px 0 rgba(0, 0, 0, 0.32);
}
.button-label:active {
  -webkit-transform: translateY(2px);
          transform: translateY(2px);
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2), inset 0px -1px 0 rgba(0, 0, 0, 0.22);
}
@media (max-width: 40em) {
  .button-label {
    padding: 0em 1em 3px;
    margin: 0.25em;
  }
}

#yes-button:checked + .button-label {
  background: #2ECC71;
  color: #efefef;
}
#yes-button:checked + .button-label:hover {
  background: #29b765;
  color: #e2e2e2;
}

#no-button:checked + .button-label {
  background: #D91E18;
  color: #efefef;
}
#no-button:checked + .button-label:hover {
  background: #c21b15;
  color: #e2e2e2;
}

#maybe-button:checked + .button-label {
  background: #4183D7;
  color: #efefef;
}
#maybe-button:checked + .button-label:hover {
  background: #2c75d2;
  color: #e2e2e2;
}

.hidden {
  display: none;
}

</style>
<!---------------- CUSTOM STYLE ------------------------->
<style type="text/css">
.text-danger {
	color: #ef4848 !important;
}
.tab_pane {
	display: none;
}
.tab_pane.active {
	display: block;
}
.modal-dialog {
	height: 80% !important;
	padding-top: 10%;
}
.custom_link {
	float: right;
	display: inline-block;
	padding: 6px 12px;
	margin-bottom: 0;
	font-size: 14px;
	font-weight: 400;
	line-height: 1.42857143;
	text-align: center;
	white-space: nowrap;
	vertical-align: middle;
	text-decoration: none;
	-ms-touch-action: manipulation;
	touch-action: manipulation;
	cursor: pointer;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	background-image: none;
	border: 1px solid transparent;
	border-radius: 4px;
	background: red;
	color: white;
}

.btn-circle-round {
    background: transparent;
    border: none;
}
.col-2-5 {
    float: left;
    width: 20%;
    padding: 0 15px;
}
</style>
</head>
<body>
<?php
$coins_arr = get_coins();
$global_symbol = $this->session->userdata('global_symbol');
$global_mode = $this->session->userdata('app_mode');
$global_mode1 = $this->session->userdata('global_mode');
?>
<header class="header_nav">
  <div class="menu-iconbox"> <a href="javascript:void(0);"> <i class="fa fa-bars" aria-hidden="true"></i> </a> </div>
  <div class="menu-logo"> <a href="javascript:void(0);"> <img alt="no image found" src="<?php echo ASSETS; ?>buy_orders/assets/images/digiebot_logo.png"> </a> </div>
  <div class="menu-left-dropdown" >
    <select class="form-control mycls" id="main_symbol" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="This is the global coin Once you Select this it will use in overall application">
      <?php
if (count($coins_arr) > 0) {
    for ($i = 0; $i < count($coins_arr); $i++) {
        ?>
      <option value="<?php echo $coins_arr[$i]['symbol']; ?>" <?php if ($global_symbol == $coins_arr[$i]['symbol']) {?> selected <?php }?>><b><?php echo $coins_arr[$i]['symbol'];
        ?></b></option>
      <?php }
}
?>
    </select>
  </div>
  <?php if ($this->session->userdata('check_api_settings') == 'no') {?>
  <div class="menu-notification">
    <div class="alert alert-warning" role="alert"> <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> <span class="sr-only">Attention!</span> Your Binance Api is not set, please add from <a href="#">here</a> </div>
  </div>
  <?php }?>
  <div class="menu-user-logout"> <a href="#"><i class="fa fa-sign-out" aria-hidden="true"></i></a> </div>
  <div class="menu-user-dropdown">
    <div class="dropdown">
      <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"> <span class="mu-user-img"> <img src="<?php echo ASSETS; ?>profile_images/<?php echo $this->session->userdata('profile_image'); ?>" alt="" class="img-circle" width="39" height="39"> </span> <span class="mu-user-name"> <?php echo ucfirst($this->session->userdata('first_name') . " " . $this->session->userdata('last_name')); ?> </span> <span class="caret"></span> </button>
      <ul class="dropdown-menu">
        <li><a href="<?php echo SURL; ?>admin/dashboard/edit-profile">Edit Profile <i class="fa fa-pencil pull-right" aria-hidden="true"></i></a></li>
        <li><a href="<?php echo SURL; ?>admin/dashboard/login_history">Login History <i class="fa fa-history pull-right" aria-hidden="true"></i></a></li>
        <li><a href="<?php echo SURL; ?>admin/logout">Log out <i class="fa fa-sign-out pull-right" aria-hidden="true"></i></a></li>
      </ul>
    </div>
  </div>
  <div class="menu-radiobutton">
    <?php
if ($global_mode == 'test') {
    ?>
					<div class="togla_radio">
			      <input type="radio" value="test" name="sameName" class="test_r_btn application_mode" <?php if ($global_mode1 == 'test') {?> checked <?php }?>>
            <input type="radio" value="test" name="" class="test_r_btn application_mode" <?php if ($global_mode1 == 'test') {?> checked <?php }?>>
			      <div class="togla_radio_text"> <span class="t_r_live">Live</span> <span class="t_r_test">Test</span> <span class="t_r_circle"></span> </div>
			    </div>
			<?php } elseif ($global_mode == 'both') {
    ?>
				<div class="togla_radio">
			    <input type="radio" value="live" name="sameName" class="live_r_btn application_mode" <?php if ($global_mode1 == 'live') {?> checked <?php }?>>
					<input type="radio" value="test" name="sameName" class="test_r_btn application_mode" <?php if ($global_mode1 == 'test') {?> checked <?php }?>>
					<div class="togla_radio_text"> <span class="t_r_live">Live</span> <span class="t_r_test">Test</span> <span class="t_r_circle"></span> </div>
				</div>
			<?php } elseif ($global_mode == 'live') {
    ?>
        <div class="togla_radio">
          <input type="radio" value="live" name="sameName" class="live_r_btn application_mode" <?php if ($global_mode1 == 'live') {?> checked <?php }?>>
          <!-- <input type="radio" value="live" name="sameName" class="live_r_btn application_mode" <?php if ($global_mode1 == 'live') {?> checked <?php }?>>
          <input type="radio" value="test" name="sameName" class="test_r_btn application_mode" <?php if ($global_mode1 == 'test') {?> checked <?php }?>> -->
          <div class="togla_radio_text"> <span class="t_r_live">Live</span> <span class="t_r_test">Test</span> <span class="t_r_circle"></span> </div>
        </div>
      <?php }?>
  </div>
</header>
<section class="main-contianer">
  <div class="main-side-bar">
    <div class="sidebar_user">
      <div class="sidebar_user_img">
        <?php if ($this->session->userdata('profile_image') != "") {?>
        <img src="<?php echo ASSETS; ?>profile_images/<?php echo $this->session->userdata('profile_image'); ?>" alt="" class="img-circle" width="52" height="52">
        <?php } else {?>
        <img src="<?php echo ASSETS; ?>images/empty_user.png" alt="" class="img-circle" width="52" height="52">
        <?php }?>
      </div>
      <div class="sidebar_user_text"> <span class="su_name"><?php echo ucfirst($this->session->userdata('first_name') . " " . $this->session->userdata('last_name')); ?></span> <span class="su_online">Online</span> </div>
    </div>
    <div class="sidebar_menu_box">
      <ul class="sidebar_menu_ul">
        <li <?php if ($_SERVER['REQUEST_URI'] == '/admin/dashboard') {?> class="active" <?php }?>> <a href="<?php echo SURL ?>admin/dashboard"><span class="menuIcon"><i class=" icon-projector-screen-line"></i></span> <span>Dashboard</span></a> </li>

        <!--li <?php //if($_SERVER['REQUEST_URI']=='/admin/dashboard/chart'){?> class="active" <?php //} ?>>
                <a href="<?php// echo SURL?>admin/dashboard/chart"><span class="menuIcon"><i class=" icon-projector-screen-line"></i></span>
                <span>Chart</span></a>
                </li>

                <li <?php //if($_SERVER['REQUEST_URI']=='/admin/dashboard/chart2'){?> class="active" <?php //} ?>>
                <a href="<?php //echo SURL?>admin/dashboard/chart2"><span class="menuIcon"><i class=" icon-projector-screen-line"></i></span>
                <span>Chart2</span></a>
                </li-->

        <li <?php if ($_SERVER['REQUEST_URI'] == '/admin/indicator') {?> class="active" <?php }?>> <a href="<?php echo SURL ?>admin/chart3_group"><span class="menuIcon"><i class=" icon-projector-screen-line"></i></span> <span>Chart</span></a> </li>
        <?php if ($this->session->userdata('user_role') == 1) {?>
        <li class="hasSubmenu <?php if ($_SERVER['REQUEST_URI'] == '/admin/dashboard/add-zone' || $_SERVER['REQUEST_URI'] == '/admin/dashboard/zone-listing') {?>active<?php }?>"> <a href="#"><span class="menuIcon"><i class="icon-compose"></i></span> <span>Chart Target Zones</span></a>
          <ul class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/dashboard/add-zone' || $_SERVER['REQUEST_URI'] == '/admin/dashboard/zone-listing') {?>in<?php }?>" id="menu-style">
            <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/dashboard/zone-listing') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/dashboard/zone-listing">Target Zones Listing</a></li>
            <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/dashboard/add-zone') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/dashboard/add-zone">Add Target Zones</a></li>
          </ul>
        </li>
        <?php }?>
        <li class="hasSubmenu <?php if ($_SERVER['REQUEST_URI'] == '/admin/buy_orders/add-buy-order' || $_SERVER['REQUEST_URI'] == '/admin/buy_orders/' || $_SERVER['REQUEST_URI'] == '/admin/buy_orders/add_buy_order_triggers/') {?>active<?php }?>"> <a href="#"><span class="menuIcon"><i class="icon-compose"></i></span> <span>Buy Orders</span></a>
          <ul class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/buy_orders/add-buy-order' || $_SERVER['REQUEST_URI'] == '/admin/buy_orders/') {?>in<?php }?> " id="menu-style3" style="display:block;">
            <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/buy_orders/') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/buy_orders/">Orders Listing</a></li>
            <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/buy_orders/add-buy-order') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/buy_orders/add-buy-order">Add Digie Manual Order</a></li>
            <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/buy_orders/add_buy_order_triggers') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/buy_orders/add_buy_order_triggers">Add Digie Auto Order</a></li>

            <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/buy_orders/add_buy_order_triggers') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/buy_orders/lth_order_listing">Long Term Hold</a></li>
          </ul>
        </li>
        <?php if ($this->session->userdata('user_role') == 1) {?>
        <li class="hasSubmenu <?php if ($_SERVER['REQUEST_URI'] == '/admin/coins' || $_SERVER['REQUEST_URI'] == '/admin/coins/add-coin') {?>active<?php }?>"> <a href="#"><span class="menuIcon"><i class="icon-compose"></i></span> <span>Coins</span></a>
          <ul class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/coins' || $_SERVER['REQUEST_URI'] == '/admin/coins/add-coin') {?>in<?php }?>" id="menu-style4">
            <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/coins') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/coins">Manage Coins</a></li>
            <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/coins/add-coin') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/coins/add-coin">Add Coin</a></li>
          </ul>
        </li>
        <?php }?>
        <?php if ($this->session->userdata('user_role') == 2) {?>
        <li class="hasSubmenu <?php if ($_SERVER['REQUEST_URI'] == '/admin/user_coins' || $_SERVER['REQUEST_URI'] == '/admin/user_coins/add-coin') {?>active<?php }?>"> <a href="#"><span class="menuIcon"><i class="icon-compose"></i></span> <span>Coins</span></a>
          <ul class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/user_coins' || $_SERVER['REQUEST_URI'] == '/admin/user_coins/add-coin') {?>in<?php }?>" id="menu-style4">
            <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/coin_market') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/coin_market">Manage Coins</a></li>
            <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/user_coins/add-coin') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/user_coins/add-coin">Add Coin</a></li>
          </ul>
        </li>
        <?php }?>
        <?php if ($this->session->userdata('user_role') == 1 || $this->session->userdata('special_role') == 1) {
    ?>
        <li class="hasSubmenu <?php if ($_SERVER['REQUEST_URI'] == '/admin/users' || $_SERVER['REQUEST_URI'] == '/admin/users/add-user') {?>active<?php }?>"> <a href="#"><span class="menuIcon"><i class="icon-compose"></i></span> <span>Users</span></a>
          <ul class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/users' || $_SERVER['REQUEST_URI'] == '/admin/users/add-user') {?>in<?php }?>" id="menu-style5">
            <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/users') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/users">Manage Users</a></li>
            <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/users/add-user') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/users/add-user">Add User</a></li>
          </ul>
        </li>
        <?php if ($this->session->userdata('special_role') == 1) {?>
        <li <?php if ($_SERVER['REQUEST_URI'] == '/admin/candle_chart') {?> class="active" <?php }?>> <a href="<?php echo SURL ?>admin/candle_chart"><span class="menuIcon"><i class=" icon-projector-screen-line"></i></span> <span>15 Minute Candles</span></a> </li>
        <li <?php if ($_SERVER['REQUEST_URI'] == '/admin/chart3_group_trigger') {?> class="active" <?php }?>> <a href="<?php echo SURL ?>admin/chart3_group_trigger"><span class="menuIcon"><i class=" icon-projector-screen-line"></i></span> <span>Historical Chart</span></a> </li>
        <li <?php if ($_SERVER['REQUEST_URI'] == '/admin/candel/run') {?> class="active" <?php }?>> <a href="<?php echo SURL ?>admin/candel/run"><span class="menuIcon"><i class=" icon-projector-screen-line"></i></span> <span>Candles</span></a> </li>
        <li <?php if ($_SERVER['REQUEST_URI'] == '/admin/candel_api/run') {?> class="active" <?php }?>> <a href="<?php echo SURL ?>admin/candel_api/run"><span class="menuIcon"><i class=" icon-projector-screen-line"></i></span> <span>Historical Candles</span></a> </li>
        <li <?php if ($_SERVER['REQUEST_URI'] == '/admin/trigger/rules') {?> class="active" <?php }?>> <a href="<?php echo SURL ?>admin/trigger/rules"><span class="menuIcon"><i class=" icon-projector-screen-line"></i></span> <span>Trigger Rules</span></a> </li>
        <?php }}?>
        <li <?php if ($_SERVER['REQUEST_URI'] == '/admin/settings') {?> class="active" <?php }?>> <a href="<?php echo SURL ?>admin/settings/"><span class="menuIcon"><i class=" icon-projector-screen-line"></i></span> <span>Settings</span></a> </li>
        <?php if ($this->session->userdata('user_role') == 1) {?>
        <li <?php if ($_SERVER['REQUEST_URI'] == '/admin/sockets') {?> class="active" <?php }?>> <a href="<?php echo SURL ?>admin/sockets/"><span class="menuIcon"><i class=" icon-projector-screen-line"></i></span> <span>Binance API Statistics</span></a> </li>
        <?php }?>
        <?php if ($this->session->userdata('user_role') == 1) {?>
        <li <?php if ($_SERVER['REQUEST_URI'] == '/admin/reports') {?> class="active" <?php }?>> <a href="<?php echo SURL ?>admin/reports/"><span class="menuIcon"><i class=" icon-projector-screen-line"></i></span> <span>Admin Report</span></a> </li>
        <li <?php if ($_SERVER['REQUEST_URI'] == '/admin/api_documentation') {?> class="active" <?php }?>> <a href="<?php echo SURL ?>admin/api_documentation/"><span class="menuIcon"><i class=" icon-projector-screen-line"></i></span> <span>API Documentation</span></a> </li>
        <li <?php if ($_SERVER['REQUEST_URI'] == '/admin/app_documentation') {?> class="active" <?php }?>> <a href="<?php echo SURL ?>admin/app_documentation/"><span class="menuIcon"><i class=" icon-projector-screen-line"></i></span> <span>App Documentation</span></a> </li>
        <?php }?>
      </ul>
    </div>
  </div>
	<?php $session_post_data = $this->session->userdata('filter-data-buy');?>
  <div class="main-content-area">
    <div class="content-area-mainHead">
      <h1>BUY ORDERS LISTING</h1>
    </div>
    <div class="content-area-Head">
      <!-- <a href="#" class="btn btn-default">Buy Order Listing</a> -->
      <span class="headeringoicon" id="popover" ><i class="fa fa-info-circle" aria-hidden="true"></i></span> </div>
    <div class="content-area">
      <form class="margin-none" method="post" action="<?php echo SURL ?>admin/buy_orders/" novalidate>
        <div class="filterbox" data-step="1" data-intro="Here you can apply Filter">
          <div class="col-xs-12 col-md-2">
            <div class="form-group">
              <select name="filter_type" class="form-control">
                <?php $filter_type = $session_post_data['filter_type'];?>
                <option value="">Search By Type</option>
                <option value="market_order" <?php if ($filter_type == 'market_order') {?> selected <?php }?>>Market Order</option>
                <!-- <option value="limit_order" <?php //if ($filter_type == 'limit_order') {?> selected <?php //}?>>Limit Order</option> -->
              </select>
            </div>
          </div>
          <div class="col-xs-12 col-md-2">
            <div class="form-group">
              <select name="filter_coin[]" id="coins" multiple class="form-control">
                <option value="">Search By Coin</option>
                <?php
if (count($coins_arr) > 0) {
    for ($i = 0; $i < count($coins_arr); $i++) {

        $filter_coin = $session_post_data['filter_coin'];
        if (in_array($coins_arr[$i]['symbol'], $filter_coin)) {
            $selected = "selected";
        } else {
            $selected = "";
        }

        ?>
                <option value="<?php echo $coins_arr[$i]['symbol']; ?>" <?php echo $selected;
        ?>><?php echo $coins_arr[$i]['symbol'];
        ?></option>
                <?php }
}?>
              </select>
            </div>
          </div>
          <div class="col-xs-12 col-md-2">
            <div class="form-group">
              <select name="filter_trigger" class="form-control">
                <?php $filter_trigger = $session_post_data['filter_trigger'];?>
                <option value="">Search By Trigger</option>
                <option value="trigger_1" <?php if ($filter_trigger == 'trigger_1') {?> selected <?php }?>>Trigger 1</option>
                <option value="trigger_2" <?php if ($filter_trigger == 'trigger_2') {?> selected <?php }?>>Trigger 2</option>
                <option value="box_trigger_3" <?php if ($filter_trigger == 'box_trigger_3') {?> selected <?php }?>>Box Trigger 3</option>
                <option value="barrier_trigger" <?php if ($filter_trigger == 'barrier_trigger') {?> selected <?php }?>>BARRIER TRIGGER</option>
                <option value="barrier_percentile_trigger" <?php if ($filter_trigger == 'barrier_percentile_trigger') {?> selected <?php }?>>BARRIER Percentile TRIGGER</option>
                <option value="rg_15" <?php if ($filter_trigger == 'rg_15') {?> selected <?php }?>>RG 15</option>
                <option value="no" <?php if ($filter_trigger == 'no') {?> selected <?php }?>>Manual Order</option>
              </select>
            </div>
          </div>
					<div class="col-xs-12 col-md-2">
            <div class="form-group">
              <select name="filter_level" class="form-control">
                <?php $filter_level = $session_post_data['filter_level'];?>
                <option value="">Search By Level</option>
                <option value="level_1" <?php if ($filter_level == 'level_1') {?> selected <?php }?>>Rule_1</option>
                <option value="level_2" <?php if ($filter_level == 'level_2') {?> selected <?php }?>>Rule_2</option>
								<option value="level_3" <?php if ($filter_level == 'level_3') {?> selected <?php }?>>Rule_3</option>
								<option value="level_4" <?php if ($filter_level == 'level_4') {?> selected <?php }?>>Rule_4</option>
                                <option value="level_5" <?php if ($filter_level == 'level_5') {?> selected <?php }?>>Rule_5</option>
                                
                                <option value="level_6" <?php if ($filter_level == 'level_6') {?> selected <?php }?>>Rule_6</option>
                                <option value="level_7" <?php if ($filter_level == 'level_7') {?> selected <?php }?>>Rule_7</option>
                                <option value="level_8" <?php if ($filter_level == 'level_8') {?> selected <?php }?>>Rule_8</option>
                                <option value="level_9" <?php if ($filter_level == 'level_9') {?> selected <?php }?>>Rule_9</option>
                                <option value="level_10" <?php if ($filter_level == 'level_10') {?> selected <?php }?>>Rule_10</option>

                                <option value="level_11" <?php if ($filter_level == 'level_11') {?> selected <?php }?>>Rule_11</option>


                                <option value="level_12" <?php if ($filter_level == 'level_12') {?> selected <?php }?>>Rule_12</option>

                                <option value="level_13" <?php if ($filter_level == 'level_13') {?> selected <?php }?>>Rule_13</option>


                                <option value="level_14" <?php if ($filter_level == 'level_14') {?> selected <?php }?>>Rule_14</option>


                                <option value="level_15" <?php if ($filter_level == 'level_15') {?> selected <?php }?>>Rule_15</option>
              </select>
            </div>
          </div>
          <div class="col-xs-12 col-md-2">
            <div class="form-group">
              <input type='text' class="form-control datetime_picker" name="start_date" placeholder="Search By Start Date" value="<?php echo $session_post_data['start_date']; ?>" />
            </div>
          </div>
          <div class="col-xs-12 col-md-2">
            <div class="form-group">
              <input type='text' class="form-control datetime_picker" name="end_date" placeholder="Search By End Date" value="<?php echo $session_post_data['end_date']; ?>" />
            </div>
          </div>
          <div class="col-xs-12 col-md-2">
            <div class="row">
              <div class="col-xs-6">
                <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Search</button>
              </div>
              <div class="col-xs-6"> <a href="<?php echo SURL; ?>admin/buy_orders/reset_buy_filters" class="btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Reset</a> </div>
            </div>
          </div>
        </div>
      </form>
      <div class="status_val_box">
        <div class="row">
          <div class="col-2-5" data-step="2" data-intro="This is your bitcoin balance">
            <div class="statusBOX">
              <h2>BTC Balance</h2>
              <h3 id="bitcoin">0</h3>
            </div>
          </div>
          <div class="col-2-5" data-step="3" data-intro="This is your Selected Coin balance">
            <div class="statusBOX">
              <h2><?php echo str_replace('BTC', '', $global_symbol); ?> Balance</h2>
              <h3 id="balance">0</h3>
            </div>
          </div>
          <div class="col-2-5">
            <div class="statusBOX" data-step="4" data-intro="This is your Binance BNB balance">
              <h2>BNB Balance</h2>
              <h3 id="bnb_balance">0</h3>
            </div>
          </div>
					<div class="col-2-5" data-step="5" data-intro="Total Number of Orders Sold">
            <div class="statusBOX">
              <h2>Total Sold Orders</h2>
              <h3 id="total_sold_orders"><?php echo 0 ?></h3>
            </div>
          </div>

          <?php
if (is_numeric($avg_profit)) {
    $avg_profit = $avg_profit;
} else {
    $avg_profit = 0;
}
if (num($avg_profit) > 0) {
    $class = 'text-primary';
    $style = "style = 'color:#6ecb40 !important;'";
} else {
    $class = 'text-danger';
}
?>
          <div class="col-2-5" data-step="6" data-intro="Total Average Profit interms of BTC">
            <div class="statusBOX">
              <h2>Avg Profit</h2>
              <h3 id="avg_profit" class="<?php echo $class ?>">0</h3>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xs-12">
        <div class="row">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h4 class="pull-left">Orders Listing</h4>
              <button class="btn btn-success pull-right sell_open_ordres" data-step="7" data-intro="This Sell all the open Trades" style="margin-left:5px">Sell Bulk Orders</button>
              <button class="btn btn-success pull-right wait_sell_open_ordres" style="margin-left:5px;display:none"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></button>
              <a href="<?php echo SURL; ?>admin/buy_orders/download_csv/" data-step="8" data-intro="This is export all trades in CSV format" class="btn btn-success pull-right">Export CSV</a>
              <a href="#" data-step="9" data-intro="This will Close all of your Long Term Trades on specific profit" class="btn btn-success pull-right" data-toggle="modal" data-target="#basicExampleModal">Close All LTH Trades</a>
            </div>
            <div class="panel-body" data-step="9" data-intro="Here comes your all Trades">
              <div class="panelBtnSection" id="tabss">
                <div class="pbsbox"> <a class="tabs-tab" dt-id="1" href="javascript:void(0);" id="parent">Parent <strong>(<span id="counter9">0</span>)</strong></a> </div>
                <div class="pbsbox"> <a class="tabs-tab active" dt-id="2" href="javascript:void(0);" id="new">New <strong>(<span id="counter1">0</span>)</strong></a> </div>
                <div class="pbsbox"> <a class="tabs-tab" dt-id="3" href="javascript:void(0);" id="filled" >Filled <strong>(<span id="counter2">0</span>)</strong></a> </div>
                <div class="pbsbox"> <a class="tabs-tab" dt-id="4" href="javascript:void(0);" id="submitted">Submitted <strong>(<span id="counter3">0</span>)</strong></a> </div>
                <div class="pbsbox"> <a class="tabs-tab" dt-id="5" href="javascript:void(0);" id="cancelled"><span>Canceled <strong>(<span id="counter4">0</span>)</strong></span></a> </div>
                <div class="pbsbox"> <a class="tabs-tab" dt-id="6" href="javascript:void(0);" id="error"><span>Error<strong>(<span id="counter5">0</span>)</strong></span></a> </div>
                <div class="pbsbox"> <a class="tabs-tab" dt-id="7" href="javascript:void(0);" id="open">Open<strong>(<span id="counter6">0</span>)</strong></a> </div>
                <div class="pbsbox"> <a class="tabs-tab" dt-id="8" href="javascript:void(0);" id="sold">Sold<strong>(<span id="counter7">0</span>)</strong></a> </div>
                <div class="pbsbox"> <a class="tabs-tab" dt-id="10" href="javascript:void(0);" id="lth">LTH<strong>(<span id="counter10">0</span>)</strong></a> </div>
                <div class="pbsbox"> <a class="tabs-tab" dt-id="9" href="javascript:void(0);" id="all">All<strong>(<span id="counter8">0</span>)</strong></a> </div>
              </div>
              <div class="col-xs-12">
                <div class="row">
                  <div class="table_row tab_pane" dtid="1"></div>
                  <div class="table_row tab_pane active" dtid="2">
                    <table class="table">
                      <thead>
                        <tr>
                          <th class="coinIcon_th">Coin</th>
                          <th>Price</th>
                          <th>Order Type</th>
                          <th>Trail Price</th>
                          <th>Quantity</th>
                          <th>P/L</th>
                          <th>Market(%)</th>
                          <th class="text-center">Status</th>
                          <th>Profit(%)</th>
                          <th class="coinAction_th">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
if (count($new_arr) > 0) {
    foreach ($new_arr as $key => $value) {

        //Get Market Price
        $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

        if ($value['status'] != 'new') {
            $market_value333 = num($value['market_value']);
        } else {
            $market_value333 = num($market_value);
        }

        if ($value['status'] == 'new') {
            $current_order_price = num($value['price']);
        } else {
            $current_order_price = num($value['market_value']);
        }

        $current_data = $market_value333 - $current_order_price;
        $market_data = ($current_data * 100 / $market_value333);

        $market_data = number_format((float) $market_data, 2, '.', '');

        if ($market_value333 > $current_order_price) {
            $class = 'success';
        } else {
            $class = 'danger';
        }
        ?>
                        <?php $logo = $this->mod_coins->get_coin_logo($value['symbol']);?>
                        <tr>
                          <td class="coinIcon_td"><img src="<?php echo ASSETS; ?>coin_logo/thumbs/<?php echo $logo; ?>" class="img img-circle" data-toggle="tooltip" data-placement="top" title="<?php echo $value['symbol'] ?>"></td>
                          <?php
if ($value['trigger_type'] != 'no' && $value['price'] == '') {?>
                          <td><?php echo strtoupper(str_replace('_', ' ', $value['trigger_type'])); ?></td>
                          <?php } else {?>
                          <td><?php echo num($value['price']); ?></td>
                          <?php }?>
                          <td>BOX TRIGGER 3</td>
                          <td><?php
if ($value['trail_check'] == 'yes') {
            echo num($value['buy_trail_price']);
        } else {
            echo "-";
        }
        ?></td>
                          <td><?php echo $value['quantity']; ?></td>
                          <td class="center"><b><?php echo num($market_value333); ?></b></td>
                          <?php
if ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes') {
            ?>
                          <td class="text-center"><span class="text-<?php echo $class; ?>"><b><?php echo $market_data;
            ?>%</b></span></td>
                          <?php } else {?>
                          <td class="center"><span class="text-default"><b>-</b></span></td>
                          <?php }?>
                          <td class="center"><!--  <span class="label label-inverse"><?php echo strtoupper($value['application_mode']); ?></span> -->

                            <span class="label label-success"><?php echo strtoupper($value['status']); ?></span> <span class="custom_refresh" data-id="<?php echo $value['_id']; ?>" order_id="<?php echo $value['binance_order_id']; ?>"> <i class="fa fa-refresh" aria-hidden="true"></i> </span></td>
                          <td class="center"><?php
if ($value['market_sold_price'] != "") {

            $market_sold_price = $value['market_sold_price'];

            $current_data2222 = $market_sold_price - $current_order_price;
            $profit_data = ($current_data2222 * 100 / $market_sold_price);

            $profit_data = number_format((float) $profit_data, 2, '.', '');

            if ($market_sold_price > $current_order_price) {
                $class222 = 'success';
            } else {
                $class222 = 'danger';
            }?>
                            <span class="text-<?php echo $class222; ?>"> <b><?php echo $profit_data; ?>%</b> </span>
                            <?php
} else {?>
                            <span class="text-default"><b>-</b></span>
                            <?php }?></td>
                          <td class="text-center"><?php
if ($value['status'] == 'new') {?>
                            <button class="btn btn-danger buy_now_btn" id="<?php echo $value['_id']; ?>" data-id="<?php echo $value['_id']; ?>" market_value="<?php echo num($market_value); ?>" quantity="<?php echo $value['quantity']; ?>" symbol="<?php echo $value['symbol']; ?>">Buy Now</button>
                            <?php }?>




                            <button class="btn btn-default view_order_details" title="View Order Details" data-id="<?php echo $value['_id']; ?>"><i class="fa fa-eye"></i></button></td>
                        </tr>
                        <?php }
}?>
                      </tbody>
                    </table>
                  </div>
                  <div class="table_row tab_pane" dtid="3"></div>
                  <div class="table_row tab_pane" dtid="4"></div>
                  <div class="table_row tab_pane" dtid="5"></div>
                  <div class="table_row tab_pane" dtid="6"></div>
                  <div class="table_row tab_pane" dtid="7"></div>
                  <div class="table_row tab_pane" dtid="8"></div>
                  <div class="table_row tab_pane" dtid="9"></div>
                  <div class="table_row tab_pane" dtid="10"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Start Model -->
<div class="modal fade in" id="modal-order_details" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal heading -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="modal-title">Order Details</h3>
      </div>
      <!-- // Modal heading END -->

      <!-- Modal body -->
      <div class="modal-body">
        <div class="innerAll">
          <div class="innerLR" id="response_order_details"> </div>
        </div>
      </div>
      <!-- // Modal body END -->

    </div>
  </div>
</div>
<!-- End Model -->

<!-- Start Model -->
<div class="modal fade in" id="modal-order_update" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal heading -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="modal-title">Order Update</h3>
      </div>
      <!-- // Modal heading END -->

      <!-- Modal body -->
      <div class="modal-body">
        <div class="innerAll">
          <div class="innerLR" id="response_order_update"> </div>
        </div>
      </div>
      <!-- // Modal body END -->

    </div>
  </div>
</div>
<!-- End Model -->


<!-- Start Model -->
<div class="modal fade in" id="modal-admin" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal heading -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="modal-title">Order Update</h3>
      </div>
      <!-- // Modal heading END -->

      <!-- Modal body -->
      <div class="modal-body">
        <div class="innerAll">
          <div class="innerLR">
            <div class="container pb-cmnt-container">
              <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <label class="control-label">Quantity</label>
                            <input type="number" id="edt-qty" class="form-control">
                            <input type="hidden" id="edt-buy_id">
                            <input type="hidden" id="edt-sell_id">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <label class="control-label">Comment</label>
                            <textarea placeholder="Write your comment here!" id="admin_comment" class="pb-cmnt-textarea"></textarea>
                        </div>
                    </div>
                </div>
            </div>
          </div>

<style>
    .pb-cmnt-container {
        font-family: Lato;
    }

    .pb-cmnt-textarea {
        resize: none;
        padding: 20px;
        height: 130px;
        width: 100%;
        border: 1px solid #F2F2F2;
    }
</style>
          </div>
        </div>
      </div>
      <!-- // Modal body END -->
      <div class="modal-footer">
        <button type="button" id="btn-update_order" class="btn btn-success">Update</button>
      </div>
    </div>
  </div>
</div>
<!-- End Model -->

<!-- Modal -->
<div class="modal fade" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Close LTH Trades</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="">
          <div class="button-wrap">
            <input class="hidden radio-label mycustomoption" type="radio" name="accept-offers"  value = "profit" id="yes-button">
            <label class="button-label" for="yes-button">
              <h1>Close All Profit Trades</h1>
            </label>
            <input class="hidden radio-label mycustomoption" type="radio" name="accept-offers"  value = "loss" id="no-button">
            <label class="button-label" for="no-button">
              <h1>Close All Loss Trades</h1>
            </label>
            <input class="hidden radio-label mycustomoption" type="radio" name="accept-offers"  value = "custom" id="maybe-button">
            <label class="button-label" for="maybe-button">
              <h1>Close Trades Under Percentage</h1>
            </label>
          </div>
          <div class="row" id="percentages">
            <div class="col-md-12" style="height: 100%;">
                <input type="number" id="percentage" placeholder="Enter the desired profit/loss percentage" class="form-control">
          </div>
          <div class="col-md-12">
            <div class="alert alert-info" style="position: relative;margin-top: 5px;">
              If you want to Add Stop Loss enter the negitive percentage otherwise positive
            </div>
          </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary savebuttonlth">Save changes</button>
      </div>
    </div>
  </div>
</div>


<script src="<?php echo ASSETS; ?>buy_orders/assets/js/jquery.min.js"></script>
<script src="<?php echo ASSETS; ?>buy_orders/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo ASSETS; ?>buy_orders/assets/js/custom.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
<script src="https://app.digiebot.com/assets/jquery_confirm/jquery-confirm.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

<?php 
    $error_tab = 'hide';
if(isset($_GET['error'])){
    $error_tab = 'show';
} ?>


<script type="text/javascript">
    $(function () {
        $('.datetime_picker').datetimepicker();
    });
</script>
<script type="text/javascript">
$(document).ready(function() {

     autoload_coin_balance();
     autoload_market_buy_data();
     autoload_order_count();
     $("#percentages").hide();
     //%%%%%%%%%%%%%%%%%%%%%%% -------- %%%%%%%%%%%%%%%

    $(document).on('click','.sell_open_ordres',function(){



        $('.bulk_order_checked').each(function () {
            var primary_key = (this.checked ? $(this).val() : "");
            var sell_order_id =  $('#'+primary_key).attr('data-sell-order-id');

        });

        // $('.sell_open_ordres').hide();
        // $('.wait_sell_open_ordres').show();

    })

});
  function autoload_coin_balance(){
      $.ajax({
          type:'POST',
          url:'<?php echo SURL ?>admin/buy_orders/get_coin_balance',
          data: "",
          success:function(response){
              resp = response.split('|');
              $('#balance').html(resp[0]);
              $('#bitcoin').html(resp[1]);
							$('#bnb_balance').html(resp[2]);

              setTimeout(function() {
                    autoload_coin_balance();
              }, 30000);

          }
        });
  }

  function autoload_market_buy_data(){
     var activeli = $('div#tabss').find('div.pbsbox').find('a.active');
      var hrf = '.tab_pane.active';
      var id  = $(activeli).attr('id');
      var page = $(hrf).find('ul.pagination').find('li.active').find('a').find('b').html();


        

      if (page == null) { page = 1; }
        $.ajax({
        type: "GET",
        data: {status:id},
        url:'<?php echo SURL ?>admin/buy_orders/get_order_ajax/'+page,
        success:function(response){
            $(hrf).html(response);

              if(id == 'sold'){
                    $('.trail_stop').hide()
                    $('.trail_stop_td').hide();
                    $('.sold_price_cls').show();
                    $('.sold_price_td_cls').show();
                    
                }else{
                    $('.trail_stop').show();
                    $('.trail_stop_td').show();
                    $('.sold_price_cls').hide();
                    $('.sold_price_td_cls').hide();
                }
            setTimeout(function() {
                        autoload_market_buy_data();
                  }, 20000);
        },
        error: function(errorThrown) {
            console.log("Error: " + errorThrown);
            setTimeout(function() {
                autoload_market_buy_data();
          }, 20000);
        }
      });

  }//end autoload_market_buy_data()

  function autoload_order_count(){
        $.ajax({
        type: "POST",
        data: "",
        url:'<?php echo SURL ?>admin/buy_orders/get_all_counts',
        success:function(response){
            resp = response.split('|');
            $('#counter1').html(resp[0]);
            $('#counter2').html(resp[1]);
            $('#counter3').html(resp[2]);
            $('#counter4').html(resp[3]);
            $('#counter5').html(resp[4]);
            $('#counter6').html(resp[5]);
            $('#counter7').html(resp[6]);
            $('#counter8').html(resp[7]);
            $('#counter9').html(resp[10]);
            $('#counter10').html(resp[11]);
            $('#total_sold_orders').html(resp[8]);
            var profit = parseFloat(resp[9]);
            if(isNaN(profit))
            {
                profit = 0;
            }
            if (profit > 0.0) {
                //alert(typeof(profit) + "------- > positive     " + profit)
                $("#avg_profit").removeClass("text-danger").addClass("text-primary");
                //$("#textclass").css('color', '#6ecb40 !important');
            }else{
                //alert(typeof(profit) + "------- negitive  " + profit)
                $("#avg_profit").removeClass("text-primary").addClass("text-danger");
                $("#avg_profit").removeAttr("style");
            }
            $('#avg_profit').html(profit+"%");
            setTimeout(function() {
                        autoload_order_count();
                  }, 5000);
        },
        error: function(errorThrown) {
            console.log("Error: " + errorThrown);
            setTimeout(function() {
                autoload_order_count();
          }, 5000);
        }
      });
  }

  $("body").on("click",".view_order_details",function(e){

        var order_id = $(this).attr("data-id");

         $.ajax({
            'url': '<?php echo SURL ?>admin/dashboard/get_buy_order_details_ajax',
            'type': 'POST',
            'data': {order_id:order_id},
            'success': function (response) {

                $('#response_order_details').html(response);
                $("#modal-order_details").modal('show');
            }
        });

  });

  $("body").on("click",".change_error_status",function(e){

        var order_id = $(this).attr("data-id");



         $.ajax({
            'url': '<?php echo SURL ?>admin/buy_orders/get_buy_order_error_ajax',
            'type': 'POST',
            'data': {order_id:order_id},
            'success': function (response) {

                $('#response_order_update').html(response);
                $("#modal-order_update").modal('show');
            }
        });

  });


  $("body").on("change",".mycustomoption",function(e){

        var order_id = $(this).val();
        console.log(order_id)
        if (order_id == 'custom') {
          $("#percentages").show();
        }else{
          $("#percentages").hide();
        }
  });


    $("body").on("click",".savebuttonlth",function(e){

        var option = $("input[name=accept-offers]:checked").val();
        var per = 0;
        if (option == 'custom') {
           per = $("#percentage").val();
        }else{
          per = "";
        }
        console.log(option+" => "+per)
         $.ajax({
            'url': '<?php echo SURL ?>admin/buy_orders/sell_all_lth_trades',
            'type': 'POST',
            'data': {option:option, percentage:per },
            'success': function (response) {
               $("#basicExampleModal").modal('hide');
                $.alert({
                  title: "Alert",
                  content: response
                });

            }
        });

  });

  $("body").on("click",".admin_edt",function(e){

        var order_id = $(this).attr("id");



         $.ajax({
            'url': '<?php echo SURL ?>admin/buy_orders/get_order',
            'type': 'POST',
            'data': {order_id:order_id},
            success: function (responseJSON) {
                response = JSON.parse(responseJSON);
                $("#edt-qty").val(response.quantity);
                $("#edt-buy_id").val(response.buy_id);
                $("#edt-sell_id").val(response.sell_id);
                $("#modal-admin").modal('show');
            }
        });

  });

    $("body").on("click","#btn-update_order",function(e){
        var buy_id = $("#edt-buy_id").val();
        var sell_id = $("#edt-sell_id").val();
        var qty = $("#edt-qty").val();
        var comment = $("#admin_comment").val();

        $.ajax({
            'url': '<?php echo SURL ?>admin/buy_orders/update_admin_process',
            'type': 'POST',
            'data': {buy_id:buy_id,sell_id:sell_id,qty:qty,comment:comment,},
            success: function (response) {
                $("#modal-admin").modal('hide');
                $.alert({
                    title: "Update Order Succesfully",
                    content: response,
                });
            }
        });

    });

    $("body").on("click",".sell_now_btn",function(e){

        var id = $(this).attr('data-id');
        var market_value = $(this).attr('market_value');
        var quantity = $(this).attr('quantity');
        var symbol = $(this).attr('symbol');
        var order_type = $(this).attr('order_type');
        var buy_order_id = $(this).attr('buy_order_id');

        $("#"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

            if(order_type !='limit_order'){
            // sell_order(id,market_value,quantity,symbol);

              sell_market_order(id,market_value,quantity,symbol,buy_order_id);

            }else{
             limit_order_cancel(id,market_value,quantity,symbol,buy_order_id);
            }

    });


    //%%%%%%%%%%%%%%%%%%%%%%%5 Sell limit order %%%%%%%%%%%%%%%%%%%%%%%%%%
    function sell_market_order(sell_id,market_value,quantity,symbol,buy_order_id){

         $.ajax({
            'url':'<?php echo SURL ?>admin/dashboard/check_status_of_limit_order',
            'type':'POST',
            'data':{sell_id:sell_id,symbol:symbol},
            'success':function(response){
                var rp = JSON.parse(response);
                var resp =rp['status'];
                var current_market_price =rp['market_price'];


                $("#"+sell_id).html('Sell Now');

                    if(resp == 'error'){
                        //%%%%%%%%%%%%%%% if order is an error status%%%%%%%%%%%
                            $.confirm({
                                title: 'Attention!',
                                content: 'The order is an error status. Please Remove the error to sell this order',
                                type: 'red',
                                typeAnimated: true,
                                buttons: {
                                    tryAgain: {
                                        text: 'Ok',
                                        btnClass: 'btn-red',
                                        action: function(){
                                        }
                                    },
                                        close: function () {
                                    }
                                }
                            });
                       //%%%%%%%%%%%%%%%End of  error status%%%%%%%%%%%
                    }else if (resp == 'submitted'){
                        //%%%%%%%%%%%%%%%%%%% submitted status %%%%%%%%%%%%%%%%%%%%%

                         $.confirm({
                            title: 'Order Status',
                            content: 'Order Already Send for sell',
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: 'Ok',
                                    btnClass: 'btn-red',
                                    action: function(){

                                    }
                                },
                                    close: function () {
                                }
                            }
                        });
                         //%%%%%%%%%%%%%%%%%%% End of submitted status %%%%%%%%%%%%%%%%%%%%%
                    }else if (resp == 'new' || resp == 'LTH'){

                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%5  New Status%%%%%%%%%%%%%%%%%%%%%%%%%
                        var content_html ='Select from below to sell this order\
                            <div class="">\
                            <hr>\
                            <form>\
                            <div class="form-group">\
                            <div class="row"><div class="col-xs-6">\
                            <label>Current Market Price</label>\
                            <input class="form-control" step="any" type="number" name="cu_m_price_'+sell_id+'" value="'+current_market_price+'" disabled>\
                            </div><div class="col-xs-6">\
                            <label>Sell Price</label>\
                            <input class="form-control" step=".00000001" type="number" name="sell_price_'+sell_id+'" value="'+current_market_price+'" id="sell_price_'+sell_id+'">\
                            </div>\
                            </div>'

                            // <div class="radio">\
                            // <label><input type="radio" name="typ_new_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\
                            // </div>\
                            // <div class="radio">\
                            // <label><input type="radio" name="typ_new_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\
                            // </div>

                            content_html +='<div class="radio ">\
                            <label><input type="radio" name="typ_new_'+sell_id+'" value="m_current" checked>Fire market order on current market price</label>\
                            </div>\
                            </form>\
                            </div>';

                         $.confirm({
                            title: 'Sell order conformation',
                            content: content_html,
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: 'Ok',
                                    btnClass: 'btn-red',
                                    action: function(){

                                        var order_type = $("input[name='typ_new_"+sell_id+"']:checked").val();
                                        ;
                                        var sell_price = $('#sell_price_'+sell_id).val();
                                       sell_market_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price);
                                    }
                                },
                                    close: function () {
                                }
                            }
                        });
                        //%%%%%%%%%%%%%%%%%%%%%%%End of new status %%%%%%%%%%%%%%%%%%%%%%%%
                    }


            }
        })

    }//End of sell_market_order


    function sell_market_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price){

        //%%%%%%%%%%%%%%%%%%%%%%%%%%
        $.ajax({
            'url': '<?php echo SURL ?>admin/dashboard/sell_market_order_by_user',
            'type': 'POST', //the way you want to send data to your URL
            'data': {sell_id: sell_id,market_value:market_value,quantity:quantity,symbol:symbol,buy_order_id:buy_order_id,order_type,sell_price:sell_price},
            'success': function (response) {
                $("#"+sell_id).html('Sell Now');
                if(response ==''){

                }else{
                    $.confirm({
                        title: 'Encountered an error!',
                        content: response,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'Ok',
                                btnClass: 'btn-red',
                                action: function(){
                                }
                            },
                            close: function () {
                            }
                        }
                    });
                }

            }
        });
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%
    }//End of sell_market_order_by_user


    function  limit_order_cancel(sell_id,market_value,quantity,symbol,buy_order_id){

         $.ajax({
            'url':'<?php echo SURL ?>admin/dashboard/check_status_of_limit_order',
            'type':'POST',
            'data':{sell_id:sell_id,symbol:symbol},
            'success':function(response){

                var rp = JSON.parse(response);
                var resp =rp['status'];
                var current_market_price =rp['market_price'];

                $("#"+sell_id).html('Sell Now');

                    if(resp == 'error'){
                        //%%%%%%%%%%%%%%% if order is an error status%%%%%%%%%%%
                            $.confirm({
                                title: 'Attention!',
                                content: 'The order is an error status. Please Remove the error to sell this order',
                                type: 'red',
                                typeAnimated: true,
                                buttons: {
                                    tryAgain: {
                                        text: 'Ok',
                                        btnClass: 'btn-red',
                                        action: function(){
                                        }
                                    },
                                        close: function () {
                                    }
                                }
                            });
                       //%%%%%%%%%%%%%%%End of  error status%%%%%%%%%%%
                    }else if (resp == 'submitted'){
                        //%%%%%%%%%%%%%%%%%%% submitted status %%%%%%%%%%%%%%%%%%%%%

                            var content_html =' Order is already in <span style="color:orange;    font-size: 14px;"><b>SUBMIT</b></span> status for sell as limit order.'+
                            ' Are you want to  <span style="color:red;    font-size: 14px;"><b>Cancel</b></span>  it ?  And submit to sell agin!\
                            <div class="">\
                            <hr>\
                            <form>\
                            <div class="form-group">\
                            <div class="row"><div class="col-xs-6">\
                            <label>Current Market Price</label>\
                            <input class="form-control" step="any" type="number" name="cu_m_price_'+sell_id+'" value="'+current_market_price+'" disabled>\
                            </div><div class="col-xs-6">\
                            <label>Sell Price</label>\
                            <input class="form-control" step=".00000001" type="number" name="sell_price_'+sell_id+'" value="'+current_market_price+'" id="sell_price_'+sell_id+'">\
                            </div>\
                            </div>'

                            // <div class="radio">\
                            // <label><input type="radio" name="typ_submit_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\
                            // </div>\
                            // <div class="radio">\
                            // <label><input type="radio" name="typ_submit_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\
                            // </div>\


                           content_html +=' <div class="radio ">\
                            <label><input type="radio" name="typ_submit_'+sell_id+'" value="m_current" checked>Fire market order on current market price</label>\
                            </div>\
                            </form>\
                            </div>';

                         $.confirm({
                            title: 'Limit order Cancel  and resend order conformation',
                            content: content_html,
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: 'Ok',
                                    btnClass: 'btn-red',
                                    action: function(){

                                        var order_type = $("input[name='typ_submit_"+sell_id+"']:checked").val();
                                        var sell_price = $('#sell_price_'+sell_id).val();

                                       cancel_and_place_new_limit_order_for_sell(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price);
                                    }
                                },
                                    close: function () {
                                }
                            }
                        });
                         //%%%%%%%%%%%%%%%%%%% End of submitted status %%%%%%%%%%%%%%%%%%%%%
                    }else if (resp == 'new'){

                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%5  New Status%%%%%%%%%%%%%%%%%%%%%%%%%
                        var content_html ='Select from below to sell this order\
                            <div class="">\
                            <hr>\
                            <form>\
                            <div class="form-group">\
                            <div class="row"><div class="col-xs-6">\
                            <label>Current Market Price</label>\
                            <input class="form-control" step="any" type="number" name="cu_m_price_'+sell_id+'" value="'+current_market_price+'" disabled>\
                            </div><div class="col-xs-6">\
                            <label>Sell Price</label>\
                            <input class="form-control" step=".00000001" type="number" name="sell_price_'+sell_id+'" value="'+current_market_price+'" id="sell_price_'+sell_id+'">\
                            </div>\
                            </div>'

                            // <div class="radio">\
                            // <label><input type="radio" name="typ_new_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\
                            // </div>\
                            // <div class="radio">\
                            // <label><input type="radio" name="typ_new_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\
                            // </div>\


                           content_html +=' <div class="radio ">\
                            <label><input type="radio" name="typ_new_'+sell_id+'" value="m_current" checked>Fire market order on current market price</label>\
                            </div>\
                            </form>\
                            </div>';

                         $.confirm({
                            title: 'Sell order conformation',
                            content: content_html,
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: 'Ok',
                                    btnClass: 'btn-red',
                                    action: function(){

                                        var order_type = $("input[name='typ_new_"+sell_id+"']:checked").val();
                                        var sell_price = $('#sell_price_'+sell_id).val();

                                       sell_lmit_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price);
                                    }
                                },
                                    close: function () {
                                }
                            }
                        });
                        //%%%%%%%%%%%%%%%%%%%%%%%End of new status %%%%%%%%%%%%%%%%%%%%%%%%
                    }


            }
        })

    }//End of limit_order_cancel





    function sell_lmit_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price){
        //%%%%%%%%%%%%%%%%%%%%%%%%%%
        $.ajax({
            'url': '<?php echo SURL ?>admin/dashboard/sell_lmit_order_by_user',
            'type': 'POST', //the way you want to send data to your URL
            'data': {sell_id: sell_id,market_value:market_value,quantity:quantity,symbol:symbol,buy_order_id:buy_order_id,order_type,sell_price:sell_price},
            'success': function (response) {
                $("#"+sell_id).html('Sell Now');
                if(response ==''){

                }else{
                    $.confirm({
                        title: 'Encountered an error!',
                        content: response,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'Ok',
                                btnClass: 'btn-red',
                                action: function(){
                                }
                            },
                            close: function () {
                            }
                        }
                    });
                }

            }
        });
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%
    }//End of sell_lmit_order_by_user

    function cancel_and_place_new_limit_order_for_sell(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price){
        $.ajax({
            'url': '<?php echo SURL ?>admin/dashboard/cancel_and_place_new_limit_order_for_sell',
            'type': 'POST', //the way you want to send data to your URL
            'data': {sell_id: sell_id,market_value:market_value,quantity:quantity,symbol:symbol,buy_order_id:buy_order_id,order_type,sell_price:sell_price},
            'success': function (response) {
                $("#"+sell_id).html('Sell Now');
                if(response ==''){

                }else{
                    $.confirm({
                        title: 'Encountered an error!',
                        content: response,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'Ok',
                                btnClass: 'btn-red',
                                action: function(){
                                }
                            },
                            close: function () {
                            }
                        }
                    });
                }

            }
        });
    }//End of cancel_and_place_new_limit_order_for_sell

   function sell_order(id,market_value,quantity,symbol){
        $("#"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

        $.ajax({
            'url': '<?php echo SURL ?>admin/dashboard/sell_order',
            'type': 'POST', //the way you want to send data to your URL
            'data': {id: id,market_value:market_value,quantity:quantity,symbol:symbol},
            'success': function (response) {

                if(response ==1){
                    $("#"+id).html('Sell Now');
                }else{
                    $.confirm({
                        title: 'Encountered an error!',
                        content: response,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'Ok',
                                btnClass: 'btn-red',
                                action: function(){
                                }
                            },
                            close: function () {
                            }
                        }
                    });
                }

            }
        });

    }//End of sell_order


  $("body").on("click",".buy_now_btn",function(e){

        var id = $(this).attr('data-id');
        var market_value = $(this).attr('market_value');
        var quantity = $(this).attr('quantity');
        var symbol = $(this).attr('symbol');

        $.confirm({
                    title: 'Buy Confirmation',
                    content: 'Are you sure you want to Buy Now?',
                    icon: 'fa fa-warning',
                    animation: 'zoom',
                    closeAnimation: 'zoom',
                    opacity: 0.5,
                    buttons: {
                    confirm: {
                        text: 'Yes, sure!',
                        btnClass: 'btn-red',
                        action: function ()
                        {

                            $("#"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

                            $.ajax({
                                'url': '<?php echo SURL ?>admin/dashboard/buy_order',
                                'type': 'POST', //the way you want to send data to your URL
                                'data': {id: id,market_value:market_value,quantity:quantity,symbol:symbol},
                                'success': function (data) {

                                    $("#"+id).html('Buy Now');
                                }
                            });
                        }
                    },
                    cancel: function () {

                    }
                }

        });

    });

    // $("body").on("click",".make_lth_btn",function(e){

    //     var id = $(this).attr('data-id');
     
    //     $.confirm({
    //                 title: 'Confirmation',
    //                 content: 'Are you sure you want to Make it Long Hold?',
    //                 icon: 'fa fa-warning',
    //                 animation: 'zoom',
    //                 closeAnimation: 'zoom',
    //                 opacity: 0.5,
    //                 buttons: {
    //                 confirm: {
    //                     text: 'Yes, sure!',
    //                     btnClass: 'btn-red',
    //                     action: function ()
    //                     {

    //                         $("#btp_"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

    //                         $.ajax({
    //                             'url': '<?php echo SURL ?>admin/buy_orders/lth_status_change',
    //                             'type': 'POST', //the way you want to send data to your URL
    //                             'data': {id: id},
    //                             'success': function (data) {

    //                                 $("#btp_"+id).html('LT Hold');
    //                             }
    //                         });
    //                     }
    //                 },
    //                 cancel: function () {

    //                 }
    //             }

    //     });

    // });



  $("body").on("click",".make_lth_btn",function(e){
    var id = $(this).attr('data-id');
 
    $.confirm({
    title: 'You are going to move your trade to Long Term Pool',
    content: '' +
    '<form action="#" class="formName">' +
    '<div class="form-group">' +
    '<label>Enter Your Desired Profit</label>' +
    '<input type="number" placeholder="Enter Your Desired Profit" class="profit_desire form-control" required />' +
    '</div>' +
    '</form>',
    buttons: {
        formSubmit: {
            text: 'Submit',
            btnClass: 'btn-blue',
            action: function () {
                var target_profit = this.$content.find('.profit_desire').val();
                $("#btp_"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

                $.ajax({
                    'url': '<?php echo SURL ?>admin/buy_orders/lth_status_change',
                    'type': 'POST', //the way you want to send data to your URL
                    'data': {id: id, target_profit : target_profit},
                    'success': function (data) {

                        $("#btp_"+id).html('LT Hold');
                    }
                });
            }
        },
        cancel: function () {
            //close
        },
    },
});
});

  $("body").on("click",".inactive_btn",function(e){

        var id = $(this).attr('data-id');

        $.confirm({
                    title: 'Confirmation',
                    content: 'Are you sure you want to Inactive?<br> Once Inactive you can\'t make it active',
                    icon: 'fa fa-warning',
                    animation: 'zoom',
                    closeAnimation: 'zoom',
                    opacity: 0.5,
                    buttons: {
                    confirm: {
                        text: 'Yes, sure!',
                        btnClass: 'btn-red',
                        action: function ()
                        {

                            $("#order_"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

                            $.ajax({
                                'url': '<?php echo SURL ?>admin/buy_orders/inactive_status',
                                'type': 'POST', //the way you want to send data to your URL
                                'data': {id: id},
                                'success': function (data) {

                                    $("#"+id).hide('slow');
                                }
                            });
                        }
                    },
                    cancel: function () {

                    }
                }

        });

    });

   $("body").on("click",".pause",function(e){

        var id = $(this).attr('data-id');

        $.confirm({
                    title: 'Confirmation',
                    content: 'Are you sure you want to Pause?',
                    icon: 'fa fa-warning',
                    animation: 'zoom',
                    closeAnimation: 'zoom',
                    opacity: 0.5,
                    buttons: {
                    confirm: {
                        text: 'Yes, sure!',
                        btnClass: 'btn-red',
                        action: function ()
                        {

                            $("#porder_"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

                            $.ajax({
                                'url': '<?php echo SURL ?>admin/buy_orders/play_pause_status_change',
                                'type': 'POST', //the way you want to send data to your URL
                                'data': {id: id, type:"pause"},
                                'success': function (resp) {
                                    $("#"+id).hide('slow');
                                }
                            });
                        }
                    },
                    cancel: function () {

                    }
                }

        });

    });

    $("body").on("click",".play",function(e){

        var id = $(this).attr('data-id');

        $.confirm({
                    title: 'Confirmation',
                    content: 'Are you sure you want to Resume?',
                    icon: 'fa fa-warning',
                    animation: 'zoom',
                    closeAnimation: 'zoom',
                    opacity: 0.5,
                    buttons: {
                    confirm: {
                        text: 'Yes, sure!',
                        btnClass: 'btn-red',
                        action: function ()
                        {

                            $("#porder_"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

                            $.ajax({
                                'url': '<?php echo SURL ?>admin/buy_orders/play_pause_status_change',
                                'type': 'POST', //the way you want to send data to your URL
                                'data': {id: id, type:"play"},
                                'success': function (resp) {
                                    $("#"+id).hide('slow');
                                }
                            });
                        }
                    },
                    cancel: function () {

                    }
                }

        });

    });
  $("body").on("click","#buy_all_btn",function(e){

        $.confirm({
                title: 'Buy Confirmation',
                content: 'Are you sure you want to Buy All?',
                icon: 'fa fa-warning',
                animation: 'zoom',
                closeAnimation: 'zoom',
                opacity: 0.5,
                buttons: {
                confirm: {
                    text: 'Yes, sure!',
                    btnClass: 'btn-red',
                    action: function ()
                    {
                        $("#buy_all_btn").html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

                        $.ajax({
                            'url': '<?php echo SURL ?>admin/dashboard/buy_all_orders',
                            'type': 'POST', //the way you want to send data to your URL
                            'data': "",
                            'success': function (response) {

                                var resp = response.split('|');
                                $('#response_market_trading1').html(resp[0]);
                                $('#response_market_trading2').html(resp[1]);
                                $('#response_market_trading3').html(resp[2]);
                                $('#response_market_trading4').html(resp[3]);
                                $('#response_market_trading5').html(resp[4]);
                                $('#response_market_trading6').html(resp[5]);
                                $('#response_market_trading7').html(resp[6]);
                                $('#response_market_trading8').html(resp[7]);

                                $('#counter1').html(resp[8]);
                                $('#counter2').html(resp[9]);
                                $('#counter3').html(resp[10]);
                                $('#counter4').html(resp[11]);
                                $('#counter5').html(resp[12]);
                                $('#counter6').html(resp[13]);
                                $('#counter7').html(resp[14]);
                                $('#counter8').html(resp[15]);

                                $("#buy_all_btn").html('Sell All');


                            }
                        });
                    }
                },
                cancel: function () {

                }
            }

        });

  });


  $("body").on("click",".custom_refresh",function(e){

        var order_id = $(this).attr('order_id');
        var id = $(this).attr('data-id');

        if(order_id !=""){

            $(this).html('<img src="<?php echo IMG ?>loader.gif" width="20" height="20" style="margin-top: -2px;"/>');

            $.ajax({
                'url': '<?php echo SURL ?>admin/dashboard/get_buy_order_status',
                'type': 'POST',
                'data': {id:id,order_id:order_id},
                'success': function (response) {

                }
            });
        }

  });

</script>
<script type="text/javascript">

  $("body").on("click",".tabs-tab", function(e){

        $(".tabs-tab").removeClass("active");
        $(this).addClass("active");
        var thisdt_id = $(this).attr("dt-id");
        $(".tab_pane").removeClass("active");
        $("[dtid="+thisdt_id+"]").addClass("active");

    

        var hrf = "[dtid="+thisdt_id+"]";
        var id  = $(this).attr('id');

        


        $('div.pbsbox').find('a.active').removeClass('active');
        $(this).addClass('active');
        $(hrf).html('<div id="preloader" class="col-md-12" style="text-align: center;"><img src="<?php echo SURL; ?>assets/images/ajax-loader.gif"></div>');
        $.ajax({
            url: "<?php echo SURL; ?>admin/buy_orders/get_order_ajax/1",
            type: "GET",
            data: {status:id},
            success: function(response){
                $(hrf).html(response);
                if(id == 'sold'){
                    $('.trail_stop').hide()
                    $('.trail_stop_td').hide();
                    $('.sold_price_cls').show();
                    $('.sold_price_td_cls').show();
                    
                }else{
                    $('.trail_stop').show();
                    $('.trail_stop_td').show();
                    $('.sold_price_cls').hide();
                    $('.sold_price_td_cls').hide();
                }
            }
        });

  });

 $(document).on("click", ".pagination li a", function(event){
      event.preventDefault();
      var page = $(this).data("ci-pagination-page");
      //load_country_data(page);
      var activeli = $('div#tabss').find('div.pbsbox').find('a.active');
      var hrf = '.tab_pane.active';
      var id  = $(activeli).attr('id');

      
      /*console.log($(this).parent());*/
      $.ajax({
            url: "<?php echo SURL; ?>admin/buy_orders/get_order_ajax/"+page,
            type: "GET",
            data: {status:id},
            success: function(response){
                $(hrf).html(response);


               /* $('.pagination').find('li').removeClass('active');
               // $(".pagination li a").parent().addClass('active');

                $(".pagination li a[data-ci-pagination-page='"+page+"']").parent().addClass('active');

                //$(this).closest('li').addClass('active');
                // console.log($(this).parent());*/

            }
        });

 });

</script>
<!-- Jquery Confirm -->
<script src="<?php echo ASSETS; ?>jquery_confirm/jquery-confirm.min.js"></script>
<script type="text/javascript">

  $("body").on("change","#profit_type",function(e){

    var profit_type = $(this).val();
    $("#sell_profit_price").val('');

    if(profit_type == 'percentage'){
       $('#sell_profit_percent_div').show();
       $('#sell_profit_price_div').hide();
    }else{
      $('#sell_profit_price_div').show();
      $('#sell_profit_percent_div').hide();
    }

  });


  $("body").on("change","#main_symbol",function(e){

    var symbol = $(this).val();

    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/set_currency',
        'type': 'POST', //the way you want to send data to your URL
        'data': {symbol: symbol},
        'success': function (response) { //probably this request will return anything, it'll be put in var "data"

            location.reload();
        }
    });

  });


  $("body").on("change",".application_mode",function(e){

    var mode = $(this).val();
		var name = $(this).attr('name');
		if(name != 'sameName'){
			return false;
		}
    $.ajax({
      'url': '<?php echo SURL ?>admin/dashboard/set_application_mode',
      'type': 'POST', //the way you want to send data to your URL
      'data': {mode: mode},
      'success': function (response) { //probably this request will return anything, it'll be put in var "data"

        location.reload();
      }
    });

  });


  $("body").on("keyup","#sell_profit_percent",function(e){

    var sell_profit_percent = $(this).val();
    var purchased_price = $("#purchased_price").val();

    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/convert_price',
        'type': 'POST', //the way you want to send data to your URL
        'data': {purchased_price:purchased_price,sell_profit_percent: sell_profit_percent},
        'success': function (response) { //probably this request will return anything, it'll be put in var "data"

            $("#sell_profit_price").val(response);
            $('#sell_profit_price_div').show();
        }
    });

  });


</script>
<script type="text/javascript">

  setTimeout(function() {
   autoload_balance();
  }, 60000);

  function autoload_balance()
  {
    $.ajax({
      url: '<?php echo SURL ?>admin/coin_balance',
      type: 'POST',
      data: "",
      success: function (response) {
      }
    });

  }//end autoload_balance

</script>
<script src="<?php echo JS; ?>js.cookie.min.js"></script>
<script type="text/javascript">
  $(document).ready(function()
    {
    //var leftmenu  = "<?php echo $this->session->userdata('leftmenu'); ?>";
    var leftmenu = Cookies.get('sidebar');
    //alert(leftmenu);
    if(leftmenu == 0)
    {
      $('#user_leftmenu_body').addClass('sidebar-mini');
    }
    else
    {
      $('#user_leftmenu_body').removeClass('sidebar-mini');
    }
});


$('#user_leftmenu_setting').on('click', function(e)
{
  //var leftmenu  = "<?php echo $this->session->userdata('leftmenu'); ?>";
  var leftmenu = Cookies.get('sidebar');

  if(leftmenu == 0)
  {
    var leftmenu  = 1;
  }
  else
  {
    var leftmenu  = 0;
  }
  Cookies.set('sidebar', leftmenu, { expires: 7 });
});
</script>
<script src="<?php echo ASSETS; ?>toastr/toastr.js"></script>
<script type="text/javascript">
  function _toastr(_message,_position,_notifyType,_onclick) {

      /** JAVSCRIPT / ON LOAD
       ************************* **/
      if(_message != false) {

          if(_onclick != false) {
            onclick = function() {
              window.location = _onclick;
            }
          } else {
            onclick = null
          }

          toastr.options = {
            "closeButton":      true,
            "debug":        false,
            "newestOnTop":      false,
            "progressBar":      true,
            "positionClass":    "toast-" + _position,
            "preventDuplicates":  false,
            "onclick":        onclick,
            "showDuration":     "300",
            "hideDuration":     "1000",
            "timeOut":        "8000",
            "extendedTimeOut":    "1000",
            "showEasing":       "swing",
            "hideEasing":       "linear",
            "showMethod":       "fadeIn",
            "hideMethod":       "fadeOut"
          }

          setTimeout(function(){
            toastr[_notifyType](_message);
          }, 1000); // delay 1s
      }

  }



  function autoload_notifications(id){

      $.ajax({
        type:'POST',
        url:'<?php echo SURL ?>admin/dashboard/autoload_notifications/'+id,
        data: "",
        success:function(response){

            var res = response.split('|');

            if(res[0] !=""){
               _toastr(res[0],"top-right","success",false);
            }
						var id = res[1];
						if (typeof(id) == 'undefined') {
								id = 0;
						}
            setTimeout(function() {
                  autoload_notifications(id);
            }, 60000);

        }
      });

    }//end autoload_notifications()

    autoload_notifications(0);

</script>
<script>
$(document).ready(function(){
 $('#coins').multiselect({
  nonSelectedText: 'Select Coin',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'100%'
 });
});

$('#popover').click(function () {
        // Start the tour
        var tour = introJs()
        tour.setOption('tooltipPosition', 'auto');
        tour.setOption('positionPrecedence', ['left', 'right', 'top', 'bottom']);
        tour.setOption('showProgress', true)
        tour.start();
        //tour.start();
    });

    $(document).ready(function(){

        var error_tab = <?php echo json_encode($error_tab); ?>;
      
        if(error_tab == 'show'){
            $('#error').show();
        }else{
            $('#error').hide();
        }
    })

</script>


</body>
</html>
