            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
            <link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
            <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
            <script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
            <script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
            <script type="text/javascript" src="<?php  echo SURL ?>assets/dist/jquery-asPieProgress.js"></script>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
            <style>
            .Input_text_s {
            float: left;
            width: 100%;
            position: relative;
            }
            .Input_text_s > label {
            float: left;
            width: 100%;
            color: #000;
            font-size: 14px;
            }
            .multiselect-native-select .btn-group {
            float: left;
            width: 100% !important;
            }
            .multiselect-native-select .btn-group button {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 5px !important;
            }
            .Input_text_s > i {
            position: absolute;
            right: 8px;
            bottom: 4px !important;
            height: 20px;
            top: auto;
            }
            .ax_2, .ax_3, .ax_4, .ax_5 {
            padding-bottom: 35px !important;
            }
            .col-radio {
            float: left;
            width: 100%;
            position: relative;
            padding-left: 30px;
            height: 30px;
            }
            .col-radio span {
            position: absolute;
            left: 0;
            width: 30px;
            height: 30px;
            top: 0;
            font-size: 23px;
            line-height: 0;
            }
            .col-radio input[type="radio"] {
            position: absolute;
            left: 0;
            opacity: 0;
            }
            .col-radio input[type="radio"]:checked + span i.fa.fa-dot-circle-o {
            display: block;
            color: #72af46;
            }
            .col-radio input[type="radio"]:checked + span i.fa.fa-circle-o {
            display: none;
            }
            .col-radio span i.fa.fa-dot-circle-o {
            display: none;
            }
            .col-radio label {
            color: #000;
            font-size: 15px;
            padding-top: 1px;
            }
            .Input_text_btn > a > i, .Input_text_btn > button > i {
            margin-right: 10px;
            }
            .coin_symbol {
            }
            .coin_symbol {
            color: #fff;
            font-weight: bold;
            font-size: 14px;
            float: left;
            width: 100%;
            padding: 12px 20px;
            background: #31708f;
            border-radius: 7px 7px 0 0;
            margin-top: 25px;
            }
            .coin_symbol:first-child {
            margin-top: 0;
            }
            table.table.table-stripped {
            border: 1px solid #2d4c5a;
            }
            table.table.table-stripped tr.theadd {
            background: #ccc;
            color: #000;
            }
            table.table.table-stripped tr.theadd td {
            border: 1px solid #2d4c5a;
            font-weight: bold;
            font-size: 13px;
            }
            table.table.table-stripped tr td {
            border: 1px solid #2d4c5a;
            vertical-align: middle;
            }
            table.table.table-stripped tr td.heading {
            background: #ccc;
            color: #000;
            font-size: 13px;
            font-weight: bold;
            }
            table.table.table-stripped tr:hover {
            background: rgba(0,0,0,0.04);
            }
            table.table.table-stripped tr.theadd:hover {
            background: rgba(204,204,204,1);
            }
            tr.coin_symbol td {
            border: none !important;
            }
            table.table.table-stripped tr td .table-stripped-column tr td {
            border: none;
            padding-bottom: 0;
            padding-top: 15px;
            background: #ccc;
            color: black;
            }
            .modal-dialog {
            overflow-y: initial !important
            }
            .Opp {
            height: 550px;
            padding-left: 10px;
            overflow-y: auto;
            overflow-x: hidden;
            }
            .totalAvg {
            padding-top: 44px;
            }
            .Input_text_btn {
            padding: 25px 0 0;
            }
            </style>

            <style>
            /* New CSS Classes for Labels and Boxes */
            .label-box-pending{
            display:inline-block;
            width: 22px;
            float:left;
            height: 22px;
            border-radius: 0.7rem;
            background: #f0ad4e;
            }
            .label-box-approved{
            display:inline-block;
            width: 22px;
            float:left;
            height: 22px;
            border-radius: 0.7rem;
            background: #c3e6cb;
            }
            .label-box-rejected{
            display:inline-block;
            width: 22px;
            float:left;
            height: 22px;
            border-radius: 0.7rem;
            background: #d9534f;
            }
            .label-box-requested{
            display:inline-block;
            width: 22px;
            float:left;
            height: 22px;
            border-radius: 0.7rem;
            background: #5bc0de;
            }
            .status-box-pending{
            color: white !important;
            text-align:center;
            background-color:#f0ad4e;
            }
            .status-box-approved{
            color: white !important;
            text-align:center;
            background-color:#72af46;
            }
            .status-box-rejected{
            color: white !important;
            text-align:center;
            background-color:#d9534f;
            }
            .status-box-requested{
            color: white !important;
            text-align:center;
            background-color:#5bc0de;
            }
            .table_align_head{
            text-align: left !important;
            }
            .badge {
            background-color: black!important;
            }
            </style>
            <div id="content">
            <h1 class="content-heading bg-white border-bottom">Reports</h1>
            <div class="innerAll bg-white border-bottom">
            <div class="pull-right" style="padding-right: 12px; padding-top: 8px;">
            <div class=" pull-right alert alert-warning" style=" margin-top: -10px; background: #5c678a;color: white;"> <?php echo date("F j, Y, g:i a").'&nbsp;&nbsp;  <b>'.date_default_timezone_get().' (GMT + 0)'.'<b />' ?></div>     

            </div>
            <ul class="menubar">
            <li class=""><a href="<?php echo SURL; ?>/admin/cron_listing/cornsHistoryDisplay">Reports</a></li>
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
            <?php $filter_data = $this->session->userdata('filter_investment_report');?>
            <div class="widget widget-inverse">
                <div class="widget-body">
                    <form method="POST" action="<?php echo SURL; ?>admin/cron_listing/cornsHistoryDisplay">  
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 ax_1">
                                <!-- <div class="col-xs-12 col-sm-12 col-md-3 ax_2">
                                    <div class="Input_text_s">
                                        <label>Filter : </label>
                                        <select id="filter_by_coin" multiple="multiple" name="filter_by_coin[]" type="text" class=" filter_by_name_margin_bottom_sm">
                                        <?php foreach($coins as $coinRow){  ?>      
                                        <option value="<?php echo $coinRow['symbol']?>" <?php if (in_array($coinRow['symbol'], $filter_data['filter_by_coin'])) {?> selected <?php }?> ><?php echo $coinRow['symbol'] ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                </div> -->

                                <!-- <div class="col-xs-12 col-sm-12 col-md-3 ax_4">
                                    <div class="Input_text_s">
                                        <label>From Start Hour: </label>
                                        <input id="filter_by_start_date" name="filter_by_start_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_start_date']) ? $filter_user_data['filter_by_start_date'] : "")?>" autocomplete="off">
                                        <i class="glyphicon glyphicon-calendar"></i> </div>
                                    </div> -->
                                    <!-- <script type="text/javascript">
                                        $(function () {
                                            $('.datetime_picker').datetimepicker();
                                        });
                                    </script>        -->
                                    <!-- <div class="col-xs-12 col-sm-12 col-md-3 ax_10">
                                        <div class="Input_text_btn">
                                            <label></label>
                                            <button id="submit-form" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
                                            </span>                    
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <!-- Widget -->
            <style>
            /* STYLES FOR PROGRESSBARS */
            .progress-radial, .progress-radial * {
            -webkit-box-sizing: content-box;
            -moz-box-sizing: content-box;
            box-sizing: content-box;
            }
            /* -------------------------------------
            * Bar container
            * ------------------------------------- */
            .progress-radial {
            float: left;
            margin-right: 4%;
            position: relative;
            width: 55px;
            border-radius: 50%;
            }
            .progress-radial:first-child {
            margin-left: 4%;
            }
            /* -------------------------------------
            * Optional centered circle w/text
            * ------------------------------------- */
            .progress-radial .overlay {
            position: absolute;
            width: 80%;
            background-color: #f0f0f0;
            border-radius: 50%;
            font-size: 14px;
            top:50%;
            left:50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            }
            .progress-radial .overlay p{
            position: absolute;
            line-height: 40px;
            text-align: center;
            width: 100%;
            top:50%;
            margin-top: -20px;
            }
            .mypai_prog {
            display: inline-block;
            padding: 2px;
            }
            .tdmypi{
            padding:2px;
            text-align:center;
            }

            div.pie_progress__label {
            position: absolute;
            top: 20px;
            left: 8px;
            }
            .pie_progress {
            position: relative;
            width: 60px;
            text-align: center;
            margin-left: 39px;
            }
            .circle{
                position: relative;
            }
            .circle strong {
                position: absolute;
                top: 50%;
                left: 28px;
                z-index: 2222;
                transform: translate(-50%, -50%);
                font-size: 15px;
                color: black;
            }
            </style>
            <script>

            $(".setsize").each(function() {
                $(this).height($(this).width());
            });
            $(window).on('resize', function(){
            $(".setsize").each(function() {
                $(this).height($(this).width());
            });
            });
            </script> 
            <div class="widget widget-inverse">
            <div class="widget-body padding-bottom-none">
                <table class=" table table-bordered table table-striped" id="example">
                    <tr class="">
                        <th>#</th>
                        <th>URL</th>
                        <th>Stop Time</th>
                        <th>Corn Duration </th>
                        <th>Run Time</th>
                    </tr> 
                        <?php $count = 0;
                        foreach($final_array as $value){
                            $count++; 
                            $time_zone = date_default_timezone_get();
                            $last_modified_time = $value['enteryTime']->toDateTime()->format("Y-m-d H:i:s");

                            ?>
                            <tr>
                                <td><?=$count; ?></td>
                                <td><?= $value['url'];?></td>

                                <td>
                                    <?php 
                                        if(isset($value['last_run'])){
                                            $last_modified_timeRun = $value['last_run']->toDateTime()->format("Y-m-d H:i:s");
                                            
                                            $this->load->helper('common_helper');
                                            $last_time_agoRun = time_elapsed_string($last_modified_timeRun , $time_zone);
                                        }else{
                                            $last_modified_timeRun = '---';
                                            $last_time_agoRun = '---';
                                        }
                                    ?>
                                    <span class="label label-info" title="<?php echo $last_modified_timeRun; ?>"> <?php echo $last_time_agoRun; ?></span>  
                                    
                                </td>

                                <td><?= $value['cron_duration'];?></td>

                                <td><?php
                                    $this->load->helper('common_helper');
                                    $last_time_ago = time_elapsed_string($last_modified_time , $time_zone);?>
                                    <span class="label label-info" title="<?php echo $last_modified_time; ?>"> <?php echo $last_time_ago; ?></span>    
                                </td> 


                            </tr>
                        <?php } ?>     
                </table>
            <div><?php echo $links; ?></div>
            </div>
            </div>
            </div>
            </div>
            </div>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.1.3/circle-progress.min.js"></script> 
                <script>
                    var progressBarOptions = {
                        startAngle: -1,
                        size: 60,
                    };
                    $('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function (event, progress, value) {
                    
                    });
                </script> 
            <script type="text/javascript">
            $('.pie_progress').asPieProgress({
            size: 100,
            ringWidth: 100,
            strokeWidth: 100,
            ringEndsRounded: true,
            valueSelector: "span.value",
            color: "navy"
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
            <script>
            $(document).ready(function(e){
            var filter_by_trigger = $("#filter_by_trigger").val();



            if (filter_by_trigger == 'barrier_trigger') {
                $(".filter_by_level").hide();
                $(".filter_by_rule").show();
            }else if(filter_by_trigger == 'barrier_percentile_trigger'){
                $(".filter_by_level").show();
                $(".filter_by_rule").hide();
            }else{
                $(".filter_by_level").hide();
                $(".filter_by_rule").hide();
            }
            });


            $("body").on("change","#filter_by_trigger",function(e){
            var filter_by_trigger = $("#filter_by_trigger").val();

            $(".filter_by_level").hide();
            $(".filter_by_rule").hide();

            if(filter_by_trigger =='barrier_percentile_trigger'){
            $(".filter_by_level").show();
            $(".filter_by_rule").hide();
            }

            if(filter_by_trigger =='barrier_trigger'){
            $(".filter_by_rule").show();
            $(".filter_by_level").hide();
            }
            if(filter_by_trigger =='no'){
            $(".filter_by_level").hide();
                $(".filter_by_rule").hide();
            }
            });

            $("body").on("click","input[name=group_filter]",function(e){
            var query = $(this).attr('id');
            if (query == 'trigger_group') {
                $("#trigger1").hide();

            $("#triggerFirst").show();
            }else if(query == 'rule_group'){
                $("#trigger1").show();
            $("#triggerFirst").hide();
            }else{
            $("#trigger1").hide();
            }
            });
            $(function () {
            $('[data-toggle="tooltip"]').tooltip()
            })
            </script> 