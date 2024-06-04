<?php
$coins_arr = get_coins();
$global_symbol = $this->session->userdata('global_symbol');
$global_mode = $this->session->userdata('app_mode');
$global_mode1 = $this->session->userdata('global_mode');
?>
<div class="top-header">
        	<div class="sidebar-closer">
            	<a href="javascript:void(0);"><i data-feather="menu"></i></a>
            </div>
            <div class="top-head-left-side">
                <div class="dropdown">
                	<button class="btn btn-default dropdown-toggle s-dropdown-me" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                		<?=$global_symbol;?>
                	</button>
                	<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?php
foreach ($coins_arr as $key => $value) {
    ?>
                                <a class="dropdown-item" href="#" value="<?php echo $value['symbol']; ?>"><?php echo $value['symbol']; ?></a>
                            <?php }
?>
                       <!--  <a class="dropdown-item" href="#" value="BTNBTC">BTNBTC</a>
                        <a class="dropdown-item" href="#" value="TRXBTC">TRXBTC</a>
                        <a class="dropdown-item" href="#" value="BNBBTC">BNBBTC</a> -->
                	</div>
                </div>
            </div>
            <div class="top-head-center">
            	<div class="top-alert top-alert-success">
                	<span class="top-alert-text">Coin <strong>NCASHBTC</strong> bought on 11/05/2018 has been sold at a Profit of 2.8.</span>
                    <span class="top-alert-icon"><i data-feather="trending-up"></i></span>
                </div>
            </div>
            <div class="top-head-right-side">
            	<div class="TradOnoffbox" style="display:none;">
                	<label>Trading</label>
                    <strong class="trad-off">OFF</strong>
                </div>
            	<div class="livechecbox">
                	<label><?php if ($global_mode1 == "live") {echo 'Live';} else {echo 'Test';}?></label>
                    <div class="live-test-checked">
                        <input class="check_live_test" type="checkbox" value="live" <?php if ($global_mode1 == "live") {?>checked="checked"<?php }?>>
                        <span></span>
                    </div>
                </div>
                <div class="top-notification">
                	<a href="javascript:void(0);" class="have-notification"><i data-feather="bell"></i></a>
                    <div class="top-noti-dropdown">
                    	<ul>
                        	<li><a href="#"><span class="ti-icon ti-success"><i data-feather="trending-up"></i></span>Coin <strong>NCASHBTC</strong> bought on 11/05/2018 has been sold at a Profit of....</a></li>
                            <li><a href="#"><span class="ti-icon ti-danger"><i data-feather="trending-down"></i></span>Coin <strong>NCASHBTC</strong> bought on 11/05/2018 has been sold at a Profit of....</a></li>
                            <li><a href="#"><span class="ti-icon ti-success"><i data-feather="trending-up"></i></span>Coin <strong>NCASHBTC</strong> bought on 11/05/2018 has been sold at a Profit of....</a></li>
                            <li><a href="#"><span class="ti-icon ti-success"><i data-feather="trending-up"></i></span>Coin <strong>NCASHBTC</strong> bought on 11/05/2018 has been sold at a Profit of....</a></li>
                            <li><a href="#"><span class="ti-icon ti-danger"><i data-feather="trending-down"></i></span>Coin <strong>NCASHBTC</strong> bought on 11/05/2018 has been sold at a Profit of....</a></li>
                            <li><a href="#"><span class="ti-icon ti-success"><i data-feather="trending-up"></i></span>Coin <strong>NCASHBTC</strong> bought on 11/05/2018 has been sold at a Profit of....</a></li>
                        </ul>
                    </div>
                </div>
                <div class="top-user">
                    <button class="btn-usert dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                		<img class="top-avatar" src="<?php echo NEW_ASSETS; ?>images/avatar.png"><i data-feather="chevron-down"></i>
                	</button>
                	<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="<?php echo SURL; ?>admin2/dashboard/edit_profile"><i data-feather="user"></i> Profile</a>
                        <a class="dropdown-item" href="<?php echo SURL; ?>admin2/coin_market"><i data-feather="pocket"></i> My Wallet</a>
                        <a class="dropdown-item" href="<?php echo SURL; ?>admin2/login/lock_screen"><i data-feather="unlock"></i> My Lock Screen</a>
                        <a class="dropdown-item" href="<?php echo SURL; ?>admin2/logout"><i data-feather="log-out"></i> Log Out</a>
                        <a class="dropdown-item" href="<?php echo SURL; ?>admin2/settings"><i data-feather="settings"></i> Settings</a>
                	</div>
                </div>
            </div>
        </div>