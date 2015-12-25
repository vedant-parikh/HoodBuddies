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

$query1 = $mysqli->prepare('SELECT firstname FROM userdata WHERE username=?');
$query1->bind_param('s', $username);
$query1->execute();
$query1->store_result();
$query1->bind_result($firstname);
$value = $query1->fetch();
$query1->close();

$query4 = $mysqli->prepare('SELECT * FROM relation WHERE touser=? AND type="P"');
$query4->bind_param('s', $username);
$query4->execute();
$query4->store_result();
$query4->bind_result($fromuser1,$touser1, $type);
?>
<?php
while ($query4->fetch()) {
?>
<div class="row">
    <form action="pendingrequest.php" method="post">
        <div class="col-lg-3">
            <div class="msg-name">
                <input type="hidden" class="hidden" name="fromuser1" value="<?php echo $fromuser1 ?>"/>
                <input type="hidden" class="hidden" name="touser1" value="<?php echo $touser1 ?>"/>
                <h4><a href="userprofile.php"><?php echo $fromuser1 ?></a></h4>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="msg-text">
                <h5 class="title">Hi! I would like to add you as friend.</h5>
                <div class="row">
                    <div class="col-lg-offset-6 col-lg-3">
                        <button class="btn bg-btn" name="acceptf">Accept</button>
                    </div>
                    <div class="col-lg-3">
                        <button class="btn bg-btn" name="rejectf">Reject</button>
                    </div>
                </div>
    </form>
    <hr class="menu-hr">
    <div class="margin-bottom"></div>
</div>
</div>
<div class="col-lg-3">
</div>
</div>
<?php

}
$query4->close();

$query2 = $mysqli->prepare('SELECT * FROM brequest WHERE approvaltype <> "A" AND blockid=(select blockid from ubmap where username=?)');
$query2->bind_param('s', $username);
$query2->execute();
$query2->store_result();
$query2->bind_result($requestid, $fromuser, $blockid, $approvaltype);
?>
<?php
while ($query2->fetch()) {
    $query3 = $mysqli->prepare('SELECT COUNT(appusername) FROM bresponse WHERE appusername=? and requestid=?');
    $query3->bind_param('si', $username,$requestid);
    $query3->execute();
    $query3->store_result();
    $query3->bind_result($count);
    $value = $query3->fetch();
    $query3->close();
    if($count>0)
        continue;
    ?>
    <div class="row">
        <form action="pendingrequest.php" method="post">
        <div class="col-lg-3">
            <div class="msg-name">
                <input type="hidden" class="hidden" name="requestid" value="<?php echo $requestid ?>"/>
                <input type="hidden" class="hidden" name="appusername" value="<?php echo $username ?>"/>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="msg-text">
                <h5 class="title">Hi! I am your neighbor. I would like to connect with you and other block members. Please accept my request.</h5>
                <div class="row">
                <div class="col-lg-offset-6 col-lg-3">
                    <button class="btn bg-btn" name="acceptp">Accept</button>
                </div>
                <div class="col-lg-3">
                    <button class="btn bg-btn" name="rejectp">Reject</button>
                </div>
                </div>
        </form>
                <hr class="menu-hr">
                <div class="margin-bottom"></div>
            </div>
        </div>
        <div class="col-lg-3">
        </div>
    </div>
    <?php

}
$query2->close();
?>
