<?php
session_start();
require_once("globals/globals.php");
require_once("coreClasses/userController.class.php");

$uc = new userController();

if ($_POST) {
    $args['username'] = $_POST['inputUsernameRegister'];
    $args['password'] = $_POST['inputPasswordRegister'];
    $args['forename'] = $_POST['inputForename'];
    $args['surname'] = $_POST['inputSurname'];
    $args['email'] = $_POST['inputEmail'];
    $x = $uc->insertNewUser ( $args );

    if ($x['error']) {
        if (strstr($x['error'], "Duplicate entry" )){
            $_SESSION['registerFailed'] = true;
            header("location: index.php");
        }
    } else {
        $_SESSION['username'] = $_POST['inputUsernameRegister'];
        header("location: golfpervs.php");
    }
}

?>