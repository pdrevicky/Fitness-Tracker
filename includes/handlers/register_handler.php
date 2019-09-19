<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/database_helpers.php');

$error_array = array();

function createUsername($first_name, $last_name, $con)
{
    // generate username by concatenating first name and last name
    $username = strtolower($first_name . "_" . $last_name);
    $check_username_query = prepareAndExecuteQuery($con, "SELECT username FROM users WHERE username= ? ", 's', [$username]);

    $i = 0;
    // if username exists add number to username
    while (mysqli_num_rows($check_username_query) != 0) {
        $i++;
        $username = $username . "_" . $i;
        $check_username_query = prepareAndExecuteQuery($con, "SELECT username FROM users WHERE username= ? ", 's', [$username]);
    }
    return $username;
}

if (isset($_POST['register_button'])) {

    // Registration form values
    $first_name = strip_tags($_POST['register_first_name']);
    $first_name = ucfirst(strtolower($first_name));
    $_SESSION['register_first_name'] = $first_name;

    $last_name = strip_tags($_POST['register_last_name']);
    $last_name = ucfirst(strtolower($last_name));
    $_SESSION['register_last_name'] = $last_name;

    $email_1 = strip_tags($_POST['register_email_1']);
    $_SESSION['register_email_1'] = $email_1;

    $email_2 = strip_tags($_POST['register_email_2']);
    $_SESSION['register_email_2'] = $email_2;

    $password_1 = strip_tags($_POST['register_password_1']);
    $password_2 = strip_tags($_POST['register_password_2']);

    $sign_up_date = date("Y-m-d");

    if ($email_1 == $email_2) {
        // check if email is in valid format
        if (filter_var($email_1, FILTER_VALIDATE_EMAIL)) {
            $email_1 = filter_var($email_1, FILTER_VALIDATE_EMAIL);
            $email_exists_query = prepareAndExecuteQuery($con, "SELECT email FROM users WHERE email= ? ", 's', [$email_1]);
            $email_already_in_use = mysqli_num_rows($email_exists_query) > 0;
            if ($email_already_in_use) {
                array_push($error_array, "EMAIL_IN_USE");
            }
        } else {
            array_push($error_array, "INVALID_EMAIL_FORMAT");
        }
    } else {
        array_push($error_array, "EMAIL_DO_NOT_MATCH");
    }

    $first_and_last_name_max_length = 25;
    $first_and_last_name_min_length = 2;

    if (strlen($first_name) > $first_and_last_name_max_length || strlen($first_name) < $first_and_last_name_min_length) {
        array_push($error_array, "FIRST_NAME_NUMBER_OF_CHARACTERS_ERROR");
    }

    if (strlen($last_name) > $first_and_last_name_max_length || strlen($last_name) < $first_and_last_name_min_length) {
        array_push($error_array, "LAST_NAME_NUMBER_OF_CHARACTERS_ERROR");
    }

    if ($password_1 != $password_2) {
        array_push($error_array, "PASSWORD_DO_NOT_MATCH");
    } else {
        $password_regex_pattern = '/[^A-Za-z0-9]/';
        if (preg_match($password_regex_pattern, $password_1)) {
            array_push($error_array, "PASSWORD_CAN_ONLY_CONTAIN_ENGLISH_CHARACTERS_AND_NUMBERS");
        }
    }

    $password_max_length = 30;
    $password_min_length = 5;

    if (strlen($password_1 > $password_max_length || strlen($password_1) < $password_min_length)) {
        array_push($error_array, "PASSWORD_LENGHT");
    }

    $user_input_correct = empty($error_array);

    if ($user_input_correct) {
        $password_1 = md5($password_1); // 'encrypt' password before sending to database

        $username = createUsername($first_name, $last_name, $con);

        // profile picture assigment
        $default_picture = "assets/images/profile_pics/defaults/head_deep_blue.png";
        $profile_pic =  $default_picture;

        // putting values into the databases
        $query = prepareAndExecuteQuery($con, "INSERT INTO users VALUES (?,?,?,?,?,?,?,?,?,?,?,?)", 'isssssssssis', ['', $first_name, $last_name, $username, $email_1, $password_1, $sign_up_date, $profile_pic, 'no', '', '', '']);

        array_push($error_array, "REGISTERED_SUCCESSFULLY");

        // clear session variables
        $_SESSION['register_first_name'] = "";
        $_SESSION['register_last_name'] = "";
        $_SESSION['register_email_1'] = "";
        $_SESSION['register_email_2'] = "";
    }
}
