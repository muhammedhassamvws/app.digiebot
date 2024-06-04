<div id="content">
  <h1 class="content-heading bg-white border-bottom">Add coin</h1>
  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
      <li><a href="<?php echo SURL; ?>/admin/coins">Coins</a></li>
      <li class="active"><a href="#">Add Coins</a></li>
    </ul>
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
    <form action="<?php echo SURL; ?>admin/coins/add_coin_process" class="form-horizontal margin-none" id="validateSubmitForm" method="post" autocomplete="off" enctype= "multipart/form-data">
      <div class="widget-body">


        <!-- Row -->
        <div class="row">
        <div class="col-md-12">
            <div class="form-group col-md-12">
              <select name="exchange" id="exchange" class="form-control" >
              <option value="">Select  Exchange</option>
              <option value="binance">Binance</option>
              <option value="coin_base_pro">Coin Base Pro</option>
              </select>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="coin_name">Coin Name</label>
              <input class="form-control" id="coin_name" name="coin_name" type="text" required="required" />
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="symbol">Symbol</label>
              <input class="form-control" id="symbol" name="symbol" type="text" required="required" />
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="symbol">Keywords &nbsp;<small>(Add the keywords "," seprated)</small></label>
              <input type="text" class="form-control" name="keywords" value="">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="symbol">Unit Value</label>
              <input type="number" class="form-control" name="unit_value" value="">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="symbol">Offset Value</label>
              <input type="number" class="form-control" name="offset">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="symbol">Order Book Base</label>
              <input type="number" class="form-control" name="base_order" value="<?php echo $coin_arr['base_order']; ?>">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="symbol">Trade History Base</label>
              <input type="number" class="form-control" name="base_history" value="<?php echo $coin_arr['base_history']; ?>">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="rejection">Rejection Candle Percentage</label>
              <input type="number" class="form-control" name="rejection" value="">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label>Black Wall Percentage</label>
              <div class="input-group"> <span class="input-group-addon">
                <input type="radio" name="wall_setting" value="percentage">
                </span>
                <input type="number" class="form-control" name="depth_wall_percentage">
              </div>
              <label>Black Wall Amount</label>
              <div style="padding-top: 5px;" class="input-group"> <span class="input-group-addon">
                <input type="radio" name="wall_setting" value="amount">
                </span>
                <input type="number" class="form-control" name="depth_wall_amount">
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label>Yellow Wall Percentage</label>
              <div class="input-group"> <span class="input-group-addon">
                <input type="radio" name="yellow_wall_setting" value="percentage">
                </span>
                <input type="number" class="form-control" name="yellow_wall_percentage">
              </div>
              <label>Yellow Wall Amount</label>
              <div style="padding-top: 5px;" class="input-group"> <span class="input-group-addon">
                <input type="radio" name="yellow_wall_setting" value="amount">
                </span>
                <input type="number" class="form-control" name="yellow_wall_amount">
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="depth_wall">Contract Percentage</label>
              <input type="number" class="form-control" name="contract_percentage">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="depth_wall">Contract Time In Minutes</label>
              <input type="number" class="form-control" name="contract_time">
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="contract_size">Contract Size</label>
              <input type="number" class="form-control" name="contract_size">
            </div>
          </div>

        </div>
        <div class="form-group col-md-12">
          <label class="control-label" for="symbol">Logo</label>
          <input class="form-control" id="logo" name="logo" type="file" required="required" />
          <input type="hidden" name="logo1" id="logo1">
        </div>
        <div class="col-md-12">
          <div class="form-group col-md-6">
            <div class="coinimage" ><img class="loader" src="<?php echo SURL ?>assets/images/loader.gif" style="display:none;"/></div>
          </div>
        </div>

        <!--<div class="col-md-12" id="alert"></div>-->
      </div>
      <!-- // Row END -->

      <!-- Form actions -->
      <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
        <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancel</button>
      </div>
      <!-- // Form actions END -->

      </div>
    </form>
    <!-- // Form END -->

  </div>
  <!-- // Widget END -->

</div>
</div>
<script type="text/javascript">
// Please use body on change instead of   direct on change
$('body').on('change', '#logo', function () {
	     $(".loader").show();
         var data = new FormData();
         var files = $("#logo").get(0).files;
         if (files.length > 0) {
            data.append("image", files[0]);
         }
         $.ajax({
          url: '<?php echo SURL; ?>admin/coins/create_thumbnail',
          type: "POST",
         processData: false,
         contentType: false,
         data: data,
        success: function (response) {
             $("#logo1").val(response);

			 $(".loader").hide();
             //$('#alert').html('<p class="alert alert-dismissable alert-success">Image Has been uploaded successfully</p>');
			 $(".coinimage").html('<img  class="img img-circle"  style="width:100px; height:100px" src="<?php echo SURL ?>assets/coin_logo/'+response+'"  />');
         },
         error: function (er) {
            console.log(er);
            $('#alert').html('<p class="alert alert-dismissable alert-danger">Image Must Be in JPEG format <br>'+er.status+':'+er.statusText+'</p>');
         }

      });
      });
</script>
<!-- <script type="text/javascript" src="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
<script>
  /*  $('#tags').tagsinput({
        allowDuplicates: true
    });*/
</script> -->