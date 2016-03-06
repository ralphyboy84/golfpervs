<?php
session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/courseController.class.php");

$cc = new courseController();
$res = $cc->returnTeeInfoForCourse($_GET);

echo json_encode($res['res']);

?>

          
