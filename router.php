<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/handlers/register_handler.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/handlers/login_handler.php');

// redirect user if he is logged in to profile.php if is not to register.php
if(!empty($_SESSION)){ 
    header("Location: profile.php");
}
else {
    header("Location: register.php");
}

?>