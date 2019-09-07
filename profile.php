<?php
require_once("includes/header.php");
require_once("includes/classes/user.php");
require_once("includes/classes/post.php");

$user_obj = new User($con, $user_logged_in);

if(isset($_POST['profile_user_info_edit'])){
    $post = new Post($con, $user_logged_in);
    $post->updateProfileUserInfo($_POST['date_of_birth'],$_POST['nationality'],$_POST['email'], $_POST['phone_number'], $user_logged_in);
}


echo "<div id='profile_title'>";
    echo "<h1>";
        echo "<b>";
            echo $user_obj->getFirstAndLastName();
        echo "</b>";
    echo "</h1>";
echo "</div>";


?> 
<div class='container'>
    <div class='row'>
        <div class='col-sm'>
            <div class="user_details column profile">
                <div id='profile_profile_pic'>
                    <img  src="<?php echo $user['profile_pic']; ?>" alt="">
                <?php 
                echo "<form action='includes/handlers/upload_profile_picture_handler.php' method='POST' enctype='multipart/form-data'>";
                    echo "<div id='div_new_profile_pic'>";
                        echo "<input type='file' id='new_profile_pic' name='image' onchange='form.submit();' /> ";
                        echo "<label class='btn btn-primary' id='label_new_profile_pic' for='new_profile_pic'>Change Profile Picture</label>"; //add to web
                    echo "</div>";
                echo "</form>";
                ?>
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

                <button class='btn btn-primary' onclick='editProfileUserInfo();' >Edit</button>                           
            </div>
        </div>

        <div class='col-sm hidden' id='profile_user_info_edit' >
            <form action='profile.php' method='POST'>
                <div class="user_details column profile">
                    <div id='profile_user_info_age'>
                        <p><b> Date Of Birth </b></p>    
                            <input type='date' name='date_of_birth' value='<?php echo $user_obj->getDateOfBirth(); ?>'>
                    </div>
                    <hr>

                    <div id='profile_user_info_nationality'>
                        <p><b> Nationality </b></p>                                   
                            <input type='text' name='nationality' value='<?php echo $user_obj->getNationality(); ?>'>
                    </div>
                    <hr>
                    <div id='profile_user_info_email'>
                        <p><b> Email </b></p>                                      
                            <input type='email' name='email' value='<?php echo $user_obj->getEmail(); ?>' >
                    </div>
                    <hr>

                    <div id='profile_user_info_phone_number'>
                        <p><b> Phone Number </b></p>                        
                            <input type='number' name='phone_number' value='<?php echo $user_obj->getPhoneNumber(); ?>'  pattern='[0-9]{9}'>
                    </div>
                    <hr>

                    <button class='btn btn-primary' type='submit' name='profile_user_info_edit' >Submit</button>                           
                </div>
            </form>
        </div>
        

        <div id="profile_user_friend_column" class='col-sm'>
            <form action='profile.php' method='POST'> 
                <div  id='profile_user_search_friend' class="user_details column profile">
                    <div class="container">
                        <h5 id='profile_friends_list_title'>Your Friends</h5>
                        <hr>
                        <div id='profile_friends_list_div'>
                            <div id='profile_friends_list'>
                                <?php echo $user_obj->friendList(); ?>
                            </div>
                        </div>
                        <div id='profile_search_friend_div'>
                            <hr>
                            <h5 id='profile_search_friend_title'>Search Friend</h5>
                            <br>
                                <div class="row">
                                    <div class="col-sm-9">
                                        <input type='text' name='search_user_input' id='search_user_input' class='form-control' placeholder='Search Name'>
                                            <div id='usernameList'>
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


