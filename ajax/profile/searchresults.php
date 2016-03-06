<?php

session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/friends.class.php");
require_once("../../coreClasses/friendsController.class.php");
require_once("../../coreClasses/dateFormat.class.php");

$fr = new friends();
$fr->setDbObj(new friendsController());
$fr->setDateObj(new dateFormat());
$fr->setSearchCriteria($_POST['criteria']);
echo $fr->searchForPerv();

?>