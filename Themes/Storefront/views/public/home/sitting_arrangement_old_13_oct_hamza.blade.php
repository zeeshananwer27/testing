<?php

$segments = request()->segments();
if(isset($segments[1]) && $e_id !='00'){
    if(is_numeric($segments[1])){
        $res = DB::table('products')->where('id', $segments[1] )->select('sitting_arrangement')->get()->first();
        $sitting_arrangement = $res->sitting_arrangement;  // diagram json array
    }
}else{
    $res = DB::table('temp_data')->select('sitting_arrangement')->get()->first();
    if ($res==null){
        $sitting_arrangement = '';
    }else{
        $sitting_arrangement = $res->sitting_arrangement;
    }
}
?>
<html>
   <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

      <meta name="viewport" content="width=device-width, initial-scale=1,
         maximum-scale=1, user-scalable=0" />
         <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>Tickets</title>
      <!-- Don't reposition css files because dynamic css will change.
           Dynamic css used for color picker component.
           Our stylesheet is at 6th position in document.stylesheets. So, all of the dynamic css
           works on {document.stylesheets[6]} this sheet. That's why repositioning will affect
           color picker dynamic css.
      -->
      <link rel="shortcut icon" href="<?php echo url('modules/sitting_arrangements/assets/images/logo.png') ?>" />
      <!-- <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
      <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"> -->
      <link rel="stylesheet" href="<?php echo url('modules/sitting_arrangements/assets/css/bootstrap-grid.min.css') ?>" type="text/css" />
      <link rel="stylesheet" href="<?php echo url('modules/sitting_arrangements/assets/css/bootstrap.min.css') ?>" type="text/css" />
      <link rel="stylesheet" href="<?php echo url('modules/sitting_arrangements/assets/css/bootstrap-reboot.min.css') ?>" type="text/css" />
      <link rel="stylesheet" href="<?php echo url('modules/sitting_arrangements/assets/css/font-awesome.min.css') ?>" type="text/css" />
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/rangeslider-pure@0.4.4/dist/range-slider.css">
      <link rel="stylesheet" href="<?php echo url('modules/sitting_arrangements/assets/icons/flaticon.css') ?>" type="text/css" />
      <link rel="stylesheet" href="<?php echo url('modules/sitting_arrangements/assets/css/style.css') ?>" type="text/css" />
      <link rel="stylesheet" href="<?php echo url('modules/sitting_arrangements/assets/css/toast.css') ?>" type="text/css" />
      <link rel="stylesheet" href="<?php echo url('modules/sitting_arrangements/assets/css/tiersColorPickerCircles.css') ?>" type="text/css" />
      <link rel="stylesheet" href="<?php echo url('modules/sitting_arrangements/assets/css/holdsColorPickerCircles.css') ?>" type="text/css" />
      <link rel="stylesheet" href="<?php echo url('modules/sitting_arrangements/assets/css/colorPicker.css') ?>" type="text/css" />
      <script src="<?php echo url('modules/sitting_arrangements/go-library/go-debug.js') ?>"></script>
      <script src="<?php echo url('modules/sitting_arrangements/assets/js/GroupRotatingTool.js') ?>"></script>
      <script src="<?php echo url('modules/sitting_arrangements/assets/js/pSBC.js') ?>"></script>
      <script src="<?php echo url('modules/sitting_arrangements/assets/js/components/0.global.js') ?>"></script>
      <script src="<?php echo url('modules/sitting_arrangements/assets/js/components/1.Handlers.js') ?>"></script>
      <script src="<?php echo url('modules/sitting_arrangements/assets/js/components/2.Shapes.js') ?>"></script>
      <script src="<?php echo url('modules/sitting_arrangements/assets/js/components/3.Models.js') ?>"></script>
      <script src="<?php echo url('modules/sitting_arrangements/assets/js/components/4.Utilities.js') ?>"></script>
      <script src="<?php echo url('modules/sitting_arrangements/assets/js/components/5.Getters.js') ?>"></script>
      <script src="<?php echo url('modules/sitting_arrangements/assets/js/components/6.Removers.js') ?>"></script>
      <script src="<?php echo url('modules/sitting_arrangements/assets/js/components/7.Updaters.js') ?>"></script>
      <script src="<?php echo url('modules/sitting_arrangements/assets/js/components/8.Validators.js') ?>"></script>
      <script src="<?php echo url('modules/sitting_arrangements/assets/js/components/9.EventListeners.js') ?>"></script>
      <!-- <script src="./assets/js/scripts.js"></script> -->
      <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css">
      <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
   </head>
   <body onload="init()">
      <div id="snackbar"></div>
        <input type = "hidden" id = "e_id" name = "e_id" value = "{{ @$e_id}}" />
      <div class="container-fluid">
      <div class="row">
      <div class="col-12 py-3 pl-5">
         <div class="row">
            <div class="col">
               <div class="tab-header">
                  <h2>Sitting Arrangement</h2>
               </div>
            </div>
            <div class="col-auto text-center">
               <div class="tab-header">
                  <button class="btn btn-danger" id="saveButton">Save</button>
               </div>
            </div>
         </div>
      </div>
      <div class="col-12" id="body">
      <!-- Nav pills -->
      <ul class="nav nav-pills main_tabs">
      <li class="nav-item">
            <a class="nav-link active" id="webMenu-map">Map</a>
         </li>
         <!-- <li class="nav-item">
            <a class="nav-link" id="webMenu-tiers">Tiers</a>
         </li>
         <li class="nav-item">
            <a class="nav-link" id="webMenu-holds">Holds</a>
         </li> -->
      </ul>
      <!-- Tab panes -->
      <div class="tab-content">
         <div class="tab-pane active show">
            <div class="row">
               <div class="col-md-7 col-lg-9 px-0">
                  <div class="vmr-zoom__container">
                     <button class="vmr-zoom__button" id="zoomIn">
                        <i class="fa fa-plus"></i>
                     </button>
                     <button class="vmr-zoom__button" id="zoomOut">
                        <i class="fa fa-minus"></i>
                     </button>
                  </div>
                  <div id="myDiagramDiv" style="background-color: white; width:
                     100%; height:
                     100%">
                  </div>
               </div>
               <div class="col-md-5 col-lg-3 px-0">
                  <div class="canvas_setting" id="map">
                     <div class="canvas_set_main" id="capacity">
                        <!-- inner tabs -->
                        <!-- Nav pills -->
                        <p class="capacity">Capacity <span id="capacityValue">0</span></p>
                        <ul class="nav nav-pills setting_tabs">
                           <li class="nav-item" data-toggle="tooltip" data-placement="bottom" title="Add a section">
                              <a class="nav-link active" data-toggle="pill"
                                 href="#menu1"
                                 onclick="Helper__HideOrShowCapacityMenu({elementID:
                                 'menu1'})">
                              <img src="<?php echo url('modules/sitting_arrangements/assets/images/t5.png') ?>" class="d-block
                                 mx-auto" alt="" />
                              <img src="<?php echo url('modules/sitting_arrangements/assets/images/t1.png') ?>" class="d-none
                                 mx-auto add_icon" alt="" />
                              Selection
                              </a>
                           </li>
                           <li class="nav-item" data-toggle="tooltip" data-placement="bottom" title="Add a table">
                              <a class="nav-link" data-toggle="pill" href="#menu2"
                                 onclick="Helper__HideOrShowCapacityMenu({elementID:
                                 'menu2'})">
                              <img src="<?php echo url('modules/sitting_arrangements/assets/images/t2.png') ?>" class="d-block
                                 mx-auto" alt="" />
                              <img src="<?php echo url('modules/sitting_arrangements/assets/images/t1.png') ?>" class="d-none
                                 mx-auto add_icon" alt="" />
                              Table
                              </a>
                           </li>
                           <li class="nav-item" data-toggle="tooltip" data-placement="bottom" title="Add an object">
                              <a class="nav-link" data-toggle="pill" href="#menu3"
                                 onclick="Helper__HideOrShowCapacityMenu({elementID:
                                 'menu3'})">
                              <img src="<?php echo url('modules/sitting_arrangements/assets/images/t3.png') ?>" class="d-block
                                 mx-auto" alt="" />
                                 <img src="<?php echo url('modules/sitting_arrangements/assets/images/t1.png') ?>" class="d-none
                                 mx-auto add_icon" alt="" />
                              Object
                              </a>
                           </li>
                           <li class="nav-item" data-toggle="tooltip" data-placement="bottom" title="Add text">
                              <a class="nav-link" data-toggle="pill" href="#menu4"
                                 onclick="Helper__HideOrShowCapacityMenu({elementID:
                                 'menu4'})">
                              <img src="<?php echo url('modules/sitting_arrangements/assets/images/t4.png') ?>" class="d-block
                                 mx-auto" alt="" />
                                 <img src="<?php echo url('modules/sitting_arrangements/assets/images/t1.png') ?>" class="d-none
                                 mx-auto add_icon" alt="" />
                              Text
                              </a>
                           </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                           <div class="tab-pane" id="menu1">
                              <div class="selection_content">
                                 <p class="text-center"><u>1</u></p>
                                 <div class="form-group assign_seat">
                                    <i class="fa fa-angle-down"></i>
                                    <select class="selectSeat form-control"
                                       id="Section">
                                       <option value="AssignedSeating">Assigned Seating</option>
                                       <option value="GeneralAdmission">General
                                          Admission
                                       </option>
                                    </select>
                                 </div>
                                 <div class="AssignedSeating siting_options">
                                    <p class="capacity sit">
                                       Rows <span><input type="text" placeholder="5" value="5"
                                          id="getRows"
                                          onkeyup="Helper__ValidateField({ element:
                                          this, id: 'getRows', diagramType:
                                          'AssignedSeating', rowOrColumn: 'row'})"></span>
                                    </p>
                                    <p class="capacity sit">
                                       Seats
                                       <span><input type="text" placeholder="10" value="10"
                                          id="getColumns"
                                          onkeyup="Helper__ValidateField({ element:
                                          this, id: 'getColumns', diagramType:
                                          'AssignedSeating', rowOrColumn: 'column'})"></span>
                                    </p>
                                    <!-- <a id="increaseZoom">increaseZoom</a> -->
                                 </div>
                                 <div class="GeneralAdmission siting_options">
                                    <p class="capacity sit pt-1">
                                       <k class="mt-3 float-left">Type</k>
                                       <span>
                                       <input type="radio" name="type1" id="seating"
                                          class="input-hidden">
                                       <label for="seating">
                                       <img src="<?php echo url('modules/sitting_arrangements/assets/images/tt1.png') ?>" alt="a"
                                          id="generalAdmissionSeatingImg">
                                       </label>
                                       </span>
                                       <span>
                                       <input type="radio" name="type1" id="standing"
                                          class="input-hidden" checked>
                                       <label for="standing">
                                       <img src="<?php echo url('modules/sitting_arrangements/assets/images/tt2.png') ?>" alt="c">
                                       </label>
                                       </span>
                                    </p>
                                    <p class="capacity sit">Show icon
                                       <span> <input class="styled-checkbox"
                                          id="generalAdmissionShowIcon" type="checkbox"
                                          value="value2" checked>
                                       <label for="generalAdmissionShowIcon"></label></span>
                                    </p>
                                    <p class="capacity sit">
                                       Capacity
                                       <span><input type="text" placeholder="50"
                                          id="getCapacity" value="50"
                                          onkeyup="Helper__ValidateField({ element:
                                          this, id: 'getCapacity', diagramType:
                                          'GeneralAdmission'})"></span>
                                    </p>
                                 </div>
                                 <div class="row justify-content-center my-2">
                                    <div class="col-5">
                                       <div class="seats_btn">
                                          <button class="btn btn-danger"
                                             onclick="Helper__HideOrShow({elementID:
                                             'menu1'})">Cancel</button>
                                       </div>
                                    </div>
                                    <div class="col-5">
                                       <div class="seats_btn">
                                          <button class="btn btn-danger active" id="sectionCreateButton">
                                          Create
                                          </button>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="tab-pane fade" id="menu2">
                              <div class="selection_content">
                                 <p class="text-center"><u>Create Object</u></p>
                                 <div class="siting_options">
                                     <p class="shape sit pt-1">
                                         <k class="mt-3 float-left">Shape</k>
                                         <span id="objectRectangleSpan">
                                           <input type="radio" name="type2" id="tableRectangle" class="input-hidden">
                                           <label for="tableRectangle" class="px-2 py-1">
                                             <i class="flaticon-rectangular"></i>
                                           </label>
                                         </span>
                                         <span id="objectCircleSpan">
                                           <input type="radio" name="type2" id="tableCircle" checked class="input-hidden">
                                           <label for="tableCircle" class="px-2 py-1">
                                             <i class="flaticon-circle-outline"></i>
                                           </label>
                                         </span>
                                      </p>
                                 </div>
                                 <div>
                                   <div class="siting_options" id="objectLabel">
                                       <p class="capacity sit pt-1">
                                           <k class="mt-3 float-left">Seats</k>
                                           <span>
                                             <input type="text" name="type3" id="tableValue" value="8" placeholder="8">
                                           </span>
                                       </p>
                                       <p class="capacity sit pt-1" id="displayEndSeats" style="display: none;">
                                           <k class="mt-3 float-left">End Seats</k>
                                           <span>
                                             <input type="text" name="type3" id="endSeatsValue" value="2" placeholder="2">
                                           </span>
                                       </p>
                                   </div>
                                 </div>
                                 <div class="row justify-content-center my-2">
                                  <div class="col-5">
                                     <div class="seats_btn">
                                        <button class="btn btn-danger"
                                           onclick="Helper__HideOrShow({elementID:
                                           'menu2'})">Cancel</button>
                                     </div>
                                  </div>
                                  <div class="col-5">
                                     <div class="seats_btn">
                                        <button class="btn btn-danger active" id="tableCreateButton">
                                         Create
                                        </button>
                                     </div>
                                  </div>
                               </div>
                             </div>
                           </div>
                           <div class="tab-pane fade" id="menu3">
                              <div class="selection_content">
                                  <p class="text-center"><u>Create Object</u></p>
                                  <div class="siting_options">
                                      <p class="shape sit pt-1">
                                          <k class="mt-3 float-left">Shape</k>
                                          <span id="objectLineSpan">
                                            <input type="radio" name="type4" id="objectLine" class="input-hidden">
                                            <label for="objectLine"  class="px-2 py-1">
                                            <i class="flaticon-line"></i>
                                            </label>
                                          </span>
                                          <span id="objectCircleSpan">
                                            <input type="radio" name="type4" id="objectCircle" class="input-hidden">
                                            <label for="objectCircle"  class="px-2 py-1">
                                            <i class="flaticon-circle-outline"></i>
                                            </label>
                                          </span>
                                          <span id="objectRectangleSpan">
                                            <input type="radio" name="type4" id="objectRectangle" checked class="input-hidden">
                                            <label for="objectRectangle"  class="px-2 py-1">
                                            <i class="flaticon-rectangular"></i>
                                            </label>
                                          </span>
                                       </p>
                                  </div>
                                  <div id="objectBody">
                                    <div class="siting_options" id="objectLabel">
                                        <p class="capacity sit pt-1">
                                            <k class="mt-3 float-left">Label</k>
                                            <span>
                                              <input type="text" name="type5" id="objectLabelValue" value="Stage" placeholder="Optional">
                                            </span>
                                        </p>
                                    </div>
                                    <div class="siting_options">
                                       <p class="capacity sit">Object icon
                                          <span> <input class="styled-checkbox"
                                             id="objectShowIcon" type="checkbox"
                                             value="value2" checked>
                                          <label for="objectShowIcon"></label></span>
                                      </p>
                                    </div>
                                    <div class="siting_options">
                                        <p class="objectIcon sit pt-1 text-center">
                                            <span>
                                              <input type="radio" name="type6" id="objectStage" checked class="input-hidden">
                                              <label for="objectStage" id="objectStageLabel">
                                                <span class="icons-object-color">
                                                   <i class="flaticon-microphone"></i>
                                                </span>
                                              </label>
                                            </span>
                                            <span>
                                              <input type="radio" name="type6" id="objectFood" class="input-hidden">
                                              <label for="objectFood" id="objectFoodLabel">
                                                   <span class="icons-object-color">
                                                      <i class="flaticon-fork-and-knife"></i>
                                                   </span>
                                              </label>
                                            </span>
                                            <span>
                                              <input type="radio" name="type6" id="objectDrink" class="input-hidden">
                                              <label for="objectDrink" id="objectDrinkLabel">
                                                   <span class="icons-object-color">
                                                      <i class="flaticon-cocktail"></i>
                                                   </span>
                                              </label>
                                            </span>
                                            <span>
                                              <input type="radio" name="type6" id="objectBathroom" class="input-hidden">
                                              <label for="objectBathroom" id="objectBathroomLabel">
                                                   <span class="icons-object-color">
                                                      <i class="flaticon-wc"></i>
                                                   </span>
                                              </label>
                                            </span>
                                            <span>
                                              <input type="radio" name="type6" id="objectExit" class="input-hidden">
                                              <label for="objectExit" id="objectExitLabel">
                                                   <span class="icons-object-color">
                                                      <i class="flaticon-logout"></i>
                                                   </span>
                                              </label>
                                            </span>
                                            <span>
                                              <input type="radio" name="type6" id="objectDance" class="input-hidden">
                                              <label for="objectDance" id="objectDanceLabel">
                                                   <span class="icons-object-color">
                                                      <i class="flaticon-jumping-dancer"></i>
                                                   </span>
                                              </label>
                                            </span>
                                        </p>
                                    </div>
                                  </div>
                                  <div class="row justify-content-center my-2">
                                    <a class="sportsVenues" id="sportsVenuesLink">Sports Venues</a>
                                </div>
                                <div class="row justify-content-center my-2">
                                 <div class="col-5">
                                    <div class="seats_btn">
                                       <button class="btn btn-danger"
                                          onclick="Helper__HideOrShow({elementID:
                                          'menu3'})">Cancel</button>
                                    </div>
                                 </div>
                                 <div class="col-5">
                                    <div class="seats_btn">
                                       <button class="btn btn-danger active" id="objectCreateButton">
                                        Create
                                       </button>
                                    </div>
                                 </div>
                              </div>
                              </div>
                           </div>
                           <div class="tab-pane fade" id="menu4">
                              <div class="selection_content">
                                 <p class="text-center"><u>Text</u></p>
                                 <div class="row text-justify-center">
                                    <div class="col-md-2 align-self-center">
                                    Size
                                 </div>
                                 <div  class="col-md-10 m-0 float-right">
                                    <div class="float-right">
                                       <span>
                                        <input type="radio" name="type7" id="textSize1"
                                          class="input-hidden">
                                        <label for="textSize1">
                                          <h6 class="text-h6">A</h6>
                                        </label>
                                       </span>
                                       <span>
                                        <input type="radio" name="type7" id="textSize2"
                                          class="input-hidden">
                                        <label for="textSize2">
                                          <h5 class="text-h6">A</h5>
                                        </label>
                                       </span>
                                       <span>
                                        <input type="radio" name="type7" id="textSize3"
                                          class="input-hidden">
                                        <label for="textSize3">
                                          <h4 class="text-h6">A</h4>
                                        </label>
                                       </span>
                                       <span>
                                        <input type="radio" name="type7" id="textSize4" checked
                                          class="input-hidden">
                                        <label for="textSize4">
                                          <h3 class="text-h6">A</h3>
                                        </label>
                                       </span>
                                       <span>
                                        <input type="radio" name="type7" id="textSize5"
                                          class="input-hidden">
                                        <label for="textSize5">
                                          <h2 class="text-h6">A</h2>
                                        </label>
                                       </span>
                                       <span>
                                        <input type="radio" name="type7" id="textSize6"
                                          class="input-hidden">
                                        <label for="textSize6">
                                          <h1 class="text-h6">A</h1>
                                        </label>
                                       </span>
                                    </div>
                                    </div>
                                 </div>
                                 <div class="row justify-content-center my-2">
                                  <div class="col-5">
                                     <div class="seats_btn">
                                        <button class="btn btn-danger"
                                           onclick="Helper__HideOrShow({elementID:
                                           'menu4'})">Cancel</button>
                                     </div>
                                  </div>
                                  <div class="col-5">
                                     <div class="seats_btn">
                                        <button class="btn btn-danger active" id="createTextButton">
                                          Create
                                        </button>
                                     </div>
                                  </div>
                               </div>
                              </div>
                           </div>
                        </div>
                        <!-- inner tabs end -->
                     </div>
                     <!-- Selected menu with edit button -->
                     <div class="canvas_set_main" id="selected" style="display:
                        none;">
                        <p class="capacity">Selected <span>1</span></p>
                        <div class="tab-content">
                           <div class="tab-pane active">
                              <div class="selection_content mt-0 float-none">
                                 <div class="card mb-3 border-0">
                                    <div class="row no-gutters">
                                       <div class="col-auto">
                                          <img src="<?php echo url('modules/sitting_arrangements/assets/images/list.png') ?>"
                                             class="card-img" alt="list">
                                       </div>
                                       <div class="col">
                                          <div class="card-body p-0 pl-2">
                                             <p class="card-title mb-0" id="selectedSectionLabel">1</p>
                                             <p class="card-text"><small
                                                class="text-muted">Seat count: <span id="selectedSectionSeatCount">50</span></small></p>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!-- <div class="container1">
                                    <div class="row d-flex">
                                       <div class="col-auto align-self-center">
                                          <p class="m-0">Rotate</p>
                                       </div>
                                       <div class="col align-self-center">
                                          <input type="range" id="rotateRangeSlider"
                                             min="-360" max="360" />
                                          <output data-output></output>
                                       </div>
                                    </div>
                                 </div> -->
                              </div>
                              <div class="setting_tabs siting_edit_btn">
                                 <div class="row no-gutters">
                                    <div class="col">
                                       <a href="#Selection" id="editButton">
                                       <img src="<?php echo url('modules/sitting_arrangements/assets/images/pencil.png') ?>"
                                          class="mx-auto" alt="">
                                       Edit
                                       </a>
                                    </div>
                                    <div class="col">
                                       <a href="#Selection"
                                          onclick="Handler__onDuplicateNodes()">
                                       <img src="<?php echo url('modules/sitting_arrangements/assets/images/plus.png') ?>"
                                          class="mx-auto" alt="">
                                       Duplicate
                                       </a>
                                    </div>
                                    <div class="col">
                                       <a href="#Selection"
                                          onclick="Handler__onRemoveNodes()">
                                       <img src="<?php echo url('modules/sitting_arrangements/assets/images/del.png') ?>"
                                          class="mx-auto" alt="">
                                       Delete
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row no-gutters justify-content-center my-2">
                              <div class="col-5">
                                 <div class="seats_btn">
                                    <button class="btn btn-danger bg-none closed"
                                       data-target="#edit"
                                       onclick="Helper__clearSelection()">Cancel</button>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- inner tabs end -->
                     </div>
                     <!-- Selected menu end -->
                     <!-- Siting edit -->
                     <div class="canvas_set_main" id="editShape" style="display:
                        none;">
                        <div class="tab-content">
                           <div class="tab-pane active" id="Selection">
                              <div class="selection_content mt-0">
                                 <p class="text-center"><u>1</u></p>
                                 <div class="row justify-content-center">
                                    <div class="col-md-8">
                                       <ul class="nav nav-pills setting_tabs_edit">
                                          <li class="nav-item">
                                             <a class="nav-link active"
                                                data-toggle="pill" href="#layout">
                                             LAYOUT
                                             </a>
                                          </li>
                                          <li class="nav-item">
                                             <a class="nav-link" data-toggle="pill"
                                                href="#label">
                                             LABEL
                                             </a>
                                          </li>
                                       </ul>
                                    </div>
                                 </div>
                                 <div class="tab-content">
                                    <div class="tab-pane active" id="layout">
                                       <div class="set1 siting_options mt-3">
                                          <p class="capacity sit">Row count <span>
                                             <input type="text" id="layoutEditRows"
                                                /></span>
                                          </p>
                                          <p class="capacity sit">Seat count per Row
                                             <span><input type="text"
                                                id="layoutEditColumns"></span>
                                          </p>
                                          <p class="capacity sit">Total <span><u
                                             id="layoutEditTotal">50</u></span></p>
                                          <p class="capacity sit">
                                             <k class="mt-3 float-left">Alignment</k>
                                             <span>
                                             <input type="radio" name="align"
                                                id="align2" class="input-hidden" checked
                                                />
                                             <label for="align2">
                                             <img src="<?php echo url('modules/sitting_arrangements/assets/images/t7.png') ?>"
                                                alt="c" />
                                             </label>
                                             </span>
                                             <span>
                                             <input type="radio" name="align"
                                                id="align1" class="input-hidden" />
                                             <label for="align1">
                                             <img src="<?php echo url('modules/sitting_arrangements/assets/images/t6.png') ?>"
                                                alt="a" />
                                             </label>
                                             </span>
                                          </p>
                                       </div>
                                       <div class="container1">
                                          <div class="row d-flex my-3">
                                             <div class="col-auto align-self-center">
                                                <p class="m-0 sit">Curve</p>
                                             </div>
                                             <div class="col align-self-center">
                                                <input type="range" id="curveRangeSlider"
                                                   step="1" min="-10" max="10" value="0" />
                                             </div>
                                          </div>
                                       </div>
                                       <div class="container1">
                                          <div class="row d-flex my-3">
                                             <div class="col-auto align-self-center">
                                                <p class="m-0 sit">Skew</p>
                                             </div>
                                             <div class="col align-self-center">
                                                <input type="range" id="skewRangeSlider"
                                                   step="1" min="-10" max="10" value="0"
                                                   />
                                             </div>
                                          </div>
                                       </div>
                                       <!-- <div class="container1">
                                          <div class="row d-flex my-3">
                                             <div class="col-auto align-self-center">
                                                <p class="m-0 sit">Rotate</p>
                                             </div>
                                             <div class="col align-self-center">
                                                <input type="range" id="editASRotateRangeSlider"
                                                   step="1" min="0" max="360" value="180"
                                                   />
                                             </div>
                                          </div>
                                       </div> -->
                                       <div class="row justify-content-center my-2
                                          mt-5">
                                          <div class="col-5">
                                             <div class="seats_btn">
                                                <button class="btn btn-danger closed" id="editAssignedSeatingLayoutCancelButton">Cancel</button>
                                             </div>
                                          </div>
                                          <div class="col-5">
                                             <div class="seats_btn">
                                                <button class="btn btn-danger active" id="editAssignedSeatingLayoutApplyButton">Apply</button>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="tab-pane fade" id="label">
                                       <div class="main-contents">
                                          <div class="set1 siting_options mt-3">
                                             <p class="capacity sit">Section prefix<span><input
                                                type="text" placeholder="Section" /></span></p>
                                             <p class="capacity sit">Row prefix<span><input
                                                type="text" placeholder="Row"
                                                id="editLabelRowPrefix"
                                                onkeydown="Helper__LimitInputFieldText({element:
                                                this, limit: 8})"
                                                onkeyup="Helper__LimitInputFieldText({element:
                                                this, limit: 8})"
                                                /></span></p>
                                             <p class="capacity sit">Seat prefix<span><input
                                                type="text" placeholder="Seat"
                                                id="editLabelSeatPrefix"
                                                onkeydown="Helper__LimitInputFieldText({element:
                                                this, limit: 8})"
                                                onkeyup="Helper__LimitInputFieldText({element:
                                                this, limit: 8})"
                                                /></span></p>
                                             <p class="capacity sit">Show section label
                                                <span> <input class="styled-checkbox"
                                                   id="styled-checkbox-2" type="checkbox"
                                                   value="value2">
                                                <label for="styled-checkbox-2"></label></span>
                                             </p>
                                          </div>
                                          <div class="row my-2 mt-5">
                                             <div class="col-8 align-self-center">
                                                <label>Show row label
                                                </label>
                                             </div>
                                             <div class="col-4">
                                                <div class="form-group assign_seat">
                                                   <i class="fa fa-angle-down"></i>
                                                   <select class="selectSeat form-control">
                                                      <option value="set1">All</option>
                                                      <option value="set2">Left</option>
                                                      <option value="set2">Right</option>
                                                      <option value="set2">None</option>
                                                   </select>
                                                </div>
                                             </div>
                                             <hr class="hr_b">
                                          </div>
                                          <div class="row mt-3">
                                             <div class="col-7 align-self-center">
                                                <label id="editLabelRow">Row
                                                </label>
                                             </div>
                                             <div class="col-5">
                                                <div class="form-group assign_seat">
                                                   <i class="fa fa-angle-down"></i>
                                                   <select class="selectSeat form-control"
                                                      id="editLabelNumberSet">
                                                      <option value="naturalNumbers">1,2,3...</option>
                                                      <option value="oddNumbers">1,3,5...</option>
                                                      <option value="alphabets">A,B,C...</option>
                                                   </select>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="set1 siting_options">
                                             <p class="capacity sit">Start with<span><input
                                                type="text" id="editLabelStart"
                                                placeholder="1" /></span></p>
                                             <p class="capacity sit pt-1 border-bottom
                                                mb-3">
                                                <k class="mt-3 float-left">Label Direction</k>
                                                <span>
                                                <input type="radio" name="dir" id="dir1"
                                                   class="input-hidden" checked=""
                                                   onclick="Helper__RowLabelSorting()">
                                                <label for="dir1">
                                                <img src="<?php echo url('modules/sitting_arrangements/assets/images/arrow.png') ?>"
                                                   class="r-180" alt="c">
                                                </label>
                                                </span>
                                                <span>
                                                <input type="radio" name="dir" id="dir2"
                                                   class="input-hidden"
                                                   onclick="Helper__RowLabelSorting()">
                                                <label for="dir2">
                                                <img src="<?php echo url('modules/sitting_arrangements/assets/images/arrow.png') ?>"
                                                   alt="a">
                                                </label>
                                                </span>
                                             </p>
                                          </div>
                                          <div class="row">
                                             <div class="col-7 align-self-center">
                                                <label id="editLabelSeat">Label
                                                </label>
                                             </div>
                                             <div class="col-5">
                                                <div class="form-group assign_seat">
                                                   <i class="fa fa-angle-down"></i>
                                                   <select class="selectSeat form-control"
                                                      id="editSeatNumberSet">
                                                      <option value="naturalNumbers">1,2,3...</option>
                                                      <option value="oddNumbers">1,3,5...</option>
                                                      <option value="alphabets">A,B,C...</option>
                                                   </select>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="set1 siting_options">
                                             <p class="capacity sit">Start with<span><input
                                                type="text" id="editSeatStart"
                                                placeholder="1"></span></p>
                                             <p class="capacity sit pt-1">
                                                <k class="mt-3 float-left">Label Direction</k>
                                                <span>
                                                <input type="radio" name="dirl"
                                                   id="dirl" class="input-hidden"
                                                   onclick="Helper__SeatsSorting()"
                                                   checked="">
                                                <label for="dirl">
                                                <img src="<?php echo url('modules/sitting_arrangements/assets/images/arrow.png') ?>"
                                                   class="r-90n" alt="c">
                                                </label>
                                                </span>
                                                <span>
                                                <input type="radio" name="dirl"
                                                   id="dirr" class="input-hidden"
                                                   onclick="Helper__SeatsSorting()">
                                                <label for="dirr">
                                                <img src="<?php echo url('modules/sitting_arrangements/assets/images/arrow.png') ?>"
                                                   class="r-90" alt="a">
                                                </label>
                                                </span>
                                             </p>
                                          </div>
                                       </div>
                                       <div class="row justify-content-center pt-4">
                                          <div class="col-5">
                                             <div class="seats_btn">
                                                <button class="btn btn-danger closed" data-target="#editShape" id="editAssignedSeatingLabelCancelButton">Cancel</button>
                                             </div>
                                          </div>
                                          <div class="col-5">
                                             <div class="seats_btn">
                                                <button class="btn btn-danger active
                                                   closed" data-target="#editShape" id="editAssignedSeatingLabelApplyButton">Apply</button>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- inner tabs end -->
                     </div>
                     <!-- Siting edit end -->
                     <!-- Unit Information -->
                     <div id="unitMenu1" style="display: none;">
                        <div class="selection_content">
                           <p class="text-center"><u>Menu1</u></p>
                           <div class="AssignedSeating siting_options">
                              <p class="capacity sit">
                                 Row <span><input type="text"
                                    id="unitMenu1Row"></span>
                              </p>
                              <p class="capacity sit">
                                 Seat
                                 <span><input type="text"
                                    id="unitMenu1Seat"></span>
                              </p>
                              <div class="row">
                                 <div class="col-7 align-self-center">
                                    <label id="editLabelRow">Options
                                    </label>
                                 </div>
                                 <div class="col-5">
                                    <div class="form-group assign_seat">
                                       <i class="fa fa-angle-down"></i>
                                       <select class="selectSeat form-control" id="unitMenu1AdvancedOptions">
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row justify-content-center my-2">
                              <div class="col-12">
                                 <div class="seats_btn">
                                    <button class="btn btn-danger active w-100" id="unitMenu1DoneButton">
                                    Done
                                    </button>
                                 </div>
                              </div>
                           </div>
                           <hr />
                           <div class="row justify-content-center my-2"
                              id="menu1DeleteButton" style="display: true;">
                              <div class="col-12">
                                 <div class="seats_btn text-center">
                                    <button class="btn btn-danger delete_unit"
                                       id="onDeleteMenu1"><i class="fa fa-trash"></i> Delete Unit</button>
                                 </div>
                              </div>
                           </div>
                           <div class="row justify-content-center my-2"
                              id="menu1RestoreButton" style="display: none;">
                              <div class="col-12">
                                 <div class="seats_btn text-center">
                                    <button class="btn btn-danger delete_unit"
                                       id="onRestoreMenu1">Restore Unit</button>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- Unit Information End -->
                     <!-- Unit Information -->
                     <div id="unitMenu2" style="display: none;">
                        <div class="selection_content">
                           <p class="text-center"><u>Menu2</u></p>
                           <div class="AssignedSeating siting_options">
                              <p class="capacity sit">
                                 Row <span><input type="text" placeholder="5"
                                    id="unitMenu2Row"></span>
                              </p>
                              <div class="row mt-3">
                                 <div class="col-7 align-self-center">
                                    <label id="editLabelRow">Seat
                                    </label>
                                 </div>
                                 <div class="col-5">
                                    <div class="form-group assign_seat">
                                       <i class="fa fa-angle-down"></i>
                                       <select class="selectSeat form-control"
                                          id="unitMenu2NumberSet">
                                          <option value="naturalNumbers">1,2,3...</option>
                                          <option value="oddNumbers">1,3,5...</option>
                                          <option value="alphabets">A,B,C...</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-7 align-self-center">
                                    <label id="editLabelRow">Options
                                    </label>
                                 </div>
                                 <div class="col-5">
                                    <div class="form-group assign_seat">
                                       <i class="fa fa-angle-down"></i>
                                       <select class="selectSeat form-control" id="unitMenu2AdvancedOptions">
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="set1 siting_options">
                                 <p class="capacity sit">Start with<span><input
                                    type="text" id="unitMenu2SeatStart"
                                    placeholder="1" /></span></p>
                                 <p class="capacity sit pt-1 border-bottom
                                    mb-3">
                                    <k class="mt-3 float-left">Label Direction</k>
                                    <span>
                                    <input type="radio" name="unitMenu2dir"
                                       id="unitMenu2dir1"
                                       class="input-hidden" checked=""
                                       onclick="Helper__UnitMenu2SeatsSorting()">
                                    <label for="unitMenu2dir1">
                                    <img src="<?php echo url('modules/sitting_arrangements/assets/images/arrow.png') ?>"
                                       class="r-180" alt="c">
                                    </label>
                                    </span>
                                    <span>
                                    <input type="radio" name="unitMenu2dir"
                                       id="unitMenu2dir2"
                                       class="input-hidden"
                                       onclick="Helper__UnitMenu2SeatsSorting()">
                                    <label for="unitMenu2dir2">
                                    <img src="<?php echo url('modules/sitting_arrangements/assets/images/arrow.png') ?>"
                                       alt="a">
                                    </label>
                                    </span>
                                 </p>
                              </div>
                              <div class="row justify-content-center my-2">
                                 <div class="col-12">
                                    <div class="seats_btn">
                                       <button class="btn btn-danger w-100 active" id="unitMenu2DoneButton">
                                       Done
                                       </button>
                                    </div>
                                 </div>
                              </div>
                              <hr />
                              <div class="row justify-content-center my-2"
                                 id="menu2DeleteButton" style="display: true;">
                                 <div class="col-12">
                                    <div class="seats_btn text-center">
                                       <button class="btn btn-danger delete_unit"
                                          id="onDeleteMenu2"><i class="fa fa-trash"></i> Delete Unit</button>
                                    </div>
                                 </div>
                              </div>
                              <div class="row justify-content-center my-2"
                                 id="menu2RestoreButton" style="display: none;">
                                 <div class="col-12">
                                    <div class="seats_btn text-center">
                                       <button class="btn btn-danger delete_unit"
                                          id="onRestoreMenu2">Restore Unit</button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- Unit Information End -->
                     <!-- Unit Information -->
                     <div id="unitMenu3" style="display: none;">
                        <div class="selection_content">
                           <p class="text-center"><u>Menu2</u></p>
                           <div class="AssignedSeating siting_options">
                              <div class="row mt-3">
                                 <div class="col-7 align-self-center">
                                    <label id="editLabelRow">Row
                                    </label>
                                 </div>
                                 <div class="col-5">
                                    <div class="form-group assign_seat">
                                       <i class="fa fa-angle-down"></i>
                                       <select class="selectSeat form-control"
                                          id="unitMenu3NumberSet">
                                          <option value="naturalNumbers">1,2,3...</option>
                                          <option value="oddNumbers">1,3,5...</option>
                                          <option value="alphabets">A,B,C...</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="set1 siting_options">
                                 <p class="capacity sit">Start with<span><input
                                    type="text" id="unitMenu3LabelStart"
                                    placeholder="1" /></span></p>
                                 <p class="capacity sit pt-1 border-bottom
                                    mb-3">
                                    <k class="mt-3 float-left">Label Direction</k>
                                    <span>
                                    <input type="radio" name="unitMenu3dir"
                                       id="unitMenu3dir1"
                                       class="input-hidden" checked=""
                                       onclick="Helper__RowLabelSorting()">
                                    <label for="unitMenu3dir1">
                                    <img src="<?php echo url('modules/sitting_arrangements/assets/images/arrow.png') ?>"
                                       class="r-180" alt="c">
                                    </label>
                                    </span>
                                    <span>
                                    <input type="radio" name="unitMenu3dir"
                                       id="unitMenu3dir2"
                                       class="input-hidden"
                                       onclick="Helper__RowLabelSorting()">
                                    <label for="unitMenu3dir2">
                                    <img src="<?php echo url('modules/sitting_arrangements/assets/images/arrow.png') ?>"
                                       alt="a">
                                    </label>
                                    </span>
                                 </p>
                              </div>
                           </div>
                           <div class="AssignedSeating siting_options">
                              <div class="row mt-3">
                                 <div class="col-7 align-self-center">
                                    <label id="editLabelRow">Seat
                                    </label>
                                 </div>
                                 <div class="col-5">
                                    <div class="form-group assign_seat">
                                       <i class="fa fa-angle-down"></i>
                                       <select class="selectSeat form-control"
                                          id="unitMenu3SeatsNumberSet">
                                          <option value="naturalNumbers">1,2,3...</option>
                                          <option value="oddNumbers">1,3,5...</option>
                                          <option value="alphabets">A,B,C...</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-7 align-self-center">
                                    <label id="editLabelRow">Options
                                    </label>
                                 </div>
                                 <div class="col-5">
                                    <div class="form-group assign_seat">
                                       <i class="fa fa-angle-down"></i>
                                       <select class="selectSeat form-control" id="unitMenu3AdvancedOptions">
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="set1 siting_options">
                                 <p class="capacity sit">Start with<span><input
                                    type="text" id="unitMenu3SeatsStart"
                                    placeholder="1" /></span></p>
                                 <p class="capacity sit pt-1 border-bottom
                                    mb-3">
                                    <k class="mt-3 float-left">Label Direction</k>
                                    <span>
                                    <input type="radio" name="unitMenu33dir"
                                       id="unitMenu33dir1"
                                       class="input-hidden" checked=""
                                       onclick="Helper__SeatsSortingForUnitMenu3()">
                                    <label for="unitMenu33dir1">
                                    <img src="<?php echo url('modules/sitting_arrangements/assets/images/arrow.png') ?>"
                                       class="r-180" alt="c">
                                    </label>
                                    </span>
                                    <span>
                                    <input type="radio" name="unitMenu33dir"
                                       id="unitMenu33dir2"
                                       class="input-hidden"
                                       onclick="Helper__SeatsSortingForUnitMenu3()">
                                    <label for="unitMenu33dir2">
                                    <img src="<?php echo url('modules/sitting_arrangements/assets/images/arrow.png') ?>"
                                       alt="a">
                                    </label>
                                    </span>
                                 </p>
                              </div>
                              <div class="row justify-content-center my-2">
                                 <div class="col-12">
                                    <div class="seats_btn">
                                       <button class="btn btn-danger active w-100" id="unitMenu3DoneButton">
                                       Done
                                       </button>
                                    </div>
                                 </div>
                              </div>
                              <hr />
                              <div class="row justify-content-center my-2"
                                 id="menu3DeleteButton" style="display: true;">
                                 <div class="col-12">
                                    <div class="seats_btn text-center">
                                       <button class="btn btn-danger delete_unit"
                                          id="onDeleteMenu3"><i class="fa fa-trash"></i> Delete Unit</button>
                                    </div>
                                 </div>
                              </div>
                              <div class="row justify-content-center my-2"
                                 id="menu3RestoreButton" style="display: none;">
                                 <div class="col-12">
                                    <div class="seats_btn text-center">
                                       <button class="btn btn-danger delete_unit"
                                          id="onRestoreMenu3">Restore Unit</button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- Unit Information End -->
                     </div>
                     <!-- Unit Information End -->
                     <!-- Edit General Admission -->
                     <div id="editGeneralAdmission" style="display: none;">
                        <div class="selection_content">
                           <p class="text-center"><u>1</u></p>
                           <div class="siting_options">
                              <p class="capacity sit">
                                 Show Section Prefix
                                 <span><input type="text" placeholder="Section" value="Section"></span>
                              </p>
                              <p class="capacity sit">
                                 Show section label
                                 <span>
                                 <input class="styled-checkbox"
                                    id="styled-checkbox-1" type="checkbox"
                                    value="value1" checked />
                                 <label for="styled-checkbox-1"></label></span>
                              </p>
                              <p class="capacity sit pt-1">
                                 <k class="mt-3 float-left">Type</k>
                                 <span>
                                 <input type="radio" name="type5" id="editSeating"
                                    class="input-hidden">
                                 <label for="editSeating">
                                 <img src="<?php echo url('modules/sitting_arrangements/assets/images/tt1.png') ?>" alt="a"
                                    id="generalAdmissionSeatingImg">
                                 </label>
                                 </span>
                                 <span>
                                 <input type="radio" name="type5" id="editStanding"
                                    class="input-hidden">
                                 <label for="editStanding">
                                 <img src="<?php echo url('modules/sitting_arrangements/assets/images/tt2.png') ?>" alt="c">
                                 </label>
                                 </span>
                              </p>

                                 <p class="capacity sit">Show icon
                                    <span> <input class="styled-checkbox"
                                       id="editGeneralAdmissionShowIcon" type="checkbox"
                                       value="value2" checked>
                                    <label for="editGeneralAdmissionShowIcon"></label></span>
                                </p>
                              <p class="capacity sit">
                                 Capacity
                                 <span><input type="text" placeholder="20"
                                    id="editGetCapacity"></span>
                              </p>
                           </div>
                           <div class="row justify-content-center my-2">
                              <div class="col-5">
                                 <div class="seats_btn">
                                    <button class="btn btn-danger" id="editGeneralAdmissionCancelButton">Cancel</button>
                                 </div>
                              </div>
                              <div class="col-5">
                                 <div class="seats_btn">
                                    <button class="btn btn-danger active"
                                       id="editGeneralAdmissionApplyChanges">
                                    Apply
                                    </button>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- Edit General Admission End -->
                     <!-- Selected menu with edit button -->
                     <div class="canvas_set_main" id="selected_general_admission" style="display:
                     none;">
                        <p class="capacity">Selected <span>1</span></p>
                        <div class="tab-content">
                           <div class="tab-pane active">
                              <div class="selection_content mt-0 float-none">
                                 <div class="card mb-3 border-0">
                                    <div class="row no-gutters">
                                       <div class="col-auto">
                                          <img src="" id="selectedGeneralAdmissionImg"
                                             class="card-img" alt="list" style="display: block;">
                                       </div>
                                       <div class="col">
                                          <div class="card-body p-0 pl-2">
                                             <p class="card-title mb-0" id="selectedGeneralAdmissionLabel">2</p>
                                             <p class="card-text"><small
                                                class="text-muted">Capacity: <span id="selectedGeneralAdmissionSeatCount">50</span id="selectedGeneralAdmissionImg"></small></p>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="container1">
                                    <div class="row d-flex">
                                       <div class="col-auto align-self-center">
                                          <p class="m-0">Rotate</p>
                                       </div>
                                       <div class="col align-self-center">
                                          <input type="range" id="rotateRangeSlider"
                                             min="0" max="360" value="0"/>
                                          <output data-output></output>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="setting_tabs siting_edit_btn">
                                 <div class="row no-gutters">
                                    <div class="col">
                                       <a href="#Selection" id="editButtonGeneralAdmission">
                                       <img src="<?php echo url('modules/sitting_arrangements/assets/images/pencil.png') ?>"
                                          class="mx-auto" alt="">
                                       Edit
                                       </a>
                                    </div>
                                    <div class="col">
                                       <a href="#Selection" id="duplicateButtonGeneralAdmission">
                                       <img src="<?php echo url('modules/sitting_arrangements/assets/images/plus.png') ?>"
                                          class="mx-auto" alt="">
                                       Duplicate
                                       </a>
                                    </div>
                                    <div class="col">
                                       <a href="#Selection" id="deleteButtonGeneralAdmission">
                                       <img src="<?php echo url('modules/sitting_arrangements/assets/images/del.png') ?>"
                                          class="mx-auto" alt="">
                                       Delete
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row no-gutters justify-content-center my-2">
                              <div class="col-5">
                                 <div class="seats_btn">
                                    <button class="btn btn-danger bg-none closed"
                                       data-target="#edit"
                                       onclick="Helper__clearSelection()">Cancel</button>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- inner tabs end -->
                     </div>
                     <!-- Selected menu end -->
                     <!-- Selected menu -->
                     <div class="canvas_set_main collapse" id="selected_all"
                        style="display: none;">
                        <p class="capacity">Selected <span id="selectedValue">1</span></p>
                        <div class="tab-content">
                           <div class="tab-pane active" id="Selection">
                              <div class="selection_content mt-0 float-none" id="selected_all_body">
                              </div>
                              <div class="setting_tabs siting_edit_btn">
                                 <div class="row no-gutters">
                                    <div class="col">
                                       <a href="#Selection" id="selectedAllDuplicateButton">
                                       <img src="<?php echo url('modules/sitting_arrangements/assets/images/plus.png') ?>"
                                          class="mx-auto" alt="">
                                       Duplicate
                                       </a>
                                    </div>
                                    <div class="col">
                                       <a href="#Selection" id="selectedAllDeleteButton">
                                       <img src="<?php echo url('modules/sitting_arrangements/assets/images/del.png') ?>"
                                          class="mx-auto" alt="">
                                       Delete
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row no-gutters justify-content-center my-2">
                              <div class="col-5">
                                 <div class="seats_btn">
                                    <button class="btn btn-danger bg-none closed"
                                       data-target="#edit"
                                       onclick="Helper__clearSelection()">Cancel</button>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- inner tabs end -->
                     </div>
                     <!-- Selected menu end -->
                     <!-- Selected textblock menu -->
                     <div class="canvas_set_main collapse" id="selected_text_block" style="display: none;">
                      <p class="capacity">Selected <span>1</span></p>
                      <div class="tab-content">
                          <div class="tab-pane active">
                            <div class="selection_content mt-0 float-none">
                                <div class="mb-3">
                                  <div class="row no-gutters">
                                      <div class="col-auto">
                                        <img src="<?php echo url('modules/sitting_arrangements/assets/images/t4.png') ?>"
                                            class="card-img" alt="list">
                                      </div>
                                      <div class="col">
                                        <div class="card-body p-0 pl-2">
                                            <p class="card-text"><input class="text-muted" placeholder="Text" id="editTextBlock"/></p>
                                        </div>
                                      </div>
                                  </div>
                                </div>
                                <div class="container1">
                                    <div class="row d-flex">
                                       <div class="col-auto align-self-center">
                                          <p class="m-0">Rotate</p>
                                       </div>
                                       <div class="col align-self-center">
                                          <input type="range" id="editTextBlockRotateRangeSlider"
                                             min="0" max="360" value="0" />
                                          <output data-output></output>
                                       </div>
                                    </div>
                                 </div>
                            </div>
                            <div class="setting_tabs siting_edit_btn">
                                <div class="row no-gutters">
                                  <div class="col">
                                      <a href="#Selection" id="editTextBlockDuplicate">
                                      <img src="<?php echo url('modules/sitting_arrangements/assets/images/plus.png') ?>"
                                        class="mx-auto" alt="">
                                      Duplicate
                                      </a>
                                  </div>
                                  <div class="col">
                                      <a href="#Selection" id="editTextBlockDelete">
                                      <img src="<?php echo url('modules/sitting_arrangements/assets/images/del.png') ?>"
                                        class="mx-auto" alt="">
                                      Delete
                                      </a>
                                  </div>
                                </div>
                            </div>
                          </div>
                          <div class="row no-gutters justify-content-center my-2">
                            <div class="col-5">
                                <div class="seats_btn">
                                  <button class="btn btn-danger bg-none closed" data-target="#edit" id="editTextBlockCancel">Cancel</button>
                                </div>
                            </div>
                          </div>
                      </div>
                      <!-- inner tabs end -->
                      </div>
                     <!-- Selected textblock menu end -->
                     <!-- Selected line menu -->
                     <div class="canvas_set_main collapse" id="selected_object" style="display: none;">
                        <p class="capacity">Selected <span>1</span></p>
                        <div class="tab-content">
                            <div class="tab-pane active">
                              <div class="selection_content mt-0 float-none">
                                  <div class="mb-3">
                                    <div class="row no-gutters">
                                        <div class="col-auto">
                                          <i class="flaticon-microphone" id="selectedObjectImg"></i>
                                        </div>
                                        <div class="col">
                                          <div class="card-body p-0 pl-2">
                                              <p class="card-text">Stage</p>
                                          </div>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="container1">
                                      <div class="row d-flex">
                                         <div class="col-auto align-self-center">
                                            <p class="m-0">Rotate</p>
                                         </div>
                                         <div class="col align-self-center">
                                            <input type="range" id="editObjectRotateRangeSlider"
                                               min="0" max="360" value="0" />
                                            <output data-output></output>
                                         </div>
                                      </div>
                                   </div>
                              </div>
                              <div class="setting_tabs siting_edit_btn">
                                  <div class="row no-gutters">
                                    <div class="col">
                                        <a href="#Selection" id="editObjectDuplicate">
                                        <img src="<?php echo url('modules/sitting_arrangements/assets/images/plus.png') ?>"
                                          class="mx-auto" alt="">
                                        Duplicate
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="#Selection" id="editObjectDelete">
                                        <img src="<?php echo url('modules/sitting_arrangements/assets/images/del.png') ?>"
                                          class="mx-auto" alt="">
                                        Delete
                                        </a>
                                    </div>
                                  </div>
                              </div>
                            </div>
                            <div class="row no-gutters justify-content-center my-2">
                              <div class="col-5">
                                  <div class="seats_btn">
                                    <button class="btn btn-danger bg-none closed" data-target="#edit" id="editObjectCancel">Cancel</button>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <!-- inner tabs end -->
                        </div>
                     <!-- Selected line menu end -->
                     <!-- Selected line menu -->
                     <div class="canvas_set_main collapse" id="selected_line" style="display: none;">
                        <p class="capacity">Selected <span>1</span></p>
                        <div class="tab-content">
                            <div class="tab-pane active">
                              <div class="selection_content mt-0 float-none">
                                  <div class="mb-3">
                                    <div class="row no-gutters">
                                        <div class="col-auto">
                                          <i class="flaticon-line" id="selectedObjectImg"></i>
                                        </div>
                                        <div class="col">
                                          <div class="card-body p-0 pl-2">
                                              <p class="card-text">Line</p>
                                          </div>
                                        </div>
                                    </div>
                                  </div>
                              </div>
                              <div class="setting_tabs siting_edit_btn">
                                  <div class="row no-gutters">
                                    <div class="col">
                                        <a href="#Selection" id="editLineDuplicate">
                                        <img src="<?php echo url('modules/sitting_arrangements/assets/images/plus.png') ?>"
                                          class="mx-auto" alt="">
                                        Duplicate
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="#Selection" id="editLineDelete">
                                        <img src="<?php echo url('modules/sitting_arrangements/assets/images/del.png') ?>"
                                          class="mx-auto" alt="">
                                        Delete
                                        </a>
                                    </div>
                                  </div>
                              </div>
                            </div>
                            <div class="row no-gutters justify-content-center my-2">
                              <div class="col-5">
                                  <div class="seats_btn">
                                    <button class="btn btn-danger bg-none closed" data-target="#edit" id="editLineCancel">Cancel</button>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <!-- inner tabs end -->
                     </div>
                     <!-- Selected line menu end -->
                     <!-- Selected menu with edit button -->
                     <div class="canvas_set_main" id="selected_table" style="display:
                     none;">
                        <p class="capacity">Selected <span>1</span></p>
                        <div class="tab-content">
                           <div class="tab-pane active">
                              <div class="selection_content mt-0 float-none">
                                 <div class="card mb-3 border-0">
                                    <div class="row no-gutters">
                                       <div class="col-auto">
                                          <img src="<?php echo url('modules/sitting_arrangements/assets/images/t2.png') ?>"
                                             class="card-img" alt="list">
                                       </div>
                                       <div class="col">
                                          <div class="card-body p-0 pl-2">
                                             <p class="card-title mb-0" id="selectedTableLabel">2</p>
                                             <p class="card-text"><small
                                                class="text-muted">Seat count: <span id="selectedTableSeatCount">8</span></small></p>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="container1">
                                    <div class="row d-flex">
                                       <div class="col-auto align-self-center">
                                          <p class="m-0">Rotate</p>
                                       </div>
                                       <div class="col align-self-center">
                                          <input type="range" id="rotateTableRangeSlider"
                                             min="0" max="360" value="0" />
                                          <output data-output></output>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="setting_tabs siting_edit_btn">
                                 <div class="row no-gutters">
                                    <div class="col">
                                       <a href="#Selection" id="editTableButton">
                                       <img src="<?php echo url('modules/sitting_arrangements/assets/images/pencil.png') ?>"
                                          class="mx-auto" alt="">
                                       Edit
                                       </a>
                                    </div>
                                    <div class="col">
                                       <a href="#Selection" id="duplicateTableButton">
                                       <img src="<?php echo url('modules/sitting_arrangements/assets/images/plus.png') ?>"
                                          class="mx-auto" alt="">
                                       Duplicate
                                       </a>
                                    </div>
                                    <div class="col">
                                       <a href="#Selection" id="deleteTableButton">
                                       <img src="<?php echo url('modules/sitting_arrangements/assets/images/del.png') ?>"
                                          class="mx-auto" alt="">
                                       Delete
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row no-gutters justify-content-center my-2">
                              <div class="col-5">
                                 <div class="seats_btn">
                                    <button class="btn btn-danger bg-none closed"
                                       data-target="#edit"
                                       onclick="Helper__clearSelection()">Cancel</button>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- inner tabs end -->
                     </div>
                     <!-- Selected menu end -->
                     <!-- Siting edit -->
                     <div class="canvas_set_main" id="editTable" style="display: none;">
                        <div class="tab-content">
                           <div class="tab-pane active" id="Selection">
                              <div class="selection_content mt-0">
                                 <p class="text-center"><u>1</u></p>
                                 <div class="tab-content">
                                    <div class="tab-pane active" id="layout">
                                       <div class="set1 siting_options mt-3">
                                          <p class="capacity sit">Table Prefix<span>
                                             <input type="text" id="tablePrefix" value="Table"/></span>
                                          </p>
                                          <p class="capacity sit">Seat Prefix
                                             <span><input type="text" id="editTableSeatPrefix" value="Seat" />
                                          </span>
                                          </p>
                                          <p class="capacity sit">
                                             Seat Total
                                             <span><input type="text" id="editTableSeatTotal" placeholder="8"></span>
                                          </p>
                                          <p class="capacity sit" id="editTableEndSeatsDisplay" style="display: none;">
                                             End Seats
                                             <span><input type="text" id="editTableEndSeats" placeholder="2"></span>
                                          </p>
                                       </div>
                                       <div class="row mt-3">
                                          <div class="col-7 align-self-center">
                                             <label id="editTableSeatLabel">Seat
                                             </label>
                                          </div>
                                          <div class="col-5">
                                             <div class="form-group assign_seat">
                                                <i class="fa fa-angle-down"></i>
                                                <select class="selectSeat form-control" id="editTableNumberSet">
                                                   <option value="naturalNumbers">1,2,3...</option>
                                                   <option value="oddNumbers">1,3,5...</option>
                                                   <option value="alphabets">A,B,C...</option>
                                                </select>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="set1 siting_options">
                                          <p class="capacity sit">Start with
                                             <span>
                                             <input type="text" id="editTableStart" placeholder="1" />
                                             </span>
                                          </p>
                                       </div>
                                    </div>
                                    <div>
                                       <div class="row justify-content-center pt-4">
                                          <div class="col-5">
                                             <div class="seats_btn">
                                                <button class="btn btn-danger closed" data-target="#editShape" id="editTableCancelButton">Cancel</button>
                                             </div>
                                          </div>
                                          <div class="col-5">
                                             <div class="seats_btn">
                                                <button class="btn btn-danger active
                                                   closed" data-target="#editShape" id="editTableApplyButton">Apply</button>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- inner tabs end -->
                     </div>
                     <!-- Siting edit end -->
                     <!-- Unit Information -->
                     <div id="editTableSeatMenu1" style="display: none;">
                        <div class="selection_content">
                           <p class="text-center"><u>Menu1</u></p>
                           <div class="AssignedSeating siting_options">
                              <p class="capacity sit">
                                 Seat
                                 <span><input type="text" id="editTableSeatMenu1SeatValue"></span>
                              </p>
                           </div>
                           <div class="row">
                              <div class="col-7 align-self-center">
                                 <label id="editLabelRow">Options
                                 </label>
                              </div>
                              <div class="col-5">
                                 <div class="form-group assign_seat">
                                    <i class="fa fa-angle-down"></i>
                                    <select class="selectSeat form-control" id="editTableSeatMenu1AdvancedOptions">
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="row justify-content-center my-2">
                              <div class="col-12">
                                 <div class="seats_btn">
                                    <button class="btn btn-danger active w-100" id="editTableSeatMenu1DoneButton">
                                       Done
                                    </button>
                                 </div>
                              </div>
                           </div>
                           <hr />
                           <div class="row justify-content-center my-2"
                              id="editTableSeatMenu1DisplayDeleteButton" style="display: block;">
                              <div class="col-12">
                                 <div class="seats_btn text-center">
                                    <button class="btn btn-danger delete_unit" id="editTableSeatMenu1DeleteButton"><i class="fa fa-trash"></i> Delete Unit</button>
                                 </div>
                              </div>
                           </div>
                           <div class="row justify-content-center my-2"
                              id="editTableSeatMenu1DisplayRestoreButton" style="display: none;">
                              <div class="col-12">
                                 <div class="seats_btn text-center">
                                    <button class="btn btn-danger delete_unit" id="editTableSeatMenu1RestoreButton">Restore Unit</button>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- Unit Information End -->
                     <!-- Unit Information -->
                     <div id="editTableSeatMenu2" style="display: none;">
                        <div class="selection_content">
                           <p class="text-center"><u>Menu2</u></p>
                           <div class="AssignedSeating siting_options">
                              <div class="row mt-3">
                                 <div class="col-7 align-self-center">
                                    <label id="editLabelRow">Seat
                                    </label>
                                 </div>
                                 <div class="col-5">
                                    <div class="form-group assign_seat">
                                       <i class="fa fa-angle-down"></i>
                                       <select class="selectSeat form-control"
                                          id="editTableSeatMenu2NumberSet">
                                          <option value="naturalNumbers">1,2,3...</option>
                                          <option value="oddNumbers">1,3,5...</option>
                                          <option value="alphabets">A,B,C...</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-7 align-self-center">
                                    <label id="editLabelRow">Options
                                    </label>
                                 </div>
                                 <div class="col-5">
                                    <div class="form-group assign_seat">
                                       <i class="fa fa-angle-down"></i>
                                       <select class="selectSeat form-control" id="editTableSeatMenu2AdvancedOptions">
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="set1 siting_options">
                                 <p class="capacity sit">Start with<span><input
                                    type="text" id="editTableSeatMenu2SeatStart"
                                    placeholder="1" /></span></p>
                                 <p class="capacity sit pt-1 border-bottom
                                    mb-3">
                                    <k class="mt-3 float-left">Label Direction</k>
                                    <span>
                                    <input type="radio" name="editTableSeatMenu2dir"
                                       id="editTableSeatMenu2dir1"
                                       class="input-hidden" checked="">
                                    <label for="editTableSeatMenu2dir1">
                                    <img src="<?php echo url('modules/sitting_arrangements/assets/images/arrow.png') ?>"
                                       class="r-180" alt="c">
                                    </label>
                                    </span>
                                    <span>
                                    <input type="radio" name="editTableSeatMenu2dir"
                                       id="editTableSeatMenu2dir2"
                                       class="input-hidden">
                                    <label for="editTableSeatMenu2dir2">
                                    <img src="<?php echo url('modules/sitting_arrangements/assets/images/arrow.png') ?>"
                                       alt="a">
                                    </label>
                                    </span>
                                 </p>
                              </div>
                              <div class="row justify-content-center my-2">
                                 <div class="col-12">
                                    <div class="seats_btn">
                                       <button class="btn btn-danger active w-100" id="editTableSeatMenu2DoneButton">
                                       Done
                                       </button>
                                    </div>
                                 </div>
                              </div>
                              <hr />
                              <div class="row justify-content-center my-2"
                                 id="editTableSeatMenu2DisplayDeleteButton" style="display: block;">
                                 <div class="col-12">
                                    <div class="seats_btn text-center">
                                       <button class="btn btn-danger delete_unit" id="editTableSeatMenu2DeleteButton"><i class="fa fa-trash"></i> Delete Unit</button>
                                    </div>
                                 </div>
                              </div>
                              <div class="row justify-content-center my-2"
                                 id="editTableSeatMenu2DisplayRestoreButton" style="display: none;">
                                 <div class="col-12">
                                    <div class="seats_btn text-center">
                                       <button class="btn btn-danger delete_unit" id="editTableSeatMenu2RestoreButton">Restore Unit</button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- Unit Information End -->
                     <!-- Sports Venues -->
                     <div id="sportsVenuesMenu" style="display: none;">
                        <div class="selection_content float-left">
                           <p class="text-center">Choose a Sport Venue</p>
                           <p class="row objectIcon sit pt-1 text-center">
                              <span class="col-md-6">
                                 <input type="radio" name="type8" id="americanFootball" checked class="input-hidden">
                                 <label for="americanFootball" id="americanFootballLabel" class="px-2">
                                 <span class="icons-object-color">
                                    <i class="flaticon-rugby d-block"></i>
                                    <span>American Football</span>
                                 </span>

                                 </label>
                              </span>
                              <span class="col-md-6">
                                 <input type="radio" name="type8" id="baseball" class="input-hidden">
                                 <label for="baseball" id="baseballLabel" class="px-2">
                                    <span class="icons-object-color">
                                       <i class="flaticon-baseball d-block"></i>
                                       <span>Baseball</span>
                                    </span>
                                 </label>
                              </span>
                              <span class="col-md-6">
                                 <input type="radio" name="type8" id="basketball" class="input-hidden">
                                 <label for="basketball" id="basketballLabel" class="px-2">
                                    <span class="icons-object-color">
                                       <i class="flaticon-basketball-ball-variant d-block"></i>
                                       <span>Basketball</span>
                                    </span>
                                 </label>
                              </span>
                              <span class="col-md-6">
                                 <input type="radio" name="type8" id="boxing" class="input-hidden">
                                 <label for="boxing" id="boxingLabel" class="px-2">
                                    <span class="icons-object-color">
                                       <i class="flaticon-boxing d-block"></i>
                                       <span>Boxing</span>
                                    </span>
                                 </label>
                              </span>
                              <span class="col-md-6">
                                 <input type="radio" name="type8" id="cricket" class="input-hidden">
                                 <label for="cricket" id="cricketLabel" class="px-2">
                                    <span class="icons-object-color">
                                       <i class="flaticon-cricket d-block"></i>
                                       <span>Cricket</span>
                                    </span>
                                 </label>
                              </span>
                              <span class="col-md-6">
                                 <input type="radio" name="type8" id="hockey" class="input-hidden">
                                 <label for="hockey" id="hockeyLabel" class="px-2">
                                    <span class="icons-object-color">
                                       <i class="flaticon-hockey d-block"></i>
                                       <span>Hockey</span>
                                    </span>
                                 </label>
                              </span>
                              <span class="col-md-6">
                                 <input type="radio" name="type8" id="lacrosse" class="input-hidden">
                                 <label for="lacrosse" id="lacrosseLabel" class="px-2">
                                    <span class="icons-object-color">
                                       <i class="flaticon-lacrosse d-block"></i>
                                       <span>Lacrosse</span>
                                    </span>
                                 </label>
                              </span>
                              <span class="col-md-6">
                                 <input type="radio" name="type8" id="rugby" class="input-hidden">
                                 <label for="rugby" id="rugbyLabel" class="px-2">
                                 <span class="icons-object-color">
                                    <i class="flaticon-rugby-1 d-block"></i>
                                    <span>Rugby</span>
                                 </span>
                                 </label>
                              </span>
                              <span class="col-md-6">
                                 <input type="radio" name="type8" id="soccer" class="input-hidden">
                                 <label for="soccer" id="soccerLabel" class="px-2">
                                    <span class="icons-object-color">
                                       <i class="flaticon-football-ball d-block"></i>
                                       <span>Soccer</span>
                                    </span>
                                 </label>
                              </span>
                              <span class="col-md-6">
                                 <input type="radio" name="type8" id="swimmingPool" class="input-hidden">
                                 <label for="swimmingPool" id="swimmingPoolLabel" class="px-2">
                                    <span class="icons-object-color">
                                       <i class="flaticon-swimming-pool d-block"></i>
                                       <span>Swimming Pool</span>
                                    </span>
                                 </label>
                              </span>
                              <span class="col-md-6">
                                 <input type="radio" name="type8" id="tennis" class="input-hidden">
                                 <label for="tennis" id="tennisLabel" class="px-2">
                                    <span class="icons-object-color">
                                       <i class="flaticon-tennis-ball d-block"></i>
                                       <span>Tennis</span>
                                    </span>
                                 </label>
                              </span>
                              <span class="col-md-6">
                                 <input type="radio" name="type8" id="trackAndField" class="input-hidden">
                                 <label for="trackAndField" id="trackAndFieldLabel" class="px-2">
                                    <span class="icons-object-color">
                                       <i class="flaticon-sneakers d-block"></i>
                                       <span>Volleyball</span>
                                    </span>
                                 </label>
                              </span>
                              <span class="col-md-6">
                                 <input type="radio" name="type8" id="volleyball" class="input-hidden">
                                 <label for="volleyball" id="volleyballLabel" class="px-2">
                                    <span class="icons-object-color">
                                       <i class="flaticon-volleyball-ball d-block"></i>
                                       <span>Volleyball</span>
                                    </span>
                                 </label>
                              </span>
                              <div class="col-md-12 row justify-content-center my-2">
                                 <div class="col-5">
                                    <div class="seats_btn">
                                       <button class="btn btn-danger"
                                          onclick="Helper__HideOrShowCapacityMenu({elementID:
                                          'menu3'})">Cancel</button>
                                    </div>
                                 </div>
                                 <div class="col-5">
                                    <div class="seats_btn">
                                       <button class="btn btn-danger active" id="createSportsVenue">
                                       Create
                                       </button>
                                    </div>
                                 </div>
                              </div>
                           </p>
                        </div>
                     </div>
                     <!-- Sports Venues End -->
                     <!-- Selected textblock menu -->
                     <div class="canvas_set_main" id="selected_sports_venue" style="display: none;">
                        <p class="capacity">Selected <span>1</span></p>
                        <div class="tab-content">
                            <div class="tab-pane active">
                              <div class="selection_content mt-0 float-none">
                                 <div class="card mb-3 border-0">
                                    <div class="row no-gutters">
                                       <div class="col-auto">
                                             <i class="flaticon-microphone" id="selectedSportsVenueImg"></i>
                                       </div>
                                       <div class="col">
                                          <div class="card-body p-0 pl-2">
                                             <p class="card-title mb-0">2</p>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                  <div class="container1">
                                      <div class="row d-flex">
                                         <div class="col-auto align-self-center">
                                            <p class="m-0">Rotate</p>
                                         </div>
                                         <div class="col align-self-center">
                                            <input type="range" id="sportsVenueRotateRangeSlider"
                                               min="0" max="360" value="0" />
                                            <output data-output></output>
                                         </div>
                                      </div>
                                   </div>
                              </div>
                              <div class="setting_tabs siting_edit_btn">
                                  <div class="row no-gutters">
                                    <div class="col">
                                        <a href="#Selection" id="sportsVenueDuplicate">
                                        <img src="<?php echo url('modules/sitting_arrangements/assets/images/plus.png') ?>"
                                          class="mx-auto" alt="">
                                        Duplicate
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="#Selection" id="sportsVenueDelete">
                                        <img src="<?php echo url('modules/sitting_arrangements/assets/images/del.png') ?>"
                                          class="mx-auto" alt="">
                                        Delete
                                        </a>
                                    </div>
                                  </div>
                              </div>
                            </div>
                            <div class="row no-gutters justify-content-center my-2">
                              <div class="col-5">
                                  <div class="seats_btn">
                                    <button class="btn btn-danger bg-none closed" data-target="#edit" id="editTextBlockCancel">Cancel</button>
                                  </div>
                              </div>
                            </div>
                        </div>
                     <!-- inner tabs end -->
                     </div>
                     <!-- Selected textblock menu end -->
                  </div>
                  <div class="canvas_setting" id="tiers" style="display: none;">
                     <div class="canvas_set_main" id="selected">
                     <p class="capacity" id="tiersAssignedBody">Assigned
                           <span>
                              <span id="tiersAssignedTotal">0</span>
                              <span>&nbsp/&nbsp</span>
                              <span id="tiersAssignedValue">0</span>
                           </span>
                        </p>
                        <p class="capacity d-none" id="tiersSelectedBody">Selected
                           <span>
                              <span id="tiersSelectedValue">0</span>
                           </span>
                        </p>
                        <div class="tab-content" id="tiersMultipleColorPicker">
                           <!-- Multiple color picker will generate here. -->
                           <div class="row no-gutters justify-content-center">
                              <div class="col-12">
                                 <div class="seats_btn">
                                    <button class="btn btn-danger bg-none add_tier" id="tiersAddButton"
                                       data-target="#edit">Add Tier</button>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- inner tabs end -->
                     </div>
                  </div>
                  <div class="canvas_setting" id="holds" style="display: none;">
                     <div class="canvas_set_main" id="selected">
                        <!-- When other than generalAdmission selected, below section execute. -->
                        <p class="capacity d-none" id="holdsSelectedBody">Selected
                           <span>
                              <span id="holdsSelectedValue">0</span>
                           </span>
                        </p>
                        <p class="capacity d-block" id="holdsAssignedBody">Held
                           <span>
                              <span>%)</span>
                              <span id="holdsAssignedTotal">0</span>
                              <span>&nbsp(</span>
                              <span id="holdsAssignedValue">0</span>
                           </span>
                        </p>
                        <!-- End -->
                        <!-- When generalAdmission selected, below section execute. -->
                        <p class="capacity d-none" id="holdsGASectionBody">Section
                           <span>
                              <span id="holdsGASection">0</span>
                           </span>
                        </p>
                        <p class="capacity d-none" id="holdsGAAvailableBody">Available
                           <span>
                              <span id="holdsGAAvailableTotal">0</span>
                              <span>&nbsp/&nbsp</span>
                              <span id="holdsGAAvailableValue">0</span>
                           </span>
                        </p>
                        <p class="capacity d-none" id="holdsGAAssignedBody">Held
                           <span>
                              <span>%)</span>
                              <span id="holdsGAAssignedTotal">0</span>
                              <span>&nbsp(</span>
                              <span id="holdsGAAssignedValue">0</span>
                           </span>
                        </p>
                        <!-- End -->
                        <div class="tab-content d-block" id="holdsMultipleColorPicker">
                           <div class="row no-gutters justify-content-center">
                              <div class="col-12">
                                 <div class="seats_btn">
                                    <button class="btn btn-danger bg-none add_tier" id="holdsAddButton"
                                       data-target="#edit">Add Hold</button>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- Holds GeneralAdmission menu Start -->
                        <div class="selection_content d-none" id="holdsGeneralAdmission">
                           <div class="GeneralAdmission siting_options" id="holdsGeneralAdmissionChildList">
                           </div>
                           <div class="row">
                           </div>
                        </div>
                        <!-- Holds GeneralAdmission menu End -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- <p class="holdsCapacity sit">
            <span>Hold</span>
            <span>
               <input type="text" id="holdsValue" />
            </span>
         </p> -->
         <!-- <div class="tab-pane fade" id="holds">Data Here</div> -->
      </div>
      <!-- Modal Start -->
      <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-header cust_head">
                  <div class="text-center">
                  <h5 class="modal-title text-center" id="exampleModalLongTitle">New Access Code</h5>
               </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body cust_body">


                  <div class="container pb-5">
                        <div id="accordion" class="accordion">
                           <div class="card mb-0 border-bottom ">
                              <div class="card-header collapsed" data-toggle="collapse" href="#collapseOne">
                                    <a class="card-title">
                                       New Access Code
                                    </a>
                              </div>
                              <div id="collapseOne" class="card-body collapse cust_card" data-parent="#accordion" >
                                    <div class="row">
                                 <div class="col-12 p-0">
                                    <div class="form-group">
                                       <label class="cust_lable" for="usr">Access Code</label>
                                       <input type="text" class="form-control" id="accessCode" placeholder="Enter the code to be used">
                                       <aside class="cust_aside">A customer can enter this code or access the code via a custom URL</aside>
                                    </div>
                                    </div>
                                 </div>
                                    <!-- <div class="col-12 p-0"> -->
                                    <div class="row">

                                          <div class="col-sm">
                                          <div class="form-group">
                                                <label class="cust_lable" for="usr">Discount Amount (Optional)</label>
                                                <input type="tel" class="form-control" id="discountAmount" placeholder="$">
                                             </div>
                                          </div>
                                          <div class="col-auto align-self-center mt-2 p-0">
                                          <p class="mb-0">OR</p>
                                          </div>
                                          <div class="col-sm">
                                          <div class="form-group">
                                                <label class="cust_lable" for="usr">&nbsp</label>
                                                <input type="phone" class="form-control" id="percentage" placeholder="%">
                                             </div>
                                       </div>
                                       <p class="cust_aside pl-4">Apply a discount to the ticket assigned to inventory in this hold</p>
                                    </div>

                                    <!-- </div> -->
                                    <div class="row">
                                    <div class="col-12 p-0">
                                       <div class="form-group">
                                          <label class="cust_lable" for="usr">Ticket Limit (Optional)</label>
                                          <input type="tel" class="form-control" id="ticketLimit">
                                          <aside class="cust_aside">Enter the total number of tickets that can be purchased with this code.
                                             If left blank, code can be used while held inventory is available.</aside>
                                          </div>
                                       </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-md-6 col-sm-12">
                                             <div class="form-group" action="/action_page.php">
                                                <label class="cust_lable"  for="usr">Expiration Date</label>
                                                <input  class="form-control" type="datetime-local" name="bdaytime">
                                                </div>

                                          </div>
                                          <div class="col-md-6 col-sm-12">
                                             <div class="form-group" action="/action_page.php">
                                                <label class="cust_lable" for="usr">Expiration Time</label>
                                                <input class="form-control" type="time" name="usr_time">
                                                </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-12 p-0">
                                             <p class="p_radi">Choose which tickets can be unlocked with this code.</p>
                                          <div class="row">

                                             <div class="col-sm-12">
                                                   <div class="form-check">
                                                      <label class="cust_radio" class="form-check-label">
                                                         <input type="radio" class="form-check-input" name="availableTicketsRadio">All available tickets
                                                      </label>
                                                   </div>
                                             </div>
                                             <div class="col-sm-12">
                                                   <div class="form-check">
                                                      <label class="cust_radio" class="form-check-label">
                                                         <input type="radio" class="form-check-input" name="certainTicketsRadio">Only certain tickets
                                                      </label>
                                                   </div>
                                             </div>
                                             <div class="col-12 ">
                                             <div class="form-group">
                                                   <label class="cust_lable" for="usr">Selected (0)</label>
                                                   <input type="tel" class="form-control" id="ticketNameSearch" placeholder="Search for a ticket name">
                                                </div>
                                             </div>
                                             <!-- tabla start -->

                                             <div class="table-responsive">
                                                <table class="table">
                                                <thead class=" cust_t_head">
                                                   <tr>
                                                      <th>
                                                         <div class="custom-control custom-checkbox ">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck" name="example1">
                                                            <label class="custom-control-label" for="customCheck"></label>
                                                         </div>
                                                      </th>
                                                      <th>Firstname</th>
                                                      <th>Lastname</th>

                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   <tr>
                                                      <td>1</td>
                                                      <td>Anna</td>
                                                      <td>Pitt</td>
                                                   </tr>
                                                </tbody>
                                                </table>
                                                </div>

                                             <!-- table End -->
                                       </div>
                                          </div>
                                       </div>
                           </div>
                        </div>
                  </div>
               </div>
               <div class="modal-footer cust_modl_foot">
                  <button type="button" class="btn btn-primary save_btn" data-dismiss="modal" aria-label="Close">Close</button>
               </div>
            </div>
            </div>
         </div>
      </div>
      <!-- Modal End -->
      <script src="<?php echo url('modules/sitting_arrangements/assets/js/jquery.js') ?>" type="text/javascript"></script>
      <script src="<?php echo url('modules/sitting_arrangements/assets/js/popper.js') ?>" type="text/javascript"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
      <script src="<?php echo url('modules/sitting_arrangements/assets/js/bootstrap.min.js') ?>" type="text/javascript"></script>
      <script src="https://cdn.jsdelivr.net/npm/rangeslider-pure@0.4.4/dist/range-slider.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
      <!-- <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
      <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" type="text/javascript"></script> -->

      <script>
      	jQuery(document).ready(function() {
      		$(function() {
      			$('[data-toggle="tooltip"]').tooltip()
      		})
      		jQuery(".GeneralAdmission").hide();
      		jQuery(".selectSeat").on('change', function() {

      			var selectVal = "." + this.value;
      			if (selectVal == ".GeneralAdmission") {
      				$(selectVal).show();
      				$(".AssignedSeating").hide();
      			} else {
      				$(selectVal).show();
      				$(".GeneralAdmission").hide();
      			}
      		});
         });
      </script>
      <script>
      	// $('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
      	//     if (!$(this).next().hasClass('show')) {
      	//         $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
      	//     }
      	//     var $subMenu = $(this).next(".dropdown-menu");
      	//     $subMenu.toggleClass('show');
      	//     $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
      	//         $('.dropdown-submenu .show').removeClass("show");
      	//     });
      	//     return false;
      	// });
      </script>
      <!-- <script>
      	$(document).ready(function() {

      		$(document).on("click", ".main_tabs .nav-link", function() {
      			var a = $(this).attr('href');
      			$('.canvas_setting').hide();
               $(a).show();
               Handler__SectionStarter({ activePortion: a });
      		});
         });
      </script>  -->
      <script>
      	$(document).ready(function() {
      		var placeholderLen = $('input[type="text"]').attr('placeholder').length;
      		// keep some default lenght
      		placeholderLen = Math.max(5, placeholderLen);
      		$('input[type="text"]').attr('size', placeholderLen);

      		$('input[type="text"]').keydown(function() {
      			var size = $(this).val().length;
      			$(this).attr('size', Math.max(placeholderLen, size));
      		});
         });
      </script>
      <script>
         function displayToast({ message = 'Completed...' }) {
            var x = document.getElementById("snackbar");
            x.className = "show";
            x.textContent = message;
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 2000);
         }
      </script>
        <!-- <script>
         $(document).ready(function() {
         $('#example').DataTable({
             "paging":false,
             "searching":false,
             "info": false,
         });
     });
         </script> -->
      <!-- <script>
         $('#accessCodeTimePicker').timepicker({
             uiLibrary: 'bootstrap4'
         });
     </script>
       <script>
         $('#accessCodeDatePicker').datepicker({
             uiLibrary: 'bootstrap4'
         });
     </script> -->
      <!-- color picker -->

    <script>
        @if($option_data)
            window.advancedOptions = <?php echo $option_data; ?>;
            console.log('window.advancedOptions', window.advancedOptions);
        @endif
        @if($sitting_arrangement)
            var sitting_arrangement = <?php echo $sitting_arrangement; ?>;
            window.dataDiagram = sitting_arrangement;
        @endif
    </script>
   </body>
</html>
