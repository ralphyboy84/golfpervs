<?php
session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/courseController.class.php");

$teeArgs = array (
	'course' => $_POST['courseName'],
);

$q = new courseController ();
$teeRes = $q->returnTeeInfoForCourse ($teeArgs);

if ( $teeRes['res'] ) {
	foreach ( $teeRes['res'] as $tees ) {
		$optionArgs[] = "<option value='".$tees['tee']."'>".$tees['teelabel']."</option>";
	}
}

if ($optionArgs) {
	$tee = "<select id='tee_select' name='tee_select' class='form-control'>".implode ( $optionArgs )."</select>";
}

?>
<div class="form-group">
	<label for="selectCourse" class="col-sm-2 control-label">Select Tee:</label>
	<div class="col-sm-5">
		<?php echo $tee; ?>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<button id="selectFullCourseForEditButton" type="button" class="btn btn-primary">Edit Course</button>
	</div>
</div>