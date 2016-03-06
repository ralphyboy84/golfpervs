<?php
session_start();

require_once '../../globals/globals.php';
require_once '../../coreClasses/eventsCalendar.class.php';
require_once '../../coreClasses/eventsCalendarDay.class.php';
require_once '../../coreClasses/eventsCalendarMonth.class.php';
require_once '../../coreClasses/quickForm.class.php';
require_once '../../coreClasses/errorHandler.class.php';

echo "<div id='eventscalendarcontent'>";
/*
//get toggle selector first
$cal = new eventsCalendar ();
echo $cal->buildToggleSelector ();
*/
$calargs['todaysdate'] = date ("Ymd");
$cal = new eventsCalendarMonth ($calargs);
echo $cal->buildMonthView ();

echo "</div>";

?>


