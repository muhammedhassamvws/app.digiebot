<!-- Global --> 
<?php $page_url = $this->uri->segment(3); ?>
<script>
	var basePath = '',
		commonPath = '<?php echo ASSETS;?>',
		rootPath = '',
		DEV = false,
		componentsPath = '<?php echo ASSETS;?>components/';
	
	var primaryColor = '#cb4040',
		dangerColor = '#b55151',
		infoColor = '#466baf',
		successColor = '#8baf46',
		warningColor = '#ab7a4b',
		inverseColor = '#45484d';
	
	var themerPrimaryColor = primaryColor;
</script> 
<script src="<?php echo ASSETS;?>components/library/bootstrap/js/bootstrap.min.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/plugins/nicescroll/jquery.nicescroll.min.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/plugins/breakpoints/breakpoints.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/core/js/animations.init.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/charts/flot/assets/lib/jquery.flot.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/charts/flot/assets/lib/jquery.flot.resize.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/charts/flot/assets/lib/plugins/jquery.flot.tooltip.min.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/charts/flot/assets/custom/js/flotcharts.common.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/charts/flot/assets/custom/js/flotchart-simple.init.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/charts/flot/assets/custom/js/flotchart-simple-bars.init.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/widgets/widget-chat/assets/js/widget-chat.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/plugins/slimscroll/jquery.slimscroll.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/forms/elements/bootstrap-datepicker/assets/lib/js/bootstrap-datepicker.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/forms/elements/bootstrap-datepicker/assets/custom/js/bootstrap-datepicker.init.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/charts/easy-pie/assets/lib/js/jquery.easy-pie-chart.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/charts/easy-pie/assets/custom/easy-pie.init.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/widgets/widget-scrollable/assets/js/widget-scrollable.init.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/plugins/holder/holder.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/core/js/sidebar.main.init.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/core/js/sidebar.collapse.init.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/helpers/themer/assets/plugins/cookie/jquery.cookie.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/core/js/core.init.js?v=v1.2.3"></script>

<!-- Form Validation -->
<script src="<?php echo ASSETS;?>components/modules/admin/forms/validator/assets/lib/jquery-validation/dist/jquery.validate.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/modules/admin/forms/validator/assets/custom/form-validator.init.js?v=v1.2.3"></script>
<!-- End Form Validation -->

<script src="<?php echo ASSETS;?>components/modules/admin/forms/wizards/assets/lib/jquery.bootstrap.wizard.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/modules/admin/forms/wizards/assets/custom/js/form-wizards.init.js?v=v1.2.3"></script>

<script src="<?php echo ASSETS;?>components/modules/admin/forms/elements/fuelux-checkbox/fuelux-checkbox.js?v=v1.2.3"></script>


<script src="<?php echo ASSETS;?>components/modules/admin/tables/datatables/assets/lib/js/jquery.dataTables.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/modules/admin/tables/datatables/assets/lib/extras/TableTools/media/js/TableTools.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/modules/admin/tables/datatables/assets/lib/extras/ColVis/media/js/ColVis.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/modules/admin/tables/datatables/assets/custom/js/DT_bootstrap.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/modules/admin/tables/datatables/assets/custom/js/datatables.init.js?v=v1.2.3"></script>


<!-- DropZone -->
<script src="<?php echo ASSETS?>dropzone/dropzone.js"></script>

<!-- Jquery Confirm -->
<script src="<?php echo ASSETS;?>jquery_confirm/jquery-confirm.min.js"></script>


<script>
//function get_states(country_id){}//end check_nic_exist
//Dropzone autodiscover should be false in global scope
Dropzone.autoDiscover = false;
var myDropzone;
var myDropzone22;
var myDropzone33;
$(document).ready(function() { 

	//////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////DROPZONE FILE UPLOAD SCRIPT FOR IMAGES/////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////
	myDropzone = new Dropzone("div#upload_images_dropzone", {
        url: '<?php echo SURL ?>admin/campaigns/upload_image_files',
        addRemoveLinks: true,
    	});
	
	//drop zone sending event. Append extra data along with dropzone ajax file calls
	myDropzone.on("sending", function (file, xhr, formData) {

        var campaign_id = $('#campaign_id').val();
		var variation_id = $('#variation_id').val();
				
		formData.append("campaign_id", campaign_id);
		formData.append("variation_id", variation_id);
        // Will send the filesize along with the file as POST data.

    });

	//if successfull retrive server filename and append it along with file
	myDropzone.on("success", function (file, serverFileName) {
       
        returned_data= $.parseJSON(serverFileName);
        
   		//append the imageid/file_id for remove operation
    	$(file.previewTemplate).attr("id" , returned_data.image_id);
    });
	
	//dropzone delete event. Send a delete ajax request
	myDropzone.on("removedfile", function (file) {
		   
		//var server_file_name = $(file.previewTemplate).children('.server_file_name').text();
		var image_id = $(file.previewTemplate).attr('id');
		
		//send an ajax call to delete the said file from server n db
		ajax_delete_itemimages(image_id)
	
	});

	//delete funtion to remove the file from the server. Adjust it according to our current scenario
	function ajax_delete_itemimages(image_id) {
		
		$.ajax({
			'url': '<?php echo SURL ?>admin/campaigns/remove_uploaded_images',
			'type': 'POST', //the way you want to send data to your URL
			'data': {image_id: image_id},
			'success': function (data) { //probably this request will return anything, it'll be put in var "data"
				console.log(data);
				return;
			}
		});
	}
	
	<?php if($page_url == 'edit-campaign-step2'){?>
	//Preview Files
	var dropzonefiles_data = $.parseJSON($('#dropzone_images_files').val());
	$.each(dropzonefiles_data, function (key, value) {
        file = value;
        var mockFile = {name: file.file_name, size: 200, id: file.id};
        myDropzone.options.addedfile.call(myDropzone, mockFile);
		mockFile.previewElement.classList.add('dz-success');
  	    mockFile.previewElement.classList.add('dz-complete');
		$(mockFile.previewElement).prop('id', file.id);
        //add thumb path incase its an image and thumb is set
	    if(file.path_thumb){
			myDropzone.options.thumbnail.call(myDropzone, mockFile,file.path_thumb);
	    }
    });
	<?php } ?>
	//////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////END DROPZONE FILE UPLOAD SCRIPT FOR IMAGES///////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////DROPZONE FILE UPLOAD SCRIPT FOR CSS/////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////
	myDropzone22 = new Dropzone("div#upload_css_dropzone", {
        url: '<?php echo SURL ?>admin/campaigns/upload_css_files',
        addRemoveLinks: true,
    	});
	
	//drop zone sending event. Append extra data along with dropzone ajax file calls
	myDropzone22.on("sending", function (file, xhr, formData) {

        var campaign_id = $('#campaign_id').val();
		var variation_id = $('#variation_id').val();
				
		formData.append("campaign_id", campaign_id);
		formData.append("variation_id", variation_id);
        // Will send the filesize along with the file as POST data.

    });

	//if successfull retrive server filename and append it along with file
	myDropzone22.on("success", function (file, serverFileName) {
       
        returned_data= $.parseJSON(serverFileName);
        
   		//append the imageid/file_id for remove operation
    	$(file.previewTemplate).attr("id" , returned_data.file_id);
    });
	
	//dropzone delete event. Send a delete ajax request
	myDropzone22.on("removedfile", function (file) {
		   
		//var server_file_name = $(file.previewTemplate).children('.server_file_name').text();
		var file_id = $(file.previewTemplate).attr('id');
		//send an ajax call to delete the said file from server n db
		
		//console.log(server_file_name)
		console.log(file_id)
		ajax_delete_itemcss(file_id)
	
	});
	
	//delete funtion to remove the file from the server. Adjust it according to our current scenario
	function ajax_delete_itemcss(file_id) {
		
		$.ajax({
			'url': '<?php echo SURL ?>admin/campaigns/remove_uploaded_css',
			'type': 'POST', //the way you want to send data to your URL
			'data': {file_id: file_id},
			'success': function (data) { //probably this request will return anything, it'll be put in var "data"
				console.log(data);
				return;
			}
		});
	}
	
	<?php if($page_url == 'edit-campaign-step2'){?>
	//Preview Files
    var dropzonefiles_data22 = $.parseJSON($('#dropzone_css_files').val());
    $.each(dropzonefiles_data22, function (key, value) {
        file = value;
        var mockFile22 = {name: file.file_name, size: 200, id: file.id};
        myDropzone22.options.addedfile.call(myDropzone22, mockFile22);
		mockFile22.previewElement.classList.add('dz-success');
  	    mockFile22.previewElement.classList.add('dz-complete');
		$(mockFile22.previewElement).prop('id', file.id);
        //add thumb path incase its an image and thumb is set
	    if(file.path_thumb){
			myDropzone22.options.thumbnail.call(myDropzone22, mockFile22,file.path_thumb);
	    }
    });
	<?php } ?>
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////END DROPZONE FILE UPLOAD SCRIPT FOR CSS/////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////DROPZONE FILE UPLOAD SCRIPT FOR JS//////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////
	myDropzone33 = new Dropzone("div#upload_js_dropzone", {
        url: '<?php echo SURL ?>admin/campaigns/upload_js_files',
        addRemoveLinks: true,
    	});
	
	//drop zone sending event. Append extra data along with dropzone ajax file calls
	myDropzone33.on("sending", function (file, xhr, formData) {

        var campaign_id = $('#campaign_id').val();
		var variation_id = $('#variation_id').val();
				
		formData.append("campaign_id", campaign_id);
		formData.append("variation_id", variation_id);
        // Will send the filesize along with the file as POST data.

    });

	//if successfull retrive server filename and append it along with file
	myDropzone33.on("success", function (file, serverFileName) {
       
        returned_data= $.parseJSON(serverFileName);
        
   		//append the imageid/file_id for remove operation
    	$(file.previewTemplate).attr("id" , returned_data.file_id);
    });
	
	//dropzone delete event. Send a delete ajax request
	myDropzone33.on("removedfile", function (file) {
		   
		//var server_file_name = $(file.previewTemplate).children('.server_file_name').text();
		var file_id = $(file.previewTemplate).attr('id');
		//send an ajax call to delete the said file from server n db
		
		//console.log(server_file_name)
		console.log(file_id)
		ajax_delete_itemjs(file_id)
	
	});
	
	//delete funtion to remove the file from the server. Adjust it according to our current scenario
	function ajax_delete_itemjs(file_id) {
		
		$.ajax({
			'url': '<?php echo SURL ?>admin/campaigns/remove_uploaded_js',
			'type': 'POST', //the way you want to send data to your URL
			'data': {file_id: file_id},
			'success': function (data) { //probably this request will return anything, it'll be put in var "data"
				console.log(data);
				return;
			}
		});
	}
	
	//Preview Files
    <?php if($page_url == 'edit-campaign-step2'){?>
	var dropzonefiles_data33 = $.parseJSON($('#dropzone_js_files').val());
    $.each(dropzonefiles_data33, function (key, value) {
        file = value;
        var mockFile33 = {name: file.file_name, size: 200, id: file.id};
        myDropzone33.options.addedfile.call(myDropzone33, mockFile33);
		mockFile33.previewElement.classList.add('dz-success');
  	    mockFile33.previewElement.classList.add('dz-complete');
		$(mockFile33.previewElement).prop('id', file.id);
        //add thumb path incase its an image and thumb is set
	    if(file.path_thumb){
			myDropzone33.options.thumbnail.call(myDropzone33, mockFile33,file.path_thumb);
	    }
    });
	<?php } ?>
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////END DROPZONE FILE UPLOAD SCRIPT FOR JS//////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////DROPZONE FILE UPLOAD SCRIPT FOR FONTS///////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////
	myDropzone44 = new Dropzone("div#upload_fonts_dropzone", {
        url: '<?php echo SURL ?>admin/campaigns/upload_fonts_files',
        addRemoveLinks: true,
    	});
	
	//drop zone sending event. Append extra data along with dropzone ajax file calls
	myDropzone44.on("sending", function (file, xhr, formData) {

        var campaign_id = $('#campaign_id').val();
		var variation_id = $('#variation_id').val();
				
		formData.append("campaign_id", campaign_id);
		formData.append("variation_id", variation_id);
        // Will send the filesize along with the file as POST data.

    });

	//if successfull retrive server filename and append it along with file
	myDropzone44.on("success", function (file, serverFileName) {
       
        returned_data= $.parseJSON(serverFileName);
        
   		//append the imageid/file_id for remove operation
    	$(file.previewTemplate).attr("id" , returned_data.file_id);
    });
	
	//dropzone delete event. Send a delete ajax request
	myDropzone44.on("removedfile", function (file) {
		   
		//var server_file_name = $(file.previewTemplate).children('.server_file_name').text();
		var file_id = $(file.previewTemplate).attr('id');
		//send an ajax call to delete the said file from server n db
		
		//console.log(server_file_name)
		console.log(file_id)
		ajax_delete_itemfonts(file_id)
	
	});
	
	//delete funtion to remove the file from the server. Adjust it according to our current scenario
	function ajax_delete_itemfonts(file_id) {
		
		$.ajax({
			'url': '<?php echo SURL ?>admin/campaigns/remove_uploaded_fonts',
			'type': 'POST', //the way you want to send data to your URL
			'data': {file_id: file_id},
			'success': function (data) { //probably this request will return anything, it'll be put in var "data"
				console.log(data);
				return;
			}
		});
	}
	
	//Preview Files
    <?php if($page_url == 'edit-campaign-step2'){?>
	var dropzonefiles_data44 = $.parseJSON($('#dropzone_fonts_files').val());
    $.each(dropzonefiles_data44, function (key, value) {
        file = value;
        var mockFile44 = {name: file.file_name, size: 200, id: file.id};
        myDropzone44.options.addedfile.call(myDropzone44, mockFile44);
		mockFile44.previewElement.classList.add('dz-success');
  	    mockFile44.previewElement.classList.add('dz-complete');
		$(mockFile44.previewElement).prop('id', file.id);
        //add thumb path incase its an image and thumb is set
	    if(file.path_thumb){
			myDropzone44.options.thumbnail.call(myDropzone44, mockFile44,file.path_thumb);
	    }
    });
	<?php } ?>
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////END DROPZONE FILE UPLOAD SCRIPT FOR FONTS///////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////
	
});
</script>

<script type="text/javascript">

  $("body").on("change","#profit_type",function(e){

    var profit_type = $(this).val();
    $("#sell_profit_price").val('');

    if(profit_type == 'percentage'){
       $('#sell_profit_percent_div').show();
       $('#sell_profit_price_div').hide();
    }else{
      $('#sell_profit_price_div').show();
      $('#sell_profit_percent_div').hide();
    }
    
  });


  $("body").on("change","#main_symbol",function(e){

    var symbol = $(this).val();

    $.ajax({
		'url': '<?php echo SURL ?>admin/dashboard/set_currency',
		'type': 'POST', //the way you want to send data to your URL
		'data': {symbol: symbol},
		'success': function (response) { //probably this request will return anything, it'll be put in var "data"
			
			location.reload();
		}
	});
   
  });


  $("body").on("keyup","#sell_profit_percent",function(e){

    var sell_profit_percent = $(this).val();
    var purchased_price = $("#purchased_price").val();

    $.ajax({
		'url': '<?php echo SURL ?>admin/dashboard/convert_price',
		'type': 'POST', //the way you want to send data to your URL
		'data': {purchased_price:purchased_price,sell_profit_percent: sell_profit_percent},
		'success': function (response) { //probably this request will return anything, it'll be put in var "data"
			
			$("#sell_profit_price").val(response);
			$('#sell_profit_price_div').show();
		}
	});
   
  });


</script>



