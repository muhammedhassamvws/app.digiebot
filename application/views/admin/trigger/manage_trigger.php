<?php
   $session_post_data = $this->session->userdata('filter_data');
?>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Rule</h1>
  <div class="innerAll bg-white border-bottom">
    <ul class="menubar pull-right">
       <li><a href="<?php  echo SURL ?>admin/trigger/add_new_trigger"  style="    padding-top: 23px;">Add Rule</a></li>
    </ul>
  </div>
  <br /><br />
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

//echo "<pre>";  print_r($coins); exit;

?>
 <!-- Widget -->

    <div class="widget widget-inverse"> <br />
      <div class="widget-body padding-bottom-none">
        <!-- Table -->
     <!-- <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">-->
      <table id="" class="table table-striped table-bordered bulk_action">
       <thead>
        <tr>
         <th>S.NO</th>
         <th>Rule</th>
       
         <th class=" ">Status</th>
         <th class="">Created Date</th>
         <th class="">Options</th>
        </tr>
       </thead>
       <tbody>
        <?php 
							$Sno =1; for($i=0;$i<count($trigger_list_arr['trigger_list_arr']);$i++){ ?>
        <tr id="active-row-<?php echo $Sno; ?>">
         <td><span class="xedit"><?php echo ($Sno) ?></span></td>
         <td><span class="xedit"><?php echo (stripslashes($trigger_list_arr['trigger_list_arr'][$i]['trigger_name'])) ?></span></td>
        
         <td class=""><?php echo ($trigger_list_arr['trigger_list_arr'][$i]['status'] == 1) ? '<span class="label btn-success" title="Active">Active</span>' : '<span class="label btn-danger" title="InActive">InActive</span>' ?></td>
         <td class=""><?php echo date('d, M Y', strtotime($trigger_list_arr['trigger_list_arr'][$i]['created_date'])) ?></td>
         <td class="text-center">
         
           <a href="<?php echo SURL?>admin/trigger/add-column/<?php echo $trigger_list_arr['trigger_list_arr'][$i]['cat_id']?>" class="btn btn-info btn-xs" title="Add Trigger Field">Rules Fields </a>
         
         <!-- <a href="<?php echo SURL?>admin/trigger/edit-trigger/<?php echo $trigger_list_arr['trigger_list_arr'][$i]['cat_id']?>" type="button" class="btn btn-info btn-xs" title="Edit Trigger"> <i class="fa fa-pencil"></i> Edit </a>-->
          						
          <a onClick="delete_trigger(<?php echo $trigger_list_arr['trigger_list_arr'][$i]['cat_id']?>,<?php echo $Sno;?>)" type="button" class="btn btn-danger btn-xs" title="Delete Trigger "> <i class="fa fa-trash-o"></i> Delete </a>
        </td>
        </tr>
        <?php			
					$Sno++;	}//end for
					?>
       </tbody>
      </table>
   
      </div>
    </div>

    <!-- // Widget END -->

  </div>
</div>
<script>

	function delete_trigger(cat_id,count_id){
		
		if (confirm("Are you sure you want to delete")) {
		$.ajax({
			method:"POST",
			 dataType: "json",
			url:"<?php echo SURL?>admin/trigger/delete-trigger",	
			data:{cat_id:cat_id,active_row:count_id},
			success: function(responseData){
			 if(responseData.success==true){
				 				
				 $('#active-row-'+responseData.active_row).remove().fadeOut('slow');
				 $(".success-class").show().fadeIn('slow').fadeOut(3000);
				 $('#show_message_success').append(responseData.message);
			 }else{
				  $(".danger-class").show().fadeIn('slow').fadeOut(3000);
			      $('#show_message_delete').append(responseData.message);
				 }
			}
		});	
	}
  }
</script> 
