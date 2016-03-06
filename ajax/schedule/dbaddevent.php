<?php

session_start();

require_once("../../globals/globals.php");

require_once ( "../../coreClasses/adminAddEvent.class.php" );
require_once ( "../../coreClasses/dateFormat.class.php" );

$dateArgs = array(
	'date' => $_POST['startDate'],
);

$df = new dateFormat($dateArgs);
$args['startdate'] = $df->formatDateToDatabase();

if ($_POST['endDate']) {
	$dateArgs = array(
		'date' => $_POST['endDate'],
	);

	$df = new dateFormat($dateArgs);
}

$args['enddate'] = $df->formatDateToDatabase();
$args['title'] = $_POST['eventName'];
$args['description'] = $_POST['description'];
$args['courseid'] = $_POST['course'];
$admin = new adminAddEvent ($args);
$admin->addEventToDB ();

echo "Your event has been added!";
?>


