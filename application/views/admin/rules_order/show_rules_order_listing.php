
<style type="text/css">
.center_div {
	margin: 0 auto;
	width: 100% /* value of your choice which suits your alignment */
}
.menu {
	font-weight: bold;
	padding: 10px;
	font-size: 0.8em;
	width: 100%;
	background: white;
	position: relative;
	text-align: center;
	margin: 51px 0px;
}
.menu ul {
	margin: 0;
	padding: 0;
	list-style-type: none;
	list-style-image: none;
}
.menu li {
	display: block;
	padding: 15px 0 15px 0;
}
.menu li:hover {
	display: block;
	padding: 15px 0 15px 0;
}
.menu ul li a {
	text-decoration: none;
	margin: 0px;
	color: #fff;
}
.menu ul li a:hover {
	color: #fff;
	text-decoration: none;
}
.menu a {
	text-decoration: none;
	color: white;
}
.menu a:hover {
	text-decoration: none;
	color: white;
}
</style>


<div id="content">
  <div class="heading-buttons bg-white border-bottom innerAll">
    <h1 class="content-heading padding-none pull-left"><h3><?php echo $rule ?></h3></h1>
    <div class="clearfix"></div>
  </div>
  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
      <li><a href="/admin/rules-order/listing/">Rules Order Listing</a></li>
    </ul>
  </div>
  <div class="innerAll spacing-x2">
    <div class="container center_div">
      <div class="widget-body"> 
        
        <!-- Table -->
        
        
       <!-- <div class="pull-left"><h4> Order Buy : <?php  echo $record_of_rules_for_orders_type[0]->order_type;?></h4></div>
        
         <div class="pull-right"><h4>Order Sell : <?php  echo $record_of_rules_for_orders_type[1]->order_type;?></h4></div>
      -->
        <?php   if(count($rules_orders_arr)>0){ ?>
        <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center checkboxs">
          <thead>
            <tr>
              
              <th class="center">Coin Name</th>
              <th class="center">Order Type</th>
              <th class="center">Rule</th>
              <th class="center">Mode</th>
              <!--<th class="center">Options</th>-->
              
            </tr>
          </thead>
          <tbody>
          <?php foreach($rules_orders_arr as $rule){
			  
		   if($rule->order_type=='buy'){
			   $order_type  =  '<span class="label label-success">Buy</span>';   
		   }else if($rule['order_type']=='sell'){	
		       $order_type  =  '<span class="label label-danger">Sell</span>';   
		   }
			  
		 ?>
          
            <tr class="selectable" style="height: 50px;">
              
              <td class="center"><?php echo $rule->coin_symbol; ?></td>
              <td class="center"><?php echo $rule->order_type; ?></td>
              <td class="center"><?php echo $rule->rule ?></td>
              <td class="center"><?php echo $rule->mode ?></td>
              <td class="text-center">
         
          <!-- <a href="<?php echo SURL ?>admin/rules-order/show-order/<?php echo $rule->rule; ?>" class="btn btn-info btn-xs" title="Rules Order">Rules Order </a>-->
         
       </td>
            </tr>
            <?php }?>
           
          </tbody>
        </table>
        <div class="page_links text-center"><?php echo $page_links ?></div>
         <?php }else{?>
                <div class="alert alert-danger">
                    <strong>Opps!</strong> Sorry no record found against rules order .
                </div>
                
            <?php }?>
        <!-- // Table END --> 
      </div>
      <!-- END ROW -->
      
      
      
    </div>
  </div>
</div>
</div>
