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

if(isset($_POST['acceptf'])){
    $fuser1 = $_POST['fromuser1'];
    $touser1 = $_POST['touser1'];
    $query5 = $mysqli->prepare('UPDATE relation SET type="F" WHERE touser=? AND fromuser=?');
    $query5->bind_param('ss', $username,$fuser1);
    $query5->execute();
    $query5->close();
}

elseif(isset($_POST['rejectf'])){
    $fuser2 = $_POST['fromuser1'];
    $touser2 = $_POST['touser1'];
    $query6 = $mysqli->prepare('UPDATE relation SET type="R" WHERE touser=? AND fromuser=?');
    $query6->bind_param('ss', $username,$fuser2);
    $query6->execute();
    $query6->close();
}

if(isset($_POST['acceptp'])){
    $reqid = $_POST['requestid'];
    $appusername = $_POST['appusername'];
    echo $reqid.$appusername;
    $query7 = $mysqli->prepare('INSERT INTO bresponse VALUES (?,?,"A")');
    $query7->bind_param('is', $reqid, $appusername);
    $query7->execute();
    $query7->close();
}

elseif(isset($_POST['rejectp'])){
    $reqid = $_POST['requestid'];
    $appusername = $_POST['appusername'];
    $query8 = $mysqli->prepare('INSERT INTO bresponse VALUES (?,?,"R")');
    $query8->bind_param('is', $reqid,$appusername);
    $query8->execute();
    $query8->close();
}

$query1 = $mysqli->prepare('SELECT firstname,lastname,gender,address,birthdate,email,phone FROM userdata WHERE username=?');
$query1->bind_param('s', $username);
$query1->execute();
$query1->store_result();
$query1->bind_result($firstname, $lastname, $gender, $address, $birthdate, $email, $phone);
$value = $query1->fetch();
$query1->close();
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
                    <a class="" href="index.php"><img class="logo-img" src="images/hoodicon.png"
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
                    <li class='active'><a href='home.php'>Home</a></li>
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
            </ul>
        </div>
        <div class="col-lg-9">
        <div class="pending-page">
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
            url: "brrequest.php",
            type: "GET",
            dataType: "html",
            success: function (data) {
                $('.pending-page').html(data);
            }
        });
    }, 1000);
</script>
</html>