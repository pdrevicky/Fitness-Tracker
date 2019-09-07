<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/database_helpers.php');
class Post{
    private $user_obj;   
    private $con;

    public function __construct($con, $user){ 
        $this->con = $con;
        $this->user_obj = new User($con, $user);
    }

    public function submitPost($cycling, $running, $swimming,
                               $ba_deadlift, $ba_barbell,$ba_machine,
                               $b_dumbbell,$b_barbell,$b_machine,
                               $c_benchpress,$c_dumbbell,$c_machine,
                               $l_legPress,$l_squats,$l_calf, 
                               $s_dumbbell,$s_barbell,$s_machine,
                               $t_dumbbell,$t_barbell,$t_machine,
                               $body,$day_of_training
                            ) {
        $body = strip_tags($body); //remove html tags
        $body = mysqli_real_escape_string($this->con, $body); //do not act on special characters
        $check_empty = preg_replace('/\s+/', '', $body); //Deletes all spaces

        
        //Current date and time
        $date_added = date("Y-m-d H:i:s");

        //Get username
        $added_by = $this->user_obj->getUsername();
        

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

        $returned_id = mysqli_insert_id($this->con);  //return the id of post just submited
        }

        public function updateProfileUserInfo($dateOfBirth, $nationality, $email, $phoneNumber, $user_logged_in){
            prepareAndExecuteQuery($this->con, "UPDATE users SET date_of_birth = ? WHERE username = ?", 'ss', [$dateOfBirth, $user_logged_in] );
            prepareAndExecuteQuery($this->con, "UPDATE users SET nationality = ? WHERE username = ?", 'ss', [$nationality, $user_logged_in] );
            prepareAndExecuteQuery($this->con, "UPDATE users SET email = ? WHERE username = ?", 'ss', [$email, $user_logged_in] );
            prepareAndExecuteQuery($this->con, "UPDATE users SET phone_number = ? WHERE username = ?", 'is', [$phoneNumber, $user_logged_in] );
        
        }

        public function addFriend($friendId, $user_logged_in, $friendName){
            //check if user exist
            if ($user_logged_in == $friendName){
                return null;
            }
            $id = $friendId;
            $friend = $friendName;
            $users = mysqli_query($this->con, "SELECT * FROM users");
            while ($row = $users->fetch_assoc()) {
               if($row['id'] == $friendId){
                prepareAndExecuteQuery( $this->con, "INSERT INTO friends VALUES (?,?,?,?)", 'issi', [ '', $user_logged_in, $friend, $id] );
               }
            }

        }

        public function addMessage($message, $user_logged_in, $friendUsername){
            $message = strip_tags($message);  //Remove html tags
            $message = rtrim($message);
            prepareAndExecuteQuery( $this->con, "INSERT INTO messages VALUES (?,?,?,?)", 'isss', [ '', $user_logged_in, $friendUsername, $message] );
        }

    }
?>