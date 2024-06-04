<script type="text/javascript" src="<?php echo NEW_ASSETS; ?>js/popper.min.js"></script>
<!-- <script type="text/javascript" src="<?php echo NEW_ASSETS; ?>bootstrap/js/bootstrap.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/feather-icons"></script>

<script src="<?php echo ASSETS; ?>jquery_confirm/jquery-confirm.min.js"></script> 


<script>
$(document).ready(function(e) {

    $("body").on("click",".s-dropdown-me + .dropdown-menu .dropdown-item",function(){
		var sdm_val = $(this).text();
		var symbol = $(this).attr("value");
		$(this).closest(".dropdown").find(".dropdown-toggle").html(sdm_val);

		$.ajax({
  		'url': '<?php echo SURL ?>admin/dashboard/set_currency',
  		'type': 'POST', //the way you want to send data to your URL
  		'data': {symbol: symbol},
  		'success': function (response) { //probably this request will return anything, it'll be put in var "data"

  			location.reload();
  		}

  		});
	});




	$("body").on("click",".check_live_test",function(e){
		var mode = '';
		//var mode_e = $(this).val();

		if($(this).is(":checked")){
			$(this).closest(".livechecbox").find("label").html("Live");
			mode = 'live';
		}else{
			$(this).closest(".livechecbox").find("label").html("Test");
			mode = 'test';
		}

		$.ajax({
		'url': '<?php echo SURL ?>admin/dashboard/set_application_mode',
		'type': 'POST', //the way you want to send data to your URL
		'data': {mode: mode},
		'success': function (response) { //probably this request will return anything, it'll be put in var "data"

			location.reload();
			}
		});

	});
});
</script>
<script src="<?php echo NEW_ASSETS; ?>js/custom.js"></script>
<script>
	feather.replace();
</script>