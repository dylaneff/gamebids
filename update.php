<?php
/**
 * Update the users details
 */
require_once 'core/init.php';
$user = new User();
if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

$pageTitle = 'Account Information';

//Layout
include 'includes/template/head.php';
include 'includes/template/sidebarAccount.php';
include 'includes/template/closeSidebar.php';
include 'includes/template/contentHeading.php';
echo '<h2>Update your account information</h2>';
include 'includes/template/content.php';





if(Input::exists()) {
    if(Token::check(Input::get('token'))){

        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            )
        ));

        if($validation->passed()){



            try {
                $user->update([
                    'name' => Input::get('name')
                ]);

                Session::flash('home', 'Your information has been updated.');
                Redirect::to('index.php');

            } catch(Exception $e) {
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
        <label for="name">Name</label>
        <input type="text" name="name" value="<?php echo $user->data()->name; ?>">
        <br>
        <input type="submit" class="submit" value="Update">
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    </div>
</form>

<?php
include 'includes/template/closeContent.php';
include 'includes/template/foot.php';