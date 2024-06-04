<?php
   $session_post_data = $this->session->userdata('filter_data');
?>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Category</h1>
  <div class="innerAll bg-white border-bottom">
    <ul class="menubar">
      <li><a href="#" data-target="#add_form_modal" data-toggle="modal">Edit Category</a></li>
    </ul>
  </div>
  <div class="innerAll spacing-x2">
   
                        
     <form class="cmxform" id="upd_new_category_frm" method="POST" action="<?php echo SURL?>admin/categories/edit-category-process">
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
                       <div class="row form-group">
                    
                        <div class="col-md-6">
                        <label for="category_name">Category Name</label>
                        <input id="category_name" name="category_name" type="text" class="form-control" placeholder="Category Name" value="<?php echo stripslashes($category_arr['category_name']) ?>" />
                      </div>
                      </div>
                      <!--<div class="row form-group">
                    
                        <div class="col-md-12">
                          <label for="standard-list1">Select Category Parent</label>
                            <select class="form-control" id="parent_id" name="parent_id" style="font-size:12px">
                            	<option value="" selected >Select Parent Category</option>
                                <option value="0" title="Parent Category" <?php echo ($category_arr['parent_id'] == 0) ? 'selected' : ''?> >Parent Category</option>
                            
                            <?php
								for($i=0;$i<$category_list_count;$i++){
							?>
									<option title="<?php echo $category_list_arr[$i]['category_name'] ?>" value="<?php echo $category_list_arr[$i]['id'] ?>" <?php echo ($category_arr['parent_id'] == $category_list_arr[$i]['id']) ? 'selected' : ''?>><?php echo stripslashes($category_list_arr[$i]['category_chain']) ?></option>
                            <?php		
								}//end for
							?>
                        </select>
                        </div>
                    </div>-->
                  
                    <div class="row form-group">
                    
                        <div class="col-md-6">
                          <label for="standard-list1">Status</label>
                            <select class="form-control" id="status" name="status">
                            <option value="1" <?php echo ($category_arr['status'] == 1) ? 'selected' : ''?>  >Active</option>
                            <option value="0" <?php echo ($category_arr['status'] == 0) ? 'selected' : ''?>>InActive</option>
                        </select>
                        </div>
                    </div>    
                    
                    
                      
                           <div class="row">
                    <div class=" form-group">
                     <div class="col-md-12">
                        <div class="col-md-6">
                         <input type="hidden" name="cat_id" id="cat_id" value="<?php echo stripslashes($category_arr['cat_id'])?>" readonly>
      <input class="submit btn btn-blue" type="submit" name="upd_new_cat_sbt" id="upd_new_cat_sbt" value="Update Category" title="Click to Update Category" style="margin-top: 15px; margin-left: -8px;">
      </div>                  
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