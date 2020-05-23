<?php
session_start();
require "connector.php";
$total = 0;
$n = 1;

$total_berat = 0;
//Ambil data dari sessions
$id_transaksi = $_SESSION['id_transaksi'][$n];
$id_pos = $_SESSION['id_pos'][$n];
?>

<div class= "checkout" style="margin: auto">
    <?php
    echo "<center><h2>DATA TRANSAKSI</h2>";
    echo "<hr>";
    echo "ID Transaksi = <b>". $id_transaksi."</b></center><br><br>";
    foreach($_SESSION["cart_item"] as $item){

        $total_quantity = 0;
        $total_price = 0;

        $berat = $item['BERAT_PRODUK'];
        $stok = $item['STOK_PRODUK'];

        $item_price = $item["quantity"]*$item["HARGA_PRODUK"];
        $item_weight = $item["quantity"]*$item["BERAT_PRODUK"];
        $berat = $item['BERAT_PRODUK']*$item['quantity'];
        $total_berat += $berat;
        $subtotal = $item['HARGA_PRODUK']*$item['quantity'];
        $total += $subtotal;
        $sisa_stok = (int)$item['STOK_PRODUK'] - (int)$item['quantity'];

        echo '<img src="./img-barang/'.$item["FILE_FOTO"]. '" class="cart-item-image" />';
        echo '<p>';
        echo '<span class= "itemname">'.$item["NAMA_PRODUK"].'</span>';
        echo '<br>';
        echo rupiah($item_price);
        echo '<br>';
        echo $item["quantity"].' pcs | '.$berat.'kg' ;
        echo '<br>';
        echo '@ '.rupiah($item["HARGA_PRODUK"]);
        echo '</p>';

        //Insert ke Proses Jual
        $prosesJual = $conn->prepare("INSERT INTO proses_jual (ID_TRANSAKSI, ID_PRODUK, HARGA_PRODUK, JUMLAH_PRODUK) VALUES (:id_trx, :id_prd, :harga, :jumlah)");
        $prosesJual->bindParam(':id_trx',$id_transaksi);
        $prosesJual->bindParam(':id_prd',$item['ID_PRODUK']);
        $prosesJual->bindParam(':harga',$item['HARGA_PRODUK']);
        $prosesJual->bindParam(':jumlah',$item['quantity']);
        try{
            $prosesJual->execute();
        }catch(PDOException $e) {
            echo $e->getMessage();
        }

        //Update stok di Barang
        $updateProduk = $conn->prepare("UPDATE produk SET STOK_PRODUK = :stok where ID_PRODUK = :id_prd");
        $updateProduk->bindParam(':stok',$sisa_stok);
        $updateProduk->bindParam(':id_prd',$item['ID_PRODUK']);
            try{
                $updateProduk->execute();
            }catch(PDOException $e) {
                echo $e->getMessage();
            }
        $total_quantity += $item["quantity"];
        $total_price += ($item["HARGA_PRODUK"]*$item["quantity"]);
        }
        echo '<hr><p>';
        echo '<span class= "itemname">Total Berat : </span>';
        echo '<br>';
        echo $total_berat.'kg';
        echo '<br>';
        echo '<span class= "itemname">Total Harga : </span>';
        echo '<br>';
        echo rupiah($total);
        echo '</p><hr>';


        //Update Penjualan
        $updatePenjualan = $conn->prepare('UPDATE penjualan SET TOTAL_PEMBAYARAN = :total where ID_TRANSAKSI = :id_trx');
        $updatePenjualan->bindParam(':total',$total);
        $updatePenjualan->bindParam(':id_trx',$id_transaksi);
        try{
            $updatePenjualan->execute();
        }catch(PDOException $e) {
            echo $e->getMessage();
        }

        //Select Ongkir
        $ongkir = $conn->prepare("SELECT * FROM ongkir where KODE_POS_TUJUAN = :id_pos");
        $ongkir->bindParam(':id_pos',$id_pos);
        try{
            $ongkir->execute();
        }catch(PDOException $e) {
            echo $e->getMessage();
        }

        foreach ($ongkir as $row){
            $pos_tujuan = $row["ID_POS"];
            $kota_tujuan = $row["KOTA"];
            $ongkir = $row["biaya_ongkir"];
        }
        echo '<span class= "itemname">Kode Pos Tujuan : </span>';
        echo '<br>';
        echo $id_pos;
        echo '<br>';
        $berat_sisa = $total_berat;
        $tambahin = 1.0;
        while($berat_sisa > 1){
            $berat_sisa = $berat_sisa - 1;
        }

        if($berat_sisa == 1){
            $ongkir = $ongkir*$total_berat;
        }else if ($berat_sisa > 0.3) {
            $tambahin = $tambahin - $berat_sisa;
            $total_berat = $total_berat + $tambahin;
            $ongkir = $ongkir*$total_berat;
        } else {
            $total_berat = $total_berat - $berat_sisa;
            $ongkir = $ongkir*$total_berat;
        }

        echo '<span class= "itemname">Biaya Ongkir : </span>';
        echo '<br>';
        echo $ongkir;
        echo ' ('.$kota_tujuan.')';
        echo '<br>';

        $total_harga = $total + $ongkir;

        echo '<span class= "itemname">Total Harga + Ongkir : </span>';
        echo '<br>';
        echo $total_harga;
        echo '<br>';
        unset($_SESSION["cart_item"]);
    ?>

</div>