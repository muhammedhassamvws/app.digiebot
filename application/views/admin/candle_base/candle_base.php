<div id="content">
  <h1 class="content-heading bg-white border-bottom">settings</h1>
  <div class="innerAll bg-white border-bottom"> 
  <ul class="menubar">
  <li><a href="<?php echo SURL; ?>/admin/candle_base/add_base_candle">Add Base Candle</a></li>
  </ul>
  </div>
  <div class="innerAll spacing-x2"> 
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
    
    <!-- Widget -->
    <div class="widget widget-inverse">
      
      <div class="widget-body padding-bottom-none"> 
        <!-- Table -->
        <table class="dynamicTable table table-bordered">
          
          <!-- Table heading -->
          <thead>
            <tr>
              <th>Sr</th>
              <th>Coin</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Base Value</th>
              <th>Action</th>
            </tr>
          </thead>
          
          <tbody>
           <?php 
    		   if(count($settings_arr)>0){
    		   for($i=0; $i<count($settings_arr); $i++){
    		   ?> 
           <tr class="gradeX">
              <td><?php echo $i+1; ?></td>
              <td><?php echo $settings_arr[$i]['coin'];?></td>
              <td><?php echo date('d, M Y G:i:s A', strtotime($settings_arr[$i]['start_date']));?></td>
              <td><?php echo date('d, M Y G:i:s A', strtotime($settings_arr[$i]['end_date']));?></td>
              <td><?php echo $settings_arr[$i]['base_value'];?></td>
              <td class="center">
                <div class="btn-group btn-group-xs ">
                    <a href="<?php echo SURL.'admin/candle_base/edit-base/'.$settings_arr[$i]['id'];?>" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>
                    <a href="<?php echo SURL.'admin/candle_base/delete-base/'.$settings_arr[$i]['id'];?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete?')"><i class="fa fa-times"></i></a>
                </div>
               </td>
            </tr>
           <?php }
		   } ?>
          </tbody>
         
        </table>
        <!-- // Table END --> 
        
        
        
        
      </div>
    </div>
    <!-- // Widget END --> 
    
  </div>
</div>
