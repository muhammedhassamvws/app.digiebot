<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>

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
      <?php //$filter_user_data = $this->session->userdata('filter_order_data'); ?>
      <div class="widget widget-inverse">
         <div class="widget-body">
            <form method="POST" action="<?php echo SURL; ?>admin/reports/user_trade_profit_report">
              <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter Username: </label>
                     <input type="text" class="form-control" name="filter_username" id="username" value="<?=(!empty($filter_user_data['filter_username']) ? $filter_user_data['filter_username'] : "")?>">
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-12" style="padding-bottom: 6px;">
                  <div class="Input_text_btn">
                     <label></label>
                     <button id="submit-form" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
                     <a href="<?php echo SURL; ?>admin/reports/reset_filters_report/all" class="btn btn-danger"><i class="fa fa-times-circle"></i>Reset</a>
                  </div>
               </div>
            </div>
            </form>
          </div>
      </div>
    <!-- Widget -->
    <div class="widget widget-inverse">
    	<div class="widget-head">
            <div class="row">
                <div class="col-xs-12">
                    Trading Report
                    <span style="float:right;">
                        <!-- <button class="btn btn-info" onclick="exportTableToCSV('report.csv')">Export To CSV File</button> -->
                    </span>
                </div>
            </div>
      	</div>
        <div class="widget-body">
            <div class="row" style="padding-bottom: 8px;margin-bottom: 10px;">
                <span style="float:right">
                    <a href="#!" class="btn btn-primary btn-copy"><img src="http://app.digiebot.com/assets/images/my_icons/copy.png"></a>
                    <a href="javascript:void(0)" class="btn btn-primary btn-csv" onclick="exportTableToCSV('report.csv')"><img src="http://app.digiebot.com/assets/images/my_icons/csv.png"></a>
                    <a href="#!" class="btn btn-primary btn-excel"><img src="http://app.digiebot.com/assets/images/my_icons/excel.png"></a>
                    <a href="javascript:void(0)" class="btn btn-primary btn-pdf"><img src="http://app.digiebot.com/assets/images/my_icons/pdf.png"></a>
                    <a href="#!" class="btn btn-primary btn-print"><img src="http://app.digiebot.com/assets/images/my_icons/print.png"></a>
                </span>
            </div>
        	<div class="row">
                <div class="table-responsive">
                  <table class="table table-hover" id="my_tables">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Trade Id</th>
                          <th>Coin Symbol</th>
                          <th>Total BTC Spent</th>
                          <th>Total BTC Gain</th>
                          <th>Buy Time Fee Deducted (BTC)</th>
                          <th>Sell Time Fee Deducted (BTC)</th>
                          <th>Profit/Loss (BTC)</th>
                          <th>Profit/Loss</th>
                        </tr>
                      </thead>
                      <?php
//initialize balance as per your requirement
$balance = 0;
$i = 1;
foreach ($rearrangedFinalData AS $row) {
    ?>
                      <tr>
                          <td><?php echo $i++; ?></td>
                          <th><?php echo $row['buy_id'] ?></th>
                          <th><?php echo $row['coin'] ?></th>

                          <td><?php echo $row['totalBuyBTC'] . " BTC" ?></td>
                          <td><?php echo $row['totalSoldBTC'] . " BTC" ?></td>

                          <td><?php echo num($row['fee_in_btc']) . " BTC" ?></td>
                          <td><?php echo num($row['sell_fee_in_btc']) . " BTC" ?></td>
                          <?php
$profit_btc = $row['totalSoldBTC'] - ($row['totalBuyBTC'] + $row['fee_in_btc'] + $row['sell_fee_in_btc']);
    ?>
                          <td><?php echo num($profit_btc) . " BTC" ?></td>
                          <td><?php echo number_format($row['ProfitLossBTC'], 2) . " %" ?></td>
                           <?php $totalBTCspent += $row['totalBuyBTC'];
    $totalBTCgain += $row['totalSoldBTC'];?>
                      </tr>
                      <?php }?>
                  </table>

                  <table class="table table-hover">
                    <tr>
                      <th>
                        Total BTC Spent
                      </th>
                      <td>
                        <?php echo $totalBTCspent; ?>
                      </td>
                      <th>
                        Total BTC Gain
                      </th>
                      <td>
                        <?php echo $totalBTCgain; ?>
                      </td>
                      <th>
                        Average Profit
                      </th>
                      <td>
                        <?php echo (($totalBTCgain - $totalBTCspent) / $totalBTCgain) * 100; ?>
                      </td>
                    </tr>
                  </table>
                </div>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
  <script type="text/javascript">
      $(function () {

    var specialElementHandlers = {
        '#editor': function (element,renderer) {
            return true;
        }
    };
 $('.btn-pdf').click(function () {
        var doc = new jsPDF();
        doc.fromHTML($('#my_tables').html(), 15, 15, {
            'width': 170,'elementHandlers': specialElementHandlers
        });
        doc.save('sample-file.pdf');

    //     html2canvas(document.getElementById('my_tables'),{
    //     onrendered:function(canvas){
    //     var doc = new jsPDF("p", "px", [1224, 6468]);
    //     var width_C = doc.internal.pageSize.getWidth()-10;
    //     var height_C = doc.internal.pageSize.getHeight()-10;
    //     var img=canvas.toDataURL("image/png");
    //     doc.addImage(img,'JPEG',5,5,width_C,height_C);
    //     doc.save('report.pdf');
    // }
    // });
});
 });
  </script>