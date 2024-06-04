<div id="content">
  <h1 class="content-heading bg-white border-bottom">Market Depth Chart</h1>
  <div class="innerAll bg-white border-bottom"> 
  <ul class="menubar">
  <li class="active"><a href="<?php echo SURL; ?>/admin/coins">Market Depth Chart</a></li>
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
      
      <div class="widget-body padding-bottom-none"> 
     

        <div id="container"></div>
  
      </div>
    </div>
    <!-- // Widget END --> 
    
  </div>
</div>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script type="text/javascript">
  var charts = Highcharts.chart('container', {
    chart: {
       type: 'line',
        zoomType: 'x',
    },
    title: {
        text: 'Depth Price Volume Chart'
    },
    xAxis:{
         //tickPixelInterval: 1,
         tickInterval: 0.0000000000013,
         labels: {
            formatter: function () {
                return this.value;
            }},
         allowDecimals:true,
         title: {
              text: 'Prices'
          }
      },
    yAxis: {
        tickInterval:1000000,
        title: {
            text: 'Quantity'
        }
    },
     tooltip: {
         formatter: function () {
                      return '<b>' + this.series.name + '</b><br/>' +
                              'Price: '+this.x + '<br/>' +
                              'Volume: '+this.y;
                  }
      },
      plotOptions: {
        line: {
            dataLabels: {
                enabled: false
            },
            enableMouseTracking: true
        }
    },
    series: [{
        name: 'Depth Bid',
         pointStart: 0.0000001,
         pointInterval: 0.0000001,
       data: [<?php 
            if(count($depth_bid)>0){
            foreach ($depth_bid as $key => $value) { ?>
            [<?php echo $value['price'];?>, <?php echo $value['volume'];?>], 
            <?php } 
            } ?>  ]
    }, {
        name: 'Depth Ask',
        pointStart: 0.0000001,
         pointInterval: 0.0000001,
         data: [<?php 
            if(count($depth_ask)>0){
            foreach ($depth_ask as $key => $value) { ?>
            [<?php echo $value['price'];?>, <?php echo $value['volume'];?>], 
            <?php } 
            } ?>  ]
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

});
</script>