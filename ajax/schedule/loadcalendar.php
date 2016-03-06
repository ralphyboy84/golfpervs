<?php
session_start ();

require_once("../../globals/globals.php");

require_once ( "../../coreClasses/eventsCalendar.class.php" );
require_once ( "../../coreClasses/eventsCalendarMonth.class.php" );
require_once ( "../../coreClasses/eventsCalendarDay.class.php" );
require_once ( "../../coreClasses/quickForm.class.php" );

//need to check the date format
//needs to be in YYYYMMDD for the functions below..
if (strlen($_GET['date']) == 10) {
	$expArgs = explode ("/", $_GET['date']);
	$_GET['date'] = $expArgs[2].$expArgs[1].$expArgs[0];
}

if ( $_GET['param'] == "month" )
{
	if ( $_GET['date'] ) { $calargs['todaysdate'] = $_GET['date']; }
	else				 { $calargs['todaysdate'] = date ("Ymd"); }
	$cal = new eventsCalendarMonth ($calargs);
	$pagecontent = $cal->buildMonthView ();
	echo $pagecontent;
}
else if ( $_GET['param'] == "day" )
{
	if ( $_GET['date'] ) { $calargs['todaysdate'] = $_GET['date']; }
	else				 { $calargs['todaysdate'] = date ("Ymd"); }
	$cal = new eventsCalendarDay ($calargs);
	$pagecontent = $cal->buildDayView ();
	echo $pagecontent;
}
?>