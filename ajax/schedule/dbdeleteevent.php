<?php
// start the session
session_start(); 
require_once ("../../globals/globals.php");

require_once ( "../../coreClasses/adminDeleteEvent.class.php" );
require_once ( "../../coreClasses/dateFormat.class.php" );

$args['eventid'] = $_POST['eventid'];
$admin = new adminDeleteEvent ($args);
$x = $admin->deleteEventInDB ();

require_once '../../coreClasses/quickForm.class.php';
require_once '../../coreClasses/errorHandler.class.php';

$params = array (
	'updated' => 1,
);

$add = new adminDeleteEvent ($params);
echo $add->viewEventList ();
?>


