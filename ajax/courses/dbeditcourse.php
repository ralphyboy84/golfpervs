<?php

session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/courseController.class.php");


$courseDb = new courseController();


$courseArray['name'] = $_POST['courseId'];
$courseArray['label'] = $_POST['courseName'];
$courseArray['location'] = $_POST['courseLocation'];
$courseArray['website'] = $_POST['courseWebsite'];
$courseArray['phonenno'] = $_POST['coursePhoneNo'];
$courseDb->updateCourse($courseArray);



for ($x=1;$x<=18;$x++) {
	$holearray['course'] = $_POST['courseId'];
	$holearray['tees'] = $_POST['teeId'];
	$holearray['hole'] = $x;
	$holearray['par'] = $_POST['par'.$x];
	$holearray['si'] = $_POST['si'.$x];
	$holearray['yards'] = $_POST['yards'.$x];
	$courseDb->updateHole($holearray);

	$totalPar = $totalPar + $_POST['par'.$x];
}

$teeArray['course'] = $_POST['courseId'];
$teeArray['tee'] = $_POST['teeId'];
$teeArray['teelabel'] = $_POST['courseTee'];
$teeArray['ss'] = $_POST['courseSSS'];
$teeArray['addedby'] = $_SESSION['username'];
$teeArray['par'] = $totalPar;
$teeArray['addedon'] = date ("Y-m-d");
$courseDb->updateTee($teeArray);


echo "Your course has been edited successfully.";
?>