<?php
/**
 * Browse the active listings
 */
require_once 'core/init.php';
$pageTitle = 'Browse Listings';


//Retrieve the listings
$listing = new Listing();
if(Input::exists('get')) {

    $category = $_GET['category'];
    if($category != 'all') {
            $results = $listing->getListings($category);
    } else {
        $results = $listing->getListings();
    }

} else {
    $results = $listing->getListings();
    $category = 'View All';
}


$category = ucwords($category);
//add the category to page title
$pageTitle .= ' - ' . $category;


//ALL SORTS OF SORTS
if(isset($_POST['sort'])){
    //The sorts given category
    if(isset($_GET['category'])) {
        $sort = $_POST['sort'];
        if ($sort == 'priceAsc') {
            $results = $listing->getListingsByPriceAsc($category);
        } else if ($sort == 'priceDesc') {
            $results = $listing->getListingsByPriceDesc($category);
        } else if ($sort == 'titleAsc') {
            $results = $listing->getListingsByTitleAsc($category);
        } else if ($sort == 'titleDesc') {
            $results = $listing->getListingsByTitleDesc($category);
        } else if ($sort == 'timeAsc') {
            $results = $listing->getListingsByTimeAsc($category);
        } else if ($sort == 'timeDesc') {
            $results = $listing->getListingsByTimeDesc($category);
        }
    } else {
        //The sorts given category
        $sort = $_POST['sort'];
        if($sort == 'priceAsc'){
            $results = $listing->getListingsByPriceAsc();
        } else if($sort == 'priceDesc'){
            $results = $listing->getListingsByPriceDesc();
        } else if($sort == 'titleAsc'){
            $results = $listing->getListingsByTitleAsc();
        } else if($sort == 'titleDesc'){
            $results = $listing->getListingsByTitleDesc();
        } else if($sort == 'timeAsc'){
            $results = $listing->getListingsByTimeAsc();
        } else if($sort == 'timeDesc'){
            $results = $listing->getListingsByTimeDesc();
        }
    }
}






//Layout
include 'includes/template/head.php';

include 'includes/template/sidebarLeft.php';?>
<h2>Choose a category</h2>

<ul class="sideNav">
    <li><a href="browse.php">All</a></li>
    <li><a href="browse.php?category=accessories">Accessories</a></li>
    <li><a href="browse.php?category=computers">Computers</a></li>
    <li><a href="browse.php?category=consoles">Consoles</a></li>
    <li><a href="browse.php?category=games">Games</a></li>
    <li><a href="browse.php?category=handhelds">Handhelds</a></li>
    <li><a href="browse.php?category=merchandise">Merchandise</a></li>
</ul>
<?php
include 'includes/template/closeSidebar.php';
include 'includes/template/contentHeading.php';
echo '<h2>' . $category . '</h2>';


?>

<form action="" method="post">
    <div class="field">
        <label for="sort">Sort by</label>
        <select name="sort" id="sort">
            <option value="titleAsc">Title: A-Z</option>
            <option value="titleDesc">Title: Z-A</option>
            <option value="priceAsc">Price: Lowest to highest</option>
            <option value="priceDesc">Price: Highest to lowest</option>
            <option value="timeDesc">Time: ending soonest</option>
            <option value="timeAsc">Time: ending latest</option>
        </select>
    </div>
    <input type="submit" value="Go">

</form>

<?php
include 'includes/template/content.php';
//Output the listings
echo '<table class="browse">';

    foreach($results as $result){

        $tempListing = new Listing($result->id);

        echo '<tr>';
        echo '<td class="browseCell"><a href ="view.php?listing=' . $result->id. '"><img class="browseimg" src ="' .
            $result->picture .'"></td>';
        echo '<td><h4><a href ="view.php?listing=' . $result->id. '">' . $result->title . '</a></h4><br>$'
            . number_format($tempListing->getHighestBid(), 2) .'</td>';
        echo '<td>' . $tempListing->timeLeft() . ' left</td>';
        echo '</tr>';
    }




    ?>
</table>
<?php
include 'includes/template/closeContent.php';
include 'includes/template/foot.php';