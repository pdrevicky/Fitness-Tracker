<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/classes/user.php');

$function = $_POST['function'];
$friendUsername = $_POST['friend'];
$user_logged_in = $_POST['userLoggedIn'];

$result = array();
$messagesToUserLoggedIn = array();
$sentBy = array();

switch($function){
    case('getState'):
            $sizeOfFile = 0;
            $messages = prepareAndExecuteQuery($con, "SELECT * FROM messages WHERE (user= ?  AND user_to = ?) OR (user = ? AND user_to = ?) ", 'ssss', [$user_logged_in,$friendUsername , $friendUsername, $user_logged_in]);
            while ($row = $messages->fetch_assoc()) {
                    array_push($messagesToUserLoggedIn, $row['text']);
                    array_push($sentBy, $row['user']);
            }
}

$result['messages'] = $messagesToUserLoggedIn;

$result['sentBy'] = $sentBy;
 
echo json_encode($result);
?>