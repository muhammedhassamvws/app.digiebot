<?php $logged_id_user_id = $this->session->userdata('admin_id');?>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

<!-- Data Tables -->
<link rel='stylesheet' href='https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css'>
<link rel='stylesheet' href='https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css'>

<script
    src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-de7e2ef6bfefd24b79a3f68b414b87b8db5b08439cac3f1012092b2290c719cd.js">
</script>

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
    integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- End Data Tables -->


<style>
.failed {
    background: #e8bdbd !important;
}

.success-custom {
    background: rgb(203, 255, 203) !important;
}

.text-success-custom {
    color: #0d420d;
}

.text-danger-custom {
    color: #801515;
}

#example tbody td:first-child {
    position: relative;
    /* width: 100%;
    height: 100%;*/
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

td.t-long {
    min-width: 200px !important;
}

.label-row {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    gap: 5px;
    margin-bottom: 5px;
}
</style>

<style>
.hi_error {
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

input[type=checkbox] {
    display: none;
}

/* to hide the checkbox itself */
input[type=checkbox]+label:before {
    font-family: FontAwesome;
    display: inline-block;
}

.custom_label {
    font-size: 25px;
    width: 100%;
    text-align: center;
}

input[type=checkbox]+label:before {
    content: "\f096";
}

/* unchecked icon */
input[type=checkbox]+label:before {
    letter-spacing: 10px;
}

/* space between checkbox and label */
input[type=checkbox]:checked+label:before {
    content: "\f046";
}

/* checked icon */
input[type=checkbox]:checked+label:before {
    letter-spacing: 5px;
}

/* allow space for check mark */
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

.af-switcher>small {
    border-radius: 50px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
    height: 15px;
    position: absolute;
    top: 5px;
    width: 25px;
    left: 30px;
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

.af-custom-radio-label {
    color: #0f1c42;
    display: inline-block;
    cursor: pointer;
    font-weight: bold;
    padding: 5px 20px;
    margin: 0;
    width: 50%;
    float: left;
}

input[type=radio].af-cust-radio:checked+.af-custom-radio-label {
    color: #fff;
    background: #0f1c42;
}

.af-custom-radio-label+input[type=radio].af-cust-radio+.af-custom-radio-label {
    border-left: 3px solid #0f1c42;
}

.af-radio-group {
    border: 3px solid #0f1c42;
    display: block;
    margin: 5px 0 10px;
    border-radius: 10px;
    overflow: hidden;
    width: 290px;
    /* margin-left: -9%; */
}

@media only screen and (max-width: 1800px) {
    .af-radio-group {
        width: 250px;
    }

    .af-custom-radio-label {
        padding: 6px 10px;
    }
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

.af-table-body td:nth-child(7) {
    min-width: 102px;
}

.af-table-body td:nth-child(8) {
    min-width: 102px;
}

.af-table-body td:nth-child(9) {
    min-width: 120px;
}

/* .af-table-body td:nth-child(13) {
        min-width: 200px;
    }
    .af-table-body td:nth-child(14) {
        min-width: 200px;
    } */
.af-table-body td:nth-child(24) {
    min-width: 102px;
}

.af-table-body td:nth-child(25) {
    min-width: 200px;
}

.af-table-body td:nth-child(26) {
    min-width: 102px;
}

.af-table-body td:nth-child(27) {
    min-width: 102px;
}

.af-table-body {
    text-align: center;
}

/*---End-----*/
thead {
    background-color: #fff;
}

#table-container {
    max-height: 800px;
    /* Set the desired maximum height */
    overflow: auto;
    position: relative;
    /* Required for sticky header */
}

/* Table */
#example {
    width: 100%;
    border-collapse: collapse;
}

/* Sticky header */
#example thead th {
    position: sticky;
    top: 0;
    background-color: #f4f4f4;
    /* Customize the background color */
    z-index: 1;
}

.multiselect-container {
    overflow-y: auto;
    height: 500px;
}
</style>
<style>
.modal-dialog {
    width: 55% !important;
}

.modal-content {
    width: 100% !important;
}

@media screen and (max-width:1830px) {
    .modal-dialog {
        width: 65% !important;
    }
}

@media screen and (max-width:1560px) {
    .modal-dialog {
        width: 75% !important;
    }
}

@media screen and (max-width:1350px) {
    .modal-dialog {
        width: 85% !important;
    }
}

@media screen and (max-width:1220px) {
    .modal-dialog {
        width: 95% !important;
    }
}
</style>
<div id="content">

<div style="display: flex; justify-content:space-between; background: white;">
   
        <h2 class="content-heading bg-white" style="width: 40%; padding: 1%;">Order Report</h2>
      
    <div class="alert alert-info text-center" style="margin-bottom: 0px;width: fit-content;height: 50%;margin-top: auto;margin-bottom: auto;">
    <?php echo "Server Time: " . date("d-m-y H:i:s a"); ?></div>
</div>


    <!-- <div class="innerAll bg-white border-bottom">
        <div class="row">
            <div class="col-md-2">
                <ul class="menubar" style="margin-top: 5%;">

                    <li class=""><a href="<?php echo SURL; ?>admin/reports">Reports</a></li>

                    <li class="active"><a href="#">Custom Report</a></li>

                </ul>
            </div>
            <div class="col-md-10">
                <div class="alert alert-info" style="margin-bottom: 0px;">
                    <?php echo "Server Time: " . date("d-m-y H:i:s a"); ?></div>
            </div>
        </div>
    </div> -->



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
                    <form id="order_report_filter_form" method="POST"
                        action="<?php echo SURL; ?>admin/order_report/index">

                        <div class="row">

                            <div class="col-md-10">

                            </div>
                            <div class="col-md-2">
                                <button id="toggleFilter" class="btn btn-primary pull-right"
                                    style="margin-top: 4%;">Other Filters</button>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12"
                                style="display: flex;flex-wrap: wrap;padding-bottom: 6px;">


                                <!-- Multi-select status filter //Umer Abbas [12-11-19] -->
                                <?php $temp_filter_by_status = (!empty($filter_user_data['filter_by_status']) ? $filter_user_data['filter_by_status'] : array() );?>
                                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                                    <div class="Input_text_s">
                                        <label>Filter Status: </label>
                                        <select id="filter_by_status" name="filter_by_status[]" multiple type="text"
                                            class="form-control filter_by_name_margin_bottom_sm">
                                            <option value="new"
                                                <?php echo (in_array('new', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                New</option>
                                            <option value="sell_rule"
                                                <?php echo (in_array('sell_rule', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Sell Rule</option>
                                            <option value="shifted_order"
                                                <?php echo (in_array('shifted_order', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Shifted Order</option>
                                            <option value="open"
                                                <?php echo (in_array('open', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Open</option>
                                            <option value="bulk_orders"
                                                <?php echo (in_array('bulk_orders', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Bulk Orders</option>
                                            <option value="ca_single"
                                                <?php echo (in_array('ca_single', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                CA Open Single Order</option>
                                            <option value="cost_submitted_all_for_sell"
                                                <?php echo (in_array('cost_submitted_all_for_sell', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Cost Submitted All For Sell</option>
                                            <!-- <option value="submitted_for_sell"<?php echo (in_array('submitted_for_sell', $temp_filter_by_status) ? ' selected ' : ''); ?>>submitted for sell</option> -->
                                            <!-- <option value="fraction_submitted_sell"<?php echo (in_array('fraction_submitted_sell', $temp_filter_by_status) ? ' selected ' : ''); ?>>fraction submitted sell</option> -->
                                            <option value="error"
                                                <?php echo (in_array('error', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Error</option>
                                            <option value="sold"
                                                <?php echo (in_array('sold', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Sold</option>
                                            <option value="sold_manually"
                                                <?php echo (in_array('sold_manually', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Sold manually</option>
                                            <option value="shifted_open"
                                                <?php echo (in_array('shifted_open', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Open Shifted Trades </option>
                                            <option value="shifted_lth"
                                                <?php echo (in_array('shifted_lth', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Lth Shifted Trades </option>
                                            <option value="shifted_sold"
                                                <?php echo (in_array('shifted_sold', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Sold Shifted Trades </option>
                                            <option value="LTH_sold"
                                                <?php echo (in_array('LTH_sold', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                LTH Sold</option>
                                            <option value="LTH"
                                                <?php echo (in_array('LTH', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                LTH</option>
                                            <option value="lth_pause"
                                                <?php echo (in_array('lth_pause', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                LTH Paused</option>
                                            <!-- <option value="fraction_submitted_buy"<?php echo (in_array('fraction_submitted_buy', $temp_filter_by_status) ? ' selected ' : ''); ?>>Fraction Submitted Buy</option> -->
                                            <option value="takingparent"
                                                <?php echo (in_array('takingparent', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Parent Taking Trades</option>
                                            <option value="takeparent"
                                                <?php echo (in_array('takeparent', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Parent To Take Trade</option>
                                            <option value="play"
                                                <?php echo (in_array('play', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Active Parent</option>
                                            <option value="pause"
                                                <?php echo (in_array('pause', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Inactive Parent</option>
                                            <option value="pick_parent_yes"
                                                <?php echo (in_array('pick_parent_yes', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Pick Parent Yes</option>
                                            <option value="pick_parent_no"
                                                <?php echo (in_array('pick_parent_no', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Pick Parent No</option>
                                            <option value="canceled"
                                                <?php echo (in_array('canceled', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Canceled</option>
                                            <option value="error_in_sell"
                                                <?php echo (in_array('error_in_sell', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Error In Sell</option>
                                            <option value="new_ERROR"
                                                <?php echo (in_array('new_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                new ERROR</option>
                                            <option value="IP_BAN_ERROR"
                                                <?php echo (in_array('IP_BAN_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Ip Ban Error</option>
                                            <option value="KEYFILLED_ERROR"
                                                <?php echo (in_array('KEYFILLED_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Key Filled Error</option>
                                            <!-- <option value="cost_submitted_all_for_sell"<?php echo (in_array('cost_submitted_all_for_sell', $temp_filter_by_status) ? ' selected ' : ''); ?>>Cost Submitted all for Sell</option> -->
                                            <option value="binance_sold_doubt"
                                                <?php echo (in_array('binance_sold_doubt', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Binance Sold Doubt</option>

                                            <option value="FILLED_ERROR"
                                                <?php echo (in_array('FILLED_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                FILLED ERROR</option>
                                            <!-- <option value="submitted_ERROR"<?php echo (in_array('submitted_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>submitted ERROR</option> -->
                                            <option value="SELL_ID_ERROR"
                                                <?php echo (in_array('SELL_ID_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                SELL ID ERROR</option>
                                            <option value="LTH_ERROR"
                                                <?php echo (in_array('LTH_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                LTH ERROR</option>
                                            <option value="canceled_ERROR"
                                                <?php echo (in_array('canceled_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                canceled ERROR</option>
                                            <option value="credentials_ERROR"
                                                <?php echo (in_array('credentials_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                credentials ERROR</option>
                                            <option value="fraction_ERROR"
                                                <?php echo (in_array('fraction_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                fraction ERROR</option>
                                            <option value="resume_paused"
                                                <?php echo (in_array('resume_paused', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Paused</option>
                                            <option value="resumed"
                                                <?php echo (in_array('resumed', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Resumed</option>
                                            <option value="resume_in_progress"
                                                <?php echo (in_array('resume_in_progress', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Resume (In progress)</option>
                                            <option value="resume_completed"
                                                <?php echo (in_array('resume_completed', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Resume Completed</option>
                                            <option value="resume_child_sold"
                                                <?php echo (in_array('resume_child_sold', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Resume Childs (open)</option>
                                            <option value="quantity_issue"
                                                <?php echo (in_array('quantity_issue', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Quantity Issue</option>
                                            <option value="cost_average"
                                                <?php echo (in_array('cost_average', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                All Ledger Cost Avg Parents</option>
                                            <option value="cost_average_orders"
                                                <?php echo (in_array('cost_average_orders', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Cost Avg Orders</option>
                                            <option value="cost_averageOpen"
                                                <?php echo (in_array('cost_averageOpen', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Open Ledger Cost Average</option>
                                            <option value="cost_averageSold"
                                                <?php echo (in_array('cost_averageSold', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Sold Ledger Cost Average</option>
                                            <option value="cost_average_sold_status"
                                                <?php echo (in_array('cost_average_sold_status', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Cost Avg Sold Status</option>
                                            <option value="profitOrders"
                                                <?php echo (in_array('profitOrders', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Profit orders check</option>
                                            <option value="childBuy"
                                                <?php echo (in_array('childBuy', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                CA child buy last 24H</option>
                                            <option value="duplicateOrders"
                                                <?php echo (in_array('duplicateOrders', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Duplicate Sold Orders Check</option>
                                            <option value="duplicateOrdersOpen"
                                                <?php echo (in_array('duplicateOrdersOpen', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Duplicate Open/lth Orders Check</option>
                                            <option value="user_left"
                                                <?php echo (in_array('user_left', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                User Left</option>
                                            <option value="COIN_BAN_ERROR"
                                                <?php echo (in_array('COIN_BAN_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                COIN BAN ERROR</option>
                                            <option value="APIKEY_ERROR"
                                                <?php echo (in_array('APIKEY_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                APIKEY ERROR</option>
                                            <option value="LOT_SIZE_ERROR"
                                                <?php echo (in_array('LOT_SIZE_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                LOT SIZE ERROR</option>
                                            <option value="TEMPAPILOCK_ERROR"
                                                <?php echo (in_array('TEMPAPILOCK_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                TEMPAPILOCK ERROR</option>
                                            <option value="APINONCE"
                                                <?php echo (in_array('APINONCE', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                API NONCE</option>
                                            <option value="atg_yes"
                                                <?php echo (in_array('atg_yes', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                ATG Enabled</option>
                                            <option value="ARCHIVE"
                                                <?php echo (in_array('ARCHIVE', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                ARCHIVE</option>
                                            <option value="CA_Pre_july_2021"
                                                <?php echo (in_array('CA_Pre_july_2021', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                CA(Parents) Pre july 2021</option>
                                            <option value="CA_child_buy"
                                                <?php echo (in_array('CA_child_buy', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                CA(Child) buy</option>
                                            <option value="CA_child_sold"
                                                <?php echo (in_array('CA_child_sold', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                CA(Child) sold</option>
                                            <option value="negative_sold"
                                                <?php echo (in_array('negative_sold', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                Negative sold/Negative accumulation</option>
                                            <option value="errors_tab"
                                                <?php echo (in_array('errors_tab', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                All Erros Users Trades</option>
                                            <option value="QUANTITY_ERROR"
                                                <?php echo (in_array('QUANTITY_ERROR', $temp_filter_by_status) ? ' selected ' : ''); ?>>
                                                QUANTITY ERROR</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- style="display: flex;flex-wrap: wrap;padding-bottom: 6px;" -->
                                <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;" id="coinDiv">

                                    <div class="Input_text_s">

                                        <label>Filter Coin: </label>

                                        <select id="filter_by_coin" name="filter_by_coin[]" type="text"
                                            class="form-control filter_by_name_margin_bottom_sm" multiple="multiple"
                                            data-mdb-filter="true">

                                            <option value=""
                                                <?=(($filter_user_data['filter_by_coin'] == "") ? "selected" : "")?>
                                                disabled>Search By Coin Symbol</option>

                                            <?php

                                        for ($i = 0; $i < count($coins); $i++) {

                                            $selected = (in_array($coins[$i]['symbol'],$filter_user_data['filter_by_coin'])) ? "selected" : "";

                                            echo "<option value='" . $coins[$i]['symbol'] . "' $selected>" . $coins[$i]['symbol'] . "</option>";

                                        }

                                        ?>

                                        </select>

                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3" id="ipDiv"
                                    style="padding-bottom: 6px; display:none;">
                                    <div class="Input_text_s">
                                        <label>Select Ip: </label>
                                        <select id="tradingIp" name="tradingIp" type="text"
                                            class="form-control filter_by_name_margin_bottom_sm">
                                            <option value="">Select Ip</option>
                                            <option value="3.227.143.115"
                                                <?=(($filter_user_data['tradingIp'] == "3.227.143.115") ? "selected" : "")?>>
                                                3.227.143.115</option>
                                            <option value="3.228.180.22"
                                                <?=(($filter_user_data['tradingIp'] == "3.228.180.22") ? "selected" : "")?>>
                                                3.228.180.22</option>
                                            <option value="3.226.226.217"
                                                <?=(($filter_user_data['tradingIp'] == "3.226.226.217") ? "selected" : "")?>>
                                                3.226.226.217</option>
                                            <option value="3.228.245.92"
                                                <?=(($filter_user_data['tradingIp'] == "3.228.245.92") ? "selected" : "")?>>
                                                3.228.245.92</option>
                                            <option value="35.153.9.225"
                                                <?=(($filter_user_data['tradingIp'] == "35.153.9.225") ? "selected" : "")?>>
                                                35.153.9.225</option>
                                            <option value="54.157.102.20"
                                                <?=(($filter_user_data['tradingIp'] == "54.157.102.20") ? "selected" : "")?>>
                                                54.157.102.20</option>
                                            <option value="18.170.235.202"
                                                <?=(($filter_user_data['tradingIp'] == "18.170.235.202") ? "selected" : "")?>>
                                                18.170.235.202</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-3" id="modeDiv"
                                    style="padding-bottom: 6px; display:none;">

                                    <div class="Input_text_s">

                                        <label>Filter Mode: </label>

                                        <select id="filter_by_mode" name="filter_by_mode" type="text"
                                            class="form-control filter_by_name_margin_bottom_sm">
                                            <option value="live"
                                                <?=(($filter_user_data['filter_by_mode'] == "live") ? "selected" : "")?>>
                                                Live</option>
                                            <option value="test"
                                                <?=(($filter_user_data['filter_by_mode'] == "test") ? "selected" : "")?>>
                                                Test</option>
                                        </select>

                                    </div>

                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-3" id="createdDateFromDiv"
                                    style="padding-bottom: 6px; display:none;">

                                    <div class="Input_text_s">

                                        <label>Created Date Range From: </label>

                                        <input id="filter_by_start_date" name="filter_by_start_date" type="datetime-local"
                                            class="form-control" placeholder="Search By Date"
                                            value="<?=(!empty($filter_user_data['filter_by_start_date']) ? $filter_user_data['filter_by_start_date'] : "")?>">

                                        <!-- <i class="glyphicon glyphicon-calendar"></i> -->

                                    </div>

                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-3" id="createdDateToDiv"
                                    style="padding-bottom: 6px; display:none;">

                                    <div class="Input_text_s">

                                        <label>Created Date Range To: </label>

                                        <input id="filter_by_end_date" name="filter_by_end_date" type="datetime-local"
                                            class="form-control " placeholder="Search By Date"
                                            value="<?=(!empty($filter_user_data['filter_by_end_date']) ? $filter_user_data['filter_by_end_date'] : "")?>">

                                        <!-- <i class="glyphicon glyphicon-calendar"></i> -->

                                    </div>

                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-3" id="modifiedDateFromDiv"
                                    style="padding-bottom: 6px; display:none;">

                                    <div class="Input_text_s">

                                        <label>Modified Date Range From: </label>

                                        <input id="filter_by_start_date_m" name="filter_by_start_date_m" type="datetime-local"
                                            class="form-control" placeholder="Search By Date"
                                            value="<?=(!empty($filter_user_data['filter_by_start_date_m']) ? $filter_user_data['filter_by_start_date_m'] : "")?>">

                                        <!-- <i class="glyphicon glyphicon-calendar"></i> -->

                                    </div>

                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-3" id="modifiedDateToDiv"
                                    style="padding-bottom: 6px; display:none;">

                                    <div class="Input_text_s">

                                        <label>Modified Date Range To: </label>

                                        <input id="filter_by_end_date_m" name="filter_by_end_date_m" type="datetime-local"
                                            class="form-control" placeholder="Search By Date"
                                            value="<?=(!empty($filter_user_data['filter_by_end_date_m']) ? $filter_user_data['filter_by_end_date_m'] : "")?>">

                                        <!-- <i class="glyphicon glyphicon-calendar"></i> -->

                                    </div>

                                </div>

                                <script type="text/javascript">
                                $(function() {

                                    $('.datetime_picker').datetimepicker();

                                });
                                </script>

                                <style>
                                .Input_text_btn {
                                    padding: 25px 0 0;
                                }
                                </style>

                                <div class="col-xs-12 col-sm-12 col-md-3" id="triggerDiv"
                                    style="padding-bottom: 6px; display:none;">

                                    <div class="Input_text_s">

                                        <label>Filter Trigger: </label>

                                        <select id="filter_by_trigger" name="filter_by_trigger" type="text"
                                            class="form-control filter_by_name_margin_bottom_sm">

                                            <option value="">Search By Trigger</option>

                                            <option value="trigger_1"
                                                <?php if ($filter_user_data['filter_by_trigger'] == 'trigger_1') {?>
                                                selected <?php }?>>Trigger 1</option>

                                            <option value="trigger_2"
                                                <?php if ($filter_user_data['filter_by_trigger'] == 'trigger_2') {?>
                                                selected <?php }?>>Trigger 2</option>

                                            <option value="box_trigger_3"
                                                <?php if ($filter_user_data['filter_by_trigger'] == 'box_trigger_3') {?>
                                                selected <?php }?>>Box Trigger 3</option>

                                            <option value="barrier_trigger"
                                                <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_trigger') {?>
                                                selected <?php }?>>BARRIER TRIGGER</option>

                                            <option value="rg_15"
                                                <?php if ($filter_user_data['filter_by_trigger'] == 'rg_15') {?>
                                                selected <?php }?>>RG 15</option>

                                            <option value="barrier_percentile_trigger"
                                                <?php if ($filter_user_data['filter_by_trigger'] == 'barrier_percentile_trigger') {?>
                                                selected <?php }?>>Barrier Percentile Trigger</option>

                                            <option value="no"
                                                <?php if ($filter_user_data['filter_by_trigger'] == 'no') {?> selected
                                                <?php }?>>Manual Order</option>

                                        </select>

                                    </div>

                                </div>

                                <!-- Hidden Searches -->

                                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px; display: none;"
                                    id="barrier_t">

                                    <div class="Input_text_s">

                                        <label>Filter Rule: </label>

                                        <select id="filter_by_rule" name="filter_by_rule" type="text"
                                            class="form-control filter_by_name_margin_bottom_sm">

                                            <option value="">Filter By Rule</option>

                                            <?php for ($i = 1; $i <= 10; $i++) {?>

                                            <option value="<?=$i;?>"
                                                <?php if ($filter_user_data['filter_by_rule'] == $i) {?> selected
                                                <?php }?>>Rule No <?=$i;?></option>

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
                                <option value="<?php echo $lev['level']; ?>"
                                    <?php if ($filter_level == $lev['level']) {?> selected <?php }?>>
                                    <?php echo $lev['level']; ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                        */ ?>

                        <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px; display: none;"
                            id="barrier_p_t">
                            <div class="Input_text_s">
                                <label>Filter Level: </label>
                                <select id="filter_by_level" name="filter_level[]" multiple type="text"
                                    class="form-control">
                                    <?php $filter_level = $filter_user_data['filter_level'];?>
                                    <option value="">Search By Level</option>
                                    <?php foreach ($order_levels as $lev) { ?>
                                    <option value="<?php echo $lev['level']; ?>"
                                        <?php echo (in_array($lev['level'], $filter_level) ? ' selected ' : '');?>>
                                        <?php echo $lev['level']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <!-- End Hidden Searches -->

                        <!-- Exchange auto/manual order  //Umer Abbas [4-11-19] -->
                        <div class="col-xs-12 col-sm-12 col-md-3" id="orderTypeDiv"
                            style="padding-bottom: 6px; display:none;">
                            <div class="Input_text_s">
                                <label>Filter Auto/Manual Order: </label>
                                <select id="filter_by_nature" name="filter_by_nature" type="text"
                                    class="form-control filter_by_name_margin_bottom_sm">
                                    <option value="">Search By Order Type</option>
                                    <option value="auto" <?php if ($filter_user_data['filter_by_nature'] == 'auto') {?>
                                        selected <?php }?>>Auto Order</option>
                                    <option value="manual"
                                        <?php if ($filter_user_data['filter_by_nature'] == 'manual') {?> selected
                                        <?php }?>>Manual Order</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3" id="coinTypeDiv"
                            style="padding-bottom: 6px; display:none;">
                            <div class="Input_text_s">
                                <label>Filter Coin Type: </label>
                                <select id="filter_by_coinNature" name="filter_by_coinNature" type="text"
                                    class="form-control filter_by_name_margin_bottom_sm">
                                    <option value="both"
                                        <?php if ($filter_user_data['filter_by_coinNature'] == 'both') {?> selected
                                        <?php }?>> Both </option>
                                    <option value="btc"
                                        <?php if ($filter_user_data['filter_by_coinNature'] == 'btc')  {?> selected
                                        <?php }?>> BTC Orders </option>
                                    <option value="usdt"
                                        <?php if ($filter_user_data['filter_by_coinNature'] == 'usdt') {?> selected
                                        <?php }?>> USDT Orders </option>
                                </select>
                            </div>
                        </div>

                        <!-- Exchange Filter //Umer Abbas [1-11-19] -->
                        <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;" id="exchangeDiv">
                            <div class="Input_text_s">
                                <label>Filter Exchange: </label>
                                <select id="filter_by_exchange" name="filter_by_exchange" type="text"
                                    class="form-control filter_by_name_margin_bottom_sm">
                                    <option value="">Search By Exchange</option>
                                    <option value="binance"
                                        <?php if ($filter_user_data['filter_by_exchange'] == 'binance') {?> selected
                                        <?php }?>>Binance</option>
                                    <option value="kraken"
                                        <?php if ($filter_user_data['filter_by_exchange'] == 'kraken') {?> selected <?php } if (!in_array($filter_user_data['filter_by_exchange'], ['binance','kraken'])) {echo 'selected';}
                            ?>>Kraken</option>
                                    <option value="dg" <?php if ($filter_user_data['filter_by_exchange'] == 'dg') {?>
                                        selected <?php }?>>Digie</option>
                                    <option value="okex"
                                        <?php if ($filter_user_data['filter_by_exchange'] == 'okex') {?> selected
                                        <?php }?>>Okex</option>
                                    <!-- <option value="coinbasepro" selected>Coinbase pro</option> -->
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-2" style="padding-bottom: 6px;" id="usernameDiv">

                            <div class="Input_text_s">

                                <label>Filter Username: </label>

                                <input type="text" class="form-control" name="filter_username" id="username"
                                    value="<?=(!empty($filter_user_data['filter_username']) ? $filter_user_data['filter_username'] : "")?>">

                            </div>

                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3" id="excludeUsernameDiv"
                            style="padding-bottom: 6px; display:none;">

                            <div class="Input_text_s">

                                <label>Exclude Users name:</label>

                                <input type="text" class="form-control" name="filter_username_exclude"
                                    id="filter_username_exclude"
                                    value="<?=(!empty($filter_user_data['filter_username_exclude']) ? $filter_user_data['filter_username_exclude'] : "")?>">

                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3" id="profitDiv"
                            style="padding-bottom: 6px;display:none;">

                            <div class="Input_text_s">

                                <label>Filter Profit: </label>

                                <input type="text" class="form-control" name="profit" id="profit"
                                    value="<?=(!empty($filter_user_data['profit']) ? $filter_user_data['profit'] : "")?>">

                            </div>

                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-3" id="opportunityIdDiv"
                            style="padding-bottom: 6px; display:none;">
                            <div class="Input_text_s">
                                <label>Filter Opportunity Id: </label>
                                <input type="text" class="form-control" name="opportunityId" id="opportunityId"
                                    value="<?=(!empty($filter_user_data['opportunityId']) ? $filter_user_data['opportunityId'] : "")?>">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3" id="OrderIdDiv" style="padding-bottom: 6px;">
                            <div class="Input_text_s">
                                <label>Filter Order Id: </label>
                                <input type="text" class="form-control" name="orderId" id="orderId"
                                    value="<?=(!empty($filter_user_data['orderId']) ? $filter_user_data['orderId'] : "")?>">
                            </div>
                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-3" id="parentAdminDiv"
                            style="padding-bottom: 6px;padding-top: 1%;margin-bottom: 0%; display:none;">
                            <div style="">
                                <div class="Input_text_s">
                                    <label></label>
                                    <input id="box2" class="form-control" name="show_parents" value="yes"
                                        type="checkbox" wfd-invisible="true">
                                    <label class="" for="box2">Show Parents</label>
                                </div>
                                <div class="Input_text_s" style="margin-left: 0%;">
                                    <label></label>
                                    <input class="form-control" type="checkbox" id="adminOrder" name="adminOrder"
                                        value="yes" wfd-invisible="true">
                                    <label class="" for="adminOrder">Show Admin Order
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                    
                </div>  -->

                        <div class="col-xs-12 col-sm-12 col-md-5" style="padding-bottom: 6px;">

                            <label>Sort Field: </label>

                            <div class="Input_text_s"
                                style="display:flex; justify-content:space-evenly;margin-left: -4%;">


                                <div class="af-radio-group">

                                    <input class="af-cust-radio" value="created_date" name="optradio" type="radio"
                                        id="option-one" <?php if ($filter_user_data['optradio'] == 'created_date') {?>
                                        checked <?php }?>
                                        <?=(empty($filter_user_data['optradio']) ? ' checked ' : '')?>>

                                    <label class="af-custom-radio-label" for="option-one">Created Date</label>

                                    <input class="af-cust-radio" value="modified_date" name="optradio" type="radio"
                                        id="option-two" <?php if ($filter_user_data['optradio'] == 'modified_date') {?>
                                        checked <?php }?>>

                                    <label class="af-custom-radio-label" for="option-two">Modified Date</label>

                                </div>

                                <div class="af-radio-group af-form-group-created">

                                    <input class="af-cust-radio" value="ASC" type="radio" id="option-one2"
                                        name="selector" <?php if ($filter_user_data['selector'] == 'ASC') {?> checked
                                        <?php }?>>

                                    <label class="af-custom-radio-label" for="option-one2">ASC</label>

                                    <input class="af-cust-radio" value="DESC" type="radio" id="option-two2"
                                        name="selector" <?php if ($filter_user_data['selector'] == 'DESC') {?> checked
                                        <?php }?> <?=(empty($filter_user_data['selector']) ? ' checked ' : '')?>>

                                    <label class="af-custom-radio-label" for="option-two2">DESC</label>

                                </div>

                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-5" style="padding-bottom: 6px;margin-top: 0.4%;">

                            <div class="Input_text_btn">

                                <label></label>

                                <button id="submit-form" class="btn btn-success"><i
                                        class="glyphicon glyphicon-filter"></i>Search</button>

                                <a href="<?php echo SURL; ?>admin/order_report/reset_filters_report/all"
                                    class="btn btn-danger"><i class="fa fa-times-circle"></i>Reset</a>
                                <button class="btn btn-primary btn-sm" onclick="create_csv_report()"
                                    style="height:34px;">Export CSV</button>


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
                <!-- <button class="btn btn-primary btn-sm" onclick="create_csv_report()">Export CSV</button> -->
            </div>
            <div class="table-responsive">
                <!-- Table -->
                <div id='table-container'>
                    <table id="example" class="table table-bordered" cellspacing="0" width="100%">

                        <!-- Table heading -->
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center"><strong>Coin</strong></th>
                                <th class="text-center"><strong>Price</strong></th>
                                <th class="text-center"><strong>Order Type</strong></th>
                                <th class="text-center"><strong>Level</strong></th>
                                <th class="text-center"><strong>Quantity</strong></th>
                                <th class="text-center"><strong>Usd Worth</strong></th>
                                <th class="text-center"><strong>Invest</strong></th>
                                <th class="text-center"><strong>Sold</strong></th>
                                <th class="text-center"><strong>Buy/Sell Fee</strong></th>
                                <th class="text-center"><strong>Gain</strong><small> including fee</small></th>
                                <th class="text-center"><strong>Allocated BTC</strong></th>
                                <th class="text-center"><strong>Remaining BTC</strong></th>
                                <th class="text-center"><strong>Allocated USDT</strong></th>
                                <th class="text-center"><strong>Remaining Allocated USDT</strong></th>
                                <th class="text-center"><strong>Accumulations Collective</strong></th>
                                <th class="text-center"><strong>Accumulations</strong></th>
                                <!--     <th class="text-center"><strong>CA Combine Accumulations</strong></th> -->
                                <th class="text-center"><strong>Created Date</strong></th>
                                <th class="text-center"><strong>Last Modified Date</strong></th>
                                <th class="text-center"><strong>P/L</strong></th>
                                <th class="text-center"><strong>Market(%)</strong></th>
                                <th class="text-center"><strong>Status</strong></th>
                                <th class="text-center"><strong>P/L(%)</strong></th>

                                <th class="text-center"><strong>Ledger Details</strong></th>
                                <th class="text-center"><strong>Last Child details</strong></th>
                                <th class="text-center"><strong>Latest Child Buy/Sell Time</strong></th>
                                <th class="text-center"><strong>Market Sold Price</strong></th>
                                <th class="text-center"><strong>Target Profit(%)</strong></th>
                                <th class="text-center"><strong>Slippage(%)</strong></th>
                                <th class="text-center"><strong>Original Slippage(%)</strong></th>
                                <th class="text-center"><strong>Time Difference</strong></th>
                                <th class="text-center"><strong>LTH</strong></th>
                                <th class="text-center"><strong>LTH Profit(%)</strong></th>
                                <th class="text-center"><strong>Stop Loss(%)</strong></th>
                                <th class="text-center"><strong>Sub Status</strong></th>
                                <th class="text-center"><strong>Max(%) / Min(%)</strong></th>
                                <th class="text-center"><strong>5hMax(%) / 5hMin(%)</strong></th>
                                <th class="text-center"><strong>User Info</strong></th>
                                <th class="text-center"><strong>Full Name</strong></th>
                                <th class="text-center"><strong>User IP</strong></th>
                                <!-- <th class="text-center" style="min-width: 120px"><strong>Buy Rules</strong></th>             -->
                                <th class="text-center"><strong>Actions</strong></th>
                            </tr>
                        </thead>

                        <tbody class="af-table-body">
                            <?php

    $coin_arrayBTC  = ['XMRBTC','XLMBTC','ETHBTC','XRPBTC', 'NEOBTC', 'QTUMBTC', 'XEMBTC', 'POEBTC', 'TRXBTC', 'ZENBTC', 'ETCBTC', 'EOSBTC', 'LINKBTC', 'DASHBTC', 'ADABTC','AAVEBTC','COMPBTC', 'KSMBTC', 'ALGOBTC', 'ANTBTC', 'BNBBTC', 'DOTBTC', 'VETBTC'];
    

$errArr = ['KEYFILLED_ERROR','APINONCE_ERROR','user_left','COIN_BAN_ERROR','APIKEY_ERROR','TEMPAPILOCK_ERROR','error', 'new_ERROR', 'FILLED_ERROR', 'submitted_ERROR', 'LTH_ERROR', 'canceled_ERROR', 'credentials_ERROR', 'fraction_ERROR', 'SELL_ID_ERROR','IP_BAN_ERROR','binance_sold_doubt','api_error','LOT_SIZE_ERROR','ARCHIVE','QUANTITY_ERROR']; 
$nanArr = ['nan', 'Nan', 'NAN', 'inf', 'Inf', 'INF', '']; 
$order_exchange = 'binance';
if (!empty($filter_user_data['filter_by_exchange'])) {
    $order_exchange = $filter_user_data['filter_by_exchange'];
}

$BTCUSDTmarket_value = $this->mod_dashboard->get_market_value2('BTCUSDT', $order_exchange);

if (count($orders) > 0) {
    
    foreach ($orders as $key => $value) {
        if(isset($_COOKIE['sheraz']) && $_COOKIE['sheraz'] == 1){
            echo '<pre>';print_r($value);exit;
        }
        if(!empty($value)){ ?>
                            <?php 
                // $market_value = $this->mod_dashboard->get_market_value($value['symbol']);
                $market_value = $this->mod_dashboard->get_market_value2($value['symbol'], $order_exchange);
                $logo = $this->mod_coins->get_coin_logo($value['symbol']);
                if ($value['status'] != 'new') {
                    if($value['status'] == 'FILLED'){
                        $market_value333 = num($value['purchased_price']);
                    }else{
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

                if($value['status'] == 'canceled' && (empty($value['parent_status']) || $value['parent_status'] != 'parent')){
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
                                    <?= ++$serial_number; ?>
                                </td>
                                <!-- end 1st <td> -->
                                <td class="text-center">
                                    <div class="fill_style_toplft">
                                        <?php 
                    if($value['duplicate_buy'] == 'yes'){
                        echo 'DB';
                }

                if($value['duplicate_sell'] == 'yes'){
                        echo 'DS';
                }

                if($value['trigger_type'] == 'barrier_percentile_trigger'){
                    $ledger_type = 'Auto';
                }else{
                    $ledger_type = 'Manual';
                }
                ?>
                                    </div>
                                    <?php echo $value['symbol'] ?>
                                    <span><?php echo $ledger_type; ?></span>
                                </td>
                                <!-- end 2nd <td> -->
                                <td class="text-center">
                                    <span><?= (isset($value['purchased_price']) ? num($value['purchased_price']): num($value['price']) ); ?></span><br>
                                    <?php if($value['highPrice_range'] != 0){ 
                    if($value['highPrice_range'] > 0){
                        $style = 'style="background: #a0e46f;border-radius: 7px;font-weight: bold;"';
                    }else{
                        $style = 'style="background: #e480806b;border-radius: 7px;font-weight: bold;"';
                    } ?>
                                    <span <?= $style; ?>>High Range :
                                        <?= number_format($value['highPrice_range'],2); ?>%</span><br>
                                    <?php } ?>
                                    <?php if($value['lowPrice_range'] != 0){ 
                    if($value['lowPrice_range'] > 0){
                        $style = 'style="background: #a0e46f;border-radius: 7px;font-weight: bold;"';
                    }else{
                        $style = 'style="background: #e480806b;border-radius: 7px;font-weight: bold;"';
                    } ?>
                                    <span <?= $style; ?>>Low Range :
                                        <?= number_format($value['lowPrice_range'],2) ?>%</span><br>
                                    <?php } ?>
                                    <?php if(isset($value['CRT1'])){ ?>
                                    <span>CRT1 : <?= number_format($value['CRT1'],5); ?></span><br>
                                    <?php } ?>
                                    <?php if(isset($value['CRT7'])){ ?>
                                    <span>CRT7 : <?= number_format($value['CRT7'],5); ?></span><br>
                                    <?php } ?>
                                    <?php if(isset($value['randomize_sort'])){ ?>
                                    <span style="background-color: lavenderblush;">Randomize :
                                        <?= $value['randomize_sort']; ?></span><br>
                                    <?php } ?>

                                </td>
                                <!-- end 3rd <td> -->
                                <td class="text-center">
                                    <?php 
                    if($value['trigger_type'] != 'no') {
                        if($value['trigger_type'] == 'barrier_percentile_trigger'){
                            echo 'BPT';
                        }else{
                            echo strtoupper(str_replace('_', ' ', $value['trigger_type']));
                        }
                    }else{
                        echo 'MANUAL ORDER';
                    } 
                ?>
                                </td>
                                <!-- end 4th <td> -->
                                <td class="text-center"><?= $value['order_level']; ?></td>
                                <!-- end 5th <td> -->
                                <?php if(isset($value['cost_avg_array']) && count($value['cost_avg_array']) > 0){ ?>
                                <td class="text-center"><?= $value['cost_avg_array'][0]['filledQtyBuy']?></td>
                                <?php  }else{ ?>
                                <td class="text-center"><?= $value['quantity']?></td>
                                <?php } ?>
                                <!-- end 6th <td> -->
                                <td class="text-center">
                                    <?php 
                $usd_worth_tt = get_usd_worth($value['symbol'], $value['quantity'], $market_value333, $BTCUSDTmarket_value);
                if(isset($value['cost_avg_array']) && count($value['cost_avg_array']) > 0){
                    echo '$'.$usd_worth_tt * (count($value['cost_avg_array']));
                }else{
                    echo '$'.$usd_worth_tt;
                }
                ?>
                                </td>
                                <!-- end 7th <td> -->
                                <td>
                                    <?php 
                    if(in_array($value['symbol'] , $coin_arrayBTC)){
                        $btcInvest   =  $value['quantity'] * $value['purchased_price'];

                        echo "<br>₿: ".number_format($btcInvest, 6);
                         $usdt_worth_showing = number_format(($btcInvest), 6);
                        echo '<pre style="background-color: aquamarine;padding: 2px;"> $ : '.number_format(convert_btc_to_usdt($usdt_worth_showing),2);
                    }else{
                        $usdtInvest   =  $value['quantity'] * $value['purchased_price'];
                        echo "<br>$: ".number_format($usdtInvest, 2);
                    } 
                ?>
                                </td>
                                <!-- end 8th <td>-->
                                <td>
                                    <?php if($value['is_sell_order'] == 'sold'){ 
                    if(in_array($value['symbol'] , $coin_arrayBTC)){
                        $btcGain   =  $value['quantity'] * $value['market_sold_price'];
                        echo "<br>₿: ".number_format($btcGain, 6);
                        $usdt_worth_showing = number_format(($btcGain), 6);
                        echo '<pre style="background-color: aquamarine;padding: 2px;"> $ : '.number_format(convert_btc_to_usdt($usdt_worth_showing),2);
                    }else{
                        $usdtGain   =  $value['quantity'] * $value['market_sold_price'];
                        echo "<br>$: ".number_format($usdtGain, 2);
                    }
                }else{
                    echo "---";
                } ?>

                                </td>
                                <!-- end 9th <td>-->

                                <td>
                                    <?php 
                    if($value['is_sell_order'] == 'sold'){
                        $commission_sold_array          =  $value['buy_fraction_filled_order_arr'];
						$sell_commission_sold_array     =  $value['sell_fraction_filled_order_arr'];

                        $sell_comssion_bnb          = 0 ;
                        $sell_fee_respected_coin    = 0;
                        $buy_commision_bnb          = 0 ;
                        $buy_fee_respected_coin     = 0;

                        foreach($sell_commission_sold_array as $sell_comm){
                            if($sell_comm['commissionAsset'] =='BNB'){
                                $sell_comssion_bnb += (float)$sell_comm['commission'];
                            }else{
                                $sell_fee_respected_coin += (float) $sell_comm['commission'];
                            }
                        }
                        foreach($commission_sold_array as $comm_1){
                            if($comm_1['commissionAsset'] =='BNB'){
                                $buy_commision_bnb +=(float) $comm_1['commission'];
                            }else{
                                $buy_fee_respected_coin += (float) $comm_1['commission'];
                            }  
                        }
                        if($buy_commision_bnb > 0){
                            echo "buy bnb: ".$buy_commision_bnb;
                        }else{
                            echo "buy qty: ".$buy_fee_respected_coin;
                        }

                        if($sell_comssion_bnb > 0){
                            echo "<br>sell bnb: ".$sell_comssion_bnb;
                        }else{
                            echo "<br>sell qty: ".$sell_fee_respected_coin;
                        }

                    }else{
                        echo "---";
                    }
                ?>
                                </td>
                                <!-- end 10th <td>-->

                                <td>

                                    <?php 
                    if($value['is_sell_order'] == 'sold'){
                        if(in_array($value['symbol'] , $coin_arrayBTC)){
                            $btcIGain1    =  $value['quantity'] * $value['market_sold_price'];
                            $btcInvest1   =  $value['quantity'] * $value['purchased_price'];
                            echo "<br>₿: ".number_format(($btcIGain1 - $btcInvest), 6);
                            $usdt_worth_showing = number_format(($btcIGain1 - $btcInvest), 6);
                        echo '<pre style="background-color: aquamarine;padding: 2px;"> $ : '.number_format(convert_btc_to_usdt($usdt_worth_showing),2);
                        }else{
                            $usdtGain1     =  $value['quantity'] * $value['market_sold_price'];
                            $usdtInvest1   =  $value['quantity'] * $value['purchased_price'];
                            echo "<br>$: ".number_format(($usdtGain1 - $usdtInvest1), 4);
                        }
                    }else{
                        echo "---";
                    }
                ?>
                                </td>
                                <!-- end 11th <td>-->

                                <td class="text-center">
                                    <?php echo $value['new_atg_allocated_btc']; ?><br><br>
                                    <?php echo '<pre style="background-color: aquamarine;padding: 2px;"> $ : '.number_format(convert_btc_to_usdt($value['new_atg_allocated_btc'])); ?>
                                </td>
                                <!-- end 12th <td>-->

                                <td class="text-center">
                                    <?php echo $value['remaining_btc_allocated']; ?><br><br>
                                    <?php echo '<pre style="background-color: aquamarine;padding: 2px;"> $ : '.number_format(convert_btc_to_usdt($value['remaining_btc_allocated'])); ?>
                                </td>
                                <!-- end 13th <td>-->

                                <td class="text-center">
                                    <?php echo $value['new_atg_allocated_usdt']; ?>
                                </td>
                                <!-- end 14th <td>-->

                                <td class="text-center">
                                    <?php echo $value['remaining_usdt_allocated']; ?>
                                </td>
                                <!-- end 15th <td>-->

                                <td class="text-center">
                                    <?php echo $value['accumulations_data']; ?>
                                </td>
                                <!-- end 16th <td>-->

                                <td>

                                    <?php 
                    if($value['is_sell_order'] == 'sold'){
                        if(in_array($value['symbol'] , $coin_arrayBTC)){
                            echo "<br>₿: ".number_format($value['accumulations'], 6);
                            $usdt_worth_showing = number_format($value['accumulations'], 6);
                        echo '<pre style="background-color: aquamarine;padding: 2px;"> $ : '.number_format(convert_btc_to_usdt($usdt_worth_showing),2);
                        }else{
                            echo "<br>$: ".number_format($value['accumulations'], 4);
                        }
                    }else{
                        echo "---";
                    }
                ?>
                                </td>
                                <!-- end 17th <td>-->

                                <td class="text-center">
                                    <a data-toggle="tooltip" data-placement="top"
                                        title="<?= $value['created_date_hover']; ?>">
                                        <?php echo (!empty($value['created_date']) ?"<span class='label label-success'>" . $value['created_date'] . "</span>" : '');?>
                                    </a>
                                </td>
                                <!-- end 18th <td>-->

                                <td class="text-center">
                                    <a data-toggle="tooltip" data-placement="top"
                                        title="<?= $value['modified_date_hover']; ?>">
                                        <?php echo (!empty($value['modified_date']) ?"<span class='label label-success'>" . $value['modified_date'] . "</span>" : '');?>
                                    </a>
                                </td>
                                <!-- end 19th <td>-->

                                <td class="text-center">
                                    <?php //echo num($market_value333); ?>
                                    <?php 
                    if($value['is_sell_order'] == 'sold'){
                        echo num($value['market_sold_price']);
                    }else{
                        echo num($market_value);
                    }
                ?>
                                </td>
                                <!-- end 20th <td>-->


                                <td class="text-center">
                                    <?php if ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes' && $value['status'] != 'new' && !in_array($market_data, $nanArr)) {?>
                                    <?php //echo '<span class="text-'.$class.'"><b>'.$market_data.'%</b></span>';?>
                                    <span class="text-default"><b>-</b></span>
                                    <?php } else {?>
                                    <span class="text-default"><b>-</b></span>
                                    <?php }?>
                                </td>
                                <!-- end 21th <td>-->

                                <td class="text-center"><span
                                        class="label label-<?=in_array($value['status'], $errArr) || in_array('errors_tab', $temp_filter_by_status) ? 'danger': 'success';?>">
                                        <?php 

                    $temp_f = false;
                if($value['is_sell_order'] == 'yes'){
                        if ($value['status'] == 'LTH') {
                            echo strtoupper("LTH OPEN"); 
                        }elseif(in_array($value['status'], $errArr) && !in_array('errors_tab', $temp_filter_by_status)){
                            echo strtoupper(str_replace('_', ' ', $value['status']));
                        }elseif($value['status'] == 'fraction_submitted_buy'){
                    
                            echo strtoupper(str_replace('_', ' ', $value['status']));
                        
                        }elseif($value['status'] == 'submitted_for_sell'){
                            echo strtoupper(str_replace('_', ' ', $value['status']));
                        }elseif($value['status'] == 'fraction_submitted_sell'){
                            echo strtoupper(str_replace('_', ' ', $value['status']));
                        }elseif($value['status'] == 'canceled'){
                            echo strtoupper(str_replace('_', ' ', $value['status']));
                        }elseif($value['status'] == 'new'){
                            echo strtoupper(str_replace('_', ' ', $value['status']));
                        }elseif($value['status'] == 'cost_submitted_all_for_sell'){
                            echo strtoupper(str_replace('_', ' ', $value['status']));
                        }else{
                            if(empty($value['status'])){
                                
                            }else{
                                if(!in_array('errors_tab', $temp_filter_by_status)){
                                    echo strtoupper('OPEN');
                                }
                            }
                        } 
                }else{
                        if($value['status'] == 'FILLED' && $value['avg_orders_ids'] > 0 && $value['cavg_parent'] == 'yes' && $value['cost_avg'] != 'completed' ){
                            echo strtoupper('OPEN');
                        }else{
                            echo strtoupper(str_replace('_', ' ', $value['status']));
                            $temp_f = '11';
                        }
                    }

                    if(in_array('errors_tab', $temp_filter_by_status) && $temp_f != '11'){
                        echo strtoupper(str_replace('_', ' ', $value['status']));
                    }

                ?>
                                    </span>
                                    <?php if ($value['parent_status'] == 'parent') {
                    echo '<span class="label label-warning">Parent Order</span>';
                } ?>
                                    <span class="custom_refresh" data-id="<?php echo $value['_id']; ?>"
                                        order_id="<?php echo $value['binance_order_id']; ?>"> <i class="fa fa-refresh"
                                            aria-hidden="true"></i> </span>
                                </td>
                                <!-- end 22th <td>-->

                                <td class="text-center">

                                    <!--      <?php
                if(isset($value['cost_avg_array']) && count($value['cost_avg_array']) > 0){ ?>
                  <a href="javascript:void(0);" onclick="open_modal_child_details('<?php echo (string)$value['_id']; ?>','<?php echo $order_exchange; ?>','<?php echo $market_value ?>','<?php echo (string)$value['symbol']; ?>')"><?php echo "(".((count($value['cost_avg_array']) > 0)?count($value['cost_avg_array']):0).")";?> </a>
                <?php } 
                 ?> -->
                    <?php   
                    
                    $avgPL = 0;
                    $total_worth = 0.0;
                    $total_worth_sold = 0.0;
                    $total_worth_sold_other = 0.0;
                    $orders_worth_profit = 0.0;
                    $total_sold = 0;
                    $sold_pl = 0.0;
                    $total_cavg = 0;
                    if(isset($value['cost_avg_array']) && count($value['cost_avg_array']) > 0){

                        // if(isset($_COOKIE['hassam']) && $_COOKIE['hassam'] == 1){
                        //     echo '<pre>'; print_r(count($value['cost_avg_array']));
                        // }

                        $total_cavg = count($value['cost_avg_array']);
                        foreach($value['cost_avg_array'] as $value_cost_avg){
                            if($value_cost_avg["order_sold"] == 'yes'){
                                    $price = $value_cost_avg["filledPriceSell"];
                                    $profitPercentage = (($price-$value_cost_avg["filledPriceBuy"])/$value_cost_avg["filledPriceBuy"])*100;
                                    $total_worth += (float) $value_cost_avg["filledQtyBuy"]*$value_cost_avg["filledPriceBuy"];
                                    $total_worth_sold += (float) $value_cost_avg["filledQtyBuy"]*$value_cost_avg["filledPriceBuy"];
                                    $total_worth_sold_other += (float) $value_cost_avg["filledQtySell"]*$value_cost_avg["filledPriceSell"];
                                    $single_worth = (float) $value_cost_avg["filledQtyBuy"]*$value_cost_avg["filledPriceBuy"];
                                    $pl_new = $profitPercentage*$single_worth;
                                    $avgPL += $pl_new;
                                    $sold_pl += $pl_new;
                                    $total_sold = $total_sold + 1;
                                    
                                }else{
                                   $profitPercentage = (($market_value-$value_cost_avg["filledPriceBuy"])/$value_cost_avg["filledPriceBuy"])*100;
                                   $total_worth += (float) $value_cost_avg["filledQtyBuy"]*$value_cost_avg["filledPriceBuy"];
                                   $single_worth = (float) $value_cost_avg["filledQtyBuy"]*$value_cost_avg["filledPriceBuy"];
                                   $pl_new = $profitPercentage*$single_worth;
                                   $avgPL += $pl_new;
                                }
                        }
                    
                        $profitPercentage_new_for = number_format(($avgPL /(float)$total_worth), 2);
                        if($total_sold > 0){
                            $profitPercentage_new_sold = number_format(($sold_pl /(float)$total_worth_sold), 2); 
                        }else{
                            $profitPercentage_new_sold = 0.0;
                        } 
                        
                        $show_move_button = 0;
                        if ($profitPercentage_new_for > 0) {
                            $class222 = 'success';
                            $show_move_button = 0;
                        } else {
                            $class222 = 'danger';
                            $show_move_button = 1;
                        }
                      
                        echo '<span class="text-' . $class222 . '"> <b> ' . $profitPercentage_new_for . '%</b> </span>';
                    }else{
                        if ($value['market_sold_price'] != "") {
                            
                            $market_sold_price = num($value['market_sold_price']);
                            $pp = num($value['purchased_price']);
                            $profitPercentage = calculateProfitPercentage($pp, $market_sold_price);
                            $show_move_button = 0;
                            if ($market_sold_price > $pp) {
                                $class222 = 'success';
                                $show_move_button = 0;
                            } else {
                                $class222 = 'danger';
                                $show_move_button = 1;
                            }
                            echo '<span class="text-' . $class222 . '"> <b>' . $profitPercentage . '%</b> </span>';
                        } else {
                            if(isset($_COOKIE['hassam']) && $_COOKIE['hassam'] == 1){
                                echo 'Filled or LTH';
                            }
                            if ($value['status'] == 'FILLED' || $value['status'] == 'LTH') {
                                if (1 == 1) {
                                    // $value['is_sell_order'] == 'sold'
                                    $pp = num($value['purchased_price']);
                                    $profitPercentage = calculateProfitPercentage($pp, $market_value);
                                    $show_move_button = 0;
                                    if ($market_value > $pp) {
                                        $class = 'success';
                                         $show_move_button = 0;
                                    } else {
                                        $class = 'danger';
                                        $show_move_button = 1;
                                    }
                                    echo '<span class="text-' . $class . '"><b>' . $profitPercentage  . '%</b></span>';
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
                                <!-- end 23th <td>-->

                                <?php if(isset($value['cost_avg_array']) && count($value['cost_avg_array']) > 0){ ?>
                                <td class="t-long">
                                    <?php 
                // if(isset($_COOKIE['hassam']) && $_COOKIE['hassam'] == 1 && isset($value['cost_avg_array']) && count($value['cost_avg_array']) > 0){
                //     echo '<pre> cost_avg_array'; print_r($value['cost_avg_array']);
                //     echo '<pre> profitPercentage_new_for'; print_r($profitPercentage_new_for);
                //     echo '<pre> profitPercentage_new_sold'; print_r($profitPercentage_new_sold);
                //     echo '<pre> total_sold'; print_r($total_sold);
                //     echo '<pre> total_worth'; print_r($total_worth);
                //     echo '<pre> total_worth_sold'; print_r($total_worth_sold);
                    
                // }
               ?>
                                    <div class="label-row">
                                        <label>Total:</label>
                                        <?php if($profitPercentage_new_for < 0){
                            $css = 'red';
                        }else{
                            $css = 'green';
                        } ?>
                                        <a href="javascript:void(0);"
                                            onclick="open_modal_child_details('<?php echo (string)$value['_id']; ?>','<?php echo $order_exchange; ?>','<?php echo $market_value ?>','<?php echo (string)$value['symbol']; ?>','<?php echo $profitPercentage_new_for; ?>','<?php echo $profitPercentage_new_sold; ?>')"><span
                                                style="color:<?php echo $css; ?>"><?php echo $profitPercentage_new_for.'%'.'  ('.$total_cavg.')'; ?></span></a>
                                    </div>
                                    <div class="label-row">
                                        <label>Sold:</label>
                                        <?php if($profitPercentage_new_sold < 0){
                            $css_2 = 'red';
                        }else{
                            $css_2 = 'green';
                        } ?>
                                        <span
                                            style="color:<?php echo $css_2; ?>"><?php echo $profitPercentage_new_sold.'%'.'  ('.$total_sold.')'; ?><span>
                                    </div>
                                    <div class="label-row">
                                        <label>Total Worth:</label>
                                        <?php $coin_league = substr($value['symbol'],-3);
                                        if($coin_league == 'BTC'){
                                            $cost_avg_worth = number_format(convert_btc_to_usdt((float)$total_worth),2);
                                            $sold_cost_avg_worth = number_format(convert_btc_to_usdt((float)$total_worth_sold),2);
                                            $sold_cost_avg_worth_new = number_format(convert_btc_to_usdt((float)$total_worth_sold_other),2);

                                            $cost_avg_worth = floatval(str_replace(',', '', $cost_avg_worth));
                                            $sold_cost_avg_worth = floatval(str_replace(',', '', $sold_cost_avg_worth));
                                            $sold_cost_avg_worth_new = floatval(str_replace(',', '', $sold_cost_avg_worth_new));

                                        }else{
                                            $cost_avg_worth = number_format((float)$total_worth,2);
                                            $sold_cost_avg_worth =  number_format((float)$total_worth_sold,2);
                                            $sold_cost_avg_worth_new = number_format((float)$total_worth_sold_other,2);

                                            $cost_avg_worth = floatval(str_replace(',', '', $cost_avg_worth));
                                            $sold_cost_avg_worth = floatval(str_replace(',', '', $sold_cost_avg_worth));
                                            $sold_cost_avg_worth_new = floatval(str_replace(',', '', $sold_cost_avg_worth_new));
                                        }  ?>
                                        <?php if($total_sold == count($value['cost_avg_array'])){ ?>
                                        <span><?php echo '$'.$sold_cost_avg_worth_new; ?><span>
                                                <?php }else{ ?>
                                                <span><?php echo '$'.$cost_avg_worth; ?><span>
                                                        <?php } ?>
                                    </div>
                                    <div class="label-row">
                                        <label>Remaining Worth:</label>
                                       <?php 
                                            
                                       ?>
                                        <span><?php echo '$'.((float)$cost_avg_worth-(float)$sold_cost_avg_worth); ?><span>
                                    </div>

                                    <?php if(isset($value['bulk_orders']) && count($value['bulk_orders']) > 0) { ?>
                                    <div class="label-row">
                                        <label>Bulk Orders:</label>
                                        <a href="javascript:void(0);"
                                            onclick="open_modal_bulk_orders_details('<?php echo htmlspecialchars(json_encode($value['bulk_orders']), ENT_QUOTES, 'UTF-8'); ?>')">
                                            <?php echo count($value['bulk_orders']); ?>
                                        </a>
                                    </div>
                                    <?php } ?>


                                    <?php $completion_cavg = ((float)$total_sold/(float)$total_cavg)*100; ?>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar"
                                            aria-valuenow="<?php echo $completion_cavg; ?>" aria-valuemin="0"
                                            aria-valuemax="100"
                                            style="width:<?php echo $completion_cavg ?>%;background: linear-gradient(to right,rgba(0,216,17,1) 0,rgba(0,193,56,1) 100%)!important;">
                                            <?php echo number_format((float)$completion_cavg,2).' %'; ?>
                                        </div>
                                    </div>

                                </td>
                                <?php }else{ ?>
                                <td>N/A</td>
                                <?php }?>
                                <!-- end 24th <td>-->

                                <td>
                                    <?php 
                    if(isset($value['cost_avg_array']) && count($value['cost_avg_array']) > 0){
                        if($value['cost_avg_array'][count($value['cost_avg_array']) - 1]['order_sold'] == 'yes'){
                            $price = $value['cost_avg_array'][count($value['cost_avg_array']) - 1]["filledPriceSell"];
                            $profitPercentage = (($price-$value['cost_avg_array'][count($value['cost_avg_array']) - 1]["filledPriceBuy"])/$value['cost_avg_array'][count($value['cost_avg_array']) - 1]["filledPriceBuy"])*100;
                                echo number_format($profitPercentage, 2).'%';
                                if(gettype($value['cost_avg_array'][count($value['cost_avg_array']) - 1]['sellTimeDate']) == 'string'){
                                    $buy_date = $value['cost_avg_array'][count($value['cost_avg_array']) - 1]['sellTimeDate'];
                                    // $show_date = $buy_date->toDateTime()->format("Y-m-d g:i:s A");
                                }else if($value['cost_avg_array'][count($value['cost_avg_array']) - 1]['sellTimeDate'] != ''){
                                    $buy_date = $value['cost_avg_array'][count($value['cost_avg_array']) - 1]['sellTimeDate'];
                                    $buy_date = new DateTime($buy_date->toDateTime()->format("c"));
                                    $buy_date->setTimeZone(new DateTimeZone('PKT'));
                                    $show_date = $buy_date->format("Y-m-d g:i:s A");

                                    $buy_date = time_elapsed_string($value['cost_avg_array'][count($value['cost_avg_array']) - 1]['sellTimeDate']->toDateTime()->format("Y-m-d g:i:s A") ,  new DateTimeZone('PKT'));
                                }else{
                                    $buy_date = 'N/A';
                                    // $show_date = $buy_date;
                                }
                                $var = 'Last Sold :';
                            }else{
                               $profitPercentage = (($market_value-$value['cost_avg_array'][count($value['cost_avg_array']) - 1]["filledPriceBuy"])/$value['cost_avg_array'][count($value['cost_avg_array']) - 1]["filledPriceBuy"])*100;
                               echo number_format($profitPercentage, 2).'%<br>';
                               if(gettype($value['cost_avg_array'][count($value['cost_avg_array']) - 1]['buyTimeDate']) == 'string'){
                                    $buy_date = $value['cost_avg_array'][count($value['cost_avg_array']) - 1]['buyTimeDate'];
                                    // $show_date = $buy_date->toDateTime()->format("Y-m-d g:i:s A");
                                }else if($value['cost_avg_array'][count($value['cost_avg_array']) - 1]['buyTimeDate'] != ''){
                                    $buy_date = $value['cost_avg_array'][count($value['cost_avg_array']) - 1]['buyTimeDate'];
                                    $buy_date = new DateTime($buy_date->toDateTime()->format("c"));
                                    $buy_date->setTimeZone(new DateTimeZone('PKT'));
                                    $show_date = $buy_date->format("Y-m-d g:i:s A");
                                    $buy_date = time_elapsed_string($value['cost_avg_array'][count($value['cost_avg_array']) - 1]['buyTimeDate']->toDateTime()->format("Y-m-d g:i:s A") , new DateTimeZone('PKT'));
                                }else{
                                    $buy_date = 'N/A';
                                    $show_date = $buy_date;
                                }
                                $var = 'Last Buy :';
                            }
                            ?>
                                    <?php echo $var."<span class='label label-success' title='".$show_date."'>" . $buy_date  . "</span>";?>

                                    <?php } ?>
                                </td>
<!-- 25 <td> end -->
                                <td>

                                <?php 
                                    
                                ?>




                                    <?php 
                                        $profitPercentage_new_for_child = 0.0;

                                        if(isset($value['cost_avg_array']) && count($value['cost_avg_array']) > 0) {
                                            $latestSellData = null;
                                            $latestBuyData = null;
                                            $latestSellDate = 0;
                                            $latestBuyDate = 0;
                                        
                                            foreach($value['cost_avg_array'] as $data) {
                                                if($data['order_sold'] == 'yes' && isset($data['sellTimeDate']) && $data['sellTimeDate'] > $latestSellDate) {
                                                    $latestSellData = $data;
                                                    $latestSellDate = $data['sellTimeDate'];
                                                }
                                                elseif($data['order_sold'] == 'no' && isset($data['buyTimeDate']) && $data['buyTimeDate'] > $latestBuyDate) {
                                                    $latestBuyData = $data;
                                                    $latestBuyDate = $data['buyTimeDate'];
                                                }
                                            }
                                        
                                            // Calculate profitPercentage_new_for_child based on the latest sell or buy
                                            if($latestSellData !== null && ($latestBuyData === null || $latestSellDate > $latestBuyDate)) {
                                                $price = $latestSellData["filledPriceSell"];
                                                $profitPercentage = (($price - $latestSellData["filledPriceBuy"]) / $latestSellData["filledPriceBuy"]) * 100;
                                                $profitPercentage_new_for_child = number_format($profitPercentage, 2);
                                            }
                                            elseif($latestBuyData !== null) {
                                                $profitPercentage = (($market_value - $latestBuyData["filledPriceBuy"]) / $latestBuyData["filledPriceBuy"]) * 100;
                                                $profitPercentage_new_for_child = number_format($profitPercentage, 2);
                                            }
                                        }
                                        
                                    // if($value['cost_avg_array'][count($value['cost_avg_array']) - 1]['order_sold'] == 'yes'){
                                        $latest_trade_date = 'N/A';
                                        if(isset($value['cost_avg_array']) && count($value['cost_avg_array']) > 0){
                                            $loop_iteration = 1;
                                            foreach($value['cost_avg_array'] as $cavg_record){
                                               
                                                

                                                if($loop_iteration == 1){
                                                    $latest_buy_date = $value['cost_avg_array'][count($value['cost_avg_array']) - 1]['buyTimeDate'];
                                                    $latest_trade_date = $latest_buy_date;

                                                   
                                                }

                                                if ($profitPercentage_new_for_child > 0) {
                                                    $class222 = 'success';
                                                
                                                } else {
                                                    $class222 = 'danger';
                                                    
                                                }

                                                if($cavg_record['sellTimeDate'] > $latest_trade_date){
                                                    $latest_trade_date = $cavg_record['sellTimeDate'];
                                                }
                                                $loop_iteration++;
                                                
                                            }
                                        }
                

                                        if(is_string($latest_trade_date)){ ?>
                                            <span
                                        class="label label-success"><?php echo $latest_trade_date; ?></span>
                                       <?php }elseif(!is_string($latest_trade_date) && $latest_trade_date != null){
                                        $latest_trade_date = new DateTime($latest_trade_date->toDateTime()->format("c"));
                                        $latest_trade_date->setTimeZone(new DateTimeZone('PKT'));
                                        // $latest_trade_date = $latest_trade_date->toDateTime()->format("c");
                                        ?>
<!-- // hello -->
                                    <span
                                        class="label label-success"><?php echo $latest_trade_date->format("Y-m-d g:i:s A"); ?></span>
                                        <b>P/L(%) : <span class="text-<?php echo $class222; ?>">   <?php echo $profitPercentage_new_for_child ?> </span></b>
                                    <?php } 
                                    // } ?>


                                </td>

                                <!-- latest sold time end -->

                                <?php if(isset($value['market_sold_price']) && $value['market_sold_price'] != ''){ ?>
                                <td><?php echo $value['market_sold_price']; ?></td>
                                <?php }else{ ?>
                                <td>N/A</td>
                                <?php } ?>


                                <td class="text-center">
                                    <?php 
                    if($value['trigger_type'] == 'no' && $value['status'] == 'LTH'){
                        $target_profit = $value['lth_profit'];
                    }

                    if($value['trigger_type'] == 'no' && $value['status'] == 'FILLED'){
                        $target_profit = $value['sell_profit_percent'];
                    }

                    if($value['trigger_type'] != 'no' && $value['status'] == 'LTH'){
                        $target_profit = $value['lth_profit'];
                    }

                    if($value['trigger_type'] != 'no' && $value['status'] == 'FILLED'){
                        $target_profit = $value['defined_sell_percentage'];
                    }
                    
                    if($value['parent_status'] == 'parent'){
                        $target_profit = $value['defined_sell_percentage'];
                    }
                ?>
                                    <?php echo (!in_array($target_profit, $nanArr)? $target_profit : '-'); ?>
                                </td>


                                <td class="text-center">
                                    <?php
                    if(!in_array($target_profit, $nanArr) && !in_array($profitPercentage, $nanArr) && $value['is_sell_order'] == 'sold'){
                        
                        if($value['is_lth_order'] == 'yes' && !in_array($value['lth_profit'], $nanArr)){
                            $target_profit = $value['lth_profit'];
                        }

                        if($profitPercentage >= $target_profit){
                            $class = 'success';
                            $slippage = ($profitPercentage - $target_profit); 
                            $slippage = round($slippage, 3).'%';
                        }else if($profitPercentage < $target_profit){
                            $class = 'danger';
                            $slippage = ($target_profit - $profitPercentage);
                            $slippage = round($slippage, 3) . '%';
                        }else{
                            $class = 'default';
                            $slippage = '-';
                        }
                    }else{
                        $class = 'default';
                        $slippage = '-';
                    }
                    echo '<span class="text-'.$class.'"><b>'.$slippage.'</b></span>';
                ?>
                                </td>


                                <td class="text-center">
                                    <?php
                if(isset($value['sell_market_price']) && $value['is_sell_order'] == 'sold' && $value['sell_market_price'] !="" && !is_string($value['sell_market_price'])){
                    $val1 = $value['market_sold_price'] - $value['sell_market_price']; 
                    $val2 = ($value['market_sold_price'] + $value['sell_market_price'])/ 2;
                    $slippageOrignalPercentage = ($val1/ $val2) * 100;
                    $slippageOrignalPercentage = round($slippageOrignalPercentage, 3) . '%';

                    if($slippageOrignalPercentage >= 0 ){
                        $class = 'success';
                    }else{
                        $class = 'danger';
                    }
                }else{
                    $class = 'default';
                    $slippageOrignalPercentage = '-';
                }
                echo '<span class="text-'.$class.'"><b>'.$slippageOrignalPercentage.'</b></span>';
                ?>
                                </td>


                                <td class="text-center">
                                    <?php
                    if(isset($value['order_send_time']) && isset($value['sell_date']) && !empty($value['order_send_time']) && !empty($value['sell_date']) && $value['is_sell_order'] == "sold"){
                        $difference = $value['sell_date'] - $value['order_send_time'];

                        $filledTime     = strtotime($value['sell_date']->toDateTime()->format('Y-m-d H:i:s'));
                        $orderSendTime  = 0;//strtotime($value['order_send_time']->toDateTime()->format('Y-m-d H:i:s'));

                        // $filledTime    = strtotime($filledTime);
                        // $orderSendTime = strtotime($orderSendTime);
                         $differenceInSeconds = ($filledTime - $orderSendTime).": sec";

                        
                    }else{
                        $differenceInSeconds = '-';
                    }
                    echo '<span><b>'.$differenceInSeconds.'</b></span>';
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
                            if($value['is_sell_order'] == 'sold' && $value['is_lth_order'] == 'yes'){
                                echo '<button class="btn btn-warning" style="font-size:12px">LTH</button>';
                            }else{
                                echo '<button class="btn btn-success" style="font-size:12px">Normal</button>';
                            } 
                        ?>
                                    <?php
                }
            ?>
                                </td>

                                <td class="text-center">
                                    <?php echo ($value['lth_profit'] != '' && !in_array(trim($value['lth_profit']), $nanArr) ? $value['lth_profit'] : '-'); ?>
                                </td>

                                <td class="text-center">
                                    <span
                                        class="text-<?php echo $value['iniatial_trail_stop'] > $value['purchased_price'] ? 'success' : 'danger' ; ?>">
                                        <?php 
                        if($value['loss_percentage'] != '' && !in_array(trim($value['loss_percentage']), $nanArr)){
                            $loss_percentage_temp = $value['loss_percentage'];
                            $loss_percentage_temp .= $value['iniatial_trail_stop'] > $value['purchased_price'] ? " (+)" : " (-)";
                        }else{
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
                    if(in_array($value['status'], $errArr)) {

                        echo '<span class="label label-danger">' . str_replace('_', ' ', $value['status']) . '</span>';
                        echo '<button class="btn btn-sm btn-warning change_order_error_status" onclick="remove_order_error(this,\''.$value['_id'].'\',\''.$order_exchange.'\')">Remove Error</button>';
                    }else {
                        if ($value['status'] == 'FILLED') {
                            if ($value['is_sell_order'] == 'yes') {
                                if(!empty($value['sell_order_id'])){
                                    $sell_status = $this->mod_buy_orders->is_sell_order_in_error_status($value['sell_order_id']);
                                    $sell_status_submit = $this->mod_buy_orders->is_sell_order_in_submitted_status($value['sell_order_id']);
                                }else{
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
                                    echo ' <button class="btn btn-danger" onclick="sell_order_now(this,\''.$value['_id'].'\',\''.$order_exchange.'\')">Sell Now</button>';
                                    
                                    // echo '<button class="btn btn-danger sell_now_btn" id="' . $value['_id'] . '" data-id="' . $value['sell_order_id'] . '" market_value="' . num((float) $market_value) . '" quantity="' . $value['quantity'] . '" symbol="' . $value['symbol'] . '" order_type="' . $value['order_type'] . '"  buy_order_id="' . $value['_id'] . '">Sell Now</button>';
                                }
                            } elseif ($value['is_sell_order'] == 'sold' && !isset($value['count_avg_order']) ) { 
                                echo ' <button class="btn btn-success">Sold</button> '.($value['is_manual_sold'] == 'yes' ? '<span class="label label-info">Sold Manually</span>' : '');
                            } elseif($value['status'] == 'FILLED' && $value['count_avg_order'] > 0 && $value['cavg_parent'] == 'yes' && $value['cost_avg'] != 'completed' ){
                                echo ' <button class="btn btn-success">Parent Sold</button> '.($value['is_manual_sold'] == 'yes' ? '<span class="label label-info">Sold Manually</span>' : '');
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

                        }else if ($value['status'] == 'FILLED' && ($value['is_sell_order'] == 'yes' || $value['is_sell_order'] == 'sold') && !empty($value['resume_order_id'])) {
                            
                            echo ' <span class="label label-success">Resume Child</span>';

                        }else if ($value['is_sell_order'] == 'resume_pause' && empty($value['resume_order_arr'])) {
                            
                            echo ' <span class="label label-warning">Resumed</span>';

                        }else if ($value['is_sell_order'] == 'resume_pause' && !empty($value['resume_order_arr'])) {

                            echo ' <span class="label label-info">In Progress</span>';

                        }else if ($value['is_sell_order'] == 'pause') {

                            echo ' <span class="label label-success">Paused</span>';

                        }
                        
                        if ($value['mapped_order'] == 'yes') {
                            echo'<span class="label label-warning">Mapped</span>';
                        }
                        
                        if ($value['script_fix'] != '' && $value['script_fix'] == 1) {

                            echo ' <span class="label label-warning">Script Fixed</span>';
                        }else if($value['script_fix'] != '' && $value['script_fix'] == 0 && $logged_id_user_id == '5c0915befc9aadaac61dd1b8'){
                            
                            echo ' <span class="label label-warning">Script Ignorded</span>';
                        }
                    }

                    if(!empty($value['buy_on_buy_hit'])){
                        echo ' <span class="label label-warning">Buy Hit Yes</span>';
                    } 

                    if(!empty($value['sell_on_sell_hit'])){
                        echo ' <span class="label label-warning">Sell Hit Yes</span>';
                    } 
                    if(!empty($value['buy_trail_check'])){
                        echo ' <span class="label label-warning">Trail Buy Yes</span>';
                    } 

                    if(!empty($value['buy_trail_interval'])){
                        echo ' <span class="label label-warning">Buy Trail Interval Yes</span>';
                    } 
                    if(!empty($value['deep_price_on_off'])){
                        echo ' <span class="label label-warning">Deep Price Yes</span>';
                    } 
                    
                    if(!empty($value['shifted_order_label'])){
                        echo ' <span class="label label-warning">shifted</span>';
                    }  

                    if($value['quantity_issue'] != ''){
                        echo ' <span class="label label-warning">Quantity Issue</span>';
                    }
                    if($value['cost_avg'] == 'yes'){
                        echo ' <span class="label label-warning">Cost Avg (Yes)</span>';
                    }
                    if($value['cost_avg'] == 'completed'){
                        echo ' <span class="label label-warning">Cost Avg (Completed)</span>';
                    }
                    if($value['cost_avg'] == 'taking_child'){
                        echo ' <span class="label label-warning">Cost Avg (Taking Child)</span>';
                    }
                    if ($value['pick_parent'] == 'yes') {
                        echo ' <span class="label label-success">Pick Parent (Yes)</span>';
                    }
                    if ($value['pick_parent'] == 'no') {
                        echo ' <span class="label label-warning">Pick Parent (No)</span>';
                    }
                    if (!empty($value['cancel_hour'])) {
                        echo ' <span class="label label-warning">cancel Hour Yes </span>';
                    }
                    if ($value['cavg_parent'] == 'yes') {
                        echo ' <span class="label label-info">Cost Avg Parent</span>';
                    }
                    if (!empty($value['parent_pause'])){
                        echo ' <span class="label label-info">Parent Pause(Cost Avg & Child)</span>';
                    }
                    if (!empty($value['pause_by'])){
                        echo ' <span class="label label-info">Pause By(Cost Avg Merge)</span>';
                    }

                    if (!empty($value['shifted_order_label']) && $value['shifted_order_label'] == 'shifted'){
                        echo ' <span class="label label-info">Shifted Order</span>';
                    }



                ?>
                                    </div>
                                </td>
                                <td class="text-center"> <?php
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
                                    <span style="font-size: 10px"
                                        class="text-<?php echo $color; ?>"><b><?php echo number_format($profit_per, 2); ?>%</b></span>
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
                                    <span style="font-size: 10px"
                                        class="text-<?php echo $color; ?>"><b><?php echo number_format($profit_per, 2); ?>%</b></span>
                                    <?php
            } else {echo "-";}
            ?>
                                </td>
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
                                    <span style="font-size: 10px"
                                        class="text-<?php echo $color; ?>"><b><?php echo number_format($profit_per, 2); ?>%</b></span>
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
                                    <span style="font-size: 10px"
                                        class="text-<?php echo $color; ?>"><b><?php echo number_format($profit_per, 2); ?>%</b></span>
                                    <?php
        } else {echo "-";} ?>
                                </td>
                                <td class="text-center"><?= $value['admin']->username?></td>
                                <td class="text-center">
                                    <?= $value['admin']->first_name." ".$value['admin']->last_name;?></td>
                                <td class="text-center"><?= $value['admin']->trading_ip;?></td>
                                <!-- <td><?="Buy Rule: <span class='badge badge-info'>" . $value['buy_rule_number'] . "</span> <br> Buy Via : <span class='badge badge-danger'>" . (($value['is_manual_buy'] == 'yes') ? "Manual" : "Auto") . " </span>";?></td>
              <td><?="Sell Rule: <span class='badge badge-info'>" . $value['sell_rule_number'] . "</span> <br> Sell Via: <span class='badge badge-danger'>" . (($value['is_manual_sold'] == 'yes') ? "Manual" : "Auto") . " </span>";?><br>
                <small>if Sell Rule is 0 the its sold by Stop Loss</small>
              </td>             -->


                                <td class="center">

                                    <button
                                        class="btn btn-xs btn-warning <?php echo ($filter_user_data['filter_by_exchange'] != 'binance' ? 'view_order_details_exchange' : 'view_order_details'); ?>"
                                        title="View Order Details" data-id="<?php echo $value['_id']; ?>"><i
                                            class="fa fa-eye"></i></button>

                                    <button type="button" class="btn btn-xs btn-primary viewadmininfo"
                                        data-toggle="modal" data-target="#largeShoes"
                                        id="<?php echo $value['admin_id']; ?>"><i class="fa fa-eye"></i></button>

                                    <?php 
            if( $value['status'] == 'error'){
                ?>
                                    <!-- <a href="<?=SURL;?>admin/buy_orders/edit-buy-order/<?=$value['_id'];?>" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-link"></i></a> -->
                                    <?php
            }else{
                ?>
                                    <!-- <a href="<?=SURL;?>admin/sell_orders/edit-order/<?=$value['sell_order_id'];?>" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-link"></i></a> -->
                                    <?php
            }
            ?>

                                    <?php if($value['trigger_type'] != 'no' && $value['parent_status'] != 'parent'){ ?>
                                    <!-- <a href="<?=SURL;?>admin/buy_orders/edit-buy-trigger-order/<?=$value['buy_parent_id'];?>" target="_blank" class="btn btn-xs btn-warning"><i class="fa fa-external-link"></i></a> -->
                                    <?php }else if($value['trigger_type'] == 'no'){ ?>
                                    <!-- <a href="<?=SURL;?>admin/sell_orders/edit-order/<?=$value['sell_order_id'];?>" target="_blank" class="btn btn-xs btn-warning"><i class="fa fa-external-link"></i></a> -->
                                    <?php }else { ?>
                                    <!-- <a href="<?=SURL;?>admin/buy_orders/edit-buy-trigger-order/<?=$value['buy_parent_id'];?>" target="_blank" class="btn btn-xs btn-warning"><i class="fa fa-external-link"></i></a> -->
                                    <?php } ?>

                                    <!-- https://trading.digiebot.com Detail page links  -->
                                    <?php if($value['trigger_type'] =='no'){ ?>
                                    <!-- Edit Auto Order -->
                                    <a href="https://trading.digiebot.com/buy-orders/edit-buy-manual-order/<?=$value['_id'];?>"
                                        target="_blank" title="Edit Manual Order" class="btn btn-xs btn-warning"><i
                                            class="fa fa-external-link"></i></a>
                                    <?php } ?>

                                    <?php if($value['trigger_type'] !='no'){ ?>
                                    <?php if($value['parent_status'] == 'parent'){ ?>
                                    <!-- Edit Parent Order -->
                                    <a href="https://trading.digiebot.com/buy-orders/edit-parent-order/<?=$value['_id'];?>"
                                        target="_blank" title="Edit Parent Order" class="btn btn-xs btn-warning"><i
                                            class="fa fa-external-link"></i></a>
                                    <?php }else{ ?>
                                    <!-- Edit Auto Order -->
                                    <a href="https://trading.digiebot.com/buy-orders/edit-auto-order/<?=$value['_id'];?>"
                                        target="_blank" title="Edit Auto Order" class="btn btn-xs btn-warning"><i
                                            class="fa fa-external-link"></i></a>
                                    <?php } ?>
                                    <?php } ?>



                                    <?php if(!empty($value['buy_parent_id'])){ ?>
                                    <!-- Edit Parent Order -->
                                    <a href="https://trading.digiebot.com/buy-orders/edit-parent-order/<?=$value['buy_parent_id'];?>"
                                        target="_blank" title="Edit Parent Order" class="btn btn-xs btn-warning"><i
                                            class="fa fa-external-link"></i></a>
                                    <?php } ?>
                                    <?php if(count($value['cost_avg_array']) > 0){ 
                $cost_avg_array = (array)$value['cost_avg_array']; ?>
                                    <!-- Edit Parent Order -->

                                    <!--  <a href="https://trading.digiebot.com/edit-auto-order/<?=(string)$cost_avg_array[0]['buy_order_id'];?>" target="_blank" title="Edit Cost avg Parent Order" class="btn btn-xs btn-warning"><i class="fa fa-external-link"></i></a> -->
                                    <?php } ?>
                                    <?php if(in_array('QUANTITY_ERROR', $temp_filter_by_status)){ ?>
                                    <a href="#" title="Edit Order" class="btn btn-xs btn-warning"
                                        onclick="quantity_error_issue(<?php echo "'".$value['_id']."'";?>);"
                                        style="margin-top: 2px;">Ready for Buy</a>
                                    <?php } ?>
                                    <a href="#" title="Edit Parent Order" class="btn btn-xs btn-warning"
                                        onclick="avg_sell_price_upd(<?php echo "'".$value['_id']."'";?>,<?php echo "'".$value['avg_sell_price']."'";?>);"
                                        style="margin-top: 2px;">Upd Avg Sell price</a>
                                    <?php //if($show_move_button == 1) { ?>
                                    <a href="#" title="Move to Buy again" class="btn btn-xs btn-success"
                                        onclick="move_request(<?php echo "'".$value['_id']."'";?>);"
                                        style="margin-top: 2px;">Move order to buy</a>

                                    <?php if(!isset($value['moved_to_cost_avg_loss_sell'])){ ?>
                                    <!-- Edit Parent Order -->
                                    <a href="#" title="Edit Parent Order" class="btn btn-xs btn-warning"
                                        onclick="merge_request(<?php echo "'".$value['_id']."'";?>,<?php echo "'".$value['admin_id']."'";?>,<?php echo "'".$value['symbol']."'";?>);"
                                        style="margin-top: 2px;">Move to CA</a>
                                    <?php } ?>

                                    <?php //} ?>
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

                </div>
                <div id="bottom_anchor"></div>
                <!-- // Table END -->

            </div>
            <div class="row bottom-box">
                <div class="pagination" style="margin-bottom: 0px;">
                    <?php echo $pagination; ?>
                </div>
                <div class="total-count">
                    <span style="
    padding: 12px 12px 12px 0;
    font-size: 14px;
    font-weight: bolder;
    color: red;
">
                        Total Count:
                    </span>
                    <span>
                        <?php echo $total_count; ?>
                    </span>
                </div>
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
    width: fit-content;
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
<div class="modal" id="avg_sell_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">

    <div class="modal-dialog modal-sm">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

                <h4 class="modal-title" id="modalLabelLarge">Update Avg Sell price</h4>

            </div>

            <div class="modal-body" id="mymodalresp">

                <form>
                    <div class="row">
                        <label>Avg sell price old</label>
                        <input type="text" id="avg_sell_price_old" name="avg_sell_price_old" class="form-control">
                    </div>
                    <div class="row">
                        <label>Avg sell price new</label>
                        <input type="text" name="avg_sell_price_new" id="avg_sell_price_new" class="form-control">
                    </div>
                    <input type="hidden" name="avg_sell_price_id" id="avg_sell_price_id">
                    <input type="hidden" name="exchange_avg_upd" id="exchange_avg_upd">
                    <div class="row" style="margin-top:5px">
                        <button type="button" class="btn btn-success" onclick="update_sell_price_avg();">Submit</button>
                    </div>



                </form>

            </div>

        </div>

    </div>

</div>

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

<div class="modal fade" id="bulkOrdersModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 800px !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bulk Orders</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Placeholder for the table -->
                <table id="bulkOrdersTable" class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Symbol</th>
                            <th>Order Sold</th>

                            <th>Sell Price</th>
                            <th>Quantity</th>
                            <th>Buy Date</th>
                            <th>Sell Date</th>
                            <th>P/L (%)</th>

                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="child_avg_detail" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge"
    aria-hidden="true">

    <div class="modal-dialog cavg_modal">

        <div class="modal-content cavg_modal_content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

                <h4 class="modal-title" id="modalLabelLarge">Cost Average Order</h4>

            </div>

            <div class="modal-body">

                <div class="widget">
                    <div class="widget-head text-center" style="background-color: orange; color: white;"><strong>Cost
                            Avg Details</strong>
                    </div>
                    <div class="widget-body" id="cavgmodalresp">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
            </div>
        </div>
    </div>
</div>

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


</script>
<script>
$(function() {


    $("#example").find("tr").each(function(index, element) {

        // $("td").filter(function() {
        //     return $(this).text() === "SELL";
        // }).parent().addClass("failed");
        // $("td").filter(function() {
        //     return $(this).text() === "BUY";
        // }).parent().addClass("success-custom");

        // console.log(element); 

        var profit = jQuery(element).find("td:eq(16)").text();
        var target = jQuery(element).find("td:eq(20)").text();
        var status = jQuery(element).find("td:eq(27)").text();
        var percentage = profit.split("%")
        var per = percentage[0];
        var current_profit = parseFloat(per);
        var target_profit = parseFloat(target);
        // console.log("status" , status , current_profit, "<============>" , target_profit);
        status = $.trim(status)
        // if((typeof(current_profit) !== 'undefined' || typeof(target_profit) !== 'undefined') && status != 'Sold'){
        if ((typeof(current_profit) !== 'undefined' || typeof(target_profit) !== 'undefined') && !status
            .includes('Sold')) {
            if (current_profit > target_profit) {
                // console.log(current_profit, "<============>" , target_profit);
                $(element).addClass("failed")
            }
        }
    });

    $(document).ready(function() {


        //Only needed for the filename of export files.
        //Normally set in the title tag of your page.document.title = 'Simple DataTable';
        //Define hidden columns
        // var hCols = [3, 4];
        var hCols = [3, 4, 6, 13, 14, 15, 16, 20, 26, 27, 31, 32, 33,  34, 35, 37, 38];
        // DataTable initialisation
        $('#example').DataTable({
            // "order": [],
            "ordering": false,
            "paging": false,
            "dom": "<'row'<'col-sm-4'B><'col-sm-2'l><'col-sm-6'p<br/>i>>" +
                "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12'p<br/>i>>",

            "columnDefs": [{
                'targets': [3, 4, 6, 13, 14, 15, 16, 8, 9, 10, 20, 26, 27, 28, 29, 30, 31, 32, 33, 35, 36, 38, 39],
                'visible': false,
                'searchable': true
            }, ],

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
            }],
            oLanguage: {
                oPaginate: {
                    sNext: '<span class="pagination-default">&#x276f;</span>',
                    sPrevious: '<span class="pagination-default">&#x276e;</span>'
                }
            },
            "initComplete": function(settings, json) {
                // Adjust hidden columns counter text in button -->
                $('#example').on('column-visibility.dt', function(e, settings, column,
                    state) {
                    var visCols = $('#example thead tr:first th').length;
                    //Below: The minus 2 because of the 2 extra buttons Show all and Restore
                    var tblCols = $(
                            '.dt-button-collection li[aria-controls=example] a')
                        .length - 2;
                    $('.buttons-colvis[aria-controls=example] span').html(
                        'Columns (' + visCols + ' of ' + tblCols + ')');
                    e.stopPropagation();
                });
            }
        });
    });

});
</script>


<script>
$(function() {
    $("#filter_by_coin").multiselect({
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth: '100%'
    });
});
$(document).ready(function() {
    $('#filter_by_status').multiselect({
        nonSelectedText: 'Select Status',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth: '100%'
    });
});

$(document).ready(function() {
    $('#filter_by_level').multiselect({
        nonSelectedText: 'Select Level',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth: '100%'
    });
});


$(document).ready(function(e) {

    var query = $("#filter_by_trigger").val();

    if (query == 'barrier_trigger') {

        $("#barrier_t").show();

        $("#barrier_p_t").hide();

    } else if (query == 'barrier_percentile_trigger') {

        $("#barrier_t").hide();

        $("#barrier_p_t").show();

    } else {

        $("#barrier_t").hide();

        $("#barrier_p_t").hide();

    }

});

$("body").on("click", ".glassflter", function(e) {

    var query = $("#filter_by_name").val();

    window.location.href = "<?php echo SURL; ?>/admin/users/?query=" + query;

});





$("body").on("change", "#filter_by_trigger", function(e) {

    var query = $("#filter_by_trigger").val();

    if (query == 'barrier_trigger') {

        $("#barrier_t").show();

        $("#barrier_p_t").hide();

    } else if (query == 'barrier_percentile_trigger') {

        $("#barrier_t").hide();

        $("#barrier_p_t").show();

    } else {

        $("#barrier_t").hide();

        $("#barrier_p_t").hide();

    }

});



$("body").on("click", ".viewadmininfo", function(e) {

    var user_id = $(this).attr('id');

    $.ajax({

        url: "<?php echo SURL; ?>admin/reports/get_user_info",

        data: {
            user_id: user_id
        },

        type: "POST",

        success: function(response) {

            $("#mymodalresp").html(response);

        }

    });

});



$("body").on("click", ".view_order_details", function(e) {

    var order_id = $(this).attr("data-id");
    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/get_buy_order_details_ajax',
        'type': 'POST',
        'data': {
            order_id: order_id
        },
        'success': function(response) {
            $('#response_order_details').html(response);
            $("#modal-order_details").modal('show');
        }
    });

});

$("body").on("click", ".view_order_details_exchange", function(e) {

    var order_id = $(this).attr("data-id");
    var exchange =
        "<?php echo ($filter_user_data['filter_by_exchange'] != 'binance' ? $filter_user_data['filter_by_exchange'] : ''); ?>";
    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/get_buy_order_details_exchange_ajax',
        'type': 'POST',
        'data': {
            order_id: order_id,
            exchange: exchange
        },
        'success': function(response) {

            console.log(response);
            $('#response_order_details').html(response);
            $("#modal-order_details").modal('show');
        }
    });

});
</script>

<script>
$(function() {

    availableTags = [];

    $.ajax({

        'url': '<?php echo SURL ?>admin/reports/get_all_usernames_ajax',

        'type': 'POST',

        'data': "",

        'success': function(response) {

            availableTags = JSON.parse(response);



            $("#username").autocomplete({

                source: availableTags

            });

        }

    });



});





//Custom switcher by Afzal

jQuery("body").on("change", "#af-swith-asc", function() {

    if (jQuery(".af-switcher-default").hasClass("active")) {

        jQuery(".af-switcher-default").removeClass("active");
        jQuery(this).val("ASC");

    } else {

        jQuery(".af-switcher-default").addClass("active");
        jQuery(this).val("DESC");
    }

});

jQuery("body").on("click", ".af-switcher-default", function() {

    if (jQuery(".af-switcher-default").hasClass("active")) {

        jQuery(".af-switcher-default").removeClass("active");

    } else {

        jQuery(".af-switcher-default").addClass("active");

    }

});

jQuery("body").on("change", ".af-cust-radio", function() {

    jQuery(".af-form-group-created").addClass("active");

});

//----End--------
</script>


<!-- added by 19/8/19 -->
<script type="text/javascript">
$("body").on("click", ".sell_now_btn", function(e) {

    var id = $(this).attr('data-id');
    var market_value = $(this).attr('market_value');
    var quantity = $(this).attr('quantity');
    var symbol = $(this).attr('symbol');
    var order_type = $(this).attr('order_type');
    var buy_order_id = $(this).attr('buy_order_id');

    $("#" + id).html(
        '<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

    if (order_type != 'limit_order') {
        // sell_order(id,market_value,quantity,symbol);

        sell_market_order(id, market_value, quantity, symbol, buy_order_id);

    } else {
        limit_order_cancel(id, market_value, quantity, symbol, buy_order_id);
    }

});


//%%%%%%%%%%%%%%%%%%%%%%%5 Sell limit order %%%%%%%%%%%%%%%%%%%%%%%%%%
function sell_market_order(sell_id, market_value, quantity, symbol, buy_order_id) {

    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/check_status_of_limit_order',
        'type': 'POST',
        'data': {
            sell_id: sell_id,
            symbol: symbol
        },
        'success': function(response) {
            var rp = JSON.parse(response);
            var resp = rp['status'];
            var current_market_price = rp['market_price'];


            $("#" + sell_id).html('Sell Now');

            if (resp == 'error') {
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
                            action: function() {}
                        },
                        close: function() {}
                    }
                });
                //%%%%%%%%%%%%%%%End of  error status%%%%%%%%%%%
            } else if (resp == 'submitted') {
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
                            action: function() {

                            }
                        },
                        close: function() {}
                    }
                });
                //%%%%%%%%%%%%%%%%%%% End of submitted status %%%%%%%%%%%%%%%%%%%%%
            } else if (resp == 'new' || resp == 'LTH') {

                //%%%%%%%%%%%%%%%%%%%%%%%%%%%%5  New Status%%%%%%%%%%%%%%%%%%%%%%%%%
                var content_html = 'Select from below to sell this order\
                            <div class="">\
                            <hr>\
                            <form>\
                            <div class="form-group">\
                            <div class="row"><div class="col-xs-6">\
                            <label>Current Market Price</label>\
                            <input class="form-control" step="any" type="number" name="cu_m_price_' + sell_id +
                    '" value="' + current_market_price + '" disabled>\
                            </div><div class="col-xs-6">\
                            <label>Sell Price</label>\
                            <input class="form-control" step=".00000001" type="number" name="sell_price_' + sell_id +
                    '" value="' + current_market_price + '" id="sell_price_' + sell_id + '">\
                            </div>\
                            </div>'

                // <div class="radio">\
                // <label><input type="radio" name="typ_new_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\
                // </div>\
                // <div class="radio">\
                // <label><input type="radio" name="typ_new_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\
                // </div>

                content_html += '<div class="radio ">\
                            <label><input type="radio" name="typ_new_' + sell_id + '" value="m_current" checked>Fire market order on current market price</label>\
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
                            action: function() {

                                var order_type = $("input[name='typ_new_" + sell_id +
                                    "']:checked").val();;
                                var sell_price = $('#sell_price_' + sell_id).val();
                                sell_market_order_by_user(sell_id, market_value, quantity,
                                    symbol, buy_order_id, order_type, sell_price);
                            }
                        },
                        close: function() {}
                    }
                });
                //%%%%%%%%%%%%%%%%%%%%%%%End of new status %%%%%%%%%%%%%%%%%%%%%%%%
            }


        }
    })

} //End of sell_market_order


function sell_market_order_by_user(sell_id, market_value, quantity, symbol, buy_order_id, order_type, sell_price) {

    //%%%%%%%%%%%%%%%%%%%%%%%%%%
    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/sell_market_order_by_user',
        'type': 'POST', //the way you want to send data to your URL
        'data': {
            sell_id: sell_id,
            market_value: market_value,
            quantity: quantity,
            symbol: symbol,
            buy_order_id: buy_order_id,
            order_type,
            sell_price: sell_price
        },
        'success': function(response) {
            $("#" + sell_id).html('Sell Now');
            if (response == '') {

            } else {
                $.confirm({
                    title: 'Encountered an error!',
                    content: response,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Ok',
                            btnClass: 'btn-red',
                            action: function() {}
                        },
                        close: function() {}
                    }
                });
            }

        }
    });
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%
} //End of sell_market_order_by_user


function limit_order_cancel(sell_id, market_value, quantity, symbol, buy_order_id) {

    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/check_status_of_limit_order',
        'type': 'POST',
        'data': {
            sell_id: sell_id,
            symbol: symbol
        },
        'success': function(response) {

            var rp = JSON.parse(response);
            var resp = rp['status'];
            var current_market_price = rp['market_price'];

            $("#" + sell_id).html('Sell Now');

            if (resp == 'error') {
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
                            action: function() {}
                        },
                        close: function() {}
                    }
                });
                //%%%%%%%%%%%%%%%End of  error status%%%%%%%%%%%
            } else if (resp == 'submitted') {
                //%%%%%%%%%%%%%%%%%%% submitted status %%%%%%%%%%%%%%%%%%%%%

                var content_html =
                    ' Order is already in <span style="color:orange;    font-size: 14px;"><b>SUBMIT</b></span> status for sell as limit order.' +
                    ' Are you want to  <span style="color:red;    font-size: 14px;"><b>Cancel</b></span>  it ?  And submit to sell agin!\
                            <div class="">\
                            <hr>\
                            <form>\
                            <div class="form-group">\
                            <div class="row"><div class="col-xs-6">\
                            <label>Current Market Price</label>\
                            <input class="form-control" step="any" type="number" name="cu_m_price_' + sell_id +
                    '" value="' + current_market_price + '" disabled>\
                            </div><div class="col-xs-6">\
                            <label>Sell Price</label>\
                            <input class="form-control" step=".00000001" type="number" name="sell_price_' + sell_id +
                    '" value="' + current_market_price + '" id="sell_price_' + sell_id + '">\
                            </div>\
                            </div>'

                // <div class="radio">\
                // <label><input type="radio" name="typ_submit_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\
                // </div>\
                // <div class="radio">\
                // <label><input type="radio" name="typ_submit_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\
                // </div>\


                content_html += ' <div class="radio ">\
                            <label><input type="radio" name="typ_submit_' + sell_id + '" value="m_current" checked>Fire market order on current market price</label>\
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
                            action: function() {

                                var order_type = $("input[name='typ_submit_" + sell_id +
                                    "']:checked").val();
                                var sell_price = $('#sell_price_' + sell_id).val();

                                cancel_and_place_new_limit_order_for_sell(sell_id, market_value,
                                    quantity, symbol, buy_order_id, order_type, sell_price);
                            }
                        },
                        close: function() {}
                    }
                });
                //%%%%%%%%%%%%%%%%%%% End of submitted status %%%%%%%%%%%%%%%%%%%%%
            } else if (resp == 'new') {

                //%%%%%%%%%%%%%%%%%%%%%%%%%%%%5  New Status%%%%%%%%%%%%%%%%%%%%%%%%%
                var content_html = 'Select from below to sell this order\
                            <div class="">\
                            <hr>\
                            <form>\
                            <div class="form-group">\
                            <div class="row"><div class="col-xs-6">\
                            <label>Current Market Price</label>\
                            <input class="form-control" step="any" type="number" name="cu_m_price_' + sell_id +
                    '" value="' + current_market_price + '" disabled>\
                            </div><div class="col-xs-6">\
                            <label>Sell Price</label>\
                            <input class="form-control" step=".00000001" type="number" name="sell_price_' + sell_id +
                    '" value="' + current_market_price + '" id="sell_price_' + sell_id + '">\
                            </div>\
                            </div>'

                // <div class="radio">\
                // <label><input type="radio" name="typ_new_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\
                // </div>\
                // <div class="radio">\
                // <label><input type="radio" name="typ_new_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\
                // </div>\


                content_html += ' <div class="radio ">\
                            <label><input type="radio" name="typ_new_' + sell_id + '" value="m_current" checked>Fire market order on current market price</label>\
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
                            action: function() {

                                var order_type = $("input[name='typ_new_" + sell_id +
                                    "']:checked").val();
                                var sell_price = $('#sell_price_' + sell_id).val();

                                sell_lmit_order_by_user(sell_id, market_value, quantity, symbol,
                                    buy_order_id, order_type, sell_price);
                            }
                        },
                        close: function() {}
                    }
                });
                //%%%%%%%%%%%%%%%%%%%%%%%End of new status %%%%%%%%%%%%%%%%%%%%%%%%
            }


        }
    })

} //End of limit_order_cancel





function sell_lmit_order_by_user(sell_id, market_value, quantity, symbol, buy_order_id, order_type, sell_price) {
    //%%%%%%%%%%%%%%%%%%%%%%%%%%
    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/sell_lmit_order_by_user',
        'type': 'POST', //the way you want to send data to your URL
        'data': {
            sell_id: sell_id,
            market_value: market_value,
            quantity: quantity,
            symbol: symbol,
            buy_order_id: buy_order_id,
            order_type,
            sell_price: sell_price
        },
        'success': function(response) {
            $("#" + sell_id).html('Sell Now');
            if (response == '') {

            } else {
                $.confirm({
                    title: 'Encountered an error!',
                    content: response,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Ok',
                            btnClass: 'btn-red',
                            action: function() {}
                        },
                        close: function() {}
                    }
                });
            }

        }
    });
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%
} //End of sell_lmit_order_by_user

function cancel_and_place_new_limit_order_for_sell(sell_id, market_value, quantity, symbol, buy_order_id, order_type,
    sell_price) {
    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/cancel_and_place_new_limit_order_for_sell',
        'type': 'POST', //the way you want to send data to your URL
        'data': {
            sell_id: sell_id,
            market_value: market_value,
            quantity: quantity,
            symbol: symbol,
            buy_order_id: buy_order_id,
            order_type,
            sell_price: sell_price
        },
        'success': function(response) {
            $("#" + sell_id).html('Sell Now');
            if (response == '') {

            } else {
                $.confirm({
                    title: 'Encountered an error!',
                    content: response,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Ok',
                            btnClass: 'btn-red',
                            action: function() {}
                        },
                        close: function() {}
                    }
                });
            }

        }
    });
} //End of cancel_and_place_new_limit_order_for_sell

function sell_order(id, market_value, quantity, symbol) {
    $("#" + id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/sell_order',
        'type': 'POST', //the way you want to send data to your URL
        'data': {
            id: id,
            market_value: market_value,
            quantity: quantity,
            symbol: symbol
        },
        'success': function(response) {

            if (response == 1) {
                $("#" + id).html('Sell Now');
            } else {
                $.confirm({
                    title: 'Encountered an error!',
                    content: response,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Ok',
                            btnClass: 'btn-red',
                            action: function() {}
                        },
                        close: function() {}
                    }
                });
            }

        }
    });

} //End of sell_order

function downloadCSV(csv, filename) {

    var csvFile;

    var downloadLink;



    // CSV file

    csvFile = new Blob([csv], {
        type: "text/csv"
    });



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

        var row = [],
            cols = rows[i].querySelectorAll("td, th");



        for (var j = 0; j < cols.length; j++)

            row.push(cols[j].innerText);



        csv.push(row.join(","));

    }



    // Download CSV file

    downloadCSV(csv.join("\n"), filename);

}
$('body').on("click", ".showhidebtn", function(e) {
    $(".showhide").toggle();
});


function create_custom_csv() {
    // $("#order_report_filter_form").clone().appendTo("#csv_report_filter_form");
    // $("#csv_report_filter_form > form").attr("action", "<?php //echo SURL; ?>admin/order_report/get_csv");
    // $("#csv_report_filter_form > form").attr("target", "_blank");
    // $("#csv_report_filter_form > form").attr("id", "csv_report_filter");
}

function create_csv_report() {
    $("#order_report_filter_form").clone().appendTo("#csv_report_filter_form");
    $("#csv_report_filter_form > form").attr("action", "<?php echo SURL; ?>admin/order_report/get_csv");
    $("#csv_report_filter_form > form").attr("target", "_blank");
    $("#csv_report_filter_form > form").attr("id", "csv_report_filter");
    $('form#csv_report_filter').submit();
}

function remove_order_error(el, order_id, exchange) {
    $.ajax({
        'url': '<?php echo SURL ?>admin/api_calls/remove_error',
        'type': 'POST',
        'data': {
            order_id: order_id,
            exchange: exchange
        },
        'success': function(res) {
            if (res.status) {
                $(el).closest("tr").remove();
            }
            // location . reload();
        }
    });
}

function sell_order_now(el, id, exchange) {

    if (confirm('Are you sure you want to sell this order ?')) {
        $.ajax({
            'url': '<?php echo SURL ?>admin/api_calls/sell_now',
            'type': 'POST',
            'data': {
                id: id,
                exchange: exchange
            },
            'success': function(res) {
                if (res.status) {
                    $(el).closest("tr").remove();
                }

                location.reload();

            }
        });
    }
}

function open_modal_child_details(id, exchange, market_value, symbol, total_perc,
    sold_total_perc) { // function to show the child cost avg detail of the order.
    var html = '';
    $.ajax({
        url: '<?php echo SURL ?>admin/barrier_trigger/order_array_cavg', // script path for getting the cost avg array of orders
        type: 'POST',
        data: {
            id: id,
            exchange: exchange
        },
        success: function(res) {
            obj = JSON.parse(res);
            nHTML =
                '<table class="table table-bordered table table-striped"><thead><tr><th>SR.</th><th>Quantity</th><th>Purchased Price</th><th>Sell price</th><th>Status</th><th>P/L(%)</th><th>BTC worth</th><th>Buy Date</th><th>Sell Date</th><th>Order Type</th></tr></thead><tbody>';
            //console.log(obj.buy_array['cost_avg_array']);
            //html += '<table><thead>';
            //Object.keys(obj.shareInfo[i]).length
            //console.log(Object.keys(obj.shareInfo[i]).length);
            var counter = 1;
            var status = '';
            var price = 'N/A';
            var profitPercentage = 0.0;
            var profitPercentage_sold = 0.0;
            var profitPercentage_sold_count = 0.0;
            var btc_worth = 0.0;
            var avg_price = 0.0;
            var avg_sell_price_three = 0.0;
            var total_percentage = 0.0;
            var sell_profit_percent = '';
            var sold_counter = 0;
            var levelRegex = /^level_[1-9]$|^level_1[0-5]$/;
            if (!jQuery.isEmptyObject(obj.buy_array)) {
                avg_price = obj.buy_array['avg_sell_price'];
                avg_sell_price_three = obj.buy_array['avg_sell_price_three'];
                sell_profit_percent = obj.buy_array['sell_profit_percent'];
                jQuery.each(obj.buy_array['cost_avg_array'], function(index, item) {
                    order_type = item['order_type']
                    if (levelRegex.test(order_type)) {
                        order_type = 'Sell Rule';
                    }
                    if (order_type == null || order_type == '' || order_type == undefined) {
                        order_type = '--';
                    }
                    myArray = symbol.split("USDT");
                    if (item["order_sold"] == 'yes') {
                        sold_counter += 1;
                        if (myArray[1] == '') {
                            btc_worth = (item['filledQtySell'] * item['filledPriceSell']).toFixed(
                                2);
                        } else {
                            btc_worth = (item['filledQtySell'] * item['filledPriceSell']).toFixed(
                                8);
                        }
                        status = 'sold';
                        price = item["filledPriceSell"];
                        profitPercentage = ((price - item["filledPriceBuy"]) / item[
                            "filledPriceBuy"]) * 100;
                        profitPercentage_sold = ((price - item["filledPriceBuy"]) / item[
                            "filledPriceBuy"]) * 100;
                        profitPercentage_sold_count += profitPercentage_sold;
                        total_percentage += profitPercentage;
                    } else {
                        if (myArray[1] == '') {
                            btc_worth = (item['filledQtyBuy'] * item['filledPriceBuy']).toFixed(2);
                        } else {
                            btc_worth = (item['filledQtyBuy'] * item['filledPriceBuy']).toFixed(8);
                        }
                        status = 'buy';
                        price = 'N/A';
                        profitPercentage = ((market_value - item["filledPriceBuy"]) / item[
                            "filledPriceBuy"]) * 100;
                        total_percentage += profitPercentage;
                    }

                    var date_time = new Date(parseInt(item["buyTimeDate"]['$date'][
                        '$numberLong'
                    ])); // create Date object
                    console.log(item["buyTimeDate"]['$date']['$numberLong']);
                    console.log(date_time);


                    //console.log(date.toString());
                    nHTML += '<tr><td>' + counter + '  ' + '</td><td>' + '  ' + item[
                            "filledQtyBuy"] + '</td><td>' + '  ' + item["filledPriceBuy"] +
                        '</td><td>' + price + '</td><td>' + status + '</td>';
                    if (profitPercentage < 0) {
                        nHTML += '<td style="background-color:#ffbdbd;">' + profitPercentage
                            .toFixed(2) + '</td>';
                    } else {
                        nHTML += '<td style="background-color:#cdffbd;">' + profitPercentage
                            .toFixed(2) + '</td>';
                    }

                    nHTML += '<td>' + btc_worth + '</td><td>' + date_time.toLocaleDateString() +
                        '</td>';
                    if (typeof(item["sellTimeDate"]) != "undefined" && item["sellTimeDate"] !==
                        null) {
                        var sell_date_time = new Date(parseInt(item["sellTimeDate"]['$date'][
                            '$numberLong'
                        ])); // create Date object
                        console.log(item["sellTimeDate"]['$date']['$numberLong']);
                        console.log(sell_date_time);
                        nHTML += '<td>' + sell_date_time.toLocaleDateString() + '</td>';
                    } else {
                        nHTML += '<td>N/A</td>';
                    }
                    nHTML += '<td>' + order_type + '</td></tr>';


                    counter += 1;
                });
                var s_pl = 0.0;
                if (sold_counter > 0) {
                    s_pl = (profitPercentage_sold_count / sold_counter).toFixed(2);
                }
                if (avg_price > 0) {
                    avg_price = avg_price.toFixed(8)
                } else {
                    avg_price = 0.0;
                }
                nHTML +=
                    '<tr><table class="table"><thead><tr><th colspan="2">Total Avg Price</th><th colspan="2">Last Three Avg</th><th colspan="2">Sold Average</th><th colspan="2">Current Average</th><th colspan="2">Average Target</th></tr></thead><tbody>';
                nHTML += '<tr><td colspan="2">' + avg_price + '</td>  <td colspan="2">' +
                    avg_sell_price_three + '</td colspan="2">   <td colspan="2">' + sold_total_perc +
                    '%</td><td>' + total_perc + '%</td>   <td class="text-center" colspan="2">' +
                    sell_profit_percent + '%</td>   </tr>';
                nHTML += '</tbody></table></tr>';
            } else if (!jQuery.isEmptyObject(obj.sell_order)) {
                avg_price = obj.sell_order['avg_sell_price'];
                avg_sell_price_three = obj.sell_order['avg_sell_price_three'];
                sell_profit_percent = obj.sell_order['sell_profit_percent'];
                jQuery.each(obj.sell_order['cost_avg_array'], function(index, item) {
                    order_type = item['order_type'];
                    if (order_type == null || order_type == '' || order_type == undefined) {
                        order_type = '--';
                    }
                    myArray = symbol.split("USDT");
                    if (item["order_sold"] == 'yes') {
                        sold_counter += 1;
                        if (myArray[1] == '') {
                            btc_worth = (item['filledQtySell'] * item['filledPriceSell']).toFixed(
                                2);
                        } else {
                            btc_worth = (item['filledQtySell'] * item['filledPriceSell']).toFixed(
                                8);
                        }
                        status = 'sold';
                        price = item["filledPriceSell"];
                        profitPercentage = ((price - item["filledPriceBuy"]) / item[
                            "filledPriceBuy"]) * 100;
                        profitPercentage_sold = ((price - item["filledPriceBuy"]) / item[
                            "filledPriceBuy"]) * 100;
                        profitPercentage_sold_count += profitPercentage_sold;
                        total_percentage += profitPercentage;
                    } else {
                        if (myArray[1] == '') {
                            btc_worth = (item['filledQtyBuy'] * item['filledPriceBuy']).toFixed(2);
                        } else {
                            btc_worth = (item['filledQtyBuy'] * item['filledPriceBuy']).toFixed(8);
                        }
                        status = 'buy';
                        price = 'N/A';
                        profitPercentage = ((market_value - item["filledPriceBuy"]) / item[
                            "filledPriceBuy"]) * 100;
                        total_percentage += profitPercentage;
                    }

                    var date_time = new Date(parseInt(item["buyTimeDate"]['$date'][
                        '$numberLong'
                    ])); // create Date object
                    console.log(item["buyTimeDate"]['$date']['$numberLong']);
                    console.log(date_time);

                    //console.log(date.toString());
                    nHTML += '<tr><td>' + counter + '  ' + '</td><td>' + '  ' + item[
                            "filledQtyBuy"] + '</td><td>' + '  ' + item["filledPriceBuy"] +
                        '</td><td>' + price + '</td><td>' + status + '</td>';
                    if (profitPercentage < 0) {
                        nHTML += '<td style="background-color:#ffbdbd;">' + profitPercentage
                            .toFixed(2) + '</td>';
                    } else {
                        nHTML += '<td style="background-color:#cdffbd;">' + profitPercentage
                            .toFixed(2) + '</td>';
                    }
                    nHTML += '<td>' + btc_worth + '</td><td>' + date_time.toLocaleDateString() +
                        '</td>';
                    if (typeof(item["sellTimeDate"]) != "undefined" && item["sellTimeDate"] !==
                        null) {
                        var sell_date_time = new Date(parseInt(item["sellTimeDate"]['$date'][
                            '$numberLong'
                        ])); // create Date object
                        console.log(item["sellTimeDate"]['$date']['$numberLong']);
                        console.log(sell_date_time);
                        nHTML += '<td>' + sell_date_time.toLocaleDateString() + '</td>';
                    } else {
                        nHTML += '<td>N/A</td>';
                    }

                    nHTML += '<td>' + order_type + '</td></tr>'


                    counter += 1;
                });
                var s_pl = 0.0;
                if (sold_counter > 0) {
                    s_pl = (profitPercentage_sold_count / sold_counter).toFixed(2);
                }
                nHTML +=
                    '<tr><table class="table"><thead><tr><th colspan="2">Total Avg Price</th><th colspan="2">Last Three Avg</th><th colspan="2">Sold Average</th><th colspan="2">Current Average</th><th colspan="2">Average Target</th></tr></thead><tbody>';
                nHTML += '<tr><td colspan="2">' + avg_price.toFixed(8) + '</td>  <td colspan="2">' +
                    avg_sell_price_three + '</td colspan="2">   <td colspan="2">' + sold_total_perc +
                    '%</td><td>' + total_perc + '%</td>   <td class="text-center" colspan="2">' +
                    sell_profit_percent + '%</td>   </tr>';
                nHTML += '</tbody></table></tr>';
            } else {
                nHTML += '<tr><td colspan="3" align="center">No Record Found</td></tr>';
            }
            nHTML += '</tbody></table>';
            $("#cavgmodalresp").html(nHTML);
            $("#child_avg_detail").modal('show');
        }
    });

}

function open_modal_bulk_orders_details(jsonData) {
    var bulkOrders = JSON.parse(jsonData);
    console.log(bulkOrders);
    var tableBody = document.getElementById('bulkOrdersTable').getElementsByTagName('tbody')[0];

    tableBody.innerHTML = '';

    // Sort the bulkOrders array based on sell date difference
    bulkOrders.sort(function(a, b) {
        var sellDateDifferenceA = Math.abs(new Date(a.sellTimeDate.$date.$numberLong) - new Date(a.buyTimeDate
            .$date.$numberLong));
        var sellDateDifferenceB = Math.abs(new Date(b.sellTimeDate.$date.$numberLong) - new Date(b.buyTimeDate
            .$date.$numberLong));
        return sellDateDifferenceA - sellDateDifferenceB;
    });

    var currentSellDateDifference = null;
    if (typeof(bulkOrders[0].symbol) != 'undefined' && bulkOrders[0].symbol != null) {
        var orderSymbol = bulkOrders[0].symbol;
    }
    bulkOrders.forEach(function(order) {
        var sellDateDifference = Math.abs(new Date(order.sellTimeDate.$date.$numberLong) - new Date(order
            .buyTimeDate.$date.$numberLong));

        // If the sell date difference is greater than 1 minute, create a new row
        if (currentSellDateDifference !== null && sellDateDifference > 60000) {
            var separatorRow = tableBody.insertRow();
            var separatorCell = separatorRow.insertCell(0);
            separatorCell.colSpan = 8; // Span all columns
            separatorCell.innerHTML = '--- Separator ---';
        }


        var row = tableBody.insertRow();

        var orderIDCell = row.insertCell(0);
        var symbolCell = row.insertCell(1);
        var orderSoldCell = row.insertCell(2);
        var filledPriceSell = row.insertCell(3);
        var filledQtyBuy = row.insertCell(4);
        var buyTimeDateCell = row.insertCell(5);
        var sellTimeDateCell = row.insertCell(6);
        var profitPercentage = (order.filledPriceSell - order.filledPriceBuy) / order.filledPriceBuy * 100;
        var profit = profitPercentage.toFixed(2);

        orderIDCell.innerHTML = order.orderFilledIdBuy || order.orderFilledIdSell;
        symbolCell.innerHTML = orderSymbol;
        orderSoldCell.innerHTML = order.order_sold;
        filledPriceSell.innerHTML = order.filledPriceSell;
        filledQtyBuy.innerHTML = order.filledQtyBuy;

        var buyTimeDate = new Date(parseInt(order.buyTimeDate.$date.$numberLong));
        buyTimeDateCell.innerHTML = buyTimeDate.toLocaleString();
        var sellTimeDate = new Date(parseInt(order.sellTimeDate.$date.$numberLong));
        sellTimeDateCell.innerHTML = sellTimeDate.toLocaleString();

        var plCell = row.insertCell(7);
        plCell.innerHTML = profit + "%";

        if (profitPercentage < 0) {
            plCell.style.backgroundColor = '#ffbdbd';
        } else if (profitPercentage > 0) {
            plCell.style.backgroundColor = '#cdffbd';
        } else {
            plCell.style.backgroundColor = 'white';
        }

        // Update the current sell date difference
        currentSellDateDifference = sellDateDifference;
    });

    // Show the modal
    $('#bulkOrdersModal').modal('show');
}

function merge_request(param, user_id, symbol) {
    var exchange = "<?php echo $filter_user_data['filter_by_exchange']; ?>";
    $.ajax({
        'url': '<?php echo SURL ?>admin/order_report/move_order_to_cavg_to_new',
        'type': 'POST',
        'data': {
            order_id: param,
            admin_id: user_id,
            symbol: symbol,
            exchange: exchange
        },
        'success': function(res) {
            obj = JSON.parse(res);
            if (obj.success == true) {
                console.log(obj.data);
                console.log(obj.exchange);
                swal(
                    'Success',
                    'Order Moved successfuly',
                    'success'
                );
            } else {
                swal(
                    'Error',
                    'Something Went wrong',
                    'error'
                );
            }
        }
    });
}

function quantity_error_issue(param) {
    var exchange = "<?php echo $filter_user_data['filter_by_exchange']; ?>";
    $.ajax({
        'url': '<?php echo SURL ?>admin/order_report/move_order_to_buy_quantity',
        'type': 'POST',
        'data': {
            order_id: param,
            exchange: exchange
        },
        'success': function(res) {
            obj = JSON.parse(res);
            if (obj.success == true) {
                console.log(obj.data);
                console.log(obj.exchange);
                swal(
                    'Success',
                    'Order Updated successfuly',
                    'success'
                );
            } else {
                swal(
                    'Error',
                    'Something Went wrong',
                    'error'
                );
            }
        }
    });
}

function move_request(param) {
    var exchange = "<?php echo $filter_user_data['filter_by_exchange']; ?>";
    $.ajax({
        'url': '<?php echo SURL ?>admin/order_report/move_from_sold_to_buy',
        'type': 'POST',
        'data': {
            order_id: param,
            exchange: exchange
        },
        'success': function(res) {
            obj = JSON.parse(res);
            if (obj.success == true) {
                console.log(obj.data);
                console.log(obj.exchange);
                swal(
                    'Success',
                    'Order Moved successfuly',
                    'success'
                );
            } else {
                swal(
                    'Error',
                    'Something Went wrong',
                    'error'
                );
            }
        }
    });
}

function avg_sell_price_upd(param, avg_sell_price) {

    var exchange = "<?php echo $filter_user_data['filter_by_exchange']; ?>";
    $('#avg_sell_price_id').val(param);
    $('#avg_sell_price_old').val(avg_sell_price);
    $('#exchange_avg_upd').val(exchange);
    $('#avg_sell_modal').modal('show');
    // $.ajax({
    //     'url': '<?php echo SURL ?>admin/order_report/merge_old_cavg_to_new',
    //     'type': 'POST',
    //     'data': {
    //         order_id:param,
    //         exchange:exchange
    //     },
    //     'success': function (res) {
    //         obj = JSON.parse(res);
    //         if(obj.success == true){
    //             console.log(obj.data);
    //             console.log(obj.exchange);
    //             swal(
    //               'Success',
    //               'Order Merged successfuly',
    //               'success'
    //             );
    //         }else{
    //             swal(
    //               'Error',
    //               'Something Went wrong',
    //               'error'
    //             );
    //         }
    //     }
    // });
}

function update_sell_price_avg() {

    var exchange = "<?php echo $filter_user_data['filter_by_exchange']; ?>";
    var avg_sell_price_id = $('#avg_sell_price_id').val();
    var avg_sell_price_new = $('#avg_sell_price_new').val();
    $.ajax({
        'url': '<?php echo SURL ?>admin/order_report/upd_avg_price_new',
        'type': 'POST',
        'data': {
            avg_sell_price_id: avg_sell_price_id,
            avg_sell_price_new: avg_sell_price_new,
            exchange_avg_upd: exchange
        },
        'success': function(res) {
            obj = JSON.parse(res);
            if (obj.success == true) {
                console.log(obj.data);
                swal(
                    'Success',
                    'Avg Sell Price Updated Successfuly',
                    'success'
                );
                location.reload();
            } else {
                swal(
                    'Error',
                    'Something Went wrong',
                    'error'
                );
            }
        }
    });
}

$(document).ready(function(){
            if($('#tradingIp').val()!= '' || $('#filter_by_start_date').val()!= '' || $('#filter_by_end_date').val()
            != '' || $('#filter_by_start_date_m').val()!= '' || $('#filter_by_end_date_m').val()!= '' || $('#filter_by_trigger').val()
           != '' || $('#filter_by_nature').val()!= '' || $('#filter_by_coinNature').val() != 'both' || $('#filter_username_exclude').val()
           != '' || $('#profit').val()!= '' || $('#opportunityId').val()!= '')
           {
            $('#toggleFilter').click()
           }


})
$('#toggleFilter').click(function(e) {
    e.preventDefault();
    if ($('#ipDiv').css('display') == 'block') {
        $('#usernameDiv').removeClass('col-md-3');
        $('#usernameDiv').addClass('col-md-2');
        $('#exchangeDiv').removeClass('col-md-3');
        $('#exchangeDiv').addClass('col-md-2');
        $('#coinDiv').removeClass('col-md-3');
        $('#coinDiv').addClass('col-md-2');
        $('#ipDiv').css('display', 'none');
        $('#modeDiv').css('display', 'none');
        $('#createdDateFromDiv').css('display', 'none');
        $('#createdDateToDiv').css('display', 'none');
        $('#modifiedDateFromDiv').css('display', 'none');
        $('#modifiedDateToDiv').css('display', 'none');
        $('#triggerDiv').css('display', 'none');
        $('#orderTypeDiv').css('display', 'none');
        $('#coinTypeDiv').css('display', 'none');
        $('#excludeUsernameDiv').css('display', 'none');
        $('#profitDiv').css('display', 'none');
        $('#opportunityIdDiv').css('display', 'none');
        // $('#OrderIdDiv').css('display', 'none');
        $('#parentAdminDiv').css('display', 'none');
    } else {
        $('#usernameDiv').removeClass('col-md-2');
        $('#usernameDiv').addClass('col-md-3');
        $('#exchangeDiv').removeClass('col-md-2');
        $('#exchangeDiv').addClass('col-md-3');
        $('#coinDiv').removeClass('col-md-2');
        $('#coinDiv').addClass('col-md-3');
        $('#ipDiv').css('display', 'block');
        $('#modeDiv').css('display', 'block');
        $('#createdDateFromDiv').css('display', 'block');
        $('#createdDateToDiv').css('display', 'block');
        $('#modifiedDateFromDiv').css('display', 'block');
        $('#modifiedDateToDiv').css('display', 'block');
        $('#triggerDiv').css('display', 'block');
        $('#orderTypeDiv').css('display', 'block');
        $('#coinTypeDiv').css('display', 'block');
        $('#excludeUsernameDiv').css('display', 'block');
        $('#profitDiv').css('display', 'block');
        $('#opportunityIdDiv').css('display', 'block');
        // $('#OrderIdDiv').css('display', 'block');
        $('#parentAdminDiv').css('display', 'block');
    }
});

</script>

<!-- <script type="text/javascript">
    $( document ).ready(function() {
    ('#example').DataTable();
});
  
 function moveScroll(){
    var scroll = $(window).scrollTop();
    var anchor_top = $("#example").offset().top;
    var anchor_bottom = $("#bottom_anchor").offset().top;
    if (scroll>anchor_top && scroll<anchor_bottom) {
    clone_table = $("#clone");
    if(clone_table.length == 0){
        clone_table = $("#example").clone();
        clone_table.attr('id', 'clone');
        clone_table.css({position:'fixed',
                 'pointer-events': 'none',
                 top:50});
        clone_table.width($("#example").width());
        $("#table-container").append(clone_table);
        $("#clone").css({visibility:'hidden'});
        $("#clone thead").css({'visibility':'visible','pointer-events':'auto'});
    }
    } else {
    $("#clone").remove();
    }
}
$(window).scroll(moveScroll);
</script> -->