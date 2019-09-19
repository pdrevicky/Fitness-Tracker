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

   $extensions = array("jpeg", "jpg", "png");

   if (in_array($file_ext, $extensions) === false) {
      $file_name = "head_deep_blue.png";
      $new_route = "assets/images/profile_pics/defaults/" . $file_name;
      $query = prepareAndExecuteQuery($con, "UPDATE users SET profile_pic = ? WHERE username = ?", 'ss', [$new_route, $_SESSION['username']]);
      $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
   }

   if (empty($errors) == true) {
      move_uploaded_file($file_tmp, $_SERVER['DOCUMENT_ROOT'] . "/assets/images/profile_pics/defaults/" . $file_name);
   } else {
      print_r($errors);
   }
   header("Location: ../../profile.php");
   exit();
}
