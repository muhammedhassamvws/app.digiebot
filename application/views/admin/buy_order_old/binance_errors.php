 <style>
.file {

  border: 1px solid #ddd;
  border-radius: 3px;
  margin-bottom: 16px;
  margin-top: 16px;
  position: relative;
  margin-left: 15%;
  margin-right: 15%;
}

article.markdown-body.entry-content {
    border: 0;
    border-radius: 0;
    padding: 45px;
}

.highlight.highlight-source-js {
    margin-bottom: 16px;
}

body ul {
    padding-left: 2em;
}

h2 {
    font-size: 1.5em;
}

h4 {
    font-size: 1.0em;
    font-weight: 600;
    line-height: 1.25;
    margin-bottom: 16px;
    margin-top: 24px;
}

h2 {
    font-size: 1.5 em;
    border-bottom: 1px solid #eaecef;
    padding-bottom: .3em;
}

table {
    display: block;
    overflow: auto;
    width: 100%;
    margin-bottom: 16px;
    margin-top: 0;
    border-collapse: collapse;
    border-spacing: 0;
}

tr {
    background-color: #fff;
    border-top: 1px solid #c6cbd1;
    font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Helvetica,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol;
    font-size: 16px;
    line-height: 1.5;
}

th {
    border: 1px solid #dfe2e5;
    padding: 6px 13px;
}

td {
    border: 1px solid #dfe2e5;
    padding: 6px 13px !important;
}

table tr:nth-child(2n) {
    background-color: #f6f8fa;
}
</style>

<div id="content">
  <h1 class="content-heading bg-white border-bottom">Digiebot Possible Errors</h1>

  <div class="bg-white innerAll border-bottom">
    <ul class="menubar">
        <li class="active"><a href="<?php echo SURL; ?>admin/buy_orders/get_errors_detail">Errors</a></li>
    </ul>
    <span class="fa fa-info-circle" style="float: right;font-size: 20px;margin-top: -25px;color: #cb4040;" data-toggle="popover" data-placement="left" data-trigger="hover" data-container="body" data-original-title="Buy Order Listing" data-content="Here in Buy order listing page every order is filtered by their current status, If you want to see the specific order look that in related tab, Moreover you can filter the orders by date, type and coin"></span>
  </div>

  <div class="innerAll spacing-x2">
    <div class="file ">
      <div id="readme" class="readme blob instapaper_body">
        <article class="markdown-body entry-content" itemprop="text"><h1><a id="user-content-error-codes-for-binance-2018-07-18" class="anchor" aria-hidden="true" href="#error-codes-for-binance-2018-07-18"></a>Error codes for Digiebot</h1>
    <p>Errors consist of two parts: an error code and a message. Codes are universal,
    but messages can vary. Here is the error JSON payload:</p>
    <div class="highlight highlight-source-js"><pre>{
      <span class="pl-s"><span class="pl-pds">"</span>code<span class="pl-pds">"</span></span><span class="pl-k">:</span><span class="pl-k">-</span><span class="pl-c1">1121</span>,
      <span class="pl-s"><span class="pl-pds">"</span>msg<span class="pl-pds">"</span></span><span class="pl-k">:</span><span class="pl-s"><span class="pl-pds">"</span>Invalid symbol.<span class="pl-pds">"</span></span>
    }</pre></div>
    <h2><a id="user-content-10xx---general-server-or-network-issues" class="anchor" aria-hidden="true" href="#10xx---general-server-or-network-issues"> </a>10xx - General Server or Network issues</h2>
    <h4><a id="user-content--1000-unknown" class="anchor" aria-hidden="true" href="#-1000-unknown"> </a>-1000 UNKNOWN</h4>
    <ul>
    <li>An unknown error occured while processing the request.</li>
    </ul>
    <h4><a id="user-content--1001-disconnected" class="anchor" aria-hidden="true" href="#-1001-disconnected"> </a>-1001 DISCONNECTED</h4>
    <ul>
    <li>Internal error; unable to process your request. Please try again.</li>
    </ul>
    <h4><a id="user-content--1002-unauthorized" class="anchor" aria-hidden="true" href="#-1002-unauthorized"> </a>-1002 UNAUTHORIZED</h4>
    <ul>
    <li>You are not authorized to execute this request.</li>
    </ul>
    <h4><a id="user-content--1003-too_many_requests" class="anchor" aria-hidden="true" href="#-1003-too_many_requests"> </a>-1003 TOO_MANY_REQUESTS</h4>
    <ul>
    <li>Too many requests; please use the websocket for live updates.</li>
    <li>Too many requests; current limit is %s requests per minute. Please use the websocket for live updates to avoid polling the API.</li>
    <li>Way too many requests; IP banned until %s. Please use the websocket for live updates to avoid bans.</li>
    </ul>
    <h4><a id="user-content--1006-unexpected_resp" class="anchor" aria-hidden="true" href="#-1006-unexpected_resp"> </a>-1006 UNEXPECTED_RESP</h4>
    <ul>
    <li>An unexpected response was received from the message bus. Execution status unknown.</li>
    </ul>
    <h4><a id="user-content--1007-timeout" class="anchor" aria-hidden="true" href="#-1007-timeout"> </a>-1007 TIMEOUT</h4>
    <ul>
    <li>Timeout waiting for response from backend server. Send status unknown; execution status unknown.</li>
    </ul>
    <h4><a id="user-content--1014-unknown_order_composition" class="anchor" aria-hidden="true" href="#-1014-unknown_order_composition"> </a>-1014 UNKNOWN_ORDER_COMPOSITION</h4>
    <ul>
    <li>Unsupported order combination.</li>
    </ul>
    <h4><a id="user-content--1015-too_many_orders" class="anchor" aria-hidden="true" href="#-1015-too_many_orders"> </a>-1015 TOO_MANY_ORDERS</h4>
    <ul>
    <li>Too many new orders.</li>
    <li>Too many new orders; current limit is %s orders per %s.</li>
    </ul>
    <h4><a id="user-content--1016-service_shutting_down" class="anchor" aria-hidden="true" href="#-1016-service_shutting_down"> </a>-1016 SERVICE_SHUTTING_DOWN</h4>
    <ul>
    <li>This service is no longer available.</li>
    </ul>
    <h4><a id="user-content--1020-unsupported_operation" class="anchor" aria-hidden="true" href="#-1020-unsupported_operation"> </a>-1020 UNSUPPORTED_OPERATION</h4>
    <ul>
    <li>This operation is not supported.</li>
    </ul>
    <h4><a id="user-content--1021-invalid_timestamp" class="anchor" aria-hidden="true" href="#-1021-invalid_timestamp"> </a>-1021 INVALID_TIMESTAMP</h4>
    <ul>
    <li>Timestamp for this request is outside of the recvWindow.</li>
    <li>Timestamp for this request was 1000ms ahead of the server's time.</li>
    </ul>
    <h4><a id="user-content--1022-invalid_signature" class="anchor" aria-hidden="true" href="#-1022-invalid_signature"> </a>-1022 INVALID_SIGNATURE</h4>
    <ul>
    <li>Signature for this request is not valid.</li>
    </ul>
    <h2><a id="user-content-11xx---request-issues" class="anchor" aria-hidden="true" href="#11xx---request-issues"> </a>11xx - Request issues</h2>
    <h4><a id="user-content--1100-illegal_chars" class="anchor" aria-hidden="true" href="#-1100-illegal_chars"> </a>-1100 ILLEGAL_CHARS</h4>
    <ul>
    <li>Illegal characters found in a parameter.</li>
    <li>Illegal characters found in parameter '%s'; legal range is '%s'.</li>
    </ul>
    <h4><a id="user-content--1101-too_many_parameters" class="anchor" aria-hidden="true" href="#-1101-too_many_parameters"> </a>-1101 TOO_MANY_PARAMETERS</h4>
    <ul>
    <li>Too many parameters sent for this endpoint.</li>
    <li>Too many parameters; expected '%s' and received '%s'.</li>
    <li>Duplicate values for a parameter detected.</li>
    </ul>
    <h4><a id="user-content--1102-mandatory_param_empty_or_malformed" class="anchor" aria-hidden="true" href="#-1102-mandatory_param_empty_or_malformed"> </a>-1102 MANDATORY_PARAM_EMPTY_OR_MALFORMED</h4>
    <ul>
    <li>A mandatory parameter was not sent, was empty/null, or malformed.</li>
    <li>Mandatory parameter '%s' was not sent, was empty/null, or malformed.</li>
    <li>Param '%s' or '%s' must be sent, but both were empty/null!</li>
    </ul>
    <h4><a id="user-content--1103-unknown_param" class="anchor" aria-hidden="true" href="#-1103-unknown_param"> </a>-1103 UNKNOWN_PARAM</h4>
    <ul>
    <li>An unknown parameter was sent.</li>
    </ul>
    <h4><a id="user-content--1104-unread_parameters" class="anchor" aria-hidden="true" href="#-1104-unread_parameters"> </a>-1104 UNREAD_PARAMETERS</h4>
    <ul>
    <li>Not all sent parameters were read.</li>
    <li>Not all sent parameters were read; read '%s' parameter(s) but was sent '%s'.</li>
    </ul>
    <h4><a id="user-content--1105-param_empty" class="anchor" aria-hidden="true" href="#-1105-param_empty"> </a>-1105 PARAM_EMPTY</h4>
    <ul>
    <li>A parameter was empty.</li>
    <li>Parameter '%s' was empty.</li>
    </ul>
    <h4><a id="user-content--1106-param_not_required" class="anchor" aria-hidden="true" href="#-1106-param_not_required"> </a>-1106 PARAM_NOT_REQUIRED</h4>
    <ul>
    <li>A parameter was sent when not required.</li>
    <li>Parameter '%s' sent when not required.</li>
    </ul>
    <h4><a id="user-content--1111-bad_precision" class="anchor" aria-hidden="true" href="#-1111-bad_precision"> </a>-1111 BAD_PRECISION</h4>
    <ul>
    <li>Precision is over the maximum defined for this asset.</li>
    </ul>
    <h4><a id="user-content--1112-no_depth" class="anchor" aria-hidden="true" href="#-1112-no_depth"> </a>-1112 NO_DEPTH</h4>
    <ul>
    <li>No orders on book for symbol.</li>
    </ul>
    <h4><a id="user-content--1114-tif_not_required" class="anchor" aria-hidden="true" href="#-1114-tif_not_required"> </a>-1114 TIF_NOT_REQUIRED</h4>
    <ul>
    <li>TimeInForce parameter sent when not required.</li>
    </ul>
    <h4><a id="user-content--1115-invalid_tif" class="anchor" aria-hidden="true" href="#-1115-invalid_tif"> </a>-1115 INVALID_TIF</h4>
    <ul>
    <li>Invalid timeInForce.</li>
    </ul>
    <h4><a id="user-content--1116-invalid_order_type" class="anchor" aria-hidden="true" href="#-1116-invalid_order_type"> </a>-1116 INVALID_ORDER_TYPE</h4>
    <ul>
    <li>Invalid orderType.</li>
    </ul>
    <h4><a id="user-content--1117-invalid_side" class="anchor" aria-hidden="true" href="#-1117-invalid_side"> </a>-1117 INVALID_SIDE</h4>
    <ul>
    <li>Invalid side.</li>
    </ul>
    <h4><a id="user-content--1118-empty_new_cl_ord_id" class="anchor" aria-hidden="true" href="#-1118-empty_new_cl_ord_id"> </a>-1118 EMPTY_NEW_CL_ORD_ID</h4>
    <ul>
    <li>New client order ID was empty.</li>
    </ul>
    <h4><a id="user-content--1119-empty_org_cl_ord_id" class="anchor" aria-hidden="true" href="#-1119-empty_org_cl_ord_id"> </a>-1119 EMPTY_ORG_CL_ORD_ID</h4>
    <ul>
    <li>Original client order ID was empty.</li>
    </ul>
    <h4><a id="user-content--1120-bad_interval" class="anchor" aria-hidden="true" href="#-1120-bad_interval"> </a>-1120 BAD_INTERVAL</h4>
    <ul>
    <li>Invalid interval.</li>
    </ul>
    <h4><a id="user-content--1121-bad_symbol" class="anchor" aria-hidden="true" href="#-1121-bad_symbol"> </a>-1121 BAD_SYMBOL</h4>
    <ul>
    <li>Invalid symbol.</li>
    </ul>
    <h4><a id="user-content--1125-invalid_listen_key" class="anchor" aria-hidden="true" href="#-1125-invalid_listen_key"> </a>-1125 INVALID_LISTEN_KEY</h4>
    <ul>
    <li>This listenKey does not exist.</li>
    </ul>
    <h4><a id="user-content--1127-more_than_xx_hours" class="anchor" aria-hidden="true" href="#-1127-more_than_xx_hours"> </a>-1127 MORE_THAN_XX_HOURS</h4>
    <ul>
    <li>Lookup interval is too big.</li>
    <li>More than %s hours between startTime and endTime.</li>
    </ul>
    <h4><a id="user-content--1128-optional_params_bad_combo" class="anchor" aria-hidden="true" href="#-1128-optional_params_bad_combo"> </a>-1128 OPTIONAL_PARAMS_BAD_COMBO</h4>
    <ul>
    <li>Combination of optional parameters invalid.</li>
    </ul>
    <h4><a id="user-content--1130-invalid_parameter" class="anchor" aria-hidden="true" href="#-1130-invalid_parameter"> </a>-1130 INVALID_PARAMETER</h4>
    <ul>
    <li>Invalid data sent for a parameter.</li>
    <li>Data sent for paramter '%s' is not valid.</li>
    </ul>
    <h4><a id="user-content--2010-new_order_rejected" class="anchor" aria-hidden="true" href="#-2010-new_order_rejected"> </a>-2010 NEW_ORDER_REJECTED</h4>
    <ul>
    <li>NEW_ORDER_REJECTED</li>
    </ul>
    <h4><a id="user-content--2011-cancel_rejected" class="anchor" aria-hidden="true" href="#-2011-cancel_rejected"> </a>-2011 CANCEL_REJECTED</h4>
    <ul>
    <li>CANCEL_REJECTED</li>
    </ul>
    <h4><a id="user-content--2013-no_such_order" class="anchor" aria-hidden="true" href="#-2013-no_such_order"> </a>-2013 NO_SUCH_ORDER</h4>
    <ul>
    <li>Order does not exist.</li>
    </ul>
    <h4><a id="user-content--2014-bad_api_key_fmt" class="anchor" aria-hidden="true" href="#-2014-bad_api_key_fmt"> </a>-2014 BAD_API_KEY_FMT</h4>
    <ul>
    <li>API-key format invalid.</li>
    </ul>
    <h4><a id="user-content--2015-rejected_mbx_key" class="anchor" aria-hidden="true" href="#-2015-rejected_mbx_key"> </a>-2015 REJECTED_MBX_KEY</h4>
    <ul>
    <li>Invalid API-key, IP, or permissions for action.</li>
    </ul>
    <h4><a id="user-content--2016-no_trading_window" class="anchor" aria-hidden="true" href="#-2016-no_trading_window"> </a>-2016 NO_TRADING_WINDOW</h4>
    <ul>
    <li>No trading window could be found for the symbol. Try ticker/24hrs instead.</li>
    </ul>
    <h2><a id="user-content-messages-for--1010-error_msg_received--2010-new_order_rejected-and--2011-cancel_rejected" class="anchor" aria-hidden="true" href="#messages-for--1010-error_msg_received--2010-new_order_rejected-and--2011-cancel_rejected"> </a>Messages for -1010 ERROR_MSG_RECEIVED, -2010 NEW_ORDER_REJECTED, and -2011 CANCEL_REJECTED</h2>
    <p>This code is sent when an error has been returned by the matching engine.
    The following messages which will indicate the specific error:</p>
    <table>
    <thead>
    <tr>
    <th>Error message</th>
    <th>Description</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <td>"Unknown order sent."</td>
    <td>The order (by either <code>orderId</code>, <code>clOrdId</code>, <code>origClOrdId</code>) could not be found</td>
    </tr>
    <tr>
    <td>"Duplicate order sent."</td>
    <td>The <code>clOrdId</code> is already in use</td>
    </tr>
    <tr>
    <td>"Market is closed."</td>
    <td>The symbol is not trading</td>
    </tr>
    <tr>
    <td>"Account has insufficient balance for requested action."</td>
    <td>Not enough funds to complete the action</td>
    </tr>
    <tr>
    <td>"Market orders are not supported for this symbol."</td>
    <td><code>MARKET</code> is not enabled on the symbol</td>
    </tr>
    <tr>
    <td>"Iceberg orders are not supported for this symbol."</td>
    <td><code>icebergQty</code> is not enabled on the symbol</td>
    </tr>
    <tr>
    <td>"Stop loss orders are not supported for this symbol."</td>
    <td><code>STOP_LOSS</code> is not enabled on the symbol</td>
    </tr>
    <tr>
    <td>"Stop loss limit orders are not supported for this symbol."</td>
    <td><code>STOP_LOSS_LIMIT</code> is not enabled on the symbol</td>
    </tr>
    <tr>
    <td>"Take profit orders are not supported for this symbol."</td>
    <td><code>TAKE_PROFIT</code> is not enabled on the symbol</td>
    </tr>
    <tr>
    <td>"Take profit limit orders are not supported for this symbol."</td>
    <td><code>TAKE_PROFIT_LIMIT</code> is not enabled on the symbol</td>
    </tr>
    <tr>
    <td>"Price * QTY is zero or less."</td>
    <td><code>price</code> * <code>quantity</code> is too low</td>
    </tr>
    <tr>
    <td>"IcebergQty exceeds QTY."</td>
    <td><code>icebergQty</code> must be less than the order quantity</td>
    </tr>
    <tr>
    <td>"This action disabled is on this account."</td>
    <td>Contact customer support; some actions have been disabled on the account.</td>
    </tr>
    <tr>
    <td>"Unsupported order combination"</td>
    <td>The <code>orderType</code>, <code>timeInForce</code>, <code>stopPrice</code>, and/or <code>icebergQty</code> combination isn't allowed.</td>
    </tr>
    <tr>
    <td>"Order would trigger immediately."</td>
    <td>The order's stop price is not valid when compared to the last traded price.</td>
    </tr>
    <tr>
    <td>"Cancel order is invalid. Check origClOrdId and orderId."</td>
    <td>No <code>origClOrdId</code> or <code>orderId</code> was sent in.</td>
    </tr>
    <tr>
    <td>"Order would immediately match and take."</td>
    <td><code>LIMIT_MAKER</code> order type would immediately match and trade, and not be a pure maker order.</td>
    </tr></tbody></table>
    <h2><a id="user-content--9xxx-filter-failures" class="anchor" aria-hidden="true" href="#-9xxx-filter-failures">  </a>-9xxx Filter failures</h2>
    <table>
    <thead>
    <tr>
    <th>Error message</th>
    <th>Description</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <td>"Filter failure: PRICE_FILTER"</td>
    <td><code>price</code> is too high, too low, and/or not following the tick size rule for the symbol.</td>
    </tr>
    <tr>
    <td>"Filter failure: LOT_SIZE"</td>
    <td><code>quantity</code> is too high, too low, and/or not following the step size rule for the symbol.</td>
    </tr>
    <tr>
    <td>"Filter failure: MIN_NOTIONAL"</td>
    <td><code>price</code> * <code>quantity</code> is too low to be a valid order for the symbol.</td>
    </tr>
    <tr>
    <td>"Filter failure: MAX_NUM_ORDERS"</td>
    <td>Account has too many open orders on the symbol.</td>
    </tr>
    <tr>
    <td>"Filter failure: MAX_ALGO_ORDERS"</td>
    <td>Account has too many open stop loss and/or take profit orders on the symbol.</td>
    </tr>
    <tr>
    <td>"Filter failure: EXCHANGE_MAX_NUM_ORDERS"</td>
    <td>Account has too many open orders on the exchange.</td>
    </tr>
    <tr>
    <td>"Filter failure: EXCHANGE_MAX_ALGO_ORDERS"</td>
    <td>Account has too many open stop loss and/or take profit orders on the exchange.</td>
    </tr>
    <tr>
    <td>"Filter failure: ICEBERG_PARTS"</td>
    <td>Iceberg order would break into too many parts; icebergQty is too small.</td>
    </tr></tbody></table>
    </article>
      </div>

        </div>
  </div>
</div>
