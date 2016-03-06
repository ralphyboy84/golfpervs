<?php

session_start();

require_once("../../globals/globals.php");
require_once("../../coreClasses/rounds.class.php");
$roundInfo = new rounds();
$roundInfo->setUser($_SESSION['username']);
$table = $roundInfo->getRoundsForEditRoundTable();

echo<<<EOHTML
<div class='row'>
    <div class="col-lg-12 col-xs-12">
        <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-bullseye"></i> Edit/Delete Round</h3>
            </div>
            <div class='box-body'>
                $table
            </div>
        </div>
    </div>
</div>
EOHTML;


?>