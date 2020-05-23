<?php
session_start();
if(!isset($_SESSION['username']))
{
    header('location: ../index.php?content=login.php');
}
if(!isset($_GET['content']))
{
    $vcontent = 'history.php';
}else
{
    $vcontent = $_GET['content'];
}
require("connector.php");

if(!empty($_GET["aksi"])) {
    switch ($_GET["aksi"]) {
        case "update":
            $getStatus = $_GET['status'];
            if ($getStatus==0)
                $getStatus=1;
            elseif ($getStatus==1)
                $getStatus=2;

            //Update status
            $updateStatus = $conn->prepare("UPDATE penjualan SET STATUS = :sts where ID_TRANSAKSI = :id_trx");
            $updateStatus->bindParam(':sts', $getStatus);
            $updateStatus->bindParam(':id_trx', $_GET['kode']);
            try {
                $updateStatus->execute();
                header("location: index.php?content=history.php");
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            break;
    }

}
?>
<?php
        $result = $conn->prepare("SELECT * FROM penjualan");
        $result->execute();
        ?>
    <div class="containercart" style="font-family: "Lato", sans-serif">
    <h2>Riwayat Transaksi</h2>
    <ul class="responsive-table">
        <li class="table-header">
            <div class="col col-1">ID TRX</div>
            <div class="col col-2">TGL</div>
            <div class="col col-3">PEMBELI</div>
            <div class="col col-4">TOTAL BAYAR</div>
            <div class="col col-5">STATUS</div>
        </li>


        <?php
            foreach($result as $row):
                if ($row["STATUS"]==0)
                    $statuz = "BELUM DIBAYAR";
                else if ($row["STATUS"]==1)
                    $statuz = "TELAH DIBAYAR";
                else if ($row["STATUS"]==2)
                    $statuz = "TELAH DIKIRIM";
        ?>
                <form method="post" action="history.php?aksi=update&kode=<?php echo $row['ID_TRANSAKSI']; ?>&status=<?php echo $row['STATUS']; ?>">
            <li class="table-row">
                <div class="col col-1" data-label="ID_TRX"><?php echo $row["ID_TRANSAKSI"]; ?></div>
                <div class="col col-2" data-label="TGL"><?php echo $row["TGL_TRANSAKSI"]; ?></div>
                <div class="col col-3" data-label="PEMBELI"><?php echo $row["NAMA_PEMBELI"]; ?></div>
                <div class="col col-4" data-label="TOTAL BAYAR"><?php echo rupiah($row["TOTAL_PEMBAYARAN"]); ?></div>
                <div class="col col-5" data-label="STATUS BAYAR">
                    <?php
                    if ($row["STATUS"]==0)
                        echo '<input type="submit" value="'.$statuz.'" class="btn btn-danger" name="submit" />';
                    else if ($row["STATUS"]==1)
                        echo '<input type="submit" value="'.$statuz.'" class="btn btn-warning" name="submit" />';
                    else if ($row["STATUS"]==2)
                        echo '<input type="submit" value="'.$statuz.'" class="btn btn-primary" name="submit" />';
                    ?>
<!--                    <input type="submit" value="--><?php //echo $statuz; ?><!--" class="btn btn-danger" name="submit" />-->
                    </form>
                </div>
            </li>
            <?php
            endforeach;
        ?>
    </ul>
    </div>
<!--<a id="btnback" href ="index.php?content=--><?php //echo 'catalog.php' ?><!--">Kembali Belanja</a>-->
<!--<center>-->
<!--    <a id="btncheckout" href ="index.php?content=--><?php //echo 'checkout.php' ?><!--">Checkout</a>-->
<!--</center>-->