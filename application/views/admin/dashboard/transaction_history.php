<style type="text/css">
    .tabs-left {
        border-bottom: none;
        padding-top: 2px;
        border-right: 1px solid #ddd;
    }

    .tabs-left>li {
        float: none;
        margin-bottom: 2px;
        margin-right: -1px;
    }

    .tabs-left>li.active>a,
    .tabs-left>li.active>a:hover,
    .tabs-left>li.active>a:focus {
        border-bottom-color: #ddd;
        border-right-color: transparent;
    }

    .tabs-left>li>a {
        border-radius: 4px 0 0 4px;
        margin-right: 0;
        display: block;
    }

    .Input_text_s {
    /* display: inline; */
    position: relative;
}

.Input_text_s i {
    position: absolute;
    top: 9px;
    right: 15px;
}
</style>
<div id="content">
    <h1 class="content-heading bg-white border-bottom">Transaction History</h1>
    <div class="innerAll spacing-x2">
        <div class="widget widget-inverse">
           <div class="widget-head" style="height: 55px;">
                <h3 class="heading">Transaction History</h3>
            <a href="http://app.digiebot.com/admin/dashboard/bitcoin_reference" class="btn btn-primary" target="_blank" style="float: right;margin: 10px;">Check Bitcoin Historical Prices</a></div>
            <div class="widget-body">
                <div class="row">
                    <div class="col-xs-2">
                        <ul class="nav nav-tabs tabs-left">
                            <li class="active"><a href="#tab3" data-toggle="tab">Deposit</a></li>
                            <li><a href="#tab4" data-toggle="tab">Withdraw</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-10">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab3">
                                <div class="table-responsive">
                                  <table class="table table-stripped">
                                    <thead>
                                      <tr>
                                        <th>Datetime</th>
                                        <th>Txn Id</th>
                                        <th>Amount</th>
                                        <th>Price in USD at time of Deposit</th>
                                        <th>Action</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php foreach ($user_info['deposit'] as $key => $value) {?>
                                        <tr>
                                        <th><?=date("d-m-Y H:i:s", (($value['insertTime'] / 1000)));?></th>
                                        <td><?=$value['txId'];?></td>
                                        <td><?=$value['amount'] . " " . $value['asset'];?></td>
                                        <td><div class="Input_text_s">
                                             <input class="form-control price_in_usd" id="am_<?php echo $value['_id']; ?>" type="text" name="price_in_usd" id="" value="<?=$value['price_in_usd'];?>">
                                             <i class="">USD</i>
                                          </div></td>
                                        <td><button class="btn btn-success btn-md updatebtn" data-id="<?php echo $value['_id']; ?>">Update</button></td>
                                      </tr>
                                      <?php }?>
                                    </tbody>
                                  </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab4">
                              <div class="table-responsive">
                                  <table class="table table-stripped">
                                    <thead>
                                      <tr>
                                        <th>Datetime</th>
                                        <th>Txn Id</th>
                                        <th>Amount</th>
                                        <th>Price in USD at time of Deposit</th>
                                        <th>Action</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                       <?php foreach ($user_info['withdraw'] as $key => $value) {?>
                                        <tr>
                                        <th><?=date("d-m-Y H:i:s", (($value['successTime'] / 1000)));?></th>
                                        <td><?=$value['txId'];?></td>
                                        <td><?=$value['amount'] . " " . $value['asset'];?></td>
                                        <td><div class="Input_text_s">
                                             <input class="form-control price_in_usd" id="am_<?php echo $value['_id']; ?>" type="text" name="price_in_usd" value="<?=$value['price_in_usd'];?>">
                                             <i class="">USD</i>
                                          </div></td>
                                        <td><button class="btn btn-success btn-md updatebtn" data-id="<?php echo $value['_id']; ?>">Update</button></td>
                                      </tr>
                                      <?php }?>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <a href="<?php echo SURL; ?>admin/dashboard/bitcoin_reference" class="btn btn-info" target="_blank">Check Bitcoin Historical Prices</a> -->
                </div>
            </div>
        </div>
    </div>
    <!-- // Widget END -->
</div>

<script type="text/javascript">
  $("body").on("click",".updatebtn",function(e){
      var id = $(this).data("id");
      var price = $("#am_"+id).val();

      $.ajax({
        url: "<?php echo SURL; ?>admin/dashboard/transaction_history_process",
        data:{id:id, price:price},
        type:"POST",
        success:function(response){
          $.alert({
            title: "Success",
            content: response,
          });
        }
      });
  });
</script>