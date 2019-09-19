<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/user.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/profile_helper.php');

$user_obj = new User($con, $_SESSION['username']);

function printFriendList($user_obj)
{
    $friends_objects_array = $user_obj->getFriendList();
    foreach ($friends_objects_array as $friend) {
        $friend_profile_picture_src = $friend->getProfilePicture();
        $friend_id = $friend->getId();
        $friend_name_to_user_friend_list = $friend->getFirstAndLastName();
        $friend_username = $friend->getUsername();

        echo "<div class='row profile_friends_list'>";
        echo "<div class='col-sm-9'>";
        echo "<a href='friend_profile.php?id=" . $friend_id . " '' class='btn'>";
        echo '<img class="profile_user_friend_list_image" src="' . $friend_profile_picture_src . '">';
        echo $friend_name_to_user_friend_list;
        echo "</a>";
        echo "</div>";
        echo "<div class='col-sm-2'>";
        echo "<a  href='messages.php?friend_username=" . $friend_username . "' >";
        echo "<i class='fas fa-comment profile_user_friend_message'>";
        echo "</i>";
        echo  "</a>";
        echo "</div>";
        echo "</div>";
    }
}

if (isset($_POST['profile_user_info_edit'])) {
    $user_obj->updateProfileUserInfo($_POST['date_of_birth'], $_POST['nationality'], $_POST['email'], $_POST['phone_number'], $_SESSION['username']);
    header("Refresh:0");
    exit();
}

printProfileTitle($user_obj);

?>
<div class='container'>
    <div class='row'>
        <?php
        printProfilePicture($user_obj);
        printProfileEditColumn($user_obj)
        ?>
        <div id="profile_user_friend_column" class='col-sm'>
            <form action='profile.php' method='POST'>
                <div id='profile_user_search_friend' class="user_details column profile">
                    <div class="container">
                        <h5 id='profile_friends_list_title'>Your Friends</h5>
                        <hr>
                        <div id='profile_friends_list_div'>
                            <div id='profile_friends_list'>
                                <?php echo printFriendList($user_obj); ?>
                            </div>
                        </div>
                        <div id='profile_search_friend_div'>
                            <hr>
                            <h5 id='profile_search_friend_title'>Search Friend</h5>
                            <br>
                            <div class="row">
                                <div class="col-sm-9">
                                    <input type='text' name='search_user_input' id='search_user_input' class='form-control' placeholder='Search Name'>
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