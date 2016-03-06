<?php

session_start();

require_once("../../globals/globals.php");
require_once("../../coreClasses/rounds.class.php");
$roundInfo = new rounds();
$roundInfo->setUser($_SESSION['username']);
$table = $roundInfo->getRoundsForTable();

echo<<<EOHTML
<div class='box'>
    <div class='box-header'>
        Please click a round to view it in more detail
    </div>
    <div class='box-body'>
        $table
    </div>
</div>
EOHTML;


?>