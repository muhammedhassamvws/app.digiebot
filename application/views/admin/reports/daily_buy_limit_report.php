<style>

.af-ledger-titles {
  width: 100%;
  float: left;
  display: inline-block;
}

.af-ledger-titles h2 {
  display: inline-block;
  float: left;
  width: 50%;
  text-align: center;
  border-bottom: 2px solid #eee;
  margin: 0;
  padding-bottom: 10px;
}
.af-ledger-table-debit {
  width: 50%;
  float: left;
  display: inline-table;
  text-align: center;
  border-right: 2px solid #eee;
}
.af-ledger-table-credit {
  width: 50%;
  float: left;
  display: inline-table;
  text-align: center;
}
.af-ledger-table-debit th {
  text-align: center;
  border-bottom: 2px solid #eee;
}
.af-ledger-table-credit th {
  text-align: center;
  border-bottom: 2px solid #eee;
}
.af-ledger-table-debit tbody {
  border-bottom: 2px solid #eee;
  background: #f7f7f7;
}
.af-ledger-table-credit tbody {
  border-bottom: 2px solid #eee;
  background: #f7f7f7;
}
.af-ledger-table-debit tr {
  height: 40px;
}
.af-ledger-table-credit tr {
  height: 40px;
}
.af-ledger-table-debit tfoot {
  border-bottom: 2px solid #eee;
}
.af-ledger-table-credit tfoot {
  border-bottom: 2px solid #eee;
}
</style>

<div id="content">
  <h1 class="content-heading bg-white border-bottom">Reports</h1>
  <div class="innerAll bg-white border-bottom">
  <ul class="menubar">
    <li class=""><a href="<?php echo SURL; ?>admin/reports">Reports</a></li>
    <li class="active"><a href="#">Daily Buy Limit Report</a></li>
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

    <?php $filter_user_data = $this->session->userdata('filter_buy_limit_report'); ?>

      <div class="widget widget-inverse">
         <div class="widget-body">
            <form method="POST" action="<?php echo SURL; ?>admin/daily_buy_limit_report">
              <div class="row">
                <!-- username -->
                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                    <div class="Input_text_s">
                        <label>Filter Username: </label>
                        <input type="text" class="form-control" name="filter_username" id="username" value="<?=(!empty($filter_user_data['filter_username']) ? $filter_user_data['filter_username'] : "")?>">
                    </div>
                </div>
                <!-- exchange -->
                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                    <div class="Input_text_s">
                        <label>Filter Exchange: </label>
                        <select id="filter_by_exchange" name="filter_by_exchange" type="text" class="form-control">
                            <option value="">Search By Exchange</option>
                            
                            <?php if(empty($filter_user_data['filter_by_exchange'])){ $exchange = 'binance'; ?>
                            
                                <option value="binance" selected>Binance</option>
                            
                            <?php }else{ $exchange = $filter_user_data['filter_by_exchange']; ?>
                                
                                <option value="bam"<?php if ($filter_user_data['filter_by_exchange'] == 'bam') {?> selected <?php } ?>   >Bam</option>
                                <option value="binance"<?php if ($filter_user_data['filter_by_exchange'] == 'binance') {?> selected <?php }?>>Binance</option>
                                <option value="kraken"<?php if ($filter_user_data['filter_by_exchange'] == 'kraken') {?> selected <?php }?>>Kraken</option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <!-- trades buy no -->
                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                    <div class="Input_text_s">
                        <label>No. of Trades Buy >= : </label>
                        <input type="number" class="form-control" name="buy_greater_than" id="buy_greater_than" value="<?=(!empty($filter_user_data['buy_greater_than']) ? $filter_user_data['buy_greater_than'] : '')?>">
                    </div>
                </div>
                <!-- limit exceeded -->
                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                    <div class="Input_text_s">
                        <label for="box1">Show Limit Exceeded:</label>
                        <input id="box1" name="limit_exceeded" value="yes" type="checkbox" <?=((!empty($filter_user_data['limit_exceeded']) && $filter_user_data['limit_exceeded'] == "yes") ? "checked" : "")?> />
                    </div>
                </div>
                <!-- show limit -->
                <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                    <div class="Input_text_s">
                        <label for="box2">Show Limit 0:</label>
                        <input id="box2" name="limit_zero" value="yes" type="checkbox" <?=((!empty($filter_user_data['limit_zero']) && $filter_user_data['limit_zero'] == "yes") ? "checked" : "")?> />
                    </div>
                </div>
                <!-- search btn -->
                <div class="col-xs-12 col-sm-12 col-md-12" style="padding-bottom: 6px;">
                    <br>
                    <br>
                    <div class="Input_text_btn">
                        <label></label>
                        <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
                        <a href="<?php echo SURL; ?>admin/daily_buy_limit_report/reset_filters_report" class="btn btn-danger"><i class="fa fa-times-circle"></i>Reset</a>
                    </div>
                </div>

            </div>
            </form>
          </div>
      </div>
    <!-- Widget -->
    <div class="widget widget-inverse">
    	<div class="widget-head">
        Daily Buy Limit Report
        <!-- <span style="float:right;"><button class="btn btn-info" onclick="exportTableToCSV('report.csv')">Export To CSV File</button></span> -->
      </div>
        <div class="widget-body">
        	<div class="row">
                <div class="table-responsive">
                  <table class="table table-hover">
                      <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Total trades buy BTC</th>
                            <th>Daily Buy Worth ($) BTC</th>
                            <th>Daily Buy Limit ($) BTC</th>
                            
                            <th>Total trades buy USDT</th>
                            <th>Daily Buy Worth ($) USDT</th>
                            <th>Daily Buy Limit ($) USDT</th>
                            <th>Total Daily Buy</th>
                            <th>Actions</th>
                        </tr>

                      </thead>
                        <?php
                            //initialize balance as per your requirement
                            if(!empty($buy_limit_arr)){
                            $totalRows = count($buy_limit_arr);
                            for($i =0; $i<$totalRows; $i++){
                        ?>
                        <tr>
                            <td><?= $i+1; ?></td>
                            <td><?= $buy_limit_arr[$i]['username'] ?></td>

                            <td><?= $buy_limit_arr[$i]['BTCTradesTodayCount'] ?></td>
                            <td><?= $buy_limit_arr[$i]['daily_bought_btc_usd_worth'] ?></td>
                            <td><?= number_format($buy_limit_arr[$i]['dailyTradeableBTC_usd_worth'], 2, '.', '') ?></td>
                            
                            <td><?= $buy_limit_arr[$i]['USDTTradesTodayCount'] ?></td>
                            <td><?= $buy_limit_arr[$i]['daily_bought_usdt_usd_worth'] ?></td>
                            <td><?= number_format($buy_limit_arr[$i]['dailyTradeableUSDT_usd_worth'], 2, '.', '') ?></td>
                            <td><?= ($buy_limit_arr[$i]['USDTTradesTodayCount'] + $buy_limit_arr[$i]['BTCTradesTodayCount'])  ?></td>

                            <td><a href="<?=SURL.'admin/order_report/buy_limit_order_report/'.$buy_limit_arr[$i]['user_id'].'/'.$exchange.'/live'?>" target="_blank" title="Order Report" class="btn btn-xs btn-warning"><i class="fa fa-external-link"></i></a></td> 
                        </tr>

                      <?php } } ?>

                  </table>
                </div>
                <?= $pagination ?>
            </div>
        </div>
    </div>
    <!-- // Widget END -->

  </div>
</div>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
</script>

  <script type="text/javascript">
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
  </script>