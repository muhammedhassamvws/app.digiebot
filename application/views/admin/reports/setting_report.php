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

    <!-- Widget -->
    <div class="widget widget-inverse">
    	<div class="widget-head">
            <div class="row">
                <div class="col-md-12">
                    Settings
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
                <div class="autoresponsive">
                <div class="table-responsive">
                  <table class="example table table-hover" id="example">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Coin Symbol</th>
                          <th>Setting Name</th>
                          <th>Total Oppurtunities</th>
                          <th>Winning Opportunities</th>
                          <th>Losing Opportunities</th>
                          <th>Winning Percentage</th>
                          <th>Losing Percentage</th>
                          <th>Total Winning Profit</th>
                          <th>Total Losing Profit</th>
                          <th>Total Percentage</th>
                          <th>Per Trade Percentage Profit</th>
                          <th>Per Day Percentage Profit</th>
                          <th>Average Time</th>
                          <th>No of Days</th>
                          <th>Start Date</th>
                          <th>End Date</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <?php
$i = 1;
foreach ($setting AS $row) {
    ?>
                      <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?=$row['symbol']?></td>
                          <td><?=$row['title_to_filter']?></td>
                          <td><?=$row['total']?></td>
                          <td><?=number_format($row['winning'], 2)?></td>
                          <td><?=number_format($row['losing'], 2)?></td>
                          <td><?=number_format($row['win_per'], 2)?></td>
                          <td><?=number_format($row['lose_per'], 2)?></td>
                          <td><?=number_format($row['winners'], 2)?></td>
                          <td><?=number_format($row['losers'], 2)?></td>
                          <td><?=number_format($row['total_profit'], 2)?></td>
                          <td><?=number_format($row['per_trade'], 2)?></td>
                          <td><?=number_format($row['per_day'], 2)?></td>
                          <td><?=number_format($row['average_time'], 2)?></td>
                          <td>
                            <?php
$start_date = $row['created_date']->toDatetime()->format("Y-m-d H:i:s");
    $end_date = $row['end_date']->toDatetime()->format("Y-m-d H:i:s");
    $date1 = new DateTime($start_date);
    $date2 = new DateTime($end_date);
    $diff = $date2->diff($date1);
    $total_days = $diff->days;

    echo $total_days;
    ?>

                          </td>
                          <td><?=$row['created_date']->toDatetime()->format("Y-m-d H:i:s");?></td>
                          <td><?=$row['end_date']->toDatetime()->format("Y-m-d H:i:s");?></td>
                          <td>
                          <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal_<?php echo $i; ?>"><i class="fa fa-file"></i></button>
                           <div class="modal fade" id="myModal_<?php echo $i; ?>" role="dialog">
                              <div class="modal-dialog modal-lg" style="width: 90%;">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Report</h4>
                                  </div>
                                  <div class="modal-body autoresponsive">
                                     <div class="table-responsive">
                                      <table class="dynamicTable display table table-stripped" id="my_tables">
                                        <thead>
<?php
$final = $row['result'];
    if (count($final) > 0) {
        $x = 0;
        foreach ($final as $key => $value) {
            if (!empty($value)) {
                if ($x == 0) {
                    $percentile_log_head = $value['percentile_log'];
                    $x++;
                    break;
                } else {
                    continue;
                }
            }
        }
    }
    ?>

                                          <tr>
                                            <th>Opportunity Time</th>
                                            <th>Market Price</th>
                                            <th>Market Time</th>
                                            <th>Barrier Value</th>
                                            <th>Message</th>
                                            <th>Profit Percentage</th>
                                            <th>Profit Time</th>
                                            <th>Profit Time Ago</th>
                                            <th>Loss Percentage</th>
                                            <th>Loss Time</th>
                                            <th>Loss Time Ago</th>
                                            <th>Five Hour Max Profit</th>
                                            <th>Five Hour Min Profit</th>
                                            <?php
foreach ($percentile_log_head as $heading => $val) {?>
                                                <th><?php echo ucfirst(str_replace("_", " ", $heading)) ?></th>
                                            <?php }?>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <?php
$final = $row['result'];
    if (count($final) > 0) {
        foreach ($final as $key => $value) {
            if (!empty($value)) {
                ?>
                                                  <tr>
                                                    <td><?=$key;?></td>
                                                    <td><?=$value['market_value'];?></td>
                                                    <td><?=$value['market_time'];?></td>
                                                    <td><?=num($value['barrier']);?></td>
                                                    <td><?=$value['message'];?></td>
                                                    <td><?=$value['profit_percentage'];?></td>
                                                    <td><?=$value['profit_date'];?></td>
                                                    <td><?=$value['profit_time'];?></td>
                                                    <td><?=$value['loss_percentage'];?></td>
                                                    <td><?=$value['loss_date'];?></td>
                                                    <td><?=$value['loss_time'];?></td>
                                                    <td><?=number_format(($value['high'] - $value['market_value']) / $value['high'] * 100, 2);?></td>
                                                    <td><?=number_format(($value['low'] - $value['market_value']) / $value['low'] * 100, 2);?></td>
                                                   <?php
$percentile_log = $value['percentile_log'];
                foreach ($percentile_log as $heading => $val) {?>
                                                <td><?php echo $val; ?></td>
                                            <?php }?>
                                                </tr>
                                                <?php
}
        }
    }
    ?>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal2_<?php echo $i; ?>"><i class="fa fa-cogs"></i></button>
                           <div class="modal fade" id="myModal2_<?php echo $i; ?>" role="dialog">
                              <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Settings</h4>
                                  </div>
                                  <div class="modal-body">
                                    <?php $setting_arr = $row['settings'];?>
                                    <?php foreach ($setting_arr as $set_key => $set_val) {
        if (!empty($set_val)) {?>
                                         <div class="row">
                                        <div class="col-md-6"><p style="font-weight: bolder; padding:10px;"><?=ucfirst(str_replace("_", " ", $set_key))?></p></div>
                                        <div class="col-md-6"><p style="padding: 10px;"><?=$set_val?></p></div>
                                      </div>
                                    <?php }

    }?>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <a href="<?php echo SURL; ?>admin/reports/delete_setting/<?=trim($row['setting_id']);?>" class="btn btn-danger btn-sm btn-del" id="<?=$row['setting_id'];?>"><i class="fa fa-trash"></i></a>
                          </td>
                      </tr>
                      <?php }?>
                  </table>
                </div>
              </div>
            </div>
        </div>
    </div>
    <!-- // Widget END -->

  </div>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<style>
  .autoresponsive {
    width: 100%;
    overflow-x: auto;
  }
</style>
<script>
  $(document).ready(function() {
$('table.display').dataTable();
} );
</script>
<script>
  $('#example').DataTable( {
        buttons: [
            {
                extend: 'collection',
                text: 'Export',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                    'print'
                ]
            }
        ]
    } );
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
    /*$("body").on("click",".btn-del",function(e){
        var id = $(this).attr('id');
        $.confim({
          title:" You are going to delete something",
          content: " Are you sure you want to delete setting with id = "+ id,
          buttons: {
            confirm: function () {
                window.location.href = "<?php echo SURL; ?>admin/reports/delete_setting/"+$id;
            },
            cancel: function () {
              //code
            },
          }
        });
    });*/

    jQuery("body").on("click","button[data-dismiss='modalx']",function(){
      jQuery(this).closest(".modalx").modal("close");
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