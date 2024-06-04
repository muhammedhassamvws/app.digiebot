<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
.Input_text_s {
    /* display: inline; */
    position: relative;
}

.Input_text_s i {
    position: absolute;
    top: 33px;
    right: 10px;
}


table.mycustom_table tr:nth-child(2n) td {
    border-top: medium none;
}

table.mycustom_table tr td {
    border-top: 20px solid #eee;
    padding-bottom: 20px;
    padding-top: 20px;
    background: #fff;
    padding-left: 20px;
}

.our-team {
  padding: 30px 0 40px;
  margin-bottom: 30px;
  background-color: #f7f5ec;
  text-align: center;
  overflow: hidden;
  position: relative;
}

.our-team .picture {
  display: inline-block;
  height: 230px;
  width: 230px;
  margin-bottom: 50px;
  z-index: 1;
  position: relative;
}

.our-team .picture::before {
  content: "";
  width: 100%;
  height: 0;
  border-radius: 50%;
  background-color: #1369ce;
  position: absolute;
  bottom: 135%;
  right: 0;
  left: 0;
  opacity: 0.9;
  transform: scale(3);
  transition: all 0.3s linear 0s;
}

.our-team:hover .picture::before {
  height: 100%;
}

.our-team .picture::after {
  content: "";
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background-color: #1369ce;
  position: absolute;
  top: 0;
  left: 0;
  z-index: -1;
}

.our-team .picture img {
  width: 100%;
  height: auto;
  border-radius: 50%;
  transform: scale(1);
  transition: all 0.9s ease 0s;
}

.our-team:hover .picture img {
  box-shadow: 0 0 0 14px #f7f5ec;
  transform: scale(0.7);
}

.our-team .title {
  display: block;
  font-size: 15px;
  color: #4e5052;
  text-transform: capitalize;
}

.our-team .social {
  width: 100%;
  padding: 0;
  margin: 0;
  background-color: #1369ce;
  position: absolute;
  bottom: -100px;
  left: 0;
  transition: all 0.5s ease 0s;
}

.our-team:hover .social {
  bottom: 0;
}

.our-team .social li {
  display: inline-block;
}

.our-team .social li a {
  display: block;
  padding: 10px;
  font-size: 17px;
  color: white;
  transition: all 0.3s ease 0s;
  text-decoration: none;
}

.our-team .social li a:hover {
  color: #1369ce;
  background-color: #f7f5ec;
}

/*** custom checkboxes ***/
@import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);
input[type=checkbox] { display:none; } /* to hide the checkbox itself */
input[type=checkbox] + label:before {
  font-family: FontAwesome;
  display: inline-block;
}
.custom_label {
    font-size: 25px;
    width: 100%;
    text-align: center;
}
input[type=checkbox] + label:before { content: "\f096"; } /* unchecked icon */
input[type=checkbox] + label:before { letter-spacing: 10px; } /* space between checkbox and label */

input[type=checkbox]:checked + label:before { content: "\f046"; } /* checked icon */
input[type=checkbox]:checked + label:before { letter-spacing: 5px; } /* allow space for check mark */

</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Reports</h1>
  <div class="innerAll bg-white border-bottom">
  <ul class="menubar">
    <li class=""><a href="<?php echo SURL; ?>admin/reports">Reports</a></li>
    <li class="active"><a href="#">Custom Report</a></li>
	</ul>
  </div>
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
      <?php //$filter_user_data = $this->session->userdata('filter_order_data'); ?>
      <div class="widget widget-inverse">
         <div class="widget-body">
            <?php if (isset($_GET['testing'])) {$testing = '?testing=true';} else { $testing = "";}?>
            <form method="POST" action="<?php echo SURL; ?>admin/reports/get_user_order_history<?php echo $testing; ?>">
              <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter Coin: </label>
                     <select id="filter_by_coin" name="filter_by_coin" type="text" class="form-control filter_by_name_margin_bottom_sm">
                        <option value ="" <?=(($filter_user_data['filter_by_coin'] == "") ? "selected" : "")?>>Search By Coin Symbol</option>
                        <?php
for ($i = 0; $i < count($coins); $i++) {
    $selected = ($coins[$i]['symbol'] == $filter_user_data['filter_by_coin']) ? "selected" : "";
    echo "<option value='" . $coins[$i]['symbol'] . "' $selected>" . $coins[$i]['symbol'] . "</option>";
}
?>
                     </select>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-3" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter Username: </label>
                     <input type="text" class="form-control" name="filter_username" id="username" value="<?=(!empty($filter_user_data['filter_username']) ? $filter_user_data['filter_username'] : "")?>">
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-12" style="padding-bottom: 6px;">
                  <div class="Input_text_btn">
                     <label></label>
                     <button id="submit-form" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
                     <a href="<?php echo SURL; ?>admin/reports/reset_filters_report/all" class="btn btn-danger"><i class="fa fa-times-circle"></i>Reset</a>
                  </div>
               </div>
            </div>
            </form>
          </div>
      </div>
    <!-- Widget -->
    <div class="widget widget-inverse" style="background: #eee;">

      <div class="widget-body padding-bottom-none">
        <!-- Table -->
        <table class="table table-responsive mycustom_table">
            <thead>
                <tr>
                  <th>Digiebot Orders</th>
                  <th>Digiebot Status</th>
                  <th>Binance Orders</th>
                  <th>Status</th>
                </tr>
            </thead>
            <tbody>
              <?php
if (isset($resp)) {
    foreach ($resp as $index) {?>
                <tr>
                  <td><a href="<?=$index['buy']['url']?>"><?=$index['buy']['id']?></a><span class="label label-info label-stroke" style="margin-left: 10px;"><?=$index['buy']['qty']?></span><span class="label label-success label-stroke" style="margin-left: 10px;"><?=$index['buy']['type']?></span><span class="label label-info label-stroke" style="margin-left: 10px;"><?=$index['buy']['dtime']?></span></td>
                  <td><?=$index['buy']['order_status']?><span class="label label-stroke label-default"><?=$index['buy']['price'];?></span></td>
                  <td><?=$index['buy']['bid']?><span class="label label-info label-stroke" style="margin-left: 10px;"><?=$index['buy']['bqty']?></span><span class="label label-info label-stroke" style="margin-left: 10px;"><?=$index['buy']['btime']?></span></td>
                  <td><?=$index['buy']['status']?></td>
                </tr>
                <tr>
                  <td><a href="<?=$index['sell']['url']?>"><?=$index['sell']['id']?></a><span class="label label-info label-stroke" style="margin-left: 10px;"><?=$index['sell']['qty']?></span><span class="label label-primary label-stroke" style="margin-left: 10px;"><?=$index['sell']['type']?></span><span class="label label-info label-stroke" style="margin-left: 10px;"><?=$index['sell']['dtime']?></span></td>
                  <td><?=$index['sell']['order_status']?><span class="label label-stroke label-default"><?=$index['sell']['price'];?></span></td>
                  <td><?=$index['sell']['bid']?><span class="label label-info label-stroke" style="margin-left: 10px;"><?=$index['sell']['bqty']?></span><span class="label label-info label-stroke" style="margin-left: 10px;"><?=$index['sell']['btime']?></span></td>
                  <td><?=$index['sell']['status']?></td>
                </tr>
                    <?php }
}
?>
            </tbody>
        </table>
        <!-- // Table END -->
      </div>
    </div>
    <!-- // Widget END -->

  </div>
</div>

<!-- The modal -->
<div class="modal" id="largeShoes" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
   <div class="modal-dialog modal-sm">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="modalLabelLarge">User Information</h4>
         </div>
         <div class="modal-body" id="mymodalresp">
            Modal content...
         </div>
      </div>
   </div>
</div>
<!-- Start Model -->
<div class="modal fade in" id="modal-order_details" aria-hidden="false">

    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal heading -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Order Details</h3>
            </div>
            <!-- // Modal heading END -->

            <!-- Modal body -->
            <div class="modal-body">
                <div class="innerAll">
                    <div class="innerLR" id="response_order_details">
                    </div>
                </div>
            </div>
            <!-- // Modal body END -->

        </div>
    </div>

</div>
<!-- End Model -->
<script>
$("body").on("click",".glassflter",function(e){
    var query = $("#filter_by_name").val();
    window.location.href = "<?php echo SURL; ?>/admin/users/?query="+query;
});

$("body").on("click",".viewadmininfo",function(e){
    var user_id = $(this).attr('id');
    $.ajax({
      url: "<?php echo SURL; ?>admin/reports/get_user_info",
      data: {user_id:user_id},
      type: "POST",
      success: function(response){
          $("#mymodalresp").html(response);
      }
    });
});

$("body").on("click",".view_order_details",function(e){

      var order_id = $(this).attr("data-id");

       $.ajax({
          'url': '<?php echo SURL ?>admin/dashboard/get_buy_order_details_ajax',
          'type': 'POST',
          'data': {order_id:order_id},
          'success': function (response) {

              $('#response_order_details').html(response);
              $("#modal-order_details").modal('show');
          }
      });

});
</script>
<script>
  $( function() {
    availableTags = [];
    $.ajax({
       'url': '<?php echo SURL ?>admin/reports/get_all_usernames_ajax',
       'type': 'POST',
       'data': "",
       'success': function (response) {
          availableTags = JSON.parse(response);

          $( "#username" ).autocomplete({
            source: availableTags
          });
       }
   });

  });
  </script>
