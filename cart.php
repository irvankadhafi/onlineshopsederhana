<?php
session_start();
require("connector.php");
//if(isset($_POST['confirmbrg'])) {
    $query = $conn->prepare("SELECT * FROM barang WHERE id_barang = :id");
    $query->bindParam(":id", $_GET['id']);
    $query->execute();

    if($query->rowCount() == 0){
        die("Error: ID Tidak Ditemukan");
    }else{
        $data = $query->fetch();
    }


    for($n=1;isset($_SESSION['id_barang'][$n]);$n++);

    $_SESSION["id_barang"][$n] = $data['id_barang'];
    $_SESSION["nama_barang"][$n] = $data['nama_barang'];
    $_SESSION["harga_barang"][$n] = $data['harga_barang'];
    $_SESSION["img_barng"][$n] = $data['img_barng'];
    $_SESSION["jlh_barang"][$n] = $_COOKIE["jlhBarang"];

    setcookie('jlhBarang', '' , time() + (60 * 60), '/');

//    unset($_COOKIE['jlhBarang']);
    echo"<script type='text/javascript'>".
        "alert('Sukses menambahkan ke-keranjang');".
        "document.location='index.php?content=product.php';".
        "</script>";
//    header("location: ");
//}


?>