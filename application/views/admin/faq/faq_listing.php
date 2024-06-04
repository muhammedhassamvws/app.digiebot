<div id="content">
  <h1 class="content-heading bg-white border-bottom">Users</h1>
  <div class="innerAll bg-white border-bottom">
  <ul class="menubar">
    <li class="active"><a href="<?php echo SURL; ?>admin/users">Users</a></li>
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

    <!-- Widget -->
    <div class="widget widget-inverse">

      <div class="widget-body padding-bottom-none">
        <!-- Table -->
         <div class="loaderImage">
        <div class="loaderimagbox" style="display:none;"><img src="<?php echo SURL ?>assets/images/loader.gif" /></div>
        <table class="table table-bordered ">

          <!-- Table heading -->
          <thead>
            <tr>
              <th>Sr</th>
              <th>Question</th>
              <th>Answer</th>
              <th>Type</th>
              <th>Action</th>

            </tr>
          </thead>

          <tbody>
           <?php
if (count($faq) > 0) {
    for ($i = 0; $i < count($faq); $i++) {
        ?>
           <tr class="gradeX">
              <td><?php echo $i + 1; ?></td>
              <td><?php echo $faq[$i]['faq_question']; ?></td>
              <td><?php echo $faq[$i]['faq_answer']; ?></td>
              <td><?php echo $faq[$i]['faq_type']; ?></td>
              <td><a href="<?=SURL;?>admin/faq_admin/edit-faq/<?=$faq[$i]['_id'];?>" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?=SURL;?>admin/faq_admin/delete-faq/<?=$faq[$i]['_id'];?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a></td>
           </tr>
           <?php }
}?>
          </tbody>

        </table>
        </div>
        <!-- // Table END -->


        <?php echo $pagination; ?>

      </div>
    </div>
    <!-- // Widget END -->

  </div>
</div>