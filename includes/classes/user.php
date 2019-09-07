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
        $firstThreeDigit = substr($row['phone_number'], 0, 3);
        $secondThreeDigit = substr($row['phone_number'], 3, 3);
        $thirdThreeDigit = substr($row['phone_number'], 6, 3);
        echo '+420 ' . $firstThreeDigit . ' ' . $secondThreeDigit . ' ' . $thirdThreeDigit; 
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
            $userToLower = strtolower($row['user_friend']);
            $username = str_replace(" ", "_", $userToLower);
            $profPic = prepareAndExecuteQuery($this->con, "SELECT profile_pic FROM users WHERE username = ? ", 's', [$username]);
            $profPiccture = $profPic->fetch_assoc();
            $profPictureSrc = $profPiccture['profile_pic'];
            $id = $row['friend_id'];
            $userFriendNameNice = $row['user_friend'];
            $userFriendNameNice = str_replace("_", " ", $userToLower);
            $userFriendNameNice = preg_replace('/[0-9]+/', '', $userFriendNameNice);
            $userFriendNameNice = ucwords($userFriendNameNice);
            echo "<div id='$row[id]' class='row profile_friends_list'>";
                echo "<div class='col-sm-9'>";
                    echo "<a href='friend_profile.php?id=".$id." '' class='btn'>";
                        echo '<img class="profile_user_friendList_image" src="'.$profPictureSrc.'">';
                        echo $userFriendNameNice;
                    echo "</a>";
                echo "</div>";
                echo "<div class='col-sm-2'>";
                    echo "<a  href='messages.php?friendUsername=".$username."' >";
                        echo "<i class='fas fa-comment profile_userFriend_message'>";
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
            $userToLower = strtolower($row['user_friend']);
            $username = str_replace(" ", "_", $userToLower);
            $profPic = prepareAndExecuteQuery($this->con, "SELECT profile_pic FROM users WHERE username = ? ", 's', [$username]);
            $profPiccture = $profPic->fetch_assoc();
            $profPictureSrc = $profPiccture['profile_pic'];
            echo "<div class='profile_friends_list'>";
                echo "<div>";
                    echo "<p href='friend_profile.php?friendUsername=".$username." '' >";
                        echo '<img class="profile_user_friendList_image" src="'.$profPictureSrc.'">';
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
                echo "<div class='userLoggedInMessages'>";
                    echo $row['text'] . "<br>";
                echo "</div>";
            }
            if($row['user_to'] == $user_logged_in){
                echo "<div class='userLoggedInMessagesFromFriend''>";
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