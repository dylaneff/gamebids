<?php
/**
 * The users profile
 *
 *
 */
require_once 'core/init.php';

if (!$username = Input::get('user')) {
    Redirect::to('index.php');
} else {
    $user = new User($username);
    if(!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
    }
}


$pageTitle = $username . '\'s profile';

//Layout
include 'includes/template/head.php';


if(!$user->isLoggedIn()){
    include 'includes/template/sidebarLeft.php';
    echo '<h2>User Profile</h2>';
} else {
    include 'includes/template/sidebarAccount.php';
}

include 'includes/template/closeSidebar.php';
include 'includes/template/contentHeading.php';
echo '<h3>User: ' . $data->username . '</h3>';
include 'includes/template/content.php';




    echo '<p>Name:' . $data->name . '</p>';


include 'includes/template/closeContent.php';
include 'includes/template/foot.php';