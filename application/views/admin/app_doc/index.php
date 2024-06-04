<?php $session_post_data = $this->session->userdata('filter-data-buy');?>

<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

<style type="text/css">
	.tab-t
	{
		overflow: visible;
	}

    .tab-pane
    {
        height: 1024px !important;
    }
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">App Documentation</h1>

  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
        <li class="active"><a href="<?php echo SURL; ?>admin/buy_orders">App Docs</a></li>
    </ul>
  </div>

  <div class="innerAll spacing-x2">


        <div class="widget widget-inverse">
            <div class="col-xs-12" style="padding: 25px 20px;">
                <div class="back">
                    <div class="widget widget-inverse widget-scroll">
                        <div class="widget-head" style="height:46px;">
                        	<h4 class="heading" style=" padding-top: 3px;"></h4>
                        </div>
                        <div class="widget-body padding-none">
                             <div class="box-generic">
                <!-- Tabs Heading -->
                <div class="tabsbar">
                    <ul id="tabss">
                        <li class="tab-t"><a class="tabs-tab" href="#tab1-3" data-toggle="tab">Cron Jobs</a></li>
                    </ul>
                </div>


                <!-- // Tabs Heading END -->
                <div class="tab-content">
                    <!-- Tab content -->
                    <div class="tab-pane active" id="tab1-3">
                        <div class="widget-body padding-none">
                        	<table class="footable table table-striped table-primary default footable-loaded">

			<!-- Table heading -->
			<thead>
				<tr>
					<th data-class="expand" class="footable-first-column">Cron Link</th>
					<th class="footable-last-column">Duration</th>
				</tr>
			</thead>
			<!-- // Table heading END -->

			<!-- Table body -->
			<tbody>

				<!-- Table row -->
				<tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>curl -s http://app.digiebot.com/admin/cronjob_auto_sell</td>
					<td>Every minute</td>
				</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>curl -s http://app.digiebot.com/admin/cronjob_auto_buy</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>curl -s http://app.digiebot.com/admin/cronjob_orders_history</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>curl -s http://app.digiebot.com/admin/market_prices_socket</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>curl -s http://app.digiebot.com/admin/chart3/delete_old_data</td>
					<td>Every 4 minutes</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>curl -s http://app.digiebot.com/admin/market_trade_socket	</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>curl -s http://app.digiebot.com/admin/buy_orders/run_trigger_2_auto_sell_and_buy_by_cron_job		</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>curl -s http://app.digiebot.com/admin/market_trade_socket/market_trade_hourly_history			</td>
					<td>Every hour</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>curl -s http://app.digiebot.com/admin/market_depth_socket/</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>curl -s http://app.digiebot.com/admin/chart3/index/NCASHBTC/</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>curl -s http://app.digiebot.com/admin/buy_orders/run_cron_for_inactive_parent</td>
					<td>Every Hour</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>curl -s http://app.digiebot.com/admin/chart3/market_percentage</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>curl -s http://app.digiebot.com/admin/box_trigger_3/run_Box_Trigger_3_auto_sell_and_buy_by_cron_job	</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>curl -s http://app.digiebot.com/admin/market_depth_history_socket		</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/chart3/index/EOSBTC</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/chart3/index/BCNBTC</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/chart3/index/TRXBTC</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/candle_chart/save_candles_fifteen_minutes	</td>
					<td>Every 15 minutes</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/candle_chart/get_market_trade_quarterly_history</td>
					<td>Every 15 minutes</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/Rg_15_trigger/run_trigger_rg_15_auto_sell_and_buy_by_cron_job	</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/chart3/index/POEBTC</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/script/calculate_pressure	</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/coin_meta	</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/barrier_trigger/run_barrier_trigger_auto_sell_and_buy_by_cron_job		</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/coin_meta/get_hourly_depthwall	</td>
					<td>Every 58th minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/chart3/index/NEOBTC	</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/chart3/index/BCCBTC	</td>
					<td>Every minute</td>
					</tr><tr class="gradeX">
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/script/delete_market_depth_history	</td>
					<td>Every Week</td>
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/barrier_trigger/run_crone_for_save_trigger_type</td>
					<td>Every Hour 2nd Minute</td>
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/barrier_trigger/run_crone_for_save_trigger_type</td>
					<td>Every Hour 2nd Minute</td>
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/script_simulator/calculate_coin_meta</td>
					<td>Every Minute</td>
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/script_simulator/calculate_breakable_non_breakable_barrier</td>
					<td>Every Hour</td>
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/script_simulator/calculate_profilt_loss/</td>
					<td>Every 30 minutes</td>
					<td class="expand footable-first-column"><span class="footable-toggle"></span>
					curl -s http://app.digiebot.com/admin/barrier_trigger/cron_job_for_poebtc/</td>
					<td>Every minute</td>
				</tr>
				<!-- // Table row END -->
			</tbody>
			<!-- // Table body END -->

		</table>
                        </div>
                    </div>
                </div>
                    <!-- // Tab content END -->
              </div>
          </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>


  </div>
</div>


