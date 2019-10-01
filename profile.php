<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<!-- Create profile page -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/user.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/profile_helper.php');

$user_obj = new User($con, $_SESSION['username']);

// prints the list of user's friends in rows
function printFriendList($user_obj)
{
    foreach ($user_obj->getFriendList() as $friend) {
        echo "<div class='row profile_friends_list'>";
        echo "<div class='col-sm-9'>";
        echo "<a href='friend_profile.php?id=" . $friend->getId() . " '' class='btn'>";
        echo '<img class="profile_user_friend_list_image" src="' . $friend->getProfilePicture() . '">';
        echo $friend->getFirstAndLastName();
        echo "</a>";
        echo "</div>";
        echo "<div class='col-sm-2'>";
        echo "<a  href='messages.php?friend_username=" . $friend->getUsername() . "' >";
        echo "<i class='fas fa-comment profile_user_friend_message'>";
        echo "</i>";
        echo  "</a>";
        echo "</div>";
        echo "</div>";
    }
}

// edit user info if edit button is pressed
if (isset($_POST['profile_user_info_edit'])) {
    $user_obj->updateProfileUserInfo($_POST['date_of_birth'], $_POST['nationality'], $_POST['email'], $_POST['phone_number']);
    header("Refresh:0");
    exit();
}

printProfileTitle($user_obj->getFirstAndLastName(), "h1");

?>
<div class='container'>
    <div class='row'>
        <?php
        printProfilePicture($user_obj, true);
        printProfileUserInfo($user_obj, true);
        printProfileUserInfoEdit($user_obj);
        ?>
        <div id="profile_user_friend_column" class='col-sm'>
            <form action='profile.php' method='POST'>
                <div id='profile_user_search_friend' class="user_details column profile">
                    <div class="container">
                        <h5 id='profile_friends_list_title'>Your Friends</h5>
                        <hr>
                        <div id='profile_friends_list_div'>
                            <div id='profile_friends_list'>
                                <?php printFriendList($user_obj); ?>
                            </div>
                        </div>
                        <div id='profile_search_friend_div'>
                            <hr>
                            <h5 id='profile_search_friend_title'>Search Friend</h5>
                            <br>
                            <div class="row">
                                <div class="col-sm-9">
                                    <input type='text' name='search_user_input' id='search_user_input' class='form-control' placeholder='Search Name' autofocus>
                                    <div id='username_list'>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>