<?php $session_post_data = $this->session->userdata('filter-data'); ?>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Sell Orders Listing</h1>

  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
        <li class="active"><a href="<?php echo SURL; ?>admin/dashboard/orders-listing">Sell Order Listing</a></li>
    </ul>
  </div>
  
  <div class="innerAll spacing-x2">

        <form class="form-horizontal margin-none" method="post" action="<?php echo SURL?>admin/dashboard/orders-listing" novalidate="novalidate">
        <div class="widget widget-inverse">     
            <div class="col-xs-12" style="padding: 10px 10px;">
                
                <div class="col-md-6"> 
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

                <div class="col-md-6"> 
                    <button class="btn btn-success" type="submit" style="margin-top: 18px;"><i class="fa fa-check-circle"></i> Search </button>
                    <a href="<?php echo SURL?>admin/dashboard/reset_filters" class="btn btn-primary btn_withIcon" style="margin-top: 18px;">
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
                        	<h4 class="heading" style=" padding-top: 3px;">Sell Orders</h4>
                            <button class="btn btn-danger pull-right" id="sell_all_btn" style="margin-top: 6px;">Sell All</button>
                        </div>
                        <div class="widget-body padding-none">
                            <div id="response_market_trading">
                            	<table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><strong>Coin</strong></th>
                                            <th><strong>Entry Price</strong></th>
                                            <th><strong>Exit Price</strong></th>
                                            <th><strong>Quantity</strong></th>
                                            <th><strong>Profit Target</strong></th>
                                            <th><strong>Sell Price</strong></th>
                                            <th><strong>Trail Price</strong></th>
                                            <th class="text-center"><strong>P/L</strong></th>
                                            <th class="text-center"><strong>Status</strong></th>
                                            <th class="text-center"><strong>Actions</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(count($orders_arr)>0){
                                        foreach ($orders_arr as $key=>$value) {

                                        //Get Market Price
                                        $market_value = $this->mod_dashboard->get_market_value($value['symbol']);   
                                        ?>    
                                        <tr>
                                            <td class="center">
                                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="<?php echo $value['_id'];?>"><i class="fa fa-eye"></i></button>
                                            </td>
                                            <td><?php echo $value['symbol']; ?></td>
                                            <td>
                                            <td><?php echo num($value['purchased_price']); ?></td>
                                            <td>
                                            <?php 
                                            if($value['market_value'] !=""){ 
                                              echo num($value['market_value']);
                                             } ?>
                                            </td>
                                            <td><?php echo $value['quantity']; ?></td>
                                            <td>
                                            <?php 
                                            if($value['profit_type'] =='percentage'){
                                                echo $value['sell_profit_percent']."%";
                                            }else{
                                                echo num($value['sell_profit_price']);
                                            }
                                            ?>
                                            </td>
                                            <td><?php echo num($value['sell_price']); ?></td>
                                            <td>
                                            <?php if($value['trail_check']=='yes'){
                                                echo num($value['sell_trail_price']);
                                            }else{
                                                echo "-";
                                            }
                                            ?>
                                            </td>
                                            <td class="center">
                                            <?php 
                                            if($value['status'] !='new'){
                                               $market_value111 = num($value['market_value']);
                                            }else{
                                               $market_value111 = num($market_value);
                                            }

                                            $current_data = $market_value111 - $value['purchased_price'];   
                                            $market_data = ($current_data * 100 / $market_value111);

                                            $market_data = number_format((float)$market_data, 2, '.', '');

                                            if($market_value111 > $value['purchased_price']){
                                                $class = 'success';
                                            }else{
                                                $class = 'danger';  
                                            }

                                            if($value['profit_type'] =='percentage'){ ?>
                                            <span class="text-<?php echo $class;?>"><b><?php echo $market_data;?>%</b></span>
                                            <?php }else{ ?>
                                            <span class="text-<?php echo $class;?>"><b><?php echo $market_value111;?></b></span>
                                            <?php } ?>
                                            </td>

                                            <td class="center">
                                            <span class="label label-success"><?php echo ucfirst($value['status']); ?></span>
                                            </td>

                                            <td class="center">
                                                <div class="btn-group btn-group-xs ">
                                                <?php if($value['status'] =='new'){ ?>    
                                                    <a href="<?php echo SURL.'admin/dashboard/edit-order/'.$value['_id'];?>" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>
                                                <?php } ?>  
                                                <?php if($value['status'] !='FILLED'){ ?>    
                                                    <a href="<?php echo SURL.'admin/dashboard/delete-order/'.$value['_id'].'/'.$value['binance_order_id'];?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete?')"><i class="fa fa-times"></i></a>
                                                <?php } ?>    
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <?php    
                                                if($value['status'] =='new'){
                                                ?>
                                                <button class="btn btn-danger sell_now_btn" id="<?php echo $value['_id']; ?>" data-id="<?php echo $value['_id'];?>" market_value="<?php echo $market_value;?>" quantity="<?php echo $value['quantity'];?>" symbol="<?php echo $value['symbol'];?>">Sell Now</button>
                                                <?php } ?>
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
 
    function autoload_market_data(){
    
      $.ajax({
        type:'POST',
        url:'<?php echo SURL?>admin/dashboard/autoload_market_data',
        data: "",
        success:function(response){
         
            $('#response_market_trading').html(response);
         
            setTimeout(function() {
                  autoload_market_data();
            }, 3000);
         
        }
      });

    }//end autoload_market_data() 

    autoload_market_data();


    $("body").on("click",".view_order_details",function(e){

        var order_id = $(this).attr("data-id");

         $.ajax({
            'url': '<?php echo SURL ?>admin/dashboard/get_sell_order_details_ajax',
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
                            'success': function (data) { 

                                $("#"+id).html('Sell Now');
                            }
                        });
                    }
                },
                cancel: function () {
                    
                }
            }
                
        });
    
    });



    $("body").on("click","#sell_all_btn",function(e){

        $.confirm({
                title: 'Sell Confirmation',
                content: 'Are you sure you want to Sell All?',
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
                        $("#sell_all_btn").html('<img src="<?php echo IMG?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

                        $.ajax({
                            'url': '<?php echo SURL ?>admin/dashboard/sell_all_orders',
                            'type': 'POST', //the way you want to send data to your URL
                            'data': "",
                            'success': function (response) { 

                                $('#response_market_trading').html(response);
                                $("#sell_all_btn").html('Sell All');
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
                'url': '<?php echo SURL ?>admin/dashboard/get_sell_order_status',
                'type': 'POST',
                'data': {id:id,order_id:order_id},
                'success': function (response) { 

                }
            });
        }
    
    });
    
  
</script>