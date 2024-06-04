<style type="text/css">
  .bg-danger{
    background: red !important;
    color: #fff;
  }

  .progress {
    height: 20px;
    width: 25%;
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
    <li class="active"><a href="<?php echo SURL; ?>admin/reports/barrier_listing">Barrier Listing</a></li>
    <li><a href="<?php echo SURL; ?>admin/reports/indicator_listing">Indicator Listing</a></li>
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

        <div class="row">
                  <?php
/*$coin = "NCASHBTC";*/
if (count($down_indicators) > 0) {
	?>
            <div class="widget widget-inverse col-md-12">

          <!-- Widget heading -->
          <div class="widget-head">
            <h4 class="heading"><?php echo $coin ?> Indicators</h4>
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
		if ($key == 'bid_contracts' || $key == 'ask_contract' || $key == 'great_wall_quantity') {
			if ($key == 'bid_contracts') {
				$offset = $value['bid_percentage'];
			}
			if ($key == 'ask_contract') {
				$offset = $value['ask_percentage'];
			}
			if ($key == 'great_wall') {
				$offset = $value['great_wall_quantity'];
			}
		}

		if ($key == 'bid_percentage' || $key == 'ask_percentage') {
			$offset = 100;
		}

		?>
                <div class="col-md-12">
                <h4><?php echo strtoupper(str_replace('_', ' ', $key)) ?></h4>
                <p><?php echo $value; ?>
                <?php if ($key == 'black_wall_pressure' || $key == 'yellow_wall_pressure' || $key == 'depth_pressure' || $key == 'seven_level_depth' || $key == 'bid_contracts' || $key == 'ask_contract' || $key == 'great_wall_quantity' || $key == 'bid_percentage' || $key == 'ask_percentage') {

			if ($value >= 0) {
				$class = "bg-info";
			} else {
				$class = "bg-danger";
			}
			if ($key == 'bid_contracts' || $key == 'ask_contract') {
				$max_per = $offset;
			} else {
				$max_per = abs(($value / $offset) * 100);
			}

			?>
                   <div class="progress">
                    <div class="progress-bar-striped progress-bar-animated progress-bar <?php echo $class; ?>" style="width:<?php echo $max_per; ?>%"><?php echo $value; ?></div>
                  </div>
                <?php }?>
                </p>
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
