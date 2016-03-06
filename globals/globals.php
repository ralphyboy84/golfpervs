<?php

if (strstr($_SERVER['SCRIPT_NAME'], "login")) {
    
} else {
    if (!$_SESSION['username']) {
        if (strstr($_SERVER['SCRIPT_NAME'], "golfperv")) {
            header ("location: index.php"); 
        } else {
            echo "sessionlost";
            die();
        }
    }
}

if ($_SERVER['HTTP_HOST'] == "localhost") {
    //dev settings
    $SERVERNAME = "localhost";
    $USERNAME = "root";
    $PASSWORD = "";
    $DATABASE = "golfperv_shotsaver";    
} else {
    //live settings
    $SERVERNAME = "localhost";
    $USERNAME = "golfperv_golfpe";
    $PASSWORD = "G0lfp3rv5!";
    $DATABASE = "golfperv_shotsaver";
}

?>