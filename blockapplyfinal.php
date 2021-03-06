<?php
/**
 * User: vedant
 */
session_start();
$link=$_SERVER['PHP_SELF'];
if (!isset($_SESSION['username']))
    header("location:login.php?link=".$link);
$username = $_SESSION['username'];
if (isset($_POST['finalsubmit'])) {
    $Flag = true;
    $Message = "";
    $db_address = $_SESSION['address'];
    $address = str_replace(" ", "+", $db_address);
    $block = $_POST['block'];
    $hood = $_POST['neighbor'];
    $mysqli = new mysqli("localhost", "root", "", "commcon");


//check if connection is a success
    if (mysqli_connect_errno()) {
        die("Connection to database error:" . mysqli_connect_error() . "(" . mysqli_connect_errno() . ")");
    }

    $call2 = $mysqli->prepare('SELECT username FROM userdata WHERE username=?');
    $call2->bind_param('s', $username);
    $call2->execute();
    $call2->store_result();
    $call2->close();
    //echo $Message;
    if ($Flag) {
        $call3 = $mysqli->prepare('SELECT blockname FROM block WHERE blockname=?');
        $call3->bind_param('s', $block);
        $call3->execute();
        $call3->store_result();

        if ($call3->num_rows < 1) {
            $call8 = $mysqli->prepare('INSERT INTO block (blockname) VALUES (?)');
            $call8->bind_param('s', $block);
            $call8->execute();
            $call8->close();
        }

        $call5 = $mysqli->prepare('SELECT neighborhood FROM neighborhood WHERE neighborhood=?');
        $call5->bind_param('s', $hood);
        $call5->execute();
        $call5->store_result();

        if ($call5->num_rows < 1) {

            $call7 = $mysqli->prepare('INSERT INTO neighborhood (neighborhood) VALUES (?)');
            $call7->bind_param('s', $hood);
            $call7->execute();
            $call7->close();
        }

        $call9 = $mysqli->prepare('SELECT blockid FROM bnmap WHERE blockid=(SELECT blockid FROM block WHERE blockname=?)');
        $call9->bind_param('s', $block);
        $call9->execute();
        $call9->store_result();

        $call10 = $mysqli->prepare('SELECT blockid FROM block WHERE blockname=?');
        $call10->bind_param('s', $block);
        $call10->execute();
        $call10->store_result();
        $call10->bind_result($blockid);
        $value10 = $call10->fetch();
        $call10->close();

        if ($call9->num_rows < 1) {
            $call11 = $mysqli->prepare('SELECT nhid FROM neighborhood WHERE neighborhood=?');
            $call11->bind_param('s', $hood);
            $call11->execute();
            $call11->store_result();
            $call11->bind_result($nhid);
            $value11 = $call11->fetch();
            $call11->close();

            $call12 = $mysqli->prepare('INSERT INTO bnmap VALUES (?,?)');
            $call12->bind_param('ii', $blockid, $nhid);
            $call12->execute();
            $call12->close();
        }

        if($mysqli->query('INSERT INTO brequest(fromuser,blockid,approvaltype) VALUES("'.$username.'","'.$blockid.'","P")')===FALSE){
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        } else {
            unset($_SESSION['address']);
           // $Message = $Message . "Registeration Successful";
            $_SESSION['username'] = $username;
            header("location:preapproval.php");
        }
        //$call->close();
    }
    $mysqli->close();
} else {
    /*if (empty($_POST['add-1']) || empty($_POST['city']) || empty($_POST['state']) || empty($_POST['zip-code'])) {
        echo "Please enter all fields of address<br/>";
        $Flag = false;
    }*/
    $input = filter_var($_POST['add-1'], FILTER_SANITIZE_STRING) . ' ' . filter_var($_POST['city'], FILTER_SANITIZE_STRING) . ' ' . filter_var($_POST['state'], FILTER_SANITIZE_STRING) . ' ' . filter_var($_POST['zip-code'], FILTER_SANITIZE_STRING);
    $address = str_replace(" ", "+", $input);
    $_SESSION['address'] = $input;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HoodBuddies</title>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
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
            <a class="" href="#"><img class="logo-img" src="images/hoodicon.png"
                                      style="max-width: 50px; display: block; margin: 10px 0;" alt="Logo"><h4
                    class="logo-text">HoodBuddies</h4></a>
        </div>
        <h1 class="reg-heading">Sign Up</h1>
    </div>
</nav>
<div class="registration-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="reg-page-signup">
                    <h4 id="block"></h4>
                    <form role="form" action="blockapplyfinal.php" method="post">
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="block1" name="block">
                        </div>
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="neighbor" name="neighbor">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="finalsubmit" class="btn bg-btn">Apply!</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="reg-page-signup">
                    <iframe
                        width="460"
                        height="395"
                        frameborder="0" style="border:0"
                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAe_SOKgsIY6U-TZwhI2xU5yg5Rg7t0ldc
    &q=<?php echo $address ?>">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</body>
<script>
    var winHeight = $(window).height();
    var navHeight = $(".navbar").height();
    $(".registration-page").height(winHeight - navHeight);
    $(function () {
        $("#birthdate").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>
<script>
    /* Google Maps geocoding API */
    var geocodingAPI = "http://maps.googleapis.com/maps/api/geocode/json?address=<?php echo $address ?>&sensor=true";
    console.log(geocodingAPI);
    $.getJSON(geocodingAPI, function (json) {

        // Set the variables from the results array
        var block = json.results[0].address_components[0].long_name + ' and ' + json.results[0].address_components[1].long_name;
        var neighborhood = json.results[0].address_components[2].long_name;
        document.getElementById("block1").value = block;
        document.getElementById("neighbor").value = neighborhood;
        //$('#neighbor').html(neighborhood);
        console.log(block);

        // Set the table td text
        <?php
        if (!isset($_POST['submit']))
            echo "$('#block').text('Your block is '+ block+'. Do you wish to proceed?');";
        else
            echo "$('#block').html('" . $Message . "');";
        ?>
    });

    /* Google Maps geocoding API End*/
</script>
</html>