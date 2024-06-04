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
// echo "<pre>";
// print_r($coin);
// print_r($market);
// exit;
$score_avg = 0;
$psum = 0;
$nsum = 0;
$sum = 0;
$x = 0;
$count = 0;
foreach ($news as $key => $value) {
    if ($value['score'] >= 0) {
        $psum = $psum + $value['score'];
    } else {
        $nsum = $nsum + $value['score'];
    }
    $count++;
}
$sum = $psum + (-1 * ($nsum));
$x = $psum / $sum;
$score_avg = round($x * 100);

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
    <div class="col-md-6" style="text-align: center;
    float: left;
    font-size: 13px;
    text-transform: initial;">
      <div class="row">
        <div class="col-md-12">
          <label class="table table-hover">
          <span><strong>Coin Name: </strong></span><?php echo ' ' . $coin['coin_name']; ?>
          </label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <label class="table table-hover">
          <span><strong>Coin Symbol: </strong></span><?php echo ' ' . $coin['symbol']; ?>
          </label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <label class="table table-hover" id="balance">
          <span><strong>Balance: </strong></span><?php echo ' ' . $market['balance']; ?>
          </label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <label class="table table-hover" id="last_price">
          <span><strong>Coin Last Price: </strong></span><?php echo ' ' . $market['last_price']; ?>
          </label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <label class="table table-hover" id="trades">
          <span><strong>Coin Open Trades: </strong></span><?php echo ' ' . $market['trade']; ?>
          </label>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
          <label class="table table-hover" id="change">
          <span><strong>Last 24h Change: </strong></span><?php echo ' ' . $market['change']; ?>
          </label>
        </div>
    </div>
    </div>
    <div class="col-md-3">
      <div style="text-align: center;background: #424242;">
          <div class="row">
              <div class="col-md-6">
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
    </div>
    </div>
  </div>
</div>

<div class="widget widget-inverse">
  <div class="widget-head" style="height:46px;">
        <h4 class="heading" style=" padding-top: 3px;">Sentiment Report</h4>
      </div>
  <div class="container-fluid" style="text-align: center;background: #424242;">
    <div class="row" style="padding:10px;">
        <div class="col-md-12">
          <div id="container" style="min-width: 300px; height: 300px; margin: 0 auto"></div>
        </div>
       <!--  <div class="col-md-6" style="padding-top: 50px;">
          <div class="meater_candle">
              <div class="goal_meater_main">
                    <div class="goal_meater_img">
                        <div class="degits"><?php echo $score_avg; ?></div>
                        <div pin-value="<?php echo $score_avg; ?>" class="goal_pin"></div>
                    </div>
                </div>
        </div> -->
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
    for ($i = 0; $i < count($news); $i++) {
        ?>
    <div class="col-md-12">
      <p class="well">
         <?php echo $news[$i]['news']; ?>
      </p>
    </div>
    <div class="col-md-4">
       <p>
        <span><strong>Labels:</strong></span>
       <?php foreach ($news[$i]['keyword'] as $key => $value) {
            ?>
         <span class="label label-success label-stroke"><?php echo $value; ?></span>
         <?php
}?>
      </p>
    </div>

    <div class="col-md-2">
      <p>
        <span><strong>Source: </strong></span><span class="label label-success"><?php echo strtoupper($news[$i]['source']); ?></span>
      </p>
    </div>

    <div class="col-md-2">
       <p>
        <span><strong>Coin: </strong></span><span class="label label-info"><?php echo strtoupper($news[$i]['coin']); ?></span>
      </p>
    </div>

    <div class="col-md-2">
      <p>
        <span><strong>Score: </strong></span><span class="label label-primary"><?php echo strtoupper($news[$i]['score']); ?></span>
      </p>
    </div>
    <div class="col-md-2">
      <p>
        <span><strong>Factor: </strong></span><span class="label label-warning"><?php echo strtoupper($news[$i]['factor']); ?></span>
      </p>
    </div>
    <div class="col-md-12">
      <p>
        <span><strong>Date: </strong></span><span class="label label-info label-stroke" style="line-height: 2;"><?php echo $news[$i]['date']; ?></span>
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
      $('#change').html("<strong>Last 24h Change: </strong></span>"+str[4]);
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
});
</script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">
Highcharts.createElement('link', {
    href: 'https://fonts.googleapis.com/css?family=Signika:400,700',
    rel: 'stylesheet',
    type: 'text/css'
}, null, document.getElementsByTagName('head')[0]);

// Add the background image to the container
Highcharts.wrap(Highcharts.Chart.prototype, 'getContainer', function (proceed) {
    proceed.call(this);
    this.container.style.background =
        'url(http://www.highcharts.com/samples/graphics/sand.png)';
});


Highcharts.theme = {
    colors: ['#ff7675', '#8085e9', '#8d4654', '#7798BF', '#aaeeee',
        '#ff0066', '#eeaaee', '#55BF3B', '#DF5353', '#7798BF', '#f45b5b'],
    chart: {
        backgroundColor: null,
        style: {
            fontFamily: 'Signika, serif'
        }
    },
    title: {
        style: {
            color: 'black',
            fontSize: '16px',
            fontWeight: 'bold'
        }
    },
    subtitle: {
        style: {
            color: 'black'
        }
    },
    tooltip: {
        borderWidth: 0
    },
    legend: {
        itemStyle: {
            fontWeight: 'bold',
            fontSize: '13px'
        }
    },
    xAxis: {
        labels: {
            style: {
                color: '#6e6e70'
            }
        }
    },
    yAxis: {
        labels: {
            style: {
                color: '#6e6e70'
            }
        }
    },
    plotOptions: {
        series: {
            shadow: true
        },
        candlestick: {
            lineColor: '#404048'
        },
        map: {
            shadow: true
        }
    },

    // Highstock specific
    navigator: {
        xAxis: {
            gridLineColor: '#D0D0D8'
        }
    },
    rangeSelector: {
        buttonTheme: {
            fill: 'white',
            stroke: '#C0C0C8',
            'stroke-width': 1,
            states: {
                select: {
                    fill: '#D0D0D8'
                }
            }
        }
    },
    scrollbar: {
        trackBorderColor: '#C0C0C8'
    },

    // General
    background2: '#E0E0E8'

};

// Apply the theme
Highcharts.setOptions(Highcharts.theme);
 Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: "Sentiment Graph Of <?php echo $coin['symbol']; ?>"
    },
    xAxis:{
          type: 'datetime',
          dateTimeLabelFormats: {
        millisecond: '%H:%M:%S.%L',
        second: '%H:%M:%S',
        minute: '%H:%M',
        hour: '%H:%M',
        day: '%e. %b',
        week: '%e. %b',
        month: '%b \'%y',
        year: '%Y'
},
          title: {
              text: 'Date & Time'
          }
      },
    yAxis: {
      labels: {
            format: '{value:.2f}'
        },
        title: {
            text: 'Price In Dollar (USD)'
        }
    },
    tooltip: {
         formatter: function () {
                      return '<b>' + this.series.name + '</b><br/>' +
                              Highcharts.dateFormat('%Y-%m-%d %l:%M:%S %p', this.x) + '<br/>' +
                              this.y;
                  }
      },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
      series: [
      {
          name: 'Sentiment Chart',
          data: [<?php
if (count($avg_score) > 0) {
    foreach ($avg_score as $key => $value) {?>
            [Date.parse('<?php echo $value['created_date']; ?> GMT'), <?php echo $value['score']; ?>],
            <?php }
}?>  ]
      }
      ]

  });
</script>