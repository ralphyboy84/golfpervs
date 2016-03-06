<?php
session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/eventsController.class.php");
require_once("../../coreClasses/roundsController.class.php");

if ($_POST['newround']) {
    $rc = new roundsController();
    $args['roundid'] = $_POST['roundid'];
    $args['username'] = $_SESSION['username'];
    $x = $rc->deleteTempRoundInfo($args);
    $x = $rc->deleteTempScoreInfo($args);
} 

$rc = new roundsController();
$args['username'] = $_SESSION['username'];
$res = $rc->checkForTempRound($args);

if ($res['res']) {
    $roundid = $res['res'][0]['roundid'];
    
    $temp = $rc->returnTempRoundDetails(array('roundid' => $roundid));
    
    if ($temp['res']) {
        $hole = count($temp['res']) + 1;
    } else {
        $hole = 1;   
    }    
    
    if ($_SESSION['username'] == "ralph") {
        $reminder = "<br /><br />More info needed here.......";   
    }
    
    echo<<<EOHTML
    <div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-bullseye"></i> Existing Round</h3>
        </div>
        <div class="box-body" id="addRoundDiv">
            <div class="alert alert-warning alert-dismissible">
                <h4><i class="icon fa fa-info"></i> Warning!</h4>
                You have an unfinished round within Golf Pervs!
                
                $reminder
            </div>
            <div class="form-group">
                <div class="col-lg-2 col-xs-6">
                    <button id="continueRoundButton" type="button" class="btn btn-primary">Continue Round</button>
                </div>
                <div class="col-lg-2 col-xs-6">
                    <button id="newRoundButton" type="button" class="btn btn-primary">New Round</button>
                </div>
            </div>
            <input type='hidden' id='roundid' name='roundid' value='$roundid' />
            <input type='hidden' id='hole' name='hole' value='$hole' />
        </div><!-- /.box -->
    </div>
  </div>
</div>
EOHTML;
die();
}

$q = new eventsController ();
$courseRes = $q->returnCourses (false);

if ( $courseRes['res'] ) {
    $optionArgs[] = "<option value=''>Select course....</option>";
	foreach ( $courseRes['res'] as $courses ) {
		if ($courses['name']) {
			$optionArgs[] = "<option value='".$courses['name']."'>".$courses['label'].", ".$courses['location']."</option>";
		}
	}
}

if ($optionArgs) {
	$course = "<select id='add_round_course_select' name='add_round_course_select' class='form-control'>".implode ( $optionArgs )."</select>";
}

?>

<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-bullseye"></i> Add Round</h3>
        </div>
        <div class="box-body" id="addRoundDiv">
            <form class="form-horizontal" id='addRound' method='POST'>					
                <div class="form-group" id="add_round_course_selectdiv">
                    <label for="selectCourse" class="col-sm-2 control-label">Select Course:</label>
                    <div class="col-sm-5">
                        <?php echo $course; ?>
                    </div>
                </div>
                
                <div class="form-group" id="add_round_tee_selectdiv">
                    <label for="selectCourse" class="col-sm-2 control-label">Select Tee:</label>
                    <div class="col-sm-5">
                        <select id='add_round_tee_select' name='add_round_tee_select' class='form-control'>
                            <option value=''>Select tee....</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group" id="datediv">
                    <label for="selectCourse" class="col-sm-2 control-label">Select Date:</label>
                    <div class="col-sm-5">
                        <input type='input' class='form-control' id='date' name='date' value='<?php echo date("d/m/Y"); ?>' placeholder='Enter Date' data-date-format="dd/mm/yyyy" />
                    </div>
                </div>
                
                <div class="form-group" id="compdiv">
                    <label for="selectCourse" class="col-sm-2 control-label">Enter Competition:</label>
                    <div class="col-sm-5">
                        <input type='input' class='form-control' id='comp' name='comp' value='' placeholder='Enter Competition' data-date-format="dd/mm/yyyy" />
                    </div>
                </div>
                
                <div class="form-group" id="cssdiv">
                    <label for="selectCourse" class="col-sm-2 control-label">Enter CSS:</label>
                    <div class="col-sm-5">
                        <input type='input' class='form-control' id='css' name='css' value='' placeholder='Enter CSS' data-date-format="dd/mm/yyyy" />
                    </div>
                </div>
                
                <div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button id="addRoundButton" type="button" class="btn btn-primary">Add Round</button>
						</div>
					</div>
                <div id='showErrorsDiv'>
                    <div class="alert alert-danger alert-dismissible">
                        <h4><i class="icon fa fa-info"></i> Alert!</h4>
                        The fields above in red have not been filled in!
                    </div>
                </div>
            </form>
      </div><!-- /.box -->
    </div>
  </div>
</div>