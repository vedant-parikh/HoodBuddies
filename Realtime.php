<?php
session_start();
if (!isset($_SESSION['username']))
    header("location:login.php");
$username = $_SESSION['username'];
$mysqli = new mysqli("localhost", "root", "", "commcon");
//check if connection is a success
if (mysqli_connect_errno()) {
    die("Connection to database error:" . mysqli_connect_error() . "(" . mysqli_connect_errno() . ")");
}

$query1 = $mysqli->prepare('SELECT firstname,lastname,gender,address,birthdate,email,phone FROM userdata WHERE username=?');
$query1->bind_param('s', $username);
$query1->execute();
$query1->store_result();
$query1->bind_result($firstname, $lastname, $gender, $address, $birthdate, $email, $phone);
$value = $query1->fetch();
$query1->close();

$query2 = $mysqli->prepare('SELECT * FROM conversation WHERE msgto=? ORDER BY msgtime DESC');
$query2->bind_param('s', $username);
$query2->execute();
$query2->store_result();
$query2->bind_result($msgfrom, $msgto, $msgtime, $msgtype, $title, $message, $flag);
while ($query2->fetch()) {
    if ($msgtype == 1)
        continue;
    ?>
    <div class="row">
        <div class="col-lg-3">
            <div class="msg-name">
                <h4><a href="#"><?php echo $msgfrom ?></a></h4>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="msg-text">
                <h4 class="title"><?php echo $title ?></h4>
                <hr class="menu-hr">
                <h5 class="text"><?php echo $message; ?></h5>
                <div class="margin-bottom"></div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="msg-time">
                <h5><?php echo $msgtime ?></h5>
            </div>
        </div>
    </div>
    <?php

}
$query2->close();
?>
