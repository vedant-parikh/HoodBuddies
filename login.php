<?php
session_start();
if(isset($_POST['logout']))
{
    session_unset();
    session_destroy();
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
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="" href="index.php"><img class="logo-img" src="images/hoodicon.png" style="max-width: 50px; display: block; margin: 10px 0;" alt="Logo"><h4 class="logo-text">HoodBuddies</h4></a>
        </div>
    </div>
</nav>
<div class="main-page">
    <div class="container">
        <div class="login-page-signup">
            <div class="row">
            <div class="col-lg-12">
                <h1 class="login-heading">Login</h1>
                <form action="home.php" method="post" >
                    <div class="form-group">
                          <input type="text" class="form-control" id="usr2" placeholder="Username" name="username">
                    </div>
                    <div class="form-group">
                          <input type="password" class="form-control" id="usr2" placeholder="Password" name="password">
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
    var winHeight=$(window).height();
    var navHeight=$(".navbar").height();
    $(".main-page").height(winHeight-navHeight);
    var mainPageSignupHeight=(winHeight/2)-($('.login-page-signup').height())
    $(".login-page-signup").css("margin-top",mainPageSignupHeight+"px");
</script>
</html>



