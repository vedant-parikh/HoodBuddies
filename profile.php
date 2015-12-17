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
            <a class="" href="index.php"><img class="logo-img" src="images/hoodicon.png" style="max-width: 50px; display: block; margin: 10px 0;" alt="Logo"><h4 class="logo-text">HoodBuddies</h4></a>
        </div>
        <h1 class="reg-heading">Sign Up</h1>
    </div>
</nav>
<div class="profile-page">

</div>
</body>
<script>
    var winHeight=$(window).height();
    var navHeight=$(".navbar").height();
    $(".registration-page").height(winHeight-navHeight);
</script>
<script>
    /* Google Maps geocoding API */
    var geocodingAPI = "http://maps.googleapis.com/maps/api/geocode/json?address=<?php echo $db_address ?>&sensor=true";
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
</html>