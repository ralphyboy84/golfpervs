<?php

session_start();

require_once("../../globals/globals.php");
require_once '../../coreClasses/adminEditEvent.class.php';
require_once '../../coreClasses/quickForm.class.php';
require_once '../../coreClasses/errorHandler.class.php';
require_once ( "../../coreClasses/dateFormat.class.php" );

$add = new adminEditEvent ();
echo $add->viewEventList ();

?>


