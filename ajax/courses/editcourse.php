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

$addCourse = new courseCreator();
$addForm = $addCourse->generateAddCourseBasicInfo();

?>
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-tree"></i> Edit the details of an existing course</h3>
        </div>
          <div class="panel-body" id="editCourseDiv">				
				<form class="form-horizontal" id='newTeeSet' method='POST'>					
					<div class="form-group">
						<label for="selectCourse" class="col-sm-2 control-label">Select Course:</label>
						<div class="col-sm-5">
							<?php echo $course; ?>
						</div>
					</div>			
					
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button id="selectCourseForEditButton" type="button" class="btn btn-primary">Select Course</button>
						</div>
					</div>
					
					<div id="editCourseTeeSelect">
					
					</div>
				</form>
			</div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
</div>