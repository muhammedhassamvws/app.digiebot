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
                        <?php if (isset($_GET['testing'])) {$testing = '?testing=true';} else { $testing = "";}?>
            <form method="POST" action="<?php echo SURL; ?>admin/reports/get_user_order_history<?php echo $testing; ?>">
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


<style>

.label{
    font-size: 100%;
}

.label-custom{
    background: #000080;
}
.tabl_type {
    float: left;
    width: 100%;
}

.tabl_type > ul {
    float: left;
    width: 100%;
    list-style: none;
    padding: 0;
    margin: 0;
}

.tabl_type > ul > li {
    float: left;
    width: 100%;
    padding: 15px;
    background: #fff;
    box-shadow: 0px 0px 0px 0px rgba(0,0,0,0);
    border-radius: 8px;
    border-left: 5px solid #eee;
    border-bottom: 5px solid #eee;
    margin-bottom: 10px;
	transition:0.3s;
	border-top: 1px solid #eee;
    border-right: 1px solid #eee;
}
.tabl_type > ul > li:hover {
	 box-shadow: -4px 3px 20px 1px rgba(0,0,0,0.2);
}
.tabl_type > ul > li .list-group {
    margin-bottom: 0;
}

.tabl_type > ul > li .list-group strong {
    margin-right: 15px;
}
</style>


<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Mapped Trades</a></li>
    <li><a data-toggle="tab" href="#menu1">Unmapped Trades</a></li>
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      <h3 style="padding: 10px; margin: 10px; text-align: center; font-weight: bolder; text-decoration: underline; font-size: 50PX; color: navy; font-variant: small-caps; ">Mapped Trades</h3>
    <div class="tabl_type" id="my_tables">
     <ul>
        <?php 
           if(count($mapped) > 0){
                foreach ($mapped as $key => $value) { ?>
                <li class="order_row">
        	<div class="row">
        		<div class="col-xs-12 col-sm-6 col-md-3">
                	<ul class="list-group">
                    	<li class="list-group-item"><strong>Buy Trade ID:</strong><a href="<?php echo $value['buy'][0]['url'] ?>"> <?php echo $value['buy'][0]['id'] ?> </a></li>
                        <li class="list-group-item"><strong>Buy Binance ID:</strong>  <?php echo $value['buy'][0]['bid'] ?></li>
                        <li class="list-group-item"><strong>Buy Time :</strong> <?php echo $value['buy'][0]['btime'] ?></li>
                        <li class="list-group-item"><strong>Buy Price:</strong> <?php echo $value['buy'][0]['price'] ?></li>
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                	<ul class="list-group">
                    	<li class="list-group-item"><strong>Buy Qty:</strong> <?php echo $value['buy'][0]['qty'] ?></li>
                        <li class="list-group-item"><strong>Binance Quantity:</strong><?php 
                            foreach ($value['buy'] as $bvalue) { ?>
                                <label class="label label-custom"> <?php echo$bvalue['bqty'] ?></label>
                            <?php } ?>
                        </li>
                        <li class="list-group-item"><strong>Buy Status:</strong> <?php echo $value['buy'][0]['order_status'] ?></li>
                        <li class="list-group-item"><strong>Status:</strong> <?php echo $value['buy'][0]['status'] ?></li>
                    </ul>
                </div>
               <div class="col-xs-12 col-sm-6 col-md-3">
                	<ul class="list-group">
                    	<li class="list-group-item"><strong>Sell Trade ID:</strong><a href="<?php echo $value['sell'][0]['url'] ?>"> <?php echo $value['sell'][0]['id'] ?></a></li>
                        <li class="list-group-item"><strong>Sell Binance ID:</strong> <?php echo $value['sell'][0]['bid'] ?></li>
                        <li class="list-group-item"><strong>Sell Time :</strong> <?php echo $value['sell'][0]['btime'] ?></li>
                        <li class="list-group-item"><strong>Sell Price:</strong><?php echo $value['sell'][0]['price'] ?></li>
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                	<ul class="list-group">
                    	<li class="list-group-item"><strong>Sell Qty:</strong><?php echo $value['sell'][0]['qty'] ?></li>
                         <li class="list-group-item"><strong>Sell Binance Quantity:</strong><?php 
                            foreach ($value['sell'] as $bvalue) { ?>
                                <label class="label label-custom"> <?php echo $bvalue['bqty'] ?></label>
                            <?php } ?>
                        </li>
                        <li class="list-group-item"><strong>Sell Status:</strong><?php echo $value['sell'][0]['order_status'] ?></li>
                        <li class="list-group-item"><strong>Status:</strong><?php echo $value['sell'][0]['status'] ?></li>
                    </ul>
                </div>
            </div>
        </li>
           <?php  }
           }else{
               ?>
               <div class = "alert alert-danger alert-dismissable"> Sorry! No Trades Exist........</div>
           <?php }
        ?>
    </ul>
    <div id="editor"></div>
</div>
    </div>
    <div id="menu1" class="tab-pane fade">
      <h3 style="padding: 10px; margin: 10px; text-align: center; font-weight: bolder; text-decoration: underline; font-size: 50PX; color: navy; font-variant: small-caps; ">Unmapped Trades</h3>
      <div class="tabl_type" id="my_tables">
    <ul>
        <?php 
           if(count($unmapped) > 0){
                foreach ($unmapped as $key => $row) { ?>
                <li class="order_row">
        	<div class="row">
        		<div class="col-xs-12 col-sm-6 col-md-6">
                	<ul class="list-group">
                    	<li class="list-group-item"><strong>Trade ID:</strong> <?php echo $row[0]['bid'] ?></li>
                        <li class="list-group-item"><strong>Type:</strong> <?php echo $row[0]['type'] ?></li>
                        <li class="list-group-item"><strong>Price:</strong> <?php echo num($row[0]['price']) ?></li>
                        <li class="list-group-item"><strong>Time:</strong><?php echo ($row[0]['btime']) ?></li>
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                	<ul class="list-group">
                    	<li class="list-group-item"><strong>Original Quantity:</strong><?php echo $row['total_qty'] ?></li>
                        <li class="list-group-item"><strong>Quantity:</strong><?php  for ($i=0 ; $i < count($row) -1  ; $i++) {  if($row[$i] != 'total_qty') { ?>
                                <label class="label label-custom"> <?php echo $row[$i]['bqty'] ?></label>
                            <?php }} ?> </li>
                        <li class="list-group-item"><strong>Status:</strong><?php echo $row[0]['status'];  ?></li>
                    </ul>
                </div>
            </div>
        </li>
           <?php  }
           }else{
               ?>
               <div class = "alert alert-danger alert-dismissable"> Sorry! No Trades Exist........</div>
           <?php }
        ?>
    </ul>
    <div id="editor"></div>
</div>
    </div>
  </div>
</div>
</div>
</div>
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
 