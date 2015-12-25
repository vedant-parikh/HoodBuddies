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

$keyword=$_POST['search'];
$keywords='%'.$keyword.'%';

$query1 = $mysqli->prepare('SELECT username, firstname, lastname, email FROM userdata WHERE username LIKE ?');
$query1->bind_param('s',$keywords);
$query1->execute();
$query1->store_result();
$query1->bind_result($username1, $firstname, $lastname, $email);

$query2 = $mysqli->prepare('SELECT msgtime, msgfrom, title, message from conversation where msgto=? and message like ?');
$query2->bind_param('ss',$username, $keywords);
$query2->execute();
$query2->store_result();
$query2->bind_result($msgtime, $msgfrom, $title, $message);

$query3 = $mysqli->prepare('SELECT neighborhood FROM neighborhood WHERE neighborhood LIKE ?');
$query3->bind_param('s', $keywords);
$query3->execute();
$query3->store_result();
$query3->bind_result($nbr);

$query4 = $mysqli->prepare('SELECT blockname FROM block WHERE blockname LIKE ?');
$query4->bind_param('s', $keywords);
$query4->execute();
$query4->store_result();
$query4->bind_result($blk);


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
                <h4 class="reg-heading">Welcome <?php echo $username ?>!</h4>
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
                    <li><a href='home.php'>Home</a></li>
                    <li><a href='profile.php'>Profile</a></li>
                    <li><a href='connections.php'>Connections</a></li>
                    <li><a href='maps.php'>Map</a></li>
                    <li><a href='messages.php'>Messages</a></li>
                </ul>
            </div>
            <hr class="menu-hr"/>
        </div>
        <div class="col-lg-3">
        </div>
        <div class="col-lg-9">
        <div class="message-page">
        <div class="user-details">
            <div class="col-lg-12">
            <h4>User Details Section</h4>
            <?php
            while($query1->fetch())
            {
            echo "<div class='row'> <div class='col-lg-3'><form action=\"userprofile.php\" method=\"post\">
                    <div class=\"msg-name\">
                        <input type=\"hidden\" name=\"otheruser\" value=\"$username1\">
                        <input class=\"h4\" type=\"submit\" name=\"ouserpost\" value=\"$username1\">
                    </div>
                </form></div><div class='col-lg-9'></div></div>";
            }
            $query1->close();
            ?>
            </div>
        </div>
            <div class="user-details">
                <div class="col-lg-12">
                    <h4>Message Section</h4>
                    <?php
                    while($query2->fetch())
                    {
                    ?>
                    <div class="row">
                        <div class="col-lg-3">
                    <form action="userprofile.php" method="post">
                    <div class="msg-name">
                        <input type="hidden" name="otheruser" value="<?php echo $msgfrom ?>">
                        <input class="h4" type="submit" name="ouserpost" value="<?php echo $msgfrom ?>">
                    </div>
                </form>
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
               <?php     }
                    $query2->close();
                    ?>
                </div>
                </div>
            <div class="user-details">
                <div class="col-lg-12">
                    <h4>Neighbourhood Section</h4>
                    <?php
                    while($query3->fetch())
                    {
                        echo $nbr;
                    }
                    $query3->close();
                    ?>
                </div>
            </div>
            <div class="user-details">
                <div class="col-lg-12">
                    <h4>Block Section</h4>
                    <?php
                    while($query4->fetch())
                    {
                        echo $blk;
                    }
                    $query4->close();
                    ?>
                </div>
            </div>
            </div>
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
            url: "realtime.php",
            type: "GET",
            dataType: "html",
            success: function (data) {
                $('.message-page').html(data);
            }
        });
    }, 1000);
</script>
</html>