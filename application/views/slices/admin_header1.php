<div class="navbar navbar-fixed-top navbar-primary main" role="navigation">
  <div class="navbar-header pull-left">
    <div class="navbar-brand">
      <div class="pull-left"> 
      <a href="" class="toggle-button toggle-sidebar btn-navbar"><i class="fa fa-bars"></i></a> </div>
      <a href="<?php echo SURL?>admin/dashboard" class="appbrand innerL">Admin Panel</a>
    </div>
  </div>
  <ul class="nav navbar-nav navbar-left">
    <li class=" hidden-xs">
      <form class="navbar-form navbar-left " role="search">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Type in here..."/>
          <div class="input-group-btn">
            <button type="submit" class="btn btn-inverse"><i class="fa fa-search"></i></button>
          </div>
        </div>
      </form>
    </li>
    <li class="dropdown"> <a href="" class="dropdown-toggle user" data-toggle="dropdown"> 
    <?php if($this->session->userdata('profile_image') !=""){?>
    <img src="<?php echo ASSETS;?>profile_images/<?php echo $this->session->userdata('profile_image');?>" alt="" class="img-circle" width="39" height="39">
    <?php }else{ ?>
    <img src="<?php echo ASSETS;?>images/empty_user.png" alt="" class="img-circle" width="39" height="39"/>
    <?php } ?>
    <span class="hidden-xs hidden-sm"> &nbsp; <?php echo ucfirst($this->session->userdata('first_name')." ".$this->session->userdata('last_name'));?></span> <span class="caret"></span></a>
      <ul class="dropdown-menu list pull-right ">
        <li><a href="<?php echo SURL;?>admin/dashboard/edit-profile">Edit Profile <i class="fa fa-pencil pull-right"></i></a></li>
        <li><a href="<?php echo SURL;?>admin/logout">Log out <i class="fa fa-sign-out pull-right"></i></a></li>
      </ul>
    </li>
  </ul>
  
  <ul class="nav navbar-nav navbar-right hidden-xs">
   	<li>
   	<a href="<?php echo SURL;?>admin/logout" class="menu-icon" title="Logout"><i class="fa fa-sign-out"></i></a>
   	</li>
  </ul>
</div>