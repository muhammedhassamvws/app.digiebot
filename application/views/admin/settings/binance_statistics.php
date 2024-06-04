<div id="content">
    <h1 class="content-heading bg-white border-bottom">Binance API Statistics</h1>
  
    <div class="innerAll spacing-x2">

        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="panel" style="visibility: visible;">
                    <div class="front">

                        <div class="widget text-center">
                            <div class="widget-body padding-none">
                                <div tabindex="5001" style="height: 100px; overflow: hidden; outline: none;">
                                    <div class="box-generic border-none text-center bg-inverse">
                                        <p class="margin-none"><b>Market Depth Counter</b></p>
                                        <p><strong class="text-large text-primary" id="response_market_depth" style="color:#6ecb40 !important;"><?php echo number_format($count_market_depth);?></strong></p>
                                    </div>
                                </div>
                                <div class="innerAll">
                                    <button class="btn btn-danger" id="remove_market_depth_data">Delete Market Depth Data</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="panel" style="visibility: visible;">
                    <div class="front">

                        <div class="widget text-center">
                            <div class="widget-body padding-none">
                                <div tabindex="5001" style="height: 100px; overflow: hidden; outline: none;">
                                    <div class="box-generic border-none text-center bg-inverse">
                                        <p class="margin-none"><b>Market Trade Couner</b></p>
                                        <p><strong class="text-large text-primary" id="response_market_trade" style="color:#6ecb40 !important;"><?php echo number_format($count_market_trade);?></strong></p>
                                    </div>
                                </div>
                                <div class="innerAll">
                                    <button class="btn btn-danger" id="remove_market_trade_data">Delete Market Trade Data</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="panel" style="visibility: visible;">
                    <div class="front">

                        <div class="widget text-center">

                            <div class="widget-body padding-none">
                                <div tabindex="5001" style="height: 100px; overflow: hidden; outline: none;">
                                    <div class="box-generic border-none text-center bg-inverse">
                                        <p class="margin-none"><b>CandleStick Counter</b></p>
                                        <p><strong class="text-large text-primary" id="response_candle_stick" style="color:#6ecb40 !important;"><?php echo number_format($count_candle_stick_records);?></strong></p>
                                    </div>
                                </div>
                                <div class="innerAll">
                                    <button class="btn btn-danger" id="remove_candle_stick_data">Delete CandleStick Data</button>
                                </div>
                            </div>
                          
                        </div>

                    </div>
                </div>
            </div>
            
        </div>


        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="panel" style="visibility: visible;">
                    <div class="front">

                        <div class="widget text-center">
                            <div class="widget-body padding-none">
                                <div tabindex="5001" style="height: 100px; overflow: hidden; outline: none;">
                                    <div class="box-generic border-none text-center bg-inverse">
                                        <p class="margin-none"><b>Candle Stick Duplication</b></p>
                                        <p><strong class="text-large text-primary" id="response_candle_repeating" style="color:#6ecb40 !important;"><?php echo number_format($count_candle_stick_repeating);?></strong></p>
                                    </div>
                                </div>
                                <div class="innerAll">
                                    <button class="btn btn-danger" id="remove_candle_repeat">Delete Candle Stick Duplication counter</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
            
        </div>

    </div>

</div>

<script type="text/javascript">

 function autoload_binance_statistics(){
    
      $.ajax({
        type:'POST',
        url:'<?php echo SURL?>admin/sockets/autoload_binance_statistics',
        data: "",
        success:function(response){

            var res = response.split("|");
         
            $('#response_market_depth').html(res[0]);
            $('#response_market_trade').html(res[1]);
            $('#response_candle_stick').html(res[2]);
            $('#response_candle_repeating').html(res[3]);

            
         
            setTimeout(function() {
                  autoload_binance_statistics();
            }, 3000);
         
        }
      });

}//end autoload_binance_statistics() 

autoload_binance_statistics();


$("body").on("click","#remove_market_depth_data",function(e){

    $.confirm({
                title: 'Delete Confirmation',
                content: 'Are you sure you want to Delete?',
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

                        $("#remove_market_depth_data").html('<img src="<?php echo IMG?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

                        $.ajax({
                            'url': '<?php echo SURL ?>admin/sockets/delete_market_depth_socket',
                            'type': 'POST',
                            'data': "",
                            'success': function (data) { 

                                $("#remove_market_depth_data").html('Delete Market Depth Data');
                            }
                        });

                    }
                },
                cancel: function () {
                    
                }
            }
                
    });

});


$("body").on("click","#remove_market_trade_data",function(e){

    $.confirm({
                title: 'Delete Confirmation',
                content: 'Are you sure you want to Delete?',
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

                        $("#remove_market_trade_data").html('<img src="<?php echo IMG?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

                        $.ajax({
                            'url': '<?php echo SURL ?>admin/sockets/delete_market_trade_socket',
                            'type': 'POST',
                            'data': "",
                            'success': function (data) { 

                                $("#remove_market_trade_data").html('Delete Market Trade Data');
                            }
                        });

                    }
                },
                cancel: function () {
                    
                }
            }
                
    });

});


$("body").on("click","#remove_candle_stick_data",function(e){

    $.confirm({
                title: 'Delete Confirmation',
                content: 'Are you sure you want to Delete?',
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

                        $("#remove_candle_stick_data").html('<img src="<?php echo IMG?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

                        $.ajax({
                            'url': '<?php echo SURL ?>admin/sockets/delete_candle_socket',
                            'type': 'POST',
                            'data': "",
                            'success': function (data) { 

                                $("#remove_candle_stick_data").html('Delete CandleStick Data');
                            }
                        });

                    }
                },
                cancel: function () {
                    
                }
            }
                
    });

});




$("body").on("click","#remove_candle_repeat",function(e){

    $.confirm({
                title: 'Delete Confirmation',
                content: 'Are you sure you want to Delete?',
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

                        $("#remove_candle_repeat").html('<img src="<?php echo IMG?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

                        $.ajax({
                            'url': '<?php echo SURL ?>admin/sockets/delete_candle_repeat',
                            'type': 'POST',
                            'data': "",
                            'success': function (data) { 

                                $("#remove_candle_repeat").html('Delete CandleStick Data');
                            }
                        });

                    }
                },
                cancel: function () {
                    
                }
            }
                
    });

});


</script>          
