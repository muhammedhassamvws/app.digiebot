<?php //echo "<pre>"; print_r($userFinalData); exit; ?>
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
    <li class="active"><a href="#">Balance Report</a></li>
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
      <?php $filter_user_data = $this->session->userdata('filter_order_data'); ?>
      <div class="widget widget-inverse">
         <div class="widget-body">
            <form method="POST" action="<?php echo SURL; ?>admin/trading_reports/chk_log_duplication">
              <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                <div class="Input_text_s">

                    <label>From Date Range: </label>

                    <input id="filter_by_start_date" name="filter_by_start_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_start_date']) ? $filter_user_data['filter_by_start_date'] : "")?>">
                    
                    </div>
                </div>
               <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                      <label>To Date Range: </label>
                      <input id="filter_by_end_date" name="filter_by_end_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_end_date']) ? $filter_user_data['filter_by_end_date'] : "")?>">
                      
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter Status: </label>
                     <select id="filter_by_status" name="filter_by_status" type="text" class="form-control filter_by_name_margin_bottom_sm">
                       <option value="">Search By Status</option>
                        <option value="buy_submitted"<?php if ($filter_user_data['filter_by_status'] == 'buy_submitted') {?> selected <?php }?>>buy_submitted</option>
                        <option value="sell_submitted"<?php if ($filter_user_data['filter_by_status'] == 'sell_submitted') {?> selected <?php }?>>sell_submitted</option>


                     </select>

                  </div>

               </div>
               <div class="col-xs-12 col-sm-12 col-md-12" style="padding-bottom: 6px;">
                  <div class="Input_text_btn">
                     <label></label>
                     <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
                     <a href="<?php echo SURL; ?>admin/trading_reports/chk_log_duplication/reset_filter" class="btn btn-danger"><i class="fa fa-times-circle"></i>Reset</a>
                  </div>
               </div>
            </div>
            </form>
          </div>
      </div>
    <!-- Widget -->
    <div class="widget widget-inverse">
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
                        
                          <th>User</th>
                          <th>Coin</th>
                          <th>duplication</th>
                          <th>Order_id</th>
                          
                      </tr>
                      </thead>
                      <?php
//initialize balance as per your requirement
$balance = 0;
$i = 1;
$total_origQty = 0;


foreach ($userFinalData AS $row) {

 
    ?>
                      <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?php echo $row['user'] ?></td>
                          <td><?php echo $row['coin'] ?></td>
                          <td><?php echo $row['counts'] ?></td>
                          <td><?php echo $row['oid'] ?></td>
        
                        
                      

                         
                      </tr>
                      <?php }?>

                      <!-- <tfoot>
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
                          
                          </tr>
                      </tfoot> -->
                  </table>
                </div>
            </div>
        </div>
    </div>
    <!-- // Widget END -->

  </div>
</div>
<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>

<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">

  $(function () {

      $('.datetime_picker').datetimepicker();

  });

</script>