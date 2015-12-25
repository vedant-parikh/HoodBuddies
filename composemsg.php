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


/*Stored Procedure:

Create PROCEDURE sendmessage
(IN mfrom VARCHAR(30), IN mto VARCHAR(30),IN mtype INT(10), IN mtitle VARCHAR(50), IN message VARCHAR(250))
INSERT INTO `conversation`INSERT INTO `conversation` VALUES (mfrom,mto,NOW(),mtype,mtitle,message,'1');*/
$msgto="vedantparikh";
$mtitle="Hi";
$message="Hello";
//msgtype is decided by grp msg or nbr msg or frn msg or blk msg chosen
$msgtype=5;

if($msgtype==1)
{
    $query2 = $mysqli->prepare('CALL sendmessage(?,?,?,?,?)');
    $query2->bind_param('sssss', $username, $msgto, $msgtype, $mtitle, $message);
    $query2->execute();
    $query2->close();
}

if($msgtype==3)
{
    $query3 = $mysqli->prepare('SELECT fromuser, touser FROM relation WHERE fromuser=? OR touser=? AND type="N"');
    $query3->bind_param('ss', $username, $username);
    $query3->execute();
    $query3->store_result();
    $query3->bind_result($fromuser, $touser);
    while($query3->fetch())
    {
       if($fromuser==$username)
           $msgto=$touser;
        else
            $msgto=$fromuser;
        $query2 = $mysqli->prepare('CALL sendmessage(?,?,?,?,?)');
        $query2->bind_param('ssiss', $username, $msgto, $msgtype, $mtitle, $message);
        $query2->execute();
        $query2->close();
    }
    $query3->close();
}

if($msgtype==4)
{
    $query4 = $mysqli->prepare('SELECT fromuser, touser FROM relation WHERE fromuser=? OR touser=? AND type="F"');
    $query4->bind_param('ss', $username, $username);
    $query4->execute();
    $query4->store_result();
    $query4->bind_result($fromuser, $touser);
    while($query4->fetch())
    {
        if($fromuser==$username)
            $msgto=$touser;
        else
            $msgto=$fromuser;
        $query5 = $mysqli->prepare('CALL sendmessage(?,?,?,?,?)');
        $query5->bind_param('ssiss', $username, $msgto, $msgtype, $mtitle, $message);
        $query5->execute();
        $query5->close();
    }
    $query4->close();
}

if($msgtype==5)
{
    $query8 = $mysqli->prepare('SELECT nhid FROM ubmap JOIN bnmap USING(blockid) WHERE username=?');
    $query8->bind_param('s', $username);
    $query8->execute();
    $query8->store_result();
    $query8->bind_result($nid);
    $value8 = $query8->fetch();
    $query8->close();

    $query6 = $mysqli->prepare('SELECT username FROM ubmap LEFT OUTER JOIN bnmap USING(blockid) WHERE nhid=?');
    $query6->bind_param('i', $nid);
    $query6->execute();
    $query6->store_result();
    $query6->bind_result($touser);

    while($query6->fetch())
    {
        $query7 = $mysqli->prepare('CALL sendmessage(?,?,?,?,?)');
        $query7->bind_param('ssiss', $username, $touser, $msgtype, $mtitle, $message);
        $query7->execute();
        $query7->close();
    }
    $query6->close();
}

if (isset($_POST['submit'])) {
    session_unset();
    session_destroy();
}
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
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
                    <li><a href='connections.php'>Connections</a></li>
                    <li><a href='maps.php'>Map</a></li>
                    <li class='active'><a href='#'>Messages</a></li>
                </ul>
            </div>
            <hr class="menu-hr"/>
            <div class="profile-page">
                <form action="profile.php" method="post">
                    <div class="row">
                        <div class="col-lg-4">
                            <!-- <img id="profileimage" src="#" alt="your image" />
                             <input type='file' onchange="readURL(this);" /> -->

                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-2">
                                    <h5 class="profile-param">To:</h5>
                                </div>
                                <div class="col-lg-1"><h5>:</h5></div>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="firstname" value ="<?php echo $firstname ?>" required title="address is required"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2">
                                    <h5 class="profile-param">Title</h5>
                                </div>
                                <div class="col-lg-1"><h5>:</h5></div>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="lastname" value ="<?php echo $lastname ?>" required title="address is required"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2">
                                    <h5 class="profile-param">Message</h5>
                                </div>
                                <div class="col-lg-1"><h5>:</h5></div>
                                <div class="col-lg-9">
                                    <textarea type="text" class="form-control" name="address" value ="<?php echo $address ?>" required title="address is required"/></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn bg-btn" name="sendMessage">Send!</button>
                                </div>
                            </div>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>