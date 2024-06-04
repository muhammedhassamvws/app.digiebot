

        <link rel="stylesheet" href="https://res.cloudinary.com/dxfq3iotg/raw/upload/v1569006288/BBBootstrap/choices.min.css?version=7.0.0">
        <script src="https://res.cloudinary.com/dxfq3iotg/raw/upload/v1569006273/BBBootstrap/choices.min.js?version=7.0.0"></script>

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

            .Input_text_s > i {
                position: absolute;
                right: 8px;
                bottom: 4px !important;
                height: 20px;
                top: auto;
            }
            .ax_1, .ax_2, .ax_3, .ax_4, .ax_5, .ax_6, .ax_7, .ax_8, .ax_9, .ax_10, .ax_11, .ax_12, .ax_13 {
                padding-bottom: 35px !important;
            }

            .Input_text_btn > a > i, .Input_text_btn > button > i {
                margin-right: 10px;
            }

        </style> 
        <?php echo "<pre>";print_r($resData);

            $filter_user_data = $this->session->userdata('user_post_data');
        ?>

        <div id="content">
            <div class="widget widget-inverse">
                <div class="widget-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 ax_1">

                            <form method="POST" action="<?php echo SURL; ?>admin/Monthly_trading_volume/index">

                                <div class="col-xs-12 col-sm-12 col-md-3 ax_2">
                                    <div class="Input_text_s">
                                    <label>Filter Coin: </label>
                                    <select  name="filter_by_coin[]" type="text" class="filter_by_name_margin_bottom_sm form-control" id="choices-multiple-remove-button" multiple >
                                        <?php foreach($coins as $coinRow){  ?>      
                                            <option value="<?php echo $coinRow['symbol'] ?>" <?php if (in_array($coinRow['symbol'], $filter_user_data['filter_by_coin'])) {?> selected <?php }?>><?php echo $coinRow['symbol'] ?></option>
                                        <?php } ?>
                                    </select>
                                    </div>
                                </div>


                                <div class="col-xs-12 col-sm-12 col-md-3 ax_3">
                                    <div class="Input_text_s">
                                        <label>From Date Range: </label>
                                        <input style= "height:44px" id="start_date" name="start_date" type="datetime-local" class="form-control datetime_picker filter_by_name_margin_bottom_sm" value="<?=(!empty($filter_user_data['start_date']) ? $filter_user_data['start_date'] : "")?>" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-3 ax_4">
                                    <div class="Input_text_s">
                                        <label>To Date Range: </label>
                                        <input style= "height:44px" id="end_date" name="end_date" type="datetime-local" class="form-control datetime_picker filter_by_name_margin_bottom_sm" value="<?=(!empty($filter_user_data['end_date']) ? $filter_user_data['end_date'] : "")?>" autocomplete="off">
                                    </div>
                                </div>


                                <div class="col-xs-12 col-sm-12 col-md-3 ax_5">
                                    <div class="Input_text_btn" style="margin-top:5%">
                                        <label></label>
                                        <button style= "height:44px" id="submit-form" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
                                        <a style= "height:44px" href="<?php echo SURL; ?>admin/Monthly_trading_volume/index?button=reset"class="btn btn-danger">Reset</a>
                                        </span>   
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>







       

        <script>
            $(document).ready(function(){

                var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
                    removeItemButton: true,
                    // maxItemCount:5,
                    // searchResultLimit:5,
                    // renderChoiceLimit:5
                });
            });


        </script>






