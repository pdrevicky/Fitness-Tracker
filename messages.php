<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/classes/user.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/profile_helper.php');

$user_obj = new User($con, $_SESSION['username']);

//add new info about user to database and edit it
if(isset($_POST['profile_user_info_edit'])){
    $user_obj->updateProfileUserInfo($_POST['date_of_birth'],$_POST['nationality'],$_POST['email'], $_POST['phone_number'], $_SESSION['username']);
}

//adding friend
if(isset($_POST['profile_add_friend_button'])){
    $user_obj->addFriend($_POST['search_user_input'], $_SESSION['username'], $friend_username);
}

//user full name as a page title
printProfileTitle($user_obj);

$friend_username = $_GET['friend_username'];
$friend_username_title = str_replace("_", " ", $friend_username);
$friend_username_title = ucwords($friend_username_title);
$friend_username_title = preg_replace('/[0-9]+/', '', $friend_username_title); 


//get las id for message I get
$lastID =  $user_obj->getLastMessageToId($_SESSION['username'], $friend_username);

//add messages to databes
if(isset($_POST['send_message'])){
    $user_obj->addMessage($_POST['message'], $_SESSION['username'], $friend_username);
    header('Location: messages.php?friend_username='.$friend_username.'');
    exit();
}

function getMessages($user_logged_in_username, $friend_username, $user_obj){
    $messages =  $user_obj->getMessages($user_logged_in_username, $friend_username);
    while ($row = $messages->fetch_assoc()) {
        if ($row['user'] == $user_logged_in_username) {
            echo "<div class='user_loggen_in_messages'>";
            echo $row['text'] . "<br>";
            echo "</div>";
        }
        if ($row['user_to'] == $user_logged_in_username) {
            echo "<div class='user_logged_in_messages_from_friend''>";
            echo $row['text'] . "<br>";
            echo "</div>";
        }
    }

}

?> 
<script>

    function processNewMessages(data)
    {  
        $("#message_box").empty();
        for(var i = 0; i < data['messages'].length; i++ ){
            var message_box = document.getElementById("message_box");
            var div = document.createElement("DIV");
            if(data['sent_by'][i] == "<?php echo $friend_username; ?>" ){
                div.className = 'user_logged_in_messages_from_friend';
            }
            if(data['sent_by'][i] == "<?php echo $_SESSION['username']; ?>" ){
                div.className = 'user_loggen_in_messages';
            }
            div.innerHTML = data['messages'][i];
            message_box.appendChild(div);    
        }
    }
    //loead all messages in databese every 0.5 sec;
    setInterval(function(){
        $load_messages_interval = 500;
        $.ajax({
                    url:"includes/handlers/messages_handler.php", //the page containing php script
                    type: "POST", //request type,
                    dataType: "json",
                    data: {function: "get_state", friend: "<?php echo $friend_username; ?>", user_logged_in: "<?php echo $_SESSION['username']; ?>" },
                    success: processNewMessages,        
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr);
                        console.log(xhr.status);
                        console.log(thrownError);
                    }
                });
        } , 500)
 
</script>
<div class='container'>
    <div class='row'>
        <?php
             printProfilePicture($user_obj);
             printProfileEditColumn($user_obj)
        ?>
        <div id="profile_user_message_column" >
            <div class='col-sm profile column'>
                    <?php              
                    echo "<div id='profile_title'>";
                        echo "<h4>";
                            echo "<b>";
                                echo $friend_username_title;
                            echo "</b>";
                        echo "</h4>";
                    echo "</div>";
                        echo "<div id='message_box'>";
                            getMessages($_SESSION['username'], $friend_username,$user_obj );
                        echo "</div>";
                    echo "<form id='messages_input' action='messages.php?friend_username=".$friend_username."' method='post'>";
                         echo "<textarea id='messages_input_text' name='message' rows='5' required></textarea>";
                         echo "<button id='messages_input_button' type='submit' name='send_message' class='btn btn-primary'>Send</button>";
                    echo "</form>";
                    ?>
                    <a href='profile.php' id='profile_back_button' class='btn btn-primary'>Back</a>
            </div>
            <div>
            </div>
        </div>

    </div>
</div>
