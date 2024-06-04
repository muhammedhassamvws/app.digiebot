<div id="content">
  <h1 class="content-heading bg-white border-bottom">coins</h1>
  <div class="innerAll bg-white border-bottom">
  <ul class="menubar">
  <li class="active"><a href="<?php echo SURL; ?>/admin/coin_market">Market</a></li>
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
      <div class="widget-head" style="height:46px;">
      <h4 class="heading" style=" padding-top: 3px;">Coin Market</h4>
    </div>
      <div class="widget-body padding-bottom-none">
        <!-- Table -->
        <table id="tableOne" class="table table-hover">

          <!-- Table heading -->
          <thead>
            <tr>
              <th>Sr</th>
              <th>Coin Logo</th>
              <th>Coin Pair</th>
              <th>Last Price</th>
              <th>-24h Change</th>
              <th>Balance</th>
              <th>Open Trades</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
          <?php
if (count($coin_market) > 0) {
	for ($i = 0; $i < count($coin_market); $i++) {
		?>
            <tr>
              <td><?php echo $i + 1; ?></td>
              <td class="logo" width="100px"><img class="img img-circle" src="<?php echo SURL; ?>assets/coin_logo/thumbs/<?php echo $coin_market[$i]['logo']; ?>" width="45px"></td>
              <td class="symbol"><?php echo $coin_market[$i]['symbol']; ?></td>
              <td class="last_price"><?php echo num($coin_market[$i]['last_price']); ?>/<?php echo $coin_market[$i]['usd_amount']; ?> USD</td>
              <td class="change_val"><?php echo num($coin_market[$i]['change']); ?></td>
              <td class="balance"><?php echo $coin_market[$i]['balance']; ?></td>
              <td class="trade"><?php echo $coin_market[$i]['trade']; ?></td>
              <td>
                <?php if ($coin_market[$i]['symbol'] != "BTC") {

			?>
                <span class="btn-group btn-group-xs" style="padding:10px;">
                    <a href="<?php echo SURL . 'admin/user_coins/delete-coin/' . $coin_market[$i]['coin_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete?')"><i class="fa fa-times"></i></a>
                </span>
              <?php }?>
                 <span class="btn-group btn-group-xs" style="padding:10px;">
                <a href="<?php echo SURL; ?>admin/coin_market/coin_detail/<?php echo $coin_market[$i]['symbol']; ?>" class="btn btn-primary">Details</a>
              </span>
              </td>
            </tr>
          <?php
}
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
<script type="text/javascript">
$(document).ready(function(){
    autoload_page();
});

  function autoload_page()
  {
    $.ajax({
    url: '<?php echo SURL ?>admin/coin_market/get_auto_update_data',
    type: 'POST',
    data: "",
    dataType: "json",
    success: function (response) {
      var arr = response;
    $('#tableOne > tbody  > tr').each(function(index,item) {
      var balance = $(this).find(".balance");
      var last_price = $(this).find(".last_price");
      var trade = $(this).find(".trade");
      var change = $(this).find(".change_val");
      balance.html(arr[index].balance);
      last_price.html(arr[index].last_price+'/'+arr[index].usd_amount+' USD');
      trade.html(arr[index].trade);
      change.html(arr[index].change);
     });
      setTimeout(function() {
       autoload_page();
      }, 5000);
    }
  });
  }
</script>