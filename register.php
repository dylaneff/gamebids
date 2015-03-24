<?php
/**
 * Registration page
 */
require_once 'core/init.php';

$pageTitle = 'Register';
//Hide sidebar on mobile
//Initialized to false in init
$hide = true;

//Layout
include 'includes/template/head.php';
include 'includes/template/sidebarLeft.php';
echo '<img src="images/donkey.jpg" class="sideimg"/>';
include 'includes/template/closeSidebar.php';
include 'includes/template/contentHeading.php';
echo '<h2>Register for an account</h2>';
echo '<p class="info">All fields are required</p>';
include 'includes/template/content.php';

if(Input::exists()) {
    if (Token::check(Input::get('token'))){
        $validate = new Validate();
        //Check rules for form
        $validation = $validate->check($_POST, array (
            'username' => array (
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users',
                'alphanumeric_' => true
            ),
            'password' => array (
                'required' => true,
                'min' => 6
            ),
            'confirm_password' => array(
                'required' => true,
                'matches' => 'password'
            ),
            'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50,
            )
        ));
        if($validation->passed()) {
            //register the user
            $user = new User();
            $salt = Hash::salt(32);
            try{
                $user->create(array(
                    'username' => Input::get('username'),
                    'password' => Hash::make(Input::get('password'), $salt),
                    'salt' => $salt,
                    'name' => Input::get('name'),
                    'groups' => 1
                ));

                Session::flash('home', 'You have been registered successfully!');
                Redirect::to('index.php');

            } catch (Exception $e) {
                die($e->getMessage());
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
        <input type="text" name="username" id="username" value="" autocomplete="off">
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>
    <div class="field">
        <label for="password_retype">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password">
    </div>
    <div class="field">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="">
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" class="submit" value="Register">

</form>
<?php

include 'includes/template/closeContent.php';
include 'includes/template/foot.php';