<?php

session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/courseController.class.php");

$courseDb = new courseController();

//before we insert into the course table we need to make sure we don't have info for this course
$checkArray = array (
	'name' => $_POST['courseName'],
);

$courseCheck = $courseDb->returnCourseInfo($checkArray);

if (!$courseCheck['res']) {
	$courseArray['name'] = $_POST['courseName'];
	$courseArray['label'] = $_POST['courseName'];
	$courseArray['location'] = $_POST['courseLocation'];
	$courseArray['website'] = $_POST['courseWebsite'];
	$courseArray['phonenno'] = $_POST['coursePhoneNo'];
	$courseDb->insertCourse($courseArray);
}

//we also need to check we havent entered this tee set
//if we have then dont enter it again
$teeCheckArray = array (
	'course' => $_POST['courseName'],
	'tee' => $_POST['courseTee'],
);

$teeCheck = $courseDb->returnTeeInfo($teeCheckArray);

if (!$teeCheck['res']) {
	for ($x=1;$x<=18;$x++) {
		$holearray['course'] = $_POST['courseName'];
		$holearray['tees'] = $_POST['courseTee'];
		$holearray['hole'] = $x;
		$holearray['par'] = $_POST['par'.$x];
		$holearray['si'] = $_POST['si'.$x];
		$holearray['yards'] = $_POST['yards'.$x];
		$courseDb->insertHole($holearray);

		$totalPar = $totalPar + $_POST['par'.$x];
	}

	$teeArray['course'] = $_POST['courseName'];
	$teeArray['tee'] = $_POST['courseTee'];
	$teeArray['teelabel'] = $_POST['courseTee'];
	$teeArray['ss'] = $_POST['courseSS'];
	$teeArray['addedby'] = $_SESSION['username'];
	$teeArray['par'] = $totalPar;
	$teeArray['addedon'] = date ("Y-m-d");
	$courseDb->insertTee($teeArray);
} else {
	$errorFlag = true;
}


if (!$errorFlag) {
	echo "You're course has been added successfully.";
} else {
	echo "It looks like the information you have tried to enter has been entered before. Please try again";
}
?>