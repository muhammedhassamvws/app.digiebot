<div id="content">
  <h1 class="content-heading bg-white border-bottom">coins</h1>
  <div class="innerAll bg-white border-bottom">
  <ul class="menubar">
  <li class="active"><a href="<?php echo SURL; ?>/admin/coins">Coins</a></li>
  </ul>
  </div>
  <div class="innerAll spacing-x2">
    <!-- Filter Form -->
    <form action="<?php echo SURL; ?>/admin/coins" method="post" id="exchangeForm">
      <label for="exchange-filter">Filter by Exchange:</label>
      <input type="radio" id="binance" name="exchange" value="binance" <?php echo ($exchange === 'binance') ? 'checked' : ''; ?>>
      <label for="binance">Binance</label>
      <input type="radio" id="kraken" name="exchange" value="kraken" <?php echo ($exchange === 'kraken') ? 'checked' : ''; ?>>
      <label for="kraken">Kraken</label>
      <input type="radio" id="dg" name="exchange" value="dg" <?php echo ($exchange === 'dg') ? 'checked' : ''; ?>>
      <label for="dg">Digie</label>
      <input type="radio" id="okex" name="exchange" value="okex" <?php echo ($exchange === 'okex') ? 'checked' : ''; ?>>
      <label for="okex">Okex</label>
      <button type="submit" class="btn btn-primary">Apply Filter</button>
  </form>
    <!-- End Filter Form -->

    <?php
    // Your existing code for displaying alerts and the table goes here
    ?>
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
        <table class="dynamicTable table table-bordered">

          <!-- Table heading -->
          <thead>
            <tr>
              <th>Sr</th>
              <th>Coin Logo</th>
              <th>Coin Name</th>
              <th>Symbol</th>
              <th>KeyWords</th>
              <th>Category</th>
              <th>Unit Value</th>
              <th>Created Date</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
           <?php
if (count($coins_arr) > 0) {
	for ($i = 0; $i < count($coins_arr); $i++) {
		?>
           <tr class="gradeX">
              <td><?php echo $i + 1; ?></td>
              <td width="100px"><img class="img img-circle" src="<?php echo SURL; ?>assets/coin_logo/thumbs/<?php echo $coins_arr[$i]['coin_logo']; ?>"></td>
              <td><?php echo $coins_arr[$i]['coin_name']; ?></td>
              <td><?php echo $coins_arr[$i]['symbol']; ?></td>
              <td><?php echo $coins_arr[$i]['coin_keywords']; ?></td>
              <td class="clickableTd">

                <form action="#" style="display:none;">
                <input type="text" class="form-control" name="coin_category" id="coin_category<?php echo $i?>" value="<?php echo $coins_arr[$i]['category']; ?>">
                <input type="text" name="coin_id" value="<?php echo $coins_arr[$i]['_id'];?>" hidden>
                <i onclick='addCategory("#coin_category<?php echo $i?>")' class="fa fa-plus-circle pull-right btn-info btn-sm" style="margin-top: 2%;" title="Save Category"></i>
                </form>
                
                   <span><?php echo isset($coins_arr[$i]['category'])?$coins_arr[$i]['category'] :'--'?></span>
                
                
            </td>
              <td><?php echo $coins_arr[$i]['unit_value']; ?></td>
              <td><?php echo date('d, M Y', strtotime($coins_arr[$i]['created_date'])); ?></td>
              <td class="center">
                <div class="btn-group btn-group-xs ">
                    <a href="<?php echo SURL . 'admin/coins/edit-coin/' . $coins_arr[$i]['_id']. '/'. $exchange; ?>" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>
                    <a href="<?php echo SURL . 'admin/coins/delete-coin/' . $coins_arr[$i]['_id']. '/'. $exchange; ?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete?')"><i class="fa fa-times"></i></a>
                </div>
               </td>
            </tr>
           <?php }
}?>
          </tbody>

        </table>
        <!-- // Table END -->




      </div>
    </div>
    <!-- // Widget END -->

  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $('.clickableTd').dblclick(function(){

    var form = $(this).children().first();
    // var inputFields = form.find('input');
    // var category = form.find('input[name="category"]');
    
    if(form.css('display') == "block"){

      form.next('span').css('display', 'block');
      form.css('display' ,'none');
    }else{

      form.next('span').css('display', 'none');
      form.css('display' ,'block');

    }
  })

  function addCategory(category_id){

    var category = $(category_id).val();

    var coin = $(category_id).next('input');

    var coin_id = coin.val();

    let val = + category;

    if(val > 5){
      
      swal.fire('Info!' , 'You cannot exceed the limit 5.', 'info');
      return;
    }

    var exchange = $('#exchangeForm input:radio:checked');

    exchange = exchange.val();

    $.ajax({
      url : '<?php echo SURL ?>/admin/coins/update_coin_category',
      type: 'POST',
      data:{coin_id:coin_id , category:category ,exchange:exchange},
      success:function(response){
        var obj = JSON.parse(response);
        if(obj.success){
          console.log(obj)
          form = $(category_id).closest('form')
          form.css('display' , 'none');
          form.next('span').html(category);
          form.next('span').css('display','block')
          swal.fire('Success!' , obj.message, 'success')
        }else{
          swal.fire('Info!' , 'Something went wrong.', 'info');
        }
      }

    });

  }
</script>