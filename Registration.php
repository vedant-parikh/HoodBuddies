<?php
/**
 * User: vedant
 */
session_start();
if(isset($_POST['submit']))
{
    $Flag=true;
    $Message="";
    $db_address = $_SESSION['address'];
    $address = str_replace(" ", "+", $db_address);
    $username=filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email=filter_var($_POST['reg-email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $Message=$Message."$email is not a valid email address<br>";
        $Flag=false;
    }
    $firstname=filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
    $lastname=filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    $pass=filter_var($_POST['reg-password'], FILTER_SANITIZE_STRING);
    if(!preg_match("/([A-Z-]{1,8})([a-z-]{1,8})([0-9-]{1,8})/",$pass))
    {
        $Message=$Message."Please Enter Valid Password<br>";
        $Flag=false;
    }
    $gender=filter_var($_POST['sex'], FILTER_SANITIZE_STRING);
    $birthdate=preg_replace("([^0-9-])", "", $_POST['birthdate']);
    $mobile=$_POST['phone'];
    if(!preg_match('/[0-9]{10}/',$mobile))
    {
        $Message=$Message."Please Enter Valid Mobile Number<br>";
        $Flag=false;
    }
    $mysqli = new mysqli("localhost", "root", "", "commcon");


//check if connection is a success
    if(mysqli_connect_errno())
    {
        die("Connection to database error:" . mysqli_connect_error() . "(" . mysqli_connect_errno(). ")" );
    }

    $call2=$mysqli->prepare('SELECT username FROM userdata WHERE username=?');
    $call2->bind_param('s', $username);
    $call2->execute();
    $call2->store_result();
    if($call2->num_rows>0)
    {
        $Message=$Message."Selected username already exists";
        $Flag=false;
    }
    $call2->close();
    //echo $Message;
    if($Flag)
    {
        $call=$mysqli->prepare('call usersignup(?,?,?,?,?,?,?,?,?)');
        $call->bind_param('ssssssssi', $username, $firstname, $lastname,$pass, $gender, $db_address,$birthdate,$email,$mobile);

        if (!$call->execute()) {
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }else {
            unset($_SESSION['address']);
            //$Mesage=$Message."Registeration Successful";
            $_SESSION['username']=$username;
            header("location:preapproval.php");
        }
        $call->close();
    }
    $mysqli->close();
}
else {
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
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="" href="#"><img class="logo-img" src="images/hoodicon.png" style="max-width: 50px; display: block; margin: 10px 0;" alt="Logo"><h4 class="logo-text">HoodBuddies</h4></a>
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
                <form role="form" action="registration.php" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" autofocus required title="Username is required">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="reg-email" name="reg-email" placeholder="Email Address" autofocus required title="Email Address is required">
                    </div>
                    <div class="form-group">
                        <div class="row">
                        <div class="col-lg-6">
                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Firstname" autofocus required title="Firstname is required">
                        </div>
                        <div class="col-lg-6">
                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Lastname" autofocus required title="Lastname is required">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="reg-password" name="reg-password" placeholder="Password" autofocus required title="Password is required">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="radio-btn">
                                <input type="radio" name="sex" value="male" checked>Male
                                <input type="radio" name="sex" value="female">Female
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="birthdate" name="birthdate" placeholder="Birth Date" autofocus required title="Birthdate is required">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number">
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn bg-btn">Apply!</button>
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
    var winHeight=$(window).height();
    var navHeight=$(".navbar").height();
    $(".registration-page").height(winHeight-navHeight);
    $(function(){
        $("#birthdate").datepicker({ dateFormat: 'yy-mm-dd' });
    });
</script>
<script>
    /* Google Maps geocoding API */
    var geocodingAPI = "http://maps.googleapis.com/maps/api/geocode/json?address=<?php echo $address ?>&sensor=true";
    console.log(geocodingAPI);
    $.getJSON(geocodingAPI, function (json) {

        // Set the variables from the results array
        var block = json.results[0].address_components[0].long_name+' and '+json.results[0].address_components[1].long_name;
        console.log(block);

        // Set the table td text
        <?php
        if(!isset($_POST['submit']))
            echo "$('#block').text('Your block is '+ block+'. Fill out the form below to register and apply.');";
        else
            echo "$('#block').html('".$Message."');";
        ?>
    });

    /* Google Maps geocoding API End*/
</script>
</html>