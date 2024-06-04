<?php //echo "<pre>";   print_r($rules_set_arr); exit; ?>
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
table.table.table-bordered.zama_th tr:hover {
	background: #ececec;
}
</style>


<style>
.close_colm {
    float: right;
    border: 1px solid #F44336;
    height: 15px;
    width: 15px;
    border-radius: 50%;
    font-size: 7px;
    font-weight: normal;
    text-align: center;
    padding-top: 2px;
    cursor: pointer;
    margin-top: 3px;
    margin-right: -4px;
	background: #F44336;
    color: #fff;
}
.close_colm:hover {
    border: 1px solid #F44336;
    background: #fff;
    color: #F44336;
}
.close_row {
    float: left;
    width: 15px;
    height: 15px;
    font-size: 8px;
    border: 1px solid #F44336;
    text-align: center;
    border-radius: 50%;
    padding-top: 2px;
    margin-right: 10px;
    margin-top: 4px;
    cursor: pointer;
    background: #F44336;
    color: #fff;
}
.close_row:hover {
    border: 1px solid #F44336;
    background: #fff;
    color: #F44336;
}
table.compair_hide_row_col_table td.hover{
	background:#ececec !important;
}
</style>
<div id="content">
  <div class="heading-buttons bg-white border-bottom innerAll">
    <h1 class="content-heading padding-none pull-left">Compare Coin Rules </h1>
    <div class="clearfix"></div>
  </div>
  <div class="innerAll spacing-x2">
    <div class="widget-body"> 
      
      <!-- Table -->
      <div class="row">
        <div class="col-md-12 ">
          <div class="form-group col-md-4">
            <label class="control-label" for="hour">Select Trigger </label>
            <select class="form-control triggers_type" name="triggers_type">
              <option value="">Select Trigger</option>
              <option value="barrier_trigger">Barrier Trigger</option>
              <option value="barrier_percentile_trigger">Barrier Percentile Trigger</option>
            </select>
          </div>
          <div class="form-group col-md-4 barrier" style="">
            <label class="control-label" for="rule">Select Rule #</label>
            <select class="form-control rule" name="rule" id="rule">
              <?php 
			 for ($x = 1; $x <= 10; $x++) { ?>
              <option value="<?php echo $x;?>">Rule <?php echo $x;?></option>
              <?php }?>
            </select>
          </div>
          <div class="form-group col-md-4 percentile" style="display:none">
            <label class="control-label" for="rule">Select Level #</label>
            <select class="form-control rule" name="rule" id="rule">
              <?php 
			 for ($x = 1; $x <= 15; $x++) { ?>
              <option value="<?php echo $x;?>">Level <?php echo $x;?></option>
              <?php }?>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label class="control-label" for="hour">Select Order Mode </label>
            <select class="form-control order_mode" name="order_mode">
              <option value="live">(Real time and test live)</option>
              <option value="test">Simulator Test</option>
            </select>
          </div>
        </div>
      </div>
      
      <!--  End of cancel trade--> 
      
      <!--  End  Trigger_1 -->
      
      <div class="col-md-12 ">
        <div class="alert alert-danger fade in alert-dismissible errodiv" style="display:none;"> <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> <span class="showerrormessage"></span> </div>
        <br />
      </div>
      <div class="loaderImage">
        <div class="loaderimagbox" style="display:none;"><img src="<?php echo SURL ?>assets/images/loader.gif" /></div>
        <div class="triggercls show_trigger" >
          <div class="col-md-12 appnedAjax" >
            <div class="tab-content " style="height:800px;">
              <div class="color-box space">
                <div class="shadow">
                  <div class="info-tab tip-icon" title="Useful Tips"><i></i></div>
                  <div class="tip-box">
                    <p><strong>Tip:</strong> Please select the trigger and rule to comapre the differnt coins with rule and level and compare the prices .</p>
                  </div>
                </div>
              </div>
              <div class="alert alert-primary" role="alert"> </div>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <!-- END ROW -->
    <div class="clearfix"></div>
    <div class="clearfix"></div>
  </div>
</div>
</div>
<br />
<script type="text/javascript">

  $(document).on('click','.getAjaxData',function(){
	  
	  $(".loadprofitIMg").show();    
      var start_date     = $('#start_date').val();
	  var end_date       = $('#end_date').val();
	  var coin           = $('.coin').val();
	  var triggers_type  = $('.triggers_type').val();
	  var userID         = 169;
	  var order_mode     = $('.order_mode').val();
	 
      $.ajax({
        'url': '<?php echo base_url(); ?>admin/rules_order/get_rulesOrderProfit_ajax',
        'data': {start_date:start_date,end_date:end_date,coin:coin,triggers_type:triggers_type,userID:userID,order_mode:order_mode},
        'type': 'POST',
        success : function(data){
            var res_obj =  JSON.parse(data);
			
			if(res_obj.success==true){
			  $('.triggercls').hide();
			  $('.trg_'+triggers_type).show();
              $('.appnedAjax').html('');
			  $('.trg_'+triggers_type).html('');
			  $('.trg_'+triggers_type).html(res_obj.html);
			  $(".loaderimagbox").hide();
			}else{
				
			   $('.triggercls').hide();
			   $('.errodiv').show();
			   $('.showerrormessage').html(res_obj.html);	
			   $(".loaderimagbox").hide();  
			}
         }
     });            
    });

</script> 
<script type="text/javascript">

  $(document).on('change','.order_mode, .rule, .triggers_type',function(){
	  
	  $(".loaderimagbox").show();   
	  
	  var triggers_type  = $('.triggers_type').val(); 
      
	  if(triggers_type=='barrier_trigger'){
		  $('.percentile').hide();
		  $('.barrier').show();
	  }else{
		  $('.percentile').show();
		  $('.barrier').hide();
	  }
	  
	  var order_mode     = $('.order_mode').val();
	  var testing        = '<?php echo $testing ?>';
      var rule           = $('.rule:visible').val();
	 
	  
      $('.errodiv').hide();
	  
      $.ajax({
        'url': '<?php echo base_url(); ?>admin/rules_order/get_global_rules_ajax',
        'data': {order_mode:order_mode,rule:rule,triggers_type:triggers_type,testing:testing},
        'type': 'POST',
        success : function(data){
            var res_obj =  JSON.parse(data);
			
			if(res_obj.success==true){
			  
              $('.appnedAjax').html('');
			  $('.show_trigger').html('');
			  $('.show_trigger').html(res_obj.html);
			  $(".loaderimagbox").hide();
			  setTimeout(function(){
				  compare_remover_item_func();
			  },1000)
			  
			}else{
				
			   $('.show_trigger').html('');
			   $('.errodiv').show();
			   $('.showerrormessage').html(res_obj.html);	
			   $(".loaderimagbox").hide();  
			  
			}
         }
     });            
    });
function compare_remover_item_func(){
	
	
	$("table.compair_hide_row_col_table").each(function(index, element) {
        var thead_indx = 0
		$(this).find("thead > tr > th").each(function(index, element) {
			thead_indx++;
			$(this).attr("index",thead_indx);
            
        });
		
		$(this).find("tbody > tr").each(function(index, element) {
			var tbody_indx = 1;
			$(this).find("td").each(function(index, element) {
				tbody_indx++;
                $(this).attr("index",tbody_indx);
            });
        });
		
    });
	
	$("body").on("mouseenter",".compair_hide_row_col_table thead > tr > th",function(){
		var colindexo = $(this).attr("index");
		$(this).closest("table.compair_hide_row_col_table").find("td[index="+colindexo+"]").addClass("hover");
	});
	$("body").on("mouseleave",".compair_hide_row_col_table thead > tr > th",function(){
		var colindexl = $(this).attr("index");
		$(this).closest("table.compair_hide_row_col_table").find("td[index="+colindexl+"]").removeClass("hover");
	});
	
	
	$("body").on("click",".close_colm",function(){
		var colindex = $(this).closest("th").attr("index");
		$(this).closest("th").hide();
		$(this).closest("table.compair_hide_row_col_table").find("td[index="+colindex+"]").hide();
	});
	
	$("body").on("click",".close_row",function(){
		$(this).closest("tr").hide();
	});

}
</script> 



































