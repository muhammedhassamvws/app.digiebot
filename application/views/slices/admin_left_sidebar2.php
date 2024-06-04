<div class="sidebar-menu">
                <ul class="sidebar-menu-ul">
                    <li class="menu-item <?php if ($_SERVER['REQUEST_URI'] == '/admin2/dashboard') { ?>active<?php }?>">
                        <a href="<?php echo SURL ?>admin2/dashboard"><span class="menu-icon"><i data-feather="home"></i></span>Dashboard</a>
                    </li>
                    <li class="menu-item <?php if ($_SERVER['REQUEST_URI'] == '/admin2/indicator') { ?>active<?php }?>">
                        <a href="<?php echo SURL ?>admin2/indicator"><span class="menu-icon"><i data-feather="bar-chart-2"></i></span>Charts</a>
                    </li>
                    <?php if ($this->session->userdata('user_role') == 1) {?>
                    <li class="menu-item has-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/dashboard/add-zone' || $_SERVER['REQUEST_URI'] == '/admin2/dashboard/zone-listing') { ?>active<?php }?>">
                        <a href="javascript:void(0);"><span class="menu-icon"><i data-feather="target"></i></span>Chart Target Zone <span class="menu-child-icon"><i data-feather="chevron-down"></i></span></a>
                        <ul class="menu-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/dashboard/add-zone' || $_SERVER['REQUEST_URI'] == '/admin2/dashboard/zone-listing') { ?>in<?php }?>">
                            <li class="menu-item-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/dashboard/zone-listing') { ?>active<?php }?>">
                                <a href="<?php echo SURL ?>admin2/dashboard/zone-listing">Target Zone Listing</a>
                            </li>
                            <li class="menu-item-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/dashboard/add-zone') { ?>active<?php }?>">
                                <a href="<?php echo SURL ?>admin2/dashboard/add-zone">Add Target Zones</a>
                            </li>
                        </ul>
                    </li>
                    <?php }?>
                    <li class="menu-item has-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/buy_orders/add-buy-order' || $_SERVER['REQUEST_URI'] == '/admin2/buy_orders/' || $_SERVER['REQUEST_URI'] == '/admin2/buy_orders/add_buy_order_triggers/') { ?>active<?php }?>">       
                        <a href="javascript:void(0);"><span class="menu-icon"><i data-feather="tag"></i></span>Buy Orders <span class="menu-child-icon"><i data-feather="chevron-down"></i></span></a>
                        <ul class="menu-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/buy_orders/add-buy-order' || $_SERVER['REQUEST_URI'] == '/admin2/buy_orders/' || $_SERVER['REQUEST_URI'] == '/admin2/buy_orders/add_buy_order_triggers/') { ?>in<?php }?>">
                            <li class="menu-item-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/buy_orders') { ?>active<?php }?>">
                                <a href="<?php echo SURL ?>admin2/buy_orders">Orders Listing</a>
                            </li>
                            <li class="menu-item-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/add-buy-order') { ?>active<?php }?>">
                                <a href="<?php echo SURL ?>admin2/add-buy-order">Add Digie Manual Order</a>
                            </li>
                            <li class="menu-item-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/add_buy_order_triggers') { ?>active<?php }?>">
                                <a href="<?php echo SURL ?>admin2/add_buy_order_triggers">Add Digie Auto Order</a>
                            </li>
                        </ul>
                    </li>
                    <?php if ($this->session->userdata('user_role') == 1) {	?>
                    <li class="menu-item has-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/coins' || $_SERVER['REQUEST_URI'] == '/admin2/coins/add-coin') { ?>active<?php }?>">
                        <a href="javascript:void(0);"><span class="menu-icon"><i data-feather="cpu"></i></span>Coins <span class="menu-child-icon"><i data-feather="chevron-down"></i></span></a>
                        <ul class="menu-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/coins' || $_SERVER['REQUEST_URI'] == '/admin2/coins/add-coin') { ?>in<?php }?>">
                            <li class="menu-item-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/coins') { ?>active<?php }?>">
                                <a href="<?php echo SURL ?>admin2/coins">Manage Coins</a>
                            </li>
                            <li class="menu-item-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/add-coin') { ?>active<?php }?>">
                                <a href="<?php echo SURL ?>admin2/add-coin">Add Coin</a>
                            </li>
                        </ul>
                    </li>
                    <?php }?>
                    <?php if ($this->session->userdata('user_role') == 2) {	?>
                    <li class="menu-item has-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/user_coins' || $_SERVER['REQUEST_URI'] == '/admin2/user_coins/add-coin') {?>active<?php }?>">
                        <a href="javascript:void(0);"><span class="menu-icon"><i data-feather="cpu"></i></span>Coins <span class="menu-child-icon"><i data-feather="chevron-down"></i></span></a>
                        <ul class="menu-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/user_coins' || $_SERVER['REQUEST_URI'] == '/admin2/user_coins/add-coin') {?>in<?php }?>">
                            <li class="menu-item-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/coin_market') {?>active<?php }?>">
                                <a href="<?php echo SURL ?>admin2/coin_market">Manage Coins</a>
                            </li>
                            <li class="menu-item-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/user_coins/add-coin') {?>active<?php }?>">
                                <a href="<?php echo SURL ?>admin2/user_coins/add-coin">Add Coin</a>
                            </li>
                        </ul>
                    </li>
                    <?php }?>
                    <?php if ($this->session->userdata('user_role') == 1 || $this->session->userdata('special_role') == 1) { ?>
                    <li class="menu-item has-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/users' || $_SERVER['REQUEST_URI'] == '/admin2/users/add-user') {?>active<?php }?>">
                        <a href="javascript:void(0);"><span class="menu-icon"><i data-feather="user"></i></span>Users <span class="menu-child-icon"><i data-feather="chevron-down"></i></span></a>
                        <ul class="menu-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/users' || $_SERVER['REQUEST_URI'] == '/admin2/users/add-user') {?>in<?php }?>">
                            <li class="menu-item-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/users') {?>active<?php }?>">
                                <a href="<?php echo SURL ?>admin2/users">Manage Users</a>
                            </li>
                            <li class="menu-item-child <?php if ($_SERVER['REQUEST_URI'] == '/admin2/users/add-user') {?>active<?php }?>">
                                <a href="<?php echo SURL ?>admin2/users/add-user">Add User</a>
                            </li>
                        </ul>
                    </li>
                    <?php if($this->session->userdata('special_role') == 1) { ?>
					<li class="menu-item <?php if ($_SERVER['REQUEST_URI'] == '/admin2/candle_chart') { ?>active<?php }?>">
                        <a href="<?php echo SURL ?>admin2/candle_chart"><span class="menu-icon"><i data-feather="clock"></i></span>15 Minute Candles</a>
                    </li>
                    <li class="menu-item <?php if ($_SERVER['REQUEST_URI'] == '/admin2/chart3_group_trigger') { ?>active<?php }?>">
                        <a href="<?php echo SURL ?>admin2/chart3_group_trigger"><span class="menu-icon"><i data-feather="activity"></i></span>Historical Chart</a>
                    </li>
                    <li class="menu-item <?php if ($_SERVER['REQUEST_URI'] == '/admin2/candel/run') { ?>active<?php }?>">
                        <a href="<?php echo SURL ?>admin2/candel/run"><span class="menu-icon"><i data-feather="bar-chart-2"></i></span>Candles</a>
                    </li>
					<?php }?>
                    <li class="menu-item <?php if ($_SERVER['REQUEST_URI'] == '/admin2/candel_api/run') { ?>active<?php }?>">
                        <a href="<?php echo SURL ?>admin2/candel_api/run"><span class="menu-icon"><i data-feather="activity"></i></span>Historical Candles</a>
                    </li>
                    <li class="menu-item <?php if ($_SERVER['REQUEST_URI'] == '/admin2/trigger/rules') { ?>active<?php }?>">
                        <a  href="<?php echo SURL ?>admin2/trigger/rules"><span class="menu-icon"><i data-feather="activity"></i></span>Trigger Rules</a>
                    </li>
                    <?php if ($this->session->userdata('user_role') == 1) {?>
                    <li class="menu-item  <?php if ($_SERVER['REQUEST_URI'] == '/admin2/rules_order/grid_rules') { ?>active<?php }?>">
                        <a href="<?php echo SURL ?>admin2/rules_order/grid_rules"><span class="menu-icon"><i data-feather="activity"></i></span>Grid Rules</a>
                    </li>
                    <?php }?>
					<?php }?>
                    <li class="menu-item <?php if ($_SERVER['REQUEST_URI'] == '/admin2/settings') { ?>active<?php }?>">
                        <a href="<?php echo SURL ?>admin2/indicator"><span class="menu-icon"><i data-feather="settings"></i></span>Settings</a>
                    </li>
                    <?php if ($this->session->userdata('user_role') == 1) { ?>
                    <li class="menu-item <?php if ($_SERVER['REQUEST_URI'] == '/admin2/sockets') { ?>active<?php }?>">
                        <a href="<?php echo SURL ?>admin2/sockets/"><span class="menu-icon"><i data-feather="airplay"></i></span>Binance API Stats</a>
                    </li>
                    <li class="menu-item <?php if ($_SERVER['REQUEST_URI'] == '/admin2/reports') { ?>active<?php }?>">
                        <a href="<?php echo SURL ?>admin2/reports/"><span class="menu-icon"><i data-feather="alert-octagon"></i></span>Admin Report</a>
                    </li>
                    <li class="menu-item <?php if ($_SERVER['REQUEST_URI'] == '/admin2/api_documentation') { ?>active<?php }?>">
                        <a href="<?php echo SURL ?>admin2/api_documentation/"><span class="menu-icon"><i data-feather="file"></i></span>API Documentation</a>
                    </li>
                    <li class="menu-item <?php if ($_SERVER['REQUEST_URI'] == '/admin2/app_documentation') { ?>active<?php }?>">
                        <a href="<?php echo SURL ?>admin2/app_documentation"><span class="menu-icon"><i data-feather="file"></i></span>App Documentation</a>
                    </li>
                    <li class="menu-item <?php if ($_SERVER['REQUEST_URI'] == '/admin2/settings/function_timelog') { ?>active<?php }?>">
                        <a href="<?php echo SURL ?>admin2/settings/function_timelog"><span class="menu-icon"><i data-feather="file"></i></span>Execution Time</a>
                    </li>
                    <li class="menu-item <?php if ($_SERVER['REQUEST_URI'] == '/admin2/settings/get_barrier_trigger_setting_log') { ?>active<?php }?>">
                        <a href="<?php echo SURL ?>admin2/settings/get_barrier_trigger_setting_log"><span class="menu-icon"><i data-feather="file"></i></span>Trigger Setting Log</a>
                    </li>
                    <?php }?>
                </ul>
            </div>