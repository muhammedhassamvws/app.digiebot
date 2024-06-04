<?php $logged_id_user_id = $this->session->userdata('admin_id');?>


<!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script> -->
<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

<!-- Data Tables -->
<link rel='stylesheet' href='https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css'>
<link rel='stylesheet' href='https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css'>
<script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-de7e2ef6bfefd24b79a3f68b414b87b8db5b08439cac3f1012092b2290c719cd.js"></script>
<script src='https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js'></script>
<script src='https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js'></script>
<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js'></script>
<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js'></script>
<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js'></script>
<script src='https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js'></script>
<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js'></script>
<script src='https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js'></script>
<script src='https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js'></script>
<!-- End Data Tables -->

<style>
    .failed {
        background: #e8bdbd !important;
    }
    .success-custom {
        background: rgb(203, 255, 203) !important;
    }
    .text-success-custom {
        color : #0d420d;
    }
    .text-danger-custom {
        color : #801515;
    }
    #example tbody td:first-child {
    position: relative;
    width: 100%;
    height: 100%;
}

.fill_style_toplft {
    position: absolute;
    top: 0;
    bottom: auto;
    left: 0;
    right: auto;
    background: #1e3374;
    padding: 0px 8px 0px 8px;
}
</style>

<style>
    .hi_error{
        background-color: #fde7e7;
    }
    .Input_text_s {
        /* display: inline; */
        position: relative;
    }
    .Input_text_s i {
        position: absolute;
        top: 33px;
        right: 10px;
    }
    .our-team {
    padding: 30px 0 40px;
    margin-bottom: 30px;
    background-color: #f7f5ec;
    text-align: center;
    overflow: hidden;
    position: relative;
    }
    .our-team .picture {
    display: inline-block;
    height: 230px;
    width: 230px;
    margin-bottom: 50px;
    z-index: 1;
    position: relative;
    }
    .our-team .picture::before {
    content: "";
    width: 100%;
    height: 0;
    border-radius: 50%;
    background-color: #1369ce;
    position: absolute;
    bottom: 135%;
    right: 0;
    left: 0;
    opacity: 0.9;
    transform: scale(3);
    transition: all 0.3s linear 0s;
    }
    .our-team:hover .picture::before {
    height: 100%;
    }
    .our-team .picture::after {
    content: "";
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background-color: #1369ce;
    position: absolute;
    top: 0;
    left: 0;
    z-index: -1;
    }
    .our-team .picture img {
    width: 100%;
    height: auto;
    border-radius: 50%;
    transform: scale(1);
    transition: all 0.9s ease 0s;
    }
    .our-team:hover .picture img {
    box-shadow: 0 0 0 14px #f7f5ec;
    transform: scale(0.7);
    }
    .our-team .title {
    display: block;
    font-size: 15px;
    color: #4e5052;
    text-transform: capitalize;
    }
    .our-team .social {
    width: 100%;
    padding: 0;
    margin: 0;
    background-color: #1369ce;
    position: absolute;
    bottom: -100px;
    left: 0;
    transition: all 0.5s ease 0s;
    }
    .our-team:hover .social {
    bottom: 0;
    }
    .our-team .social li {
    display: inline-block;
    }
    .our-team .social li a {
    display: block;
    padding: 10px;
    font-size: 17px;
    color: white;
    transition: all 0.3s ease 0s;
    text-decoration: none;
    }
    .our-team .social li a:hover {
    color: #1369ce;
    background-color: #f7f5ec;
    }

    /*** custom checkboxes ***/
    @import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);
    input[type=checkbox] { display:none; } /* to hide the checkbox itself */
    input[type=checkbox] + label:before {
    font-family: FontAwesome;
    display: inline-block;
    }
    .custom_label {
        font-size: 25px;
        width: 100%;
        text-align: center;
    }
    input[type=checkbox] + label:before { content: "\f096"; } /* unchecked icon */
    input[type=checkbox] + label:before { letter-spacing: 10px; } /* space between checkbox and label */
    input[type=checkbox]:checked + label:before { content: "\f046"; } /* checked icon */
    input[type=checkbox]:checked + label:before { letter-spacing: 5px; } /* allow space for check mark */
    /*Custom Switcher by Afzal*/
    .af-form-group-created {
        display: none;
    }
    .af-form-group-created.active {
        display: block;
    }
    .af-switcher {
        background-color: #0f1c42;
        box-shadow: #0f1c42 0px 0px 0px 11px inset;
        transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s, background-color 1.2s ease 0s;
        border-radius: 20px;
        cursor: pointer;
        display: inline-block;
        height: 25px;
        position: relative;
        vertical-align: middle;
        width: 60px;
        box-sizing: content-box;
        background-clip: content-box;
        bottom: 4px;
        margin-top: 5px;
        border: 1px solid #EEEEEE;
    }
    .af-switcher > small {
        border-radius: 50px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.4);
        height: 15px;
        position: absolute;
        top: 5px;
        width: 25px;
        left:30px;
        background-color: rgb(255, 255, 255);
        transition: background-color 0.4s ease 0s, left 0.2s ease 0s;
    }
    .af-switcher.af-switcher-small.af-switcher-default.active small {
        left: 4px;
    }
    .af-cust-radio {
    position: absolute;
    visibility: hidden;
    display: none;
    }
    .af-custom-radio-label{
    color: #0f1c42;
    display: inline-block;
    cursor: pointer;
    font-weight: bold;
    padding: 5px 20px;
    margin: 0;
    width:50%;
    float:left;
    }
    input[type=radio].af-cust-radio:checked + .af-custom-radio-label{
    color: #fff;
    background: #0f1c42;
    }
    .af-custom-radio-label + input[type=radio].af-cust-radio + .af-custom-radio-label {
    border-left:3px solid #0f1c42;
    }
    .af-radio-group {
    border:3px solid #0f1c42;
    display: block;
    margin: 5px 0 10px;
    border-radius: 10px;
    overflow: hidden;
    width: 290px;
    }
    .widget.widget-inverse {
        width: 100%;
        float: left;
        overflow-x: auto;
    }
    .af-table-body td:last-child {
        min-width: 130px !important;
    }
    .af-table-body td:nth-child(3) {
        min-width: 140px;
    }
    /* .af-table-body td:nth-child(13) {
        min-width: 200px;
    }
    .af-table-body td:nth-child(14) {
        min-width: 200px;
    } */
    .af-table-body td:nth-child(18) {
        min-width: 102px;
    }
    .af-table-body td:nth-child(19) {
        min-width: 200px;
    }
    .af-table-body td:nth-child(20) {
        min-width: 102px;
    }
    .af-table-body td:nth-child(21) {
        min-width: 102px;
    }
    .af-table-body {
        text-align: center;
    }
    /*---End-----*/

</style>

<div id="content">

  <h1 class="content-heading bg-white border-bottom">Reports</h1>



  <div class="innerAll bg-white border-bottom">

  <ul class="menubar">

    <li class=""><a href="<?php echo SURL; ?>admin/reports">Reports</a></li>

    <li class="active"><a href="#">Custom Report</a></li>

	</ul>

  </div>

  <div class="innerAll spacing-x2">

	 <?php

if ($this->session->flashdata('err_message')) {

    ?>

      <div class="alert alert-danger"><?php echo $this->session->flashdata('err_message'); ?></div>

      <?php

}

if ($this->session->flashdata('ok_message')) {

    ?>

      <div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('ok_message'); ?></div>

      <?php

}

?>

      <?php $filter_user_data = $this->session->userdata('filter_order_data');?>

      <div class="widget widget-inverse">

         <div class="widget-body">
            <!-- <button class="btn btn-info showhidebtn" onclick="create_custom_csv()">Show Filters / Hide Filters</button> -->
            <div class="showhide">
                <form id="order_report_filter_form" method="POST" action="<?php echo SURL; ?>admin/order_report_test/index">

                <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-12" style="padding-bottom: 6px;">

                    <div class="alert alert-info"><?php echo "Server Time: " . date("d-m-y H:i:s a"); ?></label>

                    </div>

                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">

                    <div class="Input_text_s">

                        <label>Filter Coin: </label>

                        <select id="filter_by_coin" name="filter_by_coin" type="text" class="form-control filter_by_name_margin_bottom_sm">

                            <option value ="" <?=(($filter_user_data['filter_by_coin'] == "") ? "selected" : "")?>>Search By Coin Symbol</option>

                            <?php

for ($i = 0; $i < count($coins); $i++) {

    $selected = ($coins[$i]['symbol'] == $filter_user_data['filter_by_coin']) ? "selected" : "";

    echo "<option value='" . $coins[$i]['symbol'] . "' $selected>" . $coins[$i]['symbol'] . "</option>";

}

?>

                        </select>

                    </div>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">

                    <div class="Input_text_s">

                        <label>Filter Mode: </label>

                        <select id="filter_by_mode" name="filter_by_mode" type="text" class="form-control filter_by_name_margin_bottom_sm">

                            <option value="">Search By Mode</option>

                            <option value="live"<?=(($filter_user_data['filter_by_mode'] == "live") ? "selected" : "")?>>Live</option>

                            <option value="test"<?=(($filter_user_data['filter_by_mode'] == "test") ? "selected" : "")?>>Test</option>

                        </select>

                    </div>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">

                    <div class="Input_text_s">

                        <label>From Date Range: </label>

                        <input id="filter_by_start_date" name="filter_by_start_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_start_date']) ? $filter_user_data['filter_by_start_date'] : "")?>">

                        <i class="glyphicon glyphicon-calendar"></i>

                    </div>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">

                    <div class="Input_text_s">

                        <label>To Date Range: </label>

                        <input id="filter_by_end_date" name="filter_by_end_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_end_date']) ? $filter_user_data['filter_by_end_date'] : "")?>">

                        <i class="glyphicon glyphicon-calendar"></i>

                    </div>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">

                    <div class="Input_text_s">

                        <label>Last Updated Date From: </label>

                        <input id="filter_by_start_date_m" name="filter_by_start_date_m" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_start_date_m']) ? $filter_user_data['filter_by_start_date_m'] : "")?>">

                        <i class="glyphicon glyphicon-calendar"></i>

                    </div>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">

                    <div class="Input_text_s">

                        <label>Last Updated Date To: </label>

                        <input id="filter_by_end_date_m" name="filter_by_end_date_m" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_end_date_m']) ? $filter_user_data['filter_by_end_date_m'] : "")?>">

                        <i class="glyphicon glyphicon-calendar"></i>

                    </div>

                </div>

                <script type="text/javascript">

                    $(function () {

                        $('.datetime_picker').datetimepicker();

                    });

                </script>

                <style>

                    .Input_text_btn {padding: 25px 0 0;}

                </style>

                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">

                    <div class="Input_text_s">

                        <label>Filter Trigger: </label>

                        <select id="filter_by_trigger" name="filter_by_trigger" type="text" class="form-control filter_by_name_margin_bottom_sm">

                        <option value="">Search By Trigger</option>

                        <option value="trigger_1" <?php if ($filter_user_data['filter_by_trigger'] == 'trigger_1') {?> selected <?php }?>>Trigger 1</option>

                        <option value="trigger_2" <?php if ($filter_user_data['filter_by_trigger'] == 'trigger_2') {?> selected <?php }?>>Trigger 2</option>

                        <option value="box_trigger_3" <?php if ($filter_user_data['filter_by_trigger'] == 'box_trigger_3') {?> selected <?php }?>>Box Trigger 3</option>

                        <option value="barrier_trigger" <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_trigger') {?> selected <?php }?>>BARRIER TRIGGER</option>

                        <option value="rg_15" <?php if ($filter_user_data['filter_by_trigger'] == 'rg_15') {?> selected <?php }?>>RG 15</option>

                            <option value="barrier_percentile_trigger" <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_percentile_trigger') {?> selected <?php }?>>Barrier Percentile Trigger</option>

                        <option value="no" <?php if ($filter_user_data['filter_by_trigger'] == 'no') {?> selected <?php }?>>Manual Order</option>

                        </select>

                    </div>

                </div>

                <!-- Hidden Searches -->

                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px; display: none;" id="barrier_t">

                    <div class="Input_text_s">

                        <label>Filter Rule: </label>

                        <select id="filter_by_rule" name="filter_by_rule" type="text" class="form-control filter_by_name_margin_bottom_sm">

                            <option value="">Filter By Rule</option>

                            <?php for ($i = 1; $i <= 10; $i++) {?>

                                <option value="<?=$i;?>" <?php if ($filter_user_data['filter_by_rule'] == $i) {?> selected <?php }?>>Rule No <?=$i;?></option>

                            <?php }?>

                        </select>

                    </div>

                </div>

                <?php /*
<div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px; display: none;" id="barrier_p_t">
<div class="Input_text_s">
<label>Filter Level: </label>
<select name="filter_level" class="form-control">
<?php $filter_level = $filter_user_data['filter_level'];?>
<option value="">Search By Level</option>

<?php foreach ($order_levels as $lev) { ?>
<option value="<?php echo $lev['level']; ?>" <?php if ($filter_level == $lev['level']) {?> selected <?php }?>><?php echo $lev['level']; ?></option>
<?php } ?>
</select>
</div>
</div>
 */?>

                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px; display: none;" id="barrier_p_t">
                    <div class="Input_text_s">
                        <label>Filter Level: </label>
                        <select id="filter_by_level" name="filter_level[]" multiple type="text" class="form-control">
                        <?php $filter_level = $filter_user_data['filter_level'];?>
                        <option value="">Search By Level</option>
                        <?php foreach ($order_levels as $lev) {?>
                            <option value="<?php echo $lev['level']; ?>" <?php echo (in_array($lev['level'], $filter_level) ? ' selected ' : ''); ?>><?php echo $lev['level']; ?></option>
                        <?php }?>
                        </select>
                    </div>
                </div>

                <!-- End Hidden Searches -->

                    <!-- Exchange auto/manual order  //Umer Abbas [4-11-19] -->
                    <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                    <div class="Input_text_s">
                        <label>Filter Auto/Manual Order: </label>
                        <select id="filter_by_nature" name="filter_by_nature" type="text" class="form-control filter_by_name_margin_bottom_sm">
                        <option value="">Search By Order Type</option>
                        <option value="auto" <?php if ($filter_user_data['filter_by_nature'] == 'auto') {?> selected <?php }?>>Auto Order</option>
                        <option value="manual" <?php if ($filter_user_data['filter_by_nature'] == 'manual') {?> selected <?php }?>>Manual Order</option>
                        </select>
                    </div>
                    </div>

                <!-- Exchange Filter //Umer Abbas [1-11-19] -->
                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                    <div class="Input_text_s">
                        <label>Filter Exchange: </label>
                        <select id="filter_by_exchange" name="filter_by_exchange" type="text" class="form-control filter_by_name_margin_bottom_sm">
                            <option value="">Search By Exchange</option>
                            <option value="bam"<?php if ($filter_user_data['filter_by_exchange'] == 'bam') {?> selected <?php }?>   >Bam</option>
                            <option value="binance"<?php if ($filter_user_data['filter_by_exchange'] == 'binance') {?> selected <?php }?>>Binance</option>
                            <option value="kraken"<?php if ($filter_user_data['filter_by_exchange'] == 'kraken') {?> selected <?php }if (!in_array($filter_user_data['filter_by_exchange'], ['binance', 'bam', 'kraken'])) {echo 'selected';}
?>>Kraken</option>
                            <!-- <option value="coinbasepro" selected>Coinbase pro</option> -->
                        </select>
                    </div>
                </div>


                <!-- Multi-select status filter //Umer Abbas [12-11-19] -->
                <?php $temp_filter_by_status = (!empty($filter_user_data['filter_by_status']) ? $filter_user_data['filter_by_status'] : array());?>
                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                    <div class="Input_text_s">
                        <label>Filter Status: </label>
                        <select id="filter_by_status" name="filter_by_status[]" multiple type="text" class="form-control filter_by_name_margin_bottom_sm">
                            <option value="new"<?php echo (in_array('new', $temp_filter_by_status) ? ' selected ' : ''); ?>>New</option>
                            <option value="open"<?php echo (in_array('open', $temp_filter_by_status) ? ' selected ' : ''); ?>>Open</option>
                            <option value="submitted_for_sell"<?php echo (in_array('submitted_for_sell', $temp_filter_by_status) ? ' selected ' : ''); ?>>submitted for sell</option>
                            <option value="fraction_submitted_sell"<?php echo (in_array('fraction_submitted_sell', $temp_filter_by_status) ? ' selected ' : ''); ?>>fraction submitted sell</option>
                            <option value="error"<?php echo (in_array('error', $temp_filter_by_status) ? ' selected ' : ''); ?>>Error</option>
                            <option value="errors_tab"<?php echo (in_array('errors_tab', $temp_filter_by_status) ? ' selected ' : ''); ?>>Errors Tab</option>
                            <option value="sold"<?php echo (in_array('sold', $temp_filter_by_status) ? ' selected ' : ''); ?>>Sold</option>
                            <option value="sold_manually"<?php echo (in_array('sold_manually', $temp_filter_by_status) ? ' selected ' : ''); ?>>Sold manually</option>
                            <option value="LTH_sold"<?php echo (in_array('LTH_sold', $temp_filter_by_status) ? ' selected ' : ''); ?>>LTH Sold</option>
                            <option value="LTH"<?php echo (in_array('LTH', $temp_filter_by_status) ? ' selected ' : ''); ?>>LTH</option>
                            <option value="lth_pause"<?php echo (in_array('lth_pause', $temp_filter_by_status) ? ' selected ' : ''); ?>>LTH Paused</option>
                            <option value="takingparent"<?php echo (in_array('takingparent', $temp_filter_by_status) ? ' selected ' : ''); ?>>Parent Taking Trades</option>
                            <option value="takeparent"<?php echo (in_array('takeparent', $temp_filter_by_status) ? ' selected ' : ''); ?>>Parent To Take Trade</option>
                            <option value="play"<?php echo (in_array('play', $temp_filter_by_status) ? ' selected ' : ''); ?>>Active Parent</option>
                            <option value="pause"<?php echo (in_array('pause', $temp_filter_by_status) ? ' selected ' : ''); ?>>Inactive Parent</option>
                            <option value="pick_parent_yes"<?php echo (in_array('pick_parent_yes', $temp_filter_by_status) ? ' selected ' : ''); ?>>Pick Parent Yes</option>
                            <option value="pick_parent_no"<?php echo (in_array('pick_parent_no', $temp_filter_by_status) ? ' selected ' : ''); ?>>Pick Parent No</option>
                            <option value="canceled"<?php echo (in_array('canceled', $temp_filter_by_status) ? ' selected ' : ''); ?>>Canceled</option>
                            <option value="error_in_sell"<?php echo (in_array('error_in_sell', $temp_filter_by_status) ? ' selected ' : ''); ?>>Error In Sell</option>
                            <option value="new_ERROR"<?php echo (in_array('new_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>new ERROR</option>
                            <option value="FILLED_ERROR"<?php echo (in_array('FILLED_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>FILLED ERROR</option>
                            <option value="submitted_ERROR"<?php echo (in_array('submitted_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>submitted ERROR</option>
                            <option value="SELL_ID_ERROR"<?php echo (in_array('SELL_ID_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>SELL ID ERROR</option>
                            <option value="LTH_ERROR"<?php echo (in_array('LTH_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>LTH ERROR</option>
                            <option value="canceled_ERROR"<?php echo (in_array('canceled_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>canceled ERROR</option>
                            <option value="credentials_ERROR"<?php echo (in_array('credentials_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>credentials ERROR</option>
                            <option value="fraction_ERROR"<?php echo (in_array('fraction_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>fraction ERROR</option>
                            <option value="resume_paused"<?php echo (in_array('resume_paused', $temp_filter_by_status) ? ' selected ' : ''); ?>>Paused</option>
                            <option value="resumed"<?php echo (in_array('resumed', $temp_filter_by_status) ? ' selected ' : ''); ?>>Resumed</option>
                            <option value="resume_in_progress"<?php echo (in_array('resume_in_progress', $temp_filter_by_status) ? ' selected ' : ''); ?>>Resume (In progress)</option>
                            <option value="resume_completed"<?php echo (in_array('resume_completed', $temp_filter_by_status) ? ' selected ' : ''); ?>>Resume Completed</option>
                            <option value="resume_child_sold"<?php echo (in_array('resume_child_sold', $temp_filter_by_status) ? ' selected ' : ''); ?>>Resume Childs (open)</option>
                            <option value="quantity_issue"<?php echo (in_array('quantity_issue', $temp_filter_by_status) ? ' selected ' : ''); ?>>Quantity Issue</option>
                            <option value="cost_average"<?php echo (in_array('cost_average', $temp_filter_by_status) ? ' selected ' : ''); ?>>Cost Average</option>

                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">

                    <div class="Input_text_s">

                        <label>Filter Username: </label>

                        <input type="text" class="form-control" name="filter_username" id="username" value="<?=(!empty($filter_user_data['filter_username']) ? $filter_user_data['filter_username'] : "")?>">

                    </div>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                    <div class="Input_text_s">
                        <label>Filter Opportunity Id: </label>
                        <input type="text" class="form-control" name="opportunityId" id="opportunityId" value="<?=(!empty($filter_user_data['opportunityId']) ? $filter_user_data['opportunityId'] : "")?>">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                    <div class="Input_text_s">
                        <label>Show Parents:</label>
                        <input id="box2" class="form-control" name="show_parents" value="yes" type="checkbox" <?=((!empty($filter_user_data['show_parents']) && $filter_user_data['show_parents'] == "yes") ? "checked" : "")?> />
                        <label class="" for="box2"></label>
                    </div>
                </div>


                <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">

                    <div class="Input_text_s">

                        <label>Sort Field: </label>

                        <div class="af-radio-group">

                            <input class="af-cust-radio" value="created_date" name="optradio" type="radio" id="option-one" <?php if ($filter_user_data['optradio'] == 'created_date') {?> checked <?php }?> <?=(empty($filter_user_data['optradio']) ? ' checked ' : '')?>>

                            <label class="af-custom-radio-label" for="option-one">Created Date</label>

                            <input class="af-cust-radio" value="modified_date" name="optradio" type="radio" id="option-two" <?php if ($filter_user_data['optradio'] == 'modified_date') {?> checked <?php }?>>

                            <label class="af-custom-radio-label" for="option-two">Modified Date</label>

                        </div>

                        <div class="af-radio-group af-form-group-created">

                            <input class="af-cust-radio" value="ASC" type="radio" id="option-one2" name="selector" <?php if ($filter_user_data['selector'] == 'ASC') {?> checked <?php }?>>

                            <label class="af-custom-radio-label" for="option-one2">ASC</label>

                            <input class="af-cust-radio" value="DESC" type="radio" id="option-two2" name="selector" <?php if ($filter_user_data['selector'] == 'DESC') {?> checked <?php }?> <?=(empty($filter_user_data['selector']) ? ' checked ' : '')?>>

                            <label class="af-custom-radio-label" for="option-two2">DESC</label>

                        </div>

                    </div>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-12" style="padding-bottom: 6px;">

                    <div class="Input_text_btn">

                        <label></label>

                        <button id="submit-form" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>

                        <a href="<?php echo SURL; ?>admin/order_report_test/reset_filters_report/all" class="btn btn-danger"><i class="fa fa-times-circle"></i>Reset</a>

                        <!-- <span style="float:right;"><button class="btn btn-info" onclick="exportTableToCSV('report.csv')">Export To CSV File</button></span> -->

                        <!-- <div style="padding-top: 20px;">
                            <pre>
                                <?php //echo "<code>".$json."</code>" ?>
                            </pre>
                        </div> -->

                    </div>

                </div>
                </div>

                </form>
            </div>

            <!-- clone above form with values to use in csv creation  -->
            <div id="csv_report_filter_form" style="display:none;">
            </div>

          </div>

      </div>

    <!-- Widget -->

    <div class="widget widget-inverse">



      <div class="widget-body padding-bottom-none">

      <div>
            <button class="btn btn-primary btn-sm pull-right" onclick="create_csv_report()">Export CSV</button>
      </div>

      	<div class="table-responsive">
            <!-- Table -->
        <table id="example" class="table table-bordered" cellspacing="0" width="100%">

<!-- Table heading -->
<thead>
  <tr>
    <th class="text-center"></th>
    <th class="text-center"><strong>Coin</strong></th>
    <th class="text-center"><strong>Price</strong></th>
    <th class="text-center"><strong>Order Type</strong></th>
    <th class="text-center"><strong>Level</strong></th>
    <th class="text-center"><strong>Quantity</strong></th>
    <th class="text-center"><strong>Usd Worth</strong></th>
    <th class="text-center"><strong>Created Date</strong></th>
    <th class="text-center"><strong>Last Modified Date</strong></th>
    <th class="text-center"><strong>P/L</strong></th>
    <th class="text-center"><strong>Market(%)</strong></th>
    <th class="text-center"><strong>Status</strong></th>
    <th class="text-center"><strong>P/L(%)</strong></th>
    <th class="text-center"><strong>Target Profit(%)</strong></th>
    <th class="text-center"><strong>Slippage(%)</strong></th>
    <th class="text-center"><strong>LTH</strong></th>
    <th class="text-center"><strong>LTH Profit(%)</strong></th>
    <th class="text-center"><strong>Stop Loss(%)</strong></th>
    <th class="text-center"><strong>Sub Status</strong></th>
    <th class="text-center"><strong>Max(%) / Min(%)</strong></th>
    <th class="text-center"><strong>5hMax(%) / 5hMin(%)</strong></th>
    <th class="text-center"><strong>User Info</strong></th>
    <th class="text-center"><strong>User IP</strong></th>
    <!-- <th class="text-center" style="min-width: 120px"><strong>Buy Rules</strong></th>             -->
    <th class="text-center"><strong>Actions</strong></th>
  </tr>
</thead>

<tbody class="af-table-body">
      <?php
$errArr = ['error', 'new_ERROR', 'FILLED_ERROR', 'submitted_ERROR', 'LTH_ERROR', 'canceled_ERROR', 'credentials_ERROR', 'fraction_ERROR', 'SELL_ID_ERROR'];
$nanArr = ['nan', 'Nan', 'NAN', 'inf', 'Inf', 'INF', ''];
$order_exchange = 'binance';
if (!empty($filter_user_data['filter_by_exchange'])) {
    $order_exchange = $filter_user_data['filter_by_exchange'];
}

$BTCUSDTmarket_value = $this->mod_dashboard->get_market_value2('BTCUSDT', $order_exchange);

$pricesArr = get_current_market_prices($order_exchange, []);

if (count($orders) > 0) {
    foreach ($orders as $key => $value) {
        if (!empty($value)) {?>
            <?php

            // $market_value = $this->mod_dashboard->get_market_value2($value['symbol'], $order_exchange);
            $symbol = $value['symbol'];
            $market_value = $pricesArr[$symbol];

            // $logo = $this->mod_coins->get_coin_logo($value['symbol']);
            if ($value['status'] != 'new') {
                if ($value['status'] == 'FILLED') {
                    $market_value333 = num($value['purchased_price']);
                } else {
                    $market_value333 = num($value['market_value']);
                }
            } else {
                $market_value333 = num($market_value);
            }
            if ($value['status'] == 'new') {
                $current_order_price = num($value['price']);
            } else {
                if ($value['status'] == 'FILLED') {
                    $market_value333 = num($value['purchased_price']);
                } else {
                    $market_value333 = num($value['market_value']);
                }
            }

            if ($value['status'] == 'canceled' && (empty($value['parent_status']) || $value['parent_status'] != 'parent')) {
                $market_value333 = !empty($value['purchased_price']) ? num($value['purchased_price']) : num($value['price']);
            }

            $current_data = $market_value333 - $current_order_price;
            $market_data = ($current_data * 100 / $market_value333);
            $market_data = number_format((float) $market_data, 2, '.', '');
            if ($market_value333 > $current_order_price) {
                $class = 'success';
            } else {
                $class = 'danger';
            }
            ?>
            <tr class="gradeX">
            <td class="text-center">
                <?=++$serial_number;?>
            </td>
            <td class="text-center">
            <div class="fill_style_toplft">
            <?php
if ($value['duplicate_buy'] == 'yes') {
                echo 'DB';
            }

            if ($value['duplicate_sell'] == 'yes') {
                echo 'DS';
            }
            ?>
            </div>
            <?php echo $value['symbol'] ?>
            </td>

            <td class="text-center">
                <?=(isset($value['purchased_price']) ? num($value['purchased_price']) : num($value['price']));?>
            </td>

            <td class="text-center">
                <?php
if ($value['trigger_type'] != 'no') {
                if ($value['trigger_type'] == 'barrier_percentile_trigger') {
                    echo 'BPT';
                } else {
                    echo strtoupper(str_replace('_', ' ', $value['trigger_type']));
                }
            } else {
                echo 'MANUAL ORDER';
            }
            ?>
            </td>

            <td class="text-center"><?=$value['order_level'];?></td>
            <td class="text-center"><?=$value['quantity']?></td>
            <td class="text-center">
                <?php
$usd_worth_tt = get_usd_worth($value['symbol'], $value['quantity'], $market_value333, $BTCUSDTmarket_value);
            echo '$' . $usd_worth_tt;
            ?>
            </td>

            <td class="text-center">
                <a data-toggle="tooltip" data-placement="top" title="<?=$value['created_date_hover'];?>">
                    <?php echo (!empty($value['created_date']) ? "<span class='label label-success'>" . $value['created_date'] . "</span>" : ''); ?>
                </a>
            </td>

            <td class="text-center">
                <a data-toggle="tooltip" data-placement="top" title="<?=$value['modified_date_hover'];?>">
                    <?php echo (!empty($value['modified_date']) ? "<span class='label label-success'>" . $value['modified_date'] . "</span>" : ''); ?>
                </a>
            </td>

            <td class="text-center">
                <?php //echo num($market_value333); ?>
                <?php
if ($value['is_sell_order'] == 'sold') {
                echo num($value['market_sold_price']);
            } else {
                echo num($market_value);
            }
            ?>
            </td>


            <td class="text-center">
                <?php if ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes' && $value['status'] != 'new' && !in_array($market_data, $nanArr)) {?>
                    <?php //echo '<span class="text-'.$class.'"><b>'.$market_data.'%</b></span>';?>
                    <span class="text-default"><b>-</b></span>
                <?php } else {?>
                    <span class="text-default"><b>-</b></span>
                <?php }?>
            </td>

            <td class="text-center"><span class="label label-<?=in_array($value['status'], $errArr) || in_array('errors_tab', $temp_filter_by_status) ? 'danger' : 'success';?>">
            <?php

            $temp_f = false;
            if ($value['is_sell_order'] == 'yes') {
                if ($value['status'] == 'LTH') {
                    echo strtoupper("LTH OPEN");
                } elseif (in_array($value['status'], $errArr) && !in_array('errors_tab', $temp_filter_by_status)) {
                    echo strtoupper(str_replace('_', ' ', $value['status']));
                } elseif ($value['status'] == 'submitted_for_sell') {
                    echo strtoupper(str_replace('_', ' ', $value['status']));
                } elseif ($value['status'] == 'fraction_submitted_sell') {
                    echo strtoupper(str_replace('_', ' ', $value['status']));
                } elseif ($value['status'] == 'canceled') {
                    echo strtoupper(str_replace('_', ' ', $value['status']));
                } elseif ($value['status'] == 'new') {
                    echo strtoupper(str_replace('_', ' ', $value['status']));
                } else {
                    if (empty($value['status'])) {

                    } else {
                        if (!in_array('errors_tab', $temp_filter_by_status)) {
                            echo strtoupper('OPEN');
                        }
                    }
                }
            } else {
                echo strtoupper(str_replace('_', ' ', $value['status']));
                $temp_f = '11';
            }

            if (in_array('errors_tab', $temp_filter_by_status) && $temp_f != '11') {
                echo strtoupper(str_replace('_', ' ', $value['status']));
            }

            ?>
            </span>
            <?php if ($value['parent_status'] == 'parent') {
                echo '<span class="label label-warning">Parent Order</span>';
            }?>
            <span class="custom_refresh" data-id="<?php echo $value['_id']; ?>" order_id="<?php echo $value['binance_order_id']; ?>"> <i class="fa fa-refresh" aria-hidden="true"></i> </span></td>
            <td class="text-center">
                <?php
if (!empty($value['resume_order_arr_total'])) {
                if ($value['resume_order_arr_total'] > 0) {
                    $class222 = 'success';
                } else {
                    $class222 = 'danger';
                }
                echo '<span class="text-' . $class222 . '"> <b> ' . $value['resume_order_arr_total'] . '%</b> </span>';
            } else {
                if ($value['market_sold_price'] != "") {
                    $market_sold_price = num($value['market_sold_price']);
                    $pp = num($value['purchased_price']);
                    $profitPercentage = calculateProfitPercentage($pp, $market_sold_price);
                    if ($market_sold_price > $pp) {
                        $class222 = 'success';
                    } else {
                        $class222 = 'danger';
                    }
                    echo '<span class="text-' . $class222 . '"> <b> ' . $profitPercentage . '%</b> </span>';
                } else {
                    if ($value['status'] == 'FILLED' || $value['status'] == 'LTH') {
                        if ($value['is_sell_order'] == 'yes') {
                            $pp = num($value['purchased_price']);
                            $profitPercentage = calculateProfitPercentage($pp, $market_value);
                            if ($market_value > $pp) {
                                $class = 'success';
                            } else {
                                $class = 'danger';
                            }
                            echo '<span class="text-' . $class . '"><b>' . $profitPercentage . '%</b></span>';
                        } else {
                            echo '<span class="text-default"><b>-</b></span>';
                        }
                    } else {
                        echo '<span class="text-default"><b>-</b></span>';
                    }
                }
            }
            ?>
            </td>
            <td class="text-center">
                <?php
if ($value['trigger_type'] == 'no' && $value['status'] == 'LTH') {
                $target_profit = $value['lth_profit'];
            }

            if ($value['trigger_type'] == 'no' && $value['status'] == 'FILLED') {
                $target_profit = $value['sell_profit_percent'];
            }

            if ($value['trigger_type'] != 'no' && $value['status'] == 'LTH') {
                $target_profit = $value['lth_profit'];
            }

            if ($value['trigger_type'] != 'no' && $value['status'] == 'FILLED') {
                $target_profit = $value['defined_sell_percentage'];
            }

            if ($value['parent_status'] == 'parent') {
                $target_profit = $value['defined_sell_percentage'];
            }
            ?>
                <?php echo (!in_array($target_profit, $nanArr) ? $target_profit : '-'); ?>
            </td>

            <td class="text-center">
                <?php
if (!in_array($target_profit, $nanArr) && !in_array($profitPercentage, $nanArr) && $value['is_sell_order'] == 'sold') {

                if ($value['is_lth_order'] == 'yes' && !in_array($value['lth_profit'], $nanArr)) {
                    $target_profit = $value['lth_profit'];
                }

                if ($profitPercentage >= $target_profit) {
                    $class = 'success';
                    $slippage = ($profitPercentage - $target_profit);
                    $slippage = round($slippage, 3) . '%';
                } else if ($profitPercentage < $target_profit) {
                    $class = 'danger';
                    $slippage = ($target_profit - $profitPercentage);
                    $slippage = round($slippage, 3) . '%';
                } else {
                    $class = 'default';
                    $slippage = '-';
                }
            } else {
                $class = 'default';
                $slippage = '-';
            }
            echo '<span class="text-' . $class . '"><b>' . $slippage . '</b></span>';
            ?>
            </td>

            <td class="text-center"><?php
if ($value['status'] == 'LTH') {
                ?>
                            <button class="btn btn-warning" style="font-size:12px">LTH</button>
                        <?php
} else {
                ?>

                        <?php
if ($value['is_sell_order'] == 'sold' && $value['is_lth_order'] == 'yes') {
                    echo '<button class="btn btn-warning" style="font-size:12px">LTH</button>';
                } else {
                    echo '<button class="btn btn-success" style="font-size:12px">Normal</button>';
                }
                ?>
                        <?php
}
            ?></td>

            <td class="text-center">
                <?php echo ($value['lth_profit'] != '' && !in_array(trim($value['lth_profit']), $nanArr) ? $value['lth_profit'] : '-'); ?>
            </td>

            <td class="text-center">
                <span class="text-<?php echo $value['iniatial_trail_stop'] > $value['purchased_price'] ? 'success' : 'danger'; ?>">
                    <?php
if ($value['loss_percentage'] != '' && !in_array(trim($value['loss_percentage']), $nanArr)) {
                $loss_percentage_temp = $value['loss_percentage'];
                $loss_percentage_temp .= $value['iniatial_trail_stop'] > $value['purchased_price'] ? " (+)" : " (-)";
            } else {
                $loss_percentage_temp = '-';
            }
            ?>
                    <?php echo $loss_percentage_temp; ?>
                    <?php //echo ($value['loss_percentage'] != '' && !in_array(trim($value['loss_percentage']), $nanArr) ? $value['loss_percentage'] : '-');?>
                </span>
            </td>

            <td class="text-center">
                <div class="btn-group btn-group-xs ">
                <?php
//Check for error
            if (in_array($value['status'], $errArr)) {

                echo '<span class="label label-danger">' . str_replace('_', ' ', $value['status']) . '</span>';
                echo '<button class="btn btn-sm btn-warning change_order_error_status" onclick="remove_order_error(this,\'' . $value['_id'] . '\',\'' . $order_exchange . '\')">Remove Error</button>';
            } else {
                if ($value['status'] == 'FILLED') {
                    if ($value['is_sell_order'] == 'yes') {
                        if (!empty($value['sell_order_id'])) {
                            $sell_status = $this->mod_buy_orders->is_sell_order_in_error_status($value['sell_order_id']);
                            $sell_status_submit = $this->mod_buy_orders->is_sell_order_in_submitted_status($value['sell_order_id']);
                        } else {
                            $sell_status = '';
                            $sell_status_submit = '';
                        }
                        // if ($value['status'] == 'error') {echo '<span class="label label-danger">ERROR IN BUY</span>';}
                        if ($sell_status) {
                            // echo '<span class="label label-danger">ERROR IN SELL</span>';
                            // echo '<button class="btn btn-sm btn-warning change_error_status" title="Update Error" data-id="' . $value['_id'] . '">Remove Error</button>';
                        } elseif ($sell_status_submit) {
                            echo ' <span class="label label-success">SUBMITTED FOR SELL</span>';
                        } else {
                            echo ' <span class="label label-info">WAITING FOR SELL</span>';
                            echo ' <button class="btn btn-danger" onclick="sell_order_now(this,\'' . $value['_id'] . '\',\'' . $order_exchange . '\')">Sell Now</button>';

                            // echo '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num((float) $market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '" order_type="' . $value['order_type'] . '"  buy_order_id="' . $value['_id'] . '">Sell Now</button>';
                        }
                    } elseif ($value['is_sell_order'] == 'sold') {
                        echo ' <button class="btn btn-success">Sold</button> ' . ($value['is_manual_sold'] == 'yes' ? '<span class="label label-info">Sold Manually</span>' : '');
                    }
                } elseif ($value['status'] == 'LTH') {
                    if ($sell_status) {
                        echo '<span class="label label-danger">ERROR IN SELL' . $sell_status . '</span>';
                        echo '<button class="btn btn-sm btn-warning change_error_status" title="Update Error" data-id="' . $value['_id'] . '">Remove Error</button>';
                    } elseif ($sell_status_submit) {
                        echo '<span class="label label-success">SUBMITTED FOR SELL</span>';
                    } else {
                        echo '<span class="label label-info">WAITING FOR SELL</span>';
                        echo '<button class="btn btn-danger" onclick="sell_order_now(this,\'' . $value['_id'] . '\',\'' . $order_exchange . '\')">Sell Now</button>';

                        // echo '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num((float) $market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '" order_type="' . $value['order_type'] . '"  buy_order_id="' . $value['_id'] . '">Sell Now</button>';
                    }
                }

                if ($value['resume_status'] == 'completed') {

                    echo ' <span class="label label-success">Resume Completed</span>';

                } else if ($value['status'] == 'FILLED' && ($value['is_sell_order'] == 'yes' || $value['is_sell_order'] == 'sold') && !empty($value['resume_order_id'])) {

                    echo ' <span class="label label-success">Resume Child</span>';

                } else if ($value['is_sell_order'] == 'resume_pause' && empty($value['resume_order_arr'])) {

                    echo ' <span class="label label-warning">Resumed</span>';

                } else if ($value['is_sell_order'] == 'resume_pause' && !empty($value['resume_order_arr'])) {

                    echo ' <span class="label label-info">In Progress</span>';

                } else if ($value['is_sell_order'] == 'pause') {

                    echo ' <span class="label label-success">Paused</span>';

                }

                if ($value['mapped_order'] == 'yes') {
                    echo ' <span class="label label-warning">Mapped</span>';
                }

                if ($value['script_fix'] != '' && $value['script_fix'] == 1) {
                    echo ' <span class="label label-warning">Script Fixed</span>';
                } else if ($value['script_fix'] != '' && $value['script_fix'] == 0 && $logged_id_user_id == '5c0915befc9aadaac61dd1b8') {
                    echo ' <span class="label label-warning">Script Ignorded</span>';
                }
            }
            if ($value['quantity_issue'] != '') {
                echo ' <span class="label label-warning">Quantity Issue</span>';
            }
            if ($value['cost_avg'] == 'yes') {
                echo ' <span class="label label-warning">Cost Avg (Yes)</span>';
            }
            if ($value['cost_avg'] == 'completed') {
                echo ' <span class="label label-warning">Cost Avg (Completed)</span>';
            }
            if ($value['cost_avg'] == 'taking_child') {
                echo ' <span class="label label-warning">Cost Avg (Taking Child)</span>';
            }
            if ($value['pick_parent'] == 'yes') {
                echo ' <span class="label label-success">Pick Parent (Yes)</span>';
            }
            if ($value['pick_parent'] == 'no') {
                echo ' <span class="label label-warning">Pick Parent (No)</span>';
            }
            ?>
                </div>
            </td>
            <td class="text-center">  <?php
if (isset($value['market_heighest_value']) && $value['market_heighest_value'] != '') {
                $five_hour_max_market_price = $value['market_heighest_value'];
                $purchased_price = (float) $value['purchased_price'];
                $profit = $five_hour_max_market_price - $purchased_price;
                $profit_margin = ($profit / $five_hour_max_market_price) * 100;
                $profit_per = ($profit) * (100 / $purchased_price);
                if ($profit > 0) {
                    $color = 'success';
                    $word = "Profit";
                } elseif ($profit < 0) {
                    $color = 'danger';
                    $word = 'Loss';
                }
                ?>
                <span style="font-size: 10px" class="text-<?php echo $color; ?>"><b><?php echo number_format($profit_per, 2); ?>%</b></span>
                <?php
} else {echo "-";}
            ?>
                |
                <?php
if (isset($value['market_lowest_value']) && $value['market_lowest_value'] != '') {
                $market_lowest_value = $value['market_lowest_value'];
                $purchased_price = (float) $value['purchased_price'];
                $profit = $market_lowest_value - $purchased_price;
                $profit_margin = ($profit / $market_lowest_value) * 100;
                $profit_per = ($profit) * (100 / $purchased_price);
                if ($profit > 0) {
                    $color = 'success';
                    $word = "Profit";
                } elseif ($profit < 0) {
                    $color = 'danger';
                    $word = 'Loss';
                }
                ?>
                <span style="font-size: 10px" class="text-<?php echo $color; ?>"><b><?php echo number_format($profit_per, 2); ?>%</b></span>
                <?php
} else {echo "-";}
            ?></td>
            <td class="text-center"><?php
if (isset($value['5_hour_max_market_price']) && $value['5_hour_max_market_price'] != '') {
                $five_hour_max_market_price = $value['5_hour_max_market_price'];
                $market_sold_price = (float) $value['market_sold_price'];
                $profit = $five_hour_max_market_price - $market_sold_price;
                $profit_margin = ($profit / $five_hour_max_market_price) * 100;
                $profit_per = ($profit) * (100 / $market_sold_price);
                if ($profit > 0) {
                    $color = 'success';
                    $word = "Profit";
                } elseif ($profit < 0) {
                    $color = 'danger';
                    $word = 'Loss';
                }
                ?>
            <span style="font-size: 10px" class="text-<?php echo $color; ?>"><b><?php echo number_format($profit_per, 2); ?>%</b></span>
            <?php
} else {echo "-";}
            ?>
             |
             <?php
if (isset($value['5_hour_min_market_price']) && $value['5_hour_min_market_price'] != '') {
                $market_lowest_value = $value['5_hour_min_market_price'];
                $market_sold_price = (float) $value['market_sold_price'];
                $profit = $market_lowest_value - $market_sold_price;
                $profit_margin = ($profit / $market_lowest_value) * 100;
                $profit_per = ($profit) * (100 / $market_sold_price);
                if ($profit > 0) {
                    $color = 'success';
                    $word = "Profit";
                } elseif ($profit < 0) {
                    $color = 'danger';
                    $word = 'Loss';
                }
                ?>
            <span style="font-size: 10px" class="text-<?php echo $color; ?>"><b><?php echo number_format($profit_per, 2); ?>%</b></span>
            <?php
} else {echo "-";}?></td>
            <td class="text-center"><?=$value['admin']->username;?></td>
            <td class="text-center"><?=$value['admin']->trading_ip;?></td>
            <!-- <td><?="Buy Rule: <span class='badge badge-info'>" . $value['buy_rule_number'] . "</span> <br> Buy Via : <span class='badge badge-danger'>" . (($value['is_manual_buy'] == 'yes') ? "Manual" : "Auto") . " </span>";?></td>
              <td><?="Sell Rule: <span class='badge badge-info'>" . $value['sell_rule_number'] . "</span> <br> Sell Via: <span class='badge badge-danger'>" . (($value['is_manual_sold'] == 'yes') ? "Manual" : "Auto") . " </span>";?><br>
                <small>if Sell Rule is 0 the its sold by Stop Loss</small>
              </td>             -->


            <td class="center">

             <button class="btn btn-xs btn-warning <?php echo ($filter_user_data['filter_by_exchange'] != 'binance' ? 'view_order_details_exchange' : 'view_order_details'); ?>"  title="View Order Details" data-id="<?php echo $value['_id']; ?>"><i class="fa fa-eye"></i></button>

             <button type="button" class="btn btn-xs btn-primary viewadmininfo" data-toggle="modal" data-target="#largeShoes" id="<?php echo $value['admin_id']; ?>"><i class="fa fa-eye"></i></button>

            <?php
if ($value['status'] == 'error') {
                ?>
                <!-- <a href="<?=SURL;?>admin/buy_orders/edit-buy-order/<?=$value['_id'];?>" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-link"></i></a> -->
                <?php
} else {
                ?>
                    <!-- <a href="<?=SURL;?>admin/sell_orders/edit-order/<?=$value['sell_order_id'];?>" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-link"></i></a> -->
                <?php
}
            ?>

            <?php if ($value['trigger_type'] != 'no' && $value['parent_status'] != 'parent') {?>
                <!-- <a href="<?=SURL;?>admin/buy_orders/edit-buy-trigger-order/<?=$value['buy_parent_id'];?>" target="_blank" class="btn btn-xs btn-warning"><i class="fa fa-external-link"></i></a> -->
            <?php } else if ($value['trigger_type'] == 'no') {?>
                <!-- <a href="<?=SURL;?>admin/sell_orders/edit-order/<?=$value['sell_order_id'];?>" target="_blank" class="btn btn-xs btn-warning"><i class="fa fa-external-link"></i></a> -->
            <?php } else {?>
                <!-- <a href="<?=SURL;?>admin/buy_orders/edit-buy-trigger-order/<?=$value['buy_parent_id'];?>" target="_blank" class="btn btn-xs btn-warning"><i class="fa fa-external-link"></i></a> -->
            <?php }?>

            <!-- https://trading.digiebot.com Detail page links  -->
            <?php if ($value['trigger_type'] == 'no') {?>
                <!-- Edit Auto Order -->
                <a href="https://trading.digiebot.com/edit-buy-manual-order/<?=$value['_id'];?>" target="_blank" title="Edit Manual Order" class="btn btn-xs btn-warning"><i class="fa fa-external-link"></i></a>
            <?php }?>

            <?php if ($value['trigger_type'] != 'no') {?>
                <?php if ($value['parent_status'] == 'parent') {?>
                    <!-- Edit Parent Order -->
                    <a href="https://trading.digiebot.com/edit-parent-order/<?=$value['_id'];?>" target="_blank" title="Edit Parent Order" class="btn btn-xs btn-warning"><i class="fa fa-external-link"></i></a>
                <?php } else {?>
                    <!-- Edit Auto Order -->
                    <a href="https://trading.digiebot.com/edit-auto-order/<?=$value['_id'];?>" target="_blank" title="Edit Auto Order" class="btn btn-xs btn-warning"><i class="fa fa-external-link"></i></a>
                <?php }?>
            <?php }?>



            <?php if (!empty($value['buy_parent_id'])) {?>
                <!-- Edit Parent Order -->
                <a href="https://trading.digiebot.com/edit-parent-order/<?=$value['buy_parent_id'];?>" target="_blank" title="Edit Parent Order" class="btn btn-xs btn-warning"><i class="fa fa-external-link"></i></a>
            <?php }?>
            <!-- End https://trading.digiebot.com Detail page links  -->

        <!-- // added by 1/8/19 -->
        <!-- <a href="#" target="_blank" class="btn btn-xs btn-warning"><i class="fa fa-external-link"></i></a> -->
        <!-- // added by 1/8/19 -->
        </td>
        </tr>
      <?php }
    }
}?>
   </tbody>

</table>
<!-- // Table END -->

        </div>
        <div class="row bottom-box">
    <div class="pagination" style="margin-bottom: 0px;">
    <?php echo $pagination; ?>
    </div>
    <div class="total-count">
<span style="
    padding: 12px;
    font-size: 15px;
    font-weight: bolder;
    color: red;
">
Total Count:
</span>
<span>
<?php echo $total_count; ?>
</span></div>
</div>
      </div>

    </div>

    <!-- // Widget END -->



  </div>

</div>


<style>
.bottom-box {
    width: 100%;
}

.total-count {
    padding-top: 0px;
    margin-bottom: 3%;
    position: relative;
    top: 0;
    width: 12%;
    text-align: left;
    padding: 15px;
    background: aquamarine;
    display: block;
    border: 2px solid #676767;
    border-radius: 25px;
}
</style>
<!-- The modal -->

<div class="modal" id="largeShoes" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">

   <div class="modal-dialog modal-sm">

      <div class="modal-content">

         <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

            </button>

            <h4 class="modal-title" id="modalLabelLarge">User Information</h4>

         </div>

         <div class="modal-body" id="mymodalresp">

            Modal content...

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



<!-- Start Model -->

<div class="modal fade in" id="modal-order_update" aria-hidden="false">

  <div class="modal-dialog">

    <div class="modal-content">



      <!-- Modal heading -->

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

        <h3 class="modal-title">Order Update</h3>

      </div>

      <!-- // Modal heading END -->



      <!-- Modal body -->

      <div class="modal-body">

        <div class="innerAll">

          <div class="innerLR" id="response_order_update"> </div>

        </div>

      </div>

      <!-- // Modal body END -->



    </div>

  </div>

</div>

<!-- End Model -->
<script>
$(function() {

$("#example").find("tr").each(function (index, element) {

    // $("td").filter(function() {
    //     return $(this).text() === "SELL";
    // }).parent().addClass("failed");
    // $("td").filter(function() {
    //     return $(this).text() === "BUY";
    // }).parent().addClass("success-custom");

    // console.log(element);

    var profit = jQuery(element).find("td:eq(12)").text();
    var target = jQuery(element).find("td:eq(13)").text();
    var status = jQuery(element).find("td:eq(18)").text();
    var percentage = profit.split("%")
    var per = percentage[0];
    var current_profit = parseFloat(per);
    var target_profit = parseFloat(target);
    // console.log("status" , status , current_profit, "<============>" , target_profit);
    status = $.trim(status)
    // if((typeof(current_profit) !== 'undefined' || typeof(target_profit) !== 'undefined') && status != 'Sold'){
    if((typeof(current_profit) !== 'undefined' || typeof(target_profit) !== 'undefined') && !status.includes('Sold')){
        if(current_profit > target_profit){
            // console.log(current_profit, "<============>" , target_profit);
            $(element).addClass("failed")
        }
    }
});

$(document).ready(function() {
			//Only needed for the filename of export files.
			//Normally set in the title tag of your page.document.title = 'Simple DataTable';
			//Define hidden columns
			var hCols = [3, 4];
			// DataTable initialisation
			$('#example').DataTable({
                // "order": [],
                "ordering": false,
                "paging": false,
				"dom": "<'row'<'col-sm-4'B><'col-sm-2'l><'col-sm-6'p<br/>i>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12'p<br/>i>>",
				"buttons": [{
					extend: 'colvis',
					collectionLayout: 'three-column',
					text: function() {
						var totCols = $('#example thead th').length;
						var hiddenCols = hCols.length;
						var shownCols = totCols - hiddenCols;
						return 'Columns (' + shownCols + ' of ' + totCols + ')';
					},
					prefixButtons: [{
						extend: 'colvisGroup',
						text: 'Show all',
						show: ':hidden'
					}, {
						extend: 'colvisRestore',
						text: 'Restore'
					}]
				}, {
					extend: 'collection',
					text: 'Export',
					buttons: [{
							text: 'Excel',
							extend: 'excelHtml5',
							footer: false,
							exportOptions: {
								columns: ':visible'
							}
						}, {
							text: 'CSV',
							extend: 'csvHtml5',
							fieldSeparator: ';',
							exportOptions: {
								columns: ':visible'
							}
						}, {
							text: 'PDF Portrait',
							extend: 'pdfHtml5',
							message: '',
							exportOptions: {
								columns: ':visible'
							}
						}, {
							text: 'PDF Landscape',
							extend: 'pdfHtml5',
							message: '',
							orientation: 'landscape',
							exportOptions: {
								columns: ':visible'
							}
						}]
					}]
				,oLanguage: {
            oPaginate: {
                sNext: '<span class="pagination-default">&#x276f;</span>',
                sPrevious: '<span class="pagination-default">&#x276e;</span>'
            }
        }
					,"initComplete": function(settings, json) {
						// Adjust hidden columns counter text in button -->
						$('#example').on('column-visibility.dt', function(e, settings, column, state) {
							var visCols = $('#example thead tr:first th').length;
							//Below: The minus 2 because of the 2 extra buttons Show all and Restore
							var tblCols = $('.dt-button-collection li[aria-controls=example] a').length - 2;
							$('.buttons-colvis[aria-controls=example] span').html('Columns (' + visCols + ' of ' + tblCols + ')');
							e.stopPropagation();
						});
					}
				});
			});

});
</script>


<script>

$(document).ready(function(){
 $('#filter_by_status').multiselect({
  nonSelectedText: 'Select Status',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'100%'
 });
});

$(document).ready(function(){
 $('#filter_by_level').multiselect({
  nonSelectedText: 'Select Level',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'100%'
 });
});


$(document).ready(function(e){

   var query = $("#filter_by_trigger").val();

    if (query == 'barrier_trigger') {

        $("#barrier_t").show();

        $("#barrier_p_t").hide();

    }else if(query == 'barrier_percentile_trigger'){

        $("#barrier_t").hide();

        $("#barrier_p_t").show();

    }else{

      $("#barrier_t").hide();

        $("#barrier_p_t").hide();

    }

});

$("body").on("click",".glassflter",function(e){

    var query = $("#filter_by_name").val();

    window.location.href = "<?php echo SURL; ?>/admin/users/?query="+query;

});





$("body").on("change","#filter_by_trigger",function(e){

    var query = $("#filter_by_trigger").val();

    if (query == 'barrier_trigger') {

        $("#barrier_t").show();

        $("#barrier_p_t").hide();

    }else if(query == 'barrier_percentile_trigger'){

        $("#barrier_t").hide();

        $("#barrier_p_t").show();

    }else{

      $("#barrier_t").hide();

      $("#barrier_p_t").hide();

    }

});



$("body").on("click",".viewadmininfo",function(e){

    var user_id = $(this).attr('id');

    $.ajax({

      url: "<?php echo SURL; ?>admin/reports/get_user_info",

      data: {user_id:user_id},

      type: "POST",

      success: function(response){

          $("#mymodalresp").html(response);

      }

    });

});



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

$("body").on("click",".view_order_details_exchange",function(e){

    var order_id = $(this).attr("data-id");
    var exchange = "<?php echo ($filter_user_data['filter_by_exchange'] != 'binance' ? $filter_user_data['filter_by_exchange'] : ''); ?>";
    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/get_buy_order_details_exchange_ajax',
        'type': 'POST',
        'data': {
            order_id:order_id,
            exchange:exchange
        },
        'success': function (response) {

            console.log(response);
            $('#response_order_details').html(response);
            $("#modal-order_details").modal('show');
        }
    });

});

</script>

<script>

  $( function() {

    availableTags = [];

    $.ajax({

       'url': '<?php echo SURL ?>admin/reports/get_all_usernames_ajax',

       'type': 'POST',

       'data': "",

       'success': function (response) {

          availableTags = JSON.parse(response);



          $( "#username" ).autocomplete({

            source: availableTags

          });

       }

   });



  });





  //Custom switcher by Afzal

  jQuery("body").on("change","#af-swith-asc",function(){

	if(jQuery(".af-switcher-default").hasClass("active")){

		jQuery(".af-switcher-default").removeClass("active");
    jQuery(this).val("ASC");

	}

	else{

		jQuery(".af-switcher-default").addClass("active");
    jQuery(this).val("DESC");
	}

	});

	jQuery("body").on("click",".af-switcher-default",function(){

		if(jQuery(".af-switcher-default").hasClass("active")){

			jQuery(".af-switcher-default").removeClass("active");

		}

		else{

			jQuery(".af-switcher-default").addClass("active");

		}

	});

  	jQuery("body").on("change",".af-cust-radio",function(){

		jQuery(".af-form-group-created").addClass("active");

	});

  //----End--------

  </script>


<!-- added by 19/8/19 -->
<script type="text/javascript">

    $("body").on("click",".sell_now_btn",function(e){

        var id = $(this).attr('data-id');
        var market_value = $(this).attr('market_value');
        var quantity = $(this).attr('quantity');
        var symbol = $(this).attr('symbol');
        var order_type = $(this).attr('order_type');
        var buy_order_id = $(this).attr('buy_order_id');

        $("#"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

        if(order_type !='limit_order'){
        // sell_order(id,market_value,quantity,symbol);

        sell_market_order(id,market_value,quantity,symbol,buy_order_id);

        }else{
        limit_order_cancel(id,market_value,quantity,symbol,buy_order_id);
        }

    });


    //%%%%%%%%%%%%%%%%%%%%%%%5 Sell limit order %%%%%%%%%%%%%%%%%%%%%%%%%%
    function sell_market_order(sell_id,market_value,quantity,symbol,buy_order_id){

        $.ajax({
            'url':'<?php echo SURL ?>admin/dashboard/check_status_of_limit_order',
            'type':'POST',
            'data':{sell_id:sell_id,symbol:symbol},
            'success':function(response){
                var rp = JSON.parse(response);
                var resp =rp['status'];
                var current_market_price =rp['market_price'];


                $("#"+sell_id).html('Sell Now');

                    if(resp == 'error'){
                        //%%%%%%%%%%%%%%% if order is an error status%%%%%%%%%%%
                            $.confirm({
                                title: 'Attention!',
                                content: 'The order is an error status. Please Remove the error to sell this order',
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
                    //%%%%%%%%%%%%%%%End of  error status%%%%%%%%%%%
                    }else if (resp == 'submitted'){
                        //%%%%%%%%%%%%%%%%%%% submitted status %%%%%%%%%%%%%%%%%%%%%

                        $.confirm({
                            title: 'Order Status',
                            content: 'Order Already Send for sell',
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
                        //%%%%%%%%%%%%%%%%%%% End of submitted status %%%%%%%%%%%%%%%%%%%%%
                    }else if (resp == 'new' || resp == 'LTH'){

                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%5  New Status%%%%%%%%%%%%%%%%%%%%%%%%%
                        var content_html ='Select from below to sell this order\
                            <div class="">\
                            <hr>\
                            <form>\
                            <div class="form-group">\
                            <div class="row"><div class="col-xs-6">\
                            <label>Current Market Price</label>\
                            <input class="form-control" step="any" type="number" name="cu_m_price_'+sell_id+'" value="'+current_market_price+'" disabled>\
                            </div><div class="col-xs-6">\
                            <label>Sell Price</label>\
                            <input class="form-control" step=".00000001" type="number" name="sell_price_'+sell_id+'" value="'+current_market_price+'" id="sell_price_'+sell_id+'">\
                            </div>\
                            </div>'

                            // <div class="radio">\
                            // <label><input type="radio" name="typ_new_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\
                            // </div>\
                            // <div class="radio">\
                            // <label><input type="radio" name="typ_new_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\
                            // </div>

                            content_html +='<div class="radio ">\
                            <label><input type="radio" name="typ_new_'+sell_id+'" value="m_current" checked>Fire market order on current market price</label>\
                            </div>\
                            </form>\
                            </div>';

                        $.confirm({
                            title: 'Sell order conformation',
                            content: content_html,
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: 'Ok',
                                    btnClass: 'btn-red',
                                    action: function(){

                                        var order_type = $("input[name='typ_new_"+sell_id+"']:checked").val();
                                        ;
                                        var sell_price = $('#sell_price_'+sell_id).val();
                                    sell_market_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price);
                                    }
                                },
                                    close: function () {
                                }
                            }
                        });
                        //%%%%%%%%%%%%%%%%%%%%%%%End of new status %%%%%%%%%%%%%%%%%%%%%%%%
                    }


            }
        })

    }//End of sell_market_order


    function sell_market_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price){

        //%%%%%%%%%%%%%%%%%%%%%%%%%%
        $.ajax({
            'url': '<?php echo SURL ?>admin/dashboard/sell_market_order_by_user',
            'type': 'POST', //the way you want to send data to your URL
            'data': {sell_id: sell_id,market_value:market_value,quantity:quantity,symbol:symbol,buy_order_id:buy_order_id,order_type,sell_price:sell_price},
            'success': function (response) {
                $("#"+sell_id).html('Sell Now');
                if(response ==''){

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
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%
    }//End of sell_market_order_by_user


    function  limit_order_cancel(sell_id,market_value,quantity,symbol,buy_order_id){

        $.ajax({
            'url':'<?php echo SURL ?>admin/dashboard/check_status_of_limit_order',
            'type':'POST',
            'data':{sell_id:sell_id,symbol:symbol},
            'success':function(response){

                var rp = JSON.parse(response);
                var resp =rp['status'];
                var current_market_price =rp['market_price'];

                $("#"+sell_id).html('Sell Now');

                    if(resp == 'error'){
                        //%%%%%%%%%%%%%%% if order is an error status%%%%%%%%%%%
                            $.confirm({
                                title: 'Attention!',
                                content: 'The order is an error status. Please Remove the error to sell this order',
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
                    //%%%%%%%%%%%%%%%End of  error status%%%%%%%%%%%
                    }else if (resp == 'submitted'){
                        //%%%%%%%%%%%%%%%%%%% submitted status %%%%%%%%%%%%%%%%%%%%%

                            var content_html =' Order is already in <span style="color:orange;    font-size: 14px;"><b>SUBMIT</b></span> status for sell as limit order.'+
                            ' Are you want to  <span style="color:red;    font-size: 14px;"><b>Cancel</b></span>  it ?  And submit to sell agin!\
                            <div class="">\
                            <hr>\
                            <form>\
                            <div class="form-group">\
                            <div class="row"><div class="col-xs-6">\
                            <label>Current Market Price</label>\
                            <input class="form-control" step="any" type="number" name="cu_m_price_'+sell_id+'" value="'+current_market_price+'" disabled>\
                            </div><div class="col-xs-6">\
                            <label>Sell Price</label>\
                            <input class="form-control" step=".00000001" type="number" name="sell_price_'+sell_id+'" value="'+current_market_price+'" id="sell_price_'+sell_id+'">\
                            </div>\
                            </div>'

                            // <div class="radio">\
                            // <label><input type="radio" name="typ_submit_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\
                            // </div>\
                            // <div class="radio">\
                            // <label><input type="radio" name="typ_submit_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\
                            // </div>\


                        content_html +=' <div class="radio ">\
                            <label><input type="radio" name="typ_submit_'+sell_id+'" value="m_current" checked>Fire market order on current market price</label>\
                            </div>\
                            </form>\
                            </div>';

                        $.confirm({
                            title: 'Limit order Cancel  and resend order conformation',
                            content: content_html,
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: 'Ok',
                                    btnClass: 'btn-red',
                                    action: function(){

                                        var order_type = $("input[name='typ_submit_"+sell_id+"']:checked").val();
                                        var sell_price = $('#sell_price_'+sell_id).val();

                                    cancel_and_place_new_limit_order_for_sell(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price);
                                    }
                                },
                                    close: function () {
                                }
                            }
                        });
                        //%%%%%%%%%%%%%%%%%%% End of submitted status %%%%%%%%%%%%%%%%%%%%%
                    }else if (resp == 'new'){

                        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%5  New Status%%%%%%%%%%%%%%%%%%%%%%%%%
                        var content_html ='Select from below to sell this order\
                            <div class="">\
                            <hr>\
                            <form>\
                            <div class="form-group">\
                            <div class="row"><div class="col-xs-6">\
                            <label>Current Market Price</label>\
                            <input class="form-control" step="any" type="number" name="cu_m_price_'+sell_id+'" value="'+current_market_price+'" disabled>\
                            </div><div class="col-xs-6">\
                            <label>Sell Price</label>\
                            <input class="form-control" step=".00000001" type="number" name="sell_price_'+sell_id+'" value="'+current_market_price+'" id="sell_price_'+sell_id+'">\
                            </div>\
                            </div>'

                            // <div class="radio">\
                            // <label><input type="radio" name="typ_new_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\
                            // </div>\
                            // <div class="radio">\
                            // <label><input type="radio" name="typ_new_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\
                            // </div>\


                        content_html +=' <div class="radio ">\
                            <label><input type="radio" name="typ_new_'+sell_id+'" value="m_current" checked>Fire market order on current market price</label>\
                            </div>\
                            </form>\
                            </div>';

                        $.confirm({
                            title: 'Sell order conformation',
                            content: content_html,
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: 'Ok',
                                    btnClass: 'btn-red',
                                    action: function(){

                                        var order_type = $("input[name='typ_new_"+sell_id+"']:checked").val();
                                        var sell_price = $('#sell_price_'+sell_id).val();

                                    sell_lmit_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price);
                                    }
                                },
                                    close: function () {
                                }
                            }
                        });
                        //%%%%%%%%%%%%%%%%%%%%%%%End of new status %%%%%%%%%%%%%%%%%%%%%%%%
                    }


            }
        })

    }//End of limit_order_cancel





    function sell_lmit_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price){
        //%%%%%%%%%%%%%%%%%%%%%%%%%%
            $.ajax({
                'url': '<?php echo SURL ?>admin/dashboard/sell_lmit_order_by_user',
                'type': 'POST', //the way you want to send data to your URL
                'data': {sell_id: sell_id,market_value:market_value,quantity:quantity,symbol:symbol,buy_order_id:buy_order_id,order_type,sell_price:sell_price},
                'success': function (response) {
                    $("#"+sell_id).html('Sell Now');
                    if(response ==''){

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
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%
    }//End of sell_lmit_order_by_user

    function cancel_and_place_new_limit_order_for_sell(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price){
        $.ajax({
            'url': '<?php echo SURL ?>admin/dashboard/cancel_and_place_new_limit_order_for_sell',
            'type': 'POST', //the way you want to send data to your URL
            'data': {sell_id: sell_id,market_value:market_value,quantity:quantity,symbol:symbol,buy_order_id:buy_order_id,order_type,sell_price:sell_price},
            'success': function (response) {
                $("#"+sell_id).html('Sell Now');
                if(response ==''){

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
    }//End of cancel_and_place_new_limit_order_for_sell

    function sell_order(id,market_value,quantity,symbol){
        $("#"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

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

    }//End of sell_order

    function downloadCSV(csv, filename) {

        var csvFile;

        var downloadLink;



        // CSV file

        csvFile = new Blob([csv], {type: "text/csv"});



        // Download link

        downloadLink = document.createElement("a");



        // File name

        downloadLink.download = filename;



        // Create a link to the file

        downloadLink.href = window.URL.createObjectURL(csvFile);



        // Hide download link

        downloadLink.style.display = "none";



        // Add the link to DOM

        document.body.appendChild(downloadLink);



        // Click download link

        downloadLink.click();

    }



    function exportTableToCSV(filename) {

        var csv = [];

        var rows = document.querySelectorAll("table tr");



        for (var i = 0; i < rows.length; i++) {

            var row = [], cols = rows[i].querySelectorAll("td, th");



            for (var j = 0; j < cols.length; j++)

                row.push(cols[j].innerText);



            csv.push(row.join(","));

        }



        // Download CSV file

        downloadCSV(csv.join("\n"), filename);

    }
    $('body').on("click", ".showhidebtn", function(e){
        $(".showhide").toggle();
    });


    function create_custom_csv(){
        // $("#order_report_filter_form").clone().appendTo("#csv_report_filter_form");
        // $("#csv_report_filter_form > form").attr("action", "<?php //echo SURL; ?>admin/order_report_test/get_csv");
        // $("#csv_report_filter_form > form").attr("target", "_blank");
        // $("#csv_report_filter_form > form").attr("id", "csv_report_filter");
    }

    function create_csv_report(){
        $("#order_report_filter_form").clone().appendTo("#csv_report_filter_form");
        $("#csv_report_filter_form > form").attr("action", "<?php echo SURL; ?>admin/order_report_test/get_csv");
        $("#csv_report_filter_form > form").attr("target", "_blank");
        $("#csv_report_filter_form > form").attr("id", "csv_report_filter");
        $('form#csv_report_filter').submit();
    }

    function remove_order_error(el, order_id, exchange){
        $.ajax({
            'url': '<?php echo SURL ?>admin/api_calls/remove_error',
            'type': 'POST',
            'data': {
                order_id:order_id,
                exchange:exchange
            },
            'success': function (res) {
                if(res.status){
                    $(el).closest("tr").remove();
                }

                location . reload();

            }
        });
    }

    function sell_order_now(el, id, exchange){

        if(confirm('Are you sure you want to sell this order ?')){
            $.ajax({
                'url': '<?php echo SURL ?>admin/api_calls/sell_now',
                'type': 'POST',
                'data': {
                    id:id,
                    exchange:exchange
                },
                'success': function (res) {
                    if(res.status){
                        $(el).closest("tr").remove();
                    }

                    location . reload();

                }
            });
        }
    }

</script>