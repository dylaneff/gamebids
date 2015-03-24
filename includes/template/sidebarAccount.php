<?php
/*****
 * Sidebar for the user account pages
 */

?>

<!--SIDE BAR-->
<aside class="sidebarLeft">
    <article>

    <h2>Your Account</h2>
<ul class="sideNav">
    <li><a href="profile.php?user=<?php echo $user->data()->id;?>">Profile</a></li>
    <li><a href="update.php">Update Account</a></li>
    <li><a href="changepassword.php">Change Password</a></li>
    <li><a href="../../allselling.php">All Selling</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>