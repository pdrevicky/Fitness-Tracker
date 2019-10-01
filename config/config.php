<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<!-- set connection to database and start session -->
<?php
ob_start();
session_start();
$timezone = date_default_timezone_set("Europe/Bratislava");
$con = mysqli_connect("localhost", "czpeterdrevick56", "5764F06675", "czpeterdrevicky");
if (mysqli_connect_errno()) {
    echo "failed to connect:" . mysqli_connect_errono();
}
?>