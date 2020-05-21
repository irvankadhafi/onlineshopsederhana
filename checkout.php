<?php
require_once 'connector.php';
if(!isset($_GET['content']))
{
    $vcontent = 'checkout.php';
}else
{
    $vcontent =$_GET['content'];
}
?>
<hr>
<form method="post" action="" enctype="multipart/form-data">
    <table cellpadding="8">
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
            <td><input type="text" name="txt_kodepospenerima"> KG</td>
        </tr>
        <tr>
            <td>Nomor Telepon</td>
            <td><input type="text" name="txt_hppenerima"></td>
        </tr>

    </table>
    <hr>
    <input type="submit" name="btnsave" value="Final">
    <a href="index.php?content=<?php echo 'product.php' ?>"><input type="button" value="Batal"></a>
</form>
<?php
error_reporting( ~E_NOTICE ); // avoid notice
if(isset($_POST['btnsave']))
{
    $namaPenerima = $_POST['txt_namapenerima'];
    $alamatPenerima = $_POST['txt_alamatpenerima'];
    $kodeposPenerima = $_POST['txt_kodepospenerima'];
    $hpPenerima = $_POST['txt_hppenerima'];

    // if no error occured, continue ....
    if(!isset($errMSG))
    {
        $stmt = $conn->prepare('INSERT INTO produk(kode_produk, nama_produk,stok_produk, harga_produk,berat_produk, file_foto) VALUES(:id, :nama, :stok, :harga, :berat, :foto)');
        $stmt->bindParam(':id',$kode);
        $stmt->bindParam(':nama',$nama);
        $stmt->bindParam(':stok',$stok);
        $stmt->bindParam(':berat',$berat);
        $stmt->bindParam(':harga',$harga);
        $stmt->bindParam(':foto',$userpic);

        if($stmt->execute())
        {
            $successMSG = "new record succesfully inserted ...";
            echo "new record succesfully inserted ...";
            header("location:index.php?content=product.php"); // redirects image view page after 5 seconds.
        }
        else
        {
            $errMSG = "error while inserting....";
        }
    }
}
?>
