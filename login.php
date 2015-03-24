<?php
/**
 * Created by PhpStorm.
 * User: dylan
 * Date: 14/03/15
 * Time: 5:15 PM
 */

require_once 'core/init.php';
$pageTitle = 'Login';
//Hide sidebar on mobile
//Initialized to false in init
$hide = true;


//Layout
include 'includes/template/head.php';
include 'includes/template/sidebarLeft.php';
echo '<img src="images/marioplay.png" class="sideimg"/>';
include 'includes/template/closeSidebar.php';
include 'includes/template/contentHeading.php';
echo '<h2>Log in</h2>';
if(Session::exists('listing')){
    echo Session::flash('listing');
}
include 'includes/template/content.php';
$user = new User();
if($user->isLoggedIn()) {
    echo 'You are already logged in <a href="index.php">Home</a> <a href="logout.php">Logout</a>';
}
else {
    //Logging the user in
if(Input::exists()) {
    if(Token::check(Input::get('token'))) {

        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array('required' => true),
            'password' => array('required' => true)
        ));

        if($validation->passed()) {
            $user = new User();

            $remember = (Input::get('remember') === 'on')? true : false ;
            $login = $user->login(Input::get('username'), Input::get('password'), $remember);

            if($login) {

                Redirect::to('index.php');
            } else {
                echo '<p>There was an error logging you in. Please make sure you have entered the correct detais.</p>';
            }

        } else {
            $validation->print_errors();
        }

    }
}
?>

<form action="" method="post">
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username">
    </div>

    <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" autocomplete="off">
    </div>

    <div class="field">
        <label for="remember">
            <input type="checkbox" name="remember" id="remember"> Remember me
        </label>
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" class="submit" value="Login">
</form>
<?php
//close else
}
include 'includes/template/closeContent.php';
include 'includes/template/foot.php';