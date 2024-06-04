<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/drag-panes.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>

<div id="content">
  <h1 class="content-heading bg-white border-bottom">Orders</h1>
  <div class="bg-white innerAll border-bottom">
	<ul class="menubar">
    	<li class="active"><a href="<?php echo SURL; ?>/admin/dashboard/drawCandlestick">Candle Stick Chart</a></li>
	</ul>
  </div>
  <div class="innerAll spacing-x2">
  	
        <div class="widget widget-inverse"> 
            
            <div id="container" style="height: 650px; min-width: 510px"></div>

        </div>    
     
    
  </div>
</div>

<?php 

//   $arrrNew = array();


//   if(count($candlesdtickArr)>0){
//     echo '<pre>';
//     print_r($candlesdtickArr);
//   }



// exit;

$candlesdtickArr = $candlesdtickArr;

?>



<script type="text/javascript">
    

var data = <?php echo json_encode($candlesdtickArr); ?>;
console.log(data);


    // split the data set into ohlc and volume
    var ohlc = [],
        volume = [],
        dataLength = data.length,
        // set the allowed units for data grouping
        groupingUnits = [[
            'week',                         // unit name
            [1]                             // allowed multiples
        ], [
            'month',
            [1, 2, 3, 4, 6]
        ]],

        i = 0;

    for (i; i < dataLength; i += 1) {
        ohlc.push([
            data[i].openTime, // the date
            parseFloat(data[i].open), // open
            parseFloat(data[i].high), // high
            parseFloat(data[i].low), // low
            parseFloat(data[i].close) // close
        ]);

        volume.push([
            data[i].openTime, // the date
            parseFloat(data[i].volume)// the volume
        ]);
    }


    // create the chart
    Highcharts.stockChart('container', {

        rangeSelector: {
            selected: 1
        },

        title: {
            text: 'AAPL Historical'
        },

        yAxis: [{
            labels: {
                align: 'right',
                x: -3
            },
            title: {
                text: 'OHLC'
            },
            height: '80%',
            lineWidth: 2,
            resize: {
                enabled: true
            }
        }, {
            labels: {
                align: 'right',
                x: -3
            },
            title: {
                text: 'Volume'
            },
            top: '65%',
            height: '35%',
            offset: 0,
            lineWidth: 2
        }],

        tooltip: {
            split: true
        },

        series: [{
            type: 'candlestick',
            name: 'AAPL',
            data: ohlc,
            dataGrouping: {
                units: groupingUnits
            }
        }, {
            type: 'column',
            name: 'Volume',
            data: volume,
            yAxis: 1,
            dataGrouping: {
                units: groupingUnits
            }
        }]
    });
// });

</script>

