<link rel="stylesheet" type="text/css" href="<?php echo ASSETS; ?>datatables/datatables.min.css"/>
<script type="text/javascript" src="<?php echo ASSETS; ?>datatables/datatables.min.js"></script>
<script src="https://difi.digiebot.com/difi-admin/assets/js/sweetalert.min.js"></script>
<div id="content">
  <?php if($test_case == 'TC4'){ ?>
  <h1 class="content-heading bg-white border-bottom">Test Case(TC4 users)</h1>
  <?php }else{ ?>
  <h1 class="content-heading bg-white border-bottom">Test Case(TC5 users)</h1>
  <?php } ?>
  <div class="innerAll bg-white border-bottom">
    <ul class="menubar">
      <li class="active" style="background: transparent linear-gradient( 
58deg , #ffffff 0%, #bdbdbd 100%) 0% 0% no-repeat padding-box !important;">Users list</li>
    </ul>
  </div>
  <div class="innerAll spacing-x2">
<?php
  if ($this->session->flashdata('err_message')) { ?>
      <div class="alert alert-danger"><?php echo $this->session->flashdata('err_message'); ?></div>
<?php }
      if ($this->session->flashdata('ok_message')) { ?>
        <div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('ok_message'); ?></div>
<?php } ?>
<?php $filter_data = $this->session->userdata('filters_users_tc5');
$tc = $test_case;
if(isset($_COOKIE['sheraz']) && $_COOKIE['sheraz'] == 1){
  echo '<pre>';print_r($filter_data);exit;
}?>
    <!-- Widget -->
<div class="widget widget-inverse">
      <div class="widget-body padding-bottom-none" style="background: #67d2d724 !important;">
          <form method="POST" action="<?php echo SURL; ?>admin/Reports/get_TC4_TC5_users?tc=<?php echo $tc ?>" id="users_filter">
          <div class="row">
              <div class="col-md-3" style="margin: 3px;">
                  <div class="form-group">
                      <label>User id:</label>
                      <input type="text" id="id" class="form-control" name="id" placeholder="Enter user id" value="<?=(!empty($filter_data['id']) ? $filter_data['id'] : "")?>">
                  </div>
              </div>
              <div class="col-md-3" style="margin: 3px;">
                  <div class="form-group">
                      <label>Username:</label>
                      <input type="text" id="username" class="form-control" name="username" placeholder="Search by username" value="<?=(!empty($filter_data['username']) ? $filter_data['username'] : "")?>">
                  </div>
              </div>
              <div class="col-md-3" style="margin: 3px;">
                  <div class="form-group">
                      <label>Coin/Symbol:</label>
                      <input type="text" id="symbol" class="form-control" name="symbol" placeholder="Search by symbol" value="<?=(!empty($filter_data['symbol']) ? $filter_data['symbol'] : "")?>">
                  </div>
              </div>
              <div class="col-md-3" style="margin: 3px;">
                  <div class="form-group">
                      <label>Coin type:</label>
                      <select name="category" id="category" class="form-control">
                        <option value="all" <?php  echo (isset($filter_data['category']) && $filter_data['category'] == 'all') ? 'selected' : ''; ?>>All</option>
                          <option value="BTC" <?php  echo (isset($filter_data['category']) && $filter_data['category'] == 'BTC') ? 'selected' : ''; ?>>BTC</option>
                          <option value="SDT" <?php  echo (isset($filter_data['category']) && $filter_data['category'] == 'SDT') ? 'selected' : ''; ?>>USDT</option>
                        </select>
                  </div>
              </div>
              <div class="col-md-3" style="margin: 3px;">
                  <div class="form-group">
                      <label>Exchange:</label>
                      <select name="exchange" id="exchange" class="form-control">
                          <option value="kraken" <?php  echo (isset($filter_data['exchange']) && $filter_data['exchange'] == 'kraken') ? 'selected' : ''; ?>>Kraken</option>
                          <option value="binance" <?php  echo (isset($filter_data['exchange']) && $filter_data['exchange'] == 'binance') ? 'selected' : ''; ?>>Binance</option>
                        </select>
                  </div>
              </div>
            <div class="col-xs-12 col-sm-12 col-md-2 ax_35" style="margin-top: 27px;">
                <div class="Input_text_btn">
                  <label></label>
                  <button id="submit-form" class="btn nsi_btn button3"><i class="glyphicon glyphicon-filter"></i>Search</button>
                  <a href="<?php echo SURL; ?>admin/Reports/resetFilters_tc5"class="btn btn-danger button3">Reset</a>
                  </span>   
                </div>
            </div>
          </div>
        </form>
    </div>
</div>

<div class="widget widget-inverse" style="overflow:auto">
  <div class="widget-head" style="background-color: white !important; margin-top:3px; margin-right: 15px; margin-bottom: 2px;">
    <!-- <button class="btn nsi_btn button3 pull-right btn-md" type="button" name="csv" id="csv" value="csv" data-toggle="tooltip" title="CSV Report" style="margin-top: 0px;">
      <li class="fa fa-print"></li> Export CSV
    </button> -->
  </div>
    <div class="widget-body padding-bottom-none">
        <!-- Table -->
        <table class=" table table-bordered table-hover" id="datatable" width="100%"> 
          <!-- Table heading -->
          <thead>
            <tr>
              <th>Sr</th>
              <th>User ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Username</th>
              <th>Email Address</th>
              <th>Symbol</th>
              <th>Order id</th>
              <th>Accumulation</th>
              <th>Sell Date</th>
              <th>Exchange</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
<?php
if (count($TC5_users) > 0) {
	for ($i = 0; $i < count($TC5_users); $i++) {
		?>
        <?php if($TC5_users[$i]['tokenStatus'] == 'blacklisted'){ 
            $danger_class = "style='background-color:#f2dede !important;'";
            $tooltip="title='This Token is Blacklisted'";
         }else{
            $danger_class = "";
            $tooltip="";
         } ?>
         <tr class="gradeX" <?php echo $danger_class; ?> <?php echo $tooltip; ?> data-id="<?php echo $TC5_users[$i]['_id']; ?>">
              <td><?php echo $i + 1; ?></td>
              <td><?php echo $TC5_users[$i]['user_id']; ?></td>
              <td><?php echo $TC5_users[$i]['first_name']; ?></td>
              <td><?php echo $TC5_users[$i]['last_name']; ?></td>
              <td><?php echo $TC5_users[$i]['username']; ?></td>
              <td><?php echo $TC5_users[$i]['email_address']; ?></td>
              <td><?php echo $TC5_users[$i]['symbol']; ?></td>
              <td><?php echo $TC5_users[$i]['order_id']; ?></td>
              <td><?php echo $TC5_users[$i]['accumulations']; ?></td>
              <td><?php echo $TC5_users[$i]['sell_date'] ? date('Y-m-d H:i:s', (string)$TC5_users[$i]['sell_date'] / 1000) : '____'; ?></td>
              <td><?php echo $TC5_users[$i]['exchange']; ?></td>
              <!-- <td><?php //echo //get_price_updated_at((string)$TC5_users[$i]['id']); ?></td> -->
              <td class="">
                <?php if(isset($TC5_users[$i]['status']) && $TC5_users[$i]['status'] != '') {?>
                  <?php echo $TC5_users[$i]['status']; ?>
                <?php }else{ ?>
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action<span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?php 
                            $doc_id = $TC5_users[$i]['_id']; 
                            $status = $TC5_users[$i]['status']; 
                        ?>
                        <li><a href="#" onclick="updateStatus('<?php echo (string)$doc_id ?>', 'acknowledged')">Acknowledged</a></li>
                        <li><a href="#" onclick="updateStatus('<?php echo (string)$doc_id ?>', 'fixed')">Fixed</a></li>
                    </ul>
                </div> 
                <?php } ?>
              </td>
            </tr>
           <?php } }else{?>
            <tr><td colspan="11" class="text-center">No Users found</td></tr>
           <?php } ?>
          </tbody>
        </table>
        <div ><?php echo $links; ?></div>
        <div ><?php echo "Total: <b>".$total."</b>"; ?></div>
        <!-- // Table END -->
      </div>
    </div>
    <!-- // Widget END -->
  </div>
</div>

<style type="text/css">
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
</style>
<script>
$(function(){
setTimeout(function(){ $(".alert").fadeOut(); }, 2000);
});
function updateStatus(paramId, paramStatus){
    swal({
        title: "Are you sure!",
        text: "You want to mark "+ paramStatus + "?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((markblacklist) => {
        if (markblacklist) {
          //this.style.display = 'none'
          var doc_id = paramId;
          var status = paramStatus;
          $.ajax({
            url: '<?php echo SURL ?>admin/Reports/markUserStatus/',
            type: 'POST',
            data: {
              doc_id: doc_id,
              status: status
            },
            success: function(result) {
              var obj = $.parseJSON(result);
              if (obj.success == true) {
                //$('"#'+param+'"').fadeIn();
                swal("Success!", "User Marked " + status + "...", "success");
                setTimeout(function(){ location.reload(); }, 2000);
              }
              if (obj.success == false) {
                swal("Error! ", "Something went wrong.", "error");
              }
            }
          });
        } else {
          swal("Not marked "+ status );
        }
      });
      //console.log('blacklist'); 
}

$('body').on('click', '#csv', function() {
    $('#users_filter').append('<input type="hidden" name="csv" value="csv">');
    $('#users_filter').submit();
    $('#users_filter').append('<input type="hidden" name="csv" value="">');
});
</script> 


