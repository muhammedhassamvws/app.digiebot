

<div id="content">
  <h1 class="content-heading bg-white border-bottom">Settings</h1>
  <div class="bg-white innerAll border-bottom">
	<ul class="menubar">
    	<li class="active"><a href="<?php echo SURL; ?>/admin/settings">Settings</a></li>
      <li><a href="<?php echo SURL; ?>admin/settings/enable_google_auth">Google Authentication</a></li>
      <li><a href="<?php echo SURL; ?>admin/settings/password_change">Change Password</a></li>
      <?php if ($this->session->userdata('user_role') == 1) {
	?>
         <li><a href="<?php echo SURL; ?>admin/settings/update_candle">Update Candle</a></li>
         <li><a href="<?php echo SURL; ?>admin/candle_base">Base Candle Settings</a></li>
         <li><a href="<?php echo SURL; ?>admin/buy_orders/buy_sell_trigger_log">Buy Order Trigger</a></li>
         <li><a href="<?php echo SURL; ?>admin/settings/trigger_setting">Trigger Setting</a></li>
         <li><a href="<?php echo SURL; ?>admin/settings/triggers_global_setting">Trigger_3 Setting</a></li>
         <li><a href="<?php echo SURL; ?>admin/settings/on_off_trading">On-Off Trading</a></li>
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
        <div class="show_message"></div>
         <!-- Form -->
         <div class="widget-body">
            <h4>Convert Trading to Reserved Ips</h4>
        </div>
    
        <!-- %%%%%%%%%%%%%%%%%%%%%%%%%% Form Part Start %%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

          


          <form>
            <div class="row">
              <div class="col-md-3">
              
                <div class="">
                    <input type="checkbox" class="reserved_ip" value="50.28.36.32" >
                    <label for="box-1"> 50.28.36.32  </label>
                </div>
              </div>
              <div class="col-md-4 ">
                  <label for="sel1">Select Ip to convert :</label>
                  <select class="form-control" id="trading_ip">
                  <option value="">Select Ip </option>
                  <option value="50.28.36.48"> 50.28.36.48</option>
                  <option value="50.28.36.49"> 50.28.36.49</option>
                  </select>
              </div>

            </div>
            <div class="row">
              <div class="col-md-1">
              <button style="margin-left:50px;padding:5px" type="button" class="btn btn-primary save_conversion"><i class="fa fa-check-circle"></i> Save</button>
              </div>

              <button style="margin-left:50px;padding:5px;display:none" type="button" class="btn btn-primary wait_save_conversion"> <i class="fa fa-spinner fa-spin" style="font-size:24px"></i></button>
              </div>

              <div class="col-md-4">
              
    
              </div>
            </div>
          </form>

        <!-- %%%%%%%%%%%%%%%%%%%%%%%%%% End of Form Start %%%%%%%%%%%%%%%%%%%%%%%%%%% -->

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>
              Reserved Ip
            </th>
            <th>
              Trading Ip 
            </th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>

        <?php 
          foreach ($ips_arr as $row) {
            ?>
              <tr>
                <td><?php echo $row['reserved_ip'] ?></td>
                <td><?php echo $row['trading_ip'] ?></td>
                <td><a href="
                " class="btn btn-danger unassign_dt" data-id="<?php echo (string)$row['_id']; ?>" onclick="return confirm('Are you sure want to Unassign Reserved Ip')"><i class="fa fa-times"></i></a></td>
              </tr>
            <?php
          }
        ?>
         
          
        </tbody>
      </table>


      <!-- Form actions -->
 
     
     
   		<!-- // Form END -->


      </div>
 
  
  </div>
</div>
<script type="text/javascript">

  $(document).on('click','.unassign_dt',function(e){
      e.preventDefault();
      var Id = $(this).attr('data-id');
      $.ajax({
        url: "<?php echo SURL ?>admin/settings/unassign_convert_trading_on_reserved_ips",
        type: "POST",
        data:{Id: Id},
        success: function()
        {
          //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
            var msg = ' <div class="alert alert-success">Trading Successfully Converted</div>';
            $('.show_message').empty().append(msg);
            setTimeout(function(){
            $('.show_message').empty();
            }, 3000);
            location.reload();
          //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        }
      });

     
  })


  $(document).on('click','.save_conversion',function(){
      
      var reserved_ip = ''; 
      if ($('.reserved_ip').is(':checked'))
      {
         var reserved_ip = $('.reserved_ip').val();
      }

      var trading_ip = $('#trading_ip').val();

      if(reserved_ip == ''){

        var msg = ' <div class="alert alert-danger">Please Select Reserved Ip</div>';
        $('.show_message').empty().append(msg);
            setTimeout(function(){
              $('.show_message').empty();
            }, 3000);

        return false;
      }

      if(trading_ip == ''){

        var msg = ' <div class="alert alert-danger">Please Select Tradding Ip</div>';
        $('.show_message').empty().append(msg);
        setTimeout(function(){
          $('.show_message').empty();
        }, 3000);
        return false;
      }

      $('.save_conversion').hide();
      $('.wait_save_conversion').show();

      $.ajax({
        url: "<?php echo SURL ?>admin/settings/save_convert_trading_on_reserved_ips",
        type: "POST",
        data:{trading_ip: trading_ip, reserved_ip:reserved_ip},
        success: function()
        {
          //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
            $('.save_conversion').show();
            $('.wait_save_conversion').hide();

            var msg = ' <div class="alert alert-success">Trading Successfully Converted On Reserved Ip</div>';
            $('.show_message').empty().append(msg);
            setTimeout(function(){
            $('.show_message').empty();
            }, 3000);
            location.reload();
          //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        }
      });

     

  })// %%%%%%%%% End of Save Conversion %%%%%%%%%%%%%%%%%



</script>
