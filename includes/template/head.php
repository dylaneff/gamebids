<?php
/**
 * Created by PhpStorm.
 * User: dylan
 * Date: 20/03/15
 * Time: 9:27 AM
 */
?>
<!DOCTYPE>
<html>
<head>
    <title>Game Bids - <?php echo $pageTitle; ?></title>
    <meta charset="UTF-8" />

    <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Press+Start+2P' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
</head>
</html>
<body>
    <!-- PAGE HEAD-->
    <header class="mainheader">
        <img src="images/LOGO.png" />
        <div id="user">
            <?php $user = new User();
            if($user->isLoggedIn()) {
                echo '<p>Hello, '. $user->data()->username.
                    '<br>View your <a href="profile.php?user=' .$user->data()->username . '">account</a> or
                    <a href="logout.php">logout</a></p>';
            } else {
                echo '<p>Hello, Guest.<a href="login.php"><br> Login</a> or <a href="register.php">register</a>.</p>';
            }?>




        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="browse.php">Buy</a></li>
                <li><a href="newlisting.php">Sell</a></li>
                <li><a href ="about.php">About</a></li>
            </ul>
        </nav>
    </header>
    <div class="wrapper">
    <!----Login for mobile devices-->
    <div class="mobilelogin">
        <?php $user = new User();
        if($user->isLoggedIn()) {
            echo '<ul><li><a href="profile.php?user=' .$user->data()->username . '">My Account</a></li>
                <li><a href="logout.php">Logout</a></li></ul>';
        } else {
            echo '<ul><li><a href="login.php">Login</a></li><li><a href="register.php">Register</a></li></ul>';
        }?>
       </div>


