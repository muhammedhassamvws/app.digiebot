
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php  echo SURL ?>assets/dist/jquery-asPieProgress.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style type="text/css">
    label.error {
        /* remove the next line when you have trouble in IE6 with labels in list */
        color: red;
        font-style: italic
    }

    .alert-ignore {
        font-size: 12px;
        border-color: #b38d41;
    }

    small {
        color: orange;
        font-weight: bold;
    }

    .btnbtn {
        padding-left: 0px;
    }

    .btnval {
        width: 9%;
        font-size: 11px;
        height: auto;
        padding-left: 3px;
        margin-left: 5px;
        background: #ffffff;
        border-color: #373737;
        border-radius: 6px;
        color: #373737;
        font-weight: bold;
    }

    .btnval2 {
        width: 9%;
        font-size: 11px;
        height: auto;
        padding-left: 3px;
        margin-left: 5px;
        background: #ffffff;
        border-color: #373737;
        border-radius: 6px;
        color: #373737;
        font-weight: bold;
    }

    .btnval3 {
        width: 9%;
        font-size: 11px;
        height: auto;
        padding-left: 3px;
        margin-left: 5px;
        background: #ffffff;
        border-color: #373737;
        border-radius: 6px;
        color: #373737;
        font-weight: bold;
    }

    .btnval1 {
        width: 9%;
        font-size: 11px;
        height: auto;
        padding-left: 3px;
        margin-left: 5px;
        background: #ffffff;
        border-color: #373737;
        border-radius: 6px;
        color: #373737;
        font-weight: bold;
    }

    .btn-group,
    .btn-group-vertical {
        position: relative;
        display: inline-block;
        vertical-align: middle;
        padding-bottom: 10px;
    }

    .close {
        margin-top: -2%;
    }

    .slidecontainer {
        width: 100%;
    }

    .slider {
        -webkit-appearance: none;
        width: 100%;
        height: 25px;
        background: #d3d3d3;
        outline: none;
        opacity: 0.7;
        -webkit-transition: .2s;
        transition: opacity .2s;
    }

    .slider:hover {
        opacity: 1;
    }
    .ax_2, .ax_3, .ax_4, .ax_5, .ax_6, .ax_7, .ax_8, .ax_9, .ax_10, .ax_11, .ax_12, .ax_13, .ax_14, .ax_15, .ax_16, .ax_17, .ax_18, .ax_19 {
      padding-bottom: 35px !important;
    }
    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 25px;
        height: 25px;
        background: #4CAF50;
        cursor: pointer;
    }

    .slider::-moz-range-thumb {
        width: 25px;
        height: 25px;
        background: #4CAF50;
        cursor: pointer;
    }

    .get-relative {
        position: relative;
    }

    .optimized-loader {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        text-align: center;
        background: rgba(255, 255, 255, 0.7);
        z-index: 99;

    }

    .optimized-loader img {
        width: 50px;
        height: 50px;
        position: absolute;
        margin: auto;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }
</style>
<div id="content" class="get-relative">
    <div class="optimized-loader" style="display:none;">
        <img src="https://app.digiebot.com/assets/images/load_cube.gif">
    </div>
    <h1 class="content-heading bg-white border-bottom">Add Buy Order</h1>
    <?php $filter_user_data = $this->session->userdata('filter_order_data');?>
    <!-- <div class="bg-white innerAll border-bottom">
        <ul class="menubar">
            <li><a href="<?php echo SURL; ?>admin/buy_orders/">Buy Order Listing</a></li>
            <li class="active"><a href="#">Add Buy Order</a></li>
        </ul>
        <span class="fa fa-info-circle" style="float: right;font-size: 20px;margin-top: -25px;color: #cb4040;" data-toggle="popover" data-placement="left" data-trigger="hover" data-container="body" data-original-title="Add Buy Manual Order" data-content="Here in Manual Order you can set the price and quantity of order you want to Buy. Moreover you can set the Trail Period of Buy and Sell, AutoSell, sell percentage etc."></span>
    </div> -->

    <div class="innerAll spacing-x2">
    
    
    <div id="sell_array_response" id="message"></div>
        
    <?php 
        if ($this->session->flashdata('error_message')) {
        ?>
            <div class="alert alert-danger" id="message"><?php echo $this->session->flashdata('error_message'); ?></div>
        <?php
        }
        if ($this->session->flashdata('query_message')) {
        ?>
            <div class="alert alert-success alert-dismissable" id="message"><?php echo $this->session->flashdata('query_message'); ?></div>
        <?php
        }
        ?>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="widget widget-inverse">
                <div class="widget-head" style="text-align:center">
                    <h2>Create Child Orders</h2>
                </div>
                <div class="widget-body">
                    <!-- Row -->
                    <div class="row">
                        <div class="col-md-3 ax_1">
                            <div class="form-group">
                                <label>Filter Level: </label>
                                <select id="filter_by_level" name="filter_by_level" type="text" class="form-control filter_by_name_margin_bottom_sm filter_by_level" >
                                    <option value="level_1">Level 1</option>
                                    <option value="level_2">Level 2</option>
                                    <option value="level_3">Level 3</option>
                                    <option value="level_4">Level 4</option>
                                    <option value="level_5">Level 5</option>
                                    <option value="level_6">Level 6</option>
                                    <option value="level_7">Level 7</option>
                                    <option value="level_8">Level 8</option>
                                    <option value="level_9">Level 9</option>
                                    <option value="level_10">Level 10</option>
                                    <option value="level_11">Level 11</option>
                                    <option value="level_12">Level 12</option>
                                    <option value="level_13">Level 13</option>
                                    <option value="level_14">Level 14</option>
                                    <option value="level_15">Level 15</option>
                                    <option value="level_16">Level 16</option>
                                    <option value="level_17">Level 17</option>
                                    <option value="level_18">Level 18</option>
                                    <option value="level_19">Level 19</option>
                                    <option value="level_20">Level 20</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3  ax_2" style=" min-height: 60px;">
                            <div class="form-group">
                                <label>Filter Coin: </label>
                                <select id="filter_by_coin"  name="filter_by_coin" type="text" class=" filter_by_name_margin_bottom_sm">
                                    <?php foreach($coins as $coinRow){  ?>      
                                    <option value="<?php echo $coinRow['symbol'] ?>"><?php echo $coinRow['symbol'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-3  ax_3" style=" min-height: 60px;">
                            <div class="form-group">
                                <label>Enter account Id:</label>
                                <input type="text" id="user_id" class="form-control" name="user_id" placeholder="Enter user Id" value="<?php echo $this->session->userdata('user_idd'); ?>" required>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3  ax_4" style=" min-height: 60px;">
                            <div class="form-group">
                                <label>Enter Quantity:</label>
                                <input type="text" id="quantity" class="form-control" name="quantity" placeholder="Enter Quantity" value="<?php echo $this->session->userdata('quantity'); ?>" required>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3  ax_5" style=" min-height: 60px;">
                            <div class="Input_text_s">
                                <label>Select Trading Ip: </label>
                                <select name="trading_ip" class="form-control">
                                    <option value="">Select IP for Trading</option>
                                    <option value="3.227.143.115">3.227.143.115</option>
                                    <option value="3.228.180.22">3.228.180.22</option>
                                    <option value="3.226.226.217">3.226.226.217</option>
                                    <option value="3.228.245.92">3.228.245.92</option>
                                    <option value="35.153.9.225">35.153.9.225</option>
                                </select>
                            </div>
                        </div>


                         <div class="col-xs-12 col-sm-12 col-md-3  ax_6" style=" min-height: 60px;">
                            <div class="Input_text_s">
                            <label>Select Order Type: </label>
                                <select name="orderType" class="form-control" id="orderType">
                                    <option value="auto">Auto</option>
                                    <option value="manul">Manual</option>
                                </select>
                            </div>
                        </div>
                   
                        <div class="col-md-3 ax_7">
                            <div class="form-group">
                                <label>Select Exchange:</label>
                                <select class="form-control" name="exchange"  id="exchange">
                                    <option value="binance">binance</option>
                                    <option value="bam">bam</option>
                                    <option value="kraken">kraken</option>
                                    <option value="dg">Digie</option>
                                    <option value="okex">Okex</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-3  ax_8" style=" min-height: 60px;">
                            <div class="form-group">
                                <label>Enter Purchase Price:</label>
                                <input type="text" id="purchase_price" class="form-control" name="purchase_price" placeholder="Enter Pruchase Price" value="<?php echo $this->session->userdata('purchase_price'); ?>" required>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3 ax_9" style=" min-height: 60px;">
                            <div class="form-group">
                                <label>Enter Kraken Id:</label>
                                <input type="text" id="order_id" class="form-control" name="order_id" placeholder="Enter Kraken Id" value="<?php echo $this->session->userdata('order_id'); ?>" required>
                            </div>
                        </div>

                        <div class="col-md-1 ax_10">
                            <div class="form-actions form-group" style="margin-top:22px">
                                <button class="btn btn-success" id="add_order_p" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order">Create Child</button>
                            </div>
                        </div>
                    </div>
                    <!-- Form actions -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('body').on("click", "#add_order_p", function(e){
        var $this = $(this);
        $this.button('loading');
        var user_id = $("#user_id").val();
        var filter_by_level = $('#filter_by_level').val();
        var filter_by_coin = $('#filter_by_coin').val();
        var quantity = $('#quantity').val();
        var exchange = $('#exchange').val();
        var orderType = $('#orderType').val();
        var purchase_price = $('#purchase_price').val();
        var trading_ip =  $('#trading_ip').val();
        var order_id =  $('#order_id').val();
        console.log(orderType);
        var surl = '<?php echo SURL ?>';
        $.ajax({
            url: surl + "/admin/buy_orders/create_child_process",
            data: {orderType: orderType, userid: user_id, exchange: exchange, filter_by_level: filter_by_level, filter_by_coin:filter_by_coin, purchase_price:purchase_price, quantity:quantity, trading_ip: trading_ip, order_id:order_id },
            type: "POST",
            success: function(datajson) {
                $this.button('reset');
                alert("Created Sucessfully");
            },
      error: function() {
        alert('Some Thing Wrong');
      }
        });
    });

// flash message time out 
    $(document).ready(function() {
        setTimeout(function() {
            $("#message").hide('blind', {}, 500)
        }, 5000);
    });

</script>
<script type="text/javascript">
$(document).ready(function() {
    $('#filter_by_coin, #filter_by_rule_select, #filter_by_level_select').multiselect({
      includeSelectAllOption: true,
      buttonWidth: 435.7,
      enableFiltering: true
    });
});
</script> 