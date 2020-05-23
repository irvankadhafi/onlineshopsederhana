<?php
session_start();
require("connector.php");
?>
<!DOCTYPE html>
<html>
<body>
<div class="container" style="font-family: 'Roboto Condensed', sans-serif;">
    <div class="row">
        <?php
        $result = $conn->prepare("SELECT * FROM produk");
        $result->execute();

        if(!empty($_GET["aksi"])) {
            switch($_GET["aksi"]) {
                case "addcart":
                    if(!empty($_POST["quantity"])) {
                        $stmt = $conn->prepare("SELECT * FROM produk WHERE KODE_PRODUK=:kode");
                        $stmt->bindParam('kode', $_GET['kode']);
                        $stmt->execute();
                        $productSession = $stmt->fetchAll();

                        $itemArray = array(
                            $productSession[0]["KODE_PRODUK"]=>array(
                                'ID_PRODUK'=>$productSession[0]["ID_PRODUK"],
                                'NAMA_PRODUK'=>$productSession[0]["NAMA_PRODUK"],
                                'KODE_PRODUK'=>$productSession[0]["KODE_PRODUK"],
                                'BERAT_PRODUK'=>$productSession[0]["BERAT_PRODUK"],
                                'quantity'=>$_POST["quantity"],
                                'HARGA_PRODUK'=>$productSession[0]["HARGA_PRODUK"],
                                'STOK_PRODUK'=>$productSession[0]["STOK_PRODUK"],
                                'FILE_FOTO'=>$productSession[0]["FILE_FOTO"]));

                        if(!empty($_SESSION["cart_item"])) {
                            if(in_array($productSession[0]["KODE_PRODUK"],array_keys($_SESSION["cart_item"]))) {
                                foreach($_SESSION["cart_item"] as $index => $v) {
                                    if($productSession[0]["KODE_PRODUK"] == $index) {
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
            if ($row['STOK_PRODUK']== 0){
                $ket_stok = "Habis";
            }else{
                $ket_stok = $row[STOK_PRODUK];
            }
            ?>
            <div class="col-lg-3" style="margin-top: 20px">
                <form method="post" action="product.php?aksi=addcart&kode=<?php echo $row['KODE_PRODUK']; ?>">
                    <div class="section bg-white p-1 grid-katalok">
                        <div class="row">
                            <div class="col-lg-12 img-section">
                                <style>
                                    div.gambarproduk{
                                        overflow: hidden;
                                        max-width:100%;
                                        height:200px;
                                        border-radius: 10px;
                                        background-size: 50% 50%;
                                    }
                                </style>
                                <div class="gambarproduk res-ponsive" style="background: url('./img-barang/<?php echo $row['FILE_FOTO']; ?>') no-repeat center center;"></div>
                                <!--                                <img src="./img-barang/--><?php //echo $row['FILE_FOTO']; ?><!--" class="p-0 m-0 res-ponsive">-->
                                <span class="badge add-sens p-2 rounded-0">Stok : <?php echo $ket_stok; ?></span>
                                <span class="badge add-sens2 p-2 rounded-0"><?php echo $row['BERAT_PRODUK']; ?>kg</span>
                            </div>
                            <div class="col-lg-12 section-title">
                                <h1 class="pt-2"><?php echo $row['NAMA_PRODUK']; ?></h1>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <span class="badge badge-success p-2"><?php echo rupiah($row['HARGA_PRODUK']); ?></span>
                                    </div>
                                </div>
                                <!--                                <hr>-->
                            </div>
                            <div class="col-12 pb-2">
                                <div class="row">
                                    <div class="col-lg-12 text-center" style="margin-top: 10px">
                                        <input type="number" name="quantity" value="1" size="4" />  <input type="submit" value="Add to Cart" class="btn btn-danger btn-sm" />
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
</div>
</body>
</html>