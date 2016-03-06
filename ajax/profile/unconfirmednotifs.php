<?php
session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/notifications.class.php");
require_once("../../coreClasses/notificationsController.class.php");

$notif = new notifications();
$notif->setUserName($_SESSION['username']);
$notif->setDbObj(new notificationsController());
$notifs = $notif->getAllUnconfirmedNotifications();

$newArray['data'] = $notifs;

if ($notifs) {
    $newArray['numofnotifs'] = sizeof($notifs);
} else {
    $newArray['numofnotifs'] = "";   
}

echo json_encode($newArray);
?>