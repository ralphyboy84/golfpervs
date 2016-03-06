<?php
session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/notifications.class.php");
require_once("../../coreClasses/notificationsController.class.php");

$notifs = new notifications();
$notifs->setNotifID($_POST['id']);
$notifs->setDbObj(new notificationsController());
echo $notifs->getSingleNotification();

$nc = new notificationsController();
$nc->confirmNotification($_POST);

?>