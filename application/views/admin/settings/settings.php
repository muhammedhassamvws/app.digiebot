<div id="content">
  <h1 class="content-heading bg-white border-bottom">settings</h1>
  <div class="innerAll bg-white border-bottom"> 
  <ul class="menubar">
  <li class="active"><a href="<?php echo SURL; ?>/admin/coins">Settings</a></li>
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
              <th>API KEY</th>
              <th>API SECRET</th>
              <th>Created Date</th>
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
              <td><?php echo $settings_arr[$i]['api_key'];?></td>
              <td><?php echo $settings_arr[$i]['api_secret'];?></td>
              <td><?php echo date('d, M Y', strtotime($settings_arr[$i]['created_date']));?></td>
              <td class="center">
                <div class="btn-group btn-group-xs ">
                    <a href="<?php echo SURL.'admin/settings/edit-settings/'.$settings_arr[$i]['id'];?>" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>
                    <a href="<?php echo SURL.'admin/settings/delete-settings/'.$settings_arr[$i]['id'];?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete?')"><i class="fa fa-times"></i></a>
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
