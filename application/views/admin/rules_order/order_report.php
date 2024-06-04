<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style type="text/css">
.center_div{
  margin: 0 auto;
  width:100% /* value of your choice which suits your alignment */
}

.menu {
  font-weight: bold;
  padding: 10px;
  font-size: 0.8em;
  width: 100%;
  background: white;
  position: relative;
  text-align: center;
  margin: 51px 0px;
}
.menu ul {
  margin: 0;
  padding: 0;
  list-style-type: none;
  list-style-image: none;
}
.menu li {
  display: block;
  padding:15px 0 15px 0;
  }
.menu li:hover{
  display: block;
  padding:15px 0 15px 0;
}
.menu ul li a {
 text-decoration:none;
 margin: 0px;
 color:#fff;
}
.menu ul li a:hover {
 color: #fff;
 text-decoration:none;
}
.menu a{
  text-decoration:none;
  color:white;
}
.menu a:hover{
  text-decoration:none;
  color:white;
}
</style>
<div id="content">
        <div class="heading-buttons bg-white border-bottom innerAll">
            <h1 class="content-heading padding-none pull-left"><?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?> Report</h1>
            <div class="clearfix"></div>
        </div>
        <div class="bg-white innerAll border-bottom">
            <ul class="menubar">
                <li><a href="/admin/reports/">Reports</a></li>
            </ul>
        </div>
        <div class="innerAll spacing-x2">
            <div class="container center_div">
              <div class="widget-body">
                    <!-- Table -->
                    <div style="text-align: center;"><h4>Log Information</h4></div>
                    <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center checkboxs">
                        <thead>
                            <tr>
                              <th>Log Message</th>
                              <th>Created Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($log as $key => $value) {?>
                            <tr class="selectable" style="height: 50px;">
                                <td class="center"><?php echo $value['log_msg'] ?></td>
                                <td class="center"><?php echo date("F j, Y, g:i:s a", strtotime($value['created_date'])) ?></td>
                            </tr>
                          <?php }?>
                        </tbody>
                    </table>
                    <!-- // Table END -->
              </div>


            <div class="row">
              <div class="col-md-12">
            <div class="widget-body">
            </div>
        </div>
      </div>
  </div>
</div>
</div>
<script type="text/javascript">
$( ".close" ).hide();
$( ".menu" ).hide();
$( ".hamburger" ).click(function() {
  $( ".menu" ).slideToggle( "slow", function() {
    $( ".hamburger" ).hide();
    $( ".close" ).show();
  });
});

$( ".close" ).click(function() {
  $( ".menu" ).slideToggle( "slow", function() {
    $( ".close" ).hide();
    $( ".hamburger" ).show();
  });
});
  </script>