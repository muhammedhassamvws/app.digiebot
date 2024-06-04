<link href="<?php echo ASSETS;?>cdn_links/bootstrap-datetimepicker-master/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="<?php echo ASSETS;?>cdn_links/moment-with-locales.js"></script>
<script src="<?php echo ASSETS;?>cdn_links/bootstrap-datetimepicker-master/build/js/bootstrap-datetimepicker.min.js"></script>

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
</style>
<div id="content" class="get-relative">
    <div class="optimized-loader" style="display:none;">
				<img src="http://app.digiebot.com/assets/images/load_cube.gif">
    </div>
  <h1 class="content-heading bg-white border-bottom">Data Analysis</h1>
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
            <h4>Data Analysis</h4>
        </div>
      	<div class="widget-body">

          <!-- Row -->
          <div class="row">
            <div class = 'col-md-12'>
              <label>Select A Coin</label>
              <select class="form-control" name="coin_symbol" id="symbol" placeholder="Select a Coin">
                <?php foreach ($coin_arr as $key => $value) {
                  echo "<option value='".$value['symbol']."'>".$value['symbol']."</option>";
                } ?>
              </select>
            </div>
              <div class = 'col-md-12'>
                <label>Select An Hour</label>
                <input type="text" class = "datetime_picker form-control" placeholder="select a datetime" id="date_picker" >
              </div>
          </div>
          <script type="text/javascript">
              $(function () {
                  $('.datetime_picker').datetimepicker();
              });
          </script>
          <!-- // Row END -->


          <hr class="separator" />
          <input type="hidden" value="0" name="submit">
          <!-- Form actions -->
          <div class="form-actions">
            <button type="submit" id='submit' class="btn btn-primary"><i class="fa fa-check-circle"></i> Check</button>
            <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancel</button>
          </div>
          <!-- // Form actions END -->

        </div>
        <hr class="separator" />
        <div class="widget-body">
          <div class="row">
            <div class="col-md-12">
              <div id = "candle_info" class="table-responsive">
            </div>
          </div>
        </div>

      </div>
      <!-- // Widget END -->


  </div>
</div>
<!-- Button trigger modal -->
<!-- Modal -->
<div class="modal fade" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Data Anaylsis</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="response_data">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>
<script>
$("body").on("click","#submit",function(argument) {
    $(".optimized-loader").show();
    var time = $("#date_picker").val();
    var coin = $("#symbol").val();

    $.ajax({
      url: "<?php echo SURL; ?>/admin/data_analysis/get_the_data",
      data:{coin:coin, time:time},
      type:"POST",
      success: function(response){
        $("#candle_info").html(response);
        $(".optimized-loader").hide();
      }
    });
  });

  $("body").on("click",".analysis",function(argument) {
      var time = $("#date_picker").val();
      var coin = $("#symbol").val();
      var type = $(this).data('id');
      $.ajax({
        url: "<?php echo SURL; ?>/admin/data_analysis/analyze_data",
        data:{coin:coin, time:time, type:type},
        type:"POST",
        success: function(response){
          $("#response_data").html(response);
        }
      });
    });
</script>
