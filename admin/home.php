<?php
session_start();    //session start
if(!isset($_SESSION['username']))     //if session not found redirect to login_tanpa_database.php
{
    header('location: ../index.php?content=login.php');
}
?>
<h2>Welcome Admin!!</h2>