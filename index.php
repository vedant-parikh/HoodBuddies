<?php
session_start();
if (isset($_POST['login'])) {
    $Flag = true;
    $Message = "";
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $pass = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    if (!preg_match("/([A-Z-]{1,8})([a-z-]{1,8})([0-9-]{1,8})/", $pass)) {
        $Message = $Message . "Please Enter Valid Password<br>";
        $Flag = false;
    }
    $mysqli = new mysqli("localhost", "root", "", "commcon");
    if (mysqli_connect_errno()) {
        die("Connection to database error:" . mysqli_connect_error() . "(" . mysqli_connect_errno() . ")");
    }
    $call2 = $mysqli->prepare('SELECT username, password FROM userdata WHERE username=?');
    $call2->bind_param('s', $username);
    $call2->execute();
    $call2->store_result();
    if ($call2->num_rows > 0) {
        $call2->bind_result($username, $password);
        while ($call2->fetch()) {
            if ($password == $pass) {
                $_SESSION['username'] = $username;
                header("location:home.php");
                break;
            } else {
                $Message = $Message . "Password does not match. Please try again";
                break;
            }
        }
    } else {
        $Message = $Message . "No user record found. Please Sign-Up";
    }


    $call2->close();
} else {
    $Message = "Please enter your address";
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
        <div id="navbar" class="navbar-collapse collapse">
            <form action="index.php" method="post">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <div class="form-group">
                            <input type="text" class="form-control" id="usr" placeholder="Username" name="username">
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <input type="password" class="form-control" id="usr" placeholder="Password" name="password">
                        </div>
                    </li>
                    <li>
                        <button type="submit" name="login" class="btn bg-btn">Login</button>
                    </li>
                </ul>
            </form>

        </div>
    </div>
</nav>
<div class="main-page">
    <div class="container">
        <div class="main-page-signup">
            <div class="row">
                <div class="col-lg-12">
                    <form role="form" action="registration.php" method="post" id="add_reg">
                        <div class="form-group">
                            <label for="address"><?php echo $Message ?></label>
                            <input type="text" class="form-control" id="add-1" name="add-1" placeholder="Address Line 1"
                                   autofocus required title="Address 1 field is required.">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="add-2" name="add-2" placeholder="Addess Line 2">
                        </div>
                        <div class="form-group city-state">
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="city" name="city" placeholder="City"
                                           autofocus required title="City field is required">
                                </div>
                                <div class="col-lg-2">
                                    <select name="state" class="form-control">
                                        <option value="AL">AL</option>
                                        <option value="AK">AK</option>
                                        <option value="AZ">AZ</option>
                                        <option value="AR">AR</option>
                                        <option value="CA">CA</option>
                                        <option value="CO">CO</option>
                                        <option value="CT">CT</option>
                                        <option value="DE">DE</option>
                                        <option value="DC">DC</option>
                                        <option value="FL">FL</option>
                                        <option value="GA">GA</option>
                                        <option value="HI">HI</option>
                                        <option value="ID">ID</option>
                                        <option value="IL">IL</option>
                                        <option value="IN">IN</option>
                                        <option value="IA">IA</option>
                                        <option value="KS">KS</option>
                                        <option value="KY">KY</option>
                                        <option value="LA">LA</option>
                                        <option value="ME">ME</option>
                                        <option value="MD">MD</option>
                                        <option value="MA">MA</option>
                                        <option value="MI">MI</option>
                                        <option value="MN">MN</option>
                                        <option value="MS">MS</option>
                                        <option value="MO">MO</option>
                                        <option value="MT">MT</option>
                                        <option value="NE">NE</option>
                                        <option value="NV">NV</option>
                                        <option value="NH">NH</option>
                                        <option value="NJ">NJ</option>
                                        <option value="NM">NM</option>
                                        <option value="NY">NY</option>
                                        <option value="NC">NC</option>
                                        <option value="ND">ND</option>
                                        <option value="OH">OH</option>
                                        <option value="OK">OK</option>
                                        <option value="OR">OR</option>
                                        <option value="PA">PA</option>
                                        <option value="RI">RI</option>
                                        <option value="SC">SC</option>
                                        <option value="SD">SD</option>
                                        <option value="TN">TN</option>
                                        <option value="TX">TX</option>
                                        <option value="UT">UT</option>
                                        <option value="VT">VT</option>
                                        <option value="VA">VA</option>
                                        <option value="WA">WA</option>
                                        <option value="WV">WV</option>
                                        <option value="WI">WI</option>
                                        <option value="WY">WY</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" name="zip-code" class="form-control" id="zip-code"
                                           placeholder="Zip-Code" autofocus required title="City field is required">
                                    <div id="er3"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn bg-btn">Sign Up!</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    var winHeight = $(window).height();
    var navHeight = $(".navbar").height();
    $(".main-page").height(winHeight - navHeight);
    var mainPageSignupHeight = (winHeight / 2) - ($('.main-page-signup').height())
    $(".main-page-signup").css("margin-top", mainPageSignupHeight + "px");
</script>
</html>



