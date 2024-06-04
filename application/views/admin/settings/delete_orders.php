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
      <?php if ($this->session->userdata('user_role') == 1 || $this->session->userdata('admin_id') == 1) {
	?>
         <li><a href="<?php echo SURL; ?>admin/settings/update_candle">Update Candle</a></li>
         <li><a href="<?php echo SURL; ?>admin/candle_base">Base Candle Settings</a></li>
         <li><a href="<?php echo SURL; ?>admin/buy_orders/buy_sell_trigger_log">Buy Order Trigger</a></li>
         <li class="active"><a href="<?php echo SURL; ?>admin/settings/trigger_setting">Trigger Setting</a></li>
         <li><a href="<?php echo SURL; ?>admin/settings/triggers_global_setting">Trigger_3 Setting</a></li>

         <li><a href="<?php echo SURL; ?>admin/settings/delete_orders">Delet Orders Setting</a></li>
        <?php
}
?>



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
    <div class="widget widget-inverse">
        <div class="widget-body bg-white">
          <h4>Search Candle</h4>
            <div class="widget-body">

             <form action="<?php echo SURL; ?>admin/settings/add_trigger_settings_process" class="form-horizontal" id="" method="post" >

              <div class="row">
                <input type = "hidden" value = "<?php echo $admin_id; ?>" name = "admin_id">
                      


                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label" for="coin">Select Coin</label>
                        <select class="form-control" name="coins" id="coin" >
                          <option value="">Select Coin</option>
                          <?php
                          foreach ($coins as $coin) {
                            ?>
                            <option value="<?php echo $coin['symbol'] ?>"><?php echo $coin['symbol']; ?></option>
                            <?php 
                            }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label" for="coin">Select User</label>
                        <select class="form-control" name="user" id="user" >
                          <option value="">Select User</option>
                          <?php
                          foreach ($users as $user) {
                            ?>
                            <option value="<?php echo $user['id'] ?>"><?php echo $user['first_name'].'  '.$user['last_name']; ?></option>
                            <?php 
                            }
                          ?>
                        </select>
                      </div>
                    </div>


                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label" for="hour">Select Trigger </label>


                        <select class="form-control triggers_type" name="triggers_type">
                           <option value="">select trigger</option>
                           <option value="trigger_1">trigger_1</option>
                           <option value="trigger_2">trigger_2</option>
                           <option value="box_trigger_3">box_trigger_3</option>
                           <option value="rg_15">box_rg_15</option>
                        </select>

                      </div>
                    </div>


                    <div class="form-group col-md-12">
                        <label class="control-label" for="hour">Select Order Mode </label>
                        <select class="form-control order_mode" name="order_mode">
                             <option value="live">(Real time)</option>
                             <option value="test_live">(Test live)</option>
                             <option value="test">Simulator Test</option>
                        </select>
                      </div>


                       <!--  Trigger_1 parts-->

              









                 

                      
                  </div>
                  <!--  End  Trigger_1 -->



           

          <hr class="separator" />

          <!-- Form actions -->
          <div class="form-actions">
            <p  id="delete_order_id" class="btn btn-primary"><i class="fa fa-check-circle"></i> 
               Delete Orders </p>
            <p class="btn btn-success " id="delete_order_id_wait" style="display: none;"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i> </p>

            <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancel</button>
          </div>
          <!-- // Form actions END -->
          </form>
        </div>
        </div>
      </div>
  </div>

</div>


<script type="text/javascript">

  

  

    $(document).on('click','#delete_order_id',function(){

      if(confirm("Are you sure to Delete Orders!")){
        var coin = $('#coin').val();
        var user = $('#user').val();
        var triggers_type = $('.triggers_type').val();
        var order_mode  = $('.order_mode').val();
           $.ajax({
        'url': '<?php echo base_url(); ?>admin/settings/delete_orders_ajax',
        'data': {coin:coin,user:user,triggers_type:triggers_type,order_mode:order_mode},
        'type': 'POST',
        success : function(data){



        }
      })
      }


    })
 



</script>