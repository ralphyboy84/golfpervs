<?php
session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/notifications.class.php");
require_once("../../coreClasses/notificationsController.class.php");
require_once("../../coreClasses/friendsController.class.php");



$notifs = new notifications();
$notifs->setNotifID($_POST['id']);
$notifs->setDbObj(new notificationsController());
$info = $notifs->getSingleNotificationInfo();

$args = array (
    'friendid' => $_SESSION['username'],
    'username' => $info['res'][0]['sentby']
);
$fc = new friendsController();
$fc->updateFriendRequest($args);

$args = array (
    'username' => $_SESSION['username'],
    'friendid' => $info['res'][0]['sentby'],
    'confirmed' => 1,
    'since' => 'now()'
);
$fc = new friendsController();
$fc->insertFriendRequest($args);

$notif = new notifications();
$notif->setTo($info['res'][0]['sentby']);
$notif->setFrom($_SESSION['username']);
$notif->setSubject("Friend Request Confirmed");
$notif->setNotification($_SESSION['username']." has confirmed your friend request");
$notif->setType("friendaccept");
$notif->setDbObj(new notificationsController());
$notif->generateNotification();

$notif = new notifications();
$notif->setFrom($info['res'][0]['sentby']);
$notif->setTo($_SESSION['username']);
$notif->setSubject("Friend Request Confirmed");
$notif->setNotification("You and ".$info['res'][0]['sentby']." are now friends");
$notif->setType("friendconfirm");
$notif->setDbObj(new notificationsController());
$notif->generateNotification();

$friend = $info['res'][0]['sentby'];

echo<<<EOTHML
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-users"></i> Friend Request Confirmed</h3>
        </div>
        <div class="box-body">
            You and $friend are now friends
      </div><!-- /.box -->
    </div>
  </div>
</div>
EOTHML;
?>