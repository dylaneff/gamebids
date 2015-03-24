<?php
/**
 * Create a listing
 */
require_once 'core/init.php';

$pageTitle = 'Create a new listing';
//Hide sidebar on mobile
//Initialized to false in init
$hide = true;




//Layout
include 'includes/template/head.php';
include 'includes/template/sidebarLeft.php';
echo '<img src="images/8bitmegaman.png" class="sideimg"/>';;
include 'includes/template/closeSidebar.php';
include 'includes/template/contentHeading.php';
echo '<h2>List an item</h2>';
echo '<p class="info">Fields marked with an asterisk * are required.</p>';
include 'includes/template/content.php';

$user = new User();
if(!$user->isLoggedIn()) {
    Session::flash('listing', 'You have need to log in to view this page.');
    Redirect::to('login.php');
}


//check for input
if(Input::exists()) {

    //check the token
    if (Token::check(Input::get('token'))){
        //Check the validation rules
        $validate = new Validate();
        $validation = $validate->check($_POST, array (
            'title' => array (
                'required' => true,
                'min' => 5,
                'max' => 80
            ),
            'description' => array (
                'required' => true,
                'min' => 10
            ),
            'start_price' => array(
                'required' => true,
                '>=' => 0
            )
        ));

        $pictureOK = false;
        //Check and upload image
        if($_FILES['picture']['error'] === 0){
            $file = $_FILES['picture'];
            if($validation->checkUpload($file)) {
                $target_file = $validation->checkUpload($file);
                if(move_uploaded_file($file['tmp_name'], $target_file)) {
                    $pictureOK = true ;
                } else {
                    echo '<span class="error">Sorry you file could not be uploaded. Please try again.</span>';
                    $pictureOK = false;
                }
            }
        } else {
            $pictureOK = true;
            $target_file = 'images/listings/placeholder.png';
        }



        if($validation->passed() && $pictureOK) {


            $listing = new Listing();
            try{

                //Set start date and end date of auction
                $duration = Input::get('duration');
                $start = new DateTimeImmutable();
                $end = $start->modify('+' . $duration . 'day');

                //insert the record
                $listing->create(array(
                    'owner_id' => $user->data()->id,
                    'title' => Input::get('title'),
                    'description' => Input::get('description'),
                    'category' => Input::get('category'),
                    'start_price' => Input::get('start_price'),
                    'reserve_price' => Input::get('reserve_price'),
                    'start_time' => $start->format('Y-m-d H:i:s'),
                    'end_time' => $end->format('Y-m-d H:i:s'),
                    'picture' => $target_file
                ));

                Session::flash('listingcreated', 'You\'re listing has been created!');
                Redirect::to('index.php');

            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            $validation->print_errors();
            $validation->print_IOErrors();
        }

    }
}

?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="field">
        <label for="title">Title (80 chars max) *</label><br>
        <input type="text" name="title" id="title"
               value="" autocomplete="off" spellcheck="true">
    </div>
    <div class="field">
        <label for="description">Description (250 chars max) *</label> <br>
        <textarea name="description" id="description" rows="5" cols="50" autocomplete="off" spellcheck="true"
            placeholder="Write your description here."></textarea>
    </div>
    <div class="field">
        <label for="category">Category *</label>
        <select name="category" id="category">
            <option value="accessories">Accessories</option>
            <option value="computers">Computers</option>
            <option value="consoles">Consoles</option>
            <option value="games">Games</option>
            <option value="handhelds">Handhelds</option>
            <option value="merchandise">Merchandise</option>
        </select>
    </div>
    <div class="field">
        <label for="start_price">Starting price * $</label>
        <input type="number" step="0.01" name="start_price" id="start_price">
    </div>
    <div class="field">
        <label for="reserve_price">Reserve price $</label>
        <input type="number" step="0.01" name="reserve_price" id="reserve_price">
    </div>

    <div class="field">
        <label for="duration">Duration *</label>
        <select name="duration" id="duration">
            <option value="3">3 days</option>
            <option value="5">5 days</option>
            <option value="7">7 days</option>
        </select>
    </div>
    <div class="field">
        <label for="picture">Picture </label>
        <input type="file" name="picture" id="picture">
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" class="submit" value="Submit">

</form>
<?php
include 'includes/template/closeContent.php';
include 'includes/template/foot.php';
