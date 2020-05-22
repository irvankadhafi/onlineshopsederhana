<?php
session_start();
require_once 'connector.php';
if(!isset($_GET['content']))
{
    $vcontent = 'checkout.php';
}else
{
    $vcontent =$_GET['content'];
}
?>
<?php
if(isset($_SESSION["cart_item"])){
    $total_quantity = 0;
    $total_price = 0;
    ?>
    <table width="90%" style="margin:auto;">
        <tbody>
        <tr style="background: blue;color:#fff;">
            <th style="text-align:left;">Name</th>
            <th style="text-align:left;">Code</th>
            <th style="text-align:right;" width="5%">Quantity</th>
            <th style="text-align:right;" width="5%">WEIGHT</th>
            <th style="text-align:right;" width="15%">Unit Price</th>
            <th style="text-align:right;" width="15%">Price</th>
        </tr>
        <br>
        <?php

        foreach($_SESSION["cart_item"] as $item) :
            $item_price = $item["quantity"]*$item["HARGA_PRODUK"];
            $item_weight = $item["quantity"]*$item["BERAT_PRODUK"];
        ?>
            <tr>
                <td><img width="50" height="50" src="./img-barang/<?php echo $item["FILE_FOTO"]; ?>" class="cart-item-image" /><?php echo $item["NAMA_PRODUK"]; ?></td>
                <td><?php echo $item['KODE_PRODUK'];?></td>
                <td><?php echo $item['quantity'];?></td>
                <td><?php echo $item_weight ;?></td>
                <td><?php echo rupiah($item['HARGA_PRODUK']);?></td>
                <td><?php echo rupiah($item_price) ;?></td>
            </tr>
            <?php
            $total_quantity += $item["quantity"];
            $total_price += ($item["HARGA_PRODUK"]*$item["quantity"]);
        endforeach;
        ?>
        <tr>
            <td colspan="2">Total:</td>
            <td colspan="1"><?php echo $total_quantity; ?></td>
            <td colspan="2"><strong><?php echo rupiah($total_price); ?></strong></td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <?php
}
$stmt = $conn->prepare("SELECT * FROM penjualan");
$stmt->execute();
$kode_trx = $stmt->rowCount();
if(!empty($kode_trx)){
    $kode_trx++;
}else{
    $kode_trx=1;
}
$auto_kode = "TRX" .str_pad($kode_trx, 5, "0",  STR_PAD_LEFT);
?>
<hr>
<?php
//foreach($_SESSION['cart_item'] as $index => $value)
//    echo "Var = " . $value['quantity'];
//print_r(array_values($_SESSION['cart_item']));
//print_r(array_values($_SESSION["cart_item"]),BRG002);
?>
<form method="post" action="" enctype="multipart/form-data">
    <table cellpadding="8">
        <tr>
            <td>ID Transaksi</td>
            <td><input type="text" name="txt_transaksi" value="<?php echo $auto_kode ?>" readonly></td>
        </tr>
        <tr>
            <td>Nama Penerima</td>
            <td><input type="text" name="txt_namapenerima"></td>
        </tr>
        <tr>
            <td>Alamat  Penerima</td>
            <td><textarea type="text" rows="5" name="txt_alamatpenerima"></textarea></td>
        </tr>
        <tr>
            <td>Kode Pos</td>
            <td><input type="text" name="txt_kodepospenerima"></td>
        </tr>
        <tr>
            <td>Nomor Telepon</td>
            <td><input type="text" name="txt_hppenerima"></td>
        </tr>

    </table>
    <hr>
    <input type="submit" name="btnsave" value="Final">
<!--    <a href="index.php?content=--><?php //echo 'product.php' ?><!--"><input type="button" value="Batal"></a>-->
</form>
<?php
error_reporting( ~E_NOTICE ); // avoid notice
if(isset($_POST['btnsave']))
{
    $idTransaksi = $_POST['txt_transaksi'];
    $namaPenerima = $_POST['txt_namapenerima'];
    $alamatPenerima = $_POST['txt_alamatpenerima'];
    $kodeposPenerima = $_POST['txt_kodepospenerima'];
    $hpPenerima = $_POST['txt_hppenerima'];
    $tgl = date('Y-m-d');

    $i =1;
    $_SESSION['id_transaksi'][$i] = $idTransaksi;
    $_SESSION['id_pos'][$i] = $kodeposPenerima;

    $stmt = $conn->prepare('INSERT INTO penjualan(NAMA_PEMBELI, ALAMAT, NO_HP, ID_TRANSAKSI, TGL_TRANSAKSI,TOTAL_PEMBAYARAN) VALUES(:nama, :alamat, :hp, :id, :tgl, :total)');
    $stmt->bindParam(':nama',$namaPenerima);
    $stmt->bindParam(':alamat',$alamatPenerima);
    $stmt->bindParam(':hp',$hpPenerima);
    $stmt->bindParam(':id',$idTransaksi);
    $stmt->bindParam(':tgl',$tgl);
    $defaulttotal = 0;
    $stmt->bindParam(':total',$defaulttotal);

    try{
        $stmt->execute();
        echo "new record succesfully inserted ...";
        header("location:index.php?content=summary.php"); // redirects image view page after 5 seconds.
    }catch(PDOException $e) {
        echo $e->getMessage();
    }
//    if($stmt->execute())
//    {
//        $successMSG = "new record succesfully inserted ...";
//        echo "new record succesfully inserted ...";
//        header("location:index.php?content=summary.php"); // redirects image view page after 5 seconds.
//    }
//    else
//        {
//            $errMSG = "error while inserting....";
//        }
}

?>
