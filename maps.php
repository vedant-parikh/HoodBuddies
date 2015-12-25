<?php
session_start();
$link=$_SERVER['PHP_SELF'];
if (!isset($_SESSION['username']))
    header("location:login.php?link=".$link);
$username = $_SESSION['username'];
$mysqli = new mysqli("localhost", "root", "", "commcon");
//check if connection is a success
if(mysqli_connect_errno())
{
    die("Connection to database error:" . mysqli_connect_error() . "(" . mysqli_connect_errno(). ")" );
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

$query1=$mysqli->prepare('SELECT firstname,lastname,gender,address,birthdate,email,phone FROM userdata WHERE username=?');
$query1->bind_param('s', $username);
$query1->execute();
$query1->store_result();
$query1->bind_result($firstname,$lastname,$gender,$address,$birthdate,$email,$phone);
$value =  $query1->fetch();
$query1->close();

$query2=$mysqli->prepare('SELECT latitude,longitude FROM latlong WHERE username=?');
$query2->bind_param('s', $username);
$query2->execute();
$query2->store_result();
$query2->bind_result($lat,$long);
$value2 =  $query2->fetch();
$query2->close();
$latrange = intval($lat);
$longrange = intval($long);

$latrangen = $latrange + 10;
$longrangen = $longrange + 10;

$latranges = $latrange - 10;
$longranges = $longrange - 10;

$query3=$mysqli->prepare('SELECT latitude,longitude FROM userdata WHERE username=?');
$query3->bind_param('s', $username);
$query3->execute();
$query3->store_result();
$query3->bind_result($firstname,$lastname,$gender,$address,$birthdate,$email,$phone);
$value =  $query3->fetch();
$query3->close();

if(isset($_POST['submit']))
{
    session_unset();
    session_destroy();
}
?>
<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        #google_map {width: 1200px; height: 600px;margin-top:50px;margin-left:auto;margin-right:auto;}
    </style>
    <title>HoodBuddies</title>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Indie+Flower' rel='stylesheet' type='text/css'>
    <link rel="icon" sizes="180x180" href="images/apple-icon-180x180.png">
    <script type="text/javascript"
            src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAe_SOKgsIY6U-TZwhI2xU5yg5Rg7t0ldc&sensor=false">
    </script>
    <script type="text/javascript">

        var mapCenter = new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $long; ?>); //Google map Coordinates
        var map
        function initialize() //function initializes Google map
        {
            var googleMapOptions =
            {
                center: mapCenter, // map center
                zoom: 15, //zoom level, 0 = earth view to higher value
                panControl: true, //enable pan Control
                zoomControl: true, //enable zoom control
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL //zoom control size
                },
                scaleControl: true, // enable scale control
                mapTypeId: google.maps.MapTypeId.ROADMAP // google map type
            };
            map = new google.maps.Map(document.getElementById("google_map"), googleMapOptions);
        }

        function addMyMarker() { //function that will add markers on button click
            var marker = new google.maps.Marker({
                position:mapCenter,
                map: map,
                draggable:true,
                animation: google.maps.Animation.DROP,
                title:"This a new marker!",
                icon: "http://maps.google.com/mapfiles/ms/micons/blue.png"
            });
        }
    </script>
</head>

<body onLoad="initialize()">
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
                <h4 class="reg-heading">Welcome <?php echo $firstname ?>!</h4>
                <form action="login.php" method="post">
                    <button type="submit" class="btn bg-btn" name="logout" style="float: right">Log Out</button>
                </form>
            </div>
        </div>
</nav>
<div id="google_map" ></div><button id="drop" onClick="addMyMarker()">Drop Markers</button>
</body>
</html>