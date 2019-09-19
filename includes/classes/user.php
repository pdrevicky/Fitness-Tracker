<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/database_helpers.php');
class User
{
    private $userTable;
    private $dbConnection;

    public function __construct($connection, $username)
    {
        $this->dbConnection = $connection;
        $query = prepareAndExecuteQuery($connection, "SELECT * FROM users WHERE username= ? ", 's', [$username]);
        $this->userTable = mysqli_fetch_array($query);   //this user all data from table 
    }

    public function submitPost(
        $cycling,
        $running,
        $swimming,
        $ba_deadlift,
        $ba_barbell,
        $ba_machine,
        $b_dumbbell,
        $b_barbell,
        $b_machine,
        $c_benchpress,
        $c_dumbbell,
        $c_machine,
        $l_legPress,
        $l_squats,
        $l_calf,
        $s_dumbbell,
        $s_barbell,
        $s_machine,
        $t_dumbbell,
        $t_barbell,
        $t_machine,
        $body,
        $day_of_training,
        $user_logged_in
    ) {
        $body = strip_tags($body);
        $body = mysqli_real_escape_string($this->dbConnection, $body); //do not act on special characters

        $added_by = $user_logged_in;

        // insert post        
        prepareAndExecuteQuery($this->dbConnection, "INSERT INTO posts VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", 'iiiiiiiiiiiiiiiiiiiiiisss', [
            '',
            $cycling, $running, $swimming,
            $ba_deadlift, $ba_barbell, $ba_machine,
            $b_dumbbell, $b_barbell, $b_machine,
            $c_benchpress, $c_dumbbell, $c_machine,
            $l_legPress, $l_squats, $l_calf,
            $s_dumbbell, $s_barbell, $s_machine,
            $t_dumbbell, $t_barbell, $t_machine,
            $body, $added_by, $day_of_training
        ]);
    }

    public function updateProfileUserInfo($date_of_birth, $nationality, $email, $phone_number, $user_logged_in)
    {
        prepareAndExecuteQuery($this->dbConnection, "UPDATE users SET date_of_birth = ?, nationality = ?, email = ?, phone_number = ?  WHERE username = ?", 'sssis', [$date_of_birth, $nationality, $email, $phone_number, $user_logged_in]);
    }

    public function addFriend($friend_id, $user_logged_in, $friend_name)
    {
        // check if user exist
        if ($user_logged_in == $friend_name) {
            return null;
        }
        $user_logged_in_friends_ids = prepareAndExecuteQuery($this->dbConnection, "SELECT friend_id FROM friends WHERE user= ? ", 's', [$user_logged_in]);
        while ($row = $user_logged_in_friends_ids->fetch_assoc()) {
            if ($row['friend_id'] == $friend_id) {
                return null;
            }
        }
        $id = $friend_id;
        $friend = $friend_name;
        $users = mysqli_query($this->dbConnection, "SELECT * FROM users");
        while ($row = $users->fetch_assoc()) {
            if ($row['id'] == $friend_id) {
                prepareAndExecuteQuery($this->dbConnection, "INSERT INTO friends VALUES (?,?,?,?)", 'issi', ['', $user_logged_in, $friend, $id]);
            }
        }
    }

    public function removeFriend($friend_id, $user_logged_in)
    {
        $friend_id = (int) $friend_id;
        prepareAndExecuteQuery($this->dbConnection, "DELETE FROM friends WHERE user = ? AND friend_id = ? ", 'si', [$user_logged_in, $friend_id]);
    }

    public function addMessage($message, $user_logged_in, $friend_username)
    {
        $message = strip_tags($message);
        $message = rtrim($message);
        prepareAndExecuteQuery($this->dbConnection, "INSERT INTO messages VALUES (?,?,?,?)", 'isss', ['', $user_logged_in, $friend_username, $message]);
    }

    public function getProfilePicture()
    {
        return $this->userTable['profile_pic'];
    }

    public function getUsername()
    {
        return $this->userTable['username'];
    }

    public function getFirstAndLastName()
    {
        return $this->userTable['first_name'] . " " . $this->userTable['last_name'];
    }

    public function getEmail()
    {
        return $this->userTable['email'];
    }

    public function getId()
    {
        return $this->userTable['id'];
    }

    public function getAge()
    {
        if ($this->userTable['date_of_birth'] == "0000-00-00") {
            echo "<br>";
        } else {
            //get the current UNIX timestamp.
            $now = time();

            //get the timestamp of the person's date of birth.
            $dob = strtotime($this->userTable['date_of_birth']);

            //calculate the difference between the two timestamps.
            $difference = $now - $dob;

            //there are 31556926 seconds in a year.
            $age = floor($difference / 31556926);

            if ($age <= 0) {
                echo "<br>";
                return;
            }

            //print it out.
            echo $age;
        }
    }

    public function getDateOfBirth()
    {
        $this->userTable['date_of_birth'];
    }

    public function getPhoneNumber()
    {
        if ($this->userTable['phone_number'] == null) {
            return null;
        }
        return $this->userTable['phone_number'];
    }

    public function getNationality()
    {
        if ($this->userTable['Nationality'] == "") {
            echo "";
        }
        return $this->userTable['Nationality'];
    }

    // show firiend list
    public function getFriendList()
    {
        $friends_table = prepareAndExecuteQuery($this->dbConnection, "SELECT * FROM friends WHERE user = ? ", 's', [$this->userTable['username']]);
        if ($friends_table == null) {
            return null;
        }
        $friends_objects_array = [];
        while ($row = $friends_table->fetch_assoc()) {
            $friend_username = $row['user_friend'];
            $user_friend_obj = new User($this->dbConnection, $friend_username);
            array_push($friends_objects_array, $user_friend_obj);
        }
        return $friends_objects_array;
    }

    public function getMessages($user_logged_in, $friend)
    {        
        $messages = prepareAndExecuteQuery($this->dbConnection, "SELECT * FROM messages WHERE (user = ? AND user_to= ?) OR (user = ? AND user_to = ?)", 'ssss', [$user_logged_in, $friend, $friend, $user_logged_in]);
        return $messages;
    }

    public function getLastMessageToId($user_logged_in, $friend)
    {
        $lastID = '';
        $messages = prepareAndExecuteQuery($this->dbConnection, "SELECT * FROM messages WHERE (user = ? AND user_to = ?)", 'ss', [$user_logged_in, $friend]);
        while ($row = $messages->fetch_assoc()) {
            $lastID = $row['id'];
        }
        if ($lastID != null) {
            return $lastID;
        }
    }
}
