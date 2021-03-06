<?php
session_start();
$link=$_SERVER['PHP_SELF'];
if (!isset($_SESSION['username']))
    header("location:login.php?link=".$link);
$username = $_SESSION['username'];
$mysqli = new mysqli("localhost", "root", "", "commcon");
//check if connection is a success
if (mysqli_connect_errno()) {
    die("Connection to database error:" . mysqli_connect_error() . "(" . mysqli_connect_errno() . ")");
}

if(isset($_SESSION['username'])) {
    $query33 = $mysqli->prepare('SELECT approvaltype FROM brequest WHERE fromuser=?');
    $query33->bind_param('s', $_SESSION['username']);
    $query33->execute();
    $query33->store_result();
    $query33->bind_result($approvaltype);
    $value33 = $query33->fetch();
    $query33->close();

    if ($approvaltype != "A")
        header("location:preapproval.php");
}

$query1 = $mysqli->prepare('SELECT firstname,lastname,gender,address,birthdate,email,phone FROM userdata WHERE username=?');
$query1->bind_param('s', $username);
$query1->execute();
$query1->store_result();
$query1->bind_result($firstname, $lastname, $gender, $address, $birthdate, $email, $phone);
$value = $query1->fetch();
$query1->close();

if (isset($_POST['readcheck'])) {
    $msgfrom2 = $_POST['msgfrom'];
    $msgto2 = $_POST['msgto'];
    $msgtime2 = $_POST['msgtime'];
    $query56 = $mysqli->prepare('UPDATE conversation SET flag=0 WHERE msgfrom=? AND msgto=? AND msgtime LIKE ?');
    $query56->bind_param('sss', $msgfrom2, $msgto2, $msgtime2);
    $query56->execute();
    $query56->close();

} elseif(isset($_POST['unreadcheck'])) {
    $msgfrom2 = $_POST['msgfrom'];
    $msgto2 = $_POST['msgto'];
    $msgtime2 = $_POST['msgtime'];
    $query56 = $mysqli->prepare('UPDATE conversation SET flag=1 WHERE msgfrom=? AND msgto=? AND msgtime LIKE ?');
    $query56->bind_param('sss', $msgfrom2, $msgto2, $msgtime2);
    $query56->execute();
    $query56->close();

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HoodBuddies</title>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Indie+Flower' rel='stylesheet' type='text/css'>
    <link rel="icon" sizes="180x180" href="images/apple-icon-180x180.png">
</head>
<body>
<nav class="navbar navbar-default navbar-static-top nav-bg">
    <div class="container" id="nav-height">
        <div class="row">
            <div class="col-lg-4">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                            aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="" href="home.php"><img class="logo-img" src="images/hoodicon.png"
                                                      style="max-width: 50px; display: block; margin: 10px 0;"
                                                      alt="Logo"><h4 class="logo-text">HoodBuddies</h4></a>
                </div>
            </div>
            <div class="col-lg-4">
                <form action="search.php" method="post">
                    <input class="form-control nav-search" name="search" placeholder="search here...">
                    <input type="submit" style="display:none"/>
                </form>
            </div>
            <div class="col-lg-4">
                <h4 class="reg-heading">Welcome <?php echo $firstname ?>!</h4>
                <form action="login.php" method="post">
                    <button type="submit" class="btn bg-btn" name="logout" style="float: right">Log Out</button>
                </form>
            </div>
        </div>
</nav>
<div class="profile-page">
    <div class="container">
        <div class="row">
            <div id='cssmenu'>
                <ul>
                    <li class='active'><a href='#'>Home</a></li>
                    <li><a href='profile.php'>Profile</a></li>
                    <li><a href='connections.php'>Connections</a></li>
                    <li><a href='maps.php'>Map</a></li>
                    <li><a href='messages.php'>Messages</a></li>
                </ul>
            </div>
            <hr class="menu-hr"/>
        </div>
        <div class="col-lg-3">
            <ul class="side-menu">
                <li><a href="pendingrequest.php">Pending Requests</a></li>
                <li><a id="neighborsurl">Neighbors</a></li>
                <li><a id="blockurl">Block</a></li>
                <li><a id="neighborhoodurl">Neighborhood</a></li>
                <li><a id="senturl">Sent</a></li>
            </ul>
        </div>
        <div class="col-lg-9">
        <div class="message-page">
            <div id="neighbors"></div>
            <div id="block"></div>
            <div id="neighborhood"></div>
            <div id="sent"></div>
        </div>
            </div>
    </div>
</body>
</html>
<script>
    var timestamp = 0;
    //setTimeout("location.reload(true);",5000);
    setInterval(function () {
        $.ajax({
            url: "nbmessages.php",
            type: "GET",
            dataType: "html",
            success: function (data) {
                $('#neighborhood').html(data);
            }
        });
    }, 1000);
    setInterval(function () {
        $.ajax({
            url: "nbmessages.php",
            type: "GET",
            dataType: "html",
            success: function (data) {
                $('#neighborhood').html(data);
            }
        });
    }, 1000);
    setInterval(function () {
        $.ajax({
            url: "nmessages.php",
            type: "GET",
            dataType: "html",
            success: function (data) {
                $('#neighbors').html(data);
            }
        });
    }, 1000);
    setInterval(function () {
        $.ajax({
            url: "bmessages.php",
            type: "GET",
            dataType: "html",
            success: function (data) {
                $('#block').html(data);
            }
        });
    }, 1000);
    setInterval(function () {
        $.ajax({
            url: "sent.php",
            type: "GET",
            dataType: "html",
            success: function (data) {
                $('#sent').html(data);
            }
        });
    }, 1000);
</script>
<script>
    $(document).ready(function() {
    $('#neighborsurl').click(function(){
        $('#neighbors').show();
        $('#blocks').hide();
        $('#neighborhood').hide();
        $('#sent').hide();
    }) });
    $(document).ready(function() {
    $('#blockurl').click(function(){
        $('#neighbors').hide();
        $('#blocks').show();
        $('#neighborhood').hide();
        $('#sent').hide();
    }) });
    $(document).ready(function() {
    $('#neighborhoodurl').click(function(){
        $('#neighbors').hide();
        $('#blocks').hide();
        $('#neighborhood').show();
        $('#sent').hide();
    }) });
    $(document).ready(function() {
    $('#senturl').click(function(){
        $('#neighbors').hide();
        $('#blocks').hide();
        $('#neighborhood').hide();
        $('#sent').show();
    }) });
</script>
</html>