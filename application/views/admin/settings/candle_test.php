<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
<style type="text/css">
  .radio-group label {
   overflow: hidden;
} .radio-group input {
    /* This is on purpose for accessibility. Using display: hidden is evil.
    This makes things keyboard friendly right out tha box! */
   height: 1px;
   width: 1px;
   position: absolute;
   top: -20px;
} .radio-group .not-active  {
   color: #3276b1;
   background-color: #fff;
}
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Settings</h1>
  <div class="bg-white innerAll border-bottom">
	<ul class="menubar">
    	<li><a href="<?php echo SURL; ?>/admin/settings">Settings</a></li>
      <li><a href="<?php echo SURL; ?>admin/settings/enable_google_auth">Google Authentication</a></li>
      <li><a href="<?php echo SURL; ?>admin/settings/password_change">Change Password</a></li>
      <li class="active"><a href="<?php echo SURL; ?>admin/settings/update_candle">Update Candle</a></li>
	</ul>
  </div>
  <div class="innerAll spacing-x2">


      <!-- Widget -->
    <div class="widget widget-inverse">
      <?php
if ($this->session->flashdata('err_message')) {
	?>
	  <div class="alert alert-danger"><?php echo $this->session->flashdata('err_message'); ?></div>
	  <?php
}
if ($this->session->flashdata('ok_message')) {
	?>
	  <div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('ok_message'); ?></div>
	  <?php
}
?>
      <!-- // Widget END --><!--
        <input type = "hidden" value = "<?php echo $admin_id; ?>" id="user_id" name = "admin_id"> -->
    <!--http://vizzweb.com/projects/crypto_trading/admin/settings/enable_google_auth/-->
   <form method="post" action="<?php echo SURL; ?>admin/settings/test_rejection">
    <div class="widget widget-inverse">
        <div class="widget-body bg-white">
          <h4>Search Candle</h4>
            <div class="widget-body">
              <div class="row">
                <input type = "hidden" value = "<?php echo $admin_id; ?>" name = "admin_id">
                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label" for="coin">Select Coin</label>
                        <select class="form-control" name="coin" id="coin" >
                          <?php
foreach ($coins as $coin) {
	?>
                          <option value="<?php echo $coin['symbol'] ?>"><?php echo $coin['symbol']; ?></option>
                        <?php }
?>
                        </select>
                      </div>
                    </div>

                     <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label" for="hour">Select Hour</label>
                        <input class="form-control datetime_picker" id="hour" name="hour" type="text" required="required" />
                      </div>
                      <script type="text/javascript">
                        $(function () {
                            $('.datetime_picker').datetimepicker();
                        });
                    </script>
                    </div>

          <hr class="separator" />

          <!-- Form actions -->
          <div class="form-actions">
            <button type="submit" id="clint_info_btn" class="btn btn-primary"><i class="fa fa-check-circle"></i> Search</button>
            <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancel</button>
          </div>
          <!-- // Form actions END -->

        </div>
        </div>
      </div>
  </div>
   </form>
   <div class="widget widget-inverse">
        <div class="widget-body bg-white">
          <h4>Candle Information</h4>
            <div class="widget-body" id="candle_info">
            </div>
        </div>
    </div>
</div>
<!-- <script type="text/javascript">
  $("body").on("click","#clint_info_btn",function(argument) {
    var coin = $("#coin").val();
    var time = $("#hour").val();

    $.ajax({
      url: "<?php echo SURL; ?>admin/settings/get_candle_info",
      data:{coin:coin, time:time},
      type:"POST",
      success: function(response){
        $("#candle_info").html(response);
      }
    });
  });
</script> -->