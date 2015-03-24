<?php
/**
 * Change users password
 */
require_once 'core/init.php';

$user = new User();
if(!$user->isLoggedIn()){
    Redirect::to('index.php');
}

$pageTitle = 'Change Password';
//Layout
include 'includes/template/head.php';
include 'includes/template/sidebarAccount.php';
include 'includes/template/closeSidebar.php';
include 'includes/template/contentHeading.php';
echo '<h2>Change your password</h2>';
include 'includes/template/content.php';






if(Input::exists()){
    if(Token::check(Input::get('token'))) {

        $validate = new Validate();
        $validation = $validate->check($_POST,[
            'current_password' => [
                'required' => true,
                'min' =>6
            ],
            'password_new' => [
                'required' => true,
                'min' =>6
            ],
            'password_new_confirm' => [
                'required' => true,
                'min' =>6,
                'matches' => 'password_new'
            ]
        ]);

        if($validation->passed()) {
            if(Hash::make(Input::get('current_password'), $user->data()->salt) !== $user->data()->password){
                echo 'Your entered an incorrect password.';
            }else{
                $salt = Hash::salt(32);
                $user->update(array(
                    'password' => Hash::make(Input::get('password_new'), $salt),
                    'salt' => $salt
                ));
                Session::flash('success', 'Password changed successfully.');
                Redirect::to('index.php');
            }
        } else {
            $validation->print_errors();
        }

    }
}

?>

<form action="" method="post">
    <div class="field">
        <label for="current_password">Current password</label>
        <input type="password" name="current_password" id="current_password">
    </div>
    <div class="field">
        <label for="password_new">New password</label>
        <input type="password" name="password_new" id="password_new">
    </div>
    <div class="field">
        <label for="password_new_confirm">Confirm new password</label>
        <input type="password" name="password_new_confirm" id="password_new_confirm">
    </div>
    <input type="submit" class="submit" value="Change">
    <input type="hidden" name="token" value="<?php echo Token::generate() ;?>">
</form>

<?php
include 'includes/template/closeContent.php';
include 'includes/template/foot.php';