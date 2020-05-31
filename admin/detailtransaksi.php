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
    echo '</div>';
    ?>
<a id="btnback" href ="index.php?content=<?php echo 'history.php' ?>">Kembali</a>
