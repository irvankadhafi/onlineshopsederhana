<?php
require_once ("connector.php");
session_start();    // Session start

if(isset($_GET['pesan'])) {
    if ($_GET['pesan'] == "sukses") {
        $err="Anda Berhasil Logout";
    }
}
if(isset($_POST['check']))
{
    $username=$_POST['username'];    // set variable value
    $password=$_POST['password'];        // set variable value
    $query = $conn->prepare("SELECT * FROM admin where username = ?");
    $query->execute(array($username));
    $hasil = $query->fetch();
    if($query->rowCount() == 0) {
        echo "<div align='center'>Username Belum Terdaftar! <a href='login_dengan_database.php'>Back</a></div>";
    } else {
        if($password <> $hasil['password']) {
            echo "<div align='center'>Password salah! <a href='login_dengan_database.php'>Back</a></div>";
        } else {
            $_SESSION['username'] = $hasil['username'];
            header('location:./admin/');
        }
    }
}
?>

<html>
<head>
    <title>Login Admin IrvanKadhafi Store</title>
</head>
<body>
<Center>
    <?php if(isset($err)){ echo $err; } ?>      <!-- Print Error -->

    <form method="POST" name="loginauth" target="_self">

        Username: <input name="username" size="20" type="text" />
        <br/><br/>
        Password: <input name="password" size="20" type="password" />
        <br/><br/>
        <input name="check" type="submit" value="Login" />

    </form>
</center>
</body>
</html>
