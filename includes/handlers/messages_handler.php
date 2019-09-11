<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/classes/user.php');

$function = $_POST['function'];
$friend_username = $_POST['friend'];
$user_logged_in = $_POST['user_logged_in'];

$result = array();
$messages_to_user_logged_in = array();
$sent_by = array();

switch($function){
    case('get_state'):
            $size_of_file = 0;
            $messages = prepareAndExecuteQuery($con, "SELECT * FROM messages WHERE (user= ?  AND user_to = ?) OR (user = ? AND user_to = ?) ", 'ssss', [$user_logged_in,$friend_username , $friend_username, $user_logged_in]);
            while ($row = $messages->fetch_assoc()) {
                    array_push($messages_to_user_logged_in, $row['text']);
                    array_push($sent_by, $row['user']);
            }
}

$result['messages'] = $messages_to_user_logged_in;

$result['sent_by'] = $sent_by;
 
echo json_encode($result);
?>