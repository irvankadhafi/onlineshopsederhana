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
    <style>
        form > div {
            display: flex;
            flex-wrap: wrap;
        }

        form > div > p {
            min-width: 33.33%;
            padding: 10px;
        }

        form p { color: #38ef7d; }

        /* ------------------- */
        /* Form Styles
        /* ----------------- */

        form > div { margin-bottom: 1em; }

        button,
        input,
        optgroup,
        select,
        keygen::-webkit-keygen-select,
        select[_moz-type="-mozilla-keygen"],
        textarea {
            color: inherit;
            font: inherit;
            margin: 0;
            margin-top: 0.5em;
        }

        label {
            display: block;
            margin: 0.75em 0;
            font-weight: bold;
        }
        input {
            line-height: normal;
        }

        textarea { line-height: 1.25em; }
    </style>
<div class= "checkout">
    <h2>Daftar Isi Keranjang</h2>
        <?php

            foreach($_SESSION["cart_item"] as $item) :
            $item_price = $item["quantity"]*$item["HARGA_PRODUK"];
            $item_weight = $item["quantity"]*$item["BERAT_PRODUK"];
        ?>
                <img src="./img-barang/<?php echo $item["FILE_FOTO"]; ?>" class="cart-item-image" />
                <p>
                    <span class= "itemname"><?php echo $item["NAMA_PRODUK"]; ?></span>
                    <br>
                    <?php echo rupiah($item_price); ?>
                    <br>
                    <?php echo $item["quantity"]; ?> pcs
                    <br>
                    @ <?php echo rupiah($item["HARGA_PRODUK"]); ?>
                </p>
                <?php
                $total_quantity += $item["quantity"];
                $total_price += ($item["HARGA_PRODUK"]*$item["quantity"]);
            endforeach;
        ?>
    <p>
        <span class= "itemname">Total Semua : </span>
        <br>
        <?php echo $total_quantity; ?> pcs
        <br>
        <?php echo rupiah($total_price); ?> pcs
    </p>
</div>
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
$pos = $conn->prepare("SELECT * FROM ongkir");
$pos->execute();
?>
<form method="post" action="" enctype="multipart/form-data">
    <div style="margin:auto">
        <p>
            <label>Nama Penerima<br>
                <input type="text" placeholder="Nama Penerima" name="txt_namapenerima">
            </label>
        </p>
        <p>
            <label>Alamat<br>
                <textarea rows="5" cols="40" placeholder="Jl. Ciwaruga No. 48" name="txt_alamatpenerima"></textarea>
            </label>
        </p>
        <p>
            <label>Kode Pos<br>
                <select name="txt_kodepospenerima">
                    <option value="" disabled selected>Select...</option>
                    <?php
                        foreach($pos as $row):
                    ?>
                    <option value="<?php echo $row['KODE_POS_TUJUAN'] ?>"><?php echo $row['KODE_POS_TUJUAN'].' - '.$row['KOTA'] ?></option>
                        <?php
                        endforeach;
                    ?>
                </select>
            </label>
        </p>
        <p>
            <label>Nomor HP<br>
                <input type="tel" placeholder="081927145985" name="txt_hppenerima">
            </label>
        </p>
    </div>
    <center>
        <input type="submit" name="btnsave" value="Proses"></center>
</form>
<?php
error_reporting( ~E_NOTICE ); // avoid notice
if(isset($_POST['btnsave']))
{
    $idTransaksi = $auto_kode;
    $namaPenerima = $_POST['txt_namapenerima'];
    $alamatPenerima = $_POST['txt_alamatpenerima'];
    $kodeposPenerima = $_POST['txt_kodepospenerima'];
    $hpPenerima = $_POST['txt_hppenerima'];
    $tgl = date('Y-m-d H:i:s');

    $i =1;
    $_SESSION['id_transaksi'][$i] = $idTransaksi;
    $_SESSION['id_pos'][$i] = $kodeposPenerima;
    $statusbayar = 0;
    $stmt = $conn->prepare('INSERT INTO penjualan(NAMA_PEMBELI, ALAMAT,POS_TUJUAN , NO_HP, ID_TRANSAKSI, TGL_TRANSAKSI,TOTAL_PEMBAYARAN, STATUS) VALUES(:nama, :alamat,:pos, :hp, :id, :tgl, :total,:sts)');
    $stmt->bindParam(':nama',$namaPenerima);
    $stmt->bindParam(':alamat',$alamatPenerima);
    $stmt->bindParam(':pos',$kodeposPenerima);
    $stmt->bindParam(':hp',$hpPenerima);
    $stmt->bindParam(':id',$idTransaksi);
    $stmt->bindParam(':tgl',$tgl);
    $stmt->bindParam(':sts',$statusbayar);

    $defaulttotal = 0;
    $stmt->bindParam(':total',$defaulttotal);

    try{
        $stmt->execute();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?content=summary.php">';
        exit;
//        header("location:index.php?content=summary.php");
//        echo "new record succesfully inserted ...";
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
