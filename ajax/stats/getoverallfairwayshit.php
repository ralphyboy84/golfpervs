<?php
session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/fairways.class.php");
require_once("../../coreClasses/statsController.class.php");
require_once("../../coreClasses/dateFormat.class.php");

$df = new dateFormat();

if ($_GET['startdate']) {
    $startdate = $df->formatDateToDatabaseOther($_GET['startdate']);   
}

if ($_GET['enddate']) {
    $enddate = $df->formatDateToDatabaseOther($_GET['enddate']);   
}

$fairways = new fairways();
$fairways->setUsername($_SESSION['username']);
$fairways->setStartDate($startdate);
$fairways->setEndDate($enddate);
$fairways->setCompetition($_GET['competition']);
$fairways->setCourse($_GET['course']);
$fairways->setDbObj(new statsController());
$fairways->setDateObj($df);
$info = $fairways->getStats();

echo json_encode($info['overall']);
?>