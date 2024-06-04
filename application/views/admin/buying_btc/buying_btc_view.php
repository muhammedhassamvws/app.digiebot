<style type="text/css">
    label.error {
        /* remove the next line when you have trouble in IE6 with labels in list */
        color: red;
        font-style: italic
    }

    .alert-ignore{
        font-size:12px;
        border-color: #b38d41;
    }
    small{
        color:orange;
        font-weight: bold;
    }
    .btnbtn{
        padding-left:0px;
    }

    .btnval {
        width: 9%;
        font-size: 11px;
        height: auto;
        padding-left: 3px;
        margin-left: 5px;
        background: #ffffff;
        border-color: #373737;
        border-radius: 6px;
        color: #373737;
        font-weight: bold;
    }

    .btnval2 {
        width: 9%;
        font-size: 11px;
        height: auto;
        padding-left: 3px;
        margin-left: 5px;
        background: #ffffff;
        border-color: #373737;
        border-radius: 6px;
        color: #373737;
        font-weight: bold;
    }
    .btnval3 {
        width: 9%;
        font-size: 11px;
        height: auto;
        padding-left: 3px;
        margin-left: 5px;
        background: #ffffff;
        border-color: #373737;
        border-radius: 6px;
        color: #373737;
        font-weight: bold;
    }

    .btnval1 {
        width: 9%;
        font-size: 11px;
        height: auto;
        padding-left: 3px;
        margin-left: 5px;
        background: #ffffff;
        border-color: #373737;
        border-radius: 6px;
        color: #373737;
        font-weight: bold;
    }

    .btn-group, .btn-group-vertical {
        position: relative;
        display: inline-block;
        vertical-align: middle;
        padding-bottom: 10px;
    }
    .close{
        margin-top: -2%;
    }

    .slidecontainer {
    width: 100%;
    }

    .slider {
        -webkit-appearance: none;
        width: 100%;
        height: 25px;
        background: #d3d3d3;
        outline: none;
        opacity: 0.7;
        -webkit-transition: .2s;
        transition: opacity .2s;
    }

    .slider:hover {
        opacity: 1;
    }

    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 25px;
        height: 25px;
        background: #4CAF50;
        cursor: pointer;
    }

    .slider::-moz-range-thumb {
        width: 25px;
        height: 25px;
        background: #4CAF50;
        cursor: pointer;
    }
    .get-relative {
        position: relative;
    }

    .optimized-loader {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        text-align: center;
        background: rgba(255,255,255,0.7);
        z-index: 99;

    }

    .optimized-loader img {
        width: 50px;
        height: 50px;
        position: absolute;
        margin: auto;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }
</style>


<div id="content" class="get-relative">
    <div class="optimized-loader" style="display:none;">
				<img src="http://app.digiebot.com/assets/images/load_cube.gif">
    </div>
  <h1 class="content-heading bg-white border-bottom">Buy BTC</h1>

  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
        <li><a href="#">Buy BTC</a></li>
        
    </ul>
    <span class="fa fa-info-circle" style="float: right;font-size: 20px;margin-top: -25px;color: #cb4040;" data-toggle="popover" data-placement="left" data-trigger="hover" data-container="body" data-original-title="Buy BTC" data-content="Here Buy BTC."></span>
  </div>

  <div class="innerAll spacing-x2">
        <div class="row">
          <div class="col-md-3">
          </div>
          <div class="col-md-6">
            
            <div class="widget widget-inverse">
                <form id="buy_btc_form" class="form-horizontal margin-none" method="post" action="#" novalidate="novalidate">
                <div class="widget-body">
                    <!-- Row -->
                    <div class="row">
                        <div class="col-md-12" id="pricealert"></div>
                        <div class="col-md-12">
                            <div class="form-group col-md-8">
                                <label class="control-label">Amount</label>
                                <input type="number" id="txt_usd" class="form-control" min="0" step="any" value="0" />
                            </div>
                            <div class="form-group col-md-4" style="padding-left:35px;">
                                <label class="control-label">BTC</label>
                                <div class="label label-success" id="lbl_btc" style="height: 33px;padding-top: 9px;font-size: 15px;">BTC 0.000</div>
                            </div>
                        </div>

                        </div>
                        <div class="col-md-12" id="quantitydv">

                        </div>
                        <div class="col-md-12">
                        <div class="form-group col-md-12">
                            <label class="control-label">Available Balance</label>
                            <input type="text" id="txt_balance" style="letter-spacing:0.25em;" class="form-control" readonly>
                
                                                    <div class="price_error"></div>
                                                </div>
                        </div>

                    
                    <!-- // Row END -->
                    <hr class="separator">

                    <!-- Form actions -->
                    <div class="form-actions">
                    <button class="btn btn-success" id="btn_buy_btc" type="submit"><i class="fa fa-check-circle"></i> Buy BTC  </button>
                    </div>
                    <!-- // Form actions END -->

                </div>
                </form>
            </div>
          </div>       
          <div class="col-md-3">
          </div> 
  </div>
</div>


<script type="text/javascript">
    $(document).ready(function(){
        available_balance()
        
        $("#txt_usd").keyup(function (){
            var usd_bal = $('#txt_usd').val();
            $.ajax({
                url:"<?php echo SURL; ?>admin/buying_btc/btc_rate",
                type:'post',
                data:{usd_bal:usd_bal},
                success: 
                function(data){
                    data = JSON.parse(data)
                    var available_usd=data.available_bal;  

                    $('#lbl_btc').text(data.btc_balance);
                    
                }
            });
            
        
        });

        $("#txt_usd").change(function (){
            var usd_bal = $('#txt_usd').val();
            $.ajax({
                url:"<?php echo SURL; ?>admin/buying_btc/btc_rate",
                type:'post',
                data:{usd_bal:usd_bal},
                success: 
                function(data){
                    data = JSON.parse(data)
                    var available_usd=data.available_bal;  

                    $('#lbl_btc').text(data.btc_balance);
                    
                }
            });
            
        
        });
        
    function available_balance(){
        
        $.ajax({
                url:"<?php echo SURL; ?>admin/buying_btc/available_balance",
                type:'post',
                success: 
                function(data){
                    data = JSON.parse(data)
                    var available_usd=data.available_bal;  
                    $('#txt_balance').val(available_usd+" USD");
                    
                }
            });
        }

});



</script>