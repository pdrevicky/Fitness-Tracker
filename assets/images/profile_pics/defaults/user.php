<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/database_helpers.php');
class User{
    // TOOD: rename $user to $userTable (je to SQL tabulka)
    private $user;   //privete only this user can see them - TODO: zmazat koment + vies vysvetlit rozdiel medzi private a public premennou/metodou?
    private $con;    // TODO: rename to dbConnection

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
            $now = time();
            $date_of_birth = strtotime($row['date_of_birth']);
            $difference = $now - $date_of_birth;
            
            $seconds_per_year = 31556926;
            $age = floor($difference / $seconds_per_year);
            
            if($age <= 0){
                echo "<br>";
                return;
            }
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

    // TODO: Aky je rozdiel medzi showNationality a getNationality?
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

    // TODO: zmazat a nechat len getFriendList, ktore vrati pole objektov triedy User (za kazdeho frienda 1 objekt)
    // show friend list
    // TODO: Rename: getFriendList
    public function friendList(){
        $username = $this->user['username'];
        // TODO: rename to friendsQuery
        $query = prepareAndExecuteQuery($this->con, "SELECT * FROM friends WHERE user = ? ", 's', [$username]);
        // TODO: Ak ma uzivatel 0 priatelov tak query == null? funguje to spravne? Lebo
        // inde sa myslim pouziva if (num_rows($query) > 0)
        if($query == null){
            return null;
        }
        //TODO: refactor and put to profile.php as printFriendLis();
        while ($row = $query->fetch_assoc()) {
            // TODO: rename to $friendUsername
            $userToLower = strtolower($row['user_friend']);
            $username = str_replace(" ", "_", $userToLower);
            // TODO: vytvor $friend_obj = new User($this->con, $friendUsername) a pouzi $friend_obj->getProfilePicture
            $profPic = prepareAndExecuteQuery($this->con, "SELECT profile_pic FROM users WHERE username = ? ", 's', [$username]);
            $profPicture = $profPic->fetch_assoc();
            $profPictureSrc = $profPicture['profile_pic'];
            $id = $row['friend_id'];
            // TODO: pouzi $friend_obj->getFirstName,  $friend_obj->getLastName, $friend_obj->getId
            // nreprevadzaj username na cele meno, pouzi first_name a last_name dostupne z query
            $userFriendNameNice = $row['user_friend'];
            $userFriendNameNice = str_replace("_", " ", $userToLower);
            $userFriendNameNice = preg_replace('/[0-9]+/', '', $userFriendNameNice);
            $userFriendNameNice = ucwords($userFriendNameNice);
            // TODO: friend_profile.php?id=" rename to friend_profile.php?friend_id="
            // (musis potom zmenit aj vo friend_profile.php $friend_id =  $_GET['friend_id'];
            //)
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

    // TODO: Pouziva sa? ak nie zmazat
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