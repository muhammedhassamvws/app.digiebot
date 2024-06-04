
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

?>

         <!-- Form -->
         <div class="widget-body">
            <h4>Trading On-Off </h4>
        </div>
      
                  

        <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
            
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Coin</th>
      <th scope="col">Trigger Type</th>
      <th scope="col">Live Buy</th>
      <th scope="col">Test Buy</th>
      <th scope="col">Live Sell</th>
      <th scope="col">Test Sell</th>
      <th scope="col">Live</th>
      <th scope="col">Test</th>
    </tr>
  </thead>
  <tbody>

    <?php
    $index =1;
    $triggerArr =  array('barrier_percentile_trigger','barrier_trigger','box_trigger_3');
    foreach ($triggerArr as $trigger) {
    foreach ($all_coins_arr as $row) { 
      $statusArr = $this->mod_settings->get_trading_on_off_setting_for_triggers($row['symbol'],$trigger);
      ?>
    <tr>
      <th scope="row"><?php echo $index; ?></th>
      <td> <b><?php echo $row['symbol'];  ?></b></td>
      <td><b><?php echo $trigger; ?></b></td>
      <td>
        <?php 
        $buy_on_off = (isset($statusArr['live_buy']) && $statusArr['live_buy'] == 'yes')?'checked':''; 
 
        ?>

          <div class="onoffswitch ">
              <input type="checkbox" trade_type="live_buy" coin="<?php echo $row['symbol'];  ?>" trigger="<?php echo $trigger; ?>"   name="live_buy_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>" class="onoffswitch-checkbox global_check_box" id="live_buy_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>" value="live_buy*<?php echo $row['symbol']; ?>*<?php echo $trigger; ?>"  <?php echo $buy_on_off; ?>>
              <label class="onoffswitch-label" for="live_buy_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>">
              <span class="onoffswitch-inner"></span>
              <span class="onoffswitch-switch"></span>
              </label>
          </div>
      
      </td>
      <td>
      <?php 
         $buy_on_off = (isset($statusArr['test_buy']) && $statusArr['test_buy'] == 'yes')?'checked':''; 
        ?>

        <div class="onoffswitch">
          <input type="checkbox" name="test_buy_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>" class="onoffswitch-checkbox global_check_box" id="test_buy_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>" value="on"  trade_type="test_buy" coin="<?php echo $row['symbol'];  ?>" trigger="<?php echo $trigger; ?>" <?php echo $buy_on_off; ?>>
          <label class="onoffswitch-label" for="test_buy_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>">
          <span class="onoffswitch-inner"></span>
          <span class="onoffswitch-switch"></span>
          </label>
        </div>
        
      </td>
      <td>
      <?php 
         $buy_on_off = (isset($statusArr['live_sell']) && $statusArr['live_sell'] == 'yes')?'checked':''; 
        ?>
        <div class="onoffswitch">
          <input type="checkbox" name="live_sell_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>" class="onoffswitch-checkbox  global_check_box" id="live_sell_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>" value="on" trade_type="live_sell" coin="<?php echo $row['symbol'];  ?>" trigger="<?php echo $trigger; ?>" <?php echo $buy_on_off; ?>>
          <label class="onoffswitch-label" for="live_sell_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>">
          <span class="onoffswitch-inner"></span>
          <span class="onoffswitch-switch"></span>
          </label>
        </div>
      </td>
      <td>
      <?php 
        $buy_on_off = (isset($statusArr['test_sell']) && $statusArr['test_sell'] == 'yes')?'checked':'';
        ?>
         <div class="onoffswitch">
          <input type="checkbox" name="test_sell_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>" class="onoffswitch-checkbox global_check_box" id="test_sell_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>" value="on"  trade_type="test_sell" coin="<?php echo $row['symbol'];  ?>" trigger="<?php echo $trigger; ?>" <?php echo $buy_on_off; ?>>
          <label class="onoffswitch-label" for="test_sell_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>">
          <span class="onoffswitch-inner"></span>
          <span class="onoffswitch-switch"></span>
          </label>
        </div>
      </td>


      <td>
      <?php 
         $buy_on_off = (isset($statusArr['live']) && $statusArr['live'] == 'yes')?'checked':'';
        ?>
         <div class="onoffswitch">
          <input type="checkbox" name="live_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>" class="onoffswitch-checkbox global_check_box" id="live_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>" value="on" trade_type="live" coin="<?php echo $row['symbol'];  ?>" trigger="<?php echo $trigger; ?>" <?php echo $buy_on_off; ?>>
          <label class="onoffswitch-label" for="live_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>">
          <span class="onoffswitch-inner"></span>
          <span class="onoffswitch-switch"></span>
          </label>
        </div>
      </td>



      <td>
      <?php 
        $buy_on_off = (isset($statusArr['test']) && $statusArr['test'] == 'yes')?'checked':'';
        ?>
         <div class="onoffswitch">
          <input type="checkbox" name="test_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>" class="onoffswitch-checkbox global_check_box" id="test_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>" value="on" trade_type="test" coin="<?php echo $row['symbol'];  ?>" trigger="<?php echo $trigger; ?>" <?php echo $buy_on_off; ?>>
          <label class="onoffswitch-label" for="test_<?php echo $row['symbol']; ?>_<?php echo $trigger; ?>">
          <span class="onoffswitch-inner"></span>
          <span class="onoffswitch-switch"></span>
          </label>
        </div>
      </td>
    </tr>
    
    <?php 
    $index++;
    }
  }    
  ?>
  </tbody>
</table>



        <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

      </div>
      <!-- // Widget END -->
 
 
      <script>
    $(document).ready(function(){


      



        $(document).on("click",".global_check_box",function(e){
          var trigger = $(this).attr('trigger'); 
          var type = $(this).attr('trade_type'); 
          var coin = $(this).attr('coin');
          var status = 'no';
          if($(this).prop("checked") == true){
            status = 'yes';
          }
          
             
      
          $.ajax({
            url:"<?php echo SURL; ?>admin/settings/on_off_trading_by_trigger_coin",
            type:"POST",
            data:{type:type,status:status,coin:coin,trigger:trigger},
            success: function(resp){
              $.alert({
                  title: 'Alert!',
                  content: 'Success alert! <strong>Status Changed Successfully</strong>',
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


        })
      </script>
  </div>
</div>
