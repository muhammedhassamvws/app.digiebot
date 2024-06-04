<link rel="stylesheet" type="text/css" href="http://users.digiebot.com/assets/admin/stylesheets/animate.css">
<style type="text/css">
a.btn.btn-default.custom-btn {
    height: 120px;
    width: 75%;
    font-size: 20px;
    background: #fff;
    text-align: center;
    padding: 5px;
    padding-top: 20px;
    border-radius: 10px;
}
.slideInRight {
    -webkit-animation-name: slideInRight;
    animation-name: slideInRight;
}

.animated {
    -webkit-animation-duration: 1s;
    animation-duration: 1s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
}
.dash-main {
    background: #fff none repeat scroll 0 0;
    border: 1px solid #eee;
    box-shadow: 0 1px 23px -20px #e8e8e8;
    float: left;
    padding: 15px;
    width: 100%;
}

.menu-item.animated {
    height: 175px;
    padding-top: 21px;
}

a.btn.btn-default.custom-btn:hover {
    background: #0f1c42;
    color: white !important;
}
.custom-btn i {
    padding-bottom: 14px;
}
@keyframes bounce {
 0%, 20%, 60%, 100% {
   -webkit-transform: translateY(0);
   transform: translateY(0);
 }
 40% {
   -webkit-transform: translateY(-20px);
   transform: translateY(-20px);
 }
 80% {
   -webkit-transform: translateY(-10px);
   transform: translateY(-10px);
 }
}

.menu-item:hover a{
 animation: bounce 1s;
}
</style>
<div id="content">
    <div class="heading-buttons bg-white border-bottom innerAll">
        <h1 class="content-heading padding-none pull-left">All linked Reports </h1>
        <div class="clearfix"></div>
    </div>
    <div class="bg-white innerAll border-bottom">
        <ul class="menubar">
            <li class="active"><a href="<?php echo SURL; ?>admin/trading_reports/All_link_reports/">All Reports</a></li>
            <!-- <li><a href="<?php echo SURL; ?>admin/coin_meta/view_coin_meta">Coin Meta</a></li>
            <li><a href="<?php echo SURL; ?>admin/reports/barrier_listing">Barrier Listing</a></li>
            <li><a href="<?php echo SURL; ?>admin/reports/indicator_listing">Indicator Listing</a></li>
            <li><a href="<?php echo SURL; ?>admin/reports/order_reports">Order Reports</a></li>
            <li><a href="<?php echo SURL; ?>admin/reports/get_user_order_history">Map User Order History</a></li> -->
        </ul>
    </div>
    <div class="innerAll spacing-x2">
        <div class="row">
            <div class="menu-tiles dash-main animated opacity slideInRight" style="opacity: 1;">
            <div class="col-md-3">
                    <div class="menu-item animated">
                        <a href="<?php echo SURL; ?>admin/cron_listing" class="btn btn-default custom-btn">
                            <i class="fa fa-cogs" style="font-size: 35px;text-align: center;"></i>
                            <br> Cronjob Report
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="menu-item animated">
                        <a href="<?php echo SURL; ?>admin/trading_reports/balance_reports/" class="btn btn-default custom-btn">
                            <!-- <i class="fa fa-address-book" ></i> -->
                            <i class="fa fa-history" style="font-size: 35px;text-align: center;"></i>
                            <br> Balance Report
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="menu-item animated">
                        <a href="<?php echo SURL; ?>admin/reports/user_trade_profit_report" class="btn btn-default custom-btn">
                            <i class="fa fa-cogs" style="font-size: 35px;text-align: center;"></i>
                            <br>BTC profit/loss Report
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="menu-item animated">
                        <a href="<?php echo SURL; ?>admin/reports/user_trade_history_report" class="btn btn-default custom-btn">
                            <i class="fa fa-cogs" style="font-size: 35px;text-align: center;"></i>
                            <br>$ profit/loss Report
                        </a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="menu-item animated">
                        <a href="<?php echo SURL; ?>admin/trading_reports/chk_log_duplication/" class="btn btn-default custom-btn">
                            <i class="fa fa-history" style="font-size: 35px;text-align: center;"></i>
                            <br> Duplication Report
                        </a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="menu-item animated">
                        <a href="<?php echo SURL; ?>admin/trading_reports/error_sell/order_reports/" class="btn btn-default custom-btn">
                            <i class="fa fa-history" style="font-size: 35px;text-align: center;"></i>
                            <br> Error in Sell Report
                        </a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="menu-item animated">
                        <a href="<?php echo SURL; ?>admin/trading_reports/" class="btn btn-default custom-btn">
                            <i class="fa fa-briefcase" style="font-size: 35px;text-align: center;"></i>
                            <br> Parent stauts Report
                        </a>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
</div>