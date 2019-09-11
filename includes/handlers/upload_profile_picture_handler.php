<?php
   require_once($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');
   require_once($_SERVER['DOCUMENT_ROOT'].'/includes/classes/user.php');
   require_once($_SERVER['DOCUMENT_ROOT'].'/includes/database_helpers.php');

   if(isset($_FILES['image'])){
      $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size =$_FILES['image']['size'];
      $file_tmp =$_FILES['image']['tmp_name'];
      $file_type=$_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      
      $newRoute = "assets/images/profile_pics/defaults/" . $file_name = $_FILES['image']['name'];
      $query = prepareAndExecuteQuery($con, "UPDATE users SET profile_pic = ? WHERE username = ?", 'ss', [$newRoute, $user_logged_in]);

      $extensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$extensions)=== false){
         $file_name = "head_deep_blue.png";
         $newRoute = "assets/images/profile_pics/defaults/" . $file_name;
         $query = prepareAndExecuteQuery($con, "UPDATE users SET profile_pic = ? WHERE username = ?", 'ss', [$newRoute, $user_logged_in]);
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"assets/images/profile_pics/defaults/".$file_name);
         echo "Success";
      }else{
         print_r($errors);
      }
      header("Location: ../../profile.php");
      exit();
   }
?>