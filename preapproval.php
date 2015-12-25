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

$query1 = $mysqli->prepare('SELECT firstname FROM userdata WHERE username=?');
$query1->bind_param('s', $username);
$query1->execute();
$query1->store_result();
$query1->bind_result($firstname);
$value = $query1->fetch();
$query1->close();

$query2 = $mysqli->prepare('SELECT requestid FROM brequest WHERE fromuser=?');
$query2->bind_param('s', $username);
$query2->execute();
$query2->store_result();
$query2->bind_result($rid);
$valu2 = $query2->fetch();
$query2->close();

$query3 = $mysqli->prepare('CALL totalblockmembers(?)');
$query3->bind_param('i', $rid);
$query3->execute();
$query3->store_result();
$query3->bind_result($totmem);
$valu3 = $query3->fetch();
$query3->close();

$query4 = $mysqli->prepare('CALL checkapproval(?)');
$query4->bind_param('i', $rid);
$query4->execute();
$query4->store_result();
$query4->bind_result($counappr);
$valu4 = $query4->fetch();
$query4->close();

if($counappr==$totmem ||$counappr>=3)
{
    $query5 = $mysqli->prepare('UPDATE brequest SET approvaltype="A" WHERE requestid=?');
    $query5->bind_param('i', $rid);
    $query5->execute();
    $query5->store_result();
    $query5->bind_result($counappr);
    $valu5 = $query5->fetch();
    $query5->close();

    $query7 = $mysqli->prepare('SELECT blockid FROM brequest WHERE requestid=?');
    $query7->bind_param('i', $rid);
    $query7->execute();
    $query7->store_result();
    $query7->bind_result($blockid);
    $valu7 = $query7->fetch();
    $query7->close();

    $query6 = $mysqli->prepare('INSERT INTO ubmap VALUES(?,?)');
    $query6->bind_param('si', $username, $blockid);
    $query6->execute();
    $query6->close();

    header("location:home.php");
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
                    <a class="" href="index.php"><img class="logo-img" src="images/hoodicon.png"
                                                      style="max-width: 50px; display: block; margin: 10px 0;"
                                                      alt="Logo"><h4 class="logo-text">HoodBuddies</h4></a>
                </div>
            </div>
            <div class="col-lg-4">
            </div>
            <div class="col-lg-4">
                <h4 class="reg-heading">Welcome <?php echo $firstname ?>!</h4>
                <form action="login.php" method="post">
                    <button type="submit" class="btn bg-btn" name="logout" style="float: right">Log Out</button>
                </form>
            </div>
        </div>
</nav>
<div class="preapproval-page">
    <div class="container">
        <div class="preapproval-page">
            <h4>We are still waiting for approval by block members. Once you are approved, we will add you to the block. <br>Thank you for your patience.</h4>
            <div class="row">
                <hr class="menu-hr"/>
            </div>
        </div>
    </div>
</body>
</html>
</html>