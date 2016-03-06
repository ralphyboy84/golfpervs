<?php
session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/greens.class.php");
require_once("../../coreClasses/statsController.class.php");
require_once("../../coreClasses/dateFormat.class.php");

$df = new dateFormat();

if ($_GET['startdate']) {
    $startdate = $df->formatDateToDatabaseOther($_GET['startdate']);   
}

if ($_GET['enddate']) {
    $enddate = $df->formatDateToDatabaseOther($_GET['enddate']);   
}

$girs = new greens();
$girs->setUsername($_SESSION['username']);
$girs->setStartDate($startdate);
$girs->setEndDate($enddate);
$girs->setCompetition($_GET['competition']);
$girs->setCourse($_GET['course']);
$girs->setDbObj(new statsController());
$girs->setDateObj($df);
$info = $girs->getStats();

echo json_encode($info['overall']);
?>