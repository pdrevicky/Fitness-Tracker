<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/user.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/profile_helper.php');

$person_id =  $_GET['id'];

$friend_query = prepareAndExecuteQuery($con, "SELECT username FROM users WHERE id= ? ", 'i', [$person_id]);
$friend = mysqli_fetch_array($friend_query);

//user's friend user object
$friend_obj = new User($con, $friend['username']);

//user logged in user object
$user_obj = new User($con, $_SESSION['username']);

//add friend
if (isset($_POST["add_friends_button"])) {
    $user_obj->addFriend($person_id, $_SESSION['username'], $friend['username']);
}

if (isset($_POST['remove_friends_button'])) {
    $user_obj->removeFriend($person_id, $_SESSION['username']);
}

//friend status
$friends = prepareAndExecuteQuery($con, "SELECT * FROM friends WHERE user= ? ", 's', [$_SESSION['username']]);
$friends_ids_array = [];
while ($row = $friends->fetch_assoc()) {
    array_push($friends_ids_array, $row['friend_id']);
}

//parameters for showNoneOrAddOrRemoveFriendButton function
$same_user_as_user_logged_in = $_SESSION['username'] == $friend['username'];
$users_are_friends = in_array("$person_id", $friends_ids_array);

//choose if to show button next to title(friend name) and if which one add friend or remove friend
showNoneOrAddOrRemoveFriendButton($same_user_as_user_logged_in,$users_are_friends,$friend_obj,$person_id);

$profPic = prepareAndExecuteQuery($con, "SELECT profile_pic FROM users WHERE username= ? ", 's', [$friend['username']]);
$row = mysqli_fetch_array($profPic);

//user friend user ubj
$user_obj = new User($con, $friend['username']);

function printFriendList($user_obj){
    $friends_objects_array = $user_obj->getFriendList();
    foreach ($friends_objects_array as $friend) {
        $friend_profile_picture_src = $friend->getProfilePicture();
        $friend_name_to_user_friend_list = $friend->getFirstAndLastName();
        
        echo "<div class='profile_friend_friends_list'>";
        echo "<div class='column_in_friend_friends_list'>";
        echo '<img class="profile_user_friend_list_image" src="' . $friend_profile_picture_src . '">';
        echo $friend_name_to_user_friend_list;
        echo "</div>";
        echo "</div>";
    }
}

?>

<!-- Show naother user profile picture-->
<div class='container'>
    <div class='row'>
        <div class='col-sm'>
            <div class="user_details column profile">
                <div id='profile_pic'>
                    <h4>Profile Picture</h4>
                    <br>
                    <img src="<?php echo $row['profile_pic'] ?>" alt="">
                </div>
            </div>
        </div>
        
        <!-- Show another user information  -->
        <div class='col-sm' id='profile_user_info'>
            <div class="user_details column profile">
                <div id='profile_user_info_age'>
                    <p><b> Age </b></p>
                    <?php echo $user_obj->getAge(); ?>
                </div>
                <hr>

                <div id='profile_user_info_nationality'>
                    <p><b> Nationality </b></p>
                    <?php echo $user_obj->getNationality(); ?>
                </div>
                <hr>
                <div id='profile_user_info_email'>
                    <p><b> Email </b></p>
                    <?php echo $user_obj->getEmail(); ?>
                </div>
                <hr>

                <div id='profile_user_info_phone_number'>
                    <p><b> Phone Number </b></p>
                    <?php echo "+420" . " " .  $user_obj->getPhoneNumber(); ?>
                </div>
                <hr>

            </div>
        </div>


        <div class='col-sm'>
            <form action='profile.php' method='POST'>
                <div id='profile_user_add_friend' class="user_details column profile">
                    <div class="container">
                        <h5 id='profile_friends_list_title'><?php echo $friend_obj->getFirstAndLastName() . '´s' ?> Friends</h5>
                        <hr>
                        <div id='profile_userFriend_list_div'>
                            <div id='profile_friends_list'>
                                <?php echo printFriendList($user_obj); ?>
                            </div>
                        </div>
                        <hr>
                        <a href='profile.php' id='friend_profile_back_button' class='btn btn-primary'>Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>