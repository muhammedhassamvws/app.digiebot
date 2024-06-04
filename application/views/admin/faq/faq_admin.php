<style type="text/css">
  ul.mylist {
    list-style: none;
    width: 100%;
    background: #fff;
}

mylist.li {
    width: 100%;
    background: #eeee;
}

li.list_item {
    text-align: center;
    border-bottom: 2px solid #e5e6ee;
    /* float: left; */
    padding: 21px 80px;
    position: relative;
    width: 100%;
    box-shadow: 0 24px 153px 0 rgba(0,0,53,0.07);
}

span.text-m {
    float: left;
}
span.text-m3 {
    font-weight: 500;
}
span.btn-m {
    float: right;
}
</style>
<div id="content">
<div class="heading-buttons bg-white border-bottom innerAll">
   <h1 class="content-heading padding-none pull-left">Reports</h1>
   <div class="clearfix"></div>
</div>
<div class="bg-white innerAll border-bottom">
   <ul class="menubar">
      <li class="active"><a href="<?php echo SURL; ?>admin/faq_admin/">FAQ Admin</a></li>
      <li><a href="<?php echo SURL; ?>admin/faq_admin/faq_listing">FAQ Listing</a></li>
   </ul>
</div>
<div class="innerAll spacing-x2">
   <div class="widget widget-inverse">
      <!-- Widget heading -->
      <div class="widget-head">
         <h4 class="heading">Add FAQ Page</h4>
      </div>
      <!-- // Widget heading END -->
      <div class="widget-body" style="background: #ccc;">
      	<div class="alert alert-success alert-dismissable" style="display: none;" id="resp_div">Question added Successfully</div>
      	<div class="form-group">
		    <label for="email">Question:</label>
		    <textarea class="form-control" name="faq_question" id="faq_question"></textarea>
	  	</div>
	  <div class="form-group">
	    <label for="pwd">Answer:</label>
	    <textarea class="form-control" name="faq_answer" id="faq_answer"></textarea>
	  </div>
	  <div class="form-group">
	    <label>Type</label>
	    <select class="form-control" id="faq_type" name="faq_type">
	    	<option>Digiebot</option>
        <option>Balance</option>
	    	<option>Trades</option>
	    	<option>Rules</option>
	    	<option>Triggers</option>
	    	<option>Errors</option>
        <option>Others</option>
	    </select>
	  </div>
    <input type="hidden" value="empty" id="hidden-id" name="hidden_id">
	  <button type="submit" id="submit" class="btn btn-success">Submit</button>
  </div>
   </div>

   <!-- WIDGET FOR CLIENT QUESTIONS -->
   <div class="widget widget-inverse">
      <!-- Widget heading -->
      <div class="widget-head">
         <h4 class="heading">Asked Questions</h4>
      </div>
      <!-- // Widget heading END -->
      <div class="widget-body" style="background: #ccc;">
        <ul class="mylist">
          <?php if (!empty($questions)) {
    foreach ($questions as $key => $value) {
        ?>
              <li class="list_item"><span class="text-m"><?=$value['faq_question'];?></span> <span class="text-m3"><?=$value['username'];?></span> <span class="btn-m"><button class="btn btn-info btn-xs li_itm" id="<?=$value['_id'];?>" data-q = "<?=$value['faq_question'];?>">Answer</button></span></li>
            <?php }
}?>
        </ul>
      </div>
</div>
</div>
<script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
<script type="text/javascript">
CKEDITOR.replace('faq_question', {
  skin: 'moono',
  enterMode: CKEDITOR.ENTER_BR,
  shiftEnterMode:CKEDITOR.ENTER_P,
  toolbar: [{ name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
             { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
             { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
             { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
             { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
             { name: 'links', items: [ 'Link', 'Unlink' ] },
             { name: 'insert', items: [ 'Image'] },
             { name: 'spell', items: [ 'jQuerySpellChecker' ] },
             { name: 'table', items: [ 'Table' ] }
             ],
});

CKEDITOR.replace('faq_answer', {
  skin: 'moono',
  enterMode: CKEDITOR.ENTER_BR,
  shiftEnterMode:CKEDITOR.ENTER_P,
  toolbar: [{ name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
             { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
             { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
             { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
             { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
             { name: 'links', items: [ 'Link', 'Unlink' ] },
             { name: 'insert', items: [ 'Image'] },
             { name: 'spell', items: [ 'jQuerySpellChecker' ] },
             { name: 'table', items: [ 'Table' ] }
             ],
});
</script>
<script type="text/javascript">
	$(document).ready(function(e){
		$("body").on("click","#submit",function(event){
			var faq_question = CKEDITOR.instances.faq_question.getData();
			var faq_answer = CKEDITOR.instances.faq_answer.getData();
			var faq_type = $('#faq_type').val();
      var hidden_id = $('#hidden-id').val();

			//alert(faq_question+" => "+faq_answer+" => "+faq_type);
			$.ajax({
				url: "<?php echo SURL; ?>admin/faq_admin/faq_add_process",
				type: "POST",
				data: {faq_question:faq_question,faq_answer:faq_answer,faq_type:faq_type,hidden_id:hidden_id},
				success: function(response){
					$("#resp_div").show();
					setTimeout(function() {
		                 $("#resp_div").hide();
		            }, 3000);
				}
			});
		});

    $("body").on("click",".li_itm",function(ev) {
      var data = $(this).data('q');
      var id = $(this).attr("id");
      console.log()
      $("#hidden-id").val(id);
      CKEDITOR.instances.faq_question.setData(data);
    });
	});
</script>