<?php
require_once("includes/header.php");
require_once("includes/classes/user.php");
require_once("includes/classes/post.php");

$user_obj = new User($con, $user_logged_in);



if(isset($_POST['profile_user_info_edit'])){
    $post = new Post($con, $user_logged_in);
    $post->updateProfileUserInfo($_POST['date_of_birth'],$_POST['nationality'],$_POST['email'], $_POST['phone_number'], $user_logged_in);
}

if(isset($_POST['profile_add_friend_button'])){
    $post = new Post($con, $user_logged_in);
    $post->addFriend($_POST['search_user_input'], $user_logged_in, $friendUsername);
}

echo "<div id='profile_title'>";
    echo "<h1>";
        echo "<b>";
            echo $user_obj->getFirstAndLastName();
        echo "</b>";
    echo "</h1>";
echo "</div>";

$friendUsername = $_GET['friendUsername'];
$friendUsernameTitle = str_replace("_", " ", $friendUsername);
$friendUsernameTitle = ucwords($friendUsernameTitle);
$friendUsernameTitle = preg_replace('/[0-9]+/', '', $friendUsernameTitle); 


//get las id for message I get
$lastID =  $user_obj->getLastMessageToId($user_logged_in, $friendUsername);

if(isset($_POST['sendMessage'])){
    $post = new Post($con, $user_logged_in);
    $post->addMessage($_POST['message'], $user_logged_in, $friendUsername);
    header('Location: messages.php?friendUsername='.$friendUsername.'');
    exit();
}

?> 
<script>

    function processNewMessages(data)
    {  
        $("#messageBox").empty();
        for(var i = 0; i < data['messages'].length; i++ ){
            var messageBox = document.getElementById("messageBox");
            var div = document.createElement("DIV");
            if(data['sentBy'][i] == "<?php echo $friendUsername; ?>" ){
                div.className = 'userLoggedInMessagesFromFriend';
            }
            if(data['sentBy'][i] == "<?php echo $user_logged_in; ?>" ){
                div.className = 'userLoggedInMessages';
            }
            div.innerHTML = data['messages'][i];
            messageBox.appendChild(div);    
        }
        console.log("idem");
    }

    setInterval(function(){
        $.ajax({
                    url:"messages_handler.php", //the page containing php script
                    type: "POST", //request type,
                    dataType: "json",
                    data: {function: "getState", friend: "<?php echo $friendUsername; ?>", userLoggedIn: "<?php echo $user_logged_in; ?>" },
                    success: processNewMessages,        
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr);
                        console.log(xhr.status);
                        console.log(thrownError);
                    }
                });
        } , 3000)
 
</script>
<div class='container'>
    <div class='row'>
        <div class='col-sm'>
            <div class="user_details column profile">
                <div id='profile_profile_pic'>
                    <img  src="<?php echo $user['profile_pic']; ?>" alt="">
                <?php 
                echo "<form action='upload_profile_picture_handler.php' method='POST' enctype='multipart/form-data'>";
                    echo "<div id='div_new_profile_pic'>";
                        echo "<input type='file' id='new_profile_pic' name='image' onclick='searchNewPic();' /> ";
                        echo "<label class='btn btn-primary' id='label_new_profile_pic' for='new_profile_pic'>Change Profile Picture</label>"; //add to web
                    echo "</div>";
                    echo "<div class='hidden' id='submit_new_profile_pic'>";
                        echo "<input type='submit' class='btn btn-primary' />";
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
        
        <div id="profile_user_message_column" >
            <div class='col-sm profile column'>
                    <?php              
                    echo "<div id='profile_title'>";
                        echo "<h4>";
                            echo "<b>";
                                echo $friendUsernameTitle;
                            echo "</b>";
                        echo "</h4>";
                    echo "</div>";
                        echo "<div id='messageBox'>";
                            $user_obj->getMessages($user_logged_in, $friendUsername);
                        echo "</div>";
                    echo "<form id='messagesInput' action='messages.php?friendUsername=".$friendUsername."' method='post'>";
                         echo "<textarea id='messagesInputText' name='message' rows='5' required></textarea>";
                         echo "<button id='messageInputbutton' type='submit' name='sendMessage' class='btn btn-primary'>Send</button>";
                    echo "</form>";
                    ?>
                    <a href='profile.php' id='profile_back_button' class='btn btn-primary'>Back</a>
            </div>
            <div>
            </div>
        </div>

    </div>
</div>