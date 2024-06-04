<link rel="stylesheet" type="text/css" href="http://users.digiebot.com/assets/admin/stylesheets/animate.css">
<div id="content">
    <style>
        @import "bourbon";

        body {
            font-family: 'Open Sans', "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5em;
            font-weight: 400;
        }

        p,
        span,
        a,
        ul,
        li,
        button {
            font-family: inherit;
            font-size: inherit;
            font-weight: inherit;
            line-height: inherit;
        }

        strong {
            font-weight: 600;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Open Sans', "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
            line-height: 1.5em;
            font-weight: 300;
        }

        strong {
            font-weight: 400;
        }

        .tile {
            width: 100%;
            display: inline-block;
            box-sizing: border-box;
            background: #fff;
            padding: 20px;
            margin-bottom: 10px;
        }

        .tile .title {
            margin-top: 0px;
        }

        .tile.purple,
        .tile.blue,
        .tile.red,
        .tile.orange,
        .tile.green {
            color: #fff;
        }

        .tile.purple {
            background: #5133ab;
        }

        .tile.purple:hover {
            background: #3e2784;
        }

        .tile.red {
            background: #ac193d;
        }

        .tile.red:hover {
            background: #7f132d;
        }

        .tile.green {
            background: #00a600;
        }

        .tile.green:hover {
            background: #007300;
        }

        .tile.blue {
            background: #2672ec;
        }

        .tile.blue:hover {
            background: #125acd;
        }

        .tile.orange {
            background: #dc572e;
        }

        .tile.orange:hover {
            background: #b8431f;
        }
        a.tile{
            text-decoration : none;
        }
        .tile.grey {
            background: linear-gradient(45deg, whitesmoke, white);
            margin: 15px;
            border: 1px solid #eee;
            box-shadow: 0 1px 23px -20px #e8e8e8;
            border-radius: 25px;
            color: black;

        }

        .notification_popup {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.7);
            z-index: 99999;
            display: none;
        }

        .notification_popup_iner {
            padding: 25px;
            background: #fff;
            margin: auto;
            height: 250px;
            width: 100%;
            max-width: 500px;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            position: absolute;
            box-shadow: 0 0 59px 24px rgba(0, 0, 43, 0.2);
            border-radius: 15px;
        }

        .conternta-pop h2 {
            font-size: 19px;
            text-align: center;
            font-weight: bold;
            color: #000;
            margin-top: 33px !important;
            float: left;
            width: 100%;
        }

        .conternta-pop h2 codet {
            color: #c3a221;
        }

        .npclss {
            position: absolute;
            right: 15px;
            top: 15px;
            height: 30px;
            width: 30px;
            text-align: center;
            border: 1px solid #ccc;
            background: #fff;
            border-radius: 22px;
            padding-top: 5px;
            cursor: pointer;
        }

        a.btn.btn-default.custom-btn {
            height: 120px;
            width: 75%;
            font-size: 20px;
            background: #fff;
            text-align: center;
            padding: 5px;
            padding-top: 20px;
            border-radius: 10px;
        }

        .slideInRight {
            -webkit-animation-name: slideInRight;
            animation-name: slideInRight;
        }

        .animated {
            -webkit-animation-duration: 1s;
            animation-duration: 1s;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
        }

        .dash-main {
            background: #fff none repeat scroll 0 0;
            border: 1px solid #eee;
            box-shadow: 0 1px 23px -20px #e8e8e8;
            float: left;
            padding: 15px;
            width: 100%;
        }

        .menu-item.animated {
            height: 175px;
            padding: 15px;
            color: black;
            
        }

        a.btn.btn-default.custom-btn:hover {
            background: #0f1c42;
            color: white !important;
        }

        .custom-btn i {
            padding-bottom: 14px;
        }

        @keyframes bounce {

            0%,
            20%,
            60%,
            100% {
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }

            40% {
                -webkit-transform: translateY(-20px);
                transform: translateY(-20px);
            }

            80% {
                -webkit-transform: translateY(-10px);
                transform: translateY(-10px);
            }
        }

        .menu-item:hover a {
            animation: bounce 1s;
        }
    </style>
    <!-- <div class="notification_popup">
        <div class="notification_popup_iner">
        	<div class="npclss">X</div>
            <div id="popup_text" class="conternta-pop">
              <h2>
              WARNING: You have less than <codet>$10 USD in BNB </codet> balance. To reduce your Binance trading fees, please Maintain a Minimum of <codet>$10 USD in BNB </codet> coin balance in your Binance account.Your digiebot trading account will still trade if you do not have any BNB, but your Binance trading fees will be slightly higher. Binance Fee Schedule https://www.binance.com/en/fee/schedule
              </h2>
            </div>
        </div>
    </div> -->

    <div class="innerAll spacing-x2">
        <div class="row">
            <div class="col-md-12">
                <div class="widget">
                    <div class="widget-body padding-none">
                        <div class="row row-merge">
                            <div class="row">
                                <div class="col-sm-4 menu-item animated">
                                    <a href="#" class="tile grey">
                                        <h3 class="title">Cron Jobs</h3>
                                        <p>Cron Message.</p>
                                    </a>
                                </div>
                                <div class="col-sm-4 menu-item animated">
                                    <a href="#" class="tile grey">
                                        <h3 class="title">Trading Alert</h3>
                                        <p>Trading Message.</p>
                                    </a>
                                </div>
                                <div class="col-sm-4 menu-item animated">
                                    <a href="#" class="tile grey">
                                        <h3 class="title">Error In Sell</h3>
                                        <p>Error Trade Message Here.</p>
                                    </a>
                                </div>
                                <div class="col-sm-4 menu-item animated">
                                    <a href="#" class="tile grey">
                                        <h3 class="title">User Balance</h3>
                                        <p>User Balance Here.</p>
                                    </a>
                                </div>
                                <div class="col-sm-4 menu-item animated">
                                    <a href="#" class="tile grey">
                                        <h3 class="title">Wrong Login</h3>
                                        <p>Login Message Here.</p>
                                    </a>
                                </div>
                                <div class="col-sm-4 menu-item animated">
                                    <a href="#" class="tile grey">
                                        <h3 class="title">Auto Trading</h3>
                                        <p>Auto Trading Message Here.</p>
                                    </a>
                                </div>
                                <div class="col-sm-4 menu-item animated">
                                    <a href="#" class="tile grey">
                                        <h3 class="title">New Ticket</h3>
                                        <p>New Ticket Message Here.</p>
                                    </a>
                                </div>

                                <div class="col-sm-4 menu-item animated">
                                    <a href="#" class="tile grey">
                                        <h3 class="title">Server Usage</h3>
                                        <p>Server usage Message Here.</p>
                                    </a>
                                </div>
                                <div class="col-sm-4 menu-item animated">
                                    <a href="#" class="tile grey">
                                        <h3 class="title">Profitable Trades</h3>
                                        <p>MEssage Here.</p>
                                    </a>
                                </div>
                                <div class="col-sm-4 menu-item animated">
                                    <a href="#" class="tile grey">
                                        <h3 class="title">New Message</h3>
                                        <p>New User Message Here.</p>
                                    </a>
                                </div>
                                <div class="col-sm-4 menu-item animated">
                                    <a href="#" class="tile grey">
                                        <h3 class="title">Testing New Git</h3>
                                        <p>Testing New Git.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<!-- // Content END -->

<script type="text/javascript">
    $(document).ready(function(e) {
        $("body").on("click", ".npclss", function() {
            $(".notification_popup").hide();
        });

        var is_bnb_balance = "<?php echo $is_bnb_balance; ?>";
        if (is_bnb_balance == 'NO') {
            $(".notification_popup").show();
        }

    });


    var call_statusinterval;
    var auto_refresh;

    //auto_refresh = setInterval(autoload_trading_data, 2000);

    //   function autoload_trading_data(){

    //     var market_value = $("#market_value").val();

    //     $.ajax({
    //       type:'POST',
    //       url:'<?php echo SURL?>admin/dashboard/autoload_trading_data',
    //       data: {market_value:market_value},
    //       success:function(response){

    //         var split_response = response.split('|');

    //         if(split_response[0] !=""){
    //           $('#response_sell_trade').html(split_response[0]);
    //           $('#response_market_value_sell').html('Sell ('+split_response[3]+')');
    //         }
    //         if(split_response[1] !=""){
    //           $('#response_buy_trade').html(split_response[1]);
    //           $('#response_market_value_buy').html('Buy ('+split_response[3]+')');
    //         }
    //         if(split_response[2] !=""){
    //           $('#response_market_history').html(split_response[2]);
    //         }

    //         setTimeout(function() {
    //             autoload_trading_data();
    //         }, 1000);
    //         //autoload_trading_data();
    //       }
    //     });

    //   }//end autoload_trading_data() 

    //autoload_trading_data();
</script>