
<style type="text/css">
@import('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.0/css/bootstrap.min.css');

.slidecontainer {
    width: 100%;
}

.slider {
    -webkit-appearance: none;
    width: 100%;
    height: 25px;
    background: #d3d3d3;
    outline: none;
    opacity: 0.7;
    -webkit-transition: .2s;
    transition: opacity .2s;
}

.slider:hover {
    opacity: 1;
}

.slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 25px;
    height: 25px;
    background: #4CAF50;
    cursor: pointer;
}

.slider::-moz-range-thumb {
    width: 25px;
    height: 25px;
    background: #4CAF50;
    cursor: pointer;
}
.funkyradio div {
  clear: both;
  overflow: hidden;
}

.funkyradio label {
  width: 100%;
  border-radius: 3px;
  border: 1px solid #D1D3D4;
  font-weight: normal;
}

.funkyradio input[type="radio"]:empty,
.funkyradio input[type="checkbox"]:empty {
  display: none;
}

.funkyradio input[type="radio"]:empty ~ label,
.funkyradio input[type="checkbox"]:empty ~ label {
  position: relative;
  line-height: 2.5em;
  text-indent: 3.25em;
  margin-top: 2em;
  cursor: pointer;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
}

.funkyradio input[type="radio"]:empty ~ label:before,
.funkyradio input[type="checkbox"]:empty ~ label:before {
  position: absolute;
  display: block;
  top: 0;
  bottom: 0;
  left: 0;
  content: '';
  width: 2.5em;
  background: #D1D3D4;
  border-radius: 3px 0 0 3px;
}

.funkyradio input[type="radio"]:hover:not(:checked) ~ label,
.funkyradio input[type="checkbox"]:hover:not(:checked) ~ label {
  color: #888;
}

.funkyradio input[type="radio"]:hover:not(:checked) ~ label:before,
.funkyradio input[type="checkbox"]:hover:not(:checked) ~ label:before {
  content: '\2714';
  text-indent: .9em;
  color: #C2C2C2;
}

.funkyradio input[type="radio"]:checked ~ label,
.funkyradio input[type="checkbox"]:checked ~ label {
  color: #777;
}

.funkyradio input[type="radio"]:checked ~ label:before,
.funkyradio input[type="checkbox"]:checked ~ label:before {
  content: '\2714';
  text-indent: .9em;
  color: #333;
  background-color: #ccc;
}

.funkyradio input[type="radio"]:focus ~ label:before,
.funkyradio input[type="checkbox"]:focus ~ label:before {
  box-shadow: 0 0 0 3px #999;
}

.funkyradio-default input[type="radio"]:checked ~ label:before,
.funkyradio-default input[type="checkbox"]:checked ~ label:before {
  color: #333;
  background-color: #ccc;
}

.funkyradio-primary input[type="radio"]:checked ~ label:before,
.funkyradio-primary input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #337ab7;
}

.funkyradio-success input[type="radio"]:checked ~ label:before,
.funkyradio-success input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #5cb85c;
}

.funkyradio-danger input[type="radio"]:checked ~ label:before,
.funkyradio-danger input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #d9534f;
}

.funkyradio-warning input[type="radio"]:checked ~ label:before,
.funkyradio-warning input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #f0ad4e;
}

.funkyradio-info input[type="radio"]:checked ~ label:before,
.funkyradio-info input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #5bc0de;
}
</style>
<div id="content">
  <h1 class="content-heading bg-white border-bottom">Candle Stick Settings</h1>
  
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


    <div class="widget widget-inverse"> 
    
        <div class="widget widget-inverse"> 
            <form class="form-horizontal margin-none" method="post" action="<?php echo SURL?>admin/test/save-setting-process" novalidate="novalidate">
            <div class="widget-body"> 
              
              <!-- Row -->
              <div class="row">
                <div class="col-md-6">
                   <div class="form-group col-md-12">
                    <label for="comment" class="col-md-3">Coin:</label>
                    <div class="col-md-9">
                      <select name="coin" class="form-control">
                        <option>Select Coin</option>
                        <?php 
                        if (count($coins) > 0) {
                          for ($i=0; $i < count($coins) ; $i++) { 
                           ?>
                           <option value="<?php echo $coins[$i]['symbol'] ?>"><?php echo $coins[$i]['symbol'] ?></option>
                          <?php }
                        }
                        ?>
                      </select>
                    </div>
                </div>  
                </div>
              </div>

               <div class="row">
                <div class="col-md-6">
                  <div class="col-md-9">
                    <label>Pline<span class="pl">100</span></label>
                      <div class="range-slider">
                       <input type="range" min="10" max="500" value="100" name="pline" class="slider" id="myRange"  data-show-value = "true" data-popup-enabled = "true">
                    </div>
                    </div>
                    <div class="col-md-3" style="top: 10px;">
                       <div id="demo" class="alert alert-success alert-dismissable" style="width:10px; height: auto;"></div>
                    </div>
                  </div>
                  <div class="col-md-6">
                     <div class="col-md-9">
                       <label>Tline<span class="tl">7</span></label>
                        <div class="range-slider">
                        <input type="range" min="20" max="1000" value="50" name="tline" class="slider" id="myRange1">
                        </div>
                      </div>
                      <div class="col-md-3" style="top: 10px;">
                         <div id="demo1" class="alert alert-success alert-dismissable" style="width:10px; height: auto;"></div>
                       </div>
                  </div>
              </div>
              <div class="row">
                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label for="comment" class="col-md-3">Warning limit:</label>
                    <div class="col-md-9">
                      <input type="number"  id="limit_id" class=" form-control" name="warning_limit"  step="0.1" value="0.1">
                    </div>
                </div>  
                </div> 
                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                   <label for="comment" class="col-md-3">candle seconds:</label>
                     <div class="col-md-9">
                          <input type="number" id="candlePeriod_id" name="candle_seconds" class=" form-control"   value="3600">
                     </div>
                  </div>
                </div> 
                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label for="comment" class="col-md-3">vol MA size:</label>
                      <div class="col-md-9">
                        <input type="number" id="sizeMAVol_id" name="vol_ma_size" class=" form-control" step="10"   value="10">
                      </div>
                  </div>
                </div>  
                <div class="col-md-6"> 
                  <div class="form-group col-md-12">
                    <label for="comment" class="col-md-3">Lookback:</label>
                      <div class="col-md-9">
                        <input type="number" id="Lookback" name="lookback" class=" form-control" value="7">
                      </div>
                  </div>
                </div>  
                <hr>
               <div class="col-md-6">
                       <div class="funkyradio">
                       <div class="funkyradio-default">
                       <input type="checkbox" name="enableBarColors" id="enableBarColors" />
                          <label for="enableBarColors">enableBarColors</label>
                      </div>
                      <div class="funkyradio-default">
                        <input type="checkbox" name="use2Bars" value="1" id="use2Bars" checked="true" />
                          <label for="use2Bars">use2Bars</label>
                      </div>
                      <div class="funkyradio-default">
                      
                          <input type="checkbox" name="lowVol" value="1" id="lowVol" checked="true"/>
                          <label for="lowVol">lowVol</label>
                      </div>
                      <div class="funkyradio-default">
                        <input type="checkbox" name="climaxUp" value="1" id="climaxUp" checked="true"/>
                          <label for="climaxUp">climaxUp</label>
                      </div>
                    </div>
                  </div>
                      <div class="col-md-6">
                      <div class="funkyradio">
                      <div class="funkyradio-default">
                       <input type="checkbox" name="climaxDown" value="1" id="climaxDown" checked="true"/>
                          <label for="climaxDown">climaxDown</label>
                      </div>
                      <div class="funkyradio-default">
                        <input type="checkbox" name="churn" value="1" id="churn" checked="true"/>
                          <label for="churn">churn</label>
                      </div>
                      <div class="funkyradio-default">
                          <input type="checkbox" name="climaxChurn" value="1" id="climaxChurn" checked="true"/>
                          <label for="climaxChurn">climaxChurn</label>
                      </div>
                       <div class="funkyradio-default">
                          <input type="checkbox" name="chck_white" id="chck_white"/>
                          <label for="chck_white">First chart color white</label>
                      </div>
                    </div>
                  </div>
              </div>
              <!-- // Row END -->

              <div class="row">
               <div class="col-md-4">
                 <div class="funkyradio">
                    <div class="funkyradio-default">
                            <input type="checkbox" name="climaxDown1" value="1" id="climaxDown1" checked="true"/>
                            <label for="climaxDown1">Climax Down</label>
                    </div>
                   
                  </div>
                 </div>
                 <div class="col-md-4">
                 <div class="funkyradio">
                    <div class="funkyradio-default">
                      <input type="checkbox" name="churn1" value="1" id="churn1" checked="true"/>
                      <label for="churn1">Churn</label>
                    </div>
                   
                  </div>
                 </div>
                 <div class="col-md-4">
                 <div class="funkyradio">
                    <div class="funkyradio-default">
                          <input type="checkbox" name="climaxChurn1" value="1" id="climaxChurn1" checked="true"/>
                            <label for="climaxChurn1">Climax Churn</label>
                    </div>
                   
                  </div>
                 </div>
               </div>
               <br>
               <hr>
               <div class="row">
                 <div class="col-md-12">
                    <div class="form-group col-md-12">
                    <label for="comment" class="col-md-3">Pivot Length Left Hand Side:</label>
                    <div class="col-md-9">
                      <input type="number"  id="pvtLenL" class=" form-control" name="pvtLenL"  step="1" value="5">
                    </div>
                </div>  
                 </div>
               </div>
               <div class="row">
                 <div class="col-md-12">
                    <div class="form-group col-md-12">
                    <label for="comment" class="col-md-3">Pivot Length Right Hand Side:</label>
                    <div class="col-md-9">
                      <input type="number"  id="pvtLenR" class=" form-control" name="pvtLenR"  step="1" value="3">
                    </div>
                </div>  
                 </div>
               </div>
               <div class="row">
                 <div class="col-md-12">
                    <div class="form-group col-md-12">
                    <label for="comment" class="col-md-3">Maximum Extension In Length:</label>
                    <div class="col-md-9">
                      <input type="number"  id="maxLvlLen" class=" form-control" name="maxLvlLen"  step="1" value="0">
                    </div>
                </div>  
                 </div>
               </div>

               <div class="row">
               <div class="col-md-6">
                 <div class="funkyradio">
                    <div class="funkyradio-default">
                    <input type="checkbox" name="ShowHHLL" value="1" id="ShowHHLL" />                      <label for="ShowHHLL">Show HH,LL,LH,HL Markers On Pivots Points:</label>
                    </div>
                   
                  </div>
                 </div>

                  <div class="col-md-6">
                 <div class="funkyradio">
                    <div class="funkyradio-default">
                    <input type="checkbox" name="WaitForClose" value="1" id="WaitForClose" checked="true"/>
                    <label for="WaitForClose">Wait For Candle Close Before Printing Pivot:</label>
                    </div>
                   
                  </div>
                 </div>
               </div>
               <hr>
               <div class="row">
                 <div class="col-md-3">
                  <div class="rfi_row">
                  <label for="comment">PercentileTrigger:</label>
                  <input type="number"  id="PercentileTrigger" name="PercentileTrigger" class=" form-control" value="90">
                </div>
                 </div>

                 <div class="col-md-3">
                  <div class="rfi_row ">
                     <label for="comment">DemandTrigger:</label>                
                  
                     <input type="number"  id="DemandTrigger" name="DemandTrigger" value="23598658" class=" form-control">
                  </div>
                </div>

                 <div class="col-md-3">
                  <div class="rfi_row">
                    <label for="comment">SupplyTrigger:</label>
                     <input type="number"  id="SupplyTrigger" value="23598658" name="SupplyTrigger" class=" form-control">
                  </div>
                 </div>

                 <div class="col-md-3">
                    <div class="rfi_row">
                      <label for="comment">BarsBack:</label>
                      <input type="number" id="BarsBack" value="8" name="BarsBack" class=" form-control">
                   </div>
                 </div>
               </div>

               <div class="row">
                 <div class="col-md-3">
                   <div class="rfi_row supply_cls">
                      <label for="comment">Current Down Percentile D:</label>
                      <input type="number" id="Current_Down_Percentile" name="Current_Down_Percentile" class=" form-control" value="25">
                  </div>
                 </div>

                <div class="col-md-3">
                    <div class="rfi_row supply_cls">
                    <label for="comment">Current Down Percentile S:</label>
                    <input type="number" id="Current_Down_Percentile_supply" name="Current_Down_Percentile_supply" class=" form-control" value="25">
                     </div>
                 </div>

                 <div class="col-md-3">
                     <div class="rfi_row supply_cls">
                          <label for="comment">Continuationi Down Percentile D:</label>
                          <input type="number" name="Continuation_Down_Percentile" id="Continuation_Down_Percentile" class=" form-control" value="20">
                      </div>
                  </div>

                  <div class="col-md-3">
                    <div class="rfi_row supply_cls">
                          <label for="comment">Continuationi Down Percentile S:</label>
                          <input type="number" name="Continuation_Down_Percentile_supply" id="Continuation_Down_Percentile_supply" class=" form-control" value="20">
                      </div>
                  </div>

                  <div class="col-md-3">
                    <div class="rfi_row supply_cls">
                        <label for="comment">Current Up Percentile D:</label>
                        <input type="number" name="Current_up_Percentile" id="Current_up_Percentile" class=" form-control" value="25">
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="rfi_row supply_cls">
                          <label for="comment">Current Up Percentile S:</label>
                          <input type="number" name="Current_up_Percentile_supply" id="Current_up_Percentile_supply" class=" form-control" value="25">
                      </div>
                  </div>

                  <div class="col-md-3">
                    <div class="rfi_row supply_cls">
                        <label for="comment">Continuation Up Percentile D:</label>
                        <input type="number" name="Continuation_up_Percentile" id="Continuation_up_Percentile" class=" form-control" value="30">
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="rfi_row supply_cls">
                      <label for="comment">Continuation Up Percentile S:</label>
                      <input type="number" name="Continuation_up_Percentile_supply" id="Continuation_up_Percentile_supply" class=" form-control" value="30">
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="rfi_row supply_cls">
                      <label for="comment">LH Percentile D:</label>
                      <input type="number" name="LH_Percentile" id="LH_Percentile" value="10" class=" form-control">
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="rfi_row supply_cls">
                        <label for="comment">LH Percentile S:</label>
                        <input type="number" name="LH_Percentile_supply" value="10" id="LH_Percentile_supply" class=" form-control">
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="rfi_row supply_cls">
                      <label for="comment">HL Percentile D:</label>
                      <input type="number" name="HL_Percentile" id="HL_Percentile" value="10" class=" form-control">
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="rfi_row supply_cls">
                        <label for="comment">HL Percentile S:</label>
                        <input type="number" name="HL_Percentile_supply" id="HL_Percentile_supply" value="10" class=" form-control">
                    </div>
                  </div>
               </div>
              <hr class="separator">
              <!-- Form actions -->
              <div class="form-actions">
                <button class="btn btn-success" type="submit"><i class="fa fa-check-circle"></i> Save Settings </button>
              </div>
              <!-- // Form actions END --> 
              
            </div>
            </form>
        </div>    
     
    
  </div>
</div>
<script type="text/javascript">
var slider = document.getElementById("myRange");
var output = document.getElementById("demo");
var outputs = document.getElementsByClassName("pl");

var slider1 = document.getElementById("myRange1");
var output1 = document.getElementById("demo1");
var output1s = document.getElementsByClassName("tl");

output.innerHTML = slider.value; // Display the default slider value
output1.innerHTML = slider1.value;
// Update the current slider value (each time you drag the slider handle)
slider.oninput = function() {
    output.innerHTML = this.value;
    outputs.innerHTML= this.value;
}
slider1.oninput = function() {
    output1.innerHTML = this.value;
    output1s.innerHTML = this.value;
}
</script>