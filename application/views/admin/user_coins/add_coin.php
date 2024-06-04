<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
<?php //print_r($coins_arr); exit; ?>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Add coin</h1>
  <div class="bg-white innerAll border-bottom">
	<ul class="menubar">
    	<li><a href="<?php echo SURL; ?>/admin/coin_market">Coins</a></li>
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
    	<form action="<?php echo SURL; ?>admin/user_coins/add_coin_process" class="form-horizontal margin-none" id="validateSubmitForm" method="post" autocomplete="off" enctype= "multipart/form-data">
        <div class="widget-body">

          <!-- Row -->
          <div class="row">

            <div class="col-md-12">
              <div class="form-group col-md-6">
                <label class="control-label" for="coin_name">Select Coin Name You Want To Deal With</label>
                <div style="padding-top:10px;">
                <select class="form-control" id="coins" name="coins[]" multiple>
                  <?php
if (count($all_coins_arr) > 0) {
    for ($i = 0; $i < count($all_coins_arr); $i++) {
        ?>
                  <?php if (in_array($all_coins_arr[$i]['symbol'], $coins_arr)) {?>
                      <option value="<?php echo $all_coins_arr[$i]['_id'] ?>" selected><?php echo $all_coins_arr[$i]['symbol']; ?></option>
                  <?php } else {?>
                          <option value="<?php echo $all_coins_arr[$i]['_id'] ?>" ><?php echo $all_coins_arr[$i]['symbol']; ?></option>
                    <?php }
    }
}
?>
                </select>
              </div>
              </div>
            </div>

         <!--    <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="symbol">Symbol</label>
                <input class="form-control" id="symbol" name="symbol" type="text" required="required" />
              </div>
            </div> -->

          </div>

         <!--  <div class="col-md-12">
              <div class="form-group col-md-12">
                <label class="control-label" for="symbol">Logo</label>
                <input class="form-control" id="logo" name="logo" type="file" required="required" />
              </div>
            </div> -->

         <!--  </div> -->
          <!-- // Row END -->


          <hr class="separator" />

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
<script>
$(document).ready(function(){
 $('#coins').multiselect({
  nonSelectedText: 'Select Coin',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'100%'
 });
});
</script>
