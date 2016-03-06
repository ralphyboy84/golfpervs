<?php
session_start();
require_once ("../../coreClasses/courseCreator.class.php");

echo "<div class='col-sm-12'><form id='holesForm' method='POST'>";

if ($_POST) {
	foreach ($_POST as $id => $value) {
		echo "<input type='hidden' id='$id' name='$id' value='$value' />";
	}
}

echo "<h4>Add Hole Details for ".$_POST['courseName']." > ".$_POST['courseTee']."</h4>";
$courseInput = new courseCreator();
echo $courseInput->createCourse();
echo "<br /><button id='addFullCourseButton' type='button' class='btn btn-primary'>Add Course</button>";
echo "</form></div>";

?>