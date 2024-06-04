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
            <form method="POST" action="<?php echo SURL; ?>admin/reports/user_ledger">
              <div class="row">
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
                     <label>Filter Username: </label>
                     <input type="text" class="form-control" name="filter_username" id="username" value="<?=(!empty($filter_user_data['filter_username']) ? $filter_user_data['filter_username'] : "")?>">
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-12" style="padding-bottom: 6px;">
                  <div class="Input_text_btn">
                     <label></label>
                     <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
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
        General Ledger
        <span style="float:right;"><button class="btn btn-info" onclick="exportTableToCSV('report.csv')">Export To CSV File</button></span>
      </div>
        <div class="widget-body">
        	<div class="row">
            	<!-- <div class="af-ledger-titles">
                	<h2>Debit</h2>
                  <h2>Credit</h2>
                </div> -->
                <div class="table-responsive">
                  <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Date</th>
                          <th>Market Price</th>
                          <th>Invoice No</th>
                          <th>Credit</th>
                          <th>Debit</th>
                          <th>Balance</th>
                          <th>origQty</th>
                          <th>executedQty</th>
                          <th>side</th>
                          <th>status</th>
                      </tr>
                      </thead>
                      <?php
//initialize balance as per your requirement
$balance = 0;
$i = 1;
$total_origQty = 0;
$total_executedQty = 0;
$total_buy = 0;
$total_sell = 0;
foreach ($rearrangedFinalData AS $row) {
  $total_origQty += $row['origQty'];
  $total_executedQty += $row['executedQty'];
    if($row['status'] == 'FILLED'){
      if($row['side'] == 'SELL'){
          $total_sell += $row['qty'];
      }else{
        $total_buy  += $row['qty'];
      }
    }
    ?>
                      <tr>
                          <td><?php echo $i++; ?></td>
                          <th><?php echo $row['time'] ?></th>
                          <td><?php echo $row['price'] ?></td>
                          <td><?php echo $row['binance_id'] ?></td>
                          <td style="color:blue;"><?php echo $row['type'] == "buy" ? $row['debit'] : '' ?></td>
                          <td style="color:red;"><?php echo $row['type'] == "sell" ? $row['credit'] : '' ?></td>
                          <td><?php echo $balance = ($balance + $row['debit'] - $row['credit']) ?></td>
                          <td><?php echo $row['origQty']; ?></td>
                          <td><?php echo $row['executedQty']; ?></td>
                          <td><?php echo $row['side'] ?></td>
                          <td><?php echo $row['status'] ?></td>
                      </tr>
                      <?php }?>

                      <tfoot>
                          <tr>
                            <td><td>
                            <td><td>
                            <td><td>
                            <td><td>
                            <td><td>
                            <td><td>
                            
                            <td><?php echo $total_origQty; ?></td>
                            <td><?php echo $total_executedQty; ?></td>
                            <td><?php echo $total_buy; ?></td>
                            <td><?php echo $total_sell; ?></td>
                          </tr>
                      </tfoot>
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