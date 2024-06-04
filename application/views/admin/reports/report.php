<script type="text/javascript" src="<?php echo ASSETS ?>report/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ASSETS ?>report/moment.min.js"></script>
<script type="text/javascript" src="<?php echo ASSETS ?>report/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ASSETS ?>report/daterangepicker.css" />
<style type="text/css">
.center_div {
	margin: 0 auto;
	width: 100% /* value of your choice which suits your alignment */
}
.menu {
	font-weight: bold;
	padding: 10px;
	font-size: 0.8em;
	width: 100%;
	background: white;
	position: relative;
	text-align: center;
	margin: 51px 0px;
}
.menu ul {
	margin: 0;
	padding: 0;
	list-style-type: none;
	list-style-image: none;
}
.menu li {
	display: block;
	padding: 15px 0 15px 0;
}
.menu li:hover {
	display: block;
	padding: 15px 0 15px 0;
}
.menu ul li a {
	text-decoration: none;
	margin: 0px;
	color: #fff;
}
.menu ul li a:hover {
	color: #fff;
	text-decoration: none;
}
.menu a {
	text-decoration: none;
	color: white;
}
.menu a:hover {
	text-decoration: none;
	color: white;
}
</style>

<div id="content">
  <div class="heading-buttons bg-white border-bottom innerAll">
    <h1 class="content-heading padding-none pull-left"><?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?> Report</h1>
    <div class="clearfix"></div>
  </div>
  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
      <li><a href="/admin/reports/">Reports</a></li>
    </ul>
  </div>
  <div class="innerAll spacing-x2">
    <div class="container center_div">
      <div class="widget-body"> 
        
        <!-- Table -->
        
        <div style="text-align: center;">
          <h4>User Information</h4>
        </div>
        <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center checkboxs">
          <thead>
            <tr>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Timezone</th>
              <th>Application Mode</th>
              <th>Last Login</th>
              <th>Joined Date</th>
              <th>Google Auth Enabled</th>
            </tr>
          </thead>
          <tbody>
            <tr class="selectable" style="height: 50px;">
              <td class="center"><?php echo $customer['first_name'] ?></td>
              <td><?php echo $customer['last_name'] ?></td>
              <td class="center"><?php echo $customer['email_address']; ?></td>
              <td class="center"><?php echo $customer['timezone']; ?></td>
              <td class="center"><?php echo $customer['application_mode']; ?></td>


              <?php
                if ($customer['last_login_datetime'] == null || $customer['last_login_datetime'] == "") {
                    $login_time = 'N/A';
                } else if (gettype($customer['last_login_datetime']) == 'object') {
                    $login_time = $customer['last_login_datetime']->toDateTime();
                    $login_time = $login_time->format("F j, Y, g:i a");
                } else {
                    $login_time = date("F j, Y, g:i a", strtotime($customer['last_login_datetime']));
                }
              ?>

              <td class="center"><?php echo $login_time; ?></td>
              <td class="center"><?php echo date("F j, Y, g:i a", strtotime($customer['created_date'])) ?></td>
              <td class="center"><?php echo strtoupper($customer['google_auth']) ?></td>
            </tr>
          </tbody>
        </table>
        
        <!-- // Table END --> 
        
      </div>
      <div class="row">  
        <!-- Column -->
        <div class="col-md-6"> 
          <!-- Widget -->
          <div class="widget widget-3"> 
            <!-- Widget heading -->
            <div class="widget-head">
              <h4 class="heading"><span class="glyphicons shopping_cart"><i></i></span>Total Orders</h4>
            </div>
            <!-- // Widget heading END -->
            <div class="widget-body large"> <?php echo $count ?> </div>
          </div>
          <!-- // Widget END --> 
        </div>
        <!-- // Column END --> 
        <!-- Column -->
        <div class="col-md-6"> 
          <!-- Widget -->
          <div class="widget widget-3"> 
            <!-- Widget heading -->   
            <div class="widget-head">
              <h4 class="heading"><span class="glyphicons coins"><i></i></span>Average Profit/Loss</h4>
            </div>
            <!-- // Widget heading END -->
            <div class="widget-body large cancellations"> <span> 
            
				<?php 
					if ($avg_profit > 0) {
					  $val =  "Profit";
					  $class = "text-success";
					} elseif ($avg_profit < 0) {  
					  $val = "Lost";
					  $class = "text-danger";
					} else {
					  $val = "";
					  $class = 'text-default';
					}
				?>
            
            
               <span class=<?php echo $class; ?>>
              <?php   echo $val; ?>
              </span> <span class="<?php echo $class; ?>">
              <?php    echo $avg_profit = number_format((float) $avg_profit, 3, '.', '');;?>
              </span> </span> </div>
          </div>
          
          <!-- // Widget END --> 
          
        </div>
        
        <!-- // Column --> 
        
      </div>
      
      <!-- END ROW -->
      
      <div class="row">
        <div class="col-md-12">
          <div class="widget-body"> 
            
            <!-- Table -->
            
            <div style="text-align: center;">
              <h4>Order History <span class="mini-submenu" style="float: right; margin-bottom: 5px;">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="glyphicon glyphicon-filter"></span> </button>
                <ul class="nav navbar-nav pull-right">
                  <li>
                    <p class="navbar-btn hamburger" style="display: block;"> <a href="#" class="btn btn-info"><span class="glyphicon glyphicon-filter"></span></a> </p>
                  </li>
                  <li>
                    <p class="navbar-btn close" style="display: none;"> <a href="#" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a> </p>
                  </li>
                </ul>
              </h4>
            </div>
            <?php

$session_post_data = $this->session->userdata('filter_data');

?>
            <div class="menu" style="display: block; float:right;">
              <form action="/admin/reports/get_report" method="post">
                <ul>
                  <li>
                    <h3>Filter Form</h3>
                  </li>
                  <li>
                    <select class="form-control" name="coin_filter">
                      <option value="">Select Coin</option>
                      <?php foreach ($coins as $key => $value) {

	if ($session_post_data['coin_filter'] == $value['symbol']) {

		?>
                      <option value="<?php echo $value['symbol']; ?>" selected><?php echo $value['symbol']; ?></option>
                      <?php } else {

		?>
                      <option value="<?php echo $value['symbol']; ?>"><?php echo $value['symbol']; ?></option>
                      <?php }}?>
                    </select>
                  </li>
                  <li>
                    <select class="form-control" name="type_filter">
                      <option value="">Select Order Type</option>
                      <option value="market_order" <?php if ($session_post_data['type_filter'] == 'market_order') {echo 'selected';}?>>Market Order</option>
                      <option value="limit_order" <?php if ($session_post_data['type_filter'] == 'limit_order') {echo 'selected';}?>>Limit Order</option>
                    </select>
                  </li>
                  <li>
                    <input type="text" id="reportrange" name="date_filter" class="form-control">
                     
                  </li>
                  <li>
                    <button type="submit" class="btn btn-success">Apply Filter</button>
                    &nbsp;&nbsp;&nbsp; <a href="/admin/reports/reset_filters" class="btn btn-danger">Reset Filter</a> </li>
                </ul>
              </form>
            </div>
            <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center checkboxs">
              <thead>
                <tr>
                  <th><strong>Coin</strong></th>
                  <th><strong>Price</strong></th>
                  <th><strong>Trail Price</strong></th>
                  <th><strong>Quantity</strong></th>
                  <th class="text-center"><strong>P/L</strong></th>
                  <th class="text-center"><strong>Market(%)</strong></th>
                  <th class="text-center"><strong>Status</strong></th>
                  <th class="text-center"><strong>Profit(%)</strong></th>
                  <th class="text-center"><strong>Actions</strong></th>
                </tr>
              </thead>
              <tbody>
                <?php

if (count($orders) > 0) {

	foreach ($orders as $key => $value) {



		// Get Market Price
        // By ali 9-17  why this fucntion call 
		 $market_value = $this->mod_dashboard->get_market_value($value['symbol']);



		if ($value['status'] != 'new') {

			$market_value333 = num($value['market_value']);

		} else {

			$market_value333 = num($market_value);

		}



		if ($value['status'] == 'new') {

			$current_order_price = num($value['price']);

		} else {

			$current_order_price = num($value['market_value']);

		}



		$current_data = $market_value333 - $current_order_price;

		$market_data = ($current_data * 100 / $market_value333);



		$market_data = number_format((float) $market_data, 2, '.', '');



		if ($market_value333 > $current_order_price) {

			$class = 'success';

		} else {

			$class = 'danger';

		}

		?>
                <?php $logo = $this->mod_coins->get_coin_logo($value['symbol']);?>
                <tr>
                  <td><img src="<?php echo ASSETS; ?>coin_logo/thumbs/<?php echo $logo; ?>" class="img img-circle" data-toggle="tooltip" data-placement="top" title="<?php echo $value['symbol'] ?>"></td>
                  <?php

if ($value['trigger_type'] != 'no' && $value['price'] == '') {?>
                  <td><?php echo strtoupper(str_replace('_', ' ', $value['trigger_type'])); ?></td>
                  <?php } else {?>
                  <td><?php echo num($value['price']); ?></td>
                  <?php }?>
                  <td><?php

if ($value['trail_check'] == 'yes') {

			echo num($value['buy_trail_price']);

		} else {

			echo "-";

		}

		?></td>
                  <td><?php echo $value['quantity']; ?></td>
                  <td class="center"><b><?php echo num($market_value333); ?></b></td>
                  <?php

if ($value['is_sell_order'] != 'sold' && $value['is_sell_order'] != 'yes') {?>
                  <td class="center"><span class="text-<?php echo $class; ?>"><b><?php echo $market_data; ?>%</b></span></td>
                  <?php } else {?>
                  <td class="center"><span class="text-default"><b>-</b></span></td>
                  <?php }?>
                  <td class="center"><!--  <span class="label label-inverse"><?php echo strtoupper($value['application_mode']); ?></span> --> 
                    
                    <span class="label label-success"><?php echo strtoupper($value['status']); ?></span> <span class="custom_refresh" data-id="<?php echo $value['_id']; ?>" order_id="<?php echo $value['binance_order_id']; ?>"> <i class="fa fa-refresh" aria-hidden="true"></i> </span></td>
                  <td class="center"><?php

if ($value['market_sold_price'] != "") {



			$market_sold_price = $value['market_sold_price'];



			$current_data2222 = $market_sold_price - $current_order_price;

			$profit_data = ($current_data2222 * 100 / $market_sold_price);



			$profit_data = number_format((float) $profit_data, 2, '.', '');



			if ($market_sold_price > $current_order_price) {

				$class222 = 'success';

			} else {

				$class222 = 'danger';

			}?>
                    <span class="text-<?php echo $class222; ?>"> <b><?php echo $profit_data; ?>%</b> </span>
                    <?php

} else {?>
                    <span class="text-default"><b>-</b></span>
                    <?php }?></td>
                  <td class="center"><div class="btn-group btn-group-xs ">
                      <?php



		if ($value['status'] == 'FILLED') {



			if ($value['is_sell_order'] == 'yes') {?>
                      <button class="btn btn-info">Submited For Sell</button>
                      <?php } elseif ($value['is_sell_order'] == 'sold') {?>
                      <button class="btn btn-success">Sold</button>
                      <?php }



		}?>
                    </div></td>
                </tr>
                <?php }

}?>
              </tbody>
            </table>
            <div class="page_links text-center"><?php echo $page_links ?></div>
          </div>
          
          <!-- // Table END --> 
          
        </div>
      </div>
      <div class="col-md-12">
        <div style="text-align: center;">
          <h4>Error History </h4>
        </div>
        <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center checkboxs">
          <thead>
            <tr>
              <th><strong>Order ID</strong></th>
              <th><strong>Log Message</strong></th>
            </tr>
          </thead>
          <tbody>
            <?php

if (!empty($error)) {

	foreach ($error as $key => $value) {

		?>
            <tr>
              <td><?php echo $value['order_id']; ?></td>
              <td><?php echo $value['log_msg']; ?></td>
            </tr>
            <?php }

} else {

	echo "<td colspan='2'><div class='alert alert-danger'>No Error Occured</div></td>";

}

?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>

<script type="text/javascript">

                        $(function() {



                            var start = moment().subtract(29, 'days');

                            var end = moment();



                            function cb(start, end) {

                                $('#reportrange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

                            }



                            $('#reportrange').daterangepicker({

                                startDate: start,

                                endDate: end,

                                ranges: {

                                   'Today': [moment(), moment()],

                                   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],

                                   'Last 7 Days': [moment().subtract(6, 'days'), moment()],

                                   'Last 30 Days': [moment().subtract(29, 'days'), moment()],

                                   'This Month': [moment().startOf('month'), moment().endOf('month')],

                                   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]

                                }

                            }, cb);



                            cb(start, end);



                        });

                        </script>
<script type="text/javascript">

$( ".close" ).hide();

$( ".menu" ).hide();

$( ".hamburger" ).click(function() {

  $( ".menu" ).slideToggle( "slow", function() {

    $( ".hamburger" ).hide();

    $( ".close" ).show();

  });

});



$( ".close" ).click(function() {

  $( ".menu" ).slideToggle( "slow", function() {

    $( ".close" ).hide();

    $( ".hamburger" ).show();

  });

});

  </script>