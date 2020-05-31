<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    th, td {
        padding: 5px;
        text-align: left;
    }
    th{
        width: 30%;
    }
</style>
<?php
require("connector.php");
session_start();
if(!isset($_SESSION['username']))
{
    header('location: ../index.php?content=login.php');
}
if(!isset($_GET['content']))
{
    $vcontent = 'detailtransaksi.php';
}else
{
    $vcontent = $_GET['content'];
}

$id_transaksi = $_SESSION['current_trx'];
echo '<div class= "checkout">';
echo "<center><h2>DETAIL TRANSAKSI</h2></center>";
echo "<hr>";

//echo $id_transaksi;
$result = $conn->prepare("SELECT * FROM proses_jual WHERE  ID_TRANSAKSI = :id_trx");
$result->bindParam(':id_trx', $id_transaksi);
$result->execute();

$resultx = $conn->prepare("SELECT * FROM penjualan WHERE  ID_TRANSAKSI = :id_trx");
$resultx->bindParam(':id_trx', $id_transaksi);
$resultx->execute();
$penjualan = $resultx->fetch(PDO::FETCH_ASSOC);
if ($penjualan["STATUS"]==0)
    $statuz = "BELUM DIBAYAR";
else if ($penjualan["STATUS"]==1)
    $statuz = "TELAH DIBAYAR";
else if ($penjualan["STATUS"]==2)
    $statuz = "TELAH DIKIRIM";
?>
<table style="width:60%;margin: auto;margin-bottom: 30px">
    <tr>
        <th colspan="2" style="text-align: center">Informasi Penjualan</th>
    </tr>
    <tr>
        <th>ID Transaksi</th>
        <td><?php echo $id_transaksi ?></td>
    </tr>
    <tr>
        <th>Waktu Transaksi</th>
        <td><?php echo $penjualan['TGL_TRANSAKSI'] ?></td>
    </tr>
    <tr>
        <th>Status Transaksi</th>
        <td><?php echo $statuz ?></td>
    </tr>
    <tr>
        <th colspan="2" style="text-align: center">Detail Pembeli</th>
    </tr>
    <tr>
        <th>Nama Pembeli</th>
        <td><?php echo $penjualan['NAMA_PEMBELI'] ?></td>
    </tr>
    <tr>
        <th>Alamat Pembeli</th>
        <td><?php echo $penjualan['ALAMAT'] ?></td>
    </tr>
    <tr>
        <th>NO HP</th>
        <td><?php echo $penjualan['NO_HP'] ?></td>
    </tr>
</table>
<?php
foreach($result as $row):

    $result2 = $conn->prepare("SELECT * FROM produk WHERE  ID_PRODUK = :id_produk");
    $result2->bindParam(':id_produk', $row["ID_PRODUK"]);
    $result2->execute();
    $produknya = $result2->fetch(PDO::FETCH_ASSOC);

    $item_price = $row["JUMLAH_PRODUK"]*$row["HARGA_PRODUK"];
    $berat = $produknya['BERAT_PRODUK']*$row['JUMLAH_PRODUK'];
    $subtotal = $row['HARGA_PRODUK']*$row['JUMLAH_PRODUK'];
    $total += $subtotal;
    $total_berat += $berat;
    echo '<img src="../img-barang/'.$produknya["FILE_FOTO"]. '" class="cart-item-image" />';
    echo '<p>';
    echo '<span class= "itemname">'.$produknya["NAMA_PRODUK"].'</span>';
    echo '<br>';
    echo rupiah($item_price);
    echo '<br>';
    echo $row["JUMLAH_PRODUK"].' pcs | '.$berat.'kg' ;
    echo '<br>';
    echo '@ '.rupiah($row["HARGA_PRODUK"]);
    echo '</p>';

    endforeach;
    ?>
<?php
    echo '<span class= "itemname">Total Harga Barang : </span>';
    echo '<br>';
    echo rupiah($total);
    echo '<br>';
    //Select Ongkir
    $ongkir = $conn->prepare("SELECT * FROM ongkir where KODE_POS_TUJUAN = :id_pos");
    $ongkir->bindParam(':id_pos',$penjualan['POS_TUJUAN']);
    try{
        $ongkir->execute();
    }catch(PDOException $e) {
        echo $e->getMessage();
    }
    foreach ($ongkir as $row2){
        $pos_tujuan = $row2["KODE_POS_TUJUAN"];
        $kota_tujuan = $row2["KOTA"];
        $ongkir = $row2["biaya_ongkir"];
    }
    echo '<span class= "itemname">Kode Pos Tujuan : </span>';
    echo '<br>';
    echo $pos_tujuan.'('.$kota_tujuan.')';
    echo '<br>';
    echo '<span class= "itemname">Total Berat Barang : </span>';
    echo '<br>';
    echo $total_berat.' kg';
    echo '<br>';

    $berat_sisa = $total_berat;
    $tambahin = 1.0;
    while($berat_sisa > 1){
        $berat_sisa = $berat_sisa - 1;
    }
    if($berat_sisa == 1){
        $ongkir = $ongkir*$total_berat;
    }else if ($berat_sisa > 0.3 || $berat_sisa > 1.3 || $berat_sisa > 2.3) {
        $tambahin = $tambahin - $berat_sisa;
        $total_berat = $total_berat + $tambahin;
        $ongkir = $ongkir*$total_berat;
    } else {
        $total_berat = $total_berat - $berat_sisa;
        $ongkir = $ongkir*$total_berat;
    }
    echo '<span class= "itemname">Biaya Ongkir : </span>';
    echo '<br>';
    echo rupiah($ongkir);
    echo '<br>';

    echo '<span class= "itemname">Total Harga + Ongkir : </span>';
    echo '<br>';
    echo rupiah($total + $ongkir);
    echo '<br>';
    echo '</div>';
    ?>
<a id="btnback" href ="index.php?content=<?php echo 'history.php' ?>">Kembali</a>
