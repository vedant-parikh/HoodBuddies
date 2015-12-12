<?php
/**
 * User: vedant
 */
$input=$_POST['add-1'].'+'.$_POST['city'].'+'.$_POST['state'].'+'.$_POST['zip-code'];
$address=str_replace(" ", "+", $input);
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
                <form role="form">
                    <div class="form-group">
                        <input type="text" class="form-control" id="username" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="reg-email" placeholder="Email Address">
                    </div>
                    <div class="form-group">
                        <div class="row">
                        <div class="col-lg-6">
                            <input type="text" class="form-control" id="firstname" placeholder="First Name">
                        </div>
                        <div class="col-lg-6">
                        <input type="text" class="form-control" id="lastname" placeholder="Last Name">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="reg-password" placeholder="Password">
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
                                <input type="text" class="form-control" id="birthdate" placeholder="Birth Date">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="phone" placeholder="Phone Number">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn bg-btn">Apply!</button>
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
<script>
    var winHeight=$(window).height();
    var navHeight=$(".navbar").height();
    $(".registration-page").height(winHeight-navHeight);
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
       $('#block').text('Your block is '+ block+'. Fill out the form below to register and apply.');
    });

    /* Google Maps geocoding API End*/
</script>
</body>