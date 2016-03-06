<?php

session_start();
require_once("../../globals/globals.php");
require_once("../../coreClasses/user.class.php");
require_once("../../coreClasses/email.class.php");
require_once("../../coreClasses/roundInfo.class.php");
require_once("../../coreClasses/dateFormat.class.php");

$roundInfo = new roundInfo();
$roundInfo->setRoundId($_POST['roundid']);
$roundInfo->setDateObj(new dateFormat());
$scorecard = $roundInfo->buildScoreCard();

$user = new user();
$user->setUsername($_SESSION['username']);
$info = $user->returnAllUserInfo();

$mail = new email();
$mail->setEmail($info['email']);
$mail->setSubject("New Round");
$mail->setNotification($scorecard);
$mail->sendEmail();
?>