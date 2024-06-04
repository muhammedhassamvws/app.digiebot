<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
<style type="text/css">
  .radio-group label {
   overflow: hidden;
} .radio-group input {
    /* This is on purpose for accessibility. Using display: hidden is evil.
    This makes things keyboard friendly right out tha box! */
   height: 1px;
   width: 1px;
   position: absolute;
   top: -20px;
} .radio-group .not-active  {
   color: #3276b1;
   background-color: #fff;
}
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Settings</h1>
  <div class="bg-white innerAll border-bottom">
   <ul class="menubar">
      <li><a href="<?php echo SURL; ?>/admin/settings">Settings</a></li>
      <li><a href="<?php echo SURL; ?>admin/settings/enable_google_auth">Google Authentication</a></li>
      <li><a href="<?php echo SURL; ?>admin/settings/password_change">Change Password</a></li>
      <?php if ($this->session->userdata('user_role') == 1 || $this->session->userdata('admin_id') == 1) {
	?>
         <li><a href="<?php echo SURL; ?>admin/settings/update_candle">Update Candle</a></li>
         <li><a href="<?php echo SURL; ?>admin/candle_base">Base Candle Settings</a></li>
         <li><a href="<?php echo SURL; ?>admin/buy_orders/buy_sell_trigger_log">Buy Order Trigger</a></li>
         <li class="active"><a href="<?php echo SURL; ?>admin/settings/trigger_setting">Trigger Setting</a></li>
         <li><a href="<?php echo SURL; ?>admin/settings/triggers_global_setting">Trigger_3 Setting</a></li>

         <li><a href="<?php echo SURL; ?>admin/settings/delete_orders">Delet Orders Setting</a></li>
        <?php
}
?>



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
      <!-- // Widget END --><!--
        <input type = "hidden" value = "<?php echo $admin_id; ?>" id="user_id" name = "admin_id"> -->
    <!--http://vizzweb.com/projects/crypto_trading/admin/settings/enable_google_auth/-->
    <div class="widget widget-inverse">
        <div class="widget-body bg-white">



                  <div class="col-md-12">
                  <div class="form-group col-md-12">
                    <label class="control-label" for="hour">Select Trigger </label>
                    <select class="form-control triggers_type_change" name="triggers_type_change">
                       <option value="">select trigger</option>
                      <!--  <option value="trigger_1">trigger_1</option> -->
                       <option value="trigger_2">trigger_2</option>
                       <option value="box_trigger_3">box_trigger_3</option>
                       <option value="rg_15">box_rg_15</option>
                        <option value="barrier_trigger">barrier_trigger</option>
                       
                    </select>
                  </div>
                </div>

                <div class="show_detail">
                </div>
                <input type="hidden" name="trigger_type" id="trigger_type_id" value="">



            <div class="widget-body">







             <form action="<?php echo SURL; ?>admin/settings/add_trigger_settings_process" class="form-horizontal" id="" method="post" >

              <div class="row">
                <input type = "hidden" value = "<?php echo $admin_id; ?>" name = "admin_id">





                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label" for="coin">Select Coin</label>
                        <select class="form-control" name="coins" id="coin" >
                          <?php
                          foreach ($coins as $coin) {
                          ?>
                          <option value="<?php echo $coin['symbol'] ?>"><?php echo $coin['symbol']; ?></option>
                          <?php }
                          ?>
                        </select>
                      </div>
                    </div>


                    <div class="col-md-12">
                      <div class="form-group col-md-12">
                        <label class="control-label" for="hour">Select Trigger </label>


                        <select class="form-control triggers_type" name="triggers_type">
                           <option value="">select trigger</option>
                           <option value="trigger_1">trigger_1</option>
                           <option value="trigger_2">trigger_2</option>
                           <option value="box_trigger_3">box_trigger_3</option>
                           <option value="rg_15">box_rg_15</option>
                          <option value="barrier_trigger">barrier_trigger</option>
                        </select>

                      </div>
                    </div>


                    <div class="form-group col-md-12">
                        <label class="control-label" for="hour">Select Order Mode </label>
                        <select class="form-control order_mode" name="order_mode">
                             <option value="live">(Real time and test live)</option>
                             <option value="test">Simulator Test</option>
                        </select>
                      </div>

                  <div class="all_trigger_except_barrier_trigger">
                      <!--  Trigger_1 parts-->

                    <div class="trigger_1_cls" style="display: none;">
                      <div class="col-md-12">
                        <div class="form-group col-md-12">
                          <label class="control-label" for="hour">buy part_1 price % </label>
                          <input class="form-control " id="buy_part_1_price_percent" name="buy_part_1_price_percent" type="text"  />
                        </div>
                      </div>


                      <div class="col-md-12">
                        <div class="form-group col-md-12">
                          <label class="control-label" for="hour">buy part_2 price %  </label>
                          <input class="form-control " id="buy_part_2_price_percent" name="buy_part_2_price_percent" type="text"  />
                        </div>
                      </div>



                      <div class="col-md-12">
                        <div class="form-group col-md-12">
                          <label class="control-label" for="hour"> buy part_3 price % </label>
                          <input class="form-control " id="buy_part_3_price_percent" name="buy_part_3_price_percent" type="text"  />
                        </div>
                      </div>



                      <div class="col-md-12">
                        <div class="form-group col-md-12">
                          <label class="control-label" for="hour">sell part_1 price % </label>
                          <input class="form-control " id="sell_part_1_price_percent" name="sell_part_1_price_percent" type="text"  />
                        </div>
                      </div>


                      <div class="col-md-12">
                        <div class="form-group col-md-12">
                          <label class="control-label" for="hour">sell part_2 price %  </label>
                          <input class="form-control " id="sell_part_2_price_percent" name="sell_part_2_price_percent" type="text"  />
                        </div>
                      </div>



                      <div class="col-md-12">
                        <div class="form-group col-md-12">
                          <label class="control-label" for="hour">sell part_3 price % </label>
                          <input class="form-control " id="sell_part_3_price_percent" name="sell_part_3_price_percent" type="text"  />
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group col-md-12">
                          <label class="control-label" for="hour"> Initail trail stop  % </label>
                          <input class="form-control " id="Initail_trail_stop_trigger_1" name="Initail_trail_stop_trigger_1" type="text" />
                        </div>
                      </div>
                     </div>
                  <!--  End  Trigger_1 -->



                    <!--  Trigger_2 parts-->

                    <div class="trigger_2_cls" style="display: none;">
                        <div class="col-md-12">
                          <div class="form-group col-md-12">
                            <label class="control-label" for="hour">buy price % </label>
                            <input class="form-control " id="buy_price_percent" name="buy_price" type="text"  />
                          </div>
                        </div>


                        <div class="col-md-12">
                          <div class="form-group col-md-12">
                            <label class="control-label" for="hour">sell price % </label>
                            <input class="form-control " id="sell_price_percent" name="sell_price" type="text"  />
                          </div>
                        </div>



                        <div class="col-md-12">
                          <div class="form-group col-md-12">
                            <label class="control-label" for="hour">Stop loss percentage % </label>
                            <input class="form-control " id="stop_loss_price_percent" name="stop_loss" type="text"  />
                          </div>
                        </div>
                    </div>
                  <!--  End  -->

                </div><!-- End of all trigger excepth barrier rigger -->


                <!--Barrier Trigger -->
                   <div class="barrier_trigger_show_hide" style="display: none;">
                        <div class="col-md-12">
                          <div class="form-group col-md-12">
                            <label class="control-label" for="hour">buy price % </label>
                            <input class="form-control " id="buy_price_percent_barrier_trigger" name="buy_price_percent_barrier_trigger" type="number"  />
                          </div>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group col-md-12">
                            <label class="control-label" for="hour">Stop loss percentage % </label>
                            <input class="form-control " id="stop_loss_price_percent_barrier_trigger" name="stop_loss_price_percent_barrier_trigger" type="number"  />
                          </div>
                        </div>


                        <div class="col-md-12">
                          <div class="form-group col-md-12">
                            <label class="control-label" for="hour">Compare Quantity </label>
                            <input class="form-control " id="barrier_trigger_quantity" name="barrier_trigger_quantity" type="number"  />
                          </div>
                        </div>
                    </div>
                <!-- End of barrier Trigger -->

                






                  <input type="hidden" name="trigger_setting_id"  id="trigger_setting_id">

          <hr class="separator" />

          <!-- Form actions -->
          <div class="form-actions">
            <button type="submit" id="clint_info_btn" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
            <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancel</button>
          </div>
          <!-- // Form actions END -->
          </form>
        </div>
        </div>
      </div>
  </div>

</div>


<script type="text/javascript">

  $(document).on('change','.order_mode',function(){
      $(".triggers_type option[value='']").attr('selected', true)
      $('#trigger_setting_id').val('');
      $('#buy_price_percent').val('');
      $('#sell_price_percent').val('');
      $('#stop_loss_price_percent').val('');
      $('#buy_part_1_price_percent').val('');
      $('#buy_part_2_price_percent').val('');
      $('#buy_part_3_price_percent').val('');
      $('#sell_part_1_price_percent').val('');
      $('#sell_part_2_price_percent').val('');
      $('#sell_part_3_price_percent').val('');
      $('#Initail_trail_stop_trigger_1').val('');

      $('#buy_price_percent_barrier_trigger').val('');
      $('#stop_loss_price_percent_barrier_trigger').val('');
      $('#barrier_trigger_quantity').val('');

  })//End of 


  $(document).on('change','.triggers_type',function(){

      var triggers_type = $(this).val();


      if(triggers_type =='barrier_trigger'){
        $('.barrier_trigger_show_hide').show();
        $('.all_trigger_except_barrier_trigger').hide();
      }else{
         $('.barrier_trigger_show_hide').hide();
         $('.all_trigger_except_barrier_trigger').show();
      }

      var order_mode   = $('.order_mode').val();
      var coin = $('#coin').val();
      $('#trigger_setting_id').val('');
      $('#buy_price_percent').val('');
      $('#sell_price_percent').val('');
      $('#stop_loss_price_percent').val('');
      $('#buy_part_1_price_percent').val('');
      $('#buy_part_2_price_percent').val('');
      $('#buy_part_3_price_percent').val('');
      $('#sell_part_1_price_percent').val('');
      $('#sell_part_2_price_percent').val('');
      $('#sell_part_3_price_percent').val('');
      $('#Initail_trail_stop_trigger_1').val('');
      $.ajax({
        'url': '<?php echo base_url(); ?>admin/settings/get_coin_setting_ajax',
        'data': {triggers_type:triggers_type,coin:coin,order_mode:order_mode},
        'type': 'POST',
        success : function(data){
            var res_obj =  JSON.parse(data);

            $('#trigger_setting_id').val(res_obj._id);
            if( (res_obj.triggers_type =='trigger_2') || (res_obj.triggers_type == 'box_trigger_3') || (res_obj.triggers_type == 'rg_15')){
                $('#buy_price_percent').val(res_obj.buy_price);
                $('#sell_price_percent').val(res_obj.sell_price);
                $('#stop_loss_price_percent').val(res_obj.stop_loss);
            }else if(res_obj.triggers_type =='trigger_1'){

              $('#buy_part_1_price_percent').val(res_obj.buy_part_1_price_percent);
              $('#buy_part_2_price_percent').val(res_obj.buy_part_2_price_percent);
              $('#buy_part_3_price_percent').val(res_obj.buy_part_3_price_percent);
              $('#sell_part_1_price_percent').val(res_obj.sell_part_1_price_percent);
              $('#sell_part_2_price_percent').val(res_obj.sell_part_2_price_percent);
              $('#sell_part_3_price_percent').val(res_obj.sell_part_3_price_percent);
              $('#Initail_trail_stop_trigger_1').val(res_obj.Initail_trail_stop_trigger_1);
            }else if(res_obj.triggers_type =='barrier_trigger'){

              $('#buy_price_percent_barrier_trigger').val(res_obj.buy_price_percent_barrier_trigger);
              $('#stop_loss_price_percent_barrier_trigger').val(res_obj.stop_loss_price_percent_barrier_trigger);
              $('#barrier_trigger_quantity').val(res_obj.barrier_trigger_quantity);

            }



        }
      })





      if((triggers_type == 'trigger_2') || (triggers_type == 'box_trigger_3') || (triggers_type == 'rg_15')){

        $('.trigger_2_cls').show();
        $('.trigger_1_cls').hide();
      }

      if(triggers_type == 'trigger_1'){

          $('.trigger_2_cls').hide();
          $('.trigger_1_cls').show();
      }


      if(triggers_type == ''){

          $('.trigger_2_cls').hide();
          $('.trigger_1_cls').hide();
      }
  })

    $(document).ready(function(){
      $(".nav-tabs a").click(function(){
        $(this).tab('show');
      });

      $(document).on('change','.triggers_type_change',function(){
        var trigger_type = $(this).val();

         $('#trigger_type_id').val(trigger_type);

      $.ajax({
        'url': '<?php echo base_url(); ?>admin/settings/get_coin_trigger_setting',
        'data': {trigger_type:trigger_type},
        'type': 'POST',
        success : function(data){
          jQuery(".show_detail").empty().append(data);
        }
       })

      })

      $(document).on('click','.upd_cls',function(){
        var live_id = $(this).attr('live_att');
        var test_id = $(this).attr('test_att');

        var trigger_type = $('#trigger_type_id').val();
        var coin = $(this).attr('coin_att');
      

        if(trigger_type == 'barrier_trigger'){

          if(live_id !=''){
            var buy_price_percent_barrier_trigger = $('#b_sell'+live_id).val();
            var stop_loss_price_percent_barrier_trigger = $('#b_s_l'+live_id).val();
            var barrier_trigger_quantity = $('#b_quantity'+live_id).val();

              $.ajax({
              'url': '<?php echo base_url(); ?>admin/settings/update_coin_trigger_setting_barrier_trigger',
              'data': {buy_price_percent_barrier_trigger:buy_price_percent_barrier_trigger,stop_loss_price_percent_barrier_trigger:stop_loss_price_percent_barrier_trigger,barrier_trigger_quantity:barrier_trigger_quantity,live_id:live_id},
              'type': 'POST',
              success : function(data){
                 alert('updated');
               }
              })
           
          }

        }else{

          var l_buy = $('#l_buy'+coin).val();
          var l_sell = $('#l_sell'+coin).val();
          var l_s_l = $('#l_s_l'+coin).val();

          var t_buy = $('#t_buy'+coin).val();
          var t_sell = $('#t_sell'+coin).val();
          var t_s_l = $('#t_s_l'+coin).val();

        

        
          $.ajax({
            'url': '<?php echo base_url(); ?>admin/settings/update_coin_trigger_setting',
            'data': {l_buy:l_buy,l_sell:l_sell,l_s_l:l_s_l,t_buy:t_buy,t_sell:t_sell,t_s_l:t_s_l,live_id:live_id,test_id:test_id,trigger_type:trigger_type,coin:coin},
            'type': 'POST',
            success : function(data){
            alert('updated');
            }
          })


        }
        

        // alert('l_buy '+l_buy+'  l_sell'+l_sell+'  l_s_l'+l_s_l);
        // alert('t_buy '+t_buy+'  t_sell'+t_sell+'  t_s_l'+t_s_l);
      
      })

    });




</script>