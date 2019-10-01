<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<!-- Common functions for profile, friend profile and messages pages-->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/user.php');

function printProfileTitle($title, $heading)
{
    echo "<div id='profile_title'>";
    echo "<$heading>";
    echo "<b>";
    echo $title;
    echo "</b>";
    echo "</$heading>";
    echo "</div>";
}

// print profile picture with an optional button for changing the picture (if on user's own profile)
function printProfilePicture($user_obj, $change_profile_picture_button)
{
    echo " <div class='col-sm'>";
    echo "<div class='user_details column profile'>";
    echo "<div id='profile_pic'>";
    echo "<img  src=" . $user_obj->getProfilePicture() . " >";
    if ($change_profile_picture_button) {
        echo "<form action='includes/handlers/upload_profile_picture_handler.php' method='POST' enctype='multipart/form-data'>";
        echo "<div id='div_new_profile_pic'>";
        echo "<input type='file' id='new_profile_pic' name='image' onchange='form.submit();' /> ";
        echo "<label class='btn profile_user_buttons' id='label_new_profile_pic' for='new_profile_pic'>Change Profile Picture</label>";
        echo "</div>";
        echo "</form>";
    }
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

function printProfileUserInfoRow($div_id, $row_title, $content)
{
    echo "<div id='$div_id'>";
    echo "<p><b> $row_title </b></p> ";
    echo $content;
    echo "</div>";
    echo "<hr>";
}

function printProfileUserInfo($user_obj, $profile_edit_button)
{
    echo "<div class='col-sm' id='profile_user_info'>";
    echo "<div class='user_details column profile'>";

    printProfileUserInfoRow('profile_user_info_age', ' Age ', $user_obj->getAge());
    printProfileUserInfoRow('profile_user_info_nationality', ' Nationality ', $user_obj->getNationality());
    printProfileUserInfoRow('profile_user_info_email', ' Email ', $user_obj->getEmail());
    printProfileUserInfoRow('profile_user_info_phone_number', ' Phone Number ', "+420" . " " . $user_obj->getPhoneNumber());

    if ($profile_edit_button) {
        echo "<button class='profile_user_buttons' class='btn' onclick='editProfileUserInfo();' >Edit</button>";
    }
    echo "</div>";
    echo "</div>";
}

function printProfileUserInfoEdit($user_obj)
{
    echo "<div class='col-sm hidden' id='profile_user_info_edit'>";
    echo "<form action='profile.php' method='POST'>";
    echo "<div class='user_details column profile'>";

    printProfileUserInfoRow('profile_user_info_age', ' Date Of Birth ', "<input type='date' name='date_of_birth' value=" . $user_obj->getDateOfBirth() . ">");
    printProfileUserInfoRow('profile_user_info_nationality', ' Nationality ', "<input type='text' name='nationality' value=" . $user_obj->getNationality() . ">");
    printProfileUserInfoRow('profile_user_info_email', ' Email ', "<input type='email' name='email' value=" . $user_obj->getEmail() . " >");
    printProfileUserInfoRow('profile_user_info_phone_number', ' Phone Number ', "<input type='number' name='phone_number' value=" . $user_obj->getPhoneNumber() . " pattern='[0-9]{9}' >");

    echo "<button class='profile_user_buttons' class='btn' type='submit' name='profile_user_info_edit' >Submit</button>";
    echo "</div>";
    echo "</form>";
    echo "</div>";
}

function showNoneOrAddOrRemoveFriendButton($user_obj, $person_obj)
{
    echo "<div id='profile_title'>";
    echo "<h1>";
    echo "<b>";
    $same_user_as_user_logged_in = $user_obj->getUsername() == $person_obj->getUsername();
    if ($same_user_as_user_logged_in) {
        echo $person_obj->getFirstAndLastName();
    } else {
        echo "<form action='friend_profile.php?id=" . $person_obj->getId() . "' method='POST'>";
        echo $person_obj->getFirstAndLastName();
        // create 'add friend' or 'remove friend' button depending on whether the user has the person among friends
        if ($user_obj->isFriendWith($person_obj)) {
            echo "<button type='submit' class='btn btn-danger remove_from_friends_button' name='remove_friends_button'>Remove from friends</button>";
        } else {
            echo "<button type='submit' class='btn profile_user_buttons add_to_friends_button' name='add_friends_button'>Add to friends</button>";
        }
        echo "</form>";
    }
    echo "</b>";
    echo "</h1>";
    echo "</div>";
}
