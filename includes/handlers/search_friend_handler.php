<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/database_helpers.php');

// print under search bar similar names to name user is looking for
if (isset($_POST["query"])) {
    $output = '<ul>';

    $searched_name = $_POST["query"];
    $searched_name = str_replace(" ", "_", $searched_name);
    $searched_name = "%$searched_name%";
    $searched_name_query = prepareAndExecuteQuery($con, "SELECT * FROM users WHERE username LIKE ?", 's', [$searched_name]);
    $searched_name_exists = mysqli_num_rows($searched_name_query) > 0;

    if ($searched_name_exists) {
        while ($user = mysqli_fetch_array($searched_name_query)) {
            $output .=  '<a class="search_friend_anchor" href="friend_profile.php?id=' . $user['id'] . '">' . '<li>' .
                '<img class="profile_user_search_image" src="' . $user['profile_pic'] . '">' . 
                $user['first_name'] . " " . $user['last_name'] . '</li>' . '</a>';
        }
    } else {
        $output .= '<li>Name Not Found</li>';
    }
    $output .= '</ul>';
    echo $output;
}
