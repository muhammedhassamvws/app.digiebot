<div id="content">
  <h1 class="content-heading bg-white border-bottom">Add Order</h1>
  
  <div class="innerAll spacing-x2">
  	
        <div class="widget widget-inverse"> 
            <form class="form-horizontal margin-none" method="post" action="<?php echo SURL?>admin/dashboard/add-order-process" novalidate="novalidate">
            <div class="widget-body"> 
              
              <!-- Row -->
              <div class="row">
                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Purchased Price</label>
                    <input type="text" name="purchased_price" required="required" class="form-control">
                  </div>
                </div> 
                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Quantity</label>
                    <input type="text" name="quantity" required="required" class="form-control">
                  </div>
                </div>
              </div>  

              <div class="row">
                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Profit Type</label>
                    <select class="form-control" name="profit_type" id="profit_type">
                      <option value="percentage">Percentage</option>
                      <option value="fixed_price">Fixed Price</option>
                    </select>
                  </div>
                </div> 
              </div>

              <div class="row">
                <div class="col-md-6" id="sell_profit_percent_div"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Sell Profit (%)</label>
                    <input type="text" name="sell_profit_percent" required="required" class="form-control">
                  </div>
                </div> 
                <div class="col-md-6" id="sell_profit_price_div"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Sell Price</label>
                    <input type="text" name="sell_profit_price" required="required" class="form-control">
                  </div>
                </div> 
              </div>
              <div class="row"> 
                <div class="col-md-6"> 
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" value="trail_sell" id="trail_check" name = "trail_check">
                    <label class="form-check-label" for="trail_check">Trail Sell</label>
                  </div>
                </div> 
              <!-- <div class="row"> 
                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Stop Loss (BTC PRICE)</label>
                    <input type="text" name="stop_los_price" required="required" class="form-control">
                  </div>
                </div> 

                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Stop Loss (%)</label>
                    <input type="text" name="stop_los_percent" required="required" class="form-control">
                  </div>
                </div> 
                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Stop Loss (BTC PRICE)</label>
                    <input type="text" name="stop_los_price" required="required" class="form-control">
                  </div>
                </div>  
                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">BTC Invest</label>
                    <input type="text" name="btc_invest" required="required" class="form-control">
                  </div>
                </div>  
                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label class="control-label">Amount of (EOS) to Buy</label>
                    <input type="text" name="amount_of_eos_to_buy" required="required" class="form-control">
                  </div>
                </div>
                <div class="col-md-12"> 
                  <div class="form-group col-md-12">
                    <label class="control-label" style="padding-bottom: 5px;">Stop Profit(%) <span class="badge badge-danger">New</span> [minimum percentage to close order]</label>
                    <input type="text" name="stop_profit" required="required" class="form-control">
                  </div>
                </div> 
              </div> -->
              <!-- // Row END -->
              <hr class="separator">
              
              <!-- Form actions -->
              <div class="form-actions">
                <button class="btn btn-success" type="submit"><i class="fa fa-check-circle"></i> Add Order </button>
              </div>
              <!-- // Form actions END --> 
              
            </div>
            </form>
        </div>    
     
    
  </div>
</div>


