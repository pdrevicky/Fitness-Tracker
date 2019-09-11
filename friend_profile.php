<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/classes/user.php');

$id =  $_GET['id'];

$usernames = prepareAndExecuteQuery($con, "SELECT username FROM users WHERE id= ? ", 'i', [$id]);

$row = mysqli_fetch_array($usernames);
$friend_username = $row['username'];

$friend_username_title = str_replace("_", " ", $friend_username);
$friend_username_title = ucwords($friend_username_title);
$friend_username_title = preg_replace('/[0-9]+/', '', $friend_username_title);

//user logged in user object
$user_obj = new User($con, $user_logged_in);

//add friend
if(isset($_POST["add_friends_button"])){
    $user_obj->addFriend($id, $user_logged_in, $friend_username);
}

if(isset($_POST['remove_friends_button'])){
    $user_obj->removeFriend($id, $user_logged_in);
}

//friend status
$friends = prepareAndExecuteQuery($con, "SELECT * FROM friends WHERE user= ? ", 's', [$user_logged_in]);
$friends_ids_array = [];
while ($row = $friends->fetch_assoc()) {
    array_push($friends_ids_array, $row['friend_id']);
}
if ($user_logged_in == $friend_username) {
    echo "<div id='profile_title'>";
    echo "<h1>";
        echo "<b>";
            echo $friend_username_title;
        echo "</b>";
    echo "</h1>";
    echo "</div>";
}
else {
    if (in_array("$id", $friends_ids_array)) { 
        echo "<div id='profile_title'>";
        echo "<h1>";
            echo "<b>";
                echo "<form action='friend_profile.php?id=".$id."' method='POST'>";
                    echo $friend_username_title;
                    echo "<button type='submit' class='btn btn-danger remove_from_friends_button' name='remove_friends_button'>Remove from friends</button>";
                echo "</form>";
            echo "</b>";
        echo "</h1>";
        echo "</div>";
    } 
    else { 
        echo "<div id='profile_title'>";
            echo "<h1>";
                echo "<b>";
                    echo "<form action='friend_profile.php?id=".$id."' method='POST'>";
                        echo $friend_username_title;
                        echo "<button type='submit' class='btn btn-primary add_to_friends_button' name='add_friends_button'>Add to friends</button>";
                    echo "</form>";
                echo "</b>";
            echo "</h1>";
            echo "</div>";
    } 
}
$profPic = prepareAndExecuteQuery($con, "SELECT profile_pic FROM users WHERE username= ? ", 's', [$friend_username]);
$row = mysqli_fetch_array($profPic);

//user friend user ubj
$user_obj = new User($con, $friend_username);

?>

<div class='container'>
    <div class='row'>
        <div class='col-sm'>
            <div class="user_details column profile">
                <div id='profile_profile_pic'>
                    <h4>Profile Picture</h4>
                    <br>
                    <img  src="<?php echo $row['profile_pic'] ?>" alt="">
                </div>
            </div>
        </div>

        <div class='col-sm' id='profile_user_info'>
            <div class="user_details column profile">
                <div id='profile_user_info_age'>
                    <p><b> Age </b></p>
                    <?php echo $user_obj->getAge(); ?>
                </div>
                <hr>

                <div id='profile_user_info_nationality'>
                    <p><b> Nationality </b></p>                    
                    <?php echo $user_obj->showNationality(); ?>
                </div>
                <hr>
                <div id='profile_user_info_email'>
                    <p><b> Email </b></p>                    
                        <?php echo $user_obj->getEmail(); ?>
                </div>
                <hr>

                <div id='profile_user_info_phone_number'>
                    <p><b> Phone Number </b></p>                    
                    <?php echo  $user_obj->showPhoneNumber(); ?>
                </div>
                <hr>
                         
            </div>
        </div>


        <div class='col-sm'>
            <form action='profile.php' method='POST'> 
                <div  id='profile_user_add_friend' class="user_details column profile">
                    <div class="container">
                        <h5 id='profile_friends_list_title'><?php echo $friend_username_title. '´s' ?> Friends</h5>
                        <hr>
                        <div id='profile_userFriend_list_div'>
                            <div id='profile_friends_list'>
                                <?php echo $user_obj->friendFriendList(); ?>
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


