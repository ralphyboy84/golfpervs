<?php
session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/friendsController.class.php");

$args = array (
    'username' => $_SESSION['username'],
    'friendid' => $_POST['friendid']
);
$fc = new friendsController();
$fc->insertFriendRequest($args);

require_once("../../coreClasses/notifications.class.php");
require_once("../../coreClasses/notificationsController.class.php");

$notif = new notifications();
$notif->setTo($_POST['friendid']);
$notif->setFrom($_SESSION['username']);
$notif->setSubject("New Friend Request");
$notif->setNotification("You have a new friend request from ".$_SESSION['username']);
$notif->setType("friend");
$notif->setDbObj(new notificationsController());
$notif->generateNotification();

?>
Friend Request Sent!