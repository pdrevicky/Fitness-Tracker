<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<!-- log User out and redirect to register.php-->
<?php 
session_start();
session_destroy();
header("Location: ../../register.php");
exit();
?>