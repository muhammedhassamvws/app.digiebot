<style type="text/css">
.timeline {
    list-style: none;
    height: 80px;
}
	/*.date{
		color: green;
		background: #efefef;
	}*/
.date {
    position: relative;
    width: 70px;
    font-family: Trebuchet MS, sans-serif;
    color: #218c39;
    margin: 0 0 0 0;
    font-weight: 700;
}
.day, .month, .year {
   position: absolute;
}

.day {
   font-size: 30px;
   top: 15px;
}

.month {
   top: 0;
   left: 0;
   font-size: 18px;
}
.time{
   top: 55px;
   position:absolute;
}

.year {
   top: 19px;
   right: 0;
   font-size: 20px;
   rotation: -90deg !important;
   /* ** Hacks ** */
   -webkit-transform: rotate(-90deg);
   -moz-transform: rotate(-90deg);
}
.ellipsis {
    margin-left: 70px;
    position: relative;
    top: 28px;
}
</style>
<div id="content">
   <h1 class="content-heading bg-white border-bottom">Login History</h1>
   <div class="innerAll spacing-x2">
      <div class="widget widget-inverse">
         <div class="widget-head">
            <h3 class="heading"><i class="icon-manager"></i>Login Timeline</h3>
         </div>
         <div class="widget-body">
            <div class="row">
               <div class="col-md-12">
                  <!-- Timeline Widget -->
                  <div class="widget-timeline">
                     <ul class="list-timeline">
                        <!-- Item -->
                        <?php
if (!empty($user_info)) {
	foreach ($user_info as $key => $value) {
		?>
                        <li class="timeline">
                          <!--  <span class="date"><b><i><?php echo date("Y-m-d H:i:s", strtotime($value['login_date_time'])) ?></i></b>&nbsp;:&nbsp;&nbsp;</span> -->

                          <div class="date">
                             <span class="day"><?php echo date("d", strtotime($value['login_date_time'])) ?></span>
                             <span class="month"><?php echo date("M", strtotime($value['login_date_time'])) ?></span>
                             <span class="year"><?php echo date("Y", strtotime($value['login_date_time'])) ?></span>
                             <span class="time"><?php echo date("H:i:s", strtotime($value['login_date_time'])) ?></span>
                           </div>

                           <span class="ellipsis">Login from IP <b><?php echo $value['login_ip'] ?></b> Location: <b><?php echo $value['login_location'] ?></b> Login Device: <b><?php echo $value['login_device_browser'] ?></b></span>
                           <div class="clearfix"></div>
                        </li>
                        	<?php }
}
?>
                        <!-- // Item END -->
                  </div>
                  <!-- Timeline Widget END -->
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- // Widget END -->
</div>
</div>