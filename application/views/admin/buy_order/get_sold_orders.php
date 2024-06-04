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
    .blink_me {
  animation: blinker 2s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>
<div id="content" class="get-relative">
    <div class="optimized-loader" style="display:none;">
        <img src="https://app.digiebot.com/assets/images/load_cube.gif">
    </div>
    <h1 class="content-heading bg-white border-bottom">Add Buy Order</h1>

    <div class="bg-white innerAll border-bottom">
        <ul class="menubar">
            <li><a href="<?php echo SURL; ?>admin/buy_orders/">Buy Order Listing</a></li>
            <li class="active"><a href="#">Add Buy Order</a></li>
        </ul>
        <span class="fa fa-info-circle" style="float: right;font-size: 20px;margin-top: -25px;color: #cb4040;" data-toggle="popover" data-placement="left" data-trigger="hover" data-container="body" data-original-title="Add Buy Manual Order" data-content="Here in Manual Order you can set the price and quantity of order you want to Buy. Moreover you can set the Trail Period of Buy and Sell, AutoSell, sell percentage etc."></span>
    </div>

    <div class="innerAll spacing-x2">
    
    
    <div id="sell_array_response" id="message"></div>
        
    <?php 
        if ($this->session->flashdata('error_message_1')) {
        ?>
            <div class="alert alert-danger" id="message"><?php echo $this->session->flashdata('error_message_1'); ?></div>
        <?php
        }
        if ($this->session->flashdata('query_message_1')) {
        ?>
            <div class="alert alert-success alert-dismissable" id="message"><?php echo $this->session->flashdata('query_message_1'); ?></div>
        <?php
        }
        ?>
    </div>

    <div class="row" >
        <div class="col-md-12"></div>
            <div class="widget widget-inverse" style="margin-left: 12px;">
                <div class="widget-head" style="text-align:center">
                    <h2>Search For Order</h2>
                </div>
                <div class="widget-body">
                    <!-- Row -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Enter Order Id:</label>                                                        
                                <input type="text" id="order_id" class="form-control" name="order_id" placeholder="Enter Order Id" value="<?php echo $this->session->userdata('parent_id1');?>">
                            </div>
                        </div>

                        <!-- <?php //$exch = $this->session->userdata('exchange1');if($this->session->userdata('exchange1')){?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Exchange:</label>
                                <select class="form-control" name="exchange" , id="exchange">
                                    <?php //if ($exch == 'binance') : ?>
                                        <option value="<?php //echo $this->session->userdata('exchange1'); ?>" checked><?php //echo $this->session->userdata('exchange1'); ?></option>
                                        <option value="kraken">kraken</option>
                                    <?php// endif; ?>
                                    <?php// if ($exch == 'kraken') : ?>
                                        <option value="<?php// echo $this->session->userdata('exchange1'); ?>" checked><?php //echo $this->session->userdata('exchange1'); ?></option>
                                        <option value="binance">binance</option>
                                    <?php //endif; ?>
                                </select>
                            </div>
                        </div>
                      <?php  //} else { ?> -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Exchange:</label>
                                <select class="form-control" name="exchange" , id="exchange">
                                    <option value="binance" <?php if($this->session->userdata('exchge') && $this->session->userdata('exchge') == 'binance'){ echo 'selected'; } ?>>binance</option>
                                    <option value="kraken" <?php if($this->session->userdata('exchge') && $this->session->userdata('exchge') == 'kraken'){ echo 'selected'; } ?>>kraken</option>
                                    <option value="dg" <?php if($this->session->userdata('exchge') && $this->session->userdata('exchge') == 'dg'){ echo 'selected'; } ?>>Digie</option>
                                    <option value="okex" <?php if($this->session->userdata('exchge') && $this->session->userdata('exchge') == 'okex'){ echo 'selected'; } ?>>Okex</option>
                                </select>
                            </div>
                                    </div><?php //} ?>

                        <div class="col-md-1">
                            <div class="form-actions form-group" style="margin-top:22px">
                                <button class="btn btn-success" id="get_parent" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order">Get Sold Order</button>
                            </div>
                        </div>

                    </div>

                    <!-- Form actions -->
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-12">
                        <div class="widget widget-inverse">
                            <div class="widget-head">
                                <h2>Sold Order Detail</h2>
                            </div>
                            <div class="widget-body" id="response_panel">
                                <?php // if($this->session->userdata('buy_data')){echo $this->session->userdata('buy_data');}
                                ?>

                            </div>
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('body').on("click", "#get_parent", function(e) {
        var $this = $(this);
        $this.button('loading');
        var order_id = $('#order_id').val();
        var exchange = $('#exchange').val();
        var surl = '<?php echo SURL ?>';
        $.ajax({
            url: surl + "/admin/buy_orders/get_sold_ajax",
            data: { orderid: order_id, exchange: exchange },
            type: "POST",
            success: function(datajson) {
                $this.button('reset');
                // alert(datajson);
                console.log(datajson);
                resp = datajson.split('@@@@@');
                $("#response_panel").html(resp[0]);
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