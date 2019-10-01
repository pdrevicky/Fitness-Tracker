<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<!-- Create register and login page -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/handlers/register_handler.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/handlers/login_handler.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Welcome to Fitness Tracker</title>
    <link rel="stylesheet" type="text/css" href="includes/css/register_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="includes/js/register.js"></script>
</head>

<body>

    <?php
    // after registration show login form
    if (isset($_POST['register_button'])) {
        echo '
        <script>
        showRegisterForm();
        </script>
     ';
    }

    ?>

    <div class="wrapper">
        <!-- show login and register box , process all user input through login_handler.php and register_handler.php -->
        <!-- display problems with entered values to the user  -->
        <div class="login_and_register_box">

            <div class="login_and_register_header">
                <h1>Fitness Tracker</h1>
                Login or sign up below!
            </div>

            <div id="login_form">
                <form action="register.php" method="POST">
                    <input type="email" name="login_email" placeholder="Email Adress" value="beta@gmail.com" required> <br>
                    <input id="login_password" type="password" name="login_password" placeholder="Password" value="gogogo"> <br>

                    <?php if (in_array("EMAIL_OR_PASSWORD_INCORRECT", $error_array)) echo "<span style='color: #ff0000'> Email or password was incorrect <span><br>" ?>

                    <input type="submit" name="login_button" value="Login"> <br>

                    <a href="#" id="signup" class="signup">Need an account? Register here!</a>
                </form>
            </div>

            <div id="register_form">
                <form action="register.php" method="POST">
                    <input type="text" name="register_first_name" placeholder="First Name" value="<?php
                                                                                                    if (isset($_SESSION['register_first_name'])) {
                                                                                                        echo $_SESSION['register_first_name'];
                                                                                                    }
                                                                                                    ?>" required>
                    <br>
                    <?php if (in_array("FIRST_NAME_NUMBER_OF_CHARACTERS_ERROR", $error_array)) echo "<span style='color: #ff0000'> Your first name must be between 2 and 25 characters <span><br>"; ?>


                    <input type="text" name="register_last_name" placeholder="Last Name" value="<?php
                                                                                                if (isset($_SESSION['register_last_name'])) {
                                                                                                    echo $_SESSION['register_last_name'];
                                                                                                }
                                                                                                ?>" required>
                    <br>
                    <?php if (in_array("LAST_NAME_NUMBER_OF_CHARACTERS_ERROR", $error_array)) echo "<span style='color: #ff0000'> Your last name must be between 2 and 25 characters <span><br>"; ?>

                    <input type="email" name="register_email_1" placeholder="Email" value="<?php
                                                                                            if (isset($_SESSION['register_email_1'])) {
                                                                                                echo $_SESSION['register_email_1'];
                                                                                            }
                                                                                            ?>" required>
                    <br>

                    <input type="email" name="register_email_2" placeholder="Confirm Email" value="<?php
                                                                                                    if (isset($_SESSION['register_email_2'])) {
                                                                                                        echo $_SESSION['register_email_2'];
                                                                                                    }
                                                                                                    ?>" required>
                    <br>
                    <?php if (in_array("EMAIL_IN_USE", $error_array)) echo "<span style='color: #ff0000'> Email already in use <span><br>";
                    else if (in_array("INVALID_EMAIL_FORMAT", $error_array)) echo "<span style='color: #ff0000'> Invalid email format <span><br>";
                    else if (in_array("EMAIL_DO_NOT_MATCH", $error_array)) echo "<span style='color: #ff0000'> Email do not match <span><br>"; ?>

                    <input type="password" name="register_password_1" placeholder="Password" required>
                    <br>

                    <input type="password" name="register_password_2" placeholder="Confirm Password" required>
                    <br>
                    <?php if (in_array("PASSWORD_DO_NOT_MATCH", $error_array)) echo "<span style='color: #ff0000'> Your password do not match <span><br>";
                    else if (in_array("PASSWORD_CAN_ONLY_CONTAIN_ENGLISH_CHARACTERS_AND_NUMBERS", $error_array)) echo "<span style='color: #ff0000'> Your password can on contain english characters and numbers <span><br>";
                    else if (in_array("PASSWORD_LENGHT", $error_array)) echo "<span style='color: #ff0000'> Your password must by between 5 and 30 characters <span><br>"; ?>

                    <input type="submit" name="register_button" value="Register">
                    <br>

                    <?php if (in_array("REGISTERED_SUCCESSFULLY", $error_array)) echo "<span style='color: #14C800'> You are all set! Goahead and login!<span><br>"; ?>
                    <a href="#" id="sign_in" class="sign_in">Already have and account? Sign in here!</a>
                </form>
            </div>
        </div>
    </div>

</body>

</html>