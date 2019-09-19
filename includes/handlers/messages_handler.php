<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/classes/user.php');

$function = $_POST['function'];
$friend_username = $_POST['friend'];

$result = array();
$messages_to_user_logged_in = array();
$sent_by = array();

switch($function){
    case('get_state'):
            $size_of_file = 0;
            $messages = prepareAndExecuteQuery($con, "SELECT * FROM messages WHERE (user= ?  AND user_to = ?) OR (user = ? AND user_to = ?) ", 'ssss', [$_SESSION['username'],$friend_username , $friend_username, $_SESSION['username']]);
            while ($row = $messages->fetch_assoc()) {
                    array_push($messages_to_user_logged_in, $row['text']);
                    array_push($sent_by, $row['user']);
            }
}

$result['messages'] = $messages_to_user_logged_in;

$result['sent_by'] = $sent_by;
 
echo json_encode($result);
?>