<?php $session_post_data = $this->session->userdata('filter-data'); ?>

<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
<style type="text/css">
    .tab-pane
    {
        height: 1024px !important;
    }
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Sell Orders Listing</h1>

  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
        <li class="active"><a href="<?php echo SURL; ?>admin/sell_orders">Sell Order Listing</a></li>
    </ul>
  </div>
  
  <div class="innerAll spacing-x2">

        <form class="form-horizontal margin-none" method="post" action="<?php echo SURL?>admin/sell_orders/" novalidate="novalidate">
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
                   

                <div class="col-md-4"> 
                    <button class="btn btn-success" type="submit" style="margin-top: 18px;"><i class="fa fa-check-circle"></i> Search </button>
                    <a href="<?php echo SURL?>admin/sell_orders/reset_filters" class="btn btn-primary btn_withIcon" style="margin-top: 18px;">
                    <span class="glyphicon glyphicon-refresh"></span> Reset</a>
                </div>
                   
            </div>    
            <div class="clearfix"></div>      
        </div> 
        </form> 
  	
        <div class="widget widget-inverse widget-scroll">  
            <div class="widget-head" style="height:46px;">               
            <h4 class="heading" style=" padding-top: 3px;">Sell Orders</h4>
            <button class="btn btn-danger pull-right" id="sell_all_btn" style="margin-top: 6px;">Sell All</button>
            </div>   
            <div class="box-generic">
                <!-- Tabs Heading -->
                <div class="tabsbar">
                    <ul id="tabss">
                        <li class="glyphicons eye_open active"><a class="tab-tab" id="new" href="#tab2-3" data-toggle="tab"><i></i> New <strong>(<span id="counter1"><?php echo count($new_arr); ?></span>)</strong></a></li>
                        <li class="glyphicons circle_ok"><a class="tab-tab" href="#tab3-3" id="FILLED" data-toggle="tab"><i></i> Filled <strong>(<span id="counter2"><?php echo count($filled_arr); ?></span>)</strong></a></li>
                        <li class="glyphicons circle_remove tab-stacked"><a class="tab-tab" id="canceled" href="#tab4-3" data-toggle="tab"><i></i> <span>Canceled <strong>(<span id="counter3"><?php echo count($cancelled_arr); ?></span>)</strong></span></a></li>
                        <li class="glyphicons warning_sign tab-stacked"><a id="error" class="tab-tab" href="#tab5-3" data-toggle="tab"><i></i> <span>Error<strong>(<span id="counter4"><?php echo count($error_arr); ?></span>)</strong></span></a></li>
                        <li class="glyphicons show_big_thumbnails"><a id="all" class="tab-tab" href="#tab1-3" data-toggle="tab"><i></i> View all Sell Orders <strong>(<span id="counter5"><?php echo count($orders_arr); ?></span>)</strong></a></li>

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
                                    if(count($new_arr)>0){
                                        foreach ($new_arr as $key=>$value) {

                                        //Get Market Price
                                        $market_value = $this->mod_dashboard->get_market_value($value['symbol']);   
                                        ?>    
                                        <tr>
                                            <td class="center">
                                                <button class="btn btn-default view_order_details" title="View Order Details" data-id="<?php echo $value['_id'];?>"><i class="fa fa-eye"></i></button>
                                            </td>
                                            <td><?php echo $value['symbol']; ?></td>
                                            
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
                                                    <a href="<?php echo SURL.'admin/sell_orders/edit-order/'.$value['_id'];?>" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>
                                                <?php } ?>  
                                                <?php if($value['status'] !='FILLED'){ ?>    
                                                    <a href="<?php echo SURL.'admin/sell_orders/delete-order/'.$value['_id'].'/'.$value['binance_order_id'];?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete?')"><i class="fa fa-times"></i></a>
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
                    <!-- // Tab content END -->
                    <!-- Tab content -->
                    <div class="tab-pane" id="tab3-3">
                        
                    </div>
                    <!-- // Tab content END -->
                    <!-- Tab content -->
                    <div class="tab-pane" id="tab4-3">
                        
                    </div>

                    <div class="tab-pane" id="tab5-3">
                       
                    </div>
                    <!-- // Tab content END -->
                    <!-- Tab content -->
                    <div class="tab-pane" id="tab1-3">
                         
                    </div>
                    <!-- // Tab content END -->
              </div>
          </div>
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
    
      var activeli = $('ul#tabss').find('li.active').find('a');
      var hrf = $(activeli).attr('href');
      var id  = $(activeli).attr('id');
      var page = $(hrf).find('ul.pagination').find('li.active').find('a').find('b').html();

      console.log(page);
      if (page == null) { page = 1; }
    $.ajax({
    type: "GET",
    data: {status:id},
    url:'<?php echo SURL?>admin/sell_orders/get_order_ajax/'+page,
    success:function(response){
        $(hrf).html(response);
        setTimeout(function() {
                    autoload_market_data();
              }, 5000);
    }
});
    }//end autoload_market_data() 

    autoload_market_data();

  function autoload_order_count()
    {
     $.ajax({
    type: "POST",
    data: "",
    url:'<?php echo SURL?>admin/sell_orders/get_all_counts',
    success:function(response){
        resp = response.split('|');
        $('#counter1').html(resp[0]);
        $('#counter2').html(resp[1]);
        $('#counter3').html(resp[2]);
        $('#counter4').html(resp[3]);
        $('#counter5').html(resp[4]);
        setTimeout(function() {
            autoload_order_count();
        }, 5000);
    }
  });
  }

    autoload_order_count();
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

                                var resp = response.split('|');              
                                $('#response_market_trading1').html(resp[0]);
                                $('#response_market_trading2').html(resp[1]);
                                $('#response_market_trading3').html(resp[2]);
                                $('#response_market_trading4').html(resp[3]);
                                $('#response_market_trading5').html(resp[4]);

                                $('#counter1').html(resp[5]);
                                $('#counter2').html(resp[6]);
                                $('#counter3').html(resp[7]);
                                $('#counter4').html(resp[8]);
                                $('#counter5').html(resp[9]);
            
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
<script type="text/javascript">
  $("body").on("click",".tab-tab", function(e){
    var hrf = $(this).attr('href');
    $(hrf).html('<div id="preloader" class="col-md-12" style="text-align: center;"><img src="<?php echo SURL; ?>assets/images/preloader.gif" style="width: 100px; height:100px;"></div>');
    var id  = $(this).attr('id');

    $.ajax({
        url: "<?php echo SURL; ?>admin/sell_orders/get_order_ajax/1",
        type: "GET",
        data: {status:id},
        success: function(response){
            $(hrf).html(response);
        }
    });

  });  

 $(document).on("click", ".pagination li a", function(event){
  event.preventDefault();
  var page = $(this).data("ci-pagination-page");
  //load_country_data(page);
  var activeli = $('ul#tabss').find('li.active').find('a');
  var hrf = $(activeli).attr('href');
  var id  = $(activeli).attr('id');
  /*console.log($(this).parent());*/
  $.ajax({
        url: "<?php echo SURL; ?>admin/sell_orders/get_order_ajax/"+page,
        type: "GET",
        data: {status:id},
        success: function(response){
            $(hrf).html(response);


            //$('.pagination').find('li').removeClass('active');
           // $(".pagination li a").parent().addClass('active');

            //$(".pagination li a[data-ci-pagination-page='"+page+"']").parent().addClass('active');
           
            //$(this).closest('li').addClass('active');
            // console.log($(this).parent());

        }
    });

 });

</script>