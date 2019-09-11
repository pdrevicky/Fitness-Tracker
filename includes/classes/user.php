<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/database_helpers.php');
class User{
    private $user;   //privete only this user can see them
    private $con;

    public function __construct($con, $user){ 
        $this->con = $con;
        $query = prepareAndExecuteQuery($con, "SELECT * FROM users WHERE username= ? ", 's', [$user]);
        $this->user = mysqli_fetch_array($query);
    }
    
    public function submitPost($cycling, $running, $swimming,
                               $ba_deadlift, $ba_barbell,$ba_machine,
                               $b_dumbbell,$b_barbell,$b_machine,
                               $c_benchpress,$c_dumbbell,$c_machine,
                               $l_legPress,$l_squats,$l_calf, 
                               $s_dumbbell,$s_barbell,$s_machine,
                               $t_dumbbell,$t_barbell,$t_machine,
                               $body,$day_of_training,$user_logged_in
                            ) {
        $body = strip_tags($body); //remove html tags
        $body = mysqli_real_escape_string($this->con, $body); //do not act on special characters

        //Get username
        $added_by = $user_logged_in;

        //insert post        
        prepareAndExecuteQuery( $this->con, "INSERT INTO posts VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",'iiiiiiiiiiiiiiiiiiiiiisss', ['', 
        $cycling, $running, $swimming, 
        $ba_deadlift, $ba_barbell, $ba_machine,
        $b_dumbbell, $b_barbell, $b_machine,
        $c_benchpress, $c_dumbbell, $c_machine,
        $l_legPress, $l_squats, $l_calf, 
        $s_dumbbell, $s_barbell, $s_machine,
        $t_dumbbell, $t_barbell, $t_machine,
        $body, $added_by, $day_of_training]);

    }

    public function updateProfileUserInfo($date_of_birth, $nationality, $email, $phone_number, $user_logged_in){
        prepareAndExecuteQuery($this->con, "UPDATE users SET date_of_birth = ? WHERE username = ?", 'ss', [$date_of_birth, $user_logged_in] );
        prepareAndExecuteQuery($this->con, "UPDATE users SET nationality = ? WHERE username = ?", 'ss', [$nationality, $user_logged_in] );
        prepareAndExecuteQuery($this->con, "UPDATE users SET email = ? WHERE username = ?", 'ss', [$email, $user_logged_in] );
        prepareAndExecuteQuery($this->con, "UPDATE users SET phone_number = ? WHERE username = ?", 'is', [$phone_number, $user_logged_in] );
    
    }

    public function addFriend($friend_id, $user_logged_in, $friend_name){
        //check if user exist
        if ($user_logged_in == $friend_name){
            return null;
        }
        $user_logged_in_friends_ids = prepareAndExecuteQuery($this->con, "SELECT friend_id FROM friends WHERE user= ? ", 's', [$user_logged_in]);
        while ($row = $user_logged_in_friends_ids->fetch_assoc()){
            if($row['friend_id'] == $friend_id){
                return null;
            }
        }
        $id = $friend_id;
        $friend = $friend_name;
        $users = mysqli_query($this->con, "SELECT * FROM users");
        while ($row = $users->fetch_assoc()) {
           if($row['id'] == $friend_id){
            prepareAndExecuteQuery( $this->con, "INSERT INTO friends VALUES (?,?,?,?)", 'issi', [ '', $user_logged_in, $friend, $id] );
           }
        }

    }

    public function removeFriend($friend_id, $user_logged_in){
        $friend_id = (int)$friend_id;
        prepareAndExecuteQuery( $this->con, "DELETE FROM friends WHERE user = ? AND friend_id = ? ", 'si', [$user_logged_in, $friend_id]);
    }

    public function addMessage($message, $user_logged_in, $friend_username){
        $message = strip_tags($message);  //Remove html tags
        $message = rtrim($message);
        prepareAndExecuteQuery($this->con, "INSERT INTO messages VALUES (?,?,?,?)", 'isss', [ '', $user_logged_in, $friend_username, $message] );
    }

    public function getUsername(){
        return $this->user['username'];
    }

    public function getFirstAndLastName() {
        $username = $this->user['username'];
        $query = prepareAndExecuteQuery($this->con, "SELECT first_name, last_name FROM users WHERE username= ? ", 's', [$username]);
        $row = mysqli_fetch_array($query);
        return $row['first_name'] . " " . $row['last_name'];
    }

    public function getEmail(){
        $username = $this->user['username'];
        $query = prepareAndExecuteQuery($this->con, "SELECT email FROM users WHERE username= ? ", 's', [$username]);
        $row = mysqli_fetch_array($query);
        return $row['email'];
    }

    public function getAge(){
        $username = $this->user['username'];
        $query = prepareAndExecuteQuery($this->con, "SELECT date_of_birth FROM users WHERE username= ? ", 's', [$username]);
        $row = mysqli_fetch_array($query);
        if ($row['date_of_birth'] == "0000-00-00"){
           echo "<br>";
        }
        else {
            //Get the current UNIX timestamp.
            $now = time();
            
            //Get the timestamp of the person's date of birth.
            $dob = strtotime($row['date_of_birth']);
            
            //Calculate the difference between the two timestamps.
            $difference = $now - $dob;
            
            //There are 31556926 seconds in a year.
            $age = floor($difference / 31556926);
            
            if($age <= 0){
                echo "<br>";
                return;
            }

            //Print it out.
            echo $age;
        }
    }

    public function getDateOfBirth(){
        $username = $this->user['username'];
        $query = prepareAndExecuteQuery($this->con, "SELECT date_of_birth FROM users WHERE username= ? ", 's', [$username]);
        $row = mysqli_fetch_array($query);
        echo $row["date_of_birth"];
    }


    public function getPhoneNumber(){
        $username = $this->user['username'];
        $query = prepareAndExecuteQuery($this->con, "SELECT phone_number FROM users WHERE username= ? ", 's', [$username]);
        $row = mysqli_fetch_array($query);
        echo $row['phone_number'];
    }

    public function showPhoneNumber(){
        $username = $this->user['username'];
        $query = prepareAndExecuteQuery($this->con, "SELECT phone_number FROM users WHERE username= ? ", 's', [$username]);
        $row = mysqli_fetch_array($query);
        if ($row['phone_number'] == 0) { echo "<br>";}
        else{
        $first_three_digits = substr($row['phone_number'], 0, 3);
        $secont_three_digits = substr($row['phone_number'], 3, 3);
        $third_three_digits = substr($row['phone_number'], 6, 3);
        $phone_number_with_spaces = $first_three_digits . ' ' . $secont_three_digits . ' ' . $third_three_digits;
        echo '+420 ' .  $phone_number_with_spaces;
        }
    }

    public function showNationality(){
        $username = $this->user['username'];
        $query = prepareAndExecuteQuery($this->con, "SELECT nationality FROM users WHERE username= ? ", 's', [$username]);
        $row = mysqli_fetch_array($query);
        if ($row['nationality'] == ""){  echo "<br>";}
        echo $row['nationality'];
    }

    public function getNationality(){
        $username = $this->user['username'];
        $query = prepareAndExecuteQuery($this->con, "SELECT nationality FROM users WHERE username= ? ", 's', [$username]);
        $row = mysqli_fetch_array($query);
        if ($row['nationality'] == ""){  echo "";}
        echo $row['nationality'];
    }

    // show firiend list
    public function friendList(){
        $username = $this->user['username'];
        $query = prepareAndExecuteQuery($this->con, "SELECT * FROM friends WHERE user = ? ", 's', [$username]);
        if($query == null){
            return null;
        }
        while ($row = $query->fetch_assoc()) {
            $user_friend_to_lower = strtolower($row['user_friend']);
            $friend_username = str_replace(" ", "_", $user_friend_to_lower);
            $friend_profile_picture = prepareAndExecuteQuery($this->con, "SELECT profile_pic FROM users WHERE username = ? ", 's', [$friend_username]);
            $friend_profile_picture = $friend_profile_picture->fetch_assoc();
            $friend_profile_picture_src = $friend_profile_picture['profile_pic'];
            $id = $row['friend_id'];
            $friend_name_to_user_friend_list = str_replace("_", " ", $user_friend_to_lower);
            $friend_name_to_user_friend_list = preg_replace('/[0-9]+/', '', $friend_name_to_user_friend_list);
            $friend_name_to_user_friend_list = ucwords($friend_name_to_user_friend_list);
            echo "<div id='$row[id]' class='row profile_friends_list'>";
                echo "<div class='col-sm-9'>";
                    echo "<a href='friend_profile.php?id=".$id." '' class='btn'>";
                        echo '<img class="profile_user_friend_list_image" src="'.$friend_profile_picture_src.'">';
                        echo $friend_name_to_user_friend_list;
                    echo "</a>";
                echo "</div>";
                echo "<div class='col-sm-2'>";
                    echo "<a  href='messages.php?friend_username=".$friend_username."' >";
                        echo "<i class='fas fa-comment profile_user_friend_message'>";
                        echo "</i>";
                    echo  "</a>";
                echo "</div>";
            echo "</div>";
          
        }
    }

    public function friendFriendList(){
        $username = $this->user['username'];
        $query = prepareAndExecuteQuery($this->con, "SELECT * FROM friends WHERE user = ? ", 's', [$username]);
        if($query == null){
            return null;
        }
        while ($row = $query->fetch_assoc()) {
            $user_friend_to_lower = strtolower($row['user_friend']);
            $username = str_replace(" ", "_", $user_friend_to_lower);
            $profPic = prepareAndExecuteQuery($this->con, "SELECT profile_pic FROM users WHERE username = ? ", 's', [$username]);
            $profPiccture = $profPic->fetch_assoc();
            $profPictureSrc = $profPiccture['profile_pic'];
            echo "<div class='profile_friends_list'>";
                echo "<div>";
                    echo "<p href='friend_profile.php?friend_username=".$username." '' >";
                        echo '<img class="profile_user_friend_list_image" src="'.$profPictureSrc.'">';
                        $friendsFriend = str_replace("_", " ", $row['user_friend']);
                        $friendsFriend = preg_replace('/[0-9]+/', '', $friendsFriend);
                        $friendsFriend = ucwords($friendsFriend);
                        echo $friendsFriend;
                    echo "</p>";
                echo "</div>";
            echo "</div>";
        }
    }


    public function getMessages($user_logged_in, $friend){
        $messages = prepareAndExecuteQuery($this->con, "SELECT * FROM messages WHERE (user = ? AND user_to= ?) OR (user = ? AND user_to = ?)", 'ssss', [$user_logged_in , $friend, $friend, $user_logged_in]);
        while ($row = $messages->fetch_assoc()) {
            if($row['user'] == $user_logged_in){
                echo "<div class='user_loggen_in_messages'>";
                    echo $row['text'] . "<br>";
                echo "</div>";
            }
            if($row['user_to'] == $user_logged_in){
                echo "<div class='user_logged_in_messages_from_friend''>";
                    echo $row['text'] . "<br>";
                echo "</div>";
            }
        }
    }

    public function getLastMessageToId($user_logged_in, $friend){
        $lastID = '';
        $messages = prepareAndExecuteQuery($this->con, "SELECT * FROM messages WHERE (user = ? AND user_to = ?)", 'ss', [$user_logged_in, $friend]);
        while ($row = $messages->fetch_assoc()) {
            $lastID = $row['id'];
        }
        if ($lastID != null){
            return $lastID;
        }
    }

    public function isClosed(){
        $username = $this->user['username'];
        $query = prepareAndExecuteQuery($this->con, "SELECT user_closed FROM users WHERE username = ? ", 's', [$username]);
        $row = mysqli_fetch_array($query);

        if($row['user_closed'] == 'yes')
            return true;
        else 
            return false;
    }


}

?>