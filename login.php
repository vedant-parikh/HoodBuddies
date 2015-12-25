<?php
if (isset($_POST['logout'])) {
    session_start();
    session_unset();
    session_destroy();
}
$Message = "";
if (isset($_POST['login'])) {
    session_start();
$Flag = true;

$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
$pass = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
if (!preg_match("/([A-Z-]{1,8})([a-z-]{1,8})([0-9-]{1,8})/", $pass)) {
$Message = $Message . "Please Enter Valid Password<br>";
$Flag = false;
}
$mysqli = new mysqli("localhost", "root", "", "commcon");
if (mysqli_connect_errno()) {
die("Connection to database error:" . mysqli_connect_error() . "(" . mysqli_connect_errno() . ")");
}
$call2 = $mysqli->prepare('SELECT username, password FROM userdata WHERE username=?');
$call2->bind_param('s', $username);
$call2->execute();
$call2->store_result();
if ($call2->num_rows > 0) {
$call2->bind_result($username, $password);
while ($call2->fetch()) {
if ($password == $pass) {
$_SESSION['username'] = $username;
header("location:home.php");
break;
} else {
$Message = $Message . "Password does not match. Please try again";
break;
}
}
} else {
$Message = $Message . "No user record found. Please Sign-Up";
}
$call2->close();
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
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="" href="index.php"><img class="logo-img" src="images/hoodicon.png"
                                              style="max-width: 50px; display: block; margin: 10px 0;" alt="Logo"><h4
                    class="logo-text">HoodBuddies</h4></a>
        </div>
    </div>
</nav>
<div class="main-page">
    <div class="container">
        <div class="login-page-signup">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="login-heading">Login</h1>
                    <h4 class="login-heading2" style="color:#000"><?php echo $Message;?></h4>
                    <form action="login.php" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" id="usr2" placeholder="Username" name="username">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="usr2" placeholder="Password"
                                   name="password">
                        </div>
                        <button type="submit" name="login" class="btn bg-btn">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    var winHeight = $(window).height();
    var navHeight = $(".navbar").height();
    $(".main-page").height(winHeight - navHeight);
    var mainPageSignupHeight = (winHeight / 2) - ($('.login-page-signup').height())
    $(".login-page-signup").css("margin-top", mainPageSignupHeight + "px");
</script>
</html>



