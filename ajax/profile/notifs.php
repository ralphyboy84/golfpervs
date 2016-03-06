<?php
session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/notifications.class.php");
require_once("../../coreClasses/notificationsController.class.php");
require_once("../../coreClasses/dateFormat.class.php");

$notif = new notifications();
$notif->setUserName($_SESSION['username']);
$notif->setDbObj(new notificationsController());
$notif->setDateObj(new dateFormat());
$allnotifs = $notif->getAllNotificationsTable();

?>
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-map-o"></i> All Notifications</h3>
        </div>
        <div class="box-body">
            <?php
            echo $allnotifs;
            ?>
      </div><!-- /.box -->
    </div>
  </div>
</div>
