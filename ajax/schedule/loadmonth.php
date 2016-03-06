<?php
session_start ();

require_once("../../globals/globals.php");

require_once ( "../../coreClasses/eventsCalendar.class.php" );
require_once ( "../../coreClasses/eventsCalendarMonth.class.php" );
require_once ( "../../coreClasses/eventsCalendarDay.class.php" );
require_once ( "../../coreClasses/quickForm.class.php" );

$calargs['viewdate'] = $_GET['viewdate'];
$cal = new eventsCalendarMonth ($calargs);
$pagecontent = $cal->buildMonthView ();
echo $pagecontent;
?>