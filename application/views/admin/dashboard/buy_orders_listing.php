<?php $session_post_data = $this->session->userdata('filter-data-buy'); ?>

<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

<style type="text/css">
	.tab-t
	{
		overflow: visible;
	}
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Buy Orders Listing</h1>

  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
        <li class="active"><a href="<?php echo SURL; ?>admin/dashboard/buy-orders-listing">Buy Order Listing</a></li>
    </ul>
  </div>
  
  <div class="innerAll spacing-x2">

        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="panel" style="visibility: visible;">
                    <div class="front">

                        <div class="widget text-center">
                            <div class="widget-body padding-none">
                                <div tabindex="5001" style="height: 100px; overflow: hidden; outline: none;">
                                    <div class="box-generic border-none text-center bg-inverse">
                                        <p class="margin-none"><b>BTC Balance</b></p>
                                        <p><strong class="text-large text-primary" style="color:#6ecb40 !important;" id="bitcoin">0</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel" style="visibility: visible;">
                    <div class="front">

                        <div class="widget text-center">
                            <div class="widget-body padding-none">
                                <div tabindex="5001" style="height: 100px; overflow: hidden; outline: none;">
                                    <div class="box-generic border-none text-center bg-inverse">
                                        <?php $global_symbol = $this->session->userdata('global_symbol'); ?>
                                        <p class="margin-none"><b><?php echo str_replace('BTC','',$global_symbol); ?> Balance</b></p>
                                        <p><strong class="text-large text-success" style="color:#6ecb40 !important;" id="balance"><?php echo 0;?></strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel" style="visibility: visible;">
                    <div class="front">

                        <div class="widget text-center">

                            <div class="widget-body padding-none">
                                <div tabindex="5001" style="height: 100px; overflow: hidden; outline: none;">
                                    <div class="box-generic border-none text-center bg-inverse">
                                        <p class="margin-none"><b>Total Sold Orders</b></p>
                                        <p><strong class="text-large text-primary" style="color:#6ecb40 !important;"><?php echo $total_sold_orders;?></strong></p>
                                    </div>
                                </div>
                            </div>
                          
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel" style="visibility: visible;">
                    <div class="front">
                        
                        <div class="widget text-center">
                            <div class="widget-body padding-none">
                                <div tabindex="5001" style="height: 100px; overflow: hidden; outline: none;">
                                    <div class="box-generic border-none text-center bg-inverse">
                                        <p class="margin-none"><b>Avg Profit</b></p>
                                        <?php if (num($avg_profit) > 0) {
                                            $class = 'text-primary';
                                            $style = "style = 'color:#6ecb40 !important;'";
                                        }else{
                                            $class = 'text-primary';
                                        }
                                        ?>
                                        <p><strong class="text-large <?php echo $class ?>" <?php echo $style; ?>><?php echo $avg_profit;?> %</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <form class="form-horizontal margin-none" method="post" action="<?php echo SURL?>admin/dashboard/buy-orders-listing" novalidate="novalidate">
        <div class="widget widget-inverse">     
            <div class="col-xs-12" style="padding: 10px 10px;">
                
               <div class="col-md-2"> 
                  <div class="form-group col-md-12">
                    <label class="control-label"></label>
                    <select name="filter_type" class="form-control">
                      <?php $filter_type = $session_post_data['filter_type']; ?>
                      <option value="">Search By Type</option>
                      <option value="market_order" <?php if($filter_type =='market_order'){?> selected <?php } ?>>Market Order</option>
                      <option value="limit_order" <?php if($filter_type =='limit_order'){?> selected <?php } ?>>Limit Order</option>
                    </select>
                  </div>
                </div>

                <div class="col-md-2"> 
                  <div class="form-group col-md-12">
                    <label class="control-label"></label>
                    <select name="filter_coin" class="form-control">
                      <option value="">Search By Coin</option>
                      <?php 
                       if(count($coins_arr)>0){
                       for($i=0; $i<count($coins_arr); $i++){

                           $filter_coin = $session_post_data['filter_coin'];
                           if($filter_coin ==  $coins_arr[$i]['symbol']){
                                $selected="selected"; 
                           }else{
                                $selected=""; 
                           }

                       ?> 
                      <option value="<?php echo $coins_arr[$i]['symbol'];?>" <?php echo $selected;?>><?php echo $coins_arr[$i]['symbol'];?></option>
                      <?php }
                      } ?>
                    </select>
                  </div>
                </div>


                <div class="col-md-2"> 
                  <div class="form-group col-md-12">
                    <label class="control-label"></label>
                     <input type='text' class="form-control datetime_picker" name="start_date" placeholder="Search By Start Date" value="<?php echo $session_post_data['start_date']; ?>" />
                  </div>
                </div>

                <div class="col-md-2"> 
                  <div class="form-group col-md-12">
                    <label class="control-label"></label>
                     <input type='text' class="form-control datetime_picker" name="end_date" placeholder="Search By End Date" value="<?php echo $session_post_data['end_date']; ?>" />
                  </div>
                </div>

                <script type="text/javascript">
                    $(function () {
                        $('.datetime_picker').datetimepicker();
                    });
                </script>

                <div class="col-md-2"> 
                    <button class="btn btn-success" type="submit" style="margin-top: 18px;"><i class="fa fa-check-circle"></i> Search </button>
                    <a href="<?php echo SURL?>admin/dashboard/reset_buy_filters" class="btn btn-primary btn_withIcon" style="margin-top: 18px;">
                    <span class="glyphicon glyphicon-refresh"></span> Reset</a>
                </div>
                   
            </div>    
            <div class="clearfix"></div>      
        </div> 
        </form> 

  	
        <div class="widget widget-inverse">     
            <div class="col-xs-12" style="padding: 25px 20px;">
                <div class="back">
                    <div class="widget widget-inverse widget-scroll">
                        <div class="widget-head" style="height:46px;">               
                        	<h4 class="heading" style=" padding-top: 3px;">Buy Orders</h4>
                            <button class="btn btn-danger pull-right" id="buy_all_btn" style="margin-top: 6px;">Buy All</button>
                        </div>
                        <div class="widget-body padding-none">
                             <div class="box-generic">
                <!-- Tabs Heading -->
                <div class="tabsbar">
                    <ul>
                        <li class="tab-t active"><a href="#tab2-3" data-toggle="tab">New <strong>(<span id="counter1"><?php echo count($new_arr); ?></span>)</strong></a></li>
                        <li class="tab-t"><a href="#tab3-3" data-toggle="tab">Filled <strong>(<span id="counter2"><?php echo count($filled_arr); ?></span>)</strong></a></li>
                        <li class="tab-t"><a href="#tab6-3" data-toggle="tab">Submitted <strong>(<span id="counter3"><?php echo count($submitted); ?></span>)</strong></a></li>
                        <li class="tab-t"><a href="#tab4-3" data-toggle="tab"><span>Canceled <strong>(<span id="counter4"><?php echo count($cancelled_arr); ?></span>)</strong></span></a></li>
                        <li class="tab-t"><a href="#tab5-3" data-toggle="tab"><span>Error<strong>(<span id="counter5"><?php echo count($error_arr); ?></span>)</strong></span></a></li>
                        <li class="tab-t"><a href="#tab7-3" data-toggle="tab">Open<strong>(<span id="counter6"><?php echo count($open_trades); ?></span>)</strong></a></li>
                        <li class="tab-t"><a href="#tab8-3" data-toggle="tab">Sold<strong>(<span id="counter7"><?php echo count($sold_trades); ?></span>)</strong></a></li>
                         <li class="tab-t"><a href="#tab1-3" data-toggle="tab">All<strong>(<span id="counter8"><?php echo count($orders_arr); ?></span>)</strong></a></li>

                    </ul>
                </div>
                <!-- // Tabs Heading END -->
                <div class="tab-content">
                    <!-- Tab content -->
                    <div class="tab-pane active" id="tab2-3">
                        <div class="widget-body padding-none">
                            <div id="response_market_trading2">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><strong>Coin</strong></th>
                                            <th><strong>Price</strong></th>
                                            <th><strong>Trail Price</strong></th>
                                            <th><strong>Quantity</strong></th>
                                            <th class="text-center"><strong>P/L</strong></th>
                                            <th class="text-center"><strong>Market(%)</strong></th>
                                            <th class="text-center"><strong>Status</strong></th>
                                            <th class="text-center"><strong>Profit(%)</strong></th>
                                            <th class="text-center"><strong>Actions</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(count($new_arr)>0){
                                        foreach ($new_arr as $key=>$value) {

                                            //Get Market Price
                                            $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                                            if($value['status'] !='new'){
                                                $market_value333 = num($value['market_value']);
                                            }else{
                                                $market_value333 = num($market_value);
                                            }


                                            if($value['status'] =='new'){
                                                $current_order_price = num($value['price']);
                                            }else{
                                                $current_order_price = num($value['market_value']);
                                            }

                                            $current_data = $market_value333 - $current_order_price;   
                                            $market_data = ($current_data * 100 / $market_value333);

                                            $market_data = number_format((float)$market_data, 2, '.', '');

                                            if($market_value333 > $current_order_price){
                                                $class = 'success';
                                            }else{
                                                $class = 'danger';  
                                            }
                                            ?>

                                        <tr>
                                            <td class="center">
                                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="<?php echo $value['_id'];?>"><i class="fa fa-eye"></i></button>
                                            </td>
                                            <td><?php echo $value['symbol']; ?></td>
                                            <td><?php echo num($value['price']); ?></td>
                                            <td>
                                            <?php
                                            if($value['trail_check']=='yes'){
                                                echo num($value['buy_trail_price']);
                                            }else{
                                                echo "-";
                                            }
                                            ?>
                                            </td>
                                            <td><?php echo $value['quantity']; ?></td>
                                            <td class="center"><b><?php echo num($market_value333); ?></b></td>
                                            <?php
                                            if($value['is_sell_order'] !='sold' && $value['is_sell_order'] !='yes'){ ?>

                                            <td class="center"><span class="text-<?php echo $class;?>"><b><?php echo $market_data; ?>%</b></span></td>

                                            <?php }else{ ?>

                                            <td class="center"><span class="text-default"><b>-</b></span></td>
                                            <?php } ?>

                                            <td class="center">

                                            <span class="label label-success"><?php echo ucfirst($value['status']); ?></span>
                                            <span class="custom_refresh" data-id="<?php echo $value['_id']; ?>" order_id="<?php echo $value['binance_order_id'];?>">
                                                <i class="fa fa-refresh" aria-hidden="true"></i>
                                            </span>  
                          
                                            </td>

                                            <td class="center">

                                            <?php
                                            if($value['market_sold_price'] !=""){

                                                $market_sold_price = $value['market_sold_price'];

                                                $current_data2222 = $market_sold_price - $current_order_price;  
                                                $profit_data = ($current_data2222 * 100 / $market_sold_price);

                                                $profit_data = number_format((float)$profit_data, 2, '.', '');

                                                if($market_sold_price > $current_order_price){
                                                        $class222 = 'success';
                                                }else{
                                                        $class222 = 'danger';   
                                                }?>

                                                <span class="text-<?php echo $class222;?>">
                                                                <b><?php echo $profit_data;?>%</b>
                                                              </span>
                                                <?php 
                                                }else{ ?>

                                                 <span class="text-default"><b>-</b></span>
                                                <?php } ?>

                                                </td>

                                                <td class="center">
                                                    <div class="btn-group btn-group-xs ">
                                                    <?php
                                                    if($value['status'] =='new'){ ?>
                                                    <a href="<?php echo SURL.'admin/dashboard/edit-buy-order/'.$value['_id']; ?>" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>
                                                    <?php } 
                                                    if($value['status'] !='FILLED'){ ?>
                                                    <a href="<?php echo SURL.'admin/dashboard/delete-buy-order/'.$value['_id'].'/'.$value['binance_order_id'];?>" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>
                                                    <?php }

                                                    if($value['status'] =='FILLED'){

                                                        if($value['is_sell_order'] =='yes'){ ?>
                                                           <button class="btn btn-info">Submited For Sell</button>
                                                        <?php }elseif($value['is_sell_order'] =='sold'){ ?>
                                                            <button class="btn btn-success">Sold</button>
                                                        <?php }else{ ?>
                                                            <a href="<?php echo SURL.'admin/dashboard/add-order/'.$value['_id'];?>" class="btn btn-warning" target="_blank">Sell Now</a>
                                                        <?php }
                                                        
                                                    } ?>

                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                <?php
                                                if($value['status'] =='new'){ ?>
                                                    <button class="btn btn-danger buy_now_btn" id="<?php echo $value['_id']; ?>" data-id="<?php echo $value['_id']; ?>" market_value="<?php echo num($market_value); ?>" quantity="<?php echo $value['quantity'];?>" symbol="<?php echo $value['symbol'];?>">Buy Now</button>
                                                <?php } ?>
                                                </td>
                                            </tr>
                           <?php }
                        } ?>   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- // Tab content END -->
                    <!-- Tab content -->
                    <div class="tab-pane" id="tab6-3">
                        <div class="widget-body padding-none">
                            <div id="response_market_trading6">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><strong>Coin</strong></th>
                                            <th><strong>Price</strong></th>
                                            <th><strong>Trail Price</strong></th>
                                            <th><strong>Quantity</strong></th>
                                            <th class="text-center"><strong>P/L</strong></th>
                                            <th class="text-center"><strong>Market(%)</strong></th>
                                            <th class="text-center"><strong>Status</strong></th>
                                            <th class="text-center"><strong>Profit(%)</strong></th>
                                            <th class="text-center"><strong>Actions</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(count($submitted)>0){
                                        foreach ($submitted as $key=>$value) {

                                            //Get Market Price
                                            $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                                            if($value['status'] !='new'){
                                                $market_value333 = num($value['market_value']);
                                            }else{
                                                $market_value333 = num($market_value);
                                            }


                                            if($value['status'] =='new'){
                                                $current_order_price = num($value['price']);
                                            }else{
                                                $current_order_price = num($value['market_value']);
                                            }

                                            $current_data = $market_value333 - $current_order_price;   
                                            $market_data = ($current_data * 100 / $market_value333);

                                            $market_data = number_format((float)$market_data, 2, '.', '');

                                            if($market_value333 > $current_order_price){
                                                $class = 'success';
                                            }else{
                                                $class = 'danger';  
                                            }
                                            ?>

                                        <tr>
                                            <td class="center">
                                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="<?php echo $value['_id'];?>"><i class="fa fa-eye"></i></button>
                                            </td>
                                            <td><?php echo $value['symbol']; ?></td>
                                            <td><?php echo num($value['price']); ?></td>
                                            <td>
                                            <?php
                                            if($value['trail_check']=='yes'){
                                                echo num($value['buy_trail_price']);
                                            }else{
                                                echo "-";
                                            }
                                            ?>
                                            </td>
                                            <td><?php echo $value['quantity']; ?></td>
                                            <td class="center"><b><?php echo num($market_value333); ?></b></td>
                                            <?php
                                            if($value['is_sell_order'] !='sold' && $value['is_sell_order'] !='yes'){ ?>

                                            <td class="center"><span class="text-<?php echo $class;?>"><b><?php echo $market_data; ?>%</b></span></td>

                                            <?php }else{ ?>

                                            <td class="center"><span class="text-default"><b>-</b></span></td>
                                            <?php } ?>

                                            <td class="center">

                                            <span class="label label-success"><?php echo ucfirst($value['status']); ?></span>
                                            <span class="custom_refresh" data-id="<?php echo $value['_id']; ?>" order_id="<?php echo $value['binance_order_id'];?>">
                                                <i class="fa fa-refresh" aria-hidden="true"></i>
                                            </span>  
                          
                                            </td>

                                            <td class="center">

                                            <?php
                                            if($value['market_sold_price'] !=""){

                                                $market_sold_price = $value['market_sold_price'];

                                                $current_data2222 = $market_sold_price - $current_order_price;  
                                                $profit_data = ($current_data2222 * 100 / $market_sold_price);

                                                $profit_data = number_format((float)$profit_data, 2, '.', '');

                                                if($market_sold_price > $current_order_price){
                                                        $class222 = 'success';
                                                }else{
                                                        $class222 = 'danger';   
                                                }?>

                                                <span class="text-<?php echo $class222;?>">
                                                                <b><?php echo $profit_data;?>%</b>
                                                              </span>
                                                <?php 
                                                }else{ ?>

                                                 <span class="text-default"><b>-</b></span>
                                                <?php } ?>

                                                </td>

                                                <td class="center">
                                                    <div class="btn-group btn-group-xs ">
                                                    <?php
                                                    if($value['status'] =='new'){ ?>
                                                    <a href="<?php echo SURL.'admin/dashboard/edit-buy-order/'.$value['_id']; ?>" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>
                                                    <?php } 
                                                    if($value['status'] !='FILLED'){ ?>
                                                    <a href="<?php echo SURL.'admin/dashboard/delete-buy-order/'.$value['_id'].'/'.$value['binance_order_id'];?>" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>
                                                    <?php }

                                                    if($value['status'] =='FILLED'){

                                                        if($value['is_sell_order'] =='yes'){ ?>
                                                           <button class="btn btn-info">Submited For Sell</button>
                                                        <?php }elseif($value['is_sell_order'] =='sold'){ ?>
                                                            <button class="btn btn-success">Sold</button>
                                                        <?php }else{ ?>
                                                            <a href="<?php echo SURL.'admin/dashboard/add-order/'.$value['_id'];?>" class="btn btn-warning" target="_blank">Sell Now</a>
                                                        <?php }
                                                        
                                                    } ?>

                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                <?php
                                                if($value['status'] =='new'){ ?>
                                                    <button class="btn btn-danger buy_now_btn" id="<?php echo $value['_id']; ?>" data-id="<?php echo $value['_id']; ?>" market_value="<?php echo num($market_value); ?>" quantity="<?php echo $value['quantity'];?>" symbol="<?php echo $value['symbol'];?>">Buy Now</button>
                                                <?php } ?>
                                                </td>
                                            </tr>
                           <?php }
                        } ?>   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- // Tab content END -->
                    <!-- Tab content -->
                    <div class="tab-pane" id="tab7-3">
                        <div class="widget-body padding-none">
                            <div id="response_market_trading7">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><strong>Coin</strong></th>
                                            <th><strong>Price</strong></th>
                                            <th><strong>Trail Price</strong></th>
                                            <th><strong>Quantity</strong></th>
                                            <th class="text-center"><strong>P/L</strong></th>
                                            <th class="text-center"><strong>Market(%)</strong></th>
                                            <th class="text-center"><strong>Status</strong></th>
                                            <th class="text-center"><strong>Profit(%)</strong></th>
                                            <th class="text-center"><strong>Actions</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(count($open_trades)>0){
                                        foreach ($open_trades as $key=>$value) {

                                            //Get Market Price
                                            $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                                            if($value['status'] !='new'){
                                                $market_value333 = num($value['market_value']);
                                            }else{
                                                $market_value333 = num($market_value);
                                            }


                                            if($value['status'] =='new'){
                                                $current_order_price = num($value['price']);
                                            }else{
                                                $current_order_price = num($value['market_value']);
                                            }

                                            $current_data = $market_value333 - $current_order_price;   
                                            $market_data = ($current_data * 100 / $market_value333);

                                            $market_data = number_format((float)$market_data, 2, '.', '');

                                            if($market_value333 > $current_order_price){
                                                $class = 'success';
                                            }else{
                                                $class = 'danger';  
                                            }
                                            ?>

                                        <tr>
                                            <td class="center">
                                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="<?php echo $value['_id'];?>"><i class="fa fa-eye"></i></button>
                                            </td>
                                            <td><?php echo $value['symbol']; ?></td>
                                            <td><?php echo num($value['price']); ?></td>
                                            <td>
                                            <?php
                                            if($value['trail_check']=='yes'){
                                                echo num($value['buy_trail_price']);
                                            }else{
                                                echo "-";
                                            }
                                            ?>
                                            </td>
                                            <td><?php echo $value['quantity']; ?></td>
                                            <td class="center"><b><?php echo num($market_value333); ?></b></td>
                                            <?php
                                            if($value['is_sell_order'] !='sold' && $value['is_sell_order'] !='yes'){ ?>

                                            <td class="center"><span class="text-<?php echo $class;?>"><b><?php echo $market_data; ?>%</b></span></td>

                                            <?php }else{ ?>

                                            <td class="center"><span class="text-default"><b>-</b></span></td>
                                            <?php } ?>

                                            <td class="center">

                                            <span class="label label-success"><?php echo ucfirst($value['status']); ?></span>
                                            <span class="custom_refresh" data-id="<?php echo $value['_id']; ?>" order_id="<?php echo $value['binance_order_id'];?>">
                                                <i class="fa fa-refresh" aria-hidden="true"></i>
                                            </span>  
                          
                                            </td>

                                            <td class="center">

                                            <?php
                                            if($value['market_sold_price'] !=""){

                                                $market_sold_price = $value['market_sold_price'];

                                                $current_data2222 = $market_sold_price - $current_order_price;  
                                                $profit_data = ($current_data2222 * 100 / $market_sold_price);

                                                $profit_data = number_format((float)$profit_data, 2, '.', '');

                                                if($market_sold_price > $current_order_price){
                                                        $class222 = 'success';
                                                }else{
                                                        $class222 = 'danger';   
                                                }?>

                                                <span class="text-<?php echo $class222;?>">
                                                                <b><?php echo $profit_data;?>%</b>
                                                              </span>
                                                <?php 
                                                }else{ ?>

                                                 <span class="text-default"><b>-</b></span>
                                                <?php } ?>

                                                </td>

                                                <td class="center">
                                                    <div class="btn-group btn-group-xs ">
                                                    <?php
                                                    if($value['status'] =='new'){ ?>
                                                    <a href="<?php echo SURL.'admin/dashboard/edit-buy-order/'.$value['_id']; ?>" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>
                                                    <?php } 
                                                    if($value['status'] !='FILLED'){ ?>
                                                    <a href="<?php echo SURL.'admin/dashboard/delete-buy-order/'.$value['_id'].'/'.$value['binance_order_id'];?>" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>
                                                    <?php }

                                                    if($value['status'] =='FILLED'){

                                                        if($value['is_sell_order'] =='yes'){ ?>
                                                           <button class="btn btn-info">Submited For Sell</button>
                                                        <?php }elseif($value['is_sell_order'] =='sold'){ ?>
                                                            <button class="btn btn-success">Sold</button>
                                                        <?php }else{ ?>
                                                            <a href="<?php echo SURL.'admin/dashboard/add-order/'.$value['_id'];?>" class="btn btn-warning" target="_blank">Sell Now</a>
                                                        <?php }
                                                        
                                                    } ?>

                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                <?php
                                                if($value['status'] =='new'){ ?>
                                                    <button class="btn btn-danger buy_now_btn" id="<?php echo $value['_id']; ?>" data-id="<?php echo $value['_id']; ?>" market_value="<?php echo num($market_value); ?>" quantity="<?php echo $value['quantity'];?>" symbol="<?php echo $value['symbol'];?>">Buy Now</button>
                                                <?php } ?>
                                                </td>
                                            </tr>
                           <?php }
                        } ?>   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- // Tab content END -->
                    <!-- Tab content -->
                    <div class="tab-pane" id="tab3-3">
                        <div class="widget-body padding-none">
                            <div id="response_market_trading3">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><strong>Coin</strong></th>
                                            <th><strong>Price</strong></th>
                                            <th><strong>Trail Price</strong></th>
                                            <th><strong>Quantity</strong></th>
                                            <th class="text-center"><strong>P/L</strong></th>
                                            <th class="text-center"><strong>Market(%)</strong></th>
                                            <th class="text-center"><strong>Status</strong></th>
                                            <th class="text-center"><strong>Profit(%)</strong></th>
                                            <th class="text-center"><strong>Actions</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(count($filled_arr)>0){
                                        foreach ($filled_arr as $key=>$value) {

                                            //Get Market Price
                                            $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                                            if($value['status'] !='new'){
                                                $market_value333 = num($value['market_value']);
                                            }else{
                                                $market_value333 = num($market_value);
                                            }


                                            if($value['status'] =='new'){
                                                $current_order_price = num($value['price']);
                                            }else{
                                                $current_order_price = num($value['market_value']);
                                            }

                                            $current_data = $market_value333 - $current_order_price;   
                                            $market_data = ($current_data * 100 / $market_value333);

                                            $market_data = number_format((float)$market_data, 2, '.', '');

                                            if($market_value333 > $current_order_price){
                                                $class = 'success';
                                            }else{
                                                $class = 'danger';  
                                            }
                                            ?>

                                        <tr>
                                            <td class="center">
                                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="<?php echo $value['_id'];?>"><i class="fa fa-eye"></i></button>
                                            </td>
                                            <td><?php echo $value['symbol']; ?></td>
                                            <td><?php echo num($value['price']); ?></td>
                                            <td>
                                            <?php
                                            if($value['trail_check']=='yes'){
                                                echo num($value['buy_trail_price']);
                                            }else{
                                                echo "-";
                                            }
                                            ?>
                                            </td>
                                            <td><?php echo $value['quantity']; ?></td>
                                            <td class="center"><b><?php echo num($market_value333); ?></b></td>
                                            <?php
                                            if($value['is_sell_order'] !='sold' && $value['is_sell_order'] !='yes'){ ?>

                                            <td class="center"><span class="text-<?php echo $class;?>"><b><?php echo $market_data; ?>%</b></span></td>

                                            <?php }else{ ?>

                                            <td class="center"><span class="text-default"><b>-</b></span></td>
                                            <?php } ?>

                                            <td class="center">

                                            <span class="label label-success"><?php echo ucfirst($value['status']); ?></span>
                                            <span class="custom_refresh" data-id="<?php echo $value['_id']; ?>" order_id="<?php echo $value['binance_order_id'];?>">
                                                <i class="fa fa-refresh" aria-hidden="true"></i>
                                            </span>  
                          
                                            </td>

                                            <td class="center">

                                            <?php
                                            if($value['market_sold_price'] !=""){

                                                $market_sold_price = $value['market_sold_price'];

                                                $current_data2222 = $market_sold_price - $current_order_price;  
                                                $profit_data = ($current_data2222 * 100 / $market_sold_price);

                                                $profit_data = number_format((float)$profit_data, 2, '.', '');

                                                if($market_sold_price > $current_order_price){
                                                        $class222 = 'success';
                                                }else{
                                                        $class222 = 'danger';   
                                                }?>

                                                <span class="text-<?php echo $class222;?>">
                                                                <b><?php echo $profit_data;?>%</b>
                                                              </span>
                                                <?php 
                                                }else{ ?>

                                                 <span class="text-default"><b>-</b></span>
                                                <?php } ?>

                                                </td>

                                                <td class="center">
                                                    <div class="btn-group btn-group-xs ">
                                                    <?php
                                                    if($value['status'] =='new'){ ?>
                                                    <a href="<?php echo SURL.'admin/dashboard/edit-buy-order/'.$value['_id']; ?>" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>
                                                    <?php } 
                                                    if($value['status'] !='FILLED'){ ?>
                                                    <a href="<?php echo SURL.'admin/dashboard/delete-buy-order/'.$value['_id'].'/'.$value['binance_order_id'];?>" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>
                                                    <?php }

                                                    if($value['status'] =='FILLED'){

                                                        if($value['is_sell_order'] =='yes'){ ?>
                                                           <button class="btn btn-info">Submited For Sell</button>
                                                        <?php }elseif($value['is_sell_order'] =='sold'){ ?>
                                                            <button class="btn btn-success">Sold</button>
                                                        <?php }else{ ?>
                                                            <a href="<?php echo SURL.'admin/dashboard/add-order/'.$value['_id'];?>" class="btn btn-warning" target="_blank">Sell Now</a>
                                                        <?php }
                                                        
                                                    } ?>

                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                <?php
                                                if($value['status'] =='new'){ ?>
                                                    <button class="btn btn-danger buy_now_btn" id="<?php echo $value['_id']; ?>" data-id="<?php echo $value['_id']; ?>" market_value="<?php echo num($market_value); ?>" quantity="<?php echo $value['quantity'];?>" symbol="<?php echo $value['symbol'];?>">Buy Now</button>
                                                <?php } ?>
                                                </td>
                                            </tr>
                           <?php }
                        } ?>   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- // Tab content END -->
                    <!-- Tab content -->
                    <div class="tab-pane" id="tab4-3">
                        <div class="widget-body padding-none">
                            <div id="response_market_trading4">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><strong>Coin</strong></th>
                                            <th><strong>Price</strong></th>
                                            <th><strong>Trail Price</strong></th>
                                            <th><strong>Quantity</strong></th>
                                            <th class="text-center"><strong>P/L</strong></th>
                                            <th class="text-center"><strong>Market(%)</strong></th>
                                            <th class="text-center"><strong>Status</strong></th>
                                            <th class="text-center"><strong>Profit(%)</strong></th>
                                            <th class="text-center"><strong>Actions</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(count($cancelled_arr)>0){
                                        foreach ($cancelled_arr as $key => $value) {

                                            //Get Market Price
                                            $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                                            if($value['status'] !='new'){
                                                $market_value333 = num($value['market_value']);
                                            }else{
                                                $market_value333 = num($market_value);
                                            }


                                            if($value['status'] =='new'){
                                                $current_order_price = num($value['price']);
                                            }else{
                                                $current_order_price = num($value['market_value']);
                                            }

                                            $current_data = $market_value333 - $current_order_price;   
                                            $market_data = ($current_data * 100 / $market_value333);

                                            $market_data = number_format((float)$market_data, 2, '.', '');

                                            if($market_value333 > $current_order_price){
                                                $class = 'success';
                                            }else{
                                                $class = 'danger';  
                                            }
                                            ?>

                                        <tr>
                                            <td class="center">
                                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="<?php echo $value['_id'];?>"><i class="fa fa-eye"></i></button>
                                            </td>
                                            <td><?php echo $value['symbol']; ?></td>
                                            <td><?php echo num($value['price']); ?></td>
                                            <td>
                                            <?php
                                            if($value['trail_check']=='yes'){
                                                echo num($value['buy_trail_price']);
                                            }else{
                                                echo "-";
                                            }
                                            ?>
                                            </td>
                                            <td><?php echo $value['quantity']; ?></td>
                                            <td class="center"><b><?php echo num($market_value333); ?></b></td>
                                            <?php
                                            if($value['is_sell_order'] !='sold' && $value['is_sell_order'] !='yes'){ ?>

                                            <td class="center"><span class="text-<?php echo $class;?>"><b><?php echo $market_data; ?>%</b></span></td>

                                            <?php }else{ ?>

                                            <td class="center"><span class="text-default"><b>-</b></span></td>
                                            <?php } ?>

                                            <td class="center">

                                            <span class="label label-success"><?php echo ucfirst($value['status']); ?></span>
                                            <span class="custom_refresh" data-id="<?php echo $value['_id']; ?>" order_id="<?php echo $value['binance_order_id'];?>">
                                                <i class="fa fa-refresh" aria-hidden="true"></i>
                                            </span>  
                          
                                            </td>

                                            <td class="center">

                                            <?php
                                            if($value['market_sold_price'] !=""){

                                                $market_sold_price = $value['market_sold_price'];

                                                $current_data2222 = $market_sold_price - $current_order_price;  
                                                $profit_data = ($current_data2222 * 100 / $market_sold_price);

                                                $profit_data = number_format((float)$profit_data, 2, '.', '');

                                                if($market_sold_price > $current_order_price){
                                                        $class222 = 'success';
                                                }else{
                                                        $class222 = 'danger';   
                                                }?>

                                                <span class="text-<?php echo $class222;?>">
                                                                <b><?php echo $profit_data;?>%</b>
                                                              </span>
                                                <?php 
                                                }else{ ?>

                                                 <span class="text-default"><b>-</b></span>
                                                <?php } ?>

                                                </td>

                                                <td class="center">
                                                    <div class="btn-group btn-group-xs ">
                                                    <?php
                                                    if($value['status'] =='new'){ ?>
                                                    <a href="<?php echo SURL.'admin/dashboard/edit-buy-order/'.$value['_id']; ?>" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>
                                                    <?php } 
                                                    if($value['status'] !='FILLED'){ ?>
                                                    <a href="<?php echo SURL.'admin/dashboard/delete-buy-order/'.$value['_id'].'/'.$value['binance_order_id'];?>" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>
                                                    <?php }

                                                    if($value['status'] =='FILLED'){

                                                        if($value['is_sell_order'] =='yes'){ ?>
                                                           <button class="btn btn-info">Submited For Sell</button>
                                                        <?php }elseif($value['is_sell_order'] =='sold'){ ?>
                                                            <button class="btn btn-success">Sold</button>
                                                        <?php }else{ ?>
                                                            <a href="<?php echo SURL.'admin/dashboard/add-order/'.$value['_id'];?>" class="btn btn-warning" target="_blank">Sell Now</a>
                                                        <?php }
                                                        
                                                    } ?>

                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                <?php
                                                if($value['status'] =='new'){ ?>
                                                    <button class="btn btn-danger buy_now_btn" id="<?php echo $value['_id']; ?>" data-id="<?php echo $value['_id']; ?>" market_value="<?php echo num($market_value); ?>" quantity="<?php echo $value['quantity'];?>" symbol="<?php echo $value['symbol'];?>">Buy Now</button>
                                                <?php } ?>
                                                </td>
                                            </tr>
                           <?php }
                        } ?>   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab5-3">
                        <div class="widget-body padding-none">
                            <div id="response_market_trading5">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><strong>Coin</strong></th>
                                            <th><strong>Price</strong></th>
                                            <th><strong>Trail Price</strong></th>
                                            <th><strong>Quantity</strong></th>
                                            <th class="text-center"><strong>P/L</strong></th>
                                            <th class="text-center"><strong>Market(%)</strong></th>
                                            <th class="text-center"><strong>Status</strong></th>
                                            <th class="text-center"><strong>Profit(%)</strong></th>
                                            <th class="text-center"><strong>Actions</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(count($error_arr)>0){
                                        foreach ($error_arr as $key=>$value) {

                                            //Get Market Price
                                            $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                                            if($value['status'] !='new'){
                                                $market_value333 = num($value['market_value']);
                                            }else{
                                                $market_value333 = num($market_value);
                                            }


                                            if($value['status'] =='new'){
                                                $current_order_price = num($value['price']);
                                            }else{
                                                $current_order_price = num($value['market_value']);
                                            }

                                            $current_data = $market_value333 - $current_order_price;   
                                            $market_data = ($current_data * 100 / $market_value333);

                                            $market_data = number_format((float)$market_data, 2, '.', '');

                                            if($market_value333 > $current_order_price){
                                                $class = 'success';
                                            }else{
                                                $class = 'danger';  
                                            }
                                            ?>

                                        <tr>
                                            <td class="center">
                                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="<?php echo $value['_id'];?>"><i class="fa fa-eye"></i></button>
                                            </td>
                                            <td><?php echo $value['symbol']; ?></td>
                                            <td><?php echo num($value['price']); ?></td>
                                            <td>
                                            <?php
                                            if($value['trail_check']=='yes'){
                                                echo num($value['buy_trail_price']);
                                            }else{
                                                echo "-";
                                            }
                                            ?>
                                            </td>
                                            <td><?php echo $value['quantity']; ?></td>
                                            <td class="center"><b><?php echo num($market_value333); ?></b></td>
                                            <?php
                                            if($value['is_sell_order'] !='sold' && $value['is_sell_order'] !='yes'){ ?>

                                            <td class="center"><span class="text-<?php echo $class;?>"><b><?php echo $market_data; ?>%</b></span></td>

                                            <?php }else{ ?>

                                            <td class="center"><span class="text-default"><b>-</b></span></td>
                                            <?php } ?>

                                            <td class="center">

                                            <span class="label label-success"><?php echo ucfirst($value['status']); ?></span>
                                            <span class="custom_refresh" data-id="<?php echo $value['_id']; ?>" order_id="<?php echo $value['binance_order_id'];?>">
                                                <i class="fa fa-refresh" aria-hidden="true"></i>
                                            </span>  
                          
                                            </td>

                                            <td class="center">

                                            <?php
                                            if($value['market_sold_price'] !=""){

                                                $market_sold_price = $value['market_sold_price'];

                                                $current_data2222 = $market_sold_price - $current_order_price;  
                                                $profit_data = ($current_data2222 * 100 / $market_sold_price);

                                                $profit_data = number_format((float)$profit_data, 2, '.', '');

                                                if($market_sold_price > $current_order_price){
                                                        $class222 = 'success';
                                                }else{
                                                        $class222 = 'danger';   
                                                }?>

                                                <span class="text-<?php echo $class222;?>">
                                                                <b><?php echo $profit_data;?>%</b>
                                                              </span>
                                                <?php 
                                                }else{ ?>

                                                 <span class="text-default"><b>-</b></span>
                                                <?php } ?>

                                                </td>

                                                <td class="center">
                                                    <div class="btn-group btn-group-xs ">
                                                    <?php
                                                    if($value['status'] =='new'){ ?>
                                                    <a href="<?php echo SURL.'admin/dashboard/edit-buy-order/'.$value['_id']; ?>" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>
                                                    <?php } 
                                                    if($value['status'] !='FILLED'){ ?>
                                                    <a href="<?php echo SURL.'admin/dashboard/delete-buy-order/'.$value['_id'].'/'.$value['binance_order_id'];?>" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>
                                                    <?php }

                                                    if($value['status'] =='FILLED'){

                                                        if($value['is_sell_order'] =='yes'){ ?>
                                                           <button class="btn btn-info">Submited For Sell</button>
                                                        <?php }elseif($value['is_sell_order'] =='sold'){ ?>
                                                            <button class="btn btn-success">Sold</button>
                                                        <?php }else{ ?>
                                                            <a href="<?php echo SURL.'admin/dashboard/add-order/'.$value['_id'];?>" class="btn btn-warning" target="_blank">Sell Now</a>
                                                        <?php }
                                                        
                                                    } ?>

                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                <?php
                                                if($value['status'] =='new'){ ?>
                                                    <button class="btn btn-danger buy_now_btn" id="<?php echo $value['_id']; ?>" data-id="<?php echo $value['_id']; ?>" market_value="<?php echo num($market_value); ?>" quantity="<?php echo $value['quantity'];?>" symbol="<?php echo $value['symbol'];?>">Buy Now</button>
                                                <?php } ?>
                                                </td>
                                            </tr>
                           <?php }
                        } ?>   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab8-3">
                        <div class="widget-body padding-none">
                            <div id="response_market_trading8">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><strong>Coin</strong></th>
                                            <th><strong>Price</strong></th>
                                            <th><strong>Trail Price</strong></th>
                                            <th><strong>Quantity</strong></th>
                                            <th class="text-center"><strong>P/L</strong></th>
                                            <th class="text-center"><strong>Market(%)</strong></th>
                                            <th class="text-center"><strong>Status</strong></th>
                                            <th class="text-center"><strong>Profit(%)</strong></th>
                                            <th class="text-center"><strong>Actions</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(count($sold_trades)>0){
                                        foreach ($sold_trades as $key=>$value) {

                                            //Get Market Price
                                            $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                                            if($value['status'] !='new'){
                                                $market_value333 = num($value['market_value']);
                                            }else{
                                                $market_value333 = num($market_value);
                                            }


                                            if($value['status'] =='new'){
                                                $current_order_price = num($value['price']);
                                            }else{
                                                $current_order_price = num($value['market_value']);
                                            }

                                            $current_data = $market_value333 - $current_order_price;   
                                            $market_data = ($current_data * 100 / $market_value333);

                                            $market_data = number_format((float)$market_data, 2, '.', '');

                                            if($market_value333 > $current_order_price){
                                                $class = 'success';
                                            }else{
                                                $class = 'danger';  
                                            }
                                            ?>

                                        <tr>
                                            <td class="center">
                                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="<?php echo $value['_id'];?>"><i class="fa fa-eye"></i></button>
                                            </td>
                                            <td><?php echo $value['symbol']; ?></td>
                                            <td><?php echo num($value['price']); ?></td>
                                            <td>
                                            <?php
                                            if($value['trail_check']=='yes'){
                                                echo num($value['buy_trail_price']);
                                            }else{
                                                echo "-";
                                            }
                                            ?>
                                            </td>
                                            <td><?php echo $value['quantity']; ?></td>
                                            <td class="center"><b><?php echo num($market_value333); ?></b></td>
                                            <?php
                                            if($value['is_sell_order'] !='sold' && $value['is_sell_order'] !='yes'){ ?>

                                            <td class="center"><span class="text-<?php echo $class;?>"><b><?php echo $market_data; ?>%</b></span></td>

                                            <?php }else{ ?>

                                            <td class="center"><span class="text-default"><b>-</b></span></td>
                                            <?php } ?>

                                            <td class="center">

                                            <span class="label label-success"><?php echo ucfirst($value['status']); ?></span>
                                            <span class="custom_refresh" data-id="<?php echo $value['_id']; ?>" order_id="<?php echo $value['binance_order_id'];?>">
                                                <i class="fa fa-refresh" aria-hidden="true"></i>
                                            </span>  
                          
                                            </td>

                                            <td class="center">

                                            <?php
                                            if($value['market_sold_price'] !=""){

                                                $market_sold_price = $value['market_sold_price'];

                                                $current_data2222 = $market_sold_price - $current_order_price;  
                                                $profit_data = ($current_data2222 * 100 / $market_sold_price);

                                                $profit_data = number_format((float)$profit_data, 2, '.', '');

                                                if($market_sold_price > $current_order_price){
                                                        $class222 = 'success';
                                                }else{
                                                        $class222 = 'danger';   
                                                }?>

                                                <span class="text-<?php echo $class222;?>">
                                                                <b><?php echo $profit_data;?>%</b>
                                                              </span>
                                                <?php 
                                                }else{ ?>

                                                 <span class="text-default"><b>-</b></span>
                                                <?php } ?>

                                                </td>

                                                <td class="center">
                                                    <div class="btn-group btn-group-xs ">
                                                    <?php
                                                    if($value['status'] =='new'){ ?>
                                                    <a href="<?php echo SURL.'admin/dashboard/edit-buy-order/'.$value['_id']; ?>" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>
                                                    <?php } 
                                                    if($value['status'] !='FILLED'){ ?>
                                                    <a href="<?php echo SURL.'admin/dashboard/delete-buy-order/'.$value['_id'].'/'.$value['binance_order_id'];?>" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>
                                                    <?php }

                                                    if($value['status'] =='FILLED'){

                                                        if($value['is_sell_order'] =='yes'){ ?>
                                                           <button class="btn btn-info">Submited For Sell</button>
                                                        <?php }elseif($value['is_sell_order'] =='sold'){ ?>
                                                            <button class="btn btn-success">Sold</button>
                                                        <?php }else{ ?>
                                                            <a href="<?php echo SURL.'admin/dashboard/add-order/'.$value['_id'];?>" class="btn btn-warning" target="_blank">Sell Now</a>
                                                        <?php }
                                                        
                                                    } ?>

                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                <?php
                                                if($value['status'] =='new'){ ?>
                                                    <button class="btn btn-danger buy_now_btn" id="<?php echo $value['_id']; ?>" data-id="<?php echo $value['_id']; ?>" market_value="<?php echo num($market_value); ?>" quantity="<?php echo $value['quantity'];?>" symbol="<?php echo $value['symbol'];?>">Buy Now</button>
                                                <?php } ?>
                                                </td>
                                            </tr>
                           <?php }
                        } ?>   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- // Tab content END -->
                    <!-- Tab content -->
                    <div class="tab-pane" id="tab1-3">
                         <div class="widget-body padding-none">
                            <div id="response_market_trading1">
                                 <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><strong>Coin</strong></th>
                                            <th><strong>Price</strong></th>
                                            <th><strong>Trail Price</strong></th>
                                            <th><strong>Quantity</strong></th>
                                            <th class="text-center"><strong>P/L</strong></th>
                                            <th class="text-center"><strong>Market(%)</strong></th>
                                            <th class="text-center"><strong>Status</strong></th>
                                            <th class="text-center"><strong>Profit(%)</strong></th>
                                            <th class="text-center"><strong>Actions</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(count($orders_arr)>0){
                                        foreach ($orders_arr as $key=>$value) {

                                            //Get Market Price
                                            $market_value = $this->mod_dashboard->get_market_value($value['symbol']);

                                            if($value['status'] !='new'){
                                                $market_value333 = num($value['market_value']);
                                            }else{
                                                $market_value333 = num($market_value);
                                            }


                                            if($value['status'] =='new'){
                                                $current_order_price = num($value['price']);
                                            }else{
                                                $current_order_price = num($value['market_value']);
                                            }

                                            $current_data = $market_value333 - $current_order_price;   
                                            $market_data = ($current_data * 100 / $market_value333);

                                            $market_data = number_format((float)$market_data, 2, '.', '');

                                            if($market_value333 > $current_order_price){
                                                $class = 'success';
                                            }else{
                                                $class = 'danger';  
                                            }
                                            ?>

                                        <tr>
                                            <td class="center">
                                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="<?php echo $value['_id'];?>"><i class="fa fa-eye"></i></button>
                                            </td>
                                            <td><?php echo $value['symbol']; ?></td>
                                            <td><?php echo num($value['price']); ?></td>
                                            <td>
                                            <?php
                                            if($value['trail_check']=='yes'){
                                                echo num($value['buy_trail_price']);
                                            }else{
                                                echo "-";
                                            }
                                            ?>
                                            </td>
                                            <td><?php echo $value['quantity']; ?></td>
                                            <td class="center"><b><?php echo num($market_value333); ?></b></td>
                                            <?php
                                            if($value['is_sell_order'] !='sold' && $value['is_sell_order'] !='yes'){ ?>

                                            <td class="center"><span class="text-<?php echo $class;?>"><b><?php echo $market_data; ?>%</b></span></td>

                                            <?php }else{ ?>

                                            <td class="center"><span class="text-default"><b>-</b></span></td>
                                            <?php } ?>

                                            <td class="center">

                                            <span class="label label-success"><?php echo ucfirst($value['status']); ?></span>
                                            <span class="custom_refresh" data-id="<?php echo $value['_id']; ?>" order_id="<?php echo $value['binance_order_id'];?>">
                                                <i class="fa fa-refresh" aria-hidden="true"></i>
                                            </span>  
                          
                                            </td>

                                            <td class="center">

                                            <?php
                                            if($value['market_sold_price'] !=""){

                                                $market_sold_price = $value['market_sold_price'];

                                                $current_data2222 = $market_sold_price - $current_order_price;  
                                                $profit_data = ($current_data2222 * 100 / $market_sold_price);

                                                $profit_data = number_format((float)$profit_data, 2, '.', '');

                                                if($market_sold_price > $current_order_price){
                                                        $class222 = 'success';
                                                }else{
                                                        $class222 = 'danger';   
                                                }?>

                                                <span class="text-<?php echo $class222;?>">
                                                                <b><?php echo $profit_data;?>%</b>
                                                              </span>
                                                <?php 
                                                }else{ ?>

                                                 <span class="text-default"><b>-</b></span>
                                                <?php } ?>

                                                </td>

                                                <td class="center">
                                                    <div class="btn-group btn-group-xs ">
                                                    <?php
                                                    if($value['status'] =='new'){ ?>
                                                    <a href="<?php echo SURL.'admin/dashboard/edit-buy-order/'.$value['_id']; ?>" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>
                                                    <?php } 
                                                    if($value['status'] !='FILLED'){ ?>
                                                    <a href="<?php echo SURL.'admin/dashboard/delete-buy-order/'.$value['_id'].'/'.$value['binance_order_id'];?>" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete?\')"><i class="fa fa-times"></i></a>
                                                    <?php }

                                                    if($value['status'] =='FILLED'){

                                                        if($value['is_sell_order'] =='yes'){ ?>
                                                           <button class="btn btn-info">Submited For Sell</button>
                                                        <?php }elseif($value['is_sell_order'] =='sold'){ ?>
                                                            <button class="btn btn-success">Sold</button>
                                                        <?php }else{ ?>
                                                            <a href="<?php echo SURL.'admin/dashboard/add-order/'.$value['_id'];?>" class="btn btn-warning" target="_blank">Sell Now</a>
                                                        <?php }
                                                        
                                                    } ?>

                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                <?php
                                                if($value['status'] =='new'){ ?>
                                                    <button class="btn btn-danger buy_now_btn" id="<?php echo $value['_id']; ?>" data-id="<?php echo $value['_id']; ?>" market_value="<?php echo num($market_value); ?>" quantity="<?php echo $value['quantity'];?>" symbol="<?php echo $value['symbol'];?>">Buy Now</button>
                                                <?php } ?>
                                                </td>
                                            </tr>
                           <?php }
                        } ?>   
                                    </tbody>
                                </table>
                            </div>
                        </div>
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


<!-- Start Model -->
<div class="modal fade in" id="modal-order_details" aria-hidden="false">
    
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal heading -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Order Details</h3>
            </div>
            <!-- // Modal heading END -->
            
            <!-- Modal body -->
            <div class="modal-body">
                <div class="innerAll">
                    <div class="innerLR" id="response_order_details">
                    </div>
                </div>
            </div>
            <!-- // Modal body END -->
    
        </div>
    </div>
    
</div>
<!-- End Model -->


<script type="text/javascript">
$(document).ready(function() {
     autoload_bitcoin_price();
     autoload_coin_balance();
     autoload_market_buy_data();
});
  function autoload_bitcoin_price()
  {
      $.ajax({
          type:'POST',
          url:'<?php echo SURL?>admin/bitcoin_balance',
          data: "",
          success:function(response){
           
              $('#bitcoin').html(response);
           
              setTimeout(function() {
                    autoload_bitcoin_price();
              }, 3000);
           
          }
        });
  }

  function autoload_coin_balance()
  {
      $.ajax({
          type:'POST',
          url:'<?php echo SURL?>admin/dashboard/get_coin_balance',
          data: "",
          success:function(response){
           
              $('#balance').html(response);
           
              setTimeout(function() {
                    autoload_bitcoin_price();
              }, 3000);
           
          }
        });
  }
  

  function autoload_market_buy_data(){
    
    $.ajax({
    type:'POST',
    url:'<?php echo SURL?>admin/dashboard/autoload_market_buy_data2',
    data: "",
    success:function(response){

        var resp = response.split('@@@@@@');
        
        $('#response_market_trading1').html(resp[0]);
        $('#response_market_trading2').html(resp[1]);
        $('#response_market_trading3').html(resp[2]);
        $('#response_market_trading4').html(resp[3]);
        $('#response_market_trading5').html(resp[4]);
        $('#response_market_trading6').html(resp[5]);
        $('#response_market_trading7').html(resp[6]);
        $('#response_market_trading8').html(resp[7]);

        $('#counter1').html(resp[8]);
        $('#counter2').html(resp[9]);
        $('#counter3').html(resp[10]);
        $('#counter4').html(resp[11]);
        $('#counter5').html(resp[12]);
        $('#counter6').html(resp[13]);
        $('#counter7').html(resp[14]);
        $('#counter8').html(resp[15]);
     	
        setTimeout(function() {
              autoload_market_buy_data();
        }, 3000);
     
    }
  });

  }//end autoload_market_buy_data() 

  


  $("body").on("click",".view_order_details",function(e){

        var order_id = $(this).attr("data-id");

         $.ajax({
            'url': '<?php echo SURL ?>admin/dashboard/get_buy_order_details_ajax',
            'type': 'POST', 
            'data': {order_id:order_id},
            'success': function (response) { 

                $('#response_order_details').html(response);
                $("#modal-order_details").modal('show');
            }
        });
        
  }); 


    $("body").on("click",".sell_now_btn",function(e){

        var id = $(this).attr('data-id');
        var market_value = $(this).attr('market_value');
        var quantity = $(this).attr('quantity');
        var symbol = $(this).attr('symbol');

        $.confirm({
                title: 'Sell Confirmation',
                content: 'Are you sure you want to Sell Now?',
                icon: 'fa fa-warning',
                animation: 'zoom',
                closeAnimation: 'zoom',
                opacity: 0.5,
                buttons: {
                confirm: {
                    text: 'Yes, sure!',
                    btnClass: 'btn-red',
                    action: function ()
                    { 

                        $("#"+id).html('<img src="<?php echo IMG?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

                        $.ajax({
                            'url': '<?php echo SURL ?>admin/dashboard/sell_order',
                            'type': 'POST', //the way you want to send data to your URL
                            'data': {id: id,market_value:market_value,quantity:quantity,symbol:symbol},
                            'success': function (response) { 

                                if(response ==1){
                                    $("#"+id).html('Sell Now');   
                                }else{
                                    $.confirm({
                                        title: 'Encountered an error!',
                                        content: response,
                                        type: 'red',
                                        typeAnimated: true,
                                        buttons: {
                                            tryAgain: {
                                                text: 'Ok',
                                                btnClass: 'btn-red',
                                                action: function(){
                                                }
                                            },
                                            close: function () {
                                            }
                                        }
                                    });
                                }
                                
                            }
                        });
                    }
                },
                cancel: function () {
                    
                }
            }
                
        });
    
    });


    $("body").on("click",".buy_now_btn",function(e){

        var id = $(this).attr('data-id');
        var market_value = $(this).attr('market_value');
        var quantity = $(this).attr('quantity');
        var symbol = $(this).attr('symbol');

        $.confirm({
                    title: 'Buy Confirmation',
                    content: 'Are you sure you want to Buy Now?',
                    icon: 'fa fa-warning',
                    animation: 'zoom',
                    closeAnimation: 'zoom',
                    opacity: 0.5,
                    buttons: {
                    confirm: {
                        text: 'Yes, sure!',
                        btnClass: 'btn-red',
                        action: function ()
                        {

                            $("#"+id).html('<img src="<?php echo IMG?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

                            $.ajax({
                                'url': '<?php echo SURL ?>admin/dashboard/buy_order',
                                'type': 'POST', //the way you want to send data to your URL
                                'data': {id: id,market_value:market_value,quantity:quantity,symbol:symbol},
                                'success': function (data) { 

                                    $("#"+id).html('Buy Now');
                                }
                            });
                        }
                    },
                    cancel: function () {
                        
                    }
                }
                    
        });

    });



  $("body").on("click","#buy_all_btn",function(e){

        $.confirm({
                title: 'Buy Confirmation',
                content: 'Are you sure you want to Buy All?',
                icon: 'fa fa-warning',
                animation: 'zoom',
                closeAnimation: 'zoom',
                opacity: 0.5,
                buttons: {
                confirm: {
                    text: 'Yes, sure!',
                    btnClass: 'btn-red',
                    action: function ()
                    {
                        $("#buy_all_btn").html('<img src="<?php echo IMG?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

                        $.ajax({
                            'url': '<?php echo SURL ?>admin/dashboard/buy_all_orders',
                            'type': 'POST', //the way you want to send data to your URL
                            'data': "",
                            'success': function (response) { 

                                var resp = response.split('|');              
                                $('#response_market_trading1').html(resp[0]);
						        $('#response_market_trading2').html(resp[1]);
						        $('#response_market_trading3').html(resp[2]);
						        $('#response_market_trading4').html(resp[3]);
						        $('#response_market_trading5').html(resp[4]);
						        $('#response_market_trading6').html(resp[5]);
						        $('#response_market_trading7').html(resp[6]);
                                $('#response_market_trading8').html(resp[7]);

                                $('#counter1').html(resp[8]);
                                $('#counter2').html(resp[9]);
                                $('#counter3').html(resp[10]);
                                $('#counter4').html(resp[11]);
                                $('#counter5').html(resp[12]);
                                $('#counter6').html(resp[13]);
                                $('#counter7').html(resp[14]);
                                $('#counter8').html(resp[15]);
        
                                $("#buy_all_btn").html('Sell All');

                             
                            }
                        });
                    }
                },
                cancel: function () {
                    
                }
            }
                
        });

  });


    $("body").on("click",".custom_refresh",function(e){

        var order_id = $(this).attr('order_id');
        var id = $(this).attr('data-id');

        if(order_id !=""){

            $(this).html('<img src="<?php echo IMG?>loader.gif" width="20" height="20" style="margin-top: -2px;"/>');

            $.ajax({
                'url': '<?php echo SURL ?>admin/dashboard/get_buy_order_status',
                'type': 'POST',
                'data': {id:id,order_id:order_id},
                'success': function (response) { 

                }
            });
        }
    
    });
  
  
</script>