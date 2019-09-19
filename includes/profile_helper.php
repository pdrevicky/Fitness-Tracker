<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/user.php');

function printProfileTitle($user_obj){
    echo "<div id='profile_title'>";
    echo "<h1>";
        echo "<b>";
            echo $user_obj->getFirstAndLastName();
        echo "</b>";
    echo "</h1>";
echo "</div>";
}

function printProfilePicture($user_obj)
{
    echo " <div class='col-sm'>";
    echo "<div class='user_details column profile'>";
    echo "<div id='profile_pic'>";
    echo "<img  src=" . $user_obj->getProfilePicture() . " >";
    echo "<form action='includes/handlers/upload_profile_picture_handler.php' method='POST' enctype='multipart/form-data'>";
    echo "<div id='div_new_profile_pic'>";
    echo "<input type='file' id='new_profile_pic' name='image' onchange='form.submit();' /> ";
    echo "<label class='btn btn-primary' id='label_new_profile_pic' for='new_profile_pic'>Change Profile Picture</label>"; //add to web
    echo "</div>";
    echo "</form>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

function printProfileEditColumn($user_obj)
{
    echo "<div class='col-sm' id='profile_user_info'>";
    echo "<div class='user_details column profile'>";
    echo  "<div id='profile_user_info_age'>";
    echo "<p><b> Age </b></p> ";
    echo $user_obj->getAge();
    echo "</div>";
    echo "<hr>";

    echo  "<div id='profile_user_info_nationality'>";
    echo "<p><b> Nationality </b></p>";
    echo $user_obj->getNationality();
    echo "</div>";
    echo "<hr>";
    echo "<div id='profile_user_info_email'>";
    echo "<p><b> Email </b></p>";
    echo $user_obj->getEmail();
    echo "</div>";
    echo "<hr>";

    echo "<div id='profile_user_info_phone_number'>";
    echo " <p><b> Phone Number </b></p> ";
    echo "+420" . " " . $user_obj->getPhoneNumber();
    echo "</div>";
    echo  "<hr>";

    echo "<button class='btn btn-primary' onclick='editProfileUserInfo();' >Edit</button>";
    echo "</div>";
    echo "</div>";

    echo "<div class='col-sm hidden' id='profile_user_info_edit'>";
    echo "<form action='profile.php' method='POST'>";
    echo "<div class='user_details column profile'>";
    echo "<div id='profile_user_info_age'>";
    echo "<p><b> Date Of Birth </b></p>";
    echo "<input type='date' name='date_of_birth' value=" . $user_obj->getDateOfBirth() . ">";
    echo "</div>";
    echo "<hr>";
    echo "<div id='profile_user_info_nationality'>";
    echo "<p><b> Nationality </b></p>";
    echo "<input type='text' name='nationality' value=" . $user_obj->getNationality() . ">";
    echo "</div>";
    echo "<hr>";
    echo "<div id='profile_user_info_email'>";
    echo "<p><b> Email </b></p>";
    echo "<input type='email' name='email' value=" . $user_obj->getEmail() . " >";
    echo "</div>";
    echo "<hr>";
    echo "<div id='profile_user_info_phone_number'>";
    echo "<p><b> Phone Number </b></p>";
    echo "<input type='number' name='phone_number' value=" . $user_obj->getPhoneNumber() . " pattern='[0-9]{9}' >";
    echo "</div>";
    echo "<hr>";

    echo "<button class='btn btn-primary' type='submit' name='profile_user_info_edit' >Submit</button>";
    echo "</div>";
    echo "</form>";
    echo "</div>";
}

function showNoneOrAddOrRemoveFriendButton($same_user_as_user_logged_in,$users_are_friends,$friend_obj,$person_id){
    if ($same_user_as_user_logged_in) {
        echo "<div id='profile_title'>";
        echo "<h1>";
        echo "<b>";
        echo $friend_obj->getFirstAndLastName();
        echo "</b>";
        echo "</h1>";
        echo "</div>";
    } else {
        if ($users_are_friends) {
            echo "<div id='profile_title'>";
            echo "<h1>";
            echo "<b>";
            echo "<form action='friend_profile.php?id=" . $person_id . "' method='POST'>";
            echo $friend_obj->getFirstAndLastName();
            echo "<button type='submit' class='btn btn-danger remove_from_friends_button' name='remove_friends_button'>Remove from friends</button>";
            echo "</form>";
            echo "</b>";
            echo "</h1>";
            echo "</div>";
        } else {
            echo "<div id='profile_title'>";
            echo "<h1>";
            echo "<b>";
            echo "<form action='friend_profile.php?id=" . $person_id . "' method='POST'>";
            echo $friend_obj->getFirstAndLastName();
            echo "<button type='submit' class='btn btn-primary add_to_friends_button' name='add_friends_button'>Add to friends</button>";
            echo "</form>";
            echo "</b>";
            echo "</h1>";
            echo "</div>";
        }
    }
}


