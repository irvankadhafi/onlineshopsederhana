
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
        $vcontent =$_GET['content'];
    }

?>
<html>
<head>
    <title>TUGAS</title>
<style>
    table, th, td {
        border-collapse: collapse;
        border: 1px solid black;
        /*text-align: center;*/
        margin: auto;
    }
</style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

</head>
<body>

<table style="width:800px">
    <tr style="height: 100px" class="text-center">
        <td>
            <h1>Irvan's Store</h1>
<!--            <img src="polban.png" alt="Polban" align="left" height="110px" width="110px">-->
<!--            <img src="github.png" alt="Github" align="right" height="110px" width="110px">-->
        </td>
    </tr>

    <tr style="height: 50px" class="text-center">
        <td><a href="index.php?content=<?php echo 'home.php' ?>">HOME</a>     |
            <a href="index.php?content=<?php echo 'insertproduct.php' ?>">INSERT</a>     |
            <a href="index.php?content=<?php echo 'product.php' ?>">PRODUCT</a>     |
            <a href="index.php?content=<?php echo 'cartlist.php' ?>">CART</a>


        </td>
    </tr>

    <tr style="height: 200px">
        <td><?php include $vcontent; ?></td>
    </tr>

    <tr style="height: 100px">
    </tr>
</table>
</body>
</html>
