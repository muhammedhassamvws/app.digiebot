

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<div class="container">
             
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>User name</th>
        <th>Old password</th>
        <th>New one</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

      <?php 
      $index = 1;
          foreach ($data as $row) {
          ?>
              <tr>
              <td><?php echo $index; ?></td>
              <td><?php echo $row['username']; ?></td>
              <td><?php echo $row['password']; ?></td>
              <td><input type="text" id="pass_<?php echo $row['id']; ?>" class="form-control" name="" value=""></td>
              <td><button value="<?php echo $row['id']; ?>" type="button" class="btn btn-success update">Update</button></td>
              </tr>
          <?php
          $index++;
          }
      ?>
      
    
    </tbody>
  </table>
</div>

<script type="text/javascript">

  $(document).ready(function(){
    $(document).on('click','.update',function(){

      if(confirm("are your sure")){

         var id = $(this).val();
         var password = $('#pass_'+id).val();
          $.ajax({
        'url': '<?php echo base_url(); ?>admin/login/update_login_pass',
        'data': {id:id,password:password},
        'type': 'POST',
        success : function(data){
          alert('updated success fully');
         }
        })



      }

    })
});
  
</script>