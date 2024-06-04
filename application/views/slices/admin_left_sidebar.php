<?php   //echo $this->session->userdata('admin_id'); exit; ?>

<?php
$admin_id_ttt = $this->session->userdata('admin_id');
$allow_ids_ttt = [
'5c0912b7fc9aadaac61dd072'
];
?>


<link rel="stylesheet" href="<?php echo ASSETS; ?>css/admin/font-awesome.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div id="menu" class="hidden-print hidden-xs">
  <div class="sidebar sidebar-inverse">
    <div class="user-profile media innerAll">
    <a href="" class="pull-left">
    <?php if ($this->session->userdata('profile_image') != "") {?>
        <img src="<?php echo ASSETS; ?>profile_images/<?php echo $this->session->userdata('profile_image'); ?>" alt="" class="img-circle" width="52" height="52">
    <?php } else {?>
        <img src="<?php echo ASSETS; ?>images/empty_user.png" alt="" class="img-circle" width="52" height="52">
    <?php }?>
    </a>
    <div class="media-body"> <a href="" class="strong"><?php echo ucfirst($this->session->userdata('first_name') . " " . $this->session->userdata('last_name')); ?></a>
    <p class="text-success"><i class="fa fa-fw fa-circle"></i> Online</p>
    </div>
    </div>
    <div class="sidebarMenuWrapper">
      <ul class="list-unstyled">
        <li <?php if ($_SERVER['REQUEST_URI'] == '/admin/dashboard') {?> class="active" <?php }?>>
        <a href="<?php echo SURL ?>admin/dashboard"><i class="fa fa-tachometer"></i>
        <span>Dashboard</span></a>

        </li>
          <?php if ($this->session->userdata('user_role') == 1) { ?>
            <li class="hasSubmenu <?php if ($_SERVER['REQUEST_URI'] == '/admin/user_coins' || $_SERVER['REQUEST_URI'] == '/admin/user_coins/add-coin') {?>active<?php }?>">
            <a href="#" data-target="#menu-style4" data-toggle="collapse"><i class="fa fa-btc"></i> 
            <span>Coins</span></a>
              <ul class="collapse <?php if ($_SERVER['REQUEST_URI'] == '/admin/user_coins' || $_SERVER['REQUEST_URI'] == '/admin/user_coins/add-coin') {?>in<?php }?>" id="menu-style4">
                <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/coins') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/coins">Manage Coins</a></li>
                <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/coins/add_coin') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/Coins/add_coin">Add Coin</a></li>
                <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/rule_calls/update_rule_status') {?>active<?php }?>"><a href="<?php echo SURL ?>/admin/rule_calls/update_rule_status">Bot on/off</a></li>   
                <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/coins/coin_moves') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/coins/coin_moves">Coin Moves</a></li>          
              </ul>
            </li>
          <?php }?>
    <?php if ($this->session->userdata('user_role') == 1) {?>
        <li class="hasSubmenu <?php if ($_SERVER['REQUEST_URI'] == '/admin/dashboard/add-zone' || $_SERVER['REQUEST_URI'] == '/admin/dashboard/zone-listing') {?>active<?php }?>">
        <a href="#" data-target="#menu-style" data-toggle="collapse"><i class="fa fa-bullseye"></i>
        <span>Chart Target Zones</span></a>
          <ul class="collapse <?php if ($_SERVER['REQUEST_URI'] == '/admin/dashboard/add-zone' || $_SERVER['REQUEST_URI'] == '/admin/dashboard/zone-listing') {?>in<?php }?>" id="menu-style">
            <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/dashboard/zone-listing') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/dashboard/zone-listing">Target Zones Listing</a></li>
            <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/dashboard/add-zone') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/dashboard/add-zone">Add Target Zones</a></li>
          </ul>
        </li>
    <?php }?>
    <li class="hasSubmenu <?php if($_SERVER['REQUEST_URI'] == '/admin/buy_orders/create_child_view' || $_SERVER['REQUEST_URI'] == '/admin/Api_notifications/trade_history_report' || $_SERVER['REQUEST_URI'] == '/admin/rule_calls/rules_display' || $_SERVER['REQUEST_URI'] == '/admin/trigger_rule_reports/oppertunity_reports' || $_SERVER['REQUEST_URI'] == '/admin/users_list/investment_report') {?>active<?php }?>">
            <a href="#" data-target="#menu-style5" data-toggle="collapse"><i class="fa fa-line-chart"></i> 
            <span>Reports</span></a>
              <ul class="collapse <?php if($_SERVER['REQUEST_URI'] == '/admin/Crons/cornsHistoryDisplay' || $_SERVER['REQUEST_URI'] == '/admin/Api_notifications/trade_history_report' || $_SERVER['REQUEST_URI'] == '/admin/rule_calls/rules_display' || $_SERVER['REQUEST_URI'] == '/admin/trigger_rule_reports/oppertunity_reports' || $_SERVER['REQUEST_URI'] == '/admin/users_list/investment_report') {?>in<?php }?>" id="menu-style5">
                <li class="<?php if ($_SERVER['REQUEST_URI'] == 'admin/order_report') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/order_report">Order Report</a></li>
                <!-- <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/trigger_rule_reports/oppertunity_reports') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/trigger_rule_reports/oppertunity_reports">Opportunity Report</a></li>
                <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/users_list/investment_report') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/users_list/investment_report">Investment Detail</a></li> -->
                <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/rule_calls/rules_display') {?>active<?php }?>"><a href="<?php echo SURL ?>/admin/rule_calls/rules_display">Rule Setting</a></li>   
                <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/Api_notifications/trade_history_report') {?>active<?php }?>"><a href="<?php echo SURL ?>/admin/Api_notifications/trade_history_report">Data Report</a></li>          
                <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/buy_orders/create_child_view') {?>active<?php }?>"><a href="<?php echo SURL ?>/admin/buy_orders/create_child_view">Create Child</a></li>          
                <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/Crons/cornsHistoryDisplay') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/Crons/cornsHistoryDisplay">Cron Stop History</a></li>
                <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/coins/coinSlippage') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/coins/coinSlippage">Coin Slippage Report</a></li>
             <!--    <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/Monthly_trading_volume/fee_analysis') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/Monthly_trading_volume/fee_analysis">Monthly Trading Volume</a></li> -->
                 <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/users_list/users_balance_report') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/users_list/users_balance_report">IPs Report</a></li>
                 <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/reports/test_cases_listing') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/reports/test_cases_listing">Users Testcases</a></li>
              </ul>
          </li>
         <li class="hasSubmenu <?php if ($_SERVER['REQUEST_URI'] == '/admin/buy_orders/update_now_order' || $_SERVER['REQUEST_URI'] == '/admin/buy_orders/get_sold' || $_SERVER['REQUEST_URI'] == '/admin/buy_orders/get_parent_order') {?>active<?php }?>">
            <a href="#" data-target="#menu-style6" data-toggle="collapse"><i class="fa fa-edit"></i> 
            <span>Edit Reports</span></a>
              <ul class="collapse <?php if ($_SERVER['REQUEST_URI'] == '/admin/buy_orders/PercentageUpDown' || $_SERVER['REQUEST_URI'] == '/admin/buy_orders/update_now_order' || $_SERVER['REQUEST_URI'] == '/admin/buy_orders/get_sold' || $_SERVER['REQUEST_URI'] == '/admin/buy_orders/get_parent_order') {?>in<?php }?>" id="menu-style6">
                <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/buy_orders/update_now_order') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/buy_orders/update_now_order">Edit Buy Order</a></li>
                <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/buy_orders/get_sold') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/buy_orders/get_sold">Edit Sold Order</a></li>
                <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/buy_orders/get_parent_order') {?>active<?php }?>"><a href="<?php echo SURL ?>/admin/buy_orders/get_parent_order">Edit Parent</a></li>            
                <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/buy_orders/PercentageUpDown') {?>active<?php }?>"><a href="<?php echo SURL ?>/admin/buy_orders/PercentageUpDown">P/L change</a></li>            
              </ul>
          </li>
      <?php if ($this->session->userdata('user_role') == 1 || $this->session->userdata('special_role') == 1 || $this->session->userdata('admin_id')== '5c3a4986fc9aad6bbd55b4f2') { ?>
                 <li class="hasSubmenu <?php if ($_SERVER['REQUEST_URI'] == '/admin/users' || $_SERVER['REQUEST_URI'] == '/admin/users/add-user') {?>active<?php }?>">
                  <a href="#" data-target="#menu-style7" data-toggle="collapse"><i class="fa fa-users"></i>
                  <span>Users</span></a>
                    <ul class="collapse <?php if ($_SERVER['REQUEST_URI'] == '/admin/users' || $_SERVER['REQUEST_URI'] == '/admin/users/add-user') {?>in<?php }?>" id="menu-style7">
                      <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/users') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/users">Manage Users</a></li>
                      <?php if ($this->session->userdata('user_role') == 1 || $this->session->userdata('special_role') == 1){?>
                      <li class="<?php if ($_SERVER['REQUEST_URI'] == '/admin/users/add-user') {?>active<?php }?>"><a href="<?php echo SURL ?>admin/users/add-user">Add User</a></li>
                      <?php }?>
                    </ul>
                  </li>
      <?php } ?>
 <li <?php if ($_SERVER['REQUEST_URI'] == '/admin/settings') {?> class="active" <?php }?>>
                    <a href="<?php echo SURL ?>admin/settings/password_change"><i class="fa fa-cogs"></i>
                    <span>Settings</span></a>
                  </li>
    <?php if ($this->session->userdata('user_role') == 1) {?>
        <li <?php if ($_SERVER['REQUEST_URI'] == '/admin/api_documentation') {?> class="active" <?php }?>>
        <a href="<?php echo SURL ?>admin/api_documentation/"><i class="fa fa-file-text"></i>
        <span>API Documentation</span></a>
        </li>
        <li <?php if ($_SERVER['REQUEST_URI'] == '/admin/app_documentation') {?> class="active" <?php }?>>
        <a href="<?php echo SURL ?>admin/app_documentation/"><i class="fa fa-file-text"></i>
        <span>App Documentation</span></a>
        </li>
      <?php } ?>
      </ul>

    </div>

  </div>

</div>

