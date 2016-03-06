<?php
session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/eventsController.class.php");
require_once("../../coreClasses/courseCreator.class.php");

$q = new eventsController ();
$courseRes = $q->returnCourses (false);

if ( $courseRes['res'] ) {
	foreach ( $courseRes['res'] as $courses ) {
		if ($courses['name']) {
			$optionArgs[] = "<option value='".$courses['name']."'>".$courses['label'].", ".$courses['location']."</option>";
		}
	}
}

if ($optionArgs) {
	$course = "<select id='course_select' name='course_select' class='form-control'>".implode ( $optionArgs )."</select>";
}

?>
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-tree"></i> View Course</h3>
        </div>
        <div class="box-body">
            <form class="form-horizontal" role="form" id="addCourseForm">
                <div id="courseNameForm" class="form-group">
                    <label for="courseName" class="col-sm-2 control-label">Select Course:</label>
                    <div class="col-sm-5">
                        <?php echo $course ?>
                    </div>
                </div> 

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button id="viewCourseButton" type="button" class="btn btn-primary">View Course</button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10" id='viewCourseInfo'>

                    </div>
                </div>
            </form>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
</div>