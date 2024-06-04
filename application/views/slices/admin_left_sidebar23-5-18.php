<div id="menu" class="hidden-print hidden-xs">
  <div class="sidebar sidebar-inverse">
    <div class="user-profile media innerAll"> 
    <a href="" class="pull-left">
    <?php if($this->session->userdata('profile_image') !=""){?>
    <img src="<?php echo ASSETS;?>profile_images/<?php echo $this->session->userdata('profile_image');?>" alt="" class="img-circle" width="52" height="52">
    <?php }else{ ?>
    <img src="<?php echo ASSETS;?>images/empty_user.png" alt="" class="img-circle" width="52" height="52">
    <?php } ?>
    </a>
    <div class="media-body"> <a href="" class="strong"><?php echo ucfirst($this->session->userdata('first_name')." ".$this->session->userdata('last_name'));?></a>
    <p class="text-success"><i class="fa fa-fw fa-circle"></i> Online</p>
    </div>
      
    </div>
    <div class="sidebarMenuWrapper"> 
      <ul class="list-unstyled">
        
        <li <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/dashboard'){?> class="active" <?php } ?>>
        <a href="<?php echo SURL?>admin/dashboard"><i class=" icon-projector-screen-line"></i>
        <span>Dashboard</span></a>
        </li>


        <!--li <?php //if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/dashboard/chart'){?> class="active" <?php //} ?>>
        <a href="<?php// echo SURL?>admin/dashboard/chart"><i class=" icon-projector-screen-line"></i>
        <span>Chart</span></a>
        </li>

        <li <?php //if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/dashboard/chart2'){?> class="active" <?php //} ?>>
        <a href="<?php //echo SURL?>admin/dashboard/chart2"><i class=" icon-projector-screen-line"></i>
        <span>Chart2</span></a>
        </li-->

        <li <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/dashboard/chart3'){?> class="active" <?php } ?>>
        <a href="<?php echo SURL?>admin/dashboard/chart3"><i class=" icon-projector-screen-line"></i>
        <span>Chart3</span></a>
        </li>


        <li class="hasSubmenu <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/dashboard/add-zone' || $_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/dashboard/zone-listing'){?>active<?php } ?>"> 
        <a href="#" data-target="#menu-style" data-toggle="collapse"><i class="icon-compose"></i>
        <span>Chart Target Zones</span></a>
          <ul class="collapse <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/dashboard/add-zone' || $_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/dashboard/zone-listing'){?>in<?php } ?>" id="menu-style">
            <li class="<?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/dashboard/zone-listing'){?>active<?php } ?>"><a href="<?php echo SURL?>admin/dashboard/zone-listing">Target Zones Listing</a></li>
            <li class="<?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/dashboard/add-zone'){?>active<?php } ?>"><a href="<?php echo SURL?>admin/dashboard/add-zone">Add Target Zones</a></li>
          </ul>
        </li>




        <li class="hasSubmenu <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/buy_orders/add-buy-order' || $_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/buy_orders/'){?>active<?php } ?>"> 
        <a href="#" data-target="#menu-style3" data-toggle="collapse"><i class="icon-compose"></i>
        <span>Buy Orders</span></a>
          <ul class="collapse <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/buy_orders/add-buy-order' || $_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/buy_orders/'){?>in<?php } ?>" id="menu-style3">
            <li class="<?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/buy_orders/'){?>active<?php } ?>"><a href="<?php echo SURL?>admin/buy_orders/">Orders Listing</a></li>
            <li class="<?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/buy_orders/add-buy-order'){?>active<?php } ?>"><a href="<?php echo SURL?>admin/buy_orders/add-buy-order">Add Order</a></li>
          </ul>
        </li>



        <li class="hasSubmenu <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/sell_orders/add-order' || $_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/sell_orders/'){?>active<?php } ?>"> 
        <a href="#" data-target="#menu-style2" data-toggle="collapse"><i class="icon-compose"></i>
        <span>Sell Orders</span></a>
          <ul class="collapse <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/sell_orders/add-order' || $_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/sell_orders/'){?>in<?php } ?>" id="menu-style2">
            <li class="<?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/sell_orders/'){?>active<?php } ?>"><a href="<?php echo SURL?>admin/sell_orders/">Orders Listing</a></li>
            <!-- <li class="<?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/sell_orders/add-order'){?>active<?php } ?>"><a href="<?php echo SURL?>admin/dashboard/add-order">Add Order</a></li> -->
          </ul>
        </li>
        


        <li <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/dashboard/drawCandlestick'){?> class="active" <?php } ?>>
        <a href="<?php echo SURL?>admin/dashboard/drawCandlestick"><i class=" icon-projector-screen-line"></i>
        <span>Draw Candles Stick</span></a>
        </li>

        <li <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/Candel/drawCandlestick_custom'){?> class="active" <?php } ?>>
        <a href="<?php echo SURL?>admin/Candel/drawCandlestick_custom"><i class=" icon-projector-screen-line"></i>
        <span>Real Custom Candles Stick</span></a>
        </li>

        <?php if ($this->session->userdata('user_role') == 1) {
        ?>
        <li class="hasSubmenu <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/coins' || $_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/coins/add-coin'){?>active<?php } ?>"> 
        <a href="#" data-target="#menu-style4" data-toggle="collapse"><i class="icon-compose"></i>
        <span>Coins</span></a>
          <ul class="collapse <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/coins' || $_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/coins/add-coin'){?>in<?php } ?>" id="menu-style4">
            <li class="<?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/coins'){?>active<?php } ?>"><a href="<?php echo SURL?>admin/coins">Manage Coins</a></li>
            <li class="<?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/coins/add-coin'){?>active<?php } ?>"><a href="<?php echo SURL?>admin/coins/add-coin">Add Coin</a></li>
          </ul>
        </li>
        <?php } ?>
        <?php if ($this->session->userdata('user_role') == 2) {
        ?>
        <li class="hasSubmenu <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/user_coins' || $_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/user_coins/add-coin'){?>active<?php } ?>"> 
        <a href="#" data-target="#menu-style4" data-toggle="collapse"><i class="icon-compose"></i>
        <span>Coins</span></a>
          <ul class="collapse <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/user_coins' || $_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/user_coins/add-coin'){?>in<?php } ?>" id="menu-style4">
            <li class="<?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/coin_market'){?>active<?php } ?>"><a href="<?php echo SURL?>admin/coin_market">Manage Coins</a></li>
            <li class="<?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/user_coins/add-coin'){?>active<?php } ?>"><a href="<?php echo SURL?>admin/user_coins/add-coin">Add Coin</a></li>
          </ul>
        </li>
        <?php } ?>
        <?php if ($this->session->userdata('user_role') == 1) {
        ?>
        <li class="hasSubmenu <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/users' || $_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/users/add-user'){?>active<?php } ?>"> 
        <a href="#" data-target="#menu-style5" data-toggle="collapse"><i class="icon-compose"></i>
        <span>Users</span></a>
          <ul class="collapse <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/users' || $_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/users/add-user'){?>in<?php } ?>" id="menu-style5">
            <li class="<?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/users'){?>active<?php } ?>"><a href="<?php echo SURL?>admin/users">Manage Users</a></li>
            <li class="<?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/users/add-user'){?>active<?php } ?>"><a href="<?php echo SURL?>admin/users/add-user">Add User</a></li>
          </ul>
        </li>

        
        <?php } ?>
        <li <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/settings'){?> class="active" <?php } ?>>
        <a href="<?php echo SURL?>admin/settings/"><i class=" icon-projector-screen-line"></i>
        <span>Settings</span></a>
        </li>

        <?php if ($this->session->userdata('user_role') == 1) {
        ?>
        <li <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/settings/sockets'){?> class="active" <?php } ?>>
        <a href="<?php echo SURL?>admin/sockets/"><i class=" icon-projector-screen-line"></i>
        <span>Binance API Statistics</span></a>
        </li>
        <?php } ?>

        <li <?php if($_SERVER['REQUEST_URI']=='/projects/crypto_trading/admin/Candel/candlesdtick_dyamic'){?> class="active" <?php } ?>>
        <a href="<?php echo SURL?>admin/Candel/candlesdtick_dyamic/"><i class=" icon-projector-screen-line"></i>
        <span>Double Candle Stick</span></a>
        </li>
      </ul>
    </div>
  </div>
</div>