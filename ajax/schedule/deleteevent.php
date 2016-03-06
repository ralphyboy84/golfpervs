<?php

session_start();

require_once("../../globals/globals.php");
require_once '../../coreClasses/adminDeleteEvent.class.php';
require_once '../../coreClasses/quickForm.class.php';
require_once '../../coreClasses/errorHandler.class.php';
require_once ( "../../coreClasses/dateFormat.class.php" );

$add = new adminDeleteEvent ();
echo $add->viewEventList ();

?>


