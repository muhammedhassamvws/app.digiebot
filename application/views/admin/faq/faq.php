<style>
/**-----------------------------------FAQ--**/

.faq_tabs_box {
    float: left;
    width: 100%;
}
.faq_tabs_box {
    float: left;
    width: 100%;
}
.faq_tabs_header {
    background: #fff none repeat scroll 0 0;
    float: left;
    width: 100%;
  box-shadow:0 0 45px -5px rgba(0,0,0,0.1);
}
.faq_tabs_header > ul {
    float: left;
    list-style: outside none none;
    margin: 0;
    padding: 0;
    text-align: center;
    width: 100%;
}
.tab_head {
    display: inline-block;
    margin: 0 15px;
}
.tab_head > a {
    color: #313131;
    display: block;
    font-family:"Gotham_Medium";
    font-size: 15px;
    padding: 25px 15px 20px;
    text-decoration: none;
    width: 100%;
}
.tab_head.active > a, .tab_head:hover > a {
    border-bottom: 3px solid #1d2d5f;
}
.faq_tabs_body {
    background: #fff none repeat scroll 0 0;
    float: left;
    margin: 40px 0;
    width: 100%;
  box-shadow:0 0 45px -5px rgba(0,0,0,0.1);
}
.faq_tabs_body > ul {
    float: left;
    list-style: outside none none;
    margin: 0;
    padding: 0;
    width: 100%;
}
.tab_body {
    display: none;
    float: left;
    width: 100%;
}
.tab_body.active {
    display: block;
}
.tab_body > h1 {
    float: left;
    font-size: 25px;
    margin: 5px 10px;
    width: 100%;
}
.faq_item {
    border-bottom: 2px solid #e5e6ee;
    float: left;
    padding: 21px 80px;
    position: relative;
    width: 100%;
  	box-shadow: 0 24px 153px 0 rgba(0,0,53,0.07);
  	counter-increment: my-awesome-counter;
}
.faq_item_head {
    float: left;
    margin: 15px 0;
    width: 100%;
}
.faq_item_head > h2 {
    float: left;
    font-family:"Gotham_Medium";
    font-size: 18px;
    margin: 0;
    width: 100%;
  color:#313131;
}
.faqicon_updown {
    background: #18a6d1 none repeat scroll 0 0;
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    font-size: 21px;
    height: 42px;
    padding-top: 5px;
    position: absolute;
    right: 30px;
    text-align: center;
    top: 25px;
    transition: all 0.3s ease 0s;
    width: 42px;
}
.faq_item_body {
    display: none;
    float: left;
    width: 100%;
}
/*.faq_item_head > h2::before {
    background: #313131;
    border-radius: 50%;
    content: "";
    height: 8px;
    left: 41px;
    position: absolute;
    top: 43px;
    width: 8px;
}*/
.faq_item::before {
    content: counter(my-awesome-counter) ". ";
    color: #333;
    font-weight: bold;
    position: absolute;
    left: 43px;
    font-size: 18px;
    top: 32px;
}
.faq_item.active .faq_item_body {
    display: block;
	padding:0 20px;
}
.faq_item_body > p {
    float: left;
    margin: 0 0 15px;
    width: 100%;
}
.faq_item.active .faqicon_updown, .faq_item:hover .faqicon_updown {
    background: #1E3374;
    color: #fff;
}
.minheight60{
  min-height:60px !important;
}
@media(max-width:480px){
  .faq_item {
    padding: 10px 40px;
  }
  .faq_item_head > h2::before {
    left: 15px;
    top: 30px;
  }
  .faq_item_head > h2 {
    font-size: 14px;
  }
  .faqicon_updown {
    font-size: 14px;
    height: 25px;
    padding-top: 3px;
    right: 15px;
    top: 20px;
    width: 25px;
  }
  .faq_item_body > p {
    font-size: 12px;
  }
}
.heading {
    background: #fff none repeat scroll 0 0;
    text-align: center;
    width: 100%;
    box-shadow: 0 0 45px -5px rgba(0,0,0,0.1);
    padding: 25px 0px;
    margin: 18px 0px;
    color: #1e3374;
    font-size: 10px;
}

.heading h1 {
    font-size: 29px;
    display: inline-block;
    border-bottom: 4px solid;
}
.ask-questions {
    background: #1E3374;
    border-radius: 50%;
    bottom: 20px;
    height: 55px;
    position: fixed;
    right: 20px;
    text-align: center;
    width: 55px;
    z-index: 99999;
}
.ask-popup-trigger img {
  margin: 4px 0 0;
}
.ask-popup-trigger {
  bottom: 0;
  color: #fff;
  cursor: pointer;
  height: 38px;
  left: 0;
  line-height: 1;
  position: absolute;
  right: 0;
  top: 0;
  width: 100%;
  margin: auto;
}
.modal-dialog .modal-content {
    border: none;
}
.modal-content .modal-header {
    background: #1E3374;
    color: #fff;
    text-align: center;
	padding: 5px 0;
}
.modal-content .modal-header .modal-title {
    font-size: 18px;
}
.modal-content .modal-header .close {
    color: #fff;
    font-size: 26px;
}
.ask-ques-textarea {
    width: 100%;
    min-height: 100px;
    padding: 10px;
    border: 1px solid #ccc;
	color: #333;
}
.modal-dialog .modal-body {
    padding: 20px;
}
.modal .modal-dialog {
    width: 600px;
    margin: 150px auto;
}
.af-submit-btn {
    background: #1E3374;
    padding: 10px 30px;
    color: #fff;
	border:2px solid #1E3374;
}
.af-submit-btn:hover , .af-submit-btn:focus{
	background:#fff;
	color:#1E3374;
}
.modal-dialog .modal-footer {
    padding: 20px;
}
</style>
<script>
jQuery("body").on("click",".So_How_does_faq_title a",function(){
    jQuery(".So_How_does_faq_panel").hide();
    jQuery(this).closest(".So_How_does_faq_box").find(".So_How_does_faq_panel").show();
  });
  jQuery("body").on("click",".tab_head",function(){
    jQuery(".tab_head").removeClass("active");
    jQuery(".tab_body").removeClass("active");
    var this_tab_id = jQuery(this).attr("tab_id");
    jQuery("[tab_id="+this_tab_id+"]").addClass("active");
    jQuery("[tabb_id="+this_tab_id+"]").addClass("active");
  });
  jQuery("body").on("click",".faq_item_head",function(){

    if(jQuery(this).closest(".faq_item").hasClass("active")){

      jQuery(this).closest(".faq_item").removeClass("active");
      jQuery(this).closest(".faq_item").find(".fa").removeClass("fa-angle-up");
      jQuery(this).closest(".faq_item").find(".fa").addClass("fa-angle-down");

    }else{
      jQuery(this).closest(".faq_item").addClass("active");
      jQuery(this).closest(".faq_item").find(".fa").addClass("fa-angle-up");
      jQuery(this).closest(".faq_item").find(".fa").removeClass("fa-angle-down");
      }

  });
</script>
<div id="content">

    <div class="heading-buttons bg-white border-bottom innerAll">
        <h1 class="content-heading padding-none pull-left">Frequently Asked Questions</h1>
        <div class="clearfix"></div>
    </div>
    <div class="bg-white innerAll border-bottom">
        <ul class="menubar">
            <li><a href="#">FAQ's</a></li>
        </ul>
    </div>
    <div class="innerAll spacing-x2">
        <div class="row">
          <div class="col-md-12"><div class="heading"><h1>FAQ Questions</h1></div></div>
            <div class="col-xs-12">
              <div class="faq_tabs_box">
                  <div class="faq_tabs_header">
                      <ul>
                        <li class="tab_head active" tab_id="5"><a href="javascript:void(0);">Digiebot FAQs <span class="badge badge-info badge-stroke"> <?=count($faq['Digiebot'])?></span></a></li>
                        <li class="tab_head" tab_id="6"><a href="javascript:void(0);">Balance <span class="badge badge-info badge-stroke"> <?=count($faq['Balance'])?></span></a></li>
                        <li class="tab_head" tab_id="7"><a href="javascript:void(0);">Trades <span class="badge badge-info badge-stroke"> <?=count($faq['Trades'])?></span></a></li>
                        <li class="tab_head" tab_id="8"><a href="javascript:void(0);">Rules <span class="badge badge-info badge-stroke"> <?=count($faq['Rules'])?></span></a></li>
                        <li class="tab_head" tab_id="10"><a href="javascript:void(0);">Triggers <span class="badge badge-info badge-stroke"> <?=count($faq['Triggers'])?></span></a></li>
                        <li class="tab_head" tab_id="11"><a href="javascript:void(0);">Errors <span class="badge badge-info badge-stroke"> <?=count($faq['Errors'])?></span></a></li>
                        <li class="tab_head" tab_id="12"><a href="javascript:void(0);">Others <span class="badge badge-info badge-stroke"><?=count($faq['Others'])?></span></a></li>
                      </ul>
                  </div>
                  <div class="faq_tabs_body">
                      <ul>
                          <li class="tab_body active" tabb_id="5">
                            <?php if (!empty($faq['Digiebot'])) {
    foreach ($faq['Digiebot'] as $key => $value) {
        ?>
                                        <div class="faq_item">
                                            <div class="faq_item_head">
                                                <h2><?php echo $value['faq_question'] ?></h2>
                                                <span class="faqicon_updown"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="faq_item_body">
                                               <div><?php echo $value['faq_answer'] ?></div>
                                            </div>
                                        </div>
                                    <?php }
}?>
                          </li>
                          <li class="tab_body" tabb_id="6">

                             <?php if (!empty($faq['Balance'])) {
    foreach ($faq['Balance'] as $key => $value) {
        ?>
                                        <div class="faq_item">
                                            <div class="faq_item_head">
                                                <h2><?php echo $value['faq_question'] ?></h2>
                                                <span class="faqicon_updown"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="faq_item_body">
                                               <div><?php echo $value['faq_answer'] ?></div>
                                            </div>
                                        </div>
                                    <?php }
}?>

                          </li>

                          <li class="tab_body" tabb_id="7">
<?php if (!empty($faq['Trades'])) {
    foreach ($faq['Trades'] as $key => $value) {
        ?>
                                        <div class="faq_item">
                                            <div class="faq_item_head">
                                                <h2><?php echo $value['faq_question'] ?></h2>
                                                <span class="faqicon_updown"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="faq_item_body">
                                               <div><?php echo $value['faq_answer'] ?></div>
                                            </div>
                                        </div>
                                    <?php }
}?>

                          </li>

                          <li class="tab_body" tabb_id="8">

                                                           <?php if (!empty($faq['Rules'])) {
    foreach ($faq['Rules'] as $key => $value) {
        ?>
                                        <div class="faq_item">
                                            <div class="faq_item_head">
                                                <h2><?php echo $value['faq_question'] ?></h2>
                                                <span class="faqicon_updown"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="faq_item_body">
                                               <div><?php echo $value['faq_answer'] ?></div>
                                            </div>
                                        </div>
                                    <?php }
}?>


                          </li>

                          <li class="tab_body" tabb_id="10">

                                                          <?php if (!empty($faq['Triggers'])) {
    foreach ($faq['Triggers'] as $key => $value) {
        ?>
                                        <div class="faq_item">
                                            <div class="faq_item_head">
                                                <h2><?php echo $value['faq_question'] ?></h2>
                                                <span class="faqicon_updown"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="faq_item_body">
                                               <div><?php echo $value['faq_answer'] ?></div>
                                            </div>
                                        </div>
                                    <?php }
}?>


                          </li>
                          <li class="tab_body" tabb_id="11">

                                                          <?php if (!empty($faq['Errors'])) {
    foreach ($faq['Errors'] as $key => $value) {
        ?>
                                        <div class="faq_item">
                                            <div class="faq_item_head">
                                                <h2><?php echo $value['faq_question'] ?></h2>
                                                <span class="faqicon_updown"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="faq_item_body">
                                               <div><?php echo $value['faq_answer'] ?></div>
                                            </div>
                                        </div>
                                    <?php }
}?>


                          </li>
                          <li class="tab_body" tabb_id="12">

                                                           <?php if (!empty($faq['Others'])) {
    foreach ($faq['Others'] as $key => $value) {
        ?>
                                        <div class="faq_item">
                                            <div class="faq_item_head">
                                                <h2><?php echo $value['faq_question'] ?></h2>
                                                <span class="faqicon_updown"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="faq_item_body">
                                               <div><?php echo $value['faq_answer'] ?></div>
                                            </div>
                                        </div>
                                    <?php }
}?>


                          </li>

                      </ul>
                  </div>
                  <div class="faq_tabs_header">
                      <ul>

                        <li class="tab_head active" tab_id="5"><a href="javascript:void(0);">Digiebot FAQs</a></li>
                        <li class="tab_head" tab_id="6"><a href="javascript:void(0);">Balance</a></li>
                        <li class="tab_head" tab_id="7"><a href="javascript:void(0);">Trades</a></li>
                        <li class="tab_head" tab_id="8"><a href="javascript:void(0);">Rules</a></li>
                        <li class="tab_head" tab_id="10"><a href="javascript:void(0);">Triggers</a></li>
                        <li class="tab_head" tab_id="11"><a href="javascript:void(0);">Errors</a></li>
                        <li class="tab_head" tab_id="12"><a href="javascript:void(0);">Others</a></li>

                      </ul>
                  </div>
              </div>
          </div>
        </div>
    </div>
    <div class="ask-questions">
    	<a class="ask-popup-trigger" href="javascript:void(0)" data-toggle="modal" data-target="#myModal" title="Ask Your Question"><img src="http://app.digiebot.com/assets/images/dialogue.png" /></a>
    </div>
    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Ask Your Question</h4>
        </div>
        <div class="modal-body">
          <textarea class="ask-ques-textarea" placeholder="Ask Your Question..."></textarea>
        </div>
        <div class="modal-footer">
        	<a class="af-submit-btn" href="javascript:void(0)">Submit</a>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $("body").on("click",".af-submit-btn",function(){
      var faq_question = $('.ask-ques-textarea').val();
      //alert(faq_question+" => "+faq_answer+" => "+faq_type);
      $.ajax({
        url: "<?php echo SURL; ?>admin/faq_admin/faq_add_client_process",
        type: "POST",
        data: {faq_question:faq_question},
        success: function(response){
          $("#myModal").modal('hide');
          $.alert({
            icon: 'fa fa-question-circle-o',
            theme: 'material',
            closeIcon: true,
            animation: 'scale',
            type: 'green',
            title: "Alert",
            content: "Your <strong>Question</strong> has been added successfully"
        });
        }
      });
  });
</script>