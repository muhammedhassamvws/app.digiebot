<div id="content">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
  <h1 class="content-heading bg-white border-bottom">Edit coin</h1>
  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
      <li><a href="<?php echo SURL; ?>admin/coins">coins</a></li>
      <li class="active"><a href="<?php echo SURL; ?>admin/coins/edit-coin/<?php echo $coin_id; ?>">Edit coin</a></li>
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
    <form action="<?php echo SURL; ?>admin/coins/edit_coin_process" class="form-horizontal margin-none" id="validateSubmitForm" method="post" autocomplete="off" enctype= "multipart/form-data">
      <div class="widget-body">

        <!-- Row -->
        <div class="row">
          <div class="col-md-12">
            <!-- <div class="form-group col-md-12">
              <select name="exchange" id="exchange" class="form-control" >
              <option value="">Select  Exchange</option>
              <option value="binance" <?php if ($coin_arr['exchange_type'] == 'binance') {?> selected <?php }?>>Binance</option>
              <option value="kraken" <?php if ($coin_arr['exchange_type'] == 'kraken') {?> selected <?php }?>>Kraken</option>
              <option value="coin_base_pro" <?php if ($coin_arr['exchange_type'] == 'coin_base_pro') {?> selected <?php }?>>Coin Base Pro</option>
              </select>
            </div> -->
           
            <div class="form-group col-md-12">
              <label class="control-label" for="coin_name">Coin Name</label>
              <input class="form-control" id="coin_name" name="coin_name" type="text" required="required" value="<?php echo $coin_arr['coin_name']; ?>" />
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="symbol">Symbol</label>
              <input class="form-control" id="symbol" name="symbol" type="text" required="required" value="<?php echo $coin_arr['symbol']; ?>" />
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="symbol">Keywords &nbsp;<small>(Add the keywords "," seprated)</small></label>
              <input type="text" name="keywords" class="form-control" value="<?php echo $coin_arr['coin_keywords']; ?>">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="symbol">Unit Value</label>
              <input type="number" class="form-control" name="unit_value" value="<?php echo $coin_arr['unit_value']; ?>">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="symbol">Offset Value</label>
              <input type="number" class="form-control" name="offset" value="<?php echo $coin_arr['offset_value']; ?>">
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
              <input type="number" class="form-control" name="rejection" value="<?php echo $coin_arr['rejection']; ?>">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label>Black Wall Percentage</label>
              <div class="input-group"> <span class="input-group-addon">
                <input type="radio" name="wall_setting" value="percentage" <?php if ($coin_arr['wall_setting'] == 'percentage') {
	echo "checked";
}?>>
                </span>
                <input type="number" class="form-control" name="depth_wall_percentage" value="<?php echo $coin_arr['depth_wall_percentage'] ?>">
              </div>
              <label>Black Wall Amount</label>
              <div style="padding-top: 5px;" class="input-group"> <span class="input-group-addon">
                <input type="radio" name="wall_setting" value="amount" <?php if ($coin_arr['wall_setting'] == 'amount') {
	echo "checked";
}?>>
                </span>
                <input type="number" class="form-control" name="depth_wall_amount" value="<?php echo $coin_arr['depth_wall_amount'] ?>">
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label>Yellow Wall Percentage</label>
              <div class="input-group"> <span class="input-group-addon">
                <input type="radio" name="yellow_wall_setting" value="percentage" <?php if ($coin_arr['yellow_wall_setting'] == 'percentage') {
	echo "checked";
}?>>
                </span>
                <input type="number" class="form-control" name="yellow_wall_percentage" value="<?php echo $coin_arr['yellow_wall_percentage'] ?>">
              </div>
              <label>Yellow Wall Amount</label>
              <div style="padding-top: 5px;" class="input-group"> <span class="input-group-addon">
                <input type="radio" name="yellow_wall_setting" value="amount" <?php if ($coin_arr['yellow_wall_setting'] == 'amount') {
	echo "checked";
}?>>
                </span>
                <input type="number" class="form-control" name="yellow_wall_amount" value="<?php echo $coin_arr['yellow_wall_amount'] ?>">
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="depth_wall">Contract Percentage</label>
              <input type="number" class="form-control" name="contract_percentage" value="<?php echo $coin_arr['contract_percentage']; ?>">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="depth_wall">Contract Time In Minutes</label>
              <input type="number" class="form-control" name="contract_time" value="<?php echo $coin_arr['contract_time']; ?>">
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="contract_size">Contract Size</label>
              <input type="number" class="form-control" name="contract_size" value="<?php echo $coin_arr['contract_size']; ?>">
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="contract_period">Contract Period</label>
              <input type="number" class="form-control" name="contract_period" value="<?php echo $coin_arr['contract_period']; ?>">
            </div>
          </div>
          <div class="row">
            <?php $coin_blocked_countries = get_blocked_countries_of_coin($coin_arr['symbol']); ?>
            <div class="col-xs-12 col-md-12 form-group">
              <label>Restricted Countries</label>
              <select id="country_multi" name="country_multi[]" type="text" class="form-control filter_by_name_margin_bottom_sm" multiple="multiple" data-mdb-filter="true">
                <option value=""> None </option>
                <?php
                  foreach(RestCountries() as $country) { ?>
                    <option value="<?=$country['name']?>" <?php if((isset($coin_blocked_countries['blocked_country_array'])) && (in_array($country['name'],iterator_to_array($coin_blocked_countries['blocked_country_array'])))){ echo 'selected' ; }?>><?=$country['name']?></option>
                    <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group col-md-12">
              <label class="control-label" for="symbol">Logo</label>
              <input class="form-control" id="logo" name="logo" type="file" />
              <input type="hidden" name="logo1" id="logo1">
            </div>
          </div>

          <div class="col-md-12">
          <div class="col-md-6">
          <div id="imgbox" style="width: 100%;">
           <img id="coin_image" class="img img-circle" src="<?php echo ASSETS; ?>coin_logo/<?php echo $coin_arr['coin_logo']; ?>" style="width:100px; height:100px"></div>
          </div>


          <div class="form-group col-md-6">
                <div class="coinimage" ><img class="loader" src="<?php echo SURL ?>assets/images/loader.gif" style="display:none;"/></div>
              </div>
          </div>

        </div>
      </div>
      <!-- // Row END -->


      <!-- Form actions -->
      <div class="form-actions">
        <input name="coin_id" type="hidden" value="<?php echo $coin_arr['_id']; ?>" />
        <input name="exchange" type="hidden" value="<?php echo $exchange; ?>" />
        <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#country_multi").multiselect({
              enableFiltering: true,
              enableCaseInsensitiveFiltering: true,
              buttonWidth:'100%'
        });
  });
  $('body').on('change', '#logo', function () {
	     $(".loader").show();
         var data = new FormData();
         var files = $("#logo").get(0).files;
         if (files.length > 0) {
            data.append("image", files[0]);
         }
         console.log(files);
         $.ajax({
          url: '<?php echo SURL; ?>admin/Coins/create_thumbnail',
          type: "POST",
         processData: false,
         contentType: false,
         data: data,
         success: function (response) {
          console.log(response);
			 $(".loader").hide();
          $("#logo1").val(response);
			 $("#imgbox").html('');
			 $("#imgbox").html('<img id="coin_image" class="img img-circle" src="<?php echo ASSETS; ?>coin_logo/'+response+'" style="width:100px; height:100px">');
             //$('#alert').html('<p class="alert alert-dismissable alert-success">Image Has been uploaded successfully</p>');
         },
         error: function (er) {
            $('#alert').html('<p class="alert alert-dismissable alert-danger">Image Must Be in JPEG format <br>'+er+'</p>');
         }

      });
      });
</script>
<script type="text/javascript">
  /*$(function () {
    $(":file").change(function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(this.files[0]);
        }
    });
});

function imageIsLoaded(e) {
    $('#coin_image').attr('src', e.target.result);
};*/
</script>
