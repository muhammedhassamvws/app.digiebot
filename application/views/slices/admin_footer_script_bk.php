<!-- Global --> 
<?php $page_url = $this->uri->segment(3); ?>
<script>
	var basePath = '',
		commonPath = '<?php echo ASSETS;?>',
		rootPath = '',
		DEV = false,
		componentsPath = '<?php echo ASSETS;?>components/';
	
	var primaryColor = '#cb4040',
		dangerColor = '#b55151',
		infoColor = '#466baf',
		successColor = '#8baf46',
		warningColor = '#ab7a4b',
		inverseColor = '#45484d';
	
	var themerPrimaryColor = primaryColor;
</script> 
<script src="<?php echo ASSETS;?>components/library/bootstrap/js/bootstrap.min.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/plugins/nicescroll/jquery.nicescroll.min.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/plugins/breakpoints/breakpoints.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/core/js/animations.init.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/charts/flot/assets/lib/jquery.flot.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/charts/flot/assets/lib/jquery.flot.resize.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/charts/flot/assets/lib/plugins/jquery.flot.tooltip.min.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/charts/flot/assets/custom/js/flotcharts.common.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/charts/flot/assets/custom/js/flotchart-simple.init.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/charts/flot/assets/custom/js/flotchart-simple-bars.init.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/widgets/widget-chat/assets/js/widget-chat.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/plugins/slimscroll/jquery.slimscroll.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/forms/elements/bootstrap-datepicker/assets/lib/js/bootstrap-datepicker.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/forms/elements/bootstrap-datepicker/assets/custom/js/bootstrap-datepicker.init.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/charts/easy-pie/assets/lib/js/jquery.easy-pie-chart.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/charts/easy-pie/assets/custom/easy-pie.init.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/modules/admin/widgets/widget-scrollable/assets/js/widget-scrollable.init.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/plugins/holder/holder.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/core/js/sidebar.main.init.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/core/js/sidebar.collapse.init.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/helpers/themer/assets/plugins/cookie/jquery.cookie.js?v=v1.2.3"></script> 
<script src="<?php echo ASSETS;?>components/core/js/core.init.js?v=v1.2.3"></script>

<!-- Form Validation -->
<script src="<?php echo ASSETS;?>components/modules/admin/forms/validator/assets/lib/jquery-validation/dist/jquery.validate.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/modules/admin/forms/validator/assets/custom/form-validator.init.js?v=v1.2.3"></script>
<script type="text/javascript" src="<?php echo ASSETS;?>js/jquery.validate.js"></script>
<!-- End Form Validation -->

<script src="<?php echo ASSETS;?>components/modules/admin/forms/wizards/assets/lib/jquery.bootstrap.wizard.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/modules/admin/forms/wizards/assets/custom/js/form-wizards.init.js?v=v1.2.3"></script>

<script src="<?php echo ASSETS;?>components/modules/admin/forms/elements/fuelux-checkbox/fuelux-checkbox.js?v=v1.2.3"></script>


<script src="<?php echo ASSETS;?>components/modules/admin/tables/datatables/assets/lib/js/jquery.dataTables.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/modules/admin/tables/datatables/assets/lib/extras/TableTools/media/js/TableTools.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/modules/admin/tables/datatables/assets/lib/extras/ColVis/media/js/ColVis.min.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/modules/admin/tables/datatables/assets/custom/js/DT_bootstrap.js?v=v1.2.3"></script>
<script src="<?php echo ASSETS;?>components/modules/admin/tables/datatables/assets/custom/js/datatables.init.js?v=v1.2.3"></script>




<script src="<?php echo ASSETS;?>toastr/toastr.js"></script>
<script type="text/javascript">
  function _toastr(_message,_position,_notifyType,_onclick) {

      /** JAVSCRIPT / ON LOAD
       ************************* **/
      if(_message != false) {

          if(_onclick != false) {
            onclick = function() {
              window.location = _onclick;
            }
          } else {
            onclick = null
          }

          toastr.options = {
            "closeButton":      true,
            "debug":        false,
            "newestOnTop":      false,
            "progressBar":      true,
            "positionClass":    "toast-" + _position,
            "preventDuplicates":  false,
            "onclick":        onclick,
            "showDuration":     "300",
            "hideDuration":     "1000",
            "timeOut":        "8000",
            "extendedTimeOut":    "1000",
            "showEasing":       "swing",
            "hideEasing":       "linear",
            "showMethod":       "fadeIn",
            "hideMethod":       "fadeOut"
          }

          setTimeout(function(){
            toastr[_notifyType](_message);
          }, 1000); // delay 1s
      }

  }

  

  function autoload_notifications(){
    
      $.ajax({
        type:'POST',
        url:'<?php echo SURL?>admin/dashboard/autoload_notifications',
        data: "",
        success:function(response){
         
            if(response !=""){
               _toastr(response,"top-right","success",false);
            }

            setTimeout(function() {
                  autoload_notifications();
            }, 2000);
         
        }
      });

    }//end autoload_notifications() 

    autoload_notifications();

</script>      





<!-- DropZone -->
<script src="<?php echo ASSETS?>dropzone/dropzone.js"></script>

<!-- Jquery Confirm -->
<script src="<?php echo ASSETS;?>jquery_confirm/jquery-confirm.min.js"></script>


<script type="text/javascript">

  $("body").on("change","#profit_type",function(e){

    var profit_type = $(this).val();
    $("#sell_profit_price").val('');

    if(profit_type == 'percentage'){
       $('#sell_profit_percent_div').show();
       $('#sell_profit_price_div').hide();
    }else{
      $('#sell_profit_price_div').show();
      $('#sell_profit_percent_div').hide();
    }
    
  });


  $("body").on("change","#main_symbol",function(e){

    var symbol = $(this).val();

    $.ajax({
		'url': '<?php echo SURL ?>admin/dashboard/set_currency',
		'type': 'POST', //the way you want to send data to your URL
		'data': {symbol: symbol},
		'success': function (response) { //probably this request will return anything, it'll be put in var "data"
			
			location.reload();
		}
	});
   
  });


  $("body").on("keyup","#sell_profit_percent",function(e){

    var sell_profit_percent = $(this).val();
    var purchased_price = $("#purchased_price").val();

    $.ajax({
		'url': '<?php echo SURL ?>admin/dashboard/convert_price',
		'type': 'POST', //the way you want to send data to your URL
		'data': {purchased_price:purchased_price,sell_profit_percent: sell_profit_percent},
		'success': function (response) { //probably this request will return anything, it'll be put in var "data"
			
			$("#sell_profit_price").val(response);
			$('#sell_profit_price_div').show();
		}
	});
   
  });


</script>
<script type="text/javascript">

  setTimeout(function() {
   autoload_balance();
  }, 60000);

  function autoload_balance()
  {
    $.ajax({
      url: '<?php echo SURL ?>admin/coin_balance',
      type: 'POST',
      data: "",
      success: function (response) {
      }
    });

  }//end autoload_balance


  // setTimeout(function() {
  //  autoload_check_lasts_order();
  // }, 70000);

  // function autoload_check_lasts_order()
  // {
  //   $.ajax({
  //     url: '<?php echo SURL ?>admin/check_last_balance',
  //     type: 'POST',
  //     data: "",
  //     success: function (response) {
  //     }
  //   });

  // }//end autoload_check_lasts_order

</script>
<script type="text/javascript">
  $(document).ready(function()
{ 
  var leftmenu  = "<?php echo $this->session->userdata('leftmenu');?>";
  if(leftmenu == 0)
  {
    $('#user_leftmenu_body').addClass('sidebar-mini');
  }
  else
  {
    $('#user_leftmenu_body').removeClass('sidebar-mini');
  }
})


$('#user_leftmenu_setting').on('click', function(e)
{
  var leftmenu  = "<?php echo $this->session->userdata('leftmenu');?>";
  
  if(leftmenu == 0)
  {
    var leftmenu  = 1;
  }
  else
  {
    var leftmenu  = 0;
  }
  $.ajax({
      'url': '<?php echo SURL ?>admin/settings/user_leftmenu_setting',
      'type': 'POST', //the way you want to send data to your URL
      'data': {leftmenu: leftmenu},
      'success': function (data) { //probably this request will return anything, it'll be put in var "data"
        console.log(data);
        return;
      }
    });
  
  
  
});
</script>