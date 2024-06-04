<link href="<?php echo ASSETS; ?>buyer_order/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="<?php echo ASSETS; ?>buyer_order/moment-with-locales.js"></script>
<script src="<?php echo ASSETS; ?>buyer_order/bootstrap-datetimepicker.js"></script>
<style>
.Input_text_s {
    /* display: inline; */
    position: relative;
}

.Input_text_s i {
    position: absolute;
    top: 33px;
    right: 10px;
}
.role{
   margin-top:10%;
   margin-bottom:-10%;
}
/*** custom checkboxes ***/
@import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);
input[type=checkbox] { display:none; } /* to hide the checkbox itself */
input[type=checkbox] + label:before {
  font-family: FontAwesome;
  display: inline-block;
}
.custom_label {
    font-size: 25px;
    width: 100%;
    text-align: center;
}
input[type=checkbox] + label:before { content: "\f096"; } /* unchecked icon */
input[type=checkbox] + label:before { letter-spacing: 10px; } /* space between checkbox and label */

input[type=checkbox]:checked + label:before { content: "\f046"; } /* checked icon */
input[type=checkbox]:checked + label:before { letter-spacing: 5px; } /* allow space for check mark */

</style>

<style>
.loaderImage {
	float: left;
	width: 100%;
	position: relative;
}
.loaderimagbox {
	background: rgba(255, 255, 255, 0.78);
	position: absolute;
	z-index: 9;
	width: 100%;
	height: 100%;
	left: 0;
	top: 0;
	bottom: 0;
}
.loaderimagbox img {
	position: absolute;
	margin: auto;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	border-radius: 50%;
}
#content{
   background:white;
}
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Users</h1>
  <div class="innerAll bg-white border-bottom">
  <ul class="menubar">
    <li class="active"><a href="<?php echo SURL; ?>admin/users">Users</a></li>
	</ul>
  </div>
  <div class="innerAll spacing-x2">

      <div class="alert alert-success alert-dismissable successMessage" style="display:none;"><strong>Success !</strong> User Application mode updated Successfully</div>
      
      <div class="alert alert-success alert-dismissable unblockSuccessMessage" style="display:none;"><strong>Success !</strong> User Unblocked Successfully</div>

      <div class="alert alert-danger error errormessage" style="display:none;"><strong>Oops !</strong> Something went wrong .</div>

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
      <?php $filter_user_data = $this->session->userdata('filter_user_data');?>
      <div class="margin-bottom:1%;">
         <div class="widget-body">
            <form method="POST" action="<?php echo SURL; ?>admin/users/index">
              <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter Query: </label>
                     <input id="filter_by_name" name="filter_by_name" type="text" class="form-control filter_by_name_margin_bottom_sm" placeholder="Search By Name, Email, Phone" value="<?=(!empty($filter_user_data['filter_by_name']) ? $filter_user_data['filter_by_name'] : "")?>">
                     <i class="fa fa-search glassflter" aria-hidden="true"></i>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter By User Id: </label>
                     <input id="filter_by_id" name="filter_by_id" type="text" class="form-control filter_by_id_margin_bottom_sm" placeholder="Search By User Id" value="<?=(!empty($filter_user_data['filter_by_id']) ? $filter_user_data['filter_by_id'] : "")?>">
                     <i class="fa fa-search glassflter1" aria-hidden="true"></i>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter IP: </label>
                     <select id="filter_by_ip" name="filter_by_ip" type="text" class="form-control filter_by_name_margin_bottom_sm">
                        <option value ="" <?=(($filter_user_data['filter_by_ip'] == "") ? "selected" : "")?>>Search By IP</option>
                         <?php 
                            foreach($allowed_ips as $key=>$value)
                            { ?>
                               <option <?php echo $filter_user_data['filter_by_ip'] == $value ? "selected":'' ?> value="<?php echo $key ?>"><?php echo $key ?></option> 
                            <?php }
                          ?>

                     </select>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter Mode: </label>
                     <select id="filter_by_mode" name="filter_by_mode" type="text" class="form-control filter_by_name_margin_bottom_sm">
                        <option value="">Search By Mode</option>
                        <option value="live"<?=(($filter_user_data['filter_by_mode'] == "live") ? "selected" : "")?>>Live</option>
                        <option value="test"<?=(($filter_user_data['filter_by_mode'] == "test") ? "selected" : "")?>>Test</option>
                        <option value="both"<?=(($filter_user_data['filter_by_mode'] == "both") ? "selected" : "")?>>Both</option>
                     </select>
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter App: </label>
                     <select id="filter_by_app" name="filter_by_app" type="text" class="form-control filter_by_name_margin_bottom_sm">
                        <option value="">Search By Mode</option>
                        <option value="all" <?=(($filter_user_data['filter_by_app'] == "all") ? "selected" : "")?>>All</option>
                        <option value="enabled" <?=(($filter_user_data['filter_by_app'] == "enabled") ? "selected" : "")?>>App enabled</option>
                        <option value="disabled" <?=(($filter_user_data['filter_by_app'] == "disabled") ? "selected" : "")?>>App disabled</option>
                     </select>
                  </div>
               </div>
               
               <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>Filter Exchange: </label>
                     <select id="filter_exchange" name="filter_exchange" type="text" class="form-control filter_by_name_margin_bottom_sm">
                        <option value="">Search By Exchange</option>
                        <option value="binance" <?=(($filter_user_data['filter_exchange'] == "binance" || empty($filter_user_data['filter_exchange'])) ? "selected" : "")?>>Binance</option>
                        <option value="bam" <?=(($filter_user_data['filter_exchange'] == "bam") ? "selected" : "")?>>Bam</option>
                        <option value="kraken" <?=(($filter_user_data['filter_exchange'] == "kraken") ? "selected" : "")?>>Kraken</option>
                        <option value="dg" <?=(($filter_user_data['filter_exchange'] == "dg") ? "selected" : "")?>>Digie</option>
                        <option value="okex" <?=(($filter_user_data['filter_exchange'] == "okex") ? "selected" : "")?>>Okex</option>
                     </select>
                  </div>
               </div>

               <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>From Date Range: </label>
                     <input id="filter_by_start_date" name="filter_by_start_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_start_date']) ? $filter_user_data['filter_by_start_date'] : "")?>">
                     <i class="glyphicon glyphicon-calendar"></i>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                     <label>To Date Range: </label>
                     <input id="filter_by_end_date" name="filter_by_end_date" type="text" class="form-control datetime_picker filter_by_name_margin_bottom_sm" placeholder="Search By Date" value="<?=(!empty($filter_user_data['filter_by_end_date']) ? $filter_user_data['filter_by_end_date'] : "")?>">
                     <i class="glyphicon glyphicon-calendar"></i>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-4" style="padding-bottom: 6px;">
                  <div class="Input_text_s">
                  <label>Filter Country: </label>
                  <select id="filter_country" name="filter_country" type="text" class="form-control filter_by_country_margin_bottom_sm">
                  <option value="">Select a country</option>
                  <option value="Afghanistan" <?=(($filter_user_data['filter_country'] == "Afghanistan") ? "selected" : "")?>>Afghanistan</option>
                  <option value="Åland Islands" <?=(($filter_user_data['filter_country'] == "Åland Islands") ? "selected" : "")?>>Åland Islands</option>
                  <option value="Albania" <?=(($filter_user_data['filter_country'] == "Albania") ? "selected" : "")?>>Albania</option>
                  <option value="Algeria" <?=(($filter_user_data['filter_country'] == "Algeria") ? "selected" : "")?>>Algeria</option>
                  <option value="American Samoa" <?=(($filter_user_data['filter_country'] == "American Samoa") ? "selected" : "")?>>American Samoa</option>
                  <option value="Andorra" <?=(($filter_user_data['filter_country'] == "Andorra") ? "selected" : "")?>>Andorra</option>
                  <option value="Angola" <?=(($filter_user_data['filter_country'] == "Angola") ? "selected" : "")?>>Angola</option>
                  <option value="Anguilla" <?=(($filter_user_data['filter_country'] == "Anguilla") ? "selected" : "")?>>Anguilla</option>
                  <option value="Antarctica" <?=(($filter_user_data['filter_country'] == "Antarctica") ? "selected" : "")?>>Antarctica</option>
                  <option value="Antigua and Barbuda" <?=(($filter_user_data['filter_country'] == "Antigua and Barbuda") ? "selected" : "")?>>Antigua and Barbuda</option>
                  <option value="Argentina" <?=(($filter_user_data['filter_country'] == "Argentina") ? "selected" : "")?>>Argentina</option>
                  <option value="Armenia" <?=(($filter_user_data['filter_country'] == "Armenia") ? "selected" : "")?>>Armenia</option>
                  <option value="Aruba" <?=(($filter_user_data['filter_country'] == "Aruba") ? "selected" : "")?>>Aruba</option>
                  <option value="Australia" <?=(($filter_user_data['filter_country'] == "Australia") ? "selected" : "")?>>Australia</option>
                  <option value="Austria" <?=(($filter_user_data['filter_country'] == "Austria") ? "selected" : "")?>>Austria</option>
                  <option value="Azerbaijan" <?=(($filter_user_data['filter_country'] == "Azerbaijan") ? "selected" : "")?>>Azerbaijan</option>
                  <option value="Bahamas" <?=(($filter_user_data['filter_country'] == "Bahamas") ? "selected" : "")?>>Bahamas</option>
                  <option value="Bahrain" <?=(($filter_user_data['filter_country'] == "Bahrain") ? "selected" : "")?>>Bahrain</option>
                  <option value="Bangladesh" <?=(($filter_user_data['filter_country'] == "Bangladesh") ? "selected" : "")?>>Bangladesh</option>
                  <option value="Barbados" <?=(($filter_user_data['filter_country'] == "Barbados") ? "selected" : "")?>>Barbados</option>
                  <option value="Belarus" <?=(($filter_user_data['filter_country'] == "Belarus") ? "selected" : "")?>>Belarus</option>
                  <option value="Belgium" <?=(($filter_user_data['filter_country'] == "Belgium") ? "selected" : "")?>>Belgium</option>
                  <option value="Belize" <?=(($filter_user_data['filter_country'] == "Belize") ? "selected" : "")?>>Belize</option>
                  <option value="Benin" <?=(($filter_user_data['filter_country'] == "Benin") ? "selected" : "")?>>Benin</option>
                  <option value="Bermuda" <?=(($filter_user_data['filter_country'] == "Bermuda") ? "selected" : "")?>>Bermuda</option>
                  <option value="Bhutan" <?=(($filter_user_data['filter_country'] == "Bhutan") ? "selected" : "")?>>Bhutan</option>
                  <option value="Bolivia" <?=(($filter_user_data['filter_country'] == "Bolivia") ? "selected" : "")?>>Bolivia</option>
                  <option value="Bonaire, Sint Eustatius, and Saba" <?=(($filter_user_data['filter_country'] == "Bonaire, Sint Eustatius, and Saba") ? "selected" : "")?>>Bonaire, Sint Eustatius, and Saba</option>
                  <option value="Bosnia and Herzegovina" <?=(($filter_user_data['filter_country'] == "Bosnia and Herzegovina") ? "selected" : "")?>>Bosnia and Herzegovina</option>
                  <option value="Botswana" <?=(($filter_user_data['filter_country'] == "Botswana") ? "selected" : "")?>>Botswana</option>
                  <option value="Bouvet Island" <?=(($filter_user_data['filter_country'] == "Bouvet Island") ? "selected" : "")?>>Bouvet Island</option>
                  <option value="Brazil" <?=(($filter_user_data['filter_country'] == "Brazil") ? "selected" : "")?>>Brazil</option>
                  <option value="British Indian Ocean Territory" <?=(($filter_user_data['filter_country'] == "British Indian Ocean Territory") ? "selected" : "")?>>British Indian Ocean Territory</option>
                  <option value="Brunei" <?=(($filter_user_data['filter_country'] == "Brunei") ? "selected" : "")?>>Brunei</option>
                  <option value="Bulgaria" <?=(($filter_user_data['filter_country'] == "Bulgaria") ? "selected" : "")?>>Bulgaria</option>
                  <option value="Burkina Faso" <?=(($filter_user_data['filter_country'] == "Burkina Faso") ? "selected" : "")?>>Burkina Faso</option>
                  <option value="Burundi" <?=(($filter_user_data['filter_country'] == "Burundi") ? "selected" : "")?>>Burundi</option>
                  <option value="Cabo Verde" <?=(($filter_user_data['filter_country'] == "Cabo Verde") ? "selected" : "")?>>Cabo Verde</option>
                  <option value="Cambodia" <?=(($filter_user_data['filter_country'] == "Cambodia") ? "selected" : "")?>>Cambodia</option>
                  <option value="Cameroon" <?=(($filter_user_data['filter_country'] == "Cameroon") ? "selected" : "")?>>Cameroon</option>
                  <option value="Canada" <?=(($filter_user_data['filter_country'] == "Canada") ? "selected" : "")?>>Canada</option>
                  <option value="Cayman Islands" <?=(($filter_user_data['filter_country'] == "Cayman Islands") ? "selected" : "")?>>Cayman Islands</option>
                  <option value="Central African Republic" <?=(($filter_user_data['filter_country'] == "Central African Republic") ? "selected" : "")?>>Central African Republic</option>
                  <option value="Chad" <?=(($filter_user_data['filter_country'] == "Chad") ? "selected" : "")?>>Chad</option>
                  <option value="Chile" <?=(($filter_user_data['filter_country'] == "Chile") ? "selected" : "")?>>Chile</option>
                  <option value="China" <?=(($filter_user_data['filter_country'] == "China") ? "selected" : "")?>>China</option>
                  <option value="Christmas Island" <?=(($filter_user_data['filter_country'] == "Christmas Island") ? "selected" : "")?>>Christmas Island</option>
                  <option value="Cocos (Keeling) Islands" <?=(($filter_user_data['filter_country'] == "Cocos (Keeling) Islands") ? "selected" : "")?>>Cocos (Keeling) Islands</option>
                  <option value="Colombia" <?=(($filter_user_data['filter_country'] == "Colombia") ? "selected" : "")?>>Colombia</option>
                  <option value="Comoros" <?=(($filter_user_data['filter_country'] == "Comoros") ? "selected" : "")?>>Comoros</option>
                  <option value="Congo" <?=(($filter_user_data['filter_country'] == "Congo") ? "selected" : "")?>>Congo</option>
                  <option value="Cook Islands" <?=(($filter_user_data['filter_country'] == "Cook Islands") ? "selected" : "")?>>Cook Islands</option>
                  <option value="Costa Rica" <?=(($filter_user_data['filter_country'] == "Costa Rica") ? "selected" : "")?>>Costa Rica</option>
                  <option value="Croatia" <?=(($filter_user_data['filter_country'] == "Croatia") ? "selected" : "")?>>Croatia</option>
                  <option value="Cuba" <?=(($filter_user_data['filter_country'] == "Cuba") ? "selected" : "")?>>Cuba</option>
                  <option value="Curaçao" <?=(($filter_user_data['filter_country'] == "Curaçao") ? "selected" : "")?>>Curaçao</option>
                  <option value="Cyprus" <?=(($filter_user_data['filter_country'] == "Cyprus") ? "selected" : "")?>>Cyprus</option>
                  <option value="Czechia" <?=(($filter_user_data['filter_country'] == "Czechia") ? "selected" : "")?>>Czechia</option>
                  <option value="Côte d'Ivoire" <?=(($filter_user_data['filter_country'] == "Côte d'Ivoire") ? "selected" : "")?>>Côte d'Ivoire</option>
                  <option value="Denmark" <?=(($filter_user_data['filter_country'] == "Denmark") ? "selected" : "")?>>Denmark</option>
                  <option value="Djibouti" <?=(($filter_user_data['filter_country'] == "Djibouti") ? "selected" : "")?>>Djibouti</option>
                  <option value="Dominica" <?=(($filter_user_data['filter_country'] == "Dominica") ? "selected" : "")?>>Dominica</option>
                  <option value="Dominican Republic" <?=(($filter_user_data['filter_country'] == "Dominican Republic") ? "selected" : "")?>>Dominican Republic</option>
                  <option value="Ecuador" <?=(($filter_user_data['filter_country'] == "Ecuador") ? "selected" : "")?>>Ecuador</option>
                  <option value="Egypt" <?=(($filter_user_data['filter_country'] == "Egypt") ? "selected" : "")?>>Egypt</option>
                  <option value="El Salvador" <?=(($filter_user_data['filter_country'] == "El Salvador") ? "selected" : "")?>>El Salvador</option>
                  <option value="Equatorial Guinea" <?=(($filter_user_data['filter_country'] == "Equatorial Guinea") ? "selected" : "")?>>Equatorial Guinea</option>
                  <option value="Eritrea" <?=(($filter_user_data['filter_country'] == "Eritrea") ? "selected" : "")?>>Eritrea</option>
                  <option value="Estonia" <?=(($filter_user_data['filter_country'] == "Estonia") ? "selected" : "")?>>Estonia</option>
                  <option value="Eswatini" <?=(($filter_user_data['filter_country'] == "Eswatini") ? "selected" : "")?>>Eswatini</option>
                  <option value="Ethiopia" <?=(($filter_user_data['filter_country'] == "Ethiopia") ? "selected" : "")?>>Ethiopia</option>
                  <option value="Falkland Islands" <?=(($filter_user_data['filter_country'] == "Falkland Islands") ? "selected" : "")?>>Falkland Islands</option>
                  <option value="Faroe Islands" <?=(($filter_user_data['filter_country'] == "Faroe Islands") ? "selected" : "")?>>Faroe Islands</option>
                  <option value="Fiji" <?=(($filter_user_data['filter_country'] == "Fiji") ? "selected" : "")?>>Fiji</option>
                  <option value="Finland" <?=(($filter_user_data['filter_country'] == "Finland") ? "selected" : "")?>>Finland</option>
                  <option value="France" <?=(($filter_user_data['filter_country'] == "France") ? "selected" : "")?>>France</option>
                  <option value="French Guiana" <?=(($filter_user_data['filter_country'] == "French Guiana") ? "selected" : "")?>>French Guiana</option>
                  <option value="French Polynesia" <?=(($filter_user_data['filter_country'] == "French Polynesia") ? "selected" : "")?>>French Polynesia</option>
                  <option value="French Southern Territories" <?=(($filter_user_data['filter_country'] == "French Southern Territories") ? "selected" : "")?>>French Southern Territories</option>
                  <option value="Gabon" <?=(($filter_user_data['filter_country'] == "Gabon") ? "selected" : "")?>>Gabon</option>
                  <option value="Gambia" <?=(($filter_user_data['filter_country'] == "Gambia") ? "selected" : "")?>>Gambia</option>
                  <option value="Georgia" <?=(($filter_user_data['filter_country'] == "Georgia") ? "selected" : "")?>>Georgia</option>
                  <option value="Germany" <?=(($filter_user_data['filter_country'] == "Germany") ? "selected" : "")?>>Germany</option>
                  <option value="Ghana" <?=(($filter_user_data['filter_country'] == "Ghana") ? "selected" : "")?>>Ghana</option>
                  <option value="Gibraltar" <?=(($filter_user_data['filter_country'] == "Gibraltar") ? "selected" : "")?>>Gibraltar</option>
                  <option value="Greece" <?=(($filter_user_data['filter_country'] == "Greece") ? "selected" : "")?>>Greece</option>
                  <option value="Greenland" <?=(($filter_user_data['filter_country'] == "Greenland") ? "selected" : "")?>>Greenland</option>
                  <option value="Grenada" <?=(($filter_user_data['filter_country'] == "Grenada") ? "selected" : "")?>>Grenada</option>
                  <option value="Guadeloupe" <?=(($filter_user_data['filter_country'] == "Guadeloupe") ? "selected" : "")?>>Guadeloupe</option>
                  <option value="Guam" <?=(($filter_user_data['filter_country'] == "Guam") ? "selected" : "")?>>Guam</option>
                  <option value="Guatemala" <?=(($filter_user_data['filter_country'] == "Guatemala") ? "selected" : "")?>>Guatemala</option>
                  <option value="Guernsey" <?=(($filter_user_data['filter_country'] == "Guernsey") ? "selected" : "")?>>Guernsey</option>
                  <option value="Guinea" <?=(($filter_user_data['filter_country'] == "Guinea") ? "selected" : "")?>>Guinea</option>
                  <option value="Guinea-Bissau" <?=(($filter_user_data['filter_country'] == "Guinea-Bissau") ? "selected" : "")?>>Guinea-Bissau</option>
                  <option value="Guyana" <?=(($filter_user_data['filter_country'] == "Guyana") ? "selected" : "")?>>Guyana</option>
                  <option value="Haiti" <?=(($filter_user_data['filter_country'] == "Haiti") ? "selected" : "")?>>Haiti</option>
                  <option value="Heard Island and McDonald Islands" <?=(($filter_user_data['filter_country'] == "Heard Island and McDonald Islands") ? "selected" : "")?>>Heard Island and McDonald Islands</option>
                  <option value="Holy See" <?=(($filter_user_data['filter_country'] == "Holy See") ? "selected" : "")?>>Holy See</option>
                  <option value="Honduras" <?=(($filter_user_data['filter_country'] == "Honduras") ? "selected" : "")?>>Honduras</option>
                  <option value="Hong Kong" <?=(($filter_user_data['filter_country'] == "Hong Kong") ? "selected" : "")?>>Hong Kong</option>
                  <option value="Hungary" <?=(($filter_user_data['filter_country'] == "Hungary") ? "selected" : "")?>>Hungary</option>
                  <option value="Iceland" <?=(($filter_user_data['filter_country'] == "Iceland") ? "selected" : "")?>>Iceland</option>
                  <option value="India" <?=(($filter_user_data['filter_country'] == "India") ? "selected" : "")?>>India</option>
                  <option value="Indonesia" <?=(($filter_user_data['filter_country'] == "Indonesia") ? "selected" : "")?>>Indonesia</option>
                  <option value="Iran" <?=(($filter_user_data['filter_country'] == "Iran") ? "selected" : "")?>>Iran</option>
                  <option value="Iraq" <?=(($filter_user_data['filter_country'] == "Iraq") ? "selected" : "")?>>Iraq</option>
                  <option value="Ireland" <?=(($filter_user_data['filter_country'] == "Ireland") ? "selected" : "")?>>Ireland</option>
                  <option value="Isle of Man" <?=(($filter_user_data['filter_country'] == "Isle of Man") ? "selected" : "")?>>Isle of Man</option>
                  <option value="Israel" <?=(($filter_user_data['filter_country'] == "Israel") ? "selected" : "")?>>Israel</option>
                  <option value="Italy" <?=(($filter_user_data['filter_country'] == "Italy") ? "selected" : "")?>>Italy</option>
                  <option value="Jamaica" <?=(($filter_user_data['filter_country'] == "Jamaica") ? "selected" : "")?>>Jamaica</option>
                  <option value="Japan" <?=(($filter_user_data['filter_country'] == "Japan") ? "selected" : "")?>>Japan</option>
                  <option value="Jersey" <?=(($filter_user_data['filter_country'] == "Jersey") ? "selected" : "")?>>Jersey</option>
                  <option value="Jordan" <?=(($filter_user_data['filter_country'] == "Jordan") ? "selected" : "")?>>Jordan</option>
                  <option value="Kazakhstan" <?=(($filter_user_data['filter_country'] == "Kazakhstan") ? "selected" : "")?>>Kazakhstan</option>
                  <option value="Kenya" <?=(($filter_user_data['filter_country'] == "Kenya") ? "selected" : "")?>>Kenya</option>
                  <option value="Kiribati" <?=(($filter_user_data['filter_country'] == "Kiribati") ? "selected" : "")?>>Kiribati</option>
                  <option value="Kuwait" <?=(($filter_user_data['filter_country'] == "Kuwait") ? "selected" : "")?>>Kuwait</option>
                  <option value="Kyrgyzstan" <?=(($filter_user_data['filter_country'] == "Kyrgyzstan") ? "selected" : "")?>>Kyrgyzstan</option>
                  <option value="Laos" <?=(($filter_user_data['filter_country'] == "Laos") ? "selected" : "")?>>Laos</option>
                  <option value="Latvia" <?=(($filter_user_data['filter_country'] == "Latvia") ? "selected" : "")?>>Latvia</option>
                  <option value="Lebanon" <?=(($filter_user_data['filter_country'] == "Lebanon") ? "selected" : "")?>>Lebanon</option>
                  <option value="Lesotho" <?=(($filter_user_data['filter_country'] == "Lesotho") ? "selected" : "")?>>Lesotho</option>
                  <option value="Liberia" <?=(($filter_user_data['filter_country'] == "Liberia") ? "selected" : "")?>>Liberia</option>
                  <option value="Libya" <?=(($filter_user_data['filter_country'] == "Libya") ? "selected" : "")?>>Libya</option>
                  <option value="Liechtenstein" <?=(($filter_user_data['filter_country'] == "Liechtenstein") ? "selected" : "")?>>Liechtenstein</option>
                  <option value="Lithuania" <?=(($filter_user_data['filter_country'] == "Lithuania") ? "selected" : "")?>>Lithuania</option>
                  <option value="Luxembourg" <?=(($filter_user_data['filter_country'] == "Luxembourg") ? "selected" : "")?>>Luxembourg</option>
                  <option value="Macao" <?=(($filter_user_data['filter_country'] == "Macao") ? "selected" : "")?>>Macao</option>
                  <option value="Madagascar" <?=(($filter_user_data['filter_country'] == "Madagascar") ? "selected" : "")?>>Madagascar</option>
                  <option value="Malawi" <?=(($filter_user_data['filter_country'] == "Malawi") ? "selected" : "")?>>Malawi</option>
                  <option value="Malaysia" <?=(($filter_user_data['filter_country'] == "Malaysia") ? "selected" : "")?>>Malaysia</option>
                  <option value="Maldives" <?=(($filter_user_data['filter_country'] == "Maldives") ? "selected" : "")?>>Maldives</option>
                  <option value="Mali" <?=(($filter_user_data['filter_country'] == "Mali") ? "selected" : "")?>>Mali</option>
                  <option value="Malta" <?=(($filter_user_data['filter_country'] == "Malta") ? "selected" : "")?>>Malta</option>
                  <option value="Marshall Islands" <?=(($filter_user_data['filter_country'] == "Marshall Islands") ? "selected" : "")?>>Marshall Islands</option>
                  <option value="Martinique" <?=(($filter_user_data['filter_country'] == "Martinique") ? "selected" : "")?>>Martinique</option>
                  <option value="Mauritania" <?=(($filter_user_data['filter_country'] == "Mauritania") ? "selected" : "")?>>Mauritania</option>
                  <option value="Mauritius" <?=(($filter_user_data['filter_country'] == "Mauritius") ? "selected" : "")?>>Mauritius</option>
                  <option value="Mayotte" <?=(($filter_user_data['filter_country'] == "Mayotte") ? "selected" : "")?>>Mayotte</option>
                  <option value="Mexico" <?=(($filter_user_data['filter_country'] == "Mexico") ? "selected" : "")?>>Mexico</option>
                  <option value="Micronesia" <?=(($filter_user_data['filter_country'] == "Micronesia") ? "selected" : "")?>>Micronesia</option>
                  <option value="Moldova" <?=(($filter_user_data['filter_country'] == "Moldova") ? "selected" : "")?>>Moldova</option>
                  <option value="Monaco" <?=(($filter_user_data['filter_country'] == "Monaco") ? "selected" : "")?>>Monaco</option>
                  <option value="Mongolia" <?=(($filter_user_data['filter_country'] == "Mongolia") ? "selected" : "")?>>Mongolia</option>
                  <option value="Montenegro" <?=(($filter_user_data['filter_country'] == "Montenegro") ? "selected" : "")?>>Montenegro</option>
                  <option value="Montserrat" <?=(($filter_user_data['filter_country'] == "Montserrat") ? "selected" : "")?>>Montserrat</option>
                  <option value="Morocco" <?=(($filter_user_data['filter_country'] == "Morocco") ? "selected" : "")?>>Morocco</option>
                  <option value="Mozambique" <?=(($filter_user_data['filter_country'] == "Mozambique") ? "selected" : "")?>>Mozambique</option>
                  <option value="Myanmar" <?=(($filter_user_data['filter_country'] == "Myanmar") ? "selected" : "")?>>Myanmar</option>
                  <option value="Namibia" <?=(($filter_user_data['filter_country'] == "Namibia") ? "selected" : "")?>>Namibia</option>
                  <option value="Nauru" <?=(($filter_user_data['filter_country'] == "Nauru") ? "selected" : "")?>>Nauru</option>
                  <option value="Nepal" <?=(($filter_user_data['filter_country'] == "Nepal") ? "selected" : "")?>>Nepal</option>
                  <option value="Netherlands" <?=(($filter_user_data['filter_country'] == "Netherlands") ? "selected" : "")?>>Netherlands</option>
                  <option value="New Caledonia" <?=(($filter_user_data['filter_country'] == "New Caledonia") ? "selected" : "")?>>New Caledonia</option>
                  <option value="New Zealand" <?=(($filter_user_data['filter_country'] == "New Zealand") ? "selected" : "")?>>New Zealand</option>
                  <option value="Nicaragua" <?=(($filter_user_data['filter_country'] == "Nicaragua") ? "selected" : "")?>>Nicaragua</option>
                  <option value="Niger" <?=(($filter_user_data['filter_country'] == "Niger") ? "selected" : "")?>>Niger</option>
                  <option value="Nigeria" <?=(($filter_user_data['filter_country'] == "Nigeria") ? "selected" : "")?>>Nigeria</option>
                  <option value="Niue" <?=(($filter_user_data['filter_country'] == "Niue") ? "selected" : "")?>>Niue</option>
                  <option value="Norfolk Island" <?=(($filter_user_data['filter_country'] == "Norfolk Island") ? "selected" : "")?>>Norfolk Island</option>
                  <option value="North Korea" <?=(($filter_user_data['filter_country'] == "North Korea") ? "selected" : "")?>>North Korea</option>
                  <option value="North Macedonia" <?=(($filter_user_data['filter_country'] == "North Macedonia") ? "selected" : "")?>>North Macedonia</option>
                  <option value="Northern Mariana Islands" <?=(($filter_user_data['filter_country'] == "Northern Mariana Islands") ? "selected" : "")?>>Northern Mariana Islands</option>
                  <option value="Norway" <?=(($filter_user_data['filter_country'] == "Norway") ? "selected" : "")?>>Norway</option>
                  <option value="Oman" <?=(($filter_user_data['filter_country'] == "Oman") ? "selected" : "")?>>Oman</option>
                  <option value="Pakistan" <?=(($filter_user_data['filter_country'] == "Pakistan") ? "selected" : "")?>>Pakistan</option>
                  <option value="Palau" <?=(($filter_user_data['filter_country'] == "Palau") ? "selected" : "")?>>Palau</option>
                  <option value="Palestine" <?=(($filter_user_data['filter_country'] == "Palestine") ? "selected" : "")?>>Palestine</option>
                  <option value="Panama" <?=(($filter_user_data['filter_country'] == "Panama") ? "selected" : "")?>>Panama</option>
                  <option value="Papua New Guinea" <?=(($filter_user_data['filter_country'] == "Papua New Guinea") ? "selected" : "")?>>Papua New Guinea</option>
                  <option value="Paraguay" <?=(($filter_user_data['filter_country'] == "Paraguay") ? "selected" : "")?>>Paraguay</option>
                  <option value="Peru" <?=(($filter_user_data['filter_country'] == "Peru") ? "selected" : "")?>>Peru</option>
                  <option value="Philippines" <?=(($filter_user_data['filter_country'] == "Philippines") ? "selected" : "")?>>Philippines</option>
                  <option value="Pitcairn" <?=(($filter_user_data['filter_country'] == "Pitcairn") ? "selected" : "")?>>Pitcairn</option>
                  <option value="Poland" <?=(($filter_user_data['filter_country'] == "Poland") ? "selected" : "")?>>Poland</option>
                  <option value="Portugal" <?=(($filter_user_data['filter_country'] == "Portugal") ? "selected" : "")?>>Portugal</option>
                  <option value="Puerto Rico" <?=(($filter_user_data['filter_country'] == "Puerto Rico") ? "selected" : "")?>>Puerto Rico</option>
                  <option value="Qatar" <?=(($filter_user_data['filter_country'] == "Qatar") ? "selected" : "")?>>Qatar</option>
                  <option value="Réunion" <?=(($filter_user_data['filter_country'] == "Réunion") ? "selected" : "")?>>Réunion</option>
                  <option value="Romania" <?=(($filter_user_data['filter_country'] == "Romania") ? "selected" : "")?>>Romania</option>
                  <option value="Russia" <?=(($filter_user_data['filter_country'] == "Russia") ? "selected" : "")?>>Russia</option>
                  <option value="Rwanda" <?=(($filter_user_data['filter_country'] == "Rwanda") ? "selected" : "")?>>Rwanda</option>
                  <option value="Saint Barthélemy" <?=(($filter_user_data['filter_country'] == "Saint Barthélemy") ? "selected" : "")?>>Saint Barthélemy</option>
                  <option value="Saint Helena, Ascension and Tristan da Cunha" <?=(($filter_user_data['filter_country'] == "Saint Helena, Ascension and Tristan da Cunha") ? "selected" : "")?>>Saint Helena, Ascension and Tristan da Cunha</option>
                  <option value="Saint Kitts and Nevis" <?=(($filter_user_data['filter_country'] == "Saint Kitts and Nevis") ? "selected" : "")?>>Saint Kitts and Nevis</option>
                  <option value="Saint Lucia" <?=(($filter_user_data['filter_country'] == "Saint Lucia") ? "selected" : "")?>>Saint Lucia</option>
                  <option value="Saint Martin" <?=(($filter_user_data['filter_country'] == "Saint Martin") ? "selected" : "")?>>Saint Martin</option>
                  <option value="Saint Pierre and Miquelon" <?=(($filter_user_data['filter_country'] == "Saint Pierre and Miquelon") ? "selected" : "")?>>Saint Pierre and Miquelon</option>
                  <option value="Saint Vincent and the Grenadines" <?=(($filter_user_data['filter_country'] == "Saint Vincent and the Grenadines") ? "selected" : "")?>>Saint Vincent and the Grenadines</option>
                  <option value="Samoa" <?=(($filter_user_data['filter_country'] == "Samoa") ? "selected" : "")?>>Samoa</option>
                  <option value="San Marino" <?=(($filter_user_data['filter_country'] == "San Marino") ? "selected" : "")?>>San Marino</option>
                  <option value="Sao Tome and Principe" <?=(($filter_user_data['filter_country'] == "Sao Tome and Principe") ? "selected" : "")?>>Sao Tome and Principe</option>
                  <option value="Saudi Arabia" <?=(($filter_user_data['filter_country'] == "Saudi Arabia") ? "selected" : "")?>>Saudi Arabia</option>
                  <option value="Senegal" <?=(($filter_user_data['filter_country'] == "Senegal") ? "selected" : "")?>>Senegal</option>
                  <option value="Serbia" <?=(($filter_user_data['filter_country'] == "Serbia") ? "selected" : "")?>>Serbia</option>
                  <option value="Seychelles" <?=(($filter_user_data['filter_country'] == "Seychelles") ? "selected" : "")?>>Seychelles</option>
                  <option value="Sierra Leone" <?=(($filter_user_data['filter_country'] == "Sierra Leone") ? "selected" : "")?>>Sierra Leone</option>
                  <option value="Singapore" <?=(($filter_user_data['filter_country'] == "Singapore") ? "selected" : "")?>>Singapore</option>
                  <option value="Sint Maarten" <?=(($filter_user_data['filter_country'] == "Sint Maarten") ? "selected" : "")?>>Sint Maarten</option>
                  <option value="Slovakia" <?=(($filter_user_data['filter_country'] == "Slovakia") ? "selected" : "")?>>Slovakia</option>
                  <option value="Slovenia" <?=(($filter_user_data['filter_country'] == "Slovenia") ? "selected" : "")?>>Slovenia</option>
                  <option value="Solomon Islands" <?=(($filter_user_data['filter_country'] == "Solomon Islands") ? "selected" : "")?>>Solomon Islands</option>
                  <option value="Somalia" <?=(($filter_user_data['filter_country'] == "Somalia") ? "selected" : "")?>>Somalia</option>
                  <option value="South Africa" <?=(($filter_user_data['filter_country'] == "South Africa") ? "selected" : "")?>>South Africa</option>
                  <option value="South Georgia and the South Sandwich Islands" <?=(($filter_user_data['filter_country'] == "South Georgia and the South Sandwich Islands") ? "selected" : "")?>>South Georgia and the South Sandwich Islands</option>
                  <option value="South Korea" <?=(($filter_user_data['filter_country'] == "South Korea") ? "selected" : "")?>>South Korea</option>
                  <option value="South Sudan" <?=(($filter_user_data['filter_country'] == "South Sudan") ? "selected" : "")?>>South Sudan</option>
                  <option value="Spain" <?=(($filter_user_data['filter_country'] == "Spain") ? "selected" : "")?>>Spain</option>
                  <option value="Sri Lanka" <?=(($filter_user_data['filter_country'] == "Sri Lanka") ? "selected" : "")?>>Sri Lanka</option>
                  <option value="Sudan" <?=(($filter_user_data['filter_country'] == "Sudan") ? "selected" : "")?>>Sudan</option>
                  <option value="Suriname" <?=(($filter_user_data['filter_country'] == "Suriname") ? "selected" : "")?>>Suriname</option>
                  <option value="Svalbard and Jan Mayen" <?=(($filter_user_data['filter_country'] == "Svalbard and Jan Mayen") ? "selected" : "")?>>Svalbard and Jan Mayen</option>
                  <option value="Sweden" <?=(($filter_user_data['filter_country'] == "Sweden") ? "selected" : "")?>>Sweden</option>
                  <option value="Switzerland" <?=(($filter_user_data['filter_country'] == "Switzerland") ? "selected" : "")?>>Switzerland</option>
                  <option value="Syria" <?=(($filter_user_data['filter_country'] == "Syria") ? "selected" : "")?>>Syria</option>
                  <option value="Taiwan" <?=(($filter_user_data['filter_country'] == "Taiwan") ? "selected" : "")?>>Taiwan</option>
                  <option value="Tajikistan" <?=(($filter_user_data['filter_country'] == "Tajikistan") ? "selected" : "")?>>Tajikistan</option>
                  <option value="Tanzania" <?=(($filter_user_data['filter_country'] == "Tanzania") ? "selected" : "")?>>Tanzania</option>
                  <option value="Thailand" <?=(($filter_user_data['filter_country'] == "Thailand") ? "selected" : "")?>>Thailand</option>
                  <option value="Timor-Leste" <?=(($filter_user_data['filter_country'] == "Timor-Leste") ? "selected" : "")?>>Timor-Leste</option>
                  <option value="Togo" <?=(($filter_user_data['filter_country'] == "Togo") ? "selected" : "")?>>Togo</option>
                  <option value="Tokelau" <?=(($filter_user_data['filter_country'] == "Tokelau") ? "selected" : "")?>>Tokelau</option>
                  <option value="Tonga" <?=(($filter_user_data['filter_country'] == "Tonga") ? "selected" : "")?>>Tonga</option>
                  <option value="Trinidad and Tobago" <?=(($filter_user_data['filter_country'] == "Trinidad and Tobago") ? "selected" : "")?>>Trinidad and Tobago</option>
                  <option value="Tunisia" <?=(($filter_user_data['filter_country'] == "Tunisia") ? "selected" : "")?>>Tunisia</option>
                  <option value="Turkey" <?=(($filter_user_data['filter_country'] == "Turkey") ? "selected" : "")?>>Turkey</option>
                  <option value="Turkmenistan" <?=(($filter_user_data['filter_country'] == "Turkmenistan") ? "selected" : "")?>>Turkmenistan</option>
                  <option value="Turks and Caicos Islands" <?=(($filter_user_data['filter_country'] == "Turks and Caicos Islands") ? "selected" : "")?>>Turks and Caicos Islands</option>
                  <option value="Tuvalu" <?=(($filter_user_data['filter_country'] == "Tuvalu") ? "selected" : "")?>>Tuvalu</option>
                  <option value="Uganda" <?=(($filter_user_data['filter_country'] == "Uganda") ? "selected" : "")?>>Uganda</option>
                  <option value="Ukraine" <?=(($filter_user_data['filter_country'] == "Ukraine") ? "selected" : "")?>>Ukraine</option>
                  <option value="United Arab Emirates" <?=(($filter_user_data['filter_country'] == "United Arab Emirates") ? "selected" : "")?>>United Arab Emirates</option>
                  <option value="United Kingdom" <?=(($filter_user_data['filter_country'] == "United Kingdom") ? "selected" : "")?>>United Kingdom</option>
                  <option value="United States" <?=(($filter_user_data['filter_country'] == "United States") ? "selected" : "")?>>United States</option>
                  <option value="United States Minor Outlying Islands" <?=(($filter_user_data['filter_country'] == "United States Minor Outlying Islands") ? "selected" : "")?>>United States Minor Outlying Islands</option>
                  <option value="Uruguay" <?=(($filter_user_data['filter_country'] == "Uruguay") ? "selected" : "")?>>Uruguay</option>
                  <option value="Uzbekistan" <?=(($filter_user_data['filter_country'] == "Uzbekistan") ? "selected" : "")?>>Uzbekistan</option>
                  <option value="Vanuatu" <?=(($filter_user_data['filter_country'] == "Vanuatu") ? "selected" : "")?>>Vanuatu</option>
                  <option value="Venezuela" <?=(($filter_user_data['filter_country'] == "Venezuela") ? "selected" : "")?>>Venezuela</option>
                  <option value="Vietnam" <?=(($filter_user_data['filter_country'] == "Vietnam") ? "selected" : "")?>>Vietnam</option>
                  <option value="Virgin Islands (British)" <?=(($filter_user_data['filter_country'] == "Virgin Islands (British)") ? "selected" : "")?>>Virgin Islands (British)</option>
                  <option value="Virgin Islands (U.S.)" <?=(($filter_user_data['filter_country'] == "Virgin Islands (U.S.)") ? "selected" : "")?>>Virgin Islands (U.S.)</option>
                  <option value="Wallis and Futuna" <?=(($filter_user_data['filter_country'] == "Wallis and Futuna") ? "selected" : "")?>>Wallis and Futuna</option>
                  <option value="Western Sahara" <?=(($filter_user_data['filter_country'] == "Western Sahara") ? "selected" : "")?>>Western Sahara</option>
                  <option value="Yemen" <?=(($filter_user_data['filter_country'] == "Yemen") ? "selected" : "")?>>Yemen</option>
                  <option value="Zambia" <?=(($filter_user_data['filter_country'] == "Zambia") ? "selected" : "")?>>Zambia</option>
                  <option value="Zimbabwe" <?=(($filter_user_data['filter_country'] == "Zimbabwe") ? "selected" : "")?>>Zimbabwe</option>
						</select>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-9" style="padding-bottom: 6px;"></div>
               <div class="col-xs-12 col-sm-12 col-md-1" style="padding-bottom: 6px;">
                  <div class="Input_text_s role">
                     <label>Special Role:</label>
                     <input id="box1" name="filter_special" value="yes" type="checkbox" <?=(($filter_user_data['filter_special'] == "yes") ? "checked" : "")?> />
                     <label class="custom_label" for="box1"></label>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-1" style="padding-bottom: 6px;">
                  <div class="Input_text_s role">
                     <label>Active:</label>
                     <input id="box3" name="filter_active" value="yes" type="checkbox" <?=(($filter_user_data['filter_active'] == "yes") ? "checked" : "")?> />
                     <label class="custom_label" for="box3"></label>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-1" style="padding-bottom: 6px;">
                  <div class="Input_text_s role">
                      <label>InActive:</label>
                      <input id="box2" name="filter_inactive" value="yes" type="checkbox" <?=(($filter_user_data['filter_inactive'] == "yes") ? "checked" : "")?> />
                     <label class="custom_label" for="box2"></label>
                  </div>
               </div>
               <script type="text/javascript">
                   $(function () {
                       $('.datetime_picker').datetimepicker();
                   });
               </script>
               <style>
                  .Input_text_btn {padding: 25px 0 0;}
               </style>

               <div class="col-xs-12 col-sm-12 col-md-12" style="padding-bottom: 6px;display: flex;justify-content: end; margin-top: 1.3%;">
               
               

               <div class="">
                  <label></label>
                  <button class="btn btn-success"><i class="glyphicon glyphicon-filter"></i>Search</button>
                  <a href="<?php echo SURL; ?>admin/users/reset_filters/all" class="btn btn-danger"><i class="fa fa-times-circle"></i>Reset</a>
               </div>
               <div class=""style="margin-left: 1%;">
                 <a href="<?php echo SURL; ?>admin/users/csvreport/" class="btn btn-warning pull-right"><i class="fa fa-print"></i> &nbsp;CSV Report</a>
               </div>

            </div>
            </div>
            </form>
          </div>
      </div>

    <!-- Widget -->
    <div class="widget widget-inverse">

      <div class="widget-body padding-bottom-none">
        <!-- Table -->
         <div class="loaderImage">
        <div class="loaderimagbox" style="display:none;"><img src="<?php echo SURL; ?>assets/images/loader.gif" /></div>
        <table class="table table-bordered ">

          <!-- Table heading -->
          <thead>
            <tr>
              <th>Sr</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>UserName</th>
              <th>BCA User</th>
              <th>Application Mode</th>
              <th>Trading IP</th>
              <th>Country</th>
              <th>Last Login</th>
              <th>Created Date</th>
              <th>Action</th>
              <th>Special Action</th>
            </tr>
          </thead>

          <tbody>
           <?php
if (count($users_arr) > 0) {
    for ($i = 0; $i < count($users_arr); $i++) {
        ?>
           <tr class="gradeX">
              <td><?php echo $i + 1; ?></td>
              <td><?php echo $users_arr[$i]['first_name']; ?></td>
              <td><?php echo $users_arr[$i]['last_name']; ?></td>
              <td><?php echo $users_arr[$i]['username']; ?></td>
              <td><?php 
                     if ($users_arr[$i]['blockchain_alliance_id'] == 0 || $users_arr[$i]['blockchain_alliance_id'] == '' || $users_arr[$i]['blockchain_alliance_id'] == null)  {
                      echo '<small class="text-danger" data-toggle="tooltip" title="Normal User">N/A</small>';
                    }else{
                      echo $users_arr[$i]['blockchain_alliance_id'];
                    }

                    ?>
              </td>
              <!-- <td><?php //echo $users_arr[$i]['email_address']; 
              ?></td> -->
              <td><select class="form-control app_mode_change" data-id="<?php echo $users_arr[$i]['_id']; ?>">
                <option value="both" <?php if ($users_arr[$i]['application_mode'] == '') {echo "selected";}?>>Select Mode</option>
                <option value="both" <?php if ($users_arr[$i]['application_mode'] == 'both') {echo "selected";}?>>Both</option>
                <option value="test" <?php if ($users_arr[$i]['application_mode'] == 'test') {echo "selected";}?>>Test</option>
                <option value="live" <?php if ($users_arr[$i]['application_mode'] == 'live') {echo "selected";}?>>Live</option>
              </select></td>
              <td><?php echo $users_arr[$i]['trading_ip'] ?></td>
              <td><?php echo $users_arr[$i]['country'] ?></td>
              <?php
                  if ($users_arr[$i]['last_login_datetime'] == null || $users_arr[$i]['last_login_datetime'] == "") {
                     $login_time = 'N/A';
                  } else if(gettype($users_arr[$i]['last_login_datetime']) == 'object'){
                     // $login_time = date("M d, Y g:i:s A", strtotime($users_arr[$i]['last_login_datetime']));
                     $login_time = $users_arr[$i]['last_login_datetime']->toDateTime(); 
                     $login_time = $login_time->format("M d, Y g:i:s A");
                  }else{
                     $login_time = date("M d, Y g:i:s A", strtotime($users_arr[$i]['last_login_datetime']));
                  }
               ?>
              <td><?php echo $login_time; ?></td>
              <td>
                  <?php $datetiem = $users_arr[$i]['created_date']->toDateTime(); echo $datetiem->format("M d, Y g:i:s A");?>
               </td>
              <td class="center">
                <div class="btn-group btn-group-xs ">
                    <a href="<?php echo SURL . 'admin/users/edit-user/' . $users_arr[$i]['_id']; ?>" class="btn btn-inverse"><i class="fa fa-pencil"></i></a>
                    <!-- <a href="<?php //echo SURL.'admin/users/delete-user/'.$users_arr[$i]['_id'];?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete?')"><i class="fa fa-times"></i></a> -->
                </div>
               </td>
               <td>
                <?php $role = ($users_arr[$i]['special_role'] == 0) ? "1" : "0"?>
                <a href="<?php echo SURL . 'admin/users/edit-role/' . $users_arr[$i]['_id'] . '/' . $role ?>" class="btn btn-xs btn-inverse"><?=($users_arr[$i]['special_role'] == 0) ? "Make Special" : "Remove Special"?></a>

                <?php $status = ($users_arr[$i]['status'] == 0) ? "1" : "0"?>
                <a href="<?php echo SURL . 'admin/users/edit-status/' . $users_arr[$i]['_id'] . '/' . $status ?>" class="btn  btn-xs btn-inverse"><?=($users_arr[$i]['status'] == 1) ? "Make Active" : "Make Inactive"?></a>
                <div class="checkbox">
                  <div class="Input_text_s">
                     <label>Allow Trigger:</label>
                     <input id="box66_<?php echo $users_arr[$i]['_id']; ?>" class="update_trigger" value="yes" type="checkbox" <?=(($users_arr[$i]['trigger_enable'] == 'yes') ? "checked" : "")?> data-id="<?php echo $users_arr[$i]['_id']; ?>">
                     <label class="custom_label" for="box66_<?php echo $users_arr[$i]['_id']; ?>"></label>
                  </div>
                  <div class="Input_text_s">
                     <label>Allow App Use:</label>
                     <input id="box67_<?php echo $users_arr[$i]['_id']; ?>" class="update_app" value="yes" type="checkbox" <?=(($users_arr[$i]['app_enable'] == 'yes') ? "checked" : "")?> data-id="<?php echo $users_arr[$i]['_id']; ?>">
                     <label class="custom_label" for="box67_<?php echo $users_arr[$i]['_id']; ?>"></label>
                  </div>

                 <!--  <label><input type="checkbox" class="update_trigger" data-id="<?php //echo $users_arr[$i]['_id']; ?>" value="yes">Allow Trigger</label> -->
                  
                  <?php if($users_arr[$i]['unsuccessfull_login_attempt_count'] >= 3){ ?>
                     <br>
                     <button class="btn btn-xs btn-inverse" onclick="removeTempBlock('<?=$users_arr[$i]['_id']?>')">Remove Temporary Block</button>
                  <?php } ?>

                </div>
            </td>
            </tr>
           <?php }
}else{?>
<tr style="text-align:center;" class="gradeX">
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td style="color:red;">Data is not available</td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
<?php } ?>
          </tbody>

        </table>
        </div>
        <!-- // Table END -->


        <?php echo $pagination; ?>

      </div>
    </div>
    <!-- // Widget END -->

  </div>
</div>
<script>

function removeTempBlock(id) {
   $(".loaderimagbox").show();
   $.ajax({
      url: "<?php echo SURL; ?>admin/Api_calls/temp_un_block",
      type: "POST",
      data: {user_id:id},
      success: function(response){
         if(response.status){
            $(".unblockSuccessMessage").show();
            $(".loaderimagbox").hide();
            setTimeout(function() {
               $(".unblockSuccessMessage").hide();
            }, 3000); // <-- time in milliseconds
         }else{
            $(".errormessage").show();
            $(".loaderimagbox").hide();
            setTimeout(function() {
               $(".errormessage").hide();
            }, 3000); // <-- time in milliseconds
         }
      }
   });
}


$("body").on("click",".glassflter",function(e){
    var query = $("#filter_by_name").val();
    window.location.href = "<?php echo SURL; ?>/admin/users/?query="+query;
});

$("body").on("change",".app_mode_change",function(e){

	$(".loaderimagbox").show();
    var user_id = $(this).data("id");
    var mode = $(this).val();
    $.ajax({
      url: "<?php echo SURL; ?>admin/users/update_application_mode",
      data: {user_id:user_id, mode:mode},
      type: "POST",
      success: function(response){

	   $(".successMessage").show();
       $(".loaderimagbox").hide();

	   setTimeout(function() {
         $(".successMessage").hide();
       }, 3000); // <-- time in milliseconds



      }
    });
});


$("body").on("change",".update_trigger",function(){
  var user_id = $(this).data('id');
  var checked = $(this).is(':checked');
  if(checked) {
        var mode = "yes";
    }else{
      var mode = "no";
    }

    $(".loaderimagbox").show();

    $.ajax({
      url: "<?php echo SURL; ?>admin/users/update_trigger_mode",
      data: {user_id:user_id, mode:mode},
      type: "POST",
      success: function(response){

     $(".successMessage").show();
       $(".loaderimagbox").hide();

     setTimeout(function() {
         $(".successMessage").hide();
       }, 3000); // <-- time in milliseconds



      }
    });
});


$("body").on("change",".update_app",function(){
  var user_id = $(this).data('id');
  var checked = $(this).is(':checked');
  if(checked) {
        var mode = "yes";
    }else{
      var mode = "no";
    }

    $(".loaderimagbox").show();

    $.ajax({
      url: "<?php echo SURL; ?>admin/users/update_app_mode",
      data: {user_id:user_id, mode:mode},
      type: "POST",
      success: function(response){

     $(".successMessage").show();
       $(".loaderimagbox").hide();

     setTimeout(function() {
         $(".successMessage").hide();
       }, 3000); // <-- time in milliseconds
      }
    });
});
</script>
