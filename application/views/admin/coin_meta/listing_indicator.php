<style type="text/css">
  .bg-danger{
    background: #ef7070 !important;
    color: #000;
  }
  .bg-info{
    background: #86b1ff !important;
    color: #000;
      }

  .progress {
    height: 20px;
    width: 100%;
    margin-bottom: 20px;
    overflow: hidden;
    background-color: #ededed;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
}
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">coins</h1>
  <div class="innerAll bg-white border-bottom">
 <ul class="menubar">
    <li><a href="<?php echo SURL; ?>admin/reports/">Reports</a></li>
    <li><a href="<?php echo SURL; ?>admin/coin_meta/view_coin_meta">Coin Meta</a></li>
    <li><a href="<?php echo SURL; ?>admin/reports/barrier_listing">Barrier Listing</a></li>
    <li class="active"><a href="<?php echo SURL; ?>admin/reports/indicator_listing">Indicator Listing</a></li>
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

    <!-- Widget -->
    <div class="widget widget-inverse">
      <div class="widget-body padding-bottom-none" id="response_text">
          <div class="widget widget-inverse">

          <!-- Widget heading -->
          <div class="widget-head">
            <h4 class="heading">Search Indicators</h4>
          </div>
          <div class="widget-body">
            <form method="post" action="<?php echo SURL; ?>admin/reports/indicator_listing">
              <div class="row">
              <div class="col-md-2">
                <select class="form-control" name="coin_symbol" id="filter_coin">
                  <option value="">Select Coin</option>
                  <?php foreach ($coins as $key => $value) {
	?>
  <option value="<?php echo $value['symbol'] ?>" <?php if ($coin == $value['symbol']) {echo "selected";}?>><?php echo $value['symbol'] ?></option>
                  <?php }?>
                </select>
              </div>

              <div class="col-md-2">
                <select class="form-control" name="barrier_swing" id="filter_state">
                  <option value="">Select State</option>
                   <option value="HH" <?php if ($barrier_swing == 'HH') {echo "selected";}?>>HH</option>
                   <option value="LH" <?php if ($barrier_swing == 'LH') {echo "selected";}?>>LH</option>
                   <option value="HL" <?php if ($barrier_swing == 'HL') {echo "selected";}?>>HL</option>
                   <option value="LL" <?php if ($barrier_swing == 'LL') {echo "selected";}?>>LL</option>
                </select>
              </div>

              <div class="col-md-2">
                <select class="form-control" name="barrier_status" id="filter_status">
                  <option value="">Select State</option>
                   <option value="very_strong_barrier" <?php if ($barrier_status == 'very_strong_barrier') {echo "selected";}?>>very_strong_barrier</option>
                   <option value="strong_barrier" <?php if ($barrier_status == 'strong_barrier') {echo "selected";}?>>strong_barrier</option>
                   <option value="weak_barrier" <?php if ($barrier_status == 'weak_barrier') {echo "selected";}?>>weak_barrier</option>
                </select>
              </div>

              <div class="col-md-2">
                <select name="breakable_barrier" class="form-control">
                    <option value="">Breakable/Non Breakable</option>
                    <option value="breakable" <?php if ($break_barrier == 'breakable') {?> selected <?php }?>>Breakable</option>
                    <option value="non_breakable" <?php if ($break_barrier == 'non_breakable') {?> selected <?php }?>>Non Breakable</option>
                </select>
              </div>

              <div class="col-md-2">
                <select name="filter_time" class="form-control">
                    <option value="">Select Time Period</option>
                    <option value="-12 hours" <?php if ($filter_time == '-12 hours') {?> selected <?php }?>>12 hour</option>
                    <option value="-1 days" <?php if ($filter_time == '-1 days') {?> selected <?php }?>>1 day</option>
                    <option value="-2 days" <?php if ($filter_time == '-2 days') {?> selected <?php }?>>2 days</option>
                    <option value="-3 days" <?php if ($filter_time == '-3 days') {?> selected <?php }?>>3 days</option>
                    <option value="-7 days" <?php if ($filter_time == '-7 days') {?> selected <?php }?>>1 week</option>
                    <option value="-14 days" <?php if ($filter_time == '-14 days') {?> selected <?php }?>>2 weeks</option>
                    <option value="-1 month" <?php if ($filter_time == '-1 month') {?> selected <?php }?>>1 Month</option>
                </select>
              </div>


              <div class="col-md-2">
                <button id="submit" type="submit" class="btn btn-success">Search</button>
              </div>
            </div>
            </form>
          </div>
        </div>
        <div class="widget widget-inverse col-md-12">

          <!-- Widget heading -->
          <div class="widget-head">
            <h4 class="heading">Barrier Profit Indicator</h4>
          </div>
          <div class="widget-body">

            <!-- 4 Column Grid / One Fourth -->
          <div class="row">
            <div class="col-md-4">
              Avg Profit <?php echo number_format($profit['avg'], 2); ?>
            </div>
            <div class="col-md-4">
              Max Profit <?php echo number_format($profit['max'], 2); ?>
            </div>
            <div class="col-md-4">
              Min Profit <?php echo number_format($profit['min'], 2); ?>
            </div>



            <div class="col-md-4">
              Avg Loss <?php echo number_format($loss['avg'], 2); ?>
            </div>
            <div class="col-md-4">
              Max Loss <?php echo number_format($loss['max'], 2); ?>
            </div>
            <div class="col-md-4">
              Min Loss <?php echo number_format($loss['min'], 2); ?>
            </div>
            </div>
            </div>
        </div>
      </div>
      <div class="widget widget-inverse col-md-12">

          <!-- Widget heading -->
          <div class="widget-head">
            <h4 class="heading">Non-Breakable Barrier Indicator</h4>
          </div>
          <div class="widget-body">

            <!-- 4 Column Grid / One Fourth -->
          <div class="row">
            <div class="col-md-6">
              Total Up Non Breakable <?php echo $up_non_breakable; ?>
            </div>
            <div class="col-md-6">
              Total Down Non Breakable <?php echo $down_non_breakable; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="widget widget-inverse col-md-12">

          <!-- Widget heading -->
          <div class="widget-head">
            <h4 class="heading">Breakable Barrier Indicator</h4>
          </div>
          <div class="widget-body">

            <!-- 4 Column Grid / One Fourth -->
          <div class="row">
           <div class="col-md-6">
              Total Up Breakable <?php echo $up_breakable; ?>
            </div>
             <div class="col-md-6">
              Total Down Breakable <?php echo $down_breakable; ?>
            </div>
          </div>
        </div>
      </div>
        <div class="row">
           <?php
/*$coin = "NCASHBTC";*/
if (count($down_indicators) > 0) {
	?>
            <div class="widget widget-inverse col-md-6">

          <!-- Widget heading -->
          <div class="widget-head">
            <h4 class="heading"><?php echo $coin ?> Down Indicators</h4>
          </div>
          <div class="widget-body">

            <!-- 4 Column Grid / One Fourth -->
            <div class="row">

              <!-- One Fourth Column -->
             <?php foreach ($down_indicators as $key => $value) {
		if ($key == 'black_wall_pressure') {
			$offset = 10;
		}
		if ($key == 'yellow_wall_pressure') {
			$offset = 10;
		}
		if ($key == 'depth_pressure') {
			$offset = 5;
		}
		if ($key == 'seven_level_depth') {
			$offset = 3;
		}
		if ($key == 'bid_contracts' || $key == 'ask_contract' || $key == 'great_wall' || $key == 'barrier_quantity' || $key == 'buyers' || $key == 'sellers' || $key == 'sellers_buyers_per') {
			$offset = $down_indicators[$key]['max'];
		}

		if ($key == 'bid_percentage' || $key == 'ask_percentage' || $key == 'buyers_percentage' || $key == 'sellers_percentage') {
			$offset = 100;
		}

		?>
                <div class="col-md-12">
                <h4><?php echo ucwords(str_replace('_', ' ', $key)) ?></h4>
                <div class="col-md-4">Max: <?php
if ($value['max'] >= 0) {
			$class = "bg-info";
		} else {
			$class = "bg-danger";
		}
		$max_per = abs(($value['max'] / $offset) * 100);
		if ($max_per > 100) {$max_per = 100;}
		?>
                  <div class="progress">
                    <div class="progress-bar-striped progress-bar-animated progress-bar <?php echo $class; ?>" style="width:<?php echo $max_per; ?>%"><?php
if ($key == 'bid_contracts' || $key == 'ask_contract' || $key == 'great_wall' || $key == 'barrier_quantity' || $key == 'buyers' || $key == 'sellers' || $key == 'sellers_buyers_per') {
			echo number_format_short(abs($value['max']));
		} else {
			echo number_format(abs($value['max']), 2);
		}
		?></div>
                  </div>
                </div>
                <div class="col-md-4">Average: <?php
if ($value['avg'] >= 0) {
			$class = "bg-info";
		} else {
			$class = "bg-danger";
		}
		$avg_per = abs(($value['avg'] / $offset) * 100);
		if ($avg_per > 100) {$avg_per = 100;}
		?>
                  <div class="progress">
                    <div class="progress-bar-striped progress-bar-animated progress-bar <?php echo $class; ?>" style="width:<?php echo $avg_per; ?>%"><?php
if ($key == 'bid_contracts' || $key == 'ask_contract' || $key == 'great_wall' || $key == 'barrier_quantity' || $key == 'buyers' || $key == 'sellers' || $key == 'sellers_buyers_per') {
			echo number_format_short(round(abs($value['avg'])));
		} else {
			echo (number_format(abs($value['avg']), 2));
		}
		?></div>
                  </div>


                </div>
                <div class="col-md-4">Min: <?php
if ($value['min'] >= 0) {
			$class = "bg-info";
		} else {
			$class = "bg-danger";
		}
		$min_per = abs(($value['min'] / $offset) * 100);
		if ($min_per > 100) {$min_per = 100;}
		?>
                  <div class="progress">
                    <div class="progress-bar-striped progress-bar-animated progress-bar <?php echo $class; ?>" style="width:<?php echo $min_per; ?>%"><?php
if ($key == 'bid_contracts' || $key == 'ask_contract' || $key == 'great_wall' || $key == 'barrier_quantity' || $key == 'buyers' || $key == 'sellers' || $key == 'sellers_buyers_per') {
			echo number_format_short(abs($value['min']));
		} else {
			echo number_format(abs($value['min']), 2);
		}
		?></div>
                  </div>

                </div>
              </div>
             <?php }?>

              <!-- // One Fourth Column END -->
          </div>
      </div>
      </div>
          <?php }?>

           <?php
/*$coin = "NCASHBTC";*/
if (count($up_indicators) > 0) {
	?>
            <div class="widget widget-inverse col-md-6">

          <!-- Widget heading -->
          <div class="widget-head">
            <h4 class="heading"><?php echo $coin ?> Up Indicators</h4>
          </div>
          <div class="widget-body">

            <!-- 4 Column Grid / One Fourth -->
            <div class="row">

              <!-- One Fourth Column -->

             <?php foreach ($up_indicators as $key => $value) {
		if ($key == 'black_wall_pressure') {
			$offset = 10;
		}
		if ($key == 'yellow_wall_pressure') {
			$offset = 10;
		}
		if ($key == 'depth_pressure') {
			$offset = 5;
		}
		if ($key == 'seven_level_depth') {
			$offset = 3;
		}
		if ($key == 'bid_contracts' || $key == 'ask_contract' || $key == 'great_wall' || $key == 'barrier_quantity' || $key == 'buyers' || $key == 'sellers' || $key == 'sellers_buyers_per') {
			$offset = $up_indicators[$key]['max'];
		}

		if ($key == 'bid_percentage' || $key == 'ask_percentage' || $key == 'buyers_percentage' || $key == 'sellers_percentage') {
			$offset = 100;
		}

		?>
                <div class="col-md-12">
                <h4><?php echo ucwords(str_replace('_', ' ', $key)) ?></h4>
                <div class="col-md-4">Max: <?php
if ($value['max'] >= 0) {
			$class = "bg-info";
		} else {
			$class = "bg-danger";
		}
		$max_per = abs(($value['max'] / $offset) * 100);
		if ($max_per > 100) {$max_per = 100;}
		?>
                  <div class="progress">
                    <div class="progress-bar-striped progress-bar-animated progress-bar <?php echo $class; ?>" style="width:<?php echo $max_per; ?>%"><?php
if ($key == 'bid_contracts' || $key == 'ask_contract' || $key == 'great_wall' || $key == 'barrier_quantity' || $key == 'buyers' || $key == 'sellers' || $key == 'sellers_buyers_per') {
			echo number_format_short(abs($value['max']));
		} else {
			echo number_format(abs($value['max']), 2);
		}
		?></div>
                  </div>
                </div>
                <div class="col-md-4">Average: <?php
if ($value['avg'] >= 0) {
			$class = "bg-info";
		} else {
			$class = "bg-danger";
		}
		$avg_per = abs(($value['avg'] / $offset) * 100);
		if ($avg_per > 100) {$avg_per = 100;}
		?>
                  <div class="progress">
                    <div class="progress-bar-striped progress-bar-animated progress-bar <?php echo $class; ?>" style="width:<?php echo $avg_per; ?>%"><?php
if ($key == 'bid_contracts' || $key == 'ask_contract' || $key == 'great_wall' || $key == 'barrier_quantity' || $key == 'buyers' || $key == 'sellers' || $key == 'sellers_buyers_per') {
			echo number_format_short(round(abs($value['avg'])));
		} else {
			echo abs(number_format($value['avg'], 2));
		}
		?></div>
                  </div>


                </div>
                <div class="col-md-4">Min: <?php
if ($value['min'] >= 0) {
			$class = "bg-info";
		} else {
			$class = "bg-danger";
		}
		$min_per = abs(($value['min'] / $offset) * 100);
		if ($min_per > 100) {$min_per = 100;}
		?>
                  <div class="progress">
                    <div class="progress-bar-striped progress-bar-animated progress-bar <?php echo $class; ?>" style="width:<?php echo $min_per; ?>%"><?php
if ($key == 'bid_contracts' || $key == 'ask_contract' || $key == 'great_wall' || $key == 'barrier_quantity' || $key == 'buyers' || $key == 'sellers' || $key == 'sellers_buyers_per') {
			echo number_format_short(abs($value['min']));
		} else {
			echo number_format(abs($value['min']), 2);
		}
		?></div>
                  </div>

                </div>
              </div>
             <?php }?>

              <!-- // One Fourth Column END -->
          </div>
      </div>
      </div>
          <?php }?>

        </div>
    </div>
    <!-- // Widget END -->

  </div>
</div>
