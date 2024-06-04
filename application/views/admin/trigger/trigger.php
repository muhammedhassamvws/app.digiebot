<?php
   $session_post_data = $this->session->userdata('filter_data');
?>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Category</h1>
  <div class="innerAll bg-white border-bottom">
    <ul class="menubar">
      <li><a href="<?php  echo SURL ?>admin/categories/add_new_category" >Add Category</a></li>
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

//echo "<pre>";  print_r($coins); exit;

?>
    <form class="form-horizontal margin-none" method="post" action="<?php echo SURL ?>admin/dashboard/barrier-listing" novalidate="novalidate">
      <div class="widget widget-inverse">
        <div class="col-xs-12" style="padding: 10px 10px;">
          <div class="col-md-2">
                      <div class="form-group col-md-12">
                        <label class="control-label"></label>
                        <select class="form-control" name="status" id="status">
                  <option value="">Select Status</option>
                  <?php $global_swing_parent_status = $session_post_data['status'];?>
                  <option value="very_strong_barrier" <?php if ($global_swing_parent_status == 'very_strong_barrier') {?> selected <?php }?>>Very Strong Barrier</option>
                  <option value="strong_barrier" <?php if ($global_swing_parent_status == 'strong_barrier') {?> selected <?php }?>>Strong Barrier</option>
                  <option value="weak_barrier" <?php if ($global_swing_parent_status == 'weak_barrier') {?> selected <?php }?>>Weak Barrier</option>
                </select>
                      </div>
                    </div>

          <div class="col-md-2">
            <div class="form-group col-md-12">
              <label class="control-label"></label>
              <select name="filter_coin" class="form-control">
                <option value="">Search By Coin</option>
                <?php
if (count($coins) > 0) {
	for ($i = 0; $i < count($coins); $i++) {

		$filter_coin = $session_post_data['filter_coin'];
		if ($filter_coin == $coins[$i]['symbol']) {
			$selected = "selected";
		} else {
			$selected = "";
		}

		?>
                <option value="<?php echo $coins[$i]['symbol']; ?>" <?php echo $selected; ?>><?php echo $coins[$i]['symbol']; ?></option>
                <?php }
}?>
              </select>
            </div>
          </div>

          <div class="col-md-2">
                      <div class="form-group col-md-12">
                        <label class="control-label"></label>
                        <select name="global_swing_parent_status" class="form-control">
                          <?php $global_swing_parent_status = $session_post_data['global_swing_parent_status'];?>
                          <option value="">Filter by swing point</option>
                          <option value="HL" <?php if ($global_swing_parent_status == 'HL') {?> selected <?php }?>>HL</option>
                          <option value="LH" <?php if ($global_swing_parent_status == 'LH') {?> selected <?php }?>>LH</option>
                        </select>
                      </div>
                    </div>

                     <div class="col-md-2">
                      <div class="form-group col-md-12">
                        <label class="control-label"></label>
                        <select name="breakable" class="form-control">
                          <?php $breakable = $session_post_data['breakable'];?>
                          <option value="">Filter by Barrier Breakable</option>
                          <option value="breakable" <?php if ($breakable == 'breakable') {?> selected <?php }?>>Breakable</option>
                          <option value="non_breakable" <?php if ($breakable == 'non_breakable') {?> selected <?php }?>>Non Breakable</option>
                        </select>
                      </div>
                    </div>
    <!--      <div class="col-md-2">
                      <div class="form-group col-md-12">
                        <label class="control-label"></label>
                         <?php $global_swing_parent_status = $session_post_data['start_date'];?>
                         <input type='text' class="form-control datetime_picker" name="start_date" placeholder="Search By Start Date" value="<?php echo $session_post_data['start_date']; ?>" />
                      </div>
                    </div>

                     <div class="col-md-2">
                      <div class="form-group col-md-12">
                        <label class="control-label"></label>
                        <?php $global_swing_parent_status = $session_post_data['start_date'];?>
                         <input type='text' class="form-control datetime_picker" name="end_date" placeholder="Search By End Date" value="<?php echo $session_post_data['global_swing_parent_status']; ?>" />
                      </div>
                    </div>
          -->
          <div class="col-md-2">
            <div class="form-group col-md-12">
              <label class="control-label"></label>
              <select class="form-control form-control-sm" name="barrier_type" id="barrier_type">
                <option value="">Select Barrier Type</option>
                <option value="up"> Barrier Up</option>
                <option value="down">Barrier Down</option>
              </select>
            </div>
          </div>
          <script type="text/javascript">
                        $(function () {
                            $('.datetime_picker').datetimepicker();
                        });
                    </script>
          <div class="col-md-2">
            <button class="btn btn-success" type="submit" style="margin-top: 18px;"><i class="fa fa-check-circle"></i> Search </button>
            <!--<a href="<?php echo SURL ?>admin/dashboard/barrier-listing" class="btn btn-primary btn_withIcon" style="margin-top: 18px;"> <span class="glyphicon glyphicon-refresh"></span> Reset</a>
            <input type="submit" name="clear" id="clear" value="Reset"  class="btn btn-primary btn_withIcon" style="margin-top: 18px;" /> -->

             </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </form>

    <!-- Widget -->

    <div class="widget widget-inverse"> <br />
      <div class="widget-body padding-bottom-none">
        <!-- Table -->

        <table class="table table-bordered">
          <!-- Table heading -->

          <thead>
            <tr>
              <th>Sr</th>
              <th>Coin</th>
              <th>Barrier Value</th>
              <th>Created Date</th>
              <th>Barrier Type</th>
              <th>Barrier Status</th>
              <th>Swing Point</th>
              <th>Breakable</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php

if (count($barrier_arr) > 0) {

	for ($i = 0; $i < count($barrier_arr); $i++) {

		?>
            <tr class="gradeX">
              <td><?php echo $i + 1; ?></td>
              <td><?php echo $barrier_arr[$i]['coin']; ?></td>
              <td><div class="barrier" id="bb_<?php echo $i ?>"><?php echo num($barrier_arr[$i]['barier_value']); ?></div></td>
              <td><?php

		$timezone = $this->session->userdata('timezone');

		$datetime = $barrier_arr[$i]['created_date']->toDateTime();

		$created_date = $datetime->format(DATE_RSS);

		$datetime = new DateTime($created_date);

		$datetime->format('Y-m-d g:i:s A');

		$new_timezone = new DateTimeZone($timezone);

		$datetime->setTimezone($new_timezone);

		$formated_date_time = $datetime->format('Y-m-d g:i:s A');

		echo $formated_date_time;?></td>
              <td><?php echo $barrier_arr[$i]['barrier_type']; ?></td>
              <td><?php echo $barrier_arr[$i]['barrier_status']; ?></td>
              <td><?php echo $barrier_arr[$i]['global_swing_parent_status']; ?></td>
              <td><?php echo $barrier_arr[$i]['breakable']; ?></td>
              <td class="center"><div class="btn-group btn-group-xs "> <a href="#" data-id="<?php echo $barrier_arr[$i]['_id'] ?>" class="bb btn btn-inverse"><i class="fa fa-pencil"></i></a> <a href="<?php echo SURL . 'admin/dashboard/delete_barrier/' . $barrier_arr[$i]['_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete?')"><i class="fa fa-times"></i></a>
              <?php if ($barrier_arr[$i]['status'] != 0) {?>
              <a href="<?php echo SURL . 'admin/dashboard/show_barrier/' . $barrier_arr[$i]['_id']; ?>" data-id="<?php echo $barrier_arr[$i]['_id'] ?>" class="btn btn-success"><i class="fa fa-eye"></i></a>
                              <?php }?>
              </div></td>
            </tr>
            <?php }

}?>
          </tbody>
        </table>
        <div class="page_links text-center"><?php echo $page_links ?></div>

        <!-- // Table END -->

      </div>
    </div>

    <!-- // Widget END -->

  </div>
</div>

<!-- Modal -->

<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="edit_modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Barrier</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body" id="response_edit_barrier"> ... </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="btn_upd" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->

<div class="modal fade" id="add_form_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Barrier</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">
      <form class="form-horizontal" id="add_form" method="POST" action="<?=SURL?>admin/dashboard/add_barrier_action">
        <div class="form-group">
          <label class="control-label col-sm-2" for="barrier_val">Coin:</label>
          <div class="col-sm-10">
            <select type="text" class="form-control" name="coin_type" id="barrier_type">
              <?php foreach ($coins as $key => $value) {

	echo '<option value="' . $value['symbol'] . '">' . $value['symbol'] . '</option>';

}?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="barrier_val">Barrier Type:</label>
          <div class="col-sm-10">
            <select type="text" class="form-control" name="barrier_type" id="barrier_type">
              <option value="up">Up</option>
              <option value="down">Down</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="barrier_val">Barrier Value:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="barrier_val" id="barrier_val" value="">
          </div>
        </div>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="btn_add" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

  $("body").on("click",".bb", function(e){

    var id = $(this).data("id");



    $.ajax({

      url: "<?php echo SURL ?>admin/dashboard/edit_barrier/",

      data: {id:id},

      type: "POST",

      success: function(response){

        $('#response_edit_barrier').html(response);

        $("#edit_modal").modal("show");

      }

    })

  });



   $("body").on("click","#btn_upd", function(e){

      $("#edit_form").submit();

  });



   $("body").on("click","#btn_add", function(e){

      $("#add_form").submit();

  });

</script>