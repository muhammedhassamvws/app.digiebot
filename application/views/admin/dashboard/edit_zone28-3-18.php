<div id="content">
  <h1 class="content-heading bg-white border-bottom">Edit Target Zone</h1>
  
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


    <div class="widget widget-inverse"> 
    
        <div class="widget widget-inverse"> 
            <form class="form-horizontal margin-none" method="post" action="<?php echo SURL?>admin/dashboard/edit-zone-process" novalidate="novalidate">
            <div class="widget-body"> 
              
              <!-- Row -->
              <div class="row">
                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Enter Start Value</label>
                    <input type="text" name="start_value" value="<?php echo $zone_arr['start_value']; ?>" required="required" class="form-control">
                  </div>
                </div> 
                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Enter End value</label>
                    <input type="text" name="end_value" value="<?php echo $zone_arr['end_value']; ?>" required="required" class="form-control">
                  </div>
                </div> 
                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Type</label>
                    <select name="type" class="form-control">
                      <option value="sell" <?php if($zone_arr['type'] =='sell'){ ?> selected <?php } ?>>Sell</option>
                      <option value="buy" <?php if($zone_arr['type'] =='buy'){ ?> selected <?php } ?>>Buy</option>
                    </select>
                  </div>
                </div>  
               
                
              </div>
              <!-- // Row END -->
              <hr class="separator">
              <input type="hidden" name="id" value="<?php echo $zone_arr['_id']; ?>">
              
              <!-- Form actions -->
              <div class="form-actions">
                <button class="btn btn-success" type="submit"><i class="fa fa-check-circle"></i> Update </button>
              </div>
              <!-- // Form actions END --> 
              
            </div>
            </form>
        </div>    
     
    
  </div>
</div>
