<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<!-- Create friend profile page -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/user.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/profile_helper.php');

$person_id =  $_GET['id'];

$person_query = prepareAndExecuteQuery($con, "SELECT username FROM users WHERE id= ? ", 'i', [$person_id]);
$person_table = mysqli_fetch_array($person_query);

// user's friend User object
$person_obj = new User($con, $person_table['username']);

// user logged in User object
$user_obj = new User($con, $_SESSION['username']);

// add friend
if (isset($_POST["add_friends_button"])) {
    $user_obj->addFriend($person_obj);
}

if (isset($_POST['remove_friends_button'])) {
    $user_obj->removeFriend($person_obj);
}

// choose if to show button next to title(friend name) and if yes, then which: 'Add friend' or 'Remove friend'
showNoneOrAddOrRemoveFriendButton($user_obj, $person_obj);

function printFriendList($person_obj){
    foreach ($person_obj->getFriendList() as $friend) {
        echo "<div class='profile_friend_friends_list'>";
        echo "<div class='column_in_friend_friends_list'>";
        echo '<img class="profile_user_friend_list_image" src="' . $friend->getProfilePicture() . '">';
        echo $friend->getFirstAndLastName();
        echo "</div>";
        echo "</div>";
    }
}

?>

<!-- Show friend's profile picture-->
<div class='container'>
    <div class='row'>
        <!-- Show friend's information  -->
        <?php
        printProfilePicture($person_obj,false);
        printProfileUserInfo($person_obj, false);
        ?>

        <div class='col-sm'>
            <form action='profile.php' method='POST'>
                <div id='profile_user_add_friend' class="user_details column profile">
                    <div class="container">
                        <h5 id='profile_friends_list_title'><?php echo $person_obj->getFirstAndLastName() . '´s' ?> Friends</h5>
                        <hr>
                        <div id='profile_userFriend_list_div'>
                            <div id='profile_friends_list'>
                                <?php printFriendList($person_obj); ?>
                            </div>
                        </div>
                        <hr>
                        <a href='profile.php' id='friend_profile_back_button' class='btn profile_user_buttons'>Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>