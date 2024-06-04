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
</style>
<div id="content" class="get-relative" style="margin-bottom:0px !important;overflow-x: hidden; 
    overflow-y: auto;">
    <div class="optimized-loader" style="display:none;">
        <img src="https://app.digiebot.com/assets/images/load_cube.gif">
    </div>
    <h1 class="content-heading bg-white border-bottom">Edit Buy Order</h1>
    <div class="innerAll spacing-x2">
        <div id="sell_array_response" id="message"></div>
        <?php 
        if ($this->session->flashdata('error')) {
        ?>
            <div class="alert alert-danger" id="message"><?php echo $this->session->flashdata('error'); ?></div>
        <?php
        }
        if ($this->session->flashdata('sucessMessage')) {
        ?>
            <div class="alert alert-success alert-dismissable" id="message"><?php echo $this->session->flashdata('sucessMessage'); ?></div>
        <?php
        }
        ?>
    </div>
        <?php $sessionData = $this->session->userdata('userPostData');?>
    <div class="row">
        <div class="col-md-12">
            <div class="widget widget-inverse" style="height:1000px !important;">
                <div class="widget-head">
                    <h2>Search For Order</h2>
                </div>
                <div class="widget-body">
                    <!-- Row -->
                    <form method="POST" action="<?php echo SURL; ?>admin/buy_orders/PercentageUpDownUpdate"> 
                        <div class="row">

                            <div class="col-xs-12 col-sm-12 col-md-3  ax_1"  style=" min-height: 60px;">
                                <div class="Input_text_s">
                                    <label>Enter order Id:</label>
                                    <input type="text" id="order_id" class="form-control" name="order_id" placeholder="Enter Order Id" value="<?=(!empty($sessionData['order_id']) ? $sessionData['order_id'] : "")?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3 ax_3"  style=" min-height: 60px;">
                                <div class="Input_text_s ">
                                    <label>Select Exchange:</label>
                                    <select class="form-control" name="exchange" , id="exchange">
                                        <option value="binance" <?php if ($sessionData['exchange'] == 'binance') {?> selected <?php }?>>Binance</option>
                                        <option value="bam" <?php if ($sessionData['exchange'] == 'bam') {?> selected <?php }?>>Bam</option>
                                        <option value="kraken" <?php if ($sessionData['exchange'] == 'kraken') {?> selected <?php }?>>Kraken</option>
                                        <option value="dg" <?php if ($sessionData['exchange'] == 'dg') {?> selected <?php }?>>Digeb</option>
                                        <option value="okex" <?php if ($sessionData['exchange'] == 'okex') {?> selected <?php }?>>Okex</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                    
                            <div class="col-xs-12 col-sm-12 col-md-3 ax_4"  style=" min-height: 60px;">
                                <div class="Input_text_s">
                                    <label>Enter Percentage Change:</label>
                                    <input type="text" class="form-control" name="percentageChange" placeholder="Enter Percentage Change in Number"value="<?=(!empty($sessionData['percentageChange']) ? $sessionData['percentageChange'] : "")?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3  ax_5"  style=" min-height: 60px;">
                                <div class="Input_text_s">
                                    <label>Filter Level: </label>
                                    <select name="percentageUpDown" type="text" class="form-control filter_by_name_margin_bottom_sm">
                                        <option value="positive" <?php if ($sessionData['percentageUpDown'] == "positive") {?> selected <?php }?>>Make Positive</option>
                                        <option value="negitive" <?php if ($sessionData['percentageUpDown'] == "negitive") {?> selected <?php }?>>Make Negitive</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3  ax_6"  style=" min-height: 60px;">
                                <label></label>    
                                <div class="Input_text_s percentageUpDown">
                                    <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i> Change Percentage</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Form actions -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// flash message time out 
    $(document).ready(function() {
        setTimeout(function() {
            $("#message").hide('blind', {}, 500)
        }, 5000);
    });

</script>