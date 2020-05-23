<?php
//    session_start();    //session start
//    if(!isset($_SESSION['username']))     //if session not found redirect to login_tanpa_database.php
//    {
//        header('location: login_tanpa_database.php');
//    }
    if(!isset($_GET['content']))
    {
        $vcontent = 'home.php';
    }else
    {
        $vcontent = $_GET['content'];
    }

?>
<html>
<head>
    <title>IrvanKadhafi's Store</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="title">
        IrvanKadhafi's <span style="color: #11998e">Store</span> <br>
    </div>

    <div class= "admin">
        <a href= "login.php">Login</a>
    </div>

    <div class="navigation_bar">
        <ul>
            <li><a class="active" href="index.php?content=<?php echo 'home.php' ?>">Home</a></li>
            <li><a href="index.php?content=<?php echo 'catalog.php' ?>">Catalog</a></li>
            <li><a href="index.php?content=<?php echo 'cartlist.php' ?>">Cart</a></li>
            <li><a href="index.php?content=<?php echo 'cartlist.php' ?>">Purchases</a></li>
        </ul>
    </div>

    <div class="main-section" style="margin-top:15px">
        <?php include $vcontent; ?>
    </div>
</body>
</html>
