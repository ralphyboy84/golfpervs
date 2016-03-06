<?php

session_start();

require_once("globals/globals.php");
require_once("coreClasses/loginController.class.php");

$args = array (
	'username' => $_POST['inputUsername'],
	'password' => $_POST['inputPassword'],
);

$lc = new loginController();
$loginInfo = $lc->validateLogin($args);

if ($loginInfo['res']) {
	$_SESSION['username'] = $loginInfo['res']['0']['username'];
	
	//unset the loginFailed var if its ben set..
	unset($_SESSION['loginFailed']);
	
	//redirect towards the main page...
  	header('Location: golfpervs.php');
} else {
	$_SESSION['loginFailed'] = 1;

	//redirect towards the main page...
  	header('Location: index.php');
}

?>