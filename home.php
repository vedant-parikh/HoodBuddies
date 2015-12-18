<?php
session_start();
if(!isset($_SESSION['username']))
    header("location:login.php");
$username = $_SESSION['username'];
$mysqli = new mysqli("localhost", "root", "", "commcon");
//check if connection is a success
if(mysqli_connect_errno())
{
    die("Connection to database error:" . mysqli_connect_error() . "(" . mysqli_connect_errno(). ")" );
}

$query1=$mysqli->prepare('SELECT firstname,lastname,gender,address,birthdate,email,phone FROM userdata WHERE username=?');
$query1->bind_param('s', $username);
$query1->execute();
$query1->store_result();
$query1->bind_result($firstname,$lastname,$gender,$address,$birthdate,$email,$phone);
$value =  $query1->fetch();
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
<div id="bc">
<nav class="navbar navbar-default navbar-static-top nav-bg">
    <div class="container" id="nav-height">
        <div class="row">
            <div class="col-lg-4">
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
            <div class="col-lg-4">
                <input class="form-control nav-search" name="search" placeholder="search here...">
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
            <hr class="menu-hr" />
        </div>
        <?php
        $query2=$mysqli->prepare('SELECT * FROM conversation WHERE msgto=? ORDER BY msgtime DESC');
        $query2->bind_param('s', $username);
        $query2->execute();
        $query2->store_result();
        $query2->bind_result($msgfrom,$msgto,$msgtime,$msgtype,$title,$message,$flag);
        while($query2->fetch())
        {
            if($msgtype==1)
                continue;
            ?>
        <div class="message-page">
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
        </div>
            <?php
        }
        $query2->close();
        ?>
            <!--  <div class="message-page">
                  <div class="row">
                      <div class="col-lg-3">
                          <div class="msg-name">
                              <h4><a href="#"> Zeal</a></h4>
                          </div>
                      </div>
                      <div class="col-lg-6">
                          <div class="msg-text">
                              <h4 class="title">hello</h4>
                              <hr class="menu-hr">
                              <h5 class="text">hellow</h5>
                              <div class="margin-bottom"></div>
                          </div>
                      </div>
                      <div class="col-lg-3">
                          <div class="msg-time">
                              <h4></h4>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-lg-3">
                          <div class="msg-name">
                              <h4>Zeal</h4>
                          </div>
                      </div>
                      <div class="col-lg-6">
                          <div class="msg-text">
                              <h4 class="title">hello</h4>
                              <hr class="menu-hr">
                              <h5 class="text">hellow</h5>
                          </div>
                      </div>
                      <div class="col-lg-3">
                          <div class="msg-time">
                              <h4></h4>
                          </div>
                      </div>
                  </div>
              </div> -->


</div>
</div>
</body>
<script>
    $(document).ready(function() {
        $.get('home.php', function (data) {
            $('#bc').html(data);
        });
    });
</script>
</html>