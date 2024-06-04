<?php  
       $page_post_data        = $this->session->userdata('page_post_data_coin'); 
       $box_session_data_coin = $this->session->userdata('page_post_data_coin');  
	   $checboxValue          = ($box_session_data_coin=='') ? 'checked="checked"' : '';
	   
	     
?>
 
<link href="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/bootstrap-datetimepicker.js"></script>
<script src="<?php echo ASSETS; ?>js/highcharts/highcharts.js"></script>
<script src="<?php echo ASSETS; ?>js/highcharts/series-label.js"></script>
<script src="<?php echo ASSETS; ?>js/highcharts/exporting.js"></script>
<script src="<?php echo ASSETS; ?>js/highcharts/export-data.js"></script>
<script src="<?php echo ASSETS ?>buyer_order/moment-with-locales.js"></script>

<style>
.sidebar.sidebar-inverse {
	z-index: 9999;
}
.highcharts-credits {
	display: none;
}
.highcharts-legend {
/*visibility:hidden;*/
}
.loader_parent {
	position: relative;
}
.loader_image_main {
	bottom: 0;
	height: 50px;
	left: 0;
	margin: auto;
	position: absolute;
	right: 0;
	top: 0;
	width: 50px;
	z-index: 9999;
}
.loader_overlay_main {
	background: rgba(255, 255, 255, 0.8) none repeat scroll 0 0;
	height: 100%;
	position: absolute;
	width: 100%;
	z-index: 99;
	display: none;
}
.highcharts-axis-labels tspan.utc {
    display: none;
}
</style>
<div id="content">
  <div class="innerAll bg-white border-bottom"></div>
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
    <?php
    // only use for GMT and UTC goes here 
								$dtz = new DateTimeZone( $timezone);
								$time_in_sofia = new DateTime('now', $dtz);
								$offset = $dtz->getOffset( $time_in_sofia ) / 3600;
								$fianlUtcGmt = ($offset < 0 ? $offset : "+".$offset);
                                // only use for GMT and UTC goes here 
								
								$end_dateBO  = '<span class="utc">  ( GMT'.$fianlUtcGmt.' )</span>';
								$dtNew       = new DateTime( date("Y-m-d H:i:s"), new DateTimeZone($timezone));
								$dtNew->setTimezone(new DateTimeZone($timezone));
								$end_dateAM  = $dtNew->format("F j, Y, g:i a");
								
								$date  = new DateTime("now", new DateTimeZone($timezone) );
                                $fina  = $date->format('F j, Y, g:i a');
								
								
    ?>
    <div class=" pull-right alert alert-warning" style="margin-top: 0px;background: #5c678a;color: white;height: 40px;"> <p style="    margin-top: -6px;">Server Time : <?php echo date("F j, Y, g:i a").'&nbsp;&nbsp;  <b>'.date_default_timezone_get().' ( GMT+0 )'.'</b>' ?> <span><b> ||| </b></span> Using Your Time Zone : <?php echo $fina ;?> <b><strong> <?php echo ''.$end_dateBO; ?></strong></b></p>
    </div>
    
     <br />
     
     <br />
     
     
        
        <form action="<?php  echo SURL ?>admin/highchart/coin_average_chart"  id="highchartform" enctype="multipart/form-data" method="post">
          <div class="col-md-12 ">
            <div class="form-group col-md-2">
            
            
            
              <label class="control-label">Start Date</label>
              <input type='text' class="form-control datetime_picker" id="filter_by_start_date"  name="start_date" placeholder="Search By Start Date" value="<?php echo $startDate; ?>" />
            </div>
            <div class="form-group col-md-2">
              <label class="control-label">End Date</label>
              <input type='text' class="form-control datetime_picker" name="end_date" placeholder="Search By End Date" value="<?php echo $endDate ; ?>" />
            </div>
            <div class="form-group col-md-2">
              <label class="control-label" for="coin">Time Duration</label>
              <select class="form-control time" name="time" id="time">
                <option value="minut" <?php echo ($time=='minut') ? 'selected="selected"' : ''; ?>> Minut</option>
                <option value="hour" <?php echo ($time=='hour') ? 'selected="selected"' : ''; ?>> Hour</option>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label class="control-label">Defined Width</label>
              <select class="form-control time" name="chart_width" id="chart_width">
                <option value="1600" <?php echo ($page_post_data['chart_width']=='1600') ? 'selected="selected"' : ''; ?>> Normal</option>
                <option value="3000" <?php echo ($page_post_data['chart_width']=='3000') ? 'selected="selected"' : ''; ?>> Large </option>
                <option value="5000" <?php echo ($page_post_data['chart_width']=='5000') ? 'selected="selected"' : ''; ?>> Extra Large </option>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label class="control-label">Defined Height</label>
              <select class="form-control time" name="chart_height" id="chart_height">
                <option value="600" <?php echo ($page_post_data['chart_height']=='600') ? 'selected="selected"' : ''; ?>> Normal</option>
                <option value="900" <?php echo ($page_post_data['chart_height']=='900') ? 'selected="selected"' : ''; ?>> Large </option>
                <option value="1200" <?php echo ($page_post_data['chart_height']=='1200') ? 'selected="selected"' : ''; ?>> Extra Large </option>
              </select>
            </div>
          </div>
          <div class="col-md-12 ">
            
            <?php  $i=0;
			 $coinsarrVal  = array_reverse($coinsArr);
			 foreach($coinsarrVal as $coin){ ?>
            <div class="form-group col-md-1">
              <label class="checkbox-inline">
                <input type="checkbox" value="<?php  echo $coin['symbol']; ?>" name="post_coin[<?php  echo $coin['symbol']; ?>]" id="check<?php  echo $i; ?>"  class="checkbox" <?php echo (in_array($coin['symbol']."", $box_session_data_coin['post_coin'])) ?  'checked="checked"' : ''; echo $checboxValue; ?>>
                <?php echo $coin['symbol'] ?> </label>
            </div>
            <?php $i++;} ?>
            
            
          </div>
          <div class="col-md-12 ">
            <div class="form-group col-md-1">
              <input type="submit" class="btn btn-success btn-block btn-sm " value="Submit">
            </div>
            <div class="form-group col-md-1">
              <input type="submit" class="btn btn-primary btn-block btn-sm " name="clear" id="clear"  value="clear">
            </div>
          </div>
        </form>
      </div>
      <div class="clearfix"></div>
      <div class="loader_parent">
        <div class="loader_overlay_main"> <img class="loader_image_main" src="http://app.digiebot.com/assets/images/loader.gif"> </div>
        <div id="container"></div>
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
</div>
<!-- // Widget END -->

</div>
</div>
<?php  
     $width  = !empty($page_post_data['chart_width'])     ? ($page_post_data['chart_width']) : 1600;
	 $height = !empty($page_post_data['chart_height'])    ? ($page_post_data['chart_height']) : 600;
?>
<script type="text/javascript">

jQuery(document).ready(function() {
   loadcandle();
});
function loadcandle(){
 
    // the button action
    var chart = $('#container').highcharts();
	
      for(var i = 0; i < $('input[type=checkbox]').length; i++) {
        var series = chart.series[i];    
        if($('#check'+i).is(':checked')){
           //series.show();
        }
        else {
          //series.hide();
        }
      }
}

 function addChartCrosshairs() {
        var chart = this;
        //initialize the X and Y component of the crosshairs (you can adjust the color and size of the crosshair lines here)
        var crosshairX = chart.renderer.path(['M', chart.plotLeft, 2, 'L', chart.plotLeft + chart.plotWidth, 2]).attr({
            stroke: '#686868',
                'stroke-width': 0.5,
				dashStyle: 'shortdot',
            zIndex: 0
        }).add()
            .toFront()
            .hide();

        var crosshairY = chart.renderer.path(['M', 2, chart.plotTop, 'L', 2, chart.plotTop + chart.plotHeight]).attr({
            stroke: '#686868',
                'stroke-width': 0.5,
				dashStyle: 'shortdot',
            zIndex: 0
        }).add()
            .toFront()
            .hide();

        $(chart.container).mousemove(function (event) {
            //onmousemove move our crosshair lines to the current mouse postion
            xpos = (event.offsetX==undefined)?event.originalEvent.layerX:event.offsetX;
            ypos = (event.offsetY==undefined)?event.originalEvent.layerY:event.offsetY;
            
            crosshairX.translate(0, ypos);
            crosshairY.translate(xpos, 0);

            //only show the crosshairs if we are inside of the plot area (the area within the x and y axis)
            if (xpos > chart.plotLeft && xpos < chart.plotLeft + chart.plotWidth && 
                ypos > chart.plotTop && ypos < chart.plotTop + chart.plotHeight) {
                crosshairX.show();
                crosshairY.show();
            } else { //if we are here then we are inside of the container, but outside of the plot area
                crosshairX.hide();
                crosshairY.hide();
            }
        });
    }

 $(function () {
      $('.datetime_picker').datetimepicker({
		
		 //minDate : '-<?php echo $dtepickerdate[2];?>/<?php echo $dtepickerdate[0];?>/<?php echo $dtepickerdate[1];?>'
		  
	  });
 });

function load_hight_chart(black_wall_pressure,yellow_wall_pressure,pressure_diff,great_wall_price,seven_level_depth,score,last_qty_time_ago,last_200_time_ago,current_market_value){
		
	Highcharts.setOptions({
    time: {
        timezone: 'America/New_York'
    }
    });

	<?php if($time =='minut'){ $MmnutHour = 60; $formate =  'd M Y H:i:s';}else{$MmnutHour = 3600; $formate =  'd M Y H:i:s';} ?>
	
	Highcharts.chart('container', {
		
		time: {
             timezone: 'America/New_York'
        },
		title: {
			text: 'Show the data of last <b> <?php echo $totalHours; ?> </b> <?php echo ucfirst($time).'s';?> over the graph .'
		},
		width: 500,
			height: 400,
		subtitle: {
			text: ''
		},
		/* tooltip: {
			xDateFormat: '%Y-%m-%d %H:%M',
			pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.2f}</b><br/>'
        },*/
		
		
		mapNavigation: {
            enabled: true,
            enableDoubleClickZoomTo: true
        },
		
		xAxis: {
							gridLineColor: '#e5eaee',
							lineColor: '#e5eaee',
							tickColor: '#e5eaee',
							title:{ text:'Current Time Zone : <?php echo date_default_timezone_get();?>     Current Chart : <?php echo ucfirst($time).'s';?> '},
							categories: [
							<?php 
							$i=0; 
							$recent_count  =  $totalHours;
							
							
							//echo $recent_count; exit;
							for ($x = 1; $x <= $totalHours; $x++) {
								
								$currentDateTime = $startDate;
								$end_dateA       = date('m/d/Y h:i A', strtotime($currentDateTime));
								$dt = new DateTime($currentDateTime, new DateTimeZone($timezone));
								$dt->setTimezone(new DateTimeZone('PKT'));
								$pre_time = $dt->format('Y-m-d H:i:s');
								$second          = strtotime($pre_time) + (($x -1) * $MmnutHour);
								// only use for GMT and UTC goes here 
								$dtz = new DateTimeZone( $timezone);
								$time_in_sofia = new DateTime('now', $dtz);
								$offset = $dtz->getOffset( $time_in_sofia ) / 3600;
								$fianlUtcGmt = ($offset < 0 ? $offset : "+".$offset);
                                // only use for GMT and UTC goes here 
								
								$end_dateB       = date($formate, ($second)).'<span class="utc"> UTC ( GMT'.$fianlUtcGmt.' )</span>';
							?>            
							'<?php echo $end_dateB; ?>'
							<?php 
							 if(++$i === $recent_count){}else{ ?>,<?php }
							} ?>   	
							]
						},
	
		yAxis: {
			title: {
				text: ''
			},
			 min: -15,
				minRange: 0.5,
				 tickInterval: 0.5,
				 
				 plotLines: [{
							color: 'black', // Color value
							dashStyle: 'longdashdot', // Style of the plot line. Default to solid
							value: 0, // Value of where the line will appear
							width: 2 // Width of the line    
						  }]
				 
				 
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'middle'
		},
		
		tooltip: {
			shared: true,
			crosshairs: true
		},
	
		plotOptions: {
			series: {
				label: {
					connectorAllowed: false
				},
				pointStart: 0
			}
		},
		 chart: {
			renderTo: 'chart',
			events: {
                load: addChartCrosshairs
            },
			
			width:  <?php echo $width; ?>,
			height: <?php echo $height; ?>,
			marginRight: 180,
			
		},  
		
		   
		  
	legend: {
        layout: 'vertical',
         align: 'right',
        x: 0,
        verticalAlign: 'right',
        y: 30,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || 'rgba(255,255,255,0.25)'
    },
				

		series: [ 
		<?php if (!empty(in_array("BTCUSDT", $box_session_data_coin['post_coin']))){?>
		 {
		   name: 'BTCUSD ',
		    color: '#cc0000',
			data: [<?php echo $BTCUSDT; ?>],
			negativeColor: ''      
		},
		<?php }?>
		<?php if (!empty(in_array("NCASHBTC", $box_session_data_coin['post_coin']))){?>
		{
		    name: 'NCASHBTC',
		    color: '#009900',
			negativeColor: ' #009900',
			data: [<?php echo $NCASHBTC; ?>]
		}, 
		<?php }?>
		<?php if (!empty(in_array("TRXBTC", $box_session_data_coin['post_coin']))){?>
		{
		   name: 'TRXBTC',
		   color: '#00e600',
			data: [<?php echo $TRXBTC ; ?>],
			negativeColor: '' 
		}, 
		<?php }?>
		<?php if (!empty(in_array("EOSBTC", $box_session_data_coin['post_coin']))){?>
		{
		   name: 'EOSBTC',
		     color: '#ffbf00',
			data: [<?php echo $EOSBTC ; ?>],
			negativeColor: '' 
		}, 
		<?php }?>
		<?php if (!empty(in_array("POEBTC", $box_session_data_coin['post_coin']))){?>
		 {
		   name: 'POEBTC',
		     color: '#9370DB',
			data: [<?php echo $POEBTC ; ?>],
			negativeColor: '#9370DB' 
		}, 
		<?php }?>
		<?php if (!empty(in_array("NEOBTC", $box_session_data_coin['post_coin']))){?>
		{
			name: 'NEOBTC',
			 color: ' #cc33ff',
			data: [<?php echo $NEOBTC ; ?>],
			negativeColor: '' 
		},
		<?php }?>
		<?php if (!empty(in_array("ETCBTC", $box_session_data_coin['post_coin']))){?>
		{
			name: 'ETCBTC',
			  color: '#000000',
			data: [<?php echo $ETCBTC ; ?>],
			negativeColor: '' 
			
		},
		<?php }?>
		<?php if (!empty(in_array("XRPBTC", $box_session_data_coin['post_coin']))){?>
		{
		   name: 'XRPBTC ',
			data: [<?php echo $XRPBTC ; ?>]
		},
		<?php }?>
		<?php if (!empty(in_array("XEMBTC", $box_session_data_coin['post_coin']))){?>
		{
		   name: 'XEMBTC',
		    color: '#ff8080',
			data: [<?php echo $XEMBTC ; ?>],
			negativeColor: '' 
		},
		<?php }?>
		<?php if (!empty(in_array("XLMBTC", $box_session_data_coin['post_coin']))){?>
		{
		   name: 'XLMBTC ',
		   color: '#333300',
			data: [<?php echo $XLMBTC ; ?>],
			negativeColor: ''     
		},
		<?php }?>
		<?php if (!empty(in_array("QTUMBTC", $box_session_data_coin['post_coin']))){?>
		{
		   name: 'QTUMBTC ',
		   color: '#002663',
			data: [<?php echo $QTUMBTC ; ?>],
			negativeColor: ''      
		},
		<?php }?>
		<?php if (!empty(in_array("ZENBTC", $box_session_data_coin['post_coin']))){?>
		{
		   name: 'ZENBTC ',
		    color: '#cc9900',
			data: [<?php echo $ZENBTC ; ?>],
			negativeColor: ''      
		},
		<?php }?>
		
		<?php if (!empty(in_array("BTCUSDT", $box_session_data_coin['post_coin']))){?>
		{
		   name: 'BTCUSDT ',
		    color: '#cc8885',
			data: [<?php echo $BTCUSDT ; ?>],
			negativeColor: ''      
		},
		<?php }?>
		<?php if (!empty(in_array("XRPUSDT", $box_session_data_coin['post_coin']))){?>
		{
		   name: 'XRPUSDT ',
		    color: '#1b02ad',
			data: [<?php echo $XRPUSDT ; ?>],
			negativeColor: ''      
		},
		<?php }?>
		<?php if (!empty(in_array("QTUMUSDT", $box_session_data_coin['post_coin']))){?>
		{
		   name: 'QTUMUSDT ',
		    color: '#cc2200',
			data: [<?php echo $QTUMUSDT ; ?>],
			negativeColor: ''      
		},
		<?php }?>
		<?php if (!empty(in_array("NEOUSDT", $box_session_data_coin['post_coin']))){?>
		{
		   name: 'NEOUSDT ',
		    color: '#gg9900',
			data: [<?php echo $NEOUSDT ; ?>],
			negativeColor: ''      
		}
		<?php }?>
		
		
		],
	
		responsive: {
			rules: [{
				condition: {
					maxWidth: 700
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
}

black_wall_pressure  ='';
yellow_wall_pressure ='';
pressure_diff        ='';
great_wall_price     ='';
seven_level_depth    ='';
score                ='';
last_qty_time_ago    ='';
last_200_time_ago    ='';
current_market_value ='';
market_depth_quantity='';

load_hight_chart(black_wall_pressure,yellow_wall_pressure,pressure_diff,great_wall_price,seven_level_depth,score,last_qty_time_ago,last_200_time_ago,current_market_value);
//var chart = new Highcharts.Chart(options);
</script> 
