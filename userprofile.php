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
$query3 = $mysqli->prepare('SELECT firstname,lastname,gender,address,birthdate,email,phone FROM userdata WHERE username=?');
$query3->bind_param('s', $username);
$query3->execute();
$query3->store_result();
$query3->bind_result($firstname, $lastname, $gender, $address, $birthdate, $email, $phone);
$value = $query3->fetch();
$query3->close();

$otheruser = 'zealdalal';
if(isset($_POST['frequest'])){
    $appusername = $_POST['userprofile'];
    $query4 = $mysqli->prepare('INSERT INTO relation VALUES (?,?,"P")');
    $query4->bind_param('ss',$username,$appusername);
    $query4->execute();
    $query4->close();
}
elseif(isset($_POST['nrequest'])){
    $appusername = $_POST['userprofile'];
    $query4 = $mysqli->prepare('INSERT INTO relation VALUES (?,?,"N")');
    $query4->bind_param('ss', $appusername,$username);
    $query4->execute();
    $query4->close();
}

$query1 = $mysqli->prepare('SELECT firstname,lastname,username,email,familyinfo,education FROM userdata JOIN profile USING (username) WHERE username=?');
$query1->bind_param('s',$otheruser);
$query1->execute();
$query1->store_result();
$query1->bind_result($firstname1, $lastname1, $username1, $email1, $finfo1, $edu1);
$value = $query1->fetch();
$query1->close();

$query2 = $mysqli->prepare('SELECT blockname, neighborhood FROM (((ubmap JOIN block USING (blockid)) JOIN bnmap USING (blockid)) JOIN neighborhood USING (nhid))  WHERE username=?');
$query2->bind_param('s',$otheruser);
$query2->execute();
$query2->store_result();
$query2->bind_result($bname, $nname);
$value = $query2->fetch();
$query2->close();

$query3 = $mysqli->prepare('SELECT * FROM relation  WHERE (fromuser=? AND touser=?) OR (touser=? AND fromuser=?)');
$query3->bind_param('ssss',$username, $otheruser, $username, $otheruser);
$query3->execute();
$query3->store_result();
$query3->bind_result($fuser, $tuser, $type);
$value = $query3->fetch();
$query3->close();

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
                    <button type="submit" class="btn bg-btn" style="float: right" name="logout">Log Out</button>
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
            <div class="profile-page">
                <form action="userprofile.php" method="post">
                    <input type="hidden" name="userprofile" value="<?php echo $username1 ?>">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-lg-2">
                            <h5 class="profile-param">Firstname</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <h5 class="profile-param"><?php echo $firstname1 ?></h5>
                        </div>
                        <div class="col-lg-2">
                            <h5 class="profile-param">Lastname</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <h5 class="profile-param"><?php echo $lastname1 ?></h5>
                        </div>
                        <div class="col-lg-2">
                            <h5 class="profile-param">Username</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <h5 class="profile-param"><?php echo $username1 ?></h5>
                        </div>
                        <div class="col-lg-2">
                            <h5 class="profile-param">Email</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <h5 class="profile-param"><?php echo $email1 ?></h5>
                        </div>
                        <div class="col-lg-2">
                            <h5 class="profile-param">Family Info</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <h5 class="profile-param"><?php echo $finfo1 ?></h5>
                        </div>
                        <div class="col-lg-2">
                            <h5 class="profile-param">Education</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <h5 class="profile-param"><?php echo $edu1 ?></h5>
                        </div>
                        <div class="col-lg-2">
                            <h5 class="profile-param">Block</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <h5 class="profile-param"><?php echo $bname ?></h5>
                        </div>
                        <div class="col-lg-2">
                            <h5 class="profile-param">Neighborhood</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <h5 class="profile-param"><?php echo $nname ?></h5>
                        </div>
                        <div class="col-lg-12">
                        <?php
                        if($type!='F' && $type!='N' && $type!='R'){
                        ?>
                                <button type="submit" class="btn bg-btn" name="frequest">Add as Friend</button>
                                <button type="submit" class="btn bg-btn" name="nrequest">Add as Neighbor</button>
                        <?php
                        }
                        elseif($type=='N' && $type!='F' && $type!='R'){
                        ?>
                                <button type="submit" class="btn bg-btn" name="frequest">Add as Friend</button>
                        <?php
                        }
                        elseif($type=='F' && $type!='N' && $type!='R'){
                            ?>
                                <h4>You are friend of <?php echo $firstname ?></h4>
                        <?php
                        }
                        ?>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>