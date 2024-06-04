<style type="text/css">
section {
  display: flex;
  flex-flow: row wrap;
}
section > div {
  flex: 1;
  padding: 1px;
}
input[type="radio"] {
  display: none;
  &:not(:disabled) ~ label {
    cursor: pointer;
  }
  &:disabled ~ label {
    color: hsla(150, 5%, 75%, 1);
    border-color: hsla(150, 5%, 75%, 1);
    box-shadow: none;
    cursor: not-allowed;
  }
}
label.label2 {
  height: 100%;
  display: block;
  background: white;
  border: 2px solid hsl(208, 23%, 54%);
  border-radius: 30px;
  padding: 1px;
  /*margin-bottom: 1rem;*/
  margin: 1px;
  text-align: center;
  box-shadow: 0px 3px 10px -2px hsla(150, 5%, 65%, 0.5);
  position: relative;
}
input[type="radio"]:checked + label {
  background: hsl(208, 23%, 54%);
  color: hsla(215, 0%, 100%, 1);
 /* box-shadow: 0px 0px 20px hsla(150, 100%, 50%, 0.75);*/
  &::after {
    color: hsla(215, 5%, 25%, 1);
    font-family: FontAwesome;
    border: 2px solid hsla(150, 75%, 45%, 1);
    content: "\f00c";
    font-size: 24px;
    position: absolute;
    top: -25px;
    left: 50%;
    transform: translateX(-50%);
    height: 50px;
    width: 50px;
    line-height: 50px;
    text-align: center;
    border-radius: 50%;
    background: white;
    box-shadow: 0px 2px 5px -2px hsla(0, 0%, 0%, 0.25);
  }
}
/*input[type="radio"]#control_05:checked + label {
  background: red;
  border-color: red;
}*/
p {
  font-weight: 900;
}


@media only screen and (max-width: 700px) {
  section {
    flex-direction: column;
  }
}
</style>

<div id="content">
  <h1 class="content-heading bg-white border-bottom">coins</h1>
  <div class="innerAll bg-white border-bottom">
  <ul class="menubar">
    <li><a href="<?php echo SURL; ?>admin/reports/">Reports</a></li>
    <li class="active"><a href="<?php echo SURL; ?>admin/coin_meta/view_coin_meta">Coin Meta</a></li>
    <li><a href="<?php echo SURL; ?>admin/reports/barrier_listing">Barrier Listing</a></li>
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

        <?php
if (count($info) > 0) {
	foreach ($info as $key => $value) {
		?>
            <div class="widget widget-inverse">

          <!-- Widget heading -->
          <div class="widget-head">
            <span style="float:left;"><h4 class="heading"><?php echo $value['coin'] ?></h4></span>
            <span style="float:right;"><button class="btn btn-info">Edit Meta Fields</button></span>
          </div>
          <div class="widget-body">

            <!-- 4 Column Grid / One Fourth -->
            <div class="row">

              <!-- One Fourth Column -->
              <div class="col-md-3">
                <h4>Current Market Value</h4>
                <p><?php echo num($value['current_market_value']); ?></p>
              </div>
              <!-- // One Fourth Column END -->
              <div class="col-md-3">
                <h4>Black Wall Difference</h4>
                <p><?php echo ($value['black_wall_pressure']); ?></p>
              </div>

            <!-- // One Fourth Column END -->
              <div class="col-md-3">
                <h4>Yellow Wall Difference</h4>
                <p><?php echo ($value['yellow_wall_pressure']); ?></p>
              </div>

            <!-- // One Fourth Column END -->
              <div class="col-md-3">
                <h4>Pressure Type</h4>
                <p><?php echo ($value['pressure_diff']); ?></p>
              </div>

            <!-- // One Fourth Column END -->
              <div class="col-md-3">
                <h4>Ask Contracts</h4>
                <p><?php echo number_format_short($value['ask_contract']); ?></p>
              </div>

            <!-- // One Fourth Column END -->
              <div class="col-md-3">
                <h4>Ask Contracts (%)</h4>
                <p><?php echo number_format($value['ask_percentage'], 2); ?></p>
              </div>

            <!-- // One Fourth Column END -->
              <div class="col-md-3">
                <h4>Bid Contracts</h4>
                <p><?php echo number_format_short($value['bid_contracts']); ?></p>
              </div>

            <!-- // One Fourth Column END -->
              <div class="col-md-3">
                <h4>Bid Contracts (%)</h4>
                <p><?php echo number_format($value['bid_percentage'], 2); ?></p>
              </div>

               <!-- // One Fourth Column END -->
              <div class="col-md-3">
                <h4>Buyers</h4>
                <p><?php echo number_format_short($value['buyers']); ?></p>
              </div>
                <!-- // One Fourth Column END -->
              <div class="col-md-3">
                <h4>Buyers (%)</h4>
                <p><?php echo number_format($value['buyers_percentage'], 2); ?></p>
              </div>
                <!-- // One Fourth Column END -->
              <div class="col-md-3">
                <h4>Sellers</h4>
                <p><?php echo number_format_short($value['sellers']); ?></p>
              </div>
                <!-- // One Fourth Column END -->
              <div class="col-md-3">
                <h4>Up Big Wall</h4>
                <p><?php echo num($value['up_big_price']) . "(" . number_format_short($value['up_big_wall']) . ")"; ?></p>
              </div>
              <!-- // One Fourth Column END -->
             <div class="col-md-3">
                <h4>Down Big Wall</h4>
                <p><?php echo num($value['down_big_price']) . "(" . number_format_short($value['down_big_wall']) . ")"; ?></p>
              </div>
              <!-- // One Fourth Column END -->
              <div class="col-md-3">
                <h4>Great Big Wall</h4>
                <p style="color:<?php echo $value['great_wall_color']; ?>;"><?php echo num($value['great_wall_price']) . "(" . number_format_short($value['great_wall_quantity']) . ")"; ?></p>
              </div>
              <!-- // One Fourth Column END -->
              <div class="col-md-3">
                <h4>Great Wall Pressure</h4>
                <p><?php echo ($value['great_wall']); ?></p>
              </div>
              <!-- // One Fourth Column END -->
              <div class="col-md-3">
                <h4>Seven Level Depth</h4>
                <p><?php if ($value['seven_level_type'] == 'negitive') {
			echo "-" . number_format($value['seven_level_depth'], 2);
		} else {
			echo number_format($value['seven_level_depth'], 2);
		}?></p>
              </div>
            <!-- // 4 Column Grid / One Fourth END -->
          </div>
      </div>
      </div>
          <?php }
}
?>
    </div>
    <!-- // Widget END -->

  </div>
</div>

<!-- Modal -->

<div class="modal fade" id="edit_modal_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Barrier</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">
      <form class="form-horizontal" id="add_form" method="POST" action="<?=SURL?>admin/coin_meta/edit_meta">
        <div class="form-group">
          <label class="control-label col-sm-2" for="coin_coin">Coin:</label>
          <div class="col-sm-10">
           <input type="text" class="form-control" name="coin" id="coin_coin" value="coin">
        </div>
      </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="channel">Channel:</label>
          <div class="col-sm-10">
            <section>
                <div>
                  <input type="radio" id="control_01" name="channel" value="yes">
                  <label class="label2" for="control_01">
                    <p>Yes</p>
                  </label>
                </div>
                <div>
                  <input type="radio" id="control_05" name="channel" value="no">
                  <label class="label2" for="control_05">
                    <p>No</p>
                  </label>
                </div>
                </section>
          </div>
        </div>
        <!-- <div class="form-group">
          <label class="control-label col-sm-2" for="barrier_val">Label 2:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="barrier_val" id="barrier_val" value="">
          </div>
        </div> -->
        <!-- <div class="form-group">
          <label class="control-label col-sm-2" for="barrier_val">Label 3:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="barrier_val" id="barrier_val" value="">
          </div>
        </div> -->
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="btn_upd" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $('body').on("click","#edtbtn",function(e){
      $("#coin_coin").val($(this).data("coin"));
      $("#coin_coin").prop("readonly","true");
      var channel = $(this).data("channel");
      if(channel == 'yes'){
        $("#control_01").prop("checked", true);
      }else if(channel == 'no'){
        $("#control_05").prop("checked", true);
      }else{
        $("#control_01").prop("checked", false);
        $("#control_05").prop("checked", false);
      }
      $("#edit_modal_form").modal("show");
  });

  $("body").on("click","#btn_upd", function(e){

      $("#add_form").submit();

  });

  function autoload_market_coin_meta(){
        $.ajax({
        type: "POST",
        data: "",
        url:'<?php echo SURL ?>admin/coin_meta/autoload_meta/',
        success:function(response){
            $('#response_text').html(response);
            setTimeout(function() {
                        autoload_market_coin_meta();
                  }, 5000);
        },
        error: function(errorThrown) {
            console.log("Error: " + errorThrown);
            setTimeout(function() {
                autoload_market_coin_meta();
          }, 5000);
        }
      });

  }//end autoload_market_buy_data()

  autoload_market_coin_meta();
</script>