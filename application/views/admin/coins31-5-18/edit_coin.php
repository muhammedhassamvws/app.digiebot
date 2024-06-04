<div id="content">
  <h1 class="content-heading bg-white border-bottom">Edit coin</h1>
  <div class="bg-white innerAll border-bottom">
	<ul class="menubar">
    	<li><a href="<?php echo SURL;?>admin/coins">coins</a></li>
		<li class="active"><a href="<?php echo SURL;?>admin/coins/edit-coin/<?php echo $coin_id;?>">Edit coin</a></li>
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
    	<form action="<?php echo SURL;?>admin/coins/edit_coin_process" class="form-horizontal margin-none" id="validateSubmitForm" method="post" autocomplete="off" enctype= "multipart/form-data">
        <div class="widget-body"> 
          
          <!-- Row -->
          <div class="row"> 
            
            <div class="col-md-12"> 
              <div class="form-group col-md-12">
                <label class="control-label" for="coin_name">Coin Name</label>
                <input class="form-control" id="coin_name" name="coin_name" type="text" required="required" value="<?php echo $coin_arr['coin_name']; ?>" />
              </div>
            </div>
            
            <div class="col-md-12"> 
              <div class="form-group col-md-12">
                <label class="control-label" for="symbol">Symbol</label>
                <input class="form-control" id="symbol" name="symbol" type="text" required="required" value="<?php echo $coin_arr['symbol']; ?>" />
              </div>
            </div>

            <div class="col-md-12"> 
              <div class="form-group col-md-12">
                <label class="control-label" for="symbol">Keywords &nbsp;<small>(Add the keywords "," seprated)</small></label>
               <input type="text" name="keywords" class="form-control" value="<?php echo $coin_arr['coin_keywords']; ?>"> 
              </div>
            </div>

          <div class="col-md-12"> 
              <div class="form-group col-md-12">
                <label class="control-label" for="symbol">Logo</label>
                <input class="form-control" id="logo" name="logo" type="file" />
                
              </div>
            </div>
            <div class="col-md-12" id="alert"></div>
          </div>
          </div>
          <!-- // Row END -->
          
       
          <hr class="separator" />
          
          <!-- Form actions -->
          <div class="form-actions">
            <input name="coin_id" type="hidden" value="<?php echo $coin_arr['id']; ?>" />
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
  $("#logo").change(function () {
         var data = new FormData();
         var files = $("#logo").get(0).files;
         if (files.length > 0) {
            data.append("image", files[0]);
         }
         $.ajax({
          url: '<?php echo SURL; ?>admin/coins/create_thumbnail',
          type: "POST",
         processData: false,
         contentType: false,
         data: data,
         success: function (response) {
             //$("#logo1").val(response);
             $('#alert').html('<p class="alert alert-dismissable alert-success">Image Has been uploaded successfully</p>');
         },
         error: function (er) {
            $('#alert').html('<p class="alert alert-dismissable alert-danger">Image Must Be in JPEG format <br>'+er+'</p>');
         }

      });
      });
</script>