
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


/* %%%%%%%%%%%%%%%% Check Box ON OFF %%%%%%%%%%%%%% */
.onoffswitch {
    position: relative; width: 90px;
    -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
}
.onoffswitch-checkbox {
    display: none;
}
.onoffswitch-label {
    display: block; overflow: hidden; cursor: pointer;
    border: 2px solid #999999; border-radius: 20px;
}
.onoffswitch-inner {
    display: block; width: 200%; margin-left: -100%;
    transition: margin 0.3s ease-in 0s;
}
.onoffswitch-inner:before, .onoffswitch-inner:after {
    display: block; float: left; width: 50%; height: 30px; padding: 0; line-height: 30px;
    font-size: 14px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
    box-sizing: border-box;
}
.onoffswitch-inner:before {
    content: "ON";
    padding-left: 10px;
    background-color: #34A7C1; color: #FFFFFF;
}
.onoffswitch-inner:after {
    content: "OFF";
    padding-right: 10px;
    background-color: #EEEEEE; color: #999999;
    text-align: right;
}
.onoffswitch-switch {
    display: block; width: 18px; margin: 6px;
    background: #FFFFFF;
    position: absolute; top: 0; bottom: 0;
    right: 56px;
    border: 2px solid #999999; border-radius: 20px;
    transition: all 0.3s ease-in 0s;
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
    margin-left: 0;
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
    right: 0px;
}

/* %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */
</style>


<div id="content">
  <h1 class="content-heading bg-white border-bottom">Settings</h1>
  <div class="bg-white innerAll border-bottom">

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


$automatic_selected = '';
$custom_selected = '';
$buy_trading_selected = '';
$sell_trading_selected = '';
$buy_manual_trading_selected = '';
$sell_manual_trading_selected = '';

$live_trading_selected = '';
$test_trading_selected = '';

foreach ($trading as $row) {
    if($row['type'] == 'automatic_on_of_trading'){
      if($row['status'] == 'on'){
        $automatic_selected = 'checked';
      }
    }


    if($row['type'] == 'custom_on_of_trading'){
      if($row['status'] == 'on'){
        $custom_selected = 'checked';
      }
    }


    if($row['type'] == 'buy_on_of_trading'){
      if($row['status'] == 'on'){
        $buy_trading_selected = 'checked';
      }
    }



    if($row['type'] == 'sell_on_of_trading'){
      if($row['status'] == 'on'){
        $sell_trading_selected = 'checked';
      }
    }


    if($row['type'] == 'buy_on_of_manual_trading'){
      if($row['status'] == 'on'){
        $buy_manual_trading_selected = 'checked';
      }
    }


    if($row['type'] == 'sell_on_of_manual_trading'){
      if($row['status'] == 'on'){
        $sell_manual_trading_selected = 'checked';
      }
    }



    if($row['type'] == 'on_of_live_trading'){
      if($row['status'] == 'on'){
        $live_trading_selected = 'checked';
      }
    }


    if($row['type'] == 'on_of_test_trading'){
      if($row['status'] == 'on'){
        $test_trading_selected = 'checked';
      }
    }


   

}//End of foreach trading



?>

         <!-- Form -->
         <div class="widget-body">
            <h4>Trading On-Off </h4>
        </div>
      	<form action="<?php echo SURL; ?>admin/settings/on_off_trading" class="form-horizontal margin-none" id="" method="post" autocomplete="off">
        <div class="widget-body">

          <!-- Row -->
          <div class="row">
              <div class="col-md-2">
              <label class="" style="margin-top: 11px; margin-bottom: 0;">
                  <strong>
                  Custom On-Off Trading
                  </strong>
                </label>
              </div>

                <div class="col-md-4">
                    <div class="onoffswitch">
                            <input type="checkbox" name="custom_on_of_trading" class="onoffswitch-checkbox" id="custom_on_of_trading" value="on"  <?php echo $custom_selected; ?>>
                            <label class="onoffswitch-label" for="custom_on_of_trading">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                            </label>
                    </div>
                </div>
                <!-- %%%%%%%%%%%%%%%%%%%%%%%% -->
                <div class="col-md-2">
                  <label class="" style="margin-top: 11px; margin-bottom: 0;">
                    <strong>
                    Automatic On-Off Trading
                    </strong>
                  </label>
                </div>

                <!-- %%%%%%%%%%%%%%%%%%%%%%%% -->
                <div class="col-md-4">
                    <div class="onoffswitch">
                            <input type="checkbox" name="automatic_on_of_trading" class="onoffswitch-checkbox" id="automatic_on_of_trading" value="on" <?php echo $automatic_selected; ?>>
                            <label class="onoffswitch-label" for="automatic_on_of_trading">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                            </label>
                    </div>
                </div>
          </div>



          <!-- // Row END -->


          <hr class="separator">
          <div class="row">
              <div class="col-md-2">
              <label class="" style="margin-top: 11px; margin-bottom: 0;">
                  <strong>
                  Buy On-Off Trading
                  </strong>
                </label>
              </div>

                <div class="col-md-4">
                    <div class="onoffswitch">
                            <input type="checkbox" name="buy_on_of_trading" class="onoffswitch-checkbox" id="buy_on_of_trading" value="on"  <?php echo $buy_trading_selected; ?>>
                            <label class="onoffswitch-label" for="buy_on_of_trading">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                            </label>
                    </div>
                </div>
                <!-- %%%%%%%%%%%%%%%%%%%%%%%% -->
                <div class="col-md-2">
                  <label class="" style="margin-top: 11px; margin-bottom: 0;">
                    <strong>
                    Sell On-Off Trading
                    </strong>
                  </label>
                </div>

                <!-- %%%%%%%%%%%%%%%%%%%%%%%% -->
                <div class="col-md-4">
                    <div class="onoffswitch">
                            <input type="checkbox" name="sell_on_of_trading" class="onoffswitch-checkbox" id="sell_on_of_trading" value="on" <?php echo $sell_trading_selected; ?>>
                            <label class="onoffswitch-label" for="sell_on_of_trading">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                            </label>
                    </div>
                </div>
          </div>

          <!-- %%%%%%%% Manual Trading ON,OFF %%%%%%%%%%% -->

          <hr class="separator">
          <div class="row">
              <div class="col-md-2">
              <label class="" style="margin-top: 11px; margin-bottom: 0;">
                  <strong>
                  Buy On-Off Manual Trading
                  </strong>
                </label>
              </div>

                <div class="col-md-4">
                    <div class="onoffswitch">
                            <input type="checkbox" name="buy_on_of_manual_trading" class="onoffswitch-checkbox" id="buy_on_of_manual_trading" value="on"  <?php echo $buy_manual_trading_selected; ?>>
                            <label class="onoffswitch-label" for="buy_on_of_manual_trading">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                            </label>
                    </div>
                </div>
                <!-- %%%%%%%%%%%%%%%%%%%%%%%% -->
                <div class="col-md-2">
                  <label class="" style="margin-top: 11px; margin-bottom: 0;">
                    <strong>
                    Sell On-Off Manual Trading
                    </strong>
                  </label>
                </div>

                <!-- %%%%%%%%%%%%%%%%%%%%%%%% -->
                <div class="col-md-4">
                    <div class="onoffswitch">
                            <input type="checkbox" name="sell_on_of_manual_trading" class="onoffswitch-checkbox" id="sell_on_of_manual_trading" value="on" <?php echo $sell_manual_trading_selected; ?>>
                            <label class="onoffswitch-label" for="sell_on_of_manual_trading">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                            </label>
                    </div>
                </div>
          </div>

          <!-- ****** --- Trading ON,OFF by Order Mode  --- ***** -->

          <hr class="separator">
          <div class="row">
              <div class="col-md-2">
              <label class="" style="margin-top: 11px; margin-bottom: 0;">
                  <strong>
                   On-Off Live Trading
                  </strong>
                </label>
              </div>

                <div class="col-md-4">
                    <div class="onoffswitch">
                            <input type="checkbox" name="on_of_live_trading" class="onoffswitch-checkbox" id="on_of_live_trading" value="on"  <?php echo $live_trading_selected; ?>>
                            <label class="onoffswitch-label" for="on_of_live_trading">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                            </label>
                    </div>
                </div>
                <!-- %%%%%%%%%%%%%%%%%%%%%%%% -->
                <div class="col-md-2">
                  <label class="" style="margin-top: 11px; margin-bottom: 0;">
                    <strong>
                     On-Off Test Trading
                    </strong>
                  </label>
                </div>

                <!-- %%%%%%%%%%%%%%%%%%%%%%%% -->
                <div class="col-md-4">
                    <div class="onoffswitch">
                            <input type="checkbox" name="on_of_test_trading" class="onoffswitch-checkbox" id="on_of_test_trading" value="on" <?php echo $test_trading_selected; ?>>
                            <label class="onoffswitch-label" for="on_of_test_trading">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                            </label>
                    </div>
                </div>
          </div>
      
          <hr class="separator" />
          <input type="hidden" value="0" name="submit">
          <!-- Form actions -->
          <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
            <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancel</button>
          </div>
          <!-- // Form actions END -->

        </div>
        </form>
   		<!-- // Form END -->


      </div>
      <!-- // Widget END -->
      <div class="widget widget-inverse">
      <div class="widget-head"><h4 style="margin: 10px;">Recover Backup Order</h4></div>
      <div class="widget-body" style="text-align: center;">
        <button type="button" class="btn btn-lg btn-info" id="restore_order">Restore Backup</button>
      </div>
      </div>
      <script>
        $("body").on("click","#restore_order",function(e){
          $.ajax({
            url:"<?php echo SURL; ?>admin/cron_order_backup/restore_back_up_orders",
            type:"POST",
            data:"",
            success: function(resp){
              $.alert({
                  title: 'Alert!',
                  content: 'Success alert! <strong> Data Has Been Restored Successfully</strong>',
              });
            },
            error: function(e){
              $.alert({
                  title: 'Alert!',
                  content: 'Error alert! <strong> Something Went Wrong</strong>',
              });
            }
          });
        });
      </script>
  </div>
</div>
