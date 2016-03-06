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
          <h3 class="box-title"><i class="fa fa-tree"></i> Add New Course</h3>
        </div>
        <div class="box-body" id="addCourseDiv">
            <form role="form" class="form-horizontal">
            <?php
            echo $addForm;
            ?>
            </form>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button id="addCourseButton" type="button" class="btn btn-primary">Add Course</button>
                </div>
            </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
</div>

<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
          <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-tree"></i> Add New Tee Set to Existing Course</h3>
        </div>
        <div class="box-body" id="addCourseDivNewTee">
            <form class="form-horizontal" id='newTeeSet' method='POST'>					
					<div class="form-group">
						<label for="selectCourse" class="col-sm-2 control-label">Select Course:</label>
						<div class="col-sm-5">
							<?php echo $course; ?>
						</div>
					</div>
					
					<div id="newTeeCourseTeeForm" class="form-group">
						<label for="courseTee" class="col-sm-2 control-label">Course Tee:</label>
						<div class="col-sm-5">
							<input type='input' class='form-control' id='newTeeCourseTee' name='newTeeCourseTee' value='' placeholder='Enter Course Tee' />
						</div>
						<div id="newTeeHiddenCourseTeeDiv" class="col-sm-4">
							You must enter a course tee.
						</div>
					</div>
					
					<div id="newTeeCourseSSSForm" class="form-group">
						<label for="courseSSS" class="col-sm-2 control-label">Tee SSS:</label>
						<div class="col-sm-5">
							<input type='input' class='form-control' id='newTeeCourseSSS' name='newTeeCourseSSS' value='' placeholder='Enter Course SSS' />
						</div>
					</div>				
					
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button id="selectCourseButton" type="button" class="btn btn-primary">Select Course</button>
						</div>
					</div>
				</form>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
</div>