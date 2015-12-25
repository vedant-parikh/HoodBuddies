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

$friend='';
$query2 = $mysqli->prepare('select fromuser, touser from relation where fromuser=? or touser=? and type="F"');
$query2->bind_param('ss',$username, $username);
$query2->execute();
$query2->store_result();
$query2->bind_result($fromuser, $touser);


$nbrs='';
$query3 = $mysqli->prepare('select fromuser, touser from relation where fromuser=? or touser=? and type="N"');
$query3->bind_param('ss',$username,$username);
$query3->execute();
$query3->store_result();
$query3->bind_result($fromuser1, $touser1);


if (isset($_POST['submit'])) {
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
                <input class="form-control nav-search" name="search" placeholder="search here...">
            </div>
            <div class="col-lg-4">
                <h4 class="reg-heading">Welcome <?php echo $firstname ?>!</h4>
                <form action="login.php" method="post">
                    <button type="submit" class="btn bg-btn" style="float: right">Log Out</button>
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
                    <li class='active'><a href='#'>Connections</a></li>
                    <li><a href='maps.php'>Map</a></li>
                    <li><a href='messages.php'>Messages</a></li>
                </ul>
            </div>
            <hr class="menu-hr"/>
        </div>
        <div class="row">
        <div class="col-lg-12">
            <div class="connection-page">
                <h1>Friends</h1>
                <?php
                while($query2->fetch())
                {
                if($fromuser!=$username)
                    $friend=$fromuser;
                elseif($touser!=$username){
                    $friend=$touser;}
                else
                    $friend=NULL;
                if($friend==NULL)
                    echo "You Have No Friends :(";
                elseif($friend!=false)
                    echo "<div class='row'> <div class='col-lg-3'><div class='msg-name'>".$friend."</div></div><div class='col-lg-9'></div></div>";?>
                    <?php
                }
                $query2->close();
                ?>
                <h1>Neighbors</h1>
                <?php
                while($query3->fetch())
                {
                    if($fromuser1!=$username)
                        $nbrs=$fromuser1;
                    elseif($touser1!=$username)
                        $nbrs=$touser1;
                    else
                        $nbrs=NULL;
                    if($nbrs==NULL)
                        echo "You Have No Neighbors :(";
                    elseif($nbrs!=false)
                        echo "<div class='row'> <div class='col-lg-3'><div class='msg-name'>".$nbrs."</div></div><div class='col-lg-9'></div></div>";
                        ?>
                    <?php
                }
                $query3->close();?>
            </div>
        </div>
        </div>
    </div>
</div>
</body>
</html>