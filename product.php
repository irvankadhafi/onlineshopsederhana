<?php
session_start();
require("connector.php");
if(!isset($_GET['content']))
{
    $vcontent = 'product.php';
}else
{
    $vcontent =$_GET['content'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Irvan's Store</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="custom.css">
</head>
<body>
<div class="container main-section" style="font-family: 'Roboto Condensed', sans-serif;">
    <div class="row">
        <?php
        $result = $conn->prepare("SELECT * FROM produk");
        $result->execute();

        if(!empty($_GET["aksi"])) {
            switch($_GET["aksi"]) {
                case "addcart":
                    if(!empty($_POST["quantity"])) {
                        $stmt = $conn->prepare("SELECT * FROM produk WHERE kode_produk=:kode");
                        $stmt->bindParam('kode', $_GET['kode']);
                        $stmt->execute();
                        $productSession = $stmt->fetchAll();

                        $itemArray = array(
                            $productSession[0]["kode_produk"]=>array(
                                'nama_produk'=>$productSession[0]["nama_produk"],
                                'kode_produk'=>$productSession[0]["kode_produk"],
                                'quantity'=>$_POST["quantity"],
                                'harga_produk'=>$productSession[0]["harga_produk"],
                                'file_foto'=>$productSession[0]["file_foto"]));

                        if(!empty($_SESSION["cart_item"])) {
                            if(in_array($productSession[0]["kode_produk"],array_keys($_SESSION["cart_item"]))) {
                                foreach($_SESSION["cart_item"] as $index => $v) {
                                    if($productSession[0]["kode_produk"] == $index) {
                                        if(empty($_SESSION["cart_item"][$index]["quantity"])) {
                                            $_SESSION["cart_item"][$index]["quantity"] = 0;
                                        }
                                        $_SESSION["cart_item"][$index]["quantity"] += $_POST["quantity"];
                                    }
                                }
                            } else {
                                $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
                            }
                        } else {
                            $_SESSION["cart_item"] = $itemArray;
                        }
                        header("location: index.php?content=cartlist.php");
                    }
                    break;
            }
        }
        foreach($result as $row):
            if ($row['stok_produk']==0){
                $ket_stok = "Habis";
            }else{
                $ket_stok = $row[stok_produk];
            }
            ?>
            <div class="col-lg-3">
                <form method="post" action="product.php?aksi=addcart&kode=<?php echo $row['kode_produk']; ?>">
                    <div class="section border bg-white rounded p-2">
                        <div class="row">
                            <div class="col-lg-12 img-section">
                                <img src="./img-barang/<?php echo $row['file_foto']; ?>" class="p-0 m-0 res-ponsive">
                                <span class="badge badge-danger add-sens p-2 rounded-0">Stok : <?php echo $ket_stok; ?></span>
                            </div>
                            <div class="col-lg-12 sectin-title">
                                <h1 class="pt-2" style="font-size: 12px"><?php echo $row['nama_produk']; ?></h1>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <span class="badge badge-success p-2"><?php echo rupiah($row['harga_produk']); ?></span>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <div class="col-12 pb-2">
                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <input type="text" name="quantity" value="1" size="1" />  <input type="submit" value="Add to Cart" class="btn btn-danger" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <?php
        endforeach;
        ?>
    </div>
</body>
</html>