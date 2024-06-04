<?php
   $session_post_data = $this->session->userdata('filter_data');
?>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Trigger</h1>
  <div class="innerAll bg-white border-bottom">
    <ul class="menubar">
      <!--<li><a href="#" data-target="#add_form_modal" data-toggle="modal">Add Trigger</a></li>-->
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

//echo "<pre>";  print_r($coins); exit;

?>
                        
      <form class="cmxform" id="add_new_trigger_frm" method="POST" action="<?php echo SURL?>admin/trigger/add-new-trigger-process">
                <div class="tab-content border-none padding-none">
                        
                        <div id="myTabContent" class="tab-content">
                          <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                           <div id="data_content" class="tab-pane active">
					<?php
                        if($this->session->flashdata('err_message')){
                    ?>
                            <div class="alert alert-danger"><?php echo $this->session->flashdata('err_message'); ?></div>
                    <?php
                        }//end if($this->session->flashdata('err_message'))
                        
                        if($this->session->flashdata('ok_message')){
                    ?>
                            <div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('ok_message'); ?></div>
                    <?php 
                        }//if($this->session->flashdata('ok_message'))
						
                    ?>
                    <div class="row">
                      <div class="form-group">
                        <div class="col-md-12">
                        <div class="col-md-6">
                        <label for="trigger_name">Trigger Name</label>
                        <input id="trigger_name" name="trigger_name" type="text" class="form-control" placeholder="Trigger Name" value="<?php echo $session_post_data['trigger_name'] ?>" />
                      </div>
                      </div>
                       </div>
                      </div>
                      <!--<div class="row form-group">
                    
                        <div class="col-md-12">
                          <label for="standard-list1">Select Trigger Parent</label>
                            <select class="form-control" id="parent_id" name="parent_id" style="font-size:12px">
                            	<option value="0" selected >Select Parent Trigger</option>
                                    
                            <?php
								for($i=0;$i<$trigger_list_count;$i++){
							?>
									<option title="<?php echo $trigger_list_arr[$i]['trigger_name'] ?>" value="<?php echo $trigger_list_arr[$i]['id'] ?>" <?php echo ($session_post_data['parent_id'] == $trigger_list_arr[$i]['id']) ? 'selected' : ''?>><?php echo stripslashes($trigger_list_arr[$i]['trigger_chain']) ?></option>
                            <?php		
								}//end for
							?>
                        </select>
                        </div>
                    </div>-->
                     <div class="row">
                    <div class=" form-group">
                     <div class="col-md-12">
                        <div class="col-md-6">
                          <label for="standard-list1">Status</label>
                            <select class="form-control" id="status" name="status">
                            <option value="1" <?php echo ($session_post_data['status'] == 1) ? 'selected' : ''?>  >Active</option>
                            <option value="0" <?php echo ($session_post_data['status'] == 0) ? 'selected' : ''?>>InActive</option>
                        </select>
                        </div>
                    </div>                      
                  </div>
                            
                          </div>
                          
                          
                          
                           <div class="row">
                    <div class=" form-group">
                     <div class="col-md-12">
                        <div class="col-md-6">
                    	
                           <input class="submit btn btn-blue" type="submit" name="add_new_cat_sbt" id="add_new_cat_sbt" value="Add Trigger" title="Click to Add Trigger" style="margin-top: 15px; ">
                      </div>
                      </div>
                      </div>
                      </div>
                         
                          
                        </div>
                      </div>
                     
   
              </form>
    </div>
   </div>
  </div>
 </div>
</div>
</div>
<!-- /page content --> 
