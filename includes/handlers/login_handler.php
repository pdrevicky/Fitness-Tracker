<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/database_helpers.php');

// check if user and password are correct, if not show error, if correct log User in 
if (isset($_POST['login_button'])) {

    $email = filter_var($_POST['login_email'], FILTER_VALIDATE_EMAIL);
    $_SESSION['login_email'] = $email;
    $password = md5($_POST['login_password']);
    $matched_users_query = prepareAndExecuteQuery($con, "SELECT username FROM users WHERE email= ? AND password = ? ", 'ss', [$email, $password]);

    $user_exists = mysqli_num_rows($matched_users_query) > 0;
    if ($user_exists) {
        $row = mysqli_fetch_array($matched_users_query);
        $_SESSION['username'] = $row['username'];
        header("Location: profile.php");
        exit();
    } else {
        array_push($error_array, "EMAIL_OR_PASSWORD_INCORRECT");
    }
}
