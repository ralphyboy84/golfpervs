<?php

session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/roundsController.class.php");
require_once("../../coreClasses/roundInfo.class.php");
require_once("../../coreClasses/dateFormat.class.php");

$args = array (
    'roundid' => $_POST['roundid'],
    'username' => $_SESSION['username']
);

$rc = new roundsController();
$x = $rc->completeTempRoundInfo($args);

$args['newroundid'] = $x['insertid'];
$y = $rc->completeTempScoreInfo($args);

$rc->deleteTempRoundInfo($args);
$rc->deleteTempScoreInfo($args);

$roundInfo = new roundInfo();
$roundInfo->setRoundId($x['insertid']);
$roundInfo->setDateObj(new dateFormat());
$scorecard = $roundInfo->buildScoreCard();

$roundid = $x['insertid'];

echo $roundid;
?>