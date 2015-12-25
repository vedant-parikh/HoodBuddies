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

$query2 = $mysqli->prepare('SELECT familyinfo,education FROM profile WHERE username=?');
$query2->bind_param('s', $username);
$query2->execute();
$query2->store_result();
$query2->bind_result($familyinfo, $education);
$value2 = $query2->fetch();
$query2->close();

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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
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
                    <li class="active"><a href='#'>Profile</a></li>
                    <li><a href='connections.php'>Connections</a></li>
                    <li><a href='maps.php'>Map</a></li>
                    <li><a href='messages.php'>Messages</a></li>
                </ul>
            </div>
            <hr class="menu-hr"/>
            <div class="profile-page">
                <div class="row">
                    <div class="col-lg-4">
                        <form action="blockapply.php" method="post">
                            <button type="submit" class="btn bg-btn" name="bapply">Change Block</button>
                        </form>

                    </div>
                    <form action="profile.php" method="post">
                    <div class="col-lg-8">
                        <div class="row">
                        <div class="col-lg-2">
                            <h5 class="profile-param">Firstname</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="firstname" value ="<?php echo $firstname ?>" required title="address is required"/>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-lg-2">
                            <h5 class="profile-param">Lastname</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="lastname" value ="<?php echo $lastname ?>" required title="address is required"/>
                        </div>
                            </div>
                        <div class="row">
                        <div class="col-lg-2">
                            <h5 class="profile-param">Gender</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <div>
                                <input type="radio" name="sex" value="male" checked>Male
                                <input type="radio" name="sex" value="female">Female
                            </div>
                        </div>
                            </div>
                        <div class="row">
                        <div class="col-lg-2">
                            <h5 class="profile-param">Address</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <h5 class="profile-param"><?php echo $address ?></h5>
                        </div>
                            </div>
                        <div class="row">
                        <div class="col-lg-2">
                            <h5 class="profile-param">Birthdate</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" id="birthdate" name="birthdate"
                                   placeholder="<?php echo $birthdate ?>" >
                        </div>
                            </div>
                        <div class="row">
                        <div class="col-lg-2">
                            <h5 class="profile-param">Email</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="email" value ="<?php echo $email ?>" required title="address is required"/>
                        </div>
                            </div>
                        <div class="row">
                        <div class="col-lg-2">
                            <h5 class="profile-param">Phone</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="phone" value ="<?php echo $phone ?>" required title="address is required"/>
                        </div>
                            </div>
                        <div class="row">
                        <div class="col-lg-2">
                            <h5 class="profile-param">Family Info</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="familyinfo" value ="<?php echo $familyinfo ?>" required title="address is required"/>
                        </div>
                            </div>
                        <div class="row">
                        <div class="col-lg-2">
                            <h5 class="profile-param">Education</h5>
                        </div>
                        <div class="col-lg-1"><h5>:</h5></div>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="education" value ="<?php echo $education ?>" required title="address is required"/>
                        </div>
                            </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn bg-btn" name="profileupdate">Update!</button>
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
<script>
    $(function () {
        $("#birthdate").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>
</html>