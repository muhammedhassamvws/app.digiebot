<style type="text/css">
 .well
{
  background-color: #ffffff !important;
  font-size: 16px;
  font-family: monospace;
  color: black;
}
</style>
<?php 
$score_avg = 0;
$psum = 0;
$nsum = 0;
$sum = 0;
$x = 0;
$count = 0;
foreach ($news as $key => $value) {
  if ($value['score'] >= 0) {
    $psum = $psum + $value['score'];
  }
  else
  {
    $nsum = $nsum+ $value['score'];
  }
  $count++;
}
$sum = $psum+(-1*($nsum));
$x = $psum/$sum; 
$score_avg = round($x*100);

?>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Coin Information</h1>
  <div class="innerAll bg-white border-bottom"> 
  <ul class="menubar">
  <li class="active"><a href="<?php echo SURL; ?>/admin/coin_market">Market</a></li>
  </ul>
  </div>
  <div class="innerAll spacing-x2"> 
	 <?php
      if($this->session->flashdata('err_message')){
      ?>
      <div class="alert alert-danger"><?php echo $this->session->flashdata('err_message'); ?></div>
      <?php
      }
      if($this->session->flashdata('ok_message')){
      ?>
      <div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('ok_message'); ?></div>
      <?php 
      }
      ?>
    
    <!-- Widget -->
    <div class="widget widget-inverse">
      <div class="widget-head" style="height:46px;">               
      <h4 class="heading" style=" padding-top: 3px;">Coin Information</h4>
    </div>
   <div class="container-fluid">
  <div class="row" style="padding:10px;">
    <div class="col-md-3">
      <center>
        <h4>Coin Logo</h4>
      <img alt="Image" src="<?php echo SURL; ?>assets/coin_logo/<?php echo $coin['coin_logo']; ?>" class="img img-circle" width="50%" />
      </center>
    </div>
    <div class="col-md-9">
      <div class="row">
        <div class="col-md-12">
          <label class="table table-hover">
          <span><strong>Coin Name: </strong></span><?php echo ' '.$coin['coin_name']; ?>
          </label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <label class="">
          <span><strong>Coin Symbol: </strong></span><?php echo ' '.$coin['symbol']; ?>
          </label>
        </div>
        <div class="col-md-6">
          <label class="table table-hover" id="balance">
          <span><strong>Balance: </strong></span><?php echo ' '.$market['balance']; ?>
          </label>
        </div>
      </div>
    </div>
    <div class="row">
        <div class="col-md-6">
          <label class="table table-hover" id="last_price">
          <span><strong>Coin Last Price: </strong></span><?php echo ' '.$market['last_price']; ?>
          </label>
        </div>
        <div class="col-md-6">
          <label class="table table-hover" id="trades">
          <span><strong>Coin Open Trades: </strong></span><?php echo ' '.$market['trade']; ?>
          </label>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="widget widget-inverse">
  <div class="widget-head" style="height:46px;">               
        <h4 class="heading" style=" padding-top: 3px;">Sentiment Meter</h4>
      </div>
  <div class="container-fluid" style="text-align: center;background: #424242;">
    <div class="row" style="padding:10px;">
       <!-- <div class="forgrou">
          <label>MeaterVal</label>
            <input type="number" class="Meater" value="73">
       </div> -->
        <div class="meater_candle">
              <div class="goal_meater_main">
                    <div class="goal_meater_img">
                        <div class="degits"><?php echo $score_avg; ?></div>
                        <div pin-value="<?php echo $score_avg; ?>" class="goal_pin"></div>
                    </div>
                </div>
            </div>
    </div>
  </div>
</div>

<div class="widget widget-inverse">
      <div class="widget-head" style="height:46px;">               
        <h4 class="heading" style=" padding-top: 3px;">Coin News</h4>
      </div>
    <div class="container-fluid">
    <div class="row" style="padding:10px;">
    <?php 
      if (count($news) > 0) {
       for ($i=0; $i < count($news) ; $i++) { 
    ?>
    <div class="col-md-12">
      <p class="well">
         <?php echo $news[$i]['news']; ?>
      </p>
    </div>
    <div class="col-md-3">
       <p>
        <span><strong>Labels:</strong></span>
       <?php foreach ($news[$i]['keyword'] as $key => $value) {
         ?>
         <span class="label label-success label-stroke"><?php echo $value; ?></span>
         <?php
       } ?>
      </p>
    </div>

    <div class="col-md-3">
      <p>
        <span><strong>Source: </strong></span><span class="label label-success"><?php echo strtoupper($news[$i]['source']); ?></span>
      </p>
    </div>

    <div class="col-md-3">
       <p>
        <span><strong>Coin: </strong></span><span class="label label-info"><?php echo strtoupper($news[$i]['coin']); ?></span>
      </p>
    </div>

    <div class="col-md-3">
      <p>
        <span><strong>Score: </strong></span><span class="label label-primary"><?php echo strtoupper($news[$i]['score']); ?></span>
      </p>
    </div>
    <?php 
       }
     }
    ?>
  </div>
</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    autoload_page();
});

  function autoload_page()
  {
    var symbol = '<?php echo $coin['symbol']; ?>';
    $.ajax({
    url: '<?php echo SURL ?>admin/coin_market/get_auto_update_coin_data',
    type: 'POST',
    data: {coin:symbol},
    success: function (response) { 
      var str = response.split('|');
      $('#balance').html("<strong>Balance: </strong></span>"+str[1]);
      $('#last_price').html("<strong>Coin Last Price: </strong></span>"+str[2]);
      $('#trades').html("<strong>Coin Open Trades: </strong></span>"+str[3]);
      setTimeout(function() {
       autoload_page();
      }, 5000);
      console.log(response);
    }
  });
  }
</script>

<script type="text/javascript">
  
  function meaterfunction(){

var pin_val = jQuery(".goal_pin").attr("pin-value");
  
  var actual_pin = 2.075 * pin_val;
  
  var deg_rat = 166 + actual_pin;
  //alert(deg_rat);
  
  jQuery(".goal_pin").css({
  '-webkit-transform' : 'rotate('+deg_rat+'deg)',
     '-moz-transform' : 'rotate('+deg_rat+'deg)',  
      '-ms-transform' : 'rotate('+deg_rat+'deg)',  
       '-o-transform' : 'rotate('+deg_rat+'deg)',  
          'transform' : 'rotate('+deg_rat+'deg)',  
               'zoom' : 1

    });
  
}

jQuery(document).ready(function(e) {
  meaterfunction();
  /*jQuery("body").on("keyup",".Meater",function(){
    var inmet = jQuery(this).val();
    jQuery(".goal_pin").attr("pin-value",inmet);
    jQuery(".degits").text(inmet);
    meaterfunction();
  });*/
  
   /* jQuery("body").on("click",".Meater",function(){
    var inmet = jQuery(this).val();
    jQuery(".goal_pin").attr("pin-value",inmet);
    jQuery(".degits").text(inmet);
    meaterfunction();
  });*/
  
});
</script>