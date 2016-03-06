<?php

session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/user.class.php");

if ($_GET['update'] && $_POST) {
    require_once("../../coreClasses/userController.class.php");
    
    $uc = new userController();
    $args = array (
        'forename' => $_POST['forename'],
        'surname' => $_POST['surname'],
        'email' => $_POST['email'],
        'username' => $_SESSION['username'],
        'receivenotifs' => $_POST['receivenotifs']
    );
    $x = $uc->updateUser($args);
}

$perv = new user();
$perv->setUsername($_SESSION['username']);
$table = $perv->getUserInfoTable();

?>
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Profile</h3>
        </div>
        <div class="box-body">
            <?php
            echo $table;
            ?>
      </div><!-- /.box -->
    </div>
  </div>
</div>