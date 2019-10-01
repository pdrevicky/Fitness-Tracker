<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/user.php');

// script parameters
$function = $_POST['function'];
$username = $_POST['user_logged_in'];
$friend_username = $_POST['friend'];

// script output
$result = array();

// get all messages between the user and his friend
switch ($function) {
        case ('get_messages'):
                $messages = prepareAndExecuteQuery(
                        $con,
                        "SELECT * FROM messages WHERE (user= ?  AND user_to = ?) OR (user = ? AND user_to = ?) ",
                        'ssss',
                        [$username, $friend_username, $friend_username, $username]
                );
                while ($message = $messages->fetch_assoc()) {
                        array_push($result, ['sent_by' => $message['user'], "text" => $message['text']]);
                }
}

echo json_encode($result);
