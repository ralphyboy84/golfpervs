<?php
session_start();
require_once("../../coreClasses/courseCreator.class.php");
require_once("../../globals/globals.php");

echo "<div class='col-sm-offset-1 col-sm-11'><form id='holesForm' method='POST' class='form-horizontal'>";

if ($_POST) {
	foreach ($_POST as $id => $value) {
		echo "<input type='hidden' id='$id' name='$id' value='$value' />";
	}
}

echo "<h4>Edit Course Details for ".$_POST['courseId']." > ".$_POST['teeId']."</h4>";

$editCourse = new courseCreator();
$editCourse->setCourse($_POST['courseId']);
$editCourse->setTee($_POST['teeId']);
$editForm = $editCourse->generateEditCourseBasicInfo();

echo<<<EOHTML
$editForm
EOHTML;

echo "<h4>Edit Hole Details for ".$_POST['courseId']." > ".$_POST['teeId']."</h4>";
$courseInput = new courseCreator();
$courseInput->setCourse($_POST['courseId']);
$courseInput->setTee($_POST['teeId']);
echo $courseInput->editCourse();
echo "<br /><button id='editFullCourseButton' type='button' class='btn btn-primary'>Edit Course</button>";
echo "</form></div>";

?>