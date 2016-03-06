<?php
// start the session
session_start(); 
require_once ("../../globals/globals.php");

require_once ( "../../coreClasses/adminEditEvent.class.php" );
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
$args['eventid'] = $_POST['eventid_hidden'];
$args['courseid'] = $_POST['course'];
$admin = new adminEditEvent ($args);
$x = $admin->editEventInDB ();

require_once '../../coreClasses/quickForm.class.php';
require_once '../../coreClasses/errorHandler.class.php';

$params = array (
	'updated' => 1,
);

$add = new adminEditEvent ($params);
echo $add->viewEventList ();
?>


