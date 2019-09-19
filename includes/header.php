<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php'); //for local
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/database_helpers.php');

//if session is set make user loggedIn variable username
if(isset($_SESSION['username'])) {       
    $user_details_query = prepareAndExecuteQuery($con, "SELECT * FROM users WHERE username = ? ", 's', [$_SESSION['username']]);
    $user = mysqli_fetch_array($user_details_query); //get all info about user as an array  
}
else {
    header("Location: register.php"); //if user is not logged in redirect back to register.php
    exit();
}
?>

<html>
<head>
    <title>Fitness Tracker</title>
    
    <!-- Set base url for links -->
    <!-- <base href="/"/> for web -->
    <base href="/"/> <!-- for local -->

    <!-- JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> 
    <script src="includes/js/index.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="includes/css/style.css">
    <link rel="stylesheet" href="includes/css/training_history.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=DM+Serif+Display|Raleway|Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

</head>
<body>

<div class="top_bar">
    <label for="toggle">&#9776;</label>
    <input type="checkbox" id="toggle"/>
    <div id="menu">
        <div class="logo">
        <nav>
            <span id="top_bar_links_app_name" class="top_bar_links" ><i class="fas fa-dumbbell"></i></i>Fitness Tracker<i class="fas fa-dumbbell"></i></span>
            <span id="first_top_bar_link" class="top_bar_links"><i class="fas fa-plus"></i><a href="add_new_training.php">Add New Training</a></span> 
            <span class="top_bar_links"><i class="fas fa-history"></i><a href="training_history.php">Training History</a></span>
            <span class="top_bar_links"><i class="fas fa-trophy"></i><a href="best_performances.php">Best Performances</a></span>
        </div>
    </div>
    <div id="nav_bar_profile">
            <a id='nav_bar_profile_link' href='profile.php'>
            <img id="nav_bar_profile_pic" src="<?php echo $user['profile_pic']; ?>" alt="">
            <?php echo $user['first_name']; ?>
            <?php echo $user['last_name']; ?>
            </a>
        

            <a id="sign-out-button" href="includes/handlers/logout_handler.php">
            <i class="fas fa-sign-out-alt"></i>
            </a>
        </nav>
    </div>
</div>

<div class="wrapper">
