<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<!-- Generate site header used for every page -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/database_helpers.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/user.php');

// if user is logged in create user_obj variable
if (isset($_SESSION['username'])) {
    $user_obj = new User($con, $_SESSION['username']);
}
// if user is not logged in redirect back to register.php
else {
    header("Location: register.php");
    exit();
}
?>

<html>

<head>
    <title>Fitness Tracker</title>

    <!-- Set base url for links -->
    <base href="/" />

    <!-- JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="includes/js/profile_helper.js"></script>
    <script src="includes/js/messages.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="includes/css/style.css">
    <link rel="stylesheet" href="includes/css/training_history.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=DM+Serif+Display|Raleway|Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

</head>

<body>
    <!-- create nav bar which is used on every page-->
    <div class="top_bar">
        <label for="toggle">&#9776;</label>
        <input type="checkbox" id="toggle" />
        <div id="menu">
            <div class="logo">
                <nav>
                    <span id="top_bar_links_app_name" class="top_bar_links"><i class="fas fa-dumbbell"></i></i>Fitness Tracker<i class="fas fa-dumbbell"></i></span>
                    <span id="first_top_bar_link" class="top_bar_links"><i class="fas fa-plus"></i><a href="add_new_training.php">Add New Training</a></span>
                    <span class="top_bar_links"><i class="fas fa-history"></i><a href="training_history.php">Training History</a></span>
                    <span class="top_bar_links"><i class="fas fa-trophy"></i><a href="best_performances.php">Best Performances</a></span>
            </div>
        </div>
        <!-- show user picture, name and log out button -->
        <div id="nav_bar_profile">
            <a id='nav_bar_profile_link' href='profile.php'>
                <img id="nav_bar_profile_pic" src="<?php echo $user_obj->getProfilePicture(); ?>" alt="">
                <?php echo $user_obj->getFirstAndLastName(); ?>
            </a>
            <a id="sign-out-button" href="includes/handlers/logout_handler.php">
                <i class="fas fa-sign-out-alt"></i>
            </a>
            </nav>
        </div>
    </div>

    <div class="wrapper">