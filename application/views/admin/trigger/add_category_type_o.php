<?php
   $session_post_data = $this->session->userdata('filter_data');
   
   
   
?>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Category</h1>
  <div class="innerAll bg-white border-bottom">
    <ul class="menubar">
      <li><a href="#" data-target="#add_form_modal" data-toggle="modal">Category Lists</a></li>
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

//echo "<pre>";  print_r($category_list_arr); exit;

?>
       
       <!-- start accordion -->
      
       <div class="clearfix"></div>
       <!-- end of accordion -->
       <span id="show_cat_type"></span> 
       
       
                         
      <!-- <form class="form-horizontal form-label-left" id="cat_type_form" action="<?php echo SURL ?>admin/manage-category/add-category-type-process" method="post" enctype="multipart/form-data" novalidate>
        -->
        
         <form action="<?php echo SURL ?>admin/categories/add-category-type-process" class="" id="" method="post">
        <div class="row">
                    <div class=" form-group">
                     <div class="col-md-12">
                        <div class="col-md-6">
                          <label for="standard-list1">Categories</label>
                            <select class="form-control" id="category" name="category" onchange="change_category(this.value)">
                             <option value=""> Select Category</option>
                            <?php foreach($category_list_arr as $category){?>
                            <option value="<?php echo $category['cat_id']; ?>"><?php echo $category['category_name']; ?></option>
                            <?php }?>
                        </select>
                        </div>
                    </div>                      
                  </div>
                            
                          </div>
        
        <!-- hidden Id Goes here--->
       
        <div class="row showdata">
          <div class="col-md-12">
         <div class="col-md-4 col-sm-4 col-xs-4 item form-group">
          <label for="standard-list1">Add Category column</label>
          <input type="text" placeholder="Enter Category Field" name="field_name" id="field_name" class="form-control" required>
         </div>
         
          <div class="col-md-2 col-sm-2 col-xs-2">
                 <label for="standard-list1"> Select depend value </label>
                 <select class=" form-control" id="dep_value" name="dep_value[]"  >
                  <option value=""> Select depend value</option>
                  <?php foreach($category_field['fileds_value'] as $category_field_value){  ?>
                  <option value="<?php  echo $category_field_value['cat_value_id'] ?>">
                  <?php  echo $category_field_value['field_value'] ?>
                  </option>
                  <?php }?>
                 </select>
                </div>
         
         
         <div class="col-md-2 col-sm-12 col-xs-12 form-group">
          <label for="standard-list1">Add column</label>
          <input  type="submit" name="add_cat_type" id="add_cat_type"  class="btn btn-success btn-xm form-control" value="Add Fields" >
         </div>
         </div>
         </div>
        </form>
         
        </div>
     <!--  </form>-->
       
       </div>
      </div>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>
<!-- /page content -->

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->

<link rel="stylesheet" href="<?php echo SURL; ?>assets/images_admin/chosen.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script> 
<script src="<?php echo SURL; ?>assets/images_admin/chosen.jquery.js" type="text/javascript"></script> 
<script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
	
	
	setTimeout(function() {
    $('.w3-panel').fadeOut('fast');
}, 3000); // <-- time in milliseconds
   </script> 
<script>

     $(document).ready(function(e) {
		
      $(function () {
        $('#cat_type_formmmmm').on('submit', function (e) {
			 $('.show_image_add_type').show();
          e.preventDefault();
          $.ajax({
            type: 'post',
            url: '<?php echo SURL ?>admin/manage-category/add-category-type-process',
            data: $('#cat_type_form').serialize(),
            success: function (response) {
		     $('#hide-n-response').empty();
			 $('#show_cat_type').html(response); 
			 document.getElementById("cat_type").value = "";
			 $('.show_image_add_type').hide();
            }
          });
        });
      });
	  
    });
</script> 
<script>
  

  function delete_cattype(field_id,count_id){
	 
		if (confirm("Are you sure you want to delete")) {
			  ///$('.show_image').show();
		$.ajax({
			method:"POST",
			dataType: "json",
			url:"<?php echo SURL?>admin/manage-category/delete-cattype",	
			data:{field_id:field_id,active_row:count_id},
			success: function(responseData){
			 if(responseData.success==true){
				 $('#active-row-'+responseData.active_row).remove();
				 $('.alert-success').append(responseData.message).show();
				  //$('.show_image').hide();
				  ///location.reload();
			 }else{
				 $(".alert-danger").append(responseData.message).show();
				 }
			}
		});	
	}
  }
</script> 
<script>
 
  function change_dropdown(html_structure,cat_type_id){
	 	
		$('.show_image').show();
		$.ajax({
			method:"POST",
			dataType: "json",
			url:"<?php echo SURL?>admin/manage-category/change-cattype",	
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

<script>
 
  function change_categoryyyyy(cat_id){
	  
	 	
		$('.show_image').show();
		$.ajax({
			method:"POST",
			dataType: "json",
			url:"<?php echo SURL?>admin/categories/change-categories",	
			data:{cat_id:cat_id},
			success: function(responseData){
			 if(responseData.success==true){
				 ///$('#active-row-'+responseData.active_row).remove().fadeOut('slow');
				 $('.alert-success').append(responseData.message).show();
				 $('.showdata').append(responseData.finalArray).show();
				 $('.show_image').hide();
			 }else{
				 $(".alert-danger").append(responseData.message).show();
			}
			}
		});
  }
</script> 
