<?php
$score_avg = 0;
$psum = 0;
$nsum = 0;
$sum = 0;
$x = 0;
$count = 0;

foreach ($news as $key => $value) {
    if ($value['score'] >= 0) {
        $psum = $psum + $value['score'];
    } else {
        $nsum = $nsum + $value['score'];
    }
    $count++;
}
$sum = $psum + (-1 * ($nsum));
$x = $psum / $sum;
$score_avg = round($x * 100);

?>


<?php
$global = 'BNBBTC';
$min_not = get_min_notation($global);
$market_value;

$per = $min_not / $market_value;
$new_width = (15 / 100) + $per;

/*echo $market_value . " ====== >" . $min_not . " ====== >" . $per . " ====== >" . $new_width;
exit;*/
?>
<style type="text/css">
  	label.error {
	  /* remove the next line when you have trouble in IE6 with labels in list */
	  color: red;
	  font-style: italic
  	}

  	.alert-ignore{
	    font-size:12px;
	    border-color: #b38d41;
  	}
  	small{
	    color:orange;
	    font-weight: bold;
	}
	.btnbtn{
       padding-left:0px;
    }

	.btnval {
	    width: 9%;
	    font-size: 11px;
	    height: auto;
	    padding-left: 3px;
	    margin-left: 5px;
	    background: #ffffff;
	    border-color: #373737;
	    border-radius: 6px;
	    color: #373737;
	    font-weight: bold;
	}

  .btnval2 {
      width: 9%;
      font-size: 11px;
      height: auto;
      padding-left: 3px;
      margin-left: 5px;
      background: #ffffff;
      border-color: #373737;
      border-radius: 6px;
      color: #373737;
      font-weight: bold;
  }
  .btnval3 {
      width: 9%;
      font-size: 11px;
      height: auto;
      padding-left: 3px;
      margin-left: 5px;
      background: #ffffff;
      border-color: #373737;
      border-radius: 6px;
      color: #373737;
      font-weight: bold;
  }

	.btnval1 {
	    width: 9%;
	    font-size: 11px;
	    height: auto;
	    padding-left: 3px;
	    margin-left: 5px;
	    background: #ffffff;
	    border-color: #373737;
	    border-radius: 6px;
	    color: #373737;
	    font-weight: bold;
	}

	.btn-group, .btn-group-vertical {
	    position: relative;
	    display: inline-block;
	    vertical-align: middle;
	    padding-bottom: 10px;
	}
	.close{
	  margin-top: -2%;
	}

  .slidecontainer {
    width: 100%;
}

.slider {
    -webkit-appearance: none;
    width: 100%;
    height: 25px;
    background:#d3d3d3;
    outline: none;
    opacity: 0.7;
    -webkit-transition: .2s;
    transition: opacity .2s;
}

.slider:hover {
    opacity: 1;
}

.slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 25px;
    height: 25px;
    background: #4CAF50;
    cursor: pointer;
}

.slider::-moz-range-thumb {
    width: 25px;
    height: 25px;
    background: #4CAF50;
    cursor: pointer;
}
.get-relative {
    position: relative;
}

.optimized-loader {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    bottom: 0;
    text-align: center;
    background: rgba(255,255,255,0.7);
    z-index: 99;

}

.optimized-loader img {
    width: 50px;
    height: 50px;
    position: absolute;
    margin: auto;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
}
</style>
<div id="content" class="get-relative">
    <div class="optimized-loader" style="display:none;">
				<img src="https://app.digiebot.com/assets/images/load_cube.gif">
    </div>
  <h1 class="content-heading bg-white border-bottom">Add Buy Order</h1>

  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
        <li><a href="<?php echo SURL; ?>admin/buy_orders/">Buy Order Listing</a></li>
        <li class="active"><a href="#">Add Buy Order</a></li>
    </ul>
    <span class="fa fa-info-circle" style="float: right;font-size: 20px;margin-top: -25px;color: #cb4040;" data-toggle="popover" data-placement="left" data-trigger="hover" data-container="body" data-original-title="Add Buy Manual Order" data-content="Here in Manual Order you can set the price and quantity of order you want to Buy. Moreover you can set the Trail Period of Buy and Sell, AutoSell, sell percentage etc."></span>
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

        <div class="row">
          <div class="col-md-6">
            <div class="widget widget-inverse">
                <form id="buy_order_form" class="form-horizontal margin-none" method="post" action="<?php echo SURL ?>admin/buy_orders/update_manual_entries" novalidate="novalidate">
                <div class="widget-body">
                    <!-- Row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                <label>Enter Order Id</label>
                                <input type="text" class="form-control" name="order_id">
                            </div>
                        </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                <label>Enter User Id</label>
                                <input type="text" class="form-control" name="user_id">
                            </div>
                        </div>
                        </div>     
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                <label>Enter Purchased Price</label>
                                <input type="text" class="form-control" name="purchased_price">
                            </div>
                        </div> 
                        </div>    
                    
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                <label>Enter Trail StopLoss</label>
                                <input type="text" class="form-control" name="iniatial_trail_stop">
                            </div>
                        </div>     
                    
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                <label>Enter Market Value</label>
                                <input type="text" class="form-control" name="market_value">
                            </div>
                        </div>   
                        </div>  
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                <label>Buy Trail Price</label>
                                <input type="text" class="form-control" name="buy_trail_price">
                            </div>
                        </div>     
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                <label>Enter Sell Price</label>
                                <input type="text" class="form-control" name="sell_price">
                            </div>
                            </div>
                        </div>     
                    </div>
                    <!-- // Row END -->
                  <hr class="separator">

                  <!-- Form actions -->
                  <div class="form-actions">
                    <button class="btn btn-success" id="add_order_p" type="submit"><i class="fa fa-check-circle"></i> Add Order </button>
                  </div>
                  <!-- // Form actions END -->

                </div>
                </form>

                <pre> 
                    Buy Message : <?php print_r($buy_message); ?>
                </pre>

                <pre> 
                    Sell Message : <?php print_r($sell_message); ?>
                </pre>
            </div>
          </div>
        </div>
  </div>
</div>
