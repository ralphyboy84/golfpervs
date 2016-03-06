<?php
session_start();
require_once ("../../globals/globals.php");
require_once ("../../coreClasses/roundInfo.class.php");
require_once ("../../coreClasses/rounds.class.php");
$roundInfo = new roundInfo();
$roundInfo->setRoundId($_GET['id']);
$info = $roundInfo->getGreensForRound();

echo json_encode($info);
?>
