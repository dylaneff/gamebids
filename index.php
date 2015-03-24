<?php
/**
 * The index page of the website
 */
require_once 'core/init.php';

$pageTitle = 'Home';
//Hide sidebar on mobile
//Initialized to false in init
$hide = true;


//Layout
include 'includes/template/head.php';
include 'includes/template/sidebarLeft.php';
echo '<img src="images/dangerous.bmp" class="sideimg"/>';
include'includes/template/closeSidebar.php';
include 'includes/template/contentHeading.php';
echo '<h2>Welcome to Game Bids</h2>';
include 'includes/template/content.php';


if(Session::exists('home')) {
    echo '<p>' . Session::flash('home') . '</p>';
}

if(Session::exists('listingcreated')) {
    echo '<p>' . Session::flash('listingcreated') . '</p>';
}

$user = new User();
if($user->isLoggedIn()) {

    echo '<p>Welcome back, <a href="profile.php?user=' . $user->data()->username . '">' . $user->data()->username . '</a></p>';
    echo '<ul>';
    echo '<li><a href="profile.php?user=' . $user->data()->username . '">View/Update Account</a></li>';
    echo '<li><a href="newlisting.php">Create a new listing</a></li>';
    echo '<li><a href="browse.php">Browse listings</a></li>';
    echo '<li><a href="logout.php">Log out</a></li>';
    echo '</ul>';


} else {
    echo '<p>This is an auction website for gamers to buy and sell their gaming related gear.
            If you are new here please <a href="register.php">register here</a>.</p>';
    echo '<p>If you are a returning user <a href="login.php">login here</a>.</p>';
}

include 'includes/template/closeContent.php';
include 'includes/template/foot.php';