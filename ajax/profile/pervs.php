<?php

session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/friends.class.php");
require_once("../../coreClasses/friendsController.class.php");

$pervs = new friends();
$pervs->setDbObj(new friendsController());
$pervs->setUserName($_SESSION['username']);
$table = $pervs->getPervsInTable();

?>
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> All Your Pervs</h3>
        </div>
        <div class="box-body">
            <?php
            echo $table;
            ?>
      </div><!-- /.box -->
    </div>
  </div>
</div>