<link href="<?php echo ASSETS;?>cdn_links/bootstrap-datetimepicker-master/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="<?php echo ASSETS;?>cdn_links/moment-with-locales.js"></script>
<script src="<?php echo ASSETS;?>cdn_links/bootstrap-datetimepicker-master/build/js/bootstrap-datetimepicker.min.js"></script>
<style>
.checkbox-animated {
  position: relative;
  margin-top: 10px;
  margin-bottom: 10px;
}
.checkbox-animated input[type="checkbox"] {
  display: none;
}
.checkbox-animated input[type="checkbox"]:disabled ~ label .box {
  border-color: #777;
  background-color: #e6e6e6;
}
.checkbox-animated input[type="checkbox"]:disabled ~ label .check {
  border-color: #777;
}
.checkbox-animated input[type="checkbox"]:checked ~ label .box {
  opacity: 0;
  -webkit-transform: scale(0) rotate(-180deg);
  -moz-transform: scale(0) rotate(-180deg);
  transform: scale(0) rotate(-180deg);
}
.checkbox-animated input[type="checkbox"]:checked ~ label .check {
  opacity: 1;
  -webkit-transform: scale(1) rotate(45deg);
  -moz-transform: scale(1) rotate(45deg);
  transform: scale(1) rotate(45deg);
}
.checkbox-animated label {
  cursor: pointer;
  padding-left: 28px;
  font-weight: normal;
  margin-bottom: 0;
}
.checkbox-animated label span {
  display: block;
  position: absolute;
  left: 0;
  -webkit-transition-duration: 0.3s;
  -moz-transition-duration: 0.3s;
  transition-duration: 0.3s;
}
.checkbox-animated label .box {
  border: 2px solid #000;
  height: 20px;
  width: 20px;
  z-index: 888;
  -webkit-transition-delay: 0.2s;
  -moz-transition-delay: 0.2s;
  transition-delay: 0.2s;
}
.checkbox-animated label .check {
  top: -7px;
  left: 6px;
  width: 12px;
  height: 24px;
  border: 2px solid #bada55;
  border-top: none;
  border-left: none;
  opacity: 0;
  z-index: 888;
  -webkit-transform: rotate(180deg);
  -moz-transform: rotate(180deg);
  transform: rotate(180deg);
  -webkit-transition-delay: 0.3s;
  -moz-transition-delay: 0.3s;
  transition-delay: 0.3s;
}
.checkbox-animated-inline {
  position: relative;
  margin-top: 10px;
  margin-bottom: 10px;
}
.checkbox-animated-inline input[type="checkbox"] {
  display: none;
}
.checkbox-animated-inline input[type="checkbox"]:disabled ~ label .box {
  border-color: #777;
  background-color: #e6e6e6;
}
.checkbox-animated-inline input[type="checkbox"]:disabled ~ label .check {
  border-color: #777;
}
.checkbox-animated-inline input[type="checkbox"]:checked ~ label .box {
  opacity: 0;
  -webkit-transform: scale(0) rotate(-180deg);
  -moz-transform: scale(0) rotate(-180deg);
  transform: scale(0) rotate(-180deg);
}
.checkbox-animated-inline input[type="checkbox"]:checked ~ label .check {
  opacity: 1;
  -webkit-transform: scale(1) rotate(45deg);
  -moz-transform: scale(1) rotate(45deg);
  transform: scale(1) rotate(45deg);
}
.checkbox-animated-inline label {
  cursor: pointer;
  padding-left: 28px;
  font-weight: normal;
  margin-bottom: 0;
}
.checkbox-animated-inline label span {
  display: block;
  position: absolute;
  left: 0;
  -webkit-transition-duration: 0.3s;
  -moz-transition-duration: 0.3s;
  transition-duration: 0.3s;
}
.checkbox-animated-inline label .box {
  border: 2px solid #000;
  height: 20px;
  width: 20px;
  z-index: 888;
  -webkit-transition-delay: 0.2s;
  -moz-transition-delay: 0.2s;
  transition-delay: 0.2s;
}
.checkbox-animated-inline label .check {
  top: -7px;
  left: 6px;
  width: 12px;
  height: 24px;
  border: 2px solid #bada55;
  border-top: none;
  border-left: none;
  opacity: 0;
  z-index: 888;
  -webkit-transform: rotate(180deg);
  -moz-transform: rotate(180deg);
  transform: rotate(180deg);
  -webkit-transition-delay: 0.3s;
  -moz-transition-delay: 0.3s;
  transition-delay: 0.3s;
}
.checkbox-animated-inline.checkbox-animated-inline {
  display: inline-block;
}
.checkbox-animated-inline.checkbox-animated-inline + .checkbox-animated-inline {
  margin-left: 10px;
}
</style>
<style>
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

th.span1 input {
    margin: 0 10px 0 0;
}

form#registration_form {
    width: 100%;
    float: left;
}

.form-group {
    padding: 25px 25px;
    border: 1px solid #ccc;
    margin: 0 25% !important;
}
.btn span.glyphicon {
	opacity: 0;
}
.btn.active span.glyphicon {
	opacity: 1;
}
</style>
<div id="content" class="get-relative">
    <div class="optimized-loader" style="display:none;">
				<img src="http://app.digiebot.com/assets/images/load_cube.gif">
    </div>
  <h1 class="content-heading bg-white border-bottom">Merge Orders</h1>
  <div class="bg-white innerAll border-bottom">

  </div>
  <div class="innerAll spacing-x2">


      <!-- Widget -->
    <div class="widget widget-inverse">
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

         <!-- Form -->
         <div class="widget-body">
            <h4>Merge Orders</h4>
        </div>
        <form method="POST" action="<?=SURL;?>admin/merge_trades/sell_merged_order">
        <div class="widget-body">

          <!-- Row -->
          <div class="row">
              <div class = 'col-md-6 col-md-offset-3'>
                <table class="table table-condensed table-hover">
                  <thead>
                    <tr>
                      <th class="span1">
                        <div class="checkbox-animated">
                            <input id="checkbox_animated_4" name="sell_one_tip_below" type="checkbox">
                            <label for="checkbox_animated_4">
                                <span class="check"></span>
                                <span class="box"></span>
                            </label>
                        </div>
                      </th>
                      <th class="span2">Symbol</th>
                      <th class="span2">Quantity</th>
                      <th class="span2">Purchased Price</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                      if (count($order_arr) > 0) {
                        foreach ($order_arr as $key => $value) {
                          ?>
                          <tr>
                            <td><div class="checkbox-animated">
                                <input class="myClass" id="<?=$value['_id']?>" name="myID[]" value="<?=$value['_id']?>" quantity="<?=floatval($value['quantity']);?>" type="checkbox">
                                <label for="<?=$value['_id']?>">
                                    <span class="check"></span>
                                    <span class="box"></span>
                                </label>
                            </div></td>
                            <td><strong><?=$value['symbol']?></strong></td>
                            <td><span><?=floatval($value['quantity']);?></span></td>
                            <td><strong><?=$value['market_value']?></strong></td>
                          </tr>
                        <?php }
                      }
                  ?>
                  </tbody>
                </table>
              </div>
          </div>
          <div class="row">
            <form name="registration_form" id="registration_form" class="form-horizontal">
               <div class="form-group">
                     <div class="col-sm-4">
                       <label for="symbol" class="sr-only">Symbol</label>
                       <input id="symbol" class="form-control input-group-lg reg_name" type="text" name="symbol" title="Enter Symbol" value="<?=$symbol?>">
                     </div>
                     <div class="col-sm-4">
                       <label for="quantity" class="sr-only">Quantity</label>
                       <input id="quantity" class="form-control input-group-lg reg_name" type="text" name="quantity" title="Enter last name" placeholder="Quantity">
                     </div>
                     <div class="col-sm-4">
                        <input type="hidden" value="<?=$admin_id?>"/>
                       <input id="submit" class="btn btn-success btn-sm" type="submit" name="submit">
                     </div>
              </div><!--/form-group-->
            </form>
          </div>
        </div>
        </form>
        <!-- // Widget END -->
  </div>
</div>
<!-- Button trigger modal -->

<script>
function updateTextArea() {
   var sThisVal = 0;
   $('.myClass').each(function () {
      if (this.checked) {
        sThisVal += parseFloat($(this).attr('quantity'))
      };
      $('#quantity').val(sThisVal)
    });
}
$(function() {
 $('.myClass').click(updateTextArea);
 updateTextArea();
});

$("#checkbox_animated_4").click(function(){
    $('.myClass').not(this).prop('checked', this.checked);
    updateTextArea();
});

</script>
