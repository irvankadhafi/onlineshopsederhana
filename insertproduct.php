<?php
require_once 'connector.php';
if(!isset($_GET['content']))
{
    $vcontent = 'insertproduct.php';
}else
{
    $vcontent =$_GET['content'];
}
$stmt = $conn->prepare("SELECT * FROM produk");
$stmt->execute();
$kode_faktur = $stmt->rowCount();
?>
<form method="post" action="" enctype="multipart/form-data">
    <?php
    if(!empty($kode_faktur)){
        $kode_faktur++;
    }else{
        $kode_faktur=1;
    }
    $auto_kode = "BRG" .str_pad($kode_faktur, 3, "0",  STR_PAD_LEFT);
    ?>
    <table cellpadding="8">
        <tr>
            <td>KODE PRODUK</td>
            <td><input type="text" name="kode" value="<?php echo $auto_kode ?>" readonly></td>
        </tr>
        <tr>
            <td>NAMA PRODUK</td>
            <td><input type="text" name="nama"></td>
        </tr>
        <tr>
            <td>STOK PRODUK</td>
            <td><input type="number" name="stok"></td>
        </tr>
        <tr>
            <td>BERAT PRODUK</td>
            <td><input type="number" name="berat" step="0.01"> KG</td>
        </tr>
        <tr>
            <td>HARGA PRODUK</td>
            <td><input type="text" name="harga"></td>
        </tr>
        <tr>
            <td>Foto</td>
            <td><input class="input-group" type="file" name="img-barang" accept="image/*" /></td>
        </tr>
    </table>
    <hr>
    <input type="submit" name="btnsave" value="Simpan">
    <a href="index.php?content=<?php echo 'product.php' ?>"><input type="button" value="Batal"></a>
</form>
<?php
error_reporting( ~E_NOTICE ); // avoid notice
if(isset($_POST['btnsave']))
{
    $kode_faktur++;
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $stok = $_POST['stok'];
    $berat = $_POST['berat'];
    $harga = $_POST['harga'];

    $imgFile = $_FILES['img-barang']['name'];
    $tmp_dir = $_FILES['img-barang']['tmp_name'];
    $imgSize = $_FILES['img-barang']['size'];

        $upload_dir = './img-barang/'; // upload directory

        $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension

        // valid image extensions
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions

        // rename uploading image
        $filename = pathinfo($_FILES['img-barang']['name'], PATHINFO_FILENAME);
//        $userpic = rand(1000,1000000).$filename.".".$imgExt;
        $userpic = $filename."-".rand(1,100).".".$imgExt;

        // allow valid image file formats
        if(in_array($imgExt, $valid_extensions)){
            // Check file size '5MB'
            if($imgSize < 5000000)    {
                move_uploaded_file($tmp_dir,$upload_dir.$userpic);
                chmod($upload_dir.$userpic, 0777);
            }
            else{
                $errMSG = "Sorry, your file is too large.";
            }
        }
        else{
            $errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }


    // if no error occured, continue ....
    if(!isset($errMSG))
    {
        $stmt = $conn->prepare('INSERT INTO produk(KODE_PRODUK, NAMA_PRODUK,STOK_PRODUK, HARGA_PRODUK,BERAT_PRODUK, FILE_FOTO) VALUES(:id, :nama, :stok, :harga, :berat, :foto)');
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
