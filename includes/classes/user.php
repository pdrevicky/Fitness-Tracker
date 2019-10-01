<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/database_helpers.php');

// contains functionality for working with user's data and accessing database
class User
{
    // sql table with user's profile info
    private $userTable;
    private $dbConnection;

    public function __construct($connection, $username)
    {
        $this->dbConnection = $connection;
        $query = prepareAndExecuteQuery($connection, "SELECT * FROM users WHERE username= ? ", 's', [$username]);
        $this->userTable = mysqli_fetch_array($query);
    }

    // submits training session as a post into the database
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
        $user_notes,
        $day_of_training
    ) {
        $user_notes = strip_tags($user_notes);
        $user_notes = mysqli_real_escape_string($this->dbConnection, $user_notes); // do not act on special characters

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
            $user_notes, $this->userTable['username'], $day_of_training
        ]);
    }

    public function updateProfileUserInfo($date_of_birth, $nationality, $email, $phone_number)
    {
        prepareAndExecuteQuery(
            $this->dbConnection,
            "UPDATE users SET date_of_birth = ?, nationality = ?, email = ?, phone_number = ?  WHERE username = ?",
            'sssis',
            [$date_of_birth, $nationality, $email, $phone_number, $this->userTable['username']]
        );
    }

    public function addFriend($person_obj)
    {
        // make sure user cannot befriend themselves
        if ($this->userTable['username'] == $person_obj->getUsername()) {
            return;
        }

        // do not add friend if user has befriended him already
        if ($this->isFriendWith($person_obj)) {
            return;
        }

        // insert friendship into database
        prepareAndExecuteQuery($this->dbConnection, "INSERT INTO friends VALUES (?,?,?,?)", 'issi', ['', $this->userTable['username'], $person_obj->getUsername(), $person_obj->getId()]);
        prepareAndExecuteQuery($this->dbConnection, "INSERT INTO friends VALUES (?,?,?,?)", 'issi', ['',$person_obj->getUsername(),$this->userTable['username'], $this->userTable['id']]);
    }

    public function removeFriend($friend_obj)
    {
        prepareAndExecuteQuery($this->dbConnection, "DELETE FROM friends WHERE user = ? AND friend_id = ? ", 'si', [$this->userTable['username'], (int) $friend_obj->getId()]);
        prepareAndExecuteQuery($this->dbConnection, "DELETE FROM friends WHERE user = ? AND friend_id = ? ", 'si', [$friend_obj->getUsername(),$this->userTable['id'] ]);
    }

    public function addMessage($message, $friend_obj)
    {
        $message = strip_tags($message);
        $message = rtrim($message);
        prepareAndExecuteQuery($this->dbConnection, "INSERT INTO messages VALUES (?,?,?,?)", 'isss', ['', $this->userTable['username'], $friend_obj->getUsername(), $message]);
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
        return strtolower($this->userTable['email']);
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
            // calculate age
            $now = time();
            $date_of_birth = strtotime($this->userTable['date_of_birth']);
            $seconds_per_year = 31556926;
            $age = floor(($now - $date_of_birth) / $seconds_per_year);

            if ($age <= 0) {
                echo "<br>";
                return;
            }

            return $age;
        }
    }

    public function getDateOfBirth()
    {
        return $this->userTable['date_of_birth'];
    }

    public function getPhoneNumber()
    {
        return $this->userTable['phone_number'];
    }

    public function getNationality()
    {
        return $this->userTable['Nationality'];
    }

    private static function userCompare($user1, $user2)
    {
        return strcmp($user1->getUsername(), $user2->getUsername());
    }
    
    //  returns list of user's friends as user objects sorted by username
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

        usort($friends_objects_array, array("User", "userCompare"));

        return  $friends_objects_array;
    }

    public function isFriendWith($person)
    {
        foreach ($this->getFriendList() as $friend) {
            if ($friend->getUsername() == $person->getUsername()) {
                return true;
            }
        }
        return false;
    }

    // returns all messages between user and his friend
    public function getMessages($friend_obj)
    {
        $messagesTable = prepareAndExecuteQuery(
            $this->dbConnection,
            "SELECT * FROM messages WHERE (user = ? AND user_to= ?) OR (user = ? AND user_to = ?)",
            'ssss',
            [$this->userTable['username'], $friend_obj->getUsername(), $friend_obj->getUsername(), $this->userTable['username']]
        );
        return $messagesTable;
    }
}
