<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/database_helpers.php');

if(isset($_POST["query"])){
    $output = ''; 
    $searchedName = $_POST["query"];
    $searchedName = str_replace(" ", "_", $searchedName); 
    $query = "SELECT * FROM users WHERE username LIKE '%".$searchedName."%' ";
    // $query = prepareAndExecuteQuery($con, "SELECT * FROM users WHERE username =  ", 's', [$user]);
    // $query = prepareAndExecuteQuery($con, "SELECT * FROM users WHERE username LIKE ? ", 's', [$searchedName]);
    // $query = ("SELECT * FROM users WHERE username LIKE CONCAT  ('%', ? , '%') ", 's', [$searchedName]);
    $result = mysqli_query($con, $query);
    $output = '<ul>';
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $username = $row['username'];
            $id = $row['id'];
            $username = str_replace("_", " ", $username);
            $username = ucwords($username);
            $username = preg_replace('/[0-9]+/', '', $username);
            $profilePic = $row['profile_pic'];
            $output .=  '<a class="search_friend_anchor" href="friend_profile.php?id='.$id.'">' . '<li>'.'<img class="profile_user_search_image" src="'.$profilePic.'">' . $username.  '</li>' .'</a>';
        }
    }
    else{
        $output .= '<li>Username Not Found</li>';
    }
    $output .= '</ul>' ;
    echo $output;
}


