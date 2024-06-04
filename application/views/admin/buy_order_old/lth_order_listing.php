<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Long Term Hold</h1>

  <div class="innerAll bg-white border-bottom">
  <ul class="menubar">
    <li class=""><a href="<?php echo SURL; ?>admin/buy_orders">Buy Order Listing</a></li>
    <li class="active"><a href="#">LTH Listing</a></li>
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

      <div class="widget-body">
<style>
.checktypeTabs {
    float: left;
    width: 100%;
}

.checktypeTabs ul.nav.nav-tabs {
    float: left;
    width: 100%;
    border-bottom: none;
}

.checktypeTabs ul.nav.nav-tabs > li {
    float: left;
    margin-bottom: -1px;
}

.checktypeTabs ul.nav.nav-tabs > li > a {
    float: left;
    padding: 15px 20px;
    border: none;
    background: #e6e6e6;
    color: #000;
    font-weight: bold;
    margin-right: 2px;
    border: 1px solid #c1c1c1;
    text-shadow: none;
}

.checktypeTabs ul.nav.nav-tabs > li.active {
    border: none !important;
}

.checktypeTabs ul.nav.nav-tabs > li.active > a {
    border: 1px solid #c1c1c1;
    box-shadow: none;
    background: #fff;
    color: #000;
    border-bottom-color: #fff;
}

.checktypeTabs .tab-content {
    float: left;
    width: 100%;
    border: 1px solid #c1c1c1;
    padding: 25px;
    border-radius: 0 7px 7px 7px;
    background: #fff;
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
.tabfot {
    float: left;
    width: 100%;
    border-top: 1px solid #eee;
    padding-top: 15px;
    margin-bottom: -10px;
	margin-top: 15px;
}

.tabfot .btn {
    border-radius: 6px;
}


.modal {
  text-align: center;
  padding: 0!important;
}

.modal:before {
  content: '';
  display: inline-block;
  height: 100%;
  vertical-align: middle;
  margin-right: -4px;
}

.modal-dialog {
  display: inline-block;
  text-align: left;
  vertical-align: middle;
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script>
$(document).ready(function(){
 $('#multiselect').multiselect({
  nonSelectedText: 'Select Coin',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'100%'
 });
 $('#multiselect2').multiselect({
  nonSelectedText: 'Select Coin',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'100%'
 });
 $('#multiselect3').multiselect({
  nonSelectedText: 'Select Coin',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'100%'
 });
 	$("body").on("click",".close-profit-trad",function(){
	 	if($(this).val() == 'specific'){
			 $(".close-profit-trad-specific-coin").show();
		}else{
			$(".close-profit-trad-specific-coin").hide();
		}
	});
	$("body").on("click",".close-loss-trad",function(){
	 	if($(this).val() == 'specific'){
			 $(".close-loss-trad-specific-coin").show();
		}else{
			$(".close-loss-trad-specific-coin").hide();
		}
	});
});
</script>
<style>
  .notification_popup {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.7);
    z-index: 99999;
    display:none;
}

.notification_popup_iner {
    padding: 25px;
    background: #fff;
    margin: auto;
    height: 200px;
    width: 100%;
    max-width: 500px;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    position: absolute;
    box-shadow: 0 0 59px 24px rgba(0,0,43,0.2);
    border-radius: 15px;
}

.conternta-pop h2 {
    font-size: 19px;
    text-align: center;
    font-weight: bold;
    color: #000;
    margin-top: 33px !important;
    float: left;
    width: 100%;
}

.conternta-pop h2 codet {
    color: #c3a221;
}

.npclss {
    position: absolute;
    right: 15px;
    top: 15px;
    height: 30px;
    width: 30px;
    text-align: center;
    border: 1px solid #ccc;
    background: #fff;
    border-radius: 22px;
    padding-top: 5px;
    cursor: pointer;
}    
    </style>
    <div class="notification_popup">
        <div class="notification_popup_iner">
          <div class="npclss">X</div>
            <div class="conternta-pop">
              <h2>
              The Feature is in development Mode, Stay Tuned it will be released Soon
              </h2>
              <!-- <h2>You have insufficent <codet>BNB</codet> blance
              <br>
              Minimum 10 <codet>BNB</codet> Required
            </h2> -->
              
            </div>
        </div>
    </div> 
    <script type="text/javascript">
      $(document).ready(function(e) {
    $("body").on("click",".npclss",function(){
    $(".notification_popup").hide();
  });

  //var is_bnb_balance = "<?php echo $is_bnb_balance; ?>";
  if(true){
    $(".notification_popup").show();
  }

});
  var call_statusinterval;
  var auto_refresh;





  
    </script>





  
<!-- <div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#crdn_1">
          <span class="glyphicon glyphicon-flag text-success"></span>Close All (Profit) Trade
          <span class="glyphicon glyphicon-chevron-up pull-right"></span>
        </a>
      </h4>
    </div>
    <div id="crdn_1" class="panel-collapse collapse in">
      <div class="panel-body">body of tab</div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#crdn_2">
          <span class="glyphicon glyphicon-flag text-success"></span>Close All (Profit) Trade
          <span class="glyphicon glyphicon-chevron-down pull-right"></span>
        </a>
      </h4>
    </div>
    <div id="crdn_2" class="panel-collapse collapse">
      <div class="panel-body">body of tab</div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#crdn_3">
          <span class="glyphicon glyphicon-flag text-success"></span>Close All (Profit) Trade
          <span class="glyphicon glyphicon-chevron-down pull-right"></span>
        </a>
      </h4>
    </div>
    <div id="crdn_3" class="panel-collapse collapse">
      <div class="panel-body">body of tab</div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#crdn_4">
          <span class="glyphicon glyphicon-flag text-success"></span>Close All (Profit) Trade
          <span class="glyphicon glyphicon-chevron-down pull-right"></span>
        </a>
      </h4>
    </div>
    <div id="crdn_4" class="panel-collapse collapse">
      <div class="panel-body">body of tab</div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#crdn_5">
          <span class="glyphicon glyphicon-flag text-success"></span>Close All (Profit) Trade
          <span class="glyphicon glyphicon-chevron-down pull-right"></span>
        </a>
      </h4>
    </div>
    <div id="crdn_5" class="panel-collapse collapse">
      <div class="panel-body">body of tab</div>
    </div>
  </div>
</div> -->







      <div class="clearfix"></div>
      <div class="checktypeTabs">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Close All (Profit) Trade</a></li>
            <li><a data-toggle="tab" href="#menu1">Close All (Loss) Trade</a></li>
            <li><a data-toggle="tab" href="#menu2">Close All (Specific Coin) Trade</a></li>
            <li><a data-toggle="tab" href="#menu3">Close All (Specific Percentage) Trade</a></li>
            <li><a data-toggle="tab" href="#menu4">Close My All Trades</a></li>
            <li><a data-toggle="tab" href="#menu5">Settings</a></li>
        </ul>
        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
            	<div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-4">

                        <div class="form-group">
                            <div class="col-radio">
                                <label>
                                    <input type="radio" name="p" class="close-profit-trad" value="all">
                                    <span>
                                        <i class="fa fa-dot-circle-o"></i>
                                        <i class="fa fa-circle-o"></i>
                                    </span>
                                    Close All Profitable Trades
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                            <div class="col-radio">
                                <label>
                                    <input type="radio" name="p" class="close-profit-trad" value="specific">
                                    <span>
                                        <i class="fa fa-dot-circle-o"></i>
                                        <i class="fa fa-circle-o"></i>
                                    </span>
                                    Close Specific Coin Profitable Trades
                                </label>
                            </div>
                        </div>
                        <div class="form-group close-profit-trad-specific-coin" style="display:none;">
                            <label>Select Coins</label>
                            <select id="multiselect" class="form-control" multiple="multiple">
                               <?php foreach ($coin_arr as $key => $value) {
    echo "<option value='" . $value['symbol'] . "'>" . $value['symbol'] . "</option>";
}?>
                            </select>
                        </div>
                    </div>
                    <div class="tabfot">
                    	<a href="#" class="btn btn-success sbmtBtn" id="winning-trade">Save</a> <a href="#" class="btn btn-danger">Refresh</a>
                    </div>
                </div>

            </div>
            <div id="menu1" class="tab-pane fade">
            	<div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-4">

                        <div class="form-group">
                            <div class="col-radio">
                                <label>
                                    <input type="radio" name="l" class="close-loss-trad" value="all">
                                    <span>
                                        <i class="fa fa-dot-circle-o"></i>
                                        <i class="fa fa-circle-o"></i>
                                    </span>
                                    Close All Loss Trades
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                            <div class="col-radio">
                                <label>
                                    <input type="radio" name="l" class="close-loss-trad" value="specific">
                                    <span>
                                        <i class="fa fa-dot-circle-o"></i>
                                        <i class="fa fa-circle-o"></i>
                                    </span>
                                    Close Specific Coin Loss Trades
                                </label>
                            </div>
                        </div>
                        <div class="form-group close-loss-trad-specific-coin" style="display:none;">
                            <label>Select Coins</label>
                            <select id="multiselect2" class="form-control" multiple="multiple">
                               <?php foreach ($coin_arr as $key => $value) {
    echo "<option value='" . $value['symbol'] . "'>" . $value['symbol'] . "</option>";
}?>
                            </select>
                        </div>
                    </div>
                    <div class="tabfot">
                    	<a href="#" class="btn btn-success sbmtBtn" id="losing-trade">Save</a> <a href="" class="btn btn-danger">Refresh</a>
                    </div>
                </div>
            </div>
            <div id="menu2" class="tab-pane fade">
            	<div class="col-xs-12 col-md-4">
                    <div class="form-group">
                        <label>Select Coins</label>
                        <select id="multiselect3" class="form-control" multiple="multiple">
                            <?php foreach ($coin_arr as $key => $value) {
    echo "<option value='" . $value['symbol'] . "'>" . $value['symbol'] . "</option>";
}?>
                        </select>
                    </div>
                </div>
                 <div class="tabfot">
                    <a href="#" class="btn btn-success" id="coin-trade">Save</a> <a href="" class="btn btn-danger">Refresh</a>
                </div>

            </div>
            <div id="menu3" class="tab-pane fade">
            	<div class="col-xs-12 col-md-4">
                    <div class="form-group">
                        <label>Close All (Specific Percentage) Trade</label>
                        <label>Select Coins</label>
                        <input type="number" id="percentage" class="form-control">
                    </div>
                </div>
                 <div class="tabfot">
                    <a href="#" class="btn btn-success" id="percentage-button">Save</a> <a href="" class="btn btn-danger">Refresh</a>
                </div>

            </div>
            <div id="menu4" class="tab-pane fade">
            	<div class="col-xs-12 col-md-4">
                    <div class="form-group">
                        <p class="btn-switch">
                            <input type="radio" id="yes" name="tradeswitch" class="btn-switch__radio btn-switch__radio_yes" value="yes" />
                            <input type="radio" checked id="no" name="tradeswitch" class="btn-switch__radio btn-switch__radio_no" value="no" />
                            <label for="yes" class="btn-switch__label btn-switch__label_yes"><span class="btn-switch__txt">Yes</span></label>
                            <label for="no" class="btn-switch__label btn-switch__label_no"><span class="btn-switch__txt">No</span></label>
                        </p>
                    </div>
                </div>
                 <div class="tabfot">
                    <a href="#" class="btn btn-success sell_all_trades">Save</a> <a href="" class="btn btn-danger">Refresh</a>
                </div>

            </div>
            <div id="menu5" class="tab-pane fade">
            	<div class="col-xs-12 col-md-12">
                    <div class="form-group">
                       <a href="#" data-toggle="modal" data-target="#myModal" class="btn btn-success">Add Settings</a>

                            
                       <!-- Trigger the modal with a button -->
                      <!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> -->

                      <!-- Modal -->
                      <div id="myModal" class="modal fade" role="dialog">
                      <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Add Settings</h4>
                          </div>
                          <div class="modal-body">
                          <p>
                                 <?php foreach ($coin_arr as $key => $value) {
                                     ?>
                                  <div class="setting">
                                      <div class="col-md-6">
                                     <div class = "form-group">
                                        <label class = "sr-only" for = "name">Coin Symbol</label>
                                        <input type = "text" class = "form-control symbolll" name = "name[]" value="<?php echo $value['symbol']; ?>" placeholder = "Enter Coin Name">
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class = "form-group">
                                      <label class = "sr-only" for = "name">Percentage</label>
                                      <input type = "text" class = "form-control perrr" name = "percentage[]" placeholder = "Enter Profit Percentage">
                                    </div>
                                  </div>
                                  </div>
                                 <?php }?>
                                 <br>
                                 <hr>
                                 <button type = "submit" id="submit-frm" class = "btn btn-success">Submit</button>
                                 <script type="text/javascript">
                                   $("body").on("click","#submit-frm",function(e){
                                      var coin = [];
                                      var per = [];
                                      $('.symbolll').each(function (index, value) {
                                        coin.push($(this).val());
                                      });

                                      $('.perrr').each(function (index, value) {
                                        per.push($(this).val());
                                      });


                                      $.ajax({
                                        url: '<?php echo SURL; ?>admin/buy_orders/save_lth_settings',
                                        data:{coin:coin,per:per},
                                        type: "POST",
                                        success : function(resp){
                                          $.confirm({
                                            title:"Success",
                                            content:resp,
                                          });
                                        }
                                      });
                                   });
                                 </script>
                              </form>
                            </p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                      </div>



                       <a href="#" data-toggle="modal" data-target="#myModal2" class="btn btn-info">View Settings</a>

                       <a href="#" data-toggle="modal" id="lth_balance_stting_modal_popup"  class="btn btn-warning">Lth Balance Setting</a>



                       <a href="#" data-toggle="modal" data-target="#comulative_profit_modal" class="btn btn-success comulative_profit_list_modal_popup">Lth cumulative Profit percentage</a>

                       <!-- Modal -->
                      <div id="myModal2" class="modal fade" role="dialog">
                      <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">View Settings</h4>
                          </div>
                          <div class="modal-body">
                            <div class="table-responsive">
                              <table class="table table-responsive table-stripped">
                                <thead>
                                  <tr>
                                    <th>Coin</th>
                                    <th>Profit (%)</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php foreach ($settings as $key => $value) {
    ?>
                                    <tr>
                                    <td><?php echo $value['coin']; ?></td>
                                    <td><?php echo $value['percentage'] . "%"; ?></td>
                                  </tr>
                                  <?php }?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>

                      </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="clearfix"></div>






      </div>
    </div>
    <!-- // Widget END -->

    <!-- Widget -->
    <div class="widget widget-inverse">

      <div class="widget-body padding-bottom-none">
      	<div class="table-responsive hrf">
        <!-- Table -->
        	<?php echo $response; ?>
		</div>
        <!-- // Table END -->
      </div>
    </div>
    <!-- // Widget END -->

  </div>
</div>

<!-- The modal -->
<div class="modal" id="largeShoes" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
   <div class="modal-dialog modal-sm">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="modalLabelLarge">User Information</h4>
         </div>
         <div class="modal-body" id="mymodalresp">
            Modal content...
         </div>
      </div>
   </div>
</div>
<!-- Start Model -->
<div class="modal fade in" id="modal-order_details" aria-hidden="false">

    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal heading -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Order Details</h3>
            </div>
            <!-- // Modal heading END -->

            <!-- Modal body -->
            <div class="modal-body">
                <div class="innerAll">
                    <div class="innerLR" id="response_order_details">
                    </div>
                </div>
            </div>
            <!-- // Modal body END -->

        </div>
    </div>

</div>
<!-- End Model -->

<!-- Start Model -->
<div class="modal fade in" id="modal-order_update" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal heading -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="modal-title">Order Update</h3>
      </div>
      <!-- // Modal heading END -->

      <!-- Modal body -->
      <div class="modal-body">
        <div class="innerAll">
          <div class="innerLR" id="response_order_update"> </div>
        </div>
      </div>
      <!-- // Modal body END -->

    </div>
  </div>
</div>


<div id="let_balance_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Lth Balance Settings</h4>
            </div>
            <div class="modal-body">
            <p>
                   
                        <div class="row">
                            <div class="col-md-12">
                                <div class = "form-group">
                                    <label>Enter the percentage you want LTH to stop taking new LTH trades Depending on Open Trades this Will not be exact percentage</label>
                                    
                                    <input type = "number" id="lth_balance_percentage" class="form-control " name="" value="" placeholder = "" min="1" max="100">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <!-- -->
                                <div class="form-group">
                                    <div class="col-radio">
                                        <label>
                                        <input type="radio" name="lth_balance_check" id="sell_lth_when_qty_excedded" value="yes">
                                        <span>
                                        <i class="fa fa-dot-circle-o"></i>
                                        <i class="fa fa-circle-o"></i>
                                        </span>
                                        Start Using Stoploss on New Trades, When LTH Hits Your Set Limit
                                        
                                        </label>
                                    </div>
                                </div>
                                <!-- -->
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                
                                <!-- -->
                                <div class="form-group">
                                    <div class="col-radio">
                                        <label>
                                        <input type="radio" name="lth_balance_check" class="" id="take_no_trade_when_qty_excedded"  value="yes">
                                        <span>
                                        <i class="fa fa-dot-circle-o"></i>
                                        <i class="fa fa-circle-o"></i>
                                        </span>
                                        Stop Taking New Trades, When LTH Hits Your Set Limit
                                        </label>
                                    </div>
                                </div>
                                <!-- -->
                            </div>
                        </div>
                    
                    <br>
                    <hr>
                    <button type = "button" id="save_lth_balance_percentage" class = "btn btn-success">Submit</button>
                </form>
            </p>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- End Model -->




  <!-- Modal -->
    <div id="comulative_profit_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add comulative percentage setting</h4>
            </div>
            <div class="modal-body">
            <p>
                    <?php foreach ($coin_arr as $key => $value) {
                        ?>
                    <div class="setting">
                        <div class="col-md-6">
                        <div class = "form-group">
                        <label class = "sr-only" for = "name">Coin Symbol</label>
                        <input type = "text" class = "form-control symbol_comulative" name = "name[]" value="<?php echo $value['symbol']; ?>" placeholder = "Enter Coin Name">
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class = "form-group">
                        <label class = "sr-only" for = "name">Percentage</label>
                        <input type = "text" class = "form-control per_comulative" name = "percentage[]" placeholder = "Enter Profit Percentage" id="<?php echo $value['symbol']; ?>_comulative">
                    </div>
                    </div>
                    </div>
                    <?php }?>
                    <br>
                    <hr>
                    <button type = "submit" id="submit-frm-comulative" class = "btn btn-success">Submit</button>
                    <script type="text/javascript">
                    $("body").on("click","#submit-frm-comulative",function(e){
                        var coin = [];
                        var per = [];
                        $('.symbol_comulative').each(function (index, value) {
                        coin.push($(this).val());
                        });

                        $('.per_comulative').each(function (index, value) {
                        per.push($(this).val());
                        });


                        $.ajax({
                        url: '<?php echo SURL; ?>admin/buy_orders/save_comulative_settings',
                        data:{coin:coin,per:per},
                        type: "POST",
                        success : function(resp){
                            $.confirm({
                            title:"Success",
                            content:resp,
                            });
                        }
                        });
                    });
                    </script>
                </form>
            </p>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>

    <!-- End of Modal Commulative Profit percentate -->



<script>

$(document).ready(function(){
    $(document).on('click','#lth_balance_stting_modal_popup',()=>{
        $("#let_balance_modal").modal("toggle");
        $.ajax({
            url: '<?php echo SURL; ?>admin/buy_orders/get_lth_balance_percentage',
            data:{'send':''},
            type: "POST",
            success : function(resp){
                var resp = JSON.parse(resp)
                $('#lth_balance_percentage').val(resp.lth_percentage);
                
                if(resp.sell_lth_when_qty_excedded == 'yes'){
                    $( "#sell_lth_when_qty_excedded" ).prop( "checked", true );
                }else{
                    $( "#sell_lth_when_qty_excedded" ).prop( "checked", false );
                }


                if(resp.take_no_trade_when_qty_excedded == 'yes'){
                    $( "#take_no_trade_when_qty_excedded" ).prop( "checked", true );
                }else{
                    $( "#take_no_trade_when_qty_excedded" ).prop( "checked", false );
                }

               
            }
        });  
    })    

    $(document).on('click','#save_lth_balance_percentage',()=>{

        var lth_balance_percentage = $('#lth_balance_percentage').val();
        var sell_lth_when_qty_excedded = ($('#sell_lth_when_qty_excedded').prop('checked'))?'yes':'no';
        var take_no_trade_when_qty_excedded = ($('#take_no_trade_when_qty_excedded').prop('checked'))?'yes':'no';
        $.ajax({
            url: '<?php echo SURL; ?>admin/buy_orders/save_lth_balance_percentage',
            data:{'lth_balance_percentage':lth_balance_percentage,'sell_lth_when_qty_excedded':sell_lth_when_qty_excedded,'take_no_trade_when_qty_excedded':take_no_trade_when_qty_excedded},
            type: "POST",
            success : function(resp){
                alert('setting added successfully');
            }
        });
    })
    

});



autoload_market_buy_data();
function autoload_market_buy_data(){

      var page = $('.hrf').find('ul.pagination').find('li.active').find('a').find('b').html();

      if (page == null) { page = 1; }
        $.ajax({
        type: "GET",
        data: '',
        url:'<?php echo SURL ?>admin/buy_orders/autoload_lth_data/'+page,
        success:function(response){
            $('.hrf').html(response);
            setTimeout(function() {
                        autoload_market_buy_data();
                  }, 20000);
        },
        error: function(errorThrown) {
            console.log("Error: " + errorThrown);
            setTimeout(function() {
                autoload_market_buy_data();
          }, 20000);
        }
      });

  }//end autoload_market_buy_data()

$("body").on("click","#winning-trade",function(e){
  var option = "";
  var coin = "";
  var action = "winning";
  if($(".close-profit-trad:checked").val() == 'all'){
       option = 'all_profit';
    }else{
      option = 'specific-coin';
      coin = $("#multiselect").val();
    }

    $.ajax({
      url: "<?php echo SURL; ?>admin/long_term_hold/do_ajax_action",
      data: {action:action,option:option,coin:coin},
      type: "POST",
      success: function(response){
          $.alert({
            title: "Success!",
            content: "your action has been recorded successfully"
          })
      },
      error:function(err){
        $.alert({
            title: "Error!",
            content: "There is some issue, Please try again"
          });
      }
    });

});





//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
$("body").on("click",".sell_all_trades",function(e){
  var option = "";
  var coin = "";
  var action = "sell_all_trades";
  


    $.ajax({
      url: "<?php echo SURL; ?>admin/long_term_hold/do_ajax_action",
      data: {action:action,option:option,coin:coin},
      type: "POST",
      success: function(response){
          $.alert({
            title: "Success!",
            content: "your action has been recorded successfully"
          })
      },
      error:function(err){
        $.alert({
            title: "Error!",
            content: "There is some issue, Please try again"
          });
      }
    });
});
///%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
$("body").on("click","#percentage-button",function(e){
  var option = "";
  var coin = "";
  var action = "percentage";
  
  var perc = $('#percentage').val();
    if(perc == ''){
        return false;
    }

    $.ajax({
      url: "<?php echo SURL; ?>admin/long_term_hold/do_ajax_action",
      data: {action:action,option:option,coin:coin,perc:perc},
      type: "POST",
      success: function(response){
          $.alert({
            title: "Success!",
            content: "your action has been recorded successfully"
          })
      },
      error:function(err){
        $.alert({
            title: "Error!",
            content: "There is some issue, Please try again"
          });
      }
    });
});
///%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

$("body").on("click","#losing-trade",function(e){
  var option = "";
  var coin = "";
  var action = "losing";

  var checked_value = $('.close-loss-trad:checked').val();
  
  if(checked_value == 'all'){
       option = 'all_lose';
    }else{
      option = 'specific-coin';
      coin = $("#multiselect2").val();
    }

    $.ajax({
      url: "<?php echo SURL; ?>admin/long_term_hold/do_ajax_action",
      data: {action:action,option:option,coin:coin},
      type: "POST",
      success: function(response){
          $.alert({
            title: "Success!",
            content: "your action has been recorded successfully"
          })
      },
      error:function(err){
        $.alert({
            title: "Error!",
            content: "There is some issue, Please try again"
          });
      }
    });
});

$("body").on("click","#coin-trade",function(e){
  var action = "all_coin";
  var option = "";
  coin = $("#multiselect3").val();
  $.ajax({
      url: "<?php echo SURL; ?>admin/long_term_hold/do_ajax_action",
      data: {action:action,option:option,coin:coin},
      type: "POST",
      success: function(response){
          $.alert({
            title: "Success!",
            content: "your action has been recorded successfully"
          })
      },
      error:function(err){
        $.alert({
            title: "Error!",
            content: "There is some issue, Please try again"
          });
      }
    });
});

$("body").on("click","#percentage-trade",function(e){
  var action = "percentage";
  var option = ""
  var perc = $("#percentage").val();
  $.ajax({
      url: "<?php echo SURL; ?>admin/long_term_hold/do_ajax_action",
      data: {action:action,option:option,perc:perc},
      type: "POST",
      success: function(response){
          $.alert({
            title: "Success!",
            content: "your action has been recorded successfully"
          })
      },
      error:function(err){
        $.alert({
            title: "Error!",
            content: "There is some issue, Please try again"
          });
      }
    });
});

$("body").on("change","input[name=tradeswitch]",function(e){
  alert($(this).val())

  var action = "close_All";
  var option = ""
  var value = $(this).val();
  if (value == 'yes') {
    $.ajax({
      url: "<?php echo SURL; ?>admin/long_term_hold/do_ajax_action",
      data: {action:action,option:option,perc:perc},
      type: "POST",
      success: function(response){
          $.alert({
            title: "Success!",
            content: "your action has been recorded successfully"
          })
      },
      error:function(err){
        $.alert({
            title: "Error!",
            content: "There is some issue, Please try again"
          });
      }
    });
  }
});
$(document).ready(function(e){
   var query = $("#filter_by_trigger").val();
    if (query == 'barrier_trigger') {
        $("#barrier_t").show();
        $("#barrier_p_t").hide();
    }else if(query == 'barrier_percentile_trigger'){
        $("#barrier_t").hide();
        $("#barrier_p_t").show();
    }else{
      $("#barrier_t").hide();
        $("#barrier_p_t").hide();
    }
});
$("body").on("click",".glassflter",function(e){
    var query = $("#filter_by_name").val();
    window.location.href = "<?php echo SURL; ?>/admin/users/?query="+query;
});


$("body").on("click","#submit-frm",function(e){
  var symbol = array
  console.log(formData);
  // $.ajax('/endpoint.php', {
  //     processData: false,
  //     contentType: false,
  //     data: formData
  // });
});

$("body").on("change","#filter_by_trigger",function(e){
    var query = $("#filter_by_trigger").val();
    if (query == 'barrier_trigger') {
        $("#barrier_t").show();
        $("#barrier_p_t").hide();
    }else if(query == 'barrier_percentile_trigger'){
        $("#barrier_t").hide();
        $("#barrier_p_t").show();
    }else{
      $("#barrier_t").hide();
      $("#barrier_p_t").hide();
    }
});

$("body").on("click",".viewadmininfo",function(e){
    var user_id = $(this).attr('id');
    $.ajax({
      url: "<?php echo SURL; ?>admin/reports/get_user_info",
      data: {user_id:user_id},
      type: "POST",
      success: function(response){
          $("#mymodalresp").html(response);
      }
    });
});

$("body").on("click",".view_order_details",function(e){

      var order_id = $(this).attr("data-id");

       $.ajax({
          'url': '<?php echo SURL ?>admin/dashboard/get_buy_order_details_ajax',
          'type': 'POST',
          'data': {order_id:order_id},
          'success': function (response) {

              $('#response_order_details').html(response);
              $("#modal-order_details").modal('show');
          }
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


  //Custom switcher by Afzal
  jQuery("body").on("change","#af-swith-asc",function(){
	if(jQuery(".af-switcher-default").hasClass("active")){
		jQuery(".af-switcher-default").removeClass("active");
	}
	else{
		jQuery(".af-switcher-default").addClass("active");
	}
	});
	jQuery("body").on("click",".af-switcher-default",function(){
		if(jQuery(".af-switcher-default").hasClass("active")){
			jQuery(".af-switcher-default").removeClass("active");
		}
		else{
			jQuery(".af-switcher-default").addClass("active");
		}
	});
  	jQuery("body").on("change",".af-cust-radio",function(){
		jQuery(".af-form-group-created").addClass("active");
	});
  //----End--------
  </script>
<script>
$("body").on("click",".sell_now_btn",function(e){

    var id = $(this).attr('data-id');
    var market_value = $(this).attr('market_value');
    var quantity = $(this).attr('quantity');
    var symbol = $(this).attr('symbol');
    var order_type = $(this).attr('order_type');
    var buy_order_id = $(this).attr('buy_order_id');

    $("#"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

        if(order_type !='limit_order'){
        // sell_order(id,market_value,quantity,symbol);

          sell_market_order(id,market_value,quantity,symbol,buy_order_id);

        }else{
         limit_order_cancel(id,market_value,quantity,symbol,buy_order_id);
        }

});


//%%%%%%%%%%%%%%%%%%%%%%%5 Sell limit order %%%%%%%%%%%%%%%%%%%%%%%%%%
function sell_market_order(sell_id,market_value,quantity,symbol,buy_order_id){

     $.ajax({
        'url':'<?php echo SURL ?>admin/dashboard/check_status_of_limit_order',
        'type':'POST',
        'data':{sell_id:sell_id,symbol:symbol},
        'success':function(response){
            var rp = JSON.parse(response);
            var resp =rp['status'];
            var current_market_price =rp['market_price'];


            $("#"+sell_id).html('Sell Now');

                if(resp == 'error'){
                    //%%%%%%%%%%%%%%% if order is an error status%%%%%%%%%%%
                        $.confirm({
                            title: 'Attention!',
                            content: 'The order is an error status. Please Remove the error to sell this order',
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: 'Ok',
                                    btnClass: 'btn-red',
                                    action: function(){
                                    }
                                },
                                    close: function () {
                                }
                            }
                        });
                   //%%%%%%%%%%%%%%%End of  error status%%%%%%%%%%%
                }else if (resp == 'submitted'){
                    //%%%%%%%%%%%%%%%%%%% submitted status %%%%%%%%%%%%%%%%%%%%%

                     $.confirm({
                        title: 'Order Status',
                        content: 'Order Already Send for sell',
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'Ok',
                                btnClass: 'btn-red',
                                action: function(){

                                }
                            },
                                close: function () {
                            }
                        }
                    });
                     //%%%%%%%%%%%%%%%%%%% End of submitted status %%%%%%%%%%%%%%%%%%%%%
                }else if (resp == 'new'){

                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%5  New Status%%%%%%%%%%%%%%%%%%%%%%%%%
                    var content_html ='Select from below to sell this order\
                        <div class="">\
                        <hr>\
                        <form>\
                        <div class="form-group">\
                        <div class="row"><div class="col-xs-6">\
                        <label>Current Market Price</label>\
                        <input class="form-control" step="any" type="number" name="cu_m_price_'+sell_id+'" value="'+current_market_price+'" disabled>\
                        </div><div class="col-xs-6">\
                        <label>Sell Price</label>\
                        <input class="form-control" step=".00000001" type="number" name="sell_price_'+sell_id+'" value="'+current_market_price+'" id="sell_price_'+sell_id+'">\
                        </div>\
                        </div>'

                        // <div class="radio">\
                        // <label><input type="radio" name="typ_new_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\
                        // </div>\
                        // <div class="radio">\
                        // <label><input type="radio" name="typ_new_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\
                        // </div>

                        content_html +='<div class="radio ">\
                        <label><input type="radio" name="typ_new_'+sell_id+'" value="m_current">Fire market order on current market price</label>\
                        </div>\
                        </form>\
                        </div>';

                     $.confirm({
                        title: 'Sell order conformation',
                        content: content_html,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'Ok',
                                btnClass: 'btn-red',
                                action: function(){

                                    var order_type = $("input[name='typ_new_"+sell_id+"']:checked").val();
                                    ;
                                    var sell_price = $('#sell_price_'+sell_id).val();
                                   sell_market_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price);
                                }
                            },
                                close: function () {
                            }
                        }
                    });
                    //%%%%%%%%%%%%%%%%%%%%%%%End of new status %%%%%%%%%%%%%%%%%%%%%%%%
                }


        }
    })

}//End of sell_market_order


function sell_market_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price){

    //%%%%%%%%%%%%%%%%%%%%%%%%%%
    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/sell_market_order_by_user',
        'type': 'POST', //the way you want to send data to your URL
        'data': {sell_id: sell_id,market_value:market_value,quantity:quantity,symbol:symbol,buy_order_id:buy_order_id,order_type,sell_price:sell_price},
        'success': function (response) {
            $("#"+sell_id).html('Sell Now');
            if(response ==''){
                $.alert({
                  title: 'Success!',
                  content: "Order Has been submitted to sell Successfully",
                });
            }else{
                $.confirm({
                    title: 'Encountered an error!',
                    content: response,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Ok',
                            btnClass: 'btn-red',
                            action: function(){
                            }
                        },
                        close: function () {
                        }
                    }
                });
            }

        }
    });
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%
}//End of sell_market_order_by_user


function  limit_order_cancel(sell_id,market_value,quantity,symbol,buy_order_id){

     $.ajax({
        'url':'<?php echo SURL ?>admin/dashboard/check_status_of_limit_order',
        'type':'POST',
        'data':{sell_id:sell_id,symbol:symbol},
        'success':function(response){

            var rp = JSON.parse(response);
            var resp =rp['status'];
            var current_market_price =rp['market_price'];

            $("#"+sell_id).html('Sell Now');

                if(resp == 'error'){
                    //%%%%%%%%%%%%%%% if order is an error status%%%%%%%%%%%
                        $.confirm({
                            title: 'Attention!',
                            content: 'The order is an error status. Please Remove the error to sell this order',
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: 'Ok',
                                    btnClass: 'btn-red',
                                    action: function(){
                                    }
                                },
                                    close: function () {
                                }
                            }
                        });
                   //%%%%%%%%%%%%%%%End of  error status%%%%%%%%%%%
                }else if (resp == 'submitted'){
                    //%%%%%%%%%%%%%%%%%%% submitted status %%%%%%%%%%%%%%%%%%%%%

                        var content_html =' Order is already in <span style="color:orange;    font-size: 14px;"><b>SUBMIT</b></span> status for sell as limit order.'+
                        ' Are you want to  <span style="color:red;    font-size: 14px;"><b>Cancel</b></span>  it ?  And submit to sell agin!\
                        <div class="">\
                        <hr>\
                        <form>\
                        <div class="form-group">\
                        <div class="row"><div class="col-xs-6">\
                        <label>Current Market Price</label>\
                        <input class="form-control" step="any" type="number" name="cu_m_price_'+sell_id+'" value="'+current_market_price+'" disabled>\
                        </div><div class="col-xs-6">\
                        <label>Sell Price</label>\
                        <input class="form-control" step=".00000001" type="number" name="sell_price_'+sell_id+'" value="'+current_market_price+'" id="sell_price_'+sell_id+'">\
                        </div>\
                        </div>'

                        // <div class="radio">\
                        // <label><input type="radio" name="typ_submit_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\
                        // </div>\
                        // <div class="radio">\
                        // <label><input type="radio" name="typ_submit_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\
                        // </div>\


                       content_html +=' <div class="radio ">\
                        <label><input type="radio" name="typ_submit_'+sell_id+'" value="m_current">Fire market order on current market price</label>\
                        </div>\
                        </form>\
                        </div>';

                     $.confirm({
                        title: 'Limit order Cancel  and resend order conformation',
                        content: content_html,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'Ok',
                                btnClass: 'btn-red',
                                action: function(){

                                    var order_type = $("input[name='typ_submit_"+sell_id+"']:checked").val();
                                    var sell_price = $('#sell_price_'+sell_id).val();

                                   cancel_and_place_new_limit_order_for_sell(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price);
                                }
                            },
                                close: function () {
                            }
                        }
                    });
                     //%%%%%%%%%%%%%%%%%%% End of submitted status %%%%%%%%%%%%%%%%%%%%%
                }else if (resp == 'new'){

                    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%5  New Status%%%%%%%%%%%%%%%%%%%%%%%%%
                    var content_html ='Select from below to sell this order\
                        <div class="">\
                        <hr>\
                        <form>\
                        <div class="form-group">\
                        <div class="row"><div class="col-xs-6">\
                        <label>Current Market Price</label>\
                        <input class="form-control" step="any" type="number" name="cu_m_price_'+sell_id+'" value="'+current_market_price+'" disabled>\
                        </div><div class="col-xs-6">\
                        <label>Sell Price</label>\
                        <input class="form-control" step=".00000001" type="number" name="sell_price_'+sell_id+'" value="'+current_market_price+'" id="sell_price_'+sell_id+'">\
                        </div>\
                        </div>'

                        // <div class="radio">\
                        // <label><input type="radio" name="typ_new_'+sell_id+'" checked value="l_current">Fire limit order at above price</label>\
                        // </div>\
                        // <div class="radio">\
                        // <label><input type="radio" name="typ_new_'+sell_id+'" value="l_below" >Fire limit order one tick below above price</label>\
                        // </div>\


                       content_html +=' <div class="radio ">\
                        <label><input type="radio" name="typ_new_'+sell_id+'" value="m_current">Fire market order on current market price</label>\
                        </div>\
                        </form>\
                        </div>';

                     $.confirm({
                        title: 'Sell order conformation',
                        content: content_html,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'Ok',
                                btnClass: 'btn-red',
                                action: function(){

                                    var order_type = $("input[name='typ_new_"+sell_id+"']:checked").val();
                                    var sell_price = $('#sell_price_'+sell_id).val();

                                   sell_lmit_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price);
                                }
                            },
                                close: function () {
                            }
                        }
                    });
                    //%%%%%%%%%%%%%%%%%%%%%%%End of new status %%%%%%%%%%%%%%%%%%%%%%%%
                }


        }
    })

}//End of limit_order_cancel





function sell_lmit_order_by_user(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price){
    //%%%%%%%%%%%%%%%%%%%%%%%%%%
    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/sell_lmit_order_by_user',
        'type': 'POST', //the way you want to send data to your URL
        'data': {sell_id: sell_id,market_value:market_value,quantity:quantity,symbol:symbol,buy_order_id:buy_order_id,order_type,sell_price:sell_price},
        'success': function (response) {
            $("#"+sell_id).html('Sell Now');
            if(response ==''){

            }else{
                $.confirm({
                    title: 'Encountered an error!',
                    content: response,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Ok',
                            btnClass: 'btn-red',
                            action: function(){
                            }
                        },
                        close: function () {
                        }
                    }
                });
            }

        }
    });
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%
}//End of sell_lmit_order_by_user

function cancel_and_place_new_limit_order_for_sell(sell_id,market_value,quantity,symbol,buy_order_id,order_type,sell_price){
    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/cancel_and_place_new_limit_order_for_sell',
        'type': 'POST', //the way you want to send data to your URL
        'data': {sell_id: sell_id,market_value:market_value,quantity:quantity,symbol:symbol,buy_order_id:buy_order_id,order_type,sell_price:sell_price},
        'success': function (response) {
            $("#"+sell_id).html('Sell Now');
            if(response ==''){

            }else{
                $.confirm({
                    title: 'Encountered an error!',
                    content: response,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Ok',
                            btnClass: 'btn-red',
                            action: function(){
                            }
                        },
                        close: function () {
                        }
                    }
                });
            }

        }
    });
}//End of cancel_and_place_new_limit_order_for_sell

function sell_order(id,market_value,quantity,symbol){
    $("#"+id).html('<img src="<?php echo IMG ?>loader.gif"  width="20" height="20" style="margin-top: -2px;"/>');

    $.ajax({
        'url': '<?php echo SURL ?>admin/dashboard/sell_order',
        'type': 'POST', //the way you want to send data to your URL
        'data': {id: id,market_value:market_value,quantity:quantity,symbol:symbol},
        'success': function (response) {

            if(response ==1){
                $("#"+id).html('Sell Now');
            }else{
                $.confirm({
                    title: 'Encountered an error!',
                    content: response,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Ok',
                            btnClass: 'btn-red',
                            action: function(){
                            }
                        },
                        close: function () {
                        }
                    }
                });
            }

        }
    });

}//End of sell_order

$(document).on('click','.comulative_profit_list_modal_popup',()=>{
    $.ajax({
          'url': '<?php echo SURL ?>admin/buy_orders/list_user_comulative_lth_profit_percentage_setting',
          'type': 'POST',
          'data': {'post':''},
          'success': function (response) {
              var arr = JSON.parse(response);
              for(let index in arr){
                  let coinSymbol = arr[index]['coin'];
                  let comulative_percentage = arr[index]['comulative_percentage'];
                  $('#'+coinSymbol+'_comulative').val(comulative_percentage);   
              }
          }
      });

})

$("body").on("click",".change_error_status",function(e){

      var order_id = $(this).attr("data-id");



       $.ajax({
          'url': '<?php echo SURL ?>admin/reports/get_buy_order_error_ajax',
          'type': 'POST',
          'data': {order_id:order_id},
          'success': function (response) {

              $('#response_order_update').html(response);
              $("#modal-order_update").modal('show');
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