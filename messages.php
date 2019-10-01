<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<!-- Create a page with a chat window between the logged user and one of his friends -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/user.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/profile_helper.php');

$user_obj = new User($con, $_SESSION['username']);
$friend_obj = new User($con, $_GET['friend_username']);

// add new info about user to database and edit it
if (isset($_POST['profile_user_info_edit'])) {
    $user_obj->updateProfileUserInfo($_POST['date_of_birth'], $_POST['nationality'], $_POST['email'], $_POST['phone_number']);
}

// add messages to database
if (isset($_POST['send_message'])) {
    $user_obj->addMessage($_POST['message'], $friend_obj);
    header('Location: messages.php?friend_username=' . $friend_obj->getUsername() . '');
    exit();
}

// print single message as a row in chat
function printMessage($message_text, $div_class)
{
    echo "<div class='$div_class'>";
    echo $message_text . "<br>";
    echo "</div>";
}

// print all messages between two users as rows in chat when the page is first loaded
function printMessages($user_obj, $friend_obj)
{
    $messages =  $user_obj->getMessages($friend_obj);
    while ($message = $messages->fetch_assoc()) {
        if ($message['user'] == $user_obj->getUsername()) {
            $message_div_class = 'messages_from_user_logged_in';
        }
        if ($message['user_to'] == $user_obj->getUsername()) {
            $message_div_class = 'messages_from_friend';
        }
        printMessage($message['text'], $message_div_class);
    }
}

?>
<script>
    // if getUpdatedMessages return success , print all messages between two users as rows in chat
    function processMessages(result) {
        $("#message_box").empty();
        for (var i = 0; i < result.length; i++) {
            var div = document.createElement("DIV");
            if (result[i]['sent_by'] == "<?php echo $user_obj->getUsername(); ?>") {
                div.className = 'messages_from_user_logged_in';
            }
            if (result[i]['sent_by'] == "<?php echo $friend_obj->getUsername(); ?>") {
                div.className = 'messages_from_friend';
            }
            div.innerHTML = result[i]['messages'][i];

            document.getElementById("message_box").appendChild(div);
        }
    }

    // retrieves up to date messages from server
    function getUpdatedMessages() {
        $.ajax({
            url: "includes/handlers/messages_handler.php",
            type: "POST",
            dataType: "json",
            data: {
                function: "get_messages",
                user_logged_in: "<?php echo $user_obj->getUsername(); ?>"
                friend: "<?php echo $friend_obj->getUsername(); ?>",
            },
            success: processMessages,
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
    }

    // reload all messages from database periodically
    $reload_messages_interval = 500;
    setInterval(getUpdatedMessages, $reload_messages_interval)
</script>

<!-- create page -->
<?php printProfileTitle($user_obj->getFirstAndLastName(), "h1"); ?>

<div class='container'>
    <div class='row'>
        <?php
        printProfilePicture($user_obj, true);
        printProfileUserInfo($user_obj, true);
        printProfileUserInfoEdit($user_obj);
        ?>
        <div id="profile_user_message_column">
            <div class='col-sm profile column'>
                <?php
                printProfileTitle($friend_obj->getFirstAndLastName(), "h4");

                echo "<div id='message_box'>";
                printMessages($user_obj, $friend_obj);
                echo "</div>";

                echo "<form id='messages_input' action='messages.php?friend_username=" . $friend_obj->getUsername() . "' method='post'>";
                echo "<textarea id='messages_input_text' name='message' rows='5' autofocus required></textarea>";
                echo "<button id='messages_input_button' type='submit' name='send_message' class='btn profile_user_buttons'>Send</button>";
                echo "</form>";
                ?>
                <a href='profile.php' id='profile_back_button' class='btn profile_user_buttons'>Back</a>
            </div>
            <div>
            </div>
        </div>

    </div>
</div>