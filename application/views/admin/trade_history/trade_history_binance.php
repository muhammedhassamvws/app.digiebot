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


<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<style>
.failed {
    background: #e8bdbd !important;
}
.success-custom {
    background: rgb(203, 255, 203) !important;
}
.text-success-custom {
    color : #0d420d;
}
.text-danger-custom {
    color : #801515;
}
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Binance Order History</h1>
  <div class="innerAll bg-white border-bottom">
  <ul class="menubar">
  <li class="active"><a href="<?php echo SURL; ?>/admin/Trade_history/get_binance_user_order_history">Binance Order History</a></li>
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

if ($this->session->flashdata('message')) {
	?>
  <div class="alert alert-danger alert-dismissable"><?php echo $this->session->flashdata('message'); ?></div>
  <?php
}

?>
   <?php $filter_user_data = $this->session->userdata('userdata'); ?>
    <!-- Widget -->
    <div class="widget widget-inverse">
      <div class="widget-body padding-bottom-none">
        <!-- Form -->
        <div class="widget widget-inverse">
         <div class="widget-body">
            <form method="POST" action="<?php echo SURL; ?>admin/Trade_history/get_binance_user_order_history">
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
                        <option value ="BNBBTC" >BNBBTC</option>
                        <option value ="BNBUSDT">BNBUSDT</option>
                        <option value ="SXPBTC" >SXPBTC</option>
                        <option value ="WINUSDT">WINUSDT</option>
                        <option value ="SNTBTC" >SNTBTC</option>
                        <option value ="GASBTC"> GASBTC</option>
                        <option value ="OMGBTC"> OMG</option>
                        <option value ="FUNBTC" >FUNBTC</option>
                        <option value ="DATABTC">DATABTC</option>
                        <option value ="CNDBTC" >CNDBTC</option>
                        <option value ="GTOBTC"> GTOBTC</option>
                        <option value ="VETBTC"> VETBTC</option>
                        <option value ="VTHOUSDT">VTHOUSDT</option>
                        <option value ="BTTUSDT">BTTUSDT</option>
                        <option value ="ALGOBTC">ALGOBTC</option>
                        <option value ="XTZBTC" >XTZBTC</option>
                        <option value ="JSTBTC">JSTBTC</option>
                        <option value ="MKRBTC" >MKRBTC</option>  
                        <option value ="IDEXBTC">IDEXBTC</option>
                        <option value ="FETBTC">FETBTC</option>   
                        <option value ="BUSDUSDT">BUSDUSDT</option>  
                        <option value ="PAXUSDT">PAXUSDT</option> 
                        <option value ="TUSDUSDT">TUSDUSDT</option> 

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
                     <a href="<?php echo SURL; ?>admin/trade_history/reset_filters_report" class="btn btn-danger"><i class="fa fa-times-circle"></i>Reset</a>
                  </div>
               </div>
            </div>
            </form>
          </div>
      </div>
        <!-- End Form -->
        <!-- Table -->
        <table id="example" class="table table-bordered" cellspacing="0" width="100%">

          <!-- Table heading -->
          <thead>
            <tr>
              <th>Sr</th>
              <th>Date</th>
              <th>Pair</th>
              <th>Type</th>
              <th>Side</th>
              <th>Price</th>
              <th>Filled</th>
              <th>Order Id</th>
              <th>Commission Asset</th>
              <th>Total</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
           <?php
          //  echo "<pre>";print_r($order_arr);
              if (count($order_arr) > 0) {
                for ($i = 0; $i < count($order_arr); $i++) {
                  ?>
                  <tr class="gradeX">
                      <td><?php echo $i + 1; ?></td>
                      <td><?= date("d-m-Y H:i:s", ((int)($order_arr[$i]['time']/1000)))?></td>
                      <td><?= $pair; ?></td>
                      <td>MARKET</td>
                      <td><?= (!$order_arr[$i]['isBuyer']) ? "<span class='text-danger-custom'>SELL</span>" : "<span class='text-success-custom'>BUY</span>";?></td>
                      <td><?=$order_arr[$i]['price']?></td>
                      <td><?=$order_arr[$i]['qty']?></td>
                      <td><?=$order_arr[$i]['orderId']?></td>
                      <td><?=$order_arr[$i]['commissionAsset']?></td>
                      <td><?=$order_arr[$i]['quoteQty']?></td>
                      <td>FILLED</td>

                      <?php
                        $collection1 = 'buy_orders'; 
                        $collection2 = 'sold_buy_orders';

                        $find_order['admin_id'] = (string)$admin_id;
                        $find_order['binance_order_id'] =  $order_arr[$i]['orderId'];     //[binance_order_id] 

                        $db = $this->mongo_db->customQuery();
                        $record = $db->$collection1->count($find_order);

                        $findOrderSold['admin_id'] = (string)$admin_id;
                        $findOrderSold['$or'] =  [['binance_order_id' => $order_arr[$i]['orderId']], ['binance_order_id_sell'=> $order_arr[$i]['orderId']], ['tradeId' => $order_arr[$i]['orderId']], ['tradeId_sell' => $order_arr[$i]['orderId']]] ;
                        $record1 = $db->$collection2->count($findOrderSold);
                      ?>

                      <td> 
                        <?php if($record > 0 || $record1 > 0){ ?>
                          <span class="fa fa-check" style="color:green; float:right"></span> 
                        <?php } else { ?>
                          <span class="fa fa-close" style="color:red; float:right"></span>
                          <br>
                          <?php if($value->Type != 'sell'){?>
                            <form action="<?php echo SURL; ?>admin/Trade_history/create_child_process" method="POST">
                              <input type="hidden" name="admin_id" value="<?=$admin_id;?>" />
                              <input type="hidden" name="quantity" value="<?=$order_arr[$i]['qty'];?>" />
                              <input type="hidden" name="purchased_price" value="<?=$order_arr[$i]['price'];?>" />
                              <input type="hidden" name="symbol" value="<?=$pair;?>" />
                              <input type="hidden" name="kraken_id" value="<?=$order_arr[$i]['orderId'];?>" />
                              <input type="hidden" name="exchange" value="exchange" />
                              <!-- <button type="submit" class="btn btn-success"></i>Create Child</button> -->
                            </form>
                          <?php } 
                        }?>
                        </td>
                    </tr>
                <?php }
              }?>
          </tbody>
        </table>
        <!-- // Table END -->
      </div>
    </div>
    <!-- // Widget END -->

  </div>
</div>
<script>
$(function() {

$("#example").find("tr").each(function () {
    $("td").filter(function() {
        return $(this).text() === "SELL";
    }).parent().addClass("failed");
    $("td").filter(function() {
        return $(this).text() === "BUY";
    }).parent().addClass("success-custom");
});

// $('.newdynamicTable').dataTable( {
//     "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
//     dom: 'Bfrtip',
//         buttons: [
//             'copy', 'csv', 'excel', 'pdf', 'print'
//         ],
//     "pagingType": "full_numbers",
//     "pageLength": 50,
// });

    $(document).ready(function() {
        //Only needed for the filename of export files.
        //Normally set in the title tag of your page.document.title = 'Simple DataTable';
        //Define hidden columns
        var hCols = [3, 4];
        // DataTable initialisation
        $('#example').DataTable({
            "pageLength" : 50,
            "dom": "<'row'<'col-sm-4'B><'col-sm-2'l><'col-sm-6'p<br/>i>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12'p<br/>i>>",
            "paging": true,
            "buttons": [{
                extend: 'colvis',
                collectionLayout: 'three-column',
                text: function() {
                    var totCols = $('#example thead th').length;
                    var hiddenCols = hCols.length;
                    var shownCols = totCols - hiddenCols;
                    return 'Columns (' + shownCols + ' of ' + totCols + ')';
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
                $('#example').on('column-visibility.dt', function(e, settings, column, state) {
                    var visCols = $('#example thead tr:first th').length;
                    //Below: The minus 2 because of the 2 extra buttons Show all and Restore
                    var tblCols = $('.dt-button-collection li[aria-controls=example] a').length - 2;
                    $('.buttons-colvis[aria-controls=example] span').html('Columns (' + visCols + ' of ' + tblCols + ')');
                    e.stopPropagation();
                });
            }
        });
    });
});
</script>

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