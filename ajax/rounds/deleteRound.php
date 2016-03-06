<?php

session_start();

require_once("../../globals/globals.php");
require_once("../../coreClasses/roundsController.class.php");

$args = array (
    'roundid' => $_POST['roundid'],
    'username' => $_SESSION['username']
);

$rc = new roundsController();
$x = $rc->deleteRound($args);

echo<<<EOHTML
<div class='row'>
    <div class="col-lg-12 col-xs-12">
        <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-bullseye"></i> Delete Round</h3>
            </div>
            <div class='box-body'>
                Your round has been deleted.
            </div>
        </div>
    </div>
</div>
EOHTML;


?>