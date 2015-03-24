<?php
include 'core/init.php';
$pageTitle = 'About';
//Hide sidebar on mobile
//Initialized to false in init
$hide = true;


//Layout
include 'includes/template/head.php';
include 'includes/template/contentHeading.php';
echo '<h2>About this project</h2>';
include 'includes/template/content.php';


?>
<p>Hey I'm Dylan Fontaine and this is a nifty little auction website I'm developing on my LAMP server.
    I am no php wizard but this website seems to be working so far.</p>

<?php
include 'includes/template/closeContent.php';


include 'includes/template/sidebarRight.php';
echo '<h2>Contact</h2>';
echo '<p>View this project on GitHub</p>';
include 'includes/template/closeSidebar.php';
include 'includes/template/foot.php';