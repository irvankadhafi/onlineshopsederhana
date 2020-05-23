<?php
session_start();
if(!isset($_SESSION['username']))     //if session not found redirect to login_tanpa_database.php
{
    header('location: ../index.php?content=login.php');
}
if(!isset($_GET['content']))
{
    $vcontent = 'home.php';
}else
{
    $vcontent = $_GET['content'];
}
unset($_SESSION["username"]);
?>
<h2>Berhasil Logout</h2>
<center>
    <a id="btncheckout" href ="../index.php?content=<?php echo 'login.php' ?>">Login Ulang?</a>
</center>
