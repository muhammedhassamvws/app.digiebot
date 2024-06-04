<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Digiebot-API Docs</title>
<link rel="Shortcut Icon" href="http://app.digiebot.com/assets/icons/favicon_ico.ico">
<meta name="description" content="Pixxett API Docs Theme">
<meta name="author" content="Pixxett">
<meta name="copyright" content="Pixxett">
<meta name="date" content="2017-12-28">

<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900">
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900">

<link rel="stylesheet" type="text/css" href="<?php echo ASSETS; ?>api_doc/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ASSETS; ?>api_doc/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ASSETS; ?>api_doc/css/jquery.mobile-menu.css">
<link rel="stylesheet" type="text/css" href="<?php echo ASSETS; ?>api_doc/css/style.css" media="all">
<link rel="stylesheet" type="text/css" href="<?php echo ASSETS; ?>api_doc/css/responsive.css">

<style type="text/css">
.destinationMethod {
    color: #fff;
    margin-right: 5px;
    font-weight: 700;
    border: 2px solid #c1c6d1 !important;
    padding: 2px 7px !important;
    border-radius: 4px !important;
}

</style>
</head>
<body id="pixxett-api">
<div id="page">

<header class="header" id="header">
<div class="container-fluid">
<div class="row">
<div class="col-lg-1 col-sm-2">
<div class="mm-toggle-wrap">
<div class="mm-toggle"><i class="fa fa-align-justify"></i><span class="mm-label">Menu</span> </div>
</div>

<a class="header__block header__brand" href="<?php echo SURL; ?>admin">
<h1> <img src="<?php echo ASSETS; ?>images/digiebot_logo.png" alt="API UI logo"></h1>
</a>

</div>
<div class="col-lg-11 col-sm-10 hidden-xs">
<div class="header__nav">
<div class="header__nav--left">
<ul class="dx-nav-0 dx-nav-0-docs">
<li class="dx-nav-0-item dx-nav-active">
<a class="dx-nav-0-link" href="javascript:void(0);">API Reference</a>
</li>
</ul>
<!-- <form class="header__search dx-form-search" id="siteSearch" name="search" method="get">
<label class="sr-only" for="siteQ">Enter search term</label>
<input class="dx-search-input" id="siteQ" name="q" type="search" value="" placeholder="Search">
<span class="button-search fa fa-search"></span>
</form> -->
</div>
<div class="header__nav--right">
<div class="dx-auth-block">
<div class="dx-auth-logged-out">
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</header>


<div class="header-section-wrapper">
<div class="header-section header-section-example">
<div id="language">
<ul class="language-toggle">
<li>
<input type="radio" class="language-toggle-source" name="language-toggle" id="toggle-lang-php" data-language="curl" checked="checked">
<label for="toggle-lang-php" class="language-toggle-button language-toggle-button--php">PHP</label>
</li>
</ul>
</div>
</div>
</div>


<div id="documenter_sidebar">
<div id="scrollholder" class="scrollholder">
<div id="scroll" class="scroll">
<ol id="documenter_nav">
<li><a class="current" href="#documenter-1"><i class="fa fa-file-text-o"></i> API Reference</a></li>
<li><a href="#documenter-11"><i class="fa fa-key"></i> Authentication</a></li>
<li><a href="#documenter-2"><i class="fa fa-key"></i> Login </a></li>
<li><a href="#documenter-3"><i class="fa fa-warning"></i> Verification </a></li>
<li><a href="#documenter-4"><i class="fa fa-table"></i> Fetch All Coins</a></li>
<li><a href="#documenter-5"><i class="fa fa-gear"></i> Get Coin Market Value</a></li>
<li><a href="#documenter-6"><i class="fa fa-gear"></i> Fetch All Orders</a></li>
<li><a href="#documenter-7"><i class="fa fa-gear"></i> Fetch Order Details</a></li>
<li><a href="#documenter-8"><i class="fa fa-gear"></i> Add Manual Order</a></li>
<li><a href="#documenter-9"><i class="fa fa-gear"></i> Add Auto Order</a></li>
<li><a href="#documenter-12"><i class="fa fa-gear"></i> Get Coin Meta</a></li>
<li><a href="#documenter-10"><i class="fa fa-gear"></i> Error Codes</a></li>

</ol>
</div>
</div>
</div>


<div id="background">
<div class="background-actual"></div>
</div>


<div id="documenter_content" class="method-area-wrapper">
<section id="documenter-1" class="method">
	<div class="method-area">
	<div class="method-copy">
	<div class="method-copy-padding">
	<h3>API Reference</h3>
	<p>
		Digiebot API is a REST API accross the most known exchanges. One interface to access all different exchanges for your crypto use cases.

		It is possible to make very few requests without authentication. In order to authenticate, you need to access the token.

		and the access token is Authorization:"Basic ZGlnaWVib3Q6ZGlnaWVib3Q="
		API
		The endpoint to access Digiebot API is:

		<pre>https://app.digiebot.com/admin/api/</pre>
		Authentication
		Once you have the access token, you can make requests by setting the Authorization header to Token your access token in each request.

		{{Authorization: "Basic ZGlnaWVib3Q6ZGlnaWVib3Q="}}
	</p>
	<p> <img src="<?php echo ASSETS; ?>api_doc/images/place-holder.png" alt="Image"> </p>
	</div>
	</div>
	<div class="method-example">
	<div class="method-example-part">
	<h5>API Reference</h5>
	<p>
	The basic End point for our Api Services is the Following
	</p>
	</div>
	<div class="method-example-part">
	<div class="method-example-endpoint">
	<pre class=" language-none"><code class=" language-none">https://app.digiebot.com/admin/api_services/</code></pre>
	</div>
	</div>
	</div>
	</div>
</section>
<section id="documenter-11" class="method">
	<div class="method-area">
	<div class="method-copy">
	<div class="method-copy-padding">
	<h3>API Reference</h3>
	<p>
		Digiebot API is a REST API accross the most known exchanges. One interface to access all different exchanges for your crypto use cases.

		It is possible to make very few requests without authentication. In order to authenticate, you need to access the token.

		API
		The endpoint to access Digiebot API is:

		<pre>https://app.digiebot.com/admin/api_services/</pre>
		Authentication
		Once you have the access token, you can make requests by setting the Authorization header to Token your access token in each request.

		{{Authorization: "Basic ZGlnaWVib3Q6ZGlnaWVib3Q="}}
	</p>
	</div>
	</div>
	<div class="method-example">
	<div class="method-example-part">
	<h5>API Reference</h5>
	<p>
	The basic End point for our Api Services is the Following
	</p>
	</div>
	<div class="method-example-part">
	<div class="method-example-endpoint">
	<pre class=" language-none"><code class=" language-none">https://app.digiebot.com/admin/api/</code></pre>
	</div>
	</div>
	</div>
	</div>
</section>
<section id="documenter-12" class="method">
	<div class="method-area">
	<div class="method-copy">
	<div class="method-copy-padding">
	<h3>Get Coin Meta</h3>
	<p>
		In order to Use application one must use to login the <a href="http://app.digiebot.com/admin">Digiebot </a>
		<div class="method-list attributes">
			<h5 class="method-list-title"> Attributes </h5>
			<ul class="method-list-group">
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			type
			</h6>
			<p class="method-list-item-description">
			The type of error returned. Can be: <code class="  language-undefined">api_connection_error</code>, <code class="  language-undefined">api_error</code>, <code class="  language-undefined">authentication_error</code>, <code class="  language-undefined">card_error</code>, <code class="  language-undefined">invalid_request_error</code>, or <code class="  language-undefined">rate_limit_error</code>.
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Request Type:
			</h6>
			<p class="method-list-item-description">
			POST
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Params
			<span class="method-list-item-label-details">Important</span>
			</h6>
			<p class="method-list-item-description">
			1. symbol <br>
			<br>
			</p>
			</li>
			</ul>
		</div>
	</p>
	</div>
	</div>
	<div class="method-example">
	<div class="method-example-part">
	<h5>Get Coin Meta</h5>
	</div>
	<div class="method-example-part">
	<div class="method-example-endpoint">
	<pre class=" language-none"><code class=" language-none"><span class="destinationMethod get">POST</span> https://app.digiebot.com/admin/api/get_coin_meta</code></pre>
	<label>Response</label>
	<strong>200</strong>
	<pre style="overflow: visible;">
			<code style="margin-left: -160px;">
{
    "HTTP Response": "200",
    "Message": {
        "coin": "NCASHBTC",
        "current_market_value": "0.00000076",
        "ask_black_wall": "0.00000077",
        "ask_yellow_wall": "0.00000077",
        "bid_black_wall": "0.00000075",
        "bid_yellow_wall": "0.00000075",
        "black_wall_type": "negitive",
        "black_wall_pressure": 0,
        "yellow_wall_type": "negitive",
        "yellow_wall_pressure": 0,
        "up_pressure": 1,
        "down_pressure": 4,
        "pressure_type": "negitive",
        "pressure_diff": -3,
        "bid_contracts": "8.00K+",
        "bid_percentage": 12,
        "ask_contract": "58.38K+",
        "ask_percentage": 86,
        "buyers": "301.03K+",
        "sellers": "40.00K+",
        "buyers_percentage": 88.270723432455,
        "sellers_percentage": 11.729276567545,
        "up_big_wall": "16.23M+",
        "down_big_wall": "14.34M+",
        "up_big_price": "0.00000078",
        "down_big_price": "0.00000075",
        "great_wall_price": "0.00000078",
        "great_wall_quantity": "16.23M+",
        "great_wall": "upside",
        "great_wall_color": "red",
        "seven_level_depth": "0.20",
        "seven_level_type": "negitive",
        "modified_date": "2018-10-11 13:02:07",
        "sellers_buyers_per": 7.525675,
        "trade_type": "red",
        "last_candle_type": "normal",
        "last_candle_rejection_value": 0,
        "last_candle_rejection_status": "no_rejection",
        "last_candle_yellow_diff": 0,
        "last_candle_black_diff": 0,
        "last_candle_depth_pressure": -30,
        "social_score": 0,
        "news_score": 0,
        "score": 52,
        "last_qty_buy_vs_sell": 2.486674830904,
        "last_qty_time_ago": " 46 min ago",
        "last_200_buy_vs_sell": -1.1685849476848,
        "last_200_time_ago": " 96 min ago"
    }
}
				</code>
			</pre>
	</div>
	</div>
	</div>
	</div>
</section>
<section id="documenter-2" class="method">
	<div class="method-area">
	<div class="method-copy">
	<div class="method-copy-padding">
	<h3>Get Coin</h3>
	<p>
		In order to Use application one must use to login the <a href="http://app.digiebot.com/admin">Digiebot </a>
		<div class="method-list attributes">
			<h5 class="method-list-title"> Attributes </h5>
			<ul class="method-list-group">
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			type
			</h6>
			<p class="method-list-item-description">
			The type of error returned. Can be: <code class="  language-undefined">api_connection_error</code>, <code class="  language-undefined">api_error</code>, <code class="  language-undefined">authentication_error</code>, <code class="  language-undefined">card_error</code>, <code class="  language-undefined">invalid_request_error</code>, or <code class="  language-undefined">rate_limit_error</code>.
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Request Type:
			</h6>
			<p class="method-list-item-description">
			POST
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Params
			<span class="method-list-item-label-details">Important</span>
			</h6>
			<p class="method-list-item-description">
			1. username <br>
			2. password<br>
			</p>
			</li>
			</ul>
		</div>
	</p>
	</div>
	</div>
	<div class="method-example">
	<div class="method-example-part">
	<h5>Login Verification</h5>
	</div>
	<div class="method-example-part">
	<div class="method-example-endpoint">
	<pre class=" language-none"><code class=" language-none"><span class="destinationMethod get">POST</span> https://app.digiebot.com/admin/api_services/login_process</code></pre>
	<label>Response</label>
	<strong>200</strong>
	<pre style="overflow: visible;">
			<code style="margin-left: -160px;">
{
 "status": true,
   "data": {
    "logged_in": true,
    "admin_id": "6",
    "first_name": "waqar",
    "last_name": "irshad",
    "username": "waqar",
    "email_address": "khan.waqar278@gmail.com",
    "check_api_settings": "yes",
    "global_symbol": "NCASHBTC",
    "app_mode": "both",
    "leftmenu": "1",
    "user_role": "2",
    "google_auth": "yes",
    "google_auth_code": "E75RFIYQ36QY3K63",
    "global_mode": "live"
   },
    "message": "Login successfully."
}
				</code>
			</pre>
	</div>
	</div>
	</div>
	</div>
</section>

<section id="documenter-3" class="method">
	<div class="method-area">
	<div class="method-copy">
	<div class="method-copy-padding">
	<h3>Login Verification</h3>
	<p>
		After Login Attempt We Prompt User to verify is the target account is he own or not, For this purpose we use Google Verification/Email Verification
		<div class="method-list attributes">
			<h5 class="method-list-title"> Attributes </h5>
			<ul class="method-list-group">
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			type
			</h6>
			<p class="method-list-item-description">
			The type of error returned. Can be: <code class="  language-undefined">api_connection_error</code>, <code class="  language-undefined">api_error</code>, <code class="  language-undefined">authentication_error</code>, <code class="  language-undefined">card_error</code>, <code class="  language-undefined">invalid_request_error</code>, or <code class="  language-undefined">rate_limit_error</code>.
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Request Type:
			</h6>
			<p class="method-list-item-description">
			POST
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Params
			<span class="method-list-item-label-details">Important</span>
			</h6>
			<p class="method-list-item-description">
			1. type <br>
			2. code<br>
			3. admin_id<br>
			</p>
			</li>
			</ul>
		</div>
	</p>
	</div>
	</div>
	<div class="method-example">
	<div class="method-example-part">
	<h5>Login Verification</h5>
	</div>
	<div class="method-example-part">
	<div class="method-example-endpoint">
	<pre class=" language-none"><code class=" language-none"><span class="destinationMethod get">POST</span> https://app.digiebot.com/admin/api_services/verification_code</code></pre>
	<label>Response</label>
	<strong>200</strong>
	<pre style="overflow: visible;">
			<code style="margin-left: -160px;">
{
 "status": true,
   "data": {
   		"msg" : "Code Matched"
   },
    "message": "Verification Success."
}
				</code>
			</pre>
	</div>
	</div>
	</div>
	</div>
</section>

<section id="documenter-4" class="method">
	<div class="method-area">
	<div class="method-copy">
	<div class="method-copy-padding">
	<h3>Fetch All Coins</h3>
	<p>
		The Following Api Give Response for fetching all the coin pair selected by the user
		<div class="method-list attributes">
			<h5 class="method-list-title"> Attributes </h5>
			<ul class="method-list-group">
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			type
			</h6>
			<p class="method-list-item-description">
			The type of error returned. Can be: <code class="  language-undefined">api_connection_error</code>, <code class="  language-undefined">api_error</code>, <code class="  language-undefined">authentication_error</code>, <code class="  language-undefined">card_error</code>, <code class="  language-undefined">invalid_request_error</code>, or <code class="  language-undefined">rate_limit_error</code>.
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Request Type:
			</h6>
			<p class="method-list-item-description">
			POST
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Params
			<span class="method-list-item-label-details">Important</span>
			</h6>
			<p class="method-list-item-description">
				1. admin_id
			</p>
			</li>
			</ul>
		</div>
	</p>
	</div>
	</div>
	<div class="method-example">
	<div class="method-example-part">
	<h5>Fetch Coins</h5>
	</div>
	<div class="method-example-part">
	<div class="method-example-endpoint">
	<pre class=" language-none"><code class=" language-none"><span class="destinationMethod get">POST</span> https://app.digiebot.com/admin/api_services/get_all_coins</code></pre>
	<label>Response</label>
	<strong>200</strong>
	<pre style="overflow: visible;">
			<code style="margin-left: -160px;">
{
    "status": true,
    "data": {
        "NCASHBTC": {
            "symbol": "NCASHBTC",
            "logo": "ncash.jpg",
            "balance": "6680.17",
            "usd_amount": 0.00516,
            "last_price": "0.00000080",
            "trade": 0,
            "price_change": "0.00000009",
            "percentage_change": "11.25",
            "score": 78
        },
        "BCNBTC": {
            "symbol": "BCNBTC",
            "logo": "flat,800x800,070,f.u2.jpg",
            "balance": "0",
            "usd_amount": 0.00194,
            "last_price": "0.00000030",
            "trade": 0,
            "price_change": "0.00000002",
            "percentage_change": "6.67",
            "score": 0
        },
    },
    "message": "Coins Fetched successfully."
}
				</code>
			</pre>
	</div>
	</div>
	</div>
	</div>
</section>

<section id="documenter-5" class="method">
	<div class="method-area">
	<div class="method-copy">
	<div class="method-copy-padding">
	<h3>Fetch Current Market Value</h3>
	<p>
		The Following Api Give Response for fetching the current market value of Selected Coin
		<div class="method-list attributes">
			<h5 class="method-list-title"> Attributes </h5>
			<ul class="method-list-group">
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			type
			</h6>
			<p class="method-list-item-description">
			The type of error returned. Can be: <code class="  language-undefined">api_connection_error</code>, <code class="  language-undefined">api_error</code>, <code class="  language-undefined">authentication_error</code>, <code class="  language-undefined">card_error</code>, <code class="  language-undefined">invalid_request_error</code>, or <code class="  language-undefined">rate_limit_error</code>.
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Request Type:
			</h6>
			<p class="method-list-item-description">
			POST
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Params
			<span class="method-list-item-label-details">Important</span>
			</h6>
			<p class="method-list-item-description">
				1. symbol
			</p>
			</li>
			</ul>
		</div>
	</p>
	</div>
	</div>
	<div class="method-example">
	<div class="method-example-part">
	<h5>Fetch Current Market Value</h5>
	</div>
	<div class="method-example-part">
	<div class="method-example-endpoint">
	<pre class=" language-none"><code class=" language-none"><span class="destinationMethod get">POST</span> https://app.digiebot.com/admin/api_services/get_coin_current_market_value</code></pre>
	<label>Response</label>
	<strong>200</strong>
	<pre style="overflow: visible;">
			<code style="margin-left: -160px;">
{
    "status": true,
    "data": {
        "market_value": "0.00000030"
    },
    "message": "Order Fetched successfully."
}
				</code>
			</pre>
	</div>
	</div>
	</div>
	</div>
</section>

<section id="documenter-6" class="method">
	<div class="method-area">
	<div class="method-copy">
	<div class="method-copy-padding">
	<h3>Fetch All Orders</h3>
	<p>
		The Following Api Give Response for fetching all the orders
		<div class="method-list attributes">
			<h5 class="method-list-title"> Attributes </h5>
			<ul class="method-list-group">
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			type
			</h6>
			<p class="method-list-item-description">
			The type of error returned. Can be: <code class="  language-undefined">api_connection_error</code>, <code class="  language-undefined">api_error</code>, <code class="  language-undefined">authentication_error</code>, <code class="  language-undefined">card_error</code>, <code class="  language-undefined">invalid_request_error</code>, or <code class="  language-undefined">rate_limit_error</code>.
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Request Type:
			</h6>
			<p class="method-list-item-description">
			POST
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Params
			<span class="method-list-item-label-details">Important</span>
			</h6>
			<p class="method-list-item-description">
				1. symbol
			</p>
			</li>
			</ul>
		</div>
	</p>
	</div>
	</div>
	<div class="method-example">
	<div class="method-example-part">
	<h5>Fetch All Orders</h5>
	</div>
	<div class="method-example-part">
	<div class="method-example-endpoint">
	<pre class=" language-none"><code class=" language-none"><span class="destinationMethod get">POST</span> https://app.digiebot.com/admin/api_services/get_orders</code></pre>
	<label>Response</label>
	<strong>200</strong>
	<pre style="overflow: visible;">
			<code style="margin-left: -160px;">
{
    "status": true,
    "data": [
        {
            "_id": {
                "$oid": "5b98815a819e12330428ea7f"
            },
            "symbol": "NCASHBTC",
            "binance_order_id": null,
            "price": 7.49e-7,
            "quantity": 20001,
            "order_type": "MARKET_ORDER",
            "market_value": 7.1e-7,
            "trail_check": "no",
            "trail_interval": "0",
            "buy_trail_price": "0",
            "status": "FILLED",
            "is_sell_order": "sold",
            "market_sold_price": 7.9e-7,
            "sell_order_id": {
                "$oid": "5b98ffef819e125f1c160651"
            },
            "admin_id": "1",
            "application_mode": "test",
            "created_date": "2018-09-12 8:00:00 AM"
        },
        {
            "_id": {
                "$oid": "5b98815a819e12330428ea7d"
            },
            "symbol": "NCASHBTC",
            "binance_order_id": null,
            "price": 7.49e-7,
            "quantity": 20001,
            "order_type": "MARKET_ORDER",
            "market_value": 7.1e-7,
            "trail_check": "no",
            "trail_interval": "0",
            "buy_trail_price": "0",
            "status": "FILLED",
            "is_sell_order": "sold",
            "market_sold_price": 7.9e-7,
            "sell_order_id": {
                "$oid": "5b98ffef819e125f1c16064e"
            },
            "admin_id": "1",
            "application_mode": "test",
            "created_date": "2018-09-12 8:00:00 AM"
        },
    ],
    "message": "Orders Fetched successfully."
}
				</code>
			</pre>
	</div>
	</div>
	</div>
	</div>
</section>

<section id="documenter-7" class="method">
	<div class="method-area">
	<div class="method-copy">
	<div class="method-copy-padding">
	<h3>Fetch Order Detail</h3>
	<p>
		The Following Api Give Response for fetching the current market value of Selected Coin
		<div class="method-list attributes">
			<h5 class="method-list-title"> Attributes </h5>
			<ul class="method-list-group">
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			type
			</h6>
			<p class="method-list-item-description">
			The type of error returned. Can be: <code class="  language-undefined">api_connection_error</code>, <code class="  language-undefined">api_error</code>, <code class="  language-undefined">authentication_error</code>, <code class="  language-undefined">card_error</code>, <code class="  language-undefined">invalid_request_error</code>, or <code class="  language-undefined">rate_limit_error</code>.
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Request Type:
			</h6>
			<p class="method-list-item-description">
			POST
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Params
			<span class="method-list-item-label-details">Important</span>
			</h6>
			<p class="method-list-item-description">
				1. id
			</p>
			</li>
			</ul>
		</div>
	</p>
	</div>
	</div>
	<div class="method-example">
	<div class="method-example-part">
	<h5>Fetch Order Detail</h5>
	</div>
	<div class="method-example-part">
	<div class="method-example-endpoint">
	<pre class=" language-none"><code class=" language-none"><span class="destinationMethod get">POST</span> https://app.digiebot.com/admin/api_services/get_buy_order</code></pre>
	<label>Response</label>
	<strong>200</strong>
	<pre style="overflow: visible;">
			<code style="margin-left: -160px;">
{
    "status": true,
    "data": {
        "_id": {
            "$oid": "5b98815a819e12330428ea7d"
        },
        "symbol": "NCASHBTC",
        "binance_order_id": null,
        "price": 7.49e-7,
        "quantity": 20001,
        "market_value": 7.1e-7,
        "order_type": "MARKET_ORDER",
        "status": "FILLED",
        "admin_id": "1",
        "trail_check": "no",
        "trail_interval": "0",
        "is_sell_order": "sold",
        "sell_order_id": {
            "$oid": "5b98ffef819e125f1c16064e"
        },
        "auto_sell": "no",
        "application_mode": "test",
        "created_date": "2018-09-12 8:00:00 AM",
        "profit_data": "11.25"
    },
    "message": "Order Fetched successfully."
}
				</code>
			</pre>
	</div>
	</div>
	</div>
	</div>
</section>

<section id="documenter-8" class="method">
	<div class="method-area">
	<div class="method-copy">
	<div class="method-copy-padding">
	<h3>Add Digie Manual Order</h3>
	<p>
		The Following Api Give Response for submitting Manual Order
		<div class="method-list attributes">
			<h5 class="method-list-title"> Attributes </h5>
			<ul class="method-list-group">
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			type
			</h6>
			<p class="method-list-item-description">
			The type of error returned. Can be: <code class="  language-undefined">api_connection_error</code>, <code class="  language-undefined">api_error</code>, <code class="  language-undefined">authentication_error</code>, <code class="  language-undefined">card_error</code>, <code class="  language-undefined">invalid_request_error</code>, or <code class="  language-undefined">rate_limit_error</code>.
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Request Type:
			</h6>
			<p class="method-list-item-description">
			POST
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Params
			<span class="method-list-item-label-details">Important</span>
			</h6>
			<p class="method-list-item-description">
				1. price <br>
				2. quantity <br>
				3. coin <br>
				4. order_type <br>
				5. admin_id <br>
				6. application_mode <br>
				7. trail_interval(optional) <br>
				8. trail_check (optional)<br>
				9. auto_sell (optional)<br>
			</p>
			</li>
			</ul>
		</div>
	</p>
	</div>
	</div>
	<div class="method-example">
	<div class="method-example-part">
	<h5>Submit Manual Order</h5>
	</div>
	<div class="method-example-part">
	<div class="method-example-endpoint">
	<pre class=" language-none"><code class=" language-none"><span class="destinationMethod get">POST</span> https://app.digiebot.com/admin/api_services/add_digie_manual_order</code></pre>
	<label>Response</label>
	<strong>200</strong>
	<pre style="overflow: visible;">
			<code style="margin-left: -160px;">
{
    "status": true,
    "data": {
       "_id": {
            "$oid": "5b98815a819e12330428ea7d"
        },
    },
    "message": "Order Submitted successfully."
}
				</code>
			</pre>
	</div>
	</div>
	</div>
	</div>
</section>

<section id="documenter-9" class="method">
	<div class="method-area">
	<div class="method-copy">
	<div class="method-copy-padding">
	<h3>Submit Auto Bot Order</h3>
	<p>
		The Following Api Give Response for fetching the current market value of Selected Coin
		<div class="method-list attributes">
			<h5 class="method-list-title"> Attributes </h5>
			<ul class="method-list-group">
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			type
			</h6>
			<p class="method-list-item-description">
			The type of error returned. Can be: <code class="  language-undefined">api_connection_error</code>, <code class="  language-undefined">api_error</code>, <code class="  language-undefined">authentication_error</code>, <code class="  language-undefined">card_error</code>, <code class="  language-undefined">invalid_request_error</code>, or <code class="  language-undefined">rate_limit_error</code>.
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Request Type:
			</h6>
			<p class="method-list-item-description">
			POST
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Params
			<span class="method-list-item-label-details">Important</span>
			</h6>
			<p class="method-list-item-description">
				1.order_mode <br>
				2.quantity <br>
				3.coin <br>
				4.admin_id <br>
				5.inactive_time_new (optional) <br>
				6.trigger_type <br>
				7.application_mode <br>
			</p>
			</li>
			</ul>
		</div>
	</p>
	</div>
	</div>
	<div class="method-example">
	<div class="method-example-part">
	<h5>Submit Auto Bot Order</h5>
	</div>
	<div class="method-example-part">
	<div class="method-example-endpoint">
	<pre class=" language-none"><code class=" language-none"><span class="destinationMethod get">POST</span> https://app.digiebot.com/admin/api_services/add_digie_trigger_order</code></pre>
	<label>Response</label>
	<strong>200</strong>
	<pre style="overflow: visible;">
			<code style="margin-left: -160px;">
{
    "status": true,
    "data": {
       "_id": {
            "$oid": "5b98815a819e12330428ea7d"
        },
    },
    "message": "Order Submitted successfully."
}
				</code>
			</pre>
	</div>
	</div>
	</div>
	</div>
</section>



<!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

	<section id="documenter-9" class="method">
	<div class="method-area">
	<div class="method-copy">
	<div class="method-copy-padding">

	<h3>Submit Trading Trends</h3>
	<p>
		The Api use for sending trading trends
		<div class="method-list attributes">
			<h5 class="method-list-title"> Attributes </h5>
			<ul class="method-list-group">
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			type
			</h6>
			<p class="method-list-item-description">
			The type of error returned. Can be: <code class="  language-undefined">api_connection_error</code>
			<code class="language-undefined">api_connection_error</code>			
			, <code class="bad-request">bad_request</code>, <code class="  language-undefined">authentication_error</code>, <code class="  language-undefined">parameter_error</code>, <code class="  language-undefined">invalid_request_error</code>
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Request Type:
			</h6>
			<p class="method-list-item-description">
			POST
			</p>
			</li>
			<li class="method-list-item">
			<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
			Params
			<span class="method-list-item-label-details">Important</span>
			</h6>

			<div class="resourceGroupDescription markdown formalTheme">
				<p>Represents user details.</p>
				<hr>
				<p><strong>User attributes:</strong></p>
				<ul>
					<li>
						<p>coin <code>(String)</code> : coin symbol(*).</p>
					</li>
					<li>
						<p>market_trend <code>(String)</code> :market trend.</p>
					</li>
					
					<li>
						<p>caption_option <code>(Float)</code> : caption option.</p>
					</li>
					<li>
						<p>meta_trading <code>(Float)</code> : meta trading.</p>
					</li>

					<li>
						<p>demand <code>(String)</code> : demand candle.</p>
					</li>
					<li>
						<p>supply <code>(String)</code> : supply candle.</p>
					</li>
					<li>
						<p>caption_score <code>(Float)</code> :caption score.</p>
					</li>

					<li>
						<p>riskpershare <code>(Float)</code> :riskpershare value.</p>
					</li>


					<li>
						<p>buy <code>(Float)</code> :buy value.</p>
					</li>

					<li>
						<p>sell <code>(Float)</code> :sell value.</p>
					</li>

					<li>
						<p>RL <code>(Float)</code> :RL value.</p>
					</li>


					<li>
						<p>long_term_intension <code>(string)</code> value.</p>
					</li>


					<li>
						<p>previous_state <code>(string)</code> : (POSITIVE,NEGATIVE)</p>
					</li>

					<li>
						<p>range_30
						<br>
						range_40
						<br>
						range_50
						<br>
						range_60
						<br>
						range_70
						<br>
						 <code>(Float)</code> :(float).</p>
					</li>

					<li>
						<p>deep_value <code>(Float)</code> :(any float).</p>
					</li>
					
				</ul>
				<hr>    
			</div>

		
			</li>
			</ul>
		</div>
	</p>
	</div>
	</div>
	<div class="method-example">
	<div class="method-example-part">
	<h5>Submit Trading Trends</h5>
	</div>
	<div class="method-example-part">
	<div class="method-example-endpoint">
	<pre class=" language-none"><code class=" language-none"><span class="destinationMethod get">POST</span> https://app.digiebot.com/admin/api_trend_setting/post_market_trends</code></pre>
	<label>Response</label>
	<strong>200</strong>
	<pre style="overflow: visible;">
			<code style="margin-left: -160px;">
{
    "status": true,
    "data": {
       "_id": {
            "$oid": "5b98815a819e12330428ea7d"
        },
    },
    "message": "Trending  Submitted successfully."
}
				</code>
			</pre>
	</div>
	</div>
	</div>
	</div>
</section>



<!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->



<!-- %%%%%%%%%%%%%%%%%%%%% Percentile trigger setting %%%%%%%%%%%%%%%% -->
	<section id="documenter-9" class="method">
		<div class="method-area">
			<div class="method-copy">
				<div class="method-copy-padding">

					<h3>Submit Percentile Trigger Setting </h3>
					<p>
						The Api use for sending percentile trigger setting
						<div class="method-list attributes">
							<h5 class="method-list-title"> Attributes </h5>
							<ul class="method-list-group">
							<li class="method-list-item">
							<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
							type
							</h6>
							<p class="method-list-item-description">
							The type of error returned. Can be: <code class="  language-undefined">api_connection_error</code>
							<code class="language-undefined">api_connection_error</code>			
							, <code class="bad-request">bad_request</code>, <code class="  language-undefined">authentication_error</code>, <code class="  language-undefined">parameter_error</code>, <code class="  language-undefined">invalid_request_error</code>
							, <code class="  language-undefined">
								Trigger Name Rquired
							</code>
							, <code class="  language-undefined">
								Level Rquired
							</code>
							, <code class="  language-undefined">
								coin Rquired
							</code>
							, <code class="  language-undefined">
								type Rquired
							</code>
							, <code class="  language-undefined">
								trading mode required
							</code>
							, <code class="  language-undefined">
								At least one parameter Rquired
							</code>
							</p>
							</li>
							<li class="method-list-item">
							<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
							Request Type:
							</h6>
							<p class="method-list-item-description">
							POST
							</p>
							</li>
							<li class="method-list-item">
							<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
							Params
							<span class="method-list-item-label-details">Important</span>
							</h6>

							<div class="resourceGroupDescription markdown formalTheme">
								<p>Represents user details.</p>
								<hr>
								<p><strong>User attributes:</strong></p>
								<ul>
									<li>
										<p>trigger_type <code>(String)</code> : Trigger name.(<b style="color:red">*</b>)</p>
									</li>
									<li>
										<p>level <code>(String)</code> :values range(1 to 10).(<b style="color:red">*</b>)</p>
									</li>
									
									<li>
										<p>coin <code>(String)</code> :coin name.(<b style="color:red">*</b>)</p>
									</li>
									<li>
										<p>trading_mode <code>(String)</code> :value(live,test,test_simulator).(<b style="color:red">*</b>)</p>
									</li>

									<li>
										<p>type <code>(String)</code> :trade type(buy,sell,stop_loss).(<b style="color:red">*</b>)</p>
									</li>

									<li>
										<p>rule_active <code>(String)</code> :rule values(ON,OFF).(<b style="color:red">*</b>)</p>
									</li>
									<li>
										<p>previous_candle_status <code>(String)</code> :value('ON','OFF').</p>
									</li>
									<li>
										<p>previous_candle_type <code>(String)</code> :values('normal','demand','supply').</p>
									</li>

									<li>
										<p>barrier_range_percentage <code>(Float)</code> :barrier range percentage.</p>
									</li>


									<li>
										<p>black_wall_active <code>(String)</code> :values('ON','OFF').</p>
									</li>

									<li>
										<p>black_wall_percentile_val <code>(number)</code> :values(1 to 10).</p>
									</li>

									<li>
										<p>virtural_support_barrier_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>virtural_support_barrier_val <code>(number)</code> :values(1 to 10).</p>
									</li>
									<li>
										<p>virtural_resistance_barrier_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>virtual_resistance_barrier_val <code>(number)</code> :values(1 to 10).</p>
									</li>


									<li>
										<p>seven_level_pressure_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>seven_level_pressure_val <code>(number)</code> :values(1 to 10).</p>
									</li>


									<li>
										<p>last_200_buy_vs_sell_contract_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>last_200_buy_vs_sell_contract_val <code>(number)</code> :values(1 to 10).</p>
									</li>


									<li>
										<p>last_200_contracts_time_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>last_200_contracts_time_val <code>(number)</code> :values(1 to 10).</p>
									</li>


									<li>
										<p>last_qty_contracts_buy_vs_sellers_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>last_qty_contracts_buy_vs_sellers_val <code>(number)</code> :values(1 to 10).</p>
									</li>


									<li>
										<p>last_qty_contracts_time_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>last_qty_contracts_time_val <code>(number)</code> :values(1 to 10).</p>
									</li>

									<li>
										<p>five_minute_rolling_candle_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>five_minute_rolling_candle_val <code>(number)</code> :values(1 to 10).</p>
									</li>


									<li>
										<p>rolling_candle_15_m_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>rolling_candle_15_m_val <code>(number)</code> :values(1 to 10).</p>
									</li>


									<li>
										<p>sellers_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>sellers_val <code>(number)</code> :values(1 to 10).</p>
									</li>


									<li>
										<p>buyers_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>buyers_val <code>(number)</code> :values(1 to 10).</p>
									</li>


									<li>
										<p>last_time_ago_15_m_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>last_time_ago_15_m_val <code>(number)</code> :values(1 to 10).</p>
									</li>

									<li>
										<p>ask_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>ask_val <code>(number)</code> :values(1 to 10).</p>
									</li>

									<li>
										<p>bid_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>bid_val <code>(number)</code> :values(1 to 10).</p>
									</li>


									<li>
										<p>buy_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>buy_val <code>(number)</code> :values(1 to 10).</p>
									</li>

									<li>
										<p>sell_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>sell_val <code>(number)</code> :values(1 to 10).</p>
									</li>


									<li>
										<p>ask_contracts_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>ask_contracts_val <code>(number)</code> :values(1 to 10).</p>
									</li>


									<li>
										<p>bid_contracts_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>bid_contracts_val <code>(number)</code> :values(1 to 10).</p>
									</li>



									<li>
										<p>caption_option_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>caption_option_val <code>(number)</code> :values(1 to 10).</p>
									</li>


									<li>
										<p>caption_score_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>caption_score_val <code>(number)</code> :values(1 to 10).</p>
									</li>


									<li>
										<p>buy_market_trend_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>buy_market_trend_val <code>(number)</code> :values(1 to 10).</p>
									</li>

									<li>
										<p>sell_market_trend_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>sell_market_trend_val <code>(number)</code> :values(1 to 10).</p>
									</li>



									<li>
										<p>demand_market_trend_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>demand_market_trend_val <code>(number)</code> :values(1 to 10).</p>
									</li>




									<li>
										<p>supply_market_trend_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>supply_market_trend_val <code>(number)</code> :values(1 to 10).</p>
									</li>

									<li>
										<p>market_trend_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>market_trend_val <code>(number)</code> (POSITIVE,NEGATIVE) .</p>
									</li>

									<li>
										<p>meta_trading_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>meta_trading_val <code>(number)</code> :values(1 to 10).</p>
									</li>


									<li>
										<p>riskpershare_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>riskpershare_val <code>(number)</code> :values(1 to 10).</p>
									</li>


									<li>
										<p>RL_active <code>(String)</code> :values('ON','OFF').</p>
									</li>
									<li>
										<p>RL_val <code>(number)</code> :values(1 to 10).</p>
									</li>

								</ul>
								<hr>    
							</div>

						
							</li>
							</ul>
						</div>
					</p>
				</div>
			</div>
			<div class="method-example">
				<div class="method-example-part">
					<h5>Submit Percentile Setting</h5>
				</div>
				<div class="method-example-part">
					<div class="method-example-endpoint">
						<pre class=" language-none"><code class=" language-none"><span class="destinationMethod get">POST</span> https://app.digiebot.com/admin/api_trigger_setting/api_percentile_setting</code></pre>
						<label>Response</label>
						<strong>200</strong>
							<pre style="overflow: visible;">
									<code style="margin-left: -160px;">
										{
												"status": true,
												"message": "Setting  Submitted successfully."
											}
									</code>
								</pre>
					</div>
				</div>
			</div>
		</div>
   </section>
<!-- %%%%%%%%%%%%%%%%%%%%% End of Percentile trigger setting %%%%%%%%%%%%%%%% -->









<!-- %%%%%%%%%%%%%%%%%%%%% barrier trigger setting %%%%%%%%%%%%%%%% -->
<section id="documenter-9" class="method">
		<div class="method-area">
			<div class="method-copy">
				<div class="method-copy-padding">

					<h3>Submit barrier Trigger Setting </h3>
					<p>
						The Api use for sending barrier trigger setting
						<div class="method-list attributes">
							<h5 class="method-list-title"> Attributes </h5>
							<ul class="method-list-group">
							<li class="method-list-item">
							<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
							type
							</h6>
							<p class="method-list-item-description">
							The type of error returned. Can be: <code class="  language-undefined">api_connection_error</code>
							<code class="language-undefined">api_connection_error</code>			
							, <code class="bad-request">bad_request</code>, <code class="  language-undefined">authentication_error</code>, <code class="  language-undefined">parameter_error</code>, <code class="  language-undefined">invalid_request_error</code>
							, <code class="  language-undefined">
								Trigger Name Rquired
							</code>
							, <code class="  language-undefined">
								Rule no Rquired
							</code>
							, <code class="  language-undefined">
								coin Rquired
							</code>
							, <code class="  language-undefined">
								type Rquired
							</code>
							, <code class="  language-undefined">
								trading mode required
							</code>
							, <code class="  language-undefined">
								At least one parameter Rquired
							</code>

							, <code class="  language-undefined">
								Format incorrect
							</code>
							, <code class="  language-undefined">
								value incorrect
							</code>
							</p>
							</li>
							<li class="method-list-item">
							<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
							Request Type:
							</h6>
							<p class="method-list-item-description">
							POST
							</p>
							</li>
							<li class="method-list-item">
							<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
							Params
							<span class="method-list-item-label-details">Important</span>
							</h6>

							<div class="resourceGroupDescription markdown formalTheme">
								<p>Represents user details.</p>
								<hr>
								<p><strong>User attributes:</strong></p>
								<ul>
									<li>
										<p>trigger_type <code>(String)</code> : Trigger name.(<b style="color:red">*</b>)</p>
									</li>
									<li>
										<p>rule_no <code>(Numercie)</code> :values range(1 to 10).(<b style="color:red">*</b>)</p>
									</li>
									
									<li>
										<p>coin <code>(String)</code> :coin name.(<b style="color:red">*</b>)</p>
									</li>
									<li>
										<p>trading_mode <code>(String)</code> :value(live,test,test_simulator).(<b style="color:red">*</b>)</p>
									</li>

									<li>
										<p>type <code>(String)</code> :trade type(buy,sell,stop_loss).(<b style="color:red">*</b>)</p>
									</li>

									<li>
										<p>rule_active <code>(String)</code> :rule values(ON,OFF).(<b style="color:red">*</b>)</p>
									</li>

									<li>
										<p>swing_point_status_active <code>(String)</code> :rule values(ON,OFF).(<b style="color:red">*</b>)</p>
									</li>

									<li>
										<p>swing_point_status <code>(String)</code> :rule values('LL','LH','HL','HH').(<b style="color:red">*</b>)</p>
									</li>


									<li>
										<p>buy_range_percet <code>(numeric)</code> :rule values(any numerice value).(<b style="color:red">*</b>)</p>
									</li>
									

									<li>
										<p>follow_behind_current_market_percentage <code>(numeric)</code> :rule values(any numerice value).(<b style="color:red">*</b>)</p>
									</li>

									<li>
										<p>stop_loss_percet <code>(numeric)</code> :rule values(any numerice value).(<b style="color:red">*</b>)</p>
									</li>

									<li>
										<p>virtual_barrier_active <code>(String)</code> :rule values(ON,OFF).(<b style="color:red">*</b>)</p>
									</li>
									
									<li>
										<p>virtual_barrier_value <code>(numeric)</code> :rule values(any numerice value).(<b style="color:red">*</b>)</p>
									</li>

									<li>
										<p>resistance_barrier_active <code>(String)</code> :rule values(ON,OFF).(<b style="color:red">*</b>)</p>
									</li>
									
									<li>
										<p>resistance_barrier_value <code>(numeric)</code> :rule values(any numerice value).(<b style="color:red">*</b>)</p>
									</li>

									<li>
										<p>volume_active <code>(String)</code> :rule values(ON,OFF).(<b style="color:red">*</b>)</p>
									</li>
									
									<li>
										<p>volume_value <code>(numeric)</code> :rule values(any numerice value).(<b style="color:red">*</b>)</p>
									</li>


									<li>
										<p>down_pressure_active <code>(String)</code> :rule values(ON,OFF).(<b style="color:red">*</b>)</p>
									</li>
									
									<li>
										<p>down_pressure_value <code>(numeric)</code> :rule values(any numerice value).(<b style="color:red">*</b>)</p>
									</li>

									

									<li>
										<p>big_buyers_active <code>(String)</code> :rule values(ON,OFF).(<b style="color:red">*</b>)</p>
									</li>
									
									<li>
										<p>big_buyers_value <code>(numeric)</code> :rule values(any numerice value).(<b style="color:red">*</b>)</p>
									</li>



									<li>
										<p>black_wall_active <code>(String)</code> :rule values(ON,OFF).(<b style="color:red">*</b>)</p>
									</li>
									
									<li>
										<p>black_wall_value <code>(numeric)</code> :rule values(any numerice value).(<b style="color:red">*</b>)</p>
									</li>



									<li>
										<p>yellow_wall_active <code>(String)</code> :rule values(ON,OFF).(<b style="color:red">*</b>)</p>
									</li>
									
									<li>
										<p>yellow_wall_value <code>(numeric)</code> :rule values(any numerice value).(<b style="color:red">*</b>)</p>
									</li>


									<li>
										<p>seven_level_active <code>(String)</code> :rule values(ON,OFF).(<b style="color:red">*</b>)</p>
									</li>
									
									<li>
										<p>seven_level_value <code>(numeric)</code> :rule values(any numerice value).(<b style="color:red">*</b>)</p>
									</li>



									<li>
										<p>buyers_vs_sellers_active <code>(String)</code> :rule values(ON,OFF).(<b style="color:red">*</b>)</p>
									</li>
									
									<li>
										<p>buyers_vs_sellers_value <code>(numeric)</code> :rule values(any numerice value).(<b style="color:red">*</b>)</p>
									</li>



									<li>
										<p>last_candle_type_active <code>(String)</code> :rule values(ON,OFF).(<b style="color:red">*</b>)</p>
									</li>
									
									<li>
										<p>last_candle_type_value <code>(numeric)</code> :rule values(any numerice value).(<b style="color:red">*</b>)</p>
									</li>


									<li>
										<p>rejection_candle_active <code>(String)</code> :rule values(ON,OFF).(<b style="color:red">*</b>)</p>
									</li>
									
									<li>
										<p>rejection_candle_value <code>(string)</code> :rule values('top_demand_rejection','bottom_demand_rejection','top_supply_rejection','bottom_supply_rejection','no_rejection').(<b style="color:red">*</b>)</p>
									</li>
									
										

									<li>
										<p>last_200_contracts_active <code>(String)</code> :rule values(ON,OFF).</p>
									</li>
									
									<li>
										<p>last_200_contracts_value <code>(numeric)</code> :rule values(any numerice value).</p>
									</li>
									
									
									<li>
										<p>last_200_contracts_time_active <code>(String)</code> :rule values(ON,OFF).</p>
									</li>
									
									<li>
										<p>last_200_contracts_time_value <code>(numeric)</code> :rule values(any numerice value).</p>
									</li>


									<li>
										<p>last_qty_contracts_active <code>(String)</code> :rule values(ON,OFF).</p>
									</li>
									
									<li>
										<p>last_qty_contracts_value <code>(numeric)</code> :rule values(any numerice value).</p>
									</li>
									

									<li>
										<p>last_qty_time_active <code>(String)</code> :rule values(ON,OFF).</p>
									</li>
									
									<li>
										<p>last_qty_time_value <code>(numeric)</code> :rule values(any numerice value).</p>
									</li>


									<li>
										<p>score_active <code>(String)</code> :rule values(ON,OFF).</p>
									</li>
									
									<li>
										<p>score_value <code>(numeric)</code> :rule values(any numerice value).</p>
									</li>


									<li>
										<p>comment_active <code>(String)</code> :rule values(ON,OFF).</p>
									</li>
									
									<li>
										<p>comment_value <code>(numeric)</code> :rule values(any numerice value).</p>
									</li>


									<li>
										<p>level_active <code>(String)</code> :rule values(ON,OFF).</p>
									</li>
									
									<li>
										<p>level_value <code>(numeric)</code> :rule values(any numerice value).</p>
									</li>

									<li>
										<p>rule_sorting_active <code>(String)</code> :rule values(ON,OFF).</p>
									</li>
									
									<li>
										<p>rule_sorting_value <code>(numeric)</code> :rule values(any numerice value).</p>
									</li>



									<li>
										<p>buyers_vs_sellers_15_m_active <code>(String)</code> :rule values(ON,OFF).</p>
									</li>
									
									<li>
										<p>buyers_vs_sellers_15_m_value <code>(numeric)</code> :rule values(any numerice value).</p>
									</li>


									<li>
										<p>ask_percentile_active <code>(String)</code> :rule values(ON,OFF).</p>
									</li>
									
									<li>
										<p>ask_percentile_value <code>(numeric)</code> :rule values(any numerice value).</p>
									</li>



									<li>
										<p>bid_percentile_active <code>(String)</code> :rule values(ON,OFF).</p>
									</li>
									
									<li>
										<p>bid_percentile_value <code>(numeric)</code> :rule values(any numerice value).</p>
									</li>



									<li>
										<p>buy_percentile_active <code>(String)</code> :rule values(ON,OFF).</p>
									</li>
									
									<li>
										<p>buy_percentile_value <code>(numeric)</code> :rule values(any numerice value).</p>
									</li>


									<li>
										<p>sell_percentile_active <code>(String)</code> :rule values(ON,OFF).</p>
									</li>
									
									<li>
										<p>sell_percentile_value <code>(numeric)</code> :rule values(any numerice value).</p>
									</li>


								</ul>
								<hr>    
							</div>

						
							</li>
							</ul>
						</div>
					</p>
				</div>
			</div>
			<div class="method-example">
				<div class="method-example-part">
					<h5>Submit Percentile Setting</h5>
				</div>
				<div class="method-example-part">
					<div class="method-example-endpoint">
						<pre class=" language-none"><code class=" language-none"><span class="destinationMethod get">POST</span> https://app.digiebot.com/admin/api_trigger_setting/api_barrier_setting</code></pre>
						<label>Response</label>
						<strong>200</strong>
							<pre style="overflow: visible;">
									<code style="margin-left: -160px;">
										{
												"status": true,
												"message": "Setting  Submitted successfully."
											}
									</code>
								</pre>
					</div>
				</div>
			</div>
		</div>
   </section>
<!-- %%%%%%%%%%%%%%%%%%%%% End of barrier trigger setting %%%%%%%%%%%%%%%% -->



<!-- %%%%%%%%%%%%%%% -- Get Candles -- %%%%%%%%%%%%%%%%% -->
<section id="documenter-9" class="method">
		<div class="method-area">
			<div class="method-copy">
				<div class="method-copy-padding">

					<h3>Get Candle Detail</h3>
					<p>
						The Api use for getting Candles detail
						<div class="method-list attributes">
							<h5 class="method-list-title"> Attributes </h5>
							<ul class="method-list-group">
							<li class="method-list-item">
							<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
							type
							</h6>
							<p class="method-list-item-description">
							The type of error returned. Can be: <code class="  language-undefined">api_connection_error</code>
							<code class="language-undefined">api_connection_error</code>			
							, <code class="bad-request">bad_request</code>, <code class="  language-undefined">authentication_error</code>, <code class="  language-undefined">parameter_error</code>, <code class="  language-undefined">invalid_request_error</code>
							, <code class="  language-undefined">
								Coin Name Rquired
							</code>
							, <code class="  language-undefined">
								Coin not registered with Digieboat
							</code>
							
							</p>
							</li>
							<li class="method-list-item">
							<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
							Request Type:
							</h6>
							<p class="method-list-item-description">
							POST
							</p>
							</li>
							<li class="method-list-item">
							<h6 class="method-list-item-label"><a href="#" class="header-anchor"></a>
							Params
							<span class="method-list-item-label-details">Important</span>
							</h6>

							<div class="resourceGroupDescription markdown formalTheme">
								<p>Candle Detail.</p>
								<hr>
								<p><strong>User attributes:</strong></p>
								<ul>
									<li>
										<p>coin <code>(String)</code> : coin name.(<b style="color:red">*</b>)</p>
									</li>
									<li>
										<p>Candel Date time <code>(date)</code> Consider as Gmt time.(<b style="color:red">*</b>)</p>
									</li>

								</ul>
								<hr>    
							</div>

						
							</li>
							</ul>
						</div>
					</p>
				</div>
			</div>
			<div class="method-example">
				<div class="method-example-part">
					<h5>Get candle detail</h5>
				</div>
				<div class="method-example-part">
					<div class="method-example-endpoint">
						<pre class=" language-none"><code class=" language-none"><span class="destinationMethod get">POST</span> https://app.digiebot.com/admin/api_trigger_setting/get_candles</code></pre>
						<label>Response</label>
						<strong>200</strong>
							<pre style="overflow: visible;">
									<code style="margin-left: -160px;">
										{
												"status": true,
												"message": "Response Arr"
											}
									</code>
								</pre>
					</div>
				</div>
			</div>
		</div>
   </section>
<!-- %%%%%%%%%%%%%% -- End of candles -- %%%%%%%%%%%%%%%% -->







<section id="documenter-10" class="method">
	<div class="method-area">
	<div class="method-copy">
	<div class="method-copy-padding">
	<h3>Error Codes</h3>
		Following are the expected error codes
	</div>
	</div>
	<div class="method-example">
	<div class="method-example-part">
	<h5>Error Codes</h5>
	</div>
	<div class="method-example-part">
	<pre class=" language-none"><code class=" language-none">No Data Found</code></pre>
	<br>
<div class="table-responsive">
<div class="table">
<table class="table-container">
<colgroup>
<col class="col-xs-3">
<col class="col-xs-9">
</colgroup>
<tbody>
<tr>
<th class="table-row-property">200 - OK</th>
<td class="table-row-definition">Request is Success.</td>
</tr>
<tr>
<th class="table-row-property">400 - Bad Request</th>
<td class="table-row-definition">The operation results in a duplicate key for a unique index.</td>
</tr>
<tr>
<th class="table-row-property">401 - Unauthorized</th>
<td class="table-row-definition">You did not provide authorization which is required for this operation..</td>
</tr>
<tr>
<th class="table-row-property">403 - Access denied.</th>
<td class="table-row-definition">You are not allowed to perform the operation.</td>
</tr>
<tr>
<th class="table-row-property">404 - Not Found</th>
<td class="table-row-definition">The specified item was not found.</td>
</tr>
<tr>
<th class="table-row-property">500, 502, 503, 504 - Server Errors</th>
<td class="table-row-definition">An error occurred during execution of custom server code.</td>
</tr>
</tbody>
</table>
</div>
</div>
	</div>
	</div>
	</div>
</section>
</div>
</div>

<div id="mobile-menu">
<div class="mobile-menu-inner">
<ul>
<li>
<div class="mm-search">
<form id="search1" name="search">
<div class="input-group">
<input type="text" class="form-control simple" placeholder="Search ..." name="srch-term" id="srch-term">
<div class="input-group-btn">
<button class="btn btn-default" type="submit"><i class="fa fa-search"></i> </button>
</div>
</div>
</form>
</div>
</li>
</div>
</div>
<script src="<?php echo ASSETS; ?>api_doc/js/prism.js"></script>
<script src="<?php echo ASSETS; ?>api_doc/js/jquery.1.6.4.js"></script>
<script src="<?php echo ASSETS; ?>api_doc/js/jquery.min.js"></script>
<script src="<?php echo ASSETS; ?>api_doc/js/jquery.scrollTo-1.4.2-min.js"></script>
<script src="<?php echo ASSETS; ?>api_doc/js/jquery.easing.js"></script>
<script>document.createElement('section');
         var duration = 500, easing = 'swing';
      </script>
<script src="<?php echo ASSETS; ?>api_doc/js/slides.min.jquery.js"></script>
<script src="<?php echo ASSETS; ?>api_doc/js/script.js"></script>
<script src="<?php echo ASSETS; ?>api_doc/js/scroll.js"></script>
<script src="<?php echo ASSETS; ?>api_doc/js/common.js"></script>
<script src="<?php echo ASSETS; ?>api_doc/js/jquery.mobile-menu.min.js"></script>
<script>
         ScrollLoad("scrollholder", "scroll", false);
      </script>
</body>

</html>