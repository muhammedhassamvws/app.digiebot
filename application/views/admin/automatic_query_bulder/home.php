<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style>
.glyphicon-ok {
	display: none;
}
</style>

<div class="container" style="margin-top: 4% !important;"> 
  <div class="form-group row">
    <div class="form-group col-md-6">
      <label class="control-label" for="hour">Select Collection </label>
      <br />
      <select class="form-control selectpicker" id="collection" name="" >
        <option value="" >Select Collection</option>
        <?php 
            if(isset($collections)){
              $index =1;
              foreach ($collections as $name) {
                ?>
        <option value="<?php echo $name; ?>"># <?php echo $index.'   ----> '.$name; ?></option>
        <?php
                $index++;
              }
            }

            ?>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <div class="form-group col-md-6">
      <label class="control-label" for="hour">Limit</label>
      <input type="number" name="limit" id="limit" value="10"  class="form-control">
    </div>
  </div>
  <!-- -->
  <table class="table table_cls" >
    <thead>
      <tr>
        <th>#</th>
        <th>Field name</th>
        <th>Field Type</th>
        <th>Operator</th>
        <th>Compare Value</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  <!-- -->
  <button type="button" class="btn btn-success run_query">Run Query</button>
  <button type="button" class="btn btn-success wait_run_query" style="display: none;"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></button>
</div>
<script type="text/javascript">

  $(document).ready(function(){

      $(document).on('click','.run_query',function(){
          var ids_arr = [];
          var fields_arr = [];
          var operator_id_arr = [];
          var compare_value_arr = [];
          var collection = $('#collection').val();
        $(".enable_disable_cls:checked").each(function(){
            var index_id = $(this).val();
            fields_arr.push($('#field_'+index_id).val());
            operator_id_arr.push($('#operator_id_'+index_id).val());
            compare_value_arr.push($('#compare_value_'+index_id).val());
            ids_arr.push(index_id);
        });

        alert(JSON.stringify(fields_arr));

        $('.wait_run_query').show();
        $('.run_query').hide();

          $.ajax({
            'url': '<?php echo base_url(); ?>admin/run_query_bulder/get_collection_fields',
            'data': {ids_arr:ids_arr,compare_value_arr:compare_value_arr,operator_id_arr:operator_id_arr,fields_arr:fields_arr},
            'type': 'POST',
            success : function(data){
                  $('.wait_run_query').hide();
                  $('.run_query').show();
            }
          })
        
          
      })//End of 

    
  



  $(document).on('change','#collection',function(){
         var collection_name  = $(this).val();

         if(!collection_name){
          return false;
         }

          $.ajax({
            'url': '<?php echo base_url(); ?>admin/Automatic_query_bulder/get_collection_fields',
            'data': {collection_name:collection_name},
            'type': 'POST',
            success : function(data){
              $(".table_cls tbody").empty().append(data); 
            }
          })
    })
});
  
</script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> 
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script> 
<script>
$('.selectpicker').selectpicker({
  liveSearch: true, 
  showTick: true, 
  width: 'auto'
});

</script> 
