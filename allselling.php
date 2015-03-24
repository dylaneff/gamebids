<?php
/******
 * Page that lists all of users listings
 *
 */

require_once 'core/init.php';
$pageTitle = 'All Selling';

$user = new User();
if(!$user->isLoggedIn()) {
    Session::flash('listing', 'You have need to log in to view this page.');
    Redirect::to('login.php');
}


//Retrieve the listings
$listing = new Listing();
$results = $user->getListings(0);
$activeResults = $user->getListings();





//Layout
include 'includes/template/head.php';

include 'includes/template/sidebarAccount.php';
include 'includes/template/closeSidebar.php';
include 'includes/template/contentHeading.php';
echo '<h2>Selling</h2>';
include 'includes/template/content.php';
echo '<table class="browse">';

    foreach($activeResults as $result){

        $tempListing = new Listing($result->id);

        echo '<tr>';
        echo '<td class="browseCell"><a href ="view.php?listing=' . $result->id. '"><img class="browseimg" src ="' .
            $result->picture .'"></td>';
        echo '<td><h4><a href ="view.php?listing=' . $result->id. '">' . $result->title . '</a></h4>';
        echo '</tr></a>';
    }
echo '</table>';
echo '<br><br>';

echo'<h2>Sold</h2>';
echo '<table class="browse">';

$count = 0;
foreach($results as $result){

    $tempListing = new Listing($result->id);
    if($tempListing->listingSold()) {
        echo '<tr>';
        echo '<td class="browseCell"><a href ="view.php?listing=' . $result->id . '"><img class="browseimg" src ="' .
            $result->picture . '"></td>';
        echo '<td><h4><a href ="view.php?listing=' . $result->id . '">' . $result->title . '</a></h4><br>Price: $'
            . number_format($tempListing->getHighestBid(), 2) .'</td>';
        echo '<td>Sold to: ' . $tempListing->getHighestBidder() . '</td>';
        echo '</tr>';

        $count += 1;
    }
}
if($count == 0){
    echo '<tr><h4>No listings</h4></tr>';
}
echo '</table>';
echo '<br><br>';

echo'<h2>Did not sell</h2>';
echo '<table class="browse">';

$count = 0;
foreach($results as $result) {

    $tempListing = new Listing($result->id);
    if (!$tempListing->listingSold()) {
        echo '<tr>';
        echo '<td class="browseCell"><a href ="view.php?listing=' . $result->id . '"><img class="browseimg" src ="' .
            $result->picture . '"></td>';
        echo '<td><h4><a href ="view.php?listing=' . $result->id . '">' . $result->title . '</a></h4><br>Highest price: $'
            . number_format($tempListing->getHighestBid(), 2) .'</td>';
        echo '</tr>';
        $count += 1;
    }
}
if($count == 0){
    echo '<tr><h4>No listings</h4></tr>';
}
echo '</table>';

include 'includes/template/closeContent.php';
include 'includes/template/foot.php';