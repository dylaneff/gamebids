<?php
/**
 * View a listing
 */
require_once 'core/init.php';

$user = new User();
//status message for the submitted bid
$bidStatus = null;


//retrieve the listing based on get
if (!$listing_id = Input::get('listing')) {
    Redirect::to('index.php');
} else {
    $listing = new Listing($listing_id);
    if(!$listing->exists()) {
        Redirect::to(404);
    } else {
        $data = $listing->data();
    }
}
//create the bid
if(Input::exists()) {
    if (Token::check(Input::get('token'))){
        $submitBid = floatval(Input::get('bid'));
        //use the current time to check if a bid is allowed to be placed
        $now = date("Y-m-d H:i:s");
        $endTime = $listing->getEndTime();
        if($now > $endTime){
            echo 'Smoke crack';
        }
        //use the current time to check if a bid is allowed to be placed
        $now = date("Y-m-d H:i:s");
        $endTime = $listing->getEndTime();
        if($now > $endTime){
            echo 'This listing is over';
        } else if($listing->checkBid($submitBid)) {
            $bidStatus = '<span class="accept">Your bid was placed</span>';
            $bid = new Bid();
            $time = new DateTime();
            $time = $time->format('Y-m-d H:i:s');
            try{
                $bid->create(array(
                    'listing_id' => $listing_id,
                    'username' => $user->data()->username,
                    'amount' => $submitBid,
                    'time' => $time
                ));


            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            $bidStatus = '<span class="error">Please place a higher bid</span>';
        }

    }
}


$pageTitle = $listing->data()->title;

//Layout
include 'includes/template/head.php';
//Ad title
include 'includes/template/contentHeading.php';

//Flash on creation of listing
if(Session::exists('view')){
    echo Session::flash('view');
}

echo '<h2>' . $listing->data()->title . '</h2>';
include 'includes/template/content.php';

//Output the listings info


echo '<img src="' . $listing->data()->picture . '" class="viewListing" /></p>';
echo '<p>' . $listing->data()->description . '</p>';
echo '<p>Category: ' . ucwords($listing->data()->category) . '</p>';
echo '<p>Seller: ' . $listing->getOwner() . '</p>';


//DISPLAY TIME LEFT
if(!$listing->listingEnded()) {
    ?>
<strong><span id="timeleft">Time left:</span></strong><br>
<span id="days"></span><span id="hours"></span><span id="minutes"></span><span id="seconds"></span>

<script src="js/timer.js"></script>
<script>
    countdown('<?php echo $listing->getEndTimeString(); ?>',
            ['days', 'hours', 'minutes', 'seconds'],
        function() { console.log('Finished') });
</script>
<?php
} else {
    echo '<p><strong> This listing has ended </strong></p>';
}

include 'includes/template/closeContent.php';

//Side bar with bids
include 'includes/template/sidebarRight.php';



if($listing->listingEnded()){
    if(!$listing->listingSold()) {
        echo '<h2>Listing Ended</h2>';
    } else {
        echo '<h2>Sold</h2>';
        echo 'Winner: ' .$listing->getHighestBidder();
        echo '<br>Amount: $' . number_format($listing->getHighestBid(), 2);
    }
} else {

    echo '<h2> Bid on this</h2>';
    echo ($listing->hasBids() ? 'Current' : 'Starting') . ' bid: $' . number_format($listing->getHighestBid(), 2) . '<br>';
    if (!$listing->reservePriceMet()) {
        echo '<span class="error">Reserve price not met</span><br>';
    }
    if($user->isLoggedIn()) {
        if ($user->data()->id == $listing->data()->owner_id) {
            echo 'You are viewing your auction';
        } else {?>
            <form action="" method="post">

                <div class="field">
                    <label for="bid">Place a bid</label>
                    <p class="info">Minimum bid: $<?php echo number_format($listing->getMinimumBid(), 2); ?> </p>
                    <input type="text" name="bid" id="bid">
                </div>
                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                <input type="submit" value="Bid" class="submit" id="placeBid">
            </form>


            <?php
            echo $bidStatus;

        }

    } else {
        echo 'You must <a href="login.php">login</a> or <a href="register.php">register</a> to bid.';
    }


//Output the bid history
    echo '<h4>Bid history</h4>';

    $historyHead = '<table class="history"><tr><td>Amount</td><td>Time</td></tr>';
    $historyBody = '';
    $historyFoot = '</table>';

    if ($listing->hasBids()) {
        foreach ($listing->getHistory() as $tempBid) {
            //using this var to check if the history is empty
            $tempArray = (array)$tempBid;

            if (!empty($tempArray)) {
                //format the number and date
                $bidAmount = '$' . number_format($tempBid->amount, 2);
                $tempTime = new DateTime($tempBid->time);
                $bidTime = $tempTime->format('M d g:i A');

                $historyBody .= '<tr><td>' . escape($bidAmount) . '</td><td>' . $bidTime . '</td></tr>';
            }


        }
        echo $historyHead;
        echo $historyBody;
        echo $historyFoot;
    } else {
        echo 'There are no bids yet';
    }
}

include 'includes/template/closeSidebar.php';
include 'includes/template/foot.php';
