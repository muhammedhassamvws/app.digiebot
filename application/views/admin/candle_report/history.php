<?php //echo "<pre>";   print_r($rules_set_arr); exit; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">

<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
<style>
.loaderImage {
	float: left;
	width: 100%;
	position: relative;
}
.loaderimagbox {
	background: rgba(255, 255, 255, 0.78);
	position: absolute;
	z-index: 9;
	width: 100%;
	height: 100%;
	left: 0;
	top: 0;
	bottom: 0;
}
.loaderimagbox img {
	position: absolute;
	margin: auto;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	border-radius: 50%;
}

.loaderImage {
	float: left;
	width: 100%;
	position: relative;
}
.loaderimagbox {
	background: rgba(255, 255, 255, 0.78);
	position: absolute;
	z-index: 9;
	width: 100%;
	height: 100%;
	left: 0;
	top: 0;
	bottom: 0;
}
.loaderimagbox img {
	position: absolute;
	margin: auto;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	border-radius: 50%;
}
table.table.table-bordered.zama_th tr:hover {
	background: #ececec;
}
.headerclass {
	width: 160px;
}
</style>
<div id="content">
  <div class="heading-buttons bg-white border-bottom innerAll">
    <h1 class="content-heading padding-none pull-left">Candle Chart Report</h1>
    <div class="clearfix"></div>
  </div>
  <div class="innerAll spacing-x2">
    <div class="widget-body"> 
    
     <div class="row">
        <div class="col-md-12 ">
    
     <?php if($this->session->flashdata('ok_message') != ''){?>
  <div class="alert alert-success"> <span class="alert alert-success" style="margin:0px;"><?php echo $this->session->flashdata('ok_message'); ?> </span></div>
  <?php }
	  
if($this->session->flashdata('err_message') != ''){?>
  <div class="alert alert-danger"><span class="alert alert-danger" style="margin:0px;"><?php echo $this->session->flashdata('err_message'); ?> </span></div>
  <?php }?>
  </div>
  </div>
      
      <!-- Table -->
      <div class="row">
        <div class="col-md-12 ">
          <form action="<?php echo SURL ?>admin/candle-report/history" name="" method="post" >
            <div class="col-md-12 ">
              <div class="form-group col-md-2">
                <label class="control-label">Start Date</label>
                <input type='text' id="datetime_picker" class="form-control  datetime_picker" name="previous_date" placeholder="Search By Start Date" value="<?php echo $page_post_data['previous_date']; ?>"  autocomplete="off"/>
              </div>
              <div class="form-group col-md-2">
                <label class="control-label">End Date </label>
                <input type='text' id="datetime_picker" class="form-control datetime_picker" name="forward_date" placeholder="Search By End Date" value="<?php echo $page_post_data['forward_date']; ?>" autocomplete="off" />
              </div>
              <div class="form-group col-md-2">
                <label>Coin </label>
                <select class="form-control coin" name="coin" id="coin">
                  <?php
                foreach ($coins_arr as $coin) { ?>
                  <option value="<?php echo $coin['symbol'];?>" 
                <?php echo ($global_symbol == $coin['symbol']) ? 'selected="selected"' : '';?>> <?php echo $coin['symbol']; ?> </option>
                  <?php }?>
                </select>
              </div>
              <!--<div class="form-group col-md-1" style="padding-top: 25px;">
               
                <button class="s_submit btn btn-success" name="submit" id="submit">Submit</button>
                
               
              </div>-->
              <div class="form-group col-md-1  " style="padding-top: 25px;">
               
               
                
                 <input type="submit"   class="btn btn-info" name="csv" id="csv" value="CSV REPORT">
              </div>
            </div>
          </form>
        </div>
      </div>
      
      <!--  End of cancel trade--> 
      
      <!--  End  Trigger_1 -->
      
      <div class="col-md-12 ">
        <div class="alert alert-danger fade in alert-dismissible errodiv" style="display:none;"> <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> <span class="showerrormessage"></span> </div>
        <br />
      </div>
      <?php /*?><!--<div class="loaderImage">
        <div class="loaderimagbox" style="display:none;"><img src="<?php echo SURL ?>assets/images/loader.gif" /></div>
        <div class="triggercls show_trigger" >
          <div class="col-md-12 appnedAjax" >
            <div class="tab-content">
              <div id="buy" class="tab-pane fade in active"> 
                <!-- Buy part -->
                
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col" style="background:#4267b2; color: #FFF;">Aggressive Stop Rule</th>
                      <th scope="col" style="background:#4267b2; color: #FFF;">Buy Range %</th>
                      <th scope="col" style="background:#4267b2; color: #FFF;">Deep % For Active</th>
                      <th scope="col" style="background:#4267b2; color: #FFF;">Initial Stop Loss</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="centre">sadsadsa</td>
                      <td class="centre"><?php echo $rules_set_arr->buy_range_percet .' %';  ?></td>
                      <td class="centre"><?php echo $rules_set_arr->sell_profit_percet .' %';  ?></td>
                      <td class="centre"><?php echo $rules_set_arr->stop_loss_percet;  ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>--><?php */?>
    </div>
    <!-- END ROW -->
    <div class="clearfix"></div>
    <div class="clearfix"></div>
  </div>
</div>
</div>
<script>
 $(function () {
      $('.datetime_picker').datetimepicker({});
 });
</script> 