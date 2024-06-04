<!DOCTYPE html>
<html>
<!-- <![endif]-->
<head>
<title>Admin Panel</title>

<?php if (!empty($admin_header_script)) {echo $admin_header_script;}?>

<!-- Meta -->
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
<!-- <link rel="icon" type="image/png" href="<?php //echo SURL ?>assets/icons/favicon_ico.ico"> -->
<style>
.navbar.main.navbar-primary {
    border-bottom-color: #162450;
    background-color: #1E3374;
}
.sidebar.sidebar-inverse .user-profile {
    border-bottom: 1px solid #162450;
    background-color: #0f1c42;
}
.sidebar > .sidebarMenuWrapper{
	background-color: #0f1c42;
}
.sidebar.sidebar-inverse > .sidebarMenuWrapper > ul li.active, .sidebar.sidebar-inverse > .sidebarMenuWrapper > ul li.active:hover > a{
	background-color:#667eea;
}

.mycls {
    color: white;
	background-color: #667eea;
    border-color: rgb(65, 200, 255);
}
.navbar.main.navbar-primary .navbar-brand{
	border-right: 1px solid #162450;
}
.sidebar.sidebar-inverse > .sidebarMenuWrapper > ul li:hover > a{
	background: #667eea;
}
.widget.widget-inverse > .widget-head{
	background-color: #162450;
}
.sidebar.sidebar-inverse > .sidebarMenuWrapper > ul li.active.hasSubmenu ul li a:hover{
	background-color:#667eea;
}
.spacing-x2.innerAll, .spacing-x2 .innerAll, .spacing-x2 .widget.widget-tabs-vertical .widget-body .tab-content, .spacing-x2 .box-generic{
 	 background: white;
}
.navbar.main.navbar-primary .toggle-button > i{
	color:#667eea;
}
.navbar.main.navbar-primary .nav > li > a:hover {
    background-color: #667eea;
}
.tooltip.bottom{
	background-color:#667eea;
}
.sidebar.sidebar-inverse > .sidebarMenuWrapper > ul li.active.hasSubmenu > a[data-toggle="collapse"] {
    background: #1c3888;
}

.sidebar.sidebar-inverse > .sidebarMenuWrapper > ul ul {
    background: #122a6fb8;
}
.sidebar.sidebar-inverse>.sidebarMenuWrapper>ul li.active.hasSubmenu ul li a {
    color: white;
}

.dropdown-menu li.active a, .dropdown-menu li.active:hover a {
    background-color: #1e3374;
}
</style>
    <meta name="google-site-verification" content="gFhfhx6jvmZRZQYRm_Ot1ledmN8bs5ReigRKk4x6JzE" />
</head>

<body class="" id="user_leftmenu_body">

<?php if (!empty($admin_header)) {echo $admin_header;}?>

<?php if (!empty($admin_left_sidebar)) {echo $admin_left_sidebar;}?>

<!-- // Content -->
<?php echo $content; ?>
<!-- // Content END -->

<div class="clearfix"></div>
</div>
<!-- // Main Container Fluid END -->

<?php if (!empty($admin_footer_script)) {echo $admin_footer_script;}?>

<?php
// $session_data = $this->session->userdata();
// if (!empty($session_data['logged_in'])) {
//     $admin_id = $session_data['admin_id'];
//     $username = $session_data['username'];
//     $email_address = $session_data['email_address'];

    ?>
<!-- <script id='vizz-chat' data-user="<?php echo $admin_id ?>" data-username="<?php echo $username ?>" data-useremail="<?php echo $email_address ?>"  data-projectname="Digiebot" data-siteid="5d96d4dc0b6d2339ed75f17b" src="https://forexformoney.com/vizz/cdn_vizz_livechat.js"></script> -->

<?php //} else {?>
<!-- <script id='vizz-chat' data-projectname="Digiebot" data-siteid="5d96d4dc0b6d2339ed75f17b" src="https://forexformoney.com/vizz/cdn_vizz_livechat.js"></script> -->
        <?php //}
?>

</body>
</html>