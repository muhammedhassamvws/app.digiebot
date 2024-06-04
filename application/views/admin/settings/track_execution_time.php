
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Function Execution Time</h1>
  <div class="bg-white innerAll border-bottom">
    
    <span class="fa fa-info-circle" style="float: right;font-size: 20px;margin-top: -25px;color: #cb4040;" data-toggle="popover" data-placement="left" data-trigger="hover" data-container="body" data-original-title="Buy Order Listing" data-content="Here in Buy order listing page every order is filtered by their current status, If you want to see the specific order look that in related tab, Moreover you can filter the orders by date, type and coin"></span> </div>
  <div class="innerAll spacing-x2">
    <div class="widget widget-inverse">
      <div class="col-xs-12" style="padding: 25px 20px;">
        <div class="back">
          <div class="widget widget-inverse widget-scroll">
            <div class="widget-head" style="height:46px;">
            
            </div>
            <div class="widget-body padding-none">
              <div class="box-generic"> 
                <!-- Tabs Heading --> 
                
                <!-- // Tabs Heading END -->
                <div class="tab-content"> 
                  <!-- Tab content -->
                  <div class="tab-pane active" id="tab2-3">
                    <div class="widget-body padding-none">
                      <table class="table table-condensed">
                        <thead>
                          <tr>
                            <th class="text-center"><strong>S.No</strong></th>
                            <th class="text-center"><strong>Function Name</strong></th>
                            <th class="text-center"><strong>Created Date</strong></th>
                            
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
	$i=1;					  
if (count($timelogarr) > 0) {
	foreach ($timelogarr as $key => $value) {?>
                          <tr>
                            
                            
                            <td class="center"><?php echo $i; ?></td> 
                            <td class="center"><?php echo $value->function_name; ?></td>
                            <td class="center"><b><?php echo date('d, M Y h:i:s', strtotime($value->created_date_human_readible)) ?></b></td>
                           
                          
                          </tr>
                          <?php $i++;}
}else{?>

<div class="alert alert-danger fade in alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
    Opps No data found .
</div>
<?php }?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!-- // Tab content END --> 
          
                </div>
                <!-- // Tab content END --> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
</div>
</div>
