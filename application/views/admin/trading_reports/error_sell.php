
<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>

<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
    
    input[type=checkbox] + label:before {
        font-family: FontAwesome;
        display: inline-block;
    }
    
    .custom_label {
        font-size: 25px;
        width: 100%;
        text-align: center;
    }
    
    input[type=checkbox] + label:before {
        content: "\f096";
    }
    /* unchecked icon */
    
    input[type=checkbox] + label:before {
        letter-spacing: 10px;
    }
    /* space between checkbox and label */
    
    input[type=checkbox]:checked + label:before {
        content: "\f046";
    }
    /* checked icon */
    
    input[type=checkbox]:checked + label:before {
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
    
    .af-switcher > small {
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
    
    input[type=radio].af-cust-radio:checked + .af-custom-radio-label {
        color: #fff;
        background: #0f1c42;
    }
    
    .af-custom-radio-label + input[type=radio].af-cust-radio + .af-custom-radio-label {
        border-left: 3px solid #0f1c42;
    }
    
    .af-radio-group {
        border: 3px solid #0f1c42;
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
        min-width: 250px !important;
    }
    
    .af-table-body td:nth-child(3) {
        min-width: 140px;
    }
    
    .af-table-body td:nth-child(13) {
        min-width: 200px;
    }
    
    .af-table-body td:nth-child(15) {
        min-width: 200px;
    }
    
    .af-table-body td:nth-child(14) {
        min-width: 200px;
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

            <!-- <li class=""><a href="<?php echo SURL; ?>admin/reports">Reports</a></li> -->

            <li class="active"><a href="#">Custom Report</a></li>

        </ul>

    </div>

    <div class="innerAll spacing-x2">

        <?php

if ($this->session->flashdata('err_message')) {

    ?>

            <div class="alert alert-danger">
                <?php echo $this->session->flashdata('err_message'); ?>
            </div>

            <?php

}

if ($this->session->flashdata('ok_message')) {

    ?>

                <div class="alert alert-success alert-dismissable">
                    <?php echo $this->session->flashdata('ok_message'); ?>
                </div>

                <?php

}

?>

                    <?php $filter_user_data = $this->session->userdata('filter_order_data');?>

                        <div class="widget widget-inverse">

                            <div class="widget-body">
                            </div>

                        </div>

                        <!-- Widget -->

                        <div class="widget widget-inverse">

                            <div class="widget-body padding-bottom-none">

                                <div class="table-responsive">

                                    <!-- Table -->

                                    <table class="table table-condensed">

                                        <thead>

                                            <tr>

                                                <th class="text-center"><strong>Coin</strong></th>

                                                <th class="text-center"><strong>Price</strong></th>

                                                <th class="text-center"><strong>Order Type</strong></th>

                                                <th class="text-center"><strong>Level</strong></th>

                                                <th class="text-center"><strong>Created Date</strong></th>

                                                <th class="text-center"><strong>Status</strong></th>

                                                <th class="text-center"><strong>Profit(%)</strong></th>
                                                
                                                <th class="text-center"><strong>Quantity</strong></th>

                                                <th class="text-center"><strong>Last Modified Date</strong></th>

                                                <th class="text-center"><strong>Actions</strong></th>

                                            </tr>

                                        </thead>
                                        <tbody>
                                            <?php foreach($records as $value){ ?>
                                                <tr>
                                                
                                                <td class="text-center"><?= $value['symbol'] ?></td>

                                                <td class="text-center"><?= $value['purchased_price'] ?></td>

                                                <td class="text-center"><?= strtoupper(str_replace("_", " ", $value['order_type'])) ?></td>

                                                <td class="text-center"><?= strtoupper(str_replace("_", " ", $value['order_level'])) ?></td>

                                                <td class="text-center"><?= $value['created_date']->toDateTime()->format("Y-m-d H:i:s") ?></td>

                                                <td class="text-center"><?= $value['status'] ?></td>

                                                <td class="text-center">Profit(%)</td>

                                                <td class="text-center"><?= $value['quantity'] ?></td>

                                                <td class="text-center"><?= $value['modified_date']->toDateTime()->format("Y-m-d H:i:s") ?></td>

                                                <td class="text-center"><strong>Actions</td>
                                            </tr>
                                           <?php } ?>
                                        </tbody>

                                    </table>

                                </div>

                                <!-- // Table END -->

                                <?php echo $pagination; ?>

                            </div>

                        </div>

                        <!-- // Widget END -->

    </div>

</div>

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