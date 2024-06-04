
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
<link rel='stylesheet' href='https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css'>
<link rel='stylesheet' href='https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css'>
<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
<style>


    .chart-container {
        position: relative;
        margin: auto;
        height: 400px;
        width: 90%;
        /* margin-top: 60px; */
    } 
    button.btn.btn-success {
    margin-left: 50px;
}


</style>
<div id="content" style="padding-bottom:0px;background: white;">
    <!-- Widget -->
    <?php $filter_data = $this->session->userdata('filter_monthly_report');?>
    <div class="widget widget-inverse">
      <div class="widget-body padding-bottom-none">
            <!-- Form -->
            <div class="widget widget-inverse" style="margin: 0px;border: 0 solid white;">
            
                <div class="col" style="padding-top: 2%">    
                    <button class="btn btn-success" id="all">All</button>
                    <button class="btn btn-info" id="binance_chart">Binance</button>
                    <button class="btn btn-warning" id="kraken_chart">Kraken</button> 
                    <!-- <button class="btn btn-danger" id="bam_chart" >Bam</button> -->
                </div>
                <form method="POST" action="<?php echo SURL; ?>admin/Monthly_trading_volume/fee_analysis">
                    <div class="row" style="margin-top: 20px;">
                        <div class="col" style="margin-left:50px;">
                            <div class="col-xs-12 col-sm-12 col-md-3 ax_4">
                                <div class="Input_text_s">
                                <label>From Date Range: </label>
                                <input id="start_date" name="start_date" type="date" class="form-control datetime_picker filter_by_name_margin_bottom_sm" value="<?=(!empty($filter_data['start_date']) ? $filter_data['start_date'] : "")?>" autocomplete="off">

                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 ax_5">
                                <div class="Input_text_s">
                                <label>To Date Range: </label>
                                <input id="end_date" name="end_date" type="date" class="form-control datetime_picker filter_by_name_margin_bottom_sm" value="<?=(!empty($filter_data['end_date']) ? $filter_data['end_date'] : "")?>" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 ax_35" style="margin-top: 20px;">
                          <div class="Input_text_btn">
                            <label></label>
                            <button id="submit-form" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
                            <a href="<?php echo SURL; ?>admin/Monthly_trading_volume/resetFilter_monthly"class="btn btn-danger">Reset</a>
                            </span>   
                          </div>
                        </div>
                    </div>
                </form>
                <div class="row chart-container" style="padding-top: 2%">
                    <div id ='binance_mtv' class="col-xs-12 col-sm-12 col-md-12 text-center"style= "background-color: darkgray;margin-top: 15px;">Binance Montly Trade Volume</div>
                    <div class="row" style="margin-top: 15px;"> 
                        <!-- binance charts -->
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="binance-line-chart" width="800" height="300"></canvas>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="binance-line-chart_new" width="800" height="300"></canvas>
                        </div>
                    </div>
                     <div id ='binance_mua' class="col-xs-12 col-sm-12 col-md-12 text-center"style= "background-color: darkgray;margin-top: 15px;">Binance Montly Users Analytics</div>
                    <div class="row" style="margin-top: 15px;"> 
                
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="binance-user-line-chart" width="800" height="300"></canvas>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="binance-user-line-chart_new" width="800" height="300"></canvas>
                        </div>
                    </div>
                    <div id ='binance_mtca' class="col-xs-12 col-sm-12 col-md-12 text-center"style= "background-color: darkgray;margin-top: 15px;">Binance Montly Trade Count Analytics</div>
                    <div class="row" style="margin-top: 15px;"> 

                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="binance-trade-calculation" width="800" height="300"></canvas>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="binance-trade-calculation_new" width="800" height="300"></canvas>
                        </div>
                    </div>
                     <div id ='binance_mta' class="col-xs-12 col-sm-12 col-md-12 text-center"style= "background-color: darkgray;margin-top: 15px;">Binance Montly Trade Analytics</div>
                    <div class="row" style="margin-top: 15px;"> 
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="binance-trade-error-count" width="800" height="300"></canvas>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="binance-trade-opportunity-count" width="800" height="300"></canvas>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 15px;"> 
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="binance-trade-cavg-calculation" width="800" height="300"></canvas>
                        </div>
                    </div>
                        <div class="row"></div>
                        <div id ='kraken' class="col-xs-12 col-sm-12 col-md-12 text-center"style= "background-color: darkgray;margin-top: 15px;"> Kraken Montly Trade Volume</div>
                   


                        <!-- kraken charts -->
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="kraken-line-chart" width="800" height="300"></canvas>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="kraken-line-chart_new" width="800" height="300"></canvas>
                        </div>

                    </div>
                      <div id ='kraken_mua' class="col-xs-12 col-sm-12 col-md-12 text-center"style= "background-color: darkgray;margin-top: 15px;">Kraken Montly Users Analytics</div>

                    <div class="row" style="margin-top: 15px;"> 
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="kraken-user-line-chart" width="800" height="300"></canvas>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="kraken-user-line-chart_new" width="800" height="300"></canvas>
                        </div>
                    </div>
                    <div id ='kraken_mtca' class="col-xs-12 col-sm-12 col-md-12 text-center"style= "background-color: darkgray;margin-top: 15px;">Kraken Montly Trade Count Analytics</div>
                    <div class="row" style="margin-top: 15px;"> 

                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="kraken-trade-calculation" width="800" height="300"></canvas>
                        </div>
                       
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="kraken-trade-calculation_new" width="800" height="300"></canvas>
                        </div>
                    </div>
                    <div id ='kraken_mta' class="col-xs-12 col-sm-12 col-md-12 text-center"style= "background-color: darkgray;margin-top: 15px;">Kraken Montly Trade Analytics</div>
                    <div class="row" style="margin-top: 15px;"> 
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="kraken-trade-error-count" width="800" height="300"></canvas>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="kraken-trade-opportunity-count" width="800" height="300"></canvas>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 15px;"> 
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="kraken-trade-cavg-calculation" width="800" height="300"></canvas>
                        </div>
                    </div>
                        <!-- <div id = 'bam' class="col-xs-12 col-sm-12 col-md-12 text-center"style= "background-color: darkgray"> Bam Chart</div> -->


                        <!-- bam charts -->
<!--                         <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="bam-line-chart" width="800" height="300"></canvas>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="bam-line-chart_new" width="800" height="300"></canvas>
                        </div>

                
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="bam-user-line-chart" width="800" height="300"></canvas>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="bam-user-line-chart_new" width="800" height="300"></canvas>
                        </div>


                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="bam-trade-calculation" width="800" height="300"></canvas>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <canvas id="bam-trade-calculation_new" width="800" height="300"></canvas>
                        </div> -->

                    </div>
                </div>
            </div>
        </div>
        <!-- // Widget END -->  
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script>

    window.onload = function() {
        
        // Binance chart 
        new Chart(document.getElementById("binance-line-chart"), {
            type: 'line',
            data: {
                labels: [<?php foreach ($binance_result as $key => $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['total_invest']) ) ?  0 : $value['total_invest']).','; }?>],
                        label: "Invest($) ",
                        borderColor: "#A88157",
                        fill: false,
                    },{ 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['totaolInvestInUSDT']) ) ?  0 : $value['totaolInvestInUSDT']).','; }?>],
                        label: "USDT ",
                        borderColor: "#3e95cd",
                        fill: false
                    },{ 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['totaolInvestInbtc']) ) ?  0 : $value['totaolInvestInbtc']).','; } ?>],
                        label: "BTC",
                        borderColor: "#8e5ea2",
                        fill: false
                    },{ 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['total_sell_in_usdt']) ) ?  0 : $value['total_sell_in_usdt']).','; } ?>],
                        label: "Total Sell($)",
                        borderColor: "#8A064E",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['sell_usdt']) ) ?  0 : $value['sell_usdt']).','; } ?>],
                        label: "Sell Usdt",
                        borderColor: "#323E44",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['sell_btc_in_$']) ) ?  0 : $value['sell_btc_in_$']).','; } ?>],
                        label: "Sell Btc($)",
                        borderColor: "#1313A9",
                        fill: false,
                        hidden: true
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Binance Monthly Trading Volume '
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM'];
                                // do some stuff
                                //multistringText.push('');
                                multistringText.push('invest($) is the total investment of trades.');
                                multistringText.push('USDT is the total of USDT investment of trades.');
                                multistringText.push('BTC is the total of BTC investment of trades.');
                                multistringText.push('Total Sell ($) is the total of sell of trades.');
                                multistringText.push('Sell USDT is the total of sell in USDT of trades.');
                                multistringText.push('Sell BTC is the total of sell in BTC of trades.');

                                return multistringText;
                            }
                        }
                    } 
            }
        });
        new Chart(document.getElementById("binance-line-chart_new"), {
            type: 'line',
            data: {
                labels: [<?php foreach ($binance_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['buy_commision']) ) ?  0 : $value['buy_commision']).','; } ?>],
                        label: "Buy Fee($)",
                        borderColor: "#5765A8",
                        fill: false
                    },{
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['sell_commission']) ) ?  0 : $value['sell_commission']).','; } ?>],
                        label: "Sell Fee ($)",
                        borderColor: "#A88157",
                        fill: false
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Binance Monthly Trading Volume '
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM'];
                                // do some stuff
                                //multistringText.push('');
                                multistringText.push('Buy Fee ($) is the total of Buy commisions of trades.');
                                multistringText.push('Sell Fee ($) is the total of Sell commisions of trades.');
                                return multistringText;
                            }
                        }
                    } 
            }
        });

        new Chart(document.getElementById("binance-user-line-chart"), {
            type: 'line',
            data: {
                labels: [<?php foreach ($binance_users_results as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($binance_users_results as $value) { echo ((empty($value['Total_users']) ) ?  0 : $value['Total_users']).','; }?>],
                        label: "Total Usres ",
                        borderColor: "#3e95cd",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($binance_users_results as $value) { echo ((empty($value['Active_users']) ) ?  0 : $value['Active_users']).','; } ?>],
                        label: "Active Users",
                        borderColor: "#7DA857",
                        fill: false
                    },{ 
                        data: [<?php foreach ($binance_users_results as $value) { echo ((empty($value['Unactive_users']) ) ?  0 : $value['Unactive_users']).','; } ?>],
                        label: "Unactive Users",
                        borderColor: "#8e5ea2",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($binance_users_results as $value) { echo ((empty($value['blockUsers']) ) ?  0 : $value['blockUsers']).','; } ?>],   
                        label: "Block Users",
                        borderColor: "#da3d19",
                        fill: false
                    },{ 
                        data: [<?php foreach ($binance_users_results as $value) { echo ((empty($value['loginCount']) ) ?  0 : $value['loginCount']).','; } ?>],  
                        label: "Users Login",
                        borderColor: "#A88157",
                        fill: false,
                        hidden: true
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Binance User Analytics'
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(tooltipItems,data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM.'];
                                    multistringText.push('Active Users with tading status `ON` and Total balance > 0.');
                                    multistringText.push('Users login with last login time of current date.');
                                    multistringText.push('Blocked users with exchange enable `block`.');
                                // do some stuff
                                //multistringText.push('');
                                return multistringText;
                            }
                        }
                    } 
            }
        });
        new Chart(document.getElementById("binance-user-line-chart_new"), {
            type: 'line',
            data: {
                labels: [<?php foreach ($binance_users_results as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                   
                    {
                        data: [<?php foreach ($binance_users_results as $value) { echo ((empty($value['lthBalance30_grater_per']) ) ?  0 : $value['lthBalance30_grater_per']).','; } ?>],
                        label: "Lth 30% Greater/equal",
                        borderColor: "#0F468C",
                        fill: false,
                        hidden: true
                    },{
                        data: [<?php foreach ($binance_users_results as $value) { echo ((empty($value['lthBalance50_grater_per']) ) ?  0 : $value['lthBalance50_grater_per']).','; } ?>],
                        label: "Lth 50% Greater/equal",
                        borderColor: "#870F8C",
                        fill: false,
                        hidden: true
                    },{
                        data: [<?php foreach ($binance_users_results as $value) { echo ((empty($value['lthBalance70_grater_per']) ) ?  0 : $value['lthBalance70_grater_per']).','; } ?>],
                        label: "Lth 70% Greater/equal",
                        borderColor: "#8C0F35",
                        fill: false
                    },{
                        data: [<?php foreach ($binance_users_results as $value) { echo ((empty($value['lthBalance100_grater_per']) ) ?  0 : $value['lthBalance100_grater_per']).','; } ?>],
                        label: "Lth 100% Greater/equal",
                        borderColor: "#3e95cd",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($binance_users_results as $value) { echo ((empty($value['balanceAre500_Greater']) ) ?  0 : $value['balanceAre500_Greater']).','; } ?>],
                        label: "Total Balance 500 Greater",
                        borderColor: "#8e5ea2",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($binance_users_results as $value) { echo ((empty($value['balanceAre1000_Greater']) ) ?  0 : $value['balanceAre1000_Greater']).','; } ?>],
                        label: "Total Balance 1000 Greater",
                        borderColor: "#A88157",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($binance_users_results as $value) { echo ((empty($value['balanceAre2500_Greater']) ) ?  0 : $value['balanceAre2500_Greater']).','; } ?>],
                        label: "Total Balance 2500 Greater",
                        borderColor: "#da3d19",
                        fill: false
                    },{ 
                        data: [<?php foreach ($binance_users_results as $value) { echo ((empty($value['daily_trades_users_count']) ) ?  0 : $value['daily_trades_users_count']).','; } ?>],
                        label: "Previous Daily Trades Users",
                        borderColor: "#e4d72f",
                        fill: false,
                    },{ 
                        data: [<?php foreach ($binance_users_results as $value) { echo ((empty($value['seven_days_trades_users_count']) ) ?  0 : $value['seven_days_trades_users_count']).','; } ?>],
                        label: "Last 7 Days Trades Users",
                        borderColor: "#449d44",
                        fill: false,
                        
                    }
                    
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Binance User Analytics'
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM'];
                                // do some stuff
                                //multistringText.push('');
                                multistringText.push('Previous daily trade users having last trade buy on previous day.');

                                return multistringText;
                            }
                        }
                    } 
            }
        });

        new Chart(document.getElementById("binance-trade-calculation"), {
            type: 'line',     
            data: {
                labels: [<?php foreach ($binance_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['totalBuyTradeCount']) ) ?  0 : $value['totalBuyTradeCount']).','; }?>],
                        label: "Total Buy Trade Count",
                        borderColor: "#A88157",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['totalSoldTradeCount']) ) ?  0 : $value['totalSoldTradeCount']).','; } ?>],
                        label: "Total Sold Trade Count",
                        borderColor: "#da3d19",
                        fill: false
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Binance Trade count Analytics'
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM'];
                                // do some stuff
                                //multistringText.push('');
                                multistringText.push('Total buy trade count contains  count of "Sold trades in same day" trades also.');
                                multistringText.push('Total Sold trades count despite of when they were bought but sold today.');


                                return multistringText;
                            }
                        }
                    } 
            }
        });
        new Chart(document.getElementById("binance-trade-cavg-calculation"), {
            type: 'line',     
            data: {
                labels: [<?php foreach ($binance_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['total_cavg_ledger_open']) ) ?  0 : $value['total_cavg_ledger_open']).','; }?>],
                        label: "Cost avg open Legder Count",
                        borderColor: "#A88157",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['total_cavg_ledger_closed']) ) ?  0 : $value['total_cavg_ledger_closed']).','; } ?>],
                        label: "Cost avg closed Legder count",
                        borderColor: "#da3d19",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['total_cavg_child_buy']) ) ?  0 : $value['total_cavg_child_buy']).','; } ?>],
                        label: "Total Cost Avg Buy Count",
                        borderColor: "#7DA857",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['total_cavg_child_sold']) ) ?  0 : $value['total_cavg_child_sold']).','; } ?>],
                        label: "Total Cost Avg Sold Count",
                        borderColor: "#3e95cd",
                        fill: false
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Binance Cost average Analytics'
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM'];
                                // do some stuff
                                //multistringText.push('');
                                multistringText.push('Total Cost avg open Legder count.');
                                multistringText.push('Total Cost avg closed Legder count despite of when they were bought but sold today.');
                                multistringText.push('Total Cost Avg Buy Count contains the "Sold count of same day".');
                                multistringText.push('Total Cost Avg Sold Count despite of when they were bought but sold today.');


                                return multistringText;
                            }
                        }
                    } 
            }
        });
        new Chart(document.getElementById("binance-trade-calculation_new"), {
            type: 'line',     
            data: {
                labels: [<?php foreach ($binance_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($binance_result as $value) { echo ($value['expected_btc_count'] + $value['expected_usdt_count']).','; }?>],
                        label: "Total Expected Buy",
                        borderColor: "#8e5ee9",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['expected_usdt_count']) ) ?  0 : $value['expected_usdt_count']).','; }?>],
                        label: "Expected Buy Usdt",
                        borderColor: "#3e95cd",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['expected_btc_count']) ) ?  0 : $value['expected_btc_count']).','; }?>],
                        label: "Expected Buy Btc",
                        borderColor: "#A88157",
                        fill: false
                    },{ 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['totalBuyBtc']) ) ?  0 : $value['totalBuyBtc']).','; } ?>],
                        label: "Btc Buy Trade",
                        borderColor: "#da3d19",
                        fill: false
                    },{ 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['totalSoldBtc']) ) ?  0 : $value['totalSoldBtc']).','; } ?>],
                        label: "Btc Sold Trade",
                        borderColor: "#5765A8",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['totalBuyUsdt']) ) ?  0 : $value['totalBuyUsdt']).','; } ?>],
                        label: "Usdt Buy Trade",
                        borderColor: "#A88157",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['totalSoldUsdt']) ) ?  0 : $value['totalSoldUsdt']).','; } ?>],
                        label: "Usdt Sold Trade",
                        borderColor: "#8e5ea2",
                        fill: false,
                        hidden: true
                    }
                ]  
            },
            options: {
                title: {
                    display: true,
                    text: 'Binance Trade count Analytics'
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM'];
                                // do some stuff
                                //multistringText.push('');
                                return multistringText;
                            }
                        }
                    } 
            }
        });
        new Chart(document.getElementById("binance-trade-error-count"), {
            type: 'line',     
            data: {
                labels: [<?php foreach ($binance_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['total_trades_errors_count']) ) ?  0 : $value['total_trades_errors_count']).','; }?>],
                        label: "Total Error Trades Count",
                        borderColor: "#A88157",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['total_trades_cancelled_count']) ) ?  0 : $value['total_trades_cancelled_count']).','; } ?>],
                        label: "Total Cancelled Trades Count",
                        borderColor: "#da3d19",
                        fill: false
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Binance Trade Errors count Analytics'
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM'];
                                // do some stuff
                                multistringText.push('Total error trades having trades with error while buying or selling.');
                                multistringText.push('Total Cancelled trades having trades got cancelled while buying or selling.');
                                return multistringText;
                            }
                        }
                    } 
            }
        });
        new Chart(document.getElementById("binance-trade-opportunity-count"), {
            type: 'line',     
            data: {
                labels: [<?php foreach ($binance_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['numberOfOpportunities']) ) ?  0 : $value['numberOfOpportunities']).','; }?>],
                        label: "Total Opportunities",
                        borderColor: "#8e5ee9",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['total_btc_opportunities']) ) ?  0 : $value['total_btc_opportunities']).','; }?>],
                        label: "Total BTC Opportunities",
                        borderColor: "#3e95cd",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($binance_result as $value) { echo ((empty($value['total_usdt_opportunities']) ) ?  0 : $value['total_usdt_opportunities']).','; }?>],
                        label: "Total USDT Opportunities",
                        borderColor: "#A88157",
                        fill: false
                    }
                ]  
            },
            options: {
                title: {
                    display: true,
                    text: 'Binance Opportunities count Analytics'
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM'];
                                // do some stuff
                                multistringText.push('Total Opportunities on current date.');
                                multistringText.push('Total BTC Opportunities on current date.');
                                multistringText.push('Total USDT Opportunities on current date.');
                             
                                return multistringText;
                            }
                        }
                    }
            }
        });
        
       
        //kraken
        new Chart(document.getElementById("kraken-line-chart"), {
            type: 'line',
            data: {  
                labels: [<?php foreach ($kraken_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['total_invest']) ) ?  0 : $value['total_invest']).','; }?>],
                        label: "Invest($)",
                        borderColor: "#A88157",
                        fill: false
                    },{ 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['totaolInvestInUSDT']) ) ?  0 : $value['totaolInvestInUSDT']).','; }?>],
                        label: "USDT ",
                        borderColor: "#3e95cd",
                        fill: false
                    },{ 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['totaolInvestInbtc']) ) ?  0 : $value['totaolInvestInbtc']).','; } ?>],
                        label: "BTC",
                        borderColor: "#8e5ea2",
                        fill: false
                    },{ 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['numberOfOpportunities']) ) ?  0 : $value['numberOfOpportunities']).','; } ?>],
                        label: "Opportunity Count",
                        borderColor: "#da3d19",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['total_sell_in_usdt']) ) ?  0 : $value['total_sell_in_usdt']).','; } ?>],
                        label: "Total Sell($)",
                        borderColor: "#8A064E",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['sell_usdt']) ) ?  0 : $value['sell_usdt']).','; } ?>],
                        label: "Sell Usdt",
                        borderColor: "#323E44",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['sell_btc_in_$']) ) ?  0 : $value['sell_btc_in_$']).','; } ?>],
                        label: "Sell Btc($)",
                        borderColor: "#1313A9",
                        fill: false,
                        hidden: true
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Kraken Monthly Trading Volume '
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM'];
                                // do some stuff
                                //multistringText.push('');
                                multistringText.push('invest($) is the total investment of trades.');
                                multistringText.push('USDT is the total of USDT investment of trades.');
                                multistringText.push('BTC is the total of BTC investment of trades.');
                                multistringText.push('Total Sell ($) is the total of sell of trades.');
                                multistringText.push('Sell USDT is the total of sell in USDT of trades.');
                                multistringText.push('Sell BTC is the total of sell in BTC of trades.');

                                return multistringText;
                            }
                        }
                    } 
            }
        });
        new Chart(document.getElementById("kraken-line-chart_new"), {
            type: 'line',
            data: {
                labels: [<?php foreach ($kraken_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['buy_commision_qty']) ) ?  0 : $value['buy_commision_qty']).','; }?>],
                        label: "Buy Fee ($)",
                        borderColor: "#3e95cd",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['sell_commission']) ) ?  0 : $value['sell_commission']).','; } ?>],
                        label: "Sell Fee ($)",
                        borderColor: "#da3d19",
                        fill: false
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Kraken Monthly Trading Volume '
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM'];
                                // do some stuff
                                //multistringText.push('');
                                multistringText.push('Buy Fee ($) is the total of Buy commisions of trades.');
                                multistringText.push('Sell Fee ($) is the total of Sell commisions of trades.');
                                return multistringText;
                            }
                        }
                    }
            }
        });

        new Chart(document.getElementById("kraken-user-line-chart"), {
            type: 'line',
            data: {
                labels: [<?php foreach ($kraken_users_results as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($kraken_users_results as $value) { echo ((empty($value['Total_users']) ) ?  0 : $value['Total_users']).','; }?>],
                        label: "Total Users ",
                        borderColor: "#3e95cd",
                        fill: false
                    },{ 
                        data: [<?php foreach ($kraken_users_results as $value) { echo ((empty($value['Active_users']) ) ?  0 : $value['Active_users']).','; } ?>],
                        label: "Active Users",
                        borderColor: "#7DA857",
                        fill: false
                    },{ 
                        data: [<?php foreach ($kraken_users_results as $value) { echo ((empty($value['Unactive_users']) ) ?  0 : $value['Unactive_users']).','; } ?>],
                        label: "Unactive Users",
                        borderColor: "#8e5ea2",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($kraken_users_results as $value) { echo ((empty($value['blockUsers']) ) ?  0 : $value['blockUsers']).','; } ?>],
                        label: "Block Users",
                        borderColor: "#da3d19",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($kraken_users_results as $value) { echo ((empty($value['loginCount']) ) ?  0 : $value['loginCount']).','; } ?>],
                        label: "Users Login",
                        borderColor: "#A88157",
                        fill: false,
                        hidden: true
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Kraken User Analytics'
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(tooltipItems,data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM.'];
                                    multistringText.push('Active Users with tading status `ON` and Total balance > 0.');
                                    multistringText.push('Users login with last login time of current date.');
                                    multistringText.push('Blocked users with exchange enable `block`.');
                                // do some stuff
                                //multistringText.push('');
                                return multistringText;
                            }
                        }
                    } 
            }
        });
        new Chart(document.getElementById("kraken-user-line-chart_new"), {
            type: 'line',
            data: {
                labels: [<?php foreach ($kraken_users_results as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($kraken_users_results as $value) { echo ((empty($value['lthBalance30_grater_per']) ) ?  0 : $value['lthBalance30_grater_per']).','; } ?>],
                        label: "Lth 30% Greater/equal",
                        borderColor: "#da3d19",
                        fill: false
                    },{ 
                        data: [<?php foreach ($kraken_users_results as $value) { echo ((empty($value['lthBalance50_grater_per']) ) ?  0 : $value['lthBalance50_grater_per']).','; } ?>],
                        label: "Lth 50% Greater/equal",
                        borderColor: "#0F468C",
                        fill: false
                    },{ 
                        data: [<?php foreach ($kraken_users_results as $value) { echo ((empty($value['lthBalance70_grater_per']) ) ?  0 : $value['lthBalance70_grater_per']).','; } ?>],
                        label: "Lth 70% Greater/equal",
                        borderColor: "#5765A8",
                        fill: false
                    },{ 
                        data: [<?php foreach ($kraken_users_results as $value) { echo ((empty($value['lthBalance100_grater_per']) ) ?  0 : $value['lthBalance100_grater_per']).','; } ?>],
                        label: "Lth 100% Greater/equal",
                        borderColor: "#8e5ea2",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($kraken_users_results as $value) { echo ((empty($value['balanceAre500_Greater']) ) ?  0 : $value['balanceAre500_Greater']).','; } ?>],
                        label: "Total Balance 500 Greater",
                        borderColor: "#7DA857",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($kraken_users_results as $value) { echo ((empty($value['balanceAre1000_Greater']) ) ?  0 : $value['balanceAre1000_Greater']).','; } ?>],
                        label: "Total Balance 1000 Greater",
                        borderColor: "#3e95cd",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($kraken_users_results as $value) { echo ((empty($value['balanceAre2500_Greater']) ) ?  0 : $value['balanceAre2500_Greater']).','; } ?>],
                        label: "Total Balance 2500 Greater",
                        borderColor: "#A88157",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($kraken_users_results as $value) { echo ((empty($value['daily_trades_users_count']) ) ?  0 : $value['daily_trades_users_count']).','; } ?>],
                        label: "Previous Daily Trades Users",
                        borderColor: "#e4d72f",
                        fill: false,
                        
                    },{ 
                        data: [<?php foreach ($kraken_users_results as $value) { echo ((empty($value['seven_days_trades_users_count']) ) ?  0 : $value['seven_days_trades_users_count']).','; } ?>],
                        label: "Last 7 Days Trades Users",
                        borderColor: "#449d44",
                        fill: false,
                        
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Kraken User Analytics'
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM'];
                                // do some stuff
                                //multistringText.push('');
                                multistringText.push('Previous daily trade users having last trade buy on previous day.');

                                return multistringText;
                            }
                        }
                    } 
            }
        });

        new Chart(document.getElementById("kraken-trade-calculation"), {
            type: 'line',
            data: {
                labels: [<?php foreach ($kraken_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['totalBuyTradeCount']) ) ?  0 : $value['totalBuyTradeCount']).','; }?>],
                        label: "Total Buy Trade Count",
                        borderColor: "#A88157",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['totalSoldTradeCount']) ) ?  0 : $value['totalSoldTradeCount']).','; } ?>],
                        label: "Total Sold Trade Count",
                        borderColor: "#da3d19",
                        fill: false
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'kraken Trade count Analytics'
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(data) {
                                
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM'];
                                // do some stuff
                                //multistringText.push('');
                                multistringText.push('Total buy trade count contains  count of "Sold trades in same day" trades also.');
                                multistringText.push('Total Sold trades count despite of when they were bought but sold today.');


                                return multistringText;
                            }
                        }
                    } 
            }
        });
        new Chart(document.getElementById("kraken-trade-cavg-calculation"), {
            type: 'line',     
            data: {
                labels: [<?php foreach ($kraken_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['total_cavg_ledger_open']) ) ?  0 : $value['total_cavg_ledger_open']).','; }?>],
                        label: "Cost avg open Legder Count",
                        borderColor: "#A88157",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['total_cavg_ledger_closed']) ) ?  0 : $value['total_cavg_ledger_closed']).','; } ?>],
                        label: "Cost avg closed Legder count",
                        borderColor: "#da3d19",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['total_cavg_child_buy']) ) ?  0 : $value['total_cavg_child_buy']).','; } ?>],
                        label: "Total Cost Avg Buy Count",
                        borderColor: "#7DA857",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['total_cavg_child_sold']) ) ?  0 : $value['total_cavg_child_sold']).','; } ?>],
                        label: "Total Cost Avg Sold Count",
                        borderColor: "#3e95cd",
                        fill: false
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Kraken Cost average Analytics'
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM'];
                                // do some stuff
                                //multistringText.push('');
                                multistringText.push('Total Cost avg open Legder count.');
                                multistringText.push('Total Cost avg closed Legder count despite of when they were bought but sold today.');
                                multistringText.push('Total Cost Avg Buy Count contains the "Sold count of same day".');
                                multistringText.push('Total Cost Avg Sold Count despite of when they were bought but sold today.');


                                return multistringText;
                            }
                        }
                    } 
            }
        });
        new Chart(document.getElementById("kraken-trade-calculation_new"), {
            type: 'line',
            data: {
                labels: [<?php foreach ($kraken_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty(($value['expected_btc_count'] + $value['expected_usdt_count'])) ) ?  0 : ($value['expected_btc_count'] + $value['expected_usdt_count'])).','; }?>],
                        label: "Total Expected Buy",
                        borderColor: "#7DA857",
                        fill: false  
                    },
                    { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['expected_btc_count']) ) ?  0 : $value['expected_btc_count']).','; }?>],
                        label: "Expected Buy Btc",
                        borderColor: "#A88133",
                        fill: false
                    },
                   { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['expected_usdt_count']) ) ?  0 : $value['expected_usdt_count']).','; }?>],
                        label: "Expected Buy Usdt",
                        borderColor: "#3e95cd",
                        fill: false
                    },{ 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['totalBuyBtc']) ) ?  0 : $value['totalBuyBtc']).','; } ?>],
                        label: "Btc Buy Trade",
                        borderColor: "#da3d19",
                        fill: false
                    },{ 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['totalSoldBtc']) ) ?  0 : $value['totalSoldBtc']).','; } ?>],
                        label: "Btc Sold Trade",
                        borderColor: "#A88157",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['totalBuyUsdt']) ) ?  0 : $value['totalBuyUsdt']).','; } ?>],
                        label: "Usdt Buy Trade",
                        borderColor: "#8e5ea2",
                        fill: false,
                        hidden: true
                    },{ 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['totalSoldUsdt']) ) ?  0 : $value['totalSoldUsdt']).','; } ?>],
                        label: "Usdt Sold Trade",
                        borderColor: "#5765A8",
                        fill: false,
                        hidden: true
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'kraken Trade count Analytics'
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM'];
                                // do some stuff
                                //multistringText.push('');
                                return multistringText;
                            }
                        }
                    }
            }
        });
        new Chart(document.getElementById("kraken-trade-error-count"), {
            type: 'line',     
            data: {
                labels: [<?php foreach ($kraken_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['total_trades_errors_count']) ) ?  0 : $value['total_trades_errors_count']).','; }?>],
                        label: "Total Error Trades Count",
                        borderColor: "#A88157",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['total_trades_cancelled_count']) ) ?  0 : $value['total_trades_cancelled_count']).','; } ?>],
                        label: "Total Cancelled Trades Count",
                        borderColor: "#da3d19",
                        fill: false
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'kraken Trade Errors count Analytics'
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM'];
                                // do some stuff
                                multistringText.push('Total error trades having trades with error while buying or selling.');
                                multistringText.push('Total Cancelled trades having trades got cancelled while buying or selling.');
                                return multistringText;
                            }
                        }
                    } 
            }
        });
        new Chart(document.getElementById("kraken-trade-opportunity-count"), {
            type: 'line',     
                        data: {
                labels: [<?php foreach ($kraken_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
                datasets: [
                    { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['numberOfOpportunities']) ) ?  0 : $value['numberOfOpportunities']).','; }?>],
                        label: "Total Opportunities",
                        borderColor: "#8e5ee9",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['total_btc_opportunities']) ) ?  0 : $value['total_btc_opportunities']).','; }?>],
                        label: "Total BTC Opportunities",
                        borderColor: "#3e95cd",
                        fill: false
                    },
                    { 
                        data: [<?php foreach ($kraken_result as $value) { echo ((empty($value['total_usdt_opportunities']) ) ?  0 : $value['total_usdt_opportunities']).','; }?>],
                        label: "Total USDT Opportunities",
                        borderColor: "#A88157",
                        fill: false
                    }
                ]  
            },
            options: {
                title: {
                    display: true,
                    text: 'Kraken Opportunities count Analytics'
                },tooltips: {
                        mode: 'single',
                        callbacks: {
                            afterBody: function(data) {
                                var multistringText = ['This Data is from last 7:59 AM to current 7:59 AM'];
                                // do some stuff
                                multistringText.push('Total Opportunities on current date.');
                                multistringText.push('Total BTC Opportunities on current date.');
                                multistringText.push('Total USDT Opportunities on current date.');
                             
                                return multistringText;
                            }
                        }
                    }
            }
        });

        // bam chart 
        // new Chart(document.getElementById("bam-line-chart"), {
        //     type: 'line',
        //     data: {
        //         labels: [<?php foreach ($bam_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
        //         datasets: [
        //             { 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty($value['totaolInvestInUSDT']) ) ?  0 : $value['totaolInvestInUSDT']).','; }?>],
        //                 label: "USDT ",
        //                 borderColor: "#3e95cd",
        //                 fill: false
        //             }, { 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty($value['totaolInvestInbtc']) ) ?  0 : $value['totaolInvestInbtc']).','; } ?>],
        //                 label: "BTC",
        //                 borderColor: "#8e5ea2",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty($value['numberOfOpportunities']) ) ?  0 : $value['numberOfOpportunities']).','; } ?>],
        //                 label: "Opportunity Count",
        //                 borderColor: "#da3d19",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty($value['total_sell_in_usdt']) ) ?  0 : $value['total_sell_in_usdt']).','; } ?>],
        //                 label: "Total Sell($)",
        //                 borderColor: "#8A064E",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty($value['sell_usdt']) ) ?  0 : $value['sell_usdt']).','; } ?>],
        //                 label: "Sell Usdt",
        //                 borderColor: "#323E44",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty($value['sell_btc_in_$']) ) ?  0 : $value['sell_btc_in_$']).','; } ?>],
        //                 label: "Sell Btc($)",
        //                 borderColor: "#1313A9",
        //                 fill: false
        //             }
        //         ]
        //     },
        //     options: {
        //         title: {
        //             display: true,
        //             text: 'bam Monthly Trading Volume '
        //         }
        //     }
        // });
        // new Chart(document.getElementById("bam-line-chart_new"), {
        //     type: 'line',
        //     data: {
        //         labels: [<?php foreach ($bam_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
        //         datasets: [
        //             { 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty($value['buy_commision_qty']) ) ?  0 : $value['buy_commision_qty']).','; }?>],
        //                 label: "Buy Fee (Coin)",
        //                 borderColor: "#3e95cd",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty($value['sell_fee_respected_coin']) ) ?  0 : $value['sell_fee_respected_coin']).','; } ?>],
        //                 label: "Sell Fee (Coin)",
        //                 borderColor: "#da3d19",
        //                 fill: false
        //             }
        //         ]
        //     },
        //     options: {
        //         title: {
        //             display: true,
        //             text: 'bam Monthly Trading Volume '
        //         }
        //     }
        // });

        // new Chart(document.getElementById("bam-user-line-chart"), {
        //     type: 'line',
        //     data: {
        //         labels: [<?php foreach ($bam_users_results as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
        //         datasets: [
        //             { 
        //                 data: [<?php foreach ($bam_users_results as $value) { echo ((empty($value['Total_users']) ) ?  0 : $value['Total_users']).','; }?>],
        //                 label: "Total Users ",
        //                 borderColor: "#3e95cd",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_users_results as $value) { echo ((empty($value['Active_users']) ) ?  0 : $value['Active_users']).','; } ?>],
        //                 label: "Active Users",
        //                 borderColor: "#8e5ea2",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_users_results as $value) { echo ((empty($value['Unactive_users']) ) ?  0 : $value['Unactive_users']).','; } ?>],
        //                 label: "Unactive Users",
        //                 borderColor: "#5765A8",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_users_results as $value) { echo ((empty($value['blockUsers']) ) ?  0 : $value['blockUsers']).','; } ?>],
        //                 label: "Block Users",
        //                 borderColor: "#A88157",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_users_results as $value) { echo ((empty($value['loginCount']) ) ?  0 : $value['loginCount']).','; } ?>],
        //                 label: "Users Login",
        //                 borderColor: "#da3d19",
        //                 fill: false
        //             }
        //         ]
        //     },
        //     options: {
        //         title: {
        //             display: true,
        //             text: 'bam User Analytics'
        //         }
        //     }
        // });
        // new Chart(document.getElementById("bam-user-line-chart_new"), {
        //     type: 'line',
        //     data: {
        //         labels: [<?php foreach ($bam_users_results as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
        //         datasets: [
        //             { 
        //                 data: [<?php foreach ($bam_users_results as $value) { echo ((empty($value['lthBalance50_grater_per']) ) ?  0 : $value['lthBalance50_grater_per']).','; } ?>],
        //                 label: "Lth 50% Greater/equal",
        //                 borderColor: "#da3d19",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_users_results as $value) { echo ((empty($value['balanceAre500_Greater']) ) ?  0 : $value['balanceAre500_Greater']).','; } ?>],
        //                 label: "Total Balance 500 Greater",
        //                 borderColor: "#8e5ea2",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_users_results as $value) { echo ((empty($value['balanceAre1000_Greater']) ) ?  0 : $value['balanceAre1000_Greater']).','; } ?>],
        //                 label: "Total Balance 1000 Greater",
        //                 borderColor: "#5765A8",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_users_results as $value) { echo ((empty($value['balanceAre2500_Greater']) ) ?  0 : $value['balanceAre2500_Greater']).','; } ?>],
        //                 label: "Total Balance 2500 Greater",
        //                 borderColor: "#A88157",
        //                 fill: false
        //             }
        //         ]
        //     },
        //     options: {
        //         title: {
        //             display: true,
        //             text: 'bam User Analytics'
        //         }
        //     }
        // });

        // new Chart(document.getElementById("bam-trade-calculation"), {
        //     type: 'line',
        //     data: {
        //         labels: [<?php foreach ($bam_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
        //         datasets: [
        //             { 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty(($value['expected_btc_count'] + $value['expected_usdt_count'])) ) ?  0 : ($value['expected_btc_count'] + $value['expected_usdt_count']) ) .','; }?>],
        //                 label: "Total Expected Buy",
        //                 borderColor: "#3e95cd",
        //                 fill: false  
        //             },
        //             { 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty($value['expected_btc_count']) ) ?  0 : $value['expected_btc_count']).','; }?>],
        //                 label: "Expected Buy Btc",
        //                 borderColor: "#5765A8",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty($value['totalBuyTradeCount']) ) ?  0 : $value['totalBuyTradeCount']).','; }?>],
        //                 label: "Total Buy Trade",
        //                 borderColor: "#A88157",
        //                 fill: false
        //             }, { 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty($value['totalSoldTradeCount']) ) ?  0 : $value['totalSoldTradeCount']).','; } ?>],
        //                 label: "Total Sold Trade",
        //                 borderColor: "#8e5ea2",
        //                 fill: false
        //             }
                   
        //         ]
        //     },
        //     options: {
        //         title: {
        //             display: true,
        //             text: 'bam Trade count Analytics'
        //         }
        //     }
        // });
        // new Chart(document.getElementById("bam-trade-calculation_new"), {
        //     type: 'line',
        //     data: {
        //         labels: [<?php foreach ($bam_result as $value) { $res = '"'.$value['created_date']->toDateTime()->format("Y-m-d").'",'; echo $res; }?>],
        //         datasets: [
        //            { 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty($value['expected_usdt_count']) ) ?  0 : $value['expected_usdt_count']).','; }?>],
        //                 label: "Expected Buy Usdt",
        //                 borderColor: "#3e95cd",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty($value['totalBuyBtc']) ) ?  0 : $value['totalBuyBtc']).','; } ?>],
        //                 label: "Btc Buy Trade",
        //                 borderColor: "#da3d19",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty($value['totalSoldBtc']) ) ?  0 : $value['totalSoldBtc']).','; } ?>],
        //                 label: "Btc Sold Trade",
        //                 borderColor: "#5765A8",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty($value['totalBuyUsdt']) ) ?  0 : $value['totalBuyUsdt']).','; } ?>],
        //                 label: "Usdt Buy Trade",
        //                 borderColor: "#8e5ea2",
        //                 fill: false
        //             },{ 
        //                 data: [<?php foreach ($bam_result as $value) { echo ((empty($value['totalSoldUsdt']) ) ?  0 : $value['totalSoldUsdt']).','; } ?>],
        //                 label: "Usdt Sold Trade",
        //                 borderColor: "#A88157",
        //                 fill: false
        //             }
        //         ]
        //     },
        //     options: {
        //         title: {
        //             display: true,
        //             text: 'bam Trade count Analytics'
        //         }
        //     }
        // });

    }

    $(document).ready(function() {

        // $('#bam-line-chart').hide(); 
        // $('#bam-line-chart_new').hide(); 
        // $('#bam-user-line-chart').hide();
        // $('#bam-user-line-chart_new').hide();
        // $('#bam-trade-calculation').hide();
        // $('#bam-trade-calculation_new').hide();

        $( "#binance_chart" ).click(function() {
            $('#binance-line-chart').show();    
            $('#binance-user-line-chart').show();
            $('#binance-trade-calculation').show();
            $('#kraken-trade-calculation').hide();
            // $('#bam-trade-calculation').hide();
            $('#kraken-line-chart').hide();
            $('#kraken_mua').hide();
            $('#kraken').hide();
            $('#kraken_mtca').hide();
            $('#kraken_mta').hide();
            $('#kraken-user-line-chart').hide();
            $('#kraken-trade-error-count').hide();
            $('#kraken-trade-opportunity-count').hide();
            // $('#bam-line-chart').hide();    
            // $('#bam-user-line-chart').hide();
            $('#binance-line-chart_new').show();    
            $('#binance-user-line-chart_new').show();
            $('#binance-trade-calculation_new').show();
            $('#kraken-trade-calculation_new').hide();
            // $('#bam-trade-calculation_new').hide();
            $('#kraken-line-chart_new').hide();
            $('#kraken-user-line-chart_new').hide();
            // $('#bam-line-chart_new').hide();    
            // $('#bam-user-line-chart_new').hide();
            $('#binance-trade-error-count').show();
            $('#binance-trade-opportunity-count').show();
            $('#binance_mua').show();
            $('#binance_mtv').show();
            $('#binance_mtca').show();
            $('#binance_mta').show();
            $('#binance-trade-cavg-calculation').show();
            $('#kraken-trade-cavg-calculation').hide();
            // $('#kraken').hide();
            // $('#bam').hide();


        })
        $( "#kraken_chart" ).click(function() {
            $('#kraken-line-chart').show();
            $('#kraken-user-line-chart').show();
            $('#kraken-trade-calculation').show();
            $('#binance-trade-calculation').hide();
            // $('#bam-trade-calculation').hide();
            // $('#bam-line-chart').hide();    
            // $('#bam-user-line-chart').hide();
            $('#binance-line-chart').hide();    
            $('#binance-user-line-chart').hide();
            $('#binance-trade-error-count').hide();
            $('#kraken-trade-error-count').show();
            $('#kraken-line-chart_new').show();
            $('#kraken-user-line-chart_new').show();
            $('#kraken-trade-calculation_new').show();
            $('#binance-trade-calculation_new').hide();
            // $('#bam-trade-calculation_new').hide();
            // $('#bam-line-chart_new').hide();    
            // $('#bam-user-line-chart_new').hide();
            $('#binance-line-chart_new').hide();    
            $('#binance-user-line-chart_new').hide();
            $('#kraken-trade-opportunity-count').show();
            $('#binance-trade-opportunity-count').hide();
             $('#kraken_mua').show();
            $('#kraken').show();
            $('#kraken_mtca').show();
            $('#kraken_mta').show();
            $('#binance_mua').hide();
            $('#binance_mtv').hide();
            $('#binance_mtca').hide();
            $('#binance_mta').hide();
            $('#binance-trade-cavg-calculation').hide();
            $('#kraken-trade-cavg-calculation').show();

            // $('#kraken').show();
             // $('#bam').hide();

        })
        // $( "#bam_chart" ).click(function() {
        //     $('#bam-line-chart').show(); 
        //     $('#bam-line-chart_new').show(); 
        //     $('#bam-user-line-chart').show();
        //     $('#bam-user-line-chart_new').show();
        //     $('#bam-trade-calculation').show();
        //     $('#bam-trade-calculation_new').show();
        //     $('#binance-trade-calculation').hide();
        //     $('#binance-trade-calculation_new').hide();
        //     $('#kraken-trade-calculation').hide();
        //     $('#kraken-trade-calculation_new').hide();
        //     $('#kraken-line-chart').hide();
        //     $('#kraken-line-chart_new').hide();
        //     $('#kraken-user-line-chart').hide();
        //     $('#kraken-user-line-chart_new').hide();
        //     $('#binance-line-chart').hide();   
        //     $('#binance-line-chart_new').hide();   
        //     $('#binance-user-line-chart').hide();
        //     $('#binance-user-line-chart_new').hide();
        //     $('#binance-trade-error-count').hide();
        //     $('#kraken-trade-error-count').hide();
        //     $('#kraken-trade-opportunity-count').hide();
        //     $('#binance-trade-opportunity-count').hide();
        //     $('#kraken_mta').hide();
        //     $('#kraken').hide();
        //     $('#kraken_mtca').hide();
        //     $('#kraken_mua').hide();
        //     $('#binance_mua').hide();
        //     $('#binance_mtv').hide();
        //     $('#binance_mtca').hide();
        //     $('#binance_mta').hide();


        //     // $('#kraken').hide();
        //     // $('#bam').show();

        // })
        $('#all').click(function(){
            // $('#bam-line-chart').show();    
            // $('#bam-user-line-chart').show();
            // $('#bam-trade-calculation').show();
            $('#binance-trade-calculation').show();
            $('#kraken-trade-calculation').show();
            $('#kraken-line-chart').show();
            $('#kraken-user-line-chart').show();
            $('#binance-line-chart').show();    
            $('#binance-user-line-chart').show();
            // $('#bam-line-chart_new').show();    
            // $('#bam-user-line-chart_new').show();
            // $('#bam-trade-calculation_new').show();
            $('#binance-trade-cavg-calculation').show();
            $('#kraken-trade-cavg-calculation').show();
            $('#binance-trade-calculation_new').show();
            $('#kraken-trade-calculation_new').show();
            $('#kraken-line-chart_new').show();
            $('#kraken-user-line-chart_new').show();
            $('#binance-line-chart_new').show();    
            $('#binance-user-line-chart_new').show();
            $('#binance-trade-error-count').show();
            $('#kraken-trade-error-count').show();
            $('#kraken-trade-opportunity-count').show();
            $('#binance-trade-opportunity-count').show();
             $('#kraken_mua').show();
            $('#kraken').show();
            $('#kraken_mtca').show();
            $('#kraken_mta').show();
            $('#binance_mua').show();
            $('#binance_mtv').show();
            $('#binance_mtca').show();
            $('#binance_mta').show();
            // $('#kraken').show();
             // $('#bam').show();
        })
    });
</script>