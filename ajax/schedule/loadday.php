<?php
session_start ();

require_once("../../globals/globals.php");

require_once ( "../../coreClasses/eventsCalendar.class.php" );
require_once ( "../../coreClasses/eventsCalendarMonth.class.php" );
require_once ( "../../coreClasses/eventsCalendarDay.class.php" );
require_once ( "../../coreClasses/quickForm.class.php" );

if ( $_SERVER['HTTP_HOST'] == "localhost" )
{   function __autoload($class_name) {
    require_once 'classes/shotsaver/query.class.php';
	}
}
else
{
   function __autoload($class_name) {
    require_once '../../classes/shotsaver/query.class.php';
	}
}

$calargs['viewdate'] = $_GET['viewdate'];
$cal = new eventsCalendarDay ($calargs);
$pagecontent = $cal->buildDayView ();
echo $pagecontent;
?>