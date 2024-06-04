<div id="content">
  <h1 class="content-heading bg-white border-bottom">Edit User</h1>
  <div class="bg-white innerAll border-bottom">
	<ul class="menubar">
    	<li><a href="<?php echo SURL;?>admin/users">Users</a></li>
		<li class="active"><a href="<?php echo SURL;?>admin/users/edit-user/<?php echo $user_id;?>">Edit User</a></li>
	</ul>
  </div>

  <div class="innerAll spacing-x2">


      <!-- Widget -->
      <div class="widget widget-inverse">
      <?php
	  if($this->session->flashdata('err_message')){
	  ?>
	  <div class="alert alert-danger"><?php echo $this->session->flashdata('err_message'); ?></div>
	  <?php
	  }
	  if($this->session->flashdata('ok_message')){
	  ?>
	  <div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('ok_message'); ?></div>
	  <?php
	  }
	  ?>

         <!-- Form -->
    	<form action="<?php echo SURL;?>admin/users/edit_user_process" class="form-horizontal margin-none" id="validateSubmitForm" method="post" autocomplete="off">
        <div class="widget-body">

          <!-- Row -->
          <div class="row">

            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="first_name">First Name</label>
                <input class="form-control" id="first_name" name="first_name" type="text" required="required" value="<?php echo $user_arr['first_name']; ?>" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="last_name">Last Name</label>
                <input class="form-control" id="last_name" name="last_name" type="text" required="required" value="<?php echo $user_arr['last_name']; ?>" />
              </div>
            </div>


            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="username">User Name</label>
                <input class="form-control" id="username" name="username" type="text" required="required" value="<?php echo $user_arr['username']; ?>"  readonly/>
              </div>
            </div>


            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="email_address">Email Address</label>
                <input class="form-control" id="email_address" name="email_address" type="text" required="required" value="<?php echo $user_arr['email_address']; ?>" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="country">Country</label>
                <input class="form-control" id="country" name="country" type="text" required="required" value="<?php echo $user_arr['country']; ?>"/>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="phone_number">Phone Number</label>
                <input class="form-control" id="phone_number" name="phone_number" type="text" required="required" value="<?php echo $user_arr['phone_number']; ?>" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="application_mode">Application Mode</label>
                <select name="application_mode" class="form-control">
                  <option value="">Select Mode</option>
                  <option value="live" <?php if($user_arr['application_mode'] == "live") echo "selected" ?>>Live</option>
                  <option value="test" <?php if($user_arr['application_mode'] == "test") echo "selected" ?>>Test</option>
                  <option value="both" <?php if($user_arr['application_mode'] == "both") echo "selected" ?>>Both</option>
                </select>
              </div>
            </div>
             <?php $kraken_details = get_kraken_details($user_arr['_id']); ?>
            <div class="row" style="text-align: center;font-size: 20px;"><span id='kraken_span' style="background-color: antiquewhite;font-weight: bold;margin-left: 10px;border: 1px solid;border-radius: 8px;padding: 4px;">Kraken Trading IP : <?php echo $kraken_details ?></span> &nbsp; <span id='binance_span' style="background-color: antiquewhite;font-weight: bold;margin-left: 10px;border: 1px solid;border-radius: 8px;padding: 4px;">Binance Trading IP : <?php echo $user_arr['trading_ip'] ?></span></div>
            <div class="row">
              <div id="exchanefilter" class="col-xs-12 col-sm-12 col-md-8" style="padding-bottom: 6px;">
                 <label>Select Exchange: </label>
                 <select id="update_exchange" name="update_exchange" type="text" class="form-control filter_by_name_margin_bottom_sm">
                    <option value="binance">Binance</option>
                    <option value="kraken">Kraken</option>
                 </select>
              </div>  
            </div>
            <div class="row" style="margin-left: 0;">
              <div class="col-md-12" id='TradingIpDiv'>
                
                </div>
              </div>  
            </div>
           
            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="trading_ip">Enable/Disable Authenticator</label>
                <select name="google_auth" class="form-control">
                  <option value="yes" <?php if($user_arr['google_auth'] == "yes") echo "selected" ?>>YES</option>
                  <option value="no" <?php if($user_arr['google_auth'] == "no") echo "selected" ?>>NO</option>
                </select>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password" autocomplete="off" />
              </div>
            </div>
          </div>
          <!-- // Row END -->


          <hr class="separator" />

          <!-- Form actions -->
          <div class="form-actions">
            <input name="user_id" id="user_id" type="hidden" value="<?php echo $user_arr['_id']; ?>" />
            <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>
            <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancel</button>
          </div>
          <!-- // Form actions END -->

        </div>
        </form>
   		<!-- // Form END -->


      </div>
      <!-- // Widget END -->



  </div>
</div>
<script type="text/javascript">
  $(function(){
    $('#update_exchange').trigger('change');
  })
  $("body").on("change","#update_exchange",function(){
     var user_id = $('#user_id').val();
     var exchange = $('#update_exchange').val();
     if(exchange == 'kraken'){
      $('#binance_span').css('background-color','antiquewhite');
      $('#kraken_span').css('background-color','#d7faf8;');
     }else{
      $('#kraken_span').css('background-color','antiquewhite');
      $('#binance_span').css('background-color','#d7faf8;');
     }
     $.ajax({
        'url': 'https://app.digiebot.com/admin/Users/create_trading_ip_html',
        'type': 'post',
        'data': {
            user_id:user_id,
            exchange:exchange
        },
        'success': function (res) {
            console.log(res);
            obj = JSON.parse(res);
            console.log(obj);
            $("#TradingIpDiv").html('');
            $("#TradingIpDiv").html(obj);
        }
    });
});
</script>
