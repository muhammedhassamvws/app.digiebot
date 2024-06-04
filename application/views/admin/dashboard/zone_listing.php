<div id="content">
  <h1 class="content-heading bg-white border-bottom">Target Zones Listing</h1>
  <div class="bg-white innerAll border-bottom">
	<ul class="menubar">
    	<li class="active"><a href="<?php echo SURL; ?>admin/dashboard/zone-listing">Target Zones Listing</a></li>
	</ul>
  </div>
  <div class="innerAll spacing-x2"> 
  	
    <div class="widget widget-inverse">     
        <div class="col-xs-12" style="padding: 25px 20px;">
        
            <div class="back">
            
                <div class="widget widget-inverse widget-scroll">
                    <div class="widget-head" style="height:46px;">               
                    	<h4 class="heading" style=" padding-top: 3px;">Chart Target Zones</h4>
                    </div>
                    <div class="widget-body padding-none">
                        <div id="response_buy_trade">
                        	<table class="dynamicTable table table-bordered">
                                <thead>
                                    <tr>
                                        <th><strong>#</strong></th>
                                        <th><strong>Start Value</strong></th>
                                        <th><strong>End Value</strong></th>
                                        <th><strong>Type</strong></th>
                                        <th><strong>Coin</strong></th>
                                        <th><strong>Start Date</strong></th>
                                        <th><strong>End Date</strong></th>
                                        <th><strong>Created Date</strong></th>
                                        <th class="text-center"><strong>Actions</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(count($chart_target_zones_arr)>0){
                                    foreach ($chart_target_zones_arr as $key=>$value) { ?>
                                    <tr>
                                        <td><?php echo ($key+1); ?></td>
                                        <td>
                                        <?php 
                                        $lenth =  strlen(substr(strrchr($value['start_value'], "."), 1));
                                        if($lenth==6){
                                            $start_value = $value['start_value'].'0';
                                        }else{

                                            $start_value = $value['start_value'];
                                        }
                                        echo $start_value; 
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                        $lenth22 =  strlen(substr(strrchr($value['end_value'], "."), 1));
                                        if($lenth22==6){
                                            $end_value = $value['end_value'].'0';
                                        }else{

                                            $end_value = $value['end_value'];
                                        }
                                        echo $end_value; 
                                        ?>
                                        </td>

                                        <td><?php echo ucfirst($value['type']); ?></td>
                                        <td><?php echo ucfirst($value['coin']); ?></td>
                                        <td><?php echo ucfirst($value['start_date']); ?></td>
                                        <td><?php echo ucfirst($value['end_date']); ?></td>
                                        <td><?php echo $value['created_date']; ?></td>
                                        <td class="center">
                                        <div class="btn-group btn-group-xs ">
                                            <a href="<?php echo SURL.'admin/dashboard/edit-zone/'.$value['_id'];?>" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>
                                            <a href="<?php echo SURL.'admin/dashboard/delete-zone/'.$value['_id'];?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete?')"><i class="fa fa-times"></i></a>
                                        </div>
                                       </td>
                                    </tr>
                                <?php }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
        <div class="clearfix"></div>      
    </div> 
    
  </div>
</div>