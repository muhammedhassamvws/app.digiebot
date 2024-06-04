<style type="text/css">
  .trigger_1_cls{
    background-color: rgb(255, 230, 230);

  }


  .trigger_2_cls{
    background-color:rgb(153, 102, 102);
    color: white;
  }
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">settings</h1>
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
         <li><a href="<?php echo SURL; ?>admin/settings/trigger_setting">Trigger Setting</a></li>
         <li><a href="<?php echo SURL; ?>admin/settings/triggers_global_setting">Trigger_3 Setting</a></li>
        <?php
}
?>



  </ul>
  </div>
  <div class="innerAll spacing-x2">
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

    <!-- Widget -->
    <div class="widget widget-inverse">

      <div class="widget-body padding-bottom-none">
        <!-- Table -->
        <table class="dynamicTable table table-bordered">

          <!-- Table heading -->
          <thead>
            <tr>
              <th>Coin</th>
              <th>Trigger type</th>
              <th class="trigger_1_cls">buy_1 %</th>
              <th class="trigger_1_cls">buy_2 %</th>
              <th class="trigger_1_cls">buy_2 %</th>

              <th class="trigger_1_cls">sell_1 %</th>
              <th class="trigger_1_cls">sell_2 %</th>
              <th class="trigger_1_cls">sell_3 %</th>

              <th class="trigger_1_cls">trigger_1 stop loss %</th>

              <th class="trigger_2_cls">Buy price % </th>
              <th class="trigger_2_cls">Sell Price %</th>
              <th class="trigger_2_cls">Stop Loss %</th>
              <th>Admin Id</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
           <?php
if (count($trigger_list_arr) > 0) {
	foreach ($trigger_list_arr as $trigger_list) {
		?>
           <tr class="gradeX">
              <td><?php echo $trigger_list['coins']; ?></td>

              <?php if ($trigger_list['triggers_type'] == 'trigger_1') {
			$class = 'trigger_1_cls';
		} else {
			$class = 'trigger_2_cls';
		}?>
              <td class="<?php echo $class; ?>"><?php echo $trigger_list['triggers_type']; ?></td>

              <td class=""><?php echo $trigger_list['buy_part_1_price_percent']; ?></td>
              <td class=""><?php echo $trigger_list['buy_part_2_price_percent']; ?></td>
              <td class=""><?php echo $trigger_list['buy_part_3_price_percent']; ?></td>


              <td class=""><?php echo $trigger_list['sell_part_1_price_percent']; ?></td>
              <td class=""><?php echo $trigger_list['sell_part_2_price_percent']; ?></td>
              <td class=""><?php echo $trigger_list['sell_part_3_price_percent']; ?></td>

              <td class=""><?php echo $trigger_list['Initail_trail_stop_trigger_1']; ?></td>

              <td class=""><?php echo $trigger_list['buy_price']; ?></td>
              <td class=""><?php echo $trigger_list['sell_price']; ?></td>
              <td class=""><?php echo $trigger_list['stop_loss']; ?></td>
              <td class=""><?php echo $trigger_list['admin_id']; ?></td>



              <td class="center">
                <div class="btn-group btn-group-xs ">
                  <!--   <a href="<?php echo SURL . 'admin/settings/edit_trigger_setting/' . $trigger_list['_id']; ?>" class="btn btn-inverse"><i class="fa fa-pencil"></i></a> -->
                    <a href="<?php echo SURL . 'admin/settings/delete_trigger_setting/' . $trigger_list['_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete?')"><i class="fa fa-times"></i></a>
                </div>
               </td>
            </tr>
           <?php }
}?>
          </tbody>

        </table>
        <!-- // Table END -->




      </div>
    </div>
    <!-- // Widget END -->

  </div>
</div>
