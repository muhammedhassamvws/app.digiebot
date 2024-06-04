<div id="content">
    <h1 class="content-heading bg-white border-bottom">Bitcoin Prices</h1>
    <div class="innerAll spacing-x2">
        <div class="widget widget-inverse">
            <div class="widget-head">
                <h3 class="heading"><i class="icon-manager"></i>Bitcoin Prices</h3>
            </div>
            <div class="widget-body">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- Tab panes -->
                                <div class="table-responsive">
                                  <table class="table table-stripped">
                                    <thead>
                                      <tr>
                                        <th>Datetime</th>
                                        <th>Price in USD at time of Deposit</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php foreach ($bitcoin_prices as $key => $value) {?>
                                        <tr>
                                        <th><?=$key;?></th>
                                        <td><?=$value;?></td>
                                      </tr>
                                      <?php }?>
                                    </tbody>
                                  </table>
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- // Widget END -->
</div>
