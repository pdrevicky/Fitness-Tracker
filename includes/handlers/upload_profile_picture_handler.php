<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<!-- Upload a new profile picture for the user -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/user.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/database_helpers.php');

if (isset($_FILES['image'])) {
   $errors = array();
   $file_name = $_FILES['image']['name'];
   $file_tmp = $_FILES['image']['tmp_name'];
   $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

   $new_route = "assets/images/profile_pics/defaults/" . $file_name = $_FILES['image']['name'];
   $query = prepareAndExecuteQuery($con, "UPDATE users SET profile_pic = ? WHERE username = ?", 'ss', [$new_route, $_SESSION['username']]);

   $allowed_extensions = array("jpeg", "jpg", "png");
   if (in_array($file_ext, $allowed_extensions) === false) {
      prepareAndExecuteQuery($con, "UPDATE users SET profile_pic = ? WHERE username = ?", 'ss', [$user_obj->getProfilePicture(), $_SESSION['username']]);
   }

   $user_obj = new User($con, $_SESSION['username']);
   if (empty($errors) == true) {
      move_uploaded_file($file_tmp, $_SERVER['DOCUMENT_ROOT'] . "/assets/images/profile_pics/defaults/" . $file_name);
   } else {
      echo($errors);
   }
   header("Location: ../../profile.php");
   exit();
}
