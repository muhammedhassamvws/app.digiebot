
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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








  .switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<div id="content">


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
          <div class="col-md-12">
            <div class="widget widget-inverse">
                <form id="buy_order_form" class="form-horizontal margin-none" " novalidate="novalidate">
                <div class="widget-body">
                  <!-- Row -->
                     <div class="row">
                    <div class="col-md-3">
                      <div class="form-group col-md-12">
                        <label class="control-label">Set Trigger Start Date </label>
                        <input type="text" id="date" name="date" placeholder="" value="2018-04-01 00:00:00"  class="form-control">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group col-md-12">
                        <label class="control-label">Select Trigger Type </label>
                        <select class="trigger_type_cls form-control" name="trigger_type">
                          <option value="barrier_trigger"> barrier_trigger</option>
                          <option value="barrier_percentile_trigger"> barrier_percentile_trigger</option>
                          <option value="percentile_calculation">percentile_calculation</option>
                          <option value="meta_percentile_calculation"> meta_percentile_calculation</option>
                          <option value="box_trigger_3">Box Trigger_3</option>

                            <option value="trigger_2">trigger_2</option>
                            <option value="update_candel_type">Update  Candel Type</option>
                            <option value="trigger_1">trigger_1</option>

                            <option value="rg_15">Trigger rg_15</option>



                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group col-md-12">
                      <label class="control-label">Select Coin </label>
                          <select class="form-control" name="coin" id="coin" >
                            <option value="">Select Coin</option>
                          <?php
foreach ($coins as $coin) {
    ?>
                          <option value="<?php echo $coin['symbol'] ?>"><?php echo $coin['symbol']; ?></option>
                          <?php }
?>
                          </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group col-md-12">
                        <label class="control-label">Cron JOb Status </label>
                        <label class="switch">
                            <input type="checkbox" id="cron_job_on_off">
                            <span class="slider round" ></span>
                          </label>
                      </div>
                    </div>
                  </div><!-- End of Row -->
                    <input type="hidden" id="look_forward" name="look_forward" required="" value="1" class="form-control">
                  </div>
                  <!-- Form actions -->
                  <div class="row">
                      <div class="col-md-2">

                        <p class="btn btn-success save_samulation_setting" id="save_samulation_setting"  >Set Setting trigger   </p>
                        <p class="btn btn-success wait_save_samulation_setting" id="save_samulation_setting" style="display: none;" ><i class="fa fa-spinner fa-spin" style="font-size:24px"></i> </p>
                      </div>
                        <div class="col-md-2">
                         <p class="btn btn-success run_samulater_ajax" id="run_samulater_ajax"  > Show simulator Log  </p>
                        <p class="btn btn-success wait_run_samulater_ajax" id="run_samulater_ajax"  style="display: none;"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i> </p>

                      </div>

                      <div class="col-md-6">
                          <p class="btn btn-success " id="" onclick="delete_trigger_orders()" > Delete Selected Trigger all Orders</p>
                          <p class="btn btn-success " id="delete_trigger_orders_wait" style="display: none;" ><i class="fa fa-spinner fa-spin" style="font-size:24px"></i> </p>
                      </div>

                      <!-- <div class="col-md-2">
                        <label class="control-label">R </label>
                          <label class="switch">
                            <input type="checkbox" id="run_automatic">
                            <span class="slider round" ></span>
                          </label>
                      </div> -->
                    </div>
                    <div class="row">


                  </div>


                  <!-- // Form actions END -->

                </div>
                </form>
            </div>
          </div>



        <table class="table table-striped  append_table_tr2">
          <thead>
            <tr>
              <th>Date</th>
              <th>Coin </th>
              <th>Trigger Type</th>
              <th>Counter</th>
              <th>Status</th>

            </tr>
          </thead>
          <tbody>
            <tr>
            </tr>
          </tbody>
        </table>
        </div>
  </div>
</div>


<script type="text/javascript">


 $(document).on("click", ".save_samulation_setting", function(event){

      event.preventDefault();
      var select_trigger = $('.trigger_type_cls').val();

       if(select_trigger ==''){
        alert('Trigger Required Required');
        return false;
      }


      var date = $('#date').val()
      if(date ==''){
        alert('Date Required');
        return false;
      }

      //%%%%%%%%%%%%% --Get Coin -- %%%%%%%%%%%%%%
      var coin = $('#coin').val();
      if(coin ==''){
        alert('coin Required');
        return false;
      }

    if($('#cron_job_on_off').prop("checked") == true){
      var cron_job_on_off = 'on';
    }else{
      var cron_job_on_off = 'off';
    }



      $('.wait_save_samulation_setting').show();
      $('.save_samulation_setting').hide();

      $.ajax({
            url: "<?php echo SURL; ?>admin/buy_orders/save_samulation_setting",
            type: "POST",
            data: {date:date,select_trigger:select_trigger,coin:coin,cron_job_on_off:cron_job_on_off},
            success: function(response){
              $('.wait_save_samulation_setting').hide();
              $('.save_samulation_setting').show();

            }
        });

 });




  function trigger_log_print()
  {
    var trigger_type = $('.trigger_type_cls').val();

    var coin = $('#coin').val();
      if(coin ==''){
        alert('coin Required');
        return false;
    }


     $.ajax({
              url: "<?php echo SURL; ?>admin/buy_orders/trigger_log_print_ajax",
              type: "POST",
              data: {trigger_type:trigger_type,coin:coin},
              success: function(row){
                $(".append_table_tr2 tr:first").after(row);
              }
          });
  }






function run_samulater_ajax(){


    var trigger_type = $('.trigger_type_cls').val();
    var coin = $('#coin').val();

    if(trigger_type == 'barrier_trigger'){

      if(coin== ''){
        alert('Select Coin');
        return false;
      }
      var url = "<?php echo SURL; ?>admin/Barrier_trigger_simulator/run_triggers_auto_sell_and_buy";
    }else if(trigger_type == 'barrier_percentile_trigger'){
      if(coin== ''){
        alert('Select Coin');
        return false;
      }
      var url = "<?php echo SURL; ?>admin/percentile_trigger_test_simulator/hit_by_cron_job"
    }
    else if(trigger_type == 'percentile_calculation'){
      if(coin== ''){
        alert('Select Coin');
        return false;
      }
      var url = "<?php echo SURL; ?>admin/coin_meta_percentile_simulator/hit_by_cron_job"
    }else if(trigger_type == 'meta_percentile_calculation'){
      if(coin== ''){
        alert('Select Coin');
        return false;
      }
      var url = "<?php echo SURL; ?>admin/coin_meta_percentile_simulator/hit_by_cron_job"
    }
    else{
      var url = "<?php echo SURL; ?>admin/buy_orders/run_triggers_auto_sell_and_buy";
    }

    $('.wait_run_samulater_ajax').show();
    $('.run_samulater_ajax').hide();


  $.ajax({
            url: url,
            type: "POST",
            data: {trigger_type:trigger_type,coin:coin},
            success: function(response){
              trigger_log_print();
               $('.wait_run_samulater_ajax').hide();
               $('.run_samulater_ajax').show();

            }
        });
}



  $(document).on('click','.run_samulater_ajax',function(event){
      event.preventDefault();
      run_samulater_ajax();
  })






    var stop_start_simulater;

    function run_samulater_automatically(){


         var trigger_type = $('.trigger_type_cls').val();
         var coin = $('#coin').val();

         if(coin== ''){
            alert('Select Coin');
            return false;
        }


        if(trigger_type== ''){
            alert('Select Trigger');
            return false;
        }


        if(trigger_type == 'barrier_trigger'){


          var url = "<?php echo SURL; ?>admin/Barrier_trigger_simulator/run_triggers_auto_sell_and_buy";
          }else if(trigger_type == 'barrier_percentile_trigger'){
          if(coin== ''){
            alert('Select Coin');
            return false;
          }
          var url = "<?php echo SURL; ?>admin/percentile_trigger_test_simulator/hit_by_cron_job"
          }
          else if(trigger_type == 'percentile_calculation'){
      if(coin== ''){
        alert('Select Coin');
        return false;
      }
      var url = "<?php echo SURL; ?>admin/coin_meta_percentile_simulator/hit_by_cron_job"
    }
          else{
          var url = "<?php echo SURL; ?>admin/buy_orders/run_triggers_auto_sell_and_buy";
          }

        $('.wait_run_samulater_ajax').show();
        $('.run_samulater_ajax').hide();
      $.ajax({
                url: url,
                type: "POST",
                data: {trigger_type:trigger_type,coin:coin},
                success: function(response){
                  trigger_log_print();
                   $('.wait_run_samulater_ajax').hide();
                   $('.run_samulater_ajax').show();
                    stop_start_simulater = setTimeout(function(){
                    run_samulater_automatically();
                    }, 2000);
                }
            });
    }//End of



$(document).on('click','#run_automatic',function(){

    if($('#run_automatic').prop('checked')){

       run_samulater_automatically();

    }else{

      clearTimeout(stop_start_simulater);
    }
})




   function delete_trigger_orders(){

      //get selected triggers
      var select_trigger = $('.trigger_type_cls').val();

      var r = confirm("Are you sure delete "+select_trigger+" Orders!");
      if (r == true) {
            $('#delete_trigger_orders_wait').show();
            $('#delete_trigger_orders').hide();
            $.ajax({
            url: "<?php echo SURL; ?>admin/buy_orders/delete_triggers_orders",
            type: "POST",
            data: {select_trigger:select_trigger},
            success: function(response){

                $('#delete_trigger_orders_wait').hide();
                $('#delete_trigger_orders').show();
            }
        });

      }

  }



</script>