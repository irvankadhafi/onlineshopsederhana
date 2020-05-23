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
<?php
if(isset($_SESSION["cart_item"])){
$total_quantity = 0;
$total_price = 0;
?>
<div class="containercart" style="font-family: "Lato", sans-serif">
    <h2>Daftar Isi Keranjang</h2>
    <ul class="responsive-table">
        <li class="table-header">
            <div class="col col-1">Nama</div>
            <div class="col col-2">Jumlah</div>
            <div class="col col-3">Unit Price</div>
            <div class="col col-4">Price</div>
            <div class="col col-5">Action</div>
        </li>
        <?php

            foreach($_SESSION["cart_item"] as $item) :
            $item_price = $item["quantity"]*$item["HARGA_PRODUK"];
            $item_weight = $item["quantity"]*$item["BERAT_PRODUK"];
        ?>
        <li class="table-row">
            <div class="col col-1" data-label="Nama Produk"><?php echo $item["NAMA_PRODUK"]; ?></div>
            <div class="col col-2" data-label="Jumlah Produk"><?php echo $item['quantity']." (".$item_weight."kg)";?></div>
            <div class="col col-3" data-label="Harga perUnit"><?php echo rupiah($item['HARGA_PRODUK']);?></div>
            <div class="col col-4" data-label="Harga"><?php echo rupiah($item_price) ;?></div>
            <div class="col col-5" data-label="Remove"><a href="cartlist.php?aksi=remove&kode=<?php echo $item["KODE_PRODUK"]; ?>" class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></div>
        </li>
                <?php
                $total_quantity += $item["quantity"];
                $total_price += ($item["HARGA_PRODUK"]*$item["quantity"]);
            endforeach;
        ?>
    </ul>
</div>
    <?php
}
?>
<a id="btnEmpty" href="cartlist.php?aksi=empty">Hapus Semua</a>
<a id="btnback" href ="index.php?content=<?php echo 'catalog.php' ?>">Kembali Belanja</a>
<center>
<a id="btncheckout" href ="index.php?content=<?php echo 'checkout.php' ?>">Checkout</a>
</center>