<!doctype html>
<html lang="en">
<head>
<title>Digiebot Admin</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">

<?php if (!empty($admin_header_script2)) {echo $admin_header_script2;}?>
<link rel="Shortcut Icon" href="<?php echo SURL ?>assets/icons/favicon_ico.ico">

</head>
<body>
<div class="main">
	<div class="sidebar">
      	<div class="sidebar-overlay"></div>
        <div class="sidebar-coverlay">
            <div class="sidebar-logo">
                <a href="<?php echo SURL ?>admin2/dashboard"><img src="<?php echo NEW_ASSETS; ?>images/logo.png"></a>
            </div>
            <?php if (!empty($admin_left_sidebar2)) {echo $admin_left_sidebar2;}?>
        </div>
    </div>
    <div class="content-area">
    	<?php if (!empty($admin_header2)) {echo $admin_header2;}?>
        <div class="clearfix"></div>
        <div class="container-iner">
        	<?php echo $content; ?>
        </div>
    </div>
</div>
<?php if (!empty($admin_footer_script2)) {echo $admin_footer_script2;} ?> 
<script src="<?php echo SURL ?>assets/cdn_links/cdn_vizz_livechat.js"></script>  
</body>
</html>