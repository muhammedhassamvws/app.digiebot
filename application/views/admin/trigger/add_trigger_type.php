<?php
   $session_post_data = $this->session->userdata('filter_data');
?>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Trigger</h1>
  <div class="innerAll bg-white border-bottom">
    <div class="pricing">
    <div class="title" style="height: 47px;   padding: 15px 15px 0; text-align:left">
     <h2>
      <?php   echo $trigger_arr['trigger_name']; ?>
     </h2>
    </div>
   </div>
   
   
  </div>
  <div class="innerAll spacing-x2">
  <div class="col-md-12">
  <div class="row">
   <div class="alert alert-danger  reponsedan" style="display:none"></div>
   </div>
   <div class="row">
   <div class="alert alert-success reponsesucc alert-dismissable" style="display:none"></div>
   </div>
   </div>
    <?php
if ($this->session->flashdata('err_message')) {
	?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('err_message'); ?></div>
    <?php
}
if ($this->session->flashdata('ok_message')) {
	?>
    <div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('ok_message'); ?></div>
    <?php }?>
       
       <!-- start accordion -->
       <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
        
       <span id="show_cat_type"></span> </div>
       <div class="clearfix"></div>
       
       
          <?php   foreach($trigger_fields['cat_fields_array'] as $key => $field){ ?>
            <div class="row maindev" id="active-row-<?php echo $key?>">
               <div class="col-md-4 col-sm-4 col-xs-4 item form-group">
                  <label for="standard-list1"> Variable Name</label>
                  <input type="text" placeholder="Enter variable" name="variable" id="variable" class="form-control" required value="<?php   echo $field['variable']; ?>" readonly="readonly">
                 </div>
               
               
                <div class="col-md-2 col-sm-2 col-xs-2 item form-group">
                  <label for="standard-list1">Operateor</label>
                  <input type="text" placeholder="Enter Operator" name="field_name" id="field_name" class="form-control" value="<?php   echo $field['field_name']; ?>" required readonly="readonly">
                 </div>
                 
                 
                <div class="col-md-4 col-sm-4 col-xs-4 item form-group">
                  <label for="standard-list1">Value</label>
                  <input type="text" placeholder="Add Value" name="field_value" id="field_value" class="form-control"  value="<?php   echo $field['field_value']; ?>" required readonly="readonly">
                 </div>
                  <div class="col-md-1 col-sm-1 col-xs-1 item form-group">
                  <label for="standard-list1"></label>
                 
                
	    
                  <a  name="delete" id="delete" class=" btn btn-danger btn-xs"  onclick="delete_cattype(<?php echo $field['field_id'] ?>,<?php echo $key;?>)" style="    margin-top: 29px;">X</a>
                  
         
                  
                 </div>
                
                 </div>
                 
          <?php }?>
       
       <hr />
       
       <!-- end of accordion -->
       
        <div class="row ">
       
       <form class="cat_type_form" id="cat_type_form" action="<?php echo SURL ?>admin/trigger/add-rule-type-process" method="post" enctype="multipart/form-data" novalidate>
        
       
        <!-- hidden Id Goes here--->
        <input type="hidden" name="cat_id" id="cat_id"  value="<?php echo $cat_id; ?>">
        
        <div class="col-md-4 col-sm-4 col-xs-4 item form-group">
                  <label for="standard-list1">Add Variables</label>
                   <input type="text" placeholder="Enter variable" name="variable" id="variable" class="form-control variable" required>
                 </div>
        
         <div class="col-md-2 col-sm-2 col-xs-2 item form-group ">
          <label for="standard-list1">Operaters</label>
          <input type="text" placeholder="Enter Operaters" name="field_name" id="field_name" class="form-control field_name" required>
         </div>
         
         
         <div class="col-md-3 col-sm-3 col-xs-3 item form-group">
          <label for="standard-list1">Add Value</label>
          <input type="text" placeholder="Add  Value" name="field_value" id="field_value" class="form-control field_value" required>
         </div>
         
         
         <?php  if(count($trigger_fields['cat_fields_array'])!=''){ ?>
         <!--<div class="col-md-2 col-sm-2 col-xs-2 form-group">
          <label for="standard-list1"> Dependent Fields</label>
          <select class="form-control" id="depend_id" name="depend_id" >
           <option value="">Please Select</option>
           <?php  $i=1; foreach($trigger_fields['cat_fields_array'] as $trigger_field){ ?>
           <option value="<?php echo $trigger_field['field_id'] ?>"><?php echo $trigger_field['field_name'] ?></option>
           <?php }?>
          </select>
         </div>-->
         <?php }?>
         <input type="hidden" name="trigger_id" value="<?php echo $cat_id ?>" id="trigger_id" />
         <div class="col-md-2 col-sm-12 col-xs-12 form-group">
          <label for="standard-list1">&nbsp;&nbsp;</label>
          <input  type="submit" name="add_cat_type" id="add_cat_type"  class="btn btn-success btn-xm" value="Add Rule Data" style="margin-top: 24px; margin-left: 20px;">
         </div>
        
       </form>
       </div>
        </div>
        
        </div>
      </div>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>
<!-- /page content -->


<script>

	function delete_cattype(field_id,count_id){
		
		if (confirm("Are you sure you want to delete")) {
		$.ajax({
			method:"POST",
			 dataType: "json",
			url:"<?php echo SURL?>admin/trigger/delete-cattype",	
			data:{field_id:field_id,active_row:count_id},
			success: function(responseData){
			 if(responseData.success==true){
				 				
				 $('#active-row-'+count_id).remove().fadeOut('slow');
				 $(".reponsesucc").show();
				 $(".reponsesucc").text(responseData.message);
				
			 }else{
				  $(".danger-class").show().fadeIn('slow').fadeOut(3000);
				  $(".reponsedan").show();
			      $('.reponsedan').append(responseData.message);
				 }
			}
		});	
	}
  }
</script> 



 
 
<script>

setTimeout(function() {
    $('.reponsedan').fadeOut('slow');
	$('.reponsesucc').fadeOut('slow');
}, 4000); // <-- time in milliseconds

</script>   
   
   
<script>

     $(document).ready(function(e) {
		
      $(function () {
        $('#cat_type_form').on('submit', function (e) {
			
		  $('.show_image_add_type').show();
          e.preventDefault();
         
		  $.ajax({
			method:"POST",
			dataType: "json",
			url:"<?php echo SURL?>admin/trigger/add_rule_type_process",	
			data: $('#cat_type_form').serialize(),
			success: function(responseData){
			 if(responseData.success==true){
				 $('#hide-n-response').empty();
				 $('.maindev').html('');
				 
			     $('#show_cat_type').html(responseData.finalArray); 
				  $(".reponsesucc").css("display", "block");
				 $(".reponsesucc").text(responseData.message);
			     //document.getElementById("cat_type").value = "";
			     //$('.show_image_add_type').hide();
				 $(this).closest('.cat_type_form').find('.variable').val('');
				  $(this).closest('.cat_type_form').find('.field_name').val('');
				   $(this).closest('.cat_type_form').find('.field_value').val('');
					$('#variable').val('');
					$('#field_name').val('');
					$('#field_value').val('');
			 }else{
				 $(".alert-danger").append(responseData.message).show();
				 }
			}
		});
        });
      });
	  
    });
</script> 

<script>
 
  function change_dropdown(html_structure,cat_type_id){
	 	
		$('.show_image').show();
		$.ajax({
			method:"POST",
			dataType: "json",
			url:"<?php echo SURL?>admin/trigger/change-cattype",	
			data:{cat_type_id:cat_type_id,html_structure:html_structure},
			success: function(responseData){
			 if(responseData.success==true){
				 ///$('#active-row-'+responseData.active_row).remove().fadeOut('slow');
				 $('.alert-success').append(responseData.message).show();
				   $('.show_image').hide();
			 }else{
				 $(".alert-danger").append(responseData.message).show();
				 }
			}
		});
  }
</script> 
