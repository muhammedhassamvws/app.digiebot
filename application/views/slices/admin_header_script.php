<?php

 $uri = $this->uri->segment(2);  // exit;

// if($uri=='highchart'){ ?>



<?php //}else{?>







<link rel="stylesheet" href="<?php echo CSS; ?>admin/module.admin.page.index.min.css" />

<link rel="stylesheet" href="<?php echo CSS; ?>admin/module.admin.page.form_validator.min.css" />

<link rel="stylesheet" href="<?php echo CSS; ?>admin/module.admin.page.form_wizards.min.css" />

<link rel="stylesheet" href="<?php echo CSS; ?>admin/module.admin.page.form_elements.min.css" />

<link rel="stylesheet" href="<?php echo CSS; ?>admin/module.admin.page.tables.min.css" />



<link rel="stylesheet" type="text/css" href="<?php echo CSS; ?>km_charts/style.css">



<link rel="stylesheet" href="<?php echo ASSETS; ?>dropzone/dropzone.css">



<!-- Jquery Confirm -->

<link rel="stylesheet" href="<?php echo ASSETS; ?>jquery_confirm/jquery-confirm.min.css">

<?php //}?>

<!-- Digiebot Custom CSS -->

<!-- <link rel="stylesheet" href="<?php echo ASSETS; ?>css/digiebot_style.css"> -->

<link rel="icon" type="image/png" href="<?php echo SURL ?>assets/icons/favicon_ico.ico">

<style>



label.error{

color: red;

}



.dropzone_cls{

    min-height: 150px;

    border: 2px dotted rgba(0, 0, 0, 0.3);

    background: #f9f9f9;

    padding: 20px 20px;

}

.has-error{color:#F00;}







/** Toastr Alerts

 ********************** **/

.toast-title {

  font-weight: bold;

}

.toast-message {

  -ms-word-wrap: break-word;

  word-wrap: break-word;

}

.toast-message a,

.toast-message label {

  color: #ffffff;

}

.toast-message a:hover {

  color: #cccccc;

  text-decoration: none;

}

.toast-close-button {

  position: relative;

  right: -0.3em;

  top: -0.3em;

  float: right;

  font-size: 20px;

  font-weight: bold;

  color: #ffffff;

  opacity: 0.8;

  -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=80);

  filter: alpha(opacity=80);

}

.toast-close-button:hover,

.toast-close-button:focus {

  color: #000000;

  text-decoration: none;

  cursor: pointer;

  opacity: 0.4;

  -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=40);

  filter: alpha(opacity=40);

}

/*Additional properties for button version

 iOS requires the button element instead of an anchor tag.

 If you want the anchor version, it requires `href="#"`.*/

button.toast-close-button {

  padding: 0;

  cursor: pointer;

  background: transparent;

  border: 0;

  -webkit-appearance: none;

}

.toast-top-center {

  top: 0;

  right: 0;

  width: 100%;

}

.toast-bottom-center {

  bottom: 0;

  right: 0;

  width: 100%;

}

.toast-top-full-width {

  top: 0;

  right: 0;

  width: 100%;

}

.toast-bottom-full-width {

  bottom: 0;

  right: 0;

  width: 100%;

}

.toast-top-left {

  top: 12px;

  left: 12px;

}

.toast-top-right {

  top: 12px;

  right: 12px;

}

.toast-bottom-right {

  right: 12px;

  bottom: 12px;

}

.toast-bottom-left {

  bottom: 12px;

  left: 12px;

}

#toast-container {

  position: fixed;

  z-index: 999999;

  /*overrides*/



}

#toast-container * {

  -moz-box-sizing: border-box;

  -webkit-box-sizing: border-box;

  box-sizing: border-box;

}

#toast-container > div {

  position: relative;

  overflow: hidden;

  margin: 0 0 6px;

  padding: 15px 15px 15px 50px;

  width: 300px;

  -moz-border-radius: 3px 3px 3px 3px;

  -webkit-border-radius: 3px 3px 3px 3px;

  border-radius: 3px 3px 3px 3px;

  background-position: 15px center;

  background-repeat: no-repeat;

  color: #ffffff;

  opacity: 0.8;

  -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=80);

  filter: alpha(opacity=80);

}

#toast-container > :hover {

  opacity: 1;

  -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=100);

  filter: alpha(opacity=100);

  cursor: pointer;

}

#toast-container > .toast-info {

  background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAGwSURBVEhLtZa9SgNBEMc9sUxxRcoUKSzSWIhXpFMhhYWFhaBg4yPYiWCXZxBLERsLRS3EQkEfwCKdjWJAwSKCgoKCcudv4O5YLrt7EzgXhiU3/4+b2ckmwVjJSpKkQ6wAi4gwhT+z3wRBcEz0yjSseUTrcRyfsHsXmD0AmbHOC9Ii8VImnuXBPglHpQ5wwSVM7sNnTG7Za4JwDdCjxyAiH3nyA2mtaTJufiDZ5dCaqlItILh1NHatfN5skvjx9Z38m69CgzuXmZgVrPIGE763Jx9qKsRozWYw6xOHdER+nn2KkO+Bb+UV5CBN6WC6QtBgbRVozrahAbmm6HtUsgtPC19tFdxXZYBOfkbmFJ1VaHA1VAHjd0pp70oTZzvR+EVrx2Ygfdsq6eu55BHYR8hlcki+n+kERUFG8BrA0BwjeAv2M8WLQBtcy+SD6fNsmnB3AlBLrgTtVW1c2QN4bVWLATaIS60J2Du5y1TiJgjSBvFVZgTmwCU+dAZFoPxGEEs8nyHC9Bwe2GvEJv2WXZb0vjdyFT4Cxk3e/kIqlOGoVLwwPevpYHT+00T+hWwXDf4AJAOUqWcDhbwAAAAASUVORK5CYII=") !important;

}

#toast-container > .toast-error {

  background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAHOSURBVEhLrZa/SgNBEMZzh0WKCClSCKaIYOED+AAKeQQLG8HWztLCImBrYadgIdY+gIKNYkBFSwu7CAoqCgkkoGBI/E28PdbLZmeDLgzZzcx83/zZ2SSXC1j9fr+I1Hq93g2yxH4iwM1vkoBWAdxCmpzTxfkN2RcyZNaHFIkSo10+8kgxkXIURV5HGxTmFuc75B2RfQkpxHG8aAgaAFa0tAHqYFfQ7Iwe2yhODk8+J4C7yAoRTWI3w/4klGRgR4lO7Rpn9+gvMyWp+uxFh8+H+ARlgN1nJuJuQAYvNkEnwGFck18Er4q3egEc/oO+mhLdKgRyhdNFiacC0rlOCbhNVz4H9FnAYgDBvU3QIioZlJFLJtsoHYRDfiZoUyIxqCtRpVlANq0EU4dApjrtgezPFad5S19Wgjkc0hNVnuF4HjVA6C7QrSIbylB+oZe3aHgBsqlNqKYH48jXyJKMuAbiyVJ8KzaB3eRc0pg9VwQ4niFryI68qiOi3AbjwdsfnAtk0bCjTLJKr6mrD9g8iq/S/B81hguOMlQTnVyG40wAcjnmgsCNESDrjme7wfftP4P7SP4N3CJZdvzoNyGq2c/HWOXJGsvVg+RA/k2MC/wN6I2YA2Pt8GkAAAAASUVORK5CYII=") !important;

}

#toast-container > .toast-success {

  background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAADsSURBVEhLY2AYBfQMgf///3P8+/evAIgvA/FsIF+BavYDDWMBGroaSMMBiE8VC7AZDrIFaMFnii3AZTjUgsUUWUDA8OdAH6iQbQEhw4HyGsPEcKBXBIC4ARhex4G4BsjmweU1soIFaGg/WtoFZRIZdEvIMhxkCCjXIVsATV6gFGACs4Rsw0EGgIIH3QJYJgHSARQZDrWAB+jawzgs+Q2UO49D7jnRSRGoEFRILcdmEMWGI0cm0JJ2QpYA1RDvcmzJEWhABhD/pqrL0S0CWuABKgnRki9lLseS7g2AlqwHWQSKH4oKLrILpRGhEQCw2LiRUIa4lwAAAABJRU5ErkJggg==") !important;

}

#toast-container > .toast-warning {

  background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAGYSURBVEhL5ZSvTsNQFMbXZGICMYGYmJhAQIJAICYQPAACiSDB8AiICQQJT4CqQEwgJvYASAQCiZiYmJhAIBATCARJy+9rTsldd8sKu1M0+dLb057v6/lbq/2rK0mS/TRNj9cWNAKPYIJII7gIxCcQ51cvqID+GIEX8ASG4B1bK5gIZFeQfoJdEXOfgX4QAQg7kH2A65yQ87lyxb27sggkAzAuFhbbg1K2kgCkB1bVwyIR9m2L7PRPIhDUIXgGtyKw575yz3lTNs6X4JXnjV+LKM/m3MydnTbtOKIjtz6VhCBq4vSm3ncdrD2lk0VgUXSVKjVDJXJzijW1RQdsU7F77He8u68koNZTz8Oz5yGa6J3H3lZ0xYgXBK2QymlWWA+RWnYhskLBv2vmE+hBMCtbA7KX5drWyRT/2JsqZ2IvfB9Y4bWDNMFbJRFmC9E74SoS0CqulwjkC0+5bpcV1CZ8NMej4pjy0U+doDQsGyo1hzVJttIjhQ7GnBtRFN1UarUlH8F3xict+HY07rEzoUGPlWcjRFRr4/gChZgc3ZL2d8oAAAAASUVORK5CYII=") !important;

}

#toast-container.toast-top-center > div,

#toast-container.toast-bottom-center > div {

  width: 300px;

  margin: auto;

}

#toast-container.toast-top-full-width > div,

#toast-container.toast-bottom-full-width > div {

  width: 96%;

  margin: auto;

}

.toast {

  background-color: #030303;

}

#toast-container .toast-primary {

	padding:15px;

}

.toast-primary {

  border:0;

  background-color: #333;

}

.toast-success {

  background-color: #51a351;

}

.toast-error {

  background-color: #bd362f;

}

.toast-info {

  background-color: #2f96b4;

}

.toast-warning {

  background-color: #f89406;

}

.toast-progress {

  position: absolute;

  left: 0;

  bottom: 0;

  height: 2px;

  background-color: #000000;

  opacity: 0.4;

  -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=40);

  filter: alpha(opacity=40);

}

</style>

<style>

.no-js #loader { display: none;  }

.js #loader { display: block; position: absolute; left: 100px; top: 0; }

.se-pre-con {

    position: fixed;

    left: 0px;

    top: 0px;

    width: 100%;

    height: 100%;

    z-index: 9999;

    background: url('<?php echo SURL; ?>assets/images/ajax_loader.gif') center no-repeat #fff;

    background-size: 150px 231px;

}

</style>

<script src="<?php echo ASSETS; ?>components/library/jquery/jquery.min.js?v=v1.2.3"></script>

<script src="<?php echo ASSETS; ?>components/library/jquery/jquery-migrate.min.js?v=v1.2.3"></script>

<script>

 //paste this code under head tag or in a seperate js file.

 // Wait for window load

 $(window).load(function() {

  // Animate loader off screen

  $(".se-pre-con").fadeOut("slow");;

 });

</script>

<?php if ($this->session->userdata('special_role') == 1) {

  ?>

<div class="se-pre-con"></div>



<?php }

  ?>

<style>

.tradonof {

    display: none;

    position: fixed;

    bottom: 20px;

    right: 20px;

    width: 100%;

    max-width: 200px;

    padding: 25px;

    background: #ccc;

    border-radius: 9px;

    box-shadow: 0 0 15px 5px rgba(0,0,0,0.1);

}



.tradonof h2 {

    font-size: 16px;

    text-align: center;

    font-weight: bold;

    margin: 0;

}



.tradonof.tof-color-red {

    background: #dc3d00;

    color: #fff;

    z-index: 9999;

}

</style>



<style>

.datamissing {

    display: none;

    position: fixed;

    bottom: 20px;

    right: 20px;

    width: 100%;

    max-width: 200px;

    padding: 25px;

    background: #ccc;

    border-radius: 9px;

    box-shadow: 0 0 15px 5px rgba(0,0,0,0.1);

}



.datamissing h2 {

    font-size: 16px;

    text-align: center;

    font-weight: bold;

    margin: 0;

}



.datamissing.tof-color-red {

    background: #c31e1e;

    color: #fff;

    z-index: 9999;

}
</style>



<div class="tradonof tof-color-red" id="on_off">
	<h2>Trading:<strong>OFF</strong></h2>
</div>

<?php if ($this->session->userdata('special_role') == 1) {?>


<?php }?>


<script>

$( "div" ).hide();
$( "#on_off" ).click(function( event ) {
  event.preventDefault();
  $( this ).hide("slow");
  setTimeout(function() { $("#on_off").show(); }, 300000);
});


</script>


<script src="<?php echo ASSETS; ?>components/library/modernizr/modernizr.js?v=v1.2.3"></script>

<script src="<?php echo ASSETS; ?>components/plugins/less-js/less.min.js?v=v1.2.3"></script>

<script src="<?php echo ASSETS; ?>components/modules/admin/charts/flot/assets/lib/excanvas.js?v=v1.2.3"></script>

<script src="<?php echo ASSETS; ?>components/plugins/browser/ie/ie.prototype.polyfill.js?v=v1.2.3"></script>

<script src="<?php echo ASSETS; ?>components/library/jquery-ui/js/jquery-ui.min.js?v=v1.2.3"></script>

<script src="<?php echo ASSETS; ?>components/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js?v=v1.2.3"></script>









<?php  if($uri=='highchart'){ ?>

<script src="<?php echo ASSETS; ?>js/highcharts/highcharts.js"></script>

<script src="<?php

echo ASSETS;

?>js/highcharts/series-label.js"></script>

<script src="<?php

echo ASSETS;

?>js/highcharts/exporting.js"></script>

<script src="<?php

echo ASSETS;

?>js/highcharts/export-data.js"></script>









<link href="<?php

echo ASSETS;

?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">

<script src="<?php

echo ASSETS;

?>buyer_order/moment-with-locales.js"></script>

<script src="<?php

echo ASSETS;

?>buyer_order/bootstrap-datetimepicker.js"></script>

<?php }?>

