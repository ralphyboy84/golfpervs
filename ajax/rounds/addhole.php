<?php

session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/course.class.php");
require_once("../../coreClasses/roundsController.class.php");
require_once("../../coreClasses/dateFormat.class.php");

$buttonLabel = "Add";

//if we are updating set the update flag
if ($_POST['update']) {
    $update = $_POST['update'];   
    $buttonLabel = "Edit";
}

if ($_POST['edit']) {
    $edit = $_POST['edit'];
    $buttonLabel = "Edit";
}

//generate the label for course, tee and date
if ($_POST['add_round_course_select'] && $_POST['add_round_tee_select']) {
    $course = new course();
    $course->setCourseID($_POST['add_round_course_select']);
    $course->setTee($_POST['add_round_tee_select']);
    
    $df = new dateFormat();
    $dateDB = $df->formatDateToDatabaseOther($_POST['date']);
    $date = $_POST['date'];   
    
    $coursestring = $_POST['add_round_course_select'];
    $teestring = $_POST['add_round_tee_select'];
} else if (!$_POST['update'] && $_POST['edit']) {
    $rc = new roundsController();
    $info = $rc->returnSimpleRoundDetails(array('roundid' => $_POST['roundid'], 'username' => $_SESSION['username']));
    $course = new course();
    $course->setCourseID($info['res'][0]['course']);
    $course->setTee($info['res'][0]['tees']);
    
    $df = new dateFormat();
    $date = $df->formatDateFromDatabaseOther($info['res'][0]['date']);
    
    $coursestring = $info['res'][0]['course'];
    $teestring = $info['res'][0]['tees'];
} else {
    $rc = new roundsController();
    $info = $rc->checkForTempRoundByRoundId(array('roundid' => $_POST['roundid']));
    $course = new course();
    $course->setCourseID($info['res'][0]['course']);
    $course->setTee($info['res'][0]['tees']);
    
    $df = new dateFormat();
    $date = $df->formatDateFromDatabaseOther($info['res'][0]['date']);
    
    $coursestring = $info['res'][0]['course'];
    $teestring = $info['res'][0]['tees'];
}

$label = $course->getCourseTeeString();

//if the hole hasn't been set then we know this is the first hole
if (!$_POST['hole'] && !$_POST['update'] && !$_POST['edit']) {
    $hole = 1;
    
    $rc = new roundsController();
    $args = array (
        'username' => $_SESSION['username'],
        'course' => $_POST['add_round_course_select'],
        'tees' => $_POST['add_round_tee_select'],
        'date' => $dateDB,
        'competition' => $_POST['comp'],
        'css' => $_POST['css']
    );
    $info = $rc->insertTempRound($args);
    $roundid = $info['insertid'];
} else if (!$_POST['continue']) { //we are carrying on a round as normal
    
    $rc = new roundsController();
    
    if (!$_POST['update'] && !$_POST['edit']) {
        $insertArgs = $_POST;
        unset($insertArgs['update']);
        unset($insertArgs['edit']);
        unset($insertArgs['continue']);
        unset($insertArgs['defaultPuttsForSwipe']);
        unset($insertArgs['defaultScoreForSwipe']);
        unset($insertArgs['defaultLengthForSwipe']);
        $x = $rc->insertTempHole($insertArgs);
        
        if ($x['error'] && $_SESSION['username'] == "ralph") {
            echo $x['error'];
        }
    } else if ($_POST['update']) {
        $x = $rc->updateTempScoreInfo($_POST);
    } else if ($_POST['edit'] && $_POST['hole']) {
        $x = $rc->updateScoreInfo($_POST);  
    }
    
    $hole = $_POST['hole'];
    $hole++;
    
    $roundid = $_POST['roundid'];
} else if ($_POST['continue']) { //we are continuing an existing round
    $roundid = $_POST['roundid'];   
    $hole = $_POST['hole'];
}

//if we are on hole 18 (or 19 in some instances) then display the round scorecard
if ($_POST['hole'] == 18 || $hole == 19) {
    require_once("../../coreClasses/roundInfo.class.php");
    
    $roundInfo = new roundInfo();
    $roundInfo->setRoundId($_POST['roundid']);
    $roundInfo->setDateObj(new dateFormat());
    $scorecard = $roundInfo->buildTempScoreCard();
    
    echo<<<EOHTML
    <div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-map-o"></i> Scorecard</h3>
        </div>
        <div class="box-body">
            $scorecard
            
            <div class="form-group">
                <div class="col-lg-2 col-xs-6">
                    <button id="completeRoundButton" type="button" class="btn btn-primary" data-roundid='$roundid'>Complete Round</button>
                </div>
                <div class="col-lg-2 col-xs-6">
                    <button id="editRoundButton" type="button" class="btn btn-primary">Edit Round</button>
                </div>
            </div>
            <input type='hidden' id='roundid' name='roundid' value='$roundid' />
      </div><!-- /.box -->
    </div>
  </div>
</div>
EOHTML;
    
    die();    
}


//we need to get the par of the hole to hide fairway stuff for 3 pars...
$courseArgs = array (
    'course' => $coursestring,
    'tee' => $teestring,
    'hole' => $hole
);
$cc = new courseController();
$holeInfo = $cc->returnCourseTeeHole($courseArgs);

$defaultPar = $holeInfo['res'][0]['par'];

if (!$update && !$edit) {
    $fHit = "activeF";
    $fl = "inActiveF";
    $fr = "inActiveF";
    
    $gHit = "activeG";
    $gl = "inActiveG";
    $glo = "inActiveG";
    $gr = "inActiveG";
    $gs = "inActiveG";
    $na = "inActiveG";
    
    $sgclass = "hideSG";
    
    $udy = "inActiveS";
    $udn = "inActiveS";
    $ssn = "inActiveS";
    $ssy = "inActiveS";
    
    $fairway = 2;
    $green = 2;
} else {
    
    $holeArgs = array (
        'roundid' => $roundid,
        'hole' => $hole
    );
    
    $rc = new roundsController();
    
    if ($update) {
        $holeInfo = $rc->getTempHoleForRound($holeArgs);
    } else if ($edit) {
        $holeInfo = $rc->getHoleForRound($holeArgs);  
    }
    
    $score = $holeInfo['res'][0]['score'];
    $putts = $holeInfo['res'][0]['putts'];
    $puttholedlength = $holeInfo['res'][0]['puttholedlength'];
    $fairway = $holeInfo['res'][0]['fairway'];
    $fmissed = $holeInfo['res'][0]['fmissed'];
    $green = $holeInfo['res'][0]['green'];
    $gmissed = $holeInfo['res'][0]['gmissed'];
    $upndown = $holeInfo['res'][0]['upndown'];
    $sandsave = $holeInfo['res'][0]['sandsave'];
    
    if ($holeInfo['res'][0]['fairway'] == "2") {
        $fHit = "activeF";
        $fl = "inActiveF";
        $fr = "inActiveF";
    } else if ($holeInfo['res'][0]['fairway'] == "1") {
        $fHit = "inActiveF";
        
        if ($holeInfo['res'][0]['fmissed'] == "1") {
            $fl = "activeF";
            $fr = "inActiveF";
        } else if ($holeInfo['res'][0]['fmissed'] == "2") {
            $fl = "inActiveF";
            $fr = "activeF";
        }
    }
    
    if ($holeInfo['res'][0]['green'] == "2") {
        $gHit = "activeG";
        $gl = "inActiveG";
        $gr = "inActiveG";
        $glo = "inActiveG";
        $gs = "inActiveG";
        $sgclass = "hideSG";
        $udy = "inActiveS";
        $udn = "inActiveS";
        $ssn = "inActiveS";
        $ssy = "inActiveS";
    } else if ($holeInfo['res'][0]['green'] == "1") {
        $gHit = "inActiveG";
        $sgclass = "showSG";
        
        if ($holeInfo['res'][0]['gmissed'] == "1") {
            $gl = "activeG";
            $gr = "inActiveG";
            $glo = "inActiveG";
            $gs = "inActiveG";
            $na = "inActiveG";
        } else if ($holeInfo['res'][0]['gmissed'] == "2") {
            $gl = "inActiveG";
            $gr = "activeG";
            $glo = "inActiveG";
            $gs = "inActiveG";
            $na = "inActiveG";
        } else if ($holeInfo['res'][0]['gmissed'] == "3") {
            $gl = "inActiveG";
            $gr = "inActiveG";
            $glo = "activeG";
            $gs = "inActiveG";
            $na = "inActiveG";
        } else if ($holeInfo['res'][0]['gmissed'] == "4") {
            $gl = "inActiveG";
            $gr = "inActiveG";
            $glo = "inActiveG";
            $gs = "activeG";
            $na = "inActiveG";
        } else if ($holeInfo['res'][0]['gmissed'] == "5") {
            $gl = "inActiveG";
            $gr = "inActiveG";
            $glo = "inActiveG";
            $gs = "inActiveG";
            $na = "activeG";
        }
        
        if ($holeInfo['res'][0]['upndown'] == 2) {
            $udy = "activeS";
            $udn = "inActiveS";
            $ssn = "inActiveS";
            $ssy = "inActiveS";
        } else if ($holeInfo['res'][0]['upndown'] == 1) {
            $udy = "inActiveS";
            $udn = "activeS";
            $ssn = "inActiveS";
            $ssy = "inActiveS";
        } else if ($holeInfo['res'][0]['sandsave'] == 2) {
            $udn = "inActiveS";
            $udy = "inActiveS";
            $ssy = "activeS";
            $ssn = "inActiveS";
        } else if ($holeInfo['res'][0]['sandsave'] == 1) {
            $udn = "inActiveS";
            $udy = "inActiveS";
            $ssy = "inActiveS";
            $ssn = "activeS";
        }
    }
}


if ($score) {
    $ds = $score;  
} else {
    $ds = $defaultPar; //the hole par   
    $score = $ds;
}

for ($x=1;$x<11;$x++) {
    $scoreString.= "<div class='scoreSlideDiv'>$x</div>";
    
    if ($x==$ds) {
        $liClass = "class='on'";
        $selected = "selected='selected'";
    } else {
        $liClass = "";
        $selected = "";
    }

    $liScoreString.= "<li id='$x' $liClass></li>";
    
    $scoreOptionArgs[] = "<option value='$x' $selected>$x</option>";
}

$scoreSelect = "<select id='score_select' class='form-control'>".implode($scoreOptionArgs)."</select>";

$ds--;

//putts
if ($putts) {
    $dp = $putts;   
} else {
    $dp = 2;  
    $putts = $dp;
}

for ($x=0;$x<6;$x++) {
    $puttString.= "<div class='puttSlideDiv'>$x</div>";
    
    if ($x==$dp) {
        $liClass = "class='on'";
        $selected = "selected='selected'";
    } else {
        $liClass = "";
        $selected = "";
    }

    $liPuttString.= "<li id='$x' $liClass></li>";
    $puttOptionArgs[] = "<option value='$x' $selected>$x</option>";
}

$puttSelect = "<select id='putt_select' class='form-control'>".implode($puttOptionArgs)."</select>";

//putt length holed
if ($puttholedlength) {
    $dl = $puttholedlength;   
} else {
    $dl = 2; 
    $puttholedlength = $dl;
}

$lengthArray = array (
    0,1,2,3,4,5,6,8,10,15,20,25,30,40,50,75,100
);

foreach ($lengthArray as $len) {
    $lengthString.= "<div class='puttSlideDiv'>$len foot</div>";
    
    if ($len==$dl) {
        $liClass = "class='on'";
        $selected = "selected='selected'";
    } else {
        $liClass = "";
        $selected = "";
    }

    $liLengthString.= "<li id='$len' $liClass></li>";
    $puttLengthOptionArgs[] = "<option value='$len' $selected>$len</option>";
}

$puttLengthSelect = "<select id='putt_length_select' class='form-control'>".implode($puttLengthOptionArgs)."</select>";

if ($defaultPar != 3) {
    $fairwaySelectString =<<<EOSTRING
    <div class="fgContainer">
        <div class="fgInner">
            <div class="$fl" id="fl" onclick="changeMe(this)">MISS<br>LEFT</div>
            <div class="$fHit" id="fHit" onclick="changeMe(this)">FAIRWAY<br>HIT</div>
            <div class="$fr" id="fr" onclick="changeMe(this)">MISS<br>RIGHT</div>
        </div>
    </div>

    <br /><br />
EOSTRING;
} else if ($defaultPar == 3) {
    $fairway = 0;
    $fmissed = 0;   
}

echo<<<EOHTML
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-bullseye"></i> $buttonLabel Round for $label on $date</h3>
        </div>
        <div class="box-body">
            <div>
                Hole $hole
            </div>
            
            <div class='row'>
                <div class='col-xs-12 visible-xs-block visible-sm-block'>
                    <div id='mySwipeScore' class='swipe'>
                        <div class='swipe-wrap'>
                        $scoreString
                        </div>
                    </div>
                    <br />
			         <ul id='position'>
                        $liScoreString
                     </ul>
                </div>
                
                <div class='col-xs-12 visible-md-block visible-lg-block'>
                    <div class='row'>
                        <div class='col-md-2'>Score:</div>
                        <div class='col-md-2'>$scoreSelect</div>
                    </div>
                </div>
            </div>
            
            <br /><br />
            
            $fairwaySelectString
              
            <div class="fgContainer">
                <div class="fgInner">
                    <div class="hiddenG"> &nbsp;</div>
                    <div class="$glo" id="glo" onclick="changeMe(this)">MISS<br>LONG</div>
                    <div class="hiddenG">&nbsp;</div>
                </div>
            </div>
            <div class="fgContainer">
                <div class="fgInner">
                    <div class="$gl" id="gl" onclick="changeMe(this)">MISS<br>LEFT</div>
                    <div class="$gHit" id="gHit" onclick="changeMe(this)">GREEN<br>HIT</div>
                    <div class="$gr" id="gr" onclick="changeMe(this)">MISS<br>RIGHT</div>	
                </div>
            </div>
            <div class="fgContainer">
                <div class="fgInner">
                    <div class="hiddenG">&nbsp;</div>
                    <div class="$gs" id="gs" onclick="changeMe(this)">MISS<br>SHORT</div>
                    <div class="$na" id="na" onclick="changeMe(this)">NA</div>
                </div>
            </div>
            
            <div id='shortGameDiv' class='$sgclass'>
                <br /><br />
                <div class="fgContainer">
                    <div class="fgInner">
                        <div class="$udn" id="udn" onclick="changeMe(this)">NO</div>
                        <div class="$udy" id="udy" onclick="changeMe(this)">YES</div>
                    </div>
                </div>
            </div>
            
            <div id='bunkerDiv' class='$sgclass'>
                <br />
                <div class="fgContainer">
                    <div class="fgInner">
                        <div class="$ssn" id="ssn" onclick="changeMe(this)">NO</div>
                        <div class="$ssy" id="ssy" onclick="changeMe(this)">YES</div>
                    </div>
                </div>
            </div>
            
            <div class='row'>
                <div class='col-xs-12 visible-xs-block visible-sm-block'>
                    <div id='mySwipePutts' class='swipe'>
                        <div class='swipe-wrap'>
                        $puttString
                        </div>
                    </div>
                    <br />
			         <ul id='position2'>
                        $liPuttString
                     </ul>
                </div>
                
                <div class='col-xs-12 visible-md-block visible-lg-block'>
                    <div class='row'>
                        <div class='col-md-2'>Putts:</div>
                        <div class='col-md-2'>$puttSelect</div>
                    </div>
                </div>
            </div>
            
            <div class='row'>
                <div class='col-xs-12 visible-xs-block visible-sm-block'>
                    <div id='mySwipeLength' class='swipe'>
                        <div class='swipe-wrap'>
                        $lengthString
                        </div>
                    </div>
                    <br />
			         <ul id='position3'>
                        $liLengthString
                     </ul>
                </div>
                
                <div class='col-xs-12 visible-md-block visible-lg-block'>
                    <div class='row'>
                        <div class='col-md-2'>Putt Length Holed:</div>
                        <div class='col-md-2'>$puttLengthSelect</div>
                    </div>
                </div>
            </div>
            
            <form id="holeInput"  method='POST'>
                <input type='hidden' id='roundid' name='roundid' value='$roundid' />
                <input type='hidden' id='hole' name='hole' value='$hole' />
                <input type='hidden' id='score' name='score' value='$score' />
                <input type='hidden' id='putts' name='putts' value='$putts' />
                <input type='hidden' id='puttholedlength' name='puttholedlength' value='$puttholedlength' />
                <input type='hidden' id='fairway' name='fairway' value='$fairway' />
                <input type='hidden' id='fmissed' name='fmissed' value='$fmissed' />
                <input type='hidden' id='green' name='green' value='$green' />
                <input type='hidden' id='gmissed' name='gmissed' value='$gmissed' />
                <input type='hidden' id='upndown' name='upndown' value='$upndown' />
                <input type='hidden' id='sandsave' name='sandsave' value='$sandsave' />
                <input type='hidden' id='update' name='update' value='$update' />
                <input type='hidden' id='edit' name='edit' value='$edit' />
                <input type='hidden' id='continue' name='continue' value='$continue' />
                <input type='hidden' id='defaultPuttsForSwipe' name='defaultPuttsForSwipe' value='$dp' />
                <input type='hidden' id='defaultScoreForSwipe' name='defaultScoreForSwipe' value='$ds' />
                <input type='hidden' id='defaultLengthForSwipe' name='defaultLengthForSwipe' value='$dl' />
                <div class="form-group">
                    <div class="col-xs-12">
                        <button id="addHoleButton" type="button" class="btn btn-primary">$buttonLabel Hole</button>
                    </div>
                </div>
            </form>

      </div><!-- /.box -->
    </div>
  </div>
</div>
EOHTML;

?>

          
