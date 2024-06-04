
  <link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
  <script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
  <script type="text/javascript" src="<?php  echo SURL ?>assets/dist/jquery-asPieProgress.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>       
  <!--  for pie chart script   -->

  <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
  <link rel='stylesheet' href='https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css'>
  <link rel='stylesheet' href='https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css'>
  <script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-de7e2ef6bfefd24b79a3f68b414b87b8db5b08439cac3f1012092b2290c719cd.js"></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
  <script src='https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js'></script>
  <script src='https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js'></script>
  <script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js'></script>
  <script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js'></script>
  <script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js'></script>
  <script src='https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js'></script>
  <script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js'></script>
  <script src='https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js'></script>
  <script src='https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js'></script>
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
    .ax_1, .ax_2, .ax_3, .ax_4, .ax_5, .ax_6, .ax_7, .ax_8, .ax_9, .ax_10, .ax_11, .ax_12, .ax_13 {
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
    .filter_by_name_margin_bottom_sm{
        margin-bottom: 10px;
        margin-left: -6px;
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
    .circle{
      position: relative;
    }
    .circle strong {
      position: absolute;
      top: 50%;
      left: 50%;
      /* z-index: 2222; */
      transform: translate(-50%, -50%);
      font-size: 15px;
      color: black;
    }
    </style>
<?php //echo "<pre>";  print_r($full_arr); exit; ?>
<div id="content">
<h1 class="content-heading bg-white border-bottom">Testcases Listing</h1>
<div class="innerAll bg-white border-bottom">
</div>

<div class="widget widget-inverse">
  <div class="widget-body">
       <br>
          <table class=" table table-bordered table table-striped" id="datatable" width="100%"> 
            <thead class="thead-dark">
              <tr>
                <th scope="col">Sr#</th>
                <th scope="col">T_id</th>
                <th scope="col">TestCase</th>
                <th scope="col" class="center">Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>TC1</td>
                <td>Users having Cost avg open ledger and also same coin order in open/lth</td>
                <?php $test_status = get_status_of_test_case('TC1'); ?>
                <td class="center"><?php if($test_status > 0){?><i class="fa fa-close" aria-hidden="true" style="color:red"></i><?php }else{ ?><i class="fa fa-check" aria-hidden="true" style="color:green"></i> <?php } ?></td>
                <td><a href="https://app.digiebot.com/admin/Reports/get_TC1_users" target="_blank" class="btn btn-warning">Show Users</a>&nbsp;&nbsp;<a href="https://app.digiebot.com/admin/Reports/get_test_case_one_users/binance" class="btn btn-success">Update Users Binance</a>&nbsp;&nbsp;<a href="https://app.digiebot.com/admin/Reports/get_test_case_one_users/kraken" class="btn btn-success">Update Users Kraken</a></td>
              </tr>
              <tr>
                <td>2</td>
                <td>TC2</td>
                <td>Users having Locked out error ([ETrade:User Locked])</td>
                <?php $test_status = get_status_of_test_case('TC2'); ?>
                <td class="center"><?php if($test_status > 0){?><i class="fa fa-close" aria-hidden="true" style="color:red"></i><?php }else{ ?><i class="fa fa-check" aria-hidden="true" style="color:green"></i> <?php } ?></td>
                <td><a href="https://app.digiebot.com/admin/Reports/get_TC2_users" target="_blank" class="btn btn-warning">Show Users</a>&nbsp;&nbsp;<a href="https://app.digiebot.com/admin/Reports/get_test_case_two_users" class="btn btn-success">Update Users Kraken</a></td>
              </tr>
              <tr>
                <td>3</td>
                <td>TC(user_doubt)</td>
                <td>Trade history user_doubt binance</td>
                <?php $test_status = get_user_doubt_test_case('TCB'); ?>
                <td class="center"><?php if($test_status > 0){?><i class="fa fa-close" aria-hidden="true" style="color:red"></i><?php }else{ ?><i class="fa fa-check" aria-hidden="true" style="color:green"></i> <?php } ?></td>
                <td><a href="https://trading.digiebot.com/trade-history" target="_blank" class="btn btn-warning">Show Users</a>&nbsp; &nbsp;<p>Total user_doubt found (<?php echo $test_status;?>)</p></td>
              </tr>
              <tr>
                <td>4</td>
                <td>TC(user_doubt)</td>
                <td>Trade history user_doubt binance</td>
                <?php $test_status = get_user_doubt_test_case('TCK'); ?>
                <td class="center"><?php if($test_status > 0){?><i class="fa fa-close" aria-hidden="true" style="color:red"></i><?php }else{ ?><i class="fa fa-check" aria-hidden="true" style="color:green"></i> <?php } ?></td>
                <td><a href="https://trading.digiebot.com/trade-history" target="_blank" class="btn btn-warning">Show Users</a>&nbsp; &nbsp;<p>Total user_doubt found (<?php echo $test_status;?>)</p></td>
              </tr>
              <tr>
                <td>5</td>
                <td>TC3</td>
                <td>Users having FILLED_ERROR in their Trades current month</td>
                <?php $test_status = get_status_of_test_case('TC3'); ?>
                <td class="center"><?php if($test_status > 0){?><i class="fa fa-close" aria-hidden="true" style="color:red"></i><?php }else{ ?><i class="fa fa-check" aria-hidden="true" style="color:green"></i> <?php } ?></td>
                <td><a href="https://app.digiebot.com/admin/Reports/get_TC3_users" target="_blank" class="btn btn-warning">Show Users</a>&nbsp;&nbsp;<a href="https://app.digiebot.com/admin/Reports/test_case_filled_error/binance" class="btn btn-success">Update Users Binance</a>&nbsp;&nbsp;<a href="https://app.digiebot.com/admin/Reports/test_case_filled_error/kraken" class="btn btn-success">Update Users Kraken</a></td>
              </tr>
              <tr>
                <td>6</td>
                <td>TC4</td>
                <td>Positive Maximum Accumulation Test Case</td>
                <?php $test_status = get_status_of_test_case('TC4'); ?>
                <td class="center"><?php if($test_status > 0){?><i class="fa fa-close" aria-hidden="true" style="color:red"></i><?php }else{ ?><i class="fa fa-check" aria-hidden="true" style="color:green"></i> <?php } ?></td>
                <td><a href="<?php echo SURL;?>admin/Reports/get_TC4_TC5_users?tc=TC4" target="_blank" class="btn btn-warning">Show Users</a>&nbsp;&nbsp;<a href="https://app.digiebot.com/admin/Reports/test_case_accumulation_finding/positive/binance" class="btn btn-success">Update Users Binance</a>&nbsp;&nbsp;<a href="https://app.digiebot.com/admin/Reports/test_case_accumulation_finding/positive/kraken" class="btn btn-success">Update Users Kraken</a></td>
              </tr>
              <tr>
                <td>7</td>
                <td>TC5</td>
                <td>Negative Maximum Accumulation Test Case</td>
                <?php $test_status = get_status_of_test_case('TC5'); ?>
                <td class="center"><?php if($test_status > 0){?><i class="fa fa-close" aria-hidden="true" style="color:red"></i><?php }else{ ?><i class="fa fa-check" aria-hidden="true" style="color:green"></i> <?php } ?></td>
                <td><a href="<?php echo SURL;?>admin/Reports/get_TC4_TC5_users?tc=TC5" target="_blank" class="btn btn-warning">Show Users</a>&nbsp;&nbsp;<a href="https://app.digiebot.com/admin/Reports/test_case_accumulation_finding/negative/binance" class="btn btn-success">Update Users Binance</a>&nbsp;&nbsp;<a href="https://app.digiebot.com/admin/Reports/test_case_accumulation_finding/negative/kraken" class="btn btn-success">Update Users Kraken</a></td>
              </tr>
              <tr>
                <td>8</td>
                <td>TC6</td>
                <td>Users Balance 100 % consumed last week Test Case</td>
                <?php $test_status = get_status_of_test_case('TC6'); ?>
                <td class="center"><?php if($test_status > 0){?><i class="fa fa-close" aria-hidden="true" style="color:red"></i><?php }else{ ?><i class="fa fa-check" aria-hidden="true" style="color:green"></i> <?php } ?></td>
                <td><a href="<?php echo SURL;?>admin/Reports/get_TC6_users?tc=TC6" target="_blank" class="btn btn-warning">Show Users</a>&nbsp;&nbsp;<a href="https://app.digiebot.com/admin/Reports/test_case_consumed_users/binance" class="btn btn-success">Update Users Binance</a>&nbsp;&nbsp;<a href="https://app.digiebot.com/admin/Reports/test_case_consumed_users/kraken" class="btn btn-success">Update Users Kraken</a></td>
              </tr>
              
            </tbody>
          </table>
          </div>
</div>

<!-- Widget -->
<style>
/* STYLES FOR PROGRESSBARS */
.blink_me {
  background-color:#efcece !important;
  animation: blinker 3s linear infinite;
  border: 1px solid black!important;
}

@keyframes blinker {
  70% {
    opacity: 50%;
  }
}
.green {
  background-color:#cde2be!important;
}
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
.dataTables_filter{
float:right;
}
.paginate_disabled_previous{
margin-right: 10%;
}
.paginate_enabled_next{
margin-left: 10%;
}
.dataTables_length{
margin-bottom: -1%;
}
input[type="checkbox"][readonly] {
  pointer-events: none;
}
</style>
<script>
  $(function(){
           $('#datatable').DataTable({
          
          "aLengthMenu": [[25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],
          "iDisplayLength": 20,
          "dom": "<'row'<'col-sm-4'B><'col-sm-2'l><'col-sm-6'p<br/>i>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12'p<br/>i>>",
          "paging": true,
          "buttons": [{
            extend: 'colvis',
            collectionLayout: 'three-column',
            text: function() {
              var totCols = $('#datatable thead th').length;
              return 'Columns (' + totCols + ' of ' + totCols + ')';
            },
            prefixButtons: [{
              extend: 'colvisGroup',
              text: 'Show all',
              show: ':hidden'
            }, {
              extend: 'colvisRestore',
              text: 'Restore'
            }]
          }, {
            extend: 'collection',
            text: 'Export',
            buttons: [{
                text: 'Excel',
                extend: 'excelHtml5',
                footer: false,
                exportOptions: {
                  columns: ':visible'
                }
              }, {
                text: 'CSV',
                extend: 'csvHtml5',
                fieldSeparator: ';',
                exportOptions: {
                  columns: ':visible'
                }
              }, {
                text: 'PDF Portrait',
                extend: 'pdfHtml5',
                message: '',
                exportOptions: {
                  columns: ':visible'
                }
              }, {
                text: 'PDF Landscape',
                extend: 'pdfHtml5',
                message: '',
                orientation: 'landscape',
                exportOptions: {
                  columns: ':visible'
                }
              }]
            }]
          ,oLanguage: {
              oPaginate: {
                  sNext: '<span class="pagination-default">&#x276f;</span>',
                  sPrevious: '<span class="pagination-default">&#x276e;</span>'
              }
          }
            ,"initComplete": function(settings, json) {
              // Adjust hidden columns counter text in button -->
              $('#datatable').on('column-visibility.dt', function(e, settings, column, state) {
                var visCols = $('#datatable thead tr:first th').length;
                //Below: The minus 2 because of the 2 extra buttons Show all and Restore
                var tblCols = $('.dt-button-collection li[aria-controls=datatable] a').length - 2;
                $('.buttons-colvis[aria-controls=datatable] span').html('Columns (' + visCols + ' of ' + tblCols + ')');
                e.stopPropagation();
              });
            }
          });
    //   $(".paging_simple_numbers").hide();
    $(".dataTables_length").hide();
    $("#datatable_info").hide();
  })

$(".setsize").each(function() {
    $(this).height($(this).width());
});
// });
$(window).on('resize', function(){
$(".setsize").each(function() {
    $(this).height($(this).width());
});
});
</script> 
</div>
