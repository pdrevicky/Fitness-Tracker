<?php
ob_start(); //turns on output buffering

session_start();

$timezone = date_default_timezone_set("Europe/Bratislava");

// $con= mysqli_connect("localhost", "czpeterdrevick56", "5764F06675", "czpeterdrevicky"); // host: localhost , username for databse: default:"root", password for databes: defauld: "" , name for database : in this case "fitness"
$con = mysqli_connect("localhost", "czpeterdrevick56", "5764F06675", "czpeterdrevicky");
if(mysqli_connect_errno()) {
    echo "failed to connect:" . mysqli_connect_errono();
}

?>