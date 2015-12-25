<?php
/**
 * User: vedant
 */
session_start();
$link=$_SERVER['PHP_SELF'];
if (!isset($_SESSION['username']))
    header("location:login.php?link=".$link);
$username = $_SESSION['username'];
$blockid=$_GET['blockid'];
$mysqli = new mysqli("localhost", "root", "", "commcon");
$blockid = str_replace('.', '', $blockid);
$blockid = str_replace("'", '', $blockid);
//echo $blockid;
//check if connection is a success
if (mysqli_connect_errno()) {
    die("Connection to database error:" . mysqli_connect_error() . "(" . mysqli_connect_errno() . ")");
}
        if($mysqli->query('INSERT INTO brequest(fromuser,blockid,approvaltype) VALUES("'.$username.'","'.$blockid.'","P")')===FALSE){
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        } else {
            unset($_SESSION['address']);
           // $Message = $Message . "Registeration Successful";
            $_SESSION['username'] = $username;
            header("location:preapproval.php");
        }
        //$call->close();

    $mysqli->close();

?>
