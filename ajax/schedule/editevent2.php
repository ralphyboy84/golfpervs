<?php
// start the session
session_start(); 
require_once ("../../globals/globals.php");
require_once ( "../../coreClasses/quickForm.class.php" );
require_once ( "../../coreClasses/dateFormat.class.php" );
require_once ( "../../coreClasses/adminEditEvent.class.php" );
require_once ( "../../coreClasses/eventsController.class.php" );

if ( $_GET['eventid'] )
{
	$q = new eventsController ();
	$dbargs['eventid'] = $_GET['eventid'];
	$dbres = $q->returnCalendarEventByEventID ( $dbargs );
	
	if ( $dbres['res'] )
	{
		$args['startdate']   = $dbres['res'][0]['startdate'];
		$args['enddate']     = $dbres['res'][0]['enddate'];
		$args['title']       = $dbres['res'][0]['title'];
		$args['description'] = $dbres['res'][0]['description'];
		$args['eventid']     = $dbres['res'][0]['eventid'];
		$args['courseid']     = $dbres['res'][0]['courseid'];
		$edit = new adminEditEvent ($args);
		echo $edit->buildEditEvent ();
	}
	else
	{
		echo "Nothin from DB";
	}
}
else
{
	echo "ERROR";
}
?>