<?php
session_start();
require("connector.php");
if(!empty($_GET["aksi"])) {
    switch ($_GET["aksi"]) {
        case "remove":
            if(!empty($_SESSION["cart_item"])) {
                foreach($_SESSION["cart_item"] as $index => $v) {
                    if($_GET["kode"] == $index)
                        unset($_SESSION["cart_item"][$index]);
                    if(empty($_SESSION["cart_item"]))
                        unset($_SESSION["cart_item"]);
                }
            }
            header("location: index.php?content=cartlist.php");
            break;
        case "empty":
            unset($_SESSION["cart_item"]);
            header("location: index.php?content=cartlist.php");
            break;
    }
}
?>
<style>
    table, th, td {
        border-collapse: collapse;
        border: 1px solid black;
        text-align: center;
        margin: auto;
    }
    h1{
        text-align: center;
    }
    .cart-item-image {
        width: 100px;
        height: 100px;
        border: #E0E0E0 1px solid;
        padding: 5px;
        vertical-align: middle;
        margin-right: 15px;
    }
</style>
<h1>Daftar Isi Keranjang</h1>
<a id="btnEmpty" href="cartlist.php?aksi=empty">Hapus Semua</a>
<table width="90%" style="margin:auto;">
    <tr style="background: blue;color:#fff;">
        <th style="text-align:left;">Name</th>
        <th style="text-align:left;">Code</th>
        <th style="text-align:right;" width="5%">Quantity</th>
        <th style="text-align:right;" width="15%">Unit Price</th>
        <th style="text-align:right;" width="15%">Price</th>
        <th style="text-align:center;" width="2%">Remove</th>
    </tr>
    <br>
    <?php

        foreach($_SESSION["cart_item"] as $item) :
            $item_price = $item["quantity"]*$item["harga_produk"];
        ?>
        <tr>
            <td><img width="50" height="50" src="./img-barang/<?php echo $item["file_foto"]; ?>" class="cart-item-image" /><?php echo $item["nama_produk"]; ?></td>
            <td><?php echo $item['kode_produk'];?></td>
            <td><?php echo $item['quantity'];?></td>
            <td><?php echo rupiah($item['harga_produk']);?></td>
            <td><?php echo rupiah($item_price) ;?></td>
            <td><a href="cartlist.php?aksi=remove&kode=<?php echo $item["kode_produk"]; ?>"><button>X</button></a></td>
            </tr>

    <?php
        endforeach;
    ?>
</table>
<hr>
<center><a href ="index.php?content=<?php echo 'product.php' ?>">KEMBALI BELANJA</a> | <a href ="index.php?content=<?php echo 'checkout.php' ?>">CHECKOUT</a> </center>
<?php
//var_dump($_SESSION["cart_item"]);
?>