<?php
session_start();
require_once("../../globals/globals.php");

require_once '../../coreClasses/adminAddEvent.class.php';
require_once '../../coreClasses/quickForm.class.php';
require_once '../../coreClasses/errorHandler.class.php';


$add = new adminAddEvent ();
echo $add->buildAddEvent ();


?>




