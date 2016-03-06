<?php
session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/eventsController.class.php");

$q = new eventsController ();
$courseRes = $q->returnCourses (false);

if ( $courseRes['res'] ) {
	foreach ( $courseRes['res'] as $courses ) {
		if ($courses['name']) {
            
            if ($courses['name'] == $_POST['course']) {
                $selected = "selected='selected'";   
            } else {
                $selected = "";   
            }
            
			$optionArgs[] = "<option value='".$courses['name']."' $selected>".$courses['label'].", ".$courses['location']."</option>";
		}
	}
}

if ($optionArgs) {
	$courseselect = "<select id='course_select' name='course_select' class='form-control'>".implode ( $optionArgs )."</select>";
}

if ($_POST['course']) {
    require_once("../../coreClasses/coursestats.class.php");
    require_once("../../coreClasses/statsController.class.php");
    require_once("../../coreClasses/dateFormat.class.php");
    
    $df = new dateFormat();

    if ($_POST['startdate']) {
        $startdate = $df->formatDateToDatabaseOther($_POST['startdate']);   
    }

    if ($_POST['enddate']) {
        $enddate = $df->formatDateToDatabaseOther($_POST['enddate']);   
    }
    
    // setting up properties
    $course = new coursestats();
    $course->setUsername($_SESSION['username']);
    $course->setStartDate($startdate);
    $course->setEndDate($enddate);
    $course->setCourse($_POST['course']);
    $course->setDbObj(new statsController());
    $course->setDateObj($df);
    
    //do the dirty work
    $overall = $course->getStatsOverall();
    
    $display=<<<EOHTML
<div class='row'>
  <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> $courseName</h3>
        </div>
        <div class="box-body">
            $overall
      </div><!-- /.box -->
    </div>
  </div>
</div> 
EOHTML;

}

$defaultStartDate = $_POST['startdate'];
$defaultEndDate = $_POST['enddate'];

?>
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pie-chart"></i> Course Summary</h3>
        </div>
        <div class="box-body">
          <form class="form-horizontal" id='coursesummary' method='POST'>
            <div class="form-group">
                <label for="selectCourse" class="col-sm-2 control-label">Select Course:</label>
                <div class="col-sm-5">
                    <?php echo $courseselect; ?>
                </div>
            </div>	
            
            <div class="form-group">
                    <label for="startDate" class="col-sm-2 control-label">Start Date:</label>
                    <div class="col-sm-5">
                        <input type='input' class='form-control' id='startDate' name='startDate' value='<?php echo $defaultStartDate; ?>' placeholder='Enter Start Date' data-date-format="dd/mm/yyyy" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="endDate" class="col-sm-2 control-label">End Date:</label>
                    <div class="col-sm-5">
                        <input type='input' class='form-control' id='endDate' name='endDate' value='<?php echo $defaultEndDate; ?>' placeholder='Enter End Date' data-date-format="dd/mm/yyyy" />
                    </div>
                </div>
                

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button id="selectCourseForCourseSummaryButton" type="button" class="btn btn-primary">Select Course</button>
                </div>
            </div>
          </form>
      </div><!-- /.box -->
    </div>
  </div>
</div>

<?php
echo $display;
?>

